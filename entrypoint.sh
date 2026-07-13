#!/bin/sh

# CORRECCIÓN DE PERMISOS CRUCIAL: Asegurar escritura para storage y caché
echo "Ajustando permisos de almacenamiento..."
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Forzar la optimización de caché en producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones automáticamente
echo "Ejecutando migraciones de base de datos..."
php artisan migrate --force

# Iniciar PHP-FPM en segundo plano
php-fpm -D

# Arrancar el servidor web Nginx en primer plano
echo "Iniciando Nginx..."
nginx -g 'daemon off;'
