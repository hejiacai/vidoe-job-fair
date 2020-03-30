<?php

/**
 * @copyright 2002-2013 www.huibo.com
 * @name 企业登录
 * @author    fuzy
 * @version   2013-6-26 下午5:19:50
 */
class controller_login extends components_cbasepage {
	
	private $back_url;
	static  $key = "wzeh3iyaun";

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct(false);
		session_start();
		$this->_aParams['seed'] = uniqid();
		
	}


	
	/**
	 * 登录入口
	 */
	function pageIndex($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$logintype = base_lib_BaseUtils::getStr($pathdata['type'], "string", ""); //如果带有参数 type-part 的链接则表示是兼职企业登录
		$redirect = base_lib_BaseUtils::getStr($pathdata['redirect'], "string", ""); //如果带有参数 redirect 参数表示登陆后的链接跳转
		$is_down = base_lib_BaseUtils::getStr($pathdata['is_down'], "int", 0); //是否被顶号 默认 0 没有顶号
		if ($logintype == "part") {
			$this->_aParams['is_part'] = true;
		}
		
		if (!empty($redirect)) {
			$this->_aParams['redirect'] = $redirect;
		}
		
		if ($this->isLogin() && $this->_usertype == 'c') {
			$this->redirect(base_lib_Constant::COMPANY_URL_NO_HTTP . $redirect);
		}
		
		$this->_aParams['needCode'] = $this->_isShowAuthCode();
		$this->_aParams['title'] = "汇博网-企业登录";
		
		$xml = SXML::load('../config/config.xml');
		if (!is_null($xml)) {
			$this->_aParams['ShowResumeNum'] = $xml->ShowResumeNum;
			$this->_aParams['ShowCompanyNum'] = $xml->ShowCompanyNum;
			$this->_aParams['ShowDayJobNum'] = $xml->ShowDayJobNum;
			$this->_aParams['VisitPerDay'] = $xml->VisitPerDay;
			$this->_aParams['HuiboPhone400'] = $xml->HuiboPhone400;
			$this->_aParams['HuiBoFairShowRoom'] = $xml->HuiBoFairShowRoom;
			$this->_aParams['HuiBoFairAttendance'] = $xml->HuiBoFairAttendance;
		}
		
		//hr活动
		$service_hr = new base_service_hractivity_activity();
		$hrlist = $service_hr->getTopNList(1, 'activity_id,subject,activity_time,modus,feature,resume_id,resume_id,course_introduction');
		
		$review_activity_items = $hrlist->items;
		if (count($review_activity_items) > 0) {
			$service_resume = new base_service_hractivity_lecturer();
			$service_modus = new base_service_hractivity_modus();
			$resume_ids = $review_activity_items[0]['resume_id'];
			$resumes = $service_resume->getLecturer($resume_ids, 'resume_id,user_name');
			
			$review_activity_items[0]['resume_name'] = $resumes['user_name'];
			$review_activity_items[0]['date'] = date('m月d日', strtotime($review_activity_items[0]['activity_time']));
			$review_activity_items[0]['modus'] = $service_modus->getName($review_activity_items[0]['modus']);
			$this->_aParams['review_activity_arr'] = $review_activity_items;
		}
		session_start();
		/**=====二维码扫码部分======**/
		$phpsessid = session_id();
		//加密sessid
		$service_sqrcode = new base_service_company_app_sqrcodelogin();
		$items = array ();
		$items["session_id"] = $phpsessid;
		$items["status"] = 0;
		$service_sqrcode->addSqrcodeInfo($items);
		$this->_aParams["phpsessid"] = base_lib_BaseUtils::authcode($phpsessid, "ENCODE", self::$key);

		$this->_aParams['is_down'] = $is_down;
		return $this->render('login.html', $this->_aParams);
	}

    /**
     * 登录是否需要验证码
     */
	public function pageNeedCode($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $callbackparam = base_lib_BaseUtils::getStr($pathdata['callbackparam'], 'string', '');
        exit($this->_returnJsons($callbackparam,['needCode'=>$this->_isShowAuthCode()]));
    }
	
	/**
	 * @desc 扫码二维码
	 */
	public function pageSqrcode($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$phpsessid = base_lib_BaseUtils::getStr($pathdata["ssid"], "string", "");
		$phpsessid = str_replace(" ", "+", $phpsessid);
		$service_sqrcode_type = new base_service_common_sqrcodetype();
		SQrcode::png(json_encode(array ('ssid' => $phpsessid, "apiname" => "company_web_login", "type" => $service_sqrcode_type->companylogin)));
	}
	
	/**
	 * @desc 长轮询
	 */
	public function pageChecksqrlogin($inPath) {
		//set_time_limit(0);//无限请求超时时间
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$phpsessid = base_lib_BaseUtils::getStr($pathdata["ssid"], "string", "");
		$phpsessid = str_replace(" ", "+", $phpsessid);
		$session_id = base_lib_BaseUtils::authcode($phpsessid, "DECODE", self::$key);
		//判断有参数
		$type = base_lib_BaseUtils::getStr($pathdata["type"], "string", "scan");//默认扫码
		$service_sqrcode = new base_service_company_app_sqrcodelogin();
		$time = time();
		$data = $service_sqrcode->getSqrcodeStatusByCache($session_id);
		if ($data === false) {
			echo $this->jsonMsg(false, "二维码已失效");
			
			return;
		}
		//验证是否
		if ($type == "scan") {
			if ($data > 0) {
				echo $this->jsonMsg(true, "扫码成功", array ("s" => 1));
				
				return;
			}
		}
		if ($type == "login") {
			if ($data == "2") {
				echo $this->jsonMsg(true, "已确认登录", array ("s" => $data));
				
				return;
			}
			if ($data == "3") {
				echo $this->jsonMsg(true, "取消登录", array ("s" => $data));
				
				return;
			}
		}
		echo $this->jsonMsg(true, "", array ("s" => 0));
		
		return;
	}
	
	/**
	 * @desc 验证是否绑定微信
	 */
	public function pageCheckBindWX($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$this->checkLogin();
		
		$companywerixinservice = new base_service_weixin_companyweixin();
		$is_bindWX = $companywerixinservice->isBind(base_lib_BaseUtils::getCookie('accountid'), $this->_userid);
		
		echo $this->jsonMsg(true, "", array ("isbind" => $is_bindWX));
		
		return;
	}
	
	
	public function pageLoginBySqrcode($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		//扫码登录
		$ssid = base_lib_BaseUtils::getStr($pathdata["ssid"], "string", "");
		$ssid = str_replace(" ", "+", $ssid);
		$session_id = base_lib_BaseUtils::authcode($ssid, "DECODE", self::$key);
		
		$service_sqrcode = new base_service_company_app_sqrcodelogin();
		$data = $service_sqrcode->getSqrcodeInfoBySessionId($session_id, "company_id,account_id,status");
		if (empty($data)) {
			$this->_aParams["msg"] = "未找到当前扫码信息，或二维码失效";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}
		if ($data["status"] != 2 || empty($data["company_id"]) || empty($data["account_id"])) {
			$this->_aParams["msg"] = "未找到当前扫码信息，或二维码失效";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}
		
		$service_company = new base_service_company_company();
		$service_account = new base_service_company_account();
		$serivce_com_log = new base_service_company_loginlog();
		$service_forbid = new base_service_company_accountsforbid();
		$service_company_change = new base_service_company_changeable();
		$service_comstate = new base_service_company_comstate();
		$service_actionsource = new base_service_common_actionsource();
		
		$account_info = $service_account->getAccount($data["account_id"], "account_id,is_main,company_id,user_id");
		if (empty($account_info)) {
			$this->_aParams["msg"] = "账号信息失效";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}
		$company_info = $service_company->getCompany($account_info["company_id"], 1, "company_id,company_name");
		if (empty($company_info)) {
			$this->_aParams["msg"] = "企业信息失效";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}
		$isforbid = $service_forbid->checkForbid($account_info["user_id"], date('Y-m-d H:i:s'));
		if ($isforbid) {
			//账号被禁用
			$this->_aParams["msg"] = "账号被禁用，请联系客服!";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}
		
		$skey = md5($company_info['company_id'] . $company_info['company_name'] . 'c' . base_lib_Constant::SYSUSERKEY);
		//一个单位可能存在多个用户同时登录，用一个tick表示不同的用户
		$tick = base_lib_BaseUtils::random(10, 0);
		//登陆成功记录到cookie
		$aCookie = array (
			'userid'    => $account_info['company_id'],
			'accountid' => $account_info["account_id"],
			'nickname'  => $company_info['company_name'],
			'usertype'  => 'c',
			'tick'      => $tick,
			'userkey'   => $skey,
			"headphoto" => ""
		);
		base_lib_BaseUtils::ssetcookie($aCookie, 0, '/', base_lib_Constant::COOKIE_DOMAIN);
		//更新账户最近登录时间
		$service_company_account = new base_service_company_account();
		$service_company_account->updateLastLoginTime($account_info['account_id']);
		//添加登录日志
		if (!$account_info['is_main']) {
			$companyaccountlog_service = new base_service_company_companyaccountlog();
			$ervice_common_account_accountoperatetype = new base_service_common_account_accountoperatetype();
			$service_common_account_accountlogfrom = new base_service_common_account_accountlogfrom();
			$insertItems = array (
				"company_id"   => $account_info['company_id'],
				"account_id"   => $account_info['account_id'],
				"operate_type" => $ervice_common_account_accountoperatetype->account_login,
				"content"      => date("Y-m-d H:i:s", time()) . ",根据APP扫码登录系统。",
				"create_time"  => date("Y-m-d H:i:s", time()),
				"source"       => $service_common_account_accountlogfrom->website
			);
			$companyaccountlog_service->addLogToMongo($insertItems);
		}
		//track与用户的映射
		$this->userTrackBind($account_info['company_id'], 1, 1);
		
		$mi = date('Y-m-d H:i');
		$stamp = strtotime($mi);
		$this->setLifetime($this->getSessionid($account_info['company_id'], $tick, $account_info["account_id"]), $skey . '_' . $stamp);
		
		//添加成功日志
		$companylog['company_id'] = $account_info['company_id'];
		$companylog['login_time'] = date('Y-m-d H:i:s');
		$companylog['ip'] = base_lib_BaseUtils::getIp(0);
		$companylog['is_success'] = 1;
		$companylog['login_id'] = "";
		$companylog['password'] = "";
		$companylog['is_cqjob_staff_login'] = 0;
		$companylog['origin_company_id'] = 0;
		$companylog["source"] = $service_actionsource->website;
		$serivce_com_log->addCompanyLoginLog($companylog);
		
		//添加单位异变表
		$service_company_change = new base_service_company_changeable();
		$service_company_change->AddLoginTimes($company_info['company_id']);
		
		//自动锁定任务
		$comstate = $service_comstate->getCompanyState($company_info['company_id'], "is_repeat,net_heap_id");
		if ($comstate['net_heap_id'] <= 0) {
			$service_process = new base_service_company_processtask();
			$service_process_state = new base_service_company_processtaskstate();
			$service_process_type = new base_service_company_processtasktype();
			$item_process['company_id'] = $company_info['company_id'];
			$item_process['state'] = $service_process_state->unexecuted;
			$item_process['try_times'] = 0;
			$item_process['type'] = $service_process_type->loginautolock;
			$item_process['create_time'] = date('Y-m-d H:i:s');
			$service_process->addProcessTask($item_process);
		}
		
		// 记录用户cookie
		$userCookie = array ('username' => urlencode($account_info["user_id"]));
		base_lib_BaseUtils::ssetcookie($userCookie, 3600 * 24 * 7, '/', base_lib_Constant::COOKIE_DOMAIN);
		
		//保存登录动作
		//动作类型
		$service_actiontype = new base_service_common_actiontype();
		//用户类型
		$service_actionusertype = new base_service_common_actionusertype();
		//渠道
		base_lib_BaseUtils::saveAction($service_actiontype->login, $service_actionsource->website, $company['company_id'], $service_actionusertype->company);
		$this->redirect();
	}
	
	/**
	 *
	 * 单位ajax登录
	 * @param  $inPath
	 */
	function pageAjaxLogin($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$callback = base_lib_BaseUtils::getStr($pathdata['success'], 'string', 'null');
		$this->_aParams['callback'] = $callback;
		
		return $this->render('./companyajaxlogin.html', $this->_aParams);
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
			} else {
				$invaliErr = "{$limitMinutes}分钟内密码输错{$forbidTimes}次，您的账号将被禁用{$forbidMinutes}分钟，您还有<span class='strong' style='font-size:16px;'>{$errCount}</span>次机会";
			}
		} else {
			$comstate = $service_comstate->getCompanyState($company['company_id'], "is_repeat,net_heap_id");
			if ($comstate['is_repeat']) {
				//重复账号
				$error = "重复账号,请联系客服!";
			}
		}
		
		if (base_lib_BaseUtils::nullOrEmpty($company['company_logo_path'])) {
			$headphoto = '';
		} else {
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

        //----------是否是汇博教育企业-----------
        $service_comstate = new base_service_company_comstate();
        $comstate         = $service_comstate->getCompanyState($company['company_id'], "train_type");
        $is_company_edu   = $comstate['training_type'] == 1 ? true : false;
        if($is_company_edu){
            $error = "该账号只能登录汇博教育!";
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
		


		//关闭浏览器就失效
		base_lib_BaseUtils::ssetcookie($aCookie, 0, '/', base_lib_Constant::COOKIE_DOMAIN);
		
		$mi = date('Y-m-d H:i');
		$stamp = strtotime($mi);

		//清除SESSION
		$this->_clearFailTimes();
		
		//添加登陆次数		
		$servic_company_change->AddLoginTimes($company['company_id']);
		

		
		//track与用户的映射
		$this->userTrackBind($company['company_id'], 1, 1);
		
		//自动锁定任务
		if ($comstate['net_heap_id'] <= 0) {
			$service_process = new base_service_company_processtask();
			$service_process_state = new base_service_company_processtaskstate();
			$service_process_type = new base_service_company_processtasktype();
			$item_process['company_id'] = $company['company_id'];
			$item_process['state'] = $service_process_state->unexecuted;
			$item_process['try_times'] = 0;
			$item_process['type'] = $service_process_type->loginautolock;
			$item_process['create_time'] = date('Y-m-d H:i:s');
			$service_process->addProcessTask($item_process);
		}
		
		$userCookie = array ('username' => urlencode($user_name));
		base_lib_BaseUtils::ssetcookie($userCookie, 3600 * 24 * 7, '/', base_lib_Constant::COOKIE_DOMAIN);
		
		//保存登录动作
		//动作类型
		$service_actiontype = new base_service_common_actiontype();
		//用户类型
		$service_actionusertype = new base_service_common_actionusertype();
		//渠道
		$service_actionsource = new base_service_common_actionsource();
		base_lib_BaseUtils::saveAction($service_actiontype->login, $service_actionsource->website, $company['company_id'], $service_actionusertype->company);



        //是否强制下线逻辑判断
        $login_info['login_id'] = $user_name;
        $login_info['password'] = $password;
        $this->loginPeatRemain($company['company_id'],$company['account_id'],$login_info);

        //服务器登陆信息放在强制下线逻辑后面
        $this->setLifetime($this->getSessionid($company['company_id'], $tick, $company['account_id']), $skey . '_' . $stamp);


        //添加成功日志
        $companylog['company_id'] = $company['company_id'];
        $companylog['login_time'] = date('Y-m-d H:i:s');
        $companylog['ip'] = base_lib_BaseUtils::getIp(0);
        $companylog['is_success'] = 1;
        $companylog['login_id'] = $user_name;
        $companylog['password'] = $password;
        $companylog['is_cqjob_staff_login'] = 0;
        $companylog['origin_company_id'] = 0;
        $serivce_com_log->addCompanyLoginLog($companylog);

        //更新账户最近登录时间
        $service_company_account = new base_service_company_account();
        $service_company_account->updateLastLoginTime($company['account_id']);

        //添加登录日志
        if (!$company['is_main']) {
            $companyaccountlog_service = new base_service_company_companyaccountlog();
            $ervice_common_account_accountoperatetype = new base_service_common_account_accountoperatetype();
            $service_common_account_accountlogfrom = new base_service_common_account_accountlogfrom();
            $insertItems = array (
                "company_id"   => $company['company_id'],
                "account_id"   => $company['account_id'],
                "operate_type" => $ervice_common_account_accountoperatetype->account_login,
                "content"      => date("Y-m-d H:i:s", time()) . ",登录系统。",
                "create_time"  => date("Y-m-d H:i:s", time()),
                "source"       => $service_common_account_accountlogfrom->website
            );
            $companyaccountlog_service->addLogToMongo($insertItems);
        }

        $json_arr['name'] = $company['company_name'];
		$json_arr['success'] = '登录成功';
		echo json_encode($json_arr);
		
		return;
	}

    /**
     * 登录顶号提醒
     * @falg 企业迭代3.03 强制登陆逻辑
     */
	function loginPeatRemain($company_id,$account_id,$login_info){
        return true;//功能暂停使用

	    $key = $this->getSessionid($company_id, 'tick',$account_id);
        $has_login_ip = $this->getLoginIp_arr($key);
        $ip = base_lib_BaseUtils::getIp(0);

        if(!empty($has_login_ip) && !in_array((string)$ip,$has_login_ip)){
            //需要提醒是否强制下线
            session_start();
            $_SESSION['is_peat_login_verify'] = true;
            $_SESSION['peat_login_company_id'] = $company_id;
            $_SESSION['peat_login_account_id'] = $account_id;
            $_SESSION['peat_login_info'] = $login_info;

            exit(json_encode(['status'=>false,'error'=>'提醒是否强制下线','code'=>1888,'ip'=>$ip]));
        }
    }


    /**
     * 强制登陆执行
     * @falg 企业迭代3.03 强制登陆逻辑
     * @desc 需验证之前是否成功登陆 从session
     */
    function pageForceLoginDo($inPath){
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

        session_start();
        if($this->isLogin() && $this->_userid){
            $service_company = new base_service_company_company();
            $company = $service_company->getCompany($this->_userid,1,"company_id,company_name,company_logo_path");
            goto End;
        }

        if(!$_SESSION['is_peat_login_verify'] || !$_SESSION['peat_login_company_id'] || !$_SESSION['peat_login_account_id']){
            exit(json_encode(['status'=>false,'error'=>'请先登陆','code'=>0]));
        }
        $company_id = $_SESSION['peat_login_company_id'];

        $login_info = $_SESSION['peat_login_info'];

        $service_company = new base_service_company_company();
        $company = $service_company->getCompany($company_id,1,"company_id,company_name,company_logo_path,is_main");
        $company['account_id'] = $_SESSION['peat_login_account_id'];

        $mi = date('Y-m-d H:i');
        $stamp = strtotime($mi);
        $tick = base_lib_BaseUtils::random(10, 0);
        $skey = md5($company['company_id'] . $company['company_name'] . 'c' . base_lib_Constant::SYSUSERKEY);
        $session_key = $this->getSessionid($company['company_id'], $tick, $company['account_id']);
        $this->setLifetime($session_key, $skey . '_' . $stamp);

        //清除SESSION
        $this->_clearFailTimes();


        //关闭浏览器就失效
        if (base_lib_BaseUtils::nullOrEmpty($company['company_logo_path'])) {
            $headphoto = '';
        } else {
            $xml = SXML::load('../config/company/company.xml');
            if (!is_null($xml)) {
                $VirtualName = $xml->VirtualName;//虚拟目录名 CompanyImages
                $LogoFolder = $xml->LogoFolder;// <!--logo文件夹名-->
            }
            $headphoto = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $VirtualName . '/' . $LogoFolder . '/' . $company['company_logo_path'];
        }
        $aCookie = array (
            'userid'    => $company['company_id'],
            'accountid' => $company['account_id'],
            'nickname'  => $company['company_name'],
            'usertype'  => 'c',
            'tick'      => $tick,
            'userkey'   => $skey,
            'headphoto' => $headphoto
        );
        base_lib_BaseUtils::ssetcookie($aCookie, 0, '/', base_lib_Constant::COOKIE_DOMAIN);

      
        //添加登录日志
        //更新账户最近登录时间
        $service_company_account = new base_service_company_account();
        $service_company_account->updateLastLoginTime($company['account_id']);

        if (!$company['is_main']) {
            $companyaccountlog_service = new base_service_company_companyaccountlog();
            $ervice_common_account_accountoperatetype = new base_service_common_account_accountoperatetype();
            $service_common_account_accountlogfrom = new base_service_common_account_accountlogfrom();
            $insertItems = array (
                "company_id"   => $company['company_id'],
                "account_id"   => $company['account_id'],
                "operate_type" => $ervice_common_account_accountoperatetype->account_login,
                "content"      => date("Y-m-d H:i:s", time()) . ",登录系统。",
                "create_time"  => date("Y-m-d H:i:s", time()),
                "source"       => $service_common_account_accountlogfrom->website
            );
            $companyaccountlog_service->addLogToMongo($insertItems);
        }

        //添加成功日志
        $serivce_com_log = new base_service_company_loginlog();
        $service_actionsource = new base_service_common_actionsource();
        $companylog['company_id'] = $company['company_id'];
        $companylog['login_time'] = date('Y-m-d H:i:s');
        $companylog['ip'] = base_lib_BaseUtils::getIp(0);
        $companylog['is_success'] = 1;
        $companylog['login_id'] = $login_info['login_id'];
        $companylog['password'] = $login_info['password'];
        $companylog['is_cqjob_staff_login'] = 0;
        $companylog['origin_company_id'] = 0;
        $companylog["source"] = $service_actionsource->website;
        $serivce_com_log->addCompanyLoginLog($companylog);


        End:

        //以微信绑定及模板消息通知类型增加  判断是否绑定微信
        $companywerixinservice = new base_service_weixin_companyweixin();
        $is_bindweixin = $companywerixinservice->isBind($company['account_id'], $company['company_id']);


        //清除session
        $_SESSION['is_peat_login_verify'] = false;


        /*登录*/
        $json_arr['name'] = $company['company_name'];
        $json_arr['bindweixin'] = $is_bindweixin;
        $json_arr['success'] = '登录成功';


        $this->_returnJsons('',$json_arr);


    }



    /**
     * 强制登陆提醒页面
     * @falg 企业迭代3.03 强制登陆逻辑
     * @desc 需验证之前是否成功登陆 从session
     */
    public function pageForceLogin($inPath){
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->_aParams['redirect'] = base_lib_BaseUtils::getStr($path_data['backurl'], "string", ""); //如果带有参数 redirect 参数表示登陆后的链接跳转
        $this->_aParams['is_part'] = base_lib_BaseUtils::getStr($path_data['part'], "bool", false); //true是兼职企业登录
        $this->_aParams['ishistoryback'] = base_lib_BaseUtils::getStr($path_data['ishistoryback'], "bool", false);

        if($this->isLogin()){
            $this->redirect(base_lib_Constant::COMPANY_URL);
            return 1;
        }

        session_start();

        if(!$_SESSION['is_peat_login_verify'] || !$_SESSION['peat_login_company_id'] || !$_SESSION['peat_login_account_id']){
            $data["msg"] = "请先从企业端登陆";
            $data["url"] = base_lib_Constant::COMPANY_URL."/login";

            return $this->render("./common/showmsg.html", $data);
        }

        //获取当前在线的ip
        $key = $this->getSessionid($_SESSION['peat_login_company_id'], 'tick',$_SESSION['peat_login_account_id']);
        $has_login_ip = $this->getLoginIp_arr($key);
        $ip = $has_login_ip[0];

        $service_account = new base_service_company_account();
        $account = $service_account->getAccount($_SESSION['peat_login_account_id'],'user_id,last_login_time');
        $account['ip'] = $ip;
        $this->_aParams['account'] = $account;


        return $this->render("./resume/invite/force_index.html",$this->_aParams);
    }



	/**
	 * 登录
	 */
	function pageLoginDo($inPath) {

        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$val = new base_lib_Validator();
		$user_name = $val->getStr($path_data['txtUsername'], 2, 30, '请输入长度为2-30位的用户名');
		$password = $val->getStr($path_data['txtPassword'], 6, 18, '请输入长度为6-18位的密码');
        $callbackparam = base_lib_BaseUtils::getStr($path_data['callbackparam'], 'string', '');
        $specil_list_type = base_lib_BaseUtils::getStr($path_data['specil_list_type'], 'int', 0);// 企业特殊名单限制 1:区团委

		if ($val->has_err) {
			//将错误打印json
            $this->_returnJsons($callbackparam,array ('error' => $val->toHtml()));
		}
		
		//是否需要验证码
		$needCode = $this->_isShowAuthCode();
		
		if ($needCode) {
			$code = base_lib_BaseUtils::getStr($path_data['txtAuthCode']);
			$seed = base_lib_BaseUtils::getStr($path_data['hddseed']);
			$captcha = new SCaptchalu();
			if ($captcha->verify($seed, $code) === false) {
				$this->_returnJsons($callbackparam,array ('error' => '验证码错误'));
			}
		}
		
		$service_company = new base_service_company_company();
		/*--------------boss端企业违规封号处理- 2018-12-15 --------------*/
		$boos_fordden = $service_company->isBossForbid($user_name);
		if ($boos_fordden['is_foribid'] === true) {
            $this->_returnJsons($callbackparam,array (
                'code'  => 'boss_forbid',
                'error' => $boos_fordden['msg']
            ));
		}
		
		
		$company = $service_company->newLogin($user_name, $password, 'a.company_id,a.company_name,a.company_logo_path,b.account_id,b.is_main,b.user_id');
		$serivce_com_log = new base_service_company_loginlog();
		$service_forbid = new base_service_company_accountsforbid();
		$service_company_change = new base_service_company_changeable();
		$service_comstate = new base_service_company_comstate();
		//渠道
		$service_actionsource = new base_service_common_actionsource();

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
                $this->_returnJsons($callbackparam,['error' => "账号被禁用，请联系客服!", "user_id" => $user_name, "redirect_html" => 1, "error_type" => 1]);
			} else {
				$invaliErr = "若{$limitMinutes}分钟内密码输错{$forbidTimes}次，您的账号将被禁用{$forbidMinutes}分钟。您还有<span class='strong' style='font-size:16px;'>{$errCount}</span>次机会";
			}
		} else {
			$comstate = $service_comstate->getCompanyState($company['company_id'], "is_repeat,net_heap_id");
			if ($comstate['is_repeat']) {
				//重复账号
				$this->_returnJsons($callbackparam,['error' => "重复账号,请联系客服!", "user_id" => $user_name, "redirect_html" => 1, "error_type" => 2]);
			}

			//---------2019-05-14 @UPDATE 企业相关白名单过滤----------
            if ($specil_list_type > 0) {
                $specillist_service = new base_service_company_speciallist();
                $can_pass = false;
			    switch ($specil_list_type) {
                    case 1:
                        //区团委名单限制
                        $can_pass = $specillist_service->isEffect($company['company_id'],$company['user_id'],$specil_list_type);
                        break;
                    default:
                        $can_pass = false;
                        break;
                }

                if(!$can_pass){
                    $this->_returnJsons($callbackparam,array ('error' => '该企业无白名单登录权限'));
                }
            }
		}
		

		//检查账号是否被禁用
		$isforbid = $service_forbid->checkForbid($user_name, date('Y-m-d H:i:s'));
		if ($isforbid) {
			//账号被禁用
            $this->_returnJsons($callbackparam,['error' => "账号被禁用，请联系客服!", "user_id" => $user_name, "redirect_html" => 1, "error_type" => 1]);
		}


		
		//输出错误信息
		if (!empty($error)) {
			$json_arr['invaliErr'] = $invaliErr;
			$json_arr['needCode'] = $this->_getFailTimes() + 1 >= $fail_times_then_cuth_code;
			$json_arr['error'] = $error;
            $this->_returnJsons($callbackparam,$json_arr);
		}

        //----------是否是汇博教育企业-----------
        $service_comstate = new base_service_company_comstate();
        $comstate         = $service_comstate->getCompanyState($company['company_id'], "train_type");
        $is_company_edu   = $comstate['train_type'] == 1 ? true : false;
        if($is_company_edu){
            $error = "该账号只能登录汇博教育!";
        }
        //输出错误信息
        if (!empty($error)) {
            $json_arr['invaliErr'] = $invaliErr;
            $json_arr['needCode'] = $this->_getFailTimes() + 1 >= $fail_times_then_cuth_code;
            $json_arr['error'] = $error;
            $this->_returnJsons($callbackparam,$json_arr);
        }



        $servic_passwordlog = new base_service_company_passwordlog();
		$state = $servic_passwordlog->getLastCompanyPasswordLog($company['company_id'], 'is_change');
		if (!base_lib_BaseUtils::nullOrEmpty($state) && $state['is_change'] == 0 && $company["is_main"]) {
			$json_arr['need_update_password'] = 'true';
		}
		//判断密码是否符合验证规则
		$val->getPassword($password);
		if ($val->has_err && empty($json_arr["need_update_password"])) {
			$json_arr['need_update_password_by_badpassword'] = 'true';
			$snumcrypt = new SNumCrypt();
			$app_token_key = "wzeh3iyaun";
			$encrypt = $snumcrypt->encrypt($company["account_id"], $app_token_key);
			$json_arr["token"] = $encrypt;
			$json_arr["code"] = $password;
            $this->_returnJsons($callbackparam,$json_arr);
		}
		$skey = md5($company['company_id'] . $company['company_name'] . 'c' . base_lib_Constant::SYSUSERKEY);





		//一个单位可能存在多个用户同时登录，用一个tick表示不同的用户
		$tick = base_lib_BaseUtils::random(10, 0);
		
		if (base_lib_BaseUtils::nullOrEmpty($company['company_logo_path'])) {
			$headphoto = '';
		} else {
			$xml = SXML::load('../config/company/company.xml');
			if (!is_null($xml)) {
				$VirtualName = $xml->VirtualName;//虚拟目录名 CompanyImages
				$LogoFolder = $xml->LogoFolder;// <!--logo文件夹名-->
			}
			$headphoto = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $VirtualName . '/' . $LogoFolder . '/' . $company['company_logo_path'];
		}
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




		//关闭浏览器就失效
		base_lib_BaseUtils::ssetcookie($aCookie, 0, '/', base_lib_Constant::COOKIE_DOMAIN);
		
		$mi = date('Y-m-d H:i');
		$stamp = strtotime($mi);
		

		//添加登陆次数
		$service_company_change->AddLoginTimes($company['company_id']);
		
		

		
		//track与用户的映射
		$this->userTrackBind($company['company_id'], 1, 1);
		
		//自动锁定任务
		if ($comstate['net_heap_id'] <= 0) {
			$service_process = new base_service_company_processtask();
			$service_process_state = new base_service_company_processtaskstate();
			$service_process_type = new base_service_company_processtasktype();
			$item_process['company_id'] = $company['company_id'];
			$item_process['state'] = $service_process_state->unexecuted;
			$item_process['try_times'] = 0;
			$item_process['type'] = $service_process_type->loginautolock;
			$item_process['create_time'] = date('Y-m-d H:i:s');
			$service_process->addProcessTask($item_process);
		}
		
		
		// 记录用户cookie
		$userCookie = array ('username' => urlencode($user_name));
		base_lib_BaseUtils::ssetcookie($userCookie, 3600 * 24 * 7, '/', base_lib_Constant::COOKIE_DOMAIN);
		
		//保存登录动作
		//动作类型
		$service_actiontype = new base_service_common_actiontype();
		//用户类型
		$service_actionusertype = new base_service_common_actionusertype();
		
		base_lib_BaseUtils::saveAction($service_actiontype->login, $service_actionsource->website, $company['company_id'], $service_actionusertype->company);


        $this->_clearFailTimes();



        if(!$callbackparam)
        {
            /**
             *  是否强制下线逻辑判断
             * @desc 如果有来自其他站点的跨域请求，直接强制下线不用提醒
             */
            $login_info['login_id'] = $user_name;
            $login_info['password'] = $password;
            $this->loginPeatRemain($company['company_id'],$company['account_id'],$login_info);
        }

        //服务器登陆信息放在强制下线逻辑后面
        $this->setLifetime($this->getSessionid($company['company_id'], $tick, $company['account_id']), $skey . '_' . $stamp);


        //更新账户最近登录时间
        $service_company_account = new base_service_company_account();
        $service_company_account->updateLastLoginTime($company['account_id']);
        if (!$company['is_main']) {
            $companyaccountlog_service = new base_service_company_companyaccountlog();
            $ervice_common_account_accountoperatetype = new base_service_common_account_accountoperatetype();
            $service_common_account_accountlogfrom = new base_service_common_account_accountlogfrom();
            $insertItems = array (
                "company_id"   => $company['company_id'],
                "account_id"   => $company['account_id'],
                "operate_type" => $ervice_common_account_accountoperatetype->account_login,
                "content"      => date("Y-m-d H:i:s", time()) . ",登录系统。",
                "create_time"  => date("Y-m-d H:i:s", time()),
                "source"       => $service_common_account_accountlogfrom->website
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
        $this->_returnJsons($callbackparam,$json_arr);
	}


    private function _returnJsons($callbackparam, $data)
    {
        if (!empty($callbackparam)) {
            exit($callbackparam . '(' . json_encode((array)$data) . ")");
        } else {
            exit(json_encode((array)$data));
        }
    }


	//重置密码展示
	function pagePasswordMod($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$from = base_lib_BaseUtils::getStr($path_data["from"], "string", "");
		$this->_aParams["from"] = $from;
		
		return $this->render('loginupdatepassword.html', $this->_aParams);
	}
	
	//重置密码
	function pagePasswordModDo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$company_id = $this->_userid;
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		if (empty($company_id) || empty($account_id)) {
			$json_arr['error'] = "请重新登陆";
			echo json_encode($json_arr);
			
			return;
		}
		$val = new base_lib_Validator();
		$password = $val->getPassword($path_data['txtUpdatePassword']);
		$verify_password = $val->getPassword($path_data['txtVerifyPassword']);
		
		//错误提示
		if ($val->has_err) {
			echo $val->toJsonWithHtml();
			
			return;
		}
		//密码验证
		if ($password !== $verify_password) {
			echo json_encode(array ('error' => '两次密码输入不一致'));
			
			return;
		}
		$service_company_account = new base_service_company_account();
		$service_company = new base_service_company_company();
		$main_account = $service_company_account->getMainAccount($company_id, "company_id,account_id");
		if ($main_account) {
			$result = $service_company->updateCompanyPassword($company_id, $password);
		} else {
			$result = $service_company_account->updateAccount($account_id, array ('password' => base_lib_BaseUtils::md5_16($password)));
		}
		
		if ($result === false) {
			echo json_encode(array ('error' => '密码修改失败!'));
			
			return;
		}
		
		
		$service_passwordlog = new base_service_company_passwordlog();
		$result = $service_passwordlog->setPasswordLogChanged($company_id);
		
		if ($result === false) {
			echo json_encode(array ('error' => '密码修改失败!'));
			
			return;
		}
		
		echo json_encode(array ('success' => '密码修改成功!'));
		
		return;
	}
	
	
	/**
	 * 获取验证码
	 */
	function pageVerify($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$seed = $path_data["seed"];
		$captcha = new SCaptchalu();
		$captcha->conf->type = 0;
		$image_resource = $captcha->getImageResource($seed);
		//ob_clean();//防止出现'图像因其本身有错无法显示'的问题。
		header("Content-type: image/png");
		if (false !== $image_resource)
			imagepng($image_resource);
	}
	
	/**
	 * 退出登录  h65pgujteiftunkv7p4rmv6vg0  uh0jm8i7p06i2j6hvactdipac7  2015101515105375631 2015101515105375631
	 */
	function pageDoLogout() {
		
		$this->destroySession(); //需要在清除cookie之前调用
		
		$aCookie = array (
			'userid'         => '',
			'nickname'       => '',
			'usertype'       => '',
			'tick'           => '',
			'userkey'        => '',
			'headphoto'      => '',
			'hidePromiseTip' => '',
			'accountid'      => '',
			'bossuser'       => ""
		);
		base_lib_BaseUtils::ssetcookie($aCookie, -1, '/', base_lib_Constant::COOKIE_DOMAIN);
		//销毁session
		session_start();
		session_unset();
		session_destroy();
		
		header("Location:" . base_lib_Constant::MAIN_URL_NO_HTTP);
		
		return;
	}
	
	/**
	 * [pageFakeLoginDo description]
	 * @param  [type] $inPath [description]
	 * @return [type]         [description]
	 */
	function pageFakeLoginDo($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$flag = $pathdata['flag'];
		
		$crypt = new SNumCrypt();
		$configXml = SXML::load("../config/fake.xml");
		
		if (is_null($configXml))
			return $this->render("../config/404.html");
		
		// if (strpos($_SERVER["HTTP_REFERER"], "http://cp.boss.huibo.com/fakelogin/") === false)
		// 	return $this->render("../config/404.html");
		
		$company_id = $crypt->decrypt($flag, $configXml->key);
		/* 获取公司id */
		$service_company_account = new base_service_company_account();
		$service_company = new base_service_company_company();
		$company = $service_company->getCompany($company_id, 1, "company_id,company_name,company_logo_path");
		if (base_lib_BaseUtils::nullOrEmpty($company['company_logo_path'])) {
			$headphoto = '';
		} else {
			$xml = SXML::load('../config/company/company.xml');
			if (!is_null($xml)) {
				$VirtualName = $xml->VirtualName;//虚拟目录名 CompanyImages
				$LogoFolder = $xml->LogoFolder;// <!--logo文件夹名-->
			}
			$headphoto = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $VirtualName . '/' . $LogoFolder . '/' . $company['company_logo_path'];
		}
		
		if (empty($company))
			return $this->render("../config/404.html");
		
		$skey = md5($company['company_id'] . $company['company_name'] . 'c' . base_lib_Constant::SYSUSERKEY);
		//一个单位可能存在多个用户同时登录，用一个tick表示不同的用户
		$tick = base_lib_BaseUtils::random(10, 0);
		$account_list_temp = $service_company_account->getAccountList($company_id, 'account_id,is_main');
		$account_list = $account_list_temp->items;
		if (empty($account_list))
			return $this->render("../config/404.html");
		
		foreach ($account_list as $value) {
			if ($value['is_main']) {
				$accountid = $value['account_id'];
				break;
			}
		}
		//登陆成功记录到cookie
		$aCookie = array (
			'userid'    => $company['company_id'],
			'nickname'  => $company['company_name'],
			'usertype'  => 'c',
			'tick'      => $tick,
			'userkey'   => $skey,
			'headphoto' => $headphoto,
			'accountid' => $accountid
		);
		//关闭浏览器就失效
		base_lib_BaseUtils::ssetcookie($aCookie, 0, '/', base_lib_Constant::COOKIE_DOMAIN);
		
		$mi = date('Y-m-d H:i');
		$stamp = strtotime($mi);
		$this->setLifetime($this->getSessionid($company['company_id'], $tick, $accountid), $skey . '_' . $stamp);
		
		//清除SESSION
		$this->_clearFailTimes();
		
		$this->redirect();
	}
	
	function pageLoginErr($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$this->_aParams['err_type'] = base_lib_BaseUtils::getStr($pathdata['error_type'], "int", 1);
		$this->_aParams['user_id'] = base_lib_BaseUtils::getStr($pathdata['user_id'], "string", "");
		if ($this->_aParams['err_type'] == 1) {
			$this->_aParams['is_main'] = 0;
			$service_company_account = new base_service_company_account();
			$account = $service_company_account->getAccountByUserId($this->_aParams['user_id'], "is_main");
			$this->_aParams['is_main'] = !$account ? 1 : $account['is_main'];
		}
		$xml = SXML::load('../config/config.xml');
		if (!is_null($xml)) {
			$this->_aParams['HuiboPhone400'] = $xml->HuiboPhone400;
		}
		
		return $this->render("loginerr.html", $this->_aParams);
	}
	
	//判断是否显示验证码
	private function _isShowAuthCode() {
		$xml = SXML::load('../config/company/company.xml');
		if (!is_null($xml)) {
			$fail_times_then_cuth_code = $xml->FailTimesThenAuthCode;
		}


		return $this->_getFailTimes() >= $fail_times_then_cuth_code;
	}
	
	//获取登录失败次数
	private function _getFailTimes() {
		$obj = $_SESSION['_FAIL_TIMES_SESSIONKEY'];
		$times = 0;
		if ($obj != null) {
			$times = $obj;
		}
		
		return $times;
	}
	
	//添加登录失败次数
	private function _addFailTimes() {
		$obj = $_SESSION['_FAIL_TIMES_SESSIONKEY'];
		$times = 0;
		if ($obj != null) {
			$times = $obj;
		}
		$times++;
		$_SESSION['_FAIL_TIMES_SESSIONKEY'] = $times;
		
		return $times;
	}
	
	private function _clearFailTimes() {
		$_SESSION['_FAIL_TIMES_SESSIONKEY'] = null;
	}
	
	/**
	 * @desc 播放视频
	 *
	 */
	public function pagePlayVideo() {
		return $this->render('playvideo.html', $this->_aParams);
	}
	
	
	//ajax参数返回
	private function _return_ajax($status, $message, $error_code) {
		return json_encode(array ('status' => $status, 'message' => $message, 'error_code' => $error_code), JSON_UNESCAPED_UNICODE);
	}
	
	//企业申诉
	function pageCompanyRespreations($inPath) {
		$pathData = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$appeal_service = new base_service_company_appeal();
		$source_service = new base_service_common_appealsource();
		$company_name = base_lib_BaseUtils::getStr($pathData['company_name'], 'string', 0);
		if (empty($company_name))
			exit($this->_return_ajax(false, '公司名称为空', 1));
		$mobile = base_lib_BaseUtils::getStr($pathData['mobile'], 'string', 0);
		if (base_lib_BaseUtils::nullOrEmpty($mobile))
			exit($this->_return_ajax(false, '联系电话不能为空', 1));
		
		
		$service_company = new base_service_company_company();
		/*--------------boss端企业违规封号处理- 2018-12-15 --------------*/
		$boos_fordden = $service_company->isBossForbid(null, null, $company_name, null, 'explain');
		if ($boos_fordden['is_foribid'] === true) {
			exit($this->_return_ajax(false, $boos_fordden['msg'], 1));
		}
		$boos_fordden = $service_company->isBossForbid(null, null, null, $mobile, 'explain');
		if ($boos_fordden['is_foribid'] === true) {
			exit($this->_return_ajax(false, $boos_fordden['msg'], 1));
		}
		
		$items['company_name'] = $company_name;
		$items['mobile'] = $mobile;
		$items['source_type'] = $source_service->regpage;
		
		//添加一个工单信息
		$report = new base_service_report_report();
		$reportData = array ();
		$reportData['order_type'] = 4;
		$reportData['title'] = '企业账户申诉';
		$reportData['content'] = $company_name;
		$reportData['source_obj_type'] = 2;
		$reportData['source_obj_name'] = $company_name;
		$reportData['tel'] = $mobile;
		$reportData['product'] = 'PC端登录页面';
		$reportData['product_type'] = base_lib_BaseUtils::GetBrowser();
		$res = $report->addReport($reportData);
		
		if ($appeal_service->addAppeal($items)) {
			exit($this->_return_ajax(true, 'ok', 1));
		} else {
			exit($this->_return_ajax(false, '请求失败，稍后尝试', 1));
		}
		
	}
	
	//重置密码展示
	function pagePasswordModV2($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$from = base_lib_BaseUtils::getStr($path_data["from"], "string", "");
		$code = $path_data["code"];
		$token = $path_data["token"];
		$this->_aParams["code"] = $code;
		$this->_aParams["token"] = $token;
		
		return $this->render('loginupdatepasswordv2.html', $this->_aParams);
	}
	
	//重置密码
	function pagePasswordModDoV2($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$token = $path_data["token"];
		$code = $path_data["code"];
		$snumcrypt = new SNumCrypt();
		$app_token_key = "wzeh3iyaun";
		$account_id = $snumcrypt->decrypt($token, $app_token_key);
		if (empty($account_id) || empty($token) || empty($code)) {
			$json_arr['error'] = "验证失败";
			echo json_encode($json_arr);
			
			return;
		}
		$service_company_account = new base_service_company_account();
		$base_pwd = base_lib_BaseUtils::md5_16(trim($code));
		$account_info = $service_company_account->getAccount($account_id, "account_id,company_id,password");
		if (empty($account_info) || $account_info["password"] != $base_pwd) {
			$json_arr['error'] = "密码验证失败";
			echo json_encode($json_arr);
			
			return;
		}
		
		$val = new base_lib_Validator();
		$password = $val->getPassword($path_data['txtUpdatePassword']);
		$verify_password = $val->getPassword($path_data['txtVerifyPassword']);
		
		//错误提示
		if ($val->has_err) {
			echo $val->toJsonWithHtml();
			
			return;
		}
		//密码验证
		if ($password !== $verify_password) {
			echo json_encode(array ('error' => '两次密码输入不一致'));
			
			return;
		}
		$service_company = new base_service_company_company();
		//获取简历ID
		$main_account = $service_company_account->getMainAccount($account_info["company_id"], "company_id,account_id");
		if ($main_account) {
			$result = $service_company->updateCompanyPassword($account_info["company_id"], $password);
		} else {
			$result = $service_company_account->updateAccount($account_id, array ('password' => base_lib_BaseUtils::md5_16($password)));
		}
		
		if ($result === false) {
			echo json_encode(array ('error' => '密码修改失败!'));
			
			return;
		}
		
		
		$service_passwordlog = new base_service_company_passwordlog();
		$result = $service_passwordlog->setPasswordLogChanged($account_info["company_id"]);
		
		if ($result === false) {
			echo json_encode(array ('error' => '密码修改失败!'));
			
			return;
		}
		
		echo json_encode(array ('success' => '密码修改成功，请重新登录'));
		
		return;
	}
	
	/**
	 * @desc bosslogin
	 */
	public function pageBossLogin($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$flag = $path_data['company_flag'];
		$user_flag = $path_data["user_flag"];
		$edit_job_id = $path_data["edit_job_id"];
		$type = $path_data["type"];
		$AESmcrpt = new SAESmcrpt();
		$validity_time = $AESmcrpt->decode($path_data["validity_time"]);
		if (!$validity_time || $validity_time < time()) {
			$this->_aParams["msg"] = "请求超时,请重试!";
			$this->_aParams["url"] = base_lib_Constant::MAIN_URL_NO_HTTP;
			
			return $this->render("./common/showmsg.html", $this->_aParams);
		}
		$crypt = new SNumCrypt();
		$configXml = SXML::load("../config/fake.xml");
		if (is_null($configXml))
			return $this->render("../config/404.html");
		
		// if (strpos($_SERVER["HTTP_REFERER"], "http://cp.boss.huibo.com/fakelogin/") === false)
		// 	return $this->render("../config/404.html");
		$company_id = $crypt->decrypt($flag, $configXml->key);
		$user_id = $crypt->decrypt($user_flag, $configXml->key);
		if (!$this->canDo("login_company", $user_id) && !$type) {
			$this->_aParams["msg"] = "无权限访问，没有开通登录权限";
			$this->_aParams["url"] = base_lib_Constant::MAIN_URL_NO_HTTP;
			
			return $this->render("./common/showmsg.html", $this->_aParams);
		}
		/* 获取公司id */
		$service_company_account = new base_service_company_account();
		$service_company = new base_service_company_company();
		$company = $service_company->getCompany($company_id, 1, "company_id,company_name,company_logo_path");
		if (base_lib_BaseUtils::nullOrEmpty($company['company_logo_path'])) {
			$headphoto = '';
		} else {
			$xml = SXML::load('../config/company/company.xml');
			if (!is_null($xml)) {
				$VirtualName = $xml->VirtualName;//虚拟目录名 CompanyImages
				$LogoFolder = $xml->LogoFolder;// <!--logo文件夹名-->
			}
			$headphoto = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $VirtualName . '/' . $LogoFolder . '/' . $company['company_logo_path'];
		}
		
		if (empty($company))
			return $this->render("../config/404.html");
		
		$skey = md5($company['company_id'] . $company['company_name'] . 'c' . base_lib_Constant::SYSUSERKEY);
		//一个单位可能存在多个用户同时登录，用一个tick表示不同的用户
		$tick = base_lib_BaseUtils::random(10, 0);
		$account_info = $service_company_account->getMainAccount($company_id, 'account_id,is_main');
		if (empty($account_info))
			return $this->render("../config/404.html");
		
		$account_id = $account_info["account_id"];
		//登陆成功记录到cookie
		if ($type) {
			$aCookie = array (
				'userid'       => $company['company_id'],
				'usertype'     => 'c',
				'user_id_type' => md5($company['company_id'] . date('Y-m-d') . base_lib_Constant::SYSUSERKEY),
			);
		} else {
			$aCookie = array (
				'userid'    => $company['company_id'],
				'nickname'  => $company['company_name'],
				'usertype'  => 'c',
				'tick'      => $tick,
				'userkey'   => $skey,
				'headphoto' => $headphoto,
				'accountid' => $account_id,
				"bossuser"  => $user_id,
			);
		}
		
		//关闭浏览器就失效
		base_lib_BaseUtils::ssetcookie($aCookie, 1200, '/', base_lib_Constant::COOKIE_DOMAIN);
		
		$mi = date('Y-m-d H:i');
		$stamp = strtotime($mi);
		$session_id = $this->getSessionid($company['company_id'], $tick, $account_id);
		$this->setLifetime($session_id, $skey . '_' . $stamp, true);
		
		//标记为 封号后也可以登录
		$cache_bosslogin = new company_service_bosslogin();
		$cache_bosslogin->deleteCode($this->getSessionid($company['company_id'], $tick, $account_id));
		$cache_bosslogin->setVerifyCode($session_id);
		
		
		//记录伪登录日志
		$service_login_log = new base_service_boss_company_log();
		$items = array ();
		$items["user_id"] = $user_id;
		$items["company_id"] = $company_id;
		$items["log_content"] = "伪登录企业成功";
		$service_login_log->addLog($items);
		//清除SESSION
		$this->_clearFailTimes();
		
		//重定向
		if ($type) {
			$this->redirect("/{$type}");
		}
		//重定向职位修改
		if ($edit_job_id) {
			$this->redirect("/job/mod/job_id-$edit_job_id-mod_type-edit");
		}
		
		$this->redirect();
	}
}

?>