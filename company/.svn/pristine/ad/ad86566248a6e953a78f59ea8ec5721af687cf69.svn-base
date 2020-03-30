<?php
/**
 * @Desc:	带找客户管理
 * @Author: zhangfangjun@huibo.com
 * @Date:   2015-11-24 14:53:52
 * @Last Modified by:   zhangfangjun - Administrator
 * @Last Modified time: 2015-12-08 17:04:11
 * @Copyright (c) http://www.huibo.com All rights reserved.:
 */
class controller_hrmanage extends components_cbasepage{
	
	function __construct(){
		parent::__construct();
	}

	function pageIndex($inPath){
		$company_service = new base_service_company_company();
		$hrinfo = base_service_company_resources_resources::getInstance($this->_userid,false);;
		if ($hrinfo->account_type != 'hr_main') {
			$companyStateService = new base_service_company_comstate();
			$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
			$hrManager    = $this->GetHRManager($companyState["net_heap_id"]);
			if(empty($hrManager)){
				//$userInfor = $this->GetCustomerService($this->_userid);
				//$this->_aParams['hrManager'] 		= $userInfor;
			}else{				
				$this->_aParams['hrManager'] 		= $hrManager;
			}
			$xml = SXML::load('../config/config.xml');
	        $tel_head = "023-61627888";
	        if (!is_null($xml)) {
	            $tel_head = $xml->TechniquePhone;
	        }
	        $easy_tel_head = str_replace("-", "", $tel_head);
			$this->_aParams['tel_head']      	= $tel_head;
			$this->_aParams['easy_tel_head'] 	= $easy_tel_head;
		}else{
			$sub_account = $hrinfo->accounts;
			if(!empty($sub_account)){
				$customs = $company_service->getCompanys($sub_account,'company_id,company_name,company_shortname,create_time');
				$job_service = new base_service_company_job_job();
				$jobstatus = new base_service_common_jobstatus();

				$adult_service = new base_service_company_hrcompanyaudit();
				// $adult_status = new base_service_common_hrstatus();

				// var_dump($adult_status);die;

				$adults = base_lib_BaseUtils::array_key_assoc($adult_service->getHrAudits($sub_account,'company_id,status as is_audit'),'company_id');

				foreach ($customs as $key => $company) {
					$customs[$key]['is_audit'] = $adults[$company['company_id']]['is_audit'];
					$customs[$key]['company_flag'] = base_lib_Rewrite::getFlag('company',$company['company_id']);
					$customs[$key]['create_time'] = date('Y-m-d',strtotime($customs[$key]['create_time']));
					$customs[$key]['pub_jobcount'] = $job_service->getJobCount($company['company_id'], $jobstatus->use);
					// $company["is_audit"] = 2;
					switch ($customs[$key]['is_audit']) {
						case 2://不通过
							$is_audit = '3';//认证未通过
							$customs[$key]['status_name'] = '[审核未通过]';
							break;
						case 0://等待
							$customs[$key]['status_name'] = '[审核中]';//审核中
							break;
						default://未提交审核
							break;
					}
				}
			}
		}
		$this->_aParams['max_count'] = intval($this->GetCompanyMaxCount($this->_userid));

		$left_count = intval($this->_aParams['max_count']-count($sub_account));
		$left_count = ($left_count <=0)?0:$left_count;
		$this->_aParams['account_type'] = $hrinfo->account_type;
		$this->_aParams['customs'] = $customs;
		$this->_aParams['sub_count'] = count($sub_account);
		$this->_aParams['left_count'] = $left_count;
		return $this->render('hrmember/index.html',$this->_aParams);
	}


	

	//代招的专题介绍页面
	public function pageDescrip($inPath){
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
		return $this->render('hrmember/hrdesc.html',$this->_aParams);
	}

	//获取待处理的简历数
	public function pageGetApplyCount($inPath){
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$company_id = base_lib_BaseUtils::getStr($pathdata['companyid'],'int',0);
		$resource_service = base_service_company_resources_resources::getInstance($this->_userid,false);;
		$sub_account = $resource_service->accounts;
		$json = array('status'=>0,'msg'=>'操作失败');
		if(!in_array($company_id,$sub_account) || count($sub_account)==0){
			echo json_encode($json);
			return;
		}
		$jobs = array();
		$job_service = new base_service_company_job_job();
		$apply_service = new base_service_company_resume_apply();
		$jobstatus = new base_service_common_jobstatus();
		$jobs = $job_service->getJobList($company_id,null,$jobstatus->pub,'job_id');
		$jobids = base_lib_BaseUtils::getProperty($jobs,'job_id');
		if(!empty($jobids)){
			$applycounts = $apply_service->getApplyStatisticsByJobIdsVerson2($company_id,$jobids)->items;
		}
		$job_ids = array();$count = 0;
		foreach ($applycounts as $key => $value) {
			if($value['no_reply_num']){
				$job_ids[] = $value['job_id'];
				$count += intval($value['no_reply_num']);
			}
		}
		$json = array('status'=>1,'jobcount'=>count($job_ids),'applycount'=>$count,'jobids'=>$job_ids);
		echo json_encode($json); return;
	}

