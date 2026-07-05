@extends('layouts.admin')

@section('title', 'Dashboard Monitoring Direktur - Halo MANAP')

@section('admin_content')
<div class="mb-6">
    <div class="rounded-2xl bg-gradient-to-r from-blue-700 to-blue-900 px-8 py-6 text-white shadow-lg">
        <p class="text-blue-200 text-sm font-medium mb-1">Selamat Datang,</p>
        <h1 class="text-2xl font-bold">{{ auth()->user()->nama }}</h1>
        <p class="text-blue-200 text-sm mt-1">RSUD H. Abdul Manap Kota Jambi &mdash; Dashboard Monitoring</p>
    </div>
</div>

{{-- ── Card Stats ──────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
    @php
    $cards = [
        ['label' => 'Total',              'value' => $stats['total'],               'icon' => 'fa-inbox',           'color' => 'blue'],
        ['label' => 'Baru',               'value' => $stats['baru'],                'icon' => 'fa-envelope',        'color' => 'indigo'],
        ['label' => 'Dalam Penanganan',   'value' => $stats['dalam_penanganan'],    'icon' => 'fa-gears',           'color' => 'amber'],
        ['label' => 'Menunggu Verifikasi','value' => $stats['menunggu_verifikasi'], 'icon' => 'fa-hourglass-half',  'color' => 'orange'],
        ['label' => 'Selesai',            'value' => $stats['selesai'],             'icon' => 'fa-circle-check',    'color' => 'green'],
        ['label' => 'Lewati SLA',         'value' => $stats['sla_breach'],          'icon' => 'fa-triangle-exclamation', 'color' => 'red'],
    ];
    $palette = [
        'blue'   => 'bg-blue-50 text-blue-700 border-blue-100',
        'indigo' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
        'amber'  => 'bg-amber-50 text-amber-700 border-amber-100',
        'orange' => 'bg-orange-50 text-orange-700 border-orange-100',
        'green'  => 'bg-green-50 text-green-700 border-green-100',
        'red'    => 'bg-red-50 text-red-700 border-red-100',
    ];
    @endphp
    @foreach($cards as $card)
    <div class="bg-white rounded-xl border {{ $palette[$card['color']] }} p-5 shadow-sm flex flex-col gap-2">
        <div class="flex items-center justify-between">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $card['label'] }}</p>
            <i class="fa-solid {{ $card['icon'] }} text-sm opacity-60"></i>
        </div>
        <p class="text-3xl font-bold">{{ $card['value'] }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    {{-- ── Workflow Monitoring ─────────────────────────────────────── --}}
    <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2"><i class="fa-solid fa-diagram-project text-blue-500"></i> Workflow Aktif</h2>
            <span class="text-xs text-gray-400">{{ $activeWorkflows->count() }} tiket</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b">
                    <tr>
                        <th class="px-4 py-3">No. Pengaduan</th>
                        <th class="px-4 py-3">Unit</th>
                        <th class="px-4 py-3">Penanggung Jawab</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">SLA</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($activeWorkflows as $wf)
                    @php $badge = $wf->status_badge; @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.monitoring.ticket.show', $wf->ticket_id) }}" class="font-mono text-blue-600 hover:underline text-xs">
                                {{ $wf->ticket->ticket_number ?? '-' }}
                            </a>
                            <div class="text-xs text-gray-400 mt-0.5 truncate max-w-[120px]">{{ $wf->ticket->title ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3 text-xs">{{ $wf->toUnit?->nama ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="text-xs font-medium text-gray-900">{{ $wf->toUser?->nama ?? 'Belum Ditugaskan' }}</div>
                            <div class="text-[10px] text-gray-400">{{ $wf->toJabatan?->nama ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                        </td>
                        <td class="px-4 py-3 text-center text-xs">
                            @if($wf->due_at)
                                @php $overdue = $wf->due_at->isPast(); @endphp
                                <span class="{{ $overdue ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                    {{ $wf->due_at->diffForHumans() }}
                                </span>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-gray-400 text-sm">Tidak ada workflow aktif saat ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Eskalasi Terbaru ────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
            <i class="fa-solid fa-arrow-up-right-dots text-red-500 text-sm"></i>
            <h2 class="font-semibold text-gray-800">Eskalasi Terbaru</h2>
        </div>
        <ul class="divide-y divide-gray-100">
            @forelse($latestEscalations as $esc)
            <li class="px-5 py-4 hover:bg-gray-50/50 transition-colors">
                <a href="{{ route('admin.monitoring.ticket.show', $esc->ticket_id) }}" class="block">
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <span class="font-mono text-xs text-blue-600">{{ $esc->ticket->ticket_number ?? '-' }}</span>
                        <span class="text-[10px] text-gray-400">{{ $esc->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="text-xs text-gray-700 flex items-center gap-1.5">
                        <span class="text-gray-500">{{ $esc->fromJabatan?->nama ?? '-' }}</span>
                        <i class="fa-solid fa-arrow-right text-[9px] text-red-400"></i>
                        <span class="font-medium text-gray-800">{{ $esc->toJabatan?->nama ?? '-' }}</span>
                    </div>
                    <div class="text-[10px] text-gray-400 mt-0.5">{{ $esc->toUnit?->nama ?? '-' }}</div>
                </a>
            </li>
            @empty
            <li class="px-5 py-8 text-center text-gray-400 text-sm">Belum ada eskalasi.</li>
            @endforelse
        </ul>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    {{-- ── Pengaduan Terbaru ───────────────────────────────────────── --}}
    <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2"><i class="fa-regular fa-comment-dots text-indigo-500"></i> Pengaduan Terbaru</h2>
        </div>
        <ul class="divide-y divide-gray-100">
            @forelse($latestTickets as $ticket)
            <li class="px-6 py-4 hover:bg-gray-50/50 transition-colors flex items-center gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-0.5">
                        <span class="font-mono text-xs text-blue-600">{{ $ticket->ticket_number }}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded bg-gray-100 text-gray-600">{{ $ticket->type }}</span>
                    </div>
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $ticket->title }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $ticket->created_at->diffForHumans() }}</p>
                </div>
                <span class="text-xs px-2.5 py-1 rounded-full font-semibold whitespace-nowrap
                    @if($ticket->status == 'Baru') bg-blue-100 text-blue-700
                    @elseif($ticket->status == 'Diproses') bg-amber-100 text-amber-700
                    @elseif($ticket->status == 'Selesai') bg-green-100 text-green-700
                    @else bg-gray-100 text-gray-600 @endif">
                    {{ $ticket->status }}
                </span>
            </li>
            @empty
            <li class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada pengaduan.</li>
            @endforelse
        </ul>
    </div>

    {{-- ── Top & Bottom Unit ───────────────────────────────────────── --}}
    <div class="flex flex-col gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2 text-sm"><i class="fa-solid fa-trophy text-amber-500"></i> Top Unit (Tercepat)</h2>
            </div>
            <ul class="divide-y divide-gray-100">
                @forelse($topUnits as $i => $unit)
                <li class="px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-amber-50 text-amber-600 text-xs font-bold flex items-center justify-center">{{ $i + 1 }}</span>
                        <span class="text-sm text-gray-800">{{ $unit['unit'] }}</span>
                    </div>
                    <span class="text-xs text-gray-500">~{{ $unit['avg_hours'] }}j</span>
                </li>
                @empty
                <li class="px-5 py-6 text-center text-gray-400 text-sm">Belum ada data.</li>
                @endforelse
            </ul>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2 text-sm"><i class="fa-solid fa-circle-exclamation text-red-500"></i> Bottom Unit (Terlambat)</h2>
            </div>
            <ul class="divide-y divide-gray-100">
                @forelse($bottomUnits as $i => $unit)
                <li class="px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-red-50 text-red-600 text-xs font-bold flex items-center justify-center">{{ $i + 1 }}</span>
                        <span class="text-sm text-gray-800">{{ $unit['unit'] }}</span>
                    </div>
                    <span class="text-xs text-red-500">~{{ $unit['avg_hours'] }}j</span>
                </li>
                @empty
                <li class="px-5 py-6 text-center text-gray-400 text-sm">Belum ada data.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
