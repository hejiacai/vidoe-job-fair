<?php
/**
 * 企业注册
 * @ClassName controller_register 
 * @Desc 单位注册相关操作
 * @author jiangchenglin@huibo.com
 * @date 2013-10-28 上午10:30:46
 */
class controller_register extends components_cbasepage {
  
    private $_catcha_code = "open"; //验证码开启
    
    function __construct() {
        parent::__construct(FALSE);
    }

    /**
     * 注册入口
     */
    public function pageIndex($pathdata) {

		/**
		 * 跳转到新的注册页
		 */
		$new_register_url = base_lib_Constant::MAIN_URL_NO_HTTP."/company".$_SERVER['REQUEST_URI'];
		header("location:{$new_register_url}");die;
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($pathdata));
        $register_type = base_lib_BaseUtils::getStr($pathdata['type'], "string", "");  //若带来参数type-part 的链接 则表示是兼职企业注册
        $site_type     = base_lib_BaseUtils::getStr($pathdata['sitetype'], "string", ""); // 分站类型
		$from = base_lib_BaseUtils::getStr($pathdata['from'], "string", ""); // 链接来源

		if ($register_type == "part") {
			$this->_aParams['is_part'] = true;
		}

    	$this->_aParams['title'] = '汇博人才网_企业注册';
    	$xml = SXML::load('../config/config.xml');
    	if (!is_null($xml)) {
    		$this->_aParams['resume_num'] = $xml->ShowResumeNum;
            $login_limit = $xml->LoginLimit;
    	}

        $this->_aParams['site_type']  = $site_type;
        $this->_aParams['from']  = $from;
        $this->_aParams['referer'] =  $_SERVER['HTTP_REFERER'];
        $this->_aParams['cur_year']   = date('Y');
        $this->_aParams['need_vcode'] = 1;
        $this->_aParams['seed']       = uniqid();

