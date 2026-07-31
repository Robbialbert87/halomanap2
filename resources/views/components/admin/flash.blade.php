@php
    $flashes = [
        'success' => session('success'),
        'error' => session('error'),
    ];
    $styles = [
        'success' => ['bg-green-50 text-green-700 border-green-200', 'fa-circle-check'],
        'error' => ['bg-red-50 text-red-700 border-red-200', 'fa-circle-exclamation'],
    ];
@endphp

@foreach($flashes as $type => $message)
    @if($message)
        @php [$palette, $icon] = $styles[$type]; @endphp
        {{-- Mobile --}}
        <div class="md:hidden {{ $palette }} p-3 rounded-lg mb-4 border flex items-center gap-2 text-[13px]">
            <i class="fa-solid {{ $icon }}"></i> {{ $message }}
        </div>
        {{-- Desktop --}}
        <div class="hidden md:flex {{ $palette }} p-4 rounded-lg mb-6 border items-center gap-2">
            <i class="fa-solid {{ $icon }}"></i> {{ $message }}
        </div>
    @endif
@endforeach
