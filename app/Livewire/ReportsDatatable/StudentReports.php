<?php

namespace App\Livewire\ReportsDatatable;

use Livewire\Component;
use Livewire\WithPagination;

class StudentReports extends Component
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

    public function sortField($field, $direction)
    {
        $this->sortBy = $field;
        $this->sortDirection = $direction;
    }

    public function render()
    {
        // $schedules = Schedules::query()
        //     ->when($this->search, function ($query) {
        //             $query->where('name', 'like', '%' . $this->search . '%')
        //                   ->orWhere('schedule_code', 'like', '%' . $this->search . '%');
        //     })
        //     ->orderBy($this->sortBy, $this->sortDirection)
        //     ->paginate($this->perPage);
        $schedules = [];
    
        return view('livewire.reports-datatable.student-reports', [
            'schedules' => $schedules,
        ]);
    }
}
