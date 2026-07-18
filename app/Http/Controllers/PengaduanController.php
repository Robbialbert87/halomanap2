<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Room;
use App\Models\ReportCategory;
use App\Models\Ticket;
use App\Models\User;
use App\Jobs\SendWhatsAppNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PengaduanController extends Controller
{
    public function create(Request $request)
    {
        $units      = Unit::where('status', 'active')->orderBy('nama')->get();
        $rooms      = Room::orderBy('name')->get()->groupBy('unit_id');
        $categories = ReportCategory::orderBy('name')->get();

        $type = in_array($request->query('type'), ['Pengaduan', 'Survei', 'Apresiasi', 'Informasi'])
            ? $request->query('type')
            : 'Pengaduan';

        return view('pengaduan.create', compact('units', 'rooms', 'categories', 'type'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required',
            'room_id' => 'required',
            'category_id' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,heic,heif,webp|max:20480',
            'is_anonymous' => 'nullable|boolean',
        ]);

        $isAnonymous = $request->has('is_anonymous');
        
        if (!$isAnonymous) {
            $request->validate([
                'reporter_name' => 'required|string|max:255',
                'reporter_phone' => 'required|string|max:20',
                'reporter_email' => 'nullable|email|max:255',
            ]);
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            try {
                if (!$file->isValid()) {
                    $errorCode = $file->getError();
                    throw new \RuntimeException(match ($errorCode) {
                        UPLOAD_ERR_INI_SIZE     => 'Ukuran file melebihi batas maksimum server',
                        UPLOAD_ERR_FORM_SIZE    => 'Ukuran file melebihi batas maksimum form',
                        UPLOAD_ERR_PARTIAL      => 'File hanya terupload sebagian',
                        UPLOAD_ERR_NO_FILE      => 'Tidak ada file yang dipilih',
                        UPLOAD_ERR_NO_TMP_DIR   => 'Folder temporary tidak ditemukan',
                        UPLOAD_ERR_CANT_WRITE   => 'Gagal menulis file ke disk',
                        UPLOAD_ERR_EXTENSION    => 'Upload file dihentikan oleh ekstensi',
                        default                 => 'File gagal diupload (kode: ' . $errorCode . ')',
                    });
                }
                $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'heic', 'heif', 'pdf'])) {
                    $ext = 'jpg';
                }
                $filename = Str::random(40) . '.' . $ext;
                $attachmentPath = $file->storeAs('attachments', $filename, 'public');
            } catch (\Throwable $e) {
                return back()->withErrors(['attachment' => 'Gagal mengupload file: ' . $e->getMessage()])->withInput();
            }
        }

        // Generate Ticket Number HM + YYMMDD + XXXX
        $datePrefix = Carbon::now()->format('ymd');
        $lastTicket = Ticket::where('ticket_number', 'like', 'HM' . $datePrefix . '%')
            ->orderBy('id', 'desc')
            ->first();
            
        $sequence = 1;
        if ($lastTicket) {
            $lastSequence = (int) substr($lastTicket->ticket_number, -4);
            $sequence = $lastSequence + 1;
        }
        
        $ticketNumber = 'HM' . $datePrefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'type' => in_array($request->input('type'), ['Pengaduan', 'Survei', 'Apresiasi', 'Informasi']) ? $request->input('type') : 'Pengaduan',
            'category_id' => $request->category_id,
            'room_id' => $request->room_id,
            'is_anonymous' => $isAnonymous,
            'reporter_name' => $isAnonymous ? null : $request->reporter_name,
            'reporter_phone' => $isAnonymous ? null : $request->reporter_phone,
            'title' => $request->title,
            'description' => $request->description,
            'attachment_path' => $attachmentPath,
            'status' => 'NEW'
        ]);

        // Kirim WA ke Admin Pengaduan
        $this->notifyAdmins($ticket);

        // Kirim WA ke Pelapor
        if ($ticket->reporter_phone) {
            $pesanPelapor = implode("\n", [
                "*HALO MANAP - Pengaduan Terdaftar*",
                "─────────────────────",
                "📋 *No Tiket:* {$ticket->ticket_number}",
                "📝 *Judul:* {$ticket->title}",
                "",
                "Simpan nomor tiket untuk melacak status.",
                "🔗 " . route('pengaduan.track') . '?ticket_number=' . $ticket->ticket_number,
                "─────────────────────",
                "_RSUD H. Abdul Manap Kota Jambi_",
            ]);
            SendWhatsAppNotification::dispatch($ticket->reporter_phone, $pesanPelapor);
        }

        return redirect()->route('pengaduan.success', ['ticket_number' => $ticket->ticket_number]);
    }

    public function success($ticket_number)
    {
        $ticket = Ticket::where('ticket_number', $ticket_number)->firstOrFail();
        return view('pengaduan.success', compact('ticket'));
    }

    public function downloadTicket($ticket_number)
    {
        $ticket = Ticket::where('ticket_number', $ticket_number)->firstOrFail();
        $pdf = Pdf::loadView('pengaduan.ticket-pdf', compact('ticket'));
        return $pdf->download('tiket-' . $ticket->ticket_number . '.pdf');
    }

    public function track(Request $request)
    {
        $ticket = null;
        $notFound = false;

        if ($request->filled('ticket_number')) {
            $ticket = Ticket::with([
                'room',
                'category',
                'histories.user',
                'workflows' => fn($q) => $q
                    ->with(['toUser.jabatan', 'fromUser', 'toJabatan', 'toUnit'])
                    ->orderBy('created_at', 'asc'),
            ])
                ->where('ticket_number', strtoupper(trim($request->ticket_number)))
                ->first();

            if (!$ticket) {
                $notFound = true;
            }
        }

        $timeline = [];
        $activeWorkflow = null;
        $completion = null;

        if ($ticket) {
            // Build timeline from TicketHistory + WorkflowHistory
            $histories = $ticket->histories ?? collect();
            $workflows = $ticket->workflows ?? collect();

            // Find TicketHistory entry for verification event
            $verifiedHistory = $histories->firstWhere('new_status', 'TERVERIFIKASI');

            // 1. Diterima (from ticket creation)
            $timeline[] = [
                'type' => 'diterima',
                'label' => 'Diterima',
                'time' => $ticket->created_at,
                'icon' => 'fa-paper-plane',
                'user' => null,
            ];

            // 2. Diverifikasi (from TicketHistory)
            if ($verifiedHistory) {
                $timeline[] = [
                    'type' => 'diverifikasi',
                    'label' => 'Diverifikasi',
                    'time' => $verifiedHistory->created_at,
                    'icon' => 'fa-shield-check',
                    'user' => $verifiedHistory->user,
                ];
            }

            // 3. Workflow steps
            foreach ($workflows as $wf) {
                $isActiveWf = $activeWorkflow && $activeWorkflow->id === $wf->id;

                $entry = [
                    'type' => $wf->action,
                    'label' => $wf->action_label,
                    'time' => $wf->created_at,
                    'icon' => match ($wf->action) {
                        'disposisi' => 'fa-arrow-right',
                        'eskalasi' => 'fa-share',
                        'tangani_sendiri' => 'fa-hand',
                        'selesai' => 'fa-circle-check',
                        'tutup' => 'fa-lock',
                        'ditolak' => 'fa-circle-xmark',
                        default => 'fa-circle',
                    },
                    'user' => $wf->toUser,
                    'jabatan' => $wf->toJabatan,
                    'fromUser' => $wf->fromUser,
                    'komentar' => $wf->komentar,
                    'status' => $wf->status,
                    'completed_at' => $wf->completed_at,
                    'is_active' => $isActiveWf,
                ];

                $timeline[] = $entry;

                // Capture completion report
                if ($wf->action === 'selesai' && $wf->komentar) {
                    $completion = $entry;
                }
            }

            // Active workflow (non-terminal)
            $activeWorkflow = $workflows
                ->whereIn('status', ['menunggu_respon', 'dalam_penanganan'])
                ->last();
        }

        return view('pengaduan.track', compact('ticket', 'notFound', 'timeline', 'activeWorkflow', 'completion'));
    }

    private function notifyAdmins(Ticket $ticket): void
    {
        $admins = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['Super Admin', 'Admin Pengaduan']);
        })->where('status', 'active')->whereNotNull('phone_number')->get();

        if ($admins->isEmpty()) return;

        $nama = $ticket->is_anonymous ? 'Anonim' : ($ticket->reporter_name ?? '-');
        $pesan = implode("\n", [
            "*HALO MANAP - Pengaduan Baru*",
            "─────────────────────",
            "📋 *No:* {$ticket->ticket_number}",
            "📝 *Judul:* {$ticket->title}",
            "👤 *Pelapor:* {$nama}",
            "",
            "Silakan login untuk memverifikasi.",
            "🔗 " . route('admin.tickets.index'),
            "─────────────────────",
            "_RSUD H. Abdul Manap Kota Jambi_",
        ]);

        foreach ($admins as $admin) {
            SendWhatsAppNotification::dispatch($admin->phone_number, $pesan);
        }
    }
}
