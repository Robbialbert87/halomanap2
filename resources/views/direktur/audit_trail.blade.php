@extends('layouts.admin')

@section('title', 'Audit Trail - Halo MANAP')

@section('admin_content')
<x-admin.page-header
    title="Audit Trail"
    section="Direktur"
    crumb="Audit Trail"
    icon="fa-list-check"
    gradient="indigo"
>
    <x-slot name="summary"><i class="fa-solid fa-eye"></i> Mode baca saja</x-slot>
</x-admin.page-header>

{{-- Toolbar Filter & Pencarian (full-width: pencarian di atas, Reset kiri | Filter kanan di bawah, live tanpa reload) --}}
<div class="admin-card overflow-hidden mb-4 md:mb-6">
    <form action="{{ route('direktur.audit-trail') }}" method="GET"
        data-live-filter="#audit-trail-results"
        class="admin-toolbar">
        <div class="relative w-full">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari aksi, model, atau nama user..." autocomplete="off"
                class="admin-input text-[13px] pl-9 w-full">
            @if(request('search'))
            <a href="{{ route('direktur.audit-trail', request()->except(['search', 'page'])) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </a>
            @endif
        </div>
        <div class="flex items-center justify-between gap-3 w-full pt-1">
            <button type="button" data-live-reset class="admin-btn admin-btn-ghost" title="Reset semua filter">
                <i class="fa-solid fa-rotate-left mr-1 text-xs"></i> Reset
            </button>
            <button type="submit" class="admin-btn admin-btn-primary" title="Terapkan filter">
                <i class="fa-solid fa-filter mr-1 text-xs"></i> Filter
            </button>
        </div>
    </form>
</div>

{{-- Hasil: tabel + pagination (di-refresh real-time oleh live filter) --}}
<div id="audit-trail-results" class="admin-live-wrap">
    @include('direktur._audit_trail_table', ['auditTrails' => $auditTrails])
</div>
@endsection
