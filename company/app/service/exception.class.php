<?php

class company_service_exception extends base_components_baseservice {

    protected $marter_table = 'info_oa_com_exceptional';
    protected $primary_key  = 'exceptional_id';
    protected $dbinfo       = 'oa_new';

    public function __construct() {
        parent::__construct();
    }


    public function getExceptionalByCompany($company_id, $exception_type_arr, $status, $items) {
        $condition['company_id'] = $company_id;
        $condition['status'] = $status;
        $condition['exceptional_type'] = array ("in" => $exception_type_arr);

        return $this->select($condition, $items);
    }


    //设置company状态
    public function setCompanyState($state_key, $state_val, $company_id) {
        if(empty($company_id)){
            return false;
        }
        $serv_comstate = new company_service_comstate();
        $comCondition['company_id'] =$company_id;
        $comstate = $serv_comstate->selectOne($comCondition);
        if ($comstate) {
            if($state_key=="repeat"){
                $com_state_item['is_repeat']=$state_val;
                $com_state_item['repeat']=$state_val;
            }else{
                $com_state_item = array ($state_key => $state_val);
            }
            $serv_comstate->update($comCondition, $com_state_item);
            $comstate = $serv_comstate->selectOne($comCondition);
            if ($comstate['is_repeat'] == 1 || $comstate['invalid_tel'] == 1 || $comstate['spe_calling'] == 1
                || $comstate['bad_com'] == 1 || $comstate['is_linked'] == 1 || $comstate['licence_unreg'] == 1
                || $comstate['not_integrity'] == 1|| $comstate['repeat'] == 1) {
                $serv_comstate->update($comCondition, array ('all_state' => 1));
            } else {
                $serv_comstate->update($comCondition, array ('all_state' => 0));
            }
        } else {
            if($state_key=="repeat"){
                $com_state_item['is_repeat']=$state_val;
                $com_state_item['repeat']=$state_val;
                $com_state_item['all_state']=$state_val;
                $com_state_item['company_id']=$company_id;
                $serv_comstate->insert($com_state_item);
            }else{
                $comdata['all_state']=$state_val;
                $comdata[$state_key] =$state_val;
                $comdata['company_id'] =$company_id;
                $serv_comstate->insert($comdata);
            }
        }
    }

    //异常举报
    public function addExceptional($data, $login_user) {
        $indata['company_id'] = $data['company_id'];
        $indata['exceptional_type'] = $data['exceptional_type'];
        $indata['link_com_id'] = $data['link_com_id'];
        $indata['using_com_id'] = $data['using_com_id'];
        $indata['remark'] = $data['exceptional_remark'];
        $indata['create_time'] = $this->now;
        $indata['create_man'] = $login_user;
        $indata['status'] = 1;
        $indata['exceptional_img_src'] = $data['exceptional_img_src'];
        $result = $this->insert($indata);

        return $result;
    }

    //异常举报确定撤销
    public function exceptionalDo($exceptional_type, $exceptional_id, $login_user) {
        $item = "exceptional_id, exceptional_type,using_com_id,company_id, link_com_id,create_time,create_man,remark,status,treat_man,treat_time,exceptional_img_src";
        $condition['exceptional_id'] = $exceptional_id;
        $condition['status'] = 1;
        $result = $this->selectOne($condition, $item);
        if (empty($result)) {
           return false;
        }
        if (!empty($result)) {
            $this->update(array ('exceptional_id' => $exceptional_id), array ('status' => 2, 'treat_man' => $login_user, 'treat_time' => $this->now));
        }

        if ($exceptional_type == 'invalid_tel') {
            $this->setCompanyState('invalid_tel', 1, $result['company_id']);
        }

        return true;
    }




}
