<?php
/**
 * 现场招聘会邀请求职者
 * @ClassName controller_fairinvite 
 * @Desc todo(这里用一句话描述这个类的作用) 
 * @author liuchang@huibo.com
 * @date 2013-11-14 下午04:25:17 
 *
 */
class controller_fairinvite extends components_cbasepage{
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * 邀请到现场
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	function pageFairInvite($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$service_company = new base_service_company_company();
		$current_company = $service_company->getCompany($this->_userid, true, 'company_id,company_name,linkman,link_tel');
		if (empty($current_company)) {
			return;
		}
		$resume_id = base_lib_BaseUtils::getStr($path_data['resumeid'],'int',0);
		$resume_ids = base_lib_BaseUtils::getStr($path_data['resumeids'],'array',null);
		$resume_ids = base_lib_BaseUtils::getIntArrayOrString($resume_ids);
		if ($resume_id != 0) {
			$this->_aParams['resume_id'] = $resume_id;
			$this->_aParams['ta'] = 'TA';
		}elseif (!base_lib_BaseUtils::nullOrEmpty($resume_ids) && count($resume_ids>0)){
//			$resumeids_arr = explode(',', $resume_ids);
//			if (count($resumeids_arr) == 0) {
//				return;
//			}
			$this->_aParams['resume_ids'] = implode(',',$resume_ids);
			$this->_aParams['ta'] = 'TA们';
		}else {
			return;
		}
		//获取单位所有开通的场次
		$service_company_service_scene = new base_service_company_fair_comfairservicescene();
		$service_company_service_scene_state = new base_service_company_fair_comfairservicescenestate();
		$all_service_sceneid = $service_company_service_scene->getAllComFairServiceScene($current_company['company_id'], $service_company_service_scene_state->started, 'scene_id');
		$all_sceneid_arr = $all_service_sceneid->items;
		//获取所有未召开的现场招聘会场次
		$service_fair = new base_service_company_fair_fairscene();
		$service_fairscenestate = new base_service_company_fair_fairscenestate();
		$all_fair_scene = $service_fair->getFairSceneListByIDs(null, $service_fairscenestate->enable, date('Y-m-d H:i:s',time()), 'scene_id,date,subject');
		$all_fair_scene_arr = $all_fair_scene->items;
		//获取单位所有开通的未召开的场次
		$scene_json = '';
		$has_default_select = false;
		foreach ($all_fair_scene_arr as $item=>$value) {
//			$is_join = false;
			foreach ($all_sceneid_arr as $it=>$val) {
				if ($value['scene_id'] == $val['scene_id']) {
//					$is_join = true;
					$thedate = date("Y年m月d日", strtotime($value['date']));
					$scene_json = $scene_json."{id:\"{$value['scene_id']}\",name:\"{$thedate}  &quot;{$value['subject']}&quot;\"},";
					if ($has_default_select == false) {
						$has_default_select = true;
						$this->_aParams['select_scene'] = $value['scene_id'];
					}
					break;
				}
			}
//			if ($is_join == false) {
//				unset($all_fair_scene_arr[$item]);
//			}
		}
		//如果单位未开通场次
		if (base_lib_BaseUtils::nullOrEmpty($scene_json)){
			foreach ($all_fair_scene_arr as $item=>$value) {
				$is_join = false;
				foreach ($all_sceneid_arr as $it=>$val){
					if ($value['scene_id'] == $val['scene_id']) {
						$is_join = true;
						break;
					}
				}
				if ($is_join == false) {
					$thedate = date("Y年m月d日", strtotime($value['date']));
					$scene_json = $scene_json."{id:\"{$value['scene_id']}\",name:\"{$thedate}  &quot;{$value['subject']}&quot;\"},";
				}
			}
			$scene_json = substr($scene_json, 0, strlen($scene_json)-1);
			$this->_aParams['scene_json'] = "[$scene_json]";
			$this->_aParams['current_company'] = $current_company;
			return $this->render('fair/fairapply.html', $this->_aParams);
		}
		$scene_json = substr($scene_json, 0, strlen($scene_json)-1);
		$this->_aParams['scene_json'] = "[$scene_json]";
		$obj = base_lib_BaseUtils::getStr($path_data['obj'],'string','');
		$callback = base_lib_BaseUtils::getStr($path_data['callback'],'string','');
		$this->_aParams['obj'] = $obj;
		$this->_aParams['callback'] = $callback;
		return $this->render('fair/inviteonline.html', $this->_aParams);
	}
	
