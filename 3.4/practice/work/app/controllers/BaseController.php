<?php

use Phalcon\Mvc\Controller;
use Phalcon\Cache\Backend\Redis;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Flash\Direct as FlashDirect;
use Phalcon\Logger\AdapterInterface;

class BaseController extends Controller
{
    /** @var AdapterInterface $logger */
    protected $logger;
    /** @var Redis $redis */
    protected $redis;
    /** @var DbAdapter $db */
    protected $db;
    /** @var FlashDirect $flash */
    protected $flash;

    public function initialize()
    {
        $date = date("Ymd");
        $this->logger = $this->di->get("logger", ["/home/www-data/" . $date . ".log"]);

        $this->redis = $this->di->get("redis_short");

        $this->db = $this->di->get("db");

        $this->flash = $this->di->get("flash");
    }
}
