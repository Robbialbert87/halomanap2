@extends('layouts.app')

@section('title', 'Sampaikan ' . $type . ' - Halo MANAP')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <header class="bg-white/70 backdrop-blur-xl sticky top-0 z-50 border-b border-white/30" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
        <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('assets/images/halomanaplogo.png') }}" alt="Halo MANAP" class="w-8 h-8 object-contain">
                <div>
                    <span class="font-heading font-bold text-lg text-blue-800 leading-tight">Halo <span class="text-green-600">MANAP</span></span>
                    <p class="text-[8px] text-gray-400 leading-none -mt-0.5">RSUD H. Abdul Manap</p>
                </div>
            </div>
            <a href="/" class="text-sm font-medium text-gray-400 hover:text-blue-600 flex items-center gap-1.5 transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
    </header>

    <div class="max-w-5xl mx-auto px-4 pt-5 pb-24">

        {{-- SECTION 1: HERO HEADER (glossy PayApp) --}}
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
            $gradient = match($type) {
                'Survei' => 'from-emerald-400 to-emerald-600',
                'Apresiasi' => 'from-blue-400 to-blue-600',
                'Informasi' => 'from-orange-400 to-orange-600',
                default => 'from-red-400 to-red-600',
            };
        @endphp
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl p-5 lg:p-8 shadow-sm border border-white/30 mb-5 flex flex-col md:flex-row items-center gap-4" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
            <span class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $gradient }} flex items-center justify-center shadow-md flex-shrink-0">
                <i class="fa-solid {{ $icon }} text-white text-2xl"></i>
            </span>
            <div class="flex-1">
                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full {{ $badge_color }} text-[10px] font-bold mb-2 uppercase tracking-wider">
                    <i class="fa-solid {{ $icon }}"></i> Layanan {{ $type }}
                </span>
                <h1 class="text-xl md:text-3xl font-bold text-gray-800 leading-tight">Sampaikan {{ $type }} Anda</h1>
                <p class="text-sm md:text-base text-gray-500 leading-relaxed mt-1">
                    Kami berkomitmen untuk terus meningkatkan kualitas pelayanan. Sampaikan {{ strtolower($type) }} Anda secara mudah, aman, dan transparan.
                </p>
            </div>
        </div>

        {{-- SECTION 2: INFORMASI PENTING (glossy cards) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-5">
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/30 p-4 flex items-start gap-3" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
                <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md shadow-blue-200/50 flex-shrink-0">
                    <i class="fa-solid fa-clipboard-check text-white text-base"></i>
                </span>
                <div>
                    <h3 class="font-heading font-bold text-gray-800 text-sm mb-0.5">Ditangani Tim Ahli</h3>
                    <p class="text-xs text-gray-400">Pengaduan diproses oleh tim yang berwenang secara cepat.</p>
                </div>
            </div>
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/30 p-4 flex items-start gap-3" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
                <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-md shadow-emerald-200/50 flex-shrink-0">
                    <i class="fa-solid fa-shield-halved text-white text-base"></i>
                </span>
                <div>
                    <h3 class="font-heading font-bold text-gray-800 text-sm mb-0.5">Kerahasiaan Terjamin</h3>
                    <p class="text-xs text-gray-400">Identitas pelapor dijaga kerahasiaannya.</p>
                </div>
            </div>
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/30 p-4 flex items-start gap-3" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
                <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center shadow-md shadow-violet-200/50 flex-shrink-0">
                    <i class="fa-solid fa-user-secret text-white text-base"></i>
                </span>
                <div>
                    <h3 class="font-heading font-bold text-gray-800 text-sm mb-0.5">Dukung Anonimitas</h3>
                    <p class="text-xs text-gray-400">Pengaduan dapat dikirim secara anonim.</p>
                </div>
            </div>
        </div>

        {{-- SECTION 3: FORM PENGADUAN (glossy) --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/30 mb-5 overflow-hidden" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
            <div class="border-b border-white/30 px-5 lg:px-6 py-4">
                <h2 class="text-base lg:text-xl font-heading font-bold text-gray-800 flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md shadow-blue-200/50">
                        <i class="fa-regular fa-pen-to-square text-white text-sm"></i>
                    </span> Formulir Laporan
                </h2>
            </div>

            <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="px-5 lg:px-8 py-5 lg:py-6">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                @if ($errors->any())
                    <div class="bg-red-50/80 backdrop-blur-sm text-red-600 p-4 rounded-2xl mb-6 text-sm border border-red-200/50">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">1. Unit Pelayanan <span class="text-red-500">*</span></label>
                        <select name="unit_id" id="unit_id" class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                            <option value="">-- Pilih Unit --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">2. Ruangan <span class="text-red-500">*</span></label>
                        <select name="room_id" id="room_id" class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                            <option value="">-- Pilih Unit dahulu --</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">3. Kategori Pengaduan <span class="text-red-500">*</span></label>
                        <select name="category_id" class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">4. Judul Pengaduan <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: AC di Ruang Tunggu Poli Umum Mati" class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">5. Isi Pengaduan <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="6" style="min-height: 180px;" placeholder="Jelaskan kronologi pengaduan secara lengkap agar kami dapat menindaklanjuti dengan baik." class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>{{ old('description') }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">6. Upload Bukti (Opsional)</label>
                        <div class="relative">
                            <input type="file" name="attachment" id="attachment" accept="image/*,.pdf" capture="environment" class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gradient-to-br file:from-blue-400 file:to-blue-600 file:text-white hover:file:from-blue-500 hover:file:to-blue-700 bg-white/70 border border-gray-200 rounded-xl cursor-pointer focus:outline-none p-2">
                        </div>
                        <p class="mt-1.5 text-xs text-gray-400">Format: Gambar (JPG, PNG, HEIC, WebP) dan PDF. Maksimal 20 MB.</p>
                        <div id="image-preview-container" class="mt-4 hidden">
                            <p class="text-xs font-semibold text-gray-600 mb-2">Preview Gambar:</p>
                            <img id="image-preview" src="#" alt="Preview" class="max-h-48 rounded-xl border border-gray-200 shadow-sm">
                        </div>
                    </div>
                </div>

                {{-- IDENTITAS PELAPOR (glossy) --}}
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl border border-white/30 p-5 lg:p-6 mb-6" style="background: linear-gradient(135deg, rgba(255,255,255,0.85) 0%, rgba(255,255,255,0.4) 100%);">
                    <h3 class="text-base lg:text-lg font-heading font-bold text-gray-800 mb-4 flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md shadow-blue-200/50">
                            <i class="fa-solid fa-address-card text-white text-sm"></i>
                        </span> Identitas Pelapor
                    </h3>

                    <div class="flex items-center mb-5">
                        <input type="checkbox" name="is_anonymous" id="is_anonymous" value="1" class="w-5 h-5 text-blue-600 bg-white/70 border-gray-300 rounded focus:ring-blue-500 cursor-pointer" {{ old('is_anonymous') ? 'checked' : '' }}>
                        <label for="is_anonymous" class="ml-3 text-sm font-medium text-gray-700 cursor-pointer">Saya ingin mengirim pengaduan secara anonim.</label>
                    </div>

                    <div id="anonymous_info" class="hidden mb-4 p-4 bg-green-50/80 backdrop-blur-sm text-green-800 text-sm rounded-2xl border border-green-200/50 flex gap-3 items-center">
                        <i class="fa-solid fa-circle-info text-xl"></i>
                        <p>Identitas Anda tidak akan dicatat dan pengaduan tetap akan diproses dengan aman.</p>
                    </div>

                    <div id="identity_fields" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="reporter_name" id="reporter_name" value="{{ old('reporter_name') }}" class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor HP (WhatsApp) <span class="text-red-500">*</span></label>
                            <input type="text" name="reporter_phone" id="reporter_phone" value="{{ old('reporter_phone') }}" class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Email (Opsional)</label>
                            <input type="email" name="reporter_email" id="reporter_email" value="{{ old('reporter_email') }}" class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                        </div>
                    </div>
                </div>

                {{-- PERSETUJUAN --}}
                <div class="flex items-start mb-6">
                    <div class="flex items-center h-5">
                        <input id="consent" type="checkbox" required class="w-5 h-5 text-green-600 bg-white/70 border-gray-300 rounded-xl focus:ring-green-500 cursor-pointer">
                    </div>
                    <label for="consent" class="ml-3 text-sm text-gray-600 cursor-pointer">
                        Saya menyatakan bahwa informasi yang saya berikan benar dan dapat dipertanggungjawabkan. Saya juga telah membaca <a href="#" class="text-blue-600 hover:underline">Kebijakan Privasi</a>.
                    </label>
                </div>

                {{-- BUTTONS --}}
                <div class="flex flex-col md:flex-row gap-3">
                    <button type="submit" class="w-full md:w-auto bg-gradient-to-br from-emerald-500 to-emerald-700 text-white font-semibold rounded-xl px-8 py-3 text-sm text-center shadow-md shadow-emerald-200/50 hover:shadow-lg active:scale-[0.98] transition-all">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Pengaduan
                    </button>
                    <a href="/" class="w-full md:w-auto text-gray-600 bg-white/70 backdrop-blur-sm border border-gray-200 hover:border-gray-300 font-medium rounded-xl text-sm px-8 py-3 text-center transition-all active:scale-[0.98]">
                        <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali
                    </a>
                </div>
            </form>
        </div>

        {{-- SECTION 4: FAQ (glossy) --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/30 p-5 lg:p-8 mb-5" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
            <h2 class="text-base md:text-xl font-heading font-bold text-gray-800 mb-5 text-center">Pertanyaan yang Sering Diajukan</h2>
            <div class="space-y-3 max-w-3xl mx-auto">
                <details class="group bg-white/60 backdrop-blur-sm rounded-xl border border-white/30 open:bg-white/80 open:shadow-sm transition-all" style="background: linear-gradient(135deg, rgba(255,255,255,0.8) 0%, rgba(255,255,255,0.4) 100%);">
                    <summary class="flex cursor-pointer items-center justify-between font-semibold p-4 text-gray-800 marker:content-none text-sm">
                        1. Apakah saya harus login?
                        <i class="fa-solid fa-chevron-down text-gray-500 group-open:rotate-180 transition-transform text-xs"></i>
                    </summary>
                    <div class="px-4 pb-4 text-sm text-gray-600">
                        Tidak. Pengaduan dapat dikirim tanpa login sama sekali untuk memudahkan masyarakat.
                    </div>
                </details>
                <details class="group bg-white/60 backdrop-blur-sm rounded-xl border border-white/30 open:bg-white/80 open:shadow-sm transition-all" style="background: linear-gradient(135deg, rgba(255,255,255,0.8) 0%, rgba(255,255,255,0.4) 100%);">
                    <summary class="flex cursor-pointer items-center justify-between font-semibold p-4 text-gray-800 marker:content-none text-sm">
                        2. Apakah identitas saya aman?
                        <i class="fa-solid fa-chevron-down text-gray-500 group-open:rotate-180 transition-transform text-xs"></i>
                    </summary>
                    <div class="px-4 pb-4 text-sm text-gray-600">
                        Ya. Seluruh identitas pelapor dijaga kerahasiaannya dengan sistem keamanan terkini.
                    </div>
                </details>
                <details class="group bg-white/60 backdrop-blur-sm rounded-xl border border-white/30 open:bg-white/80 open:shadow-sm transition-all" style="background: linear-gradient(135deg, rgba(255,255,255,0.8) 0%, rgba(255,255,255,0.4) 100%);">
                    <summary class="flex cursor-pointer items-center justify-between font-semibold p-4 text-gray-800 marker:content-none text-sm">
                        3. Berapa lama pengaduan diproses?
                        <i class="fa-solid fa-chevron-down text-gray-500 group-open:rotate-180 transition-transform text-xs"></i>
                    </summary>
                    <div class="px-4 pb-4 text-sm text-gray-600">
                        Estimasi maksimal 1 × 24 jam kerja untuk proses verifikasi awal sebelum diteruskan ke unit terkait.
                    </div>
                </details>
                <details class="group bg-white/60 backdrop-blur-sm rounded-xl border border-white/30 open:bg-white/80 open:shadow-sm transition-all" style="background: linear-gradient(135deg, rgba(255,255,255,0.8) 0%, rgba(255,255,255,0.4) 100%);">
                    <summary class="flex cursor-pointer items-center justify-between font-semibold p-4 text-gray-800 marker:content-none text-sm">
                        4. Bagaimana cara mengetahui status pengaduan?
                        <i class="fa-solid fa-chevron-down text-gray-500 group-open:rotate-180 transition-transform text-xs"></i>
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
