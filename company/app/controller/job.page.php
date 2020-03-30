<?php

/**
 * @copyright 2002-2013 www.huibo.com
 * @name 职位管理
 * @author    fuzy
 * @version   2013-7-1 上午11:20:56
 */
class controller_job extends components_cbasepage {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * 二维数组根据某值排序
	 * Enter description  here ...
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
		} else {
			arsort($keysvalue);
		}
		reset($keysvalue);
		foreach ($keysvalue as $k => $v) {
			$new_array[ $k ] = $arr[ $k ];
		}
		
		return $new_array;
	}
	
	/**
	 * @desc 停止招聘
	 */
	public function pageStopJob($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', 0);
		
		$job = new base_service_company_job_job();
		$job_status = new base_service_common_jobstatus();
		
		$result = $job->setJobStatus($this->_userid, $job_id, $job_status->stop_use);
		if ($result === false) {
			echo json_encode(array ('error' => '停止招聘失败'));
			
			return;
		}
		$service_job = new base_service_company_job_job();
		$job_info = $service_job->getJob($job_id, 'job_id,station');
		//---------添加操作日志--------
		$common_oper_type = new base_service_common_account_accountoperatetype();
		$service_oper_log = new base_service_company_companyaccountlog();
		$common_oper_src_type = new base_service_common_account_accountlogfrom();
		$insertItems = array (
			"company_id"   => $this->_userid,
			"source"       => $common_oper_src_type->website,
			"account_id"   => base_lib_BaseUtils::getCookie('accountid'),
			"operate_type" => $common_oper_type->job_stop,
			"content"      => "关闭职位，职位：" . $job_info['station'],
			"create_time"  => date("Y-m-d H:i:s", time())
		);
		$service_oper_log->addLogToMongo($insertItems);
		//-------------END------------
		
		
		echo json_encode(array ('success' => true));
	}
	
	/**
	 * 修改职位成功后跳转推广页面
	 * @param  array $inPath url参数集
	 * @return html          html页面
	 */
	public function pageSpread($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', 0);
		$type = base_lib_BaseUtils::getStr($path_data['type'], 'string', 'add');
		$this->_aParams['step'] = $path_data['step'];
		$this->_aParams['type'] = $type;
		
		$job_service = new base_service_company_job_job();
		$job = $job_service->getJob($job_id, "job_id,company_id,station,jobsort,jobsort_ids,check_state");
		
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,base_lib_BaseUtils::getCookie('accountid'));
		if (!in_array($job['company_id'], $company_resources->all_accounts))
			return false;
        $service_related        = base_service_hractivity_related::getInstance();
        $related_account_list   = $service_related->getRelatedByCompany($this->_userid,"account_id");
        $related_account_ids    = base_lib_BaseUtils::getProperty($related_account_list, "account_id");
        $this->_aParams["related_account_ids"] = $related_account_ids;
        $this->_aParams["account_id"] = base_lib_BaseUtils::getCookie('accountid');
		list($top_status, $top_code, $top_params) = $company_resources->check($func = "top", $params = ["job_id" => $job_id]);
		if (!$top_status || $top_params['is_already']) {
			if ($type == "add")
				$this->redirect(base_lib_Constant::COMPANY_URL_NO_HTTP . "/job/addsuccess/station-" . $job['station']);
			else
				$this->redirect(base_lib_Constant::COMPANY_URL_NO_HTTP . "/index/joblist/");
		}
		
		list($urg_status, $urg_code, $urg_params) = $company_resources->check($func = "urgent", $params = ["job_id" => $job_id]);
		if (!$urg_status || $urg_params['is_already']) {
			if ($type == "add")
				$this->redirect(base_lib_Constant::COMPANY_URL_NO_HTTP . "/job/addsuccess/station-" . $job['station']);
			else
				$this->redirect(base_lib_Constant::COMPANY_URL_NO_HTTP . "/index/joblist/");
		}
		
		$common_jobsort = new base_service_common_jobsort();
		
		$companyService = new base_service_company_company();
		$company = $companyService->getCompany($this->_userid, 1, 'company_id,company_name,is_audit,audit_state,company_flag,resume_download_upperlimit,recruit_type,com_level,start_time,end_time,hr_manager,hr_manager_sex,hr_tel,linkman,calling_id');
		// 是否是会员或者会员是否到期
		$isEnd = false;
		$endTime = strtotime(date('Y-m-d 23:59:59', strtotime($company["end_time"])));
		if ($company["com_level"] < 1 || $endTime < time()) {
			$isEnd = true;
		}
		$this->_aParams["isEnd"] = $isEnd;

		$this->_aParams['job_id'] = $job_id;
		$this->_aParams['station'] = $job['station'];
		$this->_aParams['jobsorts'] = $common_jobsort->getSelfAndParentJobsort($job['jobsort']);
		$this->_aParams['urg_params'] = $urg_params;
		$this->_aParams['top_params'] = $top_params;
		$this->_aParams['title'] = "汇博人才网_职位发布";
		$this->_aParams["companyInfor"] = $company;
		
		//置顶职位
		$service_jobtop = new base_service_company_job_jobtop();
//		$topJob_All = $service_jobtop->getListByCompanyid($service_id=0, $this->_userid, 'id,keyword,end_time,jobsort');
//		$sameSortJobNumber = 0;
//		if(!empty($topJob_All)){
//			foreach($topJob_All as $topval){
//				$tempParentJobSort = $common_jobsort->getParentJobsort($topval['jobsort']);
//				if($tempParentJobSort == $this->_aParams['jobsorts'][1]['jobsort']){
//					$sameSortJobNumber++;
//				}
//			}
//		}
		$sameSortJobNumber = $service_jobtop->getTopNumberByJobsort($this->_aParams['jobsorts'][1]['jobsort'], $company['calling_id']);
		$this->_aParams['sameSortJobNumber'] = $sameSortJobNumber;

        //企业认证信息
        $this->_aParams['audit_msg']    = $company_resources->CompanyAuditStatus();
        //企业会员状态
        $this->_aParams['com_level']    = $company["com_level"];
        //职位审核状态
        $this->_aParams['check_state']  = $job['check_state'];

		//绑定精准推广数据
		$this->__BindJobQuality($job_id);

		return $this->render("job/addspread.html", $this->_aParams);
	}

	/**
	 * 设置精准推广
	 */
	private function __BindJobQuality($job_id){

		$status = 0;
		$server_spread_job = new base_service_company_spread_spreadjob();
		$server_company_job = new base_service_company_job_job();

		$item = "spread_id,job_id,company_id,status,create_time,is_effect,bid,budget,last_budget,quality_score,sort_score,jobsort,end_time";
		//获取公司职位

		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);

		$account_ids = $company_resources->all_accounts;
		$companyresources = $company_resources->getCompanyServiceSource(['account_resource']);
		$this->_aParams['count'] = $companyresources['spread_overage'];
		$this->_aParams['companyresources'] = $companyresources;


		//获取推广职位
		$spread_job_lists = $server_spread_job->getJobSpreads($account_ids,'',$item)->items;
		$job_ids = base_lib_BaseUtils::getProperty($spread_job_lists,'job_id');
		$spread_key_job = base_lib_BaseUtils::array_key_assoc($spread_job_lists,'job_id');

		//获取该公司在招职位
		if($companyresources['resource_type'] == 2){
			$company_jobs       = $server_company_job->getJobList($account_ids,'',1,'company_id,job_id,jobsort_id,jobsort,station,end_time,account_id',0,0,null,$accountid);
		}else{
			$company_jobs       = $server_company_job->getJobList($account_ids,'',1,'company_id,job_id,jobsort_id,jobsort,station,end_time,account_id');
		}
		$jobsorts           = base_lib_BaseUtils::getProperty($company_jobs, "jobsort");
		$jobsort_avg_list   = $server_spread_job->getCompanyBidAvgV2($jobsorts);
		$spread_jobsort_avg = base_lib_BaseUtils::array_key_assoc($jobsort_avg_list, "jobsort");

		$company_job_list = base_lib_BaseUtils::array_key_assoc($company_jobs,"job_id");

		$is_spread_jobs = array();
		$is_effect_jobs = array();
		$not_spread_jobs = array();
		//成都市场判断，如果是成都的，跳到成都职位列表

		$click_service = new base_service_company_spread_click();
		if($spread_job_lists && $company_jobs) {
			$job_ids = base_lib_BaseUtils::getProperty($spread_job_lists,'job_id');
			$job_ids = array_unique($job_ids);
			//获取实际消耗的金额
			$sumPrice = $click_service->getSpreadJobUsePriceById($job_ids);
			$sumPrice = base_lib_BaseUtils::array_key_assoc($sumPrice,'job_id');
			foreach ($company_jobs as $key => $val) {
				if(!in_array($val['job_id'],$job_ids)){
					$temp = array(
						'job_id' => $val['job_id'],
						'company_id' => $val['company_id'],
						'status' => 0,
						'station' => $val['station'],
						'bid' => '',
						'is_effect' => 1,
						'budget' => 0,
						'last_budget' => 0,
						'account_id'	=> $val['account_id'],
						'jobsort'	=> $val['jobsort']
					);
					array_push($not_spread_jobs, $temp);
					continue;
				}
				$banking = '';
				if($spread_key_job[$val['job_id']]['status'] == 1){
					$banking = $server_spread_job->getCompanyBidRankingByJobsort($spread_key_job[$val['job_id']]['spread_id'],$spread_key_job[$val['job_id']]['jobsort'],$spread_key_job[$val['job_id']]['sort_score']);
				}

				//$avgBid = $server_spread_job->getCompanyBidAvg($val['jobsort']);
				$_job_jobsort = $val["jobsort"];
				$_jobsort_avg = !empty($spread_jobsort_avg[$_job_jobsort]) ? (string)sprintf("%.2f",$spread_jobsort_avg[$_job_jobsort]["bidavg"]) : $spread_key_job[$val['job_id']]['bid'];
				$last_budget = (float)(empty($sumPrice[$val['job_id']]['price_sum']) ? 0 : $sumPrice[$val['job_id']]['price_sum']);
				$last_budget = $last_budget > 0 ? sprintf("%.2f",$last_budget) : 0;
				$temp = array(
					'spread_id'	=>	$spread_key_job[$val['job_id']]['spread_id'],
					'job_id' => $val['job_id'],
					'company_id' => $val['company_id'],
					'status' => $spread_key_job[$val['job_id']]['status'],
					'ranking' => $banking,
					'jobsort'	=> $spread_key_job[$val['job_id']]['jobsort'],
					'station' => $val['station'],
					'bid' => $spread_key_job[$val['job_id']]['bid'],
					'avgbid' => $_jobsort_avg,
					'is_effect' => 1,
					'budget' => $spread_key_job[$val['job_id']]['budget'],
					'last_budget' => $last_budget,
					'account_id'	=> $val['account_id'],
					'end_time' => empty($spread_key_job[$val['job_id']]['end_time'])? '' : date('Y-m-d H:i',strtotime($spread_key_job[$val['job_id']]['end_time'])),
				);
				if ($spread_key_job[$val['job_id']]['status'] == 1) {
					array_push($is_spread_jobs, $temp);
				}else{
					array_push($is_effect_jobs,$temp);
				}
			}


		}elseif(empty($spread_job_lists) && $company_jobs){
			foreach ($company_jobs as $key => $val) {
				$temp = array(
					'job_id' => $val['job_id'],
					'company_id' => $val['company_id'],
					'status' => 0,
					'station' => $val['station'],
					'bid' => '',
					'is_effect' => 1,
					'account_id'	=> $val['account_id'],
					'budget' => 0,
					'last_budget' => 0,
					'jobsort' => $val['jobsort'],
				);
				array_push($not_spread_jobs, $temp);
			}

		}

		if($status == 1){
			$job_spread_list = $is_spread_jobs;
		}elseif($status == 2){
			$job_spread_list = array_merge($is_effect_jobs,$not_spread_jobs);
		}else{
			$job_spread_list = empty($spread_job_lists) ? $not_spread_jobs : array_merge($is_spread_jobs,$is_effect_jobs,$not_spread_jobs);
		}

		$spread_job_lists = base_lib_BaseUtils::array_key_assoc($spread_job_lists,'job_id');
		//1.获取所有的job_id
		$pub_job_id = base_lib_BaseUtils::getProperty($job_spread_list,"job_id");

		//2.再获取职位的精品信息
		$service_company_job_quality = new base_service_company_job_quality();
		$job_quality_list = $service_company_job_quality->getJobQulityByJobId($pub_job_id)->items;
		$job_quality_list = base_lib_BaseUtils::array_key_assoc($job_quality_list,"job_id");



		$service_company_account = new base_service_company_account();
		$company_account_info = $service_company_account->getAccountList($this->_userid, 'account_id,user_id,user_name,head_photo,station,resource_type')->items;
		$assoc_accounts       = base_lib_BaseUtils::array_key_assoc($company_account_info, 'account_id');


		$a_list = $service_company_account->getAccountByAccount_ids(base_lib_BaseUtils::getProperty($job_spread_list, 'account_id'), 'account_id,user_id,user_name,head_photo,station');
		$a_list = base_lib_BaseUtils::array_key_assoc($a_list->items, 'account_id');

		$fields = 'account_id,company_id,is_main,user_id,user_name,mobile_phone,link_tel,station,head_photo,last_login_time,state,resource_type';
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$account = $service_company_account->getAccount($accountid, $fields);

		$companyService = new base_service_company_company();
		$company = $companyService->getCompany($this->_userid, 1, 'company_id,company_name,is_audit,audit_state,company_flag,resume_download_upperlimit,com_level,start_time,end_time,hr_manager,hr_manager_sex,hr_tel,linkman');

		//获取职位信息
		foreach($job_spread_list as $k=>$item){
			if($companyresources['resource_type'] == 2 && $accountid != $company_job_list[$item['job_id']]['account_id']){
				unset($job_spread_list[$k]);
				continue;
			}
			if($item['job_id'] != $job_id){
				unset($job_spread_list[$k]);
				continue;
			}
			$tempIsEffect = empty($spread_job_lists[$item['job_id']]['is_effect']) ? 0 : $spread_job_lists[$item['job_id']]['is_effect'];
			$job_spread_list[$k]['is_effect'] = $tempIsEffect;
			$job_spread_list[$k]['bid'] = $item['bid'];
			$job_spread_list[$k]['budget'] = $item['budget'];
			$job_spread_list[$k]['last_budget'] = $item['last_budget'];
			$job_spread_list[$k]['end_time'] = $item['end_time'];

			$job_spread_list[ $k ]['can_do']   =  ($assoc_accounts[$item['account_id']]['resource_type']==1 && $account['resource_type'] == 1) || ($accountid == $item['account_id']) ? 1 : 2;  //只有共享模式的帐号可以相互操作功能
			//招聘人
			$job_spread_list[ $k ]['job_account_resource_type']   =  $assoc_accounts[$item['account_id']]['resource_type'];
			$job_spread_list[ $k ]['account_user_name'] = $a_list[ $item['account_id'] ]['user_name'] ? $a_list[ $item['account_id'] ]['user_name'] : $company['linkman'];//这里就用账户名

			//是否精品职位
			$is_quality = false;
			if($job_quality_list[$item['job_id']]['is_quality'] == 1 && $companyresources['isNewService']){
				$is_quality = true;
			}
			$job_spread_list[$k]['is_quality'] = $is_quality;

			//根据职位类别去拿首屏展示最低价
			$first_screen_bid=$server_spread_job->getFirstScreenBidByJobSort($job_spread_list[$k]["jobsort"],$this->_userid);
			$job_spread_list[ $k ]['first_screen_bid']=$first_screen_bid;
		}
		$job_spread_list = base_lib_BaseUtils::array_key_assoc($job_spread_list,"job_id");

		$this->_aParams['job_spread_list'] = $job_spread_list;
	}
	
	/**
	 * [pageTypeArea 地区会员发布职位地区限制]
	 * @param  array $inPath
	 * @return html  job/areaerror.html
	 */
	public function pageTypeArea($inPath) {
		$xml = SXML::load('../config/config.xml');
		$tel_head = "023-61627888";
		if (!is_null($xml)) {
			$tel_head = $xml->TechniquePhone;
		}
		
		$this->_aParams['tel_head'] = $tel_head;
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
			$this->_aParams["hrManager"] = $hrManager;
		}
		
		return $this->render("job/areaerror.html", $this->_aParams);
	}
	
	/**
	 * 删除职位
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	public function pageDelete($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', 0);
		
		$job = new base_service_company_job_job();
		$company_id = $job->getJob($job_id, "company_id")['company_id'];
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid);
		
		if (in_array($company_id, $company_resources->all_accounts)) {
			$result = $job->deleteJob($company_id, $job_id);
		} else {
			$result = false;
		}
		
		if ($result === false) {
			echo json_encode(array ('error' => '删除职位失败'));
			
			return;
		}
		echo json_encode(array ('success' => true));
	}
	
	/**
	 * 新增职位入口
	 * @param $inPath 参数说明
	 */
	public function pageAdd($inPath) {
		if (!$this->canDo("job_add")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$this->_aParams['title'] = '发布职位';
		$this->_aParams['step'] = $pathdata['step'];
		$service_company = new base_service_company_company();
		$company_info = $service_company->getCompany($this->_userid, 1, 'company_shortname,company_bright_spot,map_x,map_y,'
		                                                           . 'property_id,calling_ids,size_id,info,linkman,link_tel,area_id,address,email,is_audit,audit_state,recruit_type,link_mobile,com_level,site_type');
		
		/** =============== 判断公司会员情况 part:start=================== **/
        $account_id = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$account_id);
		$member_info = $company_resources->isMember() ? "member" : "not_member";
		$this->_aParams['member_info'] = $member_info;
		//是否是校招企业
		
		if ($company_resources->account_type == "NotMemberTypSchool" && !base_lib_BaseUtils::getCookie("bossuser")) {
			/** 2018/6/6 13:34
			 * 目前被标为校招的待启会员，模拟登录可发布职位，但企业自行登录后提示需要开通服务才能发布职位。
			 * 需要删除此逻辑，使校招待启会员企业使用账号密码登录后能够发布职位。  */
//			return $this->_companyNotAudit(1, "service");
		}
		/** =============== 判断公司会员情况 part:end=================== **/
		
		/** =============== 资料不完整页面 part:start=================== **/
		$require_arr = [];
		$audit_arr = [];
		//同行认证的不能发布职位和修改职位  //省外市场改版   除了重庆主城的同行认证，其他地区的同行认证可以发布和修改本地区的职位
		//获取审核表中的企业简称
		//公司简称审核不通过时才提示
		$service_shortname = new base_service_company_shortnameaudit();
		$companys_j = $service_shortname->getCompanyByCompanyIdAndStatus($this->_userid, "audit_id,company_id,company_shortname");
		$company_audit_list = $companys_j->items;
		
		$company_location_area = $company_resources->location_area;
		$this->_aParams["company_location_area"] = $company_location_area;
		//对应资料修改中的必填项
		$this->_aParams['company_resources'] = $company_resources;
		if ($company_resources->location_area != "NOT_CQ_CITY") {
			if (base_lib_BaseUtils::nullOrEmpty($company_info['company_shortname']) && !empty($company_audit_list)) {
				array_push($require_arr, array ("name" => '公司简称', "val" => 'shortname'));
			}
		}
		if (base_lib_BaseUtils::nullOrEmpty($company_info['property_id'])) {
			array_push($require_arr, array ("name" => '公司性质', "val" => 'pro'));
		}
		if (base_lib_BaseUtils::nullOrEmpty($company_info['calling_ids'])) {
			array_push($require_arr, array ("name" => '所处行业', "val" => 'cal'));
		}
		if (base_lib_BaseUtils::nullOrEmpty($company_info['size_id'])) {
			array_push($require_arr, array ("name" => '公司规模', "val" => 'size'));
		}
		if ($company_resources->location_area != "NOT_CQ_CITY") {
			if (base_lib_BaseUtils::nullOrEmpty($company_info['company_bright_spot'])) {
				array_push($require_arr, array ("name" => '一句话描述公司', "val" => 'company_bright_spot'));
			}
		}
		if (base_lib_BaseUtils::nullOrEmpty($company_info['info'])) {
			array_push($require_arr, array ("name" => '公司简介', "val" => 'info'));
		}
		if (base_lib_BaseUtils::nullOrEmpty($company_info['linkman'])) {
			array_push($require_arr, array ("name" => '联系人', "val" => 'linkm'));
		}
		if (base_lib_BaseUtils::nullOrEmpty($company_info['link_tel']) && base_lib_BaseUtils::nullOrEmpty($company_info['link_mobile'])) {
			array_push($require_arr, array ("name" => '联系方式', "val" => 'linkt'));
		}
		if (base_lib_BaseUtils::nullOrEmpty($company_info['area_id'])) {
			array_push($require_arr, array ("name" => '所在地区', "val" => 'area'));
		}
		if (base_lib_BaseUtils::nullOrEmpty($company_info['address'])) {
			array_push($require_arr, array ("name" => '详细地址', "val" => 'address'));
		}
		
		$auditStateService = new base_service_company_licenceauditstate();
		if ($company_info['is_audit'] == $auditStateService->notval && $company_info['audit_state'] != '4') {
			array_push($audit_arr, array ("name" => '营业执照验证', "val" => "audit"));
		}
		/** =============== 资料不完整页面 part:start=================== **/
      
		if (!empty($require_arr)) {
			return $this->notMemberJobAdd($require_arr, $audit_arr);
		}
		
//		list($is_audit, $audit_params) = $company_resources->getCompanyAuditStatus();
//		if ($is_audit == 5 && $company_location_area != "NOT_CQ_CITY") { //同行认证
//			return $this->_companyNotAudit();
//		}
		$this->_aParams['can_pub_no_salary'] = ($company_resources->account_type == "NotMemberTypSchool" && base_lib_BaseUtils::getCookie("bossuser"));//新需求，后台模拟登录的校招企业，发布校招职位可以不填薪资
		if ($company_info["is_audit"] == $auditStateService->notval) {
			$this->redirect_url('/register/add');
		}
		/** =============== 还能发布的职位数量 part:start=================== **/
		$company_resource_info = $company_resources->getCompanyServiceSource();
		$default_job_num = (int)$company_resource_info["default_job_num"];
		$current_job_num = (int)$company_resource_info["has_pub_job_num"];
        if($company_resource_info['isCqNewService']){
            $default_job_num = (int)$company_resource_info['cq_job_num'];
            $current_job_num = (int)$company_resource_info["has_pub_job_free_num"];
            $service_servicePricing = new base_service_company_service_servicePricing();
            $selling = $service_servicePricing->GetFunParallelismSelling('point_job_refresh');
            $this->_aParams['can_cash']          = ($company_resource_info['spread_overage'] + $company_resource_info['account_overage']) >= $selling ? true:false; //总余额
//            $this->_aParams['can_cash'] = 10;
//            $company_resource_info['cq_release_point_job_refresh'] = 0;
            if($company_resource_info['cq_release_point_job_refresh']>0){
                $this->_aParams['can_cash']      =  false;
                $this->_aParams['can_point_refresh'] =  true;
            }else{
                $this->_aParams['can_point_refresh'] = false;
            }
            $this->_aParams['selling']         = $selling;
            $this->_aParams['is_show_refresh'] = true;
            $this->_aParams['isCqNewService']  = true;
        }
        //贾思云四川新套餐逻辑
        $this->_aParams['default_count']  = $default_job_num;
        $this->_aParams['is_next_job']    = ($default_job_num > $current_job_num + 1) ? true : false;
        $this->_aParams['job_count_left'] = $default_job_num - $current_job_num;

        if($company_resource_info['isNewService']===true){
            $current_job_num = (int)$company_resource_info["has_pub_job_free_num"];
            $this->_aParams['job_count_left'] = $company_resource_info['job_boutique_release']+$company_resource_info['job_release_num'];
            $this->_aParams['job_count_left_free'] = $company_resource_info['job_release_num'];
            //$this->_aParams['job_count_left_free'] = 2;
            $this->_aParams['default_count']  = $company_resource_info['pricing_free_job_num']+$company_resource_info['pricing_job_boutique'];
            $this->_aParams['is_next_job']    = ($this->_aParams['default_count'] > $current_job_num+$company_resource_info['job_qulity_num']+ 1) ? true : false;
            $this->_aParams['point_job_boutique_left'] = $company_resource_info['point_job_boutique'] - $company_resource_info['point_job_boutique_use'];
          //  $this->_aParams['point_job_boutique_left'] = 2;
            $this->_aParams['job_boutique_release'] = $company_resource_info['job_boutique_release'];
           // $this->_aParams['job_boutique_release'] = 5;
           // $current_job_num = $company_resource_info['has_pub_job_free_num'] +$company_resource_info['job_qulity_num'];
        }
        $this->_aParams['isNewService'] = $company_resource_info['isNewService'];
        //当月餐饮免费会员发布职位限制
        $base_service_solr_job = new base_service_solr_job();
        $job_list = $base_service_solr_job->GetCompanyAlreadyPublishJobByCompanyId($this->_userid,date('Y-m-01',time()),10);
		if (($current_job_num + 1 >  $this->_aParams['default_count']) && ($company_info['site_type']!=4 || $company_info['com_level']!=1)) {
			return $this->_jobPubFail($company_resource_info['isNewService'],$this->_aParams['default_count']);
		}else if($company_info['site_type']==4 && count($job_list)>=5 && $company_info['com_level']==1){
            return $this->_jobPubFail($company_resource_info['isNewService'],'');
        }
		/*==============承诺职位开始==========================*/
		$last_banned_day = 0;//解禁剩余天数
		$last_mustreply_job = 0;//剩余承诺职位数
		$mustreply_job_count = 0;//总承诺职位数
		$mustreply_job_status = $this->canAddMustReplyJob(0, $last_banned_day, $last_mustreply_job, $mustreply_job_count);
		
		$this->_aParams['last_banned_day'] = $last_banned_day;
		$this->_aParams['last_mustreply_job'] = $last_mustreply_job;
		$this->_aParams['mustreply_job_count'] = $mustreply_job_count;
		$this->_aParams['mustreply_job_status'] = $mustreply_job_status;
		/*==============承诺职位结束===========================*/
		
		// 是否为代招的职位
		$this->_aParams['account_type'] = $company_resources->account_type;
		$this->_aParams['generation_binding'] = $company_resources->account_type == 'hr_main' || in_array($company_info['recruit_type'], [2, 4, 9]) ? true : false;
		
		// 代招的企业
		$accounts = $service_company->getCompanys($company_resources->all_accounts, "company_id,company_name,company_shortname,company_flag,is_audit");
		$accounts_json[] = ["id" => "0", "name" => "请选择"];
		foreach ($accounts as $key => $account) {
			$this->_aParams['accounts'][ $account['company_id'] ] = $account;
			
			$account['company_name_display'] = $account['company_shortname'] ? $account['company_shortname'] : $account['company_name'];
			$_hr_type = $account['company_id'] == $this->_userid ? "（直招）" : "（代招）";
			
			$accounts_json[] = ['id' => $account['company_id'], "name" => $account['company_name_display'] . $_hr_type];
		}
		$this->_aParams['accounts_json'] = json_encode($accounts_json);
		
		// 地区非会员特殊逻辑
		// 行业非会员特殊逻辑
		//同行认证的的地区非会员 非同行地区非会员 省外市场的同行认证 职位发布当前公司地区的职位
		$job_area_promise = false;
		if ($company_resources->account_type == "NotMemberTypeArea" || ($company_resources->location_area != "CQ_MAIN_CITY" && $company_info["com_level"] <= 1)) {
			$job_area_promise = true;
		}
		$this->_aParams["job_area_promise"] = $job_area_promise;
		/** =============== 获取职位枚举json part:start=================== **/
		
		//职位性质
		$service_job_type = new base_service_common_jobtype();
		$job_type_arr = $service_job_type->getJobtype();
		$this->_aParams['job_type_arr'] = $job_type_arr;
		
		//绑定岗位级别
		$service_job_level = new base_service_common_joblevel();
		$job_level_arr = $service_job_level->getJobLevel();
		foreach ($job_level_arr as $item => $value) {
			$job_level_json[] = ['id' => $item, "name" => $value];
		}
		
		$this->_aParams['job_level_json'] = json_encode($job_level_json);
		
		//绑定薪资
		$service_salary = new base_service_common_salaryaddjob();
		$salary_arr = $service_salary->getAll();
		foreach ($salary_arr as $item => $value) {
			$salary_json[] = ['id' => $item, "name" => $value];
		}
        
		$this->_aParams['salary_json'] = json_encode($salary_json);
		
		//绑定试用期
		$service_probation_period = new base_service_common_probationperiod();
		$probation_period_arr = $service_probation_period->getProbationPeriod();
		$probation_period_json[] = ["id" => 0, "name" => "请选择"];
		foreach ($probation_period_arr as $item => $value) {
			$probation_period_json[] = ["id" => $item, "name" => $value];
		}

		$this->_aParams['probation_period_json'] = json_encode($probation_period_json);;
		
		//绑定福利
		$service_reward = new base_service_common_reward();
		$all_reward_data = $service_reward->getAll();
		$this->_aParams["all_reward_data"] = $all_reward_data;
		
		//获得公司已有的福利
		$service_company = new base_service_company_company();
		$company_rewards = $service_company->getCompany($this->_userid, '1', 'company_reward_ids,company_other_reward');
		
		//公司默认福利
		$company_default_rewards = $company_rewards['company_reward_ids'];
		$this->_aParams["hidDefaultReward"] = $company_default_rewards;
		$company_default_rewards_arr = empty($company_default_rewards) ? array () : explode(',', $company_default_rewards);
		
		//公司其他福利
		$company_other_rewards = $company_rewards['company_other_reward'];
		$this->_aParams["hidOtherReward"] = $company_other_rewards;
		$company_other_rewards_arr = empty($company_other_rewards) ? array () : explode(',', $company_other_rewards);
		
		$this->_aParams['company_other_rewards'] = $company_other_rewards_arr;
		$this->_aParams['company_default_rewards'] = $company_default_rewards_arr;
		
		//绑定工作年限
		$service_workyear = new base_service_common_workyear();
		$workyear_arr = $service_workyear->getAll();
		$workyear_json[] = ["id" => 'all', "name" => "不限"];
		foreach ($workyear_arr as $item => $value) {
			$workyear_json[] = ["id" => $item, "name" => $value];
		}
		$this->_aParams['workyear_json'] = json_encode($workyear_json);
		
		//绑定学历
		$service_degree = new base_service_common_degree();
		$degree_arr = $service_degree->getAll();
		$degree_json[] = ["id" => 'all', "name" => "不限"];
		foreach ($degree_arr as $item => $value) {
			$degree_json[] = ["id" => $item, "name" => $value];
		}
		$this->_aParams['degree_json'] = json_encode($degree_json);

		//绑定双薪资
		$more_salary_json[] = ["id" => '0', "name" => "请选择"];
		for ($i = 13; $i <= 20 ; $i++) {
			$more_salary_json[] = ["id" => $i, "name" => "{$i}薪"];
		}
		$this->_aParams['more_salary_json'] = json_encode($more_salary_json);

		//绑定性别要求
		$service_sex = new base_service_common_sex();
		$sex_arr = $service_sex->getSex();
		$sex_json[] = ["id" => 0, "name" => "不限"];
		foreach ($sex_arr as $item => $value) {
			$sex_json[] = ["id" => $item, "name" => $value];
		}
		$this->_aParams['sex_json'] = json_encode($sex_json);
		
		//绑定年龄
		$age_json[] = ["id" => 0, "name" => "不限"];
		for ($i = 16; $i <= 60; $i++) {
			$age_json[] = ["id" => $i, "name" => $i];
		}
		$this->_aParams['age_json'] = json_encode($age_json);

		//绑定语言
		$service_language = new base_service_common_languagetype();
		$language_arr = $service_language->getAll();
		$language_json[] = ["id" => 0, "name" => "不限"];
		foreach ($language_arr as $item => $value) {
			$language_json[] = ["id" => $item, "name" => $value];
		}
		$this->_aParams['language_json'] = json_encode($language_json);
		
		/** =============== 获取职位枚举json part:end=================== **/
		
		
		/** =============== 还能发布的职位数量 part:end=================== **/
		$this->_aParams['linkman'] = $company_info['linkman'];
		$this->_aParams['link_tel'] = $company_info['link_tel'];
		$this->_aParams['company_area'] = $company_info['area_id'];
		$this->_aParams['company_address'] = $company_info['address'];
		$this->_aParams['email'] = $company_info['email'];
		$this->_aParams['map_x'] = $company_info['map_x'] ? $company_info['map_x'] : '106.55297';
		$this->_aParams['map_y'] = $company_info['map_y'] ? $company_info['map_y'] : '29.565621';
		
		//获取没有下级的二级地区
		$service_common_area = new base_service_common_area();
		$no_child_areas = $service_common_area->getSecondLevelNoChildAreas();
		$no_child_area_ids = base_lib_BaseUtils::getProperty($no_child_areas, "area_id");
		$this->_aParams['no_child_area_ids'] = json_encode($no_child_area_ids);
		
		//公司子账户信息
		$service_company_account = new base_service_company_account();
		$company_account_info = $service_company_account->getAccountList($this->_userid, 'account_id,is_main,user_id,user_name,mobile_phone,link_tel,resource_type')->items;
        
        //企业绑定列表
        $service_related        = base_service_hractivity_related::getInstance();
        $related_account_list   = $service_related->getRelatedByCompany($this->_userid,"account_id");
        $related_account_ids    = base_lib_BaseUtils::getProperty($related_account_list, "account_id");
        $this->_aParams["related_account_ids"] = $related_account_ids;
		$this->_aParams['company_account_info'] = base_lib_BaseUtils::array_key_assoc($company_account_info, 'account_id');
		//新职位发布默认为当前登录账户
        $this->_aParams["cur_is_main"] = $this->_aParams['company_account_info'][$account_id]['is_main'];

		$this->_aParams['job']['account_id'] = $account_id;
		
        $this->_aParams["chat_seed"] = uniqid();
        $this->_aParams["resource_type"] = $company_resource_info['resource_type'];
        //当月餐饮免费会员发布职位限制
        $base_service_solr_job = new base_service_solr_job();
        $jobList = $base_service_solr_job->GetCompanyAlreadyPublishJobByCompanyId($this->_userid,date('Y-m-01',time()),10);
        if ($company_info['com_level']==1 && $company_info['site_type']==4){
            $this->_aParams["job_count_left"] = count($jobList)>=5 ? 0:5-count($jobList);
        }

        //判断企业是否在
        $service_audit_jobcompany = new base_service_company_auditjobcompany();
        $audit_jobcompany      = $service_audit_jobcompany->auditJobCompanyInfo($this->_userid,"id,status_type,company_id");
        $this->_aParams["audit_jobcompany"] = $audit_jobcompany;

		//判断单位是否为预警单位
		$service_company_companyrisk = new base_service_company_companyrisk();
		$company_risk = $service_company_companyrisk->checkCompanyWarning($this->_userid);
		$this->_aParams["company_risk"] = $company_risk;


        $json_account_info = [];
        foreach ($company_account_info as $_accountinfo){
            if( $this->_aParams["cur_is_main"] == 1 && $_accountinfo['resource_type']==1){
                $json_account_info[] = ['id'=>$_accountinfo['account_id'],'name'=>$_accountinfo['user_name'],'phone'=>$_accountinfo['mobile_phone']];
            }else{
                if($_accountinfo['account_id'] == $this->_aParams['job']['account_id']){
                    $json_account_info[] = ['id'=>$_accountinfo['account_id'],'name'=>$_accountinfo['user_name'],'phone'=>$_accountinfo['mobile_phone']];
                }
            }
        }
        $this->_aParams['json_account_info'] = json_encode($json_account_info);
               
        //判断该账号登录过APP没有
        $is_login_app   = true;
        $relate_phone   = "";
        $service_related = base_service_hractivity_related::getInstance();
        $related_info    = $service_related->getRelatedByAccount($account_id, "person_id");
       
        if(!empty($related_info)){
            $service_appkey = new base_service_company_app_appkey();
            $appkey         = $service_appkey->getLastAppkeyV2($related_info["person_id"]);
            if(empty($appkey)){
                $is_login_app   = false;
                $service_person = new base_service_person_person();
                $person_info    = $service_person->getPerson($related_info["person_id"],"mobile_phone");
                if(!empty($person_info["mobile_phone"])){
                    $relate_phone = $person_info["mobile_phone"];
                    $relate_phone = preg_replace('#(\d{3})[a-z0-9-]{2,10}(\d{3})#', '${1}****${2}', $relate_phone);
                }
            }
        }
        $this->_aParams["is_login_app"]     = $is_login_app;
        $this->_aParams["relate_phone"]     = $relate_phone;

		return $this->render('job/add.html', $this->_aParams);
	}
	
	/**
	 * @desc ajax 获取账号是否管理企业
	 *
	 */
	public function pageGetRelateInfo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$account_id = base_lib_BaseUtils::getStr($path_data['account_id'], 'int', 0);
		if (empty($account_id)) {
			echo $this->jsonMsg(false, "没有账号编号");
			
			return;
		}
        $service_account = new base_service_company_account();
        $account_info    = $service_account->getAccount($account_id,"account_id,mobile_phone");
        $has_mobile      = !empty($account_info["mobile_phone"]) ? 1 : 0; 
		$service_related = new base_service_hractivity_related();
		$related_info = $service_related->getRelatedByAccount($account_id, "account_id");
		if (empty($related_info)) {
			echo $this->jsonMsg(false, "没有关联企业APP",["mobile_phone"=>$account_info["mobile_phone"],"has_mobile" => $has_mobile]);
			
			return;
		}
       
		echo $this->jsonMsg(true, "已经关联企业APP",["mobile_phone"=>$account_info["mobile_phone"],"has_mobile" => $has_mobile]);
		
		return;
	}
	
	/**
	 * 获取HR会员企业的公司福利etc
	 * @param  array $inPath url参数集
	 * @return json          数据
	 */
	public function pageGetHrComInfo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$company_id = base_lib_BaseUtils::getStr($path_data['company_id'], 'int', 0);
		
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid);
		if (!in_array($company_id, $company_resources->all_accounts))
			return;
		
		$service_company = new base_service_company_company();
		$current_company = $service_company->getCompany($company_id, 1, "area_id,address,company_reward_ids,company_other_reward");
		
		$service_reward = new base_service_common_reward();
		$all_reward_data = $service_reward->getAll();
		
		//公司默认福利
		$company_default_rewards = $current_company['company_reward_ids'];
		$company_default_rewards_arr = empty($company_default_rewards) ? array () : explode(',', $company_default_rewards);
		foreach ($all_reward_data as $reward) {
			if (in_array($reward['reward_id'], $company_default_rewards_arr))
				$company_reward_names[] = $reward['reward_name'];
		}
		
		//公司其他福利
		$company_other_rewards = $current_company['company_other_reward'];
		$company_other_rewards_arr = empty($company_other_rewards) ? array () : explode(',', $company_other_rewards);
		
		$json["area_id"] = $current_company['area_id'];
		$json["address"] = $current_company['address'];
		$json["hidDefaultReward"] = $company_default_rewards;
		$json["hidOtherReward"] = $company_other_rewards;
		$json['company_other_rewards'] = $company_other_rewards_arr;
		$json['company_default_rewards'] = $company_default_rewards_arr;
		$json['company_reward_names'] = implode(",", $company_reward_names);
		
		echo json_encode($json);
		exit;
	}
	
	/**
	 * 职位发布成功
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	public function pageAddSuccess($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$station = base_lib_BaseUtils::getStr($path_data['station'], 'string', '');
		$this->_aParams['step'] = $path_data['step'];
		$service_company = new base_service_company_company();
		
		/** =============== 判断公司会员及职位发布情况 part:start=================== **/
		$this->_aParams['member_info'] = $this->getCompanyMemberInfo();
		$this->_aParams['is_can_sue_job'] = $this->_isCanSueJob($this->_userid);
		/** =============== 判断公司会员及职位发布情况 part:end=================== **/
		
		if ($this->_aParams['member_info'] != 'member') {
			return $this->notMemberJobAddSuccess($station);
		}
		
		$company = $service_company->getCompany($this->_userid, null, 'com_level,start_time,end_time,is_audit,audit_state,linkman,link_tel');
		if (empty($company)) {
			return;
		}
		
		$auditStateService = new base_service_company_licenceauditstate();
		$this->_aParams['is_audit'] = ($company['is_audit'] == $auditStateService->pass || $company['audit_state'] == 4) ? true : false;
		$this->_aParams['linkman'] = $company['linkman'];
		$this->_aParams['linktel'] = $company['link_tel'];
		$this->_aParams['title'] = '职位发布成功';
		$this->_aParams['station'] = $station;
		$this->_aParams["company"] = $company;
		
		/** =============== 获取招聘顾问 part:start=================== **/
		$domain = $this->GetDomainInfor();
		$companyStateService = new base_service_company_comstate();
		$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
		
		$this->_aParams["hasHRManager"] = false;
		if (!empty($companyState)) {
			$hrManager = $this->GetHRManager($companyState["net_heap_id"]);
			if (!is_null($hrManager)) {
				$this->_aParams["hasHRManager"] = true;
				$headPhoto = base_lib_BaseUtils::nullOrEmpty($hrManager["head_photo_url"]) ? $domain["defaultPhoto"] : $hrManager["head_photo_url"];
				$hrManager["head_photo_url"] = $domain["image"] . "/" . $domain["photo"] . "/" . $headPhoto;
				$this->_aParams["hrManager"] = $hrManager;
			}
		}
		/** =============== 获取招聘顾问 part:end=================== **/
		
		/**==============判断该职位是否是承诺职位 part:start======================**/
		$ismustreply = base_lib_BaseUtils::getStr($path_data['ismustreply'], 'int', 0);
		if ($ismustreply) {
			$appid = 'wx1961770740d354ee'; //测试二维码 传到线上时改为正式二维码：wx1961770740d354ee
			$openweixinservice = new SOpenWeiXin($appid);
			$twodimensioncodeurl = $openweixinservice->generateTwoDimensionCode($this->_userid);
			$this->_aParams['ismustreply'] = $ismustreply;
			$this->_aParams['twodimensioncodeurl'] = $twodimensioncodeurl;
		}
		
		/**==============判断该职位是否是承诺职位 part:end======================**/
		return $this->render('job/addsuccess.html', $this->_aParams);
	}
	
	/**
	 * 修改职位入口
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	public function pageMod($inPath) {
		if (!$this->canDo("job_edit")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}

		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', 0);
		$mod_type = base_lib_BaseUtils::getStr($path_data['mod_type'], 'string', '');
        $this->_aParams['mod_type'] = $mod_type;
		$this->_aParams['step'] = $path_data['step'];
		
		$service_company = base_service_company_company::getInstances();
		$current_company = $service_company->getCompany($this->_userid, 1, 'company_shortname,company_bright_spot,map_x,map_y,'
		                                                              . 'property_id,calling_ids,size_id,info,linkman,link_tel,area_id,address,email,is_audit,audit_state,recruit_type,link_mobile,com_level,site_type');
		
		if (empty($current_company)) {
			return;
		}
		
		/** =============== 判断公司会员情况 part:start=================== **/
		$account_id = base_lib_BaseUtils::getCookie('accountid');
        $this->_aParams['account_id'] = $account_id;
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$account_id);
		$member_info = $company_resources->isMember() ? "member" : "not_member";
		$this->_aParams['member_info'] = $member_info;
		
		//是否是校招企业
		if ($company_resources->account_type == "NotMemberTypSchool" && !base_lib_BaseUtils::getCookie("bossuser")) {
			/** 2018/6/6 13:34
			 * 目前被标为校招的待启会员，模拟登录可发布职位，但企业自行登录后提示需要开通服务才能发布职位。
			 * 需要删除此逻辑，使校招待启会员企业使用账号密码登录后能够发布职位。  */
//			return $this->_companyNotAudit(2, "service");
		}
		/** =============== 判断公司会员情况 part:end=================== **/
		
		/** =============== 资料不完整页面 part:start=================== **/
		$require_arr = [];
		$audit_arr = [];
		
		//对应资料修改中的必填项
		$this->_aParams['company_resources'] = $company_resources;
		$company_location_area = $company_resources->location_area;
		//同行认证的不能发布职位和修改职位
		//公司简称审核不通过时才提示
		$service_shortname = new base_service_company_shortnameaudit();
		$companys_j = $service_shortname->getCompanyByCompanyIdAndStatus($this->_userid, "audit_id,company_id,company_shortname");
		$company_audit_list = $companys_j->items;
		
		list($is_audit, $audit_params) = $company_resources->getCompanyAuditStatus();
		if ($company_resources->location_area != "NOT_CQ_CITY") {
			if (base_lib_BaseUtils::nullOrEmpty($current_company['company_shortname']) && !empty($company_audit_list)) {
				array_push($require_arr, array ("name" => '公司简称', "val" => 'shortname'));
			}
		}
		if (base_lib_BaseUtils::nullOrEmpty($current_company['property_id'])) {
			array_push($require_arr, array ("name" => '公司性质', "val" => 'pro'));
		}
		if (base_lib_BaseUtils::nullOrEmpty($current_company['calling_ids'])) {
			array_push($require_arr, array ("name" => '所处行业', "val" => 'cal'));
		}
		if (base_lib_BaseUtils::nullOrEmpty($current_company['size_id'])) {
			array_push($require_arr, array ("name" => '公司规模', "val" => 'size'));
		}
		
		if ($company_location_area != "NOT_CQ_CITY") { //非重庆企业 发布职位时可以不限制一句话描述公司
			if (base_lib_BaseUtils::nullOrEmpty($current_company['company_bright_spot'])) {
				array_push($require_arr, array ("name" => '一句话描述公司', "val" => 'company_bright_spot'));
			}
		}
		
		if (base_lib_BaseUtils::nullOrEmpty($current_company['info'])) {
			array_push($require_arr, array ("name" => '公司简介', "val" => 'info'));
		}
		if (base_lib_BaseUtils::nullOrEmpty($current_company['linkman'])) {
			array_push($require_arr, array ("name" => '联系人', "val" => 'linkm'));
		}
		
		if (base_lib_BaseUtils::nullOrEmpty($current_company['link_tel']) && base_lib_BaseUtils::nullOrEmpty($current_company['link_mobile'])) {
			array_push($require_arr, array ("name" => '联系方式', "val" => 'linkt'));
		}
		
		if (base_lib_BaseUtils::nullOrEmpty($current_company['area_id'])) {
			array_push($require_arr, array ("name" => '所在地区', "val" => 'area'));
		}
		if (base_lib_BaseUtils::nullOrEmpty($current_company['address'])) {
			array_push($require_arr, array ("name" => '详细地址', "val" => 'address'));
		}
		
		$auditStateService = new base_service_company_licenceauditstate();
		if ($current_company['is_audit'] == $auditStateService->notval && $current_company['audit_state'] != '4') {
			array_push($audit_arr, array ("name" => '营业执照验证', "val" => "audit"));
		}
		
		/** =============== 资料不完整页面 part:start=================== **/
		if (!empty($require_arr)) {
			return $this->notMemberJobAdd($require_arr);
		}
		
		$this->_aParams['title'] = '修改职位';
		
		if ($job_id === 0) {
			return;
		}
		
