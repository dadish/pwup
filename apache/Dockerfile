FROM php:apache

RUN a2enmod rewrite
RUN a2enmod session

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions gd mysqli pdo_mysql zip xdebug

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY setup-pw /usr/local/bin/

ENTRYPOINT ["/usr/local/bin/setup-pw"]

CMD ["apache2-foreground"]