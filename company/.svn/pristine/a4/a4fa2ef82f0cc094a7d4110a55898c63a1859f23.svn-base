<?php

/**
 *
 * 简历中心 一键回绝简历的相关缓存数据操作
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/5
 * Time: 15:08
 */
class company_service_applyrefusecache extends base_components_baseservice
{
    private $_cache_prefix = 'company_refuse_job_apply_2016_';

    public function __construct() {
        parent::__construct();
    }

    /**
     * @param $info array('refuse_job_ids','visit_time','all_apply_ids')
     * @param $company_id
     * @return bool
     */
    public function setVerifyCode($info,$company_id)
    {
        if(empty($info) || empty($company_id))
            return false;
        $this->cacheSetex($this->_cache_prefix.$company_id, 600, json_encode($info));
        return true;
    }

    public function getVerifyCode($company_id)
    {
        if(empty($company_id))
            return false;
        return json_decode($this->cacheGet($this->_cache_prefix.$company_id),true);
    }


    public function deleteCode($company_id){
        if(empty($company_id))
            return false;
        return $this->cacheDelete($this->_cache_prefix.$company_id);
    }
}