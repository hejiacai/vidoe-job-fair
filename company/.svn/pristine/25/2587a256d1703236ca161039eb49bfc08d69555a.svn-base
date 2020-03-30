<?php
/**
 * 
 * @ClassName controller_part
 * @Desc 兼职首页
 * @author huangwentao@huibo.com
 * @date 2015-08-03 上午09:51:47
 */
class controller_partcompany extends components_cbasepage{
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct(true,"part");
	}
	/**
	 *
	 * 企业资料修改
	 * @param object $inPath 参数信息
	 */
	public function pageModify($inPath) {
		
	   	//获取flag
		$inData = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$this->_aParams["flag"] = base_lib_BaseUtils::getStr($inData["flag"]);
   		$service_company =new base_service_company_company();
		$company = $service_company->getCompany($this->_userid,'1','company_id,company_flag,company_name,property_id,size_id,area_id,calling_ids,address,postcode,homepage,info,hr_manager,hr_manager_sex,hr_tel,show_email,linkman,linkman_sex,open_linkman,linkman_station,link_tel,open_tel,fax,open_fax,link_mobile,open_mobile,company_logo_path,company_video_path,company_video_name,email,company_bright_spot,company_shortname');
	//	var_dump($company);
		$this->_aParams['company'] = $company;
		$xml = SXML::load('../config/company/company.xml');
		if (!is_null($xml)) {
			$VirtualName = $xml->VirtualName;//虚拟目录名 CompanyImages
			$LogoFolder  = $xml->LogoFolder;// <!--logo文件夹名-->
		}
		if (base_lib_BaseUtils::nullOrEmpty($company['company_logo_path'])) {
			$this->_aParams['logo'] = base_lib_Constant::STYLE_URL."/img/job/newjob/newJob_57.png";
		} else {
			$this->_aParams['logo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.'/'.$VirtualName.'/'.$LogoFolder.'/'.$company['company_logo_path'];
		}

		//获取企业固定电话信息
		$this->_aParams['phone_infor'] = $this->get_telephone_infor($company["link_tel"]);
		//获取企业视频信息
        //获得企业行业数据
		$calling_service = new base_service_common_calling();
		$calling_ids = $company['calling_ids'];
		$calling_arr = array();
		if (!base_lib_BaseUtils::nullOrEmpty($calling_ids)) {
			$calling_arr = explode(",", $calling_ids);
			if (count($calling_arr) > 2) {
				$calling_arr = array_slice($calling_arr, 0, 2);
			}
		}
		$calling_names = array();
		$this->_aParams['calling_arr'] = $calling_arr;
		if (!base_lib_BaseUtils::nullOrEmpty($calling_arr[0])) {
		   //获得主行业名称
		   $calling_names[0] = $calling_service->getCallingName($calling_arr[0]);
		}
		if (!base_lib_BaseUtils::nullOrEmpty($calling_arr[1])) {
		   //获得主行业名称
		   $calling_names[1] = $calling_service->getCallingName($calling_arr[1]);
		}
        $this->_aParams['calling_names'] = $calling_names;
		$this->_aParams['title'] = '企业资料修改';
		return $this->render('./part/companymodify.html', $this->_aParams);
	}
	
	/**
	 *
	 * 修改企业资料
	 * @param object $inPath 参数信息
	 */
	 public function pageModifyBasicInfo($inPath) {
 	 	$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$base_info = array();
	//	$base_info['company_name'] = base_lib_BaseUtils::getStr($pathdata['company_name'],"string",""); //公司名称
		//获取信息
		$service_company = new base_service_company_company();
		$company = $service_company->getCompany($this->_userid,'1','company_id,company_name');
		$base_info['company_id'] = $this->_userid;
		$base_info['company_name'] = $company['company_name'];
		$base_info['company_shortname'] = base_lib_BaseUtils::getStr($pathdata['company_shortname'],"string",""); //公司简称
		$base_info['calling_ids'] = base_lib_BaseUtils::getStr($pathdata['main_calling'],"string","");//公司主行业
		$base_info['info'] = base_lib_BaseUtils::getStr($pathdata['info'],"string","");//公司介绍
		$base_info['linkman'] = base_lib_BaseUtils::getStr($pathdata['linkman'],"string",1);//联系人
		$base_info['link_mobile'] = base_lib_BaseUtils::getStr($pathdata['link_mobile'],"string","");//联系手机电话
		$zone_infor = base_lib_BaseUtils::getStr($pathdata["zone_infor"],"string","");
		$phone_infor = base_lib_BaseUtils::getStr($pathdata["phone_infor"],"string","");
		$ext_infor = base_lib_BaseUtils::getStr($pathdata["ext_infor"],"string","");
		$base_info["link_tel"] = $phone_infor !="" ? $this->collect_telephone_number($zone_infor,$phone_infor,$ext_infor) :"";
		$base_info['area_id'] = base_lib_BaseUtils::getStr($pathdata['area_id'],"string","");//公司地址
		$base_info['address'] = base_lib_BaseUtils::getStr($pathdata['address'],"string","");
		//验证
	 	$validator = new base_lib_Validator();
		$validator->getNotNull($base_info["company_shortname"],"公司简称不能为空");
		$validator->getStr($base_info['company_shortname'],1,15,"公司简称请输入1-15个字");
		$validator->getNotNull($base_info["calling_ids"],"请选择公司主行业");
		$validator->getNotNull($base_info["info"],"请输入公司简介");
		$validator->getStr($base_info["info"],2,4000,"公司简介请输入2-4000个字");
		$validator->getNotNull($base_info["linkman"],"请输入联系人姓名");
		$validator->getStr($base_info["linkman"],2,6,"联系人请输入2-6个字");
		if(base_lib_BaseUtils::nullOrEmpty($base_info['link_mobile']) && base_lib_BaseUtils::nullOrEmpty($base_info['link_tel'])){
			$validator->addErr("手机号和座机号必填一项");
		}else{
			if(!base_lib_BaseUtils::nullOrEmpty($base_info['link_tel'])){
				//手机非必填
				$validator->getMobile($base_info['link_mobile'],"请填写正确的手机号码",true);
			}else{
				//手机必填
				$validator->getMobile($base_info['link_mobile'],"请填写正确的手机号码");
			}
			if(!base_lib_BaseUtils::nullOrEmpty($base_info["link_mobile"])){
				$validator->getTel($base_info['link_tel'],"请填写正确的座机号码",true);
			}else{
				$validator->getTel($base_info['link_tel'],"请填写正确的座机号码");
			}
		}
		$validator->getNotNull($base_info['address'],'公司地址不能为空');
		$validator->getStr($base_info["address"],2,50,"公司地址请输入2-50个字");
	    $validator->getNotNull($base_info['area_id'],'请选择公司地区');
		if($validator->has_err){
	 		echo $validator->toJsonWithHtml();
	 		return;
	 	}
		//修改
		$service_company = new base_service_company_company();
		$company = $service_company->getCompany($this->_userid,'1','company_id,company_name,property_id,size_id,area_id,calling_ids,address,postcode,homepage,info,hr_manager,hr_manager_sex,hr_tel,email,show_email,linkman,linkman_sex,open_linkman,linkman_station,link_tel,open_tel,fax,open_fax,link_mobile,open_mobile,company_logo_path,company_video_path,company_video_name,company_reward_ids,company_other_reward,company_bright_spot,company_shortname');
		if($company["company_shortname"] != $base_info['company_shortname']){
			//修改审核状态
			$service_shortname_audit = new base_service_company_shortnameaudit();
			$service_shortname_audit->addCompanyAudit($this->_userid,0);
		}
		$update_result = $service_company->updateCompany($base_info,explode(',', $base_info["calling_ids"]));
		if($update_result ===false){
			echo json_encode(array("error"=>"修改失败"));
		}
	 	echo json_encode(array("success"=>true));
	 	return;
	}
	
	//获取座机号码
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
	
		private function get_telephone_infor($telephone){
		$telephone_infor = array('zone'=>'','phone'=>'','ext'=>'');
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
}
?>