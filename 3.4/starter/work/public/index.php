<?php

require __DIR__ . '/../vendor/autoload.php';

use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\View;
use Phalcon\Cache\Backend\Redis;
use Phalcon\Cache\Frontend\Data as FrontendData;
use Phalcon\Logger\Factory;
use Phalcon\Flash\Direct as FlashDirect;

use Phalcon\Events\Event;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Dispatcher;

use Elasticsearch\ClientBuilder;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();
$loader->registerDirs(
    array(
        APP_PATH . '/controllers/',
        APP_PATH . '/models/'
    )
)->register();

// Create a DI
$di = new FactoryDefault();

// Setting up the view component
$di['view'] = function () {
    $view = new View();
    $view->setViewsDir(APP_PATH . '/views/');
    return $view;
};

// Setup a base URI so that all generated URIs include the "tutorial" folder
$di['url'] = function () {
    $url = new UrlProvider();
    $url->setBaseUri('/');
    return $url;
};

// Set the database service
$di['db'] = function () {
    return new DbAdapter(array(
        "host"     => "mariadb",
        "username" => "chris",
        "password" => "123456",
        "dbname"   => "simulate"
    ));
};

// Set redis service
$di['redis_short'] = function () {
    // 設置緩存期效(必需參數)
    $frontend = new FrontendData([
        'lifetime' => 86400, // 每天
    ]);

    // 連線配置
    $backend = new Redis($frontend, [
        'host' => "redis",
        'port' => 6379,
        'persistent' => true, // 是否使用持久連線
        'index' => 0, // 指定 Redis 資料庫(預設:0)
    ]);

    return $backend;
};

$di['logger'] = function ($path) {
    $options = [
        'name'    => $path,
        'adapter' => 'file',
    ];
    $logger = Factory::load($options);
    return $logger;
};

$di->set(
    'flash',
    function () {
        $flash = new FlashDirect(
            [
                'error'   => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice'  => 'alert alert-info',
                'warning' => 'alert alert-warning',
            ]
        );

        return $flash;
    }
);

$di->setShared(
    "elk",
    function () {
        $hosts = [
            [
                'host' => '192.168.0.236',   // 你的 Elasticsearch 位址
                'port' => '9200',
                'scheme' => 'http',
                'user' => 'elastic',      // 如果啟用了安全特性，請提供用戶名
                'pass' => '123456',     // 如果啟用了安全特性，請提供密碼
            ],
        ];

        $client = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();

        return $client;
    }
);


//
// @see https://docs.phalcon.io/3.4/dispatcher/#dispatch-loop-events
//   events
// @see https://docs.phalcon.io/3.4/dispatcher/#camelize-action-names
//   how to 
//
$di->set(
    'dispatcher',
    function () {
        $eventsManager = new Manager();
        $eventsManager->attach(
            'dispatch:beforeDispatchLoop',
            function (Event $event, Dispatcher $dispatcher) {
                // 回傳值
                // return {true:不影響, false:中止<stop next>} 
                echo "ControllerName :" . $dispatcher->getControllerName() . "<br/>";
                echo "ActionName :" . $dispatcher->getActionName() . "<br/>";
            }
        );

        $dispatcher = new Dispatcher();
        $dispatcher->setEventsManager($eventsManager);

        return $dispatcher;
    }
);

//
// use Phalcon\Http\Response\Cookies;
// ∟ 伺服器發送到使用者的瀏覽器，並儲存在使用者的電腦上
//
// $di->set(
//     'cookies',
//     function () {
//         $cookies = new Cookies();
//         $cookies->useEncryption(false);
//         return $cookies;
//     }
// );
//

// Handle the request
try {
    $application = new Application($di);

    echo $application->handle()->getContent();

    // $response = $application->handle();
    // $response->send();
} catch (Exception $e) {
    echo "Exception: ", $e->getMessage();
}
