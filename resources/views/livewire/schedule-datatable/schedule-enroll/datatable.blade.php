{{-- Create User and Edit User 1 Form --}}
<div class="w-full border-stroke dark:border-strokedark">
    <div class="w-full p-4 sm:p-12.5 xl:p-10">
    <div class="flex items-center justify-between mb-6 ">
        <h2 class="text-2xl font-bold text-black dark:text-white sm:text-title-xl2" >
            Student List
        </h2>
        <button @click="show = false" class="hover:text-red-500" wire:click="reset_schedId"> 
            <x-icons.close />
        </button>
    </div>

    <div>
        <header class="w-full flex justify-between items-center mb-8">
            <div class="relative w-full">
                <x-icons.search class="absolute top-3 left-2" />
                <input
                class="input w-full rounded-md px-8 py-2 border border-stroke focus:outline-none focus:border-blue-500 transition-all duration-300 shadow-md shadow-stroke dark:shadow-none dark:bg-transparent dark:border-bodydark"
                placeholder="Search User ID..."
                type="text"
                wire:model.live.debounce.300ms="search"
                />
           </div>
           <div class="flex items-center gap-2 w-full justify-end">
            <select wire:model.live="perPage" class="p-2 border border-stroke rounded-md cursor-pointer focus:outline-none focus:border-blue-500 transition-all duration-300 shadow-md shadow-stroke dark:shadow-none dark:bg-transparent dark:border-bodydark">
                <option value="5">5 per page</option>
                <option value="10">10 per page</option>
                <option value="15">15 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
            </select>
           </div>
            
        </header>
    
        <div class="max-w-full overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-2 text-left dark:bg-meta-4 border-b border-gray dark:border-bodydark">
                        <livewire:datatable-component.th-cell field="id" label="ID" :sortBy="$sortBy" :sortDirection="$sortDirection" />
                        <livewire:datatable-component.th-cell field="" label="Student Id"/>
                        <livewire:datatable-component.th-cell field="" label="Student Name" />
                        <livewire:datatable-component.th-cell field="" label="Course Name" />
                        <livewire:datatable-component.th-cell field="" label="Instructor" />
                        <livewire:datatable-component.th-cell field="" label="Date"/>
                        <livewire:datatable-component.th-cell field="" label="Type"/>
                        <livewire:datatable-component.th-cell field="" label="Amount" />
                        <livewire:datatable-component.th-cell field="" label="Day Attended" />
                        <th class="py-3 px-4 flex items-center gap-1">
                            <span>Actions</span>
                        </th>
                    </tr>
            </thead>
            <tbody>
                @forelse($enrollees as $enrollee)
                    <tr wire:key="enrollee-{{ $enrollee->id }}" class="border-b border-stroke text-base dark:border-bodydark">
                        <td class="py-3 px-4">{{ $enrollee->id }}</td>
                        <td class="py-3 px-4 flex items-center gap-2 w-[250px]">
                            @if ($enrollee->student->image_path)
                                <img class="w-10 h-10 object-cover rounded-full" src="{{ Storage::url($enrollee->student->image_path) }}" alt="profile" >
                            @else
                                <img class="w-10 h-10 object-cover rounded-full" src="{{ asset('build/assets/images/profile.avif') }}" alt="profile" >
                            @endif
                            <span>{{ $enrollee->student->user_id }}</span>
                        </td>
                        <td class="py-3 px-4">{{ $enrollee->student->firstname }} {{ $enrollee->student->lastname }}</td>
                        <td class="py-3 px-4">{{ $enrollee->schedule->name }}</td>
                        <td class="py-3 px-4">{{ $enrollee->schedule->instructorBy->firstname }} {{ $enrollee->schedule->instructorBy->lastname }}</td>
                        <td class="py-3 px-4">{{ $enrollee->schedule->date }}</td>
                        <td class="py-3 px-4 capitalize">{{ $enrollee->schedule->type }}</td>
                        <td class="py-3 px-4">{{ $enrollee->schedule->amount }}</td>
                        <td class="py-3 px-4">
                            <label class="relative text-[#008080] flex cursor-pointer items-center gap-2"
                                for="day-1-{{ $enrollee->id }}"
                                >
                                <input class="peer appearance-none" id="day-1-{{ $enrollee->id }}" name="tick" type="checkbox" />
                                <span
                                    class="absolute left-0 top-1/2 h-[1rem] w-[1rem] -translate-x-full -translate-y-1/2 rounded-[0.25em] border-[2px] border-[#008080]"
                                >
                                </span>
                                <x-icons.checkbox />

                                <p class="text-base [user-select:none]">Day 1</p>
                            </label>
                        </td>
                        <td class="py-3 px-4 flex gap-2">
                            <button class="text-secondary flex items-center hover:text-primary transition ease-linear" wire:click="edit_enrollee({{ $enrollee->id }})">
                                <x-icons.edit />
                                <span>Edit</span>
                            </button>
                            <button class="text-red-500 flex items-center hover:text-red-700 transition ease-linear" wire:click="delete_enrollee({{ $enrollee->id }})">
                                <x-icons.delete />
                                <span>Remove</span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr class="border-b border-stroke text-base">
                        <td class="py-3 px-4 text-center" colspan="9">No data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    
        {{-- wire:confirm.prompt="Delete confirmation, type DELETE to delete user. |DELETE" --}}
    
        <div class="mt-4">
            {{ $enrollees->links() }}
        </div>
    
    </div> 
    
    <x-elements.notification >
        <x-slot:svg>
            <x-icons.success class="text-[#06D001]" />
        </x-slot:svg>
        {{session('success')}}
    </x-elements.notification>

    </div>
</div>

