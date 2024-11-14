<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    
    public function showStudentReports(): View {
        return view('pages.reports.student-reports');
    }

    public function showScheduleReports(): View {
        return view('pages.reports.schedule-reports');
    }
    
}
