# Stage 1: Build assets
FROM node:20-alpine AS node-builder
WORKDIR /app
COPY . .
RUN npm install && npm run build

# Stage 2: Application runtime
FROM php:8.4-fpm-alpine

# Install system dependencies
ENV DB_CONNECTION=pgsql
ENV LOG_CHANNEL=stderr
ENV RENDER=true
ENV APP_ENV=production

RUN apk add --no-cache \
    nginx \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    libzip-dev \
    unzip \
    git \
    curl \
    oniguruma-dev \
    libxml2-dev \
    postgresql-dev \
    supervisor

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Remove potential local .env if it somehow made it into the context
RUN rm -f /var/www/.env

# Copy built assets from node-builder
COPY --from=node-builder /app/public/build /var/www/public/build

# Install composer dependencies
RUN php -v
RUN composer install --no-dev --optimize-autoloader

# Copy nginx, supervisor, and entrypoint configs
COPY ./docker/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/supervisord.conf /etc/supervisord.conf
COPY ./docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Set permissions
RUN chmod +x /usr/local/bin/entrypoint.sh
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Expose port 80
EXPOSE 80

# Start via entrypoint
ENTRYPOINT ["entrypoint.sh"]
