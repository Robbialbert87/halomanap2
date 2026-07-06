@echo off
title HaloMANAP - Services
cd /d "%~dp0"
echo ============================================
echo  HaloMANAP - Starting Services
echo ============================================
echo.
echo [1/2] Starting WhatsApp Node.js API (port 3000)...
start "WA Node" cmd /c "cd /d "%~dp0whatsapp-api" && node index.js"
if %errorlevel% neq 0 (
    echo ERROR: Failed to start Node.js!
    pause
    exit /b 1
)
timeout /t 3 /nobreak >nul

echo [2/2] Starting Queue Worker...
start "WA Queue" cmd /c "cd /d "%~dp0" && php artisan queue:work --queue=notifications --tries=1"

echo.
echo ============================================
echo  Both services started successfully!
echo.
echo  - Node.js API: http://localhost:3000
echo  - Queue Worker: processing notifications
echo.
echo  DO NOT CLOSE these CMD windows!
echo ============================================
echo.
pause
