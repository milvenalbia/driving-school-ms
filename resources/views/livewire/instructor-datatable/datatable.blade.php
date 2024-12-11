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
            <x-elements.primary-button type="button" class="py-2 rounded-md hover:bg-blue-800 font-medium shadow-md shadow-stroke dark:shadow-none" @click="$dispatch('open-modal',{name:'create-instructor'})">
                Add Instructor
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
                <livewire:datatable-component.th-cell label="User ID" />
                <livewire:datatable-component.th-cell field="firstname" label="Name" :sortBy="$sortBy" :sortDirection="$sortDirection" />
                <livewire:datatable-component.th-cell field="email" label="Email" :sortBy="$sortBy" :sortDirection="$sortDirection" />
                <livewire:datatable-component.th-cell label="Contact" />
                <th class="py-3 px-4 flex items-center gap-1">
                    <span>Actions</span>
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse($instructors as $instructor)
                <tr wire:key="instructor-{{ $instructor->id }}" class="border-b border-stroke text-base dark:border-bodydark">
                    <td class="py-3 px-4">{{ $instructor->id }}</td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2 w-full">
                            @if ($instructor->image_path)
                                <img class="w-10 h-10 object-cover rounded-full" src="{{ Storage::url($instructor->image_path) }}" alt="profile" >
                            @else
                                <img class="w-10 h-10 object-cover rounded-full" src="{{ asset('build/assets/images/profile.avif') }}" alt="profile" >
                            @endif
                            <span>{{ $instructor->user_id }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-4">{{ $instructor->firstname }} {{ $instructor->lastname }}</td>
                    <td class="py-3 px-4">{{ $instructor->email }}</td>
                    <td class="py-3 px-4">{{ $instructor->phone_number }}</td>
                    <td class="py-3 px-4 flex items-center">
                        <button class="border-2 border-primary rounded-md py-1 px-2 text-primary flex items-center hover:text-white hover:bg-primary transition ease-linear" wire:click="edit_instructor({{ $instructor->id }})">
                            <x-icons.edit />
                            <span>Edit</span>
                        </button>
                        <button class="border-2 border-red-500 rounded-md py-1 px-2 text-red-500 ml-2 flex items-center hover:text-white hover:bg-red-500 transition ease-linear" wire:confirm.prompt="Delete confirmation, type DELETE to delete instructor. |DELETE" wire:click="delete_instructor({{ $instructor->id }})">
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

    {{-- wire:confirm.prompt="Delete confirmation, type DELETE to delete instructor. |DELETE" --}}

    <div class="mt-4">
        {{ $instructors->links() }}
    </div>

    @if(session('success'))
        <x-elements.notification >
            <x-slot:svg>
                <x-icons.success class="text-[#06D001]" />
            </x-slot:svg>
            {{ session('success') }}
        </x-elements.notification>
    @endif

    @if(session('error'))
        <x-elements.notification class="!bg-red-500">
            <x-slot:svg>
                <x-icons.warning class="text-[#ffffff]" />
            </x-slot:svg>
            {{ session('error') }}
        </x-elements.notification>
    @endif


    <x-elements.modal name="create-instructor">
        <livewire:instructor-datatable.create-instructor />
    </x-elements.modal>

</div>
