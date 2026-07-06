@extends('layouts.admin')

@section('title', 'Tambah Pengaduan - Halo MANAP')

@section('admin_content')

@if ($errors->any())
    <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm border border-red-200">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
    <div class="border-b border-gray-100 p-6 bg-gray-50/50 flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-regular fa-pen-to-square text-blue-600"></i> Form Pengaduan Baru
        </h2>
        <a href="{{ route('admin.tickets.index') }}" class="text-sm text-gray-500 hover:text-blue-600">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="{{ route('admin.tickets.store') }}" method="POST" enctype="multipart/form-data" class="p-6 lg:p-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis <span class="text-red-500">*</span></label>
                <select name="type" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                    <option value="Pengaduan" {{ old('type', 'Pengaduan') == 'Pengaduan' ? 'selected' : '' }}>Pengaduan</option>
                    <option value="Saran" {{ old('type') == 'Saran' ? 'selected' : '' }}>Saran</option>
                    <option value="Apresiasi" {{ old('type') == 'Apresiasi' ? 'selected' : '' }}>Apresiasi</option>
                    <option value="Informasi" {{ old('type') == 'Informasi' ? 'selected' : '' }}>Informasi</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                <select name="category_id" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Unit Pelayanan <span class="text-red-500">*</span></label>
                <select name="unit_id" id="unit_id" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                    <option value="">-- Pilih Unit --</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Ruangan <span class="text-red-500">*</span></label>
                <select name="room_id" id="room_id" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                    <option value="">-- Pilih Unit dahulu --</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Judul <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: AC di Ruang Tunggu Poli Umum Mati" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="description" rows="5" placeholder="Jelaskan kronologi pengaduan secara lengkap." class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>{{ old('description') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Lampiran (Opsional)</label>
                <input type="file" name="attachment" id="attachment" accept="image/*,.pdf" capture="environment" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                <p class="mt-1 text-xs text-gray-500">Format: Gambar (JPG, PNG, HEIC, WebP) dan PDF. Maksimal 20 MB.</p>
                <div id="image-preview-container" class="mt-4 hidden">
                    <img id="image-preview" src="#" alt="Preview" class="max-h-48 rounded-lg border border-gray-200 shadow-sm">
                </div>
            </div>
        </div>

        <hr class="border-gray-200 my-8">

        <div class="bg-blue-50/50 rounded-xl p-6 border border-blue-100 mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-address-card text-blue-600"></i> Data Pelapor
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Pelapor <span class="text-red-500">*</span></label>
                    <input type="text" name="reporter_name" value="{{ old('reporter_name') }}" placeholder="Nama lengkap pelapor" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor HP <span class="text-red-500">*</span></label>
                    <input type="text" name="reporter_phone" value="{{ old('reporter_phone') }}" placeholder="Nomor WhatsApp" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg text-sm px-8 py-3 transition-colors shadow-sm">
                <i class="fa-solid fa-paper-plane mr-1"></i> Simpan Pengaduan
            </button>
            <a href="{{ route('admin.tickets.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg text-sm px-8 py-3 transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    const roomsByUnit = @json($rooms);
    const oldRoomId = '{{ old('room_id') }}';

    document.addEventListener('DOMContentLoaded', function() {
        const unitSelect = document.getElementById('unit_id');
        const roomSelect = document.getElementById('room_id');

        function updateRooms() {
            const uid = unitSelect.value;
            roomSelect.innerHTML = '<option value="">-- Pilih Ruangan --</option>';
            if (uid && roomsByUnit[uid]) {
                roomsByUnit[uid].forEach(function(room) {
                    const opt = document.createElement('option');
                    opt.value = room.id;
                    opt.textContent = room.name;
                    if (String(room.id) === String(oldRoomId)) opt.selected = true;
                    roomSelect.appendChild(opt);
                });
            } else {
                roomSelect.innerHTML = '<option value="">-- Pilih Unit dahulu --</option>';
            }
        }
        unitSelect.addEventListener('change', updateRooms);
        if (unitSelect.value) updateRooms();

        const attachmentInput = document.getElementById('attachment');
        const previewContainer = document.getElementById('image-preview-container');
        const previewImage = document.getElementById('image-preview');

        attachmentInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file && file.type.match('image.*')) {
                const reader = new FileReader();
                reader.onload = e => {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('hidden');
            }
        });
    });
</script>
@endpush