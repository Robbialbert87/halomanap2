@extends('layouts.admin')

@section('title', 'Tambah Ruangan - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Tambah Ruangan</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Master Data</span> 
            <span class="text-gray-400">/</span> 
            <a href="{{ route('admin.rooms.index') }}" class="hover:text-blue-600">Ruangan</a>
            <span class="text-gray-400">/</span> 
            <span>Tambah</span>
        </div>
    </div>
    <a href="{{ route('admin.rooms.index') }}" class="admin-btn admin-btn-ghost">
        Kembali
    </a>
</div>

<div class="admin-card overflow-hidden max-w-2xl">
    <form action="{{ route('admin.rooms.store') }}" method="POST" class="p-6">
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
</div>
@endsection
