@extends('layouts.admin')

@section('title', 'Profil - Halo MANAP')

@section('admin_content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Profil</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">Kasi / Kasubbag</span>
            <span class="text-gray-400">/</span>
            <span>Profil</span>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center gap-6 mb-6">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=3b82f6&color=fff&size=100" alt="Avatar" class="w-24 h-24 rounded-full">
        <div>
            <h2 class="text-xl font-bold">{{ $user->nama }}</h2>
            <p class="text-gray-500">{{ $user->nip }}</p>
            <p class="text-sm text-gray-400">{{ $user->jabatan?->nama ?? '-' }} | {{ $user->unit?->nama ?? '-' }}</p>
        </div>
    </div>
</div>
@endsection
