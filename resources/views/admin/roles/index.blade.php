@extends('layouts.admin')

@section('title', 'Manajemen Role - Halo MANAP')

@section('admin_content')

<x-admin.page-header
    title="Manajemen Role"
    section="Master Data"
    crumb="Role"
    icon="fa-user-shield"
    gradient="purple"
>
    <x-slot:summary>
        <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-shield-halved text-purple-500 text-xs"></i> Total Role: <b class="text-gray-800">{{ $totalRoles }}</b></span>
        <span class="text-gray-300">•</span>
        <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-users text-blue-500 text-xs"></i> Total User Terdaftar: <b class="text-gray-800">{{ $totalUsers }}</b></span>
    </x-slot:summary>
    <x-admin.btn href="{{ route('admin.roles.create') }}" icon="fa-plus">Tambah Role</x-admin.btn>
</x-admin.page-header>

<x-admin.flash />

<x-admin.search-toolbar
    action="{{ route('admin.roles.index') }}"
    live-target="#roles-results"
    placeholder="Cari nama, kode, atau deskripsi role..."
    :search="request('search')"
/>

{{-- Hasil: card ringkasan + tabel detail (di-refresh real-time oleh live filter) --}}
<div id="roles-results" class="admin-live-wrap">
    @include('admin.roles._results', ['roles' => $roles])
</div>
@endsection
