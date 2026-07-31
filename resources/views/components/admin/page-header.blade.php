@props([
    'title' => '',
    'titleMobile' => null, // fallback: pakai $title
    'section' => '',
    'crumb' => '',
    'breadcrumb' => null, // slot bernama untuk breadcrumb custom (bisa berisi link)
    'icon' => 'fa-shield-halved',
    'gradient' => 'purple', // purple|pink|blue|emerald|teal|orange|indigo|violet|cyan|slate|green|amber|yellow|red|gray
])

@php
    $gradients = [
        'purple'  => 'from-purple-400 to-purple-600 shadow-purple-200/50 text-purple-500',
        'pink'    => 'from-pink-400 to-pink-600 shadow-pink-200/50 text-pink-500',
        'blue'    => 'from-blue-400 to-blue-600 shadow-blue-200/50 text-blue-500',
        'emerald' => 'from-emerald-400 to-emerald-600 shadow-emerald-200/50 text-emerald-500',
        'teal'    => 'from-teal-400 to-teal-600 shadow-teal-200/50 text-teal-500',
        'orange'  => 'from-orange-400 to-orange-600 shadow-orange-200/50 text-orange-500',
        'indigo'  => 'from-indigo-400 to-indigo-600 shadow-indigo-200/50 text-indigo-500',
        'violet'  => 'from-violet-400 to-violet-600 shadow-violet-200/50 text-violet-500',
        'cyan'    => 'from-cyan-400 to-cyan-600 shadow-cyan-200/50 text-cyan-500',
        'slate'   => 'from-slate-400 to-slate-600 shadow-slate-200/50 text-slate-500',
        'green'   => 'from-green-400 to-green-600 shadow-green-200/50 text-green-500',
        'amber'   => 'from-amber-400 to-amber-600 shadow-amber-200/50 text-amber-500',
        'yellow'  => 'from-yellow-400 to-yellow-600 shadow-yellow-200/50 text-yellow-500',
        'red'     => 'from-red-400 to-red-600 shadow-red-200/50 text-red-500',
        'gray'    => 'from-gray-400 to-gray-600 shadow-gray-200/50 text-gray-500',
    ];
    $g = $gradients[$gradient] ?? $gradients['purple'];
@endphp

{{-- Mobile Page Header (PayApp style) --}}
<div class="md:hidden mb-3">
    <div class="flex items-center gap-2.5 p-1">
        <span class="w-9 h-9 rounded-2xl bg-gradient-to-br {{ $g }} flex items-center justify-center shadow-sm flex-shrink-0">
            <i class="fa-solid {{ $icon }} text-white text-sm"></i>
        </span>
        <div>
            <p class="text-[9px] {{ $g }} font-semibold tracking-wider uppercase font-heading">{{ $section }}</p>
            <h1 class="text-base font-bold text-gray-800 font-heading">{{ $titleMobile ?? $title }}</h1>
        </div>
    </div>
</div>

{{-- Page Header (Desktop) --}}
<div class="hidden md:flex {{ $summary ?? false ? 'flex-col md:flex-row md:items-center' : 'items-center' }} justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-heading">{{ $title }}</h1>
        @if($section || $crumb || !empty($breadcrumb))
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span class="text-blue-600">{{ $section }}</span>
            @if(!empty($breadcrumb))
                {{ $breadcrumb }}
            @elseif($crumb)
                <span class="text-gray-400">/</span><span>{{ $crumb }}</span>
            @endif
        </div>
        @endif
        {{-- Summary statistik (slot) --}}
        @isset($summary)
        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-2.5 text-sm text-gray-500">{{ $summary }}</div>
        @endisset
    </div>
    {{-- Action area (slot) --}}
    <div class="flex items-center gap-3 shrink-0">{{ $slot }}</div>
</div>
