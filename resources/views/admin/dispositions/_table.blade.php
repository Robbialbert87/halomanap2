{{-- Statistik Cards (di-refresh bersama filter) --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-4 md:mb-6">
    <div class="admin-card p-3 md:p-4">
        <p class="text-[10px] md:text-xs text-gray-500 font-semibold tracking-wide">Total</p>
        <p class="text-lg md:text-2xl font-bold text-gray-800 mt-0.5">{{ $statTotal }}</p>
    </div>
    <div class="admin-card p-3 md:p-4">
        <p class="text-[10px] md:text-xs text-gray-500 font-semibold tracking-wide">Menunggu</p>
        <p class="text-lg md:text-2xl font-bold text-yellow-600 mt-0.5">{{ $statMenunggu }}</p>
    </div>
    <div class="admin-card p-3 md:p-4">
        <p class="text-[10px] md:text-xs text-gray-500 font-semibold tracking-wide">Diproses</p>
        <p class="text-lg md:text-2xl font-bold text-indigo-600 mt-0.5">{{ $statProses }}</p>
    </div>
    <div class="admin-card p-3 md:p-4">
        <p class="text-[10px] md:text-xs text-gray-500 font-semibold tracking-wide">Terlambat</p>
        <p class="text-lg md:text-2xl font-bold {{ $statTerlambat > 0 ? 'text-red-600' : 'text-gray-800' }} mt-0.5">{{ $statTerlambat }}</p>
    </div>
</div>

{{-- Mobile: Disposition List --}}
<div class="block md:hidden mb-4">
    <div class="admin-card overflow-hidden divide-y divide-gray-100">
    @forelse($workflows as $wf)
    @php
        $isOverdue = $wf->due_at && $wf->due_at->isPast() && !in_array($wf->status, ['selesai','ditutup','menunggu_verifikasi']);
    @endphp
    <div class="flex items-stretch cursor-pointer active:bg-gray-50 transition-colors" onclick="window.location='{{ route('admin.tickets.show', $wf->ticket_id) }}'">
        <div class="w-1 shrink-0 {{ $isOverdue ? 'bg-red-500' : 'bg-blue-500' }}"></div>
        <div class="flex-1 min-w-0 pl-2.5 pr-3 py-2.5">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-1.5 min-w-0 flex-1">
                    <span class="text-[10px] font-mono font-bold text-blue-600 truncate">{{ $wf->ticket?->ticket_number ?? '-' }}</span>
                    @if($isOverdue)
                        <span class="text-red-500"><i class="fa-solid fa-exclamation-circle text-[10px]"></i></span>
                    @endif
                </div>
                <span class="text-[10px] text-gray-400 whitespace-nowrap shrink-0">{{ $wf->created_at->format('d/m/Y') }}</span>
            </div>
            <h3 class="text-[13px] font-semibold text-gray-900 leading-snug mt-0.5">{{ $wf->ticket?->title ?? '-' }}</h3>
            <div class="flex items-center gap-2 mt-0.5">
                <span class="text-[11px] text-gray-500 truncate">
                    {{ $wf->toUnit?->nama ?? '-' }} · {{ $wf->toUser?->nama ?? '-' }}
                </span>
                <span class="inline-block px-1.5 py-0.5 text-[10px] font-semibold rounded {{ $wf->action_badge['class'] }}">{{ $wf->action_badge['label'] }}</span>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-10 text-gray-400">
        <i class="fa-solid fa-arrow-right-arrow-left text-3xl mb-2 block"></i>
        <span class="text-sm">Belum ada disposisi.</span>
    </div>
    @endforelse
    </div>
</div>

{{-- Table (Desktop) --}}
<div class="hidden md:block admin-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="admin-table w-full text-sm">
            <thead>
                <tr>
                    <th class="px-4 py-3">Tiket</th>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3 hidden lg:table-cell">Unit</th>
                    <th class="px-4 py-3 hidden lg:table-cell">Penerima</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Tenggat</th>
                    <th class="px-4 py-3 hidden md:table-cell">Tgl</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($workflows as $wf)
                @php
                    $isOverdue = $wf->due_at && $wf->due_at->isPast() && !in_array($wf->status, ['selesai','ditutup','menunggu_verifikasi']);
                @endphp
                <tr class="{{ $isOverdue ? 'bg-red-50' : 'hover:bg-gray-50' }}">
                    <td class="px-4 py-3 font-mono text-xs font-bold text-blue-600">{{ $wf->ticket?->ticket_number ?? '-' }}</td>
                    <td class="px-4 py-3 max-w-[260px] font-medium text-gray-800">{{ $wf->ticket?->title ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap hidden lg:table-cell">{{ $wf->toUnit?->nama ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap hidden lg:table-cell">
                        {{ $wf->toUser?->nama ?? '-' }}
                        <span class="text-xs text-gray-400 block">{{ $wf->toJabatan?->nama ?? '' }}</span>
                    </td>
                    <td class="px-4 py-3"><span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $wf->action_badge['class'] }}">{{ $wf->action_badge['label'] }}</span></td>
                    <td class="px-4 py-3 whitespace-nowrap {{ $isOverdue ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                        @if($wf->due_at)
                            {{ $wf->due_at->format('d/m/Y') }}
                            @if($isOverdue) <i class="fa-solid fa-exclamation-circle"></i> @endif
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-gray-500 hidden md:table-cell">{{ $wf->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.tickets.show', $wf->ticket_id) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium"><i class="fa-solid fa-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-arrow-right-arrow-left text-3xl mb-2"></i>
                        <p>Belum ada disposisi</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($workflows->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">{{ $workflows->links() }}</div>
    @endif
</div>

{{-- Mobile Pagination --}}
@if($workflows->hasPages())
<div class="block md:hidden mt-4">
    {{ $workflows->links() }}
</div>
@endif
