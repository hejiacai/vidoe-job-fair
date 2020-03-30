<?php

class controller_videohall extends components_cbasepage {
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
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
        $this->checkToday($inPath);
        $sid = base_lib_BaseUtils::getStr($pathdata["sid"],"int",0);
        
        $service_schoolnet_shuangxuannet = new base_service_schoolnet_shuangxuannet();
        list($status, $msg, $data) = $service_schoolnet_shuangxuannet->videoInterviewHall($sid, $this->_userid);

        if(!$status)
            return $this->render('../config/404.html');

        $this->_aParams = $data;
        $this->_aParams['wait_apply_person_num'] = isset($data['apply_list']) && !empty($data['apply_list']) ? count($data['apply_list']) : 0;
        $this->_aParams['wait_apply_person_num_all'] = $this->_aParams['wait_apply_person_num'] + (empty($data['apply_list_top_one']) ? 0 : 1);
        $this->_aParams['sid'] = $sid;
        
        $service_schoolnet_shuangxuancompanynet = new base_service_schoolnet_shuangxuancompanynet();
        $net_company = $service_schoolnet_shuangxuancompanynet->getCompanyEnterInfo($this->_userid, $sid, 'skip_num_used');
        $skip_num = $service_schoolnet_shuangxuancompanynet->getMaxSkipNum() - $net_company['skip_num_used'];
        $this->_aParams['skip_num'] = $skip_num < 0 ? 0 : $skip_num;

