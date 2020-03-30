<?php
/**
 * @copyright 2002-2013 www.huibo.com
 * @name
 * @author 营业执照验证
 * @version 2013-8-13
 */
class controller_licencevalidateforfair extends components_cbasepage {
	function __construct() {
		parent::__construct(false);
		$userid=base_lib_BaseUtils::getCookie('userid');
		$user_id_type=base_lib_BaseUtils::getCookie('user_id_type');
		$user_id_type != md5($userid.date('Y-m-d').base_lib_Constant::SYSUSERKEY) and die('加密验证失败');
		$this->_userid=$userid;
	}

	/**
	 * @desc企业营业执照验证页面
	 * @param object $inPath
	 */
	public function  pageIndex($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		//获取企业信息
		$companyService = new base_service_company_company();
		$company = $companyService->getCompany($this->_userid, 1, 'company_id,company_name,is_audit,audit_state,site_type');
		//获取招聘服务信息
		$heapService = new base_service_company_comstate();
		$heapInfo = $heapService->getCompanyState($this->_userid, "net_heap_id");
		$domain    = $this->GetDomainInfor();
		$hrManager = $this->getHRManager($heapInfo["net_heap_id"]);
       
        $step = base_lib_BaseUtils::getStr($pathdata['step'],"int",null);
        if(!base_lib_BaseUtils::nullOrEmpty($step)){
            $this->_aParams["step"] = $step;
        }
		//获取客服员
		$customerService = $this->GetCustomerService();
		$this->_aParams["hasHRManager"] = false;
	    if (!is_null($hrManager)) {
			$this->_aParams["hasHRManager"] = true;
			$headPhoto = base_lib_BaseUtils::nullOrEmpty($hrManager["head_photo_url"]) ? $domain["defaultPhoto"] : $hrManager["head_photo_url"];
			$hrManager["head_photo_url"]    = $domain["image"] . "/" . $domain["photo"] . "/" . $headPhoto;
			$this->_aParams["hrManager"]    = $hrManager;

			$tel_head = "023-61627888";
			$xml = SXML::load('../config/config.xml');
	        if (!is_null($xml)) {
	            $tel_head = $xml->TechniquePhone;
	        }
	        $easy_tel_head = str_replace("-", "", $tel_head);
			$this->_aParams['tel_head']      = $tel_head;
		}
		$customerService["photo"] = $domain["image"]."/".$domain["photo"]."/".$domain["defaultPhoto"];
		$this->_aParams["customerService"] = $customerService;
        $openweixinservice = new SOpenWeiXin();
		$twodimensioncodeurl = $openweixinservice->generateTwoDimensionCode($this->_userid);
        $this->_aParams["codeurl"] = $twodimensioncodeurl;
		//todo 16-10-22 该codeurl无法绑定，占时替换为其他的



		//判断是否再次上传
//		$action   = base_lib_BaseUtils::getStr($pathdata['action']);
//
//		if (!empty($action) && strtolower($action) == 'againupload') {
//			$this->_aParams['action'] = "/licencevalidate/AgainUploadLicence";
//			//$this->_aParams['validateid'] = $pathdata["validateid"];
//			return $this->notlicence($company,array(),array());
//		}

		$action = "/licencevalidateforfair/UploadLicence/";
		$this->_aParams['action'] = $action;
		$this->_aParams['title']  = '营业执照验证';
		//获取认证信息
		$render = null;
		$licenceService = new base_service_company_audit();
//		$companyLicenceInfor = $licenceService->getCompanyLicenceInfor($this->_userid, "licence_id,legal_person_representative,audit_time,audit_reply");
//        $service_company_companyschoolrecruit = new base_service_company_companyschoolrecruit();
//        $companyschoolrecruit = $service_company_companyschoolrecruit->getRecruitByCompanyId($this->_userid);

        $resources_company_service = new base_service_company_resources_resources($this->_userid);
        $audit_result = $resources_company_service->getCompanyAuditStatusV2();

        $this->_aParams['audit_result'] = $audit_result;
        //上传认证资料内容
        if( in_array($audit_result['licence_audit_type'],array(0,2,3,4,5)) && in_array($audit_result['letter_audit_type'] , array(0,2,3)) ){
            $this->_aParams['ActionType'] = 'all';
        }else if(in_array($audit_result['licence_audit_type'],array(0,3,4,5)) && !in_array($audit_result['letter_audit_type'] , array(0,3)) ){
            $this->_aParams['ActionType'] = '1';
        }else if(!in_array($audit_result['licence_audit_type'],array(0,3,4,5)) && in_array($audit_result['letter_audit_type'] , array(0,3)) ){
            $this->_aParams['ActionType'] = '2';
        }
        //委托书需求 营业制造和委托书认证 逻辑
        //未审核 企业营业执照(未审核、审核待补、同行认证) 或者 委托书 ，则展示上传页面
        if(in_array($audit_result['licence_audit_type'],array(0,4,5)) || $audit_result['letter_audit_type']==0){
            $render = $this->notlicence($company, $audit_result['licence_data'],$audit_result['letter_data']);
        }elseif($audit_result['licence_audit_type']==3 || $audit_result['letter_audit_type'] == 3){
            //未通过 企业营业执照 或者 委托书，则展示上传页面
            $render = $this->notlicence($company, $audit_result['licence_data'],$audit_result['letter_data']);
        }elseif($audit_result['licence_audit_type']==2 || $audit_result['letter_audit_type'] == 2){
            //认证中 企业营业执照 或者 委托书，则展示等待页面
            $render = $this->waitLicence($company);
        }elseif(in_array($audit_result['licence_audit_type'],array(1)) && $audit_result['letter_audit_type'] == 1){
            //已认证 企业营业执照 或者 委托书，则展示等待页面
            $render = $this->passlicence($company, $audit_result['letter_data']);
        }
//		switch ($company["is_audit"]) {
//			case '0'://不通过
//				$render = $this->nopasslicence($company, $companyLicenceInfor);
//				break;
//			case '1'://通过
//				if ($company['audit_state'] == '1' || ($company['audit_state'] == '4' && empty($companyschoolrecruit))) {
//					$render = $this->passlicence($company, $companyLicenceInfor);
//				} else {
//					$render = $this->notlicence($company, $companyLicenceInfor);
//				}
//				break;
//			case '2'://等待
//				$render = $this->waitLicence($company);
//				break;
//			default://未审核
//				$render = $this->notlicence($company, $companyLicenceInfor);
//				break;
//		}
		return $render;
	}
    
