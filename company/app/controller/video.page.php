<?php

/**
 *
 * @ClassName controller_video
 * @Desc      视频面试
 * @author    huangwentao@huibo.com
 * @date      2013-9-26 下午01:51:47
 */
class controller_video extends components_cbasepage {
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * 我的账户入口
	 * @param $inPath
	 */
	function pageIndex($inPath) {
		
		
        return $this->render("./chat/video.html",$this->_aParams);
	}
    
    
    public function pageGetToken($inPath){
        $pathdata       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $channel_id     = base_lib_BaseUtils::getStr($pathdata["room"],"int",1900);
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        $service_rtc    = new SAliRTC($account_id,"company");
        $token_data     = $service_rtc->getTokenData($channel_id);
        echo json_encode($token_data);exit;
    }


    /**
     * 判断是否可以面试
     * @param $inPath
     */
    public function pageCanVideo($inPath)
    {
        $pathdata       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $net_apply_id   = base_lib_BaseUtils::getStr($pathdata["net_apply_id"],"int",0);
        $resume_id      = base_lib_BaseUtils::getStr($pathdata["resume_id"],"int",0);
        $job_id         = base_lib_BaseUtils::getStr($pathdata["job_id"],"int",0);
        $company_id     = $this->_userid;
        $account_id     = base_lib_BaseUtils::getCookie('accountid');

        if(empty($resume_id)){
            echo $this->jsonMsg(false, "缺少必要参数",["code" => 0]);exit;
        }
        //企业今日视频面试时间是否已达上限

        if(empty($net_apply_id)){
           //不是活动的视频面试判断
            $service_resume = new base_service_person_resume_resume();
            $person_resume = $service_resume->getResume($resume_id,'person_id,resume_id');
            $service_chat_history = new base_service_rong_rongchatrecord();
            $is_reply             = $service_chat_history->checkPersonIsReplyCompany($person_resume['person_id'], $account_id);
            if(!$is_reply){
                $service_download = new base_service_company_resume_download();
                $service_apple    = new base_service_company_resume_apply();
                $is_download      = $service_download->isResumeDownloaded($company_id, $resume_id);
                $is_apply         = $service_apple->isApply($company_id, $resume_id);
                if(!$is_download && !$is_apply){
                    echo $this->jsonMsg(false, "请先和求职者文字沟通，对方回复后才能发起视频面试",["code" => 0]);exit;
                }
            }
            //获取剩余时间
            $service_channel    = new base_service_schoolnet_channel();
             $surplus_time = $service_channel->getCompanyNormalLastVideoTime($company_id);
            echo $this->jsonMsg(true, "",["code" => 200,"surplus_time"=>intval($surplus_time/60)]);exit;
        }else{
            //判断投递是否是主动投递，如果不是 则判断是否有待处理的申请面试，如果有则提示去处理
            $service_net_apply   = new base_service_netfair_personapplynet();
            $service_netfair_company = new base_service_netfair_company();
            $service_netfair_person = new base_service_netfair_person();
            $service_netfair_resume = new base_service_netfair_resume();
            $netfair_company = $service_netfair_company->getCompanyID($this->_userid,1,'id');
            $apply_info          = $service_net_apply->getApplyById($net_apply_id, "sid,company_id,job_id,person_id,resume_id,source,status");
            //判断是否还有未处理的视频申请
            $not_invite_data        = $service_net_apply->getNotRtcInvitePersonV2($apply_info["sid"], $netfair_company['id'], "id,resume_id,job_id",$net_apply_id);
            $service_channel        = new base_service_schoolnet_channel(); //获取企业今日面试时长
            $total_time             = $service_channel->getCompanyInterviewTime($company_id, 2);
            $netfair_person = $service_netfair_person->getNetFairPersonInfoById($apply_info["person_id"]);

            $netfair_resume = $service_netfair_resume->getDataById($apply_info["resume_id"]);
            if($apply_info["source"] == 1){
                if($apply_info["status"] !=0 && $apply_info["status"] != 1){

                    if(!empty($not_invite_data) && count($not_invite_data) > 1){
                        $_not_invite_data = $not_invite_data[0];
                        $service_netfair_job = new base_service_netfair_job();
                        $netfair_job = $service_netfair_job->getBaseJobId($_not_invite_data["job_id"],'','id,base_job_id');
                        $netfair_resume_temp = $service_netfair_resume->getDataById($_not_invite_data["resume_id"]);
                        $temp_data = ["code" => 1, 'next_job_id' => $netfair_job["base_job_id"], 'next_resume_id' => $netfair_resume_temp["base_resume_id"], "net_apply_id" => $_not_invite_data["id"]];
                        echo $this->jsonMsg(false, "目前还有待面试的学生，请优先处理待面试学生后，才能与其他学生进行视频面试",$temp_data);exit;
                    }
                }else{
                    //可以正常面试
                }
            }else{
                if(!empty($not_invite_data) && count($not_invite_data) > 1){
                    $_not_invite_data = $not_invite_data[0];
                    $service_netfair_job = new base_service_netfair_job();
                    $netfair_job = $service_netfair_job->getBaseJobId($_not_invite_data["job_id"],'','id,base_job_id');
                    $netfair_resume_temp = $service_netfair_resume->getDataById($_not_invite_data["resume_id"]);
                    $temp_data = ["code" => 1, 'next_job_id' => $netfair_job["base_job_id"], 'next_resume_id' => $netfair_resume_temp["base_resume_id"], "net_apply_id" => $_not_invite_data["id"]];
                    echo $this->jsonMsg(false, "目前还有待面试的学生，请优先处理待面试学生后，才能与其他学生进行视频面试",$temp_data);exit;
                }
                $service_chat_history = new base_service_rong_rongchatrecord();
                $is_reply             = $service_chat_history->checkPersonIsReplyCompany($netfair_person["base_person_id"], $account_id);
                //判断是否下载过该简历
                if(!$is_reply){
                    $service_download = new base_service_company_resume_download();
                    $service_apple    = new base_service_company_resume_apply();
                    $is_download      = $service_download->isResumeDownloaded($this->_userid, $netfair_resume["base_resume_id"]);
                    $is_apply         = $service_apple->isApply($this->_userid, $netfair_resume["base_resume_id"]);
                    if(!$is_download && $is_apply){
                        echo $this->jsonMsg(false, "求职者回复聊天内容，或下载该简历后才能发起视频聊天",["code" => 0]);exit;
                    }
                }
            }
        }
        echo $this->jsonMsg(true, "",["code" => 200,"surplus_time"=>0]);exit;
    }
    
