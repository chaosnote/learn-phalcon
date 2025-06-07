<?php

// 關閉錯誤顯示
ini_set('display_errors', 1);
// 設定錯誤報告的級別 (可選，如果需要更精細的控制)
// ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
ini_set('error_reporting', E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

require BASE_PATH . '/vendor/autoload.php';

use \Phalcon\Di\FactoryDefault;
use \Phalcon\Db\Adapter\Pdo\Mysql;
use \Phalcon\Loader;
use \Phalcon\Mvc\Application;
use \Phalcon\Mvc\Url;
use \Phalcon\Mvc\View;
use \Phalcon\Cache\Backend\Redis;
use \Phalcon\Cache\Frontend\Data;

use \Phalcon\Flash\Direct;

use \Phalcon\Logger\Factory;
use \Phalcon\Logger\Formatter\Line;
use \Phalcon\Logger\FormatterInterface;

use \Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use \Phalcon\Mvc\View\Engine\Php as PhpEngine; // 引入 PhpEngine
use \Phalcon\Mvc\View\Engine\Twig as TwigEngine; // 引入 TwigEngine

use \Phalcon\Session\Adapter\Files as SessionAdapter;

use \Utils\DIKey;

class CustomFormatter extends Line implements FormatterInterface
{
    public function format($message, $type, $timestamp, $context = null)
    {
        $output = '';
        if (!empty($context) && is_array($context)) {
            $output = ' ' . json_encode($context);
        }

        return parent::format($message . $output, $type, $timestamp);
    }
}

// Register an autoloader
$loader = new Loader();
$loader->registerDirs(
    array(
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/utils/',
        APP_PATH . '/tmp/',
    )
)->register();

// Create a DI
$di = new FactoryDefault();

// Setting up the view component
$di->set('view', function () {
    $view = new View();
    $view->setViewsDir(APP_PATH . '/views/');

    // 設定 Volt 引擎
    $cache_dir = "/home/www-data/cache/volt/";
    if (!is_dir($cache_dir)) {
        mkdir($cache_dir, 0777, true);
    }
    $volt = new VoltEngine($view, $this);
    $volt->setOptions([
        'compiledPath' => $cache_dir,
        'compiledSeparator' => '_',
        'stat' => true, // 在開發模式下建議啟用
    ]);

    // 註冊 PHTML 引擎

    // 設定並註冊 Twig 引擎
    $cache_dir = "/home/www-data/cache/twig/";
    if (!is_dir($cache_dir)) {
        mkdir($cache_dir, 0777, true);
    }
    $twig = new TwigEngine(
        $view,
        $this,
        [
            'cache' => $cache_dir,
            'debug' => true,
            'auto_reload' => true,
        ]
    );

    $view->registerEngines([
        '.volt' => $volt,
        '.phtml' => PhpEngine::class,
        '.twig' => $twig,
    ]);


    return $view;
});

$di->set(DIKey::URL, function () {
    $url = new Url();
    $url->setBaseUri('/');
    return $url;
});

$di->set(DIKey::DB, function () {
    return new Mysql(array(
        "host" => "mariadb",
        "username" => "chris",
        "password" => "123456",
        "dbname" => "simulate"
    ));
});

$di->set(DIKey::REDIS, function ($duration, $index = 0) {
    // 設置緩存期效(必需參數)
    $frontend = new Data([
        'lifetime' => $duration, // 例: 每天 - 86400 ( 亦或是 PHP_INT_MAX )
    ]);
    // 連線配置
    $backend = new Redis($frontend, [
        'host' => "redis",
        'port' => 6379,
        'persistent' => true, // 是否使用持久連線
        'index' => $index, // 指定 Redis 資料庫(預設:0)
    ]);

    return $backend;
});

$di->set(DIKey::LOGGER, function ($path) {
    $options = [
        'name' => $path,
        'adapter' => 'file'
    ];
    $logger = Factory::load($options);
    $logger->setFormatter(new CustomFormatter('[%date%][%type%] %message%'));
    return $logger;
});

$di->set(
    DIKey::FLASH,
    function () {
        $flash = new Direct(
            [
                'error' => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice' => 'alert alert-info',
                'warning' => 'alert alert-warning',
            ]
        );

        return $flash;
    }
);

$di->setShared(DIKey::SESSION, function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});
// Handle the request
try {
    $application = new Application($di);
    $application->session->start();

    echo $application->handle()->getContent();

    // $response = $application->handle();
    // $response->send();
} catch (Exception $e) {
    echo "Exception: ", $e->getMessage();
}
