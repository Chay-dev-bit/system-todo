@props(['id' => null, 'maxWidth' => null])

@php
$id = $id ?? md5($attributes->wire('model'));

switch ($maxWidth ?? '2xl') {
    case 'sm':
        $maxWidth = 'sm:max-w-sm';
        break;
    case 'md':
        $maxWidth = 'sm:max-w-md';
        break;
    case 'lg':
        $maxWidth = 'sm:max-w-lg';
        break;
    case 'xl':
        $maxWidth = 'sm:max-w-xl';
        break;
    case '2xl':
    default:
        $maxWidth = 'sm:max-w-2xl';
        break;
}
@endphp

<div
    x-data="{ show: @entangle($attributes->wire('model')) }"
    x-on:close.stop="show = false"
    x-show="show"
    id="{{ $id }}"
    class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0"
    style="display: none;"
>

    {{-- BACKDROP --}}
    <div
        x-show="show"
        class="fixed inset-0 bg-black/50 transition-opacity"
    ></div>

    {{-- MODAL --}}
    <div
        x-show="show"
        class="mb-6 bg-white rounded-2xl overflow-hidden shadow-2xl transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto"
    >

        {{-- TITLE --}}
        @isset($title)
            <div class="px-6 py-4 border-b bg-gray-100">
                {{ $title }}
            </div>
        @endisset

        {{-- CONTENT --}}
        <div class="px-6 py-4 bg-white text-black">
            {{ $content }}
        </div>

        {{-- FOOTER --}}
        @isset($footer)
            <div class="px-6 py-4 bg-gray-100 border-t flex justify-end gap-2">
                {{ $footer }}
            </div>
        @endisset

    </div>

</div>