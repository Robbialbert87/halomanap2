@extends('layouts.app')

@section('content')

<div class="flex h-screen bg-[#F3F4F6] overflow-hidden">
    {{-- ========================================================================= --}}
    {{--                          DESKTOP SIDEBAR (md+)                            --}}
    {{-- ========================================================================= --}}
    <div class="hidden lg:block">
        @include('components.sidebar')
    </div>

    {{-- ========================================================================= --}}
    {{--                         MOBILE SLIDE-OVER MENU                            --}}
    {{-- ========================================================================= --}}
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/60 z-50 hidden lg:hidden" onclick="toggleMobileMenu()"></div>
    <div id="mobile-menu" class="fixed inset-y-0 left-0 w-72 bg-[#1e293b] z-50 transform -translate-x-full transition-transform duration-300 overflow-y-auto lg:hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-700">
            <div class="flex items-center gap-2">
                <img src="{{ asset('assets/images/halomanaplogo.png') }}" alt="Logo" class="h-6 w-auto">
                <span class="font-bold text-white">HaloMANAP</span>
            </div>
            <button onclick="toggleMobileMenu()" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <div class="px-4 py-3 border-b border-slate-700 flex items-center gap-3">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()?->nama ?? 'User') }}&background=3b82f6&color=fff" class="w-9 h-9 rounded-full" alt="User">
            <div>
                <p class="text-sm font-semibold text-white">{{ auth()->user()?->nama }}</p>
                <p class="text-xs text-green-400">● Online</p>
            </div>
        </div>
        @php
            $mobileUser = auth()->user();
            $mobileRoleGroup = \App\Services\RoleMenuService::getRoleGroup($mobileUser);
        @endphp
        <nav class="py-3 px-3 space-y-0.5">
            @if($mobileRoleGroup === 'admin')
            <a href="/dashboard" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-house w-5 text-center"></i> Dashboard
            </a>

            <div class="pt-3 pb-1 px-3">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pelayanan</p>
            </div>
            <a href="{{ route('admin.tickets.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-clipboard-list w-5 text-center"></i> Pengaduan
            </a>
            <a href="{{ route('admin.dispositions.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-arrow-right-arrow-left w-5 text-center"></i> Disposisi
            </a>

            @php $hasMaster = auth()->user()->can('manage-roles') || auth()->user()->can('manage-users') || auth()->user()->can('manage-jabatans') || auth()->user()->can('manage-units') || auth()->user()->can('manage-rooms') || auth()->user()->can('manage-categories'); @endphp
            @if($hasMaster)
            <div class="pt-3 pb-1 px-3">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Master Data</p>
            </div>
            @can('manage-roles')
            <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-shield-halved w-5 text-center"></i> Role
            </a>
            @endcan
            @can('manage-users')
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-users w-5 text-center"></i> Pengguna
            </a>
            @endcan
            @can('manage-jabatans')
            <a href="{{ route('admin.jabatans.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-sitemap w-5 text-center"></i> Jabatan
            </a>
            @endcan
            @can('manage-units')
            <a href="{{ route('admin.units.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-building w-5 text-center"></i> Unit
            </a>
            @endcan
            @can('manage-rooms')
            <a href="{{ route('admin.rooms.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-door-open w-5 text-center"></i> Ruangan
            </a>
            @endcan
            @can('manage-categories')
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-tags w-5 text-center"></i> Kategori
            </a>
            @endcan
            @can('manage-units')
            <a href="{{ route('admin.unit-types.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-tag w-5 text-center"></i> Jenis Unit
            </a>
            @endcan
            @endif

            @can('manage-reports')
            <div class="pt-3 pb-1 px-3">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Monitoring & Laporan</p>
            </div>
            <a href="{{ route('admin.monitoring.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-chart-line w-5 text-center"></i> Monitoring
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-file-lines w-5 text-center"></i> Laporan
            </a>
            @endcan

            @php $hasSettings = auth()->user()->can('manage-audit-trail') || auth()->user()->can('manage-whatsapp') || auth()->user()->can('manage-settings'); @endphp
            @if($hasSettings)
            <div class="pt-3 pb-1 px-3">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengaturan</p>
            </div>
            @can('manage-audit-trail')
            <a href="{{ route('direktur.audit-trail') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-magnifying-glass w-5 text-center"></i> Audit Trail
            </a>
            @endcan
            @can('manage-whatsapp')
            <a href="{{ route('admin.whatsapp.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-brands fa-whatsapp w-5 text-center"></i> WhatsApp Gateway
            </a>
            @endcan
            @can('manage-settings')
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-gear w-5 text-center"></i> Pengaturan
            </a>
            @endcan
            @endif

            @elseif($mobileRoleGroup === 'kepala_unit')
            <a href="{{ route('kepala-unit.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-house w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('kepala-unit.dispositions.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-inbox w-5 text-center"></i> Kotak Masuk Disposisi
            </a>
            <a href="{{ route('kepala-unit.dalam-penanganan') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-spinner w-5 text-center"></i> Dalam Penanganan
            </a>
            <a href="{{ route('kepala-unit.riwayat') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i> Riwayat Pengaduan Unit
            </a>
            <a href="{{ route('kepala-unit.laporan') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-file-lines w-5 text-center"></i> Laporan Unit
            </a>

            @elseif($mobileRoleGroup === 'kasi')
            <a href="{{ route('kasi.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-house w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('kasi.dispositions.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-inbox w-5 text-center"></i> Kotak Masuk Disposisi
            </a>
            <a href="{{ route('kasi.dalam-penanganan') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-spinner w-5 text-center"></i> Dalam Penanganan
            </a>
            <a href="{{ route('kasi.riwayat') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i> Riwayat Pengaduan Bidang
            </a>
            <a href="{{ route('kasi.laporan') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-file-lines w-5 text-center"></i> Laporan Bidang
            </a>

            @elseif($mobileRoleGroup === 'kabid')
            <a href="{{ route('kabid.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-house w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('kabid.dispositions.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-inbox w-5 text-center"></i> Kotak Masuk Disposisi
            </a>
            <a href="{{ route('kabid.dalam-penanganan') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-spinner w-5 text-center"></i> Dalam Penanganan
            </a>
            <a href="{{ route('kabid.riwayat') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i> Riwayat Pengaduan
            </a>
            <a href="{{ route('kabid.monitoring') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-chart-line w-5 text-center"></i> Monitoring Bidang
            </a>
            <a href="{{ route('kabid.laporan') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-file-lines w-5 text-center"></i> Laporan Bidang
            </a>

            @elseif($mobileRoleGroup === 'head_unit')
            <a href="{{ route('head-unit.dispositions.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-inbox w-5 text-center"></i> Kotak Masuk
            </a>
            <a href="{{ route('head-unit.dalam-penanganan') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-spinner w-5 text-center"></i> Dalam Penanganan
            </a>
            <a href="{{ route('head-unit.riwayat') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i> Riwayat Pengaduan
            </a>

            @elseif($mobileRoleGroup === 'direktur')
            <a href="{{ route('direktur.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-chart-pie w-5 text-center"></i> Dashboard Monitoring
            </a>
            <a href="{{ route('direktur.monitoring-workflow') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-diagram-project w-5 text-center"></i> Monitoring Workflow
            </a>
            <a href="{{ route('direktur.statistik') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-chart-bar w-5 text-center"></i> Statistik Pengaduan
            </a>
            <a href="{{ route('direktur.laporan') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-file-lines w-5 text-center"></i> Laporan
            </a>
            <a href="{{ route('direktur.audit-trail') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors text-sm">
                <i class="fa-solid fa-magnifying-glass w-5 text-center"></i> Audit Trail
            </a>
            @endif
        </nav>

        <!-- Logout -->
        <div class="px-4 py-4 border-t border-slate-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 text-red-400 hover:bg-red-900/30 hover:text-red-300 rounded-lg transition-colors text-sm">
                    <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
                    <span>Keluar / Logout</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Main Wrapper --}}
    <div class="flex flex-col flex-1 w-full lg:pl-64 transition-all duration-300 h-full relative">
        
        {{-- ========================================================================= --}}
        {{--                          DESKTOP HEADER (md+)                             --}}
        {{-- ========================================================================= --}}
        <div class="hidden md:block">
            @include('components.header')
        </div>

        {{-- ========================================================================= --}}
        {{--                          MOBILE HEADER (< md)                             --}}
        {{-- ========================================================================= --}}
        <header class="md:hidden bg-[#1e293b] text-white sticky top-0 z-40 shadow-md">
            <div class="flex items-center justify-between px-4 h-14">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('assets/images/halomanaplogo.png') }}" alt="Logo" class="h-6 w-auto">
                    <span class="font-bold text-base tracking-wide">Halo<span class="text-blue-400">MANAP</span> <span class="text-xs font-normal text-slate-400">{{ auth()->user()?->roles->first()?->name ?? 'User' }}</span></span>
                </div>
                <div class="flex items-center gap-3">
                    <button class="text-slate-300 hover:text-white transition-colors" onclick="toggleMobileMenu()">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto p-4 lg:p-6 no-scrollbar pb-24 md:pb-6">
            @yield('admin_content')
        </main>

        {{-- ========================================================================= --}}
        {{--                          MOBILE BOTTOM NAV (< md)                         --}}
        {{-- ========================================================================= --}}
        <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 flex justify-around items-center px-2 py-1 pb-4 z-40 shadow-[0_-4px_20px_rgba(0,0,0,0.07)]">
            {{-- Dashboard (all roles) --}}
            <a href="/dashboard" class="flex flex-col items-center gap-0.5 w-14 {{ request()->is('dashboard') ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-500 transition-colors">
                <i class="fa-solid fa-house text-xl"></i>
                <span class="text-[9px] font-medium">Dashboard</span>
            </a>

            {{-- Primary feature by role --}}
            @if($mobileRoleGroup === 'admin')
            <a href="{{ route('admin.tickets.index') }}" class="flex flex-col items-center gap-0.5 w-14 {{ request()->is('admin/tickets*') ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-500 transition-colors">
                <i class="fa-solid fa-clipboard-list text-xl"></i>
                <span class="text-[9px] font-medium">Pengaduan</span>
            </a>
            @elseif(in_array($mobileRoleGroup, ['kepala_unit', 'kasi', 'kabid', 'head_unit']))
            <a href="{{ route(str_replace('_', '-', $mobileRoleGroup) . '.dispositions.index') }}" class="flex flex-col items-center gap-0.5 w-14 {{ request()->routeIs(str_replace('_', '-', $mobileRoleGroup) . '.dispositions.*') ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-500 transition-colors">
                <i class="fa-solid fa-inbox text-xl"></i>
                <span class="text-[9px] font-medium">Disposisi</span>
            </a>
            @elseif($mobileRoleGroup === 'direktur')
            <a href="{{ route('direktur.statistik') }}" class="flex flex-col items-center gap-0.5 w-14 {{ request()->routeIs('direktur.statistik') ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-500 transition-colors">
                <i class="fa-solid fa-chart-bar text-xl"></i>
                <span class="text-[9px] font-medium">Statistik</span>
            </a>
            @endif

            {{-- FAB: Buat Pengaduan Baru --}}
            <div class="relative w-14 flex justify-center">
                <a href="{{ route('pengaduan.create') }}" class="absolute -top-8 w-14 h-14 bg-blue-600 text-white rounded-full flex items-center justify-center shadow-lg shadow-blue-500/40">
                    <i class="fa-solid fa-plus text-xl"></i>
                </a>
                <span class="text-[9px] font-medium text-gray-400 mt-7 text-center">Buat</span>
            </div>

            {{-- Secondary feature by role --}}
            @if($mobileRoleGroup === 'admin' && auth()->user()->can('manage-units'))
            <a href="{{ route('admin.units.index') }}" class="flex flex-col items-center gap-0.5 w-14 {{ request()->is('admin/units*') || request()->is('admin/rooms*') || request()->is('admin/categories*') ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-500 transition-colors">
                <i class="fa-solid fa-database text-xl"></i>
                <span class="text-[9px] font-medium">Master</span>
            </a>
            @elseif(in_array($mobileRoleGroup, ['kepala_unit', 'kasi']))
            <a href="{{ route(str_replace('_', '-', $mobileRoleGroup) . '.laporan') }}" class="flex flex-col items-center gap-0.5 w-14 {{ request()->routeIs(str_replace('_', '-', $mobileRoleGroup) . '.laporan') ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-500 transition-colors">
                <i class="fa-solid fa-file-lines text-xl"></i>
                <span class="text-[9px] font-medium">Laporan</span>
            </a>
            @elseif($mobileRoleGroup === 'kabid')
            <a href="{{ route('kabid.monitoring') }}" class="flex flex-col items-center gap-0.5 w-14 {{ request()->routeIs('kabid.monitoring') ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-500 transition-colors">
                <i class="fa-solid fa-chart-line text-xl"></i>
                <span class="text-[9px] font-medium">Monitoring</span>
            </a>
            @elseif($mobileRoleGroup === 'direktur')
            <a href="{{ route('direktur.laporan') }}" class="flex flex-col items-center gap-0.5 w-14 {{ request()->routeIs('direktur.laporan') ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-500 transition-colors">
                <i class="fa-solid fa-file-lines text-xl"></i>
                <span class="text-[9px] font-medium">Laporan</span>
            </a>
            @else
            <div class="w-14"></div>
            @endif

            {{-- Menu --}}
            <button onclick="toggleMobileMenu()" class="flex flex-col items-center gap-0.5 w-14 text-gray-400 hover:text-blue-500 transition-colors">
                <i class="fa-solid fa-bars text-xl"></i>
                <span class="text-[9px] font-medium">Menu</span>
            </button>
        </nav>
    </div>
</div>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    const overlay = document.getElementById('mobile-menu-overlay');
    const isOpen = !menu.classList.contains('-translate-x-full');
    if (isOpen) {
        menu.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    } else {
        menu.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
    }
}
</script>

@endsection
