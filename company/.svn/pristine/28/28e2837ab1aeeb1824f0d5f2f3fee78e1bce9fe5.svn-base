<?php

/**
 * 活动报名
 * @ClassName controller_votesgin
 * @Desc      单位活动报名
 */
class controller_votesgin extends components_cbasepage {
	
	
	function __construct() {
		parent::__construct(false);
	}
	
	/**
	 * 活动报名
	 */
	public function pageIndex($pathdata) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($pathdata));
		$activity_id = base_lib_BaseUtils::getStr($pathdata['activity_id'], "int", "");
		
		$detect = new SMobiledetect();
		if ($detect->isMobile() || $detect->isTablet()) {
			$this->redirect_url2(base_lib_Constant::APP_MOBILE_URL . "/companyvoting/index-id-{$activity_id}");
		}
		
		$service_company = new base_service_company_company();
		$service_licencevalidate = new base_service_company_licencevalidate();
		$service_comsize = new base_service_common_comsize();
		$service_common_area = new base_service_common_area();
		$calling_service = new base_service_common_calling();
		$service_company_voting_votingActivity = new base_service_company_voting_votingActivity();
		$service_company_voting_votingAwards = new base_service_company_voting_votingAwards();
		$service_company_voting_votingSign = new base_service_company_voting_votingSign();
		$service_weixin_weixin = new base_service_weixin_weixin();
		$licenceService = new base_service_company_audit();
		
		$activity_items = [
			'id',
			'activity_name',
			'state',
			'activity_url',
			'activity_code_url',
			'is_effect',
			'create_time',
			'create_user_id',
			'start_date',
			'end_date',
			'max_voting',
			'awards_ids',
			'mobile_banner',
			'pc_banner',
			'activity_end_msg',
		];
		
		$activity = $service_company_voting_votingActivity->GetVotingActivityDataById($activity_id, false, $activity_items);
		if (empty($activity)) {
			return $this->showErrorPage('未找到该活动记录');
		}
		
		$this->_aParams['title'] = $activity['activity_name'];
		$this->_aParams['activity_id'] = $activity_id;
		$this->_aParams['company_id'] = '';
		$this->_aParams["need_upload_licence"] = true;
		$xml = SXML::load('../config/company/company.xml');
		if (!is_null($xml)) {
			$VirtualName = $xml->VirtualName;//虚拟目录名 CompanyImages
//            $licence_folder = $xml->LicenceTempFolder;
			$licence_folder = $xml->LicenceFolder;
			$LogoFolder = $xml->LogoFolder;// <!--logo文件夹名-->
			$logoTempfolder = $xml->LogoTempFolder;
		}
		$this->_aParams['logo_virt_path'] = base_lib_Constant::UPLOAD_FILE_URL . '/' . $VirtualName . '/' . $logoTempfolder . '/';
		$this->_aParams['company_logo_path'] = base_lib_Constant::STYLE_URL . "/img/job/newjob/newJob_57.png";
