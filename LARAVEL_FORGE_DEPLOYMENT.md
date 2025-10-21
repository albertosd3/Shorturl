# Laravel Forge Deployment - Error Fix & Guide# Laravel Forge Deployment Readiness Assessment



## ❌ Error yang Terjadi## Status: ⚠️ **NOT READY** - Requires Fixes Before Deployment



```---

The /home/forge/cs02.online/releases/57921793/bootstrap/cache directory must be present and writable.

## Critical Issues That Must Be Fixed

Script @php artisan package:discover --ansi handling the post-autoload-dump event returned with error code 1

=> Deployment failed: An unexpected error occurred during deployment.### 1. ❌ Security Issues (BLOCKER)

```

#### Admin Password System

## ✅ Penyebab Error- **Current:** Hardcoded password "G666" in plain text

- **Risk:** Anyone can access admin panel

Error ini terjadi karena:- **Required Fix:**

1. **Folder `bootstrap/cache` tidak ada** di repository  ```php

2. Laravel Forge tidak bisa create cache files saat deployment  // In AuthController.php, change from:

3. Permission issues pada server deployment  if ($request->password === env('ADMIN_PASSWORD', 'G666')) {

  

## 🔧 Solusi yang Sudah Dilakukan  // To use hashed password:

  if (Hash::check($request->password, env('ADMIN_PASSWORD_HASH'))) {

### 1. Created Missing Directories ✅  ```

```  

bootstrap/- **In .env:**

└── cache/  ```env

    └── .gitignore  # Generate hash: php artisan tinker

```  # >>> Hash::make('your-secure-password')

  ADMIN_PASSWORD_HASH=your-hashed-password-here

### 2. Updated .gitignore ✅  ```

Memastikan folder structure ter-commit ke Git tapi isinya di-ignore:

#### API Key Exposure

```gitignore- **Current:** StopBot API key visible in README.md

/bootstrap/cache/*- **Risk:** Public exposure of API credentials

!/bootstrap/cache/.gitignore- **Required Fix:** Remove API key from README.md

```

### 2. ❌ Database Configuration (BLOCKER)

### 3. Created deploy.sh ✅

Deployment script dengan automatic directory creation dan permission fixing.#### SQLite Not Suitable for Production

- **Current:** Using SQLite database

## 🚀 Cara Deploy ke Laravel Forge- **Issues:**

  - No concurrent write support

### Step 1: Push Changes ke GitHub  - File-based (not scalable)

  - No replication/backup

Setelah semua perbaikan, commit dan push:  - Performance issues under load



```bash- **Required Fix:** Switch to MySQL/PostgreSQL

git add .  ```env

git commit -m "Fix: Add bootstrap/cache directory for Laravel Forge deployment"  # In .env for production:

git push origin main  DB_CONNECTION=mysql

```  DB_HOST=127.0.0.1

  DB_PORT=3306

### Step 2: Deployment Script di Laravel Forge  DB_DATABASE=your_database

  DB_USERNAME=your_username

Login ke Laravel Forge → Site → **Deployment Script**, paste script ini:  DB_PASSWORD=your_password

  ```

```bash

cd /home/forge/cs02.online### 3. ⚠️ Performance Issues (HIGH PRIORITY)

git pull origin $FORGE_SITE_BRANCH

#### Synchronous API Calls

# IMPORTANT: Ensure all directories exist with correct permissions- **Issue:** StopBot API called on every redirect (blocks response)

mkdir -p bootstrap/cache- **Impact:** Slow redirects (up to 5 second timeout)

mkdir -p storage/framework/{sessions,views,cache}- **Required Fix:** Implement queue system

mkdir -p storage/logs  ```php

mkdir -p database  // Dispatch job instead of direct call

  TrackClickJob::dispatch($shortCode, $clickType, ...);

# Set correct permissions  ```

chmod -R 775 storage

chmod -R 775 bootstrap/cache#### No Caching

chown -R forge:forge storage- **Issue:** No caching for IP lookups or analytics

chown -R forge:forge bootstrap/cache- **Impact:** Repeated API calls, slow dashboard

