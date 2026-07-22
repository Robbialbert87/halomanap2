@extends('layouts.admin')

@section('title', 'Test Kirim WhatsApp - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Test Kirim WhatsApp</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Pengaturan Sistem</span> 
            <span class="text-gray-400">/</span> 
            <a href="{{ route('admin.whatsapp.index') }}" class="text-blue-600 hover:underline">WhatsApp Gateway</a>
            <span class="text-gray-400">/</span> 
            <span>Test Kirim</span>
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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Form Kirim --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane text-green-500"></i> 
                    Kirim Pesan Test
                </h2>
            </div>
            
            <form action="{{ route('admin.whatsapp.send-test') }}" method="POST" class="p-6 space-y-5">
                @csrf

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">
                        <i class="fa-solid fa-phone mr-1 text-gray-400"></i>
                        Nomor WhatsApp
                    </label>
                    <input type="text" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone') }}"
                           placeholder="08xxxxxxxxxx"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow"
                           required>
                    <p class="mt-1 text-xs text-gray-400">Format: 08xxx atau 628xxx (otomatis dikonversi ke 62)</p>
                    @error('phone')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1.5">
                        <i class="fa-solid fa-comment mr-1 text-gray-400"></i>
                        Pesan
                    </label>
                    <textarea id="message" 
                              name="message" 
                              rows="6"
                              placeholder="Tulis pesan yang ingin dikirim..."
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow resize-y"
                              required>{{ old('message') }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">Mendukung format teks bold *text* dan enter untuk baris baru.</p>
                    @error('message')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg text-sm px-6 py-2.5 transition-colors shadow-sm flex items-center gap-2">
                        <i class="fa-brands fa-whatsapp"></i>
                        Kirim Pesan
                    </button>
                    <span id="sending-indicator" class="text-sm text-gray-400 hidden">
                        <i class="fa-solid fa-spinner fa-spin mr-1"></i>
                        Mengirim...
                    </span>
                </div>
            </form>
        </div>
    </div>

    {{-- Panel Info --}}
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2 mb-4">
                <i class="fa-solid fa-circle-info text-blue-500"></i> 
                Status Gateway
            </h2>
            <div id="gateway-status" class="text-sm space-y-3">
                <div class="flex items-center gap-2">
                    <span id="status-dot" class="w-2.5 h-2.5 rounded-full bg-gray-300"></span>
                    <span id="status-text" class="text-gray-500">Memeriksa...</span>
                </div>
                <div class="text-xs text-gray-400" id="status-detail"></div>
            </div>
        </div>

        <div class="bg-blue-50 rounded-xl shadow-sm border border-blue-200 p-6">
            <h2 class="font-semibold text-blue-800 flex items-center gap-2 mb-2">
                <i class="fa-solid fa-lightbulb"></i> 
                Tips
            </h2>
            <ul class="text-sm text-blue-700 space-y-2 list-disc pl-4">
                <li>Gunakan nomor yang terdaftar di WhatsApp</li>
                <li>Pastikan session WhatsApp sudah terautentikasi (scan QR)</li>
                <li>Cek status gateway sebelum mengirim</li>
                <li>Pesan error akan tampil jika ada masalah koneksi</li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function checkGatewayStatus() {
        fetch('{{ route('admin.whatsapp.status') }}')
            .then(res => res.json())
            .then(data => {
                const dot = document.getElementById('status-dot');
                const text = document.getElementById('status-text');
                const detail = document.getElementById('status-detail');

                if (data.isAuthenticated) {
                    dot.className = 'w-2.5 h-2.5 rounded-full bg-green-500';
                    text.textContent = 'WhatsApp Terhubung';
                    text.className = 'text-green-700 font-medium';
                    detail.textContent = 'Session aktif, siap mengirim pesan';
                } else if (data.qr) {
                    dot.className = 'w-2.5 h-2.5 rounded-full bg-yellow-500';
                    text.textContent = 'Menunggu Scan QR';
                    text.className = 'text-yellow-700 font-medium';
                    detail.textContent = 'Scan QR Code di halaman Gateway';
                } else {
                    dot.className = 'w-2.5 h-2.5 rounded-full bg-red-500';
                    text.textContent = 'Tidak Terhubung';
                    text.className = 'text-red-700 font-medium';
                    detail.textContent = 'WhatsApp API tidak siap';
                }
            })
            .catch(() => {
                document.getElementById('status-dot').className = 'w-2.5 h-2.5 rounded-full bg-red-500';
                document.getElementById('status-text').textContent = 'API Offline';
                document.getElementById('status-text').className = 'text-red-700 font-medium';
                document.getElementById('status-detail').textContent = 'WhatsApp API tidak dapat dijangkau';
            });
    }

    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('sending-indicator').classList.remove('hidden');
    });

    checkGatewayStatus();
</script>
@endpush
@endsection
