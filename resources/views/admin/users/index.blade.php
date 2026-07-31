@extends('layouts.admin')

@section('title', 'Master Pengguna - Halo MANAP')

@section('admin_content')

{{-- Mobile Page Header (PayApp style) --}}
<div class="md:hidden mb-3">
    <div class="flex items-center gap-2.5 p-1">
        <span class="w-9 h-9 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-sm shadow-emerald-200/50 flex-shrink-0">
            <i class="fa-solid fa-users text-white text-sm"></i>
        </span>
        <div>
            <p class="text-[9px] text-emerald-500 font-semibold tracking-wider uppercase font-heading">Master Data</p>
            <h1 class="text-base font-bold text-gray-800 font-heading">Master Pengguna</h1>
        </div>
    </div>
</div>

{{-- Page Header (Desktop) --}}
<div class="hidden md:flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-heading">Master Pengguna</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Master Data</span>
            <span class="text-gray-400">/</span>
            <span>Pengguna</span>
        </div>
    </div>
    <a href="{{ route('admin.users.create') }}" class="admin-btn admin-btn-primary">
        <i class="fa-solid fa-plus"></i> Tambah Pengguna
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

{{-- Toolbar Filter & Pencarian (full-width: pencarian di atas, Reset kiri | Filter kanan di bawah, live tanpa reload) --}}
<div class="admin-card overflow-hidden mb-4 md:mb-6">
    <form action="{{ route('admin.users.index') }}" method="GET"
        data-live-filter="#users-results"
        class="admin-toolbar">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full">
            <div class="relative w-full">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari NIP, Nama, atau No WA..." autocomplete="off"
                    class="admin-input text-[13px] pl-9 w-full">
                @if(request('search'))
                <a href="{{ route('admin.users.index', request()->except(['search', 'page'])) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark"></i>
                </a>
                @endif
            </div>
            <select name="unit_id" class="admin-input text-[13px]">
                <option value="">Semua Unit</option>
                @foreach($units as $u)
                    <option value="{{ $u->id }}" {{ request('unit_id') == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                @endforeach
            </select>
            <select name="jabatan_id" class="admin-input text-[13px]">
                <option value="">Semua Jabatan</option>
                @foreach($jabatans as $j)
                    <option value="{{ $j->id }}" {{ request('jabatan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama }}</option>
                @endforeach
            </select>
            <select name="role" class="admin-input text-[13px]">
                <option value="">Semua Role</option>
                @foreach($roles as $r)
                    <option value="{{ $r->name }}" {{ request('role') == $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                @endforeach
            </select>
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

{{-- Hasil: mobile list + tabel desktop + pagination (di-refresh real-time oleh live filter) --}}
<div id="users-results" class="admin-live-wrap">
    @include('admin.users._table', ['users' => $users])
</div>

@endsection
