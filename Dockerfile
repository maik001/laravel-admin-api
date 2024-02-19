# Instala a imagem do php
FROM php:7.4

# Instala as dependências do php e o composer
RUN apt-get update -y && apt-get install -y openssl zip unzip git 
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && composer self-update
RUN docker-php-ext-install pdo pdo_mysql 

# A pasta /app do container vai receber a cópia da pasta raiz do projeto(.)
WORKDIR /app
COPY . .

# Pacotes do composer e Inicia o servidor especificando o host e a porta
CMD composer install && php artisan serve --host=0.0.0.0

EXPOSE 8001