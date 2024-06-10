FROM php:7.1-fpm

#composer
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --version=1.10.0 --filename=composer

#APT-GET
RUN apt-get update && apt-get install --no-install-recommends -y \
	libzip-dev \
	zlibc \
	zlib1g \
	libmemcached-dev \
	libpng-dev \
	libwebp-dev \
    libjpeg62-turbo-dev \
    libpng-dev libxpm-dev \
    libfreetype6-dev \
	libjpeg62-turbo-dev \
	libmcrypt-dev \
	libpng-dev \
	libxml2-dev

#ZIP & MySQL
RUN docker-php-ext-configure zip --with-libzip \
	&& docker-php-ext-install mysqli pdo pdo_mysql zip \
	&& docker-php-ext-enable mysqli

#MENCACHE
RUN pecl install memcached \
    && docker-php-ext-enable memcached

#APCU
RUN pecl install apcu \
    && pecl install apcu_bc-1.0.3 \
    && docker-php-ext-enable apcu --ini-name 10-docker-php-ext-apcu.ini \
    && docker-php-ext-enable apc --ini-name 20-docker-php-ext-apc.ini
	
#GD
RUN docker-php-ext-configure gd \
    --with-gd \
    --with-webp-dir \
    --with-jpeg-dir \
    --with-png-dir \
    --with-zlib-dir \
    --with-xpm-dir \
    --with-freetype-dir \
    --enable-gd-native-ttf \
	&& docker-php-ext-install gd

#MCRYPT
RUN docker-php-ext-install -j$(nproc) iconv mcrypt \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

#SOAP
RUN docker-php-ext-install soap

#INSTALL_YII
#RUN composer create-project --prefer-dist yiisoft/yii ./var/www/html/

