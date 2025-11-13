# ATOMMART LMS - Production Setup Script
# This script sets up a clean production environment

Write-Host "=== ATOMMART LMS PRODUCTION SETUP ===" -ForegroundColor Cyan
Write-Host ""
Write-Host "⚠️  WARNING: This will delete all existing data!" -ForegroundColor Yellow
Write-Host ""

$confirm = Read-Host "Are you sure you want to continue? (yes/no)"

if ($confirm -ne "yes") {
    Write-Host "Setup cancelled." -ForegroundColor Red
    exit
}

Write-Host "🚀 Starting production setup..." -ForegroundColor Green
Write-Host ""

# Clear config cache
Write-Host "🔄 Clearing configuration cache..." -ForegroundColor Yellow
php artisan config:clear

# Run production seeder
Write-Host "🌱 Running production seeder..." -ForegroundColor Yellow
php artisan db:seed --class=ProductionSeeder

Write-Host ""
Write-Host "✅ Production setup completed successfully!" -ForegroundColor Green
Write-Host ""
Write-Host "=== LOGIN CREDENTIALS ===" -ForegroundColor Cyan
Write-Host "Email: admin@atommart.com" -ForegroundColor White
Write-Host "Password: ADMIN2025!@#" -ForegroundColor White
Write-Host ""
Write-Host "📝 Next steps:" -ForegroundColor Cyan
Write-Host "1. Login as admin" -ForegroundColor White
Write-Host "2. Create your departments" -ForegroundColor White
Write-Host "3. Create facilitators and personnel" -ForegroundColor White
Write-Host "4. Start creating courses!" -ForegroundColor White
Write-Host ""
Write-Host "🔒 Remember to change the default admin password!" -ForegroundColor Yellow
Write-Host ""

Read-Host "Press Enter to exit"
