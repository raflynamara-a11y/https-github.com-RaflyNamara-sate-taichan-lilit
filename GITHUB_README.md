# Sate Taichan Lilit - Platform E-Commerce v2.0

Platform e-commerce modern untuk menjual Sate Taichan Lilit online dengan sistem admin dan user yang lengkap.

![Version](https://img.shields.io/badge/version-2.0.0-blue)
![License](https://img.shields.io/badge/license-MIT-green)
![PHP](https://img.shields.io/badge/PHP-7.4+-purple)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange)

## Fitur Utama

### Untuk User:
- ✅ Registrasi dan Login dengan keamanan password hashing
- ✅ Browse menu dengan harga dan deskripsi lengkap
- ✅ Sistem pemesanan yang mudah
- ✅ Dashboard pesanan dengan status real-time
- ✅ Riwayat pesanan dan tracking
- ✅ Integrasi WhatsApp untuk konfirmasi order
- ✅ Profile management
- ✅ Rating dan review produk

### Untuk Admin:
- ✅ Dashboard dengan statistik penjualan
- ✅ CRUD menu (tambah, edit, hapus, upload gambar)
- ✅ Manajemen pesanan lengkap
- ✅ Update status pesanan real-time
- ✅ Laporan penjualan dan revenue
- ✅ Manajemen user
- ✅ Activity logs
- ✅ Notifikasi pesanan baru

### Fitur Keamanan:
- ✅ Password hashing dengan bcrypt
- ✅ Protection terhadap SQL Injection
- ✅ Input validation dan sanitasi
- ✅ CSRF token protection
- ✅ Session management yang aman
- ✅ Activity logging

## Requirement

- PHP >= 7.4
- MySQL >= 5.7
- Apache 2.4+
- Composer (optional)

## Instalasi

### 1. Clone atau Download Project
```bash
git clone https://github.com/yourusername/sate-taichan-lilit.git
cd sate-taichan-lilit
```

### 2. Setup Database
```bash
# Import database schema
mysql -u root -p < db/database.sql
```

Atau gunakan phpMyAdmin:
- Buat database baru: `sate_taichan`
- Import file `db/database.sql`

### 3. Konfigurasi
Edit file `config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sate_taichan');
define('DB_PORT', 3306);
```

### 4. Jalankan Server
```bash
# Jika menggunakan XAMPP
# Letakkan folder di htdocs/

# Atau gunakan PHP built-in server
php -S localhost:8000
```

### 5. Akses Aplikasi
- **User**: http://localhost/sate-taichan-lilit
- **Admin**: http://localhost/sate-taichan-lilit/admin

## Kredensial Default

### Admin:
- **Email**: admin@taichan.com
- **Password**: admin123

### Membuat User Baru:
1. Klik tombol "Daftar" di halaman login
2. Isi form dengan data yang benar
3. Login dengan email dan password yang sudah didaftarkan

## Struktur Folder

```
sate-taichan-lilit/
├── admin/                 # Halaman admin
│   ├── login.php         # Login admin
│   ├── dashboard.php     # Dashboard admin
│   ├── menu/             # Manajemen menu
│   └── pesanan.php       # Manajemen pesanan
├── user/                 # Halaman user
│   ├── login.php         # Login user
│   ├── register.php      # Registrasi user
│   ├── dashboard.php     # Dashboard user
│   └── logout.php        # Logout
├── config/               # File konfigurasi
│   ├── config.php        # Konfigurasi utama
│   └── database.php      # Koneksi database
├── includes/             # File include
│   ├── auth.php          # Authentication logic
│   ├── helpers.php       # Helper functions
│   └── header.php        # Header template
├── assets/               # Assets
│   ├── css/              # CSS files
│   │   └── style.css     # Main stylesheet
│   └── js/               # JavaScript files
├── uploads/              # Upload folder
│   └── menu/             # Menu images
├── db/                   # Database files
│   └── database.sql      # Database schema
├── pages/                # Halaman publik
│   ├── menu.php          # Daftar menu
│   ├── pengenalan.php    # Tentang produk
│   └── ...
├── logs/                 # Log files
└── index.php             # Home page
```

## API Endpoints (Optional)

Beberapa endpoint API untuk integrasi:

```
GET  /api/menu              - Daftar menu
GET  /api/menu/:id          - Detail menu
POST /api/pesanan           - Buat pesanan
GET  /api/pesanan/:id       - Detail pesanan
PUT  /api/pesanan/:id       - Update pesanan
```

## Fitur Lanjutan

### Untuk Pengembangan Lebih Lanjut:
- [ ] Email notification
- [ ] Payment gateway integration (Midtrans, Stripe)
- [ ] SMS notification
- [ ] Multiple admin roles
- [ ] Inventory management
- [ ] Report export (PDF, Excel)
- [ ] Mobile app (Flutter/React Native)
- [ ] REST API lengkap

## Kontribusi

Silakan fork repository ini dan buat pull request dengan fitur atau perbaikan baru.

## Lisensi

MIT License - Bebas digunakan untuk keperluan komersial maupun non-komersial.

## Dukungan

Untuk pertanyaan atau bantuan teknis:
- Email: admin@taichan.com
- WhatsApp: +62 895-235-00868

## Changelog

### v2.0.0 (2026-06-21)
- Refactor kode dengan struktur yang lebih rapi
- Implementasi class Auth untuk keamanan
- Database schema yang lebih lengkap
- UI/UX improvement dengan Bootstrap 5
- Penambahan activity logging
- Security enhancement

### v1.0.0 (Initial Release)
- Basic e-commerce functionality
- User authentication
- Admin dashboard
- Order management

## Credits

Dikembangkan oleh Tim Development untuk Sate Taichan Lilit
Website: https://sate-taichan-lilit.id

---

**Dibuat dengan ❤️ untuk memudahkan penjualan Sate Taichan Lilit**
