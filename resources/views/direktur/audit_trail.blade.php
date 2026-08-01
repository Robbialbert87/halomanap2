@extends('layouts.admin')

@section('title', 'Audit Trail - Halo MANAP')

@section('admin_content')
<x-admin.page-header
    title="Audit Trail"
    section="Direktur"
    crumb="Audit Trail"
    icon="fa-list-check"
    gradient="indigo"
>
    <x-slot name="summary"><i class="fa-solid fa-eye"></i> Mode baca saja</x-slot>
</x-admin.page-header>

{{-- Toolbar Filter & Pencarian (full-width: pencarian di atas, Reset kiri | Filter kanan di bawah, live tanpa reload) --}}
<div class="admin-card overflow-hidden mb-4 md:mb-6">
    <form action="{{ route('direktur.audit-trail') }}" method="GET"
        data-live-filter="#audit-trail-results"
        class="admin-toolbar">
        <div class="relative w-full">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari aksi, model, atau nama user..." autocomplete="off"
                class="admin-input text-[13px] pl-9 w-full">
            @if(request('search'))
            <a href="{{ route('direktur.audit-trail', request()->except(['search', 'page'])) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </a>
            @endif
        </div>
        <div class="flex items-center justify-between gap-3 w-full pt-1">
            <button type="button" data-live-reset class="admin-btn admin-btn-ghost" title="Reset semua filter">
                <i class="fa-solid fa-rotate-left mr-1 text-xs"></i> Reset
            </button>
            <button type="submit" class="admin-btn admin-btn-primary" title="Terapkan filter">
                <i class="fa-solid fa-filter mr-1 text-xs"></i> Filter
            </button>
        </div>
    </form>
</div>

{{-- Hasil: tabel + pagination (di-refresh real-time oleh live filter) --}}
<div id="audit-trail-results" class="admin-live-wrap">
        @fragment('results')
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
                    <td class="px-4 py-3 text-xs whitespace-nowrap">{{ $trail->created_at->format('d/m/Y H:i:s') }}</td>
                    <td class="px-4 py-3">{{ $trail->user?->nama ?? 'System' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                            {{ $trail->action }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $trail->model }}</td>
                    <td class="px-4 py-3 align-top whitespace-normal break-words text-xs text-gray-400 max-w-[320px] leading-snug">{{ json_encode($trail->payload) }}</td>
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
    @if($auditTrails->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $auditTrails->links() }}
    </div>
    @endif
</div>

    @endfragment
</div>
@endsection
