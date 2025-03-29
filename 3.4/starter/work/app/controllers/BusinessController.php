<?php

use Phalcon\Filter;

//
// @see https://docs.phalcon.io/3.4/assets/
//

class BusinessController extends BaseController{
	public function indexAction() {
        // print_r($this->router) ;
    }

    //
    // @see https://docs.phalcon.io/3.4/acl/#creating-an-acl
    //  service 與 middleware 時再處理
    //

    /**
     * @Route("/business/post")
     */
    public function postAction($post)
    {
        // $this->view->post = $this->request->$_POST() ;
        $this->view->post = $post;
    }

    /**
     * @Route("/business/filter")
     */
    public function filterAction()
    {
        $filter = new Filter();

        // filter 與驗證不同、因此不會有失敗的情況

        echo $filter->sanitize("hello 123", [Filter::FILTER_INT]); // 過濾出數字
        echo $filter->sanitize(0, [Filter::FILTER_INT]);
    }

    /**
     * @Route("/business/addFilter")
     */
    public function addFilterAction()
    {
        $filter = new Filter();

        $filter->add(
            'md5',
            function ($value) {
                return preg_replace('/[^0-9a-f]/', '', $value);
            }
        );

        // Sanitize with the 'md5' filter
        $filtered = $filter->sanitize("md5 value", 'md5');

        var_dump($filtered);
    }

    /**
     * @Route("/business/customFilter")
     */
    public function customFilterAction()
    {
        $filter = new Filter();

        // Using an object
        $filter->add(
            'ipv4',
            new IPv4Filter()
        );

        // Sanitize with the 'ipv4' filter
        $filteredIp = $filter->sanitize('127.0.0.1', 'ipv4');
        var_dump($filteredIp);
    }

    /**
     * @Route("/business/trigger")
     */
    public function triggerAction()
    {
        // 將請求轉發到 "XXX"Controller 的 "xxx"Action
        $this->dispatcher->forward([
            'controller' => 'index', // 選擇性、未設置即當前控制項
            'action' => 'index',
        ]);
    }

}