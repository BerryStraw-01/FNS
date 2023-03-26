FROM ubuntu
WORKDIR /temp
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash \
    && apt update \
    && apt install -y git php8.1 composer symfony-cli npm

WORKDIR /data
RUN npm install \
    && composer install
