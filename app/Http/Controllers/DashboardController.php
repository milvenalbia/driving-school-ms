<?php

namespace App\Http\Controllers;

use App\Models\CourseEnrolled;
use App\Models\Instructor;
use App\Models\Payment;
use App\Models\Schedules;
use App\Models\StudentReport;
use App\Models\Students;
use Illuminate\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function show(): View {

        $data = [
            'sales' => 0,
            'students' => 0,
            'theoretical_students' => 0,
            'practical_students' => 0,
            'graduated_students' => 0,
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

        $data['graduated_students'] = StudentReport::select('remarks')->where('remarks', true)->count();
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
