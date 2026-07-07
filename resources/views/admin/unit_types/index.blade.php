@extends('layouts.admin')

@section('title', 'Jenis Unit - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Jenis Unit</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Master Data</span>
            <span class="text-gray-400">/</span>
            <span>Jenis Unit</span>
        </div>
    </div>
    <a href="{{ route('admin.unit-types.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
        <i class="fa-solid fa-plus mr-1"></i> Tambah Jenis Unit
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
@endsection
