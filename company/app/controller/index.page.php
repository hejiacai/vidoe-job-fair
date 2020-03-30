<?php

/**
 * 新版企业招聘中心首页
 * @ClassName controller_index
 * @Desc      todo(这里用一句话描述这个类的作用)
 * @author    liuchang@huibo.com
 * @date      2014-2-17 下午02:51:51
 *
 */
class controller_index extends components_cbasepage {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		//过滤 index 首页不需要登录
		$splitFlag = preg_quote(SlightPHP::$splitFlag, "/");
		if (!empty($_SERVER["PATH_INFO"])) {
			$path_array = preg_split("/[$splitFlag\/]/", $_SERVER["PATH_INFO"], -1, PREG_SPLIT_NO_EMPTY);
		}
		$entry = !empty($path_array[2]) ? $path_array[2] : SlightPHP::$defaultEntry;
		parent::__construct(!in_array(strtolower($entry), [strtolower('MakeFeedBack'),]) ?: false);
	}


	/**
	 *  入口函数
	 */
	function pageJobList($inPath) {
		if (!$this->canDo("job_manage")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}
		
		
		$job_status = new base_service_common_jobstatus();
		
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$station = base_lib_BaseUtils::getStr($path_data['txtStation']);
		$account_id = base_lib_BaseUtils::getStr($path_data['account_id']);
		$status = base_lib_BaseUtils::getStr($path_data['status'], 'int', $job_status->pub);
		$position = base_lib_BaseUtils::getStr($path_data['position'], "string", "");
		
		//成都市场判断，如果是成都的，跳到成都职位列表
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, base_lib_BaseUtils::getCookie('accountid'));
		//企业认证信息
		$this->_aParams['audit_msg'] = $company_resources->CompanyAuditStatus();
		$companyresources = $company_resources->getCompanyServiceSource();
		
		//var_dump($companyresources);exit();
		if ($companyresources['isNewService']) {
			$this->redirect_url("/index/CdJobList", $path_data);
		}
		if ($companyresources['isCqNewService']) {
			$this->redirect_url("/index/cQJobList", $path_data);
		}
		
		//使用“分配资源”模式的子账号默认勾选我的职位
		if ($companyresources['resource_type'] == 2 && empty($position)) {
			$is_myjob = base_lib_BaseUtils::getStr($path_data['ismyjob'], 'bool', true);
		}
		else {
			$is_myjob = base_lib_BaseUtils::getStr($path_data['ismyjob'], 'bool', false);
		}
		
		
		$this->_aParams['is_myjob'] = $is_myjob;
		$this->_aParams['title'] = '汇博人才网_招聘中心';
		$this->_aParams["weekdays"] = array ("日", "一", "二", "三", "四", "五", "六");
		
		/** =============== 企业基本资料 part:start=================== **/
		$companyService = new base_service_company_company();
		$company = $companyService->getCompany($this->_userid, 1, 'company_id,company_name,is_audit,audit_state,company_flag,resume_download_upperlimit,com_level,start_time,end_time,hr_manager,hr_manager_sex,hr_tel,linkman');
		
		/** =============== 企业基本资料 part:end=================== **/
		
		/** =============== 获取招聘顾问 part:start=================== **/
		$domain = $this->GetDomainInfor();
		$companyStateService = new base_service_company_comstate();
		$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
		$hrManager = $this->GetHRManager($companyState["net_heap_id"]);
		
		//获取客服员
		// $customeruser = $this->GetCustomerService($this->_userid);
		// $this->_aParams['hasCustomer'] = false;
		// if (!is_null($customeruser)) {
		// 	$this->_aParams["hasCustomer"] = true;
		// 	$headPhoto = base_lib_BaseUtils::nullOrEmpty($customeruser["head_photo_url"]) ? $domain["defaultPhoto"] : $customeruser["head_photo_url"];
		// 	$customeruser["head_photo_url"] = $domain["image"] . "/" . $domain["photo"] . "/" . $headPhoto;
		// 	$this->_aParams["customeruser"] = $customeruser;
		// }
		
		$this->_aParams["hasHRManager"] = false;
		if (!is_null($hrManager)) {
			$this->_aParams["hasHRManager"] = true;
			$headPhoto = base_lib_BaseUtils::nullOrEmpty($hrManager["head_photo_url"]) ? $domain["defaultPhoto"] : $hrManager["head_photo_url"];
			$hrManager["head_photo_url"] = $domain["image"] . "/" . $domain["photo"] . "/" . $headPhoto;
			$this->_aParams["hrManager"] = $hrManager;
		}
		
		$xml = SXML::load('../config/config.xml');
		$this->_aParams['tel_head'] = $xml->TechniquePhone;;
		$this->_aParams['huibo400'] = $xml->HuiboPhone400;
		
		/** =============== 获取招聘顾问 part:end=================== **/
		
		
		/** =============== 单位状态 part:end=================== **/
		$account_ids = $company_resources->all_accounts;
		
		$accounts = $companyService->getCompanys($account_ids, "company_id,company_name,company_shortname,company_flag");
		$accounts = array_map(function ($v) {
			$v['company_name_display'] = $v['company_shortname'] ? $v['company_shortname'] : $v['company_name'];
			
			return $v;
		}, $accounts);
		$accounts = base_lib_BaseUtils::array_key_assoc($accounts, "company_id");
		
		// 自动刷新
		$company['isautorefresh'] = $company_resources->getCompanyServiceSource(['isautorefresh'])['isautorefresh'];
		
		$companyresources['refresh_today_overplus'] = max($companyresources['refresh_perday'] - $companyresources['refresh_today_num'], 0);
		$this->_aParams['companyresources'] = $companyresources;
		
		$this->_aParams['accounts'] = $accounts;
		$this->_aParams['hr_type'] = $company_resources->account_type;
		$this->_aParams['now_account'] = $accounts[ $account_id ];
		$this->_aParams["companyInfor"] = $company;
		// 是否是会员或者会员是否到期
		$isEnd = false;
		if (!empty($company["end_time"])) {
			$endTime = strtotime(date('Y-m-d 23:59:59', strtotime($company["end_time"])));
			if ($company["com_level"] < 1 || $endTime < time()) {
				$isEnd = true;
			}
		}
		$this->_aParams["isEnd"] = $isEnd;
		$this->_aParams['com_level'] = $company["com_level"];
		
		
		/** =============== 职位状态 part:start=================== **/
		$account_id = $account_id ? $account_id : $company_resources->all_accounts;
		
		if (!base_lib_BaseUtils::nullOrEmpty($station)) {
			$station = trim($station);
			$this->_aParams['search_keyword'] = $station;
		}
		
		$this->_aParams['use_job_status'] = $job_status->pub;
		$this->_aParams['stop_job_status'] = $job_status->stop_use;
		$job = new base_service_company_job_job();
		
		$condition_account_id = null;
		if ($is_myjob === true) {
			$condition_account_id = base_lib_BaseUtils::getCookie('accountid');
		}
		$job_list = $job->getJobList($account_id, $station, $status, 'job_id,job_flag,station,issue_time,check_state,end_time,status,order_no,urgent_end_time,auto_filter,re_apply_type,company_id,account_id,map_x,map_y,agency_state', 0, 0, null, $condition_account_id);
		/*************************** 简历几日回复 strat *****************************/
		$field = 'job_id,job_flag,station,issue_time,check_state,end_time,status,order_no,urgent_end_time,auto_filter,re_apply_type,company_id,account_id,map_x,map_y';
		$temp = $job->getMustReplyJobS($account_id, null, null, $job_status->stop_use, $field);
		
		if ($status == $job_status->pub) {
			foreach ($temp as $key => $value) {
				$temp[ $key ]['promiseStop'] = 1;
			}
			
			$job_list = array_merge($temp, $job_list);
		}
		
		/*************************** 简历几日回复 end  *****************************/
		$new_job_list = array ();
		$job_audit = new base_service_company_job_jobauditrecord();
		$job_stat = new base_service_company_stat_jobstat();
		$job_over_time_count = 0;
		
		$temp_job_list = $job->getJobList($account_id, null, $job_status->stop_use, 'company_id,job_id,station,issue_time,check_state,end_time,status,re_apply_type,company_id,account_id,map_x,map_y');
		for ($i = 0; $i < count($temp_job_list); $i++) {
			// 结束时间
			$temp_job_list[ $i ]['end_time_name'] = base_lib_TimeUtil::to_friend_time($temp_job_list[ $i ]['end_time']);
			//已结束并且结束时间至今大于5天
			$temp_job_list[ $i ]['hidden_end_time'] = (strtotime(date('Y-m-d', time())) - strtotime(date('Y-m-d', strtotime($temp_job_list[ $i ]['end_time'])))) >= 432000
				? true : false;
			$temp_job_list[ $i ]['day'] = base_lib_TimeUtil::time_diff_day($this->now(), $temp_job_list[ $i ]['end_time']);
			
			//发布时间
			$temp_job_list[ $i ]['issue_time_name'] = base_lib_TimeUtil::to_friend_time($temp_job_list[ $i ]['issue_time']);
			
			// 增加审核不通过的原因
			$audit = $job_audit->getLastAuditRecord($temp_job_list[ $i ]['company_id'], $temp_job_list[ $i ]['job_id'], 'audit_remark,click_type');
			if ($audit && $audit['click_type']) {
				$error_msg_button = array ();
				$error_button = explode(',', $audit['click_type']);
				$com_jobMsg = new base_service_common_jobMsg();
				foreach ($error_button as $msg_li) {
					$get_data = array ('mod_type' => 'edit', 'job_id' => $temp_job_list[ $i ]['job_id'], 'company_msg' => 1);
					$error_msg_button[] = $com_jobMsg->GetErrorMsgButton($msg_li, $get_data);
				}
				$error_msg_button and $temp_job_list[ $i ]['error_msg_button'] = implode('&nbsp;&nbsp;', $error_msg_button);
			}
			$temp_job_list[ $i ]['audit_remark'] = $audit ? $audit['audit_remark'] : '';
		}
		
		$temp_job_list = $this->__arraySort($temp_job_list, 'hidden_end_time', 'asc');
		$temp_job_list = array_values($temp_job_list);
		$unaudit_jobs = array ();//审核未通过的职位
		foreach ($temp_job_list as $key => $value) {
			if ($value['check_state'] == 2) {
				array_push($unaudit_jobs, $value);
			}
		}
		
		$temp_job_list = array_values($temp_job_list);
		for ($j = 0; $j < count($temp_job_list); $j++) {
			for ($k = 0; $k < count($unaudit_jobs); $k++) {
				if ($temp_job_list[ $j ]['job_id'] == $unaudit_jobs[ $k ]['job_id']) {
					array_splice($temp_job_list, $j, 1);
					$j--;
					break;
				}
			}
		}
		
		$service_jobstatus = new base_service_common_jobstatus();
		$stopuse_jobs = array ();//手动停用的职位
		foreach ($temp_job_list as $key => $value) {
			if ($value['status'] == $service_jobstatus->stop_use) {
				array_push($stopuse_jobs, $value);
			}
		}
		
		for ($j = 0; $j < count($temp_job_list); $j++) {
			for ($k = 0; $k < count($stopuse_jobs); $k++) {
				if ($temp_job_list[ $j ]['job_id'] == $stopuse_jobs[ $k ]['job_id']) {
					array_splice($temp_job_list, $j, 1);
					$j--;
					break;
				}
			}
		}
		
		$infivedaysstop_jobs = array ();//5天内过期的职位
		foreach ($temp_job_list as $key => $value) {
			if ($value['hidden_end_time'] == false) {
				array_push($infivedaysstop_jobs, $value);
			}
		}
		$job_over_time_count = count($infivedaysstop_jobs);
		
		if (!empty($job_list)) {
			$job_id_arr = base_lib_BaseUtils::getProperty($job_list, 'job_id');
			$account_id_arr = array_unique(base_lib_BaseUtils::getProperty($job_list, 'account_id'));
			$applyJobService = new base_service_company_resume_apply();
			
			
			//发布人获取
			$service_company_account = new base_service_company_account();
			$company_account_info = $service_company_account->getAccountByAccount_ids($account_id_arr, 'account_id,user_name,user_id')->items;
			$company_account_info = base_lib_BaseUtils::array_key_assoc($company_account_info, 'account_id');
			
			$statistics = $applyJobService->getApplyStatisticsByJobIdsVerson2($account_id, $job_id_arr);
			$statistics_arr = null;
			
			if (!empty($statistics) && count($statistics->items) > 0) {
				$statistics_arr = $statistics->items;
			}
			$service_top = new base_service_company_job_jobtop();
			for ($i = 0; $i < sizeof($job_list); $i++) {
				if (!empty($statistics_arr)) {
					$stat = $this->arrayFind($statistics_arr, "job_id", $job_list[ $i ]['job_id']);
				}
				
				$job_list[ $i ]['account_user_name'] = $company_account_info[ $job_list[ $i ]['account_id'] ]['user_name'] ? $company_account_info[ $job_list[ $i ]['account_id'] ]['user_name'] : $company['linkman'];//这里就用账户名
				
				$job_list[ $i ]['applyCount'] = 0;
				$job_list[ $i ]['applyNotReadCount'] = 0;
				$job_list[ $i ] ['no_reply_num'] = 0;//未处理简历投递个数
				$job_list[ $i ] ['wait_deal_num'] = 0;//待定的简历
				if (!empty($stat)) {
					$job_list[ $i ]['applyCount'] = $stat['total_count'];
					$job_list[ $i ]['applyNotReadCount'] = $stat['not_read_count'];
					$job_list[ $i ]['no_reply_num'] = $stat['no_reply_num'];
					$job_list[ $i ]['wait_deal_num'] = $stat['wait_deal_num'];
				}
				
				// 职位链接
				$job_list[ $i ]['job_link'] = base_lib_Rewrite::job($job_list[ $i ]['job_id'], $job_list[ $i ]['job_flag']);
				
				// 结束时间
				$job_list[ $i ]['end_time_name'] = base_lib_TimeUtil::to_friend_time($job_list[ $i ] ['end_time']);
				
				// 已结束并且结束时间至今大于5天
				$job_list[ $i ]['hidden_end_time'] = (strtotime(date('Y-m-d', time())) - strtotime(date('Y-m-d', strtotime($job_list[ $i ]['end_time'])))) >= 432000 ? true : false;
				$job_list[ $i ]['day'] = base_lib_TimeUtil::time_diff_day($this->now(), $job_list[ $i ]['end_time']);
				
				// 发布时间
				$job_list[ $i ]['issue_time_name'] = base_lib_TimeUtil::to_friend_time($job_list[ $i ]['issue_time']);
				
				// 代招
				$job_list[ $i ]['generation_bidding'] = $job_list[ $i ]['company_id'] == $this->_userid ? false : true;
				
				// 增加审核不通过的原因
				$audit = $job_audit->getLastAuditRecord($job_list[ $i ]['company_id'], $job_list[ $i ]['job_id'], 'audit_remark,click_type');
				$job_list[ $i ]['audit_remark'] = $audit ? $audit['audit_remark'] : '';
				if ($audit && $audit['click_type']) {
					$error_msg_button = array ();
					$error_button = explode(',', $audit['click_type']);
					$com_jobMsg = new base_service_common_jobMsg();
					foreach ($error_button as $msg_li) {
						$get_data = array ('mod_type' => 'edit', 'job_id' => $job_list[ $i ]['job_id'], 'company_msg' => 1);
						$error_msg_button[] = $com_jobMsg->GetErrorMsgButton($msg_li, $get_data);
					}
					$error_msg_button and $job_list[ $i ]['error_msg_button'] = implode('&nbsp;&nbsp;', $error_msg_button);
				}
				
				if ($job_list[ $i ] ['urgent_end_time'] > 0) {
					$interval_time = (strtotime(date('Y-m-d', $job_list[ $i ]['urgent_end_time']))) - (strtotime(date('Y-m-d', time())));
					if ($interval_time >= 0) {
						$job_list[ $i ]['urgent_mark'] = true;
						$job_list[ $i ]['urgent_end_date'] = date('Y年m月d日', $job_list[ $i ]['urgent_end_time']);
					}
				}
				
				$toplist = $service_top->getList($service_id, $company_id, $job_list[ $i ]['job_id'], "id");
				if (count($toplist->items)) {
					$job_list[ $i ]['top_mark'] = true;
					$job_list[ $i ]['top_count'] = count($toplist->items);
				}
				
				$max_str_len = 24;
				if ($job_list[ $i ]['urgent_mark'] == true && $job_list[ $i ]['check_state'] == 4) {
					$max_str_len = 18;
				}
				elseif ($job_list[ $i ]['urgent_mark'] == true) {
					$max_str_len = 20;
				}
				elseif ($job_list[ $i ]['check_state'] == 4) {
					$max_str_len = 20;
				}
				
				$station_len = 0;
				$sub_station = '';
				$need_ellipsis = false;
				
				for ($j2 = 0; $j2 < mb_strlen($job_list[ $i ]['station']); $j2++) {
					if (!base_lib_BaseUtils::nullOrEmpty($sub_station)) {
						$sub_station .= '...';
						break;
					}
					if (preg_match("/[\x7f-\xff]/", mb_substr($job_list[ $i ]['station'], $j2, 1))) {
						$station_len += 2;
					}
					else {
						$station_len += 1;
					}
					if ($station_len >= $max_str_len) {
						$sub_station = mb_substr($job_list[ $i ]['station'], 0, $j2);
					}
				}
				if (!base_lib_BaseUtils::nullOrEmpty($sub_station)) {
					$job_list[ $i ]['sub_station'] = $sub_station;
				}
				else {
					$job_list[ $i ]['sub_station'] = $job_list[ $i ]['station'];
				}
			}
			
			if ($status == $job_status->pub) {
				$max_order_no = max(base_lib_BaseUtils::getProperty($job_list, 'order_no'));
				
				/*** 简历几日回复重新排序 start (优先级：承诺停招的-> 承诺再招的 -> 普通再招的)****/
				$temp_list = array ();//var_dump($job_list);
				$a_count = 0;
				$promiseSize = 0;
				foreach ($job_list as $key => $value) {
					$job_list[ $key ]['sub_station'] = base_lib_BaseUtils::cutstr($value['sub_station'], 16, 'utf-8', '', '...');
					if (($value['promiseStop'] == 1) && ($value['no_reply_num'] == 0)) {
						$a_count += 1;
						unset($job_list[ $key ]);
					}
					else {
						if (($value['promiseStop'] == 1) && ($value['re_apply_type'] != 0)) {
							$job_list[ $key ]['my_sort'] = $max_order_no + 2;
							$promiseSize += 1;
						}
						elseif ($value['re_apply_type'] != 0) {
							$job_list[ $key ]['my_sort'] = $max_order_no + 1;
						}
						else {
							$job_list[ $key ]['my_sort'] = $value['order_no'];
						}
					}
				}
				$this->_aParams['promiseStopSize'] = $promiseSize;
				
				$job_list = array_values($job_list);
				$job_list = base_lib_BaseUtils::multi_array_sort($job_list, 'my_sort', SORT_DESC);
				
				/*** 简历几日回复重新排序 end ****/
				$this->_aParams['job_list'] = $job_list;
			}
			else {
				$job_list = $this->__arraySort($job_list, 'hidden_end_time', 'asc');
				$job_list = array_values($job_list);
				$unaudit_jobs = array ();//审核未通过的职位
				foreach ($job_list as $key => $value) {
					if ($value['check_state'] == 2) {
						array_push($unaudit_jobs, $value);
					}
				}
				
				$job_list = array_values($job_list);
				for ($j = 0; $j < count($job_list); $j++) {
					for ($k = 0; $k < count($unaudit_jobs); $k++) {
						if ($job_list[ $j ]['job_id'] == $unaudit_jobs[ $k ]['job_id']) {
							array_splice($job_list, $j, 1);
							$j--;
							break;
						}
					}
				}
				
				$service_jobstatus = new base_service_common_jobstatus();
				$stopuse_jobs = array ();//手动停用的职位
				foreach ($job_list as $key => $value) {
					if ($value['status'] == $service_jobstatus->stop_use) {
						array_push($stopuse_jobs, $value);
					}
				}
				
				for ($j = 0; $j < count($job_list); $j++) {
					for ($k = 0; $k < count($stopuse_jobs); $k++) {
						if ($job_list[ $j ]['job_id'] == $stopuse_jobs[ $k ]['job_id']) {
							array_splice($job_list, $j, 1);
							$j--;
							break;
						}
					}
				}
				
				$infivedaysstop_jobs = array ();//5天内过期的职位
				foreach ($job_list as $key => $value) {
					if ($value['hidden_end_time'] == false) {
						array_push($infivedaysstop_jobs, $value);
					}
				}
				for ($j = 0; $j < count($job_list); $j++) {
					for ($k = 0; $k < count($infivedaysstop_jobs); $k++) {
						if ($job_list[ $j ]['job_id'] == $infivedaysstop_jobs[ $k ]['job_id']) {
							array_splice($job_list, $j, 1);
							$j--;
							break;
						}
					}
				}
				
				$infivedaysstop_jobs = $this->__arraySort($infivedaysstop_jobs, 'end_time', 'desc');
				foreach ($infivedaysstop_jobs as $k => $v) {
					array_push($new_job_list, $v);
				}
				foreach ($unaudit_jobs as $k => $v) {
					array_push($new_job_list, $v);
				}
				$stopuse_jobs = $this->__arraySort($stopuse_jobs, 'end_time', 'desc');
				foreach ($stopuse_jobs as $k => $v) {
					array_push($new_job_list, $v);
				}
				$job_list = $this->__arraySort($job_list, 'end_time', 'desc');
				foreach ($job_list as $k => $v) {
					array_push($new_job_list, $v);
				}
				
				//停招的职位中剔除承诺职位
				$filterjobids = base_lib_BaseUtils::getProperty($temp, 'job_id'); //var_dump($filterjobids);
				$f_count = count($temp);
				foreach ($new_job_list as $key => $value) {
					if (in_array($value['job_id'], $filterjobids)) {
						unset($new_job_list[ $key ]);
					}
				}
				$this->_aParams['job_list'] = array_values($new_job_list);
			}
		}
		
		$use_job_count = 0;
		$stop_job_count = 0;
		if ($status == $job_status->pub) {
			$use_job_count = count($job_list);
			$stop_job_count = ($job->getJobCount($account_id, $job_status->stop_use) - count($temp) + $a_count);
			
		}
		else {
			$use_job_count = ($job->getJobCount($account_id, $job_status->pub) + $f_count);
			$stop_job_count = count($new_job_list);
		}
		
		$this->_aParams['job_over_time_count'] = $job_over_time_count;
		$this->_aParams['use_job_count'] = $use_job_count;
		$this->_aParams['stop_job_count'] = $stop_job_count;
		$this->_aParams['station'] = $station;
		$this->_aParams['status'] = $status;
		
		/** =============== 刷新时间 part:start=================== **/
		$service_refresh = new base_service_company_refresh();
		$refresh_info = $service_refresh->getRefresh($this->_userid, "is_auto,auto_start_time,auto_end_time,auto_refreshtime,last_refresh_time");
		$is_auto = 1;
		if ($refresh_info['is_auto']) {
			if ($refresh_info['auto_start_time'] <= time() && $refresh_info['auto_end_time'] >= time()) {
				$is_auto = 0;
			}
		}
		if ($refresh_info['auto_refreshtime'] > 0) {
			$this->_aParams['autorefreshtime'] = date("m-d H:i", $refresh_info['auto_refreshtime']);
		}
		
		$this->_aParams['is_auto'] = $is_auto;
		if ($refresh_info['last_refresh_time'] > 0) {
			$this->_aParams['refresh_time'] = base_lib_TimeUtil::to_friend_time(date("Y-m-d H:i:s", $refresh_info['last_refresh_time']));
			$this->_aParams['refresh_left'] = base_lib_TimeUtil::time_diff(date("Y-m-d H:i:s", $refresh_info['last_refresh_time']), date("Y-m-d 00:00:00", strtotime("tomorrow")));
		}
		
		/** =============== 刷新时间 part:end=================== **/
		$this->_aParams['total_urgent_count'] = $the_urgent_point;
		$this->_aParams['ser_resume_num'] = $ser_resume_num;
		
		
		if ($status == $job_status->pub) {
			self::spreadJobStatistics($job_list);
			$outer_job_service = new base_service_outer_outerjob();
			$this->_aParams['importData'] = $outer_job_service->is_import($this->_userid, true);
			
			return $this->render('joblist.html', $this->_aParams);
		}
		else {
			return $this->render('stopjoblist.html', $this->_aParams);
		}
	}
	
	/**
	 *  入口函数
	 */
	function pagecQJobList($inPath) {
		if (!$this->canDo("job_manage")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}
		

		$job_status = new base_service_common_jobstatus();
		
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$station = base_lib_BaseUtils::getStr($path_data['txtStation']);
		$account_id = base_lib_BaseUtils::getStr($path_data['account_id']);
		$status = base_lib_BaseUtils::getStr($path_data['status'], 'int', $job_status->pub);
		$position = base_lib_BaseUtils::getStr($path_data['position'], "string", "");
		//成都市场判断，如果是成都的，跳到成都职位列表
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, base_lib_BaseUtils::getCookie('accountid'));
		//企业认证信息
		$this->_aParams['audit_msg'] = $company_resources->CompanyAuditStatus();
		$companyresources = $company_resources->getCompanyServiceSource(['account_resource']);
		
		//使用“分配资源”模式的子账号默认勾选我的职位
		if ($companyresources['resource_type'] == 2 && empty($position)) {
			$is_myjob = base_lib_BaseUtils::getStr($path_data['ismyjob'], 'bool', true);
		}
		else {
			$is_myjob = base_lib_BaseUtils::getStr($path_data['ismyjob'], 'bool', false);
		}
		
		$this->_aParams['is_myjob'] = $is_myjob;
		$this->_aParams['title'] = '汇博人才网_招聘中心';
		$this->_aParams["weekdays"] = array ("日", "一", "二", "三", "四", "五", "六");
		
		/** =============== 企业基本资料 part:start=================== **/
		$companyService = new base_service_company_company();
		$company = $companyService->getCompany($this->_userid, 1, 'company_id,company_name,is_audit,audit_state,company_flag,resume_download_upperlimit,com_level,start_time,end_time,hr_manager,hr_manager_sex,hr_tel,linkman,site_type');
		
		/** =============== 企业基本资料 part:end=================== **/
		
		/** =============== 获取招聘顾问 part:start=================== **/
		$domain = $this->GetDomainInfor();
		$companyStateService = new base_service_company_comstate();
		$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
		$hrManager = $this->GetHRManager($companyState["net_heap_id"]);
		
		
		$this->_aParams["hasHRManager"] = false;
		if (!is_null($hrManager)) {
			$this->_aParams["hasHRManager"] = true;
			$headPhoto = base_lib_BaseUtils::nullOrEmpty($hrManager["head_photo_url"]) ? $domain["defaultPhoto"] : $hrManager["head_photo_url"];
			$hrManager["head_photo_url"] = $domain["image"] . "/" . $domain["photo"] . "/" . $headPhoto;
			$this->_aParams["hrManager"] = $hrManager;
		}
		
		$xml = SXML::load('../config/config.xml');
		$this->_aParams['tel_head'] = $xml->TechniquePhone;;
		$this->_aParams['huibo400'] = $xml->HuiboPhone400;
		
		/** =============== 获取招聘顾问 part:end=================== **/
		
		/** =============== 单位状态 part:end=================== **/
		$account_ids = $company_resources->all_accounts;
		
		$accounts = $companyService->getCompanys($account_ids, "company_id,company_name,company_shortname,company_flag");
		$accounts = array_map(function ($v) {
			$v['company_name_display'] = $v['company_shortname'] ? $v['company_shortname'] : $v['company_name'];
			
			return $v;
		}, $accounts);
		$accounts = base_lib_BaseUtils::array_key_assoc($accounts, "company_id");
		
		
		$this->_aParams['companyresources'] = $companyresources;
		$this->_aParams['accounts'] = $accounts;
		$this->_aParams['hr_type'] = $company_resources->account_type;
		$this->_aParams['now_account'] = $accounts[ $account_id ];
		$this->_aParams["companyInfor"] = $company;
		// 是否是会员或者会员是否到期
		$isEnd = false;
		if (!empty($company["end_time"])) {
			$endTime = strtotime(date('Y-m-d 23:59:59', strtotime($company["end_time"])));
			if ($company["com_level"] < 1 || $endTime < time()) {
				$isEnd = true;
			}
		}
		$this->_aParams["isEnd"] = $isEnd;
		$this->_aParams['com_level'] = $company["com_level"];
		
		
		/** =============== 职位状态 part:start=================== **/
		$account_id = $account_id ? $account_id : $company_resources->all_accounts;
		
		if (!base_lib_BaseUtils::nullOrEmpty($station)) {
			$station = trim($station);
			$this->_aParams['search_keyword'] = $station;
		}
		
		$this->_aParams['use_job_status'] = $job_status->pub;
		$this->_aParams['stop_job_status'] = $job_status->stop_use;
		$job = new base_service_company_job_job();
		
		$condition_account_id = null;
		if ($is_myjob === true) {
			$condition_account_id = base_lib_BaseUtils::getCookie('accountid');
		}
		$job_list = $job->getJobList($account_id, $station, $status, 'job_id,job_flag,station,issue_time,check_state,end_time,status,order_no,urgent_end_time,auto_filter,re_apply_type,company_id,account_id,map_x,map_y,agency_state,refresh_time', 0, 0, null, $condition_account_id);
		/*************************** 简历几日回复 strat *****************************/
		$field = 'job_id,job_flag,station,issue_time,check_state,refresh_time,end_time,status,order_no,urgent_end_time,auto_filter,re_apply_type,company_id,account_id,map_x,map_y';
		$temp = $job->getMustReplyJobS($account_id, null, null, $job_status->stop_use, $field);
		
		if ($status == $job_status->pub) {
			foreach ($temp as $key => $value) {
				$temp[ $key ]['promiseStop'] = 1;
			}
			
			$job_list = array_merge($temp, $job_list);
		}
		
		/*************************** 简历几日回复 end  *****************************/
		$new_job_list = array ();
		$job_audit = new base_service_company_job_jobauditrecord();
		$job_stat = new base_service_company_stat_jobstat();
		$job_over_time_count = 0;

		$temp_job_list = $job->getJobList($account_id, null, $job_status->stop_use, 'company_id,job_id,station,issue_time,check_state,end_time,status,re_apply_type,company_id,account_id,map_x,map_y');
		for ($i = 0; $i < count($temp_job_list); $i++) {
			// 结束时间
			$temp_job_list[ $i ]['end_time_name'] = base_lib_TimeUtil::to_friend_time($temp_job_list[ $i ]['end_time']);
			//已结束并且结束时间至今大于5天
			$temp_job_list[ $i ]['hidden_end_time'] = (strtotime(date('Y-m-d', time())) - strtotime(date('Y-m-d', strtotime($temp_job_list[ $i ]['end_time'])))) >= 432000
				? true : false;
			$temp_job_list[ $i ]['day'] = base_lib_TimeUtil::time_diff_day($this->now(), $temp_job_list[ $i ]['end_time']);
			
			//发布时间
			$temp_job_list[ $i ]['issue_time_name'] = base_lib_TimeUtil::to_friend_time($temp_job_list[ $i ]['issue_time']);
			
			// 增加审核不通过的原因
			$audit = $job_audit->getLastAuditRecord($temp_job_list[ $i ]['company_id'], $temp_job_list[ $i ]['job_id'], 'audit_remark,click_type');
			if ($audit && $audit['click_type']) {
				$error_msg_button = array ();
				$error_button = explode(',', $audit['click_type']);
				$com_jobMsg = new base_service_common_jobMsg();
				foreach ($error_button as $msg_li) {
					$get_data = array ('mod_type' => 'edit', 'job_id' => $temp_job_list[ $i ]['job_id'], 'company_msg' => 1);
					$error_msg_button[] = $com_jobMsg->GetErrorMsgButton($msg_li, $get_data);
				}
				$error_msg_button and $temp_job_list[ $i ]['error_msg_button'] = implode('&nbsp;&nbsp;', $error_msg_button);
			}
			$temp_job_list[ $i ]['audit_remark'] = $audit ? $audit['audit_remark'] : '';
		}
		
		$temp_job_list = $this->__arraySort($temp_job_list, 'hidden_end_time', 'asc');
		$temp_job_list = array_values($temp_job_list);
		$unaudit_jobs = array ();//审核未通过的职位
		foreach ($temp_job_list as $key => $value) {
			if ($value['check_state'] == 2) {
				array_push($unaudit_jobs, $value);
			}
		}
		
		$temp_job_list = array_values($temp_job_list);
		for ($j = 0; $j < count($temp_job_list); $j++) {
			for ($k = 0; $k < count($unaudit_jobs); $k++) {
				if ($temp_job_list[ $j ]['job_id'] == $unaudit_jobs[ $k ]['job_id']) {
					array_splice($temp_job_list, $j, 1);
					$j--;
					break;
				}
			}
		}
		
		$service_jobstatus = new base_service_common_jobstatus();
		$stopuse_jobs = array ();//手动停用的职位
		foreach ($temp_job_list as $key => $value) {
			if ($value['status'] == $service_jobstatus->stop_use) {
				array_push($stopuse_jobs, $value);
			}
		}
		
		for ($j = 0; $j < count($temp_job_list); $j++) {
			for ($k = 0; $k < count($stopuse_jobs); $k++) {
				if ($temp_job_list[ $j ]['job_id'] == $stopuse_jobs[ $k ]['job_id']) {
					array_splice($temp_job_list, $j, 1);
					$j--;
					break;
				}
			}
		}
		
		$infivedaysstop_jobs = array ();//5天内过期的职位
		foreach ($temp_job_list as $key => $value) {
			if ($value['hidden_end_time'] == false) {
				array_push($infivedaysstop_jobs, $value);
			}
		}
		$job_over_time_count = count($infivedaysstop_jobs);
		
		if (!empty($job_list)) {
			$job_id_arr = base_lib_BaseUtils::getProperty($job_list, 'job_id');
			$account_id_arr = array_unique(base_lib_BaseUtils::getProperty($job_list, 'account_id'));
			$applyJobService = new base_service_company_resume_apply();
			
			
			//发布人获取
			$service_company_account = new base_service_company_account();
			$company_account_info = $service_company_account->getAccountByAccount_ids($account_id_arr, 'account_id,user_name,user_id')->items;
			$company_account_info = base_lib_BaseUtils::array_key_assoc($company_account_info, 'account_id');
			
			$statistics = $applyJobService->getApplyStatisticsByJobIdsVerson2($account_id, $job_id_arr);
			$statistics_arr = null;
			
			if (!empty($statistics) && count($statistics->items) > 0) {
				$statistics_arr = $statistics->items;
			}
			$service_top = new base_service_company_job_jobtop();
			
			//1.获取所有的job_id
			$pub_job_id = base_lib_BaseUtils::getProperty($job_list, "job_id");
			
			//2.再获取职位的精品信息
			$service_company_job_quality = new base_service_company_job_quality();
			$job_quality_list = $service_company_job_quality->getJobQulityByJobId($pub_job_id)->items;
			$job_quality_list = base_lib_BaseUtils::array_key_assoc($job_quality_list, "job_id");
			
			//职位免费刷新次数
			$service_refresh_log = new base_service_company_refreshlog();
			
			$company_account_info = $service_company_account->getAccountList($this->_userid, 'account_id,user_id,user_name,head_photo,station,resource_type')->items;
			$assoc_accounts = base_lib_BaseUtils::array_key_assoc($company_account_info, 'account_id');
			$this->_aParams['company_account_info'] = $assoc_accounts;
			
			$a_list = $service_company_account->getAccountByAccount_ids(base_lib_BaseUtils::getProperty($job_list, 'account_id'), 'account_id,user_id,user_name,head_photo,station');
			$a_list = base_lib_BaseUtils::array_key_assoc($a_list->items, 'account_id');
			$fields = 'account_id,company_id,is_main,user_id,user_name,mobile_phone,link_tel,station,head_photo,last_login_time,state,resource_type';
			$accountid = base_lib_BaseUtils::getCookie('accountid');
			$account = $service_company_account->getAccount($accountid, $fields);
			
			for ($i = 0; $i < sizeof($job_list); $i++) {
				if (!empty($statistics_arr)) {
					$stat = $this->arrayFind($statistics_arr, "job_id", $job_list[ $i ]['job_id']);
				}
				
				$job_list[ $i ]['can_do'] = ($assoc_accounts[ $job_list[ $i ]['account_id'] ]['resource_type'] == 1 && $account['resource_type'] == 1) || ($accountid == $job_list[ $i ]['account_id']) ? 1 : 2;  //只有共享模式的帐号可以相互操作功能
				//招聘人
				$job_list[ $i ]['job_account_resource_type'] = $assoc_accounts[ $job_list[ $i ]['account_id'] ]['resource_type'];
				
				$job_list[ $i ]['account_user_name'] = $a_list[ $job_list[ $i ]['account_id'] ]['user_name'] ? $a_list[ $job_list[ $i ]['account_id'] ]['user_name'] : $company['linkman'];//这里就用账户名
				
				
				$job_list[ $i ]['applyCount'] = 0;
				$job_list[ $i ]['applyNotReadCount'] = 0;
				$job_list[ $i ] ['no_reply_num'] = 0;//未处理简历投递个数
				$job_list[ $i ] ['wait_deal_num'] = 0;//待定的简历
				if (!empty($stat)) {
					$job_list[ $i ]['applyCount'] = $stat['total_count'];
					$job_list[ $i ]['applyNotReadCount'] = $stat['not_read_count'];
					$job_list[ $i ]['no_reply_num'] = $stat['no_reply_num'];
					$job_list[ $i ]['wait_deal_num'] = $stat['wait_deal_num'];
				}
				
				// 职位链接
				$job_list[ $i ]['job_link'] = base_lib_Rewrite::job($job_list[ $i ]['job_id'], $job_list[ $i ]['job_flag']);
				
				// 结束时间
				$job_list[ $i ]['end_time_name'] = base_lib_TimeUtil::to_friend_time($job_list[ $i ] ['end_time']);
				
				// 已结束并且结束时间至今大于5天
				$job_list[ $i ]['hidden_end_time'] = (strtotime(date('Y-m-d', time())) - strtotime(date('Y-m-d', strtotime($job_list[ $i ]['end_time'])))) >= 432000 ? true : false;
				$job_list[ $i ]['day'] = base_lib_TimeUtil::time_diff_day($this->now(), $job_list[ $i ]['end_time']);
				
				// 发布时间
				$job_list[ $i ]['issue_time_name'] = base_lib_TimeUtil::to_friend_time($job_list[ $i ]['issue_time']);
				
				// 代招
				$job_list[ $i ]['generation_bidding'] = $job_list[ $i ]['company_id'] == $this->_userid ? false : true;
				
				// 增加审核不通过的原因
				$audit = $job_audit->getLastAuditRecord($job_list[ $i ]['company_id'], $job_list[ $i ]['job_id'], 'audit_remark,click_type');
				$job_list[ $i ]['audit_remark'] = $audit ? $audit['audit_remark'] : '';
				if ($audit && $audit['click_type']) {
					$error_msg_button = array ();
					$error_button = explode(',', $audit['click_type']);
					$com_jobMsg = new base_service_common_jobMsg();
					foreach ($error_button as $msg_li) {
						$get_data = array ('mod_type' => 'edit', 'job_id' => $job_list[ $i ]['job_id'], 'company_msg' => 1);
						$error_msg_button[] = $com_jobMsg->GetErrorMsgButton($msg_li, $get_data);
					}
					$error_msg_button and $job_list[ $i ]['error_msg_button'] = implode('&nbsp;&nbsp;', $error_msg_button);
				}
				
				if ($job_list[ $i ] ['urgent_end_time'] > 0) {
					$interval_time = (strtotime(date('Y-m-d', $job_list[ $i ]['urgent_end_time']))) - (strtotime(date('Y-m-d', time())));
					if ($interval_time >= 0) {
						$job_list[ $i ]['urgent_mark'] = true;
						$job_list[ $i ]['urgent_end_date'] = date('Y年m月d日', $job_list[ $i ]['urgent_end_time']);
					}
				}
				
				$toplist = $service_top->getList($service_id, $company_id, $job_list[ $i ]['job_id'], "id");
				if (count($toplist->items)) {
					$job_list[ $i ]['top_mark'] = true;
					$job_list[ $i ]['top_count'] = count($toplist->items);
				}
				
				$max_str_len = 24;
				if ($job_list[ $i ]['urgent_mark'] == true && $job_list[ $i ]['check_state'] == 4) {
					$max_str_len = 18;
				}
				elseif ($job_list[ $i ]['urgent_mark'] == true) {
					$max_str_len = 20;
				}
				elseif ($job_list[ $i ]['check_state'] == 4) {
					$max_str_len = 20;
				}
				
				$station_len = 0;
				$sub_station = '';
				$need_ellipsis = false;
				
				for ($j2 = 0; $j2 < mb_strlen($job_list[ $i ]['station']); $j2++) {
					if (!base_lib_BaseUtils::nullOrEmpty($sub_station)) {
						$sub_station .= '...';
						break;
					}
					if (preg_match("/[\x7f-\xff]/", mb_substr($job_list[ $i ]['station'], $j2, 1))) {
						$station_len += 2;
					}
					else {
						$station_len += 1;
					}
					if ($station_len >= $max_str_len) {
						$sub_station = mb_substr($job_list[ $i ]['station'], 0, $j2);
					}
				}
				if (!base_lib_BaseUtils::nullOrEmpty($sub_station)) {
					$job_list[ $i ]['sub_station'] = $sub_station;
				}
				else {
					$job_list[ $i ]['sub_station'] = $job_list[ $i ]['station'];
				}
			}
			
			if ($status == $job_status->pub) {
				$max_order_no = max(base_lib_BaseUtils::getProperty($job_list, 'order_no'));
				
				/*** 简历几日回复重新排序 start (优先级：承诺停招的-> 承诺再招的 -> 普通再招的)****/
				$temp_list = array ();//var_dump($job_list);
				$a_count = 0;
				$promiseSize = 0;
				$last_refresh_time = '';
				foreach ($job_list as $key => $value) {
					$job_list[ $key ]['sub_station'] = base_lib_BaseUtils::cutstr($value['sub_station'], 16, 'utf-8', '', '...');
					if (($value['promiseStop'] == 1) && ($value['no_reply_num'] == 0)) {
						$a_count += 1;
						unset($job_list[ $key ]);
					}
					else {
						if (($value['re_apply_type'] != 0)) {
							//已承诺
							if ($value['promiseStop'] == 1) {
								//已停招
								$job_list[ $key ]['my_sort'] = $max_order_no + 4;
								$promiseSize += 1;
							}
							else {
								//未停招
								if ($value['check_state'] != 4 && $value['refresh_time'] == '2014-01-01 00:00:00') {
									//未刷新
									$job_list[ $key ]['my_sort'] = $max_order_no + 3;
								}
								else {
									//已刷新
									$job_list[ $key ]['my_sort'] = $max_order_no + 1;
								}
							}
							
						}
						else {
							//未承诺
							if ($value['check_state'] != 4 && $value['refresh_time'] == '2014-01-01 00:00:00') {
								//未刷新
								$job_list[ $key ]['my_sort'] = $max_order_no + 2;
							}
							else {
								//已刷新
								$job_list[ $key ]['my_sort'] = $value['order_no'];
							}
						}
						
					}
					
					
					/**
					 * 刷新时间
					 * 精确到分钟
					 */
					if ($value['refresh_time'] > $last_refresh_time) {
						$last_refresh_time = $value['refresh_time'];
					}

					$refresh_time = base_lib_TimeUtil::time_diff($value['refresh_time'], date("Y-m-d H:i:s"));

					if ($refresh_time['day'] <= 0 && $refresh_time['day'] >= -3) {
						if ($refresh_time['day'] == 0) {
							if ($refresh_time['hour'] == 0) {
								if ($refresh_time['min'] == 0) {
									$refresh_time = "刚刚";
								}
								else {
									$refresh_time = "{$refresh_time['min']}分钟前";
								}
							}
							else {
								$refresh_time = "{$refresh_time['hour']}小时前";
							}
							
						}
						else {
							$refresh_time = "{$refresh_time['day']}天前";
						}
					}
					else {
						$refresh_time = date("m-d", strtotime($value['refresh_time']));
					}
					//不是审核中
					if ($value['check_state'] != 4) {
						if ($value['refresh_time'] == '2014-01-01 00:00:00') {
							$job_list[ $key ]['is_not_refresh'] = true;
							$refresh_time = "未刷新";
						}
					}
					else {
						if ($value['refresh_time'] == '2014-01-01 00:00:00') {
							$refresh_time = "&nbsp&nbsp";
						}
					}
					
					$job_list[ $key ]['refresh_time'] = $refresh_time;
				}

				if (strtotime($last_refresh_time) > 0) {
					$this->_aParams['refresh_time'] = base_lib_TimeUtil::to_friend_time($last_refresh_time);
				}
				$this->_aParams['promiseStopSize'] = $promiseSize;
				
				$job_list = array_values($job_list);
				$job_list = base_lib_BaseUtils::multi_array_sort($job_list, 'my_sort', SORT_DESC);
				
				/*** 简历几日回复重新排序 end ****/
				$this->_aParams['job_list'] = $job_list;
			}
			else {
				$job_list = $this->__arraySort($job_list, 'hidden_end_time', 'asc');
				$job_list = array_values($job_list);
				$unaudit_jobs = array ();//审核未通过的职位
				foreach ($job_list as $key => $value) {
					if ($value['check_state'] == 2) {
						array_push($unaudit_jobs, $value);
					}
				}
				
				$job_list = array_values($job_list);
				for ($j = 0; $j < count($job_list); $j++) {
					for ($k = 0; $k < count($unaudit_jobs); $k++) {
						if ($job_list[ $j ]['job_id'] == $unaudit_jobs[ $k ]['job_id']) {
							array_splice($job_list, $j, 1);
							$j--;
							break;
						}
					}
				}
				
				$service_jobstatus = new base_service_common_jobstatus();
				$stopuse_jobs = array ();//手动停用的职位
				foreach ($job_list as $key => $value) {
					if ($value['status'] == $service_jobstatus->stop_use) {
						array_push($stopuse_jobs, $value);
					}
				}
				
				for ($j = 0; $j < count($job_list); $j++) {
					for ($k = 0; $k < count($stopuse_jobs); $k++) {
						if ($job_list[ $j ]['job_id'] == $stopuse_jobs[ $k ]['job_id']) {
							array_splice($job_list, $j, 1);
							$j--;
							break;
						}
					}
				}
				
				$infivedaysstop_jobs = array ();//5天内过期的职位
				foreach ($job_list as $key => $value) {
					if ($value['hidden_end_time'] == false) {
						array_push($infivedaysstop_jobs, $value);
					}
				}
				for ($j = 0; $j < count($job_list); $j++) {
					for ($k = 0; $k < count($infivedaysstop_jobs); $k++) {
						if ($job_list[ $j ]['job_id'] == $infivedaysstop_jobs[ $k ]['job_id']) {
							array_splice($job_list, $j, 1);
							$j--;
							break;
						}
					}
				}
				
				$infivedaysstop_jobs = $this->__arraySort($infivedaysstop_jobs, 'end_time', 'desc');
				foreach ($infivedaysstop_jobs as $k => $v) {
					array_push($new_job_list, $v);
				}
				foreach ($unaudit_jobs as $k => $v) {
					array_push($new_job_list, $v);
				}
				$stopuse_jobs = $this->__arraySort($stopuse_jobs, 'end_time', 'desc');
				foreach ($stopuse_jobs as $k => $v) {
					array_push($new_job_list, $v);
				}
				$job_list = $this->__arraySort($job_list, 'end_time', 'desc');
				foreach ($job_list as $k => $v) {
					array_push($new_job_list, $v);
				}
				
				//停招的职位中剔除承诺职位
				$filterjobids = base_lib_BaseUtils::getProperty($temp, 'job_id'); //var_dump($filterjobids);
				$f_count = count($temp);
				foreach ($new_job_list as $key => $value) {
					if (in_array($value['job_id'], $filterjobids)) {
						unset($new_job_list[ $key ]);
					}
				}
				$this->_aParams['job_list'] = array_values($new_job_list);
			}
		}
		
		$use_job_count = 0;
		$stop_job_count = 0;
		if ($status == $job_status->pub) {
			$use_job_count = count($job_list);
			if ($condition_account_id) {
				$stop_job_count = ($job->getJobCount($account_id, $job_status->stop_use, array ($condition_account_id)) - count($temp) + $a_count);
			}
			else {
				$stop_job_count = ($job->getJobCount($account_id, $job_status->stop_use) - count($temp) + $a_count);
			}
			
			
		}
		else {
			if ($condition_account_id) {
				$use_job_count = ($job->getJobCount($account_id, $job_status->pub, array ($condition_account_id)) + $f_count);
			}
			else {
				$use_job_count = ($job->getJobCount($account_id, $job_status->pub) + $f_count);
			}
			
			$stop_job_count = count($new_job_list);
		}
		
		$this->_aParams['job_over_time_count'] = $job_over_time_count;
		$this->_aParams['use_job_count'] = $use_job_count;
		$this->_aParams['stop_job_count'] = $stop_job_count;
		$this->_aParams['station'] = $station;
		$this->_aParams['status'] = $status;
		
		
		/** =============== 刷新时间 part:end=================== **/
		$this->_aParams['total_urgent_count'] = $the_urgent_point;
		$this->_aParams['ser_resume_num'] = $ser_resume_num;
		
		
		if ($status == $job_status->pub) {
			self::spreadJobStatistics($job_list);
			$outer_job_service = new base_service_outer_outerjob();
			$this->_aParams['importData'] = $outer_job_service->is_import($this->_userid, true);
			
			return $this->render('joblist_v2.html', $this->_aParams);
		}
		else {
			return $this->render('stopjoblist.html', $this->_aParams);
		}
	}
	
	
	/**
	 *  成都职位管理列表
	 */
	function pageCdJobList($inPath) {
		if (!$this->canDo("job_manage")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}
		$job_status = new base_service_common_jobstatus();
		
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$station = base_lib_BaseUtils::getStr($path_data['txtStation']);
		$account_id = base_lib_BaseUtils::getStr($path_data['account_id']);
		$position = base_lib_BaseUtils::getStr($path_data['position'], "string", "");
		$status = base_lib_BaseUtils::getStr($path_data['status'], 'int', $job_status->pub);
		
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, base_lib_BaseUtils::getCookie('accountid'));
		//企业认证信息
		$this->_aParams['audit_msg'] = $company_resources->CompanyAuditStatus();
		$companyresources = $company_resources->getCompanyServiceSource(['account_resource']);
		//var_dump($companyresources);
		//使用“分配资源”模式的子账号默认勾选我的职位
		if ($companyresources['resource_type'] == 2 && empty($position)) {
			$is_myjob = base_lib_BaseUtils::getStr($path_data['ismyjob'], 'bool', true);
		}
		else {
			$is_myjob = base_lib_BaseUtils::getStr($path_data['ismyjob'], 'bool', false);
		}
		
		$this->_aParams['is_myjob'] = $is_myjob;
		$this->_aParams['title'] = '汇博人才网_招聘中心';
		$this->_aParams["weekdays"] = array ("日", "一", "二", "三", "四", "五", "六");
		
		/** =============== 企业基本资料 part:start=================== **/
		$companyService = new base_service_company_company();
		$company = $companyService->getCompany($this->_userid, 1, 'company_id,company_name,is_audit,audit_state,company_flag,resume_download_upperlimit,com_level,start_time,end_time,hr_manager,hr_manager_sex,hr_tel,linkman');
		
		/** =============== 企业基本资料 part:end=================== **/
		//企业认证信息
		$this->_aParams['audit_msg'] = $company_resources->CompanyAuditStatus();
		
		
		/** =============== 获取招聘顾问 part:start=================== **/
		$domain = $this->GetDomainInfor();
		$companyStateService = new base_service_company_comstate();
		$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
		$hrManager = $this->GetHRManager($companyState["net_heap_id"]);
		
		//获取客服员
		// $customeruser = $this->GetCustomerService($this->_userid);
		// $this->_aParams['hasCustomer'] = false;
		// if (!is_null($customeruser)) {
		// 	$this->_aParams["hasCustomer"] = true;
		// 	$headPhoto = base_lib_BaseUtils::nullOrEmpty($customeruser["head_photo_url"]) ? $domain["defaultPhoto"] : $customeruser["head_photo_url"];
		// 	$customeruser["head_photo_url"] = $domain["image"] . "/" . $domain["photo"] . "/" . $headPhoto;
		// 	$this->_aParams["customeruser"] = $customeruser;
		// }
		
		$this->_aParams["hasHRManager"] = false;
		if (!is_null($hrManager)) {
			$this->_aParams["hasHRManager"] = true;
			$headPhoto = base_lib_BaseUtils::nullOrEmpty($hrManager["head_photo_url"]) ? $domain["defaultPhoto"] : $hrManager["head_photo_url"];
			$hrManager["head_photo_url"] = $domain["image"] . "/" . $domain["photo"] . "/" . $headPhoto;
			$this->_aParams["hrManager"] = $hrManager;
		}
		
		$xml = SXML::load('../config/config.xml');
		$this->_aParams['tel_head'] = $xml->TechniquePhone;;
		$this->_aParams['huibo400'] = $xml->HuiboPhone400;
		
		/** =============== 获取招聘顾问 part:end=================== **/
		
		
		/** =============== 单位状态 part:end=================== **/
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		$account_ids = $company_resources->all_accounts;
		
		$accounts = $companyService->getCompanys($account_ids, "company_id,company_name,company_shortname,company_flag");
		$accounts = array_map(function ($v) {
			$v['company_name_display'] = $v['company_shortname'] ? $v['company_shortname'] : $v['company_name'];
			
			return $v;
		}, $accounts);
		$accounts = base_lib_BaseUtils::array_key_assoc($accounts, "company_id");
		
		// 自动刷新
		$company['isautorefresh'] = $company_resources->getCompanyServiceSource(['isautorefresh'])['isautorefresh'];
		$companyresources = $company_resources->getCompanyServiceSource();
		//var_dump($companyresources);
		$companyresources['refresh_today_overplus'] = max($companyresources['refresh_perday'] - $companyresources['refresh_today_num'], 0);
		$this->_aParams['companyresources'] = $companyresources;
		
		$this->_aParams['accounts'] = $accounts;
		$this->_aParams['hr_type'] = $company_resources->account_type;
		$this->_aParams['now_account'] = $accounts[ $account_id ];
		$this->_aParams["companyInfor"] = $company;
		// 是否是会员或者会员是否到期
		$isEnd = false;
		if (!empty($company["end_time"])) {
			$endTime = strtotime(date('Y-m-d 23:59:59', strtotime($company["end_time"])));
			if ($company["com_level"] < 1 || $endTime < time()) {
				$isEnd = true;
			}
		}
		$this->_aParams["isEnd"] = $isEnd;
		
		
		/** =============== 职位状态 part:start=================== **/
		$account_id = $account_id ? $account_id : $company_resources->all_accounts;
		
		if (!base_lib_BaseUtils::nullOrEmpty($station)) {
			$station = trim($station);
			$this->_aParams['search_keyword'] = $station;
		}
		
		$this->_aParams['use_job_status'] = $job_status->pub;
		$this->_aParams['stop_job_status'] = $job_status->stop_use;
		$job = new base_service_company_job_job();
		
		$condition_account_id = null;
		if ($is_myjob === true) {
			$condition_account_id = base_lib_BaseUtils::getCookie('accountid');
		}
		
		$job_list = $job->getJobList($account_id, $station, $status, 'job_id,job_flag,account_id,station,issue_time,check_state,end_time,status,order_no,urgent_end_time,auto_filter,re_apply_type,company_id,account_id,map_x,map_y,agency_state,refresh_time', 0, 0, null, $condition_account_id);
		/*************************** 简历几日回复 strat *****************************/
		$field = 'job_id,job_flag,station,issue_time,check_state,end_time,status,order_no,urgent_end_time,auto_filter,re_apply_type,company_id,account_id,map_x,map_y';
		$temp = $job->getMustReplyJobS($account_id, null, null, $job_status->stop_use, $field);
		
		if ($status == $job_status->pub) {
			foreach ($temp as $key => $value) {
				$temp[ $key ]['promiseStop'] = 1;
			}
			
			$job_list = array_merge($temp, $job_list);
		}
		
		/*************************** 简历几日回复 end  *****************************/
		$new_job_list = array ();
		$job_audit = new base_service_company_job_jobauditrecord();
		$job_stat = new base_service_company_stat_jobstat();
		$job_over_time_count = 0;
		
		$temp_job_list = $job->getJobList($account_id, null, $job_status->stop_use, 'company_id,job_id,station,issue_time,check_state,end_time,status,re_apply_type,company_id,account_id,map_x,map_y');
		for ($i = 0; $i < count($temp_job_list); $i++) {
			// 结束时间
			$temp_job_list[ $i ]['end_time_name'] = base_lib_TimeUtil::to_friend_time($temp_job_list[ $i ]['end_time']);
			//已结束并且结束时间至今大于5天
			$temp_job_list[ $i ]['hidden_end_time'] = (strtotime(date('Y-m-d', time())) - strtotime(date('Y-m-d', strtotime($temp_job_list[ $i ]['end_time'])))) >= 432000
				? true : false;
			$temp_job_list[ $i ]['day'] = base_lib_TimeUtil::time_diff_day($this->now(), $temp_job_list[ $i ]['end_time']);
			
			//发布时间
			$temp_job_list[ $i ]['issue_time_name'] = base_lib_TimeUtil::to_friend_time($temp_job_list[ $i ]['issue_time']);
			
			// 增加审核不通过的原因
			$audit = $job_audit->getLastAuditRecord($temp_job_list[ $i ]['company_id'], $temp_job_list[ $i ]['job_id'], 'audit_remark,click_type');
			if ($audit && $audit['click_type']) {
				$error_msg_button = array ();
				$error_button = explode(',', $audit['click_type']);
				$com_jobMsg = new base_service_common_jobMsg();
				foreach ($error_button as $msg_li) {
					$get_data = array ('mod_type' => 'edit', 'job_id' => $temp_job_list[ $i ]['job_id'], 'company_msg' => 1);
					$error_msg_button[] = $com_jobMsg->GetErrorMsgButton($msg_li, $get_data);
				}
				$error_msg_button and $temp_job_list[ $i ]['error_msg_button'] = implode('&nbsp;&nbsp;', $error_msg_button);
			}
			$temp_job_list[ $i ]['audit_remark'] = $audit ? $audit['audit_remark'] : '';
		}
		
		$temp_job_list = $this->__arraySort($temp_job_list, 'hidden_end_time', 'asc');
		$temp_job_list = array_values($temp_job_list);
		$unaudit_jobs = array ();//审核未通过的职位
		foreach ($temp_job_list as $key => $value) {
			if ($value['check_state'] == 2) {
				array_push($unaudit_jobs, $value);
			}
		}
		
		$temp_job_list = array_values($temp_job_list);
		for ($j = 0; $j < count($temp_job_list); $j++) {
			for ($k = 0; $k < count($unaudit_jobs); $k++) {
				if ($temp_job_list[ $j ]['job_id'] == $unaudit_jobs[ $k ]['job_id']) {
					array_splice($temp_job_list, $j, 1);
					$j--;
					break;
				}
			}
		}
		
		$service_jobstatus = new base_service_common_jobstatus();
		$stopuse_jobs = array ();//手动停用的职位
		foreach ($temp_job_list as $key => $value) {
			if ($value['status'] == $service_jobstatus->stop_use) {
				array_push($stopuse_jobs, $value);
			}
		}
		
		for ($j = 0; $j < count($temp_job_list); $j++) {
			for ($k = 0; $k < count($stopuse_jobs); $k++) {
				if ($temp_job_list[ $j ]['job_id'] == $stopuse_jobs[ $k ]['job_id']) {
					array_splice($temp_job_list, $j, 1);
					$j--;
					break;
				}
			}
		}
		
		$infivedaysstop_jobs = array ();//5天内过期的职位
		foreach ($temp_job_list as $key => $value) {
			if ($value['hidden_end_time'] == false) {
				array_push($infivedaysstop_jobs, $value);
			}
		}
		$job_over_time_count = count($infivedaysstop_jobs);
		
		if (!empty($job_list)) {
			$job_id_arr = base_lib_BaseUtils::getProperty($job_list, 'job_id');
			$account_id_arr = array_unique(base_lib_BaseUtils::getProperty($job_list, 'account_id'));
			$applyJobService = new base_service_company_resume_apply();
			
			
			//发布人获取
			$service_company_account = new base_service_company_account();
			$company_account_info = $service_company_account->getAccountByAccount_ids($account_id_arr, 'account_id,user_name,user_id')->items;
			$company_account_info = base_lib_BaseUtils::array_key_assoc($company_account_info, 'account_id');
			
			$statistics = $applyJobService->getApplyStatisticsByJobIdsVerson2($account_id, $job_id_arr);
			$statistics_arr = null;
			
			if (!empty($statistics) && count($statistics->items) > 0) {
				$statistics_arr = $statistics->items;
			}
			$service_top = new base_service_company_job_jobtop();
			for ($i = 0; $i < sizeof($job_list); $i++) {
				if (!empty($statistics_arr)) {
					$stat = $this->arrayFind($statistics_arr, "job_id", $job_list[ $i ]['job_id']);
				}
				
				$job_list[ $i ]['account_user_name'] = $company_account_info[ $job_list[ $i ]['account_id'] ]['user_name'] ? $company_account_info[ $job_list[ $i ]['account_id'] ]['user_name'] : $company['linkman'];//这里就用账户名
				
				$job_list[ $i ]['applyCount'] = 0;
				$job_list[ $i ]['applyNotReadCount'] = 0;
				$job_list[ $i ] ['no_reply_num'] = 0;//未处理简历投递个数
				$job_list[ $i ] ['wait_deal_num'] = 0;//待定的简历
				if (!empty($stat)) {
					$job_list[ $i ]['applyCount'] = $stat['total_count'];
					$job_list[ $i ]['applyNotReadCount'] = $stat['not_read_count'];
					$job_list[ $i ]['no_reply_num'] = $stat['no_reply_num'];
					$job_list[ $i ]['wait_deal_num'] = $stat['wait_deal_num'];
				}
				
				// 职位链接
				$job_list[ $i ]['job_link'] = base_lib_Rewrite::job($job_list[ $i ]['job_id'], $job_list[ $i ]['job_flag']);
				
				// 结束时间
				$job_list[ $i ]['end_time_name'] = base_lib_TimeUtil::to_friend_time($job_list[ $i ] ['end_time']);
				
				// 已结束并且结束时间至今大于5天
				$job_list[ $i ]['hidden_end_time'] = (strtotime(date('Y-m-d', time())) - strtotime(date('Y-m-d', strtotime($job_list[ $i ]['end_time'])))) >= 432000 ? true : false;
				$job_list[ $i ]['day'] = base_lib_TimeUtil::time_diff_day($this->now(), $job_list[ $i ]['end_time']);
				
				// 发布时间
				$job_list[ $i ]['issue_time_name'] = base_lib_TimeUtil::to_friend_time($job_list[ $i ]['issue_time']);
				
				// 代招
				$job_list[ $i ]['generation_bidding'] = $job_list[ $i ]['company_id'] == $this->_userid ? false : true;
				
				// 增加审核不通过的原因
				$audit = $job_audit->getLastAuditRecord($job_list[ $i ]['company_id'], $job_list[ $i ]['job_id'], 'audit_remark,click_type');
				$job_list[ $i ]['audit_remark'] = $audit ? $audit['audit_remark'] : '';
				if ($audit && $audit['click_type']) {
					$error_msg_button = array ();
					$error_button = explode(',', $audit['click_type']);
					$com_jobMsg = new base_service_common_jobMsg();
					foreach ($error_button as $msg_li) {
						$get_data = array ('mod_type' => 'edit', 'job_id' => $job_list[ $i ]['job_id'], 'company_msg' => 1);
						$error_msg_button[] = $com_jobMsg->GetErrorMsgButton($msg_li, $get_data);
					}
					$error_msg_button and $job_list[ $i ]['error_msg_button'] = implode('&nbsp;&nbsp;', $error_msg_button);
				}
				
				if ($job_list[ $i ] ['urgent_end_time'] > 0) {
					$interval_time = (strtotime(date('Y-m-d', $job_list[ $i ]['urgent_end_time']))) - (strtotime(date('Y-m-d', time())));
					if ($interval_time >= 0) {
						$job_list[ $i ]['urgent_mark'] = true;
						$job_list[ $i ]['urgent_end_date'] = date('Y年m月d日', $job_list[ $i ]['urgent_end_time']);
					}
				}
				
				$toplist = $service_top->getList($service_id, $company_id, $job_list[ $i ]['job_id'], "id");
				if (count($toplist->items)) {
					$job_list[ $i ]['top_mark'] = true;
					$job_list[ $i ]['top_count'] = count($toplist->items);
				}
				
				$max_str_len = 24;
				if ($job_list[ $i ]['urgent_mark'] == true && $job_list[ $i ]['check_state'] == 4) {
					$max_str_len = 18;
				}
				elseif ($job_list[ $i ]['urgent_mark'] == true) {
					$max_str_len = 20;
				}
				elseif ($job_list[ $i ]['check_state'] == 4) {
					$max_str_len = 20;
				}
				
				$station_len = 0;
				$sub_station = '';
				$need_ellipsis = false;
				
				for ($j2 = 0; $j2 < mb_strlen($job_list[ $i ]['station']); $j2++) {
					if (!base_lib_BaseUtils::nullOrEmpty($sub_station)) {
						$sub_station .= '...';
						break;
					}
					if (preg_match("/[\x7f-\xff]/", mb_substr($job_list[ $i ]['station'], $j2, 1))) {
						$station_len += 2;
					}
					else {
						$station_len += 1;
					}
					if ($station_len >= $max_str_len) {
						$sub_station = mb_substr($job_list[ $i ]['station'], 0, $j2);
					}
				}
				if (!base_lib_BaseUtils::nullOrEmpty($sub_station)) {
					$job_list[ $i ]['sub_station'] = $sub_station;
				}
				else {
					$job_list[ $i ]['sub_station'] = $job_list[ $i ]['station'];
				}
			}
			
			if ($status == $job_status->pub) {
				$max_order_no = max(base_lib_BaseUtils::getProperty($job_list, 'order_no'));
				
				/*** 简历几日回复重新排序 start (优先级：承诺停招的-> 承诺再招的 -> 普通再招的)****/
				$temp_list = array ();//var_dump($job_list);
				$a_count = 0;
				$promiseSize = 0;
				
				//1.获取所有的job_id
				$pub_job_id = base_lib_BaseUtils::getProperty($job_list, "job_id");
				
				//2.再获取职位的精品信息
				$service_company_job_quality = new base_service_company_job_quality();
				$job_quality_list = $service_company_job_quality->getJobQulityByJobId($pub_job_id)->items;
				$job_quality_list = base_lib_BaseUtils::array_key_assoc($job_quality_list, "job_id");
				
				//职位免费刷新次数
				$service_refresh_log = new base_service_company_refreshlog();
				
				$company_account_info = $service_company_account->getAccountList($this->_userid, 'account_id,user_id,user_name,head_photo,station,resource_type')->items;
				$assoc_accounts = base_lib_BaseUtils::array_key_assoc($company_account_info, 'account_id');
				$this->_aParams['company_account_info'] = $assoc_accounts;
				
				$a_list = $service_company_account->getAccountByAccount_ids(base_lib_BaseUtils::getProperty($job_list, 'account_id'), 'account_id,user_id,user_name,head_photo,station');
				$a_list = base_lib_BaseUtils::array_key_assoc($a_list->items, 'account_id');
				$fields = 'account_id,company_id,is_main,user_id,user_name,mobile_phone,link_tel,station,head_photo,last_login_time,state,resource_type';
				$accountid = base_lib_BaseUtils::getCookie('accountid');
				$account = $service_company_account->getAccount($accountid, $fields);
				foreach ($job_list as $key => $value) {
					$job_list[ $key ]['sub_station'] = base_lib_BaseUtils::cutstr($value['sub_station'], 16, 'utf-8', '', '...');
					$job_list[ $key ]['can_do'] = ($assoc_accounts[ $value['account_id'] ]['resource_type'] == 1 && $account['resource_type'] == 1) || ($accountid == $value['account_id']) ? 1 : 2;  //只有共享模式的帐号可以相互操作功能
					//招聘人
					
					$job_list[ $key ]['job_account_resource_type'] = $assoc_accounts[ $value['account_id'] ]['resource_type'];
					
					$job_list[ $key ]['account_user_name'] = $a_list[ $value['account_id'] ]['user_name'] ? $a_list[ $value['account_id'] ]['user_name'] : $company['linkman'];//这里就用账户名
					
					
					if (($value['promiseStop'] == 1) && ($value['no_reply_num'] == 0)) {
						$a_count += 1;
						unset($job_list[ $key ]);
					}
					else {
						$job_list[ $key ]['is_quality'] = $job_quality_list[ $value['job_id'] ]['is_quality'];
						if (empty($job_quality_list[ $value['job_id'] ]) || $job_quality_list[ $value['job_id'] ]['is_quality'] != 1) {
							//职位部位精品职位，按以前排序
							if (($value['promiseStop'] == 1) && ($value['re_apply_type'] != 0)) {
								$job_list[ $key ]['my_sort'] = $max_order_no + 2;
								$promiseSize += 1;
							}
							elseif ($value['re_apply_type'] != 0) {
								$job_list[ $key ]['my_sort'] = $max_order_no + 1;
							}
							else {
								$job_list[ $key ]['my_sort'] = $value['order_no'];
							}
						}
						else {
							//为精品职位，排序靠前,并显示精品职位标签
							
							$job_list[ $key ]['my_sort'] = $max_order_no + 3;
							if (($value['promiseStop'] == 1) && ($value['re_apply_type'] != 0)) {
								$promiseSize += 1;
							}
						}
						$can_free_refresh = $service_refresh_log->isFreeRefreshToday($this->_userid, $value['job_id']);
						$job_list[ $key ]['can_free_refresh'] = $can_free_refresh ? 1 : 0;
						
						/**
						 * 刷新时间
						 * 精确到分钟
						 */
						
						$refresh_time = base_lib_TimeUtil::time_diff($value['refresh_time'], date("Y-m-d H:i:s"));
						
						if ($refresh_time['day'] <= 0 && $refresh_time['day'] >= -3) {
							if ($refresh_time['day'] == 0) {
								if ($refresh_time['hour'] == 0) {
									if ($refresh_time['min'] == 0) {
										$refresh_time = "刚刚";
									}
									else {
										$refresh_time = "{$refresh_time['min']}分钟前";
									}
								}
								else {
									$refresh_time = "{$refresh_time['hour']}小时前";
								}
								
							}
							else {
								$refresh_time = "{$refresh_time['day']}天前";
							}
						}
						else {
							$refresh_time = date("m-d", strtotime($value['refresh_time']));
						}
						
						$job_list[ $key ]['refresh_time'] = $refresh_time;
						
						//24小时内是否扣除精品点
						$params = array (
							'company_id' => $this->_userid,
							'job_id'     => $value['job_id'],
							'account_id' => base_lib_BaseUtils::getCookie("accountid")
						);
						$pay_in24_info = $company_resources->check('pub_boutique_job', $params);
						$is_pay_in24 = 0;
						if ($pay_in24_info[0] && $pay_in24_info[1] == base_service_company_resources_code::Pricing_Buied_Boutique_in24) {
							$is_pay_in24 = 1;
						}
						$job_list[ $key ]['is_pay_in24'] = $is_pay_in24;
						
					}
				}
				//var_dump($job_list);
				$this->_aParams['promiseStopSize'] = $promiseSize;
				
				$job_list = array_values($job_list);
				$job_list = base_lib_BaseUtils::multi_array_sort($job_list, 'my_sort', SORT_DESC);
				
				/*** 简历几日回复重新排序 end ****/
				$this->_aParams['job_list'] = $job_list;
			}
			else {
				$job_list = $this->__arraySort($job_list, 'hidden_end_time', 'asc');
				$job_list = array_values($job_list);
				$unaudit_jobs = array ();//审核未通过的职位
				foreach ($job_list as $key => $value) {
					if ($value['check_state'] == 2) {
						array_push($unaudit_jobs, $value);
					}
				}
				
				$job_list = array_values($job_list);
				for ($j = 0; $j < count($job_list); $j++) {
					for ($k = 0; $k < count($unaudit_jobs); $k++) {
						if ($job_list[ $j ]['job_id'] == $unaudit_jobs[ $k ]['job_id']) {
							array_splice($job_list, $j, 1);
							$j--;
							break;
						}
					}
				}
				
				$service_jobstatus = new base_service_common_jobstatus();
				$stopuse_jobs = array ();//手动停用的职位
				foreach ($job_list as $key => $value) {
					if ($value['status'] == $service_jobstatus->stop_use) {
						array_push($stopuse_jobs, $value);
					}
				}
				
				for ($j = 0; $j < count($job_list); $j++) {
					for ($k = 0; $k < count($stopuse_jobs); $k++) {
						if ($job_list[ $j ]['job_id'] == $stopuse_jobs[ $k ]['job_id']) {
							array_splice($job_list, $j, 1);
							$j--;
							break;
						}
					}
				}
				
				$infivedaysstop_jobs = array ();//5天内过期的职位
				foreach ($job_list as $key => $value) {
					if ($value['hidden_end_time'] == false) {
						array_push($infivedaysstop_jobs, $value);
					}
				}
				for ($j = 0; $j < count($job_list); $j++) {
					for ($k = 0; $k < count($infivedaysstop_jobs); $k++) {
						if ($job_list[ $j ]['job_id'] == $infivedaysstop_jobs[ $k ]['job_id']) {
							array_splice($job_list, $j, 1);
							$j--;
							break;
						}
					}
				}
				
				$infivedaysstop_jobs = $this->__arraySort($infivedaysstop_jobs, 'end_time', 'desc');
				foreach ($infivedaysstop_jobs as $k => $v) {
					array_push($new_job_list, $v);
				}
				foreach ($unaudit_jobs as $k => $v) {
					array_push($new_job_list, $v);
				}
				$stopuse_jobs = $this->__arraySort($stopuse_jobs, 'end_time', 'desc');
				foreach ($stopuse_jobs as $k => $v) {
					array_push($new_job_list, $v);
				}
				$job_list = $this->__arraySort($job_list, 'end_time', 'desc');
				foreach ($job_list as $k => $v) {
					array_push($new_job_list, $v);
				}
				
				//停招的职位中剔除承诺职位
				$filterjobids = base_lib_BaseUtils::getProperty($temp, 'job_id'); //var_dump($filterjobids);
				$f_count = count($temp);
				foreach ($new_job_list as $key => $value) {
					if (in_array($value['job_id'], $filterjobids)) {
						unset($new_job_list[ $key ]);
					}
				}
				$this->_aParams['job_list'] = array_values($new_job_list);
			}
		}
		
		$use_job_count = 0;
		$stop_job_count = 0;
		if ($status == $job_status->pub) {
			$use_job_count = count($job_list);
			$stop_job_count = ($job->getJobCount($account_id, $job_status->stop_use) - count($temp) + $a_count);
			
		}
		else {
			$use_job_count = ($job->getJobCount($account_id, $job_status->pub) + $f_count);
			$stop_job_count = count($new_job_list);
		}
		
		$this->_aParams['job_over_time_count'] = $job_over_time_count;
		$this->_aParams['use_job_count'] = $use_job_count;
		$this->_aParams['stop_job_count'] = $stop_job_count;
		$this->_aParams['station'] = $station;
		$this->_aParams['status'] = $status;
		
		/** =============== 刷新时间 part:start=================== **/
		$service_refresh = new base_service_company_refresh();
		$refresh_info = $service_refresh->getRefresh($this->_userid, "is_auto,auto_start_time,auto_end_time,auto_refreshtime,last_refresh_time");
		$is_auto = 1;
		if ($refresh_info['is_auto']) {
			if ($refresh_info['auto_start_time'] <= time() && $refresh_info['auto_end_time'] >= time()) {
				$is_auto = 0;
			}
		}
		if ($refresh_info['auto_refreshtime'] > 0) {
			$this->_aParams['autorefreshtime'] = date("m-d H:i", $refresh_info['auto_refreshtime']);
		}
		
		$this->_aParams['is_auto'] = $is_auto;
		if ($refresh_info['last_refresh_time'] > 0) {
			$this->_aParams['refresh_time'] = base_lib_TimeUtil::to_friend_time(date("Y-m-d H:i:s", $refresh_info['last_refresh_time']));
			$this->_aParams['refresh_left'] = base_lib_TimeUtil::time_diff(date("Y-m-d H:i:s", $refresh_info['last_refresh_time']), date("Y-m-d 00:00:00", strtotime("tomorrow")));
		}
		
		/** =============== 刷新时间 part:end=================== **/
		$this->_aParams['total_urgent_count'] = $the_urgent_point;
		$this->_aParams['ser_resume_num'] = $ser_resume_num;
		
		
		if ($status == $job_status->pub) {
			self::spreadJobStatistics($job_list);
			$outer_job_service = new base_service_outer_outerjob();
			$this->_aParams['importData'] = $outer_job_service->is_import($this->_userid, true);
			
			return $this->render('cdjoblist.html', $this->_aParams);
		}
		else {
			return $this->render('cdstopjoblist.html', $this->_aParams);
		}
	}
	
	
	/**
	 * 获取承诺职位的
	 */
	function pageGetNoreplycount($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_ids = base_lib_BaseUtils::getStr($pathdata['jids'], 'string', null);
		$job_ids = array_unique(explode(',', $job_ids));
		$old_size = count($job_ids);
		
		foreach ($job_ids as $kk => $value) {
			if (!is_numeric($value)) {
				unset($job_ids[ $kk ]);
			}
		}
		if (empty($job_ids) || $old_size != count($job_ids)) {
			echo json_encode(array ('status' => 0, 'error' => '参数错误'));
			
			return;
		}
		
		$post_job_ids = $job_ids;
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		
		$job_service = new base_service_company_job_job();
		$jobs = $job_service->getJobs($job_ids, "company_id,job_id,re_apply_type,station");
		foreach ($jobs as $key => $job) {
			if ($job['re_apply_type'] <= 0 || !in_array($job['company_id'], $company_resources->all_accounts)) {
				unset($jobs[ $key ]);
			}
		}
		
		$job_ids = base_lib_BaseUtils::getProperty($jobs, "job_id");
		
		if (!empty($job_ids)) {
			$apply_service = new base_service_company_resume_apply();
			$counts = $apply_service->getApplyStatisticsByJobIdsVerson2($company_resources->all_accounts, $job_ids)->items;//die;
			$jobs = base_lib_BaseUtils::compareAdd($jobs, $counts, 'job_id', 'no_reply_num');
			$stations = array ();
			$count = 0;
			$t_count_jobids = array ();
			
			foreach ($jobs as $k => $value) {
				if ($value['no_reply_num'] == 0) {
					unset($jobs[ $k ]);
					continue;
				}
				
				$count += $value['no_reply_num'];
				$stations[] = $value['station'];
				$t_count_jobids[] = $value['job_id'];
			}
			
			$stationstr = implode('，', $stations);
			$stationstr = base_lib_BaseUtils::cutstr($stationstr, 11, 'utf-8', '', '...');
			
			$jobs = array_values($jobs);
			echo json_encode(array ('status' => 1, 'count' => $count, 'names' => $stationstr, 'post_jids' => $post_job_ids, 'promise_jids' => $t_count_jobids));
		}
		else {
			echo json_encode(array ('status' => 1, 'count' => 0, 'post_jids' => $post_job_ids, 'promise_jids' => $job_ids));
		}
		
		return;
	}
	
	/**
	 * 获取即将过期承诺职位数    (当前时间 <= 过期时间 <= 明天的这个时候)
	 */
	function pagepromisesoonstop($inPath) {
		$apply_service = new base_service_company_resume_apply();
		$count = $apply_service->getPromiseSoonStop($this->_userid);
		
		echo json_encode(array ('status' => 1, 'count' => $count));
		
		return;
	}
	
	/**
	 * 回复并关闭所有职位
	 */
	function pagestopandreplyall($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_ids = base_lib_BaseUtils::getStr($pathdata['jids'], 'string', null);
		$job_ids = array_unique(explode(',', $job_ids));
		
		foreach ($job_ids as $kk => $value) {
			$service_quality = new base_service_company_job_quality();
			$quality_job = $service_quality->getJobIsQuality($value);
			if (!empty($quality_job)) {
				$service_quality->cancelJobQulity($this->_userid, $value); // 若选择发布免费职位，则取消以前的精品职位
			}
			if (!is_numeric($value)) {
				unset($job_ids[ $kk ]);
			}
		}
		if (empty($job_ids)) {
			echo json_encode(array ('status' => 0, 'error' => '参数错误'));
			
			return;
		}
		
		$service_apply = new base_service_company_resume_apply();
		$allstatus = new base_service_company_resume_applystatus();
		$job_status = new base_service_common_jobstatus();
		$service_job = new base_service_company_job_job();
		
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$resources_service = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		$all_accounts = $resources_service->all_accounts;
		
		
		$where['company_id'] = ['in' => $all_accounts];
		$where['re_status'] = $allstatus->no_reply;  //未处理的简历
		$where['job_id'] = ['in' => $job_ids];
		$apply_list = $service_apply->select($where, 'apply_id,company_id,job_id')->items;
		if (empty($apply_list)) {
			echo json_encode(array ('status' => 0, 'msg' => '停止招聘失败'));
			
			return;
		}
		$service_quality = new base_service_company_job_quality();
		
		foreach ($apply_list as $key => $value) {
			$content = "非常荣幸收到您的简历，在我们认真阅读您的简历之后，发现您的简历与该职位的定位有些不匹配，因此我们不得不遗憾的通知您无法进入面试。但您的信息我们已录入我司人才储备库，当有与您履历相匹配的职位时，我们将第一时间联系您，希望在未来我们有机会能一起奋斗拼搏。再次感谢您对我们公司的信任，祝您早日找到满意的工作。";
			$result = $service_apply->refusedReplayV2($value['apply_id'], $value['company_id'], $content, false);
			if ($result === false) {
				echo json_encode(array ('status' => 0, 'msg' => '停止招聘失败'));
				
				return;
			}
			
			//停止职位，职位为精品职位，取消推广等信息
			$quality_job = $service_quality->getJobIsQuality($value['job_id']);
			if (!empty($quality_job)) {
				$cancel_info = $service_quality->cancelJobQulity($this->_userid, $value['job_id']); // 若选择发布免费职位，则取消以前的精品职位
				if ($cancel_info['code'] == ERROR) {
					echo json_encode(array ('status' => 0, 'msg' => $cancel_info['msg']));
					
					return;
				}
			}
			
			$result = $service_job->setJobStatus($value['company_id'], $value['job_id'], $job_status->stop_use);
			if ($result === false) {
				echo json_encode(array ('status' => 0, 'msg' => '停止招聘失败'));
				
				return;
			}
			$this->setOuterStop($value['job_id']);
		}
		echo json_encode(array ('status' => 1, 'msg' => '停止招聘成功'));
		
		return;
	}
	
	
	/**
	 * @desc   新版首页
	 * @param  @inPath
	 * @author huangwwt 2015-01-13 09:20
	 */
	function pageIndex($inPath) {
		if (!$this->canDo("login_company")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = base_lib_Constant::MAIN_URL_NO_HTTP;
			
			return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$this->_aParams['title'] = '汇博人才网_招聘中心';
		//获得企业信息
		$companyService = base_service_company_company::getInstances();
		$company = $companyService->getCompany($this->_userid, true, 'company_id,company_name,is_audit,audit_state,is_effect,company_flag,area_id,info,address,'
		                                                     . 'resume_download_upperlimit,com_level,start_time,end_time,effect_job_end_time,hr_manager,create_time,'
		                                                     . 'hr_manager_sex,hr_tel,company_logo_path,company_bright_spot,property_id,size_id,'
		                                                     . 'company_shortname,email,linkman,link_tel,calling_id,calling_ids,'
		                                                     . 'is_proxyed,proxy_com_id,hrlicence,recruit_type,is_allow_appraise,site_type'    //hr会员提醒
		);

		$this->_aParams['company_url'] = base_lib_Rewrite::company($this->_userid, $company['company_flag']);
		$this->_aParams['company_level'] = $company["com_level"];
		$this->_aParams['recruit_type'] = $company["recruit_type"];
		$calling_id = $company['calling_id'];
		$this->_aParams["is_allow_appraise"] = $company["is_allow_appraise"] == 1 ? true : false;

		$companyLevelService = new base_service_company_level();
		$companyLevel = $companyLevelService->getName($company["com_level"]);
		$this->_aParams['level_name'] = $companyLevel;
		$this->_aParams['site_type'] = $company['site_type'];
		//会员时长
		if (!base_lib_BaseUtils::nullOrEmpty($company['end_time']) && !base_lib_BaseUtils::nullOrEmpty($company['start_time'])) {
			//会员剩余时长
			$last_lev_day = base_lib_TimeUtil::time_diff_day(date("Y-m-d H:i:s"), $company['end_time']);
			$this->_aParams['last_lev_day'] = $last_lev_day;

			$level_day = base_lib_TimeUtil::time_diff_day($company['start_time'], $company['end_time']);
			$level_type = 4;//会员过期提醒类型

			if ($level_day >= 180) {
				$level_type = 1;//6个月以上
			}
			elseif ($level_day >= 90 && $level_day < 180) {
				$level_type = 2;//3个月到6个月之间
			}
			elseif ($level_day >= 30 && $level_day < 90) {
				$level_type = 3;//1个月到3个月之间
			}
			else {
				$level_type = 4;
			}
		}

		if (!base_lib_BaseUtils::nullOrEmpty($company['end_time'])) {
			$day = base_lib_TimeUtil::time_diff_day(date('Y-m-d H:i:s'), $company['end_time']);
			switch ($level_type) {
				case 1:
					if ($day <= 30) {
						$this->_aParams['overdue_day'] = $day;
					}
					break;
				case 2:
					if ($day <= 15) {
						$this->_aParams['overdue_day'] = $day;
					}
					break;
				case 3:
					if ($day <= 7) {
						$this->_aParams['overdue_day'] = $day;
					}
					break;
				case 4:
					if ($day <= 3) {
						$this->_aParams['overdue_day'] = $day;
					}
					break;
				default :
					if ($day <= 3) {
						$this->_aParams['overdue_day'] = $day;
					}
					break;
			}
		}

		// 获取是否为HR会员
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $account_id);
		$this->_aParams['hr_member'] = $company_resources->account_type == 'hr_main' ? "HR版-" : "";

		// 是否为会员
		$memberinfo = $company_resources->isMember() ? "member" : "not_member";
		$this->_aParams['memberinfo'] = $memberinfo;

		//获取首页的推广金，置顶点等资料
		$companyresources = $company_resources->getCompanyServiceSource();

		$companyresources['refresh_today_overplus'] = max($companyresources['refresh_perday'] - $companyresources['refresh_today_num'], 0);
		$this->_aParams['companyresources'] = $companyresources;

		$company['isautorefresh'] = $companyresources['isautorefresh'];


		// 会员/非会员类型
		$this->_aParams['account_type'] = $company_resources->account_type;
		if ($memberinfo == "not_member") {
			if ($company_resources->account_type == "NotMemberTypeArea" || $company_resources->account_type == "NotMemberTypeCalling") {
				$this->_aParams["public_notice_url"] = "//www.huibo.com/topicpage/message/index1.html";
			}
			elseif ($company_resources->account_type == "NotMember") {
				$this->_aParams["public_notice_url"] = "//www.huibo.com/topicpage/message/index.html";
			}
		}
		//hr会员人力资源资格证提醒
		$hrlicencestatus = new base_service_common_hrlicencestatus();
		if ($company_resources->account_type == 'hr_main') {
			if ($company['hrlicence'] == $hrlicencestatus->noupload) {
				$this->_aParams['hrlicence'] = 1;
			}
			else if ($company['hrlicence'] == $hrlicencestatus->nopass) {
				$this->_aParams['hrlicence'] = 2;
			}
		}
		$audit_info = $this->checkCompanyLetter($this->_userid);
		//企业是否认证
		$service_company_status = new base_service_common_companystatus();
		list($is_audit, $audit_params) = $company_resources->getCompanyAuditStatus();
		if (!empty($audit_params)) {
			$this->_aParams = array_merge($this->_aParams, $audit_params);
		}
		$this->_aParams['is_audit'] = $is_audit;
		$company_not_update = $company_resources->getCompanyNotUpdate($is_audit);
		$this->_aParams['company_not_update'] = $company_not_update;
		//是否绑定微信 sx 7.20
		$companywerixinservice = new base_service_weixin_companyweixin();
		$weixin_result = $companywerixinservice->getListByCompanyId($company['company_id'], 'account_id,open_id', 1);
		$this->_aParams["open_id"] = $weixin_result[0]['open_id'];
		$this->_aParams['bindweixin'] = $companywerixinservice->isBind(base_lib_BaseUtils::getCookie('accountid'), $this->_userid);
		$service_related = base_service_hractivity_related::getInstances();
		$related_list = $service_related->getRelatedByCompany($this->_userid, "person_id,account_id,company_id");
		$related_list = base_lib_BaseUtils::array_key_assoc($related_list, "account_id");
		$this->_aParams["related_list"] = $related_list;

		//    //企业置顶点
		//    //单位状态
		//    $service_comservice = new base_service_company_service_comservice();
		//    $comservice = $service_comservice->getComService($this->_userid, 'service_id,resume_num,resume_down_num,urgent_point,stick_point,is_enabled_intelligentrecommend,is_enabled_autorefresh');

		//    //非会员 展示优惠券
		// $service_coupon = new base_service_company_commoncoupon();
		// $recent_coupon  = $service_coupon->getRecentCoupon($this->_userid, 'coupon_fee,coupon_end_time');
		// if (!base_lib_BaseUtils::nullOrEmpty($recent_coupon)) {
		// 	$this->_aParams['coupon'] = $recent_coupon;
		// }

		// 是否为过期会员提示
		$isEndMember = false;
		if (!base_lib_BaseUtils::nullOrEmpty($company["com_level"]) && !empty($company["start_time"]) && !empty($company["end_time"])) {
			$time_diff = intval(floor((strtotime($company['end_time']) - strtotime(date("Y-m-d 00:00:00"))) / 3600 / 24));
			if ($time_diff < 0 && $time_diff > -30) {
				$isEndMember = true;
			}
		}
		// 是否是会员或者会员是否到期
		$isEnd = false;
		$is_end_month = false;
		if (!empty($company["end_time"])) {
			$endTime = strtotime(date('Y-m-d 23:59:59', strtotime($company["end_time"])));
			if ($company["com_level"] < 1 || $endTime < time()) {
				$isEnd = true;
			}
			//贾思云需求，过期一个月内弹窗
			if ($isEnd && date("Y-m-d 23:59:59", strtotime("-30 days")) <= $company["end_time"] && $company["end_time"] < date("Y-m-d  00:00:00")) {
				$is_end_month = true;
			}
		}

		$company_resource_info = $companyresources;
		//  $company_resource_info = $company_resources->getCompanyServiceSource(); //查询企业是否是新套餐
		// if(!$company_resource_info['isNewService']){
		// $company_resource_info = $company_resources->getCompanyServiceSource(); //查询企业是否是新套餐
		// }

//		if($company_resource_info['isNewService']){
//            $is_end_month = false;
//        }
		$this->_aParams["isEnd"] = $isEnd;
		$this->_aParams["isEndMember"] = $isEndMember;

		$this->_aParams['companyinfo'] = $company;
		$xml = SXML::load('../config/config.xml');
		$tel_head = "023-61627888";
		if (!is_null($xml)) {
			$tel_head = $xml->TechniquePhone;
			$this->_aParams["ShowResumeNum"] = $xml->ShowResumeNum;
		}

		$easy_tel_head = str_replace("-", "", $tel_head);
		$this->_aParams['tel_head'] = $tel_head;
		$this->_aParams['easy_tel_head'] = $easy_tel_head;
		$this->_aParams['huibo400'] = $xml->HuiboPhone400;

		//获取招聘顾问
		$companyStateService = new base_service_company_comstate();
		$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
		$hrManager = $this->GetHRManager($companyState["net_heap_id"]);

		//获取客服员
		//$customeruser = $this->GetCustomerService($this->_userid);
		//$this->_aParams['hasCustomer'] = false;
		$this->_aParams["hasHRManager"] = false;
		if (!is_null($hrManager)) {
			$this->_aParams["hasHRManager"] = true;
			$headPhoto = base_lib_BaseUtils::nullOrEmpty($hrManager["head_photo_url"]) ? $domain["defaultPhoto"] : $hrManager["head_photo_url"];
			$hrManager["head_photo_url"] = $domain["image"] . "/" . $domain["photo"] . "/" . $headPhoto;
		}

		$service_company = new base_service_company_company();
		$site_type = $service_company->getCompany($this->_userid, 1, 'site_type')['site_type'];
		if ($site_type == 5) {
			$this->_aParams["hasHRManager"] = true;
			$hrManager['mobile'] = '18523192707';
			$hrManager['qq'] = '2851501221';
			$hrManager['user_name'] = '任慧荣';
		}
		$this->_aParams["hrManager"] = $hrManager;

		//获取销售人员的ID
		$netheap_compmany_service = new base_service_company_netheap();
		$own_dept = $netheap_compmany_service->GetNetHeapByID($companyState["net_heap_id"], 'own_dept')['own_dept'];
		if (!empty($own_dept)) {
			$this->_aParams['QRCodeImagePath'] = $this->getSalesDeptQRCode($own_dept);
		}
		else {
			$this->_aParams['QRCodeImagePath'] = $this->getSalesDeptQRCode('');
		}

		//企业资料完善度
		$companyInfoPercent = 0;
		$companyService->getComPercentAndNoComplete($this->_userid, false, $companyInfoPercent); //调用者可以不用&，否则在5.4下报错
		$this->_aParams['companyInfoPercent'] = $companyInfoPercent;

		//企业推荐简历
		$service_recommend = new base_service_company_resume_recommend();
		$item = "recommend_id,resume_id,recommend_type,recommend_man,station,status,is_read,job_id,recommend_time";

		$recommend_list = array ();
		if ($memberinfo == 'member') {
			$recommend_list = $service_recommend->getRecommendResumeByJob($this->_userid, $item, true, $is_right);
		}

		$recommend = array ();
		$calling_common = new base_service_common_calling();
		$main_calling = $calling_common->getTopCalling($company['calling_id']);
		//获取企业简历回复率
		//$rate_redis        = new base_service_cache_reversionrate();
		//$service_reversion = new base_service_common_resumereversion();
		//$reversion_applies = unserialize($rate_redis->getRateCache($this->_userid, $service_reversion));//设置缓存
//        $reversion_rate = $service_reversion->getReversionRate($reversion_applies[0]['total_count'], $reversion_applies[0]['reply_num']);
		//$this->_aParams['reversion_applies'] = $reversion_applies[0];
		//$this->_aParams['reversion_rate'] = $reversion_rate;
		//获取回复率，回复时间（新)
//        $company_ids[] = $company['company_id'];
//        $service_company_resume_apply = new base_service_company_resume_apply();
//        $reversion_rate = $service_company_resume_apply->getReplyDetail(NULL, $company_ids);
//        //大于48小时显示天数，进一取整
//        $reversion_rate['avg_time'] = floatval($reversion_rate['avg_time']);
//        if($reversion_rate['avg_time'] > 48){
//            $reversion_rate['avg_time'] = ceil($reversion_rate['avg_time'] / 24) . '天';
//        }else{
//            $reversion_rate['avg_time'] = floor($reversion_rate['avg_time']) . '小时';
//        }
//        $reversion_rate['reply_rate'] = floor($reversion_rate['re_count'] / $reversion_rate['total_count'] *100);
		//面试评价
		$service_appraise = new base_service_person_appraise_appraise();
		if ($company_resource_info['resource_type'] == 2) {
			$accountrelate_service = new base_service_replayrate_account();
			$reversion_rate = $accountrelate_service->getLastAccountRelayrate($account_id);
			$appraise_appraise = $service_appraise->getAccountScore($account_id)[0];
		}
		else {
			$relate_service = new base_service_replayrate_company();
			$reversion_rate = $relate_service->getLastCompanyRelayrate($this->_userid);
			$appraise_appraise = $service_appraise->getCompanyScore($company['company_id']);
		}

		$this->_aParams['reversion_date_range'] = date("Y-m-d", strtotime("-14 day")) . '至' . date("Y-m-d", strtotime("-1 day"));
		$this->_aParams['reversion_rate'] = $reversion_rate;
		$this->_aParams['appraise_appraise'] = $appraise_appraise;
//		var_dump($appraise_appraise);


		//获取求职者留言
		$no_read_guestbook_size = 5;
		$guestbook_read_sate = 0;
		$service_guestbook = new base_service_company_companytemplates_companytemplateguestbook();
		$time = date('Y-m-d 0:0:0', time() - 30 * 24 * 3600);
		$guestbooks = $service_guestbook->getGuestbookList($no_read_guestbook_size,
		                                                   1,
		                                                   $company_resources->all_accounts,
		                                                   '1',
		                                                   $guestbook_read_sate,
		                                                   'guestbook_id,job_id,is_read,content,create_man,create_time,is_replay,reply_content,company_id',
		                                                   $time
		);
		$this->_aParams['guestbook_count'] = $guestbooks->totalSize;
		$guestbook_list = $guestbooks->items;
		$personids = 0;
		if ($this->_aParams['guestbook_count'] > 0) {
			// 查询留言者编号
			$personids = base_lib_BaseUtils::getProperty($guestbook_list, 'create_man');
		}

		$service_person = new base_service_person_person();
		$service_resume = new base_service_person_resume_resume();
		$persons = new stdClass();
		$resumes = new stdClass();
		if (count($personids) > 0) {
			$persons = $service_person->getPersons($personids, 'person_id,user_name,small_photo');
			$resumes = $service_resume->getDefaultResumes($personids, 'person_id,resume_id');
		}

		$persons = base_lib_BaseUtils::array_key_assoc($persons->items, "person_id");
		$resumes = base_lib_BaseUtils::array_key_assoc($resumes->items, "person_id");

		for ($i = 0; $i < count($guestbook_list); $i += 1) {
			$guestbook_list[ $i ]['time'] = base_lib_TimeUtil::to_friend_time($guestbook_list[ $i ]['create_time']);

			$person = $persons[ $guestbook_list[ $i ]['create_man'] ];
			//个人头像
			$guestbook_list[ $i ]['hasHead'] = false;
			if (!base_lib_BaseUtils::nullOrEmpty($person['small_photo'])) {
				$avatar = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . "{$person['small_photo']}?r=" . rand(1000, 9999);
				$guestbook_list[ $i ]['hasHead'] = true;
				$guestbook_list[ $i ]['headphoto'] = $avatar;

			}
			$guestbook_list[ $i ]['personname'] = $person['user_name'];
		}
		$this->_aParams['guestbook_list'] = $guestbook_list;

		//获得系统用户 列表
		$service_user_sys = new company_service_user();
		$service_download = new base_service_company_resume_download();
		$job_service = new base_service_company_job_job();
		$service_person_resume_resume = new base_service_person_resume_resume();
		$service_person_person = new base_service_person_person();

		$recommend_temp_list = array();
		if (count($recommend_list) > 0) {
			foreach ($recommend_list as $value) {
				$resume_id = $value['resume_id'];
				$have_down = $service_download->isResumeDownloaded($this->_userid, $value['resume_id']);
				//根据推荐人ID 获得推荐的姓名
				//获取
				$recommend_man_name = $service_user_sys->getUser($value['recommend_man'], 'user_name');
				//重第一个推荐人身上拿到推荐人
				if (empty($value['station'])) {
					continue;
				}

				//过滤掉已读简历
				if ($value['status'] != 0) {
					continue;
				}
				$temp_array['recommend_id'] = $value['recommend_id'];
				$temp_array['resume_id'] = $value['resume_id'];
				$temp_array['is_headhunters'] = false;
				$temp_array['recommend_type'] = $value['recommend_type'];

				if(count($recommend_temp_list) >= 2){
					continue;
				}

				array_push($recommend_temp_list,$temp_array);

//				$recommend[ $value['station'] ]['info'][] = array (
//					'recommend_id'       => $value['recommend_id'],
//					'have_down'          => $have_down,
//					'is_read'            => $value['is_read'],
//					'status'             => $value['status'],
//					'resume_id'          => $value['resume_id'],
//					'recommend_man'      => $value['recommend_man'],
//					'recommend_man_name' => !empty($recommend_man_name) ? $recommend_man_name['user_name'] : '',
//					'station'            => $value['station']
//				);
//				$job_id = $value['job_id'];
//				$job_url = "";
//				if (!base_lib_BaseUtils::nullOrEmpty($job_id)) {
//					//获得job_flag
//					$job_info = $job_service->getJob($job_id, "job_flag", $this->_userid);
//					$job_url = base_lib_Rewrite::job($job_id, $job_info['job_flag'], base_lib_Constant::MAIN_URL_NO_HTTP);
//				}
//				$recommend[ $value['station'] ]['job_url'] = $job_url;//未处理简历数
//				if ($value['is_read'] != 1) {
//					$recommend[ $value['station'] ]['not_read_count'] = $recommend[ $value['station'] ]['not_read_count'] + 1;
//				}
//				if ($value['is_read'] == 1 && $value['status'] == 0) {
//					$recommend[ $value['station'] ]['not_do'] = $recommend[ $value['station'] ]['not_do'] + 1; //修改未处理
//				}
//				if (base_lib_BaseUtils::nullOrEmpty($job_id) || $job_id == 0) {
//					$job_id = $value['station'];
//				}
//				$recommend[ $value['station'] ]['job_id'] = $job_id;
//				$recommend[ $value['station'] ]['is_headhunters'] = true;
			}
		}

		$recommend_resume_num = count($recommend_temp_list);

		if($recommend_resume_num < 2){
			//人工推荐简历小于2分时，读取自动推荐
			$auto_recommend_job = $this->getAutoRecommendJob();
			if($auto_recommend_job){
				$auto_recommend_resume_num = 2 - $recommend_resume_num;
				$solr_resume = new base_service_solr_resume();
				$resume_ids = $solr_resume->resumeRecommendByJob($this->_userid,$auto_recommend_job,1,[],$auto_recommend_resume_num);
				for($i = 0 ; $i < $auto_recommend_resume_num ; $i++){
					$temp_array = array(
						'resume_id'		=> $resume_ids['resume_ids'][$i],
						'recommend_type'	=> 4
					);
					array_push($recommend_temp_list,$temp_array);
				}
			}
		}

		//获取推荐简历信息
		$resume_temp_ids = base_lib_BaseUtils::getPropertys($recommend_temp_list,"resume_id");
		$recommend_temp_list = base_lib_BaseUtils::array_key_assoc($recommend_temp_list,"resume_id");
		if($recommend_temp_list && $resume_temp_ids){
			$sort_max = 2;
			foreach($recommend_temp_list as $key =>$value){
				$recommend_temp_list[$key]['sort'] = $sort_max;
				$sort_max--;
			}
			$resume_list = array();
			$resume_ids_str = implode(',',$resume_temp_ids);

			$field = "resume_id,person_id,user_name,create_time,sex,birthday,cur_area_id,degree_id,major_desc,work_year,station,appraise";
			$resume_list = $service_person_resume_resume->getResumes($resume_ids_str,$field)->items;
			$person_ids = base_lib_BaseUtils::getPropertys($resume_list,"person_id");
			$person_ids = implode(',',$person_ids);

			//求职信息
			$service_person_resume_work = new base_service_person_resume_work();
			$service_edu = new base_service_person_resume_edu();
			$person_list = $service_person_person->GetPersonListByIDs($person_ids, 'person_id,user_name,name_open,photo_open,sex,birthday2,cur_area_id,start_work,photo,small_photo,mobile_phone,job_state_id,accession_time')->items;
			$person_list = base_lib_BaseUtils::array_key_assoc($person_list,"person_id");
			$work_datas = $service_person_resume_work->getLastResumeWorks($resume_temp_ids, 'work_id,resume_id,start_time,end_time,station,company_name,work_content')->items;
			$work_datas = base_lib_BaseUtils::array_key_assoc($work_datas,"resume_id");
			//教育
			$edu_data = $service_edu->getResumeEdus($resume_ids_str,'resume_id,school,major_desc,degree')->items;

			$service_company_resume_resumevisit = new base_service_company_resume_resumevisit();
			$company_resources_info = $company_resources->getCompanyAuditStatusV2();
			$isshowresumeinfo = true;
			$letter_info = $this->CheckCompanyLetter($this->_userid);

			if($company_resources_info['audit_type'] == 1){
				//老规则
				$isshowresumeinfo = true;
			}else{
				//新规则
				if($letter_info['code'] != 200){
					$isshowresumeinfo = false;
				}
			}

			foreach($resume_list as $key => $value){
				$person_info = $person_list[$value['person_id']];

				$is_show_name = false; //是否显示简历的名字
				$is_show_linkway = false;//是否显示联系方式  其中有是否下载
				$member_info = false;//是否是会员
				$member_expires = false;//会员是否过期
				$is_show_resumeinfo = false;//是否显示详细信息

				$result = $this->checkResume($value['resume_id'], $is_show_name, $is_show_linkway, $member_info, $member_expires,$is_show_resumeinfo);

				//姓名
				if ($is_show_name && $isshowresumeinfo) {
					$resume_list[$key]['user_name'] = $person_info['user_name'];
				} else {
					$sex_name = $person_info['sex'] == 1 ? '先生' : '女士';
					$resume_list[$key]['user_name'] = mb_substr($person_info['user_name'], 0, 1, 'utf-8') . $sex_name;
				}

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

				$resume_list[$key]['small_photo'] = $person_info['photo'];
				$resume_list[$key]['sex']         = $this->getSex($person_info['sex']);
				$resume_list[$key]['age']         = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']) . '岁';
				$resume_list[$key]['degree']      = $this->getDegree($value['degree_id']);
				$resume_list[$key]['cur_area']    = $this->getArea($person_info['cur_area_id']);

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

				$resume_list[$key]['start_work'] = $basic_start_work_year ? $basic_start_work_year:'应届毕业生';

				$resume_list[$key]['is_headhunters'] = $recommend_temp_list[$value['resume_id']]['is_headhunters'];
				$resume_list[$key]['recommend_type'] = $recommend_temp_list[$value['resume_id']]['recommend_type'];
				$resume_list[$key]['recommend_id'] = $recommend_temp_list[$value['resume_id']]['recommend_id'];
				$resume_list[$key]['sort'] = $recommend_temp_list[$value['resume_id']]['sort'];

				$resume_list[$key]['station'] = $work_datas[$value['resume_id']]['station'];
				$resume_list[$key]['company_name'] = $work_datas[$value['resume_id']]['company_name'];
				//教育
				$edu_info = $this->arrayFind($edu_data, 'resume_id',$value['resume_id']);
				$resume_list[$key]['school']        = $edu_info['school'];
				$resume_list[$key]['major_desc']    = $edu_info['major_desc'];
				$resume_list[$key]['school_degree'] = $this->getDegree($edu_info['degree']);
			}

			$resume_list = $this->__arraySort($resume_list,"sort","desc");

			$this->_aParams['recommend'] = $resume_list;
		}


		$this->_aParams['recommend_resume_num'] = $recommend_resume_num;



		$this->_aParams['job_number'] = 3;
		$xml = SXML::load('../config/company/company.xml');
		if (!is_null($xml)) {
			$max_photo_count = $xml->PhotoMaxCount;
			$photo_virt_path = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $xml->VirtualName . '/' . $xml->PhotoFolder . '/' . $company['company_id'] . '/';
			$photo_thumb_suffix = $xml->PhotoThumbSuffix;
			$singel_photo_count = $xml->PhotoSingelCount;
			//logo
			$logofolder = $xml->LogoFolder;
			$logoTempfolder = $xml->LogoTempFolder;
			$virtualName = $xml->VirtualName;
			$this->_aParams['job_number'] = $xml->DefaultJobNum;//非会员默认开通职位数量：5
		}

		//获得6条相同行业名企 is_famous 为2的是名企
		if ($memberinfo != 'member') {
			$calling_id = $company['calling_id'];//主行业
			$not_calling_count = 0;
			$c_famous_company = array ();
			if (!base_lib_BaseUtils::nullOrEmpty($calling_id)) {
				$c_famous_company = $companyService->getFamousCompanysByCalling(6, "company_id,company_name,company_shortname,is_audit,audit_state,"
				                                                                 . "company_logo_path,effect_job_end_time,company_flag", $calling_id, $this->_userid)->items;
				if (count($c_famous_company) < 6) {
					$not_calling_count = 6 - count($c_famous_company);
				}
			}
			else {
				$not_calling_count = 6;
			}

			$famous_company = array ();
			if ($not_calling_count > 0) {
				$ids_arr = null;
				if (count($c_famous_company) > 0) {
					$ids_arr = $this->getPropertys($c_famous_company, "company_id");
				}
				$famous_company = $companyService->getFamousCompanys($not_calling_count, "company_id,company_name,company_shortname,is_audit,audit_state,effect_job_end_time,company_logo_path,company_flag", $this->_userid, $ids_arr)->items;
			}
			$famous_company_result = array_merge($c_famous_company, $famous_company);
			//获得名企的资料
			if (!empty($famous_company_result)) {
				foreach ($famous_company_result as $k => $v) {
					if (!base_lib_BaseUtils::nullOrEmpty($v['company_logo_path'])) {
						$famous_company_result[ $k ]['logo_path'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $virtualName . '/' . $logofolder . '/' . $v['company_logo_path'];
					}
					else {
						$famous_company_result[ $k ]['logo_path'] = base_lib_Constant::STYLE_URL . "/img/c/new_index/headlogo.png";
					}
					$famous_company_result[ $k ]['company_url'] = base_lib_Rewrite::company($v['company_id'], $v['company_flag']);
				}
			}
		}
		$this->_aParams['famous_company'] = $famous_company_result;

		// 获得已发布的职位
		$job_status = new base_service_common_jobstatus();

		// 添加职位类别未匹配的job_id
		$job_list = [];
		$page_now = base_lib_BaseUtils::getStr($pathdata['page'], 'int', 1);
		$page_size = 20;
		$page_now = 0;
		$page_size = 0;
		$service_company_account = new base_service_company_account();
		$this->_aParams['account_id'] = $account_ids = base_lib_BaseUtils::getStr($pathdata['account_id'], 'string', null);//待查询子账户
		$all_job_list = $job_service->getJobList($company_resources->all_accounts, '', $job_status->pub, 'job_id,station,issue_time,mod_time');//所有的职位，用于判断是否提示发布职位弹窗

		//最新人才推荐逻辑
        $this->_aParams['can_use_newresume_search'] = $service_company->canProviteNewResumeSearch($this->_userid);//是否可以提供新简历搜索
		if($this->_aParams['can_use_newresume_search']==1)
        	$this->getNewestRecommendResume($all_job_list,$account_id);

		$is_all = base_lib_BaseUtils::getStr($pathdata['all'], 'string', null);
		$fields = 'account_id,company_id,is_main,user_id,user_name,mobile_phone,link_tel,station,head_photo,last_login_time,state,resource_type';
		$account = $service_company_account->getAccount($account_id, $fields);
		$this->_aParams['resource_type'] = $account['resource_type'];
		$this->_aParams['cur_is_main'] = $account['is_main'];
		if ($account['resource_type'] == 2 && empty($account_ids) && !$is_all) {
			$account_ids = $account_id;
			$this->_aParams['account_id'] = $account_id;
		}
		$tempList = $job_list = $job_service->getJobList($company_resources->all_accounts, '', $job_status->pub, 'job_id,company_id,job_flag,station,issue_time,check_state,end_time,status,order_no,urgent_end_time,create_time,jobsort_ids,jobsort,re_apply_type,account_id,map_x,map_y,agency_state,refresh_time', $page_size, $page_now, null, $account_ids);
		$temp = $job_service->getMustReplyJobS($company_resources->all_accounts, null, null, $job_status->stop_use, 'job_id,company_id,job_flag,station,issue_time,check_state,end_time,status,order_no,urgent_end_time,auto_filter,re_apply_type,account_id,refresh_time');
		foreach ($temp as $key => $value) {
			$temp[ $key ]['promiseStop'] = 1;
		}
		$has_pub_job = !empty($job_list);
		//在招的职位中加入已经停招的承诺职位
		$job_list = array_merge($temp, $job_list);
		$all_job_list = array_merge($temp, $all_job_list);
		$this->_aParams['all_job_list'] = $all_job_list;
		$jobsort_arr = array ();
		foreach ($job_list as $key => $value) {
			array_push($jobsort_arr, $value['jobsort_ids']);
			if ($value['re_apply_type'] == '0') {
				$job_list[ $key ]['promiseStop'] = 0;
			}
		}

		$applyJobService = new base_service_company_resume_apply();
		$job_ids = base_lib_BaseUtils::getProperty($job_list, "job_id");

		$statistics = $applyJobService->getApplyStatisticsByJobIdsVerson2($company_resources->all_accounts, $job_ids)->items;
		$statistics = base_lib_BaseUtils::array_key_assoc($statistics, "job_id");

		//子账户列表
		$company_account_info = $service_company_account->getAccountList($this->_userid, 'account_id,user_id,user_name,head_photo,station,resource_type')->items;
		$assoc_accounts = base_lib_BaseUtils::array_key_assoc($company_account_info, 'account_id');
		$this->_aParams['company_account_info'] = $assoc_accounts;

		$a_list = $service_company_account->getAccountByAccount_ids(base_lib_BaseUtils::getProperty($job_list, 'account_id'), 'account_id,user_id,user_name,head_photo,station');
		$a_list = base_lib_BaseUtils::array_key_assoc($a_list->items, 'account_id');

		$service_top = new base_service_company_job_jobtop();
		$service_company_resume_apply = new base_service_company_resume_apply();

		$not_has_map_jobs = array ();
		$no_refresh_job_lists = array ();
		$week_time = date("Y-m-d H:i:s",strtotime("-1 week"));
		$refresh_time_list = base_lib_BaseUtils::getPropertys($job_list,"refresh_time");

		$is_show_recommend_job = array();


		foreach ((array)$job_list as $i => $job) {
			$job_list[ $i ]['applyCount'] = $statistics[ $job['job_id'] ]['total_count'] ? $statistics[ $job['job_id'] ]['total_count'] : 0;
			$job_list[ $i ]['applyNotReadCount'] = $statistics[ $job['job_id'] ]['not_read_count'] ? $statistics[ $job['job_id'] ]['not_read_count'] : 0;
			$job_list[ $i ]['no_reply_num'] = $statistics[ $job['job_id'] ]['no_reply_num'] ? $statistics[ $job['job_id'] ]['no_reply_num'] : 0;//未处理简历投递个数
			$job_list[ $i ]['wait_deal_num'] = $statistics[ $job['job_id'] ]['wait_deal_num'] ? $statistics[ $job['job_id'] ]['wait_deal_num'] : 0;//待定的简历
			$job_list[ $i ]['job_link'] = base_lib_Rewrite::job($job['job_id'], $job['job_flag'], base_lib_Constant::MAIN_URL_NO_HTTP);
			$job_list[ $i ]['can_do'] = $assoc_accounts[ $job['account_id'] ]['resource_type'] == 1 && $account['resource_type'] == 1 || $account_id == $job['account_id'] ? true : false;  //只有共享模式的帐号可以相互操作功能
			//招聘人
			$job_list[ $i ]['account_user_name'] = $a_list[ $job['account_id'] ]['user_name'] ? $a_list[ $job['account_id'] ]['user_name'] : $company['linkman'];//这里就用账户名

			// 结束时间
			$job_list[ $i ]['end_time_name'] = base_lib_TimeUtil::to_friend_time($job['end_time']);

			//已结束并且结束时间至今大于5天
			$job_list[ $i ]['hidden_end_time'] = (strtotime(date('Y-m-d', time())) - strtotime(date('Y-m-d', strtotime($job_list[ $i ]['end_time'])))) >= 432000 ? true : false;
			$job_list[ $i ]['is_show_lazy'] = false;
			/** 结束时间 */
			$day = base_lib_TimeUtil::time_diff_day($this->now(), $job['end_time']);
			if ($day >= 0 && $day <= 3) {
				$job_list[ $i ]['is_show_lazy'] = true;
				if ($day == 0) {
					$endtime = "今天";
				}
				else if ($day == 1) {
					$endtime = "明天";
				}
				else {
					$endtime = "{$day}天后";
				}
			}
			else {
				$endtime = date("m-d", strtotime($job['end_time']));
			}

			$job_list[ $i ]['day'] = $endtime;


			/**
			 * 刷新时间
			 * 精确到分钟
			 */
			if ($company_resource_info['isCqNewService']) {
				$job_list[ $i ]['refresh_time_temp'] = $job['refresh_time'];
			}

			$refresh_time = base_lib_TimeUtil::time_diff($job['refresh_time'], $this->now());
			if ($refresh_time['day'] <= 0 && $refresh_time['day'] >= -3) {
				if ($refresh_time['day'] == 0) {
					if ($refresh_time['hour'] == 0) {
						if ($refresh_time['min'] == 0) {
							$refresh_time = "刚刚";
						}
						else {
							$refresh_time = "{$refresh_time['min']}分钟前";
						}
					}
					else {
						$refresh_time = "{$refresh_time['hour']}小时前";
					}

				}
				else {
					$refresh_time = "{$refresh_time['day']}天前";
				}
			}
			else {
				$refresh_time = date("m-d", strtotime($job['refresh_time']));
			}
			if ($job['refresh_time'] == '2014-01-01 00:00:00') {
				$refresh_time = '未刷新';
			}

			$job_list[ $i ]['refresh_time'] = $refresh_time;
			if ($company_resource_info['isCqNewService']) {
				if ($job['refresh_time'] == '2014-01-01 00:00:00') {
					array_push($no_refresh_job_lists, $job_list[ $i ]);
					unset($job_list[ $i ]);
				}
			}

			//急聘
			if ($job_list[ $i ] ['urgent_end_time'] > 0) {
				$interval_time = (strtotime(date('Y-m-d', $job_list[ $i ]['urgent_end_time']))) - (strtotime(date('Y-m-d', time())));
				if ($interval_time >= 0) {
					$job_list[ $i ]['urgent_mark'] = true;
					$job_list[ $i ]['urgent_end_date'] = date('Y年m月d日', $job_list[ $i ]['urgent_end_time']);
				}
			}

			//置顶
			$toplist = $service_top->getList('', '', $job_list[ $i ]['job_id'], "id");
			if (count($toplist->items)) {
				$job_list[ $i ]['top_mark'] = true;
				$job_list[ $i ]['top_count'] = count($toplist->items);
			}

			if (empty($job["map_x"]) && empty($job["map_y"])) {
				$not_has_map_jobs[] = $job["station"];
			}

			//推荐人才气泡显示逻辑  一周内新发布的职位，一周内投递简历数在X份以下
			$set_apply_num = 5;
			$job_apply_num = $service_company_resume_apply->getApplyCountByJobId($job['job_id'],30);

			if($job['create_time'] >= $week_time && ($job_apply_num < $set_apply_num || empty($job_apply_num))){
				$recommend_temp_tip = array(
					'job_id'		=> 	$job['job_id'],
					'refresh_time'	=> $job['refresh_time']
				);
				array_push($is_show_recommend_job,$recommend_temp_tip);
			}
		}

		//登录账号一周内是否点击推荐人才气泡
		$account_id1 = base_lib_BaseUtils::getCookie('accountid');
		$recommend_tip_cookie_key = "recommend_tip_cookie_".$account_id;
		$recommend_tip_cookie = base_lib_BaseUtils::getCookie($recommend_tip_cookie_key);
		$this->_aParams['recommend_tip_cookie'] = $recommend_tip_cookie;
		$this->_aParams['account_id11'] = $account_id1;

		//招聘效果气泡显示
		$this->_aParams['recruitment_result_show'] = base_lib_BaseUtils::getCookie("recruitment_result_show");

		//能够显示推荐气泡的职位
		$is_can_show_recommend_tip = "";

		if($is_show_recommend_job){
			if(count($is_show_recommend_job) == 1){
				$is_can_show_recommend_tip = $is_show_recommend_job[0]['job_id'];
			}else{
				$is_can_show_recommend_refresh = "";
				foreach($is_show_recommend_job as $k => $v){
					if($v['refresh_time'] > $is_can_show_recommend_refresh){
						$is_can_show_recommend_tip = $v['job_id'];
						$is_can_show_recommend_refresh = $v['refresh_time'];
					}
				}
			}
		}


		if ($company_resource_info['isCqNewService']) {
			$is_show_refresh_tip = false;
			$sort_job_list = base_lib_BaseUtils::multi_array_sort($job_list, 'refresh_time_temp', SORT_DESC);
			$this->_aParams['refresh_time'] = $sort_job_list[0]['refresh_time'];//最近刷新时间取最近刷新的职位时间
			$job_list = array_merge($no_refresh_job_lists, $job_list);
			if ($sort_job_list[0]['refresh_time_temp'] <= date("Y-m-d 06:00:00")) {
				$is_show_refresh_tip = true;
			}
			$this->_aParams["is_show_refresh_tip"] = $is_show_refresh_tip;
		}

		if (!empty($not_has_map_jobs)) {
			$this->_aParams["not_has_map_job_num"] = count($not_has_map_jobs);
		}

		if($job_list){
			$max_order = max(base_lib_BaseUtils::getProperty($job_list, 'order_no'));
		}
		$promiseStopSize = count($temp);

		if ($company_resource_info['isNewService']) {  //使用新套餐
			$service_company_job_quality = new base_service_company_job_quality(); //精品职位
			$jobquality = $service_company_job_quality->getJobByCompany($this->_userid, 'company_id,job_id,is_quality')->items; //企业发布的精品职位
			$jobquality_list = base_lib_BaseUtils::array_key_assoc($jobquality, 'job_id');
		}

		$this->_aParams['company_resource_info'] = $company_resource_info;
		$poster_job_num = 0;
		$poster_job_id_temp = 0;
		foreach ($job_list as $key => $value) {

			$job_list[ $key ]['man_sort'] = $value['order_no'];
			if ($value['promiseStop'] == 1) {
				$job_list[ $key ]['man_sort'] = $max_order + 2;
				continue;
			}

			if ($value['re_apply_type'] != 0) {
				$job_list[ $key ]['man_sort'] = $max_order + 1;
			}
			//是否发布过
			$service_consolelog = new base_service_company_service_serviceConsumeLog();
			$is_pay_in24 = $service_consolelog->isPayIn24($this->_userid, $value['job_id']);
			$job_list[ $key ]['is_quality'] = $jobquality_list[ $value['job_id'] ]['is_quality'];
			$job_list[ $key ]['is_pay'] = $is_pay_in24;
			$service_refresh_log = new base_service_company_refreshlog();
			$can_free_refresh = $service_refresh_log->isFreeRefreshToday($this->_userid, $value['job_id']);
			$job_list[ $key ]['is_free_refresh'] = $can_free_refresh;

			if ($value['check_state'] != 4) {
				$poster_job_num++;
				$poster_job_id_temp = $value['job_id'];
			}
			//显示气泡
			$job_list[ $key ]['is_show_recommend_tip'] = false;
			if($value['job_id'] == $is_can_show_recommend_tip){
				$job_list[ $key ]['is_show_recommend_tip'] = true;
			}
		}

		if (!$company_resource_info['isCqNewService']) {
			if ($company_resource_info['isNewService']) {  //使用新套餐
				$job_list = base_lib_BaseUtils::multi_array_sort($job_list, 'is_quality', SORT_DESC);
			}
			else {
				$job_list = base_lib_BaseUtils::multi_array_sort($job_list, 'man_sort', SORT_DESC);
			}
		}


		$this->_aParams['promiseStopSize'] = $promiseStopSize; //var_dump($promiseStopSize);
		$this->_aParams['job_lists'] = array_values($job_list);

		$xml = SXML::load('../config/company/company.xml');
		if (!is_null($xml)) {
			$this->_aParams['default_count'] = $xml->DefaultJobNum;
			$not_member_area = explode(",", $xml->NotMemberTypeArea->AreaLimit);
			$not_member_calling = explode(",", $xml->NotMemberTypeCalling->CallingLimit);
		}

		$area_common = new base_service_common_area();
		foreach ((array)$not_member_area as $_area) {
			$this->_aParams['not_member_areanames'][] = $area_common->getArea($_area, false)['abbreviation'];
		}

		foreach ((array)$not_member_calling as $_calling) {
			$this->_aParams['not_member_callingnames'][] = $calling_common->getCallingName($_calling);
		}

		//获得求职者简历信息
		/*if ($memberinfo != "member") {
           $this->__getResumeInfo($company['calling_ids']);
        }*/
		/** =============== 最近招聘会 part:start=================== **/
		$fairSceneService = new base_service_company_fair_fairscene();
		//最近三场招聘会
		$lastThreeFairs = $fairSceneService->getLastThreeFairs("scene_id,date,predict_extent,predict_jobcount,subject");
		$this->_aParams['lastThreeFairs'] = $lastThreeFairs->items;
		$this->_aParams["weekdays"] = array ("日", "一", "二", "三", "四", "五", "六");
		/** =============== 最近招聘会 part:end=================== **/

		/** =============== 刷新职位 part:start=================== **/
		$service_refresh = new base_service_company_refresh();
		$refresh_info = $service_refresh->getRefresh($this->_userid, "is_auto,auto_start_time,auto_end_time,auto_refreshtime,last_refresh_time");
		$is_auto = $refresh_info['is_auto'] && $refresh_info['auto_start_time'] <= time() && $refresh_info['auto_end_time'] >= time() ? 0 : 1;
		if ($refresh_info['auto_refreshtime'] > 0) {
			$this->_aParams['autorefreshtime'] = date("m-d H:i", $refresh_info['auto_refreshtime']);
		}

		//刷新
		$this->_aParams['is_auto'] = $is_auto;
		if ($refresh_info['last_refresh_time'] > 0) {
			if (empty($all_job_list)) {
				$this->_aParams['showRefreshTip'] = false;
			}
			else {
				$this->_aParams['showRefreshTip'] = round((intval(time() - $refresh_info['last_refresh_time'])) / 60 / 60) >= 2 ? true : false;
			} //最近刷新时间大于2小时就显示提示
			if (!$company_resource_info['isCqNewService']) {
				$this->_aParams['refresh_time'] = base_lib_TimeUtil::to_friend_time(date("Y-m-d H:i:s", $refresh_info['last_refresh_time']));
			}

			// var_dump(time(),$refresh_info['last_refresh_time'],$this->_aParams['showRefreshTip']);//die;
		}
		/** =============== 刷新职位 part:end=================== **/

		/** =============== 企业LOGO part:start=================== **/
		//读取配置xml文件
		$logo_virt_path = base_lib_Constant::UPLOAD_FILE_URL . '/' . $virtualName . '/' . $logoTempfolder . '/';
		$logo_base_path = base_lib_Constant::UPLOAD_FILE_URL . '/' . $virtualName . '/' . $logofolder . '/';
		$this->_aParams['photo_virt_path'] = $photo_virt_path;
		$this->_aParams['logo_virt_path'] = $logo_virt_path;//logo临时目录
		$this->_aParams['logo_base_path'] = $logo_base_path;//logo最终目录
		//企业LOGO
		if ($company['company_logo_path'] != '') {
			$logo_path = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $virtualName . '/' . $logofolder . '/' . $company['company_logo_path'];
		}
		else {
			//$logo_path = base_lib_Constant::STYLE_URL.'/img/c/new_index/headlogo.png';
			$logo_path = base_lib_Constant::STYLE_URL . '/img/c/new_index/headuplogo.jpg';//sx 7.22
		}

		$this->_aParams['logo_path'] = $logo_path;
		$this->_aParams['company_url'] = base_lib_Rewrite::company($this->_userid, $company['company_flag']);
		/** =============== 企业LOGO part:end=================== **/

		/** ===============   QQ群 part:start=================== **/
		//根据主行业获得HR
		$qqgroup_service = new base_service_company_hrqqgroup();
		$qq_group_arr = $qqgroup_service->getGroupDataByCalling($company['calling_id'], "qq_group,group_name", 1);// 1为正常 2为已满 0为关闭
		$this->_aParams['qq_group_arr'] = $qq_group_arr;
		/** ===============   QQ群 part:end=================== **/

		$this->_getAdvert($company);


		$xml = SXML::load('../config/callingadvert.xml');
		if (!is_null($xml)) {
			$virtualName = $xml->VirtualName;
			$visitFolder = $xml->VisitFolder;
			$tx = $xml->PhotoThumbSuffix;
		}
		/** ===============  行业广告--右侧=================== **/
		$callingAdvertService = new base_service_advert_callingadvert();
		$callingAdvertInfo = $callingAdvertService->selectAdvert('1', '', '', '', 'adv_id,calling_id,img,title,url,is_no_member_see', 'order by order_num desc', 1, 1, 10, time())->items;
		if (!base_lib_BaseUtils::nullOrEmpty($callingAdvertInfo)) {
			$callingAdvertInfo_tmp = array ();
			foreach ($callingAdvertInfo as $k => $v) {
				$callingAdvertInfo[ $k ]['img_url'] = (base_lib_Constant::UPLOAD_FILE_URL . '/' . $virtualName . '/' . $visitFolder . '/' . $v['img']);

				if ($v['calling_id']) {
					if ($calling_id != $v['calling_id']) {
						//如果广告有行业ID，那么只有该行业可以看
						unset($callingAdvertInfo[ $k ]);
						continue;
					}

					//非会员不可见的广告处理
					if ($v['is_no_member_see'] == 0) {
						if ($memberinfo != "member") {
							unset($callingAdvertInfo[ $k ]);
							continue;
						}
					}
				}
				$callingAdvertInfo_tmp[] = $callingAdvertInfo[ $k ];
			}
			$this->_aParams['calling_advert'] = $callingAdvertInfo_tmp;
		}
		/** ===============  行业广告--中简=================== **/
		$callingAdvertService = new base_service_advert_callingadvert();
		$callingAdvertInfo = $callingAdvertService->selectAdvert('2', '', '', '', 'adv_id,calling_id,img,title,url,is_no_member_see', 'order by order_num desc', 1, 1, 10, time())->items;
		if (!base_lib_BaseUtils::nullOrEmpty($callingAdvertInfo)) {
			foreach ($callingAdvertInfo as $k => $v) {
				$callingAdvertInfo[ $k ]['img_url'] = (base_lib_Constant::UPLOAD_FILE_URL . '/' . $virtualName . '/' . $visitFolder . '/' . $v['img']);
				if ($v['calling_id']) {
					if ($calling_id != $v['calling_id']) {
						unset($callingAdvertInfo[ $k ]);
					}//如果广告有行业ID，那么只有该行业可以看
				}

				//非会员不可见的广告处理
				if ($v['is_no_member_see'] == 0) {
					if ($memberinfo != "member") {
						unset($callingAdvertInfo[ $k ]);
						continue;
					}
				}

			}
			$this->_aParams['calling_advert_middle'] = $callingAdvertInfo;
		}

		$is_interv = substr($company['area_id'], 0, 4) == '0318' ? true : false;
		$all_job_list1 = $job_service->getJobList($company_resources->all_accounts, '', $job_status->pub, 'job_id,check_state');//所有的职
		$dialogs = $this->__isShowDialog($company_not_update, $is_audit, $all_job_list1, $is_interv, $is_end_month, $company_resource_info['isNewService'], $audit_info['code']);

		$this->_aParams["dialog_box"] = $dialogs;
		//新增需求，省外同行认证非会员企业，有在招职位的，还有刷新机会的每天提示一次刷新弹窗(会有上面弹框的也不弹了吧)
		list($status, $code, $params) = $company_resources->check($func = "refresh");
		$has_prompt_refresh_tip = base_lib_BaseUtils::getCookie("has_prompt_refresh_tip");
		//省外企业都是免费会员，这里要排除
		$this->_aParams['need_prompt_refresh_tip'] = (($memberinfo == "not_member" || $company["com_level"] == 1) && $company_resources->location_area == "NOT_CQ_CITY" && $is_audit == 5 && $has_pub_job && empty($dialogs) && !$has_prompt_refresh_tip && $status);

		//职位联系人信息
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$this->_aParams['account_user_info'] = $this->_aParams['company_account_info'][ $account_id ];
		if (!empty($this->_aParams['account_user_info']['head_photo'])) {
			$this->_aParams['account_user_info']['head_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $this->_aParams['account_user_info']['head_photo'];
		}

		//企业年报弹窗
		$report_cookie_name = "company_annual_report_".$account_id;
		$is_show_company_annual_dialog = false;

		if (base_lib_BaseUtils::nullOrEmpty(base_lib_BaseUtils::getCookie($report_cookie_name)) && empty($company_not_update)) {
			$is_show_company_annual_dialog = true;
			$report_aCookie = array ("{$report_cookie_name}" => 'is_show');
			$report_time = strtotime(date("Y-m-d 23:59:59",strtotime("+1 year"))) - time();
			base_lib_BaseUtils::ssetcookie($report_aCookie, $report_time, '/',base_lib_Constant::COOKIE_DOMAIN);
		}
		$this->_aParams['is_show_company_annual_dialog'] = $is_show_company_annual_dialog;

		//是否登录企业app
		$this->_aParams['is_login_app'] = false;
		$service_redis = new company_service_isloginapp();
		$this->_aParams['is_login_app'] = $service_redis->is_login_app($this->_userid, $account_id);
		//暂时通过融云拿登录状态
		$service_rong = new base_service_company_app_rong();
		$this->_aParams['is_login_app'] = $service_rong->checkIsOnline($this->_userid, $account_id);


		//近一个月的面试评价
		//待处理 appraise_wait_deal

		$service_appraise = new base_service_person_appraise_appraise();
		$appraise_object = $service_appraise->getCompanyAppraiseList(1, 5, $this->_userid, "appraise_id,person_id,appraise_time,content,complain_status,check_state", 0, date('Y-m-d 0:0:0', strtotime('-30days')));
		$appraise_list = $appraise_object->items;
		$this->_aParams['appraise_wait_deal'] = $appraise_object->totalSize;
		$service_person = new base_service_person_person();
		if (!empty($appraise_list)) {
			$person_ids = base_lib_BaseUtils::getPropertys($appraise_list, "person_id");
			$person_infos = $service_person->getPersons($person_ids, "person_id,user_name,photo,small_photo,big_photo")->items;
			$person_list = base_lib_BaseUtils::array_key_assoc($person_infos, "person_id");
			foreach ($appraise_list as $k => $v) {
				$person = $person_list[ $v["person_id"] ];
				$appraise_list[ $k ]["person_user_name"] = $person["user_name"];
				$appraise_list[ $k ]['person_small_photo'] = base_lib_BaseUtils::nullOrEmpty($person['small_photo']) ? base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person['photo'] : base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person['small_photo'];
				if (empty($person['photo']) && empty($person['small_photo'])) {
					$appraise_list[ $k ]['person_small_photo'] = base_lib_Constant::STYLE_URL . "/img/appeal/default_head.jpg";
				}

				$appraise_list[ $k ]['appraise_time'] = base_lib_TimeUtil::to_friend_time2($v['appraise_time']);
			}
			$this->_aParams['appraise_list'] = $appraise_list;
		}
		$this->_aParams['area_id'] = $company['area_id'];
		$this->_aParams['is_local_company'] = $company_resources->location_area != "NOT_CQ_CITY";
		$this->_aParams['calling_id'] = $company['calling_id'];
		//获取注册推广信息
		$Promotionreg = $this->getPromotionreg();

		$this->_aParams['promotion'] = $Promotionreg;
		//判断 并获取推广码
		if ((in_array($company['area_id'], $Promotionreg['area_ids']) || in_array($company['calling_id'], $Promotionreg['calling_ids'])) && $Promotionreg['is_effect'] == 1) {
			$this->_aParams['promotion_code_link'] = $this->getCompanyPromotionCode();
		}
		$commonArea = new base_service_common_area();
		$commonCalling = new base_service_common_calling();
		$this->_aParams['company_flag'] = $company['company_flag'];
		$prolist = $this->getPromotionRegistered($company['company_id']);
		$companyArea = $commonArea->getArea($company['area_id'], false);
		if (in_array($companyArea['parent_id'], $Promotionreg['main_area'])) {
			$showAreaName = $commonArea->getAreaName($companyArea['parent_id']);
		}
		else {
			$showAreaName = $companyArea['area_name'];
		}
		$this->_aParams['company_Areaname'] = $showAreaName;
		$this->_aParams['company_Calling'] = $commonCalling->getCallingName($company['calling_id']);
		$this->_aParams['prolist'] = $prolist;
		$showEveryDay = empty($_COOKIE['isShowEveryDay']) ? null : $_COOKIE['isShowEveryDay'];
		$showLQ = empty($_COOKIE['isShowPromotionShowLQ']) ? null : $_COOKIE['isShowPromotionShowLQ'];
		$showFX = empty($_COOKIE['isShowPromotionShowFX']) ? null : $_COOKIE['isShowPromotionShowFX'];
		if ($is_audit == 0 && !empty($showLQ)) {
			$this->_aParams['ShowPromotion'] = true;
		}
		else if ($is_audit == 2 && !empty($showLQ)) {
			$this->_aParams['ShowPromotion'] = false;
		}
		else if ($is_audit == 2 && !empty($showFX)) {
			$this->_aParams['ShowPromotion'] = true;
		}
		if (!empty($showEveryDay)) {
			$this->_aParams['ShowEveryDay'] = true;
		}
		else {
			$this->_aParams['tomorrow'] = strtotime(date("Y-m-d") . ' 23:59:59') * 1000;
		}

		$outer_job_service = new base_service_outer_outerjob();

		$this->_aParams['importData'] = $outer_job_service->is_import($this->_userid, true);

		//今日待面试
		$service_invite = new base_service_company_resume_jobinvite();
		$no_audition_items = $service_invite->getInviteListByCompanyv3($company_resources->all_accounts, "invite_id,info_job_invite.person_id,info_job_invite.resume_id,info_job_invite.station,info_job_invite.create_time,re_status,audition_result,job_id,audition_time,company_id", 1);
		$has_audition_items = $service_invite->getInviteListByCompanyv3($company_resources->all_accounts, "invite_id,info_job_invite.person_id,info_job_invite.resume_id,info_job_invite.station,info_job_invite.create_time,re_status,audition_result,job_id,audition_time,company_id", 2);
		if (!empty($no_audition_items)) {
			// 求职者
			$person_ids = implode(',', base_lib_BaseUtils::getProperty($no_audition_items, 'person_id'));
			$person_list = $service_person->GetPersonListByIDs($person_ids, 'person_id,user_name,sex,birthday2,cur_area_id,start_work,photo,small_photo,mobile_phone,telephone')->items;
			$person_list = base_lib_BaseUtils::array_key_assoc($person_list, "person_id");
			foreach ($no_audition_items as $k => $v) {
				$person_info = $person_list[ $v["person_id"] ];
				//姓名
				$no_audition_items[ $k ]['user_name'] = base_lib_BaseUtils::cutstr($person_info['user_name'], 4, 'utf-8', '', '...');

				if (!empty($v['audition_time'])) {
					$no_audition_items[ $k ]['audition_time_hour'] = date('H:i', strtotime($v['audition_time']));
					$no_audition_items[ $k ]['audition_time_day'] = date('Y-m-d', strtotime($v['audition_time']));
					if ($no_audition_items[ $k ]['audition_time_day'] == date('Y-m-d')) {
						$no_audition_items[ $k ]['audition_time_day'] = '今天';
					}
					else if ($no_audition_items[ $k ]['audition_time_day'] == date('Y-m-d', strtotime("+1 day"))) {
						$no_audition_items[ $k ]['audition_time_day'] = '明天';
					}
					else if ($no_audition_items[ $k ]['audition_time_day'] == date('Y-m-d', strtotime("-1 day"))) {
						$no_audition_items[ $k ]['audition_time_day'] = '昨天';
					}
					else if ($no_audition_items[ $k ]['audition_time_day'] == date('Y-m-d', strtotime("-2 day"))) {
						$no_audition_items[ $k ]['audition_time_day'] = '前天';
					}

				}
			}
		}
		if (!empty($has_audition_items)) {
			// 求职者
			$person_ids = implode(',', base_lib_BaseUtils::getProperty($has_audition_items, 'person_id'));
			$person_list = $service_person->GetPersonListByIDs($person_ids, 'person_id,user_name,sex,birthday2,cur_area_id,start_work,photo,small_photo,mobile_phone,telephone')->items;
			$person_list = base_lib_BaseUtils::array_key_assoc($person_list, "person_id");
			foreach ($has_audition_items as $k => $v) {
				$person_info = $person_list[ $v["person_id"] ];
				//姓名
				$has_audition_items[ $k ]['user_name'] = base_lib_BaseUtils::cutstr($person_info['user_name'], 4, 'utf-8', '', '...');
				if (!empty($v['audition_time'])) {
					$has_audition_items[ $k ]['audition_time_hour'] = date('H:i', strtotime($v['audition_time']));
					$has_audition_items[ $k ]['audition_time_day'] = date('Y-m-d', strtotime($v['audition_time']));
					if ($has_audition_items[ $k ]['audition_time_day'] == date('Y-m-d')) {
						$has_audition_items[ $k ]['audition_time_day'] = '今天';
					}
					else if ($has_audition_items[ $k ]['audition_time_day'] == date('Y-m-d', strtotime("+1 day"))) {
						$has_audition_items[ $k ]['audition_time_day'] = '明天';
					}
					else if ($has_audition_items[ $k ]['audition_time_day'] == date('Y-m-d', strtotime("-1 day"))) {
						$has_audition_items[ $k ]['audition_time_day'] = '昨天';
					}
					else if ($has_audition_items[ $k ]['audition_time_day'] == date('Y-m-d', strtotime("-2 day"))) {
						$has_audition_items[ $k ]['audition_time_day'] = '前天';
					}

				}
			}
		}
		$this->_aParams['no_audition_items'] = $no_audition_items;
		$this->_aParams['has_audition_items'] = $has_audition_items;
		$cq_main_areas = $commonArea->getNearAreas();//主城地区编号
		$cq_main_areas = array_merge($cq_main_areas, ['0316', '0318']);
		if (strlen($company['area_id']) > 4) {
			$company_area_id = substr($company["area_id"], 0, 4);
		}
		else {
			$company_area_id = $company['area_id'];
		}
		$is_free_vip = true;
		if (in_array($company_area_id, $cq_main_areas) && $company['calling_id'] != '55') {
			$is_free_vip = false;
		}
		$this->_aParams['is_free_vip'] = $is_free_vip;

		//贾思云需求，待启会员诱导开启会员逻辑
		if ($company["com_level"] <= 1 || $company_resource_info['isNewService']) {
			$download_total = $service_download->cacheDownLoadNum();//7天内简历下载数量
			$service_personstatistics = new base_service_person_personstatistics();
			$refresh_times = $service_personstatistics->cacheRefreshTimes();
			$this->_aParams['download_total'] = $download_total;
			$this->_aParams['refresh_times'] = $refresh_times;

			$service_solr_resume = new base_service_solr_resume();
			$resume_ids = $service_solr_resume->resumeRecommendByCompany($this->_userid, 1, 6)['docs'];

			$personIds = array ();
			$service_areaexp = new base_service_person_areaexp();
			$areas = $service_areaexp->getAreaExpByResumeIds(base_lib_BaseUtils::getProperty($resume_ids, 'id'));
			$person_exp_areas = base_lib_BaseUtils::array_key_assoc($areas, 'resume_id');

			$service_jobsort = new base_service_common_jobsort();
			$service_jobsortexp = new base_service_person_resume_jobsortexp();
			$jobsorts = $service_jobsortexp->getJobsortByResumeIds(base_lib_BaseUtils::getProperty($resume_ids, 'id'));
			$person_jobsorts = base_lib_BaseUtils::array_key_assoc($jobsorts, 'resume_id');
			$resume_ids = base_lib_BaseUtils::getProperty($resume_ids, 'id');
			if (!empty($resume_ids)) {
				$resume_ids = $service_resume->getResumes($resume_ids, 'resume_id,person_id,station')->items;
			}
			if (strlen($company['area_id']) > 4) {
				$company_area_id = substr($company["area_id"], 0, 4);
			}
			else {
				$company_area_id = $company['area_id'];
			}

			foreach ($resume_ids as $k => $v) {
				array_push($personIds, $v['person_id']);
				//获取意向地区

				$area_items = explode(",", $person_exp_areas[ $v['resume_id'] ]['area_id']);

				$first_area = array ();
				$other_area = array ();

				if (!base_lib_BaseUtils::nullOrEmpty($area_items)) {
					$area_name = '';
					$area_ids = '';
					$area_ids_arr = array ();
					for ($i = 0; $i < sizeof($area_items); $i++) {
						$area = $commonArea->getArea($area_items[ $i ], false);
						if ($i == sizeof($area_items) - 1) {
							$area_ids .= $area['area_id'];
						}
						else {
							$area_ids .= $area['area_id'] . ',';
						}
						array_push($area_ids_arr, $area['area_id']);
					}
					$area_name = $this->getSpecialAreaName($commonArea, $area_ids_arr, $area_ids);
					$area_name_arr = explode(';', $area_name);
//                    for($i=0;$i<count($area_name);$i++){
//                        if($area_name[$i] == $service_area->getArea($company['area_id'], true)){
//                            array_push($first_area, $area_name[$i]);
//                        }else{
//                            array_push($other_area, $area_name[$i]);
//                        }
//                    }
					//期望工作地区
					$new_area_name = $commonArea->getArea($company_area_id, true) . ';' . $area_name;
					for ($i = 0; $i < count($area_name_arr); $i++) {
						if ($area_name_arr[ $i ] == $commonArea->getArea($company_area_id, true) && $i == 0) {
							$new_area_name = $area_name;
						}
					}

				}

				$resume_ids[ $k ]['area_name'] = $new_area_name;
				//期望工作
				$jobsort_items = explode(",", $person_jobsorts[ $v['resume_id'] ]['jobsort']);
				if (!base_lib_BaseUtils::nullOrEmpty($jobsort_items)) {
					$str_expect_jobsorts_a = '';
					$str_expect_jobsorts_b = '';
					for ($i = 0; $i < sizeof($jobsort_items); $i++) {
						if (in_array($jobsort_items[ $i ], $jobsort_arr) && !empty($jobsort_arr)) {
							if (sizeof($jobsort_items) == 1 || $i == sizeof($jobsort_items) - 1) {
								$str_expect_jobsorts_a .= $service_jobsort->getJobsortName($jobsort_items[ $i ]);
							}
							else {
								$str_expect_jobsorts_a .= $service_jobsort->getJobsortName($jobsort_items[ $i ]) . ',';
							}
						}
						else {
							if (sizeof($jobsort_items) == 1 || $i == sizeof($jobsort_items) - 1) {
								$str_expect_jobsorts_b .= $service_jobsort->getJobsortName($jobsort_items[ $i ]);
							}
							else {
								$str_expect_jobsorts_b .= $service_jobsort->getJobsortName($jobsort_items[ $i ]) . ',';
							}
						}

					}
				}

				$resume_ids[ $k ]['str_expect_callings'] = $str_expect_jobsorts_a . $str_expect_jobsorts_b ? $str_expect_jobsorts_a . $str_expect_jobsorts_b : $v['station'];
			}

			$service_person = new base_service_person_person();
			$persons = $service_person->getPersons($personIds, 'person_id,user_name,photo,sex,birthday2,start_work')->items;
			$persons = base_lib_BaseUtils::array_key_assoc($persons, 'person_id');

			foreach ($resume_ids as $k => $v) {
				$resume_ids[ $k ]['user_name'] = $persons[ $v['person_id'] ]['sex'] == 1 ? mb_substr($persons[ $v['person_id'] ]['user_name'], 0, 1) . '先生' : mb_substr($persons[ $v['person_id'] ]['user_name'], 0, 1) . '女士';
				$resume_ids[ $k ]['photo'] = $persons[ $v['person_id'] ]['photo'] ? base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $persons[ $v['person_id'] ]['photo'] : base_lib_Constant::STYLE_URL . "/img/m/new_person/headportrait.png";
				$resume_ids[ $k ]['sex'] = $persons[ $v['person_id'] ]['sex'] == 1 ? "男" : "女";
				$resume_ids[ $k ]['age'] = base_lib_TimeUtil::ceil_diff_year($persons[ $v['person_id'] ]['birthday2']);
				$basic_start_work_year = base_lib_TimeUtil::date_diff_month($persons[ $v['person_id'] ]['start_work']);
				$workY = floor($basic_start_work_year / 12);
				$workM = intval($basic_start_work_year % 12);
				if ($workY <= 0 && $workM <= 6 && $workM > -6) {
					$basic_start_work_year = '应届毕业生';
				}
				else if ($workY == 0 && $workM > 6) {
					$basic_start_work_year = $workM . '个月工作经验';
				}
				else if ($basic_start_work_year <= -6) {
					$basic_start_work_year = '目前在读';
				}
				else {
					$basic_start_work_year = $workY . '年工作经验';
				}
				$resume_ids[ $k ]['start_work'] = $basic_start_work_year;
			}

			$this->_aParams['recommend_resume'] = $resume_ids;
			$service_solr_company = new base_service_solr_company();
			$service_company = new base_service_company_company();
			$service_apply = new base_service_company_resume_apply();
			$service_job = new base_service_company_job_job();
			$turn = 1;
			$recommend_companys = array ();

			while (true) {
				if ($turn > 10) {
					break;
				}
				switch ($turn) {
					//当地同行业
					case 1:
						$postvar['calling'] = $company['calling_id'];
						$postvar['location_area_id'] = in_array($company_area_id, $cq_main_areas) ? '0300' : $company['area_id'];
						break;
					//当地不同行业
					case 2:
						$postvar['calling'] = '';
						$postvar['location_area_id'] = in_array($company_area_id, $cq_main_areas) ? '0300' : $company['area_id'];
						break;
					//异地同行业
					case 3:
						$postvar['calling'] = $company['calling_id'];
						$postvar['location_area_id'] = '';
						break;
					//异地不同行业
					case 4:
						$postvar['calling'] = '';
						$postvar['location_area_id'] = '';
						break;
					default:
						$postvar['calling'] = '';
						$postvar['location_area_id'] = '';
						break;
				}
				$postvar['page_size'] = 100;
				$postvar["com_level"] = 2;
				$postvar["sort"] = 2;
				$postvar["has_job"] = 1;
				$company_ids = $service_solr_company->recommendCompanyByCity($postvar)['jobIDs'];

				if (!empty($company_ids)) {
					//投递数量
					$company_apply_total = $service_apply->getCount($company_ids);
					$company_apply_total = base_lib_BaseUtils::array_key_assoc($company_apply_total, 'company_id');
					//简历下载数量
					$company_download_total = $service_download->getCount($company_ids);
					$company_download_total = base_lib_BaseUtils::array_key_assoc($company_download_total, 'company_id');
					$_companys = array ();
					for ($i = 0; $i < count($company_ids); $i++) {
						$total = $company_apply_total[ $company_ids[ $i ] ]['total'] + $company_download_total[ $company_ids[ $i ] ]['total'];
						$_company_ids = base_lib_BaseUtils::getProperty($recommend_companys, 'company_id');
						if ($total >= 50 && (count($_companys) + count($recommend_companys) < 6 && !in_array($company_ids[ $i ], $_company_ids))) {
							$is_famous = $service_company->getCompany($company_ids[ $i ], 1, 'is_famous')['is_famous'];
							array_push($_companys, ['company_id' => $company_ids[ $i ], "down_total" => $total, "is_famous" => $is_famous]);
						}
					}
					$_companys = base_lib_BaseUtils::array_sort2($_companys, 'down_total');
					$_companys = base_lib_BaseUtils::array_sort2($_companys, 'is_famous');
				}

				if (empty($recommend_companys)) {
					$recommend_companys = $_companys;
				}
				else {
					$recommend_companys = array_merge($recommend_companys, $_companys);
				}

				if (count($recommend_companys) >= 6) {
					break;
				}

				$turn++;
			}


			$xml = SXML::load('../config/company/company.xml');
			if (!is_null($xml)) {
				$VirtualName = $xml->VirtualName;//虚拟目录名 CompanyImages
				$LogoFolder = $xml->LogoFolder;// <!--logo文件夹名-->
			}

			if($recommend_companys){
				foreach ($recommend_companys as $k => $v) {
					$company_info = $service_company->getCompany($v['company_id'], '1', 'company_id,company_flag,company_name,company_shortname,company_logo_path');
					$recommend_companys[ $k ]['company_name'] = $company_info['company_shortname'] ? $company_info['company_shortname'] : $company_info['company_name'];
					$recommend_companys[ $k ]['company_flag'] = $company_info['company_flag'];

					if ($company_info['company_logo_path'] != '') {
						$logo_path = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $VirtualName . '/' . $LogoFolder . '/' . $company_info['company_logo_path'];
					}
					else {
						$logo_path = base_lib_Constant::STYLE_URL . '/img/c/new_index/headlogo.png';//sx 7.22
					}

					$recommend_companys[ $k ]['logo_path'] = $logo_path;
					$recommend_companys[ $k ]['company_path'] = base_lib_Constant::MAIN_URL_NO_HTTP . '/qiye/' . $company_info['company_flag'] . '.html';
					$recommend_companys[ $k ]['job_count'] = $service_job->getJobCount($v['company_id']);

				}
			}


			$this->_aParams['recommend_companys'] = $recommend_companys;
		}
		if (!base_lib_BaseUtils::getCookie("audition_tip_msg")) {
			base_lib_BaseUtils::ssetcookie(["audition_tip_msg" => 1], 3600 * 24 * 365, '/', base_lib_Constant::COOKIE_DOMAIN);
		}

		//精准推广统计
		self::spreadJobStatistics($job_list);
		//升级维护公告
		$this->_aParams['newBulletin'] = self::getNewBulletin();

		//企业风采 上传情况, 图片点亮
		//项目
		$base_service_introduce_project = new base_service_introduce_project();
		$projectlist = $base_service_introduce_project->getListByCompanyId($company['company_id'], 'id')->items;

		//高管
		$base_service_introduce_management = new base_service_introduce_management();
		$managelist = $base_service_introduce_management->getListByCompanyId($company['company_id'], 'id')->items;

		//企业图片
		$base_service_company_companyphotoalbum = new base_service_company_companyphotoalbum();
		$companyphotolist = $base_service_company_companyphotoalbum->getPhotoAlbumList($company['company_id'], 'photo_path')->items;

        //融资阶段
        $service_stat = new base_service_company_comstate();
        $company_states = $service_stat->getCompanyState($this->_userid,'frnance_type');

        //2020完善资料弹框, false:已经完善 true：需要去完善, by Pcg, time:2020-02-14
        $need_alert_modify = ['franace'=>false, 'photolist'=>false, 'project'=>false, 'manager'=>false];
        $need_alert_modify_bool = false;// false:已经完善 true：需要去完善
        if(empty($company_states['frnance_type'])){
        	$need_alert_modify['franace'] = true;
            $need_alert_modify_bool = true;
		}
		if(empty($companyphotolist)){
            $need_alert_modify['photolist'] = true;
            $need_alert_modify_bool = true;
		}
        if(empty($projectlist)){
            $need_alert_modify['project'] = true;
            $need_alert_modify_bool = true;
        }
        if(empty($managelist)){
            $need_alert_modify['manager'] = true;
            $need_alert_modify_bool = true;
        }
        $this->_aParams['need_alert_modify'] = $need_alert_modify;
        $this->_aParams['need_alert_modify_bool'] = $need_alert_modify_bool;
        if($need_alert_modify_bool)
		{
			//---需要弹框提示完善企业资料--

            //融资阶段
			if(empty($company_states['frnance_type'])){
				$service_franace = new base_service_common_frnance();
				$franaces = $service_franace->getFrance();
				$this->_aParams['franaces'] = $franaces;
            }

            //企业相册图片--不判断 因为要公用上传组件的js,csss
			$ser_upload                   = new base_service_upload_upload();
			$up_options                   = array(
				'file_name'      => 'hddNewPhotoName[]',
				'fileVal'        => 'Filedata',
				'auto'           => true,
				'is_load_jquery' => false
			);
			$up_options['defaults_files'] = [];
			$this->_aParams['up_img_html']  = $ser_upload->GetUploadHtmlDom($up_options, $this->_userid, 'up_style4', 'img', 'newPhoto', '/company/company.xml');


            //产品
			if(empty($projectlist)){
                $ser_upload = new base_service_upload_upload();
                $up_options = array ('file_name' => 'hddProjectName[]','fileVal' => 'Filedata', 'auto' => true, 'is_load_jquery' => false);
                $this->_aParams['project_img_html'] = $ser_upload->GetUploadHtmlDom($up_options, $this->_userid, 'up_style4', 'img', 'introduce', '/company/company.xml');

            }

            if(empty($managelist)){
                $ser_upload = new base_service_upload_upload();
                $up_options = array ('file_name' => 'hddMnagerName[]','fileVal' => 'Filedata', 'auto' => true, 'is_load_jquery' => false);
                $this->_aParams['manager_img_html'] = $ser_upload->GetUploadHtmlDom($up_options, $this->_userid, 'up_style4', 'img', 'manage', '/company/company.xml');

            }

			$this->_aParams['modify_notice_html'] = $this->render("./modify_notice.html",$this->_aParams);
        }

		//企业引导完善资料弹窗
		if ($projectlist || $managelist || $companyphotolist) {
			$this->_aParams['show_light2'] = 1;
		}
		else {
			$this->_aParams['show_light2'] = 0;
		}
		if ($projectlist && $managelist && $companyphotolist) {
			$this->_aParams['show_light'] = 1;
		}
		else {
			$this->_aParams['show_light'] = 0;
		}


		//企业评分
		$service_companyServiceGrade = new base_service_company_service_companyServiceGrade();
		$grade_info = $service_companyServiceGrade->GetCompanyServiceGradeByCompanyId($company['company_id'], 'id,service_end_time,create_time,status');
		if ($grade_info['id']) {
			if ($grade_info['status'] == 0 && $grade_info['service_end_time'] > date('Y-m-d H:i:s', time()) && $grade_info['create_time'] > date('Y-m-d H:i:s', strtotime('-3 months', time()))) {
				$this->_aParams['open_grade'] = 1;
				base_lib_BaseUtils::ssetcookie(["grade_id" => $grade_info['id']], 0, '/', base_lib_Constant::COOKIE_DOMAIN);
			}
			else {
				$this->_aParams['open_grade'] = 0;
			}
		}
		else {
			$this->_aParams['open_grade'] = 0;
		}
		$this->_aParams['cookie_grade_id'] = base_lib_BaseUtils::getCookie('grade_id');
		//职位浏览量、投递量、邀请量
		//self::getVisitToYesterDayData($company['company_id']);

		//营业执照-公司真实名称
		$service_companyaudit = new base_service_company_audit();
		$companyaudit = $service_companyaudit->getCompanyLicenceInfor($this->_userid, 'real_company_name,is_audit');
		$this->_aParams['companyname'] = $companyaudit['real_company_name'];

		$company_service = new base_service_company_company();
		$company = $company_service->getCompany($this->_userid, 1, 'com_level,end_time,is_test,site_type');

		//判断是否签订使用协议
		$base_service_company_agreement = new base_service_company_agreement();
		$info = $base_service_company_agreement->getAgreeMentByCompany($this->_userid, 'id');
		if (empty($info) && ($company['com_level'] > 1 && $company['end_time'] > date('Y-m-d H:i:s') && $company['is_test'] < 1) && $companyaudit['real_company_name'] && $companyaudit['is_audit'] == 1) {
			$this->_aParams['have_agreement'] = 0;
		}
		else {
			$this->_aParams['have_agreement'] = 1;
		}

		$this->_aParams['audit_code'] = $audit_info['code'];
		$this->_aParams['audit_msg'] = $audit_info['msg'];
		$CompanyAuditStatus = $company_resources->CompanyAuditStatus();
		$this->_aParams['CompanyAuditStatus'] = $CompanyAuditStatus;
		//登录账号是否为主账号
		$is_main = false;
		if ($account['is_main'] == 1) {
			$is_main = true;
		}
		$this->_aParams['is_main'] = $is_main;

		//点击分享海报按钮控制
		//1.如果企业暂无在招职位，则提示“您当前没有在招职位，无法生成招聘海报”
		$poster_msg = "";
		$poster_job_id = 0;
		$is_share_h5 = 0;
		if (empty($job_list) || $poster_job_num < 1) {
			$poster_msg = "您当前没有在招职位，无法生成招聘海报";
			$is_share_h5 = 1;
		}
		else {
			//2.如果企业有在招职位但企业是待启会员或企业认证状态不是已认证，则提示“您当前职位不能公开展示，无法生成招聘海报"
			if ($company['com_level'] < 1 || $CompanyAuditStatus != '认证通过') {
				$poster_msg = "您当前职位不能公开展示，无法生成招聘海报";
				$is_share_h5 = 2;
			}
			else {
				if ($poster_job_num == 1) {
					$poster_job_id = $poster_job_id_temp;
				}
			}
		}

		$this->_aParams['has_pub_job'] = $poster_job_num;
		$this->_aParams['poster_job_id'] = $poster_job_id;
		$this->_aParams['poster_msg'] = $poster_msg;
		$this->_aParams['is_share_h5'] = $is_share_h5;
        $service_question = new base_service_company_question();

        if($service_question->canAnswer($this->_userid)){
            $this->_aParams['is_question'] = 1;
        }

		//当月餐饮免费会员发布职位限制
		$base_service_solr_job = new base_service_solr_job();
		$jobList = $base_service_solr_job->GetCompanyAlreadyPublishJobByCompanyId($this->_userid,date('Y-m-01',time()),10);

        //海报分享效果
        $service_person_statistics_recordSaveActionLog = new base_service_person_statistics_recordSaveActionLog();
        $this->_aParams['share_effect_num'] = $service_person_statistics_recordSaveActionLog->getShareEffectNum($company_resources->all_accounts);

		//企业年报二维码
		$this->_aParams['companyAnnualReport'] = $this->getCompanyAnnualReport();

		//判断是否有需要签章的电子合同
		$company_service_contractEcontract = new company_service_contractEcontract();
		$info = $company_service_contractEcontract->getInfoByCompanyId($this->_userid,'econtract_state');
		$have_editioncontract = 0;
		foreach ($info as $key=>&$val){
			if ($val['econtract_state']==2){
				$have_editioncontract = 1;
			}
		}
		$this->_aParams["have_editioncontract"] = $have_editioncontract;

		$this->_aParams['job_count'] = count($jobList);
		if ($company_resource_info['isCqNewService']) {
			$service_servicePricing = new base_service_company_service_servicePricing();
			$selling = $service_servicePricing->GetFunParallelismSelling('point_job_refresh');
			$this->_aParams['selling'] = $selling;
			$this->_aParams['cq_is_batch_refresh'] = $company_resource_info['cq_is_batch_refresh'];
			$this->_aParams['cash'] = (int)($companyresources['spread_overage'] + $companyresources['account_overage']); //推广金和余额综合
			// $this->_aParams['cash']    = 10000; //推广金和余额综合
			$this->_aParams["service_type"] = 1;

			return $this->render("./new_indexv4.html", $this->_aParams);
		}


		if ($company_resource_info['isNewService']) {
			if ($company_resource_info['job_boutique_release']) {
				$this->_aParams['can_boutique_job'] = true;
			}
			else {
				$this->_aParams['can_boutique_job'] = false;
			}
			$this->_aParams["service_type"] = 2;

			return $this->render("./new_indexv3.html", $this->_aParams);
		}
		$this->_aParams["service_type"] = 0;


		return $this->render("./new_indexv2.html", $this->_aParams);


	}

    /**
     * 2020 完善资料弹框修改保存
	 * 提交路径：/index/SaveModifyNotice
	 * 提交参数：
	 * france:  int  融资阶段
	 * hddNewPhotoName： array  ['1008130721249_554.jpg','1008130721249_554.png',....]  办公环境
	 * project_arr： aray  [ ['name','info','pic'],['name','info','pic'].... ]  产品项目
	 * manager_arr： aray  [ ['name','info','station','pic'] ,['name','info','station','pic'] .... ]  高管
	 *
	 * 返回 json ['status','msg']
     */
    public function pageSaveModifyNotice($inPath)
    {
        /**
         * 处理逻辑：
         * 前提是没有填写相关资料的才完善，这里只需做新增操作
         * 完善资料项：
         * 1、融资阶段
         * 2、办公环境
         * 3、产品介绍
         * 4、老板介绍
         */
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

        //融资阶段
        $france_type = base_lib_BaseUtils::getStr($pathdata['france'], 'int', 0);

        //办公环境
        $photo_url_arr = base_lib_BaseUtils::getStr($pathdata['hddNewPhotoName'], 'array', '');

        //产品项目：project_arr = [ ['name','info','pic'] ]
        $projects = base_lib_BaseUtils::getStr($pathdata['project_arr'], 'array', '');

        //高管 :manager_arr = [ ['name','info','station','pic'] ]
        $managers = base_lib_BaseUtils::getStr($pathdata['manager_arr'], 'array', '');


        if (!empty($projects)) {
            foreach ($projects as $k => $project) {

                if(empty($project['name']) && empty($project['info'])  && empty($project['pic'])){
                    unset($projects[$k]);
                    continue;
                }
            	
            	if(empty($project['name']) || empty($project['info']) || empty($project['pic'])){
                    exit(json_encode(array('status' => false, 'msg' => '未填写内容，请填写')));
                }
            }
        }

        if (!empty($managers)) {
            foreach ($managers as $k => $manage) {

                if(empty($manage['name']) && empty($manage['info']) && empty($manage['station']) && empty($manage['pic'])){
                    unset($managers[$k]);
                    continue;
                }

                if(empty($manage['name']) || empty($manage['info']) || empty($manage['station']) || empty($manage['pic'])){
                    exit(json_encode(array('status' => false, 'msg' => '未填写内容，请填写')));
                }
            }
        }

        if (empty($france_type) && empty($photo_url_arr) && empty($projects) && empty($managers))
            exit(json_encode(['status' => false, 'msg' => '不能全部为空', 'code' => 1]));



        $xml = SXML::load('../config/company/company.xml');
        if (!is_null($xml)) {
            $photo_max_count = $xml->PhotoMaxCount;
        }
        if (count($photo_url_arr) > $photo_max_count) {
        	exit(json_encode(array('status' => false, 'msg' => '企业办公环境数量不能超过'.$photo_max_count.'个')));
        }

        if (count($projects) > 3) {
            exit(json_encode(array('status' => false, 'msg' => '产品/项目数量不能超过三个')));
        }

        if (count($managers) > 5) {
            exit(json_encode(array('status' => false, 'msg' => '老板/高管介绍数量不能超过三个')));
        }


        //融资阶段 -》保存
        if (!empty($france_type)) {
            $service_state = new base_service_company_comstate();
            $service_state->UpdateComStateByCompanyIds([$this->_userid], ['frnance_type' => $france_type]);

        }

        //办公环境 -》保存
        if (!empty($photo_url_arr)) {

            $service_companyphoto = new base_service_company_companyphotoalbum();
            $photo_names = array();
            for ($i = 0; $i < count($photo_url_arr); $i++) {
                $item['photo_name'] = '';
                $item['photo_path'] = $photo_url_arr[$i];
                $item['company_id'] = $this->_userid;

                $service_companyphoto->addPhotoAlbum($item);
                array_push($photo_names, $item['photo_path']);
            }

            $service_company = new base_service_company_company();
            $service_company->saveCompanyPhotoCount($this->_userid, count($photo_url_arr));
        }

        //产品项目 -》保存
        if (!empty($projects)) {
            $server_do                  = new base_service_introduce_project();
            $base_service_introduce_img = new base_service_introduce_img();
            foreach ($projects as $k => $project) {
                if(empty($project['name']) || empty($project['info']) || empty($project['pic'])){
                    exit(json_encode(array('status' => false, 'msg' => '未填写内容，请填写')));
				}
                $items['company_id'] = $this->_userid;
                $items['name']       = $project['name'];
                $items['details']    = $project['info'];
                $items['is_effect']  = 1;
                $ret                 = $server_do->addIntroduceProject($items);

                $data['is_effect']      = 1;
                $data['introduce_id']   = $ret;
                $data['introduce_type'] = 1;

                foreach ($project['pic'] as $val) {
                    $data['img_path'] = $val;
                    $base_service_introduce_img->addIntroducImg($data);
                }


            }
        }

        //老板介绍 -》保存
        if (!empty($managers)) {
            $server_do                  = new base_service_introduce_management();
            $base_service_introduce_img = new base_service_introduce_img();

            foreach ($managers as $k => $manage) {

                $items['company_id'] = $this->_userid;
                $items['name']       = $manage['name'];
                $items['position']   = $manage['station'];
                $items['details']    = $manage['info'];
                $items['is_effect']  = 1;
                $ret                 = $server_do->addIntroduceManage($items);

                $data['is_effect']      = 1;
                $data['introduce_id']   = $ret;
                $data['introduce_type'] = 2;
                foreach ($manage['pic'] as $val) {
                    $data['img_path'] = $val;
                    $base_service_introduce_img->addIntroducImg($data);
                }
            }
        }

        exit(json_encode(['status' => true, 'msg' => 'ok', 'code' => 0]));
    }

    /**
	 * 2020 完善资料获的产品介绍跟boss介绍获取新的图片传输组件
	 * 提交路径：/index/NoticeGetImgUpload
	 * 提交参数：
	 * type: 1 产品项目 ， 2 老板高管
	 * pic_name: 上传图片对应input的name属性值
     * @param $inPath
	 * 返回 json ['status','msg']
     */
    public function pageNoticeGetImgUpload($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $type =  base_lib_BaseUtils::getStr($pathdata['type'], 'int', 1);//1 产品项目 ， 2 老板高管
		$pic_name =  base_lib_BaseUtils::getStr($pathdata['pic_name'], 'string', '');

		if(!$pic_name){
			exit(json_encode(['status'=>false,'msg'=>'上传图片对应input的name不能为空']));
		}

		$html = '';
		$this->_aParams['pic_name'] = $pic_name;
		$this->_aParams['type'] = $type;

		switch ($type){
			case 1:
                $ser_upload = new base_service_upload_upload();
                $up_options = array ('file_name' => $pic_name.'[]','fileVal' => 'Filedata', 'auto' => true, 'is_load_jquery' => false);
                $this->_aParams['project_img_html'] = $ser_upload->GetUploadHtmlDom($up_options, $this->_userid, 'up_style4', 'img', 'introduce', '/company/company.xml');

				break;
			case 2:
                $ser_upload = new base_service_upload_upload();
                $up_options = array ('file_name' => $pic_name.'[]','fileVal' => 'Filedata', 'auto' => true, 'is_load_jquery' => false);
                $this->_aParams['manager_img_html'] = $ser_upload->GetUploadHtmlDom($up_options, $this->_userid, 'up_style4', 'img', 'manage', '/company/company.xml');

                break;
			default:
                exit(json_encode(['status'=>false,'msg'=>'type 只能是1 或者 2']));
				break;
		}
        $html = $this->render('./common/up_pic.html',$this->_aParams);

        exit(json_encode(['status'=>true,'msg'=>'ok','up_html'=>$html]));
	}

	/**
	 * 获取最新人才推荐简历
	 * 当企业有一周内发布职位，且职位1周内接收简历数在5份以下时，展示该职位的最新简历。当有多个这样的职位时，展示最新修改/发布的职位推荐简历。
	 */
	public function getNewestRecommendResume($job_list,$account_id){
		$service_person_person = new base_service_person_person();
		$service_solr_resume = new base_service_solr_resume();
		$service_person_resume_resume = new base_service_person_resume_resume();
		$service_person_resume_work = new base_service_person_resume_work();
		$service_edu = new base_service_person_resume_edu();
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $account_id);
		//1.获取单位一周内发布或者修改过的职务信息  issue_time,mod_time
		$week_time = date("Y-m-d",strtotime("-1 week"));
		$resume_list = [];
		if($job_list){
			$job_data = [];
			foreach($job_list as $key => $value){
				$issue_time = date("Y-m-d",strtotime($value['issue_time']));
				$mod_time = date("Y-m-d",strtotime($value['mod_time']));
				if($issue_time >= $week_time || $mod_time >= $week_time){
					array_push($job_data,$value);
				}
			}

			if($job_data){
				$job_data = base_lib_BaseUtils::array_key_assoc($job_data,"job_id");
				$job_ids = base_lib_BaseUtils::getPropertys($job_data,"job_id");

				//2.获取一周内获得简历少于5份的职位
				$applyJobService = new base_service_company_resume_apply();
				$statistics = $applyJobService->getApplyStatisticsByJobIdsVerson2($this->_userid, $job_ids,90)->items;
				$statistics = base_lib_BaseUtils::array_key_assoc($statistics,"job_id");
				$job_id = '';
				$job_last_time = '';
				foreach($job_data as $key => $value){
					if(isset($statistics[$value['job_id']])){
						//获得简历少于5
						if($statistics[$value['job_id']]['total_count'] < 5 && ($value['issue_time'] > $job_last_time || $value['mod_time'] > $job_last_time)){
							$job_id = $value['job_id'];
							$job_last_time = $value['issue_time'] > $value['mod_time'] ? $value['issue_time'] : $value['mod_time'];
						}
					}else{
						if($value['issue_time'] > $job_last_time || $value['mod_time'] > $job_last_time){
							$job_id = $value['job_id'];
							$job_last_time = $value['issue_time'] > $value['mod_time'] ? $value['issue_time'] : $value['mod_time'];
						}
					}
				}
				//3.根据职位获取推荐的最新人才
				if($job_id){
//					var_dump($job_id);
					$resume_ids = $service_solr_resume->newResumeRecommendByJob($job_id,$this->_userid,1,2);
//					var_dump($resume_ids);
//					$resume_ids = [7031294,7029119];
					if($resume_ids) {

						$field = "resume_id,person_id,user_name,create_time,sex,birthday,cur_area_id,degree_id,major_desc,work_year,station,appraise";
						$resume_list = $service_person_resume_resume->getResumes($resume_ids, $field)->items;

						$person_ids = base_lib_BaseUtils::getPropertys($resume_list, "person_id");
						$person_ids = implode(',', $person_ids);

						//职位名称
						$this->_aParams['newest_job_station'] = $job_data[$job_id]['station'];

						//求职信息
						$person_list = $service_person_person->GetPersonListByIDs($person_ids, 'person_id,user_name,name_open,photo_open,sex,birthday2,cur_area_id,start_work,photo,small_photo,mobile_phone,job_state_id,accession_time')->items;
						$person_list = base_lib_BaseUtils::array_key_assoc($person_list, "person_id");
						$work_datas = $service_person_resume_work->getLastResumeWorks($resume_ids, 'work_id,resume_id,start_time,end_time,station,company_name,work_content')->items;
						$work_datas = base_lib_BaseUtils::array_key_assoc($work_datas, "resume_id");
						//教育
						$resume_ids_temp = implode(',', $resume_ids);
						$edu_data = $service_edu->getResumeEdus($resume_ids_temp, 'resume_id,school,major_desc,degree')->items;

						$company_resources_info = $company_resources->getCompanyAuditStatusV2();
						$isshowresumeinfo = true;
						$letter_info = $this->CheckCompanyLetter($this->_userid);

						if ($company_resources_info['audit_type'] == 1) {
							//老规则
							$isshowresumeinfo = true;
						} else {
							//新规则
							if ($letter_info['code'] != 200) {
								$isshowresumeinfo = false;
							}
						}

						foreach ($resume_list as $key => $value) {
							$person_info = $person_list[$value['person_id']];

							$is_show_name = false; //是否显示简历的名字
							//姓名
							if ($is_show_name && $isshowresumeinfo) {
								$resume_list[$key]['user_name'] = $person_info['user_name'];
							} else {
								$sex_name = $person_info['sex'] == 1 ? '先生' : '女士';
								$resume_list[$key]['user_name'] = mb_substr($person_info['user_name'], 0, 1, 'utf-8') . $sex_name;
							}

							if ($person_info['photo_open'] === '0') {//允许null,和1一样，默认可以公开
								$person_info['photo'] = false;
								$person_info['small_photo'] = false;
							} else {
								if (base_lib_BaseUtils::nullOrEmpty($person_info['small_photo'])) {
									$person_info['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['photo'];
								} else {
									$person_info['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['small_photo'];
								}
								//兼容判断
								if (base_lib_BaseUtils::nullOrEmpty($person_info['photo']))
									$person_info['photo'] = false;
								else
									$person_info['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['photo'];
							}

							$resume_list[$key]['small_photo'] = $person_info['photo'];
							$resume_list[$key]['sex'] = $this->getSex($person_info['sex']);
							$resume_list[$key]['age'] = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']) . '岁';
							$resume_list[$key]['degree'] = $this->getDegree($value['degree_id']);
							$resume_list[$key]['cur_area'] = $this->getArea($person_info['cur_area_id']);

							//工作年限
							$basic_start_work_year = base_lib_TimeUtil::date_diff_month($person_info['start_work']);
							$workY = floor($basic_start_work_year / 12);
							$workM = intval($basic_start_work_year % 12);

							if ($workY <= 0 && $workM <= 6 && $workM >= -6) {
								$basic_start_work_year = '应届毕业生';
							} else if ($workY == 0 && $workM > 6) {
								$basic_start_work_year = $workM . '个月工作经验';
							} else if ($basic_start_work_year < -6) {
								$basic_start_work_year = '目前在读';
							} else {
								$basic_start_work_year = $workY . '年工作经验';
							}

							$resume_list[$key]['start_work'] = $basic_start_work_year ? $basic_start_work_year : '应届毕业生';
							$resume_list[$key]['station'] = $work_datas[$value['resume_id']]['station'];
							$resume_list[$key]['company_name'] = $work_datas[$value['resume_id']]['company_name'];
							//教育
							$edu_info = $this->arrayFind($edu_data, 'resume_id', $value['resume_id']);
							$resume_list[$key]['school'] = $edu_info['school'];
							$resume_list[$key]['major_desc'] = $edu_info['major_desc'];
							$resume_list[$key]['school_degree'] = $this->getDegree($edu_info['degree']);
						}
					}
				}

			}
		}

		$this->_aParams['newest_recommend_resume'] = $resume_list;


	}
    
    //海报分享效果首页列表
    public function pageShareEffectList($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		
		$page_size = base_lib_BaseUtils::getStr($pathdata['page_size'], 'int', 6);
		$page = base_lib_BaseUtils::getStr($pathdata['page'], 'int', 1);
        
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, base_lib_BaseUtils::getCookie('accountid'));
        $service_person_statistics_recordSaveActionLog = new base_service_person_statistics_recordSaveActionLog();
        $list = $service_person_statistics_recordSaveActionLog->getShareEffectList($company_resources->all_accounts, $page_size, $page);

        /* 页码 */
        $this->_aParams['page'] = $this->pageBarFullPath($list->totalSize, $page_size, $page, $inPath);
        $this->_aParams['list'] = $list->items;

        return $this->render("./shareeffectlist.html", $this->_aParams);
    }
    
    //海报分享效果未读数量
    public function pageGetShareEffectNum($inPath){
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, base_lib_BaseUtils::getCookie('accountid'));
        $service_person_statistics_recordSaveActionLog = new base_service_person_statistics_recordSaveActionLog();
        $share_effect_num = $service_person_statistics_recordSaveActionLog->getShareEffectNum($company_resources->all_accounts);
        echo json_encode(['share_effect_num'=>$share_effect_num]);
        return;
    }
	
	//保存 使用协议
	public function pageSaveAgreement($inPath) {
		$base_service_company_agreement = new base_service_company_agreement();
		$res = $base_service_company_agreement->addAgreeMent($this->_userid);
		if ($res) {
			echo json_encode(array ('status' => true, 'msg' => '协议签订成功'));
		}
		else {
			echo json_encode(array ('status' => false, 'msg' => '协议签订失败'));
		}
	}
	
	/**
	 * 获取地区名称(特殊  例如：重庆-主城区,北京-五环以内)
	 * @param $service_area       实例化对象
	 * @param array $area_ids_arr 选择的地区数组
	 * @param string $area_ids    选择的地区字符串
	 */
	private function getSpecialAreaName($service_area, $area_ids_arr, $area_ids) {
		$area_name = '';
		if (count($area_ids_arr) <= 0) {
			return $area_name;
		}
		$xml = SXML::load('../config/person/Person.xml');
		if (!is_null($xml)) {
			$cq_mian_city = $xml->CQMainCity;
			$cq_other_counties = $xml->CQOtherCounties;
			$bj_rings_within = $xml->BJRingsWithin;
			$bj_rings_without = $xml->BJRingsWithout;
			$sh_outer_within = $xml->SHOuterWithin;
			$sh_suburbs = $xml->SHSuburbs;
			$tj_mian_city = $xml->TJMainCity;
			$tj_other_counties = $xml->TJOtherCounties;
		}
		$temp_area_ids_arr = array ();
		//重庆
		$cq_mian_city_arr = explode(',', $cq_mian_city);
		$cq_other_counties_arr = explode(',', $cq_other_counties);
		
		$intersect_cqmain = implode(',', array_intersect($cq_mian_city_arr, $area_ids_arr)) == $cq_mian_city;
		$intersect_cqother = implode(',', array_intersect($cq_other_counties_arr, $area_ids_arr)) == $cq_other_counties;
		
		if ($intersect_cqmain && $intersect_cqother) {
			$area_name .= '重庆-主城区;重庆-周边区县;';
			$temp_area_ids_arr = array_diff($area_ids_arr, $cq_mian_city_arr);
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $cq_other_counties_arr);
		}
		elseif ($intersect_cqmain) {
			$area_name .= '重庆-主城区;';
			$temp_area_ids_arr = array_diff($area_ids_arr, $cq_mian_city_arr);
		}
		elseif ($intersect_cqother) {
			$area_name .= '重庆-周边区县;';
			$temp_area_ids_arr = array_diff($area_ids_arr, $cq_other_counties_arr);
		}
		$temp_area_ids_arr = count($temp_area_ids_arr) <= 0 && empty($area_name) ? $area_ids_arr : $temp_area_ids_arr;
		
		//北京
		$bj_mian_city_arr = explode(',', $bj_rings_within);
		$bj_other_counties_arr = explode(',', $bj_rings_without);
		
		$intersect_bjmain = implode(',', array_intersect($bj_mian_city_arr, $temp_area_ids_arr)) == $bj_rings_within;
		$intersect_bjother = implode(',', array_intersect($bj_other_counties_arr, $temp_area_ids_arr)) == $bj_rings_without;
		
		if ($intersect_bjmain && intersect_bjother) {
			$area_name .= '北京-五环以内;北京-五环以外;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $bj_mian_city_arr);
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $bj_other_counties_arr);
		}
		elseif ($intersect_bjmain) {
			$area_name .= '北京-五环以内;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $bj_mian_city_arr);
		}
		elseif ($intersect_bjother) {
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $bj_other_counties_arr);
			$area_name .= '北京-五环以外;';
		}
		//上海
		$sh_mian_city_arr = explode(',', $sh_outer_within);
		$sh_other_counties_arr = explode(',', $sh_suburbs);
		
		$intersect_shmain = implode(',', array_intersect($sh_mian_city_arr, $temp_area_ids_arr)) == $sh_outer_within;
		$intersect_shother = implode(',', array_intersect($sh_other_counties_arr, $temp_area_ids_arr)) == $sh_suburbs;
		
		if ($intersect_shmain && $intersect_shother) {
			$area_name .= '上海-外环以内;上海-郊区/县;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $sh_mian_city_arr);
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $sh_other_counties_arr);
		}
		elseif ($intersect_shmain) {
			$area_name .= '上海-外环以内;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $sh_mian_city_arr);
		}
		elseif ($intersect_shother) {
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $sh_other_counties_arr);
			$area_name .= '上海-郊区/县;';
		}
		//天津
		$tj_mian_city_arr = explode(',', $tj_mian_city);
		$tj_other_counties_arr = explode(',', $tj_other_counties);
		
		$intersect_tjmain = implode(',', array_intersect($tj_mian_city_arr, $temp_area_ids_arr)) == $tj_mian_city;
		$intersect_tjother = implode(',', array_intersect($tj_other_counties_arr, $temp_area_ids_arr)) == $tj_other_counties;
		
		if ($intersect_tjmain && $intersect_tjother) {
			$area_name .= '天津-主城区;天津-周边区县;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $tj_mian_city_arr);
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $tj_other_counties_arr);
		}
		elseif ($intersect_tjmain) {
			$area_name .= '天津-主城区;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $tj_mian_city_arr);
		}
		elseif ($intersect_tjother) {
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $tj_other_counties_arr);
			$area_name .= '天津-周边区县;';
		}
		$temp_area_ids_arr = array_merge($temp_area_ids_arr);
		if (count($temp_area_ids_arr) == 0) {
			$area_name = mb_substr($area_name, 0, mb_strlen($area_name, 'utf-8') - 1);
		}
		for ($m2 = 0; $m2 < count($temp_area_ids_arr); $m2++) {
			$area_temp_name = $service_area->getArea($temp_area_ids_arr[ $m2 ]);
			if ($m2 == sizeof($temp_area_ids_arr) - 1) {
				$area_name .= $area_temp_name;
			}
			else {
				$area_name .= $area_temp_name . ';';
			}
		}
		
		return $area_name;
	}
	
	/*
	 * 获取是否存在站外新职位
	 * */
	public function pagegetOuterJobs($inPath) {
		
		$company_id = $this->_userid;
		if (empty($company_id)) {
			return json_encode(array ('error' => 'error'));
		}
		$outer_job_service = new base_service_outer_outerjob();
		$jobs = $outer_job_service->importJobs($company_id);
		
		$this->_aParams['data'] = $jobs;
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		$companyresources = $company_resources->getCompanyServiceSource();
		$companyresources['refresh_today_overplus'] = max($companyresources['refresh_perday'] - $companyresources['refresh_today_num'], 0);
		$this->_aParams['companyresources'] = $companyresources;
		
		return $this->render('./importAjax.html', $this->_aParams);
	}
	
	/*
	 * 导入选择的站外关联职位
	 * */
	public function pageimportJobDo($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		
		$jobList = base_lib_BaseUtils::getStr($pathdata['data'], 'string', '');
		if (empty($jobList)) {
			echo json_encode(array ('status' => false, 'msg' => '参数错误'));
			
			return;
		}
		
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		$companyresources = $company_resources->getCompanyServiceSource();
		$companyresources['refresh_today_overplus'] = max($companyresources['refresh_perday'] - $companyresources['refresh_today_num'], 0);
		
		$jobList = explode(',', $jobList);
		$jobs = array ();
		$saveJobs = array ();
		foreach ($jobList as &$value) {
			$value = explode(':', $value);
			$saveJobs[] = $value;
			if ($value[1] == 1) {
				$jobs[] = $value[0];
			}
		}
		$max_insert_job_number = intval($companyresources['default_job_num'] - $companyresources['has_pub_job_num']);
		if (count($jobs) > $max_insert_job_number) {
			echo json_encode(array ('status' => false, 'msg' => '最多可导入' + $max_insert_job_number + '个职位'));
			
			return;
		}
		$outerjobs_Service = new base_service_outer_outerjob();
		//检查上传的此批职位是否已经导入  且返回职位相关信息
		
		$jobs = $outerjobs_Service->checkOutJobs($jobs, $this->_userid);
		
		if (!empty($jobs)) {
			
			$single_size = 0;
			$slice_jobs = array ();
			$count = count($jobs);
			$successNumber = 0;
			for ($i = 0; $i < $count; $i++) {
				array_push($slice_jobs, array_pop($jobs));
				$single_size = $single_size + 1;
				
				if ($single_size == 200 || ($i == $count - 1)) {
//					echo "新发布职位：".count($slice_jobs);
					//发布职位
					$successNumber += $outerjobs_Service->pubJob($slice_jobs);
					
					$single_size = 0;
					$slice_jobs = array ();
				}
			}
			$msg = '导入成功，本次一共导入了' . $successNumber . '个职位';
		}
		else {
			$successNumber = 0;
			$msg = '本次一共导入了' . $successNumber . '个职位';
		}
		//设置导入状态
		$outerjobs_Service->saveOuterJobImportStart($saveJobs);
		//导入成功
		echo json_encode(array ('status' => true, 'msg' => $msg));
		
		return;
	}
	
	
	/**企业首页弹窗**/
	private function __isShowDialog($company_not_update, $is_audit, $job_list, $is_interview, $is_end_month, $is_new_service, $audit_code) {
		$dialog_box = array (); //保存需要的弹窗数组
		if (!empty($company_not_update)) {
			array_push($dialog_box, array ("type" => "info"));  //企业资料
		}
		//审核不通过的弹窗
		if (in_array($audit_code, array (507, 508, 509))) {
			if (base_lib_BaseUtils::nullOrEmpty($_COOKIE["audit_not_pass"])) {
				array_push($dialog_box, array ("type" => "audit_not_pass"));
			}
		}
		//未提交营业执照
		if ($is_audit == 0) {
			if (base_lib_BaseUtils::nullOrEmpty($_COOKIE["no_audit"])) {
				array_push($dialog_box, array ("type" => "no_audit"));
				$this->_addMessage();//推送站内消息
			}
		}
		//未发布职位
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		$job_status = new base_service_common_jobstatus();
		if (empty($job_list) && !$is_end_month) {
			if (base_lib_BaseUtils::nullOrEmpty($_COOKIE["no_job"])) {
				array_push($dialog_box, array ("type" => "no_job", 'is_audit' => $is_audit));
			}
		}
		
		//用户已完善企业信息、已发布职位、已验证营业执照，且未上次委托书
		$letter_info = $company_resources->getCompanyAuditStatusV2();
		//企业会员等级
		$company_resources_info = $company_resources->getCompanyServiceSource();
		$company_service = new base_service_company_company();
		
		$company_level = $company_resources_info['company_level'];
		$company = $company_service->getCompany($this->_userid, 1, 'is_proxyed,proxy_com_id,end_time,com_level,start_time,end_time,is_audit,calling_id,area_id,audit_state,is_exterior');
		
		//企业资料未完善并且线外注册渠道特殊处理
		if (!empty($company_not_update) && $company['is_exterior'] == 1) {
			//array_push($dialog_box, array ("type" => "info_exterior"));
			$dialog_box[0]['type'] = 'info_exterior';
		}

		$getCompanyAuditStatus = $company_resources->getCompanyAuditStatus();
		$auditstatus = $getCompanyAuditStatus[0];
		$auditparams = $getCompanyAuditStatus[1];
		//委托书上传状态
		$letter_audit_type = $letter_info['letter_audit_type'];
//        //验证待补 未上传委托书 且延迟15天超期 提示
//        if($letter_audit_type == 0 && $auditstatus == 4 && !empty($auditparams['audit_expire']) && time() > strtotime($auditparams['audit_expire'])){
//            if (base_lib_BaseUtils::nullOrEmpty($_COOKIE["no_audit_letter_new"]) && base_lib_BaseUtils::nullOrEmpty($_COOKIE["no_audit_letter_old"])) {
//                $temp_type = (int)date('Y',strtotime($company['start_time'])) > 2018 ? 'no_audit_letter_new' : 'no_audit_letter_old';
//                array_push($dialog_box, array ("type" => $temp_type , 'expires'=> (int)strtotime(date('Y-m-d 00:00:00', strtotime('+1 day'))) * 1000));
//            }
//		}else if($letter_audit_type == 0 && $company_level > 1 && $company['start_time']){ //未上次委托书 且 收费会员
//            if (base_lib_BaseUtils::nullOrEmpty($_COOKIE["no_audit_letter_new"]) && base_lib_BaseUtils::nullOrEmpty($_COOKIE["no_audit_letter_old"])) {
//            	$temp_type = (int)date('Y',strtotime($company['start_time'])) > 2018 ? 'no_audit_letter_new' : 'no_audit_letter_old';
//            	array_push($dialog_box, array ("type" => $temp_type , 'expires'=> (int)strtotime(date('Y-m-d 00:00:00', strtotime('+1 day'))) * 1000));
//			}
//		}
		$to_date = '2019-05-13';
		//未上传业务单 新逻辑提示
		if ($letter_audit_type == 0) {
			if (!(($letter_info['licence_audit_type'] == 4
				&& $letter_info['licence_end_time'] >= $this->_ymd)
				|| ($letter_info['licence_audit_type'] == 0))
			) {
				$temp_type = strtotime($this->_ymd) <= strtotime($to_date) ? 'no_audit_letter_old' : 'no_audit_letter_new';
				array_push($dialog_box, array ("type" => $temp_type, 'expires' => (int)strtotime(date('Y-m-d 00:00:00', strtotime('+1 day'))) * 1000));
			}
		}
		
		
		//服务到期一个月内
		if ($is_end_month) {
			if (base_lib_BaseUtils::nullOrEmpty($_COOKIE["service_end"])) {
				array_push($dialog_box, array ("type" => "service_end"));
			}
		}
		
		//培训机构大起底活动弹窗
//		if ($is_interview) {
//			if (base_lib_BaseUtils::nullOrEmpty($_COOKIE["is_interview"])) {
//				array_push($dialog_box, array ("type" => "is_interview"));
//				$aCookie = array ('is_interview' => 'is_interview');
//				$time = strtotime(date("Y-m-d 23:59:59")) - time();
//				base_lib_BaseUtils::ssetcookie($aCookie, $time, '/');
//			}
//		}
		
		$is_member = $company_resources->isMember();
		if (!$is_member && !$is_new_service) {
			if ($company_resources->account_type == "NotMemberTypeArea" || $company_resources->account_type == "NotMemberTypeCalling") {
				//2018-06-19 bug修改，没有发布职位，不提示
				if (base_lib_BaseUtils::nullOrEmpty($_COOKIE["not_type_member"]) && !empty($job_list)) {
					$is_showDialog = false;
					foreach ($job_list as $val) {
						if ($val['check_state'] == 1) {
							$is_showDialog = true;
						}
					}
					if ($is_showDialog) {
						array_push($dialog_box, array ("type" => "not_type_member"));
					}
					
				}
			}
			else {
				if (base_lib_BaseUtils::nullOrEmpty($_COOKIE["not_member"]) && !$is_end_month) {
					array_push($dialog_box, array ("type" => "not_member"));
				}
			}
		}

		//1周内企业未读的人工推荐的简历
		$recomend_type = [2,3,4];
		$week_time = date("Y-m-d",strtotime("-1 week"));
		$service_recommend = new base_service_company_resume_recommend();
		$recommend_list = $service_recommend->getRecommendResumeV2ByTime($this->_userid,"recommend_id,company_id,recommend_type,is_read,job_id,station",$recomend_type,0,$week_time);

		$recommend_job_id = $recommend_list[0]['job_id'];
		$recommend_job_station = $recommend_list[0]['station'];
		$recommend_resume_dialog = base_lib_BaseUtils::getCookie("recommend_resume_dialog");

		if(($recommend_job_id || $recommend_job_station) && !$recommend_resume_dialog){
			$service_company_job_job = new base_service_company_job_job();
			if($recommend_job_id) {
				$recommend_job_info = $service_company_job_job->getJob($recommend_job_id, "job_id,station");
				array_push($dialog_box, array("type" => "recommend_resume_dialog", 'station' => $recommend_job_info['station'],'expires' => (int)strtotime(date('Y-m-d 00:00:00', strtotime('+14 day'))) * 1000));
			}else{
				array_push($dialog_box, array("type" => "recommend_resume_dialog", 'station' => $recommend_job_station,'expires' => (int)strtotime(date('Y-m-d 00:00:00', strtotime('+14 day'))) * 1000));
			}
		}
		return $dialog_box;
	}
	
	public function pageSetHotCompanySpreadCookie() {
		$cookies = array ('isHotCompanySpread' => 1);
		base_lib_BaseUtils::ssetcookie($cookies, 3600 * 24 * 365, '/', base_lib_Constant::COOKIE_DOMAIN);
	}
	
	/**
	 * @desc 投递职位自动过滤设置
	 */
	function pageAutomaticJob($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'], "int", 0);
		$is_show_success = base_lib_BaseUtils::getStr($path_data['is_show_success'], 'int', 0);
		$is_automatic = base_lib_BaseUtils::getStr($path_data['is_automatic'], "int", null); //0关  开
		
		if ($job_id == 0 || base_lib_BaseUtils::nullOrEmpty($is_automatic)) {
			echo json_encode(array ("error" => "传入参数错误"));
			
			return;
		}
		
		$service_job = new base_service_company_job_job();
		$current_job = $service_job->getJob($job_id, 'job_id,station,work_year_id,degree_id,sex,age_lower,age_upper,auto_filter,company_id');
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		if (empty($current_job) || !in_array($current_job['company_id'], $company_resources->all_accounts)) {
			return;
		}
		$log_message = '职位：' . $current_job['station'];
		//修改职位是否自动过滤
		$result = $service_job->modifyJobIsAutomatic($job_id, $is_automatic);
		if ($is_automatic == 0) {
			//关闭
			if (!$result) {
				echo json_encode(array ("error" => "关闭自动过滤失败"));
				
				return;
			}
			$log_message .= '，关闭自动过滤成功';
			echo json_encode(array ("success" => "关闭自动过滤成功"));
			
		}
		else {
			if ($is_show_success) {//进入成功页面
				$this->_aParams['set_result'] = $result;
				$this->_aParams['job_id'] = $job_id;
				$log_message .= '，开启自动过滤成功';
				echo $this->render("./setautomaticsuccess.html", $this->_aParams);
			}
			else {
				//返回json
				if (!$result) {
					echo json_encode(array ("error" => "开启自动过滤失败"));
					
					return;
				}
				$log_message .= '，开启自动过滤成功';
				echo json_encode(array ("success" => "开启自动过滤成功"));
				
			}
			
		}
		
		//---------添加操作日志--------
		$common_oper_type = new base_service_common_account_accountoperatetype();
		$service_oper_log = new base_service_company_companyaccountlog();
		$common_oper_src_type = new base_service_common_account_accountlogfrom();
		$insertItems = array (
			"company_id"   => $this->_userid,
			"source"       => $common_oper_src_type->website,
			"account_id"   => base_lib_BaseUtils::getCookie('accountid'),
			"operate_type" => $common_oper_type->job_smart_filter,
			"content"      => "职位过滤操作，详情：" . $log_message,
			"create_time"  => date("Y-m-d H:i:s", time())
		);
		$service_oper_log->addLogToMongo($insertItems);
		//-------------END------------
		die;
	}
	
	/**
	 * @desc 判断该职位是否设置了关键匹配字
	 */
	function pageCheckJobAutomatic($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'], "int", 0);
		if ($job_id == 0) {
			echo json_encode(array ("error" => "传入参数错误"));
			
			return;
		}
		
		$service_job = new base_service_company_job_job();
		$current_job = $service_job->getJob($job_id, 'job_id,work_year_id,degree_id,sex,age_lower,age_upper,auto_filter,allow_graduate,company_id');
		
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		if (empty($current_job) || !in_array($current_job['company_id'], $company_resources->all_accounts)) {
			return;
		}
		
		//判断职位是否设置了性别 年龄 工作年限  最低学历
		$show_set_page = false;
		if (!empty($current_job['work_year_id'])
			|| !empty($current_job['degree_id'])
			|| !empty($current_job['sex'])
			|| !empty($current_job['age_lower'])
			|| !empty($current_job['age_upper'])
			|| !empty($current_job['allow_graduate'])
		) {
			
			$show_set_page = true;//设置了任意一个关键匹配字  就可以自动匹配
		}
		
		echo json_encode(array ("success" => $show_set_page));
		exit;
	}
	
	/**
	 * @desc 未设置关键匹配字
	 */
	function pageSetJobAutomatic($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'], "int", 0);
		if ($job_id == 0) {
			echo json_encode(array ("error" => "传入参数错误"));
			
			return;
		}
		
		$this->_aParams['job_id'] = $job_id;
		
		return $this->render("./setAutomatic.html", $this->_aParams);
	}
	
	//   function pageTopOrder($inPath) {
	//   	$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
	// $validator = new base_lib_Validator();
	
	// $datas  = $validator->getArray($path_data['data'], "请选择置顶的关键词");
	// $job_id = $validator->getNotNull($path_data['job_id'], "请选择置顶的职位");
	//   	foreach ((array)$datas as $data) {
	//   		if (empty($data['keyword']) && !empty($data['dllday']))
	//   			$validator->addErr("请选择置顶的关键词");
	
	//   		$tops[] = ['keyword' => $data['keyword'], 'delay_day' => $data['dllday']];
	//   	}
	
	//   	$company_resources = base_service_company_resources_resources::getInstance($this->_userid);
	// list($status, $code, $params) = $company_resources->check($func="top", $params=['job_id'=>$job_id, "tops"=>$tops]);
	// var_dump($params);
	// die;
	// if (!$status)
	// 	$validator->addErr($params['msg']);
	
	//   	if ($validator->has_err) {
	//   		echo json_encode($json=['status'=>false, 'msg'=>$validator->err[0]]);
	//   		exit;
	//   	}
	//   }
	
	/*
     *下载的简历 最近15天以内 被下载次数最多的简历
     */
	private function __getResumeInfo($callings = null) {
		$calling_arr = array ();
		if (!base_lib_BaseUtils::nullOrEmpty($callings)) {
			$calling_arr = explode(",", $callings);
		}
		//下载的简历 最近15天以内 被下载次数最多的简历
		//$download_service = new base_service_company_resume_download();
		//$resume_ids = $download_service->getResumeIdsByCount(15,5,$calling_arr[0]);
		$resume_id_arr = array ();//获取的本行业简历ID
		$solr_count = 5;//需要用solr查找的数据条数
		$solrresult = array ();
		if ($solr_count > 0) {
			$postvar = array ();
			$postvar['pageindex'] = 1;
			$postvar['pagesize'] = $solr_count;
			$postvar['hddIsfirstPost'] = 1;
			$postvar['callings'] = $calling_arr;
			$postvar['wordtype'] = "word";
			$postvar['workyear_min'] = 3;
			$postvar['complete_percent'] = 70;//简历完善度大于==70
			$postvar['exp_areas'] = "0300";//默认重庆
			$postvar['degree_ids'] = array ("05", "06", "07", "08");//学历大专以上
			$postvar['key_word_type'] = "word";
			
			$postvar['hddSortType'] = 1;//按照刷新时间排序
			$service_solr = new base_service_solr_solr();
			$solrresult = $service_solr->resumeSearch($postvar);
		}
		$solr_resume_ids = array ();
		if (!base_lib_BaseUtils::nullOrEmpty($solrresult) && isset($solrresult['resumeIDs'])) {
			$solr_resume_ids = $solrresult['resumeIDs'];
		}
		$result_resume_ids = $solr_resume_ids; //合并2种简历数组
		if (count($result_resume_ids) > 0) {
			$resume_ids = implode(",", $result_resume_ids);
			$service_resume = new base_service_person_resume_resume();
			$resumes = $service_resume->getResumeListByIDs($resume_ids, "resume_id,resume_name,relate_resume_id,person_id,degree_id, refresh_time,station,current_station,current_station_start_time,current_station_end_time,is_audit,visit_num");
			$resume_items = $resumes->items;
			if (count($resume_items) > 0) {
				$service_person = new base_service_person_person();
				$service_resume_work = new base_service_person_resume_work();
				$service_resume_remark = new base_service_company_resume_resumeremark();
				
				$person_ids = $this->_buildIDs($resume_items, 'person_id');
				$person_data = $service_person->GetPersonListByIDs($person_ids, 'person_id,open_mode,user_name,sex,birthday2,cur_area_id,start_work,photo,small_photo,name_open,person_class');
				$person_list = $this->_buildArray($person_data->items, "person_id");
				for ($i = 0; $i < count($resume_items); $i++) {
					$resume_info = $resume_items[ $i ];
					$resume_id = $resume_info['resume_id'];
					$person_id = $resume_info['person_id'];
					$person_info = $person_list["$person_id"];
					$relate_resume_id = empty($resume_info['relate_resume_id']) ? 0 : $resume_info['relate_resume_id'];
					$isshowinfo = $this->__checkResumeShowAll($resume_id, $relate_resume_id);
					$resume_items[ $i ]['open_mode'] = $person_info['open_mode'];
					//姓名
					if ($person_info['name_open'] == 1 && $isshowinfo) {
						$resume_items[ $i ]['user_name'] = $person_info['user_name'];
					}
					else {
						$sex_name = $person_info['sex'] == 1 ? '先生' : '女士';
						$resume_items[ $i ]['user_name'] = mb_substr($person_info['user_name'], 0, 1, 'utf-8') . $sex_name;
					}
					
					//$resume_items[$i]['remark'] = $resume_remark_info['remark'];
					
					//头像性别、年龄、学历、当前所在地
					
					if (empty($person_info['photo']) || !$isshowinfo) {
						$person_info['photo'] = base_lib_Constant::STYLE_URL . '/img/c/new_index/firmicon17.png';;
						$person_info['small_photo'] = base_lib_Constant::STYLE_URL . '/img/c/new_index/firmicon17.png';;
					}
					else {
						$person_info['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['photo'];
						$person_info['small_photo'] = $person_info['photo'];
					}
					$resume_items[ $i ]['photo'] = $person_info['photo'];
					$resume_items[ $i ]['small_photo'] = $person_info['small_photo'];
					$resume_items[ $i ]['degree'] = $resume_info['degree_id'];
					//工作年限
					$basic_start_work_year = base_lib_TimeUtil::date_diff_month($person_info['start_work']);
					$workY = floor($basic_start_work_year / 12);
					$workM = intval($basic_start_work_year % 12);
					
					if ($workY <= 0 && $workM <= 6 && $workM >= -6) {
						$basic_start_work_year = '应届毕业生';
					}
					else if ($workY == 0 && $workM > 6) {
						$basic_start_work_year = $workM . '个月';
					}
					else if ($basic_start_work_year < -6) {
						$basic_start_work_year = '目前在读';
					}
					else {
						$basic_start_work_year = $workY . '年';
					}
					$resume_items[ $i ]['work_year'] = $basic_start_work_year;
					//最近工作经验
					if ($resume_info['current_station'] == '') {
						$resume_items[ $i ]['last_work'] = $resume_info['station'];
					}
					else {
						$resume_items[ $i ]['last_work'] = $resume_info['current_station'];
					}
				}
				$data['list'] = $resume_items;
				$this->_aParams['resumedata_arr'] = $data['list'];
			}
		}
	}
	
	
	/**
	 * 会员详情页面
	 * @param  mixed $inPath url参数
	 * @return html          memberdetail.html
	 */
	function pageMemberDetail($inPath) {
		$xml = SXML::load('../config/config.xml');
		if (!is_null($xml)) {
			$huibo_phone = $xml->HuiboPhone400;
		}
		$this->_aParams["huibo_phone"] = $huibo_phone;
		return $this->render('member_detail.html', $this->_aParams);
	}
	
	/**
	 * 设置急聘职位
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	function pageSetUrgentjob($inPath) {
		//判断单位是否登录
		$service_company = new base_service_company_company();
		$current_company = $service_company->getCompany($this->_userid, 1, 'com_level');
		if (empty($current_company)) {
			echo json_encode(array ('error' => '请登录'));
			
			return;
		}
		
		//判断单位是否开通服务
		$service_comservice = new base_service_company_service_comservice();
		$comservice = $service_comservice->getComService($this->_userid, 'com_level,service_id,urgent_point');
		if (empty($comservice)) {
			echo json_encode(array ('error' => '单位未开通服务'));
			
			return;
		}
		
		//判断单位会员服务剩余急聘数
		if (base_lib_BaseUtils::nullOrEmpty($comservice['urgent_point']) || $comservice['urgent_point'] == 0) {
			echo json_encode(array ('fail' => '您的推广点剩余：0，无法将此职位设为“急聘”，有疑问，请联系您的招聘顾问！'));
			
			return;
		}
		
		$service_job_urgent = new base_service_company_job_joburgent();
		$total_urgent_count = $service_job_urgent->getTotalUrgentCount($comservice['service_id'], $this->_userid);
		if ($total_urgent_count >= $comservice['urgent_point']) {
			echo json_encode(array ('fail' => '您的推广点剩余：0，无法将此职位设为“急聘”，有疑问，请联系您的招聘顾问！'));
			
			return;
		}
		
		//获取职位
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($path_data['hidJobID'], 'int', 0);
		if ($job_id == 0) {
			echo json_encode(array ('error' => '职位获取失败'));
			
			return;
		}
		
		$service_job = new base_service_company_job_job();
		$current_job = $service_job->getJob($job_id, 'job_id,company_id,check_state,status,end_time,urgent_end_time');
		
		//校验职位
		if (empty($current_job)) {
			echo json_encode(array ('error' => '职位获取失败'));
			
			return;
		}
		
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		if (!in_array($current_job['company_id'], $company_resources->all_accounts)) {
			echo json_encode(array ('error' => '职位获取失败'));
			
			return;
		}
		
		if ($current_job['check_state'] == 2) {
			echo json_encode(array ('fail' => '该职位未通过审核，设置急聘失败'));
			
			return;
		}
		
		if ($current_job['check_state'] == 4) {
			$this->_aParams['message'] = '该职位正在审核中，设置急聘失败';
			
			return $this->render('showerror.html', $this->_aParams);
		}
		
		$service_jobstatus = new base_service_common_jobstatus();
		if ($current_job['status'] == $service_jobstatus->stop_use) {
			echo json_encode(array ('fail' => '该职位已停用，设置急聘失败'));
			
			return;
		}
		
		if ((strtotime(date('Y-m-d', time())) - strtotime(date('Y-m-d', strtotime($current_job['end_time'])))) > 0) {
			echo json_encode(array ('fail' => '该职位已过期，设置急聘失败'));
			
			return;
		}
		
		//获取急聘设置天数
		$days = base_lib_BaseUtils::getStr($path_data['radUrgentDay'], 'int', 0);
		if ($days != 7 && $days != 14 && $days != 21) {
			echo json_encode(array ('error' => '请选择急聘天数'));
			
			return;
		}
		
		if (empty($current_job['urgent_end_time'])) {
			$current_job['urgent_end_time'] = time();
		}
		$the_time = strtotime(date("Y-m-d", $current_job['urgent_end_time'])) > strtotime(date("Y-m-d")) ? $current_job['urgent_end_time'] : time();
		$end_time_stamp = strtotime(date('Y-m-d', strtotime("+{$days} day", $the_time)));
		
		//设置急聘
		$urgent['service_id'] = $comservice['service_id'];
		$urgent['company_id'] = $this->_userid;
		$urgent['job_id'] = $current_job['job_id'];
		$urgent['create_time'] = date('Y-m-d H:i:s');
		$urgent['point'] = $days / 7;
		$new_job_end_time = '';
		$member_type = $company_resources->isMember;
		$add_result = $service_job_urgent->addJobUrgent($urgent, $current_job, $end_time_stamp, $new_job_end_time,$member_type);
		if (!base_lib_BaseUtils::nullOrEmpty($new_job_end_time)) {
			$new_job_end_time = date('m-d', strtotime($new_job_end_time));
		}
		
		if ($add_result) {
			echo json_encode(array (
				                 'success'          => 'success',
				                 'job_id'           => $urgent['job_id'],
				                 'point'            => ($days / 7),
				                 'urgent_end_time'  => date('Y年m月d日', $end_time_stamp) . '截止',
				                 'new_job_end_time' => $new_job_end_time
			                 )
			);
		}
		else {
			echo json_encode(array ('fail' => '设置急聘失败'));
		}
		
		return;
	}
	
	
	/**
	 * 批量刷新
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	private function pageRefreshAllBak($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		
		$job = new base_service_company_job_job();
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		$job_list = $job->getJobList($company_resources->all_accounts, null, 2, 'job_id,station');
		
		if (empty($job_list) || count($job_list) < 1) {
			echo json_encode(array ('error' => '没有可刷新的职位'));
			
			return;
		}
		
		$job_ids = $this->getPropertys($job_list, "job_id");
		
		$service_refresh = new base_service_company_refresh();
		$refresh_result = $service_refresh->refreshCompany($this->_userid, $job_ids);
		
		if (isset($refresh_result[0])) {
			$refresh_status = $refresh_result[0];
			if ($refresh_status == 1 || $refresh_status == 2 || $refresh_status == 9) {
				echo json_encode(array ('error' => "刷新全部职位失败!"));
				
				return;
			}
			
			if ($refresh_status == 4) {
				if (!empty($refresh_result['day'])) {
					echo json_encode(array ('fail' => true, 'failitem' => "离上次刷新不足{$refresh_result['day']}天，请稍候再试！"));
					
					return;
				}
				else {
					echo json_encode(array ('fail' => true, 'failitem' => "离上次刷新不足{$refresh_result['min']}分钟，请稍候再试！"));
					
					return;
				}
			}
			else if ($refresh_status == 5) {
				echo json_encode(array ('fail' => true, 'failitem' => "今日的刷新次数（{$refresh_result['refreshtoday']}次）已用完，请明日再试！"));
				
				return;
			}
		}
		
		$refresh_time = $service_refresh->getRefreshTime($this->_userid);
		
		$r_time = 0;
		if ($refresh_time['last_refresh_time'] > 0) {
			$r_time = base_lib_TimeUtil::to_friend_time(date("Y-m-d H:i:s", $refresh_time['last_refresh_time']));
		}
		echo json_encode(array ('success' => true, 'refreshtime' => $r_time));
	}
	
	public function pageRefreshAllCq($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$count = base_lib_BaseUtils::getStr($path_data['count'], 'string', null);
		$is_batch = base_lib_BaseUtils::getStr($path_data['is_batch'], 'string', null);
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'], 'string', null);
		$data = array ();
		$data['is_batch'] = $is_batch;
		$data['count'] = $count;
		if (empty($count)) {
			echo "参数错误，请刷新后重试";
			
			return;
		}
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$service_account = new base_service_company_account();
		$account = $service_account->getAccount($account_id, 'resource_type');
		$data['resource_type'] = $account['resource_type'];
		$service_serviceConsumeLog = new base_service_company_service_serviceConsumeLog();
		$refresh_times = $service_serviceConsumeLog->getCountByRelevance(3, $job_id, 'count(id) refresh_times')[0]['refresh_times'];
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $account_id);
		$company_resource_info = $company_resources->getCompanyServiceSource();
		$data['day_refresh'] = false;
		if ($refresh_times >= $company_resource_info['cq_refresh_times_day']) {
			$data['day_refresh'] = true;
			$data['day_refresh_times'] = $company_resource_info['cq_refresh_times_day'];
			
			return $this->render('cqdialog.html', $data);
		}
		$service_servicePricing = new base_service_company_service_servicePricing();
		$selling = $service_servicePricing->GetFunParallelismSelling('point_job_refresh');
		$data['selling'] = $selling;//汇率
		$needCash = $count * $selling;
		$spread = (int)$company_resource_info['spread_overage'];
		$yuE = (int)$company_resource_info['account_overage'];
		$cash = (int)($company_resource_info['spread_overage'] + $company_resource_info['account_overage']);
		
		$point = $company_resource_info['cq_release_point_job_refresh'] < 0 || empty($company_resource_info['cq_release_point_job_refresh']) ? 0 : (int)$company_resource_info['cq_release_point_job_refresh'];
//
//        $spread = 50;
//        $yuE = 10;
//        $point = 1;
//        $cash  = $spread+$yuE;
		if ($point < $count) {
			if (($count - $point) * $selling > $cash && $point > 0 || ($point == 0 && $needCash > $cash)) {
				//echo json_encode(array ('status' => false,'code'=>302, 'msg' => "刷新点和推广金不足"));
				return $this->render('cqshowpay.html', $data);
			}
		}
		if ($point >= $count) {
			$_point = $count;
			$_spread = 0;
			$_yuE = 0;
			$data['point'] = $_point;
			$data['spread'] = $_spread;
			$data['yue'] = $_yuE;
			
			return $this->render('cqdialog.html', $data);
		}
		
		if ($point > 0) {
			$count = $count - $point; //先扣剩余的刷新点
			$_point = $point;
		}
		$needCash = $count * $selling;
		if ($spread > 0) {
			$_spread = $count * $selling;
			$count = $count - $spread / $selling;
		}
		
		if ($count > 0) {
			$_spread = $spread;
		}
		else {
			$_yuE = 0;
		}
		
		if ($yuE > 0 && $count > 0) {
			$_yuE = $needCash - $spread;
		}
		$data['point'] = $_point;
		$data['spread'] = $_spread;
		$data['yue'] = $_yuE;
		
		return $this->render('cqdialog.html', $data);
//        echo json_encode(array ('status' => true,'code'=>200,'point'=>$_point,'spread'=>$_spread,'yue'=>$_yuE));
//        return;
		
	}
	
	function pageRefreshAllCqDo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_ids = base_lib_BaseUtils::getStr($path_data['jobids'], 'string', null);
		if (!$this->canDo("refresh_job")) {
			echo json_encode(array ('status' => false, 'msg' => "无权限访问，没有开通相应权限"));;
			
			return;
		}
		
		if (empty($job_ids)) {
			echo json_encode(array ('status' => false, 'msg' => "请选择需要刷新的职位"));
			
			return;
		}
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		$job_ids = explode(',', $job_ids);
		//判断刷新点
		$company_resource_info = $company_resources->getCompanyServiceSource(["account_resource"]);
		
		if (!$company_resource_info['isCqNewService']) {
			echo json_encode(array ('status' => false, 'msg' => "服务信息错误，请联系客服"));
			
			return;
		}
		
		$service_servicePricing = new base_service_company_service_servicePricing();
		$selling = $service_servicePricing->GetFunParallelismSelling('point_job_refresh');
		$cash = (int)($company_resource_info['spread_overage'] + $company_resource_info['account_overage']); //剩余的总金额
		$count = count($job_ids);
		$needCash = $count * $selling;
		$point = $company_resource_info['cq_release_point_job_refresh'] < 0 || empty($company_resource_info['cq_release_point_job_refresh']) ? 0 : (int)$company_resource_info['cq_release_point_job_refresh'];
		if ($point < $count) {
			if (($count - $point) * $selling > $cash && $point > 0 || ($point == 0 && $needCash > $cash)) {
				if ($company_resource_info['resource_type'] == 2) {
					$msg = '您的推广金/刷新点不足，请联系主账号为您分配更多资源';
				}
				else {
					$msg = '刷新点和推广金不足';
				}
				echo json_encode(array ('status' => false, 'code' => 302, 'msg' => $msg));
				
				return;
			}
		}
		
		//查询出含有超过2天未处理简历的职位
		$apply_service = new base_service_company_resume_apply();
		$job_service = new base_service_company_job_job();
		
		$apply_jobs = $apply_service->getNotReplyJobIds($company_resources->all_accounts, 2);
		$_apply_jobs = base_lib_BaseUtils::array_key_assoc($apply_jobs, 'job_id');
		$apply_job_ids = base_lib_BaseUtils::getProperty($apply_jobs, 'job_id');
		
		$jobs = $job_service->getJobs($job_ids, 'job_id,station,account_id');
		$service_company_account = new base_service_company_account();
		$account = $service_company_account->getAccountByAccount_ids(base_lib_BaseUtils::getProperty($jobs, 'account_id'), 'account_id,user_name')->items;
		$_account = base_lib_BaseUtils::array_key_assoc($account, 'account_id');
		$_jobs = base_lib_BaseUtils::array_key_assoc($jobs, 'job_id');
		$point_count = 0;//刷新点
		$spread_count = 0;//推广金
		$rest_count = 0;//余额
		$rest_spread_count = 0;//推广金
		$job_count = 0;//刷新的职位数
		$not_refresh_jobs = array ();
		
		$service_serviceConsumeLog = new base_service_company_service_serviceConsumeLog();
		$refresh_times = $service_serviceConsumeLog->getCountByRelevance(3, $job_ids, 'count(id) refresh_times,relevance_id');
		$_refresh_times = base_lib_BaseUtils::array_key_assoc($refresh_times, 'relevance_id');
		$day_refresh_jobs = array ();
		for ($i = 0; $i < count($job_ids); $i++) {
			if ($_refresh_times[ $job_ids[ $i ] ]['refresh_times'] >= $company_resource_info['cq_refresh_times_day']) { //判断每日刷新次数
				array_push($day_refresh_jobs, $_jobs[ $job_ids[ $i ] ]['station']);
				continue;
			}
			if (!in_array($job_ids[ $i ], $apply_job_ids) || (in_array($job_ids[ $i ], $apply_job_ids) && $_apply_jobs[ $job_ids[ $i ] ]['no_reply_num'] == 0)) {
				$res = $company_resources->consume('cq_setmeal_consume', ['consume_type' => 'refresh', 'batch' => true, 'job_id' => $job_ids[ $i ]]);
				if ($res['code'] != 200) {
					echo json_encode(array ('status' => false, 'msg' => $res['msg'], 'code' => $res['code']));
					
					return;
				}
				else {
					$job_count += 1;
					switch ($res['pay_type']) {
						case 1:
							$point_count += 1;
							break;
						case 2:
							$spread_count += 1;
							break;
						case 3:
							$rest_count += 1;
							break;
						case 4:
							$rest_spread_count += 1;
							break;
					}
				}
			}
			else {
				if ($_apply_jobs[ $job_ids[ $i ] ]['no_reply_num'] > 0) {
					array_push($not_refresh_jobs, ['job_id' => $job_ids[ $i ], 'station' => $_jobs[ $job_ids[ $i ] ]['station'], 'no_reply_num' => $_apply_jobs[ $job_ids[ $i ] ]['no_reply_num'], 'account_username' => $_account[ $_jobs[ $job_ids[ $i ] ]['account_id'] ]['user_name']]);
				}
			}
		}
		
		$msg = '';
		if ($point_count > 0 && $spread_count == 0 && $rest_count == 0 && $rest_spread_count == 0) {
			$msg = "已批量刷新{$job_count}个职位，共消耗{$point_count}个刷新点";
		}
		else {
			$neeCash = ($job_count - $point_count) * $selling;
			if ($spread_count > 0 && $rest_count == 0) {
				$spread_cash = $selling * $spread_count;
				$msg = "已批量刷新{$job_count}个职位，共消耗{$point_count}个刷新点，{$spread_cash}元推广金";
			}
			if ($spread_count > 0 && $rest_count > 0) {
				$spread_cash = $selling * $spread_count;
				$rest_cash = $selling * $rest_count;
				$msg = "已批量刷新{$job_count}个职位，共消耗{$point_count}个刷新点，{$spread_cash}元推广金,{$rest_cash}元余额";
			}
			if ($spread_count == 0 && $rest_count > 0) {
				$spread_cash = $selling * $spread_count;
				$rest_cash = $selling * $rest_count;
				$msg = "已批量刷新{$job_count}个职位，共消耗{$point_count}个刷新点，{$rest_cash}元余额";
			}
			if ($rest_spread_count > 0) {
				$spread_cash = (int)$company_resource_info['spread_overage'];
				$rest_cash = $neeCash - $spread_cash;
				$msg = "已批量刷新{$job_count}个职位，共消耗{$point_count}个刷新点，{$spread_cash}元推广金，{$rest_cash}元余额";
			}
		}
		if ($day_refresh_jobs) {
			if ($msg) {
				$msg = $msg . "<br>以下" . count($day_refresh_jobs) . '个职位由于“当天已刷新' . $company_resource_info['cq_refresh_times_day'] . '次”，无法刷新：' . implode("、", $day_refresh_jobs);
			}
			else {
				$msg = "<br>以下" . count($day_refresh_jobs) . '个职位由于“当天已刷新' . $company_resource_info['cq_refresh_times_day'] . '次”，无法刷新：' . implode("、", $day_refresh_jobs);
			}
		}
		if ($not_refresh_jobs) {
			if ($msg) {
				$msg = $msg . "<br>以下" . count($not_refresh_jobs) . '个职位没有批量刷新。原因：48小时内未查看投递简历';
			}
			else {
				$msg = "<br>以下" . count($not_refresh_jobs) . '个职位没有批量刷新。原因：48小时内未查看投递简历';
			}
		}

        //------@add 2019-11-27 职位刷新：更新企业_stamp_remark------
        $service_company = new base_service_company_company();
        $service_company->update_stamp_remark($this->_userid);

		echo json_encode(array (
			                 'status'          => true,
			                 'msg'             => $msg,
			                 "hasDayNoRefresh" => empty($day_refresh_jobs) ? false : count($day_refresh_jobs),
			                 "notRefreshJobs"  => $not_refresh_jobs,
			                 'hasNoRefresh'    => empty($not_refresh_jobs) ? false : count($not_refresh_jobs)
		                 ));
		
		return;
	}
	
	function pageRefreshCqDo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'], 'string', null);
		if (!$this->canDo("refresh_job")) {
			echo json_encode(array ('status' => false, 'msg' => "无权限访问，没有开通相应权限"));;
			
			return;
		}
		if (empty($job_id)) {
			echo json_encode(array ('status' => false, 'msg' => "请选择需要刷新的职位"));
			
			return;
		}
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		$company_resource_info = $company_resources->getCompanyServiceSource(['account_resource']);
		
		$service_serviceConsumeLog = new base_service_company_service_serviceConsumeLog();
		$refresh_times = $service_serviceConsumeLog->getCountByRelevance(3, $job_id, 'count(id) refresh_times')[0]['refresh_times'];
		
		if (!$company_resource_info['isCqNewService']) {
			echo json_encode(array ('status' => false, 'msg' => "服务信息错误，请联系客服"));
			
			return;
		}
		if ($refresh_times >= $company_resource_info['cq_refresh_times_day']) {
			echo json_encode(array ('status' => false, 'msg' => "今天已经刷新{$company_resource_info['cq_refresh_times_day']}次了，明天再来吧"));
			
			return;
		}
		$params = array (
			'company_id'   => $this->_userid,
			'job_id'       => $job_id,
			'consume_type' => 'refresh',
			'batch'        => false
		);
		$consume_result = $company_resources->consume("cq_setmeal_consume", $params);
		
		if ($consume_result['result'] == false) {
			echo json_encode(array ('status' => false, 'msg' => $consume_result['msg'], 'data' => $consume_result));
			
			return;
		}

		//------@add 2019-11-27 职位刷新：更新企业_stamp_remark------
		$service_company = new base_service_company_company();
		$service_company->update_stamp_remark($this->_userid);

		echo json_encode(array ('status' => true, 'msg' => "刷新成功"));
		
		return;
		
		
	}
	
	
	/**
	 * 批量刷新
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	function pageRefreshAll($inPath) {
		if (!$this->canDo("refresh_job")) {
			echo json_encode(['status' => false, 'msg' => "无权限访问，没有开通相应权限", 'items' => array ()]);
			
			return;
		}
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		list($status, $code, $params) = $company_resources->check($func = "refresh");
		if (!$status && $code == base_service_company_resources_code::RERESH_NOT_MEMBER_FAIL) {
			echo json_encode(['status' => $status, 'msg' => $params['msg'], 'not_member' => true]);
			
			return;
		}
		
		if (!$status && $code != base_service_company_resources_code::REFESH_FAIL_NO_CASH) {
			echo json_encode(['status' => $status, 'msg' => $params['msg']]);
			
			return;
		}
		
		$items['orders'][] = ["title" => "订单：职位刷新", 'day' => '1次', "consume" => $params['consume']];
		$items['params'] = $params;
		
		echo json_encode(['status' => true, 'code' => $code, 'items' => $items]);
	}
	
	
	/**
	 * 批量刷新
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	function pageRefreshAllDo($inPath) {
		if (!$this->canDo("refresh_job")) {
			echo json_encode(array ('fail' => true, 'failitem' => "无权限访问，没有开通相应权限", 'code' => ""));;
			
			return;
		}
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$resource_service = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		
		
		$resource_consume = $resource_service->consume('refresh', array ("fresh_version" => "new_version"));
		list($status, $code, $params) = $resource_consume;
		if ($status) {
			//---------添加操作日志--------
			$common_oper_type = new base_service_common_account_accountoperatetype();
			$service_oper_log = new base_service_company_companyaccountlog();
			$common_oper_src_type = new base_service_common_account_accountlogfrom();
			$insertItems = array (
				"company_id"   => $this->_userid,
				"source"       => $common_oper_src_type->website,
				"account_id"   => base_lib_BaseUtils::getCookie('accountid'),
				"operate_type" => $common_oper_type->job_refush,
				"content"      => "刷新了所有职位。",
				"create_time"  => date("Y-m-d H:i:s", time())
			);
			$service_oper_log->addLogToMongo($insertItems);
			//-------------END------------
			list($not_refresh_job_list, $not_refresh_num, $job_num) = $this->_getNotFreshJobs();
			//保存动作类型
			//保存登录动作
			//动作类型
			$service_actiontype = new base_service_common_actiontype();
			//用户类型
			$service_actionusertype = new base_service_common_actionusertype();
			//渠道
			$service_actionsource = new base_service_common_actionsource();
			base_lib_BaseUtils::saveAction($service_actiontype->refreashjob, $service_actionsource->website, $this->_userid, $service_actionusertype->company);

            //------@add 2019-11-27 职位刷新：更新企业_stamp_remark------
            $service_company = new base_service_company_company();
            $service_company->update_stamp_remark($this->_userid);

			echo json_encode(['success' => true, 'refreshtime' => base_lib_TimeUtil::to_friend_time(date('Y-m-d H:i:s')), 'code' => $code, 'not_refresh_job_list' => $not_refresh_job_list, "job_num" => $job_num, "not_refresh_num" => $not_refresh_num]);
		}
		else {
			echo json_encode(array ('fail' => true, 'failitem' => $params['msg'], 'code' => $code));
		}
	}
	
	//没有批量刷新的，弹框内的单个刷新职位
	function pageSingleRefrshDo($inPath) {
		if (!$this->canDo("refresh_job")) {
			echo json_encode(array ('status' => false, 'msg' => "无权限访问，没有开通相应权限！"));
			
			return;
		}
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$resource_service = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$refresh_job_id = base_lib_BaseUtils::getStr($path_data['refresh_job_id'], 'int', 0);
		if (!$refresh_job_id) {
			echo json_encode(array ('status' => false, 'msg' => "刷新失败！"));
			
			return;
		}
		
		$resource_consume = $resource_service->consume('refresh', ["refresh_job_id" => $refresh_job_id]);
		list($status, $code, $params) = $resource_consume;


        //------@add 2019-11-27 职位刷新：更新企业_stamp_remark------
        $service_company = new base_service_company_company();
        $service_company->update_stamp_remark($this->_userid);

		echo json_encode(array ('status' => $status, 'msg' => $params['msg']));
		
		return;
	}
	
	//ajax请求未刷新职位
	public function pageGetNotFreshJobs() {
		list($not_refresh_job_list, $not_refresh_num, $job_num) = $this->_getNotFreshJobs();
		echo json_encode(['not_refresh_job_list' => $not_refresh_job_list, "job_num" => $job_num, "not_refresh_num" => $not_refresh_num]);
		
		return;
	}
	
	//没有批量刷新的职位
	private function _getNotFreshJobs() {
		//查询出含有超过3天未处理简历的职位
		$apply_service = new base_service_company_resume_apply();
		$service_company_job = new base_service_company_job_job();
		$job_status = new base_service_common_jobstatus();
		$service_company_account = new base_service_company_account();
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		
		$jobs = $apply_service->getNotReplyJobIds($company_resources->all_accounts);
		
		$not_refresh_jobs = [];
		foreach ($jobs as $job) {
//			if ($job['has_reply_num'] > 0)
//				continue;
			if ($job['no_reply_num'] > 0) {
				array_push($not_refresh_jobs, $job);
			}
		}
		
		//获取最后刷新时间
		$service_refresh = new base_service_company_refresh();
		$last_refresh_time = $service_refresh->getRefreshTime($company_resources->main_company_id);
		
		$not_refresh_job_ids = base_lib_BaseUtils::getProperty($not_refresh_jobs, "job_id");
		$not_refresh_job_list = $service_company_job->getJobs($not_refresh_job_ids, 'job_id,job_flag,station,issue_time,check_state,end_time,status,order_no,urgent_end_time,jobsort_ids,jobsort,re_apply_type,account_id,is_effect,refresh_time');
		//剔除掉状态不符合的职位
		foreach ($not_refresh_job_list as $k => $job) {
			if ($job['status'] != $job_status->use || strtotime(date("Y-m-d 00:00:00")) > strtotime($job['end_time']) || $job['check_state'] > 1 || $job['is_effect'] != 1) {
				unset ($not_refresh_job_list[ $k ]);
			}
		}
		$job_num = $service_company_job->getJobCount($company_resources->all_accounts, 2);
		$job_num = $job_num - count($not_refresh_job_list);
		//剔除掉已刷新的职位
		foreach ($not_refresh_job_list as $k => $job) {
			if (strtotime($job['refresh_time']) == $last_refresh_time['last_refresh_time']) {
				unset ($not_refresh_job_list[ $k ]);
			}
		}
		$a_list = $service_company_account->getAccountByAccount_ids(base_lib_BaseUtils::getProperty($not_refresh_job_list, 'account_id'), 'account_id,user_id,user_name,head_photo,station');
		$a_list = base_lib_BaseUtils::array_key_assoc($a_list->items, 'account_id');
		
		$statistics = $apply_service->getApplyStatisticsByJobIdsVerson2($company_resources->all_accounts, $not_refresh_job_ids)->items;
		$statistics = base_lib_BaseUtils::array_key_assoc($statistics, "job_id");
		//$not_refresh_jobs = base_lib_BaseUtils::array_key_assoc($not_refresh_jobs, "job_id");
		foreach ($not_refresh_job_list as $key => $not_refresh_job) {
			//未处理数量
			$not_refresh_job_list[ $key ]['no_reply_num'] = $statistics[ $not_refresh_job['job_id'] ]['no_reply_num'] ? $statistics[ $not_refresh_job['job_id'] ]['no_reply_num'] : 0;
			//$not_refresh_job_list[$key]['no_reply_num'] = $not_refresh_jobs[$not_refresh_job['job_id']]['no_reply_num'] ? $not_refresh_jobs[$not_refresh_job['job_id']]['no_reply_num'] : 0;
			//职位发布人
			$not_refresh_job_list[ $key ]['account_user_name'] = $a_list[ $not_refresh_job['account_id'] ]['user_name'] ? $a_list[ $not_refresh_job['account_id'] ]['user_name'] : $a_list[ $not_refresh_job['account_id'] ]['user_id'];
		}
		$not_refresh_num = count($not_refresh_job_list);
		
		return [$not_refresh_job_list, $not_refresh_num, $job_num];
	}
	
	
	/**
	 * 停用选中的职位
	 * Enter description here ...
	 * @param $inPath
	 */
	function pageStopChooseJob($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_ids = base_lib_BaseUtils::getStr($path_data['jobids'], 'array', null);
		$job_ids = base_lib_BaseUtils::getIntArrayOrString($job_ids);
		$job = new base_service_company_job_job();
		$job_status = new base_service_common_jobstatus();
		$job_list_temp = $job->getJobs($job_ids, 'job_id,station,company_id');
		$job_list = array ();
		foreach ($job_list_temp as $ite => $val) {
			if ($val['company_id'] == $this->_userid) {
				array_push($job_list, $val);
			}
		}
		if (empty($job_list) || count($job_list) < 1) {
			echo json_encode(array ('error' => '没有可停用的职位'));
			
			return;
		}
		$failItem = '';
		$failCount = 0;
		$failMsg = '';
		$jobids = array ();
		foreach ($job_list as $item => $v) {
			$result = $job->setJobStatus($this->_userid, $v['job_id'], $job_status->stop_use);
			if ($result === false) {
				if ($failCount < 5) {
					$failItem .= "<br />{$v['station']}";
				}
				$failCount++;
				continue;
			}
			array_push($jobids, $v['job_id']);
		}
		if (!base_lib_BaseUtils::nullOrEmpty($failItem)) {
			if ($failCount > 5) {
				$failItem = $failItem . '...等' . $failCount . '个职位停止招聘失败，请稍后重试！';
			}
			else {
				$failItem = $failItem . '，停止招聘失败，请稍后重试！';
			}
			echo json_encode(array ('fail' => true, 'failitem' => $failItem, 'job_ids' => $jobids));
			
			return;
		}
		echo json_encode(array ('success' => true, 'job_ids' => $jobids));
	}
	
	/**
	 * 判断企业会员和过期时间
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	function pageCheckComLevelEndtime($inPath) {
		$company_info = new base_service_company_company();
		$company = $company_info->getCompany($this->_userid, '1', 'end_time,com_level');
		if (empty($company)) {
			echo json_encode(array ('showservicelink' => true, 'error' => '您未开通会员，职位无法发布到huibo.com供求职者查看，请开通会员后重试！'));
			
			return;
		}
		if (empty($company['end_time']) || strtotime(date('Y-m-d', strtotime($company['end_time']))) < strtotime(date('Y-m-d', time())) || empty($company['com_level'])) {
			echo json_encode(array ('showservicelink' => true, 'error' => '您未开通会员，职位无法发布到huibo.com供求职者查看，请开通会员后重试！'));
			
			return;
		}
		echo json_encode(array ('success' => true));
		
		return;
	}
	
	// /**
	//  *@desc 重新发布职位
	//  */
	// public function pageRePubJob($inPath) {
	// 	$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
	// 	$job_id = base_lib_BaseUtils::getStr($path_data['job_id'],'int',0);
	// 	if ($job_id == 0) {
	// 		echo json_encode(array('error'=>'重新发布职位失败'));
	// 		return ;
	// 	}
	// 	$service_job = new base_service_company_job_job();
	// 	$job = $service_job->getJob($job_id,'job_id,issue_time,jobsort_ids,station',$this->_userid);
	// 	if(empty($job)){
	// 		echo json_encode(array('error'=>'重新发布职位失败'));
	// 		return ;
	// 	}
	// 	$company_info = new base_service_company_company();
	// 	$company = $company_info->getCompany($this->_userid,'1','end_time,com_level');
	// 	if(empty($company)){
	// 		echo json_encode(array('showservicelink'=>true,'error'=>'您未开通会员，职位无法发布到huibo.com供求职者查看，请开通会员后重试！'));
	// 		return ;
	// 	}
	// 	if (empty($company['end_time']) || strtotime(date('Y-m-d',strtotime($company['end_time'])))<strtotime(date('Y-m-d',time())) || empty($company['com_level']))
	// 	{
	// 		echo json_encode(array('showservicelink'=>true,'error'=>'您未开通会员，职位无法发布到huibo.com供求职者查看，请开通会员后重试！'));
	// 		return ;
	// 	}
	// 	$issetendtime = base_lib_BaseUtils::getStr($path_data['hidSetEndTime'],'int',0);
//	 	$validator = new base_lib_Validator();
	// 	$end_time = null;
	// 	$valid_days = null;
	// 	if ($issetendtime == 1) {
	// 		//有效期
	// 		$valid_days = $validator->getNotNull($path_data['txtEndTime'],'请输入有效期');
	// 		$validator->getNum($path_data['txtEndTime'],1,90,'请输入1-90之间的整数');
	// 		//截至时间
	// 		$end_time = date('Y-m-d H:i:s', strtotime("+{$valid_days} day"));
	// 		if($validator->has_err){
	//     		echo header("Content-type:text/plain;charset=utf-8");
	// 			echo $validator->toJsonWithHtml();
	// 			return;
	//    		}
	// 	}
	// 	$result = $service_job->rePubJob($this->_userid, $job, $end_time, $valid_days);
	// 	if (!$result){
	// 		echo json_encode(array('error'=>'重新发布职位失败'));
	// 		return ;
	// 	}
	// 	echo json_encode(array('success'=>true,'job_id'=>$job_id));
	// 	return;
	// }
	
	// /**
	//  * 排序置顶
	//  * Enter description here ...
	//  * @param unknown_type $inPath
	//  */
	// function pageGoTop($inPath){
	// 	$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
	// 	$job_id = base_lib_BaseUtils::getStr($path_data['job_id'],'int',0);
	// 	$job = new base_service_company_job_job();
	// 	$result = $job->setOrderNo($this->_userid, $job_id);
	// 	if ($result===false){
	// 		echo json_encode(array('error'=>'置顶失败'));
	// 		return ;
	// 	}
	// 	echo json_encode(array('success'=>true));
	// }
	
	/**
	 * @desc 停止招聘
	 */
	function pageStopJob($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', 0);
		$job = new base_service_company_job_job();
		$job_status = new base_service_common_jobstatus();
		$result = $job->setJobStatus($this->_userid, $job_id, $job_status->stop_use);
		if ($result === false) {
			echo json_encode(array ('error' => '停止招聘失败'));
			
			return;
		}
		echo json_encode(array ('success' => true));
	}
	
	/**
	 * 删除职位
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	function pageDelete($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', 0);
		
		$job = new base_service_company_job_job();
		$company_id = $job->getJob($job_id, "company_id")['company_id'];
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		
		if (in_array($company_id, $company_resources->all_accounts)) {
			$result = $job->deleteJob($company_id, $job_id);
		}
		else {
			$result = false;
		}
		
		if ($result === false) {
			echo json_encode(array ('error' => '删除职位失败'));
			
			return;
		}
		//---------添加操作日志--------
		$service_job = new base_service_company_job_job();
		$job_info = $service_job->getJob($job_id, 'station');
		$common_oper_src_type = new base_service_common_account_accountlogfrom();
		$common_oper_type = new base_service_common_account_accountoperatetype();
		$service_oper_log = new base_service_company_companyaccountlog();
		$insertItems = array (
			"company_id"   => $this->_userid,
			"account_id"   => base_lib_BaseUtils::getCookie('accountid'),
			"source"       => $common_oper_src_type->website,
			"operate_type" => $common_oper_type->job_del,
			"content"      => "删除职位：" . $job_info['station'],
			"create_time"  => date("Y-m-d H:i:s", time())
		);
		$service_oper_log->addLogToMongo($insertItems);
		//-------------END------------
		
		echo json_encode(array ('success' => true));
	}
	
	/**
	 * 删除选中的职位
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	function pageDeleteChooseJob($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_ids = base_lib_BaseUtils::getStr($path_data['jobids'], 'array', null);
		$job_ids = base_lib_BaseUtils::getIntArrayOrString($job_ids);
		$job = new base_service_company_job_job();
		$job_list_temp = $job->getJobs($job_ids, 'job_id,station,company_id');
		$job_list = array ();
		foreach ($job_list_temp as $ite => $val) {
			if ($val['company_id'] == $this->_userid) {
				array_push($job_list, $val);
			}
		}
		if (empty($job_list) || count($job_list) < 1) {
			echo json_encode(array ('error' => '没有可删除的职位'));
			
			return;
		}
		$failItem = '';
		$failCount = 0;
		$failMsg = '';
		$jobids = array ();
		foreach ($job_list as $item => $v) {
			$result = $job->deleteJob($this->_userid, $v['job_id']);
			if ($result === false) {
				if ($failCount < 5) {
					$failItem .= "<br />{$v['station']}";
				}
				$failCount++;
				continue;
			}
			array_push($jobids, $v['job_id']);
		}
		if (!base_lib_BaseUtils::nullOrEmpty($failItem)) {
			if ($failCount > 5) {
				$failItem = $failItem . '...等' . $failCount . '个职位删除失败，请稍后重试！';
			}
			else {
				$failItem = $failItem . '，删除失败，请稍后重试！';
			}
			echo json_encode(array ('fail' => true, 'failitem' => $failItem, 'job_ids' => $jobids));
			
			return;
		}
		echo json_encode(array ('success' => true, 'job_ids' => $jobids));
	}
	
	/**
	 * 设置置顶
	 * @param unknown_type $inPath
	 */
	function pageTopBox($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($path_data['jobid'], 'int', 0);
		
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		list($status, $code, $params) = $company_resources->check($func = "top", $params = ['job_id' => $job_id]);
		if (!$status) {
			$this->_aParams['message'] = $params['msg'];
			
			return $this->render('showerror.html', $this->_aParams);
		}
		
		$this->_aParams['params'] = $params;
		if ($params['is_already']) {
			//是否已经是置顶职位
			$service_jobtop = new base_service_company_job_jobtop();
			$top_list = $service_jobtop->getList($service_id = 0, $this->_userid, $job_id, 'id,keyword,end_time')->items;
			
			$this->_aParams['toplist'] = $top_list;
		}
		
		$service_job = new base_service_company_job_job();
		$current_job = $service_job->getJob($job_id, 'job_id,jobsort,station,company_id,check_state,status,end_time,urgent_end_time');
		
		$common_jobsort = new base_service_common_jobsort();
		$this->_aParams['job_id'] = $job_id;
		$this->_aParams['jobsorts'] = $common_jobsort->getSelfAndParentJobsort($current_job['jobsort']);
		
		//是否已经是置顶职位
		return $this->render('settopjob.html', $this->_aParams);
	}
	
	/**
	 * 设置置顶
	 * @param  array $inPath url参数
	 * @return [type]         [description]
	 */
	// function pageSetTop($inPath) {
	// 	//判断单位是否登录
	// 	$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
	// 	$jobid     = base_lib_BaseUtils::getStr($path_data['jobid'], 'int', 0);
	// 	$txtword1  = base_lib_BaseUtils::getStr($path_data['txtword1'], 'string', '');
	// 	$dllday1   = base_lib_BaseUtils::getStr($path_data['dllday1'], 'int', 0);
	// 	$topid1    = base_lib_BaseUtils::getStr($path_data['topid1'], 'int', 0);
	// 	$txtword2  = base_lib_BaseUtils::getStr($path_data['txtword2'], 'string', '');
	// 	$dllday2   = base_lib_BaseUtils::getStr($path_data['dllday2'], 'int', 0);
	// 	$topid2    = base_lib_BaseUtils::getStr($path_data['topid2'], 'int', 0);
	// 	$txtword3  = base_lib_BaseUtils::getStr($path_data['txtword3'], 'string', '');
	// 	$dllday3   = base_lib_BaseUtils::getStr($path_data['dllday3'], 'int', 0);
	// 	$topid3    = base_lib_BaseUtils::getStr($path_data['topid3'], 'int', 0);
	
	// 	foreach ([1, 2, 3] as $num) {
	// 		if (!empty(${dllday . $num})) {
	// 			if (empty(${txtword . $num})) {
	// 				echo json_encode(['error' => '请填写置顶的关键词']);
	// 				exit;
	// 			}
	
	// 			$tops[] = [
	// 				'job_id'      => $jobid,
	// 				'keyword'     => ${txtword . $num},
	// 				'delay_day'   => ${dllday . $num}
	// 			];
	// 		}
	// 	}
	
	// 	$company_resources = base_service_company_resources_resources::getInstance($this->_userid);
	// 	list($status, $code, $params) = $company_resources->check($func="top", $params=['job_id'=>$job_id, "tops"=>$tops]);
	// 	if (!$status) {
	// 		$this->_aParams['message'] = $params['msg'];
	// 		return $this->render('showerror.html', $this->_aParams);
	// 	}
	
	// 	$this->_aParams['params'] = $params;
	// 	return $this->render("spread/order.html", $this->_aParams);
	// }
	
	// $urgent_point = 0;
	// $dlldays      = array(0);
	// if (!empty($dllday1)) {
	// 	if (empty($txtword1)) {
	// 		echo json_encode(array('error' => '请填写要置顶的关键词'));
	// 		return ;
	// 	}
	// 	$top_item[0]['id']         = $topid1;
	// 	$top_item[0]['service_id'] = $comservice['service_id'];
	// 	$top_item[0]['company_id'] = $this->_userid;
	// 	$top_item[0]['job_id']     = $jobid;
	// 	$top_item[0]['keyword']    = $txtword1;
	// 	if (!empty($endtime1)) {
	// 		$top_item[0]['end_time'] = ;
	// 	} else {
	// 		$top_item[0]['end_time'] =
	// 	}
	// 	$top_item[0]['point'] = $dllday1;
	// 	$top_item[0]['create_time'] = date('Y-m-d H:i:s');
	
	// 	$urgent_point += $dllday1;
	// 	array_push($dlldays, $dllday1);
	// }
	
	
	// if (!empty($dllday2)) {
	// 	if(empty($txtword2)){
	// 		echo json_encode(array('error'=>'请填写要置顶的关键词'));
	// 		return ;
	// 	}
	// 	$top_item[1]['id']         = $topid2;
	// 	$top_item[1]['service_id'] = $comservice['service_id'];
	// 	$top_item[1]['company_id'] = $this->_userid;
	// 	$top_item[1]['job_id']     = $jobid;
	// 	$top_item[1]['keyword']    = $txtword2;
	// 	if (!empty($endtime2)) {
	// 		$top_item[1]['end_time'] = date('Y-m-d H:i:s', strtotime("+{$dllday2} days", strtotime($endtime2)));
	// 	} else {
	// 		$top_item[1]['end_time'] = date('Y-m-d H:i:s', strtotime("+{$dllday2} days"));
	// 	}
	// 	$top_item[1]['point']       = $dllday2;
	// 	$top_item[1]['create_time'] = date('Y-m-d H:i:s');
	
	// 	$urgent_point += $dllday2;
	// 	array_push($dlldays, $dllday2);
	// }
	
	// if (!empty($dllday3)) {
	// 	if (empty($txtword3)) {
	// 		echo json_encode(array('error'=>'请填写要置顶的关键词'));
	// 		return ;
	// 	}
	// 	$top_item[2]['service_id'] = $comservice['service_id'];
	// 	$top_item[2]['company_id'] = $this->_userid;
	// 	$top_item[2]['job_id']     = $jobid;
	// 	$top_item[2]['keyword']    = $txtword3;
	// 	if (!empty($endtime3)) {
	// 		$top_item[2]['end_time'] = date('Y-m-d H:i:s',strtotime("+{$dllday3} days",strtotime($endtime3)));
	// 	} else {
	// 		$top_item[2]['end_time'] = date('Y-m-d H:i:s',strtotime("+{$dllday3} days"));
	// 	}
	
	// 	$top_item[2]['point']       = $dllday3;
	// 	$top_item[2]['create_time'] = date('Y-m-d H:i:s');
	
	// 	$urgent_point += $dllday3;
	// 	array_push($dlldays, $dllday3);
	// }
	
	// $maxday = max($dlldays);
	// $end_time_stamp = strtotime("+$maxday days");
	
	// $service_jobtop = new base_service_company_job_jobtop();
	// $topcount = $service_jobtop->getTotalTopCount($comservice['service_id'], $this->_userid);
	
	// if (($topcount+$urgent_point) > $comservice['stick_point']) {
	// 	echo json_encode(array('error'=>'您的置顶点不够支付您当前所需支付的点数'));
	// 	return ;
	// }
	// if (count($top_item)) {
	// 	$service_jobtop = new base_service_company_job_jobtop();
	// 	$new_job_end_time = '';
	// 	$result = $service_jobtop->AddJobTop($top_item,$current_job,$end_time_stamp,$new_job_end_time);
	// 	if (!base_lib_BaseUtils::nullOrEmpty($new_job_end_time)) {
	// 		$new_job_end_time = date('m-d', strtotime($new_job_end_time));
	// 	}
	// 	if ($result === false) {
	// 		echo json_encode(array('error'=>'设置置顶失败'));
	// 		return ;
	// 	}
	
	// 	$toplist  = $service_jobtop->getList($comservice['service_id'], $this->_userid, $jobid, "id");
	// 	$topcount = count($toplist->items);
	// 	echo json_encode(array('success'=>'设置置顶成功','job_id'=>$jobid,'point'=>$urgent_point,'toplist'=>$topcount,'new_job_end_time'=>$new_job_end_time));
	// 	return ;
	// }
	
	// echo json_encode(array('normal'=>true));
	// return ;
	// }
	
	
	// public function pageCheckCanTopJob($inPath){
	// 	//判断单位是否登录
	// 	$service_company = new base_service_company_company();
	// 	$current_company = $service_company->getCompany($this->_userid, 1, 'com_level');
	// 	if (empty($current_company)) {
	// 		echo json_encode(array('error' => '请登录'));
	// 		return ;
	// 	}
	
	// 	//判断单位是否开通服务
	// 	$service_comservice = new base_service_company_service_comservice();
	// 	$comservice = $service_comservice->getComService($this->_userid, 'com_level,service_id,stick_point');
	// 	if (empty($comservice)){
	// 		echo json_encode(array('error' => '单位未开通服务'));
	// 		return ;
	// 	}
	
	// 	$service_jobtop = new base_service_company_job_jobtop();
	// 	$topcount = $service_jobtop->getTotalTopCount($comservice['service_id'], $this->_userid);
	// 	$stick_point = $comservice['stick_point'] == '' ? 0 : $comservice['stick_point'];
	
	// 	// 判断单位会员服务总急聘数
	// 	if (($stick_point - $topcount) <= 0) {
	// 		echo json_encode(array('fail' => '您的置顶点剩余：0，无法将此职位设为“置顶”，有疑问，请联系您的招聘顾问！'));
	// 		return ;
	// 	}
	
	// 	// 判断该职位是否在审核中，如果是，则不能设置
	// 	$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
	// 	$job_id    = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', 0);
	
	// 	if ($job_id != 0) {
	// 		$service_job = new base_service_company_job_job();
	// 		$job = $service_job->getJob($job_id, "job_id,check_state");
	// 		if (!base_lib_BaseUtils::nullOrEmpty($job) && $job['check_state'] == 4) {   //处于审核中状态，不能设置
	// 			echo json_encode(array('error' => '职位处于审核中，请通过审核后再设置置顶！'));
	// 			return ;
	// 		}
	// 	}
	
	// 	echo json_encode(array('success'=>'success', 'job_id'=>$job_id));
	// 	return ;
	// }
	
	function pageQQ($inPath) {
		return $this->render('qq.html', $this->_aParams);
	}
	
	/**
	 * 二维数组根据某值排序
	 * Enter description here ...
	 * @param $arr
	 * @param $keys
	 * @param $type
	 */
	private function __arraySort($arr, $keys, $type = 'asc') {
		$keysvalue = $new_array = array ();
		foreach ($arr as $k => $v) {
			$keysvalue[ $k ] = $v[ $keys ];
		}
		if ($type == 'asc') {
			asort($keysvalue);
		}
		else {
			arsort($keysvalue);
		}
		reset($keysvalue);
		foreach ($keysvalue as $k => $v) {
			$new_array[] = $arr[ $k ];
		}
		
		return $new_array;
	}
	
	private function GetDomainInfor() {
		$domain = array (
			'image'        => '',
			'photo'        => '',
			'defaultPhoto' => ''
		);
		$xml = SXML::load('../config/config.xml');
		if (is_null($xml)) {
			return $domain;
		}
		$domain["image"] = $xml->ImgDomain;
		$domain["photo"] = $xml->CqjobSysUserHeadPhoto;
		$domain["defaultPhoto"] = $xml->CqjobSysUserDefaultHeadPhoto;
		
		return $domain;
	}
	
	private function GetHRManager($heap_id) {
		$companyHeapService = new base_service_company_netheap();
		$companyHeap = $companyHeapService->GetNetHeapByID($heap_id, "own_man");
		$userInfor = null;
		if (is_null($companyHeap) || !isset($companyHeap["own_man"])) {
			return $userInfor;
		}
		$userService = new base_service_crm_user();
		$userInfor = $userService->GetUsers($companyHeap['own_man'], "user_name,head_photo_url,tel,mobile,qq");
		
		return $userInfor;
	}
	
	// 获取客服人员
	// private function GetCustomerService($companyid) {
	// 	$customerbelongService = new base_service_crm_netcompanycustomerbelong();
	// 	$customerbelong =  $customerbelongService->getCustomerbelongById($companyid, 'net_heap_id');
	// 	if(empty($customerbelong)) {
	// 		return null;
	// 	}
	// 	$customerheapservice = new base_service_crm_netcustomerheap();
	// 	$customerheap = $customerheapservice->getCustomerheapById($customerbelong['net_heap_id'], 'own_man');
	// 	if(empty($customerheap)) {
	// 		return null;
	// 	}
	// 	$userService=new base_service_crm_user();
	// 	$userInfor = $userService->GetUsers($customerheap['own_man'], "user_name,head_photo_url,tel,mobile,qq");
	// 	return $userInfor;
	// }
	
	//获取企业首页广告
	private function _getAdvert($company) {
		
		// if(isset($company['recruit_type']) && !in_array($company['recruit_type'],[2,4,9])){
		// 	return false;
		// }
		
		$advertConfig = new AdConfig('../config/advert.xml');
		$adPath = $advertConfig->getAdPath();
		$callingAdPath = $advertConfig->getCallingPath();
		$type = '104';
		
		$service_advert = new base_service_advert_advert();
		
		$fields = 'advert_id,advert_type,url,img,is_url,company_id';
		
		$adv_list = $service_advert->getAdvertsOfType($type, $fields)->items;
		
		$xml = SXML::load('../config/advert.xml');
		if (!is_null($xml)) {
			$VirtualName = $xml->VirtualName;
			$CompanyImagePath = $xml->CompanyImagePath;
			$file_folder = $xml->AdFolder;
		}
		
		// $this->_aParams['advpath'] = base_lib_Constant::UPLOAD_FILE_URL."/{$VirtualName}/{$file_folder}/";
		$this->_aParams['advpath'] = "{$VirtualName}/{$file_folder}/";
		
		$this->_aParams['advLst'] = $adv_list;
		
		// var_dump($adv_list,$this->_aParams['path']);
		
	}
	
	/**
	 *
	 * 获取数组里对象的属性集合
	 * @param array $arr       对象数组
	 * @param string $property 属性
	 */
	private function getPropertys($arr, $property) {
		$peropertys = array ();
		foreach ($arr as $item) {
			array_push($peropertys, $item[ $property ]);
		}
		
		return $peropertys;
	}
	
	/**
	 * 数组查询
	 * @param array $arr
	 * @param string $key
	 * @param string $value
	 */
	private function arrayFind($arr, $property, $value) {
		foreach ($arr as $item) {
			if ($item[ $property ] == $value) {
				return $item;
			}
		}
		
		return null;
	}
	
	/**
	 * 设置自动刷新
	 * @param unknown_type $inPath
	 */
	public function pageAutoRefesh($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$auto_status = base_lib_BaseUtils::getStr($path_data['auto_status'], 'int', 0);
		
		$service_comservice = new base_service_company_service_comservice();
		$comservice = $service_comservice->getComService($this->_userid, 'is_enabled_autorefresh');
		
		if ($comservice['is_enabled_autorefresh'] != 1) {
			$json = array ("info" => "尚未拥有自动刷新功能权限，成为相应会员即可开通此功能", "status" => "error");
			echo json_encode($json);
			
			return;
		}
		
		$service_refresh = new base_service_company_refresh();
		$result = $service_refresh->setAuto($this->_userid, $auto_status);
		
		if ($result === false) {
			if ($auto_status == 1) {
				$json = array ("info" => "设置自动刷新失败", "status" => "error");
			}
			else {
				$json = array ("info" => "取消自动刷新失败", "status" => "error");
			}
			echo json_encode($json);
			
			return;
		}
		
		if ($auto_status == 1) {
			$json = array ("info" => "设置自动刷新成功", "status" => "success");
		}
		else {
			$json = array ("info" => "取消自动刷新成功", "status" => "success");
		}
		
		echo json_encode($json);
		
		return;
	}
	
	/**
	 * @desc 修改公司logo
	 */
	public function pageEditLogoPath($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$logo_path = base_lib_BaseUtils::getStr($path_data['logo_path'], 'string', '');
		if ($logo_path == '') {
			echo json_encode(array ('error' => '上传logo失败'));
			exit;
		}
		$companyService = new base_service_company_company();
		$re = $companyService->saveCompanyLogoPath($this->_userid, $logo_path);
		if ($re === false) {
			echo json_encode(array ('error' => '上传logo失败'));
			exit;
		}
		$del_logo = base_lib_BaseUtils::getStr($path_data['del_logo']);
		$logo_name = array ();
		// 读取配置xml文件
		$xml = SXML::load('../config/company/company.xml');
		if (!is_null($xml)) {
			$logo_folder = $xml->LogoFolder;
			$logo_temp_folder = $xml->LogoTempFolder;
			$company_image_path = $xml->CompanyImagePath;
			$virtualName = $xml->VirtualName;
		}
		array_push($logo_name, $logo_path);
		
		//将LOGO移动到正式目录中
		$postvar['newfile'] = "{$virtualName}/{$logo_folder}";
		$postvar['oldfile'] = "{$virtualName}/{$logo_temp_folder}";
		$postvar['names'] = $logo_name;
		$postvar['thumbSuffix'] = '';
		$postvar['authenticate'] = "logo";
		$result_move = base_lib_Uploadfilesv::moveFile($postvar);
		if (!$result_move) {
			echo json_encode(array ('error' => "修改logo图片失败"));
			
			return;
		}
		//else{
		//    //移动云存储文件到正式目录
		//	//上传七牛云存储
		//	$qiniu = new SQiniu();
		//	$qiniu->Move($postvar['oldfile']."/".$logo_path, base_lib_Constant::QINIU_BUCKET, $postvar['newfile']."/".$logo_path, base_lib_Constant::QINIU_BUCKET);
		//}
		if (!base_lib_BaseUtils::nullOrEmpty($del_logo)) {
			$result = $companyService->delLogo($del_logo);
		}
		echo json_encode(array ('success' => "修改logo成功"));
		
		return;
	}
	
	private function _buildArray($arr, $filer = "resume_id") {
		if (!is_array($arr) || count($arr) <= 0) {
			return $arr;
		}
		foreach ($arr as $key => $value) {
			$new_arr[ $value["$filer"] ] = $value;
		}
		
		return $new_arr;
	}
	
	private function _buildIDs($arr, $filer = "resume_id") {
		if (!is_array($arr) || count($arr) <= 0) {
			return $arr;
		}
		foreach ($arr as $key => $value) {
			$newArr[] = $value["$filer"];
		}
		
		return implode(',', $newArr);
	}
	
	//添加审核营业执照消息
	private function _addMessage() {
		$service_message_company = new base_service_message_messagecompany();
		//根据公司编号和类型查询
		$mssage_info = $service_message_company->getMesssageByType($this->_userid, "message_id", 9);
		if (!empty($mssage_info)) {
			return;
		}
		$xml = SXML::load('../config/config.xml');
		if (!is_null($xml)) {
			$huibo_title = $xml->HuiBoSiteTitle;
		}
		$message_item['company_id'] = $this->_userid;
		$message_item['subject'] = "您的企业信息尚未审核，请上传企业资质";
		$message_item['content'] = "您的企业信息尚未审核，请上传企业资质";
		$message_item['create_time'] = date('Y-m-d H:i:s');
		$message_item['sender'] = $huibo_title;
		$message_item['read_state'] = 0;
		$message_item['type'] = 9;
		$message_item['is_effect'] = '1';
		$message_item['link_msg_id'] = 0;
		$message_item['create_time'] = date('Y-m-d H:i:s');
		$service_message_company->addMessageCompany($message_item);
	}
	
	//检查企业是否有权限查看完整简历
	private function __checkResumeShowAll($resume_id, $relate_resume_id) {
		$is_show_resumeinfo = false;
		$service_apply = new base_service_company_resume_apply();
		$service_download = new base_service_company_resume_download();
		// 验证求职者是否投递过企业的职位
		if ($service_apply->isApply($this->_userid, $resume_id)) {
			$is_show_resumeinfo = true;
		}
		else if ($service_apply->isApply($this->_userid, $relate_resume_id)) {
			$is_show_resumeinfo = true;
		}
		else {
			$enum_openmode = new base_service_common_openmode();
			if ($person['open_mode'] == $enum_openmode->notopen) {
				$is_show_resumeinfo = false;
			}
			
			// 验证企业是否下载过该简历
			if ($service_download->isResumeDownloaded($this->_userid, $resume_id)) {
				$is_show_resumeinfo = true;
			}
		}
		
		$service_company = new base_service_company_company();
		if ($is_show_resumeinfo != true) {
			$is_show_resumeinfo = $service_company->isShowResumeInfo($this->_userid);
		}
		
		return $is_show_resumeinfo;
	}
	
	/**
	 * @desc 职位类别匹配
	 */
	public function pageUpdateJobSort($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$jobids = base_lib_BaseUtils::getStr($path_data['jobids'], "string", "");
		//获得当前企业的未设置职位类别的职位
		//获得已发布的职位
		$job_status = new base_service_common_jobstatus();
		$service_job = new base_service_company_job_job();
		if (!empty($jobids)) {
			$jobs = $service_job->getJobs(explode(",", $jobids), "job_id,jobsort_ids,station");
		}
		else {
			$jobs = array ();
		}
		$this->_aParams['jobs'] = $jobs;
		
		return $this->render("./updatejobsort.html", $this->_aParams);
	}
	
	/**
	 * @desc 职位类别匹配修改
	 */
	public function pageUpdateJobSortDo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$hidJobSort = base_lib_BaseUtils::getStr($path_data['hidJobsort'], "array", array ());
		//获得当前企业的未设置职位类别的职位
		//获得已发布的职位
		$result = true;
		$service_job = new base_service_company_job_job();
		//验证数据的有效性
		if (count($hidJobSort) > 0) {
			foreach ($hidJobSort as $key => $job_sort) {
				if (empty($job_sort)) {
					echo json_encode(array ("error" => "职位类别不能为空"));
					exit;
				}
			}
		}
		if (count($hidJobSort) > 0) {
			foreach ($hidJobSort as $key => $job_sort) {
				if (!empty($job_sort)) {
					$r = $service_job->UpdateJobSbyJobSort($key, $job_sort);
					if ($r !== true) {
						$result = false;
						break;
					}
				}
			}
		}
		if ($result !== false) {
			echo json_encode(array ("success" => "修改职位类别成功"));
		}
		else {
			echo json_encode(array ("error" => "修改职位类别失败"));
		}
	}
	
	/**
	 * @desc 行业类别选择
	 */
	public function pageSelectCalling($inPath) {
		return $this->render("selectcalling.html");
	}
	
	/**
	 * @desc 行业类别匹配修改
	 */
	public function pageUpdateCallingDo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$main_calling = base_lib_BaseUtils::getStr($path_data['main_calling'], "string", '');
		$next_calling = base_lib_BaseUtils::getStr($path_data['next_calling'], "string", '');
		//获得当前企业设置的行业类别信息
		$result = true;
		//验证数据的有效性
		if (empty($main_calling)) {
			echo json_encode(array ("error" => "行业类别不能为空"));
			exit;
		}
		//修改行业信息
		$calling_list = array ($main_calling);
		if (!empty($next_calling)) {
			array_push($calling_list, $next_calling);
		}
		$service_companycalling = new base_service_company_companycalling();
		$company['calling_ids'] = implode(',', $calling_list);
		$company['calling_id'] = $calling_list[0];
		$company_calling['is_effect'] = '0';
		$service_companycalling->update(array ('company_id=' . $this->_userid), $company_calling);
		foreach ($calling_list as $addcallingId) {
			$addCalling['calling_id'] = $addcallingId;
			$addCalling['company_id'] = $this->_userid;
			$addCalling['is_effect'] = '1';
			$service_companycalling->insert($addCalling);
		}
		//更新缓存
		$service_company = new base_service_company_company();
		$result = $service_company->cacheHashSet($this->_userid, $company, array ('company_id' => $this->_userid));
		if ($result !== false) {
			echo json_encode(array ("success" => "修改行业类别成功"));
		}
		else {
			echo json_encode(array ("error" => "修改行业类别失败"));
		}
	}
	
	public function pageAjaxSpreadJobStatistics() {
		
		//访问量
		$clickSpreadJob_service = new base_service_company_spread_click();
		$spreadClickCount = $clickSpreadJob_service->getCompanySpreadClickCount($this->_userid);
		//曝光量
		$seeSpreadJob_service = new base_service_company_spread_seehistory();
		$spreadJobSeeCount = $seeSpreadJob_service->getCompanySpreadSeeCount($this->_userid);
		
		$spreadData = array ();
		$spreadData['spreadClickCount'] = $spreadClickCount;
		$spreadData['spreadJobSeeCount'] = $spreadJobSeeCount;

//		$this->_aParams['spreadData'] = $spreadData;
		return json_encode($spreadData);
	}
	
	/*
	 * 获取注册推广的活动
	 * */
	private function getPromotionreg() {
		$promotion = new base_service_company_promotion_promotion();
		$promotionid = $promotion->getAction('registered');
		$thisTime = date('Y-m-d H:i:s');
		$condtion = "id = {$promotionid} and start_time <='{$thisTime}' and end_time>= '{$thisTime}'";
		$ret = $promotion->getData($condtion, 'is_effect,area_ids,calling_ids,share_price');
		$ret['area_ids'] = explode(',', $ret['area_ids']);
		$common_area = new base_service_common_area();
		$areaList = $ret['area_ids'];
		foreach ($areaList as $item) {
			$file = $common_area->getChildArea($item);
			if (!empty($file)) {
				foreach ($file as $value) {
					array_push($ret['area_ids'], $value['area_id']);
				}
			}
		}
		$ret['main_area'] = $areaList;//主城区id
		$ret['calling_ids'] = explode(',', $ret['calling_ids']);
		
		return $ret;
	}
	
	/*
	 * 获取公司的注册推广码 和 推荐短连接
	 * */
	private function getCompanyPromotionCode() {
		
		$promotioninvitecode = new base_service_company_promotion_promotioninvitecode();
		$condition = array ('company_id' => $this->_userid);
		$ioninvitecode = $promotioninvitecode->getData($condition, 'id,company_id,code,short_link,create_time');
		if (empty($ioninvitecode)) {
			
			$code = $promotioninvitecode->getPromotionCode();
			$data = array ();
			$data['company_id'] = $this->_userid;
			$data['code'] = $code;
			$data['create_time'] = date('Y-m-d H:i:s');
			$ret = $promotioninvitecode->addData($data);
			
			$invite = new base_service_company_resume_jobinvite();
			$ShortLink = $invite->createShortLinkReg($ret, base_lib_Rewrite::getFlag('company', $this->_userid));
			$promotioninvitecode->saveData($ret, array ('short_link' => $ShortLink));
			if ($ret !== false) {
				return array ('code' => $code, 'shortLink' => $ShortLink);
			}
		}
		else {
			return array ('code' => $ioninvitecode['code'], 'shortLink' => $ioninvitecode['short_link']);
		}
	}
	
	/*
	 * 获取已经通过审核的推荐注册企业  并保存修改状态为已查看
	 * */
	private function getPromotionRegistered($company_id) {
		$promotionregistered = new base_service_company_promotion_promotionregistered();
		$promotion = new base_service_company_promotion_promotion();
		$promotion_id = $promotion->getAction('registered');
		$proData = $promotion->getData(array ('id' => $promotion_id), 'registered_price,share_price');
//		$condition = array();
		$condition = '(company_id = ' . $company_id . ' or promotion_company_id = ' . $company_id . ') and status = 1 and ( check_status = 0 or promotion_check_status = 0)';
		$list = $promotionregistered->getDatas($condition, 'reg_id,company_id,company_name,promotion_company_id,promotion_company_name,check_status,promotion_check_status');
		$reg_list = array ();
		$promotion_list = array ();
		$price = 0;
		$promotion_price = 0;
		foreach ($list as $item) {
			//注册企业
			if ($company_id == $item['company_id'] && $item['check_status'] == 0) {
				$reg_list[] = $item['company_name'];
				$ret = $promotionregistered->saveData($item['reg_id'], array ('check_status' => 1));
				$price += $proData['registered_price'];
			}
			//推广企业
			if ($company_id == $item['promotion_company_id'] && $item['promotion_check_status'] == 0) {
				$promotion_list[] = $item['company_name'];
				$ret1 = $promotionregistered->saveData($item['reg_id'], array ('promotion_check_status' => 1));
				$promotion_price += $proData['share_price'];
			}
		}
		$array = array (
			'reglist'          => $reg_list,
			'promotionlist'    => implode('、', $promotion_list),
			'price'            => $price,
			'promotion_price'  => $promotion_price,
			'registered_price' => $proData['registered_price'],
			'share_price'      => $proData['share_price']
		);
		
		return $array;
	}
	
	function getSalesDeptQRCode($dept_id = '') {
		switch ($dept_id) {
			case 176:
				return base_lib_Constant::YUN_JSCSSIMG_URL . '/img/company/001.png';
				break;
			case 177:
				return base_lib_Constant::YUN_JSCSSIMG_URL . '/img/company/002.png';
				break;
			case 178:
				return base_lib_Constant::YUN_JSCSSIMG_URL . '/img/company/003.png';
				break;
			case 179:
				return base_lib_Constant::YUN_JSCSSIMG_URL . '/img/company/004.png';
				break;
			case 180:
				return base_lib_Constant::YUN_JSCSSIMG_URL . '/img/company/005.png';
				break;
			case 181:
				return base_lib_Constant::YUN_JSCSSIMG_URL . '/img/company/006.png';
				break;
			case 182:
				return base_lib_Constant::YUN_JSCSSIMG_URL . '/img/company/007.png';
				break;
			case 189:
				return base_lib_Constant::YUN_JSCSSIMG_URL . '/img/company/008.png';
				break;
			case 183:
				return base_lib_Constant::YUN_JSCSSIMG_URL . '/img/company/009.png';
				break;
			case 201:
				return base_lib_Constant::YUN_JSCSSIMG_URL . '/img/company/010.png';
				break;
			default:
				return base_lib_Constant::YUN_JSCSSIMG_URL . '/img/company/public.png';
				break;
		}
	}

	/**
	 *
	 * 验证简历
	 * @param  $resume_id
	 * @param  $is_show_name
	 * @param  $is_show_linkway
	 * @param  $not_member
	 * @param  $member_expires
	 */
	private function checkResume($resume_id, &$is_show_name, &$is_show_linkway, &$member_info, &$member_expires, &$is_show_resumeinfo) {
		$service_company = new base_service_company_company();
		$service_person  = new base_service_person_person();
		$service_resume  = new base_service_person_resume_resume();
		$result = true;
		$resume = $service_resume->getResume($resume_id, 'resume_id,person_id');
		if (empty($resume)) {
			return false;
		}

		$person = $service_person->getPerson($resume['person_id'], 'name_open,user_name,user_name_en,open_mode');
		if (empty($person)) {
			return false;
		}


//		if ($person['name_open'] == 1) {
//			$is_show_name = true;
//		}

		$service_apply    = new base_service_company_resume_apply();
		$service_download = new base_service_company_resume_download();

		// 验证求职者是否投递过企业的职位
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
		$relate_resume_id  = empty($resume['relate_resume_id']) ? 0 : $resume['relate_resume_id'];
		if ($service_apply->isApply($company_resources->all_accounts, $resume['resume_id'])) {
			$is_show_linkway    = true;
			$is_show_name       = true;
			$is_show_resumeinfo = true;
		} else if ($service_apply->isApply($company_resources->all_accounts, $relate_resume_id)) {
			$is_show_linkway    = true;
			$is_show_name       = true;
			$is_show_resumeinfo = true;
		} else {
			$enum_openmode = new base_service_common_openmode();

		}

		$privilege = $service_person->checkMobilesPrivilege(array($resume_id), $this->_userid);
		$is_show_linkway = $privilege[$resume_id];
		//如果是投递或者下载的简历 显示姓名
		$is_show_name = $privilege[ $resume_id ];

		return $result;
	}


	private function setOuterStop($job_id) {
		if (empty($job_id)) {
			return;
		}
		$outerJob_union_InnerJob_service = new base_service_outer_outerjobunioninnerjob();
		//获取站外关联id
		$outer_id = $outerJob_union_InnerJob_service->getOuterjobsort($job_id, 'id');
		if (empty($outer_id)) {
			return;
		}
		//关闭 自动关闭站内职位
		$outerJob_union_InnerJob_service->setOuterJobAutoStopStatus($outer_id['id']);
	}
	
	private function spreadJobStatistics($jobList) {
		$spreadCount = 0;
		if (!empty($jobList)) {
			$job_ids = base_lib_BaseUtils::getProperty($jobList, 'job_id');
			$company_ids = base_lib_BaseUtils::getProperty($jobList, 'company_id');
			$company_ids = array_unique($company_ids);
			
			//推广数量
			$spreadJob_service = new base_service_company_spread_spreadjob();
			$EffectSpreadJob = $spreadJob_service->getSpreadJobListOfjob_ids($company_ids, $job_ids);
			$spreadCount = count($EffectSpreadJob);
			$this->_aParams['EffectSpreadJob_ids'] = $EffectSpreadJob;
		}
		
		return $spreadCount;
	}
	
	private function getNewBulletin() {
		$obj_id = 1;
		$bulletin_service = new base_service_bulletin_bulletin();
		$newBulletin = $bulletin_service->getNewBulletinOne($obj_id);
		
		$newBulletin['msg_type'] = explode(',', $newBulletin['msg_type']);
		$newBulletin['is_show'] = false;
		$newBulletin['is_show_yellow_strip'] = false;
		
		//判断是否存在弹窗 且 时间在显示时间区间内
		if (strtotime($newBulletin['start_time']) < time() && strtotime($newBulletin['end_time']) > time()) {
			if (in_array(1, $newBulletin['msg_type'])) {
				$newBulletin['is_show_yellow_strip'] = true;
			}
			if (in_array(2, $newBulletin['msg_type']) && !base_lib_BaseUtils::getCookie("newBulletinidByC{$newBulletin['id']}")) {
				$newBulletin['is_show'] = true;
			}
		}
		
		return $newBulletin;
	}
	
	public function pageshowNewBulletin($inpath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inpath));
		$id = base_lib_BaseUtils::getstr($pathdata['id'], 'int', '');
		if (empty($id)) {
			echo "参数错误！";
			
			return;
		}
		$bulletin_service = new base_service_bulletin_bulletin();
		$newBulletin = $bulletin_service->getDataOne($id, 'id,title,content,create_time,start_time,end_time,msg_type');
		$this->_aParams['newBulletin'] = $newBulletin;
		echo $this->render('./showNewBulletin.html', $this->_aParams);
	}
	
	public function pageCloseNewBulletin($inpath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inpath));
		$id = base_lib_BaseUtils::getstr($pathdata['id'], 'int', '');
		if (empty($id)) {
			return;
		}
		//1 、设置当前用户和是否保留登录状态
		$cookie = array (
			"newBulletinidByC{$id}" => true,
		);
		$bulletin_service = new base_service_bulletin_bulletin();
		$newBulletin = $bulletin_service->getDataOne($id, 'end_time');
		$endTime = strtotime($newBulletin['end_time']) - time();
		base_lib_BaseUtils::ssetcookie($cookie, $endTime, '/', base_lib_Constant::COOKIE_DOMAIN);
	}
	
	public function pageCloseNewBulletinMsg($inpath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inpath));
		$id = base_lib_BaseUtils::getstr($pathdata['id'], 'int', '');
		if (empty($id)) {
			return;
		}
		//1 、设置当前用户和是否保留登录状态
		$cookie = array (
			"newBulletinidByMsgList{$id}" => true,
		);
		$bulletin_service = new base_service_bulletin_bulletin();
		$newBulletin = $bulletin_service->getDataOne($id, 'end_time');
		$endTime = strtotime($newBulletin['end_time']) - time();
		base_lib_BaseUtils::ssetcookie($cookie, $endTime, '/', base_lib_Constant::COOKIE_DOMAIN);
	}
	
	/*
	 * 获取企业昨天的职位浏览量等统计结果
	 * */
	public function getVisitToYesterDayData($company_id) {
		$visit_service = new base_service_company_job_companyjobvisit();
		$data = (array)$visit_service->getToYesterDayData($company_id);
		$retData = array ();
		
		$retData['job_visit_count'] = empty($data['visit_count']) ? 0 : $data['visit_count'];
		$retData['job_apply_count'] = empty($data['apply_count']) ? 0 : $data['apply_count'];
		$retData['job_invite_count'] = empty($data['invite_count']) ? 0 : $data['invite_count'];
		
		$this->_aParams['job_visit_number'] = $retData;
	}
	
	//意见反馈
	public function pageMakeFeedBack($inPath) {
		if (!$this->isLogin() || $this->_usertype != 'c') {
			$this->_aParams['MakeFeedBack_redirect_url'] = '/login';
		}
		
		define('IS_VIRTUAL_NAME', false);
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$feedback_type = base_lib_BaseUtils::getStr($path_data['feedback_type'], "int", "1");
		//魏霞需求，保留售后服务，并将售后服务改为pc企业意见反馈，其余的去掉
		$feedback_type = 1;
		$this->_aParams['feedback_type'] = $feedback_type;
		//当前招聘顾问
		$company_id = $this->_userid;
		$base_company = new base_service_company_company();
		$company = $base_company->getCompany($company_id, 1, 'net_heap_id,com_level,end_time,is_bad,recruit_type,link_tel,link_mobile');
		$this->_aParams['company'] = $company;
		
		$ser_related = new base_service_hractivity_related();
		$relate_data = $ser_related->getRelatedByAccount($_COOKIE['accountid'], 'person_id');
		if ($relate_data['person_id']) {
			$person_ser = new base_service_person_person();
			$person = $person_ser->getPerson($relate_data['person_id'], 'mobile_phone', true);
			$this->_aParams['bind_mobile_phone'] = $person['mobile_phone'];
		}
		
		$company_accounservice = new base_service_company_account();
		$companyaccount = $company_accounservice->getAccount($_COOKIE['accountid'], 'link_tel,mobile_phone');
		$this->_aParams['companyaccount'] = $companyaccount;
		if ($company['net_heap_id']) {
			$base_netheap = new base_service_company_netcompanyheap();
			$heapinfo = $base_netheap->getHeapInfoByHeapId($company['net_heap_id']);
			$this->_aParams['heapinfo'] = $heapinfo;
		}
		//获取在招职位
		$base_job = new base_service_company_job_job();
		
		//获取代招职位
		
		$proxyedcompany = $base_company->getProxyedCompanyByCompanyID($company_id, 'company_id')->items;
		$adult_service = new base_service_company_hrcompanyaudit();
		if (!empty($proxyedcompany)) {
			foreach ($proxyedcompany as $key => $val) {
				$auditlist = $adult_service->getHrAudits(array ($val['company_id']), 'status as is_audit');
				if ($auditlist[0]['is_audit'] != 2) {
					$company_ids[] = $val['company_id'];
				}
			}
		}
		$company_ids[] = $company_id;
		$jobs = $base_job->getJobList($company_ids, '', 1, 'company_id,station,job_id,is_effect');
		if (!empty($jobs)) {
			foreach ($jobs as $key => $val) {
				$job_ids[] = $val['job_id'];
			}
			//查询职位投递量 职位按投递量升序
			$applyJobService = new base_service_company_resume_apply();
			$statistics = $applyJobService->getApplyStatisticsByJobIdsVerson2($company_ids, $job_ids);
			if (!empty($statistics->items)) {
				foreach ($statistics->items as $key => $val) {
					$newarr[ $val['job_id'] ] = $val['total_count'];
				}
			}
			foreach ($jobs as $key => $val) {
				$jobs[ $key ]['apply_total_count'] = $newarr[ $val['job_id'] ] ? $newarr[ $val['job_id'] ] : 0;
				$apply_total_count[] = $newarr[ $val['job_id'] ] ? $newarr[ $val['job_id'] ] : 0;
			}
			
			array_multisort($apply_total_count, SORT_ASC, $jobs);
		}
		$this->_aParams['jobs'] = $jobs;
		
		//已经有反馈的职位
		$bese_feedback = new base_service_companyImprove_companyJobFeedback();
		$item = 'job_id';
		$feedback_list = $bese_feedback->getListByCompanyIdAndJobIds($company_id, $job_ids, $item)->items;
		foreach ($feedback_list as $key => $val) {
			$new_job_ids[] = $val['job_id'];
		}
		$this->_aParams['feedback_jobids'] = $new_job_ids;
		
		//上传图片
		$ser_upload = new base_service_upload_upload();
		$up_options = array ('file_name' => 'hddNewPhotoName[]', 'fileVal' => 'Filedata', 'auto' => true, 'is_load_jquery' => false);
		$this->_aParams['up_img_html'] = $ser_upload->GetUploadHtmlDom($up_options, $this->_userid, 'up_style2', 'img', 'companyAdvise', '/cp/config.xml');

		$this->_aParams['company_id'] = $company_id;
		$innerHtml = $this->render("./feedbackcommon.html", $this->_aParams);
		$this->_aParams['innerHtml'] = $innerHtml;
		
		return $this->render("./feedback_index.html", $this->_aParams);
	}
	
	//添加意见反馈
	public function pageAddCompanyImprove($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$common_data['feedback_type'] = base_lib_BaseUtils::getStr($pathdata['feedback_type'], 'int');
		$common_data['content'] = base_lib_BaseUtils::getStr($pathdata['content'], 'string');
		$common_data['tel'] = base_lib_BaseUtils::getStr($pathdata['tel'], 'string');

		$web_app_name = base_lib_BaseUtils::getStr($pathdata['webAppName'],'string','');
		$web_user_agent = base_lib_BaseUtils::getStr($pathdata['webUserAgent'],'string','');
		$url = base_lib_BaseUtils::getStr($pathdata['url'],'string','');
		$resolution = base_lib_BaseUtils::getStr($pathdata['resolution'],'string','');
		if (count($pathdata['hddNewPhotoName']) > 1) {
			$common_data['img_path'] = implode(',', $pathdata['hddNewPhotoName']);
		}
		else {
			$common_data['img_path'] = $pathdata['hddNewPhotoName'][0];
		}
		
		$company_accounservice = new base_service_company_account();
		$companyaccount = $company_accounservice->getAccount($_COOKIE['accountid'], 'user_name');
		//售后服务    现改成和触屏版一样，存入info_advise表
		$afterservice_data['company_id'] = $this->_userid;
		$afterservice_data['score_familiarity'] = base_lib_BaseUtils::getStr($pathdata['match_level_familiarity'], 'int');
		$afterservice_data['score_dispose'] = base_lib_BaseUtils::getStr($pathdata['match_level_problem'], 'int');
		$afterservice_data['score_attitude'] = base_lib_BaseUtils::getStr($pathdata['match_level_service'], 'int');
		$afterservice_data['score_efficiency'] = base_lib_BaseUtils::getStr($pathdata['match_level_response'], 'int');
		$afterservice_data['sys_user_id'] = base_lib_BaseUtils::getStr($pathdata['own_man'], 'int');
		$afterservice_data['sys_dept_id'] = base_lib_BaseUtils::getStr($pathdata['own_dept'], 'int');
		$afterservice_data['evaluate_mobile_phone'] = $common_data['tel'];
		$afterservice_data['evaluate_content'] = $common_data['content'];
		$afterservice_data['evaluate_user_id'] = $_COOKIE['accountid'];
		$afterservice_data['evaluate_name'] = $companyaccount['user_name'];
		$afterservice_data['evaluate_img_path'] = $common_data['img_path'];
		$afterservice_data['web_app_name'] = $web_app_name;
		$afterservice_data['web_user_agent'] = $web_user_agent;
		$afterservice_data['url'] = $url;
		$afterservice_data['resolution'] = $resolution;

		
		//系统功能或者其他
		$report_data['company_id'] = $this->_userid;
		$report_data['evaluate_mobile_phone'] = $common_data['tel'];
		$report_data['evaluate_content'] = $common_data['content'];
		$report_data['evaluate_img_path'] = $common_data['img_path'];
		
		//招聘效果反馈
		$feedback_data['company_id'] = $this->_userid;
		$feedback_data['account_name'] = $companyaccount['user_name'];
		$feedback_data['account_mobile_phone'] = $common_data['tel'];
		$feedback_data['sys_user_id'] = base_lib_BaseUtils::getStr($pathdata['own_man'], 'int');
		$feedback_data['sys_dept_id'] = base_lib_BaseUtils::getStr($pathdata['own_dept'], 'int');
		$jobids_string = base_lib_BaseUtils::getStr($pathdata['choose_names'], 'string');
		$feedback_problem_strings = base_lib_BaseUtils::getStr($pathdata['choose_resons'], 'string');
		
		$base_company = new base_service_company_company();
		$company = $base_company->getCompany($this->_userid, 1, 'company_name');
		
		if ($common_data['feedback_type'] == 1) {
			//售后服务
			$res = $this->_addAfterService($afterservice_data);
//			if ($res && $afterservice_data['score_familiarity']) {
//				$ser_sys_msg = new base_service_sys_msg();
//				$href = base_lib_Constant::OA_SYSTEM_URL . "/companyImprove/CompanyAfterService/?company_key=2&company_value={$this->_userid}&workorder_id={$res}";
//				$content = <<<HTML
//你收到了新的售后服务评价，请及时查看。
//
//点击查看: $href
//HTML;
//				$ser_sys_msg->sendSysMsg(array ($afterservice_data['sys_user_id']), "服务评价提醒", $content, 0);
//
//				//发送给上级
//				$sys_dept = new base_service_sys_dept();
//				$sys_user = new base_service_sys_user();
//				$base_service_sys_deptmanager = new base_service_sys_deptmanager();
//				$dept_ser = $sys_dept->deptSearchUp($afterservice_data['sys_dept_id']);
//				if (@$dept_ser) {
//					$info = $base_service_sys_deptmanager->getFirstDeptManagerDeptS($dept_ser)->items;
//					if ($info) {
//						foreach ($info as $key => $li) {
//							if (in_array($li['user_id'], array (1, 2))) {
//								unset($info[ $key ]);
//							}
//						}
//						$info = reset($info);
//						$user_name = $sys_user->getUserinfo($afterservice_data['sys_user_id'], 'user_name')['user_name'];
//						if ($info['user_id'] != 1 && $info['user_id'] != 2 && @$info['user_id']) {
//							$content = <<<HTML
//你所在部门的{$user_name}收到了新的售后服务评价， 请及时查看。
//
//点击查看: $href
//HTML;
//							$ser_sys_msg->sendSysMsg(array ($info['user_id']), "服务评价提醒", $content, 0);
//						}
//
//					}
//				}
//			}
		}
		else if ($common_data['feedback_type'] == 2) {
			//系统功能
			$res = $this->_addReport($report_data, $common_data['feedback_type']);
		}
		else if ($common_data['feedback_type'] == 3) {
			//招聘效果
			$res = $this->_addFeedBack($feedback_data, $jobids_string, $feedback_problem_strings);
			if ($res) {
				$ser_sys_msg = new base_service_sys_msg();
				$href = base_lib_Constant::OA_SYSTEM_URL . "/companyImprove/CompanyJobFeedback/?user_id={$feedback_data['sys_user_id']}";
				$content = <<<HTML
 你的客户: {$company['company_name']}({$this->_userid})提交了一条招聘效果反馈,请及时处理。

点击查看: $href
HTML;
				$ser_sys_msg->sendSysMsg(array ($feedback_data['sys_user_id']), "招聘效果反馈提醒", $content, 0);
			}
		}
		else if ($common_data['feedback_type'] == 4) {
			//其他
			$res = $this->_addReport($report_data, $common_data['feedback_type']);
		}
		
		if ($this->isLogin() && $this->_usertype == 'c') {
			if ($res) {
				$json['status'] = true;
				$json['isNeedLogin'] = false;
				$json['msg'] = '感谢您的反馈，我们会尽快跟您联系！';
			}
			else {
				$json['status'] = false;
				$json['isNeedLogin'] = false;
				$json['msg'] = '提交失败';
			}
		}
		else {
			$json['status'] = false;
			$json['isNeedLogin'] = true;
			$json['msg'] = '提交失败';
		}
		
		echo json_encode($json);
	}
	
	//添加售后服务
	private function _addAfterService($afterservice_data) {
		if (empty($afterservice_data)) {
			return false;
		}
		$afterservice_data['create_time'] = date('Y-m-d H:i:s', time());
		$afterservice_data['is_effect'] = 1;


		
		//添加工单
		$base_company = new base_service_company_company();
		$company = $base_company->getCompany($afterservice_data['company_id'], 1, 'company_name,linkman,link_tel,email');
		$this->_aParams['company'] = $company;
		
		$sev = new base_service_report_report();
		$items['type'] = 1;
		$items['way'] = 2;
		$items['order_type'] = '17';
		$items['title'] = '公司PC意见反馈';
		$items['content'] = $afterservice_data['evaluate_content'];
		$items['source_obj_type'] = '2';
		$items['source_obj_id'] = $_COOKIE['accountid'];
		$items['source_obj_name'] = $company['company_name'];
		$items['tel'] = $afterservice_data['evaluate_mobile_phone'];
		$items['email'] = $company['email'];
		$items['create_time'] = time();
		$items['product_type'] = base_lib_BaseUtils::GetBrowser();
		$items['picurl'] = $afterservice_data['evaluate_img_path'];
		$report_id = $sev->addreport($items);



		//添加反馈内容
		$item1['company_id'] = $this->_userid;
		$item1['is_user'] = true;
		$item1['content'] = $afterservice_data['evaluate_content']."【来自PC企业招聘中心】";
		$item1['email'] = $company['email'];
		$item1['phone'] = $company['link_tel'];
		$item1['user_name'] = $company['linkman'];
		$item1['advise_type'] = 2;//3G留言 1描述网站留言
		$item1['ip'] = base_lib_BaseUtils::getIp(0);
		$item1['web_app_name'] = $afterservice_data['web_app_name'];
		$item1['web_user_agent'] = $afterservice_data['web_user_agent'];
		$item1['resolution'] = $afterservice_data['resolution'];
		$item1['img_path'] = $afterservice_data['evaluate_img_path'];
		$item1['url'] = $afterservice_data['url'].'  来源地址:'.base_lib_Constant::COMPANY_URL;
		$service_advise = new base_service_callback_advise();
		$result = $service_advise->addAdvise($item1, array());

		$afterservice_data['workorder_id'] = $report_id;

//		if ($afterservice_data['score_familiarity']) {
//			$base_afterService = new base_service_companyImprove_companyAfterService();
//			$base_afterService->addCompanyAfterService($afterservice_data);
//		}
		
		return $report_id;
	}
	
	//添加系统功能/其他 工单
	private function _addReport($report_data, $feedback_type) {
		if (empty($report_data) || !$feedback_type) {
			return false;
		}
		
		//添加工单
		$base_company = new base_service_company_company();
		$company = $base_company->getCompany($report_data['company_id'], 1, 'company_name,email');
		$this->_aParams['company'] = $company;
		
		$sev = new base_service_report_report();
		$items['type'] = 1;
		$items['way'] = 2;
		$items['order_type'] = '17';
		if ($feedback_type == 2) {
			$items['title'] = '系统功能';
		}
		else {
			$items['title'] = '其他';
		}
		
		$items['content'] = $report_data['evaluate_content'];
		$items['source_obj_type'] = '2';
		$items['source_obj_id'] = $_COOKIE['accountid'];
		$items['source_obj_name'] = $company['company_name'];
		$items['tel'] = $report_data['evaluate_mobile_phone'];
		$items['email'] = $company['email'];
		$items['create_time'] = time();
		$items['product_type'] = base_lib_BaseUtils::GetBrowser();
		$items['picurl'] = $report_data['evaluate_img_path'];
		$ress = $sev->addreport($items);
		
		return $ress;
	}
	
	//添加招聘效果反馈_addFeedBack
	private function _addFeedBack($feedback_data, $jobids_string, $feedback_problem_strings) {
		if (empty($feedback_data) || !$jobids_string || !$feedback_problem_strings) {
			return false;
		}
		$feedback_data['create_time'] = date('Y-m-d H:i:s', time());
		$feedback_data['is_effect'] = 1;
		$feedback_data['feed_back_type'] = 1;
		$job_ids = explode(',', $jobids_string);
		$feedback_problems = explode(',', $feedback_problem_strings);
		
		$base_JobFeedbacke = new base_service_companyImprove_companyJobFeedback();
		$res = $base_JobFeedbacke->addJobFeedBacks($feedback_data, $job_ids, $feedback_problems);
		
		return $res;
	}
	
	public function pagePicture($inPath) {
		define('IS_VIRTUAL_NAME', false);
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$up_type = base_lib_BaseUtils::getStr($pathdata['up_type'], 'string');
		$file = $_FILES['Filedata'];
		
		$verify_data = base_lib_BaseUtils::getStr($pathdata['verify_data'], 'array');
		$serv_askforleave = new base_service_upload_upload();
		$arr = $serv_askforleave->UploadFile($file, $verify_data, $up_type, 'companyAdvise', '/cp/config.xml');
		
		if ($arr['status'] == false) {
			$this->ajax_data_json(ERROR, $arr['msg'], $arr);
		}
		if ($up_type == 'file') {
			$arr['newname_path'] = $arr['name'] . "|" . $arr['newname_path'];
		}
		
		$this->ajax_data_json(SUCCESS, "上传成功", $arr);
	}
	
	/**
	 * 删除临时照片（废弃）
	 * @param array $inPath
	 */
	public function pageDelTempFile($inPath) {
		define('IS_VIRTUAL_NAME', false);
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$up_type = base_lib_BaseUtils::getStr($pathdata['up_type'], 'string');
		$verify_data = base_lib_BaseUtils::getStr($pathdata['verify_data'], 'array');
		$file = $_REQUEST['file_path'];
		$serv_askforleave = new base_service_upload_upload();
		$arr = $serv_askforleave->DelFile($file, $verify_data, $up_type, 'feedback', '/cp/config.xml');
		if (@$arr['status'] == false) {
			$this->ajax_data_json(ERROR, $arr['msg'], $arr);
		}
		
		$this->ajax_data_json(SUCCESS, "删除成功", $arr);
	}
	
	/**
	 * 设置职位为精品职位
	 * @param $inPath
	 */
	public function pageJobQuality($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($pathdata['job_id'], 'int', '');
		$quality_type = base_lib_BaseUtils::getStr($pathdata['quality_type'], 'string', 'add');
		
		if ($quality_type == 'add') {
			$msg = "设置精品职位后，每展示24小时会消耗1个精品点，确定要设置吗？";
		}
		else {
			$msg = "取消精品后职位将变成免费职位，将会降低职位曝光量。确定要取消吗？";
		}
		
		$this->_aParams['msg'] = $msg;
		$this->_aParams['job_id'] = $job_id;
		$this->_aParams['quality_type'] = $quality_type;
		
		return $this->render('jobqualitymsg.html', $this->_aParams);
	}
	
	
	/**
	 *
	 * 成都新套餐刷新
	 */
	public function pageRefresh($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($pathdata['job_id'], 'int', '');
		
		if (empty($job_id)) {
			echo json_encode(['status' => false, 'msg' => "参数错误，请刷新后重试"]);
			
			return;
		}
		$service_refresh_log = new base_service_company_refreshlog();
		$can_free_refresh = $service_refresh_log->isFreeRefreshToday($this->_userid, $job_id);
		
		if (!$can_free_refresh) {
			$msg = '今日免费刷新次数已用完，继续刷新需要消耗1个刷新点，确定要刷新吗？';
		}
		else {
			$msg = '确定刷新？';
		}
		$_data['can_refresh'] = $can_free_refresh;
		$_data['msg'] = $msg;
		$_data['job_id'] = $job_id;
		
		return $this->render('qualityrefresh.html', $_data);
	}
	
	/**
	 * 执行刷新
	 */
	public function pageRefreshDo($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($pathdata['job_id'], 'int', '');
		
		if (empty($job_id)) {
			echo json_encode(['status' => false, 'msg' => "参数错误"]);
			
			return;
		}
		
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		$companyresources = $company_resources->getCompanyServiceSource(['account_resource']);
		//刷新次数
		$service_serviceConsumeLog = new base_service_company_service_serviceConsumeLog();
		$refresh_times  = $service_serviceConsumeLog->getCountByRelevance(3,$job_id,'count(id) refresh_times')[0]['refresh_times'];

		if($refresh_times >= $companyresources['cd_refresh_times_day']){
			echo json_encode(['status' => false, 'msg' => "今天已经刷新".$companyresources['cq_refresh_times_day']."次，明天再来吧！"]);
			return;
		}

		list($status, $code, $params) = $company_resources->consume('refreshV2', ['job_id' => $job_id, 'company_id' => $this->_userid]);
		if (!$status) {
			echo json_encode(['status' => $status, 'code' => $code, 'msg' => $params]);
			
			return;
		}
		
		echo json_encode(['status' => $status, 'code' => $code, 'msg' => $params]);
	}
	
	/**
	 * 设置职位为精品职位
	 * @param $inPath
	 */
	public function pageJobQualityDo($inPath) {
		echo header("Content-type:text/json;charset=utf-8");
		
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($pathdata['job_id'], 'int', '');
		$quality_type = base_lib_BaseUtils::getStr($pathdata['quality_type'], 'string', 'add');
		if (empty($job_id)) {
			return $this->jsonMsg(false, "参数错误!");
		}
		if ($quality_type != 'add' && $quality_type != 'cancel') {
			return $this->jsonMsg(false, "参数错误!");
		}
		
		$service_company_job_quality = new base_service_company_job_quality();
		$service_company_job_job = new base_service_company_job_job();
		
		$items = "job_id,company_id,area_id,jobsort,station,account_id";
		$job_info = $service_company_job_job->getJob($job_id, $items);
		if (empty($job_info)) {
			return $this->jsonMsg(false, "职位不存在");
		}
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$service_company_account = new base_service_company_account();
		$job_account = $service_company_account->getAccount($job_info['account_id'], 'resource_type,user_name');
		
		
		//验证套餐信息
		$company_id = $this->_userid;
		$company_resources = base_service_company_resources_resources::getInstance($company_id, true, $account_id);
		$companyresources = $company_resources->getCompanyServiceSource(['account_resource']);
		
		//登录账号为分配资源模式，并且该职位不为此账号发布的，提示失败
		if ($account_id != $job_info['account_id'] && $companyresources['resource_type'] == 2) {
			return $this->jsonMsg(false, "该职位由账号（{$job_account['user_name']}）发布，请登录该账号操作");
		}
		
		$params = array (
			'company_id' => $company_id,
			'job_id'     => $job_id,
			'account_id' => $account_id
		);
		
		
		if ($quality_type == "add") {
			//设置精品职位
			$add_result = $company_resources->consume('pub_boutique_job', $params);
			if (!$add_result[0]) {
				//精品点不足
				if (base_service_company_resources_code::Pricing_NO_Boutique == $add_result[1]) {
					if ($companyresources['resource_type'] == 2) {
						$msg = "对不起，您的精品点不足。请联系主账号为您分配更多资源";
						
						return $this->jsonMsg(false, $msg, array ('code' => 502, 'data' => $add_result));
					}
					else {
						$msg = $add_result[2];
						
						return $this->jsonMsg(false, $msg, array ('code' => base_service_company_resources_code::Pricing_NO_Boutique, 'data' => $add_result));
					}
					
					
				}
				//扣款失败
				if (base_service_company_resources_code::Pricing_buy_failed == $add_result[1]) {
					return $this->jsonMsg(false, $add_result[2]);
				}
			}
			
			return $this->jsonMsg(true, "设置精品职位成功");
		}
		else {
			//取消精品职位
			$result = $service_company_job_quality->cancelJobQulity($company_id, $job_id);
			if ($result['code'] == ERROR) {
				return $this->jsonMsg(false, $result['msg'], array ('is_stop_job' => $result['is_stop_job']));
			}
			$data = array ();
			if ($quality_type == 'cancel') {
				$data['is_stop_job'] = $result['is_stop_job'];
				if ($result['is_stop_job']) {
					if ($companyresources['resource_type'] == 2) {
						$msg = "取消精品成功，此职位暂时无法展示，请联系主账号为您分配免费职位数。";
					}
					else {
						$msg = "取消精品成功，由于您的免费职位数已用完，此职位暂时无法展示。建议您及时关闭不再招聘的免费职位，再去“职位管理-结束招聘”的职位列表中重新发布此职位。";
					}
				}
				else {
					$msg = "取消精品职位成功";
				}
			}
			
			return $this->jsonMsg(true, $msg, $data);
			
		}
		
	}
	
	/**
	 * 设置职位为精品推广
	 * @param $inPath
	 */
	public function pageSetJobQualitySpread($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($pathdata['job_id'], 'int', '');
		$from = base_lib_BaseUtils::getStr($pathdata['from'], 'string', '');
		//获取企业资源信息
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		$companyresources = $company_resources->getCompanyServiceSource(['account_resource']);
		$point_job_refresh_last = $companyresources['point_job_boutique_last'];
		$params = array (
			'company_id' => $this->_userid,
			'job_id'     => $job_id,
			'account_id' => base_lib_BaseUtils::getCookie("accountid")
		);
		
		$pay_in24_info = $company_resources->check('pub_boutique_job', $params);
		$is_pay_in24 = 0;
		if ($pay_in24_info[0] && $pay_in24_info[1] == base_service_company_resources_code::Pricing_Buied_Boutique_in24) {
			$is_pay_in24 = 1;
		}
		
		$this->_aParams['is_pay_in24'] = $is_pay_in24;
		$this->_aParams['point_job_refresh_last'] = $point_job_refresh_last;
		$this->_aParams['companyresources'] = $companyresources;
		$this->_aParams['job_id'] = $job_id;
		$this->_aParams['from'] = $from;
		
		return $this->render("setjobqualitymsg.html", $this->_aParams);
	}
	
	
	/**
	 * 设置职位为精品推广
	 * @param $inPath
	 */
	public function pageSetJobQualitySpreadDo($inPath) {
		echo header("Content-type:text/json;charset=utf-8");
		
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($pathdata['job_id'], 'int', '');
		$is_set_job_quality = base_lib_BaseUtils::getStr($pathdata['is_set_job_quality'], 'int', 0);
		$from = base_lib_BaseUtils::getStr($pathdata['from'], 'string', '');
		
		if (empty($job_id)) {
			return $this->jsonMsg(false, "参数错误!");
		}
		$service_company_job_job = new base_service_company_job_job();
		$service_company_job_quality = new base_service_company_job_quality();
		$job_info = $service_company_job_job->getJob($job_id, "job_id,account_id");
		if (empty($job_info)) {
			return $this->jsonMsg(false, "职位不存在或已停招");
		}
		
		//获取企业资源信息
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		$companyresources = $company_resources->getCompanyServiceSource();
		$point_job_refresh_last = $companyresources['point_job_boutique_last'];

//		if($point_job_refresh_last < 1){
//			return $this->jsonMsg(false,"剩余精品点不足，请购买");
//		}
		
		
		//非精品职位，必须设置为精品职位
		$job_quality_info = $service_company_job_quality->getJobQulityByJobId($job_id)->items;
		if ($is_set_job_quality != 1 && (empty($job_quality_info) || $job_quality_info['is_quality'] != 1)) {
			return $this->jsonMsg(false, "该职位不是精品职位，请勾选设置为精品职位");
		}
		$success_msg = "设置精品职位成功,即将跳转设置精品推广界面";
		if ($from == 'spreadjob') {
			$success_msg = "设置精品职位成功";
			//判断推广金是否足够
			$spread_last = $companyresources['spread_overage'];
			$service_company_spread_spreadjob = new base_service_company_spread_spreadjob();
			$spread_info = $service_company_spread_spreadjob->getJobSpreadByJobid($company_id, $job_id, "bid");
			if ($spread_last < $spread_info["bid"]) {
				if ($companyresources['resource_type'] == 2) {
					return $this->jsonMsg(false, "剩余推广金不足，请联系主账号分配更多资源");
				}
				else {
					return $this->jsonMsg(false, "剩余推广金不足");
				}
			}
		}
		elseif ($from == 'job_top') {
			//置顶推广时，如果是免费职位，则判断是否能够设置精品职位
			$service_company_account = new base_service_company_account();
			$job_account_info = $service_company_account->getAccount($job_info['account_id'], "account_id,user_name,resource_type");
			if ($job_quality_info['is_quality'] != 1) {
				if ($companyresources['resource_type'] == 2) {
					if ($accountid != $job_info['account_id']) {
						return $this->jsonMsg(false, "该职位由账号（" . $job_account_info['user_name'] . "）发布，请登录该子账号操作");
					}
				}
				else {
					if ($job_account_info['resource_type'] == 2) {
						return $this->jsonMsg(false, "该职位由分配模式账号（" . $job_account_info['user_name'] . "）发布，请登录该子账号操作");
					}
				}
			}
			
			$success_msg = "设置精品职位成功,即将跳转职位置顶界面";
		}
		
		//如果是精品职位，直接跳转到精品推广界面
		if ($job_quality_info['is_quality'] == 1) {
			return $this->jsonMsg(true, $success_msg);
		}
		
		//非精品职位，将职位设置为精品职位
		$params = array (
			'company_id' => $this->_userid,
			'job_id'     => $job_id,
			'from_src'   => $from,
			'account_id' => base_lib_BaseUtils::getCookie("accountid")
		);
		$add_result = $company_resources->consume('pub_boutique_job', $params);
		if (!$add_result[0]) {
			return $this->jsonMsg(false, $add_result[2]);
		}
		
		return $this->jsonMsg(true, $success_msg);
		
	}
	
	
	public function pageGetHRManager($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$from = base_lib_BaseUtils::getStr($pathdata['from'], 'string', '');
		$companyStateService = new base_service_company_comstate();
		$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
		$net_heap_id = $companyState["net_heap_id"];
		if (empty($net_heap_id)) {
			$net_heap_id = 213;
		}
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$service_account = new base_service_company_account();
		$account = $service_account->getAccount($account_id, 'resource_type');
		$hrManager = $this->GetHRManager($net_heap_id);
		//$hrManager['qq'] = '625899211';
		
		$xml = SXML::load('../config/config.xml');
		if (!is_null($xml)) {
			$this->_aParams["huibo400"] = $xml->HuiboPhone400;
		}
		if ($from == 'refresh') {
			if ($account['resource_type'] == 2) {
				$msg = "您的刷新点不足，请联系主账号为您分配更多资源。";
			}
			else {
				$msg = "今日免费刷新次数已用完，你没有足够的刷新点用来刷新职位，请联系工作人员进行购买。";
			}
		}
		elseif ($from == 'buy') {
			if ($account['resource_type'] == 2) {
				$msg = "请联系主账号为您分配更多资源";
			}
			else {
				$msg = '请联系工作人员购买精品点。';
			}
		}
		else {
			if ($account['resource_type'] == 2) {
				$msg = '设置精品职位需要消耗精品点，你没有足够的精品点，请联系主账号为您分配更多资源。';
			}
			else {
				$msg = '设置精品职位需要消耗精品点，你没有足够的精品点，请联系工作人员进行购买。';
			}
		}
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $account_id);
		$companyresources = $company_resources->getCompanyServiceSource(['account_resource']);
		
		$service_company = new base_service_company_company();
		$site_type = $service_company->getCompany($this->_userid, 1, 'site_type')['site_type'];
		if ($site_type == 5) {
			$hrManager['mobile'] = '18523192707';
			$hrManager['qq'] = '2851501221';
			$hrManager['user_name'] = '任慧荣';
		}
		
		
		$this->_aParams['resource_type'] = $account['resource_type'];
		$this->_aParams['msg'] = $msg;
		$this->_aParams['hr_info'] = $hrManager;
		
		return $this->render("hrmanagermsg.html", $this->_aParams);
	}
	
	//已超出简历下载地区限制，请联系工作人员开通相应招聘服务。
	public function pageShowHRManagerMsg($inPath) {
		$companyStateService = new base_service_company_comstate();
		$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
		$net_heap_id = $companyState["net_heap_id"];
		
		$hrManager = $this->GetHRManager($net_heap_id);
		//$hrManager['qq'] = '625899211';
		$xml = SXML::load('../config/config.xml');
		if (!is_null($xml)) {
			$this->_aParams["huibo400"] = $xml->HuiboPhone400;
		}
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, base_lib_BaseUtils::getCookie('accountid'));
		//  $companyresources = $company_resources->getCompanyServiceSource();
		
		$service_company = new base_service_company_company();
		$site_type = $service_company->getCompany($this->_userid, 1, 'site_type')['site_type'];
		if ($site_type == 5) {
			$hrManager['mobile'] = '18523192707';
			$hrManager['qq'] = '2851501221';
			$hrManager['user_name'] = '任慧荣';
		}
		$msg = '已超出简历下载地区限制，请联系工作人员开通相应招聘服务。';
		
		$this->_aParams['msg'] = $msg;
		$this->_aParams['hr_info'] = $hrManager;
		
		return $this->render("common/showhrmanagermsg.html", $this->_aParams);
	}

	/**
	 * 获取企业年报二维码
	 */
	private function getCompanyAnnualReport(){
		$service_company_yearusestatic = new base_service_company_yearusestatic();
		$img_path = $service_company_yearusestatic->getCompanyAnnualReportCode($this->_userid,1);
		return $img_path;
	}
	
	
	//企业评分 2019/3/7
	public function pageMakeGrade($inPath) {
		$detect = new SMobiledetect();
		if ($detect->isMobile()) {
			//header('Location: '.base_lib_Constant::MOBILE_URL.'/companyindex/');
			$url = base_lib_Constant::MOBILE_URL . '/companyindex/MakeGrade';
			echo "<script> window.parent.location.href = '{$url}';</script>";
			exit;
		}
		if (!$this->isLogin() || $this->_usertype != 'c') {
			$this->_aParams['MakeGrade_redirect_url'] = '/login';
		}

		//当前招聘顾问
		$company_id = $this->_userid;
		$base_company = new base_service_company_company();
		$common_gradetype = new base_service_common_gradetype();
		$company = $base_company->getCompany($company_id, 1, 'net_heap_id,com_level,end_time,is_bad,recruit_type,link_tel,link_mobile');

		if ($company['net_heap_id']) {
			$base_netheap = new base_service_company_netcompanyheap();
			$heapinfo = $base_netheap->getHeapInfoByHeapId($company['net_heap_id']);
			$this->_aParams['heapinfo'] = $heapinfo;
		}

		$this->_aParams['grade_a'] = $common_gradetype->getAllOne();
		$this->_aParams['grade_b'] = $common_gradetype->getAllTwo();
		$this->_aParams['grade_c'] = $common_gradetype->getAllThree();

		return $this->render("./companygrade.html", $this->_aParams);

	}
	
	//添加企业评分
	public function pageAddGrade($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		
		$in_data['product_grade'] = base_lib_BaseUtils::getStr($path_data['match_grade_familiarity'], "int", "");
		$in_data['counselor_grade'] = base_lib_BaseUtils::getStr($path_data['match_grade_problem'], "int", "");
		$in_data['apply_grade'] = base_lib_BaseUtils::getStr($path_data['match_grade_service'], "int", "");
		$in_data['suggest'] = base_lib_BaseUtils::getStr($path_data['content'], "string", "");
        $in_data['apply_option'] = base_lib_BaseUtils::getStr($path_data['gradeCheckboxPut03'], "string", "");
        $in_data['counselor_option'] = base_lib_BaseUtils::getStr($path_data['gradeCheckboxPut02'], "string", "");
        $in_data['product_option'] = base_lib_BaseUtils::getStr($path_data['gradeCheckboxPut01'], "string", "");
		$company_id = $this->_userid;




        $in_data['product_grade']>2 and $in_data['product_option'] = '';
        $in_data['counselor_grade']>2 and $in_data['counselor_option'] = '';
        $in_data['apply_grade']>2 and $in_data['apply_option'] = '';

		if($in_data['product_grade']<=2 && empty($in_data['product_option'])){
            $json['status'] = false;
            $json['isNeedLogin'] = false;
            $json['msg'] = '请选择产品功能满意度具体原因';
            echo json_encode($json);
            return;
        }
        if($in_data['counselor_grade']<=2 && empty($in_data['counselor_option'])){
            $json['status'] = false;
            $json['isNeedLogin'] = false;
            $json['msg'] = '请选择招聘顾问服务体验具体原因';
            echo json_encode($json);
            return;
        }
        if($in_data['apply_grade']<=2 && empty($in_data['apply_option'])){
            $json['status'] = false;
            $json['isNeedLogin'] = false;
            $json['msg'] = '请选择招聘效果满意度具体原因';
            echo json_encode($json);
            return;
        }
        if(mb_strlen($in_data['suggest']) > 200){
            $json['status'] = false;
            $json['isNeedLogin'] = false;
            $json['msg'] = '建议不能超过200字';
            echo json_encode($json);
            return;
        }
		//获取最新一条记录
		$service_companyServiceGrade = new base_service_company_service_companyServiceGrade();
		$info = $service_companyServiceGrade->GetCompanyServiceGradeByCompanyId($company_id, 'id,status,grade_time');
		if($info['status'] == 1){
            $service_account = new base_service_company_account();
            $account_name    = $service_account->getAccount($info['account_id'],'user_name')['user_name'];
            $json['status'] = false;
            $json['isNeedLogin'] = false;
            $json['msg'] = "{$account_name}已于{$info['grade_time']}完成评分";
            echo json_encode($json);
            return;
        }
		$in_data['status'] = 1;
		$in_data['grade_time'] = date('Y-m-d H:i:s', time());
		$in_data['account_id'] = base_lib_BaseUtils::getCookie('accountid');
		if ($this->isLogin() && $this->_usertype == 'c') {
			$res = $service_companyServiceGrade->UpdateCompanyServiceGradeDataById($info['id'], $in_data);
			if ($res) {
				//送50推广金
				$spread['company_id'] = $company_id;
				$spread['start_time'] = date('Y-m-d');
				$spread['total'] = 50;
				$spread['origin'] = 3;
				
				$service_spread = new base_service_company_spread_spread();
				$service_spreadhistory = new base_service_company_spread_spreadhistory();
				//获取该单位最后一条有效并未过期的充值推广金记录，如果存在，则取该记录的结束日期为本次返还的日期，否则按当前取15天作为结束时间
				$lastest_spread = $service_spread->getLastestEffectSpread($company_id, "spread_id,end_time");
				if ($lastest_spread !== false && !base_lib_BaseUtils::nullOrEmpty($lastest_spread['end_time'])) {
					$spread['end_time'] = $lastest_spread['end_time'];
				}
				else {
					$spread['end_time'] = date("Y-m-d", strtotime("+15 days", strtotime(date("Y-m-d"))));
				}
				$spread_id = $service_spread->addCompanySpread($spread);
				$com_spreadconsume = new base_service_common_spreadconsume();
				$service_spreadhistory->addHistory($company_id, $spread_id, -50, $com_spreadconsume->company_grade, -1);
				
				$json['status'] = true;
				$json['isNeedLogin'] = false;
				$json['msg'] = '评价成功！';
			}
			else {
				$json['status'] = false;
				$json['isNeedLogin'] = false;
				$json['msg'] = '提交失败';
			}
		}
		else {
			$json['status'] = false;
			$json['isNeedLogin'] = true;
			$json['msg'] = '提交失败';
		}
		echo json_encode($json);
	}

	public function pageRecruitStatus($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$service_company_account = new base_service_company_account();
		
		$account = $service_company_account->getAccount($account_id, 'is_main,resource_type');
		if ($account['is_main'] != 1) {
			$this->_aParams['not_main'] = '该账号非主账号，无权限查看招聘状态';
			
			return $this->render("./recruitstatus.html", $this->_aParams);
		}
		$accounts = $service_company_account->getAccountCompany($this->_userid, 'account_id,user_name,station');
		$account_ids = base_lib_BaseUtils::getProperty($accounts, 'account_id');
		$accounts = base_lib_BaseUtils::array_key_assoc($accounts, 'account_id');
		
		$service_appraise = new base_service_person_appraise_appraise();
		$accountrelate_service = new base_service_replayrate_account();
		$appraise = $service_appraise->getAccountScore($account_ids, $this->_userid);
		$appraise = base_lib_BaseUtils::array_key_assoc($appraise, 'account_id');
		
		//企业
		$relate_service = new base_service_replayrate_company();
		$companyreversion_rate = $relate_service->getLastCompanyRelayrate($this->_userid);
		$company_appraise = $service_appraise->getCompanyScore($this->_userid);
		$companyreversion_rate['appraise'] = $company_appraise['population_avg_level'];
		$companyreversion_rate['avg_match_level'] = $company_appraise['avg_match_level'];
		$companyreversion_rate['avg_welfare_salary_level'] = $company_appraise['avg_welfare_salary_level'];
		$reversion_rate = array ();
        $account_reversion_rates = $accountrelate_service->getLastAccountRelayrateV2($account_ids, $this->_userid);
//        $account_reversion_rates = base_lib_BaseUtils::array_key_assoc($account_reversion_rates, 'account_id');
		foreach ($accounts as $k => $v) {
			$account_reversion_rates[$k]['appraise'] = $appraise[ $v['account_id'] ]['population_avg_level'];
			$account_reversion_rates[$k]['avg_match_level'] = $appraise[ $v['account_id'] ]['avg_match_level'];
			$account_reversion_rates[$k]['avg_welfare_salary_level'] = $appraise[ $v['account_id'] ]['avg_welfare_salary_level'];
			if ($companyreversion_rate['reply_rate'] > $account_reversion_rates[$k]['reply_rate']) {
				$account_reversion_rates[$k]['lower_reply_rate'] = 1;
			}
			else {
				$account_reversion_rates[$k]['lower_reply_rate'] = 0;
			}
			
			if (($companyreversion_rate['avg_time'] < $account_reversion_rates[$k]['avg_time']
					&& $companyreversion_rate['day_hour'] == $account_reversion_rates[$k]['day_hour'])
				|| ($companyreversion_rate['day_hour'] == '小时' && $account_reversion_rates[$k]['day_hour'] == '天')
			) {
				$account_reversion_rates[$k]['lower_avg_time'] = 1;
			}
			else {
				$account_reversion_rates[$k]['lower_avg_time'] = 0;
			}
			if ($company_appraise['population_avg_level'] > $account_reversion_rates[$k]['appraise'] && !empty($account_reversion_rates[$k]['appraise'])) {
				$account_reversion_rates[$k]['lower_appraise'] = 1;
			}
			else {
				$account_reversion_rates[$k]['lower_appraise'] = 0;
			}
            
            if ($company_appraise['avg_match_level'] > $account_reversion_rates[$k]['avg_match_level'] && !empty($account_reversion_rates[$k]['avg_match_level'])) {
				$account_reversion_rates[$k]['lower_avg_match_level'] = 1;
			}
			else {
				$account_reversion_rates[$k]['lower_avg_match_level'] = 0;
			}
            if ($company_appraise['avg_welfare_salary_level'] > $account_reversion_rates[$k]['avg_welfare_salary_level'] && !empty($account_reversion_rates[$k]['avg_welfare_salary_level'])) {
				$account_reversion_rates[$k]['lower_avg_welfare_salary_level'] = 1;
			}
			else {
				$account_reversion_rates[$k]['lower_avg_welfare_salary_level'] = 0;
			}
			array_push($reversion_rate, ['account_id' => $v['account_id'], 'user_name' => $v['user_name'], 'station' => $v['station'], 'data' => $account_reversion_rates[$k]]);
		}

		$this->_aParams['list'] = $reversion_rate;
		$this->_aParams['company'] = $companyreversion_rate;
		
		return $this->render("./recruitstatus.html", $this->_aParams);
		
		
	}

	/**
	 * 获取自动推荐简历的职位
	 */
	private function getAutoRecommendJob(){
		$apply_resume_num = 5;
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, base_lib_BaseUtils::getCookie('accountid'));
		$account_id = $company_resources->all_accounts;

		$job_status = new base_service_common_jobstatus();

		//获取所有在招职位
		$status = $job_status->pub;
		$service_company_job_job = new base_service_company_job_job();
		$field = 'job_id,job_flag,station,issue_time,check_state,end_time,status,create_time,mod_time,re_apply_type,company_id,account_id,agency_state';
		$job_list = $service_company_job_job->getJobList($account_id, '', $status, $field, 0, 0, null, null);
		$job_ids = base_lib_BaseUtils::getPropertys($job_list,"job_id");

		//获取一周内新发布职位
		$week_time = date("Y-m-d",strtotime("-1 week"));
		$create_week_job = array();
		$default_job_id = '';
		$last_pub_job = 0;
		$last_mod_time = "";
		foreach($job_list as $key => $value){
			if($value['create_time'] >= $week_time){
				array_push($create_week_job,$value['job_id']);
			}
			if($value['mod_time'] > $last_mod_time){
				$last_pub_job = $value['job_id'];
				$last_mod_time = $value['mod_time'];
			}

		}

		$service_company_resume_apply = new base_service_company_resume_apply();
		$job_apply_result = $service_company_resume_apply->getJobApplyCount($create_week_job);

		$week_job_num = count($create_week_job);
		if(empty($job_apply_result)){
			//若无符合条件职位，默认最近发布/修改的职位
			$default_job_id = $last_pub_job;
		}else{
			//将投递职位大于X份的简历抛出
			foreach($job_apply_result as $key =>$value){
				if($value['applycount'] >= $apply_resume_num){
					foreach($create_week_job as $k => $val){
						if($val == $value['jobid']){
							unset($create_week_job[$k]);
						}
					}
				}
			}

			if(empty($create_week_job)){
				if($week_job_num > 0){
					$default_job_id = "";
				}else{
					$default_job_id = $last_pub_job;
				}

			}else{
				$job_list = base_lib_BaseUtils::array_key_assoc($job_list,"job_id");
				$last_create_time = "";
				$last_pub_job = "";
				foreach($create_week_job as $key => $value){
					if($job_list[$value]['create_time'] > $last_create_time){
						$last_pub_job = $value;
						$last_create_time = $job_list[$value]['create_time'];
					}
				}
				$default_job_id = $last_pub_job;
			}
		}

//		$data = array(
//			'job_list'          => $job_list,
//			'default_job_id'    => $default_job_id
//		);
		return $default_job_id;
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
	 * h5模板埋点统计
	 * 1: 发布职位H5点击量,
	 *                                                          2: 职位管理H5点击量
	 *                                                          3: 首页H5点击量（投递数据入口）
	 *                                                          4: 首页H5点击量（固定入口）
	 *                                                          5: 扫码分享企业数
	 *                                                          6: 链接分享企业数
	 *                                                          7: 编辑H5企业数',
	 * @param $inPath
	 */
	public function pageTemplateStatisticalByH5($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

		$type = base_lib_BaseUtils::getStr($path_data['type'], "int", "1");
		$service_company_mrecruit_companylog = new base_service_company_mrecruit_companylog();
		$service_company_mrecruit_companylog->addLog(base_lib_BaseUtils::getIp(),$this->_userid,$type);
		$this->redirect_url("/mrecruit/");
	}

}

?>

<?php

/**
 * 获取广告配置文件的配置类
 */
class AdConfig {
	
	private $xml = null;
	
	function __construct($xmlPath) {
		$this->xml = SXML::load($xmlPath);
	}
	
	function getAdPath() {
		$path = "";
		if ($this->xml == null) {
			return $path;
		}
		//$domain = base_lib_Constant::UPLOAD_FILE_URL;
		$root = $this->xml->VirtualName;
		$imgPath = $this->xml->AdFolder;
		$path = $root . "/" . $imgPath;
		
		return $path;
	}
	
	function getCallingAdDir() {
		$dir = "";
		if ($this->xml == null) {
			return $dir;
		}
		$dir = "/" . $this->xml->callingAdFolder;
		
		return $dir;
	}
	
	function getCallingPath() {
		$path = $this->getAdPath() . $this->getCallingAdDir();
		
		return $path;
	}



	
	
}

?>
