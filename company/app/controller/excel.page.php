<?php
class controller_excel extends components_cbasepage {	
    function __construct() {
		parent::__construct();
    }
	/**
	 * 简历excel下载
	*/
	public function pageIndex($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_ids = base_lib_BaseUtils::getStr($pathdata['resumeid'],'array',"");
		$resume_ids = base_lib_BaseUtils::getIntArrayOrString($resume_ids);
	    if(base_lib_BaseUtils::nullOrEmpty($resume_ids) || count($resume_ids)==0) {
	    	
	    	return;
	    }
	    $ids = $resume_ids;
		$apply_ids = base_lib_BaseUtils::getStr($pathdata['applyid'],'array',null);
		$apply_ids = base_lib_BaseUtils::getIntArrayOrString($apply_ids);
		if (!empty($apply_ids)) {
				
			$this->changeApplyReadStatus($applyids);
		}
		$resumeexcelhelper = new company_service_resumeexcelhelper();
		$resumeexcelhelper->buildResumeExcel($ids,$this->_userid);
		ob_end_clean();
		$filename = $resumeexcelhelper->getExcelFileName();
		$showname = "汇博简历（".count($ids)."份）".date('Ymd').".xls";
		base_lib_BaseUtils::download($filename,$showname);
		$resumeexcelhelper->cleanFile();
		exit();
	}  
	/**
		* @desc 当企业下载简历的时候更改简历状态为已读
    */
	private function changeApplyReadStatus($applyids){
	   //更改简历的未读状态
	   if(base_lib_BaseUtils::nullOrEmpty($applyids)){
		   return false;
	   }
	   if(!is_array($applyids)){
		   $applyids = explode(",", $applyids);
	   }
	   $service_apply  = new base_service_company_resume_apply();
	   $result = $service_apply->setRead($applyids,$this->_userid);
	   return $result;
	}
}   
?>