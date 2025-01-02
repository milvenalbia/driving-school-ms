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
        {{-- <button class="bg-primary py-2 px-4 text-white rounded-md" wire:click="generatePDF">Generate PDF</button> --}}
       </div>
        
    </header>

    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-2 text-left dark:bg-meta-4 border-b border-gray dark:border-bodydark">
                    {{-- <th class="py-3 px-4">
                        <input type="checkbox" class="w-4 h-4 cursor-pointer" wire:model.live="selectAll" />
                    </th> --}}
                    <livewire:datatable-component.th-cell field="" label="Student ID"  />
                    <livewire:datatable-component.th-cell field="" label="Student Name"/>
                    <livewire:datatable-component.th-cell field="" label="Course Name"/>
                    <livewire:datatable-component.th-cell field="" label="Type"/>
                    <livewire:datatable-component.th-cell field="" label="Grade"/>
                    <livewire:datatable-component.th-cell field="" label="Instructor" />
                    <livewire:datatable-component.th-cell field="" label="Remarks"/>
                    <livewire:datatable-component.th-cell field="" label="Action"/>
                </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr wire:key="student-{{ $student->id }}" class="border-b border-stroke text-base dark:border-bodydark">
                    {{-- <td class="py-3 px-4 ">
                        <input type="checkbox" class="w-4 h-4 cursor-pointer" wire:model.live="student_id" value="{{ $student->id }}" />
                    </td> --}}
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
                    <td class="py-3 px-4">{{ $student->grade }}</td>
                    <td class="py-3 px-4">{{ $student->schedule->instructorBy->firstname }} {{ $student->schedule->instructorBy->lastname }}</td>
                    <td class="py-3 px-4">
                        @if ($student->remarks === 1)
                            <span class="py-2 px-3 rounded-full bg-success text-white text-sm">Passed</span>
                        @elseif($student->remarks === 0)
                            <span class="py-2 px-3 rounded-full bg-red-400 text-white text-sm">Failed</span>
                        @else
                            <span class="py-2 px-3 rounded-full bg-yellow-400 text-white text-sm text-nowrap">In Progress</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        @if($student->course->payments->status === 'paid')
                            <a href="{{ url('/generate-student-certificate/' . $student->student->user_id . '/' . $student->id) }}" target="_blank" class="border-2 border-primary rounded-md py-1 px-2 text-primary flex items-center hover:text-white hover:bg-primary transition ease-linear">
                                Certificate
                            </a>
                        @else
                            <span class="border-2 border-gray-400 rounded-md py-1 px-2 text-gray-400 flex items-center cursor-not-allowed">
                                Certificate
                            </span>
                        @endif
                        {{-- <a href="{{ url('/generate-student-certificate/' . $student->student->user_id . '/' . $student->id) }}" target="_blank" class="border-2 border-primary rounded-md py-1 px-2 text-primary flex items-center hover:text-white hover:bg-primary transition ease-linear">
                            Certificate
                        </a> --}}
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
</div>