//         $this->_aParams['company_logo_path'] = "";
		$this->_aParams["licence_file_url"] = base_lib_Constant::STYLE_URL . "/img/job/newjob/newJob_57.png";
		//判断是否登录
		if ($this->isLogin() && $this->_usertype == 'c') {
			$this->_aParams['company_id'] = $this->_userid;
			$company = $service_company->getCompany($this->_userid, 1, 'company_id,company_name,company_shortname,calling_id,size_id,area_id,address,remark,linkman,link_mobile,company_logo_path,company_license_path,info');
			$code_company_logo_path = base_lib_Constant::APP_STYLE_URL . "/img/c/new_index/headlogo.png";
			if (!empty($company['company_logo_path'])) {
				$this->_aParams['company_logo_path'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $VirtualName . '/' . $LogoFolder . '/' . $company['company_logo_path'];
				$code_company_logo_path = $this->_aParams['company_logo_path'];
			}
			
			$licencevaliate = $service_licencevalidate->getLicenceValidateInfo($this->_userid, 'validate_id,company_id,licence_file_url,upload_time');
			if (!empty($licencevaliate["licence_file_url"])) {
				$this->_aParams["licence_file_url"] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . "/" . $VirtualName . "/" . $licence_folder . "/" . $licencevaliate["licence_file_url"];
			}
			
			//已经报名
			$extend_data = $service_company_voting_votingSign->getExtendCompanyData($activity_id, $this->_userid);
			$resources_company_service = new base_service_company_resources_resources($this->_userid);
			$audit_result = $resources_company_service->getCompanyAuditStatusV2();
			if (!empty($audit_result) && !in_array($audit_result['licence_audit_type'], array (0, 4, 5)) && !in_array($audit_result['letter_audit_type'], [0, 3, 4])) {
				$this->_aParams["need_upload_licence"] = false;
			}
			
			$this->_aParams["licence_file_name"] = $licencevaliate["licence_file_url"];
			$this->_aParams["found_date"] = isset($extend_data['found_date']) ? $extend_data['found_date'] : '';
			$this->_aParams["year_profit"] = isset($extend_data['year_profit']) ? $extend_data['year_profit'] : '';
			$this->_aParams["company"] = $company;
			$this->_aParams['area_name'] = $company['area_id'] ? $service_common_area->getArea($company['area_id'], true) : '';
			$this->_aParams['calling_name'] = $company['calling_id'] ? $calling_service->getCallingName($company['calling_id']) : '';
			$this->_aParams["company"]['linkman'] = isset($extend_data['link_man']) ? $extend_data['link_man'] : '';
			$this->_aParams["company"]['link_mobile'] = isset($extend_data['mobile_phone']) ? $extend_data['mobile_phone'] : '';
			
			$this->_aParams["company_code"] = $service_weixin_weixin->GetHuiboWeixinQrcod(2, $extend_data['id'], array (
				'company_name'  => $company['company_name'],
				'company_id'    => $this->_userid,
				'company_photo' => 'http://' . $code_company_logo_path,
				'code_num'      => $extend_data['code_num'],
				'activity_id'   => $activity_id,
				'activity_name' => $activity['activity_name'],
			), 2592000, $service_weixin_weixin::HUIBO_WEIXIN_QRCOD_REDIS_TMP);
		}
		
		$this->_aParams["activity_code"] = $service_weixin_weixin->GetHuiboWeixinQrcod(1, $activity_id, ['activity_id' => $activity_id, 'activity_name' => $activity['activity_name']], 2592000,
		                                                                               $service_weixin_weixin::HUIBO_WEIXIN_QRCOD_REDIS_TMP);
		
		
		$this->_aParams["company_sizes"] = $service_comsize->getAll();
		//奖项
		$this->_aParams["awards"] = $service_company_voting_votingAwards->getVotingAwardsByIds(explode(',', $activity['awards_ids']), 'id,awards_name')->items;
		
		if ($activity['end_date'] && strtotime($activity['end_date'] . ' 23:59:59') < time()) {
			$this->_aParams['activity_end_msg'] = $activity['activity_end_msg'] ? $activity['activity_end_msg'] : '活动已过期';
			
			return $this->render('./activityvote/index.html', $this->_aParams);
		}
		
		return $this->render('./activityvote/index.html', $this->_aParams);
	}
	
	/**
	 * 提交报名
	 * @param array $inPath
	 */
	public function pagePost($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		
		$val = new base_lib_Validator();
		$company_name = $val->getStr($path_data['famouseCompanyName'], 6, 40, '公司名称由6-40个字组成');
		$calling_id = $val->getNotNull($path_data['famouseTradePut'], "请选择公司所处行业");
		$size_id = $val->getNotNull($path_data['size_id'], "请选择公司规模");
		$activity_id = $val->getNotNull($path_data['activity_id'], "缺少必要参数");
		$link_man = $val->getStr($path_data['famouseContacts'], 1, 10, '联系人1-10个字组成');
		$area_id = $val->getNotNull($path_data["famouseArea"], "请选择公司所在地区");
		$address = $val->getStr($path_data["famouseinDetail"], 2, 200, "请填写2-200字公司详细地址");
//		$remark = $val->getStr($path_data["famouseIntro"], 2, 600, "请填写2-600字公司简介");
		$remark = base_lib_BaseUtils::getStr($path_data['famouseIntro'], "string", "");
		$mobile_phone = $val->getMobile($path_data['famousePhone'], '您输入的手机号不正确');
		$licence_file_url = base_lib_BaseUtils::getStr($path_data['licence_file_url'], "string", "");
		$company_logo_path = base_lib_BaseUtils::getStr($path_data['company_logo_path'], "string", "");
		$authCode = base_lib_BaseUtils::getStr($path_data['famouseCode'], "string", "");
		$awards_ids = $val->getNotNull($path_data["famouseAwardPut"], "请选择奖项");
		$found_date = $val->getNotNull($path_data["famouseFormed"], "请填写公司成立时间");
		$year_profit = $val->getNotNull($path_data["famouseProd"], "请填写公司年产值");
		
		if (!is_numeric($year_profit)) {
			$val->addErr('请输入正确的年产值');
		}
		
		$awards_ids = explode(',', $awards_ids);
		if (count($awards_ids) > 2) {
			$val->addErr('最多只能选择2个奖项');
		}
		//错误提示
		if ($val->has_err) {
			echo $val->toJsonWithHtml();
			
			return;
		}
		$ip = base_lib_BaseUtils::getIp(0);
		$site_type = 7;
		$company_id = 0;
		$need_move_logo = false;
		$need_move_licence = false;
		
		$service_company = new base_service_company_company();
		$service_companyregvlid = new base_service_company_companyregvlid();
		$service_company_voting_votingSign = new base_service_company_voting_votingSign();
		$service_licencevalidate = new base_service_company_licencevalidate();
		$service_companyregsource = new  base_service_common_companyregsource();
		$serv_regaudit = new base_service_company_companyregaudit();
		$serv_regaudithistory = new base_service_company_regaudithistory();
		$service_company_voting_votingSignVote = new base_service_company_voting_votingSignVote();
		$service_company_voting_votingActivity = new base_service_company_voting_votingActivity();
		$service_logoaudit = new base_service_company_logoaudit();
		$licenceService = new base_service_company_audit();
		
		$activity_items = [
			'id',
			'activity_name',
			'state',
			'activity_url',
			'activity_code_url',
			'is_effect',
			'create_time',
			'create_user_id',
			'start_date',
			'end_date',
			'max_voting',
			'awards_ids',
			'mobile_banner',
			'pc_banner',
			'activity_end_msg',
		];
		$activity = $service_company_voting_votingActivity->GetVotingActivityDataById($activity_id, false, $activity_items);
		if (empty($activity)) {
			$val->err = ['未找到您要报名的活动'];
			echo $val->toJsonWithHtml();
			
			return;
		}
		$company_item['company_name'] = $company_name;
		$company_item['calling_id'] = $calling_id;
		$company_item['size_id'] = $size_id;
		$company_item['area_id'] = $area_id;
		$company_item['address'] = $address;
		$company_item['info'] = $remark;
//        $company_item['licence_file_url'] = $licence_file_url;
//        $company_item['company_logo_path'] = $company_logo_path;
		//判断是否登录（未登录，注册+报名。 2，已登录，修改+报名）
		$error = '';
		if ($this->isLogin() && $this->_usertype == 'c') {
			//是否已经报名
			$extend_data = $service_company_voting_votingSign->getExtendCompanyData($activity_id, $this->_userid);
			if (!empty($extend_data)) {
				$val->err = ['您已报名该活动，请勿重复报名'];
				echo $val->toJsonWithHtml();
				
				return;
			}
			
			$company = $service_company->getCompany($this->_userid, true, 'link_mobile,company_logo_path');
			//手机号码变动，判断验证码正确性
//            if(!$val->has_err && $mobile_phone !=$company["link_mobile"]){
			if (!$val->has_err) {
				$service_companyregvlid->validMobilePhone($ip, $mobile_phone, $authCode, $error);
				if (!empty($error)) {
					$val->addErr('验证码错误');
				}
			}
			if (empty($company['company_logo_path']) && !$company_logo_path) {
				$val->addErr('请上传企业logo');
			}
			
			$resources_company_service = new base_service_company_resources_resources($this->_userid);
			$audit_result = $resources_company_service->getCompanyAuditStatusV2();
			$licencevaliate = $service_licencevalidate->getLicenceValidateInfo($this->_userid, 'validate_id,company_id,licence_file_url,upload_time');
			if (empty($audit_result) || in_array($audit_result['licence_audit_type'], array (0, 4, 5)) || in_array($audit_result['letter_audit_type'], [0, 3, 4])) {
				if (empty($licencevaliate['licence_file_url']) && !$licence_file_url) {
					$val->addErr('请上传营业执照');
				}
			}
			
			//错误提示
			if ($val->has_err) {
				echo $val->toJsonWithHtml();
				
				return;
			}
			
			if ($company['company_logo_path'] != $company_logo_path) {
				$need_move_logo = true;
			}
			if (empty($audit_result) || in_array($audit_result['licence_audit_type'], array (0, 4, 5)) || in_array($audit_result['letter_audit_type'], [0, 3, 4])) {
				if (empty($licencevaliate['licence_file_url']) && $licence_file_url) {
					$licencevalidate = [
						'company_name' => $company_name,
						'create_time'  => date('Y-m-d H:i:s'),
						'company_id'   => $this->_userid
					];
					$service_licencevalidate->saveLicence($licencevalidate, $licence_file_url);
				}
			}
			
			$company_item['company_logo_path'] = $company['company_logo_path'] ? $company['company_logo_path'] : $company_logo_path;
			//修改企业信息
			$company_item['company_id'] = $this->_userid;
			$company_id = $this->_userid;
			$result = $service_company->updateCompany($company_item, [$calling_id]);
		}
		else {
			$need_move_logo = true;
			if (!$company_logo_path) {
				$val->addErr('请上传企业logo');
			}
			
			if (!$licence_file_url) {
				$val->addErr('请上传营业执照');
			}
			
			$service_companyregvlid->validMobilePhone($ip, $mobile_phone, $authCode, $error);
			if (!empty($error)) {
				$val->addErr('验证码错误');
			}
			//用户名唯一验证
			$user_id = 'huibo_' . $mobile_phone;
			if ($service_company->doCheckUniqueUserId($user_id)) {
				$val->addErr('您的电话已被占用');
			}
			
			//错误提示
			if ($val->has_err) {
				echo $val->toJsonWithHtml();
				
				return;
			}
			
			$password = 'hb1234' . rand(100, 9999);
			$md5_password = base_lib_BaseUtils::md5_16($password);
			
			//企业注册来源
			$company_item['reg_source'] = $service_companyregsource->fulltimeexterior;
			$company_item['user_id'] = $user_id;
			$company_item['password'] = $md5_password;
			$company_item['create_ip'] = $ip;
			$company_item['site_type'] = $site_type;
			$company_item['calling_ids'] = $calling_id;
			$company_item['link_mobile'] = $mobile_phone;
			$company_item['linkman'] = $link_man;
			$company_item['company_logo_path'] = $company_logo_path;
			
			$service_company->addCompany($company_item, $company_id);
			if ($company_id <= 0) {
				echo json_encode(array ('error' => '注册失败'));
				
				return;
			}
			
			$msg = "你已注册汇博网企业版，登录用户名：{$user_id}  登录初始密码：{$password}";
			base_lib_SMS::send(trim($mobile_phone), $msg, 1);
			//上传营业执照
			$licencevalidate = [
				'company_name' => $company_name,
				'create_time'  => date('Y-m-d H:i:s'),
				'company_id'   => $company_id
			];
			$service_licencevalidate->saveLicence($licencevalidate, $licence_file_url);
			
			$company_item['company_id'] = $company_id;
			$skey = md5($company_item['company_id'] . $company_item['company_name'] . 'c' . base_lib_Constant::SYSUSERKEY);
			
			//一个单位可能存在多个用户同时登录，用一个tick表示不同的用户
			$tick = base_lib_BaseUtils::random(10, 0);
			
			$account = array (
				"company_id"   => $company_id,
				"is_main"      => 1,
				"user_id"      => $user_id,
				"user_name"    => $link_man,//真实姓名，暂时写成联系人
				"password"     => $md5_password,
				"mobile_phone" => $mobile_phone,  //手机暂时取联系人手机
				"state"        => 1
			);
			$service_company_account = new base_service_company_account();
			$account_id = $service_company_account->addAccount($account);
			
			//登陆成功记录到cookie
			$aCookie = array (
				'userid'    => $company_item['company_id'],
				'nickname'  => $company_item['company_name'],
				'usertype'  => 'c',
				'tick'      => $tick,
				'userkey'   => $skey,
				'accountid' => $account_id
			);
			
			//关闭浏览器就失效
			base_lib_BaseUtils::ssetcookie($aCookie, 0, '/', base_lib_Constant::COOKIE_DOMAIN);
			
			//track与用户的映射
			$this->userTrackBind($company_item['company_id'], 1, 2);
			
			$mi = date('Y-m-d H:i');
			$stamp = strtotime($mi);
			$this->setLifetime($this->getSessionid($company_item['company_id'], $tick, $account_id), $skey . '_' . $stamp);
			
			
			// 获取配置
//            $xml = SXML::load('../config/config.xml');
//            if (!is_null($xml)) {
//                $expire_time = $xml->SetVcodeExpireTime;
//            }
			
			//新注册的企业都统一默认添加兼职信息
//            $service_partcompany = new base_service_part_company_partcompany();
//            $error = "";
//            $part_items['company_id'] = $company_id;
//            $service_partcompany->addPartCompany($part_items, $error);
//            $cache = new base_lib_Cache('redis');
//            if ($cache->redis_exists("create_ip_" . $ip) == 0)
//                $cache->set("create_ip_" . $ip, 1, intval($expire_time));
//            else
//                $cache->set("create_ip_" . $ip, $cache->get("create_ip_" . $ip) + 1, intval($cache->redis_ttl("create_ip_" . $ip)));
			
			//将单位设为初审通过
			$reg_item = [
				'company_id'  => $company_id,
				'audit_state' => 1,
				'audit_time'  => $this->now
			];
			$serv_regaudit->update(['company_id' => $company_id], $reg_item);
			$service_company->update(array ('company_id' => $company_id), array ('reg_audit_state' => 1));
			//插入审核记录
			$serv_regaudithistory->insert(array (
				                              'company_id'      => $company_id,
				                              'audit_old_state' => 0,
				                              'audit_new_state' => 1,
				                              'audit_time'      => $this->now,
			                              ));
			//保存动作类型
			//动作类型
//            $service_actiontype = new base_service_common_actiontype();
//            //用户类型
//            $service_actionusertype = new base_service_common_actionusertype();
//            //渠道
//            $service_actionsource = new base_service_common_actionsource();
//
//            base_lib_BaseUtils::saveAction($service_actiontype->register, $service_actionsource->website, $company_id, $service_actionusertype->company);
		}
		
		//处理图片
		// 读取配置xml文件
		if ($need_move_logo) {
			$xml = SXML::load('../config/company/company.xml');
			if (!is_null($xml)) {
				$logo_folder = $xml->LogoFolder;
				$logo_temp_folder = $xml->LogoTempFolder;
				$company_image_path = $xml->CompanyImagePath;
				$virtualName = $xml->VirtualName;
			}
			
			//将LOGO移动到正式目录中
			$postvar['newfile'] = "{$virtualName}/{$logo_folder}";
			$postvar['oldfile'] = "{$virtualName}/{$logo_temp_folder}";
			$postvar['names'] = [$company_logo_path];
			$postvar['thumbSuffix'] = '';
			$postvar['authenticate'] = "logo";
			$result_move = base_lib_Uploadfilesv::moveFile($postvar);
			
			$service_logoaudit->addCompanyLogoAudit($company_id, 0);//将logo 设置为未审核
		}
		//添加报名信息
		foreach ($awards_ids as $awards_id) {
			$sgin_data = [
				'found_date'   => $found_date,
				'year_profit'  => $year_profit,
				'link_man'     => $link_man,
				'mobile_phone' => $mobile_phone,
				'company_id'   => $company_id,
				'activity_id'  => $activity_id,
				'awards_id'    => $awards_id,
				'create_time'  => date('Y-m-d H:i:s'),
				'state'        => 1,
				'state_time'   => date('Y-m-d H:i:s'),
				'sign_type'    => 1
			];
			
			$service_company_voting_votingSign->sginDo($sgin_data);
			$service_company_voting_votingSignVote->AddVotingRankingData($activity_id, $awards_id, $company_id, 0, $activity['end_date']);
		}
		
		echo json_encode(['success' => '报名成功', 'company_id' => $company_id]);
		
		return;
	}
	
	public function pageIsLogin() {
		$is_login = $this->isLogin() && $this->_usertype == 'c';
		echo json_encode(['status' => $is_login]);
		
		return;
	}
	
	public function pageAjaxLogin($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$callback = base_lib_BaseUtils::getStr($pathdata['success'], 'string', 'null');
		
		return $this->render('./activityvote/companyajaxlogin.html', $this->_aParams);
	}
	
	function pageAjaxLoginDo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		
		$val = new base_lib_Validator();
		$user_name = $val->getStr($path_data['txtUsername'], 2, 30, '请输入长度为2-30位的用户名');
		$password = $val->getStr($path_data['txtPassword'], 6, 18, '请输入长度为6-16位的密码');
		if ($val->has_err) {
			//将错误打印json
			echo $val->toJsonWithHtml();
			
			return;
		}
		
		$service_company = new base_service_company_company();
		$serivce_com_log = new base_service_company_loginlog();
		$service_forbid = new base_service_company_accountsforbid();
		$servic_company_change = new base_service_company_changeable();
		$service_comstate = new base_service_company_comstate();
		
		$company = $service_company->newLogin($user_name, $password, 'a.company_id,a.company_name,a.company_logo_path,b.account_id,b.is_main,b.user_id');
		
		if (empty($company)) {
			//登陆失败
			$this->_addFailTimes();
			
			//添加登陆失败记录
			$log['company_id'] = 0;
			$log['login_time'] = date('Y-m-d H:i:s');
			$log['ip'] = base_lib_BaseUtils::getIp(0);
			$log['is_success'] = 0;
			$log['login_id'] = $user_name;
			$log['password'] = $password;
			$log['is_cqjob_staff_login'] = 0;
			$log['origin_company_id'] = 0;
			$serivce_com_log->addCompanyLoginLog($log);
			
			//读取配置文件
			$xml = SXML::load('../config/company/company.xml');
			if (!is_null($xml)) {
				$limitMinutes = $xml->ForbidCheckMinutes;
				$forbidTimes = $xml->ForbidTimes;
				$forbidMinutes = $xml->ForbidMinutes;
				$fail_times_then_cuth_code = $xml->FailTimesThenAuthCode;
			}
			
			//检查失败次数，如果超过次数，禁用账号
			$invaliCount = 0;
			$invaliCount = $serivce_com_log->getInvaliCompanyLoginCount($user_name, $limitMinutes);
			if ($invaliCount >= $forbidTimes) {
				//禁用账号
				$forbid['company_id'] = 0;
				$forbid['login_id'] = $user_name;
				$forbid['start_time'] = date('Y-m-d H:i:s');
				$forbid['till_time'] = date('Y-m-d H:i:s', strtotime("+$forbidMinutes minutes"));
				$forbid['state'] = 1;
				$service_forbid->addComAccountsForbid($forbid);
			}
			
			$error = "账号或密码错误!";
			$errCount = $forbidTimes - $invaliCount;
			if ($errCount <= 0) {
				$error = "账号被禁用，请联系客服!";
			}
			else {
				$invaliErr = "{$limitMinutes}分钟内密码输错{$forbidTimes}次，您的账号将被禁用{$forbidMinutes}分钟，您还有<span class='strong' style='font-size:16px;'>{$errCount}</span>次机会";
			}
		}
		else {
			$comstate = $service_comstate->getCompanyState($company['company_id'], "is_repeat,net_heap_id");
			if ($comstate['is_repeat']) {
				//重复账号
				$error = "重复账号,请联系客服!";
			}
		}
		
		if (base_lib_BaseUtils::nullOrEmpty($company['company_logo_path'])) {
			$headphoto = '';
		}
		else {
			$xml = SXML::load('../config/company/company.xml');
			if (!is_null($xml)) {
				$VirtualName = $xml->VirtualName;//虚拟目录名 CompanyImages
				$LogoFolder = $xml->LogoFolder;// <!--logo文件夹名-->
			}
			$headphoto = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $VirtualName . '/' . $LogoFolder . '/' . $company['company_logo_path'];
		}
		
		//检查账号是否被禁用
		$isforbid = $service_forbid->checkForbid($user_name, date('Y-m-d H:i:s'));
		if ($isforbid) {
			//账号被禁用
			$error = "账号被禁用，请联系客服!";
		}
		
		//输出错误信息
		if (!empty($error)) {
			$json_arr['invaliErr'] = $invaliErr;
			$json_arr['needCode'] = $this->_getFailTimes() + 1 >= $fail_times_then_cuth_code;
			$json_arr['error'] = $error;
			echo json_encode($json_arr);
			
			return;
		}
		
		$servic_passwordlog = new base_service_company_passwordlog();
		$state = $servic_passwordlog->getLastCompanyPasswordLog($company['company_id'], 'is_change');
		
		if (!base_lib_BaseUtils::nullOrEmpty($state) && $state['is_change'] == 0) {
			$json_arr['need_update_password'] = 'true';
		}
		
		$skey = md5($company['company_id'] . $company['company_name'] . 'c' . base_lib_Constant::SYSUSERKEY);
		//一个单位可能存在多个用户同时登录，用一个tick表示不同的用户
		$tick = base_lib_BaseUtils::random(10, 0);
		//登陆成功记录到cookie
		$aCookie = array (
			'userid'    => $company['company_id'],
			'accountid' => $company['account_id'],
			'nickname'  => $company['company_name'],
			'usertype'  => 'c',
			'tick'      => $tick,
			'userkey'   => $skey,
			'headphoto' => $headphoto
		);
		
		//更新账户最近登录时间
