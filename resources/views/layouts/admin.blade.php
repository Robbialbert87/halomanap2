@extends('layouts.app')

@section('content')

<div class="flex min-h-[100dvh] overflow-hidden bg-[#F8FAFC] text-slate-800">
    {{-- ========================================================================= --}}
    {{--                          DESKTOP SIDEBAR (md+)                            --}}
    {{-- ========================================================================= --}}
    <div class="hidden lg:block">
        <x-sidebar />
    </div>

    {{-- ========================================================================= --}}
    {{--                         MOBILE SLIDE-OVER MENU (PayApp style)             --}}
    {{-- ========================================================================= --}}
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-slate-950/45 backdrop-blur-sm z-[60] hidden lg:hidden" onclick="toggleMobileMenu()"></div>
    <div id="mobile-menu" class="fixed top-4 bottom-4 left-4 w-[17rem] admin-glass z-[60] transform -translate-x-[calc(100%+2rem)] transition-transform duration-300 overflow-y-auto shadow-2xl lg:hidden rounded-[1.75rem]">
        {{-- User Profile Card --}}
        <div class="mx-3 mt-3 p-3 bg-white/70 rounded-2xl border border-white/70 shadow-[0_18px_38px_-30px_rgba(15,23,42,.45)] flex items-center gap-2.5">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()?->nama ?? 'User') }}&background=2563eb&color=fff" class="w-10 h-10 rounded-xl shadow-sm" alt="User">
            <div class="flex-1 min-w-0">
                <p class="font-bold text-slate-900 text-[13px] truncate">{{ auth()->user()?->nama ?? 'User' }}</p>
                <p class="text-[10px] text-blue-600 font-semibold flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 admin-breathe"></span>{{ auth()->user()?->roles->first()?->name ?? 'User' }}</p>
            </div>
        </div>

        @php
            $mobileUser = auth()->user();
            $mobileRoleGroup = $mobileUser->getRoleGroup();
        @endphp

        {{-- Navigation --}}
        <nav class="px-2.5 py-3 space-y-0.5">
            @if($mobileRoleGroup === 'admin')

            <a href="/dashboard" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->is('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200/50 flex-shrink-0">
                    <i class="fa-solid fa-house text-white text-xs"></i>
                </span>
                Dashboard
            </a>

            <div class="pt-3 pb-0.5 px-2.5">
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.15em]">Pelayanan</p>
            </div>
            <a href="{{ route('admin.tickets.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('admin.tickets.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200/50 flex-shrink-0">
                    <i class="fa-solid fa-clipboard-list text-white text-xs"></i>
                </span>
                Pengaduan
            </a>
            <a href="{{ route('admin.dispositions.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('admin.dispositions.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center shadow-sm shadow-teal-200/50 flex-shrink-0">
                    <i class="fa-solid fa-arrow-right-arrow-left text-white text-xs"></i>
                </span>
                Disposisi
            </a>
            <a href="{{ route('admin.apresiasi.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('admin.apresiasi.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200/50 flex-shrink-0">
                    <i class="fa-solid fa-thumbs-up text-white text-xs"></i>
                </span>
                Apresiasi
            </a>

            @php $hasMaster = auth()->user()->can('manage-roles') || auth()->user()->can('manage-users') || auth()->user()->can('manage-jabatans') || auth()->user()->can('manage-units') || auth()->user()->can('manage-rooms') || auth()->user()->can('manage-categories'); @endphp
            @if($hasMaster)
            <div class="pt-3 pb-0.5 px-2.5">
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.15em]">Master Data</p>
            </div>
            @can('manage-roles')
            <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('admin.roles.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-sm shadow-indigo-200/50 flex-shrink-0">
                    <i class="fa-solid fa-shield-halved text-white text-xs"></i>
                </span>
                Role
            </a>
            @endcan
            @can('manage-users')
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-sm shadow-emerald-200/50 flex-shrink-0">
                    <i class="fa-solid fa-users text-white text-xs"></i>
                </span>
                Pengguna
            </a>
            @endcan
            @can('manage-jabatans')
            <a href="{{ route('admin.jabatans.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('admin.jabatans.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-sm shadow-orange-200/50 flex-shrink-0">
                    <i class="fa-solid fa-sitemap text-white text-xs"></i>
                </span>
                Jabatan
            </a>
            @endcan
            @can('manage-units')
            <a href="{{ route('admin.units.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('admin.units.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200/50 flex-shrink-0">
                    <i class="fa-solid fa-building text-white text-xs"></i>
                </span>
                Unit
            </a>
            @endcan
            @can('manage-rooms')
            <a href="{{ route('admin.rooms.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('admin.rooms.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center shadow-sm shadow-violet-200/50 flex-shrink-0">
                    <i class="fa-solid fa-door-open text-white text-xs"></i>
                </span>
                Ruangan
            </a>
            @endcan
            @can('manage-categories')
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-pink-400 to-pink-600 flex items-center justify-center shadow-sm shadow-pink-200/50 flex-shrink-0">
                    <i class="fa-solid fa-tags text-white text-xs"></i>
                </span>
                Kategori
            </a>
            @endcan
            @can('manage-units')
            <a href="{{ route('admin.unit-types.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('admin.unit-types.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-cyan-400 to-cyan-600 flex items-center justify-center shadow-sm shadow-cyan-200/50 flex-shrink-0">
                    <i class="fa-solid fa-tag text-white text-xs"></i>
                </span>
                Jenis Unit
            </a>
            @endcan
            @endif

            @php $canMonitoring = auth()->user()->can('manage-reports') || auth()->user()->hasRole('Admin Pengaduan'); @endphp
            @if($canMonitoring)
            <div class="pt-3 pb-0.5 px-2.5">
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.15em]">Monitoring & Laporan</p>
            </div>
            <a href="{{ route('admin.monitoring.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('admin.monitoring.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-sm shadow-emerald-200/50 flex-shrink-0">
                    <i class="fa-solid fa-chart-line text-white text-xs"></i>
                </span>
                Monitoring
            </a>
            <a href="{{ route('admin.laporan') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('admin.laporan') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200/50 flex-shrink-0">
                    <i class="fa-solid fa-file-lines text-white text-xs"></i>
                </span>
                Laporan
            </a>
            @endif

            @php $hasSettings = auth()->user()->can('manage-audit-trail') || auth()->user()->can('manage-whatsapp') || auth()->user()->can('manage-settings'); @endphp
            @if($hasSettings)
            <div class="pt-3 pb-0.5 px-2.5">
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.15em]">Pengaturan</p>
            </div>
            @can('manage-audit-trail')
            <a href="{{ route('direktur.audit-trail') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('direktur.audit-trail') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-slate-400 to-slate-600 flex items-center justify-center shadow-sm shadow-slate-200/50 flex-shrink-0">
                    <i class="fa-solid fa-magnifying-glass text-white text-xs"></i>
                </span>
                Audit Trail
            </a>
            @endcan
            @can('manage-whatsapp')
            <a href="{{ route('admin.whatsapp.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('admin.whatsapp.index') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center shadow-sm shadow-green-200/50 flex-shrink-0">
                    <i class="fa-brands fa-whatsapp text-white text-xs"></i>
                </span>
                WhatsApp Gateway
            </a>
            <a href="{{ route('admin.whatsapp.resend') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('admin.whatsapp.resend') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-sm shadow-orange-200/50 flex-shrink-0">
                    <i class="fa-solid fa-clock-rotate-left text-white text-xs"></i>
                </span>
                Kirim Ulang
            </a>
            <a href="{{ route('admin.whatsapp.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('admin.whatsapp.index') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-sm shadow-purple-200/50 flex-shrink-0">
                    <i class="fa-solid fa-qrcode text-white text-xs"></i>
                </span>
                WhatsApp Sessions
            </a>
            @endcan
            @endif

            @elseif($mobileRoleGroup === 'kepala_unit')
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200/50 flex-shrink-0">
                    <i class="fa-solid fa-house text-white text-xs"></i>
                </span>
                Dashboard
            </a>
            <a href="{{ route('dispositions.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('dispositions.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center shadow-sm shadow-teal-200/50 flex-shrink-0">
                    <i class="fa-solid fa-inbox text-white text-xs"></i>
                </span>
                Kotak Masuk Disposisi
            </a>
            <a href="{{ route('dalam-penanganan') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('dalam-penanganan') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center shadow-sm shadow-yellow-200/50 flex-shrink-0">
                    <i class="fa-solid fa-spinner text-white text-xs"></i>
                </span>
                Dalam Penanganan
            </a>
            <a href="{{ route('riwayat') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('riwayat') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center shadow-sm shadow-violet-200/50 flex-shrink-0">
                    <i class="fa-solid fa-clock-rotate-left text-white text-xs"></i>
                </span>
                Riwayat Pengaduan Unit
            </a>
            <a href="{{ route('laporan.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('laporan.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-sm shadow-emerald-200/50 flex-shrink-0">
                    <i class="fa-solid fa-file-lines text-white text-xs"></i>
                </span>
                Laporan Unit
            </a>

            @elseif($mobileRoleGroup === 'kasi')
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200/50 flex-shrink-0">
                    <i class="fa-solid fa-house text-white text-xs"></i>
                </span>
                Dashboard
            </a>
            <a href="{{ route('dispositions.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('dispositions.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center shadow-sm shadow-teal-200/50 flex-shrink-0">
                    <i class="fa-solid fa-inbox text-white text-xs"></i>
                </span>
                Kotak Masuk Disposisi
            </a>
            <a href="{{ route('dalam-penanganan') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('dalam-penanganan') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center shadow-sm shadow-yellow-200/50 flex-shrink-0">
                    <i class="fa-solid fa-spinner text-white text-xs"></i>
                </span>
                Dalam Penanganan
            </a>
            <a href="{{ route('riwayat') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('riwayat') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center shadow-sm shadow-violet-200/50 flex-shrink-0">
                    <i class="fa-solid fa-clock-rotate-left text-white text-xs"></i>
                </span>
                Riwayat Pengaduan Bidang
            </a>
            <a href="{{ route('laporan.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('laporan.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-sm shadow-emerald-200/50 flex-shrink-0">
                    <i class="fa-solid fa-file-lines text-white text-xs"></i>
                </span>
                Laporan Bidang
            </a>

            @elseif($mobileRoleGroup === 'kabid')
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200/50 flex-shrink-0">
                    <i class="fa-solid fa-house text-white text-xs"></i>
                </span>
                Dashboard
            </a>
            <a href="{{ route('dispositions.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('dispositions.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center shadow-sm shadow-teal-200/50 flex-shrink-0">
                    <i class="fa-solid fa-inbox text-white text-xs"></i>
                </span>
                Kotak Masuk Disposisi
            </a>
            <a href="{{ route('dalam-penanganan') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('dalam-penanganan') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center shadow-sm shadow-yellow-200/50 flex-shrink-0">
                    <i class="fa-solid fa-spinner text-white text-xs"></i>
                </span>
                Dalam Penanganan
            </a>
            <a href="{{ route('riwayat') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('riwayat') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center shadow-sm shadow-violet-200/50 flex-shrink-0">
                    <i class="fa-solid fa-clock-rotate-left text-white text-xs"></i>
                </span>
                Riwayat Pengaduan
            </a>
            <a href="{{ route('laporan.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('laporan.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-sm shadow-amber-200/50 flex-shrink-0">
                    <i class="fa-solid fa-file-lines text-white text-xs"></i>
                </span>
                Laporan Bidang
            </a>

            @elseif($mobileRoleGroup === 'head_unit')
            <a href="{{ route('dispositions.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('dispositions.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center shadow-sm shadow-teal-200/50 flex-shrink-0">
                    <i class="fa-solid fa-inbox text-white text-xs"></i>
                </span>
                Kotak Masuk
            </a>
            <a href="{{ route('dalam-penanganan') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('dalam-penanganan') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center shadow-sm shadow-yellow-200/50 flex-shrink-0">
                    <i class="fa-solid fa-spinner text-white text-xs"></i>
                </span>
                Dalam Penanganan
            </a>
            <a href="{{ route('riwayat') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('riwayat') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center shadow-sm shadow-violet-200/50 flex-shrink-0">
                    <i class="fa-solid fa-clock-rotate-left text-white text-xs"></i>
                </span>
                Riwayat Pengaduan
            </a>

            @elseif($mobileRoleGroup === 'direktur')
            <a href="{{ route('direktur.dashboard') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('direktur.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200/50 flex-shrink-0">
                    <i class="fa-solid fa-chart-pie text-white text-xs"></i>
                </span>
                Dashboard Monitoring
            </a>
            <a href="{{ route('direktur.monitoring-workflow') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('direktur.monitoring-workflow') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center shadow-sm shadow-violet-200/50 flex-shrink-0">
                    <i class="fa-solid fa-diagram-project text-white text-xs"></i>
                </span>
                Monitoring Workflow
            </a>
            <a href="{{ route('direktur.statistik') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('direktur.statistik') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-sm shadow-emerald-200/50 flex-shrink-0">
                    <i class="fa-solid fa-chart-bar text-white text-xs"></i>
                </span>
                Statistik Pengaduan
            </a>
            <a href="{{ route('direktur.laporan') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('direktur.laporan') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-sm shadow-amber-200/50 flex-shrink-0">
                    <i class="fa-solid fa-file-lines text-white text-xs"></i>
                </span>
                Laporan
            </a>
            <a href="{{ route('direktur.audit-trail') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl transition-colors text-[13px] {{ request()->routeIs('direktur.audit-trail') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50' }}">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-slate-400 to-slate-600 flex items-center justify-center shadow-sm shadow-slate-200/50 flex-shrink-0">
                    <i class="fa-solid fa-magnifying-glass text-white text-xs"></i>
                </span>
                Audit Trail
            </a>
            @endif
        </nav>

        {{-- Logout --}}
        <div class="px-3 py-3 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-gradient-to-br from-red-50 to-white border border-red-100 text-red-500 font-semibold rounded-xl px-3 py-2 text-[13px] hover:from-red-100 hover:to-red-50 active:scale-[0.98] transition-all">
                    <i class="fa-solid fa-right-from-bracket text-xs"></i>
                    <span>Keluar / Logout</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Main Wrapper --}}
    <div id="main-wrapper" class="flex flex-col flex-1 w-full lg:pl-64 transition-all duration-300 h-full relative">
        
        {{-- ========================================================================= --}}
        {{--                          DESKTOP HEADER (md+)                             --}}
        {{-- ========================================================================= --}}
        <div class="hidden md:block">
            <x-header />
        </div>

        {{-- ========================================================================= --}}
        {{--                          MOBILE HEADER (< md) Glossy                      --}}
        {{-- ========================================================================= --}}
        <header class="md:hidden fixed top-3 left-3 right-3 bg-white/70 backdrop-blur-xl z-50 rounded-2xl border border-white/30 shadow-[0_4px_30px_rgba(0,0,0,0.08)]" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
            <div class="flex items-center justify-between px-4 h-14">
                <div class="flex items-center gap-2">
                    <picture>
                        <source srcset="{{ asset('assets/images/halomanaplogo.webp') }}" type="image/webp">
                        <img src="{{ asset('assets/images/halomanaplogo.png') }}" alt="Logo" class="h-6 w-auto">
                    </picture>
                    <span class="font-heading font-bold text-base tracking-wide text-blue-800">Halo<span class="text-green-600">MANAP</span></span>
                </div>
                <div class="flex items-center gap-2">
                    {{-- Notifikasi bell (PayApp style - triggers offcanvas panel) --}}
                    <button id="bell-btn" onclick="toggleNotifPanel()"
                        class="relative text-gray-500 hover:text-blue-600 transition-colors">
                        <i class="fa-regular fa-bell text-xl"></i>
                        @if($unreadCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center border border-white">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                        @endif
                    </button>
                    {{-- Profile avatar with PayApp-style dropdown --}}
                    <div class="relative" id="profileDropdownMobile">
                        <button onclick="toggleProfileMenuMobile()" class="block">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()?->nama ?? 'User') }}&background=eff6ff&color=1e3a8a"
                                alt="Profile" class="w-8 h-8 rounded-full border-2 border-white shadow-sm cursor-pointer">
                        </button>
                        <div id="profileMenuMobile"
                            class="hidden absolute right-0 top-full mt-3 w-56 bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 z-50 overflow-hidden" style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.6) 100%); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
                            <div class="px-4 py-3.5 border-b border-gray-100">
                                <p class="text-sm font-bold text-gray-800 font-heading truncate">{{ auth()->user()?->nama ?? 'User' }}</p>
                                <p class="text-[11px] text-blue-500 font-medium truncate mt-0.5">{{ auth()->user()?->roles->first()?->name ?? 'Pegawai' }}</p>
                            </div>
                            <a href="{{ route('admin.profil') }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-blue-50 transition-colors active:bg-blue-100 border-b border-gray-50">
                                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200/50 flex-shrink-0">
                                    <i class="fa-solid fa-user-gear text-white text-xs"></i>
                                </span>
                                <span class="font-medium">Profil & Pengaturan</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors active:bg-red-100">
                                    <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center shadow-sm shadow-red-200/50 flex-shrink-0">
                                        <i class="fa-solid fa-right-from-bracket text-white text-xs"></i>
                                    </span>
                                    <span class="font-medium">Keluar / Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Notifikasi Panel (PayApp offcanvas-top style) --}}
        <div id="notifPanel" class="md:hidden fixed left-3 right-3 bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 z-50 transition-all duration-300 overflow-hidden" style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.6) 100%); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); top: -500px; opacity: 0; max-height: 420px;">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200/50">
                        <i class="fa-regular fa-bell text-white text-sm"></i>
                    </span>
                    <div>
                        <p class="text-sm font-bold text-gray-800 font-heading">Notifikasi</p>
                        @if($unreadCount > 0)
                        <p class="text-[10px] text-blue-500 font-medium -mt-0.5">{{ $unreadCount }} belum dibaca</p>
                        @endif
                    </div>
                </div>
                <button onclick="toggleNotifPanel()" class="w-8 h-8 rounded-xl bg-gray-100 text-gray-500 hover:bg-gray-200 flex items-center justify-center transition-all">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>
            <div class="overflow-y-auto" style="max-height: 300px;">
                @forelse($notifications as $notif)
                <a href="{{ $notif['url'] ?? route('admin.tickets.show', $notif['id']) }}"
                    data-ticket-id="{{ $notif['id'] }}"
                    class="notif-link block border-l-4 {{ $notif['notif_type'] === 'selesai' ? 'border-green-500 bg-green-50/30 hover:bg-green-100/50' : 'border-blue-500 bg-blue-50/30 hover:bg-blue-100/50' }} transition-colors border-b border-gray-50 last:border-0 active:bg-blue-100">
                    <div class="flex items-center gap-3 px-4 py-3.5">
                        @if($notif['notif_type'] === 'selesai')
                        <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center shadow-sm shadow-green-200/50 flex-shrink-0">
                            <i class="fa-solid fa-check text-white text-sm"></i>
                        </span>
                        @else
                        <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200/50 flex-shrink-0">
                            <i class="fa-solid {{ $notif['icon'] ?? 'fa-file-lines' }} text-white text-sm"></i>
                        </span>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $notif['title'] }}</p>
                            <p class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-xs font-bold text-blue-700">{{ $notif['ticket_number'] }}</span>
                                @if($notif['category'])
                                <span class="text-[10px] text-gray-400">·</span>
                                <span class="text-[10px] font-medium text-gray-500">{{ $notif['category'] }}</span>
                                @endif
                                @if($notif['notif_type'] === 'selesai')
                                <span class="text-[10px] text-green-600 font-medium">· Selesai</span>
                                @endif
                            </p>
                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $notif['time'] }}</p>
                        </div>
                        @if($notif['notif_type'] === 'selesai')
                        <span class="w-2 h-2 rounded-full bg-green-500 shrink-0"></span>
                        @else
                        <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>
                        @endif
                    </div>
                </a>
                @empty
                <div class="px-4 py-10 text-center text-gray-400 text-sm">
                    <i class="fa-regular fa-bell-slash text-3xl mb-2 block"></i>
                    Tidak ada notifikasi baru
                </div>
                @endforelse
            </div>
            <a href="{{ route('admin.tickets.index', ['status' => 'NEW']) }}"
                class="block text-center text-sm font-semibold text-blue-600 hover:bg-blue-50 py-3.5 rounded-b-2xl border-t border-gray-100 transition-colors active:bg-blue-100">
                Lihat Semua Pengaduan Baru
            </a>
        </div>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto no-scrollbar px-4 lg:px-8 pt-[72px] pb-24 md:pt-6 md:pb-8">
            @yield('admin_content')
        </main>

        {{-- ========================================================================= --}}
        {{--                          MOBILE BOTTOM NAV (< md) Detached Rounded Glossy    --}}
        {{-- ========================================================================= --}}
        <nav class="md:hidden fixed bottom-3 left-3 right-3 admin-glass rounded-[1.4rem] flex justify-around items-center px-2 pt-1.5 pb-2 z-40">
            {{-- Dashboard (all roles) --}}
            <a href="/dashboard" class="flex flex-col items-center gap-0.5 w-12 {{ request()->is('dashboard') ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-500 transition-colors">
                <i class="fa-solid fa-house text-lg"></i>
                <span class="text-[8px] font-medium">Dashboard</span>
            </a>

            {{-- Primary feature by role --}}
            @if($mobileRoleGroup === 'admin')
            <a href="{{ route('admin.tickets.index') }}" class="flex flex-col items-center gap-0.5 w-12 {{ request()->is('admin/tickets*') ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-500 transition-colors">
                <i class="fa-solid fa-clipboard-list text-lg"></i>
                <span class="text-[8px] font-medium">Pengaduan</span>
            </a>
            @elseif(in_array($mobileRoleGroup, ['kepala_unit', 'kasi', 'kabid', 'head_unit']))
            <a href="{{ route('dispositions.index') }}" class="flex flex-col items-center gap-0.5 w-12 {{ request()->routeIs('dispositions.*') ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-500 transition-colors">
                <i class="fa-solid fa-inbox text-lg"></i>
                <span class="text-[8px] font-medium">Disposisi</span>
            </a>
            @elseif($mobileRoleGroup === 'direktur')
            <a href="{{ route('direktur.statistik') }}" class="flex flex-col items-center gap-0.5 w-12 {{ request()->routeIs('direktur.statistik') ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-500 transition-colors">
                <i class="fa-solid fa-chart-bar text-lg"></i>
                <span class="text-[8px] font-medium">Statistik</span>
            </a>
            @endif

            {{-- FAB: Buat Pengaduan Baru --}}
            <div class="relative w-12 flex flex-col items-center">
                <a href="{{ route('pengaduan.create') }}" class="absolute -top-7 w-11 h-11 bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30 border-[3px] border-white active:scale-90 transition-transform">
                    <i class="fa-solid fa-plus text-lg"></i>
                </a>
                <span class="text-[8px] font-medium text-gray-400 mt-5 text-center leading-tight">Buat</span>
            </div>

            {{-- Secondary feature by role --}}
            @if($mobileRoleGroup === 'admin')
            <a href="{{ route('admin.monitoring.index') }}" class="flex flex-col items-center gap-0.5 w-12 {{ request()->routeIs('admin.monitoring.*') ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-500 transition-colors">
                <i class="fa-solid fa-chart-line text-lg"></i>
                <span class="text-[8px] font-medium">Monitoring</span>
            </a>

            @elseif(in_array($mobileRoleGroup, ['kepala_unit', 'kasi', 'kabid']))
            <a href="{{ route('laporan.index') }}" class="flex flex-col items-center gap-0.5 w-12 {{ request()->routeIs('laporan.*') ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-500 transition-colors">
                <i class="fa-solid fa-file-lines text-lg"></i>
                <span class="text-[8px] font-medium">Laporan</span>
            </a>
            @elseif($mobileRoleGroup === 'direktur')
            <a href="{{ route('direktur.laporan') }}" class="flex flex-col items-center gap-0.5 w-12 {{ request()->routeIs('direktur.laporan') ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-500 transition-colors">
                <i class="fa-solid fa-file-lines text-lg"></i>
                <span class="text-[8px] font-medium">Laporan</span>
            </a>
            @else
            <div class="w-12"></div>
            @endif

            {{-- Menu --}}
            <button onclick="toggleMobileMenu()" class="flex flex-col items-center gap-0.5 w-12 text-gray-400 hover:text-blue-500 transition-colors">
                <i class="fa-solid fa-bars text-lg"></i>
                <span class="text-[8px] font-medium">Menu</span>
            </button>
        </nav>
    </div>
