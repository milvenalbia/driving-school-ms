<?php

namespace App\Livewire\DatatableComponent;

use Livewire\Component;

class ThCell extends Component
{
    
    public $field;
    public $label;
    public $sortBy;
    public $sortDirection;

    public function render()
    {
        return view('livewire.datatable-component.th-cell');
    }

    public function sortField($field = null)
{
        if (!$field) {
            // Optional: handle cases where no valid field is provided
            return;
        }


        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }

        $this->dispatch('sortField', $this->sortBy, $this->sortDirection);
    }
}
