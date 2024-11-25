<?php

namespace App\Livewire\ReportsDatatable;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Instructor;
use Livewire\WithPagination;
use App\Models\StudentReport;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class StudentReports extends Component
{

    use WithPagination;

    public $sortBy = 'id';
    public $sortDirection = 'asc';
    public $search = '';
    public $perPage = 10;

    public $showNotification = false;
    public $showModal = false;

    public $selectAll = false;
    public $student_id = [];
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
      
            $this->student_id = $this->getFilteredStudents()
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->student_id = [];
        }
    }

    // Update selectAll when individual items are selected/deselected
    public function updatedStudentId($value)
    {
        $this->selectAll = count($this->student_id) === $this->getFilteredStudents()->count();
    }

    private function getFilteredStudents()
    {
        $user = Auth::user();

        $instructor_id = Instructor::where('user_id', $user->user_id)->pluck('id');

        if($user->role === "instructor"){
            return StudentReport::query()
            ->whereHas('schedule', function ($q) use ($instructor_id){
                $q->where('instructor', $instructor_id);
            })
            ->when($this->search, function ($query) {
                $query->WhereHas('student', function ($query) {
                        $query->where('firstname', 'like', '%' . $this->search . '%')
                            ->orWhere('lastname', 'like', '%' . $this->search . '%')
                            ->orWhere('user_id', 'like', '%' .$this->search . '%')
                            ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ['%' . $this->search . '%']);
                    });
            })
            ->with(['student', 'schedule'])
            ->orderBy($this->sortBy, $this->sortDirection);
        }

        return StudentReport::query()
        ->when($this->search, function ($query) {
            $query->WhereHas('student', function ($query) {
                    $query->where('firstname', 'like', '%' . $this->search . '%')
                        ->orWhere('lastname', 'like', '%' . $this->search . '%')
                        ->orWhere('user_id', 'like', '%' .$this->search . '%')
                        ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ['%' . $this->search . '%']);
                });
        })
        ->with(['student', 'schedule'])
        ->orderBy($this->sortBy, $this->sortDirection);
    
    }

    public function generatePDF()
    {
        if (empty($this->student_id)) {

            session()->flash('error', 'Please select at least one student to generate PDF');

            $this->showNotification = true;
            return;
        }

        $student_ids = is_array($this->student_id) ? $this->student_id : [$this->student_id];

        $this->dispatch('openInNewTab', route('generate-student-reports', ['ids' => $student_ids]));
        // return redirect()->route('generate-student-reports', ['ids' => $student_ids]);

        // $selectedStudents = StudentReport::whereIn('id', $this->student_id)
        // ->with(['student', 'schedule'])
        // ->get();

        // $pdf = PDF::loadView('pdf.students', [
        //     'students' => $selectedStudents,
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

        // return $pdf->stream('course_students_report_' . now()->format('Y-m-d_His') . '.pdf');
        

        // return response()->streamDownload(function() use ($pdf) {
        //     echo $pdf->output();
        // }, 'course_students_report_' . now()->format('Y-m-d_His') . '.pdf');
    }

    public function render()
    {
        $students = $this->getFilteredStudents()->paginate($this->perPage);
    
        return view('livewire.reports-datatable.student-reports', [
            'students' => $students,
        ]);
    }
}
