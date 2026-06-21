<?php
require_once 'config/config.php';

// Test koneksi database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

if ($mysqli->connect_error) {
    $status = "❌ ERROR: " . $mysqli->connect_error;
    $color = "red";
} else {
    $status = "✅ DATABASE TERHUBUNG";
    $color = "green";
}

// Ambil informasi dari database
$bahan_count = 0;
$alat_count = 0;

if (!$mysqli->connect_error) {
    $bahan_result = $mysqli->query("SELECT COUNT(*) as count FROM bahan");
    $alat_result = $mysqli->query("SELECT COUNT(*) as count FROM alat");
    
    $bahan = $bahan_result->fetch_assoc();
    $alat = $alat_result->fetch_assoc();
    
    $bahan_count = $bahan['count'];
    $alat_count = $alat['count'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status - Sate Taican Lilit</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #C41230, #ff6b5b);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #C41230;
            margin-bottom: 10px;
            text-align: center;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }
        .status-box {
            background: linear-gradient(135deg, rgba(196, 18, 48, 0.1), rgba(255, 107, 91, 0.1));
            border: 2px solid #C41230;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .status-text {
            font-size: 1.3rem;
            font-weight: bold;
            color: <?php echo $color; ?>;
            margin-bottom: 10px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }
        .info-card {
            background: #f5f5f5;
            border: 2px solid #C41230;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
        }
        .info-card h3 {
            color: #C41230;
            font-size: 0.9rem;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .info-card .number {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }
        .links {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            padding: 12px 20px;
            background: #C41230;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            text-align: center;
            transition: 0.3s;
            border: 2px solid #C41230;
            font-weight: bold;
        }
        .btn:hover {
            background: white;
            color: #C41230;
        }
        .btn-secondary {
            background: white;
            color: #C41230;
            border: 2px solid #C41230;
        }
        .btn-secondary:hover {
            background: #C41230;
            color: white;
        }
        .config-info {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            font-size: 0.85rem;
            font-family: monospace;
        }
        .config-info h4 {
            color: #C41230;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }
        .config-info p {
            margin: 5px 0;
            color: #333;
            word-break: break-all;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            color: #999;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>🍗 Sate Taican Lilit</h1>
    <div class="subtitle">Status Sistem</div>
    
    <div class="status-box">
        <div class="status-text"><?php echo $status; ?></div>
        <p style="color: #666; font-size: 0.9rem;">
            Aplikasi PHP Native dengan MySQL Database
        </p>
    </div>

    <div class="info-grid">
        <div class="info-card">
            <h3>Bahan Tersimpan</h3>
            <div class="number"><?php echo $bahan_count; ?></div>
        </div>
        <div class="info-card">
            <h3>Alat Tersimpan</h3>
            <div class="number"><?php echo $alat_count; ?></div>
        </div>
    </div>

    <div class="config-info">
        <h4>📋 Konfigurasi Aplikasi:</h4>
        <p><strong>Nama Aplikasi:</strong> <?php echo APP_NAME; ?></p>
        <p><strong>URL:</strong> <?php echo APP_URL; ?></p>
        <p><strong>Database:</strong> <?php echo DB_NAME; ?></p>
        <p><strong>Host:</strong> <?php echo DB_HOST; ?>:<?php echo DB_PORT; ?></p>
        <p><strong>User:</strong> <?php echo DB_USER; ?></p>
    </div>

    <div class="links">
        <a href="index.php" class="btn">🏠 Ke Halaman Utama</a>
        <a href="http://localhost/phpmyadmin" class="btn btn-secondary" target="_blank">📊 phpMyAdmin</a>
    </div>

    <footer>
        Sate Taican Lilit © 2026 | Sistem Berbasis Web
    </footer>
</div>
</body>
</html>
