@extends('layouts.admin')

@section('title', 'Master Struktur Organisasi - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Master Struktur Organisasi</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Master Data</span>
            <span class="text-gray-400">/</span>
            <span>Struktur Organisasi</span>
        </div>
    </div>
    <a href="{{ route('admin.organization-hierarchies.create', ['unit_id' => request('unit_id')]) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Posisi
    </a>
</div>

@if(session('success'))
<div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-200 flex items-center gap-2">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-200 flex items-center gap-2">
    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="p-4 border-b border-gray-100 bg-gray-50/50">
        <form action="{{ route('admin.organization-hierarchies.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <select name="unit_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white" onchange="this.form.submit()">
                    <option value="">-- Pilih Unit untuk Melihat Struktur Organisasi --</option>
                    @foreach($units as $u)
                        <option value="{{ $u->id }}" {{ request('unit_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->nama }} ({{ $u->jenis }})
                        </option>
                    @endforeach
                </select>
            </div>
            @if(request('unit_id'))
            <a href="{{ route('admin.organization-hierarchies.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded-lg transition-colors flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-xmark"></i> Reset
            </a>
            @endif
        </form>
    </div>

    @if(request('unit_id'))
    <div class="p-4 bg-blue-50/50 border-b border-gray-100">
        <div class="flex items-start gap-4">
            <div class="flex-1">
                <h3 class="text-sm font-semibold text-blue-800 mb-2">
                    <i class="fa-solid fa-circle-info mr-1"></i> Cara Kerja Workflow Disposisi:
                </h3>
                <div class="text-xs text-blue-700 space-y-2">
                    <p>
                        <strong>1. Mulai Workflow</strong> — Centang pada jabatan yang pertama menerima pengaduan (contoh: Kepala Unit).<br>
                        Saat Admin mendisposisikan tiket ke unit ini, otomatis masuk ke jabatan yang dicentang.
                    </p>
                    <p>
                        <strong>2. Atasan Langsung</strong> — Pilih siapa atasan dari jabatan ini.<br>
                        Saat user menekan tombol "Eskalasi", pengaduan naik ke atasan langsung yang dipilih.
                    </p>
                    <p>
                        <strong>3. Ujung Eskalasi</strong> — Centang pada jabatan puncak (contoh: Direktur).<br>
                        Pengaduan tidak bisa dieskalasi lagi dan berhenti di sini.
                    </p>
                </div>
            </div>
            <div class="hidden md:block flex-shrink-0 bg-white/80 rounded-lg p-3 border border-blue-200">
                <div class="text-[10px] font-mono text-blue-700 leading-relaxed whitespace-nowrap">
                    <div class="text-center font-bold text-blue-900 mb-1">ALUR ESKALASI</div>
                    <div class="flex flex-col items-center">
                        <span class="bg-purple-100 text-purple-700 px-3 py-0.5 rounded text-[10px] font-bold">Level 3</span>
                        <span class="text-[8px] my-0.5">⬆️ Direktur (Ujung)</span>
                        <span class="text-blue-300">│</span>
                        <span class="bg-amber-100 text-amber-700 px-3 py-0.5 rounded text-[10px] font-bold">Level 2</span>
                        <span class="text-[8px] my-0.5">⬆️ Kabid / Kasi</span>
                        <span class="text-blue-300">│</span>
                        <span class="bg-green-100 text-green-700 px-3 py-0.5 rounded text-[10px] font-bold">Level 1</span>
                        <span class="text-[8px] my-0.5">⬆️ Kepala Unit (Mulai)</span>
                        <span class="text-blue-300">│</span>
                        <span class="text-gray-400 text-[8px]">📥 Disposisi Admin</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50 text-gray-800 font-semibold border-b border-gray-100 uppercase text-xs">
            <tr>
                <th class="px-6 py-4 w-12 text-center"></th>
                <th class="px-6 py-4">Jabatan</th>
                <th class="px-6 py-4">Atasan Langsung</th>
                <th class="px-6 py-4 text-center">Level Eskalasi</th>
                <th class="px-6 py-4 text-center">Atribut</th>
                <th class="px-6 py-4 text-center">Status</th>
                <th class="px-6 py-4 w-28 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($hierarchies as $h)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold
                        {{ $h->is_workflow_start ? 'bg-green-100 text-green-700' : ($h->is_workflow_end ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-500') }}">
                        {{ $h->workflow_level }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-900">{{ $h->jabatan?->nama ?? '<Dihapus>' }}</div>
                    @if(!request('unit_id'))
                    <div class="text-xs text-gray-500 mt-0.5"><i class="fa-solid fa-building mr-1"></i> {{ $h->unit?->nama ?? '<Dihapus>' }}</div>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    @if($h->parentJabatan)
                        <span class="flex items-center gap-1.5">
                            <i class="fa-solid fa-level-up-alt text-[10px] text-blue-400"></i>
                            {{ $h->parentJabatan->nama }}
                        </span>
                    @else
                        <span class="text-gray-300 text-xs italic">— Tidak Ada Atasan —</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex flex-col items-center gap-0.5">
                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-mono text-xs border border-slate-200">
                            Level {{ $h->workflow_level }}
                        </span>
                        @if($h->is_workflow_start && $h->is_workflow_end)
                            <span class="text-[9px] text-purple-500 font-semibold">⬇ Mulai &amp; Ujung ⬆</span>
                        @elseif($h->is_workflow_start)
                            <span class="text-[9px] text-green-500 font-semibold">⬇ Mulai Disposisi</span>
                        @elseif($h->is_workflow_end)
                            <span class="text-[9px] text-purple-500 font-semibold">⬆ Ujung Eskalasi</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 text-center space-y-1">
                    @if($h->is_workflow_start)
                        <span class="block px-2 py-0.5 rounded text-[10px] font-semibold bg-green-100 text-green-700">Mulai Workflow</span>
                    @endif
                    @if($h->is_workflow_end)
                        <span class="block px-2 py-0.5 rounded text-[10px] font-semibold bg-purple-100 text-purple-700">Ujung Eskalasi</span>
                    @endif
                    @if(!$h->can_escalate)
                        <span class="block px-2 py-0.5 rounded text-[10px] font-semibold bg-red-100 text-red-700">Tidak Bisa Eskalasi</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    @if($h->status === 'active')
                        <span class="w-2 h-2 rounded-full bg-green-500 inline-block shadow-sm shadow-green-200" title="Aktif"></span>
                    @else
                        <span class="w-2 h-2 rounded-full bg-gray-400 inline-block" title="Nonaktif"></span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-1">
                        <a href="{{ route('admin.organization-hierarchies.edit', $h->id) }}"
                           class="text-blue-600 hover:text-blue-800 p-1.5 rounded-md hover:bg-blue-50 transition-colors"
                           title="Edit">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.organization-hierarchies.destroy', $h->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus hierarki ini?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 p-1.5 rounded-md hover:bg-red-50 transition-colors" title="Hapus">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center gap-2 text-gray-400">
                        <i class="fa-solid fa-diagram-project text-3xl"></i>
                        <p>Belum ada data struktur organisasi untuk unit ini.</p>
                        @if(request('unit_id'))
                        <a href="{{ route('admin.organization-hierarchies.create', ['unit_id' => request('unit_id')]) }}" class="text-blue-600 hover:underline text-sm">Tambah Posisi Pertama</a>
                        @endif
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($hierarchies->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $hierarchies->links() }}
    </div>
    @endif
</div>
@endsection
