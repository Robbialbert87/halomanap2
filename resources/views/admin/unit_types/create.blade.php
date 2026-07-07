@extends('layouts.admin')

@section('title', 'Tambah Jenis Unit - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Tambah Jenis Unit</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Master Data</span>
            <span class="text-gray-400">/</span>
            <a href="{{ route('admin.unit-types.index') }}" class="hover:text-blue-600">Jenis Unit</a>
            <span class="text-gray-400">/</span>
            <span>Tambah</span>
        </div>
    </div>
    <a href="{{ route('admin.unit-types.index') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
        Kembali
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-2xl">
    <form action="{{ route('admin.unit-types.store') }}" method="POST" class="p-6">
        @csrf

        <div class="mb-5">
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Jenis Unit <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                required placeholder="Contoh: Instalasi">
            @error('name')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="color" class="block text-sm font-semibold text-gray-700 mb-2">Warna (Opsional)</label>
            <div class="flex items-center gap-3">
                <input type="color" name="color" id="color" value="{{ old('color', '#3b82f6') }}"
                    class="w-10 h-10 rounded border border-gray-300 cursor-pointer">
                <input type="text" name="color_preview" value="{{ old('color', '#3b82f6') }}"
                    class="w-32 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 font-mono"
                    placeholder="#3b82f6" oninput="document.getElementById('color').value=this.value">
            </div>
            @error('color')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="text-sm text-gray-700">Aktif</span>
            </label>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-6 py-2.5 transition-colors">
                Simpan Jenis Unit
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('color').addEventListener('input', function() {
    document.querySelector('[name="color_hex"]').value = this.value;
});
</script>
@endsection
