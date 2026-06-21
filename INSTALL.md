# Panduan Instalasi Sate Taichan Lilit v2.0

Panduan lengkap untuk setup dan menjalankan aplikasi Sate Taichan Lilit di komputer lokal atau server.

## Daftar Isi
1. [System Requirements](#system-requirements)
2. [Instalasi XAMPP](#instalasi-xampp)
3. [Download Project](#download-project)
4. [Setup Database](#setup-database)
5. [Konfigurasi Aplikasi](#konfigurasi-aplikasi)
6. [Testing & Troubleshooting](#testing--troubleshooting)

## System Requirements

### Minimum Requirement:
- **PHP**: 7.4 atau lebih tinggi
- **MySQL**: 5.7 atau lebih tinggi  
- **Apache**: 2.4 atau lebih tinggi
- **RAM**: 512MB minimal
- **Disk Space**: 100MB

### Software yang Perlu Diinstal:
- XAMPP (Apache + MySQL + PHP)
- Web Browser (Chrome, Firefox, Edge)
- Text Editor (VSCode, Sublime, dll)

## Instalasi XAMPP

### Windows:
1. Download XAMPP dari https://www.apachefriends.org/
2. Pilih versi PHP 7.4+ atau 8.0+
3. Jalankan installer `.exe`
4. Install di folder default `C:\xampp`
5. Buka XAMPP Control Panel
6. Klik `Start` pada Apache dan MySQL

### Mac:
```bash
# Gunakan Homebrew
brew install xampp

# Atau download dari apachefriends.org
```

### Linux:
```bash
# Ubuntu/Debian
sudo apt-get install xampp

# Atau download versi Linux dari apachefriends.org
```

**Verifikasi**: Buka http://localhost - Anda akan melihat halaman XAMPP.

## Download Project

### Opsi 1: Clone dari GitHub
```bash
cd C:\xampp\htdocs  # Windows
# atau
cd /Applications/XAMPP/htdocs  # Mac
# atau  
cd /opt/lampp/htdocs  # Linux

git clone https://github.com/yourusername/sate-taichan-lilit.git
cd sate-taichan-lilit
```

### Opsi 2: Download ZIP
1. Kunjungi https://github.com/yourusername/sate-taichan-lilit
2. Klik tombol `Code` → `Download ZIP`
3. Extract ke folder `htdocs`
4. Rename folder menjadi `sate-taichan-lilit`

### Folder Structure:
```
htdocs/
└── sate-taichan-lilit/
    ├── admin/
    ├── user/
    ├── config/
    ├── includes/
    ├── assets/
    ├── db/
    ├── uploads/
    └── index.php
```

## Setup Database

### Metode 1: PhpMyAdmin (Paling Mudah)

1. Buka http://localhost/phpmyadmin
2. Login dengan:
   - **Username**: `root`
   - **Password**: (kosongkan)
3. Klik menu `File` → Pilih file `db/database.sql`
4. Klik tombol `Buka`
5. Klik `Go` atau `Execute`
6. Database akan dibuat otomatis dengan tabel dan data awal

### Metode 2: Command Line

Windows (Command Prompt):
```bash
cd C:\xampp\mysql\bin
mysql -u root -p

# Jika tidak ada password, tekan Enter saja
```

Linux/Mac (Terminal):
```bash
mysql -u root -p

# Masukkan password jika ada
```

Kemudian jalankan:
```sql
-- Di MySQL console
source /path/to/sate-taichan-lilit/db/database.sql;

-- Atau untuk import file
mysql -u root -p sate_taichan < /path/to/db/database.sql
```

### Verifikasi Database:
```sql
mysql> use sate_taichan;
mysql> SHOW TABLES;
```

Akan terlihat tabel-tabel seperti: `users`, `menu`, `pesanan`, dll.

## Konfigurasi Aplikasi

### 1. Edit File Config
Buka file `config/config.php` dan pastikan setting sesuai:

```php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Kosong jika tidak ada password
define('DB_NAME', 'sate_taichan');
define('DB_PORT', 3306);
```

### 2. Buat Folder Uploads (Jika Belum Ada)
```bash
mkdir uploads/menu
chmod 755 uploads/menu
```

### 3. Buat Folder Logs (Jika Belum Ada)
```bash
mkdir logs
chmod 755 logs
```

### 4. Set Permissions (Linux/Mac)
```bash
chmod 755 uploads/
chmod 755 logs/
chmod 644 config/config.php
```

## Testing & Troubleshooting

### Test 1: Akses Halaman Utama
1. Buka browser
2. Ketik URL: `http://localhost/sate-taichan-lilit`
3. Seharusnya melihat halaman home

### Test 2: Login User
1. Klik link "Daftar" atau "Login"
2. Buat akun baru atau gunakan:
   - Email: user@test.com
   - Password: test123456

### Test 3: Login Admin
1. Buka: `http://localhost/sate-taichan-lilit/admin`
2. Gunakan kredensial default:
   - **Email**: `admin@taichan.com`
   - **Password**: `admin123`
3. Seharusnya masuk ke admin dashboard

### Common Issues & Solusi

#### Error: "Connection refused" atau "Can't connect to MySQL"
**Solusi**:
- Pastikan MySQL sudah running di XAMPP Control Panel
- Cek username dan password di config/config.php
- Restart Apache dan MySQL

#### Error: "Database sate_taichan not found"
**Solusi**:
- Import database SQL file lagi
- Pastikan menggunakan nama database `sate_taichan` (exact)

#### Error: "Access Denied for user 'root'@'localhost'"
**Solusi**:
- Cek password di config/config.php
- Reset MySQL password di XAMPP

#### Error: "Cannot upload file"
**Solusi**:
- Buat folder `uploads/menu` jika belum ada
- Set permissions: `chmod 755 uploads/`
- Cek ukuran file (max 5MB)

#### Error 404 di halaman tertentu
**Solusi**:
- Pastikan folder struktur lengkap
- Cek spelling URL
- Pastikan `.htaccess` tidak menghalangi

#### Performance/Database Lambat
**Solusi**:
- Cek file size database
- Jalankan optimize database:
  ```sql
  OPTIMIZE TABLE users, menu, pesanan, detail_pesanan;
  ```

## Langkah Selanjutnya

Setelah instalasi berhasil:

1. **Setup Admin Account**
   - Login dengan admin@taichan.com / admin123
   - Ubah password di Settings
   - Tambah admin user jika diperlukan

2. **Tambah Data Menu**
   - Login sebagai admin
   - Ke Menu Management
   - Tambah menu produk dengan gambar

3. **Customize Settings**
   - Edit teks aplikasi
   - Setup WhatsApp number
   - Setup email notifications

4. **Test Pesanan End-to-End**
   - Buat akun user baru
   - Pesan menu
   - Lihat di admin pesanan
   - Update status pesanan
   - Test WhatsApp integration

## Deployment ke Server

Setelah development selesai dan ingin upload ke server hosting:

1. **Siapkan Hosting**
   - Pilih hosting dengan PHP 7.4+ dan MySQL 5.7+
   - Dapat domain (opsional)

2. **Upload Files**
   - Gunakan FTP/SFTP client
   - Upload semua file ke public_html atau www folder

3. **Import Database**
   - Login ke cPanel → phpMyAdmin
   - Buat database baru
   - Import `db/database.sql`

4. **Update Config**
   - Edit `config/config.php` dengan database info dari hosting
   - Update APP_URL ke domain production

5. **Security**
   - Ubah admin password
   - Disable debug mode
   - Backup database regularly

## Dokumentasi Lebih Lanjut

Untuk info lebih detail, lihat:
- [README.md](README.md) - Overview aplikasi
- [API Documentation](docs/API.md) - API endpoints
- [Admin Guide](docs/ADMIN_GUIDE.md) - Panduan admin

## Support

Jika ada masalah:
- Email: admin@taichan.com
- WhatsApp: +62 895-235-00868
- Issue di GitHub: https://github.com/yourusername/sate-taichan-lilit/issues

---

**Happy Coding! 🎉**
