@extends('layouts.admin')

@section('title', 'Buat Session WhatsApp - Halo MANAP')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Buat Session WhatsApp</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <a href="{{ route('admin.whatsapp-sessions.index') }}" class="text-blue-600 hover:text-blue-700">WhatsApp Sessions</a>
            <span class="text-gray-400">/</span>
            <span>Buat Baru</span>
        </div>
    </div>
</div>

@if($errors->any())
<div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200 flex items-center gap-2 mb-6">
    <i class="fa-solid fa-exclamation-circle"></i>
    {{ $errors->first() }}
</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    <form action="{{ route('admin.whatsapp-sessions.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Session ID</label>
            <input type="text" name="session_id" value="{{ old('session_id') }}" required
                class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                placeholder="contoh: my-wa-session">
            <p class="text-xs text-gray-400 mt-1">Identifier unik untuk session WhatsApp ini.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor WhatsApp</label>
            <input type="text" name="phone_number" value="{{ old('phone_number') }}" required
                class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                placeholder="628123456789">
            <p class="text-xs text-gray-400 mt-1">Nomor WhatsApp yang akan didaftarkan. Format internasional tanpa + atau spasi.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Webhook URL <span class="text-gray-400">(opsional)</span></label>
            <input type="url" name="webhook_url" value="{{ old('webhook_url') }}"
                class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                placeholder="https://example.com/webhook/whatsapp">
            <p class="text-xs text-gray-400 mt-1">URL untuk menerima notifikasi pesan masuk secara real-time.</p>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors text-sm font-medium shadow-sm">
                <i class="fa-solid fa-plus mr-1.5"></i>
                Buat Session
            </button>
            <a href="{{ route('admin.whatsapp-sessions.index') }}" class="px-5 py-2.5 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-xl transition-colors text-sm">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
