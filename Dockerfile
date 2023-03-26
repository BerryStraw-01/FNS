FROM ubuntu
WORKDIR /temp
RUN apt update \
    && apt install -y curl \
    && curl -sS https://get.symfony.com/cli/installer | bash \
    && apt update \
    && apt install -y git php8.1 composer npm

WORKDIR /data
RUN npm install \
    && composer install
