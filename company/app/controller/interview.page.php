<?php
/**
 * @copyright 2002-2013 www.huibo.com
 * @name 要求面试
 * @author fuzy
 * @version 2013-7-19 上午11:20:56
*/
class controller_interview extends components_cbasepage {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * 单个面试
	 */
	function pageSingle($inPath) {
		return $this->render('resume/interview/many.html', $this->_aParams);
	}
	
	/**
	 * 多个面试
	 */
	function pageMany($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$apply_ids = base_lib_BaseUtils::getStr($path_data['applyID'],'array',null);
		$apply_ids = base_lib_BaseUtils::getIntArrayOrString($apply_ids);
		//$apply_ids = explode(',',$path_data['applyID']);
		if(empty($apply_ids)){
			echo json_encode(array('error'=>'请选择你要邀请的简历'));
			return ;
		}
		
		$job_apply = new base_service_company_resume_apply();
		$resume = new base_service_person_resume_resume();
		$person = new base_service_person_person();
		$apply_list = array();
		
		foreach ($apply_ids as $apply_id){
			$applys = array();
			$apply = $job_apply->getApply($apply_id, 'apply_id,person_id,resume_id,station,company_id,job_id');
			//简历信息
			$resume_info = $resume->getResume($apply['resume_id'], 'resume_id,resume_name,is_chinese_resume');
			//用户信息
			$person_info = $person->getPerson($apply['person_id'], 'person_id,user_name,user_name_en');
			
			//姓名
			if(is_null($resume_info['is_chinese_resume'])||$resume_info['is_chinese_resume']==1){
				$applys['user_name'] = $person_info['user_name'];
			}else {
				$applys['user_name'] = $person_info['user_name_en'];
			}
			$applys['apply_id'] = $apply['apply_id'];
			$applys['station'] = $apply['station'];
			$applys['job_id'] = $apply['job_id'];
			
			array_push($apply_list, $applys);
		}
		$this->_aParams['apply_list'] = $apply_list;
		
		$option_date = '';
		for($i = 0; $i < 7; $i++) {
			$date = base_lib_TimeUtil::time_add_day($this->now('Y-m-d'), $i);
			$option_date.='<option value="'.date('Y-m-d',strtotime($date)).'">'.date('Y-m-d',strtotime($date)).'['. $this->getWeek(base_lib_TimeUtil::date_of_week($date)).']</option>';
		}
		$this->_aParams['option_date'] = $option_date;
		
		//模板
		$this->_aParams['template_list'] = $this->getTemplateList('template_id,name');
		return $this->render('resume/interview/many.html', $this->_aParams);
	}
	
	/**
	 *@desc方法说明
	 *@param type $param 参数说明
	 *@return
	*/
	private function getWeek($weekID) {
		switch ($weekID) {
			case 0 :
				return '星期天';
			case 1 :
				return '星期一';
			case 2 :
				return '星期二';
			case 3 :
				return '星期三';
			case 4 :
				return '星期四';
			case 5 :
				return '星期五';
			case 6 :
				return '星期六';
			default:
				return '';
		}
	}
	
	/** 
	 * @desc 获取模板列表
	 * @param string $field
	 * @return array
	 */
	private function getTemplateList($field){
		$company_template = new base_service_company_template();
		$template = $company_template->getTemplateList($this->_userid,$field);
		return $template->items;
	}
	
	/**
	 *@desc 获取模板
	 *@param int $inPath 模板编号
	 *@return
	*/
	public function pageGetTemplate($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$template_id = base_lib_BaseUtils::getStr($path_data['template_id'],'int',0);
		$company_template = new base_service_company_template();
		$template = $company_template->getTemplate($template_id,'template_id,name,address,link_man,link_tel,remark');
		if(!$template){
			echo json_encode(array('error'=>'选择模板失败'));
			return ;
		}
		echo json_encode($template);
	}
	
	/**
	 *@desc管理模板
	 *@return
	*/
	public function pageManageTemplate($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$obj = base_lib_BaseUtils::getStr($path_data['obj'], 'string', '');
		$callback = base_lib_BaseUtils::getStr($path_data['callback'], 'string', '');
		$this->_aParams['obj'] = $obj;
		$this->_aParams['callback'] = $callback;
		$this->_aParams['template_list'] = $this->getTemplateList('template_id,name');
		return $this->render('resume/interview/managetemplate.html', $this->_aParams);
	}
	
