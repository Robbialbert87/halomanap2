<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class WhatsappSettingsController extends Controller
{
    private function apiUrl(): string
    {
        return config('whatsapp.api_url');
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

        $formattedNumber = preg_replace('/\D/', '', $phone);
        if (str_starts_with($formattedNumber, '0')) {
            $formattedNumber = '62'.substr($formattedNumber, 1);
        }

        try {
            $resp = Http::timeout(15)->post($this->apiUrl().'/send', [
                'number' => $formattedNumber,
                'message' => $message,
            ]);

            $body = $resp->json();

            if ($resp->successful() && ($body['success'] ?? false)) {
                return redirect()->route('admin.whatsapp.test')
                    ->with('success', "Pesan berhasil dikirim ke {$formattedNumber}");
            }

            $errorMsg = $body['error'] ?? 'Gagal mengirim pesan (unknown error)';

            if (str_contains($errorMsg, 'No LID for user')) {
                $errorMsg = 'WhatsApp belum terautentikasi. Scan QR Code di halaman WhatsApp Gateway terlebih dahulu.';
            }

            return redirect()->route('admin.whatsapp.test')
                ->with('error', "Gagal: {$errorMsg}")
                ->withInput();
        } catch (\Throwable $e) {
            $errorMsg = $e->getMessage();

            if (str_contains($errorMsg, 'Connection refused') || str_contains($errorMsg, 'could not connect')) {
                $errorMsg = 'WhatsApp API tidak dapat dijangkau. Pastikan container WhatsApp berjalan (ddev start).';
            }

            return redirect()->route('admin.whatsapp.test')
                ->with('error', "Gagal: {$errorMsg}")
                ->withInput();
        }
    }

    public function startServer(): RedirectResponse
    {
        try {
            $resp = Http::timeout(5)->post($this->apiUrl().'/reset');
            sleep(2);
            $check = @Http::timeout(2)->get($this->apiUrl().'/status');
            if ($check->successful()) {
                return redirect()->back()->with('success', 'Layanan berjalan! Tunggu QR Code muncul...');
            }
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal menghubungi WhatsApp API: '.$e->getMessage());
        }

        return redirect()->back()->with('warning', 'Perintah sudah dijalankan, tapi port 3000 belum merespon. Tunggu beberapa saat.');
    }

    public function checkStatus()
    {
        try {
            $resp = Http::timeout(5)->get($this->apiUrl().'/status');
            if ($resp->successful()) {
                return response($resp->body())->header('Content-Type', 'application/json');
            }
        } catch (\Throwable $e) {
            //
        }

        return response()->json(['success' => false, 'error' => 'WhatsApp API offline'], 503);
    }

    public function proxyReset()
    {
        try {
            $resp = Http::timeout(5)->post($this->apiUrl().'/reset');

            return response($resp->body())->header('Content-Type', 'application/json');
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 503);
        }
    }
}
