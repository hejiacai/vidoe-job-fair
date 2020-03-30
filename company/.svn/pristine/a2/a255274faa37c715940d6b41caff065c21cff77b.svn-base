<?php

/**
 * 单位联系方式异常记录
 * Class linkmanAnomaly.class.php
 * User: zhouwenjun  2019/10/10 9:57
 */
class company_service_linkmanAnomaly extends base_components_baseservice {

    protected $id;
    protected $marter_table = 'info_linkman_anomaly';
    protected $primary_key = 'id';
    protected $dbinfo = 'oa_new';

    public function __construct($id = 0) {
        parent::__construct();
        $this->id = $id;
    }

    /**
     * 获取单位 联系方式 企业资料联系方式    zhouwenjun 2019/10/10 9:58
     * @param int    $company_id
     * @param string $mobile_phone
     * @return array|bool
     */
    function GetLinkmanAnomalyDataTypeByCompanyId($company_id = 0, $mobile_phone = '') {
        $mobile_phone = trim($mobile_phone);
        if(!$company_id || !$mobile_phone) {
            return false;
        }

        $condition = array ();
        $condition['company_id'] = $company_id;
        $condition['mobile_phone'] = $mobile_phone;
        $condition['is_effect'] = 1;
        $linkmanAnomaly_data = $this->selectOne($condition);
        if($linkmanAnomaly_data && $linkmanAnomaly_data['id']) {
            return false;//已存在 不需要保存
        }

        //企业资料联系方式 1-手机 2-座机 3-linkman_id 4-新增联系方式(只做记录)
        //优先判断 企业资料的联系方式
        $ser_company = new base_service_company_company();
        $company_data = $ser_company->getCompany($company_id, 1, 'company_id,link_mobile,link_tel');
        if(!$company_data || !$company_data['company_id']) {
            return false;//单位信息不存在
        }
        if($company_data['link_mobile'] == $mobile_phone) {
            return [1];
        }
        if($company_data['link_tel'] == $mobile_phone) {
            return [2];
        }

        //判断关键招聘相关人
        $ser_linkmanonjob = new company_service_linkmanonjob();
        $linkmanonjob_data = $ser_linkmanonjob->GetComlinkmanByCompanyIdMobile($company_id, $mobile_phone);
        if($linkmanonjob_data && $linkmanonjob_data['link_id']) {
            return [3, $linkmanonjob_data['link_id']];
        }

        return [4];
    }

    /**
     * 判断单位 联系方式无效    zhouwenjun 2019/10/10 11:05
     * 如果该企业下的所有联系人电话（包括企业资料里面的联系电话）的电话号码都被标记为无效，则该单位被标记为：电话无效
     * @param int  $company_id
     * @param bool $is_data 是否查询数据
     * @return array|bool
     */
    function IsLinkmanAnomalyCompanyVain($company_id = 0, $is_data = false) {
        if(!$company_id) {
            return false;
        }
        $mobile_phones = array ();

        $ser_company = new base_service_company_company();
        $company_data = $ser_company->getCompany($company_id, 1, 'company_id,link_mobile,link_tel');
        if(!$company_data || !$company_data['company_id']) {
            return false;//单位信息不存在
        }
        $company_data['link_mobile'] and $mobile_phones[] = $company_data['link_mobile'];
        $company_data['link_tel'] and $mobile_phones[] = $company_data['link_tel'];

        $ser_linkmanonjob = new company_service_linkmanonjob();
        $linkmanonjob_data = $ser_linkmanonjob->getcomlinkmans($company_id, 'mobile_phone,a.link_id')->items;
        if($linkmanonjob_data) {
            $_tmp_mobile_phones = base_lib_BaseUtils::getProperty($linkmanonjob_data, 'mobile_phone');
            $mobile_phones = array_merge($mobile_phones, $_tmp_mobile_phones);
        }
        elseif(!$is_data && !$mobile_phones) {
            $linkmanonjob_data_tmp = $ser_linkmanonjob->getcomlinkmans($company_id, 'mobile_phone,a.link_id', false)->items;
            if($linkmanonjob_data_tmp) {
                return true;
            }
        }

        $mobile_phones = array_filter(array_unique($mobile_phones));
        if (!$mobile_phones){
            return false;
        }

        $condition = array ();
        $condition['company_id'] = $company_id;
        $condition['mobile_phone'] = array ('in' => $mobile_phones);
        $condition['is_effect'] = 1;
        $_data_item = "count(1) mobile_phone_num,GROUP_CONCAT(DISTINCT mobile_phone) mobile_phones";
        $linkmanAnomaly_data = $this->selectOne($condition, $_data_item);
        if($linkmanAnomaly_data['mobile_phone_num'] == count($mobile_phones)) {
            return !$is_data ? true : explode(',', $linkmanAnomaly_data['mobile_phones']);
        }
        return !$is_data ? false : explode(',', $linkmanAnomaly_data['mobile_phones']);
    }

    public function getInfoByCompanyIdAndPhone($company_id,$link_tel,$item='*'){
        if (empty($company_id)||empty($link_tel)){
            return false;
        }
        $condition['company_id'] = $company_id;
        $condition['mobile_phone'] = $link_tel;
        $condition['is_effect'] = 1;

        return $this->select($condition,$item)->items;
    }

    public function addinfo($data){
        if (empty($data)){
            return false;
        }
        $data['is_effect'] = 1;
        $data['create_time'] =date('Y-m-d H:i:s',time());
        return $this->insert($data);
    }

    public function deleteinfo($company_id,$link_tel){
        if (empty($company_id)||empty($link_tel)){
            return false;
        }
        $condition['company_id'] = $company_id;
        $condition['mobile_phone'] = $link_tel;
        return $this->delete($condition);
    }
}