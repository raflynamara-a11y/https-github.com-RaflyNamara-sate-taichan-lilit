# ========================================================================
# SATE TAICAN LILIT - AUTOMATIC SETUP SCRIPT (PowerShell)
# ========================================================================
# Usage: powershell -ExecutionPolicy Bypass -File setup.ps1
# ========================================================================

$ErrorActionPreference = "Stop"

# Colors
$Green = [ConsoleColor]::Green
$Red = [ConsoleColor]::Red
$Yellow = [ConsoleColor]::Yellow
$Blue = [ConsoleColor]::Blue
$Cyan = [ConsoleColor]::Cyan

function Write-Status {
    param($Message, $Status = "INFO")
    $Color = $Green
    $Icon = "ℹ️"
    
    if ($Status -eq "ERROR") { $Color = $Red; $Icon = "❌" }
    elseif ($Status -eq "WARN") { $Color = $Yellow; $Icon = "⚠️" }
    elseif ($Status -eq "OK") { $Color = $Green; $Icon = "✅" }
    elseif ($Status -eq "PROCESS") { $Color = $Cyan; $Icon = "⏳" }
    
    Write-Host "$Icon $Message" -ForegroundColor $Color
}

Write-Host "`n" -ForegroundColor $Blue
Write-Host "========================================================================" -ForegroundColor $Blue
Write-Host "  SATE TAICAN LILIT - AUTOMATIC SETUP" -ForegroundColor $Blue
Write-Host "========================================================================" -ForegroundColor $Blue
Write-Host "`n"

# Step 1: Find XAMPP Installation
Write-Status "Mencari instalasi XAMPP..." "PROCESS"

$XamppPath = $null
if (Test-Path "C:\xampp") {
    $XamppPath = "C:\xampp"
} elseif (Test-Path "D:\xampp") {
    $XamppPath = "D:\xampp"
} elseif (Test-Path "E:\xampp") {
    $XamppPath = "E:\xampp"
}

if (-not $XamppPath) {
    Write-Status "XAMPP tidak ditemukan! Silakan install XAMPP terlebih dahulu." "ERROR"
    exit 1
}
Write-Status "XAMPP ditemukan di: $XamppPath" "OK"

# Step 2: Define Paths
$ProjectName = "Sate Taichan Lilit"
$SourceDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$DestDir = "$XamppPath\htdocs\$ProjectName"
$MysqlPath = "$XamppPath\mysql\bin\mysql.exe"
$XamppControl = "$XamppPath\xampp-control.exe"

Write-Host "`n"

# Step 3: Copy Project
Write-Status "Copy project ke htdocs..." "PROCESS"

if (Test-Path $DestDir) {
    Write-Status "Folder sudah ada, skip copy" "WARN"
} else {
    Copy-Item -Path $SourceDir -Destination $DestDir -Recurse -Force
    Write-Status "Project berhasil dicopy ke: $DestDir" "OK"
}

Write-Host "`n"

# Step 4: Check Services
Write-Status "Mengecek layanan..." "PROCESS"

$ApacheRunning = Get-Process httpd -ErrorAction SilentlyContinue
$MysqlRunning = Get-Process mysqld -ErrorAction SilentlyContinue

if (-not $ApacheRunning) {
    Write-Status "Apache belum berjalan. Membuka XAMPP Control Panel..." "WARN"
    Start-Process $XamppControl
    Write-Host "Silakan klik START pada Apache dan MySQL, tunggu sampai HIJAU`n" -ForegroundColor $Yellow
    Read-Host "Tekan Enter ketika sudah siap"
} else {
    Write-Status "Apache sudah berjalan" "OK"
}

if (-not $MysqlRunning) {
    Write-Status "MySQL belum berjalan. Buka XAMPP Control Panel untuk menjalankannya." "WARN"
    Read-Host "Tekan Enter setelah MySQL berjalan"
} else {
    Write-Status "MySQL sudah berjalan" "OK"
}

Write-Host "`n"

# Step 5: Import Database
Write-Status "Mengimport database..." "PROCESS"

$DbSqlFile = "$DestDir\db\database.sql"

if (-not (Test-Path $DbSqlFile)) {
    Write-Status "File database.sql tidak ditemukan" "ERROR"
    exit 1
}

# Check if database exists
& $MysqlPath -u root -e "USE sate_taican_db" *>$null 2>&1
if ($LASTEXITCODE -eq 0) {
    Write-Status "Database sudah ada, skip import" "WARN"
} else {
    Get-Content $DbSqlFile | & $MysqlPath -u root
    Write-Status "Database berhasil diimport" "OK"
}

Write-Host "`n"

# Step 6: Start Browser
Write-Status "Membuka browser..." "PROCESS"

$ChromePath = "C:\Program Files\Google\Chrome\Application\chrome.exe"
$FirefoxPath = "C:\Program Files\Mozilla Firefox\firefox.exe"
$EdgePath = "C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"
$BrowserPath = $null

if (Test-Path $ChromePath) {
    $BrowserPath = $ChromePath
} elseif (Test-Path $FirefoxPath) {
    $BrowserPath = $FirefoxPath
} elseif (Test-Path $EdgePath) {
    $BrowserPath = $EdgePath
}

Start-Sleep -Seconds 2

if ($BrowserPath) {
    Start-Process $BrowserPath -ArgumentList "http://localhost/Sate%20Taichan%20Lilit/"
    Write-Status "Browser dibuka" "OK"
} else {
    Write-Status "Buka browser dan akses: http://localhost/Sate%20Taichan%20Lilit/" "WARN"
}

Write-Host "`n"
Write-Host "========================================================================" -ForegroundColor $Blue
Write-Host "  SETUP SELESAI!" -ForegroundColor $Green
Write-Host "========================================================================" -ForegroundColor $Blue
Write-Host "`n"

Write-Host "📍 URLs Penting:" -ForegroundColor $Cyan
Write-Host "   Website:    http://localhost/Sate%20Taichan%20Lilit/" -ForegroundColor $Green
Write-Host "   Admin:      http://localhost/Sate%20Taichan%20Lilit/admin/" -ForegroundColor $Green
Write-Host "   Database:   http://localhost/phpmyadmin/" -ForegroundColor $Green
Write-Host "   Test:       http://localhost/Sate%20Taichan%20Lilit/test-db.php" -ForegroundColor $Green
Write-Host "`n"

Write-Host "🔐 Admin Credentials:" -ForegroundColor $Cyan
Write-Host "   Username: admin" -ForegroundColor $Green
Write-Host "   Password: admin123" -ForegroundColor $Green
Write-Host "`n"

Read-Host "Tekan Enter untuk selesai"
