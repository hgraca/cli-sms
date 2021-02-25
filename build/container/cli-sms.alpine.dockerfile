FROM php:7.2.34-cli-alpine3.12

# `apk --update`    updates indexes before installing
# `apk --no-cache`  doesn't put stuff in the cache, so we don't need to remove it at the end
# `apk --virtual`   lets us uninstall temporary dependencies in one go at the end
# fetch https://dl-cdn.alpinelinux.org/alpine/v3.13/main/x86_64/APKINDEX.tar.gz was hanging, so I had to add the mirror
#   as mentioned here: https://github.com/gliderlabs/docker-alpine/issues/307#issuecomment-649642293
RUN echo "http://dl-4.alpinelinux.org/alpine/v3.12/main" > /etc/apk/repositories && \
    echo "http://dl-4.alpinelinux.org/alpine/v3.12/community" >> /etc/apk/repositories && \
    apk --update add --no-cache --virtual build-dependencies autoconf g++ && \
    apk add --no-cache bash make curl sqlite graphviz && \
    mkdir -p /tmp/pear/cache && \
    pecl install xdebug-2.7.0 && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer && \
    apk del build-dependencies && \
    rm -rf /var/cache/apk/* && \
    pecl clear-cache

COPY ./build/container/php.ini /usr/local/etc/php/php.ini
COPY ./build/container/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /opt/app
