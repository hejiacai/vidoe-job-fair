<?php
/**
 * @copyright 2002-2013 www.huibo.com
 * @name 职位申请（收到的简历） 
 * @author fuzy
 * @version 2013-7-11 上午11:20:56
*/
class controller_apply extends components_cbasepage {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * 主账号及使用“共享资源”模式的子账号默认不限，使用“分配资源”模式的子账号默认自己
	 */
	private function checkAccountResource(){
		$service_company_account = new base_service_company_account();
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$fields = 'account_id,company_id,is_main';
		$account = $service_company_account->getAccount($account_id, $fields);
		if($account['is_main'] == 1){
			return true;
		}
		$company_resources =  base_service_company_resources_resources::getInstance($this->_userid,true,$account_id);
		$company_resources_info = $company_resources->getCompanyServiceSource(['account_resource']);
		if($company_resources_info['resource_type'] == 1){
			//共享模式
			return true;
		}else{
			return false;
		}
	}
    
	/**
	 * 收到的简历列表
	 * @param  $inPath
	 */
	public function pageIndex($inPath) {
        $this->grayJump(1, $inPath);
        if(!$this->canDo("apply_resume_init") || !$this->canDo("resume_manage")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
		$params           = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		//该企业是否需要使用新功能（是否参与灰度测试
		$is_gray = $this->is_gray_company;
		if($is_gray){
			//灰度测试单位跳转新方法
			$this->redirect_url("/apply/grayIndex", $params);
		}


		// 参数信息:  职位编号，投递时间，状态，姓名/简历编号，显示方式（列表/摘要）  
		$job_id           = base_lib_BaseUtils::getStr($params['job_id'], 'int', null);
		$invite_time      = base_lib_BaseUtils::getStr($params['invite_time'], 'int', null);
		$applystatus      = base_lib_BaseUtils::getStr($params['status'], 'int', 2); //若未传状态参数，默认显示未处理的简历  //简历几日回复可传入几个状态：3,5同时传
		$keyword          = base_lib_BaseUtils::getStr($params['keyword'], 'string', '');
		$time             = base_lib_BaseUtils::getStr($params['time'], 'int', null);
		$showmode         = base_lib_BaseUtils::getStr($params['show_model'], 'int', '1'); // 显示方式 0表示列表，1表示摘要  
		$searchmode       = base_lib_BaseUtils::getStr($params['search_model'], 'int', '0'); // 查询方式 0表示按筛选条件，1表示按姓名/简历编号  
		$cur_page         = base_lib_BaseUtils::getStr($params['page'], 'int', 1);
		$show_not_use_job = base_lib_BaseUtils::getStr($params['show_not_use'], 'int', 1);//是否不显示已停止招聘的职位
		$page_size        = base_lib_BaseUtils::getStr($params['pageSize'], 'int', base_lib_Constant::PAGE_SIZE);
		$child_status     = base_lib_BaseUtils::getStr($params['child_status'], 'int', null);
		$account_id       = base_lib_BaseUtils::getStr($params['account_id'], 'int', "");

		$search       	  = base_lib_BaseUtils::getStr($params['search'], 'int', "");

		$promiseStop = base_lib_BaseUtils::getCookie('showPromiseStop');

		//是否默认选择当前账号
		$is_main = $this->checkAccountResource();
		if($is_main){
			$account_id_temp = "";
		}else{
			$account_id_temp = base_lib_BaseUtils::getCookie('accountid');
		}

		if($search || $promiseStop == 1){
			$account_id_temp = "";
		}

		//var_dump($account_id_temp);
		$son_account_id       = base_lib_BaseUtils::getStr($params['son_account_id'], 'int', $account_id_temp);

		$this->_aParams['account_id'] = $account_id;
		$this->_aParams['son_account_id'] = $son_account_id;

	/*************************简历二次筛选***************************/
		$screening_list = $this->_fixParams($params)[1];
		$path_data = $this->_fixParams($params)[0];
		//简历筛选条件
		$this->_aParams['marriage_id'] = $path_data['marriage_id'] ? $path_data['marriage_id'] : 0;

		$this->_aParams['dutytime_id'] = $path_data['dutytime_id'] ? $path_data['dutytime_id'] : 0;

		$this->_aParams['sex_id'] = $path_data['sex_id'] ? $path_data['sex_id'] : 0;

		$this->_aParams['years_id'] = $path_data['years_id'] ? $path_data['years_id'] : 0;
		$this->_aParams['years_id2'] = $path_data['years_id2'] ? $path_data['years_id2'] : 0;

		//学历搜索
		$this->_aParams['education_id'] = $path_data['education_id'] ? $path_data['education_id'] : 0;
		$this->_aParams['education_id2'] = $path_data['education_id2'] ? $path_data['education_id2'] : 0;


		$this->_aParams['amin'] = $path_data['amin'] ? $path_data['amin'] : 0;
		$this->_aParams['amax'] = $path_data['amax'] ? $path_data['amax'] : 0;

		$this->_aParams['is_show_sceening'] = true;
		$int_dutytime = intval($path_data['dutytime_id']);
		if(!empty($path_data['marriage_id']) || !empty($path_data['amin']) || !empty($path_data['amax']) || !empty($int_dutytime) || !empty($path_data['education_id'])||!empty($path_data['education_id2'])||!empty($path_data['years_id'])||!empty($path_data['years_id2'])||!empty($path_data['sex_id'])){
			$this->_aParams['is_show_sceening'] = false;
		}
	/*************end***********************************************/



		// 公司会员区分

		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
		$member = $company_resources->account_type;
		$this->_aParams['member'] = $member;
        //新加判断，之前用的memberinfo已弃用，发现一直没改
        $this->_aParams['memberinfo'] = $memberinfo = $company_resources->isMember() ? "member" : "not_member";
        
        $account_id = empty($account_id) || !in_array($account_id, $company_resources->all_accounts) ? $company_resources->all_accounts : $account_id;


		//获取所有账号
		$service_company_account = new base_service_company_account();
		$son_account_list = $service_company_account->getAccountList($company_resources->all_accounts,'account_id,company_id,is_main,user_id,user_name');

        $company_service = new base_service_company_company();
        $accounts = $company_service->getCompanys($company_resources->all_accounts, "company_id,company_shortname,company_name");
        $_accounts = [];

        // 是否为hr代招会员
        $this->_aParams['is_hr'] = $company_resources->account_type == 'hr_main' ? true : false;

        $_datas[] = ["id" => "0", "name" => "所有公司"];
        foreach ($accounts as $key => $account) {
            $account['company_name_display']   = $account['company_shortname'] ? $account['company_shortname'] : $account['company_name'];
            $_accounts[$account['company_id']] = $account;

            $_datas[] = ['id' => $account['company_id'], "name" => $account['company_name_display']];
        }

        $this->_aParams['accounts']      = $_accounts;

        $this->_aParams['accounts_json'] = json_encode($_datas);

		// 职位
		$base_service_company_job_job = new base_service_company_job_job();
		$service_apply = new base_service_company_resume_apply();

		$account_job_ids = '';
		if($son_account_id){
			$account_job_list =$base_service_company_job_job->getJobIdByAccount($company_resources->all_accounts,$son_account_id,'company_id,job_id,account_id');
			$account_job_ids = base_lib_BaseUtils::getProperty($account_job_list->items,'job_id');
		}


        $job_ids = [];
        if(!$son_account_id || !empty($account_job_list)){
            $hasApplyJobs = $service_apply->getHasApplyJobIdsNew($account_id,$account_job_ids);

            $job_ids = base_lib_BaseUtils::getProperty($hasApplyJobs->items, 'job_id');
            $job_ids = array_unique($job_ids);
        }
        

		$use_job_ids = array();
		$showStopJobApply = base_lib_BaseUtils::getCookie("showStopJobApply"); //判断用户是否包含了 停招招聘的职位
		$this->_aParams['showStopJobApply'] = $showStopJobApply;
		$jobs = $this->_getJobsAndSort($job_ids, $show_not_use_job, $job_id, $use_job_ids, $showStopJobApply);
		if($son_account_id && empty($account_job_ids)){
			$jobs = array();
		}


		if (!base_lib_BaseUtils::nullOrEmpty($showStopJobApply) && $showStopJobApply == "true") {
			$use_job_ids = null;
		}
        $account_job_ids = array_unique(base_lib_BaseUtils::getProperty($jobs, 'job_id'));
		//学历
		$degree = new base_service_common_degree();
		$degree_lists = $degree->getAll();
		$degree_lists_temp[] = ["id" => "00", "name" => "不限"];
		foreach ($degree_lists as $key => $account) {
			$degree_lists_temp[] = ['id' => $key, "name" => $account];
		}
		$this->_aParams['degree_lists'] = json_encode($degree_lists_temp);

		//到岗时间
		$common_accessiontime = new base_service_common_accessiontime();
		$common_accessiontime_list = $common_accessiontime->getAll();
		$common_accessiontime_list_temp[] = ["id" => "00", "name" => "不限"];
		foreach ($common_accessiontime_list as $key => $account) {
			$common_accessiontime_list_temp[] = ['id' => $key, "name" => $account];
		}
		$this->_aParams['common_accessiontime_list'] = json_encode($common_accessiontime_list_temp);

		//婚姻状态
		$common_marriage = new base_service_common_marriage();
		$common_marriage_list = $common_marriage->getMarriage();
		$common_marriage_list_temp[] = ["id" => "0", "name" => "不限"];
		foreach ($common_marriage_list as $key => $account) {
			$common_marriage_list_temp[] = ['id' => $key, "name" => $account];
		}
		$this->_aParams['common_marriage_list'] = json_encode($common_marriage_list_temp);

		//工作年限
		$workyear_common    = new company_service_workyear();
		$common_workyear_list = $workyear_common->getAll();
		$common_workyear_list_temp[] = ["id" => "0", "name" => "不限"];
		foreach ($common_workyear_list as $key => $account) {
			$common_workyear_list_temp[] = ['id' => $key, "name" => $account];
		}
		$this->_aParams['common_workyear_list'] = json_encode($common_workyear_list_temp);

		//性别
		$common_sex = new base_service_common_sex();
		$common_sex_list = $common_sex->getSex();
		$common_sex_list_temp[] = ["id" => "0", "name" => "不限"];
		foreach ($common_sex_list as $key => $account) {
			$common_sex_list_temp[] = ['id' => $key, "name" => $account];
		}
		$this->_aParams['common_sex_list'] = json_encode($common_sex_list_temp);



		//----START 新增停止招聘职位和投递数量. 2016-6-8 ------
		//-》获取某个企业停止招聘职位的投递信息总信息
		$this->_aParams['count_job_stop_num'] = 0;
		$this->_aParams['count_job_stop_apply_num'] = 0;
		$service_job = new base_service_company_job_job();
		$service_cache_refuse_job = new company_service_applyrefusecache();
		$cache_info = array();
		if($applystatus == 2){
			//获取公司下所有停止招聘的职位id
            $job_status = new base_service_common_jobstatus();
			$job_no_use = $service_job->getJobList($account_id, '', $job_status->stop_use, 'job_id,end_time',0,0,null,$son_account_id);
			if(!empty($job_no_use))
			{
                $end_date = date("Y-m-d 00:00:00",  strtotime("-90 days"));
                $refuse_job_ids = array();
                foreach($job_no_use as $value){
                    if($value["end_time"] > $end_date){
                        $refuse_job_ids[] = $value["job_id"];
                    }
                }
				$cache_info['refuse_job_ids'] = $refuse_job_ids;
				$job_stop_apply_detail = $service_apply->getWaitDealNumByJobIds($cache_info['refuse_job_ids'])->items;

				foreach($job_stop_apply_detail as $k=>$v){
					$this->_aParams['count_job_stop_apply_num'] += $v['num'];
				}
				$this->_aParams['job_stop_apply_detail'] = $job_stop_apply_detail;
				$this->_aParams['count_job_stop_num'] = @(int)count($job_stop_apply_detail);
			}

		}
		//----END 新增停止招聘职位和投递数量. 2016-6-8 ------



		if ($invite_time === 0) {
	    	$invite_time = '+0'; // XXX：js的下拉组件默认认为选项为0的项为默认项
	    }


	    /*========================== 简历几日回复 start =============================*/ 
	    if ($applystatus == "3" || $applystatus == "2") {	    	


	    	base_lib_BaseUtils::ssetcookie(array('showPromiseStop' => ''), -1); //删除cookie
	    	$this->_aParams['showPromise'] = $promiseStop;

	    	if (!empty($promiseStop) && $promiseStop == '1') {
	    		$searchstatus = $applystatus . ",5";    	
	    	} else {
	    		$searchstatus = is_numeric($applystatus) ? $applystatus : 2;
	    	}

	    	$this->_aParams['auto_filter_count'] = $service_apply->getPromiseAutoReplyCount(
	    		$account_id,
	    		$use_job_ids
	    	);
	    } else {
	    	$searchstatus = is_numeric($applystatus) ? $applystatus : 2;
	    }

	    /*========================== 简历几日回复 end =============================*/
		$job_id_temp = '';
		if(!base_lib_BaseUtils::nullOrEmpty($son_account_id) && base_lib_BaseUtils::nullOrEmpty($job_id)){
			$job_id_temp = $account_job_ids;
		}else{
			$job_id_temp = $job_id;
		}

		$contain_cancel_apply = true;
    	if ($searchmode == 0) {
			$apply_list = $service_apply->getApplyByCompanyV2($page_size, $cur_page, $account_id, 'info_job_apply.apply_id,'
				. 'info_job_apply.person_id,info_job_apply.resume_id,info_job_apply.station,info_job_apply.create_time,info_job_apply.company_id,'
				. 'info_job_apply.is_cancelled,info_job_apply.has_read,info_job_apply.re_status,info_job_apply.job_id,info_job_apply.remind_time,info_job_apply.need_re_time,info_job_apply.show_person_read',
				$job_id_temp, $time, $searchstatus, $child_status, $invite_time, $use_job_ids,$screening_list,$contain_cancel_apply);
			if($applystatus == 2)
			{
				//获取当前所有简历ID
				$all_apply_ids = $service_apply->getApplyByCompanyV2('', '', $account_id, 'info_job_apply.apply_id',
					$job_id_temp, $time, $searchstatus, $child_status, $invite_time, $use_job_ids,$screening_list,$contain_cancel_apply);

				if(!empty($all_apply_ids->items)){
					$cache_info['all_apply_ids'] =  base_lib_BaseUtils::getProperty($all_apply_ids->items,'apply_id');
				}
			}
		} else {
			$job_id        = null;
			$apply_time    = null;
			$child_status  = null;
			$apply_list = $service_apply->getApplyByNameOrResumeID($page_size, $cur_page, $account_id, 'apply_id,info_job_apply.company_id,'
				. 'info_job_apply.person_id,info_job_apply.resume_id,info_job_apply.station,info_job_apply.create_time,info_job_apply.remind_time,info_job_apply.need_re_time,'
				. 'is_cancelled,has_read,re_status,info_job_apply.job_id,show_person_read', $keyword, $searchstatus, $use_job_ids,$contain_cancel_apply);
			if($applystatus == 2)
			{
				//获取当前所有简历ID
				$all_apply_ids = $service_apply->getApplyByNameOrResumeID('', '', $account_id, 'apply_id', $keyword, $searchstatus, $use_job_ids,$contain_cancel_apply);
				if(!empty($all_apply_ids->items)){
					$cache_info['all_apply_ids'] =  base_lib_BaseUtils::getProperty($all_apply_ids->items,'apply_id');
				}
			}

		}

		$show_person_read_applyids = array();
		if ($apply_list !== false) {
			if (!empty($apply_list->items)) {
				$list = $apply_list->items;
				$service_person        = new base_service_person_person();
				$service_resume        = new base_service_person_resume_resume();
				$service_resume_work   = new base_service_person_resume_work();
				$service_resume_remark = new base_service_company_resume_resumeremark();
				$service_download      = new base_service_company_resume_download();
				$service_company       = new base_service_company_company();
                $service_invites 	   = new base_service_company_resume_jobinvite();

				//筛选即将过期的承诺职位
				// 求职者
				$person_ids = implode(',',base_lib_BaseUtils::getProperty($list, 'person_id'));
				
				$person_list = $service_person->GetPersonListByIDs($person_ids, 'person_id,user_name,name_open,photo_open,sex,birthday2,'
					. 'cur_area_id,start_work,photo,small_photo,mobile_phone');
				
				// 简历
				$resume_ids     = $this->getPropertys($list, 'resume_id');
				$apply_job_ids  = $this->getPropertys($list, 'job_id');

				// 公司基本信息 
				$company = $service_company->getCompany($this->_userid, 1, "end_time");

                $service_qloudmsg = new base_service_app_qcloudmsg();
                $chat_list        = $service_qloudmsg->getReplyTimes($person_ids);
                $chat_list        = base_lib_BaseUtils::array_key_assoc($chat_list, "person_id");
                
				// 投递信息
				$apply_job_info = $service_job->getJobs($apply_job_ids, 'station,company_id,reply_content,allow_email,other_email,self_linkway,'
					. 'work_year_id,allow_graduate,degree_id,sex,age_lower,age_upper,is_automatic,check_state,end_time,status,is_effect');

				// 简历信息
				$resume_list = $service_resume->getResumes($resume_ids, 'person_id,resume_id,resume_name,degree_id,current_station,'
					. 'current_station_start_time,current_station_end_time,is_effect,mobile_phone,create_time');

				// 下载联系方式信息
				$down_list   = $service_download->queryDownloadList($resume_ids, $this->_userid, "resume_id,company_id");
				$down_list   = base_lib_BaseUtils::array_key_assoc((array)$down_list->items, "resume_id");

				// 备注列表
				$remark_data = $service_resume_remark->getLastResumeRemarks($this->_userid, $resume_ids, 'remark_id,resume_id,company_id,remark,update_time');
				
				// 摘要
				if ($showmode == 1) {
					//毕业院校
					$service_edu = new base_service_person_resume_edu();
					$edu_data = $service_edu->getResumeEdus(implode(',',$resume_ids),'resume_id,school,major_desc,degree');
					//工作经验
					$work_datas = $service_resume_work->getResumeWorks(implode(',',$resume_ids), 'work_id,resume_id,start_time,end_time,station,company_name,work_content');
					foreach ($work_datas->items as $workskey => $worksvalue) {
						$workslist[$worksvalue["resume_id"]][$workskey]['start_time']   = date('Y/m', strtotime($worksvalue['start_time']));
						$workslist[$worksvalue["resume_id"]][$workskey]['end_time']     = empty($worksvalue['end_time']) ? "至今" : date('Y/m', strtotime($worksvalue['end_time']));
						$workslist[$worksvalue["resume_id"]][$workskey]['station']      = $worksvalue['station'];
						$workslist[$worksvalue["resume_id"]][$workskey]['company_name'] = $worksvalue['company_name'];
						$workslist[$worksvalue["resume_id"]][$workskey]['work_content'] = base_lib_BaseUtils::cutstr($worksvalue['work_content'], 180, 'utf-8', '','...');
					}
				}

				//用户联系方式获取数组
    			$privileges = $service_person->checkMobilesPrivilege($resume_ids, $this->_userid);

				//生成apply_ids数组用于更新求职者查阅状态
				$apply_ids           = base_lib_BaseUtils::getProperty($list, 'apply_id');
				$refuse_result_array = array();
				$refuse_apply_ids    = array();
				if ($applystatus == 3) {
					//如果是已拒绝的简历，那么判断是手动拒绝还是自动拒绝
					$service_autorefuse = new base_service_company_resume_applyautorefuse();
					$refuse_result_array = $service_autorefuse->getRefuseList($apply_ids, "apply_id");
					$refuse_apply_ids    = count($refuse_result_array) > 0 ? $this->getPropertys($refuse_result_array, 'apply_id') : array();
				}

                $service_company = new base_service_company_company();
                $site_type       = $service_company->getCompany($this->_userid,1,'site_type')['site_type'];
                //区县企业投递优先查看
                $top_apply_ids = array();
                if($site_type == 2){
                    $service_applytop = new base_service_mobile_applytop();
                    $top_apply        = $service_applytop->getTopByCompany($this->_userid,'apply_id');
                    $top_apply_ids    = base_lib_BaseUtils::getProperty($top_apply,'apply_id');
                    //更新优先查看的投递记录
                    $service_applytop->updateTop($top_apply_ids,['top_status'=>2]);
                }
                $len = count($list);
                $top_list  = array();
				$apply_ids = base_lib_BaseUtils::getPropertys($list,"apply_id");

				$service_company_resume_applyresource = new base_service_company_resume_applyresource();
				$service_common_applyresource = new base_service_common_applyresource();
                $job_status = new base_service_common_jobstatus();

				$apply_resource_list = $service_company_resume_applyresource->getApplyListByIds($apply_ids);
				$apply_resource_list = base_lib_BaseUtils::array_key_assoc($apply_resource_list,"apply_id");
                //获取是否是视频招聘的投递简历
				$service_schoolenet_relate = new base_service_schoolnet_shuangxuanpersonapplyrelate();
				$shuangxuanpersonapplyrelate_list = $service_schoolenet_relate->getApplies($apply_ids,$this->_userid,'id,apply_id');

				//求职者一周内是否登录过app
                $sercie_loginlog= new base_service_person_loginlog();
                $login_status = $sercie_loginlog->getLoginAppData($person_ids,14);
                if(!empty($login_status))
                    $login_status = base_lib_BaseUtils::array_key_assoc($login_status,'person_id');
				//$service_chat = new company_service_chat(0,0);
				//$service_wangyiaction = new base_service_app_wangyiaction();
                //判断这些person_ids对应的网易云账户是否在线
                //$wy_person_status_arr = $service_wangyiaction->checkPersonsIsOnline($person_ids);
                for ($i = 0; $i < $len; $i++) {
					$apply       = $list[$i];
					$resume_info = $this->arrayFind($resume_list->items, 'resume_id', $apply['resume_id']);              
					$resume_id   = $resume_info['resume_id'];
					$person_id   = $apply['person_id'];

					if($apply['show_person_read'] != '1'){
						array_push($show_person_read_applyids,$apply['apply_id']);
					}

					$list[$i]['is_auto_refuse'] = false;
					if ($applystatus == 3 || !base_lib_BaseUtils::nullOrEmpty($refuse_apply_ids)) {
						//判断是否是拒绝的简历
						if (in_array($apply['apply_id'], $refuse_apply_ids)) {
							$list[$i]['is_auto_refuse'] = true;//是自动拒绝
						}
					}
                    //获取视频招聘关联
					if(isset($shuangxuanpersonapplyrelate_list[$apply['apply_id']])){
						$list[$i]['is_shuangxuan_relate'] = 1;
					}else{
						$list[$i]['is_shuangxuan_relate'] = 0;
					}

					$person_info        = $this->arrayFind($person_list->items, 'person_id', $person_id);
					$resume_work_info   = $this->arrayFind($workslist, 'resume_id', $resume_id);
					$resume_remark_info = $this->arrayFind($remark_data->items, 'resume_id', $resume_id);

					// 非会员是否获取联系方式
					$list[$i]['need_contact'] = !$privileges[$resume_id];

					// 姓名
					$list[$i]['user_name'] = empty($person_info['user_name']) ? "&nbsp;" : $person_info['user_name'];

					if ($person_info['name_open'] == 0) {
						$sex_name = $person_info['sex'] == 1 ? '先生' : '女士';
						$list[$i]['user_name'] = mb_substr($person_info['user_name'], 0, 1, 'utf-8') . $sex_name;
					}

					$list[$i]['full_user_name'] = base_lib_BaseUtils::htmlEncode($person_info['user_name']);
					$list[$i]['remark']         = base_lib_BaseUtils::nullOrEmpty($resume_remark_info['remark'])
						? false :  base_lib_BaseUtils::cutstr($resume_remark_info['remark'], 8, 'utf-8', '', "...")
							. '&nbsp' . date('Y-m-d', strtotime($resume_remark_info['update_time']));

	              	//已邀请的简历添加电话号码显示
					if (!$list[$i]['need_contact'] && ($memberinfo != 'member' || $applystatus == 1)) {
                        if($this->canDo("see_resume_mobile")){
                            $list[$i]['mobile_phone'] = $person_info['mobile_phone'];
                        }else{
                            $list[$i]['mobile_phone'] = preg_replace('#(\d{3})[a-z0-9-]{2,10}(\d{3})#', '${1}****${2}', $person_info['mobile_phone']);
                        }
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

					$list[$i]['photo']       = $person_info['photo'];
					$list[$i]['small_photo'] = $person_info['photo'];//改版后头像用原始头像
					$list[$i]['sex']         = $this->getSex($person_info['sex']);
					$list[$i]['age']         = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']) . '岁';
					$list[$i]['degree']      = $this->getDegree($resume_info['degree_id']);
					$list[$i]['cur_area']    = $this->getArea($person_info['cur_area_id']);
					
					// 是否为代招
					$list[$i]['generation_binding'] = $list[$i]['company_id'] == $this->_userid ? false : true; 

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

					$list[$i]['start_work'] = $basic_start_work_year;
					if (base_lib_BaseUtils::nullOrEmpty($list[$i]['start_work'])) {
						$list[$i]['start_work'] = "应届毕业生";
					}

					//聊一聊状态 ，提示等级 1：(未登录)提示 2：（未登录）提示 + 提示消耗 3：提示消耗
					$chat_params['resume_id'] = $resume_id;
					$chat_params['person_id'] = $person_id;
					$chat_params['company_id'] = $this->_userid;
                    $list[$i]['chat_status'] = !empty($login_status[$person_id]) ? true : false;
                    //$list[$i]['chat_status'] = $service_chat->getChatNoticeStatus($this->_userid,base_lib_BaseUtils::getCookie('accountid'),null,$chat_params,$person_id);
                    //$list[$i]['chat_status'] = $service_wangyiaction->checkPersonIsOnline($person_id);
                    //$list[$i]['chat_status'] = !empty($wy_person_status_arr[$person_id]) ? $wy_person_status_arr[$person_id] : false;


					// 投递时间
					$list[$i]['apply_time'] = base_lib_TimeUtil::to_friend_time($list[$i]['create_time']);
					
					//最近工作经验
					if ($resume_info['current_station'] == '') {
						$list[$i]['work'] = '无';
					} else {
						if (base_lib_BaseUtils::nullOrEmpty($resume_info['current_station_start_time'])) {
							$list[$i]['work'] = $resume_info['current_station'];
						} else {
							$list[$i]['work'] = $resume_info['current_station'] . '(' 
								. base_lib_TimeUtil::date_diff_year3($resume_info['current_station_start_time'], $resume_info['current_station_end_time'])
								. ')';
						}
					}

					//摘要
					if ($showmode == 1) {
						$edu_info = $this->arrayFind($edu_data->items, 'resume_id', $resume_id);
						$list[$i]['school']        = $edu_info['school'];
						$list[$i]['major_desc']    = $edu_info['major_desc'];
						$list[$i]['school_degree'] = $this->getDegree($edu_info['degree']) ;
						//var_dump($workslist["$resume_id"]);
						$count = count($workslist["$resume_id"]);
						if ($count > 0) {
							$list[$i]['worklist'] = array_slice($workslist["$resume_id"], 0, ($count >= 3 ? 3 : $count));
						} else {
							$list[$i]['worklist'] = array();
						}
					}

					// 获取申请状态
					$status = $this->getStatus($apply, $resume_info);

					//自动匹配
                    $single_apply_job_info = $this->arrayFind($apply_job_info, 'job_id', $apply['job_id']);
					if ($status == 9) {
					   $matchs = $this->matchingApplyResume($single_apply_job_info, $resume_info);
					   $list[$i]['matchs'] = $matchs;
					}

					$list[$i]['status'] = $status;
					
					//根据apply_id 获得邀请的简历面试时间
					if ($status == 1) {
						//已邀请的简历 

						$invite_info   = $service_invites->getInviteByApply($apply['apply_id'], true, "invite_id,audition_time,station,invite_type,create_time,audition_address,audition_link_man,audition_link_tel");
						$audition_time = $invite_info['audition_time'];
						if (base_lib_BaseUtils::isDate($audition_time)) {
							$audition_time = date("Y-m-d H:i", strtotime($audition_time));
						}
						$list[$i]['audition_time'] = $audition_time;
						$invite_content = "<p>{$invite_info['create_time']} 发送面试邀请</p><p>面试岗位:{$invite_info['station']}</p><p>面试时间:{$this->_getAuditionTime($invite_info['audition_time'])}</p><p>面试地点:{$invite_info['audition_address']}</p><p>联系人:{$invite_info['audition_link_man']}&nbsp;&nbsp;&nbsp;&nbsp;{$invite_info['audition_link_tel']}</p>";
						$list[$i]['invite_content'] = $invite_content;
					}

					$list[$i]['statusName'] = $this->getStatusName($status);
                    //筛选置顶的投递
                    if(in_array($apply['apply_id'],$top_apply_ids) && !empty($top_apply_ids) && $applystatus==2){
                        array_push($top_list,$list[$i]);
                        unset($list[$i]);
                    }

					//判断简历投递是否是从快米投递同步过来的
					$list[$i]['is_kuaimi'] = false;
					if($apply_resource_list[$apply['apply_id']]['resouce_type'] == $service_common_applyresource->kuaimi){
						$list[$i]['is_kuaimi'] = true;
					}
                    //判断是否活跃
                    $chat_info = $chat_list[$person_id];
                    $list[$i]["is_active"] = $chat_info["count"] >= 3 ? true : false;
                    $_time = date("Y-m-d") . " 00:00:00";
                    $list[$i]['is_job_effect'] = 1;
                    if ($single_apply_job_info['check_state'] > 1
                        || ($single_apply_job_info['status'] == $job_status->use 
                            && $single_apply_job_info['end_time'] < $_time) 
                        || $single_apply_job_info['status'] == $job_status->stop_use 
                        || $single_apply_job_info['status'] == $job_status->deleted
                        || $single_apply_job_info['is_effect'] == '0') {
                        $list[$i]['is_job_effect'] = 0;
                    }
                    
				}
				//分页
				$pager = $this->pageBar($apply_list->totalSize, $page_size, $cur_page, $inPath);
				$this->_aParams['pager'] = $pager;
			}
		}
        if(!empty($top_list)){
            $list = array_merge($top_list,$list);
        }
		/* 获取投递处理提醒 */
		if(!empty($list)){
            $last_invite_list = $service_invites->getLastInviteByApplyids($apply_ids,$company_resources->all_accounts);
            if(!empty($last_invite_list)){
            	$last_invite_list = base_lib_BaseUtils::array_key_assoc($last_invite_list,'apply_id');
			}


            foreach ($list as $key => $value) {
				if ($value['re_status'] == 0
					&& strtotime($value['need_re_time']) >= time()
					&& strtotime($value['need_re_time']) <= strtotime('+1 day')) {

					$list[$key]['promiseSoonStop'] = 1;
				}

                //是否已经发送offer
                $list[ $key ]['is_send_offer'] = $last_invite_list[ $value['apply_id'] ]['offer_send_time'] ? true : false;
                $list[ $key ]['invite_id']     = $last_invite_list[ $value['apply_id'] ]['invite_id'];

			}

            //待回复简历列表显示近7天，浏览量和下载量
            if($applystatus == 2){
                $resume_ids = base_lib_BaseUtils::getProperty($list, 'resume_id');
                $person_ids = base_lib_BaseUtils::getProperty($list, 'person_id');
                
                $service_person_visit                           = new base_service_person_visit();
                $service_company_appraise_linkwayget            = new base_service_company_appraise_linkwayget();
                $service_person_statistics_backfloStatistics    = new base_service_person_statistics_backfloStatistics();
                
                $visit_nums         = $service_person_visit->getVisitNumByResumeIds($resume_ids);
                $get_linkway_nums   = $service_company_appraise_linkwayget->getNumByPersonIds($person_ids);
                $back_floses        = $service_person_statistics_backfloStatistics->isBackFloByPersonIds($person_ids);
                $earliest_resumes   = $service_resume->getResumesByPersonIds($person_ids);
                
                $visit_nums         = base_lib_BaseUtils::array_key_assoc($visit_nums, 'resume_id');
                $get_linkway_nums   = base_lib_BaseUtils::array_key_assoc($get_linkway_nums, 'person_id');
                $back_floses        = base_lib_BaseUtils::array_key_assoc($back_floses, 'person_id');
                $earliest_resumes   = base_lib_BaseUtils::array_key_assoc($earliest_resumes, 'person_id');
                $resume_list        = base_lib_BaseUtils::array_key_assoc($resume_list->items, 'resume_id');

                foreach ($list as $k => $v) {
                    $list[$k]['visit_num'] = isset($visit_nums[$v['resume_id']]['hitNum']) ? $visit_nums[$v['resume_id']]['hitNum'] : 0;
                    $list[$k]['get_linkway_num'] = isset($get_linkway_nums[$v['person_id']]['num']) ? $get_linkway_nums[$v['person_id']]['num'] : 0;
                    $list[$k]['is_new'] = isset($back_floses[$v['person_id']]) || ($resume_list[$v['resume_id']]['create_time'] == $earliest_resumes[$v['person_id']]['create_time'] && $resume_list[$v['resume_id']]['create_time'] >= date('Y-m-d 00:00:00', strtotime('-7 days')));
                }
            }
		}
		$this->_aParams['applylist'] = $list;
         //echo json_encode($list);die();
		$this->_aParams['totalSize'] = $apply_list->totalSize;

	    // 是否发布过职位	
	    $has_issuejob = $service_job->hasIssueJob($this->_userid);
	    $this->_aParams['hasJob'] = $has_issuejob;

	    // 是否收到过申请			    
	    $applystate = $service_apply->getApplyStat($this->_userid);
		$this->_aParams['hasApply'] = intval($applystate['total']) > 0 ? true : false;

		// 筛选以后是否有数据
		$this->_aParams['hasFilterApply'] = $apply_list->totalSize > 0 ? true : false;
		$this->_aParams['jobs'] = "[]";

		// 职位筛选项
		$jobs_json = [];
		array_push($jobs_json, ["id"=>"", "name"=>"全部职位"]);
		foreach ($jobs as $job)
			array_push($jobs_json, ["id"=>$job['job_id'], "name"=>$job['station']]);

		//发布人选项
		$job_people = [];
		array_push($job_people,["id"=>"","name"=>"全部"]);
		foreach($son_account_list->items as $val){
			array_push($job_people,["id"=>$val['account_id'],"name"=>$val['user_name']]);
		}

        $jobs = base_lib_BaseUtils::array_key_assoc($jobs, "job_id");
        $this->_aParams['job_station'] = $job_id && isset($jobs[$job_id]) ? $jobs[$job_id]["station"] : "所有职位";
		$this->_aParams['job_people'] = json_encode($job_people);
		$this->_aParams['jobs'] = json_encode($jobs_json);

		$this->_aParams['child_status']     = $child_status;
		$this->_aParams['show_model']       = $showmode;
		$this->_aParams['invite_time']      = $invite_time;
		$this->_aParams['search_model']     = $searchmode;
		$this->_aParams['job_id']           = $job_id;
		$this->_aParams['apply_time']       = $apply_time;
		$this->_aParams['status']           = $applystatus;
		$this->_aParams['keyword']          = $keyword;
		$this->_aParams['showfilter']       = false;
		$this->_aParams['show_not_use_job'] = $show_not_use_job;




		if (!base_lib_BaseUtils::nullOrEmpty($job_id)
			|| !base_lib_BaseUtils::nullOrEmpty($apply_time)
			|| !base_lib_BaseUtils::nullOrEmpty($applystatus)
			|| !base_lib_BaseUtils::nullOrEmpty($keyword)) {
			$this->_aParams['showfilter'] = true;	
		}

		if (count($show_person_read_applyids) > 0) {
			$this->_aParams['set_show_person_url'] = base_lib_Constant::COMPANY_URL_NO_HTTP
			. "/apply/SetShowPersonRead/applyids-" . implode(',',$show_person_read_applyids);
		}



		//如果是待处理简历,显示简历处理率
		if ($applystatus == 2) {

            $service_company_resume_apply = new base_service_company_resume_apply();
            if(!base_lib_BaseUtils::nullOrEmpty($job_id)){
                $service_replayrate_job = new base_service_replayrate_job();
                $reversion_rate = $service_replayrate_job->getByJobId($job_id);
                $reversion_count = $service_company_resume_apply->getReplyDetail($job_id, array());
                $reversion_rate['reply_rate'] = base_lib_BaseUtils::formatnumber(floatval($reversion_rate['reply_rate']) * 100);
                $reversion_rate['avg_time'] = base_lib_BaseUtils::formatnumber(floatval($reversion_rate['avg_time']));

                $sort_no = $service_replayrate_job->getTopCountByAvgtime($reversion_rate['avg_time']);
                $reversion_rate['avg_time_hours'] =   $reversion_rate['avg_time'];
                if($reversion_rate['avg_time'] > 48){
                    $reversion_rate['avg_time_str'] = ceil($reversion_rate['avg_time'] / 24) . '天';
                }else{
                    $reversion_rate['avg_time_str'] = base_lib_BaseUtils::formatnumber($reversion_rate['avg_time']) . '小时';
                }

            }else{
                $service_replayrate_company = new base_service_replayrate_company();
                //如果选中了子账号，则计算当前选择子账号的职位简历回复率
				if(is_array($account_id)){
					$company_ids = $account_id;
				}else{
					$company_ids[] = $account_id;
				}

                $company_resources_temp = $company_resources->getCompanyServiceSource();
                if($company_resources_temp['resource_type'] == 2){
                    $accountrelate_service = new base_service_replayrate_account();
                    $reversion_rate = $accountrelate_service->getLastAccountRelayrate($account_id);
                }else{
                    $relate_service = new base_service_replayrate_company();
                    $reversion_rate = $relate_service->getLastCompanyRelayrate($this->_userid);
                }
                $reversion_count = $service_company_resume_apply->getReplyDetail(NULL, $company_ids);
                $reversion_rate['avg_time_hours'] =   $reversion_rate['_data']['avg_time'];
                $sort_no = $service_replayrate_company->getTopCountByAvgtime($reversion_rate['avg_time']);
                $reversion_rate['avg_time_str'] = $reversion_rate['avg_time'] . $reversion_rate['day_hour'];
            }
        
            $reversion_rate["total_count"] = $reversion_count["total_count"];
            $reversion_rate["re_count"] = $reversion_count["re_count"];
            $reversion_rate['sort_no'] = (isset($sort_no['count']) && !empty($sort_no['count'])) ? ($sort_no['count'] + 1) : ($reversion_rate['avg_time'] > 0 ? '1' : '～');
            $this->_aParams['reversion_date_range'] = date("Y-m-d",strtotime("-14 day")) . '至' . date("Y-m-d",strtotime("-1 day"));
            $this->_aParams['reversion_rate'] = $reversion_rate;
            
            $this->_aParams['closefiltration_cookie_key'] = md5("closefiltration{$accountid}");
            $this->_aParams['closefiltration_cookie_value'] = base_lib_BaseUtils::getCookie($this->_aParams['closefiltration_cookie_key']);
		}

		$cache_info['visit_time'] = time();

		// 查询是否有收到的简历信息
		$xml = SXML::load('../config/config.xml');
		$this->_aParams['ShowResumeNum'] = $xml->ShowResumeNum;
		$this->_aParams['title']         = "收到的简历 简历管理_{$xml->HuiBoSiteName}";

		//获得各种状态下收到的总条数
		$apply_status_count = $service_apply->getStatusGroupCount($this->_userid, $use_job_ids);
		$this->_aParams['apply_status_count'] = $apply_status_count->items;

		//更新缓存
		$service_cache_refuse_job->setVerifyCode($cache_info,$this->_userid);
        $service_question = new base_service_company_question();

        if($service_question->canAnswer($this->_userid)){
            $this->_aParams['is_question'] = 1;
        }
        
		switch ($applystatus) {
			case 1:
				return $this->render('resume/apply/invite.html', $this->_aParams); 

			case 2:
				$this->_aParams["userid"] = $this->_userid; //var_dump($this->_aParams);
				return $this->render('resume/apply/apply_v2.html', $this->_aParams);
			
			case 3:
				return $this->render('resume/apply/applyrefuse.html', $this->_aParams);
			
			case 9:
				return $this->render('resume/apply/automatic.html', $this->_aParams); 
			
			default:
				return $this->render('resume/apply/apply_v2.html', $this->_aParams); 
		}
	}

	/**
	 * 收到的简历列表
	 * @param  $inPath
	 */
	public function pageGrayIndex($inPath) {
		if(!$this->canDo("apply_resume_init") || !$this->canDo("resume_manage")){
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";
			return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}
		$params           = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));


		// 参数信息:  职位编号，投递时间，状态，姓名/简历编号，显示方式（列表/摘要）
		$job_id           = base_lib_BaseUtils::getStr($params['job_id'], 'int', null);
		$invite_time      = base_lib_BaseUtils::getStr($params['invite_time'], 'int', null);
		$applystatus      = base_lib_BaseUtils::getStr($params['status'], 'int', 2); //若未传状态参数，默认显示未处理的简历  //简历几日回复可传入几个状态：3,5同时传
		$keyword          = base_lib_BaseUtils::getStr($params['keyword'], 'string', '');
		$time             = base_lib_BaseUtils::getStr($params['time'], 'int', null);
		$showmode         = base_lib_BaseUtils::getStr($params['show_model'], 'int', '1'); // 显示方式 0表示列表，1表示摘要
		$searchmode       = base_lib_BaseUtils::getStr($params['search_model'], 'int', '0'); // 查询方式 0表示按筛选条件，1表示按姓名/简历编号
		$cur_page         = base_lib_BaseUtils::getStr($params['page'], 'int', 1);
		$show_not_use_job = base_lib_BaseUtils::getStr($params['show_not_use'], 'int', 1);//是否不显示已停止招聘的职位
		$page_size        = base_lib_BaseUtils::getStr($params['pageSize'], 'int', base_lib_Constant::PAGE_SIZE);
		$child_status     = base_lib_BaseUtils::getStr($params['child_status'], 'int', null);
		$account_id       = base_lib_BaseUtils::getStr($params['account_id'], 'int', "");

		$search       	  = base_lib_BaseUtils::getStr($params['search'], 'int', "");

		$promiseStop = base_lib_BaseUtils::getCookie('showPromiseStop');

		//是否默认选择当前账号
		$is_main = $this->checkAccountResource();
		if($is_main){
			$account_id_temp = "";
		}else{
			$account_id_temp = base_lib_BaseUtils::getCookie('accountid');
		}

		if($search || $promiseStop == 1){
			$account_id_temp = "";
		}

		//var_dump($account_id_temp);
		$son_account_id       = base_lib_BaseUtils::getStr($params['son_account_id'], 'int', $account_id_temp);

		$this->_aParams['account_id'] = $account_id;
		$this->_aParams['son_account_id'] = $son_account_id;

		/*************************简历二次筛选***************************/
		$screening_list = $this->_fixParams($params)[1];
		$path_data = $this->_fixParams($params)[0];
		//简历筛选条件
		$this->_aParams['marriage_id'] = $path_data['marriage_id'] ? $path_data['marriage_id'] : 0;

		$this->_aParams['dutytime_id'] = $path_data['dutytime_id'] ? $path_data['dutytime_id'] : 0;

		$this->_aParams['sex_id'] = $path_data['sex_id'] ? $path_data['sex_id'] : 0;

		$this->_aParams['years_id'] = $path_data['years_id'] ? $path_data['years_id'] : 0;
		$this->_aParams['years_id2'] = $path_data['years_id2'] ? $path_data['years_id2'] : 0;

		//学历搜索
		$this->_aParams['education_id'] = $path_data['education_id'] ? $path_data['education_id'] : 0;
		$this->_aParams['education_id2'] = $path_data['education_id2'] ? $path_data['education_id2'] : 0;


		$this->_aParams['amin'] = $path_data['amin'] ? $path_data['amin'] : 0;
		$this->_aParams['amax'] = $path_data['amax'] ? $path_data['amax'] : 0;

		$this->_aParams['is_show_sceening'] = true;
		$int_dutytime = intval($path_data['dutytime_id']);
		if(!empty($path_data['marriage_id']) || !empty($path_data['amin']) || !empty($path_data['amax']) || !empty($int_dutytime) || !empty($path_data['education_id'])||!empty($path_data['education_id2'])||!empty($path_data['years_id'])||!empty($path_data['years_id2'])||!empty($path_data['sex_id'])){
			$this->_aParams['is_show_sceening'] = false;
		}
		/*************end***********************************************/



		// 公司会员区分

		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
		$member = $company_resources->account_type;
		$this->_aParams['member'] = $member;
		//新加判断，之前用的memberinfo已弃用，发现一直没改
		$this->_aParams['memberinfo'] = $memberinfo = $company_resources->isMember() ? "member" : "not_member";

		$account_id = empty($account_id) || !in_array($account_id, $company_resources->all_accounts) ? $company_resources->all_accounts : $account_id;


		//获取所有账号
		$service_company_account = new base_service_company_account();
		$son_account_list = $service_company_account->getAccountList($company_resources->all_accounts,'account_id,company_id,is_main,user_id,user_name');

		$company_service = new base_service_company_company();
		$accounts = $company_service->getCompanys($company_resources->all_accounts, "company_id,company_shortname,company_name");
		$_accounts = [];

		// 是否为hr代招会员
		$this->_aParams['is_hr'] = $company_resources->account_type == 'hr_main' ? true : false;

		$_datas[] = ["id" => "0", "name" => "所有公司"];
		foreach ($accounts as $key => $account) {
			$account['company_name_display']   = $account['company_shortname'] ? $account['company_shortname'] : $account['company_name'];
			$_accounts[$account['company_id']] = $account;

			$_datas[] = ['id' => $account['company_id'], "name" => $account['company_name_display']];
		}

		$this->_aParams['accounts']      = $_accounts;

		$this->_aParams['accounts_json'] = json_encode($_datas);

		// 职位
		$base_service_company_job_job = new base_service_company_job_job();
		$service_apply = new base_service_company_resume_apply();

		$account_job_ids = '';
		if($son_account_id){
			$account_job_list =$base_service_company_job_job->getJobIdByAccount($company_resources->all_accounts,$son_account_id,'company_id,job_id,account_id');
			$account_job_ids = base_lib_BaseUtils::getProperty($account_job_list->items,'job_id');
		}

        $job_ids = [];
        if(!$son_account_id || !empty($account_job_ids)){
            $hasApplyJobs = $service_apply->getHasApplyJobIdsNew($account_id,$account_job_ids);

            $job_ids = base_lib_BaseUtils::getProperty($hasApplyJobs->items, 'job_id');
            $job_ids = array_unique($job_ids);
        }
        
		$use_job_ids = array();
		$showStopJobApply = base_lib_BaseUtils::getCookie("showStopJobApply"); //判断用户是否包含了 停招招聘的职位
		$this->_aParams['showStopJobApply'] = $showStopJobApply;

		$jobs = $this->_getJobsAndSort($job_ids, $show_not_use_job, $job_id, $use_job_ids, $showStopJobApply);
		if($son_account_id && empty($account_job_ids)){
			$jobs = array();
		}
        
		if (!$son_account_id && !base_lib_BaseUtils::nullOrEmpty($showStopJobApply) && $showStopJobApply == "true") {
			$use_job_ids = null;
		}
        $account_job_ids = array_unique(base_lib_BaseUtils::getProperty($jobs, 'job_id'));
		//学历
		$degree = new base_service_common_degree();
		$degree_lists = $degree->getAll();
		$degree_lists_temp[] = ["id" => "00", "name" => "不限"];
		foreach ($degree_lists as $key => $account) {
			$degree_lists_temp[] = ['id' => $key, "name" => $account];
		}
		$this->_aParams['degree_lists'] = json_encode($degree_lists_temp);

		//到岗时间
		$common_accessiontime = new base_service_common_accessiontime();
		$common_accessiontime_list = $common_accessiontime->getAll();
		$common_accessiontime_list_temp[] = ["id" => "00", "name" => "不限"];
		foreach ($common_accessiontime_list as $key => $account) {
			$common_accessiontime_list_temp[] = ['id' => $key, "name" => $account];
		}
		$this->_aParams['common_accessiontime_list'] = json_encode($common_accessiontime_list_temp);

		//婚姻状态
		$common_marriage = new base_service_common_marriage();
		$common_marriage_list = $common_marriage->getMarriage();
		$common_marriage_list_temp[] = ["id" => "0", "name" => "不限"];
		foreach ($common_marriage_list as $key => $account) {
			$common_marriage_list_temp[] = ['id' => $key, "name" => $account];
		}
		$this->_aParams['common_marriage_list'] = json_encode($common_marriage_list_temp);

		//工作年限
		$workyear_common    = new company_service_workyear();
		$common_workyear_list = $workyear_common->getAll();
		$common_workyear_list_temp[] = ["id" => "0", "name" => "不限"];
		foreach ($common_workyear_list as $key => $account) {
			$common_workyear_list_temp[] = ['id' => $key, "name" => $account];
		}
		$this->_aParams['common_workyear_list'] = json_encode($common_workyear_list_temp);

		//性别
		$common_sex = new base_service_common_sex();
		$common_sex_list = $common_sex->getSex();
		$common_sex_list_temp[] = ["id" => "0", "name" => "不限"];
		foreach ($common_sex_list as $key => $account) {
			$common_sex_list_temp[] = ['id' => $key, "name" => $account];
		}
		$this->_aParams['common_sex_list'] = json_encode($common_sex_list_temp);



		//----START 新增停止招聘职位和投递数量. 2016-6-8 ------
		//-》获取某个企业停止招聘职位的投递信息总信息
		$this->_aParams['count_job_stop_num'] = 0;
		$this->_aParams['count_job_stop_apply_num'] = 0;
		$service_job = new base_service_company_job_job();
		$service_cache_refuse_job = new company_service_applyrefusecache();
		$cache_info = array();
		if($applystatus == 2){
			//获取公司下所有停止招聘的职位id
			$job_status = new base_service_common_jobstatus();
			$job_no_use = $service_job->getJobList($account_id, '', $job_status->stop_use, 'job_id,end_time',0,0,null,$son_account_id);
			if(!empty($job_no_use))
			{
				$end_date = date("Y-m-d 00:00:00",  strtotime("-90 days"));
				$refuse_job_ids = array();
				foreach($job_no_use as $value){
					if($value["end_time"] > $end_date){
						$refuse_job_ids[] = $value["job_id"];
					}
				}
				$cache_info['refuse_job_ids'] = $refuse_job_ids;
				$job_stop_apply_detail = $service_apply->getWaitDealNumByJobIds($cache_info['refuse_job_ids'])->items;

				foreach($job_stop_apply_detail as $k=>$v){
					$this->_aParams['count_job_stop_apply_num'] += $v['num'];
				}
				$this->_aParams['job_stop_apply_detail'] = $job_stop_apply_detail;
				$this->_aParams['count_job_stop_num'] = @(int)count($job_stop_apply_detail);
			}

		}
		//----END 新增停止招聘职位和投递数量. 2016-6-8 ------



		if ($invite_time === 0) {
			$invite_time = '+0'; // XXX：js的下拉组件默认认为选项为0的项为默认项
		}


		/*========================== 简历几日回复 start =============================*/
		if ($applystatus == "3" || $applystatus == "2") {


			base_lib_BaseUtils::ssetcookie(array('showPromiseStop' => ''), -1); //删除cookie
			$this->_aParams['showPromise'] = $promiseStop;

			if (!empty($promiseStop) && $promiseStop == '1') {
				$searchstatus = $applystatus . ",5";
			} else {
				$searchstatus = is_numeric($applystatus) ? $applystatus : 2;
			}

			$this->_aParams['auto_filter_count'] = $service_apply->getPromiseAutoReplyCount(
				$account_id,
				$use_job_ids
			);
		} else {
			$searchstatus = is_numeric($applystatus) ? $applystatus : 2;
		}

		/*========================== 简历几日回复 end =============================*/
		$job_id_temp = '';
		if(!base_lib_BaseUtils::nullOrEmpty($son_account_id) && base_lib_BaseUtils::nullOrEmpty($job_id)){
			$job_id_temp = $account_job_ids;
		}else{
			$job_id_temp = $job_id;
		}

		$contain_cancel_apply = true;
		if ($searchmode == 0) {
			$apply_list = $service_apply->getApplyByCompanyV2($page_size, $cur_page, $account_id, 'info_job_apply.apply_id,'
				. 'info_job_apply.person_id,info_job_apply.resume_id,info_job_apply.station,info_job_apply.create_time,info_job_apply.company_id,'
				. 'info_job_apply.is_cancelled,info_job_apply.has_read,info_job_apply.re_status,info_job_apply.job_id,info_job_apply.remind_time,info_job_apply.need_re_time,info_job_apply.show_person_read',
				$job_id_temp, $time, $searchstatus, $child_status, $invite_time, $use_job_ids,$screening_list,$contain_cancel_apply);

			if($applystatus == 2)
			{
				//获取当前所有简历ID
				$all_apply_ids = $service_apply->getApplyByCompanyV2('', '', $account_id, 'info_job_apply.apply_id',
					$job_id_temp, $time, $searchstatus, $child_status, $invite_time, $use_job_ids,$screening_list,$contain_cancel_apply);

				if(!empty($all_apply_ids->items)){
					$cache_info['all_apply_ids'] =  base_lib_BaseUtils::getProperty($all_apply_ids->items,'apply_id');
				}
			}
		} else {
			$job_id        = null;
			$apply_time    = null;
			$child_status  = null;
			$apply_list = $service_apply->getApplyByNameOrResumeID($page_size, $cur_page, $account_id, 'apply_id,info_job_apply.company_id,'
				. 'info_job_apply.person_id,info_job_apply.resume_id,info_job_apply.station,info_job_apply.create_time,info_job_apply.remind_time,info_job_apply.need_re_time,'
				. 'is_cancelled,has_read,re_status,info_job_apply.job_id,show_person_read', $keyword, $searchstatus, $use_job_ids,$contain_cancel_apply);
			if($applystatus == 2)
			{
				//获取当前所有简历ID
				$all_apply_ids = $service_apply->getApplyByNameOrResumeID('', '', $account_id, 'apply_id', $keyword, $searchstatus, $use_job_ids,$contain_cancel_apply);
				if(!empty($all_apply_ids->items)){
					$cache_info['all_apply_ids'] =  base_lib_BaseUtils::getProperty($all_apply_ids->items,'apply_id');
				}
			}

		}

		$show_person_read_applyids = array();
		if ($apply_list !== false) {
			if (!empty($apply_list->items)) {
				$list = $apply_list->items;
				$service_person        = new base_service_person_person();
				$service_resume        = new base_service_person_resume_resume();
				$service_resume_work   = new base_service_person_resume_work();
				$service_resume_remark = new base_service_company_resume_resumeremark();
				$service_download      = new base_service_company_resume_download();
				$service_company       = new base_service_company_company();
				$service_invites 	   = new base_service_company_resume_jobinvite();

				//筛选即将过期的承诺职位
				// 求职者
				$person_ids = implode(',',base_lib_BaseUtils::getProperty($list, 'person_id'));

				$person_list = $service_person->GetPersonListByIDs($person_ids, 'person_id,user_name,name_open,photo_open,sex,birthday2,'
					. 'cur_area_id,start_work,photo,small_photo,mobile_phone');

				// 简历
				$resume_ids     = $this->getPropertys($list, 'resume_id');
				$apply_job_ids  = $this->getPropertys($list, 'job_id');

				// 公司基本信息
				$company = $service_company->getCompany($this->_userid, 1, "end_time");

				$service_qloudmsg = new base_service_app_qcloudmsg();
				$chat_list        = $service_qloudmsg->getReplyTimes($person_ids);
				$chat_list        = base_lib_BaseUtils::array_key_assoc($chat_list, "person_id");

				// 投递信息
				$apply_job_info = $service_job->getJobs($apply_job_ids, 'station,company_id,reply_content,allow_email,other_email,self_linkway,'
					. 'work_year_id,allow_graduate,degree_id,sex,age_lower,age_upper,is_automatic,check_state,end_time,status,is_effect');

				// 简历信息
				$resume_list = $service_resume->getResumes($resume_ids, 'person_id,resume_id,resume_name,degree_id,current_station,'
					. 'current_station_start_time,current_station_end_time,is_effect,mobile_phone,create_time');

				// 下载联系方式信息
				$down_list   = $service_download->queryDownloadList($resume_ids, $this->_userid, "resume_id,company_id");
				$down_list   = base_lib_BaseUtils::array_key_assoc((array)$down_list->items, "resume_id");

				// 备注列表
				$remark_data = $service_resume_remark->getLastResumeRemarks($this->_userid, $resume_ids, 'remark_id,resume_id,company_id,remark,update_time');

				// 摘要
				if ($showmode == 1) {
					//毕业院校
					$service_edu = new base_service_person_resume_edu();
					$edu_data = $service_edu->getResumeEdus(implode(',',$resume_ids),'resume_id,school,major_desc,degree');
					//工作经验
					$work_datas = $service_resume_work->getResumeWorks(implode(',',$resume_ids), 'work_id,resume_id,start_time,end_time,station,company_name,work_content');
					foreach ($work_datas->items as $workskey => $worksvalue) {
						$workslist[$worksvalue["resume_id"]][$workskey]['start_time']   = date('Y/m', strtotime($worksvalue['start_time']));
						$workslist[$worksvalue["resume_id"]][$workskey]['end_time']     = empty($worksvalue['end_time']) ? "至今" : date('Y/m', strtotime($worksvalue['end_time']));
						$workslist[$worksvalue["resume_id"]][$workskey]['station']      = $worksvalue['station'];
						$workslist[$worksvalue["resume_id"]][$workskey]['company_name'] = $worksvalue['company_name'];
						$workslist[$worksvalue["resume_id"]][$workskey]['work_content'] = base_lib_BaseUtils::cutstr($worksvalue['work_content'], 180, 'utf-8', '','...');
					}
				}

				//用户联系方式获取数组
				$privileges = $service_person->checkMobilesPrivilege($resume_ids, $this->_userid);

				//生成apply_ids数组用于更新求职者查阅状态
				$apply_ids           = base_lib_BaseUtils::getProperty($list, 'apply_id');
				$refuse_result_array = array();
				$refuse_apply_ids    = array();
				if ($applystatus == 3) {
					//如果是已拒绝的简历，那么判断是手动拒绝还是自动拒绝
					$service_autorefuse = new base_service_company_resume_applyautorefuse();
					$refuse_result_array = $service_autorefuse->getRefuseList($apply_ids, "apply_id");
					$refuse_apply_ids    = count($refuse_result_array) > 0 ? $this->getPropertys($refuse_result_array, 'apply_id') : array();
				}

				$service_company = new base_service_company_company();
				$site_type       = $service_company->getCompany($this->_userid,1,'site_type')['site_type'];
				//区县企业投递优先查看
				$top_apply_ids = array();
				if($site_type == 2){
					$service_applytop = new base_service_mobile_applytop();
					$top_apply        = $service_applytop->getTopByCompany($this->_userid,'apply_id');
					$top_apply_ids    = base_lib_BaseUtils::getProperty($top_apply,'apply_id');
					//更新优先查看的投递记录
					$service_applytop->updateTop($top_apply_ids,['top_status'=>2]);
				}
				$len = count($list);
				$top_list  = array();
				$apply_ids = base_lib_BaseUtils::getPropertys($list,"apply_id");

				$service_company_resume_applyresource = new base_service_company_resume_applyresource();
				$service_common_applyresource = new base_service_common_applyresource();
				$job_status = new base_service_common_jobstatus();

				$apply_resource_list = $service_company_resume_applyresource->getApplyListByIds($apply_ids);
				$apply_resource_list = base_lib_BaseUtils::array_key_assoc($apply_resource_list,"apply_id");
				//获取是否是视频招聘的投递简历
				$service_schoolenet_relate = new base_service_schoolnet_shuangxuanpersonapplyrelate();
				$shuangxuanpersonapplyrelate_list = $service_schoolenet_relate->getApplies($apply_ids,$this->_userid,'id,apply_id');

				//求职者一周内是否登录过app
				$sercie_loginlog= new base_service_person_loginlog();
				$login_status = $sercie_loginlog->getLoginAppData($person_ids,14);
				if(!empty($login_status))
					$login_status = base_lib_BaseUtils::array_key_assoc($login_status,'person_id');
				//$service_chat = new company_service_chat(0,0);
				$service_wangyiaction = new base_service_app_wangyiaction();
				//判断这些person_ids对应的网易云账户是否在线
				$wy_person_status_arr = $service_wangyiaction->checkPersonsIsOnline($person_ids);
				for ($i = 0; $i < $len; $i++) {
					$apply       = $list[$i];
					$resume_info = $this->arrayFind($resume_list->items, 'resume_id', $apply['resume_id']);
					$resume_id   = $resume_info['resume_id'];
					$person_id   = $apply['person_id'];

					if($apply['show_person_read'] != '1'){
						array_push($show_person_read_applyids,$apply['apply_id']);
					}

					$list[$i]['is_auto_refuse'] = false;
					if ($applystatus == 3 || !base_lib_BaseUtils::nullOrEmpty($refuse_apply_ids)) {
						//判断是否是拒绝的简历
						if (in_array($apply['apply_id'], $refuse_apply_ids)) {
							$list[$i]['is_auto_refuse'] = true;//是自动拒绝
						}
					}
					//获取视频招聘关联
					if(isset($shuangxuanpersonapplyrelate_list[$apply['apply_id']])){
						$list[$i]['is_shuangxuan_relate'] = 1;
					}else{
						$list[$i]['is_shuangxuan_relate'] = 0;
					}

					$person_info        = $this->arrayFind($person_list->items, 'person_id', $person_id);
					$resume_work_info   = $this->arrayFind($workslist, 'resume_id', $resume_id);
					$resume_remark_info = $this->arrayFind($remark_data->items, 'resume_id', $resume_id);

					// 非会员是否获取联系方式
					$list[$i]['need_contact'] = !$privileges[$resume_id];

					// 姓名
					$list[$i]['user_name'] = empty($person_info['user_name']) ? "&nbsp;" : $person_info['user_name'];

					if ($person_info['name_open'] == 0) {
						$sex_name = $person_info['sex'] == 1 ? '先生' : '女士';
						$list[$i]['user_name'] = mb_substr($person_info['user_name'], 0, 1, 'utf-8') . $sex_name;
					}

					$list[$i]['full_user_name'] = base_lib_BaseUtils::htmlEncode($person_info['user_name']);
					$list[$i]['remark']         = base_lib_BaseUtils::nullOrEmpty($resume_remark_info['remark'])
						? false :  base_lib_BaseUtils::cutstr($resume_remark_info['remark'], 8, 'utf-8', '', "...")
						. '&nbsp' . date('Y-m-d', strtotime($resume_remark_info['update_time']));

					//已邀请的简历添加电话号码显示
					if (!$list[$i]['need_contact'] && ($memberinfo != 'member' || $applystatus == 1)) {
						if($this->canDo("see_resume_mobile")){
							$list[$i]['mobile_phone'] = $person_info['mobile_phone'];
						}else{
							$list[$i]['mobile_phone'] = preg_replace('#(\d{3})[a-z0-9-]{2,10}(\d{3})#', '${1}****${2}', $person_info['mobile_phone']);
						}
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

					$list[$i]['photo']       = $person_info['photo'];
					$list[$i]['small_photo'] = $person_info['photo'];//改版后头像用原始头像
					$list[$i]['sex']         = $this->getSex($person_info['sex']);
					$list[$i]['age']         = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']) . '岁';
					$list[$i]['degree']      = $this->getDegree($resume_info['degree_id']);
					$list[$i]['cur_area']    = $this->getArea($person_info['cur_area_id']);

					// 是否为代招
					$list[$i]['generation_binding'] = $list[$i]['company_id'] == $this->_userid ? false : true;

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

					$list[$i]['start_work'] = $basic_start_work_year;
					if (base_lib_BaseUtils::nullOrEmpty($list[$i]['start_work'])) {
						$list[$i]['start_work'] = "应届毕业生";
					}

					//聊一聊状态 ，提示等级 1：(未登录)提示 2：（未登录）提示 + 提示消耗 3：提示消耗
					$chat_params['resume_id'] = $resume_id;
					$chat_params['person_id'] = $person_id;
					$chat_params['company_id'] = $this->_userid;
					$list[$i]['chat_status'] = !empty($login_status[$person_id]) ? true : false;
					//$list[$i]['chat_status'] = $service_chat->getChatNoticeStatus($this->_userid,base_lib_BaseUtils::getCookie('accountid'),null,$chat_params,$person_id);
					//$list[$i]['chat_status'] = $service_wangyiaction->checkPersonIsOnline($person_id);


					// 投递时间
					$list[$i]['apply_time'] = base_lib_TimeUtil::to_friend_time($list[$i]['create_time']);

					//最近工作经验
					if ($resume_info['current_station'] == '') {
						$list[$i]['work'] = '无';
					} else {
						if (base_lib_BaseUtils::nullOrEmpty($resume_info['current_station_start_time'])) {
							$list[$i]['work'] = $resume_info['current_station'];
						} else {
							$list[$i]['work'] = $resume_info['current_station'] . '('
								. base_lib_TimeUtil::date_diff_year3($resume_info['current_station_start_time'], $resume_info['current_station_end_time'])
								. ')';
						}
					}

					//摘要
					if ($showmode == 1) {
						$edu_info = $this->arrayFind($edu_data->items, 'resume_id', $resume_id);
						$list[$i]['school']        = $edu_info['school'];
						$list[$i]['major_desc']    = $edu_info['major_desc'];
						$list[$i]['school_degree'] = $this->getDegree($edu_info['degree']) ;
						//var_dump($workslist["$resume_id"]);
						$count = count($workslist["$resume_id"]);
						if ($count > 0) {
							$list[$i]['worklist'] = array_slice($workslist["$resume_id"], 0, ($count >= 3 ? 3 : $count));
						} else {
							$list[$i]['worklist'] = array();
						}
					}

					// 获取申请状态
					$status = $this->getStatus($apply, $resume_info);

					//自动匹配
					$single_apply_job_info = $this->arrayFind($apply_job_info, 'job_id', $apply['job_id']);
					if ($status == 9) {
						$matchs = $this->matchingApplyResume($single_apply_job_info, $resume_info);
						$list[$i]['matchs'] = $matchs;
					}

					$list[$i]['status'] = $status;

					//根据apply_id 获得邀请的简历面试时间
					if ($status == 1) {
						//已邀请的简历

						$invite_info   = $service_invites->getInviteByApply($apply['apply_id'], true, "invite_id,audition_time,station,invite_type,create_time,audition_address,audition_link_man,audition_link_tel");
						$audition_time = $invite_info['audition_time'];
						if (base_lib_BaseUtils::isDate($audition_time)) {
							$audition_time = date("Y-m-d H:i", strtotime($audition_time));
						}
						$list[$i]['audition_time'] = $audition_time;
						$invite_content = "<p>{$invite_info['create_time']} 发送面试邀请</p><p>面试岗位:{$invite_info['station']}</p><p>面试时间:{$this->_getAuditionTime($invite_info['audition_time'])}</p><p>面试地点:{$invite_info['audition_address']}</p><p>联系人:{$invite_info['audition_link_man']}&nbsp;&nbsp;&nbsp;&nbsp;{$invite_info['audition_link_tel']}</p>";
						$list[$i]['invite_content'] = $invite_content;
					}

					$list[$i]['statusName'] = $this->getStatusName($status);
					//筛选置顶的投递
					if(in_array($apply['apply_id'],$top_apply_ids) && !empty($top_apply_ids) && $applystatus==2){
						array_push($top_list,$list[$i]);
						unset($list[$i]);
					}

					//判断简历投递是否是从快米投递同步过来的
					$list[$i]['is_kuaimi'] = false;
					if($apply_resource_list[$apply['apply_id']]['resouce_type'] == $service_common_applyresource->kuaimi){
						$list[$i]['is_kuaimi'] = true;
					}
					//判断是否活跃
					$chat_info = $chat_list[$person_id];
					$list[$i]["is_active"] = $chat_info["count"] >= 3 ? true : false;
					$_time = date("Y-m-d") . " 00:00:00";
					$list[$i]['is_job_effect'] = 1;
					if ($single_apply_job_info['check_state'] > 1
						|| ($single_apply_job_info['status'] == $job_status->use
							&& $single_apply_job_info['end_time'] < $_time)
						|| $single_apply_job_info['status'] == $job_status->stop_use
						|| $single_apply_job_info['status'] == $job_status->deleted
						|| $single_apply_job_info['is_effect'] == '0') {
						$list[$i]['is_job_effect'] = 0;
					}

				}
				//分页
				$pager = $this->pageBar($apply_list->totalSize, $page_size, $cur_page, $inPath);
				$this->_aParams['pager'] = $pager;
			}
		}
		if(!empty($top_list)){
			$list = array_merge($top_list,$list);
		}
		/* 获取投递处理提醒 */
		if(!empty($list)){
			$last_invite_list = $service_invites->getLastInviteByApplyids($apply_ids,$company_resources->all_accounts);
			if(!empty($last_invite_list)){
				$last_invite_list = base_lib_BaseUtils::array_key_assoc($last_invite_list,'apply_id');
			}


			foreach ($list as $key => $value) {
				if ($value['re_status'] == 0
					&& strtotime($value['need_re_time']) >= time()
					&& strtotime($value['need_re_time']) <= strtotime('+1 day')) {

					$list[$key]['promiseSoonStop'] = 1;
				}

				//是否已经发送offer
				$list[ $key ]['is_send_offer'] = $last_invite_list[ $value['apply_id'] ]['offer_send_time'] ? true : false;
				$list[ $key ]['invite_id']     = $last_invite_list[ $value['apply_id'] ]['invite_id'];

			}

			//待回复简历列表显示近7天，浏览量和下载量
			if($applystatus == 2){
				$resume_ids = base_lib_BaseUtils::getProperty($list, 'resume_id');
				$person_ids = base_lib_BaseUtils::getProperty($list, 'person_id');

				$service_person_visit                           = new base_service_person_visit();
				$service_company_appraise_linkwayget            = new base_service_company_appraise_linkwayget();
				$service_person_statistics_backfloStatistics    = new base_service_person_statistics_backfloStatistics();

				$visit_nums         = $service_person_visit->getVisitNumByResumeIds($resume_ids);
				$get_linkway_nums   = $service_company_appraise_linkwayget->getNumByPersonIds($person_ids);
				$back_floses        = $service_person_statistics_backfloStatistics->isBackFloByPersonIds($person_ids);
				$earliest_resumes   = $service_resume->getResumesByPersonIds($person_ids);

				$visit_nums         = base_lib_BaseUtils::array_key_assoc($visit_nums, 'resume_id');
				$get_linkway_nums   = base_lib_BaseUtils::array_key_assoc($get_linkway_nums, 'person_id');
				$back_floses        = base_lib_BaseUtils::array_key_assoc($back_floses, 'person_id');
				$earliest_resumes   = base_lib_BaseUtils::array_key_assoc($earliest_resumes, 'person_id');
				$resume_list        = base_lib_BaseUtils::array_key_assoc($resume_list->items, 'resume_id');

				foreach ($list as $k => $v) {
					$list[$k]['visit_num'] = isset($visit_nums[$v['resume_id']]['hitNum']) ? $visit_nums[$v['resume_id']]['hitNum'] : 0;
					$list[$k]['get_linkway_num'] = isset($get_linkway_nums[$v['person_id']]['num']) ? $get_linkway_nums[$v['person_id']]['num'] : 0;
					$list[$k]['is_new'] = isset($back_floses[$v['person_id']]) || ($resume_list[$v['resume_id']]['create_time'] == $earliest_resumes[$v['person_id']]['create_time'] && $resume_list[$v['resume_id']]['create_time'] >= date('Y-m-d 00:00:00', strtotime('-7 days')));
				}
			}
		}

		$this->_aParams['applylist'] = $list;
		//echo json_encode($list);die();
		$this->_aParams['totalSize'] = $apply_list->totalSize;

		// 是否发布过职位
		$has_issuejob = $service_job->hasIssueJob($this->_userid);
		$this->_aParams['hasJob'] = $has_issuejob;

		// 是否收到过申请
		$applystate = $service_apply->getApplyStat($this->_userid);
		$this->_aParams['hasApply'] = intval($applystate['total']) > 0 ? true : false;

		// 筛选以后是否有数据
		$this->_aParams['hasFilterApply'] = $apply_list->totalSize > 0 ? true : false;
		$this->_aParams['jobs'] = "[]";

		// 职位筛选项
		$jobs_json = [];
		array_push($jobs_json, ["id"=>"", "name"=>"全部职位"]);
		foreach ($jobs as $job)
			array_push($jobs_json, ["id"=>$job['job_id'], "name"=>$job['station']]);

		//发布人选项
		$job_people = [];
		array_push($job_people,["id"=>"","name"=>"全部"]);
		foreach($son_account_list->items as $val){
			array_push($job_people,["id"=>$val['account_id'],"name"=>$val['user_name']]);
		}

		$jobs = base_lib_BaseUtils::array_key_assoc($jobs, "job_id");
		$this->_aParams['job_station'] = $job_id && isset($jobs[$job_id]) ? $jobs[$job_id]["station"] : "所有职位";
		$this->_aParams['job_people'] = json_encode($job_people);
		$this->_aParams['jobs'] = json_encode($jobs_json);

		$this->_aParams['child_status']     = $child_status;
		$this->_aParams['show_model']       = $showmode;
		$this->_aParams['invite_time']      = $invite_time;
		$this->_aParams['search_model']     = $searchmode;
		$this->_aParams['job_id']           = $job_id;
		$this->_aParams['apply_time']       = $apply_time;
		$this->_aParams['status']           = $applystatus;
		$this->_aParams['keyword']          = $keyword;
		$this->_aParams['showfilter']       = false;
		$this->_aParams['show_not_use_job'] = $show_not_use_job;




		if (!base_lib_BaseUtils::nullOrEmpty($job_id)
			|| !base_lib_BaseUtils::nullOrEmpty($apply_time)
			|| !base_lib_BaseUtils::nullOrEmpty($applystatus)
			|| !base_lib_BaseUtils::nullOrEmpty($keyword)) {
			$this->_aParams['showfilter'] = true;
		}

		if (count($show_person_read_applyids) > 0) {
			$this->_aParams['set_show_person_url'] = base_lib_Constant::COMPANY_URL_NO_HTTP
				. "/apply/SetShowPersonRead/applyids-" . implode(',',$show_person_read_applyids);
		}



		//如果是待处理简历,显示简历处理率
		if ($applystatus == 2) {

			$service_company_resume_apply = new base_service_company_resume_apply();
			if(!base_lib_BaseUtils::nullOrEmpty($job_id)){
				$service_replayrate_job = new base_service_replayrate_job();
				$reversion_rate = $service_replayrate_job->getByJobId($job_id);
				$reversion_count = $service_company_resume_apply->getReplyDetail($job_id, array());
				$reversion_rate['reply_rate'] = base_lib_BaseUtils::formatnumber(floatval($reversion_rate['reply_rate']) * 100);
				$reversion_rate['avg_time'] = base_lib_BaseUtils::formatnumber(floatval($reversion_rate['avg_time']));

				$sort_no = $service_replayrate_job->getTopCountByAvgtime($reversion_rate['avg_time']);
				$reversion_rate['avg_time_hours'] =   $reversion_rate['avg_time'];
				if($reversion_rate['avg_time'] > 48){
					$reversion_rate['avg_time_str'] = ceil($reversion_rate['avg_time'] / 24) . '天';
				}else{
					$reversion_rate['avg_time_str'] = base_lib_BaseUtils::formatnumber($reversion_rate['avg_time']) . '小时';
				}

			}else{
				$service_replayrate_company = new base_service_replayrate_company();
				//如果选中了子账号，则计算当前选择子账号的职位简历回复率
				if(is_array($account_id)){
					$company_ids = $account_id;
				}else{
					$company_ids[] = $account_id;
				}

				$company_resources_temp = $company_resources->getCompanyServiceSource();
				if($company_resources_temp['resource_type'] == 2){
					$accountrelate_service = new base_service_replayrate_account();
					$reversion_rate = $accountrelate_service->getLastAccountRelayrate($account_id);
				}else{
					$relate_service = new base_service_replayrate_company();
					$reversion_rate = $relate_service->getLastCompanyRelayrate($this->_userid);
				}
				$reversion_count = $service_company_resume_apply->getReplyDetail(NULL, $company_ids);
				$reversion_rate['avg_time_hours'] =   $reversion_rate['_data']['avg_time'];
				$sort_no = $service_replayrate_company->getTopCountByAvgtime($reversion_rate['avg_time']);
				$reversion_rate['avg_time_str'] = $reversion_rate['avg_time'] . $reversion_rate['day_hour'];
			}

			$reversion_rate["total_count"] = $reversion_count["total_count"];
			$reversion_rate["re_count"] = $reversion_count["re_count"];
			$reversion_rate['sort_no'] = (isset($sort_no['count']) && !empty($sort_no['count'])) ? ($sort_no['count'] + 1) : ($reversion_rate['avg_time'] > 0 ? '1' : '～');
			$this->_aParams['reversion_date_range'] = date("Y-m-d",strtotime("-14 day")) . '至' . date("Y-m-d",strtotime("-1 day"));
			$this->_aParams['reversion_rate'] = $reversion_rate;

			$this->_aParams['closefiltration_cookie_key'] = md5("closefiltration{$accountid}");
			$this->_aParams['closefiltration_cookie_value'] = base_lib_BaseUtils::getCookie($this->_aParams['closefiltration_cookie_key']);
		}

		$cache_info['visit_time'] = time();

		// 查询是否有收到的简历信息
		$xml = SXML::load('../config/config.xml');
		$this->_aParams['ShowResumeNum'] = $xml->ShowResumeNum;
		$this->_aParams['title']         = "收到的简历 简历管理_{$xml->HuiBoSiteName}";

		//获得各种状态下收到的总条数
		$apply_status_count = $service_apply->getStatusGroupCount($this->_userid, $use_job_ids);
		$this->_aParams['apply_status_count'] = $apply_status_count->items;

		//更新缓存
		$service_cache_refuse_job->setVerifyCode($cache_info,$this->_userid);
		$service_question = new base_service_company_question();

		if($service_question->canAnswer($this->_userid)){
			$this->_aParams['is_question'] = 1;
		}


		switch ($applystatus) {
			case 1:
				return $this->render('resume/apply/invite.html', $this->_aParams);

			case 2:
				$this->_aParams["userid"] = $this->_userid; //var_dump($this->_aParams);
				return $this->render('resume/apply/apply_v2_gray.html', $this->_aParams);

			case 3:
				return $this->render('resume/apply/applyrefuse_gray.html', $this->_aParams);

			case 9:
				return $this->render('resume/apply/automatic_gray.html', $this->_aParams);

			default:
				return $this->render('resume/apply/apply_v2_gray.html', $this->_aParams);
		}

	}


	// 获取面试时间
	private function _getAuditionTime($invite_audition_time) {
		$audition_time = base_lib_BaseUtils::getStr($invite_audition_time, 'datetime', null);
		if (base_lib_BaseUtils::nullOrEmpty($audition_time)) {
			return $invite_audition_time;
		}

		$auditiondate = date('Y年m月d日', strtotime($audition_time));
		$noon = date('H', strtotime($audition_time)) <= 12 ? '上午' : '下午';

		$auditiontime = $noon . date('H:i', strtotime($audition_time));
		$week = $this->_week(base_lib_TimeUtil::date_of_week($audition_time));
		$time = $auditiondate . '（' . $week . '）' . $auditiontime;

		return $time;
	}

	/**
	 * 返回星期
	 * @param $number
	 */
	private function _week($number) {
		switch ($number) {
			case 1:
				$week = '周一';
				break;
			case 2:
				$week = '周二';
				break;
			case 3:
				$week = '周三';
				break;
			case 4:
				$week = '周四';
				break;
			case 5:
				$week = '周五';
				break;
			case 6:
				$week = '周六';
				break;
			default:
				$week = '周日';
				break;
		}

		return $week;
	}

	/**
	 * 获取简历搜索所有参数
	 * @param  array $path_data 所有链接参数
	 * @return array       solr请求参数
	 */
	private function _fixParams($path_data) {
		/* age param */
		$postvar['age_lower']    = base_lib_BaseUtils::getStr($path_data['amin'], 'int', '');
		$postvar['age_upper']    = base_lib_BaseUtils::getStr($path_data['amax'], 'int', '');
		if ($postvar['age_upper'] < $postvar['age_lower'] && $postvar['age_lower'] && $postvar['age_upper']) {
			list($postvar['age_lower'], $postvar['age_upper']) = array($postvar['age_upper'], $postvar['age_lower']);
			list($path_data['amin'], $path_data['amax']) = array($path_data['amax'], $path_data['amin']);
		}

		/* workyear param */
		$postvar['workyear_min'] = base_lib_BaseUtils::getStr($path_data['years_id'], 'int', 0);
		$postvar['workyear_max'] = base_lib_BaseUtils::getStr($path_data['years_id2'], 'int', 0);
		if ($postvar['workyear_min'] != 99 && $postvar['workyear_max'] != 99) {
			if ($postvar['workyear_min'] > $postvar['workyear_max'] && $postvar['workyear_min'] && $postvar['workyear_max']) {
				list($postvar['workyear_min'], $postvar['workyear_max']) = array($postvar['workyear_max'], $postvar['workyear_min']);
				list($path_data['years_id'], $path_data['years_id2']) = array($path_data['years_id2'], $path_data['years_id']);
			}
		} else if ($postvar['workyear_max'] == 99 && $postvar['workyear_min']) {
			list($postvar['workyear_min'], $postvar['workyear_max']) = array($postvar['workyear_max'], $postvar['workyear_min']);
			list($path_data['years_id'], $path_data['years_id2']) = array($path_data['years_id2'], $path_data['years_id']);
		}

		/* degree param */
		$degree_lower            = base_lib_BaseUtils::getStr($path_data['education_id'], 'string', '');
		$degree_upper            = base_lib_BaseUtils::getStr($path_data['education_id2'], 'string', '');
		$degree_common           = new base_service_common_degree();
		$degrees_all             = array_keys($degree_common->getAll());

		if ($degree_lower > $degree_upper && $degree_upper && $degree_lower && $degree_upper !='00') {
			list($degree_upper, $degree_lower) = array($degree_lower, $degree_upper);
			list($path_data['education_id'], $path_data['education_id2']) = array($path_data['education_id2'], $path_data['education_id']);
		}

		$lower = array_search($degree_lower, $degrees_all);
		$upper = array_search($degree_upper, $degrees_all);

		if ($lower !== false && $upper !== false) {
			$degrees = array_slice($degrees_all, $lower, $upper-$lower+1);
		} else if ($lower !== false) {
			$degrees = array_slice($degrees_all, $lower);
		} else if ($upper !== false) {
			$degrees = array_slice($degrees_all, 0, $upper+1);
		}

		if (!empty($degrees))
			$postvar['degree_ids'] = implode(",", $degrees);

		/* build accession_time param */
		$postvar['accession_time'] = base_lib_BaseUtils::getStr($path_data['dutytime_id'], 'string', 0);
        $postvar['accession_time'] = $postvar['accession_time'] == "undefined"?0:$postvar['accession_time'];
		/* sex param */
		$postvar['sex'] = base_lib_BaseUtils::getStr($path_data['sex_id'], 'int', 0);


		/* build marriage param */
		$postvar['marriage'] = base_lib_BaseUtils::getStr($path_data['marriage_id'],"int",0);
        $path_data['amax'] = base_lib_BaseUtils::getStr($path_data['amax'], 'int', '');
        $path_data['amin'] = base_lib_BaseUtils::getStr($path_data['amin'], 'int', '');
		return array($path_data,$postvar);
	}


	private function __returnAjax($msg,$status,$code)
	{
		return json_encode(array('msg'=>$msg,'status'=>$status,'code'=>$code));
	}


	//停招职位相关的一键拒绝
	public function pageRefuseNoUseJob($inPath)
	{
        if(!$this->canDo("refuse_resume")){
            echo $this->__returnAjax('无权限访问，没有开通相应权限',false,2);return;
        }
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$service_cache_refuse_job = new company_service_applyrefusecache();
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'],'int','');
		$txtContent = base_lib_BaseUtils::getStr($path_data['txtContent'],'string','');
		//从缓存拿
		$res = $service_cache_refuse_job->getVerifyCode($this->_userid);

		if(empty($res['refuse_job_ids'])){
			echo $this->__returnAjax('请刷新后再尝试',false,2);die;
		}
		if(empty($job_id)){
			$job_id = $res['refuse_job_ids'];
		}else{
			if(!in_array($job_id,$res['refuse_job_ids'])){
				exit($this->__returnAjax('请刷新后再尝试',false,3));
			}
		}


		$service_job = new base_service_company_job_job();
		$job_info = $service_job->getJobs($job_id, 'job_id,station');
		$job_info = base_lib_BaseUtils::getProperty($job_info,'station');

		$apply_service = new base_service_company_resume_apply;
		if($apply_service->refuseLastResume($job_id,2,$txtContent)){

			//---------添加操作日志--------
			$common_oper_type = new base_service_common_account_accountoperatetype();
			$common_oper_src_type = new base_service_common_account_accountlogfrom();
			$service_oper_log = new base_service_company_companyaccountlog();
			$insertItems=array(
				"company_id"=>$this->_userid,
				"account_id"=>base_lib_BaseUtils::getCookie('accountid'),
				"operate_type"=>$common_oper_type->resume_refuse_stop_job,
				"source"=>$common_oper_src_type->website,
				"content"=>"一键回绝停招职位：".implode("，",$job_info)." 中的所有待处理简历",
				"create_time"=>date("Y-m-d H:i:s",time())
			);
			$service_oper_log->addLogToMongo($insertItems);
			//-------------END------------

			echo $this->__returnAjax('一键回绝成功',true,0);
		}else{
			echo $this->__returnAjax('一键回绝失败',false,1);
		}

	}


	//一键回绝剩余简历
	public function pageRefuseLastResume($inPath)
	{
        if(!$this->canDo("refuse_resume")){
            echo $this->__returnAjax('无权限访问，没有开通相应权限',false,1);return;
        }
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$company_ids = array();
		$service_cache_refuse_job = new company_service_applyrefusecache();
		$child_status     = base_lib_BaseUtils::getStr($path_data['child_status'], 'int', null);
		$account_id       = base_lib_BaseUtils::getStr($path_data['account_id'], 'int', "");
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'],'int','');
		$txtContent = base_lib_BaseUtils::getStr($path_data['txtContent'],'string','');

		if(empty($account_id)){
			$company_ids[] = $this->_userid;
			//包括子公司
			$company_resources = new base_service_company_resources_resources($this->_userid);
			$company_service = new base_service_company_company();
			$accounts = $company_service->getCompanys($company_resources->all_accounts, "company_id");
			foreach ($accounts as $key => $account) {
				$company_ids[] = $account['company_id'];
			}
		}else{
			$company_ids[] = $account_id;
		}

		//职位id有，公司ID可不需要
//		if(!empty($job_id)){
//			$company_ids = array();
//		}

		$res = $service_cache_refuse_job->getVerifyCode($this->_userid);
		$showStopJobApply = base_lib_BaseUtils::getCookie("showStopJobApply"); //判断用户是否包含了 停招招聘的职位
		if(empty($res)){
			echo $this->__returnAjax('请刷新后再试',false,2);die;
		}
		$apply_service = new base_service_company_resume_apply;

		$result = false;
		if($showStopJobApply == false && empty($job_id)){
			//这里会过滤掉包含停招职位进行处理
			$result = $apply_service->refuseLastResumeWithoutStopJob($txtContent,$company_ids,$child_status,@$res['visit_time'],@$res['all_apply_ids']);
		}else{
			$result = $apply_service->refuseLastResume($job_id,1,$txtContent,$company_ids,$child_status,@$res['visit_time']);
		}

		if($result !== false){
			//---------添加操作日志--------
			$common_oper_type = new base_service_common_account_accountoperatetype();
			$service_oper_log = new base_service_company_companyaccountlog();
			$common_oper_src_type = new base_service_common_account_accountlogfrom();
			$insertItems=array(
				"company_id"=>$this->_userid,
				"source"=>$common_oper_src_type->website,
				"account_id"=>base_lib_BaseUtils::getCookie('accountid'),
				"operate_type"=>$common_oper_type->resume_refuse_last,
				"content"=>"一键回绝了剩余简历",
				"create_time"=>date("Y-m-d H:i:s",time())
			);
			$service_oper_log->addLogToMongo($insertItems);
			//-------------END------------
			echo $this->__returnAjax('一键拒绝剩余成功',true,0);die;
		}else{
			echo $this->__returnAjax('一键回绝失败',false,1);
		}

	}

	//修改为待定
	public function pageMarkWaitDeal($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$applay_id = base_lib_BaseUtils::getStr($path_data['applyid'],'string','11');

		if(empty($applay_id)){
			//exit($this->__returnAjax('请刷新后再试',false,1));
		}

		$apply_service = new base_service_company_resume_apply;
		$res = $apply_service->markWaitDeal($applay_id);
		if($res !== false){
			//---------添加操作日志--------
			$service_apply = new base_service_company_resume_apply();
			$service_persons = new base_service_person_person();
			$applys = $service_apply->getApplys($applay_id,$field="apply_id,station,person_id")->items;
			$applys = base_lib_BaseUtils::array_key_assoc($applys, "apply_id");
			$person_ids = base_lib_BaseUtils::getProperty($applys,'person_id');
			$resume_infos = $service_persons->getPersons($person_ids,'person_id,user_name')->items;
			$resume_infos = base_lib_BaseUtils::array_key_assoc($resume_infos,'person_id');
			$log_message = '';
			foreach($applys as $k=>$v)
			{
				$log_message .= "'".$resume_infos[$v['person_id']]['user_name']."'投递的简历'".
					$v['station']."' ";
			}

			$common_oper_type = new base_service_common_account_accountoperatetype();
			$service_oper_log = new base_service_company_companyaccountlog();
			$common_oper_src_type = new base_service_common_account_accountlogfrom();
			$insertItems=array(
				"company_id"=>$this->_userid,
				"source"=>$common_oper_src_type->website,
				"account_id"=>base_lib_BaseUtils::getCookie('accountid'),
				"operate_type"=>$common_oper_type->resume_wait_deal,
				"content"=>" 以下投递简历被修改为待定：".$log_message,
				"create_time"=>date("Y-m-d H:i:s",time())
			);
			$service_oper_log->addLogToMongo($insertItems);
			//-------------END------------
			exit($this->__returnAjax('操作成功',true,0));
		}else{
			exit($this->__returnAjax('请刷新后再试',false,2));
		}



	}


	/**
	 * 
	 * 获取企业统计信息
	 * @param $inPath
	 */
	public function pageGetStat($inPath) {
		$service_job_apply = new base_service_company_resume_apply();
		$jsonArr = array();
		$stat = $service_job_apply->getApplyStat($this->_userid);
		if ($stat) {
			$jsonArr['job_apply_not_read_num']  = $stat['no_read_num'];
			$jsonArr['job_apply_not_reply_num'] = $stat['no_deal_num'];
			$jsonArr['job_apply_invite_num']    = $stat['interview_num'];
			$jsonArr['job_apply_refute_num']    = $stat['refuse_num'];
			$jsonArr['job_no_reply_count']      = $stat['no_reply_num'];
		} else {
			$jsonArr['job_apply_not_read_num']  = 0;
			$jsonArr['job_apply_not_reply_num'] = 0;
			$jsonArr['job_apply_invite_num']    = 0;
			$jsonArr['job_apply_refute_num']    = 0;
			$jsonArr['job_no_reply_count']      = 0;
		}		
   		echo json_encode($jsonArr);	
   	    return;
   	}
	
	/*
	 * 获取简历
	 */
	public function pageapplyjob($inPath) { 
		$apply_status = new base_service_company_resume_applystatus();
	    $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
	    // 获取参数信息
		$cur_page = base_lib_BaseUtils::getStr($path_data['page'],'int',1);
	    $status = base_lib_BaseUtils::getStr($path_data['status'],'string',$apply_status->no_reply);
	    $job_id  =  base_lib_BaseUtils::getStr($path_data['jobid'],'int',0);
	    $user_name =  base_lib_BaseUtils::getStr($path_data['username'],'string',null);
	    $resume_id =  base_lib_BaseUtils::getStr($path_data['resumeid'],'int',0);
	    if($job_id==0) {
	    	$job_id = null;
	    } 
	    $page_size =base_lib_BaseUtils::getStr($path_data['pageSize'],'int',base_lib_Constant::PAGE_SIZE);	
	    $data = $this->getJobApplyList($cur_page,$page_size,$status,$this->_userid,$job_id,$user_name,$resume_id);
	    echo json_encode($data);
	    return;		   
	}

	/**
	 * [pageDeductcountprompt description]
	 * @param  [type] $inPath [description]
	 * @return [type]         [description]
	 */
	public function pageDeductcountprompt($inPath) {
		$params       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resumeid     = base_lib_BaseUtils::getStr($params['resumeid'], 'int', null);
        $recommend_id = base_lib_BaseUtils::getStr($params['recommend_id'], 'int', 0);


		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);        list($status, $code, $params) = $company_resources->check($func="download", $params=['resume_id'=>$resumeid]);


$pricing_resource_data = $company_resources->getCompanyServiceSource(['account_resource']);        $this->_aParams['isCqNewService'] = $pricing_resource_data['isCqNewService'];


$account_overage = $pricing_resource_data['account_overage'];
        $params['account_overage'] = $account_overage; 

        $params['account_overage']               = $account_overage = $pricing_resource_data['account_overage'];
        $params['status']                        = $status;
        $params['code']                          = $code;
        $this->_aParams['cq_resume_num_release'] = $pricing_resource_data['cq_resume_num_release']; //剩余简历点
        $this->_aParams['recommend_id']          = $recommend_id;
        $this->_aParams['resume_id']             = $resumeid;
        $this->_aParams['params']                = $params;
        $this->_aParams['account_type']          = $company_resources->isMember() ? "member" : "not_member";

        //余额扣除点数
        $servicePricing = new base_service_company_service_servicePricing();
        $account_overage_service_price = $servicePricing->GetFunParallelismSelling('point_dow_resume');

        //是重庆新套餐服务
        if($pricing_resource_data['isCqNewService']){
            //简历点 或 余额 不足
            if($pricing_resource_data['cq_resume_num_release'] < 1 && $account_overage < $account_overage_service_price){
                $this->redirect('/resume/resumeinfomsghtml/?type=1');
            }
        }
        $this->_aParams['account_overage_service_price'] = $account_overage_service_price;
    	return $this->render('resume/apply/deductcountprompt.html', $this->_aParams);
	}
	
