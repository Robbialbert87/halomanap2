@extends('layouts.admin')

@section('title', 'Edit Role - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Edit Role</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Master Data</span>
            <span class="text-gray-400">/</span>
            <a href="{{ route('admin.roles.index') }}" class="text-blue-600 hover:underline">Role</a>
            <span class="text-gray-400">/</span>
            <span>Edit</span>
        </div>
    </div>
    <a href="{{ route('admin.roles.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-shield-halved text-amber-600 text-sm"></i>
        </div>
        <h2 class="font-semibold text-gray-800">Edit Role: <span class="text-blue-600">{{ $role->name }}</span></h2>
    </div>
    <div class="p-6">
        @if($errors->any())
        <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-200 text-sm space-y-1">
            @foreach($errors->all() as $err)
                <div class="flex items-start gap-2"><i class="fa-solid fa-circle-exclamation mt-0.5"></i> {{ $err }}</div>
            @endforeach
        </div>
        @endif

        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kode Role <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode', $role->kode) }}" required
                        {{ $role->kode === 'SUPER_ADMIN' ? 'readonly' : '' }}
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition uppercase font-mono
                        {{ $role->kode === 'SUPER_ADMIN' ? 'bg-gray-50 cursor-not-allowed' : '' }}"
                        placeholder="Contoh: SUPER_ADMIN">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Role <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $role->name) }}" required
                        {{ $role->name === 'Super Admin' ? 'readonly' : '' }}
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                        {{ $role->name === 'Super Admin' ? 'bg-gray-50 cursor-not-allowed' : '' }}">
                    @if($role->name === 'Super Admin')
                    <p class="text-xs text-amber-600 mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-lock"></i> Nama role Super Admin tidak dapat diubah.
                    </p>
                    @endif
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="deskripsi" rows="2"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Keterangan mengenai hak akses role ini">{{ old('deskripsi', $role->deskripsi) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Role</label>
                    <select name="status" {{ $role->name === 'Super Admin' ? 'disabled' : '' }}
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white
                        {{ $role->name === 'Super Admin' ? 'bg-gray-50 cursor-not-allowed' : '' }}">
                        <option value="active" {{ old('status', $role->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $role->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @if($role->name === 'Super Admin')
                        <input type="hidden" name="status" value="active">
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3 pt-6 border-t border-gray-100">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.roles.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
