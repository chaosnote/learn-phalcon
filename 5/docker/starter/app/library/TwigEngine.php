<?php

use Phalcon\Mvc\View\Engine\AbstractEngine;
use Twig\Environment;

class TwigEngine extends AbstractEngine
{
    protected Environment $twig;
    protected string $viewsDir;

    public function __construct($view, $di, Environment $twig)
    {
        parent::__construct($view, $di);
        
        $this->_view = $view; 
        
        $this->twig = $twig;
        $this->viewsDir = rtrim($view->getViewsDir(), '/\\') . '/';
    }

    public function render(string $path, mixed $params, bool $mustClean = false): void
    {
        if (!is_array($params)) {
            $params = [];
        }

        try {
            // 1. 計算相對於 viewsDir 的路徑
            $relativePath = str_replace($this->viewsDir, '', $path);
            $relativePath = ltrim($relativePath, '/\\');

            // 2. 透過 Twig 渲染
            $content = $this->twig->render($relativePath, $params);

            // 3. 將渲染結果交還給 Phalcon View
            $this->_view->setContent($content);

        } catch (\Throwable $e) {
            echo '<div style="background: #ffe6e6; color: #900; padding: 20px; border: 2px solid red; font-family: monospace;">';
            echo '<h3>🚨 TwigEngine Render 發生例外錯誤：</h3>';
            echo '<p><strong>錯誤訊息：</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '<p><strong>傳入路徑 ($path)：</strong> ' . htmlspecialchars($path) . '</p>';
            echo '<p><strong>解析出的相對路徑：</strong> ' . htmlspecialchars($relativePath ?? 'N/A') . '</p>';
            echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
            echo '</div>';
            exit;
        }
    }
}