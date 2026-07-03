@extends('layouts.admin')

@section('title', 'Tindak Lanjut Pengaduan - Halo MANAP')

@section('admin_content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Tindak Lanjut Pengaduan</h1>
        <p class="text-sm text-gray-500 mt-1">Detail pengaduan dan form aksi.</p>
    </div>
    <a href="{{ route('kepala-unit.dispositions.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
</div>

@if(session('success'))
<div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-200 flex items-center gap-2">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-200 flex items-center gap-2">
    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
</div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2">
        {{-- ── Detail Pengaduan ────────────────────────────────────────── --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="font-semibold text-gray-800 mb-4 flex items-center gap-2 border-b border-gray-100 pb-3">
                <i class="fa-solid fa-file-lines text-blue-500"></i> Detail Pengaduan
            </h2>
            <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase">No. Tiket</p>
                    <p class="font-mono text-blue-600 font-medium">{{ $workflow->ticket->ticket_number }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase">Kategori</p>
                    <p class="text-gray-900">{{ $workflow->ticket->category?->name ?? '-' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs text-gray-400 font-semibold uppercase">Judul</p>
                    <p class="text-gray-900 font-medium text-lg">{{ $workflow->ticket->title }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs text-gray-400 font-semibold uppercase">Deskripsi</p>
                    <p class="text-gray-700 mt-1 whitespace-pre-line bg-gray-50 p-3 rounded-lg border border-gray-100">{{ $workflow->ticket->description }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="xl:col-span-1">
        {{-- ── Form Aksi ────────────────────────────────────────────────── --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="font-semibold text-gray-800 mb-4 flex items-center gap-2 border-b border-gray-100 pb-3">
                <i class="fa-solid fa-bolt text-amber-500"></i> Tindakan Anda
            </h2>
            
            <p class="text-sm text-gray-600 mb-4">Pilih tindakan untuk merespon pengaduan ini.</p>

            <div class="space-y-3">
                {{-- Selesai Ditangani (langsung, tidak perlu tangani sendiri dulu) --}}
                @if(in_array($workflow->status, ['menunggu_respon', 'dalam_penanganan']))
                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                    <p class="text-xs font-semibold text-green-700 mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check"></i> Selesaikan Pengaduan
                    </p>
                    <form action="{{ route('admin.workflow.selesai', $workflow->id) }}" method="POST" onsubmit="return confirm('Tandai pengaduan selesai ditangani?')">
                        @csrf
                        <div class="mb-3">
                            <textarea name="komentar" rows="3" required class="w-full px-3 py-2 text-sm border border-green-200 bg-white rounded-lg focus:ring-2 focus:ring-green-400 outline-none" placeholder="Tuliskan solusi / hasil penanganan... *Wajib"></textarea>
                        </div>
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <i class="fa-solid fa-circle-check"></i> Selesai Ditangani
                        </button>
                    </form>
                </div>
                @endif

                {{-- Eskalasi ke Atasan --}}
                <div class="bg-red-50 border border-red-100 rounded-xl p-4">
                    <p class="text-xs font-semibold text-red-600 mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-up-right-dots"></i> Tidak Dapat Menangani? Eskalasi
                    </p>
                    <form action="{{ route('admin.workflow.eskalasi', $workflow->id) }}" method="POST" onsubmit="return confirm('Eskalasi pengaduan ke jabatan atasan?')">
                        @csrf
                        <div class="mb-3">
                            <textarea name="komentar" rows="2" class="w-full px-3 py-2 text-sm border border-red-200 bg-white rounded-lg focus:ring-2 focus:ring-red-400 outline-none" placeholder="Alasan eskalasi..."></textarea>
                        </div>
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-white text-red-700 border border-red-300 hover:bg-red-100 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <i class="fa-solid fa-arrow-up-right-dots"></i> Eskalasi ke Atasan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
