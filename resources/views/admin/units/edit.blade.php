@extends('layouts.admin')

@section('title', 'Edit Unit - Halo MANAP')

@section('admin_content')
<x-admin.page-header
    title="Edit Unit"
    section="Master Data"
    icon="fa-building"
    gradient="amber"
>
    <x-slot name="breadcrumb">
        <span class="text-gray-400">/</span>
        <a href="{{ route('admin.units.index') }}" class="text-blue-600 hover:underline">Unit</a>
        <span class="text-gray-400">/</span>
        <span>Edit</span>
    </x-slot>
    <x-admin.btn href="{{ route('admin.units.index') }}" variant="ghost" icon="fa-arrow-left">Kembali</x-admin.btn>
</x-admin.page-header>

<x-admin.form-card title='Edit Unit: <span class="text-blue-600">{{ $unit->nama }}</span>' icon="fa-building" icon-class="bg-amber-100 text-amber-600">
    <x-admin.errors />
<form action="{{ route('admin.units.update', $unit->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kode Unit <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode', $unit->kode) }}" required
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition font-mono uppercase"
                        placeholder="Contoh: UN_RADIOLOGI">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Unit <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $unit->nama) }}" required
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Contoh: Instalasi Radiologi">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Unit <span class="text-red-500">*</span></label>
                    <select name="jenis" required
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                        <option value="">-- Pilih Jenis --</option>
                        @foreach($jenisList as $j)
                            <option value="{{ $j }}" {{ old('jenis', $unit->jenis) == $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                    <select name="status"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                        <option value="active" {{ old('status', $unit->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $unit->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-6 border-t border-gray-100">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.units.index') }}" class="admin-btn admin-btn-ghost">
                    Batal
                </a>
            </div>
        </form>
</x-admin.form-card>
@endsection
