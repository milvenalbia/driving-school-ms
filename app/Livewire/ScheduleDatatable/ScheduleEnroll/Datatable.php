<?php

namespace App\Livewire\ScheduleDatatable\ScheduleEnroll;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CourseEnrolled;
use App\Models\Schedules;
use App\Models\Students;

class Datatable extends Component
{

    use WithPagination;

    public $sortBy = 'id';
    public $sortDirection = 'asc';
    public $search = '';
    public $perPage = 10; 
    public $schedule_id;

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
            $this->dispatch('open-modal', name: 'view-students');
        }
    }
    
    public function render()
    {

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
            ->with(['student', 'schedule'])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.schedule-datatable.schedule-enroll.datatable', [
            'enrollees' => $enrollees,
        ]);
    }

    public function reset_schedId(){
        if($this->schedule_id){
            $this->schedule_id = 0;
        }
    }

    public function delete_enrollee($enrollees_id){
        $enrollee = CourseEnrolled::where('id', $enrollees_id)->first();

        if ($enrollee) {

            $schedule = Schedules::where('id', $enrollee->schedule_id)->first();

            $student = Students::where('id', $enrollee->student_id)->first();

            if($schedule){
                $schedule->update([
                    'enrolled_student' => $schedule->enrolled_student - 1,
                ]);
            }

            if($student){
                $student->update([
                    'enroll_status' => false,
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
