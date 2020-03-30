<?php

/**
 * 网络-电子合同签署
 * Class contractEcontract.class.php
 * User: zhouwenjun  2019/12/11 9:25
 */
class company_service_contractEcontract extends base_components_baseservice {
	
	protected $marter_table = 'info_oa_contract_econtract';
	protected $primary_key = 'id';
	
	/**
	 * 开票公司 税票号    zhouwenjun 2019/12/12 10:45
	 */
	private $contractcompany_social_code = array (
		"重庆汇博信息科技有限公司"       => "915001037815671567",
		"重庆聚焦人才服务有限公司"       => "915001037815671567",
		"重庆聚焦人才服务有限公司龙头寺分公司" => "915000005699215841",
		"重庆聚焦人才服务有限公司第五分公司"  => "91500112MA5YQ0WJ8A",
		"重庆聚焦人才服务有限公司第三分公司"  => "91500103MA5UMBP61B",
	);
	
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * 获取网络-电子合同    zhouwenjun 2019/12/11 9:24
	 * @param string $contract_code
	 * @param string $item
	 * @return array|bool
	 */
	public function GetContractEcontractByContractCode($contract_code = '', $item = '') {
		if(!$contract_code) {
			return false;
		}
		
		$condition['contract_code'] = $contract_code;
		$condition['is_effect'] = 1;
		
		return $this->selectOne($condition, $item, null, null, null, array ('type' => 'main'));
	}
	
	/**
	 * 添加电子合同信息    zhouwenjun 2019/12/11 15:48
	 * @param array $item
	 * @return bool
	 */
	function AddContractEcontractInfo($item = array ()) {
		if(!$item) {
			return false;
		}
		
		return $this->insert($item);
	}
	
	/**
	 * 更新电子合同信息    zhouwenjun 2019/12/11 15:48
	 * @param int   $id
	 * @param array $item
	 * @return bool
	 */
	function UpdateContractEcontractById($id = 0, $item = array ()) {
		if(!$item || !$id) {
			return false;
		}
		$condition['id'] = $id;
		
		return $this->update($condition, $item);
	}
	
	//获取电子合同
	function getInfoByCompanyId($company_id = 0, $item = array ()) {
		if(!$item || !$company_id) {
			return false;
		}
		$condition['company_id'] = $company_id;
		$condition['econtract_state'] = array ('>' => 1);
		$condition['is_effect'] = 1;
		
		return $this->select($condition, $item, '', 'order by sponsor_time desc')->items;
	}
	
	/**
	 * 获取电子合同 根据ID    zhouwenjun 2019/12/12 10:10
	 * @param int    $id
	 * @param string $item
	 * @return array|bool
	 */
	function GetEcontractDataById($id = 0, $item = '') {
		if(!$id) {
			return false;
		}
		$condition['id'] = $id;
		$condition['is_effect'] = 1;
		
		return $this->selectOne($condition, $item);
	}
	
	/**
	 * 获取电子合同 根据ID    zhouwenjun 2019/12/12 10:10
	 * @param int    $id
	 * @param string $item
	 * @return array|bool
	 */
	function GetEcontractDataStateInfoByEcontractId($id = 0, $item = '') {
		if(!$id) {
			return false;
		}
		$condition['id'] = $id;
		$condition['econtract_state'] = 2;
		$condition['is_effect'] = 1;
		
		return $this->selectOne($condition, $item);
	}
	
	/**
	 * 获取 签署企业账号    zhouwenjun 2019/12/12 10:30
	 * @param int    $company_id
	 * @param string $company_name
	 * @param string $acceptor_social_code
	 * @return array|bool
	 */
	function GetEcontractAccountId($company_id = 0, $company_name = '', $acceptor_social_code = '') {
		if(!$company_name || !$acceptor_social_code) {
			return array ('code' => ERROR, 'msg' => '参数错误!');
		}
		$ser_contractEcontractAccount = new company_service_contractEcontractAccount();
		$contractEcontractAccount_data = $ser_contractEcontractAccount->GetEcontractAccountIdByData($company_id, $company_name, $acceptor_social_code);
		if(false && $contractEcontractAccount_data && $contractEcontractAccount_data['account_id']) {
			$accountId = $contractEcontractAccount_data['account_id'];
		}
		//生成 签署企业账号
		else {
			$accountId_data = SWraper::addOrganize($company_name, $acceptor_social_code);
			//账号部分字段冲突：name 先注销账号,重新创建
			if($accountId_data['errCode'] == 1500012) {
				//查询账号
				$accountId_data = SWraper::GetOrganizeInfo($acceptor_social_code);
				//注销账号
				$accountId_data = SWraper::DelOrganize($accountId_data['accountInfo']['accountUid']);
				//注销账号-数据库保存的
				$ser_contractEcontractAccount->UpdateEcontractAccountIdByAccountId($accountId_data['accountInfo']['accountUid'], array ('is_effect' => 0));
				$accountId_data = SWraper::addOrganize($company_name, $acceptor_social_code);
			}
			if($accountId_data['errCode'] || !$accountId_data['accountId']) {
				return array ('code' => ERROR, 'msg' => $accountId_data['msg'] ?: "创建企业账户失败!");
			}
			$accountId = $accountId_data['accountId'];
			$add_data = array (
				'company_id'           => $company_id,
				'company_name'         => $company_name,
				'acceptor_social_code' => $acceptor_social_code,
				'account_id'           => $accountId,
				'is_effect'            => 1,
			);
			$ser_contractEcontractAccount->AddEcontractAccountIdInfo($add_data);
		}
		
		return array ('code' => SUCCESS, 'msg' => '获取企业账户成功!', 'data' => $accountId);
	}
	
