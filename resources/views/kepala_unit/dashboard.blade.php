@extends('layouts.admin')

@section('title', 'Dashboard Kepala Unit - Halo MANAP')

@section('admin_content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Kepala Unit</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Kepala Unit</span>
            <span class="text-gray-400">/</span>
            <span>Dashboard</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 border-l-4 border-l-blue-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-inbox"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Disposisi Baru</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $baru }}</h3>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 border-l-4 border-l-yellow-400">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-yellow-50 text-yellow-500 flex items-center justify-center text-xl">
                <i class="fa-solid fa-spinner"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Dalam Proses</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $dalamProses }}</h3>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 border-l-4 border-l-green-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-50 text-green-500 flex items-center justify-center text-xl">
                <i class="fa-solid fa-check"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Selesai</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $selesai }}</h3>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 border-l-4 border-l-purple-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Rata-rata Respon</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $avgRespon }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h3 class="font-bold text-gray-800 mb-4">Disposisi Terbaru</h3>
    @if($latestWorkflows->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="pb-3 font-medium">Pengaduan</th>
                        <th class="pb-3 font-medium">Dari</th>
                        <th class="pb-3 font-medium">Status</th>
                        <th class="pb-3 font-medium">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($latestWorkflows as $wf)
                        <tr class="border-b last:border-0">
                            <td class="py-3">
                                <a href="{{ route('kepala-unit.dispositions.show', $wf->uuid) }}" class="text-blue-600 hover:underline">
                                    {{ $wf->ticket->judul ?? 'Tanpa Judul' }}
                                </a>
                                <div class="text-xs text-gray-400">{{ $wf->ticket->room->unit->nama ?? '-' }}</div>
                            </td>
                            <td class="py-3">{{ $wf->fromUser->nama ?? '-' }}</td>
                            <td class="py-3">{!! $wf->status_badge['label'] ?? $wf->status !!}</td>
                            <td class="py-3 text-gray-500">{{ $wf->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12 text-gray-400">
            <i class="fa-solid fa-inbox text-5xl mb-4"></i>
            <p>Belum ada disposisi masuk</p>
        </div>
    @endif
</div>
@endsection
