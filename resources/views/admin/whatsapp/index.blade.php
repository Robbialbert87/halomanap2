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
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 flex items-center gap-2">
        <i class="fa-solid fa-check-circle"></i>
        {{ session('success') }}
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

    <!-- Panel Informasi -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2 mb-4">
                <i class="fa-solid fa-circle-info text-blue-500"></i> Informasi Gateway
            </h2>
            <div class="text-sm text-gray-600 space-y-3">
                <p>Sistem terhubung ke <strong>WAHA (WhatsApp HTTP API)</strong> di:</p>
                <p class="text-xs bg-gray-100 px-2 py-1 rounded font-mono break-all">{{ config('whatsapp.api_url') }}</p>
                <p>Session: <strong>{{ config('whatsapp.session') }}</strong></p>
                <ul class="list-disc pl-5 space-y-1 text-gray-500">
                    <li>Gunakan nomor WhatsApp khusus untuk sistem.</li>
                    <li>Pastikan handphone tetap terkoneksi internet.</li>
                    <li>Untuk ganti nomor, buka WAHA dashboard → reset session → scan QR baru.</li>
                </ul>
            </div>
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

        <!-- Card Barcode Pengaduan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2 mb-4">
                <i class="fa-solid fa-qrcode text-blue-500"></i> Barcode Pengaduan
            </h2>
            <div class="text-sm text-gray-600 space-y-3">
                <p>Scan barcode berikut untuk mengakses halaman pengaduan:</p>
                <div class="flex justify-center py-2">
                    <div id="barcode-container" class="bg-white p-2 border border-gray-200 rounded-lg inline-block"></div>
                </div>
                <p class="text-xs text-center text-gray-400 break-all">{{ \App\Models\Setting::getValue('barcode_url', config('app.url')) }}</p>
                <div class="flex gap-2 justify-center pt-1">
                    <button onclick="downloadBarcode()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-xs px-4 py-2 transition-colors">
                        <i class="fa-solid fa-download mr-1"></i> Download
                    </button>
                    <button onclick="printBarcode()" class="bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg text-xs px-4 py-2 transition-colors">
                        <i class="fa-solid fa-print mr-1"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    let qrCodeInstance;

    function generateBarcode() {
        const container = document.getElementById('barcode-container');
        container.innerHTML = '';
        qrCodeInstance = new QRCode(container, {
            text: '{{ \App\Models\Setting::getValue('barcode_url', config('app.url')) }}',
            width: 180,
            height: 180,
            colorDark: '#1e293b',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    }

    function downloadBarcode() {
        const canvas = document.querySelector('#barcode-container canvas');
        if (!canvas) return;
        const link = document.createElement('a');
        link.download = 'barcode-pengaduan.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    }

    function printBarcode() {
        const canvas = document.querySelector('#barcode-container canvas');
        if (!canvas) return;
        const win = window.open('', '_blank');
        win.document.write(`
            <html>
            <head><title>Print Barcode Pengaduan</title></head>
            <body style="text-align:center;padding:40px;font-family:sans-serif">
                <h2 style="margin-bottom:20px">Scan untuk mengakses pengaduan</h2>
                <img src="${canvas.toDataURL('image/png')}" style="width:300px;height:300px">
                <p style="margin-top:20px;color:#666">{{ \App\Models\Setting::getValue('barcode_url', config('app.url')) }}</p>
                <script>
                    window.onload = function() { window.print(); window.close(); }
                <\/script>
            </body>
            </html>
        `);
        win.document.close();
    }

    generateBarcode();
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
