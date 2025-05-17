<?php

trait CustomInject {
    public function init_inject() {
        $this->di = $this->di ?? $this->getDI();
        $date = date("Ymd");
        $this->logger = $this->di->get("logger", ["/home/www-data/" . $date . ".log"]);
        $this->redis = $this->di->get("redis_short");
        $this->db = $this->di->get("db");
        $this->flash = $this->di->get("flash");
    }
}
