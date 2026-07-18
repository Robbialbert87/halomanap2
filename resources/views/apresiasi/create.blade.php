@extends('layouts.app')

@section('title', 'Apresiasi - Halo MANAP')

@section('content')
<div class="bg-gray-50 min-h-screen">

    {{-- DESKTOP HEADER --}}
    <header class="hidden md:block bg-white/70 backdrop-blur-xl sticky top-0 z-50 border-b border-white/30" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
        <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2.5 hover:opacity-80 transition-opacity">
                <img src="{{ asset('assets/images/halomanaplogo.png') }}" alt="Halo MANAP" class="w-8 h-8 object-contain">
                <div>
                    <span class="font-heading font-bold text-lg text-blue-800 leading-tight">Halo <span class="text-green-600">MANAP</span></span>
                    <p class="text-[8px] text-gray-400 leading-none -mt-0.5">RSUD H. Abdul Manap</p>
                </div>
            </a>
            <a href="/" class="text-sm font-medium text-gray-400 hover:text-blue-600 flex items-center gap-1.5 transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>
    </header>

    {{-- MOBILE HEADER --}}
    <header class="md:hidden bg-white/70 backdrop-blur-xl sticky top-0 z-50 border-b border-white/30" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
        <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2.5 hover:opacity-80 transition-opacity">
                <img src="{{ asset('assets/images/halomanaplogo.png') }}" alt="Halo MANAP" class="w-8 h-8 object-contain">
                <div>
                    <span class="font-bold text-lg text-blue-800 leading-tight">Halo <span class="text-green-600">MANAP</span></span>
                    <p class="text-[8px] text-gray-400 leading-none -mt-0.5">RSUD H. Abdul Manap</p>
                </div>
            </a>
        </div>
    </header>

    <div class="max-w-5xl mx-auto px-4 pt-5 pb-28">

        {{-- HERO BANNER --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl p-5 lg:p-8 shadow-sm border border-white/30 mb-4 lg:mb-6 flex flex-col md:flex-row items-center gap-4" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
            <span class="w-14 h-14 lg:w-20 lg:h-20 rounded-2xl lg:rounded-3xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md shadow-blue-200/50 flex-shrink-0">
                <i class="fa-solid fa-thumbs-up text-white text-2xl lg:text-4xl"></i>
            </span>
            <div class="flex-1 text-center md:text-left">
                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full bg-blue-100 text-blue-700 text-[10px] lg:text-xs font-bold mb-2 uppercase tracking-wider">
                    <i class="fa-solid fa-thumbs-up"></i> Apresiasi
                </span>
                <h1 class="text-lg lg:text-3xl font-bold text-gray-800 leading-tight mt-1">Sampaikan Apresiasi Anda</h1>
                <p class="text-xs lg:text-base text-gray-500 leading-relaxed mt-1">
                    Beri penghargaan atas pelayanan yang memuaskan dari kami.
                </p>
            </div>
        </div>

        {{-- FORM + INFO CARDS in 2-column on desktop --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">

            {{-- LEFT: info cards --}}
            <div class="lg:col-span-1">
                <div class="grid grid-cols-2 lg:grid-cols-1 gap-3 mb-4 lg:mb-0">
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/30 p-3.5 lg:p-5 flex items-start gap-2.5 lg:gap-3" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
                        <span class="w-9 h-9 lg:w-11 lg:h-11 rounded-xl lg:rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md shadow-blue-200/50 flex-shrink-0">
                            <i class="fa-solid fa-star text-white text-sm lg:text-base"></i>
                        </span>
                        <div>
                            <h3 class="font-bold text-gray-800 text-[11px] lg:text-sm mb-0.5">Beri Nilai</h3>
                            <p class="text-[9px] lg:text-xs text-gray-400 leading-snug">Pilih bintang sesuai kepuasan</p>
                        </div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/30 p-3.5 lg:p-5 flex items-start gap-2.5 lg:gap-3" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
                        <span class="w-9 h-9 lg:w-11 lg:h-11 rounded-xl lg:rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-md shadow-emerald-200/50 flex-shrink-0">
                            <i class="fa-solid fa-pen text-white text-sm lg:text-base"></i>
                        </span>
                        <div>
                            <h3 class="font-bold text-gray-800 text-[11px] lg:text-sm mb-0.5">Tulis Pesan</h3>
                            <p class="text-[9px] lg:text-xs text-gray-400 leading-snug">Sampaikan apresiasi Anda</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Form --}}
            <div class="lg:col-span-2">
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/30 mb-5 overflow-hidden" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
                    <div class="border-b border-white/30 px-5 lg:px-6 py-4">
                        <h2 class="text-base lg:text-xl font-heading font-bold text-gray-800 flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md shadow-blue-200/50">
                                <i class="fa-regular fa-pen-to-square text-white text-xs"></i>
                            </span> Formulir Apresiasi
                        </h2>
                    </div>
                    <form method="POST" action="{{ route('apresiasi.store') }}" class="px-5 lg:px-6 py-5">
                @csrf

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama <span class="text-gray-300 font-normal">(opsional)</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Anda"
                        class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Penilaian <span class="text-red-500">*</span></label>
                    <div class="flex flex-row-reverse justify-center gap-2 py-2" id="starRating">
                        <button type="button" data-value="5" class="star-btn text-4xl text-gray-200 hover:text-yellow-400 transition-colors active:scale-110 touch-manipulation" style="-webkit-tap-highlight-color: transparent;"><i class="fa-solid fa-star"></i></button>
                        <button type="button" data-value="4" class="star-btn text-4xl text-gray-200 hover:text-yellow-400 transition-colors active:scale-110 touch-manipulation" style="-webkit-tap-highlight-color: transparent;"><i class="fa-solid fa-star"></i></button>
                        <button type="button" data-value="3" class="star-btn text-4xl text-gray-200 hover:text-yellow-400 transition-colors active:scale-110 touch-manipulation" style="-webkit-tap-highlight-color: transparent;"><i class="fa-solid fa-star"></i></button>
                        <button type="button" data-value="2" class="star-btn text-4xl text-gray-200 hover:text-yellow-400 transition-colors active:scale-110 touch-manipulation" style="-webkit-tap-highlight-color: transparent;"><i class="fa-solid fa-star"></i></button>
                        <button type="button" data-value="1" class="star-btn text-4xl text-gray-200 hover:text-yellow-400 transition-colors active:scale-110 touch-manipulation" style="-webkit-tap-highlight-color: transparent;"><i class="fa-solid fa-star"></i></button>
                    </div>
                    <input type="hidden" name="rating" id="ratingValue" value="{{ old('rating') }}">
                    @error('rating')
                    <p class="text-xs text-red-500 mt-1 text-center">{{ $message }}</p>
                    @enderror
                    <p class="text-[10px] text-gray-400 text-center mt-1" id="ratingLabel">Tap bintang untuk memberi nilai</p>
                </div>

                <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Pesan <span class="text-gray-300 font-normal">(opsional)</span></label>
                    <textarea name="message" rows="4" placeholder="Tulis apresiasi Anda..." class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5 resize-none">{{ old('message') }}</textarea>
                    @error('message')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-gradient-to-br from-blue-500 to-blue-700 text-white font-semibold rounded-xl px-5 py-3.5 text-sm shadow-md shadow-blue-200/50 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Apresiasi
                </button>
            </form>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center">
            <p class="text-[11px] text-gray-400 leading-relaxed">
                Terima kasih telah memberikan apresiasi.<br>
                Setiap masukan berarti bagi kami.
            </p>
        </div>
    </div>

    {{-- BOTTOM NAV MOBILE --}}
    <nav class="md:hidden fixed bottom-3 left-3 right-3 bg-white/70 backdrop-blur-xl border border-white/30 rounded-2xl flex justify-around items-center px-2 pt-1.5 pb-5 z-50 shadow-[0_-4px_30px_rgba(0,0,0,0.08)]" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
        <a href="/" class="flex flex-col items-center gap-0.5 w-14 py-1 text-blue-600">
            <i class="fa-solid fa-house text-xl"></i>
            <span class="text-[9px] font-semibold">Beranda</span>
        </a>
        <a href="{{ route('pengaduan.track') }}" class="flex flex-col items-center gap-0.5 w-14 py-1 text-gray-400">
            <i class="fa-solid fa-magnifying-glass text-xl"></i>
            <span class="text-[9px] font-medium">Cek Status</span>
        </a>
        <div class="relative w-14 flex flex-col items-center">
            <a href="/pengaduan/buat" class="absolute -top-7 w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-full flex items-center justify-center shadow-lg shadow-blue-500/40 border-[3px] border-white active:scale-90 transition-transform">
                <i class="fa-solid fa-plus text-xl"></i>
            </a>
            <span class="text-[9px] font-medium text-gray-400 mt-6 text-center leading-tight">Buat<br>Laporan</span>
        </div>
        <a href="#" class="flex flex-col items-center gap-0.5 w-14 py-1 text-gray-400">
            <i class="fa-solid fa-clock-rotate-left text-xl"></i>
            <span class="text-[9px] font-medium">Riwayat</span>
        </a>
        <a href="/dashboard" class="flex flex-col items-center gap-0.5 w-14 py-1 text-gray-400">
            <i class="fa-regular fa-user text-xl"></i>
            <span class="text-[9px] font-medium">Profil</span>
        </a>
    </nav>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var stars = document.querySelectorAll('.star-btn');
    var input = document.getElementById('ratingValue');
    var label = document.getElementById('ratingLabel');
    var labels = ['', 'Kurang Baik', 'Cukup', 'Baik', 'Sangat Baik', 'Istimewa!'];

    function highlight(val) {
        stars.forEach(function (s) {
            var sv = parseInt(s.getAttribute('data-value'));
            s.classList.toggle('text-yellow-400', sv <= val);
            s.classList.toggle('text-gray-200', sv > val);
        });
        if (label) label.textContent = val ? labels[val] : 'Tap bintang untuk memberi nilai';
    }

    stars.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var val = parseInt(this.getAttribute('data-value'));
            input.value = val;
            highlight(val);
        });
    });

    if (input.value) {
        highlight(parseInt(input.value));
    }
});
</script>
@endsection
