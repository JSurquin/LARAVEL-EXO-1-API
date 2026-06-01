<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow p-6']) }}>
    @if($title)
        <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ $title }}</h3>
    @endif
    {{ $slot }}
</div>