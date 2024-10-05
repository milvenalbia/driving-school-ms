<?php

namespace App\Livewire\StudentDatatable;

use App\Models\User;
use Livewire\Component;
use App\Models\Students;
use App\Models\Instructor;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CreateStudent extends Component
{
    public function render()
    {
        return view('livewire.student-datatable.create-student');
    }
    use WithFileUploads;
    public string $firstname = '';
    public string $lastname = '';
    public string $old_email = '';
    public string $email = '';
    public string $phoneNumber = '';
    public $image_path;
    public $old_image;

     public $student_id = 0;
     public $user_id;

     protected $listeners = ['edit_student'];
 
     public function register_student()
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
            'phoneNumber' => [
                'required', 
                'regex:/^09\d{9}$/',
                'size:11'
            ],
        ]);

        if($this->image_path){
            $validated['image_path'] = $this->image_path->store('public/photos');
        }else{
            $validated['image_path'] = null;
        }

        $userCount = Students::whereDate('created_at', now()->format('Y-m-d'))->count();

        $sequentialNumber = $userCount;
            
        $user_id = 'STUD-' . now()->format('mdY') . str_pad($sequentialNumber, 3, '0', STR_PAD_LEFT);
 
        DB::beginTransaction();
        
        try {
            $student = Students::create([
                'user_id' => $user_id,
                'created_by' => Auth::id(),
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'email' => $validated['email'],
                'phone_number' => $validated['phoneNumber'],
                'image_path' => $validated['image_path'],
            ]);
        
            if ($student) {
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
                    'email' => $this->email,
                    'password' => $password,
                    'role' => 'student',
                ]);
            }
        
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack(); 
            throw $e;
        }
 
         $this->dispatch('success_message', 'User Has Been Created Successfully');
 
         $this->reset();
     }

    public function edit_student($student_id){
        if($student_id){

            $this->student_id = $student_id;

            $student = Students::where('id', $student_id)->first();

            $this->firstname = $student->firstname;
            $this->lastname = $student->lastname;
            $this->email = $student->email;
            $this->phoneNumber = $student->phone_number;
            $this->old_image = $student->image_path;

            $this->old_email = $this->email;
            $this->user_id = $student->user_id;

            $this->dispatch('open-modal', name: 'create-student');
        }

        
    }

    public function update_student()
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
            'phoneNumber' => [
                'required', 
                'regex:/^09\d{9}$/',
                'size:11'
            ],
        ]);

        $student = Students::findOrFail($this->student_id);

        $image = $student->image_path;
        if($this->image_path)
        {
            Storage::delete($student->image_path);
            $image = $this->image_path->store('public/photos');
        }else{
            $image = $student->image_path;
        }

        $student->update([
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'phone_number' => $validated['phoneNumber'],
            'image_path' => $image,
        ]);

        if ($student) {
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
                'role' => 'student',
            ]);
        }

        $this->dispatch('success_message', 'Student Has Been Updated Successfully');

        $this->reset();

        $this->student_id = 0;

        $this->user_id = '';
    }

     public function formClose(){
        $this->reset();
        $this->resetValidation();

        if($this->student_id){
            $this->student_id = 0;
            $this->user_id = '';
        }
     }
}
