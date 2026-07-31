@extends('layouts.admin')

@section('title', 'Profil - Halo MANAP')

@section('admin_content')
<x-admin.page-header
    title="Profil"
    section="Direktur"
    crumb="Profil"
    icon="fa-user"
    gradient="indigo"
></x-admin.page-header>

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
