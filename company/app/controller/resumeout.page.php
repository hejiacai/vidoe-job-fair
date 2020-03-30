<?php

/**
 * 邮件简历处理提示
 *
 */
class controller_resumeout extends components_cbasepage{
    private $max_time = 864000;//默认十天失效

    private $key_md5 = 'fasdfasd92134sljgwewe.,weSD8FS^%$$2323wjero/;D1';
    /**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct(FALSE);
	}

    /**
     * 简历处理自动登录
     * @desc 来自邮箱
     * @param $inPath
     */
    public function pageAutoLogin($inPath)
    {
        $pathdata       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $from           = base_lib_BaseUtils::getStr($pathdata['from'], 'string', '');
        $key            = base_lib_BaseUtils::getStr($pathdata['key'], 'string', '');
        $time           = base_lib_BaseUtils::getStr($pathdata['time'], 'string', '');
        $resumeid       = base_lib_BaseUtils::getStr($pathdata['resumeid'], 'int', 0);
        $companyid       = base_lib_BaseUtils::getStr($pathdata['cid'], 'int', 0);
        $applyid        = base_lib_BaseUtils::getStr($pathdata['applyid'], 'int', 0);
        $can_auto_login = true;

        //来源不对
        if ($from != 'mail') {
            $can_auto_login = false;
        }

        //过了有效期
        if (strtotime("-6 days") >= (int)$time) {
            $can_auto_login = false;
        }

        $resume_service = new base_service_person_resume_resume();

        if (md5((string)$from . (string)$time . (string)$resumeid . (string)$applyid . (string)$companyid . (string)$resume_service->key_md5) != $key) {
            $can_auto_login = false;
        }

        if(empty($companyid) || empty($applyid) || empty($time) || empty($key)){
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
            if(!empty($applyid)){
                $url = base_lib_Constant::MOBILE_URL . "/companyresumemanage/applyResumeDetail/apply_id-{$applyid}";
            }else{
                $url = base_lib_Constant::MOBILE_URL . "/companyresumemanage/downResumeDetail/resume_id-{$resumeid}";
            }
        }else{
            $url = base_lib_Constant::COMPANY_URL . "/resume/resumeshow/type-network-resumeid-{$resumeid}-src-apply-applyid-{$applyid}";
        }


        $this->redirect($url);
        die;
    }

    public function pageProcessResume($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$code = base_lib_BaseUtils::getStr($pathdata['code'], 'string', '');
		$code = base_lib_Rewrite::decryptApply($code);
        $this->_time;
   
        $apply_id  = isset($code['apply']) ? $code['apply'] : 0;
        $time  = isset($code['time']) ? $code['time'] : 0;
        $status  = isset($code['status']) ? $code['status'] : 0;
        if (!$apply_id || ($this->_time - $time) > $this->max_time ) $this->_error_msg("处理失败，请登录您的管理系统处理简历");

       	//面试时间
		$invite   = array();
        $invite['audition_time'] = null;
        
        //添加一条邀请简历记录
		$service_company = new base_service_company_company();
		$service_apply   = new base_service_company_resume_apply();
        $service_person_person = new base_service_person_person();
        $service_company_account = new base_service_company_account();
        $apply = $service_apply->getApply($apply_id, 'apply_id,resume_id,job_id,station,re_time,re_status,company_id,person_id');
        if (base_lib_BaseUtils::nullOrEmpty($apply))  $this->_error_msg("处理失败，请登录您的管理系统处理简历");
        if ($apply['re_status'])  $this->_error_msg("处理失败，该投递记录已通过其它客户端处理，请登录您的管理系统查看");
        
		$company_resources = base_service_company_resources_resources::getInstance($apply['company_id']);
        if (base_lib_BaseUtils::nullOrEmpty($company_resources))  $this->_error_msg("处理失败，请登录您的管理系统处理简历");
        
        $currentCompany = $service_company->getCompany($apply['company_id'], 1, 'company_id,company_name,com_level,end_time,address,linkman,link_tel');
        if (!in_array($apply['company_id'], $company_resources->all_accounts))  $this->_error_msg("处理失败，请登录您的管理系统处理简历");
        
        $main_account = $service_company_account->getMainAccount($apply['company_id'], "account_id");
        $person = $service_person_person->getPerson($apply['person_id'], 'user_name');
        if (base_lib_BaseUtils::nullOrEmpty($person))  $this->_error_msg("处理失败，请登录您的管理系统处理简历");
        $title = "{$person['user_name']}-汇博人才网";
        $result = FALSE;
        switch ($status){
            case 0:
                // 婉拒求职者
                $content = "我们认真阅读了您的简历，很遗憾您的简历与该职位的要求不匹配。感谢您对我司的信任，祝您早日找到满意的工作。";
                $result = $service_apply->refusedReplayV2($apply_id, $apply['company_id'], $content);
                break;
            case 1:
                $service_invitetype       = new base_service_company_resume_invitetype();
                $service_job              = new base_service_company_job_job();
                $service_jobapplyrestatus = new base_service_person_jobapplyrestatus();

                $invite['resume_id']         = intval($apply['resume_id']);
                $invite['station']           = $apply['station'];
                $invite['job_id']            = intval($apply['job_id']);

                $current_job = $service_job->getJob($invite['job_id'], 'end_time,company_id');

                $invite['invite_type']       = $service_invitetype->jobapply;//求职者主动投递
                $invite['audition_address']  = $currentCompany['address'];
                $invite['audition_link_man'] = $currentCompany['linkman'];
                $invite['audition_link_tel'] = $currentCompany['link_tel'];
                $invite['audition_remark']   = "";

                //单位编号
                $invite['company_id']        = $apply['company_id'];

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

                if (empty($resume)) return;

                $invite['person_id'] = $resume['person_id'];
                $service_invite = new base_service_company_resume_jobinvite();
                $result = $service_invite->initiativeInviteV2($currentCompany, $resume, $invite, $apply);
                break;
        }

		//获得各种状态下收到的总条数
        if ($result){
            //---------添加操作日志--------根据需求在此添加操作记录
            $common_oper_type = new base_service_common_account_accountoperatetype();
            $service_oper_log = new base_service_company_companyaccountlog();
            $common_oper_src_type = new base_service_common_account_accountlogfrom();
            $insertItems=array(
                "company_id"=>$apply['company_id'],
                "source"=>$common_oper_src_type->website,
                "account_id"=>$main_account['account_id'],
                "create_time"=>date("Y-m-d H:i:s",time())
            );
            if($status){
                $insertItems["operate_type"] = $common_oper_type->resume_SetInvite_tell;
                $insertItems["content"] = "'".$person['user_name']."'投递的简历 在邮件中'".$apply['station']."' 被设置为已通知.";
            }else{
                $insertItems["operate_type"] = $common_oper_type->resume_refuse;
                $insertItems["content"] = "'".$person['user_name']."'投递的简历 在邮件中'".$apply['station']."' 被设置为不合适.";
            }
            $service_oper_log->addLogToMongo($insertItems);
            //-------------END------------
            $apply_status_count = $service_apply->getStatusGroupCount($company_resources->all_accounts);
            $this->_aParams['apply_status_count'] = $apply_status_count->items;
            return $this->render("./resume/out/processdone.html", $this->_aParams);
        } 
    }
    
    private function _error_msg($msg){
        header("Content-type: text/html; charset=utf-8"); 
        die($msg);
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
