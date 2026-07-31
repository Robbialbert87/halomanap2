@props([
    'title' => null,
    'icon' => null,
    'iconClass' => 'bg-blue-100 text-blue-600', // pasangan warna bg + icon
    'maxWidth' => null, // e.g. 'max-w-2xl'
])

<div {{ $attributes->merge(['class' => 'admin-card overflow-hidden' . ($maxWidth ? ' ' . $maxWidth : '')]) }}>
    @if($title)
    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
        @if($icon)
        <div class="w-8 h-8 {{ $iconClass }} rounded-lg flex items-center justify-center">
            <i class="fa-solid {{ $icon }} text-sm"></i>
        </div>
        @endif
        <h2 class="font-semibold text-gray-800">{!! $title !!}</h2>
    </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
