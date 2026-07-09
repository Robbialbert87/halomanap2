<div class="block md:hidden mb-4" id="mobile-ticket-list">
    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-white/30 divide-y divide-gray-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.5) 100%);">
    @forelse($tickets as $ticket)
    @php
        $statusStyle = $statusMap[$ticket->status] ?? ['label' => $ticket->status, 'class' => 'bg-gray-100 text-gray-700'];

        $typeBar = match($ticket->type) {
            'Pengaduan' => 'bg-red-500',
            'Survei', 'Saran' => 'bg-emerald-500',
            'Apresiasi' => 'bg-blue-500',
            'Informasi' => 'bg-orange-500',
            default => 'bg-gray-400',
        };
        $statusDot = match($ticket->status) {
            'NEW' => 'bg-yellow-500',
            'TERVERIFIKASI' => 'bg-cyan-500',
            'IN_PROGRESS', 'Diproses' => 'bg-blue-500',
            'DONE', 'Selesai' => 'bg-emerald-500',
            'REJECTED' => 'bg-red-500',
            'Menunggu Verifikasi' => 'bg-purple-500',
            default => 'bg-gray-400',
        };
    @endphp
    <div class="flex items-stretch cursor-pointer active:bg-gray-50 transition-colors" onclick="window.location='{{ route('admin.tickets.show', $ticket->id) }}'">
        <div class="w-1 shrink-0 {{ $typeBar }}"></div>
        <div class="flex-1 min-w-0 pl-2.5 pr-3 py-2.5">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-1.5 min-w-0 flex-1">
                    <span class="text-[10px] font-mono font-bold text-gray-500 truncate">{{ $ticket->ticket_number }}</span>
                    <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }} shrink-0"></span>
                </div>
                <span class="text-[10px] text-gray-400 whitespace-nowrap shrink-0">{{ $ticket->created_at->format('d M Y') }}</span>
            </div>
            <h3 class="text-[13px] font-semibold text-gray-900 leading-snug mt-0.5 line-clamp-1">{{ $ticket->title }}</h3>
            <div class="flex items-center gap-2 mt-0.5">
                <span class="text-[11px] text-gray-500 truncate">
                    @if($ticket->is_anonymous)
                        Anonim
                    @else
                        {{ $ticket->reporter_name }}
                    @endif
                    · {{ $ticket->category->name ?? '-' }}
                </span>
            </div>
        </div>
        <div class="flex flex-col items-center justify-center pr-2.5 gap-1 shrink-0">
            <button onclick="event.stopPropagation(); confirmDelete('{{ $ticket->id }}', '{{ $ticket->ticket_number }}')" class="text-gray-300 hover:text-red-500 transition-colors p-1">
                <i class="fa-solid fa-trash-can text-[10px]"></i>
            </button>
        </div>
    </div>
    @empty
    <div class="text-center py-10 text-gray-400">
        <i class="fa-regular fa-folder-open text-3xl mb-2 block"></i>
        <span class="text-sm">Belum ada data pengaduan.</span>
    </div>
    @endforelse
    </div>
</div>
