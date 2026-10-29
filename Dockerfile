FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libsqlite3-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql pdo_sqlite zip gd mbstring bcmath \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --optimize-autoloader --no-dev --no-interaction --no-scripts

# Path yang dipasang sebagai volume persisten di Railway agar file upload
# (background/frame template) bertahan setelah redeploy.
VOLUME /app/storage/app/public

# Railway: buat storage link saat runtime (volume baru tersambung saat container
# mulai, bukan hanya saat build). Diulang di CMD agar symlink selalu selesai.
RUN php artisan storage:link || true

CMD sh -c "php artisan config:clear && php artisan migrate --force && php artisan db:seed --force || true && php artisan storage:link || true && php artisan serve --host=0.0.0.0 --port=\${PORT:-8080}"