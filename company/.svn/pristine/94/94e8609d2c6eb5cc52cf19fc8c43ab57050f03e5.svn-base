<?php

/**
 *
 * @name 账户充值
 * @author  ZhangYu
 * @version 2013-10-09
 */
class controller_pay extends components_cbasepage {
	
	function __construct() {
		parent::__construct();
	}
	
	public function pageIndex($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$src = base_lib_BaseUtils::getStr($path_data['src'], 'string', '');
		$allot = base_lib_BaseUtils::getStr($path_data['allot'], 'int', 0);
		
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, base_lib_BaseUtils::getCookie('accountid'));
		$resource_data = $company_resources->getCompanyServiceSource(["_account_resource"]);
		$spread_overage = $resource_data['spread_overage'];
		$account_overage = $resource_data['account_overage'];
		
		// 获取当前用户剩余金额
		$amount = $account_overage;
		
		$xml = SXML::load('../config/config.xml');
		$this->_aParams['title'] = "在线充值 我的账户-{$xml->HuiBoSiteName}";
		$this->_aParams['amount'] = number_format($amount, 2);
		$this->_aParams['src'] = $src;
		
		$xml = SXML::load('../config/config.xml');
		$tel_head = "023-61627888";
		if (!is_null($xml)) {
			$tel_head = $xml->TechniquePhone;
		}
		$easy_tel_head = str_replace("-", "", $tel_head);
		$this->_aParams['tel_head'] = $tel_head;
		$this->_aParams['easy_tel_head'] = $easy_tel_head;
		
		//获取招聘顾问
		$companyStateService = new base_service_company_comstate();
		$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
		$hrManager = $this->GetHRManager($companyState["net_heap_id"]);
		
		//获取客服员
		//$customeruser = $this->GetCustomerService($this->_userid);
		//$this->_aParams['hasCustomer']  = false;
		$this->_aParams["hasHRManager"] = false;
		
		$domain = $this->GetDomainInfor();
		if (!is_null($hrManager)) {
			$headPhoto = base_lib_BaseUtils::nullOrEmpty($hrManager["head_photo_url"]) ? $domain["defaultPhoto"] : $hrManager["head_photo_url"];
			$hrManager["head_photo_url"] = $domain["image"] . "/" . $domain["photo"] . "/" . $headPhoto;
			
			$this->_aParams["hasHRManager"] = true;
			$this->_aParams["hrManager"] = $hrManager;
		}
		
		#region 优惠劵购买服务
		$this->_aParams['allot'] = $allot;
		if ($allot) {
			//现金券
			$ser_allotCouponCash = new base_service_company_coupon_allotCouponCash();
			$get_where_data = array (
				'allot_state' => 2,
				'state'       => 1,
				'company_id'  => $this->_userid,
				'date'        => $this->_ymd,
			);
			$allotCouponCash_data = $ser_allotCouponCash->getAllotCouponCash($get_where_data)->items;
			$this->_aParams['allotCouponCash_data'] = $allotCouponCash_data;
			
			
			return $this->render('pay/v2_allot_account.html', $this->_aParams);
		}
		
		#endregion
		
