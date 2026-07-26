@extends('layouts.admin')

@section('title', 'WhatsApp Sessions - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">WhatsApp Sessions</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">WhatsApp</span>
            <span class="text-gray-400">/</span>
            <span>Sessions</span>
        </div>
    </div>
    <a href="{{ route('admin.whatsapp-sessions.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors text-sm font-medium shadow-sm">
        <i class="fa-solid fa-plus"></i>
        Buat Session Baru
    </a>
</div>

@if(session('success'))
<div class="p-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200 flex items-center gap-2 mb-6">
    <i class="fa-solid fa-check-circle"></i>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200 flex items-center gap-2 mb-6">
    <i class="fa-solid fa-exclamation-circle"></i>
    {{ session('error') }}
</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="text-left px-4 py-3 font-medium">Session ID</th>
                    <th class="text-left px-4 py-3 font-medium">Nomor WhatsApp</th>
                    <th class="text-left px-4 py-3 font-medium">User</th>
                    <th class="text-left px-4 py-3 font-medium">Status</th>
                    <th class="text-left px-4 py-3 font-medium">Dibuat</th>
                    <th class="text-right px-4 py-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($sessions as $session)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $session->session_id }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $session->phone_number }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $session->user->name }}</td>
                    <td class="px-4 py-3">
                        @php
                        $statusColors = [
                            'CONNECTED' => 'bg-green-100 text-green-700',
                            'scanning' => 'bg-yellow-100 text-yellow-700',
                            'creating' => 'bg-blue-100 text-blue-700',
                            'disconnected' => 'bg-red-100 text-red-700',
                            'NOT_CONNECTED' => 'bg-yellow-100 text-yellow-700',
                        ];
                        $color = $statusColors[$session->status] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                            {{ $session->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $session->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.whatsapp-sessions.show', $session->session_id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @if($session->status !== 'disconnected')
                            <form action="{{ route('admin.whatsapp-sessions.disconnect', $session->session_id) }}" method="POST" class="inline" onsubmit="return confirm('Putuskan session ini?')">
                                @csrf
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Disconnect">
                                    <i class="fa-solid fa-plug"></i>
                                </button>
                            </form>
                            @endif
                            <form action="{{ route('admin.whatsapp-sessions.destroy', $session->session_id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus session ini? Tindakan ini tidak bisa dibatalkan.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-phone-slash text-3xl mb-3 block"></i>
                        <p class="text-sm">Belum ada session WhatsApp.</p>
                        <a href="{{ route('admin.whatsapp-sessions.create') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium mt-1 inline-block">Buat session pertama</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
