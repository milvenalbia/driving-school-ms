<button {{ $attributes->merge(['class' => 'bg-primary px-6 flex gap-2 transition ease-linear text-white']) }}>
    {{ $slot }}
</button>
