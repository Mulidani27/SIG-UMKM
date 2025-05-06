# Gunakan image PHP dengan Apache
FROM php:8.1-apache

# Install dependencies yang diperlukan untuk Laravel
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Install Composer (Package manager untuk PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory di dalam container
WORKDIR /var/www/html

# Salin semua file Laravel ke dalam container
COPY . .

# Install dependensi Laravel
RUN composer install --no-dev

# Set permission untuk folder storage dan cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80
