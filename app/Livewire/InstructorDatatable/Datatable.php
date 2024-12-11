<?php

namespace App\Livewire\InstructorDatatable;

use App\Models\User;
use Livewire\Component;
use App\Models\Instructor;
use App\Models\Schedules;
use Livewire\WithPagination;

class Datatable extends Component
{
    use WithPagination;

    public $sortBy = 'id';
    public $sortDirection = 'asc';
    public $search = '';
    public $perPage = 10;

    public $showNotification = false;
    public $showModal = false;
    protected $listeners = ['sortField', 'success_message'];

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

    public function render()
    {
        $instructors = Instructor::query()
            ->when($this->search, function ($query) {
                    $query->where('firstname', 'like', '%' . $this->search . '%')
                          ->orWhere('lastname', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    
        return view('livewire.instructor-datatable.datatable', [
            'instructors' => $instructors,
        ]);
    }

    public function edit_instructor($instructor_id){
        $this->dispatch('edit_instructor', $instructor_id);
    }

    public function delete_instructor($instructor_id)
    {
 
        $instructor = Instructor::where('id', $instructor_id)->first();

        $user = User::where('user_id', $instructor->user_id)->first();

        $relatedSchedule = Schedules::where('instructor', $instructor_id)->exists();

        if ($relatedSchedule) {
            session()->flash('error', 'Cannot be deleted, the instrutor has beed assigned to a schedule.');
            $this->showNotification = true;
            return;
        }

        if($user){
            $user->delete();
        }

        $instructor->delete();

        session()->flash('success', 'Instructor has been deleted successfully');

        $this->showNotification = true;
    }
}
