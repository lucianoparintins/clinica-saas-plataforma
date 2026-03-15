FROM php:8.4.1-cli

# Set the working directory
WORKDIR /var/www/html

# Set arguments for user management to match the host UID/GID
ARG USER=developer
ARG UID=1000
ARG GID=1000

# Install system dependencies and PHP extensions required by Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    gnupg \
    procps \
    vim \
    sudo \
    sqlite3 \
    libsqlite3-dev \
    libpq-dev \
    postgresql-client \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip opcache pdo_sqlite pdo_pgsql pgsql

# Install PHP Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js (v22) and NPM
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

# Create a system user that matches the host user to avoid permission issues
RUN groupadd -g $GID $USER || true \
    && useradd -u $UID -g $GID -m -s /bin/bash $USER || true \
    && usermod -aG sudo $USER \
    && echo "$USER ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers

# Ensure the working directory exists and has the correct permissions
RUN mkdir -p /var/www/html \
    && chown -R $USER:$USER /var/www/html

# Switch to the non-root user
USER $USER

# Expose ports for PHP Artisan Serve and Vite
EXPOSE 8000
EXPOSE 5173

# Default command to keep the container running for manual development
# You can override this in your compose.yml to run "php artisan serve" or "composer run dev"
CMD ["tail", "-f", "/dev/null"]
