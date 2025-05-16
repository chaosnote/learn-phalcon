<?php 

class RenderController extends BaseController{
    public function initialize()
    {
        // $this->view->setTemplateBefore('render_mode0');
    }
    /** 
     * @Route("/Render/Mode0")
     */
    function Mode0Action(){
        $this->view->setLayout('render_mode0');
        $this->view->setVar('title', 'Mode 0');
        $this->view->pick("render/mode0");
    }
    /** 
     * @Route("/Render/Mode1")
     */
    function Mode1Action(){
        $this->view->setLayout('render_mode1');
        $this->view->setVar('title', 'Mode 1');
        $this->view->pick('render/mode1');
        // $content = $this->view->render('page/custom'); // 渲染 Action View 並獲取內容
        // $this->view->setContent($content); // 將內容設定到 View 物件
    }
    /** 
     * @Route("/Render/Mode2")
     */
    function Mode2Action(){
        $this->view->setVar('title', 'Mode 2');
        // $this->view->render(
        //     'render',  
        //     'mode2',
        //     [
        //         'title'=> 'Mode 2',
        //     ]
        // );
    }
}