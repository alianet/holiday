ARG PHP_VERSION=8.5

FROM php:${PHP_VERSION}-cli AS development

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

FROM development AS reference-updater

RUN apt-get update \
    && apt-get install -y --no-install-recommends python3 python3-venv \
    && rm -rf /var/lib/apt/lists/* \
    && python3 -m venv /opt/azure-reference
COPY tests/Reference/Azure/requirements.txt /tmp/azure-reference-requirements.txt
RUN /opt/azure-reference/bin/pip install --no-cache-dir -r /tmp/azure-reference-requirements.txt
COPY tests/Reference/Usa/requirements.txt /tmp/usa-reference-requirements.txt
RUN /opt/azure-reference/bin/pip install --no-cache-dir -r /tmp/usa-reference-requirements.txt
ENV USA_REFERENCE_PYTHON=/opt/azure-reference/bin/python
ENV AZURE_REFERENCE_PYTHON=/opt/azure-reference/bin/python

FROM development AS qa

COPY composer.json ./
ARG QA_RUN_ID=default
RUN --mount=type=cache,target=/tmp/composer-cache,sharing=locked \
    test -n "${QA_RUN_ID}" \
    && export COMPOSER_CACHE_DIR=/tmp/composer-cache COMPOSER_MAX_PARALLEL_HTTP=2 \
    && attempt=1 \
    && until composer install --no-interaction --prefer-dist --no-scripts; do \
        if [ "$attempt" -ge 3 ]; then exit 1; fi; \
        delay=$((attempt * 15)); \
        echo "Composer install failed; retrying in ${delay}s..."; \
        sleep "$delay"; \
        attempt=$((attempt + 1)); \
    done

COPY . .

RUN composer test && composer analyze
