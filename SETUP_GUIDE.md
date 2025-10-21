# Short URL Manager - Quick Setup Guide

This guide will help you get the application running on Windows 10.

---

## Prerequisites Installation

### Step 1: Install PHP 8.4+

#### Option A: Using XAMPP (Recommended for Beginners)
1. Download XAMPP from: https://www.apachefriends.org/
2. Install XAMPP (includes PHP, Apache, MySQL)
3. Add PHP to system PATH:
   - Open System Properties → Environment Variables
   - Edit PATH variable
   - Add: `C:\xampp\php` (or your XAMPP installation path)
4. Verify installation:
   ```bash
   php -v
   ```

#### Option B: Using Laragon (Recommended for Laravel)
1. Download Laragon from: https://laragon.org/download/
2. Install Laragon (includes PHP, Apache, MySQL, Composer)
3. PHP and Composer will be automatically available
4. Verify installation:
   ```bash
   php -v
   composer --version
   ```

#### Option C: Standalone PHP Installation
1. Download PHP 8.4+ from: https://windows.php.net/download/
2. Extract to `C:\php`
3. Copy `php.ini-development` to `php.ini`
4. Enable required extensions in `php.ini`:
   ```ini
   extension=curl
   extension=fileinfo
   extension=mbstring
   extension=openssl
   extension=pdo_sqlite
   extension=sqlite3
   ```
5. Add to system PATH: `C:\php`
6. Verify: `php -v`

### Step 2: Install Composer

If not already installed with Laragon:

1. Download from: https://getcomposer.org/download/
2. Run the Windows installer
3. Follow installation wizard
4. Verify installation:
   ```bash
   composer --version
   ```

---

## Application Setup

### Step 3: Install Dependencies

Open PowerShell or Command Prompt in the project directory:

```bash
cd "c:\Users\wibu\Documents\New Short"
composer install
```

This will:
- Create the `vendor/` directory
- Install Laravel framework
- Install all required packages
- Set up autoloading

**Expected output:**
```
Loading composer repositories with package information
Installing dependencies from lock file
...
Generating optimized autoload files
```

### Step 4: Configure Environment

1. Verify `.env` file exists (it should already exist)

2. Generate application key:
   ```bash
   php artisan key:generate
   ```

3. Verify `.env` configuration:
   ```env
   APP_NAME="Short URL Manager"
   APP_ENV=local
   APP_KEY=base64:... (generated in previous step)
   APP_DEBUG=true
   APP_URL=http://localhost:8000

   DB_CONNECTION=sqlite
   DB_DATABASE=database/database.sqlite

   STOPBOT_API_KEY=a4b14a7c137b0f5f384206940fa11cee
   STOPBOT_BLOCKER_URL=https://stopbot.net/api/blocker
   STOPBOT_IPLOOKUP_URL=https://stopbot.net/api/iplookup

   ADMIN_PASSWORD=G666
   ```

### Step 5: Set Up Database

1. Verify database file exists:
   ```bash
   # PowerShell
   Test-Path database/database.sqlite
   ```

2. Run migrations to create tables:
   ```bash
   php artisan migrate
   ```

   **Expected output:**
   ```
   Migration table created successfully.
   Migrating: 2024_01_01_000001_create_short_urls_table
   Migrated:  2024_01_01_000001_create_short_urls_table
   Migrating: 2024_01_01_000002_create_rotator_groups_table
   Migrated:  2024_01_01_000002_create_rotator_groups_table
   Migrating: 2024_01_01_000003_create_rotator_urls_table
   Migrated:  2024_01_01_000003_create_rotator_urls_table
   Migrating: 2024_01_01_000004_create_clicks_table
   Migrated:  2024_01_01_000004_create_clicks_table
   ```

3. Verify tables were created:
   ```bash
   php artisan db:show
   ```

### Step 6: Set Storage Permissions

The storage directories should already exist. Verify they're writable:

```bash
# PowerShell - Check if directories exist
Test-Path storage/framework/cache
Test-Path storage/framework/sessions
Test-Path storage/framework/views
Test-Path storage/logs
```

If any are missing, create them:
```bash
New-Item -ItemType Directory -Force -Path storage/framework/cache
New-Item -ItemType Directory -Force -Path storage/framework/sessions
New-Item -ItemType Directory -Force -Path storage/framework/views
New-Item -ItemType Directory -Force -Path storage/logs
```

### Step 7: Start Development Server

```bash
php artisan serve
```

**Expected output:**
```
INFO  Server running on [http://127.0.0.1:8000].

Press Ctrl+C to stop the server
```

---

## Testing the Application

### Step 8: Access the Application

1. **Open your browser** and navigate to:
   - Main site: http://localhost:8000
   - Login page: http://localhost:8000/login

2. **Login to Admin Panel:**
   - Password: `G666`
   - Click "Login"

