<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsappSettingsController extends Controller
{
    public function index()
    {
        $nodePath = $this->findNodePath();
        $phpPath = $this->findPhpPath();
        $basePath = base_path();

        // Generate .ps1 untuk manual run
        $psScript = sprintf(<<<'PS'
$logDir = "%s\storage\logs"
Start-Process -WindowStyle Hidden -FilePath "cmd.exe" -ArgumentList "/c cd /d `"%s\whatsapp-api`" && `"%s`" index.js > `"$logDir\wa-node.log`" 2>&1"
Start-Sleep -Seconds 2
Start-Process -WindowStyle Hidden -FilePath "cmd.exe" -ArgumentList "/c cd /d `"%s`" && `"%s`" artisan queue:work --queue=notifications --tries=1 > `"$logDir\wa-queue.log`" 2>&1"
PS
, $basePath, $basePath, $nodePath, $basePath, $phpPath);

        file_put_contents(storage_path('app/start-services.ps1'), $psScript);

        return view('admin.whatsapp.index', [
            'nodeFound' => $nodePath !== null,
            'nodePath'  => $nodePath,
            'phpPath'   => $phpPath,
        ]);
    }

    public function startServer()
    {
        $nodePath = $this->findNodePath();
        $phpPath  = $this->findPhpPath();
        $basePath = base_path();
        $logPath  = storage_path('logs');
        $debugLog = $logPath . '\wa-debug.log';

        $this->debug($debugLog, '=== START SERVER ===');
        $this->debug($debugLog, 'Base path: ' . $basePath);
        $this->debug($debugLog, 'Node path: ' . ($nodePath ?? 'NULL'));
        $this->debug($debugLog, 'PHP path: ' . $phpPath);

        if (!$nodePath) {
            return redirect()->back()->with('error', 'Node.js tidak ditemukan.');
        }

        $errors = [];

        // 1. Node.js Gateway
        $nodeCmd = sprintf('cd /d "%s\\whatsapp-api" && "%s" index.js', $basePath, $nodePath);
        $nodeFull = sprintf('%s > "%s\\wa-node.log" 2>&1', $nodeCmd, $logPath);
        $this->debug($debugLog, 'Starting Node: ' . $nodeFull);

        $result = $this->runBackground($nodeFull, $debugLog);
        if ($result !== true) {
            $errors[] = 'Node.js: ' . $result;
        } else {
            $this->debug($debugLog, 'Node.js started OK');
        }

        sleep(1);

        // 2. Queue Worker
        $queueCmd = sprintf('cd /d "%s" && "%s" artisan queue:work --queue=notifications --tries=1', $basePath, $phpPath);
        $queueFull = sprintf('%s > "%s\\wa-queue.log" 2>&1', $queueCmd, $logPath);
        $this->debug($debugLog, 'Starting Queue: ' . $queueFull);

        $result = $this->runBackground($queueFull, $debugLog);
        if ($result !== true) {
            $errors[] = 'Queue: ' . $result;
        } else {
            $this->debug($debugLog, 'Queue started OK');
        }

        if (!empty($errors)) {
            $this->debug($debugLog, 'ERRORS: ' . implode('; ', $errors));
            return redirect()->back()->with('error', 'Gagal: ' . implode('; ', $errors) . '. Cek ' . $debugLog);
        }

        return redirect()->back()->with('success', 'Layanan berjalan di background. Tunggu 10-15 detik. Cek log: ' . $debugLog);
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
        if (defined('PHP_BINARY') && PHP_BINARY) {
            return PHP_BINARY;
        }
        if (function_exists('shell_exec')) {
            $output = shell_exec('where php 2>NUL');
            if ($output) {
                $paths = explode("\n", trim($output));
                if (!empty($paths[0])) return trim($paths[0]);
            }
        }
        return 'php';
    }

    private function runBackground(string $command, string $debugLog): true|string
    {
        $cmdLine = 'cmd /c ' . $command;

        // Method 1: start /B via exec (most compatible)
        $this->debug($debugLog, '  Trying exec start /B...');
        $fullExec = sprintf('start /B "" cmd /c "%s"', $command);
        exec($fullExec, $out, $exitCode);
        $this->debug($debugLog, "  exec exit code: $exitCode");
        if ($exitCode === 0) return true;

        // Method 2: VBScript via wscript.exe
        $this->debug($debugLog, '  Trying VBScript...');
        $wscript = getenv('SystemRoot') . '\\System32\\wscript.exe';
        if (!file_exists($wscript)) $wscript = 'C:\\Windows\\System32\\wscript.exe';
        $this->debug($debugLog, "  wscript: $wscript");

        $vbsPath = storage_path('app/run-bg.vbs');
        $escaped = str_replace('"', '""', $cmdLine);
        file_put_contents($vbsPath, 'CreateObject("WScript.Shell").Run "' . $escaped . '", 0, False' . "\r\n");

        $proc = @proc_open(
            '"' . $wscript . '" //Nologo "' . $vbsPath . '"',
            [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes
        );

        if (is_resource($proc)) {
            fclose($pipes[0]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            $code = proc_close($proc);
            $this->debug($debugLog, "  VBScript exit code: $code");
            @unlink($vbsPath);
            if ($code === 0) return true;
        } else {
            $this->debug($debugLog, '  VBScript proc_open failed');
        }

        // Method 3: PowerShell
        $this->debug($debugLog, '  Trying PowerShell...');
        $psCmd = sprintf(
            'powershell -NoProfile -ExecutionPolicy Bypass -Command "Start-Process -WindowStyle Hidden -FilePath cmd.exe -ArgumentList \'/c %s\'"',
            str_replace('"', '\\"', $command)
        );
        exec($psCmd, $psOut, $psExit);
        $this->debug($debugLog, "  PowerShell exit code: $psExit");
        if ($psExit === 0) {
            @unlink($vbsPath);
            return true;
        }

        @unlink($vbsPath);

        // Check if functions are disabled
        $disabled = ini_get('disable_functions');
        $this->debug($debugLog, '  disable_functions: ' . ($disabled ?: '(none)'));

        return 'Semua metode gagal. Coba jalankan manual: ' . $command;
    }

    private function debug(string $log, string $msg): void
    {
        @file_put_contents($log, '[' . date('Y-m-d H:i:s') . '] ' . $msg . PHP_EOL, FILE_APPEND);
    }
}
