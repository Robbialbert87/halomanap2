@extends('layouts.admin')

@section('title', 'Session WhatsApp - Halo MANAP')

@section('admin_content')
<style>
@keyframes breathe{0%,100%{opacity:1;transform:scale(1)}50%{opacity:0.7;transform:scale(0.85)}}
@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
.anim-fade{animation:fadeUp 0.6s cubic-bezier(0.16,1,0.3,1) both}
.status-dot{animation:breathe 2.5s cubic-bezier(0.4,0,0.6,1) infinite}
.btn-tactile{transition:all 0.3s cubic-bezier(0.16,1,0.3,1)}
.btn-tactile:active{transform:scale(0.97)}
.card-premium{background:#fff;border:1px solid rgba(148,163,184,0.2);box-shadow:0 20px 40px -15px rgba(0,0,0,0.05)}
</style>
<div class="max-w-4xl mx-auto space-y-6 anim-fade">
  <a href="{{ route('admin.whatsapp.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-700 transition-colors">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    Kembali
  </a>

  <div class="card-premium rounded-2xl overflow-hidden">
    <div class="px-6 py-4 flex items-center justify-between border-b border-slate-200/50">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l.498 1.498a1 1 0 00.949.684H17a2 2 0 012 2v1M3 5v10a2 2 0 002 2h12a2 2 0 002-2V8"/>
          </svg>
        </div>
        <h2 class="text-base font-semibold text-slate-800">{{ $session->name }}</h2>
      </div>
      <div class="flex items-center gap-2">
        <form method="POST" action="{{ route('admin.whatsapp.sessions.sync', $session->session_id) }}" class="inline">
          @csrf
          <button class="admin-action-pill admin-action-pill-emerald" title="Sync session">Sync</button>
        </form>
        <form method="POST" action="{{ route('admin.whatsapp.sessions.disconnect', $session->session_id) }}" class="inline" onsubmit="return confirm('Putuskan session {{ $session->name }}?')">
          @csrf
          <button class="admin-action-pill admin-action-pill-red" title="Disconnect">Disconnect</button>
        </form>
      </div>
    </div>
    <div class="px-6 py-5 space-y-5">
      @if(!empty($wahaInfo))
      <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="p-3 rounded-xl bg-slate-50/60 border border-slate-100">
          <span class="text-xs text-slate-500">Status</span>
          <p class="text-sm font-medium text-slate-700 mt-1 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full {{ ($wahaInfo['status'] ?? '') === 'WORKING' ? 'bg-emerald-500 status-dot' : 'bg-slate-300' }}"></span>
            {{ $wahaInfo['status'] ?? 'unknown' }}
            @if(($wahaInfo['engine']['state'] ?? ''))
            <span class="text-xs text-slate-400">({{ $wahaInfo['engine']['state'] }})</span>
            @endif
          </p>
        </div>
        <div class="p-3 rounded-xl bg-slate-50/60 border border-slate-100">
          <span class="text-xs text-slate-500">Push Name</span>
          <p class="text-sm font-medium text-slate-700 mt-1">{{ $wahaInfo['me']['pushName'] ?? '---' }}</p>
        </div>
        <div class="p-3 rounded-xl bg-slate-50/60 border border-slate-100">
          <span class="text-xs text-slate-500">Nomor Telepon</span>
          <p class="text-sm font-medium text-slate-700 mt-1">{{ $session->phone ?: ($wahaInfo['me']['id'] ?? '---') }}</p>
        </div>
        <div class="p-3 rounded-xl bg-slate-50/60 border border-slate-100">
          <span class="text-xs text-slate-500">Engine</span>
          <p class="text-sm font-medium text-slate-700 mt-1">{{ $wahaInfo['engine']['engine'] ?? '---' }}</p>
        </div>
        <div class="p-3 rounded-xl bg-slate-50/60 border border-slate-100">
          <span class="text-xs text-slate-500">WA Version</span>
          <p class="text-sm font-medium text-slate-700 mt-1">{{ $wahaInfo['engine']['WWebVersion'] ?? '---' }}</p>
        </div>
        <div class="p-3 rounded-xl bg-slate-50/60 border border-slate-100">
          <span class="text-xs text-slate-500">Session ID</span>
          <p class="text-sm font-medium text-slate-700 mt-1">{{ $session->session_id }}</p>
        </div>
      </div>
      @else
      <div class="text-center py-4">
        <p class="text-sm text-slate-500">Data WAHA API tidak tersedia.</p>
      </div>
      @endif
      <div id="qr-section" class="text-center py-6 border-t border-slate-100">
        @if(($wahaInfo['engine']['state'] ?? '') === 'CONNECTED' || $session->status === 'connected')
          <div class="w-20 h-20 bg-emerald-100 text-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <p class="text-sm font-medium text-emerald-700">WhatsApp Terhubung</p>
          <p class="text-xs text-slate-500 mt-1">Session aktif dan siap digunakan.</p>
        @elseif($qr)
          <div class="inline-flex items-center justify-center p-4 bg-white rounded-2xl border border-slate-200 shadow-sm mb-4">
            <img src="{{ $qr }}" class="w-56 h-56 rounded-xl" alt="QR Code">
          </div>
          <p class="text-sm font-medium text-slate-700">Scan QR Code</p>
          <p class="text-xs text-slate-500 mt-1">Buka WhatsApp > Linked Devices > Link a Device</p>
        @else
          <div class="w-20 h-20 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
          </div>
          <p class="text-sm text-slate-500">Session tidak aktif</p>
        @endif
      </div>
    </div>
    <div class="px-6 py-3 border-t border-slate-200/50 flex items-center justify-between bg-slate-50/40">
      <span class="text-xs text-slate-400">Terakhir sinkron: {{ $session->synced_at?->diffForHumans() ?? '---' }}</span>
      <form method="POST" action="{{ route('admin.whatsapp.sessions.destroy', $session->session_id) }}" class="inline" onsubmit="return confirm('Hapus session {{ $session->name }}?')">
        @csrf @method('DELETE')
        <button class="btn-tactile text-xs text-slate-400 hover:text-red-500 transition-colors">Hapus Session</button>
      </form>
    </div>
  </div>

  <div class="card-premium rounded-2xl overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200/50">
      <h3 class="text-base font-semibold text-slate-800">Konfigurasi WhatsApp</h3>
    </div>
    <div class="px-6 py-5">
      <form method="POST" action="{{ route('admin.whatsapp.update-config') }}" class="space-y-4">
        @csrf
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">API URL</label>
          <input type="url" name="api_url" value="{{ old('api_url', $wahaConfig['api_url'] ?? '') }}"
                 class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                 placeholder="https://waha.systemwebsite.my.id">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">API Key</label>
          <input type="text" name="api_key" value="{{ old('api_key', $wahaConfig['api_key'] ?? '') }}"
                 class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                 placeholder="API Key">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Session Name</label>
          <input type="text" name="session" value="{{ old('session', $wahaConfig['session'] ?? '') }}"
                 class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                 placeholder="default">
        </div>
        <div class="flex items-center justify-end gap-3 pt-2">
          <a href="{{ route('admin.whatsapp.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg text-sm px-4 py-2.5 transition-colors">Batal</a>
          <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-4 py-2.5 transition-colors">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