	/**
	 *@desc保存模板
	 *@return
	*/
	public function pageSaveTemplate($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$template_id = base_lib_BaseUtils::getStr($path_data['hidTemplateID'],'int',0);
		$val = new base_lib_Validator();
		
		$name = $val->getNotNull($path_data['txtName'],'请填写模板名称');
		$name = $val->getStr($path_data['txtName'],1,64,'模板名称不能超过64字');
		$address = $val->getNotNull($path_data['txtAddress'],'请填写面试地点');
		$address = $val->getStr($path_data['txtAddress'],1,60,'面试地点不能超过60字');
		$link_man = $val->getNotNull($path_data['txtLinkMan'],'请填写联系人');
		$link_man = $val->getStr($path_data['txtLinkMan'],1,15,'联系人不能超过15字');
		$link_tel = $val->getNotNull($path_data['txtLinkTel'],'请填写联系电话');
		$link_tel = $val->getTel($path_data['txtLinkTel'],'联系电话不正确',false);
		$remark = $val->getStr($path_data['txtRemark'],0,200,'其他信息不能超过200个字',true);


		if($val->has_err)
		{
			echo $val->toJsonWithHtml();
			return ;
		}
		$is_add = '';
		$item = array('name'=>$name,'address'=>$address,'link_man'=>$link_man,'link_tel'=>$link_tel,'remark'=>$remark);
		
		$company_template = new base_service_company_template();
		if(is_null($template_id)||$template_id==0){
			//添加模板
			$item['company_id'] = $this->_userid;
			$template_id = $company_template->addTemplate($item);
			$is_add = '1';
			if(!$template_id){
				echo json_encode(array('error'=>'新增模板失败'));
				return ;
			}
		}else {
			//修改模板
			$is_add = '0';
			$condition = array('template_id'=>$template_id);
			$template = $company_template->updateTemplate($condition,$item);
			if($template===false){
				echo json_encode(array('error'=>'修改模板失败'));
				return ;
			}
		}
		echo json_encode(array('is_add'=>$is_add,'template_id'=>$template_id,'name'=>$name));
	}
	
	/**
	 *@desc删除模板
	 *@param int $param 模板编号
	 *@return
	*/
	public function pageDeleteTemplate($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$template_id = base_lib_BaseUtils::getStr($path_data['template_id'],'int',0);
		$company_template = new base_service_company_template();
		$template = $company_template->deleteTemplate($template_id);
		if(!$template){
			echo json_encode(array('error'=>'删除模板失败'));
			return ;
		}
		echo json_encode(array('success'=>'1'));
	}
	
	/**
	 *@desc 发送邀请（批量）
	 *@return
	*/
	public function pageSendInterviewMany($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$apply_ids = base_lib_BaseUtils::getStr($path_data['hidApplyID'],'array',null);
		$apply_ids = base_lib_BaseUtils::getIntArrayOrString($apply_ids);
		//$apply_ids = $path_data['hidApplyID'];
		$job_apply = new base_service_company_resume_apply();
		$item = array();
		$val = new base_lib_Validator();
		$address = $val->getStr($path_data['txtAddress'],1,20,'面试地点长度不正确');
		$link_man = $val->getStr($path_data['txtLinkMan'],1,20,'联系人长度不正确');
		$link_tel = $val->getStr($path_data['txtLinkTel'],1,20,'联系电话长度不正确');
		$remark = $val->getStr($path_data['txtRemark'],'min',200,'长度不正确');
		$apply_status = new base_service_company_resume_applystatus();
		if($val->has_err)
		{
			echo $val->toJsonWithHtml();
			return ;
		}
		$item = array('re_status'=>$apply_status->interview,'invite_address'=>$address,'invite_link_man'=>$link_man,'invite_link_tel'=>$link_tel,'invite_remark'=>$remark,'re_time'=>$this->now());
		foreach ($apply_ids as $apply_id) {
			$job_id = base_lib_BaseUtils::getStr($path_data['hidJobID'.$apply_id],'int',0);
			$station = $path_data['txtStation'.$apply_id];
			$date = $val->getNotNull($path_data['selDate'.$apply_id],'请选择面试日期');
			$time = $val->getNotNull($path_data['selTime'.$apply_id],'请选择面试时间');
			$user_name = $path_data['hidUserName'.$apply_id];
			
			$item['invite_job_id'] = $job_id;
			$item['invite_station'] = $station;
			$item['invite_time'] = $date.' '.$time.':00';
			
			if($val->has_err)
			{
				echo $val->toJsonWithHtml();
				return ;
			}
			$job_apply->inviteApply($apply_id, $item,$user_name,$this->_username);
		}
		
		echo json_encode(array('success'=>'1'));
	}
	
}
?>