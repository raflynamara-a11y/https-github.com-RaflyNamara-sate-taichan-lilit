<?php
/**
 * DATABASE TEST & HEALTH CHECK
 * File: test-db.php
 * 
 * Untuk mengecek:
 * - Koneksi Database
 * - Struktur Tabel
 * - Data Sample
 * - Performa Query
 */

require_once 'config/config.php';

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$test_results = [];
$all_passed = true;

// Test 1: Database Connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

$test_results['database_connection'] = [
    'name' => 'Database Connection',
    'status' => $mysqli->connect_error ? 'FAILED' : 'PASSED',
    'message' => $mysqli->connect_error ?: 'Connected successfully to: ' . DB_NAME,
    'icon' => $mysqli->connect_error ? '❌' : '✅'
];

if ($mysqli->connect_error) {
    $all_passed = false;
    renderResults($test_results, $all_passed);
    exit;
}

// Test 2: Check Tables
$tables = ['bahan', 'alat', 'fitur_produk', 'langkah_pembuatan', 'keunggulan', 'testimoni', 'admin'];
$tables_status = 'PASSED';
$tables_message = [];

foreach ($tables as $table) {
    $result = $mysqli->query("SHOW TABLES LIKE '$table'");
    if ($result && $result->num_rows > 0) {
        $tables_message[] = "✓ Table '$table' exists";
    } else {
        $tables_message[] = "✗ Table '$table' missing";
        $tables_status = 'FAILED';
        $all_passed = false;
    }
}

$test_results['tables'] = [
    'name' => 'Database Tables',
    'status' => $tables_status,
    'message' => implode('<br>', $tables_message),
    'icon' => $tables_status === 'PASSED' ? '✅' : '❌'
];

// Test 3: Sample Data
$data_checks = [
    'bahan' => ['count_query' => 'SELECT COUNT(*) as cnt FROM bahan', 'min' => 5],
    'alat' => ['count_query' => 'SELECT COUNT(*) as cnt FROM alat', 'min' => 5],
    'keunggulan' => ['count_query' => 'SELECT COUNT(*) as cnt FROM keunggulan', 'min' => 3]
];

$data_status = 'PASSED';
$data_message = [];

foreach ($data_checks as $table => $check) {
    $result = $mysqli->query($check['count_query']);
    $row = $result->fetch_assoc();
    $count = $row['cnt'];
    
    if ($count >= $check['min']) {
        $data_message[] = "✓ Table '$table': $count records";
    } else {
        $data_message[] = "⚠ Table '$table': only $count records (expected: {$check['min']})";
        $data_status = 'WARNING';
    }
}

$test_results['sample_data'] = [
    'name' => 'Sample Data',
    'status' => $data_status,
    'message' => implode('<br>', $data_message),
    'icon' => $data_status === 'PASSED' ? '✅' : '⚠️'
];

// Test 4: Query Performance
$start_time = microtime(true);
$result = $mysqli->query("SELECT * FROM bahan LIMIT 10");
$end_time = microtime(true);
$exec_time = ($end_time - $start_time) * 1000;

$test_results['performance'] = [
    'name' => 'Query Performance',
    'status' => $exec_time < 100 ? 'PASSED' : 'WARNING',
    'message' => sprintf('Query executed in %.2f ms (threshold: 100ms)', $exec_time),
    'icon' => $exec_time < 100 ? '✅' : '⚠️'
];

// Test 5: PHP Version
$php_version_ok = version_compare(PHP_VERSION, '5.6.0', '>=');
$test_results['php_version'] = [
    'name' => 'PHP Version',
    'status' => $php_version_ok ? 'PASSED' : 'FAILED',
    'message' => PHP_VERSION . ($php_version_ok ? ' (OK)' : ' (Minimum required: 5.6.0)'),
    'icon' => $php_version_ok ? '✅' : '❌'
];

if (!$php_version_ok) {
    $all_passed = false;
}

// Test 6: File Permissions
$required_dirs = ['config', 'pages', 'assets', 'db', 'uploads'];
$perms_status = 'PASSED';
$perms_message = [];

foreach ($required_dirs as $dir) {
    $full_path = dirname(__FILE__) . '/' . $dir;
    if (is_dir($full_path)) {
        $perms_message[] = "✓ Directory './$dir' exists and accessible";
    } else {
        $perms_message[] = "⚠ Directory './$dir' missing or not accessible";
        if ($dir === 'config') {
            $perms_status = 'FAILED';
            $all_passed = false;
        }
    }
}

