@extends('layouts.admin')

@section('title', 'Data Apresiasi - Halo MANAP')

@section('admin_content')
<div class="animate-fadeInUp">
    <x-admin.page-header
        title="Data Apresiasi"
        title-mobile="Apresiasi"
        section="Data"
        crumb="Apresiasi"
        icon="fa-thumbs-up"
        gradient="blue"
    >
        <x-slot name="summary">Daftar apresiasi dari masyarakat</x-slot>
        <div class="text-sm text-gray-400">
            Total: <span class="font-bold text-gray-700">{{ $appreciations->total() }}</span>
        </div>
    </x-admin.page-header>

    <x-admin.search-toolbar
        action="{{ route('admin.apresiasi.index') }}"
        live-target="#apresiasi-results"
        placeholder="Cari nama atau pesan apresiasi..."
        :search="request('search')"
        :grid="true"
    >
        <select name="rating" class="admin-input text-[13px]">
            <option value="">Semua Rating</option>
            @for($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
            @endfor
        </select>
    </x-admin.search-toolbar>

    {{-- Hasil: mobile list + tabel desktop + pagination (di-refresh real-time oleh live filter) --}}
    <div id="apresiasi-results" class="admin-live-wrap">
        @include('admin.apresiasi._table', ['appreciations' => $appreciations])
    </div>
</div>
@endsection
