<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/22 0022
 * Time: 14:54
 */
class controller_promotion extends components_cbasepage{
    /**
     * 构造函数
     */
    function __construct() {
        parent::__construct(true,"part");
    }

    public function pageAjaxSetPrmotionNumber(){
        $companypromotion = new base_service_cache_companypromotion();
        $number = $companypromotion->setClicksCache($this->_userid);
//        echo $number;
    }

}