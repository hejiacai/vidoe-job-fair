<?php

class controller_videohall extends components_cbasepage {
	/**
	 * 构造函数
	 */
	function __construct() {
        parent::__construct(false);
//		parent::__construct(true, 'c'); //暂未提供
        session_start();
        if(!$this->isLogin() || !$this->_userid ||$this->_usertype != 'c'){
            $this->redirect_url('/');
            exit();
        }
	}

    /**
     *@desc 视频面试二维码-快米
     *
     */
    public function pageRtcScanCode($inPath)
    {
        $pathdata     = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $job_id       = base_lib_BaseUtils::getStr($pathdata["job_id"], "int", 0);//原生职位编号
        $net_apply_id = base_lib_BaseUtils::getStr($pathdata["net_apply_id"], "int", 0);
        $person_id    = base_lib_BaseUtils::getStr($pathdata["person_id"], "int", 0); //原生求职者编号
        $company_id   = $this->_userid; //原生企业编号
        if (empty($person_id)) {
            echo $this->jsonMsg(false, "缺少求职者编号");
            exit;
        }
        $service_sqrcode_type = new base_service_blue_sqrcodetype();
        SQrcode::png(json_encode(array(
            'resume_id'    => 0,
            'job_id'       => $job_id,
            'person_id'    => $person_id,
            'net_apply_id' => $net_apply_id,
            'company_id'   => $this->_userid,
            "apiname"      => "scan_rtc_channel",
            "source"       => "kuaimi",
            "type"         => $service_sqrcode_type->scan_rtc_channel
        )));
    }

    /**
     * 视频面试大厅
     * @param type $inPath
     * @return type
     */
    public function pageVideoInterviewHall($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
//        $is_https = $this->isHttps();
//        if(!$is_https){
//            $this->redirect_url2("https:".base_lib_Constant::COMPANY_URL_NO_HTTP . '/videohall/VideoInterviewHall/',$pathdata);
//        }
        $sid = base_lib_BaseUtils::getStr($pathdata["sid"],"int",0);
        $this->GetHeadData($sid);
        $service_netfair_net = new base_service_netfair_net();
        $service_netfair_companynet = new base_service_netfair_companynet();

        list($status, $msg, $data) = $service_netfair_net->videoInterviewHall($sid, $this->_netfair_userid, $this->_source);

        if(!$status)
            return $this->redirect('/');

        $this->_aParams = $data;
        $this->checkToday($inPath);
        $this->_aParams['wait_apply_person_num'] = isset($data['apply_list']) && !empty($data['apply_list']) ? count($data['apply_list']) : 0;
        $this->_aParams['interview_one_num'] = $this->_aParams['wait_apply_person_num'] + (empty($data['apply_list_top_one']) ? 0 : 1);

        $this->_aParams['sid'] = $sid;
        $this->_aParams['source'] = $this->_source;

        if($this->_source == 2){
            $service_blue_company_appkey = new base_service_blue_company_appkey();
            $this->_aParams['has_login'] = $service_blue_company_appkey->hasLoginDays($this->_userid);
        }else{
            $service_company_app_appkey = new base_service_company_app_appkey();
            $this->_aParams['has_login'] = $service_company_app_appkey->hasLoginDays($this->_userid);
        }
        
        $net_company = $service_netfair_companynet->getCompanyEnterInfo($this->_netfair_userid, $sid, 'skip_num_used');
        $skip_num = $service_netfair_companynet->getMaxSkipNum() - $net_company['skip_num_used'];
        $this->_aParams['skip_num'] = $skip_num < 0 ? 0 : $skip_num;

        return $this->render("./videohall/videointerviewhall.html",$this->_aParams);
    }
    
