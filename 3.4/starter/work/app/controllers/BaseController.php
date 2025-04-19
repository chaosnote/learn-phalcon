<?php

use Phalcon\Mvc\Controller;
use Phalcon\Cache\Backend\Redis;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Flash\Direct as FlashDirect;
use Phalcon\Logger\AdapterInterface;

/** 之後在搬移、暫時還沒想到其它地方會使用到 */
trait ELKTool {
    /** @var \Elasticsearch\Client $elk */
    protected $elk;

    /**
     * @var string $level
     * @var array $context
     */
    private function _send2elk($level, $context)
    {
        $from = [
            "ctrl_name" => $this->dispatcher->getControllerName(),
            "action_name" => $this->dispatcher->getActionName() ?? "",
        ];
        // 合併上下文信息
        $combine = array_merge($from, $context);

        $message = json_encode($combine);
        if (!$message) return;

        $logData = [
            '@timestamp' => date(DateTime::RFC3339),
            'level' => $level,
            'message' => $message,
        ];

        $indexName = 'php-logs-' . date('Y.m.d');

        $params = [
            'index' => $indexName,
            'body' => $logData,
        ];

        try {
            $this->elk->index($params);
            // $response = $this->elk->index($params);
            // print_r($response);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage()) ;
        }
    }

    /**
     * @var array $context
     */
    protected function Debug($context)
    {
        $this->_send2elk("debug", $context);
    }

    /**
     * @var array $context
     */
    protected function Info($context)
    {
        $this->_send2elk("info", $context);
    }

    /**
     * @var array $context
     */
    protected function Warning($context)
    {
        $this->_send2elk("Warning", $context);
    }

    /**
     * @var array $context
     */
    protected function ERROR($context)
    {
        $this->_send2elk("ERROR", $context);
    }
}

class BaseController extends Controller
{
    use ELKTool ;

    /** @var AdapterInterface $logger */
    protected $logger;
    /** @var Redis $redis */
    protected $redis;
    /** @var DbAdapter $db */
    protected $db;
    /** @var FlashDirect $flash */
    protected $flash;

    public function initialize()
    {
        $date = date("Ymd");
        //
        // __CLASS__ 類別名稱、原本預期使用這方式，但這樣子物件需要另外處理
        // 此階段 DI 才準備完成
        //

        $this->logger = $this->di->get("logger", ["/home/www-data/" . $date . ".log"]);

        $this->redis = $this->di->get("redis_short");

        $this->db = $this->di->get("db");

        $this->flash = $this->di->get("flash");

        $this->elk = $this->di->getShared("elk");
    }
}
