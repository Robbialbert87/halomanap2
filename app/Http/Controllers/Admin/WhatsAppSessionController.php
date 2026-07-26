<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppSession;
use App\Services\WahaApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class WhatsAppSessionController extends Controller
{
    public function __construct(
        private readonly WahaApiService $waha,
    ) {}

    public function index(): View
    {
        $sessions = WhatsAppSession::with('user')->latest()->get();

        return view('admin.whatsapp-sessions.index', compact('sessions'));
    }

    public function create(): View
    {
        return view('admin.whatsapp-sessions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'session_id' => 'required|string|max:255|unique:whatsapp_sessions',
            'phone_number' => 'required|string|max:20',
            'webhook_url' => 'nullable|url|max:255',
        ]);

        $sessionConfig = [
            'webhook' => $request->filled('webhook_url') ? [
                'url' => $request->webhook_url,
                'events' => ['message'],
            ] : null,
        ];

        try {
            $this->waha->upsertAndStartSession($validated['session_id'], $sessionConfig);
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', 'Gagal membuat session: '.$e->getMessage());
        }

        WhatsAppSession::create([
            'user_id' => auth()->id(),
            'session_id' => $validated['session_id'],
            'phone_number' => $validated['phone_number'],
            'status' => 'scanning',
            'webhook_config' => $sessionConfig['webhook'],
        ]);

        return redirect()->route('admin.whatsapp-sessions.show', $validated['session_id'])
            ->with('success', 'Session WhatsApp berhasil dibuat. Scan QR code untuk menghubungkan.');
    }

    public function show(string $sessionId): View
    {
        $session = WhatsAppSession::with('user')
            ->where('session_id', $sessionId)
            ->firstOrFail();

        $qr = null;
        try {
            $qr = $this->waha->getQr($sessionId);
        } catch (\RuntimeException $e) {
            // QR mungkin belum siap atau session sudah terhubung
        }

        return view('admin.whatsapp-sessions.show', compact('session', 'qr'));
    }

    public function refreshQr(string $sessionId): JsonResponse
    {
        try {
            $qr = $this->waha->getQr($sessionId);

            WhatsAppSession::where('session_id', $sessionId)->update([
                'qr_code' => $qr,
                'status' => 'scanning',
            ]);

            return response()->json(['qr' => $qr]);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 502);
        }
    }

    public function status(string $sessionId): JsonResponse
    {
        $session = WhatsAppSession::where('session_id', $sessionId)->firstOrFail();

        try {
            $info = $this->waha->getSession($sessionId);
            $status = $info['status'] ?? 'unknown';
        } catch (\RuntimeException $e) {
            $session->update(['status' => 'NOT_CONNECTED']);

            return response()->json(['status' => 'NOT_CONNECTED', 'error' => $e->getMessage()]);
        }

        $session->update([
            'status' => $status,
            'connected_at' => $status === 'CONNECTED' ? now() : $session->connected_at,
        ]);

        return response()->json(['status' => $status, 'info' => $info]);
    }

    public function sync(string $sessionId): JsonResponse
    {
        $session = WhatsAppSession::where('session_id', $sessionId)->firstOrFail();

        try {
            $info = $this->waha->getSession($sessionId);
            $status = $info['status'] ?? 'NOT_CONNECTED';
            $me = $status === 'CONNECTED' ? $this->waha->getSession($sessionId) : [];
        } catch (\RuntimeException $e) {
            $session->update(['status' => 'NOT_CONNECTED']);

            return response()->json(['status' => 'NOT_CONNECTED', 'synced' => true]);
        }

        $session->update([
            'status' => $status,
            'connected_at' => $status === 'CONNECTED' ? now() : null,
        ]);

        return response()->json([
            'status' => $status,
            'synced' => true,
            'connected' => $status === 'CONNECTED',
        ]);
    }

    public function disconnect(string $sessionId): RedirectResponse
    {
        try {
            $this->waha->logout($sessionId);
        } catch (\RuntimeException $e) {
            return back()->with('error', 'Gagal disconnect: '.$e->getMessage());
        }

        WhatsAppSession::where('session_id', $sessionId)->update([
            'status' => 'disconnected',
            'disconnected_at' => now(),
        ]);

        return redirect()->route('admin.whatsapp-sessions.index')
            ->with('success', 'Session WhatsApp berhasil diputuskan.');
    }

    public function destroy(string $sessionId): RedirectResponse
    {
        try {
            $this->waha->deleteSession($sessionId);
        } catch (\RuntimeException $e) {
            return back()->with('error', 'Gagal menghapus session: '.$e->getMessage());
        }

        WhatsAppSession::where('session_id', $sessionId)->delete();

        return redirect()->route('admin.whatsapp-sessions.index')
            ->with('success', 'Session WhatsApp berhasil dihapus.');
    }
}