	/**
	 * 单个现场招聘会会前邀请
	 * Enter description here ...
	 * @param $inPath
	 */
	function pageDoOnlineInvite($inPath){
		$service_company = new base_service_company_company();
		$company_info = $service_company->getCompany($this->_userid,1,'company_id');
		if (empty($company_info)) {
			$json['error'] = '邀请失败';
			echo json_encode($json);
			return;
		}
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$invite['company_id'] = $company_info['company_id'];
		$invite['scene_id'] = base_lib_BaseUtils::getStr($path_data['hddsceneid'],'int',0);
		if ($invite['scene_id'] == 0) {
			$json['error'] = '邀请失败';
			echo json_encode($json);
			return;
		}
		$service_scene = new base_service_company_fair_fairscene();
		$service_fairscenestate = new base_service_company_fair_fairscenestate();
		$scene = $service_scene->getFairscene($invite['scene_id'], $service_fairscenestate->enable, 'date,begin_time,end_time');
		if (empty($scene)) {
			$json['error'] = '邀请失败';
			echo json_encode($json);
			return;
		}
		if (strtotime($scene['begin_time'])<=time() && strtotime($scene['end_time'])>=time()) {
			$json['error'] = '招聘会已经开始，请到已入场求职者列表中邀请';
			echo json_encode($json);
			return;
		}
		if (strtotime($scene['end_time'])<time()) {
			$json['error'] = '招聘会已经结束';
			echo json_encode($json);
			return;
		}
		//根据场次ID获取公司现场服务场次
		$service_company_service_scene = new base_service_company_fair_comfairservicescene();
		$service_company_service_scene_state = new base_service_company_fair_comfairservicescenestate();
		$effect_company_scene = $service_company_service_scene->getComFairServiceScene($company_info['company_id'],$invite['scene_id'],$service_company_service_scene_state->started,null,'scene_id');
		if (empty($effect_company_scene)) {
			return;
		}
		$validator = new base_lib_Validator();
		$invite['station'] = $validator->getStr($path_data['txtFairJobName'],1,32,'请输入1-32位职位名称',false);
		if($validator->has_err){
			echo $validator->toJsonWithHtml();
			exit;
    	}
		$invite['content'] = null;
		$invite['scene_date'] = $scene['date'];
		$invite['re_content'] = null;
		$resumeid = base_lib_BaseUtils::getStr($path_data['hddinviteresumeid'],'int',0);
		$resumeids = base_lib_BaseUtils::getStr($path_data['hddinviteresumeids'],'string','');
		if ($resumeid != 0) {
			$invite['resume_id'] = $resumeid;
			$this->__inviteSingle($invite);
		}elseif (!base_lib_BaseUtils::nullOrEmpty($resumeids)){
			$resumeids_arr = explode(',', $resumeids);
			if (count($resumeids_arr) == 0) {
				$json['error'] = '邀请失败';
				echo json_encode($json);
				return;
			}
			$this->__inviteMulti($invite,$resumeids_arr);
		}else {
			$json['error'] = '邀请失败';
			echo json_encode($json);
			return;
		}
	}
	
	/**
	 * 单个邀请到现场
	 * Enter description here ...
	 * @param unknown_type $company_info
	 * @param unknown_type $invite
	 * @param unknown_type $path_data
	 */
	private function __inviteSingle($invite){
		$service_resume = new base_service_person_resume_resume();
		$resume = $service_resume->getResume($invite['resume_id'], 'person_id');
		if (empty($resume)) {
			$json['error'] = '邀请失败';
			echo json_encode($json);
			return;
		}
		$invite['person_id'] = $resume['person_id'];
		$service_sceneinvite = new base_service_company_fair_jobinvitescene();
		$result = $service_sceneinvite->initiativeInvite($invite);
		if ($result === 3) {
			$json['success'] = '邀请成功';
			$json['issingle'] = 'issingle';
			$json['resumeid'] = $invite['resume_id'];
			echo json_encode($json);
			//TODO发送短信
//			$sms_item_arr = array();
//			$sms_item_arr['resume_name'] = $person['user_name'];
//			$sms_item_arr['company_name'] = $company['company_name'];
//			$sms_item_arr['invite_time'] = $invite['audition_time'];
//			$sms_item_arr['invite_station'] = $invite['station'];
//			$sms_content = base_lib_TemplateUtil::get_content('../config/company/ApplySmsContent.template', $sms_item_arr);
//			base_lib_SMS::send($mobile, $sms_content);
			return;
		}
		$des = '';
		switch ($result) {
			case 1:
				$des = '邀请失败';
				break;
			case 2:
				$des = '邀请数量已满';
				break;
			default:
				$des = '邀请失败';
				break;
		}
		$json['error'] = $des;
		echo json_encode($json);
		return;
	}
	
