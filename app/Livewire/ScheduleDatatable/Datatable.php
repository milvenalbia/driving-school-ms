<?php

namespace App\Livewire\ScheduleDatatable;

use App\Models\Schedules;
use Livewire\Component;
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

    public function edit_schedule($schedule_id){
        $this->dispatch('edit_schedule', $schedule_id);
    }

    public function delete_schedule($schedule_id)
    {
 
        $schedule = Schedules::where('id', $schedule_id)->first();

        $schedule->delete();

        session()->flash('success', 'Schedule has been deleted successfully');

        $this->showNotification = true;
    }
}
