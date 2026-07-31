@extends('layouts.admin')

@section('title', 'Edit Kategori - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Edit Kategori</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Master Data</span> 
            <span class="text-gray-400">/</span> 
            <a href="{{ route('admin.categories.index') }}" class="hover:text-blue-600">Kategori</a>
            <span class="text-gray-400">/</span> 
            <span>Edit</span>
        </div>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn-ghost">
        Kembali
    </a>
</div>

<div class="admin-card overflow-hidden max-w-2xl">
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')
        <div class="mb-5">
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="admin-input" required>
            @error('name')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="icon" class="block text-sm font-semibold text-gray-700 mb-2">Ikon <span class="text-gray-400 font-normal">(Font Awesome)</span></label>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center" id="icon-preview">
                    <i class="fa-solid {{ old('icon', $category->icon ?? 'fa-tag') }} text-blue-600"></i>
                </div>
                <select name="icon" id="icon" onchange="previewIcon(this.value)"
                        class="admin-input flex-1">
                    <option value="fa-tag" {{ old('icon', $category->icon) == 'fa-tag' ? 'selected' : '' }}>Lainnya</option>
                    <option value="fa-user-doctor" {{ old('icon', $category->icon) == 'fa-user-doctor' ? 'selected' : '' }}>Pelayanan Dokter</option>
                    <option value="fa-user-nurse" {{ old('icon', $category->icon) == 'fa-user-nurse' ? 'selected' : '' }}>Pelayanan Perawat</option>
                    <option value="fa-file-invoice" {{ old('icon', $category->icon) == 'fa-file-invoice' ? 'selected' : '' }}>Administrasi</option>
                    <option value="fa-building" {{ old('icon', $category->icon) == 'fa-building' ? 'selected' : '' }}>Fasilitas</option>
                    <option value="fa-broom" {{ old('icon', $category->icon) == 'fa-broom' ? 'selected' : '' }}>Kebersihan</option>
                    <option value="fa-shield-halved" {{ old('icon', $category->icon) == 'fa-shield-halved' ? 'selected' : '' }}>Keamanan</option>
                    <option value="fa-circle-info" {{ old('icon', $category->icon) == 'fa-circle-info' ? 'selected' : '' }}>Informasi & Komunikasi</option>
                    <option value="fa-star" {{ old('icon', $category->icon) == 'fa-star' ? 'selected' : '' }}>Apresiasi</option>
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
                Perbarui Kategori
            </button>
        </div>
    </form>
</div>
@endsection
