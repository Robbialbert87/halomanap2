@extends('layouts.admin')

@section('title', 'Rekap Laporan Bulanan - Halo MANAP')

@section('admin_content')

{{-- Mobile Page Header --}}
<div class="md:hidden mb-3">
    <div class="flex items-center gap-2.5 p-1">
        <span class="w-9 h-9 rounded-2xl bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center shadow-sm shadow-violet-200/50 flex-shrink-0">
            <i class="fa-solid fa-table-list text-white text-sm"></i>
        </span>
        <div>
            <p class="text-[9px] text-violet-500 font-semibold tracking-wider uppercase font-heading">Monitoring & Laporan</p>
            <h1 class="text-base font-bold text-gray-800 font-heading">Rekap Laporan Bulanan</h1>
        </div>
    </div>
</div>

{{-- Page Title (Desktop) --}}
<div class="hidden md:flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-heading">Rekap Laporan Bulanan</h1>
        <p class="text-sm text-gray-500">Rekap pengaduan dari berbagai kanal - input manual</p>
    </div>
    <button onclick="document.getElementById('formModal').classList.remove('hidden')"
        class="bg-gradient-to-br from-violet-500 to-violet-700 text-white font-semibold rounded-xl px-5 py-2.5 text-sm shadow-md shadow-violet-200/50 hover:shadow-lg active:scale-[0.98] transition-all flex items-center gap-1.5">
        <i class="fa-solid fa-plus"></i> Tambah Data
    </button>
</div>