    /**
     * 开始面试
     * @param type $inPath
     */
    public function pageStartInterview($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $apply_id = base_lib_BaseUtils::getStr($pathdata["id"],"int",0);

        $service_netfair_personapplynet = new base_service_netfair_personapplynet();
        $service_netfair_person         = new base_service_netfair_person();
        $service_netfair_resume         = new base_service_netfair_resume();

        $apply = [];
        if(!$apply_id){
            $data['sid']        = base_lib_BaseUtils::getStr($pathdata["sid"],"int",0);
            $data['job_id']     = base_lib_BaseUtils::getStr($pathdata["job_id"],"int",0);
            $data['person_id']  = base_lib_BaseUtils::getStr($pathdata["person_id"],"int",0);
            $base_resume_id     = base_lib_BaseUtils::getStr($pathdata["base_resume_id"],"int",0);
            if(!$data['sid'] || ! $data['job_id'] || !$data['person_id'] || empty($base_resume_id)){
                echo json_encode(['status'=>false, 'msg'=>'参数错误']);
                return;
            }
            
            $person  = $service_netfair_person->GetPersonDataByIds([$data['person_id']], 'person_id,user_name,mobile_phone');
            $person = $person[$data['person_id']];

            if($person['source'] == 1){
                $netfari_resume_info = $service_netfair_resume->getResumeId($base_resume_id,1);
                $netfari_resume_id = $netfari_resume_info['id'];
                $default_resume = $service_netfair_resume->GetResumeDataByIds($netfari_resume_id, 'resume_id');
                if(empty($default_resume)){
                    echo json_encode(['status'=>false, 'msg'=>'求职者未创建简历，无法发起面试']);
                    return;
                }
            }

            $apply = $service_netfair_personapplynet->getWaitInterviewApplyByCompanyInvite($data['sid'], $data['person_id'], $this->_netfair_userid, $data['job_id']);
            if(empty($apply)){
                $data['source']         = 2;
                $data['is_effect']      = 1;
                $data['create_time']    = date('Y-m-d H:i:s');
                $data['wait_time']      = $data['create_time'];
                $data['company_id']     = $this->_netfair_userid;
                $data['resume_id']      = $person['source'] == 1 ? $netfari_resume_id : 0;

                $apply_id = $service_netfair_personapplynet->insert($data);
                $apply = [
                    'sid'       => $data['sid'],
                    'job_id'    => $data['job_id'],
                    'resume_id' => $data['resume_id'],
                    'id'        => $apply_id,
                    'person_id' => $data['person_id']
                ];
            }else{
                $apply_id = $apply['id'];
            }
        }else{
            $apply = $service_netfair_personapplynet->getApplyById($apply_id, 'id,sid,person_id,company_id,job_id,resume_id');
            if(empty($apply)){
                echo json_encode(['status'=>false, 'msg'=>'发起面试失败']);
                return;
            }
            
            $person  = $service_netfair_person->GetPersonDataByIds([$apply['person_id']], 'person_id,user_name,mobile_phone');
            $person  = $person[$apply['person_id']];
        }
        
        list($status, $msg) = $service_netfair_personapplynet->startInterview($apply_id, false, null, base_lib_BaseUtils::getCookie('accountid'));
        if($status === false){
            echo json_encode(['status'=>false, 'msg'=>$msg]);
            return;
        }
        if($apply_id) {
            $send_data['person_id'] =  $apply['person_id'];
            $send_data['company_id'] =  $this->_netfair_userid;
        }else{
            $send_data['person_id'] =  $apply['person_id'];
            $send_data['company_id'] =  $this->_netfair_userid;
        }
        
        if($person['source'] == 1 && $this->_source == 1){
            $service_schoolnet_channel  = new base_service_schoolnet_channel();
            $account_id = base_lib_BaseUtils::getCookie('accountid');
            if($service_schoolnet_channel->checkPersonAndAccountIsOnInterview($apply['person_id'], $account_id)){
                echo json_encode(['status'=>false, 'msg'=>'与该求职者正在聊天中，请勿重复发起聊天', 'code'=>1]);
                return;
            }
        }

        $service_netfair_job = new base_service_netfair_job();
        $service_netfair_resume = new base_service_netfair_resume();
        $netfair_job = $service_netfair_job->getBaseJobId($apply['job_id']);
        $netfair_resume = $service_netfair_resume->getDataById($apply['resume_id']);

       //发送短信   求职者短信：王明，【企业简称】想和你提前进行远程视频面试，打开汇博app回复该企业吧。
//        $service_person = new base_service_person_person();
//        $person_info = $service_person->getPerson($send_data['person_id'],'person_id,user_name,mobile_phone');

       // if(!empty($person_info)){
//            $service_company = new base_service_company_company();
//            $company_info = $service_company->getCompany($send_data['company_id'],'','company_id,company_name,company_shortname');
            
            $service_netfair_company = new base_service_netfair_company();
            $company_info = $service_netfair_company->GetCompanyDataByIds([$apply['company_id']], 'company_name,company_shortname');
            $company_info = $company_info[$apply['company_id']];
            if($company_info){
               $company_name =  $company_info['company_shortname']?$company_info['company_shortname']:$company_info['company_name'];
                $content = "{$person['user_name']}，【{$company_name}】想邀请您远程视频面试，赶快登陆汇博app与企业沟通吧。";
               // var_dump($content);
                if($person['mobile_phone']){
//                    base_lib_SMS::send($person['mobile_phone'],$content);
                    base_lib_SMS::sendTpMsg("7bb45cc8f69c8fe8523603939176e6d5",$person['mobile_phone'],$content);
                }
                //我想和你就【岗位名称】一职进行远程视频面试，请问你现在有时间吗？
            }
            if($person['source'] == 1 && $this->_source == 1){
                //发送聊一聊消息
               // $service_job = new base_service_company_job_job();
                // $job = $service_job->getJob($apply["job_id"],'job_id,station');
                $service_qqcloud_msg = new base_service_interface_qqcloudsendmsg();
                // $qq_cloud_content    = "我想和你就【{$job['station']}】一职进行远程视频面试，请问你现在有时间吗？";
                $qq_cloud_content    = "您好，我这边想和您进行远程视频面试，您这边是否可以呢？";
                $from_id             = base_lib_BaseUtils::getCookie('accountid');
                $service_qqcloud_msg->addPreSendMsg("CIV", $from_id,$netfair_resume['base_person_id'], $qq_cloud_content, $netfair_resume["base_resume_id"], $netfair_job["base_job_id"]);
            }
        //}


        $return_data = [
            'job_id'        => $netfair_job['base_job_id'],
            'resume_id'     => $netfair_resume['base_resume_id'],
            'person_id'     => $netfair_resume['base_person_id'],
            'sid'           => $apply['sid'],
            'id'            => $apply['id']
        ];
        echo json_encode(['status'=>true, 'msg'=>'操作成功', 'data'=>$return_data]);
        return;
    }
    
    /**
     * 改版视频面试大厅开始面试
     * @param type $inPath
     */
    public function pageStartInterviewV1($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $apply_id = base_lib_BaseUtils::getStr($pathdata["id"],"int",0);
        $need_send_msg = base_lib_BaseUtils::getStr($pathdata["need_send_msg"],"int",0);
        
        if(!$apply_id){
            echo json_encode(['status'=>false, 'msg'=>'参数错误', 'code'=>0]);
            return;
        }
        
        $service_netfair_personapplynet             = new base_service_netfair_personapplynet();
        $service_schoolnet_channel                  = new base_service_schoolnet_channel();
        $service_blue_channel                       = new base_service_blue_channel();
        $service_netfair_person                     = new base_service_netfair_person();
        $service_netfair_company                    = new base_service_netfair_company();
        $service_netfair_job                        = new base_service_netfair_job();
        $service_netfair_resume                     = new base_service_netfair_resume();
        
        $apply = $service_netfair_personapplynet->getApplyById($apply_id, 'id,sid,person_id,company_id,job_id,resume_id');
        if(empty($apply)){
            echo json_encode(['status'=>false, 'msg'=>'发起面试失败', 'code'=>0]);
            return;
        }
        
        $person  = $service_netfair_person->GetPersonDataByIds([$apply['person_id']], 'person_id,user_name,mobile_phone');
        $person  = $person[$apply['person_id']];
        
        $company = $service_netfair_company->GetCompanyDataByIds([$apply['company_id']], 'company_name,company_shortname');
        $company = $company[$apply['company_id']];

        $account_id = base_lib_BaseUtils::getCookie('accountid');
        
        if($company['source'] == 1){
            if($service_schoolnet_channel->checkPersonIsOnInterview($person['person_id'], $account_id, 15, $person['source'])){
                echo json_encode(['status'=>false, 'msg'=>'求职者正在面试中！', 'code'=>1]);
                return;
            }
        }else if($company['source'] == 2){
            if($service_blue_channel->checkPersonIsOnInterview($person['person_id'], $company['company_id'], 15, $person['source'])){
                echo json_encode(['status'=>false, 'msg'=>'求职者正在面试中！', 'code'=>1]);
                return;
            }
        }
        
        $job = $service_netfair_job->GetJobDataByIds([$apply["job_id"]], 'job_id');
        $resume = $service_netfair_resume->getBaseResumeIds([$apply["resume_id"]], null, 'id,base_resume_id');
//        $resume = $service_netfair_resume->GetResumeDataByIds([$apply["resume_id"]], 'resume_id');

        $job = $job[$apply["job_id"]];
        $resume = $resume[0];
  
        if($need_send_msg){
            if(!empty($person) && !empty($company)){
                $company_name = $company['company_shortname'] ? $company['company_shortname'] : $company['company_name'];
                $msg = "{$person['user_name']}，【{$company_name}】HR当前向你发起了视频面试邀请，请尽快打开汇博app参与面试！！！";
                $qq_cloud_content = '我正在向你发起视频面试邀请，点击面试！';

                //如果10分钟内聊过，不再发送短信提醒（产品没说，加了测试会说是bug）
//                if(!$service_schoolnet_channel->checkPersonAndAccountIsOnInterview($apply['person_id'], $account_id))
//                base_lib_BaseUtils::IsMobile($person['mobile_phone']) && base_lib_SMS::send($person['mobile_phone'], $msg);
                base_lib_BaseUtils::IsMobile($person['mobile_phone']) && base_lib_SMS::sendTpMsg("7bb45cc8f69c8fe8523603939176e6d5",$person['mobile_phone'],$msg);

                if($person['source'] == 1 && $this->_source == 1){
                    $service_qqcloud_msg = new base_service_interface_qqcloudsendmsg();
                    $service_qqcloud_msg->addPreSendMsg("CIV", $account_id, $person['person_id'], $qq_cloud_content, $resume["base_resume_id"], $job["job_id"]);
                }
            }
        }
        //聊一聊页面那边需要传转换后的参数
        $apply['job_id'] = $job["job_id"];
        $apply['resume_id'] = $resume["base_resume_id"];
        
        echo json_encode(['status'=>true, 'msg'=>'操作成功', 'code'=>2, 'data'=>$apply]);
        return;
    }
    
