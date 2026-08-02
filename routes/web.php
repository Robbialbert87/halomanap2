<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DispositionController as AdminDispositionController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\ProfilController as AdminProfilController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\TicketAttachmentController;
use App\Http\Controllers\Admin\TicketCommentController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UnitTypeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WhatsappSettingsController;
use App\Http\Controllers\Admin\WorkflowController;
use App\Http\Controllers\ApresiasiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DalamPenangananController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Direktur\AuditTrailController;
use App\Http\Controllers\Direktur\MonitoringWorkflowController;
use App\Http\Controllers\Direktur\StatistikController;
use App\Http\Controllers\DispositionController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\WahaWebhookController;
use App\Http\Middleware\CheckDirektur;
use App\Models\AppNotification;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ── AUTH ──────────────────────────────────────────────────────────────────────
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── PUBLIC ────────────────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('home');
});

// Webhook WAHA (dipanggil server WAHA, tanpa auth — validasi HMAC di controller)
Route::post('/api/waha/webhook', [WahaWebhookController::class, 'handle'])
    ->name('waha.webhook');

Route::get('/pengaduan/buat', [PengaduanController::class, 'create'])->name('pengaduan.create');
Route::post('/pengaduan/buat', [PengaduanController::class, 'store'])->name('pengaduan.store');
Route::get('/pengaduan/sukses/{ticket_number}', [PengaduanController::class, 'success'])->name('pengaduan.success');
Route::get('/pengaduan/tiket/{ticket_number}/download', [PengaduanController::class, 'downloadTicket'])->name('pengaduan.ticket-download');
Route::get('/lacak', [PengaduanController::class, 'track'])->name('pengaduan.track');

// ── APRESIASI (public) ────────────────────────────────────────────────────────
Route::get('/apresiasi', [ApresiasiController::class, 'create'])->name('apresiasi.create');
Route::post('/apresiasi', [ApresiasiController::class, 'store'])->name('apresiasi.store');
Route::get('/apresiasi/sukses', [ApresiasiController::class, 'success'])->name('apresiasi.sukses');

