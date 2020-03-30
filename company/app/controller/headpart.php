<?php
/**
 * 单位头部获取数据
 */
$base            = new base_components_basepage();
$service_company = new base_service_company_company();
$com_info = $service_company->getCompany($base->_userid, 1, 'company_name,company_flag,company_shortname,audit_state,is_effect,is_audit,recruit_type');

/* 获取公司名称和路径 */
$companyInfo['company_name'] = empty($com_info['company_shortname']) ? $com_info['company_name'] : $com_info['company_shortname'];
$companyInfo['company_name'] = base_lib_BaseUtils::cutstr($companyInfo['company_name'], 12, 'utf-8', '', '...');
$companyInfo['company_url'] = base_lib_Rewrite::company($base->_userid, $com_info['company_flag']);

/* 升级维护公告 */
$bulletin_service = new base_service_bulletin_bulletin();
$newBulletin = $bulletin_service->getNewBulletin7Day(1);
$newB_number = 0;
if(!empty($newBulletin)){
	foreach($newBulletin as $item){
		if(!base_lib_BaseUtils::getCookie("newBulletinidByMsg{$item['id']}")){
			$newB_number+=1;
		}
	}
}

/* 获取未读的消息 */
$service_message = new base_service_message_messagecompany();
$count = $service_message->getNotReadMsgCount($base->_userid);
$count = $count+$newB_number;
$companyInfo['messgecount'] = $count;

/* 获取未读的留言 */
$company_resources = base_service_company_resources_resources::getInstance($base->_userid);
$guest_service = new base_service_company_companytemplates_companytemplateguestbook();
foreach ($company_resources->all_accounts as $account_id) {
	$gcount += $guest_service->getNotReadCount($account_id);
}
$companyInfo['guestbookcount'] = $gcount;

/* 获取全职未处理简历 */
$companyInfo['full_count'] = "0";

/* 获取兼职未处理简历 */
$apply_service = new base_service_part_company_apply();
$part_count = $apply_service->getApplyCount($base->_userid)["notreply_num"];
$companyInfo['part_count'] = $part_count > 99 ? "99+" : $part_count;

//获取该企业是否是
//企业是否认证
$is_audit = 0;//未认证
if ($com_info['is_effect'] == "1" && $com_info['is_audit'] == '1') {
	if ($com_info['audit_state'] == '1' || $com_info['audit_state'] == '4') {
		$is_audit = 1; //已经认证
	} else {
		$is_audit = 4; //验证待补
	}
	if ($com_info['audit_state'] =='2' || $com_info['audit_state']=='3') {
		$is_audit = 4; //已经认证,但在临时和补办状态中
		
	}
} else if ($com_info['is_effect'] =="1" && $com_info['is_audit'] == '2') {
	$is_audit = 2; //认证中
} else if ($com_info['is_effect'] =="1" && $com_info['is_audit'] == '0') {
	$is_audit = 3;//认证未通过
}

//hr会员,企业是不是招聘中介机构 (显示)
$memberinfo = base_service_company_resources_resources::getInstance($base->_userid);
if(in_array($com_info['recruit_type'], [2,4,9]) || $memberinfo->account_type == 'hr_main') {
	$companyInfo['is_thr'] = 1;
}

$companyInfo['is_audit'] = $is_audit;

/**
 *@desc 获取未读面试评价个数 
 */
 $service_appraise   = new base_service_person_appraise_appraise();
// $num = $service_appraise->getCompanyNotReadNum($base->_userid);
 $num = 0;
 $companyInfo["not_read_appraise_num"] = $num;
//获取未读的面试评价差评数
$companyInfo['not_read_bad_review_num'] = $service_appraise->getCompanyNotReadBadReviewNum($base->_userid);

$this->assign('companyInfo', $companyInfo);

//竞价推广的cookie
$isHotCompanySpread = base_lib_BaseUtils::getCookie('isHotCompanySpread');
$this->assign('isHotCompanySpread', empty($isHotCompanySpread) ? false : true);

//面试管理提示cookie
$audition_tip_msg = base_lib_BaseUtils::getCookie('audition_tip_msg');
$this->assign('audition_tip_msg', $audition_tip_msg);

//是否显示用工管理
$service_tallage_importtask = new base_service_tallage_importtask();
$this->assign('import_task_num', $service_tallage_importtask->hasImportByCompanyId($base->_userid));
?>