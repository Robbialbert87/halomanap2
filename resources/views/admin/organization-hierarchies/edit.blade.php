@extends('layouts.admin')

@section('title', 'Edit Struktur Organisasi - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Edit Posisi</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Master Data</span>
            <span class="text-gray-400">/</span>
            <a href="{{ route('admin.organization-hierarchies.index', ['unit_id' => $organizationHierarchy->unit_id]) }}" class="text-blue-600 hover:underline">Struktur Organisasi</a>
            <span class="text-gray-400">/</span>
            <span>Edit</span>
        </div>
    </div>
    <a href="{{ route('admin.organization-hierarchies.index', ['unit_id' => $organizationHierarchy->unit_id]) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-diagram-project text-amber-600 text-sm"></i>
        </div>
        <h2 class="font-semibold text-gray-800">Edit Posisi: <span class="text-blue-600">{{ $organizationHierarchy->jabatan->nama }}</span> di <span class="text-blue-600">{{ $organizationHierarchy->unit->nama }}</span></h2>
    </div>
    <div class="p-6">
        @if($errors->any())
        <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-200 text-sm space-y-1">
            @foreach($errors->all() as $err)
                <div class="flex items-start gap-2"><i class="fa-solid fa-circle-exclamation mt-0.5"></i> {{ $err }}</div>
            @endforeach
        </div>
        @endif

        <form action="{{ route('admin.organization-hierarchies.update', $organizationHierarchy->id) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="mb-8 pb-8 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wider">Pemetaan Unit & Jabatan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Unit Kerja <span class="text-red-500">*</span></label>
                        <select name="unit_id" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                            <option value="">-- Pilih Unit --</option>
                            @foreach($units as $u)
                                <option value="{{ $u->id }}" {{ old('unit_id', $organizationHierarchy->unit_id) == $u->id ? 'selected' : '' }}>
                                    {{ $u->nama }} ({{ $u->jenis }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jabatan <span class="text-red-500">*</span></label>
                        <select name="jabatan_id" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach($jabatans as $j)
                                <option value="{{ $j->id }}" {{ old('jabatan_id', $organizationHierarchy->jabatan_id) == $j->id ? 'selected' : '' }}>
                                    [L{{ $j->level }}] {{ $j->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Atasan Langsung (Parent Jabatan)</label>
                        <select name="parent_jabatan_id" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                            <option value="">-- Tidak Ada Atasan --</option>
                            @foreach($jabatans as $j)
                                <option value="{{ $j->id }}" {{ old('parent_jabatan_id', $organizationHierarchy->parent_jabatan_id) == $j->id ? 'selected' : '' }}>
                                    [L{{ $j->level }}] {{ $j->nama }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1.5">Kosongkan jika ini adalah posisi tertinggi (misal: Direktur).</p>
                    </div>
                </div>
            </div>

            <div class="mb-8 pb-8 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wider">Alur Eskalasi (Workflow)</h3>

                <div class="p-4 bg-blue-50 rounded-xl border border-blue-100 mb-6 text-sm text-blue-800 space-y-2">
                    <p><i class="fa-solid fa-lightbulb mr-1.5"></i> <strong>Penjelasan Sederhana:</strong></p>
                    <p>Setiap pengaduan yang masuk ke unit ini akan melewati <strong>beberapa level</strong> sesuai struktur organisasi.</p>
                    <div class="flex flex-wrap gap-3 mt-2">
                        <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded text-xs font-semibold">1. Mulai (Kepala Unit)</span>
                        <span class="text-gray-400 text-xs flex items-center">➡️</span>
                        <span class="bg-amber-100 text-amber-700 px-2.5 py-1 rounded text-xs font-semibold">2. Kabid / Kasi</span>
                        <span class="text-gray-400 text-xs flex items-center">➡️</span>
                        <span class="bg-purple-100 text-purple-700 px-2.5 py-1 rounded text-xs font-semibold">3. Direktur (Ujung)</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Urutan / Level Eskalasi <span class="text-red-500">*</span></label>
                        <input type="number" name="workflow_level" value="{{ old('workflow_level', $organizationHierarchy->workflow_level) }}" min="1" required
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <p class="text-xs text-gray-400 mt-1.5">
                            <strong>Level 1</strong> = posisi paling bawah (yg pertama terima pengaduan).<br>
                            <strong>Angka lebih besar</strong> = posisi lebih tinggi (atasan).
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Atasan Langsung</label>
                        <select name="parent_jabatan_id" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                            <option value="">— Pilih Atasan (jika ada) —</option>
                            @foreach($jabatans as $j)
                                <option value="{{ $j->id }}" {{ old('parent_jabatan_id', $organizationHierarchy->parent_jabatan_id) == $j->id ? 'selected' : '' }}>
                                    [L{{ $j->level }}] {{ $j->nama }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1.5">Kosongkan jika ini posisi tertinggi (Direktur).</p>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="is_workflow_start" value="1" {{ old('is_workflow_start', $organizationHierarchy->is_workflow_start) ? 'checked' : '' }}
                            class="w-4 h-4 accent-blue-600 rounded">
                        <div>
                            <span class="block text-sm font-medium text-gray-700 group-hover:text-green-600 transition-colors">Mulai Workflow — <span class="text-green-600">Pertama Terima Pengaduan</span></span>
                            <span class="block text-xs text-gray-500">Centang jika jabatan ini yang <strong>pertama kali</strong> menerima pengaduan dari Admin (contoh: Kepala Unit).</span>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="is_workflow_end" value="1" {{ old('is_workflow_end', $organizationHierarchy->is_workflow_end) ? 'checked' : '' }}
                            class="w-4 h-4 accent-blue-600 rounded">
                        <div>
                            <span class="block text-sm font-medium text-gray-700 group-hover:text-purple-600 transition-colors">Ujung Eskalasi — <span class="text-purple-600">Puncak / Pengambil Keputusan Akhir</span></span>
                            <span class="block text-xs text-gray-500">Centang jika ini posisi tertinggi (contoh: Direktur). Pengaduan <strong>berhenti</strong> di sini.</span>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="can_escalate" value="1" {{ old('can_escalate', $organizationHierarchy->can_escalate) ? 'checked' : '' }}
                            class="w-4 h-4 accent-blue-600 rounded">
                        <div>
                            <span class="block text-sm font-medium text-gray-700 group-hover:text-blue-600 transition-colors">Bisa Eskalasi — <span class="text-blue-600">Izinkan Meneruskan ke Atasan</span></span>
                            <span class="block text-xs text-gray-500">Centang agar user di jabatan ini bisa menekan tombol "Eskalasi" untuk meneruskan pengaduan ke atasan.</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                <select name="status"
                    class="w-full md:w-1/2 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                    <option value="active" {{ old('status', $organizationHierarchy->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $organizationHierarchy->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="flex items-center gap-3 pt-6 border-t border-gray-100">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.organization-hierarchies.index', ['unit_id' => $organizationHierarchy->unit_id]) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
