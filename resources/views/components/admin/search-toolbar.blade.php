@props([
    'action' => '',
    'liveTarget' => '',
    'placeholder' => 'Cari data...',
    'search' => '',
    'resetLabel' => 'Reset',
    'filterLabel' => 'Filter',
    'searchName' => 'search',
    'grid' => false, // true: field filter tampil dalam grid (users, dispositions)
    'bare' => false, // true: tanpa wrapper admin-card (dipakai di dalam card lain, e.g. whatsapp/resend)
])

@if($bare)
<form action="{{ $action }}" method="GET"
    @if($liveTarget) data-live-filter="{{ $liveTarget }}" @endif
    class="admin-toolbar {{ $attributes->get('class') }}">
@else
<div class="admin-card overflow-hidden mb-4 md:mb-6">
    <form action="{{ $action }}" method="GET"
        @if($liveTarget) data-live-filter="{{ $liveTarget }}" @endif
        class="admin-toolbar">
@endif
    <div @class(['grid grid-cols-1 sm:grid-cols-2 gap-3 w-full' => $grid, 'relative w-full' => ! $grid])>
        <div class="relative @if($grid) w-full @endif">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" name="{{ $searchName }}" value="{{ $search }}"
                placeholder="{{ $placeholder }}" autocomplete="off"
                class="admin-input text-[13px] pl-9 w-full">
            @if($search)
            <a href="{{ url($action) }}?{{ http_build_query(request()->except([$searchName, 'page'])) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </a>
            @endif
        </div>
        {{-- Field filter tambahan (select, dsb.) via slot default --}}
        {{ $slot }}
    </div>
    <div class="flex items-center justify-between gap-3 w-full pt-1">
        <button type="button" data-live-reset class="admin-btn admin-btn-ghost" title="Reset semua filter">
            <i class="fa-solid fa-rotate-left mr-1 text-xs"></i> {{ $resetLabel }}
        </button>
        <button type="submit" class="admin-btn admin-btn-primary" title="Terapkan filter">
            <i class="fa-solid fa-filter mr-1 text-xs"></i> {{ $filterLabel }}
        </button>
    </div>
@if($bare)
</form>
@else
    </form>
</div>
@endif
