<?php

trait CustomInject {
    /**
     * @var Phalcon\Session\Adapter\Files $session
     */
    protected $session;
    /**
     * @var Phalcon\Db\Adapter\Pdo\Mysql $db
     */
    protected $db;
    /**
     * @var Phalcon\Flash\Direct $flash
     */
    protected $flash;
    private function check(){
        $flag = !($this instanceof \Phalcon\Mvc\Controller || $this instanceof \Phalcon\Di\Injectable);
        if ($flag) {
            throw new Exception('"錯誤：在當前類別中發生例外。若需要 Phalcon 服務，請確認類別已繼承自 \\Phalcon\\Mvc\\Controller 或 \\Phalcon\\Di\\Injectable。"');
        }
        $this->di = $this->di ?? $this->getDI();
    }
    /**
     * @param string $prefix
     * @return Phalcon\Logger\AdapterInterface
     */
    protected function gen_logger($prefix = ""){
        $this->check();

        $file_name = date("Ymd");
        if(!empty($prefix)){
            $file_name = $prefix."_".$file_name;
        }
        return $this->di->get("logger", ["/home/www-data/" . $file_name . ".log"]);
    }
    /**
     * @param int $duration 持續時間
     * @param int $index DB index
     * @return Phalcon\Cache\Backend\Redis
     */
    protected function gen_redis($duration, $index = 0){
        $this->check();

        return $this->di->get("redis", [$duration, $index]);
    }
    public function init_inject() {
        $this->check() ;

        $this->db = $this->di->get("db");
        $this->flash = $this->di->get("flash");

        $this->session = $this->di->getShared("session");
    }
}
