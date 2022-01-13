FROM php:8.1.1-fpm-alpine

# ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install php composer and extensions
# RUN chmod +x /usr/local/bin/install-php-extensions && \
    # install-php-extensions @composer gd

# Install bash
RUN apk add bash

COPY setup-pw /usr/local/bin/
ENTRYPOINT ["/usr/local/bin/setup-pw"]
CMD ["php-fpm"]
