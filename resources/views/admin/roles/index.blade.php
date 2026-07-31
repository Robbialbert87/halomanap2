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
