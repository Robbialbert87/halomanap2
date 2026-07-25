<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Jobs\SendWhatsAppNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class WhatsappSettingsController extends Controller
{
    private function apiUrl(): string
    {
        return rtrim(config('whatsapp.api_url'), '/');
    }

    private function wahaHeaders(): array
    {
        return [
            'X-Api-Key' => config('whatsapp.api_key'),
            'Content-Type' => 'application/json',
        ];
    }

    public function index(): View
    {
        return view('admin.whatsapp.index');
    }

    public function test(): View
    {
        return view('admin.whatsapp.test');
    }

    public function sendTest(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        $phone = $validated['phone'];
        $message = $validated['message'];

        $chatId = $this->formatNumber($phone);

        try {
            $resp = Http::withHeaders($this->wahaHeaders())
                ->timeout(15)
                ->post($this->apiUrl().'/api/sendText', [
                    'session' => config('whatsapp.session'),
                    'chatId' => $chatId,
                    'text' => $message,
                ]);

            if ($resp->successful() || $resp->status() === 201) {
                return redirect()->route('admin.whatsapp.test')
                    ->with('success', "Pesan berhasil dikirim ke {$phone}");
            }

            $body = $resp->json();
            $errorMsg = $body['error'] ?? $resp->body();

            return redirect()->route('admin.whatsapp.test')
                ->with('error', "Gagal: {$errorMsg}")
                ->withInput();
        } catch (\Throwable $e) {
            $errorMsg = $e->getMessage();

            if (str_contains($errorMsg, 'Connection refused') || str_contains($errorMsg, 'could not connect')) {
                $errorMsg = 'WAHA API tidak dapat dijangkau. Pastikan WAHA berjalan.';
            }

            return redirect()->route('admin.whatsapp.test')
                ->with('error', "Gagal: {$errorMsg}")
                ->withInput();
        }
    }

    public function checkStatus()
    {
        try {
            $session = config('whatsapp.session');
            $resp = Http::withHeaders($this->wahaHeaders())
                ->timeout(10)
                ->get($this->apiUrl().'/api/sessions/'.$session);

            if ($resp->successful()) {
                $data = $resp->json();
                $status = $data['status'] ?? 'STOPPED';
                $me = $data['me'] ?? null;

                return response()->json([
                    'success' => true,
                    'status' => $status,
                    'isAuthenticated' => $status === 'WORKING',
                    'me' => $me,
                    'session' => $session,
                ]);
            }

            if ($resp->status() === 404) {
                return response()->json([
                    'success' => false,
                    'status' => 'NOT_FOUND',
                    'isAuthenticated' => false,
                    'error' => "Session '{$session}' tidak ditemukan. Buat session di WAHA dashboard.",
                ]);
            }
        } catch (\Throwable $e) {
            //
        }

        return response()->json([
            'success' => false,
            'status' => 'OFFLINE',
            'isAuthenticated' => false,
            'error' => 'WAHA API tidak dapat dijangkau',
        ], 503);
    }

    public function showFailed(): View
    {
        $failedLogs = NotificationLog::where('status', 'failed')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.whatsapp.resend', compact('failedLogs'));
    }

    public function resendSubmit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:notification_logs,id',
        ]);

        $resent = 0;
        $logs = NotificationLog::whereIn('id', $validated['ids'])->get();

        foreach ($logs as $log) {
            SendWhatsAppNotification::dispatch($log->nomor_wa, $log->isi_pesan);
            $resent++;
        }

        return redirect()->route('admin.whatsapp.resend')
            ->with('success', "{$resent} notifikasi sedang dikirim ulang.");
    }

    private function formatNumber(string $number): string
    {
        $digits = preg_replace('/\D/', '', $number);
        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }
        return $digits.'@c.us';
    }
}
