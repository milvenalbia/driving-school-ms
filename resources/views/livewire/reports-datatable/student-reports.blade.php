<div>
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
        <button class="bg-primary py-2 px-4 text-white rounded-md" wire:click="generatePDF">Generate PDF</button>
       </div>
        
    </header>

    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-2 text-left dark:bg-meta-4 border-b border-gray dark:border-bodydark">
                    <th class="py-3 px-4">
                        <input type="checkbox" class="w-4 h-4 cursor-pointer" wire:model.live="selectAll" />
                    </th>
                    <livewire:datatable-component.th-cell field="" label="Student ID"  />
                    <livewire:datatable-component.th-cell field="" label="Student Name"/>
                    <livewire:datatable-component.th-cell field="" label="Course Name"/>
                    <livewire:datatable-component.th-cell field="" label="Type"/>
                    <livewire:datatable-component.th-cell field="" label="Attendance"/>
                    @if(auth()->user()->role === "admin")
                    <livewire:datatable-component.th-cell field="" label="Grade"/>
                    @endif

                    @if(auth()->user()->role === "instructor")
                    <livewire:datatable-component.th-cell field="" label="Grade"/>
                    @endif
                    <livewire:datatable-component.th-cell field="" label="Instructor" />
                    {{-- <livewire:datatable-component.th-cell field="" label="Hours"/> --}}
                    <livewire:datatable-component.th-cell field="" label="Remarks"/>
                    {{-- @if(auth()->user()->role === "admin")
                    <livewire:datatable-component.th-cell field="" label="Action"/>
                    @endif --}}
                </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr wire:key="student-{{ $student->id }}" class="border-b border-stroke text-base dark:border-bodydark">
                    <td class="py-3 px-4 ">
                        <input type="checkbox" class="w-4 h-4 cursor-pointer" wire:model.live="student_id" value="{{ $student->id }}" />
                    </td>
                    <td class="py-3 px-4 flex items-center gap-2 w-[250px]">
                        @if ($student->student->image_path)
                            <img class="w-10 h-10 object-cover rounded-full" src="{{ Storage::url($student->student->image_path) }}" alt="profile" >
                        @else
                            <img class="w-10 h-10 object-cover rounded-full" src="{{ asset('build/assets/images/profile.avif') }}" alt="profile" >
                        @endif
                        <span>{{ $student->student->user_id }}</span>
                    </td>
                    <td class="py-3 px-4">{{ $student->student->firstname }} {{ $student->student->lastname }}</td>
                    <td class="py-3 px-4">{{ $student->schedule->name }}</td>
                    <td class="py-3 px-4 capitalize">{{ $student->schedule->type }}</td>
                    <td class="py-3 px-4">
                        @if($student->schedule->type === 'theoretical')
                        <div class="flex rounded-md overflow-hidden shadow-sm" wire:key="day-{{$student->id}}">
                            <button 
                                class="px-2 py-1 text-sm text-nowrap border border-gray-200 rounded-l-md border-r-0 transition-colors duration-200 
                                {{ $student->course->day1_status === 'present' ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}"
                            >
                                Day 1
                            </button>
                            <button
                                class="px-2 py-1 text-sm text-nowrap border border-gray-200 border-r-0 transition-colors duration-200 
                                {{ $student->course->day2_status === 'present' ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}"
                            >
                                Day 2
                            </button>
                            <button 
                                class="px-2 py-1 text-sm text-nowrap border border-gray-200 rounded-r-md transition-colors duration-200 
                                {{ $student->course->day3_status === 'present' ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}"
                            >
                                Day 3
                            </button>
                        </div>
                        @else
                        <div class="flex rounded-md overflow-hidden shadow-sm" wire:key="day-{{$student->id}}">
                            @if($student->course->sessions === 1)
                                <button
                                    class="px-2 py-1 text-sm text-nowrap border border-gray-200 rounded-md border-r-0 transition-colors duration-200 
                                    {{ $student->course->day1_status === 'present' ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}"
                                >
                                    Day 1
                                </button>
                            @elseif($student->course->sessions === 2)
                                <button 
                                    class="px-2 py-1 text-sm text-nowrap border border-gray-200 rounded-l-md border-r-0 transition-colors duration-200 
                                    {{ $student->course->day1_status === 'present' ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}"
                                >
                                    Day 1
                                </button>
                                <button
                                    class="px-2 py-1 text-sm text-nowrap border border-gray-200 rounded-r-md transition-colors duration-200 
                                    {{ $student->course->day2_status === 'present' ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}"
                                >
                                    Day 2
                                </button>
                            @elseif($student->course->sessions === 3)
                                <button
                                    class="px-2 py-1 text-sm text-nowrap border border-gray-200 rounded-l-md border-r-0 transition-colors duration-200 
                                    {{ $student->course->day1_status === 'present' ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}"
                                >
                                    Day 1
                                </button>
                                <button
                                    class="px-2 py-1 text-sm text-nowrap border border-gray-200 transition-colors duration-200 
                                    {{ $student->course->day2_status === 'present' ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}"
                                >
                                    Day 2
                                </button>
                                <button
                                    class="px-2 py-1 text-sm text-nowrap border border-gray-200 rounded-r-md transition-colors duration-200 
                                    {{ $student->course->day3_status === 'present' ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}"
                                >
                                    Day 3
                                </button>
                            @else
                                <button 
                                    class="px-2 py-1 text-sm text-nowrap border border-gray-200 rounded-l-md border-r-0 transition-colors duration-200 
                                    {{ $student->course->day1_status === 'present' ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}"
                                >
                                    Day 1
                                </button>
                                <button
                                    class="px-2 py-1 text-sm text-nowrap border border-gray-200 border-r-0 transition-colors duration-200 
                                    {{ $student->course->day2_status === 'present' ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}"
                                >
                                    Day 2
                                </button>
                                <button
                                    class="px-2 py-1 text-sm text-nowrap border border-gray-200 rounded-r-0 transition-colors duration-200 
                                    {{ $student->course->day3_status === 'present' ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}"
                                >
                                    Day 3
                                </button>
                                <button
                                    class="px-2 py-1 text-sm text-nowrap border border-gray-200 rounded-r-md transition-colors duration-200 
                                    {{ $student->course->day4_status === 'present' ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}"
                                >
                                    Day 4
                                </button>
                            @endif
                        </div>    
                        @endif
                    </td>

                    @if(auth()->user()->role === "admin")
                        <td class="py-3 px-4">{{ $student->grade }}</td>
                    @else
                        <td class="py-3 px-4">{{ $student->grade }}</td>
                    @endif

                    <td class="py-3 px-4">{{ $student->schedule->instructorBy->firstname }} {{ $student->schedule->instructorBy->lastname }}</td>
                    {{-- <td class="py-3 px-4">{{ $student->hours }}</td> --}}
                    @if(auth()->user()->role === "admin")
                        <td class="py-3 px-4">
                            @if(!$student->theoritical_grade && $student->practical_grade)
                                @if ($student->practical_grade > 74)
                                    <span class="py-2 px-3 rounded-full bg-success text-white text-sm">Passed</span>
                                @elseif($student->practical_grade < 75)
                                    <span class="py-2 px-3 rounded-full bg-red-400 text-white text-sm">Failed</span>
                                @else
                                    <span class="py-2 px-3 rounded-full bg-yellow-400 text-white text-sm text-nowrap">In Progress</span>
                                @endif
                            @else
                                @if ($student->remarks === 1)
                                    <span class="py-2 px-3 rounded-full bg-success text-white text-sm">Passed</span>
                                @elseif($student->remarks === 0)
                                    <span class="py-2 px-3 rounded-full bg-red-400 text-white text-sm">Failed</span>
                                @else
                                    <span class="py-2 px-3 rounded-full bg-yellow-400 text-white text-sm text-nowrap">In Progress</span>
                                @endif
                            @endif
                        </td>
                    @else
                        <td class="py-3 px-4">
                            @if ($student->remarks === 1)
                                <span class="py-2 px-3 rounded-full bg-success text-white text-sm">Passed</span>
                            @elseif($student->remarks === 0)
                                <span class="py-2 px-3 rounded-full bg-red-400 text-white text-sm">Failed</span>
                            @else
                                <span class="py-2 px-3 rounded-full bg-yellow-400 text-white text-sm text-nowrap">In Progress</span>
                            @endif
                        </td>
                    @endif
                    {{-- @if(auth()->user()->role === "admin")
                    <td class="py-3 px-4">
                        @if($student->remarks === 1)
                            <a href="{{ url('/generate-certificate/' . $student->student->user_id . '/' . $student->id) }}" target="_blank" class="border-2 border-primary rounded-md py-1 px-2 text-primary flex items-center hover:text-white hover:bg-primary transition ease-linear">
                                Certificate
                            </a>
                        @else
                            <span class="border-2 border-gray-400 rounded-md py-1 px-2 text-gray-400 flex items-center cursor-not-allowed">
                                Certificate
                            </span>
                        @endif
                    </td>
                    @endif --}}
                </tr>
            @empty
                <tr class="border-b border-stroke text-base">
                    <td class="py-3 px-4 text-center" colspan="8">No data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    {{-- wire:confirm.prompt="Delete confirmation, type DELETE to delete user. |DELETE" --}}

    <div class="mt-4">
        {{ $students->links() }}
    </div>

    <x-elements.notification class="bg-red-900" >
        <x-slot:svg>
            <x-icons.warning class="text-red-500" />
        </x-slot:svg>
        {{session('error')}}
    </x-elements.notification>

    @script
        <script>
            $wire.on('openInNewTab', (url) => {
                window.open(url, '_blank');
            });
        </script>
    @endscript
</div>


