<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

use Phalcon\Support\Version;

echo "<h1>Hello Phalcon 5.23 & PHP 8.5!</h1>";

// 測試 Phalcon 是否載入
if (extension_loaded('phalcon')) {
    // 必須先建立實例 (new Version())，再呼叫實例方法 ->get()
    $version = new Version();
    echo "<p style='color: green;'>✔ Phalcon 擴充套件已成功載入！ (版本: " . $version->get() . ")</p>";
} else {
    echo "<p style='color: red;'>❌ Phalcon 尚未載入</p>";
}