</div>

<script>
function toggleSidebar() {
    const isDesktop = window.matchMedia('(min-width: 1024px)').matches;

    // Mobile / tablet (< lg): buka drawer mobile-menu
    if (!isDesktop) {
        toggleMobileMenu();
        return;
    }

    // Desktop (lg+): collapse / expand sidebar
    const sidebar = document.getElementById('sidebar');
    const main = document.getElementById('main-wrapper');
    if (!sidebar || !main) return;

    const collapsed = sidebar.dataset.collapsed === '1';
    if (collapsed) {
        sidebar.dataset.collapsed = '0';
        sidebar.style.transform = '';
        main.style.paddingLeft = '';
        localStorage.setItem('admin-sidebar', 'expanded');
    } else {
        sidebar.dataset.collapsed = '1';
        sidebar.style.transform = 'translateX(-100%)';
        main.style.paddingLeft = '0';
        localStorage.setItem('admin-sidebar', 'collapsed');
    }
}

// Restore collapsed state pada load (desktop)
(function restoreSidebar() {
    if (localStorage.getItem('admin-sidebar') === 'collapsed' && window.matchMedia('(min-width: 1024px)').matches) {
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main-wrapper');
        if (sidebar && main) {
            sidebar.dataset.collapsed = '1';
            sidebar.style.transform = 'translateX(-100%)';
            main.style.paddingLeft = '0';
        }
    }
})();

