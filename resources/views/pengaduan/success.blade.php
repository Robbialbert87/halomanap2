@extends('layouts.app')

@section('title', 'Pengaduan Berhasil - Halo MANAP')

@section('content')
<div class="bg-[#F3F4F6] min-h-screen flex items-center justify-center p-4">
    <div class="max-w-xl w-full">

        {{-- HIDDEN TICKET VISUAL --}}
        <div id="ticket-visual" style="position:absolute;left:-9999px;top:0;width:380px;background:#fff;font-family:'Roboto',Arial,sans-serif;color:#1e293b;">
            <div style="padding:24px;">
                <div style="background:#eff6ff;border:2px dashed #bfdbfe;border-radius:14px;padding:18px 14px;text-align:center;margin-bottom:22px;">
                    <img src="{{ asset('assets/images/halomanaplogo.png') }}" alt="Logo" style="width:44px;height:44px;margin:0 auto 8px;display:block;object-fit:contain;">
                    <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:#3b82f6;margin-bottom:6px;">Nomor Tiket</div>
                    <div style="font-size:28px;font-weight:900;font-family:'Courier New',monospace;color:#1e3a8a;letter-spacing:2px;">{{ $ticket->ticket_number }}</div>
                </div>

                <div style="margin-bottom:12px;">
                    <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#64748b;margin-bottom:4px;">Judul Laporan</div>
                    <div style="font-size:13px;font-weight:600;color:#0f172a;line-height:1.4;">{{ $ticket->title }}</div>
                </div>

                <div style="border-top:2px dashed #e2e8f0;margin:14px 0;"></div>

                <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f1f5f9;font-size:11px;">
                    <span style="color:#64748b;font-weight:500;">Tanggal</span>
                    <span style="color:#1e293b;font-weight:600;">{{ \Carbon\Carbon::parse($ticket->created_at)->isoFormat('DD MMMM YYYY') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f1f5f9;font-size:11px;">
                    <span style="color:#64748b;font-weight:500;">Waktu</span>
                    <span style="color:#1e293b;font-weight:600;">{{ \Carbon\Carbon::parse($ticket->created_at)->isoFormat('HH:mm [WIB]') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f1f5f9;font-size:11px;">
                    <span style="color:#64748b;font-weight:500;">Status</span>
                    <span style="color:#10b981;font-weight:600;">{{ $ticket->status }}</span>
                </div>
                @if($ticket->room && $ticket->room->unit)
                <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f1f5f9;font-size:11px;">
                    <span style="color:#64748b;font-weight:500;">Unit</span>
                    <span style="color:#1e293b;font-weight:600;">{{ $ticket->room->unit->nama }}</span>
                </div>
                @endif
                @if($ticket->category)
                <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f1f5f9;font-size:11px;">
                    <span style="color:#64748b;font-weight:500;">Kategori</span>
                    <span style="color:#1e293b;font-weight:600;">{{ $ticket->category->name }}</span>
                </div>
                @endif
                <div style="display:flex;justify-content:space-between;padding:7px 0;font-size:11px;">
                    <span style="color:#64748b;font-weight:500;">Pelapor</span>
                    <span style="color:#1e293b;font-weight:600;">{{ $ticket->is_anonymous ? 'Anonim' : $ticket->reporter_name }}</span>
                </div>
                @if(!$ticket->is_anonymous && $ticket->reporter_phone)
                <div style="display:flex;justify-content:space-between;padding:7px 0;font-size:11px;">
                    <span style="color:#64748b;font-weight:500;">No. HP</span>
                    <span style="color:#1e293b;font-weight:600;">{{ $ticket->reporter_phone }}</span>
                </div>
                @endif
            </div>

            <div style="background:#f8fafc;padding:16px 24px;border-top:2px dashed #cbd5e1;text-align:center;">
                <div style="font-family:'Courier New',monospace;font-size:24px;font-weight:bold;letter-spacing:3px;color:#0f172a;line-height:1;margin-bottom:8px;">{{ $ticket->ticket_number }}</div>
                <p style="margin:0;font-size:8px;color:#64748b;line-height:1.5;">
                    Simpan tiket ini untuk melacak status pengaduan Anda.<br>
                    Lacak di: <span style="font-family:'Courier New',monospace;font-size:10px;color:#1e3a8a;font-weight:600;">{{ config('app.url') }}/lacak?ticket_number={{ $ticket->ticket_number }}</span>
                </p>
            </div>

            <div style="text-align:center;padding:12px 24px 18px;font-size:8px;color:#94a3b8;line-height:1.5;">
                <span style="color:#3b82f6;font-weight:600;">"Melayani Dengan Setulus Hati"</span><br>
                RSUD H. Abdul Manap Kota Jambi &bull; Sistem Informasi Pengaduan<br>
                Dokumen ini sah dan diproses secara elektronik
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-sm border border-white/30 p-8 md:p-12 text-center" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">

            <!-- Success Icon -->
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center mx-auto mb-5 shadow-md shadow-green-200/50">
                <i class="fa-solid fa-check text-4xl text-white"></i>
            </div>

            <h1 class="font-heading text-2xl md:text-3xl font-bold text-gray-800 mb-3">
                @if($ticket->type === 'Pengaduan')
                    Pengaduan Berhasil Dikirim
                @elseif($ticket->type === 'Apresiasi')
                    Apresiasi Berhasil Dikirim
                @elseif($ticket->type === 'Informasi')
                    Permintaan Informasi Berhasil Dikirim
                @else
                    Laporan Berhasil Dikirim
                @endif
            </h1>

            <p class="text-gray-500 text-sm mb-6 leading-relaxed">
                Terima kasih atas partisipasi Anda dalam membantu meningkatkan kualitas pelayanan RSUD H. Abdul Manap Kota Jambi.
            </p>

            <div class="bg-blue-50/80 backdrop-blur-sm border border-blue-100 rounded-2xl p-6 mb-6 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 text-blue-100">
                    <i class="fa-solid fa-ticket text-8xl"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs text-blue-600 font-semibold mb-2 uppercase tracking-widest">Nomor Tiket Anda</p>
                    <span id="ticket_number" class="text-3xl md:text-4xl font-black text-blue-900 font-mono tracking-wider">
                        {{ $ticket->ticket_number }}
                    </span>
                    <p class="text-[11px] text-blue-500 mt-3">
                        Simpan nomor tiket ini untuk melacak status pengaduan Anda.
                    </p>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="downloadTicketJpg()"
                        class="bg-gradient-to-br from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-semibold rounded-xl text-sm px-5 py-3.5 transition-all shadow-sm shadow-blue-200/50 flex items-center justify-center gap-2 active:scale-[0.98]">
                        <i class="fa-solid fa-download"></i> Download Tiket
                    </button>
                    <button onclick="copyTicket()"
                        class="bg-white/80 backdrop-blur-sm border border-gray-200 hover:border-blue-300 text-gray-700 font-semibold rounded-xl text-sm px-5 py-3.5 transition-all flex items-center justify-center gap-2 active:scale-[0.98]">
                        <i class="fa-regular fa-copy text-blue-500"></i> Salin Tiket
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('pengaduan.track', ['ticket_number' => $ticket->ticket_number]) }}"
                        class="bg-white/80 backdrop-blur-sm border border-gray-200 hover:border-blue-300 text-gray-700 font-semibold rounded-xl text-sm px-5 py-3.5 transition-all flex items-center justify-center gap-2 active:scale-[0.98]">
                        <i class="fa-solid fa-magnifying-glass text-blue-500"></i> Lacak Status
                    </a>
                    <a href="/"
                        class="bg-white/80 backdrop-blur-sm border border-gray-200 hover:border-blue-300 text-gray-700 font-semibold rounded-xl text-sm px-5 py-3.5 transition-all flex items-center justify-center gap-2 active:scale-[0.98]">
                        <i class="fa-solid fa-house text-gray-500"></i> Beranda
                    </a>
                </div>
            </div>

        </div>

        @if($ticket->type === 'Pengaduan')
        <div class="mt-4 text-center">
            <p class="text-xs text-gray-400">
                Laporan Anda akan diproses dalam 1x24 jam kerja. <br>
                Pantau terus status melalui nomor tiket Anda.
            </p>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyTicket() {
        var ticketText = document.getElementById('ticket_number').innerText;
        navigator.clipboard.writeText(ticketText).then(function() {
            var btn = document.querySelector('button[onclick="copyTicket()"]');
            var original = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Tersalin!';
            btn.classList.add('!border-green-400', '!text-green-600');
            setTimeout(function() {
                btn.innerHTML = original;
                btn.classList.remove('!border-green-400', '!text-green-600');
            }, 2000);
        }).catch(function() {});
    }

    function downloadTicketJpg() {
        var el = document.getElementById('ticket-visual');
        var btn = document.querySelector('button[onclick="downloadTicketJpg()"]');
        var original = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
        btn.disabled = true;

        html2canvas(el, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#ffffff',
            logging: false
        }).then(function(canvas) {
            var link = document.createElement('a');
            link.download = 'tiket-{{ $ticket->ticket_number }}.jpg';
            link.href = canvas.toDataURL('image/jpeg', 0.92);
            link.click();
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Tersimpan!';
            setTimeout(function() {
                btn.innerHTML = original;
                btn.disabled = false;
            }, 2000);
        }).catch(function() {
            btn.innerHTML = original;
            btn.disabled = false;
            alert('Gagal mengunduh gambar. Silakan coba lagi.');
        });
    }
</script>
@endpush
