@extends('layouts.app')

@section('title', 'Halo MANAP - Pusat Pengaduan, Aspirasi dan Informasi Pelayanan')

@section('content')
    <div class="bg-[#F3F4F6] min-h-screen">

        <!-- ========================================================================= -->
        <!--                           DESKTOP LAYOUT                                  -->
        <!-- ========================================================================= -->
        <div class="hidden md:block">
            <!-- TOP NAVBAR (PayApp glossy) -->
            <header class="bg-white/70 backdrop-blur-xl sticky top-0 z-50 border-b border-white/30 shadow-sm" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
                <div class="max-w-7xl mx-auto px-6 h-16 grid grid-cols-[auto_1fr_auto] items-center">
                    <a href="/" class="flex items-center gap-2.5 hover:opacity-80 transition-opacity">
                        <div class="w-9 h-9 flex items-center justify-center">
                            <img src="{{ asset('assets/images/halomanaplogo.png') }}" alt="Halo MANAP" class="w-8 h-8 object-contain">
                        </div>
                        <div>
                            <p class="font-heading font-bold text-lg text-blue-800 leading-tight">Halo <span class="text-green-600">MANAP</span></p>
                            <p class="text-[9px] text-gray-500 leading-none">RSUD H. Abdul Manap Kota Jambi</p>
                        </div>
                    </a>
                    <nav class="flex items-center justify-center gap-1">
                        <a href="/" class="px-4 py-2 text-sm font-semibold text-blue-600 bg-blue-50 rounded-lg">Beranda</a>
                        <a href="/pengaduan/buat?type=Pengaduan" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors">Pengaduan</a>
                        <a href="https://skm.go.id/share/instansi/cf0fe4fb-d51e-40e0-a3e7-4b6fbb5918b8/2" target="_blank" rel="noopener noreferrer" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors">Survei</a>
                        <a href="{{ route('apresiasi.create') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors">Apresiasi</a>
                        <a href="/pengaduan/buat?type=Informasi" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors">Informasi</a>
                    </nav>
                    <div class="flex items-center gap-1">
                        <a href="/dashboard" class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition-all" title="Login">
                            <i class="fa-solid fa-shield-halved text-base"></i>
                            <span>Login</span>
                        </a>
                    </div>
                </div>
            </header>

            <!-- HERO SECTION (PayApp glossy) -->
            <section class="py-16 md:py-20">
                <div class="max-w-7xl mx-auto px-6 flex items-center gap-16">
                    <div class="flex-1">
                        <span class="inline-block bg-blue-50/80 backdrop-blur-sm text-blue-700 text-xs font-bold px-3 py-1.5 rounded-full mb-4 uppercase tracking-widest border border-blue-100">RSUD H. Abdul Manap Kota Jambi</span>
                        <h1 class="font-heading text-5xl lg:text-6xl font-black text-gray-900 leading-tight mb-5">
                            Selamat datang di<br><span class="text-blue-700">Halo</span> <span class="text-green-600">MANAP</span>
                        </h1>
                        <p class="text-gray-500 text-lg leading-relaxed mb-3">
                            Pusat Pengaduan, Aspirasi dan Informasi Pelayanan RSUD H. Abdul Manap Kota Jambi.
                        </p>
                        <p class="text-blue-600 font-semibold italic mb-8">"Melayani Dengan Setulus Hati"</p>
                        <div class="flex items-center gap-4">
                            <a href="/pengaduan/buat" class="bg-gradient-to-br from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-bold px-8 py-4 rounded-2xl transition-all shadow-lg shadow-blue-200/50 hover:shadow-xl active:scale-[0.98]">
                                <i class="fa-solid fa-circle-exclamation mr-2"></i> Buat Laporan Sekarang
                            </a>
                            <a href="{{ route('pengaduan.track') }}" class="bg-white/80 backdrop-blur-sm border border-gray-200 hover:border-blue-300 text-gray-700 font-bold px-8 py-4 rounded-2xl transition-all active:scale-[0.98] shadow-sm" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%);">
                                <i class="fa-solid fa-magnifying-glass mr-2 text-blue-500"></i> Lacak Status
                            </a>
                        </div>
                    </div>
                    <div class="w-1/2">
                        <div class="rounded-2xl overflow-hidden shadow-xl shadow-blue-900/10 border border-white/30" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
                            <img src="{{ asset('assets/images/banner-halo-manap.jpg') }}" alt="Banner" class="w-full h-auto object-cover" onerror="this.src='https://placehold.co/800x400/eff6ff/1d4ed8?text=Halo+MANAP'">
                        </div>
                    </div>
                </div>
            </section>

            <!-- CARDS (PayApp glossy) -->
            <section class="pb-16">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="grid grid-cols-4 gap-6">
                        <a href="/pengaduan/buat?type=Pengaduan"
                            class="group bg-white/80 backdrop-blur-xl p-8 rounded-2xl shadow-sm border border-white/30 flex flex-col items-center text-center gap-3 hover:shadow-lg hover:-translate-y-1 transition-all active:scale-[0.98]" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-red-400 to-red-600 text-white flex items-center justify-center text-3xl shadow-md shadow-red-200/50">
                                <i class="fa-solid fa-circle-exclamation"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 mb-1 font-heading">Pengaduan</h3>
                                <p class="text-xs text-gray-500">Sampaikan keluhan Anda</p>
                            </div>
                        </a>
                        <a href="https://skm.go.id/share/instansi/cf0fe4fb-d51e-40e0-a3e7-4b6fbb5918b8/2" target="_blank" rel="noopener noreferrer"
                            class="group bg-white/80 backdrop-blur-xl p-8 rounded-2xl shadow-sm border border-white/30 flex flex-col items-center text-center gap-3 hover:shadow-lg hover:-translate-y-1 transition-all active:scale-[0.98]" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 text-white flex items-center justify-center text-3xl shadow-md shadow-emerald-200/50">
                                <i class="fa-solid fa-square-poll-vertical"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 mb-1 font-heading">Survei</h3>
                                <p class="text-xs text-gray-500">Isi Survei Kepuasan Masyarakat</p>
                            </div>
                        </a>
                        <a href="{{ route('apresiasi.create') }}"
                            class="group bg-white/80 backdrop-blur-xl p-8 rounded-2xl shadow-sm border border-white/30 flex flex-col items-center text-center gap-3 hover:shadow-lg hover:-translate-y-1 transition-all active:scale-[0.98]" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 text-white flex items-center justify-center text-3xl shadow-md shadow-blue-200/50">
                                <i class="fa-solid fa-thumbs-up"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 mb-1 font-heading">Apresiasi</h3>
                                <p class="text-xs text-gray-500">Sampaikan penghargaan</p>
                            </div>
                        </a>
                        <a href="https://simanap.rsudkotajambi.id/" target="_blank"
                            class="group bg-white/80 backdrop-blur-xl p-8 rounded-2xl shadow-sm border border-white/30 flex flex-col items-center text-center gap-3 hover:shadow-lg hover:-translate-y-1 transition-all active:scale-[0.98]" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 text-white flex items-center justify-center text-3xl shadow-md shadow-orange-200/50">
                                <i class="fa-solid fa-circle-info"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 mb-1 font-heading">Informasi</h3>
                                <p class="text-xs text-gray-500">Permintaan informasi</p>
                            </div>
                        </a>
                    </div>
                </div>
            </section>

            <!-- FOOTER DESKTOP -->
            <footer class="bg-gray-900 text-gray-400 py-12">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="grid grid-cols-3 gap-8 mb-8">
                        {{-- Brand --}}
                        <div>
                            <a href="/" class="flex items-center gap-3 hover:opacity-80 transition-opacity mb-3">
                                <img src="{{ asset('assets/images/halomanaplogo.png') }}" alt="Halo MANAP" class="w-8 h-8 object-contain">
                                <div>
                                    <p class="font-bold text-white text-sm">Halo MANAP</p>
                                    <p class="text-xs">RSUD H. Abdul Manap</p>
                                </div>
                            </a>
                            <p class="text-xs leading-relaxed text-gray-500">Pusat Pengaduan, Aspirasi dan Informasi Pelayanan RSUD H. Abdul Manap Kota Jambi.</p>
                        </div>
                        {{-- Layanan --}}
                        <div>
                            <h4 class="text-white text-xs font-bold uppercase tracking-wider mb-3">Layanan</h4>
                            <ul class="space-y-2 text-xs">
                                <li><a href="/pengaduan/buat?type=Pengaduan" class="hover:text-white transition-colors">Pengaduan</a></li>
                                <li><a href="https://skm.go.id/share/instansi/cf0fe4fb-d51e-40e0-a3e7-4b6fbb5918b8/2" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">Survei Kepuasan</a></li>
                                <li><a href="{{ route('apresiasi.create') }}" class="hover:text-white transition-colors">Apresiasi</a></li>
                                <li><a href="/pengaduan/buat?type=Informasi" class="hover:text-white transition-colors">Informasi</a></li>
                                <li><a href="{{ route('pengaduan.track') }}" class="hover:text-white transition-colors">Lacak Pengaduan</a></li>
                            </ul>
                        </div>
                        {{-- Kontak --}}
                        <div>
                            <h4 class="text-white text-xs font-bold uppercase tracking-wider mb-3">Kontak</h4>
                            <ul class="space-y-2.5 text-xs">
                                <li class="flex items-center gap-2.5">
                                    <i class="fa-solid fa-location-dot text-blue-400 w-3.5 text-center"></i>
                                    <span>Jl. Kol. M. Kukuhan No.11, Sungai Putri, Kec. Danau Teluk, Kota Jambi</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <i class="fa-solid fa-phone text-blue-400 w-3.5 text-center"></i>
                                    <span>(0741) 500-6908</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <i class="fa-solid fa-envelope text-blue-400 w-3.5 text-center"></i>
                                    <a href="mailto:rsudhamanap.jambi@gmail.com" class="hover:text-white transition-colors">rsudhamanap.jambi@gmail.com</a>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <i class="fa-brands fa-instagram text-blue-400 w-3.5 text-center"></i>
                                    <a href="https://www.instagram.com/rsud_h_abdul_manap/" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">@rsud_h_abdul_manap</a>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <i class="fa-brands fa-youtube text-blue-400 w-3.5 text-center"></i>
                                    <a href="https://www.youtube.com/@rsudh.abdulmanapkotaJambi" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">RSUD H. Abdul Manap</a>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <i class="fa-solid fa-globe text-blue-400 w-3.5 text-center"></i>
                                    <a href="https://rsudkotajambi.id" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">rsudkotajambi.id</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="border-t border-gray-800 pt-6 text-center text-xs text-gray-600">
                        &copy; {{ date('Y') }} Halo MANAP — RSUD H. Abdul Manap Kota Jambi. All rights reserved.
                    </div>
                </div>
            </footer>
        </div>


        <!-- ========================================================================= -->
        <!--                           MOBILE LAYOUT (PWA)                             -->
        <!-- ========================================================================= -->
        <div class="md:hidden flex flex-col min-h-screen bg-[#F3F4F6]">

            <!-- TOP NAVBAR MOBILE -->
            <header class="bg-white/70 backdrop-blur-xl sticky top-0 z-50 border-b border-white/30" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
                <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between">
                    <a href="/" class="flex items-center gap-2.5 hover:opacity-80 transition-opacity">
                        <img src="{{ asset('assets/images/halomanaplogo.png') }}" alt="Halo MANAP" class="w-8 h-8 object-contain">
                        <div>
                            <span class="font-bold text-lg text-blue-800 leading-tight">Halo <span class="text-green-600">MANAP</span></span>
                            <p class="text-[8px] text-gray-400 leading-none -mt-0.5">RSUD H. Abdul Manap</p>
                        </div>
                    </a>
                </div>
            </header>

            <!-- MAIN CONTENT MOBILE -->
            <main class="flex-1 px-4 pt-4 pb-24 overflow-y-auto no-scrollbar">

                <!-- Banner Mobile -->
                <div class="rounded-2xl overflow-hidden shadow-sm border border-white/30 mb-5" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
                    <img src="{{ asset('assets/images/banner-halo-manap.jpg') }}" alt="Banner"
                        class="w-full h-auto object-cover"
                        onerror="this.src='https://placehold.co/600x300/eff6ff/1d4ed8?text=Halo+MANAP'">
                </div>

                <!-- Menu Grid 2x2 ala PayApp -->
                <div class="grid grid-cols-2 gap-3 mb-5">

                    <a href="/pengaduan/buat?type=Pengaduan"
                        class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/30 flex flex-col items-center p-4 active:scale-[0.97] transition-transform"
                        style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%); height:175px">
                        <div class="flex flex-col items-center justify-center flex-1">
                            <span class="w-14 h-14 rounded-2xl bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center shadow-md shadow-red-200/50">
                                <i class="fa-solid fa-circle-exclamation text-white text-2xl"></i>
                            </span>
                            <h3 class="font-bold text-gray-800 text-base mt-3 font-heading">Pengaduan</h3>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-auto">Sampaikan keluhan</p>
                    </a>

                    <a href="https://skm.go.id/share/instansi/cf0fe4fb-d51e-40e0-a3e7-4b6fbb5918b8/2" target="_blank" rel="noopener noreferrer"
                        class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/30 flex flex-col items-center p-4 active:scale-[0.97] transition-transform"
                        style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%); height:175px">
                        <div class="flex flex-col items-center justify-center flex-1">
                            <span class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-md shadow-emerald-200/50">
                                <i class="fa-solid fa-square-poll-vertical text-white text-2xl"></i>
                            </span>
                            <h3 class="font-bold text-gray-800 text-base mt-3 font-heading">Survei</h3>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-auto">Isi Survei Kepuasan</p>
                    </a>

                    <a href="{{ route('apresiasi.create') }}"
                        class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/30 flex flex-col items-center p-4 active:scale-[0.97] transition-transform"
                        style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%); height:175px">
                        <div class="flex flex-col items-center justify-center flex-1">
                            <span class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md shadow-blue-200/50">
                                <i class="fa-solid fa-thumbs-up text-white text-2xl"></i>
                            </span>
                            <h3 class="font-bold text-gray-800 text-base mt-3 font-heading">Apresiasi</h3>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-auto">Sampaikan apresiasi</p>
                    </a>

                    <a href="https://simanap.rsudkotajambi.id/" target="_blank"
                        class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/30 flex flex-col items-center p-4 active:scale-[0.97] transition-transform"
                        style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%); height:175px">
                        <div class="flex flex-col items-center justify-center flex-1">
                            <span class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-md shadow-orange-200/50">
                                <i class="fa-solid fa-circle-info text-white text-2xl"></i>
                            </span>
                            <h3 class="font-bold text-gray-800 text-base mt-3 font-heading">Informasi</h3>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-auto">Permintaan informasi</p>
                    </a>
                </div>

                <!-- Cek Status Card ala PayApp Search -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/30 p-5 mb-5" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
                    <div class="flex items-center mb-4">
                        <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center shadow-md shadow-teal-200/50 flex-shrink-0">
                            <i class="fa-solid fa-magnifying-glass text-white text-lg"></i>
                        </span>
                        <div class="ml-3 flex-1">
                            <h3 class="font-bold text-gray-800 text-sm font-heading">Cek Status</h3>
                            <p class="text-[11px] text-gray-400 -mt-0.5">Lacak status pengaduan Anda</p>
                        </div>
                        <span class="bg-red-500 text-white text-[9px] font-bold px-2.5 py-1 rounded-full leading-none">CEK</span>
                    </div>
                    <form action="{{ route('pengaduan.track') }}" method="GET">
                        <div class="flex gap-2">
                            <input type="text" name="ticket_number" placeholder="Masukkan nomor tiket"
                                class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 uppercase font-mono tracking-wider bg-white/70">
                            <button type="submit"
                                class="bg-gradient-to-br from-blue-500 to-blue-700 text-white font-semibold rounded-xl px-4 py-2.5 text-sm shadow-sm shadow-blue-200/50 active:scale-95 transition-all flex-shrink-0">
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Mobile Footer Contact --}}
                <div class="bg-gray-900 rounded-2xl p-4 mb-4">
                    <div class="flex items-center gap-2.5 mb-3">
                        <img src="{{ asset('assets/images/halomanaplogo.png') }}" alt="Halo MANAP" class="w-6 h-6 object-contain">
                        <div>
                            <p class="font-bold text-white text-xs">Halo MANAP</p>
                            <p class="text-[9px] text-gray-500">RSUD H. Abdul Manap</p>
                        </div>
                    </div>
                    <div class="space-y-1.5 text-[10px] text-gray-400">
                        <p class="flex items-start gap-1.5">
                            <i class="fa-solid fa-location-dot text-blue-400 mt-0.5"></i>
                            Jl. Kol. M. Kukuhan No.11, Sungai Putri, Danau Teluk, Kota Jambi
                        </p>
                        <p class="flex items-center gap-1.5"><i class="fa-solid fa-phone text-blue-400"></i> (0741) 500-6908</p>
                        <p class="flex items-center gap-1.5"><i class="fa-solid fa-envelope text-blue-400"></i> rsudhamanap.jambi@gmail.com</p>
                        <div class="flex items-center gap-3 pt-1">
                            <a href="https://www.instagram.com/rsud_h_abdul_manap/" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-blue-400 transition-colors"><i class="fa-brands fa-instagram text-sm"></i></a>
                            <a href="https://www.youtube.com/@rsudh.abdulmanapkotaJambi" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-blue-400 transition-colors"><i class="fa-brands fa-youtube text-sm"></i></a>
                            <a href="https://rsudkotajambi.id" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-blue-400 transition-colors"><i class="fa-solid fa-globe text-sm"></i></a>
                        </div>
                    </div>
                    <p class="text-[8px] text-gray-600 mt-3 text-center">&copy; {{ date('Y') }} Halo MANAP — RSUD H. Abdul Manap</p>
                </div>
            </main>

            <!-- BOTTOM NAV ala PayApp (detached rounded) -->
            <nav class="fixed bottom-3 left-3 right-3 bg-white/70 backdrop-blur-xl border border-white/30 rounded-2xl flex justify-around items-center px-2 pt-1.5 pb-5 z-50 shadow-[0_-4px_30px_rgba(0,0,0,0.08)]" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
                <a href="/" class="flex flex-col items-center gap-0.5 w-14 py-1 text-blue-600">
                    <i class="fa-solid fa-house text-xl"></i>
                    <span class="text-[9px] font-semibold">Beranda</span>
                </a>
                <a href="{{ route('pengaduan.track') }}" class="flex flex-col items-center gap-0.5 w-14 py-1 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass text-xl"></i>
                    <span class="text-[9px] font-medium">Cek Status</span>
                </a>
                <!-- FAB Center -->
                <div class="relative w-14 flex flex-col items-center">
                    <a href="/pengaduan/buat" class="absolute -top-7 w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-full flex items-center justify-center shadow-lg shadow-blue-500/40 border-[3px] border-white active:scale-90 transition-transform">
                        <i class="fa-solid fa-plus text-xl"></i>
                    </a>
                    <span class="text-[9px] font-medium text-gray-400 mt-6 text-center leading-tight">Buat<br>Laporan</span>
                </div>
                <a href="https://skm.go.id/share/instansi/cf0fe4fb-d51e-40e0-a3e7-4b6fbb5918b8/2" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center gap-0.5 w-14 py-1 text-gray-400">
                    <i class="fa-solid fa-square-poll-vertical text-xl"></i>
                    <span class="text-[9px] font-medium">Survei</span>
                </a>
                <a href="{{ route('apresiasi.create') }}" class="flex flex-col items-center gap-0.5 w-14 py-1 text-gray-400">
                    <i class="fa-solid fa-thumbs-up text-xl"></i>
                    <span class="text-[9px] font-medium">Apresiasi</span>
                </a>
            </nav>
        </div>

    </div>
@endsection