//		if ($is_audit == 5 && $company_location_area != "NOT_CQ_CITY") { //同行认证 //省外市场改版后 非重庆的同行认证可以发布和修改职位
//			return $this->_companyNotAudit(2);
//		}
		
		$resources = $company_resources->getCompanyServiceSource(['default_job_num','has_pub_job_num','pricing_resource',"cq_pricing_resource",'spread_overage','account_overage']);
		$plus = $mod_type == "edit" ? 0 : 1;
		//企业默认职位数量
        //添加贾思云四川新套餐逻辑
        if($resources['isNewService']){
            $resources['default_job_num'] = $resources['pricing_free_job_num'] + $resources['pricing_job_boutique'];
            $resources['has_pub_job_num'] = $resources['has_pub_job_free_num'] + $resources['job_qulity_num'];

            $this->_aParams['job_count_left'] = $resources['job_boutique_release']+$resources['job_release_num'];
            $this->_aParams['job_count_left_free'] = $resources['job_release_num'];
           // $this->_aParams['job_count_left_free'] = 0;
            $this->_aParams['point_job_boutique_left'] = $resources['point_job_boutique'] - $resources['point_job_boutique_use'];
              //$this->_aParams['point_job_boutique_left'] = 1;
            $this->_aParams['job_boutique_release'] = $resources['job_boutique_release'];
            $this->_aParams['isNewService'] = $resources['isNewService'];
            // $this->_aParams['job_boutique_release'] = 1;
        }
        if($resources['isCqNewService']){
            $this->_aParams['isCqNewService'] = true;
            $resources['has_pub_job_num'] = $resources['has_pub_job_free_num'];
            $service_servicePricing = new base_service_company_service_servicePricing();
            $selling = $service_servicePricing->GetFunParallelismSelling('point_job_refresh');
            $this->_aParams['default_count'] = $resources['cq_job_num'];
            $resources['default_job_num']    = $resources['cq_job_num'];

            $this->_aParams['can_cash']          = ($resources['spread_overage'] + $resources['account_overage']) >= $selling ? true:false; //总余额
            //$resources['cq_release_point_job_refresh'] = 0;
            if($resources['cq_release_point_job_refresh']>0){
                $this->_aParams['can_cash']      =  false;
                $this->_aParams['can_point_refresh'] =  true;
            }else{
                $this->_aParams['can_point_refresh'] = false;
            }
            $this->_aParams['selling']               = $selling;
            $service_serviceConsumeLog = new base_service_company_service_serviceConsumeLog();
            $refresh_times  = $service_serviceConsumeLog->getCountByRelevance(3,$job_id,'count(id) refresh_times')[0]['refresh_times'];
            $this->_aParams['is_show_refresh'] = true;
            if($refresh_times >= $resources['cq_refresh_times_day']){
                $this->_aParams['is_show_refresh']  = false;
            }
        }

        //是否发布过
        $service_qualite = new base_service_company_job_quality();
        $need_pay        = $service_qualite->isNeedPayIn24($this->_userid, $job_id);
        $this->_aParams['is_pay'] = $need_pay?false:true;
        $this->_aParams['show_new_service'] = $mod_type == "edit" ? false :true;
		$service_job = new base_service_company_job_job();
		$current_job = $service_job->getJob($job_id, 'job_id,job_type,station,quantity,add_info,status,company_id,'
		                                           . 'is_salary_show,period,period_fee,other_reward_ids,content,valid_days,end_time,other_need,'
		                                           . 'allow_graduate,open_linkway,new_linkway,send_email,job_level,jobsort_ids,area_id,min_salary,'
		                                           . 'max_salary,work_year_id,degree_id,sex,age_lower,age_upper,language_id,allow_email,other_email,'
		                                           . 'open_linkway,self_linkway,linkman,link_tel,linkman2,link_tel2,linkman3,link_tel3,base_min_salary,map_x,map_y,address_id,'
		                                           . 'base_max_salary,salary_type,other_reward,dept_name,job_feature_ids,job_other_feature,job_flag,auto_filter,re_apply_type,'
                                                    . 'account_id,allow_online_talk,graduate_profession_ids,graduate_profession_remark,check_state,salary_month_num');


        $job_account_id = $current_job['account_id'];
        $service_company_account = new base_service_company_account();
        $cur_account = $service_company_account->getAccount($account_id,'resource_type');
        if($mod_type == 'edit'){
            $job_account = $service_company_account->getAccount($job_account_id,'resource_type,user_name');
            if ($cur_account['resource_type'] == 2 && $job_account_id != $account_id) {
                $this->_aParams["msg"] = "该职位由账号（{$job_account['user_name']}）发布，请登录该账号操作";
                $this->_aParams["url"] = base_lib_Constant::COMPANY_URL_NO_HTTP.'/index/CdJobList';

                return $this->render("./common/showmsgpopedom.html", $this->_aParams);
            }
            if ($job_account_id != $account_id && $job_account['resource_type'] == 2) {
                $this->_aParams["msg"] = "该职位由分配模式子账号（{$job_account['user_name']}）发布，请登录该子账号操作";
                $this->_aParams["url"] = base_lib_Constant::COMPANY_URL_NO_HTTP.'/index/CdJobList';

                return $this->render("./common/showmsgpopedom.html", $this->_aParams);
            }
        }
        $this->_aParams['default_count'] = $resources['default_job_num'];

        //当月餐饮免费会员发布职位限制
        $base_service_solr_job = new base_service_solr_job();
        $job_list = $base_service_solr_job->GetCompanyAlreadyPublishJobByCompanyId($this->_userid,date('Y-m-01',time()),10);

        if ($resources['has_pub_job_num'] + $plus > $resources['default_job_num'] && $plus  && ($current_company['site_type']!=4 || $current_company['com_level']!=1)) {
            return $this->_jobPubFail($resources['isNewService'],$this->_aParams['default_count']);
        }else if($current_company['site_type']==4 && count($job_list)>=5 && $current_company['com_level']==1){
            return $this->_jobPubFail($resources['isNewService'],'');
        }

		if (empty($current_job) || !in_array($current_job['company_id'], $company_resources->all_accounts)) {
			return;
		}
		if (!$current_job['map_x'] || !$current_job['map_y'] && $current_company['map_x'] && $current_company['map_y']) {
			$current_job['map_x'] = $current_company['map_x'];
			$current_job['map_y'] = $current_company['map_y'];
		} else if ((!$current_job['map_x'] || !$current_job['map_y']) && (!$current_company['map_x'] || !$current_company['map_y'])) {
			$current_job['map_x'] = '106.55297';
			$current_job['map_y'] = '29.565621';
		}
		//新需求，后台模拟登录的校招企业，发布校招职位可以不填薪资
		$this->_aParams['can_pub_no_salary'] = ($company_resources->account_type == "NotMemberTypSchool" && base_lib_BaseUtils::getCookie("bossuser"));
		
		if ($this->_aParams['can_pub_no_salary']) {
			$current_job["base_min_salary"] = $current_job["base_min_salary"] ? $current_job["base_min_salary"] : "";
			$current_job["base_max_salary"] = $current_job["base_max_salary"] ? $current_job["base_max_salary"] : "";
			$current_job["min_salary"] = $current_job["min_salary"] ? $current_job["min_salary"] : "";
			$current_job["max_salary"] = $current_job["max_salary"] ? $current_job["max_salary"] : "";
		}
		
		/**判断坐标地址是否为空 ***/
		$current_job['map_x'] = !empty($current_job['map_x']) ? $current_job['map_x'] : "106.55297";
		$current_job['map_y'] = !empty($current_job['map_y']) ? $current_job['map_y'] : "29.565621";
		$current_job["address_id"] = is_numeric($current_job["address_id"]) ? $current_job["address_id"] : -1;
		
		// 是否为代招的职位
		$this->_aParams['generation_binding'] = $company_resources->account_type == 'hr_main' ? true : false;
		// 代招的企业
		$accounts = $service_company->getCompanys($company_resources->all_accounts, "company_id,company_name,company_shortname,company_flag");
		foreach ($accounts as $key => $account) {
			$account['company_name_display'] = $account['company_shortname'] ? $account['company_shortname'] : $account['company_name'];
			$this->_aParams['accounts'][ $account['company_id'] ] = $account;
		}
		
		// 地区非会员特殊逻辑
		if ($company_resources->account_type == "NotMemberTypeArea" && substr($current_job['area_id'], 0, 4) != $current_company['area_id'])
			$current_job['area_id'] = $current_company['area_id'];
		
		$this->_aParams['account_type'] = $company_resources->account_type;
		
		//判断当前职位是否是重新发布 还是修改
		$today = date("Y-m-d");
		if (strtotime($current_job['end_time']) < strtotime($today) || $current_job['status'] == 0) {
			$current_job['re_apply_type'] = 0; //如果是
		}
		
		$member_info = $this->getCompanyMemberInfo();
		$this->_aParams['member_info'] = $member_info;
		//企业是否自动过滤
		$this->_aParams['is_automatic'] = $current_job['auto_filter'];

		//绑定职位类别
		$jobsort_ids = $current_job['jobsort_ids'];
		$jobsort_arr = array ();

		//职位类别下的重复职位
		$jobsort_job_num = 0;
		
		if (!base_lib_BaseUtils::nullOrEmpty($jobsort_ids)) {
			$jobsort_arr = explode(',', $jobsort_ids);
			//2015.8.14 职位类别包含旧职位类别的数据清理 start
			$service_jobsort = new base_service_common_jobsort();
			$jobsorts = $service_jobsort->getAllJobsort();
			foreach ($jobsort_arr as $k => $v) {
				if (empty($jobsorts[ $v ])) {
					unset($jobsort_arr[ $k ]);
				}
			}
			// 数据清理 end
			if (count($jobsort_arr) > 2) {
				$jobsort_arr = array_slice($jobsort_arr, 0, 2);
			}
			foreach($jobsort_arr as $key => $value){
				$job_num = $service_job->getJobsortJobNumber($this->_userid,$account_id,$value,$job_id);
				$jobsort_job_num += $job_num;
			}

			$jobSorts = $service_jobsort->getJobsorts($jobsort_arr);
		}
		
		$this->_aParams["jobsorts"] = $jobSorts;
		$this->_aParams["jobsort_job_num"] = $jobsort_job_num;
		$this->_aParams["jobsort_arr"] = $jobsort_arr;
		//薪酬查询
		if (!empty($jobsort_arr[0])) {
			$service_average_salary = new base_service_person_averagesalary();
			$average_salary_job = $service_average_salary->getSalaryByJobsortId($jobsort_arr[0], 'jobsort_name,job_min_salary,job_max_salary,person_salary,resume_num,job_num');
			$this->_aParams["jobsalary"] = $average_salary_job;
		}
		
		$service_jobsort = new base_service_common_jobsort();
		$jobsort_names = array ();
		
		if (!base_lib_BaseUtils::nullOrEmpty($jobsort_arr[0])) {
			$jobsort_names[0] = $service_jobsort->getJobsortName($jobsort_arr[0]);
		}
		
		if (!base_lib_BaseUtils::nullOrEmpty($jobsort_arr[1])) {
			$jobsort_names[1] = $service_jobsort->getJobsortName($jobsort_arr[1]);
		}
		
		$this->_aParams["jobsort_names"] = $jobsort_names;
		
		// 福利
		$service_reward = new base_service_common_reward();
		$all_reward_data = $service_reward->getAll();
		$this->_aParams["all_reward_data"] = $all_reward_data;
		
		//获得职位所有福利
		$default_reward = $current_job['other_reward_ids'];
		
		//获得职位其他福利
		$other_reward = $current_job['other_reward'];
		
		$this->_aParams['default_reward'] = $default_reward;
		$this->_aParams['other_reward'] = $other_reward;
		
		if (!base_lib_BaseUtils::nullOrEmpty($default_reward)) {
			$default_reward_arr = explode(',', $default_reward);
		} else {
			$default_reward_arr = array ();
		}
		
		if (!base_lib_BaseUtils::nullOrEmpty($other_reward)) {
			$other_reward_arr = explode(',', $other_reward);
		} else {
			$other_reward_arr = array ();
		}
		
		$this->_aParams['other_reward_arr'] = $other_reward_arr;
		$this->_aParams['default_reward_arr'] = $default_reward_arr;
		
		//获得岗位特点数据
		$service_fea = new base_service_common_feature();
		$all_fea_data = $service_fea->getAll();
		$this->_aParams["all_fea_data"] = $all_fea_data;
		
		//获得职位所有岗位特点
		$default_fea = $current_job['job_feature_ids'];
		
		//获得职位其他岗位特点
		$other_fea = $current_job['job_other_feature'];
		$this->_aParams['default_fea'] = $default_fea;
		$this->_aParams['other_fea'] = $other_fea;
		
		if (!base_lib_BaseUtils::nullOrEmpty($default_fea)) {
			$default_fea_arr = explode(',', $default_fea);
		} else {
			$default_fea_arr = array ();
		}
		
		if (!base_lib_BaseUtils::nullOrEmpty($other_fea)) {
			$other_fea_arr = explode(',', $other_fea);
		} else {
			$other_fea_arr = array ();
		}
		
		$this->_aParams['other_fea_arr'] = $other_fea_arr;
		$this->_aParams['default_fea_arr'] = $default_fea_arr;
		
		//获得部门
		$this->_aParams['dept_name'] = $current_job['dept_name'];
		
		//绑定部门名称
		$service_dept = new base_service_company_dept();
		$dept_info = $service_dept->getAdeptInfo($this->_userid, 'dept_id,dept_name');
		$dept_json = array ();
		if (count($dept_info) > 0) {
			foreach ($dept_info as $dept) {
				$dept_json[] = array ('id' => $dept['dept_name'], 'name' => $dept['dept_name']);
			}
		}
		
		$this->_aParams['dept_json'] = json_encode($dept_json);
		$this->_aParams['job'] = $current_job;
		$this->_aParams['job_flag'] = $current_job['job_flag'];
		
		$valid_days = (strtotime(date('Y-m-d', strtotime($current_job['end_time']))) - strtotime(date('Y-m-d', time()))) / 86400;
		$this->_aParams['job']['valid_days'] = $valid_days < 1 ? '' : $valid_days;
		
		//职位性质
		$service_job_type = new base_service_common_jobtype();
		$job_type_arr = $service_job_type->getJobtype();
		$this->_aParams['job_type_arr'] = $job_type_arr;
		
		//绑定岗位级别
		$service_job_level = new base_service_common_joblevel();
		$job_level_arr = $service_job_level->getJobLevel();
		foreach ($job_level_arr as $item => $value) {
			$job_level_json[] = ["id" => $item, "name" => $value];
		}
		$this->_aParams['job_level_json'] = json_encode($job_level_json);
		
		//绑定薪资
		$service_salary = new base_service_common_salaryaddjob();
		$salary_arr = $service_salary->getAll();
		foreach ($salary_arr as $item => $value) {
			$salary_json[] = ["id" => $item, "name" => $value];
		}
		$this->_aParams['salary_json'] = json_encode($salary_json);
		
		//绑定试用期
		$service_probation_period = new base_service_common_probationperiod();
		$probation_period_arr = $service_probation_period->getProbationPeriod();
		$probation_period_json[] = ["id" => 0, "name" => "请选择"];
		foreach ($probation_period_arr as $item => $value) {
			$probation_period_json[] = ["id" => $item, "name" => $value];
		}
		$this->_aParams['probation_period_json'] = json_encode($probation_period_json);
		
		//绑定工作年限
		$service_workyear = new base_service_common_workyear();
		$workyear_arr = $service_workyear->getAll();
		$workyear_json[] = ["id" => 'all', "name" => "不限"];
		foreach ($workyear_arr as $item => $value) {
			$workyear_json[] = ["id" => $item, "name" => $value];
		}
		$this->_aParams['workyear_json'] = json_encode($workyear_json);
		
		//绑定学历
		$service_degree = new base_service_common_degree();
		$degree_arr = $service_degree->getAll();
		$degree_json[] = ["id" => 'all', "name" => "不限"];
		foreach ($degree_arr as $item => $value) {
			$degree_json[] = ["id" => $item, "name" => $value];
		}
		$this->_aParams['degree_json'] = json_encode($degree_json);

		//绑定双薪资
		$more_salary_json[] = ["id" => '0', "name" => "请选择"];
		for ($i = 13; $i <= 20 ; $i++) {
			$more_salary_json[] = ["id" => $i, "name" => "{$i}薪"];
		}
		$this->_aParams['more_salary_json'] = json_encode($more_salary_json);
		
		//绑定性别要求
		$service_sex = new base_service_common_sex();
		$sex_arr = $service_sex->getSex();
		$sex_json[] = ["id" => 0, "name" => "不限"];
		foreach ($sex_arr as $item => $value) {
			$sex_json[] = ["id" => $item, "name" => $value];
		}
		$this->_aParams['sex_json'] = json_encode($sex_json);
		
		//绑定年龄
		$age_json[] = ["id" => 0, "name" => "不限"];
		for ($i = 16; $i <= 60; $i++) {
			$age_json[] = ["id" => $i, "name" => $i];
		}
		$this->_aParams['age_json'] = json_encode($age_json);
		
		//绑定语言
		$service_language = new base_service_common_languagetype();
		$language_arr = $service_language->getAll();
		$language_json[] = ["id" => 0, "name" => "不限"];
		foreach ($language_arr as $item => $value) {
			$language_json[] = ["id" => $item, "name" => $value];
		}
		$this->_aParams['language_json'] = json_encode($language_json);
		
		$this->_aParams['linkman'] = $current_company['linkman'];
		$this->_aParams['link_tel'] = $current_company['link_tel'];
		$this->_aParams['email'] = $current_company['email'];

		//新联系方式
		$linkways = array ();
		$linkways[] = array ('link_man' => $current_job['linkman'], 'linkman_tel' => $current_job['link_tel']);
		$this->_aParams['linkways'] = $linkways;
		
		//邮箱
		if (!base_lib_BaseUtils::nullOrEmpty($current_job['other_email'])) {
			$this->_aParams['linkemail'] = explode(',', $current_job['other_email']);
		}
		
		/*==============承诺职位开始==========================*/
		$last_banned_day = 0;//解禁剩余天数
		$last_mustreply_job = 0;//剩余承诺职位数
		$mustreply_job_count = 0;//总承诺职位数
		$mustreply_job_status = $this->canAddMustReplyJob($job_id, $last_banned_day, $last_mustreply_job, $mustreply_job_count);
		
		// $mustreply_job_status 1、能正常发布职位 2、非会员不能发布承诺职位 3、该企业在禁用期 4、该企业发布承诺职位数已满
		$this->_aParams['last_banned_day'] = $last_banned_day;
		$this->_aParams['last_mustreply_job'] = $last_banned_day == 0 ? $last_mustreply_job : 0;
		$this->_aParams['mustreply_job_count'] = $mustreply_job_count;
		$this->_aParams['mustreply_job_status'] = $mustreply_job_status;
		
		$re_apply_type = $current_job['re_apply_type'];//是否是承诺职位
		$this->_aParams['re_apply_type'] = $re_apply_type;
		
		//判断是否有待处理简历
		$statistics = $this->getNotReplyNum($current_job['job_id']);
		$no_reply_num = $statistics[0]['no_reply_num'];
		$this->_aParams['no_reply_num'] = $no_reply_num;
		
		/*==============承诺职位结束===========================*/
		
		//获取没有下级的二级地区
		$service_common_area = new base_service_common_area();
		$no_child_areas = $service_common_area->getSecondLevelNoChildAreas();
		$no_child_area_ids = base_lib_BaseUtils::getProperty($no_child_areas, "area_id");
		$this->_aParams['no_child_area_ids'] = json_encode($no_child_area_ids);
		
		//公司子账户信息
		$company_account_info = $service_company_account->getAccountList($this->_userid, 'account_id,is_main,user_id,user_name,mobile_phone,link_tel,resource_type')->items;
		$this->_aParams['company_account_info'] = base_lib_BaseUtils::array_key_assoc($company_account_info, 'account_id');


		//新职位发布默认为当前登录账户
		if ($this->_aParams['job']['account_id'] != $account_id && $mod_type != 'edit')
			$this->_aParams['job']['account_id'] = $account_id;
		
		if (!empty($this->_aParams['job']['graduate_profession_ids'])) {
			$this->_aParams['job']['graduate_profession_ids_arr'] = explode(',', $this->_aParams['job']['graduate_profession_ids']);
			$professional_commn = new base_service_common_profession1();
			$xx = array ();
			foreach ($this->_aParams['job']['graduate_profession_ids_arr'] as $k => $v) {
				$xx[ $k ]['id'] = $v;
				$xx[ $k ]['name'] = $professional_commn->getProfessionName($v);
			}
			$this->_aParams['job']['graduate_profession_ids_arr'] = $xx;
		}
		 $this->_aParams["chat_seed"] = uniqid();
		 $this->_aParams["resource_type"] = $cur_account['resource_type'];

        //企业绑定列表
        $service_related        = base_service_hractivity_related::getInstance();
        $related_account_list   = $service_related->getRelatedByCompany($this->_userid,"account_id");
        $related_account_ids    = base_lib_BaseUtils::getProperty($related_account_list, "account_id");
        $this->_aParams["related_account_ids"] = $related_account_ids;
        $this->_aParams["cur_is_main"] = $this->_aParams['company_account_info'][$account_id]['is_main'];
        
        //判断企业是否在
        if($current_job["check_state"] == 2){ //审核不通过的时候
            $service_audit_jobcompany = new base_service_company_auditjobcompany();
            $audit_jobcompany      = $service_audit_jobcompany->auditJobCompanyInfo($this->_userid,"id,status_type,company_id");
            $this->_aParams["audit_jobcompany"] = $audit_jobcompany;
        }


        $json_account_info = [];
        foreach ($company_account_info as $_accountinfo){
            if( $this->_aParams["cur_is_main"] == 1 && $_accountinfo['resource_type']==1){
                $json_account_info[] = ['id'=>$_accountinfo['account_id'],'name'=>$_accountinfo['user_name'],'phone'=>$_accountinfo['mobile_phone']];
            }else{
                if($_accountinfo['account_id'] == $this->_aParams['job']['account_id']){
                    $json_account_info[] = ['id'=>$_accountinfo['account_id'],'name'=>$_accountinfo['user_name'],'phone'=>$_accountinfo['mobile_phone']];
                }
            }
        }
        $this->_aParams['json_account_info'] = json_encode($json_account_info);

		//判断单位是否为预警单位
		$service_company_companyrisk = new base_service_company_companyrisk();
		$company_risk = $service_company_companyrisk->checkCompanyWarning($this->_userid);
		$this->_aParams["company_risk"] = $company_risk;

        //职位标签
        list($tag_data,$tag_name_arr) = $this->_getTagData($job_id);
        $this->_aParams["tag_json_data"] = !empty($tag_data) ? json_encode($tag_data) : "";
        $this->_aParams["tag_arr"]       = $tag_data;
        $this->_aParams["tag_name_arr"]  = $tag_name_arr;
        
        $this->_aParams["txtStationNum"] = $mod_type == 'edit' ? 30 : 20;
		return $this->render('job/update.html', $this->_aParams);
	}
	
    
    private function _getTagData($job_id){
        $service_job_tag    = new base_service_company_job_infojobtag();
        $jobtag_info        = $service_job_tag->getJobTags($job_id, "job_id,id,skill_tag_ids,ability_tag_ids,custom_tag_ids,tag_names");
        if(!empty($jobtag_info)){
            $skill_tag_ids                 = !empty($jobtag_info["skill_tag_ids"]) ? explode(",",$jobtag_info["skill_tag_ids"]) : []; 
            $ability_tag_ids               = !empty($jobtag_info["ability_tag_ids"]) ? explode(",",$jobtag_info["ability_tag_ids"]) : []; 
            $custom_tag_ids                = !empty($jobtag_info["custom_tag_ids"]) ? explode(",",$jobtag_info["custom_tag_ids"]) : []; 
            
            $service_jobsortskilltagcustom = new base_service_company_job_jobsortskilltagcustom();
            $service_jobsort_skilltag      = new base_service_person_resume_jobsortskilltag();
            $custom_list = [];
            if(!empty($custom_tag_ids)){
                $custom_list    = $service_jobsortskilltagcustom->getTagsIds($custom_tag_ids, $this->_userid, "id,tag_name");
                $custom_list    = base_lib_BaseUtils::array_key_assoc($custom_list, "id");
            }
            $skill_list = [];       
            if(!empty($skill_tag_ids) || !empty($ability_tag_ids)){
                $total_tag_ids  = array_merge($skill_tag_ids,$ability_tag_ids);
                $skill_list     = $service_jobsort_skilltag->getTagsIds($total_tag_ids, "id,tag_name");
                $skill_list    = base_lib_BaseUtils::array_key_assoc($skill_list, "id");
            }
            $tag_total_array = [];
            if(!empty($skill_tag_ids)){
                foreach($skill_tag_ids as $_id){
                    $_tag_data = $skill_list[$_id];
                    if(!empty($_tag_data)){
                       $tag_total_array[] = ["id" => $_id,"tag_name" => $_tag_data["tag_name"],"tag_type" => "1"]; 
                    }
                }
            }
            if(!empty($ability_tag_ids)){
                foreach($ability_tag_ids as $_id){
                    $_tag_data = $skill_list[$_id];
                    if(!empty($_tag_data)){
                       $tag_total_array[] = ["id" => $_id,"tag_name" => $_tag_data["tag_name"],"tag_type" => "2"]; 
                    }
                }
            }
            if(!empty($custom_tag_ids)){
                foreach($custom_tag_ids as $_id){
                    $_tag_data = $custom_list[$_id];
                    if(!empty($_tag_data)){
                       $tag_total_array[] = ["id" => $_id,"tag_name" => $_tag_data["tag_name"],"tag_type" => "3"]; 
                    }
                }
            }
        }
        $tag_name_arr = !empty($jobtag_info["tag_names"]) ? explode(",", $jobtag_info["tag_names"]) : [];
        if(!empty($tag_name_arr)){
            $tag_name_arr = array_filter($tag_total_array,function($v){
                if(!empty($v)){
                    return true;
                }
                return false;
            });
        }
        return [$tag_total_array,$tag_name_arr];
    }
	/**
	 *
	 * 发布职位
	 * @param unknown_type $inPath
	 */
	public function pageJobAddDo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$re_apply_type = base_lib_BaseUtils::getStr($path_data['txtReApplyType'], "int", 0);
		$is_refresh = base_lib_BaseUtils::getStr($path_data['is_refresh'], "int", 0);
		$service_company = new base_service_company_company();
		$current_company = $service_company->getCompany($this->_userid, 1, 'company_id,company_name,end_time,area_id,com_level,site_type');
        $is_new_service = base_lib_BaseUtils::getStr($path_data['is_new_service'], 'int', 0);
        $is_quality     = base_lib_BaseUtils::getStr($path_data['is_quality'], 'int', 0);
		$validator = new base_lib_Validator();
		if (empty($current_company)) {
			return;
		}
		
		if ($re_apply_type > 0) {
			//判断该职位是不是承诺职位 若是则判断该企业是不是能发布承诺职位
			$mustreply_status = $this->canAddMustReplyJob(0);
			switch ($mustreply_status) {
				case 2:
					$validator->addErr("您还未开通会员,不能发布承诺职位！");
					break;
				
				case 3:
					$validator->addErr("您在禁用期，不能发布承诺职位！");
					break;
				
				case 4:
					$validator->addErr("您的承诺职位数已满");
					break;
			}
		}
		
		$re_apply_types = [0, 2, 5];
		if (!in_array($re_apply_type, $re_apply_types))
			$validator->addErr("请选择正确的简历回复承诺类型");
		
		$job['re_apply_type'] = $re_apply_type;
		$this->_aParams['title'] = '发布职位';
		
		//单位ID
		$company_id = base_lib_BaseUtils::getStr($path_data['hddComId'], "int", 0);
		$company_id = empty($company_id) ? $this->_userid : $company_id;
		
		$now_company = $service_company->getCompany($company_id, 1, 'company_id,company_name');
		
		$job['company_id'] = $company_id;

		//判断单位是否为预警单位
		$service_company_companyrisk = new base_service_company_companyrisk();
		$company_risk = $service_company_companyrisk->checkCompanyWarning($this->_userid);
		$this->_aParams["company_risk"] = $company_risk;
		
		//单位名称
		$job['company_name'] = $now_company['company_name'];
        $account_id = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$account_id);

        //批量工作地点
        //工作地点
        $job_area['area_id'] = $validator->getArray($path_data['hddArea'], '请选择区域', false);
        $job_area['add_info'] = $validator->getArray($path_data['txtAddInfo'], '请完善职位详细地址', false);
        $job_area['map_x'] = $validator->getArray($path_data['map_x'], '请完善职位地址坐标', false);
        $job_area['map_y'] = $validator->getArray($path_data['map_y'], '请完善职位地址坐标', false);
        $job_area['address_id'] = $validator->getArray($path_data['address_id'], '请完善职位地址坐标', false);
		//职位发布数量验证
		$company_resource_info = $company_resources->getCompanyServiceSource(["default_job_num", "has_pub_job_num","pricing_resource",'cq_pricing_resource','spread_overage','account_overage']);
        $default_job_num = (int)$company_resource_info["default_job_num"];
        $current_job_num = (int)$company_resource_info["has_pub_job_num"];
        $str = '您的发布职位数已满！';
        $is_stop_use = false;
        $job_num = count($job_area['area_id']);
        if($company_resource_info['isCqNewService']){//重庆新套餐逻辑
            $default_job_num = (int)$company_resource_info['cq_job_num'];
            $current_job_num = (int)$company_resource_info['has_pub_job_free_num'];
            $service_servicePricing = new base_service_company_service_servicePricing();
            $selling = $service_servicePricing->GetFunParallelismSelling('point_job_refresh');
            //$company_resource_info['cq_release_point_job_refresh'] = 1;
            if( $is_refresh && $company_resource_info['cq_release_point_job_refresh'] < $job_num && ($company_resource_info['spread_overage'] + $company_resource_info['account_overage']) < $selling*$job_num){
                $validator->addErr("发布职位失败，刷新点和推广金不足");
            }
            if($is_refresh ){
                $job['refresh_time'] = date('Y-m-d H:i:s');
            }else{
                $job['refresh_time'] = '2014-01-01 00:00:00';
            }

        }
        //当月餐饮免费会员发布职位限制
        $base_service_solr_job = new base_service_solr_job();
        $jobList = $base_service_solr_job->GetCompanyAlreadyPublishJobByCompanyId($this->_userid,date('Y-m-01',time()),10);
        if ($current_company['com_level']==1 && $current_company['site_type']==4){
            if (count($jobList)>=5 || (count($job_area['area_id'])+count($jobList)>5)){
                $have_jobcount =count($jobList)>=5?0:5-count($jobList);
                $str = '超出职位发布数上限,当前可发布职位数:'.$have_jobcount;
                $validator->addErr($str);
            }
        }else{
            if ($current_job_num + $job_num > $default_job_num && !$company_resource_info['isNewService']) {
                $validator->addErr($str);
            }
        }


        if($company_resource_info['isNewService']&&$is_new_service==1){
            if($is_quality == 1){
                if(count($job_area['area_id']) > ($company_resource_info['point_job_boutique']-$company_resource_info['point_job_boutique_use'])
                || count($job_area['area_id']) > $company_resource_info['job_boutique_release']){
                    $is_stop_use = true; //暂存职位
                }
            }elseif( count($job_area['area_id']) > $company_resource_info['job_release_num']){
                $is_stop_use = true;
            }
        }
		//同行认证的不能发布职位和修改职位
