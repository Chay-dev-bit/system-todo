@props([
    'disabled' => false
])

<input
    {{ $disabled ? 'disabled' : '' }}

    {!! $attributes->merge([
        'class' => '
            w-full
            rounded-lg
            border
            border-gray-300
            bg-white
            px-4
            py-2.5
            text-sm
            text-gray-700
            shadow-sm
            transition
            duration-200

            focus:border-blue-500
            focus:ring
            focus:ring-blue-200
            focus:outline-none

            disabled:bg-gray-100
            disabled:cursor-not-allowed
        '
    ]) !!}
/>