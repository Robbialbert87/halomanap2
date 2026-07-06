<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsappSettingsController extends Controller
{
    public function index()
    {
        return view('admin.whatsapp.index');
    }

    public function startServer()
    {
        $nodePath = $this->findNodePath();
        $phpPath  = $this->findPhpPath();
        $basePath = base_path();
        $logPath  = storage_path('logs');

        if (!$nodePath) {
            return redirect()->back()->with('error', 'Node.js tidak ditemukan.');
        }

        $errors = [];

        // 1. Kill existing node process biar gak bentrok port
        exec('taskkill /F /IM node.exe 2>NUL', $killOut, $killCode);

        sleep(1);

        // 2. Start Node.js - pakai start /B (background, no window)
        $nodeCmd = sprintf(
            'start /B "" cmd /c "cd /d "%s\\whatsapp-api" && "%s" index.js > "%s\\wa-node.log" 2>&1"',
            $basePath, $nodePath, $logPath
        );
        exec($nodeCmd, $nodeOut, $nodeExit);
        if ($nodeExit != 0) {
            $errors[] = "Node.js exec gagal (exit: $nodeExit)";
        }

        sleep(1);

        // 3. Start Queue Worker
        $queueCmd = sprintf(
            'start /B "" cmd /c "cd /d "%s" && "%s" artisan queue:work --queue=notifications --tries=1 > "%s\\wa-queue.log" 2>&1"',
            $basePath, $phpPath, $logPath
        );
        exec($queueCmd, $queueOut, $queueExit);
        if ($queueExit != 0) {
            $errors[] = "Queue exec gagal (exit: $queueExit)";
        }

        if (!empty($errors)) {
            return redirect()->back()->with('error', 'Gagal: ' . implode('; ', $errors) . '. Double-click file <strong>start-services.bat</strong> di folder project.');
        }

        // Verify port 3000
        sleep(3);
        try {
            $check = @Http::timeout(2)->get('http://localhost:3000/status');
            if ($check->successful()) {
                return redirect()->back()->with('success', 'Layanan berjalan! Tunggu QR Code muncul...');
            }
        } catch (\Throwable $e) {
            // not running yet
        }

        return redirect()->back()->with('warning',
            'Perintah sudah dijalankan tapi port 3000 belum merespon. Tunggu beberapa saat, atau double-click <strong>start-services.bat</strong> di folder project untuk manual.'
        );
    }

    public function checkStatus()
    {
        try {
            $resp = Http::timeout(3)->get('http://localhost:3000/status');
            if ($resp->successful()) {
                return response()->json(['running' => true, 'data' => $resp->json()]);
            }
        } catch (\Throwable $e) {
            //
        }
        return response()->json(['running' => false]);
    }

    private function findNodePath(): ?string
    {
        if (function_exists('shell_exec')) {
            $output = shell_exec('where node 2>NUL');
            if ($output) {
                $paths = explode("\n", trim($output));
                if (!empty($paths[0])) return trim($paths[0]);
            }
        }

        $common = [
            'C:\\Program Files\\nodejs\\node.exe',
            'C:\\Program Files (x86)\\nodejs\\node.exe',
            getenv('LOCALAPPDATA') . '\\Programs\\Nodejs\\node.exe',
            getenv('PROGRAMFILES') . '\\nodejs\\node.exe',
            getenv('PROGRAMFILES(X86)') . '\\nodejs\\node.exe',
        ];
        foreach ($common as $p) {
            if ($p && file_exists($p)) return $p;
        }

        return null;
    }

    private function findPhpPath(): string
    {
        if (defined('PHP_BINARY') && PHP_BINARY) return PHP_BINARY;
        if (function_exists('shell_exec')) {
            $output = shell_exec('where php 2>NUL');
            if ($output) {
                $paths = explode("\n", trim($output));
                if (!empty($paths[0])) return trim($paths[0]);
            }
        }
        return 'php';
    }
}
