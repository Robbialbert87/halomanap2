@extends('layouts.admin')

@section('title', 'Data Apresiasi - Halo MANAP')

@section('admin_content')
<div class="animate-fadeInUp">
    {{-- Mobile Header --}}
    <div class="md:hidden mb-3">
        <div class="flex items-center gap-2.5 p-1">
            <span class="w-9 h-9 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200/50 flex-shrink-0">
                <i class="fa-solid fa-thumbs-up text-white text-sm"></i>
            </span>
            <div>
                <p class="text-[9px] text-blue-500 font-semibold tracking-wider uppercase font-heading">Data</p>
                <h1 class="text-base font-bold text-gray-800 font-heading">Apresiasi</h1>
            </div>
        </div>
    </div>

    {{-- Desktop Header --}}
    <div class="hidden md:flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-heading">Data Apresiasi</h1>
            <p class="text-sm text-gray-400 mt-1">Daftar apresiasi dari masyarakat</p>
        </div>
        <div class="text-sm text-gray-400">
            Total: <span class="font-bold text-gray-700">{{ $appreciations->total() }}</span>
        </div>
    </div>

    {{-- Mobile List --}}
    <div class="md:hidden space-y-2.5">
        @forelse($appreciations as $a)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden active:scale-[0.99] transition-transform">
            <div class="flex items-start gap-3 px-3.5 py-3">
                <span class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200/50 flex-shrink-0 text-white text-sm">
                    <i class="fa-solid fa-thumbs-up"></i>
                </span>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-sm font-semibold text-gray-800 truncate">{{ $a->name ?? 'Anonim' }}</span>
                        <span class="text-[10px] text-gray-400 whitespace-nowrap">{{ $a->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center gap-0.5 mt-0.5">
                        @for($i = 1; $i <= 5; $i++)
                        <i class="fa-solid fa-star {{ $i <= $a->rating ? 'text-yellow-400' : 'text-gray-200' }} text-[10px]"></i>
                        @endfor
                    </div>
                    @if($a->message)
                    <p class="text-xs text-gray-500 mt-1.5 leading-relaxed line-clamp-2">{{ $a->message }}</p>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-gray-400">
            <i class="fa-regular fa-star text-3xl mb-2 block"></i>
            <span class="text-sm">Belum ada apresiasi masuk</span>
        </div>
        @endforelse
        @if($appreciations->hasPages())
        <div class="pt-2">
            {{ $appreciations->links() }}
        </div>
        @endif
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
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
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $appreciations->firstItem() + $loop->index }}</td>
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
