# 🚀 SETUP GUIDE - SATE TAICHAN LILIT v2.0

## ⚡ Quick Setup (Mulai dalam 3 langkah)

### Langkah 1: Import Database
```bash
# Buka phpmyadmin
# http://localhost/phpmyadmin
# 
# Atau gunakan command line:
mysql -u root -p sate_taichan < db/database.sql
```

### Langkah 2: Verify Config
```php
// config/config.php - Pastikan ini sesuai dengan setup Anda
define('DB_HOST', 'localhost');      // Biasanya localhost
define('DB_USER', 'root');           // Default XAMPP
define('DB_PASS', '');               // Kosong untuk XAMPP default
define('DB_NAME', 'sate_taichan');   // Nama database
```

### Langkah 3: Akses Aplikasi
- 🏠 **Home**: http://localhost/sate-taichan-lilit
- 🔐 **Admin**: http://localhost/sate-taichan-lilit/admin
- 👤 **User**: http://localhost/sate-taichan-lilit/user/login.php

---

## 🔑 Login Credentials

### Admin Account (Default):
```
Email: admin@taichan.com
Password: admin123
```

### Buat User Baru:
1. Klik "Daftar" di halaman login user
2. Isi form dengan data Anda
3. Klik "Daftar"
4. Login dengan email dan password yang dibuat

---

## ✅ Testing Checklist

Setelah setup selesai, test fitur berikut:

### User Features:
- [ ] Bisa register akun baru
- [ ] Bisa login dengan akun baru
- [ ] Bisa lihat menu di halaman publik
- [ ] Bisa buat pesanan
- [ ] Dashboard user menampilkan pesanan dengan benar
- [ ] Bisa lihat status pesanan

### Admin Features:
- [ ] Bisa login sebagai admin
- [ ] Dashboard menampilkan statistik
- [ ] Bisa lihat daftar pesanan
- [ ] Bisa update status pesanan
- [ ] Bisa lihat detail pesanan
- [ ] Bisa menambah menu baru
- [ ] Bisa upload gambar menu
- [ ] Bisa edit menu
- [ ] Bisa hapus menu

---

## 📁 Folder Structure yang Harus Ada

```
sate-taichan-lilit/
├── admin/                    # Admin pages ✅
│   ├── login.php            # Admin login
│   ├── dashboard.php        # Admin dashboard
│   ├── pesanan.php          # Pesanan management
│   ├── menu.php             # Menu management
│   └── logout.php           # Admin logout
│
├── user/                     # User pages ✅
│   ├── login.php            # User login
│   ├── register.php         # User registration
│   ├── dashboard.php        # User dashboard
│   └── logout.php           # User logout
│
├── config/                   # Configuration ✅
│   ├── config.php           # Main config
│   └── database.php         # Database class
│
├── includes/                 # Include files ✅
│   ├── auth.php             # Authentication class
│   ├── helpers.php          # Helper functions
│   └── (existing header.php, footer.php)
│
├── assets/                   # Static assets
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── script.js
│
├── uploads/                  # Upload folder
│   └── menu/                # Menu images (create this!)
│
├── logs/                     # Log files
│   └── (auto-created)
│
├── db/                       # Database
│   └── database.sql         # Schema file
│
├── pages/                    # Public pages
│   ├── menu.php             # Daftar menu
│   ├── pengenalan.php       # About product
│   └── (existing pages)
│
├── index.php                # Home page
├── README.md                # Documentation
├── INSTALL.md               # Installation guide
├── QUICK_START_NEW.md       # Quick start
├── PROJECT_SUMMARY.md       # Project summary
├── GITHUB_README.md         # For GitHub
├── .gitignore               # Git ignore file
└── SETUP.txt                # This file
```

---

## 🔧 Common Issues & Solutions

### ❌ Error: Connection refused
**Solution:**
- Pastikan XAMPP MySQL running
- Cek port MySQL (default 3306)
- Restart MySQL service

### ❌ Error: Database 'sate_taichan' not found
**Solution:**
- Buka phpmyadmin
- Import file `db/database.sql`
- Atau: `mysql -u root -p < db/database.sql`

### ❌ Error: Access Denied for user 'root'@'localhost'
**Solution:**
- Cek password di `config/config.php`
- XAMPP default biasanya kosong (DB_PASS = '')
- Reset MySQL password di XAMPP Control Panel

### ❌ Error: Cannot write to folder 'uploads'
**Solution:**
```bash
# Linux/Mac:
chmod 755 uploads/menu

# Windows:
# Right-click folder → Properties → Security → Edit → Full Control
```

### ❌ Error: File tidak bisa di-upload
**Solution:**
- Buat folder `uploads/menu` jika belum ada
- Check file size (max 5MB)
- Allowed extensions: jpg, jpeg, png, gif, webp, avif

