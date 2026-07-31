@extends('layouts.admin')

@section('title', 'Disposisi - Halo MANAP')

@section('admin_content')

<x-admin.page-header
    title="Disposisi"
    section="Administrasi"
    crumb="Disposisi"
    icon="fa-arrow-right-arrow-left"
    gradient="blue"
>
    <x-slot name="summary">Pantau disposisi dari seluruh unit</x-slot>
</x-admin.page-header>

<x-admin.search-toolbar
    action="{{ route('admin.dispositions.index') }}"
    live-target="#dispositions-results"
    :grid="true"
>
    <select name="status" class="admin-input text-[13px]">
        <option value="">Semua Status</option>
        @foreach($statuses as $s)
            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $s)) }}</option>
        @endforeach
    </select>
    <select name="unit_id" class="admin-input text-[13px]">
        <option value="">Semua Unit</option>
        @foreach($units as $unit)
            <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama }}</option>
        @endforeach
    </select>
</x-admin.search-toolbar>

{{-- Hasil: statistik + mobile list + tabel desktop + pagination (di-refresh real-time oleh live filter) --}}
<div id="dispositions-results" class="admin-live-wrap">
    @include('admin.dispositions._table', [
        'workflows' => $workflows,
        'statTotal' => $statTotal,
        'statMenunggu' => $statMenunggu,
        'statProses' => $statProses,
        'statTerlambat' => $statTerlambat,
    ])
</div>

@endsection