    //视频聊天创建房间  校园网络招聘会  
    public function pageCreateRoom($inPath){

        $pathdata       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $net_apply_id   = base_lib_BaseUtils::getStr($pathdata["net_apply_id"],"int",0);
        $resume_id      = base_lib_BaseUtils::getStr($pathdata["resume_id"],"int",0);
        $job_id         = base_lib_BaseUtils::getStr($pathdata["job_id"],"int",0);
        $company_id     = $this->_userid;
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        
        if(empty($net_apply_id) && empty($resume_id)){
            echo $this->jsonMsg(false, "缺少必要参数",["code" => 0]);exit;
        }
        //企业今日视频面试时间是否已达上限

        $service_net_apply  = new base_service_netfair_personapplynet();
        $service_channel    = new base_service_schoolnet_channel();
        $service_netfair_person = new base_service_netfair_person();
        $apply_info         = $service_net_apply->getApplyById($net_apply_id,"company_id,sid,create_time,status,source,person_id");
        $netfair_person = $service_netfair_person->getNetFairPersonInfoById($apply_info["person_id"]);
        //判断该求职者是否在线 如果不在线 则不直接打开视频面试 如果在线 则直接打开视频面试
        $service_wy_action  = new base_service_app_wangyiaction();
        $is_online          = $service_wy_action->checkPersonIsOnline($netfair_person["base_person_id"]);
        $is_online          = true;
        if($is_online && $apply_info["source"] == 1){
            $this->_aParams["auto_create_resume"] = true;
        }
        //判断该求职者是否正在与其他人面试
        $is_on_interview = $service_channel->checkPersonIsOnInterview($netfair_person["base_person_id"], null);
        if($is_on_interview){
            echo $this->jsonMsg(false, "该求职者正在面试，请稍后...",["code" => 0]);exit;
        }
        //该企业是否在与其他人面试
        $cache_channel_data = $service_channel->getChannelByAccount($account_id);
        if(!empty($cache_channel_data)){
            $_person_id     = $cache_channel_data["person_id"];
            $service_person = new base_service_person_person();
            $person_info    = $service_person->getPerson($_person_id, "person_id,user_name");
            $_end_time      = $cache_channel_data["start_time"] + 600;;
            $diff_time      = $_end_time - time();
            $diff_minutes   = ceil($diff_time/60);
            //$service_channel->setChannelEnd($cache_channel_data["channel_id"], $account_id); //如果超过10分钟结束上一个面试
            if($cache_channel_data["net_apply_id"] != $net_apply_id){
                if($diff_time > 0){
                    echo $this->jsonMsg(false, "同时间仅能面试1人，检测到您与学生（{$person_info["user_name"]}）已在面试中，请先结束面试或等待{$diff_minutes}分钟后，发起新的面试",["code" => 0]);exit;
                }else{
                    $service_channel->setChannelEnd($cache_channel_data["channel_id"], $account_id); //如果超过10分钟结束上一个面试
                }
            }
        }

        if(empty($net_apply_id)){
            //判断该求职者是否今日回复过聊一聊 todo 
            //不是活动的视频面试判断
            $service_resume = new base_service_person_resume_resume();
            $person_resume = $service_resume->getResume($resume_id,'person_id,resume_id');
            $service_chat_history = new base_service_rong_rongchatrecord();
            $is_reply             = $service_chat_history->checkPersonIsReplyCompany($person_resume['person_id'], $account_id);
            if(!$is_reply){
                $service_download = new base_service_company_resume_download();
                $service_apple    = new base_service_company_resume_apply();
                $is_download      = $service_download->isResumeDownloaded($company_id, $resume_id);
                $is_apply         = $service_apple->isApply($company_id, $resume_id);
                if(!$is_download && !$is_apply){
                    echo $this->jsonMsg(false, "请先和求职者文字沟通，对方回复后才能发起视频面试",["code" => 0]);exit;
                }
            }
            //获取剩余时间 分钟数
            //$surplus_time = 100;
           // echo $this->jsonMsg(true, "",["code" => 200,"surplus_time"=>$surplus_time]);exit;
        }else{
            //判断投递是否是主动投递，如果不是 则判断是否有待处理的申请面试，如果有则提示去处理
            $service_net_apply   = new base_service_netfair_personapplynet();
            $service_netfair_resume = new base_service_netfair_resume();
            $service_netfair_job    = new base_service_netfair_job();
            $apply_info          = $service_net_apply->getApplyById($net_apply_id, "sid,company_id,job_id,person_id,resume_id,source,status");
            //判断是否还有未处理的视频申请
            $not_invite_data        = $service_net_apply->getNotRtcInvitePersonV2($apply_info["sid"], $this->_userid, "id,resume_id,job_id",$net_apply_id);
            $service_channel        = new base_service_schoolnet_channel(); //获取企业今日面试时长
            $total_time             = $service_channel->getCompanyInterviewTime($company_id, 2);
            $allow_total_time       = 3*3600; //一天允许3小时
            
            if($apply_info["source"] == 1){
                if($apply_info["status"] !=0 && $apply_info["status"] != 1){
                    //如果已经面试过该求职者了 则判断今天面试时长是否已用完
                    if($total_time >= $allow_total_time){
                        echo $this->jsonMsg(false, "今日面试时间已用完，可明日继续",["code" => 0]);exit;
                    }
                    if(!empty($not_invite_data) && count($not_invite_data) > 1){
                        $_not_invite_data       = $not_invite_data[0];
                        $netfair_job            = $service_netfair_job->getBaseJobId($_not_invite_data["job_id"], '', 'id,base_job_id');
                        $netfair_resume_temp    = $service_netfair_resume->getDataById($_not_invite_data["resume_id"]);
                        $temp_data              = ["code" => 1, 'next_job_id' => $netfair_job["base_job_id"], 'next_resume_id' => $netfair_resume_temp["base_resume_id"], "net_apply_id" => $_not_invite_data["id"]];
                        //["code" => 1,'next_job_id'=>$_not_invite_data["job_id"],'next_resume_id'=>$_not_invite_data["resume_id"],"net_apply_id" => $_not_invite_data["id"]];
                        echo $this->jsonMsg(false, "目前还有待面试的学生，请优先处理待面试学生后，才能与其他学生进行视频面试",$temp_data);exit;
                    }
                }else{
                    //可以正常面试
                }
            }else{
                if(!empty($not_invite_data) && count($not_invite_data) > 1){
                    $_not_invite_data       = $not_invite_data[0];
                    $netfair_job            = $service_netfair_job->getBaseJobId($_not_invite_data["job_id"], '', 'id,base_job_id');
                    $netfair_resume_temp    = $service_netfair_resume->getDataById($_not_invite_data["resume_id"]);
                    $temp_data              = ["code" => 1, 'next_job_id' => $netfair_job["base_job_id"], 'next_resume_id' => $netfair_resume_temp["base_resume_id"], "net_apply_id" => $_not_invite_data["id"]];
                    echo $this->jsonMsg(false, "目前还有待面试的学生，请优先处理待面试学生后，才能与其他学生进行视频面试",$temp_data);exit;
                }
                
                if($total_time >= $allow_total_time){
                    echo $this->jsonMsg(false, "今日面试时间已用完，可明日继续",["code" => 0]);exit;
                }
                
                //判断该求职者是否回复聊一聊，如果未回复聊一聊则判断是否下载过该简历
                //判断该求职者是否在线
//                $service_wy_action  = new base_service_app_wangyiaction();
//                $is_online          = $service_wy_action->checkPersonIsOnline($apply_info["person_id"]);
                $netfair_resume_temp    = $service_netfair_resume->getDataById($apply_info["resume_id"]);
                $service_chat_history = new base_service_rong_rongchatrecord();
                $is_reply             = $service_chat_history->checkPersonIsReplyCompany($netfair_person["base_person_id"], $account_id);
//                $is_reply             = true;
                //判断是否下载过该简历
                if(!$is_reply){
                    $service_download = new base_service_company_resume_download();
                    $service_apple    = new base_service_company_resume_apply();
                    $is_download      = $service_download->isResumeDownloaded($this->_userid, $netfair_resume_temp["base_resume_id"]);
                    $is_apply         = $service_apple->isApply($this->_userid, $netfair_resume_temp["base_resume_id"]);
                    if(!$is_download && $is_apply){
                        echo $this->jsonMsg(false, "求职者回复聊天内容，或下载该简历后才能发起视频聊天",["code" => 0]);exit;
                    }
                }
            }
        }

        $service_channel     = new base_service_schoolnet_channel();
        list($status,$msg,$return_data) = $service_channel->initRoomInfo($account_id, $company_id, $resume_id, $job_id, $net_apply_id);
        $return_data["is_first"] = false;
        $service_shuangxuannet = new base_service_netfair_net();
        if($apply_info["source"] == 1 && $service_shuangxuannet->videoInterviewHallV2($apply_info['sid'],$apply_info['company_id'],$net_apply_id)){
            $return_data["is_first"] = true;
        }
        $return_data["code"] = 200;
        $return_data["show_apply_resule"] = false;
        if(!empty($net_apply_id)){
            $return_data["surplus_time"] = 0;
        }else{
            $service_channel    = new base_service_schoolnet_channel();
            $surplus_time = $service_channel->getCompanyNormalLastVideoTime($company_id);
            $return_data["surplus_time"] = intval($surplus_time/60);
        }
        if(!empty($apply_info) && in_array($apply_info["status"], [0,1,4,5])){ //待定和面试中 未面试 跳过的 都可以重新设置面试结果
            $return_data["show_apply_result"]   = true;
        }
//        if($status && !empty($net_apply_id) && !empty($apply_info)){
//
//           $this->_sendMsgToPerson($netfair_person["base_person_id"],1,$company_id);
//
//        }
        echo $this->jsonMsg($status, $msg,$return_data);
    }
    
