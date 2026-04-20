#!/bin/bash
set -e

# ─────────────────────────────────────────────
#  OTAPlatform Docker Entrypoint
# ─────────────────────────────────────────────

echo ""
echo "╔══════════════════════════════════════════╗"
echo "║      OTAPlatform — Container Startup     ║"
echo "╚══════════════════════════════════════════╝"
echo ""

# ── 1. Ensure all required storage directories exist ──
echo "[1/7] Ensuring storage directories exist..."
mkdir -p /var/www/storage/app/public
mkdir -p /var/www/storage/framework/views
mkdir -p /var/www/storage/framework/cache/data
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/logs
mkdir -p /var/www/bootstrap/cache
echo "        Done."
echo ""

# ── 2. Fix permissions (handles Windows bind-mount quirks) ─
echo "[2/7] Setting permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true
chmod -R 777 /var/www/storage
chmod -R 775 /var/www/bootstrap/cache
echo "        Done."
echo ""

# ── 3. Wait for MySQL ──────────────────────────
echo "[3/7] Waiting for MySQL to be ready..."
MAX_TRIES=40
COUNT=0
until php -r "
\$host = getenv('DB_HOST') ?: 'mysql';
\$port = getenv('DB_PORT') ?: '3306';
\$db   = getenv('DB_DATABASE') ?: 'otaplatform';
\$user = getenv('DB_USERNAME') ?: 'root';
\$pass = getenv('DB_PASSWORD') ?: 'root';
try {
    new PDO(\"mysql:host={\$host};port={\$port};dbname={\$db}\", \$user, \$pass);
    exit(0);
} catch (Exception \$e) {
    exit(1);
}
" 2>/dev/null; do
    COUNT=$((COUNT + 1))
    if [ "$COUNT" -ge "$MAX_TRIES" ]; then
        echo "[ERROR] MySQL not available after $MAX_TRIES attempts. Exiting."
        exit 1
    fi
    echo "        MySQL not ready yet ($COUNT/$MAX_TRIES), retrying in 3s..."
    sleep 3
done
echo "        MySQL is ready!"
echo ""

# ── 4. Clear stale bootstrap cache ────────────
echo "[4/7] Clearing stale caches..."
php artisan config:clear  2>/dev/null || true
php artisan cache:clear   2>/dev/null || true
php artisan route:clear   2>/dev/null || true
php artisan view:clear    2>/dev/null || true
echo "        Done."
echo ""

# ── 5. Run database migrations ─────────────────
echo "[5/7] Running migrations..."
php artisan migrate --force
echo "        Migrations complete."
echo ""

# ── 6. Seed database (insertOrIgnore — safe to re-run) ─
echo "[6/7] Seeding database..."
php artisan db:seed --force
echo "        Seeding complete."
echo ""

# ── 7. Create storage symlink ──────────────────
echo "[7/7] Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true
echo "        Done."
echo ""

echo "══════════════════════════════════════════════"
echo "  Setup complete. Starting PHP-FPM..."
echo "  App:         http://localhost:8080"
echo "  phpMyAdmin:  http://localhost:8081"
echo "══════════════════════════════════════════════"
echo ""

exec "$@"
