<?php

namespace App\Livewire\StudentDatatable;

use App\Models\User;
use Livewire\Component;
use App\Models\Students;
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
        $students = Students::query()
            ->when($this->search, function ($query) {
                    $query->where('firstname', 'like', '%' . $this->search . '%')
                          ->orWhere('lastname', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    
        return view('livewire.student-datatable.datatable', [
            'students' => $students,
        ]);
    }

    public function edit_student($student_id){
        $this->dispatch('edit_student', $student_id);
    }

    public function delete_student($student_id)
    {
 
        $student = Students::where('id', $student_id)->first();

        $user = User::where('user_id', $student->user_id)->first();

        if($user){
            $user->delete();
        }

        $student->delete();

        session()->flash('success', 'Student has been deleted successfully');

        $this->showNotification = true;
    }
}
