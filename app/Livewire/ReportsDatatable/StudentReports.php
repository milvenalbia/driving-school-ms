<?php

namespace App\Livewire\ReportsDatatable;

use App\Models\StudentReport;
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
        $students = StudentReport::query()
            ->when($this->search, function ($query) {
                $query->WhereHas('student', function ($query) {
                        $query->where('firstname', 'like', '%' . $this->search . '%')
                            ->orWhere('lastname', 'like', '%' . $this->search . '%')
                            ->orWhere('user_id', 'like', '%' .$this->search . '%')
                            ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ['%' . $this->search . '%']);
                    });
            })
            ->with(['student', 'schedule'])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    
        return view('livewire.reports-datatable.student-reports', [
            'students' => $students,
        ]);
    }
}
