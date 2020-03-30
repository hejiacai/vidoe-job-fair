<?php

/**
 * @copyright 2002-2013 www.huibo.com
 * @name 企业消息
 * @author    ZhangYu
 * @date      2013-10-24
 *
 */
class controller_message extends components_cbasepage {
	
	function __construct() {
		parent::__construct();
	}
	
	
	public function pageIndex($inPath) {
		// 获取参数信息
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		// 个人信息统计
		$service_message = new base_service_message_messagecompany();
		// 查询消息
		$page_index = base_lib_BaseUtils::getStr($pathdata['page'], 'int', 1);
		$page_size = base_lib_Constant::PAGE_SIZE;  //每页显示多少条
		//define('DEBUG', true);
		$messagelist = $service_message->getMessage($page_index, $page_size, $this->_userid, null, 'message_id,company_id,subject,content,create_time,sender,read_state,read_time,is_effect,link_msg_id,type,end_time,is_appraise,appraise_time,workorder_id');
		$list = $messagelist->items;
		$message_ids = base_lib_BaseUtils::getPropertys($list,"message_id");
		$service_message_messagepersonappraise  = new base_service_message_messagepersonappraise();
		$message_appraise_list = $service_message_messagepersonappraise->getAppraiseListByMessageIds($message_ids,$this->_userid,2);
		$message_appraise_list = base_lib_BaseUtils::array_key_assoc($message_appraise_list,'message_id');
		if (count($list) <= 0) {
			$this->_aParams['hasdata'] = false;
		} else {
			$this->_aParams['hasdata'] = true;
		}
		$noReadMessge = array ();
		for ($i = 0; $i < count($list); $i += 1) {
			//$list[$i]['issys'] = ($list[$i]['company_id']=='0'?true:false);
			$list[ $i ]['time'] = base_lib_TimeUtil::to_friend_time($list[ $i ]['create_time']);
			if ($list[ $i ]['read_state'] == '0') {
				array_push($noReadMessge, $list[ $i ]['message_id']);
			}

			$list[ $i ]['is_advise'] = 0;
			if(isset($message_appraise_list[$list[$i]['message_id']])){
				$list[ $i ]['is_advise'] = 1;
				$list[ $i ]['appraise_grade'] = $message_appraise_list[$list[$i]['message_id']]['appraise_grade'];

			}
			$list[ $i ]['content'] = base_lib_BaseUtils::htmlEncode($list[ $i ]['content']);
//				$list[$i]['content'] = str_replace(PHP_EOL, "<br/>", $list[$i]['content']);
			$list[ $i ]['content'] = htmlspecialchars_decode($list[ $i ]['content'], ENT_QUOTES);
		}

		$enum_readstate = new base_service_message_messagereadstate();
		if (count($noReadMessge) > 0) {
			$service_message->setReadState($noReadMessge, $enum_readstate->readed, $this->_userid);
		}
		$pager = $this->pageBar($messagelist->totalSize, $page_size, $page_index, $inPath);
		$xml = SXML::load('../config/config.xml');
		$this->_aParams['title'] = "消息提醒 我的消息-{$xml->HuiBoSiteName}";
		$this->_aParams['item'] = $list;
		$this->_aParams['pager'] = $pager;
		
		$bulletin_service = new base_service_bulletin_bulletin();
		$newBulletin = $bulletin_service->getNewBulletin7Day(1);
		
		if (!empty($newBulletin)) {
			$data = array ();
			foreach ($newBulletin as $item) {
				if (base_lib_BaseUtils::getCookie("newBulletinidByMsgList{$item['id']}")) {
					continue;
				}
				//存在改cookie 则不设置查看推广
				if (!base_lib_BaseUtils::getCookie("newBulletinidByMsg{$item['id']}")) {
					$cookie = array (
						"newBulletinidByMsg{$item['id']}" => true,
					);
					$endTime = strtotime($item['create_time']) + (60 * 60 * 24 * 7) - time();
					base_lib_BaseUtils::ssetcookie($cookie, $endTime, '/', base_lib_Constant::COOKIE_DOMAIN);
				}
				$data[] = $item;
			}
			$this->_aParams['newBulletin'] = $data;
		}
		
		return $this->render('message.html', $this->_aParams);
		
	}

	/**
	 * 评分保存
	 * @param $inPath
	 * @return mixed
	 */
	public function pageMessageAppraiseDo($inPath){
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$message_id = base_lib_BaseUtils::getStr($pathdata["message_id"],"int",'');
		$content = base_lib_BaseUtils::getStr($pathdata["content"],"string",'');
		$message_appraise = base_lib_BaseUtils::getStr($pathdata["message_appraise"],"int",'');

		$service_message_messagepersonappraise = new base_service_message_messagepersonappraise();
		$message_type = 2;
		$result = $service_message_messagepersonappraise->addMessageAppraise($message_id,$this->_userid,$message_appraise,$message_type,$content);
		if(!$result['status']){
			return json_encode(['status' => false, 'msg' => $result['msg']]);
		}
		return json_encode(['status' => true, 'msg' => '评分成功']);
	}
	
