<?php
/**
 * 企业自动登录
 * @ClassName controller_register 
 * @Desc 自动登录
 * @author huangwentao@huibo.com
 * @date 2016-12-14 上午10:30:46
 */
class controller_autologin extends components_cbasepage {
  
    function __construct() {
        parent::__construct(FALSE);
    }

    /**
     * 入口
     */
    public function pageIndex($pathdata) {
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $key = base_lib_BaseUtils::getStr($path_data["key"],"string","");
        if(empty($key)){
            $this->_aParams['msg'] = "访问错误";
            $this->_aParams["url"]      = base_lib_Constant::COMPANY_URL_NO_HTTP."/login"; 
            return $this->render('./common/showmsg.html', $this->_aParams);
        }
        //根据key获取登录信息
        $service_shorturl = new base_service_common_autologinurl();
        $info = $service_shorturl->getInfo($key,"user_name,password,user_type,url");
        if(empty($info)){
            $this->_aParams['msg'] = "该链接已失效或者已过期";
            $this->_aParams["url"]      = base_lib_Constant::COMPANY_URL_NO_HTTP."/login"; 
            return $this->render('./common/showmsg.html', $this->_aParams);
        }
        //添加点击记录
        if($count_id){
            $service_message_marketsendcount = new base_service_message_marketsendcount();
            $service_message_marketsendcount->updateCount(array("id"=>$count_id,"is_click"=>0), array("is_click"=>1,"click_time"=>date("Y-m-d H:i:s")));
        }
        if($info["user_type"] ==1){
            $this->__autoLoginbyCompany($info["user_name"], $info["password"]);
        }else{
            
        }
        $this->redirect(urldecode($info["url"]));
    }
   
     /**
     *@desc 企业自动登录 
     */
    private function __autoLoginbyCompany($user_name,$password){
        //自动登录
        $service_company    = new base_service_company_company();
        $service_account    = new base_service_company_account();
        $serivce_com_log        = new base_service_company_loginlog();
        $company = $service_company->newLogin($user_name, $password,'a.company_id,a.company_name,a.company_logo_path,b.account_id,b.is_main,b.user_id',true);
		$skey = md5($company['company_id'] . $company['company_name'] . 'c' . base_lib_Constant::SYSUSERKEY);
		//一个单位可能存在多个用户同时登录，用一个tick表示不同的用户
		$tick = base_lib_BaseUtils::random(10,0);
		//登陆成功记录到cookie
		$aCookie = array(
			'userid'    => $company['company_id'],
            'accountid' => $company["account_id"],
			'nickname'  => $company['company_name'],
			'usertype'  => 'c',
			'tick'      => $tick,
			'userkey'   => $skey,
            "headphoto" => ""
		);
        base_lib_BaseUtils::ssetcookie($aCookie, 0, '/', base_lib_Constant::COOKIE_DOMAIN);
        //更新账户最近登录时间
        $service_company_account = new base_service_company_account();
        $service_company_account->updateLastLoginTime($company['account_id']);
        //添加登录日志
        if(!$company['is_main']){
            $companyaccountlog_service = new base_service_company_companyaccountlog();
            $ervice_common_account_accountoperatetype = new base_service_common_account_accountoperatetype();
            $service_common_account_accountlogfrom = new base_service_common_account_accountlogfrom();
            $insertItems=array(
              "company_id"=>$company['company_id'],
              "account_id"=>$company['account_id'],
              "operate_type"=>$ervice_common_account_accountoperatetype->account_login,
              "content"=>date("Y-m-d H:i:s",time()) . ",登录系统。",
              "create_time"=>date("Y-m-d H:i:s",time()),
              "source"=>$service_common_account_accountlogfrom->website
            );
            $companyaccountlog_service->addLogToMongo($insertItems);
        }
        //track与用户的映射
		$this->userTrackBind($company['company_id'], 1, 1);
        
		$mi = date('Y-m-d H:i');
		$stamp = strtotime($mi);
		$this->setLifetime($this->getSessionid($company['company_id'],$tick,$company["account_id"]),$skey.'_'.$stamp);
        
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
        
        //添加单位异变表
        $service_company_change = new base_service_company_changeable();
        $service_company_change->AddLoginTimes($company['company_id']);
        
		// 记录用户cookie
		$userCookie = array('username' =>urlencode($user_name));
		base_lib_BaseUtils::ssetcookie($userCookie,3600 * 24 * 7, '/', base_lib_Constant::COOKIE_DOMAIN);

		//保存登录动作
		//动作类型
		$service_actiontype = new base_service_common_actiontype();
		//用户类型
		$service_actionusertype = new base_service_common_actionusertype();
		//渠道
		$service_actionsource = new base_service_common_actionsource();
		base_lib_BaseUtils::saveAction($service_actiontype->login, $service_actionsource->website, $company['company_id'], $service_actionusertype->company);
    }
    