        return $this->render('register.html', $this->_aParams);
    }



    /**
     * 第三步发布职位 
     */
    public function pageAdd($inPath) {
        $this->checkLogin();
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
//		if (!empty($require_arr)) {
//			return $this->notMemberJobAdd($require_arr, $audit_arr);
//		}
		
//		list($is_audit, $audit_params) = $company_resources->getCompanyAuditStatus();
//		if ($is_audit == 5 && $company_location_area != "NOT_CQ_CITY") { //同行认证
//			return $this->_companyNotAudit();
//		}
		$this->_aParams['can_pub_no_salary'] = ($company_resources->account_type == "NotMemberTypSchool" && base_lib_BaseUtils::getCookie("bossuser"));//新需求，后台模拟登录的校招企业，发布校招职位可以不填薪资
//		if ($company_info["is_audit"] == $auditStateService->notval) {
//			$this->redirect_url('/register/add');
//		}
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

		//绑定双薪资
		$more_salary_json[] = ["id" => '0', "name" => "请选择"];
		for ($i = 13; $i <= 20 ; $i++) {
			$more_salary_json[] = ["id" => $i, "name" => "{$i}薪"];
		}
		$this->_aParams['more_salary_json'] = json_encode($more_salary_json);
		
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
        
		return $this->render('register/new_registerstep3.html', $this->_aParams);
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
	 *
	 * 发布职位
	 * @param unknown_type $inPath
	 */
	public function pageJobAddDo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$re_apply_type = base_lib_BaseUtils::getStr($path_data['txtReApplyType'], "int", 0);

		$service_company = new base_service_company_company();
		$current_company = $service_company->getCompany($this->_userid, 1, 'company_id,company_name,end_time,area_id,com_level');
		
		$validator = new base_lib_Validator();
		if (empty($current_company)) {
			return;
		}

		//判断是否能发布职位
		if (!$this->_isCanSueJob($this->_userid))
			$validator->addErr("您的发布职位数已满！");
		
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
		
		//单位名称
		$job['company_name'] = $now_company['company_name'];
		
		//职位性质
		$service_job_type = new base_service_common_jobtype();
		$job['job_type'] = $validator->getEnum($path_data['radJobType'], $service_job_type->getJobtype(), '请选择职位性质');

		//职位名称
		$station = $validator->getStr($path_data['txtStation'], 2, 30, '名称2-30个字符');
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
	    $validator->getNotNull($path_data['txtQuantity'],'请输入招聘人数');
		$job['quantity'] = $validator->getNum($path_data['txtQuantity'], 1, 999, '招聘人数为1～3位整数，且不能为0。');

		//工作地点
		$job['area_id'] = $validator->getStr($path_data['hddArea'],0,0,'请选择区域',false);
        $company_resources      = base_service_company_resources_resources::getInstance($this->_userid);
        $service_area = new base_service_common_area();
        //发布的职位需要限制地区  非重庆主城区的同行认证企业  或者 重庆地区非会员企业 需要限制地区
        if($company_resources->account_type =="NotMemberTypeArea" || ($company_resources->location_area !="CQ_MAIN_CITY" && $current_company["com_level"] <= 1)){
            //判断选择的地区和公司的地区是不是在同一个区
            $is_simple_area = $service_area->checkSimpleArea($job["area_id"],$current_company["area_id"]);
            if(!$is_simple_area){
                $validator->addErr("您只能发布当前公司地区范围内的职位");
            }
        }

		//详细地址描述
		$addinfo = base_lib_BaseUtils::getStr($path_data['txtAddInfo'], 'string', '');
		if (strcmp($addinfo, '详细地址描述，如：环球广场25楼汇博人才网') == 0){
			$addinfo = null;
		}
		$job['add_info'] = $addinfo;
        $map_x = base_lib_BaseUtils::getStr($path_data['map_x'],'string','');
        $map_y = base_lib_BaseUtils::getStr($path_data['map_y'],'string','');
        if($map_x && $map_y){
            $job['map_x'] = $map_x;
            $job['map_y'] = $map_y;
        }
                
		//定额工资，如果为0则是定额工资，如果不为0则为底薪+提成
		$salary_type = base_lib_BaseUtils::getStr($path_data['rd'], 'int', 0);
        $job['salary_type'] = $salary_type;
        // // $dept_name = base_lib_BaseUtils::getStr($path_data['hddJobDept'],'string');
        // if(base_lib_BaseUtils::nullOrEmpty($dept_name)){
        //     $validator->addErr('请选择公司部门');
        // }
        // $job['dept_name'] = $dept_name;
        
        if ($salary_type == 0) {
            //薪资最小值
            $minsalary = $validator->getNotNull($path_data['hddSalary1'], '请输入薪资待遇');
            $validator->getNum($path_data['hddSalary1'], 500, 'max', '薪资待遇不能低于500');
            //薪资最大值
            $maxsalary = $validator->getNotNull($path_data['hddSalary1End'], '请输入薪资待遇');
            $validator->getNum($path_data['hddSalary1End'], 'min', 100000, '薪资待遇不能超过100000');

            if ($minsalary > $maxsalary)
            	list($minsalary, $maxsalary) = [$maxsalary, $minsalary];

			$job['min_salary'] = $minsalary;
			$job['max_salary'] = $maxsalary;
        } else {
            //薪资最小值
            $minsalary = $validator->getNotNull($path_data['hddSalary2'], '请输入薪资待遇');
            $validator->getNum($path_data['hddSalary2'], 500, 'max', '薪资待遇不能低于500');
            
            //薪资最大值
            $maxsalary = $validator->getNotNull($path_data['hddSalary2End'], '请输入薪资待遇');
            $validator->getNum($path_data['hddSalary2End'], 'min', 100000, '薪资待遇不能超过100000');
            if ($minsalary > $maxsalary){
                $minsalary = $minsalary - $maxsalary;
                $maxsalary = $maxsalary + $minsalary;
                $minsalary = $maxsalary - $minsalary;
            }
            if (($maxsalary / $minsalary) > 2) {
                array_push($validator->err,'最大薪资不能超过最低工资的2倍');
                $validator->has_err = true;
            }

            $job['base_min_salary'] = $minsalary;
            $job['base_max_salary'] = $maxsalary;
            
            //平均工资
            //平均薪资最小值
            $minsalary_svg = $validator->getNotNull($path_data['hddSalary3'], '请输入薪资待遇');
            $validator->getNum($path_data['hddSalary3'], 500, 'max', '薪资待遇不能低于500');
            
            //平均最大值
            $maxsalary_svg = $validator->getNotNull($path_data['hddSalary3End'],'请输入薪资待遇');
            $validator->getNum($path_data['hddSalary3End'], 'min', 100000, '薪资待遇不能超过100000');
            
            if ($minsalary_svg > $maxsalary_svg) {
            	list($minsalary_avg, $maxsalary_svg) = [$maxsalary_svg, $minsalary_svg];
            }

            if (($maxsalary_svg/$minsalary_svg) > 2) {
            	$validator->addErr("最大薪资不能超过最低薪资的2倍");
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

		//是否在线沟通
		$job['allow_online_talk'] = base_lib_BaseUtils::getStr($path_data['param_allow_link'],'int','0');
		//发布人ID
		$job['account_id'] = base_lib_BaseUtils::getStr($path_data['param_account_id'],'int','');
		if(empty($job['account_id'])){
			$validator->addErr("无有效发布人ID");
		}
		// 应届生专业
		$job['graduate_profession_ids'] = base_lib_BaseUtils::getStr($path_data['main_professionalSort'],'string','');



		//有效期
		$job['valid_days'] = $validator->getNotNull($path_data['txtValidDays'], '请输入有效期');
		$validator->getNum($path_data['txtValidDays'], 1, 60, '请输入1-60之间的整数');

		// 截至时间
		$job['end_time'] = date('Y-m-d H:i:s', strtotime ("+{$job['valid_days']} day"));

		// 职位的有效状态
		$service_jobstatus = new base_service_common_jobstatus();
        $job['status'] = $service_jobstatus->use;

		//补充说明
		$other_need = base_lib_BaseUtils::getStr($path_data['txtOtherNeed'],'string');
		$other_need = trim($other_need);
		if(!empty($other_need)){
			$job['other_need'] = $other_need;
			$validator->getStr($other_need, 1, 2000, '不能超过2000个字');
		}

		//工作年限
		$workyear = base_lib_BaseUtils::getStr($path_data['hddWorkyear'], 'string', '0');
		$service_workyear = new base_service_common_workyear();
		$wkdes = $service_workyear->getWorkyear($workyear);
		if (!empty($workyear)) {
			if($workyear == 'all'){
				$workyear = 0;
			}else if(empty($wkdes[$workyear])){
				$validator->addErr("工作年限不正确");
			}
		}else{
			$validator->addErr("工作年限不正确");
		}

		$job['work_year_id'] = $workyear;
		
		//是否接受应届生
		$job['allow_graduate'] = base_lib_BaseUtils::getStr($path_data['chkNewGraduate'], 'int', 0);

		//学历
		$degree = base_lib_BaseUtils::getStr($path_data['hddDegree'], 'string', '0');
		$service_degree = new base_service_common_degree();
		$ddes = $service_degree->getDegree($degree);
		if (!empty($degree)) {
			if($degree == 'all'){
				$degree = 0;
			}else if(empty($ddes)){
				$validator->addErr("学历不正确");
			}
		}else{
			$validator->addErr("学历不正确");
		}
		$job['degree_id'] = $degree;

		//性别
		$sex = base_lib_BaseUtils::getStr($path_data['hddSex'],'string','0');
		if (strcmp($sex, '0') != 0){
			$service_sex = new base_service_common_sex();
			$sdes = $service_sex->getSex($service_sex);
			if (empty($sdes)){
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
			$validator->getNum($path_data['hddAge2'], 16, 60,'年龄最大值不正确');
		}

		if ($agelower > $ageupper && $ageupper != 0){
			list($agelower, $ageupper) = [$ageupper, $agelower];
		}

		$job['age_lower'] = $agelower;
		$job['age_upper'] = $ageupper;
		//外语要求
		$language = base_lib_BaseUtils::getStr($path_data['hddLanguage'], 'string', '0');
		if (strcmp($language, '0') != 0){
			$service_language = new base_service_common_languagetype();
			$ldes = $service_language->getLanguageType($language);
			if (empty($ldes)) {
				$validator->addErr("外语要求不正确");
			}
		}
        $job['language_id'] = $language;


		// 是否显示联系方式
		$isshowlinkway = base_lib_BaseUtils::getStr($path_data['showLinkway'], 'int', 0);
		$validator->getNum($path_data['showLinkway'], 0, 1, '请选择联系方式');

		// 是否使用新联系方式
		$newlinkway = base_lib_BaseUtils::getStr($path_data['newLinkway'], 'int', 0);
		$validator->getNum($path_data['newLinkway'], 0, 5, '请选择联系方式');

		// 新联系方式数量
		$newlinkwaycount = base_lib_BaseUtils::getStr($path_data['newLinkWayCount'],'int',1);
		$validator->getNum($path_data['newLinkWayCount'],1,3,'新联系方式数量异常');

		//联系人和联系方式数组
		$linkmantel_arr = array();
		if ($isshowlinkway != 0) {//显示联系方式
			$job['open_linkway'] = 1;
			if ($newlinkway == 1) {//使用新联系方式
				$job['self_linkway'] = 1;
				for ($i = 0; $i < $newlinkwaycount; $i++) {
					$job['linkman']   = $validator->getTel($path_data['txtLinkMan' . ($i + 1)], 1, 10, '联系人不正确');
					$job['link_tel'] = '';
					if(!empty($path_data['txtLinkTel' . ($i + 1)]))
						$job['link_tel']  = $validator->getNum($path_data['txtLinkTel' . ($i + 1)],0,5, '请填写0-5位数字的分机号');
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


		//是否发布下一个职位
		$goonPublish = base_lib_BaseUtils::getStr($path_data['goonPublish'], 'bool', false);

		//发布时间
		$job['issue_time']    = date('Y-m-d H:i:s', time());
		//开始时间
		$job['start_time']    = date('Y-m-d H:i:s', time());
		//创建时间
		$job['create_time']   = date('Y-m-d H:i:s', time());
		//最后发布时间
		$job['last_pub_time'] = date('Y-m-d H:i:s', time());
		//点击次数
		$job['visit_num']     = 0;
		//风险指数
		$job['risk_index']    = 0;
		//审核状态
		$job['check_state']   = 0;
		//入库类型
		$job['storage_type']  = 1;
		//未使用字段
		$job['is_advanced']   = 0;

		//$job['other_reward'] = null;
		$job['work_year']     = null;
		$job['work_year_max'] = null;
		$job['link_id']       = null;
		$job['is_auto_reply'] = '0';
		$job['reply_content'] = null;
        //新套餐默认刷新时间为未刷新
        $job['refresh_time']  = '2014-01-01 00:00:00'; 

		if ($validator->has_err) {
    		echo header("Content-type:text/plain;charset=utf-8");
			echo $validator->toJsonWithHtml();
			return;
    	}

    	$service_job = new base_service_company_job_job();
    	$job_resule = $service_job->addJob($job, $current_company, $linkmantel_arr, $emails_arr);
    	if ($job_resule !== false) {
			//---------添加操作日志--------
			$common_oper_type = new base_service_common_account_accountoperatetype();
			$service_oper_log = new base_service_company_companyaccountlog();
			$common_oper_src_type = new base_service_common_account_accountlogfrom();
			$insertItems=array(
				"company_id"=>$this->_userid,
				"source"=>$common_oper_src_type->website,
				"account_id"=>base_lib_BaseUtils::getCookie('accountid'),
				"operate_type"=>$common_oper_type->job_add,
				"content"=>"发布了新职位：".$job['station'],
				"create_time"=>date("Y-m-d H:i:s",time())
			);
			$service_oper_log->addLogToMongo($insertItems);
			//-------------END------------
    		echo json_encode(array('status'=>'succeed', 'goPublish'=>$goonPublish, 'isOverdue'=>$out_service, 'station'=>str_replace('%2F', '', urlencode($job['station'])), 'job_id'=>$job_resule));
    	} else {
    		echo json_encode(array('status'=>'fail'));
    	}

    	return;
	}
    
    /**
	 * 判断是否能发布职位
	 * 0   表示可以发布职位
	 * 1   表示会员级别不够
     * 2   表示会员期已过期
     * 3   发布职位数已满
	 */
	private function _isCanSueJob($companyID) {
		$comservice_service = new base_service_company_service_comservice();
		$comservice  = $comservice_service->getComService($companyID, 'job_num');
        //获得未开通服务的企业默认职位数量
        $xml = SXML::load('../config/company/company.xml');
        $default_job_num = 0;
        if (!is_null($xml)) {
            $default_job_num = $xml->DefaultJobNum;
        }

		$job_num = (empty($comservice) ? intval($default_job_num) : $comservice["job_num"]); // 企业可发布职位数量

		$service_job_status = new base_service_common_jobstatus();
		$service_job        = new base_service_company_job_job();

		$company_resources = base_service_company_resources_resources::getInstance($companyID);
		$current_job_num = $service_job->getJobCount($company_resources->all_accounts, $service_job_status->pub);
		if ($job_num <= $current_job_num) {
			return false;
		}

		return true;
	}
    
    /**
	 * [_jobPubFail 发布职位失败页面]
	 * @return [type] [description]
	 */
	private function _jobPubFail() {
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid);	

		$this->_aParams['member'] = $company_resources->isMember();
		$this->_aParams['account_type'] = $company_resources->account_type;
		
		$companyStateService = new base_service_company_comstate();
		$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");

		$companyHeapService = new base_service_company_netheap();
		$companyHeap = $companyHeapService->GetNetHeapByID($companyState['net_heap_id'], "own_man");

		$xml = SXML::load('../config/config.xml');
		$this->_aParams['tel_head']      = !is_null($xml) ? $xml->TechniquePhone : "023-61627888";
		$this->_aParams['HuiboPhone400'] = $xml->HuiboPhone400;

		if (!is_null($companyHeap) && isset($companyHeap["own_man"])) {
			$userService = new base_service_crm_user();
			$this->_aParams["hrManager"] = $userService->GetUsers($companyHeap['own_man'], "user_name,head_photo_url,tel,mobile,qq");
		}
		
		return $this->render("job/nojobtoadd.html", $this->_aParams);
	}

    /**
     * 注册提交
     * @param array $inPath
     */
    public function pagePost($inPath)
	{
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$from_newregister = base_lib_BaseUtils::getStr($path_data['from_newregister'], 'string', 'old');
		$callbackparam = base_lib_BaseUtils::getStr($path_data['callbackparam'], 'string', '');
		$val = new base_lib_Validator();
		$company_name = $val->getStr($path_data['txtCompanyName'], 6, 20, '公司名称由6-20个字组成');
		$calling_id = $val->getNotNull($path_data['main_calling'], "请选择公司所处主行业");
		$link_man = $val->getStr($path_data['txtLinkman'], 1, 60, '1-60个字组成');
		$area_id = $val->getNotNull($path_data["hidArea"], "请选择公司所在地区");
		$authCode = $val->getNotNull($path_data["authCode"], "请输入短信验证码");
		$code = base_lib_BaseUtils::getStr($path_data['code'], 'int', 0);
		$flagkey = base_lib_BaseUtils::getStr($path_data['flagkey'], 'string');
//		if (empty($path_data['txtMobilePhone']) && empty($path_data['txtPhoneNo'])) {
//			$val->addErr('请填写手机号或者座机号');
//		}
		$ext = '';
		$zone = '';
		$phone = '';
		$link_tel = '';
		if (!empty($path_data['txtZoneNo']))
			$zone = $val->getMatched($path_data['txtZoneNo'], '/^[0-9]{3}[0-9]?$/', '区号不正确', true);
		if (!empty($path_data['txtPhoneNo']))
			$phone = $val->getTel($path_data['txtPhoneNo'], '固定电话格式不正确');
		if (!empty($path_data['txtExtNo']))
			$ext = $val->getMatched($path_data['txtExtNo'], '/^[0-9]+/', '分机号不正确', true);
		if (!empty($path_data['txtPhoneNo']) && !empty($path_data['txtZoneNo'])) {
			$phone_info['link_tel'] = $this->collect_telephone_number($zone, $phone, $ext);
			$link_tel = base_lib_BaseUtils::getStr($phone_info['link_tel']);
		}
		$mobile_phone = $val->getMobile($path_data['txtMobilePhone'], '您输入的手机号不正确');

		$ip = base_lib_BaseUtils::getIp(0);

		//$user_id         = $val->getStr($path_data['txtUserID'], 3, 30, '请输入长度为3-30位的用户名');
		$user_id = $val->getMatched($path_data['txtUserID'], '/^[a-zA-Z0-9_]*$/', '用户名由字母、数字、下划线组成');
		$password = $val->getPassword($path_data['txtPassword']);
		$verify_password = $val->getPassword($path_data['txtVerifyPassword'], "确认密码");

		$need_vcode = base_lib_BaseUtils::getStr($path_data['hidValidation'], 'int', 0);
		$site_type = base_lib_BaseUtils::getStr($path_data['hidSiteType'], 'int', 0);
		//根据地区来判断是否主城还是区县
		$service_area = new base_service_common_area();
        $cq_main_area = $service_area->getCQMainAreas();
        //获取重庆所有子城市
        $company_top_area     = substr($area_id,0,2);
        $company_main_area     = substr($area_id,0,4);
        if($company_top_area == "03" && !in_array($company_main_area,$cq_main_area)){
            $site_type = 2;
        }


		$is_part = base_lib_BaseUtils::getStr($path_data['is_part'], 'int', 0);
		$captcha = new SCaptchalu();

        if($this->_catcha_code =="open"){
            $seed = $val->getNotNull($path_data['hidSeed'], '');
            $vcode = $val->getStr($path_data['txtCatcha'], 1, 4, '请输入正确的验证码');
            if (empty($vcode)) {
                echo $callbackparam . '(' . json_encode(array("error" => "请输入验证码", "vcodeerr" => 1)) . ")";
                return;
            }
            
            if ($captcha->verify($seed, $vcode) === false) {
                echo $callbackparam . '(' . json_encode(array("error" => "验证码错误", "vcodeerr" => 1)) . ")";
                return;
            }
        }
		//短信验证码
		if(!$val->has_err&&!empty($mobile_phone)&&!empty($authCode)){
			$service_companyregvlid = new base_service_company_companyregvlid();
			$service_companyregvlid->validMobilePhone($ip,$mobile_phone,$authCode,$error);
			if (!empty($error)) {
				$val->addErr($error);
			}
		}
		//错误提示
		if ($val->has_err) {
			if ($from_newregister != 'new_register')
				echo $val->toJsonWithHtml();
			else
				echo $callbackparam . '(' . $val->toJsonWithHtml() . ")";
			return;
		}

        $service_company = new base_service_company_company();
        /*--------------boss端企业违规封号处理- 2018-12-15 --------------*/
        $boos_fordden = $service_company->isBossForbid(null, null, null, $mobile_phone, 'register');
        if ($boos_fordden['is_foribid'] === true) {
            if ($from_newregister == "new_register")
                echo $callbackparam . '(' . json_encode(array('error' => $boos_fordden['msg'])) . ")";
            else
                echo json_encode(array('error' => $boos_fordden['msg']));
            return;
        }

		//密码验证
		if ($password !== $verify_password) {
			echo json_encode(array('error' => ''));
			if ($from_newregister == "new_register")
				echo $callbackparam . '(' . json_encode(array('error' => '两次密码输入不一致')) . ")";
			else
				echo json_encode(array('error' => '两次密码输入不一致'));
			return;
		}

		$service_company = new base_service_company_company();
		//用户名唯一验证
		$ckuser_id = $service_company->doCheckUniqueUserId($user_id);

		if (!base_lib_BaseUtils::nullOrEmpty($ckuser_id)) {
			if ($from_newregister == "new_register")
				echo $callbackparam . '(' . json_encode(array('error' => '该用户名已被注册，请更换')) . ")";
			else
				echo json_encode(array('error' => '该用户名已被注册，请更换'));
			return;
		}

		//企业注册来源
		$service_companyregsource = new  base_service_common_companyregsource();
		if ($is_part == 1) {
			$item['reg_source'] = $service_companyregsource->parttimeexterior;
		} else {
			$item['reg_source'] = $service_companyregsource->fulltimeexterior;
		}
		$item['company_name'] = $company_name;
		$item['link_tel'] = $link_tel;
		$item['linkman'] = $link_man;
		$item['link_mobile'] = $mobile_phone;
		$item['user_id'] = $user_id;
		$md5_password = base_lib_BaseUtils::md5_16($password);
		$item['password'] = $md5_password;
		$item['create_ip'] = $ip;
		$item['site_type'] = $site_type;
		$item['area_id'] = $area_id;
		$item['calling_id'] = $calling_id;
		$item['calling_ids'] = $calling_id;

		$company_id = 0;
		$service_company->addCompany($item, $company_id);
		if ($company_id <= 0) {
			if ($from_newregister == "new_register")
				echo $callbackparam . '(' . json_encode(array('error' => '注册失败')) . ")";
			else
				echo json_encode(array('error' => '注册失败'));
			return;
		}

		//define('DEBUG',1);
		$from = base_lib_BaseUtils::getStr($path_data['from'], "string", ""); // 链接来源
		//判断是不是邀请链接而来，如果是，则要根据设置赠送推广金或会员优惠券
		if (!base_lib_BaseUtils::nullOrEmpty($from)) {
			$service_invitelink = new base_service_company_invitereg_link();
			$invitelink = $service_invitelink->getInviteLinkByParam($from, "link_id,gift_type,gift_fee,gift_end_time,referer,link_end_time");
			//var_dump($invitelink);
			if ($invitelink !== false && !base_lib_BaseUtils::nullOrEmpty($invitelink)) {
				$referer = base_lib_BaseUtils::getStr($path_data['referer'], "string", "");
				//var_dump("ref".$referer);

				if (base_lib_BaseUtils::nullOrEmpty($invitelink['referer']) || strpos($referer, $invitelink['referer']) !== false) {   //检查是否从指定的链接点击而来,为空时不用检查
					$item_invitereg['link_id'] = $invitelink['link_id'];
					if (strtotime($invitelink['link_end_time']) >= strtotime(date('Y-m-d'))) {   //链接未过期
						switch ($invitelink['gift_type']) {
							case '1':    //会员优惠券
								$service_commoncoupon = new base_service_company_commoncoupon();
								$coupon_item['company_id'] = $company_id;
								$coupon_item['coupon_fee'] = $invitelink['gift_fee'];
								$coupon_item['coupon_start_time'] = date('Y-m-d');
								$coupon_item['coupon_end_time'] = $invitelink['gift_end_time'];
								$coupon_item['state'] = 0;
								$coupon_item['create_time'] = date('Y-m-d H:i:s');
								$coupon_item['coupon_code'] = base_lib_BaseUtils::getUniq();
								$service_commoncoupon->addCommonCoupon($coupon_item);
								break;
							case '2':    //推广金
								$service_spread = new base_service_company_spread_spread();
								$spread_items['company_id'] = $company_id;
								$spread_items['total'] = $invitelink['gift_fee'];
								$spread_items['start_time'] = date('Y-m-d');
								$spread_items['end_time'] = $invitelink['gift_end_time'];
								$spread_items['origin'] = 5;
								$service_spread->addCompanySpread($spread_items);
								break;
							default:
								break;
						}
						$item_invitereg['is_effect_time'] = 1;
					} else {
						$item_invitereg['is_effect_time'] = 0;
					}


				}
				$item_invitereg['company_id'] = $company_id;
				$service_invitereg = new base_service_company_invitereg_reg();
				$service_invitereg->addInvitereg($item_invitereg);
			}
		}

		$company['company_id'] = $company_id;
		$company['company_name'] = $company_name;
		$skey = md5($company['company_id'] . $company['company_name'] . 'c' . base_lib_Constant::SYSUSERKEY);

		//一个单位可能存在多个用户同时登录，用一个tick表示不同的用户
		$tick = base_lib_BaseUtils::random(10, 0);

		$account = array(
			"company_id" => $company_id,
			"is_main" => 1,
			"user_id" => $user_id,
			"user_name" => $link_man,//真实姓名，暂时写成联系人
			"password" => $md5_password,
			"mobile_phone" => $mobile_phone,  //手机暂时取联系人手机
			"link_tel" => $link_tel,
			"state" => 1
		);
		$service_company_account = new base_service_company_account();
		$account_id = $service_company_account->addAccount($account);

		//登陆成功记录到cookie
		$aCookie = array(
			'userid' => $company['company_id'],
			'nickname' => $company['company_name'],
			'usertype' => 'c',
			'tick' => $tick,
			'userkey' => $skey,
			'accountid' => $account_id
		);

		//关闭浏览器就失效
		base_lib_BaseUtils::ssetcookie($aCookie, 0, '/', base_lib_Constant::COOKIE_DOMAIN);

		//track与用户的映射
		$this->userTrackBind($company['company_id'], 1, 2);

		$mi = date('Y-m-d H:i');
		$stamp = strtotime($mi);
		$this->setLifetime($this->getSessionid($company['company_id'], $tick,$account_id), $skey . '_' . $stamp);

	    
		// 获取配置
		$xml = SXML::load('../config/config.xml');
		if (!is_null($xml)) {
			$expire_time = $xml->SetVcodeExpireTime;
		}

		//新注册的企业都统一默认添加兼职信息
		$service_partcompany = new base_service_part_company_partcompany();
		$error = "";
		$part_items['company_id'] = $company_id;
		$service_partcompany->addPartCompany($part_items, $error);
		$cache = new base_lib_Cache('redis');
		if ($cache->redis_exists("create_ip_" . $ip) == 0)
			$cache->set("create_ip_" . $ip, 1, intval($expire_time));
		else
			$cache->set("create_ip_" . $ip, $cache->get("create_ip_" . $ip) + 1, intval($cache->redis_ttl("create_ip_" . $ip)));

		//保存动作类型
		//动作类型
		$service_actiontype = new base_service_common_actiontype();
		//用户类型
		$service_actionusertype = new base_service_common_actionusertype();
		//渠道
		$service_actionsource = new base_service_common_actionsource();

		base_lib_BaseUtils::saveAction($service_actiontype->register, $service_actionsource->website, $company_id, $service_actionusertype->company);
		//判断是否有推荐码 或者链接

		$promotion = new base_service_company_promotion_promotion();
		$promotioninvitecode = new base_service_company_promotion_promotioninvitecode();
		$promotionregistered = new base_service_company_promotion_promotionregistered();
		$promotion_id = $promotion->getAction('registered');

		$promotionData = $promotion->getData(array('id' => $promotion_id), 'is_effect,start_time,end_time,area_ids,calling_ids');


		//获取设定的地区 以及 此区域下的所有子地区
		$proCalling_ids = explode(',',$promotionData['calling_ids']);
		$proArea_ids = explode(',',$promotionData['area_ids']);
		$areaList = $proArea_ids;
		$common_area = new base_service_common_area();
		foreach($areaList as $item){
			$file = $common_area->getChildArea($item);
			if(!empty($file)){
				foreach($file as $value){
					array_push($proArea_ids,$value['area_id']);
				}
			}
		}
		//判断活动是否开启
		if($promotionData['is_effect'] !=1 || empty($promotionData)){
			//注册成功
			if ($from_newregister == 'new_register') {
				//info_company_account表中增加该主账户
				echo $callbackparam . '(' . json_encode(array('successMsg' => '注册成功')) . ")";
			} else
				echo json_encode(array('successMsg' => '注册成功'));
			return;
		}
		//判断活动企业是否在活动范围内
		if(!in_array($company_main_area,$proArea_ids) && !in_array($calling_id,$proCalling_ids )){
			//注册成功
			if ($from_newregister == 'new_register') {
				//info_company_account表中增加该主账户
				echo $callbackparam . '(' . json_encode(array('successMsg' => '注册成功')) . ")";
			} else
				echo json_encode(array('successMsg' => '注册成功'));
			return;
		}
		//判断是否注册推广满足活动时间
		if (strtotime($promotionData['start_time']) <= time() && strtotime($promotionData['end_time']) >= time()) {
			$data = array();
			$data['company_id'] = $company_id;
			$data['company_name'] = $company_name;
			$data['area_id'] = $area_id;
			$data['calling_id'] = $calling_id;
			$data['terminal'] = 1;
			$data['promotion_type'] = 3;
			$data['create_time'] = date('Y-m-d H:i:s');
			if (!empty($code)) {
				$parentCompany_id = $promotioninvitecode->getData(array('code' => $code), 'company_id');
				$parentCompany_id = $parentCompany_id['company_id'];
			} else if (!empty($flagkey)) {
				$parentCompany_id = base_lib_Rewrite::getId('company', $flagkey);
			}
			if(!empty($parentCompany_id)){
				$parentCompany = $service_company->getCompany($parentCompany_id, 1, 'company_name,area_id,calling_id');
				$data['promotion_type'] = empty($code) ? 2 : 1;
				$data['promotion_company_id'] = $parentCompany_id;
				$data['promotion_company_name'] = $parentCompany['company_name'];
				$data['promotion_company_area_id'] = $parentCompany['area_id'];
				$data['promotion_company_calling_id'] = $parentCompany['calling_id'];
			}
			$promotionregistered->addData($data);
		}

		//注册成功
		if ($from_newregister == 'new_register') {
			//info_company_account表中增加该主账户
			echo $callbackparam . '(' . json_encode(array('successMsg' => '注册成功')) . ")";
		} else
			echo json_encode(array('successMsg' => '注册成功'));
		return;
	}




    /**
     * 检测用户名
     * @param array $inPath
     */
    public function pageCheckUserName($inPath){
    	$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
    	$user_id = base_lib_BaseUtils::getStr($path_data['txtUserID'],'string','');
    	if(base_lib_BaseUtils::nullOrEmpty($user_id)){
    		echo json_encode(array('error'=>'请输入用户名'));
    		return;
    	}
    	$service_company = new base_service_company_company();
    	$ckuser_id = $service_company->doCheckUniqueUserId($user_id);
    	if(empty($ckuser_id)) {
    		echo json_encode(array('state'=>true));
   		}else {
   			echo json_encode(array('state'=>false));
   		}		
    }

    /**
     * 合并固定电话
     * @param string $zone
     * @param string $phone
     * @param string $ext
     */
	private function collect_telephone_number($zone,$phone,$ext){
		$telephone = $phone;
		if(preg_match("/^[0-9]{3}[0-9]?$/",$zone))
			$telephone = $zone."-".$phone;
		else
			$telephone = "023-".$phone;
		if(preg_match("/^[0-9]+$/",$ext))
			$telephone = $telephone.'('.$ext.')';
		return $telephone;
	}
	
	//验证码
	public function pageVerify($inPath) {			
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$seed = $path_data["seed"];
		$captcha = new SCaptchalu();
		$captcha->conf->type = 3;
		$captcha->conf->mode = 0;//图片模式 文字变色为1
		$captcha->conf->length =4;
		$imageResource = $captcha->getImageResource($seed);
		header("Content-type: image/png");
		if (false !== $imageResource)
		imagepng($imageResource);
	}

}