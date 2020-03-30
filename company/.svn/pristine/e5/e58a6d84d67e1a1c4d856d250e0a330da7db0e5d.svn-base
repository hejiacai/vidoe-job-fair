<?php

/**
 * 模拟企业登录
 */
class company_service_bosslogin extends base_components_baseservice
{
    private $_cache_prefix = 'company_boss_loginstatus_';

    public function __construct() {
        parent::__construct();
    }


    public function setVerifyCode($session_id)
    {
        if(empty($session_id))
            return false;
        $secondes = strtotime(date('Y-m-d 23:59:59',time())) - time();//当天保纯
        $this->cacheSetex($this->_cache_prefix.$session_id, $secondes, 1);
        return true;
    }

    public function getVerifyCode($session_id)
    {
        if(empty($session_id))
            return false;
        return $this->cacheGet($this->_cache_prefix.$session_id);
    }


    public function deleteCode($session_id){
        if(empty($session_id))
            return false;
        return $this->cacheDelete($this->_cache_prefix.$session_id);
    }
}