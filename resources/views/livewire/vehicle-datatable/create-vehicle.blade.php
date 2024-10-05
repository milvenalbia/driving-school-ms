{{-- Create User and Edit User 1 Form --}}
<div class="w-full border-stroke dark:border-strokedark">
    <div class="w-full p-4 sm:p-12.5 xl:p-10">
    <div class="flex items-center justify-between mb-6 ">
        <h2 class="text-2xl font-bold text-black dark:text-white sm:text-title-xl2" >
            @if ($vehicle_id)
                Edit Vehicle
            @else
                Register New Vehicle
            @endif
        </h2>
        <button @click="show = false" class="hover:text-red-500" wire:click="formClose"> 
            <x-icons.close />
        </button>
    </div>

    <form wire:submit.prevent="{{ $vehicle_id ? 'update_vehicle' : 'register_vehicle' }}" x-data="{ licensePlate: ''}"> 
        <div class="mb-4 flex items-center justify-between gap-4">
            <div class="w-full">
                <label for="brand" class="mb-2.5 block font-medium text-black dark:text-white">
                    brand
                </label>
                <div class="relative">
                    <x-elements.text-input wire:model="brand" id="brand" type="text" name="brand" placeholder="Brand" autofocus autocomplete="brand" />
                    <x-elements.input-error :messages="$errors->get('brand')" class="mt-2" />
                    <span class="absolute right-4 top-4">
                        <x-icons.instructor />                     
                    </span>
                </div>
            </div>
            <div class="w-full">
                <label for="license_plate" class="mb-2.5 block font-medium text-black dark:text-white">
                    License Plate
                </label>
                <div class="relative">
                    <x-elements.text-input wire:model="license_plate" id="license_plate" type="text" name="license_plate" placeholder="Example: ABC 1234" autofocus autocomplete="license_plate"
                    x-model="licensePlate" 
                    @input="licensePlate = licensePlate.toUpperCase().replace(/[^A-Z0-9]/g, ' ')"  />
                    <x-elements.input-error :messages="$errors->get('license_plate')" class="mt-2" />
                    <span class="absolute right-4 top-4">
                        <x-icons.instructor />                     
                    </span>
                </div>
            </div>
        </div>
    
        <div class="mb-4">
            <label for="type" class="mb-2.5 block font-medium text-black dark:text-white">
                Vehicle Type
            </label>
            <div class="relative">
                <x-elements.select wire:model="type" id="type">
                    <option value="">Vehicle Type</option>
                    <option value="car">Car</option>
                    <option value="motorcycle">Motorcycle</option>
                </x-elements.select>
                <x-elements.input-error :messages="$errors->get('type')" class="mt-2" />
            </div>
        </div>

        <div class="mb-4 flex items-center justify-between gap-4">
            <div class="w-full">
                <label for="transmission_type" class="mb-2.5 block font-medium text-black dark:text-white">
                    Transmission Type
                </label>
                <div class="relative">
                    <x-elements.select wire:model="transmission_type" id="transmission_type">
                        <option value="">Transmission Type</option>
                        <option value="automatic">Automatic</option>
                        <option value="manual">Manual</option>
                    </x-elements.select>
                    <x-elements.input-error :messages="$errors->get('transmission_type')" class="mt-2" />
                </div>
            </div>
            <div class="w-full">
                <label for="v-status" class="mb-2.5 block font-medium text-black dark:text-white">
                    Status
                </label>
                <div class="relative">
                    <x-elements.select wire:model="status" id="status">
                        <option value="">Select Status</option>
                        <option value="good">Good</option>
                        <option value="in_use">In Use</option>
                        <option value="under_maintenance">Under Maintenance</option>
                    </x-elements.select>
                    <x-elements.input-error :messages="$errors->get('status')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="mb-5">
        <input
            type="submit"
            value="{{ $vehicle_id ? 'Update Vehicle' : 'Create Vehicle' }}"
            class="w-full cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90"
        />
        </div>
    </form>

    </div>
</div>
