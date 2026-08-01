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
    @fragment('results')
    <div id="resend-results" class="admin-live-wrap">
                @if($failedLogs->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Tidak Ada Notifikasi Gagal</h3>
                <p class="text-gray-500 text-sm">Semua notifikasi WhatsApp berhasil terkirim.</p>
            </div>
        @else
            <form action="{{ route('admin.whatsapp.resend-submit') }}" method="POST"
                id="resend-form" onsubmit="return validateResendForm()">
                @csrf
                <div class="p-4 border-b border-gray-100 flex flex-wrap items-center gap-3">
                    <button type="button" onclick="toggleAll()" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        <i class="fa-solid fa-check-double mr-1"></i> Pilih Semua
                    </button>
                    <span class="text-sm text-gray-500">Total: {{ $failedLogs->total() }}</span>
                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg text-sm px-4 py-2 transition-colors flex items-center gap-2 md:ml-auto">
                        <i class="fa-solid fa-paper-plane"></i>
                        Kirim Ulang Terpilih
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                                <th class="text-left px-4 py-3 w-10">
                                    <input type="checkbox" onchange="toggleAll()" id="select-all" class="rounded border-gray-300">
                                </th>
                                <th class="text-left px-4 py-3">Nomor WA</th>
                                <th class="text-left px-4 py-3">Jenis</th>
                                <th class="text-left px-4 py-3">Pesan</th>
                                <th class="text-left px-4 py-3">Error</th>
                                <th class="text-left px-4 py-3">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($failedLogs as $log)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3">
                                    <input type="checkbox" name="ids[]" value="{{ $log->id }}" class="rounded border-gray-300 log-checkbox">
                                </td>
                                <td class="px-4 py-3 font-mono text-xs">{{ $log->nomor_wa }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                        @switch($log->jenis)
                                            @case('disposisi_baru') bg-blue-100 text-blue-700 @break
                                            @case('eskalasi') bg-red-100 text-red-700 @break
                                            @case('monitoring_direktur') bg-purple-100 text-purple-700 @break
                                            @case('admin_verifikasi') bg-yellow-100 text-yellow-700 @break
                                            @default bg-gray-100 text-gray-700
                                        @endswitch">
                                        {{ str_replace('_', ' ', $log->jenis) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 max-w-xs align-top whitespace-normal break-words">
                                    <p class="leading-snug text-gray-600">{{ $log->isi_pesan }}</p>
                                </td>
                                <td class="px-4 py-3 max-w-[220px] align-top whitespace-normal break-words">
                                    @if($log->error_message)
                                        <p class="text-xs text-red-500 leading-snug">{{ $log->error_message }}</p>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">
                                    {{ $log->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($failedLogs->hasPages())
                    <div class="px-4 py-3 border-t border-gray-100">
                        {{ $failedLogs->links() }}
                    </div>
                @endif
            </form>
        @endif
    </div>
    @endfragment
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
