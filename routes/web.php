<?php

use App\Models\AppNotification;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\Auth\LoginController;

// ── AUTH ──────────────────────────────────────────────────────────────────────
Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── PUBLIC ────────────────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('home');
});

Route::get('/pengaduan/buat', [PengaduanController::class, 'create'])->name('pengaduan.create');
Route::post('/pengaduan/buat', [PengaduanController::class, 'store'])->name('pengaduan.store');
Route::get('/pengaduan/sukses/{ticket_number}', [PengaduanController::class, 'success'])->name('pengaduan.success');
Route::get('/pengaduan/tiket/{ticket_number}/download', [PengaduanController::class, 'downloadTicket'])->name('pengaduan.ticket-download');
Route::get('/lacak', [PengaduanController::class, 'track'])->name('pengaduan.track');

// ── PROTECTED (auth required) ─────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $user = auth()->user();
        $roleGroup = \App\Services\RoleMenuService::getRoleGroup($user);

        return match ($roleGroup) {
            'kepala_unit' => redirect()->route('kepala-unit.dashboard'),
            'kasi'        => redirect()->route('kasi.dashboard'),
            'kabid'       => redirect()->route('kabid.dashboard'),
            'head_unit'   => redirect()->route('head-unit.dispositions.index'),
            'direktur'    => redirect()->route('direktur.dashboard'),
            default       => (function () use ($user) {
                $total = \App\Models\Ticket::count();
                $menunggu = \App\Models\Ticket::whereIn('status', ['Baru', 'TERVERIFIKASI', 'Menunggu Verifikasi'])->count();
                $diproses = \App\Models\Ticket::where('status', 'Diproses')->count();
                $selesai = \App\Models\Ticket::where('status', 'Selesai')->count();
                $ditolak = \App\Models\Ticket::where('status', 'Ditolak')->count();

                $pMenunggu = $total > 0 ? round(($menunggu / $total) * 100, 2) : 0;
                $pDiproses = $total > 0 ? round(($diproses / $total) * 100, 2) : 0;
                $pSelesai = $total > 0 ? round(($selesai / $total) * 100, 2) : 0;
                $pDitolak = $total > 0 ? round(($ditolak / $total) * 100, 2) : 0;

                $months = collect();
                for ($i = 5; $i >= 0; $i--) {
                    $months->push(now()->subMonths($i)->format('Y-m'));
                }
                $monthlyRaw = \App\Models\Ticket::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as total")
                    ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
                    ->groupBy('bulan')
                    ->orderBy('bulan')
                    ->pluck('total', 'bulan');
                $monthlyLabels = $months->map(function ($m) {
                    return \Carbon\Carbon::createFromFormat('Y-m', $m)->isoFormat('MMM');
                });
                $monthlyData = $months->map(function ($m) use ($monthlyRaw) {
                    return $monthlyRaw->get($m, 0);
                });

                $categoryData = \App\Models\Ticket::selectRaw('category_id, COUNT(*) as total')
                    ->whereNotNull('category_id')
                    ->groupBy('category_id')
                    ->with('category')
                    ->get();
                $categoryLabels = $categoryData->pluck('category.name');
                $categoryCounts = $categoryData->pluck('total');
                $categoryColors = ['#3b82f6', '#10b981', '#f59e0b', '#f97316', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899'];

                $unitData = \App\Models\Ticket::selectRaw('room_id, COUNT(*) as total')
                    ->whereNotNull('room_id')
                    ->groupBy('room_id')
                    ->with('room.unit')
                    ->get()
                    ->groupBy(fn($item) => $item->room->unit->nama ?? 'Tanpa Unit')
                    ->map(fn($items) => $items->sum('total'))
                    ->sortDesc();
                $unitMax = $unitData->max() ?: 1;

                return view('dashboard', compact(
                    'total', 'menunggu', 'diproses', 'selesai', 'ditolak',
                    'pMenunggu', 'pDiproses', 'pSelesai', 'pDitolak',
                    'monthlyLabels', 'monthlyData',
                    'categoryData', 'categoryLabels', 'categoryCounts', 'categoryColors',
                    'unitData', 'unitMax'
                ));
            })(),
        };
    })->name('dashboard');

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
        Route::resource('tickets', App\Http\Controllers\Admin\TicketController::class);

        // Live search mobile
        Route::get('tickets/mobile-search', [App\Http\Controllers\Admin\TicketController::class, 'mobileSearch'])->name('tickets.mobile-search');

        // Verifikasi
        Route::post('tickets/{ticket}/verify', [App\Http\Controllers\Admin\TicketController::class, 'verify'])->name('tickets.verify');
        Route::post('tickets/{ticket}/reject', [App\Http\Controllers\Admin\TicketController::class, 'reject'])->name('tickets.reject');

        // Internal Comments
        Route::post('tickets/{ticket}/comments', [App\Http\Controllers\Admin\TicketCommentController::class, 'store'])->name('tickets.comments.store');

        // Internal Attachments
        Route::post('tickets/{ticket}/attachments', [App\Http\Controllers\Admin\TicketAttachmentController::class, 'store'])->name('tickets.attachments.store');
        Route::get('tickets/{ticket}/attachments/{attachment}/download', [App\Http\Controllers\Admin\TicketAttachmentController::class, 'download'])->name('tickets.attachments.download');

        // Dispositions
        Route::get('dispositions', [App\Http\Controllers\Admin\DispositionController::class, 'index'])->name('dispositions.index');
        Route::post('dispositions', [App\Http\Controllers\Admin\DispositionController::class, 'store'])->name('dispositions.store');

        Route::resource('units',      App\Http\Controllers\Admin\UnitController::class);
        Route::resource('rooms',      App\Http\Controllers\Admin\RoomController::class);
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
        Route::resource('unit-types', App\Http\Controllers\Admin\UnitTypeController::class);

        // User & Role Management
        Route::resource('users',    App\Http\Controllers\Admin\UserController::class);
        Route::resource('roles',    App\Http\Controllers\Admin\RoleController::class);
        Route::resource('jabatans', App\Http\Controllers\Admin\JabatanController::class);

        // WhatsApp Gateway
        Route::get('whatsapp', [App\Http\Controllers\Admin\WhatsappSettingsController::class, 'index'])->name('whatsapp.index');
        Route::post('whatsapp/start', [App\Http\Controllers\Admin\WhatsappSettingsController::class, 'startServer'])->name('whatsapp.start');
        Route::get('whatsapp/status', [App\Http\Controllers\Admin\WhatsappSettingsController::class, 'checkStatus'])->name('whatsapp.status');
        Route::post('whatsapp/reset', [App\Http\Controllers\Admin\WhatsappSettingsController::class, 'proxyReset'])->name('whatsapp.reset');

        // Workflow Disposisi
        Route::post('workflow/disposisi',              [App\Http\Controllers\Admin\WorkflowController::class, 'disposisi'])->name('workflow.disposisi');
        Route::post('workflow/{history}/eskalasi',     [App\Http\Controllers\Admin\WorkflowController::class, 'eskalasi'])->name('workflow.eskalasi');
        Route::post('workflow/{history}/tangani',      [App\Http\Controllers\Admin\WorkflowController::class, 'tanganiSendiri'])->name('workflow.tangani');
        Route::post('workflow/{history}/selesai',      [App\Http\Controllers\Admin\WorkflowController::class, 'selesai'])->name('workflow.selesai');
        Route::post('workflow/{history}/tutup',        [App\Http\Controllers\Admin\WorkflowController::class, 'tutup'])->name('workflow.tutup');

        // Dashboard Monitoring Direktur (khusus Direktur & Super Admin)
        Route::middleware(App\Http\Middleware\CheckDirektur::class)
            ->prefix('monitoring')
            ->name('monitoring.')
            ->group(function () {
                Route::get('/',           [App\Http\Controllers\Admin\MonitoringController::class, 'index'])->name('index');
                Route::get('ticket/{id}', [App\Http\Controllers\Admin\MonitoringController::class, 'show'])->name('ticket.show');
            });
    });

    // ── HEAD UNIT (legacy) ────────────────────────────────────────────────────
    Route::prefix('head-unit')->name('head-unit.')->group(function () {
        Route::get('dispositions', [App\Http\Controllers\HeadUnit\DispositionController::class, 'index'])->name('dispositions.index');
        Route::get('dispositions/{disposition}', [App\Http\Controllers\HeadUnit\DispositionController::class, 'show'])->name('dispositions.show');
        Route::post('dispositions/{history}/selesai',  [App\Http\Controllers\HeadUnit\DispositionController::class, 'selesai'])->name('dispositions.selesai');
        Route::post('dispositions/{history}/eskalasi', [App\Http\Controllers\HeadUnit\DispositionController::class, 'eskalasi'])->name('dispositions.eskalasi');
        Route::post('dispositions/{disposition}/accept', [App\Http\Controllers\HeadUnit\DispositionController::class, 'accept'])->name('dispositions.accept');
        Route::post('dispositions/{disposition}/handle-self', [App\Http\Controllers\HeadUnit\DispositionController::class, 'handleSelf'])->name('dispositions.handle-self');
        Route::post('dispositions/{disposition}/assign', [App\Http\Controllers\HeadUnit\DispositionController::class, 'assign'])->name('dispositions.assign');
        Route::get('/dalam-penanganan',         [App\Http\Controllers\HeadUnit\DalamPenangananController::class, 'index'])->name('dalam-penanganan');
        Route::get('/riwayat',                  [App\Http\Controllers\HeadUnit\RiwayatController::class, 'index'])->name('riwayat');
    });

    // ── KEPALA UNIT ───────────────────────────────────────────────────────────
    Route::prefix('kepala-unit')->name('kepala-unit.')->group(function () {
        Route::get('/dashboard',               [App\Http\Controllers\KepalaUnit\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dispositions',             [App\Http\Controllers\KepalaUnit\DispositionController::class, 'index'])->name('dispositions.index');
        Route::get('/dispositions/{disposition}', [App\Http\Controllers\KepalaUnit\DispositionController::class, 'show'])->name('dispositions.show');
        Route::post('dispositions/{history}/selesai',  [App\Http\Controllers\KepalaUnit\DispositionController::class, 'selesai'])->name('dispositions.selesai');
        Route::post('dispositions/{history}/eskalasi', [App\Http\Controllers\KepalaUnit\DispositionController::class, 'eskalasi'])->name('dispositions.eskalasi');
        Route::get('/dalam-penanganan',         [App\Http\Controllers\KepalaUnit\DalamPenangananController::class, 'index'])->name('dalam-penanganan');
        Route::get('/riwayat',                  [App\Http\Controllers\KepalaUnit\RiwayatController::class, 'index'])->name('riwayat');
        Route::get('/laporan',                  [App\Http\Controllers\KepalaUnit\LaporanController::class, 'index'])->name('laporan');
        Route::get('/profil',                   [App\Http\Controllers\KepalaUnit\ProfilController::class, 'index'])->name('profil');
    });

    // ── KASI / KASUBBAG ───────────────────────────────────────────────────────
    Route::prefix('kasi')->name('kasi.')->group(function () {
        Route::get('/dashboard',               [App\Http\Controllers\Kasi\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dispositions',             [App\Http\Controllers\Kasi\DispositionController::class, 'index'])->name('dispositions.index');
        Route::get('/dispositions/{disposition}', [App\Http\Controllers\Kasi\DispositionController::class, 'show'])->name('dispositions.show');
        Route::post('dispositions/{history}/selesai',  [App\Http\Controllers\Kasi\DispositionController::class, 'selesai'])->name('dispositions.selesai');
        Route::post('dispositions/{history}/eskalasi', [App\Http\Controllers\Kasi\DispositionController::class, 'eskalasi'])->name('dispositions.eskalasi');
        Route::get('/dalam-penanganan',         [App\Http\Controllers\Kasi\DalamPenangananController::class, 'index'])->name('dalam-penanganan');
        Route::get('/riwayat',                  [App\Http\Controllers\Kasi\RiwayatController::class, 'index'])->name('riwayat');
        Route::get('/laporan',                  [App\Http\Controllers\Kasi\LaporanController::class, 'index'])->name('laporan');
        Route::get('/profil',                   [App\Http\Controllers\Kasi\ProfilController::class, 'index'])->name('profil');
    });

    // ── KABID / KABAG ─────────────────────────────────────────────────────────
    Route::prefix('kabid')->name('kabid.')->group(function () {
        Route::get('/dashboard',               [App\Http\Controllers\Kabid\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dispositions',             [App\Http\Controllers\Kabid\DispositionController::class, 'index'])->name('dispositions.index');
        Route::get('/dispositions/{disposition}', [App\Http\Controllers\Kabid\DispositionController::class, 'show'])->name('dispositions.show');
        Route::post('dispositions/{history}/selesai',  [App\Http\Controllers\Kabid\DispositionController::class, 'selesai'])->name('dispositions.selesai');
        Route::post('dispositions/{history}/eskalasi', [App\Http\Controllers\Kabid\DispositionController::class, 'eskalasi'])->name('dispositions.eskalasi');
        Route::get('/dalam-penanganan',         [App\Http\Controllers\Kabid\DalamPenangananController::class, 'index'])->name('dalam-penanganan');
        Route::get('/riwayat',                  [App\Http\Controllers\Kabid\RiwayatController::class, 'index'])->name('riwayat');
        Route::get('/monitoring',               [App\Http\Controllers\Kabid\MonitoringController::class, 'index'])->name('monitoring');
        Route::get('/laporan',                  [App\Http\Controllers\Kabid\LaporanController::class, 'index'])->name('laporan');
        Route::get('/profil',                   [App\Http\Controllers\Kabid\ProfilController::class, 'index'])->name('profil');
    });

    // ── DIREKTUR ──────────────────────────────────────────────────────────────
    Route::prefix('direktur')->name('direktur.')->group(function () {
        Route::get('/dashboard',               [App\Http\Controllers\Direktur\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/monitoring-workflow',      [App\Http\Controllers\Direktur\MonitoringWorkflowController::class, 'index'])->name('monitoring-workflow');
        Route::get('/statistik',                [App\Http\Controllers\Direktur\StatistikController::class, 'index'])->name('statistik');
        Route::get('/laporan',                  [App\Http\Controllers\Direktur\LaporanController::class, 'index'])->name('laporan');
        Route::get('/audit-trail',              [App\Http\Controllers\Direktur\AuditTrailController::class, 'index'])->name('audit-trail');
        Route::get('/profil',                   [App\Http\Controllers\Direktur\ProfilController::class, 'index'])->name('profil');
    });

});

