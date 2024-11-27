<div>
    <header class="w-full flex justify-between items-center mb-8">
        <div class="relative w-full">
            <x-icons.search class="absolute top-3 left-2" />
            <input
            class="input w-full rounded-md px-8 py-2 border border-stroke focus:outline-none focus:border-blue-500 transition-all duration-300 shadow-md shadow-stroke dark:shadow-none dark:bg-transparent dark:border-bodydark"
            placeholder="Search Invoice Code..."
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
                    <livewire:datatable-component.th-cell field="" label="ID" />
                    <livewire:datatable-component.th-cell field="" label="Invoice Code"/>
                    <livewire:datatable-component.th-cell field="" label="Student Name" />
                    <livewire:datatable-component.th-cell field="" label="Amount" />
                    <livewire:datatable-component.th-cell field="" label="Paid Amount" />
                    <livewire:datatable-component.th-cell field="" label="Balance" />
                    <livewire:datatable-component.th-cell field="" label="Status" />
                </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                <tr wire:key="payment-{{ $payment->id }}" class="border-b border-stroke text-base dark:border-bodydark">
                    <td class="py-3 px-4 ">
                        <input type="checkbox" class="w-4 h-4 cursor-pointer" wire:model.live="payment_id" value="{{ $payment->id }}" />
                    </td>
                    <td class="py-3 px-4">{{ $payment->id }}</td>
                    <td class="py-3 px-4">{{ $payment->invoice_code }}</td>
                    <td class="py-3 px-4 flex items-center gap-2 w-[250px]">
                        @if ($payment->student->image_path)
                            <img class="w-10 h-10 object-cover rounded-full" src="{{ Storage::url($payment->student->image_path) }}" alt="profile" >
                        @else
                            <img class="w-10 h-10 object-cover rounded-full" src="{{ asset('build/assets/images/profile.avif') }}" alt="profile" >
                        @endif
                        <span>{{ $payment->student->firstname }} {{ $payment->student->lastname }}</span>
                    </td>
                    <td class="py-3 px-4">{{ $payment->schedule->amount }}</td>
                    <td class="py-3 px-4">{{ $payment->paid_amount }}</td>
                    <td class="py-3 px-4">{{ $payment->balance}}</td>
                    <td class="py-3 px-4">
                        @if ($payment->status === 'paid')
                        <span class="py-2 px-3 rounded-full bg-success text-white text-sm">Paid</span>
                        @elseif($payment->status === 'partial')
                            <span class="py-2 px-3 rounded-full bg-blue-400 text-white text-sm">Partial</span>
                        @else
                            <span class="py-2 px-3 rounded-full bg-red-400 text-white text-sm">Unpaid</span>
                        @endif
                    </td>
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

    <div class="mt-4">
        {{ $payments->links() }}
    </div>

    <x-elements.notification class="bg-red-800" >
        <x-slot:svg>
            <x-icons.warning class="text-red-500" />
        </x-slot:svg>
        {{session('error')}}
    </x-elements.notification>

    @script
        <script>
            $wire.on('openPaymentInNewTab', (url) => {
                window.open(url, '_blank');
            });
        </script>
    @endscript
</div>


