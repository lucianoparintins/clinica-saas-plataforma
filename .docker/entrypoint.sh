#!/bin/bash

# Ensure permissions on storage and bootstrap/cache
mkdir -p storage/framework/{sessions,views,cache}
find storage -type d -exec chmod 775 {} +
find storage -type f -exec chmod 664 {} +
find bootstrap/cache -type d -exec chmod 775 {} +
find bootstrap/cache -type f -exec chmod 664 {} +
chown -R developer:developer storage bootstrap/cache

# Install dependencies if vendor/ or node_modules/ is missing
if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

if [ ! -d "node_modules" ]; then
    echo "Installing NPM dependencies..."
    npm install
fi

# Ensure .env exists
if [ ! -f ".env" ]; then
    echo ".env not found. Copying .env.example..."
    cp .env.example .env
    php artisan key:generate
fi

# Wait for database to be ready (if using postgres)
if [ "$DB_CONNECTION" = "pgsql" ]; then
    echo "Waiting for PostgreSQL to start..."
    until pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" -d "$DB_DATABASE"; do
      sleep 2
    done
    echo "PostgreSQL is up!"
fi

# Run migrations (be careful with this in production, but fine for local dev)
echo "Running migrations..."
php artisan migrate --force

# Start the dev environment using concurrently from composer.json
# Note: We use --host 0.0.0.0 to make it accessible outside the container
echo "Starting Laravel & Vite..."
npx concurrently -c "#93c5fd,#c4b5fd,#fb7185,#fdba74" \
  "php artisan serve --host=0.0.0.0 --port=8000" \
  "php artisan queue:listen --tries=1 --timeout=0" \
  "php artisan pail --timeout=0" \
  "npm run dev -- --host 0.0.0.0" \
  --names=server,queue,logs,vite --kill-others