    public function pageStartInterviewV2($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_id      = base_lib_BaseUtils::getStr($pathdata["resume_id"],"int",0);
        $job_id         = base_lib_BaseUtils::getStr($pathdata["job_id"],"int",0);
        $net_apply_id   = base_lib_BaseUtils::getStr($pathdata["net_apply_id"],"int",0);
        $sid            = base_lib_BaseUtils::getStr($pathdata["sid"],"int",0);
        
        $service_netfair_personapplynet = new base_service_netfair_personapplynet();
        $apply = $service_netfair_personapplynet->getApplyById($net_apply_id, 'id,sid,person_id,company_id,job_id,resume_id');
        $account_id = base_lib_BaseUtils::getCookie('accountid');
        
        $service_netfair_person = new base_service_netfair_person();
        $person  = $service_netfair_person->GetPersonDataByIds([$apply['person_id']], 'person_id,user_name,mobile_phone');
        $person  = $person[$apply['person_id']];
            
        if(!empty($apply) && $person['source'] == 1 && $this->_source == 1){
            $service_qqcloud_msg = new base_service_interface_qqcloudsendmsg();
            $service_netfair_job = new base_service_netfair_job();
            $service_netfair_resume = new base_service_netfair_resume();
            
            $job = $service_netfair_job->GetJobDataByIds([$apply["job_id"]], 'job_id');
            $resume = $service_netfair_resume->GetResumeDataByIds([$apply["resume_id"]], 'resume_id');
                    
            $job = $job[$apply["job_id"]];
            $resume = $resume[$apply["resume_id"]];
            
            $service_qqcloud_msg->addPreSendMsg("CIV", $account_id, $person['person_id'], "您好，我这边想和您进行远程视频面试，您这边是否可以呢？", $resume["resume_id"], $job["job_id"]);
        }
        
        return $this->redirect_url2("https:".base_lib_Constant::COMPANY_URL_NO_HTTP . '/chat/', $pathdata);
    }
    
    public function pageSetMaxNo($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        
        $apply_id = base_lib_BaseUtils::getStr($pathdata["id"],"int",0);
        $sid = base_lib_BaseUtils::getStr($pathdata["sid"],"int",0);
        
        if(!$apply_id || !$sid){
            echo json_encode(['status'=>false, 'msg'=>'操作失败']);
            return;
        }
        
        $service_netfair_personapplynet = new base_service_netfair_personapplynet();
        $apply = $service_netfair_personapplynet->getApplyById($apply_id, 'id,sid,person_id,company_id,job_id,resume_id');
        
        if(empty($apply)){
            echo json_encode(['status'=>false, 'msg'=>'未找到投递记录']);
            return;
        }
        
        $res = $service_netfair_personapplynet->updateApply(['id'=>$apply_id], ['order_no'=>$service_netfair_personapplynet->getMaxOrderNo($sid, $apply['company_id'])]);
        
        if($res === false){
            echo json_encode(['status'=>false, 'msg'=>'操作失败']);
            return;
        }
        
        echo json_encode(['status'=>true, 'msg'=>'操作成功']);
        return;
    }
    
    public function pageSkipInterview($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $apply_id = base_lib_BaseUtils::getStr($pathdata["id"],"int",0);
        $sid = base_lib_BaseUtils::getStr($pathdata["sid"],"int",0);
        $is_add_skip_num = base_lib_BaseUtils::getStr($pathdata["is_add_skip_num"],"bool",false);
        
        $service_netfair_companynet = new base_service_netfair_companynet();
        $service_netfair_personapplynet = new base_service_netfair_personapplynet();
        $add_skip_num = 1;
        if($is_add_skip_num){
            $net_company = $service_netfair_companynet->getCompanyEnterInfo($this->_netfair_userid, $sid, 'skip_num_used');
            if($net_company['skip_num_used'] >= $service_netfair_companynet->getMaxSkipNum()){
                echo json_encode(['status'=>false, 'msg'=>'该场招聘会跳过次数已使用完，操作失败']);
                return;
            }
            $add_skip_num += $net_company['skip_num_used'];
        }
        $result = $service_netfair_personapplynet->skipInterview($apply_id, $add_skip_num);
        if($result){
            echo json_encode(['status'=>true, 'msg'=>'操作成功']);
            return;
        }
        
        echo json_encode(['status'=>false, 'msg'=>'操作失败']);
        return;
    }
    
    public function pageInterviewTimeList($inPath){
        $pathdata               = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $sid                    = base_lib_BaseUtils::getStr($pathdata["sid"],"int",'');
        $from                   = base_lib_BaseUtils::getStr($pathdata["from"],"string",'');

        $list = $this->__getCompanyTime($sid);
        if(!$list && $from != 'del'){
            $service_netfair_interviewtimenet = new base_service_netfair_interviewtimenet();
            $bll_videohall = new base_bll_videohall();
            $interview_times = $service_netfair_interviewtimenet->getTimes($sid);
            
            if(!empty($interview_times)){
                $data = [
                            'sid'           => $sid,
                            'company_id'    => $this->_netfair_userid,
                            'creator'       => base_lib_BaseUtils::getCookie('accountid')
                        ];
                foreach ($interview_times as $v) {
                    $bll_videohall->autoCompanyInterviewTime($v['start_time'], $v['end_time'], $sid, $this->_netfair_userid, base_lib_BaseUtils::getCookie('accountid'));
                }
            }
            $list = $this->__getCompanyTime($sid);

        }

        $this->_aParams['list'] = $list;
        $this->_aParams['sid'] = $sid;
        return $this->render("./videohall/interviewtimelist.html",$this->_aParams);
    }
    
