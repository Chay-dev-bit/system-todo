<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <!-- @powerGridStyles -->
</head>

<body class="font-sans antialiased">
    <header>
        <img src="<?php echo e(asset('images/Desain header sistem.png')); ?>" alt="Header Profile" width="100%">
        <div x-data="locationClock()" x-init="getLocation()" class="text-sm text-slate-700 font-medium flex justify-end mr-2">

            <span x-text="location"></span>,
            <span x-text="date"></span>

        </div>
    </header>
    <div class="min-h-screen">
        <!-- Page Content -->
        <main>
            <?php echo e($slot); ?>

        </main>
    </div>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <!-- @powerGridStyles -->
</body>
</html><?php /**PATH C:\laragon\www\system-todo\resources\views/layouts/app.blade.php ENDPATH**/ ?>