//		$service_company_account = new base_service_company_account();
//		$service_company_account->updateLastLoginTime($company['account_id']);
		
		//添加登录日志
//		if (!$company['is_main']) {
//			$companyaccountlog_service = new base_service_company_companyaccountlog();
//			$ervice_common_account_accountoperatetype = new base_service_common_account_accountoperatetype();
//			$service_common_account_accountlogfrom = new base_service_common_account_accountlogfrom();
//			$insertItems = array (
//				"company_id"   => $company['company_id'],
//				"account_id"   => $company['account_id'],
//				"operate_type" => $ervice_common_account_accountoperatetype->account_login,
//				"content"      => date("Y-m-d H:i:s", time()) . ",登录系统。",
//				"create_time"  => date("Y-m-d H:i:s", time()),
//				"source"       => $service_common_account_accountlogfrom->website
//			);
//			$companyaccountlog_service->addLogToMongo($insertItems);
//		}
		
		//关闭浏览器就失效
		base_lib_BaseUtils::ssetcookie($aCookie, 0, '/', base_lib_Constant::COOKIE_DOMAIN);
		
		$mi = date('Y-m-d H:i');
		$stamp = strtotime($mi);
		$this->setLifetime($this->getSessionid($company['company_id'], $tick, $company['account_id']), $skey . '_' . $stamp);
		
		//添加登陆次数		
