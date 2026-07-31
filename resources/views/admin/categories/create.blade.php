@extends('layouts.admin')

@section('title', 'Tambah Kategori - Halo MANAP')

@section('admin_content')
<x-admin.page-header
    title="Tambah Kategori"
    section="Master Data"
    icon="fa-tag"
    gradient="blue"
>
    <x-slot name="breadcrumb">
        <span class="text-gray-400">/</span>
        <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:underline">Kategori</a>
        <span class="text-gray-400">/</span>
        <span>Tambah</span>
    </x-slot>
    <x-admin.btn href="{{ route('admin.categories.index') }}" variant="ghost" icon="fa-arrow-left">Kembali</x-admin.btn>
</x-admin.page-header>

<x-admin.form-card max-width="max-w-2xl">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="mb-5">
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" class="admin-input" required placeholder="Contoh: Pelayanan Dokter">
            @error('name')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="icon" class="block text-sm font-semibold text-gray-700 mb-2">Ikon <span class="text-gray-400 font-normal">(Font Awesome)</span></label>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center" id="icon-preview">
                    <i class="fa-solid {{ old('icon', 'fa-tag') }} text-blue-600"></i>
                </div>
                <select name="icon" id="icon" onchange="previewIcon(this.value)"
                        class="admin-input flex-1">
                    <option value="fa-tag" {{ old('icon') == 'fa-tag' ? 'selected' : '' }}>Lainnya</option>
                    <option value="fa-user-doctor" {{ old('icon') == 'fa-user-doctor' ? 'selected' : '' }}>Pelayanan Dokter</option>
                    <option value="fa-user-nurse" {{ old('icon') == 'fa-user-nurse' ? 'selected' : '' }}>Pelayanan Perawat</option>
                    <option value="fa-file-invoice" {{ old('icon') == 'fa-file-invoice' ? 'selected' : '' }}>Administrasi</option>
                    <option value="fa-building" {{ old('icon') == 'fa-building' ? 'selected' : '' }}>Fasilitas</option>
                    <option value="fa-broom" {{ old('icon') == 'fa-broom' ? 'selected' : '' }}>Kebersihan</option>
                    <option value="fa-shield-halved" {{ old('icon') == 'fa-shield-halved' ? 'selected' : '' }}>Keamanan</option>
                    <option value="fa-circle-info" {{ old('icon') == 'fa-circle-info' ? 'selected' : '' }}>Informasi & Komunikasi</option>
                    <option value="fa-star" {{ old('icon') == 'fa-star' ? 'selected' : '' }}>Apresiasi</option>
                </select>
            </div>
            @error('icon')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        @push('scripts')
        <script>
        function previewIcon(className) {
            const el = document.querySelector('#icon-preview i');
            if (el) el.className = 'fa-solid ' + className;
        }
        </script>
        @endpush
        
        <div class="flex justify-end">
            <button type="submit" class="admin-btn admin-btn-primary">
                Simpan Kategori
            </button>
        </div>
    </form>
</x-admin.form-card>
@endsection
