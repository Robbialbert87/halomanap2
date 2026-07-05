@extends('layouts.admin')

@section('title', 'Disposisi - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Disposisi</h1>
        <div class="text-sm text-gray-500 mt-1">Pantau disposisi dari seluruh unit</div>
    </div>
</div>

{{-- Statistik --}}
@php
    $total   = $workflows->total();
    $terlambat = $workflows->filter(fn($w) => $w->due_at && $w->due_at->isPast() && !in_array($w->status, ['selesai','ditutup','menunggu_verifikasi']))->count();
    $menunggu  = $workflows->where('status', 'menunggu_respon')->count();
    $proses    = $workflows->where('status', 'dalam_penanganan')->count();
@endphp
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 border-l-4 border-l-blue-500 shadow-sm">
        <p class="text-xs text-gray-500">Total</p>
        <p class="text-2xl font-bold text-gray-800">{{ $total }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 border-l-4 border-l-yellow-400 shadow-sm">
        <p class="text-xs text-gray-500">Menunggu Respon</p>
        <p class="text-2xl font-bold text-gray-800">{{ $menunggu }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 border-l-4 border-l-indigo-500 shadow-sm">
        <p class="text-xs text-gray-500">Diproses</p>
        <p class="text-2xl font-bold text-gray-800">{{ $proses }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 border-l-4 border-l-red-500 shadow-sm">
        <p class="text-xs text-gray-500">Terlambat</p>
        <p class="text-2xl font-bold {{ $terlambat > 0 ? 'text-red-600' : 'text-gray-800' }}">{{ $terlambat }}</p>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
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

{{-- Tabel --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
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
@endsection