3. **Test Short URL Creation:**
   - Navigate to URL Management
   - Enter a URL (e.g., https://google.com)
   - Click "Create Short URL"
   - Copy the generated short URL
   - Test the redirect in a new tab

4. **Test Link Rotator:**
   - Navigate to URL Management
   - Create a new rotator
   - Add 2+ URLs
   - Select rotation type (random, sequential, or weighted)
   - Click "Create Rotator"
   - Test the rotator link multiple times

5. **View Analytics:**
   - Go to Dashboard
   - View click statistics
   - Check device/browser breakdown
   - Review geographic data

---

## Troubleshooting

### Issue: "php is not recognized"
**Solution:** PHP is not in system PATH
- Restart your terminal after installing PHP
- Verify PATH includes PHP directory
- Try: `where php` to find PHP location

### Issue: "composer is not recognized"
**Solution:** Composer is not in system PATH
- Restart your terminal after installing Composer
- Reinstall Composer with "Install for all users" option

### Issue: "Class not found" errors
**Solution:** Autoload not generated
```bash
composer dump-autoload
```

### Issue: "No application encryption key"
**Solution:** Generate app key
```bash
php artisan key:generate
```

### Issue: "Database file not found"
**Solution:** Create database file
```bash
# PowerShell
New-Item -ItemType File -Path database/database.sqlite -Force
php artisan migrate
```

### Issue: "Permission denied" on storage
**Solution:** Check directory permissions
- Ensure storage directories exist
- On Windows, usually not an issue
- Try running as Administrator if needed

### Issue: "Connection timeout" on redirects
**Solution:** StopBot API timeout
- This is normal if API is slow
- Application will continue to work
- Check logs: `storage/logs/laravel.log`

### Issue: Migration fails
**Solution:** Reset database
```bash
php artisan migrate:fresh
```
**Warning:** This will delete all data!

---

## Verification Checklist

After setup, verify everything works:

- [ ] PHP version 8.4+ installed
- [ ] Composer installed
- [ ] Dependencies installed (`vendor/` exists)
- [ ] `.env` file configured
- [ ] APP_KEY generated
- [ ] Database migrations run
- [ ] Development server starts
- [ ] Can access http://localhost:8000
- [ ] Can login with password "G666"
- [ ] Can create short URLs
- [ ] Short URLs redirect correctly
- [ ] Can create rotators
- [ ] Rotators work correctly
- [ ] Dashboard displays analytics
- [ ] Click tracking works

---

## Next Steps

Once the application is running:

1. **Change Admin Password**
   - Update `ADMIN_PASSWORD` in `.env`
   - Use a strong password

2. **Configure StopBot API**
   - Get your own API key from https://stopbot.net
   - Update `STOPBOT_API_KEY` in `.env`

3. **Test All Features**
   - Create various short URLs
   - Test all rotation types
   - Verify analytics accuracy
   - Test with different devices

4. **Review Security**
   - See TEST_RESULTS.md for security recommendations
   - Implement rate limiting
   - Add HTTPS in production

5. **Optimize Performance**
   - Enable caching
   - Consider async API calls
   - Monitor performance

---

## Useful Commands

### Development
```bash
# Start server
php artisan serve

# Clear all caches
php artisan optimize:clear

# View routes
php artisan route:list

# Access tinker (REPL)
php artisan tinker

# View logs
Get-Content storage/logs/laravel.log -Tail 50
```

### Database
```bash
# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Reset database (WARNING: deletes data)
php artisan migrate:fresh

# Show database info
php artisan db:show

# Show migration status
php artisan migrate:status
```

### Maintenance
```bash
# Clear application cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear view cache
php artisan view:clear

# Regenerate autoload files
composer dump-autoload
```

---

## Production Deployment

For production deployment on Ubuntu 24.04, see README.md for detailed instructions.

**Key differences for production:**
- Use nginx or Apache instead of `php artisan serve`
- Set `APP_ENV=production` and `APP_DEBUG=false`
- Use MySQL/PostgreSQL instead of SQLite
- Enable caching (`php artisan config:cache`, `php artisan route:cache`)
- Set up proper logging and monitoring
- Use HTTPS only
- Implement proper backup strategy

---

## Support

If you encounter issues:

1. Check `storage/logs/laravel.log` for errors
2. Review TEST_RESULTS.md for known issues
3. Verify all prerequisites are installed
4. Ensure all steps were followed in order
5. Try clearing caches: `php artisan optimize:clear`

---

## Summary

**Minimum Requirements:**
- PHP 8.4+
- Composer 2.x
- SQLite extension

**Setup Time:**
- Prerequisites: 15-30 minutes
- Application setup: 5-10 minutes
- Testing: 10-15 minutes
- **Total: ~30-60 minutes**

**Quick Start (if PHP & Composer installed):**
```bash
cd "c:\Users\wibu\Documents\New Short"
composer install
php artisan key:generate
php artisan migrate
php artisan serve
```

Then open http://localhost:8000 and login with password "G666".

---

**Good luck! 🚀**
