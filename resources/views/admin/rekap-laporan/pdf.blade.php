<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan INM - Kecepatan Waktu Tanggap Komplain</title>
    <style>
        /* F4/Folio: atas 3cm, kanan 3cm, bawah 3cm, kiri 4cm */
        @page { margin: 3cm 3cm 3cm 4cm; }

        * { box-sizing: border-box; }
        body {
            font-family: arial, sans-serif;
            font-size: 12pt;
            color: #111827;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        /* ── Kepala laporan ── */
        .cover {
            text-align: center;
            border: 2px solid #1e3a5f;
            padding: 16px 14px;
            margin-bottom: 22px;
        }
        .cover h1 { font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.8px; margin: 0 0 4px 0; color: #1e3a5f; }
        .cover h2 { font-size: 12.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px 0; }
        .cover h3 { font-size: 12.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 10px 0; }
        .periode {
            display: inline-block;
            background: #1e3a5f;
            color: #ffffff;
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 1px;
            padding: 4px 18px;
            margin-top: 2px;
        }

        /* ── Struktur bab ── */
        .bab { page-break-before: always; }
        .bab:first-of-type { page-break-before: avoid; }
        .judul-bab {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #1e3a5f;
            border-bottom: 2px solid #1e3a5f;
            padding-bottom: 5px;
            margin: 0 0 14px 0;
        }
        .sub-bab { font-weight: bold; font-size: 12pt; margin: 14px 0 6px 0; }
        p { text-align: justify; margin: 0 0 8px 0; }
        ol.tulis ul, ul.tulis { margin: 0 0 8px 0; padding-left: 22px; }
        ol.tulis li, ul.tulis li { text-align: justify; margin-bottom: 4px; }

        /* ── Tabel ── */
        table { width: 100%; border-collapse: collapse; margin: 8px 0 12px 0; }
        caption { caption-side: top; text-align: left; font-weight: bold; font-style: italic; font-size: 10.5pt; padding-bottom: 5px; }
        th { background: #1e3a5f; color: white; padding: 7px 8px; text-align: center; font-size: 11pt; text-transform: uppercase; border: 1px solid #1e3a5f; }
        td { padding: 7px 8px; border: 1px solid #d1d5db; font-size: 11pt; vertical-align: top; }
        tr:nth-child(even) td { background: #f3f4f6; }
        td.no, th.no { text-align: center; width: 46px; font-weight: bold; }
        td.jumlah, th.jumlah { text-align: center; width: 80px; font-weight: bold; }

        /* ── Grafik ── */
        .grafik-wrap { text-align: center; margin-top: 10px; }
        .grafik-judul { text-align: center; font-size: 11pt; font-weight: bold; color: #1e3a5f; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        .legend { text-align: center; margin-bottom: 6px; }
        .legend span.item { display: inline-block; margin: 0 7px; font-size: 9pt; color: #4b5563; }
        .legend span.dot { display: inline-block; width: 9px; height: 9px; border-radius: 2px; }
        .caption { text-align: center; font-style: italic; font-size: 9.5pt; color: #6b7280; margin-top: 5px; }

        /* ── Rumus Pecahan ── */
        .rumus { text-align: center; margin: 8px 0; font-size: 11pt; }
        .fraction { display: inline-block; text-align: center; vertical-align: middle; margin: 0 4px; }
        .fraction .numerator { display: block; border-bottom: 1px solid #111827; padding: 0 8px 3px; }
        .fraction .denominator { display: block; padding: 3px 8px 0; }
        .rumus-result { font-weight: bold; color: #1e3a5f; }
    </style>
</head>
<body>

@php
    $total = array_sum($chartData);
    $kategoriUrut = [
        'Tenaga Kesehatan',
        'Sarana & Prasarana',
        'Keluhan Lainnya',
        'Ketersediaan Obat',
    ];
@endphp

{{-- ══════════ KEPALA LAPORAN ══════════ --}}
<div class="cover">
    <h1>Laporan Indikator Nasional Mutu</h1>
    <h2>( INM )</h2>
    <h3>Indikator: Kecepatan Waktu Tanggap Komplain</h3>
    <div class="periode">PERIODE {{ strtoupper($namaBulan) }} {{ $tahun }}</div>
</div>

{{-- ══════════ BAB I ══════════ --}}
<div class="bab">
    <div class="judul-bab">Bab I: Pendahuluan</div>

    <div class="sub-bab">A. Dasar Pemikiran</div>
    <ol class="tulis">
        <li>Berdasarkan Undang-Undang Nomor 44 Tahun 2009 tentang Rumah Sakit Pasal 32, pasien berhak mengajukan pengaduan atas pelayanan kesehatan yang diterimanya.</li>
        <li>Rumah sakit berkewajiban memberikan pelayanan kesehatan yang aman, bermutu, dan efektif kepada seluruh pasien.</li>
        <li>Kecepatan waktu tanggap komplain adalah rentang waktu yang diperlukan rumah sakit dalam merespon keluhan baik lisan, tertulis, maupun melalui media massa, melalui proses identifikasi, grading, analisis, hingga tindak lanjut.</li>
        <li>Standar waktu tanggap komplain berdasarkan grading adalah sebagai berikut:</li>
    </ol>

    <table>
        <thead>
            <tr>
                <th class="no">No</th>
                <th>Grading Komplain</th>
                <th style="width: 180px;">Standar Waktu Tanggap</th>
            </tr>
        </thead>
        <tbody>
            <tr><td class="no">1</td><td>Merah (Ekstrim)</td><td>Maksimal 1 &times; 24 jam</td></tr>
            <tr><td class="no">2</td><td>Kuning (Tinggi)</td><td>Maksimal 3 hari</td></tr>
            <tr><td class="no">3</td><td>Hijau (Rendah)</td><td>Maksimal 7 hari</td></tr>
        </tbody>
    </table>

    <div class="sub-bab">B. Tujuan</div>
    <ol class="tulis">
        <li><b>Tujuan Umum:</b> Menangani semua keluhan pasien agar dapat diselesaikan secara profesional dan kekeluargaan.</li>
        <li><b>Tujuan Khusus:</b> Tergambarnya kecepatan rumah sakit dalam merespon keluhan untuk perbaikan mutu dan pemenuhan hak pasien.</li>
    </ol>
</div>

{{-- ══════════ BAB II ══════════ --}}
<div class="bab">
    <div class="judul-bab">Bab II: Kamus Indikator (Definisi Operasional)</div>

    <table>
        <thead>
            <tr>
                <th style="width: 170px;">Komponen</th>
                <th>Definisi Operasional</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><b>A. Numerator (Pembilang)</b></td>
                <td>Jumlah komplain yang ditanggapi dan ditindaklanjuti sesuai waktu yang ditetapkan berdasarkan grading.</td>
            </tr>
            <tr>
                <td><b>B. Denominator (Penyebut)</b></td>
                <td>Jumlah komplain yang disurvei.</td>
            </tr>
            <tr>
                <td><b>C. Target Pencapaian</b></td>
                <td>&ge; 80%</td>
            </tr>
            <tr>
                <td><b>D. Kriteria Inklusi</b></td>
                <td>Semua komplain baik lisan, tertulis, dan media massa.</td>
            </tr>
            <tr>
                <td><b>E. Kriteria Eksklusi</b></td>
                <td>Tidak ada.</td>
            </tr>
            <tr>
                <td><b>F. Formula / Rumus</b></td>
                <td>
                    <div class="rumus">
                        <span class="fraction">
                            <span class="numerator">Jumlah komplain ditanggapi dan ditindaklanjuti ({{ $selesai }})</span>
                            <span class="denominator">Jumlah komplain yang disurvei ({{ $totalData }})</span>
                        </span>
                        &times; 100% = <span class="rumus-result">{{ $persentase }}%</span>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{-- ══════════ BAB III ══════════ --}}
<div class="bab">
    <div class="judul-bab">Bab III: Metodologi Pengumpulan Data</div>

    <table>
        <thead>
            <tr>
                <th style="width: 220px;">Komponen Metodologi</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><b>A. Metode</b></td>
                <td>Pendekatan penelitian deskriptif retrospektif.</td>
            </tr>
            <tr>
                <td><b>B. Sumber Data</b></td>
                <td>Data sekunder dari catatan komplain unit layanan pengaduan.</td>
            </tr>
            <tr>
                <td><b>C. Instrumen Pengumpulan Data</b></td>
                <td>1. Formulir Komplain;&nbsp;&nbsp;2. Laporan Tindak Lanjut Komplain.</td>
            </tr>
            <tr>
                <td><b>D. Besar Sampel</b></td>
                <td>Total sampel (populasi &le; 30 orang) atau menggunakan rumus Slovin (populasi &gt; 30 orang).</td>
            </tr>
            <tr>
                <td><b>E. Cara Pengambilan Sampel</b></td>
                <td>Probability Sampling &ndash; Simple Random Sampling.</td>
            </tr>
        </tbody>
    </table>
</div>

{{-- ══════════ BAB IV ══════════ --}}
<div class="bab">
    <div class="judul-bab">Bab IV: Hasil Penelitian, Tabel, dan Grafik</div>

    <div class="sub-bab">A. Hasil Pengumpulan Data</div>
    <p>Pada periode {{ strtoupper($namaBulan) }} {{ $tahun }}, total komplain masuk sebanyak
        <b>{{ $total }}</b> laporan, dengan rincian sebagai berikut:
        @foreach($kategoriUrut as $i => $k)
            <b>{{ chr(65 + $i) }}) {{ $k }}</b> sebanyak <b>{{ $chartData[$k] ?? 0 }}</b>{{ $loop->last ? '.' : ';' }}
        @endforeach
    Rincian data disajikan pada tabel dan grafik di bawah ini.</p>

    <div class="sub-bab">B. Tabel Rekapitulasi Komplain per Kategori</div>
    <table>
        <thead>
            <tr>
                <th class="no">No</th>
                <th>Kategori</th>
                <th class="jumlah">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="no">A</td>
                <td>TENAGA KESEHATAN</td>
                <td class="jumlah">{{ $chartData['Tenaga Kesehatan'] ?? 0 }}</td>
            </tr>
            <tr>
                <td class="no">B</td>
                <td>SARANA &amp; PRASARANA</td>
                <td class="jumlah">{{ $chartData['Sarana & Prasarana'] ?? 0 }}</td>
            </tr>
            <tr>
                <td class="no">C</td>
                <td>KELUHAN LAINNYA (ASURANSI, JADWAL &amp; ADMINISTRASI YANKES LAINNYA)</td>
                <td class="jumlah">{{ $chartData['Keluhan Lainnya'] ?? 0 }}</td>
            </tr>
            <tr>
                <td class="no">D</td>
                <td>KETERSEDIAAN OBAT</td>
                <td class="jumlah">{{ $chartData['Ketersediaan Obat'] ?? 0 }}</td>
            </tr>
            <tr style="background: #e5e7eb; font-weight: bold;">
                <td class="no"></td>
                <td>TOTAL</td>
                <td class="jumlah">{{ $total }}</td>
            </tr>
        </tbody>
    </table>

    <div class="sub-bab">C. Grafik Rekapitulasi Komplain per Kategori</div>
    @if($chartImage)
    @php
        $legendItems = [
            ['label' => 'Tenaga Kesehatan', 'color' => '#3874ff'],
            ['label' => 'Sarana & Prasarana', 'color' => '#f59e0b'],
            ['label' => 'Keluhan Lainnya', 'color' => '#f97316'],
            ['label' => 'Ketersediaan Obat', 'color' => '#22c55e'],
        ];
    @endphp
    <div class="grafik-wrap">
        <div class="legend">
            @foreach($legendItems as $item)
                <span class="item"><span class="dot" style="background: {{ $item['color'] }};"></span> {{ $item['label'] }}</span>
            @endforeach
        </div>
        <img src="{{ $chartImage }}" style="width: 100%; max-width: 480px;" alt="Grafik Rekap Pengaduan">
        <div class="caption">Grafik 1. Rekapitulasi jumlah komplain per kategori periode {{ strtoupper($namaBulan) }} {{ $tahun }}</div>
    </div>
    @endif
</div>

{{-- ══════════ HALAMAN D + E ══════════ --}}
<div class="bab">
    <div class="sub-bab">D. Analisis Capaian</div>
    @if($sectionContent && $sectionContent->analisis_capaian)
        {!! nl2br(e($sectionContent->analisis_capaian)) !!}
    @else
        <p style="color: #9ca3af; font-style: italic;">Belum ada data analisis capaian untuk periode ini.</p>
    @endif

    <div style="height: 40px;"></div>

    <div class="sub-bab">E. Rencana Tindak Lanjut</div>
    @if($sectionContent && $sectionContent->rencana_tindak_lanjut)
        {!! nl2br(e($sectionContent->rencana_tindak_lanjut)) !!}
    @else
        <p style="color: #9ca3af; font-style: italic;">Belum ada data rencana tindak lanjut untuk periode ini.</p>
    @endif
</div>

{{-- ── Nomor halaman (dievaluasi DomPDF per halaman) ── --}}
<script type="text/php">
if (isset($pdf)) {
    $font = $fontMetrics->get_font("arial", "normal");
    if ($font === null) {
        $font = $fontMetrics->get_font("helvetica", "normal");
    }
    $size = 8;
    $color = array(0.38, 0.42, 0.48);
    $w = $pdf->get_width();
    $h = $pdf->get_height();

    $identitas = "Laporan INM - Kecepatan Waktu Tanggap Komplain | Periode {{ strtoupper($namaBulan) }} {{ $tahun }}";
    $pdf->page_text(113, $h - 40, $identitas, $font, $size, $color);

    $halaman = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
    $lebarHal = $fontMetrics->get_text_width(str_replace("{PAGE_NUM}", "00", str_replace("{PAGE_COUNT}", "00", $halaman)), $font, $size);
    $pdf->page_text($w - 85 - $lebarHal, $h - 40, $halaman, $font, $size, $color);
}
</script>

</body>
</html>
