<?php

use Phalcon\Mvc\Controller;
use Phalcon\Cache\Backend\Redis;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Flash\Direct as FlashDirect;
use Phalcon\Logger\AdapterInterface;

class BaseController extends Controller
{
    /** @var AdapterInterface $logger */
    protected $logger ;
    /** @var Redis $redis */
    protected $redis ;
    /** @var DbAdapter $db */
    protected $db ;
    /** @var FlashDirect $flash */
    protected $flash ;

    public function initialize()
    {
        $date = date("Ymd");
        //
        // __CLASS__ 類別名稱、原本預期使用這方式，但這樣子物件需要另外處理
        // 此階段 DI 才準備完成
        //

        $this->logger = $this->di->get("logger", ["../dist/logs/".$date.".log"]) ; 

        $this->redis = $this->di->get("redis_short") ; 

        $this->db = $this->di->get("db") ; 

        $this->flash = $this->di->get("flash") ; 
    }
}
