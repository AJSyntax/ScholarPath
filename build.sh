#!/bin/bash

# Install PHP
curl -sSL https://github.com/php/php-src/archive/php-8.1.0.tar.gz | tar -xz
cd php-src-php-8.1.0
./buildconf
./configure
make
make install
cd ..
NODE_VERSION=18
# Install Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
npm install
npm run build

# Cache Laravel configuration
php artisan config:cache