- **Required Fix:** Implement Redis caching

# Install/Update Composer dependencies

$FORGE_COMPOSER install --no-interaction --prefer-dist --optimize-autoloader --no-dev### 4. ⚠️ Missing Production Features



# Create SQLite database if not exists#### No Rate Limiting

if [ ! -f database/database.sqlite ]; then- **Issue:** No protection against abuse

    touch database/database.sqlite- **Required Fix:** Add rate limiting middleware

    chmod 664 database/database.sqlite  ```php

fi  Route::middleware('throttle:60,1')->group(function () {

      // Protected routes

# Clear and cache config  });

$FORGE_PHP artisan config:clear  ```

$FORGE_PHP artisan config:cache

#### No Monitoring/Logging

# Clear and cache routes  - **Issue:** No error tracking or performance monitoring

$FORGE_PHP artisan route:clear- **Required Fix:** 

$FORGE_PHP artisan route:cache  - Add Sentry or Bugsnag for error tracking

  - Configure proper logging

# Clear and cache views  - Set up uptime monitoring

$FORGE_PHP artisan view:clear

$FORGE_PHP artisan view:cache#### No Queue System

- **Issue:** All operations synchronous

# Run database migrations- **Required Fix:** Configure Laravel Queue with Redis/Database

$FORGE_PHP artisan migrate --force

---

# Restart PHP-FPM

( flock -w 10 9 || exit 1## Laravel Forge Deployment Checklist

    echo 'Restarting FPM...'; sudo -S service $FORGE_PHP_FPM reload ) 9>/tmp/fpmlock

### Pre-Deployment Requirements

# Clear application cache

$FORGE_PHP artisan cache:clear#### 1. Code Changes Required ❌



echo "Deployment completed successfully!"- [ ] **Fix authentication system**

```  - Implement password hashing

  - Remove hardcoded password

### Step 3: Environment Variables  - Add rate limiting to login



Di Laravel Forge → Site → **Environment**, pastikan .env berisi:- [ ] **Database migration**

  - Change from SQLite to MySQL/PostgreSQL

```env  - Update database configuration

APP_NAME="Short URL"  - Test migrations on production database

APP_ENV=production

APP_KEY=base64:xxxxx  # Generate dengan: php artisan key:generate- [ ] **Performance optimization**

APP_DEBUG=false  - Implement queue system for click tracking

APP_URL=https://cs02.online  - Add Redis caching

  - Make API calls asynchronous

LOG_CHANNEL=stack

LOG_LEVEL=error- [ ] **Security hardening**

  - Remove API keys from README

# Database - SQLite  - Add CSRF protection verification

DB_CONNECTION=sqlite  - Implement rate limiting

DB_DATABASE=/home/forge/cs02.online/database/database.sqlite  - Add input sanitization



# Cache & Session- [ ] **Error handling**

CACHE_DRIVER=file  - Add comprehensive error logging

SESSION_DRIVER=file  - Implement error tracking (Sentry)

SESSION_LIFETIME=120  - Add fallback mechanisms



# StopBot.net API#### 2. Environment Configuration ⚠️

STOPBOT_API_KEY=a4b14a7c137b0f5f384206940fa11cee

STOPBOT_BLOCKER_URL=https://stopbot.net/api/blocker**Required .env variables for production:**

STOPBOT_IPLOOKUP_URL=https://stopbot.net/api/iplookup```env

APP_NAME="Short URL Manager"

# Admin PasswordAPP_ENV=production

ADMIN_PASSWORD=G666APP_KEY=base64:... (generate new for production)

```APP_DEBUG=false

APP_URL=https://yourdomain.com

### Step 4: Deploy!

DB_CONNECTION=mysql

1. Klik **"Deploy Now"** di Laravel ForgeDB_HOST=127.0.0.1

2. Monitor deployment logDB_PORT=3306

3. Jika sukses, akses: https://cs02.onlineDB_DATABASE=production_db

DB_USERNAME=db_user

## 🔍 TroubleshootingDB_PASSWORD=secure_password



### Jika Masih Error "bootstrap/cache must be present":CACHE_DRIVER=redis

QUEUE_CONNECTION=redis

SSH ke server dan jalankan manual:SESSION_DRIVER=redis



```bashREDIS_HOST=127.0.0.1

