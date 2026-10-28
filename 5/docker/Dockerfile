# 基礎映像檔使用 Ubuntu 24.04
FROM ubuntu:24.04

# 避免安裝過程中跳出互動式問答視窗
ENV DEBIAN_FRONTEND=noninteractive

# 1. 更新系統並安裝 PPA 與編譯所需的基礎工具
RUN apt-get update && apt-get install -y \
    software-properties-common \
    lsb-release \
    ca-certificates \
    curl \
    git \
    unzip \
    pkg-config \
    build-essential \
    && rm -rf /var/lib/apt/lists/*

# 2. 加入 PHP PPA 倉庫
RUN add-apt-repository ppa:ondrej/php && apt-get update

# 3. 安裝 PHP 8.5 核心、FPM、開發包以及常用擴充功能與 Redis 驅動
RUN apt-get install -y \
    php8.5-cli \
    php8.5-fpm \
    php8.5-dev \
    php8.5-common \
    php8.5-mysql \
    php8.5-mbstring \
    php8.5-xml \
    php8.5-curl \
    php8.5-zip \
    php8.5-gd \
    php8.5-intl \
    php8.5-redis \
    && rm -rf /var/lib/apt/lists/*

# 4. 從官方 Composer 映像檔複製 Composer 執行檔
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 5. 下載並編譯指定版本 Phalcon v5.23.0 (同時為 CLI 與 FPM 啟用設定)
RUN git clone --depth=1 --branch v5.23.0 https://github.com/phalcon/cphalcon.git /tmp/cphalcon \
    && cd /tmp/cphalcon/ext \
    && phpize \
    && ./configure --enable-phalcon \
    && make -j$(nproc) \
    && make install \
    && echo "extension=phalcon.so" > /etc/php/8.5/cli/conf.d/20-phalcon.ini \
    && echo "extension=phalcon.so" > /etc/php/8.5/fpm/conf.d/20-phalcon.ini \
    && rm -rf /tmp/cphalcon

# 6. 調整 PHP-FPM 設定：改為監聽 9000 埠以供 Nginx 容器連線
RUN sed -i 's/listen = .*/listen = 9000/' /etc/php/8.5/fpm/pool.d/www.conf

# 7. 建立 PHP-FPM 執行所需的 pid 目錄
RUN mkdir -p /run/php

# 設定工作目錄
WORKDIR /var/www/html