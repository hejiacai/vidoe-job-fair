<?php
/**
 * @copyright 2002-2013 www.huibo.com
 * @name 账户充值
 * @author fuzy
 * @version 2013-8-11 上午11:20:56
*/
class controller_recharge extends components_cbasepage {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}

	function pageIndex($inPath) {
		$this->_aParams['title'] = "账户充值";
		return $this->render('service/index.html', $this->_aParams);
	}
	
	/**
	 *@desc删除收到的简历
	 *@return
	*/
	public function pageDeleteApply($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$apply_ids = base_lib_BaseUtils::getStr($path_data['applyID'],'array',null);
		$apply_ids = base_lib_BaseUtils::getIntArrayOrString($apply_ids);
		//$apply_ids = explode(',',$path_data['applyID']);
		if(empty($apply_ids)){
			echo json_encode(array('error'=>'请选择你要删除的简历'));
			return ;
		}
		
		$job_apply = new base_service_company_resume_apply();
		foreach ($apply_ids as $apply_id){
			$job_apply->deleteApply($this->_userid, $apply_id);
		}
		
		echo json_encode(array('success'=>'1'));
	}
	
}
?>