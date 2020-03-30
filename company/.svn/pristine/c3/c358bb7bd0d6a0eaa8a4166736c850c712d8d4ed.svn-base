<?php
/** 
 * @copyright 2002-2013 www.huibo.com
 * @name 留言反馈
 * @author ZhangYu
 * @date 2013-10-24
 *
*/
class controller_guestbook extends components_cbasepage {
	
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * 留言咨询回复
	 * @param  array $inPath url参数集
	 * @return html          guestbook.html
	 */
	public function pageIndex($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$service_guestbook = new base_service_company_companytemplates_companytemplateguestbook();
		
		$page_index = base_lib_BaseUtils::getStr($pathdata['page'], 'int', 1);
		$is_reply   = base_lib_BaseUtils::getStr($pathdata['isreplay'], 'string', '');  
		$page_size  = base_lib_Constant::PAGE_SIZE;

        
        if($is_reply == 1)
            $this->_aParams['loadActionType'] = 402;
        else if(base_lib_BaseUtils::nullOrEmpty($is_reply))
            $this->_aParams['loadActionType'] = 401;
        else
            $this->_aParams['loadActionType'] = 403;

        if($pathdata['page'])
            $this->_aParams['loadActionType'] = false;
        
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid);
		$guestbooks = $service_guestbook->getGuestbookList($page_size,
			$page_index,
			$company_resources->all_accounts,
			'1',
			$is_reply,
			'guestbook_id,job_id,is_read,content,create_man,create_time,is_replay,reply_content,company_id'
		);

		$count = $guestbooks->totalSize;
		$list  = $guestbooks->items;
		$this->_aParams['hasdata'] = false;
		$this->_aParams['hasdata'] = $count > 0 ? true : false;

	    if (count($list) > 0) {
	    	// 查询所有的留言者编号
	    	$personids = base_lib_BaseUtils::getProperty($list, 'create_man');
	    }

	    $notreadGuestBook = array();

	    $enum_readstate = new base_service_message_messagereadstate();
	    $service_person = new base_service_person_person();
	    $service_resume = new base_service_person_resume_resume();
	   
	    if (count($personids) > 0) {
			$persons = $service_person->getPersons($personids, 'person_id,user_name,small_photo');
			$resumes = $service_resume->getDefaultResumes($personids, 'person_id,resume_id');
	    }

	    // 获取每条留言所咨询的职位
        $job_service = new base_service_company_job_job();
	    $job_ids = array_unique(base_lib_BaseUtils::getProperty($list, 'job_id'));
        $job_list = $job_service->getJobs($job_ids, "job_id,station,job_flag");
        $job_list = base_lib_BaseUtils::array_key_assoc($job_list, "job_id");

        // 获取子账号虚拟企业信息
        $company_service = new base_service_company_company();
        $companys = $company_service->getCompanys($company_resources->all_accounts, "company_id,company_flag,company_name,company_shortname");
        $companys = base_lib_BaseUtils::array_key_assoc($companys, "company_id");

        $persons = base_lib_BaseUtils::array_key_assoc($persons->items, "person_id");
        $resumes = base_lib_BaseUtils::array_key_assoc($resumes->items, "person_id");

		for ($i = 0; $i < count($list); $i += 1) {
			// 记录未读信息
			if ($list[$i]['is_read'] == $enum_readstate->notread) {
				array_push($notreadGuestBook, $list[$i]['guestbook_id']);
			}
			$list[$i]['isreplay'] = $list[$i]['is_replay'] == '1' ? true : false;
			$list[$i]['time'] = base_lib_TimeUtil::to_friend_time($list[$i]['create_time']);

			$person = $persons[$list[$i]['create_man']];
			$resume = $resumes[$list[$i]['create_man']];

			//个人头像
		    $list[$i]['hasHead'] = false;
    		if (!base_lib_BaseUtils::nullOrEmpty($person['small_photo'])) {
    			$avatar = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . "{$person['small_photo']}?r=" . rand(1000, 9999);
				$list[$i]['hasHead']   = true;
				$list[$i]['headphoto'] = $avatar;
			}

			$list[$i]['personname'] = $person['user_name'];
			$list[$i]['resumeid']   = $resume['resume_id'];

			$list[$i]['com_name']   = $companys[$list[$i]['company_id']]['company_shortname'] ? $companys[$list[$i]['company_id']]['company_shortname'] : $companys[$list[$i]['company_id']]['company_name'];
			$list[$i]['com_url']    = base_lib_Rewrite::company($list[$i]['company_id'], $companys[$list[$i]['company_id']]['company_flag']);

			$list[$i]['job_name']   = $job_list[$list[$i]['job_id']]['station'] ? $job_list[$list[$i]['job_id']]['station'] : "";
			$list[$i]['job_url']    = $job_list[$list[$i]['job_id']]['job_flag'] ? base_lib_Rewrite::job($list[$i]['job_id'], $job_list[$list[$i]['job_id']]['job_flag']) : "";
		}	

