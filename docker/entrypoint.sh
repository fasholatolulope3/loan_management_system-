#!/bin/sh

# Fix permissions at runtime (ensures it works on any platform)
echo "Fixing permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Environment Check
if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is not set!"
else
    echo "APP_KEY is set."
fi

# Force LOG_CHANNEL to stderr if not set (best for Render)
if [ -z "$LOG_CHANNEL" ]; then
    export LOG_CHANNEL=stderr
fi

# Force DB_CONNECTION to pgsql if not set (best for Render)
if [ -z "$DB_CONNECTION" ]; then
    export DB_CONNECTION=pgsql
fi

# Diagnostic: Show database configuration
echo "Current Database Configuration (Diagnostic):"
php artisan config:show database --ansi || echo "Could not show database config."
echo "Current DB_CONNECTION: $DB_CONNECTION"

if [ "$APP_DEBUG" = "false" ]; then
    echo "Caching configuration..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
else
    echo "Debug mode detected, skipping cache..."
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
fi

# Run migrations (force)
# Only run if DB is reachable
echo "Waiting for database and running migrations..."
php artisan migrate --force --ansi || echo "Migrations failed, but continuing..."

# Start Supervisord
echo "Starting Supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
