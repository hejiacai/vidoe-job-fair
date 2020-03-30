<?php

/**
 * 网络-电子合同签署(签署企业账号)
 * Class contractEcontractAccount.class.php
 * User: zhouwenjun  2019/12/12 10:23
 */
class company_service_contractEcontractAccount extends base_components_baseservice {
	
	protected $marter_table = 'info_oa_contract_econtract_account_id';
	protected $primary_key = 'id';
	
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * 获取网络-电子合同 (签署企业账号)   zhouwenjun 2019/12/11 9:24
	 * @param string $company_id
	 * @param string $company_name
	 * @param string $acceptor_social_code
	 * @param string $item
	 * @return array|bool
	 */
	public function GetEcontractAccountIdByData($company_id = '', $company_name = '', $acceptor_social_code = '', $item = '') {
		if(!$company_name || !$acceptor_social_code) {
			return false;
		}
		
		$company_id and $condition['company_id'] = $company_id;
		$condition['company_name'] = $company_name;
		$condition['acceptor_social_code'] = $acceptor_social_code;
		$condition['is_effect'] = 1;
		
		return $this->selectOne($condition, $item, null, null, null, array ('type' => 'main'));
	}
	
	/**
	 * 添加电子合同信息(签署企业账号)    zhouwenjun 2019/12/11 15:48
	 * @param array $item
	 * @return bool
	 */
	function AddEcontractAccountIdInfo($item = array ()) {
		if(!$item) {
			return false;
		}
		
		return $this->insert($item);
	}
	
	/**
	 * 更新电子合同信息  (签署企业账号)  zhouwenjun 2019/12/11 15:48
	 * @param int   $id
	 * @param array $item
	 * @return bool
	 */
	function UpdateEcontractAccountIdById($id = 0, $item = array ()) {
		if(!$item || !$id) {
			return false;
		}
		$condition['id'] = $id;
		
		return $this->update($condition, $item);
	}
	
	/**
	 * 更新电子合同信息  (签署企业账号)  zhouwenjun 2019/12/11 15:48
	 * @param string $account_id
	 * @param array  $item
	 * @return bool
	 */
	function UpdateEcontractAccountIdByAccountId($account_id = '', $item = array ()) {
		if(!$item || !$account_id) {
			return false;
		}
		$condition['account_id'] = $account_id;
		
		return $this->update($condition, $item);
	}
}
