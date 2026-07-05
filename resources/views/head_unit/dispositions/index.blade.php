@extends('layouts.admin')

@section('title', 'Tugas Pengaduan Saya - Halo MANAP')

@section('admin_content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Tugas Pengaduan Saya</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar pengaduan yang didisposisikan kepada Anda ({{ $user->jabatan?->nama ?? '-' }} - {{ $user->unit?->nama ?? '-' }}).</p>
    </div>
</div>

{{-- Table (desktop) --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hidden md:block">
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
                    <td class="px-4 py-3 font-medium text-gray-900 max-w-[200px] truncate">{{ $wf->ticket->title }}</td>
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
    <div class="px-6 py-4 border-t border-gray-100">{{ $activeWorkflows->links() }}</div>
    @endif
</div>

{{-- Cards (mobile) --}}
<div class="space-y-3 md:hidden">
    @forelse($activeWorkflows as $wf)
    @php $badge = $wf->status_badge; $overdue = $wf->due_at && $wf->due_at->isPast(); @endphp
    <a href="{{ route('head-unit.dispositions.show', $wf->id) }}" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-4 active:scale-[0.98] transition-transform">
        <div class="flex items-start justify-between mb-2">
            <div class="font-mono text-sm font-bold text-blue-600">{{ $wf->ticket->ticket_number }}</div>
            <span class="shrink-0 inline-block px-2 py-0.5 text-[10px] font-semibold rounded {{ $badge['class'] }}">{{ $badge['label'] }}</span>
        </div>
        <p class="text-sm font-medium text-gray-900 mb-2 line-clamp-2">{{ $wf->ticket->title }}</p>
        <div class="flex items-center justify-between text-xs text-gray-500">
            <span><i class="fa-regular fa-user mr-1"></i>{{ $wf->fromUser?->nama ?? '-' }}</span>
            @if($wf->due_at)
            <span class="{{ $overdue ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                <i class="fa-regular fa-clock mr-1"></i>{{ $wf->due_at->diffForHumans() }}
            </span>
            @endif
        </div>
        <div class="mt-3 pt-2 border-t border-gray-100">
            <span class="text-xs text-blue-600 font-medium"><i class="fa-solid fa-eye mr-1"></i>Lihat Detail</span>
        </div>
    </a>
    @empty
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-400">
        <i class="fa-solid fa-inbox text-4xl mb-3"></i>
        <p>Tidak ada tugas pengaduan aktif.</p>
    </div>
    @endforelse
    @if($activeWorkflows->hasPages())
    <div class="pt-2">{{ $activeWorkflows->links() }}</div>
    @endif
</div>
@endsection