<?php

namespace App\Jobs;

use App\Models\Setting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SendWhatsAppNotification implements ShouldQueue
{
    use Queueable;

    protected $phoneNumber;

    protected $message;

    public function __construct($phoneNumber, $message)
    {
        $this->onQueue('notifications');
        $this->phoneNumber = $phoneNumber;
        $this->message = $message;
    }

    public function handle(): void
    {
        if (empty($this->phoneNumber)) {
            return;
        }

        try {
            $dbUrl = Setting::getValue('waha_api_url');
            $dbKey = Setting::getValue('waha_api_key');
            $dbSession = Setting::getValue('waha_session');

            $apiUrl = $dbUrl ?: config('whatsapp.api_url');
            $session = $dbSession ?: config('whatsapp.session');

            if ($dbKey) {
                try {
                    $apiKey = Crypt::decryptString($dbKey);
                } catch (\Throwable) {
                    $apiKey = $dbKey;
                }
            } else {
                $apiKey = config('whatsapp.api_key');
            }

            $chatId = Str::phone($this->phoneNumber);
            if (! $chatId) {
                return;
            }

            $url = rtrim($apiUrl, '/').'/api/sendText';
            $response = Http::withHeaders([
                'X-Api-Key' => $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(15)->post($url, [
                'session' => $session,
                'chatId' => $chatId,
                'text' => $this->message,
            ]);

            if (! $response->successful()) {
                Log::warning('WAHA send failed', [
                    'to' => $this->phoneNumber,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim WhatsApp via WAHA: '.$e->getMessage(), [
                'to' => $this->phoneNumber,
            ]);
        }
    }
}
