@extends('layouts.admin')

@section('title', 'Kotak Masuk Disposisi - Halo MANAP')

@section('admin_content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Kotak Masuk Disposisi</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Kasi / Kasubbag</span>
            <span class="text-gray-400">/</span>
            <span>Disposisi</span>
        </div>
    </div>
</div>

{{-- Table (desktop) --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hidden md:block">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left text-gray-500 font-semibold">
                    <th class="px-4 py-3">No Tiket</th>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Dari</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($activeWorkflows as $workflow)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-blue-600 font-medium">{{ $workflow->ticket->ticket_number }}</td>
                    <td class="px-4 py-3 max-w-[200px] truncate">{{ $workflow->ticket->title }}</td>
                    <td class="px-4 py-3">{{ $workflow->fromUser?->nama ?? '-' }}</td>
                    <td class="px-4 py-3"><span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $workflow->status_badge['class'] }}">{{ $workflow->status_badge['label'] }}</span></td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $workflow->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('kasi.dispositions.show', $workflow->uuid) }}" class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                            <i class="fa-solid fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-inbox text-4xl mb-3"></i>
                        <p>Tidak ada disposisi masuk</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($activeWorkflows->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">{{ $activeWorkflows->links() }}</div>
    @endif
</div>

{{-- Cards (mobile) --}}
<div class="space-y-3 md:hidden">
    @forelse($activeWorkflows as $workflow)
    <a href="{{ route('kasi.dispositions.show', $workflow->uuid) }}" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-4 active:scale-[0.98] transition-transform">
        <div class="flex items-start justify-between mb-2">
            <div class="font-mono text-sm font-bold text-blue-600">{{ $workflow->ticket->ticket_number }}</div>
            <span class="shrink-0 inline-block px-2 py-0.5 text-[10px] font-semibold rounded {{ $workflow->status_badge['class'] }}">{{ $workflow->status_badge['label'] }}</span>
        </div>
        <p class="text-sm font-medium text-gray-900 mb-2 line-clamp-2">{{ $workflow->ticket->title }}</p>
        <div class="flex items-center justify-between text-xs text-gray-500">
            <span><i class="fa-regular fa-user mr-1"></i>{{ $workflow->fromUser?->nama ?? '-' }}</span>
            <span><i class="fa-regular fa-clock mr-1"></i>{{ $workflow->created_at->format('d/m H:i') }}</span>
        </div>
        <div class="mt-3 pt-2 border-t border-gray-100">
            <span class="text-xs text-blue-600 font-medium"><i class="fa-solid fa-eye mr-1"></i>Lihat Detail</span>
        </div>
    </a>
    @empty
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-400">
        <i class="fa-solid fa-inbox text-4xl mb-3"></i>
        <p>Tidak ada disposisi masuk</p>
    </div>
    @endforelse
    @if($activeWorkflows->hasPages())
    <div class="pt-2">{{ $activeWorkflows->links() }}</div>
    @endif
</div>
@endsection