@extends('layouts.admin')

@section('title', 'Data Apresiasi - Halo MANAP')

@section('admin_content')
<div class="animate-fadeInUp">
    {{-- Mobile Header --}}
    <div class="md:hidden mb-3">
        <div class="flex items-center gap-2.5 p-1">
            <span class="w-9 h-9 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200/50 flex-shrink-0">
                <i class="fa-solid fa-thumbs-up text-white text-sm"></i>
            </span>
            <div>
                <p class="text-[9px] text-blue-500 font-semibold tracking-wider uppercase font-heading">Data</p>
                <h1 class="text-base font-bold text-gray-800 font-heading">Apresiasi</h1>
            </div>
        </div>
    </div>

    {{-- Desktop Header --}}
    <div class="hidden md:flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-heading">Data Apresiasi</h1>
            <p class="text-sm text-gray-400 mt-1">Daftar apresiasi dari masyarakat</p>
        </div>
        <div class="text-sm text-gray-400">
            Total: <span class="font-bold text-gray-700">{{ $appreciations->total() }}</span>
        </div>
    </div>

    {{-- Toolbar Filter & Pencarian (full-width: pencarian di atas, Reset kiri | Filter kanan di bawah, live tanpa reload) --}}
    <div class="admin-card overflow-hidden mb-4 md:mb-6">
        <form action="{{ route('admin.apresiasi.index') }}" method="GET"
            data-live-filter="#apresiasi-results"
            class="admin-toolbar">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full">
            <div class="relative w-full">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama atau pesan apresiasi..." autocomplete="off"
                    class="admin-input text-[13px] pl-9 w-full">
                @if(request('search'))
                <a href="{{ route('admin.apresiasi.index', request()->except(['search', 'page'])) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark"></i>
                </a>
                @endif
            </div>
            <select name="rating" class="admin-input text-[13px]">
                <option value="">Semua Rating</option>
                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                @endfor
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
    <div id="apresiasi-results" class="admin-live-wrap">
        @include('admin.apresiasi._table', ['appreciations' => $appreciations])
    </div>
</div>
@endsection
