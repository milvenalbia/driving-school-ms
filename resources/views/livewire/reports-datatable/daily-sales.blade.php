<div x-data="{
    dateRange: @entangle('startDate'),
}">
    <header class="w-full flex justify-between items-center mb-8">
        <div class="relative flex w-[500px]">
            <input
                type="text"
                x-ref="dateRange"
                x-init="
                    picker = flatpickr($refs.dateRange, { 
                        mode: 'range', 
                        dateFormat: 'Y-m-d',
                        onChange: function(selectedDates) { 
                            // Ensure both startDate and endDate are valid before accessing their properties
                            if (selectedDates.length > 1 && selectedDates[0] && selectedDates[1]) { 
                                let startDate = selectedDates[0];
                                let endDate = selectedDates[1];
        
                                let formattedStartDate = startDate.getFullYear() + '-' + 
                                    String(startDate.getMonth() + 1).padStart(2, '0') + '-' + 
                                    String(startDate.getDate()).padStart(2, '0');
                                let formattedEndDate = endDate.getFullYear() + '-' + 
                                    String(endDate.getMonth() + 1).padStart(2, '0') + '-' + 
                                    String(endDate.getDate()).padStart(2, '0');
        
                                @this.set('startDate', formattedStartDate);
                                @this.set('endDate', formattedEndDate);
                            } else { 
                                // If no valid dates are selected, clear the values in Livewire
                                @this.set('startDate', null);
                                @this.set('endDate', null);
                            }
                        } 
                    })
                "
                class="w-full p-2 border-r-0 border border-stroke rounded-tr-none rounded-md rounded-br-none cursor-pointer focus:outline-none focus:border-blue-500 transition-all duration-300 shadow-md shadow-stroke dark:shadow-none dark:bg-transparent dark:border-bodydark"
                placeholder="Filter Date"
            />
        
            <button
                class="py-2 px-3 border border-stroke rounded-tl-none rounded-md rounded-bl-none cursor-pointe bg-red-400 text-white hover:bg-red-500 transition-all duration-300 shadow-md shadow-stroke dark:shadow-none dark:bg-transparent dark:border-bodydark"
                @click="
                    picker.clear(); // Clear the date range picker
                    @this.set('startDate', null); // Clear start date in Livewire
                    @this.set('endDate', null); // Clear end date in Livewire
                "
            >
                Clear
            </button>
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
                    <livewire:datatable-component.th-cell field="" label="Date" />
                    <livewire:datatable-component.th-cell field="" label="Schedules"/>
                    <livewire:datatable-component.th-cell field="" label="Students" />
                    <livewire:datatable-component.th-cell field="" label="Theoretical Students" />
                    <livewire:datatable-component.th-cell field="" label="Practical Students" />
                    <livewire:datatable-component.th-cell field="" label="Total Sales" />
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr wire:key="sale-{{ $sale->date }}" class="border-b border-stroke text-base dark:border-bodydark">
                        <td class="py-3 px-4">
                            <input type="checkbox" class="w-4 h-4 cursor-pointer" wire:model.live="sale_date" value="{{ $sale->date }}" />
                        </td>
                        <td class="py-3 px-4">{{ $sale->date }}</td>
                        <td class="py-3 px-4">{{ $sale->total_schedule }}</td>
                        <td class="py-3 px-4">{{ $sale->total_student }}</td>
                        <td class="py-3 px-4">{{ $sale->theoretical_student }}</td>
                        <td class="py-3 px-4">{{ $sale->practical_student }}</td>
                        <td class="py-3 px-4">{{ number_format($sale->total_sales) }}</td>
                    </tr>
                @empty
                    <tr class="border-b border-stroke text-base">
                        <td class="py-3 px-4 text-center" colspan="8">No data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $sales->links() }}
    </div>

    <x-elements.notification class="bg-red-900">
        <x-slot:svg>
            <x-icons.warning class="text-red-500" />
        </x-slot:svg>
        {{ session('error') }}
    </x-elements.notification>

    @script
        <script>
            $wire.on('openSalesInNewTab', (url) => {
                window.open(url, '_blank');
            });
        </script>
    @endscript
</div>
