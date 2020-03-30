<?php
/**
 * @Desc:	hr会员介绍页
 * @Author: zhangfangjun@huibo.com
 * @Date:   2015-12-10 14:00:45
 * @Last Modified by:   zhangfangjun - Administrator
 * @Last Modified time: 2015-12-10 15:22:05
 * @Copyright (c) http://www.huibo.com All rights reserved.:
 */
class controller_hrdesc extends components_cbasepage {
	
	function __construct() {
		parent::__construct(false);
		session_start();
	}

	function pageIndex($inPath){
		if ($this->isLogin() && $this->_usertype == 'c') {	
			$companyStateService = new base_service_company_comstate();
			$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
			$hrManager    = $this->GetHRManager($companyState["net_heap_id"]);

			$xml = SXML::load('../config/config.xml');
	        $tel_head = "023-61627888";
	        if (!is_null($xml)) {
	            $tel_head = $xml->TechniquePhone;
	        }
	        $easy_tel_head = str_replace("-", "", $tel_head);
			$this->_aParams['tel_head']      	= $tel_head;
			$this->_aParams['easy_tel_head'] 	= $easy_tel_head;
			$this->_aParams['hrManager'] 		= $hrManager;
		}
		return $this->render('hrmember/hrdesc.html',$this->_aParams);
	}
	

	private function GetHRManager($heap_id){
		$companyHeapService = new base_service_company_netheap();
		$companyHeap = $companyHeapService->GetNetHeapByID($heap_id, "own_man");
		$userInfor = null;
		if(is_null($companyHeap) || !isset($companyHeap["own_man"])){
			return $userInfor;
		}
		$userService=new base_service_crm_user();
		$userInfor = $userService->GetUsers($companyHeap['own_man'], "user_name,head_photo_url,tel,mobile,qq");
		return $userInfor;
	}
}