		$xml = SXML::load('../config/company/company.xml');
		$this->_aParams['allMsgCount']        = $service_guestbook->getGuestBookCount($company_resources->all_accounts, '1', $xml->countMsgStartDate);
		$this->_aParams['allReplyedMsgCount'] = $this->_aParams['allMsgCount'] == 0 ? 0: $service_guestbook->getReplyedCount($this->_userid, $xml->countMsgStartDate);

		$company_service = new base_service_company_company();
		$this->_aParams['company_start_time'] = date("Y年m月d日", strtotime($xml->countMsgStartDate));

		// 设置信息为已读
		if (count($notreadGuestBook) > 0) {
			$service_guestbook->setIsRead($notreadGuestBook, null, $company_resources->all_accounts);
		}

		$xml = SXML::load('../config/config.xml');

		$pager = $this->pageBar($guestbooks->totalSize, $page_size, $page_index, $inPath);

		$this->_aParams['isreply'] = $is_reply;
		$this->_aParams['title']   = "求职者留言 我的消息-{$xml->HuiBoSiteName}";
		$this->_aParams['item']    = $list; //var_dump($list);
		$this->_aParams['pager']   = $pager;     
	    return  $this->render('guestbook.html',$this->_aParams);	 			
	}

	/**
	 * 删除留言
	 * @param  $inPath
	 */
	public function pageDelete($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));	
		$guestid = base_lib_BaseUtils::getStr($pathdata['guestid'], 'int', 0);
		
		$service_feedbackmsgtype = new base_service_message_feedbackmsgtype();
		$service_guestbook       = new base_service_company_companytemplates_companytemplateguestbook();
		if (empty($guestid)) {
			return ;
		}	

		$company_resources = base_service_company_resources_resources::getInstance($this->_userid);
		$result = $service_guestbook->deleteGuestBook($company_resources->all_accounts, $guestid);
		if ($result === false) {
	  		$json['error'] = '删除留言失败';
	    } else {
	  		$json['success'] = '删除留言成功';
		}
	  	
	  	echo json_encode($json);
	   	return ;
	}
   
	/**
	 * 留言回复
	 * @param  $inPath
	 */
   	public function pageReply($inPath) {
       	$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));	
		$guestid = base_lib_BaseUtils::getStr($pathdata['guestid'], 'int', 0);
		$operate = base_lib_BaseUtils::getStr($pathdata['operate'], 'string', '');
   	   	
   	   	$service_guestbook = new base_service_company_companytemplates_companytemplateguestbook();
   	   	$company_resources = base_service_company_resources_resources::getInstance($this->_userid);

   	   	$guest = $service_guestbook->getGuestBook($guestid, $field="guestbook_id,company_id");
   	   	if (!in_array($guest['company_id'], $company_resources->all_accounts)) {
   	   		$json['error'] = '回复留言失败';
   	   		echo json_encode($json);
			return ;
   	   	}

		if ($operate == 'reply') {
			$content = base_lib_BaseUtils::getStr($pathdata['txtReplyContent'], 'string', '');
			$result  = $service_guestbook->replyGuestBook($guest['company_id'], $guestid, 0, $content);
			if ($result !== false) {
				$json['success'] = '回复留言成功';
				$json['id']      = $guestid;
				$json['content'] = $content;
			} else {
				$json['error'] = '回复留言失败';
			}

			echo json_encode($json);
			return ;
		} else {
			$guestbook = $service_guestbook->getGuestBook($guestid, 'guestbook_id,content,create_man');
			if (empty($guestbook)) {
				return ;	
			}

			$service_person = new base_service_person_person();
			$person = $service_person->getPerson($guestbook['create_man'], 'user_name');
			$this->_aParams['personname'] = $person['user_name'];
			$this->_aParams['guestid']    = $guestbook['guestbook_id'];
			$this->_aParams['content']    = $guestbook['content'];
	
			return $this->render('guestbookreply.html',$this->_aParams);
		}
	}
}
?>