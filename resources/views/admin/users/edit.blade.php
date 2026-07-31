@extends('layouts.admin')

@section('title', 'Edit Pengguna - Halo MANAP')

@section('admin_content')
<x-admin.page-header
    title="Edit Pengguna"
    section="Master Data"
    icon="fa-user-pen"
    gradient="amber"
>
    <x-slot name="breadcrumb">
        <span class="text-gray-400">/</span>
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline">Pengguna</a>
        <span class="text-gray-400">/</span>
        <span>Edit</span>
    </x-slot>
    <x-admin.btn href="{{ route('admin.users.index') }}" variant="ghost" icon="fa-arrow-left">Kembali</x-admin.btn>
</x-admin.page-header>

<x-admin.form-card title='Edit Pegawai: <span class="text-blue-600">{{ $user->nama }}</span>' icon="fa-user-pen" icon-class="bg-amber-100 text-amber-600">
    <x-admin.errors />
<form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="mb-8 pb-8 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wider">Identitas Diri</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" placeholder="Nama lengkap">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">NIP / NIK <span class="text-red-500">*</span></label>
                        <input type="text" name="nip" value="{{ old('nip', $user->nip) }}" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition font-mono" placeholder="Nomor Induk Pegawai">
                        <p class="text-xs text-gray-400 mt-1.5">Digunakan sebagai Username login.</p>
                    </div>
                </div>
            </div>

            <div class="mb-8 pb-8 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wider">Kontak & Keamanan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor WhatsApp <span class="text-red-500">*</span></label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 text-gray-500 sm:text-sm">+62</span>
                            <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required class="flex-1 border border-gray-200 rounded-none rounded-r-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition font-mono" placeholder="81234567890">
                        </div>
                        <p class="text-xs text-gray-400 mt-1.5">Wajib untuk notifikasi disposisi.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" placeholder="Kosongkan jika tidak ingin mengubah password">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" placeholder="Ulangi password baru">
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wider">Hak Akses & Penempatan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Unit Kerja <span class="text-red-500">*</span></label>
                        <select name="unit_id" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white">
                            <option value="">-- Pilih Unit --</option>
                            @foreach($units as $u)
                                <option value="{{ $u->id }}" {{ old('unit_id', $user->unit_id) == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jabatan Aktif <span class="text-red-500">*</span></label>
                        <select name="jabatan_id" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white">
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach($jabatans as $j)
                                <option value="{{ $j->id }}" {{ old('jabatan_id', $user->jabatan_id) == $j->id ? 'selected' : '' }}>{{ $j->nama }} ({{ $j->kategori_jabatan }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Role Sistem <span class="text-red-500">*</span></label>
                        <select name="role" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white">
                            <option value="">-- Pilih Role --</option>
                            @foreach($roles as $r)
                                <option value="{{ $r->name }}" {{ old('role', $user->roles->first()->name ?? '') == $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Akun</label>
                        <select name="status" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white">
                            <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-6 border-t border-gray-100">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-ghost">
                    Batal
                </a>
            </div>
        </form>
</x-admin.form-card>
@endsection
