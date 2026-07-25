@extends('layouts.admin')

@section('title', 'Tambah Kategori - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Tambah Kategori</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Master Data</span> 
            <span class="text-gray-400">/</span> 
            <a href="{{ route('admin.categories.index') }}" class="hover:text-blue-600">Kategori</a>
            <span class="text-gray-400">/</span> 
            <span>Tambah</span>
        </div>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
        Kembali
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-2xl">
    <form action="{{ route('admin.categories.store') }}" method="POST" class="p-6">
        @csrf
        <div class="mb-5">
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required placeholder="Contoh: Pelayanan Dokter">
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
                        class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
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
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-6 py-2.5 transition-colors">
                Simpan Kategori
            </button>
        </div>
    </form>
</div>
@endsection