    /**
     * 删除企业面试时间
     * @param type $inPath
     * @return type
     */
    public function pageDelCompanyInterviewTime($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $id = base_lib_BaseUtils::getStr($pathdata["id"],"int",0);
        
        if(!$id){
            echo json_encode(['status'=>false, 'msg'=>'参数错误']);
            return;
        }
        
        $service_netfair_interviewnet = new base_service_netfair_interviewnet();
        if(!$service_netfair_interviewnet->delTime($id)){
            echo json_encode(['status'=>false, 'msg'=>'删除失败']);
            return;
        }
        
        echo json_encode(['status'=>true, 'msg'=>'删除成功']);
        return;
    }
    
    public function pageModCompanyInterviewTime($inPath){
        $pathdata           = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $id                 = base_lib_BaseUtils::getStr($pathdata["id"],"int",0);
        $sid                = base_lib_BaseUtils::getStr($pathdata["sid"],"int",0);
        
        $service_netfair_interviewnet = new base_service_netfair_interviewnet();
        $bll_videohall = new base_bll_videohall();
        if($id){
            $this->_aParams['interview_data'] = $service_netfair_interviewnet->selectOne(['id' => $id], 'id,start_time,end_time,time_type');
            $this->_aParams['interview_data']['date'] = date('Y-m-d', strtotime($this->_aParams['interview_data']['start_time']));
            $this->_aParams['interview_data']['start_time_str'] = date('H:i', strtotime($this->_aParams['interview_data']['start_time']));
            $this->_aParams['interview_data']['end_time_str'] = date('H:i', strtotime($this->_aParams['interview_data']['end_time']));
        }
        $this->_aParams['am_times'] = $service_netfair_interviewnet->getTimes(1);
        $this->_aParams['pm_times'] = $service_netfair_interviewnet->getTimes(2);
        $this->_aParams['id'] = $id;
        $this->_aParams['sid'] = $sid;
        $this->_aParams['title'] = $id ? '新增时间' : '编辑时间';
        list($this->_aParams['min_date'], $this->_aParams['max_date'], $this->_aParams['disable_dates']) = $bll_videohall->getInterviewDates($sid);
        return $this->render("./videohall/modinterviewtime.html",$this->_aParams);
    }
    /**
     * 新增修改企业面试时间
     * @param type $inPath
     * @return type
     */
    public function pageModCompanyInterviewTimeDo($inPath){
        $pathdata           = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $id                 = base_lib_BaseUtils::getStr($pathdata["id"],"int",0);
        $sid                = base_lib_BaseUtils::getStr($pathdata["sid"],"int",0);
        $start_time1        = base_lib_BaseUtils::getStr($pathdata["start_time1"],"string",'');
        $start_time2        = base_lib_BaseUtils::getStr($pathdata["start_time2"],"string",'');
        $end_time1          = base_lib_BaseUtils::getStr($pathdata["end_time1"],"string",'');
        $end_time2          = base_lib_BaseUtils::getStr($pathdata["end_time2"],"string",'');
        $interview_date     = base_lib_BaseUtils::getStr($pathdata["interview_date"],"string",'');
        
        if(!$sid){
            echo json_encode(['status'=>false, 'msg'=>'参数错误']);
            return;
        }
        
        if(!$interview_date){
            echo json_encode(['status'=>false, 'msg'=>'请选择面试日期']);
            return;
        }
        
        if(!$start_time1 && !$end_time1 && !$start_time2 && !$end_time2){
            echo json_encode(['status'=>false, 'msg'=>'请选择面试时间']);
            return;
        }
        
        if((($start_time1 || $end_time1) && (!$start_time1 || !$end_time1)) || ($start_time2 || $end_time2) && (!$start_time2 || !$end_time2)){
            echo json_encode(['status'=>false, 'msg'=>'请选择完整的开始，结束面试时间']);
            return;
        }
        
        if(($start_time1 && time() > strtotime($interview_date . ' ' . $start_time1 . ':00')) || ($start_time2 && time() > strtotime($interview_date . ' ' . $start_time2 . ':00'))){
            echo json_encode(['status'=>false, 'msg'=>'面试时间设置请大于当前时间']);
            return;
        }
        
//        $service_netfair_interviewtimenet = new base_service_netfair_interviewtimenet();
//        $interview_times = $service_netfair_interviewtimenet->getTimes($sid);//start_time,end_time
        $service_netfair_interviewnet = new base_service_netfair_interviewnet();
        $bll_videohall = new base_bll_videohall();
        $interview_times = $bll_videohall->getAllInterviewTimes($sid);
        
        $is_in_time = false;
        $interview_min_h = '';
        $interview_max_h = '';
        
        if(empty($interview_times)){
            echo json_encode(['status'=>false, 'msg'=>'招聘会未设置面试时间']);
            return;
        }

        $interview_min_h = '99:99'; 
        $interview_max_h = '00:00';
        foreach ($interview_times as $v) {
            if(date('Y-m-d', strtotime($v['start_time'])) >= $interview_date && date('Y-m-d', strtotime($v['end_time'])) <= $interview_date)
                $is_in_time = true;
            
            if(date('Y-m-d', strtotime($v['start_time']) == $interview_date) && $interview_min_h > date('H:i', strtotime($v['start_time'])))
                $interview_min_h = date('H:i', strtotime($v['start_time']));
            
            if(date('Y-m-d', strtotime($v['end_time']) == $interview_date) && $interview_max_h < date('H:i', strtotime($v['end_time'])))
                $interview_max_h = date('H:i', strtotime($v['end_time']));
        }

        if(!$is_in_time || $interview_min_h && ($start_time1 && $start_time1 < $interview_min_h || $start_time2 && $start_time2 < $interview_min_h) 
                || $interview_max_h && ($end_time1 && $end_time1 > $interview_max_h || $end_time2 && $end_time2 > $interview_max_h)){
            echo json_encode(['status'=>false, 'msg'=>"面试时间必须在{$interview_min_h}-{$interview_max_h}"]);
            return;
        }
        
        $account_id = base_lib_BaseUtils::getCookie('accountid');
        $res = true;
        if(!$id){//添加
            $data = [
                'sid'           => $sid,
                'company_id'    => $this->_netfair_userid,
                'creator'       => $account_id
            ];
            if($start_time1 && $end_time1){
                $data['start_time'] = $interview_date . ' ' . $start_time1 . ':00';
                $data['end_time']   = $interview_date . ' ' . $end_time1 . ':00';
                if(strtotime($data['start_time']) >= strtotime($data['end_time'])){
                    echo json_encode(['status'=>false, 'msg'=>'面试结束时间必须大于开始时间']);
                    return;
                }
                $data['time_type']  = 1;
                $id = $service_netfair_interviewnet->isExists($interview_date, 1, $sid, $this->_netfair_userid);
                if($id){
                    echo json_encode(['status'=>false, 'msg'=>'09：00~12:00已选择，请重新选择']);
                    return;
                }
                $res = $service_netfair_interviewnet->addTime($data);
                
//                if(!$id)
//                    $res = $service_netfair_interviewnet->addTime($data);
//                else
//                    $res = $service_netfair_interviewnet->updateTime($id, $data) !== false;
            }
            
            if($res && $start_time2 && $end_time2){
                $data['start_time'] = $interview_date . ' ' . $start_time2 . ':00';
                $data['end_time']   = $interview_date . ' ' . $end_time2 . ':00';
                if(strtotime($data['start_time']) >= strtotime($data['end_time'])){
                    echo json_encode(['status'=>false, 'msg'=>'面试结束时间必须大于开始时间']);
                    return;
                }
                $data['time_type']  = 2;
                $id = $service_netfair_interviewnet->isExists($interview_date, 2, $sid, $this->_netfair_userid);
                if($id){
                    echo json_encode(['status'=>false, 'msg'=>'13：30~17:00已选择，请重新选择']);
                    return;
                }
                $res = $service_netfair_interviewnet->addTime($data);
//                if(!$id)
//                    $res = $service_netfair_interviewnet->addTime($data);
//                else
//                    $res = $service_netfair_interviewnet->updateTime($id, $data) !== false;
            }
            
        }else{
            //修改
            $data = [
                    'start_time'    => $interview_date . ' ' . $start_time1 . ':00',
                    'end_time'      => $interview_date . ' ' . $end_time1 . ':00',
                ];
            if(strtotime($data['start_time']) >= strtotime($data['end_time'])){
                echo json_encode(['status'=>false, 'msg'=>'面试结束时间必须大于开始时间']);
                return;
            }
            
            if($start_time1 && $end_time1 && !$service_netfair_interviewnet->isExists($interview_date, date('H', strtotime($data['start_time'])) < 12 ? 1 : 2, $sid, $this->_netfair_userid, $id))
                $res = $service_netfair_interviewnet->updateTime($id, $data) !== false;
        }
        
        if($res){
            echo json_encode(['status'=>true, 'msg'=>'操作成功']);
            return;
        }

        echo json_encode(['status'=>false, 'msg'=>'操作失败']);
        return;
    }
    
