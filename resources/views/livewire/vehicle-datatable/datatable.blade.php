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
            <x-elements.primary-button type="button" class="py-2 rounded-md hover:bg-blue-800 font-medium shadow-md shadow-stroke dark:shadow-none" @click="$dispatch('open-modal',{name:'create-vehicle'})">
                Add Vehicle
                <x-icons.plus />
            </x-elements.primary-button>
        </div>
       </div>
        
    </header>

    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-2 text-left dark:bg-meta-4 border-b border-gray dark:border-bodydark">
                <livewire:datatable-component.th-cell field="id" label="ID" :sortBy="$sortBy" :sortDirection="$sortDirection"/>
                <livewire:datatable-component.th-cell field="brand" label="Brand" :sortBy="$sortBy" :sortDirection="$sortDirection"/>
                <livewire:datatable-component.th-cell field="" label="License Plate" />
                <livewire:datatable-component.th-cell field="" label="Vehicle Type" />
                <livewire:datatable-component.th-cell field="" label="Tansmission Type"/>
                <livewire:datatable-component.th-cell field="" label="Status"/>
                {{-- <livewire:datatable-component.th-cell field="role" label="Role" :sortBy="$sortBy" :sortDirection="$sortDirection" /> --}}
                <th class="py-3 px-4 flex items-center gap-1">
                    <span>Actions</span>
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse($vehicles as $vehicle)
                <tr wire:key="vehicle-{{ $vehicle->id }}" class="border-b border-stroke text-base dark:border-bodydark">
                    <td class="py-3 px-4">{{ $vehicle->id }}</td>
                    <td class="py-3 px-4">{{ $vehicle->brand }}</td>
                    <td class="py-3 px-4">{{ $vehicle->license_plate }}</td>
                    <td class="py-3 px-4 capitalize">{{ $vehicle->type }}</td>
                    <td class="py-3 px-4 capitalize">{{ $vehicle->transmission_type }}</td>
                    <td class="py-3 px-4">
                        @if ($vehicle->status === "good")
                            <span class="py-2 px-3 rounded-full bg-success text-white text-sm">Good</span>
                        @elseif($vehicle->status === "in_use")
                            <span class="py-2 px-3 rounded-full bg-blue-400 text-white text-sm">In Use</span>
                        @else
                            <span class="py-2 px-3 rounded-full bg-red-400 text-white text-sm">Under Maintenance</span>
                        @endif
                    </td>
                    {{-- <td class="py-3 px-4">{{ $user->role ? $user->role : '--' }}</td> --}}
                    <td class="py-3 px-4 flex items-center">
                        <button class="text-blue-500 flex items-center hover:text-primary transition ease-linear" wire:click="edit_vehicle({{ $vehicle->id }})">
                            <x-icons.edit />
                            <span>Edit</span>
                        </button>
                        <button class="text-red-500 ml-2 flex items-center hover:text-red-700 transition ease-linear" wire:confirm.prompt="Delete confirmation, type DELETE to delete vehicle. |DELETE" wire:click="delete_vehicle({{ $vehicle->id }})">
                            <x-icons.delete />
                            <span>Delete</span>
                        </button>
                    </td>
                </tr>
            @empty
                <tr class="border-b border-stroke text-base">
                    <td class="py-3 px-4 text-center" colspan="6">No data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    {{-- wire:confirm.prompt="Delete confirmation, type DELETE to delete user. |DELETE" --}}

    <div class="mt-4">
        {{ $vehicles->links() }}
    </div>

    <x-elements.notification >
        <x-slot:svg>
            <x-icons.success class="text-[#06D001]" />
        </x-slot:svg>
        {{session('success')}}
    </x-elements.notification>

    <x-elements.modal name="create-vehicle">
        <livewire:vehicle-datatable.create-vehicle />
    </x-elements.modal>

</div>
