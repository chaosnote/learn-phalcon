<?php

use \Utils\DIKey;

class RedisController extends BaseController
{
    var $kg_vers = "kg_vers" ;
    var $kgv_game_menu = "kg_vers:game_menu" ; // global version key (game_menu)
    var $kgd_game_menu = "kg_data:game_menu" ; // global data key (game_menu)

    public function IndexAction() {
        $modules = [$this->kgv_game_menu] ;

        $time = (string)microtime(true) ;

        $redis = $this->di->get(DIKey::REDIS);
        $vers_map = $redis->hMGet($this->kg_vers, $modules); // 全堿版本號

        if(empty($vers_map[$this->kgv_game_menu])){
            $redis->set($this->kgd_game_menu, "test");  // 將資料填入對應值
            $vers_map[$this->kgv_game_menu] = $time ; // 建立版本號
        }

        $redis->hMSet($this->kg_vers, $vers_map);

        echo print_r($vers_map, true) ;
    }
    /**
     * /Redis/Get/1788161340.2539
     * 需改使用 post raw body
     * @param string|float $timestamp
     * @return void
     */
    public function GetAction($timestamp=0) {
        $modules = [$this->kgv_game_menu] ;

        $redis = $this->di->get(DIKey::REDIS);
        $vers_map = $redis->hMGet($this->kg_vers, $modules); // 全堿版本號

        $output = [
            "timestamp" => $timestamp,
            "data" => ""
        ] ;
        // 當時間綴記不相同時，在做更新
        $ver = $vers_map[$this->kgv_game_menu] ;
        if( $timestamp != $ver ){
            $output["timestamp"] = $ver ;
            $output["data"] = $redis->get($this->kgd_game_menu) ;
        }

        echo json_encode($output);
    }
    public function SaveAction($timestamp=0) {
        $modules = [$this->kgv_game_menu] ;

        $time = (string)microtime(true) ;

        $redis = $this->di->get(DIKey::REDIS);
        $vers_map = $redis->hMGet($this->kg_vers, $modules); // 全堿版本號
        $vers_map[$this->kgv_game_menu] = $time ; // 建立版本號

        // 開啟 Pipeline 批次送出指令
        $redis->pipeline()
        ->set($this->kgd_game_menu, "test_{$time}")
        ->hMSet($this->kg_vers, $vers_map)
        ->exec();

        echo print_r($vers_map, true) ;
    }
    public function FlushAction() {
        $redis = $this->di->get(DIKey::REDIS);
        $redis->flushDB() ;
    }
}