### ❌ Halaman blank / white screen
**Solution:**
- Cek file `logs/error.log`
- Enable error display di config.php (line 10)
- Cek PHP syntax dengan `php -l index.php`

### ❌ Session tidak tersimpan
**Solution:**
- Buat folder `logs` dengan permissions 755
- Cek PHP session settings
- Clear browser cookies

---

## 🚀 First Use Workflow

### 1. Setup Admin Account
```bash
Login: admin@taichan.com / admin123
→ Go to admin dashboard
→ See default statistics
```

### 2. Add Menu Items
```bash
→ Go to Admin → Menu Management
→ Click "Tambah Menu"
→ Fill form with menu details
→ Upload menu image
→ Save
```

### 3. Create Test User
```bash
→ Go to http://localhost/sate-taichan-lilit/user/login.php
→ Click "Daftar"
→ Fill registration form
→ Verify account created
```

### 4. Make Test Order
```bash
→ Login as user
→ Browse menu (go to /pages/menu.php or home page)
→ Order menu item
→ See order in user dashboard
```

### 5. Process Order (Admin)
```bash
→ Login as admin
→ Go to Pesanan Management
→ Find user's order
→ Click "Lihat Detail"
→ Change status: pending → diproses → selesai
→ See status updated
```

---

## 📊 Database Initial Data

### Admin User:
- Email: `admin@taichan.com`
- Password: `admin123` (hashed with bcrypt)
- Role: `admin`

### Default Menu Items:
1. Sate Taichan Lilit - Rp15.000
2. Sate Taichan Lilit + Lontong - Rp17.000
3. Sate Taichan Lilit + Nasi Kuning - Rp18.000

### Ingredients (Bahan):
- Dada Ayam Fillet
- Royco Ayam
- Lada Bubuk
- Dan lainnya (7 items)

### Tools (Alat):
- Blender
- Baskom
- Tusuk Sate Lilit
- Dan lainnya (8 items)

---

## 🔐 Security Notes

### Password Security:
- Semua password di-hash dengan bcrypt (cost 12)
- Admin password: `admin123` → ganti setelah first login!
- User password min 6 character

### Input Validation:
- Semua input di-sanitasi untuk prevent XSS
- SQL Injection prevention dengan prepared statements
- Email validation dengan filter_var()

### Session Security:
- Session timeout: 1 hour
- Secure session cookies enabled
- CSRF protection dengan token (ready to implement)

---

## 📱 Browser Compatibility

✅ Chrome 90+
✅ Firefox 88+
✅ Safari 14+
✅ Edge 90+
✅ Mobile browsers (responsive)

---

## 🎨 Customization Tips

### Change App Name:
```php
// config/config.php - Line 15
define('APP_NAME', 'Sate Taichan Lilit');
// Change to your preferred name
```

### Change WhatsApp Number:
```php
// config/config.php - Line 46
define('WHATSAPP_NUMBER', '6289523500868');
// Change to your WhatsApp number
```

### Change Colors:
```css
/* assets/css/style.css */
/* Update gradient colors */
/* Primary gradient: #667eea to #764ba2 */
/* Admin gradient: #2c3e50 to #34495e */
```

---

## 📚 Documentation Files

1. **README.md** - Overview dan fitur
2. **INSTALL.md** - Detailed installation guide
3. **QUICK_START_NEW.md** - 5 minute quick start
4. **PROJECT_SUMMARY.md** - Project structure & summary
5. **GITHUB_README.md** - For GitHub upload
6. **SETUP.txt** - This file (setup instruction)

---

## 🎯 Next Steps After Setup

### Immediately:
1. ✅ Change admin password
2. ✅ Add 3-5 menu items dengan gambar
3. ✅ Test complete user flow

### Within a week:
1. ✅ Customize branding (colors, text)
2. ✅ Add more product categories
3. ✅ Setup email notifications (optional)

### For Deployment:
1. ✅ Get hosting dengan PHP 7.4+ dan MySQL 5.7+
2. ✅ Upload files via FTP
3. ✅ Import database di hosting
4. ✅ Update config.php dengan hosting credentials
5. ✅ Enable SSL certificate
6. ✅ Regular backups setup

---

## 💬 Support

Jika ada pertanyaan atau error:

1. **Check Documentation:**
   - README.md
   - INSTALL.md
   - QUICK_START_NEW.md

2. **Check Logs:**
   - `logs/error.log`
   - Browser console (F12)

3. **Contact Support:**
   - Email: admin@taichan.com
   - WhatsApp: +62 895-235-00868

---

## ✨ Congratulations!

Sistem Sate Taichan Lilit v2.0 Anda siap digunakan!

**Selamat menggunakan aplikasi** 🎉

---

**Last Updated:** 21 Juni 2026
**Version:** 2.0.0
**Status:** ✅ Production Ready
