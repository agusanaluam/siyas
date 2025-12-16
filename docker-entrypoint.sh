#!/bin/bash
set -e

echo "Menjalankan migration database..."
php artisan migrate --force

echo "Menjalankan Apache..."
exec apache2-foreground
