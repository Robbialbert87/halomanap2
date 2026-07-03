@extends('layouts.admin')

@section('title', 'Tambah Jabatan - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Tambah Jabatan</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Master Data</span>
            <span class="text-gray-400">/</span>
            <a href="{{ route('admin.jabatans.index') }}" class="text-blue-600 hover:underline">Jabatan</a>
            <span class="text-gray-400">/</span>
            <span>Tambah</span>
        </div>
    </div>
    <a href="{{ route('admin.jabatans.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-sitemap text-blue-600 text-sm"></i>
        </div>
        <h2 class="font-semibold text-gray-800">Informasi Jabatan Baru</h2>
    </div>
    <div class="p-6">
        @if($errors->any())
        <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-200 text-sm space-y-1">
            @foreach($errors->all() as $err)
                <div class="flex items-start gap-2"><i class="fa-solid fa-circle-exclamation mt-0.5"></i> {{ $err }}</div>
            @endforeach
        </div>
        @endif

        <form action="{{ route('admin.jabatans.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Jabatan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Contoh: Kepala Instalasi Radiologi">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Level Jabatan <span class="text-red-500">*</span></label>
                    <select name="level" id="levelSelect" required onchange="onLevelChange(this.value)"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                        <option value="">-- Pilih Level --</option>
                        <option value="1" {{ old('level') == 1 ? 'selected' : '' }}>Level 1 – Direktur</option>
                        <option value="2" {{ old('level') == 2 ? 'selected' : '' }}>Level 2 – Kabid / Kabag</option>
                        <option value="3" {{ old('level') == 3 ? 'selected' : '' }}>Level 3 – Kasi / Kasubbag</option>
                        <option value="4" {{ old('level') == 4 ? 'selected' : '' }}>Level 4 – Kepala Unit</option>
                    </select>
                </div>
                <div id="parentWrapper">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Atasan Langsung (Parent Jabatan) <span class="text-red-500 text-xs font-normal">*Wajib untuk Level 2 ke bawah</span></label>
                    <select name="parent_id"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                        <option value="">-- Tidak Ada --</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}
                                data-level="{{ $parent->level }}">
                                [L{{ $parent->level }}] {{ $parent->nama }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-blue-600 mt-1.5 font-medium" id="parentHint">Pilih atasan langsung agar pengaduan bisa otomatis dieskalasi (naik tingkat).</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan</label>
                    <textarea name="keterangan" rows="2"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Keterangan tambahan tentang jabatan ini">{{ old('keterangan') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                    <select name="status"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-6 border-t border-gray-100">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Jabatan
                </button>
                <a href="{{ route('admin.jabatans.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function onLevelChange(level) {
    const parentWrapper = document.getElementById('parentWrapper');
    const parentHint    = document.getElementById('parentHint');
    const parentSelect  = parentWrapper.querySelector('select');
    const options       = parentSelect.querySelectorAll('option[data-level]');

    if (level === '1') {
        parentWrapper.classList.add('opacity-50', 'pointer-events-none');
        parentSelect.value = '';
        parentHint.textContent = 'Jabatan Level 1 (Direktur) adalah level tertinggi dan tidak memiliki atasan.';
    } else {
        parentWrapper.classList.remove('opacity-50', 'pointer-events-none');
        parentHint.textContent = 'Pilih atasan langsung agar pengaduan bisa otomatis dieskalasi (naik tingkat).';
    }

    // Filter parent options by level hierarchy
    options.forEach(opt => {
        const parentLevel = parseInt(opt.getAttribute('data-level'));
        if (!level) opt.hidden = true;
        else if (parentLevel === level - 1) opt.hidden = false;
        else opt.hidden = true;
    });
}

const initLevel = document.getElementById('levelSelect').value;
if (initLevel) onLevelChange(initLevel);
</script>
@endsection