        return $this->render("./schoolnet/videointerviewhall.html",$this->_aParams);
    }
    
    /**
     * 开始面试
     * @param type $inPath
     */
    public function pageStartInterview($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $apply_id = base_lib_BaseUtils::getStr($pathdata["id"],"int",0);
        
        $service_schoolnet_shuangxuanpersonapply = new base_service_schoolnet_shuangxuanpersonapply();
        $service_person_resume = new base_service_person_resume_resume();
        $apply = [];
        if(!$apply_id){
            $data['sid']        = base_lib_BaseUtils::getStr($pathdata["sid"],"int",0);
            $data['job_id']     = base_lib_BaseUtils::getStr($pathdata["job_id"],"int",0);
            $data['person_id']  = base_lib_BaseUtils::getStr($pathdata["person_id"],"int",0);
            if(!$data['sid'] || ! $data['job_id'] || !$data['person_id']){
                echo json_encode(['status'=>false, 'msg'=>'参数错误']);
                return;
            }
            
            $default_resume = $service_person_resume->getDefaultResume($data['person_id'], 'resume_id');
            if(empty($default_resume)){
                echo json_encode(['status'=>false, 'msg'=>'求职者未创建简历，无法发起面试']);
                return;
            }
            
            $apply = $service_schoolnet_shuangxuanpersonapply->getWaitInterviewApplyByCompanyInvite($data['sid'], $data['person_id'], $this->_userid, $data['job_id']);
            if(empty($apply)){
                $data['source']         = 2;
                $data['is_effect']      = 1;
                $data['create_time']    = date('Y-m-d H:i:s');
                $data['wait_time']      = $data['create_time'];
                $data['company_id']     = $this->_userid;
                $data['resume_id']      = $default_resume['resume_id'];
                $apply_id = $service_schoolnet_shuangxuanpersonapply->insert($data);
                $apply = [
                    'sid'       => $data['sid'],
                    'job_id'    => $data['job_id'],
                    'resume_id' => $data['resume_id'],
                    'id'        => $apply_id,
                    'person_id' =>$data['person_id']
                ];
            }else{
                $apply_id = $apply['id'];
            }
        }else{
            $apply = $service_schoolnet_shuangxuanpersonapply->getApplyById($apply_id, 'id,sid,person_id,company_id,job_id,resume_id');
            if(empty($apply)){
                echo json_encode(['status'=>false, 'msg'=>'发起面试失败']);
                return;
            }
        }
        
        list($status, $msg) = $service_schoolnet_shuangxuanpersonapply->startInterview($apply_id, false, null, base_lib_BaseUtils::getCookie('accountid'));
        if($status === false){
            echo json_encode(['status'=>false, 'msg'=>$msg]);
            return;
        }
        if($apply_id) {
            $send_data['person_id'] =  $apply['person_id'];
            $send_data['company_id'] =  $this->_userid;
        }else{
            $send_data['person_id'] =  $apply['person_id'];
            $send_data['company_id'] =  $this->_userid;
        }
        $service_schoolnet_channel  = new base_service_schoolnet_channel();
        $account_id = base_lib_BaseUtils::getCookie('accountid');
        if($service_schoolnet_channel->checkPersonAndAccountIsOnInterview($apply['person_id'], $account_id)){
            echo json_encode(['status'=>false, 'msg'=>'与该求职者正在聊天中，请勿重复发起聊天', 'code'=>1]);
            return;
        }
       //发送短信   求职者短信：王明，【企业简称】想和你提前进行远程视频面试，打开汇博app回复该企业吧。
        $service_person = new base_service_person_person();
        $person_info = $service_person->getPerson($send_data['person_id'],'person_id,user_name,mobile_phone');

       // if(!empty($person_info)){
            $service_company = new base_service_company_company();
            $company_info = $service_company->getCompany($send_data['company_id'],'','company_id,company_name,company_shortname');
            if($company_info){
               $company_name =  $company_info['company_shortname']?$company_info['company_shortname']:$company_info['company_name'];
                $content = "{$person_info['user_name']}，【{$company_name}】想邀请您远程视频面试，赶快登陆汇博app与企业沟通吧。";
               // var_dump($content);
                if($person_info['mobile_phone']){
                    base_lib_SMS::send($person_info['mobile_phone'],$content);
                }
                //我想和你就【岗位名称】一职进行远程视频面试，请问你现在有时间吗？
            }
            //发送聊一聊消息
           // $service_job = new base_service_company_job_job();
            // $job = $service_job->getJob($apply["job_id"],'job_id,station');
            $service_qqcloud_msg = new base_service_interface_qqcloudsendmsg();
            // $qq_cloud_content    = "我想和你就【{$job['station']}】一职进行远程视频面试，请问你现在有时间吗？";
            $qq_cloud_content    = "您好，我这边想和您进行远程视频面试，您这边是否可以呢？";
            $from_id             = base_lib_BaseUtils::getCookie('accountid');
            $service_qqcloud_msg->addPreSendMsg("CIV", $from_id,$send_data['person_id'], $qq_cloud_content, $apply["resume_id"], $apply["job_id"]);
        //}
        echo json_encode(['status'=>true, 'msg'=>'操作成功', 'data'=>$apply]);
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
        
        $service_schoolnet_shuangxuanpersonapply    = new base_service_schoolnet_shuangxuanpersonapply();
        $service_schoolnet_channel                  = new base_service_schoolnet_channel();
        $service_person                             = new base_service_person_person();
        $service_company                            = new base_service_company_company();
        
        $apply = $service_schoolnet_shuangxuanpersonapply->getApplyById($apply_id, 'id,sid,person_id,company_id,job_id,resume_id');
        if(empty($apply)){
            echo json_encode(['status'=>false, 'msg'=>'发起面试失败', 'code'=>0]);
            return;
        }
        
        $account_id = base_lib_BaseUtils::getCookie('accountid');
        if($service_schoolnet_channel->checkPersonIsOnInterview($apply['person_id'], $account_id)){
            echo json_encode(['status'=>false, 'msg'=>'求职者正在面试中！', 'code'=>1]);
            return;
        }
        
        if($need_send_msg){
            $person = $service_person->getPerson($apply['person_id'], 'user_name,mobile_phone');
            $company = $service_company->getCompany($apply['company_id'], null, 'company_name,company_shortname');
            
            if(!empty($person) && !empty($company)){
                $company_name = $company['company_shortname'] ? $company['company_shortname'] : $company['company_name'];
                $msg = "{$person['user_name']}，【{$company_name}】HR当前向你发起了视频面试邀请，请尽快打开汇博app参与面试！！！";
                $qq_cloud_content = '我正在向你发起视频面试邀请，点击面试！';

                //如果10分钟内聊过，不再发送短信提醒（产品没说，加了测试会说是bug）
//                if(!$service_schoolnet_channel->checkPersonAndAccountIsOnInterview($apply['person_id'], $account_id))
                base_lib_BaseUtils::IsMobile($person['mobile_phone']) && base_lib_SMS::send($person['mobile_phone'], $msg);
                
                $service_qqcloud_msg = new base_service_interface_qqcloudsendmsg();
                $service_qqcloud_msg->addPreSendMsg("CIV", $account_id, $apply['person_id'], $qq_cloud_content, $apply["resume_id"], $apply["job_id"]);
            }
        }
        echo json_encode(['status'=>true, 'msg'=>'操作成功', 'code'=>2, 'data'=>$apply]);
        return;
    }
    
    public function pageStartInterviewV2($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_id      = base_lib_BaseUtils::getStr($pathdata["resume_id"],"int",0);
        $job_id         = base_lib_BaseUtils::getStr($pathdata["job_id"],"int",0);
        $net_apply_id   = base_lib_BaseUtils::getStr($pathdata["net_apply_id"],"int",0);
        $sid            = base_lib_BaseUtils::getStr($pathdata["sid"],"int",0);
        
        $service_schoolnet_shuangxuanpersonapply    = new base_service_schoolnet_shuangxuanpersonapply();
        $apply = $service_schoolnet_shuangxuanpersonapply->getApplyById($net_apply_id, 'id,sid,person_id,company_id,job_id,resume_id');
        $account_id = base_lib_BaseUtils::getCookie('accountid');
        
        if(!empty($apply)){
            $service_qqcloud_msg = new base_service_interface_qqcloudsendmsg();
            $service_qqcloud_msg->addPreSendMsg("CIV", $account_id, $apply['person_id'], "您好，我这边想和您进行远程视频面试，您这边是否可以呢？", $apply["resume_id"], $apply["job_id"]);
        }
        
        return $this->redirect_url2("https:".base_lib_Constant::COMPANY_URL_NO_HTTP . '/chat/', $pathdata);
    }
    
    public function pageSkipInterview($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $apply_id = base_lib_BaseUtils::getStr($pathdata["id"],"int",0);
        $sid = base_lib_BaseUtils::getStr($pathdata["sid"],"int",0);
        $is_add_skip_num = base_lib_BaseUtils::getStr($pathdata["is_add_skip_num"],"bool",false);
        
        $service_schoolnet_shuangxuancompanynet = new base_service_schoolnet_shuangxuancompanynet();
        $service_schoolnet_shuangxuanpersonapply = new base_service_schoolnet_shuangxuanpersonapply();
        $add_skip_num = 1;
        if($is_add_skip_num){
            $net_company = $service_schoolnet_shuangxuancompanynet->getCompanyEnterInfo($this->_userid, $sid, 'skip_num_used');
            if($net_company['skip_num_used'] >= $service_schoolnet_shuangxuancompanynet->getMaxSkipNum()){
                echo json_encode(['status'=>false, 'msg'=>'该场招聘会跳过次数已使用完，操作失败']);
                return;
            }
            $add_skip_num += $net_company['skip_num_used'];
        }
        $result = $service_schoolnet_shuangxuanpersonapply->skipInterview($apply_id, $add_skip_num);
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
        
        $service_schoolnet_shuangxuannet = new base_service_schoolnet_shuangxuannet();
        $list = $this->__getCompanyTime($sid);
        if(!$list && $from != 'del'){
            $shuang_xuan = $service_schoolnet_shuangxuannet->getShuangXuanNetByID($sid, 'start_time,end_time');

            $now_time = time();
            if($now_time < strtotime($shuang_xuan['end_time']) && date('Y-m-d', strtotime($shuang_xuan['start_time'])) != date('Y-m-d', strtotime($shuang_xuan['end_time']))){
                $begin_time = $now_time >= strtotime($shuang_xuan['start_time']) ? date('Y-m-d H:i:s', $now_time) : $shuang_xuan['start_time'];
                $service_schoolnet_shuangxuaninterview  = new base_service_schoolnet_shuangxuaninterview();
                $account_id = base_lib_BaseUtils::getCookie('accountid');
                
                $data = [
                            'sid'           => $sid,
                            'company_id'    => $this->_userid,
                            'creator'       => $account_id
                        ];
                while(date('Y-m-d', strtotime($begin_time)) <= date('Y-m-d', strtotime($shuang_xuan['end_time']))){
                    if(date('Y-m-d', strtotime($begin_time)) == date('Y-m-d', strtotime($shuang_xuan['start_time']))){//开始当天
                        if(date('H', strtotime($shuang_xuan['start_time'])) < 12){
                            $data['start_time'] = $shuang_xuan['start_time'];
                            $data['end_time']   = date('Y-m-d', strtotime($begin_time)) . ' 12:00:00';
                            $service_schoolnet_shuangxuaninterview->addTime($data);
                            
                            $data['start_time'] = date('Y-m-d', strtotime($begin_time)) . ' 14:00:00';
                            $data['end_time']   = date('Y-m-d', strtotime($begin_time)) . ' 17:00:00';
                            $service_schoolnet_shuangxuaninterview->addTime($data);
                        }else if(date('H', strtotime($shuang_xuan['start_time'])) < 17){
                            $data['start_time'] = $shuang_xuan['start_time'];
                            $data['end_time']   = date('Y-m-d', strtotime($begin_time)) . ' 17:00:00';
                            $service_schoolnet_shuangxuaninterview->addTime($data);
                        }
                        
                        
                    }else if(date('Y-m-d', strtotime($begin_time)) == date('Y-m-d', strtotime($shuang_xuan['end_time']))){//结束当天
                        if(date('H', strtotime($shuang_xuan['end_time'])) <= 12){
                            $data['start_time'] = date('Y-m-d', strtotime($begin_time)) . ' 09:00:00';
                            $data['end_time']   = $shuang_xuan['end_time'];
                            $service_schoolnet_shuangxuaninterview->addTime($data);
                        }else{
                            $data['start_time'] = date('Y-m-d', strtotime($begin_time)) . ' 09:00:00';
                            $data['end_time']   = date('Y-m-d', strtotime($begin_time)) . ' 12:00:00';
                            $service_schoolnet_shuangxuaninterview->addTime($data);
                            if(date('H', strtotime($shuang_xuan['end_time'])) > 14){
                                $data['start_time'] = date('Y-m-d', strtotime($begin_time)) . ' 14:00:00';
                                $data['end_time']   = date('H', strtotime($shuang_xuan['end_time'])) >= 17 ? date('Y-m-d', strtotime($begin_time)) . ' 17:00:00' : $shuang_xuan['end_time'];
                                $service_schoolnet_shuangxuaninterview->addTime($data);
                            }
                        }
                    }else{
                        $data['start_time'] = date('Y-m-d', strtotime($begin_time)) . ' 09:00:00';
                        $data['end_time']   = date('Y-m-d', strtotime($begin_time)) . ' 12:00:00';
                        $service_schoolnet_shuangxuaninterview->addTime($data);

                        $data['start_time'] = date('Y-m-d', strtotime($begin_time)) . ' 14:00:00';
                        $data['end_time']   = date('Y-m-d', strtotime($begin_time)) . ' 17:00:00';
                        $service_schoolnet_shuangxuaninterview->addTime($data);
                    }

                    $begin_time = date('Y-m-d', strtotime('+1 day', strtotime($begin_time)));
                }
            }
            $list = $this->__getCompanyTime($sid);

        }
        $this->_aParams['list'] = $list;
        $this->_aParams['sid'] = $sid;
        return $this->render("./schoolnet/interviewtimelist.html",$this->_aParams);
    }
    
    private function __getCompanyTime($sid){
        $service_schoolnet_shuangxuaninterview  = new base_service_schoolnet_shuangxuaninterview();
        $list = $service_schoolnet_shuangxuaninterview->getCompanyTime($this->_userid, $sid, 'id,start_time,end_time,time_type', 'order by start_time asc');
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
        
        $service_schoolnet_shuangxuaninterview = new base_service_schoolnet_shuangxuaninterview();
        if(!$service_schoolnet_shuangxuaninterview->delTime($id)){
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
        
        $service_schoolnet_shuangxuaninterview = new base_service_schoolnet_shuangxuaninterview();
        if($id){
            $this->_aParams['interview_data'] = $service_schoolnet_shuangxuaninterview->selectOne(['id' => $id], 'id,start_time,end_time,time_type');
            $this->_aParams['interview_data']['date'] = date('Y-m-d', strtotime($this->_aParams['interview_data']['start_time']));
            $this->_aParams['interview_data']['start_time_str'] = date('H:i', strtotime($this->_aParams['interview_data']['start_time']));
            $this->_aParams['interview_data']['end_time_str'] = date('H:i', strtotime($this->_aParams['interview_data']['end_time']));
        }
        $this->_aParams['am_times'] = $service_schoolnet_shuangxuaninterview->getTimes(1);
        $this->_aParams['pm_times'] = $service_schoolnet_shuangxuaninterview->getTimes(2);
        $this->_aParams['id'] = $id;
        $this->_aParams['sid'] = $sid;
        return $this->render("./schoolnet/modinterviewtime.html",$this->_aParams);
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
        
        $service_schoolnet_shuangxuaninterview  = new base_service_schoolnet_shuangxuaninterview();
        $service_schoolnet_shuangxuannet        = new base_service_schoolnet_shuangxuannet();
        
        $shuang_xuan = $service_schoolnet_shuangxuannet->getShuangXuanNetByID($sid, 'start_time,end_time');
        if(empty($shuang_xuan)){
            echo json_encode(['status'=>false, 'msg'=>'未找到该场招聘会']);
            return;
        }
        
        if(strtotime($shuang_xuan['end_time']) < time()){
            echo json_encode(['status'=>false, 'msg'=>'招聘会已结束']);
            return;
        }
        
        if(($end_time1 && strtotime($shuang_xuan['end_time']) < strtotime($interview_date . ' ' . $end_time1 . ':00')) || ($end_time2 && strtotime($shuang_xuan['end_time']) < strtotime($interview_date . ' ' . $end_time2 . ':00'))
            || ($start_time1 && strtotime($shuang_xuan['start_time']) > strtotime($interview_date . ' ' . $start_time1 . ':00')) || ($start_time2 && strtotime($shuang_xuan['start_time']) > strtotime($interview_date . ' ' . $start_time2 . ':00'))){
            echo json_encode(['status'=>false, 'msg'=>'面试时间必须在招聘会时间内']);
            return;
        }
        
        if(($start_time1 && time() > strtotime($interview_date . ' ' . $start_time1 . ':00')) || ($start_time2 && time() > strtotime($interview_date . ' ' . $start_time2 . ':00'))){
            echo json_encode(['status'=>false, 'msg'=>'面试时间设置请大于当前时间']);
            return;
        }
        $account_id = base_lib_BaseUtils::getCookie('accountid');
        $res = true;
        if(!$id){//添加
            $data = [
                'sid'           => $sid,
                'company_id'    => $this->_userid,
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
                $id = $service_schoolnet_shuangxuaninterview->isExists($interview_date, 1, $sid, $this->_userid);
                if(!$id)
                    $res = $service_schoolnet_shuangxuaninterview->addTime($data);
                else
                    $res = $service_schoolnet_shuangxuaninterview->updateTime($id, $data) !== false;
            }
            
            if($res && $start_time2 && $end_time2){
                $data['start_time'] = $interview_date . ' ' . $start_time2 . ':00';
                $data['end_time']   = $interview_date . ' ' . $end_time2 . ':00';
                if(strtotime($data['start_time']) >= strtotime($data['end_time'])){
                    echo json_encode(['status'=>false, 'msg'=>'面试结束时间必须大于开始时间']);
                    return;
                }
                $data['time_type']  = 2;
                $id = $service_schoolnet_shuangxuaninterview->isExists($interview_date, 2, $sid, $this->_userid);
                if(!$id)
                    $res = $service_schoolnet_shuangxuaninterview->addTime($data);
                else
                    $res = $service_schoolnet_shuangxuaninterview->updateTime($id, $data) !== false;
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
            
            if($start_time1 && $end_time1 && !$service_schoolnet_shuangxuaninterview->isExists($interview_date, date('H', strtotime($data['start_time'])) < 12 ? 1 : 2, $sid, $this->_userid, $id))
                $res = $service_schoolnet_shuangxuaninterview->updateTime($id, $data) !== false;
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
        $this->checkToday($inPath);
        $pathdata               = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $sid                    = base_lib_BaseUtils::getStr($pathdata["sid"],"int",'');
        $user_name              = base_lib_BaseUtils::getStr($pathdata["user_name"],"string",'');
        $interview_time_min     = base_lib_BaseUtils::getStr($pathdata["interview_time_min"],"datetime",'');
        $interview_time_max     = base_lib_BaseUtils::getStr($pathdata["interview_time_max"],"datetime",'');
        $interview_status       = base_lib_BaseUtils::getStr($pathdata["status"],"int", 0);
        $page_index             = base_lib_BaseUtils::getStr($pathdata["page"],"int",1);
        $page_size              = 10;//base_lib_Constant::PAGE_SIZE;
        
        $service_schoolnet_shuangxuanpersonapply = new base_service_schoolnet_shuangxuanpersonapply();
        $service_schoolnet_shuangxuannet         = new base_service_schoolnet_shuangxuannet();
        list($status, $msg, $data) = $service_schoolnet_shuangxuannet->videoInterviewTop($sid, $this->_userid);
        if(!$status)
            return $this->render('../config/404.html');
        $this->_aParams = $data;
              
        $this->_aParams["sid"]                  = $sid;
        $this->_aParams["user_name"]            = $user_name;
        $this->_aParams["interview_time_min"]   = $interview_time_min;
        $this->_aParams["interview_time_max"]   = $interview_time_max;
        $this->_aParams["status"]               = $interview_status;

        $item = 'id,sid,company_id,job_id,person_id,resume_id,status,interview_time,source';
        $result = $service_schoolnet_shuangxuanpersonapply->getInterviewList($sid, $this->_userid, $user_name, $interview_status, $interview_time_min, $interview_time_max, $page_index, $page_size, $item);
        if(!empty($result->items)){
            $service_person                             = new base_service_person_person();
            $service_person_resume                      = new base_service_person_resume_resume();
            $service_company_job                        = new base_service_company_job_job();
//            $service_schoolnet_shuangxuanpersonenter    = new base_service_schoolnet_shuangxuanpersonenter();
            $service_person_resume_edu                  = new base_service_person_resume_edu();
            
            $person_ids = base_lib_BaseUtils::getProperty($result->items, 'person_id');
            $resume_ids = base_lib_BaseUtils::getProperty($result->items, 'resume_id');
            $job_ids    = base_lib_BaseUtils::getProperty($result->items, 'job_id');
            
            $person_list        = $service_person->getPersons($person_ids, 'person_id,user_name,sex,birthday2')->items;
            $resume_list        = $service_person_resume->getResumes($resume_ids, 'resume_id,person_id,degree_id,school,major_desc,user_name,birthday')->items;
            $job_list           = $service_company_job->getJobs($job_ids, 'job_id,company_id,station');
//            $major_desc_list    = $service_schoolnet_shuangxuanpersonenter->getPersonMajorDescs($sid, $person_ids);

            $person_list        = base_lib_BaseUtils::array_key_assoc($person_list, 'person_id');
            $resume_list        = base_lib_BaseUtils::array_key_assoc($resume_list, 'resume_id');
            $job_list           = base_lib_BaseUtils::array_key_assoc($job_list, 'job_id');
            $major_desc_list    = base_lib_BaseUtils::array_key_assoc($major_desc_list, 'person_id');
            
            foreach ($result->items as $k => $v) {
                $result->items[$k]['user_name']             = $person_list[$v['person_id']]['user_name'];
                $result->items[$k]['mobile_phone']          = $person_list[$v['person_id']]['mobile_phone'];
                $result->items[$k]['source_name']           = $v['source'] == 1 ? '学生申请' : '企业发起';
                $result->items[$k]['status_name']           = $this->getStatusName($v['status'], $v['interview_time'], $v['source']);
                $result->items[$k]['station']               = $job_list[$v['job_id']]['station'];
                $result->items[$k]['school']                = $resume_list[$v['resume_id']]['school'];
                $result->items[$k]['major_desc']            = $major_desc_list[$v['person_id']]['major_desc'];
                $high_edu = $service_person_resume_edu->getHighestEdu($v['resume_id'], 'major_desc');
                $result->items[$k]['major_desc']            = empty($high_edu) ? "" : $high_edu['major_desc'];
                $result->items[$k]['interview_time_str']    = !$v['status'] || $v['status'] == 5? '' : date('Y-m-d H:i', strtotime($v['interview_time']));
            }
        }
        
        $this->_aParams["list"] = $result->items;
        $this->_aParams["pager"] = $this->pageBarFullPath($result->totalSize, $page_size, $page_index, $inPath);
        return $this->render("./schoolnet/interviewlist.html",$this->_aParams);
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
        
        if(!$id || !$status){
            echo json_encode(['status'=>false, 'msg'=>'参数错误']);
            return;
        }
        
        $service_schoolnet_shuangxuanpersonapply = new base_service_schoolnet_shuangxuanpersonapply();
        $msg = '操作失败';
        $res = $service_schoolnet_shuangxuanpersonapply->setStatus($id, $status, $person_id, true, $msg);

        if($res){
            echo json_encode(['status'=>true, 'msg'=>'操作成功']);
            return;
        }
        
        echo json_encode(['status'=>false, 'msg'=>$msg]);
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
    
    /**
     * 求职者大厅
     * @param type $inPath
     */
    public function pageJobWanters($inPath){
        $this->checkToday($inPath);
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
        
        $service_schoolnet_shuangxuanpersonenter = new base_service_schoolnet_shuangxuanpersonenter();
        $service_schoolnet_shuangxuanjobnet      = new base_service_schoolnet_shuangxuanjobnet();
        $service_schoolnet_shuangxuannet         = new base_service_schoolnet_shuangxuannet();
        $service_company_job                     = new base_service_company_job_job();
        
        list($status, $msg, $data) = $service_schoolnet_shuangxuannet->videoInterviewTop($sid, $this->_userid);
        if(!$status)
            return $this->render('../config/404.html');
        
        $this->_aParams = $data;
        $this->_aParams["params"]            = $pathdata;
        $this->_aParams["sid"]            = $sid;
        //$this->_aParams["user_name"]      = $user_name;
        $this->_aParams["sex"]            = $sex;
        //$this->_aParams["major"]          = $major;
        $this->_aParams["degree"]          = $degree;
        $degree_common = new base_service_common_degree();
        $this->_aParams["all_degree"]         = $degree_common->getAll();
        $net_jobs = $service_schoolnet_shuangxuanjobnet->getNetCompanyJob($this->_userid, $sid, 'job_id');
        $job_ids = base_lib_BaseUtils::getProperty($net_jobs, 'job_id');
        $this->_aParams["net_jobs"] = $service_company_job->getJobsV2($job_ids, 'job_id,station');
        //$result = $service_schoolnet_shuangxuanpersonenter->jobWanterList($sid, $user_name, $sex, $major, $page_index, $page_size,$key_word);
        $result = $service_schoolnet_shuangxuanpersonenter->jobWanterListV2($sid, $degree, $sex, $key_word, $page_index, $page_size);
        $this->_aParams["list"]  = $result->items;
        $this->_aParams["pager"] = $this->pageBarFullPath($result->totalSize, $page_size, $page_index, $inPath);
        return $this->render("./schoolnet/jobwanters.html",$this->_aParams);
    }

    /**
     * 下拉获取更多数据
     * @param $inPath
     */
    public function pageJobWantersV2Json($inPath)
    {
        //$this->checkToday($inPath);
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $sid = base_lib_BaseUtils::getStr($pathdata["sid"], "int", '');
        $sex = base_lib_BaseUtils::getStr($pathdata["sex"], "int", '');
        $key_word = base_lib_BaseUtils::getStr($pathdata["key_word"], "string", '');
        $degree = base_lib_BaseUtils::getStr($pathdata["degree"], "string", '');
        $page_index = base_lib_BaseUtils::getStr($pathdata["page"], "int", 1);
        $page_size = 21;
        $service_schoolnet_shuangxuanpersonenter = new base_service_schoolnet_shuangxuanpersonenter();
        $result = $service_schoolnet_shuangxuanpersonenter->jobWanterListV2($sid, $degree, $sex, $key_word, $page_index, $page_size);
        //$this->ajax_data_json('SUCCESS', '获取成功', ['list' => $result->items]);
        return $this->json($result->items);
    }


    /**
     * 招聘职位
     * @param type $inPath
     */
    public function pageJobs($inPath){
        $this->checkToday($inPath);
        $pathdata               = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $sid                    = base_lib_BaseUtils::getStr($pathdata["sid"],"int",'');
        
        $service_schoolnet_shuangxuanjobnet = new base_service_schoolnet_shuangxuanjobnet();
        $service_schoolnet_shuangxuannet    = new base_service_schoolnet_shuangxuannet();
        list($status, $msg, $data) = $service_schoolnet_shuangxuannet->videoInterviewTop($sid, $this->_userid);
        if(!$status)
            return $this->render('../config/404.html');
        
        $this->_aParams = $data;
        
        $job_list = $service_schoolnet_shuangxuanjobnet->getNetCompanyJob($this->_userid,$sid,'id,sid,company_id,job_id');
        
        if(!empty($job_list)){
            $job_ids = base_lib_BaseUtils::getProperty($job_list, 'job_id');

            $service_company_job = new base_service_company_job_job();
            $jobs = $service_company_job->getJobsV2($job_ids, 'job_id,station');
            $jobs = base_lib_BaseUtils::array_key_assoc($jobs, 'job_id');

            foreach ($job_list as $k => $v){
                if(!isset($jobs[$v['job_id']])){
                    unset($job_list[$k]);
                    continue;
                }
                $job_list[$k]['station'] = $jobs[$v['job_id']]['station'];
            }
        }
        
        $this->_aParams["list"]  = $job_list;
        $this->_aParams["sid"]   = $sid;
        return $this->render("./schoolnet/jobs.html",$this->_aParams);
    }


    /**
     * 视频面试复试
     * @param type $inPath
     * @return type
     */
    public function pageVideoInterviewBySecond($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $sid = base_lib_BaseUtils::getStr($pathdata["sid"],"int",0);

        $service_schoolnet_shuangxuannet = new base_service_schoolnet_shuangxuannet();
        list($status, $msg, $data) = $service_schoolnet_shuangxuannet->getInterviewBySecond($sid, $this->_userid);

        if(!$status)
            return $this->render('../config/404.html');

        $this->_aParams = $data;
        $this->_aParams['sid'] = $sid;

        return $this->render("./schoolnet/secondinterview.html",$this->_aParams);
    }
    
    /**
     * 发送offer
     * @param type $inPath
     * @return type
     */
    public function pageSendOffer($inPath){
        $pathdata   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $id        = base_lib_BaseUtils::getStr($pathdata["id"],"int",'');
        
        $service_schoolnet_shuangxuanpersonapply = new base_service_schoolnet_shuangxuanpersonapply();
        list($status, $msg, $invite_id) = $service_schoolnet_shuangxuanpersonapply->sendOffer($id);

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

        $pathdata   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $sid        = base_lib_BaseUtils::getStr($pathdata["sid"],"int",'');
        
        $service_schoolnet_shuangxuannet = new base_service_schoolnet_shuangxuannet();
        $shuang_xuan = $service_schoolnet_shuangxuannet->getShuangXuanNetByID($sid, 'start_time,end_time');

        if($shuang_xuan['end_time'] <= date('Y-m-d H:i:s'))
            return;
        
        $service_schoolnet_shuangxuaninterview  = new base_service_schoolnet_shuangxuaninterview();
        $service_chat = new company_service_chat();
        $browser = $service_chat->getbrowser();

        $has_checked = base_lib_BaseUtils::getCookie("school_videohall_equipment_check_today_{$sid}") ? true : false;
        if($has_checked)
            return;
        
        list($status, $msg, $data) = $service_schoolnet_shuangxuannet->videoInterviewTop($sid, $this->_userid);
        $this->_aParams = $data;
        
        $this->_aParams['sid'] = $sid;
        $this->_aParams['list'] = $service_schoolnet_shuangxuaninterview->getCompanyTime($this->_userid, $sid, 'id,start_time,end_time,time_type', 'order by start_time asc');
//        $this->_aParams['browser_not_ok'] = strtolower($browser['browser']) != 'chrome' || $browser['version'] < 60;
        $this->_aParams['browser_not_ok'] = false;
        
        $this->_aParams['par'] = '视频面试大厅';
        if(isset($inPath[2])){
            switch ($inPath[2]) {
                case 'JobWanters':
                    $this->_aParams['par'] = '求职者大厅';
                    break;
                case 'InterviewList':
                    $this->_aParams['par'] = '面试结果';
                    break;
                case 'Jobs':
                    $this->_aParams['par'] = '招聘职位';
                    break;
            }
        }
        
        echo $this->render("./schoolnet/checktoday.html",$this->_aParams);
        die;
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
        
        $service_schoolnet_shuangxuaninterview = new base_service_schoolnet_shuangxuaninterview();
        $is_in_time = $service_schoolnet_shuangxuaninterview->isInTime($this->_userid, $sid);

        $msg = $need_download ? '请处理完待面试求职者后，才能查看联系方式' : '请处理完待面试求职者后，才能立即沟通';
        if($is_in_time && $has_wait_deal_apply){
            echo json_encode(['status' => false, 'msg' => $msg]);
            return;
        }
        
        if($need_download){
            $accountid = base_lib_BaseUtils::getCookie('accountid');
            $company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
            $company_resources->videoDownLoad($resume_id, $person_id);
        }
        echo json_encode(['status' => true]);
        return;
    }

}

?>