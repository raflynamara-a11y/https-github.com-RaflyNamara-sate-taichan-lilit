# 🚀 AUTOMATION & DEPLOYMENT GUIDE

## 📋 Quick Start - Cara Tercepat

### **OPSI 1: Automatic Setup (Recommended)**
```bash
Double-click: setup.bat
```

Apa yang dilakukan:
- ✅ Cek instalasi XAMPP
- ✅ Copy project ke htdocs
- ✅ Mulai Apache & MySQL
- ✅ Import database otomatis
- ✅ Buka website di browser

**Selesai dalam 2-3 menit!**

---

## 🛠️ Manual Setup (Jika setup.bat tidak berjalan)

### **Step 1: Copy Project ke XAMPP**
```bash
Copy: D:\Sate Taichan Lilit
Paste: C:\xampp\htdocs\Sate Taichan Lilit
```

### **Step 2: Jalankan XAMPP**
```bash
1. Buka: C:\xampp\xampp-control.exe
2. Klik START → Apache (tunggu hijau)
3. Klik START → MySQL (tunggu hijau)
```

### **Step 3: Import Database**
**Opsi A - Gunakan Script:**
```bash
Buka Command Prompt di folder project
Jalankan: mysql -u root < db/database.sql
```

**Opsi B - Manual via phpMyAdmin:**
```
1. Buka: http://localhost/phpmyadmin
2. Klik SQL
3. Copy-paste isi file: db/database.sql
4. Klik Go
```

### **Step 4: Akses Website**
```
http://localhost/Sate%20Taichan%20Lilit/
```

---

## 📊 Testing & Diagnosis

### **1. Check Database Connection**
```
http://localhost/Sate%20Taichan%20Lilit/test-db.php
```

Menampilkan:
- ✅ Status koneksi database
- ✅ Daftar tabel
- ✅ Jumlah sample data
- ✅ Query performance
- ✅ PHP version
- ✅ File structure

### **2. System Status**
```
http://localhost/Sate%20Taichan%20Lilit/status.php
```

Informasi:
- Database configuration
- Statistics
- System info

---

## 🔧 Database Automation

### **Backup Database**
Double-click: `backup-db.bat`

Hasil:
```
backups/sate_taican_backup_2026-05-18_14-30-45.sql
```

### **Restore Database**
Double-click: `restore-db.bat`

Pilih file backup dari list, lalu restore otomatis.

---

## 👨‍💼 Admin Panel

### **Login Admin**
```
URL: http://localhost/Sate%20Taichan%20Lilit/admin/

Username: admin
Password: admin123
```

### **Features:**
- 📊 Dashboard dengan statistik
- 🥘 Kelola Bahan
- 🔪 Kelola Alat
- ⭐ Kelola Fitur
- 💬 Moderasi Testimoni
- 📈 Akses phpMyAdmin langsung

---

## 📂 File Structure

```
Sate Taichan Lilit/
├── setup.bat                    ← Double-click untuk auto setup!
├── backup-db.bat               ← Backup database
├── restore-db.bat              ← Restore database
├── test-db.php                 ← Test koneksi database
├── status.php                  ← Status page
│
├── config/
│   ├── config.php              ← Database configuration
│   └── database.php            ← Database class
│
├── pages/
│   ├── pengenalan.php
│   ├── keunggulan.php
│   ├── fitur.php
│   ├── cara.php
│   ├── bahan.php
│   └── alat.php
│
├── admin/                       ← Admin panel
│   ├── index.php               ← Dashboard
│   ├── login.php               ← Login page
│   ├── logout.php              ← Logout
│   └── manage-*.php            ← Management pages
│
├── assets/
│   ├── css/style.css
│   └── js/script.js
│
├── db/
│   └── database.sql            ← SQL schema & sample data
│
└── backups/                     ← Auto-created folder
    └── sate_taican_backup_*.sql ← Database backups
```

---

## ⚠️ Troubleshooting

### **Error: "Can't connect to server"**
```
1. Buka XAMPP Control Panel
2. Pastikan MySQL berwarna HIJAU
3. Cek konfigurasi di: config/config.php
```

### **Error: "Database doesn't exist"**
```
1. Import ulang: db/database.sql
2. Atau gunakan: restore-db.bat
```

### **Error: "Port 80 sudah dipakai"**
```
1. Buka XAMPP Config → Apache
2. Ubah "Listen 80" → "Listen 8080"
3. Akses via: http://localhost:8080/...
```

### **Gambar tidak tampil**
```
1. Pastikan file gambar ada di folder project root
2. Nama file harus exact (case-sensitive)
3. Atau pindahkan ke: assets/images/
```

---

## 🚀 Production Deployment

### **Deploy ke Hosting Live**

1. **Via FTP/SFTP:**
   ```
   Upload folder ke public_html/
   Edit config/config.php dengan DB production
   ```

2. **Via GitHub:**
   ```
   git push ke repository
   Deploy otomatis via webhook
   ```

3. **Via Docker (Optional):**
   ```docker
   FROM php:7.4-apache
   RUN docker-php-ext-install mysqli
   COPY . /var/www/html/
   ```

---

## 🔐 Security Tips

**WAJIB sebelum production:**

1. **Ubah Password Admin:**
   Edit file: `admin/login.php`
   Update database: `UPDATE admin SET password=MD5('password_baru')`

2. **Ubah Database Password:**
   Edit: `config/config.php`

3. **Remove Test Files:**
   Hapus: `test-db.php`, `status.php` di production

4. **Enable SSL/HTTPS:**
   ```apache
   SSLEngine on
   SSLCertificateFile /path/to/cert.crt
   ```

5. **Add .htaccess Protection:**
   ```apache
   # Protect sensitive files
   <FilesMatch "^config">
       Deny from all
   </FilesMatch>
   ```

---

## 📞 Support URLs

| Fitur | URL |
|-------|-----|
| Website | http://localhost/Sate%20Taichan%20Lilit/ |
| Admin Panel | http://localhost/Sate%20Taichan%20Lilit/admin/ |
| phpMyAdmin | http://localhost/phpmyadmin/ |
| Database Test | http://localhost/Sate%20Taichan%20Lilit/test-db.php |
| System Status | http://localhost/Sate%20Taichan%20Lilit/status.php |

---

## 📝 Command Reference

### **Start Services:**
```bash
# Start Apache
"C:\xampp\apache\bin\httpd.exe"

# Start MySQL
"C:\xampp\mysql\bin\mysqld.exe"
```

### **Database Operations:**
```bash
# Import database
mysql -u root < db/database.sql

# Backup database
mysqldump -u root sate_taican_db > backup.sql

# Restore database
mysql -u root sate_taican_db < backup.sql

# Login MySQL console
mysql -u root
```

### **PHP CLI:**
```bash
# Test PHP
php -v

# Run script
php script.php

# Start local server
php -S localhost:8000
```

---

**Selesai! Website Anda siap dijalankan! 🎉**
