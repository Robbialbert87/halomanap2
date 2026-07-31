@extends('layouts.admin')

@section('title', 'Tambah Jabatan - Halo MANAP')

@section('admin_content')
<x-admin.page-header
    title="Tambah Jabatan"
    section="Master Data"
    icon="fa-sitemap"
    gradient="blue"
>
    <x-slot name="breadcrumb">
        <span class="text-gray-400">/</span>
        <a href="{{ route('admin.jabatans.index') }}" class="text-blue-600 hover:underline">Jabatan</a>
        <span class="text-gray-400">/</span>
        <span>Tambah</span>
    </x-slot>
    <x-admin.btn href="{{ route('admin.jabatans.index') }}" variant="ghost" icon="fa-arrow-left">Kembali</x-admin.btn>
</x-admin.page-header>

<x-admin.form-card title="Informasi Jabatan Baru" icon="fa-sitemap" icon-class="bg-blue-100 text-blue-600">
    <x-admin.errors />
<form action="{{ route('admin.jabatans.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Jabatan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Contoh: Kepala Instalasi Radiologi">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori Jabatan <span class="text-red-500">*</span></label>
                    <select name="kategori_jabatan" required
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoriList as $k)
                            <option value="{{ $k }}" {{ old('kategori_jabatan') == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                    <select name="status"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-6 border-t border-gray-100">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Jabatan
                </button>
                <a href="{{ route('admin.jabatans.index') }}" class="admin-btn admin-btn-ghost">
                    Batal
                </a>
            </div>
        </form>
</x-admin.form-card>
@endsection
