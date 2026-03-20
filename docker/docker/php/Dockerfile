FROM php:8.1.27-apache
# composer php.iniをコピー
COPY ./php.ini /usr/local/etc/php/
# composer インストール (マルチステージビルド)
COPY --from=composer:2.7.1 /usr/bin/composer /usr/bin/composer
# apt(Advanced Package Tool) パッケージインストール
# PDO driver インストール(mysql)
# mod_rewrite 有効化
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    vim \
    zip \
    && docker-php-ext-install pdo_mysql \
    && a2enmod rewrite
