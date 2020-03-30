<?php
/**
 * @name 企业会员申请
 * @author ZhangYu
 * @version 2013-7-16 
 */
class controller_vipapply extends components_cbasepage {

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * 
	 * 开启企业会员页面
	 * @param object $inPath
	 */
	public function pageIndex($inPath) {	
		   $service_company = new base_service_load(new base_service_company_company());
	   	   $company = $service_company->getCompany($this->_userid,null,'com_level,start_time,end_time,is_audit,linkman,link_tel');
		   $this->pagedata['isopenmembership'] = false;	
		   $this->pagedata['readydue'] = true;
	   	   if(base_lib_BaseUtils::nullOrEmpty($company['com_level'])||intval($company['com_level'])<=0||base_lib_BaseUtils::nullOrEmpty($company['end_time'])||base_lib_BaseUtils::nullOrEmpty($company['start_time'])) {
	   	   	  $this->pagedata['name'] ='未开通会员';
	       }else {
	       	   // 获取公司的审核状态 并验证
		       if(!empty($company['end_time'])) {
		       	   //会员过期
		       	   $day = base_lib_TimeUtil::time_diff_day($company['end_time'],date('y-m-d H:i:s'));
				   if($day>0) {
				   	  $this->pagedata['name'] ='未开通会员';
				   }else {
 					   $service_comservice = new base_service_company_service_comservice();
 					   $fields ='service_kind,com_level';			   		
					   $comservice = $service_comservice->getComService($this->_userid,$fields);	
 					   $this->pagedata['comservice'] =$comservice; 
 					   $service_level =new base_service_load(new base_service_company_level());
		   			   $level = $service_level->getLevelById($company['com_level']);
		   			   $this->pagedata['levelname'] = $level['com_level_name'];
		   			   $this->pagedata['comlevel'] = $comservice['com_level'];
		   			   $this->pagedata['servicekind'] = $comservice['service_kind'];
		   			   $this->pagedata['isopenmembership'] = true;
		   			   $this->pagedata['readydue'] = false;
				   	   if($day>=-30) { 
				   	   	 	 $this->pagedata['endtime'] =abs($day).'天后将过期，请及时续费';
				   	      } else {
				   	      	 $this->pagedata['endtime'] = date('Y-m-d',strtotime($company['end_time']));
				   	      }
				   	   	  // 获取会员信息	  
			  			  $this->pagedata['resume_down_num']= $service_comservice->getResumeDownloadUpperlimit($this->_userid);
		   	              $this->pagedata['job_release_num']= $service_company->getCompanyCanJobCount($this->_userid);			   	   	  	 
				   }
		       }	       	
	       }
	       $xml = SXML::load('../config/config.xml');
	       $this->pagedata['title'] = "开通会员 我的账户-{$xml->HuiBoSiteName}";
	       $this->pagedata['linkman'] = $company['linkman'];
	       $this->pagedata['linktel'] = $company['link_tel'];
	       return $this->render('service/servicerequest.html',$this->pagedata);
	}
	
	/**
	 * 
	 * vip服务申请
	 * @param object $inPath
	 */
	public function pageApply($inPath) {
		$service_vipapply = new base_service_company_vipapply();		
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$val = new base_lib_Validator();
		$vipapply['company_id'] = $this->_userid;
        $vipapply['vip_type'] = $val->getStr($pathdata['hddVipType'],'1','max','请选择会员服务类型');
        $vipapply['vip_time'] = $val->getStr($pathdata['hddVipTime'],'1','max','请填写会员服务时间');
        $vipapply['Linkman'] = $val->getStr($pathdata['txtLinkMan'],'1','max','请填写联系人');
        $vipapply['link_phone'] = $val->getStr($pathdata['txtLinkTel'],'1','max','请填写联系人电话');
        $vipapply['create_time']  = date('Y-m-d H:i:s');
        $vipapply['is_effect'] = 1;
        $vipapply['has_reply'] = 0;
	    if($val->has_err) {
 	   	 	 echo $val->toJsonWithHtml();
 	   	 	 return;
 	   	}			
		$result = $service_vipapply->insert($vipapply);
		if($result!==false) {
			$service_companystate = new base_service_company_comstate();
			//获取锁定人
			$companyStateService = new base_service_company_comstate();
			$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
			if(!empty($companyState)) {
				$companyHeapService = new base_service_company_netheap();
				$companyHeap = $companyHeapService->GetNetHeapByID($companyState['net_heap_id'], "own_man");
				$toman = $companyHeap['own_man'];		
			}
			if(!base_lib_BaseUtils::nullOrEmpty($toman)) {
				$service_company = new base_service_company_company();
				$company = $service_company->getCompany($this->_userid, '1', 'company_id,company_name,linkman,link_tel');
				$content = "您锁定的单位 您锁定的单位  {$company['company_name']} ({$company['company_id']})提交了服务申请，请尽快联系客户！<br>联系人：{$company['linkman']}({$company['link_tel']})<br>服务类型：{$vipapply['vip_type']}<br>服务期：{$vipapply['vip_time']}个月";
				$this->sendSysMsg($toman,$content,0);			
			}
			$josn['success'] = '会员申请提交成功，我们会及时与您取得联系！';
		}else {	
			$josn['error'] = '服务申请失败!';
		}
		echo json_encode($josn);
		return;
		
	}
}
?>