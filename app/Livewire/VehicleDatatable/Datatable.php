<?php

namespace App\Livewire\VehicleDatatable;

use App\Models\Vehicle;
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

    public function edit_vehicle($vehicle_id){
        $this->dispatch('edit_vehicle', $vehicle_id);
    }

    public function sortField($field, $direction)
    {
        $this->sortBy = $field;
        $this->sortDirection = $direction;
    }

    public function render()
    {
        $vehicles = Vehicle::query()
            ->when($this->search, function ($query) {
                    $query->where('license_plate', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    
        return view('livewire.vehicle-datatable.datatable', [
            'vehicles' => $vehicles,
        ]);
    }

    public function delete_vehicle($vehicle_id)
    {
 
        $vehicle = Vehicle::where('id', $vehicle_id)->first();

        $vehicle->delete();

        session()->flash('success', 'Vehicle has been deleted successfully');

        $this->showNotification = true;
    }
}
