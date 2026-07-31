@extends('layouts.admin')

@section('title', 'Dashboard - Halo MANAP')

@section('admin_content')
@php
    $mobileRoleGroup = auth()->user()?->getRoleGroup();
    $statusMeta = [
        'menunggu'  => ['label' => 'Menunggu',  'dot' => 'bg-amber-500',   'bar' => 'bg-amber-500',  'icon' => 'fa-regular fa-clock'],
        'diproses'  => ['label' => 'Diproses',  'dot' => 'bg-blue-600',     'bar' => 'bg-blue-600',   'icon' => 'fa-solid fa-spinner'],
        'selesai'   => ['label' => 'Selesai',   'dot' => 'bg-emerald-500',  'bar' => 'bg-emerald-500', 'icon' => 'fa-solid fa-check'],
        'ditolak'   => ['label' => 'Ditolak',   'dot' => 'bg-rose-500',     'bar' => 'bg-rose-500',   'icon' => 'fa-solid fa-ban'],
    ];
    $kpis = [
        'menunggu' => ['value' => $menunggu, 'pct' => $pMenunggu],
        'diproses' => ['value' => $diproses, 'pct' => $pDiproses],
        'selesai'  => ['value' => $selesai,  'pct' => $pSelesai],
        'ditolak'  => ['value' => $ditolak,  'pct' => $pDitolak],
    ];
    $wfBadge = [
        'dalam_penanganan' => ['text' => 'text-blue-700',  'bg' => 'bg-blue-50',   'label' => 'Dalam Penanganan'],
        'disposisi'        => ['text' => 'text-violet-700', 'bg' => 'bg-violet-50', 'label' => 'Disposisi'],
        'eskalasi'         => ['text' => 'text-amber-700', 'bg' => 'bg-amber-50',  'label' => 'Eskalasi'],
        'selesai'          => ['text' => 'text-emerald-700', 'bg' => 'bg-emerald-50', 'label' => 'Selesai'],
        'ditutup'          => ['text' => 'text-slate-600', 'bg' => 'bg-slate-100', 'label' => 'Ditutup'],
        'diterima'         => ['text' => 'text-cyan-700',  'bg' => 'bg-cyan-50',   'label' => 'Diterima'],
    ];
@endphp