    //获取视频聊天基本信息
    public function pageGetApplyBaseInfo($inPath){
        $pathdata       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $net_apply_id   = base_lib_BaseUtils::getStr($pathdata["net_apply_id"],"int",0);
        $type           = base_lib_BaseUtils::getStr($pathdata["type"],"string","");
        $account_id     = base_lib_BaseUtils::getCookie('accountid');

        switch($type){
            case "nextApply": //获取下
                if(empty($net_apply_id)){
                    echo $this->jsonMsg(false, "缺少必要参数");exit;
                }
                $this->_getNextApplyInfo($net_apply_id);
                break;
            case "setApplyOn"://开始视频面试

                $channel_id         = base_lib_BaseUtils::getStr($pathdata["channel_id"],"int",0);
                if($net_apply_id){
                    $service_net_apply   = new base_service_netfair_personapplynet();
                    $apply_info          = $service_net_apply->getApplyById($net_apply_id, "sid,company_id,job_id,person_id,resume_id,source,status");
                    if($apply_info["status"] == 0){
                        list($result,$msg)  = $service_net_apply->startInterview($net_apply_id, true,null,$account_id);
                    }else{
                        $result = true;
                        $msg    = "成功";
                    }
                }else{
                    if(empty($channel_id)){
                        echo $this->jsonMsg(false, "缺少必要参数");exit;
                    }
                    $result = true;
                    $msg    = "成功";
                }
                if($result !== false){
                    $service_channel     = new base_service_schoolnet_channel();
                    $service_channel->setChannelStart($channel_id, $account_id);
                    echo $this->jsonMsg(true, "开始视频面试成功");exit;
                }else{
                    echo $this->jsonMsg(false, $msg);exit;
                }
                break;
            case "setInterviewResult": //

               // $service_net_apply   = new base_service_schoolnet_shuangxuanpersonapply();
                $status_type         = base_lib_BaseUtils::getStr($pathdata["status_type"],"string","");
                $set_wait_type = true;
                if($status_type == "wait"){
                    $status = 4;
                }elseif($status_type == "no"){
                     $status = 3;
                }elseif($status_type == "yes"){
                    $status = 2;
                }elseif($status_type == "jump"){
                    $set_wait_type = false;
                    $status        = 5;
                }elseif($status_type == "handup"){ //如果是挂断 则只是关闭房间
                    $channel_id             = base_lib_BaseUtils::getStr($pathdata["channel_id"],"int",0);
                    $service_channel        = new base_service_schoolnet_channel();
                    $service_channel->setChannelEnd($channel_id,$account_id);
                    echo $this->jsonMsg(true, "操作成功");exit;
                }
                if(empty($net_apply_id)){
                    echo $this->jsonMsg(false, "缺少必要参数");exit;
                }
                $service_net_apply   = new base_service_netfair_personapplynet();
                $apply_info          = $service_net_apply->getApplyById($net_apply_id, "sid,company_id,job_id,person_id,resume_id,source,status");
                $result = $service_net_apply->setStatus($net_apply_id, $status,$apply_info['person_id'],base_lib_BaseUtils::getCookie('accountid'),$set_wait_type); //设置成面试中
                if($result !== false){
                    if($status == 5){
                        $channel_id             = base_lib_BaseUtils::getStr($pathdata["channel_id"],"int",0);
                        $service_channel        = new base_service_schoolnet_channel();
                        $service_channel->setChannelEnd($channel_id,$account_id);
                        //如果是跳过 给求职者发送短信
                        $this->sendOverMsg($net_apply_id);
                    }elseif($status == 2){
                        $this->sendSuccessMsg($net_apply_id);
                    }
                    
                    echo $this->jsonMsg(true, "操作成功");exit;
                }else{
                    echo $this->jsonMsg(false, "操作失败");exit;
                }
                break;
            case "getTime":
                if(empty($net_apply_id)){
                    echo $this->jsonMsg(false, "缺少必要参数");exit;
                }
                $service_channel     = new base_service_schoolnet_channel();
                $last_time           = $service_channel->getLastApplyTime($net_apply_id);
                $is_can_extent       = $service_channel->checkCanExtend($net_apply_id);
                echo $this->jsonMsg(true, "操作成功",["last_time" => $last_time,"can_extent" => $is_can_extent]);exit;
                break;
            case "setTime":
                //$service_channel                = new base_service_schoolnet_channel();
                $service_channel             = new base_service_schoolnet_channel();
                if(empty($net_apply_id)){
                    $last_time                      = base_lib_BaseUtils::getStr($pathdata["last_time"],"int",0);
                    $service_channel->setApplyTime($net_apply_id, $last_time);
                }
                $channel_id         = base_lib_BaseUtils::getStr($pathdata["channel_id"],"int",0);
                if(empty($channel_id)){
                    echo $this->jsonMsg(false, "缺少必要参数");exit;
                }
                list($status,$msg,$code) = $service_channel->setChannelTime($channel_id,$account_id);
                if($status){
                    echo $this->jsonMsg(true, "操作成功",["is_end"=>false]);exit;
                }else{
                    if($code==2){
                        echo $this->jsonMsg(true, "操作成功",["is_end"=>true]);exit;
                    }else{
                        echo $this->jsonMsg(false, $msg,["is_end"=>false]);exit;
                    }
                }
//                $result = $service_channel->setChannelTime($channel_id,$account_id);
//                echo $this->jsonMsg(true, "操作成功",["is_end"=>false]);exit;
                break;
            case "setExtend":
                if(empty($net_apply_id)){
                    echo $this->jsonMsg(false, "缺少必要参数");exit;
                }
                $service_channel             = new base_service_schoolnet_channel();
                $is_can_extent                  = $service_channel->checkCanExtend($net_apply_id);
                if(!$is_can_extent){
                    echo $this->jsonMsg(false, "操作失败");exit;
                }
                $service_channel->setExtend($net_apply_id);
                //重新设置时间
                $last_time                   = base_lib_BaseUtils::getStr($pathdata["last_time"],"int",0);
                $service_channel->setApplyTime($net_apply_id, $last_time);
                echo $this->jsonMsg(true, "操作成功");exit;
                break;
            //结束面试
            case "setApplyEnd":
                $channel_id             = base_lib_BaseUtils::getStr($pathdata["channel_id"],"int",0);
                $service_channel        = new base_service_schoolnet_channel();
                $service_channel->setChannelEnd($channel_id,$account_id);
                break;
            case "checkChannelStatus":
                $service_channel = new base_service_schoolnet_channel();
                $channel_info    = $service_channel->getChannelByAccount($account_id);
                if(empty($channel_info) || $channel_info["status"]== -1){
                    echo $this->jsonMsg(false, "no");exit;
                }
               // echo $this->jsonMsg(false, "no");exit;
                echo $this->jsonMsg(true, "yes");exit;
                break;
            default :
                echo $this->jsonMsg(false, "没有该类型");exit; 
                break;
        }
    }

