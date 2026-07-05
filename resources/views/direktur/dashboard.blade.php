@extends('layouts.admin')

@section('title', 'Dashboard Monitoring - Halo MANAP')

@section('admin_content')
<div class="mb-6">
    <div class="rounded-2xl bg-gradient-to-r from-blue-700 to-blue-900 px-8 py-6 text-white shadow-lg">
        <p class="text-blue-200 text-sm font-medium mb-1">Selamat Datang,</p>
        <h1 class="text-2xl font-bold">{{ $user->nama }}</h1>
        <p class="text-blue-200 text-sm mt-1">RSUD H. Abdul Manap Kota Jambi &mdash; Dashboard Monitoring</p>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 border-l-4 border-l-blue-500">
        <p class="text-xs text-gray-500 font-medium">Total Pengaduan</p>
        <p class="text-2xl font-bold text-gray-800">{{ $totalTickets }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 border-l-4 border-l-yellow-400">
        <p class="text-xs text-gray-500 font-medium">Dalam Proses</p>
        <p class="text-2xl font-bold text-gray-800">{{ $diproses }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 border-l-4 border-l-green-500">
        <p class="text-xs text-gray-500 font-medium">Selesai</p>
        <p class="text-2xl font-bold text-gray-800">{{ $selesai }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 border-l-4 border-l-red-500">
        <p class="text-xs text-gray-500 font-medium">SLA Terlambat</p>
        <p class="text-2xl font-bold {{ $slaBreach > 0 ? 'text-red-600' : 'text-gray-800' }}">{{ $slaBreach }}</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
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
                        <th class="px-4 py-3 text-center">Tenggat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($activeWorkflows as $wf)
                    @php $badge = $wf->status_badge; @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.monitoring.ticket.show', $wf->ticket_id) }}" class="font-mono text-blue-600 hover:underline text-xs">
                                {{ $wf->ticket?->ticket_number ?? '-' }}
                            </a>
                            <div class="text-xs text-gray-400 mt-0.5 truncate max-w-[140px]">{{ $wf->ticket?->title ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3 text-xs">{{ $wf->toUnit?->nama ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="text-xs font-medium text-gray-900">{{ $wf->toUser?->nama ?? '-' }}</div>
                            <div class="text-[10px] text-gray-400">{{ $wf->toJabatan?->nama ?? '' }}</div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                        </td>
                        <td class="px-4 py-3 text-center text-xs">
                            @if($wf->due_at)
                                <span class="{{ $wf->due_at->isPast() ? 'text-red-600 font-semibold' : 'text-gray-600' }}">{{ $wf->due_at->diffForHumans() }}</span>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-gray-400 text-sm">Tidak ada workflow aktif.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex flex-col gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2 text-sm"><i class="fa-solid fa-trophy text-amber-500"></i> Unit Tercepat</h2>
            </div>
            <ul class="divide-y divide-gray-100">
                @forelse($topUnits as $i => $u)
                <li class="px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-amber-50 text-amber-600 text-xs font-bold flex items-center justify-center">{{ $i + 1 }}</span>
                        <span class="text-sm text-gray-800">{{ $u['unit'] }}</span>
                    </div>
                    <span class="text-xs text-gray-500">{{ $u['avg_hours'] }} jam</span>
                </li>
                @empty
                <li class="px-5 py-6 text-center text-gray-400 text-sm">Belum ada data.</li>
                @endforelse
            </ul>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2 text-sm"><i class="fa-solid fa-circle-exclamation text-red-500"></i> Unit Terlambat</h2>
            </div>
            <ul class="divide-y divide-gray-100">
                @forelse($bottomUnits as $i => $u)
                <li class="px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-red-50 text-red-600 text-xs font-bold flex items-center justify-center">{{ $i + 1 }}</span>
                        <span class="text-sm text-gray-800">{{ $u['unit'] }}</span>
                    </div>
                    <span class="text-xs text-red-500">{{ $u['avg_hours'] }} jam</span>
                </li>
                @empty
                <li class="px-5 py-6 text-center text-gray-400 text-sm">Belum ada data.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection