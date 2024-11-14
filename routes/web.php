<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleContronller;
use App\Http\Controllers\InstructorController;

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

    Route::get('/student-reports',[ReportsController::class, 'showStudentReports']
    )->name('student-reports');

    Route::get('/schedule-reports',[ReportsController::class, 'showScheduleReports']
    )->name('schedule-reports');

    Route::get('/vehicles',[VehicleController::class, 'show']
    )->name('vehicles');
    
    Route::get('/payments',[PaymentController::class, 'show']
    )->name('payments');
});

require __DIR__.'/auth.php';