    /**
     *@desc 给求职者发送短信
     */
    private function _sendMsgToPerson($person_id,$person_rtc_type,$company_id){
        $service_company = new base_service_company_company();
        $company_info    = $service_company->getCompany($company_id, 1, "company_name,company_shortname");
        if(empty($company_info)){
            return;
        }

        $company_name = $company_info['company_shortname'] ? $company_info['company_shortname'] : $company_info['company_name'];
        if($person_rtc_type == 2){
            $service_person = new base_service_blue_person_person();
            $person_info    = $service_person->getPerson($person_id, "user_name,mobile_phone");
            if(!empty($person_info) && !empty($person_info["mobile_phone"])){
                $content = "{$person_info["user_name"]}，【{$company_name}】HR当前向你发起了视频面试邀请，请尽快打开app参与面试！！！";
                base_lib_SMS::sendTpMsg("8debb647ece3badcc58b267c9d84a4aa",$person_info["mobile_phone"],$content);
            }
            return;
        }elseif($person_rtc_type == 1){
            //判断求职者是否在线
            $service_person_action  = new base_service_app_wangyiaction();
            $is_online              = $service_person_action->checkPersonIsOnline($person_id);
            if($is_online){
                return;
            }
            $service_person = new base_service_person_person();
            $person_info    = $service_person->getPerson($person_id, "user_name,mobile_phone");
            if(!empty($person_info) && !empty($person_info["mobile_phone"])){
                $content = "{$person_info["user_name"]}，【{$company_name}】HR当前向你发起了视频面试邀请，请尽快打开app参与面试！！！";
                base_lib_SMS::sendTpMsg("8debb647ece3badcc58b267c9d84a4aa",$person_info["mobile_phone"],$content);
            }
            return;
        }
    }

