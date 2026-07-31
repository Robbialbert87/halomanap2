<div class="admin-card overflow-hidden">
    <div class="admin-card-head">
        <h2 class="font-semibold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-gray-500"></i> Log Gagal Kirim
        </h2>
    </div>
    @if($failedLogs->isEmpty())
        <div class="p-10 text-center">
            <div class="w-16 h-16 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                <i class="fa-solid fa-check"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Semua Terkirim</h3>
            <p class="text-gray-500 text-sm">Tidak ada notifikasi yang gagal dikirim.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="admin-table w-full text-sm">
                <thead>
                    <tr>
                        <th class="text-left px-4 py-3">Tujuan</th>
                        <th class="text-left px-4 py-3">Pesan</th>
                        <th class="text-left px-4 py-3">Gagal</th>
                        <th class="text-left px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($failedLogs as $log)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-mono text-xs">{{ $log->recipient }}</td>
                        <td class="px-4 py-3 max-w-xs truncate text-xs text-gray-600">{{ $log->message }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs text-red-600">{{ $log->failed_at ? $log->failed_at->diffForHumans() : '-' }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.whatsapp.resend-submit') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="ids[]" value="{{ $log->id }}">
                                <button type="submit" class="admin-action-pill admin-action-pill-blue" title="Kirim ulang pesan">
                                    <i class="fa-solid fa-rotate-left text-[11px]"></i> Kirim Ulang
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($failedLogs instanceof \Illuminate\Pagination\LengthAwarePaginator && $failedLogs->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $failedLogs->links() }}
            </div>
            @endif
        </div>
    @endif
</div>
