<div class="admin-card overflow-hidden">
    <div class="admin-card-head flex items-center justify-between">
        <h2 class="font-semibold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-signal text-gray-500"></i> Status Koneksi WAHA
        </h2>
        <span id="api-status-badge" class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-600">
            <i class="fa-solid fa-spinner fa-spin mr-1"></i> Memeriksa...
        </span>
    </div>
    <div class="p-6 flex flex-col items-center justify-center min-h-[200px]" id="status-panel">
        <div id="status-loading" class="text-center py-8">
            <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto mb-3"></div>
            <p class="text-sm text-gray-500">Memeriksa koneksi...</p>
        </div>
    </div>
</div>
