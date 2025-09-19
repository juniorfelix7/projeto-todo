# Use uma imagem base do PHP com FPM
FROM php:8.2-fpm

# Instale dependências do sistema e extensões do PHP
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instale o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Defina o diretório de trabalho
WORKDIR /var/www/html

# Copie os arquivos da aplicação
COPY . .

# Instale as dependências do Composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Mude a propriedade dos arquivos para o usuário do PHP-FPM
RUN chown -R www-data:www-data /var/www/html

# Exponha a porta 9000 para o Nginx se comunicar
EXPOSE 9000
