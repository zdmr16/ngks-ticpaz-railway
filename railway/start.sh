#!/bin/bash

# Railway startup script for NGKS Ticaret Pazarlama

echo "🚀 Starting NGKS Ticaret Pazarlama on Railway..."

# Set PORT default if not provided by Railway
export PORT=${PORT:-80}
echo "📍 Using PORT: $PORT"

# Set correct permissions
echo "🔒 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# Update Apache ports configuration
echo "🔧 Configuring Apache ports..."
echo "Listen $PORT" > /etc/apache2/ports.conf

# Update VirtualHost configuration
echo "🔧 Updating VirtualHost configuration..."
sed -i "s/\${PORT}/$PORT/g" /etc/apache2/sites-available/000-default.conf

# Generate Laravel keys if needed
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating Laravel application key..."
    php artisan key:generate --force --no-interaction 2>/dev/null || true
fi

if [ -z "$JWT_SECRET" ]; then
    echo "🔐 Generating JWT secret..."
    php artisan jwt:secret --force --no-interaction 2>/dev/null || true
fi

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear --no-interaction 2>/dev/null || true
php artisan route:clear --no-interaction 2>/dev/null || true

# Create asset symlinks for frontend
echo "🔗 Creating asset symlinks..."
if [ -d "public/frontend/assets" ] && [ ! -L "public/assets" ]; then
    ln -sf frontend/assets public/assets
    echo "✅ Asset symlink created: public/assets -> frontend/assets"
fi

# Test Apache configuration
echo "🔍 Testing Apache configuration..."
apache2ctl configtest

# Start database setup in background
(
    echo "⏳ Background: Database setup starting..."
    sleep 5  # Wait for Apache to start
    
    # Try database operations
    for i in {1..25}; do
        if php artisan migrate:status --no-interaction >/dev/null 2>&1; then
            echo "✅ Background: Database connected"
            echo "🔄 Background: Running migrations..."
            php artisan migrate --force --no-interaction
            echo "🌱 Background: Running seeders..."
            php artisan db:seed --force --no-interaction
            echo "⚡ Background: Caching config..."
            php artisan config:cache --no-interaction 2>/dev/null || true
            echo "🎉 Background: Database setup completed!"
            break
        fi
        echo "Background: Waiting for database... ($i/25)"
        sleep 5
    done
) &

echo "🚀 Starting Apache on PORT $PORT..."
echo "🌐 Frontend: Available at /"
echo "🔗 API: Available at /api/*"
echo "💚 Health check: Available at /health.php"

# Start Apache in foreground
exec apache2-foreground