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

@push('styles')
<link rel="preconnect" href="https://cdn.datatables.net">
<link rel="stylesheet" href="https://cdn.datatables.net/3.0.1/css/dataTables.tailwindcss.min.css">
<style>
    .dt-container { color: #374151; }
    .dt-container input.dt-input,
    .dt-container select {
        background-color: #ffffff !important;
        color: #374151 !important;
    }
    .dt-container .dt-search .dt-input {
        font-size: 13px;
        line-height: 1.5;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        width: 260px;
        max-width: 100%;
        outline: none;
    }
    .dt-container .dt-search .dt-input::placeholder {
        color: #9ca3af;
        font-size: 13px;
    }
    .dt-container .dt-search .dt-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    .dt-container .dt-length select {
        font-size: 13px;
        padding: 6px 10px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
    }
    .dt-container .dt-info {
        font-size: 12px;
        color: #6b7280;
    }
    #tickets-table thead th {
        background-color: #f9fafb !important;
        color: #374151 !important;
    }
    #tickets-table tbody tr {
        transition: background-color 0.15s ease-in-out;
    }
    #tickets-table tbody tr:hover,
    #tickets-table tbody tr:hover > td {
        background-color: #f3f4f6 !important;
    }
    .dt-container .dt-paging button {
        color: #374151 !important;
        background-color: #ffffff !important;
    }
    .dt-container .dt-paging button[aria-current="page"] {
        background-color: #eff6ff !important;
        color: #2563eb !important;
        font-weight: 600;
    }
</style>
@endpush

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
                class="w-full bg-white/70 md:bg-gray-50 border border-gray-200 md:border-gray-300 text-gray-900 text-[13px] rounded-xl md:rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 md:p-2.5 pl-9"
                id="mobile-search-input">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            @if(request('search'))
            <a href="{{ route('admin.tickets.index', request()->except(['search', 'page'])) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </a>
            @endif
        </div>
        <div class="md:min-w-[140px]">
            <select name="status" class="w-full bg-white/70 md:bg-gray-50 border border-gray-200 md:border-gray-300 text-gray-900 text-[13px] rounded-xl md:rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 md:p-2.5">
                <option value="">Semua Status</option>
                <option value="NEW" {{ request('status') == 'NEW' ? 'selected' : '' }}>Baru</option>
                <option value="TERVERIFIKASI" {{ request('status') == 'TERVERIFIKASI' ? 'selected' : '' }}>Terverifikasi</option>
                <option value="IN_PROGRESS" {{ request('status') == 'IN_PROGRESS' ? 'selected' : '' }}>Diproses</option>
                <option value="DONE" {{ request('status') == 'DONE' ? 'selected' : '' }}>Selesai</option>
                <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div class="md:min-w-[130px]">
            <select name="type" class="w-full bg-white/70 md:bg-gray-50 border border-gray-200 md:border-gray-300 text-gray-900 text-[13px] rounded-xl md:rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 md:p-2.5">
                <option value="">Semua Jenis</option>
                <option value="Pengaduan" {{ request('type') == 'Pengaduan' ? 'selected' : '' }}>Pengaduan</option>
                <option value="Survei" {{ request('type') == 'Survei' ? 'selected' : '' }}>Survei</option>
                <option value="Apresiasi" {{ request('type') == 'Apresiasi' ? 'selected' : '' }}>Apresiasi</option>
                <option value="Informasi" {{ request('type') == 'Informasi' ? 'selected' : '' }}>Informasi</option>
            </select>
        </div>
        <div class="md:min-w-[180px]">
            <select name="unit_id" class="w-full bg-white/70 md:bg-gray-50 border border-gray-200 md:border-gray-300 text-gray-900 text-[13px] rounded-xl md:rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 md:p-2.5">
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
        @include('admin.tickets._mobile_list')
    </div>
</div>