{{-- FILTER FORM --}}
<div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/30 p-4 md:p-6 mb-4 md:mb-6" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
    <form method="GET" action="{{ route('admin.rekap-laporan') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Bulan</label>
            <select name="bulan"
                class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-violet-500 focus:border-violet-500 block p-2.5">
                @foreach(['1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus','9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $num => $nama)
                    <option value="{{ $num }}" {{ !$semua && $bulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Tahun</label>
            <select name="tahun"
                class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-violet-500 focus:border-violet-500 block p-2.5">
                @for($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ !$semua && $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit"
                class="flex-1 bg-gradient-to-br from-violet-500 to-violet-700 text-white font-semibold rounded-xl px-5 py-2.5 text-sm shadow-md shadow-violet-200/50 hover:shadow-lg active:scale-[0.98] transition-all flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            <a href="{{ route('admin.rekap-laporan', ['semua' => 1]) }}"
                class="flex-1 bg-gradient-to-br from-emerald-500 to-emerald-700 text-white font-semibold rounded-xl px-5 py-2.5 text-sm shadow-md shadow-emerald-200/50 hover:shadow-lg active:scale-[0.98] transition-all flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-list"></i> Semua
            </a>
            <a href="{{ route('admin.rekap-laporan') }}"
                class="flex-1 bg-white/70 border border-gray-200 text-gray-600 font-medium rounded-xl px-5 py-2.5 text-sm hover:bg-gray-50 active:scale-[0.98] transition-all flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-rotate-right"></i> Reset
            </a>
        </div>
    </form>
    @if(!$semua && $bulan && $tahun)
    <div class="flex items-end gap-2 mt-3 pt-3 border-t border-gray-100">
        <a href="{{ route('admin.rekap-laporan.export-pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
            class="bg-gradient-to-br from-red-500 to-red-700 text-white font-semibold rounded-xl px-5 py-2.5 text-sm shadow-md shadow-red-200/50 hover:shadow-lg active:scale-[0.98] transition-all flex items-center justify-center gap-1.5">
            <i class="fa-solid fa-file-pdf"></i> Export PDF INM
        </a>
    </div>
    @endif
</div>

@if($semua)
<div class="mb-4 md:mb-6">
    <div class="bg-emerald-50/80 border border-emerald-200 rounded-xl px-4 py-2.5 flex items-center gap-2">
        <i class="fa-solid fa-circle-info text-emerald-600 text-sm"></i>
        <span class="text-sm font-semibold text-emerald-700">Menampilkan Semua Data</span>
    </div>
</div>
@endif

{{-- STATISTIK RINGKASAN --}}
@php
$stats = [
    ['label' => 'Total',            'value' => $data->count(),                                 'icon' => 'fa-inbox',          'color' => 'violet'],
    ['label' => 'Tenaga Kesehatan', 'value' => $chartData['Tenaga Kesehatan'] ?? 0,            'icon' => 'fa-user-doctor',    'color' => 'blue'],
    ['label' => 'Sarana & Prasarana','value' => $chartData['Sarana & Prasarana'] ?? 0,          'icon' => 'fa-hospital',      'color' => 'amber'],
    ['label' => 'Keluhan Lainnya',  'value' => $chartData['Keluhan Lainnya'] ?? 0,             'icon' => 'fa-clipboard-list','color' => 'orange'],
    ['label' => 'Ketersediaan Obat','value' => $chartData['Ketersediaan Obat'] ?? 0,           'icon' => 'fa-pills',         'color' => 'green'],
    ['label' => 'Selesai',          'value' => $data->where('status', 'Selesai')->count(),     'icon' => 'fa-circle-check',  'color' => 'emerald'],
];
$palette = [
    'violet'  => 'bg-violet-50/80 text-violet-700 border-violet-200',
    'blue'    => 'bg-blue-50/80 text-blue-700 border-blue-200',
    'amber'   => 'bg-amber-50/80 text-amber-700 border-amber-200',
    'orange'  => 'bg-orange-50/80 text-orange-700 border-orange-200',
    'green'   => 'bg-green-50/80 text-green-700 border-green-200',
    'emerald' => 'bg-emerald-50/80 text-emerald-700 border-emerald-200',
];
$iconBg = [
    'violet'  => 'from-violet-400 to-violet-600',
    'blue'    => 'from-blue-400 to-blue-600',
    'amber'   => 'from-amber-400 to-amber-600',
    'orange'  => 'from-orange-400 to-orange-600',
    'green'   => 'from-green-400 to-green-600',
    'emerald' => 'from-emerald-400 to-emerald-600',
];
@endphp

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-4 md:mb-6">
    @foreach($stats as $card)
    <div class="rounded-xl border {{ $palette[$card['color']] }} p-3 md:p-4 shadow-sm flex flex-col gap-1 md:gap-2" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
        <div class="flex items-center justify-between">
            <p class="text-[10px] md:text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $card['label'] }}</p>
            <span class="w-8 h-8 rounded-xl bg-gradient-to-br {{ $iconBg[$card['color']] }} flex items-center justify-center shadow-sm flex-shrink-0">
                <i class="fa-solid {{ $card['icon'] }} text-white text-xs"></i>
            </span>
        </div>
        <p class="text-xl md:text-3xl font-bold font-heading">{{ $card['value'] }}</p>
    </div>
    @endforeach
</div>

{{-- GRAFIK --}}
<div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/30 p-4 md:p-6 mb-4 md:mb-6" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
    <div class="d-sm-flex justify-content-between align-items-start mb-3">
        <div>
            <h4 class="text-base md:text-lg font-bold text-gray-800 font-heading">Grafik Rekap per Kategori</h4>
            <p class="text-xs text-gray-500 mt-0.5">Jumlah pengaduan berdasarkan kategori</p>
        </div>
        <div id="rekapLegend" class="d-flex gap-3 flex-wrap mt-2 mt-sm-0"></div>
    </div>
    <div class="mt-4" style="height:300px;">
        <div id="rekapChart" style="width:100%;height:100%;"></div>
    </div>
</div>

{{-- TABEL DATA --}}
<div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/30 overflow-hidden" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
    <div class="px-4 md:px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-sm md:text-base font-bold text-gray-800 font-heading flex items-center gap-2">
            <span class="w-7 h-7 rounded-lg bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center shadow-sm">
                <i class="fa-solid fa-list text-white text-xs"></i>
            </span>
            Data Rekap Pengaduan ({{ $data->count() }})
        </h2>
        {{-- Mobile add button --}}
        <button onclick="document.getElementById('formModal').classList.remove('hidden')"
            class="md:hidden bg-gradient-to-br from-violet-500 to-violet-700 text-white font-semibold rounded-xl px-3 py-2 text-xs shadow-md shadow-violet-200/50 flex items-center gap-1">
            <i class="fa-solid fa-plus"></i> Tambah
        </button>
    </div>

    @if($data->isEmpty())
        <div class="text-center py-12 text-gray-400">
            <i class="fa-solid fa-inbox text-4xl mb-3"></i>
            <p class="text-sm font-medium">Tidak ada data rekap pengaduan</p>
            <p class="text-xs mt-1">Klik "Tambah Data" untuk menambahkan rekap pengaduan</p>
        </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50/80 text-gray-500 text-[11px] md:text-xs uppercase tracking-wider font-semibold">
                    <th class="text-center px-3 md:px-4 py-3 w-12">No</th>
                    <th class="text-left px-3 md:px-4 py-3">Tanggal</th>
                    <th class="text-left px-3 md:px-4 py-3 hidden md:table-cell">Nama</th>
                    <th class="text-left px-3 md:px-4 py-3 hidden lg:table-cell">No. Telp</th>
                    <th class="text-left px-3 md:px-4 py-3">Via Pengaduan</th>
                    <th class="text-left px-3 md:px-4 py-3 hidden lg:table-cell">Kategori</th>
                    <th class="text-left px-3 md:px-4 py-3">Keluhan/Pengaduan</th>
                    <th class="text-left px-3 md:px-4 py-3 hidden xl:table-cell">Tindak Lanjut</th>
                    <th class="text-left px-3 md:px-4 py-3">Status</th>
                    <th class="text-center px-3 md:px-4 py-3 w-10"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($data as $idx => $item)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-3 md:px-4 py-3 text-center text-gray-400 text-xs">{{ $idx + 1 }}</td>
                    <td class="px-3 md:px-4 py-3 text-gray-700 text-xs whitespace-nowrap">{{ $item->tanggal->format('d/m/Y') }}</td>
                    <td class="px-3 md:px-4 py-3 text-gray-700 hidden md:table-cell">{{ $item->nama ?? '-' }}</td>
                    <td class="px-3 md:px-4 py-3 text-gray-500 text-xs hidden lg:table-cell">{{ $item->nomor_pelapor ?? '-' }}</td>
                    <td class="px-3 md:px-4 py-3 text-gray-500 text-xs">{{ $item->via_pengaduan }}</td>
                    <td class="px-3 md:px-4 py-3 hidden lg:table-cell">
                        @php
                        $katBadge = match($item->kategori) {
                            'Tenaga Kesehatan'       => 'bg-blue-100 text-blue-700',
                            'Sarana & Prasarana'     => 'bg-amber-100 text-amber-700',
                            'Keluhan Lainnya'        => 'bg-orange-100 text-orange-700',
                            'Ketersediaan Obat'      => 'bg-green-100 text-green-700',
                            default                  => 'bg-gray-100 text-gray-700',
                        };
                        @endphp
                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] md:text-xs font-semibold {{ $katBadge }}">{{ $item->kategori }}</span>
                    </td>
                    <td class="px-3 md:px-4 py-3 text-gray-600 text-xs max-w-[200px] truncate" title="{{ $item->keluhan }}">{{ Str::limit($item->keluhan, 80) }}</td>
                    <td class="px-3 md:px-4 py-3 text-gray-600 text-xs max-w-[200px] truncate hidden xl:table-cell" title="{{ $item->tindak_lanjut ?? '' }}">{{ $item->tindak_lanjut ? Str::limit($item->tindak_lanjut, 60) : '-' }}</td>
                    <td class="px-3 md:px-4 py-3">
                        @php
                        $stBadge = match($item->status) {
                            'Baru'     => 'bg-blue-100 text-blue-700',
                            'Diproses' => 'bg-amber-100 text-amber-700',
                            'Selesai'  => 'bg-green-100 text-green-700',
                            default    => 'bg-gray-100 text-gray-700',
                        };
                        @endphp
                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] md:text-xs font-semibold {{ $stBadge }}">
                            {{ $item->status }}@if($item->status === 'Selesai' && $item->tanggal_selesai)
                                &nbsp;- {{ $item->tanggal_selesai->format('d/m/Y') }}
                            @endif
                        </span>
                    </td>
                    <td class="px-3 md:px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button type="button" onclick='openPreview(@json($item))'
                                class="text-blue-400 hover:text-blue-600 transition-colors" title="Preview">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </button>
                            @if($item->bukti)
                            <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank"
                                class="text-emerald-400 hover:text-emerald-600 transition-colors" title="Lihat Bukti">
                                <i class="fa-solid fa-paperclip text-xs"></i>
                            </a>
                            @endif
                            <button type="button" onclick='openEdit(@json($item))'
                                class="text-amber-400 hover:text-amber-600 transition-colors" title="Edit">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>
                            <form action="{{ route('admin.rekap-laporan.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="bulan" value="{{ $bulan }}">
                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                <button type="submit" class="text-red-400 hover:text-red-600 transition-colors" title="Hapus">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- MODAL FORM TAMBAH DATA --}}
<div id="formModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.4); backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white rounded-t-2xl">
            <h3 class="text-base font-bold text-gray-800 font-heading flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-plus text-white text-xs"></i>
                </span>
                Tambah Rekap Pengaduan
            </h3>
            <button onclick="document.getElementById('formModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form action="{{ route('admin.rekap-laporan.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="bulan" value="{{ $bulan }}">
            <input type="hidden" name="tahun" value="{{ $tahun }}">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                        class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-violet-500 focus:border-violet-500 block p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama</label>
                    <input type="text" name="nama" placeholder="Nama pelapor (opsional)"
                        class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-violet-500 focus:border-violet-500 block p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">No. Telp/WA</label>
                    <input type="text" name="nomor_pelapor" placeholder="Nomor telepon (opsional)"
                        class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-violet-500 focus:border-violet-500 block p-2.5">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Via Pengaduan <span class="text-red-500">*</span></label>
                    <select name="via_pengaduan" required
                        class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-violet-500 focus:border-violet-500 block p-2.5">
                        <option value="">Pilih Kanal</option>
                        <option>WhatsApp</option>
                        <option>Telepon</option>
                        <option>SMS</option>
                        <option>Email</option>
                        <option>Walk-in</option>
                        <option>Website</option>
                        <option>Social Media</option>
                        <option>Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori" required
                        class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-violet-500 focus:border-violet-500 block p-2.5">
                        <option value="">Pilih Kategori</option>
                        <option>Tenaga Kesehatan</option>
                        <option>Sarana & Prasarana</option>
                        <option>Keluhan Lainnya</option>
                        <option>Ketersediaan Obat</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Keluhan/Pengaduan <span class="text-red-500">*</span></label>
                <textarea name="keluhan" rows="3" placeholder="Deskripsi keluhan atau pengaduan..." required
                    class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-violet-500 focus:border-violet-500 block p-2.5 resize-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Upload Bukti <span class="text-[10px] font-normal text-gray-400">(opsional — JPG/WebP, otomatis dikompres)</span></label>
                <label class="flex items-center gap-3 cursor-pointer bg-white/70 border border-dashed border-gray-300 rounded-xl p-3 hover:border-violet-400 hover:bg-violet-50/40 transition-colors">
                    <input type="file" name="bukti" accept=".jpg,.jpeg,.webp,image/jpeg,image/webp" class="hidden"
                        onchange="document.getElementById('buktiFileName').textContent = this.files.length ? this.files[0].name : 'Pilih file gambar (JPG/WebP)'">
                    <span class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-cloud-arrow-up text-violet-500 text-sm"></i>
                    </span>
                    <span id="buktiFileName" class="text-xs text-gray-500 truncate">Klik untuk pilih file gambar (JPG/WebP)</span>
                </label>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Tindak Lanjut</label>
                <textarea name="tindak_lanjut" rows="2" placeholder="Tindak lanjut yang dilakukan (opsional)..."
                    class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-violet-500 focus:border-violet-500 block p-2.5 resize-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status" required onchange="toggleTanggalSelesai(this)"
                    class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-violet-500 focus:border-violet-500 block p-2.5">
                    <option value="Baru">Baru</option>
                    <option value="Diproses">Diproses</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>

            <div id="tanggalSelesaiWrap" class="hidden">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Penyelesaian <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_selesai" value="{{ date('Y-m-d') }}"
                    class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-violet-500 focus:border-violet-500 block p-2.5">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 bg-gradient-to-br from-violet-500 to-violet-700 text-white font-semibold rounded-xl px-5 py-2.5 text-sm shadow-md shadow-violet-200/50 hover:shadow-lg active:scale-[0.98] transition-all flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-save"></i> Simpan
                </button>
                <button type="button" onclick="document.getElementById('formModal').classList.add('hidden')"
                    class="flex-1 bg-white/70 border border-gray-200 text-gray-600 font-medium rounded-xl px-5 py-2.5 text-sm hover:bg-gray-50 active:scale-[0.98] transition-all">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL PREVIEW --}}
<div id="previewModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.4); backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white rounded-t-2xl">
            <h3 class="text-base font-bold text-gray-800 font-heading flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-eye text-white text-xs"></i>
                </span>
                Detail Rekap Pengaduan
            </h3>
            <button onclick="document.getElementById('previewModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Tanggal</p>
                    <p id="prevTanggal" class="text-sm font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Nama</p>
                    <p id="prevNama" class="text-sm font-medium text-gray-800">-</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">No. Telp/WA</p>
                    <p id="prevNomor" class="text-sm font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Via Pengaduan</p>
                    <p id="prevVia" class="text-sm font-medium text-gray-800">-</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Kategori</p>
                    <p id="prevKategori" class="text-sm font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Status</p>
                    <p id="prevStatus" class="text-sm font-medium text-gray-800">-</p>
                </div>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Keluhan/Pengaduan</p>
                <p id="prevKeluhan" class="text-sm text-gray-700 bg-gray-50 rounded-xl p-3">-</p>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Tindak Lanjut</p>
                <p id="prevTindakLanjut" class="text-sm text-gray-700 bg-gray-50 rounded-xl p-3">-</p>
            </div>
            <div id="prevBuktiWrap" class="hidden">
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Bukti</p>
                <a id="prevBuktiLink" href="#" target="_blank" class="block group relative">
                    <img id="prevBuktiImg" src="" alt="Bukti pengaduan"
                        class="w-full max-h-56 object-contain rounded-xl border border-gray-100 bg-gray-50">
                    <span class="absolute inset-0 flex items-center justify-center rounded-xl bg-black/0 group-hover:bg-black/20 transition-colors">
                        <i class="fa-solid fa-up-right-and-down-left-from-center text-white opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </span>
                </a>
            </div>
            <div>
                <button onclick="document.getElementById('previewModal').classList.add('hidden')"
                    class="w-full bg-white/70 border border-gray-200 text-gray-600 font-medium rounded-xl px-5 py-2.5 text-sm hover:bg-gray-50 active:scale-[0.98] transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.4); backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white rounded-t-2xl">
            <h3 class="text-base font-bold text-gray-800 font-heading flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-pen-to-square text-white text-xs"></i>
                </span>
                Edit Rekap Pengaduan
            </h3>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="bulan" value="{{ $bulan }}">
            <input type="hidden" name="tahun" value="{{ $tahun }}">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" id="editTanggal" required
                        class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-amber-500 focus:border-amber-500 block p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama</label>
                    <input type="text" name="nama" id="editNama" placeholder="Nama pelapor (opsional)"
                        class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-amber-500 focus:border-amber-500 block p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">No. Telp/WA</label>
                    <input type="text" name="nomor_pelapor" id="editNomor" placeholder="Nomor telepon (opsional)"
                        class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-amber-500 focus:border-amber-500 block p-2.5">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Via Pengaduan <span class="text-red-500">*</span></label>
                    <select name="via_pengaduan" id="editVia" required
                        class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-amber-500 focus:border-amber-500 block p-2.5">
                        <option value="">Pilih Kanal</option>
                        <option>WhatsApp</option>
                        <option>Telepon</option>
                        <option>SMS</option>
                        <option>Email</option>
                        <option>Walk-in</option>
                        <option>Website</option>
                        <option>Social Media</option>
                        <option>Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori" id="editKategori" required
                        class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-amber-500 focus:border-amber-500 block p-2.5">
                        <option value="">Pilih Kategori</option>
                        <option>Tenaga Kesehatan</option>
                        <option>Sarana & Prasarana</option>
                        <option>Keluhan Lainnya</option>
                        <option>Ketersediaan Obat</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Keluhan/Pengaduan <span class="text-red-500">*</span></label>
                <textarea name="keluhan" id="editKeluhan" rows="3" placeholder="Deskripsi keluhan atau pengaduan..." required
                    class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-amber-500 focus:border-amber-500 block p-2.5 resize-none"></textarea>
            </div>

            <div id="editBuktiCurrentWrap" class="hidden bg-gray-50 rounded-xl p-3">
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Bukti Saat Ini</p>
                <div class="flex items-center justify-between gap-3">
                    <a id="editBuktiLink" href="#" target="_blank"
                        class="text-blue-500 hover:text-blue-700 text-xs font-medium flex items-center gap-1.5">
                        <i class="fa-solid fa-paperclip"></i> Lihat Bukti
                    </a>
                    <label class="inline-flex items-center gap-1.5 text-xs text-red-500 cursor-pointer">
                        <input type="checkbox" name="hapus_bukti" value="1" class="rounded border-gray-300 text-red-500 focus:ring-red-400">
                        Hapus bukti
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Upload Bukti <span class="text-[10px] font-normal text-gray-400">(opsional — JPG/WebP, otomatis dikompres)</span></label>
                <label class="flex items-center gap-3 cursor-pointer bg-white/70 border border-dashed border-gray-300 rounded-xl p-3 hover:border-amber-400 hover:bg-amber-50/40 transition-colors">
                    <input type="file" name="bukti" id="editBuktiInput" accept=".jpg,.jpeg,.webp,image/jpeg,image/webp" class="hidden"
                        onchange="document.getElementById('editBuktiFileName').textContent = this.files.length ? this.files[0].name : 'Ganti dengan file baru (opsional)'">
                    <span class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-cloud-arrow-up text-amber-500 text-sm"></i>
                    </span>
                    <span id="editBuktiFileName" class="text-xs text-gray-500 truncate">Klik untuk ganti file gambar (JPG/WebP)</span>
                </label>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Tindak Lanjut</label>
                <textarea name="tindak_lanjut" id="editTindakLanjut" rows="2" placeholder="Tindak lanjut yang dilakukan (opsional)..."
                    class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-amber-500 focus:border-amber-500 block p-2.5 resize-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status" id="editStatus" required onchange="toggleTanggalSelesaiEdit(this)"
                    class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-amber-500 focus:border-amber-500 block p-2.5">
                    <option value="Baru">Baru</option>
                    <option value="Diproses">Diproses</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>

            <div id="editTanggalSelesaiWrap" class="hidden">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Penyelesaian <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_selesai" id="editTanggalSelesai"
                    class="w-full bg-white/70 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-amber-500 focus:border-amber-500 block p-2.5">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 bg-gradient-to-br from-amber-500 to-amber-700 text-white font-semibold rounded-xl px-5 py-2.5 text-sm shadow-md shadow-amber-200/50 hover:shadow-lg active:scale-[0.98] transition-all flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-save"></i> Simpan Perubahan
                </button>
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')"
                    class="flex-1 bg-white/70 border border-gray-200 text-gray-600 font-medium rounded-xl px-5 py-2.5 text-sm hover:bg-gray-50 active:scale-[0.98] transition-all">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleTanggalSelesai(select) {
    const wrap = document.getElementById('tanggalSelesaiWrap');
    const input = wrap.querySelector('input');
    if (select.value === 'Selesai') {
        wrap.classList.remove('hidden');
        input.setAttribute('required', 'required');
    } else {
        wrap.classList.add('hidden');
        input.removeAttribute('required');
        input.value = '{{ date('Y-m-d') }}';
    }
}

function toggleTanggalSelesaiEdit(select) {
    const wrap = document.getElementById('editTanggalSelesaiWrap');
    const input = wrap.querySelector('input');
    if (select.value === 'Selesai') {
        wrap.classList.remove('hidden');
        input.setAttribute('required', 'required');
    } else {
        wrap.classList.add('hidden');
        input.removeAttribute('required');
    }
}

function formatDate(dateStr) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    const day = String(d.getDate()).padStart(2, '0');
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const year = d.getFullYear();
    return day + '/' + month + '/' + year;
}

function openPreview(item) {
    document.getElementById('prevTanggal').textContent = formatDate(item.tanggal);
    document.getElementById('prevNama').textContent = item.nama || '-';
    document.getElementById('prevNomor').textContent = item.nomor_pelapor || '-';
    document.getElementById('prevVia').textContent = item.via_pengaduan || '-';
    document.getElementById('prevKategori').textContent = item.kategori || '-';
    let statusText = item.status;
    if (item.status === 'Selesai' && item.tanggal_selesai) {
        statusText += ' - ' + formatDate(item.tanggal_selesai);
    }
    document.getElementById('prevStatus').textContent = statusText;
    document.getElementById('prevKeluhan').textContent = item.keluhan || '-';
    document.getElementById('prevTindakLanjut').textContent = item.tindak_lanjut || '-';

    const buktiWrap = document.getElementById('prevBuktiWrap');
    if (item.bukti) {
        const url = '/storage/' + item.bukti;
        document.getElementById('prevBuktiImg').src = url;
        document.getElementById('prevBuktiLink').href = url;
        buktiWrap.classList.remove('hidden');
    } else {
        document.getElementById('prevBuktiImg').src = '';
        document.getElementById('prevBuktiLink').href = '#';
        buktiWrap.classList.add('hidden');
    }

    document.getElementById('previewModal').classList.remove('hidden');
}

function openEdit(item) {
    document.getElementById('editForm').action = '{{ route("admin.rekap-laporan.update", "__ID__") }}'.replace('__ID__', item.id);
    document.getElementById('editTanggal').value = item.tanggal ? item.tanggal.substring(0, 10) : '';
    document.getElementById('editNama').value = item.nama || '';
    document.getElementById('editNomor').value = item.nomor_pelapor || '';
    document.getElementById('editVia').value = item.via_pengaduan || '';
    document.getElementById('editKategori').value = item.kategori || '';
    document.getElementById('editKeluhan').value = item.keluhan || '';
    document.getElementById('editTindakLanjut').value = item.tindak_lanjut || '';

    const editBuktiWrap = document.getElementById('editBuktiCurrentWrap');
    if (item.bukti) {
        document.getElementById('editBuktiLink').href = '/storage/' + item.bukti;
        editBuktiWrap.querySelector('input[name="hapus_bukti"]').checked = false;
        editBuktiWrap.classList.remove('hidden');
    } else {
        editBuktiWrap.classList.add('hidden');
    }

    const editBuktiInput = document.getElementById('editBuktiInput');
    editBuktiInput.value = '';
    document.getElementById('editBuktiFileName').textContent = 'Klik untuk ganti file gambar (JPG/WebP)';
    document.getElementById('editStatus').value = item.status || 'Baru';
    document.getElementById('editTanggalSelesai').value = item.tanggal_selesai ? item.tanggal_selesai.substring(0, 10) : '{{ date('Y-m-d') }}';
    toggleTanggalSelesaiEdit(document.getElementById('editStatus'));
    document.getElementById('editModal').classList.remove('hidden');
}
</script>
<script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartDom = document.getElementById('rekapChart');
    if (!chartDom) return;

    const chartData = @json($chartData);
    const labels = Object.keys(chartData);
    const values = Object.values(chartData);

    const colors = [
        { border: '#3874ff', label: 'Tenaga Kesehatan' },
        { border: '#f59e0b', label: 'Sarana & Prasarana' },
        { border: '#f97316', label: 'Keluhan Lainnya' },
        { border: '#22c55e', label: 'Ketersediaan Obat' },
    ];

    const legendContainer = document.getElementById('rekapLegend');
    if (legendContainer) {
        legendContainer.innerHTML = colors.map(c =>
            `<div class="flex items-center gap-1.5">
                <span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:${c.border};"></span>
                <span class="text-xs text-gray-600 font-medium">${c.label}</span>
            </div>`
        ).join('');
    }

    const myChart = echarts.init(chartDom);
    const option = {
        tooltip: {
            trigger: 'axis',
            backgroundColor: 'rgba(15,23,42,0.92)',
            borderColor: 'rgba(15,23,42,0.92)',
            borderWidth: 0,
            textStyle: { color: '#fff', fontSize: 12 },
            formatter: function(params) {
                let html = '<b>' + params[0].axisValue + '</b><br/>';
                params.forEach(p => {
                    if (p.value != null) {
                        html += '<span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:' + p.color + ';margin-right:6px;"></span>' + p.seriesName + ': <b>' + p.value + '</b><br/>';
                    }
                });
                return html;
            }
        },
        grid: { left: '3%', right: '4%', bottom: '3%', top: '10%', containLabel: true },
        xAxis: {
            type: 'category',
            data: labels,
            axisTick: { show: false },
            axisLine: { show: false },
            axisLabel: { color: '#6b7280', fontSize: 11, fontWeight: 500 }
        },
        yAxis: {
            type: 'value',
            minInterval: 1,
            axisLabel: { color: '#9ca3af', fontSize: 11 },
            splitLine: { lineStyle: { color: 'rgba(0,0,0,0.04)' } },
            axisLine: { show: false },
            axisTick: { show: false }
        },
        series: colors.map((c, i) => ({
            name: c.label,
            type: 'line',
            data: values,
            smooth: 0.4,
            symbol: 'circle',
            symbolSize: 10,
            itemStyle: { color: '#fff', borderColor: c.border, borderWidth: 2.5 },
            emphasis: { itemStyle: { borderWidth: 3 } },
            lineStyle: { width: 2.5, color: c.border },
            areaStyle: {
                color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                    { offset: 0, color: c.border.replace(')', ',0.20)').replace('rgb', 'rgba').replace('#', '') || 'rgba(56,116,255,0.20)' },
                    { offset: 1, color: 'rgba(56,116,255,0.01)' }
                ])
            }
        }))
    };

    option.series.forEach((s, i) => {
        s.areaStyle = {
            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                { offset: 0, color: hexToRgba(colors[i].border, 0.20) },
                { offset: 1, color: hexToRgba(colors[i].border, 0.01) }
            ])
        };
    });

    myChart.setOption(option);

    function hexToRgba(hex, alpha) {
        const r = parseInt(hex.slice(1, 3), 16);
        const g = parseInt(hex.slice(3, 5), 16);
        const b = parseInt(hex.slice(5, 7), 16);
        return 'rgba(' + r + ',' + g + ',' + b + ',' + alpha + ')';
    }

    window.addEventListener('resize', () => myChart.resize());
});
</script>
@endpush

@endsection
