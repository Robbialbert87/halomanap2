@extends('layouts.admin')

@section('title', 'Dashboard - Halo MANAP')

@section('admin_content')
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

            <!-- Page Header & Filter -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4 animate-fadeInUp" style="animation-delay:0s">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                    <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                        <span class="text-blue-600">Beranda</span> 
                        <span class="text-gray-400">/</span> 
                        <span>Dashboard</span>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
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

            <!-- Stats Cards -->
            <div class="flex gap-3 overflow-x-auto snap-x snap-mandatory md:grid md:grid-cols-2 lg:grid-cols-5 md:gap-4 mb-6 pb-2 md:pb-0 scrollbar-hide">
                <!-- Card 1 -->
                <div class="snap-start shrink-0 w-[80vw] md:w-auto bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center gap-4 border-l-4 border-l-purple-500 active:scale-[0.98] transition-transform">
                    <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium mb-1">Total Pengaduan</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $total }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Semua waktu</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="snap-start shrink-0 w-[80vw] md:w-auto bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center gap-4 border-l-4 border-l-yellow-400 active:scale-[0.98] transition-transform">
                    <div class="w-12 h-12 rounded-full bg-yellow-50 text-yellow-500 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium mb-1">Menunggu</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $menunggu }}</h3>
                        <p class="text-xs text-gray-400 mt-1">{{ $pMenunggu }}%</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="snap-start shrink-0 w-[80vw] md:w-auto bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center gap-4 border-l-4 border-l-blue-500 active:scale-[0.98] transition-transform">
                    <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-spinner"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium mb-1">Diproses</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $diproses }}</h3>
                        <p class="text-xs text-gray-400 mt-1">{{ $pDiproses }}%</p>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="snap-start shrink-0 w-[80vw] md:w-auto bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center gap-4 border-l-4 border-l-green-500 active:scale-[0.98] transition-transform">
                    <div class="w-12 h-12 rounded-full bg-green-50 text-green-500 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium mb-1">Selesai</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $selesai }}</h3>
                        <p class="text-xs text-gray-400 mt-1">{{ $pSelesai }}%</p>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="snap-start shrink-0 w-[80vw] md:w-auto bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center gap-4 border-l-4 border-l-red-500 active:scale-[0.98] transition-transform">
                    <div class="w-12 h-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium mb-1">Ditolak</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $ditolak }}</h3>
                        <p class="text-xs text-gray-400 mt-1">{{ $pDitolak }}%</p>
                    </div>
                </div>
            </div>

            <!-- Charts & SLA Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 animate-fadeInUp" style="animation-delay:.15s">
                <!-- Grafik Bulanan -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4">Grafik Pengaduan Bulanan</h3>
                    @php $maxMonth = max($monthlyData->max(), 1); @endphp
                    <div class="h-64 w-full flex items-end gap-2 text-xs text-gray-400">
                        @foreach($monthlyData as $i => $val)
                        <div class="flex-1 flex flex-col items-center justify-end h-full gap-1">
                            <span class="font-bold text-gray-700 text-xs">{{ $val }}</span>
                            <div class="w-full bg-blue-500 rounded-t" style="height: {{ ($val / $maxMonth) * 100 }}%; min-height: {{ $val > 0 ? '4px' : '0' }};"></div>
                        </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between mt-2 text-xs text-gray-400">
                        @foreach($monthlyLabels as $label)
                        <span>{{ $label }}</span>
                        @endforeach
                    </div>
                </div>

                <!-- Pengaduan Kategori -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4">Pengaduan Berdasarkan Kategori</h3>
                    @php $totalCat = $categoryCounts->sum() ?: 1; @endphp
                    <div class="flex items-center justify-center h-64">
                         <div class="w-1/2 flex items-center justify-center relative">
                             <div class="w-32 h-32 rounded-full border-[16px] relative"
                                  style="border-color: {{ $categoryColors[0] ?? '#3b82f6' }};">
                             </div>
                             <div class="w-20 h-20 bg-white rounded-full absolute"></div>
                         </div>
                         <div class="w-1/2 flex flex-col gap-2 text-xs max-h-64 overflow-y-auto">
                             @forelse($categoryData as $i => $item)
                             <div class="flex justify-between items-center">
                                 <span class="flex items-center gap-2">
                                     <span class="w-3 h-3 rounded-full shrink-0" style="background: {{ $categoryColors[$i % count($categoryColors)] }}"></span>
                                     {{ $item->category->name ?? 'Tanpa Kategori' }}
                                 </span>
                                 <span class="font-bold">{{ $item->total }} ({{ round(($item->total / $totalCat) * 100) }}%)</span>
                             </div>
                             @empty
                             <p class="text-gray-400 italic">Belum ada data kategori.</p>
                             @endforelse
                         </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section (SLA and Unit) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 animate-fadeInUp" style="animation-delay:.25s">
                 <!-- Pengaduan Unit -->
                 <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4">Pengaduan Berdasarkan Unit</h3>
                    <div class="flex flex-col gap-4 max-h-64 overflow-y-auto">
                        @forelse($unitData as $unitName => $count)
                        <div class="flex items-center gap-3">
                            <div class="w-28 text-xs font-medium text-gray-600 truncate" title="{{ $unitName }}">{{ $unitName }}</div>
                            <div class="flex-1 bg-gray-100 h-2.5 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $loop->index % 2 == 0 ? 'bg-blue-600' : 'bg-teal-500' }}" style="width: {{ ($count / $unitMax) * 100 }}%;"></div>
                            </div>
                            <div class="w-10 text-xs font-bold text-right">{{ $count }}</div>
                        </div>
                        @empty
                        <p class="text-gray-400 italic text-center">Belum ada data unit.</p>
                        @endforelse
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
@endsection