	/**
	 * e签宝 发送验证码    zhouwenjun 2019/12/11 21:55
	 * @param int $econtract_id
	 * @return bool|array
	 */
	function send3Rdcode($econtract_id = 0) {
		if(!$econtract_id) {
			return array ('code' => ERROR, 'msg' => '参数错误!');
		}
		$econtract_data = $this->GetEcontractDataStateInfoByEcontractId($econtract_id);
		if(!$econtract_data) {
			return array ('code' => ERROR, 'msg' => '电子合同不存在或已签署!');
		}
		if(!$econtract_data['acceptor_phone']) {
			return array ('code' => ERROR, 'msg' => '签署手机号码错误!');
		}
		if(!base_lib_BaseUtils::IsMobile($econtract_data['acceptor_phone'])) {
			return array ('code' => ERROR, 'msg' => '签署手机号码格式错误!');
		}
		
		$client_ip = base_lib_BaseUtils::getIp(0);
		$error = '';
		$action_common = new base_service_common_actionsource();
		try {
			$regmsg = '您的验证码：${ValidationCode}（输入验证码即代表您同意签署，请勿泄露给他人），电子签名与手写签章具有同等法律效力。';
			$service_personreg = new base_service_person_personregvlid();
			$service_personreg->createRegValidCodeV2($client_ip, $econtract_data['acceptor_phone'], $error, $action_common->contract_econtract,null,$regmsg,10);
		} catch (Exception $ex) {
			return array ('code' => ERROR, 'msg' => $ex->getMessage());
		}
		
		if($error){
			return array ('code' => ERROR, 'msg' => $error);
		}
		
		// $accountId_data = $this->GetEcontractAccountId($econtract_data['company_id'], $econtract_data['acceptor_signer'], $econtract_data['acceptor_social_code']);
		// if($accountId_data['code'] == ERROR || !$accountId_data['data']) {
		// 	return array ('code' => ERROR, 'msg' => $accountId_data['msg'] ?: "创建企业账户失败!");
		// }
		// $accountId = $accountId_data['data'];
		//生成 发送验证码
		// $result = SWraper::send3rdCode($accountId, $econtract_data['acceptor_phone']);
		// if($result['errCode']) {
		// 	return array ('code' => ERROR, 'msg' => $result['msg']);
		// }
		
		return array (
			'code' => SUCCESS,
			'msg'  => '发送成功',
			'data' => array ('acceptor_phone' => $econtract_data['acceptor_phone']),
		);
	}
	
