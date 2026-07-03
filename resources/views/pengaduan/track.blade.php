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
        'Ditolak'               => ['label' => 'Tidak Dapat Diproses','color' => 'red',  'icon' => 'fa-circle-xmark',         'step' => 4],
    ];

    $statusMessages = [
        'NEW'                   => ['title' => 'Pengaduan Anda Sudah Kami Terima!', 'body' => 'Pengaduan Anda baru saja masuk ke sistem kami. Tim kami akan segera melakukan verifikasi dan penanganan lebih lanjut. Terima kasih atas kepercayaan Anda.'],
        'Baru'                  => ['title' => 'Pengaduan Anda Sudah Kami Terima!', 'body' => 'Pengaduan Anda baru saja masuk ke sistem kami. Tim kami akan segera melakukan verifikasi dan penanganan lebih lanjut. Terima kasih atas kepercayaan Anda.'],
        'Menunggu Verifikasi'   => ['title' => 'Pengaduan Sedang Dalam Antrian Verifikasi', 'body' => 'Pengaduan Anda sedang diverifikasi oleh tim terkait untuk memastikan kelengkapan dan kevalidan data. Mohon bersabar, proses ini tidak akan memakan waktu lama.'],
        'TERVERIFIKASI'         => ['title' => 'Pengaduan Anda Telah Terverifikasi ✓', 'body' => 'Kabar baik! Pengaduan Anda sudah diverifikasi dan akan segera diserahkan kepada unit terkait untuk ditindaklanjuti. Kami berkomitmen memberikan penanganan terbaik.'],
        'Diproses'              => ['title' => 'Pengaduan Anda Sedang Ditangani', 'body' => 'Tim terkait saat ini sedang aktif menangani pengaduan Anda. Kami bekerja keras untuk memberikan solusi terbaik. Mohon bersabar dan terus pantau perkembangan ini.'],
        'eskalasi'              => ['title' => 'Pengaduan Diteruskan ke Tingkat Lebih Tinggi', 'body' => 'Pengaduan Anda sedang ditangani lebih lanjut oleh pimpinan terkait agar mendapatkan penanganan yang paling tepat dan komprehensif. Ini menunjukkan kami menanggapi pengaduan Anda dengan serius.'],
        'Selesai'               => ['title' => 'Pengaduan Anda Telah Selesai Ditangani 🎉', 'body' => 'Alhamdulillah, pengaduan Anda telah berhasil kami tangani. Terima kasih atas masukan berharga Anda yang sangat membantu kami dalam meningkatkan kualitas pelayanan kepada seluruh pasien.'],
        'Ditutup'               => ['title' => 'Pengaduan Anda Telah Selesai Ditangani 🎉', 'body' => 'Pengaduan Anda telah selesai dan resmi ditutup. Kami sangat menghargai kepedulian Anda. Masukan Anda menjadi bagian penting dalam perjalanan kami menuju pelayanan yang lebih baik.'],
        'Ditolak'               => ['title' => 'Mohon Maaf, Pengaduan Tidak Dapat Diproses', 'body' => 'Setelah melalui proses verifikasi, pengaduan Anda tidak dapat kami proses lebih lanjut. Untuk informasi lebih detail, silakan hubungi tim kami di bagian informasi rumah sakit. Kami mohon maaf atas ketidaknyamanan ini.'],
    ];

    $cfg = isset($ticket) && $ticket ? ($statusConfig[$ticket->status] ?? ['label' => $ticket->status, 'color' => 'gray', 'icon' => 'fa-question', 'step' => 1]) : null;
    $msg = isset($ticket) && $ticket ? ($statusMessages[$ticket->status] ?? ['title' => 'Status Pengaduan', 'body' => 'Pengaduan Anda sedang dalam proses penanganan.']) : null;
@endphp

