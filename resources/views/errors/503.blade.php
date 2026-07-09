@extends('layouts.app')

@section('title', '503 Pemeliharaan - Halo MANAP')

@section('content')
<div class="min-h-screen bg-[#F3F4F6] flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white/80 backdrop-blur-xl rounded-3xl shadow-sm border border-white/30 p-8 text-center" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
        <span class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md mx-auto mb-4">
            <i class="fa-solid fa-wrench text-white text-2xl"></i>
        </span>
        <h1 class="text-5xl font-bold text-gray-800 font-heading mb-2">503</h1>
        <p class="text-lg font-semibold text-gray-700 mb-1">Mode Pemeliharaan</p>
        <p class="text-sm text-gray-500 mb-6">Sistem sedang dalam pemeliharaan. Silakan kembali lagi nanti.</p>
        <a href="/" class="inline-flex items-center gap-2 bg-gradient-to-br from-blue-500 to-blue-700 text-white font-semibold rounded-xl px-6 py-3 text-sm shadow-md shadow-blue-200/50 hover:shadow-lg active:scale-[0.98] transition-all">
            <i class="fa-solid fa-house"></i> Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
