{{-- Create User and Edit User 1 Form --}}
<div class="w-full border-stroke dark:border-strokedark">
    <div class="w-full p-4 sm:p-12.5 xl:p-10">
    <div class="flex items-center justify-between mb-6 ">
        <h2 class="text-2xl font-bold text-black dark:text-white sm:text-title-xl2" >
            @if ($instructor_id)
                Edit Instructor
            @else
                Register New Instructor
            @endif
        </h2>
        <button @click="show = false" wire:click="formClose" class="hover:text-red-500"> 
            <x-icons.close />
        </button>
    </div>
    <form wire:submit.prevent="{{ $instructor_id ? 'update_instructor' : 'register_instructor' }}"> 
       <center>
        <div class="h-[150px] w-[150px] mb-8 relative">
            @if ($image_path)
                <img class="w-full h-full object-cover rounded-full" src="{{ $image_path->temporaryUrl() }}" alt="profile" >
            @elseif ($old_image)
                <img class="w-full h-full object-cover rounded-full" src="{{ Storage::url($old_image) }}" alt="profile" >
            @else
                <img class="w-full h-full object-cover rounded-full" src="{{ asset('build/assets/images/profile.avif') }}" alt="profile" >
            @endif
            <label for="instr-profile" class="absolute bottom-0 right-2 cursor-pointer w-12 h-12 bg-primary flex items-center justify-center text-white rounded-full">
                <x-icons.camera />
            </label>
            <input type="file" id="instr-profile" accept="image/png, image/jpeg, image/jpg" class="hidden" wire:model.live="image_path">
        </div>
       </center>
        <div class="mb-4 flex items-center justify-between gap-4">
            <div class="w-full">
                <label for="firstname" class="mb-2.5 block font-medium text-black dark:text-white">
                    Firstname
                </label>
                <div class="relative">
                    <x-elements.text-input wire:model="firstname" id="firstname" type="text" name="firstname" placeholder="First Name" autofocus autocomplete="name" />
                    <x-elements.input-error :messages="$errors->get('firstname')" class="mt-2" />
                    <span class="absolute right-4 top-4">
                        <x-icons.instructor />                     
                    </span>
                </div>
            </div>
            <div class="w-full">
                <label for="lastname" class="mb-2.5 block font-medium text-black dark:text-white">
                    Lastname
                </label>
                <div class="relative">
                    <x-elements.text-input wire:model="lastname" id="lastname" type="text" name="lastname" placeholder="Last Name" autofocus autocomplete="name" />
                    <x-elements.input-error :messages="$errors->get('lastname')" class="mt-2" />
                    <span class="absolute right-4 top-4">
                        <x-icons.instructor />                     
                    </span>
                </div>
            </div>
        </div>
    
            <div class="mb-4">
            <label for="i-email" class="mb-2.5 block font-medium text-black dark:text-white">
                Email
            </label>
            <div class="relative">
                <x-elements.text-input wire:model="email" placeholder="Email" id="i-email" type="email" name="email" autocomplete="username" />
                <x-elements.input-error :messages="$errors->get('email')" class="mt-2" />
    
                <span class="absolute right-4 top-4">
                    <x-icons.mail />
                </span>
            </div>
            </div>
        
        <div class="mb-4 flex items-center justify-between gap-4">
            <div class="w-full">
                <label for="drivingExperience" class="mb-2.5 block font-medium text-black dark:text-white">
                    Driving Experience
                </label>
                <div class="relative">
                    <x-elements.text-input wire:model="drivingExperience" id="drivingExperience" type="text" name="drivingExperience" placeholder="Driving Experience" autofocus autocomplete="name" />
                    <x-elements.input-error :messages="$errors->get('drivingExperience')" class="mt-2" />
                    <span class="absolute right-4 top-4">
                        <x-icons.student />                     
                    </span>
                </div>
            </div>
            <div x-data="{ phoneNumber: @entangle('phoneNumber'), minLength: 11 }" class="w-full">
                <label for="phoneNumber" class="mb-2.5 block font-medium text-black dark:text-white">
                    Phone Number
                </label>
                <div class="relative">
                    <x-elements.text-input x-model="phoneNumber" wire:model="phoneNumber" id="phoneNumber" type="text" name="phoneNumber" placeholder="Phone Number" @input="phoneNumber = phoneNumber.replace(/[^0-9]/g, '')"
                    @keydown="if (phoneNumber.length >= minLength && event.keyCode !== 8 && event.keyCode !== 46) event.preventDefault()" />
                    <x-elements.input-error :messages="$errors->get('phoneNumber')" class="mt-2" />
                    <span class="absolute right-4 top-4">
                        <x-icons.contact />                     
                    </span>
                </div>
            </div>
        </div>
        
        <div class="mb-5">
        <input
            type="submit"
            value="{{ $instructor_id ? 'Update Account' : 'Create Account' }}"
            class="w-full cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90"
        />
        </div>
    </form>
    </div>
</div>