    /**
     *@desc 获取基本信息 
     */
    private function _getNextApplyInfo($net_apply_id){
        $service_net_apply   = new base_service_netfair_personapplynet();
        $apply_info          = $service_net_apply->getApplyById($net_apply_id, "sid,company_id,job_id,person_id,resume_id,source,status");
        if($apply_info["source"] == 2){
             echo $this->jsonMsg(false, "没有下一个面试申请信息");exit; 
        }
        //获取下一个面试
        $company_id = $this->_userid;
        $next_apply_info     = $service_net_apply->getNextApply($net_apply_id, $apply_info['company_id'], "id,person_id,create_time,resume_id,job_id");
        if(empty($next_apply_info)){
            echo $this->jsonMsg(false, "没有下一个面试申请信息");exit; 
        }

        $service_resume     = new base_service_person_resume_resume();
        $service_person     = new base_service_person_person();
        $service_job        = new base_service_company_job_job();
        $service_degree     = new base_service_common_degree();
        $service_netfair_job = new base_service_netfair_job();
        $service_netfair_person = new base_service_netfair_person();
        $service_netfair_resume = new base_service_netfair_resume();
        $netfair_job = $service_netfair_job->getBaseJobId($next_apply_info["job_id"]);
        $netfair_person = $service_netfair_person->getPersonIdById($next_apply_info["person_id"],null,'id,id,base_person_id,source');
        if($netfair_person['source']!=1){
            echo $this->jsonMsg(false, "没有下一个面试申请信息");exit;
        }
        $netfair_resume = $service_netfair_resume->getDataById($next_apply_info["resume_id"]);
        $job_info           = $service_job->getJob($netfair_job["base_job_id"], "station");

        $person_info        = $service_person->getPerson($netfair_person["base_person_id"], "user_name,sex,birthday2");
        $resume_info        = $service_resume->getResume($netfair_resume["base_resume_id"], "degree_id");
        $return_data        = [];
        
        $return_data["next_apply_id"] = $next_apply_info["id"];
        $return_data["next_name"]     = $person_info["user_name"];
        $return_data["next_degree"]   = "学历未知";
        if(!empty($resume_info["degree_id"])){
            $degree_name = $service_degree->getDegree($resume_info["degree_id"]);
            if(!empty($degree_name)){
                $return_data["next_degree"] = $degree_name;
            }
        }
        //性别
        $return_data["next_sex"] = $person_info["sex"] == 1 ? "男" : "女";
        //年龄
        $return_data["age"]         = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']).'岁'; 
        $return_data["resume_id"]   = $netfair_resume["base_resume_id"];
        $return_data["job_id"]      = $netfair_job["base_job_id"];
        $return_data["station"]     = $job_info["station"];
        //$time_diff                  = base_lib_TimeUtil::time_diff($next_apply_info["create_time"]);
        $diff_time                    = time()-strtotime($next_apply_info["create_time"]);
        $minutes                      = ceil($diff_time/60);
        $return_data['wait_time']   = $minutes;
         echo $this->jsonMsg(true, "获取成功",$return_data);exit;
    }

