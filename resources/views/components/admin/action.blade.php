@props([
    'variant' => 'slate', // blue|red|emerald|amber|slate
    'icon' => null,
    'href' => null,
])

@php
    $variants = [
        'blue' => 'admin-action-blue',
        'red' => 'admin-action-red',
        'emerald' => 'admin-action-emerald',
        'amber' => 'admin-action-amber',
        'slate' => 'admin-action-slate',
    ];
    $class = 'admin-action ' . ($variants[$variant] ?? $variants['slate']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>
        @if($icon)<i class="fa-solid {{ $icon }}"></i>@endif
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $class]) }}>
        @if($icon)<i class="fa-solid {{ $icon }}"></i>@endif
    </button>
@endif
