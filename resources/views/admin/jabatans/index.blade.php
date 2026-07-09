@extends('layouts.admin')

@section('title', 'Master Jabatan - Halo MANAP')

@section('admin_content')

{{-- Mobile Page Header (PayApp style) --}}
<div class="md:hidden mb-3">
    <div class="flex items-center gap-2.5 p-1">
        <span class="w-9 h-9 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-sm shadow-amber-200/50 flex-shrink-0">
            <i class="fa-solid fa-sitemap text-white text-sm"></i>
        </span>
        <div>
            <p class="text-[9px] text-amber-500 font-semibold tracking-wider uppercase font-heading">Master Data</p>
            <h1 class="text-base font-bold text-gray-800 font-heading">Master Jabatan</h1>
        </div>
    </div>
</div>

{{-- Page Header (Desktop) --}}
<div class="hidden md:flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-heading">Master Jabatan</h1>
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
            <span class="w-6 h-6 rounded-lg bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center">
                <i class="fa-solid fa-sliders text-white text-[10px]"></i>
            </span>
            <span class="font-heading font-semibold text-[13px]">Filter & Pencarian</span>
        </span>
        <i id="mobile-filter-icon" class="fa-solid fa-chevron-down text-gray-400 transition-transform duration-300 text-xs"></i>
    </button>

    <div id="filter-container" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3 mb-3" style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.6) 100%);">
    <form method="GET" action="{{ route('admin.jabatans.index') }}" class="flex flex-col gap-2.5">
        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari kode, nama, atau kategori..." autocomplete="off"
                class="w-full bg-white/70 border border-gray-200 text-gray-900 text-[13px] rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5 pl-9">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            @if(request('search'))
            <a href="{{ route('admin.jabatans.index', request()->except(['search', 'page'])) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </a>
            @endif
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-gradient-to-br from-amber-500 to-amber-700 hover:from-amber-600 hover:to-amber-800 text-white px-4 py-2.5 rounded-xl text-[13px] font-semibold transition-all shadow-sm shadow-amber-200/50 active:scale-[0.98]">
                <i class="fa-solid fa-search mr-1 text-xs"></i> Cari
            </button>
            <a href="{{ route('admin.jabatans.index') }}" class="flex-1 bg-gradient-to-br from-gray-100 to-white border border-gray-200 hover:from-gray-200 hover:to-gray-100 text-gray-700 px-4 py-2.5 rounded-xl text-[13px] font-semibold transition-all active:scale-[0.98] text-center">
                Reset
            </a>
        </div>
    </form>
    </div>
</div>

{{-- Desktop Filter --}}
<div class="hidden md:block mb-4">
    <form method="GET" action="{{ route('admin.jabatans.index') }}">
        <div class="relative max-w-md">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode, nama, atau kategori..." autocomplete="off"
                class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg pl-10 pr-10 py-2.5 focus:ring-blue-500 focus:border-blue-500">
            @if(request('search'))
            <a href="{{ route('admin.jabatans.index') }}" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </a>
            @endif
        </div>
    </form>
</div>

{{-- Mobile: Jabatan List --}}
<div class="block md:hidden mb-4">
    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-white/30 divide-y divide-gray-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
    @php
        $kategoriColors = [
            'Direktur' => ['bg' => 'bg-purple-100 text-purple-700', 'dot' => 'bg-purple-500'],
            'Kabid' => ['bg' => 'bg-blue-100 text-blue-700', 'dot' => 'bg-blue-500'],
            'Kabag' => ['bg' => 'bg-blue-100 text-blue-700', 'dot' => 'bg-blue-500'],
            'Kasi' => ['bg' => 'bg-amber-100 text-amber-700', 'dot' => 'bg-amber-500'],
            'Kasubbag' => ['bg' => 'bg-amber-100 text-amber-700', 'dot' => 'bg-amber-500'],
            'Kepala Unit' => ['bg' => 'bg-green-100 text-green-700', 'dot' => 'bg-green-500'],
            'Petugas' => ['bg' => 'bg-gray-100 text-gray-700', 'dot' => 'bg-gray-400'],
        ];
    @endphp
    @forelse($jabatans as $jabatan)
    @php
        $kc = $kategoriColors[$jabatan->kategori_jabatan] ?? ['bg' => 'bg-gray-100 text-gray-700', 'dot' => 'bg-gray-400'];
    @endphp
    <div class="flex items-stretch active:bg-gray-50 transition-colors">
        <div class="w-1 shrink-0 {{ $kc['dot'] }}"></div>
        <div class="flex-1 min-w-0 pl-2.5 pr-3 py-2.5">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-1.5 min-w-0 flex-1">
                    <span class="text-[13px] font-semibold text-gray-900 truncate">{{ $jabatan->nama }}</span>
                    @if($jabatan->status === 'active')
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 shrink-0"></span>
                    @else
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 shrink-0"></span>
                    @endif
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <a href="{{ route('admin.jabatans.edit', $jabatan->id) }}"
                       class="text-gray-300 hover:text-blue-500 transition-colors p-1" title="Edit">
                        <i class="fa-regular fa-pen-to-square text-[10px]"></i>
                    </a>
                    <form action="{{ route('admin.jabatans.destroy', $jabatan->id) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus jabatan {{ $jabatan->nama }}?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors p-1" title="Hapus">
                            <i class="fa-regular fa-trash-can text-[10px]"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-[11px] font-mono text-blue-600">{{ $jabatan->kode }}</span>
                <span class="text-[10px]">·</span>
                <span class="inline-block px-1.5 py-0.5 text-[10px] font-semibold rounded-full {{ $kc['bg'] }}">{{ $jabatan->kategori_jabatan }}</span>
                @if($jabatan->status === 'active')
                    <span class="inline-block px-1.5 py-0.5 text-[10px] font-semibold rounded-full bg-green-50 text-green-700">Aktif</span>
                @else
                    <span class="inline-block px-1.5 py-0.5 text-[10px] font-semibold rounded-full bg-gray-100 text-gray-700">Nonaktif</span>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-10 text-gray-400">
        <i class="fa-solid fa-sitemap text-3xl mb-2 block"></i>
        <span class="text-sm">Belum ada data jabatan.</span>
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
                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                    @else
                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
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

{{-- Mobile Pagination --}}
@if($jabatans->hasPages())
<div class="block md:hidden mt-4">
    {{ $jabatans->links() }}
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