    //给设置成跳过的求职者发送短信
    private function sendOverMsg($net_apply_id){
        $service_net_apply   = new base_service_netfair_personapplynet();
        //获取下一个面试
        $company_id = $this->_userid;
        $account_id = base_lib_BaseUtils::getCookie('accountid');
        $net_apply_info     = $service_net_apply->getApplyById($net_apply_id, "id,person_id,create_time,resume_id,job_id,source,company_id");
        if(empty($net_apply_info)){
            return false;
        }
        $service_company        = new base_service_company_company();
        $service_job            = new base_service_company_job_job();
        $service_person         = new base_service_person_person();
        $service_account        = new base_service_company_account();
        $service_netfair_job    = new base_service_netfair_job();
        $service_netfair_person = new base_service_netfair_person();
       // $service_netfair_company = new base_service_netfair_company();
        $netfair_job    = $service_netfair_job->getBaseJobId($net_apply_info["job_id"]);
        $netfair_person = $service_netfair_person->getPersonIdById($net_apply_info["person_id"]);
        //$netfair_company = $service_netfair_company->getBaseCompanyIdByID($net_apply_info["company_id"]);
        $account_info = $service_account->getAccount($account_id, "account_id,mobile_phone,link_tel");
        $company_info = $service_company->getCompany($company_id, 1, "company_name,company_shortname");
        $job_info     = $service_job->getJob($netfair_job["base_job_id"], "station");
        $person_info  = $service_person->getPerson($netfair_person["base_person_id"], "user_name,mobile_phone");
        
        $company_name    = !empty($company_info["company_shortname"]) ? $company_info["company_shortname"] : $company_info["company_name"];
        if($net_apply_info["source"] == 1){
            $msg_content = "{$person_info["user_name"]}同学您好，{".$company_name."}邀请您参与{".$job_info["station"]."}的视频面试未接通，您看到该信息后可登录汇博app与其联系";
        }else{
            $phone          = !empty($account_info["mobile_phone"]) ? $account_info["mobile_phone"] : $account_info["link_tel"];
            $msg_content    = "{$person_info["user_name"]}同学您好，{".$company_name."}邀请您参与{".$job_info["station"]."}的视频面试未接通，"
                . "您看到该信息后可拨打电话{$phone}或登录汇博app与我联系";
        }
        if(!empty($person_info["mobile_phone"])){
            //base_lib_SMS::send($person_info["mobile_phone"], $msg_content);
            base_lib_SMS::sendTpMsg("f6b0e3b62882e266d5f04bf0aa71c6c0",$person_info["mobile_phone"],$msg_content);

        }
        return true;
    }
    
