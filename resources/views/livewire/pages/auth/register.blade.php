<?php

use App\Models\User;
use App\Models\Students;
use App\Models\Instructor;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

new #[Layout('layouts.guest')] class extends Component
{
    use WithFileUploads;

    public string $firstname = '';
    public string $lastname = '';
    public string $phoneNumber = '';
    public string $email = '';
    public $image_path;
    public $user_id;
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
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
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
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
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'email' => $validated['email'],
                'phone_number' => $validated['phoneNumber'],
                'image_path' => $validated['image_path'],
            ]);
        
            if ($student) {
   
                $password = Hash::make($validated['password']);;
            
                // Create full name and email
                $name = $validated['firstname'] . ' ' . $validated['lastname'];
            
                // Create the user
                $user = User::create([
                    'user_id' => $user_id,
                    'name' => $name,
                    'email' => $validated['email'],
                    'password' => $password,
                    'role' => 'student',
                ]);

                 // Fire the registered event (Laravel default behavior)
                event(new Registered($user));

                // Automatically log the user in after registration
                Auth::login($user);

                $this->redirect(route('student-dashboard', absolute: false));
            }
        
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack(); 
            throw $e;
        }
    
    }
}; ?>

<div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
    <x-slot name="title">Register</x-slot>
    <div class="flex flex-wrap items-center">
    <div class="hidden w-full xl:block xl:w-1/2">
        <div class="px-26 py-17.5 text-center">
        <a class="mb-5.5 inline-block" href="#">
            <img
            class="w-[200px] h-[150px] object-cover object-center"
            src="{{ asset('build/assets/images/prime-logo.png') }}"
            alt="Logo"
            />
        </a>

        <p class="font-medium 2xl:px-20">
            "Prime Driving School: Empowering You to Drive with Confidence and Excellence"
        </p>

        <span class="mt-15 inline-block">
            <img
            src="{{ asset('build/assets/images/login.svg') }}"
            alt="illustration"
            />
        </span>
        </div>
    </div>
    <div
        class="w-full border-stroke dark:border-strokedark xl:w-1/2 xl:border-l-2"
    >
        <div class="w-full p-4 sm:p-12.5 xl:p-17.5">
        <span class="mb-1.5 block font-medium">Student Registration</span>
        <h2 class="mb-9 text-xl font-bold text-black dark:text-white sm:text-2xl">
            Sign Up to Prime Driving School
        </h2>

        <form wire:submit="register">
            <center>
                <div class="h-[150px] w-[150px] mb-8 relative">
                    @if ($image_path)
                        <img class="w-full h-full object-cover rounded-full" src="{{ $image_path->temporaryUrl() }}" alt="profile" >
                    @else
                        <img class="w-full h-full object-cover rounded-full" src="{{ asset('build/assets/images/profile.avif') }}" alt="profile" >
                    @endif
                    <label for="profile" class="absolute bottom-0 right-2 cursor-pointer w-12 h-12 bg-primary flex items-center justify-center text-white rounded-full">
                        <x-icons.camera />
                    </label>
                    <input type="file" id="profile" accept="image/png, image/jpeg, image/jpg" class="hidden" wire:model.live="image_path">
                </div>
               </center>
            <div class="mb-4 flex items-center justify-between gap-4">
                <div class="w-full">
                    <label for="s-firstname" class="mb-2.5 block font-medium text-black dark:text-white">
                        Firstname
                    </label>
                    <div class="relative">
                        <x-elements.text-input wire:model="firstname" id="s-firstname" type="text" name="firstname" placeholder="First Name" autofocus autocomplete="firstname" />
                        <x-elements.input-error :messages="$errors->get('firstname')" class="mt-2" />
                        <span class="absolute right-4 top-4">
                            <x-icons.instructor />                     
                        </span>
                    </div>
                </div>
                <div class="w-full">
                    <label for="s-lastname" class="mb-2.5 block font-medium text-black dark:text-white">
                        Lastname
                    </label>
                    <div class="relative">
                        <x-elements.text-input wire:model="lastname" id="s-lastname" type="text" name="lastname" placeholder="Last Name" autofocus autocomplete="lastname" />
                        <x-elements.input-error :messages="$errors->get('lastname')" class="mt-2" />
                        <span class="absolute right-4 top-4">
                            <x-icons.instructor />                     
                        </span>
                    </div>
                </div>
            </div>
    
            <div x-data="{ phoneNumber: @entangle('phoneNumber'), minLength: 11 }" class="mb-4">
                <label for="studNumber" class="mb-2.5 block font-medium text-black dark:text-white">
                    Phone Number
                </label>
                <div class="relative">
                    <x-elements.text-input x-model="phoneNumber" wire:model="phoneNumber" id="studNumber" type="text" name="phoneNumber" placeholder="Phone Number" autofocus autocomplete="phone_number" @input="phoneNumber = phoneNumber.replace(/[^0-9]/g, '')"
                    @keydown="if (phoneNumber.length >= minLength && event.keyCode !== 8 && event.keyCode !== 46) event.preventDefault()" />
                    <x-elements.input-error :messages="$errors->get('phoneNumber')" class="mt-2" />
                    <span class="absolute right-4 top-4">
                        <x-icons.contact />                     
                    </span>
                </div>
            </div>

            <div class="mb-4">
            <label for="email" class="mb-2.5 block font-medium text-black dark:text-white">
                Email
            </label>
            <div class="relative">
                <x-elements.text-input wire:model="email" placeholder="Enter your email" id="email" type="email" name="email"  autocomplete="username" />
                <x-elements.input-error :messages="$errors->get('email')" class="mt-2" />

                <span class="absolute right-4 top-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                        </svg>
                </span>
            </div>
            </div>

            <div class="mb-4">
            <label for="password" class="mb-2.5 block font-medium text-black dark:text-white">
                Password
            </label>
            <div class="relative">
                <x-elements.text-input wire:model="password" id="password"
                        type="password"
                        name="password"
                        placeholder="Enter your password"
                        autocomplete="new-password" />

                <x-elements.input-error :messages="$errors->get('password')" class="mt-2" />

                <span class="absolute right-4 top-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 ">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                </span>
            </div>
            </div>

            <div class="mb-6">
            <label for="password_confirmation" class="mb-2.5 block font-medium text-black dark:text-white">
                Re-type Password
            </label>
            <div class="relative">
                <x-elements.text-input wire:model="password_confirmation" id="password_confirmation"
                        type="password"
                        placeholder="Confirm your password"
                        name="password_confirmation" autocomplete="new-password" />

                <x-elements.input-error :messages="$errors->get('password_confirmation')" class="mt-2" />

                <span class="absolute right-4 top-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 ">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </span>
            </div>
            </div>

            <div class="mb-5">
            <input
                type="submit"
                value="Create account"
                class="w-full cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90"
            />
            </div>

            <div class="mt-6 text-center">
            <p class="font-medium">
                Already have an account?
                <a href={{ route('login') }} class="text-primary">Sign in</a>
            </p>
            </div>
        </form>
        </div>
    </div>
    </div>
</div>
