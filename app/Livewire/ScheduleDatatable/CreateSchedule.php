<?php

namespace App\Livewire\ScheduleDatatable;

use Livewire\Component;
use App\Models\Instructor;
use App\Models\Schedules;
use Illuminate\Support\Facades\Auth;

class CreateSchedule extends Component
{
    public $schedule_id = 0;
    public $instructors = [];
    public string $name = '';
    public $date;
    public int $instructor = 0;
    public int $slots = 0;
    public string $type = '';
    public int $amount;
    public $oldInstructor;

    protected $listeners = ['edit_schedule'];

    public function render()
    {
    
        
        $instructors = Instructor::query()
            ->get(['id', 'firstname', 'lastname']);
        

        $this->instructors = $instructors;

        return view('livewire.schedule-datatable.create-schedule');
    }

    public function register_schedule()
     {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'date' => 'required',
            'type' => ['required', 'string', 'max:255'],
            'instructor' => 'required',
            'amount' => ['required', 'integer']
        ]);

        $userCount = Schedules::whereDate('created_at', now()->format('Y-m-d'))->count();

        $sequentialNumber = $userCount;
            
        $schedule_code = 'SCHD-' . now()->format('mdY') . str_pad($sequentialNumber, 3, '0', STR_PAD_LEFT);

        $schedule = Schedules::create([
            'schedule_code' => $schedule_code,
            'created_by' => Auth::id(),
            'name' => $validated['name'],
            'date' => $validated['date'],
            'type' => $validated['type'],
            'instructor' => $validated['instructor'],
            'slots' => 0,
            'amount' => $validated['amount'],
        ]);

        $instructor = Instructor::where('id', $schedule->instructor)->first();

            $instructor->update(
                [
                    'hasSchedule' => true,
                ]
            );
 
         $this->dispatch('success_message', 'Schedule Has Been Created Successfully');
 
         $this->reset();
     }

     public function edit_schedule($schedule_id){
        if($schedule_id){

            $this->schedule_id = $schedule_id;

            $schedule = schedules::where('id', $schedule_id)->first();

            $this->name = $schedule->name;
            $this->type = $schedule->type;
            $this->date = $schedule->date;
            $this->slots = $schedule->slots;
            $this->instructor = $schedule->instructor;
            $this->amount = $schedule->amount;
            $this->oldInstructor = $schedule->instructor;

            $this->dispatch('open-modal', name: 'create-schedule');
        }

        
    }

    public function updatedInstructor(){

        $instructor = Instructor::where('id', $this->instructor)->first();
        if($this->instructor === $this->oldInstructor){

            $this->resetValidation('instructor');
            
        }elseif($this->instructor !== $this->oldInstructor){
            if($instructor->hasSchedule){
                $this->addError('instructor', "The instructor is not available");
            }else{
                $this->resetValidation('instructor');
            }
        }
    }

     public function update_schedule()
     {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'date' => 'required',
            'type' => ['required', 'string', 'max:255'],
            'instructor' => 'required',
            'amount' => ['required', 'integer']
        ]);

        $schedule = Schedules::where('id', $this->schedule_id)->first();

        $schedule->update([
            'created_by' => Auth::id(),
            'name' => $validated['name'],
            'date' => $validated['date'],
            'type' => $validated['type'],
            'instructor' => $validated['instructor'],
            'slots' => 0,
            'amount' => $validated['amount'],
        ]);

        if($this->oldInstructor !== $schedule->instructor){
            Instructor::where('id', $this->oldInstructor)->update(['hasSchedule' => false]);

            // Mark new instructor
            Instructor::where('id', $schedule->instructor)->update(['hasSchedule' => true]);
        }else{
            $instructor = Instructor::where('id', $schedule->instructor)->first();

            $instructor->update(
                [
                    'hasSchedule' => true,
                ]
            );
        }
 
         $this->dispatch('success_message', 'Schedule Has Been Updated Successfully');
 
         $this->reset();

         $this->schedule_id = 0;
     }
 
      public function formClose(){
         $this->reset();
         $this->resetValidation();
 
         if($this->schedule_id){
             $this->schedule_id = 0;
         }
      }
}
