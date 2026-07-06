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
            return redirect()->back()->with('error', 'Node.js tidak ditemukan. Pastikan Node.js terinstal dan terdaftar di PATH. Cek "where node" di CMD server.');
        }

        $errors = [];

        // 1. WhatsApp Node.js Gateway
        $nodeCmd = sprintf('cd /d "%s\\whatsapp-api" && "%s" index.js', $basePath, $nodePath);
        $nodeFull = sprintf('%s > "%s\\wa-node.log" 2>&1', $nodeCmd, $logPath);
        $result = $this->runBackground($nodeFull);
        if ($result !== true) $errors[] = 'Node.js: ' . $result;

        sleep(1);

        // 2. Queue Worker
        $queueCmd = sprintf('cd /d "%s" && "%s" artisan queue:work --queue=notifications --tries=1', $basePath, $phpPath);
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
        $cmdLine = 'cmd /c ' . $command;

        // Metode 1: VBScript via wscript.exe (COM via file, built-in Windows, no window)
        $vbsPath = storage_path('app/run-bg.vbs');
        $vbsContent = 'CreateObject("WScript.Shell").Run "' . str_replace('"', '""', $cmdLine) . '", 0, False';
        file_put_contents($vbsPath, $vbsContent . "\r\n");

        $proc = proc_open(
            'wscript.exe //Nologo "' . $vbsPath . '"',
            [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes
        );

        if (is_resource($proc)) {
            fclose($pipes[0]);
            $exitCode = proc_close($proc);
            @unlink($vbsPath);
            if ($exitCode === 0) return true;
        }

        // Metode 2: PowerShell Start-Process (hidden window)
        $psCmd = 'powershell -NoProfile -ExecutionPolicy Bypass -Command "Start-Process -WindowStyle Hidden -FilePath cmd.exe -ArgumentList \'/c ' . $command . '\'"';
        exec($psCmd, $psOut, $psExit);
        if ($psExit === 0) {
            @unlink($vbsPath);
            return true;
        }

        // Metode 3: start /B via popen (last resort, mungkin flash window)
        $handle = popen('start /B "" cmd /c "' . $command . '"', 'r');
        if ($handle !== false) {
            pclose($handle);
            @unlink($vbsPath);
            return true;
        }

        @unlink($vbsPath);
        return 'Tidak dapat membuat proses background. Cek apakah proc_open/exec aktif di php.ini.';
    }
}
