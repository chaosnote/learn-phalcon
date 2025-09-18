<?php

declare(strict_types=1);

// 取出 URI 中的 path 部分（不包含 query string）
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// 如果請求的檔案真實存在，直接交給 PHP 內建伺服器處理
if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    return false;
}

// ✅ 只保留 path，避免把 query string 塞進 _url
$_GET['_url'] = $uri;

require_once __DIR__ . '/public/index.php';
