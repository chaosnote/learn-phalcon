<?php

use \Common\CountryCodeStore ;

use \Utils\ExtraFunc ;

class JsonController extends BaseController{
    /** 
     * @Route("/Json")
     */
    function IndexAction(){
        $countries = new CountryCodeStore();

        $logger = ExtraFunc::GenLogger($this->dispatcher->getControllerName()) ;
        $logger->debug("check", [
            "[0]" => ($countries->validate("886", "900123456") ? "OK" : "NO" ),
            "[1]" => ($countries->validate("88", "900123456") ? "OK" : "NO" ),
        ]) ;

        $this->view->setVar("countries", $countries->store);
        $this->view->setVar("data", json_encode($countries->store));
    }
}