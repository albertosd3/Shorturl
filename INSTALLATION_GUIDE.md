# Panduan Instalasi dan Setup - Short URL Manager

## 📋 Prerequisites untuk Windows

Sebelum menjalankan aplikasi, Anda perlu menginstall beberapa software berikut:

### 1. Install PHP 8.4 untuk Windows

**Opsi A: Menggunakan XAMPP (Recommended untuk pemula)**

1. Download XAMPP dari: https://www.apachefriends.org/download.html
2. Install XAMPP dengan PHP 8.1+ (versi terbaru yang tersedia)
3. Tambahkan PHP ke PATH:
   - Buka System Properties → Environment Variables
   - Edit Path, tambahkan: `C:\xampp\php`
4. Verifikasi instalasi:
   ```powershell
   php --version
   ```

**Opsi B: Install PHP Standalone**

1. Download PHP 8.4 dari: https://windows.php.net/download/
2. Extract ke folder (misal: `C:\php`)
3. Rename `php.ini-development` menjadi `php.ini`
4. Edit `php.ini`, uncomment/enable extensions:
   ```ini
   extension=curl
   extension=fileinfo
   extension=mbstring
   extension=openssl
   extension=pdo_sqlite
   extension=sqlite3
   ```
5. Tambahkan ke PATH:
   - System Properties → Environment Variables
   - Edit Path, tambahkan: `C:\php`
6. Restart Command Prompt/PowerShell

### 2. Install Composer

1. Download Composer dari: https://getcomposer.org/download/
2. Jalankan installer: `Composer-Setup.exe`
3. Ikuti wizard instalasi (akan auto-detect PHP)
4. Verifikasi instalasi:
   ```powershell
   composer --version
   ```

## 🚀 Cara Menjalankan Aplikasi

Setelah PHP dan Composer terinstall:

### Step 1: Install Dependencies
```powershell
cd "C:\Users\wibu\Documents\New Short"
composer install
```

### Step 2: Generate Application Key
```powershell
php artisan key:generate
```

### Step 3: Setup Database
Database SQLite sudah dibuat otomatis. Jalankan migrasi:
```powershell
php artisan migrate
```

### Step 4: Jalankan Development Server
```powershell
php artisan serve
```

### Step 5: Akses Aplikasi
- Buka browser: http://localhost:8000
- Login page: http://localhost:8000/login
- **Password:** G666

## 🔧 Troubleshooting

### Error: "Class Str not found"
✅ Sudah diperbaiki - file konfigurasi sudah diupdate dengan import statement yang benar.

### Error: "composer not found"
Solusi:
1. Install Composer dari link di atas
2. Restart terminal/PowerShell setelah instalasi
3. Jalankan `composer --version` untuk verifikasi

### Error: "php not found"
Solusi:
1. Install PHP menggunakan XAMPP atau standalone
2. Tambahkan PHP ke PATH environment variable
3. Restart terminal/PowerShell
4. Jalankan `php --version` untuk verifikasi

### Error: "SQLite extension not enabled"
Solusi:
1. Buka file `php.ini`
2. Uncomment baris:
   ```ini
   extension=pdo_sqlite
   extension=sqlite3
   ```
3. Restart terminal dan coba lagi

### Error saat "php artisan migrate"
Solusi:
1. Pastikan file `database/database.sqlite` ada
2. Jika tidak ada, buat file kosong:
   ```powershell
   New-Item -Path "database\database.sqlite" -ItemType File -Force
   ```
3. Jalankan migrasi lagi

## 📱 Akses Aplikasi

Setelah server berjalan, Anda bisa:

1. **Login Admin**
   - URL: http://localhost:8000/login
   - Password: G666

2. **Dashboard**
   - Lihat statistik visitor
   - Grafik analytics
   - Data device, country, browser

3. **URL Management**
   - Buat short URL baru
   - Buat link rotator
   - Kelola URL yang ada

4. **Test Short URL**
   - Buat short URL dari dashboard
   - Akses: http://localhost:8000/{shortcode}
   - Akan redirect ke URL asli

## 🎯 Fitur yang Tersedia

✅ **Short URL** - Buat link pendek custom atau auto-generate
✅ **Link Rotator** - Rotasi multiple URL (random, sequential, weighted)
✅ **Analytics Dashboard** - Statistik lengkap visitor
✅ **Device Tracking** - Desktop, Mobile, Tablet
✅ **Browser Analytics** - Chrome, Firefox, Safari, dll
✅ **Country Tracking** - Geographic data pengunjung
✅ **Bot Detection** - StopBot.net API integration
✅ **Dark Theme** - Desain gelap yang elegan
✅ **SQLite Database** - Tidak perlu MySQL

## 🔐 Keamanan

- Password admin: G666 (bisa diubah di `.env`)
- Session-based authentication
- CSRF protection
- Bot detection dan blocking
- IP tracking

## 📚 Struktur Project

```
New Short/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   └── UrlController.php
│   ├── Models/
│   │   ├── ShortUrl.php
│   │   ├── RotatorGroup.php
│   │   ├── RotatorUrl.php
│   │   └── Click.php
│   └── Services/
│       ├── StopBotService.php
│       └── UserAgentParser.php
├── database/
│   ├── migrations/
│   └── database.sqlite
├── resources/views/
│   ├── auth/
│   ├── dashboard/
│   └── layouts/
└── routes/
    └── web.php
```

## 💡 Tips

1. **Ganti Password Admin**: Edit `.env` file, ubah `ADMIN_PASSWORD=G666`
2. **StopBot API Key**: Sudah terconfig, bisa diubah di `.env`
3. **Custom Port**: `php artisan serve --port=8080`
4. **Reset Database**: `php artisan migrate:fresh`

## 🆘 Bantuan Lebih Lanjut

Jika masih ada error, cek:
1. PHP version: `php --version` (harus 8.1+)
2. Composer version: `composer --version`
3. PHP extensions: `php -m` (cek sqlite3, pdo_sqlite, mbstring, curl)
4. File permissions pada folder storage/

---

**Dibuat dengan ❤️ menggunakan Laravel PHP 8.4**