	/**
	 * 平台用户PDF摘要签署（指定手机短信验证）    zhouwenjun 2019/12/11 22:28
	 * @param int    $econtract_id
	 * @param string $code
	 * @param int    $sign_operator
	 * @return array
	 */
	function userFileMobileSign($econtract_id = 0, $code = '', $sign_operator = 0) {
		if(!$econtract_id || !$code || !$sign_operator) {
			return array ('code' => ERROR, 'msg' => '参数错误!');
		}
		$econtract_data = $this->GetEcontractDataStateInfoByEcontractId($econtract_id);
		if(!$econtract_data) {
			return array ('code' => ERROR, 'msg' => '电子合同不存在或已签署!');
		}
		if(!$econtract_data['acceptor_phone']) {
			return array ('code' => ERROR, 'msg' => '签署手机号码错误!');
		}
		if(!base_lib_BaseUtils::IsMobile($econtract_data['acceptor_phone'])) {
			return array ('code' => ERROR, 'msg' => '签署手机号码格式错误!');
		}
		//验证 短信验证码
		$client_ip = base_lib_BaseUtils::getIp(0);
		$service_reglid = new base_service_person_personregvlid();
		$service_reglid->validMobilePhone($client_ip,$econtract_data['acceptor_phone'],$code,$error);
		if($error){
			return array ('code' => ERROR, 'msg' => $error);
		}
		
		$contractcompany_name = $econtract_data['contractcompany_name'];
		$contractcompany_social_code = @$this->contractcompany_social_code[ $contractcompany_name ];
		if(!$contractcompany_social_code) {
			return array ('code' => ERROR, 'msg' => '乙方公司社会信用代码错误!');
		}
		
		$dirPath = base_lib_Constant::SERVER_TEMP_FULL_FOLDER;
		$fileDir = "{$dirPath}SignContractEcontract/{$econtract_data['company_id']}/{$econtract_data['contract_code']}/";
		//签章数据
		$signature_data = array ();
		
		$ser_SAESmcrpt = new SAESmcrpt();
		$srcPath = base_lib_Constant::COMPANY_URL . '/interface/GetContractEcontractSignFile?key=';
		// $srcPath = 'http://company.huibo.com/interface/GetContractEcontractSignFile?key=';
		
		#region 甲方签章
		//获取 企业账号ID
		$accountId_data = $this->GetEcontractAccountId($econtract_data['company_id'], $econtract_data['acceptor_signer'], $econtract_data['acceptor_social_code']);
		if($accountId_data['code'] == ERROR || !$accountId_data['data']) {
			return array ('code' => ERROR, 'msg' => $accountId_data['msg'] ?: "创建企业账户失败!");
		}
		$accountId = $accountId_data['data'];
		
		//创建企业模板印章
		$result = SWraper::addOrganizeSeal($accountId, $econtract_data['acceptor_social_code']);
		if($result['errCode']) {
			return array ('code' => ERROR, 'msg' => $result['msg'] ?: "企业印章创建失败");
		}
		$sealData = $result['sealData'];
		
		//甲方签章数据 文件流 http
		$fname_pdf_owner_data = array (
			'pdf_file' => $fileDir . $econtract_data['sponsor_pdf_file'],
		);
		$fname_pdf_owner_data = $ser_SAESmcrpt->encode(json_encode($fname_pdf_owner_data));
		$srcPath_tmp = $srcPath . $fname_pdf_owner_data; //远程文件路径
		$fname_pdf_end_file = "huibo_{$econtract_data['company_id']}_{$econtract_data['contract_code']}_end.pdf";
		$userFileMobileSign_data = array (
			'file'      => $srcPath_tmp,
			'fileName'  => $econtract_data['acceptor_signer'],
			// 'dstPdfFile' => $fileDir . $fname_pdf_owner_file,
			'accountId' => $accountId,
			'sealData'  => $sealData,
			// 'mobile'    => $econtract_data['acceptor_phone'],
			// 'code'      => $code,
			'signType'  => "Key",
			'key'       => "甲方企业：",
			'posX'      => 120,
			'posY'      => -40,
		);
		$signature_data['owner'] = $result = SWraper::userStreamSign($userFileMobileSign_data);
		if($result['errCode'] || !$result || !$result['stream']) {
			return array ('code' => ERROR, 'msg' => $result['msg'] ?: "甲方企业签章失败", 'data' => $result);
		}
		unset($signature_data['owner']['stream']);
		//写入pdf stream
		base_lib_BaseUtils::writeFile($fileDir . $fname_pdf_end_file, base64_decode($result['stream']));
		
		// //甲方签章数据 文件形式
		// $fname_pdf_owner_file = "huibo_{$econtract_data['company_id']}_{$econtract_data['contract_code']}_owner.pdf";
		// $userFileMobileSign_data = array (
		// 	'fileName'   => $econtract_data['acceptor_signer'],
		// 	'srcPdfFile' => $fileDir . $econtract_data['sponsor_pdf_file'],
		// 	'dstPdfFile' => $fileDir . $fname_pdf_owner_file,
		// 	'accountId'  => $accountId,
		// 	'sealData'   => $sealData,
		// 	'mobile'     => $econtract_data['acceptor_phone'],
		// 	'code'       => $code,
		// 	'posPage'    => 2,
		// 	'posX'       => 150,
		// 	'posY'       => 350,
		// );
		// $signature_data['owner'] = $result = SWraper::userFileMobileSign($userFileMobileSign_data);
		// if($result['errCode']) {
		// 	return array ('code' => ERROR, 'msg' => $result['msg'] ?: "企业签章失败");
		// }
		if(!file_exists($fileDir . $fname_pdf_end_file)) {
			return array ('code' => ERROR, 'msg' => "甲方企业签章失败");
		}
		#endregion
		
		//更新电子合同数据
		$up_data = array (
			'econtract_state'    => 3,
			'signature_operator' => $sign_operator,
			'signature_time'     => $this->now,
			'signature_data'     => json_encode($signature_data),
			'signature_file'     => $fname_pdf_end_file,
		);
		$re = $this->UpdateContractEcontractById($econtract_id, $up_data);
		if(!$re) {
			return array ('code' => ERROR, 'msg' => "更新电子合同数据失败!");
		}
		
		//自动申请电子合同归还
		$company_service_contractrevert = new company_service_contractrevert();
		$res = $company_service_contractrevert->addContractRevert($econtract_data['contract_code']);
		if(!$res) {
			return array ('code' => ERROR, 'msg' => "归还电子合同失败!");
		}
		
		//甲方完成签章后，系统消息通知签订人销售
		//你对（986987）重庆测试有限公司发起的电子合同已经完成签章，请注意查看
		if($econtract_data['signer']) {
			$ser_user = new base_service_boss_user();
			$re_msg_html = <<<HTML
你对（{$econtract_data['company_id']}）{$econtract_data['acceptor_signer']}发起的电子合同已经完成签章，请注意查看。
HTML;
			$ser_user->sendMsg("business_audit", array ($econtract_data['signer']), '电子合同已经完成签章', $re_msg_html, null);
		}
		
		
		return array ('code' => SUCCESS, 'msg' => "电子合同签章成功!");
	}
}
