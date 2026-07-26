<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
        <h2 class="font-semibold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-qrcode text-gray-500"></i> Session WhatsApp
        </h2>
        <div class="flex items-center gap-2">
            <form action="{{ route('admin.whatsapp.sessions.sync-all') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg px-4 py-2 transition-colors">
                    <i class="fa-solid fa-rotate mr-1"></i> Sync dari Server
                </button>
            </form>
            <button onclick="this.nextElementSibling.classList.toggle('hidden')" class="text-sm bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg px-4 py-2 transition-colors">
                <i class="fa-solid fa-plus mr-1"></i> Tambah Session
            </button>
        </div>
    </div>
    <div class="hidden p-6 border-b border-gray-100 bg-blue-50">
        <form method="POST" action="{{ route('admin.whatsapp.sessions.store') }}" class="space-y-3">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama Session</label>
                    <input type="text" name="session_id" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nomor Telepon</label>
                    <input type="text" name="phone_number" required placeholder="628xxx" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Webhook URL (opsional)</label>
                <input type="url" name="webhook_url" placeholder="https://example.com/webhook" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg px-4 py-2 transition-colors">
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
                            <div class="flex items-center gap-2">
                                @if(!$isOk)
                                <a href="{{ route('admin.whatsapp.sessions.show', $session->session_id) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                    <i class="fa-solid fa-qrcode mr-0.5"></i> QR
                                </a>
                                @endif
                                <form action="{{ route('admin.whatsapp.sessions.sync', $session->session_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-500 hover:text-gray-700 text-xs" title="Sync">
                                        <i class="fa-solid fa-rotate"></i>
                                    </button>
                                </form>
                                @if(($wahaConfig['session'] ?? '') !== $session->session_id)
                                <a href="{{ route('admin.whatsapp.sessions.set-default', $session->session_id) }}"
                                   class="text-emerald-600 hover:text-emerald-800 text-xs font-medium" title="Jadikan default kirim">
                                    <i class="fa-solid fa-check"></i> Default
                                </a>
                                @else
                                <span class="text-emerald-600 text-xs font-medium"><i class="fa-solid fa-check-circle"></i> Default</span>
                                @endif
                                <form action="{{ route('admin.whatsapp.sessions.disconnect', $session->session_id) }}" method="POST" class="inline" onsubmit="return confirm('Putuskan session {{ $session->session_id }}?')">
                                    @csrf
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-800 text-xs" title="Disconnect">
                                        <i class="fa-solid fa-power-off"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.whatsapp.sessions.destroy', $session->session_id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus session {{ $session->session_id }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs" title="Hapus">
                                        <i class="fa-solid fa-trash-can"></i>
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
