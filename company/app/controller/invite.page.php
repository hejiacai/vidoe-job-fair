<?php
/**
 * @copyright 2002-2013 www.huibo.com
 * @name 邀请
 * @author che
 * @version 2013-8-19
 */
class controller_invite extends components_cbasepage {

    function __construct() {
        parent::__construct();
    }

    /**
     * 一条记录的状态修改
     */
    function pageState($inPath) {
        $servInvite = new base_service_company_resume_jobinvite();
        $urlData = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

        $state_infor = $this->get_state_infor($urlData);
        $result = $this->validate_state_infor($state_infor);

        if (empty($state_infor["msg_content"])){
            $result->addErr("通知内容不能为空");
            echo $result->toJsonWithHtml();
            return;
        }
        if ($result->has_err) {
            echo $result->toJsonWithHtml();
            return;
        }

        $result->has_err = !$this->save_state_infor($state_infor);
        if ($result->has_err) {
            $result->addErr("修改数据失败");
            echo $result->toJsonWithHtml();
            return;
        }
        //发送短信通知

        $this->__sendPassMsg($state_infor["invite_id"], $state_infor["state_value"],$state_infor["msg_content"]);
        
        //统计使用量
        $service_cache_clicks = new base_service_cache_clicks();
        $service_cache_clicks->setClicksCache("inviteManage");
        
        echo json_encode(array("success"=>true));
    }
    
    /**
     * 企业放弃面试
     * @param type $inPath
     */
    public function pageGiveUpAudition($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$invite_id = base_lib_BaseUtils::getStr($pathdata['invite_ids'],'array',[]);
		$give_up_type = base_lib_BaseUtils::getStr($pathdata['give_up_type'], 'int', 3);
		$notice_person = base_lib_BaseUtils::getStr($pathdata['notice_person'], 'int', 0);

        if(empty($invite_id)){
            echo json_encode(array("success"=>false, "error"=>"设置失败"));return;
        }
        $service_invite = new base_service_company_resume_jobinvite();
        $is_send_msg = false;
        if(($res = $service_invite->refuseByCompanyNew($invite_id, $this->_userid, $give_up_type,$is_send_msg,$notice_person)) !== false){
            //统计使用量
            $service_cache_clicks = new base_service_cache_clicks();
            $service_cache_clicks->setClicksCache("inviteManage");
            echo json_encode(array("success"=>true,'is_send_msg'=>$is_send_msg));return;
        }
        echo json_encode(array("success"=>false, "error"=>"设置失败"));return;
    }
    /**
     * 发送面试结果消息
     * @param type $inPath
     * @return type
     */
    private function __sendPassMsg($invite_ids, $state,$msg_content){
        $invite_ids = explode(",", $invite_ids);
        if(empty($invite_ids))
            return;
        foreach ($invite_ids as $invite_id) {
            $return_data = $this->__isSendPassMsg($invite_id, $state,$msg_content);
            if(empty($return_data))
                continue;

            $sms_content    = $return_data["msg"];
            $person         = $return_data["person"];
            $company        = $return_data["company"];
            $invite_info    = $return_data["invite_info"];
            $company_name   = !empty($company['company_shortname']) ? $company['company_shortname'] : $company['company_name'];
            if($state == 1){
                //发信息
                base_lib_SMS::send($person['mobile_phone'], $sms_content);
            }
            //新增APP消息
            $service_msg    = new base_service_app_interactionmsg();
            $title          = "面试结果通知";
            $params         = array();
            $params["content"]             = $sms_content;
            $params["broadcast_content"]   = "【{$company_name}】向你发送了面试结果通知";
            $service_msg->sendAppMsg($person['person_id'], $title, "T0200", $params);
            
            //站内信
            $service_message     = new base_service_message_messageperson();
			$service_messagetype = new base_service_message_messagepersontype();
            $item_message = [];
            $item_message['sender']    = 'huibo.com';
			$item_message['type']      = $service_messagetype->jobmessage;
            $item_message['person_id'] = $person['person_id'];

            $item_message['subject']   = "面试结果通知";
            $item_message['content']   = $sms_content;
            $service_message->addPersonMessage($item_message);
            
            //发送面试通过聊一聊消息
            if($state == 1){
                //发送聊一聊消息
                $service_qqcloud_msg = new base_service_interface_qqcloudsendmsg();
                $qq_cloud_content    = $msg_content;
                $from_id             = base_lib_BaseUtils::getCookie('accountid');
                $service_qqcloud_msg->addPreSendMsg("CIP", $from_id, $person['person_id'], $qq_cloud_content, $invite_info["resume_id"], $invite_info["job_id"]);
            }
            
        }
    }
    /**
     * 设置面试通过和不通过时发消息的逻辑判断，并发消息或短信等
     */
    private function __isSendPassMsg($invite_id, $state,$msg_content){
        //只有面试通过和不通过时才需要发送
        if(empty($invite_id) || ($state != 1 && $state != 2))
            return false;
        
        $service_invite = new base_service_company_resume_jobinvite();
        $invite_item = $service_invite->getInvite($invite_id, 1, "person_id,company_id,audition_time,resume_id,job_id");
        if(empty($invite_item))
            return false;
        
        //如果面试时间超过了14天就不再促发了
        $diff_days = ceil(($this->_time - strtotime($invite_item["audition_time"]))/3600/24);
        if($diff_days > 14 && $state == 2)
            return false;

        $service_company = new base_service_company_company();
        $company = $service_company->getCompany($invite_item["company_id"], 1, "company_name,company_shortname");
        $service_person = new base_service_person_person();
        $peerson = $service_person->getPersonByPersonID($invite_item["person_id"],"person_id,user_name,mobile_phone");
        
        if(empty($company) || empty($peerson))
            return false;
        
        $company_name = !empty($company['company_shortname']) ? $company['company_shortname'] : $company['company_name'];

//        if($state == 1){
//            $msg_content = "恭喜您{$peerson['user_name']}，您已经通过了我们的面试，稍后会有工作人员联系您【{$company_name}】";
//        }else{
//            $msg_content = "{$peerson['user_name']}，你好。经过我们慎重评估和考虑，您暂时不符合我们的岗位，希望您早日找到满意的工作，谢谢【{$company_name}】";
//        }
        
        return ["msg"=>$msg_content, "person"=>$peerson, "company"=>$company,"invite_info"=>$invite_item];
    }

    public function pageMoreinvites($inPath){
        $urlData = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        //获取查询信息
        $this->_aParams["search_current_class"] = $this->get_post_value($urlData["t"]);
        if($this->_aParams["search_current_class"]=="0"){
            $this->_aParams["search_current_class"] = null;
        }
        $job_search = $this->get_post_value($urlData["j"]);
        $this->_aParams["search_job"] = $job_search;
        $this->_aParams["search_date"] = $this->get_post_value(urldecode($urlData["selectDate"]));
        if($this->_aParams["search_date"]==""){
            $this->_aParams["search_date"] = date("Y年m月d日");
        }
        $search_time = $this->get_search_time($this->_aParams["search_date"]);
        $search_date = date("Y-m-d H:i:s",$search_time);
        //获取邀请列表
        $pageIndex = isset($urlData["index"])?base_lib_BaseUtils::getStr($urlData["index"]):0;
        if($pageIndex==0){
            echo json_encode(array("error"=>true));
            return;
        }

        $service_invites = new base_service_company_resume_jobinvite();
        $service_invites->setLimit(base_lib_Constant::PAGE_SIZE);
        $service_invites->setCount(true);
        $service_invites->setPage($pageIndex);
        $invites = $service_invites->get_invites($this->_userid,"invite_id,resume_id,audition_link_man,audition_link_tel,audition_remark,audition_result,station,audition_time,person_id,re_status",$this->_aParams["search_current_class"],'',$search_date,$job_search)->items;
        $person_ids = array();
        $resume_ids = array();
        foreach ($invites as $invite) {
        	if(!in_array($invite["person_id"], $person_ids) && 
                !base_lib_BaseUtils::nullOrEmpty($invite["person_id"])){
	        	array_push($person_ids,$invite["person_id"]);
	        }
            if(!in_array($invite["resume_id"], $resume_ids) && 
                !base_lib_BaseUtils::nullOrEmpty($invite["resume_id"])){
                array_push($resume_ids, $invite["resume_id"]);
            }
        }
        if(count($person_ids)>0){
            $personModel = new base_service_person_person();
            $persons = $personModel->getPersons($person_ids,"person_id,user_name,mobile_phone,telephone",true)->items;

        	for($personIndex = 0; $personIndex<count($persons); $personIndex++){
        		for($inviteIndex = 0; $inviteIndex<count($invites); $inviteIndex++){
            		if($persons[$personIndex]["person_id"] == $invites[$inviteIndex]["person_id"]){
    	    			$user_name = $persons[$personIndex]["user_name"];
            			$invites[$inviteIndex]["user_name"] = $user_name;
            			$invites[$inviteIndex]["mobile_phone"] = $persons[$personIndex]["mobile_phone"];
            			$invites[$inviteIndex]["telephone"] = $persons[$personIndex]["telephone"];
            		}
            	}
            }
        }
        //获取备注信息
        if(count($resume_ids)>0){
            $remarkModel = new base_service_company_resume_resumeremark();
            $lastRemarks = $remarkModel->getLastRemarkByResumeIDs($this->_userid, implode(",", $resume_ids),"remark_id,resume_id,remark")->items;
            for($remarkIndex=0;$remarkIndex<count($lastRemarks);$remarkIndex++){
                for($inviteIndex=0;$inviteIndex<count($invites);$inviteIndex++){
                    if($lastRemarks[$remarkIndex]["resume_id"] == $invites[$inviteIndex]["resume_id"]){
                        $invites[$inviteIndex]["remark"] = $lastRemarks[$remarkIndex]["remark"];
                    }
                }
            }
        }

        echo json_encode(array(
            "error"=>false,
            "invites"=>$invites,
            ));
    }

    public function pageDeleteInvites($inPath){
        $urlData = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $ids = base_lib_BaseUtils::getStr($urlData["ids"], "array", null);
        $ids = base_lib_BaseUtils::getIntArrayOrString($ids);
        
        if (base_lib_BaseUtils::nullOrEmpty($ids)) {
        	echo json_encode(array("error"=>"请选择要删除的面试邀请"));
        	return;
        }
        
        //要删除的ids
        $service_invites = new base_service_company_resume_jobinvite();
        $deleteApplies = $service_invites->getInvitesByIDs(implode(",", $ids), "apply_id")->items;

        $result = $service_invites->delete_invite($this->_userid, implode(",", $ids));
        //设置info_job_apply里面的is_company_deleted为删除
        $applyids = array();
        foreach ($deleteApplies as $apply) {
            if(!base_lib_BaseUtils::nullOrEmpty($apply["apply_id"]) && !in_array($apply["apply_id"], $applyids)){
                array_push($applyids, $apply["apply_id"]);
            }
        }
        if(count($applyids)>0) {
	        $applyModel = new base_service_company_resume_apply();
	        $applyModel->SetCompanyDeleteOfIDs($this->_userid,$applyids);        	
        }
        if($result)
            echo json_encode(array("success"=>true));
        else
            echo json_encode(array("error"=>"删除面试邀请失败"));
    }

    /**
     * 加入回收站
     */
    public function pageDel() {
        $inviteIds = base_lib_Utils::getStr(trim($_REQUEST['d'], ','));

        $servInvite = new base_service_company_resume_download();

        if (!$inviteIds) return $this->alert(array('state' => -1, 'msg' => '没有选择需要删除的邀请记录'));

        if (FALSE === $servInvite->delInvite($inviteIds, $this->_userid)) {
            return $this->alert(array('state' => -1, 'msg' => '删除指定邀请记录失败'));
        }

        //...
    }

    // /**
    //  * 邀请测试
    //  * Enter description here ...
    //  * @param unknown_type $inPath
    //  */
    // public function pageInviteTest($inPath){
    // 	$this->_aParams['title'] = '邀请测试';
    // 	return $this->render('invite/inviteTest.html', $this->_aParams);
    // }
    
    /**
     * 判断是否可以邀请
     * Enter description here ...
     * @param unknown_type $inPath
     */
  //   public function pageCheckCanInvite($inPath){
  //   	$service_company = new base_service_company_company();
  //   	$currentCompany = $service_company->getCompany($this->_userid,1,'com_level,end_time,address,linkman,link_tel');
  //   	if(empty($currentCompany)){
  //   		echo header("Content-type:text/plain;charset=utf-8");
		// 	$json['error'] = '请登录';
		// 	echo json_encode($json);
		// 	//var_dump($json);
		// 	return;
		// }
  //   	$com_level = $currentCompany['com_level'];
		// $end_time = $currentCompany['end_time'];
  //   	//判断会员级别
		// if ($com_level <= 1){
		// 	echo header("Content-type:text/plain;charset=utf-8");
		// 	$json['error'] = '贵公司尚未开通服务';
		// 	echo json_encode($json);
		// 	//var_dump($json);
		// 	return;
		// }
		// //判断会员期
		// //var_dump(strtotime(date('Y-m-d', time())),strtotime(date('Y-m-d', strtotime($end_time))));
		// if (empty($end_time) || strtotime(date('Y-m-d', time())) > strtotime(date('Y-m-d', strtotime($end_time)))) {
		// 	echo header("Content-type:text/plain;charset=utf-8");
		// 	$json['error'] = '您的会员已过期';
		// 	echo json_encode($json);
		// 	return;
		// }
		// echo header("Content-type:text/plain;charset=utf-8");
		// $json['success'] = 'success';
		// echo json_encode($json);
		// return;
  //   }

    /**
     * 
     * 能否发送完整短信
     * @param  $companyid
     */
    private function canSendFullMsg($companyid) {
    	$company_service = new base_service_company_company();
		$currentCompany = $company_service->getCompany($companyid, 1, 'com_level');

		$companyLevelService = new base_service_company_level();
    	if (($currentCompany["com_level"] == $companyLevelService->svip["com_level"])
            || ($currentCompany["com_level"] == $companyLevelService->newgold["com_level"]) 
            || ($currentCompany["com_level"] == $companyLevelService->gold["com_level"])) {
			
            return true;
		}

    	$service_comservice = new base_service_company_service_comservice();
		$comservice = $service_comservice->getComService($companyid, 'is_enabled_messagenotice');		

		if (empty($comservice)) {
			return false;
		}

		return $comservice['is_enabled_messagenotice'] == 1 ? true : false;
    }
    
    /**
     * 单个邀请弹窗显示
     * @param  $inPath
     */
    public function pageInviteSingleShow($inPath) {

        if(!$this->canDo("invite_resume")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
        }
        //获取并判断来源：1.主动邀请简历, 2.求职申请并同意
        $path_data   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_id   = base_lib_BaseUtils::getStr($path_data['resumeID'], 'int', 0);
        $apply_id    = base_lib_BaseUtils::getStr($path_data['applyID'], 'int', 0);
        $recommendid = base_lib_BaseUtils::getStr($path_data['recommendid'], 'int', 0);//推荐简历
        $cur_com_id  = base_lib_BaseUtils::getStr($path_data['cur_com_id'], 'int', $this->_userid);
		$this->_aParams['isfromdetail']     = base_lib_BaseUtils::getStr($path_data['isfromdetail'], 'string', 0);//是否来自简历详情
    	
		$service_company = new base_service_company_company();
    	$currentCompany = $service_company->getCompany($this->_userid, 1, 'company_name,company_shortname,com_level,end_time,address,linkman,link_tel,link_mobile');

		$companyLevelService = new base_service_company_level();		
        if ($this->canSendFullMsg($this->_userid)) {
			$this->_aParams["canComplete"] = true ;
		}

		$companyLevelName = $companyLevelService->getName($currentCompany["com_level"]);
        $end_time = $currentCompany['end_time'];

        $this->_aParams["companyLevelName"] = $companyLevelName;
        $this->_aParams['recommendid']      = $recommendid;

        $service_resume     = new base_service_person_resume_resume();
        $service_invitetype = new base_service_company_resume_invitetype();
        $service_apply      = new base_service_company_resume_apply();
        $resumelevelservice = new base_service_common_resumelevel();

        
        // $member_info = $this->getCompanyMemberInfo();
        $this->_aParams['link_man']                 = $currentCompany['linkman'];
        $this->_aParams['link_tel']                 = $currentCompany['link_tel'] ? $currentCompany['link_tel'] : $currentCompany['link_mobile'];
        
        $invite_address                              = $currentCompany["address"];
        $this->_aParams['address_select']           = 'company_address';
        $this->_aParams['company_address']          = str_replace("\r\n","",$currentCompany["address"]);
    	if (!empty($resume_id)) {
            // $resume = $service_resume->getResume($resume_id, 'resume_id,person_id,is_chinese_resume,relate_resume_id,point');
            // $resume['point'] = empty($resume['point']) ? 1 : $resume['point'];

            // $is_apply = $service_apply->isApply($company_resources->all_accounts, $resume_id);

            // /* 获取简历是否下载 */
            // $downloadservice = new base_service_company_resume_download();
            // $downloaded = $downloadservice->isResumeDownloaded($this->_userid, $resume_id);
            // $eff_apply = $service_apply->getMemberCompanyApply($this->_userid, array($resume['person_id']));

            // if (!$is_apply && $member_info == "member" && !$downloaded) {
            //     $this->_aParams['isshowpoint'] = true; 
            //     $this->_aParams['resumelevel'] = $resumelevelservice->getLevelByPoint($resume['point']);
            // }

            // if (empty($downloaded)) {
            //     if ((empty($eff_apply) && $member_info == 'overduemember') || $member_info == 'notmember') {
            //         $this->_aParams['isshowconsume'] = true;
            //         $this->_aParams['resumelevel']   = $resumelevelservice->getLevelByPoint($resume['point']);
            //     }
            // }

    		$invite_type = $service_invitetype->jobinvite;
            $person_id = $service_resume->getResume($resume_id, "person_id")['person_id'];

		} else if (!empty($apply_id)) {
			$apply = $service_apply->getApply($apply_id, 'apply_id,resume_id,person_id,job_id,station,create_time,company_id');
            // if (empty($apply)) {
            //     $this->_aParams['message'] = '获取求职者申请信息失败';
            //     return $this->render('showerror.html', $this->_aParams);
            // }

            // $resume = $service_resume->getResume($apply['resume_id'], 'resume_id,person_id,is_chinese_resume,relate_resume_id');
            // $resume['point'] = empty($resume['point']) ? 1 : $resume['point'];

            // /* 获取简历是否下载 */
            // $downloadservice = new base_service_company_resume_download();
            // $downloaded = $downloadservice->isResumeDownloaded($this->_userid, $resume['resume_id']);

            // $eff_apply = $service_apply->getMemberCompanyApply($this->_userid, array($apply['person_id']));   
            // if (empty($downloaded)) {
            //     if ((empty($eff_apply) && $member_info == 'overduemember') || $member_info == 'notmember') {
            //         $this->_aParams['isshowconsume'] = true;
            //         $this->_aParams['resumelevel']   = $resumelevelservice->getLevelByPoint($resume['point']);
            //     }
            // }
            if(isset($apply["job_id"]) && $apply["job_id"]){
                $service_company_job_job = new base_service_company_job_job();
                $apply_job = $service_company_job_job->getJob($apply["job_id"], "add_info,account_id");
                if(isset($apply_job["add_info"]) && $apply_job["add_info"]){
                    $invite_address = $apply_job["add_info"];
                    if($apply_job["add_info"] != $currentCompany["address"]){
                        $this->_aParams['address_select'] = 'job_address';
                        $this->_aParams['job_address'] = str_replace(PHP_EOL,"",$apply_job["add_info"]);
                    }
                }
                $service_company_account = new base_service_company_account();
                $pub_job_account = $service_company_account->getAccount($apply_job["account_id"], "user_name,mobile_phone,link_tel");
                if(!empty($pub_job_account) && !empty($pub_job_account["user_name"]) && ($pub_job_account["mobile_phone"] || $pub_job_account["link_tel"])){
                    $this->_aParams['link_man'] = $pub_job_account["user_name"];
                    $this->_aParams['link_tel'] = $pub_job_account['mobile_phone'] ? $pub_job_account['mobile_phone'] : $pub_job_account['link_tel'];
                }
            }
            $resume_id  = $apply['resume_id'];
            $cur_com_id = $apply['company_id'];

            $invite_type = $service_invitetype->jobapply;
			$this->_aParams['apply'] = $apply;
		} else {
			$this->_aParams['message'] = '获取求职者信息失败';
			return $this->render('showerror.html', $this->_aParams);
		}

        $company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, base_lib_BaseUtils::getCookie('accountid'));
        $resource_data     = $company_resources->getCompanyServiceSource(["_account_resource", "company_level"]);
        $spread_overage    = $resource_data['spread_overage'];
        $account_overage   = $resource_data['account_overage'];
        list($status, $code, $params) = $company_resources->check($func="download", $params=['resume_id'=>$resume_id]);

