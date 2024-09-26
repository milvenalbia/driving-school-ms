<?php

namespace App\Livewire\InstructorDatatable;

use App\Models\User;
use Livewire\Component;
use App\Models\Students;
use App\Models\Instructor;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class CreateInstructor extends Component
{

    use WithFileUploads;

    public string $firstname = '';
    public string $lastname = '';
    public string $email = '';
    public string $phoneNumber = '';
    public int $drivingExperience;
    public $image_path;
    public $old_image;
    public int $instructor_id = 0;
    public string $old_email = '';
    public string $user_id = '';

    protected $listeners = ['edit_instructor'];

    public function render()
    {
        return view('livewire.instructor-datatable.create-instructor');
    }
    
    public function register_instructor()
     {
        $validated = $this->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                function ($attribute, $value, $fail) {
                    if (User::where('email', $value)->exists() ||
                        Students::where('email', $value)->exists() ||
                        Instructor::where('email', $value)->exists()) {
                        $fail('The email has already been taken in one of the records.');
                    }
                }
            ],
            'phoneNumber' => ['required', 'string', 'max:255'],
            'drivingExperience' => 'required',
        ]);

        if($this->image_path){
            $validated['image_path'] = $this->image_path->store('public/photos');
        }else{
            $validated['image_path'] = null;
        }

        $userCount = Instructor::whereDate('created_at', now()->format('Y-m-d'))->count();

        $sequentialNumber = $userCount;
            
        $user_id = 'INSTR-' . now()->format('mdY') . str_pad($sequentialNumber, 3, '0', STR_PAD_LEFT);
        
        DB::beginTransaction();
        
        try {
            $instructor = Instructor::create([
                'user_id' => $user_id,
                'created_by' => Auth::id(),
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'email' => $validated['email'],
                'phone_number' => $validated['phoneNumber'],
                'driving_experience' => $validated['drivingExperience'],
                'image_path' => $validated['image_path'],
            ]);
        
            if ($instructor) {
                // Convert lastname to lowercase and replace spaces with underscores
                $lastname = strtolower(str_replace(' ', '_', $validated['lastname']));
            
                // Create password using the modified lastname
                $password = Hash::make($lastname . '.12345');
            
                // Create full name and email
                $name = $validated['firstname'] . ' ' . $validated['lastname'];
            
                // Create the user
                User::create([
                    'user_id' => $user_id,
                    'name' => $name,
                    'email' => $validated['email'],
                    'password' => $password,
                    'role' => 'instructor',
                ]);
            }
        
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack(); 
            throw $e;
        }
 
         $this->dispatch('success_message', 'Instructor Has Been Created Successfully');
 
         $this->reset();
     }

     public function edit_instructor($instructor_id){
        if($instructor_id){

            $this->instructor_id = $instructor_id;

            $instructor = instructor::where('id', $instructor_id)->first();

            $this->firstname = $instructor->firstname;
            $this->lastname = $instructor->lastname;
            $this->email = $instructor->email;
            $this->phoneNumber = $instructor->phone_number;
            $this->drivingExperience = $instructor->driving_experience;
            $this->old_image = $instructor->image_path;

            $this->old_email = $this->email;
            $this->user_id = $instructor->user_id;

            $this->dispatch('open-modal', name: 'create-instructor');
        }

        
    }

    public function update_instructor()
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
                    if (Instructor::where('email', $value)->exists() && $value !== $old_email) {
                        $fail('The email has already been taken in the students table.');
                    }
                }
            ],
            'drivingExperience' => 'required',
            'phoneNumber' => ['required', 'string', 'max:255'],
        ]);

        $instructor = Instructor::findOrFail($this->instructor_id);
        $image = $instructor->image_path;
        if($this->image_path)
        {
            Storage::delete($instructor->image_path);
            $image = $this->image_path->store('public/photos');
        }else{
            $image = $instructor->image_path;
        }

        $instructor->update([
            'created_by' => Auth::id(),
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'phone_number' => $validated['phoneNumber'],
            'driving_experience' => $validated['drivingExperience'],
            'image_path' => $image,
        ]);

        if ($instructor) {
            // Convert lastname to lowercase and replace spaces with underscores
            $lastname = strtolower(str_replace(' ', '_', $validated['lastname']));
            
            // Create password using the modified lastname
            $password = Hash::make($lastname . '.12345');
        
            // Create full name and email
            $name = $validated['firstname'] . ' ' . $validated['lastname'];
        
            $user = User::where('user_id', $this->user_id)->first();

            $user->update([
                'name' => $name,
                'email' => $validated['email'],
                'password' => $password,
                'role' => 'instructor',
            ]);
        }

        $this->dispatch('success_message', 'Instructor Has Been Updated Successfully');

        $this->reset();

        $this->instructor_id = 0;
    }
     
    public function formClose(){
        $this->reset();
        $this->resetValidation();

        if($this->instructor_id){
            $this->instructor_id = 0;
            $this->user_id = '';
        }
     }
}
