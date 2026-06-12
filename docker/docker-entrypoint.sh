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

echo "Ensuring storage directories exist..."
mkdir -p /var/www/html/storage/app/public

echo "Creating storage link..."
rm -rf /var/www/html/public/storage
php artisan storage:link --force

echo "Publishing vendor assets..."
php artisan vendor:publish --tag=laravel-assets --ansi --force

echo "Optimizing application cache..."
php artisan config:cache
php artisan route:clear
php artisan view:cache
php artisan event:cache

if [ "${RUN_MIGRATIONS:-false}" = "true" ] || [ "${RUN_MIGRATIONS:-false}" = "1" ]; then
    echo "Running database migrations..."
    if [ "${RUN_SEEDS:-false}" = "true" ] || [ "${RUN_SEEDS:-false}" = "1" ]; then
        echo "Running database migrations with seeding..."
        php artisan migrate --seed --force
    else
        php artisan migrate --force
    fi
fi

echo "Setting runtime permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chown -h www-data:www-data /var/www/html/public/storage
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Set Nginx port dynamically based on Railway's PORT env
if [ -n "$PORT" ]; then
    echo "Configuring Nginx to listen on port $PORT..."
    sed -i "s/listen 80;/listen ${PORT};/g" /etc/nginx/nginx.conf
fi

echo "Starting Supervisor..."
exec "$@"
