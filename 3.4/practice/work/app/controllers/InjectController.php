<?php 

class db_store extends Phalcon\Di\Injectable{
    use CustomInject;
    public function __construct(){
        $logger = $this->gen_logger();
		$logger->debug("new db_store");
    }
    function Init(){
        $this->di = $this->di ?? $this->getDI();
        echo "di :".(isset($this->di) ? "set" : "unset")."<br/>" ; // set

        $this->db = $this->db ?? $this->getDI()->get("db");
        echo "db :".(isset($this->db) ? "set" : "unset")."<br/>" ; // set

        $logger = $this->gen_logger("logger");
        $logger->debug("abc") ;
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