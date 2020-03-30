<?php
/**
 * @copyright 
 * @name   充值-网银支付
 * @author  ZhangYu
 * @version 2013-10-9
 */
class controller_chinabank extends components_cbasepage{
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct(false);
	}
	
	/**
	 * 充值入口
	 * @param $inPath
	 */
	public function pageIndex($inPath){
		if($this->isLogin() && $this->_usertype=='c') {
			$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
			$validator = new base_lib_Validator();
			$xml = SXML::load('../config/Pay.xml');	
			
			// 网银商户编号
			$v_mid = $xml->ChinaBankMerID;
			// 支付地址
			$v_url = $xml->ChinaBankRequestUrl;
			// 密匙
			$key =  $xml->ChinaBankMerchantKey;
			// 币种
			$v_moneytype = $xml->MoneyType;
			// 回调url
			$callback_url= $xml->ChinaBankCallBack;
			// 自动回调
			$auto_callback_url= $xml->ChinaBankAutoCallBack;
			// 支付金额
			$v_amount = base_lib_BaseUtils::getStr($path_data['txtAmount'],'float',0);
			if($v_amount == 0) {
				echo '获取充值金额失败';
				return;
			}
			
			$v_oid = base_lib_BaseUtils::getStr($path_data['v_oid'],'string',null);
			if(empty($v_oid)) {
				echo '获取订单编号失败';
				return;				
			}			
			$pmode_id = base_lib_BaseUtils::getStr($path_data['bank'],'string',null);

			
			$service_company = new base_service_company_company();
			/* TODO
			$service_charge  = new base_service_company_charge_companycashcharge();
			$enum_chargemode = new base_service_company_charge_rechargemode();
			// 获取ip
			$ip = base_lib_BaseUtils::getIp(0);
			$v_oid = $service_charge->addCompanyCashCharge($this->_userid, $v_amount, $enum_chargemode->chinabank, $ip);
			if($v_oid===false) {
				echo '充值失败';
				return;
			}*/
			$company = $service_company->getCompany($this->_userid,1,'company_id,link_tel');
			// 备注１（充值企业编号）
			$remark1 = $company['company_id'];
			
			$remark2 = $auto_callback_url;
			
			$text = $v_amount.$v_moneytype.$v_oid.$v_mid.$callback_url.$key;        //md5加密拼凑串,注意顺序不能变
	        $v_md5info = strtoupper(md5($text));                                    //md5函数加密并转化成大写字母
	        $this->_aParams['v_mid'] = $v_mid;
	        $this->_aParams['v_oid'] = $v_oid;
	        $this->_aParams['v_amount'] = $v_amount;
	        $this->_aParams['v_moneytype'] = $v_moneytype;
	        $this->_aParams['pmode_id'] = $pmode_id;
	        $this->_aParams['v_url'] = $v_url;
	        $this->_aParams['callback_url'] = $callback_url;
	        $this->_aParams['v_md5info'] = $v_md5info;
	        $this->_aParams['remark1'] = $remark1;
	        $this->_aParams['remark2'] = $remark2;           
	        return $this->render('pay/chinabank/send.html', $this->_aParams);
		}
	}
	
	/**
	 * 
	 * 充值接收页面
	 * @param object $inPath
	 */
	public function pageReceive($inPath) {
		if($this->isLogin() && $this->_usertype=='c') {
			$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
			$validator = new base_lib_Validator();
			$xml = SXML::load('../config/Pay.xml');
			$key=$xml->ChinaBankMerchantKey; //密匙	
																	
			$v_oid  = base_lib_BaseUtils::getStr($path_data['v_oid']);       // 商户发送的v_oid定单编号   
			$v_pmode =base_lib_BaseUtils::getStr($path_data['v_pmode']);     // 支付方式（字符串）   
			$v_pstatus =base_lib_BaseUtils::getStr($path_data['v_pstatus']);   //  支付状态 ：20（支付成功）；30（支付失败）
			$v_pstring =base_lib_BaseUtils::getStr($path_data['v_pstring']);    // 支付结果信息 ： 支付完成（当v_pstatus=20时）；失败原因（当v_pstatus=30时,字符串）； 
			$v_amount =base_lib_BaseUtils::getStr($path_data['v_amount']);     // 订单实际支付金额
			$v_moneytype =base_lib_BaseUtils::getStr($path_data['v_moneytype']); //订单实际支付币种    
			$remark1 =base_lib_BaseUtils::getStr($path_data['remark1' ]);      //备注字段1
			$remark2 =base_lib_BaseUtils::getStr($path_data['remark2' ]);     //备注字段2
			$v_md5str =base_lib_BaseUtils::getStr($path_data['v_md5str' ]);   //拼凑后的MD5校验值  
			$md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key));
			if($md5string == $v_md5str) {
				if($v_pstatus=="20"){
					////支付成功,更新账户金额和充值记录
					$service_charge = new base_service_company_charge_companycashcharge();
					$enum_chargemode = new base_service_company_charge_rechargemode();
					$enum_orderstate = new base_service_company_charge_orderstate();
				    $result	= $service_charge->updateCompanyCashCharge($v_oid,$v_amount,$enum_chargemode->chinabank,$enum_orderstate->noprocess);
					if($result===true) {
						$this->_aParams['title'] = '充值成功';
					    $this->_aParams['issuccess'] = true;
						$this->_aParams['amount'] = $v_amount;	
					}else {
					    $this->_aParams['title'] = '充值失败';
					    $this->_aParams['issuccess'] = false;
						$this->_aParams['amount'] = $result;						
					} 
				}else{
					$this->_aParams['title'] = '充值失败';
					$this->_aParams['issuccess'] = false;
					$this->_aParams['reason'] = $v_pstring;
				}
				return $this->render('pay/chinabank/chargeresult.html', $this->_aParams);			
			}
	   }
		
	}
	
	/**
	 * 
	 * 充值自动接收页面
	 * @param object $inPath
	 */	
	public function pageAutoReceive($inPath) {
	    $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$validator = new base_lib_Validator();
		$xml = SXML::load('../config/Pay.xml');
		$key=$xml->ChinaBankMerchantKey; //密匙												
		$v_oid  = base_lib_BaseUtils::getStr($path_data['v_oid']);       // 商户发送的v_oid定单编号   
		$v_pmode =base_lib_BaseUtils::getStr($path_data['v_pmode']);     // 支付方式（字符串）   
		$v_pstatus =base_lib_BaseUtils::getStr($path_data['v_pstatus']);   //  支付状态 ：20（支付成功）；30（支付失败）
		$v_pstring =base_lib_BaseUtils::getStr($path_data['v_pstring']);    // 支付结果信息 ： 支付完成（当v_pstatus=20时）；失败原因（当v_pstatus=30时,字符串）； 
		$v_amount =base_lib_BaseUtils::getStr($path_data['v_amount']);     // 订单实际支付金额
		$v_moneytype =base_lib_BaseUtils::getStr($path_data['v_moneytype']); //订单实际支付币种    
		$remark1 =base_lib_BaseUtils::getStr($path_data['remark1' ]);      //备注字段1
		$remark2 =base_lib_BaseUtils::getStr($path_data['remark2' ]);     //备注字段2
		$v_md5str =base_lib_BaseUtils::getStr($path_data['v_md5str' ]);   //拼凑后的MD5校验值  

		$md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key));
		$receiveinfo = 'error';
		if($md5string == $v_md5str) {
			if($v_pstatus=="20"){
				//支付成功,更新账户金额和充值记录
				$service_charge = new base_service_company_charge_companycashcharge();
				$enum_chargemode = new base_service_company_charge_rechargemode();
				$enum_orderstate = new base_service_company_charge_orderstate();
			    $result	= $service_charge->updateCompanyCashCharge($v_oid,$v_amount,$enum_chargemode->chinabank,$enum_orderstate->noprocess);   
				if($result===true) {
					$receiveinfo = 'ok';
				}
			}	
		}
		echo $receiveinfo;
		return;		
	}
	
}
?>