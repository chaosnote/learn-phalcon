<?php

namespace Utils;

class ExtraFunc
{
    private const ERR_MESSAGE = "使用時機點錯誤，需在 Controller.initialize() 觸發(後)使用";
    /**
     * @param string $subfix
     * @return \Phalcon\Logger\AdapterInterface
     */
    public static function GenLogger($subfix = "")
    {
        $di = \Phalcon\Di::getDefault();
        if (!isset($di)) {
            throw new \Exception(self::ERR_MESSAGE);
        }

        $file_name = date("Ymd");
        if (!empty($subfix)) {
            $file_name = $file_name . "_" . $subfix;
        }
        return $di->get(DIKey::LOGGER, ["/home/www-data/" . $file_name . ".log"]);
    }
    /**
     * @param int $duration 持續時間
     * @param int $index DB index
     * @return \Phalcon\Cache\Backend\Redis
     */
    public static function GenRedis($duration, $index = 0)
    {
        $di = \Phalcon\Di::getDefault();
        if (!isset($di)) {
            throw new \Exception(self::ERR_MESSAGE);
        }

        return $di->get(DIKey::REDIS, [$duration, $index]);
    }
}
