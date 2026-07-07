<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->search) {
            $query->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('email', 'LIKE', "%{$request->search}%")
                  ->orWhere('course', 'LIKE', "%{$request->search}%");
        }

        if ($request->course && $request->course != 'all') {
            $query->where('course', $request->course);
        }

        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status == 'active' ? 1 : 0);
        }

        $sortField = $request->sort_by ?? 'id';
        $sortDirection = $request->sort_direction ?? 'asc';
        $query->orderBy($sortField, $sortDirection);

        $students = $query->paginate(10);

        $stats = [
            'total' => Student::count(),
            'active' => Student::where('status', 1)->count(),
            'inactive' => Student::where('status', 0)->count(),
        ];

        $courses = Student::select('course')->distinct()->pluck('course');

        return view('students.index', compact('students', 'stats', 'courses'));
    }

    public function create()
    {
        ray()->clearAll();
        ray()->showQueries();
        ray('Create Student Page')->green();

        return view('students.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|min:10|max:15',
            'course' => 'required',
            'status' => 'nullable|boolean',
            'profile_image' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['status'] = $request->has('status') ? 1 : 0;

        if (empty($data['profile_image'])) {
            $data['profile_image'] = 'students/default-avatar.png';
        }

        $student = Student::create($data);

        Activity::create([
            'student_name' => $student->name,
            'action' => 'Created',
            'details' => [
                'email' => $student->email,
                'course' => $student->course
            ]
        ]);

        ray()->green("✅ Student Created: {$student->name}");

        return response()->json([
            'success' => true,
            'message' => 'Student created successfully!',
            'student' => $student
        ]);
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:students,email,' . $id,
            'phone' => 'nullable|min:10|max:15',
            'course' => 'required',
            'status' => 'nullable|boolean',
            'profile_image' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Handle image
        if (!empty($data['profile_image']) && $data['profile_image'] != $student->profile_image) {
            if ($student->profile_image && $student->profile_image != 'students/default-avatar.png') {
                Storage::disk('public')->delete($student->profile_image);
            }
        }

        $student->update($data);

        Activity::create([
            'student_name' => $student->name,
            'action' => 'Updated',
            'details' => [
                'changes' => $student->getChanges()
            ]
        ]);

        ray()->blue("🔄 Student Updated: {$student->name}");

        return response()->json([
            'success' => true,
            'message' => 'Student updated successfully!',
            'student' => $student
        ]);
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $name = $student->name;

        if ($student->profile_image && $student->profile_image != 'students/default-avatar.png') {
            Storage::disk('public')->delete($student->profile_image);
        }

        $student->delete();

        Activity::create([
            'student_name' => $name,
            'action' => 'Deleted',
            'details' => ['deleted_at' => now()->toDateTimeString()]
        ]);

        ray()->red("🗑️ Student Deleted: {$name}");

        return response()->json([
            'success' => true,
            'message' => 'Student deleted successfully!'
        ]);
    }

    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:students,id',
            'action' => 'required|in:active,inactive,delete'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request'
            ], 422);
        }

        $students = Student::whereIn('id', $request->ids);

        if ($request->action == 'delete') {
            foreach ($students->get() as $student) {
                if ($student->profile_image && $student->profile_image != 'students/default-avatar.png') {
                    Storage::disk('public')->delete($student->profile_image);
                }
            }
            $students->delete();
            $message = count($request->ids) . ' students deleted successfully!';
        } else {
            $status = $request->action == 'active' ? 1 : 0;
            $students->update(['status' => $status]);
            $message = count($request->ids) . ' students updated successfully!';
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function export()
    {
        $students = Student::all();
        $filename = 'students_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['ID', 'Name', 'Email', 'Phone', 'Course', 'Status', 'Created At']);

        foreach ($students as $student) {
            fputcsv($handle, [
                $student->id,
                $student->name,
                $student->email,
                $student->phone,
                $student->course,
                $student->status ? 'Active' : 'Inactive',
                $student->created_at
            ]);
        }

        fclose($handle);
        exit;
    }

    public function activity()
    {
        $activities = Activity::latest()->paginate(20);
        
        ray()->table($activities->toArray());
        ray()->notify("📋 Activity Logs Loaded");

        return view('students.activity', compact('activities'));
    }

    public function uploadImage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '', $file->getClientOriginalName());
                $path = $file->storeAs('students', $filename, 'public');

                ray()->green("📸 Image Uploaded: " . $filename);

                return response()->json([
                    'success' => true,
                    'path' => $path,
                    'url' => asset('storage/' . $path)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No file uploaded'
            ], 400);

        } catch (\Exception $e) {
            ray()->red("❌ Upload Failed: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
}