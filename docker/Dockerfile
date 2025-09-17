FROM php:8.3-fpm AS app

ENV PHP_EXTS="pdo pdo_mysql zip xml iconv simplexml pcntl gd bcmath opcache"

# Instala dependências e extensões
RUN apt-get update && apt-get install -y \
    libfreetype-dev libzip-dev libpng-dev libjpeg-dev \
    libonig-dev libxml2-dev unzip curl supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install $PHP_EXTS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-source delete \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurações customizadas do PHP, FPM e Xdebug
COPY ./docker/php/extra-php.ini /usr/local/etc/php/conf.d/99_extra.ini
COPY ./docker/php/extra-php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
# Supervisor (caso use múltiplos processos no container)
COPY ./docker/supervisord/supervisord.conf /etc/supervisor/supervisord.conf

# Define diretório da aplicação
WORKDIR /var/www/html
COPY . .

EXPOSE 9000

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