//		$company_location_area = $company_resources->location_area;
//		list($is_audit, $audit_params) = $company_resources->getCompanyAuditStatus();
//		if ($is_audit == 5 && $company_location_area != "NOT_CQ_CITY") { //同行认证
//			$validator->addErr("您还没有上传营业执照，不能发布职位");
//		}
		
		//职位性质
		$service_job_type = new base_service_common_jobtype();
		$job['job_type'] = $validator->getEnum($path_data['radJobType'], $service_job_type->getJobtype(), '请选择职位性质');
		
		//职位名称
		$station = $validator->getStr($path_data['txtStation'], 2, 20, '名称2-20个字符');
		$job['station'] = $station;
		
		//岗位级别
		$service_job_level = new base_service_common_joblevel();
		$job['job_level'] = $validator->getEnum($path_data['hddJoblevel'], $service_job_level->getJobLevel(), '请选择岗位级别');
		
		//新版职位类别
		$main_jobsort = $validator->getNotNull($path_data['main_jobsort'], "请选择主要职位类别");
		$next_jobsort = base_lib_BaseUtils::getStr($path_data['next_jobsort'], 'string', '');
		$job['jobsort'] = $main_jobsort;
		$job['jobsort_ids'] = empty($next_jobsort) ? $main_jobsort : $main_jobsort . "," . $next_jobsort;
		
		//招聘人数
		$validator->getNotNull($path_data['txtQuantity'], '请输入招聘人数');
		$job['quantity'] = $validator->getNum($path_data['txtQuantity'], 1, 999, '招聘人数为1～3位整数，且不能为0。');
		
		//定额工资，如果为0则是定额工资，如果不为0则为底薪+提成
		$salary_type = base_lib_BaseUtils::getStr($path_data['rd'], 'int', 0);
		$job['salary_type'] = $salary_type;
		// // $dept_name = base_lib_BaseUtils::getStr($path_data['hddJobDept'],'string');
		// if(base_lib_BaseUtils::nullOrEmpty($dept_name)){
		//     $validator->addErr('请选择公司部门');
		// }
		// $job['dept_name'] = $dept_name;
		
		//新需求，后台模拟登录的校招企业，发布校招职位可以不填薪资
		//是否接受应届生
		$job['allow_graduate'] = base_lib_BaseUtils::getStr($path_data['chkNewGraduate'], 'int', 0);
		if ($job['allow_graduate'] == 1 && base_lib_BaseUtils::nullOrEmpty($path_data['main_professionalSort']) && base_lib_BaseUtils::nullOrEmpty($path_data['professionRemark'])) {
			$validator->addErr("请选择应届生专业");
		}
		if ($job['allow_graduate'] == 1) {
			// 应届生专业
			if ($path_data['main_professionalSort'] == '00') {
				$job['graduate_profession_ids'] = '';
				$job['graduate_profession_remark'] = '';
			} else {
				$job['graduate_profession_ids'] = base_lib_BaseUtils::getStr($path_data['main_professionalSort'], 'string', '');
				$job['graduate_profession_remark'] = base_lib_BaseUtils::getStr($path_data['professionRemark'], 'string', '');
			}
		} else {
			$job['graduate_profession_ids'] = '';
			$job['graduate_profession_remark'] = '';
		}
		
		
		$can_pub_no_salary = ($company_resources->account_type == "NotMemberTypSchool" && base_lib_BaseUtils::getCookie("bossuser"));
		
		if ($salary_type == 0) {
			if ($job['allow_graduate'] && $can_pub_no_salary && empty($path_data['hddSalary1']) && empty($path_data['hddSalary1End'])) {
				$minsalary = 0;
				$maxsalary = 0;
			} else {
				//薪资最小值
				$minsalary = $validator->getNotNull($path_data['hddSalary1'], '请输入薪资待遇');
				$validator->getNum($path_data['hddSalary1'], 500, 'max', '薪资待遇不能低于500');
				//薪资最大值
				$maxsalary = $validator->getNotNull($path_data['hddSalary1End'], '请输入薪资待遇');
				$validator->getNum($path_data['hddSalary1End'], 'min', 100000, '薪资待遇不能超过100000');
				
				if ($minsalary > $maxsalary)
					list($minsalary, $maxsalary) = [$maxsalary, $minsalary];
			}

			$job['salary_month_num'] = $path_data['moreSalarySelect'];
			$job['min_salary'] = $minsalary;
			$job['max_salary'] = $maxsalary;
		} else {
			if ($job['allow_graduate'] && $can_pub_no_salary && empty($path_data['hddSalary2']) && empty($path_data['hddSalary2End']) && empty($path_data['hddSalary3']) && empty($path_data['hddSalary3End'])) {
				$job['base_min_salary'] = 0;
				$job['base_max_salary'] = 0;
				$job['min_salary'] = 0;
				$job['max_salary'] = 0;
				$job['salary_month_num'] = 0;
			} else {
				//薪资最小值
				$minsalary = $validator->getNotNull($path_data['hddSalary2'], '请输入薪资待遇');
				$validator->getNum($path_data['hddSalary2'], 500, 'max', '薪资待遇不能低于500');
				
				//薪资最大值
				$maxsalary = $validator->getNotNull($path_data['hddSalary2End'], '请输入薪资待遇');
				$validator->getNum($path_data['hddSalary2End'], 'min', 100000, '薪资待遇不能超过100000');
				if ($minsalary > $maxsalary) {
					$minsalary = $minsalary - $maxsalary;
					$maxsalary = $maxsalary + $minsalary;
					$minsalary = $maxsalary - $minsalary;
				}
				if (($maxsalary / $minsalary) > 2) {
					array_push($validator->err, '最大薪资不能超过最低工资的2倍');
					$validator->has_err = true;
				}
				$job['salary_month_num'] = 0;
				$job['base_min_salary'] = $minsalary;
				$job['base_max_salary'] = $maxsalary;
				
				//平均工资
				//平均薪资最小值
				$minsalary_svg = $validator->getNotNull($path_data['hddSalary3'], '请输入薪资待遇');
				$validator->getNum($path_data['hddSalary3'], 500, 'max', '薪资待遇不能低于500');
				
				//平均最大值
				$maxsalary_svg = $validator->getNotNull($path_data['hddSalary3End'], '请输入薪资待遇');
				$validator->getNum($path_data['hddSalary3End'], 'min', 100000, '薪资待遇不能超过100000');
				
				if ($minsalary_svg > $maxsalary_svg) {
					list($minsalary_avg, $maxsalary_svg) = [$maxsalary_svg, $minsalary_svg];
				}
				
				if (($maxsalary_svg / $minsalary_svg) > 3) {
					$validator->addErr("最大薪资不能超过最低薪资的3倍");
				}
				
				if ($maxsalary_svg <= $maxsalary) {
					$validator->addErr("平均收入必须大于底薪");
				}
				
				if ($minsalary_svg <= $minsalary) {
					$validator->addErr("平均收入必须大于底薪");
				}
				
				$job['min_salary'] = $minsalary_svg;
				$job['max_salary'] = $maxsalary_svg;
			}
		}
		
		//获得福利
		$job['other_reward_ids'] = $path_data['hidDefaultReward'];
		
		//获得其他福利
		$job['other_reward'] = $path_data['hidOtherReward'];
		
		//获得岗位特点
		$job['job_feature_ids'] = $path_data['hidDefaultFea'];
		
		//获得其他岗位特点
		$job['job_other_feature'] = $path_data['hidOtherFea'];
		
		//岗位职责
		$job['content'] = $validator->getNotNull($path_data['txtContent'], '请输入岗位职责');
		$validator->getStr($path_data['txtContent'], 20, 0, '太过简单了吧，最少20个字');
		$validator->getStr($path_data['txtContent'], 0, 2000, '不能超过2000个字');
		
		// 有效期
		$job['valid_days'] = $validator->getNotNull($path_data['txtValidDays'], '请输入有效期');
		$validator->getNum($path_data['txtValidDays'], 1, 60, '请输入1-60之间的整数');
		
		// 截至时间
		$job['end_time'] = date('Y-m-d H:i:s', strtotime("+{$job['valid_days']} day"));

		// 职位的有效状态
		$service_jobstatus = new base_service_common_jobstatus();
        $job['status'] = $service_jobstatus->use;
        if($is_stop_use){
            $job['status'] = $service_jobstatus->stop_use;
        }
		//补充说明
		$path_data['txtOtherNeed'] = trim($path_data['txtOtherNeed']);
		$other_need = base_lib_BaseUtils::getStr($path_data['txtOtherNeed'], 'string');
		if (!empty($path_data['txtOtherNeed'])) {
			$job['other_need'] = $other_need;
//			$validator->getStr($path_data['txtOtherNeed'], 20, 0, '太过简单了吧，最少20个字');
			$validator->getStr($path_data['txtOtherNeed'], 0, 2000, '补充说明不能超过2000个字');
		}
		
		//工作年限
		$workyear = base_lib_BaseUtils::getStr($path_data['hddWorkyear'], 'string', '0');
		$service_workyear = new base_service_common_workyear();
		$wkdes = $service_workyear->getWorkyear($workyear);
