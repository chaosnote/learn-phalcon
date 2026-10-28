<?php
declare(strict_types=1);

class RenderController extends ControllerBase
{
    public function indexAction()
    {
        /*
        try {
            // 直接手動測試 Twig 是否能正常初始化並渲染
            $loader = new \Twig\Loader\ArrayLoader([
                'test' => 'Hello {{ name }}! Twig 核心運作正常！',
            ]);
            $twig = new \Twig\Environment($loader);
            
            $output = $twig->render('test', ['name' => 'Phalcon 開發者']);
            
            echo $output;
            exit; // 強制中斷，避免進入 Phalcon 預設視圖渲染

        } catch (\Throwable $e) {
            echo "🚨 Twig 測試報錯：" . $e->getMessage() . "<br>";
            echo "檔案：" . $e->getFile() . " (第 " . $e->getLine() . " 行)";
            exit;
        }
        */
        $this->view->setVar('title', 'Phalcon v5 Twig 測試');
        $this->view->pick('render/index');
    }
}