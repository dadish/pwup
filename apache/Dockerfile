FROM php:7.4-apache

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions gd mysqli pdo_mysql zip xdebug

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Copy the processwire specific php configuration
COPY php-processwire.ini "$PHP_INI_DIR/conf.d/"

COPY setup-pw /usr/local/bin/
ENTRYPOINT ["/usr/local/bin/setup-pw"]
CMD ["apache2-foreground"]