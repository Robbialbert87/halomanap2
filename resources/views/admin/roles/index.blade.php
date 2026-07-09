@extends('layouts.admin')

@section('title', 'Manajemen Role - Halo MANAP')

@section('admin_content')

{{-- Mobile Page Header (PayApp style) --}}
<div class="md:hidden mb-3">
    <div class="flex items-center gap-2.5 p-1">
        <span class="w-9 h-9 rounded-2xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-sm shadow-purple-200/50 flex-shrink-0">
            <i class="fa-solid fa-user-shield text-white text-sm"></i>
        </span>
        <div>
            <p class="text-[9px] text-purple-500 font-semibold tracking-wider uppercase font-heading">Master Data</p>
            <h1 class="text-base font-bold text-gray-800 font-heading">Manajemen Role</h1>
        </div>
    </div>
</div>

{{-- Page Header (Desktop) --}}
<div class="hidden md:flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-heading">Manajemen Role</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Master Data</span>
            <span class="text-gray-400">/</span>
            <span>Role</span>
        </div>
    </div>
    <a href="{{ route('admin.roles.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Role
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

{{-- Role cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4 mb-4 md:mb-6">
    @foreach($roles as $role)
    @php
        $colors = [
            'Super Admin' => ['bg' => 'bg-purple-50', 'border' => 'border-purple-200', 'icon' => 'bg-purple-600', 'badge' => 'bg-purple-100 text-purple-700'],
            'Admin'       => ['bg' => 'bg-blue-50',   'border' => 'border-blue-200',   'icon' => 'bg-blue-600',   'badge' => 'bg-blue-100 text-blue-700'],
            'Kepala Unit' => ['bg' => 'bg-amber-50',  'border' => 'border-amber-200',  'icon' => 'bg-amber-500',  'badge' => 'bg-amber-100 text-amber-700'],
            'Staff Unit'  => ['bg' => 'bg-green-50',  'border' => 'border-green-200',  'icon' => 'bg-green-600',  'badge' => 'bg-green-100 text-green-700'],
        ];
        $c = $colors[$role->name] ?? ['bg' => 'bg-gray-50', 'border' => 'border-gray-200', 'icon' => 'bg-gray-500', 'badge' => 'bg-gray-100 text-gray-700'];
    @endphp
    <div class="{{ $c['bg'] }} {{ $c['border'] }} border rounded-xl p-4 md:p-5 relative overflow-hidden" style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.5) 100%);">
        <div class="flex items-start justify-between mb-3 md:mb-4">
            <div class="w-9 h-9 md:w-10 md:h-10 {{ $c['icon'] }} rounded-xl flex items-center justify-center text-white shadow-sm">
                @if($role->name === 'Super Admin')
                    <i class="fa-solid fa-user-shield text-sm"></i>
                @elseif($role->name === 'Admin')
                    <i class="fa-solid fa-user-gear text-sm"></i>
                @elseif($role->name === 'Kepala Unit')
                    <i class="fa-solid fa-user-tie text-sm"></i>
                @else
                    <i class="fa-solid fa-user text-sm"></i>
                @endif
            </div>
            <div class="flex items-center gap-1">
                <a href="{{ route('admin.roles.edit', $role->id) }}"
                   class="p-1.5 rounded-md text-gray-500 hover:bg-white hover:text-blue-600 transition-colors" title="Edit">
                    <i class="fa-regular fa-pen-to-square text-sm"></i>
                </a>
                @if($role->name !== 'Super Admin')
                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus role {{ $role->name }}?')" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-1.5 rounded-md text-gray-500 hover:bg-white hover:text-red-600 transition-colors" title="Hapus">
                        <i class="fa-regular fa-trash-can text-sm"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
        <h3 class="font-bold text-gray-800 text-sm md:text-base mb-1 font-heading">{{ $role->name }}</h3>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 md:px-2.5 md:py-0.5 rounded-full text-[11px] md:text-xs font-semibold {{ $c['badge'] }}">
                <i class="fa-solid fa-users text-[10px]"></i>
                {{ $role->users_count }} user
            </span>
            @if($role->name === 'Super Admin')
            <span class="inline-flex items-center gap-1 px-2 py-0.5 md:px-2.5 md:py-0.5 rounded-full text-[11px] md:text-xs font-semibold bg-purple-200 text-purple-800">
                <i class="fa-solid fa-lock text-[10px]"></i> Dilindungi
            </span>
            @endif
        </div>
    </div>
    @endforeach
</div>

{{-- Roles Table (Desktop only) --}}
<div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800 text-sm">Daftar Semua Role</h2>
    </div>
    <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50 text-gray-800 font-semibold border-b border-gray-100 uppercase text-xs">
            <tr>
                <th class="px-6 py-4 w-12 text-center">No</th>
                <th class="px-6 py-4">Kode Role</th>
                <th class="px-6 py-4">Nama Role</th>
                <th class="px-6 py-4">Deskripsi</th>
                <th class="px-6 py-4 text-center">Status</th>
                <th class="px-6 py-4 text-center">Dibuat</th>
                <th class="px-6 py-4 w-28 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($roles as $index => $role)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 text-center text-gray-400">{{ $index + 1 }}</td>
                <td class="px-6 py-4 font-mono text-sm text-blue-600">{{ $role->kode ?? '-' }}</td>
                <td class="px-6 py-4 font-medium text-gray-900">
                    {{ $role->name }}
                    <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                        <i class="fa-solid fa-users text-[10px]"></i> {{ $role->users_count }} user
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $role->deskripsi ?? '-' }}</td>
                <td class="px-6 py-4 text-center">
                    @if($role->status === 'active')
                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                    @else
                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-center text-gray-400 text-xs">
                    {{ $role->created_at->format('d M Y') }}
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-1">
                        <a href="{{ route('admin.roles.edit', $role->id) }}"
                           class="text-blue-600 hover:text-blue-800 p-1.5 rounded-md hover:bg-blue-50 transition-colors">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                        @if($role->name !== 'Super Admin')
                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus role ini?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 p-1.5 rounded-md hover:bg-red-50 transition-colors">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                        @else
                        <span class="text-gray-300 p-1.5"><i class="fa-solid fa-lock text-xs"></i></span>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
