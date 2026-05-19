<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-sans">
    <div class="header">
        <img src="{{ asset('images/gambar-header.jpeg') }}" alt="Header Image" width="100%">
    </div>
    <!-- card menu master -->
    <div class="card-menu flex gap-4 mt-6 px-6">
        <div class="card border-2 p-4 border-green-500 bg-blue-200 rounded-lg shadow-lg flex-shrink-0">
            <a href="#" class="flex flex-col items-center text-center">
                <img src="{{ asset('images/social-media.png') }}" alt="Master Data" width="40%">
                <h3 class="text-md mt-2 font-semibold">Struktur Employed</h3>
            </a>
        </div>
        <div class="card border-2 p-4 border-green-500 bg-blue-200 rounded-lg shadow-lg flex-shrink-0">
            <a href="#" class="flex flex-col items-center text-center">
                <img src="{{ asset('images/notebook.png') }}" alt="Transaksi" width="40%">
                <h3 class="text-md mt-2 font-semibold">To Do List</h3>
            </a>
        </div>
    </div>
</body>

</html>