	/**
	 * 
	 * 获取收到的简历
	 * @param int $page_size
	 * @param int $page_size
	 * @param string $status
	 * @param int $company_id
	 */
	private function getJobApplyList($cur_page,$page_size,$status,$company_id,$job_id=null,$user_name=null,$resume_id=0) {
	    // 职位申请类
		$service_job_apply = new base_service_company_resume_apply();
		$service_job_apply->setLimit($page_size);
		$service_job_apply->setPage($cur_page);
		$service_job_apply->setCount(true);
		// 根据状态和职位编号查询
		if(!empty($user_name)||$resume_id!=0) {
			$resume_list = $service_job_apply->getApplyByPerson($company_id,$resume_id, $user_name,'apply_id,info_job_apply.person_id,info_job_apply.resume_id,station,info_job_apply.create_time,is_cancelled,has_read');
		}else {
			$resume_list = $service_job_apply->getApplyList($company_id,$job_id, $status,'apply_id,person_id,resume_id,station,create_time,is_cancelled,has_read');
		}
		$data = array();
		if(!empty($resume_list->items)){		
			$service_resume = new base_service_person_resume_resume();
			$service_person = new base_service_person_person();
			$resume_work = new base_service_person_resume_work();
			$resume_remark = new base_service_company_resume_resumeremark();
			$area = new base_service_common_area();
			$degree = new base_service_common_degree();
			// 将简历信息，个人信息，工作信息和备注信息一次性取出来
			$person_ids = $this->getPropertys($resume_list->items, 'person_id');
			$resume_ids = $this->getPropertys($resume_list->items, 'resume_id');
	        $resumes = $service_resume->getResumes($resume_ids,'resume_id,resume_name,degree_id,current_station,current_station_start_time,current_station_end_time');
	        $persons = $service_person->getPersons($person_ids, 'person_id,user_name,birthday,cur_area_id,photo,small_photo,photo_open,birthday2');
	        //$lastworks = $resume_work->getLastResumeWorks($resume_ids, 'work_id,resume_id,start_time,end_time,station');
	        $remarks = $resume_remark->getLastResumeRemarks($this->_userid, $resume_ids,'remark_id,resume_id,company_id,remark');
			for($i = 0; $i <  count($resume_list->items); $i ++) {
				//申请时间
				$resume_list->items[$i]['create_time_name'] = base_lib_TimeUtil::to_friend_time3( $resume_list->items [$i] ['create_time'] );
				//简历信息
				$resume_info =$this->arrayFind($resumes->items, 'resume_id', $resume_list->items[$i]['resume_id']);
				//用户信息
				$person_info = $this->arrayFind($persons->items, 'person_id', $resume_list->items[$i]['person_id']);
				//工作信息
				//$resume_work_info =  $this->arrayFind($lastworks->items, 'resume_id', $resume_list->items[$i]['resume_id']);
				//备注信息
				$resume_remark_info =  $this->arrayFind($remarks->items, 'resume_id', $resume_list->items[$i]['resume_id']);
				//姓名
			    $resume_list->items[$i]['user_name'] = $person_info['user_name'];
				$resume_list->items[$i]['isdeleteresume'] = empty($resume_info)?1:0;
				//年龄 地区 学历
				$resume_list->items[$i]['age'] = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']).'岁';
				$resume_list->items[$i]['area'] = $area->getArea($person_info['cur_area_id']);
				$resume_list->items[$i]['degree'] = $degree->getDegree($resume_info['degree_id']);				
				//最近工作经验
				$resume_list->items[$i]['work'] = $resume_info['current_station'];
				if($resume_info['current_station']==''){
					$resume_list->items[$i]['work'] = '无';
				}else {
					if(empty($resume_info['current_station_start_time'])){
						$resume_list->items[$i]['work'] = $resume_info['current_station'];
					}else{
						$resume_list->items[$i]['work']= $resume_info['current_station'].'('.base_lib_TimeUtil::date_diff_year3($resume_info['current_station_start_time'],$resume_info['current_station_end_time']).')';
					}
				}
				//头像
				if($person_info['photo_open']!='0'){					
					if(empty($person_info['small_photo'])){						
						if(!empty($person_info['photo'])){
							$photo = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$person_info['photo'];
							$resume_list->items[$i]['photo'] = $photo;
						}
					}else{
						$photo = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$person_info['small_photo'];
						$resume_list->items[$i]['photo'] = $photo;
					}
				}
				
				/*
				if(!base_lib_BaseUtils::nullOrEmpty($person_info['photo'])){
					$photo = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP."{$person_info['photo']}?r=".rand(1000, 9999);
					$resume_list->items[$i]['photo'] = $photo;
				}*/
				
				//备注
				$remark = $resume_remark_info['remark'];
				if(base_lib_BaseUtils::nullOrEmpty($remark)) {
					$remark = '';
				}
				$resume_list->items[$i]['remark'] = $remark;
			}
			$data['list'] = $resume_list->items;
		}
	    $data['curPage'] = $cur_page;
		$data['pageSize'] = $page_size;	
		$data['recordCount'] = $resume_list->totalSize;
		return $data;			
	}


