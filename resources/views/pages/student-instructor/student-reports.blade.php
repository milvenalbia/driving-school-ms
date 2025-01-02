<x-app-layout>
    <x-slot name="title">
            Student Reports
    </x-slot>

    <div class="rounded-sm border border-stroke bg-white px-5 pb-3 pt-8 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-8 xl:pb-2 mt-8">
        <div>
            <header class="w-full flex justify-between items-center mb-8">
                <h2 class="text-title-md font-bold text-black capitalize">Reports</h2>     
            </header>
    
            <div class="max-w-full overflow-x-auto">
              <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-2 text-left dark:bg-meta-4 border-b border-gray dark:border-bodydark">
                        <livewire:datatable-component.th-cell field="" label="Course Name"/>
                        <livewire:datatable-component.th-cell field="" label="Type"/>
                        <livewire:datatable-component.th-cell field="" label="Attendance"/>
                        <livewire:datatable-component.th-cell field="" label="Grade"/>
                        <livewire:datatable-component.th-cell field="" label="Instructor" />
                        <livewire:datatable-component.th-cell field="" label="Remarks"/>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr wire:key="student-{{ $student->id }}" class="border-b border-stroke text-base dark:border-bodydark">
                            <td class="py-3 px-4">{{ $student->schedule->name }}</td>
                            <td class="py-3 px-4 capitalize">{{ $student->type }}</td>
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
                        </tr>   
                    @empty
                        <tr class="border-b border-stroke text-base">
                            <td class="py-3 px-4 text-center" colspan="8">{{'No data available.'}}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
    
        </div>
    </div>
    
</x-app-layout>