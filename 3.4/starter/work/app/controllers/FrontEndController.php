<?php

class IPv4Filter
{
    public function filter($value)
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }
}

class FrontEndController extends BaseController
{
    /**
     * @Route("/frontEnd")
     */
    public function indexAction()
    {
        // Add some local CSS resources
        $this->assets->addCss('css/style.css');
        $this->assets->addCss('css/index.css');

        $this->assets->addCss('https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css', false);

        // And some local JavaScript resources
        $this->assets->addJs('js/jquery.js');
        $this->assets->addJs('js/bootstrap.min.js');

        $this->view->message = "hello world";
    }

    /**
     * @Route("/frontEnd/flash")
     */
    public function flashAction()
    {
        // public/index.php
        //  di
        $this->flash->success('abc');
        $this->flash->warning("def");
    }

    //
    // 前端不太適合過多深入、沒必要將效能放在後端、還是使用 mvvm 比較適合
    //
    // view->render
    //
}
