FROM php:8.0-cli

RUN apt-get update && \
    apt-get install -y zlib1g-dev libonig-dev libzip-dev && \
    rm -rf /var/lib/apt/lists/* && \
    docker-php-ext-install pdo_mysql mbstring zip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

CMD ["php", "-S", "0.0.0.0:8000", "-t", "web"]
