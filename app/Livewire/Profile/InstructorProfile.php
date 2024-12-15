<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Livewire\Component;
use App\Models\Students;
use App\Models\Instructor;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class InstructorProfile extends Component
{

    use WithFileUploads;

    public $newImage;
    public $old_email;
    public $showNotification = false;

    public $firstname;
    public $lastname;
    public $middle;
    public $email;
    public $gender;
    public $phone_number;
    public $birth_date;
    public $address;
    public $image_path;
    public $driving_experience;
    

    public $new_password;
    public $new_password_confirmation;
    

    public function mount(){
        $user = Auth::user();
    
        $instructor = Instructor::where('user_id', $user->user_id)->first();
    
        if($instructor){
            $this->firstname = $instructor->firstname;
            $this->middle = $instructor->middle ?? '';
            $this->lastname = $instructor->lastname;
            $this->email = $instructor->email;
            $this->old_email = $instructor->email;
            $this->gender = $instructor->gender ?? '';
            $this->phone_number = $instructor->phone_number ?? '';
            $this->birth_date = $instructor->birth_date ?? null;
            $this->address = $instructor->address ?? '';
            $this->driving_experience = $instructor->driving_experience ?? '';
            $this->image_path = $instructor->image_path ?? '';
        }
    }

    public function render()
    {
        return view('livewire.profile.instructor-profile');
    }

    public function save()
    {
        $old_email = $this->old_email;

        $validated = $this->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255',
                function ($attribute, $value, $fail) use ($old_email) {
                    // Check in 'users' table but ignore the current email being updated
                    if (User::where('email', $value)->exists() && $value !== $old_email) {
                        $fail('The email has already been taken in the users table.');
                    }
        
                    // Check in 'instructors' table
                    if (Instructor::where('email', $value)->exists() && $value !== $old_email) {
                        $fail('The email has already been taken in the instructors table.');
                    }
        
                    // Check in 'students' table
                    if (Students::where('email', $value)->exists() && $value !== $old_email) {
                        $fail('The email has already been taken in the students table.');
                    }
                }
            ],
            'driving_experience' => 'required',
            'phone_number' => [
                'required', 
                'regex:/^09\d{9}$/',
                'size:11'
            ],
        ]);

        $user = Auth::user();

        $instructor = Instructor::where('user_id', $user->user_id)->first();

        $image = $instructor->image_path;

        if($this->newImage)
        {
            if(!empty($image)){
                Storage::delete($instructor->image_path);
            }
            $image = $this->newImage->store('public/photos');
        }else{
            $image = $instructor->image_path;
        }



        // Tiwasa ni tanan field sa student
        $instructor->update([
            'firstname' => $validated['firstname'],
            'middle' => $this->middle,
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'image_path' => $image,
            'gender' => $this->gender,
            'address' => $this->address,
            'driving_experience' => $validated['driving_experience'],
            'birth_date' => $this->birth_date,
        ]);

        if ($instructor) {
                $name = $validated['firstname'] . ' ' . $validated['lastname'];
            
                $user = User::where('user_id', $instructor->user_id)->first();

                $user->update([
                    'name' => $name,
                    'email' => $validated['email'],
                ]);
        }

        session()->flash('success', 'Infromations Updated Successfully');

        $this->showNotification = true;

    }

    public function cancel(){
        $this->reset();
        $this->resetValidation();

        $user = Auth::user();

        $instructor = Instructor::where('user_id', $user->user_id)->first();
    
        if($instructor){
            $this->firstname = $instructor->firstname;
            $this->middle = $instructor->middle ?? '';
            $this->lastname = $instructor->lastname;
            $this->email = $instructor->email;
            $this->old_email = $instructor->email;
            $this->gender = $instructor->gender ?? '';
            $this->phone_number = $instructor->phone_number ?? '';
            $this->birth_date = $instructor->birth_date ?? null;
            $this->address = $instructor->address ?? '';
            $this->driving_experience = $instructor->driving_experience ?? '';
            $this->image_path = $instructor->image_path ?? '';
        }
    }

    public function changePassword(){
        $validated = $this->validate([
            'new_password' => ['required', 'string', 'confirmed', Password::defaults()],
        ]);

        $validated['new_password'] = Hash::make($validated['new_password']);
 
        $user = Auth::user();

        $user = User::where('user_id', $user->user_id)->first();
 
        $user->update([
            'password' => $validated['new_password'],
        ]);

        session()->flash('success', 'Password Updated Successfully');
 
        $this->showNotification = true;
        
        $this->reset('new_password', 'new_password_confirmation');
    }
}