	/**
	 * 批量邀请到现场
	 * Enter description here ...
	 * @param unknown_type $invite
	 * @param unknown_type $resumeids_arr
	 */
	private function __inviteMulti($invite,$resumeids_arr){
		$service_resume = new base_service_person_resume_resume();
		$resume = $service_resume->getResumeListByIDs(implode(',', $resumeids_arr), 'resume_id,person_id','1');
		if (empty($resume) || count($resume->items) == 0) {
			$json['error'] = '邀请失败';
			echo json_encode($json);
			return;
		}
		$service_sceneinvite = new base_service_company_fair_jobinvitescene();
		$success_count = 0;
		$successid_arr = array();
		foreach ($resume->items as $item=>$value){
			$invite['resume_id'] = $value['resume_id'];
			$invite['person_id'] = $value['person_id'];
			$result = $service_sceneinvite->initiativeInvite($invite);
			if ($result === 3) {
				$success_count += 1;
				array_push($successid_arr, $value['resume_id']);
							//TODO发送短信
//			$sms_item_arr = array();
//			$sms_item_arr['resume_name'] = $person['user_name'];
//			$sms_item_arr['company_name'] = $company['company_name'];
//			$sms_item_arr['invite_time'] = $invite['audition_time'];
//			$sms_item_arr['invite_station'] = $invite['station'];
//			$sms_content = base_lib_TemplateUtil::get_content('../config/company/ApplySmsContent.template', $sms_item_arr);
//			base_lib_SMS::send($mobile, $sms_content);
			}
			if ($result === 2) {
				$json['error'] = '邀请数量已满';
				echo json_encode($json);
				return;
			}
		}
		if ($success_count != 0) {
			$json['success'] = '邀请成功';
			$json['resumeids'] = $successid_arr;
			echo json_encode($json);
			return;
		}else {
			$json['error'] = '邀请失败';
			echo json_encode($json);
			return;
		}
	}
	
	/**
	 * 邀请到展位弹窗
	 * Enter description here ...
	 * @param $inPath
	 */
	public function pageFairInviteLive($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$service_company = new base_service_company_company();
		$current_company = $service_company->getCompany($this->_userid, true, 'company_id,company_name');
		if (empty($current_company)) {
			return;
		}
		$this->_aParams['scene_id'] = base_lib_BaseUtils::getStr($path_data['sceneid'],'int',0);
		if ($this->_aParams['scene_id'] === 0) {
			return;
		}
		$content = "[uname]{$current_company['company_name']}邀请您[booth_ids]参加面试，展位地址请咨询现场工作人员！";
		$resume_id = base_lib_BaseUtils::getStr($path_data['resumeid'],'int',0);
		$resume_ids = base_lib_BaseUtils::getStr($path_data['resumeids'],'string','');
		if ($resume_id != 0) {
			$this->_aParams['resume_id'] = $resume_id;
			$the_single_username = base_lib_BaseUtils::getStr($path_data['username'],'string','');
			if (!base_lib_BaseUtils::nullOrEmpty($the_single_username)) {
				$content = str_replace('[uname]', "{$the_single_username}，", $content);
			}else {
				$content = str_replace('[uname]', '', $content);
			}
		}elseif (!base_lib_BaseUtils::nullOrEmpty($resume_ids)){
			$resumeids_arr = explode(',', $resume_ids);
			if (count($resumeids_arr) == 0) {
				return;
			}
			$this->_aParams['resume_ids'] = $resume_ids;
			$content = str_replace('[uname]', '', $content);
		}else {
			return;
		}
		//根据场次ID获取单位有效场次
		$service_company_service_scene = new base_service_company_fair_comfairservicescene();
		$service_company_service_scene_state = new base_service_company_fair_comfairservicescenestate();
		$effect_company_scene = $service_company_service_scene->getComFairServiceScene($current_company['company_id'],$this->_aParams['scene_id'],$service_company_service_scene_state->started,null,'scene_id,booth_ids');
		if (empty($effect_company_scene)) {
			return;
		}
		if (!empty($effect_company_scene['booth_ids'])) {
			$this->_aParams['booth_ids'] = $effect_company_scene['booth_ids'];
			$content = str_replace('[booth_ids]', "到{$effect_company_scene['booth_ids']}展位", $content);
		}else {
			$content = str_replace('[booth_ids]', '', $content);
		}
		$this->_aParams['content'] = $content;
		//根据场次ID获取招聘会
		$service_fair = new base_service_company_fair_fairscene();
		$service_fairscenestate = new base_service_company_fair_fairscenestate();
		$fair_scene = $service_fair->getFairscene($effect_company_scene['scene_id'], $service_fairscenestate->enable, 'scene_id,begin_time,end_time');
		if (empty($fair_scene)) {
			return;
		}
		//招聘会已经结束或未开始
		if (strtotime($fair_scene['begin_time'])>time() || strtotime($fair_scene['end_time'])<time()) {
			return;
		}
		$obj = base_lib_BaseUtils::getStr($path_data['obj'],'string','');
		$callback = base_lib_BaseUtils::getStr($path_data['callback'],'string','');
		$this->_aParams['obj'] = $obj;
		$this->_aParams['callback'] = $callback;
		return $this->render('fair/invitelive.html', $this->_aParams);
	}
	
