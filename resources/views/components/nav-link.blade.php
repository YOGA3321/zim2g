@props(['active', 'icon', 'href'])

@php
$classes = ($active ?? false)
            ? 'flex items-center space-x-3 p-4 rounded-2xl transition-all bg-green-700 text-white shadow-lg shadow-green-900/50 font-bold'
            : 'flex items-center space-x-3 p-4 rounded-2xl transition-all text-green-100/70 hover:bg-white/10 hover:text-white group';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    <div class="w-5 flex items-center justify-center">
        <i class="fas {{ $icon }} {{ ($active ?? false) ? '' : 'group-hover:scale-110 transition-transform' }}"></i>
    </div>
    <span class="text-sm tracking-wide">{{ $slot }}</span>
</a>
