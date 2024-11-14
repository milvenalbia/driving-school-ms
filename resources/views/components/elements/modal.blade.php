@props(['name', 'maxWidth' => ''])

@php
if($maxWidth){
    $maxWidth = [
    'sm' => ' sm:max-w-sm ',
    'md' => ' sm:max-w-md ',
    'lg' => ' sm:max-w-lg ',
    'xl' => ' sm:max-w-xl ',
    '2xl' => ' sm:max-w-2xl ',
    '90' => ' w-[90%] ',
    '40' => 'w-[40%]',
    '50' => 'w-[50%]',
][$maxWidth];
}else{
    $maxWidth = null;
}

@endphp

<div
    x-data="{ show : false , name : '{{ $name }}' }" 
    x-show="show"
    x-on:open-modal.window="show = ($event.detail.name === name)" 
    x-on:close-modal.window="show = false"
    class="fixed inset-0 flex items-center justify-center overflow-y-auto px-4 py-6 sm:px-0 z-99999"
    x-cloak
    >
    <!-- Overlay -->
    <div
        x-show="show"
        class="fixed inset-0 bg-black opacity-50"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>

    <!-- Modal Content -->
    <div
        x-show="show"
        class="bg-white max-h-[95%] dark:bg-slate-800 rounded-md overflow-y-auto shadow-xl transform transition-all {{ $maxWidth ? $maxWidth : ' sm:max-w-2xl '}} sm:mx-auto"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        {{ $slot}}
    </div>
</div>