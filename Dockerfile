# Build like: docker build -t converter .
# Use like: docker run --rm -t -v $(pwd):/project -w /project converter
ARG PHP_VERSION=7.4
FROM php:${PHP_VERSION}-cli-alpine as base

FROM base as builder

WORKDIR /project

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer global config minimum-stability dev \
    && composer global config prefer-stable true

RUN composer global require --prefer-dist humbug/box

COPY composer.* ./

RUN composer install --prefer-dist --no-dev --no-progress --no-suggest

COPY . .

RUN $HOME/.composer/vendor/bin/box compile

FROM base
COPY --from=builder /project/bin/converter.phar /usr/bin/converter
ENTRYPOINT ["/usr/bin/converter"]
CMD ["-h"]
