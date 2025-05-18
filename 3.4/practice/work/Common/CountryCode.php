<?php

namespace Common;

/**
 * @property string|null $key
 * @property string|null $name
 * @property int $min
 * @property int $max
 */
class CountryCode {
    public function __construct(string $_key,string $_name, int $_min, int $_max) {
        $this->key = $_key;
        $this->name = $_name;
        $this->min = $_min;
        $this->max = $_max;
    }
}
