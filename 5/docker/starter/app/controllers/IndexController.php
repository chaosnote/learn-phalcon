<?php
declare(strict_types=1);

class IndexController extends ControllerBase
{
    
    public function indexAction()
    {
    }
    // index/dbTest
    public function dbTestAction(){
        try {
            // 方法 1：透過 Phalcon 的 DI 容器讀取 config 並使用 Phalcon 內建的 Mysql Adapter
            $config = $this->di->getShared('config');
            
            // 取得設定值
            $dbConfig = $config->database;
            
            // 建立原生 PDO 連線來測試
            $dsn = "mysql:host={$dbConfig->host};dbname={$dbConfig->dbname};charset={$dbConfig->charset}";
            $pdo = new \PDO($dsn, $dbConfig->username, $dbConfig->password);
            
            // 執行查詢測試
            $stmt = $pdo->query("SELECT VERSION() as version");
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            echo "<p style='color: green;'>✔ PDO 連線資料庫成功！</p>";
            echo "<p>MariaDB 版本: " . $result['version'] . "</p>";
            echo "<p>連線主機: " . $dbConfig->host . "</p>";

        } catch (\Exception $e) {
            echo "<p style='color: red;'>❌ PDO 連線失敗：</p>";
            echo "<pre>" . $e->getMessage() . "</pre>";
        }
        
        // 終止後續執行，純看測試結果
        exit();
    }
}