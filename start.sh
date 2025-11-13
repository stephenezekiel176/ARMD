#!/bin/bash

# Railway startup script for Laravel
echo "🚀 Starting ARMD Laravel Application..."

# Set permissions
chmod -R 755 storage bootstrap/cache

# Install dependencies if needed
if [ ! -d "vendor" ]; then
    echo "📦 Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader
fi

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Cache configurations
echo "⚡ Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# Start the application
echo "✅ Starting Laravel server on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT
