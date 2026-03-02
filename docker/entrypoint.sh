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

# Force APP_ENV to production if not set
if [ -z "$APP_ENV" ]; then
    export APP_ENV=production
fi

# Generate .env file from environment variables at runtime
echo "Generating .env file..."
rm -f /var/www/.env
# Capture common Laravel environment variables directly to file
env | grep -E '^(APP_|DB_|LOG_|SESSION_|CACHE_|MAIL_|REDIS_|QUEUE_|FILESYSTEM_|AWS_|VITE_|RENDER)' > /var/www/.env
# Ensure APP_KEY is written if it exists
if [ -n "$APP_KEY" ]; then
    echo "APP_KEY=$APP_KEY" >> /var/www/.env
fi
echo ".env file generated."

# Wait for database and run migrations
echo "Waiting for database and running migrations..."
# We try to run migrations, which will also act as a connectivity check
MAX_RETRIES=5
COUNT=0
while [ $COUNT -lt $MAX_RETRIES ]; do
    php artisan migrate --force --seed --ansi && break
    COUNT=$((COUNT + 1))
    echo "Migration attempt $COUNT failed. Retrying in 5 seconds..."
    sleep 5
done

if [ $COUNT -eq $MAX_RETRIES ]; then
    echo "ERROR: Migrations failed after $MAX_RETRIES attempts."
fi

# Start Supervisord
echo "Starting Supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
