@php
    $flashes = [
        'success' => session('success'),
        'error' => session('error'),
    ];
    $styles = [
        'success' => ['bg-green-50 text-green-700 border-green-200', 'fa-circle-check'],
        'error' => ['bg-red-50 text-red-700 border-red-200', 'fa-circle-exclamation'],
    ];
    $hasFlash = collect($flashes)->filter()->isNotEmpty();
@endphp

@if($hasFlash)
    {{-- Flash → toast global (pengganti banner; container di layouts/app.blade.php) --}}
    <script type="application/json" data-admin-flash>@json($flashes)</script>
    <script>
        (function () {
            function fire() {
                if (typeof window.toast !== 'function') return;
                var node = document.querySelector('[data-admin-flash]');
                if (!node) return;
                var flashes = JSON.parse(node.textContent);
                for (var type in flashes) {
                    if (flashes[type]) window.toast(flashes[type], type === 'error' ? 'error' : 'success');
                }
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', fire);
            } else {
                fire();
            }
        })();
    </script>
    {{-- Fallback untuk user tanpa JavaScript --}}
    <noscript>
        @foreach($flashes as $type => $message)
            @if($message)
                @php [$palette, $icon] = $styles[$type]; @endphp
                <div class="{{ $palette }} p-3 md:p-4 rounded-lg mb-4 md:mb-6 border flex items-center gap-2 text-sm">
                    <i class="fa-solid {{ $icon }}"></i> {{ $message }}
                </div>
            @endif
        @endforeach
    </noscript>
@endif
