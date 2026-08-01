@extends('layouts.admin')

@section('title', 'Edit Jenis Unit - Halo MANAP')

@section('admin_content')
<x-admin.page-header
    title="Edit Jenis Unit"
    section="Master Data"
    icon="fa-layer-group"
    gradient="amber"
>
    <x-slot name="breadcrumb">
        <span class="text-gray-400">/</span>
        <a href="{{ route('admin.unit-types.index') }}" class="text-blue-600 hover:underline">Jenis Unit</a>
        <span class="text-gray-400">/</span>
        <span>Edit</span>
    </x-slot>
    <x-admin.btn href="{{ route('admin.unit-types.index') }}" variant="ghost" icon="fa-arrow-left">Kembali</x-admin.btn>
</x-admin.page-header>

<x-admin.form-card>
    <form action="{{ route('admin.unit-types.update', $unitType->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-5">
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Jenis Unit <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $unitType->name) }}"
                class="admin-input"
                required placeholder="Contoh: Instalasi">
            @error('name')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="color" class="block text-sm font-semibold text-gray-700 mb-2">Warna (Opsional)</label>
            <div class="flex items-center gap-3">
                <input type="color" name="color" id="color" value="{{ old('color', $unitType->color ?? '#3b82f6') }}"
                    class="w-10 h-10 rounded border border-gray-300 cursor-pointer">
                <input type="text" name="color_preview" value="{{ old('color', $unitType->color ?? '#3b82f6') }}"
                    class="admin-input w-32 font-mono"
                    placeholder="#3b82f6" oninput="document.getElementById('color').value=this.value">
            </div>
            @error('color')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $unitType->is_active) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="text-sm text-gray-700">Aktif</span>
            </label>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="admin-btn admin-btn-primary">
                Perbarui Jenis Unit
            </button>
        </div>
    </form>
</x-admin.form-card>

<script>
document.getElementById('color').addEventListener('input', function() {
    document.querySelector('[name="color_preview"]').value = this.value;
});
</script>
@endsection
