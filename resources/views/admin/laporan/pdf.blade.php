<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pengaduan</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        h1 { text-align: center; font-size: 18px; margin-bottom: 4px; }
        h2 { text-align: center; font-size: 12px; font-weight: normal; color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #2563eb; color: white; padding: 8px 6px; text-align: left; font-size: 10px; text-transform: uppercase; }
        td { padding: 6px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        tr:nth-child(even) { background: #f9fafb; }
        .stats { display: flex; justify-content: space-between; margin-bottom: 15px; }
        .stat { text-align: center; flex: 1; padding: 8px; border: 1px solid #e5e7eb; border-radius: 4px; margin: 0 4px; }
        .stat p { margin: 2px 0; }
        .stat .label { font-size: 9px; color: #666; text-transform: uppercase; }
        .stat .value { font-size: 16px; font-weight: bold; color: #2563eb; }
        .footer { text-align: center; color: #999; font-size: 9px; margin-top: 20px; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 9px; }
        .badge-baru { background: #dbeafe; color: #1d4ed8; }
        .badge-diproses { background: #fef3c7; color: #d97706; }
        .badge-menunggu { background: #ffedd5; color: #ea580c; }
        .badge-selesai { background: #d1fae5; color: #059669; }
        .badge-ditolak { background: #fee2e2; color: #dc2626; }
        .header-left { float: left; width: 50%; }
        .header-right { float: right; width: 50%; text-align: right; }
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>

    <div class="clearfix">
        <div class="header-left">
            <h1>Laporan Pengaduan</h1>
            <h2>RSUD H. Abdul Manap Kota Jambi</h2>
        </div>
        <div class="header-right" style="padding-top: 10px;">
            <p style="font-size: 10px; color: #666;">Periode: {{ $periode }}</p>
            <p style="font-size: 10px; color: #666;">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="stats">
        <div class="stat">
            <p class="label">Total</p>
            <p class="value">{{ $total }}</p>
        </div>
        <div class="stat">
            <p class="label">Baru</p>
            <p class="value">{{ $baru }}</p>
        </div>
        <div class="stat">
            <p class="label">Diproses</p>
            <p class="value">{{ $diproses }}</p>
        </div>
        <div class="stat">
            <p class="label">Menunggu Ver.</p>
            <p class="value">{{ $menungguVerifikasi }}</p>
        </div>
        <div class="stat">
            <p class="label">Selesai</p>
            <p class="value">{{ $selesai }}</p>
        </div>
        <div class="stat">
            <p class="label">Ditolak</p>
            <p class="value">{{ $ditolak }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No Tiket</th>
                <th>Judul</th>
                <th>Pelapor</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
            <tr>
                <td>{{ $ticket->ticket_number }}</td>
                <td>{{ $ticket->title }}</td>
                <td>{{ $ticket->is_anonymous ? 'Anonim' : ($ticket->reporter_name ?? '-') }}</td>
                <td>
                    <span class="badge badge-{{ strtolower(str_replace(' ', '-', $ticket->status)) }}">
                        {{ $ticket->status }}
                    </span>
                </td>
                <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #999; padding: 20px;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Laporan Pengaduan RSUD H. Abdul Manap Kota Jambi — Dicetak {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>
