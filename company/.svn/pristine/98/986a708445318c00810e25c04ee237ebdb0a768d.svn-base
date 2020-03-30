<?php

/**
 *
 * 简历中心 一键回绝简历的相关缓存数据操作
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/5
 * Time: 15:08
 */
class company_service_isloginapp extends base_components_baseservice
{
    protected $id;
    protected $marter_table = 'info_company_appkey';
    protected $primary_key = 'appkey_id';
    protected $cache_prefix = 'cache_company_appkey';

    public function __construct() {
        parent::__construct();
    }


    public function is_login_app($company_id,$account_id)
    {
        if (empty($company_id) || empty($account_id))
            return false;
        $appkey = $this->getCacheObj()->get($this->cache_prefix . $company_id."_".$account_id);
        if(!empty($appkey))
            return true;
        else
            return false;
    }

}