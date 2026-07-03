<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WhatsappSettingsController extends Controller
{
    public function index()
    {
        return view('admin.whatsapp.index');
    }

    public function startServer()
    {
        $basePath = str_replace('/', '\\', base_path());
        $logPath  = str_replace('/', '\\', storage_path('logs'));

        // Tulis file .bat untuk menjalankan Node.js API
        $nodePath = 'C:\\Program Files\\nodejs\\node.exe';
        $batNode = storage_path('app/start-wa-node.bat');
        file_put_contents($batNode,
            "@echo off\r\n" .
            "cd /d \"{$basePath}\\whatsapp-api\"\r\n" .
            "echo Starting WhatsApp API Gateway...\r\n" .
            "\"{$nodePath}\" index.js\r\n" .
            "pause\r\n"
        );

        // Tulis file .bat untuk menjalankan Queue Worker
        $phpPath = 'C:\\Program Files\\PHP\\php.exe'; // path default, akan dicari dinamis
        $batQueue = storage_path('app/start-wa-queue.bat');
        file_put_contents($batQueue,
            "@echo off\r\n" .
            "cd /d \"{$basePath}\"\r\n" .
            "echo Starting Laravel Queue Worker...\r\n" .
            "php artisan queue:work\r\n" .
            "pause\r\n"
        );

        // Jalankan kedua .bat secara background (muncul jendela CMD agar bisa dipantau)
        $winBatNode  = str_replace('/', '\\', $batNode);
        $winBatQueue = str_replace('/', '\\', $batQueue);

        pclose(popen("start \"WA Node API\" cmd /k \"{$winBatNode}\"", 'r'));
        sleep(1); // Beri jeda sebelum menjalankan queue
        pclose(popen("start \"WA Queue Worker\" cmd /k \"{$winBatQueue}\"", 'r'));

        return redirect()->back()->with('success', 'Server sedang dijalankan! Dua jendela CMD muncul dan perintah berjalan otomatis. Tunggu 10-15 detik lalu QR Code akan tampil di halaman ini.');
    }
}

