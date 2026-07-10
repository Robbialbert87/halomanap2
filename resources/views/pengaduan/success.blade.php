@extends('layouts.app')

@section('title', 'Pengaduan Berhasil - Halo MANAP')

@section('content')
<div class="bg-[#F3F4F6] min-h-screen flex items-center justify-center p-4">
    <div class="max-w-xl w-full">

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
                    <a href="{{ route('pengaduan.ticket-download', ['ticket_number' => $ticket->ticket_number]) }}"
                        class="bg-gradient-to-br from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-semibold rounded-xl text-sm px-5 py-3.5 transition-all shadow-sm shadow-blue-200/50 flex items-center justify-center gap-2 active:scale-[0.98]">
                        <i class="fa-solid fa-download"></i> Download Tiket
                    </a>
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
        const ticketText = document.getElementById('ticket_number').innerText;
        navigator.clipboard.writeText(ticketText).then(() => {
            // Visual feedback
            const btn = document.querySelector('button[onclick="copyTicket()"]');
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Tersalin!';
            btn.classList.add('!border-green-400', '!text-green-600');
            setTimeout(() => {
                btn.innerHTML = original;
                btn.classList.remove('!border-green-400', '!text-green-600');
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy text: ', err);
        });
    }
</script>
@endpush
