@extends('layouts.admin')

@section('title', 'Data Pengaduan - Halo MANAP')

@section('admin_content')

{{-- Flash Message --}}
@if(session('success'))
<div class="bg-green-50 text-green-700 p-3 rounded-lg mb-4 border border-green-200 flex items-center gap-2">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif

{{-- Mobile Page Header (PayApp style) --}}
<div class="md:hidden mb-3">
    <div class="flex items-center gap-2.5 p-1">
        <span class="w-9 h-9 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200/50 flex-shrink-0">
            <i class="fa-solid fa-clipboard-list text-white text-sm"></i>
        </span>
        <div>
            <p class="text-[9px] text-blue-500 font-semibold tracking-wider uppercase font-heading">Administrasi</p>
            <h1 class="text-base font-bold text-gray-800 font-heading">Data Pengaduan</h1>
        </div>
    </div>
</div>

{{-- Page Header (Desktop) --}}
<div class="hidden md:flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-heading">Data Pengaduan</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Beranda</span>
            <span class="text-gray-400">/</span>
            <span>Pengaduan</span>
        </div>
    </div>
    <a href="{{ route('admin.tickets.create') }}" class="admin-btn admin-btn-primary whitespace-nowrap">
        <i class="fa-solid fa-plus"></i> Tambah Pengaduan
    </a>
</div>

{{-- Toolbar Filter & Pencarian (full-width: pencarian di atas, Reset kiri | Filter kanan di bawah, live tanpa reload) --}}
<div class="admin-card overflow-hidden mb-4 md:mb-6">
    <form action="{{ route('admin.tickets.index') }}" method="GET"
        data-live-filter="#tickets-results"
        class="admin-toolbar">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full">
            <div class="relative w-full">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari no. tiket, judul, atau nama pelapor..." autocomplete="off"
                    class="admin-input text-[13px] pl-9 w-full">
                @if(request('search'))
                <a href="{{ route('admin.tickets.index', request()->except(['search', 'page'])) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark"></i>
                </a>
                @endif
            </div>
            <select name="status" class="admin-input text-[13px]">
                <option value="">Semua Status</option>
                <option value="NEW" {{ request('status') == 'NEW' ? 'selected' : '' }}>Baru</option>
                <option value="TERVERIFIKASI" {{ request('status') == 'TERVERIFIKASI' ? 'selected' : '' }}>Terverifikasi</option>
                <option value="IN_PROGRESS" {{ request('status') == 'IN_PROGRESS' ? 'selected' : '' }}>Diproses</option>
                <option value="DONE" {{ request('status') == 'DONE' ? 'selected' : '' }}>Selesai</option>
                <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <select name="type" class="admin-input text-[13px]">
                <option value="">Semua Jenis</option>
                <option value="Pengaduan" {{ request('type') == 'Pengaduan' ? 'selected' : '' }}>Pengaduan</option>
                <option value="Survei" {{ request('type') == 'Survei' ? 'selected' : '' }}>Survei</option>
                <option value="Apresiasi" {{ request('type') == 'Apresiasi' ? 'selected' : '' }}>Apresiasi</option>
                <option value="Informasi" {{ request('type') == 'Informasi' ? 'selected' : '' }}>Informasi</option>
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

{{-- Hasil: mobile list + tabel desktop + pagination (di-refresh real-time oleh live filter) --}}
<div id="tickets-results" class="admin-live-wrap">
    @include('admin.tickets._table', ['tickets' => $tickets])
</div>

{{-- Modal Konfirmasi Hapus (bottom sheet mobile, centered desktop) --}}
<div id="delete-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden items-end md:items-center justify-center">
    <div id="delete-modal-sheet" class="w-full md:max-w-sm md:mx-4 bg-white md:rounded-xl rounded-t-2xl shadow-xl md:shadow-xl overflow-hidden animate-slide-up md:animate-none">
        <div class="p-5">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-100 to-red-50 border border-red-200 text-red-500 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                </div>
                <div>
                    <h3 class="font-heading font-bold text-gray-800 text-sm">Hapus Pengaduan?</h3>
                    <p class="text-[12px] text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
            <p class="text-[12px] text-gray-600 bg-gray-50 px-2.5 py-1.5 rounded-lg mb-4">
                Anda akan menghapus tiket: <span id="modal-ticket-number" class="font-mono font-bold text-red-600"></span>
            </p>
            <div class="flex gap-2.5">
                <button onclick="closeDeleteModal()" class="flex-1 bg-gradient-to-br from-gray-100 to-white border border-gray-200 hover:from-gray-200 hover:to-gray-100 text-gray-700 font-semibold rounded-xl py-2.5 md:py-2 text-[12px] transition-all active:scale-[0.98]">
                    Batal
                </button>
                <form id="delete-form" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-btn admin-btn-danger w-full text-[12px]">
                        <i class="fa-solid fa-trash mr-1"></i> Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes slideUp {
    from { transform: translateY(100%); }
    to   { transform: translateY(0); }
}
.animate-slide-up {
    animation: slideUp 0.3s ease-out both;
}
</style>

<script>
function confirmDelete(id, ticketNumber) {
    const modal = document.getElementById('delete-modal');
    const form = document.getElementById('delete-form');
    const label = document.getElementById('modal-ticket-number');
    form.action = '/admin/tickets/' + id;
    label.textContent = ticketNumber;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeDeleteModal() {
    const modal = document.getElementById('delete-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
document.getElementById('delete-modal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>

@endsection
