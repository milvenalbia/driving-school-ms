<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\Students;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\StudentReport;
use Illuminate\Support\Facades\Auth;

class StudentInstructorPages extends Controller
{
    public function show(): View {

        $user = Auth::user();
        
        if(!$user){
            abort(404, 'Resource not found.');
        }
        
        if($user->role === 'student'){
            $current_user = Students::where('user_id', $user->user_id)->first();
            $studentName = $current_user->firstname .' '. $current_user->lastname;
            $students = StudentReport::query()
            ->where('student_id', $current_user->id)
            ->with(['student', 'schedule'])
            ->get();
        }else{
            $current_user = Instructor::where('user_id', $user->user_id)->first();
            
            $students = StudentReport::query()
            ->where('_id', $current_user->id)
            ->with(['student', 'schedule'])
            ->get();

            $studentName = $current_user->firstname .' '. $current_user->lastname;
        }
        

        return view('pages.student-instructor.dashboard', compact('students', 'studentName'));
    }
}
