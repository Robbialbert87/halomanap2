{{-- STATISTIK — bento asimetris (Total hero + kartu beragam span) --}}
<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-12 gap-3 md:gap-4 mb-4 md:mb-6">
    @php
    $statCards = [
        ['label' => 'Total',         'value' => $total,               'icon' => 'fa-inbox',           'color' => 'blue'],
        ['label' => 'Baru',          'value' => $baru,                'icon' => 'fa-envelope',        'color' => 'indigo'],
        ['label' => 'Diproses',      'value' => $diproses,            'icon' => 'fa-gears',           'color' => 'amber'],
        ['label' => 'Menunggu Ver.', 'value' => $menungguVerifikasi,  'icon' => 'fa-hourglass-half',  'color' => 'orange'],
        ['label' => 'Selesai',       'value' => $selesai,             'icon' => 'fa-circle-check',    'color' => 'green'],
        ['label' => 'Ditolak',       'value' => $ditolak,             'icon' => 'fa-circle-xmark',    'color' => 'red'],
    ];
    $spans = [
        'blue'   => 'xl:col-span-3', 'indigo' => 'xl:col-span-2', 'amber' => 'xl:col-span-2',
        'orange' => 'xl:col-span-1', 'green'  => 'xl:col-span-2', 'red'   => 'xl:col-span-2',
    ];
    $iconBg = [
        'blue'   => 'bg-blue-50 text-blue-600',   'indigo' => 'bg-indigo-50 text-indigo-600',
        'amber'  => 'bg-amber-50 text-amber-600', 'orange' => 'bg-orange-50 text-orange-600',
        'green'  => 'bg-emerald-50 text-emerald-600', 'red' => 'bg-red-50 text-red-600',
    ];
    @endphp
    @foreach($statCards as $card)
    @php $isHero = $card['color'] === 'blue'; @endphp
    <div class="admin-card admin-stat admin-rise relative overflow-hidden p-4 md:p-5 {{ $isHero ? 'col-span-2 md:col-span-3 xl:col-span-3 md:p-6 flex flex-col justify-between' : ($card['color'] === 'red' ? 'col-span-2 md:col-span-1 ' : 'col-span-1 ') . $spans[$card['color']] }}" style="--index: {{ $loop->index }}">
        @if($isHero)
        <div class="absolute -right-10 -top-10 w-36 h-36 rounded-full bg-blue-500/10 blur-2xl pointer-events-none"></div>
        @endif
        <div class="relative flex items-start justify-between gap-3">
            <div class="{{ $isHero ? 'flex-1' : '' }}">
                <p class="text-[10px] md:text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $card['label'] }}</p>
                <p class="{{ $isHero ? 'text-3xl md:text-4xl' : 'text-2xl' }} font-bold font-heading tracking-tight mt-1 {{ $card['color'] === 'red' ? 'text-red-600' : 'text-gray-900' }}">{{ $card['value'] }}</p>
                @if($isHero)
                <p class="text-[11px] text-gray-400 mt-2">Seluruh pengaduan masuk dari semua unit</p>
                @endif
            </div>
            <span class="w-9 h-9 md:w-10 md:h-10 rounded-xl {{ $iconBg[$card['color']] }} flex items-center justify-center flex-shrink-0 shadow-sm {{ $isHero ? 'admin-float' : '' }}">
                <i class="fa-solid {{ $card['icon'] }} text-xs md:text-sm"></i>
            </span>
        </div>
    </div>
    @endforeach
</div>

