@extends('layouts.admin')

@section('title', 'Kirim Ulang Notifikasi - Halo MANAP')

@section('admin_content')
{{-- Mobile Page Header --}}
<div class="md:hidden mb-3">
    <div class="flex items-center gap-2.5 p-1">
        <span class="w-9 h-9 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-sm shadow-orange-200/50 flex-shrink-0">
            <i class="fa-solid fa-clock-rotate-left text-white text-sm"></i>
        </span>
        <div>
            <p class="text-[9px] text-orange-500 font-semibold tracking-wider uppercase font-heading">Pengaturan Sistem</p>
            <h1 class="text-base font-bold text-gray-800 font-heading">Kirim Ulang Notifikasi</h1>
        </div>
    </div>
</div>

{{-- Page Header (Desktop) --}}
<div class="hidden md:flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-heading">Kirim Ulang Notifikasi</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Pengaturan Sistem</span>
            <span class="text-gray-400">/</span>
            <a href="{{ route('admin.whatsapp.index') }}" class="text-blue-600 hover:underline">WhatsApp Gateway</a>
            <span class="text-gray-400">/</span>
            <span>Kirim Ulang</span>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200 flex items-center gap-2">
        <i class="fa-solid fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

<div class="admin-card overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
        <h2 class="font-semibold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-clock-rotate-left text-orange-500"></i>
            Notifikasi Gagal
        </h2>
    </div>

    {{-- Toolbar Filter & Pencarian (full-width, live tanpa reload) --}}
    <form action="{{ route('admin.whatsapp.resend') }}" method="GET"
        data-live-filter="#resend-results"
        class="admin-toolbar border-b border-gray-100">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full">
            <div class="relative w-full">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nomor WA, pesan, atau error..." autocomplete="off"
                    class="admin-input text-[13px] pl-9 w-full">
                @if(request('search'))
                <a href="{{ route('admin.whatsapp.resend', request()->except(['search', 'page'])) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark"></i>
                </a>
                @endif
            </div>
            <select name="jenis" class="admin-input text-[13px]">
                <option value="">Semua Jenis</option>
                @foreach($jenisList as $jenis)
                    <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>{{ str_replace('_', ' ', $jenis) }}</option>
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

    {{-- Hasil: daftar notifikasi gagal + aksi kirim ulang (di-refresh real-time oleh live filter) --}}
    <div id="resend-results" class="admin-live-wrap">
        @include('admin.whatsapp._resend_table', ['failedLogs' => $failedLogs])
    </div>
</div>

@push('scripts')
<script>
    function toggleAll() {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.log-checkbox');
        if (!selectAll) return;
        const isChecked = selectAll.checked;
        checkboxes.forEach(cb => cb.checked = isChecked);
    }

    function validateResendForm() {
        const checked = document.querySelectorAll('.log-checkbox:checked');
        if (checked.length === 0) {
            alert('Pilih minimal satu notifikasi untuk dikirim ulang.');
            return false;
        }
        return true;
    }
</script>
@endpush
@endsection
