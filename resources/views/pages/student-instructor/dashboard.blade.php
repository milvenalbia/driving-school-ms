<x-app-layout>
    <x-slot name="title">
            Dashboard
    </x-slot>
    <div
    class="grid grid-cols-1 gap-2 md:grid-cols-2 md:gap-2 xl:grid-cols-2 2xl:gap-2"
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
          {{auth()->user()->role === 'student' ? $instructor_count : $student_count}}
        </h4>
        <span class="text-sm font-medium">{{auth()->user()->role === 'student' ? 'Instructors' : 'Students'}}</span>
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
          {{$schedules}}
        </h4>
        <span class="text-sm font-medium">{{auth()->user()->role === 'student' ? 'Schedules' : 'Assigned Schedules'}}</span>
      </div>
    </div>
  </div>
  <!-- Card Item End -->
</div>

@if(auth()->user()->role === 'student')

<div class="grid grid-cols-1 gap-2 md:grid-cols-2 md:gap-2 xl:grid-cols-2 2xl:gap-2" >
  <div class="rounded-sm border border-stroke bg-white px-5 pb-3 pt-8 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-8 xl:pb-2 mt-8">
    <div>
        <header class="w-full flex justify-between items-center mb-8">
            <h2 class="text-title-md font-bold text-black capitalize">Theoretical Course Info</h2>     
        </header>

        <div class="max-w-full overflow-x-auto">
          <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-2 text-left dark:bg-meta-4 border-b border-gray dark:border-bodydark">
                    <livewire:datatable-component.th-cell field="" label="Course Name"/>
                    <livewire:datatable-component.th-cell field="" label="Instructor"/>
                    <livewire:datatable-component.th-cell field="" label="Date"/>
                    <livewire:datatable-component.th-cell field="" label="Amount"/>
                </tr>
            </thead>
            <tbody>
                @forelse($theoretical_students as $student)
                    <tr wire:key="student-{{ $student->id }}" class="border-b border-stroke text-base dark:border-bodydark">
                        <td class="py-3 px-4">{{ $student->schedule->name }}</td>
                        <td class="py-3 px-4">{{ $student->schedule->instructorBy->firstname }} {{ $student->schedule->instructorBy->lastname }}</td>
                        <td class="py-3 px-4">{{ $student->schedule->date }}</td>
                        <td class="py-3 px-4">{{ number_format($student->schedule->amount, 2) }}</td>
                    </tr>   
                @empty
                    <tr class="border-b border-stroke text-base">
                        <td class="py-3 px-4 text-center" colspan="4">{{'No data available.'}}</td>
                    </tr>
                @endforelse
            </tbody>
          </table>
        </div>

    </div>
</div>

<div class="rounded-sm border border-stroke bg-white px-5 pb-3 pt-8 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-8 xl:pb-2 mt-8">
  <div>
      <header class="w-full flex justify-between items-center mb-8">
          <h2 class="text-title-md font-bold text-black capitalize">Practical Course Info</h2>     
      </header>

      <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
          <thead>
                <tr class="bg-gray-2 text-left dark:bg-meta-4 border-b border-gray dark:border-bodydark">
                  <livewire:datatable-component.th-cell field="" label="Course Name"/>
                  <livewire:datatable-component.th-cell field="" label="Instructor"/>
                  <livewire:datatable-component.th-cell field="" label="Start Date"/>
                  <livewire:datatable-component.th-cell field="" label="Amount"/>
                </tr>
          </thead>
          <tbody>
              @forelse($practical_students as $student)
                  <tr wire:key="student-{{ $student->id }}" class="border-b border-stroke text-base dark:border-bodydark">
                    <td class="py-3 px-4">{{ $student->schedule->name }}</td>
                    <td class="py-3 px-4">{{ $student->schedule->instructorBy->firstname }} {{ $student->schedule->instructorBy->lastname }}</td>
                    <td class="py-3 px-4">{{ $student->start_date }}</td>
                    <td class="py-3 px-4">{{ number_format($student->schedule->amount, 2) }}</td>
                  </tr>   
              @empty
                  <tr class="border-b border-stroke text-base">
                      <td class="py-3 px-4 text-center" colspan="4">{{'No data available.'}}</td>
                  </tr>
              @endforelse
          </tbody>
        </table>
      </div>

  </div>
