@extends('layouts.admin')

@section('title', 'Manajemen Ruangan - Halo MANAP')

@section('admin_content')

{{-- Mobile Page Header (PayApp style) --}}
<div class="md:hidden mb-3">
    <div class="flex items-center gap-2.5 p-1">
        <span class="w-9 h-9 rounded-2xl bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center shadow-sm shadow-violet-200/50 flex-shrink-0">
            <i class="fa-solid fa-door-open text-white text-sm"></i>
        </span>
        <div>
            <p class="text-[9px] text-violet-500 font-semibold tracking-wider uppercase font-heading">Master Data</p>
            <h1 class="text-base font-bold text-gray-800 font-heading">Manajemen Ruangan</h1>
        </div>
    </div>
</div>

{{-- Page Header (Desktop) --}}
<div class="hidden md:flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-heading">Manajemen Ruangan</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Master Data</span>
            <span class="text-gray-400">/</span>
            <span>Ruangan</span>
        </div>
    </div>
    <a href="{{ route('admin.rooms.create') }}" class="admin-btn admin-btn-primary">
        <i class="fa-solid fa-plus"></i> Tambah Ruangan
    </a>
</div>

{{-- Flash Messages --}}
@if(session('success'))
<div class="md:hidden bg-green-50 text-green-700 p-3 rounded-lg mb-4 border border-green-200 flex items-center gap-2 text-[13px]">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
<div class="hidden md:flex bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-200 items-center gap-2">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif

{{-- Toolbar Filter & Pencarian (menyatu, satu jalur mobile & desktop, live tanpa reload) --}}
<div class="admin-card overflow-hidden mb-4 md:mb-6">
    <form action="{{ route('admin.rooms.index') }}" method="GET"
        data-live-filter="#rooms-results"
        class="admin-toolbar">
        <div class="relative flex-1 md:max-w-md">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari ruangan atau unit..." autocomplete="off"
                class="admin-input text-[13px] pl-9">
            @if(request('search'))
            <a href="{{ route('admin.rooms.index', request()->except(['search', 'page'])) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </a>
            @endif
        </div>
        <div class="flex items-center gap-2 md:flex-col md:gap-1.5">
            <button type="submit" class="admin-btn admin-btn-primary flex-1 md:flex-none md:w-full" title="Terapkan filter">
                <i class="fa-solid fa-filter mr-1 text-xs"></i> Filter
            </button>
            <button type="button" data-live-reset class="admin-btn admin-btn-ghost flex-1 md:flex-none md:w-full text-center" title="Reset semua filter">
                <i class="fa-solid fa-rotate-left mr-1 text-xs"></i> Reset
            </button>
        </div>
    </form>
</div>

{{-- Hasil: mobile list + tabel desktop + pagination (di-refresh real-time oleh live filter) --}}
<div id="rooms-results" class="admin-live-wrap">
    @include('admin.rooms._table', ['rooms' => $rooms])
</div>

@endsection
