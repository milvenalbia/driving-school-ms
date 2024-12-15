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
                        <livewire:datatable-component.th-cell field="" label="Theoretical Grade"/>
                        <livewire:datatable-component.th-cell field="" label="Practical Grade"/>
                        <livewire:datatable-component.th-cell field="" label="Instructor" />
                        <livewire:datatable-component.th-cell field="" label="Remarks"/>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr wire:key="student-{{ $student->id }}" class="border-b border-stroke text-base dark:border-bodydark">
                            <td class="py-3 px-4">{{ $student->schedule->name }}</td>
                            <td class="py-3 px-4">{{ $student->theoritical_grade }}</td>
                            <td class="py-3 px-4">{{ $student->practical_grade }}</td>
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