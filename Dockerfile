FROM php:8.2-apache

# Extensions PDO MySQL (si tu es en MySQL)
RUN docker-php-ext-install pdo pdo_mysql

# rewrite utile si tu as des routes "propres" (MVC)
RUN a2enmod rewrite

# Mettre /public comme racine du site
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
  /etc/apache2/sites-available/*.conf \
  /etc/apache2/apache2.conf \
  /etc/apache2/conf-available/*.conf

# Copier le projet dans l'image
COPY . /var/www/html