<div class="w-full border-stroke dark:border-strokedark">
    <div class="w-full p-4 sm:p-12.5 xl:p-10">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-black dark:text-white sm:text-title-xl2" >
           Edit Enroll
        </h2>
        <button class="hover:text-red-500" wire:click="formClose"> 
            <x-icons.close />
        </button>
    </div>
    <form wire:submit.prevent="edit_enroll_student" >

        @if($isPractical)
        <div class="mb-4 flex items-center justify-between gap-4" >
            <div class="w-full" x-data="{ sessions: @entangle('sessions'), minimumLenght: 2 }">
               <label for="sessions" class="mb-2.5 block font-medium text-black dark:text-white">
                   Sessions (Days)
               </label>
               <div class="relative">
                    <x-elements.text-input x-model="sessions" wire:model.live="sessions" id="sessions" type="text" name="sessions" placeholder="Input Sessions" autofocus autocomplete="sessions" @input="sessions = sessions.replace(/[^0-9]/g, '')"
                    @keydown="if (sessions.length >= minimumLenght && event.keyCode !== 8 && event.keyCode !== 46) event.preventDefault()" />
                    <x-elements.input-error :messages="$errors->get('sessions')" class="mt-2" />
                    <span class="absolute right-4 top-4">
                        <x-icons.booking />                     
                    </span>
               </div>
            </div>
            <div class="w-full">
                <label for="hours" class="mb-2.5 block font-medium text-black dark:text-white">
                    Hours
                </label>
                <div class="relative">
                    <x-elements.select wire:model.live="hours" id="hours">
                        <option value="">Select Hours</option>
                        <option value="1">1 Hour</option>
                        <option value="2">2 Hours</option>
                        <option value="3">3 Hours</option>
                        <option value="4">4 Hours</option>
                        <option value="5">5 Hours</option>
                        <option value="6">6 Hours</option>
                        <option value="7">7 Hours</option>
                        <option value="8">8 Hours</option>
                    </x-elements.select>
                    <x-elements.input-error :messages="$errors->get('hours')" class="mt-2" />
                </div>
             </div>
        </div>

        <div class="mb-4" x-data x-init="flatpickr($refs.inputDate, {dateFormat: 'Y-m-d', minDate: 'today'});">
            <label for="start-date" class="mb-2.5 block font-medium text-black dark:text-white">
                Session Start Date
            </label>
            <div class="relative">
                <x-elements.text-input wire:model.live="start_date" id="start-date" x-ref="inputDate" type="text" name="start-date" placeholder="Date" autofocus />
                <x-elements.input-error :messages="$errors->get('start_date')" class="mt-2" />
                <span class="absolute right-4 top-4">
                    <x-icons.booking />                     
                </span>
            </div>
        </div>

        <div class="mb-4 flex items-center justify-between gap-4">
            <div class="w-full">
               <label for="v-type" class="mb-2.5 block font-medium text-black dark:text-white">
                   Vehicle Type
               </label>
               <div class="relative">
                   <x-elements.select wire:model.live="vehicle_type" id="v-type">
                       <option value="">Select Vehicle Type</option>
                       <option value="car">Car</option>
                       <option value="motorcycle">Motorcycle</option>
                   </x-elements.select>
                   <x-elements.input-error :messages="$errors->get('vehicle_type')" class="mt-2" />
               </div>
            </div>
            <div class="w-full">
                <label for="t-type" class="mb-2.5 block font-medium text-black dark:text-white">
                    Transmission Type
                </label>
                <div class="relative">
                    <x-elements.select wire:model.live="transmission_type" id="t-type">
                        <option value="">Select Transmission Type</option>
                        <option value="automatic">Automatic</option>
                        <option value="manual">Manual</option>
                    </x-elements.select>
                    <x-elements.input-error :messages="$errors->get('transmission_type')" class="mt-2" />
                </div>
             </div>
        </div>

        <div class="mb-4">
            <label for="vehicle" class="mb-2.5 block font-medium text-black dark:text-white">
                Vehicle (<span class="text-primary text-sm"><b>Note:</b> To enable,select date, select vehicle type & transmission type</span>)
            </label>
            <div class="relative">
                <x-elements.select wire:model="vehicle_id" id="vehicle" :disabled="empty($start_date) || empty($vehicle_type) || empty($transmission_type)" :class="empty($start_date) || empty($vehicle_type) || empty($transmission_type) ? 'cursor-not-allowed' : ''">
                <option value="">Select Vehicle</option>
                @forelse ($vehicles as $vehicle)
                    <option value={{$vehicle->id}}>{{$vehicle->brand}} ({{$vehicle->license_plate}})</option>
                @empty
                    <option value="">No Vehicle Available</option>
                @endforelse
                </x-elements.select>
                <x-elements.input-error :messages="$errors->get('vehicle_id')" class="mt-2" />
            </div>
        </div>
        @endif


        <div x-data="{ grade: '', minLength: 2 }" class="mb-4">
            <label for="grade" class="mb-2.5 block font-medium text-black dark:text-white">
                Grade
            </label>
            <div class="relative">
                <x-elements.text-input x-model="grade" wire:model="grade" id="grade" type="text" name="grade" placeholder="Input Grade" autofocus autocomplete="grade" @input="grade = grade.replace(/[^0-9]/g, '')"
                @keydown="if (grade.length >= minLength && event.keyCode !== 8 && event.keyCode !== 46) event.preventDefault()" />
                <x-elements.input-error :messages="$errors->get('grade')" class="mt-2" />
                <span class="absolute right-4 top-4">
                    <x-icons.bookmark />                     
                </span>
            </div>
        </div>
        
        <div class="mb-5">
         <input
             type="submit"
             value="Update"
             class="w-full cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90"
         />
        </div>
     </form>
    </div>
</div>

