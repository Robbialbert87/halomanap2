@extends('layouts.admin')

@section('title', 'Jenis Unit - Halo MANAP')

@section('admin_content')

{{-- Mobile Page Header (PayApp style) --}}
<div class="md:hidden mb-3">
    <div class="flex items-center gap-2.5 p-1">
        <span class="w-9 h-9 rounded-2xl bg-gradient-to-br from-cyan-400 to-cyan-600 flex items-center justify-center shadow-sm shadow-cyan-200/50 flex-shrink-0">
            <i class="fa-solid fa-tag text-white text-sm"></i>
        </span>
        <div>
            <p class="text-[9px] text-cyan-500 font-semibold tracking-wider uppercase font-heading">Master Data</p>
            <h1 class="text-base font-bold text-gray-800 font-heading">Jenis Unit</h1>
        </div>
    </div>
</div>

{{-- Page Header (Desktop) --}}
<div class="hidden md:flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-heading">Jenis Unit</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Master Data</span>
            <span class="text-gray-400">/</span>
            <span>Jenis Unit</span>
        </div>
    </div>
    <a href="{{ route('admin.unit-types.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Jenis Unit
    </a>
</div>

{{-- Flash Messages --}}
@if(session('success'))
<div class="md:hidden bg-green-50 text-green-700 p-3 rounded-lg mb-4 border border-green-200 flex items-center gap-2 text-[13px]">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
<div class="hidden md:flex bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-200 items-center gap-2">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="md:hidden bg-red-50 text-red-700 p-3 rounded-lg mb-4 border border-red-200 flex items-center gap-2 text-[13px]">
    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
</div>
<div class="hidden md:flex bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-200 items-center gap-2">
    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
</div>
@endif

{{-- Mobile: Jenis Unit List --}}
<div class="block md:hidden mb-4">
    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-white/30 divide-y divide-gray-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
    @forelse($unitTypes as $unitType)
    <div class="flex items-stretch active:bg-gray-50 transition-colors">
        <div class="w-1 shrink-0 bg-cyan-500"></div>
        <div class="flex-1 min-w-0 pl-2.5 pr-3 py-2.5">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2 min-w-0 flex-1">
                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background: {{ $unitType->color ?? '#6b7280' }}"></span>
                    <span class="text-[13px] font-semibold text-gray-900 truncate">{{ $unitType->name }}</span>
                    @if($unitType->is_active)
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 shrink-0"></span>
                    @else
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 shrink-0"></span>
                    @endif
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <a href="{{ route('admin.unit-types.edit', $unitType->id) }}"
                       class="text-gray-300 hover:text-blue-500 transition-colors p-1" title="Edit">
                        <i class="fa-regular fa-pen-to-square text-[10px]"></i>
                    </a>
                    <form action="{{ route('admin.unit-types.destroy', $unitType->id) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus jenis unit {{ $unitType->name }}?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors p-1" title="Hapus">
                            <i class="fa-regular fa-trash-can text-[10px]"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-[11px] text-gray-500">{{ $unitType->units()->count() }} unit</span>
                <span class="text-[10px] text-gray-300">·</span>
                <code class="text-[10px] font-mono bg-gray-100 px-1.5 py-0.5 rounded">{{ $unitType->color ?? '-' }}</code>
                @if($unitType->is_active)
                    <span class="inline-block px-1.5 py-0.5 text-[10px] font-semibold rounded-full bg-green-50 text-green-700">Aktif</span>
                @else
                    <span class="inline-block px-1.5 py-0.5 text-[10px] font-semibold rounded-full bg-gray-100 text-gray-700">Nonaktif</span>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-10 text-gray-400">
        <i class="fa-solid fa-tag text-3xl mb-2 block"></i>
        <span class="text-sm">Belum ada data jenis unit.</span>
    </div>
    @endforelse
    </div>
</div>

{{-- Table (Desktop) --}}
<div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50 text-gray-800 font-semibold border-b border-gray-100 uppercase text-xs">
            <tr>
                <th class="px-6 py-4 w-12 text-center">No</th>
                <th class="px-6 py-4">Nama Jenis</th>
                <th class="px-6 py-4 text-center">Warna</th>
                <th class="px-6 py-4 text-center">Jumlah Unit</th>
                <th class="px-6 py-4 text-center">Status</th>
                <th class="px-6 py-4 w-28 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($unitTypes as $unitType)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 text-center text-gray-400">{{ $unitTypes->firstItem() + $loop->index }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full" style="background: {{ $unitType->color ?? '#6b7280' }}"></span>
                        <span class="font-medium text-gray-900">{{ $unitType->name }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    <code class="text-xs font-mono bg-gray-100 px-2 py-0.5 rounded">{{ $unitType->color ?? '-' }}</code>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="font-semibold text-gray-700">{{ $unitType->units()->count() }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    @if($unitType->is_active)
                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                    @else
                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-1">
                        <a href="{{ route('admin.unit-types.edit', $unitType->id) }}"
                           class="text-blue-600 hover:text-blue-800 p-1.5 rounded-md hover:bg-blue-50 transition-colors" title="Edit">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.unit-types.destroy', $unitType->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus jenis unit {{ $unitType->name }}?')" class="inline">
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
                <td colspan="6" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center gap-2 text-gray-400">
                        <i class="fa-solid fa-tag text-3xl"></i>
                        <p>Belum ada data jenis unit.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($unitTypes->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $unitTypes->links() }}
    </div>
    @endif
</div>

{{-- Mobile Pagination --}}
@if($unitTypes->hasPages())
<div class="block md:hidden mt-4">
    {{ $unitTypes->links() }}
</div>
@endif
@endsection
