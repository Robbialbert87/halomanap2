@extends('layouts.app')

@section('title', 'Halo MANAP - Pusat Pengaduan, Aspirasi dan Informasi Pelayanan')

@section('content')
<div class="bg-gray-50 min-h-screen">

    <!-- ========================================================================= -->
    <!--                           DESKTOP LAYOUT                                  -->
    <!-- ========================================================================= -->
    <div class="hidden md:block">
        <!-- TOP NAVBAR -->
        <header class="bg-white/90 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-sm">
            <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-hospital text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="font-bold text-lg text-blue-800 leading-tight">Halo <span class="text-green-600">MANAP</span></p>
                        <p class="text-[9px] text-gray-500 leading-none">RSUD H. Abdul Manap Kota Jambi</p>
                    </div>
                </div>
                <nav class="flex items-center gap-1">
                    <a href="/" class="px-4 py-2 text-sm font-semibold text-blue-600 bg-blue-50 rounded-lg">Beranda</a>
                    <a href="/pengaduan/buat?type=Pengaduan" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors">Pengaduan</a>
                    <a href="/pengaduan/buat?type=Saran" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors">Saran</a>
                    <a href="/pengaduan/buat?type=Apresiasi" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors">Apresiasi</a>
                    <a href="/pengaduan/buat?type=Informasi" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors">Informasi</a>
                </nav>
                <div class="flex items-center gap-3">
                    <a href="/pengaduan/buat" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors shadow-sm shadow-blue-200">
                        <i class="fa-solid fa-plus mr-1.5"></i> Buat Laporan
                    </a>
                    <a href="/dashboard" class="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">
                        <i class="fa-solid fa-shield-halved mr-1"></i> Admin
                    </a>
                </div>
            </div>
        </header>

        <!-- HERO SECTION -->
        <section class="bg-white">
            <div class="max-w-7xl mx-auto px-6 py-20 flex items-center gap-16">
                <div class="flex-1">
                    <span class="inline-block bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-full mb-4 uppercase tracking-widest">RSUD H. Abdul Manap Kota Jambi</span>
                    <h1 class="text-5xl lg:text-6xl font-black text-gray-900 leading-tight mb-5">
                        Selamat datang di<br><span class="text-blue-700">Halo</span> <span class="text-green-600">MANAP</span>
                    </h1>
                    <p class="text-gray-500 text-lg leading-relaxed mb-3">
                        Pusat Pengaduan, Aspirasi dan Informasi Pelayanan RSUD H. Abdul Manap Kota Jambi.
                    </p>
                    <p class="text-blue-600 font-semibold italic mb-8">"Salurkan Aspirasi Anda dengan Mudah &amp; Transparan"</p>
                    <div class="flex items-center gap-4">
                        <a href="/pengaduan/buat" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-4 rounded-2xl transition-all shadow-lg shadow-blue-200 hover:shadow-blue-300 hover:-translate-y-0.5">
                            <i class="fa-solid fa-circle-exclamation mr-2"></i> Buat Laporan Sekarang
                        </a>
                        <a href="{{ route('pengaduan.track') }}" class="bg-white border-2 border-gray-200 hover:border-blue-300 text-gray-700 font-bold px-8 py-4 rounded-2xl transition-all hover:-translate-y-0.5">
                            <i class="fa-solid fa-magnifying-glass mr-2 text-blue-500"></i> Lacak Status
                        </a>
                    </div>
                </div>
                <div class="w-1/2">
                    <div class="rounded-2xl overflow-hidden shadow-xl shadow-blue-900/10 border border-gray-100">
                        <img src="{{ asset('assets/images/banner-halo-manap.jpg') }}" alt="Banner" class="w-full h-auto object-cover" onerror="this.src='https://placehold.co/800x400/eff6ff/1d4ed8?text=Halo+MANAP'">
                    </div>
                </div>
            </div>
        </section>

        <!-- CARDS & STATS (Simplified Desktop Content) -->
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-4 gap-6 mb-16">
                    <a href="/pengaduan/buat?type=Pengaduan" class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center gap-3 hover:shadow-lg hover:-translate-y-1 transition-all">
                        <div class="w-16 h-16 rounded-2xl bg-red-50 group-hover:bg-red-100 text-red-500 flex items-center justify-center text-3xl"><i class="fa-solid fa-circle-exclamation"></i></div>
                        <div><h3 class="font-bold text-gray-800 mb-1">Pengaduan</h3><p class="text-xs text-gray-500">Sampaikan keluhan Anda</p></div>
                    </a>
                    <a href="/pengaduan/buat?type=Saran" class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center gap-3 hover:shadow-lg hover:-translate-y-1 transition-all">
                        <div class="w-16 h-16 rounded-2xl bg-green-50 group-hover:bg-green-100 text-green-500 flex items-center justify-center text-3xl"><i class="fa-solid fa-lightbulb"></i></div>
                        <div><h3 class="font-bold text-gray-800 mb-1">Saran</h3><p class="text-xs text-gray-500">Berikan masukan terbaik</p></div>
                    </a>
                    <a href="/pengaduan/buat?type=Apresiasi" class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center gap-3 hover:shadow-lg hover:-translate-y-1 transition-all">
                        <div class="w-16 h-16 rounded-2xl bg-blue-50 group-hover:bg-blue-100 text-blue-500 flex items-center justify-center text-3xl"><i class="fa-solid fa-thumbs-up"></i></div>
                        <div><h3 class="font-bold text-gray-800 mb-1">Apresiasi</h3><p class="text-xs text-gray-500">Sampaikan penghargaan</p></div>
                    </a>
                    <a href="/pengaduan/buat?type=Informasi" class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center gap-3 hover:shadow-lg hover:-translate-y-1 transition-all">
                        <div class="w-16 h-16 rounded-2xl bg-orange-50 group-hover:bg-orange-100 text-orange-500 flex items-center justify-center text-3xl"><i class="fa-solid fa-circle-info"></i></div>
                        <div><h3 class="font-bold text-gray-800 mb-1">Informasi</h3><p class="text-xs text-gray-500">Permintaan informasi</p></div>
                    </a>
                </div>
            </div>
        </section>

        <!-- FOOTER DESKTOP -->
        <footer class="bg-gray-900 text-gray-400 py-10">
            <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white"><i class="fa-solid fa-hospital"></i></div>
                    <div><p class="font-bold text-white text-sm">Halo MANAP</p><p class="text-xs">RSUD H. Abdul Manap Kota Jambi</p></div>
                </div>
                <p class="text-xs">&copy; {{ date('Y') }} Halo MANAP. Sistem Pengaduan, Aspirasi, dan Informasi Pelayanan.</p>
            </div>
        </footer>
    </div>


    <!-- ========================================================================= -->
    <!--                           MOBILE LAYOUT (PWA)                             -->
    <!-- ========================================================================= -->
    <div class="md:hidden flex flex-col min-h-screen">
        <!-- TOP NAVBAR MOBILE -->
        <header class="bg-white sticky top-0 z-50 px-4 h-14 flex items-center justify-between border-b border-gray-100">
            <button class="text-gray-600 text-lg"><i class="fa-solid fa-bars"></i></button>
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-hospital text-blue-600 text-xl"></i>
                <div class="flex flex-col text-center leading-tight">
                    <span class="font-bold text-sm text-blue-800">Halo <span class="text-green-600">MANAP</span></span>
                    <span class="text-[8px] text-gray-500">RSUD H. Abdul Manap Kota Jambi</span>
                </div>
            </div>
            <button class="relative text-gray-500">
                <i class="fa-regular fa-bell text-lg"></i>
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold w-3.5 h-3.5 rounded-full flex items-center justify-center border border-white">3</span>
            </button>
        </header>

        <!-- MAIN CONTENT MOBILE -->
        <main class="flex-1 px-4 py-5 overflow-y-auto no-scrollbar pb-24">
            
            <!-- Banner Mobile -->
            <div class="rounded-xl overflow-hidden shadow-sm mb-6 bg-white">
                <img src="{{ asset('assets/images/banner-halo-manap.jpg') }}" alt="Banner" class="w-full h-auto object-cover" onerror="this.src='https://placehold.co/600x300/eff6ff/1d4ed8?text=Halo+MANAP'">
            </div>

            <!-- Menu Grid Mobile -->
            <div class="grid grid-cols-2 gap-3 mb-6">
                <a href="/pengaduan/buat?type=Pengaduan" class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center gap-2">
                    <div class="w-12 h-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-circle-exclamation"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-red-500">Pengaduan</h3>
                        <p class="text-[10px] text-gray-500 mt-0.5">Sampaikan keluhan</p>
                    </div>
                </a>

                <a href="/pengaduan/buat?type=Saran" class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center gap-2">
                    <div class="w-12 h-12 rounded-full bg-green-50 text-green-500 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-green-500">Saran</h3>
                        <p class="text-[10px] text-gray-500 mt-0.5">Berikan masukan</p>
                    </div>
                </a>

                <a href="/pengaduan/buat?type=Apresiasi" class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center gap-2">
                    <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-thumbs-up"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-blue-500">Apresiasi</h3>
                        <p class="text-[10px] text-gray-500 mt-0.5">Sampaikan apresiasi</p>
                    </div>
                </a>

                <a href="/pengaduan/buat?type=Informasi" class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center gap-2">
                    <div class="w-12 h-12 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-orange-500">Informasi</h3>
                        <p class="text-[10px] text-gray-500 mt-0.5">Permintaan informasi</p>
                    </div>
                </a>
            </div>

            <!-- Cek Status Mobile -->
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 mb-6">
                <div class="flex items-center gap-2 mb-3 text-gray-700 font-bold">
                    <i class="fa-regular fa-clipboard"></i>
                    <h2>Cek Status Pengaduan</h2>
                </div>
                <form action="{{ route('pengaduan.track') }}" method="GET" class="space-y-3">
                    <input type="text" name="ticket_number" placeholder="Masukkan nomor tiket" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 uppercase font-mono tracking-wider">
                    <button type="submit" class="w-full bg-blue-600 text-white font-semibold rounded-lg px-4 py-2.5 text-sm">
                        Cek Status
                    </button>
                </form>
            </div>
        </main>

        <!-- BOTTOM NAV MOBILE -->
        <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 flex justify-between items-end px-5 py-2 pb-4 z-50 shadow-[0_-4px_20px_rgba(0,0,0,0.07)]">
            <a href="/" class="flex flex-col items-center gap-1 text-blue-600 w-14">
                <i class="fa-solid fa-house text-xl"></i>
                <span class="text-[10px] font-medium">Beranda</span>
            </a>
            <a href="{{ route('pengaduan.track') }}" class="flex flex-col items-center gap-1 text-gray-400 hover:text-blue-500 transition-colors w-14">
                <i class="fa-solid fa-magnifying-glass text-xl"></i>
                <span class="text-[10px] font-medium">Cek Status</span>
            </a>
            <!-- FAB Center -->
            <div class="relative w-14 flex justify-center">
                <a href="/pengaduan/buat" class="absolute -top-9 w-14 h-14 bg-blue-600 text-white rounded-full flex items-center justify-center shadow-lg shadow-blue-500/40 border-4 border-white">
                    <i class="fa-solid fa-plus text-xl"></i>
                </a>
                <span class="text-[10px] font-medium text-gray-400 mt-6 text-center leading-none">Buat<br>Laporan</span>
            </div>
            <a href="#" class="flex flex-col items-center gap-1 text-gray-400 hover:text-blue-500 transition-colors w-14">
                <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                <span class="text-[10px] font-medium">Riwayat</span>
            </a>
            <a href="/dashboard" class="flex flex-col items-center gap-1 text-gray-400 hover:text-blue-500 transition-colors w-14">
                <i class="fa-regular fa-user text-xl"></i>
                <span class="text-[10px] font-medium">Profil</span>
            </a>
        </nav>
    </div>

</div>
@endsection
