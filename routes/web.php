<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleContronller;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\StudentInstructorPages;

Route::get('/', function () {
    // Get the authenticated user
    $user = Auth::user();
    
    // Check if the user is an admin
    if ($user && $user->role === 'admin') {
        return redirect('dashboard');
    }

    // If the user is not an admin, redirect to 'student-dashboard'
    return redirect('student-dashboard');
});

Route::view('profile', 'pages.profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard',[DashboardController::class, 'show']
    )->name('dashboard');
    
    Route::get('/users',[UserController::class, 'show']
    )->name('users');

    Route::get('/instructors',[InstructorController::class, 'show']
    )->name('instructors');

    Route::get('/students',[StudentController::class, 'show']
    )->name('students');

    Route::get('/student-reports',[ReportsController::class, 'showStudentReports']
    )->name('student-reports');

    Route::get('/schedule-reports',[ReportsController::class, 'showScheduleReports']
    )->name('schedule-reports');

    Route::get('/vehicles',[VehicleController::class, 'show']
    )->name('vehicles');
    
    Route::get('/payments',[PaymentController::class, 'show']
    )->name('payments');

});

Route::middleware(['auth'])->group(function () {

    Route::get('/schedules',[ScheduleContronller::class, 'show']
    )->name('schedules');

    Route::get('/student-dashboard',[StudentInstructorPages::class, 'show']
    )->name('student-dashboard');
});

require __DIR__.'/auth.php';
