<?php

/*单位注册控制器
 */

class controller_regist extends components_cbasepage {
	
	function __construct(){
		parent::__construct(false);
	}
	
	function pageIndex($inPath){
		$this->_aParams['title'] = '企业注册';
		return $this->render('register.html', $this->_aParams);
	}
	
	function addDo($inPath){
		$pathData = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$val = new base_lib_Validator();
		$comName = $val->getStr($pathData['txtCompanyName'],1,200,'请输入公司名称');
		$tel = $val->getStr($pathData['txtLinkPhone'],1,58,'请输入电话号码');
		$linkPerson = $val->getStr($pathData['txtLinkPerson'],1,60,'请输入联系人');
		$userName = $val->getStr($pathData['txtUserName'],3,30,'请输入用户名');
		$pwd = $val->getStr($pathData['txtPwd'],6,16,'请输入密码');
		$pwdRepeat = $val->getStr($pathData['txtPwdRepeat'],6,16,'请确认密码');
		if ($val->has_err){
			$json = $val->ToJsonWithHtml();
			exit($json);
		}
		$service_company = new base_service_company_company();
		$reUserId = $service_company->doCheckUniqueUserId($userName);
		if (!empty($reUserId)){
			$json = json_encode(array('error', '用户名已存在'));
			exit($json);
		}
		if ($pwd != $pwdRepeat){
			$json = json_encode(array('error', '确认密码错误'));
			exit($json);
		}
		$maxCompanyId = $service_company->getMaxCompanyId();
		var_dump($maxCompanyId);
		exit();
	}
}