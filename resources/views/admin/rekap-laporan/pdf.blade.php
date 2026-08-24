<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan INM - Rekap Pengaduan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #1a1a1a; margin: 0; padding: 30px; }
        .header { text-align: center; margin-bottom: 10px; }
        .header h1 { font-size: 16px; font-weight: bold; text-transform: uppercase; margin: 0 0 4px 0; letter-spacing: 0.5px; }
        .header h2 { font-size: 14px; font-weight: bold; text-transform: uppercase; margin: 0 0 4px 0; }
        .header h3 { font-size: 14px; font-weight: bold; text-transform: uppercase; margin: 0 0 20px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #1e3a5f; color: white; padding: 10px 8px; text-align: center; font-size: 11px; text-transform: uppercase; border: 1px solid #1e3a5f; }
        td { padding: 10px 8px; border: 1px solid #d1d5db; font-size: 11px; }
        td.no { text-align: center; width: 50px; font-weight: bold; }
        td.jumlah { text-align: center; width: 80px; font-weight: bold; }
        tr:nth-child(even) { background: #f3f4f6; }
        .footer { text-align: center; color: #999; font-size: 9px; margin-top: 40px; padding-top: 10px; border-top: 1px solid #e5e7eb; }
        .info { text-align: right; font-size: 10px; color: #666; margin-bottom: 15px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN INDIKATOR NASIONAL MUTU</h1>
        <h2>( INM )</h2>
        <h3>KECEPATAN WAKTU TANGGAP KOMPLAIN</h3>
    </div>

    <div class="info">
        BULAN {{ strtoupper($namaBulan) }} TAHUN {{ $tahun }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th>KATEGORI</th>
                <th style="width: 80px;">JUMLAH</th>
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
                <td class="jumlah">{{ array_sum($chartData) }}</td>
            </tr>
        </tbody>
    </table>

    @if($chartImage)
    @php
        $legendItems = [
            ['label' => 'Tenaga Kesehatan', 'color' => '#3874ff'],
            ['label' => 'Sarana & Prasarana', 'color' => '#f59e0b'],
            ['label' => 'Keluhan Lainnya', 'color' => '#f97316'],
            ['label' => 'Ketersediaan Obat', 'color' => '#22c55e'],
        ];
    @endphp
    <div style="margin-top: 25px;">
        <div style="text-align: center; font-size: 12px; font-weight: bold; color: #1e3a5f; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Grafik Rekap per Kategori</div>
        <div style="text-align: center; margin-bottom: 6px;">
            @foreach($legendItems as $item)
                <span style="display: inline-block; margin: 0 7px; font-size: 9px; color: #4b5563;">
                    <span style="display: inline-block; width: 9px; height: 9px; background: {{ $item['color'] }}; border-radius: 2px;"></span>
                    {{ $item['label'] }}
                </span>
            @endforeach
        </div>
        <div style="text-align: center;">
            <img src="{{ $chartImage }}" style="width: 100%; max-width: 500px;" alt="Grafik Rekap Pengaduan">
        </div>
    </div>
    @endif

    <div class="footer">
        Laporan INM Kecepatan Waktu Tanggap Komplain — {{ strtoupper($namaBulan) }} {{ $tahun }}
    </div>

</body>
</html>
