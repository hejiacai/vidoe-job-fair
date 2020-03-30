<?php
/**
 * @Desc:	员工共享控制器
 * @Author: ln
 * @Date:   2020-03-10
 */
class controller_workershar extends components_cbasepage {
	
	function __construct() {
		parent::__construct(true);
	}

	function pageIndex($inPath){
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$type = base_lib_BaseUtils::getStr($pathdata["type"],"int",1);
		$page_index = base_lib_BaseUtils::getStr($pathdata["page"], "int", 1);
		$page_size = $type == 1 ? 10 : 12;
		if(!in_array($type,[1,2])){
			return $this->render('../config/404.html');
		}

		$service_company_workershar_workershar = new base_service_company_workershar_workershar();
		$result = $service_company_workershar_workershar->getWorkerSharList($page_index,$page_size,$type);
		$list = $result->items;

		$company_ids = array_unique(base_lib_BaseUtils::getPropertys($list,"company_id"));
		$service_company_company = new base_service_company_company();
		$company_list = $service_company_company->getCompanyByIDs($company_ids,"company_id,company_name,company_logo_path")->items;
		$company_list = base_lib_BaseUtils::array_key_assoc($company_list,"company_id");

		$xml = SXML::load('../config/company/company.xml');
		if (!is_null($xml)) {
			//logo
			$logofolder = $xml->LogoFolder;
			$virtualName = $xml->VirtualName;
		}

		foreach($list as $key => &$value){
			$value['company_name'] = $company_list[$value['company_id']]['company_name'];
			$logo = $company_list[$value['company_id']]['company_logo_path'];
			if (!base_lib_BaseUtils::nullOrEmpty($logo)) {
				$value['logo_path'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $virtualName . '/' . $logofolder . '/' . $logo;
			}
			else {
				$value['logo_path'] = base_lib_Constant::STYLE_URL . "/img/c/new_index/headlogo.png";
			}
		}
		unset($value);

		$this->_aParams['type'] = $type;
		$this->_aParams['list'] = $list;
		$this->_aParams['company_id'] = $this->_userid;
		$this->_aParams['next_page'] = $result->totalPage <= $page_index ? 0 : $page_index + 1;
		$this->_aParams["pager"] = $this->pageBarFullPath($result->totalSize, $page_size, $page_index, $inPath);

		if($type == 1){
			$cur = "员工共享";
			$template = "./workershar/worker_shar.html";
		}else{
			$cur = "求共享";
			$template = "./workershar/request_shar.html";
		}
		$this->_aParams['cur'] = $cur;
		$this->_aParams['title'] = "人才共享-".$cur;

		return $this->render($template, $this->_aParams);
	}

	public function pageajaxGetList($inPath){
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$type = base_lib_BaseUtils::getStr($pathdata["type"],"int",1);
		$page_index = base_lib_BaseUtils::getStr($pathdata["page"], "int", 1);
		$page_size = $type == 1 ? 10 : 12;

		$service_company_workershar_workershar = new base_service_company_workershar_workershar();
		$result = $service_company_workershar_workershar->getWorkerSharList($page_index,$page_size,$type);
		$list = $result->items;

		$company_ids = array_unique(base_lib_BaseUtils::getPropertys($list,"company_id"));
		$service_company_company = new base_service_company_company();
		$company_list = $service_company_company->getCompanyByIDs($company_ids,"company_id,company_name,company_logo_path")->items;
		$company_list = base_lib_BaseUtils::array_key_assoc($company_list,"company_id");

		$xml = SXML::load('../config/company/company.xml');
		if (!is_null($xml)) {
			//logo
			$logofolder = $xml->LogoFolder;
			$virtualName = $xml->VirtualName;
		}

		foreach($list as $key => &$value){
			$value['company_name'] = $company_list[$value['company_id']]['company_name'];
			$logo = $company_list[$value['company_id']]['company_logo_path'];
			if (!base_lib_BaseUtils::nullOrEmpty($logo)) {
				$value['logo_path'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $virtualName . '/' . $logofolder . '/' . $logo;
			}
			else {
				$value['logo_path'] = base_lib_Constant::STYLE_URL . "/img/c/new_index/headlogo.png";
			}
		}
		unset($value);
		$this->_aParams['list'] = $list;
		$next_page = $result->totalPage <= $page_index ? 0 : $page_index + 1;
		$result = $this->render('./workershar/ajaxlist.html', $this->_aParams);
		echo json_encode(array ('html' => $result,'next_page' => $next_page));
		exit;
	}

	/**
	 * 共享详情
	 * @param $inPath
	 */
	public function pagesharinfo($inPath){
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$id = base_lib_BaseUtils::getStr($pathdata["id"],"int",'');

		$service_company_workershar_workershar = new base_service_company_workershar_workershar();
		$service_company_workershar_wsharapply = new base_service_company_workershar_wsharapply();
		$work_shar_info = $service_company_workershar_workershar->getWorkSharInfo($id);
		if(empty($work_shar_info)){
			echo "活动不存在";exit();
		}

		$xml = SXML::load('../config/company/company.xml');
		if (!is_null($xml)) {
			//logo
			$logofolder = $xml->LogoFolder;
			$virtualName = $xml->VirtualName;
		}

		$service_company_company = new base_service_company_company();
		$company_info = $service_company_company->getCompany($work_shar_info['company_id'],1,"company_id,company_name,company_logo_path,is_audit,audit_state,recruit_type,is_effect");
		if (!base_lib_BaseUtils::nullOrEmpty($company_info['company_logo_path'])) {
			$company_info['logo_path'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $virtualName . '/' . $logofolder . '/' . $company_info['company_logo_path'];
		}else {
			$company_info['logo_path'] = base_lib_Constant::STYLE_URL . "/img/c/new_index/headlogo.png";
		}

		$company_flag = base_lib_Rewrite::getFlag('company',$company_info['company_id']);
		$company_url = base_lib_Rewrite::company($company_info['company_id'],$company_flag);

		//企业是否认证
		$is_audit = 0;//未认证
		if ($company_info['is_effect'] == "1" && $company_info['is_audit'] == '1') {
			if ($company_info['audit_state'] == '1' || $company_info['audit_state'] == '4') {
				$is_audit = 1; //已经认证
			} else {
				$is_audit = 4; //验证待补
			}
			if ($company_info['audit_state'] =='2' || $company_info['audit_state']=='3') {
				$is_audit = 4; //已经认证,但在临时和补办状态中

			}
		} else if ($company_info['is_effect'] =="1" && $company_info['is_audit'] == '2') {
			$is_audit = 2; //认证中
		} else if ($company_info['is_effect'] =="1" && $company_info['is_audit'] == '0') {
			$is_audit = 3;//认证未通过
		}

		$company_info['is_audit'] = $is_audit;

		$check_apply = $service_company_workershar_wsharapply->checkApply($this->_userid,$id,$work_shar_info['type']);
		$work_shar_info['is_apply'] = empty($check_apply) ? 0 : 1;

		$this->_aParams['company_url'] = $company_url;
		$this->_aParams['company_info'] = $company_info;

		$this->_aParams['company_id'] = $this->_userid;
		$this->_aParams['info'] = $work_shar_info;

		$this->_aParams['title'] = $work_shar_info['type'] == 1 ? "人才共享-员工共享详情" : '人才共享-求共享详情';

		return $this->render("./workershar/shar_info.html", $this->_aParams);
	}


	public function pageAdvisoryDo($inPath){
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$id = base_lib_BaseUtils::getStr($pathdata["id"],"int",'');
		$worker_require = base_lib_BaseUtils::getStr($pathdata["worker_require"],"string",'');
		$phone = base_lib_BaseUtils::getStr($pathdata["phone"],"string",'');
		$worker_introdece = base_lib_BaseUtils::getStr($pathdata["worker_introdece"],"string",'');

		if(empty($id)){
			echo json_encode(['status' => false, 'msg' => "参数错误"]);
			exit();
		}
		$service_company_workershar_workershar = new base_service_company_workershar_workershar();
		$work_shar_info = $service_company_workershar_workershar->getWorkSharInfo($id);
		if(empty($work_shar_info)){
			echo json_encode(['status' => false, 'msg' => "活动不存在"]);
			exit();
		}
		if($work_shar_info['type'] == 1 && empty($worker_require)){
			echo json_encode(['status' => false, 'msg' => "请输入需要员工"]);
			exit();
		}
		if(empty($phone)){
			echo json_encode(['status' => false, 'msg' => "请输入联系电话"]);
			exit();
		}
		$Validator = new base_lib_Validator();
		$Validator->getMobile($phone,"请输入正确的联系电话");
		if($Validator->has_err){
			echo json_encode(['status' => false, 'msg' => $Validator->err[0]]);
			exit();
		}
		if(empty($worker_introdece) || mb_strlen($worker_introdece) > 300){
			echo json_encode(['status' => false, 'msg' => "请输入不超过300字的说明"]);
			exit();
		}
		$service_company_workershar_wsharapply = new base_service_company_workershar_wsharapply();

		$data = [
			'apply_company_id'		=> $this->_userid,
			'wshar_company_id'		=> $work_shar_info['company_id'],
			'wshar_id'				=> $id,
			'type'					=> $work_shar_info['type'],
			'worker_require'		=> $worker_require,
			'phone'					=> $phone,
			'worker_introdece'		=> $worker_introdece,
			'create_time'			=> date("Y-m-d H:i:s"),
			'is_effect'				=> 1
		];

		$result = $service_company_workershar_wsharapply->addApply($data);
		echo json_encode(['status' => $result['status'], 'msg' => $result['msg']]);
		exit();
	}
}