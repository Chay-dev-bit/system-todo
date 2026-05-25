<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="card-menu flex gap-4 mt-6 px-6">
        <div class="card border-2 p-4 border-blue-300 bg-blue-200 rounded-lg shadow-lg flex-shrink-0">
            <a href="<?php echo e(route('kantor')); ?>" class="flex flex-col items-center text-center">
                <img src="<?php echo e(asset('images/social-media.png')); ?>" alt="Master Data" width="40%">
                <h3 class="text-md mt-2 font-semibold">Struktur Employed</h3>
            </a>
        </div>
        <div class="card border-2 p-4 border-blue-300 bg-blue-200 rounded-lg shadow-lg flex-shrink-0">
            <a href="<?php echo e(route('project')); ?>" class="flex flex-col items-center text-center">
                <img src="<?php echo e(asset('images/notebook.png')); ?>" alt="Transaksi" width="40%">
                <h3 class="text-md mt-2 font-semibold">To Do List</h3>
            </a>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\system-todo\resources\views/dashboard.blade.php ENDPATH**/ ?>