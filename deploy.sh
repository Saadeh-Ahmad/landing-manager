#!/bin/bash

# Quick-Fun-Zain-IQ Deployment Script
# This script handles deployment tasks for the Laravel application

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
PROJECT_DIR="/var/www/html/Quick-Fun"
PHP_ARTISAN="php artisan"

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Starting Deployment${NC}"
echo -e "${GREEN}========================================${NC}"

# Navigate to project directory
cd "$PROJECT_DIR" || exit 1

# Step 1: Pull latest code (if using git)
if [ -d ".git" ]; then
    echo -e "${YELLOW}Step 1: Pulling latest code...${NC}"
    git pull origin main || git pull origin master || echo "Git pull skipped or failed"
else
    echo -e "${YELLOW}Step 1: Git repository not found, skipping...${NC}"
fi

# Step 2: Install/Update Composer dependencies
echo -e "${YELLOW}Step 2: Installing Composer dependencies...${NC}"
composer install --no-dev --optimize-autoloader --no-interaction

# Step 3: Run database migrations
echo -e "${YELLOW}Step 4: Running database migrations...${NC}"
$PHP_ARTISAN migrate --force

# Step 5: Seed database (optional - uncomment if needed)
 echo -e "${YELLOW}Step 5: Seeding database...${NC}"
 $PHP_ARTISAN db:seed --force

# Step 6: Clear and cache configuration
echo -e "${YELLOW}Step 6: Optimizing Laravel...${NC}"
$PHP_ARTISAN config:clear
$PHP_ARTISAN cache:clear
$PHP_ARTISAN route:clear
$PHP_ARTISAN view:clear

# Step 7: Cache configuration for better performance
echo -e "${YELLOW}Step 7: Caching configuration...${NC}"
$PHP_ARTISAN config:cache
$PHP_ARTISAN route:cache
$PHP_ARTISAN view:cache

# Step 8: Set proper permissions
echo -e "${YELLOW}Step 8: Setting permissions...${NC}"
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || echo "Permission change skipped (may require sudo)"

# Step 9: Restart queue workers (if using supervisor)
if command -v supervisorctl &> /dev/null; then
    echo -e "${YELLOW}Step 9: Restarting queue workers...${NC}"
    supervisorctl restart quickfun-worker:* || echo "Supervisor restart skipped"
else
    echo -e "${YELLOW}Step 9: Supervisor not found, skipping queue restart...${NC}"
    echo -e "${YELLOW}   Remember to restart queue workers manually: php artisan queue:restart${NC}"
fi

# Step 10: Restart PHP-FPM (if applicable)
if command -v systemctl &> /dev/null; then
    echo -e "${YELLOW}Step 10: Restarting PHP-FPM...${NC}"
    sudo systemctl restart php8.2-fpm || sudo systemctl restart php8.1-fpm || sudo systemctl restart php-fpm || echo "PHP-FPM restart skipped"
else
    echo -e "${YELLOW}Step 10: Systemctl not found, skipping PHP-FPM restart...${NC}"
fi

# Step 11: Display deployment summary
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Deployment Completed Successfully!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Next Steps:${NC}"
echo -e "1. Check application: ${GREEN}http://quickfun.local${NC}"
echo -e "2. Monitor logs: ${GREEN}tail -f storage/logs/laravel.log${NC}"
echo -e "3. Run queue worker: ${GREEN}php artisan queue:work${NC}"
echo ""