        if (!$status && $code != base_service_company_resources_code::DOWNLOAD_NOTENOUGH) {
            $this->_aParams['message'] = !empty($params['msg']) ? $params['msg'] : '获取求职者申请信息失败';
            return $this->render('showerror.html', $this->_aParams);
        }
        $this->_aParams['code'] = $code;
        //此简历3个月内未投递/未下载
        if($code != base_service_company_resources_code::DOWNLOAD_RESUME_DOWNED && $code != base_service_company_resources_code::DOWNLOAD_RESUME_APPLYED){
            $this->_aParams['is_not_downloadORapply'] = true;
        }
        if ($code == base_service_company_resources_code::DOWNLOAD_USE_POINT ) {
            $this->_aParams['isshowpoint'] = true;
            $this->_aParams['resumelevel'] = $params['name'];
            $this->_aParams['resumepoint'] = $params['point'];
        } else if ($code == base_service_company_resources_code::DOWNLOAD_USE_SPREAD) {
            $this->_aParams['isshowspread']  = true;
            $this->_aParams['resumelevel']   = $params['name'];
            $this->_aParams['resumeconsume'] = $params['price'];
        } else if ($code == base_service_company_resources_code::DOWNLOAD_USE_ACCOUNT) {
            $this->_aParams['isshowconsume']  = true;
            $this->_aParams['resumelevel']   = $params['name'];
            $this->_aParams['resumeconsume'] = $params['price'];
        }
        $companyresources = $company_resources->getCompanyServiceSource(['account_resource']);
        $this->_aParams['pricing_resource_data'] = $companyresources; //获取套餐资源
//        $this->_aParams['message']               = $pricing_resource_data['release_point_message']; //剩余短信数

        $service_company_service_servicePricing = new base_service_company_service_servicePricing();
        $this->_aParams['point_message'] = $service_company_service_servicePricing->GetFunParallelismSelling('point_message');//短信数换算推广金
        // if (empty($resume)) {
        //     $this->_aParams['message'] = '简历获取失败';
        //     return $this->render('showerror.html', $this->_aParams);
        // }

        $this->_aParams['invitetype'] = $invite_type;
        $this->_aParams['resumeID']   = $resume_id;
		
        //获取邀请模板
		$service_company_template = new base_service_company_template();
		$company_templates = $service_company_template->getTemplateList($this->_userid, '*');
		$company_templates_json = "{id:\"0\",name:\"同意-面试标准模板\"},";

        // 获取代招的企业
        $com_type    = $company_resources->account_type == 'hr_main' && $invite_type == $service_invitetype->jobinvite;
        $cur_company = $service_company->getCompany($cur_com_id, 1, 'company_name,company_shortname');

        // 公司会员区分
        // $memberinfo = $this->getCompanyMemberInfo();
        $this->_aParams['memberinfo'] = $company_resources->isMember() ? "member" : "not_member";

        $company_service = new base_service_company_company();
        $accounts = $company_service->getCompanys($company_resources->all_accounts, "company_id,company_shortname,company_name");
        $_accounts = [];

        $cur_company_str = $cur_company['company_shortname'] ? "<option value='{$cur_company['company_shortname']}'>{$cur_company['company_shortname']}</option><option value='{$cur_company['company_name']}'>{$cur_company['company_name']}</option>" : "<option value='{$cur_company['company_name']}'>{$cur_company['company_name']}</option>";

        $this->_aParams['cur_company_str'] = $cur_company_str;
        $_datas = array();
        foreach ($accounts as $key => $account) {
            $account['company_name_display']   = $account['company_shortname'] ? $account['company_shortname'] : $account['company_name'];
            $_accounts[$account['company_id']] = $account;

            $_datas[] = ['id' => $account['company_id'], "name" => $account['company_name_display']];
        }

        if($com_type){
            $this_cur_com_id = $_datas[0]['id'];
        }

        $this->_aParams['accounts']      = $_accounts;
        $this->_aParams['accounts_json'] = json_encode($_datas);

        $this->_aParams['this_cur_com_id']         = $this_cur_com_id;
        $this->_aParams['cur_com_id']         = $com_type ? "" : $cur_com_id;
        $this->_aParams['company_resources']  = $company_resources;
        $this->_aParams['select_template_id'] = 0;
        $this->_aParams['company_name']       = $com_type ? "" : $cur_company['company_name'];
		$this->_aParams['company_shortname']  = $com_type ? "" : $cur_company['company_shortname'];
        $this->_aParams['address']            = $invite_address;
        $this->_aParams['remark']             = '';

        if (!empty($company_templates->items)) {
			foreach ($company_templates->items as $item => $v) {
				$company_templates_json = "$company_templates_json{id:\"{$v['template_id']}\",name:\"{$v['name']}\"},";
			}
		}

		$company_templates_json = substr($company_templates_json, 0, strlen($company_templates_json) - 1);
		$this->_aParams['company_templates_json'] = "[$company_templates_json]";

        //获取日期
        $date_json = '';
        $weekarray = array("日", "一", "二", "三", "四", "五", "六");
		for ($i = 0; $i < 15; $i++) {
            $date      = date('Y-m-d', strtotime("+{$i} day"));
            $weekday   = date("w", strtotime("+{$i} day"));
            $date_json = "$date_json{id:\"{$date}\",name:\"{$date}[星期{$weekarray[$weekday]}]\"},";
		}

		//$date_json = "$date_json{id:\"99\",name:\"自定义\"},";
		$date_json = substr($date_json, 0, strlen($date_json) - 1);
		$this->_aParams['date_json'] = "[$date_json]";

        //需要扣除的余额点数
        $servicePricing = new base_service_company_service_servicePricing();
        $account_overage_service_price = $servicePricing->GetFunParallelismSelling('point_dow_resume');
        $this->_aParams['account_overage_service_price'] = $account_overage_service_price;

        
        //简历点
        $this->_aParams['cq_resume_num_release'] = $this->_aParams['pricing_resource_data']['cq_resume_num_release'];
        //余额
        $this->_aParams['account_overage'] = $this->_aParams['pricing_resource_data']['account_overage'];
        //推广金
        $this->_aParams['spread_overage'] = $this->_aParams['pricing_resource_data']['spread_overage'];
	
	    //添加 体验会员套餐--可使用推广金下载 仅限体验会员 2019/11/7 14:22
	    if(in_array($resource_data['company_level'], array (8))) {
		    $this->_aParams['is_spread_overage'] = true;
	    }
        
        //获取招聘顾问
        $domain = $this->GetDomainInfor();
        $companyStateService = new base_service_company_comstate();
        $companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
        $hrManager = $this->GetHRManager($companyState["net_heap_id"]);
        


        $this->_aParams["hasHRManager"] = false;
        if (!is_null($hrManager)) {
            $this->_aParams["hasHRManager"] = true;
            $headPhoto = base_lib_BaseUtils::nullOrEmpty($hrManager["head_photo_url"]) ? $domain["defaultPhoto"] : $hrManager["head_photo_url"];
            $hrManager["head_photo_url"] = $domain["image"] . "/" . $domain["photo"] . "/" . $headPhoto;
            $this->_aParams["hrManager"] = $hrManager;
        }

    	return $this->render('invite/newinviteperson.html', $this->_aParams);
    }

    /**
     * 单个邀请弹窗显示 增加是否发送短信
     * @param  $inPath
     */
    public function pageInviteSingleShowV1($inPath) {

        if(!$this->canDo("invite_resume")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
        }
        //获取并判断来源：1.主动邀请简历, 2.求职申请并同意
        $path_data   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->_aParams['params']     = $path_data;
        $resume_id   = base_lib_BaseUtils::getStr($path_data['resumeID'], 'int', 0);
        $apply_id    = base_lib_BaseUtils::getStr($path_data['applyID'], 'int', 0);
        $recommendid = base_lib_BaseUtils::getStr($path_data['recommendid'], 'int', 0);//推荐简历
        $cur_com_id  = base_lib_BaseUtils::getStr($path_data['cur_com_id'], 'int', $this->_userid);
        $this->_aParams['isfromdetail']     = base_lib_BaseUtils::getStr($path_data['isfromdetail'], 'string', 0);//是否来自简历详情

        $service_company = new base_service_company_company();
        $currentCompany = $service_company->getCompany($this->_userid, 1, 'company_name,company_shortname,com_level,end_time,address,linkman,link_tel,link_mobile');

        $companyLevelService = new base_service_company_level();
        if ($this->canSendFullMsg($this->_userid)) {
            $this->_aParams["canComplete"] = true ;
        }

        $companyLevelName = $companyLevelService->getName($currentCompany["com_level"]);
        $end_time = $currentCompany['end_time'];

        $this->_aParams["companyLevelName"] = $companyLevelName;
        $this->_aParams['recommendid']      = $recommendid;

        $service_resume     = new base_service_person_resume_resume();
        $service_invitetype = new base_service_company_resume_invitetype();
        $service_apply      = new base_service_company_resume_apply();
        $resumelevelservice = new base_service_common_resumelevel();


        // $member_info = $this->getCompanyMemberInfo();
        $this->_aParams['link_man']                 = $currentCompany['linkman'];
        $this->_aParams['link_tel']                 = $currentCompany['link_tel'] ? $currentCompany['link_tel'] : $currentCompany['link_mobile'];

        $invite_address                              = $currentCompany["address"];
        $this->_aParams['address_select']           = 'company_address';
        $this->_aParams['company_address']          = str_replace("\r\n","",$currentCompany["address"]);
        if (!empty($resume_id)) {
            // $resume = $service_resume->getResume($resume_id, 'resume_id,person_id,is_chinese_resume,relate_resume_id,point');
            // $resume['point'] = empty($resume['point']) ? 1 : $resume['point'];

            // $is_apply = $service_apply->isApply($company_resources->all_accounts, $resume_id);

            // /* 获取简历是否下载 */
            // $downloadservice = new base_service_company_resume_download();
            // $downloaded = $downloadservice->isResumeDownloaded($this->_userid, $resume_id);
            // $eff_apply = $service_apply->getMemberCompanyApply($this->_userid, array($resume['person_id']));

            // if (!$is_apply && $member_info == "member" && !$downloaded) {
            //     $this->_aParams['isshowpoint'] = true;
            //     $this->_aParams['resumelevel'] = $resumelevelservice->getLevelByPoint($resume['point']);
            // }

            // if (empty($downloaded)) {
            //     if ((empty($eff_apply) && $member_info == 'overduemember') || $member_info == 'notmember') {
            //         $this->_aParams['isshowconsume'] = true;
            //         $this->_aParams['resumelevel']   = $resumelevelservice->getLevelByPoint($resume['point']);
            //     }
            // }

            $invite_type = $service_invitetype->jobinvite;
            $person_id = $service_resume->getResume($resume_id, "person_id")['person_id'];

        } else if (!empty($apply_id)) {
            $apply = $service_apply->getApply($apply_id, 'apply_id,resume_id,person_id,job_id,station,create_time,company_id');
            // if (empty($apply)) {
            //     $this->_aParams['message'] = '获取求职者申请信息失败';
            //     return $this->render('showerror.html', $this->_aParams);
            // }

            // $resume = $service_resume->getResume($apply['resume_id'], 'resume_id,person_id,is_chinese_resume,relate_resume_id');
            // $resume['point'] = empty($resume['point']) ? 1 : $resume['point'];

            // /* 获取简历是否下载 */
            // $downloadservice = new base_service_company_resume_download();
            // $downloaded = $downloadservice->isResumeDownloaded($this->_userid, $resume['resume_id']);

            // $eff_apply = $service_apply->getMemberCompanyApply($this->_userid, array($apply['person_id']));
            // if (empty($downloaded)) {
            //     if ((empty($eff_apply) && $member_info == 'overduemember') || $member_info == 'notmember') {
            //         $this->_aParams['isshowconsume'] = true;
            //         $this->_aParams['resumelevel']   = $resumelevelservice->getLevelByPoint($resume['point']);
            //     }
            // }
            if(isset($apply["job_id"]) && $apply["job_id"]){
                $service_company_job_job = new base_service_company_job_job();
                $apply_job = $service_company_job_job->getJob($apply["job_id"], "add_info,account_id");
                if(isset($apply_job["add_info"]) && $apply_job["add_info"]){
                    $invite_address = $apply_job["add_info"];
                    if($apply_job["add_info"] != $currentCompany["address"]){
                        $this->_aParams['address_select'] = 'job_address';
                        $this->_aParams['job_address'] = str_replace(PHP_EOL,"",$apply_job["add_info"]);
                    }
                }
                $service_company_account = new base_service_company_account();
                $pub_job_account = $service_company_account->getAccount($apply_job["account_id"], "user_name,mobile_phone,link_tel");
                if(!empty($pub_job_account) && !empty($pub_job_account["user_name"]) && ($pub_job_account["mobile_phone"] || $pub_job_account["link_tel"])){
                    $this->_aParams['link_man'] = $pub_job_account["user_name"];
                    $this->_aParams['link_tel'] = $pub_job_account['mobile_phone'] ? $pub_job_account['mobile_phone'] : $pub_job_account['link_tel'];
                }
            }
            $resume_id  = $apply['resume_id'];
            $cur_com_id = $apply['company_id'];

            $invite_type = $service_invitetype->jobapply;
            $this->_aParams['apply'] = $apply;
        } else {
            $this->_aParams['message'] = '获取求职者信息失败';
            return $this->render('showerror.html', $this->_aParams);
        }

        $company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, base_lib_BaseUtils::getCookie('accountid'));
        $resource_data     = $company_resources->getCompanyServiceSource(["_account_resource", "company_level"]);
        $spread_overage    = $resource_data['spread_overage'];
        $account_overage   = $resource_data['account_overage'];
        list($status, $code, $params) = $company_resources->check($func="download", $params=['resume_id'=>$resume_id]);

        if (!$status && $code != base_service_company_resources_code::DOWNLOAD_NOTENOUGH) {
            $this->_aParams['message'] = !empty($params['msg']) ? $params['msg'] : '获取求职者申请信息失败';
            return $this->render('showerror.html', $this->_aParams);
        }
        $this->_aParams['code'] = $code;
        //此简历3个月内未投递/未下载
        if($code != base_service_company_resources_code::DOWNLOAD_RESUME_DOWNED && $code != base_service_company_resources_code::DOWNLOAD_RESUME_APPLYED){
            $this->_aParams['is_not_downloadORapply'] = true;
        }
        if ($code == base_service_company_resources_code::DOWNLOAD_USE_POINT ) {
            $this->_aParams['isshowpoint'] = true;
            $this->_aParams['resumelevel'] = $params['name'];
            $this->_aParams['resumepoint'] = $params['point'];
        } else if ($code == base_service_company_resources_code::DOWNLOAD_USE_SPREAD) {
            $this->_aParams['isshowspread']  = true;
            $this->_aParams['resumelevel']   = $params['name'];
            $this->_aParams['resumeconsume'] = $params['price'];
        } else if ($code == base_service_company_resources_code::DOWNLOAD_USE_ACCOUNT) {
            $this->_aParams['isshowconsume']  = true;
            $this->_aParams['resumelevel']   = $params['name'];
            $this->_aParams['resumeconsume'] = $params['price'];
        }
        $companyresources = $company_resources->getCompanyServiceSource(['account_resource']);

        $this->_aParams['pricing_resource_data'] = $companyresources; //获取套餐资源
