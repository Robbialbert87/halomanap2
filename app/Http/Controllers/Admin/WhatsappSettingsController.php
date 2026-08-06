<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppGatewayService;

class WhatsappSettingsController extends Controller
{
    public function index()
    {
        return view('admin.whatsapp.index');
    }

    public function startServer()
    {
        // Start session WAHA (kirim notif WA kini sinkron, tanpa queue worker)
        $service = new WhatsAppGatewayService();
        $state   = $service->state();

        if ($state === 'STOPPED' || $state === 'FAILED' || $state === null) {
            $service->start();
            $state = $service->state();
        }

        $message = $state === null
            ? 'Server WAHA tidak dapat dihubungi. Pastikan server WAHA aktif, lalu coba lagi.'
            : 'Layanan berjalan! Status session: ' . $state;

        return redirect()->back()->with('success', $message);
    }

    public function checkStatus()
    {
        $service = new WhatsAppGatewayService();
        $state   = $service->state();
        $server  = $service->baseUrl();

        $base = [
            'server'    => $server,
            'lastCheck' => now()->toIso8601String(),
        ];

        if ($state === 'WORKING') {
            return response()->json(array_merge($base, [
                'success'         => true,
                'isAuthenticated' => true,
                'qr'              => null,
                'state'           => $state,
            ]));
        }

        if ($state === 'SCAN_QR_CODE') {
            $qr = $service->qr();
            return response()->json(array_merge($base, [
                'success'         => true,
                'isAuthenticated' => false,
                'qr'              => $qr,
                'message'         => $qr ? null : 'Menunggu QR Code...',
                'state'           => $state,
            ]));
        }

        if ($state === 'STARTING') {
            return response()->json(array_merge($base, [
                'success'         => true,
                'isAuthenticated' => false,
                'qr'              => null,
                'message'         => 'Sedang memuat Client... Mohon tunggu beberapa detik.',
                'state'           => $state,
            ]));
        }

        // STOPPED / FAILED / server WAHA offline
        $serverDown = $state === null;

        return response()->json(array_merge($base, [
            'success'    => false,
            'serverDown' => $serverDown,
            'error'      => $serverDown
                ? 'Server WAHA tidak dapat dihubungi.'
                : "Session WAHA tidak aktif (state: $state).",
            'state'      => $state,
        ]), 503);
    }

    public function proxyReset()
    {
        $service = new WhatsAppGatewayService();
        $service->logout();
        $service->start();

        return response()->json([
            'success' => true,
            'message' => 'Session WAHA di-reset. Silakan scan QR baru.',
        ]);
    }
}
