# Git Setup & Deployment Fix

## ❌ Error yang Terjadi

```
fatal: not a git repository (or any of the parent directories): .git
=> Deployment failed: An unexpected error occurred during deployment.
```

## ✅ Penyebab Error

Error ini terjadi karena:
1. **Repository belum di-initialize dengan Git**
2. Laravel Forge mencoba `git pull` tapi tidak ada repository
3. Project belum diconnect ke GitHub/GitLab

## 🔧 Solusi Lengkap

### Step 1: Initialize Git Repository (Local)

Buka terminal di folder project dan jalankan:

```powershell
# Initialize Git repository
git init

# Add all files
git add .

# Commit pertama
git commit -m "Initial commit: Laravel Short URL Manager with all features"
```

### Step 2: Create Repository di GitHub

1. **Login ke GitHub:** https://github.com
2. **Create New Repository:**
   - Klik tombol `+` → New repository
   - Repository name: `shorturl` (atau nama lain)
   - Description: "Laravel Short URL Manager with Analytics"
   - **Pilih:** Private (recommended)
   - **Jangan centang:** Add README, .gitignore, license
   - Klik **Create repository**

### Step 3: Connect Local Repository ke GitHub

Setelah create repository di GitHub, akan muncul instruksi. Jalankan di terminal:

```powershell
# Add remote repository
git remote add origin https://github.com/username/shorturl.git

# Push ke GitHub
git branch -M main
git push -u origin main
```

**Ganti `username/shorturl.git` dengan URL repository Anda!**

### Step 4: Setup Laravel Forge dengan GitHub

#### A. Connect GitHub ke Laravel Forge

1. Login ke **Laravel Forge**
2. Go to **Account** → **Source Control**
3. Connect **GitHub** account
4. Authorize Laravel Forge

#### B. Create Site di Laravel Forge

1. Go to **Servers** → Your Server
2. Click **New Site**
3. Configure:
   - **Root Domain:** cs02.online
   - **Project Type:** General PHP/Laravel
   - **Web Directory:** /public
   - **PHP Version:** 8.3 (atau 8.2)
4. Click **Add Site**

#### C. Install Repository

1. Go to your site: **cs02.online**
2. Click **Git Repository** tab
3. Configure:
   - **Provider:** GitHub
   - **Repository:** username/shorturl
   - **Branch:** main
   - **Install Composer Dependencies:** ✅ Checked
4. Click **Install Repository**

### Step 5: Setup Environment Variables

1. Go to **Environment** tab
2. Edit `.env` file dengan values berikut:

```env
APP_NAME="Short URL"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://cs02.online

LOG_CHANNEL=stack
LOG_LEVEL=error

# Database - SQLite
DB_CONNECTION=sqlite
DB_DATABASE=/home/forge/cs02.online/database/database.sqlite

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# StopBot.net API
STOPBOT_API_KEY=a4b14a7c137b0f5f384206940fa11cee
STOPBOT_BLOCKER_URL=https://stopbot.net/api/blocker
STOPBOT_IPLOOKUP_URL=https://stopbot.net/api/iplookup

# Admin Password
ADMIN_PASSWORD=G666
```

3. Click **Save**

### Step 6: Generate APP_KEY

SSH ke server dan generate key:

```bash
ssh forge@cs02.online

cd /home/forge/cs02.online

# Generate application key
php artisan key:generate

# Exit SSH
exit
```

### Step 7: Setup Deployment Script

Di Laravel Forge → **cs02.online** → **Deployment Script**, paste:

```bash
cd /home/forge/cs02.online

# Pull latest code
git pull origin $FORGE_SITE_BRANCH

# Create necessary directories
mkdir -p bootstrap/cache
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p database

# Fix permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R forge:forge storage
chown -R forge:forge bootstrap/cache

# Install/Update Composer dependencies
$FORGE_COMPOSER install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Create SQLite database if not exists
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
    chmod 664 database/database.sqlite
fi

# Clear all cache
$FORGE_PHP artisan config:clear
$FORGE_PHP artisan cache:clear
$FORGE_PHP artisan route:clear
$FORGE_PHP artisan view:clear

# Cache for production
$FORGE_PHP artisan config:cache
$FORGE_PHP artisan route:cache
$FORGE_PHP artisan view:cache

# Run migrations
$FORGE_PHP artisan migrate --force

# Restart PHP-FPM
( flock -w 10 9 || exit 1
    echo 'Restarting FPM...'; sudo -S service $FORGE_PHP_FPM reload ) 9>/tmp/fpmlock

echo "✅ Deployment completed successfully!"
```

