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

    protected $listeners = ['update_schedule_id'];

    public function updatedSearch()
    {
        $enrolledStudentIds = CourseEnrolled::where('schedule_id', $this->schedule_id)
        ->pluck('student_id');

        $this->suggestions = Students::where('user_id', 'like', '%' . $this->search . '%')
                                ->whereNotIn('user_id', $enrolledStudentIds)
                                ->take(5)
                                ->get(['user_id', 'firstname', 'lastname']);
    }

    public function update_schedule_id($schedule_id)
    {
        if($schedule_id){
            $this->schedule_id = $schedule_id;

            $this->dispatch('open-modal', name: 'enroll-student');
        }
        
    }

    public function enroll_student()
    {

            $validated = $this->validate([
                'search' => ['required', 'string', 'max:255'],
            ]);
    
            $student_id = $this->student_id;
        
            $student = Students::where('user_id', $student_id)->first();
            $schedule = Schedules::where('id', $this->schedule_id)->first();
    
            if (!$student) {
                $this->addError('search', 'The selected student does not exist.');
                return;
            } 
            
            if (!$student->course_completed && $schedule->type === 'theoretical') {
                $this->addError('search', 'The selected student has not yet completed any course lesson.');
                return;
            } 
            
            if (!$student->theoretical_test && $schedule->type === 'practical') {
                $this->addError('search', 'The selected student has not passed the theoretical test.');
                return; 
            }

            $course = CourseEnrolled::create([
                'student_id' => $student_id,
                'schedule_id' => $schedule->id,
            ]);
    
            if($course){
                $schedule->update([
                    'enrolled_student' => $schedule->enrolled_student + 1,
                ]);
            }
    
    
            $this->dispatch('success_message', 'Student Has Been Created Successfully');
     
            $this->reset();
       
    }

    public function formClose(){
        $this->reset();
        $this->resetValidation();
    }
}
