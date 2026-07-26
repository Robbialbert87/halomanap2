@extends('layouts.admin')

@section('title', 'Session: '.$session->session_id.' - Halo MANAP')

@php
$statusLabels = [
    'CONNECTED' => 'Terhubung',
    'scanning' => 'Menunggu Scan',
    'creating' => 'Membuat Session',
    'disconnected' => 'Terputus',
    'NOT_CONNECTED' => 'Belum Terhubung',
];
@endphp

@section('admin_content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">{{ $session->session_id }}</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <a href="{{ route('admin.whatsapp-sessions.index') }}" class="text-blue-600 hover:text-blue-700">WhatsApp Sessions</a>
            <span class="text-gray-400">/</span>
            <span>{{ $session->session_id }}</span>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <button onclick="refreshQr()" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors text-sm font-medium shadow-sm">
            <i class="fa-solid fa-rotate mr-1.5"></i> Refresh QR
        </button>
        <button onclick="checkStatus()" class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-xl transition-colors text-sm">
            <i class="fa-solid fa-arrows-rotate mr-1.5"></i> Cek Status
        </button>
        <form action="{{ route('admin.whatsapp-sessions.sync', $session->session_id) }}" method="POST" class="inline">
            @csrf
            <button type="submit" onclick="syncSession(event)" class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors text-sm font-medium shadow-sm">
                <i class="fa-solid fa-cloud-arrow-down mr-1.5"></i> Sync
            </button>
        </form>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">QR Code</h2>
            <div id="qr-container" class="flex flex-col items-center justify-center p-8 bg-gray-50 rounded-xl min-h-[300px]">
                                @if($qr)
                    <div id="qr-image" class="bg-white p-4 rounded-xl shadow-sm flex justify-center">
                        <img src="{{ $qr }}" alt="QR Code" class="w-64 h-64">
                    </div>
                    <p class="text-sm text-gray-500 mt-4 text-center">
                        Scan QR code di atas dengan WhatsApp kamu.<br>
                        Buka WhatsApp <strong>Settings &gt; Linked Devices &gt; Link a Device</strong>.
                    </p>
                @else
                    <div id="qr-placeholder" class="text-center text-gray-400">
                        <i class="fa-solid fa-qrcode text-5xl mb-4 block"></i>
                        <p class="text-sm">QR Code belum tersedia.</p>
                        <p class="text-xs mt-1">Klik Refresh QR untuk memuat ulang.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="space-y-6">
        @include('admin.whatsapp-sessions._detail')
        @include('admin.whatsapp-sessions._actions')
    </div>
</div>
<script>
function refreshQr() {
    const c = document.getElementById('qr-container');
    const e = document.getElementById('error-message');
    c.innerHTML = '<div class="text-center text-gray-400 py-8"><i class="fa-solid fa-spinner fa-spin text-3xl mb-3 block"></i><p class="text-sm">Memuat QR Code...</p></div>';
    if (e) e.classList.add('hidden');
    fetch('{{ route("admin.whatsapp-sessions.refresh-qr", $session->session_id) }}')
        .then(r => r.json())
        .then(d => {
            if (d.error) { if(e){e.textContent=d.error;e.classList.remove('hidden')}
                c.innerHTML = '<div class="text-center text-gray-400 py-8"><i class="fa-solid fa-qrcode text-5xl mb-3"></i><p class="text-sm">QR tidak tersedia</p></div>'; return }
            c.innerHTML = '<div class="bg-white p-4 rounded-xl shadow-sm flex justify-center"><img src="' + d.qr + '" class="w-64 h-64"></div><p class="text-sm text-gray-500 mt-4">Scan QR code dengan WhatsApp kamu.</p>';
        });
}
function checkStatus() {
    const badge = document.getElementById('status-badge');
    badge.textContent = '...';
    fetch('{{ route("admin.whatsapp-sessions.status", $session->session_id) }}')
        .then(r => r.json())
        .then(d => {
            if (d.status) badge.textContent = d.status;
            if (d.error) console.warn(d.error);
        });
}
function syncSession(e) {
    e.preventDefault();
    const btn = e.target.closest('button');
    const badge = document.getElementById('status-badge');
    btn.disabled = true; btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1.5"></i> Syncing...';
    fetch('{{ route("admin.whatsapp-sessions.sync", $session->session_id) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content } })
        .then(r => r.json())
        .then(d => {
            if (d.status) badge.textContent = d.status;
            btn.innerHTML = '<i class="fa-solid fa-check mr-1.5"></i> Synced';
            setTimeout(() => { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-cloud-arrow-down mr-1.5"></i> Sync'; }, 2000);
        });
}
</script>
@endsection
