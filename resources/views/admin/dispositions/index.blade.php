@extends('layouts.admin')

@section('title', 'Disposisi - Halo MANAP')

@section('admin_content')

{{-- Statistik --}}
@php
    $total   = $workflows->total();
    $terlambat = $workflows->filter(fn($w) => $w->due_at && $w->due_at->isPast() && !in_array($w->status, ['selesai','ditutup','menunggu_verifikasi']))->count();
    $menunggu  = $workflows->where('status', 'menunggu_respon')->count();
    $proses    = $workflows->where('status', 'dalam_penanganan')->count();
@endphp

{{-- Mobile Page Header (PayApp style) --}}
<div class="md:hidden mb-3">
    <div class="flex items-center gap-2.5 p-1">
        <span class="w-9 h-9 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200/50 flex-shrink-0">
            <i class="fa-solid fa-arrow-right-arrow-left text-white text-sm"></i>
        </span>
        <div>
            <p class="text-[9px] text-blue-500 font-semibold tracking-wider uppercase font-heading">Administrasi</p>
            <h1 class="text-base font-bold text-gray-800 font-heading">Disposisi</h1>
        </div>
    </div>
</div>

{{-- Page Header (Desktop) --}}
<div class="hidden md:flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-heading">Disposisi</h1>
        <div class="text-sm text-gray-500 mt-1">Pantau disposisi dari seluruh unit</div>
    </div>
</div>

