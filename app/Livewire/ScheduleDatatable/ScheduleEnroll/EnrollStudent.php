<?php

namespace App\Livewire\ScheduleDatatable\ScheduleEnroll;

use App\Models\CourseEnrolled;
use App\Models\Schedules;
use App\Models\Students;
use Livewire\Component;

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
    public $isPractical = false;
    public int $sessions = 0;

    protected $listeners = ['update_schedule_id'];

    public function updatedSearch()
    {

        $this->suggestions = Students::where('user_id', 'like', '%' . $this->search . '%')
                                ->where('enroll_status', false)
                                ->take(5)
                                ->get(['user_id', 'firstname', 'lastname']);
    }

    public function update_schedule_id($schedule_id)
    {
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
                    'sessions' => ['required']
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
            
            if (!$student->theoretical_test && $schedule->type === 'practical') {
                $this->addError('search', 'The selected student has not passed the theoretical test.');
                return; 
            }

           
            $course = CourseEnrolled::create([
                'student_id' => $student->id,
                'user_id' => $student_id,
                'schedule_id' => $schedule->id,
                'sessions' => $this->sessions ?? 0,
            ]);

    
            if($course){
                $schedule->update([
                    'enrolled_student' => $schedule->enrolled_student + 1,
                ]);

                $student->update([
                    'enroll_status' => true,
                ]);
    
            }

            if($this->isPractical){
                $student->update([
                    'assigned_instructor' => $schedule->instructor,
                ]);
            }
    
    
            $this->dispatch('success_message', 'Student Has Been Enrolled Successfully');
     
            $this->reset();

            $this->isPractical = false;
       
    }

    public function formClose(){
        $this->reset();
        $this->isPractical = false;
        $this->resetValidation();
    }
}
