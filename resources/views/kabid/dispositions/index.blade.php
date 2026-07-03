@extends('layouts.admin')

@section('title', 'Kotak Masuk Disposisi - Halo MANAP')

@section('admin_content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Kotak Masuk Disposisi</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Kabid / Kabag</span>
            <span class="text-gray-400">/</span>
            <span>Disposisi</span>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
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
                    <td class="px-4 py-3 font-medium">{{ $workflow->ticket->ticket_number }}</td>
                    <td class="px-4 py-3">{{ $workflow->ticket->title }}</td>
                    <td class="px-4 py-3">{{ $workflow->fromUser?->nama ?? '-' }}</td>
                    <td class="px-4 py-3"><span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $workflow->status_badge['class'] }}">{{ $workflow->status_badge['label'] }}</span></td>
                    <td class="px-4 py-3">{{ $workflow->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('kabid.dispositions.show', $workflow->uuid) }}" class="text-blue-600 hover:underline">Detail</a>
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
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $activeWorkflows->links() }}
    </div>
</div>
@endsection
