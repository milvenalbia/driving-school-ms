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
                    <livewire:datatable-component.th-cell field="" label="Full Name"/>
                    <livewire:datatable-component.th-cell field="" label="Email"/>
                    <livewire:datatable-component.th-cell field="" label="Contact No."/>
                    <livewire:datatable-component.th-cell field="" label="Gender"/>
                    <livewire:datatable-component.th-cell field="" label="Civil Status"/>
                    <livewire:datatable-component.th-cell field="" label="Theoretical"/>
                    <livewire:datatable-component.th-cell field="" label="Practical"/>
                </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr wire:key="student-{{ $student->id }}" class="border-b border-stroke text-base dark:border-bodydark">
                    <td class="py-3 px-4 ">
                        <input type="checkbox" class="w-4 h-4 cursor-pointer" wire:model.live="student_id" value="{{ $student->id }}" />
                    </td>
                    <td class="py-3 px-4 flex items-center gap-2">
                        @if ($student->image_path)
                            <img class="w-10 h-10 object-cover rounded-full" src="{{ Storage::url($student->image_path) }}" alt="profile" >
                        @else
                            <img class="w-10 h-10 object-cover rounded-full" src="{{ asset('build/assets/images/profile.avif') }}" alt="profile" >
                        @endif
                        <span>{{ $student->user_id }}</span>
                    </td>
                    <td class="py-3 px-4 ">{{$student->firstname}} {{$student->lastname}}</td>
                    <td class="py-3 px-4 ">{{$student->email}}</td>
                    <td class="py-3 px-4 ">{{$student->phone_number}}</td>
                    <td class="py-3 px-4 ">{{$student->gender ?? '--'}}</td>
                    <td class="py-3 px-4 ">{{$student->civil_status ?? '--'}}</td>
                    {{-- <td class="py-3 px-4">
                        @if ($student->theoretical_test === 1)
                            <span class="py-2 px-3 rounded-full bg-success text-white text-sm">Passed</span>
                        @elseif($student->theoretical_test === 0)
                            <span class="py-2 px-3 rounded-full bg-red-400 text-white text-sm">Failed</span>
                        @else
                            --
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        @if ($student->practical_test === 1)
                            <span class="py-2 px-3 rounded-full bg-success text-white text-sm">Passed</span>
                        @elseif($student->practical_test === 0)
                            <span class="py-2 px-3 rounded-full bg-red-400 text-white text-sm">Failed</span>
                        @else
                            --
                        @endif
                    </td> --}}
                    <td class="py-3 px-4">
                        @php 
                            $theoreticalRecord = $student->student_records()->where('type', 'theoretical')->latest('created_at')->first();
                        @endphp
                    
                        @if ($theoreticalRecord) <!-- Check if the record exists -->
                            @if (!is_null($theoreticalRecord->remarks))
                                @if ($theoreticalRecord->remarks)
                                    <span class="py-2 px-3 rounded-full bg-success text-white text-sm">Passed</span>
                                @elseif (!$theoreticalRecord->remarks)
                                    <span class="py-2 px-3 rounded-full bg-red-400 text-white text-sm">Failed</span>
                                @endif
                            @else
                                <span class="py-2 px-3 rounded-full bg-blue-400 text-white text-sm">Enrolled</span>
                            @endif
                        @else
                            --
                        @endif
                    </td>
                    
                    <td class="py-3 px-4">
                        @php 
                            $practicalRecord = $student->student_records()->where('type', 'practical')->latest('created_at')->first();
                        @endphp
                    
                        @if ($practicalRecord) <!-- Check if the record exists -->
                            @if (!is_null($practicalRecord->remarks))
                                @if ($practicalRecord->remarks)
                                    <span class="py-2 px-3 rounded-full bg-success text-white text-sm">Passed</span>
                                @elseif (!$practicalRecord->remarks)
                                    <span class="py-2 px-3 rounded-full bg-red-400 text-white text-sm">Failed</span>
                                @endif
                            @else
                                <span class="py-2 px-3 rounded-full bg-blue-400 text-white text-sm">Enrolled</span>
                            @endif
                        @else
                            --
                        @endif
                    </td>                                     
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
            $wire.on('openStudentListNewTab', (url) => {
                window.open(url, '_blank');
            });
        </script>
    @endscript
</div>


