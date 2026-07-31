@extends('layouts.admin')

@section('title', 'Jenis Unit - Halo MANAP')

@section('admin_content')

<x-admin.page-header
    title="Jenis Unit"
    section="Master Data"
    crumb="Jenis Unit"
    icon="fa-tag"
    gradient="cyan"
>
    <x-admin.btn href="{{ route('admin.unit-types.create') }}" icon="fa-plus">Tambah Jenis Unit</x-admin.btn>
</x-admin.page-header>

<x-admin.flash />

{{-- Mobile: Jenis Unit List --}}
<div class="block md:hidden mb-4">
    <div class="admin-card overflow-hidden divide-y divide-gray-100">
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
                    <x-admin.action href="{{ route('admin.unit-types.edit', $unitType->id) }}" variant="blue" icon="fa-regular fa-pen-to-square" class="admin-action-sm" title="Edit" />
                    <form action="{{ route('admin.unit-types.destroy', $unitType->id) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus jenis unit {{ $unitType->name }}?')" class="inline">
                        @csrf @method('DELETE')
                        <x-admin.action type="submit" variant="red" icon="fa-regular fa-trash-can" class="admin-action-sm" title="Hapus" />
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
<div class="hidden md:block admin-card overflow-hidden">
    <table class="admin-table w-full text-left text-sm text-gray-600">
        <thead>
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
                    <div class="flex items-center justify-center gap-1.5">
                        <x-admin.pill href="{{ route('admin.unit-types.edit', $unitType->id) }}" variant="blue" icon="fa-regular fa-pen-to-square" title="Edit">Edit</x-admin.pill>
                        <form action="{{ route('admin.unit-types.destroy', $unitType->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus jenis unit {{ $unitType->name }}?')" class="inline">
                            @csrf @method('DELETE')
                            <x-admin.pill type="submit" variant="red" icon="fa-regular fa-trash-can" title="Hapus">Hapus</x-admin.pill>
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
