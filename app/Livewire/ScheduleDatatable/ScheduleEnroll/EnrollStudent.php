<?php

namespace App\Livewire\ScheduleDatatable\ScheduleEnroll;

use App\Models\Payment;
use Livewire\Component;
use App\Models\Students;
use App\Models\Schedules;
use App\Models\StudentRecord;
use App\Models\StudentReport;
use App\Models\CourseEnrolled;
use Illuminate\Support\Facades\Auth;

class EnrollStudent extends Component
{
    public function render()
    {
        return view('livewire.schedule-datatable.schedule-enroll.enroll-student');
    }

    public $search = '';
    public $suggestions = [];
    public $schedule_id;
    public $student_id;
    public $student_user_id;
    public $isPractical = false;
    public int $sessions = 0;

    protected $listeners = ['update_schedule_id'];

    public function updatedSearch()
    {

        $this->suggestions = Students::where(function ($query) {
                            $query->where('user_id', 'like', '%' . $this->search . '%')
                                    ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ['%' . $this->search . '%']);
                                })
                                ->where('enroll_status', false)
                                ->get(['id','user_id', 'firstname', 'lastname']);
                                
                                // ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ['%' . $this->search . '%'])
    }

    public function update_schedule_id($schedule_id)
    {
        $user = Auth::user();

        if($user->role === "student"){
            $this->student_id = $user->user_id;
            $this->search = $user->user_id;
        }
        
        if($schedule_id){
            $this->schedule_id = $schedule_id;

            $schedule = Schedules::where('id', $schedule_id)->first();

            $this->isPractical = $schedule->type === 'practical' ? true : false;

            $this->dispatch('open-modal', name: 'enroll-student');
        }
        
    }

    public function enroll_student()
    {
            if($this->isPractical){
                $validated = $this->validate([
                    'search' => ['required', 'string', 'max:255'],
                    'sessions' => ['required', 'integer', 'max:4', 'min:1']
                ]);
            }else{
                $validated = $this->validate([
                    'search' => ['required', 'string', 'max:255'],
                ]);
            }
            
            $student_id = $this->student_id;
        
            $student = Students::where('user_id', $student_id)->first();
            $schedule = Schedules::where('id', $this->schedule_id)->first();


            if (!$student) {
                $this->addError('search', 'The selected student does not exist.');
                return;
            }

            if ($student->theoretical_test && $schedule->type === 'theoretical') {
                $this->addError('search', 'The selected student already completed the theoretical exam.');
                return; 
            }

            if (!$student->theoretical_test && $schedule->type === 'practical') {
                $this->addError('search', 'The selected student has not completed theoretical classes.');
                return; 
            }

            if ($student->practical_test && $schedule->type === 'practical') {
                $this->addError('search', 'The selected student already completed the practical exam.');
                return; 
            }
           
            $course = CourseEnrolled::create([
                'student_id' => $student->id,
                'user_id' => $student_id,
                'schedule_id' => $schedule->id,
                'sessions' => $this->sessions ?? 0,
            ]);

            if($course){

                $this->student_user_id = $student->id;
                $schedule->update([
                    'enrolled_student' => $schedule->enrolled_student + 1,
                ]);

                $student->update([
                    'enroll_status' => true,
                ]);

                $paymentCount = Payment::whereDate('created_at', now()->format('Y-m-d'))->count();

                $sequentialNumber = $paymentCount;
                    
                $invoice_code = 'INV-' . now()->format('mdY') . str_pad($sequentialNumber, 3, '0', STR_PAD_LEFT);

                Payment::create([
                    'invoice_code' => $invoice_code,
                    'course_enrolled_id' => $course->id,
                    'student_id' => $student->id,
                    'schedule_id' => $schedule->id,
                    'paid_amount' => 0,
                    'balance' => $schedule->amount,
                    'status' => 'unpaid',
                ]);

                $this->updateStudentReports();
            }

            // if($this->isPractical){
            //     $student->update([
            //         'assigned_instructor' => $schedule->instructor,
            //     ]);
            // }
    
    
            $this->dispatch('success_message', 'Student Has Been Enrolled Successfully');
     
            $this->reset();

            $this->isPractical = false;
       
    }

    public function updateStudentReports(){

        $course = CourseEnrolled::where('student_id', $this->student_user_id)
        ->where('schedule_id', $this->schedule_id)
        ->first();

        if($course){
            StudentRecord::create([
                'student_id' => $course->student_id,
                'schedule_id' => $course->schedule_id,
                'instructor_id' => $course->schedule->instructor,
                'course_enrolled_id' => $course->id,
                'type' => $course->schedule->type,
            ]);
        }

        if(!$this->isPractical){

            StudentReport::create([
                'student_id' => $course->student_id,
                'schedule_id' => $course->schedule_id,
                'instructor_id' => $course->schedule->instructor,
            ]);
        }else{
            $students = StudentReport::query()
            ->whereHas('schedule', function ($query) {
                $query->where('isDone', false);
            })
            ->where('student_id', $this->student_user_id)
            ->whereNotNull('theoritical_grade')
            ->whereNotNull('practical_grade')
            ->with('schedule')
            ->first();

            if(!$students){
                StudentReport::create([
                    'student_id' => $course->student_id,
                    'schedule_id' => $course->schedule_id,
                    'instructor_id' => $course->schedule->instructor,
                ]);
            }
        }
       
        
        
    }

    public function formClose(){
        $this->reset();
        $this->isPractical = false;
        $this->resetValidation();
    }
}
