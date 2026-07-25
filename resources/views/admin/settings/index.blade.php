@extends('layouts.admin')

@section('title', 'Barcode Pengaduan - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Barcode Pengaduan</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Pengaturan Sistem</span> 
            <span class="text-gray-400">/</span> 
            <span>Barcode Pengaduan</span>
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
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-gray-500"></i> 
                    Ubah URL Barcode
                </h2>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6 space-y-5">
                @csrf

                <div>
                    <label for="barcode_url" class="block text-sm font-medium text-gray-700 mb-1.5">
                        <i class="fa-solid fa-qrcode mr-1 text-gray-400"></i>
                        URL Barcode Pengaduan
                    </label>
                    <input type="url"
                           id="barcode_url"
                           name="barcode_url"
                           value="{{ old('barcode_url', $settings['barcode_url']) }}"
                           placeholder="https://example.com"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow"
                           required>
                    <p class="mt-1 text-xs text-gray-400">URL yang tampil di QR Code barcode. Ubah jika domain berubah.</p>
                    @error('barcode_url')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-6 py-2.5 transition-colors shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-image text-gray-500"></i> 
                    Tampilan Barcode
                </h2>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-4">Scan barcode berikut untuk mengakses halaman pengaduan:</p>
                <div class="flex flex-col items-center gap-4">
                    <div id="barcode-container" class="bg-white p-3 border border-gray-200 rounded-lg inline-block"></div>
                    <p class="text-xs text-gray-400 break-all text-center max-w-md" id="barcode-url-display">{{ $settings['barcode_url'] }}</p>
                    <div class="flex gap-2">
                        <button onclick="downloadBarcode()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors shadow-sm flex items-center gap-2">
                            <i class="fa-solid fa-download"></i>
                            Download
                        </button>
                        <button onclick="printBarcode()" class="bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors shadow-sm flex items-center gap-2">
                            <i class="fa-solid fa-print"></i>
                            Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-blue-50 rounded-xl shadow-sm border border-blue-200 p-6">
            <h2 class="font-semibold text-blue-800 flex items-center gap-2 mb-2">
                <i class="fa-solid fa-circle-info"></i> 
                Tentang Barcode
            </h2>
            <ul class="text-sm text-blue-700 space-y-2 list-disc pl-4">
                <li>Barcode menampilkan URL pengaduan masyarakat</li>
                <li>Pasang barcode di area publik rumah sakit</li>
                <li>Ubah URL jika domain berubah</li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    let qrCodeInstance;

    function generateBarcode() {
        const container = document.getElementById('barcode-container');
        const url = '{{ $settings['barcode_url'] }}';
        container.innerHTML = '';
        qrCodeInstance = new QRCode(container, {
            text: url,
            width: 200,
            height: 200,
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
                <p style="margin-top:20px;color:#666">{{ $settings['barcode_url'] }}</p>
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
@endpush
@endsection
