<?php
/** 
 * @copyright 2004 www.huibo.com
 * @name 公司福利管理
 * @author huangwt
 * @date 2014-11-11
 *
*/
class controller_reward extends components_cbasepage {
	
	function __construct(){
		parent::__construct();
	}
	
    /**
     * 福利管理入口
     */
	public function pageIndex($inPath) {
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $defaultReward = base_lib_BaseUtils::getStr($path_data['defaultReward'], 'string', '');
        $otherReward   = base_lib_BaseUtils::getStr($path_data['otherReward'], 'string', '');
        $obj           = base_lib_BaseUtils::getStr($path_data['obj'], 'string', '');
        $callback      = base_lib_BaseUtils::getStr($path_data['callback'], 'string', '');
        $src      = base_lib_BaseUtils::getStr($path_data['src'], 'string', '');//默认来自企业编辑 ， mrecruit：来自企业微招聘修改
        $company_id = $this->_userid;

        $this->_aParams['obj']      = $obj;
        $this->_aParams['callback'] = $callback;
        $this->_aParams['src'] = $src;

        $service_reward = new base_service_common_reward();
        $all_reward_data = $service_reward->getAllRewardByAllCategory();

        //获得公司已有的福利
        $service_company = new base_service_company_company();
        $company_rewards = $service_company->getCompany($this->_userid,'1','company_reward_ids,company_other_reward');

        //已经选择了的福利
        if (base_lib_BaseUtils::nullOrEmpty($defaultReward) || $defaultReward == 0) {
            $company_default_rewards = $company_rewards['company_reward_ids'];
            if (!base_lib_BaseUtils::nullOrEmpty($company_default_rewards)) {
                $company_default_rewards_arr = explode(',', $company_default_rewards);
            } else {
                $company_default_rewards_arr = array();
            }

        } else {
            $company_default_rewards_arr = explode(',',$defaultReward);
        }
        //公司的其他福利
        if (base_lib_BaseUtils::nullOrEmpty($otherReward) || $otherReward == "0") {
            $company_other_rewards = $company_rewards['company_other_reward'];
            if (!base_lib_BaseUtils::nullOrEmpty($company_other_rewards)) {
               $company_other_rewards_arr = explode(',', $company_other_rewards);
            } else {
               $company_other_rewards_arr = array();
            }
        } else {
            $company_other_rewards_arr = explode(',', $otherReward);
        }

        //获取单位自定义记录的福利
        $service_company_customreward = new base_service_company_customreward();
        $custom_reward_list = $service_company_customreward->getCustomReward($this->_userid);
        $custom_reward_names = base_lib_BaseUtils::getPropertys($custom_reward_list,"reward_name");
        foreach($company_other_rewards_arr as $key => $value){
            if(!in_array($value,$custom_reward_names)){
                $custom_data = [
                    'custom_reward_id'      => 'custom_'.$key,
                    'reward_name'           => $value
                ];
                array_push($custom_reward_list,$custom_data);
            }
        }

        $count = count($company_other_rewards_arr) + count($company_default_rewards_arr);
        $this->_aParams['company_other_rewards']   = $company_other_rewards_arr;
        $this->_aParams['company_default_rewards'] = $company_default_rewards_arr;
        $this->_aParams['count']                   = $count;
        $this->_aParams['all_reward_data']         = $all_reward_data;
        $this->_aParams['custom_reward_list'] = $custom_reward_list;

        return $this->render('./reward/index.html', $this->_aParams);
    }


    // /**
    //  *@desc 保存福利信息
    //  *@param array $inPath
    //     */  
    // public function pageSaveReward($inPath){
    //    $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
    //    $company_id = $this->_userid;
    // }
    //  *@desc 删除福利信息 
    // *@param array $inPath
    // */  
    // function pageDeleteReward($inPath){
    //     $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        
    // }
    // /**
    // *@desc 获得更新后的福利数据
    // *@param array $inPath
    // */  
    // function pageAjaxGetReward($inPath){
      
    // }

