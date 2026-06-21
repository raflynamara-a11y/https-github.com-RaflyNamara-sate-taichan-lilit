# 🚀 QUICK START GUIDE

## Langkah 1: Buka PowerShell di Folder Project

```powershell
cd "d:\Sate Taichan Lilit"
```

## Langkah 2: Jalankan Setup Script

```powershell
powershell -ExecutionPolicy Bypass -File setup.ps1
```

**Atau gunakan BAT untuk Windows CMD:**
```cmd
setup.bat
```

**Atau Bash untuk Linux/Mac:**
```bash
bash setup.sh
```

---

## Apa yang Dilakukan Script?

1. ✅ Mencari XAMPP installation (C:\xampp, D:\xampp, E:\xampp)
2. ✅ Copy project ke `xampp/htdocs/Sate Taichan Lilit`
3. ✅ Cek Apache & MySQL running
4. ✅ Import database automatically
5. ✅ Buka browser ke website

---

## Akses Website

| Menu | URL | Username | Password |
|------|-----|----------|----------|
| **Website** | http://localhost/Sate%20Taichan%20Lilit/ | - | - |
| **Admin** | http://localhost/Sate%20Taichan%20Lilit/admin/ | admin | admin123 |
| **PHPMyAdmin** | http://localhost/phpmyadmin/ | root | (kosong) |
| **Health Check** | http://localhost/Sate%20Taichan%20Lilit/test-db.php | - | - |

---

## Jika Ada Error

### Error: XAMPP Tidak Ditemukan
- Install XAMPP di C:\, D:\, atau E:\
- Atau ubah path di setup script

### Error: MySQL Tidak Running
- Setup script akan buka XAMPP Control Panel
- Klik "START" pada Apache dan MySQL
- Tunggu status menjadi HIJAU

### Error: Database Import Gagal
- Pastikan MySQL sudah running
- Cek file: `db/database.sql` ada
- Jalankan manual: Buka phpMyAdmin → Import `database.sql`

---

## Structure PHP Native

```
Sate Taichan Lilit/
├── index.php (routing 6 halaman)
├── config/ (database config)
├── pages/ (6 halaman konten)
├── admin/ (login + dashboard)
├── db/ (database.sql)
├── assets/ (css + js + images)
└── setup.ps1/bat/sh (auto-installer)
```

---

## Features

✅ Tanpa HTML statis - Pure PHP Native
✅ MySQL database terstruktur
✅ 6 halaman konten: Pengenalan, Keunggulan, Fitur, Cara, Bahan, Alat
✅ Admin panel dengan login
✅ Responsive design (mobile-friendly)
✅ One-click setup (cross-platform)
✅ Database backup/restore tools
✅ Health check tools

---

**Done! Enjoy! 🎉**
