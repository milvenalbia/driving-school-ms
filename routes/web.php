<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\ScheduleContronller;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::redirect('/', 'dashboard');

Route::view('profile', 'pages.profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard',[DashboardController::class, 'show']
    )->name('dashboard');
    
    Route::get('/users',[UserController::class, 'show']
    )->name('users');

    Route::get('/instructors',[InstructorController::class, 'show']
    )->name('instructors');

    Route::get('/students',[StudentController::class, 'show']
    )->name('students');

    Route::get('/schedules',[ScheduleContronller::class, 'show']
    )->name('schedules');

    Route::get('/student-reports',[StudentReportController::class, 'show']
    )->name('student-reports');
});

require __DIR__.'/auth.php';
