@props([
    'variant' => 'primary', // primary|danger|ghost
    'icon' => null,
    'href' => null,
])

@php
    $variants = [
        'primary' => 'admin-btn-primary',
        'danger' => 'admin-btn-danger',
        'ghost' => 'admin-btn-ghost',
    ];
    $class = 'admin-btn ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>
        @if($icon)<i class="fa-solid {{ $icon }}"></i>@endif
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $class]) }}>
        @if($icon)<i class="fa-solid {{ $icon }} mr-1 text-xs"></i>@endif
        {{ $slot }}
    </button>
@endif
