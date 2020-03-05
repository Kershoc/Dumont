FROM php:7.2-cli

RUN set -ex; \
    apt-get update; \
    apt-get install -y \
        libssl-dev \
        unzip \
        zlib1g-dev \
        --no-install-recommends; \
    docker-php-ext-install sockets; \
    cd /tmp; \
    pecl bundle swoole; \
    docker-php-ext-configure /tmp/swoole \
        --enable-http2 --enable-mysqlnd --enable-openssl --enable-sockets; \
    docker-php-ext-install /tmp/swoole;

WORKDIR /opt/dumontbot
COPY . .
