@extends('layouts.admin')

@section('title', 'Manajemen Kategori - Halo MANAP')

@section('admin_content')

<x-admin.page-header
    title="Manajemen Kategori"
    section="Master Data"
    crumb="Kategori"
    icon="fa-tags"
    gradient="pink"
>
    <x-admin.btn href="{{ route('admin.categories.create') }}" icon="fa-plus">Tambah Kategori</x-admin.btn>
</x-admin.page-header>

<x-admin.flash />

{{-- Mobile: Category List --}}
<div class="block md:hidden mb-4">
    <div class="admin-card overflow-hidden divide-y divide-gray-100">
    @forelse($categories as $category)
    <div class="flex items-stretch active:bg-gray-50 transition-colors">
        <div class="w-1 shrink-0 bg-pink-500"></div>
        <div class="flex-1 min-w-0 pl-2.5 pr-3 py-3">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2 min-w-0 flex-1">
                    <span class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                        <i class="fa-solid {{ $category->icon ?? 'fa-tag' }} text-[10px] text-blue-600"></i>
                    </span>
                    <span class="text-[13px] font-semibold text-gray-900 truncate">{{ $category->name }}</span>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <x-admin.action href="{{ route('admin.categories.edit', $category->id) }}" variant="blue" icon="fa-regular fa-pen-to-square" class="admin-action-sm" title="Edit" />
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')" class="inline">
                        @csrf @method('DELETE')
                        <x-admin.action type="submit" variant="red" icon="fa-regular fa-trash-can" class="admin-action-sm" title="Hapus" />
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-10 text-gray-400">
        <i class="fa-solid fa-tags text-3xl mb-2 block"></i>
        <span class="text-sm">Belum ada data kategori.</span>
    </div>
    @endforelse
    </div>
</div>

{{-- Table (Desktop) --}}
<div class="hidden md:block admin-card overflow-hidden">
    <table class="admin-table w-full text-left text-sm text-gray-600">
        <thead>
            <tr>
                <th class="px-6 py-4 w-16 text-center">No</th>
                <th class="px-6 py-4">Ikon</th>
                <th class="px-6 py-4">Nama Kategori</th>
                <th class="px-6 py-4 w-32 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($categories as $index => $category)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                <td class="px-6 py-4">
                    <span class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="fa-solid {{ $category->icon ?? 'fa-tag' }} text-blue-600"></i>
                    </span>
                </td>
                <td class="px-6 py-4 font-medium text-gray-900">{{ $category->name }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-1.5">
                        <x-admin.pill href="{{ route('admin.categories.edit', $category->id) }}" variant="blue" icon="fa-regular fa-pen-to-square" title="Edit">Edit</x-admin.pill>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');" class="inline">
                            @csrf @method('DELETE')
                            <x-admin.pill type="submit" variant="red" icon="fa-regular fa-trash-can" title="Hapus">Hapus</x-admin.pill>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-8 text-center text-gray-400">Belum ada data kategori.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
