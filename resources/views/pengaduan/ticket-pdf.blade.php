<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tiket Pengaduan - {{ $ticket->ticket_number }}</title>
    <style>
        @page { margin: 0; }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', 'Arial', sans-serif;
            background: #f0f4f8;
            color: #1e293b;
        }
        .ticket {
            width: 380px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.12);
        }
        .ticket-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            padding: 28px 24px 20px;
            text-align: center;
            color: white;
            position: relative;
        }
        .ticket-header::after {
            content: '';
            position: absolute;
            bottom: -14px;
            left: 0;
            right: 0;
            height: 28px;
            background: radial-gradient(circle at 14px 0, transparent 14px, #ffffff 14px);
            background-size: 28px 28px;
            background-repeat: repeat-x;
            background-position: 0 0;
        }
        .ticket-header .logo {
            width: 56px;
            height: 56px;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .ticket-header .logo img {
            width: 56px;
            height: 56px;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }
        .ticket-header h1 {
            font-size: 14px;
            font-weight: 700;
            margin: 0 0 2px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .ticket-header .sub {
            font-size: 9px;
            opacity: 0.8;
            margin: 0;
        }
        .ticket-header .type-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 20px;
            padding: 3px 14px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 8px;
        }
        .ticket-body {
            padding: 28px 24px 20px;
        }
        .ticket-number-box {
            background: #eff6ff;
            border: 2px dashed #bfdbfe;
            border-radius: 12px;
            padding: 16px 12px;
            text-align: center;
            margin-bottom: 20px;
        }
        .ticket-number-box .ticket-logo {
            width: 40px;
            height: 40px;
            margin: 0 auto 6px;
            display: block;
        }
        .ticket-number-box .label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #3b82f6;
            margin-bottom: 6px;
        }
        .ticket-number-box .number {
            font-size: 26px;
            font-weight: 900;
            font-family: 'Courier New', monospace;
            color: #1e3a8a;
            letter-spacing: 2px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 7px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 11px;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-row .label {
            color: #64748b;
            font-weight: 500;
            min-width: 80px;
        }
        .info-row .value {
            color: #1e293b;
            font-weight: 600;
            text-align: right;
            flex: 1;
        }
        .divider {
            border-top: 2px dashed #e2e8f0;
            margin: 14px 0;
        }
        .title-section {
            margin-bottom: 10px;
        }
        .title-section .label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 4px;
        }
        .title-section .value {
            font-size: 12px;
            font-weight: 600;
            color: #0f172a;
            line-height: 1.4;
        }
        .footer {
            text-align: center;
            padding: 10px 24px 20px;
            font-size: 8px;
            color: #94a3b8;
            line-height: 1.5;
        }
        .footer .highlight {
            color: #3b82f6;
            font-weight: 600;
        }
        .stub {
            background: #f8fafc;
            padding: 16px 24px;
            border-top: 2px dashed #cbd5e1;
            position: relative;
        }
        .stub::before,
        .stub::after {
            content: '';
            position: absolute;
            top: -13px;
            width: 26px;
            height: 26px;
            background: #f0f4f8;
            border-radius: 50%;
        }
        .stub::before { left: -13px; }
        .stub::after { right: -13px; }
        .stub p {
            margin: 0;
            font-size: 8px;
            color: #64748b;
            text-align: center;
            line-height: 1.5;
        }
        .stub .track-url {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            color: #1e3a8a;
            font-weight: 600;
        }
        .stub .barcode {
            text-align: center;
            margin-bottom: 6px;
        }
        .stub .barcode .bars {
            font-family: 'Courier New', monospace;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 3px;
            color: #0f172a;
            line-height: 1;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-header">
            <div class="logo">
                <img src="{{ public_path('assets/images/halomanaplogo.png') }}" alt="Halo MANAP">
            </div>
            <h1>Halo MANAP</h1>
            <p class="sub">RSUD H. Abdul Manap Kota Jambi</p>
            <span class="type-badge">{{ $ticket->type }}</span>
        </div>

        <div class="ticket-body">
            <div class="ticket-number-box">
                <img class="ticket-logo" src="{{ public_path('assets/images/halomanaplogo.png') }}" alt="Halo MANAP">
                <div class="label">Nomor Tiket</div>
                <div class="number">{{ $ticket->ticket_number }}</div>
            </div>

            <div class="title-section">
                <div class="label">Judul Laporan</div>
                <div class="value">{{ $ticket->title }}</div>
            </div>

            <div class="divider"></div>

            <div class="info-row">
                <span class="label">Tanggal</span>
                <span class="value">{{ \Carbon\Carbon::parse($ticket->created_at)->isoFormat('DD MMMM YYYY') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Waktu</span>
                <span class="value">{{ \Carbon\Carbon::parse($ticket->created_at)->isoFormat('HH:mm [WIB]') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Status</span>
                <span class="value" style="color: #10b981;">{{ $ticket->status }}</span>
            </div>
            @if($ticket->room && $ticket->room->unit)
            <div class="info-row">
                <span class="label">Unit</span>
                <span class="value">{{ $ticket->room->unit->nama }}</span>
            </div>
            @endif
            @if($ticket->category)
            <div class="info-row">
                <span class="label">Kategori</span>
                <span class="value">{{ $ticket->category->name }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="label">Pelapor</span>
                <span class="value">{{ $ticket->is_anonymous ? 'Anonim' : $ticket->reporter_name }}</span>
            </div>
            @if(!$ticket->is_anonymous && $ticket->reporter_phone)
            <div class="info-row">
                <span class="label">No. HP</span>
                <span class="value">{{ $ticket->reporter_phone }}</span>
            </div>
            @endif
        </div>

        <div class="stub">
            <div class="barcode">
                <div class="bars">{{ $ticket->ticket_number }}</div>
            </div>
            <p>
                Simpan tiket ini untuk melacak status pengaduan Anda.<br>
                Lacak di: <span class="track-url">{{ config('app.url') }}/lacak?ticket_number={{ $ticket->ticket_number }}</span>
            </p>
        </div>

        <div class="footer">
            <span class="highlight">"Melayani Dengan Setulus Hati"</span><br>
            RSUD H. Abdul Manap Kota Jambi &bull; Sistem Informasi Pengaduan, Aspirasi dan Informasi Pelayanan<br>
            Dokumen ini sah dan diproses secara elektronik
        </div>
    </div>
</body>
</html>