$test_results['permissions'] = [
    'name' => 'File Structure',
    'status' => $perms_status,
    'message' => implode('<br>', $perms_message),
    'icon' => $perms_status === 'PASSED' ? '✅' : '⚠️'
];

$mysqli->close();

function renderResults($tests, $all_passed) {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Database Health Check - Sate Taican Lilit</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                padding: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .container {
                background: white;
                border-radius: 15px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                max-width: 700px;
                width: 100%;
                padding: 40px;
            }
            
            h1 {
                color: #333;
                margin-bottom: 10px;
                text-align: center;
            }
            
            .subtitle {
                color: #666;
                text-align: center;
                margin-bottom: 30px;
                font-size: 0.9rem;
            }
            
            .overall-status {
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, <?php echo $all_passed ? '#4CAF50' : '#f44336'; ?> 0%, <?php echo $all_passed ? '#45a049' : '#da190b'; ?> 100%);
                color: white;
                padding: 20px;
                border-radius: 10px;
                margin-bottom: 30px;
                font-size: 1.2rem;
                font-weight: bold;
            }
            
            .test-item {
                border: 2px solid #e0e0e0;
                border-radius: 10px;
                padding: 20px;
                margin-bottom: 15px;
                transition: 0.3s;
            }
            
            .test-item:hover {
                border-color: #667eea;
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
            }
            
            .test-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 10px;
            }
            
            .test-name {
                font-weight: bold;
                color: #333;
                font-size: 1.1rem;
            }
            
            .test-icon {
                font-size: 1.5rem;
            }
            
            .test-status {
                display: inline-block;
                padding: 5px 12px;
                border-radius: 5px;
                font-size: 0.85rem;
                font-weight: bold;
            }
            
            .status-passed {
                background: #c8e6c9;
                color: #2e7d32;
            }
            
            .status-failed {
                background: #ffcdd2;
                color: #c62828;
            }
            
            .status-warning {
                background: #fff9c4;
                color: #f57f17;
            }
            
            .test-message {
                color: #666;
                font-size: 0.9rem;
                line-height: 1.6;
            }
            
            .links {
                margin-top: 30px;
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }
            
            .btn {
                flex: 1;
                min-width: 150px;
                padding: 12px 20px;
                border: none;
                border-radius: 8px;
                font-weight: bold;
                cursor: pointer;
                text-align: center;
                text-decoration: none;
                transition: 0.3s;
                display: inline-block;
            }
            
            .btn-primary {
                background: #667eea;
                color: white;
            }
            
            .btn-primary:hover {
                background: #5568d3;
            }
            
            .btn-secondary {
                background: #e0e0e0;
                color: #333;
            }
            
            .btn-secondary:hover {
                background: #d0d0d0;
            }
            
            footer {
                margin-top: 30px;
                text-align: center;
                color: #999;
                font-size: 0.85rem;
            }
        </style>
    </head>
    <body>
    <div class="container">
        <h1>🔍 Database Health Check</h1>
        <p class="subtitle">Sate Taican Lilit - System Status</p>
        
        <div class="overall-status">
            <?php echo $all_passed ? '✅ ALL SYSTEMS OK' : '❌ SYSTEM ERROR'; ?>
        </div>
        
        <?php foreach ($tests as $key => $test): ?>
            <div class="test-item">
                <div class="test-header">
                    <span class="test-name">
                        <span class="test-icon"><?php echo $test['icon']; ?></span>
                        <?php echo $test['name']; ?>
                    </span>
                    <span class="test-status status-<?php echo strtolower($test['status']); ?>">
                        <?php echo $test['status']; ?>
                    </span>
                </div>
                <div class="test-message">
                    <?php echo $test['message']; ?>
                </div>
            </div>
        <?php endforeach; ?>
        
        <div class="links">
            <a href="index.php" class="btn btn-primary">🏠 Go to Website</a>
            <a href="http://localhost/phpmyadmin/" class="btn btn-secondary" target="_blank">📊 phpMyAdmin</a>
            <a href="status.php" class="btn btn-secondary">📋 Status Page</a>
        </div>
        
        <footer>
            Sate Taican Lilit © 2026 | System Test v1.0
        </footer>
    </div>
    </body>
    </html>
    <?php
}
?>
