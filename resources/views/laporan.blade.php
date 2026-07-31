@extends('layouts.admin')

@section('title', 'Laporan Bidang - Halo MANAP')

@section('admin_content')
<x-admin.page-header
    title="Laporan Bidang"
    :section="$user->getRoleLabel()"
    crumb="Laporan"
    icon="fa-file-lines"
    gradient="indigo"
></x-admin.page-header>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="text-center py-12 text-gray-400">
        <i class="fa-solid fa-file-lines text-5xl mb-4"></i>
        <p>Laporan bidang akan ditampilkan di sini</p>
    </div>
</div>
@endsection
