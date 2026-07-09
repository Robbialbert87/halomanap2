@extends('layouts.admin')

@section('title', 'Data Pengaduan - Halo MANAP')

@section('admin_content')

@php
$statusMap = [
    'NEW'                 => ['label' => 'Baru',     'class' => 'bg-yellow-100 text-yellow-700'],
    'TERVERIFIKASI'       => ['label' => 'Terverifikasi', 'class' => 'bg-cyan-100 text-cyan-700'],
    'IN_PROGRESS'         => ['label' => 'Diproses', 'class' => 'bg-blue-100 text-blue-700'],
    'DONE'                => ['label' => 'Selesai',  'class' => 'bg-green-100 text-green-700'],
    'REJECTED'            => ['label' => 'Ditolak',  'class' => 'bg-red-100 text-red-700'],
    'Diproses'            => ['label' => 'Diproses', 'class' => 'bg-blue-100 text-blue-700'],
    'Menunggu Verifikasi' => ['label' => 'Menunggu Verifikasi', 'class' => 'bg-purple-100 text-purple-700'],
    'Selesai'             => ['label' => 'Selesai',  'class' => 'bg-green-100 text-green-700'],
];
$typeMap = [
    'Pengaduan'  => 'bg-red-50 text-red-700',
    'Survei'     => 'bg-green-50 text-green-700',
    'Saran'      => 'bg-green-50 text-green-700',
    'Apresiasi'  => 'bg-blue-50 text-blue-700',
    'Informasi'  => 'bg-orange-50 text-orange-700',
];
@endphp

{{-- Flash Message --}}
@if(session('success'))
<div class="bg-green-50 text-green-700 p-3 rounded-lg mb-4 border border-green-200 flex items-center gap-2">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif

{{-- Mobile Page Header (PayApp style) --}}
<div class="md:hidden mb-3">
    <div class="flex items-center gap-2.5 p-1">
        <span class="w-9 h-9 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200/50 flex-shrink-0">
            <i class="fa-solid fa-clipboard-list text-white text-sm"></i>
        </span>
        <div>
            <p class="text-[9px] text-blue-500 font-semibold tracking-wider uppercase font-heading">Administrasi</p>
            <h1 class="text-base font-bold text-gray-800 font-heading">Data Pengaduan</h1>
        </div>
    </div>
</div>

{{-- Page Header (Desktop) --}}
<div class="hidden md:flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-heading">Data Pengaduan</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Beranda</span>
            <span class="text-gray-400">/</span>
            <span>Pengaduan</span>
        </div>
    </div>
    <a href="{{ route('admin.tickets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors flex items-center gap-2 whitespace-nowrap shadow-sm">
        <i class="fa-solid fa-plus"></i> Tambah Pengaduan
    </a>
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

    {{-- Filter & Search --}}
    <div id="filter-container" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3 mb-4" style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.6) 100%);">
    <form action="{{ route('admin.tickets.index') }}" method="GET" class="flex flex-col md:flex-row gap-2.5">
        <div class="flex-1 relative">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari no. tiket, judul, atau nama pelapor..." autocomplete="off"
                class="w-full bg-white/70 md:bg-gray-50 border border-white/50 md:border-gray-300 text-gray-900 text-[13px] rounded-xl md:rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 md:p-2.5 pl-9"
                id="mobile-search-input">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            @if(request('search'))
            <a href="{{ route('admin.tickets.index', request()->except(['search', 'page'])) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </a>
            @endif
        </div>
        <div class="md:min-w-[140px]">
            <select name="status" class="w-full bg-white/70 md:bg-gray-50 border border-white/50 md:border-gray-300 text-gray-900 text-[13px] rounded-xl md:rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 md:p-2.5">
                <option value="">Semua Status</option>
                <option value="NEW" {{ request('status') == 'NEW' ? 'selected' : '' }}>Baru</option>
                <option value="TERVERIFIKASI" {{ request('status') == 'TERVERIFIKASI' ? 'selected' : '' }}>Terverifikasi</option>
                <option value="IN_PROGRESS" {{ request('status') == 'IN_PROGRESS' ? 'selected' : '' }}>Diproses</option>
                <option value="DONE" {{ request('status') == 'DONE' ? 'selected' : '' }}>Selesai</option>
                <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div class="md:min-w-[130px]">
            <select name="type" class="w-full bg-white/70 md:bg-gray-50 border border-white/50 md:border-gray-300 text-gray-900 text-[13px] rounded-xl md:rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 md:p-2.5">
                <option value="">Semua Jenis</option>
                <option value="Pengaduan" {{ request('type') == 'Pengaduan' ? 'selected' : '' }}>Pengaduan</option>
                <option value="Survei" {{ request('type') == 'Survei' ? 'selected' : '' }}>Survei</option>
                <option value="Apresiasi" {{ request('type') == 'Apresiasi' ? 'selected' : '' }}>Apresiasi</option>
                <option value="Informasi" {{ request('type') == 'Informasi' ? 'selected' : '' }}>Informasi</option>
            </select>
        </div>
        <div class="md:min-w-[180px]">
            <select name="unit_id" class="w-full bg-white/70 md:bg-gray-50 border border-white/50 md:border-gray-300 text-gray-900 text-[13px] rounded-xl md:rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 md:p-2.5">
                <option value="">Semua Unit</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 md:flex-none bg-gradient-to-br from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white px-4 py-2.5 md:py-2.5 rounded-xl md:rounded-lg text-[13px] font-semibold transition-all shadow-sm shadow-blue-200/50 active:scale-[0.98]">
                <i class="fa-solid fa-search mr-1 text-xs"></i> Filter
            </button>
            <a href="{{ route('admin.tickets.index') }}" class="flex-1 md:flex-none bg-gradient-to-br from-gray-100 to-white border border-gray-200 hover:from-gray-200 hover:to-gray-100 text-gray-700 px-4 py-2.5 md:py-2.5 rounded-xl md:rounded-lg text-[13px] font-semibold transition-all active:scale-[0.98] text-center">
                Reset
            </a>
        </div>
    </form>
