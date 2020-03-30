<?php
/**
 * 
 * @ClassName controller_yeepay 
 * @Desc 充值-易宝
 * @author liuchang@huibo.com
 * @date 2013-10-9 下午03:09:11 
 *
 */
class controller_yeepay extends components_cbasepage{
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct(false);
	}
	
	/**
	 * 
	 * 易宝入口
	 * @param $inPath
	 */
	function pageIndex($inPath){
		if($this->isLogin() && $this->_usertype=='c') {
			$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
			$account = base_lib_BaseUtils::getStr($path_data['txtAmount'],'float',0);
			if ($account < 0.01 || $account > 9999999) {
				echo header("Content-type:text/plain;charset=utf-8");
				echo "请输入正确的充值金额！";
				return;
			}
			//添加单位现金账户历史并返回订单编号
			$enum_chargemode = new base_service_company_charge_rechargemode();
			$service_companycashcharge = new base_service_company_charge_companycashcharge();
			//获取ip
			$ip = base_lib_BaseUtils::getIp(0);
			$p2_Order = $service_companycashcharge->addCompanyCashCharge($this->_userid, $account, $enum_chargemode->yeepay, $ip);
			if ($p2_Order === false) {
				echo header("Content-type:text/plain;charset=utf-8");
				echo "充值失败！";
				return;
			}
			
			$domain_path = "";
	      	if ($_SERVER["SERVER_PORT"] == "80"){
				$domain_path = "http://" . $_SERVER["SERVER_NAME"];
	 		}else{
	    		$domain_path = "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
	  		}
			//订单号，支付金额，商品名称，回传地址，商户信息，是否需要应答机制
			$doc = new DOMDocument();
			$doc->load('../config/Pay.xml');
			$xml = simplexml_import_dom($doc);
			$p1_MerId = $xml->YeePayMerID;
			$p4_Cur = "CNY";
			$p5_Pid = iconv("UTF-8", "GB2312", "在线支付");
			
			//易宝回调页面地址
	    	$pay_call_back_url = "{$domain_path}{$xml->YeePayCallBack}";
			$p8_Url = $pay_call_back_url;
	  		$param = array(
	  			"p0_Cmd" => "Buy",
	  			"p1_MerId" => $p1_MerId,
	  			"p2_Order" => $p2_Order,
	  			"p3_Amt" => $account,
	  			"p4_Cur" => $p4_Cur,
	  			"p5_Pid" => $p5_Pid,
	  			"p6_Pcat" => "",
	  			"p7_Pdesc" => "",
	  			"p8_Url" => $p8_Url,
	  			"p9_SAF" => "0",
	  			"pa_MP" => "cqjob",
	  			"pd_FrpId" => "",
	  			"pr_NeedResponse" => "1",
	  			"hmac" => base_service_common_pay_yeepay_yeepaycommon::getReqHmacString($p2_Order, $account, $p4_Cur, $p5_Pid, "", "", $p8_Url, "cqjob", "", "1"),
	  			"yeepayrequesturl" => $xml->YeePayRequestUrl
	  		);
	  		//var_dump($param);
	  		$this->_aParams['param'] = $param;
	  		return $this->render('pay/yeepay/index.html', $this->_aParams);
		}
	}
	
	/**
	 * 
	 * 易宝回调方法
	 * @param $inPath
	 */
	function pageCallback($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$r0_Cmd = base_lib_BaseUtils::getStr($path_data["r0_Cmd"],"string","");
		$r1_Code = base_lib_BaseUtils::getStr($path_data["r1_Code"],"string","");
		$r2_TrxId = base_lib_BaseUtils::getStr($path_data["r2_TrxId"],"string","");
		$r3_Amt = base_lib_BaseUtils::getStr($path_data["r3_Amt"],"float",0);
		$r4_Cur = base_lib_BaseUtils::getStr($path_data["r4_Cur"],"string","");
		$r5_Pid = base_lib_BaseUtils::getStr($path_data["r5_Pid"],"string","");
		//$r5_Pid = iconv("UTF-8", "GB2312", $r5_Pid);
		$r6_Order = base_lib_BaseUtils::getStr($path_data["r6_Order"],"long",0);
		$r7_Uid = base_lib_BaseUtils::getStr($path_data["r7_Uid"],"string","");
		$r8_MP = base_lib_BaseUtils::getStr($path_data["r8_MP"],"string","");
		$r9_BType = base_lib_BaseUtils::getStr($path_data["r9_BType"],"string","");
		$hmac = base_lib_BaseUtils::getStr($path_data["hmac"],"string","");
		
		//判断返回签名是否正确
		$breq = base_service_common_pay_yeepay_yeepaycommon::CheckHmac($r0_Cmd, $r1_Code, $r2_TrxId, $r3_Amt, $r4_Cur, $r5_Pid, $r6_Order, $r7_Uid, $r8_MP, $r9_BType, $hmac);
		if ($breq) {
			if ($r1_Code == '1' && $r3_Amt != 0) {
				$enum_chargemode = new base_service_company_charge_rechargemode();
				$enum_orderstate = new base_service_company_charge_orderstate();
				$service_companycashcharge = new base_service_company_charge_companycashcharge();
				$result	= $service_companycashcharge->updateCompanyCashCharge($r6_Order, $r3_Amt, $enum_chargemode->yeepay, $enum_orderstate->noprocess);	
				if ($result !== true) {
					$this->_aParams['title'] = '充值失败';
				    $this->_aParams['issuccess'] = false;
					$this->_aParams['amount'] = $result;
					return $this->render('pay/chinabank/chargeresult.html', $this->_aParams);
				}
			}
			if ($r9_BType == '1') {
				if($this->isLogin() && $this->_usertype=='c') {
					//在线支付页面返回
	            	//跳转到企业充值成功界面
					$this->_aParams['title'] = '充值成功';
				    $this->_aParams['issuccess'] = true;
					$this->_aParams['amount'] = $r3_Amt;
				    return $this->render('pay/chinabank/chargeresult.html', $this->_aParams);
				}
			}elseif ($r9_BType == '2'){
				//如果需要应答机制则必须回写以"success"开头的stream,大小写不敏感
             	//易宝支付收到该stream，便认为商户已收到；否则将继续发送通知，直到商户收到为止
				echo "success";
				return;
			}
		} else {
			if($this->isLogin() && $this->_usertype=='c') {
				$this->_aParams['title'] = '充值失败';
				$this->_aParams['issuccess'] = false;
				$this->_aParams['reason'] = '交易信息被篡改';
				return $this->render('pay/chinabank/chargeresult.html', $this->_aParams);
			}
		}
	}
}
?>