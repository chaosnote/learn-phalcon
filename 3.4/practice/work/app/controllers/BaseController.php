<?php

use Phalcon\Mvc\Controller;

/** 
 * @property \Phalcon\Logger\AdapterInterface $logger
 * @property \Phalcon\Cache\Backend\Redis $redis
 * @property \Phalcon\Db\Adapter\Pdo\Mysql $db
 * @property \Phalcon\Flash\Direct $flash
 */
class BaseController extends Controller
{
    use CustomInject ;
    public function initialize() {
        $this->init_inject();
    }
}
