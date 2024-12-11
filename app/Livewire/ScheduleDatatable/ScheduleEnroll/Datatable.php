<?php

namespace App\Livewire\ScheduleDatatable\ScheduleEnroll;

use App\Models\Vehicle;
use Livewire\Component;
use App\Models\Students;
use App\Models\Schedules;
use App\Models\Instructor;
use Livewire\WithPagination;
use App\Models\CourseEnrolled;
use App\Models\VehicleScheduling;
use Illuminate\Support\Facades\Auth;

class Datatable extends Component
{

    use WithPagination;

    public $sortBy = 'id';
    public $sortDirection = 'asc';
    public $search = '';
    public $perPage = 10; 
    public $schedule_id;
    public $attendance = [];
    public $activeDay = [];  
    public $enrollee;
    public $day1_status = 'absent';
    public $day2_status = 'absent';
    public $day3_status = 'absent';
    public $type = '';

    public $showNotification = false;
    protected $listeners = ['sortField', 'success_message', 'view_students'];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function success_message($message)
    {
        session()->flash('success', $message);

        $this->dispatch('close-modal');

        $this->showNotification = true;

    }

    public function sortField($field, $direction)
    {
        $this->sortBy = $field;
        $this->sortDirection = $direction;
    }

    public function view_students($schedule_id){
        if($schedule_id){
            $this->schedule_id = $schedule_id;
            $schedule = Schedules::where('id', $schedule_id)->first();

            $this->type = $schedule->type;

            $this->dispatch('open-modal', name: 'view-students');
        }
    }

    public function edit_enroll($course_id){
        if($course_id){
            $this->schedule_id = $course_id;
            $this->dispatch('open-modal', name: 'view-students');
        }
    }
    
    public function render()
    {

        $user = Auth::user();

        if($user->role === 'instructor'){

            $enrollees = CourseEnrolled::query()
                ->where('schedule_id', $this->schedule_id)
                ->when($this->search, function ($query) {
                    $query->where('user_id', 'like', '%' . $this->search . '%')
                        ->orWhereHas('student', function ($query) {
                            $query->where('firstname', 'like', '%' . $this->search . '%')
                                ->orWhere('lastname', 'like', '%' . $this->search . '%')
                                ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ['%' . $this->search . '%']);
                        });
                })
                ->with(['student', 'schedule','payments'])
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage);

            return view('livewire.schedule-datatable.schedule-enroll.datatable', [
                'enrollees' => $enrollees,
            ]);

        }elseif($user->role === 'student'){
        
            $student_id = Students::where('user_id', $user->user_id)->pluck('id');

            $enrollees = CourseEnrolled::query()
                ->where('student_id', $student_id)
                ->with(['student', 'schedule','payments'])
                ->orderBy($this->sortBy, $this->sortDirection)
                ->get();

            return view('livewire.schedule-datatable.schedule-enroll.datatable', [
                'enrollees' => $enrollees,
            ]);

        }else{
            $enrollees = CourseEnrolled::query()
                ->where('schedule_id', $this->schedule_id)
                ->when($this->search, function ($query) {
                    $query->where('user_id', 'like', '%' . $this->search . '%')
                        ->orWhereHas('student', function ($query) {
                            $query->where('firstname', 'like', '%' . $this->search . '%')
                                ->orWhere('lastname', 'like', '%' . $this->search . '%')
                                ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ['%' . $this->search . '%']);
                        });
                })
                ->with(['student', 'schedule','payments'])
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage);

            return view('livewire.schedule-datatable.schedule-enroll.datatable', [
                'enrollees' => $enrollees,
            ]);
        }
    }

    public function reset_schedId(){
        if($this->schedule_id){
            $this->schedule_id = 0;
        }
    }

    public function setAttendance($enrollee_id, $day)
    {

        if(!$enrollee_id){
            return;
        }

        $course = CourseEnrolled::where('id', $enrollee_id)->first();

        $statusProperty = "day{$day}_status";

        $status = (($course->$statusProperty ?? 'absent') === 'present') ? 'absent' : 'present';
        
        $course->update([
            $statusProperty => $status,
        ]);

        session()->flash('success', 'Status updated successfully!');
        $this->showNotification = true;
        
        $this->calculateAttendance($course);
    }

    private function calculateAttendance($course)
    {
        $day1 = (($course->day1_status ?? 'absent') === 'present') ? 1 : 0;
        $day2 = (($course->day2_status ?? 'absent') === 'present') ? 1 : 0;
        $day3 = (($course->day3_status ?? 'absent') === 'present') ? 1 : 0;

        $day = $day1 + $day2 + $day3;

        $course->update([
            'course_attendance' => $day,
        ]);
    }
    
    public function edit_enrollee($enrollee_id){

        $user = Auth::user();

        if($enrollee_id){

                $this->dispatch('close-modal', name: 'view_students');

                $this->dispatch('update_enrollee', $enrollee_id);

            // if($user->role === 'instructor'){
            //     $this->dispatch('update_enrollee', $enrollee_id);
            // }else{
            //     $this->dispatch('close-modal', name: 'view_students');

            //     $this->dispatch('update_enrollee', $enrollee_id);
            // }
                
        }
    }

    public function delete_enrollee($enrollees_id){
        $enrollee = CourseEnrolled::where('id', $enrollees_id)->first();

        if ($enrollee) {

            $schedule = Schedules::where('id', $enrollee->schedule_id)->first();

            $student = Students::where('id', $enrollee->student_id)->first();

            $vehicle_schedule = VehicleScheduling::where('course_enrolled_id', $enrollee->id)->first();

            if($vehicle_schedule ){
                $vehicle_schedule->delete();
            }

            if($enrollee->vehicle_id){
                $hasNewUse = Vehicle::where('id', $enrollee->vehicle_id)
                    ->whereHas('vehicleSchedules', function($scheduleQuery) {
                        $scheduleQuery->where('use_status', 'new_use');
                    })
                    ->exists(); // This will return true if at least one record exists
            
                // Update status if there are no 'new_use' schedules
                if (!$hasNewUse) {
                    Vehicle::where('id', $enrollee->vehicle_id)
                        ->update(['status' => 'good']);
                }
            }
            

            if($schedule){
                $schedule->update([
                    'enrolled_student' => $schedule->enrolled_student - 1,
                ]);
            }

            if($student){
                $student->update([
                    'enroll_status' => false,
                    'theoretical_test' => null,
                    'practical_test' => null
                ]);
            }
            
            $enrollee->delete();

            session()->flash('success', 'Student has been removed successfully');
            $this->showNotification = true;
        } else {
            session()->flash('error', 'Not found.');
            $this->showNotification = true;
        }
    }

    
}
