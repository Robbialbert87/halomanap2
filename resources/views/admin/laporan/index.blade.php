@extends('layouts.admin')

@section('title', 'Laporan Pengaduan - Halo MANAP')

@section('admin_content')

<x-admin.page-header
    title="Laporan Pengaduan"
    section="Monitoring & Laporan"
    crumb="Laporan Pengaduan"
    icon="fa-file-lines"
    gradient="blue"
>
    <x-slot name="summary">Filter dan export data pengaduan</x-slot>
</x-admin.page-header>

{{-- FILTER FORM (live tanpa reload; statistik & tabel di-refresh bersama) --}}
<div class="admin-card p-4 md:p-6 mb-4 md:mb-6 admin-rise" style="--index: 0">
    <form method="GET" action="{{ route('admin.laporan') }}" data-live-filter="#laporan-results"
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}"
                class="admin-input">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}"
                class="admin-input">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Unit</label>
            <select name="unit_id"
                class="admin-input">
                <option value="">Semua Unit</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Kategori</label>
            <select name="category_id"
                class="admin-input">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="lg:col-span-2">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
            <select name="status"
                class="admin-input">
                <option value="">Semua Status</option>
                @foreach($statuses as $st)
                    <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-span-full pt-2">
            <div class="flex items-center justify-between flex-wrap gap-3">
                {{-- Kiri --}}
                <button
                    type="button"
                    data-live-reset
                    class="admin-btn admin-btn-ghost">
                    <i class="fa-solid fa-rotate-left"></i>
                    Reset
                </button>

                {{-- Kanan --}}
                <div class="flex items-center gap-2">
                    <a
                        href="{{ route('admin.laporan.export-pdf', request()->query()) }}"
                        data-live-export="{{ route('admin.laporan.export-pdf') }}"
                        class="admin-btn admin-btn-danger">
                        <i class="fa-solid fa-file-pdf"></i>
                        Export PDF
                    </a>

                    <button
                        type="submit"
                        class="admin-btn admin-btn-primary">
                        <i class="fa-solid fa-filter"></i>
                        Filter
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Hasil: statistik bento + tabel (di-refresh real-time oleh live filter) --}}
<div id="laporan-results" class="admin-live-wrap">
    @include('admin.laporan._table', [
        'tickets' => $tickets,
        'total' => $total,
        'baru' => $baru,
        'diproses' => $diproses,
        'menungguVerifikasi' => $menungguVerifikasi,
        'selesai' => $selesai,
        'ditolak' => $ditolak,
    ])
</div>

@endsection
