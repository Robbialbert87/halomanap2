<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rekap Pengaduan</title>
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
            margin-bottom: 20px;
        }
        .cover h1 { font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.8px; margin: 0 0 4px 0; color: #1e3a5f; }
        .cover h2 { font-size: 12pt; font-weight: normal; margin: 0 0 10px 0; }
        .periode {
            display: inline-block;
            background: #1e3a5f;
            color: #ffffff;
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 1px;
            padding: 4px 18px;
            margin-bottom: 8px;
        }
        .total-line { font-size: 11pt; color: #374151; }

        /* ── Blok pengaduan ── */
        .item {
            border: 1px solid #cbd5e1;
            padding: 12px 14px;
            margin-bottom: 14px;
            page-break-inside: avoid;
        }
        .item-head {
            background: #eef2f7;
            border-bottom: 1px solid #cbd5e1;
            margin: -12px -14px 10px -14px;
            padding: 6px 14px;
            overflow: hidden;
        }
        .item-head .no { float: left; font-weight: bold; font-size: 12pt; color: #1e3a5f; }
        .item-head .tgl { float: right; font-size: 11pt; color: #4b5563; }

        table.meta { width: 100%; border-collapse: collapse; margin: 0 0 8px 0; }
        table.meta td {
            border: none;
            padding: 2px 0;
            font-size: 11pt;
            vertical-align: top;
            background: none !important;
        }
        table.meta td.label { width: 130px; font-weight: bold; color: #374151; }

        .isi-label { font-weight: bold; color: #374151; margin-top: 6px; }
        .isi-teks {
            text-align: justify;
            background: #f8fafc;
            border-left: 3px solid #94a3b8;
            padding: 6px 10px;
            margin: 3px 0 6px 0;
        }

        .bukti-wrap { margin-top: 8px; }
        .bukti-label { font-weight: bold; color: #374151; margin-bottom: 4px; }
        .bukti-img { width: 240px; border: 1px solid #d1d5db; }

        .status-baru     { color: #1d4ed8; font-weight: bold; }
        .status-diproses { color: #b45309; font-weight: bold; }
        .status-selesai  { color: #15803d; font-weight: bold; }

        .kosong {
            text-align: center;
            color: #9ca3af;
            padding: 30px 0;
            font-size: 12pt;
        }
    </style>
</head>
<body>

{{-- ══════════ KEPALA LAPORAN ══════════ --}}
<div class="cover">
    <h1>Laporan Rekap Pengaduan</h1>
    <div class="periode">PERIODE {{ strtoupper($namaBulan) }} {{ $tahun }}</div>
    <div class="total-line">Total {{ $total }} laporan pengaduan</div>
</div>

{{-- ══════════ DATA PENGADUAN ══════════ --}}
@if($data->isEmpty())
<div class="kosong">Tidak ada data rekap pengaduan pada periode ini.</div>
@else
@foreach($data as $item)
@php
    $statusClass = match($item->status) {
        'Baru'     => 'status-baru',
        'Diproses' => 'status-diproses',
        default    => 'status-selesai',
    };
@endphp
<div class="item">
    <div class="item-head">
        <span class="no">Laporan #{{ $loop->iteration }}</span>
        <span class="tgl">{{ $item->tanggal->format('d/m/Y') }}</span>
    </div>

    <table class="meta">
        <tr><td class="label">Nama Pelapor</td><td>: {{ $item->nama ?? '-' }}</td></tr>
        <tr><td class="label">No. Telepon/WA</td><td>: {{ $item->nomor_pelapor ?? '-' }}</td></tr>
        <tr><td class="label">Via Pengaduan</td><td>: {{ $item->via_pengaduan }}</td></tr>
        <tr><td class="label">Kategori</td><td>: {{ $item->kategori }}</td></tr>
        <tr>
            <td class="label">Status</td>
            <td>: <span class="{{ $statusClass }}">{{ $item->status }}</span>{{ $item->status === 'Selesai' && $item->tanggal_selesai ? ' (' . $item->tanggal_selesai->format('d/m/Y') . ')' : '' }}</td>
        </tr>
    </table>

    <div class="isi-label">Keluhan / Pengaduan:</div>
    <div class="isi-teks">{{ $item->keluhan }}</div>

    <div class="isi-label">Tindak Lanjut:</div>
    <div class="isi-teks">{{ $item->tindak_lanjut ?? '-' }}</div>

    @if(isset($buktiImages[$item->id]))
    <div class="bukti-wrap">
        <div class="bukti-label">Bukti Pengaduan:</div>
        <img class="bukti-img" src="{{ $buktiImages[$item->id] }}" alt="Bukti pengaduan">
    </div>
    @endif
</div>
@endforeach
@endif

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

    $identitas = "Laporan Rekap Pengaduan | Periode {{ strtoupper($namaBulan) }} {{ $tahun }}";
    $pdf->page_text(113, $h - 40, $identitas, $font, $size, $color);

    $halaman = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
    $lebarHal = $fontMetrics->get_text_width(str_replace("{PAGE_NUM}", "00", str_replace("{PAGE_COUNT}", "00", $halaman)), $font, $size);
    $pdf->page_text($w - 85 - $lebarHal, $h - 40, $halaman, $font, $size, $color);
}
</script>

</body>
</html>
