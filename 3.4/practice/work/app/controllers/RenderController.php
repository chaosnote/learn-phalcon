<?php 

use Common\CountryCodeStore ;

/**
 * @property string $date
 * @property string $message
 */
class task {
    public function __construct(string $_date, $_message) {
        $this->date = $_date;
        $this->message = $_message;
    }
}
/**
 * @property array<string,task> $map
 * @property task[] $list
 */
class RenderController extends BaseController{
    public function initialize()
    {
        // $this->view->setTemplateBefore('render_mode0');

        $this->map = [
            "aa"=> new task("0000", "aa"),
            "ab"=> new task("0001", "ab"),
            "ac"=> new task("0002", "ac")
        ] ;
        
        $this->list = [
            new task("0000", "aa"),
            new task("0001", "ab"),
            new task("0002", "ac")
        ] ;

    }
    /** 
     * @Route("/Render/Mode0")
     */
    function Mode0Action(){
        $this->view->setLayout('render_mode0');
        $this->view->setVar('title', 'Mode 0');
        $this->view->setVar('map', $this->map);
        $this->view->setVar('list', $this->list);
        $this->view->pick("render/mode0");
    }
    /** 
     * @Route("/Render/Mode0_0")
     */
    function Mode0_0Action(){
        $countries = new CountryCodeStore();
        $this->view->setVar('title', 'Mode 0-0');
        $this->view->setVar('message', 'partial');
        $this->view->setVar("countries", $countries->store);
    }
    /** 
     * @Route("/Render/Mode1")
     */
    function Mode1Action(){
        $this->view->setLayout('render_mode1');
        $this->view->setVar('title', 'Mode 1');
        $this->view->setVar('map', $this->map);
        $this->view->setVar('list', $this->list);
        $this->view->pick('render/mode1');
        // $content = $this->view->render('page/custom'); // 渲染 Action View 並獲取內容
        // $this->view->setContent($content); // 將內容設定到 View 物件
    }
    /** 
     * @Route("/Render/Mode2")
     */
    function Mode2Action(){
        $this->view->setVar('title', 'Mode 2');
        $this->view->setVar('map', $this->map);
        $this->view->setVar('list', $this->list);
    }
    /** 
     * @Route("/Render/Mode2_0")
     */
    function Mode2_0Action(){
        $countries = new CountryCodeStore();
        $this->view->setVar('title', 'Mode 2-0');
        $this->view->setVar('message', 'partial');
        $this->view->setVar("countries", $countries->store);
    }
}