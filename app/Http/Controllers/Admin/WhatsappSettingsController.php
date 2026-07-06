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
        $nodePath = $this->findNodePath();
        $phpPath = $this->findPhpPath();
        $basePath = base_path();
        $logPath = storage_path('logs');

        if (!$nodePath) {
            return redirect()->back()->with('error', 'Node.js tidak ditemukan. Pastikan Node.js terinstal dan terdaftar di PATH.');
        }

        $errors = [];

        // 1. WhatsApp Node.js Gateway
        $nodeCmd = sprintf(
            'cd /d "%s\\whatsapp-api" && "%s" index.js',
            $basePath,
            $nodePath
        );
        $nodeFull = sprintf('%s > "%s\\wa-node.log" 2>&1', $nodeCmd, $logPath);
        $result = $this->runBackground($nodeFull);
        if ($result !== true) $errors[] = 'Node.js: ' . $result;

        sleep(1);

        // 2. Queue Worker
        $queueCmd = sprintf(
            'cd /d "%s" && "%s" artisan queue:work --tries=1',
            $basePath,
            $phpPath
        );
        $queueFull = sprintf('%s > "%s\\wa-queue.log" 2>&1', $queueCmd, $logPath);
        $result = $this->runBackground($queueFull);
        if ($result !== true) $errors[] = 'Queue: ' . $result;

        if (!empty($errors)) {
            return redirect()->back()->with('error', 'Gagal menjalankan: ' . implode('; ', $errors));
        }

        return redirect()->back()->with('success', 'Layanan berjalan di background. Tunggu 10-15 detik, QR Code akan muncul otomatis.');
    }

    private function findNodePath(): ?string
    {
        $output = shell_exec('where node 2>NUL');
        if ($output) {
            $paths = explode("\n", trim($output));
            if (!empty($paths[0])) return trim($paths[0]);
        }

        $common = [
            'C:\\Program Files\\nodejs\\node.exe',
            'C:\\Program Files (x86)\\nodejs\\node.exe',
            getenv('LOCALAPPDATA') . '\\Programs\\Nodejs\\node.exe',
        ];
        foreach ($common as $p) {
            if (file_exists($p)) return $p;
        }

        return null;
    }

    private function findPhpPath(): string
    {
        if (defined('PHP_BINARY') && PHP_BINARY) {
            return PHP_BINARY;
        }
        $output = shell_exec('where php 2>NUL');
        if ($output) {
            $paths = explode("\n", trim($output));
            if (!empty($paths[0])) return trim($paths[0]);
        }
        return 'php';
    }

    private function runBackground(string $command): true|string
    {
        // Metode 1: COM WScript.Shell — hidden window, paling reliable di Windows Server
        if (class_exists('\\COM', false)) {
            try {
                $shell = new \COM('WScript.Shell');
                $shell->Run($command, 0, false);
                return true;
            } catch (\Throwable $e) {
                // fallthrough
            }
        }

        // Metode 2: start /B via popen
        $handle = popen('start /B "" cmd /c "' . $command . '"', 'r');
        if ($handle !== false) {
            pclose($handle);
            return true;
        }

        return 'Tidak dapat membuat proses background. Cek disable_functions di php.ini.';
    }
}
