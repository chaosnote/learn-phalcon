<?php

use Phalcon\Mvc\Controller;

class BaseController extends Controller
{
    use CustomInject ;
    public function initialize() {
        $this->init_inject();
    }
}