//		if (strcmp($workyear, '0') != 0) {
		if (!empty($workyear)) {
			if ($workyear == 'all') {
				$workyear = 0;
			} else if (empty($wkdes)) {
				$validator->addErr("工作年限不正确");
			}
		} else {
			$validator->addErr("工作年限不正确");
		}
		
		$job['work_year_id'] = $workyear;
		
		//学历
		$degree = base_lib_BaseUtils::getStr($path_data['hddDegree'], 'string', '0');
		$service_degree = new base_service_common_degree();
		$ddes = $service_degree->getDegree($degree);
		if (!empty($degree)) {
			if ($degree == 'all') {
				$degree = 0;
			} else if (empty($ddes)) {
				$validator->addErr("学历不正确");
			}
		} else {
			$validator->addErr("学历不正确");
		}
		$job['degree_id'] = $degree;
		
		//性别
		$sex = base_lib_BaseUtils::getStr($path_data['hddSex'], 'string', '0');
		if (strcmp($sex, '0') != 0) {
			$service_sex = new base_service_common_sex();
			$sdes = $service_sex->getSex($service_sex);
			if (empty($sdes)) {
				array_push($validator->err, '性别不正确');
				$validator->has_err = true;
			}
		}
		$job['sex'] = $sex;
		//年龄最小值
		$agelower = base_lib_BaseUtils::getStr($path_data['hddAge1'], 'int', 0);
		if ($agelower != 0) {
			$validator->getNum($path_data['hddAge1'], 16, 60, '年龄最小值不正确');
		}
		
		//年龄最大值
		$ageupper = base_lib_BaseUtils::getStr($path_data['hddAge2'], 'int', -1);
		if ($ageupper != 0) {
			$validator->getNum($path_data['hddAge2'], 16, 60, '年龄最大值不正确');
		}
		
		if ($agelower > $ageupper && $ageupper != 0) {
			list($agelower, $ageupper) = [$ageupper, $agelower];
		}
		
		$job['age_lower'] = $agelower;
		$job['age_upper'] = $ageupper;
		//外语要求
		$language = base_lib_BaseUtils::getStr($path_data['hddLanguage'], 'string', '0');
		if (strcmp($language, '0') != 0) {
			$service_language = new base_service_common_languagetype();
			$ldes = $service_language->getLanguageType($language);
			if (empty($ldes)) {
				$validator->addErr("外语要求不正确");
			}
		}
		
		//是否开启自动过滤
		$is_automatic = base_lib_BaseUtils::getStr($path_data['hddAutomatic'], 'int', 0);
		$job['auto_filter'] = $is_automatic;
		$job['language_id'] = $language;
		
		//是否显示联系方式
		$isshowlinkway = base_lib_BaseUtils::getStr($path_data['showLinkway'], 'int', 0);
		$validator->getNum($isshowlinkway, 0, 1, '请选择联系方式');
		//是否使用新联系方式
		$newlinkway = base_lib_BaseUtils::getStr($path_data['newLinkway'], 'int', 0);
		$validator->getNum($newlinkway, 0, 5, '请选择联系方式');
		//新联系方式数量
		$newlinkwaycount = base_lib_BaseUtils::getStr($path_data['newLinkWayCount'], 'int', 1);
		$validator->getNum($path_data['newLinkWayCount'], 1, 3, '新联系方式数量异常');
		
		//联系人和联系方式数组
		$linkmantel_arr = array ();
		if ($isshowlinkway != 0) { //显示联系方式
			$job['open_linkway'] = 1;
			if ($newlinkway == 1) { //使用新联系方式
				$job['self_linkway'] = 1;
				for ($i = 0; $i < $newlinkwaycount; $i++) {
					$job['linkman'] = $validator->getStr($path_data[ 'txtLinkMan' . ($i + 1) ],6,13,'联系人电话限制6-13位数字');
					$job['link_tel'] = '';
					if (!empty($path_data[ 'txtLinkTel' . ($i + 1) ]))
						$job['link_tel'] = $validator->getStr($path_data['txtLinkTel1'], 1, 6, '请填写1-6位数字的分机号');
					break;
				}
			} else {//使用企业联系方式
				//0 使用企业联系方式(以前的) 1 使用新联系方式  4 发布人手机号 5 发布人办公电话
				//$job['self_linkway'] = 0;
				$job['self_linkway'] = $newlinkway;
			}
		} else {//不显示联系方式
			$job['open_linkway'] = 0;
			$job['self_linkway'] = 0;
		}
		
		
		//是否在线沟通
		$job['allow_online_talk'] = base_lib_BaseUtils::getStr($path_data['param_allow_link'], 'int', '0');
		//发布人ID
		$job['account_id'] = base_lib_BaseUtils::getStr($path_data['param_account_id'], 'int', '');
		if (empty($job['account_id'])) {
			$validator->addErr("无有效发布人ID");
		}
		/*if ($job["allow_online_talk"] == 1) {
			$service_related = new base_service_hractivity_related();
			$related_info = $service_related->getRelatedByAccount($job['account_id'], "account_id");
			if (empty($related_info)) {
				$validator->addErr("你还没有使用企业APP，无法使用在线沟通功能。请关闭在线沟通功能，或下载企业APP登录并关联企业账号");
			}
		}*/
		
		//是否需要发送到邮箱
		$istomail = base_lib_BaseUtils::getStr($path_data['toMail'], 'int', 0);
		$validator->getNum($path_data['toMail'], 0, 1, '是否需要发送到邮箱异常');
		
		//新邮箱数量
		$emailcount = base_lib_BaseUtils::getStr($path_data['emailCount'], 'int', 1);
		$validator->getNum($path_data['emailCount'], 1, 3, '新联系方式数量异常');
		
		//新邮箱数组
		$emails_arr = array ();
		$service_allow_email_type = new base_service_company_job_joballowemailtype();
		if ($istomail != 0) {//需要发送到邮箱
			//是发到系统邮箱还是其他新邮箱
			$emailtype = base_lib_BaseUtils::getStr($path_data['emailType'], 'int', 2);
			$validator->getNum($path_data['emailType'], 2, 3, '请选择接收简历的邮箱');
			
			if ($emailtype == 2) {
				$job['allow_email'] = $service_allow_email_type->toaccountandemail;
				$job['other_email'] = '';
			} else {
				$job['allow_email'] = $service_allow_email_type->toaccountandotheremail;
				for ($j = 0; $j < $emailcount; $j++) {
					$email = $validator->getEmail($path_data[ 'email' . ($j + 1) ], '第' . ($j + 1) . '个邮箱不正确');
					array_push($emails_arr, $email);
				};
				$job['other_email'] = implode(',', $emails_arr);
			}
		} else {
			$job['allow_email'] = $service_allow_email_type->toaccount;
			$job['other_email'] = '';
		}
		//是否发布下一个职位
		$goonPublish = base_lib_BaseUtils::getStr($path_data['goonPublish'], 'bool', false);
		
		//发布时间
		$job['issue_time'] = date('Y-m-d H:i:s', time());
		//开始时间
		$job['start_time'] = date('Y-m-d H:i:s', time());
		//创建时间
		$job['create_time'] = date('Y-m-d H:i:s', time());
		//最后发布时间
		$job['last_pub_time'] = date('Y-m-d H:i:s', time());
		//点击次数
		$job['visit_num'] = 0;
		//风险指数
		$job['risk_index'] = 0;
		//审核状态
		$job['check_state'] = 0;
		//入库类型
		$job['storage_type'] = 1;
		//未使用字段
		$job['is_advanced'] = 0;
		
		//$job['other_reward'] = null;
		$job['work_year'] = null;
		$job['work_year_max'] = null;
		$job['link_id'] = null;
		$job['is_auto_reply'] = '0';
		$job['reply_content'] = null;



		$service_area = new base_service_common_area();
		$service_job = new base_service_company_job_job();
		foreach ($job_area['area_id'] as $area_key => $area_li) {
			if (!$area_li) {
				$validator->addErr("请选择区域");
				break;
			}
			//发布的职位需要限制地区  非重庆主城区的同行认证企业  或者 重庆地区非会员企业 需要限制地区
			if ($company_resources->account_type == "NotMemberTypeArea" || ($company_resources->location_area != "CQ_MAIN_CITY" && $current_company["com_level"] <= 1)) {
				//判断选择的地区和公司的地区是不是在同一个区
				$is_simple_area = $service_area->checkSimpleArea($area_li, $current_company["area_id"]);
				if (!$is_simple_area) {
					$validator->addErr("您只能发布当前公司地区范围内的职位");
					break;
				}
			}
            $comservice_service = new base_service_company_service_comservice();
            if($company_resource_info['isNewService']){
                $area_service = new base_service_common_area();
                $area_type = $comservice_service->getComService($this->_userid, 'area_type')['area_type'];
                if($area_type){
                    switch ($area_type) {
                        case '1':
                            $_status =  array ('status' => true, 'msg' => '不限');
                            break;
                        case '2':
                            $area_list = $area_service->getCQMainAreas();
                            $area_find = array ();
                            foreach ($area_list as $item) {
                                if ($item == '0300')
                                    continue;
                                $tempFild = $area_service->getChildArea($item);
                                $area_find = array_merge($area_find, base_lib_BaseUtils::getProperty($tempFild, 'area_id'));
                            }
                            $area_list = array_unique(array_merge($area_list, $area_find));

                            $status = true;
                            $msg = "";
                            if (!in_array($area_li, $area_list)) {
                                $status = false;
                                $msg = "您不能发布重庆主城以外的职位";
                            }

                            $_status =  array ('status' => $status, 'msg' => $msg);
                            break;
                        case '3':
                            $area_list = $area_service->getCQSmallCity();
                            $area_list1 = $area_service->getCQSmallCityAll();
                            $area_list = array_merge($area_list, $area_list1);
                            $area_find = array ();
                            foreach ($area_list as $item) {
                                $tempFild = $area_service->getChildArea($item);
                                $area_find = array_merge($area_find, base_lib_BaseUtils::getProperty($tempFild, 'area_id'));
                            }
                            $area_list = array_merge($area_list, $area_find);

                            $status = true;
                            $msg = "";
                            if (!in_array($area_li, $area_list)) {
                                $status = false;
                                $msg = "您不能发布重庆区县以外的职位";
                            }

                            $_status =  array ('status' => $status, 'msg' => $msg);
                            break;
                        case '4':
                            $area_list = $area_service->getSCCityAll();
                            $status = true;
                            $msg = "";
                            if (strlen($area_li) >= 4) {
                                $cur_area = substr($area_li, 0, 4);
                            }
                            if (!in_array($cur_area, $area_list)) {
                                $status = false;
                                $msg = "您不能发布四川以外的职位";
                            }

                            $_status = array ('status' => $status, 'msg' => $msg);
                            break;
                    }
                }else{
                    $service_servicePricing = new base_service_company_service_servicePricing();
                    $SC_new_service         =  $service_servicePricing->GetCompanyDefaultPricing($this->_userid,true);
                    switch ($SC_new_service['area_type']) {
                        case '1':
                            $_status =  array ('status' => true, 'msg' => '不限');
                            break;
                        case '2':
                            $area_list = $area_service->getCQMainAreas();
                            $area_find = array ();
                            foreach ($area_list as $item) {
                                if ($item == '0300')
                                    continue;
                                $tempFild = $area_service->getChildArea($item);
                                $area_find = array_merge($area_find, base_lib_BaseUtils::getProperty($tempFild, 'area_id'));
                            }
                            $area_list = array_unique(array_merge($area_list, $area_find));

                            $status = true;
                            $msg = "";
                            if (!in_array($area_li, $area_list)) {
                                $status = false;
                                $msg = "您不能发布重庆主城以外的职位";
                            }

                            $_status =  array ('status' => $status, 'msg' => $msg);
                            break;
                        case '3':
                            $area_list = $area_service->getCQSmallCity();
                            $area_list1 = $area_service->getCQSmallCityAll();
                            $area_list = array_merge($area_list, $area_list1);
                            $area_find = array ();
                            foreach ($area_list as $item) {
                                $tempFild = $area_service->getChildArea($item);
                                $area_find = array_merge($area_find, base_lib_BaseUtils::getProperty($tempFild, 'area_id'));
                            }
                            $area_list = array_merge($area_list, $area_find);

                            $status = true;
                            $msg = "";
                            if (!in_array($area_li, $area_list)) {
                                $status = false;
                                $msg = "您不能发布重庆区县以外的职位";
                            }

                            $_status =  array ('status' => $status, 'msg' => $msg);
                            break;
                        case '4':
                            $area_list = $area_service->getSCCityAll();
                            $status = true;
                            $msg = "";
                            if (strlen($area_li) >= 4) {
                                $cur_area = substr($area_li, 0, 4);
                            }
                            if (!in_array($cur_area, $area_list)) {
                                $status = false;
                                $msg = "您不能发布四川以外的职位";
                            }

                            $_status = array ('status' => $status, 'msg' => $msg);
                            break;
                    }
                }
                if($_status && !$_status['status']){
                    $validator->addErr($_status['msg']);
                }
            }elseif ($current_company["com_level"] > 1) {
				$areatype = $comservice_service->areatypecheck($company_resources->main_company_id, $area_li);
				if (!$areatype['status']) {
					$validator->addErr($areatype['msg']);
					break;
				}
			}

			if (!$job_area['add_info'][ $area_key ]) {
				$validator->addErr("请完善职位详细地址");
				break;
			}
			if (!$job_area['map_x'][ $area_key ] || !$job_area['map_y'][ $area_key ] || $job_area['address_id'][ $area_key ] == '') {
				$validator->addErr("请完善职位地址坐标");
				break;
			}
		}
        //职位标签
        $tag_names          = [];
        $skill_tag_ids      = [];
        $ability_tag_ids    = [];
        $custom_tag_ids     = [];
        if(!empty($_POST["hddJobTags"])){
            //保存职位标签
            $hddJobTags = json_decode($_POST["hddJobTags"],true);
            if(!empty($hddJobTags)){
                foreach($hddJobTags as $tag){
                    if($tag['tag_type']==1){
                        $skill_tag_ids[]=$tag['id'];
                        $tag_names[]=$tag['tag_name'];
                    }
                    if($tag['tag_type']==2){
                        $ability_tag_ids[]=$tag['id'];
                        $tag_names[]=$tag['tag_name'];
                    }
                    if($tag['tag_type']==3){
                        $custom_tag_ids[]=$tag['id'];
                        $tag_names[]=$tag['tag_name'];
                    }
                }
            }
        }
        if(!empty($tag_names)){
            $job["tag_names"] = implode(",",$tag_names);
        }
		if ($validator->has_err) {
			echo header("Content-type:text/plain;charset=utf-8");
			if (empty($_POST)) {
				$errArr = array ('error' => $validator->ToHtml(), 'requirst_again' => true);
			} else {
				$errArr = array ('error' => $validator->ToHtml());
			}
			echo json_encode($errArr, JSON_UNESCAPED_UNICODE);

			return;
		}
		$service_company_job_job = new base_service_company_job_job();
		$service_company_job_jobrefreshaudit = new base_service_company_job_jobrefreshaudit();
		$re_job_resule = 0;
		foreach ($job_area['area_id'] as $area_key => $area_li) {
			$job_tmp = $job;
			$job_tmp['area_id'] = $area_li;
			$job_tmp['add_info'] = $job_area['add_info'][ $area_key ];
			$job_tmp['map_x'] = $job_area['map_x'][ $area_key ];
			$job_tmp['map_y'] = $job_area['map_y'][ $area_key ];
			$job_tmp['address_id'] = $job_area['address_id'][ $area_key ];

            $job_resule = $service_job->addJob($job_tmp, $current_company, $linkmantel_arr, $emails_arr, null, false);
            !$re_job_resule and $job_resule and $re_job_resule = $job_resule;
            $status = true;
            $SCCityAll_data = $service_area->getSCCityAll();
            $area_tmp = substr($job_tmp['area_id'], 0, 4);
            if($is_new_service==1 && $is_quality==1 && !$is_stop_use ){
                $resource_consume = $company_resources->consume('pub_boutique_job', $params=['job_id'=>$job_resule, "company_id"=>$this->_userid]);
                list($status, $code, $params) = $resource_consume;
            }
			//职位审核状态为手动审核，不需要刷新扣点
			$job_info = $service_company_job_job->getJob($job_resule,"job_id,check_state,station,account_id,jobsort");
            if($job_resule){
                if(!empty($tag_names)){
                    $service_job_tag = new base_service_company_job_infojobtag();
                    $tag_items       = [];
                    $tag_items["job_id"]        = $job_info["job_id"];
                    $tag_items["company_id"]    = $this->_userid;
                    $tag_items["account_id"]    = $job_info["account_id"];
                    $tag_items["station"]       = $job_info["station"];
                    $tag_items["jobsort_id"]    = $job_info["jobsort"];
                    $tag_items["skill_tag_ids"]     = !empty($skill_tag_ids)  ? implode(",", $skill_tag_ids) : "";
                    $tag_items["ability_tag_ids"]     = !empty($ability_tag_ids)  ? implode(",", $ability_tag_ids) : "";
                    $tag_items["custom_tag_ids"]     = !empty($custom_tag_ids)  ? implode(",", $custom_tag_ids) : "";
                    $tag_items["tag_names"]     = !empty($tag_names)  ? implode(",", $tag_names) : "";
                    $service_job_tag->addTags($tag_items);
                }
            }
            if($is_refresh && $company_resource_info['isCqNewService']){
                if($job_info['check_state'] != 4) {
					$consume_refresh = $company_resources->consume('cq_setmeal_consume', ['consume_type' => 'refresh', 'batch' => count($job_area['area_id']) > 1 ? true : false, 'job_id' => $job_resule]);

					if (!$consume_refresh['result']) {
						$service_job->update(['job_id' => $job_resule], ['refresh_time' => '2014-01-01 00:00:00', 'sph_update_flag' => date("Y-m-d H:i:s")]);
						$status = false;
						$validator->addErr($consume_refresh['msg']);
					}
				}else{
					//手动刷新的职位，添加审核后刷新纪录
					$service_company_job_jobrefreshaudit->setJobRefreshAudit($job_resule,$account_id);
				}
            }

            if(!$status){
                $validator->addErr($params);
                break;
            }
            if ($validator->has_err) {
                echo header("Content-type:text/plain;charset=utf-8");
                if (empty($_POST)) {
                    $errArr = array ('error' => $validator->ToHtml(), 'requirst_again' => true);
                } else {
                    $errArr = array ('error' => $validator->ToHtml());
                }
                echo json_encode($errArr, JSON_UNESCAPED_UNICODE);

                return;
            }
			if ($job_resule !== false) {
				//---------添加操作日志--------
				$common_oper_type = new base_service_common_account_accountoperatetype();
				$service_oper_log = new base_service_company_companyaccountlog();
				$common_oper_src_type = new base_service_common_account_accountlogfrom();
				$insertItems = array (
					"company_id"   => $this->_userid,
					"source"       => $common_oper_src_type->website,
					"account_id"   => base_lib_BaseUtils::getCookie('accountid'),
					"operate_type" => $common_oper_type->job_add,
					"content"      => "发布了新职位：" . $job['station'],
					"create_time"  => date("Y-m-d H:i:s", time())
				);
				$service_oper_log->addLogToMongo($insertItems);
				//保存动作类型
				//保存登录动作
				//动作类型
				$service_actiontype = new base_service_common_actiontype();
				//用户类型
				$service_actionusertype = new base_service_common_actionusertype();
				//渠道
				$service_actionsource = new base_service_common_actionsource();
				base_lib_BaseUtils::saveAction($service_actiontype->addjob, $service_actionsource->website, $this->_userid, $service_actionusertype->company);
				//-------------END------------
			} else {
				$validator->addErr("发布职位失败,请稍后重试...");
				break;
			}
		}
		
		if ($validator->has_err) {
			echo header("Content-type:text/plain;charset=utf-8");
			if (empty($_POST)) {
				$errArr = array ('error' => $validator->ToHtml(), 'requirst_again' => true);
			} else {
				$errArr = array ('error' => $validator->ToHtml());
			}
			echo json_encode($errArr, JSON_UNESCAPED_UNICODE);
			
			return;
		}
		
		return json_encode(array ('status' => 'succeed', 'goPublish' => $goonPublish, 'isOverdue' => $out_service, 'station' => str_replace('%2F', '', urlencode($job['station'])), 'job_id' => $re_job_resule));
	}
	
	/**
	 * 修改职位
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	public function pageJobUpdateDo($inPath) {
		
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$validator = new base_lib_Validator();
        $mod_type = base_lib_BaseUtils::getStr($path_data['mod_type'], 'string', '');
        $is_refresh = base_lib_BaseUtils::getStr($path_data['is_refresh'], 'string', 0);
		$service_company = new base_service_company_company();
		$current_company = $service_company->getCompany($this->_userid, 1, 'company_id,end_time,area_id,com_level');
		if (empty($current_company)) {
			return;
		}
		
		$service_job = new base_service_company_job_job();
		$this->_aParams['title'] = '修改职位';
		
		//职位ID
		$job_id = base_lib_BaseUtils::getStr($path_data['jobID'], 'int', 0);
		if ($job_id === 0) {
			return;
		}
        $is_new_service = base_lib_BaseUtils::getStr($path_data['is_new_service'], 'int', 0);
        $is_quality     = base_lib_BaseUtils::getStr($path_data['is_quality'], 'int', 0);
        $account_id = base_lib_BaseUtils::getCookie('accountid');
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$account_id);
        $company_resource_info = $company_resources->getCompanyServiceSource(["pricing_resource",'cq_pricing_resource','spread_overage','account_overage']);

        if($company_resource_info['isCqNewService'] &&$mod_type != 'edit'){//重庆新套餐逻辑
            $service_servicePricing = new base_service_company_service_servicePricing();
            $selling = $service_servicePricing->GetFunParallelismSelling('point_job_refresh');
            if( $is_refresh && $company_resource_info['cq_release_point_job_refresh'] < 1 && ($company_resource_info['spread_overage'] + $company_resource_info['account_overage']) < $selling){
                echo json_encode(array ('status' => false, 'error' => "发布职位失败，请刷新后重试"));
                return;
            }
            if($is_refresh ){
                $job['refresh_time'] = date('Y-m-d H:i:s');
            }else{
                $job['refresh_time'] = '2014-01-01 00:00:00';
            }

        }
        if($company_resource_info['isNewService']){
            $service_qualite = new base_service_company_job_quality();
            $need_pay        = $service_qualite->isNeedPayIn24($this->_userid, $job_id);
            $is_pay_in24     = $need_pay?false:true;//24h内是否设置过精品职位
        }
        $is_stop_use = false;
        if($company_resource_info['isNewService']&&$is_new_service==1 && $mod_type != 'edit'){
            if($is_quality == 1){
                if( ($company_resource_info['point_job_boutique']-$company_resource_info['point_job_boutique_use'])<=0
                    &&!$is_pay_in24 || $company_resource_info['job_boutique_release'] <= 0){
                    $is_stop_use = true; //暂存职位
                }
            }else{
                if($company_resource_info['job_release_num'] <= 0){
                    $is_stop_use = true;
                }
                $service_quality    = new base_service_company_job_quality();
                $quality_job        = $service_quality->getJobIsQuality($job_id);
                if(!empty($quality_job)){
                    $service_quality->cancelJobQulity($this->_userid,$job_id); // 若选择发布免费职位，则取消以前的精品职位
                }
            }

        }
		$re_apply_type = base_lib_BaseUtils::getStr($path_data['txtReApplyType'], "int", 0);
		$txtBaseReApplyType = base_lib_BaseUtils::getStr($path_data['txtBaseReApplyType'], "int", 0);//该职位原来的 承诺职位类型
		
		if ($re_apply_type != 0) {
			//判断该职位是不是承诺职位 若是则判断该企业是不是能发布承诺职位
			$mustreply_status = $this->canAddMustReplyJob($job_id);
			switch ($mustreply_status) {
				case 2:
					echo header("Content-type:text/plain;charset=utf-8");
					echo json_encode(array ('status' => 'mustreplyinfo', 'message' => '您还未开通会员,不能发布承诺职位！'));
					
					return;
				case 3:
					echo header("Content-type:text/plain;charset=utf-8");
					echo json_encode(array ('status' => 'mustreplyinfo', 'message' => '您在禁用期，不能发布承诺职位！'));
					
					return;
				case 4:
					if ($txtBaseReApplyType == 0) { //若当前职位是承诺职位的时候 不需要判断承诺职位数已满的情况
						echo header("Content-type:text/plain;charset=utf-8");
						echo json_encode(array ('status' => 'mustreplyinfo', 'message' => '您的承诺职位数已满'));
						
						return;
					}
					break;
			}
		}
		
		$re_apply_types = array (0, 2, 5);
		if (!in_array($re_apply_type, $re_apply_types))
			$validator->addErr('请选择正确的简历回复承诺类型');
		
		//若修改了 承诺职位类型,判断该职位是否有待处理简历 todo
		if ($re_apply_type != $txtBaseReApplyType) {
			$statistics = $this->getNotReplyNum($job_id);
			$no_reply_num = $statistics[0]['no_reply_num'];
			if ($no_reply_num > 0) {
				echo header("Content-type:text/plain;charset=utf-8");
				echo json_encode(array ('status' => 'mustreplyinfo', 'message' => '该职位有待处理简历，不能开启简历回复承诺功能'));
				
				return;
			}
		}
		$job['re_apply_type'] = $re_apply_type;
		
		//是否在线沟通
		$job['allow_online_talk'] = base_lib_BaseUtils::getStr($path_data['param_allow_link'], 'int', '0');
		//发布人ID
		$job['account_id'] = base_lib_BaseUtils::getStr($path_data['param_account_id'], 'int', '');
		if (empty($job['account_id'])) {
			$validator->addErr("无有效发布人ID");
		}
		/*if ($job["allow_online_talk"] == 1) {
			$service_related = new base_service_hractivity_related();
			$related_info = $service_related->getRelatedByAccount($job['account_id'], "account_id");
			if (empty($related_info)) {
				$validator->addErr("你还没有使用企业APP，无法使用在线沟通功能。请关闭在线沟通功能，或下载企业APP登录并关联企业账号");
			}
		}*/
		
		/*==============判断该企业是否有行业类别===================*/
		$calling_mark = $this->matchCompanyCalling();
		if ($calling_mark) {
			echo header("Content-type:text/plain;charset=utf-8");
			echo json_encode(array ('status' => 'info', 'message' => '我们对行业做了一些调整，请在企业资料中重新选择后再操作！'));
			
			return;
		}

		$jobs_info = $service_job->getJob($job_id, "company_id");
		$company_id = $jobs_info['company_id'];
		
		if (!in_array($company_id, $company_resources->all_accounts))
			$validator->addErr("您没有权限操作该职位");
		//如果是老数据默认职位性质为
		if (empty($jobs_info['radJobType'])) {
			$path_data['radJobType'] = 1;
		}
		//同行认证的不能发布职位和修改职位
		list($is_audit, $audit_params) = $company_resources->getCompanyAuditStatus();
		$company_location_area = $company_resources->location_area;
