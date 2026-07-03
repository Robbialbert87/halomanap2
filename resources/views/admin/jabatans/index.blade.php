@extends('layouts.admin')

@section('title', 'Master Jabatan - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Master Jabatan</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Master Data</span>
            <span class="text-gray-400">/</span>
            <span>Jabatan</span>
        </div>
    </div>
    <a href="{{ route('admin.jabatans.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Jabatan
    </a>
</div>

@if(session('success'))
<div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-200 flex items-center gap-2">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-200 flex items-center gap-2">
    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
</div>
@endif

{{-- Level Guide --}}
<div class="grid grid-cols-4 gap-3 mb-6">
    @php
        $levels = [
            1 => ['label' => 'Level 1', 'title' => 'Direktur', 'color' => 'bg-purple-50 border-purple-200 text-purple-700', 'icon' => 'fa-crown'],
            2 => ['label' => 'Level 2', 'title' => 'Kabid / Kabag', 'color' => 'bg-blue-50 border-blue-200 text-blue-700', 'icon' => 'fa-user-tie'],
            3 => ['label' => 'Level 3', 'title' => 'Kasi / Kasubbag', 'color' => 'bg-amber-50 border-amber-200 text-amber-700', 'icon' => 'fa-user-gear'],
            4 => ['label' => 'Level 4', 'title' => 'Kepala Unit', 'color' => 'bg-green-50 border-green-200 text-green-700', 'icon' => 'fa-user'],
        ];
        $levelCounts = $jabatans->groupBy('level');
    @endphp
    @foreach($levels as $lv => $info)
    <div class="border rounded-xl p-4 {{ $info['color'] }} flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-white/60 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid {{ $info['icon'] }} text-sm"></i>
        </div>
        <div>
            <div class="text-xs font-medium opacity-75">{{ $info['label'] }}</div>
            <div class="text-sm font-bold">{{ $info['title'] }}</div>
            <div class="text-xs opacity-60">{{ $levelCounts->get($lv, collect())->count() }} jabatan</div>
        </div>
    </div>
    @endforeach
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50 text-gray-800 font-semibold border-b border-gray-100 uppercase text-xs">
            <tr>
                <th class="px-6 py-4 w-12 text-center">No</th>
                <th class="px-6 py-4">Kode</th>
                <th class="px-6 py-4">Nama Jabatan</th>
                <th class="px-6 py-4 text-center">Level</th>
                <th class="px-6 py-4">Parent</th>
                <th class="px-6 py-4 text-center">Status</th>
                <th class="px-6 py-4 w-28 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($jabatans as $jabatan)
            @php
                $levelColors = [
                    1 => 'bg-purple-100 text-purple-700',
                    2 => 'bg-blue-100 text-blue-700',
                    3 => 'bg-amber-100 text-amber-700',
                    4 => 'bg-green-100 text-green-700',
                ];
                $levelLabels = [
                    1 => 'Direktur',
                    2 => 'Kabid/Kabag',
                    3 => 'Kasi/Kasubbag',
                    4 => 'Kepala Unit',
                ];
            @endphp
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 text-center text-gray-400">{{ $jabatans->firstItem() + $loop->index }}</td>
                <td class="px-6 py-4 font-mono text-xs text-blue-600">{{ $jabatan->kode }}</td>
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-900">{{ $jabatan->nama }}</div>
                    @if($jabatan->keterangan)
                        <div class="text-xs text-gray-400 mt-0.5">{{ Str::limit($jabatan->keterangan, 60) }}</div>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $levelColors[$jabatan->level] ?? 'bg-gray-100 text-gray-700' }}">
                        L{{ $jabatan->level }} – {{ $levelLabels[$jabatan->level] ?? '-' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    @if($jabatan->parent)
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-arrow-turn-down-right text-[10px] text-gray-400"></i>
                            {{ $jabatan->parent->nama }}
                        </span>
                    @else
                        <span class="text-gray-300 text-xs">—</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    @if($jabatan->status === 'active')
                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Aktif
                        </span>
                    @else
                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            Nonaktif
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-1">
                        <a href="{{ route('admin.jabatans.edit', $jabatan->id) }}"
                           class="text-blue-600 hover:text-blue-800 p-1.5 rounded-md hover:bg-blue-50 transition-colors"
                           title="Edit">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.jabatans.destroy', $jabatan->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus jabatan {{ $jabatan->nama }}?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 p-1.5 rounded-md hover:bg-red-50 transition-colors" title="Hapus">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center gap-2 text-gray-400">
                        <i class="fa-solid fa-sitemap text-3xl"></i>
                        <p>Belum ada data jabatan.</p>
                        <a href="{{ route('admin.jabatans.create') }}" class="text-blue-600 hover:underline text-sm">Tambah sekarang</a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($jabatans->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $jabatans->links() }}
    </div>
    @endif
</div>
@endsection
