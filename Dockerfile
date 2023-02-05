# Instala o container php
FROM php:7.4

# Instala as dependências do php e o composer
RUN apt-get update -y && apt-get install -y openssl zip unzip git 

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && composer self-update
RUN docker-php-ext-install pdo pdo_mysql 

# Instala o xdebug
RUN pecl install -o -f xdebug-3.1.5  \
    && docker-php-ext-enable xdebug

# Diz que a pasta /app do container vai receber a cópia de uma pasta acima(..)
WORKDIR /app
COPY . .
RUN composer install

# Inicia o servidor especificando o host e a porta
CMD php artisan serve --host=0.0.0.0

EXPOSE 8000