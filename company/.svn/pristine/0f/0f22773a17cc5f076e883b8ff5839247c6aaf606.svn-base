<?php
/**
 * @copyright 2002-2013 www.huibo.com
 * @name 企业中心
 * @author fuzy
 * @version 2013-6-27 下午5:19:50
*/
class controller_indexbak extends components_cbasepage {
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * 企业中心测试入口
	 */
	function pageIndex($inPath) {
		$this->_aParams['title']='汇博人才网_招聘中心';
		$this->_aParams['username']=$this->_username;
		$this->_aParams["weekdays"] = array("日","一","二","三","四","五","六");

		$companyService = new base_service_company_company();
		$company = $companyService->getCompany($this->_userid,true,'company_id,company_name,is_audit,
			resume_download_upperlimit,com_level,start_time,end_time,hr_manager,hr_manager_sex,hr_tel');
		$this->_aParams['company_url'] = base_lib_Rewrite::company($this->_userid, $company['company_flag']);
		$companyLevelService = new base_service_company_level();
		$companyLevel = $companyLevelService->getName($company["com_level"]);

		//获取公司首页配置信息
		$configXml = SXML::load("../config/config.xml");
		$this->_aParams["resumeNums"] = $configXml->ShowResumeNum;
 
		//企业资料完善度
		$companyInfoPercent = 0;
		$companyService->getComPercentAndNoComplete($this->_userid, false, $companyInfoPercent); //调用者可以不用&，否则在5.4下报错
		//获取正在招聘的职位数
		$jobService = new base_service_company_job_job();
		$jobStatus = new base_service_common_jobstatus();
		$recruitingJobCount = $jobService->getJobCount($this->_userid,$jobStatus->use);

		$applyJobService = new base_service_company_resume_apply();
		$apply_status = new base_service_company_resume_applystatus();
		/*$stat = $applyJobService->getApplyStat($this->_userid);
		if($stat!==false){
			//今天收到的简历数
			$appliesCount = $stat['no_read_num'];
			//未处理的简历数
			$undealApplies =$stat['no_deal_num'];
		}*/
		$appliesCount = $applyJobService->getTodayApplyResume($this->_userid);
		$undealApplies = $applyJobService->GetNoReadApplyNum($this->_userid);
		
		//最新招聘会
		$fairSceneService = new base_service_company_fair_fairscene();
		//最近三场招聘会
		$lastThreeFairs = $fairSceneService->getLastThreeFairs("scene_id,date,
			predict_extent,predict_jobcount,subject");

		$domain = $this->GetDomainInfor();
		//获取招聘顾问
		$companyStateService = new base_service_company_comstate();
		$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
		$hrManager = $this->GetHRManager($companyState["net_heap_id"]);
		//获取客服员
		//$customerService = $this->GetCustomerService();
		$this->_aParams["hasHRManager"] = false;
		if(!is_null($hrManager)){
			$this->_aParams["hasHRManager"] = true;
			$headPhoto = base_lib_BaseUtils::nullOrEmpty($hrManager["head_photo_url"])?$domain["defaultPhoto"]:$hrManager["head_photo_url"];
			$hrManager["head_photo_url"] = $domain["image"]."/".$domain["photo"]."/".$headPhoto;
			$this->_aParams["hrManager"] = $hrManager;
		}
		//$customerService["photo"] = $domain["image"]."/".$domain["photo"]."/".$domain["defaultPhoto"];
		//$this->_aParams["customerService"] = $customerService;
		//单位状态
		$service_comservice = new base_service_company_service_comservice();
		$comservice = $service_comservice->getComService($this->_userid, 'resume_num,resume_down_num');
		if(!empty($comservice)){
			$ser_resume_num = $comservice['resume_num']=''?0:$comservice['resume_num'];
			$ser_resume_down_num = $comservice['resume_down_num']=''?0:$comservice['resume_down_num'];
			$company["over_resume_down_num"] = $ser_resume_num - $ser_resume_down_num;
		}
		//$comStateService = new base_service_company_changeable();
		//$resumeDownNum = $comStateService->getChangeable($this->_userid, "resume_download_num");
		//$company["resume_download_num"] = isset($resumeDownNum["resume_download_num"])?$resumeDownNum["resume_download_num"]:0;
		$company['is_opened'] ='1';
	  	if(empty($company['com_level'])||intval($company['com_level'])<=0||base_lib_BaseUtils::nullOrEmpty($company['end_time'])||base_lib_BaseUtils::nullOrEmpty($company['start_time'])) {
   	 	   $company['is_opened'] ='0';
        }
        if(!base_lib_BaseUtils::nullOrEmpty($company['end_time'])) {
       	   $day = base_lib_TimeUtil::time_diff_day($company['end_time'],date('y-m-d H:i:s'));
		   if($day>10) {
		   	  $company['is_opened'] ='0';
		   }
        }   
		$this->_aParams["companyid"] = $this->_userid;
		$this->_aParams["companyInfor"] = $company;
		$this->_aParams["companyLevel"] = $companyLevel;
		$this->_aParams["companyPercent"] = $companyInfoPercent;
		$this->_aParams["recruitingJobCount"] = $recruitingJobCount;
		$this->_aParams["appliesCount"] = $appliesCount;
		$this->_aParams["apply_status_no_read"] = $apply_status->no_read;
		$this->_aParams["undealApplies"] = $undealApplies;
		$this->_aParams["apply_status_no_reply"] = $apply_status->read;
		$this->_aParams["lastThreeFairs"] = $lastThreeFairs->items;
		return $this->render('index.html', $this->_aParams);
	}

	private function GetDomainInfor(){
		$domain = array(
			'image' 	=> '',
			'photo' 	=> '',
			'defaultPhoto'=>''
		);
		$xml = SXML::load('../config/config.xml');
		if(is_null($xml)){
			return $domain;
		}
		$domain["image"] = $xml->ImgDomain;
		$domain["photo"] = $xml->CqjobSysUserHeadPhoto;
		$domain["defaultPhoto"] = $xml->CqjobSysUserDefaultHeadPhoto;
		return $domain;
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

	// private function GetCustomerService(){
	// 	$customerService = array(
	// 		'name' 		=> '',
	// 		'phone'		=> '',
	// 		'phone400'	=> ''
	// 	);
	// 	$xml = SXML::load('../config/config.xml');
	// 	if(is_null($xml)){
	// 		return $customerService;
	// 	}

	// 	$customerService = array(
	// 		'name' 		=> $xml->CustomerServiceName,
	// 		'phone'		=> $xml->CompanyServicePhone,
	// 		'phone400'	=> $xml->HuiboPhone400
	// 	);
	// 	return $customerService;
	// }
}
?>