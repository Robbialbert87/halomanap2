@extends('layouts.app')

@section('title', 'Lacak Status Pengaduan - Halo MANAP')

@section('content')

@php
    $statusConfig = [
        'NEW'                   => ['label' => 'Baru Masuk',        'color' => 'blue',   'icon' => 'fa-paper-plane',          'step' => 1],
        'Baru'                  => ['label' => 'Baru Masuk',        'color' => 'blue',   'icon' => 'fa-paper-plane',          'step' => 1],
        'Menunggu Verifikasi'   => ['label' => 'Menunggu Verifikasi','color' => 'yellow', 'icon' => 'fa-hourglass-half',       'step' => 1],
        'TERVERIFIKASI'         => ['label' => 'Terverifikasi',     'color' => 'indigo', 'icon' => 'fa-shield-check',         'step' => 2],
        'Diproses'              => ['label' => 'Sedang Diproses',   'color' => 'blue',   'icon' => 'fa-gear',                 'step' => 3],
        'eskalasi'              => ['label' => 'Diteruskan ke Atasan','color' => 'orange','icon' => 'fa-share',               'step' => 3],
        'Selesai'               => ['label' => 'Selesai',           'color' => 'green',  'icon' => 'fa-circle-check',         'step' => 4],
        'Ditutup'               => ['label' => 'Ditutup',           'color' => 'green',  'icon' => 'fa-lock',                 'step' => 4],
        'Ditolak'               => ['label' => 'Tidak Dapat Diproses','color' => 'red',  'icon' => 'fa-circle-xmark',         'step' => 'rejected'],
        'REJECTED'              => ['label' => 'Tidak Dapat Diproses','color' => 'red',  'icon' => 'fa-circle-xmark',         'step' => 'rejected'],
    ];

    $statusMessages = [
        'NEW'                   => ['title' => 'Pengaduan Anda Sudah Kami Terima!', 'body' => 'Pengaduan Anda baru saja masuk ke sistem kami. Tim kami akan segera melakukan verifikasi dan penanganan lebih lanjut. Terima kasih atas kepercayaan Anda.'],
        'Baru'                  => ['title' => 'Pengaduan Anda Sudah Kami Terima!', 'body' => 'Pengaduan Anda baru saja masuk ke sistem kami. Tim kami akan segera melakukan verifikasi dan penanganan lebih lanjut. Terima kasih atas kepercayaan Anda.'],
        'Menunggu Verifikasi'   => ['title' => 'Pengaduan Sedang Dalam Antrian Verifikasi', 'body' => 'Pengaduan Anda sedang diverifikasi oleh tim terkait untuk memastikan kelengkapan dan kevalidan data. Mohon bersabar, proses ini tidak akan memakan waktu lama.'],
        'TERVERIFIKASI'         => ['title' => 'Pengaduan Anda Telah Terverifikasi ✓', 'body' => 'Kabar baik! Pengaduan Anda sudah diverifikasi dan akan segera diserahkan kepada unit terkait untuk ditindaklanjuti. Kami berkomitmen memberikan penanganan terbaik.'],
        'Diproses'              => ['title' => 'Pengaduan Anda Sedang Ditangani', 'body' => 'Tim terkait saat ini sedang aktif menangani pengaduan Anda. Kami bekerja keras untuk memberikan solusi terbaik. Mohon bersabar dan terus pantau perkembangan ini.'],
        'eskalasi'              => ['title' => 'Pengaduan Diteruskan ke Tingkat Lebih Tinggi', 'body' => 'Pengaduan Anda sedang ditangani lebih lanjut oleh pimpinan terkait agar mendapatkan penanganan yang paling tepat dan komprehensif. Ini menunjukkan kami menanggapi pengaduan Anda dengan serius.'],
        'Selesai'               => ['title' => 'Pengaduan Anda Telah Selesai Ditangani', 'body' => 'Pengaduan Anda telah berhasil kami tangani. Terima kasih atas masukan berharga Anda yang sangat membantu kami dalam meningkatkan kualitas pelayanan kepada seluruh pasien.'],
        'Ditutup'               => ['title' => 'Pengaduan Anda Telah Selesai Ditangani', 'body' => 'Pengaduan Anda telah selesai dan resmi ditutup. Kami sangat menghargai kepedulian Anda. Masukan Anda menjadi bagian penting dalam perjalanan kami menuju pelayanan yang lebih baik.'],
        'Ditolak'               => ['title' => 'Mohon Maaf, Pengaduan Tidak Dapat Diproses', 'body' => 'Setelah melalui proses verifikasi, pengaduan Anda tidak dapat kami proses lebih lanjut. Untuk informasi lebih detail, silakan hubungi tim kami di bagian informasi rumah sakit. Kami mohon maaf atas ketidaknyamanan ini.'],
        'REJECTED'              => ['title' => 'Mohon Maaf, Pengaduan Tidak Dapat Diproses', 'body' => 'Setelah melalui proses verifikasi, pengaduan Anda tidak dapat kami proses lebih lanjut. Untuk informasi lebih detail, silakan hubungi tim kami di bagian informasi rumah sakit. Kami mohon maaf atas ketidaknyamanan ini.'],
    ];

    $cfg = isset($ticket) && $ticket ? ($statusConfig[$ticket->status] ?? ['label' => $ticket->status, 'color' => 'gray', 'icon' => 'fa-question', 'step' => 1]) : null;
    $msg = isset($ticket) && $ticket ? ($statusMessages[$ticket->status] ?? ['title' => 'Status Pengaduan', 'body' => 'Pengaduan Anda sedang dalam proses penanganan.']) : null;
