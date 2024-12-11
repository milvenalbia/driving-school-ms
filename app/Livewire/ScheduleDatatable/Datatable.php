<?php

namespace App\Livewire\ScheduleDatatable;

use Livewire\Component;
use App\Models\Students;
use App\Models\Schedules;
use App\Models\Instructor;
use Livewire\WithPagination;
use App\Models\StudentReport;
use App\Models\CourseEnrolled;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class Datatable extends Component
{
    use WithPagination;

    public $activeDay = 'Day 1';
    public $sortBy = 'id';
    public $sortDirection = 'asc';
    public $search = '';
    public $perPage = 10;

    public $showNotification = false;
    public $showModal = false;
    public $user_student_id = 0;
    protected $listeners = ['sortField', 'success_message', 'success_message_enroll'];

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

    public function success_message_enroll($message)
    {
        session()->flash('success', $message);

        $this->dispatch('close-modal', name: 'edit-enroll-student');

        $this->dispatch('open-modal', name: 'view-students');

        $this->showNotification = true;

    }

    public function sortField($field, $direction)
    {
        $this->sortBy = $field;
        $this->sortDirection = $direction;
    }

    public function render()
    {
        $user = Auth::user();
        
        if($user->role === 'instructor'){

            $intructor_id = Instructor::where('user_id', $user->user_id)->pluck('id');
            $schedules = Schedules::query()
                ->whereIn('instructor', $intructor_id)
                ->when($this->search, function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('schedule_code', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage);
        
            return view('livewire.schedule-datatable.datatable', [
                'schedules' => $schedules,
            ]);
        }else{
            $schedules = Schedules::query()
                ->when($this->search, function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('schedule_code', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage);
    
            return view('livewire.schedule-datatable.datatable', [
                'schedules' => $schedules,
            ]);
        }
        
    }

    public function toggleScheduleDone($scheduleId)
        {
            $schedule = Schedules::findOrFail($scheduleId);
            $schedule->update([
                'isDone' => !$schedule->isDone,
            ]);

            $instructor = Instructor::where('id', $schedule->instructor)->first();

            $instructor->update(
                [
                    'hasSchedule' => $schedule->isDone ? false : true,
                ]
            );
            
            session()->flash('success', 'Updated Successfully');

            $this->showNotification = true;
        }

    public function view_students($schedule_id){
        $this->dispatch('view_students', $schedule_id);
    }

    public function edit_schedule($schedule_id){
        $this->dispatch('edit_schedule', $schedule_id);
    }

    public function enroll_student($schedule_id){
        $this->dispatch('update_schedule_id', $schedule_id);
    }

    public function delete_schedule($schedule_id)
    {
       
        $schedule = Schedules::where('id', $schedule_id)->first();

        if ($schedule) {

            $instructor = Instructor::where('id', $schedule->instructor)->first();

            $instructor->update(
                [
                    'hasSchedule' => false,
                ]
            );
            
            $enrolledStudents = CourseEnrolled::where('schedule_id', $schedule->id)->count();

            if ($enrolledStudents > 0) {
                // Notify the user that there are students enrolled and ask for confirmation
                session()->flash('error', 'This schedule has enrolled students. Preventing deletion of record.');
                $this->showNotification = true;
                return;
            }
            
            $schedule->delete();

            session()->flash('success', 'Schedule has been deleted successfully');
            $this->showNotification = true;
        } else {
            session()->flash('error', 'Schedule not found.');
            $this->showNotification = true;
        }
    }
}
