<?php
/**
 * @copyright 2002-2014 www.huibo.com
 * @name 企业兼职接口
 * @author ZhangYu
 * @version 2014-02-12
*/

class controller_partinterface extends components_cbasepage  {
	function __construct() {
		parent::__construct(false);
	}


	#region
	/**
	 *  微信支付回调 PC
	 */
	function pageWXChargerRespond() {
		require_once(PLUGINS_DIR . '/pay/WxPay/JzWxPay.Config.php');
		require_once(PLUGINS_DIR . '/pay/WxPay/WxPay.Notify.php');
		$service_part_order_partorder = new base_service_part_order_partorder();

		$Wx_config = new JzWxPayConfig();
		$Wx_PayNotify = new WxPayNotify();
		$Wx_PayNotify->Handle($Wx_config, array ($service_part_order_partorder, 'WXChargerRespond'));
	}

	/**
	 * 支付宝支付回调 PC    zhouwenjun 2018/10/8 11:32
	 */
	function pageAlipayChargerRespond() {
		SlightPHP::log('partinterface_支付宝支付回调记录日志11_AlipayChargerRespond:' . print_r($_POST, true));
		$re_data = $_POST;
		if (!$re_data['trade_no'] || !$re_data['out_trade_no']) {
			die('支付宝支付回调错误!');
		}
		//获取锁
		$ser_redis_lock = new base_service_redis_redisLock();
		$lock_name = "PartAlipayChargerRespond:{$re_data['out_trade_no']}:{$re_data['trade_no']}";
		$lock_auth = $ser_redis_lock->acquire_lock($lock_name);
		if (!$lock_auth) {
			echo 'fail';
			return;
		}
		require_once(PLUGINS_DIR . '/pay/AliPay/f2fpay/service/AlipayTradeService.php');
		require PLUGINS_DIR . '/pay/AliPay/f2fpay/config/config.php';
		$notify_url = base_lib_Constant::COMPANY_URL_NO_HTTP . "/partinterface/AlipayChargerRespond";
		//$notify_url = base_lib_Constant::APP_MOBILE_URL . "/hbResumeModelOrder/AlipayChargerRespond";
		$config['notify_url'] = $notify_url;
		$qrPay = new AlipayTradeService($config);
		$aop = new AopClient ();
		$aop->gatewayUrl = $qrPay->gateway_url;
		$aop->appId = $qrPay->appid;
		$aop->signType = $qrPay->sign_type;
		$aop->rsaPrivateKeyFilePath = $qrPay->private_key;
//		$aop->rsaPrivateKey = $this->private_key;
		$aop->alipayPublicKey = $qrPay->alipay_public_key;
//		$aop->alipayrsaPublicKey = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCsILArSI5LniKprGU7HtHT5Pkdsj63tU+o67UnSqSI9EBqott1cBJEhTCFil0KlTt2d30eJrMkYigijXzlEIxZ8p77kqlcICal2uWq+brD286aZcbYjJd+lhGezk1RA4ByVCpUIJ9y0e8uSdlOWl6JMGIqGqddyuYnKDSDKaXG6QIDAQAB";
		$aop->apiVersion = "1.0";
		$aop->postCharset = $qrPay->charset;
		$aop->format = $qrPay->format;
		if ($qrPay->appid != $re_data['auth_app_id']) {
			echo 'fail';
			SlightPHP::log('partinterface_支付宝支付回调记录日志_商户号不一致:' . print_r($qrPay, true), print_r($re_data, true));

			return;
		}

		$result = $aop->rsaCheckV1($re_data, $qrPay->alipay_public_key, $qrPay->sign_type);
		SlightPHP::log("result".$result."sign_type:".$qrPay->sign_type."alipay_public_key:".$qrPay->alipay_public_key."re_data:".print_r($re_data,true));
		if ($result) {
			$transaction_id = $re_data['trade_no'];
			$order_no = $re_data['out_trade_no'];
			$total_fee = abs(floatval($re_data['total_amount']));

			$service_part_order_partorder = new base_service_part_order_partorder();
			$rse_order = $service_part_order_partorder->orderSuccessPay($re_data, $transaction_id, $order_no, $total_fee, $msg);
			if ($rse_order) {
				echo 'success';exit();
			}
			SlightPHP::log('partinterface_支付宝支付回调记录日志_更新失败:' . print_r($msg, true) . print_r($re_data, true));
		} else {
			echo 'fail';
			SlightPHP::log('partinterface_支付宝支付回调记录日志_验证失败:'.$qrPay->sign_type . print_r($re_data, true));
		}
		$ser_redis_lock->release_lock($lock_name, $lock_auth);

		return;
	}
}
?>