    /**
    *@desc 职位的福利信息
    *@param array $inPath
    */  
    public function pageJobAddReward($inPath) {
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        
        $defaultReward = base_lib_BaseUtils::getStr($path_data['defaultReward'], 'string', '');
        $otherReward   = base_lib_BaseUtils::getStr($path_data['otherReward'], 'string', '');
        $obj           = base_lib_BaseUtils::getStr($path_data['obj'], 'string', '');
        $callback      = base_lib_BaseUtils::getStr($path_data['callback'], 'string', '');

        $company_id = empty($company_id) ?: $this->_userid;

        $this->_aParams['obj']      = $obj;
        $this->_aParams['callback'] = $callback;

        $service_reward = new base_service_common_reward();
        $all_reward_data = $service_reward->getAllRewardByAllCategory();
        
        //获得公司已有的福利
        $company_default_rewards_arr = $defaultReward ? explode(',', $defaultReward) : [];

        //公司的其他福利
        $company_other_rewards_arr =  $otherReward ? explode(',', $otherReward) : [];

        //判断公司福利是否完善
        $service_company = new base_service_company_company();
        $company_rewards = $service_company->getCompany($this->_userid,'1','company_reward_ids,company_other_reward');
        $is_perfect_rewards = 1;
        if(empty($company_rewards['company_reward_ids']) && empty($company_rewards['company_other_reward'])){
            $is_perfect_rewards = 0;
        }

        //获取单位自定义记录的福利
        $service_company_customreward = new base_service_company_customreward();
        $custom_reward_list = $service_company_customreward->getCustomReward($this->_userid);
        $custom_reward_names = base_lib_BaseUtils::getPropertys($custom_reward_list,"reward_name");
        foreach($company_other_rewards_arr as $key => $value){
            if(!in_array($value,$custom_reward_names)){
                $custom_data = [
                    'custom_reward_id'      => 'custom_'.$key,
                    'reward_name'           => $value
                ];
                array_push($custom_reward_list,$custom_data);
            }
        }

        $count = count($company_default_rewards_arr) + count($company_other_rewards_arr);
        $this->_aParams['company_other_rewards']   = $company_other_rewards_arr;
        $this->_aParams['company_default_rewards'] = $company_default_rewards_arr;
        $this->_aParams['count']                   = $count;
        $this->_aParams['all_reward_data']         = $all_reward_data;
        $this->_aParams['is_perfect_rewards']         = $is_perfect_rewards;
        $this->_aParams['custom_reward_list'] = $custom_reward_list;

        return $this->render('./job/jobaddreward.html', $this->_aParams);
    }

/**
*@desc 修改职位的福利信息
*@param array $inPath
*/
public function pageJobEditReward($inPath){
   $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
     $defaultReward = base_lib_BaseUtils::getStr($path_data['defaultReward'],'string','');
     $otherReward = base_lib_BaseUtils::getStr($path_data['otherReward'],'string','');
     $company_id = $this->_userid;
     $obj = base_lib_BaseUtils::getStr($path_data['obj'], 'string', '');
     $callback = base_lib_BaseUtils::getStr($path_data['callback'], 'string', '');
     $this->_aParams['obj'] = $obj;
     $this->_aParams['callback'] = $callback;
     $service_reward = new base_service_common_reward();
     $all_reward_data = $service_reward->getAll();
     $jobflag = base_lib_BaseUtils::getStr($path_data['jobid'],'string','');
     $jobid = base_lib_Rewrite::getId('job', $jobflag);
          //获得职位的福利
    $service_job = new base_service_company_job_job();
    $company_rewards = $service_job->getJob($jobid,'job_id,other_reward_ids,other_reward');


     //已经选择了的福利
     if(base_lib_BaseUtils::nullOrEmpty($defaultReward) || $defaultReward ==0){
        $company_default_rewards = $company_rewards['other_reward_ids'];
        if(!base_lib_BaseUtils::nullOrEmpty($company_default_rewards)){
            $company_default_rewards_arr = explode(',', $company_default_rewards);
        }else{
            $company_default_rewards_arr = array();
        }
     }else{
         $company_default_rewards_arr = explode(',',$defaultReward);
     }
     //已经选择的的其他福利
      if(base_lib_BaseUtils::nullOrEmpty($otherReward) || $otherReward =="0"){
           $company_other_rewards = $company_rewards['other_reward'];
           if(!base_lib_BaseUtils::nullOrEmpty($company_other_rewards)){
               $company_other_rewards_arr = explode(',', $company_other_rewards);
           }else{
               $company_other_rewards_arr = array();
           }
      }else{
          $company_other_rewards_arr = explode(',',$otherReward);
      }
      //公司的其他福利
        $service_company = new base_service_company_company();
        $company_select_rewards = $service_company->getCompany($this->_userid,'1','company_reward_ids,company_other_reward');
        $company_select_other_reward =$company_select_rewards['company_other_reward'];
        if(!base_lib_BaseUtils::nullOrEmpty($company_select_other_reward)){
            $company_select = explode(',',$company_select_other_reward);
        }else{
            $company_select  = array();
        }
       $this->_aParams['company_select'] = array_flip(array_flip(array_merge($company_select,$company_other_rewards_arr)));
     $this->_aParams['company_other_rewards'] = $company_other_rewards_arr;
     $this->_aParams['company_default_rewards'] = $company_default_rewards_arr;
     $count = count($company_default_rewards_arr) + count($company_other_rewards_arr);
     $this->_aParams['count'] = $count;
     $this->_aParams['all_reward_data'] = $all_reward_data;
     return $this->render('./job/jobeditreward.html', $this->_aParams);
}

