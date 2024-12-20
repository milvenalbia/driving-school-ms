{{-- Create User and Edit User 1 Form --}}
<div class="w-full border-stroke dark:border-strokedark">
    <div class="w-full p-4 sm:p-12.5 xl:p-10">
    <div class="flex items-center justify-between mb-6 ">
        <h2 class="text-2xl font-bold text-black dark:text-white sm:text-title-xl2 capitalize" >
            @if(auth()->user()->role !== "student")
                Student List ({{$type}})
            @else
                Schedules Information
            @endif
        </h2>
        @if(auth()->user()->role !== "student")
        <button @click="show = false" class="hover:text-red-500" wire:click="reset_schedId"> 
            <x-icons.close />
        </button>
        @endif
    </div>

    <div>
        @if(auth()->user()->role !== "student")
        <header class="w-full flex justify-between items-center mb-8">
            <div class="relative w-full">
                <x-icons.search class="absolute top-3 left-2" />
                <input
                class="input w-full rounded-md px-8 py-2 border border-stroke focus:outline-none focus:border-blue-500 transition-all duration-300 shadow-md shadow-stroke dark:shadow-none dark:bg-transparent dark:border-bodydark"
                placeholder="Search..."
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
        @endif
    
        <div class="max-w-full overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-2 text-left dark:bg-meta-4 border-b border-gray dark:border-bodydark">
                        @if(auth()->user()->role !== "student")
                        <livewire:datatable-component.th-cell field="id" label="ID" :sortBy="$sortBy" :sortDirection="$sortDirection" />
                        <livewire:datatable-component.th-cell field="" label="Student Id"/>
                        <livewire:datatable-component.th-cell field="" label="Student Name" />
                        @endif
                        <livewire:datatable-component.th-cell field="" label="Course Name" />
                        <livewire:datatable-component.th-cell field="" label="Instructor" />
                        <livewire:datatable-component.th-cell field="" label="Date"/>
                        <livewire:datatable-component.th-cell field="" label="Amount" />
                        @if(auth()->user()->role === "student")
                            <livewire:datatable-component.th-cell field="" label="Type" />
                            <livewire:datatable-component.th-cell field="" label="Description" />
                        @endif

                        @if(auth()->user()->role !== "student")
                        @if($type === 'theoretical')
                            <livewire:datatable-component.th-cell field="" label="Day Attended" />
                        @endif
                        <livewire:datatable-component.th-cell field="" label="Status" />
                        <livewire:datatable-component.th-cell field="" label="Pay Status" />
                        <th class="py-3 px-4">
                            <span>Actions</span>
                        </th>
                        @endif
                    </tr>
            </thead>
            <tbody>
                @forelse($enrollees as $enrollee)
                    <tr wire:key="enrollee-{{ $enrollee->id }}" class="border-b border-stroke text-base dark:border-bodydark">
                        @if(auth()->user()->role !== "student")
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
                        @endif
                        <td class="py-3 px-4">{{ $enrollee->schedule->name }}</td>
                        <td class="py-3 px-4">{{ $enrollee->schedule->instructorBy->firstname }} {{ $enrollee->schedule->instructorBy->lastname }}</td>
                        <td class="py-3 px-4">
                            <div class="w-[100px]">
                                {{ $enrollee->schedule->date }}
                            </div>
                        </td>
                        <td class="py-3 px-4">{{ $enrollee->schedule->amount }}</td>
                        @if(auth()->user()->role === "student")
                        <td class="py-3 px-4 capitalize">{{ $enrollee->schedule->type }}</td>
                        <td class="py-3 px-4">{{$enrollee->schedule->type === 'practical' ? '--' : 'You are required to complete the 3 days theoretical classes in order to proceed in practical driving lesson'}}</td>
                        @endif

                        @if($type === 'theoretical' && auth()->user()->role !== "student")
                        <td class="py-3 px-4">
                            <div class="flex rounded-md overflow-hidden shadow-sm" wire:key="day-{{$enrollee->id}}">
                                <button 
                                    wire:click="setAttendance({{ $enrollee->id }}, 1)"
                                    class="px-2 py-1 text-sm text-nowrap border border-gray-200 rounded-l-md border-r-0 transition-colors duration-200 
                                    {{ $enrollee->day1_status === 'present' ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}"
                                >
                                    Day 1
                                </button>
                                <button 
                                    wire:click="setAttendance({{ $enrollee->id }}, 2)"
                                    class="px-2 py-1 text-sm text-nowrap border border-gray-200 border-r-0 transition-colors duration-200 
                                    {{ $enrollee->day2_status === 'present' ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}"
                                >
                                    Day 2
                                </button>
                                <button 
                                    wire:click="setAttendance({{ $enrollee->id }}, 3)"
                                    class="px-2 py-1 text-sm text-nowrap border border-gray-200 rounded-r-md transition-colors duration-200 
                                    {{ $enrollee->day3_status === 'present' ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}"
                                >
                                    Day 3
                                </button>
                            </div>
                        </td>
                        @endif

                        @if(auth()->user()->role !== "student")
                        <td class="py-3 px-4">
                            @if ($enrollee->grade && $enrollee->grade > 74)
                                <span class="py-2 px-3 rounded-full bg-success text-white text-sm">Passed</span>
                            @elseif($enrollee->grade && $enrollee->grade < 75)
                                <span class="py-2 px-3 rounded-full bg-red-400 text-white text-sm">Failed</span>
                            @else
                                <span class="py-2 px-3 rounded-full bg-yellow-400 text-white text-sm text-nowrap">In Progress</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if ($enrollee->payments->status === 'paid')
                                <span class="py-2 px-3 rounded-full bg-success text-white text-sm">Paid</span>
                            @elseif($enrollee->payments->status === 'partial')
                                <span class="py-2 px-3 rounded-full bg-blue-300 text-white text-sm">Partial</span>
                            @else
                                <span class="py-2 px-3 rounded-full bg-red-400 text-white text-sm">Unpaid</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                {{-- <button 
                                    class="border-2 border-secondary rounded-md py-1 px-2 text-secondary flex items-center hover:text-white hover:bg-secondary transition ease-linear @if($enrollee->payments->status !== 'paid') cursor-not-allowed @endif" 
                                    wire:click="edit_enrollee({{ $enrollee->id }})" 
                                    @disabled($enrollee->payments->status !== 'paid')>
                                    <x-icons.edit />
                                    <span>Edit</span>
                                </button> --}}
                                <button 
                                    class="border-2 border-secondary rounded-md py-1 px-2 text-secondary flex items-center hover:text-white hover:bg-secondary transition ease-linear" 
                                    wire:click="edit_enrollee({{ $enrollee->id }})"
                                   >
                                    <x-icons.edit />
                                    <span>Edit</span>
                                </button>
                                @if(auth()->user()->role === "admin")
                                    <button class="border-2 border-red-500 rounded-md py-1 px-2 text-red-500 flex items-center hover:text-white hover:bg-red-500 transition ease-linear" @if($enrollee->grade) wire:confirm.prompt="The selected student already has a grade. Are you sure you want to proceed with deletion? Type REMOVE to confirm student removal. |REMOVE" @else wire:confirm.prompt="Remove confirmation, type REMOVE to remove student. |REMOVE" @endif wire:click="delete_enrollee({{ $enrollee->id }})">
                                        <x-icons.delete />
                                        <span>Remove</span>
                                    </button>
                                @endif
                            </div>
                        </td>
                        @endif
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
    
        @if(auth()->user()->role !== "student")
            <div class="mt-4">
                {{ $enrollees->links() }}
            </div>
        @endif
    </div>
    
    {{-- @if(auth()->user()->role === "instructor")
    <x-elements.modal name="edit-enroll-students">
        <livewire:schedule-datatable.schedule-enroll.edit-enroll />
    </x-elements.modal>
    @endif --}}
    
    <x-elements.notification >
        <x-slot:svg>
            <x-icons.success class="text-[#06D001]" />
        </x-slot:svg>
        {{session('success')}}
    </x-elements.notification>

    </div>
</div>

