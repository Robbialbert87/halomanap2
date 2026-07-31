@extends('layouts.admin')

@section('title', 'Master Unit - Halo MANAP')

@section('admin_content')

<x-admin.page-header
    title="Master Unit"
    section="Master Data"
    crumb="Unit"
    icon="fa-building"
    gradient="blue"
>
    <x-admin.btn href="{{ route('admin.units.create') }}" icon="fa-plus">Tambah Unit</x-admin.btn>
</x-admin.page-header>

<x-admin.flash />

<x-admin.search-toolbar
    action="{{ route('admin.units.index') }}"
    live-target="#units-results"
    placeholder="Cari kode atau nama unit..."
    :search="request('search')"
    :grid="true"
>
    <select name="jenis" class="admin-input text-[13px]">
        <option value="">Semua Jenis</option>
        @foreach($jenisList as $j)
            <option value="{{ $j }}" {{ request('jenis') == $j ? 'selected' : '' }}>{{ $j }}</option>
        @endforeach
    </select>
</x-admin.search-toolbar>

{{-- Hasil: mobile list + tabel desktop + pagination (di-refresh real-time oleh live filter) --}}
<div id="units-results" class="admin-live-wrap">
    @include('admin.units._table', ['units' => $units])
</div>

@endsection
