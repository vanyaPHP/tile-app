FROM php:8.2-fpm

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - && apt-get install -y nodejs

WORKDIR /var/www
RUN composer install --no-interaction --optimize-autoloader
RUN php artisan key:generate
CMD ["php-fpm"]
