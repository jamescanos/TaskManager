# Etapa 1: Compilar el frontend con Vite
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Etapa 2: Servidor PHP de producción
FROM php:8.2-fpm-alpine

# Instalar dependencias del sistema y soporte para PostgreSQL
RUN apk add --no-cache nginx postgresql-dev
RUN docker-php-ext-install pdo pdo_pgsql

# Configurar directorio de trabajo
WORKDIR /var/www/html
COPY . .

# Copiar los archivos compilados de Vite desde la Etapa 1
COPY --from=frontend /app/public/build ./public/build

# Instalar dependencias de PHP con Composer
RUN curl -sS https://getcomposer.org | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Dar permisos de escritura a las carpetas internas de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copiar la configuración del servidor web Nginx
COPY ./nginx.conf /etc/nginx/nginx.conf

# Copiar el script que arranca la aplicación y darle permisos de ejecución
COPY ./entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
