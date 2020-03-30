<?php

class company_service_comstate extends base_components_baseservice {

    protected $id;
    protected $marter_table = 'info_com_state';
    protected $primary_key  = 'state_id';
    protected $dbinfo       = 'oa_new';

    /**
     * 特殊招聘标记    zhouwenjun 2018/9/3 13:39
     */
    private $special_marking = array (
        0 => '',
        1 => 'KTV',
        2 => '海外招聘',
    );

//    protected $dbinfo = 'oa';

    public function __construct($id = 0) {
        parent::__construct();
        $this->id = $id;
    }

    public function getStateByCompanyIds($company_ids, $item) {
        if (count($company_ids) == 0)
            return array ();
        $condition["company_id"] = array ("in" => implode(",", $company_ids));

        return $this->select($condition, $item)->items;
    }

    public function updateStateCompanyData($company_id, $item = array ()) {
        if (empty($company_id)) {
            return false;
        }
        $conditions['company_id'] = $company_id;

        return $this->update($conditions, $item);
    }

    public function GetStateCompanyDataByID($company_id, $item = '') {
        if (empty($company_id)) {
            return array ();
        }
        $conditions['company_id'] = $company_id;

        return $this->selectOne($conditions, $item);
    }

    public function insertStateCompanyData($item = array ()) {

        return $this->insert($item);
    }

    /**
     * 设置单位的异常、特殊行业的状态    zhouwenjun 2017/11/29 10:02
     * @param string $state_key     修改的键值
     * @param string|int $state_val value
     * @param string $company_id    companyID
     */
    function setCompanyState($state_key, $state_val, $company_id) {
        //先检查单位是否存在于状态表中，没有的话先添加单位
        $ser_comstate = new oa_service_comstate();
        $comstate_data = $ser_comstate->GetStateCompanyDataByID($company_id);
        if ($comstate_data) {
            $item = array ($state_key => $state_val);
            $ser_comstate->updateStateCompanyData($company_id, $item);
        } else {
            $item = array (
                'company_id' => $company_id,
                $state_key   => $state_val
            );
            $ser_comstate->insertStateCompanyData($item);
        }
        //检查是否有异常，如果有，则将总的状态设成1，反之设成0
        $comstate_data = $ser_comstate->GetStateCompanyDataByID($company_id);
        if ($comstate_data) {
            //如果有任何一个异常，就给总状态设为异常（1）
            //rs("invalid_tel")=1 or rs("spe_calling")=1 or rs("bad_com")=1
            // or rs("is_linked")=1 or rs("licence_unreg")=1
            // or rs("not_integrity")=1
            if ($comstate_data['repeat'] == 1 || $comstate_data['invalid'] == 1
                || $comstate_data['invalid_tel'] == 1 || $comstate_data['spe_calling'] == 1
                || $comstate_data['bad_com'] == 1 || $comstate_data['is_linked'] == 1
                || $comstate_data['licence_unreg'] == 1 || $comstate_data['not_integrity'] == 1
            ) {
                $item = array ('all_state' => 1);
            } else {
                $item = array ('all_state' => 0);
            }
            $ser_comstate->updateStateCompanyData($company_id, $item);
        }
    }

    /**
     * 更新com STATE 如果不存在新增数据    zhouwenjun 2018/5/4 13:47
     */
    function UpdateComState($company_id, $item) {
        if (!$company_id || !$item) {
            return false;
        }
        //单位客户库操作类型 如需增加 请修改备注   1-老客户登录分配'
        $condition['company_id'] = $company_id;
        $end_heap_data = $this->selectOne($condition);
        if ($end_heap_data) {
            return $this->update($condition, $item);
        } else {
            $item['company_id'] = $company_id;

            return $this->insert($item);
        }
    }

    /**
     * 获取特殊招聘标记    zhouwenjun 2018/9/3 13:40
     * @param $marking
     * @return array|mixed
     */
    function GetSpecialMarking($marking = '') {
        return $marking === '' ? $this->special_marking : @$this->special_marking[ $marking ];
    }

}