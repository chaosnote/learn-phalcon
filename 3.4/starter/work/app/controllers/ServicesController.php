<?php

use Phalcon\Cache\Frontend\Data as FrontData;
use Phalcon\Cache\Backend\File as BackFile;

use Phalcon\Cache\Backend\Factory as BFactory;
use Phalcon\Cache\Frontend\Factory as FFactory;

use Phalcon\Config;
use Phalcon\Config\Adapter\Ini as ConfigIni;

// 關鍵字 trait (特<徵/點>)
// like : compositing design pattern ( struct module )
//

trait CacheTrait
{
    /** @var BackFile $backendCache */
    private $backendCache;

    private function initCache()
    {
        if ($this->backendCache === null) {
            $frontCache = new FrontData(['lifetime' => 7200]);
            $this->backendCache = new BackFile($frontCache, ['cacheDir' => '../dist/cache/']);
        }
    }

    protected function setCachedData(string $key, $value)
    {
        $this->initCache();
        return $this->backendCache->save($key, $value);
    }

    protected function getCachedData(string $key, callable $dataCallback)
    {
        $this->initCache();
        $cachedData = $this->backendCache->get($key);

        if ($cachedData === null) {
            $cachedData = $dataCallback(); // 無值時，使用 callback 產生值
            $this->backendCache->save($key, $cachedData);
        }

        return $cachedData;
    }
}

// 在 Phalcon 的快取系統中，FrontData 前端快取適配器主要負責定義快取資料的生命週期和序列化/反序列化邏輯，
// 而實際的快取資料儲存和檢索操作都是透過 Backend 後端快取適配器來完成的。
class ServicesController extends BaseController
{
    use CacheTrait;

    /**
     * @Route("/services")
     */
    public function indexAction()
    {
        $this->logger->log("enter service");

        $key = date("His");
        if ($this->setCachedData($key, "123456")) {
            echo "設置成功";
        }
        $value = $this->getCachedData($key, function () {
            return "67890";
        }); // 這段正常不會設定到值
        echo $value;
    }

    /**
     * @Route("/services/factory")
     */
    public function FactoryAction()
    {
        $options = [
            'lifetime' => 172800,
            'adapter'  => 'data', // 滿怪的，使用工廠模式，為什麼不給常數
        ];
        $frontendCache = FFactory::load($options);

        $options = [
            'cacheDir' => '../dist/cache/',
            'prefix'   => 'app-data',
            'frontend' => $frontendCache,
            'adapter'  => 'file',
        ];

        $backendCache = BFactory::load($options);

        // 後續同之前的操作
        // $backendCache->save()
    }

    //
    // @see https://docs.phalcon.io/3.4/cache/#multi-level-cache
    //  複合使用
    //

    //
    // Frontend Adapters
    //  https://docs.phalcon.io/3.4/cache/#frontend-adapters
    // Backend Adapters
    //  https://docs.phalcon.io/3.4/cache/#backend-adapters
    //

    /**
     * @Route("/services/redis")
     */
    public function RedisAction()
    {
        $key = "redis_key";
        $value = "redis_value";
        if ($this->redis->save($key, $value, -1)) {
            echo "設置成功<br/>";
        }

        // 值鍵值是否存在
        if (!$this->redis->exists($key)) {
            echo "無指定 key<br/>";
        }

        $output = $this->redis->get($key);
        // 是否設置且不為 null
        if (isset($output)) {
            echo "取值成功<br/>";
            echo "值:" . $output;
        }
    }

    /**
     * @Route("/services/config")
     */
    public function ConfigAction()
    {
        $config = new Config(
            [
                'test' => [
                    'parent' => [
                        'property'  => 1,
                        'property2' => 'yeah',
                    ],
                ],
            ]
        );

        echo $config->get('test')->get('parent')->get('property') . "<br/>";  // displays 1
        echo $config->path('test.parent.property') . "<br/>";                 // displays 1

        // 不建議使用、編譯器無法辨識
        // echo $config->test->parent->property;                       // displays 1
    }

    /**
     * @Route("/services/ini")
     */
    public function IniAction()
    {

        // Phalcon\Config
        // ∟ Phalcon\Config\Adapter\Json
        // ∟ Phalcon\Config\Adapter\Yaml
        // ∟ Phalcon\Config\Adapter\Ini

        // 
        // Phalcon\Config\Adapter\PHP
        //  不考慮

        $config = new ConfigIni('../asset/config.ini');

        echo $config->path("phalcon.controllersDir")."<br/>" ;
        echo $config->path("database.username")."<br/>" ;
        echo $config->path("models.metadata.adapter")."<br/>" ;
    }

    //
    // Contextual Escaping 
    //  CP 值頗低
    //

    // Phalcon\Loader
    //  對應 public/index.php 的 Loader，除了搭配 condition 之外，還有那種情境會使用到?
    //

    //
    // Logging to Multiple Handlers
    //  介面寫的真好
    //
    // FileAdapter
    //  參考 public/index.php 
    //
    // StreamAdapter 
    //  支援格式
    //   https://www.php.net/manual/en/wrappers.php
    //
}