    //面试通过后发送短信
    private function sendSuccessMsg($net_apply_id){
         $service_net_apply   = new base_service_netfair_personapplynet();
        //获取下一个面试
        $company_id = $this->_userid;
        $account_id = base_lib_BaseUtils::getCookie('accountid');
        $net_apply_info     = $service_net_apply->getApplyById($net_apply_id, "id,person_id,create_time,resume_id,job_id,source,company_id");
        if(empty($net_apply_info)){
            return false;
        }
        $service_company        = new base_service_company_company();
        $service_job            = new base_service_company_job_job();
        $service_person         = new base_service_person_person();
        $service_account        = new base_service_company_account();
        $service_netfair_job    = new base_service_netfair_job();
        $service_netfair_person = new base_service_netfair_person();
        // $service_netfair_company = new base_service_netfair_company();
        $netfair_job    = $service_netfair_job->getBaseJobId($net_apply_info["job_id"]);
        $netfair_person = $service_netfair_person->getPersonIdById($net_apply_info["person_id"]);
        //$netfair_company = $service_netfair_company->getBaseCompanyIdByID($net_apply_info["company_id"]);
        $account_info = $service_account->getAccount($account_id, "account_id,mobile_phone,link_tel");
        $company_info = $service_company->getCompany($company_id, 1, "company_name,company_shortname");
        $job_info     = $service_job->getJob($netfair_job["base_job_id"], "station");
        $person_info  = $service_person->getPerson($netfair_person["base_person_id"], "user_name,mobile_phone");
        
        $company_name       = !empty($company_info["company_shortname"]) ? $company_info["company_shortname"] : $company_info["company_name"];
        $phone              = !empty($account_info["mobile_phone"]) ? $account_info["mobile_phone"] : $account_info["link_tel"];
        $msg_content          = "{$person_info["user_name"]}，您通过了{$company_name}{".$job_info["station"]."}的初面，可拨打电话{$phone}联系企业进行后续沟通";
        if(!empty($person_info["mobile_phone"])){
            //base_lib_SMS::send($person_info["mobile_phone"], $msg_content);
            base_lib_SMS::sendTpMsg("a0a67cebb84198160963ad098fc0ec79",$person_info["mobile_phone"],$msg_content);
        }
        return true;
    }
	
    
    /**
     *@desc 视频面试二维码 
     * 
     */
    public function pageRtcScanCode($inPath){
        $pathdata       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $job_id         = base_lib_BaseUtils::getStr($pathdata["job_id"],"int",0);
        $net_apply_id   = base_lib_BaseUtils::getStr($pathdata["net_apply_id"],"int",0);
        $resume_id      = base_lib_BaseUtils::getStr($pathdata["resume_id"],"int",0);
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
//        if(empty($resume_id)){
//            echo $this->jsonMsg(false, "缺少简历编号");exit;
//        }
        $service_sqrcode_type = new base_service_common_sqrcodetype();
		SQrcode::png(json_encode(array ('resume_id'     => $resume_id, 
                                        'job_id'        => $job_id,
                                        'net_apply_id'  => $net_apply_id,
                                        'account_id'    => $account_id,
                                        'company_id'    => $this->_userid,
                                        "apiname"       => "scan_rtc_channel",
                                        "source"        => "huibo",
                                        "type"          => $service_sqrcode_type->scan_rtc_channel)));
    }
    
