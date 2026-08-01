@extends('layouts.admin')

@section('title', 'Tambah Ruangan - Halo MANAP')

@section('admin_content')
<x-admin.page-header
    title="Tambah Ruangan"
    section="Master Data"
    icon="fa-door-open"
    gradient="blue"
>
    <x-slot name="breadcrumb">
        <span class="text-gray-400">/</span>
        <a href="{{ route('admin.rooms.index') }}" class="text-blue-600 hover:underline">Ruangan</a>
        <span class="text-gray-400">/</span>
        <span>Tambah</span>
    </x-slot>
    <x-admin.btn href="{{ route('admin.rooms.index') }}" variant="ghost" icon="fa-arrow-left">Kembali</x-admin.btn>
</x-admin.page-header>

<x-admin.form-card>
    <form action="{{ route('admin.rooms.store') }}" method="POST">
        @csrf
        
        <div class="mb-5">
            <label for="unit_id" class="block text-sm font-semibold text-gray-700 mb-2">Unit Terkait <span class="text-red-500">*</span></label>
            <select name="unit_id" id="unit_id" class="admin-input" required>
                <option value="">-- Pilih Unit --</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama }}</option>
                @endforeach
            </select>
            @error('unit_id')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Ruangan <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" class="admin-input" required placeholder="Contoh: Poli Umum">
            @error('name')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="admin-btn admin-btn-primary">
                Simpan Ruangan
            </button>
        </div>
    </form>
</x-admin.form-card>
@endsection
