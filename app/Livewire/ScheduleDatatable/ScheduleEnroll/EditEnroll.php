<?php

namespace App\Livewire\ScheduleDatatable\ScheduleEnroll;

use Carbon\Carbon;
use App\Models\Vehicle;
use Livewire\Component;
use App\Models\Students;
use App\Models\Schedules;
use App\Models\CourseEnrolled;

class EditEnroll extends Component
{

    public $hours;
    public $sessions;
    public $grade;
    public $start_date;
    public $end_date;
    public $transmission_type;
    public $vehicle_type;
    public $remarks;
    public $vehicle_id;
    public $vehicle_id_copy;
    public $vehicles = [];
    public int $enrollee_id = 0;
    public $isPractical = false;
    protected $listeners = ['update_enrollee'];

    public function render()
    {
        return view('livewire.schedule-datatable.schedule-enroll.edit-enroll');
    }


    public function update_enrollee($enrollee_id)
    {
        if(!$enrollee_id){
            return;
        }

        $this->enrollee_id = $enrollee_id;

        $enrollee = CourseEnrolled::where('id', $enrollee_id)->first();

        $this->isPractical = $enrollee->schedule->type === 'practical' ? true : false;

        $this->sessions = $enrollee->sessions ?? 0;
        $this->hours = $enrollee->hours ?? '';
        $this->vehicle_id = $enrollee->vehicle_id ?? '';
        $this->vehicle_id_copy = $enrollee->vehicle_id ?? '';
        $this->vehicle_type = $enrollee->vehicle->type ?? '';
        $this->transmission_type = $enrollee->vehicle->transmission_type ?? '';
        $this->start_date = $enrollee->start_date ?? '';
        $this->grade = $enrollee->grade ?? '';
        $this->filterVehicles('');

        $this->dispatch('open-modal', name: 'edit-enroll-student');
        
    }

    public function updatedDays(){
        $this->hours = '';
        $this->filterVehicles('Message');
    }

    public function updated($property)
    {
        if (in_array($property, ['start_date','vehicle_type', 'transmission_type'])) {
            $this->filterVehicles('');
        }
    }

    public function filterVehicles($message)
    {
        if($this->vehicle_id && !$this->vehicle_id_copy && !$message){
            $this->vehicle_id = '';
        }

        if ($this->sessions > 0 && !empty($this->start_date) && !empty($this->vehicle_type) && !empty($this->transmission_type)) {

            $start_date = Carbon::parse($this->start_date)->format('Y-m-d');
            
            $this->sessions = (int)$this->sessions;
            $end_date = Carbon::parse($this->start_date)->addDays($this->sessions)->subDay()->format('Y-m-d');

            $this->vehicles = Vehicle::query()
                ->where('type', $this->vehicle_type)
                ->where('transmission_type', $this->transmission_type)
                ->where(function($q) use ($end_date, $start_date) {
                    $q->where('status', 'good')
                    ->orWhere(function($subQuery) use ($end_date, $start_date) {
                        $subQuery->where('status', 'in_use')
                                ->where(function($query) use ($end_date, $start_date) {
                                    $query->whereDate('start_date', '>', $end_date)
                                            ->orWhereDate('end_date', '<', $start_date);
                                });
                    });
                })
                ->get(['id', 'brand', 'license_plate']);

        //     $this->vehicles = Vehicle::query()
        //         ->where('type', $this->vehicle_type)
        //         ->where('transmission_type', $this->transmission_type)
        //         ->where(function($q) use ($end_date, $start_date) {
        //             $q->where('status', 'good')
        //               ->orWhere(function($subQuery) use ($end_date, $start_date) {
        //                   $subQuery->where('status', 'in_use')
        //                            ->whereDate('start_date', '<=', $end_date)
        //                            ->orWhereDate('end_date', '>=', $start_date);
        //               });
        //         })
        //         ->get(['id', 'brand', 'license_plate']);
        } else {
            $this->vehicles = [];
        }
        

    }

    public function edit_enroll_student()
    {
        $validationRules = [
            'vehicle_id' => $this->isPractical ? 'required' : 'nullable',
            'start_date' => $this->isPractical ? 'required' : 'nullable',
            'vehicle_type' => $this->isPractical ? 'required' : 'nullable',
            'transmission_type' => $this->isPractical ? 'required' : 'nullable',
            'sessions' => $this->isPractical ? 'required' : 'nullable',
            'hours' => $this->isPractical ? 'required' : 'nullable',
        ];
        
        $validated = $this->validate($validationRules);

        // Determine remarks based on grade
        $remarks = $this->grade ? ($this->grade >= 75 ? 'passed' : 'failed') : null;

        // Fetch the course enrollee record
        $course = CourseEnrolled::where('id', $this->enrollee_id)->with('schedule')->first();

        if ($course) {
            $courseData = [
                'grade' => $this->grade ? $this->grade : null,
                'remarks' => $remarks,
            ];

            $end_date = Carbon::parse($this->start_date)->addDays($this->sessions)->subDay()->format('Y-m-d');

            $start_date = Carbon::parse($this->start_date)->format('Y-m-d');
            if ($this->isPractical) {
                $courseData = array_merge($courseData, [
                    'start_date' => $start_date,
                    'vehicle_id' => $validated['vehicle_id'],
                    'sessions' => $validated['sessions'],
                    'hours' => $validated['hours'],
                ]);
            }

            $course->update($courseData);

            // Fetch the student record
            $student = Students::where('id', $course->student_id)->first();

            if($this->vehicle_id){
                $vehicle = Vehicle::where('id', $this->vehicle_id)->first();

                $end_date = Carbon::parse($validated['start_date'])->addDays($this->sessions)->subDay();

                $vehicle->update([
                    'status' => 'in_use',
                    'start_date' => $validated['start_date'],
                    'end_date' => $end_date,
                ]);
            }

            if ($student) {
                $student->update([
                    'enroll_status' => false,
                    $this->isPractical ? 'practical_test' : 'theoretical_test' => $remarks ? ($remarks === 'passed' ? true : false) : null,
                ]);
            }

            // Dispatch success message and reset component state
            $this->dispatch('success_message_enroll', 'Enrollee Course Updated Successfully');
            $this->reset();
        }
    }


    public function formClose(){
        $this->reset();
        $this->resetValidation();

        $this->dispatch('close-modal', name: 'edit-enroll-student');

        $this->dispatch('open-modal', name: 'view-students');
    }
}
