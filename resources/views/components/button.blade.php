@php
$classes = [
    'primary' => 'bg-blue-600 hover:bg-blue-700 text-white',
    'danger'  => 'bg-red-600 hover:bg-red-700 text-white',
    'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-800',
];
$cls = 'inline-flex items-center px-4 py-2 rounded font-medium transition ' . ($classes[$variant] ?? $classes['primary']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $cls]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['class' => $cls]) }}>{{ $slot }}</button>
@endif