//		if ($is_audit == 5 && $company_location_area != "NOT_CQ_CITY") { //同行认证的非重庆主城区的 可以发布职位
//			$validator->addErr("您还没有上传营业执照，不能修改职位");
//		}
		
		//单位ID
		$job['company_id'] = $company_id;
		
		//职位性质
		$service_job_type = new base_service_common_jobtype();
		$job['job_type'] = $validator->getEnum($path_data['radJobType'], $service_job_type->getJobtype(), '请选择职位性质');
		
		//职位名称
		$station = $validator->getNotNull($path_data['txtStation'], '请输入职位名称');
        $txtStationNum = $mod_type == 'edit' ? 30 : 20;
		$validator->getStr($path_data['txtStation'], 2, $txtStationNum, "名称2-{$txtStationNum}个字符");
		$job['station'] = $station;
		
		//岗位级别
		$service_job_level = new base_service_common_joblevel();
		$job['job_level'] = $validator->getEnum($path_data['hddJoblevel'], $service_job_level->getJobLevel(), '请选择岗位级别');
		
		// 职位部门
		$dept_name = base_lib_BaseUtils::getStr($path_data['hddJobDept'], 'string');
		$job['dept_name'] = $dept_name;
		
		//新版职位类别
		$main_jobsort = $validator->getNotNull($path_data['main_jobsort'], "请选择主要职位类别");
		$next_jobsort = base_lib_BaseUtils::getStr($path_data['next_jobsort'], 'string', '');
		$job['jobsort'] = $main_jobsort;
		$job['jobsort_ids'] = !empty($next_jobsort) ? $main_jobsort . "," . $next_jobsort : $main_jobsort;
		
		//招聘人数
		$validator->getNotNull($path_data['txtQuantity'], '请输入招聘人数');
		$job['quantity'] = $validator->getNum($path_data['txtQuantity'], 1, 999, '招聘人数为1～3位整数，且不能为0。');
		
		//工作地点
		$job['area_id'] = $validator->getStr($path_data['hddArea'], 0, 0, '请选择区域', false);

		$service_area = new base_service_common_area();
		if ($company_resources->account_type == "NotMemberTypeArea" || ($company_resources->location_area != "CQ_MAIN_CITY" && $current_company["com_level"] <= 1)) {
			$is_simple_area = $service_area->checkSimpleArea($job["area_id"], $current_company["area_id"]);
			if (!$is_simple_area) {
				$validator->addErr("您只能发布当前公司地区范围内的职位");
			}
		}
        $comservice_service = new base_service_company_service_comservice();
        $area_service = new base_service_common_area();
        if($company_resource_info['isNewService']){
            $area_type = $comservice_service->getComService($this->_userid, 'area_type')['area_type'];
            if($area_type){
                switch ($area_type) {
                    case '1':
                        $_status =  array ('status' => true, 'msg' => '不限');
                        break;
                    case '2':
                        $area_list = $area_service->getCQMainAreas();
                        $area_find = array ();
                        foreach ($area_list as $item) {
                            if ($item == '0300')
                                continue;
                            $tempFild = $area_service->getChildArea($item);
                            $area_find = array_merge($area_find, base_lib_BaseUtils::getProperty($tempFild, 'area_id'));
                        }
                        $area_list = array_unique(array_merge($area_list, $area_find));

                        $status = true;
                        $msg = "";
                        if (!in_array($job["area_id"], $area_list)) {
                            $status = false;
                            $msg = "您不能发布重庆主城以外的职位";
                        }

                        $_status =  array ('status' => $status, 'msg' => $msg);
                        break;
                    case '3':
                        $area_list = $area_service->getCQSmallCity();
                        $area_list1 = $area_service->getCQSmallCityAll();
                        $area_list = array_merge($area_list, $area_list1);
                        $area_find = array ();
                        foreach ($area_list as $item) {
                            $tempFild = $area_service->getChildArea($item);
                            $area_find = array_merge($area_find, base_lib_BaseUtils::getProperty($tempFild, 'area_id'));
                        }
                        $area_list = array_merge($area_list, $area_find);

                        $status = true;
                        $msg = "";
                        if (!in_array($job["area_id"], $area_list)) {
                            $status = false;
                            $msg = "您不能发布重庆区县以外的职位";
                        }

                        $_status =  array ('status' => $status, 'msg' => $msg);
                        break;
                    case '4':
                        $area_list = $area_service->getSCCityAll();
                        $status = true;
                        $msg = "";
                        if (strlen($job["area_id"]) >= 4) {
                            $cur_area = substr($job["area_id"], 0, 4);
                        }
                        if (!in_array($cur_area, $area_list)) {
                            $status = false;
                            $msg = "您不能发布四川以外的职位";
                        }

                        $_status = array ('status' => $status, 'msg' => $msg);
                        break;
                }
            }else{
                $service_servicePricing = new base_service_company_service_servicePricing();
                $SC_new_service         =  $service_servicePricing->GetCompanyDefaultPricing($this->_userid,true);
                switch ($SC_new_service['area_type']) {
                    case '1':
                        $_status =  array ('status' => true, 'msg' => '不限');
                        break;
                    case '2':
                        $area_list = $area_service->getCQMainAreas();
                        $area_find = array ();
                        foreach ($area_list as $item) {
                            if ($item == '0300')
                                continue;
                            $tempFild = $area_service->getChildArea($item);
                            $area_find = array_merge($area_find, base_lib_BaseUtils::getProperty($tempFild, 'area_id'));
                        }
                        $area_list = array_unique(array_merge($area_list, $area_find));

                        $status = true;
                        $msg = "";
                        if (!in_array($job["area_id"], $area_list)) {
                            $status = false;
                            $msg = "您不能发布重庆主城以外的职位";
                        }

                        $_status =  array ('status' => $status, 'msg' => $msg);
                        break;
                    case '3':
                        $area_list = $area_service->getCQSmallCity();
                        $area_list1 = $area_service->getCQSmallCityAll();
                        $area_list = array_merge($area_list, $area_list1);
                        $area_find = array ();
                        foreach ($area_list as $item) {
                            $tempFild = $area_service->getChildArea($item);
                            $area_find = array_merge($area_find, base_lib_BaseUtils::getProperty($tempFild, 'area_id'));
                        }
                        $area_list = array_merge($area_list, $area_find);

                        $status = true;
                        $msg = "";
                        if (!in_array($job["area_id"], $area_list)) {
                            $status = false;
                            $msg = "您不能发布重庆区县以外的职位";
                        }

                        $_status =  array ('status' => $status, 'msg' => $msg);
                        break;
                    case '4':
                        $area_list = $area_service->getSCCityAll();
                        $status = true;
                        $msg = "";
                        if (strlen($job["area_id"]) >= 4) {
                            $cur_area = substr($job["area_id"], 0, 4);
                        }
                        if (!in_array($cur_area, $area_list)) {
                            $status = false;
                            $msg = "您不能发布四川以外的职位";
                        }

                        $_status = array ('status' => $status, 'msg' => $msg);
                        break;
                }
            }
            if($_status && !$_status['status']){
                $validator->addErr($_status['msg']);
            }
        }elseif ($current_company["com_level"] > 1) {
			$areatype = $comservice_service->areatypecheck($company_resources->main_company_id, $job["area_id"]);
			if (!$areatype['status']) {
				$validator->addErr($areatype['msg']);
			}
		}

		
		//详细地址描述
		$job['add_info'] = $validator->getNotNull($path_data['txtAddInfo'], "请完善职位详细地址");
		$map_x = base_lib_BaseUtils::getStr($path_data['map_x'], 'string', '');
		$map_y = base_lib_BaseUtils::getStr($path_data['map_y'], 'string', '');
		$address_id = base_lib_BaseUtils::getStr($path_data['address_id'], 'int', '0');
		
		if ($address_id >= 0)
			$job['address_id'] = $address_id;
		if ($map_x && $map_y) {
			$job['map_x'] = $map_x;
			$job['map_y'] = $map_y;
		} else {
			array_push($validator->err, '请完善职位地址坐标');
			$validator->has_err = true;
		}
		
		//定额工资，如果为0则是定额工资，如果不为0则为底薪+提成
		$salary_type = base_lib_BaseUtils::getStr($path_data['rd'], 'int', 0);
		$job['salary_type'] = $salary_type;
		
		//新需求，后台模拟登录的校招企业，发布校招职位可以不填薪资
		//是否接受应届生
		$job['allow_graduate'] = base_lib_BaseUtils::getStr($path_data['chkNewGraduate'], 'int', 0);
		if ($job['allow_graduate'] == 1 && base_lib_BaseUtils::nullOrEmpty($path_data['main_professionalSort']) && base_lib_BaseUtils::nullOrEmpty($path_data['professionRemark'])) {
			array_push($validator->err, '请选择应届生专业');
			$validator->has_err = true;
		}
		if ($job['allow_graduate'] == 1) {
			// 应届生专业
			if ($path_data['main_professionalSort'] == '00') {
				$job['graduate_profession_ids'] = '';
				$job['graduate_profession_remark'] = '';
			} else {
				$job['graduate_profession_ids'] = base_lib_BaseUtils::getStr($path_data['main_professionalSort'], 'string', '');
				$job['graduate_profession_remark'] = base_lib_BaseUtils::getStr($path_data['professionRemark'], 'string', '');
			}
		} else {
			$job['graduate_profession_ids'] = '';
			$job['graduate_profession_remark'] = '';
		}
		
		
		$can_pub_no_salary = ($company_resources->account_type == "NotMemberTypSchool" && base_lib_BaseUtils::getCookie("bossuser"));
		
		if ($salary_type == 0) {
			if ($can_pub_no_salary && $job['allow_graduate'] && empty($path_data['hddSalary1']) && empty($path_data['hddSalary1End'])) {
				$minsalary = 0;
				$maxsalary = 0;
			} else {
				//薪资最小值
				$minsalary = $validator->getNotNull($path_data['hddSalary1'], '请输入薪资待遇');
				$validator->getNum($path_data['hddSalary1'], 500, 'max', '薪资待遇不能低于500');
				
				//薪资最大值
				$maxsalary = $validator->getNotNull($path_data['hddSalary1End'], '请输入薪资待遇');
				$validator->getNum($path_data['hddSalary1End'], 'min', 100000, '薪资待遇不能低于100000');
				
				if ($minsalary > $maxsalary)
					list($minsalary, $maxsalary) = [$maxsalary, $minsalary];
			}
			$job['salary_month_num'] = $path_data['moreSalarySelect'];
			$job['min_salary'] = $minsalary;
			$job['max_salary'] = $maxsalary;
		} else {
			if ($can_pub_no_salary && $job['allow_graduate'] && empty($path_data['hddSalary1']) && empty($path_data['hddSalary1End'])) {
				$job['base_min_salary'] = 0;
				$job['base_max_salary'] = 0;
				$job['min_salary'] = 0;
				$job['max_salary'] = 0;
				$job['salary_month_num'] = 0;
			} else {
				//薪资最小值
				$minsalary = $validator->getNotNull($path_data['hddSalary2'], '请输入薪资待遇');
				$validator->getNum($path_data['hddSalary2'], 500, 'max', '薪资待遇不能低于500');
				
				//薪资最大值
				$maxsalary = $validator->getNotNull($path_data['hddSalary2End'], '请输入薪资待遇');
				$validator->getNum($path_data['hddSalary2End'], 'min', 100000, '薪资待遇不能超过100000');
				if ($minsalary > $maxsalary)
					list($minsalary, $maxsalary) = [$maxsalary, $minsalary];
				
				if (($maxsalary / $minsalary) > 2)
					$validator->addErr('最大薪资不能超过最低工资的2倍');

				$job['salary_month_num'] = 0;
				$job['base_min_salary'] = $minsalary;
				$job['base_max_salary'] = $maxsalary;
				
				//平均工资
				//平均薪资最小值
				$minsalary_svg = $validator->getNotNull($path_data['hddSalary3'], ' 请输入薪资待遇');
				$validator->getNum($path_data['hddSalary3'], 500, 'max', '薪资待遇不能低于500');
				
				//平均最大值
				$maxsalary_svg = $validator->getNotNull($path_data['hddSalary3End'], '请输入薪资待遇');
				$validator->getNum($path_data['hddSalary3End'], 'min', 100000, '薪资待遇不能超过100000');
				if ($minsalary_svg > $maxsalary_svg)
					list($minsalary_svg, $maxsalary_svg) = [$maxsalary_svg, $minsalary_svg];
				
				if (($maxsalary_svg / $minsalary_svg) > 3) {
					$validator->addErr('最大薪资不能超过最低薪资的3倍');
				}
				
				if ($maxsalary_svg <= $maxsalary) {
					$validator->addErr('平均收入必须大于底薪');
				}
				
				if ($minsalary_svg <= $minsalary) {
					$validator->addErr('平均收入必须大于底薪');
				}
				
				$job['min_salary'] = $minsalary_svg;
				$job['max_salary'] = $maxsalary_svg;
			}
		}
		
		//获得福利
		$job['other_reward_ids'] = $path_data['hidDefaultReward'];
		
		//获得其他福利
		$job['other_reward'] = $path_data['hidOtherReward'];
		
		//获得岗位特点
		$job['job_feature_ids'] = $path_data['hidDefaultFea'];
		
		//获得其他岗位特点
		$job['job_other_feature'] = $path_data['hidOtherFea'];
		
		//岗位职责
		$job['content'] = $validator->getNotNull($path_data['txtContent'], '请输入岗位职责');
		$validator->getStr($path_data['txtContent'], 20, 0, '太过简单了吧，最少20个字');
		$validator->getStr($path_data['txtContent'], 0, 2000, '不能超过2000个字');
		
		
		//有效期
		$job['valid_days'] = $validator->getNotNull($path_data['txtValidDays'], '请输入有效期');
		$validator->getNum($path_data['txtValidDays'], 1, 60, '请输入1-60之间的整数');
		
		//截至时间
		$job['end_time'] = date('Y-m-d H:i:s', strtotime("+{$job['valid_days']} day", time()));
		
		//职位状态
		$service_jobstatus = new base_service_common_jobstatus();
        $job['status'] = $service_jobstatus->use;
        if($is_stop_use){
            $job['status'] = $service_jobstatus->stop_use;
        }

		//任职资格
		$path_data['txtOtherNeed'] = trim($path_data['txtOtherNeed']);
		$other_need = base_lib_BaseUtils::getStr($path_data['txtOtherNeed'], 'string');
		$job['other_need'] = $other_need;
		if (!empty($path_data['txtOtherNeed'])) {
//			$validator->getStr($path_data['txtOtherNeed'], 20, 0, '太过简单了吧，最少20个字');
			$validator->getStr($path_data['txtOtherNeed'], 0, 2000, '补充说明不能超过2000个字');
		}
		
		//工作年限
		$workyear = base_lib_BaseUtils::getStr($path_data['hddWorkyear'], 'string', '0');
		$service_workyear = new base_service_common_workyear();
		$wkdes = $service_workyear->getWorkyear($workyear);
		if (!empty($workyear)) {
			if ($workyear == 'all') {
				$workyear = 0;
			} else if (empty($wkdes[ $workyear ])) {
				$validator->addErr("工作年限不正确");
			}
		} else {
			$validator->addErr("工作年限不正确");
		}
		
		$job['work_year_id'] = $workyear;
		
		//学历
		$degree = base_lib_BaseUtils::getStr($path_data['hddDegree'], 'string', '0');
		$service_degree = new base_service_common_degree();
		$ddes = $service_degree->getDegree($degree);
		if (!empty($degree)) {
			if ($degree == 'all') {
				$degree = 0;
			} else if (empty($ddes)) {
				$validator->addErr("学历不正确");
			}
		} else {
			$validator->addErr("学历不正确");
		}
		$job['degree_id'] = $degree;
		
		//性别
		$sex = base_lib_BaseUtils::getStr($path_data['hddSex'], 'string', '0');
		if (strcmp($sex, '0') != 0) {
			$service_sex = new base_service_common_sex();
			$sdes = $service_sex->getSex($service_sex);
			if (empty($sdes)) {
				$validator->addErr('性别不正确');
			}
		}
		$job['sex'] = $sex;
		
		//年龄最小值
		$agelower = base_lib_BaseUtils::getStr($path_data['hddAge1'], 'int', 0);
		if ($agelower != 0) {
			$validator->getNum($path_data['hddAge1'], 16, 60, '年龄最小值不正确');
		}
		
		//年龄最大值
		$ageupper = base_lib_BaseUtils::getStr($path_data['hddAge2'], 'int', 0);
		if ($ageupper != 0) {
			$validator->getNum($path_data['hddAge2'], 16, 60, '年龄最大值不正确');
		}
		
		if ($agelower > $ageupper && $ageupper != 0)
			list($agelower, $ageupper) = [$ageupper, $agelower];
		
		$job['age_lower'] = $agelower;
		$job['age_upper'] = $ageupper;
		
		//外语要求
		$language = base_lib_BaseUtils::getStr($path_data['hddLanguage'], 'string', '0');
		if (strcmp($language, '0') != 0) {
			$service_language = new base_service_common_languagetype();
			$ldes = $service_language->getLanguageType($language);
			if (empty($ldes)) {
				$validator->addErr('外语要求不正确');
			}
		}
		
		$job['language_id'] = $language;
		// 是否开启自动过滤
		$is_automatic = base_lib_BaseUtils::getStr($path_data['hddAutomatic'], 'int', 0);
		$job['auto_filter'] = $is_automatic;
		
		// 是否显示联系方式
		$isshowlinkway = base_lib_BaseUtils::getStr($path_data['showLinkway'], 'int', 0);
		$validator->getNum($path_data['showLinkway'], 0, 1, '请选择联系方式');
		
		// 是否使用新联系方式
		$newlinkway = base_lib_BaseUtils::getStr($path_data['newLinkway'], 'int', 0);
		$validator->getNum($path_data['newLinkway'], 0, 5, '请选择联系方式');
		
		// 新联系方式数量
		$newlinkwaycount = base_lib_BaseUtils::getStr($path_data['newLinkWayCount'], 'int', 1);
		$validator->getNum($path_data['newLinkWayCount'], 1, 3, '新联系方式数量异常');
		
		//联系人和联系方式数组
		
		$linkmantel_arr = array ();
		if ($isshowlinkway != 0) {//显示联系方式
			$job['open_linkway'] = 1;
			if ($newlinkway == 1) {//使用新联系方式
				$job['self_linkway'] = 1;
				for ($i = 0; $i < $newlinkwaycount; $i++) {
					$job['linkman'] = $validator->getStr($path_data[ 'txtLinkMan' . ($i + 1) ],6,13,'联系人电话限制6-13位数字');
					$job['link_tel'] = '';
					if (!empty($path_data[ 'txtLinkTel' . ($i + 1) ]))
						$job['link_tel'] = $validator->getStr($path_data['txtLinkTel1'], 1, 6, '请填写1-6位数字的分机号');
					break;
				}
			} else {
				//0 使用企业联系方式(以前的) 1 使用新联系方式  4 发布人手机号 5 发布人办公电话
				$job['self_linkway'] = $newlinkway;
			}
		} else {//不显示联系方式
			$job['open_linkway'] = 0;
			$job['self_linkway'] = 0;
		}
		
		
		//是否需要发送到邮箱
		$istomail = base_lib_BaseUtils::getStr($path_data['toMail'], 'int', 0);
		$validator->getNum($path_data['toMail'], 0, 1, '是否需要发送到邮箱异常');
		
		//新邮箱数量
		$emailcount = base_lib_BaseUtils::getStr($path_data['emailCount'], 'int', 1);
		$validator->getNum($path_data['emailCount'], 1, 3, '新联系方式数量异常');
		
		//新邮箱数组
		$emails_arr = array ();
		$service_allow_email_type = new base_service_company_job_joballowemailtype();
		if ($istomail != 0) {//需要发送到邮箱
			
			//是发到系统邮箱还是其他新邮箱
			$emailtype = base_lib_BaseUtils::getStr($path_data['emailType'], 'int', 2);
			$validator->getNum($path_data['emailType'], 2, 3, '请选择接收简历的邮箱');
			
			if ($emailtype == 2) {
				$job['allow_email'] = $service_allow_email_type->toaccountandemail;
				$job['other_email'] = '';
			} else {
				$job['allow_email'] = $service_allow_email_type->toaccountandotheremail;
				for ($j = 0; $j < $emailcount; $j++) {
					$email = $validator->getEmail($path_data[ 'email' . ($j + 1) ], '第' . ($j + 1) . '个邮箱不正确');
					array_push($emails_arr, $email);
				};
				
				$job['other_email'] = implode(',', $emails_arr);
			}
		} else {
			$job['allow_email'] = $service_allow_email_type->toaccount;
			$job['other_email'] = '';
		}
        
        //职位标签
        $tag_names          = [];
        $skill_tag_ids      = [];
        $ability_tag_ids    = [];
        $custom_tag_ids     = [];
        if(!empty($_POST["hddJobTags"])){
            //保存职位标签
            $hddJobTags = json_decode($_POST["hddJobTags"],true);
            if(!empty($hddJobTags)){
                foreach($hddJobTags as $tag){
                    if($tag['tag_type']==1){
                        $skill_tag_ids[]=$tag['id'];
                        $tag_names[]=$tag['tag_name'];
                    }
                    if($tag['tag_type']==2){
                        $ability_tag_ids[]=$tag['id'];
                        $tag_names[]=$tag['tag_name'];
                    }
                    if($tag['tag_type']==3){
                        $custom_tag_ids[]=$tag['id'];
                        $tag_names[]=$tag['tag_name'];
                    }
                }
            }
        }
        if(!empty($tag_names)){
            $job["tag_names"] = implode(",",$tag_names);
        }else{
            $job["tag_names"] = "";
        }
        
        if ($validator->has_err) {
            echo header("Content-type:text/plain;charset=utf-8");
            echo $validator->toJsonWithHtml();

            return;
        }
		//如果是审核未通过职位，修改check_state状态为审核中
		$job['form_src'] = 'nopassrepulic';//给个标志-》 $service_job->modifyJob 判断修改
		//职位修改 应届生审核状态重置为待审核
		$job['campus_recruitment_status'] = 0;

        $SCCityAll_data = $service_area->getSCCityAll();
        $area_tmp = substr($job['area_id'], 0, 4);
        $job_resule = $service_job->modifyJob($job_id, $job, $linkmantel_arr, $emails_arr);
        if($mod_type != 'edit' && $job_resule == 6){
            if($is_quality==1 && $company_resource_info['isNewService'] && !$is_stop_use){
                $status = true;
                //设置精品职位
                $resource_consume = $company_resources->consume('pub_boutique_job', $params=['job_id'=>$job_id, "company_id"=>$this->_userid]);
                list($status, $code, $params) = $resource_consume;
                if(!$status){
                    $validator->addErr($params);
                }
            }
        }

        if ($validator->has_err) {
            echo header("Content-type:text/plain;charset=utf-8");
            echo $validator->toJsonWithHtml();

            return;
        }
        if($is_refresh && $company_resource_info['isCqNewService']){ //重庆新套餐刷新
            //获取职位状态
			$job_info_temp = $service_job->getjob($job_id,"job_id,check_state");
			if($job_info_temp['check_state'] == 4){
				$service_company_job_jobrefreshaudit = new base_service_company_job_jobrefreshaudit();
				$service_company_job_jobrefreshaudit->setJobRefreshAudit($job_id,$account_id);
			}else{
				$params = array(
					'company_id' => $this->_userid,
					'job_id'	 => $job_id,
					'consume_type'	=> 'refresh',
					'batch'			=> false
				);
				$consume_result = $company_resources->consume("cq_setmeal_consume",$params);
				if(!$consume_result['result']){
					$validator->addErr($consume_result['msg']);
				}
			}

        }


        if ($validator->has_err) {
            echo header("Content-type:text/plain;charset=utf-8");
            echo $validator->toJsonWithHtml();

            return;
        }

		/**======修改职位后不用刷新  2016-11-17  职位刷新和企业刷新分科======**/
