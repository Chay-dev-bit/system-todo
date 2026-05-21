<button <?php echo e($attributes->merge([
    'type' => 'submit',
    'class' => '
        inline-flex items-center
        px-4 py-2
        bg-[#0070C0] 
        hover:bg-blue-800
        focus:ring-[#0070C0]

        border border-transparent
        rounded-lg

        font-semibold
        text-xs
        text-white
        uppercase
        tracking-widest

        focus:outline-none
        focus:ring-2
        focus:ring-blue-500
        focus:ring-offset-2

        transition
        ease-in-out
        duration-150

        disabled:opacity-50
        disabled:cursor-not-allowed
    '
])); ?>>
    <?php echo e($slot); ?>

</button><?php /**PATH C:\laragon\www\system-todo\resources\views/components/primary-button.blade.php ENDPATH**/ ?>