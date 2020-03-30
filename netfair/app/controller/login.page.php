<?php

/**
 * @name  用户登录
 * @version 1.0
 * @author ZhangYu
 */
class controller_login extends components_cbasepage {
	
	function __construct() {
		parent::__construct(false);
		$this->_usertype = 'p';
		$this->_aParams['seed'] = uniqid();
	}
	
	//求职者登录
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
			return $this->render('login/personajaxlogin1.html', $this->_aParams);
		}
		elseif($school_source == 2) {
			return $this->render('login/personajaxlogin2.html', $this->_aParams);
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
	
	#region 汇博求职者登录
	
	/**
	 * 获取手机验证码，必须输入图形验证码
	 */
	function pageGetAuthCodeBySeed($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$val = new base_lib_Validator();
		//$phone = $val->getMatched($pathdata['txtPhone'],'/^[1]\d{10}$/','请输入正确的手机号码');
		$phone = $val->getMobile($pathdata['txtPhone'], '请输入正确的手机号码');
		//判断图片验证码
		$captcha = new SCaptchalu();
		$code = base_lib_BaseUtils::getStr($pathdata['catcha'], 'string', '');
		$seed = base_lib_BaseUtils::getStr($pathdata['seed'], 'string', '');
		$src = base_lib_BaseUtils::getStr($pathdata['src'], 'string', '');
		$loginTimeCount = base_lib_BaseUtils::getStr($pathdata['loginTimeCount'], 'int', 0); //今天登录次数
		if($loginTimeCount > 4) {
			if(empty($code) || $code == '') {
				echo json_encode(Array ('error' => '请输入图片验证码'));
				
				return;
			}
			if($captcha->verify($seed, $code) === false) {
				echo json_encode(Array ('error' => '图片验证码错误，请重新输入'));
				
				return;
			}
			if($val->has_err) {
				$json = $val->ToJsonWithHtml();
				echo $json;
				
				return;
			}
		}
		
		$vali_code_count = base_lib_BaseUtils::getStr($pathdata['vali_code_count'], "int", 1);
		
		$client_ip = base_lib_BaseUtils::getIp(0);
		$error = '';
		
		$action_common = new base_service_common_actionsource();
		
		try {
			$service_personreg = new base_service_person_personregvlid();
			$code = $service_personreg->createRegValidCodeV2($client_ip, $phone, $error, $action_common->website, $vali_code_count);
		} catch (Exception $ex) {
			echo json_encode(array ('error' => $ex->getMessage()));
			
			return;
		}
		
		if(!empty($error)) {
			echo json_encode(array ('error' => $error));
			
			return;
		}
		echo json_encode(array ('success' => '发送验证码成功,' . $code));
		
		return;
	}
	
	/**
	 * 手机验证码登录
	 * @param $inPath
	 */
	function pageMobilePhoneLoginDo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$validator = new base_lib_Validator();
		$phone = $validator->getMobile($path_data['txtMobilePhone'], '您输入的手机号格式不正确');
		$authcode = base_lib_BaseUtils::getStr($path_data['txtMobilPhoneCode'], 'string', '');
		$loginTimeCount = base_lib_BaseUtils::getStr($path_data['loginTimeCount'], 'string', ''); //登录次数
		$school_source = base_lib_BaseUtils::getStr($path_data['school_source'], 'int', 1); //登录渠道
		
		$service_person = new base_service_person_person();
		$save_login = base_lib_BaseUtils::getStr($path_data['chkSave'], 'string', 'false') == 'true' ? true : false;
		
		if(!in_array($school_source, array (1, 2))) {
			$arr_json['error'] = '登录渠道错误！';
			echo json_encode($arr_json);
			
			return;
		}
		if(empty($authcode)) {
			$arr_json['error'] = '请输入短信验证码！';
			echo json_encode($arr_json);
			
			return;
		}
		
		$user_ip = base_lib_BaseUtils::getIp(0);
		$service_reglid = new base_service_person_personregvlid();
		$service_reglid->validMobilePhone($user_ip, $phone, $authcode, $error); //验证手机验证码
		
		if(!empty($error)) {
			$validator->addErr($error);
		}
		
		if($validator->has_err) {
			$arr_json['error'] = $validator->err[0];
			echo json_encode($arr_json);
			return;
		}
		//是否是新注册的用户，默认为否
		$is_new_person = false;
		$service_person = new base_service_person_person();
		$person = $service_person->getCheckPhoneUniqueInfo($phone);
		$photo = $person !== false ? (isset($person["photo"]) ? $person["photo"] : "") : "";
		$photo = preg_match('/^\s*$/', $photo) ? null : $photo;
		$headphoto = base_lib_BaseUtils::nullOrEmpty($photo) ? '' : base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $photo;
		if(empty($person)) {
			$person['user_id'] = '#' . $phone;
			$person['mobile_phone'] = $phone;
			$person['mobile_phone_is_validation'] = '1';
			$common_actionsource = new base_service_common_actionsource();
			$person['create_area_id'] = base_lib_BaseUtils::getCookie('ip_area_info');
			$person['reg_source'] = 'web_pc_natural';
			$person['person_id'] = $service_person->addPerson($person, 'phone', $common_actionsource->website, $resume_id);
			$person = $person;
			$is_new_person = true;
		}
		$arr_json['is_new_person'] = $is_new_person;
		//20191217新增，检查用户账号是否被封禁
		$is_forbidden = $this->_checkForbidden($person['person_id'], $phone);
		if($is_forbidden !== false) {
			$arr_json['error'] = $is_forbidden['msg'];
			echo json_encode($arr_json);
			
			return;
		}
		
		$ser_netfair_person = new base_service_netfair_person();
		$netfair_person_id = $ser_netfair_person->getNetFairPersonId($person['person_id'], $school_source);
		if(!$netfair_person_id) {
			$netfair_person_id = $ser_netfair_person->AddNetFairBasePerson($person['person_id'], $school_source);
		}
		
		//1 、设置当前用户和是否保留登录状态
		$skey = md5($person['person_id'] . $person['user_name'] . $this->_usertype . base_lib_Constant::SYSUSERKEY);
		$cookie = array (
			'userid'         => $person['person_id'],
			'netfair_userid' => $netfair_person_id,
			'netfair_source' => $school_source,
			'netfair_skey'   => md5($netfair_person_id . $school_source . base_lib_Constant::SYSUSERKEY),
			'nickname'       => $person['user_name'],
			'usertype'       => $this->_usertype,
			'userkey'        => $skey,
			'headphoto'      => $headphoto,
		);
		
		if(true === $save_login) {
			base_lib_BaseUtils::ssetcookie($cookie, 3600 * 24 * 7, '/', base_lib_Constant::COOKIE_DOMAIN);
		}
		else {
			base_lib_BaseUtils::ssetcookie($cookie, 3600 * 24, '/', base_lib_Constant::COOKIE_DOMAIN);
		}
		$mi = date('Y-m-d H:i');
		$stamp = strtotime($mi);
		$this->setPersonLifetime($this->getPersonSessionid($person['person_id']), $skey . '_' . $stamp, $save_login);
		//2、求职者统计数逻辑    to do..
		
		//3、简历自动登录   to do..
		
		//4、修改登录次数   添加登录日志   to do..
		$service_person->addPersonLoginTimes($person['person_id']);
		$service_loginlog = new base_service_person_loginlog();
		$loginlog['person_id'] = $person['person_id'];
		$loginlog['login_time'] = date('Y-m-d H:i:s');
		$loginlog['ip'] = base_lib_BaseUtils::getIp(0);
		$loginlog['origin'] = 'web';
		$loginlog['is_success'] = '1';
		$loginlog['login_id'] = $person['user_id'];
		$loginlog['password'] = '';
		$service_loginlog->addPersonLoginLog($loginlog);
		$this->clearFailTimes($person['user_id']);
		
		//保存登录动作
		$service_actiontype = new base_service_common_actiontype();
		$service_actionusertype = new base_service_common_actionusertype();
		$service_actionsource = new base_service_common_actionsource();
		base_lib_BaseUtils::saveAction($service_actiontype->login, $service_actionsource->website, $person['person_id'], $service_actionusertype->person);
		
		$arr_json['success'] = '登录成功！';
		
		$service_resume = new base_service_person_resume_resume();
		$default_resume = $service_resume->getDefaultResume($person['person_id'], 'resume_id');
		//刷新简历
		if($person["open_mode"] == 1 && !empty($default_resume)) {
			$service_resume->refreshResume($default_resume["resume_id"], 1, 'login');
		}
		
		//如果是投递简历进来的，并且没有创建过简历，进入创建简历页面
		$jobflag = base_lib_BaseUtils::getStr($path_data['jobflag'], 'string', '');
		$actiontype = base_lib_BaseUtils::getStr($path_data['actiontype'], 'string', '');
		if(!empty($jobflag) && !empty($actiontype) && $actiontype == "apply") {
			$arr_json['ss'] = $default_resume;
			if(empty($default_resume)) {
				$arr_json['create_resume_url'] = base_lib_Constant::PERSON_URL_NO_HTTP .
					"/person/createBasic?jobflag={$jobflag}";
			}
		}
		
		// 验证是否绑定微信账号
		$personcache_service = new base_service_person_personcache();
		$weixinaccount = $personcache_service->getPersonCache($person['person_id'], "person_id,openid,open_mode,resume_id");
		if(base_lib_BaseUtils::nullOrEmpty($weixinaccount['openid'])) {
			$isBindWX = false;
		}
		else {
			$isBindWX = true;
		}
		$arr_json['isbindwx'] = $isBindWX;
		echo header("Content-type:text/plain;charset=utf-8");
		echo json_encode($arr_json);
		
		return;
	}
	
	/**
	 * @param  $inPath
	 */
	public function pageAjaxLogin($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$validator = new base_lib_Validator();
		$user_name = $validator->getNotNull($path_data['txtUserName'], '请输入用户名');
		$password = $validator->getNotNull($path_data['txtPassword'], '请输入密码');
		$school_source = base_lib_BaseUtils::getStr($path_data['school_source'], 'int', 1); //登录渠道
		if($validator->has_err) {
			$arr_json['error'] = $validator->err[0];
			echo json_encode($arr_json);
			
			return;
		}
		$service_person = new base_service_person_person();
		$save_login = base_lib_BaseUtils::getStr($path_data['chkSave'], 'string', 'false') == 'true' ? true : false;
		$md5_pass = base_lib_BaseUtils::md5_16($password);
		$person = $service_person->GetPersonByUserIDEmailTel($user_name, $md5_pass, 'person_id,user_name,photo,open_mode,mobile_phone');
		
		if(empty($person)) {
			$this->addFailTimes();
			$arr_json['error'] = '用户名或密码错误！';
			echo json_encode($arr_json);
			
			return;
		}
		
		$photo = $person !== false ? (isset($person["photo"]) ? $person["photo"] : "") : "";
		$photo = preg_match('/^\s*$/', $photo) ? null : $photo;
		$headphoto = base_lib_BaseUtils::nullOrEmpty($photo) ? '' : base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $photo;
		
		$skey = md5($person['person_id'] . $person['user_name'] . $this->_usertype . base_lib_Constant::SYSUSERKEY);
		if($person) {
			//20191217新增，检查用户账号是否被封禁
			$is_forbidden = $this->_checkForbidden($person['person_id'], $person['mobile_phone']);
			if($is_forbidden !== false) {
				$arr_json['error'] = $is_forbidden['msg'];
				echo json_encode($arr_json);
				
				return;
			}
			
			$ser_netfair_person = new base_service_netfair_person();
			$netfair_person_id = $ser_netfair_person->getNetFairPersonId($person['person_id'], $school_source);
			if(!$netfair_person_id) {
				$netfair_person_id = $ser_netfair_person->AddNetFairBasePerson($person['person_id'], $school_source);
			}
			
			//1 、设置当前用户和是否保留登录状态
			$cookie = array (
				'userid'         => $person['person_id'],
				'netfair_userid' => $netfair_person_id,
				'netfair_source' => $school_source,
				'netfair_skey'   => md5($netfair_person_id . $school_source . base_lib_Constant::SYSUSERKEY),
				'nickname'       => $person['user_name'],
				'usertype'       => $this->_usertype,
				'userkey'        => $skey,
				'headphoto'      => $headphoto,
			);
			if(true === $save_login) {
				base_lib_BaseUtils::ssetcookie($cookie, 3600 * 24 * 7, '/', base_lib_Constant::COOKIE_DOMAIN);
			}
			else {
				base_lib_BaseUtils::ssetcookie($cookie, 3600 * 24, '/', base_lib_Constant::COOKIE_DOMAIN);
			}
			$mi = date('Y-m-d H:i');
			$stamp = strtotime($mi);
			$this->setPersonLifetime($this->getPersonSessionid($person['person_id']), $skey . '_' .
				$stamp, $save_login);
			
			//2、求职者统计数逻辑    to do..
			
			//3、简历自动登录   to do..
			
			//4、修改登录次数   添加登录日志   to do..
			$service_person->addPersonLoginTimes($person['person_id']);
			$service_loginlog = new base_service_person_loginlog();
			$loginlog['person_id'] = $person['person_id'];
			$loginlog['login_time'] = date('Y-m-d H:i:s');
			$loginlog['ip'] = base_lib_BaseUtils::getIp(0);
			$loginlog['origin'] = 'web';
			$loginlog['is_success'] = '1';
			$loginlog['login_id'] = $user_name;
			$loginlog['password'] = $password;
			$service_loginlog->addPersonLoginLog($loginlog);
			$this->clearFailTimes($user_name);
			
			//保存登录动作
			$service_actiontype = new base_service_common_actiontype();
			$service_actionusertype = new base_service_common_actionusertype();
			$service_actionsource = new base_service_common_actionsource();
			base_lib_BaseUtils::saveAction($service_actiontype->login, $service_actionsource->website, $person['person_id'], $service_actionusertype->person);
			
			$arr_json['success'] = '登录成功！';
			
			
			$service_resume = new base_service_person_resume_resume();
			$default_resume = $service_resume->getDefaultResume($person['person_id'], 'resume_id');
			
			//刷新简历
			if($person["open_mode"] == 1 && !empty($default_resume)) {
				$service_resume->refreshResume($default_resume["resume_id"], 1, 'login');
			}
			
			//如果是投递简历进来的，并且没有创建过简历，进入创建简历页面
			$jobflag = base_lib_BaseUtils::getStr($path_data['jobflag'], 'string', '');
			$actiontype = base_lib_BaseUtils::getStr($path_data['actiontype'], 'string', '');
			if(!empty($jobflag) && !empty($actiontype) && $actiontype == "apply") {
				
				$arr_json['ss'] = $default_resume;
				if(empty($default_resume)) {
					$arr_json['create_resume_url'] = base_lib_Constant::PERSON_URL_NO_HTTP .
						"/person/createBasic?jobflag={$jobflag}";
				}
			}
			
			// 验证是否绑定微信账号
			$personcache_service = new base_service_person_personcache();
			$weixinaccount = $personcache_service->getPersonCache($person['person_id'], "person_id,openid,open_mode,resume_id");
			if(base_lib_BaseUtils::nullOrEmpty($weixinaccount['openid'])) {
				$isBindWX = false;
			}
			else {
				$isBindWX = true;
			}
			$arr_json['isbindwx'] = $isBindWX;
			
			
		}
		else {
			$this->addFailTimes();
			$arr_json['error'] = '用户名或密码错误！';
			echo json_encode($arr_json);
			
			return;
		}
		echo header("Content-type:text/plain;charset=utf-8");
		echo json_encode($arr_json);
		
		return;
		
	}
	
	/**
	 * @Desc 添加登录失败次数
	 * @return type_name
	 */
	function addFailTimes() {
		$count = base_lib_BaseUtils::getCookie(base_lib_Constant::FAIL_TIMES_SESSIONKEY);
		$times = 0;
		if(isset($count) && !empty($count)) {
			$times = intval($count);
		}
		$times = $times + 1;
		base_lib_BaseUtils::ssetcookie(array (base_lib_Constant::FAIL_TIMES_SESSIONKEY => $times));
	}
	
	/**
	 * @Desc 清除登录失败次数
	 * @return type_name
	 */
	function clearFailTimes($user_name) {
		if(base_lib_BaseUtils::nullOrEmpty($user_name)) {
			return;
		}
		//base_lib_BaseUtils::ssetcookie(array(base_lib_Constant::FAIL_TIMES_SESSIONKEY=>null));
		//应朱经理需求漏洞盒子检查 改成按登录名在redis查询
		$cache = new base_lib_Cache('redis');
		$cache->redis_delete($this->cache_prefix . $user_name);
	}
	
	/**
	 * 检查用户是否已被封号
	 * @param $person_id
	 * @return array|bool
	 */
	private function _checkForbidden($person_id, $mobile_phone = null) {
		//查看求职者是否已经被封号
		$service_forbidden = new base_service_person_personforbidden();
		/* $forbidden = $service_forbidden->getForbiddenByPersonId($person_id, 'id,end_time', 1);
		 if (!empty($forbidden['end_time']) && strtotime($forbidden['end_time']) > time()) {
			 return array('status' => false, 'msg' => '您因发布广告被系统封号');
		 }*/
		$params = array (
			'person_id'    => $person_id,
			'mobile_phone' => $mobile_phone,
		);
		$forbidden = $service_forbidden->getForbidden($params, 'id,end_time', 1);
		if(!empty($forbidden['end_time']) && strtotime($forbidden['end_time']) > time()) {
			return array ('status' => false, 'msg' => '您因发布广告被系统封号');
		}
		
		return false;
	}
	
	/**
	 * 用户每天登录次数
	 * @param $inPath
	 */
	public function pageGetPersonLoginTimes($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$mobile_phone = base_lib_BaseUtils::getStr($path_data['mobile_phone'], 'string', '');
		if(empty($mobile_phone)) {
			echo json_encode(['status' => false, 'count' => 10]);
			
			return;
		}
		$personservice = new base_service_person_person();
		$service_personregvlid = new base_service_person_personregvlid();
		$person = $personservice->getPersonByPhone($mobile_phone, null, 'person_id,user_name');
		$count = $service_personregvlid->__getValidCodeCount($mobile_phone)['count'];
		if(empty($person)) {
			echo json_encode(['status' => true, 'count' => 10]);
			
			return;
		}
		echo json_encode(['status' => true, 'count' => $count]);
		
		return;
	}
	#endregion
	
	#region 快米求职者登录
	public function pageBlueLoginDo($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		
		$mobile_phone = base_lib_BaseUtils::getStr($pathdata['txtMobilePhone'], "string", "");
		$auth_code = base_lib_BaseUtils::getStr($pathdata['txtMobilPhoneCode'], "string", "");
		$fromurl = base_lib_BaseUtils::getStr($pathdata["fromurl"], "string", "");
		$school_source = 2;
		if(empty($mobile_phone) || !preg_match('/^1\d{10}$/', $mobile_phone)) {
			echo json_encode(["status" => false, "msg" => "请输入正确的电话号码"]);
			
			return;
		}
		
		$service_blue_person_mobilevalid = new base_service_blue_person_mobilevalid();
		$service_blue_person_person = new base_service_blue_person_person();
		$service_common_actionsource = new base_service_common_actionsource();
		//动作类型
		$service_actiontype = new base_service_common_actiontype();
		
		$validate = $service_blue_person_mobilevalid->getLastValidation($mobile_phone);
		if(empty($validate) || strtotime($validate["deadline_time"]) < $this->_time) {
			return json_encode(["status" => false, "msg" => "验证码失效"]);
		}
		if($validate["validation_code"] != $auth_code) {
			return json_encode(["status" => false, "msg" => "验证码错误"]);
		}
		$service_blue_person_mobilevalid->updateValidStatus($validate['valid_id']);
		
		$person = $service_blue_person_person->getPersonByMobile($mobile_phone, "person_id,user_name,sex,birthday,max_degree_id,cur_area_id,weight,stature,exp_salary,create_time");
		//如果未通过手机找到用户，则注册
		$source = $service_common_actionsource->bluemobile;
		
		$is_sync_hbinfo = false;
		if(empty($person)) {
			$person['person_id'] = $service_blue_person_person->registerPerson($mobile_phone, $source);
			$person['user_name'] = '';
			if(!$person['person_id']) {
				echo json_encode(["status" => false, "msg" => "登录失败，稍后再试"]);
				
				return;
			}
			$action_type = $service_actiontype->register;
			
			//-------------------------@add 2019-11-25 快米改版： 通过快米注册的用户 >> 同步汇博账号------------------
			$service_person = new base_service_person_person();
			$hb_person = $service_person->getCheckPhoneUniqueInfo($mobile_phone);
			if(!empty($hb_person)) {
				$service_blue_person_person->syncHbResume($person['person_id'], null, $hb_person['person_id']);
				$is_sync_hbinfo = true;
			}
		}
		else {
			$action_type = $service_actiontype->login;
			$service_blue_person_person->updatePerson($person['person_id'], [
				'refresh_time'    => date("Y-m-d H:i:s"),
				'last_login_time' => date("Y-m-d H:i:s"),
			]);
		}
		
		$ser_netfair_person = new base_service_netfair_person();
		$netfair_person_id = $ser_netfair_person->getNetFairPersonId($person['person_id'], $school_source);
		if(!$netfair_person_id) {
			$netfair_person_id = $ser_netfair_person->AddNetFairBasePerson($person['person_id'], $school_source);
		}
		
		$cookie = array (
			'b_userid'       => $person['person_id'],
			'netfair_userid' => $netfair_person_id,
			'netfair_source' => $school_source,
			'netfair_skey'   => md5($netfair_person_id . $school_source . base_lib_Constant::SYSUSERKEY),
			'b_nickname'     => $person['user_name'],
			'b_usertype'     => 'k',
			'fromurl'        => $fromurl,
			'b_userkey'      => md5($person['person_id'] . $person['user_name'] . 'k' . base_lib_Constant::SYSUSERKEY),
		);
		$m_area_info = base_lib_BaseUtils::getCookie("M_area_info");
		
		//关闭浏览器就失效
		base_lib_BaseUtils::ssetcookie($cookie, 0, '/', base_lib_Constant::COOKIE_DOMAIN);
		//用户类型
		$service_actionusertype = new base_service_common_actionusertype();
		base_lib_BaseUtils::saveActionBlue($action_type, $source, $person['person_id'], $service_actionusertype->blueperson);
		
		//添加登录日志
		$ser_loginlog = new base_service_blue_person_loginlog();
		$loginlog['person_id'] = $person['person_id'];
		$loginlog['login_time'] = $this->now();
		$loginlog['login_date'] = $this->_ymd;
		$loginlog['ip'] = base_lib_BaseUtils::getIp(0);
		$loginlog['origin'] = $source;
		$loginlog['is_success'] = '1';
		$loginlog['login_id'] = $mobile_phone;
		$ser_loginlog->addPersonLoginLog($loginlog);
		
		$register_info['is_sync_hbinfo'] = $is_sync_hbinfo;
		// if($action_type == 1){
		// 	echo json_encode(["status"=>TRUE,"register_info"=>$register_info, "msg"=>"首次参加面试可领取最高50元面试补贴红包，快去投递职位吧","is_sweep_red_code"=>$is_sweep_red_code]);
		// }else{
		// 	echo json_encode(["status"=>TRUE,"register_info"=>$register_info, "msg"=>"登录成功",]);
		// }
		
		echo json_encode(["status" => true, "register_info" => $register_info, "msg" => "登录成功",]);
		
		return;
	}
	
	//发送验证码
	public function pageBlueSendAuthCode($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		
		$mobile_phone = base_lib_BaseUtils::getStr($pathdata['mobile_phone'], "string", "");
		$imgcode = base_lib_BaseUtils::getStr($pathdata['imgcode'], "string", "");
		$hidSeed = base_lib_BaseUtils::getStr($pathdata['hidSeed'], "string", "");
		
		if(empty($mobile_phone) || !preg_match('/^1\d{10}$/', $mobile_phone)) {
			echo json_encode(["status" => false, "msg" => "请输入正确的电话号码"]);
			
			return;
		}
		if(empty($imgcode)) {
			echo json_encode(["status" => false, "msg" => "请输入图形验证码"]);
			
			return;
		}
		$captcha = new SCaptchalu();
		if($captcha->verify($hidSeed, $imgcode) === false) {
			echo json_encode(["status" => false, "msg" => "图形验证码错误"]);
			
			return;
		}
		
		$service_common_actionsource = new base_service_common_actionsource();
		$service_blue_person_mobilevalid = new base_service_blue_person_mobilevalid();
		if(!$service_blue_person_mobilevalid->addValidationCode($mobile_phone, $error, 1, $service_common_actionsource->bluemobile)) {
			echo json_encode(["status" => false, "msg" => $error]);
			
			return;
		}
		
		echo json_encode(["status" => true]);
		
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
	
	/**
	 * 其他站点 跳转到视频招聘会站点自动登录信息加密    zhouwenjun 2020/3/6 16:07
	 */
	function pageAutoNetfairDomainCookie($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$smcrpt_str = base_lib_BaseUtils::getStr($pathdata['smcrpt_str'], "string");
		if(!$smcrpt_str) {
			return $this->render('404.html', array ('error_msg' => '自动登录信息错误'));
		}
		
		$ser_SAESmcrpt = new SAESmcrpt();
		$smcrpt = json_decode($ser_SAESmcrpt->decode($smcrpt_str), true);
		if(!$smcrpt) {
			return $this->render('404.html', array ('error_msg' => '自动登录信息解密错误'));
		}
		if($smcrpt['validity_time'] < $this->_time - 10) {
			return $this->render('404.html', array ('error_msg' => '自动登录信息已失效,请重试!'));
		}
		
		$cookie = array (
			'userid'         => $smcrpt['userid'],
			'netfair_userid' => $smcrpt['netfair_userid'],
			'netfair_source' => $smcrpt['netfair_source'],
			'netfair_skey'   => md5($smcrpt['netfair_userid'] . $smcrpt['netfair_source'] .
				base_lib_Constant::SYSUSERKEY),
			'nickname'       => $smcrpt['nickname'],
			'usertype'       => $smcrpt['usertype'],
			'userkey'        => $smcrpt['userkey'],
			'mobilesys'      => $smcrpt['mobilesys'],
			'verson'         => $smcrpt['verson'],
		);
		//关闭浏览器就失效
		base_lib_BaseUtils::ssetcookie($cookie, 0, '/', base_lib_Constant::COOKIE_DOMAIN);
		$back_url = $smcrpt['back_url'] ?: '/';
		
		return $this->redirect_url2($back_url);
	}
}

?>