<style>
    @keyframes rise {
        from { opacity: 0; transform: translateY(22px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes breathe {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%      { opacity: 0.55; transform: scale(0.82); }
    }
    @keyframes shimmer {
        0%   { background-position: -468px 0; }
        100% { background-position: 468px 0; }
    }
    @keyframes growBar {
        from { transform: scaleX(0); }
        to   { transform: scaleX(1); }
    }
    .anim-rise      { animation: rise 0.55s cubic-bezier(0.16, 1, 0.3, 1) both; }
    .dot-breathe    { animation: breathe 2.2s ease-in-out infinite; }
    .skeleton-shimmer {
        background: linear-gradient(90deg, #f1f5f9 8%, #e2e8f0 18%, #f1f5f9 33%);
        background-size: 936px 100%;
        animation: shimmer 1.4s linear infinite;
    }
    .bar-grow {
        transform-origin: left;
        animation: growBar 0.9s cubic-bezier(0.16, 1, 0.3, 1) both;
    }
    .card-surface {
        background-color: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.6);
        border-radius: 2rem;
        box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.06);
    }
    .card-surface:active { transform: scale(0.995); }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div x-data="dashboardCounter()" x-init="init()" class="relative max-w-[1400px] mx-auto">

    {{-- =========================================================== --}}
    {{-- HEADER — asymmetric: eyebrow + nama kiri, aksi kanan         --}}
    {{-- =========================================================== --}}
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-5 mb-8 anim-rise" style="animation-delay:0s">
        <div>
            <p class="text-[11px] font-semibold tracking-[0.22em] uppercase text-blue-600 mb-2">Ringkasan Pelayanan</p>
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900 leading-none">
                Selamat datang, {{ auth()->user()?->nama ?? 'Pegawai' }}
            </h1>
            <p class="mt-2 text-sm text-slate-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} — data real-time unit Anda</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('pengaduan.track') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border border-slate-200 bg-white text-sm font-medium text-slate-600 hover:border-slate-300 hover:text-slate-900 active:scale-[0.98] transition-all duration-300">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                Cek Status
            </a>
            <a href="{{ route('pengaduan.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-blue-600 text-sm font-semibold text-white shadow-[0_10px_25px_-10px_rgba(37,99,235,0.55)] hover:bg-blue-700 active:scale-[0.98] transition-all duration-300">
                <i class="fa-solid fa-plus text-xs"></i>
                Buat Pengaduan
            </a>
        </div>
    </div>

    {{-- =========================================================== --}}
    {{-- BENTO ROW 1 — KPI hero (7) + Aktivitas Terkini (5)           --}}
    {{-- =========================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 mb-5">

        {{-- KPI HERO — angka besar + breakdown inline, tanpa 5 kartu --}}
        <div class="lg:col-span-7 card-surface p-6 md:p-8 anim-rise" style="animation-delay:0.06s">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Pengaduan</p>
                    <p class="mt-2 text-5xl md:text-6xl font-bold tracking-tighter text-slate-900 font-mono tabular-nums leading-none">
                        <span x-text="fmt(total)">0</span>
                    </p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-[11px] font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600 dot-breathe"></span>
                    Live
                </span>
            </div>

            <div class="mt-7 border-t border-slate-100 pt-5 grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-5">
                @foreach ($kpis as $key => $kpi)
                @php $m = $statusMeta[$key]; @endphp
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full {{ $m['dot'] }}"></span>
                        <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wider">{{ $m['label'] }}</p>
                    </div>
                    <p class="mt-1.5 text-2xl font-bold text-slate-800 font-mono tabular-nums">
                        <span x-text="fmt({{ $kpi['value'] }})">{{ $kpi['value'] }}</span>
                    </p>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $kpi['pct'] }}% dari total</p>
                    <div class="mt-2 h-1 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full {{ $m['bar'] }} bar-grow" style="width: {{ $kpi['pct'] }}%; animation-delay: {{ 0.25 + $loop->index * 0.1 }}s"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- AKTIVITAS TERKINI — live list dengan breathing indicator --}}
        <div class="lg:col-span-5 card-surface p-6 md:p-8 flex flex-col anim-rise" style="animation-delay:0.12s">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <p class="text-sm font-semibold text-slate-800">Aktivitas Terkini</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Disposisi masuk ke Anda</p>
                </div>
                <span class="flex items-center gap-1.5 text-[11px] font-semibold text-emerald-600">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dot-breathe"></span>
                    Mengalir
                </span>
            </div>

            @if ($latestWorkflows->isEmpty())
            <div class="flex-1 flex flex-col items-center justify-center text-center py-10">
                <span class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mb-4">
                    <i class="fa-regular fa-inbox text-xl"></i>
                </span>
                <p class="text-sm font-semibold text-slate-700">Belum ada aktivitas</p>
                <p class="text-xs text-slate-400 mt-1 max-w-[26ch] leading-relaxed">Disposisi pengaduan yang masuk ke Anda akan muncul di sini.</p>
            </div>
            @else
            <ul class="flex-1 divide-y divide-slate-100">
                @foreach ($latestWorkflows as $wf)
                @php
                    $badge = $wfBadge[$wf->status] ?? ['text' => 'text-slate-600', 'bg' => 'bg-slate-100', 'label' => ucfirst($wf->status)];
                    $initial = strtoupper(substr($wf->fromUser?->nama ?? '?', 0, 1));
                @endphp
                <li class="flex items-center gap-3.5 py-3 first:pt-0 last:pb-0 anim-rise" style="animation-delay: {{ 0.2 + $loop->index * 0.07 }}s">
                    <span class="w-9 h-9 rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 text-slate-500 flex items-center justify-center text-xs font-bold font-mono flex-shrink-0">{{ $initial }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-medium text-slate-700 truncate">
                            {{ $wf->ticket?->ticket_number ?? '#' . ($wf->ticket_id ?? '—') }}
                            <span class="text-slate-400 font-normal">·</span>
                            <span class="text-slate-500 font-normal truncate">{{ $wf->ticket?->category?->name ?? 'Pengaduan' }}</span>
                        </p>
                        <p class="text-[11px] text-slate-400 mt-0.5 truncate">{{ $wf->fromUser?->nama ?? 'Sistem' }} → {{ $wf->toJabatan?->nama ?? 'Anda' }}</p>
                    </div>
                    <div class="flex flex-col items-end gap-1 flex-shrink-0">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $badge['bg'] }} {{ $badge['text'] }}">{{ $badge['label'] }}</span>
                        <span class="text-[10px] text-slate-400">{{ $wf->created_at?->diffForHumans() }}</span>
                    </div>
                </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>

    {{-- =========================================================== --}}
    {{-- BENTO ROW 2 — Grafik bulanan (7) + Kategori (5)               --}}
    {{-- =========================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 mb-5">

        <div class="lg:col-span-7 card-surface p-6 md:p-8 anim-rise" style="animation-delay:0.18s">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <p class="text-sm font-semibold text-slate-800">Tren Pengaduan</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">12 bulan terakhir</p>
                </div>
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-chart-column text-sm"></i>
                </span>
            </div>
            <div class="relative h-64">
                <div id="skeleton-monthly" class="absolute inset-0 rounded-2xl skeleton-shimmer"></div>
                <canvas id="monthlyChart" class="relative"></canvas>
            </div>
        </div>

        <div class="lg:col-span-5 card-surface p-6 md:p-8 anim-rise" style="animation-delay:0.24s">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <p class="text-sm font-semibold text-slate-800">Berdasarkan Kategori</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Sebaran pengaduan</p>
                </div>
                <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center">
                    <i class="fa-solid fa-layer-group text-sm"></i>
                </span>
            </div>
            <div class="relative h-64">
                <div id="skeleton-category" class="absolute inset-0 rounded-2xl skeleton-shimmer"></div>
                <div class="relative h-full flex items-center justify-center">
                    <div class="w-1/2 h-full flex items-center justify-center relative">
                        <canvas id="categoryChart"></canvas>
                    </div>
                    <div class="w-1/2 flex flex-col gap-2 text-xs max-h-64 overflow-y-auto scrollbar-hide" id="categoryLegend"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================== --}}
    {{-- BENTO ROW 3 — Per Unit (5) + Kinerja (7)                     --}}
    {{-- =========================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        <div class="lg:col-span-5 card-surface p-6 md:p-8 anim-rise" style="animation-delay:0.3s">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <p class="text-sm font-semibold text-slate-800">Per Unit</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Distribusi pengaduan</p>
                </div>
                <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center">
                    <i class="fa-solid fa-building text-sm"></i>
                </span>
            </div>
            <div class="relative h-64">
                <div id="skeleton-unit" class="absolute inset-0 rounded-2xl skeleton-shimmer"></div>
                <canvas id="unitChart" class="relative"></canvas>
            </div>
        </div>

        <div class="lg:col-span-7 card-surface p-6 md:p-8 anim-rise" style="animation-delay:0.36s">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-sm font-semibold text-slate-800">Kinerja Pelayanan</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Metrik dari alur kerja Anda</p>
                </div>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-gauge-high text-sm"></i>
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-5">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 dot-breathe"></span>
                        <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wider">Rata-rata Respon</p>
                    </div>
                    <p class="mt-2.5 text-3xl font-bold text-slate-800 font-mono tabular-nums">{{ $avgRespon }}</p>
                    <p class="text-[11px] text-slate-400 mt-1">dari tiket selesai</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-5">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-600 dot-breathe" style="animation-delay:0.4s"></span>
                        <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wider">Dalam Proses</p>
                    </div>
                    <p class="mt-2.5 text-3xl font-bold text-slate-800 font-mono tabular-nums">{{ $dalamProses }}</p>
                    <p class="text-[11px] text-slate-400 mt-1">tugas aktif Anda</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-5">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500 dot-breathe" style="animation-delay:0.8s"></span>
                        <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wider">Belum Dilihat</p>
                    </div>
                    <p class="mt-2.5 text-3xl font-bold text-slate-800 font-mono tabular-nums">{{ $baru }}</p>
                    <p class="text-[11px] text-slate-400 mt-1">notifikasi baru</p>
                </div>
            </div>

            <div class="mt-6 border-t border-slate-100 pt-5">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-medium text-slate-500">Tingkat penyelesaian</p>
                    <p class="text-xs font-bold text-emerald-600 font-mono tabular-nums">{{ $pSelesai }}%</p>
                </div>
                <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-emerald-400 bar-grow" style="width: {{ $pSelesai }}%; animation-delay: 0.5s"></div>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span> Menunggu <span class="ml-auto font-semibold text-slate-700 font-mono tabular-nums">{{ $pMenunggu }}%</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <span class="w-2 h-2 rounded-full bg-blue-600"></span> Diproses <span class="ml-auto font-semibold text-slate-700 font-mono tabular-nums">{{ $pDiproses }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function dashboardCounter() {
    return {
        total: 0,
        init() {
            const target = @json($total);
            const dur = 900;
            const t0 = performance.now();
            const step = (ts) => {
                const p = Math.min(1, (ts - t0) / dur);
                this.total = Math.round(target * (1 - Math.pow(1 - p, 3)));
                if (p < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        },
        fmt(n) {
            return new Intl.NumberFormat('id-ID').format(n);
        }
    };
}
document.addEventListener('DOMContentLoaded', function () {
    const hideSkeleton = (id) => {
        const el = document.getElementById(id);
        if (el) el.style.display = 'none';
    };

    const colors = @json($categoryColors);
    const catData  = @json($categoryData);
    const catLabels = catData.map(d => d.category?.name ?? 'Tanpa Kategori');
    const catCounts = catData.map(d => d.total);

    // ── Monthly Bar Chart ──
    const mCtx = document.getElementById('monthlyChart')?.getContext('2d');
    if (mCtx) {
        const gradient = mCtx.createLinearGradient(0, 0, 0, 250);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.85)');
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0.18)');
        new Chart(mCtx, {
            type: 'bar',
            data: {
                labels: @json($monthlyLabels),
                datasets: [{
                    data: @json($monthlyData),
                    backgroundColor: gradient,
                    borderColor: 'rgba(37, 99, 235, 1)',
                    borderWidth: 1.5,
                    borderRadius: 8,
                    borderSkipped: false,
                    maxBarThickness: 28,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 800, easing: 'easeOutQuart' },
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, color: '#94a3b8', font: { family: 'Geist Mono' } }, grid: { color: 'rgba(15,23,42,0.05)' } },
                    x: { ticks: { color: '#94a3b8', maxRotation: 0, autoSkip: true }, grid: { display: false } }
                }
            }
        });
        hideSkeleton('skeleton-monthly');
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
                cutout: '72%',
                animation: { duration: 800, easing: 'easeOutQuart' },
                plugins: { legend: { display: false } }
            }
        });

        const legendEl = document.getElementById('categoryLegend');
        if (legendEl) {
            const total = catCounts.reduce((a, b) => a + b, 0) || 1;
            catLabels.forEach((label, i) => {
                const pct = ((catCounts[i] / total) * 100).toFixed(0);
                legendEl.innerHTML += '<div class="flex justify-between items-center py-0.5">' +
                    '<span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:' + colors[i % colors.length] + '"></span>' + label + '</span>' +
                    '<span class="font-semibold text-slate-600 text-xs font-mono tabular-nums">' + catCounts[i] + ' · ' + pct + '%</span></div>';
            });
        }
        hideSkeleton('skeleton-category');
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
                animation: { duration: 800, easing: 'easeOutQuart' },
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { font: { size: 10, family: 'Geist' }, color: '#64748b', boxWidth: 10, padding: 8 }
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        ticks: { display: false },
                        grid: { color: 'rgba(15,23,42,0.05)' }
                    }
                }
            }
        });
        hideSkeleton('skeleton-unit');
    }
});
</script>
@endpush
@endsection