    /**
     * 面试管理页面
     * @param type $inPath
     * @return type
     */
    public function pageInterviewList($inPath){
        $pathdata               = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $sid                    = base_lib_BaseUtils::getStr($pathdata["sid"],"int",'');
        $user_name              = base_lib_BaseUtils::getStr($pathdata["user_name"],"string",'');
        $interview_time_min     = base_lib_BaseUtils::getStr($pathdata["interview_time_min"],"datetime",'');
        $interview_time_max     = base_lib_BaseUtils::getStr($pathdata["interview_time_max"],"datetime",'');
        $interview_status       = base_lib_BaseUtils::getStr($pathdata["status"],"int", 0);
        $page_index             = base_lib_BaseUtils::getStr($pathdata["page"],"int",1);
        $page_size              = 10;//base_lib_Constant::PAGE_SIZE;

//        $this->_netfair_userid = 1;
//        $sid = 33;

        $service_netfair_personapplynet = new base_service_netfair_personapplynet();
        $service_netfair_net = new base_service_netfair_net();
        list($status, $msg, $data) = $service_netfair_net->videoInterviewTop($sid, $this->_netfair_userid, $this->_source);

        if(!$status){
            if($data['code'] == 401){
                $this->redirect_url2('/netfairlist/index');
            }else{
                return $this->render('../config/404.html');
            }

        }

        $this->_aParams = $data;
        $this->checkToday($inPath);

        $this->_aParams["sid"]                  = $sid;
        $this->_aParams["user_name"]            = $user_name;
        $this->_aParams["interview_time_min"]   = $interview_time_min;
        $this->_aParams["interview_time_max"]   = $interview_time_max;
        $this->_aParams["status"]               = $interview_status;


        $item = 'id,sid,company_id,job_id,person_id,resume_id,status,interview_time,source';
        $result = $service_netfair_personapplynet->getInterviewList($sid, $this->_netfair_userid, $user_name, $interview_status, $interview_time_min, $interview_time_max, $page_index, $page_size, $item);
        $list = $result->items;

        if($list){
            $person_ids = base_lib_BaseUtils::getProperty($list, 'person_id');
            $resume_ids = base_lib_BaseUtils::getProperty($list, 'resume_id');
            $job_ids    = base_lib_BaseUtils::getProperty($list, 'job_id');

            $service_netfair_person                     = new base_service_netfair_person();
            $service_netfair_job                        = new base_service_netfair_job();
            $service_netfair_resume                     = new base_service_netfair_resume();
            $service_person_resume_edu                  = new base_service_person_resume_edu();

            $person_list = $service_netfair_person->GetPersonDataByIds($person_ids,"person_id,user_name,mobile_phone");
            $resume_list        = $service_netfair_resume->getResumeList($resume_ids);
            $job_list           = $service_netfair_job->GetJobDataByIds($job_ids,"job_id,station");

            foreach ($list as $k => &$v) {
                $v['user_name']             = $person_list[$v['person_id']]['user_name'];
                $v['mobile_phone']          = $person_list[$v['person_id']]['mobile_phone'];
                $v['person_source']         = $person_list[$v['person_id']]['source'];

                $v['source_name']           = $v['source'] == 1 ? '学生申请' : '企业发起';
                $v['status_name']           = $this->getStatusName($v['status'], $v['interview_time'], $v['source']);
                $v['station']               = $job_list[$v['job_id']]['station'];
                $v['school']                = $resume_list[$v['resume_id']]['school'];
                if($resume_list[$v['resume_id']]['resume_source'] == 1){
                    $high_edu = $service_person_resume_edu->getHighestEdu($resume_list[$v['resume_id']]['base_resume_id'], 'major_desc');
                    $v['major_desc']            = empty($high_edu) ? "" : $high_edu['major_desc'];
                }
                $v['interview_time_str']    = !$v['status'] || $v['status'] == 5 || empty($v['interview_time']) ? '' : date('Y-m-d H:i', strtotime($v['interview_time']));
            }
        }
        $this->GetHeadData($sid);
        $this->_aParams["company_source"] = $this->_source;
        $this->_aParams['source'] = $this->_source;
        $this->_aParams["list"] = $list;
        $this->_aParams["pager"] = $this->pageBarFullPath($result->totalSize, $page_size, $page_index, $inPath);
        return $this->render("./videohall/interviewlist.html",$this->_aParams);
    }
    
