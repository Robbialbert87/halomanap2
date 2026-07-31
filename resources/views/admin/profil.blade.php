@extends('layouts.admin')

@section('title', 'Profil - Halo MANAP')

@section('admin_content')
<x-admin.page-header
    title="Profil"
    section="Admin"
    icon="fa-user"
    gradient="blue"
>
    <x-slot name="breadcrumb">
        <span class="text-gray-400">/</span>
        <span>Profil</span>
    </x-slot>
</x-admin.page-header>

<x-admin.flash />

<x-admin.errors />


<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- PROFIL CARD --}}
    <div class="lg:col-span-1">
        <div class="admin-card p-6 text-center">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=3b82f6&color=fff&size=120" alt="Avatar" class="w-28 h-28 rounded-full mx-auto mb-4 shadow-md border-4 border-white">
            <h2 class="text-xl font-bold text-gray-800">{{ $user->nama }}</h2>
            <p class="text-gray-500 text-sm mt-0.5">{{ $user->nip }}</p>
            <div class="mt-3 flex flex-wrap justify-center gap-2">
                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">{{ $user->jabatan?->nama ?? '-' }}</span>
                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">{{ $user->unit?->nama ?? '-' }}</span>
            </div>
            @if($user->email)
            <p class="text-sm text-gray-400 mt-3"><i class="fa-regular fa-envelope mr-1"></i>{{ $user->email }}</p>
            @endif
            @if($user->phone_number)
            <p class="text-sm text-gray-400"><i class="fa-regular fa-phone mr-1"></i>{{ $user->phone_number }}</p>
            @endif
            <div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-400">
                Bergabung sejak {{ $user->created_at ? $user->created_at->isoFormat('DD MMM YYYY') : '-' }}
            </div>
        </div>
    </div>

    {{-- GANTI PASSWORD --}}
    <div class="lg:col-span-2">
        <div class="admin-card p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-1">Ganti Password</h3>
            <p class="text-sm text-gray-500 mb-5">Pastikan password baru minimal 8 karakter dan tidak mudah ditebak.</p>

            <form method="POST" action="{{ route('admin.profil.password') }}" class="space-y-4 max-w-md">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password Saat Ini</label>
                    <div class="relative">
                        <input type="password" name="current_password" required
                            class="admin-input pr-10">
                        <button type="button" onclick="togglePassword('current_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password Baru</label>
                    <div class="relative">
                        <input type="password" name="new_password" required
                            class="admin-input pr-10">
                        <button type="button" onclick="togglePassword('new_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <input type="password" name="new_password_confirmation" required
                            class="admin-input pr-10">
                        <button type="button" onclick="togglePassword('new_password_confirmation', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="admin-btn admin-btn-primary gap-2">
                    <i class="fa-solid fa-key"></i> Simpan Password
                </button>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function togglePassword(fieldId, btn) {
    const input = document.querySelector(`[name="${fieldId}"]`);
    if (!input) return;
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush
