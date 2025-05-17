<?php

/**
 * @property string|null $key
 * @property string|null $name
 * @property int $min
 * @property int $max
 */
class Country {
    public function __construct(string $_key,string $_name, int $_min, int $_max) {
        $this->key = $_key;
        $this->name = $_name;
        $this->min = $_min;
        $this->max = $_max;
    }
}
/** 
 * @property array<string,Country> $store
 */
class Countries{
    public function __construct() {
        $this->add(new Country("86",_("中國"),11, 11));     // 中國 (+86): 手機號碼 11 位
        $this->add(new Country("886",_("台灣"),9, 9));      // 台灣 (+886): 手機號碼 9 位 (去除開頭 0)
        $this->add(new Country("852",_("香港"),8, 8));      // 香港 (+852): 手機號碼 8 位
        $this->add(new Country("853",_("澳門"),8, 8));      // 澳門 (+853): 手機號碼 8 位
        $this->add(new Country("81",_("日本"),10, 11));     // 日本 (+81): 手機號碼 10 或 11 位 (去除開頭 0)
        $this->add(new Country("82",_("韓國"),10, 11));     // 韓國 (+82): 手機號碼 10 或 11 位 (去除開頭 0)
        $this->add(new Country("65",_("新加坡"),8, 8));     // 新加坡 (+65): 手機號碼 8 位
        $this->add(new Country("60",_("馬來西亞"),9, 10));  // 馬來西亞 (+60): 手機號碼 9 或 10 位 (去除開頭 0)
        $this->add(new Country("66",_("泰國"),9, 9));       // 泰國 (+66): 手機號碼 9 位 (去除開頭 0)
        $this->add(new Country("84",_("越南"),9, 9));       // 越南 (+84): 手機號碼 9 位 (去除開頭 0)
        $this->add(new Country("63",_("菲律賓"),9, 9));     // 菲律賓 (+63): 手機號碼 9 位 (去除開頭 0)
        $this->add(new Country("62",_("印尼"),10, 12));     // 印尼 (+62): 手機號碼 10 到 12 位 (去除開頭 0)
        $this->add(new Country("855",_("柬埔寨"),8, 9));    // 柬埔寨 (+855): 手機號碼 8 或 9 位 (去除開頭 0)
        $this->add(new Country("95",_("緬甸"),9, 9));       // 緬甸 (+95): 手機號碼 9 位 (去除開頭 0)
        $this->add(new Country("856",_("老撾"),9, 9));      // 老撾 (+856): 手機號碼 9 位 (去除開頭 0)
        $this->add(new Country("976",_("蒙古"),8, 8));      // 蒙古 (+976): 手機號碼 8 位
        $this->add(new Country("91",_("印度"),10, 10));     // 印度 (+91): 手機號碼 10 位
        $this->add(new Country("94",_("斯里蘭卡"),9, 10));  // 斯里蘭卡 (+94): 手機號碼 9 或 10 位 (去除開頭 0)
    }
    function add(Country $_country){
        $this->store = $this->store ?? [];
        $this->store[$_country->key] = $_country;
    }
    function validate(string $_key, string $_value) : bool {
        $country = $this->store[$_key] ?? new Country("","", 0,0); // 無對應值，強迫驗證失敗
        $reg = "/^\d{".$country->min.",".$country->max."}$/";
        return preg_match($reg, $_value) ;
    }
}
class JsonController extends BaseController{
    /** 
     * @Route("/Json")
     */
    function IndexAction(){
        $countries = new Countries();

        $this->logger->debug("check", [
            "[0]" => ($countries->validate("886", "900123456") ? "OK" : "NO" ),
            "[1]" => ($countries->validate("88", "900123456") ? "OK" : "NO" ),
        ]) ;

        $this->view->setVar("countries", $countries->store);
        $this->view->setVar("data", json_encode($countries->store));
    }
}