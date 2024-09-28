<div class="w-full border-stroke dark:border-strokedark">
    <div class="w-full p-4 sm:p-12.5 xl:p-10">
    <div class="flex items-center justify-between mb-6 ">
        <h2 class="text-2xl font-bold text-black dark:text-white sm:text-title-xl2" >
           Enroll Student
        </h2>
        <button @click="show = false" class="hover:text-red-500" wire:click="formClose"> 
            <x-icons.close />
        </button>
    </div>
    <form wire:submit.prevent="enroll_student"  x-data="{ suggestion: @entangle('suggestions').length > 0 ? true : false, selected: ''}"> 
        {{-- wire:submit.prevent="{{ $schedule_id ? 'update_schedule' : 'register_schedule' }}" --}}

        <div class="mb-4 relative">
            <label for="student" class="mb-2.5 block font-medium text-black dark:text-white">
                Select Student
            </label>
            <x-elements.text-input 
                type="text" 
                placeholder="Search Student" 
                wire:model.live.debounce.300ms="search"
                x-model="selected"
                @focus="suggestion = true" 
                @blur="setTimeout(() => suggestion = false, 200)" 
            />
            <x-elements.text-input 
                type="hidden" 
                wire:model="student_id"
                x-model="selected"
                x-init="$watch('selected', value => $wire.set('student_id', value))"
            />
        <x-elements.input-error :messages="$errors->get('search')" class="mt-2" />
        
            <!-- Suggestions Dropdown -->
            <ul x-show="suggestion" class="absolute z-10 shadow-md rounded-md top-22 border border-blue-300 bg-white mt-1 w-full h-auto max-h-[250px] overflow-auto py-3 px-4">
                @forelse($suggestions as $suggestion)
                    <li class="p-2 cursor-pointer hover:bg-blue-400 hover:text-white ease-linear"
                    @click="selected = '{{ $suggestion->user_id }}'; suggestion = false"
                    >
                        {{ $suggestion->user_id }} ({{ $suggestion->firstname }} {{ $suggestion->lastname }})
                    </li>
                @empty
                    <li class="p-2">No results found.</li>
                @endforelse
            </ul>
        </div>
        
         
         <div class="mb-5">
         <input
             type="submit"
             value="Enroll Student"
             class="w-full cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90"
         />
         </div>

         {{-- value="{{ $schedule_id ? 'Update Schedule' : 'Create Schedule' }}" --}}
     </form>
    </div>
</div>
