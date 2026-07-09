@extends('layouts.admin')

@section('title', 'Dashboard Monitoring Direktur - Halo MANAP')

@section('admin_content')

{{-- Mobile Page Header (PayApp style) --}}
<div class="md:hidden mb-3">
    <div class="flex items-center gap-2.5 p-1">
        <span class="w-9 h-9 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-sm shadow-emerald-200/50 flex-shrink-0">
            <i class="fa-solid fa-chart-line text-white text-sm"></i>
        </span>
        <div>
            <p class="text-[9px] text-emerald-500 font-semibold tracking-wider uppercase font-heading">Monitoring & Laporan</p>
            <h1 class="text-base font-bold text-gray-800 font-heading">Dashboard Monitoring</h1>
        </div>
    </div>
</div>

{{-- Welcome Banner --}}
<div class="rounded-2xl bg-gradient-to-r from-blue-700 to-blue-900 px-5 md:px-8 py-5 md:py-6 text-white shadow-lg mb-4 md:mb-6">
    <p class="text-blue-200 text-[11px] md:text-sm font-medium mb-0.5">Selamat Datang,</p>
    <h1 class="text-lg md:text-2xl font-bold font-heading">{{ auth()->user()->nama }}</h1>
    <p class="text-blue-200 text-[11px] md:text-sm mt-0.5">RSUD H. Abdul Manap Kota Jambi &mdash; Dashboard Monitoring</p>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3 md:gap-4 mb-6 md:mb-8">
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
        'blue'   => 'bg-blue-50/80 text-blue-700 border-blue-200',
        'indigo' => 'bg-indigo-50/80 text-indigo-700 border-indigo-200',
        'amber'  => 'bg-amber-50/80 text-amber-700 border-amber-200',
        'orange' => 'bg-orange-50/80 text-orange-700 border-orange-200',
        'green'  => 'bg-green-50/80 text-green-700 border-green-200',
        'red'    => 'bg-red-50/80 text-red-700 border-red-200',
    ];
    @endphp
    @foreach($cards as $card)
    <div class="rounded-xl border {{ $palette[$card['color']] }} p-3 md:p-5 shadow-sm flex flex-col gap-1 md:gap-2" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
        <div class="flex items-center justify-between">
            <p class="text-[10px] md:text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $card['label'] }}</p>
            <i class="fa-solid {{ $card['icon'] }} text-xs md:text-sm opacity-60"></i>
        </div>
        <p class="text-xl md:text-3xl font-bold">{{ $card['value'] }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
    {{-- Workflow Monitoring --}}
    <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
        <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800 text-sm md:text-base flex items-center gap-2 font-heading"><i class="fa-solid fa-diagram-project text-blue-500"></i> Workflow Aktif</h2>
            <span class="text-[10px] md:text-xs text-gray-400">{{ $activeWorkflows->count() }} tiket</span>
        </div>
        {{-- Mobile: Workflow cards --}}
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse($activeWorkflows as $wf)
            @php $badge = $wf->status_badge; @endphp
            <div class="px-4 py-3 active:bg-gray-50 transition-colors">
                <a href="{{ route('admin.monitoring.ticket.show', $wf->ticket_id) }}" class="block">
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-mono text-[11px] font-bold text-blue-600">{{ $wf->ticket->ticket_number ?? '-' }}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                    </div>
                    <div class="text-[12px] text-gray-700 font-medium mt-0.5 truncate">{{ $wf->ticket->title ?? '-' }}</div>
                    <div class="flex items-center gap-2 mt-0.5 text-[10px] text-gray-500">
                        <span>{{ $wf->toUnit?->nama ?? '-' }}</span>
                        <span>·</span>
                        <span>{{ $wf->toUser?->nama ?? 'Belum Ditugaskan' }}</span>
                    </div>
                    @if($wf->due_at)
                        @php $overdue = $wf->due_at->isPast(); @endphp
                        <div class="text-[10px] mt-0.5 {{ $overdue ? 'text-red-600 font-semibold' : 'text-gray-400' }}">
                            <i class="fa-regular fa-clock mr-0.5"></i> {{ $wf->due_at->diffForHumans() }}
                        </div>
                    @endif
                </a>
            </div>
            @empty
            <div class="px-4 py-10 text-center text-gray-400 text-sm">Tidak ada workflow aktif saat ini.</div>
            @endforelse
        </div>
        {{-- Desktop: Workflow table --}}
        <div class="hidden md:block overflow-x-auto">
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

    {{-- Eskalasi Terbaru --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
        <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-100 flex items-center gap-2">
            <i class="fa-solid fa-arrow-up-right-dots text-red-500 text-sm"></i>
            <h2 class="font-semibold text-gray-800 text-sm md:text-base font-heading">Eskalasi Terbaru</h2>
        </div>
        <ul class="divide-y divide-gray-100">
            @forelse($latestEscalations as $esc)
            <li class="px-4 md:px-5 py-3 md:py-4 hover:bg-gray-50/50 transition-colors active:bg-gray-100">
                <a href="{{ route('admin.monitoring.ticket.show', $esc->ticket_id) }}" class="block">
                    <div class="flex items-center justify-between gap-2 mb-0.5">
                        <span class="font-mono text-[11px] md:text-xs font-bold text-blue-600">{{ $esc->ticket->ticket_number ?? '-' }}</span>
                        <span class="text-[10px] text-gray-400">{{ $esc->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="text-[12px] md:text-xs text-gray-700 flex items-center gap-1.5">
                        <span class="text-gray-500">{{ $esc->fromJabatan?->nama ?? '-' }}</span>
                        <i class="fa-solid fa-arrow-right text-[9px] text-red-400"></i>
                        <span class="font-medium text-gray-800">{{ $esc->toJabatan?->nama ?? '-' }}</span>
                    </div>
                    <div class="text-[10px] md:text-[10px] text-gray-400 mt-0.5">{{ $esc->toUnit?->nama ?? '-' }}</div>
                </a>
            </li>
            @empty
            <li class="px-5 py-8 text-center text-gray-400 text-sm">Belum ada eskalasi.</li>
            @endforelse
        </ul>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 md:gap-6">
    {{-- Pengaduan Terbaru --}}
    <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
        <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800 text-sm md:text-base flex items-center gap-2 font-heading"><i class="fa-regular fa-comment-dots text-indigo-500"></i> Pengaduan Terbaru</h2>
        </div>
        <ul class="divide-y divide-gray-100">
            @forelse($latestTickets as $ticket)
            <li class="px-4 md:px-6 py-3 md:py-4 hover:bg-gray-50/50 transition-colors flex items-center gap-3 md:gap-4 active:bg-gray-100">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5 md:gap-2 mb-0.5">
                        <span class="font-mono text-[11px] md:text-xs text-blue-600 font-bold">{{ $ticket->ticket_number }}</span>
                        <span class="text-[9px] md:text-[10px] px-1.5 md:px-2 py-0.5 rounded bg-gray-100 text-gray-600">{{ $ticket->type }}</span>
                    </div>
                    <p class="text-[13px] md:text-sm font-medium text-gray-800 truncate">{{ $ticket->title }}</p>
                    <p class="text-[10px] md:text-xs text-gray-400 mt-0.5">{{ $ticket->created_at->diffForHumans() }}</p>
                </div>
                <span class="text-[10px] md:text-xs px-1.5 md:px-2.5 py-0.5 md:py-1 rounded-full font-semibold whitespace-nowrap
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

    {{-- Top & Bottom Unit --}}
    <div class="flex flex-col gap-3 md:gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
            <div class="px-4 md:px-5 py-3 md:py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2 text-sm md:text-base font-heading"><i class="fa-solid fa-trophy text-amber-500"></i> Top Unit (Tercepat)</h2>
            </div>
            <ul class="divide-y divide-gray-100">
                @forelse($topUnits as $i => $unit)
                <li class="px-4 md:px-5 py-2.5 md:py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2 md:gap-3">
                        <span class="w-5 h-5 md:w-6 md:h-6 rounded-full bg-amber-50 text-amber-600 text-[10px] md:text-xs font-bold flex items-center justify-center">{{ $i + 1 }}</span>
                        <span class="text-[12px] md:text-sm text-gray-800">{{ $unit['unit'] }}</span>
                    </div>
                    <span class="text-[10px] md:text-xs text-gray-500">~{{ $unit['avg_hours'] }}j</span>
                </li>
                @empty
                <li class="px-5 py-6 text-center text-gray-400 text-sm">Belum ada data.</li>
                @endforelse
            </ul>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
            <div class="px-4 md:px-5 py-3 md:py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2 text-sm md:text-base font-heading"><i class="fa-solid fa-circle-exclamation text-red-500"></i> Bottom Unit (Terlambat)</h2>
            </div>
            <ul class="divide-y divide-gray-100">
                @forelse($bottomUnits as $i => $unit)
                <li class="px-4 md:px-5 py-2.5 md:py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2 md:gap-3">
                        <span class="w-5 h-5 md:w-6 md:h-6 rounded-full bg-red-50 text-red-600 text-[10px] md:text-xs font-bold flex items-center justify-center">{{ $i + 1 }}</span>
                        <span class="text-[12px] md:text-sm text-gray-800">{{ $unit['unit'] }}</span>
                    </div>
                    <span class="text-[10px] md:text-xs text-red-500">~{{ $unit['avg_hours'] }}j</span>
                </li>
                @empty
                <li class="px-5 py-6 text-center text-gray-400 text-sm">Belum ada data.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
