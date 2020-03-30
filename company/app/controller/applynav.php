<?php
$base = new components_cbasepage();
$company_resources = new base_service_company_resources_resources($base->_userid);
$showStopJobApply = base_lib_BaseUtils::getCookie("showStopJobApply");

$use_job_ids = null;
$service_apply = new base_service_company_resume_apply();
$service_job   = new base_service_company_job_job();

$jobstatus 	   = new base_service_common_jobstatus();
if (!$showStopJobApply) {
	$use_job_ids = $service_job->getJobList($company_resources->all_accounts, '', $jobstatus->pub, "job_id");
	$use_job_ids = base_lib_BaseUtils::getProperty($use_job_ids, "job_id");
}

$apply_status_count = $service_apply->getStatusGroupCount($company_resources->all_accounts, $use_job_ids, 90);
$this->assign('apply_status_count', $apply_status_count->items);

// 获取未处理推荐的列表
$service_recommend = new base_service_company_resume_recommend();
$has_not_deal_recommand = $service_recommend->hasNotDealRecommend($base->_userid);
$this->assign('recommend_red_point', $has_not_deal_recommand && $company_resources->isMember());

//1周内企业未读的人工推荐的简历
$recomend_type = [2,3,4];
$week_time = date("Y-m-d",strtotime("-1 week"));
$recommend_list = $service_recommend->getRecommendResumeV2ByTime($base->_userid,"recommend_id,company_id,recommend_type,is_read",$recomend_type,0,$week_time);
$recommend_not_read_num = count($recommend_list);
//是否关闭人工推荐的简历气泡
$cur_accountid = base_lib_BaseUtils::getCookie('accountid');
$showArtificialRecommendTip_temp = base_lib_BaseUtils::getCookie("showArtificialRecommendTip_".$cur_accountid);
$isShowArtificialRecommendTip = false;
$recommendShowType = 1;
if(!$showArtificialRecommendTip_temp && $recommend_not_read_num > 0){
	$isShowArtificialRecommendTip = true;
	$recommendShowType = 2;
}
$this->assign('recommendShowType', $recommendShowType);
$this->assign('isShowArtificialRecommendTip', $isShowArtificialRecommendTip);

//谁看我的职位是否有新的简历
$visitList = new base_service_company_job_jobvisitdetail();
$hasNew = $visitList->isHasNew($company_resources->all_accounts);

$this->assign('who_see_me_has_new', $hasNew);

//是否参与灰度测试
$this->assign('_is_gray_test_company', $base->is_gray_company);
