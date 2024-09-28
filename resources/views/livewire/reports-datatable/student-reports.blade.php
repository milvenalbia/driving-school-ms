<div>
    <header class="w-full flex justify-between items-center mb-8">
        <div class="relative w-full">
            <x-icons.search class="absolute top-3 left-2" />
            <input
            class="input w-full rounded-md px-8 py-2 border border-stroke focus:outline-none focus:border-blue-500 transition-all duration-300 shadow-md shadow-stroke dark:shadow-none dark:bg-transparent dark:border-bodydark"
            placeholder="Search name..."
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
        <button class="bg-primary py-2 px-4 text-white rounded-md">Generate PDF</button>
       </div>
        
    </header>

    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-2 text-left dark:bg-meta-4 border-b border-gray dark:border-bodydark">
                    <livewire:datatable-component.th-cell field="" label="Student ID"  />
                    <livewire:datatable-component.th-cell field="" label="Student Name"/>
                    <livewire:datatable-component.th-cell field="" label="Course Name"/>
                    <livewire:datatable-component.th-cell field="" label="Grade"/>
                    <livewire:datatable-component.th-cell field="" label="Instructor" />
                    <livewire:datatable-component.th-cell field="" label="Hours"/>
                    <livewire:datatable-component.th-cell field="" label="Remarks"/>
                </tr>
        </thead>
        <tbody>
            @forelse($schedules as $schedule)
                <tr wire:key="schedule-{{ $schedule->id }}" class="border-b border-stroke text-base dark:border-bodydark">
                    <td class="py-3 px-4">{{ $schedule->id }}</td>
                    <td class="py-3 px-4">{{ $schedule->schedule_code }}</td>
                    <td class="py-3 px-4">{{ $schedule->name }}</td>
                    <td class="py-3 px-4">{{ $schedule->date }}</td>
                    <td class="py-3 px-4">{{ $schedule->type }}</td>
                    <td class="py-3 px-4">{{ $schedule->instructorBy->firstname }} {{ $schedule->instructorBy->lastname }}</td>
                    <td class="py-3 px-4">{{ $schedule->slots - $schedule->enrolled_student }}</td>
                    {{-- <td class="py-3 px-4">{{ $user->role ? $user->role : '--' }}</td> --}}
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

    {{-- <div class="mt-4">
        {{ $schedules->links() }}
    </div> --}}

</div>


