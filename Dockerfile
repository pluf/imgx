# Dockerfile
#
# SEE: https://github.com/docker-library/php/blob/67efd89c36bf15cb5ba096213e0536b2cab5eb38/7.2/stretch/apache/Dockerfile
# SEE: https://hub.docker.com/layers/php/library/php/7.4.5-apache/images/sha256-f615d3f76fd98e1dde792e4ad514175d652e18c9d3400c94783d0c10354bbc95?context=explore
#
FROM php:8.0-apache
LABEL MAINTAINER ViraWeb123 (info@viraweb123.com)

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_ALLOW_SUPERUSER 0

####################################################
# Update os
# Installl all required applications
####################################################
RUN apt-get update \
	&& apt-get install -y --no-install-recommends \
		libonig-dev \
		libzip-dev \
		curl \
		zlib1g-dev \
		zip \
		unzip \
		git \
	####################################################
	# Prepaire source
	####################################################
	&& docker-php-source extract  \
	# Install extensions
	&& docker-php-ext-install bcmath \
	&& docker-php-ext-install zip \
	&& docker-php-ext-install mysqli pdo pdo_mysql \
	####################################################
	# Imagick
	####################################################
	&& apt-get install -y libmagickwand-dev --no-install-recommends \
	&& cd /tmp \
	&& git clone https://github.com/mkoppanen/imagick.git \
	&& cd imagick \ 
	&& phpize \
	&& ./configure \
	&& make \
	&& make install \
	&& docker-php-ext-enable imagick \
	####################################################
	# Enable extensions
	####################################################
	&& a2enmod rewrite \
	####################################################
	# remove chache 
	####################################################
	&& docker-php-source delete \
	# && apt-get autoremove --purge lib*-dev -y \
	&& rm -fR /tmp/* \
	&& rm -rf /var/lib/apt/lists/\* 



RUN cd /var/www/ \
	&& php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
	&& php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
	&& php composer-setup.php \
	&& php -r "unlink('composer-setup.php');" 

COPY composer.json /var/www/
COPY src /var/www/src
COPY html /var/www/html
COPY sql /var/www/sql

RUN cd /var/www/ \
	&&php composer.phar install --no-dev



