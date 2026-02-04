<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;

Route::get('/', function () {
    return view('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/app', function () {
    return view('app');
})->middleware('auth');;

Route::get('/employees', [EmployeeController::class, 'index'])->name('employees');



Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');