{{-- Statistik Cards (di-mobile juga ditampilkan) --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-4 md:mb-6">
    <div class="bg-white rounded-xl p-3 md:p-4 shadow-sm border border-gray-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
        <p class="text-[10px] md:text-xs text-gray-500 font-semibold tracking-wide">Total</p>
        <p class="text-lg md:text-2xl font-bold text-gray-800 mt-0.5">{{ $total }}</p>
    </div>
    <div class="bg-white rounded-xl p-3 md:p-4 shadow-sm border border-gray-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
        <p class="text-[10px] md:text-xs text-gray-500 font-semibold tracking-wide">Menunggu</p>
        <p class="text-lg md:text-2xl font-bold text-yellow-600 mt-0.5">{{ $menunggu }}</p>
    </div>
    <div class="bg-white rounded-xl p-3 md:p-4 shadow-sm border border-gray-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
        <p class="text-[10px] md:text-xs text-gray-500 font-semibold tracking-wide">Diproses</p>
        <p class="text-lg md:text-2xl font-bold text-indigo-600 mt-0.5">{{ $proses }}</p>
    </div>
    <div class="bg-white rounded-xl p-3 md:p-4 shadow-sm border border-gray-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
        <p class="text-[10px] md:text-xs text-gray-500 font-semibold tracking-wide">Terlambat</p>
        <p class="text-lg md:text-2xl font-bold {{ $terlambat > 0 ? 'text-red-600' : 'text-gray-800' }} mt-0.5">{{ $terlambat }}</p>
    </div>
</div>

{{-- Mobile Filter Sticky Wrapper --}}
<div class="md:hidden sticky top-0 z-30 bg-[#F3F4F6] pt-1 pb-1 -mx-4 px-4">
    <button id="mobile-filter-toggle" type="button" class="w-full bg-white/70 backdrop-blur-xl rounded-2xl border border-white/30 p-2.5 flex items-center justify-between text-sm text-gray-700 font-medium mb-2.5 active:scale-[0.98] transition-all shadow-sm" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
        <span class="flex items-center gap-2">
            <span class="w-6 h-6 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                <i class="fa-solid fa-sliders text-white text-[10px]"></i>
            </span>
            <span class="font-heading font-semibold text-[13px]">Filter & Pencarian</span>
        </span>
        <i id="mobile-filter-icon" class="fa-solid fa-chevron-down text-gray-400 transition-transform duration-300 text-xs"></i>
    </button>

    <div id="filter-container" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3 mb-3" style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.6) 100%);">
    <form method="GET" class="flex flex-col md:flex-row gap-2.5">
        <div class="flex-1">
            <select name="status" class="w-full bg-white/70 border border-white/50 text-gray-900 text-[13px] rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5">
                <option value="">Semua Status</option>
                @foreach($statuses as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1">
            <select name="unit_id" class="w-full bg-white/70 border border-white/50 text-gray-900 text-[13px] rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5">
                <option value="">Semua Unit</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-gradient-to-br from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white px-4 py-2.5 rounded-xl text-[13px] font-semibold transition-all shadow-sm shadow-blue-200/50 active:scale-[0.98]">
                <i class="fa-solid fa-filter mr-1 text-xs"></i> Filter
            </button>
            <a href="{{ route('admin.dispositions.index') }}" class="flex-1 bg-gradient-to-br from-gray-100 to-white border border-gray-200 hover:from-gray-200 hover:to-gray-100 text-gray-700 px-4 py-2.5 rounded-xl text-[13px] font-semibold transition-all active:scale-[0.98] text-center">
                Reset
            </a>
        </div>
    </form>
    </div>
</div>

{{-- Desktop Filter --}}
<div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" class="flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
            <select name="status" class="w-44 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2">
                <option value="">Semua</option>
                @foreach($statuses as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Unit</label>
            <select name="unit_id" class="w-56 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2">
                <option value="">Semua</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg"><i class="fa-solid fa-filter mr-1"></i> Filter</button>
        <a href="{{ route('admin.dispositions.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg"><i class="fa-solid fa-rotate-right mr-1"></i> Reset</a>
    </form>
</div>

{{-- Mobile: Disposition List --}}
<div class="block md:hidden mb-4">
    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-white/30 divide-y divide-gray-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
    @forelse($workflows as $wf)
    @php
        $isOverdue = $wf->due_at && $wf->due_at->isPast() && !in_array($wf->status, ['selesai','ditutup','menunggu_verifikasi']);
    @endphp
    <div class="flex items-stretch cursor-pointer active:bg-gray-50 transition-colors" onclick="window.location='{{ route('admin.tickets.show', $wf->ticket_id) }}'">
        <div class="w-1 shrink-0 {{ $isOverdue ? 'bg-red-500' : 'bg-blue-500' }}"></div>
        <div class="flex-1 min-w-0 pl-2.5 pr-3 py-2.5">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-1.5 min-w-0 flex-1">
                    <span class="text-[10px] font-mono font-bold text-blue-600 truncate">{{ $wf->ticket?->ticket_number ?? '-' }}</span>
                    @if($isOverdue)
                        <span class="text-red-500"><i class="fa-solid fa-exclamation-circle text-[10px]"></i></span>
                    @endif
                </div>
                <span class="text-[10px] text-gray-400 whitespace-nowrap shrink-0">{{ $wf->created_at->format('d/m/Y') }}</span>
            </div>
            <h3 class="text-[13px] font-semibold text-gray-900 leading-snug mt-0.5 line-clamp-1">{{ $wf->ticket?->title ?? '-' }}</h3>
            <div class="flex items-center gap-2 mt-0.5">
                <span class="text-[11px] text-gray-500 truncate">
                    {{ $wf->toUnit?->nama ?? '-' }} · {{ $wf->toUser?->nama ?? '-' }}
                </span>
                <span class="inline-block px-1.5 py-0.5 text-[10px] font-semibold rounded {{ $wf->status_badge['class'] }}">{{ $wf->status_badge['label'] }}</span>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-10 text-gray-400">
        <i class="fa-solid fa-arrow-right-arrow-left text-3xl mb-2 block"></i>
        <span class="text-sm">Belum ada disposisi.</span>
    </div>
    @endforelse
    </div>
</div>

{{-- Table (Desktop) --}}
<div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left text-gray-500 font-semibold text-xs uppercase tracking-wider">
                    <th class="px-4 py-3">Tiket</th>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Unit</th>
                    <th class="px-4 py-3">Penerima</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Tenggat</th>
                    <th class="px-4 py-3">Tgl</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($workflows as $wf)
                @php
                    $isOverdue = $wf->due_at && $wf->due_at->isPast() && !in_array($wf->status, ['selesai','ditutup','menunggu_verifikasi']);
                @endphp
                <tr class="{{ $isOverdue ? 'bg-red-50' : 'hover:bg-gray-50' }}">
                    <td class="px-4 py-3 font-mono text-xs font-bold text-blue-600">{{ $wf->ticket?->ticket_number ?? '-' }}</td>
                    <td class="px-4 py-3 max-w-[220px] truncate font-medium text-gray-800">{{ $wf->ticket?->title ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $wf->toUnit?->nama ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        {{ $wf->toUser?->nama ?? '-' }}
                        <span class="text-xs text-gray-400 block">{{ $wf->toJabatan?->nama ?? '' }}</span>
                    </td>
                    <td class="px-4 py-3"><span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $wf->status_badge['class'] }}">{{ $wf->status_badge['label'] }}</span></td>
                    <td class="px-4 py-3 whitespace-nowrap {{ $isOverdue ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                        @if($wf->due_at)
                            {{ $wf->due_at->format('d/m/Y') }}
                            @if($isOverdue) <i class="fa-solid fa-exclamation-circle"></i> @endif
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-gray-500">{{ $wf->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.tickets.show', $wf->ticket_id) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium"><i class="fa-solid fa-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-arrow-right-arrow-left text-3xl mb-2"></i>
                        <p>Belum ada disposisi</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($workflows->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">{{ $workflows->links() }}</div>
    @endif
</div>

{{-- Mobile Pagination --}}
@if($workflows->hasPages())
<div class="block md:hidden mt-4">
    {{ $workflows->links() }}
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