//        $this->_aParams['message']               = $pricing_resource_data['release_point_message']; //剩余短信数

        $service_company_service_servicePricing = new base_service_company_service_servicePricing();
        $this->_aParams['point_message'] = $service_company_service_servicePricing->GetFunParallelismSelling('point_message');//短信数换算推广金
        // if (empty($resume)) {
        //     $this->_aParams['message'] = '简历获取失败';
        //     return $this->render('showerror.html', $this->_aParams);
        // }

        $this->_aParams['invitetype'] = $invite_type;
        $this->_aParams['resumeID']   = $resume_id;

        //获取邀请模板
        $service_company_template = new base_service_company_template();
        $company_templates = $service_company_template->getTemplateList($this->_userid, '*');
        $company_templates_json = "{id:\"0\",name:\"同意-面试标准模板\"},";

        // 获取代招的企业
        $com_type    = $company_resources->account_type == 'hr_main' && $invite_type == $service_invitetype->jobinvite;
        $cur_company = $service_company->getCompany($cur_com_id, 1, 'company_name,company_shortname');

        // 公司会员区分
        // $memberinfo = $this->getCompanyMemberInfo();
        $this->_aParams['memberinfo'] = $company_resources->isMember() ? "member" : "not_member";

        $company_service = new base_service_company_company();
        $accounts = $company_service->getCompanys($company_resources->all_accounts, "company_id,company_shortname,company_name");
        $_accounts = [];

        $cur_company_str = $cur_company['company_shortname'] ? "<option value='{$cur_company['company_shortname']}'>{$cur_company['company_shortname']}</option><option value='{$cur_company['company_name']}'>{$cur_company['company_name']}</option>" : "<option value='{$cur_company['company_name']}'>{$cur_company['company_name']}</option>";

        $this->_aParams['cur_company_str'] = $cur_company_str;
        $_datas = array();
        foreach ($accounts as $key => $account) {
            $account['company_name_display']   = $account['company_shortname'] ? $account['company_shortname'] : $account['company_name'];
            $_accounts[$account['company_id']] = $account;

            $_datas[] = ['id' => $account['company_id'], "name" => $account['company_name_display']];
        }

        if($com_type){
            $this_cur_com_id = $_datas[0]['id'];
        }

        $this->_aParams['accounts']      = $_accounts;
        $this->_aParams['accounts_json'] = json_encode($_datas);

        $this->_aParams['this_cur_com_id']         = $this_cur_com_id;
        $this->_aParams['cur_com_id']         = $com_type ? "" : $cur_com_id;
        $this->_aParams['company_resources']  = $company_resources;
        $this->_aParams['select_template_id'] = 0;
        $this->_aParams['company_name']       = $com_type ? "" : $cur_company['company_name'];
        $this->_aParams['company_shortname']  = $com_type ? "" : $cur_company['company_shortname'];
        $this->_aParams['address']            = $invite_address;
        $this->_aParams['remark']             = '';

        if (!empty($company_templates->items)) {
            foreach ($company_templates->items as $item => $v) {
                $company_templates_json = "$company_templates_json{id:\"{$v['template_id']}\",name:\"{$v['name']}\"},";
            }
        }

        $company_templates_json = substr($company_templates_json, 0, strlen($company_templates_json) - 1);
        $this->_aParams['company_templates_json'] = "[$company_templates_json]";

        //获取日期
        $date_json = '';
        $weekarray = array("日", "一", "二", "三", "四", "五", "六");
        for ($i = 0; $i < 15; $i++) {
            $date      = date('Y-m-d', strtotime("+{$i} day"));
            $weekday   = date("w", strtotime("+{$i} day"));
            $date_json = "$date_json{id:\"{$date}\",name:\"{$date}[星期{$weekarray[$weekday]}]\"},";
        }

        //$date_json = "$date_json{id:\"99\",name:\"自定义\"},";
        $date_json = substr($date_json, 0, strlen($date_json) - 1);
        $this->_aParams['date_json'] = "[$date_json]";

        //需要扣除的余额点数
        $servicePricing = new base_service_company_service_servicePricing();
        $account_overage_service_price = $servicePricing->GetFunParallelismSelling('point_dow_resume');
        $this->_aParams['account_overage_service_price'] = $account_overage_service_price;


        //简历点
        $this->_aParams['cq_resume_num_release'] = $this->_aParams['pricing_resource_data']['cq_resume_num_release'];
        //余额
        $this->_aParams['account_overage'] = $this->_aParams['pricing_resource_data']['account_overage'];
        //推广金
        $this->_aParams['spread_overage'] = $this->_aParams['pricing_resource_data']['spread_overage'];

        //添加 体验会员套餐--可使用推广金下载 仅限体验会员 2019/11/7 14:22
        if(in_array($resource_data['company_level'], array (8))) {
            $this->_aParams['is_spread_overage'] = true;
        }

        //获取招聘顾问
        $domain = $this->GetDomainInfor();
        $companyStateService = new base_service_company_comstate();
        $companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
        $hrManager = $this->GetHRManager($companyState["net_heap_id"]);


        $this->_aParams["hasHRManager"] = false;
        if (!is_null($hrManager)) {
            $this->_aParams["hasHRManager"] = true;
            $headPhoto = base_lib_BaseUtils::nullOrEmpty($hrManager["head_photo_url"]) ? $domain["defaultPhoto"] : $hrManager["head_photo_url"];
            $hrManager["head_photo_url"] = $domain["image"] . "/" . $domain["photo"] . "/" . $headPhoto;
            $this->_aParams["hrManager"] = $hrManager;
        }

        return $this->render('invite/newinvitepersonv1.html', $this->_aParams);
    }
    private function GetDomainInfor() {
        $domain = array (
            'image'        => '',
            'photo'        => '',
            'defaultPhoto' => ''
        );
        $xml = SXML::load('../config/config.xml');
        if (is_null($xml)) {
            return $domain;
        }
        $domain["image"] = $xml->ImgDomain;
        $domain["photo"] = $xml->CqjobSysUserHeadPhoto;
        $domain["defaultPhoto"] = $xml->CqjobSysUserDefaultHeadPhoto;

        return $domain;
    }

    private function GetHRManager($heap_id) {
        $companyHeapService = new base_service_company_netheap();
        $companyHeap = $companyHeapService->GetNetHeapByID($heap_id, "own_man");
        $userInfor = null;
        if (is_null($companyHeap) || !isset($companyHeap["own_man"])) {
            return $userInfor;
        }
        $userService = new base_service_crm_user();
        $userInfor = $userService->GetUsers($companyHeap['own_man'], "user_name,head_photo_url,tel,mobile,qq");

        return $userInfor;
    }

    //获取公司名称
    public function pageGetCompanyNames($inPath){
        $path_data   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        

        $accountid = base_lib_BaseUtils::getCookie('accountid');
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);

        $company_id = base_lib_BaseUtils::getStr($path_data['company_id'],'string','');
        // 公司会员区分
        // $memberinfo = $this->getCompanyMemberInfo();
        $this->_aParams['memberinfo'] = $company_resources->isMember() ? "member" : "not_member";

        $company_service = new base_service_company_company();
        $accounts = $company_service->getCompanys($company_resources->all_accounts, "company_id,company_shortname,company_name");
        $temp_str = "";
        foreach ($accounts as $key => $account) {
            if($company_id == $account['company_id']){
                $temp_str .= $account['company_shortname'] ? "<option value='{$account['company_shortname']}'>{$account['company_shortname']}</option><option value='{$account['company_name']}'>{$account['company_name']}</option>" : "<option value='{$account['company_name']}'>{$account['company_name']}</option>";
                break;
            }
        }
        echo json_encode($temp_str);
    }

	/**
     * 批量邀请弹窗显示
     * Enter description here ...
     * @param unknown_type $inPath
     */
    public function pageInviteMultiShow($inPath) {
    	$service_company = new base_service_company_company();
    	$currentCompany = $service_company->getCompany($this->_userid, 1, 'company_name,com_level,end_time,address,linkman,link_tel,link_mobile');
    	if (empty($currentCompany)) {
			$this->_aParams['message'] = '请登录';
			return $this->render('showerror.html', $this->_aParams);
		}

    	$com_level = $currentCompany['com_level'];
		$end_time = $currentCompany['end_time'];
    	//判断会员级别
		if ($com_level <= 1){
			$this->_aParams['message'] = '贵公司尚未开通服务';
			return $this->render('showerror.html', $this->_aParams);
		}
		//判断会员期
		//var_dump(strtotime(date('Y-m-d', time())),strtotime(date('Y-m-d', strtotime($end_time))));
		if (empty($end_time) || strtotime(date('Y-m-d', time())) > strtotime(date('Y-m-d', strtotime($end_time)))) {
			$this->_aParams['message'] = '您的会员已过期';
			return $this->render('showerror.html', $this->_aParams);
		}
		$companyLevelService = new base_service_company_level();

    	if($this->canSendFullMsg($this->_userid)) {
			$this->_aParams["canComplete"] = true ;
		}
		$companyLevelName = $companyLevelService->getName($currentCompany["com_level"]);
		$this->_aParams["companyLevelName"] = $companyLevelName;
    	
        //判断公司发出的邀请是否已满
		//获取并判断来源：
		//1.主动邀请简历
		//2.求职申请并同意
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_ids = base_lib_BaseUtils::getStr($path_data['resumeids'],'array',null);
		$apply_ids = base_lib_BaseUtils::getStr($path_data['applyids'],'array',null);
		$service_resume = new base_service_person_resume_resume();
		$service_invitetype = new base_service_company_resume_invitetype();
		$invite_type = $service_invitetype->jobinvite;
		$resume_arr = null;
		$apply_arr = null;
		if (!empty($resume_ids)) {
			$resume_arr = $service_resume->getResumes($resume_ids, 'resume_id,person_id,is_chinese_resume,relate_resume_id,point');

			if (empty($resume_arr) || count($resume_arr->items) == 0) {
				$this->_aParams['message'] = '简历获取失败';
				return $this->render('showerror.html', $this->_aParams);
			}
		}elseif (!empty($apply_ids)) {
			$service_apply = new base_service_company_resume_apply();
			$apply_arr = $service_apply->getApplys($apply_ids, 'apply_id,resume_id,job_id,station');
			if (empty($apply_arr) || count($apply_arr->items) == 0) {
				$this->_aParams['message'] = '获取求职者申请信息失败';
				return $this->render('showerror.html', $this->_aParams);
			}
			$resume_ids = $this->getPropertys($apply_arr->items, 'resume_id');
			$resume_arr = $service_resume->getResumes($resume_ids, 'resume_id,person_id,is_chinese_resume,relate_resume_id');
			foreach ($apply_arr->items as $theitem=>$thevalue){
				$the_resume = $this->arrayFind($resume_arr->items, 'resume_id', $thevalue['resume_id']);
				$apply_arr->items[$theitem]['person_id'] = $the_resume['person_id'];
				$apply_arr->items[$theitem]['is_chinese_resume'] = $the_resume['is_chinese_resume'];
				$apply_arr->items[$theitem]['relate_resume_id'] = $the_resume['relate_resume_id'];
			}
			if (empty($apply_arr) || count($apply_arr->items) == 0) {
				$this->_aParams['message'] = '获取求职者申请信息失败';
				return $this->render('showerror.html', $this->_aParams);
			}
			$invite_type = $service_invitetype->jobapply;
		}else {
			$this->_aParams['message'] = '获取求职者信息失败';
			return $this->render('showerror.html', $this->_aParams);
		}
		$this->_aParams['invitetype'] = $invite_type;
    	//封装简历编号和求职者姓名数组
    	if ($this->_aParams['invitetype'] == $service_invitetype->jobinvite) {
    		foreach ($resume_arr->items as $item=>$value){
				$user_name = $this->getUserName($value);
				$resume_arr->items[$item]['user_name'] = $user_name;
			}
			$this->_aParams['resume_arr'] = $resume_arr->items;
    	}else if ($this->_aParams['invitetype'] == $service_invitetype->jobapply) {
    		foreach ($apply_arr->items as $item=>$value){
				$user_name = $this->getUserName($value);
				$apply_arr->items[$item]['user_name'] = $user_name;
			}
			$this->_aParams['resume_arr'] = $apply_arr->items;
    	}
    	//var_dump($this->_aParams['resume_arr']);
    	//如果是同意面试则通过投递职位名称进行分组
    	if ($this->_aParams['invitetype'] == $service_invitetype->jobapply) {
    		$job_id_station_count_arr = array();
    		for ($j = 0; $j < count($this->_aParams['resume_arr']); $j++) {
    			$add_already = true;
    			for ($j2 = 0; $j2 < count($job_id_station_count_arr); $j2++) {
    				if ($job_id_station_count_arr[$j2]['job_id'] == $this->_aParams['resume_arr'][$j]['job_id']) {
    					$add_already = true;
    					break;
    				}else{
    					$add_already = false;
    				}
    			}
    			if (count($job_id_station_count_arr) == 0 || $add_already == false) {
    				array_push($job_id_station_count_arr, array('job_id'=>$this->_aParams['resume_arr'][$j]['job_id'], 'station'=>$this->_aParams['resume_arr'][$j]['station'], 'count'=>1));
    			}else{
    				for ($k2 = 0; $k2 < count($job_id_station_count_arr); $k2++) {
    					if ($job_id_station_count_arr[$k2]['job_id'] == $this->_aParams['resume_arr'][$j]['job_id']) {
    						$job_id_station_count_arr[$k2]['count'] = $job_id_station_count_arr[$k2]['count'] + 1;
    					}
    				}
    			}
    		}
    		//var_dump($job_id_station_count_arr);
    		$desc_job_id_station_count_arr = $this->__arraySort($job_id_station_count_arr,'count','desc');
    		$new_desc_job_id_station_count_arr = array_values($desc_job_id_station_count_arr);
    		//var_dump($new_desc_job_id_station_count_arr);
    		$this->_aParams['job_id_station_count_arr'] = $new_desc_job_id_station_count_arr;
    		$this->_aParams['default_choose_station'] = $new_desc_job_id_station_count_arr[0]['station'];
    		$this->_aParams['default_choose_jobid'] = $new_desc_job_id_station_count_arr[0]['job_id'];
    	}
		//获取邀请模板
		$service_company_template = new base_service_company_template();
		$company_templates = $service_company_template->getTemplateList($this->_userid, '*');
		$company_templates_json = "{id:\"0\",name:\"同意-面试标准模板\"},";
		$this->_aParams['select_template_id'] = 0;
		$this->_aParams['company_name'] = $currentCompany['company_name'];
		$this->_aParams['address'] = $currentCompany['address'];
		$this->_aParams['link_man'] = $currentCompany['linkman'];
		$this->_aParams['link_tel'] = $currentCompany['link_tel'] ? $currentCompany['link_tel'] : $currentCompany['link_mobile'];
		$this->_aParams['remark'] = '';
		if (!empty($company_templates->items)) {
			foreach ($company_templates->items as $item=>$v){
				$company_templates_json = "$company_templates_json{id:\"{$v['template_id']}\",name:\"{$v['name']}\"},";
			}
		}
		$company_templates_json = substr($company_templates_json, 0, strlen($company_templates_json)-1);
		$this->_aParams['company_templates_json'] = "[$company_templates_json]";
		//获取日期
		$date_json = '';
		$weekarray=array("日","一","二","三","四","五","六");
		for ($i = 0; $i < 10; $i++) {
			$date = date('Y-m-d', strtotime("+{$i} day"));
			$weekday = date("w",strtotime("+{$i} day"));
			$date_json = "$date_json{id:\"{$date}\",name:\"{$date}[星期{$weekarray[$weekday]}]\"},";
		}
		$date_json = "$date_json{id:\"99\",name:\"自定义\"},";
		$date_json = substr($date_json, 0, strlen($date_json)-1);
		$this->_aParams['date_json'] = "[$date_json]";

    	return $this->render('invite/newinvitepersonmulti.html', $this->_aParams);
    }
    
	/**
	 * 二维数组根据某值排序
	 * Enter description here ...
	 * @param $arr
	 * @param $keys
	 * @param $type
	 */
	private function __arraySort($arr,$keys,$type='asc'){
		$keysvalue = $new_array = array();
		foreach ($arr as $k=>$v){
			$keysvalue[$k] = $v[$keys];
		}
		if($type == 'asc'){
			asort($keysvalue);
		}else{
			arsort($keysvalue);
		}
		reset($keysvalue);
		foreach ($keysvalue as $k=>$v){
			$new_array[$k] = $arr[$k];
		}
		return $new_array;
	}

	/**
	 *
	 * 获取数组里对象的属性集合
	 * @param array $arr  对象数组
	 * @param string $property  属性
	 */
	private function getPropertys($arr,$property) {
	   $peropertys = array();
	   foreach ($arr as $item){
	   	 array_push($peropertys, $item[$property]);
	   }
	   return $peropertys;
    }
   
	/**
     * 
     * 数组查询
     * @param array $arr
     * @param string $key
     * @param string $value
     */
    private function arrayFind($arr,$property,$value) {
   	   foreach ((array)$arr as $item){
	   	  if($item[$property]==$value) {
	   	  	  return $item;
	   	  }
	   }
	    return  null;
    }

    /**
     * 获取邀请模板
     * Enter description here ...
     * @param $inPath
     */
    public function pageGetTemplate($inPath) {
    	$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

        $template_id = base_lib_BaseUtils::getStr($path_data['templateId'], 'int', 0);
    	if (!empty($template_id)) {
	    	$service_company_template = new base_service_company_template();
			$template = $service_company_template->getTemplate($template_id, '*');
			if (!empty($template)) {
                $json['address']  = $template['address'];
                $json['link_man'] = $template['link_man'];
                $json['link_tel'] = $template['link_tel'];
                $json['remark']   = $template['remark'];
				echo json_encode($json);
				return;
			}
    	}

    	$service_company = new base_service_company_company();
    	$currentCompany = $service_company->getCompany($this->_userid, 1, 'address,linkman,link_tel');
	    if (empty($currentCompany)) {
			$json['error'] = '获取邀请模板失败';
			echo json_encode($json);
			return;
		}

        $json['address']  = $currentCompany['address'];
        $json['link_man'] = $currentCompany['linkman'];
        $json['link_tel'] = $currentCompany['link_tel'];
        $json['remark']   = '';

		echo json_encode($json);
		return;
    }

  //   public function pageGetPreviewSms($inPath){
  //   	$service_company = new base_service_company_company();
  //   	$company = $service_company->getCompany($this->_userid, 1, 'company_name');
		// if (empty($company)){
		// 	echo header("Content-type:text/plain;charset=utf-8");
		// 	$json['error'] = '请登录';
		// 	echo json_encode($json);
		// 	return;
		// }
		// $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		// //简历名称
		// $invite['user_name'] = base_lib_BaseUtils::getStr($path_data['userName'],'string','');
		// //职位名称
		// $invite['station'] = base_lib_BaseUtils::getStr($path_data['txtStation'],'string','');
		// //面试时间
		// $date = base_lib_BaseUtils::getStr($path_data['hddDate'],'string','');
		// $time = base_lib_BaseUtils::getStr($path_data['hddtime'],'string','');
		// $date = "$date $time:00";
		// $invite['audition_time'] = $date;
		
  //   	$sms_item_arr = array();
		// if (mb_strlen($invite['user_name'])>4) {
		// 	$sms_item_arr['resume_name'] = $invite['user_name'];
		// }else {
		// 	$sms_item_arr['resume_name'] = $invite['user_name'].'，';
		// }
		// $com_name = trim($company['company_name']);
		// if (strpos($com_name, '重庆市') === 0) {
		// 	$com_name = str_replace('重庆市', '', $com_name);
		// }elseif (strpos($com_name, '重庆') === 0 && strpos($com_name, '重庆大学') !== 0 && strpos($com_name, '重庆联盛') !== 0){
		// 	$com_name = str_replace('重庆', '', $com_name);
		// }
		// if (strpos($com_name, '有限责任公司') !== false) {
		// 	$com_name = str_replace('有限责任公司', '', $com_name);
		// }elseif (strpos($com_name, '有限公司') !== false){
		// 	$com_name = str_replace('有限公司', '', $com_name);
		// }
		// $sms_item_arr['company_name'] = $com_name;
		// $m = (int)(date('m', strtotime($invite['audition_time'])));
		// $d = (int)(date('d', strtotime($invite['audition_time'])));
		// $H = (int)(date('H', strtotime($invite['audition_time'])));
		// if (date('i', strtotime($invite['audition_time'])) == '00') {
		// 	$sms_item_arr['invite_time'] = "{$m}月{$d}日 {$H}点";
		// }else {
		// 	$i = (int)(date('i', strtotime($invite['audition_time'])));
		// 	$sms_item_arr['invite_time'] = "{$m}月{$d}日 {$H}:$i";
		// }
		// if (mb_strlen($invite['station']) >10) {
		// 	$sms_item_arr['invite_station'] = mb_substr($invite['station'], 0, 10);
		// }else {
		// 	$sms_item_arr['invite_station'] = $invite['station'];
		// }
		// $sms_content = base_lib_TemplateUtil::get_content('../config/company/ApplySmsContent.template', $sms_item_arr);
		// $sms_length = mb_strlen($sms_content);
		// if ($sms_length > 60) {
		// 	$need_del_len = $sms_length - 60;
		// 	$sms_item_arr['company_name'] = mb_substr($com_name, 0, mb_strlen($com_name)-$need_del_len);
		// 	$sms_content = base_lib_TemplateUtil::get_content('../config/company/ApplySmsContent.template', $sms_item_arr);
		// }
		// $this->_aParams['sms_content'] = $sms_content;
		// return $this->render('invite/previewsms.html', $this->_aParams);
  //   }

    /**
     * 获取建议搜索职位
     * Enter description here ...
     * @param unknown_type $inPath
     */
    public function pageGetSuggestJobs($inPath) {
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $station    = base_lib_BaseUtils::getStr($path_data['q'], 'string', '');
        $company_id = base_lib_BaseUtils::getStr($path_data['company_id'], 'string', '');

        $job_status  = new base_service_common_jobstatus();
        $service_job = new base_service_company_job_job();
    	$job_list = $service_job->getJobList($company_id, $station, $job_status->use, 'job_id,station');

        $job_json = array();
    	if (!empty($job_list)) {
    		foreach ($job_list as $item) {
                $job['jobname'] = $item['station'];
                $job['id']      = $item['job_id'];
    			array_push($job_json, $job);
    		}
    	}

    	echo json_encode($job_json);
    	return;
    }

    /**
     * 获取求职者姓名
     * Enter description here ...
     * @param unknown_type $resume
     */
    protected function getUserName($resume){
    	$name = '[未公开]';
    	$service_person = new base_service_person_person();
    	$person = $service_person->getPerson($resume['person_id'], 'user_name,name_open,user_name_en');
    	if (empty($person)) {
    		return $name;
    	}
    	if ($person['name_open'] == '1') {
    		$name = base_lib_BaseUtils::htmlEncode($resume['is_chinese_resume'] == '1' ? ((empty($person['user_name']) ? '' : $person['user_name'])) : ((empty($person['user_name_en']) ? '' : $person['user_name_en'])));
    	}
    	else{
    		if ($this->isApply($this->_userid, $resume)){
    			$name = base_lib_BaseUtils::htmlEncode($resume['is_chinese_resume'] == '1' ? ((empty($person['user_name']) ? '' : $person['user_name'])) : ((empty($person['user_name_en']) ? '' : $person['user_name_en'])));
    		}
    	}
    	return $name;
    }

    /**
     * 判断简历是否申请了本单位的职位
     * Enter description here ...
     * @param unknown_type $company_id
     * @param unknown_type $resume
     */
    protected function isApply($company_id,$resume){
    	$service_job_apply = new base_service_company_resume_apply();
    	$apply_count = $service_job_apply->getJobApplyCountByResumeID($company_id,$resume['resume_id']);
    	// var_dump($apply_count);
    	$apply_count += $service_job_apply->getJobApplyCountByResumeID($company_id,$resume['relate_resume_id']);
    	if ($apply_count>0) {
    		return true;
    	}
    	return false;
    }

    /**
     * 发送单个邀请
     * Enter description here ...
     * @param $inPath
     */
    public function pageSendInviteSingle($inPath) {
		
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $validator = new base_lib_Validator();
        $invite['resume_id'] = base_lib_BaseUtils::getStr($path_data['hddResumeID'], 'int', 0);
        $cur_company_id      = base_lib_BaseUtils::getStr($path_data['hddComId'], 'int', $this->_userid);
       
        //单位名称
        $sms_company_name = $validator->getNotNull($path_data['txtCompanyname'],'请输入单位名称');
        $validator->getStr($path_data['txtCompanyname'], 1, 20, '单位名称请输入1-20个字');
        
        //职位名称
        $station = $validator->getNotNull($path_data['txtStation'], '请输入职位名称');
        $validator->getStr($path_data['txtStation'], 1, 15, '职位名称请输入1-15个字');

        $invite['station'] = $station;
        //职位ID
        $invite['job_id']  = base_lib_BaseUtils::getStr($path_data['hddJobID'], 'int', 0);

   		$service_company = new base_service_company_company();
		$current_company = $service_company->getCompany($this->_userid, 1, 'company_id,com_level,end_time,company_flag,company_name');
		if (empty($current_company)) {
			echo header("Content-type:text/plain;charset=utf-8");
			$json['error'] = '请登录';
			echo json_encode($json);
			return;
		}

        /* 获取会员信息 */
        $member_info = $this->getCompanyMemberInfo();

		//简历编号
		if (empty($invite['resume_id'])) {
			array_push($validator->err, '邀请简历编号获取失败');
			$validator->has_err = true;
		}

        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,base_lib_BaseUtils::getCookie('accountid'));
        $resource_data = $company_resources->getCompanyServiceSource(["_account_resource"]);
        $spread_overage = $resource_data['spread_overage'];
        $account_overage = $resource_data['account_overage'];
        $service_job = new base_service_company_job_job();
		$current_job = $service_job->getJob($invite['job_id'], 'company_id,end_time');

        if (!in_array($cur_company_id, $company_resources->all_accounts))
            $validator->addErr("获取信息失败");

        //邀请类型
		$service_invitetype = new base_service_company_resume_invitetype();
		$invite['invite_type'] = $validator->getEnum($path_data['hddInviteType'], $service_invitetype->getInvitetype(), '邀请类型错误');

        //面试时间
		$date = $validator->getNotNull($path_data['hddDate'], '请设置面试时间');
		$sms_time = '';
		if ($date == '99') {
			$invite['audition_time'] = $validator->getNotNull($path_data['txtCustomTime'], '请设置面试时间');
			$sms_time = $validator->getStr($path_data['txtCustomTime'], 1, 20, '面试时间请输入1-20个字');
		} else {
			$time = $validator->getNotNull($path_data['hddtime'], '请设置面试时间');
			$time_value = explode(':', $time);
			if (mb_strlen($time_value[0]) == 1) {
			    $time = '0' . $time;
            }
			$temp_date = "{$date} {$time}:00";
            if(strtotime($temp_date) < $this->_time)
                $validator->addErr("面试时间不能早于当前时间");
            
			$invite['audition_time'] = $validator->getDatetime($temp_date, '请设置面试时间', false);
			
            $date_arr = explode('-', $date);
            $sms_time = str_replace('00', '', str_replace(':', '点', $time));
			if (count($date_arr) > 2) {
				$sms_time = (int)$date_arr[1] . '月' . (int)$date_arr[2] . '日' . $sms_time;
			}
		}

		//面试地点
		$invite['audition_address'] = $validator->getNotNull($path_data['txtAddress'], '请输入面试地点');
		$validator->getStr($path_data['txtAddress'], 1, 60, '面试地点请输入1-60个字符');

		//联系人
		$invite['audition_link_man'] = $validator->getNotNull($path_data['txtLinkman'], '请输入联系人');
		$validator->getStr($path_data['txtLinkman'], 1, 15,'联系人请输入1-15个字符');
		
        //联系电话
		$invite['audition_link_tel'] = $validator->getTel($path_data['txtLinktel'], '联系电话不正确', false);
		
        //其他
		$invite['audition_remark'] = $validator->getStr($path_data['txtRemark'], 0, 200, '其他信息不能超过200个字', true);

    	//短信类型
    	$sms_type = base_lib_BaseUtils::getStr($path_data['smsType'], 'int', 1);
    	if ($sms_type != 1 && $sms_type != 2) {
    		$sms_type = 1;
    	}

    	if ($sms_type == 2) {
    		$companyLevelService = new base_service_company_level();
    		if (!$this->canSendFullMsg($this->_userid)) {
    			$sms_type = 1;
    		}
    	}


        //判断该投递记录是否为快米投递
        $service_company_resume_applyresource = new base_service_company_resume_applyresource();
        $service_common_applyresource = new base_service_common_applyresource();
        $apply_service = new base_service_blue_person_apply();
        $service_blue_company_company = new base_service_blue_company_company();
        $blue_apply_id = 0;
        if($invite['invite_type'] == $service_invitetype->jobapply){
            $apply_id_temp = base_lib_BaseUtils::getStr($path_data['hddApplyID'], 'int', 0);
            if($apply_id_temp){
                $apply_resource_info = $service_company_resume_applyresource->getApplyListById($apply_id_temp);
                if($apply_resource_info && $apply_resource_info['resouce_type'] == $service_common_applyresource->kuaimi){
                    $blue_apply_id = $apply_resource_info['relevance_id'];
                    //快米投递的，则将公司名称和职位替换成关联企业的名称及职位
                    $blue_apply = $apply_service->getApplyById($blue_apply_id,'apply_id,job_id,company_id,station');
                    $blue_company_info = $service_blue_company_company->getCompany($blue_apply['company_id'],1,'company_id,company_name');
                    if($blue_company_info && $blue_company_info['company_name']){
                        $sms_company_name = $blue_company_info['company_name'];
                    }
                    if($blue_apply['station']){
                        $station = $blue_apply['station'];
                    }
                }
            }

        }

        //生成短信内容
        $sms_content = $this->createSmsContent($sms_type, $sms_company_name, $station, $sms_time,
            $invite['audition_address'], $invite['audition_link_man'], $invite['audition_link_tel'], $invite['audition_remark']);

        $shorturldomain_len = 16;
        $key_length         = 5;

    	$xml = SXML::load('../config/company/company.xml');
 		if (!is_null($xml)) {
 			$shorturldomain_len = mb_strlen($xml->ShortUrlDomain);
 		}

 		$total_len = mb_strlen($sms_content) + $shorturldomain_len + $key_length + mb_strlen('详情');
    	if ($sms_type == 1 && $total_len > 60) {
    		array_push($validator->err, '已超出' . ($total_len-60) . '个字，无法发送短信，请删除部分内容');
			$validator->has_err = true;
    	} elseif ($sms_type == 2 && mb_strlen($sms_content) > 100) {
    		array_push($validator->err, '已超出' . (mb_strlen($sms_content)-100) . '个字，无法发送短信，请删除部分内容');
			$validator->has_err = true;
    	}
    	
    	if ($validator->has_err) {
    		echo header("Content-type:text/plain;charset=utf-8");
			echo $validator->toJsonWithHtml();
			return;
    	}
    	
    	//验证单位相关信息
        $com_level = $current_company['com_level'];
        $end_time  = $current_company['end_time'];

		//判断会员期
		$service_apply = new base_service_company_resume_apply();

		$apply = null;
		
        //单位编号
		$invite['company_id'] = $cur_company_id;
		
        //邀请时间
		$invite['create_time'] = date('Y-m-d H:i:s', time());

		if ($invite['invite_type'] == $service_invitetype->jobinvite) {
			//截至时间
			$invite['end_time'] = date('Y-m-d H:i:s', strtotime ('+7 day'));
			//面试邀请求职者状态
			$service_jobinviterestatus = new base_service_company_resume_jobinviterestatus();
			$invite['re_status'] = $service_jobinviterestatus->notreply;
		} else if ($invite['invite_type'] == $service_invitetype->jobapply) {
			$invite['apply_id'] = base_lib_BaseUtils::getStr($path_data['hddApplyID'], 'int', 0);

			if ($invite['apply_id'] == 0) {
                $json['error'] = '获取求职者申请信息失败';
				echo header("Content-type:text/plain;charset=utf-8");
				echo json_encode($json);
				return;
			}
			
            $apply = $service_apply->getApply($invite['apply_id'], 'apply_id,re_time,re_status,is_read_com_reply,person_id,job_id,company_id,create_time,is_company_deleted,person_remark,person_re_status,is_cancelled');
			
            if (!empty($apply)) {
				if ($apply['is_company_deleted'] == '1') {
                    $json['error'] = '该求职申请已删除，邀请失败';
					echo header("Content-type:text/plain;charset=utf-8");
					echo json_encode($json);
					return;
				}

				if ($apply['is_cancelled'] == '1') {
                    $json['error'] = '求职者已撤销申请，邀请失败';
					echo header("Content-type:text/plain;charset=utf-8");
					echo json_encode($json);
					return;
				}

				$service_jobapplyrestatus = new base_service_person_jobapplyrestatus();
				$invite['re_status'] = $service_jobapplyrestatus->interview;
			} else {
				echo header("Content-type:text/plain;charset=utf-8");
				$json['error'] = '获取求职者申请信息失败';
				echo json_encode($json);
				return;
			}
			//职位截至时间
			if (!empty($current_job)) {
				$invite['end_time'] = $current_job['end_time'];
			} else {
				$invite['end_time'] = date('Y-m-d H:i:s', strtotime('+7 day'));
			}
		}

		//是否有效
		$invite['is_effect'] = 1;
		//求职者是否已阅读
		$invite['is_readed'] = 0;

		//面试结果
		$service_auditionresult = new base_service_company_resume_auditionresult();
		$invite['audition_result'] = $service_auditionresult->notset;

		//获取简历
		$service_resume = new base_service_person_resume_resume();
		$resume = $service_resume->getResume($invite['resume_id'], 'resume_id,person_id');

		if (empty($resume)) {
            $json['error'] = '简历获取失败';
			echo header("Content-type:text/plain;charset=utf-8");
			echo json_encode($json);
			//var_dump($json);
			return;
		}

		$invite['person_id'] = $resume['person_id'];
		//获取求职者
		$service_person = new base_service_person_person();
		$person = $service_person->getPerson($resume['person_id'], 'person_id,user_name,user_id,email,mobile_phone,person_class,password,open_mode');
    	if (empty($person)) {
            $json['error'] = '个人信息获取失败';
			echo header("Content-type:text/plain;charset=utf-8");
			echo json_encode($json);
			//var_dump($json);
			return;
		}
        $service_download = new base_service_company_resume_download();
		if(!$service_apply->isApply($company_resources->all_accounts, $invite['resume_id']) && $person['open_mode']!=1){
            $downloads = $service_download->getResumeDownload($this->_userid,$resume['resume_id'],'down_time');
            if (round((strtotime($this->now())-strtotime($downloads['down_time']))/3600) > 24) { //下载时间在24小时内，企业任可以查看
                $json['error'] = '对不起，该简历未投递或者简历未公开';
                echo header("Content-type:text/plain;charset=utf-8");
                echo json_encode($json);
                //var_dump($json);
                return;
            }
		}
		$invite['person_id'] = $person['person_id'];

		//获取IP
		$ip = base_lib_BaseUtils::getIp(0);

        $is_cq_setmeal = $company_resources->getCompanyServiceSource(['account_resource', 'company_level']); //获取套餐资源
        $point  = base_lib_BaseUtils::getStr($path_data['point'], 'int', 0);

        /* 余额 */
        $account_overage = $is_cq_setmeal['account_overage'];        //此简历3个月内未投递/未下载 ,扣除简历点
        $is_apply = $service_apply->isApply($company_resources->all_accounts, $invite['resume_id']);
        $download_info = $service_download->getResumeDownload( $company_resources->main_company_id, $invite['resume_id'],'down_time');

        //余额扣除点数
        $servicePricing = new base_service_company_service_servicePricing();
        $account_overage_service_price = $servicePricing->GetFunParallelismSelling('point_dow_resume');
        
        //推广金
        $account_spread_overage = $is_cq_setmeal['spread_overage']; //推广金余额
	    

        if ($is_cq_setmeal['isCqNewService'] && !$is_apply && !$download_info){
	        $tmp_status = true;
            //判断简历点是否足够
	        if($is_cq_setmeal['cq_resume_num_release'] < 1 && $account_overage < $account_overage_service_price) {
		        if(in_array($is_cq_setmeal['company_level'], array (8))) {
			        if($spread_overage + $account_overage < $account_overage_service_price) {
				        $tmp_status = false;
			        }
		        }
		        else {
			        $tmp_status = false;
		        }
	        }
	        if(!$tmp_status) {
                echo header("Content-type:text/plain;charset=utf-8");
                if($is_cq_setmeal['resource_type'] == 2){
                    $json['error'] = '简历点和余额不足，请联系主账号为您分配更多资源！';
                }else{
                    $json['error'] = '简历点/余额/推广金不足，请联系招聘顾问进行购买！';
                }

                echo json_encode($json);
                return;
            }
        }
        $point_message = $servicePricing->GetFunParallelismSelling('point_message');

        /* 推广金 */
        $spread_effect_price = $is_cq_setmeal['spread_overage'];
        if ($is_cq_setmeal['isCqNewService']){
            if ($is_cq_setmeal['cq_release_point_message'] < 1){ //剩余短信点少于发送点
//                if ($point_message >($spread_effect_price + $account_overage)){
//                    if($is_cq_setmeal['resource_type'] == 2){
//                        $validator->addErr("短信数/推广金不足,请联系主账号为您分配更多资源!");
//                    }else{
//                        $validator->addErr("短信数/推广金/余额不足");
//                    }
//
//                    echo header("Content-type:text/plain;charset=utf-8");
//                    echo $validator->toJsonWithHtml();
//                    return;
//                }
            }
        }

		//发送邀请
		$add_result = false;
		$service_invite = new base_service_company_resume_jobinvite();

        $cur_company = $service_company->getCompany($cur_company_id, 1, "company_name,company_shortname,company_id,company_flag");
        if ($invite['invite_type'] == $service_invitetype->jobinvite) {
            $add_result = $service_invite->initiativeInvite(base_lib_BaseUtils::getCookie('accountid'), $sms_type, $sms_content, $cur_company, $resume, $ip, $invite, $person, $apply = null);
        } else if ($invite['invite_type'] == $service_invitetype->jobapply) {
            $add_result = $service_invite->initiativeInvite(base_lib_BaseUtils::getCookie('accountid'), $sms_type, $sms_content, $cur_company, $resume, $ip, $invite, $person, $apply);
        }


    	if ($add_result === true) {
			//---------添加操作日志--------
			$common_oper_type = new base_service_common_account_accountoperatetype();
			$service_oper_log = new base_service_company_companyaccountlog();
			$common_oper_src_type = new base_service_common_account_accountlogfrom();
			$insertItems=array(
				"company_id"=>$this->_userid,
				"source"=>$common_oper_src_type->website,
				"account_id"=>base_lib_BaseUtils::getCookie('accountid'),
				"operate_type"=>$common_oper_type->resume_sendinvit,
				"content"=>"简历【".$person['user_name']."】投递的职位【".$station."】，发送了邀请面试",
				"create_time"=>date("Y-m-d H:i:s",time())
			);
			$service_oper_log->addLogToMongo($insertItems);
            
            //保存动作类型
            //保存登录动作
            //动作类型
            $service_actiontype = new base_service_common_actiontype();
            //用户类型
            $service_actionusertype = new base_service_common_actionusertype();
            //渠道
            $service_actionsource = new base_service_common_actionsource();
            base_lib_BaseUtils::saveAction($service_actiontype->invite, $service_actionsource->website, $this->_userid, $service_actionusertype->company);
			//-------------END------------
            
            //发送聊一聊消息
            $service_qqcloud_msg = new base_service_interface_qqcloudsendmsg();
            //$qq_cloud_content    = "您好，您的简历符合我们的要求，已向您发送面试邀请，请注意查看准时到达。如有特殊情况，请提前与我们联系。";
            if (!empty($invite['audition_remark'])) {
                $invite['audition_remark'] = $invite['audition_remark'] . '。';
            }
            $qq_cloud_content    = $person['user_name'] . "，我们向您发送面试邀请，通知您面试【". $station ."】岗位；时间：" . $invite['audition_time'] . "；地点：". $invite['audition_address'] ."； 联系人：". $invite['audition_link_man'] ."（". $invite['audition_link_tel'] ."）；注：". $invite['audition_remark'] ."如您有其他安排，请提前与我联系，谢谢！";
            $from_id             = base_lib_BaseUtils::getCookie('accountid');
            $service_qqcloud_msg->addPreSendMsg("CI", $from_id, $person['person_id'], $qq_cloud_content, $resume["resume_id"], $invite["job_id"]);
    		echo json_encode(array('status'=>'succeed', 'personid'=>$person['person_id'], 'msg'=>$this->MsgContent($sms_company_name, $station)));
    	} else {
    		echo json_encode(array('status'=>'fail'));
    	}

        return;
    }


    /**
     * 发送单个邀请
     * Enter description here ...
     * @param $inPath
     */
    public function pageSendInviteSingleV1($inPath) {

        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $validator = new base_lib_Validator();
        $is_send_msg = base_lib_BaseUtils::getStr($path_data['isSendMsg'], 'int', 0);
        $invite['resume_id'] = base_lib_BaseUtils::getStr($path_data['hddResumeID'], 'int', 0);
        $cur_company_id      = base_lib_BaseUtils::getStr($path_data['hddComId'], 'int', $this->_userid);

        //单位名称
        $sms_company_name = $validator->getNotNull($path_data['txtCompanyname'],'请输入单位名称');
        $validator->getStr($path_data['txtCompanyname'], 1, 20, '单位名称请输入1-20个字');

        //职位名称
        $station = $validator->getNotNull($path_data['txtStation'], '请输入职位名称');
        $validator->getStr($path_data['txtStation'], 1, 15, '职位名称请输入1-15个字');

        $invite['station'] = $station;
        //职位ID
        $invite['job_id']  = base_lib_BaseUtils::getStr($path_data['hddJobID'], 'int', 0);

        $service_company = new base_service_company_company();
        $current_company = $service_company->getCompany($this->_userid, 1, 'company_id,com_level,end_time,company_flag,company_name');
        if (empty($current_company)) {
            echo header("Content-type:text/plain;charset=utf-8");
            $json['error'] = '请登录';
            echo json_encode($json);
            return;
        }

        /* 获取会员信息 */
        $member_info = $this->getCompanyMemberInfo();

        //简历编号
        if (empty($invite['resume_id'])) {
            array_push($validator->err, '邀请简历编号获取失败');
            $validator->has_err = true;
        }

        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,base_lib_BaseUtils::getCookie('accountid'));
        $resource_data = $company_resources->getCompanyServiceSource(["_account_resource"]);
        $spread_overage = $resource_data['spread_overage'];
        $account_overage = $resource_data['account_overage'];        $service_job = new base_service_company_job_job();
        $current_job = $service_job->getJob($invite['job_id'], 'company_id,end_time');

        if (!in_array($cur_company_id, $company_resources->all_accounts))
            $validator->addErr("获取信息失败");

        //邀请类型
        $service_invitetype = new base_service_company_resume_invitetype();
        $invite['invite_type'] = $validator->getEnum($path_data['hddInviteType'], $service_invitetype->getInvitetype(), '邀请类型错误');

        //面试时间
        $date = $validator->getNotNull($path_data['hddDate'], '请设置面试时间');
        $sms_time = '';
        if ($date == '99') {
            $invite['audition_time'] = $validator->getNotNull($path_data['txtCustomTime'], '请设置面试时间');
            $sms_time = $validator->getStr($path_data['txtCustomTime'], 1, 20, '面试时间请输入1-20个字');
        } else {
            $time = $validator->getNotNull($path_data['hddtime'], '请设置面试时间');
            $time_value = explode(':', $time);
            if (mb_strlen($time_value[0]) == 1) {
                $time = '0' . $time;
            }
            $temp_date = "{$date} {$time}:00";
            if(strtotime($temp_date) < $this->_time)
                $validator->addErr("面试时间不能早于当前时间");

            $invite['audition_time'] = $validator->getDatetime($temp_date, '请设置面试时间', false);

            $date_arr = explode('-', $date);
            $sms_time = str_replace('00', '', str_replace(':', '点', $time));
            if (count($date_arr) > 2) {
                $sms_time = (int)$date_arr[1] . '月' . (int)$date_arr[2] . '日' . $sms_time;
            }
        }

        //面试地点
        $invite['audition_address'] = $validator->getNotNull($path_data['txtAddress'], '请输入面试地点');
        $validator->getStr($path_data['txtAddress'], 1, 60, '面试地点请输入1-60个字符');

        //联系人
        $invite['audition_link_man'] = $validator->getNotNull($path_data['txtLinkman'], '请输入联系人');
        $validator->getStr($path_data['txtLinkman'], 1, 15,'联系人请输入1-15个字符');

        //联系电话
        $invite['audition_link_tel'] = $validator->getTel($path_data['txtLinktel'], '联系电话不正确', false);

        //其他
        $invite['audition_remark'] = $validator->getStr($path_data['txtRemark'], 0, 200, '其他信息不能超过200个字', true);

        //短信类型
        $sms_type = base_lib_BaseUtils::getStr($path_data['smsType'], 'int', 1);
        if ($sms_type != 1 && $sms_type != 2) {
            $sms_type = 1;
        }

        if ($sms_type == 2) {
            $companyLevelService = new base_service_company_level();
            if (!$this->canSendFullMsg($this->_userid)) {
                $sms_type = 1;
            }
        }


        //判断该投递记录是否为快米投递
        $service_company_resume_applyresource = new base_service_company_resume_applyresource();
        $service_common_applyresource = new base_service_common_applyresource();
        $apply_service = new base_service_blue_person_apply();
        $service_blue_company_company = new base_service_blue_company_company();
        $blue_apply_id = 0;
        if($invite['invite_type'] == $service_invitetype->jobapply){
            $apply_id_temp = base_lib_BaseUtils::getStr($path_data['hddApplyID'], 'int', 0);
            if($apply_id_temp){
                $apply_resource_info = $service_company_resume_applyresource->getApplyListById($apply_id_temp);
                if($apply_resource_info && $apply_resource_info['resouce_type'] == $service_common_applyresource->kuaimi){
                    $blue_apply_id = $apply_resource_info['relevance_id'];
                    //快米投递的，则将公司名称和职位替换成关联企业的名称及职位
                    $blue_apply = $apply_service->getApplyById($blue_apply_id,'apply_id,job_id,company_id,station');
                    $blue_company_info = $service_blue_company_company->getCompany($blue_apply['company_id'],1,'company_id,company_name');
                    if($blue_company_info && $blue_company_info['company_name']){
                        $sms_company_name = $blue_company_info['company_name'];
                    }
                    if($blue_apply['station']){
                        $station = $blue_apply['station'];
                    }
                }
            }

        }

        //生成短信内容
        $sms_content = $this->createSmsContent($sms_type, $sms_company_name, $station, $sms_time,
            $invite['audition_address'], $invite['audition_link_man'], $invite['audition_link_tel'], $invite['audition_remark']);

        $shorturldomain_len = 16;
        $key_length         = 5;

        $xml = SXML::load('../config/company/company.xml');
        if (!is_null($xml)) {
            $shorturldomain_len = mb_strlen($xml->ShortUrlDomain);
        }
        if($is_send_msg){
            $total_len = mb_strlen($sms_content) + $shorturldomain_len + $key_length + mb_strlen('详情');
            if ($sms_type == 1 && $total_len > 60) {
                array_push($validator->err, '已超出' . ($total_len-60) . '个字，无法发送短信，请删除部分内容');
                $validator->has_err = true;
            } elseif ($sms_type == 2 && mb_strlen($sms_content) > 100) {
                array_push($validator->err, '已超出' . (mb_strlen($sms_content)-100) . '个字，无法发送短信，请删除部分内容');
                $validator->has_err = true;
            }
        }


        if ($validator->has_err) {
            echo header("Content-type:text/plain;charset=utf-8");
            echo $validator->toJsonWithHtml();
            return;
        }

        //验证单位相关信息
        $com_level = $current_company['com_level'];
        $end_time  = $current_company['end_time'];

        //判断会员期
        $service_apply = new base_service_company_resume_apply();

        $apply = null;

        //单位编号
        $invite['company_id'] = $cur_company_id;

        //邀请时间
        $invite['create_time'] = date('Y-m-d H:i:s', time());

        if ($invite['invite_type'] == $service_invitetype->jobinvite) {
            //截至时间
            $invite['end_time'] = date('Y-m-d H:i:s', strtotime ('+7 day'));
            //面试邀请求职者状态
            $service_jobinviterestatus = new base_service_company_resume_jobinviterestatus();
            $invite['re_status'] = $service_jobinviterestatus->notreply;
        } else if ($invite['invite_type'] == $service_invitetype->jobapply) {
            $invite['apply_id'] = base_lib_BaseUtils::getStr($path_data['hddApplyID'], 'int', 0);

            if ($invite['apply_id'] == 0) {
                $json['error'] = '获取求职者申请信息失败';
                echo header("Content-type:text/plain;charset=utf-8");
                echo json_encode($json);
                return;
            }

            $apply = $service_apply->getApply($invite['apply_id'], 'apply_id,re_time,re_status,is_read_com_reply,person_id,job_id,company_id,create_time,is_company_deleted,person_remark,person_re_status,is_cancelled');

            if (!empty($apply)) {
                if ($apply['is_company_deleted'] == '1') {
                    $json['error'] = '该求职申请已删除，邀请失败';
                    echo header("Content-type:text/plain;charset=utf-8");
                    echo json_encode($json);
                    return;
                }

                if ($apply['is_cancelled'] == '1') {
                    $json['error'] = '求职者已撤销申请，邀请失败';
                    echo header("Content-type:text/plain;charset=utf-8");
                    echo json_encode($json);
                    return;
                }

                $service_jobapplyrestatus = new base_service_person_jobapplyrestatus();
                $invite['re_status'] = $service_jobapplyrestatus->interview;
            } else {
                echo header("Content-type:text/plain;charset=utf-8");
                $json['error'] = '获取求职者申请信息失败';
                echo json_encode($json);
                return;
            }
            //职位截至时间
            if (!empty($current_job)) {
                $invite['end_time'] = $current_job['end_time'];
            } else {
                $invite['end_time'] = date('Y-m-d H:i:s', strtotime('+7 day'));
            }
        }

        //是否有效
        $invite['is_effect'] = 1;
        //求职者是否已阅读
        $invite['is_readed'] = 0;

        //面试结果
        $service_auditionresult = new base_service_company_resume_auditionresult();
        $invite['audition_result'] = $service_auditionresult->notset;

        //获取简历
        $service_resume = new base_service_person_resume_resume();
        $resume = $service_resume->getResume($invite['resume_id'], 'resume_id,person_id');

        if (empty($resume)) {
            $json['error'] = '简历获取失败';
            echo header("Content-type:text/plain;charset=utf-8");
            echo json_encode($json);
            //var_dump($json);
            return;
        }

        $invite['person_id'] = $resume['person_id'];
        //获取求职者
        $service_person = new base_service_person_person();
        $person = $service_person->getPerson($resume['person_id'], 'person_id,user_name,user_id,email,mobile_phone,person_class,password,open_mode');
        if (empty($person)) {
            $json['error'] = '个人信息获取失败';
            echo header("Content-type:text/plain;charset=utf-8");
            echo json_encode($json);
            //var_dump($json);
            return;
        }
        if(!$is_send_msg){
            $service_invite = new base_service_company_resume_jobinvite();
            $service_invite->chatInvite($apply,$resume,$invite,$sms_content,$company_resources);
            $from_id             = base_lib_BaseUtils::getCookie('accountid');
            //聊一聊中设置
//            $service_hello_template = new base_service_rong_rongsetfix();
//            $insert_data = array(
//                'account_id'=>$from_id,
//                'company_id'=>$this->_userid,
//                'person_id'=>$person['person_id'],
//                'resume_id'=>0,
//                'template_id'=>0,
//                'content'=>0,
//                'job_id'=>0,
//                'set_status'=>2,
//            );
//            $res = $service_hello_template->setPersonFix($insert_data);
            //发送聊一聊消息
            if (!empty($invite['audition_remark'])) {
                $invite['audition_remark'] = $invite['audition_remark'] . '。';
            }
            $service_qqcloud_msg = new base_service_interface_qqcloudsendmsg();
            $qq_cloud_content    = $person['user_name'] . "，我们向您发送面试邀请，通知您面试【". $station ."】岗位；时间：" . $invite['audition_time'] . "；地点：". $invite['audition_address'] ."； 联系人：". $invite['audition_link_man'] ."（". $invite['audition_link_tel'] ."）；注：". $invite['audition_remark'] ."如您有其他安排，请提前与我联系，谢谢！";
            $from_id             = base_lib_BaseUtils::getCookie('accountid');
            $service_qqcloud_msg->addPreSendMsg("CI", $from_id, $person['person_id'], $qq_cloud_content, $resume["resume_id"], $invite["job_id"]);
            echo json_encode(array('status'=>'succeed', 'personid'=>$person['person_id'], 'msg'=>$this->MsgContent($sms_company_name, $station)));
            exit();
        }

        $service_download = new base_service_company_resume_download();
        if(!$service_apply->isApply($company_resources->all_accounts, $invite['resume_id']) && $person['open_mode']!=1){
            $downloads = $service_download->getResumeDownload($this->_userid,$resume['resume_id'],'down_time');
            if (round((strtotime($this->now())-strtotime($downloads['down_time']))/3600) > 24) { //下载时间在24小时内，企业任可以查看
                $json['error'] = '对不起，该简历未投递或者简历未公开';
                echo header("Content-type:text/plain;charset=utf-8");
                echo json_encode($json);
                //var_dump($json);
                return;
            }
        }
        $invite['person_id'] = $person['person_id'];

        //获取IP
        $ip = base_lib_BaseUtils::getIp(0);

        $is_cq_setmeal = $company_resources->getCompanyServiceSource(['account_resource', 'company_level']); //获取套餐资源
        $point  = base_lib_BaseUtils::getStr($path_data['point'], 'int', 0);

        /* 余额 */
        $account_overage = $is_cq_setmeal['account_overage'];        //此简历3个月内未投递/未下载 ,扣除简历点
        $is_apply = $service_apply->isApply($company_resources->all_accounts, $invite['resume_id']);
        $download_info = $service_download->getResumeDownload( $company_resources->main_company_id, $invite['resume_id'],'down_time');

        //余额扣除点数
        $servicePricing = new base_service_company_service_servicePricing();
        $account_overage_service_price = $servicePricing->GetFunParallelismSelling('point_dow_resume');

        //推广金
        $account_spread_overage = $is_cq_setmeal['spread_overage']; //推广金余额


        if ($is_cq_setmeal['isCqNewService'] && !$is_apply && !$download_info){
            $tmp_status = true;
            //判断简历点是否足够
            if($is_cq_setmeal['cq_resume_num_release'] < 1 && $account_overage < $account_overage_service_price) {
                if(in_array($is_cq_setmeal['company_level'], array (8))) {
                    if($spread_overage + $account_overage < $account_overage_service_price) {
                        $tmp_status = false;
                    }
                }
                else {
                    $tmp_status = false;
                }
            }
            if(!$tmp_status) {
                echo header("Content-type:text/plain;charset=utf-8");
                if($is_cq_setmeal['resource_type'] == 2){
                    $json['error'] = '简历点和余额不足，请联系主账号为您分配更多资源！';
                }else{
                    $json['error'] = '简历点/余额/推广金不足，请联系招聘顾问进行购买！';
                }

                echo json_encode($json);
                return;
            }
        }
        $point_message = $servicePricing->GetFunParallelismSelling('point_message');

        /* 推广金 */
        $spread_effect_price = $is_cq_setmeal['spread_overage'];
        if ($is_cq_setmeal['isCqNewService']){
            if ($is_cq_setmeal['cq_release_point_message'] < 1){ //剩余短信点少于发送点
//                if ($point_message >($spread_effect_price + $account_overage)){
//                    if($is_cq_setmeal['resource_type'] == 2){
//                        $validator->addErr("短信数/推广金不足,请联系主账号为您分配更多资源!");
//                    }else{
//                        $validator->addErr("短信数/推广金/余额不足");
//                    }
//
//                    echo header("Content-type:text/plain;charset=utf-8");
//                    echo $validator->toJsonWithHtml();
//                    return;
//                }
            }
        }

        //发送邀请
        $add_result = false;
        $service_invite = new base_service_company_resume_jobinvite();

        $cur_company = $service_company->getCompany($cur_company_id, 1, "company_name,company_shortname,company_id,company_flag");
        if ($invite['invite_type'] == $service_invitetype->jobinvite) {
            $add_result = $service_invite->initiativeInvite(base_lib_BaseUtils::getCookie('accountid'), $sms_type, $sms_content, $cur_company, $resume, $ip, $invite, $person, $apply = null);
        } else if ($invite['invite_type'] == $service_invitetype->jobapply) {
            $add_result = $service_invite->initiativeInvite(base_lib_BaseUtils::getCookie('accountid'), $sms_type, $sms_content, $cur_company, $resume, $ip, $invite, $person, $apply);
        }


        if ($add_result === true) {
            //---------添加操作日志--------
            $common_oper_type = new base_service_common_account_accountoperatetype();
            $service_oper_log = new base_service_company_companyaccountlog();
            $common_oper_src_type = new base_service_common_account_accountlogfrom();
            $insertItems=array(
                "company_id"=>$this->_userid,
                "source"=>$common_oper_src_type->website,
                "account_id"=>base_lib_BaseUtils::getCookie('accountid'),
                "operate_type"=>$common_oper_type->resume_sendinvit,
                "content"=>"简历【".$person['user_name']."】投递的职位【".$station."】，发送了邀请面试",
                "create_time"=>date("Y-m-d H:i:s",time())
            );
            $service_oper_log->addLogToMongo($insertItems);

            //保存动作类型
            //保存登录动作
            //动作类型
            $service_actiontype = new base_service_common_actiontype();
            //用户类型
            $service_actionusertype = new base_service_common_actionusertype();
            //渠道
            $service_actionsource = new base_service_common_actionsource();
            base_lib_BaseUtils::saveAction($service_actiontype->invite, $service_actionsource->website, $this->_userid, $service_actionusertype->company);
            //-------------END------------

            //发送聊一聊消息
            if (!empty($invite['audition_remark'])) {
                $invite['audition_remark'] = $invite['audition_remark'] . '。';
            }
            $service_qqcloud_msg = new base_service_interface_qqcloudsendmsg();
            $qq_cloud_content    = $person['user_name'] . "，我们向您发送面试邀请，通知您面试【". $station ."】岗位；时间：" . $invite['audition_time'] . "；地点：". $invite['audition_address'] ."； 联系人：". $invite['audition_link_man'] ."（". $invite['audition_link_tel'] ."）；注：". $invite['audition_remark'] ."如您有其他安排，请提前与我联系，谢谢！";
            $from_id             = base_lib_BaseUtils::getCookie('accountid');
            $service_qqcloud_msg->addPreSendMsg("CI", $from_id, $person['person_id'], $qq_cloud_content, $resume["resume_id"], $invite["job_id"]);
            echo json_encode(array('status'=>'succeed', 'personid'=>$person['person_id'], 'msg'=>$this->MsgContent($sms_company_name, $station)));
        } else {
            echo json_encode(array('status'=>'fail'));
        }

        return;
    }
    /*
     * 微信信息内容
     */
    private function MsgContent($company_name,$station) {
    	return "{$company_name} 邀请您参加{$station}的面试";
    }
    
    /**
     * 发送批量邀请
     * Enter description here ...
     * @param $inPath
     */