	/**
	 * 邀请到展位
	 * Enter description here ...
	 * @param $inPath
	 */
	public function pageDoLiveInvite($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$service_company = new base_service_company_company();
		$current_company = $service_company->getCompany($this->_userid, true, 'company_id');
		if (empty($current_company)) {
			$json['error'] = '邀请失败';
			echo json_encode($json);
			return;
		}
		$liveinvite['company_id'] = $current_company['company_id'];
		$liveinvite['scene_id'] = base_lib_BaseUtils::getStr($path_data['hddinvitelivesceneid'],'int',0);
		if ($liveinvite['scene_id'] === 0) {
			$json['error'] = '邀请失败';
			echo json_encode($json);
			return;
		}
		$service_scene = new base_service_company_fair_fairscene();
		$service_fairscenestate = new base_service_company_fair_fairscenestate();
		//判断场次开始结束时间
		$scene = $service_scene->getFairscene($liveinvite['scene_id'], $service_fairscenestate->enable, 'begin_time,end_time');
		if (empty($scene)) {
			$json['error'] = '邀请失败';
			echo json_encode($json);
			return;
		}
		if (strtotime($scene['begin_time'])>time()) {
			$json['error'] = '招聘会还未召开';
			echo json_encode($json);
			return;
		}
		if (strtotime($scene['end_time'])<time()) {
			$json['error'] = '招聘会已经结束';
			echo json_encode($json);
			return;
		}
		//根据场次ID获取公司现场服务场次
		$service_company_service_scene = new base_service_company_fair_comfairservicescene();
		$service_company_service_scene_state = new base_service_company_fair_comfairservicescenestate();
		$effect_company_scene = $service_company_service_scene->getComFairServiceScene($current_company['company_id'],$liveinvite['scene_id'],$service_company_service_scene_state->started,null,'scene_id');
		if (empty($effect_company_scene)) {
			return;
		}
		$boothids = base_lib_BaseUtils::getStr($path_data['txtboothids'],'string','');
		if(base_lib_BaseUtils::nullOrEmpty($boothids)){
			$json['error'] = '请输入您的展位号';
			echo json_encode($json);
			return;
    	}
    	$validator = new base_lib_Validator();
    	$liveinvite['content'] = $validator->getStr($path_data['txtcontent'],1,50,'请输入1-50位短信内容',false);
		if($validator->has_err){
			echo $validator->toJsonWithHtml();
			exit;
    	}
    	$liveinvite['create_time'] = date('Y-m-d H:i:s',time());
		$resumeid = base_lib_BaseUtils::getStr($path_data['hddinviteliveresumeid'],'int',0);
		$resumeids = base_lib_BaseUtils::getStr($path_data['hddinviteliveresumeids'],'array',null);
		$resumeids = base_lib_BaseUtils::getIntArrayOrString($resumeids);
		if ($resumeid != 0) {
			$liveinvite['resume_id'] = $resumeid;
			$this->__inviteLiveSingle($liveinvite);
		}elseif (!base_lib_BaseUtils::nullOrEmpty($resumeids)  && count($resumeids)>0){
			$resumeids_arr = $resumeids;
			if (count($resumeids_arr) == 0) {
				$json['error'] = '邀请失败';
				echo json_encode($json);
				return;
			}
			$this->__inviteLiveMulti($liveinvite,$resumeids_arr);
		}else {
			$json['error'] = '邀请失败';
			echo json_encode($json);
			return;
		}
	}
	
