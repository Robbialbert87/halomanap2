@extends('layouts.admin')

@section('title', 'Manajemen Role - Halo MANAP')

@section('admin_content')

<x-admin.page-header
    title="Manajemen Role"
    section="Master Data"
    crumb="Role"
    icon="fa-user-shield"
    gradient="purple"
>
    <x-admin.btn href="{{ route('admin.roles.create') }}" icon="fa-plus">Tambah Role</x-admin.btn>
</x-admin.page-header>

<x-admin.flash />

<x-admin.search-toolbar
    action="{{ route('admin.roles.index') }}"
    live-target="#roles-results"
    placeholder="Cari nama, kode, atau deskripsi role..."
    :search="request('search')"
/>

{{-- Hasil: card ringkasan + tabel detail (di-refresh real-time oleh live filter) --}}
<div id="roles-results" class="admin-live-wrap">
        @fragment('results')
{{-- Roles Table (semua breakpoint — mobile scroll horizontal, desktop lega) --}}
<x-admin.card title="Daftar Semua Role">
    <table class="admin-table w-full text-left text-sm text-gray-600">
        <thead>
            <tr>
                <th class="px-4 md:px-6 py-4">Role</th>
                <th class="px-4 md:px-6 py-4 hidden sm:table-cell">Deskripsi</th>
                <th class="px-4 md:px-6 py-4 text-center">User</th>
                <th class="px-4 md:px-6 py-4 hidden sm:table-cell">Status</th>
                <th class="px-4 md:px-6 py-4 hidden md:table-cell">Dibuat</th>
                <th class="px-4 md:px-6 py-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($roles as $role)
            @php
                $colors = [
                    'Super Admin' => ['badge' => 'bg-purple-50 text-purple-700'],
                    'Admin'       => ['badge' => 'bg-blue-50 text-blue-700'],
                    'Kepala Unit' => ['badge' => 'bg-amber-50 text-amber-700'],
                    'Staff Unit'  => ['badge' => 'bg-green-50 text-green-700'],
                ];
                $c = $colors[$role->name] ?? ['badge' => 'bg-gray-100 text-gray-700'];
            @endphp
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-4 md:px-6 py-4 align-top">
                    <p class="font-medium text-gray-900 whitespace-nowrap">{{ $role->name }}</p>
                    <p class="font-mono text-[11px] text-blue-600 mt-0.5">{{ $role->kode ?? '-' }}</p>
                </td>
                <td class="px-4 md:px-6 py-4 align-top max-w-[320px] hidden sm:table-cell">
                    <p class="text-xs text-gray-500 leading-snug">{{ $role->deskripsi ?? '-' }}</p>
                </td>
                <td class="px-4 md:px-6 py-4 text-center align-top">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $c['badge'] }}">
                        <i class="fa-solid fa-users text-[10px]"></i> {{ $role->users_count }}
                    </span>
                </td>
                <td class="px-4 md:px-6 py-4 align-top hidden sm:table-cell">
                    @if($role->status === 'active')
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-green-700">
                            <i class="fa-solid fa-circle text-[8px] text-green-500"></i> Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-400">
                            <i class="fa-solid fa-circle text-[8px] text-gray-300"></i> Nonaktif
                        </span>
                    @endif
                </td>
                <td class="px-4 md:px-6 py-4 text-gray-400 text-xs align-top hidden md:table-cell">
                    {{ $role->created_at->format('d M Y') }}
                </td>
                <td class="px-4 md:px-6 py-4 align-top">
                    <div class="flex items-center justify-end gap-1.5 flex-wrap">
                        <x-admin.pill href="{{ route('admin.roles.edit', $role->id) }}" variant="blue" icon="fa-regular fa-pen-to-square" title="Edit">Edit</x-admin.pill>
                        <x-admin.pill href="{{ route('admin.roles.edit', $role->id) }}#permissions" variant="slate" icon="fa-solid fa-key" class="hidden md:inline-flex" title="Atur Permission">Permission</x-admin.pill>
                        @if($role->name !== 'Super Admin')
                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus role {{ $role->name }}?')" class="inline">
                            @csrf @method('DELETE')
                            <x-admin.pill type="submit" variant="red" icon="fa-regular fa-trash-can" title="Hapus">Hapus</x-admin.pill>
                        </form>
                        @else
                        <x-admin.action variant="slate" icon="fa-solid fa-lock" class="cursor-default" title="Dilindungi" />
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 md:px-6 py-12 text-center text-gray-400">
                    <i class="fa-solid fa-shield-halved text-3xl mb-2 block"></i>
                    <span class="text-sm">Tidak ada role yang cocok dengan pencarian.</span>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</x-admin.card>

    @endfragment
</div>
@endsection
