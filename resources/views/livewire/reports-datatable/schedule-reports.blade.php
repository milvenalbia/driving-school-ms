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
                    <livewire:datatable-component.th-cell field="" label="Date"  />
                    <livewire:datatable-component.th-cell field="" label="Schedule Code"/>
                    <livewire:datatable-component.th-cell field="" label="Course Name"/>
                    <livewire:datatable-component.th-cell field="" label="Type"/>
                    <livewire:datatable-component.th-cell field="" label="Enrolled"/>
                    <livewire:datatable-component.th-cell field="" label="Instructor" />
                </tr>
        </thead>
        <tbody>
            @forelse($schedules as $schedule)
                <tr wire:key="schedule-{{ $schedule->id }}" class="border-b border-stroke text-base dark:border-bodydark">
                    <td class="py-3 px-4 ">
                        <input type="checkbox" class="w-4 h-4 cursor-pointer" wire:model.live="schedule_id" value="{{ $schedule->id }}" />
                    </td>
                    <td class="py-3 px-4"> {{ $schedule->created_at->format('Y-m-d') }}</td>
                    <td class="py-3 px-4"> {{ $schedule->schedule_code }}</td>
                    <td class="py-3 px-4 capitalize">{{ $schedule->name }}</td>
                    <td class="py-3 px-4 capitalize">{{ $schedule->type }}</td>
                    <td class="py-3 px-4">{{ $schedule->enrolled_student }}</td>
                    <td class="py-3 px-4">{{ $schedule->instructorBy->firstname }} {{ $schedule->instructorBy->lastname }}</td>
                </tr>
            @empty
                <tr class="border-b border-stroke text-base">
                    <td class="py-3 px-4 text-center" colspan="7">No data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <div class="mt-4">
        {{ $schedules->links() }}
    </div>

    <x-elements.notification class="bg-red-900" >
        <x-slot:svg>
            <x-icons.warning class="text-red-500" />
        </x-slot:svg>
        {{session('error')}}
    </x-elements.notification>

    @script
        <script>
            $wire.on('openScheduleInNewTab', (url) => {
                window.open(url, '_blank');
            });
        </script>
    @endscript

</div>



