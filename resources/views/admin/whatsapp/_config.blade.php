<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-semibold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-sliders text-gray-500"></i> Konfigurasi WAHA
        </h2>
    </div>
    <form method="POST" action="{{ route('admin.whatsapp.update-config') }}" class="p-6 space-y-3">
        @csrf
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Base URL WAHA</label>
            <input type="url" name="api_url" value="{{ old('api_url', $wahaConfig['api_url'] ?? 'http://localhost:3000') }}"
                   class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">API Key</label>
            <input type="password" name="api_key" value="{{ old('api_key', $wahaConfig['api_key'] ?? '') }}"
                   class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Session Default Kirim</label>
            <select name="session"
                    class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Pilih Session --</option>
                @foreach($sessions as $s)
                <option value="{{ $s->session_id }}" {{ old('session', $wahaConfig['session'] ?? '') == $s->session_id ? 'selected' : '' }}>
                    {{ $s->session_id }} @if($s->phone_number)({{ $s->phone_number }})@endif {{ $s->status === 'CONNECTED' ? '✓' : '' }}
                </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-400 mt-1">Session ini akan digunakan untuk mengirim semua notifikasi otomatis.</p>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Webhook URL (opsional)</label>
            <input type="url" name="webhook_url" value="{{ old('webhook_url', $wahaConfig['webhook_url'] ?? '') }}"
                   class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            <p class="text-xs text-gray-400 mt-1">Terima notifikasi status pesan.</p>
        </div>
        <button type="submit" class="w-full bg-gray-800 hover:bg-gray-700 text-white text-sm font-medium rounded-lg px-4 py-2 transition-colors">
            <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Konfigurasi
        </button>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-semibold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-paper-plane text-gray-500"></i> Uji Kirim
        </h2>
    </div>
    <form method="POST" action="{{ route('admin.whatsapp.send-test') }}" class="p-6 space-y-3">
        @csrf
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Nomor Tujuan</label>
            <input type="text" name="phone" required placeholder="628xxx"
                   class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Pesan</label>
            <textarea name="message" rows="3" required
                      class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">Test dari Halo MANAP</textarea>
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg px-4 py-2 transition-colors">
            <i class="fa-solid fa-paper-plane mr-1"></i> Kirim Test
        </button>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
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
