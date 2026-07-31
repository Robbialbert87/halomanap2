@extends('layouts.admin')

@section('title', 'Audit Trail - Halo MANAP')

@section('admin_content')
{{-- Mobile Page Header --}}
<div class="md:hidden mb-3">
    <div class="flex items-center gap-2.5 p-1">
        <span class="w-9 h-9 rounded-2xl bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-sm shadow-indigo-200/50 flex-shrink-0">
            <i class="fa-solid fa-list-check text-white text-sm"></i>
        </span>
        <div>
            <p class="text-[9px] text-indigo-500 font-semibold tracking-wider uppercase font-heading">Direktur</p>
            <h1 class="text-base font-bold text-gray-800 font-heading">Audit Trail</h1>
        </div>
    </div>
</div>

{{-- Page Header (Desktop) --}}
<div class="hidden md:flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-heading">Audit Trail</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Direktur</span>
            <span class="text-gray-400">/</span>
            <span>Audit Trail</span>
        </div>
        <p class="text-xs text-gray-400 mt-1"><i class="fa-solid fa-eye"></i> Mode baca saja</p>
    </div>
</div>

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
