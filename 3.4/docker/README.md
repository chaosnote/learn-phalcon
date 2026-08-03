# Docker 版本

## 參考網址

1. [Phalcon 官方 Dockerfile] https://github.com/phalcon/cphalcon/tree/master/docker
1. [Phalcon 版本 v3.4.5] https://github.com/phalcon/cphalcon/releases/tag/v3.4.5

## 捷徑
- ln -s '路徑' phalcon_docker

## (建立/安裝)映像檔
- cd ./build-image
- sudo docker build -t phalcon:v3.4.5 -D .

## 運行 docker-compose
* sudo docker-compose up -d
* sudo docker-compose down

## 運行 docker
* sudo docker ps -a
* sudo docker exec -ti `ContainerID` bash
* php -m
* which composer
* sudo docker stop `ContainerID`