ssh forge@cs02.onlineREDIS_PASSWORD=null

REDIS_PORT=6379

cd /home/forge/cs02.online

STOPBOT_API_KEY=your_api_key_here

# Create directoriesSTOPBOT_BLOCKER_URL=https://stopbot.net/api/blocker

mkdir -p bootstrap/cacheSTOPBOT_IPLOOKUP_URL=https://stopbot.net/api/iplookup

mkdir -p storage/framework/{sessions,views,cache}

mkdir -p storage/logsADMIN_PASSWORD_HASH=hashed_password_here



# Fix permissionsMAIL_MAILER=smtp

sudo chown -R forge:forge .MAIL_HOST=smtp.mailtrap.io

chmod -R 775 storageMAIL_PORT=2525

chmod -R 775 bootstrap/cacheMAIL_USERNAME=null

MAIL_PASSWORD=null

# Try deployment againMAIL_ENCRYPTION=null

git pull origin mainMAIL_FROM_ADDRESS=noreply@yourdomain.com

composer install --no-dev --optimize-autoloaderMAIL_FROM_NAME="${APP_NAME}"

php artisan config:cache```

php artisan migrate --force

```#### 3. Server Requirements ✅



### Jika Error "Permission denied":**PHP Requirements:**

- [x] PHP 8.4+

```bash- [x] Required extensions:

ssh forge@cs02.online  - [x] BCMath

  - [x] Ctype

cd /home/forge/cs02.online  - [x] cURL

  - [x] DOM

# Fix ownership  - [x] Fileinfo

sudo chown -R forge:forge .  - [x] JSON

  - [x] Mbstring

# Fix permissions  - [x] OpenSSL

find . -type f -exec chmod 664 {} \;  - [x] PCRE

find . -type d -exec chmod 775 {} \;  - [x] PDO

  - [x] Tokenizer

# Special permissions for specific folders  - [x] XML

chmod -R 775 storage

chmod -R 775 bootstrap/cache**Server Software:**

chmod +x artisan- [x] Nginx or Apache

```- [ ] MySQL 8.0+ or PostgreSQL 13+ (currently using SQLite)

- [ ] Redis (not configured)

### Jika Error "Database file not found":- [x] Composer 2.x



```bash#### 4. Laravel Forge Configuration

ssh forge@cs02.online

**Site Settings:**

cd /home/forge/cs02.online```

Web Directory: /public

# Create databasePHP Version: 8.4

touch database/database.sqlite```

chmod 664 database/database.sqlite

chmod 775 database**Deployment Script:**

```bash

# Run migrationscd /home/forge/yourdomain.com

php artisan migrate --forcegit pull origin main

```

# Install dependencies

## 📋 Post-Deployment Checklistcomposer install --no-dev --optimize-autoloader



Setelah deployment sukses, test:# Clear and cache config

php artisan config:cache

- [ ] Site loads: https://cs02.onlinephp artisan route:cache

- [ ] Login page works: https://cs02.online/loginphp artisan view:cache

- [ ] Can login with password: **G666**

- [ ] Can create short URL# Run migrations (careful in production!)

- [ ] Short URL redirects workphp artisan migrate --force

- [ ] Dashboard shows analytics

- [ ] No errors in logs: `tail -f storage/logs/laravel.log`# Restart queue workers

php artisan queue:restart

## 🎯 Quick Commands

# Restart PHP-FPM

### Deploy ulang (setelah push code baru):sudo service php8.4-fpm reload

```bash```

# Di Laravel Forge: Click "Deploy Now"

# Atau via SSH:**Queue Worker:**

ssh forge@cs02.online "cd /home/forge/cs02.online && git pull && composer install --no-dev && php artisan migrate --force && php artisan config:cache"```bash

```php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600

```

### Check logs:

```bash**Scheduler (Cron):**

ssh forge@cs02.online "tail -100 /home/forge/cs02.online/storage/logs/laravel.log"```

