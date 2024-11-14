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

        <div class="flex gap-4 items-center">
            <x-elements.primary-button type="button" class="py-2 rounded-md hover:bg-blue-800 font-medium shadow-md shadow-stroke dark:shadow-none"  @click="$dispatch('open-modal',{name:'create-schedule'})">
                Add Schedule
                <x-icons.plus />
            </x-elements.primary-button>
        </div>
       </div>
        
    </header>

    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-2 text-left dark:bg-meta-4 border-b border-gray dark:border-bodydark">
                    <livewire:datatable-component.th-cell field="id" label="ID" :sortBy="$sortBy" :sortDirection="$sortDirection" />
                    <livewire:datatable-component.th-cell field="" label="Schedule Code"/>
                    <livewire:datatable-component.th-cell field="name" label="Name" :sortBy="$sortBy" :sortDirection="$sortDirection" />
                    <livewire:datatable-component.th-cell field="date" label="Schedule Date" :sortBy="$sortBy" :sortDirection="$sortDirection" />
                    <livewire:datatable-component.th-cell field="" label="Type"/>
                    <livewire:datatable-component.th-cell field="" label="Instructor"/>
                    <livewire:datatable-component.th-cell field="amount" label="Amount" :sortBy="$sortBy" :sortDirection="$sortDirection" />
                    {{-- <livewire:datatable-component.th-cell field="role" label="Role" :sortBy="$sortBy" :sortDirection="$sortDirection" /> --}}
                    <livewire:datatable-component.th-cell field="" label="Enrollees"/>
                    <th class="py-3 px-4 flex items-center gap-1">
                        <span>Actions</span>
                    </th>
                </tr>
        </thead>
        <tbody>
            @forelse($schedules as $schedule)
            <tr wire:key="schedule-{{ $schedule->id }}" class="border-b border-stroke text-base dark:border-bodydark">
                <td class="py-3 px-4">{{ $schedule->id }}</td>
                <td class="py-3 px-4">{{ $schedule->schedule_code }}</td>
                <td class="py-3 px-4">{{ $schedule->name }}</td>
                <td class="py-3 px-4">{{ $schedule->date }}</td>
                <td class="py-3 px-4 capitalize">{{ $schedule->type }}</td>
                <td class="py-3 px-4">{{ $schedule->instructorBy->firstname }} {{ $schedule->instructorBy->lastname }}</td>
                <td class="py-3 px-4">{{ $schedule->amount }}</td>
                <td class="py-3 px-4">
                    <button class="text-white text-sm bg-emerald-400 flex items-center rounded-md hover:bg-emerald-500 transition ease-linear py-2 px-3"
                            wire:click="view_students({{ $schedule->id }})">
                        <x-icons.eye />
                        <span>View {{ $schedule->enrolled_student > 1 ? $schedule->enrolled_student .' Students' : $schedule->enrolled_student . ' Student' }} </span>
                    </button>
                </td>
                <td class="py-3 px-4">
                    <div class="flex items-center gap-2">
                        <button class="bg-primary text-white flex items-center hover:bg-blue-600 transition ease-linear py-2 px-4 rounded-md" wire:click="enroll_student({{ $schedule->id }})">
                            <x-icons.bookmark style="height: 1.25rem; width: 1.25rem"/>
                            <span>Enroll</span>
                        </button>
                        <button class="text-secondary flex items-center hover:text-primary transition ease-linear" wire:click="edit_schedule({{ $schedule->id }})">
                            <x-icons.edit />
                            <span>Edit</span>
                        </button>
                        <button class="text-red-500 flex items-center hover:text-red-700 transition ease-linear" wire:confirm.prompt="Delete confirmation, type DELETE to delete schedule. |DELETE" wire:click="delete_schedule({{ $schedule->id }})">
                            <x-icons.delete />
                            <span>Delete</span>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr class="border-b border-stroke text-base">
                <td class="py-3 px-4 text-center" colspan="9">No data available.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    </div>

    {{-- wire:confirm.prompt="Delete confirmation, type DELETE to delete user. |DELETE" --}}

    <div class="mt-4">
        {{ $schedules->links() }}
    </div>

    <x-elements.modal name="edit-enroll-student">
        <livewire:schedule-datatable.schedule-enroll.edit-enroll />
    </x-elements.modal>

    <x-elements.modal name="view-students"  maxWidth="90">
        <livewire:schedule-datatable.schedule-enroll.datatable />
    </x-elements.modal>

    <x-elements.modal name="create-schedule">
        <livewire:schedule-datatable.create-schedule />
    </x-elements.modal>

    <x-elements.modal name="enroll-student">
        <livewire:schedule-datatable.schedule-enroll.enroll-student />
    </x-elements.modal>

    <x-elements.notification >
        <x-slot:svg>
            <x-icons.success class="text-[#06D001]" />
        </x-slot:svg>
        {{session('success')}}
    </x-elements.notification>

</div>

