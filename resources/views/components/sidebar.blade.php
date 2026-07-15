<aside class="fixed inset-y-0 left-0 bg-[#1e293b] w-64 text-white flex flex-col transition-transform duration-300 z-50 lg:translate-x-0 -translate-x-full" id="sidebar">
    <div class="flex items-center justify-center h-16 border-b border-slate-700 px-4">
        <div class="flex items-center gap-2">
            <img src="{{ asset('assets/images/halomanaplogo.png') }}" alt="Logo" class="h-8 w-auto">
            <span class="font-bold text-xl tracking-wide">Halo<span class="text-blue-400">MANAP</span></span>
        </div>
    </div>

    <div class="px-6 py-4 border-b border-slate-700 flex items-center gap-3">
        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()?->nama ?? 'User') }}&background=3b82f6&color=fff" alt="User" class="w-10 h-10 rounded-full">
        <div class="min-w-0 flex-1">
            <p class="text-sm font-semibold truncate">{{ auth()->user()?->nama }}</p>
            <p class="text-xs text-green-400 flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-400 inline-block"></span> Online</p>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 no-scrollbar">
        @php
            $user = auth()->user();
            $roleGroup = \App\Services\RoleMenuService::getRoleGroup($user);
            $currentRoute = request()->route()?->getName();
        @endphp

        {{-- ============================================================ --}}
        {{--  ADMIN / SUPER ADMIN                                          --}}
        {{-- ============================================================ --}}
        @if($roleGroup === 'admin')
            <a href="/dashboard" class="flex items-center gap-3 px-3 py-2.5 {{ request()->is('dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-house w-5 text-center"></i>
                <span class="text-sm font-medium">Dashboard</span>
            </a>

            {{-- ======================== PELAYANAN ======================== --}}
            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Pelayanan</p>
            </div>

            <a href="{{ route('admin.tickets.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.tickets.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-clipboard-list w-5 text-center"></i>
                <span class="text-sm font-medium">Pengaduan</span>
            </a>

            <a href="{{ route('admin.dispositions.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.dispositions.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-arrow-right-arrow-left w-5 text-center"></i>
                <span class="text-sm font-medium">Disposisi</span>
            </a>

            <a href="{{ route('admin.apresiasi.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.apresiasi.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-thumbs-up w-5 text-center"></i>
                <span class="text-sm font-medium">Apresiasi</span>
            </a>

            {{-- ======================== MASTER DATA ======================== --}}
            @php $hasMaster = auth()->user()->can('manage-roles') || auth()->user()->can('manage-users') || auth()->user()->can('manage-jabatans') || auth()->user()->can('manage-units') || auth()->user()->can('manage-rooms') || auth()->user()->can('manage-categories'); @endphp
            @if($hasMaster)
            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Master Data</p>
            </div>

            @can('manage-roles')
            <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.roles.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-shield-halved w-5 text-center"></i>
                <span class="text-sm font-medium">Role</span>
            </a>
            @endcan

            @can('manage-users')
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-users w-5 text-center"></i>
                <span class="text-sm font-medium">Pengguna</span>
            </a>
            @endcan

            @can('manage-jabatans')
            <a href="{{ route('admin.jabatans.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.jabatans.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-sitemap w-5 text-center"></i>
                <span class="text-sm font-medium">Jabatan</span>
            </a>
            @endcan

            @can('manage-units')
            <a href="{{ route('admin.units.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.units.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-regular fa-building w-5 text-center"></i>
                <span class="text-sm font-medium">Unit</span>
            </a>
            @endcan

            @can('manage-rooms')
            <a href="{{ route('admin.rooms.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.rooms.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-door-open w-5 text-center"></i>
                <span class="text-sm font-medium">Ruangan</span>
            </a>
            @endcan

            @can('manage-categories')
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.categories.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-tags w-5 text-center"></i>
                <span class="text-sm font-medium">Kategori</span>
            </a>
            @endcan
            @can('manage-units')
            <a href="{{ route('admin.unit-types.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.unit-types.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-tag w-5 text-center"></i>
                <span class="text-sm font-medium">Jenis Unit</span>
            </a>
            @endcan
            @endif

            {{-- ======================== MONITORING ======================== --}}
            @can('manage-reports')
            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Monitoring & Laporan</p>
            </div>
            @endcan

            @can('manage-reports')
            <a href="{{ route('admin.monitoring.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.monitoring.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-chart-line w-5 text-center"></i>
                <span class="text-sm font-medium">Monitoring</span>
            </a>
            <a href="{{ route('admin.laporan') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.laporan*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-file-lines w-5 text-center"></i>
                <span class="text-sm font-medium">Laporan</span>
            </a>
            @else
            @if(auth()->user()->hasRole('Admin Pengaduan'))
            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Monitoring & Laporan</p>
            </div>
            <a href="{{ route('admin.monitoring.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.monitoring.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-chart-line w-5 text-center"></i>
                <span class="text-sm font-medium">Monitoring</span>
            </a>
            <a href="{{ route('admin.laporan') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.laporan*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-file-lines w-5 text-center"></i>
                <span class="text-sm font-medium">Laporan</span>
            </a>
            @endif
            @endcan

            {{-- ======================== PENGATURAN ======================== --}}
            @php $hasSettings = auth()->user()->can('manage-audit-trail') || auth()->user()->can('manage-whatsapp') || auth()->user()->can('manage-settings'); @endphp
            @if($hasSettings)
            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Pengaturan</p>
            </div>

            @can('manage-audit-trail')
            <a href="{{ route('direktur.audit-trail') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('direktur.audit-trail') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-magnifying-glass w-5 text-center"></i>
                <span class="text-sm font-medium">Audit Trail</span>
            </a>
            @endcan

            @can('manage-whatsapp')
            <a href="{{ route('admin.whatsapp.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.whatsapp.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-brands fa-whatsapp w-5 text-center"></i>
                <span class="text-sm font-medium">WhatsApp Gateway</span>
            </a>
            @endcan

            @can('manage-settings')
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors">
                <i class="fa-solid fa-gear w-5 text-center"></i>
                <span class="text-sm font-medium">Pengaturan</span>
            </a>
            @endcan
            @endif

        {{-- ============================================================ --}}
        {{--  KEPALA UNIT                                                  --}}
        {{-- ============================================================ --}}
        @elseif($roleGroup === 'kepala_unit')
            <div class="pb-1">
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Menu Kepala Unit</p>
            </div>

            <a href="{{ route('kepala-unit.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('kepala-unit.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-house w-5 text-center"></i>
                <span class="text-sm font-medium">Dashboard</span>
            </a>

            <a href="{{ route('kepala-unit.dispositions.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('kepala-unit.dispositions.index') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-inbox w-5 text-center"></i>
                <span class="text-sm font-medium">Kotak Masuk Disposisi</span>
            </a>

            <a href="{{ route('kepala-unit.dalam-penanganan') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('kepala-unit.dalam-penanganan') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-spinner w-5 text-center"></i>
                <span class="text-sm font-medium">Dalam Penanganan</span>
            </a>

            <a href="{{ route('kepala-unit.riwayat') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('kepala-unit.riwayat') || (request()->routeIs('kepala-unit.dispositions.show') && str_contains(url()->previous(), 'riwayat')) ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i>
                <span class="text-sm font-medium">Riwayat Pengaduan</span>
            </a>

            <a href="{{ route('kepala-unit.laporan') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('kepala-unit.laporan') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-file-lines w-5 text-center"></i>
                <span class="text-sm font-medium">Laporan Unit</span>
            </a>

            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Akun</p>
            </div>

            <a href="{{ route('kepala-unit.profil') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('kepala-unit.profil') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-user w-5 text-center"></i>
                <span class="text-sm font-medium">Profil</span>
            </a>

        {{-- ============================================================ --}}
        {{--  KASI / KASUBBAG                                              --}}
        {{-- ============================================================ --}}
        @elseif($roleGroup === 'kasi')
            <div class="pb-1">
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Menu Kasi / Kasubbag</p>
            </div>

            <a href="{{ route('kasi.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('kasi.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-house w-5 text-center"></i>
                <span class="text-sm font-medium">Dashboard</span>
            </a>

            <a href="{{ route('kasi.dispositions.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('kasi.dispositions.index') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-inbox w-5 text-center"></i>
                <span class="text-sm font-medium">Kotak Masuk Disposisi</span>
            </a>

            <a href="{{ route('kasi.dalam-penanganan') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('kasi.dalam-penanganan') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-spinner w-5 text-center"></i>
                <span class="text-sm font-medium">Dalam Penanganan</span>
            </a>

            <a href="{{ route('kasi.riwayat') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('kasi.riwayat') || (request()->routeIs('kasi.dispositions.show') && str_contains(url()->previous(), 'riwayat')) ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i>
                <span class="text-sm font-medium">Riwayat Bidang</span>
            </a>

            <a href="{{ route('kasi.laporan') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('kasi.laporan') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-file-lines w-5 text-center"></i>
                <span class="text-sm font-medium">Laporan Bidang</span>
            </a>

            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Akun</p>
            </div>

            <a href="{{ route('kasi.profil') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('kasi.profil') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-user w-5 text-center"></i>
                <span class="text-sm font-medium">Profil</span>
            </a>

        {{-- ============================================================ --}}
        {{--  KABID / KABAG                                                --}}
        {{-- ============================================================ --}}
        @elseif($roleGroup === 'kabid')
            <div class="pb-1">
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Menu Kabid / Kabag</p>
            </div>

            <a href="{{ route('kabid.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('kabid.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-house w-5 text-center"></i>
                <span class="text-sm font-medium">Dashboard</span>
            </a>

            <a href="{{ route('kabid.dispositions.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('kabid.dispositions.index') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-inbox w-5 text-center"></i>
                <span class="text-sm font-medium">Kotak Masuk Disposisi</span>
            </a>

            <a href="{{ route('kabid.dalam-penanganan') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('kabid.dalam-penanganan') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-spinner w-5 text-center"></i>
                <span class="text-sm font-medium">Dalam Penanganan</span>
            </a>

            <a href="{{ route('kabid.riwayat') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('kabid.riwayat') || (request()->routeIs('kabid.dispositions.show') && str_contains(url()->previous(), 'riwayat')) ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i>
                <span class="text-sm font-medium">Riwayat Pengaduan</span>
            </a>

            <a href="{{ route('kabid.monitoring') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('kabid.monitoring') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-chart-line w-5 text-center"></i>
                <span class="text-sm font-medium">Monitoring Bidang</span>
            </a>

            <a href="{{ route('kabid.laporan') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('kabid.laporan') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-file-lines w-5 text-center"></i>
                <span class="text-sm font-medium">Laporan Bidang</span>
            </a>

            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Akun</p>
            </div>

            <a href="{{ route('kabid.profil') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('kabid.profil') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-user w-5 text-center"></i>
                <span class="text-sm font-medium">Profil</span>
            </a>

        {{-- ============================================================ --}}
        {{--  HEAD UNIT / KEPALA RUANGAN                                   --}}
        {{-- ============================================================ --}}
        @elseif($roleGroup === 'head_unit')
            <div class="pb-1">
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Menu Kepala Ruangan</p>
            </div>

            <a href="{{ route('head-unit.dispositions.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('head-unit.dispositions.index') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-inbox w-5 text-center"></i>
                <span class="text-sm font-medium">Kotak Masuk</span>
            </a>

            <a href="{{ route('head-unit.dalam-penanganan') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('head-unit.dalam-penanganan') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-spinner w-5 text-center"></i>
                <span class="text-sm font-medium">Dalam Penanganan</span>
            </a>

            <a href="{{ route('head-unit.riwayat') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('head-unit.riwayat') || (request()->routeIs('head-unit.dispositions.show') && str_contains(url()->previous(), 'riwayat')) ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i>
                <span class="text-sm font-medium">Riwayat Pengaduan</span>
            </a>

            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Akun</p>
            </div>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors">
                <i class="fa-solid fa-user w-5 text-center"></i>
                <span class="text-sm font-medium">Profil</span>
            </a>

        {{-- ============================================================ --}}
        {{--  DIREKTUR                                                     --}}
        {{-- ============================================================ --}}
        @elseif($roleGroup === 'direktur')
            <div class="pb-1">
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Menu Direktur</p>
            </div>

            <a href="{{ route('direktur.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('direktur.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                <span class="text-sm font-medium">Dashboard Monitoring</span>
            </a>

            <a href="{{ route('direktur.monitoring-workflow') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('direktur.monitoring-workflow') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-diagram-project w-5 text-center"></i>
                <span class="text-sm font-medium">Monitoring Workflow</span>
            </a>

            <a href="{{ route('direktur.statistik') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('direktur.statistik') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-chart-bar w-5 text-center"></i>
                <span class="text-sm font-medium">Statistik</span>
            </a>

            <a href="{{ route('direktur.laporan') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('direktur.laporan') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-file-lines w-5 text-center"></i>
                <span class="text-sm font-medium">Laporan</span>
            </a>

            <a href="{{ route('direktur.audit-trail') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('direktur.audit-trail') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-magnifying-glass w-5 text-center"></i>
                <span class="text-sm font-medium">Audit Trail</span>
                <span class="ml-auto text-[10px] text-slate-400 italic">baca</span>
            </a>

            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Akun</p>
            </div>

            <a href="{{ route('direktur.profil') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('direktur.profil') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-user w-5 text-center"></i>
                <span class="text-sm font-medium">Profil</span>
            </a>
        @endif

        {{-- Logout --}}
        <div class="pt-4 border-t border-slate-700 mt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-red-400 hover:bg-red-900/30 hover:text-red-300 rounded-lg transition-colors">
                    <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
                    <span class="text-sm font-medium">Keluar / Logout</span>
                </button>
            </form>
        </div>
    </nav>
</aside>

<div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>