{{-- TABEL DATA --}}
<div class="admin-card overflow-hidden admin-rise" style="--index: 7">
    <div class="admin-card-head">
        <h2 class="text-sm md:text-base font-bold text-gray-800 font-heading flex items-center gap-2">
            <span class="w-7 h-7 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm">
                <i class="fa-solid fa-list text-white text-xs"></i>
            </span>
            Data Pengaduan ({{ $tickets->count() }})
        </h2>
    </div>

    @if($tickets->isEmpty())
        <div class="admin-empty">
            <i class="fa-solid fa-inbox"></i>
            <p>Tidak ada data pengaduan</p>
            <span>Coba ubah filter atau periode tanggal</span>
        </div>
    @else
    <div class="overflow-x-auto">
        <table class="admin-table w-full text-sm">
            <thead>
                <tr>
                    <th class="text-left px-4 md:px-6 py-3">No Tiket</th>
                    <th class="text-left px-4 md:px-6 py-3">Judul</th>
                    <th class="text-left px-4 md:px-6 py-3 hidden md:table-cell">Pelapor</th>
                    <th class="text-left px-4 md:px-6 py-3 hidden lg:table-cell">Unit</th>
                    <th class="text-left px-4 md:px-6 py-3 hidden lg:table-cell">Kategori</th>
                    <th class="text-left px-4 md:px-6 py-3">Status</th>
                    <th class="text-left px-4 md:px-6 py-3 hidden md:table-cell">Keluhan</th>
                    <th class="text-left px-4 md:px-6 py-3 hidden xl:table-cell">Penyelesaian</th>
                    <th class="text-left px-4 md:px-6 py-3 hidden xl:table-cell">Tgl. Penyelesaian</th>
                    <th class="text-left px-4 md:px-6 py-3 hidden md:table-cell">Tgl. Masuk</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($tickets as $ticket)
                @php
                $penyelesaianWorkflow = $ticket->workflows->first();
                @endphp
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-4 md:px-6 py-3">
                        <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="text-blue-600 font-medium hover:underline">
                            {{ $ticket->ticket_number }}
                        </a>
                    </td>
                    <td class="px-4 md:px-6 py-3 text-gray-800 max-w-[200px] truncate" title="{{ $ticket->title }}">{{ $ticket->title }}</td>
                    <td class="px-4 md:px-6 py-3 text-gray-500 hidden md:table-cell">{{ $ticket->is_anonymous ? 'Anonim' : ($ticket->reporter_name ?? '-') }}</td>
                    <td class="px-4 md:px-6 py-3 text-gray-500 hidden lg:table-cell">{{ $ticket->room?->unit?->nama ?? '-' }}</td>
                    <td class="px-4 md:px-6 py-3 text-gray-500 hidden lg:table-cell">{{ $ticket->category?->name ?? '-' }}</td>
                    <td class="px-4 md:px-6 py-3">
                        @php
                        $badge = match($ticket->status) {
                            'Baru' => 'bg-blue-100 text-blue-700',
                            'TERVERIFIKASI', 'Menunggu Verifikasi' => 'bg-orange-100 text-orange-700',
                            'Diproses' => 'bg-amber-100 text-amber-700',
                            'Selesai' => 'bg-green-100 text-green-700',
                            'Ditolak' => 'bg-red-100 text-red-700',
                            default => 'bg-gray-100 text-gray-700',
                        };
                        @endphp
                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] md:text-xs font-semibold {{ $badge }}">{{ $ticket->status }}</span>
                    </td>
                    <td class="px-4 md:px-6 py-3 text-gray-600 text-xs max-w-[250px] truncate hidden md:table-cell" title="{{ strip_tags($ticket->description) }}">{{ Str::limit(strip_tags($ticket->description), 100) }}</td>
                    <td class="px-4 md:px-6 py-3 text-gray-600 text-xs max-w-[200px] truncate hidden xl:table-cell" title="{{ $penyelesaianWorkflow?->komentar ?? '' }}">{{ $penyelesaianWorkflow?->komentar ? Str::limit($penyelesaianWorkflow->komentar, 80) : '-' }}</td>
                    <td class="px-4 md:px-6 py-3 text-gray-400 text-xs hidden xl:table-cell">{{ $penyelesaianWorkflow?->completed_at ? \Carbon\Carbon::parse($penyelesaianWorkflow->completed_at)->format('d/m/Y H:i') : '-' }}</td>
                    <td class="px-4 md:px-6 py-3 text-gray-400 text-xs hidden md:table-cell">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
