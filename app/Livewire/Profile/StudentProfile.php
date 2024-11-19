<?php

namespace App\Livewire\Profile;

use App\Models\User;
use App\Models\Region;
use Livewire\Component;
use App\Models\Barangay;
use App\Models\Province;
use App\Models\Students;
use App\Models\Instructor;
use App\Models\Municipality;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentProfile extends Component
{
    use WithFileUploads;

    public $profile;
    public $newImage;
    public $old_email;
    public $showNotification = false;
    
    // Form fields
    public $firstname;
    public $lastname;
    public $middle;
    public $email;
    public $gender;
    public $phone_number;
    public $birth_date;
    public $street;
    public $barangay;
    public $district;
    public $municipality;
    public $province;
    public $region;
    public $civil_status;
    public $birth_palce;
    public $image_path;

    public $barangays = [];
    public $municipalities = [];
    public $provinces = [];
    public $regions = [];
    
    // Course related fields
    public $new_password;
    public $password_confirmation;

    public function updatedRegion(){

        $this->provinces = Province::select('id', 'name')
                            ->where('region_id', $this->region)
                            ->get();
    }

    public function updatedProvince(){

        $this->municipalities = Municipality::select('id', 'name')
                            ->where('province_id', $this->province)
                            ->get();
    }

    public function updatedMunicipality(){

        $this->barangays = Barangay::select('id', 'name')
                            ->where('municipality_id', $this->municipality)
                            ->get();
    }

    public function changeRegion(){

            $this->province = 0;
            $this->municipality = 0;
            $this->barangay = 0;

            $this->municipalities = [];
            $this->barangays = [];
    }

    public function changeProvince(){

            $this->municipality = 0;
            $this->barangay = 0;

            $this->barangays = [];
    }

    public function changeMunicipality(){

            $this->barangay = 0;
       
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
            'phone_number' => [
                'required', 
                'regex:/^09\d{9}$/',
                'size:11'
            ],
            'newImage' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();

        $student = Students::where('user_id', $user->user_id)->first();

        $image = $student->image_path;
        if($this->newImage)
        {
            Storage::delete($student->image_path);
            $image = $this->newImage->store('public/photos');
        }else{
            $image = $student->image_path;
        }

        // Tiwasa ni tanan field sa student
        $student->update([
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'image_path' => $image,
        ]);

        //lahi na function ang sa password
        if ($student) {
                $name = $validated['firstname'] . ' ' . $validated['lastname'];
            
                $user = User::where('user_id', $student->user_id)->first();

                $user->update([
                    'name' => $name,
                    'email' => $validated['email'],
                    'role' => 'student',
                ]);
        }

        session()->flash('success', 'Updated Successfully');

        $this->showNotification = true;

        $this->reset();
    }

    public function cancel(){
        $this->reset();
        $this->resetValidation();

        $user = Auth::user();
        $this->regions = Region::select('id','name')->get();

        $student = Students::where('user_id', $user->user_id)->first();

        if($student){
            $this->firstname = $student->firstname;
            $this->middle = $student->middle ?? '';
            $this->lastname = $student->lastname;
            $this->email = $student->email;
            $this->gender = $student->gender ?? '';
            $this->civil_status = $student->civil_status ?? '';
            $this->phone_number = $student->phone_number ?? '';
            $this->region = $student->region ?? 0;
            $this->province = $student->province ?? 0;
            $this->municipality = $student->municipalilty ?? 0;
            $this->barangay = $student->barangay ?? 0;
            $this->district = $student->district ?? '';
            $this->street = $student->street ?? '';
            $this->birth_date = $student->birth_date ?? '';
            $this->birth_palce = $student->birth_palce ?? '';
            $this->image_path = $student->image_path ?? '';
        }
    }

    public function render()
    {
        $user = Auth::user();
        $this->regions = Region::select('id','name')->get();

        $student = Students::where('user_id', $user->user_id)->first();

        if($student){
            $this->firstname = $student->firstname;
            $this->middle = $student->middle ?? '';
            $this->lastname = $student->lastname;
            $this->email = $student->email;
            $this->old_email = $student->email;
            $this->gender = $student->gender ?? '';
            $this->civil_status = $student->civil_status ?? '';
            $this->phone_number = $student->phone_number ?? '';
            $this->region = $student->region ?? 0;
            $this->province = $student->province ?? 0;
            $this->municipality = $student->municipalilty ?? 0;
            $this->barangay = $student->barangay ?? 0;
            $this->district = $student->district ?? '';
            $this->street = $student->street ?? '';
            $this->birth_date = $student->birth_date ?? '';
            $this->birth_palce = $student->birth_palce ?? '';
            $this->image_path = $student->image_path ?? '';
        }
        return view('livewire.profile.student-profile');
    }
}
