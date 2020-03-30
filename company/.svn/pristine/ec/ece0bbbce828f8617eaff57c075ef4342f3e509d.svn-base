<?php
/** 
 * @ClassName 保证金相关操作类
 * @Desc 保证金相关操作类
 * @author chenwei@huibo.com
 * @date 2015-8-10 上午10:03:00 
 * 
 * tags 
*/


class controller_partinsurefee extends components_cbasepage {
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct(true);
	}
	
	
	
	/**
	 *@Desc todo
	 *@return type_name
	*/
	public function pageIndex($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		
		//检查是否已经缴纳保证金
		$service_partcompany = new base_service_part_company_partcompany();
		$partcompany = $service_partcompany->getCompanyPartInfo($this->_userid, "is_insure_fee,insure_fee");
		
		if(!base_lib_BaseUtils::nullOrEmpty($partcompany)){
			$this->_aParams = $partcompany;
		}
		
		return $this->render("./part/insurefeepay.html", $this->_aParams);
	}
}