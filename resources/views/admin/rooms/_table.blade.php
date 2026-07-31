{{-- Info ringkas hasil filter (desktop) --}}
<div class="hidden md:flex items-center justify-between px-5 py-2.5 border-b border-gray-100 bg-gray-50/40">
    <p class="text-[11px] text-gray-500">
        Menampilkan <span class="font-semibold text-gray-700">{{ $rooms->firstItem() ?? 0 }}–{{ $rooms->lastItem() ?? 0 }}</span>
        dari <span class="font-semibold text-gray-700">{{ $rooms->total() }}</span> ruangan
    </p>
</div>

{{-- Mobile: Room List --}}
<div class="block md:hidden mb-4">
    <div class="admin-card overflow-hidden divide-y divide-gray-100">
    @forelse($rooms as $room)
    <div class="flex items-stretch active:bg-gray-50 transition-colors">
        <div class="w-1 shrink-0 bg-violet-500"></div>
        <div class="flex-1 min-w-0 pl-2.5 pr-3 py-2.5">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-1.5 min-w-0 flex-1">
                    <span class="text-[13px] font-semibold text-gray-900 truncate">{{ $room->name }}</span>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <a href="{{ route('admin.rooms.edit', $room->id) }}"
                       class="admin-action admin-action-sm admin-action-blue" title="Edit">
                        <i class="fa-regular fa-pen-to-square text-[10px]"></i>
                    </a>
                    <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="admin-action admin-action-sm admin-action-red" title="Hapus">
                            <i class="fa-regular fa-trash-can text-[10px]"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-1">
                <span class="inline-flex items-center gap-1 py-0.5 px-1.5 rounded-md bg-blue-50 text-blue-700 text-[10px] font-semibold border border-blue-100">
                    <i class="fa-solid fa-building text-[9px]"></i> {{ $room->unit->nama ?? 'Tanpa Unit' }}
                </span>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-10 text-gray-400">
        <i class="fa-solid fa-door-open text-3xl mb-2 block"></i>
        <span class="text-sm">Belum ada data ruangan.</span>
    </div>
    @endforelse
    </div>
</div>

{{-- Table (Desktop) --}}
<div class="hidden md:block admin-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="admin-table w-full text-left text-sm text-gray-600">
            <thead>
                <tr>
                    <th class="px-6 py-4 w-16 text-center">No</th>
                    <th class="px-6 py-4">Nama Ruangan</th>
                    <th class="px-6 py-4 hidden lg:table-cell">Unit Terkait</th>
                    <th class="px-6 py-4 w-32 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rooms as $index => $room)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 text-center">{{ $rooms->firstItem() + $index }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $room->name }}</td>
                    <td class="px-6 py-4 hidden lg:table-cell">
                        <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-md bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-100">
                            <i class="fa-solid fa-building"></i> {{ $room->unit->nama ?? 'Tanpa Unit' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('admin.rooms.edit', $room->id) }}" class="admin-action-pill admin-action-pill-blue" title="Edit">
                                <i class="fa-regular fa-pen-to-square text-[11px]"></i> Edit
                            </a>
                            <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?');" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="admin-action-pill admin-action-pill-red" title="Hapus">
                                    <i class="fa-regular fa-trash-can text-[11px]"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">Belum ada data ruangan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($rooms->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $rooms->links() }}
    </div>
    @endif
</div>

{{-- Mobile Pagination --}}
@if($rooms->hasPages())
<div class="block md:hidden mt-4">
    {{ $rooms->links() }}
</div>
@endif
