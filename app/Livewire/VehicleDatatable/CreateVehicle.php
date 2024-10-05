<?php

namespace App\Livewire\VehicleDatatable;

use App\Models\Vehicle;
use Livewire\Component;
use Illuminate\Validation\Rule;

class CreateVehicle extends Component
{
    public function render()
    {
        return view('livewire.vehicle-datatable.create-vehicle');
    }

    public string $brand = '';
    public string $license_plate = '';
    public string $type = '';
    public string $transmission_type = '';
    public string $status;

    public $vehicle_id = 0;

    protected $listeners = ['edit_vehicle'];

    public function register_vehicle()
    {
        $validated = $this->validate([
            'brand' => ['required', 'string', 'max:255'],
            'license_plate' => ['required', 'string', 'max:255', 'unique:'.Vehicle::class, 'regex:/^[a-zA-Z]{2,3}\s?\d{3,4}$/'],
            'type' => ['required', 'string', 'max:255'],
            'transmission_type' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
        ]);

        Vehicle::create([
           'brand' => $validated['brand'],
           'license_plate' => $validated['license_plate'],
           'type' => $validated['type'],
           'transmission_type' => $validated['transmission_type'],
           'status' => $validated['status'],
        ]);

        $this->dispatch('success_message', 'Vehicle Has Been Created Successfully');

        $this->reset();
    }

   public function edit_vehicle($vehicle_id){
       if($vehicle_id){

           $this->vehicle_id = $vehicle_id;

           $vehicle = Vehicle::where('id', $vehicle_id)->first();

           $this->brand = $vehicle->brand;
           $this->license_plate = $vehicle->license_plate;
           $this->type = $vehicle->type;
           $this->transmission_type = $vehicle->transmission_type;
           $this->status = $vehicle->status;

           $this->dispatch('open-modal', name: 'create-vehicle');
       }

       
   }

   public function update_vehicle()
   {
       
       $validated = $this->validate([
            'brand' => ['required', 'string', 'max:255'],
            'license_plate' => ['required', 'string', 'max:255', Rule::unique('vehicles')->ignore($this->vehicle_id),],
            'type' => ['required', 'string', 'max:255'],
            'transmission_type' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
        ]);

       $vehicle = Vehicle::findOrFail($this->vehicle_id);


       $vehicle->update([
           'brand' => $validated['brand'],
           'license_plate' => $validated['license_plate'],
           'type' => $validated['type'],
           'transmission_type' => $validated['transmission_type'],
           'status' => $validated['status'],
       ]);

       $this->dispatch('success_message', 'Vehicle Has Been Updated Successfully');

       $this->reset();

       $this->vehicle_id = 0; 
   }

    public function formClose(){
       $this->reset();
       $this->resetValidation();

       if($this->vehicle_id){
           $this->vehicle_id = 0;
       }
    }
}
