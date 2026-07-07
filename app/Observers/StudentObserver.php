<?php

namespace App\Observers;

use App\Models\Student;
use App\Models\Activity;

class StudentObserver
{
    public function created(Student $student)
    {
        Activity::create([
            'student_name' => $student->name,
            'action' => 'Created',
            'details' => [
                'email' => $student->email,
                'course' => $student->course
            ]
        ]);

        ray()->green("✅ Student Created: {$student->name}");
        ray()->notify("New Student Added: {$student->name}");
    }

    public function updated(Student $student)
    {
        Activity::create([
            'student_name' => $student->name,
            'action' => 'Updated',
            'details' => [
                'changes' => $student->getChanges()
            ]
        ]);

        ray()->blue("🔄 Student Updated: {$student->name}");
        ray()->table($student->getChanges());
    }

    public function deleted(Student $student)
    {
        Activity::create([
            'student_name' => $student->name,
            'action' => 'Deleted',
            'details' => [
                'deleted_at' => now()->toDateTimeString()
            ]
        ]);

        ray()->red("🗑️ Student Deleted: {$student->name}");
    }

    public function restored(Student $student)
    {
        ray()->green("♻️ Student Restored: {$student->name}");
    }

    public function forceDeleted(Student $student)
    {
        ray()->red("💥 Student Permanently Deleted: {$student->name}");
    }
}