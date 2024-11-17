<?php

namespace App\Livewire\ScheduleDatatable\ScheduleEnroll;

use Carbon\Carbon;
use App\Models\Vehicle;
use Livewire\Component;
use App\Models\Students;
use App\Models\Schedules;
use App\Models\CourseEnrolled;
use App\Models\StudentReport;
use App\Models\VehicleScheduling;

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

    public $student_id;
    public $schedule_id;
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

        $this->student_id = $enrollee->student_id ?? 0;
        $this->schedule_id = $enrollee->schedule_id ?? 0;
        $this->filterVehicles('');

        $this->dispatch('open-modal', name: 'edit-enroll-student');
        
    }

    public function updatedSessions(){
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
            $this->sessions = (int) $this->sessions;
            $end_date = Carbon::parse($this->start_date)->addDays($this->sessions)->subDay()->format('Y-m-d');

            $this->vehicles = Vehicle::query()
                ->where('type', $this->vehicle_type)
                ->where('transmission_type', $this->transmission_type)
                ->where(function ($q) use ($start_date, $end_date) {
                    // Include vehicles with a 'good' status or specific vehicle_id
                    $q->where(function ($subQuery) {
                        $subQuery->where('status', 'good')
                            ->orWhere('id', $this->vehicle_id_copy); // Always include this vehicle_id
                    })
                    // For vehicles with 'in_use' status, check availability in scheduling
                    ->orWhere(function ($subQuery) use ($start_date, $end_date) {
                        $subQuery->where('status', 'in_use')
                            ->where(function ($conflictQuery) use ($start_date, $end_date) {
                                // Check if there is any overlap in schedules
                                $conflictQuery->where('id', '!=', $this->vehicle_id_copy) // Exclude specific vehicle ID from conflict checking
                                    ->whereNotExists(function ($subquery) use ($start_date, $end_date) {
                                        $subquery->selectRaw(1)
                                            ->from('vehicle_schedulings')
                                            ->whereColumn('vehicle_schedulings.vehicle_id', 'vehicles.id')
                                            ->where('use_status', 'new_use')
                                            ->where(function ($q) use ($start_date, $end_date) {
                                                // Ensure no overlap in schedules by checking full range
                                                $q->where(function ($q) use ($start_date, $end_date) {
                                                    $q->where('start_date', '<=', $end_date)
                                                    ->where('end_date', '>=', $start_date);
                                                });
                                            });
                                    });
                            });
                    });
                })
                ->with('vehicleSchedules')
                ->get(['id', 'brand', 'license_plate']);

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

            if ($this->vehicle_id != $this->vehicle_id_copy) {
                // Check if there are any vehicle schedules with 'new_use' status
                $hasNewUse = Vehicle::where('id', $this->vehicle_id_copy)
                    ->whereHas('vehicleSchedules', function($scheduleQuery) {
                        $scheduleQuery->where('use_status', 'new_use');
                    })
                    ->with('vehicleSchedules')
                    ->exists(); // This will return true if at least one record exists
            
                // Update status if there are no 'new_use' schedules
                if (!$hasNewUse) {
                    Vehicle::where('id', $this->vehicle_id_copy)
                        ->update(['status' => 'good']);
                }
            }

            if($this->vehicle_id){
                $vehicle = Vehicle::where('id', $this->vehicle_id)->first();

                $end_date = Carbon::parse($validated['start_date'])->addDays($this->sessions)->subDay();

                $vehicle->update([
                    'status' => 'in_use',
                ]);

                $vehicles_schedule = VehicleScheduling::where('course_enrolled_id', $this->enrollee_id)->first();

                if($vehicles_schedule){
                    $vehicles_schedule->update([
                        'vehicle_id' => $this->vehicle_id,
                        'start_date' => $validated['start_date'],
                        'end_date' => $end_date,
                        'use_status' => 'new_use'
                    ]);
                }else{
                    VehicleScheduling::create([
                        'course_enrolled_id' => $this->enrollee_id,
                        'vehicle_id' => $this->vehicle_id,
                        'start_date' => $validated['start_date'],
                        'end_date' => $end_date,
                        'use_status' => 'new_use'
                    ]);
                }

            }

            if ($student) {
                $student->update([
                    'enroll_status' => false,
                    $this->isPractical ? 'practical_test' : 'theoretical_test' => $remarks ? ($remarks === 'passed' ? true : false) : null,
                ]);
            }

            if($this->grade){
                $this->updateStudentReports();
            }

            // Dispatch success message and reset component state
            $this->dispatch('success_message_enroll', 'Enrollee Course Updated Successfully');
            $this->reset();
        }
    }

    public function updateStudentReports(){

        $course = CourseEnrolled::where('student_id', $this->student_id)->first();

        $students = StudentReport::query()
                ->where('student_id', $this->student_id)
                ->first();

        if($students && $this->isPractical){

            $remarks = $students->theoritical_grade > 74 && $this->grade > 74 ? true : false;
            
            $students->update([
                'practical_grade' => $this->grade,
                'hours' => 8,
                'remarks' => $remarks,
            ]);

        }elseif($students && !$this->isPractical){
            $students->update([
                'theoritical_grade' => $this->grade,
            ]);
        }
        else{

            StudentReport::create([
                'student_id' => $course->student_id,
                'schedule_id' => $course->schedule_id,
                'instructor_id' => $course->schedule->instructor,
                'theoritical_grade' => $this->grade,
            ]);
        
        }
    }


    public function formClose(){
        $this->reset();
        $this->resetValidation();

        $this->vehicle_id_copy = '';

        $this->dispatch('close-modal', name: 'edit-enroll-student');

        $this->dispatch('open-modal', name: 'view-students');
    }
}
