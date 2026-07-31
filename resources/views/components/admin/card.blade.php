@props([
    'title' => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'admin-card overflow-hidden']) }}>
    @if($title)
    <div class="admin-card-head">
        <h2 class="font-semibold text-gray-800 text-sm">{{ $title }}</h2>
    </div>
    @endif
    <div @class(['overflow-x-auto' => $padding])>
        {{ $slot }}
    </div>
</div>
