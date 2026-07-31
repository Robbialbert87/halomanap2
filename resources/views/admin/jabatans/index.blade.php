@extends('layouts.admin')

@section('title', 'Master Jabatan - Halo MANAP')

@section('admin_content')

<x-admin.page-header
    title="Master Jabatan"
    section="Master Data"
    crumb="Jabatan"
    icon="fa-sitemap"
    gradient="orange"
>
    <x-admin.btn href="{{ route('admin.jabatans.create') }}" icon="fa-plus">Tambah Jabatan</x-admin.btn>
</x-admin.page-header>

<x-admin.flash />

<x-admin.search-toolbar
    action="{{ route('admin.jabatans.index') }}"
    live-target="#jabatans-results"
    placeholder="Cari kode, nama, atau kategori jabatan..."
    :search="request('search')"
/>

{{-- Hasil: mobile list + tabel desktop + pagination (di-refresh real-time oleh live filter) --}}
<div id="jabatans-results" class="admin-live-wrap">
    @include('admin.jabatans._table', ['jabatans' => $jabatans])
</div>

@endsection
