<?php

namespace Common;

/** 
 * @property array<string,CountryCode> $store
 */
class CountryCodeStore{
    public function __construct() {
        $this->add(new CountryCode("86",_("中國"),11, 11));     // 中國 (+86): 手機號碼 11 位
        $this->add(new CountryCode("886",_("台灣"),9, 9));      // 台灣 (+886): 手機號碼 9 位 (去除開頭 0)
        $this->add(new CountryCode("852",_("香港"),8, 8));      // 香港 (+852): 手機號碼 8 位
        $this->add(new CountryCode("853",_("澳門"),8, 8));      // 澳門 (+853): 手機號碼 8 位
        $this->add(new CountryCode("81",_("日本"),10, 11));     // 日本 (+81): 手機號碼 10 或 11 位 (去除開頭 0)
        $this->add(new CountryCode("82",_("韓國"),10, 11));     // 韓國 (+82): 手機號碼 10 或 11 位 (去除開頭 0)
        $this->add(new CountryCode("65",_("新加坡"),8, 8));     // 新加坡 (+65): 手機號碼 8 位
        $this->add(new CountryCode("60",_("馬來西亞"),9, 10));  // 馬來西亞 (+60): 手機號碼 9 或 10 位 (去除開頭 0)
        $this->add(new CountryCode("66",_("泰國"),9, 9));       // 泰國 (+66): 手機號碼 9 位 (去除開頭 0)
        $this->add(new CountryCode("84",_("越南"),9, 9));       // 越南 (+84): 手機號碼 9 位 (去除開頭 0)
        $this->add(new CountryCode("63",_("菲律賓"),9, 9));     // 菲律賓 (+63): 手機號碼 9 位 (去除開頭 0)
        $this->add(new CountryCode("62",_("印尼"),10, 12));     // 印尼 (+62): 手機號碼 10 到 12 位 (去除開頭 0)
        $this->add(new CountryCode("855",_("柬埔寨"),8, 9));    // 柬埔寨 (+855): 手機號碼 8 或 9 位 (去除開頭 0)
        $this->add(new CountryCode("95",_("緬甸"),9, 9));       // 緬甸 (+95): 手機號碼 9 位 (去除開頭 0)
        $this->add(new CountryCode("856",_("老撾"),9, 9));      // 老撾 (+856): 手機號碼 9 位 (去除開頭 0)
        $this->add(new CountryCode("976",_("蒙古"),8, 8));      // 蒙古 (+976): 手機號碼 8 位
        $this->add(new CountryCode("91",_("印度"),10, 10));     // 印度 (+91): 手機號碼 10 位
        $this->add(new CountryCode("94",_("斯里蘭卡"),9, 10));  // 斯里蘭卡 (+94): 手機號碼 9 或 10 位 (去除開頭 0)
    }
    function add(CountryCode $_country){
        $this->store = $this->store ?? [];
        $this->store[$_country->key] = $_country;
    }
    function validate(string $_key, string $_value) : bool {
        $country = $this->store[$_key] ?? new CountryCode("","", 0,0); // 無對應值，強迫驗證失敗
        $reg = "/^\d{".$country->min.",".$country->max."}$/";
        return preg_match($reg, $_value) ;
    }
}