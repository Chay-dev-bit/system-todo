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

    <div class="px-4 sm:px-6 lg:px-8 py-6">

        
        <div class="
            grid
            grid-cols-1
            sm:grid-cols-2
            gap-4
            md:gap-6
        ">

            
            <div class="
                border-2
                border-blue-300
                bg-blue-200
                rounded-xl
                shadow-lg
                hover:shadow-xl
                transition
                duration-300
            ">
                <a href="<?php echo e(route('kantor')); ?>" class="
                        flex flex-col items-center justify-center
                        text-center
                        p-5 sm:p-6
                        h-full
                    ">

                    <img src="<?php echo e(asset('images/social-media.png')); ?>" alt="Master Data"
                        class="w-20 sm:w-24 md:w-28 lg:w-32 h-auto">

                    <h3 class="text-sm sm:text-base md:text-lg mt-3 font-semibold text-slate-800">
                        Struktur Employed
                    </h3>

                </a>
            </div>

            
            <div class="
                border-2
                border-blue-300
                bg-blue-200
                rounded-xl
                shadow-lg
                hover:shadow-xl
                transition
                duration-300
            ">
                <a href="<?php echo e(route('project')); ?>" class="
                        flex flex-col items-center justify-center
                        text-center
                        p-5 sm:p-6
                        h-full
                    ">

                    <img src="<?php echo e(asset('images/notebook.png')); ?>" alt="Transaksi"
                        class="w-20 sm:w-24 md:w-28 lg:w-32 h-auto">

                    <h3 class="text-sm sm:text-base md:text-lg mt-3 font-semibold text-slate-800">
                        To Do List
                    </h3>

                </a>
            </div>

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