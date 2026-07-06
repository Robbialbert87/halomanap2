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

<div class="mb-4">
    <form method="GET" action="{{ route('admin.jabatans.index') }}">
        <div class="relative max-w-md">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode, nama, atau kategori..." autocomplete="off"
                class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg pl-10 pr-10 py-2.5 focus:ring-blue-500 focus:border-blue-500"
                oninput="clearTimeout(this.debounce); this.debounce = setTimeout(() => { this.form.submit(); }, 500);">
            @if(request('search'))
            <a href="{{ route('admin.jabatans.index') }}" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50 text-gray-800 font-semibold border-b border-gray-100 uppercase text-xs">
            <tr>
                <th class="px-6 py-4 w-12 text-center">No</th>
                <th class="px-6 py-4">Kode</th>
                <th class="px-6 py-4">Nama Jabatan</th>
                <th class="px-6 py-4 text-center">Kategori</th>
                <th class="px-6 py-4 text-center">Status</th>
                <th class="px-6 py-4 w-28 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($jabatans as $jabatan)
            @php
                $kategoriColors = [
                    'Direktur' => 'bg-purple-100 text-purple-700',
                    'Kabid' => 'bg-blue-100 text-blue-700',
                    'Kabag' => 'bg-blue-100 text-blue-700',
                    'Kasi' => 'bg-amber-100 text-amber-700',
                    'Kasubbag' => 'bg-amber-100 text-amber-700',
                    'Kepala Unit' => 'bg-green-100 text-green-700',
                    'Petugas' => 'bg-gray-100 text-gray-700',
                ];
            @endphp
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 text-center text-gray-400">{{ $jabatans->firstItem() + $loop->index }}</td>
                <td class="px-6 py-4 font-mono text-xs text-blue-600">{{ $jabatan->kode }}</td>
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-900">{{ $jabatan->nama }}</div>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $kategoriColors[$jabatan->kategori_jabatan] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ $jabatan->kategori_jabatan }}
                    </span>
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
                <td colspan="6" class="px-6 py-12 text-center">
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
