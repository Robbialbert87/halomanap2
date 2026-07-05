@extends('layouts.admin')

@section('title', 'Statistik Pengaduan - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Statistik Pengaduan</h1>
        <div class="text-sm text-gray-500 mt-1">Rekap data pengaduan</div>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 border-l-4 border-l-blue-500">
        <p class="text-xs text-gray-500">Total</p>
        <p class="text-2xl font-bold text-gray-800">{{ $totalTickets }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 border-l-4 border-l-yellow-400">
        <p class="text-xs text-gray-500">Baru</p>
        <p class="text-2xl font-bold text-gray-800">{{ $baru }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 border-l-4 border-l-indigo-500">
        <p class="text-xs text-gray-500">Diproses</p>
        <p class="text-2xl font-bold text-gray-800">{{ $diproses }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 border-l-4 border-l-green-500">
        <p class="text-xs text-gray-500">Selesai</p>
        <p class="text-2xl font-bold text-gray-800">{{ $selesai }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 border-l-4 border-l-red-500">
        <p class="text-xs text-gray-500">SLA Terlambat</p>
        <p class="text-2xl font-bold {{ $slaBreach > 0 ? 'text-red-600' : 'text-gray-800' }}">{{ $slaBreach }}</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800 flex items-center gap-2"><i class="fa-regular fa-building text-blue-500"></i> Disposisi per Unit</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left text-gray-500 font-semibold text-xs uppercase tracking-wider">
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Unit</th>
                    <th class="px-4 py-3">Total Disposisi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($perUnit as $i => $u)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                    <td class="px-4 py-3 font-medium">{{ $u['unit'] }}</td>
                    <td class="px-4 py-3">{{ $u['total'] }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-4 py-10 text-center text-gray-400">Belum ada data disposisi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection