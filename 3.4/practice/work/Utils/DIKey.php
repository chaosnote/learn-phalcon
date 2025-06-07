<?php

namespace Utils;

// 由於 PHP 支援 magic 取值，這邊的的設置在 set 時作用比較大

interface DIKey{
    public const SESSION  = "session";
    public const FLASH  = "flash";
    public const DB  = "db";
    public const URL  = "url";    
    public const LOGGER  = "logger";
    public const REDIS  = "redis";
}
