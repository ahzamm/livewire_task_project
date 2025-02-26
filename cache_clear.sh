 
#!/bin/bash

echo "🚀 Clearing Laravel caches..."

# Navigate to Laravel project root (modify if needed)
cd "$(dirname "$0")"

# Clear application cache
php artisan cache:clear
echo "✅ Application cache cleared."

# Clear route cache
php artisan route:clear
echo "✅ Route cache cleared."

# Clear config cache
php artisan config:clear
echo "✅ Config cache cleared."

# Clear view cache
php artisan view:clear
echo "✅ View cache cleared."

# Clear compiled files (if using `optimize`)
php artisan clear-compiled
echo "✅ Compiled files cleared."

# (Optional) Clear event cache
php artisan event:clear
echo "✅ Event cache cleared."

# Restart queue workers (if applicable)
php artisan queue:restart
echo "✅ Queue workers restarted."

# Restart Octane (if using Laravel Octane)
if command -v octane &> /dev/null
then
    php artisan octane:restart
    echo "✅ Laravel Octane restarted."
fi

echo "🎉 All caches cleared successfully!"