function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    const overlay = document.getElementById('mobile-menu-overlay');
    const isOpen = !menu.classList.contains('-translate-x-[calc(100%+2rem)]');
    if (isOpen) {
        menu.classList.add('-translate-x-[calc(100%+2rem)]');
        overlay.classList.add('hidden');
    } else {
        menu.classList.remove('-translate-x-[calc(100%+2rem)]');
        overlay.classList.remove('hidden');
    }
    closeNotifPanel();
    closeProfileMenuMobile();
}

function closeNotifPanel() {
    const panel = document.getElementById('notifPanel');
    if (panel) {
        panel.style.top = '-500px';
        panel.style.opacity = '0';
    }
}

function toggleNotifPanel() {
    const panel = document.getElementById('notifPanel');
    const isOpen = panel.style.top !== '-500px';
    if (isOpen) {
        closeNotifPanel();
    } else {
        panel.style.top = '76px';
        panel.style.opacity = '1';
    }
}

document.addEventListener('click', function (e) {
    const panel = document.getElementById('notifPanel');
    const bellBtn = document.getElementById('bell-btn');
    if (panel && panel.style.top !== '-500px' && (!bellBtn || !bellBtn.contains(e.target)) && !panel.contains(e.target)) {
        closeNotifPanel();
    }
    const profileDropdown = document.getElementById('profileDropdownMobile');
    const profileMenu = document.getElementById('profileMenuMobile');
    if (profileDropdown && profileMenu && !profileMenu.classList.contains('hidden') && !profileDropdown.contains(e.target)) {
        profileMenu.classList.add('hidden');
    }
});