	public function pageClearPromiseResume($inPath){
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($params['jid'],'int','0');	
		$map=array();
		$date = date('Y-m-d',strtotime("-90 days")).' 00:00:00';

		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);

		$map["company_id"] = ['in'=>$company_resources->all_accounts];
		$map['is_company_deleted'] = '0';
		$map['is_cancelled'] = '0';
		$map['re_status'] = '5';
		$map['create_time'] = array(">="=>$date);
		if($job_id){
			$map['job_id'] = $job_id;
		}

		$apply_service = new base_service_company_resume_apply();
		$apply_service->clearApply($this->_userid,'5',$map);

		echo json_encode(array('status' =>1 , 'msg'=>'清理成功！'));
	}

	/**
	 * 设置推荐简历气泡cookie
	 * @param $inPath
	 */
	public function pagesetArtificialRecommendCookie($inPath){
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$cookie_name = "showArtificialRecommendTip_".$account_id;
		$cookie_data = ["{$cookie_name}" => true];
		$time = date("Y-m-d 00:00:00",strtotime("-2 week"));
		base_lib_BaseUtils::ssetcookie($cookie_data,$time,'/',base_lib_Constant::COOKIE_DOMAIN);
	}


	/**
	 *
	 * 加载提升简历处理率弹窗
	 *
	 * @use 简历管理-》待处理简历
	 * @param $inPath
	 */
	public function pageImproveRelateAlertHtml($inPath)
	{
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

		echo $this->render('resume/apply/improverelatealert.html',$this->_aParams);
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
	
	/**
	 * 
	 * 获取数组里对象的属性集合
	 * @param array $arr  对象数组
	 * @param string $property  属性
	 */
	private function getPropertys11($arr,$property) {
	   $peropertys = array();	
	   if(count($arr)>0){
			foreach ($arr as $item){
				if($item[$property]!=NULL){
					 array_push($peropertys, $item[$property]);
				}
			}
		}
	   return $peropertys;	
    }

   	/**
	 * 
	 * 获取数组里对象的属性集合
	 * @param array $arr  对象数组
	 * @param string $property  属性
	 * @author zhangfangjun 
	 */
	private function getPropertys($arr,$property) {
	   $peropertys = array();	
	   foreach ($arr as $item){
	   	if(isset($item[$property])){
	   	 	array_push($peropertys, $item[$property]);
	   	}	   	
	   }
	   return $peropertys;	
    }
   
   /**
    * 
    * 数组查询
    * @param array $arr
    * @param string $key
    * @param string $value
    */
   private function arrayFind($arr,$property,$value) {
   	   $obj = null;
	   if(count($arr)>0){
			foreach ($arr as $item){
			   if($item[$property]==$value) {
				   $obj =  $item;
				   break;
			   }
			}
		}
	   return  $obj;
   }
   /**
    * 数组查询
    * @return 返回数据
    */
	private function arrayFindAll($arr,$property,$value){
   	   $new_arr = array();
	    if (count($arr) > 0) {
			foreach ($arr as $item){
			   if($item[$property]==$value) {
				  array_push($new_arr, $item);
			   }
			}
		}
	   return  $new_arr;
	}

	/**
	 *@desc删除收到的简历
	 *@return
	*/
	public function pageDeleteApply($inPath) {

        
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$operate   = base_lib_BaseUtils::getStr($path_data['op'], 'string', ''); 
		$apply_ids = base_lib_BaseUtils::getStr($path_data['ids'], 'array', null);
		$apply_ids = base_lib_BaseUtils::getIntArrayOrString($apply_ids);

		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);

		$service_apply = new base_service_company_resume_apply();
		$service_persons = new base_service_person_person();
		$applys = $service_apply->getApplys($apply_ids, $field="company_id,apply_id,station,person_id")->items;
		$applys = base_lib_BaseUtils::array_key_assoc($applys, "apply_id");
		$person_ids = base_lib_BaseUtils::getProperty($applys,'person_id');
		$resume_infos = $service_persons->getPersons($person_ids,'person_id,user_name')->items;
		$resume_infos = base_lib_BaseUtils::array_key_assoc($resume_infos,'person_id');
		$log_message = '';
		foreach($applys as $k=>$v)
		{
			$log_message .= "'".$resume_infos[$v['person_id']]['user_name']."'投递的简历'".$v['station']."' ";
		}

	    switch ($operate) {
			case 'del':
                if(!$this->canDo("delete_resume")){
                    echo json_encode(array('error' => '无权限访问，没有开通相应权限'));
					return ;
                }
				if (empty($apply_ids) || count($apply_ids) <= 0) {
					echo json_encode(array('error' => '请选择你要删除的简历'));
					return ;
				}

				$job_apply = new base_service_company_resume_apply();
				foreach ($apply_ids as $apply_id) {
					$apply = $applys[$apply_id];
	    			if (empty($apply) || !in_array($apply['company_id'], $company_resources->all_accounts)) {
	    				continue;
	    			}

					$job_apply->deleteApply($apply['company_id'], $apply_id);
				}

				//---------添加操作日志--------
				$common_oper_type = new base_service_common_account_accountoperatetype();
				$service_oper_log = new base_service_company_companyaccountlog();
				$common_oper_src_type = new base_service_common_account_accountlogfrom();
				$insertItems=array(
					"company_id"=>$this->_userid,
					"source"=>$common_oper_src_type->website,
					"account_id"=>base_lib_BaseUtils::getCookie('accountid'),
					"operate_type"=>$common_oper_type->resume_delete,
					"content"=>"以下投递简历被放入回收站：".$log_message,
					"create_time"=>date("Y-m-d H:i:s",time())
				);
				$service_oper_log->addLogToMongo($insertItems);
				//-------------END------------


				echo json_encode(array('success'=>'1'));
				return;

			default:
                if(!$this->canDo("delete_resume")){
                    $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
                    $this->_aParams["url"] = "/";
                    return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
                }
	    		// 提示
	    		$names =  explode(',', urldecode(base_lib_BaseUtils::getStr($path_data['names'], 'string', '')));
				$this->_aParams['names'] = $names;
				$this->_aParams['ids']   = implode(',', $apply_ids);
	    		return  $this->render('resume/apply/delete.html', $this->_aParams);
	   }
	}
	
	/**
	 * 获取职位信息并排序
	 * @param  $job_ids
	 */
	private function _getJobsAndSort($job_ids, $show_use_job=1, $job_id=null, &$use_job_ids, $showStopJobApply=false){
		$service_job = new base_service_company_job_job();
		$jobs = $service_job->getJobs($job_ids, 'job_id,station,end_time,status,check_state');


		$validJob = array();
		$voidJob  = array();

		$status = new base_service_common_jobstatus();
		foreach ($jobs as $job) {
			if ($job['status'] != $status->use
				|| base_lib_TimeUtil::time_diff_day($job['end_time'], date('Y-m-d H:i:s')) > 0) {
				if ($showStopJobApply == true || $show_use_job != 1) {
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
	
	/**
	 * 
	 * 获取企业 启用/停用的职位信息
	 * @param unknown_type $inPath
	 */
	public function pageLoadJob($inPath) {

		// 加载公司的职位信息
	    $job_status = new base_service_common_jobstatus();
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_status = base_lib_BaseUtils::getStr($path_data['status'],'int',$job_status->use);
		$service_job = new base_service_company_job_job();
		$joblist = $service_job->getJobList($this->_userid,'',$job_status,'job_id,station');
	    $item = (empty($joblist)?'[]':json_encode($joblist));
		echo $item;	
		return;
	}
	
	/**
	 * 
	 * 设置求职申请的已读状态
	 */
	public function pageSetRead($inPath) {
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $apply_ids = base_lib_BaseUtils::getIntArrayOrString($params['applyid'], 'int_string');

        if (base_lib_BaseUtils::nullOrEmpty($apply_ids)) {
            $json['error'] = '请选择你要操作的简历';
            echo json_encode($json);
            exit;
        }

		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);

        $service_apply = new base_service_company_resume_apply();
        $result = $service_apply->setRead(explode(',', $apply_ids), $company_resources->all_accounts);

        if ($result === false) {
            $json['error'] = '设置已读失败';
        } else {
            $json['success'] = '设置成功';
            //成功后添加简历浏览记录
            $applys = $service_apply->getApplys($apply_ids, 'resume_id,person_id,company_id')->items;
            foreach ((array)$applys as $apply) {
                $this->_addVisit($apply['resume_id'], $apply['person_id'], $apply['company_id']);
            }
        }

        echo json_encode($json);
        return;
	}

	/**
	 * 
	 * 设置求职申请显示给求职者的已读状态
	 */
	public function pageSetShowPersonRead($inPath) {
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$apply_ids  = $params['applyids'];
		if(base_lib_BaseUtils::nullOrEmpty($apply_ids)) {
	    	  return;			
		}
		$service_apply = new base_service_company_resume_apply();
		$result = $service_apply->setShowPersonRead(explode(',', $apply_ids),$this->_userid);
		$json['success'] = 'true';
		echo "var re ='".json_encode($json)."'";
	    return;		
	}
	
	public function pageGetStatus($inPath) {
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$apply_ids  = $params['applyid'];
		if(base_lib_BaseUtils::nullOrEmpty($apply_ids)) {
	    	  $json['error'] = '请指定收到的简历';
	    	  echo json_encode($json);
	    	  return;			
		}
		$service_apply = new base_service_company_resume_apply();
		$result = $service_apply->getApplys(explode(',', $apply_ids),'apply_id,re_status');
		echo json_encode($result->items);
	    return;		
	}	
	
	/**
	 * 
	 * 下载简历
	 */
	public function pageDownLoad($inPath) {
        if(!$this->canDo("see_resume_info")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
        }
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$apply_ids  = $params['applyid'];
		$resume_ids  = $params['resumeid'];
		$this->_aParams['applyids'] = $apply_ids;
		$this->_aParams['resumeids'] = $resume_ids;
		return $this->render('resume/apply/down.html', $this->_aParams);	
	}	
	
	/**
	 * 谢绝婉拒求职者
	 */
	public function pageRefuse($inPath) {
		try{
			
		
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$operate   = base_lib_BaseUtils::getStr($path_data['op'], 'string', '');
		$apply_ids = base_lib_BaseUtils::getStr($path_data['ids'], 'array', null);
		$apply_ids = base_lib_BaseUtils::getIntArrayOrString($apply_ids);
		$content   = base_lib_BaseUtils::getStr($path_data['txtContent'], 'string', null);
		$src	   = base_lib_BaseUtils::getStr($path_data['src'], 'string', null);//标记是否来自一键拒绝剩余简历
		$this->_aParams['job_id'] = base_lib_BaseUtils::getStr($path_data['job_id'], 'string', null);
		$this->_aParams['account_id'] = base_lib_BaseUtils::getStr($path_data['account_id'], 'string', null);
		$this->_aParams['child_status'] = base_lib_BaseUtils::getStr($path_data['child_status'], 'string', null);

		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
	    switch ($operate) {
	    	case 'refuse':
                if(!$this->canDo("refuse_resume")){
                    $json['error'] = '无权限访问，没有开通相应权限';
                    echo json_encode($json);return;
                }
	    		// 婉拒求职者
                if(empty($apply_ids)){
                    $json['error'] = '标记为不合适失败，没有投递对象';
                    echo json_encode($json);
                    return;
                }
	    		$service_apply = new base_service_company_resume_apply();
	    		$applys = $service_apply->getApplys($apply_ids, $field="company_id,apply_id,station,has_read,re_status")->items;
				$applys_info = base_lib_BaseUtils::getProperty($applys,'station');
	    		$applys = base_lib_BaseUtils::array_key_assoc($applys, "apply_id");
				$resume_applystatus = new base_service_company_resume_applystatus();
				$apply_ids_ok = array();
				$apply_ids_no = array();
	    		foreach ($apply_ids as $apply_id) {

                    if (mb_strlen($content) > 250)
                        $json['error'] = '内容不能超过250字。';

                    if (!in_array($applys[$apply_id]['company_id'], $company_resources->all_accounts))
                    	continue;

                    if (base_lib_BaseUtils::nullOrEmpty($content)) {
                        $content = "我们认真阅读了您的简历，很遗憾您的简历与该职位暂不匹配，已将您的简历纳入人才储备库。感谢您对我司的信任，祝您早日找到满意工作！";
                    }
					$result = null;
					//阅读状态必须是 已读未回复、未读 (re_status = 0)
					if($applys[$apply_id]['re_status'] == 0){
						$result = $service_apply->refusedReplayV2($apply_id, $applys[$apply_id]['company_id'], $content);
					}else{
						continue;
					}
	    			if ($result !== false) {
						$apply_ids_ok[] = $apply_id;
	    			}else{
						$apply_ids_no[] = $apply_id;
					}
	    		}

				if (!empty($apply_ids_no)) {
					$json['error'] = '标记为不合适失败';
				} else {

					//---------添加操作日志--------
					$service_apply = new base_service_company_resume_apply();
					$service_persons = new base_service_person_person();
					$applys = $service_apply->getApplys($apply_ids_ok,$field="apply_id,station,person_id")->items;
					$applys = base_lib_BaseUtils::array_key_assoc($applys, "apply_id");
					$person_ids = base_lib_BaseUtils::getProperty($applys,'person_id');
					$resume_infos = $service_persons->getPersons($person_ids,'person_id,user_name')->items;
					$resume_infos = base_lib_BaseUtils::array_key_assoc($resume_infos,'person_id');
					$log_message = '';
					foreach($applys as $k=>$v)
					{
						$log_message .= "'".$resume_infos[$v['person_id']]['user_name']."'投递的简历'".
							$v['station']."' ";
					}
					$common_oper_type = new base_service_common_account_accountoperatetype();
					$service_oper_log = new base_service_company_companyaccountlog();
					$common_oper_src_type = new base_service_common_account_accountlogfrom();
					$insertItems=array(
						"company_id"=>$this->_userid,
						"source"=>$common_oper_src_type->website,
						"account_id"=>base_lib_BaseUtils::getCookie('accountid'),
						"operate_type"=>$common_oper_type->resume_refuse,
						"content"=>"以下简历被设置成了不合适：".$log_message,
						"create_time"=>date("Y-m-d H:i:s",time())
					);
					$service_oper_log->addLogToMongo($insertItems);
					//-------------END------------

					$json['success'] = '已成功标记为不合适';
				}

				echo json_encode($json);
	    	   	return;

	    	default:
                 if(!$this->canDo("refuse_resume")){
                    $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
                    $this->_aParams["url"] = "/";
                    return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
                }
	    		// 跳转到提示窗口
	    		$names = explode(',', urldecode(base_lib_BaseUtils::getStr($path_data['names'], 'string', '')));
				$this->_aParams['names']   = $names;
				$this->_aParams['ids']     = implode(",", $apply_ids);
				$this->_aParams['content'] = "我们认真阅读了您的简历，很遗憾您的简历与该职位暂不匹配，已将您的简历纳入人才储备库。感谢您对我司的信任，祝您早日找到满意工作！";
                $this->_aParams['select_template_id'] = 0;

				$service_templates = new base_service_company_companytemplates_companyrefusetemplate();
                $company_templates = $service_templates->getTemplateList($this->_userid, "template_id,title,content");

                $company_templates_json[] = ["id" => 0, "name" => "拒绝简历默认模板"];
                foreach ((array)$company_templates->items as $item => $v) {
                    $company_templates_json[] = ["id" => $v['template_id'], "name" => $v['title']];
                }

                $this->_aParams['company_templates_json'] = json_encode($company_templates_json);
				$this->_aParams['src'] = $src;
	    		return $this->render('resume/apply/refuse_v2.html', $this->_aParams);
	    }
		}catch (Exception $e){
			var_dump($e->getMessage());
		}
	}
        //管理拒绝面试模板
        public function pageManageRefuseTemplate($inPath){
            $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
            $call_back = base_lib_BaseUtils::getStr($path_data['callback'],'string',null);
            
            $this->_aParams['callback'] = $call_back;
            //获得企业的所有拒绝面试模板
            $service_templates = new base_service_company_companytemplates_companyrefusetemplate();
            $company_templates = $service_templates->getTemplateList($this->_userid,"template_id,title,content")->items;
            $this->_aParams['template_list'] = $company_templates;
            
            return $this->render("resume/apply/managertemplate.html",$this->_aParams);
        }
        
        public function pageAjaxGetTemplate($inPath){
            $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
            $template_id = base_lib_BaseUtils::getStr($path_data['template_id'],'string',null);
            $service_templates = new base_service_company_companytemplates_companyrefusetemplate();
            if(base_lib_BaseUtils::nullOrEmpty($template_id)){
                $company_templates = $service_templates->getTemplateList($this->_userid,"template_id,title,content");
                $company_templates_json = array();
                array_push($company_templates_json,array("id"=>0,"name"=>"拒绝简历默认模板"));
               // $company_templates_json = "{id:\"0\",name:\"拒绝简历默认模板\"},";
                    if (!empty($company_templates->items)) {
                        foreach ($company_templates->items as $item=>$v){
                           // $company_templates_json = "$company_templates_json{id:\"{$v['template_id']}\",name:\"{$v['title']}\"},";
                            array_push($company_templates_json,array("id"=>$v['template_id'],"name"=>$v['title']));
                        }
                    }
                //$company_templates_json = substr($company_templates_json, 0, strlen($company_templates_json)-1);
               // $result = "[$company_templates_json]";
                echo json_encode(array("success"=>true,"template_data"=>$company_templates_json));exit;
            }else{
                if($template_id !=0){
                    $template = $service_templates->getTemplate($template_id, "template_id,title,content");
                    if(!base_lib_BaseUtils::nullOrEmpty($template)){
                        echo json_encode(array("success"=>true,'title'=>$template['title'],"content"=>$template['content'],'template_id'=>$template['template_id']));return;
                     }else{
                        echo json_encode(array("error"=>"未找到模板对象"));return;
                     }
                }else{
                     $defult_content = "我们认真阅读了您的简历，很遗憾您的简历与该职位的要求不匹配。感谢您对我司的信任，祝您早日找到满意的工作。";
                    echo json_encode(array("success"=>true,'title'=>"拒绝简历默认模板","content"=>$defult_content,'template_id'=>0));return;
                }
            }
            
        }
        
        //添加新模板
        public function pageAddTemplate($inPath){
            $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
			$val = new base_lib_Validator();

			$title = $val->getNotNull($path_data['txtTitle'],'请填写模板名称');
			$title = $val->getStr($path_data['txtTitle'],1,20,'模板名称不能超过20字');
			$content = $val->getNotNull($path_data['txtContent'],'请填写模板内容');
			$content = $val->getStr($path_data['txtContent'],1,200,'模板内容不能超过200字');
			if($val->has_err)
			{
				echo $val->toJsonWithHtml();
				return ;
			}
			$item = array('title'=>$title,'content'=>$content,'company_id'=>$this->_userid,'create_time'=>date("Y-m-d H:i:s"));
			$company_template = new base_service_company_companytemplates_companyrefusetemplate();
				//添加模板
				$item['company_id'] = $this->_userid;
				$template_id = $company_template->addTemplate($item);
				if(!$template_id){
					echo json_encode(array('error'=>'新增模板失败'));
					return ;
				}
			echo json_encode(array('success'=>true,'title'=>$title,'template_id'=>intval($template_id),'content'=>$content));
        }
        
        //删除模板
        public function pageDeleteTemplate($inPath){
            $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
            $template_id = base_lib_BaseUtils::getStr($path_data['template_id'],"int",0);
            if($template_id ==0){
                echo json_encode(array("error"=>"未找到模板对象"));return;
            }
            //修改模板
            $company_template = new base_service_company_companytemplates_companyrefusetemplate();
           $template = $company_template->deleteTemplate($template_id);
            if(!$template){
				echo json_encode(array('error'=>'删除模板失败'));
				return ;
            }
            echo json_encode(array('success'=>'1'));
        }
        public function pageGetTemplate($inPath){
            $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
            $template_id = base_lib_BaseUtils::getStr($path_data['template_id'],"int",0);
            if($template_id ==0){
                echo json_encode(array("error"=>"未找到模板对象"));return;
            }
            //修改模板
            $company_template = new base_service_company_companytemplates_companyrefusetemplate();
            $template = $company_template->getTemplate($template_id, "template_id,title,content");
            if(!base_lib_BaseUtils::nullOrEmpty($template)){
                echo json_encode(array("success"=>true,'title'=>$template['title'],"content"=>$template['content'],'template_id'=>$template['template_id']));return;
            }else{
                echo json_encode(array("error"=>"未找到模板对象"));return;
            }
        }
        //修改模板
        public function pageEditTemplate($inPath){
            $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
            $val = new base_lib_Validator();
            
            $template_id = base_lib_BaseUtils::getStr($path_data['template_id'],"int",0);
            if($template_id ==0){
                echo json_encode(array("error"=>"未找到模板对象"));return;
            }
            $title = $val->getNotNull($path_data['txtTitle'],'请填写模板名称');
            $title = $val->getStr($path_data['txtTitle'],1,20,'模板名称不能超过20字');
            $content = $val->getNotNull($path_data['txtContent'],'请填写模板内容');
            $content = $val->getStr($path_data['txtContent'],1,200,'模板内容不能超过200字');
            if($val->has_err)
            {
                echo $val->toJsonWithHtml();
                return ;
            }
            //修改模板
             $company_template = new base_service_company_companytemplates_companyrefusetemplate();
            $item = array('title'=>$title,'content'=>$content,'update_time'=>date("Y-m-d H:i:s"));
            $condition = array('template_id'=>$template_id,"company_id"=>$this->_userid);
            $template = $company_template->updateTemplate($condition,$item);
            if($template===false){
                echo json_encode(array('error'=>'修改模板失败'));
                return ;
            }
            echo json_encode(array('success'=>true,'title'=>$title,'template_id'=>$template_id,'content'=>$content));
        }

	/**
	 * @desc 电话通知面试后 添加邀请记录 入口
	 * @param  $inPath
	 */
    public function pageHasInvite($inPath) {
        if(!$this->canDo("invite_resume")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
        }
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $apply_id = base_lib_BaseUtils::getStr($params['applyid'], "int", 0);
		$is_download = base_lib_BaseUtils::getStr($params['is_download'], "int", 0);
		$download_id = base_lib_BaseUtils::getStr($params['download_id'], "int", 0);
        if ($apply_id == 0 && empty($is_download)) {
            $this->_aParams['message'] = '未找到该条投递简历记录';
            return $this->render('showerror.html', $this->_aParams);
        }

        $service_company = new base_service_company_company();
        $currentCompany = $service_company->getCompany($this->_userid, 1, 'company_name,com_level,end_time,address,linkman,link_tel');
        if (empty($currentCompany)) {
            $this->_aParams['message'] = '请登录';
            return $this->render('showerror.html', $this->_aParams);
        }

		$com_level = $currentCompany['com_level'];
		$end_time  = $currentCompany['end_time'];

		$service_resume = new base_service_person_resume_resume();
		$service_apply  = new base_service_company_resume_apply();
        
        $resume = null;
		if(empty($is_download)) {
			if (!empty($apply_id)) {
				$apply = $service_apply->getApply($apply_id, 'apply_id,resume_id,job_id,station,create_time');
				if (empty($apply)) {
					$this->_aParams['message'] = '获取求职者申请信息失败';
					return $this->render('showerror.html', $this->_aParams);
				}

				$resume = $service_resume->getResume($apply['resume_id'], 'resume_id,person_id,is_chinese_resume,relate_resume_id,point');
				$downloadservice = new base_service_company_resume_download();
				$downloaded = $downloadservice->isResumeDownloaded($this->_userid, $resume['resume_id']);

				if (empty($resume)) {
					$this->_aParams['message'] = '简历获取失败';
					return $this->render('showerror.html', $this->_aParams);
				}

				$this->_aParams['apply_id'] = $apply_id;
				$this->_aParams['apply'] = $apply;
			} else {
				$this->_aParams['message'] = '获取求职者信息失败';
				return $this->render('showerror.html', $this->_aParams);
			}
		}else{
			$resume_id = base_lib_BaseUtils::getStr($params['resume_id'], "int", 0);
//			if (empty($resume_id)) {
//				$this->_aParams['message'] = '简历获取失败';
//				return $this->render('showerror.html', $this->_aParams);
//			}
			//获取公司所有职位
			$job_status  = new base_service_common_jobstatus();
			$service_company_job_job = new base_service_company_job_job();
			$job_list_pub = $service_company_job_job->getJobList($this->_userid,'',$job_status->pub,"job_id,station,status");
			$job_list_stop = $service_company_job_job->getJobList($this->_userid,'',$job_status->stop_use,"job_id,station,status");
			$job_list = array_merge($job_list_pub,$job_list_stop);
			$job_ids = base_lib_BaseUtils::getPropertys($job_list,"job_id");
			$jobs = $this->_getJobsAndSort($job_ids, true, '', $use_job_ids, true);
			
			$this->_aParams['job_list'] = $jobs;
			$this->_aParams['resume_id'] = $resume_id;

		}

		$this->_aParams['is_download'] = $is_download;
		$this->_aParams['download_id'] = $download_id;

        //获取日期
		$date_json = '';
		$weekarray = array("日", "一", "二", "三", "四", "五", "六");
        for ($i = -7; $i < 10; $i++) {
			$date      = date('Y-m-d', strtotime("+{$i} day"));
			$weekday   = date("w", strtotime("+{$i} day"));
			$date_json = "$date_json{id:\"{$date}\",name:\"{$date}[星期{$weekarray[$weekday]}]\"},";
        }

        $date_json = "$date_json{id:\"99\", name:\"自定义\"},";
        $date_json = substr($date_json, 0, strlen($date_json) - 1);
        $this->_aParams['date_json'] = "[$date_json]";
        return $this->render("./resume/invite/hasinvite.html", $this->_aParams);
    }
        
    /**
	 * @desc 电话通知面试后 设置为已邀请，添加一条邀请面试记录
	 * @param  $inPath
	 */
    public function pageSetInviteDo($inPath){
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $validator = new base_lib_Validator();
        $apply_id  = base_lib_BaseUtils::getStr($path_data['applyid'], "int", 0);
		$is_download  = base_lib_BaseUtils::getStr($path_data['is_download'], "int", 0);
		$job_id  = base_lib_BaseUtils::getStr($path_data['job_id'], "int", 0);
		$resume_id  = base_lib_BaseUtils::getStr($path_data['resume_id'], "int", 0);
		$download_id  = base_lib_BaseUtils::getStr($path_data['download_id'], "int", 0);
        if ($apply_id == 0 && empty($is_download)) {
            echo json_encode(array("status"=>false, 'error'=>"传入参数错误！"));
            exit;
        }

       	//面试时间
		$date     = base_lib_BaseUtils::getStr($path_data['hddDate'], "string", null);
        //面试时间改为必填，后台判断一下
        if(empty($date)){
            echo json_encode(array("status"=>false, 'error'=>"请设置面试时间！"));
            exit;
        }
		$sms_time = '';
		$invite   = array();
        if ($date == '99') {
//			$invite['audition_time'] = base_lib_BaseUtils::getStr($path_data['audition_time'], "string", null);
            $invite['audition_time'] = $validator->getDatetime($path_data['audition_time'], '请设置面试时间', false);
        } else {
			$time = base_lib_BaseUtils::getStr($path_data['hddtime'], "string", null);
			if (!base_lib_BaseUtils::nullOrEmpty($date) && !base_lib_BaseUtils::nullOrEmpty($time)) {
				$temp_date = "$date $time:00";
				$invite['audition_time'] = $validator->getDatetime($temp_date, '请设置面试时间', false);
			}
        }
		if($is_download){
			if(empty($job_id)) {
				echo json_encode(array("status" => false, 'error' => "请选择职位！"));
				exit;
			}
			if(empty($resume_id)){
				echo json_encode(array("status" => false, 'error' => "传入参数错误！"));
				exit;
			}
		}
        
        //添加一条邀请简历记录
		$service_company = new base_service_company_company();
		$service_apply   = new base_service_company_resume_apply();
		$service_company_job_job = new base_service_company_job_job();
		$service_person_resume_resume = new base_service_person_resume_resume();

		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
        $currentCompany = $service_company->getCompany($this->_userid, 1, 'company_id,company_name,com_level,end_time,address,linkman,link_tel');
		$apply = null;
		if(empty($is_download)) {
			$apply = $service_apply->getApply($apply_id, 'apply_id,resume_id,job_id,station,re_time,re_status,company_id,person_id');
			if (base_lib_BaseUtils::nullOrEmpty($apply) || !in_array($apply['company_id'], $company_resources->all_accounts)) {
				echo json_encode(array("status" => false, 'error' => "未找到该条投递的简历！"));
				exit;
			}
			$resume_id = $apply['resume_id'];
			$station = $apply['station'];
			$job_id = $apply['job_id'];
			$company_id = $apply['company_id'];
		}else{
			$job_info = $service_company_job_job->getJob($job_id,"job_id,station");
			if(empty($job_info)){
				echo json_encode(array("status" => false, 'error' => "职位信息不存在!"));
				exit;
			}
			$resume_info = $service_person_resume_resume->getResume($resume_id,"resume_id");
			if(empty($resume_info)){
				echo json_encode(array("status" => false, 'error' => "简历信息错误!"));
				exit;
			}
			$station = $job_info['station'];
			$company_id = $this->_userid;
		}

        if ($validator->has_err) {
			echo header("Content-type:text/plain;charset=utf-8");
			echo $validator->toJsonWithHtml();
			return;
        }
		
		$service_invitetype       = new base_service_company_resume_invitetype();
		$service_job              = new base_service_company_job_job();
		$service_jobapplyrestatus = new base_service_person_jobapplyrestatus();

		$invite['resume_id']         = intval($resume_id);
		$invite['station']           = $station;
		$invite['job_id']            = intval($job_id);
		
		$current_job = $service_job->getJob($invite['job_id'], 'end_time,company_id');

		$invite['invite_type']       = $service_invitetype->jobapply;//求职者主动投递
		$invite['audition_address']  = $currentCompany['address'];
		$invite['audition_link_man'] = $currentCompany['linkman'];
		$invite['audition_link_tel'] = $currentCompany['link_tel'];
		$invite['audition_remark']   = "";
		
		//单位编号
		$invite['company_id']        = $company_id;

		//邀请时间
		$invite['create_time']       = date('Y-m-d H:i:s');
		$invite['apply_id']          = $apply_id;
		$invite['re_status']         = $service_jobapplyrestatus->interview;//面试邀请已发出， 默认接受

        //职位截至时间
        if (!empty($current_job)) {
			$invite['end_time'] = $current_job['end_time'];
        } else {
			$invite['end_time'] = date('Y-m-d H:i:s', strtotime ('+7 day'));
        }
        
        //是否有效
        $invite['is_effect'] = 1;
        
        //求职者是否已阅读
        $invite['is_readed'] = 1;
        
        //面试结果
        $service_auditionresult = new base_service_company_resume_auditionresult();
        $invite['audition_result'] = $service_auditionresult->notset;
        
        $service_resume = new base_service_person_resume_resume();
        $resume = $service_resume->getResume($invite['resume_id'], 'resume_id,person_id');
        
        if (empty($resume)) {
			$json['error'] = '简历获取失败';
			echo header("Content-type:text/plain;charset=utf-8");
            echo json_encode($json);
            return;
        }
		
        //获取求职者
        $service_person = new base_service_person_person();
        $person = $service_person->getPerson($resume['person_id'], 'person_id,user_name,user_id,email,mobile_phone');
        if (empty($person)) {
            $json['error'] = '个人信息获取失败';

            echo header("Content-type:text/plain;charset=utf-8");
            echo json_encode($json);
            exit;
        }

        $invite['person_id'] = $resume['person_id'];
        $service_invite = new base_service_company_resume_jobinvite();
        $add_result = $service_invite->initiativeInviteV2($currentCompany, $resume, $invite, $apply);
        if ($add_result === true) {
		   //将该简历设为已读
			if(empty($is_download)) {
				$apply_ids = array($apply_id);
				$service_apply->setRead($apply_ids, $apply['company_id']);
			}else{
				//设置下载的简历为已邀请面试
				$download_service = new base_service_company_resume_download();
				$download_service->setDownloadResumeInvite($download_id,1);
			}

			//---------添加操作日志--------
			$common_oper_type = new base_service_common_account_accountoperatetype();
			$service_oper_log = new base_service_company_companyaccountlog();
			$common_oper_src_type = new base_service_common_account_accountlogfrom();
			$insertItems=array(
				"company_id"=>$this->_userid,
				"source"=>$common_oper_src_type->website,
				"account_id"=>base_lib_BaseUtils::getCookie('accountid'),
				"operate_type"=>$common_oper_type->resume_SetInvite_tell,
				"content"=>"'".$person['user_name']."'投递的简历 '".$station."' 被设置为已通知.",
				"create_time"=>date("Y-m-d H:i:s",time())
			);
			$service_oper_log->addLogToMongo($insertItems);
			//-------------END------------

			echo json_encode(array('status'=>true));
        } else {
			echo json_encode(array('status'=>false, "error"=>"设置面试邀请失败"));
        }
        return;
    }
    
    /**
	 * 新增访问记录
	 * @param  $inPath
	 */	
	private function _addVisit($resume_id,$person_id,$company_id) {
            $visit['resume_id']	 = $resume_id;
            $visit['person_id']	 = $person_id;
            $visit['company_id']	 = $company_id;
            $visit['hit_time']	 = date('Y-m-d H:i:s');
            $service_visit = new base_service_person_visit();
            $service_visit->addVisit($visit);
            // 更新简历冗余记录
            $service_resume = new base_service_person_resume_resume();
            $service_resume->increaseVisitNum($resume_id);
	}

    /**
     * @desc 投递简历匹配，是否自动过滤 匹配成功不自动过滤，匹配不成功，自动过滤
     * @param $job_info  职位信息
     * @param $resume 简历编号
     * @return array 
     */
    public function matchingApplyResume($job_info,$resume){
        if(base_lib_BaseUtils::nullOrEmpty($job_info) || base_lib_BaseUtils::nullOrEmpty($resume)){
            return false;
        }
        $job_degree_id = $job_info['degree_id'];//学历
        $job_sex = $job_info['sex'];//性别
        $job_lower_age = $job_info['age_lower'];//最小年龄
        $job_upper_age = $job_info['age_upper'];//最大年龄
        $job_work_year = intval($job_info['work_year_id']);//工作年限
        $job_allow_graduate = $job_info['allow_graduate'];//是否需要应届毕业生
        
        $service_person = new base_service_person_person();
        
        //匹配简历的工作经验
        $person = $service_person->getPerson($resume['person_id'],'person_id,user_name,sex,birthday2,start_work');
        $basic_start_work_year = base_lib_TimeUtil::date_diff_month($person['start_work']);
        $workY = floor($basic_start_work_year/12);//工作经验年份
        $workM = intval($basic_start_work_year%12);//工作经验月份
       $match_arr = array();
        //获得简历的学历
        $resume_degree = $resume['degree_id'];
        //获得简历的性别
        $resume_sex = $person['sex'];
        //获得简历的年龄
        $resume_age = base_lib_TimeUtil::ceil_diff_year($person['birthday2']);
        if(!empty($job_degree_id)){ //匹配学历
            if($resume_degree <$job_degree_id){
                $match_arr[] = "学历不匹配";//学历不匹配
            }
        }
        if(!empty($job_sex)){//匹配性别
            $enum_sex = new base_service_company_job_jobsex();
            if($job_sex !=$enum_sex->nolimit){
                if($resume_sex !=$job_sex){
                    $match_arr[] = "性别不匹配";//性别不匹配
                }
            }
        }
        $a = false;
        if(!empty($job_lower_age) && $job_lower_age !='0'){//匹配最小年龄
            if($resume_age <$job_lower_age){
               $a = true;//最小年龄不匹配
            }
        }
        if(!empty($job_upper_age) && $job_upper_age !='0'){//匹配最大年龄
            if($resume_age >$job_upper_age){
                $a = true;//最大年龄不匹配
            }
        }
        if($a){
            $match_arr[] = "年龄不匹配";
        }
        //匹配工作年限
        $w = false;
        if(!empty($job_work_year) && $job_work_year > 0){
            if($workY<=0 && $workM<=6&&$workM>=-6){ //应届毕业生
                if($job_allow_graduate ==0){//不匹配应届毕业生
                    $w = true;
                }
            }else{
                if($workY < $job_work_year){//工作年限不匹配
                    $w = true;
                }
            }
        }
        if($w){
           $match_arr[] = "工作年限不匹配";
        }
        return $match_arr;
    }
    
    // private function _checkNeedContact($memberinfo, $apply, $download, $person_id, $resume_id) {
    // 	if ($memberinfo == "notmember" && !array_key_exists($resume_id, $download))
    // 		return true;

    // 	if ($memberinfo == 'overduemember' 
    // 		&& !array_key_exists($person_id, $apply)
    // 		&& !array_key_exists($resume_id, $download))
    // 		return true;

    // 	return false;
    // }
    
    /**
     * 简历转发给同事二维码
     * @param type $inPath
     */
    public function pageSendToWorkMatePng($inPath){
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_src = base_lib_BaseUtils::getStr($path_data["src"],"string", "apply");
        $resume_id = base_lib_BaseUtils::getStr($path_data["resume_id"],"int", 0);
        $src_id = base_lib_BaseUtils::getStr($path_data["src_id"],"int", 0);
        
        $service_sqrcode_type = new base_service_common_sqrcodetype();
        
        $params = array(
            "apiname"=>"get_resume_detail",
            "type"=>$service_sqrcode_type->resume_detail,
            'resume_id'=>$resume_id,
            'resume_src'=>$resume_src
        );
        if($resume_src == "apply")
            $params["apply_id"] = $src_id;
        else if($resume_src == "invite")
            $params["invite_id"] = $src_id;
        
        ob_clean();
         //resume_src ： apply search download invite fav  
        SQrcode::png(json_encode($params));
    }
}
?>
