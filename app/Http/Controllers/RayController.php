<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class RayController extends Controller
{
    // FORM PAGE
    public function create()
    {
        ray()->clearAll(); // Clear old logs

        ray('📄 Open Add Student Page')->green();

        return view('welcome');
    }

    // STORE DATA
    public function store(Request $request)
    {
        // 🔵 Show form input
        ray($request->all())->blue()->label('Form Data');

        // 🔴 Validation
        $validated = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email'
        ]);

        // 🟢 Show validated data
        ray($validated)->green()->label('Validated Data');

        // 🟠 Insert into DB
        $student = Student::create($validated);

        ray($student)->orange()->label('Inserted Student');

        // 🟣 Show queries
        ray()->showQueries();

        // ⚡ Performance
        ray()->measure();

        return redirect('/')->with('success', '✅ Student Added Successfully!');
    }

    // LIST PAGE
    public function list()
    {
        $students = Student::all();

        ray($students)->purple()->label('Student List Data');

        return view('list', compact('students'));
    }
}