@echo off
REM ========================================================================
REM SCRIPT BACKUP DATABASE SATE TAICAN LILIT
REM ========================================================================

setlocal enabledelayedexpansion

color 0A

echo ========================================================================
echo   BACKUP DATABASE SATE TAICAN LILIT
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

set MYSQL_PATH=!XAMPP_PATH!\mysql\bin\mysqldump.exe
set BACKUP_DIR=%~dp0\backups
set TIMESTAMP=%date:~6,4%-%date:~3,2%-%date:~0,2%_%time:~0,2%-%time:~3,2%-%time:~6,2%
set BACKUP_FILE=!BACKUP_DIR!\sate_taican_backup_%TIMESTAMP:.=-%.sql

if not exist "!BACKUP_DIR!" mkdir "!BACKUP_DIR!"

echo Backup dimulai...
echo Lokasi: !BACKUP_FILE!
echo.

!MYSQL_PATH! -u root sate_taican_db > "!BACKUP_FILE!"

if errorlevel 0 (
    echo ✓ Backup berhasil dibuat!
    echo File: !BACKUP_FILE!
) else (
    echo ✗ Backup gagal!
)

echo.
pause
