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
use Illuminate\Validation\Rules\Password;

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
    public $birth_place;
    public $image_path;

    public $barangays = [];
    public $municipalities = [];
    public $provinces = [];
    public $regions = [];
    
    // Course related fields
    public $new_password;
    public $new_password_confirmation;

    public function mount(){
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
            $this->region = $student->region ?? null;
            $this->province = $student->province ?? null;
            $this->municipality = $student->municipality ?? null;
            $this->barangay = $student->barangay ?? null;
            $this->district = $student->district ?? '';
            $this->street = $student->street ?? '';
            $this->birth_date = $student->birth_date ?? null;
            $this->birth_place = $student->birth_palce ?? ''; //typo pero ayaw hilabti
            $this->image_path = $student->image_path ?? '';
    
            // Populate the provinces if a region is selected
            if ($this->region) {
                $this->provinces = Province::select('id', 'name')
                    ->where('region_id', $this->region)
                    ->get();
            }
    
            // Populate the municipalities if a province is selected
            if ($this->province) {
                $this->municipalities = Municipality::select('id', 'name')
                    ->where('province_id', $this->province)
                    ->get();
            }
    
            // Populate the barangays if a municipality is selected
            if ($this->municipality) {
                $this->barangays = Barangay::select('id', 'name')
                    ->where('municipality_id', $this->municipality)
                    ->get();
            }
        }
    }
    
    
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

            $this->province = null;
            $this->municipality = null;
            $this->barangay = null;

            $this->municipalities = [];
            $this->barangays = [];
    }

    public function changeProvince(){

            $this->municipality = null;
            $this->barangay = null;

            $this->barangays = [];
    }

    public function changeMunicipality(){

            $this->barangay = null;
       
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
            'region' => 'required',
            'province' => 'required',
            'municipality' => 'required',
            'barangay' => 'required',
        ]);

        $user = Auth::user();

        $student = Students::where('user_id', $user->user_id)->first();

        $image = $student->image_path;

        if($this->newImage)
        {
            if(!empty($image)){
                Storage::delete($student->image_path);
            }
            $image = $this->newImage->store('public/photos');
        }else{
            $image = $student->image_path;
        }



        // Tiwasa ni tanan field sa student
        $student->update([
            'firstname' => $validated['firstname'],
            'middle' => $this->middle,
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'image_path' => $image,
            'gender' => $this->gender,
            'civil_status' => $this->civil_status,
            'region' => $this->region,
            'province' => $this->province,
            'municipality' => $this->municipality,
            'barangay' => $this->barangay,
            'district' => $this->district,
            'street' => $this->street,
            'birth_date' => $this->birth_date,
            'birth_palce' => $this->birth_place, //typo pero ayaw hilabti
        ]);

        if ($student) {
                $name = $validated['firstname'] . ' ' . $validated['lastname'];
            
                $user = User::where('user_id', $student->user_id)->first();

                $user->update([
                    'name' => $name,
                    'email' => $validated['email'],
                ]);
        }

        session()->flash('success', 'Infromations Updated Successfully');

        $this->showNotification = true;

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
            $this->region = $student->region ?? null;
            $this->province = $student->province ?? null;
            $this->municipality = $student->municipalilty ?? null;
            $this->barangay = $student->barangay ?? null;
            $this->district = $student->district ?? '';
            $this->street = $student->street ?? '';
            $this->birth_date = $student->birth_date ?? null;
            $this->birth_place = $student->birth_palce ?? ''; //typo pero ayaw hilabti
            $this->image_path = $student->image_path ?? '';
        }
    }

    public function render()
    {
        return view('livewire.profile.student-profile');
    }
}
