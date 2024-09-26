<?php

namespace App\Livewire\UserDatatable;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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

    public function sortField($field, $direction)
    {
        $this->sortBy = $field;
        $this->sortDirection = $direction;
    }

    public function render()
    {
        $users = User::query()
            ->where('id', "!=", Auth::id())
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    
        return view('livewire.user-datatable.datatable', [
            'users' => $users,
        ]);
    }

    public function edit_user($user_id){
        $this->dispatch('edit_user', $user_id);
    }

    public function delete_user($user_id)
    {
 
        $user = User::where('id', $user_id)->first();

        $user->delete();

        session()->flash('success', 'User has been deleted successfully');

        $this->showNotification = true;
    }
}
