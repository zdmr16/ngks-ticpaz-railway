#!/bin/bash

# Railway startup script for NGKS Ticaret Pazarlama

echo "🚀 Starting NGKS Ticaret Pazarlama on Railway..."

# Wait for database to be ready
echo "⏳ Waiting for database connection..."
php artisan migrate:status --database=mysql 2>/dev/null
while [ $? -ne 0 ]; do
    echo "Database not ready, waiting 5 seconds..."
    sleep 5
    php artisan migrate:status --database=mysql 2>/dev/null
done

echo "✅ Database connection established"

# Run Laravel setup commands
echo "🔧 Setting up Laravel..."

# Generate application key if not exists
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Generate JWT secret if not exists
if [ -z "$JWT_SECRET" ]; then
    echo "🔐 Generating JWT secret..."
    php artisan jwt:secret --force
fi

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run migrations
echo "📊 Running database migrations..."
php artisan migrate --force

# Run seeders
echo "🌱 Running database seeders..."
php artisan db:seed --force

# Set correct permissions
echo "🔒 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache

echo "🎉 NGKS Ticaret Pazarlama started successfully!"
echo "🌐 Frontend: Available at root path /"
echo "🔗 API: Available at /api/*"

# Start Apache
echo "🚀 Starting Apache server..."
exec apache2-foreground