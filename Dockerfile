FROM php:8.3-apache
RUN mkdir -p /var/lib/php/session \
 && chown www-data:www-data /var/lib/php/session \
 && echo "session.save_path=/var/lib/php/session" > /usr/local/etc/php/conf.d/session.ini
COPY *.php *.html /var/www/html/
