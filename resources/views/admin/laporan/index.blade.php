@extends('layouts.admin')

@section('title', 'Laporan Pengaduan - Halo MANAP')

@section('admin_content')

{{-- Mobile Page Header --}}
<div class="md:hidden mb-3">
    <div class="flex items-center gap-2.5 p-1">
        <span class="w-9 h-9 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200/50 flex-shrink-0">
            <i class="fa-solid fa-file-lines text-white text-sm"></i>
        </span>
        <div>
            <p class="text-[9px] text-blue-500 font-semibold tracking-wider uppercase font-heading">Monitoring & Laporan</p>
            <h1 class="text-base font-bold text-gray-800 font-heading">Laporan Pengaduan</h1>
        </div>
    </div>
</div>

{{-- Page Title (Desktop) --}}
<div class="hidden md:flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-heading">Laporan Pengaduan</h1>
        <p class="text-sm text-gray-500">Filter dan export data pengaduan</p>
    </div>
</div>

{{-- FILTER FORM --}}
<div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/30 p-4 md:p-6 mb-4 md:mb-6" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
    <form method="GET" action="{{ route('admin.laporan') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 md:gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}"
                class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}"
                class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Unit</label>
            <select name="unit_id"
                class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                <option value="">Semua Unit</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Kategori</label>
            <select name="category_id"
                class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
            <select name="status"
                class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                <option value="">Semua Status</option>
                @foreach($statuses as $st)
                    <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </div>
        <div class="sm:col-span-2 lg:col-span-5 flex gap-2 mt-1">
            <button type="submit"
                class="bg-gradient-to-br from-blue-500 to-blue-700 text-white font-semibold rounded-xl px-6 py-2.5 text-sm shadow-md shadow-blue-200/50 hover:shadow-lg active:scale-[0.98] transition-all">
                <i class="fa-solid fa-filter mr-1.5"></i> Filter
            </button>
            <a href="{{ route('admin.laporan') }}"
                class="bg-white/70 border border-gray-200 text-gray-600 font-medium rounded-xl px-6 py-2.5 text-sm hover:bg-gray-50 active:scale-[0.98] transition-all">
                <i class="fa-solid fa-rotate-right mr-1.5"></i> Reset
            </a>
            <a href="{{ route('admin.laporan.export-pdf', request()->query()) }}"
                class="bg-gradient-to-br from-red-500 to-red-700 text-white font-semibold rounded-xl px-6 py-2.5 text-sm shadow-md shadow-red-200/50 hover:shadow-lg active:scale-[0.98] transition-all ml-auto">
                <i class="fa-solid fa-file-pdf mr-1.5"></i> Export PDF
            </a>
        </div>
    </form>
</div>

{{-- STATISTIK CARDS --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-4 md:mb-6">
    @php
    $statCards = [
        ['label' => 'Total',        'value' => $total,               'icon' => 'fa-inbox',           'color' => 'blue'],
        ['label' => 'Baru',         'value' => $baru,                'icon' => 'fa-envelope',        'color' => 'indigo'],
        ['label' => 'Diproses',     'value' => $diproses,            'icon' => 'fa-gears',           'color' => 'amber'],
        ['label' => 'Menunggu Ver.', 'value' => $menungguVerifikasi, 'icon' => 'fa-hourglass-half',  'color' => 'orange'],
        ['label' => 'Selesai',      'value' => $selesai,             'icon' => 'fa-circle-check',    'color' => 'green'],
        ['label' => 'Ditolak',      'value' => $ditolak,             'icon' => 'fa-circle-xmark',    'color' => 'red'],
    ];
    $palette = [
        'blue'   => 'bg-blue-50/80 text-blue-700 border-blue-200',
        'indigo' => 'bg-indigo-50/80 text-indigo-700 border-indigo-200',
        'amber'  => 'bg-amber-50/80 text-amber-700 border-amber-200',
        'orange' => 'bg-orange-50/80 text-orange-700 border-orange-200',
        'green'  => 'bg-green-50/80 text-green-700 border-green-200',
        'red'    => 'bg-red-50/80 text-red-700 border-red-200',
    ];
    $iconColors = [
        'blue'   => 'from-blue-400 to-blue-600',
        'indigo' => 'from-indigo-400 to-indigo-600',
        'amber'  => 'from-amber-400 to-amber-600',
        'orange' => 'from-orange-400 to-orange-600',
        'green'  => 'from-green-400 to-green-600',
        'red'    => 'from-red-400 to-red-600',
    ];
    @endphp
    @foreach($statCards as $card)
    <div class="rounded-xl border {{ $palette[$card['color']] }} p-3 md:p-4 shadow-sm flex flex-col gap-1 md:gap-2" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
        <div class="flex items-center justify-between">
            <p class="text-[10px] md:text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $card['label'] }}</p>
            <span class="w-8 h-8 rounded-xl bg-gradient-to-br {{ $iconColors[$card['color']] }} flex items-center justify-center shadow-sm flex-shrink-0">
                <i class="fa-solid {{ $card['icon'] }} text-white text-xs"></i>
            </span>
        </div>
        <p class="text-xl md:text-3xl font-bold font-heading">{{ $card['value'] }}</p>
    </div>
    @endforeach
</div>

{{-- TABEL DATA --}}
<div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/30 overflow-hidden" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
    <div class="px-4 md:px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-sm md:text-base font-bold text-gray-800 font-heading flex items-center gap-2">
            <span class="w-7 h-7 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm">
                <i class="fa-solid fa-list text-white text-xs"></i>
            </span>
            Data Pengaduan ({{ $tickets->count() }})
        </h2>
    </div>

    @if($tickets->isEmpty())
        <div class="text-center py-12 text-gray-400">
            <i class="fa-solid fa-inbox text-4xl mb-3"></i>
            <p class="text-sm font-medium">Tidak ada data pengaduan</p>
            <p class="text-xs mt-1">Coba ubah filter atau periode tanggal</p>
        </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50/80 text-gray-500 text-[11px] md:text-xs uppercase tracking-wider font-semibold">
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

@endsection