	//判断职位审核申诉窗口是否能打开
	public function pageCheckJobAuditAllegeTimes($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$in_data['job_id'] = base_lib_BaseUtils::getStr($pathdata['job_id'], 'string', '');
		$in_data['company_id'] = $this->_userid;
		
		$base_service_report_report = new base_service_report_report();
		
		$res = $base_service_report_report->CheckJobAuditAllegeTimes($in_data['company_id'], $in_data['job_id']);
		if ($res == 1) {
			$this->ajax_data_json(ERROR, '传入参数错误');
		} else if ($res == 2) {
			$this->ajax_data_json(ERROR, '您最近15天内已有3次申诉不属实，15天内无法使用申诉功能');
		} else if ($res == 3) {
			$this->ajax_data_json(ERROR, '您已经申诉过了');
		}
		
		$this->ajax_data_json(SUCCESS, '您可以申诉');
	}
	
	//职位审核申述窗口
	public function pageJobAuditAllege($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$in_data['job_id'] = base_lib_BaseUtils::getStr($pathdata['job_id'], 'string', '');
		$in_data['company_id'] = $this->_userid;
		return $this->render('jobauditallege.html',$in_data);
	}
	
	
	//职位审核申述 生成工单 提交
	public function pageAddJobAuditRecord($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($pathdata['job_id'], 'string', '');
		$company_id = $this->_userid;
		$title = base_lib_BaseUtils::getStr($pathdata['title'], 'string', '');
		$content = base_lib_BaseUtils::getStr($pathdata['content'], 'string', '');
		
		$base_service_report_report = new base_service_report_report();
		//判断是否该单位下的该职位是否已经申诉过
        $res = $base_service_report_report->CheckJobAuditAllegeTimes($company_id, $job_id);
		if ($res == 2) {
            $json['status'] = false;
			$json['msg'] = '您最近15天内已有3次申诉不属实，15天内无法使用申诉功能';
			echo json_encode($json);
			exit;
		}else if($res == 3){
            $json['status'] = false;
            $json['msg'] = '您已经申诉过了';
            echo json_encode($json);
            exit;
        }
		
		$service_job = new base_service_company_job_job();
		$jobname = $service_job->getJob($job_id, 'station');

		$company_accounservice = new base_service_company_account();
		$companyaccount = $company_accounservice->getAccount($_COOKIE['accountid'], 'user_name,mobile_phone,link_tel');
        $company_service = new base_service_company_company();
        $company = $company_service->getCompany($company_id, 1,'company_name');
		$data['type'] = 1;
		$data['way'] = 2;
		$data['order_type'] = 16;
		$data['obj_id'] = $job_id;
		$data['obj_name'] = $jobname['station'];
		$data['title'] = $title;
		$data['content'] = $content;
        $data['obj_belongs_type'] = 2;
        $data['obj_belongs_id'] = $company_id;
        $data['obj_belongs_name'] = $company['company_name'];
		$data['source_obj_type'] = 2;
		$data['source_obj_id'] = $_COOKIE['accountid'];
		$data['source_obj_name'] = $companyaccount['user_name'];
		$data['tel'] = $companyaccount['mobile_phone']?$companyaccount['mobile_phone']:$companyaccount['link_tel'];
		$data['create_time'] = time();
		$data['product'] = 'pc';
		$data['product_type'] = base_lib_BaseUtils::GetBrowser();
		$data['status'] = '0';
		
		$res = $base_service_report_report->addreport($data, $data['type'], $data['way'], $data['status']);
		if ($res) {
            $json['status'] = true;
			$json['msg'] = '提交成功，工作人员会在1个工作日内处理';
		} else {
            $json['status'] = false;
			$json['msg'] = '申诉提交失败';
		}
		echo json_encode($json);
	}
	
	//判断
	/**
	 *  删除信息
	 */
	public function pageDelete($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$service_message = new base_service_message_messagecompany();
		$operate = base_lib_BaseUtils::getStr($pathdata['operate'], 'string', 'single');
		$messageid = base_lib_BaseUtils::getStr($pathdata['messageid'], 'int', null);
		if ($operate == 'all') {
			//　全部删除
			$result = $service_message->deleteMessage($this->_userid);
			if ($result === false) {
				$json['error'] = '删除失败';
			} else {
				$json['success'] = '删除成功';
			}
			echo json_encode($json);
			
			return;
		} else {
			// 单个删除
			if (!empty($messageid)) {
				$result = $service_message->setIsEffect($messageid, $this->_userid, '0');
				if ($result === false) {
					$json['error'] = '删除失败';
				} else {
					$json['success'] = '删除成功';
				}
				echo json_encode($json);
				
				return;
			}
		}
		
	}
	
}

?>