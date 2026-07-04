@extends('layouts.admin')

@section('title', 'Tindak Lanjut Pengaduan - Halo MANAP')

@section('admin_content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Tindak Lanjut Pengaduan</h1>
        <p class="text-sm text-gray-500 mt-1">Detail pengaduan dan form aksi.</p>
    </div>
    <a href="{{ route('kabid.dispositions.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
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

                @if($workflow->ticket->attachment_path)
                @php $ext = strtolower(pathinfo($workflow->ticket->attachment_path, PATHINFO_EXTENSION)); $isImage = in_array($ext, ['jpg', 'jpeg', 'png']); @endphp
                <div class="col-span-2 border-t border-gray-100 pt-4 mt-2">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <i class="fa-solid fa-paperclip text-blue-500"></i> Bukti / Lampiran
                    </p>
                    @if($isImage)
                    <div class="relative group inline-block">
                        <img src="{{ asset('storage/' . $workflow->ticket->attachment_path) }}" alt="Lampiran" class="max-h-52 rounded-lg border border-gray-200 shadow-sm cursor-pointer transition-all duration-200 group-hover:shadow-md group-hover:brightness-90" onclick="openPreview('{{ asset('storage/' . $workflow->ticket->attachment_path) }}')">
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                            <span class="bg-black/60 text-white text-xs font-medium px-3 py-1.5 rounded-full flex items-center gap-1.5 pointer-events-none">
                                <i class="fa-solid fa-expand"></i> Klik untuk preview
                            </span>
                        </div>
                    </div>
                    @else
                    <a href="{{ asset('storage/' . $workflow->ticket->attachment_path) }}" target="_blank" class="inline-flex items-center gap-3 bg-blue-50 text-blue-700 px-4 py-3 rounded-xl text-sm font-medium hover:bg-blue-100 transition-colors border border-blue-200">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                            <i class="fa-solid fa-file-pdf text-lg"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Dokumen Lampiran</p>
                            <p class="text-[11px] text-blue-500">Klik untuk membuka</p>
                        </div>
                    </a>
                    @endif
                </div>
                @endif

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
                    <form action="{{ route('kabid.dispositions.selesai', $workflow->id) }}" method="POST" onsubmit="var btn=this.querySelector('button[type=submit]'); if(confirm('Tandai pengaduan selesai ditangani?')){btn.disabled=true; return true;} return false;">
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
                    <form action="{{ route('kabid.dispositions.eskalasi', $workflow->id) }}" method="POST" onsubmit="var btn=this.querySelector('button[type=submit]'); if(confirm('Eskalasi pengaduan ke jabatan atasan?')){btn.disabled=true; return true;} return false;">
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

{{-- Modal Preview Gambar --}}
<div id="preview-modal" class="fixed inset-0 bg-black/80 z-[100] hidden flex items-center justify-center p-4" onclick="closePreview()">
    <div class="relative max-w-4xl w-full max-h-[90vh] flex items-center justify-center" onclick="event.stopPropagation()">
        <button onclick="closePreview()" class="absolute -top-10 right-0 text-white/80 hover:text-white text-2xl transition-colors z-10">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <button onclick="downloadPreview()" class="absolute -top-10 right-10 text-white/80 hover:text-white text-lg transition-colors z-10">
            <i class="fa-solid fa-download"></i>
        </button>
        <img id="preview-image" src="" alt="Preview" class="max-w-full max-h-[85vh] rounded-lg shadow-2xl object-contain">
    </div>
</div>

<script>
function openPreview(url) {
    const modal = document.getElementById('preview-modal');
    const img = document.getElementById('preview-image');
    img.src = url;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closePreview() {
    const modal = document.getElementById('preview-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

function downloadPreview() {
    const img = document.getElementById('preview-image');
    const link = document.createElement('a');
    link.download = 'lampiran-pengaduan.png';
    link.href = img.src;
    link.click();
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closePreview();
});
</script>

@endsection
