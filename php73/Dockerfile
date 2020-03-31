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
    # Postgres
    libpq5 libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install -j$(nproc) pgsql pdo_pgsql \
    # MySql
    && docker-php-ext-install -j$(nproc) mysqli pdo_mysql \
    # Other
    && docker-php-ext-install -j$(nproc) gettext zip \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    # Clean
    && apt-get autoremove --purge -y libpq-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/*

# Add apache and php conf from parent
COPY apache.conf /etc/apache2/conf-enabled/apache.conf
COPY php.ini /usr/local/etc/php/conf.d/php.ini

RUN a2enmod rewrite

# Oracle instantclient
ADD instantclient/instantclient-basiclite-linux.x64-19.6.0.0.0dbru.zip /tmp/
ADD instantclient/instantclient-sdk-linux.x64-19.6.0.0.0dbru.zip       /tmp/
ADD instantclient/instantclient-sqlplus-linux.x64-19.6.0.0.0dbru.zip   /tmp/

RUN unzip /tmp/instantclient-basiclite-linux.x64-19.6.0.0.0dbru.zip -d /usr/local/
RUN unzip /tmp/instantclient-sdk-linux.x64-19.6.0.0.0dbru.zip       -d /usr/local/
RUN unzip /tmp/instantclient-sqlplus-linux.x64-19.6.0.0.0dbru.zip   -d /usr/local/

RUN ln -s /usr/local/instantclient_19_6 /usr/local/instantclient
RUN ln -s /usr/local/instantclient/sqlplus /usr/bin/sqlplus

RUN echo 'export LD_LIBRARY_PATH="/usr/local/instantclient/"' >> /root/.bashrc
RUN echo 'umask 002' >> /root/.bashrc

# OCI8 Installation
ENV LD_LIBRARY_PATH /usr/local/instantclient/
RUN pear config-set php_ini /usr/local/etc/php/conf.d/php.ini
RUN echo 'instantclient,/usr/local/instantclient' | pecl install oci8

# PDO_OCI Installation
RUN docker-php-ext-configure pdo_oci --with-pdo-oci=instantclient,/usr/local/instantclient
RUN docker-php-ext-install pdo_oci

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# FROM GCP BUILD AND DEPLOY DOCS
# Use the PORT environment variable in Apache configuration files.
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# MOUNT THE TEMP WWW CONTEXT
ADD ./www /var/www/html

EXPOSE ${PORT}
