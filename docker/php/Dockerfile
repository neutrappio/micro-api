FROM phalconphp/build:debian-buster

LABEL description="Docker image to build Phalcon on Debian Buster" \
      maintainer="Serghei Iakovlev <serghei@phalconphp.com>" \
      vendor=Phalcon \
      name="com.phalconphp.images.build.buster-7.4"

ENV PATH=/root/composer/vendor/bin:/app/vendor/bin:/app/bin:/app:${PATH}

# Installing php and extentions
RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg \
    && echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list \
    && apt-get update \
    && apt-get install --no-install-recommends -yq \
        mc nano git wget curl zip unzip htop re2c \
        php7.4-cli \
        php7.4-fpm \
        php7.4-dev \
        php7.4-common \
        php7.4-curl \
        php7.4-gettext \
        php7.4-intl \
        php7.4-mbstring \
        php7.4-mysqli \
        php7.4-opcache \
        php7.4-pdo_* \
        php7.4-pgsql \
        php7.4-shmop \
        php7.4-xml \
        php7.4-xml \
        php7.4-zip \
        php7.4-psr \
        php7.4-phalcon \
        php7.4-gd \
        php-pear \
    && apt-get autoremove -y \
    && apt-get autoclean -y \
    && apt-get clean -y \
    && rm -rf /tmp/* /var/tmp/* \
    && find /var/cache/apt/archives /var/lib/apt/lists -not -name lock -type f -delete \
    && find /var/cache -type f -delete \
    && find /var/log -type f | while read f; do echo -n '' > ${f}; done

# Install pg client
RUN apt-get update && apt-get install postgresql-client -y

# Updating and installing pecl exts
RUN pecl update-channels
RUN pecl install psr

# Installing composer
RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
    && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
    && php /tmp/composer-setup.php \
        --no-ansi \
        --install-dir=/usr/local/bin \
        --filename=composer


# Editing www.conf
# RUN echo "#!/bin/sh\nexit 0" > /usr/sbin/policy-rc.d \
#     && sed -i -e "s/^;clear_env = no$/clear_env = no/" /etc/php/7.4/fpm/pool.d/www.conf \
#     && sed -i -e "/listen = .*/c\listen = [::]:9990" /etc/php/7.4/fpm/pool.d/www.conf 

RUN git clone https://github.com/phalcon/phalcon-devtools.git /phalcon-devtools

WORKDIR /phalcon-devtools

RUN ln -s $(pwd)/phalcon /usr/bin/phalcon
RUN chmod ugo+x /usr/bin/phalcon
RUN composer install

WORKDIR /app
EXPOSE 9990

CMD ["php", "-S" , "0.0.0.0:9990", "-t",".htrouter.php","-t","public"]