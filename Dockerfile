FROM php:7.1-cli

RUN apt-get update && \
    apt-get install -y zlib1g-dev && \
    rm -rf /var/lib/apt/lists/* && \
    docker-php-ext-install pdo_mysql mbstring zip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
COPY . .
RUN composer install --no-scripts

CMD ["php", "-S", "0.0.0.0:8000", "-t", "web"]
