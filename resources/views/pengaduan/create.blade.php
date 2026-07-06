@extends('layouts.app')

@section('title', 'Sampaikan ' . $type . ' - Halo MANAP')

@section('content')
<div class="bg-gray-50 min-h-screen pb-16">
    <!-- Navbar Top (Simplified for Public) -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-hospital text-blue-600 text-3xl"></i>
                <div class="flex flex-col">
                    <span class="font-bold text-xl text-blue-800 leading-tight">Halo <span class="text-green-600">MANAP</span></span>
                </div>
            </div>
            <a href="/" class="text-sm font-medium text-gray-500 hover:text-blue-600">Kembali ke Beranda</a>
        </div>
    </header>

    <div class="max-w-5xl mx-auto px-4 pt-8">
        <!-- SECTION 1: HERO HEADER -->
        <div class="bg-white rounded-2xl p-8 lg:p-12 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center gap-8 mb-8">
            <div class="flex-1">
                @php
                    $badge_color = match($type) {
                        'Survei' => 'bg-green-100 text-green-700',
                        'Apresiasi' => 'bg-blue-100 text-blue-700',
                        'Informasi' => 'bg-orange-100 text-orange-700',
                        default => 'bg-green-100 text-green-700',
                    };
                    $icon = match($type) {
                        'Survei' => 'fa-square-poll-vertical',
                        'Apresiasi' => 'fa-thumbs-up',
                        'Informasi' => 'fa-circle-info',
                        default => 'fa-circle-exclamation',
                    };
                @endphp
                <span class="inline-flex items-center gap-2 py-1 px-3 rounded-full {{ $badge_color }} text-xs font-bold mb-4 uppercase tracking-wider">
                    <i class="fa-solid {{ $icon }}"></i> Layanan {{ $type }}
                </span>
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4 leading-tight">Sampaikan {{ $type }} Anda</h1>
                <p class="text-gray-600 leading-relaxed text-lg">
                    Kami berkomitmen untuk terus meningkatkan kualitas pelayanan. Sampaikan {{ strtolower($type) }} Anda secara mudah, aman, dan transparan.
                </p>
            </div>
            <div class="hidden md:block w-1/3 text-center">
                <i class="fa-solid fa-user-doctor text-[8rem] text-blue-100"></i>
            </div>
        </div>

        <!-- SECTION 2: INFORMASI PENTING -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-clipboard-check text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-blue-900 text-sm mb-1">Ditangani Tim Ahli</h3>
                    <p class="text-xs text-blue-700">Pengaduan diproses oleh tim yang berwenang secara cepat.</p>
                </div>
            </div>
            <div class="bg-green-50 border border-green-100 rounded-xl p-5 flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-shield-halved text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-green-900 text-sm mb-1">Kerahasiaan Terjamin</h3>
                    <p class="text-xs text-green-700">Identitas pelapor dijaga kerahasiaannya dengan sistem yang aman.</p>
                </div>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-user-secret text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm mb-1">Dukung Anonimitas</h3>
                    <p class="text-xs text-slate-600">Pengaduan dapat dikirim secara anonim tanpa identitas.</p>
                </div>
            </div>
        </div>

        <!-- SECTION SKM: SURVEI KEPUASAN MASYARAKAT -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-8 lg:p-10 shadow-lg mb-8 text-white">
            <div class="flex flex-col md:flex-row items-center gap-6">
                <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-square-poll-vertical text-3xl"></i>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h2 class="text-2xl font-bold mb-1">Survei Kepuasan Masyarakat</h2>
                    <p class="text-white/80 text-sm">Bantu kami meningkatkan kualitas pelayanan dengan mengisi survei kepuasan masyarakat.</p>
                </div>
                <a href="https://skm.go.id/share/instansi/cf0fe4fb-d51e-40e0-a3e7-4b6fbb5918b8/2" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 bg-white text-green-600 font-bold px-6 py-3 rounded-xl hover:bg-green-50 transition-colors shadow-md shrink-0">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Isi Survei Sekarang
                </a>
            </div>
        </div>

        <!-- SECTION 3: FORM PENGADUAN -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-8 overflow-hidden">
            <div class="border-b border-gray-100 p-6 bg-gray-50/50">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-regular fa-pen-to-square text-blue-600"></i> Formulir Laporan
                </h2>
            </div>
            
            <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="p-6 lg:p-8">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                
                @if ($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm border border-red-200">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Unit Dropdown -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">1. Unit Pelayanan <span class="text-red-500">*</span></label>
                        <select name="unit_id" id="unit_id" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                            <option value="">-- Pilih Unit --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Ruangan Dropdown (Dynamic based on Unit) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">2. Ruangan <span class="text-red-500">*</span></label>
                        <select name="room_id" id="room_id" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                            <option value="">-- Pilih Unit dahulu --</option>
                        </select>
                    </div>

                    <!-- Kategori Dropdown -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">3. Kategori Pengaduan <span class="text-red-500">*</span></label>
                        <select name="category_id" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Judul Pengaduan -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">4. Judul Pengaduan <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: AC di Ruang Tunggu Poli Umum Mati" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                    </div>

                    <!-- Isi Pengaduan -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">5. Isi Pengaduan <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="6" style="min-height: 180px;" placeholder="Jelaskan kronologi pengaduan secara lengkap agar kami dapat menindaklanjuti dengan baik." class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>{{ old('description') }}</textarea>
                    </div>

                    <!-- Upload Bukti -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">6. Upload Bukti (Opsional)</label>
                        <input type="file" name="attachment" id="attachment" accept="image/*,.pdf" capture="environment" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                        <p class="mt-1 text-xs text-gray-500">Format: Gambar (JPG, PNG, HEIC, WebP) dan PDF. Maksimal 20 MB.</p>
                        
                        <!-- Preview Box -->
                        <div id="image-preview-container" class="mt-4 hidden">
                            <p class="text-xs font-semibold text-gray-600 mb-2">Preview Gambar:</p>
                            <img id="image-preview" src="#" alt="Preview" class="max-h-48 rounded-lg border border-gray-200 shadow-sm">
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200 my-8">

                <!-- IDENTITAS PELAPOR -->
                <div class="bg-blue-50/50 rounded-xl p-6 border border-blue-100 mb-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-address-card text-blue-600"></i> Identitas Pelapor
                    </h3>

                    <div class="flex items-center mb-6">
                        <input type="checkbox" name="is_anonymous" id="is_anonymous" value="1" class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" {{ old('is_anonymous') ? 'checked' : '' }}>
                        <label for="is_anonymous" class="ml-3 text-sm font-medium text-gray-700 cursor-pointer">Saya ingin mengirim pengaduan secara anonim.</label>
                    </div>

                    <div id="anonymous_info" class="hidden mb-4 p-4 bg-green-50 text-green-800 text-sm rounded-lg border border-green-200 flex gap-3 items-center">
                        <i class="fa-solid fa-circle-info text-xl"></i>
                        <p>Identitas Anda tidak akan dicatat dan pengaduan tetap akan diproses dengan aman.</p>
                    </div>

                    <div id="identity_fields" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="reporter_name" id="reporter_name" value="{{ old('reporter_name') }}" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor HP (WhatsApp) <span class="text-red-500">*</span></label>
                            <input type="text" name="reporter_phone" id="reporter_phone" value="{{ old('reporter_phone') }}" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Email (Opsional)</label>
                            <input type="email" name="reporter_email" id="reporter_email" value="{{ old('reporter_email') }}" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                        </div>
                    </div>
                </div>

                <!-- PERSETUJUAN -->
                <div class="flex items-start mb-8">
                    <div class="flex items-center h-5">
                        <input id="consent" type="checkbox" required class="w-5 h-5 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 cursor-pointer">
                    </div>
                    <label for="consent" class="ml-3 text-sm text-gray-600 cursor-pointer">
                        Saya menyatakan bahwa informasi yang saya berikan benar dan dapat dipertanggungjawabkan. Saya juga telah membaca <a href="#" class="text-blue-600 hover:underline">Kebijakan Privasi</a>.
                    </label>
                </div>

                <!-- BUTTONS -->
                <div class="flex flex-col md:flex-row gap-3">
                    <button type="submit" class="w-full md:w-auto text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-8 py-3 text-center transition-colors shadow-sm shadow-green-200">
                        Kirim Pengaduan
                    </button>
                    <a href="/" class="w-full md:w-auto text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-8 py-3 text-center transition-colors">
                        Kembali
                    </a>
                </div>
            </form>
        </div>

        <!-- SECTION 4: FAQ -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6 text-center">Pertanyaan yang Sering Diajukan (FAQ)</h2>
            
            <div class="space-y-3 max-w-3xl mx-auto">
                <!-- FAQ Item 1 -->
                <details class="group bg-gray-50 rounded-lg open:bg-white open:ring-1 open:ring-gray-200 open:shadow-sm transition-all">
                    <summary class="flex cursor-pointer items-center justify-between font-semibold p-4 text-gray-800 marker:content-none">
                        1. Apakah saya harus login?
                        <i class="fa-solid fa-chevron-down text-gray-500 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <div class="px-4 pb-4 text-sm text-gray-600">
                        Tidak. Pengaduan dapat dikirim tanpa login sama sekali untuk memudahkan masyarakat.
                    </div>
                </details>
                
                <!-- FAQ Item 2 -->
                <details class="group bg-gray-50 rounded-lg open:bg-white open:ring-1 open:ring-gray-200 open:shadow-sm transition-all">
                    <summary class="flex cursor-pointer items-center justify-between font-semibold p-4 text-gray-800 marker:content-none">
                        2. Apakah identitas saya aman?
                        <i class="fa-solid fa-chevron-down text-gray-500 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <div class="px-4 pb-4 text-sm text-gray-600">
                        Ya. Seluruh identitas pelapor dijaga kerahasiaannya dengan sistem keamanan terkini.
                    </div>
                </details>

                <!-- FAQ Item 3 -->
                <details class="group bg-gray-50 rounded-lg open:bg-white open:ring-1 open:ring-gray-200 open:shadow-sm transition-all">
                    <summary class="flex cursor-pointer items-center justify-between font-semibold p-4 text-gray-800 marker:content-none">
                        3. Berapa lama pengaduan diproses?
                        <i class="fa-solid fa-chevron-down text-gray-500 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <div class="px-4 pb-4 text-sm text-gray-600">
                        Estimasi maksimal 1 × 24 jam kerja untuk proses verifikasi awal sebelum diteruskan ke unit terkait.
                    </div>
                </details>

                <!-- FAQ Item 4 -->
                <details class="group bg-gray-50 rounded-lg open:bg-white open:ring-1 open:ring-gray-200 open:shadow-sm transition-all">
                    <summary class="flex cursor-pointer items-center justify-between font-semibold p-4 text-gray-800 marker:content-none">
                        4. Bagaimana cara mengetahui status pengaduan?
                        <i class="fa-solid fa-chevron-down text-gray-500 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <div class="px-4 pb-4 text-sm text-gray-600">
                        Gunakan Nomor Tiket yang Anda dapatkan setelah mensubmit form pada menu "Lacak Pengaduan".
                    </div>
                </details>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    const roomsByUnit = @json($rooms);
    const oldRoomId = '{{ old('room_id') }}';

    document.addEventListener('DOMContentLoaded', function() {
        const unitSelect  = document.getElementById('unit_id');
        const roomSelect  = document.getElementById('room_id');

        // --- Dynamic Rooms Dropdown ---
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

        // --- Anonymous Checkbox ---
        const checkbox      = document.getElementById('is_anonymous');
        const identityFields = document.getElementById('identity_fields');
        const anonymousInfo  = document.getElementById('anonymous_info');
        const inputs        = identityFields.querySelectorAll('input[type="text"], input[type="email"]');

        function toggleAnonymous() {
            if (checkbox.checked) {
                identityFields.classList.add('hidden');
                anonymousInfo.classList.remove('hidden');
                inputs.forEach(i => { i.required = false; i.value = ''; });
            } else {
                identityFields.classList.remove('hidden');
                anonymousInfo.classList.add('hidden');
                document.getElementById('reporter_name').required  = true;
                document.getElementById('reporter_phone').required = true;
            }
        }
        toggleAnonymous();
        checkbox.addEventListener('change', toggleAnonymous);

        // --- Image Preview ---
        const attachmentInput   = document.getElementById('attachment');
        const previewContainer  = document.getElementById('image-preview-container');
        const previewImage      = document.getElementById('image-preview');

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
