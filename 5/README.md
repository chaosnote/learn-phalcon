# Docker 建立

## 資料夾結構

```
docker-project/
├── Dockerfile                  # 用來建置 Ubuntu + PHP 8.5 + Phalcon 5.23 + FPM 的設定檔
├── docker-compose.yml          # 管理 app (PHP-FPM)、nginx、mariadb、redis 的編排檔
├── data/                       # 資料庫實體檔案存放處 (對應 mariadb 容器內的 /var/lib/mysql)
│   └── mariadb/                # MariaDB 實際資料
│       ├── mysql/
│       ├── performance_schema/
│       └── phalcon_db/         # 資料庫內容
├── etc/
│   └── nginx/
│       └── conf.d/
│           └── default.conf    # 你的 Nginx 虛擬主機設定檔 (fastcgi_pass app:9000;)
├── public/                     # 網站公開根目錄 (Document Root)
│   ├── .htaccess
│   └── index.php               # Phalcon 程式的進入點
├── app/                        # Phalcon 應用程式核心資料夾 (controllers, models, views 等)
├── vendor/                     # Composer 套件資料夾
├── composer.json               # 專案依賴管理檔
└── composer.lock
```

## WSL

```
wsl.exe -d Ubuntu-24.04
```

## docker 語法
> 切換至當前`Dockerfile`目錄

``` 使用 docker compose 運行
sudo docker compose up --build -d
sudo docker ps -a
sudo docker compose down
```

``` 編譯測試
docker build -t ubuntu-phalcon:latest .
sudo docker ps -a
```

``` 刪除舊卷
sudo docker compose down -v
```

``` 安裝 ide-stubs
sudo docker exec -it ubuntu_phalcon_app composer require --dev phalcon/ide-stubs:5.23
或
sudo docker exec -it ubuntu_phalcon_app composer require --dev phalcon/ide-stubs:^5.23
```

``` 已有 composer.json 的情況下
sudo docker exec -it ubuntu_phalcon_app composer install
```

## 檢查是否安裝成功

``` 透過終端機指令檢查 Phalcon 版本
sudo docker exec -it ubuntu_phalcon_app php-fpm8.5 -m | grep phalcon
sudo docker exec -it ubuntu_phalcon_app php -r "echo 'Phalcon Version: ' . Phalcon\Version::get() . PHP_EOL;"
```

``` 檢查所有已安裝的 PHP 模組
sudo docker exec -it ubuntu_phalcon_app php -m | grep -E "phalcon|redis|mysqli|pdo"
```

``` 測試 Redis 連線
sudo docker exec -it ubuntu_phalcon_app php -r "
\$redis = new Redis();
\$redis->connect('redis', 6379);
\$redis->set('test_key', 'Hello Docker Redis!');
echo 'Redis 回應: ' . \$redis->get('test_key') . PHP_EOL;
"
```

``` 測試 MariaDB 連線
sudo docker exec -it ubuntu_phalcon_app php -r "
\$pdo = new PDO('mysql:host=mariadb;dbname=phalcon_db', 'phalcon_user', 'phalcon_pass');
echo 'MariaDB 連線成功！' . PHP_EOL;
"
```