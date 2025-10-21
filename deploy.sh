#!/usr/bin/env bash
set -euo pipefail

# Deployment script for Laravel Forge (robust + idempotent)
APP_DIR=${1:-/home/forge/cs02.online}
cd "$APP_DIR"

echo "Deploying to: $APP_DIR"

# Pull latest
git pull origin ${FORGE_SITE_BRANCH:-main}

# Use Forge-provided composer/php when available
COMPOSER_CMD="${FORGE_COMPOSER:-composer}"
PHP_CMD="${FORGE_PHP:-php}"
PHP_FPM_SERVICE="${FORGE_PHP_FPM:-php8.3-fpm}"

echo "Using composer: $COMPOSER_CMD"
echo "Using php: $PHP_CMD"

# Ensure necessary directories exist
mkdir -p bootstrap/cache
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p database

# Ensure sqlite file exists (if using sqlite)
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    if [ ! -f database/database.sqlite ]; then
        touch database/database.sqlite
        chmod 664 database/database.sqlite || true
    fi
fi

# Fix permissions (best effort)
if command -v sudo >/dev/null 2>&1; then
    sudo chown -R forge:forge "$APP_DIR" || true
fi
chmod -R 775 storage bootstrap/cache || true

# Install/update composer dependencies
"$COMPOSER_CMD" install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Clear caches and rebuild
"$PHP_CMD" artisan config:clear || true
"$PHP_CMD" artisan config:cache

"$PHP_CMD" artisan route:clear || true
"$PHP_CMD" artisan route:cache

"$PHP_CMD" artisan view:clear || true
"$PHP_CMD" artisan view:cache

# Run migrations
"$PHP_CMD" artisan migrate --force || true

# Try to reload PHP-FPM if available
if command -v sudo >/dev/null 2>&1; then
    if sudo service --status-all >/dev/null 2>&1; then
        echo "Reloading PHP-FPM ($PHP_FPM_SERVICE)"
        sudo service "$PHP_FPM_SERVICE" reload || true
    fi
fi

"$PHP_CMD" artisan cache:clear || true

echo "Deployment finished successfully."
