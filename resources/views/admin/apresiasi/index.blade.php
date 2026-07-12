@extends('layouts.admin')

@section('title', 'Data Apresiasi - Halo MANAP')

@section('admin_content')
<div class="animate-fadeInUp">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-heading">Data Apresiasi</h1>
            <p class="text-sm text-gray-400 mt-1">Daftar apresiasi dari masyarakat</p>
        </div>
        <div class="text-sm text-gray-400">
            Total: <span class="font-bold text-gray-700">{{ $appreciations->total() }}</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="px-4 py-3 font-semibold text-gray-500 text-[11px] uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 font-semibold text-gray-500 text-[11px] uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 font-semibold text-gray-500 text-[11px] uppercase tracking-wider">Rating</th>
                        <th class="px-4 py-3 font-semibold text-gray-500 text-[11px] uppercase tracking-wider">Pesan</th>
                        <th class="px-4 py-3 font-semibold text-gray-500 text-[11px] uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($appreciations as $a)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-medium text-gray-700">{{ $a->name ?? 'Anonim' }}</td>
                        <td class="px-4 py-3">
                            <span class="flex items-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star {{ $i <= $a->rating ? 'text-yellow-400' : 'text-gray-200' }} text-[11px]"></i>
                                @endfor
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 max-w-[200px] truncate">{{ $a->message ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $a->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-gray-400">
                            <i class="fa-regular fa-star text-2xl mb-2 block"></i>
                            Belum ada apresiasi masuk
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($appreciations->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $appreciations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
