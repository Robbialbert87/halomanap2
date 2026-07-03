@extends('layouts.app')

@section('title', 'Pengaduan Berhasil - Halo MANAP')

@section('content')
<div class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-xl w-full bg-white rounded-3xl shadow-xl shadow-blue-900/5 p-8 md:p-12 text-center border border-gray-100">
        
        <!-- Success Icon -->
        <div class="w-24 h-24 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-check text-5xl text-green-500"></i>
        </div>

        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">Pengaduan Berhasil Dikirim</h1>
        
        <p class="text-gray-600 mb-8 leading-relaxed">
            Terima kasih atas partisipasi Anda dalam membantu meningkatkan kualitas pelayanan RSUD H. Abdul Manap Kota Jambi.
        </p>

        <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 mb-8 relative overflow-hidden">
            <!-- Decorative background shape -->
            <div class="absolute -right-6 -top-6 text-blue-200/50">
                <i class="fa-solid fa-ticket text-8xl"></i>
            </div>
            
            <div class="relative z-10">
                <p class="text-sm text-blue-600 font-semibold mb-2 uppercase tracking-widest">Nomor Tiket Anda</p>
                <div class="flex items-center justify-center gap-3">
                    <span id="ticket_number" class="text-3xl md:text-4xl font-black text-blue-900 font-mono tracking-wider">{{ $ticket->ticket_number }}</span>
                </div>
                <p class="text-xs text-blue-500 mt-3">Simpan nomor tiket ini untuk melacak status pengaduan Anda.</p>
            </div>
        </div>

        <div class="flex flex-col gap-3">
            <button onclick="copyTicket()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl text-sm px-5 py-3.5 transition-colors shadow-sm flex justify-center items-center gap-2">
                <i class="fa-regular fa-copy"></i> Salin Nomor Tiket
            </button>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('pengaduan.track', ['ticket_number' => $ticket->ticket_number]) }}" class="w-full bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-xl text-sm px-5 py-3.5 transition-colors flex justify-center items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass"></i> Lacak Status
                </a>
                <a href="/" class="w-full bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-xl text-sm px-5 py-3.5 transition-colors flex justify-center items-center gap-2">
                    <i class="fa-solid fa-house"></i> Beranda
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function copyTicket() {
        const ticketText = document.getElementById('ticket_number').innerText;
        navigator.clipboard.writeText(ticketText).then(() => {
            alert('Nomor tiket berhasil disalin: ' + ticketText);
        }).catch(err => {
            console.error('Failed to copy text: ', err);
        });
    }
</script>
@endpush
