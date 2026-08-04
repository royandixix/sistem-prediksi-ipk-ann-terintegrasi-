#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")"

echo "== Sistem Prediksi IPK ANN: Setup =="

for command in php composer node npm; do
    if ! command -v "$command" >/dev/null 2>&1; then
        echo "ERROR: $command belum tersedia di PATH."
        exit 1
    fi
done

if [ ! -f .env ]; then
    cp .env.example .env
fi

composer install
npm install
php artisan key:generate --force
php scripts/create_database.php
php artisan thesis:setup --fresh --force
npm run build

echo
echo "Setup selesai. Jalankan: php artisan serve"
echo "Admin    : admin / password123"
echo "Operator : operator / password123"
