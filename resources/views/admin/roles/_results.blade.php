{{-- Roles Table (semua breakpoint — mobile scroll horizontal, desktop lega) --}}
<div class="admin-card overflow-hidden">
    <div class="admin-card-head">
        <h2 class="font-semibold text-gray-800 text-sm">Daftar Semua Role</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="admin-table w-full text-left text-sm text-gray-600">
            <thead>
                <tr>
                    <th class="px-4 md:px-6 py-4">Role</th>
                    <th class="px-4 md:px-6 py-4 hidden sm:table-cell">Deskripsi</th>
                    <th class="px-4 md:px-6 py-4 text-center">User</th>
                    <th class="px-4 md:px-6 py-4 hidden sm:table-cell">Status</th>
                    <th class="px-4 md:px-6 py-4 hidden md:table-cell">Dibuat</th>
                    <th class="px-4 md:px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($roles as $role)
                @php
                    $colors = [
                        'Super Admin' => ['badge' => 'bg-purple-50 text-purple-700'],
                        'Admin'       => ['badge' => 'bg-blue-50 text-blue-700'],
                        'Kepala Unit' => ['badge' => 'bg-amber-50 text-amber-700'],
                        'Staff Unit'  => ['badge' => 'bg-green-50 text-green-700'],
                    ];
                    $c = $colors[$role->name] ?? ['badge' => 'bg-gray-100 text-gray-700'];
                @endphp
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-4 md:px-6 py-4 align-top">
                        <p class="font-medium text-gray-900">{{ $role->name }}</p>
                        <p class="font-mono text-[11px] text-blue-600 mt-0.5">{{ $role->kode ?? '-' }}</p>
                    </td>
                    <td class="px-4 md:px-6 py-4 align-top max-w-[320px] hidden sm:table-cell">
                        <p class="text-xs text-gray-500 leading-snug">{{ $role->deskripsi ?? '-' }}</p>
                    </td>
                    <td class="px-4 md:px-6 py-4 text-center align-top">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $c['badge'] }}">
                            <i class="fa-solid fa-users text-[10px]"></i> {{ $role->users_count }}
                        </span>
                    </td>
                    <td class="px-4 md:px-6 py-4 align-top hidden sm:table-cell">
                        @if($role->status === 'active')
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-green-700">
                                <i class="fa-solid fa-circle text-[8px] text-green-500"></i> Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-400">
                                <i class="fa-solid fa-circle text-[8px] text-gray-300"></i> Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="px-4 md:px-6 py-4 text-gray-400 text-xs align-top hidden md:table-cell">
                        {{ $role->created_at->format('d M Y') }}
                    </td>
                    <td class="px-4 md:px-6 py-4 align-top">
                        <div class="flex items-center justify-end gap-1.5 flex-wrap">
                            <a href="{{ route('admin.roles.edit', $role->id) }}"
                               class="admin-action-pill admin-action-pill-blue" title="Edit">
                                <i class="fa-regular fa-pen-to-square text-[11px]"></i> Edit
                            </a>
                            <a href="{{ route('admin.roles.edit', $role->id) }}#permissions"
                               class="admin-action-pill admin-action-pill-slate hidden md:inline-flex" title="Atur Permission">
                                <i class="fa-solid fa-key text-[11px]"></i> Permission
                            </a>
                            @if($role->name !== 'Super Admin')
                            <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus role {{ $role->name }}?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="admin-action-pill admin-action-pill-red" title="Hapus">
                                    <i class="fa-regular fa-trash-can text-[11px]"></i> Hapus
                                </button>
                            </form>
                            @else
                            <span class="admin-action admin-action-slate cursor-default" title="Dilindungi"><i class="fa-solid fa-lock text-xs"></i></span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 md:px-6 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-shield-halved text-3xl mb-2 block"></i>
                        <span class="text-sm">Tidak ada role yang cocok dengan pencarian.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
