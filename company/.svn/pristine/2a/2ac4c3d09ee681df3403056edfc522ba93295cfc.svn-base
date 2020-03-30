<?php
class controller_cmscompany extends components_cbasepage {

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}

	public function pageIndex($inPath)
	{
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $service_company = new cs_service_company();
        $base_service_company = base_service_company_company::getInstances();
        
        $company = $service_company->getCompany($this->_userid);
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid);
        $company_resource_info = $company_resources->getCompanyServiceSource(["cq_pricing_resource"]);
		$base_company = $base_service_company->getCompany($this->_userid, 1, 'company_id,company_flag,'
			. 'company_name,property_id,size_id,area_id,calling_ids,address,postcode,is_audit,'
			. 'homepage,info,hr_manager,hr_manager_sex,hr_tel,show_email,linkman,linkman_sex,'
			. 'open_linkman,linkman_station,link_tel,open_tel,fax,open_fax,link_mobile,'
			. 'open_mobile,email,'
			. 'company_bright_spot,company_shortname,company_reward_ids,company_other_reward');
        
        if(!empty($company)){
            $this->_aParams['comsite_str'] = "网站管理";
        }else{
            $this->_aParams['comsite_str'] = "立即免费开通";
        }
        if($company_resource_info['isCqNewService']){
            $this->_aParams['is_free_wordpress'] = $company_resource_info['cq_is_free_wordpress'];
        }else{
            $this->_aParams['is_free_wordpress'] = 1;
        }

        $this->_aParams['is_audit'] = $base_company["is_audit"];
		return $this->render('cmscompany.html',$this->_aParams);
	}

}
?>