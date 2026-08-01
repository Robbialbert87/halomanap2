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