@endphp

<div class="bg-gray-50 min-h-screen">

    {{-- HEADER (Glossy) --}}
    <header class="bg-white/70 backdrop-blur-xl sticky top-0 z-50 border-b border-white/30" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
        <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('assets/images/halomanaplogo.png') }}" alt="Halo MANAP" class="w-8 h-8 object-contain">
                <div>
                    <span class="font-bold text-lg text-blue-800 leading-tight">Halo <span class="text-green-600">MANAP</span></span>
                    <p class="text-[8px] text-gray-400 leading-none -mt-0.5">RSUD H. Abdul Manap</p>
                </div>
            </div>
            <a href="/" class="text-sm font-medium text-gray-400 hover:text-blue-600 flex items-center gap-1.5 transition-colors">
                <i class="fa-solid fa-house"></i> Beranda
            </a>
        </div>
    </header>

    <div class="max-w-2xl mx-auto px-4 pt-5 pb-28">

        {{-- TITLE SECTION --}}
        <div class="text-center mb-5">
            <span class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 shadow-md shadow-blue-200/50 mb-3">
                <i class="fa-solid fa-magnifying-glass text-white text-xl"></i>
            </span>
            <h1 class="text-xl md:text-2xl font-bold text-gray-800 mb-1">Lacak Status Pengaduan</h1>
            <p class="text-sm text-gray-400 leading-relaxed max-w-md mx-auto">
                Masukkan nomor tiket yang Anda terima untuk melihat status terkini.
            </p>
        </div>

        {{-- SEARCH FORM --}}
        @if(!$ticket)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5">
            <form method="GET" action="{{ route('pengaduan.track') }}">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-ticket text-blue-500 mr-1"></i> Nomor Tiket Pengaduan
                </label>
                <div class="flex gap-2">
                    <input
                        type="text"
                        name="ticket_number"
                        value="{{ request('ticket_number') }}"
                        placeholder="Contoh: HM260702001"
                        autocomplete="off"
                        class="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-sm font-mono font-semibold text-gray-800 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition uppercase tracking-wider bg-gray-50/50"
                    >
                    <button type="submit" class="bg-gradient-to-br from-blue-500 to-blue-700 text-white px-5 py-3 rounded-xl text-sm font-semibold shadow-md shadow-blue-200/50 active:scale-95 transition-all flex items-center gap-2 whitespace-nowrap">
                        <i class="fa-solid fa-search"></i>
                        <span class="hidden sm:inline">Cari</span>
                    </button>
                </div>
                @if($notFound)
                <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                    <i class="fa-solid fa-circle-info"></i>
                    Nomor tiket dikirim saat Anda berhasil mengajukan pengaduan.
                </p>
                @endif
            </form>
        </div>
        @endif

        {{-- NOT FOUND --}}
        @if($notFound)
        <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-5 flex items-start gap-4">
            <span class="w-11 h-11 rounded-xl bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center shadow-md shadow-red-200/50 flex-shrink-0">
                <i class="fa-solid fa-circle-exclamation text-white text-lg"></i>
            </span>
            <div>
                <h3 class="font-bold text-gray-800 text-sm mb-1">Nomor Tiket Tidak Ditemukan</h3>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Nomor tiket <strong class="font-mono text-red-500">{{ request('ticket_number') }}</strong> tidak terdaftar. Pastikan Anda memasukkan nomor dengan benar.
                </p>
                <p class="text-[11px] text-gray-400 mt-2">Butuh bantuan? Hubungi bagian informasi RSUD H. Abdul Manap.</p>
            </div>
        </div>
        @endif

        {{-- RESULT CARD --}}
        @if($ticket)
        @php
            $colorMap = [
                'blue'   => ['gradient' => 'from-blue-400 to-blue-600',      'badge' => 'bg-blue-100 text-blue-700',   'bar' => 'bg-blue-500'],
                'indigo' => ['gradient' => 'from-indigo-400 to-indigo-600',  'badge' => 'bg-indigo-100 text-indigo-700','bar' => 'bg-indigo-500'],
                'yellow' => ['gradient' => 'from-yellow-400 to-yellow-500',  'badge' => 'bg-yellow-100 text-yellow-700','bar' => 'bg-yellow-400'],
                'orange' => ['gradient' => 'from-orange-400 to-orange-600',  'badge' => 'bg-orange-100 text-orange-700','bar' => 'bg-orange-500'],
                'green'  => ['gradient' => 'from-emerald-400 to-emerald-600','badge' => 'bg-emerald-100 text-emerald-700','bar' => 'bg-emerald-500'],
                'red'    => ['gradient' => 'from-red-400 to-red-600',        'badge' => 'bg-red-100 text-red-700',     'bar' => 'bg-red-500'],
                'gray'   => ['gradient' => 'from-gray-400 to-gray-500',      'badge' => 'bg-gray-100 text-gray-600',   'bar' => 'bg-gray-400'],
            ];
            $c = $colorMap[$cfg['color']];
            $step = $cfg['step'];
        @endphp

        {{-- Status Message Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-4">
            {{-- Header with gradient icon --}}
            <div class="px-5 py-4 flex items-center gap-3 border-b border-gray-100">
                <span class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $c['gradient'] }} flex items-center justify-center shadow-md flex-shrink-0">
                    <i class="fa-solid {{ $cfg['icon'] }} text-white text-xl"></i>
                </span>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[10px] font-semibold font-mono tracking-wider text-gray-400">{{ $ticket->ticket_number }}</span>
                        <span class="text-[10px] px-2.5 py-0.5 rounded-full font-semibold {{ $c['badge'] }}">{{ $cfg['label'] }}</span>
                    </div>
                    <h2 class="font-bold text-gray-800 mt-0.5 text-sm leading-snug">{{ $msg['title'] }}</h2>
                </div>
            </div>

            {{-- Message Body --}}
            <div class="px-5 py-4">
                <p class="text-gray-500 text-xs leading-relaxed mb-4">{{ $msg['body'] }}</p>

                {{-- Progress Steps --}}
                <div class="mb-4">
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2.5">Progres Penanganan</p>
                    @if($step === 'rejected')
                    <div class="flex items-center gap-0">
                        @php
                            $steps = [
                                ['label' => 'Diterima',    'icon' => 'fa-paper-plane'],
                                ['label' => 'Diverifikasi','icon' => 'fa-shield-check'],
                            ];
                        @endphp
                        @foreach($steps as $i => $s)
                            <div class="flex flex-col items-center flex-1">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm border-2 bg-red-500 border-transparent text-white">
                                    <i class="fa-solid {{ $s['icon'] }} text-[10px]"></i>
                                </div>
                                <span class="text-[9px] mt-1 text-center font-medium text-gray-600">{{ $s['label'] }}</span>
                            </div>
                            @if(!$loop->last)
                                <div class="flex-1 h-[3px] rounded-full -mt-[18px] mx-1 bg-red-500"></div>
                            @endif
                        @endforeach
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm border-2 bg-red-500 border-transparent text-white">
                                <i class="fa-solid fa-circle-xmark text-[10px]"></i>
                            </div>
                            <span class="text-[9px] mt-1 text-center font-medium text-red-600">Ditolak</span>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center gap-0">
                        @php
                            $steps = [
                                ['label' => 'Diterima',    'icon' => 'fa-paper-plane'],
                                ['label' => 'Diverifikasi','icon' => 'fa-shield-check'],
                                ['label' => 'Diproses',   'icon' => 'fa-gear'],
                                ['label' => 'Selesai',    'icon' => 'fa-circle-check'],
                            ];
                        @endphp
                        @foreach($steps as $i => $s)
                            <div class="flex flex-col items-center flex-1">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm border-2
                                    {{ ($step >= $i + 1) ? $c['bar'] . ' border-transparent text-white' : 'bg-white border-gray-200 text-gray-300' }}">
                                    <i class="fa-solid {{ $s['icon'] }} text-[10px]"></i>
                                </div>
                                <span class="text-[9px] mt-1 text-center font-medium {{ ($step >= $i + 1) ? 'text-gray-600' : 'text-gray-300' }}">{{ $s['label'] }}</span>
                            </div>
                            @if(!$loop->last)
                                <div class="flex-1 h-[3px] rounded-full -mt-[18px] mx-1 {{ ($step > $i + 1) ? $c['bar'] : 'bg-gray-100' }}"></div>
                            @endif
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- WORKFLOW TIMELINE --}}
                @if(count($timeline) > 1)
                <div class="mb-4">
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-3">Riwayat Penanganan</p>
                    <div class="relative pl-6">
                        {{-- Vertical line --}}
                        <div class="absolute left-2.5 top-2 bottom-2 w-0.5 bg-gray-200"></div>

                        @foreach($timeline as $i => $item)
                        @php
                            $isLast = $loop->last;
                            $isActive = $item['is_active'] ?? false;
                            $isCompletion = $item['type'] === 'selesai' && ($item['komentar'] ?? null);
                            $dotColor = match ($item['type']) {
                                'diterima' => 'bg-blue-500',
                                'diverifikasi' => 'bg-indigo-500',
                                'disposisi' => 'bg-purple-500',
                                'eskalasi' => 'bg-orange-500',
                                'tangani_sendiri' => 'bg-indigo-500',
                                'selesai' => 'bg-emerald-500',
                                'tutup' => 'bg-gray-500',
                                'ditolak' => 'bg-red-500',
                                default => 'bg-gray-400',
                            };
                            $iconColor = match ($item['type']) {
                                'diterima' => 'text-blue-600',
                                'diverifikasi' => 'text-indigo-600',
                                'disposisi' => 'text-purple-600',
                                'eskalasi' => 'text-orange-600',
                                'tangani_sendiri' => 'text-indigo-600',
                                'selesai' => 'text-emerald-600',
                                'tutup' => 'text-gray-600',
                                'ditolak' => 'text-red-600',
                                default => 'text-gray-500',
                            };
                        @endphp
                        <div class="relative pb-4 {{ $isLast ? '' : '' }}">
                            {{-- Dot --}}
                            <div class="absolute -left-4 top-1 w-3.5 h-3.5 rounded-full border-2 border-white {{ $dotColor }} {{ $isActive ? 'ring-2 ring-blue-300 animate-pulse' : '' }} shadow-sm z-10"></div>

                            {{-- Content --}}
                            <div class="bg-white rounded-xl border {{ $isActive ? 'border-blue-200 bg-blue-50/40' : 'border-gray-100' }} p-3 ml-2">
                                <div class="flex items-start gap-2.5">
                                    <span class="w-7 h-7 rounded-lg {{ $isActive ? 'bg-blue-100' : 'bg-gray-100' }} flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i class="fa-solid {{ $item['icon'] }} text-[10px] {{ $iconColor }}"></i>
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-xs font-bold {{ $isActive ? 'text-blue-700' : 'text-gray-700' }}">
                                                {{ $item['label'] }}
                                                @if($isActive)
                                                <span class="ml-1.5 inline-flex items-center gap-1 text-[9px] font-semibold text-blue-600 bg-blue-100 px-1.5 py-0.5 rounded-full">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Aktif
                                                </span>
                                                @endif
                                            </span>
                                            <span class="text-[10px] text-gray-400 whitespace-nowrap flex-shrink-0">{{ $item['time']?->translatedFormat('d M, H:i') }}</span>
                                        </div>

                                        @if($item['user'] ?? $item['jabatan'] ?? null)
                                        <p class="text-[11px] text-gray-500 mt-0.5 flex items-center gap-1 flex-wrap">
                                            <i class="fa-solid fa-user text-[9px] text-gray-400"></i>
                                            @if($item['user'])
                                                @if($item['type'] === 'disposisi' || $item['type'] === 'eskalasi')
                                                → {{ $item['user']->nama }}
                                                @else
                                                {{ $item['user']->nama }}
                                                @endif
                                            @endif
                                            @if($item['jabatan'] ?? null)
                                            <span class="text-[10px] text-gray-400">· {{ $item['jabatan']->nama ?? '-' }}</span>
                                            @endif
                                        </p>
                                        @endif

                                        @if($item['komentar'])
                                        <div class="mt-1.5 text-[11px] text-gray-600 bg-gray-50 rounded-lg px-2.5 py-1.5 border border-gray-100 italic leading-relaxed">
                                            <i class="fa-solid fa-quote-left text-[8px] text-gray-300 mr-0.5"></i>
                                            {{ $item['komentar'] }}
                                        </div>
                                        @endif

                                        @if($isCompletion && $item['completed_at'])
                                        <p class="text-[10px] text-gray-400 mt-1">
                                            <i class="fa-regular fa-clock mr-0.5"></i> Selesai: {{ $item['completed_at']?->translatedFormat('d M Y, H:i') }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Ticket Details --}}
                <div class="bg-gray-50 rounded-xl p-3.5">
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2.5">Detail Pengaduan</p>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <p class="text-[10px] text-gray-400">Judul</p>
                            <p class="text-gray-700 font-semibold text-[11px] leading-snug mt-0.5">{{ $ticket->title }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400">Jenis</p>
                            <p class="text-gray-700 font-semibold text-[11px] mt-0.5">{{ $ticket->type }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400">Kategori</p>
                            <p class="text-gray-700 font-semibold text-[11px] mt-0.5">{{ $ticket->category?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400">Ruangan</p>
                            <p class="text-gray-700 font-semibold text-[11px] mt-0.5">{{ $ticket->room?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400">Tgl Lapor</p>
                            <p class="text-gray-700 font-semibold text-[11px] mt-0.5">{{ $ticket->created_at?->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400">Pelapor</p>
                            <p class="text-gray-700 font-semibold text-[11px] mt-0.5">
                                @if($ticket->is_anonymous) <span class="italic text-gray-400">Anonim</span> @else {{ $ticket->reporter_name ?? '-' }} @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Admin Verification / Rejection Comment --}}
        @php
            $adminWorkflow = $ticket->workflows->firstWhere(function ($w) {
                return in_array($w->action, ['tutup', 'ditolak']);
            });
        @endphp
        @if($adminWorkflow && $adminWorkflow->komentar)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-4">
            <div class="px-5 py-4">
                <div class="flex items-start gap-3">
                    <span class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-sm flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-stamp text-white text-xs"></i>
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">
                            {{ $adminWorkflow->action === 'ditolak' ? 'Alasan Penolakan' : 'Catatan Verifikasi Admin' }}
                        </p>
                        <p class="text-xs text-gray-700 leading-relaxed bg-gray-50 rounded-xl px-3.5 py-2.5 border border-gray-100 italic">
                            "{{ $adminWorkflow->komentar }}"
                        </p>
                        <p class="text-[10px] text-gray-400 mt-1.5">
                            Admin Pengaduan • {{ $adminWorkflow->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Completion Report --}}
        @if($completion)
        <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 overflow-hidden mb-4">
            <div class="px-5 py-4">
                <div class="flex items-start gap-3">
                    <span class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-sm flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-circle-check text-white text-xs"></i>
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Laporan Penyelesaian</p>
                        <p class="text-xs text-emerald-700 font-semibold mb-1">
                            Diselesaikan oleh {{ $completion['user']?->nama ?? 'Petugas' }}
                            @if($completion['jabatan'])
                            <span class="text-emerald-500 font-normal">· {{ $completion['jabatan']->nama }}</span>
                            @endif
                        </p>
                        @if($completion['komentar'])
                        <div class="mt-2 text-xs text-gray-700 leading-relaxed bg-emerald-50 rounded-xl px-3.5 py-2.5 border border-emerald-100">
                            {{ $completion['komentar'] }}
                        </div>
                        @endif
                        @if($completion['completed_at'])
                        <p class="text-[10px] text-gray-400 mt-1.5">
                            <i class="fa-regular fa-clock mr-0.5"></i> {{ $completion['completed_at']->translatedFormat('d M Y, H:i') }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Download Ticket Button --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-700">{{ $ticket->ticket_number }}</p>
                <p class="text-[10px] text-gray-400">Download tiket untuk disimpan</p>
            </div>
            <a href="{{ route('pengaduan.ticket-download', ['ticket_number' => $ticket->ticket_number]) }}"
                class="bg-gradient-to-br from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-semibold rounded-xl px-4 py-2.5 text-xs transition-all shadow-sm shadow-blue-200/50 flex items-center gap-1.5 active:scale-[0.98]">
                <i class="fa-solid fa-download"></i> Download Tiket
            </a>
        </div>

        {{-- Motto --}}
        <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-2xl border border-blue-200/50 px-5 py-4 text-center">
            <p class="text-xs font-semibold text-blue-700 leading-relaxed">
                <i class="fa-solid fa-quote-left text-blue-300 mr-1 text-[9px]"></i>
                RSUD H. Abdul Manap — Melayani dengan Setulus Hati
                <i class="fa-solid fa-quote-right text-blue-300 ml-1 text-[9px]"></i>
            </p>
        </div>

        {{-- Cari nomor lain --}}
        <div class="text-center mt-4">
            <a href="{{ route('pengaduan.track') }}" class="inline-flex items-center gap-1.5 text-xs text-blue-500 hover:text-blue-600 font-medium transition-colors">
                <i class="fa-solid fa-rotate-left text-[10px]"></i> Cari nomor tiket lain
            </a>
        </div>

        @elseif(!$notFound && !request()->filled('ticket_number'))
        {{-- Empty state / guide --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-7 text-center">
            <span class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 shadow-md shadow-blue-200/50 mx-auto mb-3">
                <i class="fa-solid fa-ticket text-white text-xl"></i>
            </span>
            <h3 class="font-bold text-gray-700 text-sm mb-1.5">Di Mana Nomor Tiket Saya?</h3>
            <p class="text-xs text-gray-400 leading-relaxed max-w-sm mx-auto">
                Nomor tiket diberikan saat Anda berhasil mengirimkan pengaduan. Formatnya seperti <strong class="font-mono text-blue-500">HM260702001</strong>.
            </p>
            <div class="mt-5 pt-4 border-t border-gray-100">
                <a href="{{ route('pengaduan.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-br from-blue-500 to-blue-700 text-white text-xs font-semibold px-5 py-2.5 rounded-xl shadow-md shadow-blue-200/50 active:scale-95 transition-all">
                    <i class="fa-solid fa-plus"></i> Buat Pengaduan Baru
                </a>
            </div>
        </div>
        @endif

        {{-- Footer Links --}}
        <div class="text-center mt-6 text-[11px] text-gray-400 flex items-center justify-center gap-3">
            <a href="/" class="hover:text-blue-600 transition-colors flex items-center gap-1">
                <i class="fa-solid fa-house"></i> Beranda
            </a>
            <span class="text-gray-200">•</span>
            <a href="{{ route('pengaduan.create') }}" class="hover:text-blue-600 transition-colors flex items-center gap-1">
                <i class="fa-solid fa-file-pen"></i> Buat Pengaduan
            </a>
            <span class="text-gray-200">•</span>
            <a href="{{ route('pengaduan.track') }}" class="hover:text-blue-600 transition-colors flex items-center gap-1">
                <i class="fa-solid fa-magnifying-glass"></i> Lacak
            </a>
        </div>

    </div>
</div>

@endsection
