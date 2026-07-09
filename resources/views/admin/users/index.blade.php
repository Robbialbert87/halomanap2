@extends('layouts.admin')

@section('title', 'Master Pengguna - Halo MANAP')

@section('admin_content')

{{-- Mobile Page Header (PayApp style) --}}
<div class="md:hidden mb-3">
    <div class="flex items-center gap-2.5 p-1">
        <span class="w-9 h-9 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-sm shadow-emerald-200/50 flex-shrink-0">
            <i class="fa-solid fa-users text-white text-sm"></i>
        </span>
        <div>
            <p class="text-[9px] text-emerald-500 font-semibold tracking-wider uppercase font-heading">Master Data</p>
            <h1 class="text-base font-bold text-gray-800 font-heading">Master Pengguna</h1>
        </div>
    </div>
</div>

{{-- Page Header (Desktop) --}}
<div class="hidden md:flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-heading">Master Pengguna</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Master Data</span>
            <span class="text-gray-400">/</span>
            <span>Pengguna</span>
        </div>
    </div>
    <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Pengguna
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

{{-- Mobile Filter Sticky Wrapper --}}
<div class="md:hidden sticky top-0 z-30 bg-[#F3F4F6] pt-1 pb-1 -mx-4 px-4">
    <button id="mobile-filter-toggle" type="button" class="w-full bg-white/70 backdrop-blur-xl rounded-2xl border border-white/30 p-2.5 flex items-center justify-between text-sm text-gray-700 font-medium mb-2.5 active:scale-[0.98] transition-all shadow-sm" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
        <span class="flex items-center gap-2">
            <span class="w-6 h-6 rounded-lg bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center">
                <i class="fa-solid fa-sliders text-white text-[10px]"></i>
            </span>
            <span class="font-heading font-semibold text-[13px]">Filter & Pencarian</span>
        </span>
        <i id="mobile-filter-icon" class="fa-solid fa-chevron-down text-gray-400 transition-transform duration-300 text-xs"></i>
    </button>

    <div id="filter-container" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3 mb-3" style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.6) 100%);">
    <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col gap-2.5">
        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari NIP, Nama, atau No WA..." autocomplete="off"
                class="w-full bg-white/70 border border-white/50 text-gray-900 text-[13px] rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5 pl-9">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            @if(request('search'))
            <a href="{{ route('admin.users.index', request()->except(['search', 'page'])) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </a>
            @endif
        </div>
        <div class="grid grid-cols-2 gap-2">
            <select name="unit_id" class="w-full bg-white/70 border border-white/50 text-gray-900 text-[13px] rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5">
                <option value="">Semua Unit</option>
                @foreach($units as $u)
                    <option value="{{ $u->id }}" {{ request('unit_id') == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                @endforeach
            </select>
            <select name="jabatan_id" class="w-full bg-white/70 border border-white/50 text-gray-900 text-[13px] rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5">
                <option value="">Semua Jabatan</option>
                @foreach($jabatans as $j)
                    <option value="{{ $j->id }}" {{ request('jabatan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama }}</option>
                @endforeach
            </select>
            <select name="role" class="w-full bg-white/70 border border-white/50 text-gray-900 text-[13px] rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5">
                <option value="">Semua Role</option>
                @foreach($roles as $r)
                    <option value="{{ $r->name }}" {{ request('role') == $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-gradient-to-br from-emerald-500 to-emerald-700 hover:from-emerald-600 hover:to-emerald-800 text-white px-4 py-2.5 rounded-xl text-[13px] font-semibold transition-all shadow-sm shadow-emerald-200/50 active:scale-[0.98]">
                    <i class="fa-solid fa-search mr-1 text-xs"></i> Filter
                </button>
                <a href="{{ route('admin.users.index') }}" class="flex-1 bg-gradient-to-br from-gray-100 to-white border border-gray-200 hover:from-gray-200 hover:to-gray-100 text-gray-700 px-4 py-2.5 rounded-xl text-[13px] font-semibold transition-all active:scale-[0.98] text-center">
                    Reset
                </a>
            </div>
        </div>
    </form>
    </div>
</div>

{{-- Desktop Filter --}}
<div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="p-4 border-b border-gray-100 bg-gray-50/50">
        <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-3">
            <div class="lg:col-span-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIP, Nama, atau No WA..." autocomplete="off"
                        class="w-full pl-9 pr-10 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    @if(request('search'))
                    <a href="{{ route('admin.users.index', request()->except(['search', 'page'])) }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                    @endif
                </div>
            </div>
            <div>
                <select name="unit_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white">
                    <option value="">Semua Unit</option>
                    @foreach($units as $u)
                        <option value="{{ $u->id }}" {{ request('unit_id') == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="jabatan_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white">
                    <option value="">Semua Jabatan</option>
                    @foreach($jabatans as $j)
                        <option value="{{ $j->id }}" {{ request('jabatan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <select name="role" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white">
                    <option value="">Semua Role</option>
                    @foreach($roles as $r)
                        <option value="{{ $r->name }}" {{ request('role') == $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                </select>
                @if(request('search') || request('unit_id') || request('jabatan_id') || request('role'))
                <a href="{{ route('admin.users.index') }}" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded-lg transition-colors flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </a>
                @endif
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg"><i class="fa-solid fa-filter mr-1"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

{{-- Mobile: User List --}}
<div class="block md:hidden mb-4">
    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-white/30 divide-y divide-gray-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
    @forelse($users as $user)
    <div class="flex items-stretch active:bg-gray-50 transition-colors">
        <div class="w-1 shrink-0 bg-emerald-500"></div>
        <div class="flex-1 min-w-0 pl-2.5 pr-3 py-2.5">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-1.5 min-w-0 flex-1">
                    <span class="text-[13px] font-semibold text-gray-900 truncate">{{ $user->nama }}</span>
                    @if($user->status === 'active')
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 shrink-0"></span>
                    @elseif($user->status === 'suspended')
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0"></span>
                    @else
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 shrink-0"></span>
                    @endif
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-gray-300 hover:text-blue-500 transition-colors p-1">
                        <i class="fa-regular fa-pen-to-square text-[10px]"></i>
                    </a>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors p-1">
                            <i class="fa-regular fa-trash-can text-[10px]"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="text-[11px] font-mono text-blue-600 mt-0.5">{{ $user->nip }}</div>
            <div class="flex items-center gap-2 mt-0.5">
                <span class="text-[11px] text-gray-500 truncate flex items-center gap-1">
                    <i class="fa-regular fa-building text-gray-400"></i>
                    {{ $user->unit ? $user->unit->nama : '-' }}
                </span>
                <span class="text-[11px] text-gray-500 truncate">
                    · {{ $user->jabatan ? $user->jabatan->nama : '-' }}
                </span>
            </div>
            @if($user->roles->count())
            <div class="flex items-center gap-1 mt-1">
                @foreach($user->roles as $r)
                    <span class="px-1.5 py-0.5 text-[9px] font-semibold bg-indigo-50 text-indigo-700 rounded border border-indigo-100">{{ $r->name }}</span>
                @endforeach
                @if($user->status === 'active')
                    <span class="px-1.5 py-0.5 text-[9px] font-semibold bg-green-50 text-green-700 rounded">Aktif</span>
                @elseif($user->status === 'suspended')
                    <span class="px-1.5 py-0.5 text-[9px] font-semibold bg-red-50 text-red-700 rounded">Suspended</span>
                @else
                    <span class="px-1.5 py-0.5 text-[9px] font-semibold bg-gray-50 text-gray-700 rounded">Nonaktif</span>
                @endif
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="text-center py-10 text-gray-400">
        <i class="fa-solid fa-users text-3xl mb-2 block"></i>
        <span class="text-sm">Belum ada data pegawai atau pencarian tidak ditemukan.</span>
    </div>
    @endforelse
    </div>
</div>

{{-- Table (Desktop) --}}
<div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-800 font-semibold border-b border-gray-100 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Pegawai</th>
                    <th class="px-6 py-4">Kontak</th>
                    <th class="px-6 py-4">Jabatan & Unit</th>
                    <th class="px-6 py-4 text-center">Role</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $user->nama }}</div>
                        <div class="text-xs font-mono text-blue-600 mt-0.5">{{ $user->nip }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-gray-900 flex items-center gap-1.5"><i class="fa-brands fa-whatsapp text-green-500"></i> {{ $user->phone_number ?? '-' }}</div>
                        @if($user->email)
                        <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-1.5"><i class="fa-regular fa-envelope"></i> {{ $user->email }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-gray-800 font-medium">{{ $user->jabatan ? $user->jabatan->nama : '-' }}</div>
                        <div class="text-xs text-gray-500 mt-0.5"><i class="fa-regular fa-building text-gray-400 mr-1"></i> {{ $user->unit ? $user->unit->nama : '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @foreach($user->roles as $r)
                            <span class="px-2 py-1 text-[10px] font-semibold bg-indigo-50 text-indigo-700 rounded border border-indigo-100">{{ $r->name }}</span>
                        @endforeach
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($user->status === 'active')
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                        @elseif($user->status === 'suspended')
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Suspended</span>
                        @else
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800 p-1.5 rounded-md hover:bg-blue-50 transition-colors" title="Edit">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')" class="inline">
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
                            <i class="fa-solid fa-users text-3xl"></i>
                            <p>Belum ada data pegawai atau pencarian tidak ditemukan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $users->links() }}
    </div>
    @endif
</div>

{{-- Mobile Pagination --}}
@if($users->hasPages())
<div class="block md:hidden mt-4">
    {{ $users->links() }}
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    var toggleBtn = document.getElementById('mobile-filter-toggle');
    var filterContainer = document.getElementById('filter-container');
    var filterIcon = document.getElementById('mobile-filter-icon');

    if (toggleBtn && filterContainer) {
        if (window.innerWidth < 768) {
            filterContainer.classList.add('hidden');
        }
        toggleBtn.addEventListener('click', function() {
            var isHidden = filterContainer.classList.contains('hidden');
            if (isHidden) {
                filterContainer.classList.remove('hidden');
                filterContainer.classList.add('block');
                if (filterIcon) filterIcon.style.transform = 'rotate(180deg)';
            } else {
                filterContainer.classList.add('hidden');
                filterContainer.classList.remove('block');
                if (filterIcon) filterIcon.style.transform = 'rotate(0deg)';
            }
        });
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                filterContainer.classList.remove('hidden');
                filterContainer.classList.add('block');
            }
        });
    }
});
</script>
@endsection
