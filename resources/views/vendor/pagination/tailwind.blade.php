@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="w-full">

        {{-- Mobile: simple prev/next glossy pills --}}
        <div class="flex items-center justify-between sm:hidden">
            <div class="flex gap-2 w-full">
                @if ($paginator->onFirstPage())
                    <span class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2.5 text-sm font-medium text-gray-400 bg-white/60 border border-white/30 rounded-xl cursor-not-allowed" style="background: linear-gradient(135deg, rgba(255,255,255,0.6) 0%, rgba(255,255,255,0.3) 100%);">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                        <span class="font-heading text-[13px]">Sebelumnya</span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2.5 text-sm font-medium text-gray-700 bg-white/80 backdrop-blur-xl border border-white/30 rounded-xl active:scale-[0.97] transition-all shadow-sm" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%);">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                        <span class="font-heading text-[13px]">Sebelumnya</span>
                    </a>
                @endif

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2.5 text-sm font-medium text-gray-700 bg-white/80 backdrop-blur-xl border border-white/30 rounded-xl active:scale-[0.97] transition-all shadow-sm" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%);">
                        <span class="font-heading text-[13px]">Selanjutnya</span>
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </a>
                @else
                    <span class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2.5 text-sm font-medium text-gray-400 bg-white/60 border border-white/30 rounded-xl cursor-not-allowed" style="background: linear-gradient(135deg, rgba(255,255,255,0.6) 0%, rgba(255,255,255,0.3) 100%);">
                        <span class="font-heading text-[13px]">Selanjutnya</span>
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </span>
                @endif
            </div>
        </div>

        {{-- Desktop: glossy pagination with page numbers --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between sm:gap-4">
            <div>
                <p class="text-sm text-gray-500 leading-5 font-medium">
                    Menampilkan
                    @if ($paginator->firstItem())
                        <span class="font-semibold text-gray-700">{{ $paginator->firstItem() }}</span>
                        -
                        <span class="font-semibold text-gray-700">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    dari
                    <span class="font-semibold text-gray-700">{{ $paginator->total() }}</span>
                    data
                </p>
            </div>

            <div class="flex items-center gap-1.5">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-gray-400 bg-white/60 border border-white/30 rounded-xl cursor-not-allowed" style="background: linear-gradient(135deg, rgba(255,255,255,0.6) 0%, rgba(255,255,255,0.3) 100%);">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-gray-600 bg-white/80 backdrop-blur-xl border border-white/30 rounded-xl hover:bg-white/90 transition-all active:scale-[0.95] shadow-sm" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%);">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-gray-500 bg-transparent">
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="inline-flex items-center justify-center w-9 h-9 text-sm font-bold text-white bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl shadow-sm shadow-blue-200/50">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-gray-600 bg-white/80 backdrop-blur-xl border border-white/30 rounded-xl hover:bg-white/90 transition-all active:scale-[0.95] shadow-sm" aria-label="{{ __('Go to page :page', ['page' => $page]) }}" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%);">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-gray-600 bg-white/80 backdrop-blur-xl border border-white/30 rounded-xl hover:bg-white/90 transition-all active:scale-[0.95] shadow-sm" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.5) 100%);">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </a>
                @else
                    <span class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-gray-400 bg-white/60 border border-white/30 rounded-xl cursor-not-allowed" style="background: linear-gradient(135deg, rgba(255,255,255,0.6) 0%, rgba(255,255,255,0.3) 100%);">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