//    	$service_job->refreshJob($this->_userid, $job_id);
		
		//----------记录修改内容 start-------
		$service_job = new base_service_company_job_job();
		$current_job = $service_job->getJob($job_id, 'job_id,job_type,station,quantity,add_info,status,company_id,'
		                                           . 'is_salary_show,period,period_fee,other_reward_ids,content,valid_days,end_time,other_need,'
		                                           . 'allow_graduate,open_linkway,new_linkway,send_email,job_level,jobsort_ids,area_id,min_salary,'
		                                           . 'max_salary,work_year_id,degree_id,sex,age_lower,age_upper,language_id,allow_email,other_email,'
		                                           . 'open_linkway,self_linkway,linkman,link_tel,linkman2,link_tel2,linkman3,link_tel3,base_min_salary,'
		                                           . 'base_max_salary,salary_type,other_reward,dept_name,job_feature_ids,job_other_feature,job_flag,auto_filter,re_apply_type,account_id,allow_online_talk,graduate_profession_ids');
		
		$log_message = '<br/>修改内容：';
		//职位类别
		
		if (!empty($job['jobsort'])) {
			if ($job['jobsort'] != $current_job['jobsort']) {
				
				$log_message .= "<br/>主职位类别：由【】 修改为【】";
			}
			
			
		}
		
		//职位名称
		//岗位级别
		//招聘人数
		//工作地点
		//薪资待遇
		//岗位职责
		//福利待遇
		//岗位特点
		//有效期
		//工作年限
		//最低学历
		//性别要求
		//年龄要求
		//语言要求
		//任职资格
		//职位发布人
		//是否允许在线沟通
		//联系方式
		//自动过滤简历
		//邮箱接收简历
		//简历回复承诺
		
		//----------记录修改内容 end---------
		
		
		if ($job_resule === 6) {


			//---------添加操作日志--------
			$service_job = new base_service_company_job_job();
			$job_info = $service_job->getJob($job_id, 'job_id,station,account_id,station,jobsort');
            //修改职位标签
            $service_job_tag = new base_service_company_job_infojobtag();
            //先删除 然后再添加
            $service_job_tag->deleteTags($job_id, $this->_userid);
            if(!empty($tag_names)){
                $tag_items       = [];
                $tag_items["job_id"]        = $job_info["job_id"];
                $tag_items["company_id"]    = $this->_userid;
                $tag_items["account_id"]    = $job_info["account_id"];
                $tag_items["station"]       = $job_info["station"];
                $tag_items["jobsort_id"]    = $job_info["jobsort"];
                $tag_items["skill_tag_ids"]     = !empty($skill_tag_ids)  ? implode(",", $skill_tag_ids) : "";
                $tag_items["ability_tag_ids"]     = !empty($ability_tag_ids)  ? implode(",", $ability_tag_ids) : "";
                $tag_items["custom_tag_ids"]     = !empty($custom_tag_ids)  ? implode(",", $custom_tag_ids) : "";
                $tag_items["tag_names"]     = !empty($tag_names)  ? implode(",", $tag_names) : "";
                $service_job_tag->addTags($tag_items);
            }
			$common_oper_type = new base_service_common_account_accountoperatetype();
			$service_oper_log = new base_service_company_companyaccountlog();
			$common_oper_src_type = new base_service_common_account_accountlogfrom();
			$insertItems = array (
				"company_id"   => $this->_userid,
				"source"       => $common_oper_src_type->website,
				"account_id"   => base_lib_BaseUtils::getCookie('accountid'),
				"operate_type" => $common_oper_type->job_mod,
				"content"      => "修改职位，职位：" . $job_info['station'] . '。',
				"create_time"  => date("Y-m-d H:i:s", time())
			);
			$service_oper_log->addLogToMongo($insertItems);
			$this->setOuterStop($job_id);
			//-------------END------------
			echo json_encode(array ('status' => 'success'));
			
			return;
		} else {
			echo json_encode(array ('status' => 'fail'));
			
			return;
		}
	}
	
	
	/*
	 * 设置为在线沟通
	 * */
	public function pagesetOnlineTalk($inPath) {
		
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_ids = base_lib_BaseUtils::getStr($path_data['hddjobID'], 'array', '');
		//新职位发布默认为当前登录账户
		
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		
		//职位ID
		if (empty($job_ids)) {
			$error = array ('msg' => '请选择职位！', 'status' => false);
			echo json_encode($error);
			
			return;
		}
		
		$service_related = new base_service_hractivity_related();
		$related_info = $service_related->getRelatedByAccount($account_id, "account_id");
		//根据企业账号获取求职者关联的企业账号信息
		if (empty($related_info)) {
			$error = array ('msg' => '您暂未关联到企业APP！', 'status' => false);
			echo json_encode($error);
			
			return;
			
		}

		//子账号判断，共享模式的账号不能修改分配模式的子账号职位，分配模式子账号不能修改共享模式的子账号职位
		$service_job = new base_service_company_job_job();
		$job_info = $service_job->getJobs($job_ids, 'job_id,station,account_id');

		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$company_id = $this->_userid;
		$company_resources  = base_service_company_resources_resources::getInstance($company_id,true,$account_id);
		$companyresources = $company_resources->getCompanyServiceSource(['account_resource']);

		//查询所有子账号信息
		$service_company_account = new base_service_company_account();
		$account_info = $service_company_account->getAccountCompany($company_id,"account_id,company_id,user_name,resource_type");
		$account_info = base_lib_BaseUtils::array_key_assoc($account_info,"account_id");
		$error_msg = array();
		foreach((array)$job_info as $key => $value){
			$job_resource_type = $account_info[$value['account_id']]['resource_type'];
			$user_name = $account_info[$value['account_id']]['user_name'];
			if($companyresources['resource_type'] == 2){
				//分配模式,查询所选职位是否包含共享模式职位和其他分配模式账号发布的职位
				if($job_resource_type == 1){
					$error = "职位:{$value['station']}由账号:{$user_name} 发布，请登录此账号进行操作";
					array_push($error_msg,$error);
					continue;
				}else{
					if($value['account_id'] != $account_id){
						$error = "职位:{$value['station']} 由分配子账号:{$user_name}发布，请登录此账号进行操作";
						array_push($error_msg,$error);
						continue;
					}
				}
			}else{
				//共享模式,验证所选职位是否包含分配模式职位
				if($job_resource_type == 2){
					$error = "职位:{$value['station']} 由分配子账号:{$user_name}发布，请登录此账号进行操作";
					array_push($error_msg,$error);
					continue;
				}
			}
		}
		if($error_msg){
			$error1 = array ('msg' => implode("</br>",$error_msg), 'status' => false);
			echo json_encode($error1);exit();
		}

		$jobs = base_lib_BaseUtils::array_key_assoc($job_info, 'job_id');
		$account_ids = base_lib_BaseUtils::getPropertys($job_info,"account_id");
		$account_ids = array_unique($account_ids);
		//账号是否绑定app
		$related = $service_related->getRelatedByAccounts($account_ids, "account_id")->items;
		$related = base_lib_BaseUtils::array_key_assoc($related,"account_id");
		$bind_error = array();
		foreach($job_info as $k => $v){
			if(empty($related[$v['account_id']])){
				array_push($bind_error,"职位：{$v['station']}的发布人未绑定app");
			}
		}
		if($bind_error){
			$error1 = array ('msg' => implode("</br>",$bind_error), 'status' => false);
			echo json_encode($error1);exit();
		}
		$log_message = '';
		$account_number = 0;
		foreach ((array)$jobs as $val) {
			//if ($val['account_id'] == $account_id) {
				$service_job->modifyJobProperty($val['job_id'], $val['company_id'], array ('allow_online_talk' => 1));
				$log_message .= " 职位：" . $jobs[ $val['job_id'] ]['station'] . "，允许在线沟通；";
				$account_number++;
			//}
		}
		
		//---------添加操作日志--------
		$common_oper_type = new base_service_common_account_accountoperatetype();
		$service_oper_log = new base_service_company_companyaccountlog();
		$common_oper_src_type = new base_service_common_account_accountlogfrom();
		$insertItems = array (
			"company_id"   => $this->_userid,
			"source"       => $common_oper_src_type->website,
			"account_id"   => base_lib_BaseUtils::getCookie('accountid'),
			"operate_type" => $common_oper_type->job_update_onlinetalk,
			"content"      => "批量设置允许在线沟通，详情：" . $log_message,
			"create_time"  => date("Y-m-d H:i:s", time())
		);
		$service_oper_log->addLogToMongo($insertItems);
		
		//-------------END------------
		
//		if ($account_number == 0) {
//			echo json_encode(array ('msg' => '该账号暂未发布职位，设置允许在线沟通失败！', 'status' => false));
//
//			return;
//		}
		
		echo json_encode(array ('msg' => '您发布的' . $account_number . '个职位，设置允许在线沟通成功！', 'status' => true));
		
	}
	
	/**
	 * 判断是否能发布职位
	 * 0   表示可以发布职位
	 * 1   表示会员级别不够
	 * 2   表示会员期已过期
	 * 3   发布职位数已满
	 */
	private function _isCanSueJob($companyID) {
		$company_resources = base_service_company_resources_resources::getInstance($companyID);
		$company_resource_info = $company_resources->getCompanyServiceSource(["default_job_num", "has_pub_job_num"]);
		$default_job_num = (int)$company_resource_info["default_job_num"];
		$current_job_num = (int)$company_resource_info["has_pub_job_num"];
		if ($default_job_num <= $current_job_num) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * 获取职位模板
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	public function pageJobLoadTemplates($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_name = base_lib_BaseUtils::getStr($path_data['jobname'], 'string', '');
		$jobsort_id = base_lib_BaseUtils::getStr($path_data['jobsortid'], 'string', '');
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', '');
		$company_templates_arr = array ();
		if (!empty($job_name)) {
			$split_names = SSplitWord::split2($job_name);
			
			$service_jobcontenttemplate = new base_service_company_job_jobcontenttemplates_jobcontenttemplate();
			$jobcontent_templates = $service_jobcontenttemplate->getTemplateListByName($job_name, $split_names, 5);
//			$jobcontent_templates = $service_jobcontenttemplate->getTemplateListByJobsort($jobsort_id);
			
			if (!empty($jobcontent_templates)) {
				foreach ($jobcontent_templates as $item => $v) {
					$company_templates_json['template_id'] = $v['template_id'];
					$company_templates_json['template_name'] = $v['template_name'];
					array_push($company_templates_arr, $company_templates_json);
				}
			}
		}
		//薪酬查询
		if (!empty($jobsort_id)) {
			$service_average_salary = new base_service_person_averagesalary();
			$average_salary_job = $service_average_salary->getSalaryByJobsortId($jobsort_id, 'jobsort_name,job_min_salary,job_max_salary,person_salary,resume_num,job_num');
			if (!empty($average_salary_job)) {
				$base_service_common_jobsort = new base_service_common_jobsort();
				$jobsort_name = $base_service_common_jobsort->getJobsort($jobsort_id, true);
				$average_salary_job['jobsort_name'] = $jobsort_name;
				$average_salary_job['is_null'] = '1';
			}
		}
		$service_company_job_job = new base_service_company_job_job();
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$job_num = $service_company_job_job->getJobsortJobNumber($this->_userid,$account_id,$jobsort_id,$job_id);

		echo json_encode(array ('content' => $company_templates_arr,'job_num' => $job_num, 'salarydata' => $average_salary_job));
		
		return;
	}

	
	/**
	 * 获取职位模板的岗位职责或任职资格
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	public function pageGetTemplateContentOrOtherNeed($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$template_id = base_lib_BaseUtils::getStr($path_data['templateid'], 'int', 0);
		$field = base_lib_BaseUtils::getStr($path_data['field'], 'string', 'content');
		if ($template_id == 0) {
			echo json_encode(array ('error' => '获取模板内容参数失败'));
			
			return;
		}
		$service_jobcontenttemplate = new base_service_company_job_jobcontenttemplates_jobcontenttemplate();
		$template = $service_jobcontenttemplate->getJobContentTemplateByID($template_id, $field);
		if (!empty($template)) {
			echo json_encode(array ('content' => $template[ $field ]));
			
			return;
		}
		
		return;
	}
	
	/**
	 * 获取推荐职位类别
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	public function pageGetRecommendJobsort($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$station = base_lib_BaseUtils::getStr($path_data['station'], 'string', '');
		if (empty($station) || strlen($station) < 2) {
			echo json_encode(array ('success' => false));
			
			return;
		}
		$service_company = new base_service_company_company();
		$company_callingids = $service_company->getCompany($this->_userid, true, 'calling_ids');
		if (empty($company_callingids) || count(explode(',', $company_callingids['calling_ids'])) == 0) {
			echo json_encode(array ('success' => false));
			
			return;
		}
		$company_calling_arr = explode(',', $company_callingids['calling_ids']);
		$company_calling = $company_calling_arr[0];
		
		$recommend_jobsort = $this->getRecommendJobsortList($station, $company_calling, 5);
		if (empty($recommend_jobsort) || count($recommend_jobsort) == 0) {
			echo json_encode(array ('success' => false));
			
			return;
		}
		$jobsorts = array ();
		foreach ($recommend_jobsort as $item => $value) {
			$jobsort_item = array ();
			$jobsort_item['jobsortID'] = $item;
			$jobsort_item['jobsortName'] = $value;
			array_push($jobsorts, $jobsort_item);
		}
		echo json_encode(array ('success' => true, 'jobsorts' => $jobsorts));
		
		return;
	}
	
	/**
	 * 获取推荐的职位类别数组
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	private function getRecommendJobsortList($station, $company_calling, $count) {
		$new_jobsorts_arr = array ();
		//获取所有职位类别
		$service_jobsort = new base_service_common_jobsort();
		$service_all_jobsort = $service_jobsort->getAllJobsort();
		// 获取职位名称与职位类别名称相等的数据
		$same_jobname = array ();
		foreach ($service_all_jobsort as $item => $value) {
			if (strlen($value['jobsort']) > 2 && strcmp(substr($value['jobsort'], -2), '00') !== 0 && strcmp($value['jobsort_name'], $station) === 0) {
				$same_jobname[ $item ] = $value['jobsort_name'];
			}
		}
		foreach ($same_jobname as $item => $value) {
			$is_exist = false;
			foreach ($new_jobsorts_arr as $ite => $val) {
				if (strcmp($val, $value) === 0) {
					$is_exist = true;
					continue;
				}
			}
			if (!$is_exist) {
				$new_jobsorts_arr[ $item ] = $value;
				if (count($new_jobsorts_arr) >= $count) {
					return $new_jobsorts_arr;
				}
			}
		}
		// 获取职位名称包含于职位类别名称中的数据
		$station_in_jobsort = array ();
		foreach ($service_all_jobsort as $item => $value) {
			if (strlen($value['jobsort']) > 2 && strcmp(substr($value['jobsort'], -2), '00') !== 0 && strcmp($value['jobsort_name'], $station) !== 0 && preg_match("/\\w*" . $this->getRegexString($station) . "\\w*/", $value['jobsort_name'])) {
				$station_in_jobsort[ $item ] = $value['jobsort_name'];
			}
		}
		foreach ($station_in_jobsort as $item => $value) {
			$is_exist = false;
			foreach ($new_jobsorts_arr as $ite => $val) {
				if (strcmp($val, $value) === 0) {
					$is_exist = true;
					continue;
				}
			}
			if (!$is_exist) {
				$new_jobsorts_arr[ $item ] = $value;
				if (count($new_jobsorts_arr) >= $count) {
					return $new_jobsorts_arr;
				}
			}
		}
		// 获取职位类别名称包含于职位名称中的数据
		$jobsort_in_station = array ();
		foreach ($service_all_jobsort as $item => $value) {
			if (strlen($value['jobsort']) > 2 && strcmp(substr($value['jobsort'], -2), '00') !== 0 && strcmp($value['jobsort_name'], $station) !== 0 && preg_match("/\\w*" . $this->getRegexString($value['jobsort_name']) . "\\w*/", $station)) {
				$jobsort_in_station[ $item ] = $value;
			}
		}
		foreach ($jobsort_in_station as $item => $value) {
			$is_exist = false;
			foreach ($new_jobsorts_arr as $ite => $val) {
				if (strcmp($val, $value) === 0) {
					$is_exist = true;
					continue;
				}
			}
			if (!$is_exist) {
				$new_jobsorts_arr[ $item ] = $value;
				if (count($new_jobsorts_arr) >= $count) {
					return $new_jobsorts_arr;
				}
			}
		}
		
		return $new_jobsorts_arr;
	}
	
	/**
	 * 含正则表达式的字符串转义
	 * Enter description here ...
	 * @param unknown_type $str
	 */
	private function getRegexString($str) {
		if (empty($str)) {
			return '';
		}
		$regstring = '[]【】()（）{}｛｝^$|?？*+\\/.';
		$chr = array ();
		for ($i = 0; $i < mb_strlen($str); $i++) {
			array_push($chr, mb_substr($str, $i, 1));
		}
		$temp = '';
		$laststring = '';
		for ($j = 0; $j < count($chr); $j++) {
			$temp = $chr[ $j ];
			if (strstr($regstring, $chr[ $j ]) !== false) {
				$temp = str_replace($temp, "\\{$temp}", $temp);
			}
			$laststring .= $temp;
		}
		
		return $laststring;
	}
	
	/**
	 * [_jobPubFail 发布职位失败页面]
	 * @return [type] [description]
	 */
	private function _jobPubFail($is_new_service=false,$default_job_num=0) {
        $account_id = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$account_id);

		$this->_aParams['member'] = $company_resources->isMember();
		$this->_aParams['is_new_service'] = $is_new_service;
		$this->_aParams['account_type'] = $company_resources->account_type;
        $service_company = new base_service_company_company();
        $company_info = $service_company->getCompany($this->_userid, 1, 'site_type,com_level');

        $this->_aParams['site_type'] = $company_info['site_type'];
        $this->_aParams['com_level'] = $company_info['com_level'];
        $companyStateService = new base_service_company_comstate();
		$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
		
		$companyHeapService = new base_service_company_netheap();
		$companyHeap = $companyHeapService->GetNetHeapByID($companyState['net_heap_id'], "own_man");

		$xml = SXML::load('../config/config.xml');
		$this->_aParams['tel_head'] = !is_null($xml) ? $xml->TechniquePhone : "023-61627888";
		$this->_aParams['HuiboPhone400'] = $xml->HuiboPhone400;
        $userService = new base_service_crm_user();
		if (!is_null($companyHeap) && isset($companyHeap["own_man"])) {
			$this->_aParams["hrManager"] = $userService->GetUsers($companyHeap['own_man'], "user_name,head_photo_url,tel,mobile,qq");
		}else if($company_info['site_type']==4){
            $base_service_sys_deptmanager = new base_service_sys_deptmanager();
            $userid  = $base_service_sys_deptmanager->getFirstDeptManager(187)['user_id'];//餐饮部门管理员
            if (empty($userid)){
                $userid  = $base_service_sys_deptmanager->getFirstDeptManager(4)['user_id'];//客服部门管理员
            }
            $this->_aParams["hrManager"] = $userService->GetUsers($userid, "user_name,head_photo_url,tel,mobile,qq");
        }
		//$company_resource_info = $company_resources->getCompanyServiceSource(["default_job_num", "has_pub_job_num"]);
		//$default_job_num = (int)$company_resource_info["default_job_num"];
		$this->_aParams["default_job_num"] = $default_job_num;
        $company_info = $company_resources->getCompanyServiceSource();
		$this->_aParams["resource_type"]   = $company_info['resource_type'];
		return $this->render("job/nojobtoadd.html", $this->_aParams);
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
	
	/**
	 * 判断企业会员和过期时间
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	public function pageCheckComLevelEndtime($inPath) {
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
	
	/**
	 * @desc 重新发布职位
	 */
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
	// 	$validator = new base_lib_Validator();
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
	
	/**
	 * 修改岗位级别
	 */
	public function pageLevelUpdate($inPath) {
		if (!$this->canDo("job_edit")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
		}
		//绑定岗位级别
		$service_job_level = new base_service_common_joblevel();
		$job_level_arr = $service_job_level->getJobLevel();
		foreach ($job_level_arr as $key => $value) {
			$_arr[] = ['id' => $key, 'name' => $value];
		}
		
		$this->_aParams['job_level_json'] = json_encode($_arr);
		
		// 在招职位
		$job_status = new base_service_common_jobstatus();
		$job = new base_service_company_job_job();

		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$company_id = $this->_userid;
		$company_resources  = base_service_company_resources_resources::getInstance($company_id,true,$account_id);
		$companyresources = $company_resources->getCompanyServiceSource(['account_resource']);

		$job_list = $job->getJobList($company_resources->all_accounts, null, $job_status->pub, 'job_id,station,job_level,account_id');

		//如果是分配模式，则只显示改账号发布的职位，如果为共享模式，则不显示分配模式账号发布的职位
		$service_company_account = new base_service_company_account();
		$account_info = $service_company_account->getAccountCompany($company_id,"account_id,company_id,user_name,resource_type");
		$account_info = base_lib_BaseUtils::array_key_assoc($account_info,"account_id");

		foreach((array)$job_list as $key => $value){
			if($companyresources['resource_type'] == 2){
				if($value['account_id'] != $account_id){
					unset($job_list[$key]);
				}
			}else{
				if($account_info[$value['account_id']]['resource_type'] == 2){
					unset($job_list[$key]);
				}
			}
		}


		$this->_aParams['joblist'] = $job_list;
		
		return $this->render('job/updatelevel.html', $this->_aParams);
	}
	
	/**
	 * 修改岗位级别
	 */
	public function pageLevelUpdateDo($inPath) {
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$jobids = base_lib_BaseUtils::getStr($params['hddjobID'], 'array', null);
		$jobids = base_lib_BaseUtils::getIntArrayOrString($jobids);
		
		$jobs = array ();
		$log_message = '';
		$service_job = new base_service_company_job_job();
		$common_job_level = new base_service_common_joblevel();
		$job_info = $service_job->getJobs($jobids, 'job_id,station,account_id');
		$job_info = base_lib_BaseUtils::array_key_assoc($job_info, 'job_id');

		//子账号判断，共享模式的账号不能修改分配模式的子账号职位，分配模式子账号不能修改共享模式的子账号职位
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$company_id = $this->_userid;
		$company_resources  = base_service_company_resources_resources::getInstance($company_id,true,$account_id);
		$companyresources = $company_resources->getCompanyServiceSource(['account_resource']);

		//查询所有子账号信息
		$service_company_account = new base_service_company_account();
		$account_info = $service_company_account->getAccountCompany($company_id,"account_id,company_id,user_name,resource_type");
		$account_info = base_lib_BaseUtils::array_key_assoc($account_info,"account_id");
		foreach((array)$job_info as $key => $value){
			$job_resource_type = $account_info[$value['account_id']]['resource_type'];
			$user_name = $account_info[$value['account_id']]['user_name'];
			if($companyresources['resource_type'] == 2){
				//分配模式,查询所选职位是否包含共享模式职位和其他分配模式账号发布的职位
				if($job_resource_type == 1){
					$error = array ('msg' => "职位:{$value['station']}由账号:{$user_name}发布，请登录此账号进行操作", 'status' => false);
					echo json_encode($error);exit();
				}else{
					if($value['account_id'] != $account_id){
						$error = array ('msg' => "职位:{$value['station']}由分配子账号:{$user_name}发布，请登录此账号进行操作", 'status' => false);
						echo json_encode($error);exit();
					}
				}
			}else{
				//共享模式,验证所选职位是否包含分配模式职位
				if($job_resource_type == 2){
					$error = array ('msg' => "职位:{$value['station']}由分配子账号:{$user_name}发布，请登录此账号进行操作", 'status' => false);
					echo json_encode($error);exit();
				}
			}
		}


		foreach ((array)$jobids as $jobid) {
			$job_level = base_lib_BaseUtils::getStr($params["hddJoblevel{$jobid}"]);
			if (base_lib_BaseUtils::nullOrEmpty($job_level)) {
				$job_level = "02";
			}
			$service_job->modifyJobLevel($jobid, array ('job_level' => $job_level));
			$log_message .= " 职位：" . $job_info[ $jobid ]['station'] . "，岗位级别改为：" . $common_job_level->getName($job_level) . "；";
		}
		
		
		//---------添加操作日志--------
		$common_oper_type = new base_service_common_account_accountoperatetype();
		$service_oper_log = new base_service_company_companyaccountlog();
		$common_oper_src_type = new base_service_common_account_accountlogfrom();
		$insertItems = array (
			"company_id"   => $this->_userid,
			"source"       => $common_oper_src_type->website,
			"account_id"   => base_lib_BaseUtils::getCookie('accountid'),
			"operate_type" => $common_oper_type->job_LevelUpdate,
			"content"      => "批量更改岗位级别，详情：" . $log_message,
			"create_time"  => date("Y-m-d H:i:s", time())
		);
		$service_oper_log->addLogToMongo($insertItems);
		//-------------END------------
		
		echo json_encode(array ('success' => '保存成功'));
		
		return;
	}
	
	/**
	 *
	 * 排序职位
	 * @param $inPath
	 */
	public function pageJobSort($inPath) {
		if (!$this->canDo("job_edit")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
		}
		$job_status = new base_service_common_jobstatus();
		$job = new base_service_company_job_job();
		
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid);
		$job_list = $job->getJobList($company_resources->all_accounts, null, $job_status->pub, 'job_id,station,order_no');
		
		$bo = usort($job_list, function ($a, $b) {
			$i = base_lib_BaseUtils::getStr($a['order_no'], 'int', 0);
			$j = base_lib_BaseUtils::getStr($b['order_no'], 'int', 0);
			if ($i == $j) {
				return 0;
			}
			
			return ($i > $j) ? -1 : 1;
		});
		
		$this->_aParams['joblist'] = $job_list;
		
		return $this->render('job/updatejobsort.html', $this->_aParams);
	}
	
	/**
	 *
	 * 保存排序
	 * @param $inPath
	 */
	public function pageJobSortDo($inPath) {
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$jobids = base_lib_BaseUtils::getStr($params['hddjobID'], 'array', null);
		$jobids = base_lib_BaseUtils::getIntArrayOrString($jobids);
		
		$job_resule = true;
		$items = array ();
		foreach ((array)$jobids as $jobid) {
			$order_no = base_lib_BaseUtils::getStr($params["txtOrderNo{$jobid}"], 'int', 1);
			array_push($items, array ('jobid' => $jobid, 'order_no' => $order_no));
		}
		// 按正序排列
		$bo = usort($items, function ($a, $b) {
			$i = base_lib_BaseUtils::getStr($a['order_no'], 'int', 0);
			$j = base_lib_BaseUtils::getStr($b['order_no'], 'int', 0);
			if ($i == $j) {
				return 0;
			}
			
			return ($i > $j) ? 1 : -1;
		});
		
		$service_job = new base_service_company_job_job();
		$job_info = $service_job->getJobs($jobids, 'job_id,station');
		$job_info = base_lib_BaseUtils::array_key_assoc($job_info, 'job_id');
		
		$count = count($items);
		$log_message = '';
		foreach ($items as $item) {
			// 保存用户设置的排列顺序
			$service_job = new base_service_company_job_job();
			$job_resule = $service_job->modifyJobProperty($item['jobid'], $this->_userid, array ('order_no' => $count), false);
			$log_message .= " 职位：" . $job_info[ $item['jobid'] ]['station'] . "，排序号：" . $count . "；";
			$count--;
		}
		
		//---------添加操作日志--------
		$common_oper_type = new base_service_common_account_accountoperatetype();
		$service_oper_log = new base_service_company_companyaccountlog();
		$common_oper_src_type = new base_service_common_account_accountlogfrom();
		$insertItems = array (
			"company_id"   => $this->_userid,
			"source"       => $common_oper_src_type->website,
			"account_id"   => base_lib_BaseUtils::getCookie('accountid'),
			"operate_type" => $common_oper_type->job_sort,
			"content"      => "职位排序，排序后：" . $log_message,
			"create_time"  => date("Y-m-d H:i:s", time())
		);
		$service_oper_log->addLogToMongo($insertItems);
		//-------------END------------
		
		
		echo json_encode(array ('success' => '保存成功'));
		
		return;
	}
	
	/**
	 * 修改联系方式
	 * @param  $inPath
	 */
	public function pageUpdateLinkway($inPath) {
		if (!$this->canDo("job_edit")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
		}
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$validator = new base_lib_Validator();
		$job_ids = $validator->getArray($params['jobids']);
		$job_ids = base_lib_BaseUtils::getIntArrayOrString($job_ids);
		
		$service_company = new base_service_company_company();
		$current_company = $service_company->getCompany($this->_userid, 1, 'linkman,link_tel,email');
		if (is_null($current_company)) {
			return;
		}
		if (count($job_ids) <= 0) {
			return;
		}
		
		// 在招职位
		$job_status = new base_service_common_jobstatus();
		$job_service = new base_service_company_job_job();
		$job_list = $job_service->getJobs($job_ids, 'job_id,station,open_linkway,self_linkway,linkman,link_tel,linkman2,link_tel2,linkman3,link_tel3');
		
		$bo = usort($job_list, function ($a, $b) {
			$i = base_lib_BaseUtils::getStr($a['order_no'], 'int', 0);
			$j = base_lib_BaseUtils::getStr($b['order_no'], 'int', 0);
			if ($i == $j) {
				return 0;
			}
			
			return ($i > $j) ? 1 : -1;
		});
		
		foreach ($job_list as $key => $job) {
			//新联系方式
			$linkways = array ();
			if (!base_lib_BaseUtils::nullOrEmpty($job['linkman']) && !base_lib_BaseUtils::nullOrEmpty($job['link_tel'])) {
				array_push($linkways, array ('link_man' => $job['linkman'], 'linkman_tel' => $job['link_tel']));
			}
			if (!base_lib_BaseUtils::nullOrEmpty($job['linkman2']) && !base_lib_BaseUtils::nullOrEmpty($job['link_tel2'])) {
				array_push($linkways, array ('link_man' => $job['linkman2'], 'linkman_tel' => $job['link_tel2']));
			}
			if (!base_lib_BaseUtils::nullOrEmpty($job['linkman3']) && !base_lib_BaseUtils::nullOrEmpty($job['link_tel3'])) {
				array_push($linkways, array ('link_man' => $job['linkman3'], 'linkman_tel' => $job['link_tel3']));
			}
			
			$job['linkways'] = $linkways;
			$job_list[ $key ] = $job;
		}
		
		$this->_aParams['curcompany'] = $current_company;
		$this->_aParams['joblist'] = $job_list;
		
		//公司子账户信息
		$service_company_account = new base_service_company_account();
		$company_account_info = $service_company_account->getAccountList($this->_userid, 'account_id,is_main,user_id,user_name,mobile_phone,link_tel')->items;
		$this->_aParams['company_account_info'] = base_lib_BaseUtils::array_key_assoc($company_account_info, 'account_id');
		//当前账号id
		$this->_aParams['count_id'] = base_lib_BaseUtils::getCookie('accountid');
		
		return $this->render('job/updatelinkway.html', $this->_aParams);
	}
	
	/**
	 * 修改联系方式
	 * @param $inPath
	 */
	public function pageUpdateLinkwayDo($inPath) {
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$validator = new base_lib_Validator();
		// 职位编号
		$jobids = base_lib_BaseUtils::getStr($params['hddjobID'], 'array', null);
		$jobids = base_lib_BaseUtils::getIntArrayOrString($jobids);
		
		//是否显示联系方式
		$isshowlinkway = base_lib_BaseUtils::getStr($params['showLinkway'], 'int', 0);
		$validator->getNum($params['showLinkway'], 0, 1, '请选择联系方式');
		
		//是否使用新联系方式
		$newlinkway = base_lib_BaseUtils::getStr($params['newLinkway'], 'int', 0);
		$validator->getNum($params['newLinkway'], 0, 5, '请选择联系方式');
		
		//新联系方式数量
		$newlinkwaycount = base_lib_BaseUtils::getStr($params['newLinkWayCount'], 'int', 1);
		$validator->getNum($params['newLinkWayCount'], 1, 3, '新联系方式数量异常');
		
		//联系人和联系方式数组
		$linkmantel_arr = array ();
		$job['open_linkway'] = 0;
		$job['self_linkway'] = 0;
		$job['linkman'] = '';
		$job['link_tel'] = '';
		
		$log_message = '';
		
		//联系人和联系方式数组
		if ($isshowlinkway != 0) {//显示联系方式
			$job['open_linkway'] = 1;
			if ($newlinkway == 1) {//使用新联系方式
				$job['self_linkway'] = 1;
				for ($i = 0; $i < 1; $i++) {
					$job['linkman'] = $validator->getStr($params[ 'txtLinkMan' . ($i + 1) ],6,13,'联系人电话限制6-13位数字');
					$job['link_tel'] = '';
					if (!empty($path_data[ 'txtLinkTel' . ($i + 1) ]))
						$job['link_tel'] = $validator->getStr($path_data['txtLinkTel1'], 1, 6, '请填写1-6位数字的分机号');
					break;
				}
				$log_message = "使用其他联系方式（" . $job['linkman'] . "-" . $job['link_tel'] . "）";
			} else {//使用企业联系方式
				//0 使用企业联系方式(以前的) 1 使用新联系方式  4 发布人手机号 5 发布人办公电话
				//$job['self_linkway'] = 0;
				$job['self_linkway'] = $newlinkway;
				if ($newlinkway == 4)
					$log_message = '使用发布人手机号';
				else {
					$log_message = '使用发布人办公电话';
				}
			}
		} else {//不显示联系方式
			$job['open_linkway'] = 0;
			$job['self_linkway'] = 0;
			$log_message = '不显示联系方式';
		}
		
		if ($isshowlinkway == 1 && ($newlinkway == 4 || $newlinkway == 5)) {
			if (!empty($params['param_account_id']))
				$job['account_id'] = intval($params['param_account_id']);
		}

		//子账号判断，共享模式的账号不能修改分配模式的子账号职位，分配模式子账号不能修改共享模式的子账号职位
		$service_job = new base_service_company_job_job();
		$job_info = $service_job->getJobs($jobids, 'job_id,station,account_id');

		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$company_id = $this->_userid;
		$company_resources  = base_service_company_resources_resources::getInstance($company_id,true,$account_id);
		$companyresources = $company_resources->getCompanyServiceSource(['account_resource']);

		//查询所有子账号信息
		$service_company_account = new base_service_company_account();
		$account_info = $service_company_account->getAccountCompany($company_id,"account_id,company_id,user_name,resource_type");
		$account_info = base_lib_BaseUtils::array_key_assoc($account_info,"account_id");
		foreach((array)$job_info as $key => $value){

			$job_resource_type = $account_info[$value['account_id']]['resource_type'];
			$user_name = $account_info[$value['account_id']]['user_name'];
			$error = "";
			if($companyresources['resource_type'] == 2){
				//分配模式,查询所选职位是否包含共享模式职位和其他分配模式账号发布的职位
				if($job_resource_type == 1){
					$error = "职位:{$value['station']}由账号:{$user_name}发布，请登录此账号进行操作";

				}else{
					if($value['account_id'] != $account_id){
						$error = "职位:{$value['station']}由分配子账号:{$user_name}发布，请登录此账号进行操作";
					}
				}
			}else{
				//共享模式,验证所选职位是否包含分配模式职位
				if($job_resource_type == 2){
					$error = "职位:{$value['station']}由分配子账号:{$user_name}发布，请登录此账号进行操作";
				}
			}
			if($error){
				$validator->addErr($error);
			}
		}


		if ($validator->has_err) {
//			echo header("Content-type:text/plain;charset=utf-8");
//			echo $validator->toJsonWithHtml();
			$error1 = array ('error' => implode("</br>",$validator->err), 'status' => false);
			echo json_encode($error1);
			return;
		}
		
		$job_result = true;

		foreach ((array)$jobids as $jobid) {
			$job_result = $service_job->modifyJobProperty($jobid, $this->_userid, $job, true);
		}
		//---------添加操作日志--------

		$job_info = base_lib_BaseUtils::getProperty($job_info, 'station');
		$common_oper_type = new base_service_common_account_accountoperatetype();
		$service_oper_log = new base_service_company_companyaccountlog();
		$common_oper_src_type = new base_service_common_account_accountlogfrom();
		$insertItems = array (
			"company_id"   => $this->_userid,
			"source"       => $common_oper_src_type->website,
			"account_id"   => base_lib_BaseUtils::getCookie('accountid'),
			"operate_type" => $common_oper_type->job_update_linkway,
			"content"      => "批量修改职位联系方式，以下职位：" . implode('，', $job_info) . "，联系方式改为：" . $log_message,
			"create_time"  => date("Y-m-d H:i:s", time())
		);
		$service_oper_log->addLogToMongo($insertItems);
		//-------------END------------
		
		
		echo json_encode(array ('success' => '保存成功'));
		
		return;
	}
	
	/**
	 * 修改邮箱
	 * @param  $inPath
	 */
	public function pageUpdateMail($inPath) {
		if (!$this->canDo("job_edit")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
		}
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$validator = new base_lib_Validator();
		$job_ids = $validator->getArray($params['jobids']);
		$job_ids = base_lib_BaseUtils::getIntArrayOrString($job_ids);
		
		$service_company = new base_service_company_company();
		$current_company = $service_company->getCompany($this->_userid, 1, 'email');
		if (is_null($current_company)) {
			return;
		}
		
		if (count($job_ids) <= 0) {
			return;
		}
		
		// 在招职位
		$job_status = new base_service_common_jobstatus();
		$job_service = new base_service_company_job_job();
		$job_list = $job_service->getJobs($job_ids, 'job_id,station,allow_email,other_email');
		
		$bo = usort($job_list, function ($a, $b) {
			$i = base_lib_BaseUtils::getStr($a['order_no'], 'int', 0);
			$j = base_lib_BaseUtils::getStr($b['order_no'], 'int', 0);
			if ($i == $j) {
				return 0;
			}
			
			return ($i > $j) ? 1 : -1;
		});
		
		foreach ($job_list as $key => $job) {
			//邮箱
			if (!base_lib_BaseUtils::nullOrEmpty($job['other_email'])) {
				$job['linkemail'] = explode(',', $job['other_email']);
			}
			$job_list[ $key ] = $job;
		}
		
		$this->_aParams['curcompany'] = $current_company;
		$this->_aParams['joblist'] = $job_list;
		
		return $this->render('job/updatemail.html', $this->_aParams);
	}
	
	/**
	 * 修改邮箱操作类
	 * @param  array $inPath url参数集
	 * @return json          result
	 */
	public function pageUpdateMailDo($inPath) {
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$validator = new base_lib_Validator();
		
		// 职位编号
		$jobids = base_lib_BaseUtils::getStr($params['hddjobID'], 'array', null);
		$jobids = base_lib_BaseUtils::getIntArrayOrString($jobids);
		
		//是否需要发送到邮箱
		$istomail = base_lib_BaseUtils::getStr($params['toMail'], 'int', 0);
		$validator->getNum($params['toMail'], 0, 1, '是否需要发送到邮箱异常');
		
		// 新邮箱方式
		$allowmail = base_lib_BaseUtils::getStr($params['allowmail'], 'int', 0);
		$validator->getNum($params['toMail'], 0, 1, '是否需要发送到邮箱异常');
		
		//新邮箱数量
		$emailcount = base_lib_BaseUtils::getStr($params['emailCount'], 'int', 1);
		$validator->getNum($params['emailCount'], 1, 3, '新联系方式数量异常');
		
		//新邮箱数组
		$emails_arr = array ();
		$job = array ();
		$service_allow_email_type = new base_service_company_job_joballowemailtype();
		$log_mess = '';
		if ($istomail == 1) {//需要发送到邮箱
			if ($allowmail == 0) {
				$job['allow_email'] = $service_allow_email_type->toaccountandemail;
				$log_mess = '使用企业邮箱';
			} else {
				$job['allow_email'] = $service_allow_email_type->toaccountandotheremail;
				for ($j = 0; $j < $emailcount; $j++) {
					$email = $validator->getEmail($params[ 'email' . ($j + 1) ], '邮箱地址不正确，修改失败！', true);
					if ($validator->has_err) {
						break;
					}
					
					if (!base_lib_BaseUtils::nullOrEmpty($email)) {
						array_push($emails_arr, $email);
					}
				};
				$job['other_email'] = implode(',', $emails_arr);
				$log_mess = '使用自定义邮箱：' . $job['other_email'];
			}
		} else {
			$log_mess = '不需要将收到的简历发送到邮箱';
			$job['allow_email'] = $service_allow_email_type->toaccount;
			$job['other_email'] = '';
		}


		//子账号判断，共享模式的账号不能修改分配模式的子账号职位，分配模式子账号不能修改共享模式的子账号职位
		$service_job = new base_service_company_job_job();
		$job_info = $service_job->getJobs($jobids, 'job_id,station,account_id');

		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$company_id = $this->_userid;
		$company_resources  = base_service_company_resources_resources::getInstance($company_id,true,$account_id);
		$companyresources = $company_resources->getCompanyServiceSource(['account_resource']);

		//查询所有子账号信息
		$service_company_account = new base_service_company_account();
		$account_info = $service_company_account->getAccountCompany($company_id,"account_id,company_id,user_name,resource_type");
		$account_info = base_lib_BaseUtils::array_key_assoc($account_info,"account_id");
		foreach((array)$job_info as $key => $value){
			$job_resource_type = $account_info[$value['account_id']]['resource_type'];
			$user_name = $account_info[$value['account_id']]['user_name'];
			$error = "";
			if($companyresources['resource_type'] == 2){
				//分配模式,查询所选职位是否包含共享模式职位和其他分配模式账号发布的职位
				if($job_resource_type == 1){
					$error = "职位:{$value['station']}由账号:{$user_name}发布，请登录此账号进行操作";

				}else{
					if($value['account_id'] != $account_id){
						$error = "职位:{$value['station']}由分配子账号:{$user_name}发布，请登录此账号进行操作";
					}
				}
			}else{
				//共享模式,验证所选职位是否包含分配模式职位
				if($job_resource_type == 2){
					$error = "职位:{$value['station']}由分配子账号:{$user_name}发布，请登录此账号进行操作";
				}
			}
			if($error){
				$validator->addErr($error);
			}
		}
		
		if ($validator->has_err) {
//			echo header("Content-type:text/plain;charset=utf-8");
//			echo $validator->toJsonWithHtml();
			$error1 = array ('error' => implode("</br>",$validator->err), 'status' => false);
			echo json_encode($error1);
			return;
		}
		
		$job_resule = true;
		if (count($jobids) > 0) {
			foreach ($jobids as $jobid) {
				$job_resule = $service_job->modifyJobProperty($jobid, $this->_userid, $job, false);
			}


			$job_info = base_lib_BaseUtils::getProperty($job_info, 'station');
			//---------添加操作日志--------
			$common_oper_type = new base_service_common_account_accountoperatetype();
			$service_oper_log = new base_service_company_companyaccountlog();
			$common_oper_src_type = new base_service_common_account_accountlogfrom();
			$insertItems = array (
				"company_id"   => $this->_userid,
				"source"       => $common_oper_src_type->website,
				"account_id"   => base_lib_BaseUtils::getCookie('accountid'),
				"operate_type" => $common_oper_type->job_update_mail,
				"content"      => "批量修改职位联系邮箱，职位：" . implode('，', $job_info) . '，邮箱修改为：' . $log_mess,
				"create_time"  => date("Y-m-d H:i:s", time())
			);
			$service_oper_log->addLogToMongo($insertItems);
			//-------------END------------
		}
		
		
		echo json_encode(array ('success' => '保存成功'));
		
		return;
	}
	
	/**
	 * 批量停招职位显示页
	 * @param  array $inPath url参数集
	 * @return html          job/batchstopjob.html
	 */
	public function pageBatchStopJob($inPath) {
		if (!$this->canDo("job_close")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
			exit;
		}
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$validator = new base_lib_Validator();
		$job_ids = $validator->getArray($params['jobids']);
		$job_ids = base_lib_BaseUtils::getIntArrayOrString($job_ids);
		
		if (count($job_ids) <= 0) {
			return;
		}
		
		// 在招职位
		$job_status = new base_service_common_jobstatus();
		$job_service = new base_service_company_job_job();
		$job_list = $job_service->getJobs($job_ids, 'job_id,station');
		
		$bo = usort($job_list, function ($a, $b) {
			$i = base_lib_BaseUtils::getStr($a['order_no'], 'int', 0);
			$j = base_lib_BaseUtils::getStr($b['order_no'], 'int', 0);
			if ($i == $j) {
				return 0;
			}
			
			return ($i > $j) ? 1 : -1;
		});
		
		$this->_aParams['joblist'] = $job_list;
		
		return $this->render('job/batchstopjob.html', $this->_aParams);
	}
	
	/**
	 * 批量停止职位
	 * @param  $inPath
	 */
	public function pageBatchStopJobDo($inPath) {
		if (!$this->canDo("job_close")) {
			echo json_encode(array ('error' => '没有权限进行此操作'));
			exit;
		}
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$jobids = base_lib_BaseUtils::getStr($params['hddjobID'], 'array', null);
		$jobids = (array)base_lib_BaseUtils::getIntArrayOrString($jobids);
		
		$service_job = new base_service_company_job_job();
		$job_status = new base_service_common_jobstatus();
		
		$jobs = $service_job->getJobs($jobids, "job_id,company_id");
		
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid);
		$service_quality    = new base_service_company_job_quality();
		foreach ((array)$jobs as $job) {
			if (!in_array($job['company_id'], $company_resources->all_accounts))
				continue;

			//停止职位，职位为精品职位，取消推广等信息
			$quality_job        = $service_quality->getJobIsQuality($job['job_id']);
			if(!empty($quality_job)){
				$cancel_info = $service_quality->cancelJobQulity($this->_userid,$job['job_id']); // 若选择发布免费职位，则取消以前的精品职位
				if($cancel_info['code'] == ERROR){
					echo json_encode(array ('status' => 0, 'msg' => $cancel_info['msg']));
					return;
				}
			}
			$service_job->setJobStatus($job['company_id'], $job['job_id'], $job_status->stop_use);
			
			$this->setOuterStop($job['job_id']);
		}
		
		//---------添加操作日志--------
		$common_oper_type = new base_service_common_account_accountoperatetype();
		$service_oper_log = new base_service_company_companyaccountlog();
		$service_job = new base_service_company_job_job();
		$job_info = $service_job->getJobs($jobids, 'job_id,station');
		$job_info = base_lib_BaseUtils::getProperty($job_info, 'station');
		$common_oper_src_type = new base_service_common_account_accountlogfrom();
		$insertItems = array (
			"company_id"   => $this->_userid,
			"source"       => $common_oper_src_type->website,
			"account_id"   => base_lib_BaseUtils::getCookie('accountid'),
			"operate_type" => $common_oper_type->job_stop,
			"content"      => "关闭职位，职位：" . implode('，', $job_info),
			"create_time"  => date("Y-m-d H:i:s", time())
		);
		$service_oper_log->addLogToMongo($insertItems);
		//-------------END------------

        //-------------关闭职位发送短信：需要发送的求职者插入到待发列表中去 info_company_action_sms_log---------------
        $service_job->JobStopSmsSend($jobids,$this->_userid);
        //-------------END---------------


		echo json_encode(array ('success' => '停止招聘成功'));
		
		return;
	}
	
	/**
	 * @Desc 单个 延期某个职位展示
	 * @return type_name
	 */
	public function pageDelaySingle($inPath) {
		if (!$this->canDo("job_edit")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
		}
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		
		$obj = base_lib_BaseUtils::getStr($path_data['obj'], 'string', '');
		$callback = base_lib_BaseUtils::getStr($path_data['callback'], 'string', '');
		$this->_aParams['theObj'] = $obj;
		$this->_aParams['theCallback'] = $callback;
		
		$vali = new base_lib_Validator();
		$job_id = $vali->getNum($path_data['job_id'], 'min', 'max', '请选择职位', false);
		
		/* 兼容过去已发布的职位 */
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid);
		//$resources = $company_resources->getCompanyServiceSource(['default_job_num', 'has_pub_job_num']);
		$resources = $company_resources->getCompanyServiceSource();
		if($resources['isNewService']){

		}else{
			if ($resources['default_job_num'] < $resources['has_pub_job_num']){
				$vali->addErr("您的发布职位数已满！");
			}
		}

		
		if ($vali->has_err) {
			echo header("Content-type:text/plain;charset=utf-8");
			echo $vali->toErrorHtml();
			
			return;
		}
		
		$service_job = new base_service_company_job_job();
		$job_info = $service_job->getJob($job_id, 'job_id,station,end_time');
		$this->_aParams['job'] = $job_info;
		
		return $this->render('job/delaysingle.html', $this->_aParams);
	}
	
	/**
	 * @Desc 单个 延期某个职位执行
	 * @return type_name
	 */
	public function pageDelaySingleDo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$vali = new base_lib_Validator();
		
		$job_id = $vali->getNum($path_data['job_id'], 'min', 'max', '请选择职位', false);
		$valid_days = $vali->getNotNull($path_data['txtValidDays'], '请输入有效期');
		
		$vali->getNum($path_data['txtValidDays'], 1, 60, '请输入1-60之间的整数');
		
		//单位状态
		$service_job = new base_service_company_job_job();
		$MarkJobIds = false;
		$MarkCompayCalling = false;
		
		/*==============判断该职位是否有职位类别===================*/
		
		$job_sort_info = $service_job->getJob($job_id, 'jobsort_ids');
		$job_sort_info_array = array ();
		
		if (!empty($job_sort_info['jobsort_ids'])) {
			$job_sort_info_array = explode($job_sort_info['jobsort_ids']);
		}
		
		if (base_lib_BaseUtils::nullOrEmpty($job_sort_info['jobsort_ids'])
			|| $job_sort_info['jobsort_ids'] == "9999"
			|| in_array("9999", $job_sort_info_array)
		) {
			
			$MarkJobIds = true;
			echo header("Content-type:text/plain;charset=utf-8");
			echo json_encode(['status' => 'info', 'message' => '该职位未选择职位类别，请先选择再操作', 'goto' => "job", 'job_id' => $job_id]);
			
			return;
		}
		
		/*==============判断该企业是否有行业类别===================*/
		$calling_mark = $this->matchCompanyCalling();
		if ($calling_mark) {
			echo header("Content-type:text/plain;charset=utf-8");
			echo json_encode(['status' => 'info', 'message' => '我们对行业做了一些调整，请在企业资料中重新选择后再操作！', 'togo' => "company"]);
			
			return;
		}
		
		if ($vali->has_err) {
			echo header("Content-type:text/plain;charset=utf-8");
			echo $vali->toJsonWithHtml();
			
			return;
		}
		
		$result = $service_job->delayJobEndTime($job_id, $valid_days);
		$job_info = $service_job->getJob($job_id, 'station,end_time');
		
		if ($result) {
			$json['success'] = 'true';
			$json['job_id'] = $job_id;
			$json['end_time'] = date('m-d', strtotime($job_info['end_time']));
			
			//---------添加操作日志--------
			$common_oper_type = new base_service_common_account_accountoperatetype();
			$service_oper_log = new base_service_company_companyaccountlog();
			$common_oper_src_type = new base_service_common_account_accountlogfrom();
			$insertItems = array (
				"company_id"   => $this->_userid,
				"source"       => $common_oper_src_type->website,
				"account_id"   => base_lib_BaseUtils::getCookie('accountid'),
				"operate_type" => $common_oper_type->job_yanqi,
				"content"      => "单个职位延期，职位：" . $job_info['station'] . "，延期天数：" . $valid_days,
				"create_time"  => date("Y-m-d H:i:s", time())
			);
			$service_oper_log->addLogToMongo($insertItems);
			//-------------END------------
		} else {
			$json['error'] = '延期失败！';
		}
		
		echo header("Content-type:text/plain;charset=utf-8");
		echo json_encode($json);
		
		return;
	}
	
	public function pageSelectAddress($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$this->_aParams["address_id"] = base_lib_BaseUtils::getStr($path_data["id"], "string", -1);
		$this->_aParams["address_id"] = explode(',', $this->_aParams["address_id"]);
		
		$this->_aParams["max_count"] = base_lib_BaseUtils::getStr($path_data["max_count"], "int", 5);
		//获取职位地址列表
		$service_company_companyaddress = new base_service_company_companyaddress();
		$address_result = $service_company_companyaddress->getAddressListByCompanyId($this->_userid, 1);
		$this->_aParams['address_list'] = $address_result->items;
		
		return $this->render('job/selectjobaddress.html', $this->_aParams);
	}
	
	public function pageSetJobAddress($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$this->_aParams["add_info"] = base_lib_BaseUtils::getStr($path_data["add_info"], "string", "");
		$this->_aParams["map_x"] = base_lib_BaseUtils::getStr($path_data["map_x"], "string", "");
		$this->_aParams["map_y"] = base_lib_BaseUtils::getStr($path_data["map_y"], "string", "");
		$this->_aParams["area_id"] = base_lib_BaseUtils::getStr($path_data["area_id"], "string", "");
		$this->_aParams["id"] = base_lib_BaseUtils::getStr($path_data["id"], "int", -1);
		$this->_aParams["orgin_id"] = base_lib_BaseUtils::getStr($path_data["orgin_id"], "int", 0);
		
		return $this->render('job/setjobaddress.html', $this->_aParams);
	}
	
	/**
	 * @Desc 添加，修改职位地址
	 */
	public function pageModJobAddress($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$service_company_companyaddress = new base_service_company_companyaddress();
		
		$data = [];
		$data["add_info"] = base_lib_BaseUtils::getStr($path_data["add_info"], "string", "");
		$data["map_x"] = base_lib_BaseUtils::getStr($path_data["map_x"], "string", "");
		$data["map_y"] = base_lib_BaseUtils::getStr($path_data["map_y"], "string", "");
		$data["area_id"] = base_lib_BaseUtils::getStr($path_data["area_id"], "string", "");
		$data["id"] = base_lib_BaseUtils::getStr($path_data["id"], "int", -1);
		$data["type"] = base_lib_BaseUtils::getStr($path_data["type"], "int", 1);
		$data["company_id"] = $this->_userid;
		
		if ($data["id"] == -1) {   //执行添加操作
//            $data["is_company_address"] = 0;
			$result = $service_company_companyaddress->addAddress($data);
		} else {              //修改操作
			if (!$data["id"]) {
				$service_company = new base_service_company_company();
				$service_area = new base_service_common_area();
				$company_resources = base_service_company_resources_resources::getInstance($this->_userid);
				$company = $service_company->getCompany($this->_userid, 1, "area_id");
				$is_simple_area = $service_area->checkSimpleArea($data["area_id"], $company["area_id"]);
				//区县非会员不能修改公司所在地
				if ($company_resources->account_type == "NotMemberTypeArea" && !$is_simple_area) {
					echo json_encode(["result" => false]);
					
					return;
				}
				$result = $service_company->saveCompanyAddress($this->_userid, $data["area_id"], $data["add_info"]);
				
				$service_baidumap = new base_service_company_companycooperaterbaidumap();
				$result = $service_baidumap->saveBaiduMap(["map_x" => $data["map_x"], "map_y" => $data["map_y"], "map_zoom" => 18, "company_id" => $this->_userid]);
			} else {
				$result = $service_company_companyaddress->updateAddress($data["id"], $data);
			}
		}
		
		echo json_encode(["result" => $result]);
		
		return;
	}
	
	/**
	 * @desc 同步其他职位地址冗余字段
	 */
	public function pageSyncJobAddress($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$service_company_job_job = new base_service_company_job_job();
		
		$add_info = base_lib_BaseUtils::getStr($path_data["add_info"], "string", "");
		$map_x = base_lib_BaseUtils::getStr($path_data["map_x"], "string", "");
		$map_y = base_lib_BaseUtils::getStr($path_data["map_y"], "string", "");
		$area_id = base_lib_BaseUtils::getStr($path_data["area_id"], "string", "");
		$address_id = base_lib_BaseUtils::getStr($path_data["id"], "int", 0);
		$company_id = $this->_userid;
		
		$result = $service_company_job_job->updateJobAddress($company_id, $address_id, $map_x, $map_y, $area_id, $add_info);
		echo json_encode(["status" => $result]);
		
		return;
	}
	
	/**
	 * @desc 删除职位地址
	 */
	public function pageDeleteAddress($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$service_company_companyaddress = new base_service_company_companyaddress();
		
		$address_id = base_lib_BaseUtils::getStr($path_data["id"], "int", 0);
		$result = $service_company_companyaddress->deleteAddress($address_id);
		
		echo json_encode(["status" => $result]);
		
		return;
	}