	/**
	 *
	 * 未上传营业执照
	 * @param object $company
	 */
	private function  notlicence($company,$licencevaliate,$letterdata) {
		$this->_aParams['companyname'] = $company['company_name'];
		$this->_aParams['site_type'] = $company['site_type'];
		//$this->_aParams['licenceid']=$licencevaliate['licence_id'];
		//$this->_aParams['legalperson'] = $licencevaliate['legal_person_representative'];
		//用于解决火狐上传COOKIE丢失问题  cw
		$ser_upload = new base_service_upload_upload();
		$xml = SXML::load('../config/company/company.xml');
		if(!is_null($xml)){
			$licence_folder = $xml->LicenceFolder;
			$licence_temp_folder = $xml->LicenceTempFolder;
			$company_image_path = $xml->CompanyImagePath;
			$virtualName = $xml-> VirtualName;
			$file_max_size = $xml->LicenceFileMaxSize;
			$ext = $xml->LicenceFileExtensions;
		}
		$path ="{$virtualName}/{$licence_folder}";
		$photo_max_count = 1;
		$up_options = array ('file_name' => 'business_license[]', 'fileVal' => 'Filedata','is_load_jquery'=>false, 'auto' => true,'path'=>$path,'file_max_size'=>$file_max_size,'ext'=>$ext,'photo_max_count'=>$photo_max_count);
		if($licencevaliate['licence_file_url']){
            $up_options['defaults_files'][] = $licencevaliate['licence_file_url'];
        }

		$up1_options = array ('file_name' => 'Power_of_attorney[]', 'fileVal' => 'Filedata','is_load_jquery'=>false, 'auto' => true,'path'=>$path,'file_max_size'=>$file_max_size,'ext'=>$ext,'photo_max_count'=>$photo_max_count);
        if($letterdata['letter_file_url']){
            $up1_options['defaults_files'][] = $letterdata['letter_file_url'];
        }


		$this->_aParams['up_img_html'] = $ser_upload->GetUploadHtmlDom($up_options, $this->_userid, 'up_style2', 'img', 'report', '../config/company/company.xml');
		$this->_aParams['up_img1_html'] = $ser_upload->GetUploadHtmlDom($up1_options, $this->_userid, 'up_style2', 'img', 'report', '../config/company/company.xml');
		$this->_aParams['expect_complete_time'] = date('Y-m-d');
		return $this->render('sysmanage/fair/companylicencevalidate.html', $this->_aParams);
	}

