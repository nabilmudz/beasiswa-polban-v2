# Menggunakan image resmi PHP 8.2 dengan Apache
FROM php:8.2-apache

# Menginstall ekstensi sistem yang dibutuhkan untuk PostgreSQL dan unzip (untuk Composer)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Mengaktifkan modul rewrite Apache (wajib untuk routing Laravel)
RUN a2enmod rewrite

# Menyalin Composer dari image resminya
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Menentukan folder kerja di dalam container
WORKDIR /var/www/html

# Menyalin seluruh file project kamu ke dalam folder kerja
COPY . .

# Menginstall package Laravel (tanpa package testing/dev agar lebih ringan)
RUN composer install --no-dev --optimize-autoloader

# Memberikan hak akses kepada web server untuk menulis file log dan cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Mengarahkan domain utama Apache agar langsung membuka folder /public milik Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf