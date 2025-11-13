<?php
/**
 * Railway Deployment Setup Script
 * Run this after deployment to set up the database
 */

echo "🚀 Starting Railway deployment setup...\n";

// Generate application key if not set
if (empty(env('APP_KEY'))) {
    echo "📝 Generating application key...\n";
    exec('php artisan key:generate --force');
}

// Run database migrations
echo "🗄️ Running database migrations...\n";
exec('php artisan migrate --force');

// Create cache tables if using database cache
echo "💾 Setting up cache tables...\n";
exec('php artisan cache:table');
exec('php artisan queue:table');
exec('php artisan session:table');
exec('php artisan migrate --force');

// Seed database if needed
echo "🌱 Seeding database...\n";
exec('php artisan db:seed --force');

// Clear and cache configurations
echo "⚡ Optimizing application...\n";
exec('php artisan config:cache');
exec('php artisan route:cache');
exec('php artisan view:cache');

// Create storage link
echo "🔗 Creating storage link...\n";
exec('php artisan storage:link');

echo "✅ Railway setup completed successfully!\n";
?>
