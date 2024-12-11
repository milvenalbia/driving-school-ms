 @props([
    'svg' => null,
])

{{-- <div
x-data="{ show: false }"
    x-init="
        setTimeout(() => show = true, 200); 
        setTimeout(() => show = false, 3000);
    "
    x-show="show"
    x-transition:enter="transform ease-out duration-500 transition"
    x-transition:enter-start="-translate-y-full opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transform ease-in duration-300 transition"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="-translate-y-full opacity-0"
    class="w-screen h-screen flex justify-center fixed inset-0 z-99999 py-5 bg-transparent"
>
    <div class="flex flex-col gap-2 w-70 sm:w-100 text-xs sm:text-sm">
        <div {{ $attributes->merge(['class' => 'cursor-default shadow-md flex items-center justify-between w-full bg-[#06D001] h-12 sm:h-14 rounded-lg px-[10px]']) }}>
            <div class="flex gap-2">
                <div class="text-success bg-white/75 backdrop-blur-xl p-1 rounded-lg">
                    @if($svg != null)
                        {{ $svg }}
                    @else
                        <x-icons.success />
                    @endif
                </div>
                <p class="text-white">{{ $slot }}</p>
            </div>
        </div>
    </div>
</div> --}}

{{-- <div x-data="{show: $wire.entangle('showNotification')}" x-init="setTimeout(() => show = false, 5000)" x-show="show"{{ $attributes->merge(['class' => 'alert-custom show showAlert flex items-center bg-[#06D001]']) }}>
    <span class="ml-2 p-1 rounded-md bg-white/75">{{$svg}}</span>
    <span class="text-white text-sm ml-5">{{$slot}}</span>
</div> --}}

<div 
    x-data="{ show: @entangle('showNotification') }" 
    x-init="$watch('show', value => {
        if (value) {
            setTimeout(() => show = false, 5000);
        }
    })"
    x-show="show"
    x-cloak
    {{ $attributes->merge(['class' => 'alert-custom show showAlert flex items-center gap-3 bg-success']) }}
>
    <span class="ml-2 p-1 rounded-md bg-white/75">{{ $svg }}</span>
    <span class="text-white text-sm font-semibold">{{ $slot }}</span>
    
    <!-- Close Button -->
    <button 
        @click="show = false" 
        class="ml-3 text-white text-4xl hover:text-gray-300 focus:outline-none"
    >
        &times;
    </button>
</div>

