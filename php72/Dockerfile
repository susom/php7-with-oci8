FROM php:7.3-apache

RUN apt-get update -qq && \
    apt-get install -yq --no-install-recommends \
    vim \
    zip \
    unzip \
    git \
    ssh \
    wget \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libaio1 \
    libzip-dev \
    && docker-php-ext-install -j$(nproc) gettext zip mysqli \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && rm -r /var/lib/apt/lists/*

# Add apache and php conf from parent
COPY apache.conf /etc/apache2/conf-enabled/apache.conf
COPY php.ini /usr/local/etc/php/conf.d/php.ini

RUN a2enmod rewrite

# Oracle instantclient
ADD instantclient/instantclient-basiclite-linux.x64-19.6.0.0.0dbru.zip /tmp/
ADD instantclient/instantclient-sdk-linux.x64-19.6.0.0.0dbru.zip       /tmp/
ADD instantclient/instantclient-sqlplus-linux.x64-19.6.0.0.0dbru.zip   /tmp/

RUN unzip /tmp/instantclient-basiclite-linux.x64-19.6.0.0.0dbru.zip -d /usr/local/
RUN unzip /tmp/instantclient-sdk-linux.x64-19.6.0.0.0dbru.zip -d       /usr/local/
RUN unzip /tmp/instantclient-sqlplus-linux.x64-19.6.0.0.0dbru.zip -d   /usr/local/

RUN ln -s /usr/local/instantclient_19_6 /usr/local/instantclient
RUN ln -s /usr/local/instantclient/sqlplus /usr/bin/sqlplus

RUN echo 'export LD_LIBRARY_PATH="/usr/local/instantclient/"' >> /root/.bashrc
RUN echo 'umask 002' >> /root/.bashrc

# SET LOCATION OF PHP.INI AND INSTALL OCI8 EXTENSION
ENV LD_LIBRARY_PATH /usr/local/instantclient/
RUN pear config-set php_ini /usr/local/etc/php/conf.d/php.ini
RUN echo 'instantclient,/usr/local/instantclient' | pecl install oci8

EXPOSE 80