    /**
     * 设置面试状态
     * @param type $inPath
     * @return type
     */
    public function pageSetInterviewStatus($inPath){
        $pathdata           = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $id                 = base_lib_BaseUtils::getStr($pathdata["id"],"int",0);
        $status             = base_lib_BaseUtils::getStr($pathdata["status"],"int",0);
        $person_id          = base_lib_BaseUtils::getStr($pathdata["person_id"],"int",0);
        $need_send_msg      = base_lib_BaseUtils::getStr($pathdata["need_send_msg"],"bool",false);
        
        if(!$id || !$status){
            echo json_encode(['status'=>false, 'msg'=>'参数错误']);
            return;
        }       
        $service_netfair_personapplynet = new base_service_netfair_personapplynet();        
        $msg = '操作失败';
		$res = $service_netfair_personapplynet->setStatus($id, $status, $person_id,base_lib_BaseUtils::getCookie('accountid'), true, $msg, $need_send_msg);
        if($res){
            echo json_encode(['status'=>true, 'msg'=>'操作成功']);
            return;
        }
        
        echo json_encode(['status'=>false, 'msg'=>$msg]);
        return;
    }
    
    /**
     * 求职者大厅
     * @param type $inPath
     */
    public function pageJobWanters($inPath){
        $pathdata               = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
       /* $is_https = $this->isHttps();
        if(!$is_https){
            $this->redirect_url2("https:".base_lib_Constant::COMPANY_URL_NO_HTTP . '/videohall/JobWanters/',$pathdata);
        }*/

        $sid                    = base_lib_BaseUtils::getStr($pathdata["sid"],"int",'');
      //  $user_name              = base_lib_BaseUtils::getStr($pathdata["user_name"],"string",'');
        $sex                    = base_lib_BaseUtils::getStr($pathdata["sex"],"int",'');
       // $major                  = base_lib_BaseUtils::getStr($pathdata["major"],"string",'');
        $key_word               = base_lib_BaseUtils::getStr($pathdata["key_word"],"string",'');
        $degree                 = base_lib_BaseUtils::getStr($pathdata["degree"],"string",'');
        $page_index             = base_lib_BaseUtils::getStr($pathdata["page"],"int",1);
        $page_size              = 21;//base_lib_Constant::PAGE_SIZE;


//        $this->_netfair_userid = 1;
//        $sid = 54;
//        $this->_source = 1;

        $service_netfair_jobnet                  = new base_service_netfair_jobnet();
        $service_netfair_job                     = new base_service_netfair_job();
        $service_netfair_personenternet          = new base_service_netfair_personenternet();
        $degree_common                           = new base_service_common_degree();
        $service_netfair_net                     = new base_service_netfair_net();

        list($status, $msg, $data) = $service_netfair_net->videoInterviewTop($sid, $this->_netfair_userid,$this->_source);
        if(!$status){
            if($data['code'] == 401){
                $this->redirect_url2('/netfairlist/index');
            }else{
                return $this->render('../config/404.html');
            }
        }


        $this->_aParams = $data;
        $this->checkToday($inPath);
        
        $net_jobs = $service_netfair_jobnet->getNetCompanyJob($this->_netfair_userid, $sid, 'job_id');
        $job_ids = base_lib_BaseUtils::getProperty($net_jobs, 'job_id');
        $netfair_job = $service_netfair_job->GetJobDataByIds($job_ids,"job_id,company_id,station");
        $this->_aParams["net_jobs"] = $netfair_job;
        $account_id = base_lib_BaseUtils::getCookie('accountid');
        $result = $service_netfair_personenternet->jobWanterListV2($sid, $degree, $sex, $key_word, $page_index, $page_size,$this->_userid,$account_id);

        $this->GetHeadData($sid);
        $this->_aParams["params"]               = $pathdata;
        $this->_aParams["sid"]                  = $sid;
        $this->_aParams["sex"]                  = $sex;
        $this->_aParams["degree"]               = $degree;
        $this->_aParams["all_degree"]           = $degree_common->getAll();
        $this->_aParams["list"]                 = $result->items;
        $this->_aParams["company_source"]       = $this->_source;
        $this->_aParams['source']               = $this->_source;
        
        $this->_aParams["pager"] = $this->pageBarFullPath($result->totalSize, $page_size, $page_index, $inPath);
        return $this->render("./videohall/jobwanters.html",$this->_aParams);
    }

    /**
     * 下拉获取更多数据
     * @param $inPath
     */
    public function pageJobWantersV2Json($inPath)
    {
        $this->checkToday($inPath);
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $sid = base_lib_BaseUtils::getStr($pathdata["sid"], "int", '');
        $sex = base_lib_BaseUtils::getStr($pathdata["sex"], "int", '');
        $key_word = base_lib_BaseUtils::getStr($pathdata["key_word"], "string", '');
        $degree = base_lib_BaseUtils::getStr($pathdata["degree"], "string", '');
        $page_index = base_lib_BaseUtils::getStr($pathdata["page"], "int", 1);
        $page_size = 21;
        $service_netfair_personenternet          = new base_service_netfair_personenternet();
        $account_id = base_lib_BaseUtils::getCookie('accountid');
        $result = $service_netfair_personenternet->jobWanterListV2($sid, $degree, $sex, $key_word, $page_index, $page_size,$this->_userid,$account_id);
        //$this->ajax_data_json('SUCCESS', '获取成功', ['list' => $result->items]);
        return $this->json(array_values($result->items));
    }

    /**
     * 招聘职位
     * @param type $inPath
     */
    public function pageJobs($inPath){
        $pathdata               = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $sid                    = base_lib_BaseUtils::getStr($pathdata["sid"],"int",'');
        $this->GetHeadData($sid);
        $service_netfair_jobnet = new base_service_netfair_jobnet();
        $service_netfair_net    = new base_service_netfair_net();
        list($status, $msg, $data) = $service_netfair_net->videoInterviewTop($sid, $this->_netfair_userid, $this->_source);
        if(!$status)
            return $this->render('../config/404.html');
        
        $this->_aParams = $data;
        $this->checkToday($inPath);
        
        $job_list = $service_netfair_jobnet->getJobs($this->_netfair_userid, $sid, $this->_source, 'id,sid,company_id,job_id');
        
        $this->_aParams["list"]  = $job_list;
        $this->_aParams["sid"]   = $sid;
        $this->_aParams['source'] = $this->_source;
        return $this->render("./videohall/jobs.html",$this->_aParams);
    }

