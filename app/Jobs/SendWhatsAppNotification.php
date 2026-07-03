<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

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
            \Illuminate\Support\Facades\Http::timeout(10)->post('http://localhost:3000/send', [
                'number' => $this->phoneNumber,
                'message' => $this->message,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim WhatsApp: ' . $e->getMessage());
        }
    }
}
