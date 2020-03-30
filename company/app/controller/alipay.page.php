<?php
/**
 * 
 * @ClassName controller_alipay 
 * @Desc 充值-支付宝
 * @author jiangchenglin@huibo.com
 * @date 2013-10-9 上午11:00:58
 */
class controller_alipay extends components_cbasepage{
	
	var $alipay_config;
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct(false);
	}
	
	/**
	 * 支付宝入口
	 * @param $inPath
	 */
	function pageIndex($inPath){
		if($this->isLogin() && $this->_usertype=='c') {
			$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
			
			$alipay_config = base_service_common_pay_alipay_commonfunction::setConfig();
			$xml = $alipay_config['xml'];
			//支付类型
	        $payment_type = "1";
	        //必填，不能修改
	        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
	        //服务器异步通知页面路径
	        $notify_url = $xml->AliPayNotifyUrl;
	        //需http://格式的完整路径，不能加?id=123这类自定义参数
	        //页面跳转同步通知页面路径
	        $return_url = $xml->AliPayReturnUrl;
	        //签约支付宝账号或卖家支付宝帐户
			$seller_email = $xml->AliPaySellerEmail;
	        //订单名称
	        $subject = '重庆聚焦人才网账户充值';
	        
	        //付款金额
	        $total_fee = base_lib_BaseUtils::getStr($path_data['txtAmount'],'float',0);
			if($total_fee == 0) {
				echo header("Content-type:text/plain;charset=utf-8");
				echo '获取充值金额失败';
				return;
			}
			if ($total_fee < 0 || $account > 9999999) {
				echo header("Content-type:text/plain;charset=utf-8");
				echo "请输入正确的充值金额！";
				return;
			}
			$order_no = base_lib_BaseUtils::getStr($path_data['v_oid'],'string',null);
			if(empty($order_no)) {
				echo '获取订单编号失败';
				return;				
			}			
			$service_company = new base_service_company_company();
			/*
			$service_charge  = new base_service_company_charge_companycashcharge();
			$enum_chargemode = new base_service_company_charge_rechargemode();
			
			// 获取ip
			$ip = base_lib_BaseUtils::getIp(0);
			$order_no = $service_charge->addCompanyCashCharge($this->_userid, $total_fee, $enum_chargemode->alipay, $ip);
			if($order_no===false) {
				echo header("Content-type:text/plain;charset=utf-8");
				echo '充值失败';
				return;
			}*/
			//商户网站订单系统中唯一订单号，必填
	        $out_trade_no = $order_no;
	        
	        //订单描述
	        $body = '';
	        //商品展示地址
	        $show_url = '';
	        //需以http://开头的完整路径，例如：http://www.xxx.com/myorder.html
	        
	        //建立请求
			$alipay_submit = new base_service_common_pay_alipay_alipaysubmit($alipay_config);
	        
	        //防钓鱼时间戳
	        $anti_phishing_key = '';
	        //$anti_phishing_key = $alipay_submit->query_timestamp();
	        //若要使用请调用类文件submit中的query_timestamp函数
	        //客户端的IP地址
	        $exter_invoke_ip = base_lib_BaseUtils::getIp(0);
	        //非局域网的外网IP地址，如：221.0.0.1
	
			
			//构造要请求的参数数组，无需改动
			$parameter = array(
					"service" => "create_direct_pay_by_user",
					"partner" => trim($alipay_config['partner']),
					"payment_type"	=> $payment_type,
					"notify_url"	=> $notify_url,
					"return_url"	=> $return_url,
					"seller_email"	=> $seller_email,
					"out_trade_no"	=> $out_trade_no,
					"subject"	=> $subject,
					"total_fee"	=> $total_fee,
					"body"	=> $body,
					"show_url"	=> $show_url,
					"anti_phishing_key"	=> $anti_phishing_key,
					"enable_paymethod"=>"directPay^bankPay^creditCardExpress^debitCardExpress",
					"exter_invoke_ip"	=> $exter_invoke_ip,
					"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
			);
			$html_text = $alipay_submit->buildRequestForm($parameter,"get", "确认");
			echo header("Content-Type: text/html; charset=utf-8");
			echo $html_text;
		}
	}
	
	/**
	 * ：支付宝服务器异步通知
	 * @param $inPath
	 */
	function pageNotify($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$alipay_config = base_service_common_pay_alipay_commonfunction::setConfig();
		//计算得出通知验证结果
		$alipay_notify = new base_service_common_pay_alipay_alipaynotify($alipay_config);
		$verify_result = $alipay_notify->verifyNotify();
		if($verify_result) {
			//验证成功
			
			//获取支付宝的通知返回参数
			//支付宝交易号
			$trade_no = base_lib_BaseUtils::getStr($path_data['trade_no'],'string','');
			//商户订单号
			$out_trade_no =  base_lib_BaseUtils::getStr($path_data['out_trade_no'],'string','');
			//交易状态
			$trade_status = base_lib_BaseUtils::getStr($path_data['trade_status'],'string','');
			//交易金额
			$total_fee = base_lib_BaseUtils::getStr($path_data['total_fee'],'float',0);
			//商品名称、订单名称
			$subject = base_lib_BaseUtils::getStr($path_data['subject'],'string','');
			//商品描述、订单备注、描述
			$body = base_lib_BaseUtils::getStr($path_data['body'],'string','');

			if($trade_status == 'TRADE_FINISHED'||$trade_status == 'TRADE_SUCCESS') {
				$service_charge = new base_service_company_charge_companycashcharge();
				$enum_chargemode = new base_service_company_charge_rechargemode();
				$enum_orderstate = new base_service_company_charge_orderstate();
				$total_fee = floatval($total_fee);
			    $result	= $service_charge->updateCompanyCashCharge($out_trade_no,$total_fee,$enum_chargemode->alipay,$enum_orderstate->noprocess);	
			}
			//$this->_aParams['title'] = '充值成功';
			//$this->_aParams['issuccess'] = $result;
			//$this->_aParams['amount'] = $total_fee;
			//return $this->render('pay/chinabank/chargeresult.html', $this->_aParams);	   
		    //echo "success";	
		}else {
			//$this->_aParams['title'] = '充值失败';
			//$this->_aParams['issuccess']  =false;	
			//$this->_aParams['reason']  = '';
			//return $this->render('pay/chinabank/chargeresult.html', $this->_aParams);	   
		    //验证失败
		   // echo "fail";
		    //调试用，写文本函数记录程序运行情况是否正常
		    $out_trade_no =  base_lib_BaseUtils::getStr($path_data['out_trade_no'],'string','');
		    SlightPHP::log("支付宝异步通知验证失败，商户订单号：".$out_trade_no);
		}
	}
	
	/**
	 * 支付宝页面跳转同步通知
	 * @param unknown_type $inPath
	 */
	function pageCallback($inPath){
		
		if($this->isLogin() && $this->_usertype=='c') {
			$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
			$alipay_config = base_service_common_pay_alipay_commonfunction::setConfig();
			//计算得出通知验证结果
			$alipay_notify = new base_service_common_pay_alipay_alipaynotify($alipay_config);
			$verify_result = $alipay_notify->verifyReturn();
			if($verify_result) {
				//验证成功
				//支付宝交易号
				$trade_no = base_lib_BaseUtils::getStr($path_data['trade_no'],'string','');
				//商户订单号
				$out_trade_no =  base_lib_BaseUtils::getStr($path_data['out_trade_no'],'string','');
				//交易状态
				$trade_status = base_lib_BaseUtils::getStr($path_data['trade_status'],'string','');
				//交易金额
				$total_fee = base_lib_BaseUtils::getStr($path_data['total_fee'],'float',0);
				//商品名称、订单名称
				$subject = base_lib_BaseUtils::getStr($path_data['subject'],'string','');
				//商品描述、订单备注、描述
				$body = base_lib_BaseUtils::getStr($path_data['body'],'string','');
			
			    if($trade_status == 'TRADE_FINISHED') {
					$service_charge = new base_service_company_charge_companycashcharge();
					$enum_chargemode = new base_service_company_charge_rechargemode();
					$enum_orderstate = new base_service_company_charge_orderstate();
					$total_fee = floatval($total_fee);
				    $result	= $service_charge->updateCompanyCashCharge($out_trade_no,$total_fee,$enum_chargemode->alipay,$enum_orderstate->noprocess);	
					if($result===true){
				    	$this->_aParams['title'] = '充值成功';
						$this->_aParams['issuccess'] = true;
						$this->_aParams['amount'] = $total_fee;
					}else{
						$this->_aParams['title'] = '充值失败';
						$this->_aParams['issuccess'] = false;
						$this->_aParams['reason'] = $result;
					}
					return $this->render('pay/chinabank/chargeresult.html', $this->_aParams);
			    }
			    else {
			    	$this->_aParams['title'] = '充值失败';
					$this->_aParams['issuccess'] = false;
					$this->_aParams['reason'] = $trade_status;
					return $this->render('pay/chinabank/chargeresult.html', $this->_aParams);
			      //echo "trade_status=".$trade_status;
			    }
				//echo "验证成功<br />";
	
			}else {
				$this->_aParams['title'] = '充值失败';
				$this->_aParams['issuccess'] = false;
				$this->_aParams['reason'] = $trade_status;
				SlightPHP::log("支付宝同步通知验证失败，商户订单号：".$out_trade_no);
				return $this->render('pay/chinabank/chargeresult.html', $this->_aParams);
			    //验证失败
			    //如要调试，请看alipay_notify.php页面的verifyReturn函数
			    //echo "验证失败";
			}
		}
	}
}
?>