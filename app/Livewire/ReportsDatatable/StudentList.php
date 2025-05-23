<?php

namespace App\Livewire\ReportsDatatable;

use App\Models\Students;
use Livewire\Component;
use Livewire\WithPagination;

class StudentList extends Component
{

    use WithPagination;

    public $sortBy = 'id';
    public $sortDirection = 'desc';
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
        'sortDirection' => ['except' => 'desc'],
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

        // return Students::select('id', 'user_id', 'firstname', 'lastname', 'email', 'phone_number', 'gender', 'civil_status', 'image_path', 'theoretical_test', 'practical_test')
        // ->when($this->search, function ($query) {
        //     $query->where('firstname', 'like', '%' . $this->search . '%')
        //         ->orWhere('lastname', 'like', '%' . $this->search . '%')
        //         ->orWhere('user_id', 'like', '%' .$this->search . '%')
        //         ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ['%' . $this->search . '%']
        //     );
        // })
        // ->orderBy($this->sortBy, $this->sortDirection);

        return Students::with('student_records') // Eager-load student_records
        ->select(
            'students.id', 
            'students.user_id', 
            'students.firstname', 
            'students.lastname', 
            'students.email', 
            'students.phone_number', 
            'students.gender', 
            'students.civil_status', 
            'students.image_path', 
            'students.theoretical_test', 
            'students.practical_test'
        )
        ->when($this->search, function ($query) {
            $query->where('students.firstname', 'like', '%' . $this->search . '%')
                ->orWhere('students.lastname', 'like', '%' . $this->search . '%')
                ->orWhere('students.user_id', 'like', '%' . $this->search . '%')
                ->orWhereRaw("CONCAT(students.firstname, ' ', students.lastname) LIKE ?", ['%' . $this->search . '%']);
        })
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

        $this->dispatch('openStudentListNewTab', route('generate-student-list-reports', ['ids' => $student_ids]));
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
    
        return view('livewire.reports-datatable.student-list', [
            'students' => $students,
        ]);
        
    }
}
