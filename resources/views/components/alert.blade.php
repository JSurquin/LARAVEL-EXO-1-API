@php
$colors = [
    'success' => 'bg-green-100 text-green-800 border-green-300',
    'error'   => 'bg-red-100 text-red-800 border-red-300',
    'warning' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
];
@endphp
<div {{ $attributes->merge(['class' => 'p-4 rounded border ' . ($colors[$type] ?? $colors['success'])]) }}>
    {{ $slot }}
</div>