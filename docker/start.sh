#!/bin/sh
# Container entrypoint for the Laravel payroll app on Railway.
set -e

cd /app

# Ensure runtime dirs exist and are writable
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Generate an APP_KEY only if one was not provided via the environment.
# Prefer setting a stable APP_KEY secret on Railway so sessions survive redeploys.
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY not set - generating an ephemeral key for this boot."
    export APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
fi

# config('app.url') must never be null: SetRequestForConsole passes it straight
# into Request::create() during `artisan serve` boot and a null value is a fatal
# TypeError that crash-loops the container. Guarantee a non-empty value here.
if [ -z "$APP_URL" ]; then
    echo "APP_URL not set - defaulting to http://localhost for console boot."
    export APP_URL="http://localhost"
fi

# Discover packages (needed because we install composer deps with --no-scripts).
php artisan package:discover --ansi || true

# Symlink storage for user-uploaded media (spatie/medialibrary, avatars).
php artisan storage:link || true

# NOTE: We intentionally do NOT run `artisan config:cache`.
# Several controllers call env('STRIPE_*') directly at runtime; caching config
# would make those env() calls return null. Route cache is also skipped to avoid
# breaking any closure-based routes.

# Opt-in migrations/seeding for a fresh database. Off by default because the
# normal path is importing an existing mysqldump.
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "RUN_MIGRATIONS=true -> running migrations"
    php artisan migrate --force || true
    if [ "$RUN_SEEDERS" = "true" ]; then
        echo "RUN_SEEDERS=true -> running seeders"
        php artisan db:seed --force || true
    fi
fi

# Opt-in demo data (12 months of realistic sample payroll/invoice data for the
# AI features). Safe: DemoDataSeeder refuses to run if employees already exist.
if [ "$RUN_DEMO_SEEDER" = "true" ]; then
    echo "RUN_DEMO_SEEDER=true -> seeding demo data"
    php artisan db:seed --class=DemoDataSeeder --force || true
fi

echo "Starting Laravel on 0.0.0.0:${PORT:-8000}"
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
