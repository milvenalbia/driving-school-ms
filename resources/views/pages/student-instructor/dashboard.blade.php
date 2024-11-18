<x-app-layout>
    <x-slot name="title">
            Dashboard
    </x-slot>
    <div
    class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-2 2xl:gap-7.5"
  >
    <div
    class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark"
  >
    <div
      class="flex h-11.5 w-11.5 items-center justify-center text-primary rounded-full bg-meta-2 dark:bg-meta-4"
    >
      <x-icons.money/>
    </div>

    <div class="mt-4 flex items-end justify-between">
      <div>
        <h4
          class="text-title-md font-bold text-black dark:text-white"
        >
          5
        </h4>
        <span class="text-sm font-medium">Total Instructors</span>
      </div>
    </div>
  </div>
  <!-- Card Item End -->

  <!-- Card Item Start -->
  <div
    class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark"
  >
    <div
      class="flex h-11.5 w-11.5 items-center justify-center text-primary rounded-full bg-meta-2 dark:bg-meta-4"
    >
      <x-icons.booking />
    </div>

    <div class="mt-4 flex items-end justify-between">
      <div>
        <h4
          class="text-title-md font-bold text-black dark:text-white"
        >
          10
        </h4>
        <span class="text-sm font-medium">Schedules</span>
      </div>
    </div>
  </div>
  <!-- Card Item End -->
</div>

{{-- Table --}}
<div class="rounded-sm border border-stroke bg-white px-5 pb-3 pt-8 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-8 xl:pb-2 mt-8">
    <div>
        <header class="w-full flex justify-between items-center mb-8">
            <h2 class="text-title-md font-bold text-black capitalize">{{$studentName}} Reports</h2>
        </div>
            
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