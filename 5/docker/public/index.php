<?php
echo "<h1>Hello Phalcon 5.23 & PHP 8.5!</h1>";

// 測試 Phalcon 是否載入
if (extension_loaded('phalcon')) {
    echo "<p style='color: green;'>✔ Phalcon 擴充套件已成功載入！ (版本: " . Phalcon\Version::get() . ")</p>";
} else {
    echo "<p style='color: red;'>❌ Phalcon 尚未載入</p>";
}