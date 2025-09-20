# Dockerfile - Laravel (PHP 8.2 + Composer)

FROM php:8.2-fpm

# Arguments for user setup
ARG user=laravel
ARG uid=1000

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Install Composer globally (from official Composer image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create non-root user for safer development
RUN useradd -G www-data,root -u $uid -d /home/$user -s /bin/bash $user && \
    mkdir -p /home/$user && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www/html

# Switch to the non-root user
USER $user

# Expose PHP-FPM port
EXPOSE 9000

# Entrypoint or CMD not needed — docker-compose will handle that
