<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/', [StudentController::class, 'index'])->name('student.index');
Route::get('/students', [StudentController::class, 'index'])->name('student.index');
Route::get('/students/create', [StudentController::class, 'create'])->name('student.create');
Route::post('/students', [StudentController::class, 'store'])->name('student.store');
Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('student.edit');
Route::put('/students/{id}', [StudentController::class, 'update'])->name('student.update');
Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('student.destroy');
Route::post('/students/bulk', [StudentController::class, 'bulkAction'])->name('student.bulk');
Route::post('/students/upload', [StudentController::class, 'uploadImage'])->name('student.upload');
Route::get('/students/export', [StudentController::class, 'export'])->name('student.export');
Route::get('/activity', [StudentController::class, 'activity'])->name('student.activity');