<div class="bg-gray-50 min-h-screen" style="background: linear-gradient(135deg, #f0f4ff 0%, #fafafa 60%, #f0f9f4 100%);">

    {{-- HEADER --}}
    <div class="bg-white border-b border-gray-100 shadow-sm">
        <div class="max-w-3xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                <img src="{{ asset('assets/images/halomanaplogo.png') }}" alt="Logo" class="h-8 w-auto">
                <span class="font-bold text-lg text-gray-800">Halo<span class="text-blue-600">MANAP</span></span>
            </a>
            <a href="/" class="text-sm text-gray-500 hover:text-blue-600 flex items-center gap-1.5 transition-colors">
                <i class="fa-solid fa-house"></i> Beranda
            </a>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-10">

        {{-- TITLE SECTION --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-600 shadow-lg shadow-blue-500/30 mb-4">
                <i class="fa-solid fa-magnifying-glass text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Lacak Status Pengaduan</h1>
            <p class="text-gray-500 text-sm leading-relaxed max-w-md mx-auto">
                Masukkan nomor tiket yang Anda terima saat pertama kali mengajukan pengaduan untuk melihat status terkini.
            </p>
        </div>

        {{-- SEARCH FORM — hanya tampil jika belum ada tiket ditemukan --}}
        @if(!$ticket)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <form method="GET" action="{{ route('pengaduan.track') }}">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-ticket text-blue-500 mr-1"></i> Nomor Tiket Pengaduan
                </label>
                <div class="flex gap-3">
                    <input
                        type="text"
                        name="ticket_number"
                        value="{{ request('ticket_number') }}"
                        placeholder="Contoh: HM260702001"
                        autocomplete="off"
                        class="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-sm font-mono font-semibold text-gray-800 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition uppercase tracking-wider"
                    >
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-sm font-semibold shadow-sm shadow-blue-500/30 transition-colors flex items-center gap-2 whitespace-nowrap">
                        <i class="fa-solid fa-search"></i>
                        <span class="hidden sm:inline">Cari Tiket</span>
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
        <div class="bg-red-50 border border-red-100 rounded-2xl p-6 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-red-800 mb-1">Nomor Tiket Tidak Ditemukan</h3>
                <p class="text-sm text-red-600 leading-relaxed">
                    Nomor tiket <strong class="font-mono">{{ request('ticket_number') }}</strong> tidak terdaftar di sistem kami. Pastikan Anda memasukkan nomor tiket dengan benar sesuai yang tercantum di halaman konfirmasi pengaduan.
                </p>
                <p class="text-xs text-red-400 mt-2">Butuh bantuan? Hubungi bagian informasi RSUD H. Abdul Manap.</p>
            </div>
        </div>
        @endif

        {{-- RESULT CARD --}}
        @if($ticket)
        @php
            $colorMap = [
                'blue'   => ['bg' => 'bg-blue-50',   'border' => 'border-blue-200',   'icon_bg' => 'bg-blue-100',   'icon_text' => 'text-blue-600',   'badge' => 'bg-blue-100 text-blue-700',   'bar' => 'bg-blue-500'],
                'indigo' => ['bg' => 'bg-indigo-50',  'border' => 'border-indigo-200',  'icon_bg' => 'bg-indigo-100',  'icon_text' => 'text-indigo-600',  'badge' => 'bg-indigo-100 text-indigo-700',  'bar' => 'bg-indigo-500'],
                'yellow' => ['bg' => 'bg-yellow-50',  'border' => 'border-yellow-200',  'icon_bg' => 'bg-yellow-100',  'icon_text' => 'text-yellow-600',  'badge' => 'bg-yellow-100 text-yellow-700',  'bar' => 'bg-yellow-400'],
                'orange' => ['bg' => 'bg-orange-50',  'border' => 'border-orange-200',  'icon_bg' => 'bg-orange-100',  'icon_text' => 'text-orange-600',  'badge' => 'bg-orange-100 text-orange-700',  'bar' => 'bg-orange-500'],
                'green'  => ['bg' => 'bg-green-50',   'border' => 'border-green-200',   'icon_bg' => 'bg-green-100',   'icon_text' => 'text-green-600',   'badge' => 'bg-green-100 text-green-700',   'bar' => 'bg-green-500'],
                'red'    => ['bg' => 'bg-red-50',     'border' => 'border-red-200',     'icon_bg' => 'bg-red-100',     'icon_text' => 'text-red-600',     'badge' => 'bg-red-100 text-red-700',     'bar' => 'bg-red-500'],
                'gray'   => ['bg' => 'bg-gray-50',    'border' => 'border-gray-200',    'icon_bg' => 'bg-gray-100',    'icon_text' => 'text-gray-500',    'badge' => 'bg-gray-100 text-gray-600',    'bar' => 'bg-gray-400'],
            ];
            $c = $colorMap[$cfg['color']];
            $step = $cfg['step'];
        @endphp

        {{-- Status Message Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-5">
            {{-- Colored Header --}}
            <div class="{{ $c['bg'] }} {{ $c['border'] }} border-b px-6 py-5 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl {{ $c['icon_bg'] }} flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid {{ $cfg['icon'] }} {{ $c['icon_text'] }} text-2xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs font-semibold font-mono tracking-wider text-gray-500">{{ $ticket->ticket_number }}</span>
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold {{ $c['badge'] }}">{{ $cfg['label'] }}</span>
                    </div>
                    <h2 class="font-bold text-gray-800 mt-1 text-base leading-snug">{{ $msg['title'] }}</h2>
                </div>
            </div>

            {{-- Message Body --}}
            <div class="px-6 py-5">
                <p class="text-gray-600 text-sm leading-relaxed mb-5">{{ $msg['body'] }}</p>

                {{-- Progress Steps --}}
                <div class="mb-5">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Progres Penanganan</p>
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
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm border-2
                                    {{ ($step >= $i + 1) ? $c['bar'] . ' border-transparent text-white' : 'bg-white border-gray-200 text-gray-300' }}">
                                    <i class="fa-solid {{ $s['icon'] }} text-xs"></i>
                                </div>
                                <span class="text-[10px] mt-1.5 text-center font-medium {{ ($step >= $i + 1) ? 'text-gray-700' : 'text-gray-300' }}">{{ $s['label'] }}</span>
                            </div>
                            @if(!$loop->last)
                                <div class="flex-1 h-0.5 -mt-5 {{ ($step > $i + 1) ? $c['bar'] : 'bg-gray-200' }}"></div>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Ticket Details --}}
                <div class="bg-gray-50 rounded-xl p-4 space-y-2.5">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Detail Pengaduan</p>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2.5 text-sm">
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Judul</p>
                            <p class="text-gray-700 font-semibold text-xs mt-0.5 leading-snug">{{ $ticket->title }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Jenis</p>
                            <p class="text-gray-700 font-semibold text-xs mt-0.5">{{ $ticket->type }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Kategori</p>
                            <p class="text-gray-700 font-semibold text-xs mt-0.5">{{ $ticket->category?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Ruangan</p>
                            <p class="text-gray-700 font-semibold text-xs mt-0.5">{{ $ticket->room?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Tanggal Lapor</p>
                            <p class="text-gray-700 font-semibold text-xs mt-0.5">{{ $ticket->created_at?->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Pelapor</p>
                            <p class="text-gray-700 font-semibold text-xs mt-0.5">
                                @if($ticket->is_anonymous) <span class="italic text-gray-400">Anonim</span> @else {{ $ticket->reporter_name ?? '-' }} @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Encouragement Quote --}}
        <div class="bg-white rounded-2xl border border-blue-100 px-6 py-5 flex items-start gap-3">
            <div class="text-blue-400 text-xl mt-0.5 flex-shrink-0"><i class="fa-solid fa-quote-left"></i></div>
            <p class="text-sm text-gray-500 italic leading-relaxed">
                "Suara Anda adalah cermin pelayanan kami. Setiap pengaduan yang Anda sampaikan adalah hadiah berharga yang mendorong kami untuk terus tumbuh dan memberikan layanan kesehatan yang lebih baik bagi seluruh masyarakat."
            </p>
        </div>

        {{-- Cari nomor lain --}}
        <div class="text-center mt-5">
            <a href="{{ route('pengaduan.track') }}" class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">
                <i class="fa-solid fa-rotate-left text-xs"></i> Cari nomor tiket lain
            </a>
        </div>

        @elseif(!$notFound && !request()->filled('ticket_number'))
        {{-- Empty state / guide when no search yet --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-8 text-center">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-ticket text-blue-400 text-2xl"></i>
            </div>
            <h3 class="font-bold text-gray-700 mb-2">Di Mana Nomor Tiket Saya?</h3>
            <p class="text-sm text-gray-500 leading-relaxed max-w-sm mx-auto">
                Nomor tiket diberikan saat Anda berhasil mengirimkan pengaduan. Formatnya seperti <strong class="font-mono text-blue-600">HM260702001</strong>.
                Cek halaman konfirmasi atau screenshot yang Anda simpan.
            </p>
            <div class="mt-6 pt-5 border-t border-gray-100">
                <a href="{{ route('pengaduan.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors shadow-sm">
                    <i class="fa-solid fa-plus"></i> Buat Pengaduan Baru
                </a>
            </div>
        </div>
        @endif

        {{-- Footer Links --}}
        <div class="text-center mt-8 text-xs text-gray-400 flex items-center justify-center gap-4">
            <a href="/" class="hover:text-blue-600 transition-colors flex items-center gap-1">
                <i class="fa-solid fa-house"></i> Beranda
            </a>
            <span>•</span>
            <a href="{{ route('pengaduan.create') }}" class="hover:text-blue-600 transition-colors flex items-center gap-1">
                <i class="fa-solid fa-file-pen"></i> Buat Pengaduan
            </a>
            <span>•</span>
            <a href="{{ route('pengaduan.track') }}" class="hover:text-blue-600 transition-colors flex items-center gap-1">
                <i class="fa-solid fa-magnifying-glass"></i> Lacak Status
            </a>
        </div>

    </div>
</div>

@endsection
