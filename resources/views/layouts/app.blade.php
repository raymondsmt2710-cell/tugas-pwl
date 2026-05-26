<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'AutoPahala') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @auth
                @livewire('navigation-menu')
            @else
                @if(view()->exists('layouts.navbar'))
                    @include('layouts.navbar')
                @else
                    <nav style="background: #ffffff; border-bottom: 1px solid #f1f5f9; padding: 0 24px; font-family: 'Plus Jakarta Sans', sans-serif;">
                        <div style="max-width: 1120px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; height: 64px;">
                            <a href="/" style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 20px; color: #0f172a; text-decoration: none; letter-spacing: -0.02em;">{{ config('app.name', 'AutoPahala') }}</a>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <a href="{{ route('login') }}" style="padding: 8px 18px; border: 2px solid #e2e8f0; border-radius: 9999px; font-weight: 700; font-size: 13.5px; color: #475569; text-decoration: none; transition: all 0.2s ease;">Log in</a>
                                <a href="{{ route('register') }}" style="padding: 8px 18px; background: #02a95c; border-radius: 9999px; font-weight: 700; font-size: 13.5px; color: #ffffff; text-decoration: none; white-space: nowrap; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(2,169,92,0.15);">Sign up</a>
                            </div>
                        </div>
                    </nav>
                @endif
            @endauth

            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main>
                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>