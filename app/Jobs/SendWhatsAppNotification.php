<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppNotification implements ShouldQueue
{
    use Queueable;

    protected $phoneNumber;

    protected $message;

    /**
     * Create a new job instance.
     */
    public function __construct($phoneNumber, $message)
    {
        $this->onQueue('notifications');
        $this->phoneNumber = $phoneNumber;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (empty($this->phoneNumber)) {
            return;
        }

        try {
            $url = config('whatsapp.api_url').'/send';
            Http::timeout(10)->post($url, [
                'number' => $this->phoneNumber,
                'message' => $this->message,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim WhatsApp: '.$e->getMessage());
        }
    }
}
