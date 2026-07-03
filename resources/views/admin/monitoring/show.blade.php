@extends('layouts.admin')

@section('title', 'Detail Pengaduan - Monitoring Direktur')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Detail Pengaduan</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <a href="{{ route('admin.monitoring.index') }}" class="text-blue-600 hover:underline">Monitoring</a>
            <span class="text-gray-400">/</span>
            <span class="font-mono text-blue-600">{{ $ticket->ticket_number }}</span>
        </div>
    </div>
    <a href="{{ route('admin.monitoring.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    {{-- ── Info Pengaduan ──────────────────────────────────────────── --}}
    <div class="xl:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-4">
            <h2 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-regular fa-file-lines text-blue-500"></i> Info Pengaduan
            </h2>
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-xs text-gray-400 uppercase font-semibold">No. Pengaduan</dt>
                    <dd class="font-mono text-blue-600 font-medium">{{ $ticket->ticket_number }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 uppercase font-semibold">Judul</dt>
                    <dd class="text-gray-800 font-medium">{{ $ticket->title }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 uppercase font-semibold">Jenis</dt>
                    <dd class="text-gray-700">{{ $ticket->type }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 uppercase font-semibold">Status</dt>
                    <dd>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            @if($ticket->status == 'Baru') bg-blue-100 text-blue-700
                            @elseif($ticket->status == 'Diproses') bg-amber-100 text-amber-700
                            @elseif($ticket->status == 'Selesai') bg-green-100 text-green-700
                            @else bg-gray-100 text-gray-600 @endif">
                            {{ $ticket->status }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 uppercase font-semibold">Pelapor</dt>
                    <dd class="text-gray-700">{{ $ticket->is_anonymous ? 'Anonim' : ($ticket->reporter_name ?? '-') }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 uppercase font-semibold">Dibuat</dt>
                    <dd class="text-gray-700">{{ $ticket->created_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>

        @if($activeWorkflow)
        <div class="bg-white rounded-xl shadow-sm border border-blue-100 p-6">
            <h2 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-user-clock text-blue-500"></i> Penanggung Jawab Aktif
            </h2>
            <div class="space-y-2 text-sm">
                <div>
                    <p class="text-xs text-gray-400 uppercase font-semibold">Nama</p>
                    <p class="text-gray-900 font-medium">{{ $activeWorkflow->toUser?->nama ?? 'Belum Ditugaskan' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-semibold">Jabatan</p>
                    <p class="text-gray-700">{{ $activeWorkflow->toJabatan?->nama ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-semibold">Unit</p>
                    <p class="text-gray-700">{{ $activeWorkflow->toUnit?->nama ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-semibold">Workflow Level</p>
                    <p class="text-gray-700 font-bold">Level {{ $activeWorkflow->workflow_level }}</p>
                </div>
                @if($activeWorkflow->due_at)
                <div>
                    <p class="text-xs text-gray-400 uppercase font-semibold">Deadline SLA</p>
                    <p class="{{ $activeWorkflow->due_at->isPast() ? 'text-red-600 font-semibold' : 'text-gray-700' }}">
                        {{ $activeWorkflow->due_at->format('d/m/Y H:i') }}
                        <span class="text-xs">({{ $activeWorkflow->due_at->diffForHumans() }})</span>
                    </p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- ── Timeline Workflow ───────────────────────────────────────── --}}
    <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-semibold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-clock-rotate-left text-indigo-500"></i> Timeline Workflow
        </h2>
        <ol class="relative border-l border-gray-200 ml-4">
            @forelse($timeline as $step)
            @php
                $icon = match($step->action) {
                    'disposisi'       => ['i' => 'fa-paper-plane',      'c' => 'bg-blue-500'],
                    'eskalasi'        => ['i' => 'fa-arrow-up-right-dots','c' => 'bg-red-500'],
                    'tangani_sendiri' => ['i' => 'fa-user-check',        'c' => 'bg-indigo-500'],
                    'selesai'         => ['i' => 'fa-circle-check',      'c' => 'bg-green-500'],
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
@endsection
