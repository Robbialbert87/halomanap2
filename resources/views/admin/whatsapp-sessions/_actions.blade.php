<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi</h2>
    <div class="space-y-2">
        <form action="{{ route('admin.whatsapp-sessions.disconnect', $session->session_id) }}" method="POST">
            @csrf
            <button type="submit" class="w-full px-4 py-2.5 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors" onclick="return confirm('Putuskan session ini?')">
                <i class="fa-solid fa-plug mr-1.5"></i> Putuskan Session
            </button>
        </form>
        <form action="{{ route('admin.whatsapp-sessions.destroy', $session->session_id) }}" method="POST">
            @csrf @method('DELETE')
            <button type="submit" class="w-full px-4 py-2.5 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors" onclick="return confirm('Hapus session secara permanen?')">
                <i class="fa-solid fa-trash-can mr-1.5"></i> Hapus Session
            </button>
        </form>
    </div>
</div>
