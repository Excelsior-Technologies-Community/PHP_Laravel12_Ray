<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RayController extends Controller
{
    // Show add form
    public function create()
    {
        ray()->clearAll();
        ray()->showQueries();

        ray('Add Student Page Opened')->green();

        $totalStudents = Student::count();

        ray("Total Students: {$totalStudents}")->blue();

        return view('welcome');
    }

    // Store student
    public function store(Request $request)
    {
        ray($request->all())->label('Form Data');

        $validated = $request->validate([
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|min:10',
            'course' => 'required',
            'status' => 'nullable|boolean'
        ]);

        // Fix checkbox issue
        $validated['status'] = $request->has('status') ? 1 : 0;

        try {
            DB::beginTransaction();

            $student = Student::create($validated);

            Activity::create([
                'student_name' => $student->name,
                'action' => 'Created'
            ]);

            ray($student)->green()->label('Student Created');
            ray()->notify("New Student: {$student->name}");

            DB::commit();

        } catch (\Exception $e) {

            DB::rollback();

            ray()->red($e->getMessage());

            return back()
                ->with('error', 'Failed to add student')
                ->withInput();
        }

        ray()->measure();

        return redirect()
            ->route('student.create')
            ->with('success', 'Student added successfully!');
    }

    // Student list
    public function list()
    {
        $students = Student::orderBy('id', 'asc')->get();

        return $this->loadStudentList($students);
    }

    // Edit page
    public function edit($id)
    {
        $student = Student::findOrFail($id);

        ray($student)->label('Editing Student');

        return view('edit', compact('student'));
    }

    // Update student
    public function update(Request $request, $id)
    {
        ray($request->all())->label('Update Data');

        $validated = $request->validate([
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:students,email,' . $id,
            'phone' => 'nullable|min:10',
            'course' => 'required',
            'status' => 'nullable|boolean'
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;

        $student = Student::findOrFail($id);

        ray($student)->label('Before Update');

        $student->update($validated);

        Activity::create([
            'student_name' => $student->name,
            'action' => 'Updated'
        ]);

        ray($student)->green()->label('After Update');

        return redirect()
            ->route('student.list')
            ->with('success', 'Student updated successfully!');
    }

    // Delete student
    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        Activity::create([
            'student_name' => $student->name,
            'action' => 'Deleted'
        ]);

        ray()->red("Deleted: {$student->name}");

        $student->delete();

        return redirect()
            ->route('student.list')
            ->with('success', 'Student deleted successfully!');
    }

    // Search students
    public function search(Request $request)
    {
        $search = $request->search;

        ray($search)->label('Search');

        $students = Student::where('name', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->orWhere('course', 'LIKE', "%{$search}%")
            ->get();

        return $this->loadStudentList($students);
    }

    // Activity logs page
    public function activity()
    {
        $activities = Activity::latest()->get();

        ray($activities)->label('Activity Logs');

        return view('activity', compact('activities'));
    }

    // Statistics loader
    private function loadStudentList($students)
    {
        $totalStudents = Student::count();
        $activeStudents = Student::where('status', 1)->count();
        $inactiveStudents = Student::where('status', 0)->count();

        ray([
            'Total' => $totalStudents,
            'Active' => $activeStudents,
            'Inactive' => $inactiveStudents
        ])->label('Student Statistics');

        return view('list', compact(
            'students',
            'totalStudents',
            'activeStudents',
            'inactiveStudents'
        ));
    }
}