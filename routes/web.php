<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RayController;

Route::get('/', [RayController::class, 'create']);
Route::post('/store', [RayController::class, 'store'])->name('student.store');
Route::get('/students', [RayController::class, 'list'])->name('student.list');