### Step 8: Deploy!

1. Click **Deploy Now** button
2. Monitor deployment log
3. Wait hingga sukses

### Step 9: Setup SSL Certificate

1. Go to **SSL** tab
2. Click **Let's Encrypt**
3. Click **Obtain Certificate**
4. Enable **Force HTTPS**

## 📋 Quick Setup Checklist

```
Local Setup:
☐ 1. git init
☐ 2. git add .
☐ 3. git commit -m "Initial commit"
☐ 4. Create GitHub repository
☐ 5. git remote add origin https://github.com/username/repo.git
☐ 6. git push -u origin main

Laravel Forge Setup:
☐ 1. Connect GitHub to Forge
☐ 2. Create site: cs02.online
☐ 3. Install Git repository
☐ 4. Setup Environment variables
☐ 5. SSH → php artisan key:generate
☐ 6. Setup Deployment Script
☐ 7. Deploy Now
☐ 8. Setup SSL Certificate
☐ 9. Test site: https://cs02.online

Post-Deployment:
☐ 1. Test login page
☐ 2. Create test short URL
☐ 3. Test redirect
☐ 4. Check analytics dashboard
☐ 5. Monitor logs
```

## 🔍 Troubleshooting

### Error: "remote: Repository not found"

**Solusi:** Periksa URL repository sudah benar

```powershell
# Check remote URL
git remote -v

# Jika salah, update:
git remote set-url origin https://github.com/username/correct-repo.git
```

### Error: "Permission denied (publickey)"

**Solusi:** Setup SSH key di GitHub atau gunakan HTTPS dengan Personal Access Token

**Untuk HTTPS + Token:**
```powershell
# GitHub → Settings → Developer settings → Personal access tokens → Generate new token
# Pilih scope: repo (full control)
# Copy token

# Push dengan token:
git remote set-url origin https://TOKEN@github.com/username/repo.git
git push -u origin main
```

### Error: "failed to push some refs"

**Solusi:** Pull dulu, lalu push

```powershell
git pull origin main --allow-unrelated-histories
git push -u origin main
```

## 💡 Alternative: Deploy dari Local File (Tanpa Git)

Jika tidak mau menggunakan Git, deploy manual via FTP/SFTP:

1. Upload semua files ke `/home/forge/cs02.online`
2. SSH ke server:
   ```bash
   ssh forge@cs02.online
   cd /home/forge/cs02.online
   
   composer install --no-dev
   php artisan key:generate
   php artisan migrate --force
   php artisan config:cache
   
   chmod -R 775 storage bootstrap/cache
   ```

**Note:** Metode ini tidak recommended karena tidak ada version control.

## 🎯 Recommended Git Workflow

Setelah setup:

```powershell
# Membuat perubahan
# ... edit files ...

# Commit changes
git add .
git commit -m "Description of changes"

# Push ke GitHub
git push origin main

# Laravel Forge akan auto-deploy (jika Quick Deploy enabled)
# Atau klik "Deploy Now" di Forge dashboard
```

## 📚 Resources

- **Git Documentation:** https://git-scm.com/doc
- **GitHub Guides:** https://guides.github.com
- **Laravel Forge Docs:** https://forge.laravel.com/docs
- **GitHub Personal Access Token:** https://github.com/settings/tokens

---

## ✅ Summary

**Error:** `fatal: not a git repository`

**Root Cause:** Project belum di-initialize dengan Git

**Solution:**
1. Initialize Git locally
2. Create GitHub repository
3. Push code ke GitHub
4. Connect GitHub ke Laravel Forge
5. Install repository di Forge
6. Deploy

**Status setelah fix:** ✅ Ready to deploy via Git

---

**Next Step:** Follow Step 1-9 di atas untuk complete setup!