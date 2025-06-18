FROM php:8.1-fpm

# Install dependencies and PHP extensions for Magento 2.4.7
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libxslt1-dev \
    libzip-dev \
    libssl-dev \
    libicu-dev \
    libjpeg-dev \
    libfreetype6-dev \
    redis-server \
    supervisor \
    procps \
    net-tools \
    dos2unix \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip intl soap xsl opcache sockets

# Install Redis PHP extension
RUN pecl install redis && docker-php-ext-enable redis

WORKDIR /var/www/html

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Supervisor config
COPY ./docker/supervisord.conf /etc/supervisord.conf

# Copy and prepare startup script
COPY ./docker/startup.sh /usr/local/bin/startup.sh
RUN dos2unix /usr/local/bin/startup.sh \
    && chmod +x /usr/local/bin/startup.sh
RUN git config --global --add safe.directory /var/www/html

CMD ["/usr/local/bin/startup.sh"]