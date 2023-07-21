FROM php:8.0-fpm

# Añadimos dependencias y utilidades interesantes al sistema como: git, curl, zip, ...:
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libxml2-dev \
    libonig-dev \
    libpng-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    zip \
    unzip

# Una vez finalizado borramos cache y limpiamos los archivos de instalación
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalamos las dependencias y extensiones PHP que necesitaremos en nuestro proyecto como: pdo_mysql o mbstring
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath sockets


RUN echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;

# Instalamos dentro de la imagen la última versión de composer, para ello copiamos la imagen disponible en el repositorio:
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


RUN echo 'alias artisan="php /var/www/artisan"' >> ~/.bashrc

# Definimos el directorio de trabajo dentro de nuestra imagen
WORKDIR /var/www

COPY --chown=www-data:www-data . .

EXPOSE 9000

