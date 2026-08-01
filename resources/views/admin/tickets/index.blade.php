@extends('layouts.admin')

@section('title', 'Data Pengaduan - Halo MANAP')

@section('admin_content')

<x-admin.flash />

<x-admin.page-header
    title="Data Pengaduan"
    section="Administrasi"
    crumb="Pengaduan"
    icon="fa-clipboard-list"
    gradient="blue"
>
    <x-admin.btn href="{{ route('admin.tickets.create') }}" icon="fa-plus" class="whitespace-nowrap">Tambah Pengaduan</x-admin.btn>
</x-admin.page-header>

<x-admin.search-toolbar
    action="{{ route('admin.tickets.index') }}"
    live-target="#tickets-results"
    placeholder="Cari no. tiket, judul, atau nama pelapor..."
    :search="request('search')"
    :grid="true"
>
    <select name="status" class="admin-input text-[13px]">
        <option value="">Semua Status</option>
        <option value="NEW" {{ request('status') == 'NEW' ? 'selected' : '' }}>Baru</option>
        <option value="TERVERIFIKASI" {{ request('status') == 'TERVERIFIKASI' ? 'selected' : '' }}>Terverifikasi</option>
        <option value="IN_PROGRESS" {{ request('status') == 'IN_PROGRESS' ? 'selected' : '' }}>Diproses</option>
        <option value="DONE" {{ request('status') == 'DONE' ? 'selected' : '' }}>Selesai</option>
        <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>Ditolak</option>
    </select>
    <select name="type" class="admin-input text-[13px]">
        <option value="">Semua Jenis</option>
        <option value="Pengaduan" {{ request('type') == 'Pengaduan' ? 'selected' : '' }}>Pengaduan</option>
        <option value="Survei" {{ request('type') == 'Survei' ? 'selected' : '' }}>Survei</option>
        <option value="Apresiasi" {{ request('type') == 'Apresiasi' ? 'selected' : '' }}>Apresiasi</option>
        <option value="Informasi" {{ request('type') == 'Informasi' ? 'selected' : '' }}>Informasi</option>
    </select>
    <select name="unit_id" class="admin-input text-[13px]">
        <option value="">Semua Unit</option>
        @foreach($units as $unit)
            <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama }}</option>
        @endforeach
    </select>
</x-admin.search-toolbar>

{{-- Hasil: mobile list + tabel desktop + pagination (di-refresh real-time oleh live filter) --}}
<div id="tickets-results" class="admin-live-wrap">
        @fragment('results')
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

{{-- Info ringkas hasil filter (desktop) --}}
<div class="hidden md:flex items-center justify-between px-5 py-2.5 border-b border-gray-100 bg-gray-50/40">
    <p class="text-[11px] text-gray-500">
        Menampilkan <span class="font-semibold text-gray-700">{{ $tickets->firstItem() ?? 0 }}–{{ $tickets->lastItem() ?? 0 }}</span>
        dari <span class="font-semibold text-gray-700">{{ $tickets->total() }}</span> pengaduan
    </p>
</div>

{{-- Mobile: Ticket Cards (Gmail Inbox style) --}}
<div class="block md:hidden mb-4" id="mobile-ticket-list">
    <div class="admin-card overflow-hidden divide-y divide-gray-100">
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
    @endphp
    <div class="flex items-stretch cursor-pointer active:bg-gray-50 transition-colors" onclick="window.location='{{ route('admin.tickets.show', $ticket->id) }}'">
        <div class="w-1 shrink-0 {{ $typeBar }}"></div>
        <div class="flex-1 min-w-0 pl-2.5 pr-3 py-2.5">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-1.5 min-w-0 flex-1">
                    <span class="text-[10px] font-mono font-bold text-gray-500 truncate">{{ $ticket->ticket_number }}</span>
                    <span class="text-[9px] font-semibold px-1.5 py-0.5 rounded-full leading-tight {{ $statusStyle['class'] }}">{{ $statusStyle['label'] }}</span>
                </div>
                <span class="text-[10px] text-gray-400 whitespace-nowrap shrink-0">{{ $ticket->created_at->format('d M Y') }}</span>
            </div>
            <h3 class="text-[13px] font-semibold text-gray-900 leading-snug mt-0.5">{{ $ticket->title }}</h3>
            <div class="flex items-center gap-2 mt-0.5">
                <span class="text-[11px] text-gray-500">
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
            <button onclick="event.stopPropagation(); confirmDelete('{{ $ticket->id }}', '{{ $ticket->ticket_number }}')" class="admin-action admin-action-sm admin-action-red" title="Hapus">
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
<div class="hidden md:block admin-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="admin-table w-full text-left text-sm text-gray-600">
            <thead>
                <tr>
                    <th class="px-4 py-4">No. Tiket</th>
                    <th class="px-4 py-4">Judul</th>
                    <th class="px-4 py-4 hidden lg:table-cell">Jenis</th>
                    <th class="px-4 py-4">Status</th>
                    <th class="px-4 py-4 hidden sm:table-cell">Tanggal</th>
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
                    <td class="px-4 py-3 max-w-[260px]">
                        <p class="font-medium text-gray-800 text-xs leading-snug">{{ $ticket->title }}</p>
                        <p class="text-[11px] text-gray-400 leading-snug mt-0.5">
                            @if($ticket->is_anonymous)
                                <span class="italic"><i class="fa-solid fa-user-secret mr-0.5"></i>Anonim</span>
                            @else
                                {{ $ticket->reporter_name }}@if($ticket->reporter_phone) · {{ $ticket->reporter_phone }}@endif
                            @endif
                        </p>
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell">
                        <span class="inline-flex text-xs font-semibold px-2 py-0.5 rounded-full {{ $typeClass }}">{{ $typeLabel }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusStyle['class'] }}">{{ $statusStyle['label'] }}</span>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap hidden sm:table-cell">{{ $ticket->created_at->format('d M Y') }}<br>{{ $ticket->created_at->format('H:i') }}</td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="admin-action-pill admin-action-pill-blue" title="Lihat detail">
                                <i class="fa-solid fa-eye text-[11px]"></i> Detail
                            </a>
                            <button onclick="confirmDelete('{{ $ticket->id }}', '{{ $ticket->ticket_number }}')"
                                class="admin-action-pill admin-action-pill-red" title="Hapus">
                                <i class="fa-solid fa-trash text-[11px]"></i> Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
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

    @endfragment
</div>

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
                    <button type="submit" class="admin-btn admin-btn-danger w-full text-[12px]">
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
</script>

@endsection
