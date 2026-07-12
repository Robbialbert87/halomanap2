@extends('layouts.admin')

@section('title', 'Dashboard - Halo MANAP')

@section('admin_content')
@php
    $mobileRoleGroup = auth()->user() ? \App\Services\RoleMenuService::getRoleGroup(auth()->user()) : null;
@endphp
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeInUp {
        animation: fadeInUp 0.45s ease-out both;
    }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4 animate-fadeInUp" style="animation-delay:0s">
                <div>
                    <div class="hidden md:block">
                        <h1 class="text-2xl font-bold text-gray-800 font-heading">Dashboard</h1>
                        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                            <span class="text-blue-600">Beranda</span>
                            <span class="text-gray-400">/</span>
                            <span>Dashboard</span>
                        </div>
                    </div>
                    <div class="md:hidden">
                        <p class="text-[10px] text-blue-500 font-semibold tracking-wider uppercase">Dashboard</p>
                        <h1 class="text-lg font-bold text-gray-800 font-heading">Selamat Datang, {{ auth()->user()?->nama ?? 'Admin' }}</h1>
                    </div>
                </div>
                <div class="hidden md:flex items-center gap-3">
                    <span class="text-sm text-gray-500 font-medium">Filter Tanggal</span>
                    <div class="relative">
                        <select class="appearance-none bg-white border border-gray-200 text-gray-700 py-2 pl-4 pr-10 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm cursor-pointer">
                            <option>01 - 30 Juni 2026</option>
                            <option>01 - 31 Mei 2026</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-3 text-gray-400 text-xs"></i>
                    </div>
                </div>
            </div>

            <!-- Stats Cards (gradient icons ala PayApp) -->
            <div class="flex gap-3 overflow-x-auto snap-x snap-mandatory md:grid md:grid-cols-2 lg:grid-cols-5 md:gap-4 mb-6 pb-2 md:pb-0 scrollbar-hide">
                <div class="snap-start shrink-0 w-[72vw] md:w-auto bg-white rounded-2xl p-4 md:p-5 shadow-sm border border-gray-100 flex items-center gap-3 active:scale-[0.98] transition-transform">
                    <span class="w-11 h-11 rounded-xl bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center shadow-md shadow-violet-200/50 flex-shrink-0">
                        <i class="fa-solid fa-file-lines text-white text-lg"></i>
                    </span>
                    <div>
                        <p class="text-[11px] text-gray-400 font-medium mb-0.5">Total Pengaduan</p>
                        <h3 class="text-xl font-bold text-gray-800">{{ $total }}</h3>
                        <p class="text-[10px] text-gray-400 mt-0.5">Semua waktu</p>
                    </div>
                </div>
                <div class="snap-start shrink-0 w-[72vw] md:w-auto bg-white rounded-2xl p-4 md:p-5 shadow-sm border border-gray-100 flex items-center gap-3 active:scale-[0.98] transition-transform">
                    <span class="w-11 h-11 rounded-xl bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center shadow-md shadow-yellow-200/50 flex-shrink-0">
                        <i class="fa-solid fa-clock text-white text-lg"></i>
                    </span>
                    <div>
                        <p class="text-[11px] text-gray-400 font-medium mb-0.5">Menunggu</p>
                        <h3 class="text-xl font-bold text-gray-800">{{ $menunggu }}</h3>
                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $pMenunggu }}%</p>
                    </div>
                </div>
                <div class="snap-start shrink-0 w-[72vw] md:w-auto bg-white rounded-2xl p-4 md:p-5 shadow-sm border border-gray-100 flex items-center gap-3 active:scale-[0.98] transition-transform">
                    <span class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md shadow-blue-200/50 flex-shrink-0">
                        <i class="fa-solid fa-spinner text-white text-lg"></i>
                    </span>
                    <div>
                        <p class="text-[11px] text-gray-400 font-medium mb-0.5">Diproses</p>
                        <h3 class="text-xl font-bold text-gray-800">{{ $diproses }}</h3>
                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $pDiproses }}%</p>
                    </div>
                </div>
                <div class="snap-start shrink-0 w-[72vw] md:w-auto bg-white rounded-2xl p-4 md:p-5 shadow-sm border border-gray-100 flex items-center gap-3 active:scale-[0.98] transition-transform">
                    <span class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-md shadow-emerald-200/50 flex-shrink-0">
                        <i class="fa-solid fa-check text-white text-lg"></i>
                    </span>
                    <div>
                        <p class="text-[11px] text-gray-400 font-medium mb-0.5">Selesai</p>
                        <h3 class="text-xl font-bold text-gray-800">{{ $selesai }}</h3>
                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $pSelesai }}%</p>
                    </div>
                </div>
                <div class="snap-start shrink-0 w-[72vw] md:w-auto bg-white rounded-2xl p-4 md:p-5 shadow-sm border border-gray-100 flex items-center gap-3 active:scale-[0.98] transition-transform">
                    <span class="w-11 h-11 rounded-xl bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center shadow-md shadow-red-200/50 flex-shrink-0">
                        <i class="fa-solid fa-xmark text-white text-lg"></i>
                    </span>
                    <div>
                        <p class="text-[11px] text-gray-400 font-medium mb-0.5">Ditolak</p>
                        <h3 class="text-xl font-bold text-gray-800">{{ $ditolak }}</h3>
                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $pDitolak }}%</p>
                    </div>
                </div>
            </div>

            {{-- Quick Actions (mobile only, ala PayApp waves) --}}
            <div class="md:hidden mb-5">
                <div class="flex text-center gap-0">
                    <a href="{{ route('pengaduan.create') }}" class="flex-1 flex flex-col items-center gap-1.5">
                        <span class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md shadow-blue-200/50">
                            <i class="fa-solid fa-plus text-white text-xl"></i>
                        </span>
                        <span class="text-[10px] font-medium text-gray-500">Pengaduan<br>Baru</span>
                    </a>
                    <a href="{{ route('admin.tickets.index') }}" class="flex-1 flex flex-col items-center gap-1.5">
                        <span class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-md shadow-emerald-200/50">
                            <i class="fa-solid fa-inbox text-white text-xl"></i>
                        </span>
                        <span class="text-[10px] font-medium text-gray-500">Semua<br>Pengaduan</span>
                    </a>
                    <a href="{{ route('pengaduan.track') }}" class="flex-1 flex flex-col items-center gap-1.5">
                        <span class="w-12 h-12 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-md shadow-orange-200/50">
                            <i class="fa-solid fa-magnifying-glass text-white text-xl"></i>
                        </span>
                        <span class="text-[10px] font-medium text-gray-500">Cek<br>Status</span>
                    </a>
                    @if($mobileRoleGroup === 'admin' && (auth()->user()?->can('manage-reports') || auth()->user()?->hasRole('Admin Pengaduan')))
                    <a href="{{ route('admin.monitoring.index') }}" class="flex-1 flex flex-col items-center gap-1.5">
                        <span class="w-12 h-12 rounded-2xl bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center shadow-md shadow-violet-200/50">
                            <i class="fa-solid fa-chart-line text-white text-xl"></i>
                        </span>
                        <span class="text-[10px] font-medium text-gray-500">Monitoring</span>
                    </a>
                    @endif
                </div>
            </div>

            <!-- Charts & SLA Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 animate-fadeInUp" style="animation-delay:.15s">
                <!-- Grafik Bulanan -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4">Grafik Pengaduan Bulanan</h3>
                    <div class="relative h-64">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>

                <!-- Pengaduan Kategori -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4">Pengaduan Berdasarkan Kategori</h3>
                    <div class="flex items-center justify-center h-64">
                        <div class="w-1/2 flex items-center justify-center relative">
                            <canvas id="categoryChart"></canvas>
                        </div>
                        <div class="w-1/2 flex flex-col gap-2 text-xs max-h-64 overflow-y-auto" id="categoryLegend"></div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section (SLA and Unit) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 animate-fadeInUp" style="animation-delay:.25s">
                 <!-- Pengaduan Unit -->
                 <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4">Pengaduan Berdasarkan Unit</h3>
                    <div class="relative h-64">
                        <canvas id="unitChart"></canvas>
                    </div>
                </div>

                <!-- SLA Section -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4">SLA (Service Level Agreement)</h3>
                    <div class="flex gap-3 overflow-x-auto snap-x snap-mandatory md:grid md:grid-cols-3 md:gap-4 h-[200px] pb-2 md:pb-0 scrollbar-hide">
                        <div class="snap-start shrink-0 w-[200px] md:w-auto border border-green-200 bg-green-50/30 rounded-lg p-4 flex flex-col justify-center items-center text-center active:scale-[0.98] transition-transform">
                            <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center mb-3">
                                <i class="fa-solid fa-check text-lg"></i>
                            </div>
                            <p class="text-xs text-gray-500 font-medium mb-1">Sesuai SLA</p>
                            <h2 class="text-3xl font-bold text-gray-800">298</h2>
                            <p class="text-xs text-green-600 font-bold mt-1">70.0%</p>
                        </div>
                        
                        <div class="snap-start shrink-0 w-[200px] md:w-auto border border-yellow-200 bg-yellow-50/30 rounded-lg p-4 flex flex-col justify-center items-center text-center active:scale-[0.98] transition-transform">
                            <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mb-3">
                                <i class="fa-solid fa-clock text-lg"></i>
                            </div>
                            <p class="text-xs text-gray-500 font-medium mb-1">Mendekati SLA</p>
                            <h2 class="text-3xl font-bold text-gray-800">74</h2>
                            <p class="text-xs text-yellow-600 font-bold mt-1">17.4%</p>
                        </div>

                        <div class="snap-start shrink-0 w-[200px] md:w-auto border border-red-200 bg-red-50/30 rounded-lg p-4 flex flex-col justify-center items-center text-center active:scale-[0.98] transition-transform">
                            <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center mb-3">
                                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                            </div>
                            <p class="text-xs text-gray-500 font-medium mb-1">Melebihi SLA</p>
                            <h2 class="text-3xl font-bold text-gray-800">54</h2>
                            <p class="text-xs text-red-600 font-bold mt-1">12.6%</p>
                        </div>
                    </div>
                </div>
            </div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colors = @json($categoryColors);
    const catData  = @json($categoryData);
    const catLabels = catData.map(d => d.category?.name ?? 'Tanpa Kategori');
    const catCounts = catData.map(d => d.total);

    // ── Monthly Bar Chart ──
    const mCtx = document.getElementById('monthlyChart')?.getContext('2d');
    if (mCtx) {
        const gradient = mCtx.createLinearGradient(0, 0, 0, 250);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.85)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.25)');
        new Chart(mCtx, {
            type: 'bar',
            data: {
                labels: @json($monthlyLabels),
                datasets: [{
                    data: @json($monthlyData),
                    backgroundColor: gradient,
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1.5,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, color: '#9ca3af' }, grid: { color: 'rgba(0,0,0,0.04)' } },
                    x: { ticks: { color: '#9ca3af' }, grid: { display: false } }
                }
            }
        });
    }

    // ── Category Donut Chart ──
    const cCtx = document.getElementById('categoryChart')?.getContext('2d');
    if (cCtx) {
        new Chart(cCtx, {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catCounts,
                    backgroundColor: colors.slice(0, catLabels.length),
                    borderWidth: 3,
                    borderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false }
                }
            }
        });

        const legendEl = document.getElementById('categoryLegend');
        if (legendEl) {
            const total = catCounts.reduce((a, b) => a + b, 0) || 1;
            catLabels.forEach((label, i) => {
                const pct = ((catCounts[i] / total) * 100).toFixed(0);
                legendEl.innerHTML += '<div class="flex justify-between items-center py-0.5">' +
                    '<span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:' + colors[i % colors.length] + '"></span>' + label + '</span>' +
                    '<span class="font-bold text-gray-700 text-xs">' + catCounts[i] + ' (' + pct + '%)</span></div>';
            });
        }
    }

    // ── Unit Polar Area Chart ──
    const uCtx = document.getElementById('unitChart')?.getContext('2d');
    if (uCtx) {
        const unitData = @json($unitData);
        const unitLabels = Object.keys(unitData);
        const unitCounts = Object.values(unitData);

        new Chart(uCtx, {
            type: 'polarArea',
            data: {
                labels: unitLabels,
                datasets: [{
                    data: unitCounts,
                    backgroundColor: colors.slice(0, unitLabels.length).map(c => c.replace(')', ', 0.7)').replace('rgb', 'rgba')),
                    borderColor: colors.slice(0, unitLabels.length),
                    borderWidth: 1.5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { font: { size: 10 }, color: '#6b7280', boxWidth: 12, padding: 8 }
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        ticks: { display: false },
                        grid: { color: 'rgba(0,0,0,0.04)' }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