    /**
     * 公司福利未完善时，职位福利同步公司福利
     * @param $inPath
     */
    public function pageUpdateRewards($inPath)
    {
        $path_data                       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $reward_ids = base_lib_BaseUtils::getStr($path_data['reward_ids'], 'string', '');
        $other_reward = base_lib_BaseUtils::getStr($path_data['other_reward'], 'string', '');
        //判断公司福利是否完善
        $service_company = new base_service_company_company();
        $company_rewards = $service_company->getCompany($this->_userid,'1','company_reward_ids,company_other_reward');
        if($company_rewards['company_reward_ids'] && $company_rewards['company_other_reward']){
            echo json_encode(array('status' => false , 'msg' => '公司福利已完善，同步福利失败'));
            return;
        }
        $service_common_reward = new base_service_common_reward();
        $company_reward_id_arr = array();
        if(empty($company_rewards['company_reward_ids']) && $reward_ids){
            $reward_id_arr = array_unique(explode(',',$reward_ids));
            foreach($reward_id_arr as $key => $value){
                $reward = $service_common_reward->getReward($value);
                if($reward){
                    array_push($company_reward_id_arr,$value);
                }
            }
        }
        $company_data = array();
        if($company_reward_id_arr){
            $company_data['company_reward_ids'] = implode(',',$company_reward_id_arr);
        }
        if(empty($company_rewards['company_other_reward']) && $other_reward){
            $company_data['company_other_reward'] = $other_reward;
        }
        $company_data['company_id'] = $this->_userid;
        if($company_data['company_reward_ids'] || $company_data['company_other_reward']){
            $result = $service_company->updateCompany($company_data, []);
            if (!$result) {
                echo json_encode(['status' => false, 'msg' => '公司福利待遇同步失败']);
                return ;
            }
        }
        echo json_encode(['status' => true, 'msg' => '公司福利待遇同步成功']);
        return ;

    }

    /**
     * 添加自定义福利
     * @param $inPath
     */
    public function pageAddCompanyCustomReward($inPath){
        $path_data                       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $custom_reward_name = base_lib_BaseUtils::getStr($path_data['reward_name'], 'string', '');

        $service_company_customreward = new base_service_company_customreward();
        $result = $service_company_customreward->addCustomReward($this->_userid,$custom_reward_name);
        if($result['status']){
            echo json_encode(['status' => true, 'msg' => '自定义福利添加成功','custom_reward_id' => $result['id']]);
            return ;
        }
        echo json_encode(['status' => false, 'msg' => $result['msg']]);
        return ;
    }

    /**
     * 删除自定义福利
     * @param $inPath
     */
    public function pageDelCompanyCustomReward($inPath){
        $path_data                       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $custom_reward_id = base_lib_BaseUtils::getStr($path_data['custom_reward_id'], 'int', '');


        $service_company_customreward = new base_service_company_customreward();
        //获取删除的自定义福利
        $custom_reward_info = $service_company_customreward->getCustomRewardByid($custom_reward_id);
        if(empty($custom_reward_info)){
            echo json_encode(['status' => false, 'msg' => '自定义福利不存在或已删除']);
            return ;
        }

        $result = $service_company_customreward->delCustomReward($custom_reward_id);
        if($result){
            //判断删除的福利单位其他福利是否存在
            $service_company = new base_service_company_company();
            $company_rewards = $service_company->getCompany($this->_userid,'1','company_reward_ids,company_other_reward');
            if($company_rewards['company_other_reward']){
                $company_other_reward = explode(',',$company_rewards['company_other_reward']);
                $company_other_reward_new = [];
                foreach($company_other_reward as $key => $value){
                    //踢出当前删除的福利
                    if($value != $custom_reward_info['reward_name']){
                        array_push($company_other_reward_new,$value);
                    }
                }
                $company_data['company_id'] = $this->_userid;
                $company_data['company_other_reward'] = implode(',',$company_other_reward_new);
                $service_company->updateCompany($company_data, []);
            }
        }
        echo json_encode(['status' => true, 'msg' => '自定义福利删除成功']);
        return ;
    }
}
    
?>