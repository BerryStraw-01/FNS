FROM bitnami/symfony

#WORKDIR /temp
#RUN apt update \
#    && apt install -y curl \
#    && curl -sS https://get.symfony.com/cli/installer | bash \
#    && apt update \
#    && apt install -y git php8.1 composer npm

WORKDIR /data
#RUN composer install
