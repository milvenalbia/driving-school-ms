{{-- Create User and Edit User 1 Form --}}
<div class="w-full border-stroke dark:border-strokedark">
    <div class="w-full p-4 sm:p-12.5 xl:p-10">
    <div class="flex items-center justify-between mb-6 ">
        <h2 class="text-2xl font-bold text-black dark:text-white sm:text-title-xl2" >
            @if ($user_id)
                Edit User
            @else
                Register New User
            @endif
        </h2>
        <button @click="show = false" class="hover:text-red-500" wire:click="formClose"> 
            <x-icons.close />
        </button>
    </div>

    <form wire:submit.prevent="{{ $user_id ? 'update_user' : 'register_user' }}"> 
        <div class="mb-4">
            <label for="name" class="mb-2.5 block font-medium text-black dark:text-white">
                Name
            </label>
            <div class="relative">
                <x-elements.text-input wire:model="name" id="name" type="text" name="name" placeholder="Enter your full name" autofocus autocomplete="name" />
                <x-elements.input-error :messages="$errors->get('name')" class="mt-2" />
                <span class="absolute right-4 top-4">
                    <x-icons.instructor />                     
                </span>
            </div>
            </div>
    
            <div class="mb-4">
            <label for="email" class="mb-2.5 block font-medium text-black dark:text-white">
                Email
            </label>
            <div class="relative">
                <x-elements.text-input wire:model="email" placeholder="Enter your email" id="email" type="email" name="email" autocomplete="username" />
                <x-elements.input-error :messages="$errors->get('email')" class="mt-2" />
    
                <span class="absolute right-4 top-4">
                    <x-icons.mail />
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
                    <x-icons.lock />
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
                    <x-icons.lock />
                </span>
            </div>
        </div>
        @if ($user_role)
            @if($user_role != 'student' && $user_role != 'instructor')
                <div class="mb-6">
                    <label class="mb-2.5 block font-medium text-black dark:text-white" for="role">
                        Select Role
                    </label>
                    <x-elements.select wire:model="role" name="role" id="role">
                        <option value="" class="text-body">Select user role</option>
                        <option value="admin" class="text-body">Admin</option>
                        <option value="employee" class="text-body">Employee</option>
                    </x-elements.select>
                    <x-elements.input-error :messages="$errors->get('role')" class="mt-2" />
                </div>
            @endif
        @else
            <div class="mb-6">
                <label
                class="mb-2.5 block font-medium text-black dark:text-white"
                for="role"
                >
                Select Role
                </label>
                <x-elements.select wire:model="role" name="role" id="role">
                    <option value="" class="text-body">Select user role</option>
                    <option value="admin" class="text-body">Admin</option>
                    <option value="employee" class="text-body">Employee</option>
                </x-elements.select>
                <x-elements.input-error :messages="$errors->get('role')" class="mt-2" />
            </div>
        @endif
        <div class="mb-5">
        <input
            type="submit"
            value="{{ $user_id ? 'Update Account' : 'Create Account' }}"
            class="w-full cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90"
        />
        </div>
    </form>

    </div>
</div>
