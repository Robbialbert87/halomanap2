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
                        <div class="w-9 h-9 flex items-center justify-center">
                            <img src="{{ asset('assets/images/halomanaplogo.png') }}" alt="Halo MANAP" class="w-8 h-8 object-contain">
                        </div>
                        <div>
                            <p class="font-heading font-bold text-lg text-blue-800 leading-tight">Halo <span
                                    class="text-green-600">MANAP</span></p>
                            <p class="text-[9px] text-gray-500 leading-none">RSUD H. Abdul Manap Kota Jambi</p>
                        </div>
                    </div>
                    <nav class="flex items-center gap-1">
                        <a href="/"
                            class="px-4 py-2 text-sm font-semibold text-blue-600 bg-blue-50 rounded-lg">Beranda</a>
                        <a href="/pengaduan/buat?type=Pengaduan"
                            class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors">Pengaduan</a>
                        <a href="https://skm.go.id/share/instansi/cf0fe4fb-d51e-40e0-a3e7-4b6fbb5918b8/2" target="_blank"
                            rel="noopener noreferrer"
                            class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors">Survei</a>
                        <a href="/pengaduan/buat?type=Apresiasi"
                            class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors">Apresiasi</a>
                        <a href="/pengaduan/buat?type=Informasi"
                            class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors">Informasi</a>
                    </nav>
                    <div class="flex items-center gap-3">
                        <a href="/pengaduan/buat"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors shadow-sm shadow-blue-200">
                            <i class="fa-solid fa-plus mr-1.5"></i> Buat Laporan
                        </a>
                        <a href="/dashboard"
                            class="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">
                            <i class="fa-solid fa-shield-halved mr-1"></i> Admin
                        </a>
                    </div>
                </div>
            </header>

            <!-- HERO SECTION -->
            <section class="bg-white">
                <div class="max-w-7xl mx-auto px-6 py-20 flex items-center gap-16">
                    <div class="flex-1">
                        <span
                            class="inline-block bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-full mb-4 uppercase tracking-widest">RSUD
                            H. Abdul Manap Kota Jambi</span>
                        <h1 class="font-heading text-5xl lg:text-6xl font-black text-gray-900 leading-tight mb-5">
                            Selamat datang di<br><span class="text-blue-700">Halo</span> <span
                                class="text-green-600">MANAP</span>
                        </h1>
                        <p class="text-gray-500 text-lg leading-relaxed mb-3">
                            Pusat Pengaduan, Aspirasi dan Informasi Pelayanan RSUD H. Abdul Manap Kota Jambi.
                        </p>
                        <p class="text-blue-600 font-semibold italic mb-8">"Melayani Dengan Setulus Hati"</p>
                        <div class="flex items-center gap-4">
                            <a href="/pengaduan/buat"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-4 rounded-2xl transition-all shadow-lg shadow-blue-200 hover:shadow-blue-300 hover:-translate-y-0.5">
                                <i class="fa-solid fa-circle-exclamation mr-2"></i> Buat Laporan Sekarang
                            </a>
                            <a href="{{ route('pengaduan.track') }}"
                                class="bg-white border-2 border-gray-200 hover:border-blue-300 text-gray-700 font-bold px-8 py-4 rounded-2xl transition-all hover:-translate-y-0.5">
                                <i class="fa-solid fa-magnifying-glass mr-2 text-blue-500"></i> Lacak Status
                            </a>
                        </div>
                    </div>
                    <div class="w-1/2">
                        <div class="rounded-2xl overflow-hidden shadow-xl shadow-blue-900/10 border border-gray-100">
                            <img src="{{ asset('assets/images/banner-halo-manap.jpg') }}" alt="Banner"
                                class="w-full h-auto object-cover"
                                onerror="this.src='https://placehold.co/800x400/eff6ff/1d4ed8?text=Halo+MANAP'">
                        </div>
                    </div>
                </div>
            </section>

            <!-- CARDS & STATS (Simplified Desktop Content) -->
            <section class="py-16">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="grid grid-cols-4 gap-6 mb-16">
                        <a href="/pengaduan/buat?type=Pengaduan"
                            class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center gap-3 hover:shadow-lg hover:-translate-y-1 transition-all">
                            <div
                                class="w-16 h-16 rounded-2xl bg-red-50 group-hover:bg-red-100 text-red-500 flex items-center justify-center text-3xl">
                                <i class="fa-solid fa-circle-exclamation"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 mb-1">Pengaduan</h3>
                                <p class="text-xs text-gray-500">Sampaikan keluhan Anda</p>
                            </div>
                        </a>
                        <a href="https://skm.go.id/share/instansi/cf0fe4fb-d51e-40e0-a3e7-4b6fbb5918b8/2" target="_blank"
                            rel="noopener noreferrer"
                            class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center gap-3 hover:shadow-lg hover:-translate-y-1 transition-all">
                            <div
                                class="w-16 h-16 rounded-2xl bg-green-50 group-hover:bg-green-100 text-green-500 flex items-center justify-center text-3xl">
                                <i class="fa-solid fa-square-poll-vertical"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 mb-1">Survei</h3>
                                <p class="text-xs text-gray-500">Isi Survei Kepuasan Masyarakat</p>
                            </div>
                        </a>
                        <a href="/pengaduan/buat?type=Apresiasi"
                            class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center gap-3 hover:shadow-lg hover:-translate-y-1 transition-all">
                            <div
                                class="w-16 h-16 rounded-2xl bg-blue-50 group-hover:bg-blue-100 text-blue-500 flex items-center justify-center text-3xl">
                                <i class="fa-solid fa-thumbs-up"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 mb-1">Apresiasi</h3>
                                <p class="text-xs text-gray-500">Sampaikan penghargaan</p>
                            </div>
                        </a>
                        <a href="https://simanap.rsudkotajambi.id/" target="_blank"
                            class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center gap-3 hover:shadow-lg hover:-translate-y-1 transition-all">
                            <div
                                class="w-16 h-16 rounded-2xl bg-orange-50 group-hover:bg-orange-100 text-orange-500 flex items-center justify-center text-3xl">
                                <i class="fa-solid fa-circle-info"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 mb-1">Informasi</h3>
                                <p class="text-xs text-gray-500">Permintaan informasi</p>
                            </div>
                        </a>
                    </div>
                </div>
            </section>

            <!-- FOOTER DESKTOP -->
            <footer class="bg-gray-900 text-gray-400 py-10">
                <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center"><img
                                src="{{ asset('assets/images/halomanaplogo.png') }}" alt="Halo MANAP" class="w-7 h-7 object-contain"></div>
                        <div>
                            <p class="font-bold text-white text-sm">Halo MANAP</p>
                            <p class="text-xs">RSUD H. Abdul Manap Kota Jambi</p>
                        </div>
                    </div>
                    <p class="text-xs">&copy; {{ date('Y') }} Halo MANAP. Sistem Pengaduan, Aspirasi, dan Informasi
                        Pelayanan.</p>
                </div>
            </footer>
        </div>


        <!-- ========================================================================= -->
        <!--                           MOBILE LAYOUT (PWA)                             -->
        <!-- ========================================================================= -->
        <div class="md:hidden flex flex-col min-h-screen bg-gray-50">

            <!-- TOP NAVBAR MOBILE -->
            <header class="bg-white/70 backdrop-blur-xl sticky top-0 z-50 border-b border-white/30" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
                <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <img src="{{ asset('assets/images/halomanaplogo.png') }}" alt="Halo MANAP" class="w-8 h-8 object-contain">
                        <div>
                            <span class="font-bold text-lg text-blue-800 leading-tight">Halo <span class="text-green-600">MANAP</span></span>
                            <p class="text-[8px] text-gray-400 leading-none -mt-0.5">RSUD H. Abdul Manap</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- MAIN CONTENT MOBILE -->
            <main class="flex-1 px-4 pt-4 pb-24 overflow-y-auto no-scrollbar">

                <!-- Banner Mobile -->
                <div class="rounded-2xl overflow-hidden shadow-sm border border-gray-100 mb-5 bg-white">
                    <img src="{{ asset('assets/images/banner-halo-manap.jpg') }}" alt="Banner"
                        class="w-full h-auto object-cover"
                        onerror="this.src='https://placehold.co/600x300/eff6ff/1d4ed8?text=Halo+MANAP'">
                </div>

                <!-- Menu Grid 2x2 ala PayApp -->
                <div class="grid grid-cols-2 gap-3 mb-5">

                    <a href="/pengaduan/buat?type=Pengaduan"
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center p-4 active:scale-[0.97] transition-transform"
                        style="height:175px">
                        <div class="flex flex-col items-center justify-center flex-1">
                            <span class="w-14 h-14 rounded-2xl bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center shadow-md shadow-red-200/50">
                                <i class="fa-solid fa-circle-exclamation text-white text-2xl"></i>
                            </span>
                            <h3 class="font-bold text-gray-800 text-base mt-3">Pengaduan</h3>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-auto">Sampaikan keluhan</p>
                    </a>

                    <a href="https://skm.go.id/share/instansi/cf0fe4fb-d51e-40e0-a3e7-4b6fbb5918b8/2" target="_blank"
                        rel="noopener noreferrer"
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center p-4 active:scale-[0.97] transition-transform"
                        style="height:175px">
                        <div class="flex flex-col items-center justify-center flex-1">
                            <span class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-md shadow-emerald-200/50">
                                <i class="fa-solid fa-square-poll-vertical text-white text-2xl"></i>
                            </span>
                            <h3 class="font-bold text-gray-800 text-base mt-3">Survei</h3>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-auto">Isi Survei Kepuasan</p>
                    </a>

                    <a href="/pengaduan/buat?type=Apresiasi"
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center p-4 active:scale-[0.97] transition-transform"
                        style="height:175px">
                        <div class="flex flex-col items-center justify-center flex-1">
                            <span class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md shadow-blue-200/50">
                                <i class="fa-solid fa-thumbs-up text-white text-2xl"></i>
                            </span>
                            <h3 class="font-bold text-gray-800 text-base mt-3">Apresiasi</h3>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-auto">Sampaikan apresiasi</p>
                    </a>

                    <a href="https://simanap.rsudkotajambi.id/" target="_blank"
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center p-4 active:scale-[0.97] transition-transform"
                        style="height:175px">
                        <div class="flex flex-col items-center justify-center flex-1">
                            <span class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-md shadow-orange-200/50">
                                <i class="fa-solid fa-circle-info text-white text-2xl"></i>
                            </span>
                            <h3 class="font-bold text-gray-800 text-base mt-3">Informasi</h3>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-auto">Permintaan informasi</p>
                    </a>
                </div>

                <!-- Cek Status Card ala PayApp Search -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5">
                    <div class="flex items-center mb-4">
                        <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center shadow-md shadow-teal-200/50 flex-shrink-0">
                            <i class="fa-solid fa-magnifying-glass text-white text-lg"></i>
                        </span>
                        <div class="ml-3 flex-1">
                            <h3 class="font-bold text-gray-800 text-sm">Cek Status</h3>
                            <p class="text-[11px] text-gray-400 -mt-0.5">Lacak status pengaduan Anda</p>
                        </div>
                        <span class="bg-red-500 text-white text-[9px] font-bold px-2.5 py-1 rounded-full leading-none">CEK</span>
                    </div>
                    <form action="{{ route('pengaduan.track') }}" method="GET">
                        <div class="flex gap-2">
                            <input type="text" name="ticket_number" placeholder="Masukkan nomor tiket"
                                class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 uppercase font-mono tracking-wider bg-gray-50/50">
                            <button type="submit"
                                class="bg-gradient-to-br from-blue-500 to-blue-700 text-white font-semibold rounded-xl px-4 py-2.5 text-sm shadow-sm shadow-blue-200/50 active:scale-95 transition-all flex-shrink-0">
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </main>

            <!-- BOTTOM NAV ala PayApp (detached rounded) -->
            <nav
                class="fixed bottom-3 left-3 right-3 bg-white/70 backdrop-blur-xl border border-white/30 rounded-2xl flex justify-around items-center px-2 pt-1.5 pb-5 z-50 shadow-[0_-4px_30px_rgba(0,0,0,0.08)]" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
                <a href="/" class="flex flex-col items-center gap-0.5 w-14 py-1 text-blue-600">
                    <i class="fa-solid fa-house text-xl"></i>
                    <span class="text-[9px] font-semibold">Beranda</span>
                </a>
                <a href="{{ route('pengaduan.track') }}"
                    class="flex flex-col items-center gap-0.5 w-14 py-1 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass text-xl"></i>
                    <span class="text-[9px] font-medium">Cek Status</span>
                </a>
                <!-- FAB Center -->
                <div class="relative w-14 flex flex-col items-center">
                    <a href="/pengaduan/buat"
                        class="absolute -top-7 w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-full flex items-center justify-center shadow-lg shadow-blue-500/40 border-[3px] border-white active:scale-90 transition-transform">
                        <i class="fa-solid fa-plus text-xl"></i>
                    </a>
                    <span class="text-[9px] font-medium text-gray-400 mt-6 text-center leading-tight">Buat<br>Laporan</span>
                </div>
                <a href="#"
                    class="flex flex-col items-center gap-0.5 w-14 py-1 text-gray-400">
                    <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                    <span class="text-[9px] font-medium">Riwayat</span>
                </a>
                <a href="/dashboard"
                    class="flex flex-col items-center gap-0.5 w-14 py-1 text-gray-400">
                    <i class="fa-regular fa-user text-xl"></i>
                    <span class="text-[9px] font-medium">Profil</span>
                </a>
            </nav>
        </div>

    </div>
@endsection
