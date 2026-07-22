@extends('layouts.admin')

@section('title', 'Pengaturan - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Pengaturan</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Pengaturan Sistem</span> 
            <span class="text-gray-400">/</span> 
            <span>Pengaturan</span>
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
    {{-- Form Pengaturan --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-gray-500"></i> 
                    Pengaturan Umum
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
                    <p class="mt-1 text-xs text-gray-400">URL yang tampil di QR Code barcode halaman WhatsApp Gateway. Ubah jika domain berubah.</p>
                    @error('barcode_url')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-6 py-2.5 transition-colors shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        {{-- Info card --}}
        <div class="bg-blue-50 rounded-xl shadow-sm border border-blue-200 p-6 mt-6">
            <h2 class="font-semibold text-blue-800 flex items-center gap-2 mb-2">
                <i class="fa-solid fa-circle-info"></i> 
                Tentang Pengaturan Ini
            </h2>
            <ul class="text-sm text-blue-700 space-y-2 list-disc pl-4">
                <li><strong>URL Barcode Pengaduan</strong> — digunakan di QR Code halaman WhatsApp Gateway</li>
                <li>Settingan lain dapat ditambahkan sesuai kebutuhan sistem</li>
            </ul>
        </div>
    </div>

    {{-- Sidebar Info --}}
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2 mb-4">
                <i class="fa-solid fa-list text-gray-500"></i> 
                Setting Lainnya
            </h2>
            <div class="text-sm text-gray-600 space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Nama Aplikasi</span>
                    <span class="font-medium">{{ config('app.name') }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">APP_URL</span>
                    <span class="font-medium text-xs">{{ config('app.url') }}</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-gray-500">Env</span>
                    <span class="font-medium">{{ app()->environment() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
