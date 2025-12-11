FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy custom Apache config
COPY docker-apache.conf /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Remove local .htaccess (CPanel config) to prevent conflicts in Docker
RUN rm -f /var/www/html/.htaccess

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