    /**
     * 视频面试复试
     * @param type $inPath
     * @return type
     */
    public function pageVideoInterviewBySecond($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $sid = base_lib_BaseUtils::getStr($pathdata["sid"],"int",0);
        $type = base_lib_BaseUtils::getStr($pathdata["type"],"int",1);

        $this->GetHeadData($sid);
        $service_netfair_net = new base_service_netfair_net();
        list($status, $msg, $data) = $service_netfair_net->getInterviewBySecond($sid, $this->_netfair_userid, $this->_source);

        if(!$status)
            return $this->render('../config/404.html');

        $this->_aParams = $data;
        $this->checkToday($inPath);
        
        $this->_aParams['sid'] = $sid;
        $this->_aParams['source'] = $this->_source;
        //兼容代离
        if($this->_source == 2){
            $service_blue_company_appkey = new base_service_blue_company_appkey();
            $this->_aParams['has_login'] = $service_blue_company_appkey->hasLoginDays($this->_userid);
        }else{
            $service_company_app_appkey = new base_service_company_app_appkey();
            $this->_aParams['has_login'] = $service_company_app_appkey->hasLoginDays($this->_userid);
        }
        if($type == 1){
            return $this->render("./videohall/secondinterview.html",$this->_aParams);
        }else{
            return $this->render("./videohall/secondpass.html",$this->_aParams);
        }
    }

    
    /**
     * 发送offer
     * @param type $inPath
     * @return type
     */
    public function pageSendOffer($inPath){
        $pathdata   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $id        = base_lib_BaseUtils::getStr($pathdata["id"],"int",'');
        
        //快米企业暂无发送offer相关流程
        if($this->_source == 2){
            echo json_encode(['status'=>false, 'msg'=>'发送offer失败']);
            return;
        }
        
        $service_netfair_personapplynet = new base_service_netfair_personapplynet();
        list($status, $msg, $invite_id) = $service_netfair_personapplynet->sendOffer($id);
        $service_netfair_personapplynet = new base_service_netfair_personapplynet();
        list($status, $msg, $invite_id) = $service_netfair_personapplynet->sendOffer($id);
        if(!$status){
            echo json_encode(['status'=>false, 'msg'=>$msg]);
            return;
        }
        
        echo json_encode(['status'=>true, 'msg'=>$msg, 'invite_id'=>$invite_id]);
        return;
    }
    
    /**
     * 判断用户是否在线
     * @param type $inPath
     * @return type
     */
    public function pageCheckOnline($inPath){
        $pathdata   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $person_id  = base_lib_BaseUtils::getStr($pathdata["person_id"],"int",'');
        
        $service_sqloud = new Sqcloud("APP");
        $online_state = $service_sqloud->getOnLineState($person_id);
        $is_online = $online_state == "Offline" ? false: true;
        echo json_encode(['is_online'=>$is_online]);
        return;
    }
    
    public function checkToday($inPath){
       /* $is_https = $this->isHttps();
        if(!$is_https){
//            $this->redirect_url2("https:" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }*/

//        $pathdata   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
//        $sid        = base_lib_BaseUtils::getStr($pathdata["sid"],"int",'');
//
//        $service_netfair_net = new base_service_netfair_net();
//        $shuang_xuan = $service_netfair_net->getShuangXuanNetByID($sid, 'start_time,end_time');
//
//        if($shuang_xuan['end_time'] <= date('Y-m-d H:i:s'))
//            return;
//
//        $service_netfair_interviewnet  = new base_service_netfair_interviewnet();
////        $service_chat = new company_service_chat();
////        $browser = $service_chat->getbrowser();
//        $has_checked = base_lib_BaseUtils::getCookie("school_videohall_equipment_check_today_{$sid}") ? true : false;
//
//        if($has_checked)
//            return;
//        
//        list($status, $msg, $data) = $service_netfair_net->videoInterviewTop($sid, $this->_netfair_userid,$this->_source);
//        
//        if(!$status)
//            return $this->render('../config/404.html');
//        
//        $this->_aParams = $data;
//        $this->_aParams['sid'] = $sid;
//        $this->_aParams['list'] = $service_netfair_interviewnet->getCompanyTime($this->_netfair_userid, $sid, 'id,start_time,end_time,time_type', 'order by start_time asc');
////        $this->_aParams['browser_not_ok'] = strtolower($browser['browser']) != 'chrome' || $browser['version'] < 60;
//        $this->_aParams['browser_not_ok'] = false;
//        
//        $this->_aParams['par'] = '视频面试大厅';
//        if(isset($inPath[2])){
//            switch ($inPath[2]) {
//                case 'JobWanters':
//                    $this->_aParams['par'] = '求职者大厅';
//                    break;
//                case 'InterviewList':
//                    $this->_aParams['par'] = '面试结果';
//                    break;
//                case 'Jobs':
//                    $this->_aParams['par'] = '招聘职位';
//                    break;
//            }
//        }
//        
//        $this->_aParams['souece'] = $this->_source;
//
//        echo $this->render("./videohall/checktoday.html",$this->_aParams);
//        die;
//        $is_https = $this->isHttps();
//        if(!$is_https){
//            $this->redirect_url2("https:" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
//        }
        
        $pathdata   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $sid        = base_lib_BaseUtils::getStr($pathdata["sid"],"int",'');
        
        $service_netfair_interviewnet = new base_service_netfair_interviewnet();
        $service_netfair_companynet = new base_service_netfair_companynet();
        $bll_videohall = new base_bll_videohall();

        $this->_aParams['check_in_type'] = 0;
        $this->_aParams['has_read_guide_pic'] = false;

        if(!$sid)
            return;
       
        //新表，面试是否结束
        list($min_date, $max_date, $disable_dates) = $bll_videohall->getInterviewDates($sid);
        if($max_date && date('Y-m-d') > $max_date)
            return;

        $company_net = $service_netfair_companynet->getCompanyEnterInfo($this->_netfair_userid, $sid, 'chat_type,is_agree_deal,has_read_guide_pic');
        $this->_aParams['has_read_guide_pic'] = !$company_net['has_read_guide_pic'];
        
        $this->_aParams['chat_type'] = $company_net['chat_type'];
        $this->_aParams['time_list'] = $this->__getCompanyTime($sid);
        if(!$company_net['chat_type']){
            $this->_aParams['check_in_type'] = 1;
            return;
        }
        
        $interview_times = $service_netfair_interviewnet->getCompanyTime($this->_netfair_userid, $sid, 'id');
        if(empty($interview_times)){
            $this->_aParams['check_in_type'] = 1;
            return;
        }
        
        if(!$company_net['is_agree_deal']){
            $this->_aParams['check_in_type'] = 2;
            return;
        }
     
        return;
    }

