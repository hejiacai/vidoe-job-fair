<?php
/**
 * @copyright 2002-2014 www.huibo.com
 * @name 企业招聘中心接口
 * @author ZhangYu
 * @version 2014-02-12
 */

class controller_interface extends components_cbasepage {
	function __construct() {
		parent::__construct(false);
	}
	
	/**
	 * 设置求职申请的已读状态
	 * @param unknown_type $inPath
	 */
	public function pageSetApplyRead($inPath) {
		$param = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$encrypt_applyid = base_lib_BaseUtils::getStr($param['key']);
		if(!base_lib_BaseUtils::nullOrEmpty($encrypt_applyid)) {
			$xml = SXML::load('../config/company/company.xml');
			$service_SNumCrypt = new SNumCrypt();
			$apply_id = $service_SNumCrypt->decrypt($encrypt_applyid, $xml->ApplyIDKey);
			$service_apply = new base_service_company_resume_apply();
			$result = $service_apply->setRead(array ($apply_id));
		}
		
	}
	
	/**
	 *  微信支付回调 PC
	 */
	function pageWXChargeRespond() {
		require_once(PLUGINS_DIR . '/pay/WxPay/HbWxPay.Config.php');
		require_once(PLUGINS_DIR . '/pay/WxPay/WxPay.Notify.php');
		$service_company_charge_companycashcharge = new base_service_company_charge_companycashcharge();
		
		$Wx_config = new HbWxPayConfig();
		$Wx_PayNotify = new WxPayNotify();
		$Wx_PayNotify->Handle($Wx_config, array ($service_company_charge_companycashcharge, 'WXReChargeRespond'));
	}
	
	/**
	 * 读取 电子合同文件    zhouwenjun 2019/12/17 15:46
	 */
	function pageGetContractEcontractSignFile($inPath) {
		$param = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$key = base_lib_BaseUtils::getStr($param['key']);
		$allowExt = ['pdf'];//允许下载文件
		
		$ser_SAESmcrpt = new SAESmcrpt();
		$pdf_file = json_decode($ser_SAESmcrpt->decode($key), true)['pdf_file'];
		
		ob_clean();
		//检测下载文件是否存在 并且可读
		if(!is_file($pdf_file) && !is_readable($pdf_file)) {
			die('文件不存在');
		}
		//检测文件类型是否允许下载
		$ext = strtolower(pathinfo($pdf_file, PATHINFO_EXTENSION));
		if(!in_array($ext, $allowExt)) {
			return false;
		}
		//设置头信息
		//声明浏览器输出的是字节流
		header('Content-Type: application/octet-stream');
		//声明浏览器返回大小是按字节进行计算
		header('Accept-Ranges:bytes');
		//告诉浏览器文件的总大小
		$fileSize = filesize($pdf_file);//坑 filesize 如果超过2G 低版本php会返回负数
		header('Content-Length:' . $fileSize); //注意是'Content-Length:' 非Accept-Length
		//声明下载文件的名称
		header('Content-Disposition:attachment;filename=' . basename($pdf_file));//声明作为附件处理和下载后文件的名称
		//获取文件内容
		$handle = fopen($pdf_file, 'rb');//二进制文件用‘rb’模式读取
		while (!feof($handle)) { //循环到文件末尾 规定每次读取（向浏览器输出为$readBuffer设置的字节数）
			echo fread($handle, 1024);
		}
		fclose($handle);//关闭文件句柄
		die;
	}
	
	function pageSendMsg() {
		$company_service_contractEcontract = new company_service_contractEcontract();
		$econtract_data = $company_service_contractEcontract->GetEcontractDataById(14);
		//甲方完成签章后，系统消息通知签订人销售
		//你对（986987）重庆测试有限公司发起的电子合同已经完成签章，请注意查看
		if($econtract_data['signer']) {
			$ser_user = new base_service_boss_user();
			$re_msg_html = <<<HTML
你对（{$econtract_data['company_id']}）{$econtract_data['acceptor_signer']}发起的电子合同已经完成签章，请注意查看。
HTML;
			$re = $ser_user->sendMsg("business_audit", array ($econtract_data['signer']), '电子合同已经完成签章', $re_msg_html, null);
			var_dump($re,$re_msg_html);
		}
		
		var_dump($econtract_data);
	}
}

?>
