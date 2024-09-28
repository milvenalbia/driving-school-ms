{{-- Create User and Edit User 1 Form --}}
<div class="w-full border-stroke dark:border-strokedark">
    <div class="w-full p-4 sm:p-12.5 xl:p-10">
    <div class="flex items-center justify-between mb-6 ">
        <h2 class="text-2xl font-bold text-black dark:text-white sm:text-title-xl2" >
            @if ($schedule_id)
                Edit Schedule
            @else
                Create New Schedule
            @endif
        </h2>
        <button @click="show = false" class="hover:text-red-500" wire:click="formClose"> 
            <x-icons.close />
        </button>
    </div>
    <form wire:submit.prevent="{{ $schedule_id ? 'update_schedule' : 'register_schedule' }}"> 
        <div class="mb-4">
            <label for="s-name" class="mb-2.5 block font-medium text-black dark:text-white">
                Name
            </label>
            <div class="relative">
                <x-elements.text-input wire:model="name" placeholder="Name" id="s-name" type="name" name="name" autocomplete="name" />
                <x-elements.input-error :messages="$errors->get('name')" class="mt-2" />
    
                <span class="absolute right-4 top-4">
                    <x-icons.bookmark />
                </span>
            </div>
        </div>
         <div class="mb-4 flex items-center justify-between gap-4">
             <div class="w-full" x-data x-init="flatpickr($refs.inputDate, {dateFormat: 'Y-m-d'});">
                 <label for="s-date" class="mb-2.5 block font-medium text-black dark:text-white">
                     Date
                 </label>
                 <div class="relative">
                     <x-elements.text-input wire:model="date" id="s-date" x-ref="inputDate" type="text" name="s-date" placeholder="Date" autofocus autocomplete="name" />
                     <x-elements.input-error :messages="$errors->get('s-date')" class="mt-2" />
                     <span class="absolute right-4 top-4">
                         <x-icons.booking />                     
                     </span>
                 </div>
             </div>
             <div class="w-full">
                 <label for="slots" class="mb-2.5 block font-medium text-black dark:text-white">
                     Slots
                 </label>
                 <div class="relative">
                     <x-elements.text-input wire:model="slots" id="slots" type="text" name="slots" placeholder="Slots" autofocus autocomplete="slots" />
                     <x-elements.input-error :messages="$errors->get('slots')" class="mt-2" />
                     <span class="absolute right-4 top-4">
                         <x-icons.instructor />                     
                     </span>
                 </div>
             </div>
         </div>
     
        <div class="mb-4">
            <label for="instr" class="mb-2.5 block font-medium text-black dark:text-white">
                Assigned Instructor
            </label>
            <div class="relative">
                <x-elements.select wire:model="instructor" id="instr">
                <option value="">Select Instructor</option>
                @foreach ($instructors as $instructor)
                    <option value={{$instructor->id}}>{{$instructor->firstname}} {{$instructor->lastname}}</option>
                @endforeach
                </x-elements.select>
                <x-elements.input-error :messages="$errors->get('instructor')" class="mt-2" />
            </div>
        </div>
         
         <div class="mb-4 flex items-center justify-between gap-4">
             <div class="w-full">
                <label for="s-type" class="mb-2.5 block font-medium text-black dark:text-white">
                    Type
                </label>
                <div class="relative">
                    <x-elements.select wire:model="type" id="s-type">
                        <option value="">Schedule Type</option>
                        <option value="course">Course</option>
                        <option value="theoretical">Theoretical Test</option>
                        <option value="pratical">Practical Test</option>
                    </x-elements.select>
                    <x-elements.input-error :messages="$errors->get('type')" class="mt-2" />
                </div>
             </div>
             <div x-data="{ amount: '', minLength: 11 }" class="w-full">
                 <label for="amount" class="mb-2.5 block font-medium text-black dark:text-white">
                     Amount
                 </label>
                 <div class="relative">
                     <x-elements.text-input x-model="amount" wire:model="amount" id="amount" type="text" name="amount" placeholder="Amount" autofocus autocomplete="phone_number" @input="amount = amount.replace(/[^0-9]/g, '')"
                     @keydown="if (amount.length >= minLength && event.keyCode !== 8 && event.keyCode !== 46) event.preventDefault()" />
                     <x-elements.input-error :messages="$errors->get('amount')" class="mt-2" />
                     <span class="absolute right-4 top-4">
                         <x-icons.money />                     
                     </span>
                 </div>
             </div>
         </div>
         
         <div class="mb-5">
         <input
             type="submit"
             value="{{ $schedule_id ? 'Update Schedule' : 'Create Schedule' }}"
             class="w-full cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90"
         />
         </div>
     </form>
    </div>
</div>


