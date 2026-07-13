#!/bin/sh

# Forzar la optimización de caché en producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones automáticamente
echo "Ejecutando migraciones de base de datos..."
php artisan migrate --force

# Arrancar el servidor web Nginx en primer plano
nginx -g 'daemon off;'