		return $this->render('pay/v2accountrecharge.html', $this->_aParams);
	}
	
	private function GetDomainInfor() {
		$domain = array (
			'image'        => '',
			'photo'        => '',
			'defaultPhoto' => '',
		);
		$xml = SXML::load('../config/config.xml');
		if (is_null($xml)) {
			return $domain;
		}
		$domain["image"] = $xml->ImgDomain;
		$domain["photo"] = $xml->CqjobSysUserHeadPhoto;
		$domain["defaultPhoto"] = $xml->CqjobSysUserDefaultHeadPhoto;
		
		return $domain;
	}
	
	
	// 获取客服人员
	// private function GetCustomerService($companyid) {
	//     $customerbelongService = new base_service_crm_netcompanycustomerbelong();
	//     $customerbelong =  $customerbelongService->getCustomerbelongById($companyid, 'net_heap_id');
	//     if(empty($customerbelong)) {
	//         return null;
	//     }
	//     $customerheapservice = new base_service_crm_netcustomerheap();
	//     $customerheap = $customerheapservice->getCustomerheapById($customerbelong['net_heap_id'], 'own_man');
	//     if(empty($customerheap)) {
	//         return null;
	//     }
	//     $userService=new base_service_crm_user();
	//     $userInfor = $userService->GetUsers($customerheap['own_man'], "user_name,head_photo_url,tel,mobile,qq");
	//     return $userInfor;
	// }
	
	// 获取HR顾问
	private function GetHRManager($heap_id) {
		$companyHeapService = new base_service_company_netheap();
		$companyHeap = $companyHeapService->GetNetHeapByID($heap_id, "own_man");
		$userInfor = null;
		if (is_null($companyHeap) || !isset($companyHeap["own_man"])) {
			return $userInfor;
		}
		$userService = new base_service_crm_user();
		$userInfor = $userService->GetUsers($companyHeap['own_man'], "user_name,head_photo_url,tel,mobile,qq");
		
		return $userInfor;
	}
	
	/**
	 * 添加订单
	 * @param $inPath
	 */
	public function pageAddOrder($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$is_com_service = base_lib_BaseUtils::getStr($path_data['is_com_service'], 'int', 0);
		
		$validator = new base_lib_Validator();
		// 支付方式
		$enum_chargemode = new base_service_company_charge_rechargemode();
		$type = $validator->getEnum($path_data['type'], $enum_chargemode->getApplyStatus(), '请选择支付方式');
		// 支付金额
		$v_amount = $validator->getNotNull($path_data['txtAmount'], '请填写充值金额');
		if (floatval($v_amount) < 0.01) {
			$validator->addErr('充值金额不能低于0.01元');
		}
		if ($type == $enum_chargemode->chinabank) {
			$bank = base_lib_BaseUtils::getStr($path_data['bank'], 'int', 0);
		}
		
		//优惠劵--现金券使用
		$allot_cash_id = base_lib_BaseUtils::getStr($path_data['allot_cash_id'], 'int');
		$allot_cash_money = base_lib_BaseUtils::getStr($path_data['allot_cash_money'], 'float');
		if ($allot_cash_id) {
			$ser_allotCouponCash = new base_service_company_coupon_allotCouponCash();
			$allotCouponCash_data = $ser_allotCouponCash->GetAllotCouponCashUnusedByIDCompanyId($this->_userid, $allot_cash_id);
			if (!$allotCouponCash_data || !$allotCouponCash_data['id']) {
				$validator->addErr('现金券不存在或已使用!');
			}
			if ($allotCouponCash_data['consume'] > 0 && $allotCouponCash_data['consume'] > $v_amount) {
				$validator->addErr("该现金券最低消费{$allotCouponCash_data['consume']}元!");
			}
			if ($allotCouponCash_data['money'] > $v_amount) {
				$validator->addErr("该现金券抵扣金额大于充值金额!");
			}
			if ($allotCouponCash_data['money'] != $allot_cash_money) {
				$validator->addErr("该现金券抵扣金额错误,请刷新后重试!");
			}
			$ser_allotCouponBusiness = new base_service_company_coupon_allotCouponBusiness();
			$cash_business_data = $ser_allotCouponBusiness->GetAllotCashDataByCashId($allot_cash_id, 'a.id');
			if ($cash_business_data && $cash_business_data['id']) {
				$validator->addErr("您的招聘顾问正在帮你使用该优惠券!");
			}
			
			$v_amount = $v_amount - $allotCouponCash_data['money'];
		}
		
		if ($validator->has_err) {
			echo header("Content-type:text/plain;charset=utf-8");
			echo $validator->toJsonWithHtml();
			
			return;
		}
		
		// 添加充值订单
		$service_charge = new base_service_company_charge_companycashcharge();
		$ip = base_lib_BaseUtils::getIp(0);
//        $order = false;
//        if($type == $enum_chargemode->weixinpay){
//            $order = $service_charge->getInTimeOrder($this->_userid, $type);
//        }

//        if($order && isset($order['order_no']) && $order['order_no'])
//            $v_oid = $order['order_no'];
//        else
		$v_oid = $service_charge->addCompanyCashCharge($this->_userid, $v_amount, $type, $ip, $allot_cash_id, $is_com_service);
		
		//添加现金券使用订单
		if ($allot_cash_id && $v_oid) {
			$up_allot_cash_data = array (
				'used_type'    => 2,
				'pay_order_no' => $v_oid,
			);
			$ser_allotCouponCash->updateAllotCouponCash($allot_cash_id, $up_allot_cash_data);
		}
		
		if ($v_oid === false) {
			echo json_encode(array ('error' => '充值失败'));
			
			return;
		}
		
		if ($type == $enum_chargemode->chinabank) {
			if ($bank == 0) {
				$url = base_lib_Constant::COMPANY_URL_NO_HTTP . "/chinabank/?txtAmount={$v_amount}&v_oid={$v_oid}";
			}
			else {
				$url = base_lib_Constant::COMPANY_URL_NO_HTTP . "/chinabank/?txtAmount={$v_amount}&v_oid={$v_oid}&bank={$bank}";
			}
		}
		else if ($type == $enum_chargemode->alipay) {
			$url = base_lib_Constant::COMPANY_URL_NO_HTTP . "/alipay/?txtAmount={$v_amount}&v_oid={$v_oid}";
		}
		else if ($type == $enum_chargemode->weixinpay) {
			$result = $this->getCodeUrl($v_oid, $v_amount);
			$url = $result['code_url'];
		}
		echo json_encode(array ('success' => 'true', 'order' => $v_oid, 'url' => $url));
		
		return;
	}
	
	public function getCodeUrl($order_no, $amount) {
		$notify_url = base_lib_Constant::COMPANY_URL . "/interface/WXChargeRespond";
//        $notify_url = "https://company.huibo.com/interface/WXChargeRespond";
		require_once(PLUGINS_DIR . '/pay/WxPay/WxPay.Api.php');
		require_once(PLUGINS_DIR . '/pay/WxPay/HbWxPay.Config.php');
		require_once(PLUGINS_DIR . '/pay/WxPay/HbWxPay.NativePay.php');
		$pay_order = new WxPayUnifiedOrder();
		$body = '充值';
		$pay_order->SetBody($body);
		$pay_order->SetDetail($body);
		$pay_order->SetAttach($body);
		$pay_order->SetDevice_info('WEB');
		$pay_order->SetFee_type('CNY');
//        $pay_order->SetAttach($attach);
		$pay_order->SetOut_trade_no($order_no);
		$pay_order->SetTotal_fee($amount * 100);
		$pay_order->SetTime_start(date("YmdHis"));
		$pay_order->SetTime_expire(date("YmdHis", time() + 600));
		$pay_order->SetNotify_url($notify_url);
		$pay_order->SetTrade_type("NATIVE");
		$pay_order->SetProduct_id($order_no);
		$notify = new NativePay();
		SlightPHP::log(print_r($pay_order, true));
		
		return $notify->GetPayUrl($pay_order);
	}
	
	/**
	 * 生成支付二维码
	 */
	public function pagePaySQrcode($inPath) {
		$path = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$in_data['code_url'] = base_lib_BaseUtils::getStr($path['code_url'], 'string');
		
		ob_end_clean();
		header('Content-Type:image/png');
		SQrcode::png1($in_data['code_url'], 6, 1);
	}
	
	/**
	 * 验证订单状态
	 * @param $inPath
	 */
	public function pageCheckOrder($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$orderNo = base_lib_BaseUtils::getStr($path_data['orderno'], 'string', null);
		if (base_lib_BaseUtils::nullOrEmpty($orderNo)) {
			echo json_encode(array ('error' => '未效的订单编号'));
			
			return;
		}
		
		$service_cashcharge = new base_service_company_charge_companycashcharge();
		$cashcharge = $service_cashcharge->getCashCharge($orderNo, 'charge_state', $this->_userid);
		if (empty($cashcharge)) {
			echo json_encode(array ('error' => '未效的订单编号'));
		}
		else {
			echo json_encode(array ('state' => $cashcharge['charge_state']));
		}
		
		return;
	}
	
	/**
	 * 充值记录
	 * @param mixed $inPath url链接参数
	 * @return html          html页面
	 */
	public function pagelist($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$cur_page = base_lib_BaseUtils::getStr($pathdata['page'], 'int', 1);
		$page_size = base_lib_Constant::PAGE_SIZE;
		$service_cashcharge = new base_service_company_charge_companycashcharge();
		
		$cashcharge = $service_cashcharge->getCompanyCashChargeList($page_size, $cur_page, $this->_userid,
		                                                            'charge_id,order_no,charge_amount,charge_state,charge_mode,create_time');
		
		if (!empty($cashcharge)) {
			$cashcharge_items = $cashcharge->items;
			$total_count = $cashcharge->totalSize;
			$this->_aParams['title'] = '充值记录';
			$this->_aParams['cashcharges'] = $cashcharge_items;
			
			//分页
			$pager = $this->pageBar($total_count, $page_size, $cur_page, $inPath);
			$this->_aParams['pager'] = $pager;
		}
		
		return $this->render('service/historyrecharge.html', $this->_aParams);
	}
	
	/**
	 * 兼职充值记录
	 * @param mixed $inPath url链接参数
	 */
	public function pagepartList($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$cur_page = base_lib_BaseUtils::getStr($pathdata['page'], 'int', 1);
		$page_size = base_lib_Constant::PAGE_SIZE;
		$service_cashcharge = new base_service_part_order_partorder();
		$com_src = new base_service_common_part_partordersrc();
		$com_pay_type = new base_service_common_part_partorderpaytype();
		$cashcharge = $service_cashcharge->getChargeCompanyId($cur_page, $page_size, $this->_userid, 'order_no,order_src,create_time,order_money,is_pay,pay_type');
		
		if (!empty($cashcharge)) {
			$cashcharge_items = $cashcharge->items;
			foreach ($cashcharge_items as &$v) {
				$v['pay_name'] = $com_pay_type->getName($v['pay_type']);
				$v['pay_type_name'] = in_array($v['order_src'], [7]) ? '销售' : '自助';
			}
			
			$total_count = $cashcharge->totalSize;
			$this->_aParams['title'] = '充值记录';
			$this->_aParams['cashcharges'] = $cashcharge_items;
			
			//分页
			$pager = $this->pageBar($total_count, $page_size, $cur_page, $inPath);
			$this->_aParams['pager'] = $pager;
		}
		
		return $this->render('service/historypartrecharge.html', $this->_aParams);
	}
	
	
	/**
	 * 账户管理 > 账户充值 > 充值记录
	 * @param mixed $inPath url链接参数
	 * @return html          html页面
	 */
	public function pagePayList($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$cur_page = base_lib_BaseUtils::getStr($pathdata['page'], 'int', 1);
		$page_size = base_lib_Constant::PAGE_SIZE;
		$service_cashconsume = new base_service_company_charge_companycashconsume();
		$common_consumetype = new base_service_common_consumetype();
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid);
		$company_ids = $company_resources->all_accounts;
		$cashconsume = $service_cashconsume->getCompanyConsumeList($this->_userid, $field = "consume_amount,consume_type,create_time,consume_info,consume_contract_amount,account_id", $cur_page, $page_size);
		$accountids = base_lib_BaseUtils::getProperty($cashconsume->items, 'account_id');
		$base_service_company_account = new base_service_company_account();
		$accounts = $base_service_company_account->getAccounts(array_unique($accountids), 'account_id,user_id,user_name');
		$list = base_lib_BaseUtils::array_key_assoc($accounts, 'account_id');
		$this->_aParams['sum'] = $service_cashconsume->getSumConsume($this->_userid);
		if (!empty($cashconsume->items)) {
			$total_count = $cashconsume->totalSize;
			$this->_aParams['title'] = '消费记录';
			foreach ($cashconsume->items as $key => $item) {
				$this->_aParams['total'] += $item['consume_contract_amount'];
				$cashconsume->items[ $key ]['consume_type_text'] = $common_consumetype->getName($item['consume_type']);
				$cashconsume->items[ $key ]['user_name'] = $list[ $item['account_id'] ]['user_id'] . '-' . $list[ $item['account_id'] ]['user_name'];
			}
			//分页
			$pager = $this->pageBar($total_count, $page_size, $cur_page, $inPath);
			$this->_aParams['items'] = $cashconsume->items;
			$this->_aParams['pager'] = $pager;
		}
		
		return $this->render('service/historyconsume.html', $this->_aParams);
	}
	
	
	public function pageWxPay($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$in_data['orderno'] = base_lib_BaseUtils::getStr($pathdata['orderno'], 'string', 0);
		$in_data['code_url'] = base_lib_BaseUtils::getStr($pathdata['code_url'], 'string', 0);
		$in_data['cash'] = base_lib_BaseUtils::getStr($pathdata['cash'], 'string', 0);
		
		if (empty($in_data['orderno'])) {
			echo header("Content-type:text/plain;charset=utf-8");
			echo "订单信息错误，请重试";
			
			return;
		}
		
		$service_cashcharge = new base_service_company_charge_companycashcharge();
		$cashcharge = $service_cashcharge->getCashCharge($in_data['orderno'], 'charge_state,charge_amount', $this->_userid);
		
		if (empty($cashcharge)) {
			echo header("Content-type:text/plain;charset=utf-8");
			echo "查询不到订单信息，请重试";
			
			return;
		}
		
		$in_data['charge_amount'] = $cashcharge['charge_amount'];
		
		
		return $this->render('service/wxpay.html', $in_data);
	}
	
	/**
	 * 检测现金券是否后台使用    zhouwenjun 2019/8/16 16:20
	 */
	function pageCheckAllotCouponBusiness($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$allot_cash_id = base_lib_BaseUtils::getStr($path_data['allot_cash_id'], 'int', 0);
		if (!$allot_cash_id) {
			$this->ajax_data_json(ERROR, '参数错误');
		}
		
		$re_data = array ("is_used" => false);//是否已使用
		$ser_allotCouponBusiness = new base_service_company_coupon_allotCouponBusiness();
		$cash_business_data = $ser_allotCouponBusiness->GetAllotCashDataByCashId($allot_cash_id, 'a.id');
		if ($cash_business_data && $cash_business_data['id']) {
			$re_data["is_used"] = true;
		}
		
		$this->ajax_data_json(SUCCESS, '查询成功', $re_data);
	}
}

?>