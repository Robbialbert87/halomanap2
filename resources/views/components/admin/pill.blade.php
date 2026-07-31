@props([
    'variant' => 'blue', // blue|red|emerald|slate
    'icon' => null,
    'href' => null,
])

@php
    $variants = [
        'blue' => 'admin-action-pill-blue',
        'red' => 'admin-action-pill-red',
        'emerald' => 'admin-action-pill-emerald',
        'slate' => 'admin-action-pill-slate',
    ];
    $class = 'admin-action-pill ' . ($variants[$variant] ?? $variants['blue']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>
        @if($icon)<i class="fa-solid {{ $icon }} text-[11px]"></i>@endif
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $class]) }}>
        @if($icon)<i class="fa-solid {{ $icon }} text-[11px]"></i>@endif
        {{ $slot }}
    </button>
@endif