	//自动回绝待处理简历->关闭职位->删除代招客户
	public function pageDelCompany($inPath){
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$company_id = base_lib_BaseUtils::getStr($pathdata['companyid'],'int',0);
		$operate = base_lib_BaseUtils::getStr($pathdata['operate'],'int',1);
		$resource_service = base_service_company_resources_resources::getInstance($this->_userid,false);;
		$sub_account = $resource_service->accounts;
		$json = array('status'=>0,'msg'=>'操作失败');
		if(!in_array($company_id,$sub_account) || count($sub_account)==0){
			echo json_encode($json);
			return;
		}
		$service_apply = new base_service_company_resume_apply();
		$allstatus = new base_service_company_resume_applystatus();
		$where['company_id'] = $company_id;
		$where['re_status'] = $allstatus->no_reply;  //未处理的简历
		$apply_list = $service_apply->select($where,'apply_id')->items;

		$appids = base_lib_BaseUtils::getProperty($apply_list,'apply_id');
		
		//拒绝简历
		$result = true;
		foreach ($appids as $id){
            $content = "非常荣幸收到您的简历，在我们认真阅读您的简历之后，发现您的简历与该职位的定位有些不匹配，因此我们不得不遗憾的通知您无法进入面试。但您的信息我们已录入我司人才储备库，当有与您履历相匹配的职位时，我们将第一时间联系您，希望在未来我们有机会能一起奋斗拼搏。再次感谢您对我们公司的信任，祝您早日找到满意的工作。";           
            $result = $service_apply->refusedReplayV2($id,$company_id,$content,false);
			if($result===false) {
				break;
			}
		}

		//关闭职位
		if($result){
			$count = 0;
			$job_status = new base_service_common_jobstatus();
			$service_job = new base_service_company_job_job();
			$jobs = $service_job->getJobList($company_id,null,$job_status->pub,'job_id');
			$job_ids = base_lib_BaseUtils::getProperty($jobs,'job_id');
			foreach ($job_ids as $jobid){
				$result = $service_job->setJobStatus($company_id, $jobid, $job_status->stop_use);
				if($result===false) {
					break;
				}
				$this->setOuterStop($jobid);
			}
		}
		//删除代招客户
		if($result){
			if(!$operate){
				$company_service = new base_service_company_company();
				$company = array('company_id'=>$company_id,'is_effect'=>0);
				$result1=$company_service->updateCompany($company,null);
			}			
		}
		if ($result==false){
			echo json_encode(array('status'=>0,'msg'=>'操作失败'));	return;
		}else {
			echo json_encode(array('status'=>1,'msg'=>'操作成功'));	 return;
		}		
	}

	private function get_telephone_infor($telephone){
		$telephone_infor = array('zone'=>'区号','phone'=>'固定电话','ext'=>'分机号');
		preg_match("/^([0-9]+)-([0-9]+)(\(([0-9]+)\))?$/", $telephone, $regs);
		if(count($regs)<=0){
			$telephone_infor["phone"] = $telephone;
			return $telephone_infor;
		}
		if($regs[1]!="")
			$telephone_infor["zone"] 	= $regs[1];
		if($regs[2]!="")
			$telephone_infor["phone"]	= $regs[2];
		if($regs[4]!=""){
			$telephone_infor["ext"]		= $regs[4];
		}
		return $telephone_infor;
	}

	private function collect_telephone_number($zone,$phone,$ext){
        //过滤掉phone 里面的- 及();
        $phone_arr = explode("-",$phone);
        $phone = $phone_arr[count($phone_arr)-1];
        $phone_arr = explode("(",$phone);
        $phone = $phone_arr[0];
		$telephone = $phone;
		if(preg_match("/^[0-9]{3}[0-9]?$/",$zone))
			$telephone = $zone."-".$phone;
		else
			$telephone = "023-".$phone;
		if(preg_match("/^[0-9]+$/",$ext))
			$telephone = $telephone."(".$ext.")";
		return $telephone;
	}

	private function get_video_infor($video_name,$video_path){
		$video_infor = array("video_name"=>"视频名称","video_path"=>"优酷、土豆等网站的视频url地址");
		if($video_name!=""){
			$video_infor["video_name"] = $video_name;
		}
		if($video_path!=""){
			$video_infor["video_path"] = $video_path;
		}
		return $video_infor;
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

	// 获取客服人员
	// private function GetCustomerService($companyid) {
	// 	$customerbelongService = new base_service_crm_netcompanycustomerbelong();
	// 	$customerbelong =  $customerbelongService->getCustomerbelongById($companyid, 'net_heap_id');
	// 	if(empty($customerbelong)) {
	// 		return null;		
	// 	}
	// 	$customerheapservice = new base_service_crm_netcustomerheap();
	// 	$customerheap = $customerheapservice->getCustomerheapById($customerbelong['net_heap_id'], 'own_man');
	// 	if(empty($customerheap)) {
	// 		return null;
	// 	}
	// 	$userService=new base_service_crm_user();
	// 	$userInfor = $userService->GetUsers($customerheap['own_man'], "user_name,head_photo_url,tel,mobile,qq");
	// 	return $userInfor;
	// }

	// 获取代招客户的最大值
	private function GetCompanyMaxCount($companyid) {
		$comservice = new base_service_company_service_comservice();
		$myservice = $comservice->getComService($companyid,'proxy_company_num',null,2);
		return $myservice['proxy_company_num'];
	}
	private function setOuterStop($job_id){
		if(empty($job_id)){
			return;
		}
		$outerJob_union_InnerJob_service = new base_service_outer_outerjobunioninnerjob();
		//获取站外关联id
		$outer_id = $outerJob_union_InnerJob_service->getOuterjobsort($job_id,'id');
		if(empty($outer_id)){
			return;
		}
		//关闭 自动关闭站内职位
		$outerJob_union_InnerJob_service->setOuterJobAutoStopStatus($outer_id['id']);
	}
}