{{-- Table (Desktop) --}}
<div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto p-4">
        <table id="tickets-table" class="w-full text-left text-sm text-gray-600">
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
            <tbody></tbody>
            <tfoot class="bg-gray-50 border-t border-gray-100">
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class="px-3 py-2 align-top">
                        <select data-column="3" class="w-full border border-gray-300 rounded-lg text-xs px-2 py-1.5 text-gray-700 bg-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Unit</option>
                            @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                            @endforeach
                        </select>
                    </th>
                    <th class="px-3 py-2 align-top">
                        <select data-column="4" class="w-full border border-gray-300 rounded-lg text-xs px-2 py-1.5 text-gray-700 bg-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </th>
                    <th class="px-3 py-2 align-top">
                        <select data-column="5" class="w-full border border-gray-300 rounded-lg text-xs px-2 py-1.5 text-gray-700 bg-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Jenis</option>
                            <option value="Pengaduan">Pengaduan</option>
                            <option value="Survei">Survei</option>
                            <option value="Apresiasi">Apresiasi</option>
                            <option value="Informasi">Informasi</option>
                        </select>
                    </th>
                    <th class="px-3 py-2 align-top">
                        <select data-column="6" class="w-full border border-gray-300 rounded-lg text-xs px-2 py-1.5 text-gray-700 bg-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="NEW">Baru</option>
                            <option value="TERVERIFIKASI">Terverifikasi</option>
                            <option value="IN_PROGRESS">Diproses</option>
                            <option value="DONE">Selesai</option>
                            <option value="REJECTED">Ditolak</option>
                        </select>
                    </th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- Mobile Load More --}}
<div class="block md:hidden mt-4" id="mobile-load-more">
    <div class="flex items-center justify-center text-xs text-gray-400 mb-2">
        <span id="mobile-ticket-count">Menampilkan <b>{{ $tickets->total() > 0 ? $tickets->lastItem() : 0 }}</b> dari <b>{{ $tickets->total() }}</b> pengaduan</span>
    </div>
    @if($tickets->hasMorePages())
    <button id="mobile-load-more-btn" type="button"
        class="w-full bg-white rounded-2xl border border-gray-200 text-blue-600 font-semibold py-3 text-[13px] transition-all active:scale-[0.98] shadow-sm flex items-center justify-center gap-2">
        <i class="fa-solid fa-arrow-down text-xs"></i> Muat Lebih Banyak
    </button>
    @endif
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