</div>
</div>

{{-- Table --}}
<div class="rounded-sm border border-stroke bg-white px-5 pb-3 pt-8 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-8 xl:pb-2 mt-8">
    <div>
        <header class="w-full flex justify-between items-center mb-8">
            <h2 class="text-title-md font-bold text-black capitalize">{{$studentName}} Reports</h2>     
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
                            @if ($student->remarks === "passed")
                                <span class="py-2 px-3 rounded-full bg-success text-white text-sm">Passed</span>
                            @elseif($student->remarks === "failed")
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

{{-- If Instructor --}}
@else
<div class="grid grid-cols-1 gap-2 md:grid-cols-2 md:gap-2 xl:grid-cols-2 2xl:gap-2" >
  <div class="rounded-sm border border-stroke bg-white px-5 pb-3 pt-8 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-8 xl:pb-2 mt-8">
    <div>
        <header class="w-full flex justify-between items-center mb-8">
            <h2 class="text-title-md font-bold text-black capitalize">Theoretical Student List</h2>     
        </header>

        <div class="max-w-full overflow-x-auto">
          <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-2 text-left dark:bg-meta-4 border-b border-gray dark:border-bodydark">
                    <livewire:datatable-component.th-cell field="" label="Course Name"/>
                    <livewire:datatable-component.th-cell field="" label="Student"/>
                    <livewire:datatable-component.th-cell field="" label="Attendance"/>
                    <livewire:datatable-component.th-cell field="" label="Remarks"/>
                </tr>
            </thead>
            <tbody>
                @forelse($theoretical_students as $student)
                    <tr wire:key="student-{{ $student->id }}" class="border-b border-stroke text-base dark:border-bodydark">
                        <td class="py-3 px-4">{{ $student->schedule->name }}</td>
                        <td class="py-3 px-4">{{ $student->student->firstname }} {{ $student->student->lastname }}</td>
                        <td class="py-3 px-4">{{ $student->course_attendance }}</td>
                        <td class="py-3 px-4">
                            @if ($student->remarks === "passed")
                                <span class="py-2 px-3 rounded-full bg-success text-white text-sm">Passed</span>
                            @elseif($student->remarks === "failed")
                                <span class="py-2 px-3 rounded-full bg-red-400 text-white text-sm">Failed</span>
                            @else
                                <span class="py-2 px-3 rounded-full bg-yellow-400 text-white text-sm text-nowrap">In Progress</span>
                            @endif
                        </td>
                    </tr>   
                @empty
                    <tr class="border-b border-stroke text-base">
                        <td class="py-3 px-4 text-center" colspan="4">{{'No data available.'}}</td>
                    </tr>
                @endforelse
            </tbody>
          </table>
        </div>

    </div>
</div>

<div class="rounded-sm border border-stroke bg-white px-5 pb-3 pt-8 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-8 xl:pb-2 mt-8">
  <div>
      <header class="w-full flex justify-between items-center mb-8">
          <h2 class="text-title-md font-bold text-black capitalize">Practical Student List</h2>     
      </header>

      <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
          <thead>
              <tr class="bg-gray-2 text-left dark:bg-meta-4 border-b border-gray dark:border-bodydark">
                  <livewire:datatable-component.th-cell field="" label="Course Name"/>
                  <livewire:datatable-component.th-cell field="" label="Student"/>
                  <livewire:datatable-component.th-cell field="" label="Start Date"/>
                  <livewire:datatable-component.th-cell field="" label="Remarks"/>
              </tr>
          </thead>
          <tbody>
              @forelse($practical_students as $student)
                  <tr wire:key="student-{{ $student->id }}" class="border-b border-stroke text-base dark:border-bodydark">
                      <td class="py-3 px-4">{{ $student->schedule->name }}</td>
                      <td class="py-3 px-4">{{ $student->student->firstname }} {{ $student->student->lastname }}</td>
                      <td class="py-3 px-4">{{ $student->start_date }}</td>
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
                      <td class="py-3 px-4 text-center" colspan="4">{{'No data available.'}}</td>
                  </tr>
              @endforelse
          </tbody>
        </table>
      </div>

  </div>
</div>
</div>
@endif

</x-app-layout>