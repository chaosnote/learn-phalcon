<?php

use Tools\KeyPair;
use Tools\MD5;

/**
 * @property Phalcon\DiInterface $di
 * @property Phalcon\Logger\AdapterInterface $logger
 */
trait store {
    public function Init() {
        echo "logger :".(isset($this->logger) ? "set" : "unset")."<br/>" ;
        echo "di :".(isset($this->di) ? "set" : "unset")."<br/>" ;
    }
}

class TraitController extends BaseController
{
    use MD5 ;
    /**
     * @Route("/Trait")
     */
    public function IndexAction() {
        echo $this->encrypt(
            new KeyPair("abc", "123"),
            new KeyPair("def", "456"),
            new KeyPair("ghi", "789")
        ) ;
    }
    use store ;
    /**
     * @Route("/Trait/Caller")
     * @return void
     */
    public function CallerAction()
    {
        $ref = $this->session->get("test", "abc123") ;
        echo "".$ref."<br/>" ;

        echo "is_post :".($this->request->isPost()?"yes":"no")."<br/>" ;
        echo "is_get :".($this->request->isGet()?"yes":"no")."<br/>" ;

        $this->init_inject();
    }
}
