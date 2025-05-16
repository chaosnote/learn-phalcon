<?php 

/**
 * @property Phalcon\Logger\AdapterInterface $logger
 */
class db_store extends Phalcon\Di\Injectable{
    function Init(){
        $this->di = $this->di ?? $this->getDI();
        echo "di :".(isset($this->di) ? "set" : "unset")."<br/>" ; // unset
        
        $this->logger = $this->di->get("logger") ;
        $this->logger->debug("abc") ;
        // 
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