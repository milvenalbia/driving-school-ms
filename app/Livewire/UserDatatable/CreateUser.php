<?php

namespace App\Livewire\UserDatatable;

use App\Models\Instructor;
use App\Models\User;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CreateUser extends Component
{
    public function render()
    {
        return view('livewire.user-datatable.create-user');
    }

     // Create user
     public string $name = '';
     public string $email = '';
     public string $password = '';
     public string $password_confirmation = '';
     public string $role;

     public $user_id = 0;
     public $user_role = '';

     protected $listeners = ['edit_user'];
 
     public function register_user()
     {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
            'role' => ['required', 'string', 'max:255'],
        ]);
 
         $validated['password'] = Hash::make($validated['password']);
 
         User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
         ]);
 
         $this->dispatch('success_message', 'User Has Been Created Successfully');
 
         $this->reset();
     }

    public function edit_user($user_id){
        if($user_id){

            $this->user_id = $user_id;

            $user = User::where('id', $user_id)->first();

            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role;

            $this->user_role = $user->role;

            $this->dispatch('open-modal', name: 'create-user');
        }

        
    }

    public function update_user()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255',
                Rule::unique('users')->ignore($this->user_id), 
            ],
            'password' => $this->password ? ['required', 'string', 'confirmed', Password::defaults()] : [],
            'role' => ['required', 'string', 'max:255'],
        ]);

        $user = User::findOrFail($this->user_id);

        if ($this->password) {
            $validated['password'] = Hash::make($this->password);
        } else {
            $validated['password'] = $user->password;
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
        ]);

        $this->dispatch('success_message', 'User Has Been Updated Successfully');

        $this->reset();

        $this->user_id = 0;

        $this->user_role = '';
    }

     public function formClose(){
        $this->reset();
        $this->resetValidation();

        if($this->user_id){
            $this->user_id = 0;
        }
     }
}
