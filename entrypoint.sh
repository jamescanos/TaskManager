#!/bin/sh

# Forzar la optimización de caché en producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones automáticamente
echo "Ejecutando migraciones de base de datos..."
php artisan migrate --force

# CORRECCIÓN IMPORTANTE: Iniciar PHP-FPM en segundo plano (Modo Demonio)
php-fpm -D

# Arrancar el servidor web Nginx en primer plano
echo "Iniciando Nginx..."
nginx -g 'daemon off;'