    /**
     * 判断用户活动场次中是否有申请
     * @param $inPath
     */
    public function pageHaveApply($inPath)
    {
        $params   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $person_id        = base_lib_BaseUtils::getStr($params["person_id"],"int",0);
        $sid        = base_lib_BaseUtils::getStr($params["sid"],"int",0);
        if(!$person_id){
            return $this->ajax_data_json(ERROR,'用户不存在');
        }
        if(!$sid){
            return $this->ajax_data_json(ERROR,'活动场次不存在');
        }
        $service_schoolnet_shuangxuanpersonapply = new base_service_schoolnet_shuangxuanpersonapply();
        $apply = $service_schoolnet_shuangxuanpersonapply->getPersonApplyOne($sid,$person_id,'id,job_id,resume_id');
        if(empty($apply)){
            return $this->ajax_data_json(ERROR,'无申请');
        }else{
            if($apply['job_id']&&$apply['resume_id']){
                return $this->ajax_data_json(SUCCESS,'有申请',$apply);
            }else{
                return $this->ajax_data_json(ERROR,'无申请',$apply);
            }
        }
    }

    public function pageGetLinkWay($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        
        $resume_id              = base_lib_BaseUtils::getStr($pathdata["resume_id"], "int", '');
        $person_id              = base_lib_BaseUtils::getStr($pathdata["person_id"], "int", '');
        $sid                    = base_lib_BaseUtils::getStr($pathdata["sid"], "int", '');
        $has_wait_deal_apply    = base_lib_BaseUtils::getStr($pathdata["has_wait_deal_apply"], "int", 0);
        $need_download          = base_lib_BaseUtils::getStr($pathdata["need_download"], "bool", true);
        
        $service_netfair_interviewnet = new base_service_netfair_interviewnet();
        $is_in_time = $service_netfair_interviewnet->isInTime($this->_netfair_userid, $sid);

        $msg = $need_download ? '请处理完待面试求职者后，才能查看联系方式' : '请处理完待面试求职者后，才能立即沟通';
        if($is_in_time && $has_wait_deal_apply){
            echo json_encode(['status' => false, 'msg' => $msg]);
            return;
        }
        
        if($need_download){
            $service_netfair_resumedownload = new base_service_netfair_resumedownload();
            $service_netfair_resumedownload->addDownload([
                'company_id' => $this->_netfair_userid,
                'person_id' => $person_id,
                'resume_id' => $resume_id,
                'down_time' => date('Y-m-d H:i:s'),
                'ip' => base_lib_BaseUtils::getIp(0),
                'get_type' => 2
            ]);
        }
        echo json_encode(['status' => true]);
        return;
    }

    public function pageSaveAction($inPath){
        $pathdata   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        
        $sid                  = base_lib_BaseUtils::getStr($pathdata["sid"],"int",'');
        $chat_type            = base_lib_BaseUtils::getStr($pathdata["chat_type"],"int",'');
        $is_agree_deal        = base_lib_BaseUtils::getStr($pathdata["is_agree_deal"],"int",'');
        $has_read_guide_pic   = base_lib_BaseUtils::getStr($pathdata["has_read_guide_pic"],"int",'');
        
        if(!$sid){
            echo json_encode(['status'=>false, 'msg'=>'参数错误']);
            return;
        }
        
        $item = [];
        if(isset($pathdata["chat_type"])){
            if(!$chat_type){
                echo json_encode(['status'=>false, 'msg'=>'请选择面试方式']);
                return;
            }
            
            $service_netfair_interviewnet = new base_service_netfair_interviewnet();
            $interview_times = $service_netfair_interviewnet->getCompanyTime($this->_netfair_userid, $sid, 'id');
            if(empty($interview_times)){
                echo json_encode(['status'=>false, 'msg'=>'请设置面试时间']);
                return;
            }
            
            $item['chat_type'] = $chat_type;
        }

        if(isset($pathdata["is_agree_deal"])){
            if(!$is_agree_deal){
                echo json_encode(['status'=>false, 'msg'=>'请阅读并勾选同意协议']);
                return;
            }
            $item['is_agree_deal'] = $is_agree_deal;
        }
        
        if(isset($pathdata["has_read_guide_pic"])){
            if(!$has_read_guide_pic){
                echo json_encode(['status'=>false, 'msg'=>'参数错误']);
                return;
            }
            $item['has_read_guide_pic'] = $has_read_guide_pic;
        }
        
        $service_netfair_companynet = new base_service_netfair_companynet();
        $res = $service_netfair_companynet->updateCompany($sid, $this->_netfair_userid, $item);
        
        if($res === false){
            echo json_encode(['status'=>false, 'msg'=>'操作失败']);
            return;
        }
        
        echo json_encode(['status'=>true, 'msg'=>'操作成功']);
        return;
    }
    
    private function getStatusName($status, $interview_time, $source){
        $status_arr = [
            '未面试',
            '待反馈',
            '初面通过',
            '不合适',
            '待定',
            '跳过',
            '录用'
        ];
        if($source == 1 && date('Y-m-d', strtotime($interview_time)) < date('Y-m-d') && !$status)
            return '已过期';
        if(isset($status_arr[$status]))
            return $status_arr[$status];
        
        return '';
    }
    
    private function __getCompanyTime($sid){
        $service_netfair_interviewnet  = new base_service_netfair_interviewnet();
        $list = $service_netfair_interviewnet->getCompanyTime($this->_netfair_userid, $sid, 'id,start_time,end_time,time_type', 'order by start_time asc');
        $now_time = time();
        if($list){
            foreach ($list as $k => $v) {
                $list[$k]['time_type_str']  = $v['time_type'] == 1 ? '上午' : '下午';
                $list[$k]['date']           = date('Y-m-d', strtotime($v['start_time']));
                $list[$k]['time_str']       = date('H:i', strtotime($v['start_time'])) . '至' . date('H:i', strtotime($v['end_time']));
                $list[$k]['status_str']     = strtotime($v['end_time']) < $now_time ? '已结束' : (strtotime($v['start_time']) < $now_time ? '进行中' : '未开始');
            }
        }
        return $list;
    }
    
    public function pageQuestions($inPath){
        $pathdata   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        
        $sid = base_lib_BaseUtils::getStr($pathdata["sid"],"int",0);
        $this->GetHeadData($sid);

        return $this->render("./videohall/questions.html",$this->_aParams);
    }
}

?>