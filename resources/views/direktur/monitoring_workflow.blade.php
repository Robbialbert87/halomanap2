@extends('layouts.admin')

@section('title', 'Monitoring Pengaduan - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Monitoring Pengaduan</h1>
        <div class="text-sm text-gray-500 mt-1">Daftar seluruh pengaduan — klik nomor tiket untuk lihat detail & workflow</div>
    </div>
</div>

{{-- Statistik cepat --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 border-l-4 border-l-blue-500">
        <p class="text-xs text-gray-500">Total</p>
        <p class="text-xl font-bold text-gray-800">{{ $counts['total'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 border-l-4 border-l-yellow-400">
        <p class="text-xs text-gray-500">Baru</p>
        <p class="text-xl font-bold text-gray-800">{{ $counts['baru'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 border-l-4 border-l-indigo-500">
        <p class="text-xs text-gray-500">Diproses</p>
        <p class="text-xl font-bold text-gray-800">{{ $counts['diproses'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 border-l-4 border-l-green-500">
        <p class="text-xs text-gray-500">Selesai</p>
        <p class="text-xl font-bold text-gray-800">{{ $counts['selesai'] }}</p>
    </div>
</div>

{{-- Filter + Search --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" class="flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tiket / Judul / Pelapor" class="w-48 bg-gray-50 border border-gray-300 text-sm rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
            <select name="status" class="w-40 bg-gray-50 border border-gray-300 text-sm rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua</option>
                @foreach($statuses as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Unit</label>
            <select name="unit_id" class="w-52 bg-gray-50 border border-gray-300 text-sm rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg"><i class="fa-solid fa-filter mr-1"></i> Filter</button>
        <a href="{{ route('direktur.monitoring-workflow') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg"><i class="fa-solid fa-rotate-right mr-1"></i> Reset</a>
    </form>
</div>

{{-- Tabel Pengaduan --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left text-gray-500 font-semibold text-xs uppercase tracking-wider">
                    <th class="px-4 py-3">Tiket</th>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Unit</th>
                    <th class="px-4 py-3">Pelapor</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Tgl</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tickets as $t)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.monitoring.ticket.show', $t->id) }}" class="font-mono text-blue-600 hover:underline font-bold">{{ $t->ticket_number }}</a>
                    </td>
                    <td class="px-4 py-3 max-w-[220px] truncate font-medium text-gray-800">{{ $t->title }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $t->room?->unit?->nama ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $t->reporter_name ?? ($t->is_anonymous ? 'Anonim' : '-') }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded
                            @if($t->status == 'Baru' || $t->status == 'NEW') bg-blue-100 text-blue-700
                            @elseif($t->status == 'Diproses' || $t->status == 'IN_PROGRESS') bg-amber-100 text-amber-700
                            @elseif($t->status == 'Selesai') bg-green-100 text-green-700
                            @elseif($t->status == 'TERVERIFIKASI') bg-teal-100 text-teal-700
                            @else bg-gray-100 text-gray-600 @endif">
                            {{ $t->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-gray-500">{{ $t->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.monitoring.ticket.show', $t->id) }}" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fa-regular fa-eye"></i> Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-12 text-center text-gray-400"><i class="fa-regular fa-file-lines text-3xl mb-2"></i><p>Belum ada pengaduan</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tickets->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">{{ $tickets->links() }}</div>
    @endif
</div>
@endsection