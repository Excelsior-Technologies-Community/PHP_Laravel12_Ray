<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RayController;
// all routes 
Route::get('/', [RayController::class, 'create'])->name('student.create');
Route::post('/store', [RayController::class, 'store'])->name('student.store');
Route::get('/students', [RayController::class, 'list'])->name('student.list');
Route::get('/students/{id}/edit', [RayController::class, 'edit'])->name('student.edit');
Route::put('/students/{id}', [RayController::class, 'update'])->name('student.update');
Route::delete('/students/{id}', [RayController::class, 'destroy'])->name('student.destroy');
Route::get('/students/search', [RayController::class, 'search'])->name('student.search');

Route::get('/activity',[RayController::class,'activity'])->name('activity');