@props(['color' => 'green'])
@php
$colors = [
    'green' => 'bg-green-100 text-green-800',
    'red'   => 'bg-red-100 text-red-800',
    'blue'  => 'bg-blue-100 text-blue-800',
    'yellow'=> 'bg-yellow-100 text-yellow-800',
];
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex px-2 py-0.5 rounded-full text-xs font-semibold ' . ($colors[$color] ?? $colors['green'])]) }}>
    {{ $slot }}
</span>