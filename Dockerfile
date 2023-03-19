FROM php:8.2-fpm

# set main params
ENV APP_HOME /var/www/html
ARG HOST_UID=1000
ARG HOST_GID=1000
ENV USERNAME=www-data



# install all the dependencies and enable PHP modules
RUN apt-get update && apt-get upgrade -y && apt-get install -y \
    procps \
    nano \
    wget \
    curl \
    git \
    unzip \
    libcurl4-openssl-dev \
    libfreetype6-dev \
    libjpeg-dev \
    libpng-dev \
    libwebp-dev \
    libonig-dev \
    libicu-dev \
    zlib1g-dev \
    libxml2 \
    libxml2-dev \
    libreadline-dev \
    supervisor \
    cron \
    sudo \
    libzip-dev \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        sockets \
        intl \
        opcache \
        mbstring \
        gd \
        curl \
        bcmath \
        zip \
    && rm -rf /tmp/* \
    && rm -rf /var/list/apt/* \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get clean



# install nodejs and npm
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | sudo -E bash -
RUN apt install -y nodejs



# install figma-backup
RUN npm install -g figma-backup@~2.0.2



# create document root, fix permissions for www-data user and change owner to www-data
RUN mkdir -p $APP_HOME/public && \
    mkdir -p /home/$USERNAME && chown $USERNAME:$USERNAME /home/$USERNAME \
    && usermod -o -u $HOST_UID $USERNAME -d /home/$USERNAME \
    && groupmod -o -g $HOST_GID $USERNAME \
    && chown -R ${USERNAME}:${USERNAME} $APP_HOME



# put php config for Laravel
COPY ./docker/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./docker/php.ini /usr/local/etc/php/php.ini



# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN chmod +x /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1



# add supervisor and crontab
RUN mkdir -p /var/log/supervisor
COPY --chown=root:root ./docker/general/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY --chown=root:crontab ./docker/general/cron /var/spool/cron/crontabs/root
RUN chmod 0600 /var/spool/cron/crontabs/root



# set working directory and user
WORKDIR $APP_HOME
USER ${USERNAME}



# copy source files and config file
COPY --chown=${USERNAME}:${USERNAME} . $APP_HOME/
COPY --chown=${USERNAME}:${USERNAME} .env $APP_HOME/.env



# build front
RUN npm install && \
    npm run build && \
    rm -rf node_modules



# install all PHP dependencies
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --optimize-autoloader --no-interaction --no-progress --no-dev;



# run artisan commands
RUN php artisan optimize
RUN php artisan migrate --force -n
RUN php artisan storage:link



# finalization
USER root
EXPOSE 9000
