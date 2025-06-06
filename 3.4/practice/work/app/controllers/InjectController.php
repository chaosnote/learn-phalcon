<?php 

class InjectController extends BaseController{
    /** 
     * @Route(/Inject/index)
     */
    function IndexAction(){
        $x = \Phalcon\Di::getDefault() ;
        echo (isset($x) ? "Yes": "No")."<br>";
        echo ($x == $this->di ? "Yes": "No")."<br>";
    }
}