@extends('layouts.app')

@section('title', 'Apresiasi - Halo MANAP')

@section('content')
<div class="bg-gray-50 min-h-screen">

    {{-- HEADER --}}
    <header class="bg-white/70 backdrop-blur-xl sticky top-0 z-50 border-b border-white/30" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%); backdrop-filter: blur(20px);">
        <div class="max-w-2xl mx-auto px-4 h-14 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('assets/images/halomanaplogo.png') }}" alt="Halo MANAP" class="w-8 h-8 object-contain">
                <div>
                    <span class="font-bold text-lg text-blue-800 leading-tight">Halo <span class="text-green-600">MANAP</span></span>
                    <p class="text-[8px] text-gray-400 leading-none -mt-0.5">RSUD H. Abdul Manap</p>
                </div>
            </div>
            <a href="/" class="text-sm font-medium text-gray-400 hover:text-blue-600 flex items-center gap-1.5 transition-colors">
                <i class="fa-solid fa-house"></i> Beranda
            </a>
        </div>
    </header>

    <div class="max-w-lg mx-auto px-4 pt-6 pb-28">

        {{-- HEADER --}}
        <div class="text-center mb-6">
            <span class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 shadow-md shadow-blue-200/50 mb-3">
                <i class="fa-solid fa-thumbs-up text-white text-2xl"></i>
            </span>
            <h1 class="text-xl font-bold text-gray-800">Sampaikan Apresiasi</h1>
            <p class="text-sm text-gray-400 mt-1">Beri penghargaan atas pelayanan yang memuaskan</p>
        </div>

        {{-- FORM --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form method="POST" action="{{ route('apresiasi.store') }}">
                @csrf

                {{-- Nama --}}
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama <span class="text-gray-300 font-normal">(opsional)</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Anda"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-gray-50/50">
                </div>

                {{-- Rating --}}
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Penilaian</label>
                    <div class="flex flex-row-reverse justify-center gap-1.5" id="starRating">
                        <button type="button" data-value="5" class="star-btn text-3xl text-gray-200 hover:text-yellow-400 transition-colors"><i class="fa-solid fa-star"></i></button>
                        <button type="button" data-value="4" class="star-btn text-3xl text-gray-200 hover:text-yellow-400 transition-colors"><i class="fa-solid fa-star"></i></button>
                        <button type="button" data-value="3" class="star-btn text-3xl text-gray-200 hover:text-yellow-400 transition-colors"><i class="fa-solid fa-star"></i></button>
                        <button type="button" data-value="2" class="star-btn text-3xl text-gray-200 hover:text-yellow-400 transition-colors"><i class="fa-solid fa-star"></i></button>
                        <button type="button" data-value="1" class="star-btn text-3xl text-gray-200 hover:text-yellow-400 transition-colors"><i class="fa-solid fa-star"></i></button>
                    </div>
                    <input type="hidden" name="rating" id="ratingValue" value="{{ old('rating') }}">
                    @error('rating')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pesan --}}
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Pesan <span class="text-gray-300 font-normal">(opsional)</span></label>
                    <textarea name="message" rows="3" placeholder="Tulis apresiasi Anda..." class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-gray-50/50 resize-none">{{ old('message') }}</textarea>
                    @error('message')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-gradient-to-br from-blue-500 to-blue-700 text-white font-semibold rounded-xl px-5 py-3 text-sm shadow-md shadow-blue-200/50 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Apresiasi
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <div class="text-center mt-6 text-[11px] text-gray-400">
            Terima kasih telah memberikan apresiasi. <br>Setiap masukan berarti bagi kami.
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var stars = document.querySelectorAll('.star-btn');
    var input = document.getElementById('ratingValue');

    stars.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var val = parseInt(this.getAttribute('data-value'));
            input.value = val;
            stars.forEach(function (s) {
                var sv = parseInt(s.getAttribute('data-value'));
                s.classList.toggle('text-yellow-400', sv <= val);
                s.classList.toggle('text-gray-200', sv > val);
            });
        });
    });

    if (input.value) {
        var saved = parseInt(input.value);
        stars.forEach(function (s) {
            var sv = parseInt(s.getAttribute('data-value'));
            s.classList.toggle('text-yellow-400', sv <= saved);
            s.classList.toggle('text-gray-200', sv > saved);
        });
    }
});
</script>
@endsection
