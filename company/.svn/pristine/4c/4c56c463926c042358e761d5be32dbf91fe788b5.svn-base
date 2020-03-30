<?php

class company_service_contractrevert extends base_components_baseservice {

	protected $marter_table = 'info_oa_contractrevert';
	protected $primary_key = 'contractrevert_id';
	protected $dbinfo = 'oa_new';

	public function __construct() {
		parent::__construct();
	}
    //电子合同归还
    public function addContractRevert($contract_code){
        if(base_lib_BaseUtils::nullOrEmpty($contract_code)){
            return false;
        }
        $info['contract_code'] = $contract_code;
        $info['origin_type'] = 1;
        $info['revert_date'] = date('Y-m-d H:i:s',time());

       return  $re = $this->insert($info);
    }

}