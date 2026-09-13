#!/bin/bash
set -e

# Render binds to a dynamic $PORT (defaults to 80 if not passed)
if [ -n "$PORT" ]; then
    sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
    sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/*.conf
fi

# Ensure SQLite file exists if using SQLite
if [ "$DB_CONNECTION" = "sqlite" ] && [ -n "$DB_DATABASE" ]; then
    mkdir -p "$(dirname "$DB_DATABASE")"
    touch "$DB_DATABASE"
    chown www-data:www-data "$DB_DATABASE"
fi

# Optimize Laravel for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Automatically run database migrations if enabled
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force
fi

# Start Apache in the foreground
exec apache2-foreground
