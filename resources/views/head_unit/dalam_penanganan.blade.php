@extends('layouts.admin')

@section('title', 'Dalam Penanganan - Halo MANAP')

@section('admin_content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Dalam Penanganan</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Kepala Ruangan</span>
            <span class="text-gray-400">/</span>
            <span>Dalam Penanganan</span>
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
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($workflows as $wf)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-blue-600 font-medium">{{ $wf->ticket->ticket_number }}</td>
                    <td class="px-4 py-3 max-w-[200px] truncate">{{ $wf->ticket->title }}</td>
                    <td class="px-4 py-3">{{ $wf->fromUser?->nama ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $wf->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('head-unit.dispositions.show', $wf->id) }}" class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                            <i class="fa-solid fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400"><i class="fa-solid fa-spinner text-4xl mb-3"></i><p>Tidak ada pengaduan yang sedang ditangani</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($workflows->hasPages())<div class="px-4 py-3 border-t border-gray-100">{{ $workflows->links() }}</div>@endif
</div>

{{-- Cards (mobile) --}}
<div class="space-y-3 md:hidden">
    @forelse($workflows as $wf)
    <a href="{{ route('head-unit.dispositions.show', $wf->id) }}" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-4 active:scale-[0.98] transition-transform">
        <div class="font-mono text-sm font-bold text-blue-600 mb-1">{{ $wf->ticket->ticket_number }}</div>
        <p class="text-sm font-medium text-gray-900 mb-2 line-clamp-2">{{ $wf->ticket->title }}</p>
        <div class="flex items-center justify-between text-xs text-gray-500">
            <span><i class="fa-regular fa-user mr-1"></i>{{ $wf->fromUser?->nama ?? '-' }}</span>
            <span><i class="fa-regular fa-clock mr-1"></i>{{ $wf->created_at->format('d/m H:i') }}</span>
        </div>
        <div class="mt-2 pt-2 border-t border-gray-100"><span class="text-xs text-blue-600 font-medium"><i class="fa-solid fa-eye mr-1"></i>Lihat Detail</span></div>
    </a>
    @empty
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-400"><i class="fa-solid fa-spinner text-4xl mb-3"></i><p>Tidak ada pengaduan yang sedang ditangani</p></div>
    @endforelse
    @if($workflows->hasPages())<div class="pt-2">{{ $workflows->links() }}</div>@endif
</div>
@endsection