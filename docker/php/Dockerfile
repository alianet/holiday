ARG PHP_VERSION=8.5

FROM php:${PHP_VERSION}-cli AS qa

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN apt-get update \
    && apt-get install -y --no-install-recommends unzip libxml2-dev libonig-dev \
    && for extension in dom mbstring xml xmlwriter; do \
        if ! php -r "exit(extension_loaded('$extension') ? 0 : 1);"; then \
            docker-php-ext-install -j"$(nproc)" "$extension" || exit 1; \
        fi; \
    done \
    && pecl install pcov \
    && docker-php-ext-enable pcov \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY composer.json ./
ARG QA_RUN_ID=default
RUN test -n "${QA_RUN_ID}" \
    && composer install --no-interaction --prefer-dist --no-scripts

COPY . .

RUN composer test && composer analyze
