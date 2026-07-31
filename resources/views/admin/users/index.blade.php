@extends('layouts.admin')

@section('title', 'Master Pengguna - Halo MANAP')

@section('admin_content')

<x-admin.page-header
    title="Master Pengguna"
    section="Master Data"
    crumb="Pengguna"
    icon="fa-users"
    gradient="emerald"
>
    <x-admin.btn href="{{ route('admin.users.create') }}" icon="fa-plus">Tambah Pengguna</x-admin.btn>
</x-admin.page-header>

<x-admin.flash />

<x-admin.search-toolbar
    action="{{ route('admin.users.index') }}"
    live-target="#users-results"
    placeholder="Cari NIP, Nama, atau No WA..."
    :search="request('search')"
    :grid="true"
>
    <select name="unit_id" class="admin-input text-[13px]">
        <option value="">Semua Unit</option>
        @foreach($units as $u)
            <option value="{{ $u->id }}" {{ request('unit_id') == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
        @endforeach
    </select>
    <select name="jabatan_id" class="admin-input text-[13px]">
        <option value="">Semua Jabatan</option>
        @foreach($jabatans as $j)
            <option value="{{ $j->id }}" {{ request('jabatan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama }}</option>
        @endforeach
    </select>
    <select name="role" class="admin-input text-[13px]">
        <option value="">Semua Role</option>
        @foreach($roles as $r)
            <option value="{{ $r->name }}" {{ request('role') == $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
        @endforeach
    </select>
</x-admin.search-toolbar>

{{-- Hasil: mobile list + tabel desktop + pagination (di-refresh real-time oleh live filter) --}}
<div id="users-results" class="admin-live-wrap">
    @include('admin.users._table', ['users' => $users])
</div>

@endsection
