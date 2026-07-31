@extends('layouts.admin')

@section('title', 'Disposisi - Halo MANAP')

@section('admin_content')

{{-- Mobile Page Header (PayApp style) --}}
<div class="md:hidden mb-3">
    <div class="flex items-center gap-2.5 p-1">
        <span class="w-9 h-9 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200/50 flex-shrink-0">
            <i class="fa-solid fa-arrow-right-arrow-left text-white text-sm"></i>
        </span>
        <div>
            <p class="text-[9px] text-blue-500 font-semibold tracking-wider uppercase font-heading">Administrasi</p>
            <h1 class="text-base font-bold text-gray-800 font-heading">Disposisi</h1>
        </div>
    </div>
</div>

{{-- Page Header (Desktop) --}}
<div class="hidden md:flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-heading">Disposisi</h1>
        <div class="text-sm text-gray-500 mt-1">Pantau disposisi dari seluruh unit</div>
    </div>
</div>

{{-- Toolbar Filter & Pencarian (menyatu, satu jalur mobile & desktop, live tanpa reload) --}}
<div class="admin-card overflow-hidden mb-4 md:mb-6">
    <form action="{{ route('admin.dispositions.index') }}" method="GET"
        data-live-filter="#dispositions-results"
        class="admin-toolbar">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full">
            <select name="status" class="admin-input text-[13px]">
                <option value="">Semua Status</option>
                @foreach($statuses as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>
            <select name="unit_id" class="admin-input text-[13px]">
                <option value="">Semua Unit</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama }}</option>
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

{{-- Hasil: statistik + mobile list + tabel desktop + pagination (di-refresh real-time oleh live filter) --}}
<div id="dispositions-results" class="admin-live-wrap">
    @include('admin.dispositions._table', [
        'workflows' => $workflows,
        'statTotal' => $statTotal,
        'statMenunggu' => $statMenunggu,
        'statProses' => $statProses,
        'statTerlambat' => $statTerlambat,
    ])
</div>

@endsection
