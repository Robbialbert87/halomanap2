@extends('layouts.admin')

@section('title', 'Master Unit - Halo MANAP')

@section('admin_content')

<x-admin.page-header
    title="Master Unit"
    section="Master Data"
    crumb="Unit"
    icon="fa-building"
    gradient="blue"
>
    <x-admin.btn href="{{ route('admin.units.create') }}" icon="fa-plus">Tambah Unit</x-admin.btn>
</x-admin.page-header>

<x-admin.flash />

<x-admin.search-toolbar
    action="{{ route('admin.units.index') }}"
    live-target="#units-results"
    placeholder="Cari kode atau nama unit..."
    :search="request('search')"
    :grid="true"
>
    <select name="jenis" class="admin-input text-[13px]">
        <option value="">Semua Jenis</option>
        @foreach($jenisList as $j)
            <option value="{{ $j }}" {{ request('jenis') == $j ? 'selected' : '' }}>{{ $j }}</option>
        @endforeach
    </select>
</x-admin.search-toolbar>

{{-- Hasil: mobile list + tabel desktop + pagination (di-refresh real-time oleh live filter) --}}
<div id="units-results" class="admin-live-wrap">
        @fragment('results')
{{-- Info ringkas hasil filter (desktop) --}}
<div class="hidden md:flex items-center justify-between px-5 py-2.5 border-b border-gray-100 bg-gray-50/40">
    <p class="text-[11px] text-gray-500">
        Menampilkan <span class="font-semibold text-gray-700">{{ $units->firstItem() ?? 0 }}–{{ $units->lastItem() ?? 0 }}</span>
        dari <span class="font-semibold text-gray-700">{{ $units->total() }}</span> unit
    </p>
</div>

{{-- Mobile: Unit List --}}
<div class="block md:hidden mb-4">
    <div class="admin-card overflow-hidden divide-y divide-gray-100">
    @forelse($units as $unit)
    <div class="flex items-stretch active:bg-gray-50 transition-colors">
        <div class="w-1 shrink-0 bg-blue-500"></div>
        <div class="flex-1 min-w-0 pl-2.5 pr-3 py-2.5">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-1.5 min-w-0 flex-1">
                    <span class="text-[13px] font-semibold text-gray-900">{{ $unit->nama }}</span>
                    @if($unit->status === 'active')
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 shrink-0"></span>
                    @else
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 shrink-0"></span>
                    @endif
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <a href="{{ route('admin.units.edit', $unit->id) }}"
                       class="admin-action admin-action-sm admin-action-blue" title="Edit">
                        <i class="fa-regular fa-pen-to-square text-[10px]"></i>
                    </a>
                    <form action="{{ route('admin.units.destroy', $unit->id) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus unit {{ $unit->nama }}?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="admin-action admin-action-sm admin-action-red" title="Hapus">
                            <i class="fa-regular fa-trash-can text-[10px]"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-[11px] font-mono text-blue-600">{{ $unit->kode }}</span>
                <span class="text-[10px] text-gray-300">·</span>
                <span class="inline-block px-1.5 py-0.5 text-[10px] font-semibold rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">{{ $unit->jenis }}</span>
                @if($unit->status === 'active')
                    <span class="inline-block px-1.5 py-0.5 text-[10px] font-semibold rounded-full bg-green-50 text-green-700">Aktif</span>
                @else
                    <span class="inline-block px-1.5 py-0.5 text-[10px] font-semibold rounded-full bg-gray-100 text-gray-700">Nonaktif</span>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-10 text-gray-400">
        <i class="fa-regular fa-building text-3xl mb-2 block"></i>
        <span class="text-sm">Belum ada data unit atau pencarian tidak ditemukan.</span>
    </div>
    @endforelse
    </div>
</div>

{{-- Table (Desktop) --}}
<div class="hidden md:block admin-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="admin-table w-full text-left text-sm text-gray-600">
            <thead>
                <tr>
                    <th class="px-6 py-4 w-12 text-center">No</th>
                    <th class="px-6 py-4">Kode</th>
                    <th class="px-6 py-4">Nama Unit</th>
                    <th class="px-6 py-4 text-center hidden lg:table-cell">Jenis</th>
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
                    </td>
                    <td class="px-6 py-4 text-center hidden lg:table-cell">
                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                            {{ $unit->jenis }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($unit->status === 'active')
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('admin.units.edit', $unit->id) }}"
                               class="admin-action-pill admin-action-pill-blue"
                               title="Edit">
                                <i class="fa-regular fa-pen-to-square text-[11px]"></i> Edit
                            </a>
                            <form action="{{ route('admin.units.destroy', $unit->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus unit {{ $unit->nama }}?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="admin-action-pill admin-action-pill-red" title="Hapus">
                                    <i class="fa-regular fa-trash-can text-[11px]"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
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
    </div>

    @if($units->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $units->links() }}
    </div>
    @endif
</div>

{{-- Mobile Pagination --}}
@if($units->hasPages())
<div class="block md:hidden mt-4">
    {{ $units->links() }}
</div>
@endif

    @endfragment
</div>

@endsection
