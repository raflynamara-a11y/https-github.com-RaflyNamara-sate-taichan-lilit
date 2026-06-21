@echo off
REM ========================================================================
REM SCRIPT RESTORE DATABASE SATE TAICAN LILIT
REM ========================================================================

setlocal enabledelayedexpansion

color 0A

echo ========================================================================
echo   RESTORE DATABASE SATE TAICAN LILIT
echo ========================================================================
echo.

REM Cari XAMPP
if exist "C:\xampp" (
    set XAMPP_PATH=C:\xampp
) else if exist "D:\xampp" (
    set XAMPP_PATH=D:\xampp
) else (
    echo Tidak menemukan XAMPP!
    pause
    exit /b 1
)

set MYSQL_PATH=!XAMPP_PATH!\mysql\bin\mysql.exe
set BACKUP_DIR=%~dp0\backups

echo Cari file backup...
if not exist "!BACKUP_DIR!" (
    echo Folder backups tidak ditemukan!
    pause
    exit /b 1
)

echo Daftar backup tersedia:
echo.
dir /B "!BACKUP_DIR!" | findstr /R "\.sql$"
echo.
set /p BACKUP_FILE=Masukkan nama file backup (tanpa path): 

if not exist "!BACKUP_DIR!\!BACKUP_FILE!" (
    echo File tidak ditemukan!
    pause
    exit /b 1
)

echo.
echo Restore dimulai...
!MYSQL_PATH! -u root sate_taican_db < "!BACKUP_DIR!\!BACKUP_FILE!"

if errorlevel 0 (
    echo ✓ Restore berhasil!
) else (
    echo ✗ Restore gagal!
)

echo.
pause