function toggleProfileMenuMobile() {
    const menu = document.getElementById('profileMenuMobile');
    menu.classList.toggle('hidden');
    closeNotifPanel();
}

function closeProfileMenuMobile() {
    const menu = document.getElementById('profileMenuMobile');
    if (menu) menu.classList.add('hidden');
}

// === Live Filter: tabel + pagination tanpa reload (real-time) ===
// Pakai pada form filter: data-live-filter="#selector" (target container hasil).
// Server merespons fragment HTML saat header X-Live-Filter: 1 (lihat controller).
function initLiveFilters() {
    document.querySelectorAll('[data-live-filter]').forEach(function (form) {
        var wrap = document.querySelector(form.getAttribute('data-live-filter'));
        if (!wrap) return;

        var timer = null;
        var basePath = form.action.split('?')[0];
        var lastUrl = null;

        function load(url, updateHistory) {
            lastUrl = url;
            wrap.classList.add('is-loading');
            fetch(url, {
                headers: {
                    'X-Live-Filter': '1',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html, */*; q=0.01',
                },
                credentials: 'same-origin',
            })
                .then(function (res) {
                    if (!res.ok) throw new Error('live filter request failed');
                    return res.text();
                })
                .then(function (html) {
                    wrap.innerHTML = html;
                    wireLinks();
                    if (updateHistory) history.replaceState(null, '', url);
                    // Sinkronkan tombol export (mis. Export PDF) dengan filter aktif
                    var params = new URLSearchParams(new URL(url, window.location.href).searchParams);
                    params.delete('page');
                    document.querySelectorAll('a[data-live-export]').forEach(function (a) {
                        var base = a.getAttribute('data-live-export') || a.href.split('?')[0];
                        a.href = base + '?' + params.toString();
                    });
                })
                .catch(function () {
                    // Error inline + tombol retry (tanpa reload halaman penuh)
                    wrap.innerHTML =
                        '<div class="admin-empty" role="alert">' +
                        '<i class="fa-solid fa-triangle-exclamation"></i>' +
                        '<p>Gagal memuat data</p>' +
                        '<span>Periksa koneksi lalu coba lagi.</span>' +
                        '<button type="button" class="admin-btn admin-btn-ghost admin-retry-btn" style="margin-top:.5rem">' +
                        '<i class="fa-solid fa-rotate-right"></i> Coba Lagi</button>' +
                        '</div>';
                    var retry = wrap.querySelector('.admin-retry-btn');
                    if (retry) retry.addEventListener('click', function () { load(lastUrl, true); });
                })
                .finally(function () {
                    wrap.classList.remove('is-loading');
                });
        }

        function runFromForm() {
            var params = new URLSearchParams(new FormData(form));
            params.delete('page'); // filter berubah -> kembali ke halaman 1
            load(basePath + '?' + params.toString(), true);
        }

        // Pagination links dalam hasil ikut di-intercept agar tetap tanpa reload.
        // HANYA link ke path yang sama dengan filter (mis. /admin/tickets?page=2).
        // Link ke path lain (detail /admin/tickets/5, create /admin/tickets/create,
        // aksi lain) dibiarkan agar navigasi reload penuh — mencegah "double layout"
        // di mana halaman penuh (sidebar + header) ter-inject ke dalam konten.
        function wireLinks() {
            wrap.querySelectorAll('a[href]').forEach(function (a) {
                var href = a.getAttribute('href') || '';
                var path = href.split('?')[0];
                if (path !== basePath) return;
                if (a.dataset.liveWired) return;
                a.dataset.liveWired = '1';
                a.addEventListener('click', function (e) {
                    e.preventDefault();
                    load(href, true);
                    wrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            });
        }

        form.querySelectorAll('input[name="search"]').forEach(function (inp) {
            inp.addEventListener('input', function () {
                clearTimeout(timer);
                timer = setTimeout(runFromForm, 300);
            });
        });
        form.querySelectorAll('select').forEach(function (sel) {
            sel.addEventListener('change', runFromForm);
        });
        form.addEventListener('submit', function (e) { e.preventDefault(); runFromForm(); });
        var reset = form.querySelector('[data-live-reset]');
        if (reset) {
            reset.addEventListener('click', function (e) {
                e.preventDefault();
                form.reset();
                runFromForm();
            });
        }
        wireLinks();
    });
}
document.addEventListener('DOMContentLoaded', initLiveFilters);
</script>

@endsection
