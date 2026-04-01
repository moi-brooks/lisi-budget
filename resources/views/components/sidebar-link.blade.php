@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-3 text-sm font-medium text-white bg-white/10 border-l-4 border-white transition duration-150 ease-in-out'
            : 'flex items-center px-4 py-3 text-sm font-medium text-white/70 hover:text-white hover:bg-white/5 border-l-4 border-transparent transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