</div>
</div>

{{-- Mobile: Ticket Cards (Gmail Inbox style) --}}
<div class="block md:hidden mb-4" id="mobile-ticket-list">
    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-white/30 divide-y divide-gray-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
    @forelse($tickets as $ticket)
    @php
        $statusStyle = $statusMap[$ticket->status] ?? ['label' => $ticket->status, 'class' => 'bg-gray-100 text-gray-700'];

        $typeBar = match($ticket->type) {
            'Pengaduan' => 'bg-red-500',
            'Survei', 'Saran' => 'bg-emerald-500',
            'Apresiasi' => 'bg-blue-500',
            'Informasi' => 'bg-orange-500',
            default => 'bg-gray-400',
        };
        $statusDot = match($ticket->status) {
            'NEW' => 'bg-yellow-500',
            'TERVERIFIKASI' => 'bg-cyan-500',
            'IN_PROGRESS', 'Diproses' => 'bg-blue-500',
            'DONE', 'Selesai' => 'bg-emerald-500',
            'REJECTED' => 'bg-red-500',
            'Menunggu Verifikasi' => 'bg-purple-500',
            default => 'bg-gray-400',
        };
    @endphp
    <div class="flex items-stretch cursor-pointer active:bg-gray-50 transition-colors" onclick="window.location='{{ route('admin.tickets.show', $ticket->id) }}'">
        <div class="w-1 shrink-0 {{ $typeBar }}"></div>
        <div class="flex-1 min-w-0 pl-2.5 pr-3 py-2.5">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-1.5 min-w-0 flex-1">
                    <span class="text-[10px] font-mono font-bold text-gray-500 truncate">{{ $ticket->ticket_number }}</span>
                    <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }} shrink-0"></span>
                </div>
                <span class="text-[10px] text-gray-400 whitespace-nowrap shrink-0">{{ $ticket->created_at->format('d M Y') }}</span>
            </div>
            <h3 class="text-[13px] font-semibold text-gray-900 leading-snug mt-0.5 line-clamp-1">{{ $ticket->title }}</h3>
            <div class="flex items-center gap-2 mt-0.5">
                <span class="text-[11px] text-gray-500 truncate">
                    @if($ticket->is_anonymous)
                        Anonim
                    @else
                        {{ $ticket->reporter_name }}
                    @endif
                    · {{ $ticket->category->name ?? '-' }}
                </span>
            </div>
        </div>
        <div class="flex flex-col items-center justify-center pr-2.5 gap-1 shrink-0">
            <button onclick="event.stopPropagation(); confirmDelete('{{ $ticket->id }}', '{{ $ticket->ticket_number }}')" class="text-gray-300 hover:text-red-500 transition-colors p-1">
                <i class="fa-solid fa-trash-can text-[10px]"></i>
            </button>
        </div>
    </div>
    @empty
    <div class="text-center py-10 text-gray-400">
        <i class="fa-regular fa-folder-open text-3xl mb-2 block"></i>
        <span class="text-sm">Belum ada data pengaduan.</span>
    </div>
    @endforelse
    </div>
</div>

