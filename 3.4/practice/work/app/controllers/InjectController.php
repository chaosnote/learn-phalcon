<?php 

/**
 * @property Phalcon\Logger\AdapterInterface $logger
 */
class db_store extends Phalcon\Di\Injectable{
    public function __construct(){
        $this->logger = $this->logger ?? $this->getDI()->get("logger");
        echo "logger :".(isset($this->logger) ? "set" : "unset")."<br/>" ; // set

        $this->logger->debug("logger :".(isset($this->logger) ? "set" : "unset")."<br/>") ; // set
    }
    function Init(){
        $this->di = $this->di ?? $this->getDI();
        echo "di :".(isset($this->di) ? "set" : "unset")."<br/>" ; // set

        $this->db = $this->db ?? $this->getDI()->get("db");
        echo "db :".(isset($this->db) ? "set" : "unset")."<br/>" ; // set

        $this->logger = $this->di->get("logger") ;
        $this->logger->debug("abc") ;
    }
}


class InjectController extends BaseController{
    /** 
     * @Route(/Inject/index)
     */
    function IndexAction(){
        $store = new db_store();
        $store->Init();
    }
}