<x-app-layout>
    <x-slot name="title">
            Dashboard
    </x-slot>
    <div
    class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4 2xl:gap-7.5"
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
          {{$data['sales']}}
        </h4>
        <span class="text-sm font-medium">Total Sales</span>
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
          {{$data['schedules']}}
        </h4>
        <span class="text-sm font-medium">Schedules</span>
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
      <x-icons.student />
    </div>

    <div class="mt-4 flex items-end justify-between">
      <div>
        <h4
          class="text-title-md font-bold text-black dark:text-white"
        >
          {{$data['students']}}
        </h4>
        <span class="text-sm font-medium">Total Students</span>
      </div>
    </div>
  </div>
  <!-- Card Item End -->

  <!-- Card Item Start -->
  <div
    class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark"
  >
    <div
      class="flex h-11.5 w-11.5 items-center justify-center rounded-full text-primary bg-meta-2 dark:bg-meta-4"
    >
      <x-icons.instructor />
    </div>

    <div class="mt-4 flex items-end justify-between">
      <div>
        <h4
          class="text-title-md font-bold text-black dark:text-white"
        >
          {{$data['instructors']}}
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
  <x-icons.bookmark />
</div>

<div class="mt-4 flex items-end justify-between">
  <div>
    <h4
      class="text-title-md font-bold text-black dark:text-white"
    >
      {{$data['theoretical_students']}} <span class="text-boxdark text-[10px]">Enrolled</span>
    </h4>
    <span class="text-sm font-medium">Theoretical Students</span>
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
  <x-icons.car />
</div>

<div class="mt-4 flex items-end justify-between">
  <div>
    <h4
      class="text-title-md font-bold text-black dark:text-white"
    >
      {{$data['practical_students']}} <span class="text-boxdark text-[10px]">Enrolled</span>
    </h4>
    <span class="text-sm font-medium">Practical Students</span>
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
  <x-icons.enrolled />
</div>

<div class="mt-4 flex items-end justify-between">
  <div>
    <h4
      class="text-title-md font-bold text-black dark:text-white"
    >
      {{$data['passed_students']}}
    </h4>
    <span class="text-sm font-medium">Passed Students</span>
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
  <x-icons.warning />
</div>

<div class="mt-4 flex items-end justify-between">
  <div>
    <h4
      class="text-title-md font-bold text-black dark:text-white"
    >
      {{$data['failed_students']}}
    </h4>
    <span class="text-sm font-medium">Failed Students</span>
  </div>
</div>
</div> 
<!-- Card Item End -->
</div>

<livewire:dashboard.chart-component />
</x-app-layout>