//		$servic_company_change->AddLoginTimes($company['company_id']);
		
		//添加成功日志
//		$companylog['company_id'] = $company['company_id'];
//		$companylog['login_time'] = date('Y-m-d H:i:s');
//		$companylog['ip'] = base_lib_BaseUtils::getIp(0);
//		$companylog['is_success'] = 1;
//		$companylog['login_id'] = $user_name;
//		$companylog['password'] = $password;
//		$companylog['is_cqjob_staff_login'] = 0;
//		$companylog['origin_company_id'] = 0;
//		$serivce_com_log->addCompanyLoginLog($companylog);
		
		//track与用户的映射
		$this->userTrackBind($company['company_id'], 1, 1);
		
		//自动锁定任务
//		if ($comstate['net_heap_id'] <= 0) {
//			$service_process = new base_service_company_processtask();
//			$service_process_state = new base_service_company_processtaskstate();
//			$service_process_type = new base_service_company_processtasktype();
//			$item_process['company_id'] = $company['company_id'];
//			$item_process['state'] = $service_process_state->unexecuted;
//			$item_process['try_times'] = 0;
//			$item_process['type'] = $service_process_type->loginautolock;
//			$item_process['create_time'] = date('Y-m-d H:i:s');
//			$service_process->addProcessTask($item_process);
//		}
		
		$userCookie = array ('username' => urlencode($user_name));
		base_lib_BaseUtils::ssetcookie($userCookie, 3600 * 24 * 7, '/', base_lib_Constant::COOKIE_DOMAIN);
		
		//保存登录动作
		//动作类型
