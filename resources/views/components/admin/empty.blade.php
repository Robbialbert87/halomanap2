@props([
    'icon' => 'fa-inbox',
    'title' => 'Belum ada data.',
    'subtitle' => null,
])

<div class="admin-empty">
    <i class="fa-solid {{ $icon }}"></i>
    <p>{{ $title }}</p>
    @if($subtitle)<span>{{ $subtitle }}</span>@endif
    {{ $slot }}
</div>
