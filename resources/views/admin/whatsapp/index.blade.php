@extends('layouts.admin')

@section('title', 'WhatsApp - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">WhatsApp</h1>
        <p class="text-sm text-gray-500 mt-1">Konfigurasi dan manajemen session WhatsApp</p>
    </div>
</div>

<x-admin.flash />

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
                <div class="admin-card overflow-hidden">
            <div class="admin-card-head flex items-center justify-between">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-signal text-gray-500"></i> Status Koneksi WAHA
                </h2>
                <span id="api-status-badge" class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-600">
                    <i class="fa-solid fa-spinner fa-spin mr-1"></i> Memeriksa...
                </span>
            </div>
            <div class="p-6 flex flex-col items-center justify-center min-h-[200px]" id="status-panel">
                <div id="status-loading" class="text-center py-8">
                    <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto mb-3"></div>
                    <p class="text-sm text-gray-500">Memeriksa koneksi...</p>
                </div>
            </div>
        </div>
                <div class="admin-card overflow-hidden">
            <div class="admin-card-head">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-qrcode text-gray-500"></i> Session WhatsApp
                </h2>
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.whatsapp.sessions.sync-all') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg text-sm px-4 py-2.5 transition-colors">
                            <i class="fa-solid fa-rotate mr-1"></i> Sync dari Server
                        </button>
                    </form>
                    <button onclick="this.parentElement.parentElement.nextElementSibling.classList.toggle('hidden')" class="admin-btn admin-btn-primary">
                        <i class="fa-solid fa-plus mr-1"></i> Tambah Session
                    </button>
                </div>
            </div>
            <div class="hidden p-6 border-b border-gray-100">
                <form method="POST" action="{{ route('admin.whatsapp.sessions.store') }}" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Session</label>
                            <input type="text" name="session_id" required class="admin-input">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="text" name="phone_number" required placeholder="628xxx" class="admin-input">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Webhook URL (opsional)</label>
                        <input type="url" name="webhook_url" value="{{ old('webhook_url', $webhookConfig['url'] ?? '') }}"
                               placeholder="{{ route('waha.webhook') }}" class="admin-input">
                        <p class="text-xs text-gray-400 mt-1">Otomatis terisi URL webhook aplikasi. Ganti bila server WAHA butuh URL publik.</p>
                    </div>
                    <button type="submit" class="admin-btn admin-btn-primary">
                        <i class="fa-solid fa-play mr-1"></i> Buat & Mulai Session
                    </button>
                </form>
            </div>
            @if($sessions->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                        <i class="fa-solid fa-qrcode"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Belum Ada Session</h3>
                    <p class="text-gray-500 text-sm">Tambahkan session WhatsApp untuk mengirim notifikasi.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="admin-table w-full text-sm">
                        <thead>
                            <tr>
                                <th class="text-left px-4 py-3">Session</th>
                                <th class="text-left px-4 py-3">Nomor</th>
                                <th class="text-left px-4 py-3">Status</th>
                                <th class="text-left px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($sessions as $session)
                            @php
                                $isOk = in_array($session->status, ['CONNECTED', 'WORKING'], true);
                                $isWait = in_array($session->status, ['scanning', 'SCAN_QR_CODE', 'STARTING', 'creating'], true);
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors" data-session-id="{{ $session->session_id }}">
                                <td class="px-4 py-3 font-medium">{{ $session->session_id }}</td>
                                <td class="px-4 py-3 font-mono text-xs">{{ $session->phone_number ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="session-badge inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium {{ $isOk ? 'bg-green-100 text-green-700' : ($isWait ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}" data-status="{{ $session->status }}">
                                        <span class="session-dot w-1.5 h-1.5 rounded-full {{ $isOk ? 'bg-green-500' : ($isWait ? 'bg-yellow-500' : 'bg-red-500') }}"></span>
                                        {{ $session->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1.5">
                                        @if(!$isOk)
                                        <a href="{{ route('admin.whatsapp.sessions.show', $session->session_id) }}" class="admin-action-pill admin-action-pill-blue" title="Lihat QR code">
                                            <i class="fa-solid fa-qrcode text-[11px]"></i> QR
                                        </a>
                                        @endif
                                        <form action="{{ route('admin.whatsapp.sessions.sync', $session->session_id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="admin-action-pill admin-action-pill-emerald" title="Sync">
                                                <i class="fa-solid fa-rotate text-[11px]"></i> Sync
                                            </button>
                                        </form>
                                        @if(($wahaConfig['session'] ?? '') !== $session->session_id)
                                        <a href="{{ route('admin.whatsapp.sessions.set-default', $session->session_id) }}"
                                           class="admin-action-pill admin-action-pill-emerald" title="Jadikan default kirim">
                                            <i class="fa-solid fa-check text-[11px]"></i> Default
                                        </a>
                                        @else
                                        <span class="admin-action-pill admin-action-pill-emerald cursor-default" title="Session default"><i class="fa-solid fa-check-circle text-[11px]"></i> Default</span>
                                        @endif
                                        <form action="{{ route('admin.whatsapp.sessions.disconnect', $session->session_id) }}" method="POST" class="inline" onsubmit="return confirm('Putuskan session {{ $session->session_id }}?')">
                                            @csrf
                                            <button type="submit" class="admin-action-pill admin-action-pill-red" title="Disconnect">
                                                <i class="fa-solid fa-power-off text-[11px]"></i> Putus
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.whatsapp.sessions.destroy', $session->session_id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus session {{ $session->session_id }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="admin-action-pill admin-action-pill-red" title="Hapus">
                                                <i class="fa-solid fa-trash-can text-[11px]"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
                <div class="admin-card overflow-hidden">
            <div class="admin-card-head">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-gray-500"></i> Log Gagal Kirim
                </h2>
            </div>
            @if($failedLogs->isEmpty())
                <div class="p-10 text-center">
                    <div class="w-16 h-16 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Semua Terkirim</h3>
                    <p class="text-gray-500 text-sm">Tidak ada notifikasi yang gagal dikirim.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="admin-table w-full text-sm">
                        <thead>
                            <tr>
                                <th class="text-left px-4 py-3">Tujuan</th>
                                <th class="text-left px-4 py-3">Pesan</th>
                                <th class="text-left px-4 py-3">Gagal</th>
                                <th class="text-left px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($failedLogs as $log)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 font-mono text-xs">{{ $log->recipient }}</td>
                                <td class="px-4 py-3 max-w-xs truncate text-xs text-gray-600">{{ $log->message }}</td>
                                <td class="px-4 py-3">
                                    <span class="text-xs text-red-600">{{ $log->failed_at ? $log->failed_at->diffForHumans() : '-' }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <form action="{{ route('admin.whatsapp.resend-submit') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="ids[]" value="{{ $log->id }}">
                                        <button type="submit" class="admin-action-pill admin-action-pill-blue" title="Kirim ulang pesan">
                                            <i class="fa-solid fa-rotate-left text-[11px]"></i> Kirim Ulang
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($failedLogs instanceof \Illuminate\Pagination\LengthAwarePaginator && $failedLogs->hasPages())
                    <div class="px-4 py-3 border-t border-gray-100">
                        {{ $failedLogs->links() }}
                    </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
    <div class="space-y-6">
                <div class="admin-card overflow-hidden">
            <div class="admin-card-head">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-gray-500"></i> Konfigurasi WAHA
                </h2>
            </div>
            <form method="POST" action="{{ route('admin.whatsapp.update-config') }}" class="p-6 space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Base URL WAHA</label>
                    <input type="url" name="api_url" value="{{ old('api_url', $wahaConfig['api_url'] ?? 'http://localhost:3000') }}"
                           class="admin-input">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">API Key</label>
                    <input type="password" name="api_key" value="{{ old('api_key', $wahaConfig['api_key'] ?? '') }}"
                           class="admin-input">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Session Default Kirim</label>
                    <select name="session"
                            class="admin-input">
                        <option value="">-- Pilih Session --</option>
                        @foreach($sessions as $s)
                        <option value="{{ $s->session_id }}" {{ old('session', $wahaConfig['session'] ?? '') == $s->session_id ? 'selected' : '' }}>
                            {{ $s->session_id }} @if($s->phone_number)({{ $s->phone_number }})@endif {{ in_array($s->status, ['CONNECTED', 'WORKING'], true) ? '✓' : '' }}
                        </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Session ini akan digunakan untuk mengirim semua notifikasi otomatis.</p>
                </div>
                <button type="submit" class="admin-btn admin-btn-primary w-full">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Konfigurasi
                </button>
            </form>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="admin-card-head">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-webhook text-gray-500"></i> Konfigurasi Webhook
                </h2>
            </div>
            <form method="POST" action="{{ route('admin.whatsapp.update-webhook') }}" class="p-6 space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Webhook URL</label>
                    <input type="url" name="webhook_url" value="{{ old('webhook_url', $webhookConfig['url'] ?? '') }}"
                           class="admin-input">
                    <p class="text-xs text-gray-400 mt-1">Endpoint penerima event dari WAHA (session.status, message). Kosongkan = otomatis pakai URL aplikasi ini.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">HMAC Secret</label>
                    <input type="text" name="webhook_secret" value="{{ old('webhook_secret', $webhookConfig['secret'] ?? '') }}"
                           class="admin-input" autocomplete="off">
                    <p class="text-xs text-gray-400 mt-1">Kunci validasi tanda tangan webhook. Kosongkan = generate otomatis. Berlaku untuk session baru.</p>
                </div>
                <button type="submit" class="admin-btn admin-btn-primary w-full">
                    <i class="fa-solid fa-plug mr-1"></i> Simpan Webhook
                </button>
            </form>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="admin-card-head">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane text-gray-500"></i> Uji Kirim
                </h2>
            </div>
            <form method="POST" action="{{ route('admin.whatsapp.send-test') }}" class="p-6 space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Tujuan</label>
                    <input type="text" name="phone" required placeholder="628xxx"
                           class="admin-input">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pesan</label>
                    <textarea name="message" rows="3" required
                              class="admin-input">Test dari Halo MANAP</textarea>
                </div>
                <button type="submit" class="admin-btn admin-btn-primary w-full">
                    <i class="fa-solid fa-paper-plane mr-1"></i> Kirim Test
                </button>
            </form>
        </div>

        <div class="admin-card p-6">
            <a href="{{ route('admin.whatsapp.resend') }}" class="flex items-center gap-3 text-gray-700 hover:text-blue-600 transition-colors">
                <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <p class="text-sm font-medium">Log Kirim Ulang</p>
                    <p class="text-xs text-gray-400">Riwayat pengiriman ulang notifikasi</p>
                </div>
            </a>
        </div>
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

// ── Realtime: SSE status session ──────────────────────────────────────────────
const sse = new EventSource('{{ route('admin.whatsapp.sse') }}');

function updateSessionBadge(s) {
  const tr = Array.from(document.querySelectorAll('tr[data-session-id]'))
    .find(el => el.dataset.sessionId === s.session_id);
  if (!tr) return;
  const badge = tr.querySelector('.session-badge');
  if (!badge) return;

  const ok = s.connected;
  const wait = ['scanning', 'SCAN_QR_CODE', 'STARTING', 'creating'].includes(s.status);
  const badgeCls = ok ? 'bg-green-100 text-green-700' : (wait ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700');
  const dotCls = ok ? 'bg-green-500' : (wait ? 'bg-yellow-500' : 'bg-red-500');

  badge.className = 'session-badge inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium ' + badgeCls;
  badge.innerHTML = '<span class="session-dot w-1.5 h-1.5 rounded-full ' + dotCls + '"></span> ' + s.status;
}

sse.addEventListener('status', (e) => {
  try {
    JSON.parse(e.data).forEach(updateSessionBadge);
  } catch (_) {}
});
sse.onerror = () => { /* EventSource auto-reconnect */ };
</script>
@endpush
@endsection
