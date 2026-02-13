ARG BASE_IMAGE
FROM $BASE_IMAGE

RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    postgresql-dev \
    && apk add --no-cache \
    freetype \
    libjpeg-turbo \
    libwebp-dev \
    postgresql-client \
    shadow \
    && pecl install \
    pcov \
    redis \
    && docker-php-ext-enable \
    pcov \
    redis \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install \
    bcmath \
    gd \
    pcntl \
    pdo_pgsql \
    && apk del -f .build-deps \
    && rm -rf /tmp/pear

ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

RUN groupmod -g ${PGID} www-data && \
    usermod -u ${PUID} -g www-data www-data && \
    apk del shadow

RUN curl -s https://getcomposer.org/installer | \
    php -- --install-dir=/usr/local/bin/ --filename=composer

RUN mkdir /.composer && \
    mkdir /.config && \
    chown -R www-data:www-data /.composer && \
    chown -R www-data:www-data /.config && \
    chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html

USER www-data
