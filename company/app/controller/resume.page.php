<?php

/**
 * @copyright 2002-2013 www.huibo.com
 * @name  简历
 * @author    ZhangYu
 * @version   2013-9-04
 */
class controller_resume extends components_cbasepage {

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
		ob_start();
	}

	// /**
	//  * 简历控制器
	//  * @param string $inPath
	//  */
	// public function pageControl($inPath) {
	// 	// 企业会员收到过该简历的求职申请或下载过该简历的，
	// 	// 1.企业会员过期或者过期时间未超过10天并且非普通会员 可以查看联系方式
	// 	// 2. 如果是普通会员的，显示开通服务链接
	// 	// 3. 否则显示
	// 	$service_company = new base_service_company_company();
	// 	$service_person  = new base_service_person_person();
	// 	$service_resume  = new base_service_person_resume_resume();

	// 	$service_person->dbquery = true; //load data from subordinate database
	// 	$service_resume->dbquery = true; //load data from subordinate database

	// 	$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
	// 	$type = $pathdata['type'];

	// 	$is_show_name    = false;
	// 	$is_show_linkway = false;
	// 	$is_apply_resume = false;   //收到的简历
	// 	$resume_id = 0;
	// 	switch ($type) {
	// 		case 'apply':
	// 			// 收到的简历处理逻辑
	// 			$apply_id = base_lib_BaseUtils::getStr($pathdata['applyid'], 'int', 0);
	// 			$service_apply = new base_service_company_resume_apply();
	// 			$apply = $service_apply->getApplyByID($apply_id, $this->_userid, 'resume_id,is_cancelled,station');
	// 			if (base_lib_BaseUtils::nullOrEmpty($apply['resume_id'])) {
	// 				return '<div class="datErr">没有找到申请的记录</div>';
	// 			}

	// 			$service_apply->setApplyReaded($apply_id, $this->_userid);
	// 			if ($apply['is_cancelled'] == 1) {
	// 				return '<div class="datErr">对方主动放弃该职位，无法查看TA的简历 </div>';// '对方主动放弃该职位，无法查看TA的简历';//
	// 			}

	// 			$this->_aParams['applystation'] = $apply['station'];
	// 			$resume_id = $apply['resume_id'];
	// 			$is_show_name    = true;
	// 			$is_show_linkway = true;
	// 			$is_apply_resume = true;
	// 			break;
	// 		case 'invite':
	// 			// 邀请的处理逻辑
	// 			$invite_id = base_lib_BaseUtils::getStr($pathdata['inviteid'], 'int', 0);
	// 			$service_invite = new base_service_company_resume_jobinvite();
	// 			$invite = $service_invite->getInviteByID($invite_id, $this->_userid, 'invite_id,resume_id', 1);
	// 			if (base_lib_BaseUtils::nullOrEmpty($invite['resume_id'])) {
	// 				return '<div class="datErr">没有找到邀请的记录</div>';
	// 			}

	// 			$resume_id = $invite['resume_id'];
	// 			$is_show_linkway = true;
	// 			break;
	// 		case 'down':
	// 			$down_id = base_lib_BaseUtils::getStr($pathdata['downid'], 'int', 0);
	// 			$service_down = new base_service_company_resume_download();
	// 			$down = $service_down->getDownloadByCompanyID($down_id, $this->_userid, 'download_id,resume_id',1);
	// 			if (base_lib_BaseUtils::nullOrEmpty($down['download_id'])) {
	// 				return '<div class="datErr">没有找到下载的记录</div>';
	// 			}
	// 			$resume_id = $down['resume_id'];
	// 			$is_show_name    = true;
	// 			$is_show_linkway = true;
	// 			break;
	// 		case 'fav':
	// 			$fav_id = base_lib_BaseUtils::getStr($pathdata['favid'],'int',0);
	// 			$service_fav = new base_service_company_resume_fav();
	// 			$fav = $service_fav->getFav($this->_userid, $fav_id, 'fav_id,resume_id');
	// 			if (base_lib_BaseUtils::nullOrEmpty($fav['resume_id'])) {
	// 				echo '<div class="datErr">未找到对应的简历信息!</div>';
	// 				return;
	// 			}
	// 			$resume_id = $fav['resume_id'];
	// 		default:
	// 			// 默认 提交的是 简历编号
	// 			if ($type != 'fav') {
	// 				$resume_id = base_lib_BaseUtils::getStr($pathdata['resumeid'], 'int', 0);
	// 				if (empty($resume_id)) {
	// 					return;
	// 				}
	// 			}
	// 			$resume = $service_resume->getResume($resume_id, 'resume_id,person_id');
	// 			if (empty($resume)) {
	// 				echo '<div class="datErr">简历已经被删除了，无法查看!</div>';
	// 				return;
	// 			}
	// 			$person = $service_person->getPerson($resume['person_id'],'name_open,user_name,user_name_en,open_mode');
	// 			if (empty($person)) {
	// 				return;
	// 			}				
	// 			if ($person['name_open'] == 1) {
	// 				$is_show_name = true;
	// 			}

	// 			$service_apply    = new base_service_company_resume_apply();
	// 			$service_download = new base_service_company_resume_download();
	// 			// 验证求职者是否投递过企业的职位
	// 			if ($service_apply->isApply($this->_userid, $resume['resume_id'])) {
	// 				$is_show_linkway = true;
	// 				$is_show_name    = true;
	// 			}
	// 			else {
	// 				// 验证企业是否下载过该简历
	// 				if ($service_download->isResumeDownloaded($this->_userid, $resume['resume_id'])) {
	// 					$is_show_linkway = true;
	// 				}
	// 			}
	// 			break;
	// 	}
	// 	// 验证企业信息
	// 	$company = $service_company->getCompany($this->_userid, '1', 'company_id,end_time,com_level');
	// 	$not_member     = false;
	// 	$member_expires = false;

	// 	$this->_aParams['member_info'] = $this->getCompanyMemberInfo();
	// 	return $this->__resumePreview($resume_id, $is_show_name, $is_show_linkway, $not_member, $member_expires, $is_apply_resume);
	// }

	/**
	 *
	 * 简历显示
	 * @param  $inPath
	 */
	public function pageResumeShow($inPath) {
		if (!$this->canDo("see_resume_info")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";

			return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

		//判断是否超出简历查看数量
		$cookie_account_id = isset($_POST['upload_cookie_accountid']) ? $_POST['upload_cookie_accountid'] : base_lib_BaseUtils::getCookie('accountid');
		$ser_resume = new base_service_person_resume_resume;
		$re = $ser_resume->company_check_resume($cookie_account_id, $pathdata['resumeid']);
		$service_apply = new base_service_company_resume_apply();
		$service_company_resume_download = new base_service_company_resume_download();
		$service_company_resume_jobinvite = new base_service_company_resume_jobinvite();

		if (!$re) {
			$this->_aParams["msg"] = "今日您查看简历已超过500份";
			$this->_aParams["url"] = "/";

			return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}

		$resume_id = base_lib_BaseUtils::getStr($pathdata['resumeid'], 'int', 0);
		$origin = base_lib_BaseUtils::getStr($pathdata['type'], 'string', 'network');
		$keyword = base_lib_BaseUtils::getStr($pathdata['redKeyword'], 'string', '');
		$resumesrc = base_lib_BaseUtils::getStr($pathdata['src'], 'string', 'network');
		$applyid = base_lib_BaseUtils::getStr($pathdata['applyid'], 'int', 0);
		$recommendid = base_lib_BaseUtils::getStr($pathdata['recommendid'], 'int', 0);
		$invitid = base_lib_BaseUtils::getStr($pathdata['invitid'], 'int', 0);
		$from = base_lib_BaseUtils::getStr($pathdata["from"], "string", "");
		$visit_id = base_lib_BaseUtils::getStr($pathdata["visit_id"], "string", "0");
		$high_light = base_lib_BaseUtils::getStr($pathdata["dh"], "string", "");
        
		$is_show_name = false;
		$is_show_linkway = false;
		$is_show_resumeinfo = false;
		if (empty($resume_id)) {
			return;
		}
		$companyService = base_service_company_company::getInstances();
		$company = $companyService->getCompany($this->_userid, true, 'company_id,company_name,is_audit,audit_state,is_effect,company_flag,area_id,info,address,'
			. 'resume_download_upperlimit,com_level,start_time,end_time,effect_job_end_time,hr_manager,create_time,'
			. 'hr_manager_sex,hr_tel,company_logo_path,company_bright_spot,property_id,size_id,'
			. 'company_shortname,email,linkman,link_tel,calling_id,calling_ids,'
			. 'is_proxyed,proxy_com_id,hrlicence,recruit_type'    //hr会员提醒
		);

		$xml = SXML::load('../config/company/company.xml');
		//判断该公司今天查看简历的次数是否已经达到限制的次数
		if ($resumesrc == "search") {
			$service_resumevisit = new base_service_company_resume_resumevisit();
			if (intval($xml->checkResumeCountLimit)) {
				if (intval($xml->checkResumeCountLimit) <= $service_resumevisit->getAreadyCheckedResumeCount($this->_userid)) {
					$this->_aParams['msg'] = "您今天查看的简历份数已达到上限 {$xml->checkResumeCountLimit} 份，请明天再查看！";

					return $this->render('./resume/msg.html', $this->_aParams);
				}
			}
		}

		//2019-8-8 3天内是否被其他公司下载或邀请并且从待处理列表进入
//		$invite_num = $service_company_resume_jobinvite->getInviteNum($resume_id, $this->_userid);
//		$down_num = $service_company_resume_download->getResumeDownNum($resume_id, $this->_userid);
//		$this->_aParams['is_interested'] = ($invite_num || $down_num) && preg_match('/[\/]apply[\/|?|-]?/i', $_SERVER['HTTP_REFERER']) &&
//			(preg_match('/[\/|?|-|&]status[-|=]2/i', $_SERVER['HTTP_REFERER']) || !preg_match('/[\/|?|-|&]status[-|=]/i', $_SERVER['HTTP_REFERER']));

		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		$member_info = $company_resources->isMember() ? "member" : "not_member";
		$result = $this->checkResume($resume_id, $is_show_name, $is_show_linkway, $member_info, $member_expires = false, $is_show_resumeinfo);

		if (!$result) {
			$this->_aParams['msg'] = '您查看的简历不存在或未向您公开';

			return $this->render('./resume/msg.html', $this->_aParams);
		}

		if ($origin == 'network') {
			$this->getLastApplyInfo($this->_userid, $resume_id);
		}
		else {
			// 获取现场服务的状态
		}

		$this->_aParams['homePage'] = base_lib_Constant::MAIN_URL_NO_HTTP;
		$this->_aParams['company_id'] = $this->_userid;
		$this->_aParams['company_name'] = $this->_username;
		$this->_aParams['origin'] = $origin;
		$this->_aParams['redKeyword'] = $keyword;
		$this->_aParams['applyid'] = $applyid;
		$this->_aParams["is_kuaimi"] = false;
		$this->_aParams["is_job_effect"] = true;
		if (!base_lib_BaseUtils::nullOrEmpty($applyid) && $resumesrc == "apply") {
			$apply_info = $service_apply->getApplyByCompanyId($applyid, $this->_userid, "apply_id,re_status,has_read,job_id");
			$this->_aParams['apply_info'] = $apply_info;
			$this->_aParams["apply_job_id"] = $apply_info["job_id"];
			//判断是否快米投递简历同步而来
			$service_company_resume_applyresource = new base_service_company_resume_applyresource();
			$service_common_applyresource = new base_service_common_applyresource();

			$apply_resource_info = $service_company_resume_applyresource->getApplyListById($applyid);
			if ($apply_resource_info && $apply_resource_info['resouce_type'] == $service_common_applyresource->kuaimi) {
				$this->_aParams["is_kuaimi"] = true;
			}
			$service_job = new base_service_company_job_job();
			$job_info = $service_job->getJob($apply_info["job_id"], "check_state,end_time,status,is_effect");
			$job_status = new base_service_common_jobstatus();
			$_time = date("Y-m-d") . " 00:00:00";
			if ($job_info['check_state'] > 1
				|| ($job_info['status'] == $job_status->use
					&& $job_info['end_time'] < $_time)
				|| $job_info['status'] == $job_status->stop_use
				|| $job_info['status'] == $job_status->deleted
				|| $job_info['is_effect'] == '0') {
				$this->_aParams["is_job_effect"] = false;
			}
		}

		$this->_aParams['recommendid'] = $recommendid;
		$this->_aParams['src'] = $resumesrc;
		$this->_aParams['resume_id'] = $resume_id;
		$this->_aParams["from"] = $from;
		if ($resumesrc == 'apply') {
			$this->_aParams['src_id'] = $applyid;
		}
		else if ($resumesrc == 'invite') {
			$this->_aParams['src_id'] = $invitid;
		}
		else if ($resumesrc == 'autorecommend') {
			//企业查看自动推荐简历，记录查看账号，2周内置灰
			$service_company_resume_resumevisit = new base_service_company_resume_resumevisit();
			$service_company_resume_resumevisit->setCompanyRecommendResume($this->_userid, $accountid, $resume_id);
		}

		$this->_fill($resume_id, $is_show_name, $is_show_linkway, $member_info, false, $is_show_resumeinfo, false, false, $applyid);

		$this->_getResumeHistoryRecord($this->_userid, $resume_id);
		//   更新redis缓存,更新redis缓存 写入今天已经读的不同简历的份数
		if ($resumesrc == "search") {
			if (intval($xml->checkResumeCountLimit)) {
				$service_resumevisit->setAreadyCheckedResume($this->_userid, $resume_id);
			}
		}

		$xml = SXML::load('../config/config.xml');
		$this->_aParams['title'] = $this->_aParams['user_name'] . "-{$xml->HuiBoSiteName}";
		$this->_aParams['downloaded'] = !($this->_aParams['show_linkway'] != true
			&& $this->_aParams['not_member'] != true
			&& $this->_aParams['member_expires'] != true);//false表示未下载，true表示已经下载

		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		$companyresources = $company_resources->getCompanyServiceSource();
		$this->_aParams['companyresources'] = $companyresources;
		$this->_aParams['visit_id'] = $visit_id;

		//是否来自成都
		$this->_aParams['from_cd'] = preg_match('/^1501/i', $company['area_id']) ? true : false;
		if (!$this->_aParams['from_cd']) {
			$this->_aParams['pricing_resource_data'] = $company_resources->getCompanyServiceSource(["cq_pricing_resource"]); //获取套餐资源
		}
		//调试参数
//        $this->_aParams['from_cd'] = true;

		$serviceArea = new base_service_company_service_serviceArea();
		//已超出简历下载地区限制，请联系工作人员开通相应招聘服务。
		$not_area_limit = $serviceArea->IsServiceAreaTypeDownloadResumeScope($this->_userid, $resume_id);//限制为false
		$this->_aParams['not_area_limit'] = $not_area_limit;


		//是否邀请面试，是否发送过offer
		$service_invite    = new base_service_company_resume_jobinvite();
		$company_resources = new base_service_company_resources_resources($this->_userid);
		$last_invite_list = $service_invite->getLastInviteByResumeidsV2([$resume_id],$company_resources->all_accounts);
		$this->_aParams['invite_id'] = $last_invite_list[0]['invite_id'];


		//获取投递是否与视频面试关联
		/* $service_shuangxuanpersonapplyrelate = new base_service_schoolnet_shuangxuanpersonapplyrelate();
         $relate_data = $service_shuangxuanpersonapplyrelate->getApply($applyid,$this->_userid,'id,apply_id,person_id,company_id,job_id,sid');
         if(empty($relate_data)){
             $this->_aParams['is_video'] = 0;
         }else{
             $this->_aParams['is_video'] = 1;
             $relate_data['resume_id'] = $resume_id;
             $this->_aParams['is_video_data'] = $relate_data;
         }*/


		$common_rsumeexplain = new base_service_common_resumeexplain();
		$this->_aParams['resumeexplain'] = $common_rsumeexplain->getAll();

		//聊一聊状态 ，提示等级 1：(未登录)提示 2：（未登录）提示 + 提示消耗 3：提示消耗
		$service_chat = new company_service_chat(0,0);
		$service_resume = new base_service_person_resume_resume();
		$resume = $service_resume->getResume($resume_id,'resume_id,person_id,create_time');
		$chat_params['resume_id'] = $resume_id;
		$chat_params['person_id'] = $resume['person_id'];
		$chat_params['company_id'] = $this->_userid;
		$this->_aParams['chat_status'] = $service_chat->getChatNoticeStatus($this->_userid,base_lib_BaseUtils::getCookie('accountid'),false,$chat_params,$resume['person_id']);
        
        //2019-10-28 回流简历和新简历不显示浏览量和下载量，显示“new”图标
        $this->_aParams['show7day_data'] = base_lib_BaseUtils::getStr($pathdata['show7day_data'], 'int', 0);
        $this->_aParams['is_from_not_read_list'] = $this->_aParams['show7day_data'] || (preg_match('/[\/]apply[\/|?|-]?/i', $_SERVER['HTTP_REFERER']) &&
			(preg_match('/[\/|?|-|&]status[-|=]2/i', $_SERVER['HTTP_REFERER']) || !preg_match('/[\/|?|-|&]status[-|=]/i', $_SERVER['HTTP_REFERER'])));
        if($this->_aParams['is_from_not_read_list']){
            $service_person_statistics_backfloStatistics = new base_service_person_statistics_backfloStatistics();
            $back_flo = $service_person_statistics_backfloStatistics->isBackFlo($resume['person_id']);
            $resume_list = $service_resume->getResumesByPersonId($resume['person_id'], 'create_time,resume_id');

            $this->_aParams['is_new_resume'] = ($resume_list[0]['resume_id'] == $resume_id && $resume['create_time'] >= date('Y-m-d 00:00:00', strtotime('-7 days'))) || !empty($back_flo);
            if(!$this->_aParams['is_new_resume']){
                $service_person_visit = new base_service_person_visit();
                $service_company_appraise_linkwayget = new base_service_company_appraise_linkwayget();
                
                $this->_aParams['visit_num'] = $service_person_visit->getVisitNumByResumeID($resume_id, 7);
                $this->_aParams['get_link_num'] = count($service_company_appraise_linkwayget->getNumByPersonId($resume['person_id']));
            }
        }
        
        $ser_upload = new base_service_upload_upload();
        $up_options = array ('file_name' => 'reportFile[]', 'fileVal' => 'Filedata', 'auto' => true, 'defaults_files' => $defaults_files,'is_load_jquery'=>false);
        $this->_aParams['up_img_html'] = $ser_upload->GetUploadHtmlDom($up_options, $this->_userid, 'up_style2', '', 'companyReport', '/company/company.xml');
        
        $this->_aParams['high_light'] = $high_light;
		return $this->render('./resume/resume_show.html', $this->_aParams);

	}


	/**
	 * 提醒求职者完善简历
	 */
	public function pageResumeAlertRemind($inPath){
		$pathdata   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_id  = base_lib_BaseUtils::getStr($pathdata['resume_id'], 'int', 0);
		$remind_tpl = base_lib_BaseUtils::getStr($pathdata['remind_tpl'], 'string', '');

		$ser_resume = new base_service_person_resume_resume();
		$resume     = $ser_resume->getResume($resume_id, 'resume_id,person_id');
		$ser_preson = new base_service_person_person();
		$person     = $ser_preson->getPerson($resume['person_id'], 'person_id,user_name');

		$common_resumeexplain = new base_service_common_resumeexplain();
		$explain_tpls         = $common_resumeexplain->getNames(explode(',', $remind_tpl));
		$explain_tpls         = implode('、', $explain_tpls);


		//发送站内信
		$service_message_person = new base_service_message_messageperson();
		$message_content        = "{$person['user_name']}您好，{$this->_username}公司提醒您简历{$explain_tpls}不详细，完善简历可获得更多的求职机会！";
		$items['person_id']     = $person["person_id"];
		$items['subject']       = "企业反馈简历不详细通知";
		$items['content']       = $message_content;
		$items['sender']        = "huibo.com";
		$items['type']          = 1; //普通消息
		$items['link_msg_id']   = $resume_id; //简历id
		$service_message_person->addPersonMessage($items);

		//发送APP消息
		$service_msg                 = new base_service_app_interactionmsg();
		$title                       = "企业反馈简历不详细通知";
		$params                      = array();
		$params["content"]           = $message_content;
		$params["broadcast_content"] = $message_content;
		$service_msg->sendAppMsg($person["person_id"], $title, "T0121", $params);

		echo json_encode(['status' => true]);
	}

	/**
	 * 检查公司余额是否充足（只针对非会员，过期会员）
	 * @param mixed $inPath 链接参数
	 * @return html/json     链接或页面
	 */
	public function pageCheckBalance($inPath) {
		$member_info = $this->getCompanyMemberInfo();
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_id = base_lib_BaseUtils::getStr($pathdata['resume_id'], 'int', 0);

		$resume_service = new base_service_person_resume_resume();
		$resumelevel_service = new base_service_common_resumelevel();

		$resume = $resume_service->getResume($resume_id, "point");
		if (empty($resume)) {
			echo json_encode(array ("status" => false, "msg" => "未找到相应简历"));
			exit;
		}

		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, base_lib_BaseUtils::getCookie('accountid'));
		$resource_data = $company_resources->getCompanyServiceSource(["_account_resource"]);
		$spread_overage = $resource_data['spread_overage'];
		$account_overage = $resource_data['account_overage'];

		$point = empty($resume['point']) ? 1 : $resume['point'];
		$resumelevel = $resumelevel_service->getLevelByPoint($point);
		if ($member_info != "member") {

			/** =============== 判断余额是否充足 part:start=================== **/
			if ($account_overage < $resumelevel['consume']) {
				$this->_aParams['account_overage'] = $account_overage;
				echo json_encode(array ("status" => false, "account_overage" => number_format($account_overage, 2), "consume" => number_format($resumelevel['consume'], 2)));
				exit;
			}
		}

		echo json_encode(array ("status" => true));
		exit;
	}

	/**
	 * 验证企业资质证明是否完善
	 * @param $inPath
	 */
	public function pageCheckCompanyLetter($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$check_type = base_lib_BaseUtils::getStr($pathdata['check_type'], 'string', '');
		$resume_id = base_lib_BaseUtils::getStr($pathdata['resume_id'], 'int', '');

		$check_result = $this->checkCompanyLetter($this->_userid, "resume");
//		echo json_encode(array ("status" => true, "msg" => "成功"));
//		exit;
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		$companyresources = $company_resources->getCompanyServiceSource(['account_resource']);

		$letter_info = $company_resources->getCompanyAuditStatusV2();
		if ($letter_info['audit_type'] == 1) {
			//老规则
			if (!in_array($check_result['code'], array (200, 502, 505, 509, 510))) {
				echo json_encode(array ('code' => 701, "status" => false, "msg" => $check_result['msg'], 'aa' => $check_result, 'letter_info' => $letter_info));
				exit;
			}
		}
		else {
			if ($check_result['code'] != 200) {
				echo json_encode(array ('code' => 701, "status" => false, "msg" => $check_result['msg'], 'aa' => $check_result, 'letter_info' => $letter_info));
				exit;
			}
		}
		//分配子账号
		if ($companyresources['resource_type'] == 2) {
			$effectSpreadTotal = $companyresources['spread_overage'];
			//账户可用余额
			$account_overage = $companyresources['account_overage'];
			//账户可用总资金
			$total = sprintf("%.2f", ($effectSpreadTotal + $account_overage));
			switch ($check_type) {
				case "chat":
					$service_company_service_servicePricing = new base_service_company_service_servicePricing();
					$consume_spread_num = $service_company_service_servicePricing->GetFunParallelismSelling('point_chat');

					$service_apply = new base_service_company_resume_apply();
					$service_download = new base_service_company_resume_download();
					$service_person_resume_resume = new base_service_person_resume_resume();
					//服务套餐扣除记录
					$service_consolelog = new base_service_company_service_serviceConsumeLog();

					$is_apply = $service_apply->isApply($company_resources->all_accounts, $resume_id);
					$download_info = $service_download->getResumeDownload($company_resources->main_company_id, $resume_id, 'down_time');

					//获取简历
					$resume_info = $service_person_resume_resume->getResume($resume_id, "person_id,resume_id");

					//简历3个月内下载过或者投递过,不用扣除聊一聊点数
					//该企业跟该求职者3个月内聊过天,不扣除点数
					$is_chat = $service_consolelog->checkConsumeChatByCompanyIdAndPersonId($this->_userid, $resume_info['person_id']);
					if (!$is_apply && !$download_info && !$is_chat) {
						if ($companyresources['cq_release_point_chat'] < 1 && $total < $consume_spread_num) {
							if ($companyresources['isCqNewService']) {
								echo json_encode(array ('code' => 702, "status" => false, "msg" => "您的聊一聊条数/推广金不足，请联系主账号为您分配更多资源"));
								exit;
							}
							else {
								echo json_encode(array ('code' => 702, "status" => false, "msg" => "您的推广金不足，请联系主账号为您分配更多资源"));
								exit;
							}
						}
					}
					break;
				case "linkway":
					//返回余额不足的弹窗信息
					list($status, $code, $params) = $company_resources->check($func = "download", $params = ['resume_id' => $resume_id]);

					$servicePricing = new base_service_company_service_servicePricing();
					$account_overage_service_price = $servicePricing->GetFunParallelismSelling('point_dow_resume');
					if (!empty($params['isCqNewService']) && ($params['spread_overage'] <= 0 && $account_overage < $account_overage_service_price)) {
						$status = false;
					}

					if (!$status && $code != 2 && $code != base_service_company_resources_code::RESUME_EXP_SALARY) {
						if ($companyresources['isCqNewService']) {
							$msg = "您的简历点不足，请联系主账号为您分配更多资源。";
						}
						elseif ($companyresources['isNewService']) {
							$msg = "您的简历点/推广金不足，请联系主账号为您分配更多资源。";
						}
						else {
							$msg = "您的简历点不足，请联系主账号为您分配更多资源。";
						}

						echo json_encode(array ('code' => 502, "status" => false, "msg" => $msg));
						exit;
					}

					break;
			}

		}

		echo json_encode(array ("status" => true, "msg" => "成功"));
		exit;
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

	public function pageBalanceNotEnough($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$this->_aParams['account'] = base_lib_BaseUtils::getStr($pathdata['account'], 'string', '');
		$this->_aParams['consume'] = base_lib_BaseUtils::getStr($pathdata['consume'], 'string', '');

		return $this->render("./invite/balancenotenough.html", $this->_aParams);
	}

	// 将数组中的字段连接起来
	private function _arrFieldJoin($arr, $field, $separator = ',') {
		$new_arr = array ();
		foreach ($arr as $item) {
			array_push($new_arr, $item[ $field ]);
		}

		return implode($separator, $new_arr);
	}

	private function _writeResumeWord($resume_id, $chkIsHideSalary) {
		$is_show_name = false;
		$is_show_linkway = false;
		$not_member = false;
		$member_expires = false;
		$is_show_resumeinfo = false;
		$result = $this->checkResume($resume_id, $is_show_name, $is_show_linkway, $not_member, $member_expires, $is_show_resumeinfo);
		if (!$result) {
			return;
		}

		ob_clean();
		$this->_getResumeHistoryRecord($this->_userid, $resume_id);
		$html = $this->_resumeword($resume_id, $is_show_name, $is_show_linkway, $not_member, $member_expires, $chkIsHideSalary, $is_show_resumeinfo);

		return $html;
	}

	private function _writeResumeHtml($resume_id) {
		$is_show_name = false;
		$is_show_linkway = false;
		$not_member = false;
		$member_expires = false;
		$is_show_resumeinfo = false;
		$result = $this->checkResume($resume_id, $is_show_name, $is_show_linkway, $not_member, $member_expires, $is_show_resumeinfo);
		if (!$result) {
			return;
		}
		ob_clean();
		$this->_getResumeHistoryRecord($this->_userid, $resume_id);
		$html = $this->_resumeHtml($resume_id, $is_show_name, $is_show_linkway, $not_member, $member_expires, $is_show_resumeinfo);

		return $html;
	}

	private function _writeResumePrint($resume_id) {
		$is_show_name = false;
		$is_show_linkway = false;
		$not_member = false;
		$member_expires = false;
		$is_show_resumeinfo = false;

		$result = $this->checkResume($resume_id, $is_show_name, $is_show_linkway, $not_member, $member_expires, $is_show_resumeinfo);
		if (!$result) {
			return;
		}

		ob_clean();
		$html = $this->_printHtml($resume_id, $is_show_name, $is_show_linkway, $not_member, $member_expires, $is_show_resumeinfo);

		return $html;
	}

	/**
	 * 简历word下载
	 */
	public function pageWordDown($inPath) {
		if (!$this->canDo("see_resume_info")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";

			return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
		}

		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_ids = base_lib_BaseUtils::getStr($pathdata['resumeid'], 'array', null);
		$resume_ids = base_lib_BaseUtils::getIntArrayOrString($resume_ids);
		$apply_ids = base_lib_BaseUtils::getStr($pathdata['applyid'], 'array', null);
		$apply_ids = base_lib_BaseUtils::getIntArrayOrString($apply_ids);
		$resumesrc = base_lib_BaseUtils::getStr($pathdata['src'], 'string', "");

		if (!empty($resume_ids)) {
			$ids = $resume_ids;
			$dirPath = base_lib_Constant::SERVER_TEMP_FULL_FOLDER;
			$fileDir = "{$dirPath}resumedown_company/{$this->_userid}/";

			array_map('unlink', glob("{$fileDir}*"));

			$service_resume = new base_service_person_resume_resume();
			$service_person = new base_service_person_person();
			if (!empty($apply_ids) &&  in_array($resumesrc,['apply','invite']) ) {
				$service_apply = new base_service_company_resume_apply();
				$applyids = $apply_ids;
				$apply_list = $service_apply->getApplys($applyids, "resume_id,station,create_time");
				$applylist = $this->_buildArray($apply_list->items, "resume_id");
				//更改简历状态为已读
				$this->changeApplyReadStatus($applyids);
			}

			if ($resumesrc == "fav" || $resumesrc == "down") {
				$service_tag = new base_service_company_resume_resumecompanytag();
				$tag_data = $service_tag->getTagbyIDs($this->_userid, $resume_ids, 'resume_id,tag_name');
				$tag_list = $this->_buildArray($tag_data->items, 'resume_id');
			}
			$privilege = $service_person->checkMobilesPrivilege($ids, $this->_userid);
			foreach ($ids as $resume_id) {
				$resume = $service_resume->getResume($resume_id, 'person_id');
				$person = $service_person->getPerson($resume['person_id'], 'user_name,name_open,sex');
				$is_show_name = $privilege[ $resume_id ];

				if ($person['name_open'] == 1 && $is_show_name) {
					$person_name = $person['user_name'];
				}
				else {
					$sex_name = $person['sex'] == 1 ? '先生' : '女士';
					$person_name = mb_substr($person['user_name'], 0, 1, 'utf-8') . $sex_name;
				}
				if (!empty($applylist) && in_array($resumesrc,['apply','invite'])) {
					$applystation = str_replace("/", "_", $applylist["$resume_id"]["station"]);
					$fname = "huibo_(应聘：{$applystation})_{$person_name}_{$resume_id}.doc";
					$filename = $fileDir . $fname;
				}
				else if ($resumesrc == "fav" || $resumesrc == "down") {
					$taginfo = str_replace("/", "_", $tag_list["$resume_id"]);
					if (empty($taginfo)) {
						$fname = "huibo_{$person_name}_{$resume_id}.doc";
					}
					else {
						$fname = "huibo_(适合：{$taginfo["tag_name"]})_{$person_name}_{$resume_id}.doc";
					}
					$filename = $fileDir . $fname;
				}
				else {
					$fname = "huibo_{$person_name}_{$resume_id}.doc";
					$filename = $fileDir . $fname;
				}

				$html = $this->_writeResumeWord($resume_id, false);
				base_lib_BaseUtils::writeFile($filename, $html);
			}

			if (count($ids) == 1) {
				base_lib_BaseUtils::download($filename, $fname);
			}
			else if (count($ids) > 1) {
				$zip = new base_lib_PHPZip();
				$date_name = date("Y-m-d");
				$filename = "{$fileDir}简历{$date_name}.zip";
				$zip->ZipAndDownload($fileDir, "Huibo_简历_{$date_name}");
			}

			array_map('unlink', glob("{$fileDir}*"));
			rmdir($fileDir);
		}
	}

	/**
	 * 简历PDF下载
	 */
	public function pagePdfDown($inPath) {
		if (!$this->canDo("see_resume_info")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";

			return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
		}
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_ids = base_lib_BaseUtils::getStr($pathdata['resumeid'], 'array', null);
		$resume_ids = base_lib_BaseUtils::getIntArrayOrString($resume_ids);
		$apply_ids = base_lib_BaseUtils::getStr($pathdata['applyid'], 'array', null);
		$apply_ids = base_lib_BaseUtils::getIntArrayOrString($apply_ids);
		$resumesrc = base_lib_BaseUtils::getStr($pathdata['src'], 'string', "");

		if (!empty($resume_ids)) {
			$ids = $resume_ids;
			//$dirPath = $_SERVER['DOCUMENT_ROOT'];
			$dirPath = base_lib_Constant::SERVER_TEMP_FULL_FOLDER;
			$fileDir = "{$dirPath}resumedown_company/{$this->_userid}/";

			array_map('unlink', glob("{$fileDir}*"));
			$service_resume = new base_service_person_resume_resume();
			$service_person = new base_service_person_person();
			if (!empty($apply_ids)) {
				$service_apply = new base_service_company_resume_apply();
				//$applyids = explode(',', $apply_ids);
				$applyids = $apply_ids;
				$apply_list = $service_apply->getApplys($applyids, "resume_id,station,create_time");
				$applylist = $this->_buildArray($apply_list->items, "resume_id");
				$this->changeApplyReadStatus($applyids);
			}

			if ($resumesrc == "fav" || $resumesrc == "down") {
				$service_tag = new base_service_company_resume_resumecompanytag();
				$tag_data = $service_tag->getTagbyIDs($this->_userid, $resume_ids, 'resume_id,tag_name');
				$tag_list = $this->_buildArray($tag_data->items, 'resume_id');
			}

			//简历是否下载  或者投递
			$privilege = $service_person->checkMobilesPrivilege($ids, $this->_userid);

			foreach ($ids as $resume_id) {
				$resume = $service_resume->getResume($resume_id, 'person_id');
				$person = $service_person->getPerson($resume['person_id'], 'user_name,name_open,sex');
				$is_show_name = $privilege[ $resume_id ];

				if ($person['name_open'] == 1 && $is_show_name) {
					$person_name = $person['user_name'];
				}
				else {
					$sex_name = $person['sex'] == 1 ? '先生' : '女士';
					$person_name = mb_substr($person['user_name'], 0, 1, 'utf-8') . $sex_name;
				}
				//$html = $this->_writeResumeWord($resume_id,false);
				if (!empty($applylist) && in_array($resumesrc,['apply','invite'])) {
					$applystation = str_replace("/", "_", $applylist["$resume_id"]["station"]);
					$fname = "huibo_(应聘：{$applystation})_{$person_name}_{$resume_id}.pdf";
					$filename1 = $fileDir . $fname;
					$applytime = date('Y-m-d', strtotime($applylist["$resume_id"]["create_time"]));
					$this->_aParams['resume_src'] = "应聘职位：{$applystation}($applytime)";
				}
				elseif ($resumesrc == "fav" || $resumesrc == "down") {
					$taginfo = str_replace("/", "_", $tag_list["$resume_id"]);
					if (empty($taginfo)) {
						$fname = "huibo_{$person_name}_{$resume_id}.pdf";
					}
					else {
						$fname = "huibo_(适合：{$taginfo["tag_name"]})_{$person_name}_{$resume_id}.pdf";
					}
					$filename1 = $fileDir . $fname;
				}
				else {
					$fname = "huibo_{$person_name}_{$resume_id}.pdf";
					$filename1 = $fileDir . $fname;
				}

				$html = $this->_writeResumeHtml($resume_id);
				$filename = "{$fileDir}/huibo_{$resume_id}.html";
				$outfilename = "{$fileDir}/huibo_{$resume_id}.pdf";

				base_lib_BaseUtils::writePDF($filename, $outfilename, $html, "huibo_{$person_name}_{$resume_id}.pdf");
				//array_map('unlink', glob("{$fileDir}*"));
			}

			if (count($ids) == 1) {
				base_lib_BaseUtils::download($outfilename, $fname);
			}
			else if (count($ids) > 1) {
				$zip = new base_lib_PHPZip();
				$date_name = date("Y-m-d");
				$filename = "{$fileDir}简历{$date_name}.zip";
				//$zip->Zip($fileDir,$filename);
				$zip->ZipAndDownload($fileDir, "Huibo_简历_{$date_name}");
				//base_lib_BaseUtils::download($filename,"Huibo_简历_{$date_name}.zip");
			}
			array_map('unlink', glob("{$fileDir}*"));
			rmdir($fileDir);
		}
	}

	/**
	 *
	 * html下载
	 * @param  $inPath
	 */
	public function pageHtmlDown($inPath) {
		if (!$this->canDo("see_resume_info")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";

			return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
		}
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_ids = base_lib_BaseUtils::getStr($pathdata['resumeid'], 'array', null);
		$resume_ids = base_lib_BaseUtils::getIntArrayOrString($resume_ids);
		$apply_ids = base_lib_BaseUtils::getStr($pathdata['applyid'], 'array', null);
		$apply_ids = base_lib_BaseUtils::getIntArrayOrString($apply_ids);
		$resumesrc = base_lib_BaseUtils::getStr($pathdata['src'], 'string', "");
		$this->_aParams = array ();
		if (!empty($resume_ids)) {
			$ids = $resume_ids;

			$dirPath = base_lib_Constant::SERVER_TEMP_FULL_FOLDER;
			$fileDir = "{$dirPath}resumedown_company/{$this->_userid}/";

			array_map('unlink', glob("{$fileDir}*"));
			$service_resume = new base_service_person_resume_resume();
			$service_person = new base_service_person_person();

			if (!empty($apply_ids) && in_array($resumesrc,['apply','invite'])) {
				$service_apply = new base_service_company_resume_apply();
				//$applyids = explode(',', $apply_ids);
				$applyids = $apply_ids;
				$apply_list = $service_apply->getApplys($applyids, "resume_id,station,create_time");
				$applylist = $this->_buildArray($apply_list->items, "resume_id");
				$this->changeApplyReadStatus($applyids);
			}

			if ($resumesrc == "fav" || $resumesrc == "down") {
				$service_tag = new base_service_company_resume_resumecompanytag();
				$tag_data = $service_tag->getTagbyIDs($this->_userid, $resume_ids, 'resume_id,tag_name');
				$tag_list = $this->_buildArray($tag_data->items, 'resume_id');

			}
			//简历是否下载  或者投递
			$privilege = $service_person->checkMobilesPrivilege($ids, $this->_userid);
			foreach ($ids as $resume_id) {
				$resume = $service_resume->getResume($resume_id, 'person_id');
				$person = $service_person->getPerson($resume['person_id'], 'user_name,name_open,sex');
				$is_show_name = $privilege[ $resume_id ];

				if ($person['name_open'] == 1 && $is_show_name) {
					$person_name = $person['user_name'];
				}
				else {
					$sex_name = $person['sex'] == 1 ? '先生' : '女士';
					$person_name = mb_substr($person['user_name'], 0, 1, 'utf-8') . $sex_name;
				}
				if (!empty($applylist) && in_array($resumesrc,['apply','invite'])) {

					$applystation = str_replace("/", "_", $applylist["$resume_id"]["station"]);
					$fname = "huibo_(应聘：{$applystation})_{$person_name}_{$resume_id}.html";
					$filename = $fileDir . $fname;
					$applytime = date('Y-m-d', strtotime($applylist["$resume_id"]["create_time"]));
					$this->_aParams['resume_src'] = "应聘职位：{$applystation}($applytime)";

				}
				elseif ($resumesrc == "fav" || $resumesrc == "down") {
					$taginfo = str_replace("/", "_", $tag_list["$resume_id"]);
					if (empty($taginfo)) {
						$fname = "huibo_{$person['user_name']}_{$resume_id}.html";
					}
					else {
						$fname = "huibo_(适合：{$taginfo["tag_name"]})_{$person_name}_{$resume_id}.html";
					}
					$filename = $fileDir . $fname;
				}
				else {
					$fname = "huibo_{$person_name}_{$resume_id}.html";
					$filename = $fileDir . $fname;
				}
				$html = $this->_writeResumeHtml($resume_id);
				base_lib_BaseUtils::writeFile($filename, $html);
			}


			if (count($ids) == 1) {
				base_lib_BaseUtils::download($filename, $fname);
			}
			else if (count($ids) > 1) {
				$zip = new  base_lib_PHPZip();
				$date_name = date("Y-m-d");
				$filename = "$fileDir简历{$date_name}";
				//$zip->Zip("$fileDir","$filename");
				//base_lib_BaseUtils::download($filename,"Huibo_简历_{$date_name}.zip");
				$zip->ZipAndDownload($fileDir, "Huibo_简历_{$date_name}");
			}
			array_map('unlink', glob("{$fileDir}*"));
			//rmdir($fileDir);
		}
	}

	/**
	 *
	 * 打印
	 * @param  $inPath
	 */
	public function pageHtmlPrint($inPath) {
		if (!$this->canDo("see_resume_info")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";

			return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
		}
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_id = base_lib_BaseUtils::getStr($pathdata['resumeid'], 'int', null);
		$apply_ids = base_lib_BaseUtils::getStr($pathdata['applyid'], 'int', null);
		$resumesrc = base_lib_BaseUtils::getStr($pathdata['src'], 'string', "");
		$this->_aParams = array ();
        
        $service_download = new base_service_company_resume_download();
		if (!empty($resume_id)) {
			if (!empty($apply_ids) && $resumesrc == "apply") {
				$service_apply = new base_service_company_resume_apply();
				$applyinfo = $service_apply->getApply($apply_ids, "resume_id,station,create_time");
				$applystation = str_replace("/", "_", $applyinfo["station"]);
				$applytime = date('Y-m-d', strtotime($applyinfo["create_time"]));

				$this->_aParams['resume_src'] = "应聘职位：{$applystation}($applytime)";
				$this->_aParams['show_station_name'] = "应聘职位：{$applystation}";
				$this->_aParams['show_station_time'] = $applytime;
            }else if($service_download->isResumeDownloaded($this->_userid, $resume_id)){
                $service_company_resume_resumecompanytag = new base_service_company_resume_resumecompanytag();
                $tag = $service_company_resume_resumecompanytag->getTag($this->_userid, $resume_id, 'tag_name,update_time,create_time');
                
                if(!empty($tag) && !empty($tag['tag_name'])){
                    $this->_aParams['show_station_name'] = "合适职位：{$tag['tag_name']}";
                    $this->_aParams['show_station_time'] = $tag['update_time'] ? date('Y-m-d', strtotime($tag["update_time"])) : date('Y-m-d', strtotime($tag["create_time"]));
                }
            }

			$html = $this->_writeResumePrint($resume_id);

			return $html;
		}
	}

	public function pageWordSend($inPath) {
		if (!$this->canDo("see_resume_info")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";

			return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
		}
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

		$resume_ids = base_lib_BaseUtils::getStr($pathdata['resumeid'], 'array', null);
		$resume_ids = base_lib_BaseUtils::getIntArrayOrString($resume_ids);
		$apply_ids = base_lib_BaseUtils::getStr($pathdata['applyid'], 'array', null);
		$apply_ids = base_lib_BaseUtils::getIntArrayOrString($apply_ids);
		$resumesrc = base_lib_BaseUtils::getStr($pathdata['src'], 'string', "");

		$service_resume = new base_service_person_resume_resume();
		$service_person = new base_service_person_person();

		$resumes = $service_resume->getResumeListByIDs(implode(",", $resume_ids), "person_id,resume_id");
		$resume_list = $this->_buildArray($resumes->items, 'resume_id');

		$person_ids = $this->_buildIDs($resumes->items, 'person_id');
		$persons = $service_person->GetPersonListByIDs($person_ids, "person_id,user_name,name_open,sex");

		$person_list = $this->_buildArray($persons->items, 'person_id');
		$postvar = array ();

		$this->_aParams = array ();
		//是否改变简历状态
		$ischangestatus = base_lib_BaseUtils::getStr($pathdata['ischangestatus'], 'int', 0);
		$this->_aParams['ischangestatus'] = $ischangestatus;


		//简历是否下载  或者投递
		$privilege = $service_person->checkMobilesPrivilege($resume_ids, $this->_userid);
		foreach ($resume_list as $key => $value) {
			$is_show_name = $privilege[ $value['resume_id'] ];
			$person_info = $person_list[ $resume_list[ $key ]['person_id'] ];
			$info['resume_id'] = $key;
			if ($person_info['name_open'] == 1 && $is_show_name) {
				$info['user_name'] = $person_info['user_name'];
			}
			else {
				$sex_name = $person_info['sex'] == 1 ? '先生' : '女士';
				$info['user_name'] = mb_substr($person_info['user_name'], 0, 1, 'utf-8') . $sex_name;
			}

			array_push($postvar, $info);
		}

		$contacts_service = new base_service_company_companyusedcontacts();
		$contacts = $contacts_service->findContacts($this->_userid, 'id,email',5);
        $mails='';
		if(!empty($contacts))
		{
			$mails_arr = base_lib_BaseUtils::getPropertys($contacts,'email');
			$mails = implode(';',$mails_arr);//多个逗号隔开
        }

		$this->_aParams['mail'] = $mails_arr[0];//最新的一个邮箱
		$this->_aParams['mail_contacts'] = $contacts;//最近5个邮箱
		$this->_aParams['applyids'] = implode(",", $apply_ids);
		$this->_aParams['resumesrc'] = $resumesrc;
		$this->_aParams['postvar'] = $postvar;

		return $this->render('./resume/sendmail.html', $this->_aParams);
	}

	private function _buildArray($arr, $filer = "resume_id") {
		if (!is_array($arr) || count($arr) <= 0) {
			return $arr;
		}
		foreach ($arr as $key => $value) {
			$new_arr[ $value["$filer"] ] = $value;
		}

		return $new_arr;
	}

	private function _buildIDs($arr, $filer = "resume_id") {
		if (!is_array($arr) || count($arr) <= 0) {
			return $arr;
		}
		foreach ($arr as $key => $value) {
			$newArr[] = $value["$filer"];
		}

		return implode(',', $newArr);
	}

	/**
	 * 发送到邮件
	 */
	public function pageWordSendDo($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_ids = base_lib_BaseUtils::getStr($pathdata['resumeid'], 'array', null);
		$resume_ids = base_lib_BaseUtils::getIntArrayOrString($resume_ids);
		$apply_ids = base_lib_BaseUtils::getStr($pathdata['applyid'], 'array', null);
		$apply_ids = base_lib_BaseUtils::getIntArrayOrString($apply_ids);
		$resumesrc = base_lib_BaseUtils::getStr($pathdata['src'], 'string', "");

		if (empty($resume_ids)) {
			$json_arr['error'] = "参数传递出错，请重试！";
			echo json_encode($json_arr);

			return;
		}

		$content = base_lib_BaseUtils::getStr($pathdata['txtContent'], 'string', "");

		$validator = new base_lib_Validator();
		$email = $validator->getNotNull($pathdata['txtEmail'], '请输入收件人');
		$subject = $validator->getNotNull($pathdata['txtSubject'], '请输入邮件主题');

		$chkIsHideSalary = base_lib_BaseUtils::getStr($pathdata['chkIsHideSalary'], 'int', 0);
		if ($validator->has_err) {
			$arr_json['error'] = $validator->err[0];
			echo json_encode($arr_json);

			return;
		}

		$email = explode(';', $email);

		$dirPath = base_lib_Constant::SERVER_TEMP_FULL_FOLDER . 'mail/';
		$timenow = time();
		$fileDir = "{$dirPath}/resumedown_company/{$this->_userid}{$timenow}/";

		//array_map('unlink',glob("{$fileDir}*"));
		if (!empty($apply_ids) && $resumesrc == "apply") {
			$service_apply = new base_service_company_resume_apply();
			$applyids = $apply_ids;
			$apply_list = $service_apply->getApplys($applyids, "resume_id,station");
			$applylist = $this->_buildArray($apply_list->items, "resume_id");
		}

		if ($resumesrc == "fav" || $resumesrc == "down") {
			$service_tag = new base_service_company_resume_resumecompanytag();
			$tag_data = $service_tag->getTagbyIDs($this->_userid, $resume_ids, 'resume_id,tag_name');
			$tag_list = $this->_buildArray($tag_data->items, 'resume_id');
		}

		foreach ($resume_ids as $resume_id) {
			$service_resume = new base_service_person_resume_resume();
			$service_person = new base_service_person_person();
			$resume = $service_resume->getResume($resume_id, 'person_id');
			$person = $service_person->getPerson($resume['person_id'], 'user_name');

			$html = $this->_writeResumeWord($resume_id, $chkIsHideSalary);
			$fname = "huibo_{$resume_id}.doc";
			$filename = $fileDir . $fname;
			unlink($filename);
			base_lib_BaseUtils::writeFile($filename, $html);
		}

		if (count($resume_ids) == 1) {
			$success = base_lib_Mail::send($email, $subject, $content, array ($filename));
			if (!$success) {
				$arr_json['error'] = "邮件发送失败！";
				echo json_encode($arr_json);

				return;
			}
		}
		else if (count($resume_ids) > 1) {
			$date_name = date("Y-m-d");
			$filename = "{$fileDir}huibo_{$date_name}.zip";

			$zip = new base_lib_PHPZip();
			$zip->Zip("$fileDir", "$filename");
			$success = base_lib_Mail::send($email, $subject, $content, array ($filename));

			if (!$success) {
				$arr_json['error'] = "邮件发送失败！";
				echo json_encode($arr_json);

				return;
			}
		}

		$contacts = array ();
		if (is_array($email)) {
			foreach ($email as $value) {
				array_push($contacts, array ("company_id" => $this->_userid, "email" => $value, "create_time" => date('Y-m-d H:i:s')));
			}
		}
		else {
			array_push($contacts, array ("company_id" => $this->_userid, "email" => $value, "create_time" => date('Y-m-d H:i:s')));
		}

		$contacts_service = new base_service_company_companyusedcontacts();
		$companyusedContacts = $contacts_service->queryContacts($this->_userid, 'id,email')->items;
		foreach ($contacts as $c) {
			$obj = $this->arrayFind($companyusedContacts, 'email', $c['email']);
			if (is_null($obj)) {
				$contacts_service->addContacts($c);
			}
			else {
				$c['id'] = $obj['id'];
				$contacts_service->updateContacts($c);
			}
		}

		//发送成功后 如果是投递的简历 修改状态
		if (!empty($apply_ids) && $resumesrc == "apply") {
			$this->changeApplyReadStatus($apply_ids);
		}

		$arr_json['success'] = "邮件发送成功！";
		echo json_encode($arr_json);

		return;
	}

	/**
	 * 新增访问记录
	 * @param  $inPath
	 */
	private function _addVisit($resume_id, $person_id, $company_id) {
		$visit['resume_id'] = $resume_id;
		$visit['person_id'] = $person_id;
		$visit['company_id'] = $company_id;
		$visit['hit_time'] = date('Y-m-d H:i:s');

		$service_visit = new base_service_person_visit();
		$service_visit->addVisit($visit);
		// 更新简历冗余记录
		$service_resume = new base_service_person_resume_resume();
		$service_resume->increaseVisitNum($resume_id);

	}

	/**
	 * 新增访问记录
	 * @param  $inPath
	 */
	private function _addVisitBlue($resume_id, $person_id, $company_id) {
		$visit['resume_id'] = $resume_id;
		$visit['person_id'] = $person_id;
		$visit['company_id'] = $company_id;
		$visit['hit_time'] = date('Y-m-d H:i:s');

		$service_visit = new base_service_person_visit();
		$service_visit->addVisitBlue($visit);
		// 更新简历冗余记录

		$service_resume = new base_service_person_resume_resume();
		$service_resume->increaseVisitNum($resume_id);

	}

	/**
	 *
	 *  简历html
	 * @param  $resume_id
	 * @param  $show_name
	 * @param  $show_linkway
	 * @param  $not_member
	 * @param  $member_expires
	 */
	private function _resumeHtml($resume_id, $show_name, $show_linkway, $not_member, $member_expires, $is_show_resumeinfo) {
		$this->_fill($resume_id, $show_name, $show_linkway, $not_member, $member_expires, false, $is_show_resumeinfo);

		return $this->render('./resume/html_template_new.html', $this->_aParams);
	}

	/**
	 *
	 * 简历打印
	 * @param  $resume_id
	 * @param  $show_name
	 * @param  $show_linkway
	 * @param  $not_member
	 * @param  $member_expires
	 */
	private function _printHtml($resume_id, $show_name, $show_linkway, $not_member, $member_expires, $is_show_resumeinfo) {
		$this->_fill($resume_id, $show_name, $show_linkway, $not_member, $member_expires, false, $is_show_resumeinfo);

		return $this->render('./resume/print_template_new.html', $this->_aParams);
	}

	/**
	 *
	 * 简历word
	 * @param  $resume_id
	 * @param  $show_name
	 * @param  $show_linkway
	 * @param  $not_member
	 * @param  $member_expires
	 */
	private function _resumeword($resume_id, $show_name, $show_linkway, $not_member, $member_expires, $chkIsHideSalary = false, $is_show_resumeinfo = false) {
		$this->_fill($resume_id, $show_name, $show_linkway, $not_member, $member_expires, $chkIsHideSalary, $is_show_resumeinfo);

		return $this->render('./resume/word_template.xml', $this->_aParams);
	}

	/**
	 * @param  $company_id
	 * @param  $resume_id
	 */
	private function getLastApplyInfo($company_id, $resume_id) {
		$service_apply = new base_service_company_resume_apply();
		$apply = $service_apply->getLastApply($company_id, $resume_id, 'station,re_status,re_time,create_time');
		if (empty($apply)) {
			return;
		}
		$applyinfo = '';
		$enum_restatus = new base_service_person_jobapplyrestatus();
		if ($apply['re_status'] == $enum_restatus->intervie) {
			$time = date('Y-m-d H:i', strtotime($apply['re_time']));
			$applyinfo = "已邀请TA面试：{$apply['station']}({$time})";
		}
		elseif ($apply['re_status'] == $enum_restatus->refused) {
			$time = date('Y-m-d H:i', strtotime($apply['re_time']));
			$applyinfo = "已婉拒TA的面试申请：{$apply['station']}({$time})";
		}
		else {
			$time = date('Y-m-d H:i', strtotime($apply['create_time']));
			$applyinfo = "TA已投递过职位：{$apply['station']}({$time})";
		}
		$this->_aParams['restatus'] = $apply['re_status'];
		$this->_aParams['applyinfo'] = $applyinfo;
	}

	/**
	 * 单位是否在求职者屏蔽的关键字当中
	 */
	public function _getFilter($person_id) {
		$service_blench = new base_service_person_blench();
		$is_filter = false;
		$blenchs = $service_blench->getAllBlenchKeyList($person_id, 'person_id,type,com_keyword,company_id');
		$blenchs_items = $blenchs->items;

		if (!base_lib_BaseUtils::nullOrEmpty($blenchs_items)) {
			for ($i = 0; $i < count($blenchs_items); $i++) {
				if ($blenchs_items[ $i ]['type'] == 0) {
					if ($this->_getKeySetResult($blenchs_items[ $i ]['com_keyword'])) {
						$is_filter = true;
						break;
					}
				}
				else {
					if ($blenchs_items[ $i ]['company_id'] == $this->_userid) {
						$is_filter = true;
						break;
					}
				}
			}
		}

		return $is_filter;
	}

	//  获取关键字结果
	public function _getKeySetResult($com_keyword) {
		//$servcie_sphinx = new base_service_sphinx_sphinx();
		$service_solr = new base_service_solr_solr();
		$postvar['keyword'] = $com_keyword;
		$result = $service_solr->companySearch($postvar);

		if (isset($result['companys']) && count($result['companys'])) {
			if ($this->arrayFind($result['companys'], 'company_id', $this->_userid) != null) {
				return true;
			}
			else {
				return false;
			}
			//return in_array($this->_userid,$result['companys'])==true;
		}
		else {
			return false;
		}
	}

	/**
	 *
	 * 验证简历
	 * @param  $resume_id
	 * @param  $is_show_name
	 * @param  $is_show_linkway
	 * @param  $not_member
	 * @param  $member_expires
	 */
	private function checkResume($resume_id, &$is_show_name, &$is_show_linkway, &$member_info, &$member_expires, &$is_show_resumeinfo) {
		$service_company = new base_service_company_company();
		$service_person = new base_service_person_person();
		$service_resume = new base_service_person_resume_resume();
		$result = true;
		$resume = $service_resume->getResume($resume_id, 'resume_id,person_id');
		if (empty($resume)) {
			return false;
		}

		$person = $service_person->getPerson($resume['person_id'], 'name_open,user_name,user_name_en,open_mode');
		if (empty($person)) {
			return false;
		}

		if ($this->_getFilter($resume['person_id'])) {
			return false;
		}

//		if ($person['name_open'] == 1) {
//			$is_show_name = true;
//		}

		$service_apply = new base_service_company_resume_apply();
		$service_download = new base_service_company_resume_download();

		// 验证求职者是否投递过企业的职位
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		$relate_resume_id = empty($resume['relate_resume_id']) ? 0 : $resume['relate_resume_id'];
		if ($service_apply->isApply($company_resources->all_accounts, $resume['resume_id'])) {
			$is_show_linkway = true;
			$is_show_name = true;
			$is_show_resumeinfo = true;
		}
		else if ($service_apply->isApply($company_resources->all_accounts, $relate_resume_id)) {
			$is_show_linkway = true;
			$is_show_name = true;
			$is_show_resumeinfo = true;
		}
		else {
			$enum_openmode = new base_service_common_openmode();
			$downloads = $service_download->getResumeDownload($this->_userid, $resume['resume_id'], 'down_time');
			if (round((strtotime($this->now()) - strtotime($downloads['down_time'])) / 3600) > 24) { //下载时间在24小时内，企业任可以查看
				if ($person['open_mode'] == $enum_openmode->notopen) {
					return false;
				}
			}
		}

		$privilege = $service_person->checkMobilesPrivilege(array ($resume_id), $this->_userid);
		$is_show_linkway = $privilege[ $resume_id ];
		//如果是投递或者下载的简历 显示姓名
		$is_show_name = $privilege[ $resume_id ];

		return $result;
	}

	/**
	 *
	 * 填充数据
	 * @param  $resume_id
	 * @param  $show_name
	 * @param  $show_linkway
	 * @param  $not_member
	 * @param  $member_expires
	 */
	private function _fill($resume_id, $show_name, $show_linkway, $member_info, $chkIsHideSalary = false, $is_show_resumeinfo = false, $is_down_old = true, $is_down = true, $apply_id = 0) {
// 		未通过认证时，需要隐藏的简历信息		
// 		1.个人照片		
// 		2.工作经历中 公司名称、工作职责		
// 		3.项目经历		
// 		5.教育经历 -只显示最近一次教育经历，其他隐藏		
// 		6.联系方式		
// 		7.实践经验		
// 		8.个人头像

		if ($resume_id == 0) {
			return;
		}

		$service_resume = new base_service_person_resume_resume();
		$service_resume->dbquery = true; //load data from subordinate database
		//获取简历
		$resume = $service_resume->getResume($resume_id, 'resume_id,person_id,degree_id,station,'
			. 'is_salary_show,salary,appraise,expect_job_level,'
			. 'is_not_accept_lower_job_level,is_not_accept_lower_salary,'
			. 'job_type,is_accept_parttime,update_time,point');


		if (base_lib_BaseUtils::nullOrEmpty($resume)) {
			return;
		}

		$service_person = new base_service_person_person();
		$service_person->dbquery = true; //load data from subordinate database
		//获取用户
		$person = $service_person->getPerson($resume['person_id'], 'person_id,user_name,sex,email,cur_area_id,'
			. 'has_big_photo,big_photo,photo,photo_open,'
			. 'name_open,birthday2,mobile_phone,mobile_phone_is_validation,'
			. 'email_is_validation,telephone,qq,qq_open,tel_open,email_open,'
			. 'stature,marriage,avoirdupois,political_status,native_place_id,'
			. 'fertility_circumstance,start_work,login_time,job_state_id,'
			. 'accession_time,person_class,password');
		if (base_lib_BaseUtils::nullOrEmpty($person)) {
			return;
		}

		//判断是否二类简历或者一类简历但是未设密码
		$this->_aParams['show_person_class_tip'] = $person['person_class'] == '2' ? 1 : 0;

		// 使用的扣点方式（扣点 OR 推广金|余额）
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);

		list($status, $code, $msg) = $company_resources->check($func = "download", $params = ['resume_id' => $resume_id]);
		$point = empty($resume['point']) ? 1 : $resume['point'];
		$resumelevel_service = new base_service_common_resumelevel();
		$this->_aParams['resume_level'] = $resumelevel_service->getLevelByPoint($point);

		$this->_aParams['point'] = empty($resume['point']) ? 1 : $resume['point'];
		$this->_aParams['use_point'] = $code == base_service_company_resources_code::DOWNLOAD_USE_POINT ? true : false;
		$this->_aParams['consume'] = $code == base_service_company_resources_code::DOWNLOAD_USE_POINT ? $msg['point'] : $msg['price'];
		$this->_aParams['level_name'] = $msg['name'];

		// 获取简历等级
		$resumelevelservice = new base_service_common_resumelevel();
		$this->_aParams['resumelevelAll'] = $resumelevelservice->getLevel();

		$service_company = new base_service_company_company();
		$company_info = $service_company->getCompany($this->_userid, true, "is_audit,site_type");
		$this->_aParams['is_audit'] = $company_info['is_audit'];

		//省外企业不显示
		if ($company_info["site_type"] == 5) {
			$this->_aParams['show_person_class_tip'] = 0;
		}

		// 新增简历访问记录
		if ($apply_id > 0) {
			//判断是否快米投递简历同步而来
			$service_company_resume_applyresource = new base_service_company_resume_applyresource();
			$service_common_applyresource = new base_service_common_applyresource();

			$apply_resource_info = $service_company_resume_applyresource->getApplyListById($apply_id);
			if ($apply_resource_info && $apply_resource_info['resouce_type'] == $service_common_applyresource->kuaimi) {
				//快米查看简历，不发送简历查看模板消息
				$this->_addVisitBlue($resume_id, $person['person_id'], $this->_userid);
			}
			else {
				$this->_addVisit($resume_id, $person['person_id'], $this->_userid);
			}

		}
		else {
			$this->_addVisit($resume_id, $person['person_id'], $this->_userid);
		}


		$this->_aParams['resume_id'] = $resume_id;
		$this->_aParams['person_id'] = $person['person_id'];
		$this->_aParams['company_id'] = $this->_userid;
		$this->_aParams['last_login_time'] = date('Y-m-d H:i', strtotime($person['login_time']));


		//---------------- @update 新增贫困地区 2019-5-16-----------------
		$this->getPersonExt($person['person_id']);


		$company_resources_info = $company_resources->getCompanyAuditStatusV2();
		//是否显示简历详细信息
		//$isshowresumeinfo = $service_company->isShowResumeInfo($this->_userid);
		$isshowresumeinfo = true;
		$letter_info = $this->CheckCompanyLetter($this->_userid);
		$this->_aParams['letter_info'] = $letter_info;
		if ($company_resources_info['audit_type'] == 1) {
			//老规则
			//$isshowresumeinfo = $service_company->isShowResumeInfo($this->_userid);
			$isshowresumeinfo = true;
		}
		else {
			//新规则
			if ($letter_info['code'] != 200
				&& ($company_resources_info['is_audit'] != 1)) {
				$isshowresumeinfo = false;
			}
		}

		//姓名
		if ($show_name && $isshowresumeinfo) {
			$this->_aParams['user_name'] = $person['user_name'];
		}
		else {
			$sex_name = $person['sex'] == 1 ? '先生' : '女士';
			$this->_aParams['user_name'] = mb_substr($person['user_name'], 0, 1, 'utf-8') . $sex_name;
		}

		$this->_aParams['appraise'] = $resume['appraise'];
		//头像，性别。年龄，学历，工作经验，职位类别，接受兼职，低薪资，低岗位，自我评价
		$this->_aParams['avatar'] = ($is_down ? base_lib_Constant::YUN_ASSETS_URL : base_lib_Constant::YUN_ASSETS_URL_NO_HTTP) . '/img/common/user120_150.jpg';
		if ($person['photo_open'] != '0' && $isshowresumeinfo) {//如果 photo_open 为null也会进
			if (!base_lib_BaseUtils::nullOrEmpty($person['photo'])) {
				$avatar = ($is_down ? base_lib_Constant::YUN_ASSETS_URL : base_lib_Constant::YUN_ASSETS_URL_NO_HTTP) . "{$person['photo']}";
				if ($person['has_big_photo'] == '1') {
					$avatar_big = "{$person['big_photo']}";
					$this->_aParams['avatar_big'] = $avatar_big;
					$this->_aParams['avatar'] = $avatar;
				}
				else {
					$this->_aParams['avatar'] = $avatar;
				}
			}
		}

		$this->_aParams['sex'] = $person['sex'];
		$this->_aParams['age'] = base_lib_TimeUtil::ceil_diff_year($person['birthday2']) . '岁';
		$this->_aParams['degree'] = $resume['degree_id'];
		$this->_aParams['job_state_id'] = $person['job_state_id'];
		$this->_aParams['job_state_text'] = (new base_service_common_applystatus())->getName($person['job_state_id']);
		$this->_aParams['accession_time'] = $person['accession_time'];

		$basic_start_work_year = base_lib_TimeUtil::date_diff_month($person['start_work']);
		$workY = floor($basic_start_work_year / 12);
		$workM = intval($basic_start_work_year % 12);

		if ($workY <= 0 && $workM <= 6 && $workM >= -6) {
			$basic_start_work_year = '应届毕业生';
		}
		else if ($workY == 0 && $workM > 6) {
			$basic_start_work_year = $workM . '个月工作经验';
		}
		else if ($basic_start_work_year < -6) {
			$basic_start_work_year = '目前在读';
		}
		else {
			$basic_start_work_year = $workY . '年工作经验';
		}

		$this->_aParams['work_year'] = $basic_start_work_year;
		$this->_aParams['job_type'] = $resume['job_type'];
		$this->_aParams['is_accept_parttime'] = $resume['is_accept_parttime'];
		$this->_aParams['is_not_accept_lower_salary'] = $resume['is_not_accept_lower_salary'];
		$this->_aParams['is_not_accept_lower_job_level'] = $resume['is_not_accept_lower_job_level'];
		$this->_aParams['appraise'] = $resume['appraise'];

		//当前所在地
		$service_area = new base_service_common_area();
		$cur_area_name = '';
		if ($person['cur_area_id'] != '') {
			$cur_area_arr = $service_area->getTopAreaByAreaID($person['cur_area_id']);
			$cur_area_count = count($cur_area_arr);
			for ($j = $cur_area_count - 1; $j >= 0; $j--) {
				if ($j == 0) {
					$cur_area_name .= $cur_area_arr[ $j ]['area_name'];
				}
				else {
					$cur_area_name .= $cur_area_arr[ $j ]['area_name'] . '-';
				}
			}
		}

		$this->_aParams['cur_area_name'] = $cur_area_name;
		$this->_aParams['exp_job'] = $resume['station'];
		$this->_aParams['exp_job_level'] = $resume['expect_job_level'];

		//用于邮件简历
		if ($chkIsHideSalary == false) {
			if ($resume['is_salary_show'] == 0) {
				$this->_aParams['exp_salary'] = $resume['salary'];
			}
			else {
				$this->_aParams['exp_salary'] = 'negotiable';
			}
		}

		//获取意向行业类别
		$service_callingexp = new base_service_person_resume_callingexp();
		$service_calling = new base_service_common_calling();
		$callings = $service_callingexp->getResumeCallingexpList($resume['resume_id'], 'resume_id,calling_id');
		$calling_items = $callings->items;
		if (!base_lib_BaseUtils::nullOrEmpty($calling_items)) {
			$str_expect_callings = '';
			for ($g = 0; $g < sizeof($calling_items); $g++) {
				if (sizeof($calling_items) == 1 || $g == sizeof($calling_items) - 1) {
					$str_expect_callings .= $service_calling->getCallingName($calling_items[ $g ]['calling_id']);
				}
				else {
					$str_expect_callings .= $service_calling->getCallingName($calling_items[ $g ]['calling_id']) . ';';
				}
			}
			$this->_aParams['str_expect_callings'] = $str_expect_callings;
		}

		//获取意向地区
		$service_areaexp = new base_service_person_areaexp();
		$service_areaexp->dbquery = true; //load data from subordinate database
		$areas = $service_areaexp->getResumeAreaexpList($resume['resume_id'], 'person_id,resume_id,area_id');
		$area_items = $areas->items;
		if (!base_lib_BaseUtils::nullOrEmpty($area_items)) {
			$area_name = '';
			$area_ids = '';
			$area_ids_arr = array ();
			for ($i = 0; $i < sizeof($area_items); $i++) {
				$area = $service_area->getArea($area_items[ $i ]['area_id'], false);
				if ($i == sizeof($area_items) - 1) {
					$area_ids .= $area['area_id'];
				}
				else {
					$area_ids .= $area['area_id'] . ',';
				}
				array_push($area_ids_arr, $area['area_id']);
			}
			$area_name = $this->getSpecialAreaName($service_area, $area_ids_arr, $area_ids);
		}
		if (!empty($area_name)) {
			$area_name_array = explode(";", $area_name);
			$area_name_array = array_filter($area_name_array, function ($v) {
				if (empty($v)) {
					return false;
				}

				return true;
			});
			$area_name = !empty($area_name_array) ? implode(";", $area_name_array) : "不限";
		}
		$this->_aParams['exp_area_names'] = $area_name;

		//获取意向职位类别
		$service_jobsort = new base_service_common_jobsort();
		$service_jobsortexp = new base_service_person_resume_jobsortexp();
		$jobsorts = $service_jobsortexp->getResumeJobsortexpList($resume_id, 'resume_id,jobsort');
		$jobsort_items = $jobsorts->items;
		if (!base_lib_BaseUtils::nullOrEmpty($jobsort_items)) {
			$str_expect_jobsorts = '';
			$expect_jobsort_ids = '';
			for ($i = 0; $i < sizeof($jobsort_items); $i++) {
				if (sizeof($jobsort_items) == 1 || $i == sizeof($jobsort_items) - 1) {
					$str_expect_jobsorts .= $service_jobsort->getJobsortName($jobsort_items[ $i ]['jobsort']);
					$expect_jobsort_ids .= $jobsort_items[ $i ]['jobsort'];
				}
				else {
					$str_expect_jobsorts .= $service_jobsort->getJobsortName($jobsort_items[ $i ]['jobsort']) . ',';
					$expect_jobsort_ids .= $jobsort_items[ $i ]['jobsort'] . ',';
				}
			}
			$this->_aParams['expect_jobsort_ids'] = $expect_jobsort_ids;
			$this->_aParams['str_expect_jobsorts'] = $str_expect_jobsorts;
		}


		/********************联系方式****************************/
		//if ($isshowresumeinfo) {//非会员和未认证都显示不完整联系方式 sx 7.16 
		//手机号
		//面试评价 需要判断是否暂时联系方式
		$service_linkwayget = new base_service_company_appraise_linkwayget();
		$has_get_linkway = $service_linkwayget->checkIsGet($this->_userid, $person["person_id"]);

		$this->_aParams["has_get_linkway"] = $has_get_linkway;
		if ($show_linkway && $this->canDo("see_resume_mobile")) {
			$this->_aParams['mobile_phone'] = $person['mobile_phone'];
			$this->_aParams['qq'] = $person['qq'];
			$this->_aParams['email'] = $person['email'];
			if (!$has_get_linkway) {
				$this->_aParams['hid_mobile_phone'] = preg_replace('#(\d{3})[a-z0-9-]{2,10}(\d{3})#', '${1}****${2}', $person['mobile_phone']);
				$this->_aParams['hid_qq'] = preg_replace('#(\d{3})[a-z0-9-]{2,10}(\d{3})#', '${1}****${2}', $person['qq']);
				$this->_aParams['hid_email'] = preg_replace('#(\w{3})[a-z0-9-]+@#', '${1}****@', $person['email']);
			}
		}
		else {
			$this->_aParams['mobile_phone'] = preg_replace('#(\d{3})[a-z0-9-]{2,10}(\d{3})#', '${1}****${2}', $person['mobile_phone']);
			$this->_aParams['qq'] = preg_replace('#(\d{3})[a-z0-9-]{2,10}(\d{3})#', '${1}****${2}', $person['qq']);
			$this->_aParams['email'] = preg_replace('#(\w{3})[a-z0-9-]+@#', '${1}****@', $person['email']);
		}
		$this->_aParams['mobile_phone_valid'] = $person['mobile_phone_is_validation'];
		$this->_aParams['email_is_validation'] = $person['email_is_validation'];
		//固话
		$this->_aParams['telephone'] = $person['telephone'];
		//QQ
		$this->_aParams['show_linkway'] = $show_linkway;
		$this->_aParams['member_info'] = $member_info;
		//}
		/********************联系方式****************************/

		//户籍
		$native_place_name = '';
		if ($person['native_place_id'] != '') {
			$native_place = new base_service_common_area();
			$native_place_arr = $native_place->getTopAreaByAreaID($person['native_place_id']);
			$native_place_arr_count = count($native_place_arr);
			for ($j = $native_place_arr_count - 1; $j >= 0; $j--) {
				if ($j == 0) {
					$native_place_name .= $native_place_arr[ $j ]['area_name'];
				}
				else {
					$native_place_name .= $native_place_arr[ $j ]['area_name'] . '-';
				}
			}
		}

		$this->_aParams['native_place_name'] = $native_place_name;
		//政治面貌
		$this->_aParams['political_status'] = $person['political_status'];
		//身高
		$this->_aParams['stature'] = $person['stature'];
		//体重
		$this->_aParams['avoirdupois'] = $person['avoirdupois'];
		//婚否
		$this->_aParams['marriage'] = $person['marriage'];
		$this->_aParams['fertility'] = $person['fertility_circumstance'];

		//工作经验板块
		$service_work = new base_service_person_resume_work();
		$service_work->dbquery = true; //load data from subordinate database
		$works = $service_work->getResumeWorkList($resume['resume_id'], 'start_time,end_time,company_name,calling_id,com_property,com_size,station,is_salary_show,
		work_content,salary_month,job_type,job_level,department,subordinate,is_creator,leave_reason,manage_department,report_man,skill_tag_ids,ability_tag_ids,custom_tag_ids,tag_names');
		$works_items = $works->items;
		$this->_aParams['resume_works'] = array ();
		if (!base_lib_BaseUtils::nullOrEmpty($works_items)) {
			for ($j = 0; $j < count($works_items); $j++) {
				//每份工作经验时间
				$work_time_diff = (base_lib_TimeUtil::date_diff_year2($works_items[ $j ]['start_time'], $works_items[ $j ]['end_time']));
				$works_items[ $j ]['work_time'] = $work_time_diff;
				//开始结束时间
				$works_items[ $j ]['start_time'] = base_lib_BaseUtils::nullOrEmpty($works_items[ $j ]['start_time']) ? '开始时间未填写' : date('Y.m', strtotime($works_items[ $j ]['start_time']));
				$works_items[ $j ]['end_time'] = base_lib_BaseUtils::nullOrEmpty($works_items[ $j ]['end_time']) ? '至今' : date('Y.m', strtotime($works_items[ $j ]['end_time']));

				if (!$isshowresumeinfo) {
					$works_items[ $j ]['company_name'] = "*****公司";
					$works_items[ $j ]['work_content'] = "营业执照认证后可查看";
				}
				//技能标签
				if($works_items[$j]['tag_names']){
					$works_items[$j]['tag_names']=explode(',',$works_items[$j]['tag_names']);
				}
			}
			$this->_aParams['resume_works'] = $works_items;
		}

		//教育经历&培训经历
		$service_edu = new base_service_person_resume_edu();
		$service_edu->dbquery = true; //load data from subordinate database
		$edus = $service_edu->getResumeEduList($resume['resume_id'], 'start_time,end_time,school,major_desc,degree,edu_detail,duty,school_score');
		$edu_items = $edus->items;
		foreach ($edu_items as &$edu_temp) {
			if ($edu_temp['school_score']) {
				$school_score = explode(',', $edu_temp['school_score']);
				$edu_temp['school_score'] = "{$school_score[0]}/{$school_score[1]}";
			}
		}
		$this->_aParams['hasEdusOrTrains'] = false;
		$this->_aParams['resume_edus'] = $isshowresumeinfo ? $edu_items : array_slice($edu_items, 0, 1);//未通过认证时教育经历 -只显示最近一次教育经历，其他隐藏 sx 2015.7.16
		if ($isshowresumeinfo) {//未通过认证时教育经历 -只显示最近一次教育经历，其他隐藏 sx 2015.7.16
			$service_train = new base_service_person_resume_train();
			$service_train->dbquery = true; //load data from subordinate database
			$trains = $service_train->getResumeTrainList($resume['resume_id'], 'start_time,end_time,institution,course,certificate,train_detail');
			$train_items = $trains->items;
			$this->_aParams['resume_trains'] = $train_items;
			if (count($edu_items) > 0 || count($train_items) > 0) {
				$this->_aParams['hasEdusOrTrains'] = true;
			}
		}
		$this->_aParams['resume_projects'] = array ();

		//获奖经历
		$winningrecord_service = new base_service_person_resume_winningrecord();
		$resume_winningrecord = $winningrecord_service->getWinningsByResumeId($resume['resume_id']);
		if ($resume_winningrecord) {
			foreach ($resume_winningrecord as &$winnin) {
				$winnin['winning_time'] and $winnin['winning_time'] = date('Y.m', strtotime($winnin['winning_time']));
			}
		}
		$this->_aParams['resume_winningrecord'] = $resume_winningrecord;

		//项目经验
		if ($isshowresumeinfo) {//未通过认证时，需要隐藏的简历信息 sx 2015.7.16
			$service_project = new base_service_person_resume_project();
			$service_project->dbquery = true; //load data from subordinate database
			$projects = $service_project->getResumeProjectList($resume['resume_id'], 'start_time,end_time,project_name,project_detail,main_duty,duty');
			$project_items = $projects->items;
			if (!base_lib_BaseUtils::nullOrEmpty($project_items)) {
				for ($a = 0; $a < count($project_items); $a++) {
					//每份工作经验时间
					$project_time_diff = (base_lib_TimeUtil::date_diff_year2($project_items[ $a ]['start_time'], $project_items[ $a ]['end_time']));
					$project_items[ $a ]['project_time'] = $project_time_diff;
					//开始结束时间
					$project_items[ $a ]['start_time'] = base_lib_BaseUtils::nullOrEmpty($project_items[ $a ]['start_time']) ? '开始时间未填写' : date('Y.m', strtotime($project_items[ $a ]['start_time']));
					$project_items[ $a ]['end_time'] = base_lib_BaseUtils::nullOrEmpty($project_items[ $a ]['end_time']) ? '至今' : date('Y.m', strtotime($project_items[ $a ]['end_time']));
				}
				$this->_aParams['resume_projects'] = $project_items;
			}
		}

		//证书
		$service_certificate = new base_service_person_resume_certificate();
		$service_certificate->dbquery = true; //load data from subordinate database
		$certificates = $service_certificate->getResumeCertificateList($resume['resume_id'], 'certificate_name,certificate_no,gain_time,score');
		$certificates_items = $certificates->items;
		$this->_aParams['resume_certificates'] = array ();
		if (!base_lib_BaseUtils::nullOrEmpty($certificates_items)) {
			for ($k = 0; $k < count($certificates_items); $k++) {
				$grin_time = strtotime($certificates_items[ $k ]['gain_time']);
				$certificates_items[ $k ]['gain_time'] = date('Y年m月', $grin_time);
			}
			$this->_aParams['resume_certificates'] = $certificates_items;
		}

		//我的作品
		$service_achievement = new base_service_person_resume_achievement();
		$service_achievement->dbquery = true; //load data from subordinate database

		$service_attachment = new base_service_person_resume_achievementattachment();
		$service_attachment->dbquery = true; //load data from subordinate database
		$achievements = $service_achievement->getResumeAchievementList($resume['resume_id'], 'achievement_id,resume_id,achievement_name,start_time,end_time,achievement_description');
		$achievement_item = $achievements->items;

		$this->_aParams['resume_achievements'] = array ();
		if (!base_lib_BaseUtils::nullOrEmpty($achievement_item)) {
			// 读取配置xml文件
			$xml = SXML::load('../config/person/ResumeAttachment.xml');
			if (!is_null($xml)) {
				$attachment_virt_path = ($is_down ? base_lib_Constant::YUN_ASSETS_URL : base_lib_Constant::YUN_ASSETS_URL_NO_HTTP) . '/' . $xml->AchievementAttachmentVirtualName . '/';
				$this->_aParams['attachment_virt_path'] = $attachment_virt_path;
				$achievement_thumb_suffix = $xml->ThumbSuffix;
				$achievement_thumb_ext = $xml->AchievementImageExtensions;
			}
			for ($j2 = 0; $j2 < count($achievement_item); $j2++) {
				$attachments = $service_attachment->getResumeAttachmentList($achievement_item[ $j2 ]['achievement_id'], 'achievement_id,achievement_path,attachment_name,status,achievement_extension');
				$attachment_item = $attachments->items;
				if (!base_lib_BaseUtils::nullOrEmpty($attachment_item)) {
					$achievement_id = $achievement_item[ $j2 ]['achievement_id'];
					for ($k = 0; $k < count($attachment_item); $k++) {
						$fileParts = pathinfo($attachment_item[ $k ]['achievement_path']);
						if (count(explode($fileParts['extension'], $achievement_thumb_ext)) > 1) {
							//$attachment_item[$k]['achievement_thumb_path'] =$fileParts['dirname'].'/'.$fileParts['filename'].$achievement_thumb_suffix.'.'.$fileParts['extension'];
							$imgsrc = base_lib_BaseUtils::getThumbImg($xml->AchievementAttachmentVirtualName . '/' . $attachment_item[ $k ]['achievement_path'], 200, 160, $is_down);
							$attachment_item[ $k ]['achievement_thumb_path'] = $imgsrc;
						}
					}
					$attachment_temp_item = $this->array_sort($attachment_item, 'achievement_extension');
					$achievement_item[ $j2 ]['attachments'] = $attachment_temp_item;
					//$this->_aParams['achievement_attachments'.$achievement_id] = $attachment_temp_item;
				}
			}
			$this->_aParams['resume_achievements'] = $achievement_item;
		}

		//附加信息
		$service_append = new base_service_person_resume_append();
		$service_append->dbquery = true; //load data from subordinate database
		$appends = $service_append->getResumeAppendList($resume['resume_id'], 'topic_desc,content');
		$append_item = $appends->items;
		$this->_aParams['resume_appends'] = array ();
		if (!base_lib_BaseUtils::nullOrEmpty($append_item)) {
			$this->_aParams['resume_appends'] = $append_item;
		}
		/**===============照片===start=================**/
		$album_service = new base_service_person_resume_album();
		$albums = $album_service->getAlbums($resume_id, "id,photo");
		$this->_aParams['resume_albums'] = $albums;
		/**===============照片=== end =================**/
		//技能
		$service_skill = new base_service_person_resume_skill();
		$service_skill->dbquery = true; //load data from subordinate database
		$skill = $service_skill->getResumeSkillList($resume['resume_id'], 'skill_name,skill_level');
		$skill_items = $skill->items;
		$this->_aParams['resume_skills'] = array ();
		if (!base_lib_BaseUtils::nullOrEmpty($skill_items)) {
			$this->_aParams['resume_skills'] = $skill_items;
		}

		//语言能力
		$service_language = new base_service_person_resume_language();
		$service_language->dbquery = true; //load data from subordinate database
		$service_languagecert = new base_service_person_resume_languagecert();
		$service_languagecert->dbquery = true; //load data from subordinate database
		$resume_languages = $service_language->getLanguageList($resume_id, 'resume_id,language_id,language_type,skill_level')->items;
		foreach ($resume_languages as &$resume_language_item) {
			$resume_language_item['certificates'] = $service_languagecert->getCertList($resume_language_item['language_id'], 'cert_id,language_id,cert_name')->items;
		}
		$this->_aParams['resume_languages'] = $resume_languages;

		//实践经验
		if ($isshowresumeinfo) {
			$service_practice = new base_service_person_resume_practice();
			$service_practice->dbquery = true; //load data from subordinate database
			$practices = $service_practice->getResumePracticeList($resume['resume_id'], 'start_time,end_time,practice_name,practice_detail');
			$practice_items = $practices->items;
			$this->_aParams['resume_practices'] = array ();
			if (!base_lib_BaseUtils::nullOrEmpty($practice_items)) {
				for ($a = 0; $a < count($practice_items); $a++) {
					//每份工作经验时间
					$practice_time_diff = (base_lib_TimeUtil::date_diff_year2($practice_items[ $a ]['start_time'], $practice_items[ $a ]['end_time']));
					$practice_items[ $a ]['practice_time'] = base_lib_BaseUtils::nullOrEmpty($practice_time_diff) ? '1个月' : $practice_time_diff;
					//开始结束时间
					$practice_items[ $a ]['start_time'] = base_lib_BaseUtils::nullOrEmpty($practice_items[ $a ]['start_time']) ? '开始时间未填写' : date('Y.m', strtotime($practice_items[ $a ]['start_time']));
					$practice_items[ $a ]['end_time'] = base_lib_BaseUtils::nullOrEmpty($practice_items[ $a ]['end_time']) ? '至今' : date('Y.m', strtotime($practice_items[ $a ]['end_time']));
				}
				$this->_aParams['resume_practices'] = $practice_items;
			}
		}
		/**===============照片===start=================**/
		$album_service = new base_service_person_resume_album();
		$albums = $album_service->getAlbums($resume_id, "id,photo");
		$this->_aParams['resume_albums'] = $albums;
		/**===============照片=== end =================**/

		//感兴趣
		$service_fav = new base_service_company_resume_fav();
		$fav_info = $service_fav->isFav($this->_userid, $resume['resume_id']);
		if (base_lib_BaseUtils::nullOrEmpty($fav_info)) {
			$this->_aParams['is_fav'] = false;
		}
		else {
			$this->_aParams['is_fav'] = $fav_info["is_effect"] == "1";
		}

		//获取备注数量
		$service_resumeremark = new base_service_company_resume_resumeremark();
		$remark_list = $service_resumeremark->getResumeRemarkList($this->_userid, $resume['resume_id'], null, 'remark_id');
		$this->_aParams['remark_count'] = 0;
		if ($remark_list !== false && count($remark_list->items) > 0) {
			$this->_aParams['remark_count'] = count($remark_list->items);
		}

		$service_job_level = new base_service_common_joblevel();
		//岗位级别   实习/见习  普通员工  高级/资深（非管理岗）的编号
		$this->_aParams['job_level_practice'] = $service_job_level->practice;
		$this->_aParams['job_level_ordinary'] = $service_job_level->ordinary;
		$this->_aParams['job_level_senior'] = $service_job_level->senior;
		$service_job_type = new base_service_common_jobtype();
		//工作类型 全职的编号ID
		$this->_aParams['job_type_fulltime'] = $service_job_type->fulltime;

		$this->_aParams['isshowresumeinfo'] = $isshowresumeinfo;

		/**
		 * @desc 获取视频
		 */
		$service_video = new base_service_person_resume_video();
		$video_list = $service_video->getVideoByResumeId($resume["resume_id"], "video_id,video_image_url", 1)->items;
		$this->_aParams["video_list"] = $video_list;

		//图片简历
		$service_resumeimage = new base_service_person_resume_resumeimage();
		$resume_image_list = $service_resumeimage->getImagesByResume($resume["resume_id"], "resume_id,image_url")->items;
		if (!empty($resume_image_list)) {
			foreach ($resume_image_list as $key => $value) {
				$resume_image_list[ $key ]["full_image_url"] = ($is_down ? base_lib_Constant::APP_STYLE_URL : base_lib_Constant::STYLE_URL) . $value["image_url"];
			}
			$this->_aParams["resume_image_list"] = $resume_image_list;
		}

		//简历附件
		$resume_annex_service = new base_service_person_resume_resumeannex();
		$annexinfo = $resume_annex_service->getResumeAnnexNotChange($resume_id, 'id,resume_id,person_id,file_type,file_names,create_time');

		if (!empty($annexinfo)) {
			$file_list = explode(',', $annexinfo['file_names']);
			if (!empty($file_list)) {
				foreach ($file_list as &$file_item) {
					$file_item = explode('|', $file_item);
				}
			}
			$annexinfo['file_list'] = $file_list;
			$this->_aParams['annex'] = $annexinfo;
		}
	}


	private function getPersonExt($person_id) {
		if (empty($person_id)) {
			return false;
		}

		$service_person_exp = new base_service_person_personext();
		$person_exp = $service_person_exp->getData($person_id, 'person_id,is_poor_family,family_address');
		$this->_aParams['_person_exp'] = $person_exp;

		return true;
	}

	/**
	 * @desc 播放视频
	 */
	public function pageShowVideo($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$video_id = base_lib_BaseUtils::getStr($pathdata["video_id"], "int", 0);
		$resume_id = base_lib_BaseUtils::getStr($pathdata["resume_id"], "int", 0);
		if (empty($video_id) || empty($resume_id)) {
			$this->_aParams["error"] = "很抱歉，您查看的视频不存在";
		}
		$service_video = new base_service_person_resume_video();
		$video_info = $service_video->getVideoDataById2($video_id, $resume_id, "video_id,video_image_url,video_url");
		if (empty($video_info)) {
			$this->_aParams["error"] = "很抱歉，您查看的视频不存在";
		}
		$this->_aParams["video_info"] = $video_info;
		//获取浏览器版本号
		$getbrowser = $this->getbrowser();
		if (strtolower($getbrowser['browser']) == "internet explorer" && intval($getbrowser["version"]) < 9) {
			$this->_aParams["error"] = "浏览器版本过低，建议升级浏览器。";
		}
		if (strtolower($getbrowser['browser']) == "internet explorer") {
			$this->_aParams["notice"] = "提示：检测到您使用的是IE内核浏览器，视频解码可能存在异常，建议使用谷歌浏览器！";
		}

		return $this->render("./resume/videoshow.html", $this->_aParams);
	}
	// /**
	//  * 简历预览
	//  * @param int $resume_id  简历编号
	//  * @param bool $show_name true|1  显示全名  false|0 按求职者的设置显示
	//  * @param bool $show_linkway true|1 显示联系方式  false|0 不显示联系方式
	//  * @param bool $not_member  不是会员  true|1  提示开通会员   false|0  不提示
	//  * @param bool $member_expires 会员过期  true|1 提示续费  false|0  不提示
	//  */
	// private function __resumePreview($resume_id, $show_name, $show_linkway, $not_member, $member_expires, $is_apply_resume=false){
	// 	if ($resume_id==0) {
	// 		return;
	// 	}
	// 	$service_resume = new base_service_person_resume_resume();
	// 	$service_resume->dbquery = true; //load data from subordinate database
	// 	//获取简历
	// 	$resume = $service_resume->getResume($resume_id,'resume_id,person_id,degree_id,station,is_salary_show,salary,appraise,expect_job_level,is_not_accept_lower_job_level,is_not_accept_lower_salary,job_type,is_accept_parttime,update_time,point');
	// 	if(base_lib_BaseUtils::nullOrEmpty($resume)){
	// 		return '<div class="datErr">简历已经被删除了，无法查看!</div>';
	// 	}
	// 	$service_person = new base_service_person_person();
	// 	$service_person->dbquery = true; //load data from subordinate database
	// 	//获取用户
	// 	$person = $service_person->getPerson($resume['person_id'],'person_id,user_name,open_mode,sex,email,cur_area_id,has_big_photo,big_photo,photo,photo_open,name_open,birthday2,mobile_phone,mobile_phone_is_validation,email_is_validation,telephone,qq,qq_open,tel_open,email_open,stature,marriage,avoirdupois,political_status,native_place_id,fertility_circumstance,start_work,login_time');
	// 	if(base_lib_BaseUtils::nullOrEmpty($person)){
	// 		echo '<div class="datErr">未找到对应的用户，无法查看!</div>';
	// 		return;
	// 	}
	// 	if(!$is_apply_resume) {
	// 		$enum_openmode = new base_service_common_openmode();
	// 		if($person['open_mode']==$enum_openmode->notopen) {
	// 			 return '<div class="datErr">您查看的简历未公开 </div>';;
	// 		}			

	// 	}

	// 	if($this->_getFilter($resume['person_id'])) {
	// 		return '<div class="datErr">您查看的简历不存在或未向您公开 </div>';
	// 	}	

	// 	// 新增简历访问记录
	// 	$this->_addVisit($resume_id, $person['person_id'], $this->_userid);
	// 	$this->_aParams['title']           = '汇博人才_简历预览';
	// 	$this->_aParams['resume_id']       = $resume_id;
	// 	$this->_aParams['person_id']       = $person['person_id'];
	// 	$this->_aParams['company_id']      = $this->_userid;
	// 	$this->_aParams['last_login_time'] = $person['login_time'];

	// 	//姓名
	// 	if($show_name=='1'){
	// 		$this->_aParams['user_name']=$person['user_name'];
	// 	}else{
	// 		if($person['name_open']==1){
	// 			$this->_aParams['user_name']=$person['user_name'];
	// 		}else{
	// 			$sex_name = $person['sex']==1?'先生':'女士';
	// 			$this->_aParams['user_name']=mb_substr($person['user_name'],0,1,'utf-8').$sex_name;
	// 		}
	// 	}
	// 	//头像，性别。年龄，学历，工作经验，职位类别，接受兼职，低薪资，低岗位，自我评价
	// 	if($person['photo_open']!='0'||$is_apply_resume){
	// 		if(!base_lib_BaseUtils::nullOrEmpty($person['photo'])){
	// 			$avatar = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP."{$person['photo']}";
	// 			//$this->_aParams['photo'] = "<img src='{$avatar}' />";
	// 			if($person['has_big_photo']=='1'){
	// 				$avater_big = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP."{$person['big_photo']}";
	// 				$show_photo = "showPhotoHD(this,null,'{$avater_big}')";
	// 				$this->_aParams['photo'] = "<img onmouseover=\"{$show_photo}\" src='{$avatar}' />";
	// 			}else{
	// 				$this->_aParams['photo'] = "<img src='{$avatar}' />";
	// 			}
	// 		}else{
	// 			$this->_aParams['photo'] ='';
	// 		}
	// 	}
	// 	$this->_aParams['sex']=$person['sex'];
	// 	$this->_aParams['age']= base_lib_TimeUtil::ceil_diff_year($person['birthday2']).'岁';
	// 	$this->_aParams['degree']=$resume['degree_id'];
	// 	$basic_start_work_year = base_lib_TimeUtil::date_diff_month($person['start_work']);
	// 	$workY = floor($basic_start_work_year/12);
	// 	$workM = intval($basic_start_work_year%12);
	// 	if($workY<=0 && $workM<=6&&$workM>=-6){
	// 		$basic_start_work_year = '应届毕业生';
	// 	}else if($workY == 0 && $workM>6){
	// 		$basic_start_work_year = $workM.'个月工作经验';
	// 	}else if($basic_start_work_year<-6){
	// 		$basic_start_work_year = '目前在读';
	// 	}else{
	// 		$basic_start_work_year = $workY.'年工作经验';
	// 	}
	// 	$this->_aParams['work_year'] = $basic_start_work_year;
	// 	$this->_aParams['job_type'] = $resume['job_type'];
	// 	if(!$is_apply_resume){
	// 		$this->_aParams['is_accept_parttime'] = $resume['is_accept_parttime'];
	// 		$this->_aParams['is_not_accept_lower_salary'] = $resume['is_not_accept_lower_salary'];
	// 		$this->_aParams['is_not_accept_lower_job_level'] = $resume['is_not_accept_lower_job_level'];
	// 	}
	// 	$this->_aParams['appraise'] = $resume['appraise'];

	// 	$service_area = new base_service_common_area();
	// 	$service_area->dbquery = true; //load data from subordinate database
	// 	//当前所在地
	// 	$cur_area_name = '';
	// 	if($person['cur_area_id']!=''){
	// 		$cur_area_arr = $service_area->getTopAreaByAreaID($person['cur_area_id']);
	// 		$cur_area_count = count($cur_area_arr);
	// 		for ($j = $cur_area_count-1; $j >= 0; $j--) {
	// 			if($j==0){
	// 				$cur_area_name .= $cur_area_arr[$j]['area_name'];
	// 			}else{
	// 				$cur_area_name .= $cur_area_arr[$j]['area_name'].'-';
	// 			}
	// 		}
	// 	}
	// 	$this->_aParams['cur_area_name'] = $cur_area_name;
	// 	//$this->_aParams['job_state'] = $person['job_state_id'];
	// 	//$this->_aParams['accession_time'] = $resume['accession_time'];
	// 	$this->_aParams['exp_job'] = $resume['station'];
	// 	$this->_aParams['exp_job_level'] = $resume['expect_job_level'];
	// 	if($resume['is_salary_show']==0){
	// 		$this->_aParams['exp_salary']=$resume['salary'];
	// 	}else{
	// 		$this->_aParams['exp_salary']='negotiable';
	// 	}

	// 	//获取意向行业类别
	// 	$service_callingexp = new base_service_person_resume_callingexp();
	// 	$service_calling = new base_service_common_calling();
	// 	$callings = $service_callingexp->getResumeCallingexpList($resume['resume_id'],'resume_id,calling_id');
	// 	$calling_items = $callings->items;
	// 	if(!base_lib_BaseUtils::nullOrEmpty($calling_items)){
	// 		$str_expect_callings = '';
	// 		for ($g = 0; $g < sizeof($calling_items); $g++) {
	// 			if(sizeof($calling_items)==1||$g == sizeof($calling_items)-1){
	// 				$str_expect_callings .= $service_calling->getCallingName($calling_items[$g]['calling_id']);
	// 			}else{
	// 				$str_expect_callings .= $service_calling->getCallingName($calling_items[$g]['calling_id']).';';
	// 			}
	// 		}
	// 		$this->_aParams['str_expect_callings'] = $str_expect_callings;
	// 	}

	// 	//获取意向地区
	// 	$service_areaexp = new base_service_person_areaexp();
	// 	$service_areaexp->dbquery = true; //load data from subordinate database
	// 	$areas = $service_areaexp->getResumeAreaexpList($resume['resume_id'],'person_id,resume_id,area_id');
	// 	$area_items = $areas->items;
	// 	if(!base_lib_BaseUtils::nullOrEmpty($area_items)){
	// 		$area_name = '';
	// 		$area_ids = '';
	// 		$area_ids_arr = array();
	// 		for ($i = 0; $i < sizeof($area_items); $i++) {
	// 			$area = $service_area->getArea($area_items[$i]['area_id'],false);
	// 			if($i == sizeof($area_items)-1){
	// 				$area_ids .= $area['area_id'];
	// 			}else{
	// 				$area_ids .= $area['area_id'].',';
	// 			}
	// 			array_push($area_ids_arr, $area['area_id']);
	// 		}
	// 		$area_name = $this->getSpecialAreaName($service_area,$area_ids_arr,$area_ids);
	// 		$this->_aParams['exp_area_names'] = $area_name;
	// 	}
	// 	/*if(!base_lib_BaseUtils::nullOrEmpty($area_items)){
	// 		$area_name='';
	// 		for ($i = 0; $i < sizeof($area_items); $i++) {
	// 		if($i == sizeof($area_items)-1){
	// 		$area_name .= $service_area->getArea($area_items[$i]['area_id']);
	// 		}else{
	// 		$area_name .= $service_area->getArea($area_items[$i]['area_id']).';';
	// 		}
	// 		}
	// 		$this->_aParams['exp_area_names'] = $area_name;
	// 		}*/

	// 	/********************联系方式****************************/
	// 	/*
	// 	 //手机号
	// 	 $this->_aParams['mobile_phone'] = $person['mobile_phone'];
	// 	 $this->_aParams['mobile_phone_valid'] = $person['mobile_phone_is_validation'];
	// 	 //邮箱
	// 	 $this->_aParams['email'] = $person['email'];
	// 	 $this->_aParams['email_is_validation'] = $person['email_is_validation'];
	// 	 //固话
	// 	 $this->_aParams['telephone'] = $person['telephone'];
	// 	 //QQ
	// 	 $this->_aParams['qq'] = $person['qq'];
	// 	 */
	// 	//手机号
	// 	$this->_aParams['mobile_phone'] = $person['mobile_phone'];
	// 	$this->_aParams['mobile_phone_valid'] = $person['mobile_phone_is_validation'];
	// 	//邮箱
	// 	$this->_aParams['email'] = $person['email'];
	// 	$this->_aParams['email_is_validation'] = $person['email_is_validation'];
	// 	//固话
	// 	$this->_aParams['telephone'] = $person['telephone'];
	// 	//QQ
	// 	$this->_aParams['qq'] = $person['qq'];

	// 	$this->_aParams['show_linkway'] = $show_linkway;
	// 	$this->_aParams['not_member'] = $not_member;
	// 	$this->_aParams['member_expires'] = $member_expires;
	// 	/********************联系方式****************************/

	// 	//户籍
	// 	$native_place_name = '';
	// 	if($person['native_place_id']!=''){
	// 		$native_place = new base_service_common_area();
	// 		$native_place_arr = $native_place->getTopAreaByAreaID($person['native_place_id']);
	// 		$native_place_arr_count = count($native_place_arr);
	// 		for ($j = $native_place_arr_count-1; $j >= 0; $j--) {
	// 			if($j==0){
	// 				$native_place_name .= $native_place_arr[$j]['area_name'];
	// 			}else{
	// 				$native_place_name .= $native_place_arr[$j]['area_name'].'-';
	// 			}
	// 		}
	// 	}
	// 	$this->_aParams['native_place_name'] = $native_place_name;
	// 	//政治面貌
	// 	$this->_aParams['political_status'] = $person['political_status'];
	// 	//身高
	// 	$this->_aParams['stature'] = $person['stature'];
	// 	//体重
	// 	$this->_aParams['avoirdupois'] = $person['avoirdupois'];
	// 	//婚否
	// 	//$this->_aParams['marriage_open'] = $person['marriage_open'];
	// 	//if($person['marriage_open']=='1'){
	// 	$this->_aParams['marriage'] = $person['marriage'];
	// 	$this->_aParams['fertility'] = $person['fertility_circumstance'];
	// 	//}

	// 	//亮点标签
	// 	$service_highlight = new base_service_person_resume_highlight();
	// 	$service_highlight->dbquery = true; //load data from subordinate database
	// 	$highlight = $service_highlight->getResumeHighlightList($resume['resume_id'], 'light_desc');
	// 	$highlight_items = $highlight->items;
	// 	if(!base_lib_BaseUtils::nullOrEmpty($highlight_items)){
	// 		$this->_aParams['resume_highlights'] = $highlight_items;
	// 	}

	// 	//工作经验板块
	// 	$service_work = new base_service_person_resume_work();
	// 	$service_work->dbquery = true; //load data from subordinate database
	// 	$works = $service_work->getResumeWorkList($resume['resume_id'],'start_time,end_time,company_name,calling_id,com_property,com_size,station,is_salary_show,work_content,salary_month,job_level,job_type,department,subordinate');
	// 	$works_items = $works->items;
	// 	if(!base_lib_BaseUtils::nullOrEmpty($works_items)){
	// 		for ($j = 0; $j < count($works_items); $j++) {
	// 			//每份工作经验时间
	// 			$work_time_diff = (base_lib_TimeUtil::date_diff_year2($works_items[$j]['start_time'],$works_items[$j]['end_time']));
	// 			$works_items[$j]['work_time'] = base_lib_BaseUtils::nullOrEmpty($work_time_diff)?'1个月':$work_time_diff;
	// 			//开始结束时间
	// 			$works_items[$j]['start_time'] = base_lib_BaseUtils::nullOrEmpty($works_items[$j]['start_time'])?'开始时间未填写':date('Y.m',strtotime($works_items[$j]['start_time']));
	// 			$works_items[$j]['end_time'] = base_lib_BaseUtils::nullOrEmpty($works_items[$j]['end_time'])?'至今':date('Y.m',strtotime($works_items[$j]['end_time']));
	// 		}
	// 		$this->_aParams['resume_works'] = $works_items;
	// 	}

	// 	//教育经历&培训经历
	// 	$service_edu = new base_service_person_resume_edu();
	// 	$service_edu->dbquery = true; //load data from subordinate database
	// 	$edus = $service_edu->getResumeEduList($resume['resume_id'],'start_time,end_time,school,major_desc,degree,edu_detail,duty');
	// 	$edu_items = $edus->items;
	// 	if(!base_lib_BaseUtils::nullOrEmpty($edu_items)){
	// 		$this->_aParams['resume_edus'] = $edu_items;
	// 	}

	// 	$service_train = new base_service_person_resume_train();
	// 	$service_train->dbquery = true; //load data from subordinate database
	// 	$trains = $service_train->getResumeTrainList($resume['resume_id'],'start_time,end_time,institution,course,certificate,train_detail');
	// 	$train_items = $trains->items;
	// 	if(!base_lib_BaseUtils::nullOrEmpty($train_items)){
	// 		$this->_aParams['resume_trains'] = $train_items;
	// 	}

	// 	//项目经验
	// 	$service_project = new base_service_person_resume_project();
	// 	$service_project->dbquery = true; //load data from subordinate database
	// 	$projects = $service_project->getResumeProjectList($resume['resume_id'],'start_time,end_time,project_name,project_detail,main_duty,duty');
	// 	$project_items = $projects->items;
	// 	if(!base_lib_BaseUtils::nullOrEmpty($project_items)){
	// 		for ($a = 0; $a < count($project_items); $a++) {
	// 			//每份工作经验时间
	// 			$project_time_diff = (base_lib_TimeUtil::date_diff_year2($project_items[$a]['start_time'],$project_items[$a]['end_time']));
	// 			$project_items[$a]['project_time'] = base_lib_BaseUtils::nullOrEmpty($project_time_diff)?'1个月':$project_time_diff;
	// 			//开始结束时间
	// 			$project_items[$a]['start_time'] = base_lib_BaseUtils::nullOrEmpty($project_items[$a]['start_time'])?'开始时间未填写':date('Y.m',strtotime($project_items[$a]['start_time']));
	// 			$project_items[$a]['end_time'] = base_lib_BaseUtils::nullOrEmpty($project_items[$a]['end_time'])?'至今':date('Y.m',strtotime($project_items[$a]['end_time']));
	// 		}
	// 		$this->_aParams['resume_projects'] = $project_items;
	// 	}

	// 	//证书
	// 	$service_certificate = new base_service_person_resume_certificate();
	// 	$service_certificate->dbquery = true; //load data from subordinate database
	// 	$certificates = $service_certificate->getResumeCertificateList($resume['resume_id'],'certificate_name,certificate_no,gain_time,score');
	// 	$certificates_items = $certificates->items;;
	// 	if(!base_lib_BaseUtils::nullOrEmpty($certificates_items)){
	// 		for ($k = 0;$k < count($certificates_items); $k++){
	// 			$grin_time = strtotime($certificates_items[$k]['gain_time']);
	// 			$certificates_items[$k]['gain_time'] = date('Y年m月',$grin_time);
	// 		}
	// 		$this->_aParams['resume_certificates'] =$certificates_items;
	// 	}

	// 	//我的作品
	// 	$service_achievement = new base_service_person_resume_achievement();
	// 	$service_achievement->dbquery = true; //load data from subordinate database
	// 	$service_attachment = new base_service_person_resume_achievementattachment();
	// 	$service_attachment->dbquery = true; //load data from subordinate database
	// 	$achievements = $service_achievement->getResumeAchievementList($resume['resume_id'],'achievement_id,resume_id,achievement_name,start_time,end_time,achievement_description');
	// 	$achievement_item = $achievements->items;
	// 	if(!base_lib_BaseUtils::nullOrEmpty($achievement_item)){
	// 		// 读取配置xml文件
	// 		$xml = SXML::load('../config/person/ResumeAttachment.xml');
	// 		if(!is_null($xml)){
	// 			$attachment_virt_path = base_lib_Constant::UPLOAD_FILE_URL.'/'.$xml->AchievementAttachmentVirtualName.'/';
	// 			$this->_aParams['attachment_virt_path'] = $attachment_virt_path;
	// 			$achievement_thumb_suffix = $xml->ThumbSuffix;
	// 			$achievement_thumb_ext = $xml->AchievementImageExtensions;
	// 		}
	// 		for ($j2 = 0; $j2 < count($achievement_item); $j2++) {
	// 			$attachments = $service_attachment->getResumeAttachmentList($achievement_item[$j2]['achievement_id'],'achievement_id,achievement_path,attachment_name,status,achievement_extension');
	// 			$attachment_item = $attachments->items;
	// 			if(!base_lib_BaseUtils::nullOrEmpty($attachment_item)){
	// 				$achievement_id = $achievement_item[$j2]['achievement_id'];
	// 				for ($k = 0; $k < count($attachment_item); $k++) {
	// 					$fileParts = pathinfo($attachment_item[$k]['achievement_path']);
	// 					if(count(explode($fileParts['extension'],$achievement_thumb_ext))>1){
	// 						//$attachment_item[$k]['achievement_thumb_path'] =$fileParts['dirname'].'/'.$fileParts['filename'].$achievement_thumb_suffix.'.'.$fileParts['extension'];
	// 						$imgsrc = base_lib_BaseUtils::getThumbImg($attachment_virt_path.$attachment_item[$k]['achievement_path'],200,160);
	// 						$attachment_item[$k]['achievement_thumb_path'] = $imgsrc;
	// 					}
	// 				}
	// 				$attachment_temp_item = $this->array_sort($attachment_item,'achievement_extension');
	// 				$this->_aParams['achievement_attachments'.$achievement_id] = $attachment_temp_item;
	// 			}
	// 		}
	// 		$this->_aParams['resume_achievements'] = $achievement_item;

	// 	}

	// 	//附加信息
	// 	$service_append = new base_service_person_resume_append();
	// 	$service_append->dbquery = true; //load data from subordinate database
	// 	$appends = $service_append->getResumeAppendList($resume['resume_id'],'topic_desc,content');
	// 	$append_item = $appends->items;
	// 	if(!base_lib_BaseUtils::nullOrEmpty($append_item)){
	// 		$this->_aParams['resume_appends'] = $append_item;
	// 	}

	// 	//技能
	// 	$service_skill = new base_service_person_resume_skill();
	// 	$service_skill->dbquery = true; //load data from subordinate database
	// 	$skill = $service_skill->getResumeSkillList($resume['resume_id'],'skill_name,skill_level');
	// 	$skill_items =  $skill->items;
	// 	if(!base_lib_BaseUtils::nullOrEmpty($skill_items)){
	// 		$stat_arr = array();
	// 		$skill_temp_item = $skill_items;
	// 		for ($k2 = 0; $k2 < count($skill_temp_item); $k2++) {
	// 			$skill_name = $skill_temp_item[$k2]['skill_name'];
	// 			$skill_level = $skill_temp_item[$k2]['skill_level'];

	// 			if(array_key_exists($skill_level,$stat_arr)){
	// 				$stat_arr[$skill_level] = $stat_arr[$skill_level].'、'.$skill_name;
	// 			}else{
	// 				$stat_arr[$skill_level] = '、'.$skill_name;
	// 			}
	// 		}
	// 		$this->_aParams['resume_skills'] = $stat_arr;
	// 	}

	// 	/*//语言能力
	// 	 $service_language = new base_service_person_resume_language();
	// 	 $languages = $service_language->getLanguagePriview($resume['resume_id']);
	// 	 $language_items = $languages->items;
	// 	 if(!base_lib_BaseUtils::nullOrEmpty($language_items)){
	// 		$this->_aParams['resume_languages'] = $languages->items;
	// 		}*/

	// 	//语言能力
	// 	$service_language = new base_service_person_resume_language();
	// 	$service_language->dbquery = true; //load data from subordinate database
	// 	$service_languagecert = new base_service_person_resume_languagecert();
	// 	$service_languagecert->dbquery = true; //load data from subordinate database
	// 	$resume_languages = $service_language->getLanguageList($resume_id,'resume_id,language_id,language_type,skill_level')->items;
	// 	foreach($resume_languages as &$resume_language_item){
	// 		$resume_language_item['certificates'] = $service_languagecert->getCertList($resume_language_item['language_id'], 'cert_id,language_id,cert_name')->items;
	// 	}
	// 	$this->_aParams['resume_languages'] = $resume_languages;

	// 	//实践经验
	// 	$service_practice = new base_service_person_resume_practice();
	// 	$service_practice->dbquery = true; //load data from subordinate database
	// 	$practices = $service_practice->getResumePracticeList($resume['resume_id'], 'start_time,end_time,practice_name,practice_detail');
	// 	$practice_items = $practices->items;
	// 	if(!base_lib_BaseUtils::nullOrEmpty($practice_items)){
	// 		for ($a = 0; $a < count($practice_items); $a++) {
	// 			//每份工作经验时间
	// 			$practice_time_diff = (base_lib_TimeUtil::date_diff_year2($practice_items[$a]['start_time'],$practice_items[$a]['end_time']));
	// 			$practice_items[$a]['practice_time'] = base_lib_BaseUtils::nullOrEmpty($practice_time_diff)?'1个月':$practice_time_diff;
	// 			//开始结束时间
	// 			$practice_items[$a]['start_time'] = base_lib_BaseUtils::nullOrEmpty($practice_items[$a]['start_time'])?'开始时间未填写':date('Y.m',strtotime($practice_items[$a]['start_time']));
	// 			$practice_items[$a]['end_time'] = base_lib_BaseUtils::nullOrEmpty($practice_items[$a]['end_time'])?'至今':date('Y.m',strtotime($practice_items[$a]['end_time']));
	// 		}
	// 		$this->_aParams['resume_practices'] = $practice_items;
	// 	}

	// 	//感兴趣
	// 	$service_fav = new base_service_company_resume_fav();
	// 	$fav_info = $service_fav->isFav($this->_userid, $resume['resume_id']);
	// 	if(base_lib_BaseUtils::nullOrEmpty($fav_info)){
	// 		$this->_aParams['is_fav'] = 0;
	// 	}else{
	// 		$this->_aParams['is_fav'] = $fav_info["is_effect"] == "1";
	// 	}

	// 	$service_job_level = new base_service_common_joblevel();	
	//    	//岗位级别   实习/见习  普通员工  高级/资深（非管理岗）的编号
	//    	$this->_aParams['job_level_practice'] = $service_job_level->practice;
	//    	$this->_aParams['job_level_ordinary'] = $service_job_level->ordinary;
	//    	$this->_aParams['job_level_senior'] = $service_job_level->senior;
	// 	$service_job_type = new base_service_common_jobtype();
	//    	//工作类型 全职的编号ID
	//    	$this->_aParams['job_type_fulltime'] = $service_job_type->fulltime;

	// 	return $this->render('./resume/preview.html', $this->_aParams);
	// }

	/**
	 * 获取地区名称(特殊  例如：重庆-主城区,北京-五环以内)
	 * @param        $service_area       实例化对象
	 * @param array  $area_ids_arr       选择的地区数组
	 * @param string $area_ids           选择的地区字符串
	 */
	private function getSpecialAreaName($service_area, $area_ids_arr, $area_ids) {
		$area_name = '';
		if (count($area_ids_arr) <= 0) {
			return $area_name;
		}
		$xml = SXML::load('../config/person/Person.xml');
		if (!is_null($xml)) {
			$cq_mian_city = $xml->CQMainCity;
			$cq_other_counties = $xml->CQOtherCounties;
			$bj_rings_within = $xml->BJRingsWithin;
			$bj_rings_without = $xml->BJRingsWithout;
			$sh_outer_within = $xml->SHOuterWithin;
			$sh_suburbs = $xml->SHSuburbs;
			$tj_mian_city = $xml->TJMainCity;
			$tj_other_counties = $xml->TJOtherCounties;
		}
		$temp_area_ids_arr = array ();
		//重庆
		$cq_mian_city_arr = explode(',', $cq_mian_city);
		$cq_other_counties_arr = explode(',', $cq_other_counties);

		$intersect_cqmain = implode(',', array_intersect($cq_mian_city_arr, $area_ids_arr)) == $cq_mian_city;
		$intersect_cqother = implode(',', array_intersect($cq_other_counties_arr, $area_ids_arr)) == $cq_other_counties;

		if ($intersect_cqmain && $intersect_cqother) {
			$area_name .= '重庆-主城区;重庆-周边区县;';
			$temp_area_ids_arr = array_diff($area_ids_arr, $cq_mian_city_arr);
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $cq_other_counties_arr);
		}
		elseif ($intersect_cqmain) {
			$area_name .= '重庆-主城区;';
			$temp_area_ids_arr = array_diff($area_ids_arr, $cq_mian_city_arr);
		}
		elseif ($intersect_cqother) {
			$area_name .= '重庆-周边区县;';
			$temp_area_ids_arr = array_diff($area_ids_arr, $cq_other_counties_arr);
		}
		$temp_area_ids_arr = count($temp_area_ids_arr) <= 0 && empty($area_name) ? $area_ids_arr : $temp_area_ids_arr;

		//北京
		$bj_mian_city_arr = explode(',', $bj_rings_within);
		$bj_other_counties_arr = explode(',', $bj_rings_without);

		$intersect_bjmain = implode(',', array_intersect($bj_mian_city_arr, $temp_area_ids_arr)) == $bj_rings_within;
		$intersect_bjother = implode(',', array_intersect($bj_other_counties_arr, $temp_area_ids_arr)) == $bj_rings_without;

		if ($intersect_bjmain && intersect_bjother) {
			$area_name .= '北京-五环以内;北京-五环以外;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $bj_mian_city_arr);
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $bj_other_counties_arr);
		}
		elseif ($intersect_bjmain) {
			$area_name .= '北京-五环以内;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $bj_mian_city_arr);
		}
		elseif ($intersect_bjother) {
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $bj_other_counties_arr);
			$area_name .= '北京-五环以外;';
		}
		//上海
		$sh_mian_city_arr = explode(',', $sh_outer_within);
		$sh_other_counties_arr = explode(',', $sh_suburbs);

		$intersect_shmain = implode(',', array_intersect($sh_mian_city_arr, $temp_area_ids_arr)) == $sh_outer_within;
		$intersect_shother = implode(',', array_intersect($sh_other_counties_arr, $temp_area_ids_arr)) == $sh_suburbs;

		if ($intersect_shmain && $intersect_shother) {
			$area_name .= '上海-外环以内;上海-郊区/县;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $sh_mian_city_arr);
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $sh_other_counties_arr);
		}
		elseif ($intersect_shmain) {
			$area_name .= '上海-外环以内;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $sh_mian_city_arr);
		}
		elseif ($intersect_shother) {
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $sh_other_counties_arr);
			$area_name .= '上海-郊区/县;';
		}
		//天津
		$tj_mian_city_arr = explode(',', $tj_mian_city);
		$tj_other_counties_arr = explode(',', $tj_other_counties);

		$intersect_tjmain = implode(',', array_intersect($tj_mian_city_arr, $temp_area_ids_arr)) == $tj_mian_city;
		$intersect_tjother = implode(',', array_intersect($tj_other_counties_arr, $temp_area_ids_arr)) == $tj_other_counties;

		if ($intersect_tjmain && $intersect_tjother) {
			$area_name .= '天津-主城区;天津-周边区县;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $tj_mian_city_arr);
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $tj_other_counties_arr);
		}
		elseif ($intersect_tjmain) {
			$area_name .= '天津-主城区;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $tj_mian_city_arr);
		}
		elseif ($intersect_tjother) {
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $tj_other_counties_arr);
			$area_name .= '天津-周边区县;';
		}
		$temp_area_ids_arr = array_merge($temp_area_ids_arr);
		if (count($temp_area_ids_arr) == 0) {
			$area_name = mb_substr($area_name, 0, mb_strlen($area_name, 'utf-8') - 1);
		}
		for ($m2 = 0; $m2 < count($temp_area_ids_arr); $m2++) {
			$area_temp_name = $service_area->getArea($temp_area_ids_arr[ $m2 ]);
			if ($m2 == sizeof($temp_area_ids_arr) - 1) {
				$area_name .= $area_temp_name;
			}
			else {
				$area_name .= $area_temp_name . ';';
			}
		}

		return $area_name;
	}

	/**
	 * 数组排序 (自定义附件排序)
	 * @param array  $arr  需排序的数组
	 * @param string $keys 排序的字段
	 */
	private function array_sort($arr, $keys) {
		$new_array = array ();
		if ($keys == 'achievement_extension') {
			$z = 1;
			foreach ($arr as $k => $v) {
				if (count(explode($arr[ $k ]['achievement_extension'], '.jpg,.jpeg,.gif,.bmp,.png')) > 1) {
					if ($z > 1) {
						$new_array[ $k - ($z - 1) ] = $arr[ $k ];
					}
					else {
						$new_array[ $k ] = $arr[ $k ];
					}
				}
				else {
					$new_array[ count($arr) - $z ] = $arr[ $k ];
					$z++;
				}
			}
			ksort($new_array);

			return $new_array;
		}
		else {
			ksort($arr);

			return $arr;
		}
	}


	/**
	 * 数组查询
	 * @param array  $arr
	 * @param string $key
	 * @param string $value
	 */
	private function arrayFind($arr, $property, $value) {
		$obj = null;
		foreach ($arr as $item) {
			if ($item[ $property ] == $value) {
				$obj = $item;
				break;
			}
		}

		return $obj;
	}

	/**
	 * @desc 当企业下载简历的时候更改简历状态为已读
	 */
	private function changeApplyReadStatus($applyids) {
		//更改简历的未读状态
		if (base_lib_BaseUtils::nullOrEmpty($applyids)) {
			return false;
		}
		if (!is_array($applyids)) {
			$applyids = explode(",", $applyids);
		}
		$service_apply = new base_service_company_resume_apply();
		$result = $service_apply->setRead($applyids, $this->_userid);

		return $result;
	}

	/**
	 * 获取简历历史记录
	 */
	private function _getResumeHistoryRecord($company_id, $resume_id) {
		$service_apply = new base_service_company_resume_apply();
		$service_invite = new base_service_company_resume_jobinvite();
		$service_remark = new base_service_company_resume_resumeremark();
		$applys = $service_apply->getApplyByPerson($company_id, $resume_id, null, 'station,create_time,re_status', 0);
		$invites = $service_invite->getInviteList($resume_id, $company_id, 'station,apply_id,invite_type,create_time,audition_time,audition_address,audition_link_man,audition_link_tel');
		$remarks = $service_remark->getResumeRemarkList($company_id, $resume_id, null, 'remark,update_time');
		// lambda 查询婉拒的求职申请
		$search_refuse = function ($date) {
			return function ($apply) use ($date) {
				return $apply['re_status'] == 3 && date('Y-m-d', strtotime($apply['create_time'])) == $date;
			};
		};
		// lambda 查询应聘职位信息
		$search_norefuse = function ($date) {
			return function ($apply) use ($date) {
				return date('Y-m-d', strtotime($apply['create_time'])) == $date;
			};
		};
		// lambda 查询邀请信息
		$search_invite = function ($date) {
			return function ($invite) use ($date) {
				return date('Y-m-d', strtotime($invite['create_time'])) == $date;
			};
		};
		// lambda 查询备注信息
		$search_remark = function ($date) {
			return function ($remark) use ($date) {
				return date('Y-m-d', strtotime($remark['update_time'])) == $date;
			};
		};
		$count = 20; // 最多显示消息数量
		$items = array ();
		$items_ago = array ();
		//XXX: 使用循环递减的时间（90天以内）
		for ($i = 0; $i <= 90; $i += 1) {
			$d = date('Y-m-d', strtotime("-{$i} days"));
			// 合并同一天的面试邀请,求职婉拒
			$arr_invite = array_filter($invites->items, $search_invite($d));
			$arr_invite = base_lib_BaseUtils::array_sort($arr_invite, 'create_time', true);
			$invite = reset($arr_invite);

			if ($invite) {
				$time = $this->_getAuditionTime($invite['audition_time']);
				$hide_content = "{$d} 邀请面试 &nbsp;&nbsp;&nbsp;&nbsp;面试职位：【{$invite['station']}】，面试时间【{$time}】,面试地点【{$invite['audition_address']}】,联系人【{$invite['audition_link_man']}】,联系电话【{$invite['audition_link_tel']}】";
				if (!base_lib_BaseUtils::nullOrEmpty($time)) {
					array_push($items, array ('time' => $d, 'type' => 1, 'datetime' => strtotime($invite['create_time']), 'content' => "邀请面试：面试职位【{$invite['station']}】，面试时间【{$time}】" , 'hide_content' => $hide_content));
				}
				else {
					array_push($items, array ('time' => $d, 'type' => 1, 'datetime' => strtotime($invite['create_time']), 'content' => "邀请面试：面试职位【{$invite['station']}】", 'hide_content' => $hide_content));
				}
			}
			// 合并同一天求职申请
			$arr_apply = array_filter($applys->items, $search_norefuse($d));
			if (count($arr_apply) > 0) {
				$first_apply = reset($arr_apply);
				$str = array ();
				foreach ($arr_apply as $a) {
					if ($a['re_status'] == 3) {
						array_push($str, $a['station'] . '(已婉拒)');
					}
					else {
						array_push($str, $a['station']);
					}
				}
				array_push($items, array ('time' => $d, 'type' => 2, 'datetime' => strtotime($first_apply['create_time']), 'content' => '应聘职位：' . implode('；', $str)));
			}

			// 备注信息
			$arr_remark = array_filter($remarks->items, $search_remark($d));
			if (count($arr_remark) > 0) {
				foreach ($arr_remark as $remark) {
					array_push($items, array ('time' => $d, 'type' => 4, 'datetime' => strtotime($remark['update_time']), 'content' => '添加备注：' . $remark['remark']));
				}
			}
			// 记录90天内的数据
			if ($i == 89) {
				$items_ago = $items;
				$items = array ();
			}
			if ((count($items) + count($items_ago)) >= $count) {
				break;
			}
		}
		$this->_aParams['historyrecords'] = base_lib_BaseUtils::array_sort($items_ago, 'datetime', true);
		$this->_aParams['historyrecordagos'] = base_lib_BaseUtils::array_sort($items, 'datetime', true);
	}

	// 获取面试时间
	private function _getAuditionTime($invite_audition_time) {
		$audition_time = base_lib_BaseUtils::getStr($invite_audition_time, 'datetime', null);
		if (base_lib_BaseUtils::nullOrEmpty($audition_time)) {
			return $invite_audition_time;
		}

		$auditiondate = date('Y年m月d日', strtotime($audition_time));
		$noon = date('H', strtotime($audition_time)) <= 12 ? '上午' : '下午';

		$auditiontime = $noon . date('H:i', strtotime($audition_time));
		$week = $this->_week(base_lib_TimeUtil::date_of_week($audition_time));
		$time = $auditiondate . '（' . $week . '）' . $auditiontime;

		return $time;
	}

	/**
	 * 返回星期
	 * @param $number
	 */
	private function _week($number) {
		switch ($number) {
			case 1:
				$week = '周一';
				break;
			case 2:
				$week = '周二';
				break;
			case 3:
				$week = '周三';
				break;
			case 4:
				$week = '周四';
				break;
			case 5:
				$week = '周五';
				break;
			case 6:
				$week = '周六';
				break;
			default:
				$week = '周日';
				break;
		}

		return $week;
	}
    
	/**
	 * @desc 设置已查看简历
	 */
	public function pageSetGetLinkWay($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$person_id = base_lib_BaseUtils::getStr($pathdata["person_id"], "int", 0);
		$apply_id = base_lib_BaseUtils::getStr($pathdata["apply_id"], "int", 0);

		if ($person_id == 0) {
			return;
		}
		if (!empty($apply_id)) {
			$service_apply = new base_service_company_resume_apply();
			$service_job = new base_service_company_job_job();
			$apply_info = $service_apply->getApply($apply_id, "job_id");
			$job_id = $apply_info["job_id"];
			$job_info = $service_job->getJob($job_id, "job_id,station,jobsort", $this->_userid);
			if (empty($job_info)) {
				return;
			}
		}
		$service_getlinkway = new base_service_company_appraise_linkwayget();
		$items = array ();
		if (!empty($job_info)) {
			$items["job_id"] = $job_id;
			$items["station"] = $job_info["station"];
		}
        //添加记录
        
		$service_getlinkway->addGetLinkway($this->_userid, $person_id, "get_linkway", $items);
	}

	function getbrowser() {
		global $_SERVER;
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$browser = '';
		$browser_ver = '';

		if (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $agent, $regs)) {
			$browser = 'OmniWeb';
			$browser_ver = $regs[2];
		}

		if (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $agent, $regs)) {
			$browser = 'Netscape';
			$browser_ver = $regs[2];
		}

		if (preg_match('/safari\/([^\s]+)/i', $agent, $regs)) {
			$browser = 'Safari';
			$browser_ver = $regs[1];
		}

		if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) {
			$browser = 'Internet Explorer';
			$browser_ver = $regs[1];
		}

		if (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs)) {
			$browser = 'Opera';
			$browser_ver = $regs[1];
		}

		if (preg_match('/NetCaptor\s([^\s|;]+)/i', $agent, $regs)) {
			$browser = '(Internet Explorer ' . $browser_ver . ') NetCaptor';
			$browser_ver = $regs[1];
		}

		if (preg_match('/Maxthon/i', $agent, $regs)) {
			$browser = '(Internet Explorer ' . $browser_ver . ') Maxthon';
			$browser_ver = '';
		}
		if (preg_match('/360SE/i', $agent, $regs)) {
			$browser = '(Internet Explorer ' . $browser_ver . ') 360SE';
			$browser_ver = '';
		}
		if (preg_match('/SE 2.x/i', $agent, $regs)) {
			$browser = '(Internet Explorer ' . $browser_ver . ') 搜狗';
			$browser_ver = '';
		}

		if (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs)) {
			$browser = 'FireFox';
			$browser_ver = $regs[1];
		}

		if (preg_match('/Lynx\/([^\s]+)/i', $agent, $regs)) {
			$browser = 'Lynx';
			$browser_ver = $regs[1];
		}

		if (preg_match('/Chrome\/([^\s]+)/i', $agent, $regs)) {
			$browser = 'Chrome';
			$browser_ver = $regs[1];

		}

		if ($browser != '') {
			return ['browser' => $browser, 'version' => $browser_ver];
		}
		else {
			return ['browser' => 'unknow browser', 'version' => 'unknow browser version'];
		}
	}

	public function pageGetResumeAndJobData($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_ids = base_lib_BaseUtils::getStr($pathdata["resume_ids"], "string", "");
		$job_ids = base_lib_BaseUtils::getStr($pathdata["job_ids"], "string", "");

		//获取简历信息
		$service_resume = new base_service_person_resume_resume();
		$service_person = new base_service_person_person();
		$service_job = new base_service_company_job_job();
		$degree_common = new base_service_common_degree();

		if (empty($resume_ids) && empty($job_ids)) {
			echo json_encode(array ("status" => false));
			exit;
		}

		$job_list = array ();
		$job_status = array ();
		if (!empty($job_ids)) {
			$job_ids = explode(",", $job_ids);
			$job_list = $service_job->getJobs($job_ids, "status,job_id,end_time,is_effect,check_state");
			if (!empty($job_list)) {
				foreach ($job_list as $key => $value) {
//                    array ('end_time' => array ('$lt' => $stamp)),
//					array ('status' => intval($job_status)),
//					array ('check_state' => array ('$in' => array (2, 3, 9)))
					if ($value["end_time"] < date("Y-m-d 00:00:00") || in_array($value["status"], [0, 9]) || in_array($value["status"], [2, 3, 9]) || $value["is_effect"] == 0) {
						$job_list[ $key ]["job_status"] = 0;
						$job_status[ $value["job_id"] ] = 0;
					}
					else {
						$job_list[ $key ]["job_status"] = 1;
						$job_status[ $value["job_id"] ] = 1;
					}

				}
			}
		}
		$resume_data = array ();
		if (!empty($resume_ids)) {
			$resume_ids = explode(",", $resume_ids);
			$resume_list = $service_resume->getResumes($resume_ids, "person_id,degree_id,resume_id");
			$person_ids = base_lib_BaseUtils::getProperty($resume_list, "person_id");
			if (!empty($person_ids)) {
				$person_list = $service_person->getPersons($person_ids, "person_id,user_name,birthday2,start_work");
				$person_list = base_lib_BaseUtils::array_key_assoc($person_list, "person_id");
			}
			foreach ($resume_list as $value) {
				$_data = array ();
				$person_info = $person_list[ $value["person_id"] ];
				$_data["resume_id"] = $value["resume_id"];
				$_data["person_id"] = $value["person_id"];
				$_data["work_year"] = !empty($person_info["start_work"]) ? $this->_calWorkYear($person_info["start_work"]) : "工作经验未知";//工作经验
				$_data["age"] = !empty($person_info["birthday2"]) ? base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']) . '岁' : "年龄未知";//工作经验
				$_data["degree_name"] = "学历未知";
				if (!empty($value["degree_id"])) {
					$degree_info = $degree_common->getDegree($value["degree_id"]);
					$_data["degree_name"] = $degree_info;
				}
				$resume_data[] = $_data;
			}
		}
		echo json_encode(array ("status" => true, "resume_list" => $resume_data, "job_status" => $job_status, "job_list" => $job_list));
		exit;
	}

	/**
	 * @Desc 格式化工作经验
	 * @param $start_work YYYY-mm-dd
	 * @return text 格式化文字
	 */
	private function _calWorkYear($start_work) {
		$basic_start_work_year = base_lib_TimeUtil::date_diff_month($start_work);
		$workY = floor($basic_start_work_year / 12);
		$workM = intval($basic_start_work_year % 12);
		if ($workY <= 0 && $workM <= 6 && $workM > -6) {
			$basic_start_work_year = '应届毕业生';
		}
		else if ($workY == 0 && $workM > 6) {
			$basic_start_work_year = $workM . '个月工作经验';
		}
		else if ($basic_start_work_year <= -6) {
			$basic_start_work_year = '目前在读';
		}
		else {
			$basic_start_work_year = $workY . '年工作经验';
		}

		return $basic_start_work_year;
	}

	/*
	 * 展示简历详情提示
	 * */
	public function pageresumeinfomsghtml($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$this->_aParams['type'] = $type = base_lib_BaseUtils::getStr($pathdata['type'], 'string', 'default');
		$this->_aParams['is_allot_pricing'] = $type = base_lib_BaseUtils::getStr($pathdata['is_allot_pricing'], 'int');
		//获取首页的推广金，置顶点等资料

		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		$company_level_data = $company_resources->getCompanyServiceSource(['company_level']);
		//添加是否存在待使用优惠劵 已进行企业资料验证；已通过营业执照验证；非在用会员
		$letter_info = $company_resources->getCompanyAuditStatusV2();
		$licence_audit_type = $letter_info['licence_audit_type'];
		$letter_audit_type = $letter_info['letter_audit_type'];
		if ($this->_aParams['type'] != 'default' && in_array($licence_audit_type, [1, 4, 5]) && in_array($letter_audit_type, [1, 4]) && $company_level_data['company_level'] == 0) {
			$ser_allotCouponPricing = new base_service_company_coupon_allotCouponPricing();
			$exist_where = [
				'allot_state'   => 2,
				'state'         => 1,
				'validity_date' => $this->_ymd,
			];
			$allotCouponPricing_data = $ser_allotCouponPricing->getAllotCouponPricingByCompanyID($this->_userid, $exist_where);
			if ($allotCouponPricing_data && $allotCouponPricing_data['id']) {
				$this->_aParams['allotCouponPricing_data'] = $allotCouponPricing_data;

				return $this->render('allotCouponPricing.html', $this->_aParams);
			}
		}

		if ($this->_aParams['is_allot_pricing']) {
			$this->_aParams['msg'] = "暂无体验劵待激活!";

			return $this->render('common/tipsmsg1.html', $this->_aParams);
		}

		$companyresources = $company_resources->getCompanyServiceSource(["account_resource"]);

		$this->_aParams['companyresources'] = $companyresources;
		$this->_aParams['account_overage'] = $companyresources['account_overage'];

		return $this->render('cqshowresumeinfo.html', $this->_aParams);
	}

	public function pageGetShareHtml($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_id = base_lib_BaseUtils::getStr($pathdata['resume_id'], 'int', 0);
		$companyService = base_service_company_company::getInstances();
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$company = $companyService->getCompany($this->_userid, true, 'company_flag');
		if (empty($company)) {
			exit;
		}
		$share_link = $this->_getShareLink($resume_id, $account_id, $company["company_flag"]);
		$_shrea_link = urlencode($share_link);
		$share_img = base_lib_Constant::COMPANY_URL . "/share/SharelinkImg/?key={$resume_id}&ac={$account_id}&flag={$company["company_flag"]}";
		$this->_aParams["share_link"] = $share_link;
		$this->_aParams["share_img"] = $share_img;

		return $this->render("./resume/shareapp.html", $this->_aParams);

	}

	//获取分享链接
	private function _getShareLink($resume_id, $account_id, $company_flag) {
		$cache_time = 604800;
		$cache_share_code = "app_com_cache_share_code";
		//简历分享
		$cache_share_code = $cache_share_code . $resume_id . "_" . $account_id;
		$cache_obj = new base_lib_Cache('redis');
		if (!$cache_obj->effect) {
			return null;
		}
		$has_cache_data = $cache_obj->get($cache_share_code);
		if (empty($has_cache_data)) {
			$cache_data = $resume_id . "_" . $account_id;
			$cache_obj->set($cache_share_code, $cache_data, $cache_time);
		}

		$share_url = base_lib_Constant::APP_MOBILE_URL . "/share/NewIndex/?key=" . $resume_id . "&ac=" . $account_id . "&flag=" . $company_flag;

		return $share_url;
	}

	public function pageSharelinkImg($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_id = base_lib_BaseUtils::getStr($pathdata['resume_id'], 'int', 0);
		$account_id = base_lib_BaseUtils::getCookie('accountid');

		$resume_id = base_lib_BaseUtils::getStr($pathdata['resume_id'], 'int', 0);
		$companyService = base_service_company_company::getInstances();
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company = $companyService->getCompany($this->_userid, true, 'company_flag');
		if (empty($company)) {
			exit;
		}
		$share_link = $this->_getShareLink($resume_id, $account_id, $company["company_flag"]);
		SQrcode::png($share_link);
	}

}

?>
