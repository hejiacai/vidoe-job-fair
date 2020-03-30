<?php

/**
 * @name  单位
 * @version 1.0
 * @author ZhangYu
 */
class controller_loginCompany extends components_cbasepage {
	
	private $session_server = null;
	
	function __construct() {
		parent::__construct(false);
		$this->_usertype = 'c';
		$this->_aParams['seed'] = uniqid();
	}
	
	//单位登录
	function pageLogin1($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$callback = base_lib_BaseUtils::getStr($pathdata['success'], 'string', 'null');
		$isReload = base_lib_BaseUtils::getStr($pathdata['isReload'], 'bool', false);
		$isFromAnnex = base_lib_BaseUtils::getStr($pathdata["isFromAnnex"], "bool", false);
		$isFromResume = base_lib_BaseUtils::getStr($pathdata["isFromResume"], "bool", false);
		$school_source = base_lib_BaseUtils::getStr($pathdata["school_source"], "int", 1);
		
		$this->_aParams["isFromAnnex"] = $isFromAnnex;
		$this->_aParams["isFromResume"] = $isFromResume;
		$this->_aParams['callback'] = $callback;
		$this->_aParams['isReload'] = $isReload;
		$this->_aParams['school_source'] = $school_source;
		
		$requestUri = $_SERVER['HTTP_REFERER']; //请求参数
		$this->_aParams['request_url'] = $requestUri;
		if($school_source == 1) {
			return $this->render('loginCompany/personajaxlogin1.html', $this->_aParams);
		}
		elseif($school_source == 2) {
			return $this->render('loginCompany/personajaxlogin2.html', $this->_aParams);
		}
		
		return false;
	}
	
	//验证码
	public function pageVerify($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$seed = $pathdata["seed"];
		$captcha = new SCaptchalu();
		$captcha->conf->type = 4;
		$imageResource = $captcha->getImageResource($seed);
		header("Content-type: image/png");
		if(false !== $imageResource) {
			imagepng($imageResource);
		}
	}
	
	#region 汇博单位登录
	
