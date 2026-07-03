@extends('layouts.admin')

@section('title', 'Master Unit - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Master Unit</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Master Data</span>
            <span class="text-gray-400">/</span>
            <span>Unit</span>
        </div>
    </div>
    <a href="{{ route('admin.units.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Unit
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

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    {{-- Filter & Search --}}
    <div class="p-4 border-b border-gray-100 bg-gray-50/50">
        <form action="{{ route('admin.units.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama unit..." 
                        class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                </div>
            </div>
            <div class="w-full sm:w-48">
                <select name="jenis" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white" onchange="this.form.submit()">
                    <option value="">-- Semua Jenis --</option>
                    @foreach($jenisList as $j)
                        <option value="{{ $j }}" {{ request('jenis') == $j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                </select>
            </div>
            @if(request('search') || request('jenis'))
            <a href="{{ route('admin.units.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded-lg transition-colors flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-xmark"></i> Reset
            </a>
            @endif
        </form>
    </div>

    <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50 text-gray-800 font-semibold border-b border-gray-100 uppercase text-xs">
            <tr>
                <th class="px-6 py-4 w-12 text-center">No</th>
                <th class="px-6 py-4">Kode</th>
                <th class="px-6 py-4">Nama Unit</th>
                <th class="px-6 py-4 text-center">Jenis</th>
                <th class="px-6 py-4">Parent Unit</th>
                <th class="px-6 py-4 text-center">Status</th>
                <th class="px-6 py-4 w-28 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($units as $unit)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 text-center text-gray-400">{{ $units->firstItem() + $loop->index }}</td>
                <td class="px-6 py-4 font-mono text-xs text-blue-600">{{ $unit->kode }}</td>
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-900">{{ $unit->nama }}</div>
                    @if($unit->keterangan)
                        <div class="text-xs text-gray-400 mt-0.5">{{ Str::limit($unit->keterangan, 50) }}</div>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                        {{ $unit->jenis }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    @if($unit->parent)
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-arrow-turn-down-right text-[10px] text-gray-400"></i>
                            {{ $unit->parent->nama }}
                        </span>
                    @else
                        <span class="text-gray-300 text-xs">—</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    @if($unit->status === 'active')
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
                        <a href="{{ route('admin.units.edit', $unit->id) }}"
                           class="text-blue-600 hover:text-blue-800 p-1.5 rounded-md hover:bg-blue-50 transition-colors"
                           title="Edit">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.units.destroy', $unit->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus unit {{ $unit->nama }}?')" class="inline">
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
                        <i class="fa-regular fa-building text-3xl"></i>
                        <p>Belum ada data unit atau pencarian tidak ditemukan.</p>
                        @if(!request('search') && !request('jenis'))
                        <a href="{{ route('admin.units.create') }}" class="text-blue-600 hover:underline text-sm">Tambah Unit Pertama</a>
                        @endif
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($units->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $units->links() }}
    </div>
    @endif
</div>
@endsection
