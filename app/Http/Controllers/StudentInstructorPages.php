<?php

namespace App\Http\Controllers;

use App\Models\Students;
use App\Models\Schedules;
use Illuminate\View\View;
use App\Models\Instructor;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\Models\StudentReport;
use App\Models\CourseEnrolled;
use Illuminate\Support\Facades\Auth;

class StudentInstructorPages extends Controller
{

    public function show(): View {

        $user = Auth::user();
        $schedules = 0;
        $student_count = 0;
        $instructor_count = 0;
        $theoretical_students = [];
        $practical_students = [];
        $students = [];
        $studentName = '';
        
        if(!$user){
            abort(404, 'Resource not found.');
        }
        
        if($user->role === 'student'){

            $current_user = Students::where('user_id', $user->user_id)->first();

            $studentName = $current_user->firstname .' '. $current_user->lastname;

            $schedules = Schedules::where('isDone', false)->count();

            $instructor_count = Instructor::count();

            $theoretical_students = CourseEnrolled::select('student_id', 'schedule_id')
                ->where('student_id', $current_user->id) // Use whereIn
                ->whereHas('schedule', function ($q) {
                    $q->where('isDone', false)
                        ->where('type', 'theoretical');
                })
                ->with('student', 'schedule')
                ->get();

            $practical_students = CourseEnrolled::select('student_id', 'schedule_id', 'start_date')
                ->where('student_id', $current_user->id) // Use whereIn
                ->whereHas('schedule', function ($q) {
                    $q->where('isDone', false)
                        ->where('type', 'practical');
                })
                ->with('student', 'schedule')
                ->get();

            $students = StudentReport::query()
            ->where('student_id', $current_user->id)
            ->with(['student', 'schedule'])
            ->get();

        }else{
            $current_user = Instructor::where('user_id', $user->user_id)->first();
            
            $theoretical_schdeule_id = Schedules::where('instructor', $current_user->id)
                ->where('type', 'theoretical')
                ->where('isDone', false)
                ->pluck('id');

            $practical_schdeule_id = Schedules::where('instructor', $current_user->id)
                ->where('type', 'practical')
                ->where('isDone', false)
                ->pluck('id');

            $schdeule_id = Schedules::where('instructor', $current_user->id)
                ->where('isDone', false)
                ->pluck('id');

            $student_count = CourseEnrolled::whereIn('schedule_id', $schdeule_id)->count();

            $schedules = Schedules::where('instructor', $current_user->id)
            ->count();

            $theoretical_students = CourseEnrolled::select('student_id', 'schedule_id', 'course_attendance', 'remarks', 'created_at')
                ->whereIn('schedule_id', $theoretical_schdeule_id) // Use whereIn
                ->with('student', 'schedule')
                ->take(5)
                ->orderBy('created_at', 'desc')
                ->get();

            $practical_students = CourseEnrolled::select('student_id', 'schedule_id', 'course_attendance', 'start_date', 'remarks','created_at')
                ->whereIn('schedule_id', $practical_schdeule_id) // Use whereIn
                ->with('student', 'schedule')
                ->take(5)
                ->orderBy('created_at', 'desc')
                ->get();

            $studentName = $current_user->firstname .' '. $current_user->lastname;
        }
        

        return view('pages.student-instructor.dashboard', compact('students', 'studentName', 'instructor_count', 'student_count', 'theoretical_students', 'practical_students','schedules'));
    }

    public function showStudentReports(): View {

        $user = Auth::user();
        $students = [];
        
        if(!$user){
            abort(404, 'Resource not found.');
        }

        $current_user = Students::where('user_id', $user->user_id)->first();


        $students = StudentRecord::query()
        ->select('schedule_id', 'grade', 'type', 'course_enrolled_id', 'remarks')
        ->where('student_id', $current_user->id)
        ->with(['schedule'])
        ->get();

        return view('pages.student-instructor.student-reports', compact('students'));
    }

}
