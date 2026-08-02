<?php

use App\Models\Setting;
use App\Services\Waha\DTO\SessionStatus;
use App\Services\Waha\SessionService;
use App\Services\Waha\WahaRequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

beforeEach(function (): void {
    // Skema minimal tabel settings (RefreshDatabase penuh gagal karena migrasi
    // users incompatibel dengan SQLite; ini cukup untuk service WAHA).
    Schema::dropIfExists('settings');
    Schema::create('settings', function ($table) {
        $table->id();
        $table->string('key')->unique();
        $table->text('value')->nullable();
        $table->text('description')->nullable();
        $table->timestamps();
    });

    Setting::setValue('waha_api_url', 'https://waha.test');
    Setting::setValue('waha_api_key', 'secret-key');
});

it('menemukan session dan memetakan status ke DTO', function (): void {
    Http::fake([
        'waha.test/api/sessions/def' => Http::response([
            'name' => 'def',
            'status' => 'FAILED',
        ]),
    ]);

    $status = app(SessionService::class)->get('def');

    expect($status)->toBeInstanceOf(SessionStatus::class)
        ->status->toBe('FAILED')
        ->name->toBe('def')
        ->isConnected()->toBeFalse()
        ->isScanning()->toBeFalse();
});

test('status WORKING ditandai terhubung', function (): void {
    Http::fake([
        'waha.test/api/sessions/def' => Http::response(['status' => 'WORKING']),
    ]);

    expect(app(SessionService::class)->get('def')->isConnected())->toBeTrue();
});

test('mengambil QR saat SCAN_QR_CODE', function (): void {
    Http::fake([
        'waha.test/api/def/auth/qr' => Http::response(['mimetype' => 'image/png', 'data' => 'aW1n']),
    ]);

    $qr = app(SessionService::class)->qr('def');

    expect($qr)->not->toBeNull();
    expect($qr->dataUri())->toBe('data:image/png;base64,aW1n');
});

test('mengambil QR saat session FAILED melempar WahaRequestException 422', function (): void {
    Http::fake([
        'waha.test/api/def/auth/qr' => Http::response([
            'error' => 'Session status is not as expected',
            'status' => 'FAILED',
            'expected' => ['SCAN_QR_CODE'],
        ], 422),
    ]);

    app(SessionService::class)->qr('def');
})->expectException(WahaRequestException::class);

test('route start mengirim POST /api/sessions/{id}/start', function (): void {
    Http::fake(['waha.test/*' => Http::response(['status' => 'STARTING', 'name' => 'def'])]);

    $status = app(SessionService::class)->start('def');

    expect($status->status)->toBe('STARTING');
    Http::assertSent(fn ($request) => $request->url() === 'https://waha.test/api/sessions/def/start' && $request->method() === 'POST');
});

test('kredensial dikirim dari Setting DB', function (): void {
    Http::fake(['waha.test/*' => Http::response(['status' => 'FAILED'])]);

    app(SessionService::class)->get('def');

    Http::assertSent(fn ($request) => $request->hasHeader('X-Api-Key', 'secret-key'));
});
