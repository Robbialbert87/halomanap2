@extends('layouts.admin')

@section('title', 'Audit Trail - Halo MANAP')

@section('admin_content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Audit Trail</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Direktur</span>
            <span class="text-gray-400">/</span>
            <span>Audit Trail</span>
        </div>
        <p class="text-xs text-gray-400 mt-1"><i class="fa-solid fa-eye"></i> Mode baca saja</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left text-gray-500 font-semibold">
                    <th class="px-4 py-3">Waktu</th>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Aksi</th>
                    <th class="px-4 py-3">Model</th>
                    <th class="px-4 py-3">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($auditTrails as $trail)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-xs">{{ $trail->created_at->format('d/m/Y H:i:s') }}</td>
                    <td class="px-4 py-3">{{ $trail->user?->nama ?? 'System' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                            {{ $trail->action }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $trail->model }}</td>
                    <td class="px-4 py-3 text-xs text-gray-400 max-w-xs truncate">{{ json_encode($trail->payload) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-4xl mb-3"></i>
                        <p>Belum ada data audit trail</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $auditTrails->links() }}
    </div>
</div>
@endsection
