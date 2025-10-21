cd /home/forge/cs02.online
git pull origin main

# Install/Update Composer dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Clear and cache config
php artisan config:clear
php artisan config:cache

# Clear and cache routes  
php artisan route:clear
php artisan route:cache

# Clear and cache views
php artisan view:clear
php artisan view:cache

# Run database migrations
php artisan migrate --force

# Restart PHP-FPM
( flock -w 10 9 || exit 1
    echo 'Restarting FPM...'; sudo -S service php8.3-fpm reload ) 9>/tmp/fpmlock

# Clear Laravel cache
php artisan cache:clear

# Restart Queue Workers (if using queues)
# php artisan queue:restart