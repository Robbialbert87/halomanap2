<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Services\Waha\SessionService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

class WahaSettingSeeder extends Seeder
{
    public function run(): void
    {
        $apiUrl = env('WHATSAPP_API_URL', 'https://waha.systemwebsite.my.id');
        $apiKey = env('WAHA_API_KEY', '');
        $session = env('WAHA_SESSION', 'default');

        if (! Setting::getValue('waha_api_url')) {
            Setting::setValue('waha_api_url', $apiUrl, 'WAHA API URL');
        }

        if (! Setting::getValue('waha_api_key') && $apiKey) {
            Setting::setValue('waha_api_key', Crypt::encryptString($apiKey), 'WAHA API Key (encrypted)');
        }

        // Session default: prioritas session aktif dari server WAHA (WORKING),
        // fallback ke env WAHA_SESSION.
        if (! Setting::getValue('waha_session')) {
            $activeSession = app(SessionService::class)->getActiveSessionName();
            Setting::setValue('waha_session', $activeSession ?: $session, 'WAHA Session name');
        }

        // Webhook receiver (dipakai SSE realtime status). Default: endpoint
        // aplikasi sendiri; opsional di-override lewat UI admin.
        if (! Setting::getValue('waha_webhook_url')) {
            Setting::setValue('waha_webhook_url', rtrim(config('app.url'), '/').'/api/waha/webhook', 'WAHA Webhook URL');
        }
    }
}
