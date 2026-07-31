@extends('layouts.admin')

@section('title', 'Edit Role - Halo MANAP')

@section('admin_content')
<x-admin.page-header
    title="Edit Role"
    section="Master Data"
    icon="fa-shield-halved"
    gradient="amber"
>
    <x-slot name="breadcrumb">
        <span class="text-gray-400">/</span>
        <a href="{{ route('admin.roles.index') }}" class="text-blue-600 hover:underline">Role</a>
        <span class="text-gray-400">/</span>
        <span>Edit</span>
    </x-slot>
    <x-admin.btn href="{{ route('admin.roles.index') }}" variant="ghost" icon="fa-arrow-left">Kembali</x-admin.btn>
</x-admin.page-header>

<x-admin.form-card title="Edit Role: <span class='text-blue-600'>{{ $role->name }}</span>" icon="fa-shield-halved" icon-class="bg-amber-100 text-amber-600">
    <x-admin.errors />

    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kode Role <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode', $role->kode) }}" required
                        {{ $role->kode === 'SUPER_ADMIN' ? 'readonly' : '' }}
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition uppercase font-mono
                        {{ $role->kode === 'SUPER_ADMIN' ? 'bg-gray-50 cursor-not-allowed' : '' }}"
                        placeholder="Contoh: EDITOR">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Role <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $role->name) }}" required
                        {{ $role->name === 'Super Admin' ? 'readonly' : '' }}
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition
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
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="Keterangan mengenai hak akses role ini">{{ old('deskripsi', $role->deskripsi) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Role</label>
                    <select name="status" {{ $role->name === 'Super Admin' ? 'disabled' : '' }}
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white
                        {{ $role->name === 'Super Admin' ? 'bg-gray-50 cursor-not-allowed' : '' }}">
                        <option value="active" {{ old('status', $role->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $role->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @if($role->name === 'Super Admin')
                        <input type="hidden" name="status" value="active">
                    @endif
                </div>
            </div>

            <div id="permissions" class="mb-6 pt-6 border-t border-gray-100 scroll-mt-24">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wider">Assign Permissions</h3>
                @php
                    $groups = $permissions->groupBy(function($p) {
                        $parts = explode('.', $p->name);
                        return $parts[0] . '.' . ($parts[1] ?? '');
                    });
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($groups as $group => $perms)
                    <div class="border border-gray-200 rounded-xl p-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-3 pb-2 border-b border-gray-100">{{ $group }}</p>
                        <div class="space-y-2">
                            @foreach($perms as $perm)
                            <label class="flex items-start gap-2.5 cursor-pointer group">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                                    {{ in_array($perm->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}
                                    {{ $role->name === 'Super Admin' ? 'disabled' : '' }}
                                    class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500
                                    {{ $role->name === 'Super Admin' ? 'opacity-60 cursor-not-allowed' : '' }}">
                                <span class="text-sm text-gray-700 group-hover:text-gray-900 transition-colors">{{ $perm->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($role->name === 'Super Admin')
                <p class="text-xs text-amber-600 mt-3 flex items-center gap-1">
                    <i class="fa-solid fa-lock"></i> Permission Super Admin tidak dapat diubah.
                </p>
                @endif
            </div>

            <div class="flex items-center gap-3 pt-6 border-t border-gray-100">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.roles.index') }}" class="admin-btn admin-btn-ghost">
                    Batal
                </a>
            </div>
        </form>
</x-admin.form-card>
@endsection
