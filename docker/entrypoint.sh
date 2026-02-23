#!/bin/sh

# Optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations (force)
# Only run if DB is reachable
echo "Waiting for database..."
php artisan migrate --force --ansi

# Start Supervisord
exec /usr/bin/supervisord -c /etc/supervisord.conf
