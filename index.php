<?php
session_start();

// Database configuration
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'sate_taichan';
const DB_PORT = 3306;

function h($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function flash($message, $type = 'success') {
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function getFlash() {
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function isUser() {
    return isset($_SESSION['user_id']) && $_SESSION['role'] === 'user';
}

function isAdmin() {
    return isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin';
}

function requireUser() {
    if (!isUser()) {
        header('Location: ?page=login');
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: ?page=admin_login');
        exit;
    }
}

// Create connection without selecting database first so we can create the schema if needed.
$setupConn = new mysqli(DB_HOST, DB_USER, DB_PASS, '', DB_PORT);
if ($setupConn->connect_error) {
    die('Koneksi database gagal: ' . $setupConn->connect_error);
}
$setupConn->set_charset('utf8mb4');
$setupConn->query('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
$setupConn->select_db(DB_NAME);

$setupConn->query('CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM("admin","user") DEFAULT "user",
    no_telepon VARCHAR(20),
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

$setupConn->query('CREATE TABLE IF NOT EXISTS menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_menu VARCHAR(100) NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

$setupConn->query('CREATE TABLE IF NOT EXISTS pesanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_harga DECIMAL(10,2) NOT NULL,
    status ENUM("pending","diproses","selesai") DEFAULT "pending",
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

$setupConn->query('CREATE TABLE IF NOT EXISTS detail_pesanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pesanan_id INT NOT NULL,
    menu_id INT NOT NULL,
    jumlah INT NOT NULL,
    harga_satuan DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_id) REFERENCES menu(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

$setupConn->query("INSERT IGNORE INTO menu (id, nama_menu, harga, deskripsi) VALUES
    (1, 'Sate Taichan Lilit', 15000, 'Sate ayam taichan dengan bumbu khas, dililit pada tusuk bambu, disajikan dengan sambal dan perasan jeruk.'),
    (2, 'Sate Taichan Lilit + Lontong', 17000, 'Paket lengkap sate taichan lilit dengan lontong, lebih mengenyangkan.')");

$adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $setupConn->prepare('INSERT IGNORE INTO users (nama_lengkap, email, password, role) VALUES (?, ?, ?, "admin")');
$adminName = 'Rija Admin';
$adminEmail = 'admin@taichan.com';
$stmt->bind_param('sss', $adminName, $adminEmail, $adminPassword);
$stmt->execute();
$stmt->close();

$conn = $setupConn;

$page = $_GET['page'] ?? 'home';
$flash = getFlash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'login_user') {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $stmt = $conn->prepare('SELECT id, nama_lengkap, password FROM users WHERE email = ? AND role = "user"');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama_lengkap'];
            $_SESSION['role'] = 'user';
            flash('Login berhasil. Selamat datang, ' . $user['nama_lengkap'] . '!');
            header('Location: ?page=dashboard');
            exit;
        }

        flash('Email atau password salah.', 'danger');
        header('Location: ?page=login');
        exit;
    }

    if (isset($_POST['action']) && $_POST['action'] === 'register') {
        $nama = trim($_POST['nama']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $telepon = trim($_POST['telepon']);

        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->close();
            flash('Email sudah terdaftar.', 'danger');
            header('Location: ?page=register');
            exit;
        }
        $stmt->close();

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO users (nama_lengkap, email, password, role, no_telepon) VALUES (?, ?, ?, "user", ?)');
        $stmt->bind_param('ssss', $nama, $email, $hash, $telepon);
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['nama'] = $nama;
            $_SESSION['role'] = 'user';
            flash('Akun berhasil dibuat. Selamat datang, ' . $nama . '!');
            header('Location: ?page=dashboard');
            exit;
        }
        $stmt->close();
        flash('Registrasi gagal. Silakan coba lagi.', 'danger');
        header('Location: ?page=register');
        exit;
    }

    if (isset($_POST['action']) && $_POST['action'] === 'login_admin') {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $stmt = $conn->prepare('SELECT id, nama_lengkap, password FROM users WHERE email = ? AND role = "admin"');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $admin = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['nama'] = $admin['nama_lengkap'];
            $_SESSION['role'] = 'admin';
            flash('Login admin berhasil.');
            header('Location: ?page=admin_dashboard');
            exit;
        }

        flash('Email atau password salah.', 'danger');
        header('Location: ?page=admin_login');
        exit;
    }

    if (isset($_POST['action']) && $_POST['action'] === 'order') {
        requireUser();
        $menu_id = (int)$_POST['menu_id'];
        $jumlah = max(1, (int)$_POST['jumlah']);

        $stmt = $conn->prepare('SELECT harga, nama_menu FROM menu WHERE id = ?');
        $stmt->bind_param('i', $menu_id);
        $stmt->execute();
        $menu = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$menu) {
            flash('Menu tidak ditemukan.', 'danger');
            header('Location: ?page=menu');
            exit;
        }

        $total = $menu['harga'] * $jumlah;
        $stmt = $conn->prepare('INSERT INTO pesanan (user_id, total_harga) VALUES (?, ?)');
        $stmt->bind_param('id', $_SESSION['user_id'], $total);
        $stmt->execute();
        $pesanan_id = $stmt->insert_id;
        $stmt->close();

        $stmt = $conn->prepare('INSERT INTO detail_pesanan (pesanan_id, menu_id, jumlah, harga_satuan) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('iiid', $pesanan_id, $menu_id, $jumlah, $menu['harga']);
        $stmt->execute();
        $stmt->close();

        $_SESSION['pesanan_terakhir'] = $pesanan_id;
        flash('Pesanan berhasil dibuat. Silakan konfirmasi via WhatsApp.', 'success');
        header('Location: ?page=sukses');
        exit;
    }

    if (isset($_POST['action']) && $_POST['action'] === 'update_status') {
        requireAdmin();
        $id = (int)$_POST['pesanan_id'];
        $status = $_POST['status'];
        if (!in_array($status, ['pending', 'diproses', 'selesai'], true)) {
            flash('Status tidak valid.', 'danger');
            header('Location: ?page=admin_pesanan');
            exit;
        }
        $stmt = $conn->prepare('UPDATE pesanan SET status = ? WHERE id = ?');
        $stmt->bind_param('si', $status, $id);
        $stmt->execute();
        $stmt->close();
        flash('Status pesanan berhasil diperbarui.');
        header('Location: ?page=admin_pesanan');
        exit;
    }

    if (isset($_POST['action']) && $_POST['action'] === 'logout') {
        session_destroy();
        session_start();
        flash('Anda telah logout.');
        header('Location: ?page=home');
        exit;
    }
}

function renderHeader() {
    global $page;
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sate Taichan Lilit</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <style>
            body { background-color: #f8f9fa; }
            .nav-brand { font-weight: 700; }
            .hero { min-height: 60vh; background: linear-gradient(135deg, #2b2d42 0%, #8d99ae 100%); color: white; }
            .hero .btn-primary { background-color: #ef233c; border-color: #ef233c; }
            .card-hero { border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
            .footer { background-color: #212529; color: #f8f9fa; }
        </style>
    </head>
    <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand nav-brand" href="?page=home">🍢 Sate Taichan Lilit</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="?page=home">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="?page=menu">Menu</a></li>
            <?php if (isAdmin()): ?>
                <li class="nav-item"><a class="nav-link" href="?page=admin_dashboard">Admin Panel</a></li>
                <li class="nav-item"><a class="nav-link" href="?page=admin_pesanan">Kelola Pesanan</a></li>
                <li class="nav-item"><a class="nav-link" href="#" onclick="document.getElementById('logoutForm').submit(); return false;">Logout</a></li>
            <?php elseif (isUser()): ?>
                <li class="nav-item"><a class="nav-link" href="?page=dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="#" onclick="document.getElementById('logoutForm').submit(); return false;">Logout</a></li>
            <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="?page=login">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="?page=register">Daftar</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
    <form id="logoutForm" method="post" class="d-none">
        <input type="hidden" name="action" value="logout">
    </form>
    <main class="container my-4">
    <?php
}

function renderFooter() {
    ?>
    </main>
    <footer class="footer py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2026 Sate Taichan Lilit | Tugas Perancangan Web</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
}

renderHeader();

if ($flash) {
    echo '<div class="alert alert-' . h($flash['type']) . ' alert-dismissible fade show" role="alert">' . h($flash['message']) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}

switch ($page) {
    case 'menu':
        $query = 'SELECT * FROM menu ORDER BY nama_menu';
        $search = trim($_GET['q'] ?? '');
        if ($search !== '') {
            $stmt = $conn->prepare('SELECT * FROM menu WHERE nama_menu LIKE ? OR deskripsi LIKE ? ORDER BY nama_menu');
            $like = '%' . $search . '%';
            $stmt->bind_param('ss', $like, $like);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query($query);
        }
        ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Menu Kami</h2>
                <p>Pilih sate terbaik dengan bumbu asli Taichan.</p>
            </div>
            <form class="d-flex" method="get">
                <input type="hidden" name="page" value="menu">
                <input class="form-control me-2" type="search" name="q" value="<?= h($search) ?>" placeholder="Cari menu...">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
            </form>
        </div>
        <div class="row gy-4">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-6">
                    <div class="card card-hero h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= h($row['nama_menu']) ?></h5>
                            <p class="card-text"><?= h($row['deskripsi']) ?></p>
                            <p class="fw-bold text-success">Rp<?= number_format($row['harga'], 0, ',', '.') ?></p>
                            <?php if (isUser()): ?>
                                <a href="?page=pesan&id=<?= $row['id'] ?>" class="btn btn-primary">Pesan Sekarang</a>
                            <?php else: ?>
                                <a href="?page=login" class="btn btn-outline-primary">Login untuk Pesan</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <?php
        break;

    case 'pesan':
        requireUser();
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $conn->prepare('SELECT * FROM menu WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $menu = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$menu) {
            flash('Menu tidak ditemukan.', 'danger');
            header('Location: ?page=menu');
            exit;
        }
        ?>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title">Pesan <?= h($menu['nama_menu']) ?></h3>
                        <form method="post">
                            <input type="hidden" name="action" value="order">
                            <input type="hidden" name="menu_id" value="<?= $menu['id'] ?>">
                            <div class="mb-3">
                                <label class="form-label">Jumlah</label>
                                <input type="number" name="jumlah" class="form-control" min="1" value="1" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Harga Satuan</label>
                                <input type="text" class="form-control" value="Rp<?= number_format($menu['harga'], 0, ',', '.') ?>" readonly>
                            </div>
                            <button class="btn btn-success">Pesan</button>
                            <a href="?page=menu" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        break;

    case 'sukses':
        requireUser();
        $pesanan_id = (int)($_SESSION['pesanan_terakhir'] ?? 0);
        $stmt = $conn->prepare('SELECT p.total_harga, p.status, dp.jumlah, m.nama_menu FROM pesanan p JOIN detail_pesanan dp ON p.id = dp.pesanan_id JOIN menu m ON dp.menu_id = m.id WHERE p.id = ?');
        $stmt->bind_param('i', $pesanan_id);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$data) {
            echo '<div class="alert alert-warning">Pesanan tidak ditemukan.</div>';
            break;
        }
        $wa_text = rawurlencode("Halo Rija, saya ingin pesan:\n{$data['nama_menu']} x {$data['jumlah']}\nTotal: Rp" . number_format($data['total_harga'], 0, ',', '.') . "\nTerima kasih.");
        $wa_link = "https://wa.me/6289523500868?text={$wa_text}";
        ?>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title text-success">Pesanan Berhasil!</h3>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">Menu: <?= h($data['nama_menu']) ?></li>
                            <li class="list-group-item">Jumlah: <?= h($data['jumlah']) ?></li>
                            <li class="list-group-item">Total: Rp<?= number_format($data['total_harga'], 0, ',', '.') ?></li>
                            <li class="list-group-item">Status: <?= h($data['status']) ?></li>
                        </ul>
                        <a href="<?= $wa_link ?>" class="btn btn-success" target="_blank"><i class="bi bi-whatsapp"></i> Konfirmasi via WhatsApp</a>
                        <a href="?page=menu" class="btn btn-outline-primary">Pesan Lagi</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
        break;

    case 'login':
        if (isUser() || isAdmin()) {
            header('Location: ?page=' . (isAdmin() ? 'admin_dashboard' : 'dashboard'));
            exit;
        }
        ?>
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title mb-3">Login User</h3>
                        <form method="post">
                            <input type="hidden" name="action" value="login_user">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button class="btn btn-primary">Login</button>
                            <a href="?page=register" class="btn btn-link">Belum punya akun? Daftar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        break;

    case 'register':
        if (isUser() || isAdmin()) {
            header('Location: ?page=' . (isAdmin() ? 'admin_dashboard' : 'dashboard'));
            exit;
        }
        ?>
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title mb-3">Daftar Akun</h3>
                        <form method="post">
                            <input type="hidden" name="action" value="register">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required minlength="6">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" name="telepon" class="form-control">
                            </div>
                            <button class="btn btn-primary">Daftar</button>
                            <a href="?page=login" class="btn btn-link">Sudah punya akun? Login</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        break;

    case 'dashboard':
        requireUser();
        $stmt = $conn->prepare('SELECT p.id, p.total_harga, p.status, p.dibuat_pada, GROUP_CONCAT(CONCAT(dp.jumlah, "x ", m.nama_menu) SEPARATOR ", ") AS detail FROM pesanan p JOIN detail_pesanan dp ON p.id = dp.pesanan_id JOIN menu m ON dp.menu_id = m.id WHERE p.user_id = ? GROUP BY p.id ORDER BY p.dibuat_pada DESC');
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $orders = $stmt->get_result();
        $stmt->close();
        ?>
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card bg-white shadow-sm">
                    <div class="card-body">
                        <h3>Dashboard User</h3>
                        <p>Selamat datang, <?= h($_SESSION['nama']) ?>!</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Riwayat Pemesanan</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead>
                            <tr><th>No</th><th>Detail</th><th>Total</th><th>Status</th><th>Tanggal</th></tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while ($row = $orders->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= h($row['detail']) ?></td>
                                    <td>Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                    <td><span class="badge bg-<?= $row['status'] === 'pending' ? 'warning' : ($row['status'] === 'diproses' ? 'info' : 'success') ?> text-capitalize"><?= h($row['status']) ?></span></td>
                                    <td><?= date('d/m/Y H:i', strtotime($row['dibuat_pada'])) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
        break;

    case 'admin_login':
        if (isAdmin()) {
            header('Location: ?page=admin_dashboard');
            exit;
        }
        ?>
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title mb-3">Login Admin</h3>
                        <form method="post">
                            <input type="hidden" name="action" value="login_admin">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        break;

    case 'admin_dashboard':
        requireAdmin();
        $total = $conn->query('SELECT COUNT(*) AS total FROM pesanan')->fetch_assoc()['total'];
        $pending = $conn->query('SELECT COUNT(*) AS total FROM pesanan WHERE status = "pending"')->fetch_assoc()['total'];
        ?>
        <div class="row gy-4">
            <div class="col-md-6">
                <div class="card text-white bg-primary shadow-sm">
                    <div class="card-body">
                        <h5>Total Pesanan</h5>
                        <p class="display-4 mb-0"><?= $total ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-warning shadow-sm">
                    <div class="card-body">
                        <h5>Menunggu</h5>
                        <p class="display-4 mb-0"><?= $pending ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <a href="?page=admin_pesanan" class="btn btn-light">Kelola Pesanan</a>
        </div>
        <?php
        break;

    case 'admin_pesanan':
        requireAdmin();
        $result = $conn->query('SELECT p.*, u.nama_lengkap, u.email, GROUP_CONCAT(CONCAT(dp.jumlah, "x ", m.nama_menu) SEPARATOR ", ") AS detail FROM pesanan p JOIN users u ON p.user_id = u.id JOIN detail_pesanan dp ON p.id = dp.pesanan_id JOIN menu m ON dp.menu_id = m.id GROUP BY p.id ORDER BY p.dibuat_pada DESC');
        ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <h3>Daftar Pesanan</h3>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr><th>ID</th><th>Pemesan</th><th>Detail</th><th>Total</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= h($row['id']) ?></td>
                                    <td><?= h($row['nama_lengkap']) ?><br><small><?= h($row['email']) ?></small></td>
                                    <td><?= h($row['detail']) ?></td>
                                    <td>Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                    <td><span class="badge bg-<?= $row['status'] === 'pending' ? 'warning' : ($row['status'] === 'diproses' ? 'info' : 'success') ?> text-capitalize"><?= h($row['status']) ?></span></td>
                                    <td><?= h($row['dibuat_pada']) ?></td>
                                    <td>
                                        <form method="post" class="d-flex gap-1 align-items-center">
                                            <input type="hidden" name="action" value="update_status">
                                            <input type="hidden" name="pesanan_id" value="<?= h($row['id']) ?>">
                                            <select name="status" class="form-select form-select-sm">
                                                <option value="pending" <?= $row['status'] === 'pending' ? 'selected' : '' ?>>pending</option>
                                                <option value="diproses" <?= $row['status'] === 'diproses' ? 'selected' : '' ?>>diproses</option>
                                                <option value="selesai" <?= $row['status'] === 'selesai' ? 'selected' : '' ?>>selesai</option>
                                            </select>
                                            <button class="btn btn-sm btn-primary">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
        break;

    case 'home':
    default:
        ?>
        <section class="hero text-white d-flex align-items-center py-5 mb-4 rounded-4">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <h1 class="display-5 fw-bold">Sate Taichan Lilit</h1>
                        <p class="lead">Nikmati sate taichan lilit dengan bumbu rahasia khas, dibuat dari bahan segar dan disajikan hangat setiap hari.</p>
                        <a href="?page=menu" class="btn btn-primary btn-lg me-2">Lihat Menu</a>
                        <?php if (!isUser()): ?>
                            <a href="?page=login" class="btn btn-outline-light btn-lg">Login</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <div class="row gy-4">
            <div class="col-md-4">
                <div class="card card-hero p-4">
                    <h5>Bahan Segar</h5>
                    <p>Hanya menggunakan ayam berkualitas, sayuran segar, dan rempah autentik.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-hero p-4">
                    <h5>Proses Higienis</h5>
                    <p>Setiap sate dibuat dengan standar kebersihan tinggi untuk keamanan dan rasa.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-hero p-4">
                    <h5>Harga Terjangkau</h5>
                    <p>Mulai Rp15.000, pilihan paket hemat cocok untuk keluarga dan acara kecil.</p>
                </div>
            </div>
        </div>
        <?php
        break;
}

renderFooter();