```* * * * * cd /home/forge/yourdomain.com && php artisan schedule:run >> /dev/null 2>&1

```

### Clear all cache:

```bash#### 5. SSL Certificate ✅

ssh forge@cs02.online "cd /home/forge/cs02.online && php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear"- [ ] Enable SSL in Forge (Let's Encrypt)

```- [ ] Force HTTPS

- [ ] Update APP_URL to https://

## 🔐 Security Checklist

#### 6. Database Setup ❌

- [x] APP_DEBUG=false in production- [ ] Create MySQL database in Forge

- [x] APP_ENV=production  - [ ] Update .env with database credentials

- [ ] Change ADMIN_PASSWORD from G666 to something secure- [ ] Run migrations: `php artisan migrate --force`

- [ ] Setup SSL (Let's Encrypt) via Forge- [ ] Set up database backups

- [ ] Enable "Force HTTPS" in Forge

- [ ] Setup database backups via Forge#### 7. Redis Setup ❌

- [ ] Monitor logs regularly- [ ] Enable Redis in Forge

- [ ] Update .env with Redis configuration

## 📱 Akses Aplikasi- [ ] Test cache connection



Setelah deployment berhasil:---



- **Main Site:** https://cs02.online## Deployment Steps for Laravel Forge

- **Login:** https://cs02.online/login

- **Password:** G666 (ubah di .env untuk security)### Step 1: Prepare Code



## 💡 Tips Laravel Forge1. **Fix critical issues** (see above)

2. **Test locally** with production-like environment

1. **Auto Deploy:** Enable "Quick Deploy" untuk auto-deploy saat push ke GitHub3. **Commit changes** to Git repository

2. **SSL Certificate:** Go to SSL tab → Let's Encrypt → Enable4. **Push to GitHub/GitLab/Bitbucket**

3. **Database Backup:** Go to Backups tab → Configure automatic backups

4. **Monitoring:** Enable site monitoring untuk uptime alerts### Step 2: Create Site in Forge

5. **Scheduler:** Jika menggunakan scheduled tasks, enable di Forge

1. Log in to Laravel Forge

## ✅ Summary2. Click "New Site"

3. Configure:

**Perbaikan yang dilakukan:**   - Domain: yourdomain.com

1. ✅ Created `bootstrap/cache` directory   - Project Type: Laravel

2. ✅ Updated `.gitignore` properly   - Web Directory: /public

3. ✅ Created comprehensive deployment script   - PHP Version: 8.4

4. ✅ Added permission fixes in deploy script

5. ✅ Created troubleshooting guide### Step 3: Connect Repository



**Next steps:**1. In Forge site settings, go to "Git Repository"

1. Commit dan push changes ke GitHub2. Connect your repository

2. Deploy via Laravel Forge3. Set branch to deploy (e.g., `main` or `production`)

3. Test aplikasi4. Enable "Quick Deploy" if desired

4. Update admin password untuk security

### Step 4: Configure Environment

---

1. Go to "Environment" tab

**Status:** ✅ READY FOR DEPLOYMENT2. Update .env with production values

3. Generate new APP_KEY: `php artisan key:generate`

Error sudah diperbaiki dan aplikasi siap di-deploy ke Laravel Forge!4. Save changes

### Step 5: Set Up Database

1. In Forge, go to "Database"
2. Create new database
3. Update .env with database credentials
4. Run migrations via SSH:
   ```bash
   php artisan migrate --force
   ```

### Step 6: Enable Redis

1. In Forge server settings, enable Redis
2. Update .env:
   ```env
   CACHE_DRIVER=redis
   QUEUE_CONNECTION=redis
   SESSION_DRIVER=redis
   ```

### Step 7: Configure Queue Worker

1. Go to "Queue" tab in Forge
2. Add queue worker:
   - Connection: redis
   - Queue: default
   - Processes: 1 (increase based on load)

### Step 8: Enable SSL

1. Go to "SSL" tab
2. Click "Let's Encrypt"
3. Enable "Force HTTPS"

### Step 9: Deploy

1. Click "Deploy Now" or push to repository
2. Monitor deployment log
3. Check for errors

### Step 10: Test

1. Visit your domain
2. Test login functionality
3. Create short URL
4. Test redirect
5. Check analytics
6. Monitor logs for errors

---

## Post-Deployment Monitoring

### Health Checks

1. **Application Health:**
   - Visit: https://yourdomain.com/up
   - Should return 200 OK

2. **Queue Status:**
   ```bash
   php artisan queue:monitor
   ```

3. **Log Monitoring:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

4. **Database Connection:**
   ```bash
   php artisan tinker
   >>> DB::connection()->getPdo();
   ```

### Performance Monitoring

1. **Response Times:**
   - Use Laravel Telescope (install for debugging)
   - Monitor with New Relic or similar

2. **Queue Performance:**
   - Monitor failed jobs
   - Check queue length

3. **Database Performance:**
   - Monitor slow queries
   - Check connection pool

### Security Monitoring

1. **Failed Login Attempts:**
   - Monitor logs for suspicious activity
   - Consider adding IP blocking

2. **API Usage:**
   - Monitor StopBot API usage
   - Check for rate limit issues

3. **SSL Certificate:**
   - Auto-renews with Let's Encrypt
   - Monitor expiration

---

## Estimated Timeline

### Minimum Required Fixes (Before Deployment)
- Fix authentication: 2-3 hours
- Switch to MySQL: 1-2 hours
- Remove sensitive data from README: 15 minutes
- Test changes: 2-3 hours
- **Total: 5-8 hours**

### Recommended Improvements (For Production)
- Implement queue system: 3-4 hours
- Add Redis caching: 2-3 hours
- Implement rate limiting: 1-2 hours
- Add error tracking: 1-2 hours
- Performance optimization: 3-4 hours
- **Total: 10-15 hours**

### Deployment Process
- Forge setup: 30 minutes
- Initial deployment: 30 minutes
- Testing and verification: 1-2 hours
- **Total: 2-3 hours**

**Grand Total: 17-26 hours of work**

---

## Current Status Summary

### ✅ Ready for Deployment
- [x] Laravel 11 application structure
- [x] Clean, well-organized code
- [x] Comprehensive features implemented
- [x] Database migrations ready
- [x] Environment configuration structure

### ❌ NOT Ready for Deployment
- [ ] Security issues (authentication)
- [ ] Database (SQLite → MySQL)
- [ ] Performance (synchronous API calls)
- [ ] Monitoring/logging
- [ ] Queue system
- [ ] Caching layer
- [ ] Rate limiting
- [ ] Production testing

### ⚠️ Needs Improvement
- [ ] Error handling
- [ ] API key management
- [ ] Backup strategy
- [ ] Scaling considerations
- [ ] Documentation updates

---

## Recommendation

**DO NOT DEPLOY TO PRODUCTION YET**

The application needs critical fixes before it's safe and performant for production use. Follow this priority order:

1. **Critical (Must Fix):**
   - Fix authentication system
   - Switch to MySQL/PostgreSQL
   - Remove API keys from README
   - Test thoroughly

2. **High Priority (Should Fix):**
   - Implement queue system
   - Add Redis caching
   - Add rate limiting
   - Set up monitoring

3. **Medium Priority (Nice to Have):**
   - Performance optimization
   - Advanced error handling
   - Comprehensive testing
   - Documentation

**Estimated time to production-ready: 1-2 weeks of development work**

---

## Alternative: Deploy to Staging First

If you want to deploy immediately for testing:

1. Create a staging site in Forge
2. Deploy current code
3. Test functionality
4. Identify issues
5. Fix issues
6. Test again
7. Then deploy to production

This approach is safer and recommended.

---

## Contact & Support

For Laravel Forge specific issues:
- Documentation: https://forge.laravel.com/docs
- Support: support@laravel.com

For this application:
- Review TEST_RESULTS.md for detailed analysis
- Follow SETUP_GUIDE.md for local testing
- Check TEST_PLAN.md for comprehensive testing

---

**Summary: The code quality is good, but critical production requirements are missing. Fix security and database issues before deploying to Laravel Forge.**
