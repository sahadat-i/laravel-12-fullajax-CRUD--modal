<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LestureController;
use App\Http\Controllers\StudentsController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/lecture', [LestureController::class, 'index']);
Route::post('/lecture', [LestureController::class, 'store'])->name('lecture.store');
Route::get('/lecture/edit/{id}', [LestureController::class, 'edit']);
Route::put('/lecture/{id}', [LestureController::class, 'update'])->name('lecture.update');
Route::get('/lecture-serverside', [LestureController::class, 'serverside']);
Route::get('/lecture/json', [LestureController::class, 'json'])->name('lecture.json');
Route::delete('/lecture/{id}', [LestureController::class, 'destroy']);
Route::get('/students',[StudentsController::class, 'index'])->name('students.index');
Route::get('/students/json',[StudentsController::class, 'json'])->name('students.json');
Route::post('/students',[StudentsController::class, 'store'])->name('students.store');
Route::get('/students/edit/{id}', [StudentsController::class, 'edit']);