//		$service_actiontype = new base_service_common_actiontype();
//		//用户类型
//		$service_actionusertype = new base_service_common_actionusertype();
//		//渠道
//		$service_actionsource = new base_service_common_actionsource();
//		base_lib_BaseUtils::saveAction($service_actiontype->login, $service_actionsource->website, $company['company_id'], $service_actionusertype->company);
		
		$json_arr['name'] = $company['company_name'];
		$json_arr['success'] = '登录成功';
		echo json_encode($json_arr);
		
		return;
	}
	
	/**
	 * 检测用户名
	 * @param array $inPath
	 */
	public function pageCheckUserName($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$user_id = base_lib_BaseUtils::getStr($path_data['txtUserID'], 'string', '');
		if (base_lib_BaseUtils::nullOrEmpty($user_id)) {
			echo json_encode(array ('error' => '请输入用户名'));
			
			return;
		}
		$service_company = new base_service_company_company();
		$ckuser_id = $service_company->doCheckUniqueUserId($user_id);
		if (empty($ckuser_id)) {
			echo json_encode(array ('state' => true));
		}
		else {
			echo json_encode(array ('state' => false));
		}
	}
	
	public function pageCheckSameCompanyName($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$company_name = base_lib_BaseUtils::getStr($path_data['company_name'], 'string', '');
		
		if (base_lib_BaseUtils::nullOrEmpty($company_name)) {
			echo json_encode(array ('status' => false));
			
			return;
		}
		
		if ($this->isLogin() && $this->_usertype == 'c') {
			echo json_encode(array ('status' => false));
			
			return;
		}
		
		$service_company = new base_service_company_company();
		$company = $service_company->getCompanyIdByName($company_name, 2);
		echo json_encode(array ('status' => !empty($company->items)));
		
		return;
	}
	
	//验证码
	public function pageVerify($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$seed = $path_data["seed"];
		$captcha = new SCaptchalu();
		$captcha->conf->type = 3;
		$captcha->conf->mode = 0;//图片模式 文字变色为1
		$captcha->conf->length = 4;
		$imageResource = $captcha->getImageResource($seed);
		header("Content-type: image/png");
		if (false !== $imageResource) {
			imagepng($imageResource);
		}
	}
	
	//发送验证码
	public function pageSendAuthCode($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		
		$mobile_phone = base_lib_BaseUtils::getStr($pathdata['mobile_phone'], "string", "");
		
		if (empty($mobile_phone) || !preg_match('/^(?:13|15|18|14|17|19|16)[0-9]\d{8}$/', $mobile_phone)) {
			echo json_encode(["status" => false, "msg" => "请输入正确的电话号码"]);
			
			return;
		}
		$client_ip = base_lib_BaseUtils::getIp(0);
		$error = '';
		
		$service_companyregvlid = new base_service_company_companyregvlid();
		$action_common = new base_service_common_actionsource();
		
		$service_companyregvlid->createRegValidCode($client_ip, $mobile_phone, $error, $action_common->mobile);
		if (!empty($error)) {
			echo json_encode(["status" => false, "msg" => $error]);
			
			return;
		}
		echo json_encode(["status" => true, 'msg' => '获取验证码成功']);
		
		return;
	}
	
	/**
	 * @desc 选择行业
	 * @param string $calling_id
	 */
	public function pageSelectCalling($inpath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inpath));
		$calling_id = base_lib_BaseUtils::getStr($pathdata['calling_id'], 'string', null);
		$type = base_lib_BaseUtils::getStr($pathdata['type'], 'int', -1);
		
		$calling_service = new base_service_common_calling();
		$top_callings = $calling_service->getTopCallings();
		
		foreach ((array)$top_callings as $k => $top) {
			$parent_id = $top['calling_id'];
			$subitem = $calling_service->getSubItem($parent_id);
			$top_callings[ $k ]['subItem'] = $subitem;
		}
		
		$this->_aParams['type'] = $type;
		$this->_aParams['calling_id'] = $calling_id;
		$this->_aParams['callings'] = $top_callings;
		
		if ($type == 2) {
			return $this->render("./part/calling.html", $this->_aParams);
		}
		else {
			return $this->render("calling.html", $this->_aParams);
		}
	}
	
	/**
	 * @desc 公司上传LOGO
	 * @param $inpath
	 */
	public function pageUploadCompanyLogo($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		
		return $this->render('./activityvote//uploadlogo.html', $this->_aParams);
	}
	
	/**
	 * @desc 上传logo
	 * @param mixed $inpath
	 */
	public function pageUploadImage($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$xml = SXML::load('../config/company/company.xml');
		if (!is_null($xml)) {
			$VirtualName = $xml->VirtualName;
			/////////////
			$file_folder = $xml->LogoTempFolder;
//			$file_folder = $xml->LogoFolder;
			$file_max_size = $xml->LogoMaxSize;
			$file_types = $xml->LogoExtensions;
		}
//		$file = $_FILES['imageFile'];
		$file = array_pop($_FILES);
		
		//检查是否有文件
		if ($file == null) {
			$json['error'] = 100;
			$json['errorMsg'] = '请选择您要上传的图片';
			echo json_encode($json);
			
			return;
		}
		//检查大小
		if ($file['size'] > $file_max_size * 1024) {
			$json['error'] = 101;
			$json['errorMsg'] = '请上传小于' . $file_max_size . 'KB的图片';
			echo json_encode($json);
			
			return;
		}
		//检查类型
		$extension_name = base_lib_BaseUtils::fileext($file['name']);
		$arr_file_type = explode(',', $file_types);
		if (!in_array('.' . $extension_name, $arr_file_type)) {
			$json['error'] = 102;
			$json['errorMsg'] = '请上传格式为' . $file_types . '的图片';
			echo json_encode($json);
			
			return;
		}
		
		//用于上传时解决火狐FLASH丢失COOKIE导致无法验证而上传不起的BUG
//		$iUserId = isset($_POST['upload_cookie_userid'])?$_POST['upload_cookie_userid']: base_lib_BaseUtils::getCookie('userid');
//                if(base_lib_BaseUtils::nullOrEmpty($iUserId)){
//                        $json['error'] = 103;
//			$json['errorMsg'] = '请先登录您的企业账号';
//			echo json_encode($json);
//			return;
//                }
		//$company_flag = base_lib_Rewrite::getFlag('company', $iUserId);
		$path = "{$VirtualName}/{$file_folder}";
//                $company_flag = base_lib_Rewrite::getFlag('company', $this->_userid);
		$postvar['path'] = $path;//存放路径 配置文件件读取
		$time = date('Ymdhis') . rand(1111, 9999);
		$postvar['name'] = $time . '.' . $extension_name;
		$thumb_name = $time . "thumb." . $extension_name;
		$img_info = getimagesize($file['tmp_name']);
		//获得图片的高宽
		if (base_lib_BaseUtils::nullOrEmpty($img_info)) {
			return null;
		}
		
		$src_width = $img_info[0]; //来源图片宽度
		$src_height = $img_info[1];//来源图片高度
		if ($src_width > 400) {
			$max_width = 400;
		}
		else {
			$max_width = $src_width;
		}
		if ($src_height > 327) {
			$max_height = 327;
		}
		else {
			$max_height = $src_height;
		}
		$thumb_type = '';//缩放方式
		$thumb_ratio = 0;//缩放比例
		$height_ratio = $src_height / $max_height;
		$width_ratio = $src_width / $max_width;
		if ($width_ratio >= $height_ratio) {
			$thumb_width = $max_width;
			$thumb_type = "width";
			$thumb_height = $src_height * ($max_width / $src_width);
			$thumb_ratio = $width_ratio;
		}
		else {
			$thumb_height = $max_height;
			$thumb_type = "height";
			$thumb_width = $src_width * ($max_height / $src_height);
			$thumb_ratio = $height_ratio;
		}
		
		$postvar['thumbMaxWidth'] = $max_width; //图片最大宽度,上传图片时必填
		$postvar['thumbMaxHeight'] = $max_height; //图片最大高度,上传图片时必填
		//调用方法 成功返回{'success',true}
		
		$result = base_lib_Uploadfilesv::postfile($postvar, $file['name'], $file['tmp_name'], $file['type']);
		if ($result) {
			//上传七牛云存储
			//$qiniu = new SQiniu();
			//$path = rtrim($postvar['path'],'/');
			//unset($postvar['thumbMaxWidth']);
			//unset($postvar['thumbMaxHeight']);
			//$qiniu->upload2qiniu($path."/{$postvar['name']}", $file['tmp_name'], base_lib_Constant::QINIU_BUCKET, $postvar);
			
			$json['status'] = 1;
			$json['name'] = $postvar['name'];
			$json['path'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $path;
			$json['fileName'] = $thumb_name;
			$json['item_index'] = $item_index;
			$json['thumb_ratio'] = $thumb_ratio;
			$json['thumb_type'] = $thumb_type;
			echo json_encode($json);
			
			return;
		}
		else {
			$json['status'] = 0;
			$json['error'] = 104;
			$json['errorMsg'] = '图片上传失败';
			echo json_encode($json);
			
			return;
		}
	}
	
	/**
	 * @desc 保存裁剪LOGO图片
	 * @param $inpath
	 */
	public function pageSaveLogo($inpath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inpath));
		$company_id = $this->_userid;
		$xml = SXML::load('../config/company/company.xml');
		if (!is_null($xml)) {
			$VirtualName = $xml->VirtualName;
			$CompanyImagePath = $xml->CompanyImagePath;
			$file_folder = $xml->LogoTempFolder;//LOGO临时文件路径
			$TemplateImageFolder = $xml->LogoFolder;//LOGO图片完整路径
		}
		//缩放比例
		$thumb_ratio = floatval($pathdata['thumb_ratio']);
		if (empty($thumb_ratio)) {
			$json['state'] = 0;
			$json['msg'] = "参数错误";
			echo json_encode($json);
			
			return;
		}
		//$thumb_type = $pathdata['thumb_type'];
		$source_name = base_lib_BaseUtils::getStr($pathdata['name'], 'string', '');
		if ($source_name == '') {
			$json['state'] = 0;
			$json['msg'] = "图片名称不能为空";
			echo json_encode($json);
			
			return;
		}
		//上传图片的临时物理地址
		$path = "{$VirtualName}/{$file_folder}";
		$source_x = base_lib_BaseUtils::getStr($pathdata['cropX'], 'int', 0);//开始裁剪X坐标
		$source_x = $source_x * $thumb_ratio;
		$source_y = base_lib_BaseUtils::getStr($pathdata['cropY'], 'int', 0);//开始裁剪Y坐标
		$source_y = $source_y * $thumb_ratio;
		$cropped_width = base_lib_BaseUtils::getStr($pathdata['cropW'], 'float', 0);//裁剪宽度
		$cropped_width = $cropped_width * $thumb_ratio;
		$cropped_height = base_lib_BaseUtils::getStr($pathdata['cropH'], 'float', 0);//裁剪高度
		$cropped_height = $cropped_height * $thumb_ratio;
		if ($cropped_width == 0 || $cropped_height == 0) {
			$json['state'] = 0;
			$json['msg'] = "裁剪的长度不能为0";
			echo json_encode($json);
			
			return;
		}
		$max_width = 160;
		$max_height = 160;
		//裁剪图片
		$postvar['path'] = $path;//图片路径
		$postvar['new_path'] = $path;//图片路径
		$postvar['name'] = $source_name;//裁剪原图名称
		$postvar['type'] = "modify";
		$postvar['authenticate'] = "logo";
		$postvar['new_file_name'] = "copy" . $source_name;
		$postvar['modifytype'] = 2;
		$postvar['copyX'] = $source_x;
		$postvar['copyY'] = $source_y;
		$postvar['copyW'] = $cropped_width;
		$postvar['copyH'] = $cropped_height;
		$postvar['createWidth'] = 160;
		$postvar['createHeight'] = 160;
		$result = base_lib_Uploadfilesv::modifyImage($postvar);
		//返回图片地址及名称
		if ($result) {
			//移动云存储文件到正式目录
			//上传七牛云存储
			//$qiniu = new SQiniu();
			//$qiniu->CopyModify($postvar['path']."/".$source_name, base_lib_Constant::QINIU_BUCKET, $postvar['path']."/".$postvar['new_file_name'], base_lib_Constant::QINIU_BUCKET,$postvar);
			//$qiniu->Del($postvar['path']."/".$source_name, base_lib_Constant::QINIU_BUCKET);
			
			$json['image_url'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . "/" . $path . "/" . $postvar['new_file_name'];
			$json['image_path'] = $postvar['new_file_name'];
			$json['state'] = 1;
			$json['msg'] = "裁剪图片成功";
			echo json_encode($json);
			
			return;
		}
		else {
			$json['state'] = 0;
			$json['msg'] = "裁剪图片失败";
			echo json_encode($json);
			
			return;
		}
	}
	
	/**
	 *
	 * 营业执照的临时保存
	 * @param object $inPath
	 */
	public function pageSaveTempLicence($inPath) {
//		$service_licencevalidate = new base_service_company_licencevalidate();
//		// 保存营业执照文件
//		$file = $_FILES['Filedata'];
//		$arr = $service_licencevalidate->saveTempLicenceValidate($file);
//		if ($arr !== false) {
//            echo json_encode($arr);
//        }
		$service_licencevalidate = new base_service_company_licencevalidate();
		// 保存营业执照文件
		$file = array_pop($_FILES);
		if (base_lib_BaseUtils::nullOrEmpty($file)) {
			echo json_encode(array ("error" => "文件上传不成功，原因可能是上传已取消或者文件过大。"));
			exit;
		}
		//判断文件error
		if (!base_lib_BaseUtils::nullOrEmpty($file['error'])) {
			switch ($file['error']) {
				case 0:
					break;
				case 1:
					echo json_encode(array ("error" => "文件过大,上传不成功！"));
					exit;
					break;
				case 2:
					echo json_encode(array ("error" => "文件过大,上传不成功！"));
					exit;
					break;
				case 3:
					echo json_encode(array ("error" => "上传失败，只有部分文件被上传！"));
					exit;
					break;
				case 4:
					echo json_encode(array ("error" => "没有文件被上传,上传失败！"));
					exit;
					break;
				default:
					break;
			}
		}
		$arr = $service_licencevalidate->saveTempLicenceValidate($file);
		if (isset($arr['error']) && !base_lib_BaseUtils::nullOrEmpty($arr['error'])) {
			switch ($arr['error']) {
				case 100:
					echo json_encode(array ('error' => "没有找到上传文件"));
					exit;
					break;
				case 101:
					echo json_encode(array ('error' => "上传的文件过大"));
					exit;
					break;
				case 102:
					echo json_encode(array ('error' => "上传的文件格式错误"));
					exit;
					break;
				case 104:
					echo json_encode(array ('error' => "上传失败，服务器错误"));
					exit;
					break;
				default:
					echo json_encode(array ('error' => "上传失败，原因未知"));
					exit;
					break;
			}
		}
		else {
			echo json_encode($arr);
			exit;
		}
		
	}
}