// ── PROTECTED (auth required) ─────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('can:menu.dashboard');

    // ── NOTIFICATIONS (AJAX mark-as-read) ──────────────────────────────────────
    Route::post('/notifications/mark-read', function (Request $request) {
        $ticketId = $request->input('ticket_id');
        $user = auth()->user();

        if ($ticketId && $user) {
            Ticket::where('id', $ticketId)
                ->whereNull('notification_seen_at')
                ->update(['notification_seen_at' => now()]);

            AppNotification::where('user_id', $user->id)
                ->where('data->ticket_id', $ticketId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        return response()->json(['success' => true]);
    })->name('notifications.mark-read');

    // ── ADMIN ─────────────────────────────────────────────────────────────────
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('tickets', TicketController::class);

        // Live search mobile
        Route::get('tickets/mobile-search', [TicketController::class, 'mobileSearch'])->name('tickets.mobile-search');

        // Verifikasi
        Route::post('tickets/{ticket}/verify', [TicketController::class, 'verify'])->name('tickets.verify');
        Route::post('tickets/{ticket}/reject', [TicketController::class, 'reject'])->name('tickets.reject');

        // Internal Comments
        Route::post('tickets/{ticket}/comments', [TicketCommentController::class, 'store'])->name('tickets.comments.store');

        // Internal Attachments
        Route::post('tickets/{ticket}/attachments', [TicketAttachmentController::class, 'store'])->name('tickets.attachments.store');
        Route::get('tickets/{ticket}/attachments/{attachment}/download', [TicketAttachmentController::class, 'download'])->name('tickets.attachments.download');

        // Dispositions
        Route::get('dispositions', [AdminDispositionController::class, 'index'])->name('dispositions.index');
        Route::post('dispositions', [AdminDispositionController::class, 'store'])->name('dispositions.store');

        Route::resource('units', UnitController::class);
        Route::resource('rooms', RoomController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('unit-types', UnitTypeController::class);

        // Apresiasi
        Route::get('apresiasi', [App\Http\Controllers\Admin\ApresiasiController::class, 'index'])->name('apresiasi.index');

        // Laporan
        Route::get('laporan', [AdminLaporanController::class, 'index'])->name('laporan');
        Route::get('laporan/export-pdf', [AdminLaporanController::class, 'exportPdf'])->name('laporan.export-pdf');

        // Profil
        Route::get('profil', [AdminProfilController::class, 'index'])->name('profil');
        Route::post('profil/password', [AdminProfilController::class, 'updatePassword'])->name('profil.password');

        // User & Role Management
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::resource('jabatans', JabatanController::class);

        // WhatsApp Gateway
        Route::get('whatsapp', [WhatsappSettingsController::class, 'index'])->name('whatsapp.index');
        Route::post('whatsapp/test/send', [WhatsappSettingsController::class, 'sendTest'])->name('whatsapp.send-test');
        Route::get('whatsapp/status', [WhatsappSettingsController::class, 'checkStatus'])->name('whatsapp.status');
        Route::get('whatsapp/resend', [WhatsappSettingsController::class, 'showFailed'])->name('whatsapp.resend');
        Route::post('whatsapp/resend-submit', [WhatsappSettingsController::class, 'resendSubmit'])->name('whatsapp.resend-submit');
        Route::post('whatsapp/barcode-update', [WhatsappSettingsController::class, 'saveBarcodeUrl'])->name('whatsapp.barcode-update');
        Route::post('whatsapp/update-config', [WhatsappSettingsController::class, 'updateConfig'])->name('whatsapp.update-config');
        Route::post('whatsapp/sessions', [WhatsappSettingsController::class, 'storeSession'])->name('whatsapp.sessions.store');
        Route::get('whatsapp/sessions/{session}', [WhatsappSettingsController::class, 'showSession'])->name('whatsapp.sessions.show');
        Route::get('whatsapp/sessions/{session}/qr', [WhatsappSettingsController::class, 'refreshQr'])->name('whatsapp.sessions.qr');
        Route::get('whatsapp/sessions/{session}/status', [WhatsappSettingsController::class, 'sessionStatus'])->name('whatsapp.sessions.status');
        Route::get('whatsapp/sse', [WhatsappSettingsController::class, 'streamStatus'])->name('whatsapp.sse');
        Route::post('whatsapp/webhook-config', [WhatsappSettingsController::class, 'updateWebhookConfig'])->name('whatsapp.update-webhook');
        Route::post('whatsapp/sessions/{session}/sync', [WhatsappSettingsController::class, 'syncSession'])->name('whatsapp.sessions.sync');
        Route::post('whatsapp/sessions/{session}/start', [WhatsappSettingsController::class, 'startSession'])->name('whatsapp.sessions.start');
        Route::post('whatsapp/sessions/{session}/restart', [WhatsappSettingsController::class, 'restartSession'])->name('whatsapp.sessions.restart');
        Route::post('whatsapp/sessions/sync-all', [WhatsappSettingsController::class, 'syncAllSessions'])->name('whatsapp.sessions.sync-all');
        Route::get('whatsapp/sessions/{session}/set-default', [WhatsappSettingsController::class, 'setDefaultSession'])->name('whatsapp.sessions.set-default');
        Route::post('whatsapp/sessions/{session}/disconnect', [WhatsappSettingsController::class, 'disconnectSession'])->name('whatsapp.sessions.disconnect');
        Route::delete('whatsapp/sessions/{session}', [WhatsappSettingsController::class, 'deleteSession'])->name('whatsapp.sessions.destroy');

        // Workflow Disposisi
        Route::post('workflow/disposisi', [WorkflowController::class, 'disposisi'])->name('workflow.disposisi');
        Route::post('workflow/{history}/eskalasi', [WorkflowController::class, 'eskalasi'])->name('workflow.eskalasi');
        Route::post('workflow/{history}/tangani', [WorkflowController::class, 'tanganiSendiri'])->name('workflow.tangani');
        Route::post('workflow/{history}/selesai', [WorkflowController::class, 'selesai'])->name('workflow.selesai');
        Route::post('workflow/{history}/tutup', [WorkflowController::class, 'tutup'])->name('workflow.tutup');

        // Dashboard Monitoring Direktur (khusus Direktur & Super Admin)
        Route::middleware(CheckDirektur::class)
            ->prefix('monitoring')
            ->name('monitoring.')
            ->group(function () {
                Route::get('/', [MonitoringController::class, 'index'])->name('index');
                Route::get('ticket/{id}', [MonitoringController::class, 'show'])->name('ticket.show');
            });
    });

    // ── ROLE-AGNOSTIC ROUTES (gated by permissions) ──────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('can:menu.dashboard');

    Route::prefix('dispositions')->name('dispositions.')->middleware('can:menu.dispositions')->group(function () {
        Route::get('/', [DispositionController::class, 'index'])->name('index');
        Route::get('/{disposition}', [DispositionController::class, 'show'])->name('show');
        Route::post('/{history}/selesai', [DispositionController::class, 'selesai'])->name('selesai')->middleware('can:manage-dispositions');
        Route::post('/{history}/eskalasi', [DispositionController::class, 'eskalasi'])->name('eskalasi')->middleware('can:manage-dispositions');
        Route::post('/{disposition}/accept', [DispositionController::class, 'accept'])->name('accept')->middleware('can:manage-dispositions');
        Route::post('/{disposition}/handle-self', [DispositionController::class, 'handleSelf'])->name('handle-self')->middleware('can:manage-dispositions');
        Route::post('/{disposition}/assign', [DispositionController::class, 'assign'])->name('assign')->middleware('can:manage-dispositions');
    });

    Route::get('/dalam-penanganan', [DalamPenangananController::class, 'index'])->name('dalam-penanganan')->middleware('can:menu.dalam-penanganan');
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat')->middleware('can:menu.riwayat');

    Route::prefix('laporan')->name('laporan.')->middleware('can:menu.laporan')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
    });

    Route::prefix('profil')->name('profil.')->middleware('can:menu.profil')->group(function () {
        Route::get('/', [ProfilController::class, 'index'])->name('index');
        Route::put('/', [ProfilController::class, 'update'])->name('update');
        Route::put('/password', [ProfilController::class, 'updatePassword'])->name('password');
    });

    // ── DIREKTUR-SPECIFIC ─────────────────────────────────────────────────────
    Route::prefix('direktur')->name('direktur.')->middleware('can:menu.direktur')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Direktur\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/monitoring-workflow', [MonitoringWorkflowController::class, 'index'])->name('monitoring-workflow');
        Route::get('/statistik', [StatistikController::class, 'index'])->name('statistik');
        Route::get('/laporan', [App\Http\Controllers\Direktur\LaporanController::class, 'index'])->name('laporan');
        Route::get('/audit-trail', [AuditTrailController::class, 'index'])->name('audit-trail');
        Route::get('/profil', [App\Http\Controllers\Direktur\ProfilController::class, 'index'])->name('profil');
    });
});
