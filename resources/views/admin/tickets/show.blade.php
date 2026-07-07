@extends('layouts.admin')

@section('title', 'Detail Pengaduan #' . $ticket->ticket_number . ' - Halo MANAP')

@section('admin_content')

@php
    $statusMap = [
        'NEW'                 => ['label' => 'Baru',     'class' => 'bg-yellow-100 text-yellow-700 border-yellow-300'],
        'TERVERIFIKASI'       => ['label' => 'Terverifikasi', 'class' => 'bg-cyan-100 text-cyan-700 border-cyan-300'],
        'IN_PROGRESS'         => ['label' => 'Diproses', 'class' => 'bg-blue-100 text-blue-700 border-blue-300'],
        'DONE'                => ['label' => 'Selesai',  'class' => 'bg-green-100 text-green-700 border-green-300'],
        'REJECTED'            => ['label' => 'Ditolak',  'class' => 'bg-red-100 text-red-700 border-red-300'],
        'Diproses'            => ['label' => 'Diproses', 'class' => 'bg-blue-100 text-blue-700 border-blue-300'],
        'Menunggu Verifikasi' => ['label' => 'Menunggu Verifikasi', 'class' => 'bg-purple-100 text-purple-700 border-purple-300'],
        'Selesai'             => ['label' => 'Selesai',  'class' => 'bg-green-100 text-green-700 border-green-300'],
    ];
    $typeMap = [
        'Pengaduan'  => ['class' => 'bg-red-100 text-red-700',    'icon' => 'fa-circle-exclamation'],
        'Saran'      => ['class' => 'bg-green-100 text-green-700','icon' => 'fa-square-poll-vertical'],
        'Survei'     => ['class' => 'bg-green-100 text-green-700','icon' => 'fa-square-poll-vertical'],
        'Apresiasi'  => ['class' => 'bg-blue-100 text-blue-700',  'icon' => 'fa-thumbs-up'],
        'Informasi'  => ['class' => 'bg-orange-100 text-orange-700','icon' => 'fa-circle-info'],
    ];
    $statusStyle = $statusMap[$ticket->status] ?? ['label' => $ticket->status, 'class' => 'bg-gray-100 text-gray-700 border-gray-300'];
    $typeStyle   = $typeMap[$ticket->type]   ?? ['class' => 'bg-gray-100 text-gray-700', 'icon' => 'fa-file'];
    $typeLabel   = $ticket->type === 'Saran' ? 'Survei' : $ticket->type;
@endphp

{{-- Page Header --}}
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4 animate-fadeInUp" style="animation-delay:0s">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Detail Pengaduan</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Beranda</span>
            <span class="text-gray-400">/</span>
            <a href="{{ route('admin.tickets.index') }}" class="hover:text-blue-600">Pengaduan</a>
            <span class="text-gray-400">/</span>
            <span class="font-mono font-semibold text-blue-700">{{ $ticket->ticket_number }}</span>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.tickets.index') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors active:scale-[0.97]">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>
</div>

