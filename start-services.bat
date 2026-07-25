@echo off
title HaloMANAP - Queue Worker
cd /d "%~dp0"
echo ============================================
echo  HaloMANAP - Starting Queue Worker
echo ============================================
echo.
echo [1/1] Starting Queue Worker...
start "WA Queue" cmd /c "cd /d "%~dp0" && php artisan queue:work --queue=notifications --tries=1"
echo.
echo ============================================
echo  Queue Worker started!
echo.
echo  WhatsApp menggunakan WAHA eksternal.
echo ============================================
echo.
pause
