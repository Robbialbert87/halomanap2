@extends('layouts.admin')

@section('title', 'Manajemen Kategori - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Kategori</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Master Data</span> 
            <span class="text-gray-400">/</span> 
            <span>Kategori</span>
        </div>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
        <i class="fa-solid fa-plus mr-1"></i> Tambah Kategori
    </a>
</div>

@if(session('success'))
<div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-200">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50 text-gray-800 font-semibold border-b border-gray-100 uppercase text-xs">
            <tr>
                <th class="px-6 py-4 w-16 text-center">No</th>
                <th class="px-6 py-4">Nama Kategori</th>
                <th class="px-6 py-4 w-32 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($categories as $index => $category)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                <td class="px-6 py-4 font-medium text-gray-900">{{ $category->name }}</td>
                <td class="px-6 py-4 text-center flex justify-center gap-2">
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-blue-600 hover:text-blue-800 p-1.5 rounded-md hover:bg-blue-50 transition-colors">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </a>
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 p-1.5 rounded-md hover:bg-red-50 transition-colors">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-6 py-8 text-center text-gray-400">Belum ada data kategori.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
