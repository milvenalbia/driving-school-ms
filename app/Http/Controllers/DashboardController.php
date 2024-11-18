<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Students;
use App\Models\Schedules;
use Illuminate\View\View;
use App\Models\Instructor;
use Illuminate\Http\Request;
use App\Models\StudentReport;
use App\Models\CourseEnrolled;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function show(): View {

        $user = Auth::user();
        
        $data = [
            'sales' => 0,
            'students' => 0,
            'theoretical_students' => 0,
            'practical_students' => 0,
            'passed_students' => 0,
            'failed_students' => 0,
            'instructors' => 0,
            'schedules' => 0,
        ];

        $data['instructors'] = Instructor::count();
        $data['students'] = Students::count();
        $data['schedules'] = Schedules::count();
        $data['theoretical_students'] = CourseEnrolled::whereHas('schedule', function ($query) {
            $query->where('type', 'theoretical');
        })
        ->with('schedule')
        ->count();

        $data['practical_students'] = CourseEnrolled::whereHas('schedule', function ($query) {
            $query->where('type', 'practical');
        })
        ->with('schedule')
        ->count();

        $data['passed_students'] = StudentReport::select('remarks')->where('remarks', true)->count();
        $data['failed_students'] = StudentReport::select('remarks')->where('remarks', false)->count();
        $data['sales'] = Payment::sum('paid_amount');

        if ($data['sales'] >= 1000000000) {
            $formattedSales = '₱' . number_format($data['sales'] / 1000000000, 1) . 'B'; // Billions
        } elseif ($data['sales'] >= 100000000) {
            $formattedSales = '₱' . number_format($data['sales'] / 1000000, 1) . 'M';    // Millions
        } else {
            $formattedSales = '₱' . number_format($data['sales']);                      // Less than 100,000
        }

        $data['sales'] = $formattedSales;

        return view('pages.dashboard', compact('data'));
        
    }

}
