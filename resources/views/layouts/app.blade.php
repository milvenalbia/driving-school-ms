<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @if (isset($title))
            <title>{{$title}} | {{ config('app.name', 'Laravel') }}</title>  
        @else
            <title>{{ config('app.name', 'Laravel') }}</title>
        @endif

        @yield('custom-styles')

        <link rel="icon" href="{{ asset('build/assets/images/prime.jpg') }}" type="image/jpg">
        <!-- <link rel="icon" href="{{ asset('build/assets/images/prime.jpg') }}" type="image/x-icon"> -->

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body
        x-data="{ page: 'ecommerce', 'loaded': false, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false, 'title': '{{ $title }}'}"
        x-init="
            darkMode = JSON.parse(localStorage.getItem('darkMode'));
            $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
        :class="{'dark text-bodydark bg-boxdark-2': darkMode === true}"
    >
        {{-- Loader --}}
        <x-elements.preloader />

        <div class="flex h-screen overflow-hidden">
            <livewire:layout.sidebar />
        
            <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            <livewire:layout.header />

                <main>
                    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
                        {{$slot}}
                    </div>
                </main>
            </div>
        
        </div>

        @yield('scripts')
    </body>
</html>
