<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'AutoPahala') }}</title>

        <link rel="icon" type="image/jpeg" href="{{ asset('images/favicon.jpeg') }}">
        <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/favicon.jpeg') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">
        <x-banner />

        <div class="min-h-screen flex flex-col">
            <x-navbar />

            @if (isset($header))
                <header class="bg-white shadow-sm border-b border-gray-100">
                    <div class="max-w-6xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="flex-1">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                    <x-flash-alert />
                </div>
                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>

            <x-footer />
        </div>

        <x-confirm-modal />

        @stack('modals')

        @livewireScripts
    </body>
</html>