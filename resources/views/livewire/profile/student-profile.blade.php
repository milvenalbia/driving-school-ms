@section('custom-styles')
<style>
    .loader {
        border-top-color: #3498db;
        -webkit-animation: spinner 1.5s linear infinite;
        animation: spinner 1.5s linear infinite;
    }

    @-webkit-keyframes spinner {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spinner {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection

<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 bg-white">
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="save" class="space-y-8 divide-y divide-gray-200">
            <div class="space-y-8 divide-y divide-gray-200">
                
                <div class="mt-6 flex items-center">
                    <div class="h-[150px] w-[150px] mb-8 relative">
                        @if ($newImage)
                            <img class="w-full h-full object-cover rounded-full" src="{{ $newImage->temporaryUrl() }}" alt="profile" >
                        @elseif ($image_path)
                            <img class="w-full h-full object-cover rounded-full" src="{{ Storage::url($image_path) }}" alt="profile" >
                        @else
                            <img class="w-full h-full object-cover rounded-full" src="{{ asset('build/assets/images/profile.avif') }}" alt="profile" >
                        @endif
                        <label for="profile" class="absolute bottom-0 right-2 cursor-pointer w-12 h-12 bg-primary flex items-center justify-center text-white rounded-full">
                            <x-icons.camera />
                        </label>
                        <input type="file" id="profile" accept="image/png, image/jpeg, image/jpg" class="hidden" wire:model.live="newImage">
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="pt-8">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Personal Information</h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <label for="firstname" class="block text-sm font-medium text-gray-700">First name</label>
                            <div class="mt-1">
                                <x-elements.text-input type="text" wire:model="firstname" id="firstname" class="!py-2 border-boxdark"/>
                            </div>
                            @error('firstname') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="middle" class="block text-sm font-medium text-gray-700">Middle name</label>
                            <div class="mt-1">
                                <x-elements.text-input type="text" wire:model="middle" id="middle" class="!py-2 border-boxdark"/>
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="lastname" class="block text-sm font-medium text-gray-700">Last name</label>
                            <div class="mt-1">
                                <x-elements.text-input type="text" wire:model="lastname" id="lastname" class="!py-2 border-boxdark"/>
                            </div>
                            @error('lastname') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <div class="mt-1">
                                <x-elements.text-input type="email" wire:model="email" id="email" class="!py-2 border-boxdark"/>
                            </div>
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <div class="mt-1" x-data="{ phone_number: @entangle('phone_number'), minLength: 11 }">
                                <x-elements.text-input x-model="phone_number" wire:model="phone_number" class="!py-2 border-boxdark" id="phone_number" type="text" name="phone_number" @input="phone_number = phone_number.replace(/[^0-9]/g, '')"
                                @keydown="if (phone_number.length >= minLength && event.keyCode !== 8 && event.keyCode !== 46) event.preventDefault()" />
                            </div>
                            @error('phone_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                            <div class="mt-1">
                                <x-elements.select wire:model="gender" id="gender" class="!py-2 border-boxdark">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </x-elements.select>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="civil_status" class="block text-sm font-medium text-gray-700">Civil Status</label>
                            <div class="mt-1">
                                <x-elements.select wire:model="civil_status" id="civil_status" class="!py-2 border-boxdark">
                                    <option value="">Select Status</option>
                                    <option value="single">Single</option>
                                    <option value="married">Married</option>
                                    <option value="divorced">Divorced</option>
                                    <option value="widowed">Widowed</option>
                                </x-elements.select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="pt-8">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Address Information</h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <label for="region" class="block text-sm font-medium text-gray-700 mb-1">Region</label>
                            <x-elements.select wire:model.live="region" wire:change="changeRegion" id="region" class="!py-2 border-boxdark">
                                <option value="">Select Region</option>
                                @foreach ($regions as $region)
                                    <option value={{$region->id}}>{{$region->name}}</option>
                                @endforeach
                            </x-elements.select>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                            <x-elements.select wire:model.live="province" wire:change="changeProvince" id="province" class="!py-2 border-boxdark">
                                <option value="">Select Province</option>
                                @foreach ($provinces as $province)
                                    <option value={{$province->id}}>{{$province->name}}</option>
                                @endforeach
                            </x-elements.select>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="municipalilty" class="block text-sm font-medium text-gray-700 mb-1">City/Municipality</label>
                            <x-elements.select wire:model.live="municipality" wire:change="changeMunicipality" id="municipality" class="!py-2 border-boxdark">
                                <option value="">Select Municipality</option>
                                @foreach ($municipalities as $municipality)
                                    <option value={{$municipality->id}}>{{$municipality->name}}</option>
                                @endforeach
                            </x-elements.select>
                        </div>
                        
                        <div class="sm:col-span-3">
                            <label for="barangay" class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                            <x-elements.select wire:model="barangay" id="barangay" class="!py-2 border-boxdark">
                                <option value="">Select Barangay</option>
                                @foreach ($barangays as $barangay)
                                    <option value={{$barangay->id}}>{{$barangay->name}}</option>
                                @endforeach
                            </x-elements.select>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="district" class="block text-sm font-medium text-gray-700 mb-1">District</label>
                            <div class="mt-1">
                                <x-elements.text-input type="text" wire:model="district" id="district" class="!py-2 border-boxdark" />
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="street" class="block text-sm font-medium text-gray-700 mb-1">Street Address</label>
                            <div class="mt-1">
                                <x-elements.text-input type="text" wire:model="street" id="street" class="!py-2 border-boxdark" />
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Birth Information -->
                <div class="pt-8">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Birth Information</h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3" x-data x-init="flatpickr($refs.inputDate, {dateFormat: 'Y-m-d', minDate: 'today'});">
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Birth Date</label>
                            <div class="mt-1">
                                <x-elements.text-input wire:model="birth_date" class="!py-2 border-boxdark" id="birth_date" x-ref="inputDate" type="text" name="birth_date" />
                            </div>
                            @error('birth_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="birth_palce" class="block text-sm font-medium text-gray-700 mb-1">Birth Place</label>
                            <div class="mt-1">
                                <x-elements.text-input type="text" wire:model="birth_palce" id="birth_palce" class="!py-2 border-boxdark" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account -->
                <div class="pt-8">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Account</h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <div class="mt-1">
                                <x-elements.text-input type="password" wire:model="new_password" id="new_password" class="!py-2 border-boxdark" />
                                @error('new_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <div class="mt-1">
                                <x-elements.text-input type="password" wire:model="password_confirmation" id="password_confirmation" class="!py-2 border-boxdark" />
                                @error('password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-5">
                <div class="flex justify-end">
                    <button type="button" wire:click='cancel' class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </button>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>

        <!-- Loading Indicator -->
        <div wire:loading wire:target="save, newImage" class="fixed top-0 left-0 right-0 bottom-0 w-full h-screen z-50 overflow-hidden bg-gray-700 opacity-75 flex flex-col items-center justify-center">
            <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12 mb-4"></div>
            <h2 class="text-center text-white text-xl font-semibold">Loading...</h2>
            <p class="w-1/3 text-center text-white">This may take a few seconds, please don't close this page.</p>
        </div>
    </div>

    <x-elements.notification >
        <x-slot:svg>
            <x-icons.success class="text-[#06D001]" />
        </x-slot:svg>
        {{session('success')}}
    </x-elements.notification>
</div>
