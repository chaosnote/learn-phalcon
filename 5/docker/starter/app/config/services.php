<?php
declare(strict_types=1);

use Phalcon\Html\Escaper;
use Phalcon\Flash\Direct as Flash;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Session\Adapter\Stream as SessionAdapter;
use Phalcon\Session\Manager as SessionManager;
use Phalcon\Mvc\Url as UrlResolver;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Setting up the view component
 */
$di->setShared('view', function () {
    try {
        $config = $this->getConfig();

        $view = new View();
        $view->setDI($this);
        $view->setViewsDir($config->application->viewsDir);

        $view->registerEngines([
            '.volt' => function ($view) use ($config) {
                $volt = new VoltEngine($view, $this);
                $volt->setOptions([
                    'path' => $config->application->cacheDir . 'volt/',
                    'separator' => '_'
                ]);
                return $volt;
            },
            '.twig' => function ($view) use ($config) {
                $loader = new \Twig\Loader\FilesystemLoader($view->getViewsDir());
                $twigEnv = new \Twig\Environment($loader, [
                    'cache' => $config->application->cacheDir . 'twig/',
                    'auto_reload' => true,
                    'debug' => true,
                ]);
                return new TwigEngine($view, $this, $twigEnv);
            },
            '.phtml' => PhpEngine::class
        ]);

        return $view;

    } catch (\Throwable $e) {
        // 強制在畫面印出詳細錯誤與堆疊追蹤
        echo '<div style="background: #ffe6e6; color: #900; padding: 20px; border: 2px solid red; font-family: monospace; z-index: 99999; position: relative;">';
        echo '<h2>🚨 View Service 發生致命錯誤</h2>';
        echo '<p><strong>錯誤訊息：</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><strong>發生檔案：</strong> ' . $e->getFile() . ' (第 ' . $e->getLine() . ' 行)</p>';
        echo '<h3>堆疊追蹤 (Trace)：</h3>';
        echo '<pre>'. htmlspecialchars($e->getTraceAsString()) . '</pre>';
        echo '</div>';
        exit;
    }
});
/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    return new $class($params);
});


/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    $escaper = new Escaper();
    $flash = new Flash($escaper);
    $flash->setImplicitFlush(false);
    $flash->setCssClasses([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);

    return $flash;
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionManager();
    $files = new SessionAdapter([
        'savePath' => sys_get_temp_dir(),
    ]);
    $session->setAdapter($files);
    $session->start();

    return $session;
});
