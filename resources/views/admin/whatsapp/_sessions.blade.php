<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
        <h2 class="font-semibold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-qrcode text-gray-500"></i> Session WhatsApp
        </h2>
        <div class="flex items-center gap-2">
            <form action="{{ route('admin.whatsapp.sessions.sync-all') }}" method="POST">
                @csrf
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg text-sm px-4 py-2.5 transition-colors">
                    <i class="fa-solid fa-rotate mr-1"></i> Sync dari Server
                </button>
            </form>
            <button onclick="this.parentElement.parentElement.nextElementSibling.classList.toggle('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-4 py-2.5 transition-colors">
                <i class="fa-solid fa-plus mr-1"></i> Tambah Session
            </button>
        </div>
    </div>
    <div class="hidden p-6 border-b border-gray-100">
        <form method="POST" action="{{ route('admin.whatsapp.sessions.store') }}" class="space-y-3">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Session</label>
                    <input type="text" name="session_id" required class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="text" name="phone_number" required placeholder="628xxx" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Webhook URL (opsional)</label>
                <input type="url" name="webhook_url" placeholder="https://example.com/webhook" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-4 py-2.5 transition-colors">
                <i class="fa-solid fa-play mr-1"></i> Buat & Mulai Session
            </button>
        </form>
    </div>
    @if($sessions->isEmpty())
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Belum Ada Session</h3>
            <p class="text-gray-500 text-sm">Tambahkan session WhatsApp untuk mengirim notifikasi.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                        <th class="text-left px-4 py-3">Session</th>
                        <th class="text-left px-4 py-3">Nomor</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-left px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($sessions as $session)
                    @php $isConnected = $session->status === 'CONNECTED'; $isOk = $isConnected || $session->status === 'WORKING'; $isWait = $session->status === 'scanning'; @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium">{{ $session->session_id }}</td>
                        <td class="px-4 py-3 font-mono text-xs">{{ $session->phone_number ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium {{ $isOk ? 'bg-green-100 text-green-700' : ($isWait ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $isOk ? 'bg-green-500' : ($isWait ? 'bg-yellow-500' : 'bg-red-500') }}"></span>
                                {{ $session->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1.5">
                                @if(!$isOk)
                                <a href="{{ route('admin.whatsapp.sessions.show', $session->session_id) }}" class="admin-action-pill admin-action-pill-blue" title="Lihat QR code">
                                    <i class="fa-solid fa-qrcode text-[11px]"></i> QR
                                </a>
                                @endif
                                <form action="{{ route('admin.whatsapp.sessions.sync', $session->session_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="admin-action-pill admin-action-pill-emerald" title="Sync">
                                        <i class="fa-solid fa-rotate text-[11px]"></i> Sync
                                    </button>
                                </form>
                                @if(($wahaConfig['session'] ?? '') !== $session->session_id)
                                <a href="{{ route('admin.whatsapp.sessions.set-default', $session->session_id) }}"
                                   class="admin-action-pill admin-action-pill-emerald" title="Jadikan default kirim">
                                    <i class="fa-solid fa-check text-[11px]"></i> Default
                                </a>
                                @else
                                <span class="admin-action-pill admin-action-pill-emerald cursor-default" title="Session default"><i class="fa-solid fa-check-circle text-[11px]"></i> Default</span>
                                @endif
                                <form action="{{ route('admin.whatsapp.sessions.disconnect', $session->session_id) }}" method="POST" class="inline" onsubmit="return confirm('Putuskan session {{ $session->session_id }}?')">
                                    @csrf
                                    <button type="submit" class="admin-action-pill admin-action-pill-red" title="Disconnect">
                                        <i class="fa-solid fa-power-off text-[11px]"></i> Putus
                                    </button>
                                </form>
                                <form action="{{ route('admin.whatsapp.sessions.destroy', $session->session_id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus session {{ $session->session_id }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="admin-action-pill admin-action-pill-red" title="Hapus">
                                        <i class="fa-solid fa-trash-can text-[11px]"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
