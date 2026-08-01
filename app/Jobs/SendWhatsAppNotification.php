<?php

namespace App\Jobs;

use App\Models\NotificationLog;
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

    /**
     * Hanya 1 percobaan: jika WAHA sudah menerima & mengirim pesan tapi response
     * balik lambat (timeout HTTP), retry akan mengirim ulang pesan yang sama → duplikat.
     */
    public $tries = 1;

    /** Backoff tidak berlaku karena $tries = 1; dijaga eksplisit agar tidak default-retry. */
    public $backoff = 0;

    public $maxExceptions = 1;

    protected $phoneNumber;

    protected $message;

    protected ?int $logId;

    /**
     * @param  int|null  $logId  ID NotificationLog yang sedang dikirim ulang
     *                           (status-nya akan di-update ke sent/failed setelah selesai).
     */
    public function __construct($phoneNumber, $message, ?int $logId = null)
    {
        $this->onQueue('notifications');
        $this->phoneNumber = $phoneNumber;
        $this->message = $message;
        $this->logId = $logId;
    }

    public function handle(): void
    {
        if (empty($this->phoneNumber)) {
            return;
        }

        $status = 'failed';
        $error = null;

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
                $error = 'Invalid phone number format';

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

            if ($response->successful() || $response->status() === 201) {
                $status = 'sent';
            } else {
                $error = $response->body();
                Log::warning('WAHA send failed', [
                    'to' => $this->phoneNumber,
                    'status' => $response->status(),
                    'body' => $error,
                ]);
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Log::error('Gagal mengirim WhatsApp via WAHA: '.$e->getMessage(), [
                'to' => $this->phoneNumber,
            ]);
        } finally {
            if ($this->logId) {
                NotificationLog::where('id', $this->logId)->update([
                    'status' => $status,
                    'error_message' => $error,
                    'sent_at' => $status === 'sent' ? now() : null,
                ]);
            }
        }
    }
}