	/**
	 * 单个邀请到展位
	 * Enter description here ...
	 * @param unknown_type $company_info
	 * @param unknown_type $invite
	 * @param unknown_type $path_data
	 */
	private function __inviteLiveSingle($liveinvite){
		$service_resume = new base_service_person_resume_resume();
		$resume = $service_resume->getResume($liveinvite['resume_id'], 'person_id');
		if (empty($resume)) {
			$json['error'] = '邀请失败';
			echo json_encode($json);
			return;
		}
		$liveinvite['person_id'] = $resume['person_id'];
		$service_invitesms = new base_service_company_fair_jobinvitesms();
		$result = $service_invitesms->addInvite($liveinvite);
		if ($result === true) {
			$json['success'] = '邀请成功';
			$json['issingle'] = 'issingle';
			$json['resumeid'] = $liveinvite['resume_id'];
			echo json_encode($json);
			//TODO发送短信
//			$sms_item_arr = array();
//			$sms_item_arr['resume_name'] = $person['user_name'];
//			$sms_item_arr['company_name'] = $company['company_name'];
//			$sms_item_arr['invite_time'] = $invite['audition_time'];
//			$sms_item_arr['invite_station'] = $invite['station'];
//			$sms_content = base_lib_TemplateUtil::get_content('../config/company/ApplySmsContent.template', $sms_item_arr);
//			base_lib_SMS::send($mobile, $sms_content);
			return;
		}
		$json['error'] = '邀请失败';
		echo json_encode($json);
		return;
	}
	
	/**
	 * 批量邀请到展位
	 * Enter description here ...
	 * @param unknown_type $liveinvite
	 * @param unknown_type $resumeids_arr
	 */
	private function __inviteLiveMulti($liveinvite,$resumeids_arr){
		$service_resume = new base_service_person_resume_resume();
		$resume = $service_resume->getResumeListByIDs(implode(',', $resumeids_arr), 'resume_id,person_id','1');
		if (empty($resume) || count($resume->items) == 0) {
			$json['error'] = '邀请失败';
			echo json_encode($json);
			return;
		}
		$service_invitesms = new base_service_company_fair_jobinvitesms();
		$success_count = 0;
		$successid_arr = array();
		foreach ($resume->items as $item=>$value){
			$liveinvite['resume_id'] = $value['resume_id'];
			$liveinvite['person_id'] = $value['person_id'];
			$result = $service_invitesms->addInvite($liveinvite);
			if ($result === true) {
				$success_count += 1;
				array_push($successid_arr, $value['resume_id']);
							//TODO发送短信
//			$sms_item_arr = array();
//			$sms_item_arr['resume_name'] = $person['user_name'];
//			$sms_item_arr['company_name'] = $company['company_name'];
//			$sms_item_arr['invite_time'] = $invite['audition_time'];
//			$sms_item_arr['invite_station'] = $invite['station'];
//			$sms_content = base_lib_TemplateUtil::get_content('../config/company/ApplySmsContent.template', $sms_item_arr);
//			base_lib_SMS::send($mobile, $sms_content);
			}
		}
		if ($success_count != 0) {
			$json['success'] = '邀请成功';
			$json['resumeids'] = $successid_arr;
			echo json_encode($json);
			return;
		}else {
			$json['error'] = '邀请失败';
			echo json_encode($json);
			return;
		}
	}
	
	/**
     * 获取建议搜索现场职位
     * Enter description here ...
     * @param unknown_type $inPath
     */
    public function pageGetSuggestJobs($inPath){
    	$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
    	$scene_id = base_lib_BaseUtils::getStr($path_data['scene_id'],'int',0);
    	$service_fairjob = new base_service_company_fair_fairjob();
    	$job_list = $service_fairjob->getFairJobs($company_id, $scene_id, 'job_id,station');
    	$job_json = array();
    	if (!empty($job_list) && count($job_list->items) > 0) {
    		foreach ($job_list->items as $item){
    			$job['jobname'] = $item['station'];
    			$job['id'] = $item['job_id'];
    			array_push($job_json, $job);
    		}
    	}
    	echo json_encode($job_json);
    	return;
    }
}

?>