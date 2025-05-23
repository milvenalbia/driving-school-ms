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
use App\Livewire\ReportsDatatable\StudentReports;

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

Route::get('/student-page-reports', [StudentInstructorPages::class, 'showStudentReports']
)->name('student-page-reports')->middleware(['auth', 'student']);

Route::view('profile', 'pages.profile')
    ->middleware(['auth', 'studentInstructor'])
    ->name('profile');

Route::get('/student-reports',[ReportsController::class, 'showStudentReports']
)->name('student-reports')->middleware(['auth', 'instructor']);

Route::get('/generate-student-reports',[ReportsController::class, 'student_pdf']
)->name('generate-student-reports')->middleware(['auth', 'instructor']);

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard',[DashboardController::class, 'show']
    )->name('dashboard');
    
    // Route::get('/users',[UserController::class, 'show']
    // )->name('users');

    Route::get('/instructors',[InstructorController::class, 'show']
    )->name('instructors');

    Route::get('/students',[StudentController::class, 'show']
    )->name('students');

    Route::get('/schedule-reports',[ReportsController::class, 'showScheduleReports']
    )->name('schedule-reports');

    Route::get('/generate-schedule-reports',[ReportsController::class, 'schedule_pdf'])
    ->name('generate-schedule-reports');

    Route::get('/vehicles',[VehicleController::class, 'show']
    )->name('vehicles');
    
    Route::get('/payments',[PaymentController::class, 'show']
    )->name('payments');

    Route::get('/payment-reports',[ReportsController::class, 'showPaymentReports']
    )->name('payment-reports');

    Route::get('/generate-payment-reports',[ReportsController::class, 'payment_pdf'])
    ->name('generate-payment-reports');

    Route::get('/student-list',[ReportsController::class, 'showStudentList']
    )->name('student-list');

    Route::get('/generate-student-list-reports',[ReportsController::class, 'student_list_pdf'])
    ->name('generate-student-list-reports');

    Route::get('/generate-certificate/{user_id}/{id}',[ReportsController::class, 'generateCertificate']
    )->name('generate-certificate');

    Route::get('/generate-invoice',[ReportsController::class, 'invoice_pdf']
    )->name('generate-invoice');

    Route::get('/theoretical-records',[ReportsController::class, 'showTheoreticalStudents']
    )->name('theoretical-records');

    Route::get('/practical-records',[ReportsController::class, 'showPracticalStudents']
    )->name('practical-records');

    Route::get('/student-certificates',[ReportsController::class, 'showStudentCertificate']
    )->name('student-certificates');

    Route::get('/generate-student-certificate/{user_id}/{id}',[ReportsController::class, 'studentCertificate']
    )->name('generate-student-certificate');

    Route::get('/daily-sales',[ReportsController::class, 'showDailySales']
    )->name('daily-sales');

    Route::get('/generate-daily-sales-reports',[ReportsController::class, 'daily_sales_pdf'])
    ->name('generate-daily-sales-reports');

});

Route::middleware(['auth'])->group(function () {

    Route::get('/schedules',[ScheduleContronller::class, 'show']
    )->name('schedules');

    Route::get('/student-dashboard',[StudentInstructorPages::class, 'show']
    )->name('student-dashboard');
});

require __DIR__.'/auth.php';
