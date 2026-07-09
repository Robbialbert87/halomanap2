@extends('layouts.admin')

@section('title', 'Manajemen Ruangan - Halo MANAP')

@section('admin_content')

{{-- Mobile Page Header (PayApp style) --}}
<div class="md:hidden mb-3">
    <div class="flex items-center gap-2.5 p-1">
        <span class="w-9 h-9 rounded-2xl bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center shadow-sm shadow-violet-200/50 flex-shrink-0">
            <i class="fa-solid fa-door-open text-white text-sm"></i>
        </span>
        <div>
            <p class="text-[9px] text-violet-500 font-semibold tracking-wider uppercase font-heading">Master Data</p>
            <h1 class="text-base font-bold text-gray-800 font-heading">Manajemen Ruangan</h1>
        </div>
    </div>
</div>

{{-- Page Header (Desktop) --}}
<div class="hidden md:flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-heading">Manajemen Ruangan</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Master Data</span>
            <span class="text-gray-400">/</span>
            <span>Ruangan</span>
        </div>
    </div>
    <a href="{{ route('admin.rooms.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Ruangan
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

{{-- Mobile Filter Sticky Wrapper --}}
<div class="md:hidden sticky top-0 z-30 bg-[#F3F4F6] pt-1 pb-1 -mx-4 px-4">
    <button id="mobile-filter-toggle" type="button" class="w-full bg-white/70 backdrop-blur-xl rounded-2xl border border-white/30 p-2.5 flex items-center justify-between text-sm text-gray-700 font-medium mb-2.5 active:scale-[0.98] transition-all shadow-sm" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
        <span class="flex items-center gap-2">
            <span class="w-6 h-6 rounded-lg bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center">
                <i class="fa-solid fa-sliders text-white text-[10px]"></i>
            </span>
            <span class="font-heading font-semibold text-[13px]">Filter & Pencarian</span>
        </span>
        <i id="mobile-filter-icon" class="fa-solid fa-chevron-down text-gray-400 transition-transform duration-300 text-xs"></i>
    </button>

    <div id="filter-container" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3 mb-3" style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.6) 100%);">
    <form method="GET" action="{{ route('admin.rooms.index') }}" class="flex flex-col gap-2.5">
        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari ruangan atau unit..." autocomplete="off"
                class="w-full bg-white/70 border border-gray-200 text-gray-900 text-[13px] rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5 pl-9">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            @if(request('search'))
            <a href="{{ route('admin.rooms.index', request()->except(['search', 'page'])) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </a>
            @endif
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-gradient-to-br from-violet-500 to-violet-700 hover:from-violet-600 hover:to-violet-800 text-white px-4 py-2.5 rounded-xl text-[13px] font-semibold transition-all shadow-sm shadow-violet-200/50 active:scale-[0.98]">
                <i class="fa-solid fa-search mr-1 text-xs"></i> Cari
            </button>
            <a href="{{ route('admin.rooms.index') }}" class="flex-1 bg-gradient-to-br from-gray-100 to-white border border-gray-200 hover:from-gray-200 hover:to-gray-100 text-gray-700 px-4 py-2.5 rounded-xl text-[13px] font-semibold transition-all active:scale-[0.98] text-center">
                Reset
            </a>
        </div>
    </form>
    </div>
</div>

{{-- Desktop Filter --}}
<div class="hidden md:block mb-4">
    <form method="GET" action="{{ route('admin.rooms.index') }}">
        <div class="relative max-w-md">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ruangan atau unit..." autocomplete="off"
                class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg pl-10 pr-10 py-2.5 focus:ring-blue-500 focus:border-blue-500">
            @if(request('search'))
            <a href="{{ route('admin.rooms.index') }}" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </a>
            @endif
        </div>
    </form>
</div>

{{-- Mobile: Room List --}}
<div class="block md:hidden mb-4">
    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-white/30 divide-y divide-gray-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
    @forelse($rooms as $room)
    <div class="flex items-stretch active:bg-gray-50 transition-colors">
        <div class="w-1 shrink-0 bg-violet-500"></div>
        <div class="flex-1 min-w-0 pl-2.5 pr-3 py-2.5">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-1.5 min-w-0 flex-1">
                    <span class="text-[13px] font-semibold text-gray-900 truncate">{{ $room->name }}</span>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <a href="{{ route('admin.rooms.edit', $room->id) }}"
                       class="text-gray-300 hover:text-blue-500 transition-colors p-1" title="Edit">
                        <i class="fa-regular fa-pen-to-square text-[10px]"></i>
                    </a>
                    <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors p-1" title="Hapus">
                            <i class="fa-regular fa-trash-can text-[10px]"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-1">
                <span class="inline-flex items-center gap-1 py-0.5 px-1.5 rounded-md bg-blue-50 text-blue-700 text-[10px] font-semibold border border-blue-100">
                    <i class="fa-solid fa-building text-[9px]"></i> {{ $room->unit->nama ?? 'Tanpa Unit' }}
                </span>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-10 text-gray-400">
        <i class="fa-solid fa-door-open text-3xl mb-2 block"></i>
        <span class="text-sm">Belum ada data ruangan.</span>
    </div>
    @endforelse
    </div>
</div>

{{-- Table (Desktop) --}}
<div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50 text-gray-800 font-semibold border-b border-gray-100 uppercase text-xs">
            <tr>
                <th class="px-6 py-4 w-16 text-center">No</th>
                <th class="px-6 py-4">Nama Ruangan</th>
                <th class="px-6 py-4">Unit Terkait</th>
                <th class="px-6 py-4 w-32 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($rooms as $index => $room)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 text-center">{{ $rooms->firstItem() + $index }}</td>
                <td class="px-6 py-4 font-medium text-gray-900">{{ $room->name }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-md bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-100">
                        <i class="fa-solid fa-building"></i> {{ $room->unit->nama ?? 'Tanpa Unit' }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-1">
                        <a href="{{ route('admin.rooms.edit', $room->id) }}" class="text-blue-600 hover:text-blue-800 p-1.5 rounded-md hover:bg-blue-50 transition-colors">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?');" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 p-1.5 rounded-md hover:bg-red-50 transition-colors">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-8 text-center text-gray-400">Belum ada data ruangan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if(method_exists($rooms, 'hasPages') && $rooms->hasPages())
<div class="block md:hidden mt-4">
    {{ $rooms->links() }}
</div>
<div class="hidden md:block mt-6">
    {{ $rooms->links() }}
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
