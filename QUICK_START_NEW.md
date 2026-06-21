# Quick Start - Sate Taichan Lilit v2.0

**Mulai dalam 5 menit!** Panduan cepat untuk menjalankan aplikasi.

## Prerequisites
- XAMPP sudah terinstall
- MySQL running
- Web browser

## Step 1: Download Project
```bash
cd C:\xampp\htdocs  # Windows
git clone https://github.com/yourusername/sate-taichan-lilit.git
cd sate-taichan-lilit
```

## Step 2: Setup Database (2 menit)
1. Buka http://localhost/phpmyadmin
2. Login (Username: `root`, Password: kosongkan)
3. Klik menu `File` → Pilih `db/database.sql`
4. Klik `Buka` kemudian `Execute`
5. ✅ Database ready!

## Step 3: Verify Konfigurasi
Buka `config/config.php` - pastikan ini:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Kosong
define('DB_NAME', 'sate_taichan');
```

## Step 4: Jalankan Aplikasi
Buka browser dan akses:
- **Home**: http://localhost/sate-taichan-lilit
- **Admin**: http://localhost/sate-taichan-lilit/admin
- **User**: http://localhost/sate-taichan-lilit/user/login.php

## Step 5: Login
### Admin Account:
- **Email**: `admin@taichan.com`
- **Password**: `admin123`

### Buat User Baru:
- Klik "Daftar"
- Isi form
- Login dengan akun yang dibuat

## Testing Checklist
- [ ] Bisa akses home page
- [ ] Bisa login admin
- [ ] Bisa lihat admin dashboard
- [ ] Bisa tambah menu baru
- [ ] Bisa buat akun user
- [ ] User bisa lihat menu
- [ ] User bisa buat pesanan
- [ ] Admin bisa lihat pesanan

## Troubleshooting Cepat

| Problem | Solusi |
|---------|--------|
| "Connection refused" | Restart MySQL di XAMPP |
| "Database not found" | Cek database.sql di-import |
| "Access Denied" | Cek password di config.php |
| "Cannot upload file" | Buat folder `uploads/menu` |
| "Halaman blank" | Cek error di logs folder |

## Next Steps
1. **Customize App**
   - Edit APP_NAME di config.php
   - Update WhatsApp number
   - Ubah admin password

2. **Add More Data**
   - Login admin
   - Tambah 3-5 menu item
   - Upload gambar menu

3. **Test Full Flow**
   - Register sebagai user
   - Pesan menu
   - Check order di admin
   - Update order status

4. **Deploy**
   - Siap upload ke hosting
   - Lihat [INSTALL.md](INSTALL.md) untuk detail

## Useful Links
- 📖 [Full Installation Guide](INSTALL.md)
- 📕 [README](README.md)
- 💬 [Support](mailto:admin@taichan.com)

## Tips & Tricks
- **Clear Cache**: Ctrl+Shift+Delete di browser
- **Database Reset**: Hapus tabel dan re-import database.sql
- **Debug Mode**: Edit `config/config.php` line 9-10 untuk debug info
- **Log Errors**: Lihat `logs/error.log` jika ada masalah

---

**Selamat! Aplikasi siap digunakan 🎉**

Butuh bantuan? Email: admin@taichan.com