//     public function pageSendInviteMulti($inPath){
//     	$service_company = new base_service_company_company();
// 		$current_company = $service_company->getCompany($this->_userid, 1, 'company_id,com_level,end_time,company_flag,company_name');
// 		if (empty($current_company)){
// 			echo header("Content-type:text/plain;charset=utf-8");
// 			$json['error'] = '请登录';
// 			echo json_encode($json);
// 			return;
// 		}
// 		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
// 		$validator = new base_lib_Validator();
//     	//简历名称
// 		$user_names = base_lib_BaseUtils::getStr($path_data['hddusername'],'array',null);
// 		if (empty($user_names)) {
// 			array_push($validator->err, '简历名称获取失败');
// 			$validator->has_err = true;
// 		}
// 		//简历编号
// 		$resume_ids = base_lib_BaseUtils::getStr($path_data['chkResume'],'array',null);
// 		if (empty($resume_ids)) {
// 			array_push($validator->err, '邀请简历编号获取失败');
// 			$validator->has_err = true;
// 		}
// 		$resume_id_count = count($resume_ids);
//     	if ($resume_id_count != count($user_names)){
//     		array_push($validator->err, '邀请失败');
// 			$validator->has_err = true;
//     	}
// 		//单位名称
// 		$sms_company_name = $validator->getNotNull($path_data['txtCompanyname'],'请输入单位名称');
// 		$validator->getStr($path_data['txtCompanyname'],1,20,'单位名称请输入1-20个字');
// 		//var_dump($sms_company_name);
// 		//职位名称
// 		$station = $validator->getNotNull($path_data['txtStation'],'请输入职位名称');
// 		$validator->getStr($path_data['txtStation'],1,10,'职位名称请输入1-10个字');
// 		//var_dump($station);
// 		//职位ID
// 		$job_id = base_lib_BaseUtils::getStr($path_data['hddJobID'],'int',0);
// 		//var_dump($job_id);
// 		$service_job = new base_service_company_job_job();
// 		$current_job = $service_job->getJob($job_id, 'end_time', $this->_userid);		
//     	//面试时间
//     	$audition_time = '';
// 		$date = $validator->getNotNull($path_data['hddDate'],'请设置面试时间');
// 		$sms_time = '';
// 		if ($date == '99') {
// 			$audition_time = $validator->getNotNull($path_data['txtCustomTime'],'请设置面试时间');
// 			$sms_time = $validator->getStr($path_data['txtCustomTime'],1,20,'面试时间请输入1-20个字');
// 		}else{
// 			$time = $validator->getNotNull($path_data['hddtime'],'请设置面试时间');
// 			$temp_date = "$date $time:00";
// 			$audition_time = $validator->getDatetime($temp_date,'请设置面试时间',false);
// 			$date_arr = explode('-', $date);
// 			$sms_time = str_replace('00', '', str_replace(':', '点', $time));
// 			if (count($date_arr) > 2) {
// 				$sms_time = (int)$date_arr[1].'月'.(int)$date_arr[2].'日'.$sms_time;
// 			}
// 		}
// 		//var_dump($audition_time);
// 		//var_dump($sms_time);
//     	$service_resume = new base_service_person_resume_resume();
//     	$resume_arr = $service_resume->getResumes($resume_ids, 'resume_id,person_id');
//     	if (empty($resume_arr) || count($resume_arr->items) == 0) {
//     		array_push($validator->err, '简历获取失败');
// 			$validator->has_err = true;
//     	}
//     	$temp_resume_ids = array();
//     	foreach ($resume_arr->items as $item=>$val){
//     		array_push($temp_resume_ids, $val['resume_id']);
//     	}
//     	for ($i = 0; $i < $resume_id_count; $i++) {
//     		$temp_count = count($temp_resume_ids);
//     		for ($j = 0; $j < $temp_count; $j++) {
//     			if ($resume_ids[$i] == $temp_resume_ids[$j]) {
//     				break;
//     			}else {
//     				if ($j == $temp_count - 1) {
//     					unset($resume_ids[$i]);
//     				}
//     			}
//     		}
//     	}
//     	//var_dump($resume_ids);
// 		//邀请类型
// 		$service_invitetype = new base_service_company_resume_invitetype();
// 		$invite['invite_type'] = $validator->getEnum($path_data['hddInviteType'],$service_invitetype->getInvitetype(),'邀请类型错误');
// 		//面试地点
// 		$invite['audition_address'] = $validator->getNotNull($path_data['txtAddress'],'请输入面试地点');
// 		$validator->getStr($path_data['txtAddress'],1,60,'面试地点请输入1-60个字符');
// 		//联系人
// 		$invite['audition_link_man'] = $validator->getNotNull($path_data['txtLinkman'],'请输入联系人');
// 		$validator->getStr($path_data['txtLinkman'],1,15,'联系人请输入1-15个字符');
// 		//联系电话
// 		$invite['audition_link_tel'] = $validator->getTel($path_data['txtLinktel'],'联系电话不正确',false);
// 		//其他
// 		$invite['audition_remark'] = $validator->getStr($path_data['txtRemark'],0,200,'其他信息不能超过200个字',true);
// 		//var_dump($invite);
//     	//短信类型
//     	$sms_type = base_lib_BaseUtils::getStr($path_data['smsType'],'int',1);
//     	if ($sms_type != 1 && $sms_type != 2) {
//     		$sms_type = 1;
//     	}
//     	if ($sms_type == 2) {
//     		$companyLevelService = new base_service_company_level();
//     		if(!$this->canSendFullMsg($this->_userid)) {
//     			$sms_type = 1;
//     		}
//     		/*
// 	    	if (($current_company["com_level"] != $companyLevelService->svip["com_level"]) && ($current_company["com_level"] != $companyLevelService->newgold["com_level"]) && ($current_company["com_level"] != $companyLevelService->gold["com_level"])) {
// 				$sms_type = 1;
// 			}*/
//     	}
//     	//var_dump($sms_type);
//     	//生成短信内容
//     	$sms_content = $this->createSmsContent($sms_type,$sms_company_name,$station,$sms_time,$invite['audition_address'],$invite['audition_link_man'],$invite['audition_link_tel'],$invite['audition_remark']);
//     	$shorturldomain_len = 16;
//     	$key_length = 5;
//     	$xml = SXML::load('../config/company/company.xml');
//  		if(!is_null($xml)){
//  			$shorturldomain_len = mb_strlen($xml->ShortUrlDomain);
//  		}
//  		$total_len = mb_strlen($sms_content) + $shorturldomain_len + $key_length + mb_strlen('详情');
//     	if ($sms_type == 1 && $total_len > 60) {
//     		array_push($validator->err, '已超出'.($total_len-60).'个字，无法发送短信，请删除部分内容');
// 			$validator->has_err = true;
//     	}elseif ($sms_type == 2 && mb_strlen($sms_content) > 95) {
//     		array_push($validator->err, '已超出'.(mb_strlen($sms_content)-95).'个字，无法发送短信，请删除部分内容');
// 			$validator->has_err = true;
//     	}
//     	if($validator->has_err){
//     		echo header("Content-type:text/plain;charset=utf-8");
// 			echo $validator->toJsonWithHtml();
// 			//var_dump($validator->err);
// 			return;
//     	}
//     	//验证单位相关信息
//     	$com_level = $current_company['com_level'];
// 		$end_time = $current_company['end_time'];
//     	//判断会员级别
// 		if ($com_level <= 1){
// 			echo header("Content-type:text/plain;charset=utf-8");
// 			$json['error'] = '贵公司尚未开通服务';
// 			echo json_encode($json);
// 			return;
// 		}
// 		//判断会员期
// 		if (empty($end_time) || strtotime(date('Y-m-d', time())) > strtotime(date('Y-m-d', strtotime($end_time)))) {
// 			echo header("Content-type:text/plain;charset=utf-8");
// 			$json['error'] = '您的会员已过期';
// 			echo json_encode($json);
// 			return;
// 		}
//     	//判断公司发出的邀请是否已满
// //		$job_invite_num = $service_jobinvite->get_invite_count($this->_userid);
// //
// //		$service_company_level = new base_service_company_level();
// //		$company_level_arr = $service_company_level->getLevelById($com_level);
// //    	if (empty($company_level_arr)) {
// //			echo header("Content-type:text/plain;charset=utf-8");
// //			$json['error'] = '您的面试邀请发送量已满';
// //			echo json_encode($json);
// //			//var_dump($json);
// //			return;
// //		}
// //		if ($job_invite_num >= $company_level_arr['invite_num']) {
// //			echo header("Content-type:text/plain;charset=utf-8");
// //			$json['error'] = '您的面试邀请发送量已满';
// //			echo json_encode($json);
// //			//var_dump($json);
// //			return;
// //		}
// 		//单位编号
// 		$invite['company_id'] = $this->_userid;
// 		//是否有效
// 		$invite['is_effect'] = '1';
// 		//求职者是否已阅读
// 		$invite['is_readed'] = '0';
// 		//面试结果
// 		$service_auditionresult = new base_service_company_resume_auditionresult();
// 		$invite['audition_result'] = $service_auditionresult->notset;
// 		//邀请时间
// 		$invite['create_time'] = date('Y-m-d H:i:s',time());
// 		//获取IP
// 		$ip = base_lib_BaseUtils::getIp(0);
// 		$service_invite = new base_service_company_resume_jobinvite();
// 		$service_person = new base_service_person_person();
// 		$service_jobinviterestatus = new base_service_company_resume_jobinviterestatus();
// 		$service_jobapplyrestatus = new base_service_person_jobapplyrestatus();
// 		$successItem = '';
// 		$failItem = '';
// 		$successPerson = array();
// 		if ($invite['invite_type'] == $service_invitetype->jobinvite) {
// 			//截至时间
// 			$invite['end_time'] = date('Y-m-d H:i:s', strtotime ('+7 day'));
// 			//面试邀请求职者状态
// 			$invite['re_status'] = $service_jobinviterestatus->notreply;
// 			foreach ($resume_ids as $item=>$val) {
// 				$invite['resume_id'] = $val;
// 				$invite['station'] = $station;
// 				$invite['job_id'] = $job_id;
// 				$user_name = $user_names[$item];
// 				$invite['audition_time'] = $audition_time;
// //				$the_job_invite_num = $service_jobinvite->get_invite_count($this->_userid);
// //				if ($the_job_invite_num >= $company_level_arr['invite_num']) {
// //					$failItem .= "<br />您的面试邀请发送量已满，邀请{$user_name}失败";
// //					break;
// //				}
// 				//获取简历
// 				$resume = $service_resume->getResume($invite['resume_id'], 'resume_id,person_id');
// 				//$resume = null;
// 				if (empty($resume)) {
// 					$failItem .= "<br />获取求职者{$user_name}简历失败";
// 					continue;
// 				}
// 				//获取求职者
// 				$person = $service_person->getPerson($resume['person_id'], 'person_id,user_name,user_id,email,mobile_phone');
// 				//$person = null;
// 		    	if (empty($person)) {
// 					$failItem .= "<br />邀请求职者{$user_name}信息失败";
// 					continue;
// 				}
// 				$invite['person_id'] = $person['person_id'];
// 				//发送邀请
// 				$add_result = $service_invite->initiativeInvite($sms_type,$sms_content,$current_company,$resume,$ip,$invite,$person);
// 				//$add_result = false;
// 				if (!$add_result) {
// 		    		$failItem .= "<br />邀请{$user_name}失败";
// 		    	}
// 		    	$successItem .= "<br />邀请{$user_name}成功";
// 		    	array_push($successPerson, array('personid'=>$person['person_id'],'message'=>$this->MsgContent($sms_company_name, $station)));
// 			}
// 		}else if ($invite['invite_type'] == $service_invitetype->jobapply) {
// 			//$invite['apply_id'] = base_lib_BaseUtils::getStr($path_data['hddApplyID'],'int',0);
// 			//职位申请编号
// 			$apply_ids = base_lib_BaseUtils::getStr($path_data['hddapplyid'],'array',null);
// 			if (empty($apply_ids)) {
// 				echo header("Content-type:text/plain;charset=utf-8");
// 				$json['error'] = '获取求职者申请信息失败';
// 				echo json_encode($json);
// 				return;
// 			}
// 			$service_apply = new base_service_company_resume_apply();
// 			//var_dump($apply_ids);
// 			$applys = $service_apply->getApplysOrderBySplitIdStr($apply_ids, 'apply_id,resume_id,station,re_time,re_status,is_read_com_reply,person_id,job_id,company_id,create_time,is_company_deleted,person_remark,person_re_status,is_cancelled');
// 			//var_dump($applys->items);return;
// 			if (!empty($applys)) {
// 				foreach ($applys->items as $item=>$val) {
// 					$user_name = $user_names[$item];
// //					$the_job_invite_num = $service_jobinvite->get_invite_count($this->_userid);
// //					if ($the_job_invite_num >= $company_level_arr['invite_num']) {
// //						$failItem .= "<br />您的面试邀请发送量已满，邀请{$user_name}失败";
// //						break;
// //					}
// 					if ($val['is_company_deleted'] == '1') {
// 						$failItem .= "<br />该求职申请已删除，邀请{$user_name}失败";
// 						continue;
// 					}
// 					if ($val['is_cancelled'] == '1') {
// 						$failItem .= "<br />求职者已撤销申请，邀请{$user_name}失败";
// 						continue;
// 					}
// 					$invite['apply_id'] = $val['apply_id'];
// 					$invite['resume_id'] = $val['resume_id'];
// 					$invite['station'] = $station;
// 					$invite['job_id'] = $job_id;
// 					$invite['audition_time'] = $audition_time;
// 					$invite['re_status'] = $service_jobapplyrestatus->interview;
// 					//职位截止时间
// //					if (empty($current_job)) {
// //						$failItem .= "<br />职位不存在，邀请{$user_name}失败";
// //						continue;
// //					}
// 					if (!empty($current_job)) {
// 						$invite['end_time'] = $current_job['end_time'];
// 					}else {
// 						$invite['end_time'] = date('Y-m-d H:i:s', strtotime ('+7 day'));
// 					}
// 					//获取简历
// 					$resume = $service_resume->getResume($invite['resume_id'], 'resume_id,person_id');
// 					//$resume = null;
// 					if (empty($resume)) {
// 						$failItem .= "<br />获取求职者{$user_name}简历失败";
// 						continue;
// 					}
// 					//获取求职者
// 					$person = $service_person->getPerson($resume['person_id'], 'person_id,user_name,user_id,email,mobile_phone');
// 					//$person = null;
// 			    	if (empty($person)) {
// 						$failItem .= "<br />获取求职者{$user_name}信息失败";
// 						continue;
// 					}
// 					$invite['person_id'] = $person['person_id'];
// 					//发送邀请
// 					$add_result = $service_invite->initiativeInvite($sms_type,$sms_content,$current_company,$resume,$ip,$invite,$person,$val);
// 					//$add_result = false;
// 					if (!$add_result) {
// 			    		$failItem .= "<br />邀请{$user_name}失败";
// 			    	}
// 			    	$successItem .= "<br />邀请{$user_name}成功";
// 			    	array_push($successPerson, array('personid'=>$person['person_id'],'message'=>$this->MsgContent($sms_company_name, $station)));
// 				}
// 			}else {
// 				echo header("Content-type:text/plain;charset=utf-8");
// 				$json['error'] = '获取求职者申请信息失败';
// 				echo json_encode($json);
// 				return;
// 			}
// 		}
// 		if (!base_lib_BaseUtils::nullOrEmpty($failItem)) {
// 			echo json_encode(array('status'=>'fail', 'failitem'=>$failItem, 'successitem'=>$successItem,'inviteperson'=>$successPerson));
//     		return;
// 		}
// 		echo json_encode(array('status'=>'succeed','inviteperson'=>$successPerson));
//     	return;
//     }
    
	/**
     * 生成短信内容
     * Enter description here ...
     * @param unknown_type $sms_type
     * @param unknown_type $sms_company_name
     * @param unknown_type $station
     * @param unknown_type $sms_time
     * @param unknown_type $address
     * @param unknown_type $link_man
     * @param unknown_type $link_tel
     * @param unknown_type $remark
     */
    protected function createSmsContent($sms_type,$sms_company_name,$station,$sms_time,$address,$link_man,$link_tel,$remark){
    	$content = '';
    	$sms_company_name = trim($sms_company_name);
    	$station = '（'.trim($station).'）';
    	if ($sms_type == 1) {
    		$content = $sms_company_name.'通知您面试'.$station;
    	}elseif ($sms_type == 2) {
    		$sms_time = ' 时间：'.trim($sms_time);
    		$address = ' 地点：'.trim($address);
    		$temp_link = ' 联系：'.trim($link_man).trim($link_tel);
    		if (!base_lib_BaseUtils::nullOrEmpty(trim($remark))) {
    			$remark = ' 注：'.trim($remark);
    		}
    		$content = $sms_company_name.'通知您面试'.$station.$sms_time.$address.$temp_link.$remark;
    	}
    	return $content;
    }

    private function get_state_infor($data) {
        
        return array(
            "invite_id"   => $this->get_post_value($data["id"]),
            "state_value" => $this->get_post_value($data["result"]),
            "msg_content" => $this->get_post_value($data["msg_content"])
        );
    }

    //验证单条信息
    private function validate_state_infor($data) {
        $validator              = new base_lib_Validator();
        $service_invite_results = new base_service_company_resume_auditionresult();
        
        $invite_results = $service_invite_results->getAuditionresult();
        if (!isset($invite_results[$data["state_value"]])) {
            $validator->addErr("请选择正确的面试状态");
        }

        return $validator;
    }

    private function save_state_infor($data) {
        $service_invites    = new base_service_company_resume_jobinvite();
        $applyService       = new base_service_company_resume_apply();
        $service_invitetype = new  base_service_company_resume_invitetype();

       
        $accountid = base_lib_BaseUtils::getCookie('accountid');
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);

        $result = $service_invites->update_invite_state($company_resources->all_accounts, $data["invite_id"], $data["state_value"]);
        $deleteApplies = $service_invites->getInvitesByIDs($data["invite_id"], "apply_id,invite_type,resume_id")->items;
        
        $applyids  = array();
        $resumeids = array();
        foreach ($deleteApplies as $apply) {
            array_push($resumeids, $apply["resume_id"]);
            if (!base_lib_BaseUtils::nullOrEmpty($apply["resume_id"])
                && !in_array($apply["apply_id"], $applyids)
                && $apply['invite_type'] == $service_invitetype->jobapply) {

                array_push($applyids, $apply["apply_id"]);
            }
        }

        $recommendis = array();
        if (count($resumeids) > 0) {
            //同步修改推荐的简历状态
            $service_recommend = new base_service_company_resume_recommend();
            $recommends = $service_recommend->getRecommendInfoByResumeIds($company_resources->all_accounts, $resumeids, "recommend_id");
            if (!base_lib_BaseUtils::nullOrEmpty($recommends)) {
                foreach($recommends as $recommend) {
                    array_push($recommendis, $recommend['recommend_id']);
                }
            }

            if (count($recommendis) > 0) {
                $recommend_status = $this->__getRecommendStatus($data['state_value']);
                if (!empty($recommend_status)) {
                    $service_recommend->updateRecommendStatus($recommend_status, $recommendis);
                }
            }
        }

        if (count($applyids) > 0) {
        	$result = $applyService->SetApplyStateOfIDs(implode(",", $applyids), $data["state_value"]);
        }    	
        return $result !== false;
    }

    //获得推荐简历的状态
    private function __getRecommendStatus($invite_status){
        $service_recommend_status = new base_service_common_recommendstatus();
        $service_auditionresult = new base_service_company_resume_auditionresult();
        $recommend_status = "";
        switch($invite_status){
//            case $service_auditionresult->notcometoaudition: //未参加面试
//                $recommend_status = $service_recommend_status->notcometoaudition;
//                break;
//            case $service_auditionresult->eliminate://面试未过
//                $recommend_status = $service_recommend_status->notpassaudition;
//                break;
//            case $service_auditionresult->employ://已发offer
//                $recommend_status = $service_recommend_status->employ;
//                break;
//            case $service_auditionresult->refuseoffer://拒绝offer
//                $recommend_status = $service_recommend_status->refuseoffer;
//                break;
//            case $service_auditionresult->entry://已入职
//                $recommend_status = $service_recommend_status->entry;
//                break;
            case $service_auditionresult->notset:
                $recommend_status = $service_recommend_status->notset;
                break;
            case $service_auditionresult->pass:
                $recommend_status = $service_recommend_status->pass;
                break;
            case $service_auditionresult->notpass:
                $recommend_status = $service_recommend_status->notpass;
                break;
            default :
                return "";
        }
       return $recommend_status;
    }
    
    //获取页面请求的参数
    private function get_post_value($value){
        return isset($value) ? base_lib_BaseUtils::getStr($value) : "";
    }

    private function get_search_time($search_date,$month_opt=""){
        preg_match('/^(\d+)年(\d+)月(\d+)日$/', $search_date,$matchs);
        $date = array("year"=>$matchs[1],"month"=>$matchs[2],"day"=>$matchs[3]);
        $month = $date["month"];
        if($month_opt == "1"){
            $month++;
        }else if($month_opt == "-1")
            $month--;
        return mktime(0,0,0,$month,$date["day"],$date["year"]);
    }

    public function pageGetCalender($inPath) {
        $params =base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $date = base_lib_BaseUtils::getStr($params['date'],'string',date('Y年m月d日'));
        $calendar = $this->get_calendar($date);
    	echo json_encode($calendar);
    	return;
    }
    
    private function get_calendar($selectDate){
        $company_invite_serivce = new base_service_company_resume_jobinvite();
        $calendar = array(0=>null,1=>null,2=>null,3=>null,4=>null,5=>null,6=>null,
            7=>null,8=>null,9=>null,10=>null,11=>null,12=>null,13=>null,
            14=>null,15=>null,16=>null,17=>null,18=>null,19=>null,20=>null,
            21=>null,22=>null,23=>null,24=>null,25=>null,26=>null,27=>null,
            28=>null,29=>null,30=>null,31=>null,32=>null,33=>null,34=>null,
            );
        preg_match('/^(\d+)年(\d+)月(\d+)日$/', $selectDate,$matchs);
        $date = array("year"=>$matchs[1],"month"=>$matchs[2],"day"=>1);
        $month_first_time = mktime(0,0,0,$date["month"],$date["day"],$date["year"]);
        $month_first_week = date("w",$month_first_time);
        $month_of_days = date("t",$month_first_time);
        $calendar[$month_first_week] = 1;

        //上个月的后几天

        $first_date = date("Y-m-d H:i:s",mktime(0,0,0,$date["month"],1,$date["year"]));
        $last_date = date("Y-m-d H:i:s",mktime(23,59,59,$date["month"],$month_of_days,$date["year"]));
        $inviteNumsOfDate = $company_invite_serivce->inviteNumOfDate($this->_userid,$first_date,$last_date)->items;
        //上一个月
        $before_first = $month_first_week-1;
        $month_days_before_date = date("t",mktime(0,0,0,$date["month"]-1,$date["day"],$date["year"]));
        for($i=$before_first;$i>=0;$i--){
            $current_date = date("Y年m月d日",mktime(0,0,0,$date["month"]-1,$month_days_before_date,$date["year"]));
            $has_invite = $this->get_invite_count_of_date($inviteNumsOfDate,$current_date);
            $calendar[$i] = array($month_days_before_date--,"oth",$current_date,$has_invite);
        }
        //本月
        for($i=1;$i<=$month_of_days;$i++){
            $this_month = "";
            /*
            if($i == $matchs[3])
                $this_month = "cu";
            else
                $this_month = "";
             */
            $current_date = date("Y年m月d日",mktime(0,0,0,$date["month"],$i,$date["year"]));
            if($current_date == date("Y年m月d日"))
                $this_month = "today";
            $has_invite = $this->get_invite_count_of_date($inviteNumsOfDate,$current_date);
            $calendar[$month_first_week++] = array($i,$this_month,$current_date,$has_invite);
        }
        //下一个月
        $next_first = $month_first_week;
        $next_month_num = 1;
        for($i=$next_first; $i <35;  $i++) {
            $current_date = date("Y年m月d日",mktime(0,0,0,$date["month"]+1,$next_month_num,$date["year"]));
            $has_invite = $this->get_invite_count_of_date($inviteNumsOfDate,$current_date);
            $calendar[$i] = array($next_month_num++,"oth",$current_date,$has_invite);
        }
        $after_last = $month_first_week;
        return $calendar;
    }

    private function get_invite_count_of_date($inviteNumsOfDate,$check_date){
        preg_match('/^(\d+)年(\d+)月(\d+)日$/', $check_date,$matchs);
        $date = array("year"=>$matchs[1],"month"=>$matchs[2],"day"=>$matchs[3]);
        $current_date = date("Y-m-d",mktime(0,0,0,$date["month"],$date["day"],$date["year"]));
        $result = false;
        foreach ($inviteNumsOfDate as $key => $value) {
        	if($value["audition_time"] == $current_date) {
        		$result = true;
        		break;
        	}
        }
        return $result;
    }
    
    /**
     * 面试邀请列表页面入口
     * @param $inPath
     */
    public function pageIndex($inPath) {
        if(!$this->canDo("invite_magane")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
 	   	$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		// 参数信息:  职位编号，投递时间，状态，姓名/简历编号，显示方式（列表/摘要）  
        $station         = base_lib_BaseUtils::getStr($params['station'], 'string', null);
        $invite_time     = base_lib_BaseUtils::getStr($params['invite_time'], 'int', null);
        $startDay        = base_lib_BaseUtils::getStr($params['startDay'], 'datetime', null);
        $endDay          = base_lib_BaseUtils::getStr($params['endDay'], 'datetime', null);
        $audition_result = base_lib_BaseUtils::getStr($params['result'], 'int', null);
        $keyword         = base_lib_BaseUtils::getStr($params['keyword'], 'string', '');
        $searchmode      = base_lib_BaseUtils::getStr($params['search_model'], 'int', '0'); // 查询方式 0表示按筛选条件，1表示按姓名/简历编号  
        $search_type      = base_lib_BaseUtils::getStr($params['search_type'], 'int', "1"); // 查询方式 1,未过期  2，已过期
        $cur_page        = base_lib_BaseUtils::getStr($params['page'], 'int', 1);
        $page_size       = base_lib_BaseUtils::getStr($params['pageSize'], 'int', base_lib_Constant::PAGE_SIZE);
        $account_id      = base_lib_BaseUtils::getStr($params['account_id'], 'int', 0);
		$son_account_id       = base_lib_BaseUtils::getStr($params['son_account_id'], 'int', "");
        $search_audition_result = !empty($audition_result) ? $audition_result-1 : NULL;
        $enum_audition = new base_service_company_resume_auditionresult();
        if($search_type == 2 && !isset($params['result']) && empty($search_audition_result)){
            $search_audition_result = $enum_audition->notset;
            $audition_result = $enum_audition->notset + 1;
        }
        //result： 0，null都按照选择不限搜索
        if(empty($params['result']))
        {
            $audition_result = $search_audition_result = NULL;
        }

	    if ($invite_time === 0) {
	    	$invite_time = '+0'; // XXX：js的下拉组件默认认为选项为0的项为默认项
	    }
        if(!empty($startDay) && !empty($endDay) && strtotime($startDay) >= strtotime($endDay))
            list($startDay, $endDay) = array($endDay, $startDay);
        
        $this->_aParams['account_id'] = $account_id;
		$this->_aParams['son_account_id'] = $son_account_id;
        $this->_aParams['search_type'] = $search_type;
        $this->_aParams['startDay'] = $startDay;
        $this->_aParams['endDay'] = $endDay;

        // HR 代招账号
        $company_resources = new base_service_company_resources_resources($this->_userid);
        $account_id = empty($account_id) || !in_array($account_id, $company_resources->all_accounts) ? $company_resources->all_accounts : $account_id;

        $company_service = new base_service_company_company();
        $accounts = $company_service->getCompanys($company_resources->all_accounts, "company_id,company_shortname,company_name");
        $_accounts = [];
        // 是否为hr代招会员
        $this->_aParams['is_hr'] = $company_resources->account_type == 'hr_main' ? true : false;

        $datas[] = ["id" => "0", "name" => "所有公司"];
        foreach ($accounts as $key => $account) {
            $account['company_name_display']   = $account['company_shortname'] ? $account['company_shortname'] : $account['company_name'];
            $_accounts[$account['company_id']] = $account;
            $this->_aParams['company_shortname'] = $account['company_shortname'] ? $account['company_shortname'] : $account['company_name'];

            $datas[] = ['id' => $account['company_id'], "name" => $account['company_name_display']];
        }

        $this->_aParams['accounts']      = $_accounts;
        $this->_aParams['accounts_json'] = json_encode($datas);
        $service_invite = new base_service_company_resume_jobinvite();
        $service_job    = new base_service_company_job_job();

		// 职位
		$base_service_company_job_job = new base_service_company_job_job();
		$account_job_ids = '';
		if($son_account_id){
			$account_job_list =$base_service_company_job_job->getJobIdByAccount($this->_userid,$son_account_id,'company_id,job_id,account_id');
			$account_job_ids = base_lib_BaseUtils::getProperty($account_job_list->items,'job_id');
		}


		//是否选择发布人  选择了发布人，并且没有选择职位，查询发布人下的所有记录
		$station_temp = '';
		if(!base_lib_BaseUtils::nullOrEmpty($son_account_id) && base_lib_BaseUtils::nullOrEmpty($station)){
			$station_temp = $account_job_ids;
		}else{
			$station_temp = $station;
		}

		if ($searchmode == 0) {
			$invite_list = $service_invite->getInviteListByCompany(
                $page_size,
                $cur_page,
                $account_id,
                'invite_id,info_job_invite.person_id,info_job_invite.resume_id,info_job_invite.station,info_job_invite.create_time,re_status,audition_result,job_id,audition_time,company_id',
				$station_temp,
                $invite_time,
                $search_audition_result,
                $search_type,
                $startDay,
                $endDay    
            );
		} else {
            $station         = null;
            $invite_time     = null;
            $audition_result = null;
			$invite_list = $service_invite->getInviteByNameOrResumeID(
                $page_size, 
                $cur_page,
                $account_id,
                'invite_id,info_job_invite.person_id,info_job_invite.resume_id,info_job_invite.station,info_job_invite.create_time,re_status,audition_result,job_id,audition_time,company_id',
                $keyword
            );
		}


		$service_company_account = new base_service_company_account();
		$son_account_list = $service_company_account->getAccountList($this->_userid,'account_id,company_id,is_main,user_id,user_name');
		$jobs = $service_invite->getInviteStationNew($this->_userid, "station",$account_job_ids)->items;
		$list = $invite_list->items;
		$invite_ids = base_lib_BaseUtils::getProperty($list, 'invite_id');
		if (!base_lib_BaseUtils::nullOrEmpty($list)) {
            $service_person        = new base_service_person_person();
            $service_resume        = new base_service_person_resume_resume();
            $service_resume_work   = new base_service_person_resume_work();
            $service_resume_remark = new base_service_company_resume_resumeremark();
            $service_person_cancel = new base_service_person_resume_cancelinvite();
            $service_company_cancel = new base_service_company_resume_jobinvitegiveup();

            // 求职者
			$person_ids = implode(',', $this->getPropertys($list, 'person_id'));
			$person_list = $service_person->GetPersonListByIDs($person_ids, 'person_id,user_name,sex,birthday2,cur_area_id,start_work,photo,small_photo,mobile_phone,telephone');
			
            // 简历
			$resume_ids = $this->getPropertys($list, 'resume_id');
			$resume_list = $service_resume->getResumes($resume_ids, 'resume_id,resume_name,degree_id,is_effect');		
			
            // 备注列表
			$remark_data = $service_resume_remark->getLastResumeRemarks($this->_userid, $resume_ids, 'remark_id,resume_id,company_id,remark,update_time');

			//取消记录
            $person_cancel_data = $service_person_cancel->getRecordInInviteIds($invite_ids, 'id, invite_id,company_id');
            $person_cancel_data = base_lib_BaseUtils::array_key_assoc($person_cancel_data->items, 'invite_id');
            $company_cancel_data = $service_company_cancel->getRecordInInviteIds($invite_ids, 'id, invite_id,company_id');
            $company_cancel_data = base_lib_BaseUtils::array_key_assoc($company_cancel_data->items, 'invite_id');
			for ($i = 0, $len = count($list); $i < $len; $i++) {
                $invite             = $list[$i];
                $resume_info        = $this->arrayFind($resume_list->items, 'resume_id', $invite['resume_id']);
                $resume_id          = $resume_info['resume_id'];
                $person_id          = $invite['person_id'];
                $person_info        = $this->arrayFind($person_list->items, 'person_id', $person_id);
                $resume_work_info   = $this->arrayFind($workslist, 'resume_id', $resume_id);
                $resume_remark_info = $this->arrayFind($remark_data->items, 'resume_id', $resume_id);
				
                //姓名
                $list[$i]['user_name']      = base_lib_BaseUtils::cutstr($person_info['user_name'], 4, 'utf-8', '', '...');;
                $list[$i]['full_user_name'] = base_lib_BaseUtils::htmlEncode($person_info['user_name']);
                $list[$i]['remark']         = base_lib_BaseUtils::nullOrEmpty($resume_remark_info['remark'])
                    ? false : $resume_remark_info['remark'] . ' ' . date('Y-m-d', strtotime($resume_remark_info['update_time']));

				//头像性别、年龄、学历、当前所在地					
				if (base_lib_BaseUtils::nullOrEmpty($person_info['photo'])) {
                    $person_info['photo']       = false;
                    $person_info['small_photo'] = false;
				} else {
					if (base_lib_BaseUtils::nullOrEmpty($person_info['small_photo'])) {
						$person_info['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['photo'];
					} else {
						$person_info['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['small_photo'];
					}
					$person_info['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['photo'];
				}

                $list[$i]['generation_bidding'] = $list[$i]['company_id'] == $this->_userid ? false : true;
                $list[$i]['photo']       = $person_info['photo'];
                $list[$i]['small_photo'] = $person_info['small_photo'];
                $list[$i]['sex']         = $this->getSex($person_info['sex']) ;
                $list[$i]['age']         = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']).'岁';
                $list[$i]['degree']      = $this->getDegree($resume_info['degree_id']);
                $list[$i]['phone']       = base_lib_BaseUtils::nullOrEmpty($person_info['mobile_phone']) ? $person_info['telephone'] : $person_info['mobile_phone'];
	
    			// 投递时间
				$audition_time = base_lib_BaseUtils::getStr($list[$i]['audition_time'], 'datetime', null);
      
				if (empty($audition_time)) {
					$list[$i]['audition_time'] = $list[$i]['audition_time'];
				} else {
					$list[$i]['audition_time_hour'] = date('H:i', strtotime($audition_time));
					$list[$i]['audition_time_day'] = date('Y-m-d', strtotime($audition_time));
                    if($list[$i]['audition_time_day'] == date('Y-m-d'))
                        $list[$i]['audition_time_day'] = '今天';   
                    else if($list[$i]['audition_time_day'] == date('Y-m-d', strtotime("+1 day")))
                        $list[$i]['audition_time_day'] = '明天';
                    else if($list[$i]['audition_time_day'] == date('Y-m-d', strtotime("-1 day")))
                        $list[$i]['audition_time_day'] = '昨天';
                    else if($list[$i]['audition_time_day'] == date('Y-m-d', strtotime("-2 day")))
                        $list[$i]['audition_time_day'] = '前天';
				}

				$list[$i]['invite_time'] =base_lib_TimeUtil::to_friend_time($list[$i]['create_time']);

				//邀请状态
				$list[$i]['re_status'] = $invite['re_status'];		

				// 面试结果			
				$list[$i]['audition_result'] = $invite['audition_result'];
//				$list[$i]['audition_result_name'] = $invite['audition_result'] == '0' ? '-' : $enum_audition->getName($invite['audition_result']);
                if (!empty($person_cancel_data[$list[$i]['invite_id']]) || !empty($company_cancel_data[$list[$i]['invite_id']])){
                    $list[$i]['has_cancel'] = 1;
                } else {
                    $list[$i]['has_cancel'] = 0;
                }
				$list[$i]['audition_result_name'] = $enum_audition->getName($invite['audition_result']);
			}
			//分页
 			$pager = $this->pageBar($invite_list->totalSize, $page_size, $cur_page, $inPath);
			$this->_aParams['pager'] = $pager;			
		}

        $this->_aParams['invitelist'] = $list;
        $this->_aParams['totalSize']  = $invite_list->totalSize;

	    //  是否发出过邀请
	    $invitestate = $service_invite->getInviteStat($this->_userid);
		$this->_aParams['hasInvite'] = intval($invitestate['total']) > 0 ? true : false;

		//  筛选以后是否有数据
		$this->_aParams['hasFilterInvite'] = $invite_list->totalSize>0?true:false;
		$this->_aParams['jobs'] = "[]";
		
        if (count($jobs) > 0) {
            $jobs_json[] = ["id" => "", "name" => "全部职位"];
            foreach ($jobs as $json)
                $jobs_json[] = ["id" => $json['station'], "name" => $json['station']];

            $this->_aParams['jobs'] = json_encode($jobs_json);
		}

		//发布人选项
		$job_people = [];
		array_push($job_people,["id"=>"","name"=>"按发布人查看"]);
		foreach($son_account_list->items as $val){
			array_push($job_people,["id"=>$val['account_id'],"name"=>$val['user_name']]);
		}

		$this->_aParams['job_people'] = json_encode($job_people);
        $this->_aParams['search_model'] = $searchmode;
        $this->_aParams['station']      = $station;
        $this->_aParams['invite_time']  = $invite_time;
        $this->_aParams['result']       = $audition_result;
        $this->_aParams['keyword']      = $keyword;
        $this->_aParams['showfilter']   = false;

		if (!base_lib_BaseUtils::nullOrEmpty($station)
            || !base_lib_BaseUtils::nullOrEmpty($invite_time)
            || !base_lib_BaseUtils::nullOrEmpty($audition_result)
            || !base_lib_BaseUtils::nullOrEmpty($keyword)) {

            $this->_aParams['showfilter'] = true;	
		}

		$xml = SXML::load('../config/config.xml');

        $this->_aParams['ShowResumeNum'] = $xml->ShowResumeNum;
        $this->_aParams['title']         = "面试邀请 简历管理_{$xml->HuiBoSiteName}";
        if($search_type == 1)
            return $this->render('resume/invite/noaudition.html', $this->_aParams);

        
        return $this->render('resume/invite/hasaudition.html', $this->_aParams);
    }

    // 进入面试结果设置页面
    public function pageSetResult($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $ids     = base_lib_BaseUtils::getStr($path_data['ids'], 'string', '');
        $operate = base_lib_BaseUtils::getStr($path_data['op'], 'string', '');

        $this->_aParams['ids'] = $ids;
    	return  $this->render('resume/invite/setresult.html', $this->_aParams);    		
    }

	/**
	 * @desc  删除面试邀请
	 * @return
	 */
	public function pageDelInvite($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $operate = base_lib_BaseUtils::getStr($path_data['op']); 
        $ids     = base_lib_BaseUtils::getStr($path_data['ids'], 'array', null);
        $ids     = base_lib_BaseUtils::getIntArrayOrString($ids);
    

        $accountid = base_lib_BaseUtils::getCookie('accountid');
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
        switch ($operate) {
			case 'del':
		        if (base_lib_BaseUtils::nullOrEmpty($ids)) {
		        	echo json_encode(array("error"=>"请选择要删除的面试邀请"));
		        	return;
		        }

		        //要删除的ids
		        $service_invites = new base_service_company_resume_jobinvite();
                $deleteApplies = $service_invites->getInvitesByIDs(implode(",", $ids), "company_id,apply_id")->items;
                $result        = $service_invites->delete_invite($company_resources->all_accounts, implode(",", $ids));

		        //设置info_job_apply里面的is_company_deleted为删除
		        $applyids = array();
		        foreach ($deleteApplies as $apply) {
		            if (!base_lib_BaseUtils::nullOrEmpty($apply["apply_id"]) && !in_array($apply["apply_id"], $applyids)) {
		                array_push($applyids, $apply["apply_id"]);
		            }
		        }

		        if (count($applyids) > 0) {
			        $applyModel = new base_service_company_resume_apply();
			        $applyModel->SetCompanyDeleteOfIDs($company_resources->all_accounts, $applyids);        	
		        }

		        if ($result) {
		            echo json_encode(array("success" => true));
		        } else {
		            echo json_encode(array("error" => "删除面试邀请失败"));
		        }
		        break;		

			default:
	    		// 提示
	    		$names =  explode(',', urldecode($path_data['names']));
                $this->_aParams['names'] = $names;
                $this->_aParams['ids']   = implode(',',$ids);
	    		return  $this->render('resume/invite/delete.html', $this->_aParams);
	   }
	}
 
	/**
	 * 
	 * 下载简历页面
	 */
	public function pageDownLoad($inPath) {
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_ids = base_lib_BaseUtils::getStr($params['resumeid']);
		
        $this->_aParams['resumeids'] = $resume_ids;
		return $this->render('resume/invite/down.html', $this->_aParams);	
	}	    
    
 	/**
      * 获取性别
      */
    private function getSex($sex,$default='') {
    	if (base_lib_BaseUtils::nullOrEmpty($sex) || $sex=='0') {
            return $default;
        }
        $enum_sex = new base_service_common_sex();
        return $enum_sex->getName($sex);
    }
     
     /**
      * 获取学历
      */
     private function getDegree($degree_id,$default='') {
		 if(empty($degree_id)) {
		 	return $default;
		 }
		 $service_degree = new base_service_common_degree();
		 return $service_degree->getDegree($degree_id);
     }

    /**
     * 批量发送面试邀请弹窗2（只针对求职者主动投递简历批量发起面试邀请）
     */
    public function pageInviteMultiShow2($inPath) {
        if(!$this->canDo("invite_resume")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
        }
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $apply_ids     = base_lib_BaseUtils::getStr($path_data['applyids'], 'array', null);
        $isShowStopJob = base_lib_BaseUtils::getStr($path_data['isShowStopJob'], 'int', '2');

        $service_company = new base_service_company_company();
    	$currentCompany = $service_company->getCompany($this->_userid, 1, 'company_name,company_shortname,com_level,end_time,address,linkman,link_tel,link_mobile');
    	if (empty($currentCompany)) {
			$this->_aParams['message'] = '请登录';
			return $this->render('showerror.html', $this->_aParams);
		}

        $com_level = $currentCompany['com_level'];
        $end_time  = $currentCompany['end_time'];

    	// 判断会员级别
		if ($com_level <= 1) {
			$this->_aParams['message'] = '贵公司尚未开通服务';
			return $this->render('showerror.html', $this->_aParams);
		}

		// 判断会员期
		if (empty($end_time) || strtotime(date('Y-m-d', time())) > strtotime(date('Y-m-d', strtotime($end_time)))) {
			$this->_aParams['message'] = '您的会员已过期';
			return $this->render('showerror.html', $this->_aParams);
		}

		// 能否发送完整短信
        if ($this->canSendFullMsg($this->_userid)) {
			$this->_aParams["canComplete"] = true ;
		}

		// 获取会员级别名称
        $companyLevelService = new base_service_company_level();    //
		$companyLevelName = $companyLevelService->getName($currentCompany["com_level"]);
        $this->_aParams["companyLevelName"] = $companyLevelName;
		
        $service_resume     = new base_service_person_resume_resume();
        $service_invitetype = new base_service_company_resume_invitetype();

        $invite_type = $service_invitetype->jobapply;//邀请类型 默认为求职者主动投递
        $resume_arr  = null;
        $apply_arr   = null;
        $resume_ids  = null;

        // 获取会员的会员类型（HR会员）
        

        $accountid = base_lib_BaseUtils::getCookie('accountid');
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
        $this->_aParams['company_resources'] = $company_resources;

        if (!empty($apply_ids)) {
			$service_apply = new base_service_company_resume_apply();
			$apply_arr = $service_apply->getApplys($apply_ids, 'apply_id,resume_id,job_id,station,company_id');

            if (empty($apply_arr) || count($apply_arr->items) == 0) {
				$this->_aParams['message'] = '获取求职者申请信息失败';
				return $this->render('showerror.html', $this->_aParams);
			}

            $company_ids = array_unique(base_lib_BaseUtils::getProperty($apply_arr->items, "company_id"));
            if (count($company_ids) > 1) {
                $this->_aParams['message'] = "只能同一家公司的简历批量操作，请按公司操作！";
                return $this->render("showerror.html", $this->_aParams);
            }

            $cur_com_id = array_pop($company_ids);
            if (!in_array($cur_com_id, $company_resources->all_accounts)) {
                $this->_aParams['message'] = "对不起，您没有该企业权限";
                return $this->render("showerror.html", $this->_aParams);
            }

			$resume_ids = base_lib_BaseUtils::getProperty($apply_arr->items, 'resume_id');
			$resume_arr = $service_resume->getResumes($resume_ids, 'resume_id,person_id,is_chinese_resume,relate_resume_id');

            /* 获取公司名称 */
            $cur_company = $service_company->getCompany($cur_com_id, 1, "company_name,company_shortname");
            $currentCompany['company_name']      = $cur_company['company_name'];
            $currentCompany['company_shortname'] = $cur_company['company_shortname'];

            foreach ($apply_arr->items as $theitem => $thevalue) {
				$the_resume = $this->arrayFind($resume_arr->items, 'resume_id', $thevalue['resume_id']);

                $apply_arr->items[$theitem]['person_id']         = $the_resume['person_id'];
                $apply_arr->items[$theitem]['is_chinese_resume'] = $the_resume['is_chinese_resume'];
                $apply_arr->items[$theitem]['relate_resume_id']  = $the_resume['relate_resume_id'];
			}

			if (empty($apply_arr) || count($apply_arr->items) == 0) {
				$this->_aParams['message'] = '获取求职者申请信息失败';
				return $this->render('showerror.html', $this->_aParams);
			}
		} else {
			$this->_aParams['message'] = '获取求职者信息失败';
			return $this->render('showerror.html', $this->_aParams);
		}

        $currentCompany["link_tel"] = $currentCompany["link_tel"] ? $currentCompany["link_tel"] : $currentCompany["link_mobile"];
        $this->_aParams["currentcompany"] = $currentCompany;
        $this->_aParams['invitetype']     = $invite_type;

        //封装简历编号和求职者姓名数组
		foreach ($apply_arr->items as $item => $value) {
            $user_name = $this->getUserName($value);
            $apply_arr->items[$item]['user_name'] = $user_name;
		}
		$this->_aParams['resume_arr'] = $apply_arr->items;
		
		//获取日期
		$date_json = '';
		$weekarray = array("日", "一", "二", "三", "四", "五", "六");
		for ($i = 0; $i < 15; $i++) {
            $date      = date('Y-m-d', strtotime("+{$i} day"));
            $weekday   = date("w", strtotime("+{$i} day"));
            $date_json = "$date_json{id:\"{$date}\",name:\"{$date}[星期{$weekarray[$weekday]}]\"},";
		}

		//$date_json = "$date_json{id:\"99\",name:\"自定义\"},";
		$date_json = substr($date_json, 0, strlen($date_json) - 1);
		$this->_aParams['date_json'] = "[$date_json]";

		// 职位
	    $service_apply = new base_service_company_resume_apply();
		$hasApplyJobs = $service_apply->getHasApplyJobIds($cur_com_id);
		$job_ids      = base_lib_BaseUtils::getProperty($hasApplyJobs->items, 'job_id');

        $jobs = $isShowStopJob == 1 ? $this->_getJobsAndSort($job_ids, 0, null, $use_job_ids=array(), true) : $this->_getJobsAndSort($job_ids, 1, null, $use_job_ids=array(), false);

        $jobs_json = [];
        foreach ($jobs as $job)
            array_push($jobs_json, ["id" => $job['job_id'], "name" => $job['station']]);

        $this->_aParams['jobs'] = json_encode($jobs_json);
        $this->_aParams['pricing_resource_data'] = $company_resources->getCompanyServiceSource(['account_resource']); //获取套餐资源
        $service_company_service_servicePricing = new base_service_company_service_servicePricing();
        $this->_aParams['point_message'] = $service_company_service_servicePricing->GetFunParallelismSelling('point_message');//短信数换算推广金
    	return $this->render('invite/invitepersonmulti2.html', $this->_aParams);
    }
    
    /**
     *@desc 发送批量邀请2（只针对求职者主动投递简历批量发起面试邀请）
     * Enter description here ...
     * @param $inPath
     */
    public function pageSendInviteMulti2($inPath) {
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $chkIndex  = base_lib_BaseUtils::getStr($path_data['chkResume'], 'array', null);
        $consume_point = base_lib_BaseUtils::getStr($path_data['consume_point'],'string',null);
        $pointV2   = base_lib_BaseUtils::getStr($path_data['pointV2'],'string','');
        $pointV2 = round($pointV2 ,3);

        $validator = new base_lib_Validator();

        $service_company = new base_service_company_company();
		$current_company = $service_company->getCompany($this->_userid, 1, 'company_id,com_level,end_time,company_flag,company_name');

		if (empty($current_company)) {
            $json['error'] = '请登录';
			echo header("Content-type:text/plain;charset=utf-8");
			echo json_encode($json);
			return;
		}
		
		$invite_Resumes = null;
        if (empty($chkIndex)) {
        	$validator->addErr('待邀请信息获取失败');

            echo header("Content-type:text/plain;charset=utf-8");
			echo $validator->toJsonWithHtml();
			return;
        }

		$accountid = base_lib_BaseUtils::getCookie('accountid');
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
        $pricing_resource_data = $company_resources->getCompanyServiceSource(['account_resource']);
        /* 推广金 */
        $spread_effect_price = $pricing_resource_data['spread_overage'];
        /* 余额 */
        $account_overage =$pricing_resource_data['account_overage'];
        if ($pricing_resource_data['isCqNewService']){
            if ($pricing_resource_data['cq_release_point_message'] < $consume_point){ //剩余短信点少于发送点
                if ($pointV2 >($spread_effect_price + $account_overage)){
                    if($pricing_resource_data['resource_type'] == 2){
                        $validator->addErr("您的短信点/推广金不足，请联系主账号为您分配更多资源。");
                        echo header("Content-type:text/plain;charset=utf-8");
                        echo $validator->toJsonWithHtml();
                        return;
                    }else{
                        $validator->addErr("短信数/推广金/余额不足");
                        echo header("Content-type:text/plain;charset=utf-8");
                        echo $validator->toJsonWithHtml();
                        return;
                    }

                }
            }
        }


		foreach ($chkIndex as $val) {
			$tmp_resumeID = base_lib_BaseUtils::getStr($path_data['hddResumeID'.$val],'string','');
			//判断是否为新套餐 ， 面试邀请
            if($pricing_resource_data['isCqNewService']){
                $company_resources->consume($func="cq_setmeal_consume", $params=['resume_id'=>$tmp_resumeID,'consume_type'=>'invite','batch'=>true]);
            }

		    if (!base_lib_BaseUtils::nullOrEmpty($tmp_resumeID)) {
                $hddApplyID       = base_lib_BaseUtils::getStr($path_data['hddApplyID' . $val], 'string', '');
                $hddResumeName    = base_lib_BaseUtils::getStr($path_data['hddResumeName' . $val], 'string', '');
                $hddInviteJobID   = base_lib_BaseUtils::getStr($path_data['hddInviteJobID' . $val], 'string', '');
                $hddInviteJobName = base_lib_BaseUtils::getStr($path_data['hddInviteJobName' . $val], 'string', '');

                //职位名称超过10个字进行截取
		    	if (!base_lib_BaseUtils::nullOrEmpty($hddInviteJobName) && mb_strlen($hddInviteJobName) > 10) {
		    		$hddInviteJobName = mb_substr($hddInviteJobName, 0, 10) . '...';
		    	}
		    	$hddInviteDate = base_lib_BaseUtils::getStr($path_data['hddInviteDate' . $val], 'string', '');
		    	$hddInviteTime = base_lib_BaseUtils::getStr($path_data['hddInviteTime' . $val], 'string', '');
		    	$hddInviteCustomTime = base_lib_BaseUtils::getStr($path_data['hddInviteCustomTime' . $val], 'string', '');

		    	$invite_Resumes[$hddApplyID] = array(
                    "ApplyID"       => $hddApplyID,
                    "ResumeID"      => $tmp_resumeID,
                    "ResumeName"    => $hddResumeName,
                    "InviteJobID"   => $hddInviteJobID,
                    "InviteJobName" => $hddInviteJobName,
                    "InviteDate"    => $hddInviteDate,
                    "InviteTime"    => $hddInviteTime,
                    "CustomTime"    => $hddInviteCustomTime
		    	);
		    }
		}

		if (empty($invite_Resumes)) {
            $validator->addErr("批量发送面试邀请失败");

			echo header("Content-type:text/plain;charset=utf-8");
			echo $validator->toJsonWithHtml();
			return;
		}
		
		//职位申请编号
	    $apply_ids = base_lib_BaseUtils::getProperty($invite_Resumes, 'ApplyID');

	    //简历编号
		$resume_ids = base_lib_BaseUtils::getProperty($invite_Resumes, 'ResumeID');
		
		//简历名称
		$user_names = base_lib_BaseUtils::getProperty($invite_Resumes, 'ResumeName');
		
		//公司名称
		$sms_company_name = $validator->getNotNull($path_data['company_name'], '请输入公司名称');
		$validator->getStr($path_data['company_name'], 1, 20, '公司名称请输入1-20个字');
		
    	$service_resume = new base_service_person_resume_resume();
		
        //邀请类型
		$invite['invite_type'] = 0;
		//面试地点
		$invite['audition_address'] = $validator->getNotNull($path_data['address'], '请输入面试地点');
		$validator->getStr($path_data['address'], 1, 60, '面试地点请输入1-60个字符');
		//联系人
		$invite['audition_link_man'] = $validator->getNotNull($path_data['linkman'], '请输入联系人');
		$validator->getStr($path_data['linkman'], 1, 15, '联系人请输入1-15个字符');
		//联系电话
		$invite['audition_link_tel'] = $validator->getTel($path_data['link_tel'], '联系电话不正确', false);
		//其他
		$invite['audition_remark'] = $validator->getStr(str_replace("如需带什么资料等...", "", $path_data['remark']), 0, 200, '其他信息不能超过200个字', true);
		
    	//短信类型
    	$sms_type = base_lib_BaseUtils::getStr($path_data['smsType'], 'int', 1);
    	if ($sms_type != 1 && $sms_type != 2) {
    		$sms_type = 1;
    	}
    	if ($sms_type == 2) {
    		$companyLevelService = new base_service_company_level();
    		//判断是否可以发送完整邀请短信
    		if (!$this->canSendFullMsg($this->_userid)) {
    			$sms_type = 1;
    		}
    		
    	}
    	
        if ($validator->has_err) {
    		echo header("Content-type:text/plain;charset=utf-8");
			echo $validator->toJsonWithHtml();
			return;
    	}
    	
        //验证单位相关信息
        $com_level = $current_company['com_level'];
        $end_time  = $current_company['end_time'];
    	//判断会员级别
		if ($com_level <= 1) {
            $json['error'] = '贵公司尚未开通服务';
			echo header("Content-type:text/plain;charset=utf-8");
			echo json_encode($json);
			return;
		}

		//判断会员期
		if (empty($end_time) || strtotime(date('Y-m-d', time())) > strtotime(date('Y-m-d', strtotime($end_time)))) {
            $json['error'] = '您的会员已过期';
			echo header("Content-type:text/plain;charset=utf-8");
			echo json_encode($json);
			return;
		}

		//获取IP
		$ip = base_lib_BaseUtils::getIp(0);
        $service_invite            = new base_service_company_resume_jobinvite();
        $service_person            = new base_service_person_person();
        $service_jobinviterestatus = new base_service_company_resume_jobinviterestatus();
        $service_jobapplyrestatus  = new base_service_person_jobapplyrestatus();
        $service_auditionresult    = new base_service_company_resume_auditionresult();

        $successItem   = '';
        $failItem      = '';
        $successPerson = array();
		
		// 获取职位申请信息
		$service_apply = new base_service_company_resume_apply();
	    $applys = $service_apply->getApplysOrderBySplitIdStr($apply_ids, 'apply_id,resume_id,station,re_time,re_status,is_read_com_reply,person_id,job_id,company_id,create_time,is_company_deleted,person_remark,person_re_status,is_cancelled');
		$apply_infos_station = base_lib_BaseUtils::getProperty($applys->items,'station');
        // 获取当前申请的企业id
        $cur_company_id = array_unique(base_lib_BaseUtils::getProperty($applys->items, "company_id"));
        if (count($cur_company_id) > 1)
            $validator->addErr("只能同一家公司的简历批量操作，请按公司操作！");

        $cur_company_id = array_pop($cur_company_id);
       	
        $accountid = base_lib_BaseUtils::getCookie('accountid');
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
        if (!in_array($cur_company_id, $company_resources->all_accounts))
            $validator->addErr("您没有该投递的操作权限");

        if ($validator->has_err) {
            echo header("Content-type:text/plain;charset=utf-8");
            echo $validator->toJsonWithHtml();
            return;
        }

        //单位编号
        $invite['company_id'] = $cur_company_id;
        //是否有效
        $invite['is_effect'] = 1;
        //求职者是否已阅读
        $invite['is_readed'] = 0;
        //面试结果
        $invite['audition_result'] = $service_auditionresult->notset;
        //邀请时间
        $invite['create_time'] = date('Y-m-d H:i:s',time());

	    //开始准备发送邀请信息
	    if (!empty($applys)) {
            $shorturldomain_len = 16;
            $key_length         = 5;
	    	$xml = SXML::load('../config/company/company.xml');
             //发送聊一聊消息
            $service_qqcloud_msg = new base_service_interface_qqcloudsendmsg();
            $qq_cloud_content    = "您好，您的简历符合我们的要求，已向您发送面试邀请，请注意查看准时到达。如有特殊情况，请提前与我们联系。";
            $from_id             = base_lib_BaseUtils::getCookie('accountid');
            
	    	foreach ($applys->items as $item => $val) {
	    	   $user_name = $invite_Resumes[$val['apply_id']]['ResumeName'];
                if ($val['is_company_deleted'] == '1') {
					$failItem .= "<br />该求职申请已删除，邀请{$user_name}失败";
					continue;
				}
				
				if ($val['is_cancelled'] == '1') {
					$failItem .= "<br />求职者已撤销申请，邀请{$user_name}失败";
					continue;
				}

                //职位ID
				$job_id = $invite_Resumes[$val['apply_id']]["InviteJobID"];
				$service_job = new base_service_company_job_job();
				$current_job = $service_job->getJob($job_id, 'end_time,station', $cur_company_id);

				//面试时间
                $audition_time = '';
                $date          = '';
                $sms_time      = '';
				$date = $invite_Resumes[$val['apply_id']]['InviteDate'];
				if (empty($date) || is_null($date)) {
					$failItem .= "<br />请设置面试时间，邀请{$user_name}失败";
					continue;
				}

				if ($date == '99') {
					$audition_time = $invite_Resumes[$val['apply_id']]['CustomTime'];
					if (empty($audition_time) || is_null($audition_time)) {
						$failItem .= "<br />请设置面试时间，邀请{$user_name}失败";
					    continue;
					}
				    $sms_time = $validator->getStr($invite_Resumes[$val['apply_id']]['CustomTime'], 1, 20, '面试时间请输入1-20个字');
					if (base_lib_BaseUtils::nullOrEmpty($sms_time) || mb_strlen($sms_time) < 1 ||  mb_strlen($sms_time) > 20) {
						$failItem .= "<br />面试时间请输入1-20个字，邀请{$user_name}失败";
					    continue;
					}
				} else {
					$time = $invite_Resumes[$val['apply_id']]['InviteTime'];
				    if (empty($time) || is_null($time)) {
						$failItem .= "<br />请设置面试时间，邀请{$user_name}失败";
					    continue;
					}
                    
                    $temp_date = "$date $time:00";
                    $audition_time = base_lib_BaseUtils::getStr($temp_date, 'datetime', null);
                    if (base_lib_BaseUtils::nullOrEmpty($audition_time)) {
                    	$failItem .= "<br />请设置面试时间，邀请{$user_name}失败";
                        continue;
                    }

                    $date_arr = explode('-', $date);
                    $sms_time = str_replace('00', '', str_replace(':', '点', $time));
                    if (count($date_arr) > 2) {
                        $sms_time = (int)$date_arr[1] . '月' . (int)$date_arr[2] . '日' . $sms_time;
                    }
				}

				$station = $invite_Resumes[$val['apply_id']]["InviteJobName"];
                $invite['apply_id']      = $val['apply_id'];
                $invite['resume_id']     = $val['resume_id'];
                $invite['station']       = $station;
                $invite['job_id']        = $job_id;
                $invite['audition_time'] = $audition_time;
                $invite['re_status']     = $service_jobapplyrestatus->interview;
				
	    	    if (!empty($current_job)) {
                    $invite['end_time'] = $current_job['end_time'];
                    $invite['station']  = $current_job['station'];
				} else {
					$invite['end_time'] = date('Y-m-d H:i:s', strtotime ('+7 day'));
				}

	    	    //获取简历
				$resume = $service_resume->getResume($invite['resume_id'], 'resume_id,person_id');
				if (empty($resume)) {
					$failItem .= "<br />获取求职者{$user_name}简历失败";
					continue;
				}
				
	    	    //获取求职者
				$person = $service_person->getPerson($resume['person_id'], 'person_id,user_name,user_id,email,mobile_phone');
		    	if (empty($person)) {
					$failItem .= "<br />获取求职者{$user_name}信息失败";
					continue;
				}
				
				$invite['person_id'] = $person['person_id'];
				
    			//生成短信内容
		    	$sms_content = $this->createSmsContent($sms_type, $sms_company_name, $station, $sms_time,
                    $invite['audition_address'], $invite['audition_link_man'], $invite['audition_link_tel'], $invite['audition_remark']);

		 		if (!is_null($xml)) {
		 			$shorturldomain_len = mb_strlen($xml->ShortUrlDomain);
		 		}

		 		$total_len = mb_strlen($sms_content) + $shorturldomain_len + $key_length + mb_strlen('详情');
		    	if ($sms_type == 1 && $total_len > 60) {
                    $failItem .= "<br />已超出" . ($total_len - 60) . "个字，无法发送短信，请删除部分内容，邀请{$user_name}失败";
                    continue;
		    	} else if ($sms_type == 2 && mb_strlen($sms_content) > 100) {
                    $failItem .= "<br />已超出" . (mb_strlen($sms_content) - 100) . "个字，无法发送短信，请删除部分内容，邀请{$user_name}失败";
                    continue;
		    	}

                //发送邀请
                $cur_company = $service_company->getCompany($cur_company_id, 1, $field="company_name,company_shortname,company_id,company_flag");
                if (!$pricing_resource_data['isCqNewService']) {
                    $add_result = $service_invite->initiativeInvite(base_lib_BaseUtils::getCookie('accountid'),$sms_type, $sms_content, $cur_company, $resume, $ip, $invite, $person, $val);
                }else{
                    $add_result = $service_invite->initiativeInviteV3($sms_type, $sms_content, $cur_company, $resume, $ip, $invite, $person, $val);
                }

                if (!$add_result) {
                    $failItem .= "<br />邀请{$user_name}失{$add_result}败";
                }
		    	$successItem .= "<br />邀请{$user_name}成功";
		    	array_push($successPerson, array('personid'=>$person['person_id'], 'apply_id'=>$invite['apply_id'], 'message'=>$this->MsgContent($sms_company_name, $station)));
                $service_qqcloud_msg->addPreSendMsg("CI", $from_id, $person['person_id'], $qq_cloud_content, $resume["resume_id"], $invite["job_id"]);
	    	}
	    } else {
            $json['error'] = '获取求职者申请信息失败';
	    	echo header("Content-type:text/plain;charset=utf-8");
			echo json_encode($json);
			return;
	    }

	    if (!base_lib_BaseUtils::nullOrEmpty($failItem)) {
			echo json_encode(array('status'=>'fail', 'failitem'=>$failItem, 'successitem'=>$successItem, 'inviteperson'=>$successPerson));
    		return;
		}

		//---------添加操作日志--------
		$common_oper_type = new base_service_common_account_accountoperatetype();
		$service_oper_log = new base_service_company_companyaccountlog();
		$common_oper_src_type = new base_service_common_account_accountlogfrom();
		$insertItems=array(
			"company_id"=>$this->_userid,
			"source"=>$common_oper_src_type->website,
			"account_id"=>base_lib_BaseUtils::getCookie('accountid'),
			"operate_type"=>$common_oper_type->resume_sendinvit,
			"content"=>"已批量发送简历邀请，包含下简历：".implode('，',$apply_infos_station),
			"create_time"=>date("Y-m-d H:i:s",time())
		);
		$service_oper_log->addLogToMongo($insertItems);
        //保存动作类型
        //保存登录动作
        //动作类型
        $service_actiontype = new base_service_common_actiontype();
        //用户类型
        $service_actionusertype = new base_service_common_actionusertype();
        //渠道
        $service_actionsource = new base_service_common_actionsource();
        base_lib_BaseUtils::saveAction($service_actiontype->invite, $service_actionsource->website, $this->_userid, $service_actionusertype->company);
		//-------------END------------
		echo json_encode(array('status'=>'succeed', 'inviteperson'=>$successPerson));
    	return;
    }
    
    /**
	 * @desc  获取职位信息并排序
	 * @param  $job_ids
	 */
	private function _getJobsAndSort($job_ids,$show_use_job=1,$job_id=null,&$use_job_ids,$showStopJobApply=false){
		$service_job = new base_service_company_job_job();
		$jobs = $service_job->getJobs($job_ids, 'job_id,station,end_time,status');
		$validJob = array();
		$voidJob = array();
		$status = new base_service_common_jobstatus();
		foreach ($jobs as $job) {			
			if($job['status']!=$status->use||base_lib_TimeUtil::time_diff_day($job['end_time'], date('Y-m-d H:i:s'))>0) {
				if($showStopJobApply ==true || $show_use_job !=1){
					$job['station'] =  base_lib_BaseUtils::cutstr($job['station'], 8,'utf-8','','...')."<span class='orange'>(停招)</span>";
					array_push($voidJob, $job);
				}
			}else {
				$job['station'] =  base_lib_BaseUtils::cutstr($job['station'], 10,'utf-8','','...');
				$use_job_ids[] = $job['job_id'];
				array_push($validJob, $job);
			}
		}

		return array_merge($validJob,$voidJob);
	}
}
?>