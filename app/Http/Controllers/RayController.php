<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class RayController extends Controller
{
    // Show form page
    public function create()
    {
        ray()->clearAll();
        ray()->showQueries();
        ray('Add Student Page Opened')->green();
        
        $totalStudents = Student::count();
        ray("Total Students: {$totalStudents}")->blue();
        
        return view('welcome');
    }

    // Store student data
    public function store(Request $request)
    {
        ray($request->all())->label('Form Data');
        
        $validated = $request->validate([
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|min:10',
            'course' => 'required',
            'status' => 'boolean'
        ]);
        
        ray($validated)->green()->label('Validated Data');
        
        try {
            DB::beginTransaction();
            $student = Student::create($validated);
            ray($student)->label('Student Created');
            DB::commit();
            
            ray()->green("Student {$student->name} added!");
            ray()->notify("New Student: {$student->name}");
            
        } catch (\Exception $e) {
            DB::rollback();
            ray()->red("Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add student!')->withInput();
        }
        
        ray()->showQueries();
        ray()->measure();
        
        return redirect()->route('student.create')->with('success', 'Student added successfully!');
    }

    // Show all students list
    public function list()
    {
        ray()->showQueries();
        $students = Student::latest()->get();
        ray($students)->label('All Students');
        ray()->count($students);
        
        return view('list', compact('students'));
    }
    
    // Show edit form
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
            'status' => 'boolean'
        ]);
        
        $student = Student::findOrFail($id);
        ray($student)->label('Before Update');
        
        $student->update($validated);
        ray($student)->label('After Update');
        
        ray()->green("Student {$student->name} updated!");
        
        return redirect()->route('student.list')->with('success', 'Student updated successfully!');
    }
    
    // Delete student
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $studentName = $student->name;
        
        ray()->orange("Deleting: {$studentName}");
        $student->delete();
        ray()->red("Deleted: {$studentName}");
        
        return redirect()->route('student.list')->with('success', 'Student deleted successfully!');
    }
    
    // Search students
    public function search(Request $request)
    {
        $searchTerm = $request->get('search');
        ray($searchTerm)->label('Search Term');
        
        $students = Student::where('name', 'LIKE', "%{$searchTerm}%")
                         ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                         ->orWhere('course', 'LIKE', "%{$searchTerm}%")
                         ->get();
        
        ray($students)->label('Search Results');
        ray()->count($students);
        
        if($students->isEmpty()) {
            ray()->orange('No results found!');
        }
        
        return view('list', compact('students'));
    }
}