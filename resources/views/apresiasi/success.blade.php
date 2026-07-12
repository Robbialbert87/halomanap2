@extends('layouts.app')

@section('title', 'Apresiasi Terkirim - Halo MANAP')

@section('content')
<div class="bg-gray-50 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full text-center">
        <span class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 shadow-lg shadow-blue-200/50 mb-5">
            <i class="fa-solid fa-check text-white text-3xl"></i>
        </span>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Apresiasi Terkirim!</h1>
        <p class="text-sm text-gray-400 leading-relaxed mb-6">
            Terima kasih telah memberikan apresiasi. <br>Setiap masukan Anda sangat berarti untuk <br>meningkatkan kualitas pelayanan kami.
        </p>
        <div class="flex flex-col gap-3">
            <a href="{{ route('apresiasi.create') }}" class="w-full bg-gradient-to-br from-blue-500 to-blue-700 text-white font-semibold rounded-xl px-5 py-3 text-sm shadow-md shadow-blue-200/50 active:scale-[0.98] transition-all inline-flex items-center justify-center gap-2">
                <i class="fa-solid fa-thumbs-up"></i> Kirim Apresiasi Lagi
            </a>
            <a href="/" class="text-sm text-gray-400 hover:text-blue-600 transition-colors flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-house"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
    <p class="absolute bottom-8 text-[10px] text-gray-300">RSUD H. Abdul Manap Kota Jambi</p>
</div>
@endsection