     /**
     *@desc 企业查看采集简历自动登录 
     */
    public function pageGatherResume($inPath){
        $path_data  = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        //判断是手机还是PC
        $detect = new SMobiledetect();
		if ($detect->isMobile() || $detect->isTablet()) {
            $url = base_lib_Constant::APP_MOBILE_URL."/autologin/gatherResume";
			$this->redirect_url2($url,$path_data);
			return;
		}
        $company_id = base_lib_BaseUtils::getStr($path_data["company_id"],"int",0);
        $job_id     = base_lib_BaseUtils::getStr($path_data["job_id"],"int",0);
        $resume_id  = base_lib_BaseUtils::getStr($path_data["resume_id"],"int",0);
        //$recommend_id  = base_lib_BaseUtils::getStr($path_data["recommend_id"],"int",0);
        $time_flag  = base_lib_BaseUtils::getStr($path_data["timeflag"],"string","");       
        $sign       = base_lib_BaseUtils::getStr($path_data["sign"],"string","");     
        $sign_string = "company_id={$company_id}&job_id={$job_id}&resume_id={$resume_id}&timeflag={$time_flag}";
        $my_sign     = base_lib_BaseUtils::md5_16($sign_string);
        if($sign != $my_sign){
            $this->_aParams["msg"] = "访问错误，这里什么都没有";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
        if(empty($company_id) || empty($job_id) || empty($resume_id)){
            $this->_aParams["msg"] = "访问错误，这里什么都没有";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
        $service_job        = new base_service_company_job_job();
        $service_company    = new base_service_company_company();
        $service_recommend = new base_service_company_resume_recommend();
        
		//获得简历的 推荐职位
		$recommend_station = $service_recommend->getResumeInfo($company_id, $resume_id,'recommend_id,station,status,is_read,recommend_type');
		if($recommend_station ===false || empty($recommend_station)){
            $this->_aParams["msg"] = "访问错误，这里什么都没有";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}
        $job_info = $service_job->getJob($job_id, "company_id,account_id");
        if($job_info["company_id"] != $company_id || empty($job_info["account_id"])){
            $this->_aParams["msg"] = "访问错误，这里什么都没有";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
        //自动登录
        $service_account = new base_service_company_account();
        $account_info    = $service_account->getAccount($job_info["account_id"], "user_id,password");
        if(empty($account_info)){
            $this->_aParams["msg"] = "访问错误，没有该账号数据";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
        $this->__autoLoginbyCompany($account_info["user_id"], $account_info["password"]);
        
        $params = [];
        $recommend_id           = $recommend_station["recommend_id"];
        $params["resumeid"]     = $resume_id;
        $params["recommendid"]  = $recommend_id;
        $params["src"]          = "recommend";
        $url = base_lib_Constant::COMPANY_URL."/recommend/recommendResumeInfo";
        $this->redirect_url2($url,$params);
        return;
    }


    public function pageGradeAutoLogin($inPath)
    {
        $pathdata       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $from           = base_lib_BaseUtils::getStr($pathdata['from'], 'string', '');
        $key            = base_lib_BaseUtils::getStr($pathdata['key'], 'string', '');
        $time           = base_lib_BaseUtils::getStr($pathdata['time'], 'string', '');
        $companyid      = base_lib_BaseUtils::getStr($pathdata['cid'], 'int', 0);
        $can_auto_login = true;

        //来源不对
        if ($from != 'mail') {
            $can_auto_login = false;
        }

        //过了有效期
        if (strtotime("-6 days") >= (int)$time) {
            $can_auto_login = false;
        }

        $key_md5 = 'fasdfasd92134sljgwewe.,weSD8FS^%$$2323wjero/;D1';
        if (md5((string)$from . (string)$time  . (string)$companyid . (string)$key_md5) != $key) {
            $can_auto_login = false;
        }

        if(empty($companyid)  || empty($time) || empty($key)){
            $can_auto_login = false;
        }


        // 自动登录
        $service_company = new base_service_company_company();
        $service_account = new base_service_company_account();
        $company         = $service_company->getCompany($companyid, '1', 'company_id,company_name,is_main,is_proxyed,proxy_com_id');
        if (!empty($company)) {
            if($company['is_proxyed'] == 2 && isset($company['proxy_com_id'])){
                $company         = $service_company->getCompany($company['proxy_com_id'], '1', 'company_id,company_name,is_main,is_proxyed,proxy_com_id');
            }
            if(empty($company)){
                $can_auto_login = false;
            }
            $account_id            = $service_account->getMainAccount($company['company_id'], 'account_id,company_id');
            $company['account_id'] = $account_id['account_id'];
        } else {
            $can_auto_login = false;
        }

        if (!$can_auto_login) {
            //跳转登录
            $this->redirect(base_lib_Constant::COMPANY_URL . "/login");
            die;
        }


        $this->_keepLoginInfo($company);

        if(base_lib_BaseUtils::isPhoneClient()){
            $url = base_lib_Constant::MOBILE_URL . "/companyindex/MakeGrade";
        }else{
            $url = base_lib_Constant::COMPANY_URL;
        }
        $this->redirect($url);
        die;
    }
    private function _keepLoginInfo($company, $user_name = '', $password = '')
    {
        $service_actionsource = new base_service_common_actionsource();
        $service_company_change = new base_service_company_changeable();
        $serivce_com_log = new base_service_company_loginlog();

        $skey = md5($company['company_id'] . $company['company_name'] . 'c' . base_lib_Constant::SYSUSERKEY);
        //一个单位可能存在多个用户同时登录，用一个tick表示不同的用户
        $tick = base_lib_BaseUtils::random(10, 0);

        //登陆成功记录到cookie
        $aCookie = array(
            'userid'    => $company['company_id'],
            'accountid' => $company['account_id'],
            'nickname'  => $company['company_name'],
            'usertype'  => 'c',
            'tick'      => $tick,
            'userkey'   => $skey
        );

        //更新账户最近登录时间
        $service_company_account = new base_service_company_account();
        $service_company_account->updateLastLoginTime($company['account_id']);

        //添加登录日志
        if (!$company['is_main']) {
            $companyaccountlog_service = new base_service_company_companyaccountlog();
            $ervice_common_account_accountoperatetype = new base_service_common_account_accountoperatetype();
            $service_common_account_accountlogfrom = new base_service_common_account_accountlogfrom();
            $insertItems = array(
                "company_id"   => $company['company_id'],
                "account_id"   => $company['account_id'],
                "operate_type" => $ervice_common_account_accountoperatetype->account_login,
                "content"      => date("Y-m-d H:i:s", time()) . ",登录系统。",
                "create_time"  => date("Y-m-d H:i:s", time()),
                "source"       => $service_common_account_accountlogfrom->website
            );
            $companyaccountlog_service->addLogToMongo($insertItems);
        }

        //关闭浏览器就失效
        base_lib_BaseUtils::ssetcookie($aCookie, 0, '/', base_lib_Constant::COOKIE_DOMAIN);

        $mi = date('Y-m-d H:i');
        $stamp = strtotime($mi);
        $this->setLifetime($this->getSessionid($company['company_id'], $tick,$company['account_id']), $skey . '_' . $stamp);


        //添加登陆次数
        $service_company_change->AddLoginTimes($company['company_id']);


        //添加成功日志
        if (!empty($user_name) && !empty($password)) {
            $companylog['company_id'] = $company['company_id'];
            $companylog['login_time'] = date('Y-m-d H:i:s');
            $companylog['ip'] = base_lib_BaseUtils::getIp(0);
            $companylog['is_success'] = 1;
            $companylog['login_id'] = $user_name;
            $companylog['password'] = $password;
            $companylog['is_cqjob_staff_login'] = 0;
            $companylog['origin_company_id'] = 0;
            $companylog["source"] = $service_actionsource->weixin;
            $serivce_com_log->addCompanyLoginLog($companylog);
        }

        //track与用户的映射
        $this->userTrackBind($company['company_id'], 1, 1);


        //保存动作类型
        //保存登录动作
        //动作类型
        $service_actiontype = new base_service_common_actiontype();
        //用户类型
        $service_actionusertype = new base_service_common_actionusertype();

        base_lib_BaseUtils::saveAction($service_actiontype->login, $service_actionsource->website, $company['company_id'], $service_actionusertype->company);

        // 记录用户cookie
        if (!empty($user_name)) {
            $userCookie = array('username' => urlencode($user_name));
            base_lib_BaseUtils::ssetcookie($userCookie, 3600 * 24 * 7, '/', base_lib_Constant::COOKIE_DOMAIN);
        }

    }

}