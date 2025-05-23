<?php

namespace App\Livewire\ReportsDatatable;

use App\Models\Schedules;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;

class ScheduleReports extends Component
{
    use WithPagination;

    public $sortBy = 'id';
    public $sortDirection = 'asc';
    public $search = '';
    public $perPage = 10;

    public $showNotification = false;
    public $showModal = false;

    public $schedule_id = [];
    public $selectAll = false;
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

    // Handle the select all functionality
    public function updatedSelectAll($value)
    {
        if ($value) {
            // Get all schedule IDs from the current query
            $this->schedule_id = $this->getFilteredSchedules()
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->schedule_id = [];
        }
    }

    // Update selectAll when individual items are selected/deselected
    public function updatedScheduleId($value)
    {
        $this->selectAll = count($this->schedule_id) === $this->getFilteredSchedules()->count();
    }

    // Helper method to get filtered schedules query
    private function getFilteredSchedules()
    {
        return Schedules::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('schedule_code', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection);
    }

    // Generate PDF for selected schedules
    public function generatePDF()
    {
        if (empty($this->schedule_id)) {

            session()->flash('error', 'Please select at least one schedule to generate PDF');

            $this->showNotification = true;
            return;
        }

        $schedule_ids = is_array( $this->schedule_id) ?  $this->schedule_id : [ $this->schedule_id];

        $this->dispatch('openScheduleInNewTab', route('generate-schedule-reports', ['ids' => $schedule_ids]));

        // $selectedSchedules = Schedules::whereIn('id', $this->schedule_id)
        // ->with('instructorBy')
        // ->get();

        // $pdf = PDF::loadView('pdf.schedules', [
        //     'schedules' => $selectedSchedules,
        // ]);

        // // Set paper size and orientation
        // $pdf->setPaper('A4', 'portrait');

        // // Optional: Set other PDF properties
        // $pdf->setOption([
        //     'dpi' => 150,
        //     'defaultFont' => 'dejavu sans',
        //     'isHtml5ParserEnabled' => true,
        //     'isRemoteEnabled' => true
        // ]);

        // return response()->streamDownload(function() use ($pdf) {
        //     echo $pdf->output();
        // }, 'course_schedules_report_' . now()->format('Y-m-d_His') . '.pdf');
    }

    // public function generatePDF()
    // {
    //     if (empty($this->schedule_id)) {
    //         session()->flash('error', 'Please select at least one schedule to generate PDF');
    //         $this->showNotification = true;
    //         return;
    //     }

    //     $selectedSchedules = Schedules::whereIn('id', $this->schedule_id)
    //         ->with('instructorBy')
    //         ->get();

    //     $pdf = PDF::loadView('pdf.schedules', [
    //         'schedules' => $selectedSchedules,
    //     ]);

    //     // Set paper size and orientation
    //     $pdf->setPaper('A4', 'portrait');

    //     // Optional: Set other PDF properties
    //     $pdf->setOption([
    //         'dpi' => 150,
    //         'defaultFont' => 'dejavu sans',
    //         'isHtml5ParserEnabled' => true,
    //         'isRemoteEnabled' => true
    //     ]);

    //     // Stream the PDF to the browser for inline viewing
    //     return $pdf->stream('course_schedules_report_' . now()->format('Y-m-d_His') . '.pdf');
    // }


    public function render()
    {
        $schedules = $this->getFilteredSchedules()->paginate($this->perPage);
    
        return view('livewire.reports-datatable.schedule-reports', [
            'schedules' => $schedules,
        ]);
    }
}