{{-- Table (Desktop) --}}
<div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 font-semibold border-b border-gray-100 uppercase text-xs">
                <tr>
                    <th class="px-4 py-4">No. Tiket</th>
                    <th class="px-4 py-4">Pelapor</th>
                    <th class="px-4 py-4">Judul</th>
                    <th class="px-4 py-4">Unit / Ruangan</th>
                    <th class="px-4 py-4">Kategori</th>
                    <th class="px-4 py-4">Jenis</th>
                    <th class="px-4 py-4">Status</th>
                    <th class="px-4 py-4">Tanggal</th>
                    <th class="px-4 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tickets as $ticket)
                @php
                    $typeLabel = $ticket->type === 'Saran' ? 'Survei' : $ticket->type;
                    $statusStyle = $statusMap[$ticket->status] ?? ['label' => $ticket->status, 'class' => 'bg-gray-100 text-gray-700'];
                    $typeClass   = $typeMap[$ticket->type] ?? 'bg-gray-100 text-gray-700';
                @endphp
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-4 py-3">
                        <span class="font-mono font-semibold text-xs text-blue-700 bg-blue-50 px-2 py-1 rounded">{{ $ticket->ticket_number }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @if($ticket->is_anonymous)
                            <span class="flex items-center gap-1 text-gray-400 text-xs italic"><i class="fa-solid fa-user-secret"></i> Anonim</span>
                        @else
                            <div class="font-medium text-gray-800 text-xs">{{ $ticket->reporter_name }}</div>
                            <div class="text-gray-400 text-xs">{{ $ticket->reporter_phone }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 max-w-[180px]">
                        <p class="font-medium text-gray-800 text-xs truncate" title="{{ $ticket->title }}">{{ $ticket->title }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-xs font-semibold text-gray-800">{{ $ticket->room->unit->nama ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $ticket->room->name ?? '-' }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-xs text-gray-700">{{ $ticket->category->name ?? '-' }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex text-xs font-semibold px-2 py-0.5 rounded-full {{ $typeClass }}">{{ $typeLabel }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusStyle['class'] }}">{{ $statusStyle['label'] }}</span>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">{{ $ticket->created_at->format('d M Y') }}<br>{{ $ticket->created_at->format('H:i') }}</td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
                                <i class="fa-solid fa-eye mr-1"></i> Detail
                            </a>
                            <button onclick="confirmDelete('{{ $ticket->id }}', '{{ $ticket->ticket_number }}')"
                                class="bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                        <i class="fa-regular fa-folder-open text-4xl mb-3 block"></i>
                        Belum ada data pengaduan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($tickets->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $tickets->links() }}
    </div>
    @endif
</div>



{{-- Mobile Pagination --}}
@if($tickets->hasPages())
<div class="block md:hidden mt-4">
    {{ $tickets->links() }}
</div>
@endif

{{-- Modal Konfirmasi Hapus (bottom sheet mobile, centered desktop) --}}
<div id="delete-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden items-end md:items-center justify-center">
    <div id="delete-modal-sheet" class="w-full md:max-w-sm md:mx-4 bg-white md:rounded-xl rounded-t-2xl shadow-xl md:shadow-xl overflow-hidden animate-slide-up md:animate-none">
        <div class="p-5">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-100 to-red-50 border border-red-200 text-red-500 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                </div>
                <div>
                    <h3 class="font-heading font-bold text-gray-800 text-sm">Hapus Pengaduan?</h3>
                    <p class="text-[12px] text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
            <p class="text-[12px] text-gray-600 bg-gray-50 px-2.5 py-1.5 rounded-lg mb-4">
                Anda akan menghapus tiket: <span id="modal-ticket-number" class="font-mono font-bold text-red-600"></span>
            </p>
            <div class="flex gap-2.5">
                <button onclick="closeDeleteModal()" class="flex-1 bg-gradient-to-br from-gray-100 to-white border border-gray-200 hover:from-gray-200 hover:to-gray-100 text-gray-700 font-semibold rounded-xl py-2.5 md:py-2 text-[12px] transition-all active:scale-[0.98]">
                    Batal
                </button>
                <form id="delete-form" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-gradient-to-br from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white font-semibold rounded-xl py-2.5 md:py-2 text-[12px] transition-all shadow-sm shadow-red-200/50 active:scale-[0.98]">
                        <i class="fa-solid fa-trash mr-1"></i> Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes slideUp {
    from { transform: translateY(100%); }
    to   { transform: translateY(0); }
}
.animate-slide-up {
    animation: slideUp 0.3s ease-out both;
}
</style>

<script>
function confirmDelete(id, ticketNumber) {
    const modal = document.getElementById('delete-modal');
    const form = document.getElementById('delete-form');
    const label = document.getElementById('modal-ticket-number');
    form.action = '/admin/tickets/' + id;
    label.textContent = ticketNumber;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeDeleteModal() {
    const modal = document.getElementById('delete-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
document.getElementById('delete-modal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

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
                filterIcon.style.transform = 'rotate(180deg)';
            } else {
                filterContainer.classList.add('hidden');
                filterContainer.classList.remove('block');
                filterIcon.style.transform = 'rotate(0deg)';
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
