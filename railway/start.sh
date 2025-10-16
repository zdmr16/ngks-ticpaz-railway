#!/bin/bash

# Railway startup script for NGKS Ticaret Pazarlama

echo "🚀 Starting NGKS Ticaret Pazarlama on Railway..."

# Set correct permissions immediately
echo "🔒 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate keys if not exists (quick operations)
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force --no-interaction
fi

if [ -z "$JWT_SECRET" ]; then
    echo "🔐 Generating JWT secret..."
    php artisan jwt:secret --force --no-interaction
fi

# Clear essential caches only
echo "🧹 Clearing essential caches..."
php artisan config:clear --no-interaction
php artisan route:clear --no-interaction

# Start Apache immediately for healthcheck
echo "🚀 Starting Apache server..."
apache2ctl start

# Database setup in background (non-blocking)
(
    echo "⏳ Background: Waiting for database connection..."
    
    # Wait for database with shorter intervals
    for i in {1..30}; do
        if php artisan migrate:status --database=mysql --no-interaction >/dev/null 2>&1; then
            echo "✅ Background: Database connection established"
            break
        fi
        echo "Background: Database not ready, attempt $i/30..."
        sleep 2
    done

    # Run database operations
    echo "📊 Background: Running database migrations..."
    php artisan migrate --force --no-interaction

    echo "🌱 Background: Running database seeders..."
    php artisan db:seed --force --no-interaction

    # Optimize for production
    echo "⚡ Background: Optimizing for production..."
    php artisan config:cache --no-interaction
    php artisan route:cache --no-interaction

    echo "🎉 Background setup completed!"
) &

echo "🌐 Frontend: Available at root path /"
echo "🔗 API: Available at /api/*"
echo "✅ Server ready for healthcheck"

# Keep Apache running in foreground
exec apache2-foreground