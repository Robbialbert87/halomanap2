{{-- Info ringkas hasil filter (desktop) --}}
<div class="hidden md:flex items-center justify-between px-5 py-2.5 border-b border-gray-100 bg-gray-50/40">
    <p class="text-[11px] text-gray-500">
        Menampilkan <span class="font-semibold text-gray-700">{{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }}</span>
        dari <span class="font-semibold text-gray-700">{{ $users->total() }}</span> pegawai
    </p>
</div>

{{-- Mobile: User List --}}
<div class="block md:hidden mb-4">
    <div class="admin-card overflow-hidden divide-y divide-gray-100">
    @forelse($users as $user)
    <div class="flex items-stretch active:bg-gray-50 transition-colors">
        <div class="w-1 shrink-0 bg-emerald-500"></div>
        <div class="flex-1 min-w-0 pl-2.5 pr-3 py-2.5">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-1.5 min-w-0 flex-1">
                    <span class="text-[13px] font-semibold text-gray-900">{{ $user->nama }}</span>
                    @if($user->status === 'active')
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 shrink-0"></span>
                    @elseif($user->status === 'suspended')
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0"></span>
                    @else
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 shrink-0"></span>
                    @endif
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="admin-action admin-action-sm admin-action-blue" title="Edit">
                        <i class="fa-regular fa-pen-to-square text-[10px]"></i>
                    </a>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="admin-action admin-action-sm admin-action-red" title="Hapus">
                            <i class="fa-regular fa-trash-can text-[10px]"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="text-[11px] font-mono text-blue-600 mt-0.5">{{ $user->nip }}</div>
            <div class="flex items-center gap-2 mt-0.5">
                <span class="text-[11px] text-gray-500 truncate flex items-center gap-1">
                    <i class="fa-regular fa-building text-gray-400"></i>
                    {{ $user->unit ? $user->unit->nama : '-' }}
                </span>
                <span class="text-[11px] text-gray-500 truncate">
                    · {{ $user->jabatan ? $user->jabatan->nama : '-' }}
                </span>
            </div>
            @if($user->roles->count())
            <div class="flex items-center gap-1 mt-1">
                @foreach($user->roles as $r)
                    <span class="px-1.5 py-0.5 text-[9px] font-semibold bg-indigo-50 text-indigo-700 rounded border border-indigo-100">{{ $r->name }}</span>
                @endforeach
                @if($user->status === 'active')
                    <span class="px-1.5 py-0.5 text-[9px] font-semibold bg-green-50 text-green-700 rounded">Aktif</span>
                @elseif($user->status === 'suspended')
                    <span class="px-1.5 py-0.5 text-[9px] font-semibold bg-red-50 text-red-700 rounded">Suspended</span>
                @else
                    <span class="px-1.5 py-0.5 text-[9px] font-semibold bg-gray-50 text-gray-700 rounded">Nonaktif</span>
                @endif
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="text-center py-10 text-gray-400">
        <i class="fa-solid fa-users text-3xl mb-2 block"></i>
        <span class="text-sm">Belum ada data pegawai atau pencarian tidak ditemukan.</span>
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
                    <th class="px-6 py-4">Pegawai</th>
                    <th class="px-6 py-4 hidden lg:table-cell">Kontak</th>
                    <th class="px-6 py-4">Jabatan & Unit</th>
                    <th class="px-6 py-4 text-center hidden lg:table-cell">Role</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $user->nama }}</div>
                        <div class="text-xs font-mono text-blue-600 mt-0.5">{{ $user->nip }}</div>
                    </td>
                    <td class="px-6 py-4 hidden lg:table-cell">
                        <div class="text-gray-900 flex items-center gap-1.5"><i class="fa-brands fa-whatsapp text-green-500"></i> {{ $user->phone_number ?? '-' }}</div>
                        @if($user->email)
                        <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-1.5"><i class="fa-regular fa-envelope"></i> {{ $user->email }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-gray-800 font-medium">{{ $user->jabatan ? $user->jabatan->nama : '-' }}</div>
                        <div class="text-xs text-gray-500 mt-0.5"><i class="fa-regular fa-building text-gray-400 mr-1"></i> {{ $user->unit ? $user->unit->nama : '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-center hidden lg:table-cell">
                        @foreach($user->roles as $r)
                            <span class="px-2 py-1 text-[10px] font-semibold bg-indigo-50 text-indigo-700 rounded border border-indigo-100">{{ $r->name }}</span>
                        @endforeach
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($user->status === 'active')
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                        @elseif($user->status === 'suspended')
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Suspended</span>
                        @else
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="admin-action-pill admin-action-pill-blue" title="Edit">
                                <i class="fa-regular fa-pen-to-square text-[11px]"></i> Edit
                            </a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')" class="inline">
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
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center gap-2 text-gray-400">
                            <i class="fa-solid fa-users text-3xl"></i>
                            <p>Belum ada data pegawai atau pencarian tidak ditemukan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $users->links() }}
    </div>
    @endif
</div>

{{-- Mobile Pagination --}}
@if($users->hasPages())
<div class="block md:hidden mt-4">
    {{ $users->links() }}
</div>
@endif
