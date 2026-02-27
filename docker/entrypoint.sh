#!/bin/sh

# Environment Check
if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is not set!"
    # exit 1 # Don't exit yet, let's see if it continues
else
    echo "APP_KEY is set."
fi
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