@push('scripts')
<script src="https://cdn.datatables.net/3.0.1/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/3.0.1/js/dataTables.tailwindcss.min.js"></script>
<script>
(function () {
    const statusMap = @json($statusMap);
    const typeMap = @json($typeMap);

    function esc(str) {
        return String(str ?? '').replace(/[&<>"']/g, function (m) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m];
        });
    }

    const table = new DataTable('#tickets-table', {
        serverSide: true,
        processing: true,
        ajax: {
            url: '{{ route('admin.tickets.data-table') }}'
        },
        order: [[7, 'desc']],
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Semua']],
        columns: [
            {
                data: 'ticket_number',
                render: function (data, type, row) {
                    return '<span class="font-mono font-semibold text-xs text-blue-700 bg-blue-50 px-2 py-1 rounded">' + esc(data) + '</span>';
                }
            },
            {
                data: 'reporter_name',
                render: function (data, type, row) {
                    if (row.is_anonymous) {
                        return '<span class="flex items-center gap-1 text-gray-400 text-xs italic"><i class="fa-solid fa-user-secret"></i> Anonim</span>';
                    }
                    return '<div class="font-medium text-gray-800 text-xs">' + esc(row.reporter_name) + '</div>' +
                           '<div class="text-gray-400 text-xs">' + esc(row.reporter_phone) + '</div>';
                }
            },
            {
                data: 'title',
                render: function (data, type, row) {
                    return '<p class="font-medium text-gray-800 text-xs truncate" style="max-width:180px" title="' + esc(row.title) + '">' + esc(row.title) + '</p>';
                }
            },
            {
                data: 'unit_nama',
                render: function (data, type, row) {
                    return '<div class="text-xs font-semibold text-gray-800">' + esc(row.unit_nama) + '</div>' +
                           '<div class="text-xs text-gray-500">' + esc(row.room_name) + '</div>';
                }
            },
            {
                data: 'category_name',
                render: function (data, type, row) {
                    return '<span class="text-xs text-gray-700">' + esc(row.category_name) + '</span>';
                }
            },
            {
                data: 'type',
                render: function (data, type, row) {
                    const label = row.type === 'Saran' ? 'Survei' : row.type;
                    const cls = typeMap[row.type] || 'bg-gray-100 text-gray-700';
                    return '<span class="inline-flex text-xs font-semibold px-2 py-0.5 rounded-full ' + cls + '">' + label + '</span>';
                }
            },
            {
                data: 'status',
                render: function (data, type, row) {
                    const s = statusMap[row.status] || { label: row.status, class: 'bg-gray-100 text-gray-700' };
                    return '<span class="inline-flex text-xs font-semibold px-2.5 py-1 rounded-full ' + s.class + '">' + s.label + '</span>';
                }
            },
            {
                data: 'created_date',
                render: function (data, type, row) {
                    return '<span class="whitespace-nowrap text-xs text-gray-500">' + esc(row.created_date) + '<br>' + esc(row.created_time) + '</span>';
                }
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return '<div class="flex items-center justify-center gap-1.5">' +
                        '<a href="/admin/tickets/' + row.id + '" class="bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"><i class="fa-solid fa-eye mr-1"></i> Detail</a>' +
                        '<button onclick="confirmDelete(' + row.id + ', \'' + esc(row.ticket_number) + '\')" class="bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"><i class="fa-solid fa-trash"></i></button>' +
                        '</div>';
                }
            }
        ],
        layout: {
            topStart: 'pageLength',
            topEnd: 'search',
            bottomStart: 'info',
            bottomEnd: 'paging'
        },
        language: {
            search: '',
            searchPlaceholder: 'Cari no. tiket / judul / pelapor...',
            lengthMenu: 'Tampilkan _MENU_',
            info: 'Menampilkan _START_–_END_ dari _TOTAL_ pengaduan',
            infoEmpty: 'Tidak ada data',
            infoFiltered: '(difilter dari _MAX_ total)',
            zeroRecords: 'Tidak ada pengaduan yang cocok.',
            emptyTable: 'Belum ada data pengaduan.',
            loadingRecords: 'Memuat...',
            processing: 'Memuat...',
            paginate: { first: '«', last: '»', next: '›', previous: '‹' }
        }
    });

    document.querySelectorAll('#tickets-table tfoot select').forEach(function (sel) {
        sel.addEventListener('change', function () {
            table.column(parseInt(sel.dataset.column, 10)).search(sel.value).draw();
        });
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 768) table.columns.adjust();
    });

    const loadMoreBtn = document.getElementById('mobile-load-more-btn');
    if (loadMoreBtn) {
        let page = 1;
        const form = document.querySelector('form[action="{{ route('admin.tickets.index') }}"]');

        loadMoreBtn.addEventListener('click', function () {
            const btn = loadMoreBtn;
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Memuat...';

            const params = new URLSearchParams({ page: page + 1 });
            if (form) {
                ['search', 'status', 'type', 'unit_id'].forEach(function (name) {
                    const el = form.querySelector('[name="' + name + '"]');
                    if (el && el.value) params.set(name, el.value);
                });
            }

            fetch('{{ route('admin.tickets.mobile-search') }}?' + params.toString())
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    const list = document.getElementById('mobile-ticket-list');
                    if (list) {
                        const wrap = list.querySelector('div') || list;
                        wrap.insertAdjacentHTML('beforeend', res.html);
                    }
                    page = res.next_page - 1;

                    const countEl = document.getElementById('mobile-ticket-count');
                    if (countEl) {
                        countEl.innerHTML = 'Menampilkan <b>' + res.shown + '</b> dari <b>' + res.total + '</b> pengaduan';
                    }

                    if (!res.has_more) {
                        btn.remove();
                    } else {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-arrow-down text-xs"></i> Muat Lebih Banyak';
                    }
                })
                .catch(function () {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-arrow-down text-xs"></i> Muat Lebih Banyak';
                });
        });
    }
})();
</script>
@endpush

@endsection