//    /**
//     *@Desc 展示未开通服务
//     *@return type_name
//    */
//    public function pageShowNoService($inPath) {
//    	$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
//
//    	$domain = $this->GetDomainInfor();
//    	//未开服务的情形
//		//获取招聘顾问
//		$companyStateService = new base_service_company_comstate();
//		$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
//		$hrManager = $this->GetHRManager($companyState["net_heap_id"]);
//		//获取客服员
//		//$customerService = $this->GetCustomerService();
//		$this->_aParams["hasHRManager"] = false;
//		if(!is_null($hrManager)){
//			$this->_aParams["hasHRManager"] = true;
//			$headPhoto = base_lib_BaseUtils::nullOrEmpty($hrManager["head_photo_url"])?$domain["defaultPhoto"]:$hrManager["head_photo_url"];
//			$hrManager["head_photo_url"] = $domain["image"]."/".$domain["photo"]."/".$headPhoto;
//			$this->_aParams["hrManager"] = $hrManager;
//		}
//		return $this->render('job/noservicepop.html', $this->_aParams);
//    }
//
//    /**
//     *@Desc 展示没有足够点数
//     *@return type_name
//    */
//    public function pageShowNoJobPoint($inPath) {
//    	$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
//
//    	$use_point = base_lib_BaseUtils::getStr($path_data['use_point'],'int',0);
//    	$leave_point = base_lib_BaseUtils::getStr($path_data['leave_point'],'int',0);
//
//    	$this->_aParams['use_point'] = $use_point;
//    	$this->_aParams['leave_point'] = $leave_point;
//
//    	$domain = $this->GetDomainInfor();
//
//		//获取招聘顾问
//		$companyStateService = new base_service_company_comstate();
//		$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
//		$hrManager = $this->GetHRManager($companyState["net_heap_id"]);
//		//获取客服员
//		//$customerService = $this->GetCustomerService();
//		$this->_aParams["hasHRManager"] = false;
//		if(!is_null($hrManager)){
//			$this->_aParams["hasHRManager"] = true;
//			$headPhoto = base_lib_BaseUtils::nullOrEmpty($hrManager["head_photo_url"])?$domain["defaultPhoto"]:$hrManager["head_photo_url"];
//			$hrManager["head_photo_url"] = $domain["image"]."/".$domain["photo"]."/".$headPhoto;
//			$this->_aParams["hrManager"] = $hrManager;
//		}
//		return $this->render('job/nojobpointpop.html', $this->_aParams);
//    }
	
	
	/**
	 * @Desc 批量职位延期展示
	 * @return type_name
	 */
	function pageDelayMulti($inPath) {
		if (!$this->canDo("job_edit")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
		}
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$obj = base_lib_BaseUtils::getStr($path_data['obj'], 'string', '');
		$callback = base_lib_BaseUtils::getStr($path_data['callback'], 'string', '');
		$this->_aParams['theObj'] = $obj;
		$this->_aParams['theCallback'] = $callback;
		
		$vali = new base_lib_Validator();
		$job_ids = $vali->getArray($path_data['job_ids'], '请选择职位');
		$job_ids = base_lib_BaseUtils::getIntArrayOrString($job_ids);
		
		/* 兼容过去已发布的职位 */
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid);
		$resources = $company_resources->getCompanyServiceSource(['default_job_num', 'has_pub_job_num']);
		
		if ($resources['default_job_num'] < $resources['has_pub_job_num'])
			$vali->addErr("您的发布职位数已满！");
		
		if ($vali->has_err) {
			echo header("Content-type:text/plain;charset=utf-8");
			echo $vali->toErrorHtml();
			
			return;
		}
		
		$service_job = new base_service_company_job_job();
		$jobs = $service_job->getJobs($job_ids, 'job_id,station,end_time');
		$this->_aParams['jobs'] = $jobs;
		
		//生成展示名称
		$showStation = '';
		$index = 0;
		foreach ($jobs as $key => $job) {
			if ($index == 0) {
				$showStation = $showStation . $job['station'];
			} else if ($index < 3) {
				$showStation = $showStation . ',' . $job['station'];
			} else {
				$showStation = $showStation . '...';
				break;
			}
			$index++;
		}
		
		$this->_aParams['showStation'] = $showStation;
		
		return $this->render('job/delaymulti.html', $this->_aParams);
	}
	
	/**
	 * @Desc 批量延期职位执行
	 * @return type_name
	 */
	function pageDelayMultiDo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$vali = new base_lib_Validator();
		
		$job_ids = $vali->getArray($path_data['hddjobID'], '请选择职位');
		$job_ids = base_lib_BaseUtils::getIntArrayOrString($job_ids);
		//有效期
		$valid_days = $vali->getNotNull($path_data['txtValidDays'], '请输入有效期');
		$vali->getNum($path_data['txtValidDays'], 1, 60, '请输入1-60之间的整数');
		
		if ($validator->has_err) {
			echo header("Content-type:text/plain;charset=utf-8");
			echo $validator->toJsonWithHtml();
			
			return;
		}
		
		$service_job = new base_service_company_job_job();
		for ($j = 0; $j < count($job_ids); $j++) {
			$result = $service_job->delayJobEndTime($job_ids[ $j ], $valid_days);
		}
		
		//---------添加操作日志--------
		$common_oper_type = new base_service_common_account_accountoperatetype();
		$service_oper_log = new base_service_company_companyaccountlog();
		$service_job = new base_service_company_job_job();
		$job_info = $service_job->getJobs($job_ids, 'job_id,station');
		$job_info = base_lib_BaseUtils::getProperty($job_info, 'station');
		$common_oper_src_type = new base_service_common_account_accountlogfrom();
		$insertItems = array (
			"company_id"   => $this->_userid,
			"source"       => $common_oper_src_type->website,
			"account_id"   => base_lib_BaseUtils::getCookie('accountid'),
			"operate_type" => $common_oper_type->job_yanqi,
			"content"      => "批量职位延期，职位：" . implode('，', $job_info) . '，增加延期时间：' . $valid_days . '天',
			"create_time"  => date("Y-m-d H:i:s", time())
		);
		$service_oper_log->addLogToMongo($insertItems);
		//-------------END------------
		
		$json['success'] = '批量延期成功！';
		echo header("Content-type:text/plain;charset=utf-8");
		echo json_encode($json);
		
		return;
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
	
	/**
	 * 批量修改薪资
	 * @param unknown_type $inPath
	 */
	public function pageMordModSalary($inPath) {
		if (!$this->canDo("job_edit")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
		}
		$job = new base_service_company_job_job();
		$job_status = new base_service_common_jobstatus();

		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$company_id = $this->_userid;
		$company_resources  = base_service_company_resources_resources::getInstance($company_id,true,$account_id);
		$companyresources = $company_resources->getCompanyServiceSource(['account_resource']);

		$job_list = $job->getJobList($company_resources->all_accounts, null, $job_status->pub, 'job_id,station,max_salary,is_salary_show,min_salary,salary_type,base_min_salary,base_max_salary,account_id');

		//如果是分配模式，则只显示改账号发布的职位，如果为共享模式，则不显示分配模式账号发布的职位
		$service_company_account = new base_service_company_account();
		$account_info = $service_company_account->getAccountCompany($company_id,"account_id,company_id,user_name,resource_type");
		$account_info = base_lib_BaseUtils::array_key_assoc($account_info,"account_id");

		foreach((array)$job_list as $key => $value){
			if($companyresources['resource_type'] == 2){
				if($value['account_id'] != $account_id){
					unset($job_list[$key]);
				}
			}else{
				if($account_info[$value['account_id']]['resource_type'] == 2){
					unset($job_list[$key]);
				}
			}
		}

		$this->_aParams['job_list'] = $job_list;
		
		return $this->render('job/modsalary.html', $this->_aParams);
	}
	
	/**
	 * 批量修改薪资
	 * @param unknown_type $inPath
	 */
	public function pageMordModSalaryDo($inPath) {
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$jobids = $params['jobids'];
		$min_salarys = $params['min_salarys'];
		$max_salarys = $params['max_salarys'];
		$base_min_salarys = $params['base_min_salarys'];
		$base_max_salarys = $params['base_max_salarys'];
		$salary_types = $params['salary_types'];
		$job_stations = $params['job_stations'];
		$is_salary_shows = $params['is_salary_shows'];
		$err_msg = '';


		//子账号判断，共享模式的账号不能修改分配模式的子账号职位，分配模式子账号不能修改共享模式的子账号职位
		$service_job = new base_service_company_job_job();
		$job_info = $service_job->getJobs($jobids, 'job_id,station,account_id');

		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$company_id = $this->_userid;
		$company_resources  = base_service_company_resources_resources::getInstance($company_id,true,$account_id);
		$companyresources = $company_resources->getCompanyServiceSource(['account_resource']);

		//查询所有子账号信息
		$service_company_account = new base_service_company_account();
		$account_info = $service_company_account->getAccountCompany($company_id,"account_id,company_id,user_name,resource_type");

		$account_info = base_lib_BaseUtils::array_key_assoc($account_info,"account_id");
		foreach((array)$job_info as $key => $value){
			$job_resource_type = $account_info[$value['account_id']]['resource_type'];
			$user_name = $account_info[$value['account_id']]['user_name'];
			if($companyresources['resource_type'] == 2){
				//分配模式,查询所选职位是否包含共享模式职位和其他分配模式账号发布的职位
				if($job_resource_type == 1){
					$error = array ('error' => "职位:{$value['station']}由账号:{$user_name}发布，请登录此账号进行操作", 'status' => false);
					echo json_encode($error);exit();
				}else{
					if($value['account_id'] != $account_id){
						$error = array ('error' => "职位:{$value['station']}由分配子账号:{$user_name}发布，请登录此账号进行操作", 'status' => false);
						echo json_encode($error);exit();
					}
				}
			}else{
				//共享模式,验证所选职位是否包含分配模式职位
				if($job_resource_type == 2){
					$error = array ('error' => "职位:{$value['station']}由分配子账号:{$user_name}发布，请登录此账号进行操作", 'status' => false);
					echo json_encode($error);exit();
				}
			}
		}
		
		//添加数据前验证数据
		foreach ((array)$jobids as $k => $v) {
			
			if (isset($salary_types[ $k ]) && $salary_types[ $k ] == 1) {
				$salary_temp_arr = $this->__changeTempData($min_salarys[ $k ], $max_salarys[ $k ]);
				$minsalary_temp = $salary_temp_arr['min'];
				$maxsalary_temp = $salary_temp_arr['max'];
				
				if (($maxsalary_temp / 2) > $minsalary_temp) {
					echo json_encode(array ('error' => "修改失败，职位 $job_stations[$k] 最大薪资不能超过最小薪资的2倍"));
					exit;
				}
				
				if ($minsalary_temp < 500) {
					echo json_encode(array ('error' => "修改失败，职位 $job_stations[$k] 底薪最小不能低于500"));
					exit;
				}
				
				if ($maxsalary_temp > 100000) {
					echo json_encode(array ('error' => "修改失败，职位 $job_stations[$k] 底薪最大薪资不能超过100000"));
					exit;
				}
				
				$avg_temp_arr = $this->__changeTempData($base_min_salarys[ $k ], $base_max_salarys[ $k ]);
				$minsalary_avg_tmep = $avg_temp_arr['min'];
				$maxsalary_avg_temp = $avg_temp_arr['max'];
				
				if (($maxsalary_avg_temp / 2) > $minsalary_avg_tmep) {
					echo json_encode(array ('error' => "修改失败，职位 $job_stations[$k] 平均工资的最大薪资不能超过最小薪资的2倍"));
					exit;
				}
				
				if ($maxsalary_avg_temp <= $maxsalary_temp) {
					echo json_encode(array ('error' => "修改失败，职位 $job_stations[$k] 提成后的最大薪资不能低于底薪的最大工资"));
					exit;
				}
				
				if ($minsalary_avg_tmep <= $minsalary_temp) {
					echo json_encode(array ('error' => "修改失败，职位 $job_stations[$k] 提成后的最低薪资不能低于底薪的最小工资"));
					exit;
				}
				
				if ($minsalary_avg_tmep < 500) {
					echo json_encode(array ('error' => "修改失败，职位 $job_stations[$k] 平均工资最小薪资不能低于500"));
					exit;
				}
				
				if ($maxsalary_avg_temp > 100000) {
					echo json_encode(array ('error' => "修改失败，职位 $job_stations[$k] 平均工资最大薪资不能超过100000"));
					exit;
				}
				
			} else {
				$salary_temp_arr = $this->__changeTempData($min_salarys[ $k ], $max_salarys[ $k ]);
				$minsalary_temp = $salary_temp_arr['min'];
				$maxsalary_temp = $salary_temp_arr['max'];
				
				if ($minsalary_temp < 500) {
					echo json_encode(array ('error' => "修改失败，职位 $job_stations[$k] 最小薪资不能低于500"));
					exit;
				}
				
				if ($maxsalary_temp > 100000) {
					echo json_encode(array ('error' => "修改失败，职位 $job_stations[$k] 最大薪资不能超过100000"));
					exit;
				}
				
				if (($maxsalary_temp / 2) > $minsalary_temp) {
					echo json_encode(array ('error' => "修改失败，职位 $job_stations[$k] 最大薪资不能超过最小薪资的2倍"));
					exit;
				}
			}
		}
		$log_message = '';
		$result_status = true;
		$job_info = base_lib_BaseUtils::array_key_assoc($job_info, 'job_id');
		if (count($jobids) > 0) {
			foreach ($jobids as $k => $job_id) {
				$update_data = array ();
				$log_message .= " 职位：" . $job_info[ $job_id ]['station'];
				if (isset($salary_types[ $k ]) && $salary_types[ $k ] == 1) { //如果工资类型为1 则是底薪+提成  为0 就是定额工资
					$update_data['salary_type'] = 1;
					
					//定额工资
					$minsalary = intval($min_salarys[ $k ]);
					$maxsalary = intval($max_salarys[ $k ]);
					$salary_arr = $this->__changeTempData($minsalary, $maxsalary);
					$update_data['base_min_salary'] = $salary_arr['min'];
					$update_data['base_max_salary'] = $salary_arr['max'];
					
					//底薪+提成
					$minsalary_avg = intval($base_min_salarys[ $k ]);
					$maxsalary_avg = intval($base_max_salarys[ $k ]);
					$salary_avg_arr = $this->__changeTempData($minsalary_avg, $maxsalary_avg);
					$update_data['min_salary'] = $salary_avg_arr['min'];
					$update_data['max_salary'] = $salary_avg_arr['max'];
					
					$log_message .= "，薪资修改为：底薪+提成，定额工资：" . $minsalary . " - " . $maxsalary . "，底薪+提成：" . $minsalary_avg . " - " . $maxsalary_avg . "；";
				} else {
					$update_data['salary_type'] = 0;
					//定额工资
					$minsalary = intval($min_salarys[ $k ]);
					$maxsalary = intval($max_salarys[ $k ]);
					$salary_array = $this->__changeTempData($minsalary, $maxsalary);
					
					$update_data['min_salary'] = $salary_array['min'];
					$update_data['max_salary'] = $salary_array['max'];
					$update_data['base_min_salary'] = 0;
					$update_data['base_max_salary'] = 0;
					$log_message .= "薪资修改为：定额工资，定额工资：" . $minsalary . " - " . $maxsalary . "；";
				}
				//取消薪资面议
				$service_job->modifyJobSalary($job_id, $this->_userid, $update_data);
			}
			
			//---------添加操作日志--------
			$common_oper_type = new base_service_common_account_accountoperatetype();
			$service_oper_log = new base_service_company_companyaccountlog();
			$common_oper_src_type = new base_service_common_account_accountlogfrom();
			$insertItems = array (
				"company_id"   => $this->_userid,
				"source"       => $common_oper_src_type->website,
				"account_id"   => base_lib_BaseUtils::getCookie('accountid'),
				"operate_type" => $common_oper_type->job_mordModSalary,
				"content"      => "批量修改职位薪资，详情：" . $log_message,
				"create_time"  => date("Y-m-d H:i:s", time())
			);
			$service_oper_log->addLogToMongo($insertItems);
			//-------------END------------
			
		}
		
		echo json_encode(array ('success' => '批量修改薪资成功'));
		
		return;
	}
	
	/**
	 * 比较数据大小
	 * @param $min ,$max
	 * @return array
	 */
	private function __changeTempData($min, $max) {
		if ($min > $max) {
			$min = $min - $max;
			$max = $max + $min;
			$min = $max - $min;
		}
		
		return array ('min' => intval($min), 'max' => intval($max));
	}
	
	/**
	 * 编辑和重新发布跳转资料完善页面
	 * @param  [type] $require_arr [description]
	 * @return [type]              [description]
	 */
	private function notMemberJobAddUpdate($require_arr) {
		if (empty($require_arr))
			return false;
		
		$service_company = new base_service_company_company();
		$company = $service_company->getCompany($this->_userid, 1, 'company_id,company_flag,company_name,property_id,size_id,area_id,calling_ids,address,postcode,homepage,info,hr_manager,hr_manager_sex,hr_tel,show_email,linkman,linkman_sex,open_linkman,linkman_station,link_tel,open_tel,fax,open_fax,link_mobile,open_mobile,company_logo_path,company_video_path,company_video_name,email,company_bright_spot,company_shortname');
		$this->_aParams['company'] = $company;
		
		$xml = SXML::load('../config/company/company.xml');
		if (!is_null($xml)) {
			$VirtualName = $xml->VirtualName;//虚拟目录名 CompanyImages
			$LogoFolder = $xml->LogoFolder;// <!--logo文件夹名-->
		}
		
		if (base_lib_BaseUtils::nullOrEmpty($company['company_logo_path'])) {
			$this->_aParams['logo'] = base_lib_Constant::STYLE_URL . "/img/job/newjob/newJob_57.png";
		} else {
			$this->_aParams['logo'] = base_lib_Constant::UPLOAD_FILE_URL . '/' . $VirtualName . '/' . $LogoFolder . '/' . $company['company_logo_path'];
		}
		
		//获取企业固定电话信息
		$this->_aParams['phone_infor'] = $this->get_telephone_infor($company["link_tel"]);
		
		//获取企业视频信息
		$this->_aParams["video_infor"] = $this->get_video_infor($company["company_video_name"], $company["company_video_path"]);
		
		//获得企业行业数据
		$calling_service = new base_service_common_calling();
		$calling_ids = $company['calling_ids'];
		$calling_arr = array ();
		if (!base_lib_BaseUtils::nullOrEmpty($calling_ids)) {
			$calling_arr = explode(",", $calling_ids);
			if (count($calling_arr) > 2) {
				$calling_arr = array_slice($calling_arr, 0, 2);
			}
		}
		
		$calling_names = array ();
		$this->_aParams['calling_arr'] = $calling_arr;
		if (!base_lib_BaseUtils::nullOrEmpty($calling_arr[0])) {
			//获得主行业名称
			$calling_names[0] = $calling_service->getCallingName($calling_arr[0]);
		}
		if (!base_lib_BaseUtils::nullOrEmpty($calling_arr[1])) {
			//获得主行业名称
			$calling_names[1] = $calling_service->getCallingName($calling_arr[1]);
		}
		$this->_aParams['calling_names'] = $calling_names;
		
		// 获取信息完善度
		$companyInfoPercent = 0;
		$no_complete = $service_company->getComPercentAndNoComplete($this->_userid, false, $companyInfoPercent);
		$this->_aParams['precent'] = $companyInfoPercent . '%';
		$this->_aParams['no_complete'] = $no_complete;
		
		// 获取公司性质码表信息
		$properties_service = new base_service_load(new base_service_common_comproperty());
		$properties = $properties_service->getAll();
		$company_property = $properties_service->getComproperty($company["property_id"]);
		$this->_aParams["properties"] = $properties;
		$this->_aParams["company_property"] = $company_property;
		
		// 获取公司规模码表信息
		$service_comsize = new base_service_load(new base_service_common_comsize());
		$comsizes = $service_comsize->getAll();
		$company_size = $service_comsize->getComsize($company["size_id"]);
		$this->_aParams['comsizes'] = $comsizes;
		$this->_aParams["company_size"] = $company_size;
		//$company_flag = base_lib_Rewrite::getFlag('company', $company['company_id']);
		
		$service_reward = new base_service_common_reward();
		$all_reward_data = $service_reward->getAll();
		$this->_aParams["all_reward_data"] = $all_reward_data;
		
		//获得公司已有的福利
		$service_company = new base_service_company_company();
		$company_rewards = $service_company->getCompany($this->_userid, 1, 'company_reward_ids,company_other_reward');
		
		//公司默认福利
		$company_default_rewards = $company_rewards['company_reward_ids'];
		$this->_aParams["hidDefaultReward"] = $company_default_rewards;
		if (!base_lib_BaseUtils::nullOrEmpty($company_default_rewards)) {
			$company_default_rewards_arr = explode(',', $company_default_rewards);
		} else {
			$company_default_rewards_arr = array ();
		}
		
		//公司其他福利
		$company_other_rewards = $company_rewards['company_other_reward'];
		$this->_aParams["hidOtherReward"] = $company_other_rewards;
		if (!base_lib_BaseUtils::nullOrEmpty($company_other_rewards)) {
			$company_other_rewards_arr = explode(',', $company_other_rewards);
		} else {
			$company_other_rewards_arr = array ();
		}
		$this->_aParams['company_other_rewards'] = $company_other_rewards_arr;
		$this->_aParams['company_default_rewards'] = $company_default_rewards_arr;
		
		// 读取配置xml文件
		$xml = SXML::load('../config/company/company.xml');
		if (!is_null($xml)) {
			$max_photo_count = $xml->PhotoMaxCount;
			$photo_virt_path = base_lib_Constant::UPLOAD_FILE_URL . '/' . $xml->VirtualName . '/' . $xml->PhotoFolder . '/' . $company['company_id'] . '/';
			$photo_thumb_suffix = $xml->PhotoThumbSuffix;
			$singel_photo_count = $xml->PhotoSingelCount;
			//logo
			$logofolder = $xml->LogoFolder;
			$logoTempfolder = $xml->LogoTempFolder;
			$virtualName = $xml->VirtualName;
		}
		
		$logo_virt_path = base_lib_Constant::UPLOAD_FILE_URL . '/' . $virtualName . '/' . $logoTempfolder . '/';
		$this->_aParams['title'] = '企业资料完善';
		$this->_aParams['singel_photo_count'] = $singel_photo_count;
		$this->_aParams['photo_virt_path'] = $photo_virt_path;
		$this->_aParams['logo_virt_path'] = $logo_virt_path;
		
		$service_baidumap = new base_service_company_companycooperaterbaidumap();
		$map = $service_baidumap->getCompanyCooperateBaiduMapByCompanyID($this->_userid);
		
		$this->_aParams['map'] = $map;
		$this->_aParams['upload_cookie_userid'] = $this->_userid;
		$this->_aParams['upload_cookie_nickname'] = $this->_username;
		$this->_aParams['upload_cookie_usertype'] = $this->_usertype;
		$this->_aParams['upload_cookie_userkey'] = base_lib_BaseUtils::getCookie('userkey');
		$this->_aParams['upload_cookie_tick'] = base_lib_BaseUtils::getCookie('tick');
		$this->_aParams['step'] = 1;
		
		return $this->render('job/improvecompanyinfo.html', $this->_aParams);
	}
	
	/**
	 * 非会员职位添加处理
	 * @param $require_arr
	 * @param $audit_arr
	 * @return mixed
	 */
	private function notMemberJobAdd($require_arr, $adult_arr) {
		$this->_aParams['companyinfofull'] = empty($require_arr) && empty($adult_arr);
		
		$service_company = base_service_company_company::getInstances();
		$company = $service_company->getCompany($this->_userid, 1, 'company_id,company_flag,'
		                                                      . 'company_name,property_id,size_id,area_id,calling_ids,address,postcode,is_audit,'
		                                                      . 'homepage,info,hr_manager,hr_manager_sex,hr_tel,show_email,linkman,linkman_sex,'
		                                                      . 'open_linkman,linkman_station,link_tel,open_tel,fax,open_fax,link_mobile,'
		                                                      . 'open_mobile,company_logo_path,company_video_path,company_video_name,email,'
		                                                      . 'company_bright_spot,company_shortname,company_reward_ids,company_other_reward');
		
		$this->_aParams['company'] = $company;
		$xml = SXML::load('../config/company/company.xml');
		if (!is_null($xml)) {
			$VirtualName = $xml->VirtualName;//虚拟目录名 CompanyImages
			$LogoFolder = $xml->LogoFolder;// <!--logo文件夹名-->
			$logoTempfolder = $xml->LogoTempFolder;
		}
		
		$this->_aParams['logo'] = empty($company['company_logo_path'])
			? base_lib_Constant::STYLE_URL . "/img/job/newjob/newJob_57.png"
			: base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $VirtualName . '/' . $LogoFolder . '/' . $company['company_logo_path'];
		
		$this->_aParams['logo_virt_path'] = base_lib_Constant::UPLOAD_FILE_URL . '/' . $VirtualName . '/' . $logoTempfolder . '/';
		
		//获取企业固定电话信息
		$this->_aParams['phone_infor'] = $this->get_telephone_infor($company["link_tel"]);
		
		//获取企业视频信息
		$this->_aParams["video_infor"] = $this->get_video_infor($company["company_video_name"], $company["company_video_path"]);
		
		//获得企业行业数据
		$calling_service = new base_service_common_calling();
		$calling_arr = array_slice(explode(",", $company['calling_ids']), 0, 2);
		
		$this->_aParams['calling_arr'] = $calling_arr;
		
		//获得主行业名称
		if (!empty($calling_arr[0]))
        {
            $calling_names[0] = $calling_service->getCallingName($calling_arr[0]);
            $service_callingadvantage = new base_service_common_callingadvantage();
            $this->_aParams['spots'] = $service_callingadvantage->getAdvantage($calling_arr[0]);
        }
		
		//获得次行业名称
		if (!empty($calling_arr[1]))
			$calling_names[1] = $calling_service->getCallingName($calling_arr[1]);
		
		$this->_aParams['calling_names'] = $calling_names;
		
		// 获取信息完善度
		$no_complete = $service_company->getComPercentAndNoComplete($this->_userid, false, $companyInfoPercent = 0);
		$this->_aParams['precent'] = $companyInfoPercent . '%';
		$this->_aParams['no_complete'] = $no_complete;
		
		// 获取公司性质码表信息
		$properties_service = new base_service_common_comproperty();
		$this->_aParams["properties"] = $properties_service->getAll();
		$this->_aParams["company_property"] = $properties_service->getComproperty($company["property_id"]);
		
		// 获取公司规模码表信息
		$service_comsize = new base_service_common_comsize();
		$this->_aParams['comsizes'] = $service_comsize->getAll();
		$this->_aParams["company_size"] = $service_comsize->getComsize($company["size_id"]);
		
		// 所有福利
		$service_reward = new base_service_common_reward();
		$this->_aParams["all_reward_data"] = $service_reward->getAll();
		
		//公司默认福利
		$this->_aParams["hidDefaultReward"] = $company['company_reward_ids'];
		$this->_aParams['company_default_rewards'] = empty($company['company_reward_ids']) ? [] : explode(',', $company['company_reward_ids']);
		
		//公司其他福利
		$this->_aParams["hidOtherReward"] = $company['company_other_reward'];
		$this->_aParams['company_other_rewards'] = empty($company['company_other_reward']) ? [] : explode(',', $company['company_other_reward']);
		
		// 百度地图
		$service_baidumap = new base_service_company_companycooperaterbaidumap();
		$map = $service_baidumap->getCompanyCooperateBaiduMapByCompanyID($this->_userid);
		$this->_aParams['map'] = $map;
		
		$this->_aParams['title'] = '企业资料修改';
		$this->_aParams['upload_cookie_userid'] = $this->_userid;
		$this->_aParams['upload_cookie_nickname'] = $this->_username;
		$this->_aParams['upload_cookie_usertype'] = $this->_usertype;
		$this->_aParams['upload_cookie_userkey'] = base_lib_BaseUtils::getCookie('userkey');
		$this->_aParams['upload_cookie_tick'] = base_lib_BaseUtils::getCookie('tick');
		$this->_aParams['step'] = 1;
		return $this->render('job/improvecompanyinfo.html', $this->_aParams);
	}
	
	/**
	 * 非会员职位添加处理
	 * @param $require_arr
	 * @param $audit_arr
	 * @return mixed
	 */
	private function notMemberJobAddSuccess($station) {
		$service_company = new base_service_load(new base_service_company_company());
		
		$this->_aParams['member_info'] = 'notmember';
		$this->_aParams['is_can_sue_job'] = $this->_isCanSueJob($this->_userid);
		
		$company = $service_company->getCompany($this->_userid, null, 'com_level,start_time,end_time,is_audit,audit_state,linkman,link_tel,is_effect');
		if (empty($company)) {
			return;
		}

		$resources_service = new base_service_company_resources_resources($this->_userid);

		$audit_info = $resources_service->getCompanyAuditStatusV2();

		$auditStateService = new base_service_company_licenceauditstate();
		$this->_aParams['is_audit'] = ($company['is_audit'] == $auditStateService->pass || $company['audit_state'] == 4) ? true : false;
		
		$this->_aParams['linkman'] = $company['linkman'];
		$this->_aParams['linktel'] = $company['link_tel'];
		$this->_aParams['title'] = '职位发布成功';
		$this->_aParams['station'] = $station;
		$this->_aParams["company"] = $company;
		
		//营业执照的审核状态 start
		$is_audit = 0;//营业执照的未认证
		
		if ($company['is_audit'] == '1') {
			if ($company['audit_state'] == '1' || $company['audit_state'] == '4') {
				$is_audit = 1; //营业执照的已经认证
			}
			if ($company['audit_state'] == '2' || $company['audit_state'] == '3') {
				$is_audit = 4; //营业执照的已经认证,但在临时和补办状态中
			}
		} else if ($company['is_audit'] == '2') {
			$is_audit = 2; //营业执照的认证中
		} else if ($company['is_audit'] == '0') {
			$is_audit = 3;//营业执照的认证未通过
		}
		
		$this->_aParams['info_audit'] = $is_audit;         //由于取不到后台asp的公司的初审状态，暂时以营业执照的状态判断
		$this->_aParams['licence'] = $is_audit;
		$this->_aParams['letter_audit_type'] = $audit_info['letter_audit_type'];

		//营业执照的审核状态 end
		
		/** =============== 获取招聘顾问 part:start=================== **/
		$domain = $this->GetDomainInfor();
		$companyStateService = new base_service_company_comstate();
		$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
		$this->_aParams["hasHRManager"] = false;
		if (!empty($companyState)) {
			$hrManager = $this->GetHRManager($companyState["net_heap_id"]);
			if (!is_null($hrManager)) {
				$this->_aParams["hasHRManager"] = true;
				$headPhoto = base_lib_BaseUtils::nullOrEmpty($hrManager["head_photo_url"]) ? $domain["defaultPhoto"] : $hrManager["head_photo_url"];
				$hrManager["head_photo_url"] = $domain["image"] . "/" . $domain["photo"] . "/" . $headPhoto;
				$this->_aParams["hrManager"] = $hrManager;
			}
		}
		//判断企业的必填资料是否完善
		$company_not_update = $this->__getCompanyNotUpdate();
		$showTop = false;
		if (count($company_not_update) > 0) {
			$showTop = true;
		}
		$this->_aParams["showTop"] = $showTop;
		
		/** =============== 获取招聘顾问 part:end=================== **/
		return $this->render('job/addsuccess_notmem.html', $this->_aParams);
	}
	
	private function get_telephone_infor($telephone) {
		$telephone_infor = array ('zone' => '', 'phone' => '', 'ext' => '');
		preg_match("/^([0-9]+)-([0-9]+)(\(([0-9]+)\))?$/", $telephone, $regs);
		if (count($regs) <= 0) {
			$telephone_infor["phone"] = $telephone;
			
			return $telephone_infor;
		}
		if ($regs[1] != "")
			$telephone_infor["zone"] = $regs[1];
		if ($regs[2] != "")
			$telephone_infor["phone"] = $regs[2];
		if ($regs[4] != "") {
			$telephone_infor["ext"] = $regs[4];
		}
		
		return $telephone_infor;
	}
	
	private function get_video_infor($video_name, $video_path) {
		$video_infor = array ("video_name" => "视频名称", "video_path" => "优酷、土豆等网站的视频url地址");
		if ($video_name != "") {
			$video_infor["video_name"] = $video_name;
		}
		if ($video_path != "") {
			$video_infor["video_path"] = $video_path;
		}
		
		return $video_infor;
	}
	
	private function matchCompanyCalling() {
		/*==============判断该企业是否有行业类别===================*/
		$companyService = new base_service_company_company();
		$company = $companyService->getCompany($this->_userid, true, 'company_id,is_effect,com_level,start_time,end_time,effect_job_end_time,hr_manager_sex,hr_tel,company_logo_path,company_bright_spot,calling_id,calling_ids');
		//修改行业信息
		$service_companycalling = new base_service_company_companycalling();
		$latest_setting = $service_companycalling->select("company_id={$this->_userid} and is_effect=1", "_stamp_remark", "", "order by company_calling_id desc limit 1")->items;
		//未在招聘中且行业匹配到特殊行业的企业
		$matchTime = strtotime('2015-07-09 18:00:00');//行业调整上线时间
		$is_alter = true;//是否已选择行业,默认未选择
		if (!empty($latest_setting) && strtotime($latest_setting[0]['_stamp_remark']) > $matchTime) {
			$is_alter = false;
		}
		$thistime = time();//当前时间
		$calling_mark = false;//默认为招聘中企业
		$notMarkCallingids = array ('11' => 11, '50' => 50);
		if ($is_alter && $company['is_effect'] && $company['calling_ids'] && $company["effect_job_end_time"] > $thistime && strtotime($company["end_time"]) > $thistime) {
			$calling_ids = explode(',', $company['calling_ids']);
			foreach ($calling_ids as $v) {
				if ($notMarkCallingids[ $v ]) {
					$calling_mark = true;
				}
			}
		}
		
		return $calling_mark;
	}
	
	/**
	 * @desc 承诺职位发布弹窗
	 */
	public function pageMustReply($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		
		$txtReApplyType = base_lib_BaseUtils::getStr($path_data['txtReApplyType'], 'int', 0);
		$jobid = base_lib_BaseUtils::getStr($path_data['jobid'], 'int', 0);
		
		$base_reply_type = 0;
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid);
		
		if ($jobid != 0) {
			//修改职位 判断该职位下是否有未处理的简历 若有则必须先处理
			$statistics = $this->getNotReplyNum($jobid);
			$this->_aParams['no_reply_num'] = $statistics[0]['no_reply_num'];
			
			$service_job = new base_service_company_job_job();
			$current_job = $service_job->getJob($jobid, 'job_id,re_apply_type,company_id');
			
			if (base_lib_BaseUtils::nullOrEmpty($current_job) || !in_array($current_job['company_id'], $company_resources->all_accounts)) {
				return;
			}
			
			$base_reply_type = $current_job['re_apply_type'];
		}
		
		$this->_aParams['jobid'] = $jobid;
		$this->_aParams['txtReApplyType'] = $txtReApplyType;
		
		$last_banned_day = 0;//解禁剩余天数
		$last_mustreply_job = 0;//剩余承诺职位数
		$mustreply_job_count = 0;//总承诺职位数
		$mustreply_job_status = $this->canAddMustReplyJob($jobid, $last_banned_day, $last_mustreply_job, $mustreply_job_count);
		
		// $mustreply_job_status 1、能正常发布职位 2、非会员不能发布承诺职位 3、该企业在禁用期 4、该企业发布承诺职位数已满
		if ($mustreply_job_status == 4 && $base_reply_type > 0) {
			$mustreply_job_status = 1; //判断当前职位是不是成功职位 如果是 则 如果是承诺职位已满的装备 改为 未满
		}
		
		$this->_aParams['mustreply_job_status'] = $mustreply_job_status;
		$this->_aParams['last_banned_day'] = $last_banned_day;
		$this->_aParams['last_mustreply_job'] = $last_mustreply_job;
		$this->_aParams['mustreply_job_count'] = $mustreply_job_count;
		
		return $this->render("./job/mustreply.html", $this->_aParams);
	}
	
	/**
	 * @desc 获得职位未处理的简历数
	 */
	private function getNotReplyNum($jobid) {
		if (empty($jobid)) {
			return;
		}
		$applyJobService = new base_service_company_resume_apply();
		$job_id_arr = array ();
		$job_id_arr[] = $jobid;
		$statistics = $applyJobService->getApplyStatisticsByJobIdsVerson2($this->_userid, $job_id_arr);
		
		return $statistics->items;
	}
	
	/**
	 * @desc 判断该企业是否能发布承诺职位
	 * @param $last_banned_day    还有多少天解禁
	 * @param $last_mustreply_job 剩余承诺职位
	 * @params $mustreply_job_count 总承诺职位数
	 * @return intval 1、能正常发布职位 2、非会员不能发布承诺职位 3、该企业在禁用期 4、该企业发布承诺职位数已满
	 *
	 */
	private function canAddMustReplyJob($jobid = 0, &$ast_banned_day = 0, &$last_mustreply_job = 0, &$mustreply_job_count = 0) {
		$service_job = new base_service_company_job_job();
		$job_status = new base_service_common_jobstatus();
		
		$return = 1;  //能发布
		$memberinfo = $this->getCompanyMemberInfo();
		$is_banned = false;//判断该企业的承诺职位禁用
		$is_full = false; //承诺职位数是否已满
		if ($memberinfo == "member") {
			//判断该企业的承诺职位情况
			$service_mustreply = new base_service_company_mustreply();
			$company_mustreply_info = $service_mustreply->getMustReplayByCompanyId($this->_userid, "company_id,job_count,banned_time,update_time");
			
			$thisday = date("Y-m-d");
			if (base_lib_BaseUtils::nullOrEmpty($company_mustreply_info)) {
				//该企业还没有承诺职位数据
				$service_mustreply->addMustReplyCompany($this->_userid);//可以发布承诺职位
			} else {
				if (!base_lib_BaseUtils::nullOrEmpty($company_mustreply_info['banned_time']) && $thisday <= date("Y-m-d", strtotime($company_mustreply_info['banned_time']))) {
					$is_banned = true;
					$ast_banned_day = floor((strtotime($company_mustreply_info['banned_time']) - strtotime($thisday)) / (24 * 60 * 60));
				}
			}
			
			$mustreply_job_count = base_lib_BaseUtils::nullOrEmpty($company_mustreply_info) ? 1 : $company_mustreply_info['job_count'];//默认承诺职位数 升级后为3
			
			// 获取企业所有关联company_id
			$company_resources = base_service_company_resources_resources::getInstance($this->_userid);
			
			// 获取改企业的承诺职位数量，若超过可发布承诺职位数 则不能再发布承诺职位
			$field = "job_id,job_flag,station,issue_time,check_state,end_time,status,order_no,urgent_end_time,re_apply_type";
			$mustreply_jobs = $service_job->getMustReplyJobS($company_resources->all_accounts, null, $job_status->pub, $job_status->stop_use, $field);
            //当月餐饮免费会员发布职位限制
            $base_service_solr_job = new base_service_solr_job();
            $jobList = $base_service_solr_job->GetCompanyAlreadyPublishJobByCompanyId($this->_userid,date('Y-m-01',time()),10);
            $company_service = new base_service_company_company();
            $company = $company_service->getCompany($this->_userid, 1, "com_level,site_type");
            if ($company['com_level']==1 && $company['site_type']==4){
                if (count($jobList)>=5){
                    $is_full = true;
                }
            }else{
                if (count($mustreply_jobs) >= $mustreply_job_count) {
                    $is_full = true;
                } else {
                    $last_mustreply_job = $mustreply_job_count - count($mustreply_jobs);
                }
            }

		} else {
			return 2; //非会员
		}
		
		//已满
		if ($jobid > 0) {
			$service_job = new base_service_company_job_job();
			$job = $service_job->getJob($jobid, 'job_id,re_apply_type,end_time,status');
			
			//判断当前职位是否是重新发布 还是修改
			$today = date("Y-m-d");
			if (strtotime($job['end_time']) < strtotime($today) || $job['status'] == 0) {
				$job['re_apply_type'] = 0; //如果是重新发布 则将承诺职位修改为非承诺职位
			}
			
			if ($job['re_apply_type'] > 0) {
				return $return;
			}
		}
		
		if ($is_banned) {
			return 3;
		}
		
		if ($is_full) {
			return 4;
		}
		
		//被禁用
		
		return $return;
	}
	
	/**
	 * @desc 一键回绝某个职位下所有待处理简历
	 */
	public function pageRefuseAllNotReplyByJob($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$jobid = base_lib_BaseUtils::getStr($path_data['jobid'], 'int', 0);
		if ($jobid == 0) {
			echo header("Content-type:text/plain;charset=utf-8");
			echo json_encode(array ('error' => '参数错误'));
			
			return;
		}
		$service_job_apply = new base_service_company_resume_apply();
		$apply_status = new base_service_company_resume_applystatus();
		$apply_list = $service_job_apply->getApplyList($this->_userid, $jobid, $apply_status->no_reply, 'apply_id')->items;
		$apply_ids = base_lib_BaseUtils::getPropertys($apply_list, "apply_id");
		if (base_lib_BaseUtils::nullOrEmpty($apply_ids)) {
			echo header("Content-type:text/plain;charset=utf-8");
			echo json_encode(array ('error' => '该职位没有待处理简历'));
			
			return;
		}
		$is_pause = false;
		$result = false;
		foreach ($apply_ids as $id) {
			$id = base_lib_BaseUtils::getStr($id, 'int', null);
			if (is_null($id)) {
				continue;
			}
			$content = "非常荣幸收到您的简历，在我们认真阅读您的简历之后，发现您的简历与该职位的定位有些不匹配，因此我们不得不遗憾的通知您无法进入面试。但您的信息我们已录入我司人才储备库，当有与您履历相匹配的职位时，我们将第一时间联系您，希望在未来我们有机会能一起奋斗拼搏。再次感谢您对我们公司的信任，祝您早日找到满意的工作。";
			
			$result = $service_job_apply->refusedReplayV2($id, $this->_userid, $content, false);
			if ($result === false) {
				$is_pause = true;
				break;
			}
		}
		if ($is_pause) {
			echo header("Content-type:text/plain;charset=utf-8");
			echo json_encode(array ('error' => '程序异常中断，请重试...'));
			
			return;
		}
		if ($result) {
			echo header("Content-type:text/plain;charset=utf-8");
			echo json_encode(array ('success' => '一键回绝所有待处理简历成功'));
			
			return;
		}
	}
	
	//承诺职位相关规则
	public function pageAboutMustReply($inPath) {
		return $this->render("./job/aboutmustreply.html", $this->_aParams);
	}
	
	//承诺职位详情页面
	public function pageMustReplyDetail($inPath) {
		return $this->render("./job/mustreplydetail.html", $this->_aParams);
	}
	
	public function pageSearchSimilarjobs($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$jobsort = base_lib_BaseUtils::getStr($path_data['main_jobsort'], 'string', '');
		$hddComId = base_lib_BaseUtils::getStr($path_data['hddComId'], 'int', '');
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', '');
		if (empty($hddComId)) {
			$hddComId = $this->_userid;
		}
		if (empty($jobsort)) {
			echo json_encode(array ("code" => 'error', 'msg' => '请选择职位类别'));
			exit;
		}
		//获取公司下面的职位
		$serivce_job = new base_service_company_job_job();
		
		$job = $serivce_job->getJobList($hddComId, '', '', 'job_id,status,station,end_time,check_state,jobsort_ids');
		$job = base_lib_BaseUtils::array_key_assoc($job, 'job_id');
		
		$thisJob = array ();
		if (!empty($job_id)) {
			$thisJob = $serivce_job->getJob($job_id, 'job_id,status,station,end_time,check_state,jobsort_ids');
		}
		
		if (!empty($job[ $job_id ]) && $job[ $job_id ]['jobsort_ids'] == $thisJob['jobsort_ids']) {
			unset($job[ $job_id ]);
		}
		
		$job_list = array ();
		if (!base_lib_BaseUtils::nullOrEmpty($job)) {
			foreach ($job as $itemjob) {
				if ($itemjob['end_time'] >= date('Y-m-d') && $itemjob['check_state'] != 2 && $itemjob['status'] == 1 && $itemjob['jobsort_ids'] == $jobsort) {
					array_push($job_list, $itemjob);
				}
				if (count($job_list) >= 3)
					break;
			}
		}
		if (count($job_list) >= 3) {
			$job_stations = base_lib_BaseUtils::getProperty($job_list, 'station');
			$job_stations = implode('”，“', $job_stations);
			echo json_encode(array ("code" => 'excess', 'msg' => '您发布的职位与“' . $job_stations . '”<font style="color:red;">疑似重复</font>，重复职位超过3个将不会被审核通过，是否继续发布职位？'));
			exit;
		}
		echo json_encode(array ("code" => 'success', 'msg' => ''));
		exit;
	}
	
	
	/*
     * 获得企业资料中未填的重要项，在首页中引导出来
     */
	private function __getCompanyNotUpdate() {
		$company_id = $this->_userid;
		$service_company = new base_service_company_company();
		$company = $service_company->getCompany($company_id, '1', 'company_name,property_id,calling_ids,size_id,area_id,address,info,linkman,link_tel,company_logo_path,company_bright_spot,company_shortname');
		$word_array = array ();
		if (!base_lib_BaseUtils::nullOrEmpty($company)) {
			if (base_lib_BaseUtils::nullOrEmpty($company['company_bright_spot'])) {
				$word_array[] = "一句话描述公司";
			}
			if (base_lib_BaseUtils::nullOrEmpty($company['company_shortname'])) {
				$word_array[] = "公司简称";
			}
			if (base_lib_BaseUtils::nullOrEmpty($company['property_id'])) {
				$word_array[] = "企业性质";
			}
			if (base_lib_BaseUtils::nullOrEmpty($company['calling_ids'])) {
				$word_array[] = "公司行业";
			}
			if (base_lib_BaseUtils::nullOrEmpty($company['size_id'])) {
				$word_array[] = "公司大小";
			}
			if (base_lib_BaseUtils::nullOrEmpty($company['address'])) {
				$word_array[] = "详细地址";
			}
			if (base_lib_BaseUtils::nullOrEmpty($company['area_id'])) {
				$word_array[] = "公司地址";
			}
			if (base_lib_BaseUtils::nullOrEmpty($company['linkman'])) {
				$word_array[] = "联系人";
			}
			if (base_lib_BaseUtils::nullOrEmpty($company['link_tel'])) {
				$word_array[] = "联系方式";
			}
//                if(base_lib_BaseUtils::nullOrEmpty($company['company_logo_path'])){
//                    $word_array[] = "公司LOGO";
//                }
			if (base_lib_BaseUtils::nullOrEmpty($company['info'])) {
				$word_array[] = "公司介绍";
			}
		}
		
		return $word_array;
	}
	
	/**
	 * @desc 未认证的企业不能发布职位
	 * @param $type 1 发布 2修改
	 */
	public function _companyNotAudit($type = 1, $template = "aduit") {
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid);
		
		$this->_aParams['member'] = $company_resources->isMember();
		$this->_aParams['account_type'] = $company_resources->account_type;
		
		$companyStateService = new base_service_company_comstate();
		$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
		
		$companyHeapService = new base_service_company_netheap();
		$companyHeap = $companyHeapService->GetNetHeapByID($companyState['net_heap_id'], "own_man");
		
		$xml = SXML::load('../config/config.xml');
		$this->_aParams['tel_head'] = !is_null($xml) ? $xml->TechniquePhone : "023-61627888";
		$this->_aParams['HuiboPhone400'] = $xml->HuiboPhone400;
		
		if (!is_null($companyHeap) && isset($companyHeap["own_man"])) {
			$userService = new base_service_crm_user();
			$this->_aParams["hrManager"] = $userService->GetUsers($companyHeap['own_man'], "user_name,head_photo_url,tel,mobile,qq");
		}
		$this->_aParams["title_name"] = $type == 1 ? "发布" : "修改";
		if ($template == "aduit") {
			return $this->render("job/companynotaudit.html", $this->_aParams);
		} else {
			return $this->render("job/companynotservice.html", $this->_aParams);
		}
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
    
    #region    技能标签相关
	/**
	 * 根据职位类别和person_id 获取技能标签
	 * User:hujian 2019/10/18 16:08:27
	 */
    
    public function pageGetJobsortSkillTags($inPath){
        $pathdata           = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inpath));
		$jobsort_id         = base_lib_BaseUtils::getStr($pathdata['jobsort_id'],'string');
		$job_id             = base_lib_BaseUtils::getStr($pathdata['job_id'],'int',0);
		//获取
		$base_service_jobsortskilltag=new base_service_person_resume_jobsortskilltag();
		$jobsort_tag=$base_service_jobsortskilltag->_getJobsortSkillTagsByCompany($jobsort_id,$this->_userid);
		return json_encode($jobsort_tag);
    }
    
    /**
	 *自定义标签添加
	 * User:hujian 2019/10/18 17:13:44
	 */
	function pageAddJobsortSkillTagCustom($inpath){
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inpath));
		$tag_name = base_lib_BaseUtils::getStr($pathdata['tag_name'],'string');
		$jobsort_id = base_lib_BaseUtils::getStr($pathdata['jobsort_id'],'string');
		if(!$jobsort_id){
			echo json_encode(array("status"=>false, "msg"=>"请优先填写职位类别"));
			exit;
		}
		$tag_name=trim($tag_name);
		if(!$tag_name){
			echo json_encode(array("status"=>false, "msg"=>"标签名字不能为空"));
			exit;
		}
		if(mb_strlen($tag_name)>10){
			echo json_encode(array("status"=>false, "msg"=>"标签名称不能超过10字"));
			exit;
		}
		$base_service_jobsortskilltagcustom=new base_service_company_job_jobsortskilltagcustom();
        
		//判断当前标签是否存在
		$jobsortskilltagcustom=$base_service_jobsortskilltagcustom->getTagByName($tag_name, $this->_userid, $jobsort_id,1);
		if($jobsortskilltagcustom){
			echo json_encode(array("status"=>false, "msg"=>"该自定义标签已经存在"));
			exit;
		}
        
        $account_id = base_lib_BaseUtils::getCookie('accountid');
		$tag_id=$base_service_jobsortskilltagcustom->addCustomSkillTag($tag_name,$jobsort_id,$this->_userid,$account_id);
		if(!$tag_id){
			echo json_encode(array("status"=>false, "msg"=>"添加自定义标签失败"));
			exit;
		}
		echo json_encode(array("status"=>true, "msg"=>"添加自定义标签成功",'data'=>array('id'=>$tag_id)));
		exit;
	}
    
    /**
	 * 删除自定义标签
	 * User:hujian 2019/10/18 17:26:19
	 */
	function pageDelJobsortSkillTagCustom($inpath){
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inpath));
		$tag_id = base_lib_BaseUtils::getStr($pathdata['tag_id'],'string');

		if(!$tag_id){
			echo json_encode(array("status"=>false, "msg"=>"获取参数失败"));
			exit;
		}
		$base_service_jobsortskilltagcustom=new base_service_company_job_jobsortskilltagcustom();
		$result=$base_service_jobsortskilltagcustom->delCustomSkillTag($tag_id);
		if(!$result){
			echo json_encode(array("status"=>false, "msg"=>"删除自定义标签失败"));
			exit;
		}
		echo json_encode(array("status"=>true, "msg"=>"删除自定义标签成功"));
		exit;
	}
    
    public function pageJobsortFeedback($inPath){
        return $this->render('job/jobsortfeedback.html', $this->_aParams);
    }
    
    /**
	 * @desc 职位类别反馈
	 */
	public function pageJobsortFeedbackDo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$new_jobsort_name = base_lib_BaseUtils::getStr($path_data['new_jobsort_name'], 'string', '');
		$parent_jobsort = base_lib_BaseUtils::getStr($path_data['parent_jobsort'], 'string', '');
		$remark = base_lib_BaseUtils::getStr($path_data['remark'], 'string', '');
		
		$service_company_job_jobsortfeedback = new base_service_company_job_jobsortfeedback();
		list($res, $msg) = $service_company_job_jobsortfeedback->addData($this->_userid, $new_jobsort_name, $parent_jobsort, $remark);
		
		echo json_encode(['status' => $res, 'msg' => $msg]);
	}
}

?>