	/**
	 * 登录
	 */
	function pageLoginDo($inPath) {
		
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$val = new base_lib_Validator();
		$user_name = $val->getStr($path_data['txtUserName'], 2, 30, '请输入长度为2-30位的用户名');
		$password = $val->getStr($path_data['txtPassword'], 6, 18, '请输入长度为6-18位的密码');
		$callbackparam = base_lib_BaseUtils::getStr($path_data['callbackparam'], 'string', '');
		
		if($val->has_err) {
			//将错误打印json
			$this->_returnJsons($callbackparam, array ('error' => $val->toHtml()));
		}
		
		$code = base_lib_BaseUtils::getStr($path_data['catcha']);
		$seed = base_lib_BaseUtils::getStr($path_data['seed']);
		$captcha = new SCaptchalu();
		if($captcha->verify($seed, $code) === false) {
			// $this->_returnJsons($callbackparam, array ('error' => '验证码错误'));
		}
		
		$service_company = new base_service_company_company();
		/*--------------boss端企业违规封号处理- 2018-12-15 --------------*/
		$boos_fordden = $service_company->isBossForbid($user_name);
		if($boos_fordden['is_foribid'] === true) {
			$this->_returnJsons($callbackparam, array (
				'code'  => 'boss_forbid',
				'error' => $boos_fordden['msg'],
			));
		}
		
		
		$company = $service_company->newLogin($user_name, $password, 'a.company_id,a.company_name,a.company_logo_path,b.account_id,b.is_main,b.user_id');
		$serivce_com_log = new base_service_company_loginlog();
		$service_forbid = new base_service_company_accountsforbid();
		$service_company_change = new base_service_company_changeable();
		$service_comstate = new base_service_company_comstate();
		//渠道
		$service_actionsource = new base_service_common_actionsource();
		
		if(empty($company)) {
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
			if(!is_null($xml)) {
				$limitMinutes = $xml->ForbidCheckMinutes;
				$forbidTimes = $xml->ForbidTimes;
				$forbidMinutes = $xml->ForbidMinutes;
				$fail_times_then_cuth_code = $xml->FailTimesThenAuthCode;
			}
			
			//检查失败次数，如果超过次数，禁用账号
			$invaliCount = $serivce_com_log->getInvaliCompanyLoginCount($user_name, $limitMinutes);
			if($invaliCount >= $forbidTimes) {
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
			if($errCount <= 0) {
				$this->_returnJsons($callbackparam, [
					'error'         => "账号被禁用，请联系客服!",
					"user_id"       => $user_name,
					"redirect_html" => 1,
					"error_type"    => 1,
				]);
			}
			else {
				$invaliErr = "若{$limitMinutes}分钟内密码输错{$forbidTimes}次，您的账号将被禁用{$forbidMinutes}分钟。您还有<span class='strong' style='font-size:16px;'>{$errCount}</span>次机会";
			}
		}
		else {
			$comstate = $service_comstate->getCompanyState($company['company_id'], "is_repeat,net_heap_id");
			if($comstate['is_repeat']) {
				//重复账号
				$this->_returnJsons($callbackparam, [
					'error'         => "重复账号,请联系客服!",
					"user_id"       => $user_name,
					"redirect_html" => 1,
					"error_type"    => 2,
				]);
			}
			
		}
		
		
		//检查账号是否被禁用
		$isforbid = $service_forbid->checkForbid($user_name, date('Y-m-d H:i:s'));
		if($isforbid) {
			//账号被禁用
			$this->_returnJsons($callbackparam, [
				'error'         => "账号被禁用，请联系客服!",
				"user_id"       => $user_name,
				"redirect_html" => 1,
				"error_type"    => 1,
			]);
		}
		
		//输出错误信息
		if(!empty($error)) {
			$json_arr['invaliErr'] = $invaliErr;
			$json_arr['needCode'] = $this->_getFailTimes() + 1 >= $fail_times_then_cuth_code;
			$json_arr['error'] = $error;
			$this->_returnJsons($callbackparam, $json_arr);
		}
		
		//----------是否是汇博教育企业-----------
		$service_comstate = new base_service_company_comstate();
		$comstate = $service_comstate->getCompanyState($company['company_id'], "train_type");
		$is_company_edu = $comstate['train_type'] == 1 ? true : false;
		if($is_company_edu) {
			$error = "该账号只能登录汇博教育!";
		}
		//输出错误信息
		if(!empty($error)) {
			$json_arr['invaliErr'] = $invaliErr;
			$json_arr['needCode'] = $this->_getFailTimes() + 1 >= $fail_times_then_cuth_code;
			$json_arr['error'] = $error;
			$this->_returnJsons($callbackparam, $json_arr);
		}
		
		
		$servic_passwordlog = new base_service_company_passwordlog();
		$state = $servic_passwordlog->getLastCompanyPasswordLog($company['company_id'], 'is_change');
		if(!base_lib_BaseUtils::nullOrEmpty($state) && $state['is_change'] == 0 && $company["is_main"]) {
			$json_arr['need_update_password'] = 'true';
		}
		
		$skey = md5($company['company_id'] . $company['company_name'] . 'c' . base_lib_Constant::SYSUSERKEY);
		//一个单位可能存在多个用户同时登录，用一个tick表示不同的用户
		$tick = base_lib_BaseUtils::random(10, 0);
		
		if(base_lib_BaseUtils::nullOrEmpty($company['company_logo_path'])) {
			$headphoto = '';
		}
		else {
			$xml = SXML::load('../config/company/company.xml');
			if(!is_null($xml)) {
				$VirtualName = $xml->VirtualName;//虚拟目录名 CompanyImages
				$LogoFolder = $xml->LogoFolder;// <!--logo文件夹名-->
			}
			$headphoto = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $VirtualName . '/' . $LogoFolder . '/' .
				$company['company_logo_path'];
		}
		
		$ser_netfair_company = new base_service_netfair_company();
		$netfair_person_id = $ser_netfair_company->getCompanyID($company['company_id'], 1, '');
		if(!$netfair_person_id) {
			$json_arr['error'] = "请稍后重试....";
			$this->_returnJsons($callbackparam, $json_arr);
		}
		
		//登陆成功记录到cookie
		$aCookie = array (
			'userid'         => $company['company_id'],
			'netfair_userid' => $netfair_person_id,
			'netfair_source' => 1,
			'accountid'      => $company['account_id'],
			'nickname'       => $company['company_name'],
			'usertype'       => 'c',
			'tick'           => $tick,
			'userkey'        => $skey,
			'headphoto'      => $headphoto,
		);
		
		
		//关闭浏览器就失效
		base_lib_BaseUtils::ssetcookie($aCookie, 0, '/', base_lib_Constant::COOKIE_DOMAIN);
		
		$mi = date('Y-m-d H:i');
		$stamp = strtotime($mi);
		
		//添加登陆次数
		$service_company_change->AddLoginTimes($company['company_id']);
		
		//track与用户的映射
		$this->userTrackBind($company['company_id'], 1, 1);
		
		// 记录用户cookie
		$userCookie = array ('username' => urlencode($user_name));
		base_lib_BaseUtils::ssetcookie($userCookie, 3600 * 24 * 7, '/', base_lib_Constant::COOKIE_DOMAIN);
		
		//保存登录动作
		//动作类型
		$service_actiontype = new base_service_common_actiontype();
		//用户类型
		$service_actionusertype = new base_service_common_actionusertype();
		
		base_lib_BaseUtils::saveAction($service_actiontype->login, $service_actionsource->website, $company['company_id'], $service_actionusertype->company);
		
		$_SESSION['_FAIL_TIMES_SESSIONKEY'] = null;
		
		//服务器登陆信息放在强制下线逻辑后面
		$this->setLifetime($this->getSessionid($company['company_id'], $tick, $company['account_id']), $skey . '_' .
			$stamp);
		
		//更新账户最近登录时间
		$service_company_account = new base_service_company_account();
		$service_company_account->updateLastLoginTime($company['account_id']);
		if(!$company['is_main']) {
			$companyaccountlog_service = new base_service_company_companyaccountlog();
			$ervice_common_account_accountoperatetype = new base_service_common_account_accountoperatetype();
			$service_common_account_accountlogfrom = new base_service_common_account_accountlogfrom();
			$insertItems = array (
				"company_id"   => $company['company_id'],
				"account_id"   => $company['account_id'],
				"operate_type" => $ervice_common_account_accountoperatetype->account_login,
				"content"      => date("Y-m-d H:i:s", time()) . ",登录系统。",
				"create_time"  => date("Y-m-d H:i:s", time()),
				"source"       => $service_common_account_accountlogfrom->website,
			);
			$companyaccountlog_service->addLogToMongo($insertItems);
		}
		
		//添加成功日志
		$companylog['company_id'] = $company['company_id'];
		$companylog['login_time'] = date('Y-m-d H:i:s');
		$companylog['ip'] = base_lib_BaseUtils::getIp(0);
		$companylog['is_success'] = 1;
		$companylog['login_id'] = $user_name;
		$companylog['password'] = $password;
		$companylog['is_cqjob_staff_login'] = 0;
		$companylog['origin_company_id'] = 0;
		$companylog["source"] = $service_actionsource->website;
		$serivce_com_log->addCompanyLoginLog($companylog);
		
		
		//以微信绑定及模板消息通知类型增加  判断是否绑定微信
		$companywerixinservice = new base_service_weixin_companyweixin();
		$is_bindweixin = $companywerixinservice->isBind($company['account_id'], $company['company_id']);
		
		/*登录cms*/
		$json_arr['name'] = $company['company_name'];
		$json_arr['bindweixin'] = $is_bindweixin;
		$json_arr['success'] = '登录成功';
		$json_arr['ip'] = base_lib_BaseUtils::getIp(0);
		$this->_returnJsons($callbackparam, $json_arr);
	}
	
	private function _returnJsons($callbackparam, $data) {
		if(!empty($callbackparam)) {
			exit($callbackparam . '(' . json_encode((array)$data) . ")");
		}
		else {
			exit(json_encode((array)$data));
		}
	}
	
	//添加登录失败次数
	private function _addFailTimes() {
		$obj = $_SESSION['_FAIL_TIMES_SESSIONKEY'];
		$times = 0;
		if($obj != null) {
			$times = $obj;
		}
		$times++;
		$_SESSION['_FAIL_TIMES_SESSIONKEY'] = $times;
		
		return $times;
	}
	
	//获取登录失败次数
	private function _getFailTimes() {
		$obj = $_SESSION['_FAIL_TIMES_SESSIONKEY'];
		$times = 0;
		if($obj != null) {
			$times = $obj;
		}
		
		return $times;
	}
	
	//获取sessionid $sw_session是否需要判断终端
	function getSessionid($company_id, $tick, $account_id = 0, $verdict_mobile = true) {
		$detect = new SMobiledetect();
		$tick_session_id[] = 'com';
		$tick_session_id[] = $company_id;
		$verdict_mobile and $tick_session_id[] = !$detect->isMobile() && !$detect->isTablet() ? 'web' : 'mobile';
		$account_id and $tick_session_id[] = $account_id;
		$tick_session_id[] = $tick;
		
		return implode('-', $tick_session_id);
	}
	
	//初始化session服务器
	private function getSessionServer() {
		if($this->session_server == null) {
			$this->session_server = new base_lib_Cache('redis3');
			$this->session_server->redis_select(4);
		}
		
		return $this->session_server;
	}
	
	//设置生成周期
	function setLifetime($key, $value, $is_boss_login = false) {
		$redis = $this->getSessionServer();
		#region 需要处理的单点登录 单位登录com-
		$re_key = explode('-', $key);
		if(!$is_boss_login && count($re_key) == 5 && $re_key[3] && $re_key[0] == 'com' &&
			in_array($re_key[2], array ('web', 'mobile'))
		) {
			//需要处理的单点登录 单位登录com-
			$login_keys = $redis->redis_keys("com-{$re_key[1]}-{$re_key[2]}-{$re_key[3]}-*");
			if($login_keys) {
				array_walk($login_keys, function ($value, $key) use ($redis) {
					$redis->redis_del($value);
				});
			}
		}
		#endregion
		$redis->redis_setex($key, 7200, $value); //2 hour
		//echo 'write';
	}
	#endregion
	
	#region 快米单位登录
	/**
	 * 登录逻辑执行
	 */
	function pageBlueAjaxLoginDo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$val = new base_lib_Validator();
		$user_name = base_lib_BaseUtils::getStr($path_data['txtUserName'], "string", "");
		$img_code = base_lib_BaseUtils::getStr($path_data['catcha'], "int", 0);
		$password = $val->getStr($path_data['txtPassword'], 6, 18, '请输入长度为6-18位的密码');
		$img_code = $val->getStr($img_code, 4, 4, '请输入有效验证码');
		
		if(empty($user_name)) {
			$val->addErr('用户名不能为空');
		}
		
		if($val->has_err) {
			return $val->toJsonWithHtml();
		}
		
		//图像验证码验证
		$captcha = new SCaptchalu();
		$seed = $val->getNotNull($path_data['seed'], '');
		if($captcha->verify($seed, $img_code) === false) {
			// return json_encode(array ("error" => "图片验证码错误", "vcodeerr" => 1));
		}
		
		$service_company = new base_service_blue_company_company();
		$serivce_com_log = new base_service_blue_company_loginlog();
		$service_actionsource = new base_service_common_actionsource();
		$company = $service_company->newLogin($user_name, $password, 'company_id,company_name,company_logo_path,mb_account,com_level,train_type');
		
		$source = $service_actionsource->bluewebsite;
		
		if(empty($company)) {
			//添加登陆失败记录
			$log['company_id'] = 0;
			$log['login_time'] = date('Y-m-d H:i:s');
			$log['IP'] = base_lib_BaseUtils::getIp(0);
			$log['is_success'] = 0;
			$log['login_id'] = $user_name;
			$log['password'] = $password;
			$companylog['source'] = $source;
			$serivce_com_log->addCompanyLoginLog($log);
			$error = "账号或密码错误!";
		}
		
		//登录失败
		if(!empty($error)) {
			$json_arr['error'] = $error;
			exit(json_encode($json_arr));
		}
		
		if($company['train_type'] == 1) {
			exit(json_encode(array ("error" => "快米教育企业不能登录")));
		}
		
		// 头像
		if(base_lib_BaseUtils::nullOrEmpty($company['company_logo_path'])) {
			$headphoto = '';
		}
		else {
			$headphoto = base_lib_Constant::YUN_ASSETS_URL . '/' . $company['company_logo_path'];
		}
		
		$ser_netfair_company = new base_service_netfair_company();
		$netfair_person_id = $ser_netfair_company->getCompanyID($company['company_id'], 2, '');
		if(!$netfair_person_id) {
			exit(json_encode(array ("error" => "请稍后重试...")));
		}
		
		$skey = md5($company['company_id'] . $company['company_name'] . 'c' . base_lib_Constant::SYSUSERKEY);
		//一个单位可能存在多个用户同时登录，用一个tick表示不同的用户
		$tick = base_lib_BaseUtils::random(10, 0);
		//登陆成功记录到cookie
		$aCookie = array (
			'b_userid'     => $company['company_id'],
			'netfair_userid'  => $netfair_person_id,
			'netfair_source'  => 2,
			'b_nickname'   => $company['company_name'],
			'b_usertype'   => 'c',
			'b_tick'       => $tick,
			'b_userkey'    => $skey,
			'b_headphoto'  => $headphoto,
		);
		
		//关闭浏览器就失效
		base_lib_BaseUtils::ssetcookie($aCookie, 0, '/', base_lib_Constant::COOKIE_DOMAIN);
		$mi = date('Y-m-d H:i');
		$stamp = strtotime($mi);
		$this->setLifetime($this->getSessionid($company['company_id'], $tick), $skey . '_' .
			$stamp);//session的也是在这个时候存储的
		
		
		// 添加成功日志
		$companylog['company_id'] = $company['company_id'];
		$companylog['login_time'] = date('Y-m-d H:i:s');
		$companylog['IP'] = base_lib_BaseUtils::getIp(0);
		$companylog['is_success'] = 1;
		$companylog['login_id'] = $user_name;
		$companylog['password'] = $password;
		$companylog['source'] = $source;
		$serivce_com_log->addCompanyLoginLog($companylog);
		
		//更新企业登录时间 last_login_time
		$service_company->update(['company_id' => $company['company_id']], ['last_login_time' => date('Y-m-d H:i:s')]);
		
		$userCookie = array ('username' => urlencode($user_name));
		base_lib_BaseUtils::ssetcookie($userCookie, 3600 * 24 * 7, '/', base_lib_Constant::COOKIE_DOMAIN);
		
		// 保存登录动作
		//动作类型
		$service_actiontype = new base_service_common_actiontype();
		//用户类型
		$service_actionusertype = new base_service_common_actionusertype();
		
		base_lib_BaseUtils::saveActionBlue($service_actiontype->login, $source, $company['company_id'], $service_actionusertype->bluecompany);
		
		//米币获取(免费会员可得)
		if(in_array($company['com_level'], [0, 1])) {
			$service_mbin = new base_service_blue_company_mbmanager();
			$mb_res = $service_mbin->actionAddMb(true, 2, $company['company_id'], 0);
			if(!empty($mb_res['code']) && $mb_res['code'] = 'hasLogin') {
				$json_arr['success'] = '登录成功';
			}
			else {
				$json_arr['success'] = '登录成功';
			}
		}
		else {
			$json_arr['success'] = '登录成功';
		}
		
		$json_arr['name'] = $company['company_name'];
		
		echo json_encode($json_arr);
		
		return;
	}
	#endregion
	
	/**
	 * 退出    zhouwenjun 2020/3/4 9:03
	 */
	public function pageLogout($inPath) {
		$aCookie = array (
			'userid'         => '',
			'nickname'       => '',
			'usertype'       => '',
			'userkey'        => '',
			'netfair_userid' => '',
			'netfair_source' => '',
			'b_userid'       => '',
			'b_nickname'     => '',
			'b_usertype'     => '',
			'fromurl'        => '',
			'b_userkey'      => '',
		);
		base_lib_BaseUtils::ssetcookie($aCookie, -1, '/', base_lib_Constant::COOKIE_HUIBO_DOMAIN);
		// $this->destroyPersonSession();
		//销毁session
		session_start();
		session_unset();
		session_destroy();
		$this->redirect_url2($_SERVER['HTTP_REFERER']);
	}
}

?>
