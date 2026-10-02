# Laravel 8 payroll app - Railway/Docker image
FROM php:8.3-cli

# System libraries + PHP extensions required by:
# pdo_mysql (DB), gd (dompdf/qrcode/images), zip+xml (phpspreadsheet),
# intl (sluggable/strings), bcmath+mbstring (framework), exif (medialibrary),
# curl (guzzle/stripe/openai), opcache (perf)
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libzip-dev \
        libicu-dev \
        libxml2-dev \
        libonig-dev \
        libcurl4-openssl-dev \
        libmagickwand-dev \
        zip \
        unzip \
        git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        gd \
        zip \
        intl \
        bcmath \
        mbstring \
        xml \
        exif \
        pdo_mysql \
        opcache \
    && printf "\n" | pecl install imagick \
    && docker-php-ext-enable imagick \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer 2 from the official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Sensible PHP runtime settings
RUN { \
        echo "memory_limit=256M"; \
        echo "upload_max_filesize=25M"; \
        echo "post_max_size=30M"; \
        echo "max_execution_time=120"; \
        echo "date.timezone=UTC"; \
    } > /usr/local/etc/php/conf.d/app.ini

WORKDIR /app

# Install dependencies first (better layer caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction || true

# Copy the rest of the application
COPY . .

# Finalize autoloader + vendor
RUN composer install --no-dev --no-scripts --optimize-autoloader --no-interaction

# Runtime writable dirs
RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 8000

CMD ["/usr/local/bin/start.sh"]
