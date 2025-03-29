<?php

class HttpController extends BaseController
{
    /**
     * @Route("/http")
     */
    public function indexAction(){
        echo "http" ;
    }

    /**
     * @Route("/http/post")
     */
    public function postAction()
    {
        // Check if request has made with POST
        if ($this->request->isPost()) {
            // Access POST data
            $customerName = $this->request->getPost('name');
            $customerBorn = $this->request->getPost('email');
        }

        if ($this->request->isGet()) {
            echo $this->request->get("id") ;
        }
    }

    /**
     * @Route("/http/header")
     */
    public function headerAction()
    {
        // @see https://docs.phalcon.io/3.4/request/#working-with-headers
        echo $this->request->getHeader("HTTP_X_REQUESTED_WITH")."<br/>" ;
        echo $this->request->getHeader("user-agent") ;
    }

    /**
     * @Route("/http/res")
     */
    public function resAction()
    {
        // $response->setRawHeader('HTTP/1.1 200 OK');
        // $response->getHeaders
        $this->response->setStatusCode(404, "test code") ;
    }

    /**
     * @Route("/http/redirect")
     */
    public function redirectAction(){
        $this->response->redirect('http'); // 導頁
    }

    //
    // Cache-Control
    //  HTTP 回應標頭，指示瀏覽器或代理伺服器將該頁面快取一天（86400 秒）
    //   $response->setHeader('Cache-Control', 'max-age=86400'); 
    //  這與 Cache 類別不同
    //
}