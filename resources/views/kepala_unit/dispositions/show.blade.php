@extends('layouts.admin')

@section('title', 'Tindak Lanjut Pengaduan - Halo MANAP')

@section('admin_content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Tindak Lanjut Pengaduan</h1>
        <p class="text-sm text-gray-500 mt-1">Detail pengaduan dan form aksi.</p>
    </div>
    @php $fromRiwayat = str_contains(url()->previous(), 'riwayat'); @endphp
    <a href="{{ $fromRiwayat ? route('kepala-unit.riwayat') : route('kepala-unit.dispositions.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
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
        {{-- Detail Pengaduan --}}
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
        {{-- Tindakan Anda --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="font-semibold text-gray-800 mb-4 flex items-center gap-2 border-b border-gray-100 pb-3">
                <i class="fa-solid fa-bolt text-amber-500"></i> Tindakan Anda
            </h2>

            @if(in_array($workflow->status, ['menunggu_respon', 'dalam_penanganan']))
            <p class="text-sm text-gray-600 mb-4">Pilih tindakan untuk merespon pengaduan ini.</p>
            <div class="space-y-3">
                {{-- Selesai Ditangani --}}
                <button type="button" onclick="openSelesaiModal()" class="w-full flex items-center gap-3 bg-green-50 hover:bg-green-100 border border-green-200 rounded-xl p-4 transition-colors text-left group">
                    <div class="w-10 h-10 rounded-lg bg-green-100 group-hover:bg-green-200 flex items-center justify-center text-green-600 shrink-0 transition-colors">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-green-800 text-sm">Selesaikan Pengaduan</p>
                        <p class="text-xs text-green-600 mt-0.5">Tandai selesai & kirim ke verifikasi</p>
                    </div>
                </button>

                {{-- Eskalasi --}}
                <button type="button" onclick="openEskalasiModal()" class="w-full flex items-center gap-3 bg-red-50 hover:bg-red-100 border border-red-200 rounded-xl p-4 transition-colors text-left group">
                    <div class="w-10 h-10 rounded-lg bg-red-100 group-hover:bg-red-200 flex items-center justify-center text-red-600 shrink-0 transition-colors">
                        <i class="fa-solid fa-arrow-up-right-dots text-lg"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-red-800 text-sm">Eskalasi ke Atasan</p>
                        <p class="text-xs text-red-600 mt-0.5">Teruskan ke level jabatan di atas</p>
                    </div>
                </button>
            </div>
            @else
            <div class="bg-gray-50 rounded-xl p-5 text-center">
                <i class="fa-solid fa-lock text-gray-300 text-2xl mb-2"></i>
                <p class="text-sm text-gray-500">Tidak ada tindakan tersedia.</p>
                <p class="text-xs text-gray-400 mt-1">Pengaduan sudah {{ $workflow->status_badge['label'] }}.</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Selesai --}}
<div id="selesai-modal" class="fixed inset-0 bg-black/60 z-[100] hidden flex items-center justify-center p-4" onclick="if(event.target===this) closeSelesaiModal()">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-green-500"></i> Selesaikan Pengaduan
            </h3>
            <button onclick="closeSelesaiModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form id="form-selesai" action="{{ route('kepala-unit.dispositions.selesai', $workflow->id) }}" method="POST">
            @csrf
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan / Solusi <span class="text-red-500">*</span></label>
                    <textarea name="komentar" rows="4" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-400 outline-none" placeholder="Tuliskan solusi / hasil penanganan... (wajib diisi)"></textarea>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                <button type="button" onclick="closeSelesaiModal()" class="bg-white hover:bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-gray-200">Batal</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> Selesaikan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Eskalasi --}}
<div id="eskalasi-modal" class="fixed inset-0 bg-black/60 z-[100] hidden flex items-center justify-center p-4" onclick="if(event.target===this) closeEskalasiModal()">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-arrow-up-right-dots text-red-500"></i> Eskalasi Pengaduan
            </h3>
            <button onclick="closeEskalasiModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form id="form-eskalasi" action="{{ route('kepala-unit.dispositions.eskalasi', $workflow->id) }}" method="POST">
            @csrf
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tujuan <span class="text-red-500">*</span></label>
                    <select name="target_user_id" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white">
                        <option value="">-- Pilih Tujuan --</option>
                        @foreach($eskalasiUsers as $eu)
                            <option value="{{ $eu->id }}">{{ $eu->jabatan?->nama }} ({{ $eu->nama }})</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1.5">User yang akan menerima eskalasi.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan / Alasan</label>
                    <textarea name="komentar" rows="3" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-400 outline-none" placeholder="Alasan eskalasi..."></textarea>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                <button type="button" onclick="closeEskalasiModal()" class="bg-white hover:bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-gray-200">Batal</button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-arrow-up-right-dots"></i> Eskalasi
                </button>
            </div>
        </form>
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
function disableBtn(formId) {
    const f = document.getElementById(formId);
    if (!f) return;
    f.addEventListener('submit', function() {
        const btn = this.querySelector('button[type=submit]');
        if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...'; }
    });
}
disableBtn('form-selesai');
disableBtn('form-eskalasi');

function openSelesaiModal() {
    document.getElementById('selesai-modal').classList.remove('hidden');
    document.getElementById('selesai-modal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closeSelesaiModal() {
    document.getElementById('selesai-modal').classList.add('hidden');
    document.getElementById('selesai-modal').classList.remove('flex');
    document.body.style.overflow = '';
}
function openEskalasiModal() {
    document.getElementById('eskalasi-modal').classList.remove('hidden');
    document.getElementById('eskalasi-modal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closeEskalasiModal() {
    document.getElementById('eskalasi-modal').classList.add('hidden');
    document.getElementById('eskalasi-modal').classList.remove('flex');
    document.body.style.overflow = '';
}
function openPreview(url) {
    document.getElementById('preview-modal').classList.remove('hidden');
    document.getElementById('preview-modal').classList.add('flex');
    document.body.style.overflow = 'hidden';
    document.getElementById('preview-image').src = url;
}
function closePreview() {
    document.getElementById('preview-modal').classList.add('hidden');
    document.getElementById('preview-modal').classList.remove('flex');
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
    if (e.key === 'Escape') { closePreview(); closeSelesaiModal(); closeEskalasiModal(); }
});
</script>

@endsection