FROM phpdockerio/php74-cli

WORKDIR /application

# Install FPM
RUN apt-get update \
    && apt-get -y --no-install-recommends install \
    php7.4-fpm \
#    php7.4-xdebug \
    php7.4-mongodb \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

STOPSIGNAL SIGQUIT

# PHP-FPM packages need a nudge to make them docker-friendly
COPY docker/php/overrides.conf /etc/php/7.4/fpm/pool.d/z-overrides.conf

#COPY docker/php/php-ini-overrides.ini /etc/php/7.4/fpm/conf.d/99-overrides.ini
#COPY docker/php/php-ini-overrides.ini /etc/php/7.4/cli/conf.d/99-overrides.ini

COPY . /application
RUN composer install

CMD ["/usr/sbin/php-fpm7.4", "-O" ]

# Open up fcgi port
EXPOSE 9000
