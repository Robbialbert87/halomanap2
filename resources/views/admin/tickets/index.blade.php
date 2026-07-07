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

{{-- Page Header --}}
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Data Pengaduan</h1>
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

{{-- Mobile Filter Toggle --}}
<button id="mobile-filter-toggle" type="button" class="md:hidden w-full bg-white rounded-xl shadow-sm border border-gray-100 p-3 flex items-center justify-between text-sm text-gray-700 font-medium mb-3 active:scale-[0.99] transition-all">
    <span class="flex items-center gap-2">
        <i class="fa-solid fa-sliders text-blue-500"></i>
        <span>Filter & Pencarian</span>
    </span>
    <i id="mobile-filter-icon" class="fa-solid fa-chevron-down text-gray-400 transition-transform duration-300"></i>
</button>

{{-- Filter & Search --}}
<div id="filter-container" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.tickets.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
        <div class="flex-1 relative">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari no. tiket, judul, atau nama pelapor..." autocomplete="off"
                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 pl-9"
                oninput="clearTimeout(this.debounce); this.debounce = setTimeout(() => { this.form.submit(); }, 500);">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            @if(request('search'))
            <a href="{{ route('admin.tickets.index', request()->except(['search', 'page'])) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </a>
            @endif
        </div>
        <div>
            <select name="status" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                <option value="">Semua Status</option>
                <option value="NEW" {{ request('status') == 'NEW' ? 'selected' : '' }}>Baru</option>
                <option value="TERVERIFIKASI" {{ request('status') == 'TERVERIFIKASI' ? 'selected' : '' }}>Terverifikasi</option>
                <option value="IN_PROGRESS" {{ request('status') == 'IN_PROGRESS' ? 'selected' : '' }}>Diproses</option>
                <option value="DONE" {{ request('status') == 'DONE' ? 'selected' : '' }}>Selesai</option>
                <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div>
            <select name="type" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                <option value="">Semua Jenis</option>
                <option value="Pengaduan" {{ request('type') == 'Pengaduan' ? 'selected' : '' }}>Pengaduan</option>
                <option value="Survei" {{ request('type') == 'Survei' ? 'selected' : '' }}>Survei</option>
                <option value="Apresiasi" {{ request('type') == 'Apresiasi' ? 'selected' : '' }}>Apresiasi</option>
                <option value="Informasi" {{ request('type') == 'Informasi' ? 'selected' : '' }}>Informasi</option>
            </select>
        </div>
        <div>
            <select name="unit_id" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                <option value="">Semua Unit</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-colors">
                <i class="fa-solid fa-search mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.tickets.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors">
                Reset
            </a>
        </div>
    </form>
</div>

{{-- Mobile: Ticket Cards --}}
<div class="block md:hidden space-y-3 mb-6" id="mobile-ticket-list">
    @forelse($tickets as $ticket)
    @php
        $typeLabel = $ticket->type === 'Saran' ? 'Survei' : $ticket->type;
        $statusStyle = $statusMap[$ticket->status] ?? ['label' => $ticket->status, 'class' => 'bg-gray-100 text-gray-700'];
        $typeClass   = $typeMap[$ticket->type] ?? 'bg-gray-100 text-gray-700';
    @endphp
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden active:bg-gray-50 transition-colors touch-manipulation" onclick="window.location='{{ route('admin.tickets.show', $ticket->id) }}'">
        <div class="p-4">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="font-mono font-semibold text-xs text-blue-700 bg-blue-50 px-2 py-1 rounded">{{ $ticket->ticket_number }}</span>
                <div class="flex items-center gap-1.5 shrink-0">
                    <span class="inline-flex text-xs font-semibold px-2 py-0.5 rounded-full {{ $typeClass }}">{{ $typeLabel }}</span>
                    <span class="inline-flex text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusStyle['class'] }}">{{ $statusStyle['label'] }}</span>
                </div>
            </div>
            <h3 class="font-medium text-gray-800 text-sm leading-snug mb-2 line-clamp-2">{{ $ticket->title }}</h3>
            <div class="flex flex-col gap-1 text-xs text-gray-500">
                @if($ticket->is_anonymous)
                    <span class="flex items-center gap-1"><i class="fa-solid fa-user-secret w-3.5 text-center text-gray-400"></i> Anonim</span>
                @else
                    <span class="flex items-center gap-1"><i class="fa-solid fa-user w-3.5 text-center text-gray-400"></i> {{ $ticket->reporter_name }} - {{ $ticket->reporter_phone }}</span>
                @endif
                <span class="flex items-center gap-1"><i class="fa-solid fa-location-dot w-3.5 text-center text-gray-400"></i> {{ $ticket->room->unit->nama ?? '-' }} / {{ $ticket->room->name ?? '-' }}</span>
                <span class="flex items-center gap-1"><i class="fa-solid fa-tag w-3.5 text-center text-gray-400"></i> {{ $ticket->category->name ?? '-' }}</span>
            </div>
        </div>
        <div class="flex items-center justify-between px-4 py-2.5 bg-gray-50 border-t border-gray-100">
            <span class="text-xs text-gray-400 flex items-center gap-1">
                <i class="fa-regular fa-clock"></i> {{ $ticket->created_at->format('d M Y H:i') }}
            </span>
            <div class="flex gap-2">
                <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors inline-flex items-center gap-1">
                    <i class="fa-solid fa-eye"></i> Detail
                </a>
                <button onclick="event.stopPropagation(); confirmDelete('{{ $ticket->id }}', '{{ $ticket->ticket_number }}')"
                    class="bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-12 text-gray-400">
        <i class="fa-regular fa-folder-open text-4xl mb-3 block"></i>
        Belum ada data pengaduan.
    </div>
    @endforelse
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

{{-- Modal Konfirmasi Hapus --}}
<div id="delete-modal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl p-6 max-w-sm w-full mx-4">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-800">Hapus Pengaduan?</h3>
                <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
        </div>
        <p class="text-sm text-gray-600 bg-gray-50 px-3 py-2 rounded-lg mb-5">
            Anda akan menghapus tiket: <span id="modal-ticket-number" class="font-mono font-bold text-red-600"></span>
        </p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg py-2 text-sm transition-colors">
                Batal
            </button>
            <form id="delete-form" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg py-2 text-sm transition-colors">
                    <i class="fa-solid fa-trash mr-1"></i> Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

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
