<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <!-- @powerGridStyles -->
</head>

<body class="font-sans antialiased">
    <header>
        <img src="{{ asset('images/gambar-header.jpeg') }}" alt="Header Profile" width="100%">
        <div x-data="locationClock()" x-init="getLocation()" class="text-sm text-slate-700 font-medium flex justify-end mr-2">

            <span x-text="location"></span>,
            <span x-text="date"></span>

        </div>
    </header>
    <div class="min-h-screen">
        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    @livewireScripts
    <!-- @powerGridStyles -->
</body>
</html>