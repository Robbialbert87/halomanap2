@extends('layouts.admin')

@section('title', 'Kirim Ulang Notifikasi - Halo MANAP')

@section('admin_content')
<x-admin.page-header
    title="Kirim Ulang Notifikasi"
    section="Pengaturan Sistem"
    icon="fa-clock-rotate-left"
    gradient="orange"
>
    <x-slot name="breadcrumb">
        <span class="text-gray-400">/</span>
        <a href="{{ route('admin.whatsapp.index') }}" class="text-blue-600 hover:underline">WhatsApp Gateway</a>
        <span class="text-gray-400">/</span>
        <span>Kirim Ulang</span>
    </x-slot>
</x-admin.page-header>

<x-admin.flash />

<div class="admin-card overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
        <h2 class="font-semibold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-clock-rotate-left text-orange-500"></i>
            Notifikasi Gagal
        </h2>
    </div>

    <x-admin.search-toolbar
        action="{{ route('admin.whatsapp.resend') }}"
        live-target="#resend-results"
        placeholder="Cari nomor WA, pesan, atau error..."
        :search="request('search')"
        :grid="true"
        bare
        class="border-b border-gray-100"
    >
        <select name="jenis" class="admin-input text-[13px]">
            <option value="">Semua Jenis</option>
            @foreach($jenisList as $jenis)
                <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>{{ str_replace('_', ' ', $jenis) }}</option>
            @endforeach
        </select>
    </x-admin.search-toolbar>

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
