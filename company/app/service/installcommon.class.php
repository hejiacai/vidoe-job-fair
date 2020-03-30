<?php

/**
 * 一些页面公用的数据（职位下拉选项，发布人下拉选项，代招公司下拉选项等）
 * @author daili
 * @date 2019-12-24
 */
class company_service_installcommon {
    private $data = [];
    
    public function __construct($args = null) {
        if(!$args || !is_array($args))
            return;
        
        foreach ($args as $k => $v)
            $this->$k = $v;
    }
    
    public function __set($name, $value){
        return $this->data[$name] = $value;
    }
    
    public function __get($name){
        if(!isset($this->data[$name])){
            if(class_exists($name))
                $this->$name = new $name();
            else if(method_exists($this, $name))
                $this->$name();
            else 
                return null;
        }
        return $this->data[$name];
    }
    
    public function company_resources(){
        $this->company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $this->account_id);
    }
    
    /**
     * 代招公司信息
     */
    public function setGetHrCompanys(&$aParams){
        $accounts = $this->base_service_company_company->getCompanys($this->company_resources->all_accounts, "company_id,company_shortname,company_name");

        $_accounts = [];
        // 是否为hr代招会员
        $aParams['is_hr'] = $this->company_resources->account_type == 'hr_main' && !empty($accounts);

        $_datas = [];
		array_push($_datas, ["id" => "", "name" => "所有公司"]);
        foreach ($accounts as $key => $account) {
            $account['company_name_display']   = $account['company_shortname'] ? $account['company_shortname'] : $account['company_name'];
            $_accounts[$account['company_id']] = $account;
            array_push($_datas, ['id' => $account['company_id'], "name" => $account['company_name_display']]);
        }

        $aParams['accounts']      = $_accounts;
        $aParams['accounts_json'] = json_encode($_datas);
        $aParams['company_shortname'] = $_accounts[$this->_userid]['company_name_display'];
    }
    
    public function setJobs($company_id, $account_id, $show_stop_job, &$aParams){
        $account_job_list = $this->base_service_company_job_job->getJobIdByAccount($company_id,$account_id,'company_id,job_id,account_id');
        $account_job_ids = base_lib_BaseUtils::getProperty($account_job_list->items,'job_id');
        // 职位筛选项
		$jobs_json = [];
		array_push($jobs_json, ["id"=>"", "name"=>"全部职位"]);
        if(!empty($account_job_list->items)){
            $hasApplyJobs = $this->base_service_company_resume_jobinvite->getHasInviteJobIds($company_id,$account_job_ids);
            $job_ids = base_lib_BaseUtils::getProperty($hasApplyJobs->items, 'job_id');
            $job_ids = array_unique($job_ids);

            $jobs = $this->_getJobsAndSort($job_ids, $use_job_ids = array(), $show_stop_job);


            foreach ($jobs as $job)
                array_push($jobs_json, ["id"=>$job['job_id'], "name"=>$job['station']]);

            $jobs = base_lib_BaseUtils::array_key_assoc($jobs, "job_id");
        }

		$aParams['jobs'] = json_encode($jobs_json);
		$aParams['job_ids'] = array_filter(base_lib_BaseUtils::getProperty($jobs_json, 'id'));
    }
    
    public function setAccounts(&$aParams){
        //获取所有账号
		$son_account_list = $this->base_service_company_account->getAccountList($this->company_resources->all_accounts,'account_id,company_id,is_main,user_id,user_name');
        
        //发布人选项
		$job_people = [];
		array_push($job_people,["id"=>"","name"=>"全部"]);
		foreach($son_account_list->items as $val){
			array_push($job_people,["id"=>$val['account_id'],"name"=>$val['user_name']]);
		}
        $aParams['job_people'] = json_encode($job_people);
    }
    
    public function setAuditionResults(&$aParams){
        //待面试，已面试待反馈
		$audition_results = [
            ["id"=>"101","name"=>"不限"],
            ["id"=>"100","name"=>"待面试"],
            ["id"=>"1","name"=>"已面试待反馈"]
        ];
        $aParams['audition_results'] = json_encode($audition_results);
    }
    
    public function setInviteSources(&$aParams){
		$invite_sources = [
            ["id"=>"","name"=>"全部简历来源"],
            ["id"=>"1","name"=>"投递的简历"],
            ["id"=>"2","name"=>"下载的简历"],
        ];
        $aParams['invite_sources'] = json_encode($invite_sources);
    }
    /**
	 * 获取职位信息并排序
	 * @param  $job_ids
	 */
	private function _getJobsAndSort($job_ids, &$use_job_ids, $show_stop_job=false){
		$jobs = $this->base_service_company_job_job->getJobs($job_ids, 'job_id,station,end_time,status,check_state');

		$validJob = array();
		$voidJob  = array();

		$status = new base_service_common_jobstatus();
		foreach ($jobs as $job) {
			if ($job['status'] != $status->use
				|| base_lib_TimeUtil::time_diff_day($job['end_time'], date('Y-m-d H:i:s')) > 0) {
				if ($show_stop_job) {
					$job['station'] = base_lib_BaseUtils::cutstr($job['station'], 17, 'utf-8', '', '...') . "<span class='orange'>(停招)</span>";
					array_push($voidJob, $job);
				}
			} else {
				$job['station'] =  base_lib_BaseUtils::cutstr($job['station'], 19, 'utf-8', '', '...');
				$use_job_ids[] = $job['job_id'];
				array_push($validJob, $job);
			}
		}

		return array_merge($validJob, $voidJob);
	}
    
    public function setBannerDates(&$aParams, $min_audition_time, $max_audition_time){
        $aParams['banner_dates'] = [];
        $banner_show_days = 7;
        $begin_day = -6;
        //只选中一个日期，且在13天之内，顶部日期有选中状态
        if($max_audition_time == $min_audition_time
                && strtotime($min_audition_time) <= strtotime(date('Y-m-d 23:59:59',strtotime('+6 days'))) && strtotime($min_audition_time) >= strtotime(date('Y-m-d 00:00:00',strtotime('-6 days'))))
            $aParams['select_audition_time'] = date('Y-m-d', strtotime($min_audition_time));

        //未选日期时，默认查询当前之后的数据
        while($begin_day < $banner_show_days){
            array_push($aParams['banner_dates'], date('Y-m-d', strtotime("+{$begin_day} days")));
            $begin_day++;
        }
        $aParams['banner_dates'] = $this->base_service_company_resume_jobinvite->getGonnaInterviewNumByDaysV1($this->company_resources->all_accounts, $aParams['banner_dates']);
        foreach ($aParams['banner_dates'] as $k => $v) {
            $aParams['banner_dates'][$k]['audition_date_str'] = $this->to_friend_time($v['audition_date']);
            if($v['audition_date'] < date('Y-m-d'))
                $aParams['banner_dates'][$k]['wait_audition_num'] = -1;
            if($v['audition_date'] > date('Y-m-d'))
                $aParams['banner_dates'][$k]['wait_deal_num'] = -1;
        }
        
    }
    
    public function to_friend_time($date) {
		if (is_null($date)) return '';
        $week_arr = array("日","一","二","三","四","五","六");
		$diff = base_lib_TimeUtil::time_diff_day($date);
		switch ($diff) {
			case 0:
				return '今天';
			case 1:
				return '昨天';
			case 2:
				return '前天';
            case -1:
				return '明天';
			case -2:
				return '后天';
			default:
                return date('m-d', strtotime($date)) . ' ' . "周".$week_arr[date("w", strtotime($date))];
		}		
	}
    
    /**
     * 简历列表数据
     * @param type $aParams
     */
    public function setResumeList(&$aParams, $list){
        if(empty($list))
            return $list;
        
        $job_status = new base_service_common_jobstatus();
        
        $job_ids = array_unique(base_lib_BaseUtils::getProperty($list, 'job_id'));
        $jobs = $this->base_service_company_job_job->getJobs($job_ids, 'station,job_id,check_state,status,end_time,is_effect');
        $jobs = base_lib_BaseUtils::array_key_assoc($jobs, 'job_id');
        
        $person_ids = array_unique(base_lib_BaseUtils::getProperty($list, 'person_id'));
        $person_list = $this->base_service_person_person->GetPersonListByIDs($person_ids, 'person_id,user_name,sex,birthday2,cur_area_id,start_work,photo,small_photo,mobile_phone,telephone,photo_open,name_open')->items;
        $person_list = base_lib_BaseUtils::array_key_assoc($person_list, 'person_id');
        
        $resume_ids = array_unique(base_lib_BaseUtils::getProperty($list, 'resume_id'));
        $resume_list = $this->base_service_person_resume_resume->getResumes($resume_ids, 'person_id,resume_id,resume_name,degree_id,current_station,'
					. 'current_station_start_time,current_station_end_time,is_effect,mobile_phone,create_time')->items;
        $resume_list = base_lib_BaseUtils::array_key_assoc($resume_list, 'resume_id');
        
        //毕业院校
        $edu_data = $this->base_service_person_resume_edu->getResumeEdus(implode(',',$resume_ids),'resume_id,school,major_desc,degree')->items;
        $edu_data = base_lib_BaseUtils::array_key_assoc($edu_data, 'resume_id');
        
        //投递信息
        $apply_ids = base_lib_BaseUtils::getProperty($list, 'apply_id');
        $apply_ids = array_unique(array_filter($apply_ids));
        $apply_list = $this->base_service_company_resume_apply->getApplys($apply_ids, 'apply_id,'
				. 'info_job_apply.person_id,info_job_apply.resume_id,info_job_apply.station,info_job_apply.create_time,info_job_apply.company_id,'
				. 'info_job_apply.is_cancelled,info_job_apply.has_read,info_job_apply.re_status,info_job_apply.job_id,info_job_apply.remind_time,info_job_apply.need_re_time,info_job_apply.show_person_read')->items;
        $apply_list = base_lib_BaseUtils::array_key_assoc($apply_list, 'apply_id');
        
        //工作经验
        $work_datas = $this->base_service_person_resume_work->getResumeWorks(implode(',',$resume_ids), 'work_id,resume_id,start_time,end_time,station,company_name,work_content');
        foreach ($work_datas->items as $workskey => $worksvalue) {
            $workslist[$worksvalue["resume_id"]][$workskey]['start_time']   = date('Y/m', strtotime($worksvalue['start_time']));
            $workslist[$worksvalue["resume_id"]][$workskey]['end_time']     = empty($worksvalue['end_time']) ? "至今" : date('Y/m', strtotime($worksvalue['end_time']));
            $workslist[$worksvalue["resume_id"]][$workskey]['station']      = $worksvalue['station'];
            $workslist[$worksvalue["resume_id"]][$workskey]['company_name'] = $worksvalue['company_name'];
            $workslist[$worksvalue["resume_id"]][$workskey]['work_content'] = base_lib_BaseUtils::cutstr($worksvalue['work_content'], 180, 'utf-8', '','...');
        }
        
        //用户联系方式获取数组
        $privileges = $this->base_service_person_person->checkMobilesPrivilege($resume_ids, $this->_userid);

        //生成apply_ids数组用于更新求职者查阅状态
        $apply_resource_list = $this->base_service_company_resume_applyresource->getApplyListByIds($apply_ids);
        $apply_resource_list = base_lib_BaseUtils::array_key_assoc($apply_resource_list,"apply_id");
        //获取是否是视频招聘的投递简历
        $shuangxuanpersonapplyrelate_list = $this->base_service_schoolnet_shuangxuanpersonapplyrelate->getApplies($apply_ids,$this->_userid,'id,apply_id');

        //求职者一周内是否登录过app
        $login_status = $this->base_service_person_loginlog->getLoginAppData($person_ids,14);
        !empty($login_status) && $login_status = base_lib_BaseUtils::array_key_assoc($login_status,'person_id');
        //判断这些person_ids对应的网易云账户是否在线
//        $wy_person_status_arr = $this->base_service_app_wangyiaction->checkPersonsIsOnline($person_ids);
        
        $chat_list        = $this->base_service_app_qcloudmsg->getReplyTimes($person_ids);
        $chat_list        = base_lib_BaseUtils::array_key_assoc($chat_list, "person_id");
        
        // 备注列表
        $remark_list = $this->base_service_company_resume_resumeremark->getLastResumeRemarks($this->_userid, $resume_ids, 'remark_id,resume_id,company_id,remark,update_time')->items;
        $remark_list = base_lib_BaseUtils::array_key_assoc($remark_list, "remark_id");

        foreach ($list as $k => $v) {
            $resume_id   = $v['resume_id'];
            $person_id   = $v['person_id'];
            $apply_id    = $v['apply_id'];
            $job_id      = $v['job_id'];
            
            $resume_info        = $resume_list[$resume_id];            
            $person_info        = $person_list[$person_id];   
            $job                = $jobs[$job_id]; 
            $apply              = $apply_list[$apply_id];
            
            // 获取申请状态
            $list[$k]['status'] = $this->getStatus($apply, $resume_info);
//            $list[$k]['statusName'] = $this->getStatusName($list[$k]['status']);
            $list[$k]['statusName'] = $this->getStatusNameV1($v);
            //获取视频招聘关联
            if(isset($shuangxuanpersonapplyrelate_list[$v['apply_id']])){
                $list[$k]['is_shuangxuan_relate'] = 1;
            }else{
                $list[$k]['is_shuangxuan_relate'] = 0;
            }
            
            // 非会员是否获取联系方式
            $list[$k]['need_contact'] = !$privileges[$resume_id];

            // 姓名
            $list[$k]['user_name'] = empty($person_info['user_name']) ? "&nbsp;" : $person_info['user_name'];

            $list[$k]['remark']         = base_lib_BaseUtils::nullOrEmpty($remark_list[$resume_id]['remark'])
						? false :  base_lib_BaseUtils::cutstr($remark_list[$resume_id]['remark'], 8, 'utf-8', '', "...")
						. '&nbsp' . date('Y-m-d', strtotime($remark_list[$resume_id]['update_time']));

            if ($person_info['name_open'] == 0) {
                $sex_name = $person_info['sex'] == 1 ? '先生' : '女士';
                $list[$k]['user_name'] = mb_substr($person_info['user_name'], 0, 1, 'utf-8') . $sex_name;
            }

            $list[$k]['full_user_name'] = base_lib_BaseUtils::htmlEncode($person_info['user_name']);
            $list[$k]['station'] = $job['station'];

            //已邀请的简历添加电话号码显示
            if($this->see_resume_mobile){
                $list[$k]['mobile_phone'] = $person_info['mobile_phone'];
            }else{
                $list[$k]['mobile_phone'] = preg_replace('#(\d{3})[a-z0-9-]{2,10}(\d{3})#', '${1}****${2}', $person_info['mobile_phone']);
            }

            //头像性别、年龄、学历、当前所在地
            if ($person_info['photo_open'] === '0') {//允许null,和1一样，默认可以公开
                $person_info['photo']       = false;
                $person_info['small_photo'] = false;
            } else {
                if (base_lib_BaseUtils::nullOrEmpty($person_info['small_photo'])) {
                    $person_info['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['photo'];
                } else {
                    $person_info['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['small_photo'];
                }
                //兼容判断
                if(base_lib_BaseUtils::nullOrEmpty($person_info['photo']))
                    $person_info['photo'] = false;
                else
                    $person_info['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['photo'];
            }

            $list[$k]['photo']       = $person_info['photo'];
            $list[$k]['small_photo'] = $person_info['photo'];//改版后头像用原始头像
            $list[$k]['sex']         = $this->getSex($person_info['sex']);
            $list[$k]['sex_v']       = $person_info['sex'];
            $list[$k]['age']         = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']) . '岁';
            $list[$k]['degree']      = $this->getDegree($resume_info['degree_id']);
            $list[$k]['cur_area']    = $this->getArea($person_info['cur_area_id']);
					
            // 是否为代招
            $list[$k]['generation_binding'] = $v['company_id'] == $this->_userid ? false : true; 

            //工作年限					
            $basic_start_work_year = base_lib_TimeUtil::date_diff_month($person_info['start_work']);		
            $workY = floor($basic_start_work_year / 12); 
            $workM = intval($basic_start_work_year % 12); 

            if ($workY <= 0 && $workM <=6 && $workM >= -6) {
                $basic_start_work_year = '应届毕业生';
            } else if ($workY == 0 && $workM > 6) {
                $basic_start_work_year = $workM . '个月工作经验';
            } else if ($basic_start_work_year < -6) {
                $basic_start_work_year = '目前在读';
            } else {
                $basic_start_work_year = $workY . '年工作经验';
            }

            $list[$k]['start_work'] = $basic_start_work_year;
            if (base_lib_BaseUtils::nullOrEmpty($list[$i]['start_work'])) {
                $list[$k]['start_work'] = "应届毕业生";
            }

            //聊一聊状态 ，提示等级 1：(未登录)提示 2：（未登录）提示 + 提示消耗 3：提示消耗
//            $list[$k]['chat_status'] = !empty($wy_person_status_arr[$person_id]) ? $wy_person_status_arr[$person_id] : false;
            $list[$k]['chat_status'] = !empty($login_status[$person_id]) ? true : false;

            //最近工作经验
            if ($resume_info['current_station'] == '') {
                $list[$k]['work'] = '无';
            } else {
                if (base_lib_BaseUtils::nullOrEmpty($resume_info['current_station_start_time'])) {
                    $list[$k]['work'] = $resume_info['current_station'];
                } else {
                    $list[$k]['work'] = $resume_info['current_station'] . '(' 
                        . base_lib_TimeUtil::date_diff_year3($resume_info['current_station_start_time'], $resume_info['current_station_end_time'])
                        . ')';
                }
            }

            //摘要
            $edu_info = $edu_data[$resume_id];
            $list[$k]['school']        = $edu_info['school'];
            $list[$k]['major_desc']    = $edu_info['major_desc'];
            $list[$k]['school_degree'] = $this->getDegree($edu_info['degree']) ;
            //var_dump($workslist["$resume_id"]);
            $count = count($workslist["$resume_id"]);
            if ($count > 0) {
                $list[$k]['worklist'] = array_slice($workslist[$resume_id], 0, ($count >= 3 ? 3 : $count));
            } else {
                $list[$k]['worklist'] = array();
            }

            //判断简历投递是否是从快米投递同步过来的
            $list[$k]['is_kuaimi'] = false;
            if($apply_resource_list[$apply_id]['resouce_type'] == $this->base_service_common_applyresource->kuaimi){
                $list[$k]['is_kuaimi'] = true;
            }
            base_lib_TimeUtil::to_friend_time2($date, $ifchina);
            //判断是否活跃
            $chat_info = $chat_list[$person_id];
            $list[$k]["is_active"] = $chat_info["count"] >= 3 ? true : false;
            $_time = date("Y-m-d") . " 00:00:00";
            $list[$k]['is_job_effect'] = 1;
            if ($job['check_state'] > 1
                    || ($job['status'] == $job_status->use 
                        && $job['end_time'] < $_time) 
                    || $job['status'] == $job_status->stop_use 
                    || $job['status'] == $job_status->deleted
                    || $job['is_effect'] == '0') {
                    $list[$k]['is_job_effect'] = 0;
                }
            
            $list[$k]['create_time'] = date('Y-m-d', strtotime($v['create_time'])) .(date('H', strtotime($v['create_time'])) < 13 ? ' 上午' : ' 下午'). date('g:i', strtotime($v['create_time']));
            $list[$k]['audition_time'] = date('Y-m-d', strtotime($v['audition_time'])) .(date('H', strtotime($v['audition_time'])) < 13 ? ' 上午' : ' 下午'). date('g:i', strtotime($v['audition_time']));
        }

		$aParams['applylist'] = $list;
    }
    
     /**
      *  获取显示的申请状态
      */
     private function getStatus($apply,$resume) {
     	if($resume['is_effect']==0) return 6;
     	if($apply['is_cancelled']==1) return 5;
     	if($apply['re_status']==9) return 9;
     	if($apply['re_status']==1) return 1;
     	if($apply['re_status']==3) return 3;
		if($apply['has_read']==2) return 10;//待定
     	if($apply['has_read']==1) return 4;
     	return 2;
     }
     
     /**
      * 状态信息
      */
     private function getStatusName($status) {
     	if($status==6) return '对方删除简历';
     	if($status==5) return '对方撤销投递';
        if($status==9) return '已自动过滤';
     	if($status==4) return '已读（未回复）';
     	if($status==3) return '不合适';
     	if($status==2) return '未读';
     	if($status==10) return '待定（未回复）';
     	return '已邀请';
     	
     }
     
     public function getStatusNameV1($v) {
     	if(!$v['audition_result'] && strtotime($v['audition_time']) > time())
            return "待面试";
        else if(!$v['audition_result'])
            return "待反馈";
        else if($v['audition_result'] == 1)
            return "面试通过";
        else if($v['audition_result'] == 2)
            return "面试未通过";
        else if($v['audition_result'] == 8)
            return "面试爽约";
        else if($v['audition_result'] == 9)
            return "已入职";
        
        return '';
     }
    
     /**
      * 获取性别
      */
     private function getSex($sex,$default='') {
     	 if(base_lib_BaseUtils::nullOrEmpty($sex)||$sex=='0') {
     	 	return  $default;
     	 }
     	 $enum_sex = new base_service_common_sex();
     	 return $enum_sex->getName($sex);
     }
     
     /**
      * 获取学历
      */
     private function getDegree($degree_id,$default='') {
		 if(empty($degree_id)) {
		 	return $default;
		 }
		 $service_degree = new base_service_common_degree();
		 return $service_degree->getDegree($degree_id);
     }	
     
    /**
     * 获取居住地
     * 默认 如果是填写的重庆下的地区，不显示重庆，只显示详细地址，非重庆的 只显示一级城市和二级地区
     */
    private function getArea($area_id,$default='',$isShowAll=false) {
     	if(base_lib_BaseUtils::nullOrEmpty($area_id)) {
     		return $default;
     	}
     	$service_area = new base_service_common_area();
     	$areas = $service_area->getTopAreaByAreaID($area_id);
     	$areas = array_reverse($areas);
     	$count  = count($areas);
     	if($count<=0) {
     		return $default;
     	}
     	$names  = array();
     	if($isShowAll) {
     	  for($i= 0;$i<$count;$i++) {
     		$area = $areas[$i];
     		array_push($names, $area['area_name']);	
     	  }     		
     	}else {
     	  $isChongqing = false;
     	  for($i= 0;$i<$count;$i++) {
     		$area = $areas[$i];
     	    if($i==0) {
     	    	if($count==1) {
     	    		array_push($names, $area['area_name']);
     	    		continue;
     	    	}
     	    	if($area['area_id']=='0300') {
     	    		$isChongqing = true;
     	    		continue;
     	    	}
     	    }
			if(!$isChongqing&&$i>=2) {
				break;
			}
		 	array_push($names, $area['area_name']);	
     		}    			
     	}
     	return implode('-', $names);
     } 
}
