@echo off
REM ========================================================================
REM SETUP OTOMATIS SATE TAICAN LILIT - PHP + XAMPP + MySQL
REM ========================================================================
REM Script ini akan:
REM 1. Deteksi lokasi XAMPP
REM 2. Copy project ke htdocs
REM 3. Mulai Apache & MySQL
REM 4. Import database
REM 5. Buka browser ke website
REM ========================================================================

SETLOCAL ENABLEDELAYEDEXPANSION

REM Set warna output
color 0A

echo.
echo ========================================================================
echo   SETUP OTOMATIS SATE TAICAN LILIT
echo ========================================================================
echo.

REM Deteksi XAMPP installation
echo [1/6] Mencari instalasi XAMPP...
if exist "C:\xampp" (
    set XAMPP_PATH=C:\xampp
    echo ✓ XAMPP ditemukan di: !XAMPP_PATH!
) else if exist "D:\xampp" (
    set XAMPP_PATH=D:\xampp
    echo ✓ XAMPP ditemukan di: !XAMPP_PATH!
) else (
    echo ✗ XAMPP tidak ditemukan!
    echo   Silakan install XAMPP terlebih dahulu di C:\xampp atau D:\xampp
    pause
    exit /b 1
)

REM Define paths
set PROJECT_NAME=Sate Taichan Lilit
set SOURCE_DIR=%~dp0
set DEST_DIR=!XAMPP_PATH!\htdocs\%PROJECT_NAME%
set MYSQL_PATH=!XAMPP_PATH!\mysql\bin\mysql.exe
set XAMPP_CONTROL=!XAMPP_PATH!\xampp-control.exe

echo.
echo [2/6] Copy project ke htdocs...
if exist "!DEST_DIR!" (
    echo ✓ Folder sudah ada di htdocs
) else (
    mkdir "!DEST_DIR!"
    xcopy "!SOURCE_DIR!" "!DEST_DIR!" /E /I /Y >nul
    echo ✓ Project dicopy ke: !DEST_DIR!
)

echo.
echo [3/6] Menjalankan Apache...
tasklist | find /i "apache" >nul
if errorlevel 1 (
    start "" "!XAMPP_CONTROL!"
    echo ✓ XAMPP Control Panel dibuka
    echo   Silakan klik START pada Apache dan MySQL
    echo   Tunggu sampai berwarna HIJAU
    echo.
    echo   Tekan tombol apa saja ketika sudah siap...
    pause
) else (
    echo ✓ Apache sudah berjalan
)

echo.
echo [4/6] Menjalankan MySQL...
tasklist | find /i "mysqld" >nul
if errorlevel 1 (
    echo ⚠  MySQL belum berjalan. Silakan jalankan dari XAMPP Control Panel.
    pause
) else (
    echo ✓ MySQL sudah berjalan
)

echo.
echo [5/6] Mengimport database...

REM Check if database already exists
!MYSQL_PATH! -u root -e "USE sate_taican_db" 2>nul
if not errorlevel 1 (
    echo ✓ Database sudah ada
) else (
    if exist "!DEST_DIR!\db\database.sql" (
        !MYSQL_PATH! -u root < "!DEST_DIR!\db\database.sql" >nul 2>&1
        if errorlevel 0 (
            echo ✓ Database berhasil diimport
        ) else (
            echo ⚠  Gagal import database. Silakan import manual di phpMyAdmin
        )
    ) else (
        echo ⚠  File database.sql tidak ditemukan
    )
)

echo.
echo [6/6] Membuka browser...

timeout /t 2 /nobreak >nul

REM Open browser
set CHROME_PATH=C:\Program Files\Google\Chrome\Application\chrome.exe
set FIREFOX_PATH=C:\Program Files\Mozilla Firefox\firefox.exe
set EDGE_PATH=C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe

if exist "!CHROME_PATH!" (
    start "" "!CHROME_PATH!" "http://localhost/Sate%%20Taichan%%20Lilit/"
) else if exist "!FIREFOX_PATH!" (
    start "" "!FIREFOX_PATH!" "http://localhost/Sate%%20Taichan%%20Lilit/"
) else if exist "!EDGE_PATH!" (
    start "" "!EDGE_PATH!" "http://localhost/Sate%%20Taichan%%20Lilit/"
) else (
    start http://localhost/Sate%%20Taichan%%20Lilit/
)

echo ✓ Browser dibuka
echo.
echo ========================================================================
echo   SETUP SELESAI!
echo ========================================================================
echo.
echo Akses website: http://localhost/Sate%%20Taichan%%20Lilit/
echo Admin panel:   http://localhost/Sate%%20Taichan%%20Lilit/admin/
echo Database:      http://localhost/phpmyadmin/
echo Status:        http://localhost/Sate%%20Taichan%%20Lilit/status.php
echo.
pause
