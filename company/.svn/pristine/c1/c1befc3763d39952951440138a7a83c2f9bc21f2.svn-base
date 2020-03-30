<?php
/**
 * @name 面试管理
 * @author dl
 * @version 2019-8-30
 */
class controller_invitev1 extends components_cbasepage {

    function __construct() {
        parent::__construct();
    }

    /**
     * 面试邀请列表页面入口
     * @param $inPath
     */
    public function pageIndex($inPath) {
        $this->grayJump(2, $inPath);
        if(!$this->canDo("invite_magane")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
 	   	$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $station                = base_lib_BaseUtils::getStr($params['station'], 'string', '');
        $audition_result        = base_lib_BaseUtils::getStr($params['audition_result'], 'int', 99);
        $user_name              = base_lib_BaseUtils::getStr($params['user_name'], 'string', '');
        $min_audition_time      = base_lib_BaseUtils::getStr($params['min_audition_time'], 'string', '');
        $max_audition_time      = base_lib_BaseUtils::getStr($params['max_audition_time'], 'string', '');
        $order_type             = base_lib_BaseUtils::getStr($params['order_type'], 'string', '');
        $left_type              = base_lib_BaseUtils::getStr($params['left_type'], 'int', 1);
        $cur_page               = base_lib_BaseUtils::getStr($params['page'], 'int', 1);
        $page_size              = base_lib_BaseUtils::getStr($params['pageSize'], 'int', base_lib_Constant::PAGE_SIZE);

        if(!$order_type){
            if(!$audition_result)
                $order_type = 'desc';
            else 
                $order_type = 'asc';
        }
        
        $this->_aParams['showfilter']   = false;
		if (!base_lib_BaseUtils::nullOrEmpty($station)
            || !base_lib_BaseUtils::nullOrEmpty($min_audition_time)
            || !base_lib_BaseUtils::nullOrEmpty($max_audition_time)
            || !base_lib_BaseUtils::nullOrEmpty($user_name)) {

            $this->_aParams['showfilter'] = true;	
		}
        
        //点击几个状态页面刷新后是否埋点统计
        $this->_aParams['logLoadAction'] =  false;
        if(!$this->_aParams['showfilter'] && !base_lib_BaseUtils::nullOrEmpty($params['audition_result']) && !$params['order_type'] && !$params['page'])
            $this->_aParams['logLoadAction'] = true;
        
        //选择日期刷新后是否埋点统计
        $this->_aParams['selectDateAction'] =  false;
        if (isset($params['audition_result']) && $params['audition_result'] == 99 && 
                !$station && !empty($min_audition_time) && !$max_audition_time && !$user_name
                && !$params['order_type'] && !$params['page']) {

            $this->_aParams['selectDateAction'] = true;	
		}
        
        $service_company_resume_jobinvite = new base_service_company_resume_jobinvite();
        $enum_audition = new base_service_company_resume_auditionresult();
        $company_resources = new base_service_company_resources_resources($this->_userid);
        $service_company = new base_service_company_company();
        
        $this->_aParams['title'] = '面试管理_汇博网';
        $this->_aParams['station'] = $station;
        $this->_aParams['audition_result'] = $audition_result;
        $this->_aParams['user_name'] = $user_name;
        $this->_aParams['order_type'] = $order_type;
        $this->_aParams['left_type'] = $left_type;
        $this->_aParams['order_types'] = json_encode([['id'=>'asc', 'name'=>'时间由近到远'], ['id'=>'desc', 'name'=>'时间由远到近']]);
        
        if(!empty($min_audition_time) && !empty($max_audition_time) && strtotime($min_audition_time) > strtotime($max_audition_time))
            list($min_audition_time, $max_audition_time) = [$max_audition_time, $min_audition_time];
        
        $this->_aParams['min_audition_time'] = $min_audition_time;
        $this->_aParams['max_audition_time'] = $max_audition_time;
        
        $this->_aParams['banner_dates'] = [];
        if($audition_result == 99){
            $banner_show_days = 7;
            $begin_day = 0;
            //只选中一个日期，且在7天之内，顶部日期有选中状态
            if(empty($max_audition_time) && !empty($min_audition_time) && strtotime($min_audition_time) < strtotime(date('Y-m-d 23:59:59',strtotime('+6 days'))))
                $this->_aParams['select_audition_time'] = date('Y-m-d', strtotime($min_audition_time));
            
            //未选日期时，默认查询当前之后的数据
            while($begin_day < $banner_show_days){
                array_push($this->_aParams['banner_dates'], date('Y-m-d', strtotime("+{$begin_day} days")));
                $begin_day++;
            }
            $this->_aParams['banner_dates'] = $service_company_resume_jobinvite->getGonnaInterviewNumByDays($company_resources->all_accounts, $this->_aParams['banner_dates']);
        }
        
        $jobs = $service_company_resume_jobinvite->getInviteStationNew($company_resources->all_accounts, $audition_result == 9 ? 3 : null)->items;
        $jobs_json[] = ["id" => "", "name" => "全部职位"];
        if (count($jobs) > 0) {
            foreach ($jobs as $json)
                !empty($json['station']) && $jobs_json[] = ["id" => $json['station'], "name" => $json['station']];
		}
        $this->_aParams['jobs'] = json_encode($jobs_json);
        
        $company = $service_company->getCompany($this->_userid, 1, 'company_shortname,company_name');
        $this->_aParams['company_shortname'] = $company['company_shortname'] ? $company['company_shortname'] : $company['company_name'];
        
		$service_company_account = new base_service_company_account();
		$son_account_list = $service_company_account->getAccountList($company_resources->all_accounts,'account_id,company_id,is_main,user_id,user_name');
        $filed = 'audition_time,person_id,resume_id,audition_result,re_status,station,invite_id,job_id,entry_station,entry_time';
        $invite_list = $service_company_resume_jobinvite->getInviteListV1($page_size, $cur_page, $filed, $company_resources->all_accounts, $audition_result, $station, $user_name, $min_audition_time, $max_audition_time, $order_type);
		$list = $invite_list->items;
		if (!base_lib_BaseUtils::nullOrEmpty($list)) {
            $service_person        = new base_service_person_person();
            $service_resume_remark = new base_service_company_resume_resumeremark();

            // 求职者
			$person_ids = base_lib_BaseUtils::getProperty($list, 'person_id');
			$person_list = $service_person->GetPersonListByIDs($person_ids, 'person_id,user_name,sex,birthday2,cur_area_id,start_work,photo,small_photo,mobile_phone,telephone')->items;
			$person_list = base_lib_BaseUtils::array_key_assoc($person_list, 'person_id');
            // 备注列表
//			$remark_data = $service_resume_remark->getLastResumeRemarks($this->_userid, $resume_ids, 'remark_id,resume_id,company_id,remark,update_time');
		
            foreach ($list as $k => $v) {
                $list[$k]['audition_time_str'] = date('Y-m-d H:i', strtotime($v['audition_time']));
                $list[$k]['user_name'] = $person_list[$v['person_id']]['user_name'];
                $list[$k]['mobile_phone'] = base_lib_BaseUtils::nullOrEmpty($person_list[$v['person_id']]['mobile_phone']) ? $person_list[$v['person_id']]['telephone'] : $person_list[$v['person_id']]['mobile_phone'];
                $list[$k]['audition_result_name'] = $audition_result == 8 ? '面试爽约' : $enum_audition->getName($v['audition_result']);
            }

		}
        //分页
        $this->_aParams['pager'] = $this->pageBar($invite_list->totalSize, $page_size, $cur_page, $inPath);

        $this->_aParams['invitelist'] = $list;
        $this->_aParams['action_log_type'] = $this->__actionLogType($audition_result);

        return $this->render('resume/invite/invite_list_v1.html', $this->_aParams);
    }
    
    
    public function pageList($inPath){
        if(!$this->canDo("resume_manage")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
        $this->_aParams['title'] = '已邀请简历_汇博网';
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->_aParams['company_id']           = $company_id             = base_lib_BaseUtils::getStr($params['account_id'], 'int', '');
        $this->_aParams['son_account_id']       = $son_account_id         = base_lib_BaseUtils::getStr($params['son_account_id'], 'int', '');
        $this->_aParams['show_stop_job']        = $show_stop_job          = base_lib_BaseUtils::getStr($params['show_stop_job'], 'int', base_lib_BaseUtils::getCookie("showStopJobApply"));
        $this->_aParams['job_id']               = $job_id                 = base_lib_BaseUtils::getStr($params['job_id'], 'int', '');
        $this->_aParams['audition_result']      = $audition_result        = base_lib_BaseUtils::getStr($params['audition_result'], 'int', 101);
        $this->_aParams['invite_source']        = $invite_source          = base_lib_BaseUtils::getStr($params['invite_source'], 'int', '');
        $this->_aParams['min_create_time']      = $min_create_time        = base_lib_BaseUtils::getStr($params['min_create_time'], 'string', '');
        $this->_aParams['max_create_time']      = $max_create_time        = base_lib_BaseUtils::getStr($params['max_create_time'], 'string', '');
        $this->_aParams['min_audition_time']    = $min_audition_time      = base_lib_BaseUtils::getStr($params['min_audition_time'], 'string', '');
        $this->_aParams['max_audition_time']    = $max_audition_time      = base_lib_BaseUtils::getStr($params['max_audition_time'], 'string', '');
        $this->_aParams['keyword']              = $key_word               = base_lib_BaseUtils::getStr($params['keyword'], 'string', '');
        $this->_aParams['order_type']           = $order_type             = base_lib_BaseUtils::getStr($params['order_type'], 'string', 'asc');
        
        $audition_result -= 1;
        $this->_aParams['no_time_select'] = base_lib_BaseUtils::getStr($params['no_time_select'], 'bool', false);
        if($min_create_time || $max_create_time || $min_audition_time || $max_audition_time)
            $this->_aParams['no_time_select'] = false;
        
        $cur_page               = base_lib_BaseUtils::getStr($params['page'], 'int', 1);
        $page_size              = base_lib_BaseUtils::getStr($params['pageSize'], 'int', base_lib_Constant::PAGE_SIZE);

        $service_company_resume_jobinvite = new base_service_company_resume_jobinvite();
        $enum_audition = new base_service_company_resume_auditionresult();
        $company_resources = new base_service_company_resources_resources($this->_userid);
        $service_company = new base_service_company_company();
        
        $service_installcommon = new company_service_installcommon([
            '_userid' => $this->_userid,
            'account_id' => base_lib_BaseUtils::getCookie('accountid'),
            'company_resources' => $company_resources,
            'base_service_company_company' => $service_company,
            'base_service_company_resume_jobinvite' => $service_company_resume_jobinvite,
            'see_resume_mobile' => $this->canDo("see_resume_mobile")
        ]);
//        compact()
        $company_id = $company_id ? $company_id : $company_resources->all_accounts;
        $service_installcommon->setJobs($company_id, $son_account_id, $show_stop_job, $this->_aParams);
        $service_installcommon->setGetHrCompanys($this->_aParams);
        $service_installcommon->setAccounts($this->_aParams);
        $service_installcommon->setAuditionResults($this->_aParams);
        $service_installcommon->setInviteSources($this->_aParams);
        //面试日程
        $service_installcommon->setBannerDates($this->_aParams, $min_audition_time, $max_audition_time);//job_ids

        $filed = 'audition_time,person_id,resume_id,audition_result,re_status,station,invite_id,job_id,entry_station,entry_time,apply_id,create_time,company_id';
        $invite_list = $service_company_resume_jobinvite->getInviteListV2($page_size, $cur_page, $filed, $company_id, $job_id ? $job_id : $this->_aParams['job_ids'], $min_create_time, $max_create_time, $min_audition_time, $max_audition_time, 
            $audition_result, $invite_source, $key_word, $order_type);
		$list = $invite_list->items;

		$service_installcommon->setResumeList($this->_aParams, $list);
        
        //分页
        $this->_aParams['pager'] = $this->pageBar($invite_list->totalSize, $page_size, $cur_page, $inPath);
        $this->_aParams['totalSize'] = $invite_list->totalSize;
        
        return $this->render('resume/apply/invite_gray.html', $this->_aParams);
    }
    
    public function pageDealedList($inPath){
        if(!$this->canDo("resume_manage")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
        
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->_aParams['company_id']           = $company_id             = base_lib_BaseUtils::getStr($params['account_id'], 'int', '');
        $this->_aParams['son_account_id']       = $son_account_id         = base_lib_BaseUtils::getStr($params['son_account_id'], 'int', '');
        $this->_aParams['show_stop_job']        = $show_stop_job          = base_lib_BaseUtils::getStr($params['show_stop_job'], 'int', base_lib_BaseUtils::getCookie("showStopJobApply"));
        $this->_aParams['job_id']               = $job_id                 = base_lib_BaseUtils::getStr($params['job_id'], 'int', '');
        $this->_aParams['audition_result']      = $audition_result        = base_lib_BaseUtils::getStr($params['audition_result'], 'int', 1);
        $this->_aParams['invite_source']        = $invite_source          = base_lib_BaseUtils::getStr($params['invite_source'], 'int', '');
        $this->_aParams['min_create_time']      = $min_create_time        = base_lib_BaseUtils::getStr($params['min_create_time'], 'string', '');
        $this->_aParams['max_create_time']      = $max_create_time        = base_lib_BaseUtils::getStr($params['max_create_time'], 'string', '');
        $this->_aParams['invite_time']          = $invite_time            = base_lib_BaseUtils::getStr($params['invite_time'], 'string', '');
        $this->_aParams['keyword']              = $key_word               = base_lib_BaseUtils::getStr($params['keyword'], 'string', '');
        $this->_aParams['order_type']           = $order_type             = base_lib_BaseUtils::getStr($params['order_type'], 'string', 'asc');
        
        $cur_page               = base_lib_BaseUtils::getStr($params['page'], 'int', 1);
        $page_size              = base_lib_BaseUtils::getStr($params['pageSize'], 'int', base_lib_Constant::PAGE_SIZE);

        if($key_word == "输入姓名或简历编号")
            $this->_aParams['keyword'] = $key_word = "";
        
        if($invite_time == 1)
            $min_audition_time = $max_audition_time = date('Y-m-d');
        else if($invite_time == 2)
            $min_audition_time = $max_audition_time = date('Y-m-d', strtotime('-1 day'));
        else if($invite_time == 3)
            $min_audition_time = date('Y-m-d', strtotime('-7 days'));
        else if($invite_time == 4)
            $min_audition_time = date('Y-m-d', strtotime('-30 days'));
        else if($invite_time == 5)
            $min_audition_time = date('Y-m-d', strtotime('-90 days'));     
        
        $service_company_resume_jobinvite = new base_service_company_resume_jobinvite();
        $enum_audition = new base_service_company_resume_auditionresult();
        $company_resources = new base_service_company_resources_resources($this->_userid);
        $service_company = new base_service_company_company();
        
        $service_installcommon = new company_service_installcommon([
            '_userid' => $this->_userid,
            'account_id' => base_lib_BaseUtils::getCookie('accountid'),
            'company_resources' => $company_resources,
            'base_service_company_company' => $service_company,
            'base_service_company_resume_jobinvite' => $service_company_resume_jobinvite,
            'see_resume_mobile' => $this->canDo("see_resume_mobile")
        ]);
        $this->_aParams['nav_name'] = $service_installcommon->getStatusNameV1(['audition_result' => $audition_result]);
        $this->_aParams['title'] = $this->_aParams['nav_name'] . '_汇博网';
//        compact()
        $company_id = $company_id ? $company_id : $company_resources->all_accounts;
        $service_installcommon->setJobs($company_id, $son_account_id, $show_stop_job, $this->_aParams);
        $service_installcommon->setGetHrCompanys($this->_aParams);
        $service_installcommon->setAccounts($this->_aParams);
        $service_installcommon->setInviteSources($this->_aParams);

        $filed = 'audition_time,person_id,resume_id,audition_result,re_status,station,invite_id,job_id,entry_station,entry_time,apply_id,create_time,company_id';
        $invite_list = $service_company_resume_jobinvite->getInviteListV2($page_size, $cur_page, $filed, $company_id, $job_id ? $job_id : $this->_aParams['job_ids'], $min_create_time, $max_create_time, $min_audition_time, $max_audition_time, 
            $audition_result, $invite_source, $key_word, $order_type);
		$list = $invite_list->items;

		$service_installcommon->setResumeList($this->_aParams, $list);
        //分页
        $this->_aParams['pager'] = $this->pageBar($invite_list->totalSize, $page_size, $cur_page, $inPath);
        $this->_aParams['totalSize'] = $invite_list->totalSize;

        return $this->render('resume/invite/invite_list_v1_gray.html', $this->_aParams);
    }
    
    public function pageEntryJob($inPath){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $station    = base_lib_BaseUtils::getStr($params['station'], 'string', '');
        $entry_time = base_lib_BaseUtils::getStr($params['entry_time'], 'string', '');
        $invite_id  = base_lib_BaseUtils::getStr($params['invite_id'], 'int', 0);

        if(empty($station)){
            echo json_encode(['status'=> false, 'msg'=>'请填写入职职位']);
            return;
        }
        if(empty($entry_time)){
            echo json_encode(['status'=> false, 'msg'=>'请选择入职时间']);
            return;
        }
        if(empty($invite_id)){
            echo json_encode(['status'=> false, 'msg'=>'操作失败']);
            return;
        }
        
        $service_company_resume_jobinvite = new base_service_company_resume_jobinvite();
        $result = $service_company_resume_jobinvite->entryJob($invite_id, $station, $entry_time);
        
        if($result === false){
            echo json_encode(['status'=> false, 'msg'=>'操作失败']);
            return;
        }
        
        echo json_encode(['status'=> true, 'msg'=>'操作成功']);
        return;
    }
    
    private function __actionLogType($audition_result){
        switch ($audition_result){
            case 0:
                return 368;
            case 1:
                return 369;
            case 2:
                return 370;
            case 8:
                return 371;
            default :
                return 367;
        }
    }
}
?>