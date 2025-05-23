<?php

namespace Tools\Crypto ;

/**
 * @property string $name
 * @property string $value
 */
class KeyPair{
    public function __construct(string $_name, string $_value){
        $this->name = $_name;
        $this->value = $_value;
    }
}

trait MD5 {
    public function encrypt(KeyPair ...$list) {
        /** @var array<string> $dist */
        $dist = array() ;
        foreach ($list as $item) {
            array_push($dist, $item->name."=".$item->value) ;
        }
        return md5(implode("|", $dist));
    }
}