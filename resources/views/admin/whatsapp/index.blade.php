@extends('layouts.admin')

@section('title', 'WhatsApp Gateway - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">WhatsApp Gateway</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Pengaturan Sistem</span> 
            <span class="text-gray-400">/</span> 
            <span>WhatsApp Gateway</span>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200 flex items-center gap-2">
        <i class="fa-solid fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200 flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation"></i>
        {{ session('error') }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Panel Status -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
            <h2 class="font-semibold text-gray-800">Status Koneksi WAHA</h2>
            <span id="api-status-badge" class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-600">
                <i class="fa-solid fa-spinner fa-spin mr-1"></i> Memeriksa...
            </span>
        </div>
        
        <div class="p-6 flex flex-col items-center justify-center min-h-[300px]">
            
            <!-- Box Error (WAHA Offline) -->
            <div id="error-box" class="hidden text-center">
                <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-solid fa-server"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">WAHA Tidak Terjangkau</h3>
                <p class="text-gray-500 text-sm mb-6 max-w-sm" id="error-message">WAHA API sedang offline.</p>
            </div>

            <!-- Box Session Not Found -->
            <div id="notfound-box" class="hidden text-center">
                <div class="w-16 h-16 bg-yellow-100 text-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Session Belum Dibuat</h3>
                <p class="text-gray-500 text-sm mb-3 max-w-sm">Session <strong id="session-name-display"></strong> tidak ditemukan. Buat session dan scan QR di WAHA dashboard.</p>
                <a href="https://waha.systemwebsite.my.id/" target="_blank" 
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-6 py-2.5 transition-colors shadow-sm">
                    <i class="fa-solid fa-external-link mr-2"></i> Buka WAHA Dashboard
                </a>
            </div>

            <!-- Box Connected -->
            <div id="connected-box" class="hidden text-center">
                <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4 text-4xl shadow-inner border border-green-200">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">WhatsApp Tersambung!</h3>
                <p class="text-gray-500 text-sm mb-2 max-w-sm" id="connected-detail">Sistem dapat mengirimkan notifikasi otomatis.</p>
                <p class="text-xs text-gray-400 mb-6" id="connected-phone"></p>
                
                <a href="https://waha.systemwebsite.my.id/" target="_blank"
                   class="bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-200 font-medium rounded-lg text-sm px-6 py-2.5 transition-colors inline-flex items-center gap-2">
                    <i class="fa-solid fa-external-link"></i> Kelola Session
                </a>
            </div>
            
        </div>
    </div>

    <!-- Panel Kanan -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-gray-500"></i> Konfigurasi WAHA
                </h2>
            </div>
            <form action="{{ route('admin.whatsapp.update-config') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label for="api_url" class="block text-sm font-medium text-gray-700 mb-1.5">API URL</label>
                    <input type="url" id="api_url" name="api_url" required
                           value="{{ old('api_url', $wahaConfig['api_url']) }}"
                           placeholder="https://waha.domain.com"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow">
                </div>
                <div>
                    <label for="api_key" class="block text-sm font-medium text-gray-700 mb-1.5">API Key</label>
                    <div class="relative">
                        <input type="password" id="api_key" name="api_key" required
                               value="{{ old('api_key', $wahaConfig['api_key']) }}"
                               placeholder="WAHA API Key"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow pr-10">
                        <button type="button" onclick="toggleKey()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i id="key-icon" class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label for="session" class="block text-sm font-medium text-gray-700 mb-1.5">Session</label>
                    <input type="text" id="session" name="session" required
                           value="{{ old('session', $wahaConfig['session']) }}"
                           placeholder="default"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow">
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-6 py-2.5 transition-colors shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan & Uji Koneksi
                    </button>
                </div>
                <p class="text-xs text-gray-400">Perubahan langsung aktif. Koneksi akan diuji otomatis saat disimpan.</p>
            </form>
        </div>

        <div class="bg-blue-50 rounded-xl shadow-sm border border-blue-200 p-6">
            <h2 class="font-semibold text-blue-800 flex items-center gap-2 mb-2">
                <i class="fa-solid fa-external-link"></i> WAHA Dashboard
            </h2>
            <p class="text-sm text-blue-700 mb-4">
                Kelola session WhatsApp, scan QR code, dan pantau status di WAHA dashboard.
            </p>
            <a href="https://waha.systemwebsite.my.id/" target="_blank"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors shadow-sm">
                <i class="fa-solid fa-external-link mr-2"></i> Buka WAHA Dashboard
            </a>
        </div>


    </div>
</div>

<div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
        <h2 class="font-semibold text-gray-800 flex items-center gap-2">
            <i class="fa-regular fa-paper-plane text-green-500"></i>
            Kirim Pesan Test
        </h2>
        <span id="send-status" class="flex items-center gap-1.5 text-xs font-medium text-gray-500">
            <span id="send-dot" class="w-2 h-2 rounded-full bg-gray-300"></span>
            <span id="send-text">Memeriksa koneksi...</span>
        </span>
    </div>

    <form action="{{ route('admin.whatsapp.send-test') }}" method="POST" class="p-6">
        @csrf

        <div class="flex items-start gap-4">
            <div class="w-56 flex-shrink-0">
                <input type="text"
                       id="phone"
                       name="phone"
                       value="{{ old('phone') }}"
                       placeholder="08xxxxxxxxxx"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow"
                       required>
                <p class="mt-1 text-xs text-gray-400">08xxx atau 628xxx</p>
            </div>

            <div class="flex-1 min-w-0">
                <textarea id="message"
                          name="message"
                          rows="2"
                          placeholder="Ketik pesan yang akan dikirim..."
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow resize-none"
                          required>{{ old('message') }}</textarea>
            </div>

            <div class="flex-shrink-0 pt-0.5">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 disabled:bg-green-300 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors shadow-sm flex items-center gap-2 whitespace-nowrap"
                        id="send-btn">
                    <i class="fa-brands fa-whatsapp"></i>
                    <span id="send-btn-text">Kirim</span>
                    <i id="send-spinner" class="fa-solid fa-spinner fa-spin hidden"></i>
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function toggleKey() {
        const input = document.getElementById('api_key');
        const icon = document.getElementById('key-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fa-solid fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fa-solid fa-eye';
        }
    }

    function updateSendStatus() {
        fetch('{{ route('admin.whatsapp.status') }}')
            .then(res => res.json())
            .then(data => {
                const dot = document.getElementById('send-dot');
                const text = document.getElementById('send-text');
                const btn = document.getElementById('send-btn');
                if (data.isAuthenticated) {
                    dot.className = 'w-2 h-2 rounded-full bg-green-500';
                    text.textContent = 'Siap kirim';
                    text.className = 'text-green-700 font-medium';
                    btn.disabled = false;
                } else {
                    dot.className = 'w-2 h-2 rounded-full bg-red-400';
                    text.textContent = 'Tidak terhubung';
                    text.className = 'text-red-500 font-medium';
                    btn.disabled = true;
                }
            })
            .catch(() => {
                document.getElementById('send-dot').className = 'w-2 h-2 rounded-full bg-red-400';
                document.getElementById('send-text').textContent = 'WAHA offline';
                document.getElementById('send-text').className = 'text-red-500 font-medium';
                document.getElementById('send-btn').disabled = true;
            });
    }

    document.querySelector('form[action*="send-test"]')?.addEventListener('submit', function() {
        document.getElementById('send-btn').disabled = true;
        document.getElementById('send-btn-text').textContent = 'Mengirim...';
        document.getElementById('send-spinner').classList.remove('hidden');
    });

    updateSendStatus();
</script>
<script>
    function setUI(state, data = null) {
        const errorBox = document.getElementById('error-box');
        const notfoundBox = document.getElementById('notfound-box');
        const connectedBox = document.getElementById('connected-box');
        const statusBadge = document.getElementById('api-status-badge');

        errorBox.classList.add('hidden');
        notfoundBox.classList.add('hidden');
        connectedBox.classList.add('hidden');

        if (state === 'error') {
            errorBox.classList.remove('hidden');
            statusBadge.className = 'px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200';
            statusBadge.innerHTML = '<i class="fa-solid fa-circle-xmark mr-1"></i> Offline';
        } 
        else if (state === 'notfound') {
            notfoundBox.classList.remove('hidden');
            document.getElementById('session-name-display').textContent = data?.session || 'default';
            statusBadge.className = 'px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 border border-yellow-200';
            statusBadge.innerHTML = '<i class="fa-solid fa-circle-exclamation mr-1"></i> Session Tidak Ditemukan';
        }
        else if (state === 'connected') {
            connectedBox.classList.remove('hidden');
            if (data?.me) {
                document.getElementById('connected-phone').textContent = 'Nomor: ' + (data.me.id || '-') + ' (' + (data.me.pushName || '-') + ')';
            }
            statusBadge.className = 'px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200';
            statusBadge.innerHTML = '<i class="fa-solid fa-check mr-1"></i> Aktif';
        }
    }

    function checkStatus() {
        fetch('{{ route('admin.whatsapp.status') }}')
            .then(res => {
                if (!res.ok) throw new Error('WAHA offline');
                return res.json();
            })
            .then(data => {
                if (data.isAuthenticated) {
                    setUI('connected', data);
                } else if (data.status === 'NOT_FOUND') {
                    setUI('notfound', data);
                } else {
                    document.getElementById('error-message').textContent = data.error || 'Session tidak aktif.';
                    setUI('error');
                }
            })
            .catch(() => {
                document.getElementById('error-message').textContent = 'WAHA API tidak dapat dijangkau. Periksa koneksi server.';
                setUI('error');
            });
    }

    checkStatus();
</script>
@endpush
@endsection
