# Multi-stage build for Railway deployment
FROM node:18-alpine AS frontend-builder

# Frontend build
WORKDIR /app/frontend
COPY frontend/package*.json ./
RUN npm install
COPY frontend/ ./
RUN npm run build

# Backend setup with built frontend
FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Enable Apache modules
RUN a2enmod rewrite headers deflate

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy backend files
COPY backend/ .

# Copy built frontend
COPY --from=frontend-builder /app/frontend/dist ./public/frontend

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Configure Apache with dynamic port support
COPY railway/apache-config.conf /etc/apache2/sites-available/000-default.conf

# Create startup script
COPY railway/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Configure Apache to listen on dynamic port
RUN echo "Listen \${PORT}" > /etc/apache2/ports.conf

# Railway uses dynamic ports, not 80
EXPOSE $PORT

CMD ["/usr/local/bin/start.sh"]