@extends('layouts.admin')

@section('title', 'Tugas Pengaduan Saya - Halo MANAP')

@section('admin_content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Tugas Pengaduan Saya</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar pengaduan yang didisposisikan kepada Anda ({{ $user->jabatan?->nama ?? '-' }} - {{ $user->unit?->nama ?? '-' }}).</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b">
                <tr>
                    <th class="px-4 py-3">No. Tiket</th>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Dari</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Deadline SLA</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($activeWorkflows as $wf)
                @php $badge = $wf->status_badge; @endphp
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-4 py-3 font-mono text-blue-600">{{ $wf->ticket->ticket_number }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $wf->ticket->title }}</td>
                    <td class="px-4 py-3">
                        <div class="text-xs font-medium text-gray-900">{{ $wf->fromUser?->nama ?? 'Sistem / Admin' }}</div>
                        <div class="text-[10px] text-gray-400">{{ $wf->fromJabatan?->nama ?? 'Admin Pengaduan' }}</div>
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
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('head-unit.dispositions.show', $wf->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-medium transition-colors">
                            Buka <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-10 text-center text-gray-400 text-sm">Tidak ada tugas pengaduan aktif saat ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($activeWorkflows->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $activeWorkflows->links() }}
    </div>
    @endif
</div>
@endsection
