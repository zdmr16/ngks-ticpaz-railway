#!/bin/bash

# Railway startup script for NGKS Ticaret Pazarlama

echo "🚀 Starting NGKS Ticaret Pazarlama on Railway..."
echo "📍 PORT: ${PORT:-80}"

# Set PORT default if not provided
export PORT=${PORT:-80}

# Set correct permissions immediately
echo "🔒 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# Generate keys if not exists (quick operations)
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force --no-interaction 2>/dev/null || true
fi

if [ -z "$JWT_SECRET" ]; then
    echo "🔐 Generating JWT secret..."
    php artisan jwt:secret --force --no-interaction 2>/dev/null || true
fi

# Clear essential caches only
echo "🧹 Clearing essential caches..."
php artisan config:clear --no-interaction 2>/dev/null || true
php artisan route:clear --no-interaction 2>/dev/null || true

# Update Apache config with correct PORT
echo "🔧 Configuring Apache for PORT $PORT..."
sed -i "s/\${PORT}/$PORT/g" /etc/apache2/sites-available/000-default.conf

# Enable Apache modules
echo "🔌 Enabling Apache modules..."
a2enmod rewrite headers deflate 2>/dev/null || true

# Start Apache immediately for healthcheck
echo "🚀 Starting Apache server on PORT $PORT..."

# Database setup in background (non-blocking)
(
    echo "⏳ Background: Waiting for database connection..."
    
    # Wait for database with shorter intervals
    for i in {1..30}; do
        if php artisan migrate:status --database=mysql --no-interaction >/dev/null 2>&1; then
            echo "✅ Background: Database connection established"
            
            # Run database operations
            echo "📊 Background: Running database migrations..."
            php artisan migrate --force --no-interaction 2>/dev/null || true

            echo "🌱 Background: Running database seeders..."
            php artisan db:seed --force --no-interaction 2>/dev/null || true

            # Optimize for production
            echo "⚡ Background: Optimizing for production..."
            php artisan config:cache --no-interaction 2>/dev/null || true
            php artisan route:cache --no-interaction 2>/dev/null || true

            echo "🎉 Background setup completed!"
            break
        fi
        echo "Background: Database not ready, attempt $i/30..."
        sleep 2
    done
) &

echo "🌐 Frontend: Available at root path /"
echo "🔗 API: Available at /api/*"
echo "✅ Server ready for healthcheck on PORT $PORT"

# Keep Apache running in foreground
exec apache2-foreground