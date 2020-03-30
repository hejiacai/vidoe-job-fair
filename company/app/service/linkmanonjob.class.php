<?php

class company_service_linkmanonjob extends base_components_baseservice {

    protected $id;
    protected $marter_table = 'info_linkman_onjob';
    protected $primary_key = 'onjob_id';
    protected $dbinfo = 'oa_new';

    public function __construct($id = 0) {
        parent::__construct();
        $this->id = $id;
    }

    public function getcomlinkmans($company_id, $item = "a.link_id,a.position,a.dept,b.link_man,b.mobile_phone,b.sex", $is_on_job = true) {
        $conditions['company_id'] = $company_id;
        $conditions['a.is_effect'] = 1;
        $conditions['b.is_effect'] = 1;
        $is_on_job and $conditions['a.is_on_job'] = 1;
        $join = array ('a inner join info_linkman b ' => ' a.link_id=b.link_id ');
        $orderby = 'order by a.is_on_job desc,a.onjob_id desc';

        return $this->select($conditions, $item, '', $orderby, $join,array('type'=>'main'));
    }

    public function getDefaultLinkmanOnjob($company_id, $item = '') {
        if(base_lib_BaseUtils::nullOrEmpty($company_id)) {
            return false;
        }

        $condition['company_id'] = $company_id;
        $condition['is_default_linkman'] = 1;

        $orderby = ' order by link_id desc';

        return $this->selectOne($condition, $item, '', $orderby);
    }

    //修改信息
    public function modHrOnJob($linkid = '', $data, $company_id = '', $is_default = false) {
        if(empty($data)) {
            return false;
        }
        if(!empty($linkid)) {
            $condition['link_id'] = $linkid;
        }
        if(!empty($company_id)) {
            $condition['company_id'] = $company_id;
        }
        if($is_default) {
            $condition['is_default_linkman'] = 1;
        }
        $condition['is_on_job'] = 1;

        return $this->update($condition, $data);
    }

    /**
     * 获取关键联系人相关数据    hujian 2019/1/3 17:44:43
     */
    public function getComLinkManByCompanyId($company_ids, $mans, $item = "count(1) count_sum,position", $groupby = "") {
        if(empty($company_ids) || empty($mans)) {
            return array ();
        }
        $conditions['company_id'] = array ('in' => $company_ids);
        $conditions['is_effect'] = 1;
        $conditions['is_on_job'] = 1;
        $conditions[] = "EXISTS(select 1 from info_linkman l where info_linkman_onjob.link_id=l.link_id and  label_man in({$mans}) and label_status=1 and label_man>0)";

        return $this->select($conditions, $item, $groupby);
    }

    /**
     * 获取 单位联系人信息   zhouwenjun 2019/10/10 10:17
     * @param int    $company_id
     * @param string $mobile_phone
     * @param string $item
     * @return array|bool
     */
    public function GetComlinkmanByCompanyIdMobile($company_id = 0, $mobile_phone = '', $item = '') {
        if(!$company_id || !$mobile_phone) {
            return false;
        }
        !$item and $item = "a.link_id,a.position,a.dept,b.link_man,b.mobile_phone,b.sex";

        $conditions['company_id'] = $company_id;
        $conditions['a.is_effect'] = 1;
        $conditions['b.is_effect'] = 1;
        $conditions['a.is_on_job'] = 1;
        $conditions['b.mobile_phone'] = $mobile_phone;

        $join = array (
            'a inner join info_linkman b ' => ' a.link_id=b.link_id ',
        );

        $group = "GROUP BY a.link_id";

        return $this->selectOne($conditions, $item, $group, null, $join);
    }
}