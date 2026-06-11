#!/bin/sh
set -e

if [ -n "$DB_HOST" ]; then
    echo "Checking database connection on host: $DB_HOST..."
    max_tries=30
    count=0
    
    until php -r "
        try {
            \$connection = getenv('DB_CONNECTION') ?: 'mysql';
            \$host = getenv('DB_HOST');
            \$port = getenv('DB_PORT') ?: '3306';
            \$database = getenv('DB_DATABASE');
            \$username = getenv('DB_USERNAME');
            \$password = getenv('DB_PASSWORD');
            
            if (\$connection === 'mysql') {
                \$dsn = \"mysql:host=\$host;port=\$port;dbname=\$database;charset=utf8mb4\";
                new PDO(\$dsn, \$username, \$password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                echo \"Connection successful.\";
                exit(0);
            }
            exit(0);
        } catch (Exception \$e) {
            echo \$e->getMessage();
            exit(1);
        }
    " > /dev/null 2>&1; do
        count=$((count + 1))
        if [ $count -gt $max_tries ]; then
            echo "Error: Database connection failed after $max_tries attempts. Exiting."
            exit 1
        fi
        echo "Database is not ready yet (attempt $count/$max_tries) - waiting 2 seconds..."
        sleep 2
    done
    echo "Database is ready!"
fi

if [ ! -e "/var/www/html/public/storage" ]; then
    echo "Creating storage link..."
    php artisan storage:link --force
fi

echo "Optimizing application cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

if [ "${RUN_MIGRATIONS:-false}" = "true" ] || [ "${RUN_MIGRATIONS:-false}" = "1" ]; then
    echo "Running database migrations..."
    php artisan migrate --force
fi

echo "Setting runtime permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Starting Supervisor..."
exec "$@"