    /**
     *@desc 获取二维码扫码后的各种状态 
     */
    public function pageRtcScanStatus($inPath){
        $pathdata       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $person_id      = base_lib_BaseUtils::getStr($pathdata["person_id"],"int",0);
        $channel_id     = base_lib_BaseUtils::getStr($pathdata["channel_id"],"int",0); //这是视频聊天房间编号
        if(empty($person_id)){
            echo $this->jsonMsg(false, "确实求职者编号");exit;
        }
        
        $account_id             = base_lib_BaseUtils::getCookie('accountid');
        $company_id = $this->_userid;
        $service_channel        = new base_service_schoolnet_channel();
        if(empty($channel_id)){
            $channel_info   = $service_channel->getChannelByAccount($account_id);
            if(!empty($channel_info)){
                $channel_id = $channel_info["channel_id"];
                $channel_info   = $service_channel->getChannel($channel_id, "person_id,channel_id,start_time,end_time,account_id,apply_source,apply_id");
            }
            //$channel_info   = $service_channel->getChanelByAccountAndPerson($account_id, $person_id, "person_id,channel_id,start_time,end_time,account_id");
        }else{
            $channel_info   = $service_channel->getChannel($channel_id, "person_id,channel_id,start_time,end_time,account_id,apply_source,apply_id");
            if($channel_info["account_id"] != $account_id){
                echo $this->jsonMsg(false, "获取视频房间信息错误");exit;
            }
        }
        $is_first = false;
        if(empty($channel_info)){

            echo $this->jsonMsg(true, "企业还未扫码，或者扫码不成功",["status" => 0,"channel_id" => $channel_info["channel_id"],"apply_source" => $channel_info["apply_source"],'is_first'=>$is_first]);exit;
        }else{
            $service_net_apply   = new base_service_netfair_personapplynet();
            $apply_info          = $service_net_apply->getApplyById($channel_info['apply_id'], "sid,company_id,job_id,person_id,resume_id,source,status");
            $service_shuangxuannet = new base_service_netfair_net();
            if($apply_info["source"] == 1 && $service_shuangxuannet->videoInterviewHallV2($apply_info['sid'],$apply_info['company_id'],$channel_info['apply_id'])){
                $return_data["is_first"] = true;
            }
        }

        if($channel_info["person_id"] != $person_id){
            echo $this->jsonMsg(true, "正在与其他求职者视频面试中",["status" => 0,"channel_id" => $channel_info["channel_id"],"apply_source" => $channel_info["apply_source"],'is_first'=>$is_first]);exit;
        }
        if(empty($channel_info["start_time"]) && empty($channel_info["end_time"])){
            echo $this->jsonMsg(true, "企业扫码成功，求职者还未接受视频面试",["status" => 1,"channel_id" => $channel_info["channel_id"],"apply_source" => $channel_info["apply_source"],'is_first'=>$is_first]);exit;
        }
        if(!empty($channel_info["start_time"]) && empty($channel_info["end_time"])){
            echo $this->jsonMsg(true, "正在视频面试中",["status" => 2,"channel_id" => $channel_info["channel_id"],"apply_source" => $channel_info["apply_source"],'is_first'=>$is_first]);exit;
        }
        
        echo $this->jsonMsg(true, "视频面试已结束",["status" => 3,"channel_id" => $channel_info["channel_id"],"apply_source" => $channel_info["apply_source"],'is_first'=>$is_first]);exit;
    }
}

?>