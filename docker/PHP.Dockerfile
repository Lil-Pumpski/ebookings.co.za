FROM php:7.3.33-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libxslt-dev \
    net-tools \
    nano

# Fix for GD in PHP 7.3
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mysqli \
        zip \
        mbstring \
        xml \
        pcntl \
        intl \
        xsl \
        gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Install and enable Xdebug
RUN pecl install xdebug-2.9.8 && docker-php-ext-enable xdebug

# Set working directory
WORKDIR /app
