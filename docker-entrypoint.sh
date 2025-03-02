#!/bin/sh
set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

if grep -q '^APP_KEY=$' .env; then
    php artisan key:generate
fi

# Run migrations
php artisan migrate --force

# Build manifest
npm run build

# Execute the original command
exec "$@"
