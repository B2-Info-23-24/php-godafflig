FROM php:8.2-apache

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Enable Apache mod_rewrite

# Adjust Apache to allow .htaccess files and enable overrides
RUN echo '<Directory "/var/www/html">' > /etc/apache2/conf-available/override.conf \
    && echo '    AllowOverride All' >> /etc/apache2/conf-available/override.conf \
    && echo '</Directory>' >> /etc/apache2/conf-available/override.conf \
    && a2enconf override

# Permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
RUN a2enmod rewrite

RUN service apache2 restart
