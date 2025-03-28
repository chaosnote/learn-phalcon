# Docker 版本

## 參考網址

1. [Phalcon 官方 Dockerfile]("https://github.com/phalcon/cphalcon/tree/master/docker")
1. [Phalcon 版本 v3.4.5]("https://github.com/phalcon/cphalcon/releases/tag/v3.4.5")

### 捷徑

    ln -s '路徑' phalcon_docker

### 建立映像檔

    ./build-image
    sudo docker build -t phalcon:v3.4.5 -D .

### 運行

    sudo docker-compose up -d
    sudo docker-compose down
