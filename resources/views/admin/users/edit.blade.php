@extends('layouts.admin')

@section('title', 'Edit Pengguna - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Edit Pengguna</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Master Data</span>
            <span class="text-gray-400">/</span>
            <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline">Pengguna</a>
            <span class="text-gray-400">/</span>
            <span>Edit</span>
        </div>
    </div>
    <a href="{{ route('admin.users.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-user-pen text-amber-600 text-sm"></i>
        </div>
        <h2 class="font-semibold text-gray-800">Edit Pegawai: <span class="text-blue-600">{{ $user->nama }}</span></h2>
    </div>
    <div class="p-6">
        @if($errors->any())
        <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-200 text-sm space-y-1">
            @foreach($errors->all() as $err)
                <div class="flex items-start gap-2"><i class="fa-solid fa-circle-exclamation mt-0.5"></i> {{ $err }}</div>
            @endforeach
        </div>
        @endif

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="mb-8 pb-8 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wider">Identitas Diri</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Gelar Depan</label>
                        <input type="text" name="gelar_depan" value="{{ old('gelar_depan', $user->gelar_depan) }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" placeholder="Contoh: dr., Ns.">
                    </div>
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" placeholder="Nama tanpa gelar">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Gelar Belakang</label>
                        <input type="text" name="gelar_belakang" value="{{ old('gelar_belakang', $user->gelar_belakang) }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" placeholder="Contoh: S.Kep, M.Kes">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">NIP / NIK <span class="text-red-500">*</span></label>
                        <input type="text" name="nip" value="{{ old('nip', $user->nip) }}" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition font-mono" placeholder="Nomor Induk Pegawai">
                        <p class="text-xs text-gray-400 mt-1.5">Digunakan sebagai Username login.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email (Opsional)</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" placeholder="email@contoh.com">
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
                                <option value="{{ $j->id }}" {{ old('jabatan_id', $user->jabatan_id) == $j->id ? 'selected' : '' }}>{{ $j->nama }}</option>
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
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.users.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
