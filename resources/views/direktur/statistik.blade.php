@extends('layouts.admin')

@section('title', 'Statistik Pengaduan - Halo MANAP')

@section('admin_content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Statistik Pengaduan</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Direktur</span>
            <span class="text-gray-400">/</span>
            <span>Statistik</span>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="text-center py-12 text-gray-400">
        <i class="fa-solid fa-chart-bar text-5xl mb-4"></i>
        <p>Statistik pengaduan akan ditampilkan di sini</p>
    </div>
</div>
@endsection