@if(session('success'))
<div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-200">
    <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fadeInUp" style="animation-delay:.05s">

    {{-- LEFT COLUMN: Ticket Detail --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Ticket Info Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-fadeInUp" style="animation-delay:.1s">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-regular fa-file-lines text-blue-600"></i>
                    Informasi Pengaduan
                </h2>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full {{ $typeStyle['class'] }}">
                        <i class="fa-solid {{ $typeStyle['icon'] }}"></i> {{ $typeLabel }}
                    </span>
                    <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full border {{ $statusStyle['class'] }}">
                        {{ $statusStyle['label'] }}
                    </span>
                </div>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">No. Tiket</p>
                        <p class="font-mono font-bold text-blue-700 text-lg">{{ $ticket->ticket_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Tanggal Masuk</p>
                        <p class="font-medium text-gray-800">{{ $ticket->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Unit</p>
                        <p class="font-medium text-gray-800">{{ $ticket->room->unit->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Ruangan</p>
                        <p class="font-medium text-gray-800">{{ $ticket->room->name ?? '-' }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Kategori</p>
                        <p class="font-medium text-gray-800">{{ $ticket->category->name ?? '-' }}</p>
                    </div>
                </div>

                <hr class="border-gray-100">

                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Judul</p>
                    <p class="font-semibold text-gray-900 text-lg leading-snug">{{ $ticket->title }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Isi / Deskripsi</p>
                    <div class="bg-gray-50 rounded-lg p-4 text-gray-700 text-sm leading-relaxed whitespace-pre-line">{{ $ticket->description }}</div>
                </div>

                {{-- Attachment --}}
                @if($ticket->attachment_path)
                @php $ext = strtolower(pathinfo($ticket->attachment_path, PATHINFO_EXTENSION)); $isImage = in_array($ext, ['jpg', 'jpeg', 'png']); @endphp
                <div class="border-t border-gray-100 pt-4">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <i class="fa-solid fa-paperclip text-blue-500"></i> Bukti / Lampiran
                    </p>
                    @if($isImage)
                    <div class="relative group inline-block">
                        <img src="{{ asset('storage/' . $ticket->attachment_path) }}" alt="Lampiran" class="max-h-52 rounded-lg border border-gray-200 shadow-sm cursor-pointer transition-all duration-200 group-hover:shadow-md group-hover:brightness-90" onclick="openPreview('{{ asset('storage/' . $ticket->attachment_path) }}')">
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                            <span class="bg-black/60 text-white text-xs font-medium px-3 py-1.5 rounded-full flex items-center gap-1.5 pointer-events-none">
                                <i class="fa-solid fa-expand"></i> Klik untuk preview
                            </span>
                        </div>
                    </div>
                    @else
                    <a href="{{ asset('storage/' . $ticket->attachment_path) }}" target="_blank" class="inline-flex items-center gap-3 bg-blue-50 text-blue-700 px-4 py-3 rounded-xl text-sm font-medium hover:bg-blue-100 transition-colors border border-blue-200">
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

    {{-- RIGHT COLUMN: Reporter & Status Update --}}
    <div class="space-y-6">

        @php $isClosed = $ticket->status === 'DONE' || $ticket->status === 'Selesai'; $isWaitingVerification = optional($ticket->activeWorkflow)->status === 'menunggu_verifikasi'; @endphp

        {{-- Mobile Action Strip --}}
        <div class="md:hidden flex gap-2 sticky top-0 z-30 bg-[#F3F4F6] py-2 -mx-1 px-1 shadow-sm border-b border-gray-200 animate-fadeInUp" style="animation-delay:.15s">
            @if($ticket->status === 'NEW')
            <button onclick="event.preventDefault(); document.getElementById('verify-form').submit();" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-3 py-2.5 rounded-lg transition-all active:scale-[0.97] flex items-center justify-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-check"></i> Setujui
            </button>
            <button onclick="event.preventDefault(); document.getElementById('reject-form').submit();" class="flex-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold px-3 py-2.5 rounded-lg transition-all active:scale-[0.97] flex items-center justify-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-xmark"></i> Tolak
            </button>
            @elseif(!$isClosed)
            @else
            <div class="flex-1 bg-green-50 text-green-700 text-xs font-semibold px-3 py-2.5 rounded-lg flex items-center justify-center gap-1.5 border border-green-200">
                <i class="fa-solid fa-circle-check"></i> Selesai
            </div>
            @endif
        </div>

        {{-- Reporter Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-fadeInUp" style="animation-delay:.2s">
            <button type="button" onclick="toggleSection(this)" data-target="reporter-content" class="w-full bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between text-left group md:cursor-default">
                <h2 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-address-card text-blue-600"></i> Data Pelapor
                </h2>
                <i class="fa-solid fa-chevron-up text-gray-400 transition-transform duration-300 md:hidden group-hover:text-gray-600"></i>
            </button>
            <div id="reporter-content" class="p-6 space-y-4">
                @if($ticket->is_anonymous)
                    <div class="bg-slate-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-user-secret text-4xl text-slate-400 mb-2 block"></i>
                        <p class="text-sm text-slate-500 font-medium">Pengaduan Anonim</p>
                        <p class="text-xs text-slate-400">Identitas pelapor tidak disertakan.</p>
                    </div>
                @else
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Nama</p>
                        <p class="font-semibold text-gray-800">{{ $ticket->reporter_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">No. HP / WA</p>
                        <div class="flex items-center gap-2">
                            <p class="font-medium text-gray-800">{{ $ticket->reporter_phone }}</p>
                            @if($ticket->reporter_phone)
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $ticket->reporter_phone) }}" target="_blank"
                                class="text-green-600 hover:text-green-800 text-lg">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

{{-- Verifikasi Pengaduan (hanya jika status NEW) --}}
        @if($ticket->status === 'NEW')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-fadeInUp" style="animation-delay:.25s">
                </h2>
                <i class="fa-solid fa-chevron-up text-gray-400 transition-transform duration-300 md:hidden group-hover:text-gray-600"></i>
            </button>
            <div id="verify-content" class="p-6">
                <p class="text-sm text-gray-600 mb-4">Verifikasi pengaduan ini sebelum melanjutkan ke disposisi. Pastikan data lengkap dan sesuai.</p>
                <form id="verify-form" action="{{ route('admin.tickets.verify', $ticket->id) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="mb-3">
                        <textarea name="notes" rows="2" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Catatan verifikasi (opsional)..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-all active:scale-[0.97] flex items-center justify-center gap-2 shadow-sm">
                        <i class="fa-solid fa-check"></i> Setujui & Lanjutkan
                    </button>
                </form>
                <form id="reject-form" action="{{ route('admin.tickets.reject', $ticket->id) }}" method="POST" onsubmit="return confirm('Tolak pengaduan ini? Tindakan ini tidak dapat dibatalkan.')">
                    @csrf
                    <div class="mb-3">
                        <textarea name="notes" rows="2" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block p-2.5" placeholder="Alasan penolakan (opsional)..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-all active:scale-[0.97] flex items-center justify-center gap-2 shadow-sm">
                        <i class="fa-solid fa-xmark"></i> Tolak Pengaduan
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Ubah Status: sembunyikan jika status NEW (digantikan verifikasi) --}}
        @if(!$isClosed && !$isWaitingVerification && $ticket->status !== 'NEW')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-fadeInUp" style="animation-delay:.3s">
            <button type="button" onclick="toggleSection(this)" data-target="status-content" class="w-full bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between text-left group md:cursor-default">
                <h2 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-blue-600"></i> Ubah Status
                </h2>
                <i class="fa-solid fa-chevron-up text-gray-400 transition-transform duration-300 md:hidden group-hover:text-gray-600"></i>
            </button>
            <div id="status-content" class="p-6">
                <form action="{{ route('admin.tickets.update', $ticket->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status Saat Ini</label>
                        <select name="status" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            <option value="NEW"         {{ $ticket->status == 'NEW'         ? 'selected' : '' }}>Baru</option>
                            <option value="TERVERIFIKASI" {{ $ticket->status == 'TERVERIFIKASI' ? 'selected' : '' }}>Terverifikasi</option>
                            <option value="IN_PROGRESS" {{ $ticket->status == 'IN_PROGRESS' ? 'selected' : '' }}>Diproses</option>
                            <option value="DONE"        {{ $ticket->status == 'DONE'        ? 'selected' : '' }}>Selesai</option>
                            <option value="REJECTED"    {{ $ticket->status == 'REJECTED'    ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea name="notes" rows="3" placeholder="Masukkan catatan penanganan..." class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-all active:scale-[0.97] shadow-sm">
                        <i class="fa-solid fa-save mr-1"></i> Simpan Status
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Banner Tiket Selesai --}}
        @if($isClosed)
        <div class="bg-green-50 border border-green-200 rounded-xl p-5 flex items-center gap-4 animate-fadeInUp" style="animation-delay:.3s">
            <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0 text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="font-bold text-green-800">Pengaduan Telah Diselesaikan</p>
                <p class="text-sm text-green-600 mt-0.5">Pengaduan ini sudah diverifikasi dan ditutup. Tidak ada tindakan lebih lanjut yang diperlukan.</p>
            </div>
        </div>
        @endif

        @if(!$isClosed && in_array($ticket->status, ['TERVERIFIKASI', 'IN_PROGRESS', 'DONE', 'Diproses', 'Menunggu Verifikasi']))
        {{-- Disposisi / Workflow Aktif: hanya tampil jika belum selesai --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6 animate-fadeInUp" style="animation-delay:.35s">
            <button type="button" onclick="toggleSection(this)" data-target="disposisi-content" class="w-full bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between text-left group md:cursor-default">
                <h2 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-share-nodes text-blue-600"></i> Disposisi
                </h2>
                <div class="flex items-center gap-2">
                    @if(!$ticket->activeWorkflow)
                        <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded font-semibold">Belum Ada</span>
                    @endif
                    <i class="fa-solid fa-chevron-up text-gray-400 transition-transform duration-300 md:hidden group-hover:text-gray-600"></i>
                </div>
            </button>
            <div id="disposisi-content" class="p-6">
                @if(!$ticket->activeWorkflow)
                    <button onclick="openDispositionModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-all active:scale-[0.97] shadow-sm">
                        <i class="fa-solid fa-plus mr-1"></i> Buat Disposisi
                    </button>
                @else
                    @php $wf = $ticket->activeWorkflow; $badge = $wf->status_badge; @endphp
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-500">Unit</span>
                            <span class="text-sm font-semibold text-gray-800">{{ $wf->toUnit?->nama ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-500">Penanggung Jawab</span>
                            <div class="text-right">
                                <span class="text-sm font-medium text-gray-800 block">{{ $wf->toUser?->nama ?? 'Belum ada' }}</span>
                                <span class="text-[10px] text-gray-400">{{ $wf->toJabatan?->nama ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-500">Aksi Terakhir</span>
                            <span class="text-sm font-medium text-gray-800 capitalize">{{ $wf->action_label }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                            <span class="text-xs text-gray-500">Status</span>
                            <span class="text-xs font-semibold px-2 py-1 rounded {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                        </div>
                    </div>
                    @if($wf->status === 'menunggu_verifikasi')
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <form action="{{ route('admin.workflow.tutup', $wf->id) }}" method="POST" onsubmit="var btn=this.querySelector('button[type=submit]'); if(confirm('Verifikasi penanganan ini dan tutup tiket?')){btn.disabled=true; return true;} return false;">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="komentar" rows="2" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 outline-none" placeholder="Catatan verifikasi... (Opsional)"></textarea>
                                </div>
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-all active:scale-[0.97] flex items-center justify-center gap-2 shadow-sm">
                                    <i class="fa-solid fa-stamp"></i> Verifikasi & Tutup Workflow
                                </button>
                            </form>
                        </div>
                    @endif
                @endif
            </div>
        </div>
        @endif

        {{-- Timeline Workflow --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-fadeInUp" style="animation-delay:.4s">
            <button type="button" onclick="toggleSection(this)" data-target="timeline-content" class="w-full bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between text-left group md:cursor-default">
                <h2 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-indigo-500"></i> Timeline Workflow
                </h2>
                <i class="fa-solid fa-chevron-up text-gray-400 transition-transform duration-300 md:hidden group-hover:text-gray-600"></i>
            </button>
            <div id="timeline-content" class="p-6">
            <ol class="relative border-l border-gray-200 ml-4">
                @php $sorted = $ticket->workflows->sortBy('created_at'); @endphp
                @forelse($sorted as $step)
                @php
                    $icon = match($step->action) {
                        'disposisi'       => ['i' => 'fa-paper-plane',      'c' => 'bg-blue-500'],
                        'eskalasi'        => ['i' => 'fa-arrow-up-right-dots','c' => 'bg-red-500'],
                        'tangani_sendiri' => ['i' => 'fa-user-check',        'c' => 'bg-indigo-500'],
                        'selesai'          => ['i' => 'fa-circle-check',      'c' => 'bg-green-500'],
                        'verifikasi'      => ['i' => 'fa-stamp',              'c' => 'bg-purple-500'],
                        'tutup'           => ['i' => 'fa-lock',               'c' => 'bg-gray-500'],
                        default           => ['i' => 'fa-circle',             'c' => 'bg-gray-400'],
                    };
                    $badge = $step->status_badge;
                @endphp
                <li class="mb-8 ml-6">
                    <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full {{ $icon['c'] }} ring-4 ring-white shadow-sm">
                        <i class="fa-solid {{ $icon['i'] }} text-white text-[10px]"></i>
                    </span>
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $step->action_label }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $step->fromUser?->nama ?? 'Sistem' }}
                                @if($step->toUser)
                                    <i class="fa-solid fa-arrow-right text-[9px] mx-1 text-gray-400"></i>
                                    <span class="font-medium text-gray-700">{{ $step->toUser->nama }}</span>
                                    <span class="text-gray-400">({{ $step->toJabatan?->nama ?? '-' }})</span>
                                @endif
                            </p>
                            @if($step->komentar)
                            <p class="mt-2 text-xs text-gray-600 italic bg-gray-50 rounded-lg px-3 py-2 border border-gray-100">"{{ $step->komentar }}"</p>
                            @endif
                        </div>
                        <div class="text-right shrink-0">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                            <p class="text-[10px] text-gray-400 mt-1">{{ $step->created_at->format('d/m H:i') }}</p>
                        </div>
                    </div>
                </li>
                @empty
                <li class="ml-6 text-gray-400 text-sm py-4">Belum ada riwayat workflow.</li>
                @endforelse
            </ol>
            </div>
        </div>

        {{-- Lampiran Penanganan: sembunyikan jika sudah selesai --}}
        @if(!$isClosed)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-fadeInUp" style="animation-delay:.45s">
            <button type="button" onclick="toggleSection(this)" data-target="lampiran-content" class="w-full bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between text-left group md:cursor-default">
                <h2 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-paperclip text-blue-600"></i> Lampiran Penanganan
                </h2>
                <i class="fa-solid fa-chevron-up text-gray-400 transition-transform duration-300 md:hidden group-hover:text-gray-600"></i>
            </button>
            <div id="lampiran-content" class="p-6">
                {{-- Form Upload Lampiran --}}
                <div class="mb-6">
                    <button onclick="toggleUploadForm()" type="button" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg text-sm px-4 py-2 transition-all active:scale-[0.97] border border-gray-300 w-full flex justify-between items-center">
                        <span><i class="fa-solid fa-upload mr-1"></i> Tambah Lampiran</span>
                        <i id="upload-icon" class="fa-solid fa-chevron-down"></i>
                    </button>
                    
                    <form id="upload-form" action="{{ route('admin.tickets.attachments.store', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="mt-4 p-4 border border-gray-200 rounded-lg bg-gray-50 space-y-3 hidden">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Jenis Lampiran</label>
                            <select name="attachment_type" required class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2">
                                <option value="Foto Sebelum">Foto Sebelum</option>
                                <option value="Foto Sesudah">Foto Sesudah</option>
                                <option value="Dokumen">Dokumen</option>
                                <option value="Berita Acara">Berita Acara</option>
                                <option value="Bukti Perbaikan">Bukti Perbaikan</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Keterangan (Opsional)</label>
                            <textarea name="description" rows="2" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Upload File (Max 10MB)</label>
                            <input type="file" name="file" required accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none">
                            <p class="text-[10px] text-gray-500 mt-1">Format: JPG, PNG, PDF, DOCX, XLSX</p>
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-4 py-2 transition-all active:scale-[0.97] shadow-sm w-full">
                                Simpan Lampiran
                            </button>
                        </div>
                    </form>
                </div>

                <script>
                    function toggleUploadForm() {
                        const form = document.getElementById('upload-form');
                        const icon = document.getElementById('upload-icon');
                        if (form.classList.contains('hidden')) {
                            form.classList.remove('hidden');
                            icon.classList.remove('fa-chevron-down');
                            icon.classList.add('fa-chevron-up');
                        } else {
                            form.classList.add('hidden');
                            icon.classList.remove('fa-chevron-up');
                            icon.classList.add('fa-chevron-down');
                        }
                    }
                </script>

                {{-- List Lampiran --}}
                @if($ticket->attachments->isEmpty())
                    <p class="text-sm text-gray-500 text-center italic py-2 border-t border-gray-100 pt-4">Belum ada lampiran.</p>
                @else
                    <div class="space-y-3 border-t border-gray-100 pt-4">
                        @foreach($ticket->attachments as $attachment)
                            @php
                                $isImage = str_starts_with($attachment->mime_type, 'image/');
                                $icon = $isImage ? 'fa-image text-purple-500' : 'fa-file-pdf text-red-500';
                            @endphp
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg bg-white shadow-sm">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div class="w-10 h-10 rounded bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0">
                                        <i class="fa-solid {{ $icon }} text-xl"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-sm font-bold text-gray-800 truncate">{{ $attachment->attachment_type }}</p>
                                        <p class="text-xs text-gray-500 truncate" title="{{ $attachment->file_name }}">{{ $attachment->file_name }}</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">Oleh: {{ $attachment->user->name ?? 'Admin Pengaduan' }} • {{ $attachment->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-1 ml-2 shrink-0">
                                    @if($isImage || $attachment->mime_type == 'application/pdf')
                                    <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="text-xs bg-blue-50 text-blue-700 hover:bg-blue-100 px-2 py-1 rounded font-medium text-center transition-colors">
                                        Lihat
                                    </a>
                                    @endif
                                    <a href="{{ route('admin.tickets.attachments.download', [$ticket->id, $attachment->id]) }}" class="text-xs bg-gray-100 text-gray-700 hover:bg-gray-200 px-2 py-1 rounded font-medium text-center transition-colors">
                                        Download
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Metadata --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-fadeInUp" style="animation-delay:.5s">
            <button type="button" onclick="toggleSection(this)" data-target="metadata-content" class="w-full bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between text-left group md:cursor-default">
                <h2 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-blue-600"></i> Metadata
                </h2>
                <i class="fa-solid fa-chevron-up text-gray-400 transition-transform duration-300 md:hidden group-hover:text-gray-600"></i>
            </button>
            <div id="metadata-content" class="p-6 space-y-3">
            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Metadata</p>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Dibuat</span>
                <span class="text-gray-800 font-medium">{{ $ticket->created_at->diffForHumans() }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Diperbarui</span>
                <span class="text-gray-800 font-medium">{{ $ticket->updated_at->diffForHumans() }}</span>
            </div>
            </div>
        </div>
    </div>
</div>

@if(in_array($ticket->status, ['TERVERIFIKASI', 'IN_PROGRESS', 'DONE']) && !$ticket->activeWorkflow)
{{-- Modal Buat Disposisi --}}
<div id="disposition-modal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
    <div onclick="closeDispositionModal()" class="md:hidden fixed inset-0 z-40"></div>
    <div class="bg-white shadow-xl w-full md:max-w-lg md:mx-4 md:rounded-xl md:max-h-[90vh] overflow-y-auto absolute bottom-0 md:relative rounded-t-2xl z-50 md:z-auto transition-transform duration-300" id="disposition-modal-content">
        <div class="flex items-center justify-between px-5 pt-5 pb-3 border-b border-gray-100 sticky top-0 bg-white z-10">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center md:hidden">
                    <i class="fa-solid fa-share-nodes text-sm"></i>
                </div>
                <h3 class="font-bold text-gray-800 text-lg">Buat Disposisi</h3>
            </div>
            <button onclick="closeDispositionModal()" class="text-gray-400 hover:text-gray-600 w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <div class="px-5 md:px-6 py-4">
        <p class="text-xs text-gray-500 mb-4 md:hidden">Buat disposisi untuk meneruskan pengaduan ke unit terkait.</p>
        
        <form action="{{ route('admin.workflow.disposisi') }}" method="POST" class="space-y-4" onsubmit="document.getElementById('btn-kirim-disposisi').disabled = true;">
            @csrf
            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Unit Tujuan <span class="text-red-500">*</span></label>
                <select name="unit_id" required class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                    <option value="">Pilih Unit</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                    @endforeach
                </select>
                <p class="text-[10px] text-gray-500 mt-1">Sistem akan otomatis meneruskan pengaduan ini ke level awal (entry point) di unit yang dipilih.</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Deadline SLA (Opsional)</label>
                <input type="datetime-local" name="due_at" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" min="{{ date('Y-m-d\TH:i') }}">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Komentar / Instruksi Awal (Opsional)</label>
                <textarea name="komentar" rows="3" placeholder="Tuliskan pesan untuk penerima disposisi..." class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"></textarea>
            </div>
            
            <div class="pt-4 border-t border-gray-100 flex gap-3 justify-end">
                <button type="button" onclick="closeDispositionModal()" class="flex-1 md:flex-none bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-all active:scale-[0.97]">
                    Batal
                </button>
                <button type="submit" id="btn-kirim-disposisi" class="flex-1 md:flex-none bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-all active:scale-[0.97] shadow-sm">
                    <i class="fa-solid fa-paper-plane mr-1"></i> Kirim Disposisi
                </button>
            </div>
        </form>
    </div>
</div>
</div>

<script>
function openDispositionModal() {
    var modal = document.getElementById('disposition-modal');
    var content = document.getElementById('disposition-modal-content');
    if (window.innerWidth < 768) {
        content.style.transform = 'translateY(100%)';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(function() {
            requestAnimationFrame(function() {
                content.style.transform = 'translateY(0)';
            });
        });
    } else {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}
function closeDispositionModal() {
    const modal = document.getElementById('disposition-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endif

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
function toggleSection(btn) {
    if (window.innerWidth >= 768) return;
    var targetId = btn.getAttribute('data-target');
    if (!targetId) return;
    var target = document.getElementById(targetId);
    var icon = btn.querySelector('.fa-chevron-up');
    if (!target) return;
    if (target.classList.contains('hidden')) {
        target.classList.remove('hidden');
        if (icon) icon.style.transform = 'rotate(0deg)';
    } else {
        target.classList.add('hidden');
        if (icon) icon.style.transform = 'rotate(180deg)';
    }
}

function openPreview(url) {
    var modal = document.getElementById('preview-modal');
    var img = document.getElementById('preview-image');
    img.src = url;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    document.body.style.position = 'fixed';
    document.body.style.width = '100%';
}

function closePreview() {
    var modal = document.getElementById('preview-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
    document.body.style.position = '';
    document.body.style.width = '';
}

function downloadPreview() {
    var img = document.getElementById('preview-image');
    var link = document.createElement('a');
    link.download = 'lampiran-pengaduan.png';
    link.href = img.src;
    link.click();
}

document.addEventListener('DOMContentLoaded', function() {
    if (window.innerWidth < 768) {
        var sections = document.querySelectorAll('[data-target]');
        sections.forEach(function(btn) {
            var targetId = btn.getAttribute('data-target');
            var target = document.getElementById(targetId);
            var icon = btn.querySelector('.fa-chevron-up');
            if (target) {
                target.classList.add('hidden');
                if (icon) icon.style.transform = 'rotate(180deg)';
            }
        });
        var firstSection = document.querySelector('[data-target="reporter-content"]');
        if (firstSection) toggleSection(firstSection);
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePreview();
        var modal = document.getElementById('disposition-modal');
        if (modal && !modal.classList.contains('hidden')) closeDispositionModal();
    }
});

document.getElementById('preview-modal')?.addEventListener('touchstart', function(e) {
    if (e.target === this) closePreview();
});
</script>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeInUp {
        animation: fadeInUp 0.45s ease-out both;
    }
</style>

@endsection
