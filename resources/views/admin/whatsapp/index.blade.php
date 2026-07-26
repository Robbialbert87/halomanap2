@extends('layouts.admin')

@section('title', 'WhatsApp - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">WhatsApp</h1>
        <p class="text-sm text-gray-500 mt-1">Konfigurasi dan manajemen session WhatsApp</p>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200 flex items-center gap-2">
        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200 flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        @include('admin.whatsapp._status')
        @include('admin.whatsapp._sessions')
        @include('admin.whatsapp._failed')
    </div>
    <div class="space-y-6">
        @include('admin.whatsapp._config')
    </div>
</div>

@push('scripts')
<script>
const STATUS_URL = '{{ route('admin.whatsapp.status') }}';

function setUI(state, data) {
  const panel = document.getElementById('status-panel');
  const badge = document.getElementById('api-status-badge');
  if (state === 'loading') {
    badge.className = 'px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-600';
    badge.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Memeriksa...';
  } else if (state === 'connected') {
    badge.className = 'px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700';
    badge.innerHTML = '<i class="fa-solid fa-check-circle mr-1"></i> Terhubung';
    panel.innerHTML = '<div class="text-center py-4"><div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4 text-4xl"><i class="fa-brands fa-whatsapp"></i></div><h3 class="text-xl font-bold text-gray-800">Siap!</h3><p class="text-sm text-gray-500 mt-1">Sistem dapat mengirim notifikasi otomatis.</p></div>';
  } else if (state === 'notfound') {
    badge.className = 'px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700';
    badge.innerHTML = '<i class="fa-solid fa-triangle-exclamation mr-1"></i> Belum Dibuat';
    panel.innerHTML = '<div class="text-center py-4"><div class="w-16 h-16 bg-yellow-100 text-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl"><i class="fa-solid fa-circle-exclamation"></i></div><h3 class="text-lg font-bold text-gray-800">Session Belum Dibuat</h3><p class="text-sm text-gray-500 mt-1">Buat session WhatsApp dan scan QR untuk menghubungkan.</p></div>';
  } else {
    badge.className = 'px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700';
    badge.innerHTML = '<i class="fa-solid fa-circle-xmark mr-1"></i> Offline';
    panel.innerHTML = '<div class="text-center py-4"><div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl"><i class="fa-solid fa-server"></i></div><h3 class="text-lg font-bold text-gray-800">WAHA Tidak Terjangkau</h3><p class="text-sm text-gray-500 mt-1" id="error-message">' + (data?.error || 'Periksa koneksi server.') + '</p></div>';
  }
}

async function checkStatus() {
  try {
    const res = await fetch(STATUS_URL);
    if (!res.ok) throw new Error('offline');
    const data = await res.json();
    if (data.success && data.isAuthenticated) setUI('connected', data);
    else if (!data.success && data.status === 'NOT_FOUND') setUI('notfound', data);
    else setUI('error', data);
  } catch { setUI('error', { error: 'WAHA API tidak dapat dijangkau.' }); }
}

document.addEventListener('DOMContentLoaded', checkStatus);
</script>
@endpush
@endsection
