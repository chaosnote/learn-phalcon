FROM composer:2.2 AS composer
FROM php:7.1-fpm

ADD ./extra.ini /usr/local/etc/php/conf.d/

ENV TIMEZONE=Asia/Tokyo
# phalcon version
ENV PHALCON_VERSION=3.4.5

# User/Group globals
ENV MY_USER="phalcon" \
	MY_GROUP="phalcon" \
	MY_UID="1000" \
	MY_GID="1000" \
	PHP_VERSION="7.1" \
	LANG=en_US.UTF-8 \
    LANGUAGE=en_US.UTF-8 \
    LC_ALL=en_US.UTF-8

# User and Group
RUN set -eux && \
	groupadd -g ${MY_GID} -r ${MY_GROUP} && \
	useradd -u ${MY_UID} -m -s /bin/bash -g ${MY_GROUP} ${MY_USER}

### for php-fpm
RUN mkdir -p /home/www-data && \
    chown -R www-data:www-data /home/www-data && \
    chmod -R 777 /home/www-data

# Update and Install Dependencies
# *** 【核心修正部分：方案二 - 直接覆蓋 sources.list 檔案】 ***
# 這是處理已歸檔的 Debian 版本（如 Buster）最可靠的方法，避免複雜的 sed 替換錯誤。
RUN set -eux; \
    # 覆蓋為指向 archive.debian.org 的精簡 buster 套件庫
    echo "deb http://archive.debian.org/debian buster main contrib non-free" > /etc/apt/sources.list; \
    # 添加安全更新的歸檔庫 (如果仍存在的話，通常是必要的)
    echo "deb http://archive.debian.org/debian-security buster/updates main contrib non-free" >> /etc/apt/sources.list; \
    \
    # 執行更新和安裝
    apt update -y; \
    apt install -y --no-install-recommends \
        apt-utils \
        gettext \
        git \
        libgmp-dev \
        libicu-dev \
        libmagickwand-dev \
        libmemcached-dev \
        libpng-dev \
        libpq-dev \
        libyaml-dev \
        libwebp-dev \
        libxpm-dev \
        libzip-dev \
        locales \
        nano \
        sudo \
        wget \
        zip \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        vim; \
    \
    # 清理以減少映像大小
    apt autoremove -y; \
    apt clean; \
    rm -rf /var/lib/apt/lists/*

# Remove this RUN when imagick will be available via pecl
RUN cd /opt && \
    git clone https://github.com/Imagick/imagick.git && \
    cd imagick && \
    phpize && ./configure && \
    make && make install

# PECL Packages
RUN pecl install redis-4.0.2 && docker-php-ext-enable redis

RUN pecl install igbinary \
      msgpack \
      apcu \
      yaml \
#      imagick \
      memcached \
      zephir_parser

# Locale
RUN sed -i -e 's/# de_DE.UTF-8 UTF-8/de_DE.UTF-8 UTF-8/' /etc/locale.gen && \
    sed -i -e 's/# el_GR.UTF-8 UTF-8/el_GR.UTF-8 UTF-8/' /etc/locale.gen && \
    sed -i -e 's/# en_US.UTF-8 UTF-8/en_US.UTF-8 UTF-8/' /etc/locale.gen && \
    sed -i -e 's/# es_ES.UTF-8 UTF-8/es_ES.UTF-8 UTF-8/' /etc/locale.gen && \
    sed -i -e 's/# ru_RU.UTF-8 UTF-8/ru_RU.UTF-8 UTF-8/' /etc/locale.gen && \
    dpkg-reconfigure --frontend=noninteractive locales && \
    update-locale LANG=en_US.UTF-8

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype-dir \
    --with-jpeg \
    --with-xpm \
    --with-webp \
    --enable-gd

RUN docker-php-ext-install \
        gd \
        gettext \
        gmp \
        intl \
        pdo_mysql \
        pdo_pgsql \
        zip

# Install PHP extensions
RUN docker-php-ext-enable \
        igbinary \
        msgpack \
        apcu \
        imagick \
        yaml \
        memcached \
        zephir_parser

# phalcon
# ENV PHALCON_VERSION=3.4.5
# curl -LO https://github.com/phalcon/cphalcon/archive/v${PHALCON_VERSION}.tar.gz 
ENV DIR_NAME=cphalcon-3.4.5
# ADD 會自動解壓縮、不需在加指命
ADD ./${DIR_NAME}.tar.gz /tmp
# 安裝並移除不使用的檔案
RUN cd /tmp && \
    cd ${DIR_NAME}/build && ./install && \
    echo "extension=phalcon.so" > /usr/local/etc/php/conf.d/phalcon.ini && \
    cd /tmp && rm -fr ${DIR_NAME}

# Composer
COPY --from=composer /usr/bin/composer /usr/local/bin/composer
# Bash script with helper aliases
COPY ./.bashrc /root/.bashrc
COPY ./.bashrc /home/phalcon/.bashrc

CMD ["php-fpm"]