@props([
    'field' => null,
    'label' => '',
    'sortBy' => null,
    'sortDirection' => null,
])

<th {{ $attributes->merge(['class' => "py-3 px-4 " .($field ? 'cursor-pointer' : '')]) }} 
    @if($field)
    wire:click="sortField('{{ $field }}')"
    @endif
>
    <div class="flex items-center gap-1 text-nowrap">
        <span>{{ $label }}</span>
        @if ($field)
            <div class="flex">
                {{-- Show sort up arrow if sortDirection is 'asc' --}}
                <x-icons.sort-up :class="$sortDirection === 'asc' ? 'text-black dark:text-white' : 'dark:text-bodydark'" />
                {{-- Show sort down arrow if sortDirection is 'desc' --}}
                <x-icons.sort-down :class="$sortDirection === 'desc' ? 'text-black dark:text-white' : 'dark:text-bodydark'" />
            </div>
        @endif
    </div>
</th>
