FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json* ./
RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi
COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
RUN npm run build

FROM php:8.2-cli-alpine AS app

RUN apk add --no-cache \
    bash \
    libzip-dev \
    oniguruma-dev \
    unzip \
    curl \
    icu-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        exif \
        gd \
        intl \
        pdo_mysql \
        zip \
    && rm -rf /var/cache/apk/*

WORKDIR /var/www/html

COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build
COPY docker/start.sh /usr/local/bin/start-app

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
        && chown -R www-data:www-data storage bootstrap/cache \
        && chmod +x /usr/local/bin/start-app

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV APP_RUNTIME=web

EXPOSE 10000

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD sh -c 'curl -fsS "http://127.0.0.1:${PORT:-10000}/healthz" >/dev/null || exit 1'

CMD ["sh", "/usr/local/bin/start-app"]
