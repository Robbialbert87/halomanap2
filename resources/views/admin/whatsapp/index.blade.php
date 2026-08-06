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
            <h2 class="font-semibold text-gray-800">Status Koneksi API</h2>
            <span id="api-status-badge" class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-600">
                <i class="fa-solid fa-spinner fa-spin mr-1"></i> Memeriksa...
            </span>
        </div>
        
        <div class="p-6 flex flex-col items-center justify-center min-h-[300px]">
            
            <!-- Box Error (Jika Server WAHA Mati) -->
            <div id="error-box" class="hidden text-center">
                <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-solid fa-server"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Server Tidak Merespons</h3>
                <p class="text-gray-500 text-sm mb-6 max-w-sm">Server WAHA belum aktif atau session belum berjalan. Klik tombol di bawah untuk menjalankan layanan.</p>
                
                <form action="{{ route('admin.whatsapp.start') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-6 py-2.5 transition-colors shadow-sm">
                        <i class="fa-solid fa-play mr-2"></i> Jalankan Layanan Server
                    </button>
                </form>
            </div>

            <!-- Box QR Code -->
            <div id="qr-box" class="hidden text-center w-full">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Tautkan Perangkat</h3>
                <p class="text-gray-500 text-sm mb-6">Buka WhatsApp > Perangkat Tertaut > Tautkan Perangkat</p>
                
                <div class="bg-white p-4 border border-gray-200 rounded-xl inline-block shadow-sm">
                    <img id="qr-image" src="" alt="WhatsApp QR Code" class="w-64 h-64 object-contain">
                </div>
                
                <p class="text-xs text-blue-600 mt-4 animate-pulse"><i class="fa-solid fa-sync fa-spin mr-1"></i> Menunggu pindaian QR Code...</p>
            </div>

            <!-- Box Connected -->
            <div id="connected-box" class="hidden text-center">
                <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4 text-4xl shadow-inner border border-green-200">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">WhatsApp Tersambung!</h3>
                <p class="text-gray-500 text-sm mb-6 max-w-sm">Sistem kini dapat mengirimkan notifikasi otomatis ke Kepala Unit dan Jajaran Direksi.</p>
                
                <button onclick="resetWhatsApp()" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-medium rounded-lg text-sm px-6 py-2.5 transition-colors">
                    <i class="fa-solid fa-power-off mr-2"></i> Putuskan & Ganti Nomor
                </button>
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
                <p>Halaman ini terhubung langsung ke <strong>Server WAHA</strong> Anda.</p>
                <p>Untuk menjaga koneksi tetap stabil, ikuti langkah berikut:</p>
                <ul class="list-disc pl-5 space-y-1 text-gray-500">
                    <li>Gunakan nomor WhatsApp yang khusus didedikasikan untuk instansi/sistem.</li>
                    <li>Pastikan handphone untuk nomor WhatsApp tersebut tetap terkoneksi dengan internet.</li>
                    <li>Notifikasi WA dikirim langsung (sinkron) saat aksi terjadi — tidak perlu service/worker tambahan.</li>
                </ul>
            </div>
        </div>

        <div class="bg-blue-50 rounded-xl shadow-sm border border-blue-200 p-6">
            <h2 class="font-semibold text-blue-800 flex items-center gap-2 mb-2">
                <i class="fa-solid fa-gear"></i> Cara Mengaktifkan
            </h2>
            <p class="text-sm text-blue-700">
                Jika status menampilkan "Server Tidak Merespons", klik tombol <strong>"Jalankan Layanan Server"</strong>
                untuk menyalakan session WAHA, lalu refresh halaman ini.
                <br><br>Jika session dalam keadaan <code class="text-sm bg-blue-100 px-2 py-1 rounded">SCAN_QR_CODE</code>,
                scan QR Code yang muncul menggunakan WhatsApp pada handphone Anda.
            </p>
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
                <p class="text-xs text-center text-gray-400 break-all">http://halomanap.rsudkotajambi.id</p>
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
            text: 'http://halomanap.rsudkotajambi.id',
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
                <p style="margin-top:20px;color:#666">http://halomanap.rsudkotajambi.id</p>
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
    let pollInterval;

    function setUI(state, data = null) {
        const errorBox = document.getElementById('error-box');
        const qrBox = document.getElementById('qr-box');
        const connectedBox = document.getElementById('connected-box');
        const statusBadge = document.getElementById('api-status-badge');

        errorBox.classList.add('hidden');
        qrBox.classList.add('hidden');
        connectedBox.classList.add('hidden');

        if (state === 'error') {
            errorBox.classList.remove('hidden');
            statusBadge.className = 'px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200';
            statusBadge.innerHTML = '<i class="fa-solid fa-circle-xmark mr-1"></i> Terputus';
        } 
        else if (state === 'qr') {
            qrBox.classList.remove('hidden');
            document.getElementById('qr-image').src = data.qr;
            statusBadge.className = 'px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 border border-yellow-200';
            statusBadge.innerHTML = '<i class="fa-solid fa-qrcode mr-1"></i> Menunggu Scan';
        }
        else if (state === 'connected') {
            connectedBox.classList.remove('hidden');
            statusBadge.className = 'px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200';
            statusBadge.innerHTML = '<i class="fa-solid fa-check mr-1"></i> Aktif';
        }
    }

    function checkStatus() {
        fetch('{{ route('admin.whatsapp.status') }}')
            .then(res => res.json().then(data => ({ ok: res.ok, data })))
            .then(({ ok, data }) => {
                if (data.isAuthenticated) {
                    setUI('connected');
                } else if (data.qr) {
                    setUI('qr', data);
                } else if (ok) {
                    setUI('error');
                    document.getElementById('error-box').querySelector('p').innerText = data.message || 'Sedang memuat Client... Mohon tunggu beberapa detik.';
                    document.getElementById('error-box').querySelector('form').classList.add('hidden');
                } else {
                    setUI('error');
                    document.getElementById('error-box').querySelector('p').innerText = data.error || 'Server WAHA tidak aktif.';
                    document.getElementById('error-box').querySelector('form').classList.remove('hidden');
                }
            })
            .catch(err => {
                console.error(err);
                setUI('error');
                document.getElementById('error-box').querySelector('p').innerText = 'Server WAHA tidak dapat dihubungi. Klik tombol "Jalankan Layanan Server" untuk mencoba kembali.';
                document.getElementById('error-box').querySelector('form').classList.remove('hidden');
            });
    }

    function resetWhatsApp() {
        if(!confirm('Apakah Anda yakin ingin memutuskan tautan nomor ini? Anda harus melakukan scan QR ulang!')) return;
        
        fetch('{{ route('admin.whatsapp.reset') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            alert('Perintah reset terkirim. Memuat QR Code baru...');
            checkStatus();
        })
        .catch(err => {
            alert('Gagal menghubungi server untuk reset.');
        });
    }

    pollInterval = setInterval(checkStatus, 3000);
    checkStatus();
</script>
@endpush
@endsection