	/*uploadify
 * 添加文件
 */
	public function pagepicture($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$up_type = base_lib_BaseUtils::getStr($pathdata['up_type'], 'string');
		$file = $_FILES['Filedata'];

		$verify_data = base_lib_BaseUtils::getStr($pathdata['verify_data'], 'array');
		$serv_askforleave = new base_service_upload_upload();
		$xml = SXML::load('../config/company/company.xml');
		if(!is_null($xml)){
			$licence_folder = $xml->LicenceFolder;
			$licence_temp_folder = $xml->LicenceTempFolder;
			$company_image_path = $xml->CompanyImagePath;
			$virtualName = $xml-> VirtualName;
		}
		$in_data['path'] ="{$virtualName}/{$licence_temp_folder}";
		$in_data['file_max_size'] = $xml->LicenceFileMaxSize;
		$in_data['ext'] = $xml->LicenceFileExtensions;
		$in_data['photo_max_count'] = 1;
		$in_data['is_folder_date'] = false;
		$arr = $serv_askforleave->UploadFile($file, $verify_data, $up_type, 'company', '../config/company/company.xml',$in_data);

		if ($arr['status'] == false) {
			$this->ajax_data_json(ERROR, $arr['msg'], $arr);
		}
		if ($up_type == 'file') {
			$arr['newname_path'] = $arr['name'] . "|" . $arr['newname_path'];
		}

		$this->ajax_data_json(SUCCESS, "上传成功", $arr);
	}

