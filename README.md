```bash
#Docker ps
#Id of container 
#copy past id 
#launch composer docker 
docker-compose up -d
# display id container 
dokcer ps 
#get id 
#for init the db follow these step , connecte to your container with the id 
docker exec -it "idcontainer" /bin/bash  ou /bin/sh
# in my case exec -it "roulemapoule_web_1" /bin/bash
#then execute the file InitDB.php one time
php Modele/InitDB.php 
#DB will get the the data in the web-site
exit 
#i create one admin user for the garage 
#email : admin@fr
#password : admin
```

dockerfile
```
FROM php:8.2-apache

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Enable Apache mod_rewrite

# Copy source files and the .htaccess
COPY src/ /var/www/html/

# Adjust Apache to allow .htaccess files and enable overrides
RUN echo '<Directory "/var/www/html">' > /etc/apache2/conf-available/override.conf \
    && echo '    AllowOverride All' >> /etc/apache2/conf-available/override.conf \
    && echo '</Directory>' >> /etc/apache2/conf-available/override.conf \
    && a2enconf override

# Permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html && chmod 644 /var/www/html/.htaccess
RUN a2enmod rewrite

RUN service apache2 restart

```

docker-compose.yaml
```
version: '3'
services:
  web:
    build: .
    ports:
      - "8080:80"
    volumes:
      - ./src:/var/www/html
      - ./src/.htaccess:/var/www/html/.htaccess
  mysql:
    image: mysql:5.7
    environment:
      MYSQL_ROOT_PASSWORD: secret
      MYSQL_DATABASE: database
      MYSQL_USER: user
      MYSQL_PASSWORD: password
    volumes:
      - db_data:/var/lib/mysql
    ports:
      - "3306:3306"
volumes:
  db_data:

```
