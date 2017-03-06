#!/bin/sh

# Basic PHP config, as operations complain without one.
cp php.ini /usr/local/etc/php/php.ini

# Install server dependencies.
apt-get update
apt-get install -y git zip unzip curl

# Crude Composer install.
curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/bin/composer

# Install Dependencies.
cd /var/www/html/
/usr/bin/composer install
