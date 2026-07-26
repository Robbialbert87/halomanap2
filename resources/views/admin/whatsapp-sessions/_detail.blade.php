<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Detail Session</h2>
    <dl class="space-y-3 text-sm">
        <div>
            <dt class="text-gray-400 text-xs uppercase tracking-wider">Session ID</dt>
            <dd class="text-gray-800 font-medium mt-0.5">{{ $session->session_id }}</dd>
        </div>
        <div>
            <dt class="text-gray-400 text-xs uppercase tracking-wider">Nomor WhatsApp</dt>
            <dd class="text-gray-800 font-medium mt-0.5">{{ $session->phone_number }}</dd>
        </div>
        <div>
            <dt class="text-gray-400 text-xs uppercase tracking-wider">Status</dt>
            <dd class="mt-0.5">
                <span id="status-badge" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium
                    @if($session->status === 'CONNECTED') bg-green-100 text-green-700
                    @elseif(in_array($session->status, ['scanning', 'NOT_CONNECTED'])) bg-yellow-100 text-yellow-700
                    @elseif($session->status === 'creating' || $session->status === 'STARTING') bg-blue-100 text-blue-700
                    @else bg-red-100 text-red-700 @endif">
                    {{ $statusLabels[$session->status] ?? $session->status }}
                </span>
            </dd>
        </div>
        <div>
            <dt class="text-gray-400 text-xs uppercase tracking-wider">User</dt>
            <dd class="text-gray-800 font-medium mt-0.5">{{ $session->user->name }}</dd>
        </div>
        <div>
            <dt class="text-gray-400 text-xs uppercase tracking-wider">Dibuat</dt>
            <dd class="text-gray-800 mt-0.5">{{ $session->created_at->format('d M Y H:i') }}</dd>
        </div>
        @if($session->connected_at)
        <div>
            <dt class="text-gray-400 text-xs uppercase tracking-wider">Terhubung</dt>
            <dd class="text-gray-800 mt-0.5">{{ $session->connected_at->format('d M Y H:i') }}</dd>
        </div>
        @endif
    </dl>
</div>
