<x-app-layout>
    <x-slot name="title">Schedule</x-slot>

    <div class="rounded-sm border border-stroke bg-white px-5 pb-3 pt-8 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-8 xl:pb-2">
        @if(auth()->user()->role !== 'admin')
            <livewire:schedule-datatable.schedule-enroll.datatable />
        @else
            <livewire:schedule-datatable.datatable />
        @endif
    </div>

    
</x-app-layout>