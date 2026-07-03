@extends('layouts.admin')

@section('title', 'Dashboard - Halo MANAP')

@section('admin_content')
            <!-- Page Header & Filter -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                <!-- Card 1 -->
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center gap-4 border-l-4 border-l-purple-500">
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
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center gap-4 border-l-4 border-l-yellow-400">
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
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center gap-4 border-l-4 border-l-blue-500">
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
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center gap-4 border-l-4 border-l-green-500">
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
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center gap-4 border-l-4 border-l-red-500">
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
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Grafik Bulanan -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4">Grafik Pengaduan Bulanan</h3>
                    <div class="h-64 w-full flex items-end gap-2 text-xs text-gray-400 relative">
                        <!-- Placeholder for Chart -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <p class="text-gray-300 italic">Area Grafik Line Chart</p>
                        </div>
                        <!-- Mock Axis -->
                        <div class="flex flex-col justify-between h-full py-4 border-r border-gray-200 pr-2">
                            <span>150</span><span>120</span><span>90</span><span>60</span><span>30</span><span>0</span>
                        </div>
                        <div class="flex-1 h-full relative border-b border-gray-200">
                            <!-- Mock Data Points -->
                        </div>
                    </div>
                    <div class="flex justify-between px-8 mt-2 text-xs text-gray-400">
                        <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>Mei</span><span>Jun</span>
                    </div>
                </div>

                <!-- Pengaduan Kategori -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4">Pengaduan Berdasarkan Kategori</h3>
                    <div class="flex items-center justify-center h-64">
                         <!-- Placeholder Pie Chart Area -->
                         <div class="w-1/2 flex items-center justify-center relative">
                             <div class="w-32 h-32 rounded-full border-[16px] border-blue-500 border-r-green-500 border-b-yellow-400 border-l-red-400 relative"></div>
                             <div class="w-20 h-20 bg-white rounded-full absolute"></div>
                         </div>
                         <div class="w-1/2 flex flex-col gap-3 text-xs">
                             <div class="flex justify-between items-center"><span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-500"></span> Pelayanan Dokter</span> <span class="font-bold">126 (29.6%)</span></div>
                             <div class="flex justify-between items-center"><span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-green-500"></span> Pelayanan Perawat</span> <span class="font-bold">98 (23.0%)</span></div>
                             <div class="flex justify-between items-center"><span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-yellow-400"></span> Administrasi</span> <span class="font-bold">72 (16.9%)</span></div>
                             <div class="flex justify-between items-center"><span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-orange-400"></span> Fasilitas</span> <span class="font-bold">61 (14.3%)</span></div>
                             <div class="flex justify-between items-center"><span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-red-400"></span> Kebersihan</span> <span class="font-bold">34 (8.0%)</span></div>
                             <div class="flex justify-between items-center"><span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-purple-400"></span> Lainnya</span> <span class="font-bold">35 (8.2%)</span></div>
                         </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section (SLA and Unit) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                 <!-- Pengaduan Unit -->
                 <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4">Pengaduan Berdasarkan Unit</h3>
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-24 text-xs font-medium text-gray-600">IGD</div>
                            <div class="flex-1 bg-gray-100 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-blue-600 h-full rounded-full" style="width: 89%;"></div>
                            </div>
                            <div class="w-8 text-xs font-bold text-right">89</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-24 text-xs font-medium text-gray-600">Rawat Jalan</div>
                            <div class="flex-1 bg-gray-100 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-teal-500 h-full rounded-full" style="width: 78%;"></div>
                            </div>
                            <div class="w-8 text-xs font-bold text-right">78</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-24 text-xs font-medium text-gray-600">Radiologi</div>
                            <div class="flex-1 bg-gray-100 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-green-500 h-full rounded-full" style="width: 64%;"></div>
                            </div>
                            <div class="w-8 text-xs font-bold text-right">64</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-24 text-xs font-medium text-gray-600">Rawat Inap</div>
                            <div class="flex-1 bg-gray-100 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-orange-500 h-full rounded-full" style="width: 58%;"></div>
                            </div>
                            <div class="w-8 text-xs font-bold text-right">58</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-24 text-xs font-medium text-gray-600">Laboratorium</div>
                            <div class="flex-1 bg-gray-100 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-purple-500 h-full rounded-full" style="width: 45%;"></div>
                            </div>
                            <div class="w-8 text-xs font-bold text-right">45</div>
                        </div>
                    </div>
                </div>

                <!-- SLA Section -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4">SLA (Service Level Agreement)</h3>
                    <div class="grid grid-cols-3 gap-4 h-[200px]">
                        <div class="border border-green-200 bg-green-50/30 rounded-lg p-4 flex flex-col justify-center items-center text-center">
                            <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center mb-3">
                                <i class="fa-solid fa-check text-lg"></i>
                            </div>
                            <p class="text-xs text-gray-500 font-medium mb-1">Sesuai SLA</p>
                            <h2 class="text-3xl font-bold text-gray-800">298</h2>
                            <p class="text-xs text-green-600 font-bold mt-1">70.0%</p>
                        </div>
                        
                        <div class="border border-yellow-200 bg-yellow-50/30 rounded-lg p-4 flex flex-col justify-center items-center text-center">
                            <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mb-3">
                                <i class="fa-solid fa-clock text-lg"></i>
                            </div>
                            <p class="text-xs text-gray-500 font-medium mb-1">Mendekati SLA</p>
                            <h2 class="text-3xl font-bold text-gray-800">74</h2>
                            <p class="text-xs text-yellow-600 font-bold mt-1">17.4%</p>
                        </div>

                        <div class="border border-red-200 bg-red-50/30 rounded-lg p-4 flex flex-col justify-center items-center text-center">
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
