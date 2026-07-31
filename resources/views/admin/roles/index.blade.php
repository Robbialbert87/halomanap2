@extends('layouts.admin')

@section('title', 'Manajemen Role - Halo MANAP')

@section('admin_content')

{{-- Mobile Page Header (PayApp style) --}}
<div class="md:hidden mb-3">
    <div class="flex items-center gap-2.5 p-1">
        <span class="w-9 h-9 rounded-2xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-sm shadow-purple-200/50 flex-shrink-0">
            <i class="fa-solid fa-user-shield text-white text-sm"></i>
        </span>
        <div>
            <p class="text-[9px] text-purple-500 font-semibold tracking-wider uppercase font-heading">Master Data</p>
            <h1 class="text-base font-bold text-gray-800 font-heading">Manajemen Role</h1>
        </div>
    </div>
</div>

{{-- Page Header (Desktop) + Summary --}}
<div class="hidden md:flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-heading">Manajemen Role</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Master Data</span>
            <span class="text-gray-400">/</span>
            <span>Role</span>
        </div>
        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-2.5 text-sm text-gray-500">
            <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-shield-halved text-purple-500 text-xs"></i> Total Role: <b class="text-gray-800">{{ $totalRoles }}</b></span>
            <span class="text-gray-300">•</span>
            <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-users text-blue-500 text-xs"></i> Total User Terdaftar: <b class="text-gray-800">{{ $totalUsers }}</b></span>
        </div>
    </div>
    <a href="{{ route('admin.roles.create') }}" class="admin-btn admin-btn-primary whitespace-nowrap">
        <i class="fa-solid fa-plus"></i> Tambah Role
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
@if(session('error'))
<div class="md:hidden bg-red-50 text-red-700 p-3 rounded-lg mb-4 border border-red-200 flex items-center gap-2 text-[13px]">
    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
</div>
<div class="hidden md:flex bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-200 items-center gap-2">
    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
</div>
@endif

{{-- Toolbar Pencarian (full-width: pencarian di atas, Reset kiri | Filter kanan di bawah, live tanpa reload) --}}
<div class="admin-card overflow-hidden mb-4 md:mb-6">
    <form action="{{ route('admin.roles.index') }}" method="GET"
        data-live-filter="#roles-results"
        class="admin-toolbar">
        <div class="relative w-full">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama, kode, atau deskripsi role..." autocomplete="off"
                class="admin-input text-[13px] pl-9 w-full">
            @if(request('search'))
            <a href="{{ route('admin.roles.index', request()->except(['search', 'page'])) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
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

{{-- Hasil: card ringkasan + tabel detail (di-refresh real-time oleh live filter) --}}
<div id="roles-results" class="admin-live-wrap">
    @include('admin.roles._results', ['roles' => $roles])
</div>
@endsection