	/**
	 * 返回json数据
	 * @author chenbin
	 * @param int|string $code fail   success
	 * @param string $msg
	 * @param array| $data
	 */
	function ajax_data_json($code = ERROR, $msg = "操作失败", $data = array ()) {
		define('IS_AJAX', ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) ? true : false);
		$msg = $msg ? $msg : '操作失败';
		$code = $code ? $code : ERROR;
		if (!IS_AJAX) {
			$this->layer_msg_to_close($msg);
		}
		// 返回JSON数据格式到客户端 包含状态信息
		header('Content-Type:application/json; charset=utf-8');
		exit(json_encode(array ('code' => $code, 'msg' => $msg, 'data' => $data)));
	}
	function layer_msg_to_close($msg = "参数错误!") {
		$data['msg'] = $msg;

		die($this->render('public/msg_error.html', $data));
	}

	/**
	 * 删除临时照片（废弃）
	 * @param array $inPath
	 */
	public function pageDelTempFile($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$up_type = base_lib_BaseUtils::getStr($pathdata['up_type'], 'string');
		$verify_data = base_lib_BaseUtils::getStr($pathdata['verify_data'], 'array');
		$file = $_REQUEST['file_path'];
		$serv_askforleave = new base_service_upload_upload();
		$xml = SXML::load('../config/company/company.xml');
		if(!is_null($xml)){
			$licence_folder = $xml->LicenceFolder;
			$licence_temp_folder = $xml->LicenceTempFolder;
			$company_image_path = $xml->CompanyImagePath;
			$virtualName = $xml-> VirtualName;
		}
		$in_data['path'] ="{$virtualName}/{$licence_temp_folder}";
		$in_data['file_max_size'] = $xml->LicenceFileMaxSize;
		$in_data['ext'] = $xml->LicenceFileExtensions;
		$in_data['photo_max_count'] = 1;
		$arr = $serv_askforleave->DelFile($file, $verify_data, $up_type, 'report', '/cp/config.xml',$in_data);
		if (@$arr['status'] == false) {
			$this->ajax_data_json(ERROR, $arr['msg'], $arr);
		}

		$this->ajax_data_json(SUCCESS, "删除成功", $arr);
	}

	/**
	 *
	 * 等待认证
	 * @param object $company
	 */
	private  function  waitLicence($company){
	    return $this->render('sysmanage/fair/waitlicence.html',$this->_aParams);
	}

	/**
	 * 未通过认证
	 * @param object $company
	 */
	private  function nopasslicence($company,$validate) {
		$this->_aParams['reply'] = $validate['audit_reply'];
		$this->_aParams['validateid'] = $validate['validate_id'];
		return $this->render('sysmanage/fair/nopasslicence.html', $this->_aParams);
	}

	/**
	 * @param object $company
	 */
	private  function passlicence($company,$licencevaliate) {
	   $this->_aParams['companyname'] = $company['company_name'];
	   $this->_aParams['audittime'] = date('Y年m月',strtotime($licencevaliate['audit_time']));
	   $this->_aParams['licenceid'] = $licencevaliate['licence_id'];
	   $this->_aParams['legalperson'] = $licencevaliate['legal_person_representative'];
	   return $this->render('sysmanage/fair/passlicence.html', $this->_aParams);
	}

	/**
	 *
	 * 上传营业执照
	 * @param object $inPath
	 */
	public function pageUploadLicence($inPath) {
 	    $service_licencevalidate = new base_service_company_licencevalidate();
	    $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

        $hddActionType = base_lib_BaseUtils::getStr($pathdata['hddActionType'],'string','');

	    $val = new base_lib_Validator();
	    if(in_array($hddActionType , array('all',1))){
            $licencevalidate['company_name'] =$val->getStr($pathdata['companyname'],1,200,'公司名称错误，请重新填写');
            //$licencevalidate['licence_id'] =$val->getStr($pathdata['licenceid'],1,32,'注册号错误，请重新填写');
            //$licencevalidate['legal_person_representative'] =$val->getStr($pathdata['legalperson'],1,32,'法人代表错误，请重新填写');
            $licencevalidate['create_time'] = date("Y-m-d H:i:s");
        }

	    $licenceurl = base_lib_BaseUtils::getStr($pathdata['hddLicenceurl'],'string','');
	    $PowerOfAttorney = base_lib_BaseUtils::getStr($pathdata['hddPowerOfAttorney'],'string','');

	    if(in_array($hddActionType , array('all',1))){
            if(base_lib_BaseUtils::nullOrEmpty($licenceurl)){
                $val->addErr("请上传营业执照");
            }
        }

        if(in_array($hddActionType , array(2))){
            if(base_lib_BaseUtils::nullOrEmpty($PowerOfAttorney)){
                $val->addErr("请上传委托书");
            }
        }

        //委托书相关信息
        if(in_array($hddActionType , array(2)) || $PowerOfAttorney) {
        	$auditdate['letter_name'] = $val->getStr($pathdata['letter_name'],1,30,'委托人姓名必须填写<br>');
        	$auditdate['letter_id_card'] = $val->getStr($pathdata['letter_id_card'],1,30,'委托人身份证号码必须填写<br>');
        	$auditdate['letter_mobile_phone'] = $val->getMobile($pathdata['letter_mobile_phone'],'委托人电话号码必须填写<br>');
        	$auditdate['letter_position'] = $val->getStr($pathdata['letter_position'],1,50,'委托人职位必须填写<br>');
        	$auditdate['letter_date'] = $val->getDatetime($pathdata['letter_date'],'委托时间必须填写<br>');
        }

	    if($val->has_err) {
 	   	 	 echo $val->toJsonWithHtml();
 	   	 	 return;
 	   	}

        $resources_company_service = new base_service_company_resources_resources($this->_userid);
        $audit_result = $resources_company_service->getCompanyAuditStatusV2();

        if(in_array($hddActionType , array('all',1)) && !base_lib_BaseUtils::nullOrEmpty($licenceurl) && in_array($audit_result['licence_audit_type'],array(0,3,4,5))){
            $licencevalidate['company_id'] = $this->_userid;
            $val->has_err = $service_licencevalidate->saveLicence($licencevalidate,$licenceurl);

            if($val->has_err==false){
                $val->addErr("修改数据失败");
                return $val->toJsonWithHtml();
            }
        }

        if(in_array($hddActionType , array('all',2)) && !base_lib_BaseUtils::nullOrEmpty($PowerOfAttorney) && in_array($audit_result['letter_audit_type'],array(0,3))){
            $company_service = new base_service_company_company();
            $companyinfo = $company_service->getCompany($this->_userid, '', 'company_name');
            $data = array();
            $data['company_id'] = $this->_userid;
            $data['letter_file_url'] = $PowerOfAttorney;
            $data['upload_time'] = date('Y-m-d H:i:s');
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['company_name'] = $companyinfo['company_name'];
            //添加委托书记录
            $company_letter_validate = new base_service_company_letter_validate();

            $val->has_err1 = $company_letter_validate->saveData($data , $PowerOfAttorney);

            if($val->has_err1==false){
                $val->addErr("修改数据失败");
                return $val->toJsonWithHtml();
            }
        }

        //委托书相关信息更新
        if (!empty($auditdate)) {
			$service_letter_audit = new base_service_company_letter_audit();
            $val->has_err_audit = $service_letter_audit->UpdateCompanyLetterAuditByCompanyId($this->_userid, $auditdate);
            if($val->has_err_audit == false){
                $val->addErr("修改委托书相关数据失败");
                return $val->toJsonWithHtml();
            }
        }

		echo json_encode(array("success"=>true));
		return;
	}

	/**
	 *
	 * 重新上传营业执照
	 */
	public  function pageAgainUploadLicence($inPath) {
 	    $service_licencevalidate = new base_service_company_licencevalidate();
	    $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
	    $val = new base_lib_Validator();
	    $validate_id = base_lib_BaseUtils::getStr($pathdata['hddvalidateid']);
	    $licencevalidate['company_name'] =$val->getStr($pathdata['companyname'],1,200,'公司名称错误，请重新填写');
	    //$licencevalidate['licence_id'] =$val->getStr($pathdata['licenceid'],1,32,'注册号错误，请重新填写');
	    //$licencevalidate['legal_person_representative'] =$val->getStr($pathdata['legalperson'],1,32,'法人代表错误，请重新填写');
	    $licencevalidate['create_time'] = date("Y-m-d H:i:s");
	    $licenceurl = base_lib_BaseUtils::getStr($pathdata['hddLicenceurl'],'string','');
		if(base_lib_BaseUtils::nullOrEmpty($licenceurl)){
			$val->addErr("请上传营业执照");
		}
	    if($val->has_err) {
 	   	 	 echo $val->toJsonWithHtml();
 	   	 	 return;
 	   	}

 	   	//$result = $service_licencevalidate->delete(array('validate_id'=>$validate_id));
 	   	//if($result!==false) {
	 	   	$licencevalidate['company_id'] = $this->_userid;
			$result = $service_licencevalidate->saveLicence($licencevalidate,$licenceurl);
			if($result){
				echo json_encode(array('success'=>'重新上传成功'));
			}else {
				echo json_encode(array('error'=>'重新上传失败'));
			}
 	   	//}
	}

	/**
	 *
	 * 营业执照的临时保存
	 * @param object  $inPath
	 */
	public function pageSaveTempLicence($inPath) {
	   $service_licencevalidate = new base_service_company_licencevalidate();
	   // 保存营业执照文件
       $file = $_FILES['Filedata'];
       $arr = $service_licencevalidate->saveTempLicenceValidate($file);
       if($arr!==false) {
          echo json_encode($arr);
       }
	}

	private function getHRManager($heap_id){
		$companyHeapService = new base_service_company_netheap();
		$companyHeap = $companyHeapService->GetNetHeapByID($heap_id, "own_man");
		$userInfor = null;
		if(is_null($companyHeap) || !isset($companyHeap["own_man"])){
			return $userInfor;
		}
		$userService=new base_service_crm_user();
		$userInfor = $userService->GetUsers($companyHeap['own_man'], "user_name,head_photo_url,tel,qq");
		return $userInfor;
	}

	private function GetCustomerService(){
		$customerService = array(
			'name' 		=> '',
			'phone'		=> '',
			'phone400'	=> ''
		);
		$xml = SXML::load('../config/config.xml');
		if(is_null($xml)){
			return $customerService;
		}

		$customerService = array(
			'name' 		=> $xml->CustomerServiceName,
			'phone'		=> $xml->CompanyServicePhone,
			'phone400'	=> $xml->HuiboPhone400
		);
		return $customerService;
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
		$domain["image"] = base_lib_Constant::STYLE_URL;
		$domain["photo"] = $xml->CqjobSysUserHeadPhoto;
		$domain["defaultPhoto"] = $xml->CqjobSysUserDefaultHeadPhoto;
		return $domain;
	}
}
?>
