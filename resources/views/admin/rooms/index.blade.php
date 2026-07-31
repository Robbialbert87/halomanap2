@extends('layouts.admin')

@section('title', 'Manajemen Ruangan - Halo MANAP')

@section('admin_content')

<x-admin.page-header
    title="Manajemen Ruangan"
    section="Master Data"
    crumb="Ruangan"
    icon="fa-door-open"
    gradient="violet"
>
    <x-admin.btn href="{{ route('admin.rooms.create') }}" icon="fa-plus">Tambah Ruangan</x-admin.btn>
</x-admin.page-header>

<x-admin.flash />

<x-admin.search-toolbar
    action="{{ route('admin.rooms.index') }}"
    live-target="#rooms-results"
    placeholder="Cari ruangan atau unit..."
    :search="request('search')"
/>

{{-- Hasil: mobile list + tabel desktop + pagination (di-refresh real-time oleh live filter) --}}
<div id="rooms-results" class="admin-live-wrap">
    @include('admin.rooms._table', ['rooms' => $rooms])
</div>

@endsection
