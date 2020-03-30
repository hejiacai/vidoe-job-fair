<?php

/**
 *
 * @ClassName controller_account
 * @Desc      我的账户
 * @author    jiangcl@huibo.com
 * @date      2013-9-26 下午01:51:47
 */
class controller_account extends components_cbasepage {
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * 我的账户入口
	 * @param $inPath
	 */
	function pageIndex($inPath) {
		
		if (!$this->canDo("company_manage")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}
		
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$this->_aParams['title'] = '汇博人才网_招聘中心_我的账户';
		//会员信息
		$memberinfo = $this->_getMemberState($this->_userid, $account_id);
		if (!base_lib_BaseUtils::nullOrEmpty($memberinfo)) {
			$this->_aParams = $memberinfo;
		}
		
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $account_id);
		
		$this->_aParams['company_resource_info'] = $company_resources->getCompanyServiceSource(); //获取套餐资源
		$service_company_account = new base_service_company_account();
		
		
		$fields = 'is_main,user_id';
		$account = $service_company_account->getAccount($account_id, $fields);
		
		
		$this->_aParams['is_main'] = $account['is_main'];
		$this->_aParams['user_id'] = $account['user_id'];

//        $audit_info = $this->checkCompanyLetter($company_resources->main_company_id);
//        $this->_aParams['audit_code'] = $audit_info['code'];
		$this->_aParams['audit_msg'] = $company_resources->CompanyAuditStatus();
		
		return $this->render('./sysmanage/account.html', $this->_aParams);
	}
	
	//解除账号禁用
	function pageFreeForbid($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$user_id = $this->_userid;
		$login_id = base_lib_BaseUtils::getStr($pathdata['login_id'], 'string', null);
		$items['free_man'] = $user_id;
		$items['free_time'] = date('Y-m-d H:i:s');
		$items['state'] = 0;
		if (base_lib_BaseUtils::nullOrEmpty($login_id)) {
			$json['error'] = '参数有误，请重试';
			echo json_encode($json);
			
			return;
		}
		$service_forbid = new base_service_company_accountsforbid();
		$result = $service_forbid->ModAccountsForbid2($login_id, $items);
		if ($result !== false) {
			$service_company_account = new base_service_company_account();
			$cookie_account_id = base_lib_BaseUtils::getCookie('accountid');
			$cookie_account = $service_company_account->getAccount($cookie_account_id, 'company_id');
			
			$companyaccountlog_service = new base_service_company_companyaccountlog();
			$ervice_common_account_accountoperatetype = new base_service_common_account_accountoperatetype();
			$service_common_account_accountlogfrom = new base_service_common_account_accountlogfrom();
			$insertItems = array (
				"company_id"   => $cookie_account['company_id'],
				"account_id"   => $cookie_account_id,
				"operate_type" => $ervice_common_account_accountoperatetype->account_forbid,
				"content"      => "解禁帐号" . $login_id,
				"create_time"  => date("Y-m-d H:i:s", time()),
				"source"       => $service_common_account_accountlogfrom->touch_screen_version,
			);
			$companyaccountlog_service->addLogToMongo($insertItems);
			$json['success'] = 'true';
			echo json_encode($json);
			
			return;
		}
		else {
			$json['error'] = '释放单位帐号的禁用标记失败';
			echo json_encode($json);
			
			return;
		}
	}
	
	/**
	 * 账户管理
	 * @param $inPath
	 */
	public function pageAccountManage($inPath) {
		if (!$this->canDo("account_manage")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}
		$service_company_account = new base_service_company_account();
		$service_weixin_companyweixin = new base_service_weixin_companyweixin();
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$fields = 'account_id,company_id,is_main,user_id,user_name,mobile_phone,link_tel,station,head_photo,last_login_time,state,resource_type';
		$account = $service_company_account->getAccount($account_id, $fields);
		$service_related = base_service_hractivity_related::getInstances();
		//主账号
		if ($account['is_main'] == 1) {
			$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
			$serach_user_id = base_lib_BaseUtils::getStr($path_data["serach_user_id"], "string", null);
			$service_company_account = new base_service_company_account();
			$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $account_id);
			$companyresources = $company_resources->getCompanyServiceSource();
			
			if ($companyresources['isCqNewService']) {
				$this->_aParams['release_point_sub_account'] = $companyresources['cq_release_point_sub_account']; //获取套餐资源
				$this->_aParams['cq_point_sub_account'] = $companyresources['cq_point_sub_account']; //获取套餐资源
			}
			else {
				$sub_account = $service_company_account->GetSubAccount($this->_userid, "company_id");
				$companyxml = SXML::load("../config/company/company.xml");
				$account_limit = $companyxml->AccountLimit;
				$sub_account_num = empty($sub_account) ? 0 : count($sub_account);
				$this->_aParams['release_point_sub_account'] = ($account_limit - $sub_account_num) < 1 ? 0 : ($account_limit - $sub_account_num);
				$this->_aParams['cq_point_sub_account'] = $account_limit;
			}
			
			
			$account_list_temp = $service_company_account->getAccountList($account['company_id'], $fields, $serach_user_id);
			$account_list = $account_list_temp->items;
			$v_accountsforbid = array ();
			//是否绑定微信
			$weixin_result = $service_weixin_companyweixin->getListByCompanyId($account['company_id'], 'account_id,open_id', 1);
			$weixin_accounts = base_lib_BaseUtils::getPropertys($weixin_result, "account_id");
			$weixin_result = base_lib_BaseUtils::array_key_assoc($weixin_result, "account_id");
			
			//是否绑定APP
			$related_list = $service_related->getRelatedByCompany($this->_userid, "person_id,account_id,company_id");
			$related_list = base_lib_BaseUtils::array_key_assoc($related_list, "account_id");
			$this->_aParams["related_list"] = $related_list;
			foreach ($account_list as $key => $value) {
				array_push($v_accountsforbid, $value['user_id']);
				//设置头像，没有设置头像的时候是真实姓名的首位
				$account_list[ $key ]['has_head_photo'] = true;
				if (base_lib_BaseUtils::nullOrEmpty($value['head_photo'])) {
					$account_list[ $key ]['has_head_photo'] = false;
					preg_match_all('/./u', $value['user_name'], $names);
					$account_list[ $key ]['head_photo'] = isset($names[0][0]) ? $names[0][0] : '';
				}
				if (!base_lib_BaseUtils::nullOrEmpty($account_list[ $key ]['last_login_time'])) {
					$account_list[ $key ]['last_login_time'] = date("Y-m-d", strtotime($value['last_login_time']));
				}
				//是否绑定微信
				$account_list[ $key ]['bind_weixin'] = in_array($value['account_id'], $weixin_accounts);
				$account_list[ $key ]['open_id'] = $weixin_result[ $value['account_id'] ]['open_id'];
			}
			
			foreach ($account_list as $key => $value) {
				//将主账号放到数组首位
				if ($value['is_main'] && $key) {
					unset($account_list[ $key ]);
					array_unshift($account_list, $value);
//                    $temp = $account_list[0];
//                    $account_list[0] = $value;
//                    $account_list[$key] = $temp;
					break;
				}
			}
			
			/*是否禁用*/
			$service_forbid = new base_service_company_accountsforbid();
			$accountsforbid = $service_forbid->GetAccountsForbid($v_accountsforbid, "*");
			if (!empty($accountsforbid) && count($accountsforbid) > 0) {
				$accountsforbid = base_lib_BaseUtils::array_key_assoc($accountsforbid, "login_id");
			}
			foreach ($account_list as $key => $val) {
				$account_list[ $key ]['forbid_state'] = $accountsforbid[ $val['user_id'] ]['state'];
			}
			/*是否禁用*/
			$this->_aParams['serach_user_id'] = $serach_user_id;
			$this->_aParams['account_list'] = $account_list;
			
			//成都新套餐和重庆新套餐进入新的账号管理页面
			if ($companyresources['isNewService'] || $companyresources['isCqNewService']) {
				return $this->_newAccountManage($companyresources, $company_resources);
			}
			
			return $this->render('./sysmanage/accountlist.html', $this->_aParams);
		}
		else {
			//子帐号
			$link_tel_arr = explode("-", $account['link_tel']);
			if (count($link_tel_arr) == 1) {
				$account['phone_info'] = $link_tel_arr[0];
			}
			else {
				$account['zone_info'] = isset($link_tel_arr[0]) ? $link_tel_arr[0] : '';
				$account['phone_info'] = isset($link_tel_arr[1]) ? $link_tel_arr[1] : '';
				$account['ext_info'] = isset($link_tel_arr[2]) ? $link_tel_arr[2] : '';
			}
			
			//是否有头像
			$account['has_head_photo'] = base_lib_BaseUtils::nullOrEmpty($account['head_photo']) ? false : true;
			//是否绑定微信
			$weixin = $service_weixin_companyweixin->getWeixinByAccount($account['account_id'], 'open_id');
			$account['bind_weixin'] = !base_lib_BaseUtils::nullOrEmpty($weixin);
			$account['open_id'] = isset($weixin['open_id']) ? $weixin['open_id'] : 0;
			$this->_aParams = $account;
			$this->_aParams['is_self'] = true;
			
			return $this->render('./sysmanage/accountnew.html', $this->_aParams);
		}
	}
	
	/**
	 * 新套餐账号管理新页面
	 * @param type $companyresources
	 * @return type
	 */
	private function _newAccountManage($companyresources, $company_resources) {
		/*資源分配*/
		$this->_aParams['isCqNewService'] = $companyresources['isCqNewService'];
		$this->_aParams['isNewService'] = $companyresources['isNewService'];
		list($this->_aParams['ser_data_divide'], $this->_aParams['ser_data_total']) = $this->_installDivideSerData([], $companyresources, $company_resources);
		
		$related_list = $this->_aParams["related_list"];
		$service_person_person = new base_service_person_person();
		$person_list = $service_person_person->getPersons(base_lib_BaseUtils::getProperty($related_list, 'person_id'), 'person_id,mobile_phone');
		$this->_aParams['person_list'] = base_lib_BaseUtils::array_key_assoc($person_list->items, 'person_id');
		
		return $this->render('./sysmanage/accountlist_v2.html', $this->_aParams);
	}
	
	/**
	 * 判断用户用是否重复(判断主账户下的子账户)
	 */
	/* public function pageCheckUserId($inPath){
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $user_id  = base_lib_BaseUtils::getStr($path_data["txtUserID"],"string","");
        if(!$user_id){
            echo json_encode(array("state"=>FALSE));die;
        }
        
        $service_company_account = new base_service_company_account();
        $account_id = base_lib_BaseUtils::getCookie('accountid');
        $account = $service_company_account->getAccount($account_id, 'company_id,user_id');
        $account_list = $service_company_account->getAccountList($account['company_id'], 'account_id', $user_id);
        if(count($account_list->items)){
            echo json_encode(array("state"=>FALSE));die;
        }
        echo json_encode(array("state"=>TRUE));die;
    }*/
	
	/**
	 * 检测用户名（所有账户）
	 * @param array $inPath
	 */
	public function pageCheckUserId($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$user_id = base_lib_BaseUtils::getStr($path_data['txtUserID'], 'string', '');
		$account_id = base_lib_BaseUtils::getStr($path_data["account_id"], "int", null);
		if (base_lib_BaseUtils::nullOrEmpty($user_id)) {
			echo json_encode(array ("state" => false));
			die;
		}
		$service_company_account = new base_service_company_account();
		$result = $service_company_account->checkAccountUserId($this->_userid, $user_id, $account_id);
		echo json_encode(array ('state' => $result));
	}
	
	/**
	 * 新套餐资源分配列表页面
	 * @param type $inPath
	 * @return type
	 */
	public function pageResourceList($inPath) {
		if (!$this->canDo("account_manage")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}
		
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid);
		$companyresources = $company_resources->getCompanyServiceSource();
		
		//成都新套餐和重庆新套餐进才能分配
		if (!$companyresources['isNewService'] && !$companyresources['isCqNewService']) {
			$this->_aParams["msg"] = "当前套餐不能分配资源";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}
		$service_company_account = new base_service_company_account();
		$service_company_accountservice = new base_service_company_accountservice();
		$service_quality = new base_service_company_job_quality();
		$service_job_status = new base_service_common_jobstatus();
		$service_job = new base_service_company_job_job();
		
		$account_list = $service_company_account->getAccountList($company_resources->all_accounts,
		                                                         'account_id,company_id,is_main,user_id,user_name,mobile_phone,link_tel,station,head_photo,last_login_time,state,resource_type', false, false, false, 1);
		$account_ids = base_lib_BaseUtils::getProperty($account_list->items, 'account_id');
		$account_sers = $service_company_accountservice->getSersByAccountIds($account_ids, null, true);
		
		if ($account_sers) {
			$qulity_counts = $service_quality->getCountByAccounts($company_resources->main_company_id, 'b.account_id,count(b.job_id) as total', $account_ids);
			
			$pub_jobs = $service_job->getJobCount($company_resources->all_accounts, $service_job_status->pub, $account_ids, array ('account_id' => true));
			
			$qulity_counts = $qulity_counts ? base_lib_BaseUtils::array_key_assoc($qulity_counts, 'account_id') : $qulity_counts;
			$pub_jobs = $pub_jobs ? base_lib_BaseUtils::array_key_assoc($pub_jobs, 'account_id') : $pub_jobs;
			$account_sers = base_lib_BaseUtils::array_key_assoc($account_sers, 'account_id');
			
			foreach ($account_sers as $k => $v) {
				if (isset($qulity_counts[ $v['account_id'] ])) {
					$account_sers[ $k ]['job_boutique'] = $qulity_counts[ $v['account_id'] ]['total'] < $v['job_boutique'] ? $v['job_boutique'] - $qulity_counts[ $v['account_id'] ]['total'] : 0;
				}
				if (isset($pub_jobs[ $v['account_id'] ])) {
					if ($companyresources['isNewService']) {
						$account_sers[ $k ]['job_num'] = $pub_jobs[ $v['account_id'] ]['count'] < $v['job_num'] + $qulity_counts[ $v['account_id'] ]['total'] ? $v['job_num'] + $qulity_counts[ $v['account_id'] ]['total'] - $pub_jobs[ $v['account_id'] ]['count'] : 0;
					}
					else {
						$account_sers[ $k ]['job_num'] = $pub_jobs[ $v['account_id'] ]['count'] < $v['job_num'] ? $v['job_num'] - $pub_jobs[ $v['account_id'] ]['count'] : 0;
					}
				}
				$account_sers[ $k ]['point_job_boutique'] = $v['point_job_boutique'] > $v['point_job_boutique_use'] ? $v['point_job_boutique'] - $v['point_job_boutique_use'] : 0;
				$account_sers[ $k ]['point_job_refresh'] = $v['point_job_refresh'] > $v['point_job_refresh_use'] ? $v['point_job_refresh'] - $v['point_job_refresh_use'] : 0;
				$account_sers[ $k ]['point_message'] = $v['point_message'] > $v['point_message_use'] ? $v['point_message'] - $v['point_message_use'] : 0;
				$account_sers[ $k ]['point_chat'] = $v['point_chat'] > $v['point_chat_use'] ? $v['point_chat'] - $v['point_chat_use'] : 0;
				$account_sers[ $k ]['spread'] = $v['spread'] > $v['spread_use'] ? $v['spread'] - $v['spread_use'] : 0;
				$account_sers[ $k ]['resume_num'] = $v['resume_num'] > $v['resume_num_use'] ? $v['resume_num'] - $v['resume_num_use'] : 0;
			}
		}
		foreach ($account_list->items as $key => $value) {
			//设置头像，没有设置头像的时候是真实姓名的首位
			$account_list->items[ $key ]['has_head_photo'] = true;
			if (base_lib_BaseUtils::nullOrEmpty($value['head_photo'])) {
				$account_list->items[ $key ]['has_head_photo'] = false;
				preg_match_all('/./u', $value['user_name'], $names);
				$account_list->items[ $key ]['head_photo'] = isset($names[0][0]) ? $names[0][0] : '';
			}
			$account_list->items[ $key ]['account_ser'] = $value['resource_type'] == 2 && isset($account_sers[ $value['account_id'] ]) ? $account_sers[ $value['account_id'] ] : null;
		}
//        $account_list->items = base_lib_BaseUtils::array_sort2($account_list->items, 'is_main');
		foreach ($account_list->items as $key => $value) {
			//将主账号放到数组首位
			if ($value['is_main'] && $key) {
				unset($account_list->items[ $key ]);
				array_unshift($account_list->items, $value);
//                $temp = $account_list->items[0];
//                $account_list->items[0] = $value;
//                $account_list->items[$key] = $temp;
				break;
			}
		}
		
		if ($companyresources['isCqNewService']) {
			$ser_data_remin = [
				"job_num"           => $companyresources['cq_relese_job_num'],
				"point_job_refresh" => $companyresources['cq_release_point_job_refresh'],
				"point_message"     => $companyresources['cq_release_point_message'],
				'point_chat'        => $companyresources['cq_release_point_chat'],
				'spread'            => $companyresources['spread_overage'],
				'resume_num'        => $companyresources['cq_resume_num_release'],
			];
		}
		else if ($companyresources['isNewService']) {
			$ser_data_remin = [
				"job_num"            => $companyresources['job_release_num'],
				"point_job_refresh"  => $companyresources['point_job_refresh_last'],
				"job_boutique"       => $companyresources['job_boutique_release'],
				'point_job_boutique' => $companyresources['point_job_boutique_last'],
				'spread'             => $companyresources['spread_overage'],
				'resume_num'         => $companyresources['resume_num_release'],
			];
		}
		
		$this->_aParams['ser_data_remin'] = $ser_data_remin;
		$this->_aParams['account_list'] = $account_list->items;
		$this->_aParams['isNewService'] = $companyresources['isNewService'];
		$this->_aParams['isCqNewService'] = $companyresources['isCqNewService'];
		
		return $this->render('./sysmanage/resourcelist.html', $this->_aParams);
	}
	
	public function pageDivideSerDo($inPath) {
		if (!$this->canDo("account_manage")) {
			echo json_encode(array ("status" => false, "msg" => '没有开通相应权限'));
			
			return;
		}
		
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		
		$resource_type = base_lib_BaseUtils::getStr($path_data["resource_type"], "int", 1);
		$job_num = base_lib_BaseUtils::getStr($path_data["job_num"], "int", 0);
		$job_boutique = base_lib_BaseUtils::getStr($path_data["job_boutique"], "int", 0);//四川新特有
		$point_job_boutique = base_lib_BaseUtils::getStr($path_data["point_job_boutique"], "int", 0);//四川新特有
		$point_job_refresh = base_lib_BaseUtils::getStr($path_data["point_job_refresh"], "int", 0);
		$point_message = base_lib_BaseUtils::getStr($path_data["point_message"], "int", 0);//重庆新特有
		$point_chat = base_lib_BaseUtils::getStr($path_data["point_chat"], "int", 0);//重庆新特有
		$spread = base_lib_BaseUtils::getStr($path_data["spread"], "float", 0.0);
		$resume_num = base_lib_BaseUtils::getStr($path_data["resume_num"], "int", 0);
		$account_id = base_lib_BaseUtils::getStr($path_data["account_id"], "int", 0);
		
		$ser_data = compact('job_num', 'job_boutique', 'point_job_boutique', 'point_job_refresh', 'point_message', 'point_chat', 'spread', 'resume_num');
		$service_company_account = new base_service_company_account();
		
		$res = $service_company_account->newUpAccount($account_id, ['resource_type' => $resource_type], $ser_data);

		if ($res === false) {
			echo json_encode(array ("status" => false, "msg" => '操作失败'));
			
			return;
		}
		
		echo json_encode(array ("status" => true, "msg" => '操作成功'));
		
		return;
	}
	
	/**
	 * 分配资源
	 * @param type $inPath
	 */
	public function pageDivideSer($inPath) {
		if (!$this->canDo("account_manage")) {
			echo '没有开通相应权限';
			
			return;
		}
		
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$divide_account_id = base_lib_BaseUtils::getStr($path_data["account_id"], "string", "");
		
		$service_company_account = new base_service_company_account();
		$current_account_id = base_lib_BaseUtils::getCookie('accountid');
//        $current_account = $service_company_account->getAccount($current_account_id);
		$accounts = base_lib_BaseUtils::array_key_assoc($service_company_account->getAccountCompany($this->_userid, 'account_id,is_main,resource_type'), 'account_id');
		if (!isset($accounts[ $divide_account_id ])) {
			echo '未找到待分配账号信息';
			
			return;
		}
		if (!$accounts[ $current_account_id ]['is_main']) {
			echo '非法操作，请登录主账号';
			
			return;
		}
		
		//主账号取出的资源为当前剩余共享资源（即剩余可分配总资源）
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $current_account_id);
		$share_companyresources = $company_resources->getCompanyServiceSource();
		
		//成都新套餐和重庆新套餐进才能分配
		if (!$share_companyresources['isNewService'] && !$share_companyresources['isCqNewService']) {
			echo '当前套餐不能分配资源';
			
			return;
		}
		
		$ser_data = array ();
		if ($accounts[ $divide_account_id ]['resource_type'] == 2) {
			$service_company_accountservice = new base_service_company_accountservice();
			$ser_data = $service_company_accountservice->getSerByAccountId($divide_account_id);
		}
		
		$this->_aParams['isNewService'] = $share_companyresources['isNewService'];
		$this->_aParams['isCqNewService'] = $share_companyresources['isCqNewService'];
		$this->_aParams['resource_type'] = $accounts[ $divide_account_id ]['resource_type'];
		$this->_aParams['account_id'] = $divide_account_id;
		
		list($this->_aParams['ser_data_divide'], $this->_aParams['ser_data_total']) = $this->_installDivideSerData($ser_data, $share_companyresources, $company_resources);
		
		return $this->render('./sysmanage/divideresource.html', $this->_aParams);
	}
	
	private function _installDivideSerData($ser_data, $companyresources, $company_resources) {
		$ser_data_divide = array ();
		$ser_data_remin = array ();
		if (!$ser_data) {
			$ser_data_divide = [
				"job_boutique"       => 0,
				"job_num"            => 0,
				"point_job_boutique" => 0,
				"point_job_refresh"  => 0,
				"point_message"      => 0,
				'point_chat'         => 0,
				'spread'             => 0,
				'resume_num'         => 0,
			];
		}
		else {
			$service_quality = new base_service_company_job_quality();
			$service_job_status = new base_service_common_jobstatus();
			$service_job = new base_service_company_job_job();
			$qulity_count = $service_quality->getCount($company_resources->main_company_id, 'count(b.job_id) as total', [$ser_data['account_id']])['total'];
			$job_num = $service_job->getJobCount($company_resources->all_accounts, $service_job_status->pub, [$ser_data['account_id']]);
			
			$ser_data_divide = [
				"job_boutique"       => $qulity_count < $ser_data['job_boutique'] ? $ser_data['job_boutique'] - $qulity_count : 0,
				"job_num"            => $job_num < $ser_data['job_num'] + $qulity_count ? $ser_data['job_num'] + $qulity_count - $job_num : 0,
				"point_job_boutique" => $ser_data['point_job_boutique'] - $ser_data['point_job_boutique_use'],
				"point_job_refresh"  => $ser_data['point_job_refresh'] - $ser_data['point_job_refresh_use'],
				"point_message"      => $ser_data['point_message'] - $ser_data['point_message_use'],
				'point_chat'         => $ser_data['point_chat'] - $ser_data['point_chat_use'],
				'spread'             => $ser_data['spread'] - $ser_data['spread_use'],
				'resume_num'         => $ser_data['resume_num'] - $ser_data['resume_num_use'],
			];
			if ($companyresources['isCqNewService']) {
				$ser_data_divide['job_num'] = $ser_data['job_num'] - $job_num;
			}
			
			array_walk($ser_data_divide, function (&$v) {
				if ($v < 0) {
					$v = 0;
				}
			});
		}
		
		if ($companyresources['isCqNewService']) {
			$ser_data_remin = [
				"job_num"           => $companyresources['cq_relese_job_num'],
				"point_job_refresh" => $companyresources['cq_release_point_job_refresh'],
				"point_message"     => $companyresources['cq_release_point_message'],
				'point_chat'        => $companyresources['cq_release_point_chat'],
				'spread'            => $companyresources['spread_overage'],
				'resume_num'        => $companyresources['cq_resume_num_release'],
			];
		}
		else if ($companyresources['isNewService']) {
			$ser_data_remin = [
				"job_num"            => $companyresources['job_release_num'],
				"point_job_refresh"  => $companyresources['point_job_refresh_last'],
				"job_boutique"       => $companyresources['job_boutique_release'],
				'point_job_boutique' => $companyresources['point_job_boutique_last'],
				'spread'             => $companyresources['spread_overage'],
				'resume_num'         => $companyresources['resume_num_release'],
			];
		}
		
		return [$ser_data_divide, $ser_data_remin];
	}
	
	
	/**
	 * @desc 编辑账户
	 */
	public function pageEditAccount($inPath) {
		if (!$this->canDo("update_account")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$account_id = base_lib_BaseUtils::getStr($path_data["account_id"], "string", "");
		if (!$account_id) {
			echo '参数错误';
			
			return;
		}
		
		$service_company_account = new base_service_company_account();
		$fields = 'account_id,company_id,is_main,user_id,user_name,mobile_phone,link_tel,station,head_photo,last_login_time,state,is_effect';
		$account = $service_company_account->getAccount($account_id, $fields);
		if (empty($account)) {
			echo '未查到该用户';
			
			return;
		}
		
		$cookie_account_id = base_lib_BaseUtils::getCookie('accountid');
		$cookie_account = $service_company_account->getAccount($cookie_account_id, 'is_main');
		
		if (!$cookie_account['is_main'] && !$account['state']) {
			echo '该用户已停用';
			
			return;
		}
		
		if (!$cookie_account['is_main'] && !$account['is_effect']) {
			echo '该用户已删除';
			
			return;
		}
		
		$link_tel_arr = explode("-", $account['link_tel']);
		if (count($link_tel_arr) == 1) {
			$account['phone_info'] = $link_tel_arr[0];
		}
		else {
			$account['zone_info'] = isset($link_tel_arr[0]) ? $link_tel_arr[0] : '';
			$account['phone_info'] = isset($link_tel_arr[1]) ? $link_tel_arr[1] : '';
			$account['ext_info'] = isset($link_tel_arr[2]) ? $link_tel_arr[2] : '';
		}
		
		//是否有头像
		$account['has_head_photo'] = base_lib_BaseUtils::nullOrEmpty($account['head_photo']) ? false : true;
		//是否绑定微信
		$service_weixin_companyweixin = new base_service_weixin_companyweixin();
		$weixin = $service_weixin_companyweixin->getWeixinByAccount($account['account_id'], 'open_id');
		$account['bind_weixin'] = !base_lib_BaseUtils::nullOrEmpty($weixin);
		$account['open_id'] = isset($weixin['open_id']) ? $weixin['open_id'] : 0;
		$this->_aParams = $account;
		if ($this->_aParams['ext_info'] == '分机号') {
			$this->_aParams['ext_info'] = '';
		}
		$this->_aParams['operate_is_main'] = $cookie_account['is_main'];
		$this->_aParams['is_self'] = $account_id == $cookie_account_id;
		
		return $this->render('./sysmanage/accountnew.html', $this->_aParams);
	}
	
	public function pageDoEditAccount($inPath) {
		if (!$this->canDo("update_account")) {
			echo json_encode(array ("status" => false, "msg" => '无权限访问，没有开通相应权限'));
			die;
		}
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$service_company_account = new base_service_company_account();
		$validator = new base_lib_Validator();
		//$mobile_phone       = $validator->getMobile($path_data["phone"], "手机号码参数错误");
		$user_name = $validator->getStr($path_data["user_name"], 2, 20, "请输入正确的姓名");
		$station = base_lib_BaseUtils::getStr($path_data["station"], "string", "");
		$zone_info = base_lib_BaseUtils::getStr($path_data["zone_infor"], "string", "");
		$link_tel = base_lib_BaseUtils::getStr($path_data["phone_infor"], "string", "");
		$ext_info = base_lib_BaseUtils::getStr($path_data["ext_infor"], "string", "");
		$head_photo = base_lib_BaseUtils::getStr($path_data["head_photo"], "string", "");
		$account_id = base_lib_BaseUtils::getStr($path_data["account_id"], "int", "0");
		$mobile_phone = base_lib_BaseUtils::getStr($path_data["phone"], "string", "");
		$password = base_lib_BaseUtils::getStr($path_data["password"], "string", "");
		if (!empty($password)) {
			$password = $validator->getPassword($password);
		}
		if (!$account_id) {
			echo json_encode(array ("status" => false, "msg" => '参数错误'));
			die;
		}
		if (!$link_tel && !$mobile_phone) {
			echo json_encode(array ("status" => false, "msg" => '手机/办公电话必填一项！'));
			die;
		}
		
		if ($validator->has_err) {
			echo json_encode(array ("status" => false, "msg" => $validator->err[0]));
			die;
		}
		//如果修改人不是自己。判断是否是主账号
		$cookie_account_id = base_lib_BaseUtils::getCookie('accountid');
		$account = $service_company_account->getAccount($account_id, 'company_id,user_id,state,is_effect,password,user_name,head_photo');
		$cookie_account = $service_company_account->getAccount($cookie_account_id, 'company_id,is_main,user_id');
		if (base_lib_BaseUtils::nullOrEmpty($account) || base_lib_BaseUtils::nullOrEmpty($cookie_account)) {
			echo json_encode(array ("status" => false, "msg" => '参数错误'));
			die;
		}
		if ($account_id != $cookie_account_id) {
			if ($cookie_account['company_id'] != $account['company_id']) {
				echo json_encode(array ("status" => false, "msg" => '帐号不属于同一个企业'));
				die;
			}
			if (!$cookie_account['is_main']) {
				echo json_encode(array ("status" => false, "msg" => '不是主账号,不能修改其他帐号'));
				die;
			}
		}
		
		//如果不是主账号操作，判断是否停用删除
		if (!$cookie_account['is_main'] && !$account['state']) {
			echo '该账户已停用';
			
			return;
		}
		
		if (!$cookie_account['is_main'] && !$account['is_effect']) {
			echo '该账户已删除';
			
			return;
		}
		if (!$link_tel && ($zone_info || $ext_info)) {
			echo json_encode(array ("status" => false, "msg" => "办公电话不正确！"));
			die;
		}
		
		if ($zone_info) {
			$link_tel = $zone_info . '-' . $link_tel;
		}
		if ($ext_info) {
			$link_tel = $link_tel . '-' . $ext_info;
		}
		
		$items = array (
			'user_name'    => $user_name,
			'mobile_phone' => $mobile_phone,
			'station'      => $station,
			'link_tel'     => $link_tel,
			'head_photo'   => $head_photo,
		);
		if ($password !== '') {
			$items['password'] = base_lib_BaseUtils::md5_16($password);
		}
		$is_app_login_out = false;
		//如果是主账号 可以修改账号名
		if ($cookie_account["is_main"] && $account_id != $cookie_account_id) {
			//主账号可以修改账号名
			$user_id = $validator->getStr($path_data["user_id"], 1, 20, "请输入20字内的账户名");
			if ($validator->has_err) {
				echo json_encode(array ("status" => false, "msg" => $validator->err[0]));
				die;
			}
			if ($account["user_id"] != $user_id) {
				//修改了账号名
				$service_company_account = new base_service_company_account();
				$result = $service_company_account->checkAccountUserId($this->_userid, $user_id, $account_id);
				if ($result == false) {
					echo json_encode(array ("status" => false, "msg" => "该账户名已被注册，请更换"));
					die;
				}
				$items["user_id"] = $user_id;
				if ($cookie_account_id != $account_id) {
					$is_app_login_out = true;
				}
			}
			
		}
		
		if ($cookie_account["is_main"] && !empty($items["password"]) && $account["password"] != $items["password"] && $account_id != $cookie_account_id) {
			$is_app_login_out = true;
		}
		$result = $service_company_account->updateAccount($account_id, $items);
		if ($result !== false) {
			$companyaccountlog_service = new base_service_company_companyaccountlog();
			$ervice_common_account_accountoperatetype = new base_service_common_account_accountoperatetype();
			$service_common_account_accountlogfrom = new base_service_common_account_accountlogfrom();
			$insertItems = array (
				"company_id"   => $account['company_id'],
				"account_id"   => $cookie_account_id,
				"operate_type" => $ervice_common_account_accountoperatetype->account_edit,
				"content"      => "修改了账号" . $account['user_id'],
				"create_time"  => date("Y-m-d H:i:s", time()),
				"source"       => $service_common_account_accountlogfrom->website,
			);
			$companyaccountlog_service->addLogToMongo($insertItems);
			if ($is_app_login_out) {
				//修改账户名 将企业APP账号踢下线
				$service_appkey = new base_service_company_app_appkey();
				$service_appkey->loginOut($account_id);
			}
			//如果修改了用户名 则同步修改融云
			if ($account["user_name"] != $items["user_name"] || $account["head_photo"] != $items["head_photo"]) {
				$service_rong = new base_service_company_app_rong();
				$service_rong->refreshRong($account_id, $account["company_id"]);
			}
			echo json_encode(array ("status" => true));
			die;
		}
		
		echo json_encode(array ("status" => false, "msg" => "修改账户失败，请稍候再试"));
		die;
	}
	
	/**
	 * @desc 修改帐号密码
	 */
	public function pageEditPwd($inPath) {
		if (!$this->canDo("update_password")) {
			echo json_encode(array ("status" => false, "msg" => '无权限访问，没有开通相应权限'));
			die;
		}
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$service_company_account = new base_service_company_account();
		$validator = new base_lib_Validator();
		$oldPwd = $validator->getStr($path_data["oldPwd"], 1, 20, "请输入正确的旧密码");
		$newPwd = $validator->getPassword($path_data["newPwd"]);
		$account_id = base_lib_BaseUtils::getStr($path_data["account_id"], "int", "0");
		
		if (!$account_id) {
			echo json_encode(array ("status" => false, "msg" => '参数错误'));
			die;
		}
		
		if ($validator->has_err) {
			echo json_encode(array ("status" => false, "msg" => $validator->err[0]));
			die;
		}
		//判断旧密码是否一致
		$account = $service_company_account->getAccount($account_id, array ('company_id,password,user_id'));
		if (base_lib_BaseUtils::nullOrEmpty($account)) {
			echo json_encode(array ("status" => false, "msg" => '参数错误'));
			die;
		}
		if (base_lib_BaseUtils::md5_16($oldPwd) != $account['password']) {
			echo json_encode(array ("status" => false, "msg" => '原密码不正确'));
			die;
		}
		
		//如果修改人不是自己。判断是否是主账号
		$cookie_account_id = base_lib_BaseUtils::getCookie('accountid');
		$cookie_account = $service_company_account->getAccount($cookie_account_id, 'company_id,is_main,user_id');
		if (base_lib_BaseUtils::nullOrEmpty($cookie_account)) {
			echo json_encode(array ("status" => false, "msg" => '参数错误'));
			die;
		}
		if ($account_id != $cookie_account_id) {
			if ($cookie_account['company_id'] != $account['company_id']) {
				echo json_encode(array ("status" => false, "msg" => '帐号不属于同一个企业'));
				die;
			}
			if (!$cookie_account['is_main']) {
				echo json_encode(array ("status" => false, "msg" => '不是主账号,不能修改其他帐号的密码'));
				die;
			}
		}
		
		if ($cookie_account["is_main"] && $account_id == $cookie_account_id) {
			$service_company = new base_service_company_company();
			$result = $service_company->updateCompanyPassword($account['company_id'], $newPwd);
		}
		else {
			$result = $service_company_account->updateAccount($account_id, array ('password' => base_lib_BaseUtils::md5_16($newPwd)));
		}
		if ($result) {
			$companyaccountlog_service = new base_service_company_companyaccountlog();
			$ervice_common_account_accountoperatetype = new base_service_common_account_accountoperatetype();
			$service_common_account_accountlogfrom = new base_service_common_account_accountlogfrom();
			$insertItems = array (
				"company_id"   => $account['company_id'],
				"account_id"   => $cookie_account_id,
				"operate_type" => $ervice_common_account_accountoperatetype->account_pwd,
				"content"      => "修改了账号" . $account['user_id'] . '密码',
				"create_time"  => date("Y-m-d H:i:s", time()),
				"source"       => $service_common_account_accountlogfrom->website,
			);
			$companyaccountlog_service->addLogToMongo($insertItems);
			//修改密码后 将企业APP账号踢下线
//            $service_appkey = new base_service_company_app_appkey();
//            $service_appkey->loginOut($account_id);
			echo json_encode(array ("status" => true));
			die;
		}
		
		echo json_encode(array ("status" => false, "msg" => "修改密码失败，请稍候再试"));
		die;
	}
	
	/**
	 * @desc 上传账户图片
	 */
	public function pageSaveAcccountImages($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$file = array_pop($_FILES);
		$companyxml = SXML::load("../config/company/company.xml");
		$file_types = $companyxml->TemplateImageExtensions;
		$file_max_size = $companyxml->TemplateImageMaxSize;
		
		//检查是否有文件
		if ($file == null) {
			$json['status'] = false;
			$json['errorMsg'] = '请选择您要上传的图片';
			echo json_encode($json);
			
			return;
		}
		
		//检查是否上传有错
		if ($file['error']) {
			$json['status'] = false;
			$json['errorMsg'] = '上传失败';
			echo json_encode($json);
			
			return;
		}
		
		//检查大小
		$file_max_size = 1024;//暂时设为1M
		if ($file['size'] > $file_max_size * 1024) {
			$json['status'] = false;
			$json['errorMsg'] = '请上传小于' . $file_max_size . 'KB的图片';
			echo json_encode($json);
			
			return;
		}
		
		if (!is_null($companyxml)) {
			$photopath = $companyxml->VirtualName . "/" . $companyxml->TemplateHeaderFolder . "/" . date("Y-m-d");
		}
		else {
			$photopath = date("Y-m-d");
		}
		
		$ext = strtolower(substr(strrchr($file["name"], '.'), 1));
		$arr_file_type = explode(',', $file_types);
		if (!in_array('.' . $ext, $arr_file_type)) {
			$json['status'] = false;
			$json['errorMsg'] = '请上传格式为' . $file_types . '的图片';
			echo json_encode($json);
			
			return;
		}
		
		$file_name = date("md") . rand(100000, 999999);
		$postvar = array (
			"type" => "file",
			"path" => $photopath,
			"name" => "{$file_name}.{$ext}",
		);
		
		$result = base_lib_Uploadfilesv::postfile($postvar, $file['name'], $file['tmp_name'], $content_type = "image/png");
		
		if ($result) {
			$data["name"] = "/{$photopath}/{$file_name}.{$ext}";
			$data["url"] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . "/" . $photopath . "/{$file_name}.{$ext}";
			$data["status"] = true;
			
			//如果有account_id，替换原来head_photo，并且删掉原来的图片
			$account_id = base_lib_BaseUtils::getStr($path_data["account_id"], "string", "");
			if ($account_id) {
				$service_company_account = new base_service_company_account();
				$account = $service_company_account->getAccount($account_id, 'head_photo');
				$this->delAcccountTempImage($account['head_photo']);
				$service_company_account->updateAccount($account_id, array ('head_photo' => $data["name"]));
			}
			
			echo json_encode($data);
			
			return;
		}
		
		echo json_encode(array ('status' => false, 'errorMsg' => '上传图片失败'));
		
		return;
	}
	
	/**
	 * 删除图片
	 */
	public function pageDelAcccountImage($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$account_id = base_lib_BaseUtils::getStr($path_data["account_id"], "string", "");
		$imgname = base_lib_BaseUtils::getStr($path_data["imgname"], "string", "");
		$result['status'] = $this->delAcccountTempImage($imgname);
		
		//如果有account_id，替换原来head_photo，并且删掉原来的图片
		if ($account_id) {
			$service_company_account = new base_service_company_account();
			$service_company_account->updateAccount($account_id, array ('head_photo' => ''));
		}
		
		echo json_encode($result);
		
		return;
	}
	
	private function delAcccountTempImage($imgname) {
		$status = false;
		if (!base_lib_BaseUtils::nullOrEmpty($imgname)) {
			$params['names'] = array ($imgname);
			$params['authenticate'] = "templateHeader";
			$result = base_lib_Uploadfilesv::delFile($params);
			if (isset($result['fileCount']) && $result['fileCount'] > 0) {
				$status = true;
			}
		}
		
		return $status;
	}
	
	public function pageAddAccount($inPath) {
		if (!$this->canDo("update_account")) {
			echo json_encode(array ("status" => false, "msg" => "无权限访问，没有开通相应权限"));
			die;
		}
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$validator = new base_lib_Validator();
		//$mobile_phone       = $validator->getMobile($path_data["mobile_phone"], "手机号码参数错误");
		$password = $validator->getPassword($path_data["password"]);
		$user_name = $validator->getStr($path_data["user_name"], 2, 20, "请输入正确的姓名");
		$user_id = $validator->getStr($path_data["user_id"], 1, 20, "请输入20字内的账户名");
		$station = base_lib_BaseUtils::getStr($path_data["station"], "string", "");
		$link_tel1 = base_lib_BaseUtils::getStr($path_data["link_tel1"], "string", "");
		$link_tel = base_lib_BaseUtils::getStr($path_data["link_tel2"], "string", "");
		$link_tel3 = base_lib_BaseUtils::getStr($path_data["link_tel3"], "string", "");
		$head_photo = base_lib_BaseUtils::getStr($path_data["head_photo"], "string", "");
		$mobile_phone = base_lib_BaseUtils::getStr($path_data["mobile_phone"], "string", "");
		
		$resource_type = base_lib_BaseUtils::getStr($path_data["resource_type"], "int", 1);
		$job_num = base_lib_BaseUtils::getStr($path_data["job_num"], "int", 0);
		$job_boutique = base_lib_BaseUtils::getStr($path_data["job_boutique"], "int", 0);//四川新特有
		$point_job_boutique = base_lib_BaseUtils::getStr($path_data["point_job_boutique"], "int", 0);//四川新特有
		$point_job_refresh = base_lib_BaseUtils::getStr($path_data["point_job_refresh"], "int", 0);
		$point_message = base_lib_BaseUtils::getStr($path_data["point_message"], "int", 0);//重庆新特有
		$point_chat = base_lib_BaseUtils::getStr($path_data["point_chat"], "int", 0);//重庆新特有
		$spread = base_lib_BaseUtils::getStr($path_data["spread"], "float", 0.0);
		$resume_num = base_lib_BaseUtils::getStr($path_data["resume_num"], "int", 0);
		$phone_code = base_lib_BaseUtils::getStr($path_data["chatCodex"], "string", "");
		$is_phoneCode = base_lib_BaseUtils::getStr($path_data["is_phoneCode"], "string", "");
		
		$ser_data = compact('job_num', 'job_boutique', 'point_job_boutique', 'point_job_refresh', 'point_message', 'point_chat', 'spread', 'resume_num');
		
		if ($validator->has_err) {
			echo json_encode(array ("status" => false, "msg" => $validator->err[0]));
			die;
		}
		if (!$mobile_phone) {
			echo json_encode(array ("status" => false, "msg" => "手机号必须填写！"));
			die;
		}
		if (!$link_tel && !$mobile_phone) {
			echo json_encode(array ("status" => false, "msg" => "手机/办公电话必填一项！"));
			die;
		}
		if (!$link_tel && ($link_tel1 || $link_tel3)) {
			echo json_encode(array ("status" => false, "msg" => "办公电话不正确！"));
			die;
		}
		
		if ($link_tel1) {
			$link_tel = $link_tel1 . '-' . $link_tel;
		}
		if ($link_tel3) {
			$link_tel = $link_tel . '-' . $link_tel3;
		}
		$service_company_account = new base_service_company_account();
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$fields = 'company_id,is_main,user_id,user_name,mobile_phone,link_tel,station,head_photo,last_login_time';
		$account = $service_company_account->getAccount($account_id, $fields);
		
		if (!$account['is_main']) {
			echo json_encode(array ("status" => false, "msg" => '不是主账号，不能添加子账号'));
			die;
		}
		
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid);
		$companyresources = $company_resources->getCompanyServiceSource();
		//重庆套餐
		$account_list_temp = $service_company_account->getAccountList($account['company_id'], $fields);
		$account_list = $account_list_temp->items;
		if ($companyresources['isCqNewService']) {
			if ($companyresources['cq_release_point_sub_account'] < 1) {
				echo json_encode(array ("status" => false, "msg" => "最多只能添加{$companyresources['cq_point_sub_account']}个子账号"));
				die;
			}
		}
		else {
			$companyxml = SXML::load("../config/company/company.xml");
			$account_limit = $companyxml->AccountLimit;
			
			
			if ($account_limit < count($account_list)) {
				echo json_encode(array ("status" => false, "msg" => "最多只能添加{$account_limit}个子账号"));
				die;
			}
		}
		
		
		foreach ($account_list as $value) {
			if ($value['user_id'] == $user_id) {
				echo json_encode(array ("status" => false, "msg" => "用户名已存在"));
				die;
			}
		}
		
		$items = array (
			'user_id'       => $user_id,
			'user_name'     => $user_name,
			'mobile_phone'  => $mobile_phone,
			'password'      => base_lib_BaseUtils::md5_16($password),
			'station'       => $station,
			'link_tel'      => $link_tel,
			'head_photo'    => $head_photo,
			'company_id'    => $account['company_id'],
			'is_main'       => 0,
			'resource_type' => $resource_type,
		);
		if ($companyresources['isCqNewService']) {
			$params = array (
				'consume_type' => 'accounts',
				'company_id'   => $account['company_id'],
			);
			$consume_result = $company_resources->consume('cq_setmeal_consume', $params);
			if ($consume_result['code'] != 200) {
				echo json_encode(array ("status" => false, "msg" => $consume_result['msg']));
				die;
			}
		}
		/*
         * .将手机号作为必填项，输入框下面加上文字描述；座机号作为选填项
         2.点击保存时，判断该手机号有无注册汇博个人账号，没有则静默注册
         3.将创建的子账号与该手机号绑定，判断：
         ①该手机号已绑定其他账号，子账号创建失败，提示“手机号已和其他账号绑定，请更换”
         ②该手机号未绑定其他账号，子账号创建成功并绑定。
         */
		if ($is_phoneCode) {
			//验证短信验证码
			$service_validate_code = new base_service_hractivity_validationcode();
			$valid = $service_validate_code->getLastValidation($mobile_phone, "id,validation_code,deadline,send_reason");
			if (empty($valid)) {
				echo json_encode(array ("status" => false, 'msg' => "未发送验证码，或验证码已失效，请重新获取验证码"));
				die;
			}
			if (strtotime($valid['deadline']) < time()) {
				echo json_encode(array ("status" => false, 'msg' => "对不起，您的验证码已过期，请重新获取验证码"));
				die;
			}
			if ($valid['validation_code'] != $phone_code) {
				echo json_encode(array ("status" => false, 'msg' => "对不起，您的验证码错误"));
				die;
			}
			$service_validate_code->updateValidStatus($valid["id"], 1); //验证成功
			//对该手机号进行解绑
			$result = $this->_delBindAccountByPhone($mobile_phone);
			if (!$result['status']) {
				echo json_encode(array ("status" => false, 'msg' => "解绑失败,请刷新重试"));
				die;
			}
		}
		$re = $this->ChechAccountByMobilePhone($mobile_phone);
		if (!$re['status']) {
			echo json_encode(array ("status" => false, "msg" => $re['msg'], 'data' => $re['data']));
			die;
		}
		if ($new_account_id = $service_company_account->addAccount($items)) {
			$companyaccountlog_service = new base_service_company_companyaccountlog();
			$ervice_common_account_accountoperatetype = new base_service_common_account_accountoperatetype();
			$service_common_account_accountlogfrom = new base_service_common_account_accountlogfrom();
			if ($resource_type == 2) {
				$ser_data['account_id'] = $new_account_id;
				$ser_data['company_id'] = $this->_userid;
				$service_company_accountservice = new base_service_company_accountservice();
				$service_company_accountservice->addSer($ser_data);
			}
			//将account账号和手机号的求职者账号进行绑定
			$related_result = $this->_bindAccount($new_account_id, $mobile_phone);
			if (!$related_result['status']) {
				echo json_encode(array ("status" => false, "msg" => $related_result['msg']));
				die;
			}
			$insertItems = array (
				"company_id"   => $account['company_id'],
				"account_id"   => $account_id,
				"operate_type" => $ervice_common_account_accountoperatetype->account_add,
				"content"      => "添加帐号" . $user_id,
				"create_time"  => date("Y-m-d H:i:s", time()),
				"source"       => $service_common_account_accountlogfrom->website,
			);
			$companyaccountlog_service->addLogToMongo($insertItems);
			echo json_encode(array ("status" => true));
			die;
		}
		
		echo json_encode(array ("status" => false, "msg" => "添加子账户失败，请稍候再试"));
		die;
	}
	
	
	/**
	 * 删除用户
	 */
	public function pageDelAccount($inPath) {
		if (!$this->canDo("update_account")) {
			echo json_encode(array ("status" => false, "msg" => '无权限访问，没有开通相应权限'));
			die;
		}
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$account_id = base_lib_BaseUtils::getStr($path_data["account_id"], "int", "0");
		
		if (!$account_id) {
			echo json_encode(array ("status" => false, "msg" => '参数错误'));
			die;
		}
		
		$service_company_account = new base_service_company_account();
		//如果修改人不是自己。判断是否是主账号
		$cookie_account_id = base_lib_BaseUtils::getCookie('accountid');
		$account = $service_company_account->getAccount($account_id, array ('company_id,user_id,is_main'));
		$cookie_account = $service_company_account->getAccount($cookie_account_id, array ('company_id,is_main,user_id'));
		if (base_lib_BaseUtils::nullOrEmpty($account) || base_lib_BaseUtils::nullOrEmpty($cookie_account)) {
			echo json_encode(array ("status" => false, "msg" => '参数错误'));
			die;
		}
		if ($account_id != $cookie_account_id) {
			if ($cookie_account['company_id'] != $account['company_id']) {
				echo json_encode(array ("status" => false, "msg" => '帐号不属于同一个企业'));
				die;
			}
			if (!$cookie_account['is_main']) {
				echo json_encode(array ("status" => false, "msg" => '不是主账号,不能删除其他帐号'));
				die;
			}
		}
		$items = array (
			'is_effect' => 0,
			'del_time'  => date("Y-m-d H:i:s", time()),
		);
//        if($service_company_account->updateAccount($account_id, $items)){
		if ($service_company_account->newUpAccount($account_id, $items)) {
			//删除子账号，自动取消微信绑定
			$service_weixin_companyweixin = new base_service_weixin_companyweixin();
			$weixin = $service_weixin_companyweixin->getWeixinByAccount($account_id, 'open_id');
			if (!base_lib_BaseUtils::nullOrEmpty($weixin)) {
				$service_weixin_companyweixin->updateCompanyWeixin($weixin['open_id'], $this->_userid, array ('is_effect' => 0, "account_id" => 0));
			}
			//删除帐号后，发布的职位改为主账号
			if (!$account['is_main']) {
				$main_account = $service_company_account->getMainAccount($account['company_id'], "account_id");
				$service_company_job = new base_service_company_job_job();
				$service_company_job->updateJobAccount(array ($account['company_id']), $account_id, $main_account['account_id']);
			}
			
			$companyaccountlog_service = new base_service_company_companyaccountlog();
			$ervice_common_account_accountoperatetype = new base_service_common_account_accountoperatetype();
			$service_common_account_accountlogfrom = new base_service_common_account_accountlogfrom();
			$insertItems = array (
				"company_id"   => $account['company_id'],
				"account_id"   => $cookie_account_id,
				"operate_type" => $ervice_common_account_accountoperatetype->account_delete,
				"content"      => "删除帐号" . $account['user_id'],
				"create_time"  => date("Y-m-d H:i:s", time()),
				"source"       => $service_common_account_accountlogfrom->website,
			);
			$companyaccountlog_service->addLogToMongo($insertItems);
			echo json_encode(array ("status" => true));
			die;
		}
		
		echo json_encode(array ("status" => false, "msg" => "删除账户失败，请稍候再试"));
		die;
	}
	
	/**
	 * 停用，启用用户
	 */
	public function pageSetAccountState($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$account_id = base_lib_BaseUtils::getStr($path_data["account_id"], "int", "0");
		
		if (!$account_id) {
			echo json_encode(array ("status" => false, "msg" => '参数错误'));
			die;
		}
		
		$service_company_account = new base_service_company_account();
		//如果修改人不是自己。判断是否是主账号
		$cookie_account_id = base_lib_BaseUtils::getCookie('accountid');
		$account = $service_company_account->getAccount($account_id, array ('company_id,state,user_id'));
		$cookie_account = $service_company_account->getAccount($cookie_account_id, array ('company_id,is_main,user_id'));
		if (base_lib_BaseUtils::nullOrEmpty($account) || base_lib_BaseUtils::nullOrEmpty($cookie_account)) {
			echo json_encode(array ("status" => false, "msg" => '参数错误'));
			die;
		}
		if ($account_id != $cookie_account_id) {
			if ($cookie_account['company_id'] != $account['company_id']) {
				echo json_encode(array ("status" => false, "msg" => '帐号不属于同一个企业'));
				die;
			}
			if (!$cookie_account['is_main']) {
				echo json_encode(array ("status" => false, "msg" => '不是主账号,不能操作其他帐号'));
				die;
			}
		}

//        if($service_company_account->updateAccount($account_id, array('state'=>!$account['state']))){
		if ($service_company_account->newUpAccount($account_id, array ('state' => !$account['state']))) {
			$operate = $account['state'] ? '停用' : '启用';
			$companyaccountlog_service = new base_service_company_companyaccountlog();
			$ervice_common_account_accountoperatetype = new base_service_common_account_accountoperatetype();
			$service_common_account_accountlogfrom = new base_service_common_account_accountlogfrom();
			$insertItems = array (
				"company_id"   => $account['company_id'],
				"account_id"   => $cookie_account_id,
				"operate_type" => $ervice_common_account_accountoperatetype->account_state,
				"content"      => "{$operate}帐号" . $account['user_id'],
				"create_time"  => date("Y-m-d H:i:s", time()),
				"source"       => $service_common_account_accountlogfrom->website,
			);
			$companyaccountlog_service->addLogToMongo($insertItems);
			echo json_encode(array ("status" => true));
			die;
		}
		
		echo json_encode(array ("status" => false, "msg" => "操作账户失败，请稍候再试"));
		die;
	}
	
	/**
	 * 会员状态
	 */
	private function _getMemberState($company_id, $account_id) {
		
		$memberInfo['user_name'] = '';
		//开通情况 ,0未开通，1已开通
		$memberInfo['is_opened'] = '0';
		//会员级别名
		$memberInfo['merber_level_name'] = '';
		//是否过期
		$memberInfo['merber_is_timeout'] = false;
		//过期时间
		$memberInfo['merber_timeout'] = '';
		//过期时间倒计时
		$memberInfo['timeout_countdown'] = '';
		//下载简历数
		$memberInfo['resume_down_num'] = 0;
		//发布职位数
		$memberInfo['job_release_num'] = 0;
		//认证状态 0,不通过;1,通过;2,等待;3,未审核
		$memberInfo['licence_state'] = '0';
		$memberInfo['licence_audit_reply'] = '';
		$memberInfo['member_info'] = $this->getCompanyMemberInfo();
		
		/*// 获取账户金额
		$service_cashaccount = new base_service_company_charge_companycashaccount();
		$account_overage = $service_cashaccount->getCompanyAccount($company_id);
		$memberInfo['account_overage'] =  sprintf("%.2f", $account_overage);
		
		// 获取推广金余额
		$service_spread = new base_service_company_spread_spread();
		$spread_overage = $service_spread->getEffectConsume($company_id);
		$memberInfo['spread_overage'] =  sprintf("%.2f", ($spread_overage['count']-$spread_overage['used']));*/
		
		
		$company_resources = base_service_company_resources_resources::getInstance($company_id, true, $account_id);
		$resource_data = $company_resources->getCompanyServiceSource(["_account_resource"]);
		$memberInfo['spread_overage'] = $resource_data['spread_overage'];
		$memberInfo['account_overage'] = $resource_data['account_overage'];
		
		//会员情况
		$service_company = new base_service_company_company();
		$company = $service_company->getCompany($company_id, null, 'company_id,company_flag,company_name,user_id,com_level,start_time,end_time,is_audit,audit_state');
		if (base_lib_BaseUtils::nullOrEmpty($company)) {
			return;
		}
		
		$company_percent = 0;
		$company_nocomplate = $service_company->getComPercentAndNoComplete($company['company_id'], true, $company_percent);
		$memberInfo['no_complete'] = $company_nocomplate;
		$memberInfo['precent'] = $company_percent . '%';
		$memberInfo['company_name'] = $company['company_name'];
		$memberInfo['company_link'] = base_lib_Rewrite::company($company['company_id'], $company['company_flag']);
		$memberInfo['user_name'] = $company['user_id'];
		
		$service_resource = base_service_company_resources_resources::getInstance($company_id);
		$service_checkaudit = new base_service_common_companyaudit();
		list($is_audit, $audit_params) = $service_resource->getCompanyAuditStatus();
		if (!empty($audit_params)) {
			$memberInfo = array_merge($memberInfo, $audit_params);
		}
		$memberInfo["is_audit"] = $is_audit;
		$memberInfo["service_checkaudit"] = $service_checkaudit;
		
		if ($company["is_audit"] == "0") {
			$memberInfo['licence_audit_reply'] = $licencevaliate['audit_reply'];
		}
		
		list($is_opened, $day) = $this->getCompanyMemberInfo();
		if ($day < -10 && !$is_opened) {
			$memberInfo['is_opened'] = '0';
		}
		else {
			$memberInfo['merber_timeout'] = date('Y-m-d', strtotime($company['end_time']));
			$service_level = new base_service_company_level();
			$level = $service_level->getLevelById($company['com_level']);
			if ($is_opened && $day < 0) {
				$memberInfo['is_opened'] = '1';
				$memberInfo['merber_level_name'] = $level['com_level_name'] . '(已过期)';
				$memberInfo['merber_is_timeout'] = true;
			}
			else {
				if (abs($day) <= 30) {
					$memberInfo['timeout_countdown'] = abs($day);
				}
				$service_comservice = new base_service_company_service_comservice();
				$memberInfo['is_opened'] = '1';
				$memberInfo['merber_level_name'] = $level['com_level_name'];
				$memberInfo['resume_down_num'] = $resource_data['resume_num_release'];
				$memberInfo['job_release_num'] = $resource_data['job_release_num'] + $resource_data['job_boutique_release'];
			}
		}
		
		//获取优惠券信息 -- 暂不使用
		$service_coupon = new base_service_company_commoncoupon();
		$coupon_info = $service_coupon->getCouponCount($company_id);
		if ($coupon_info !== false && !base_lib_BaseUtils::nullOrEmpty($coupon_info) && false) {
			$memberInfo['used_count'] = $coupon_info['used_count'];
			$memberInfo['expire_count'] = $coupon_info['expire_count'];
			$memberInfo['can_use_count'] = $coupon_info['can_use_count'];
		}
		
		//活动优惠劵
		$base_allotCoupon = new base_service_company_coupon_allotCoupon();
		$memberInfo['can_use_count'] += count($base_allotCoupon->GetAllotListByCompanyId($this->_userid, ['state' => 1]));
		$memberInfo['used_count'] += count($base_allotCoupon->GetAllotListByCompanyId($this->_userid, ['state' => 2]));
		$memberInfo['expire_count'] += count($base_allotCoupon->GetAllotListByCompanyId($this->_userid, ['state' => -1]));
		
		return $memberInfo;
	}
	
	private function _getEnableJobNum($company_id) {
		if (!$company_id) {
			return false;
		}
		$resource_service = base_service_company_resources_resources::getInstance($company_id);
		//获得未开通服务的企业默认职位数量
		$xml = SXML::load('../config/company/company.xml');
		$default_job_num = 0;
		if (!is_null($xml)) {
			$default_job_num = $xml->DefaultJobNum;  //非会员默认职位数量
		}
		
		$comservice_service = new base_service_company_service_comservice();
		$comservice = $comservice_service->getComService($resource_service->main_company_id, 'job_num,service_id,resume_num,resume_down_num,urgent_point,stick_point,'
		                                                                                   . 'is_enabled_intelligentrecommend,is_enabled_autorefresh,start_time,is_enabled_messagenotice');
		
		$company_service = new    base_service_company_company();
		$company = $company_service->getCompany($resource_service->main_company_id, '1', 'end_time,com_level');
		$service_source['default_job_num'] = (empty($comservice) ? intval($default_job_num) : $comservice["job_num"]); // 企业可发布职位数量
		
		//企业已有职位数量
		$service_job_status = new base_service_common_jobstatus();
		$service_job = new base_service_company_job_job();
		$service_source['has_pub_job_num'] = $service_job->getJobCount($resource_service->all_accounts, $service_job_status->pub);  //已发布职位数量
		
		$enable_jobs = intval($service_source['default_job_num'] - $service_source['has_pub_job_num']);
		$enable_jobs = $enable_jobs > 0 ? $enable_jobs : 0;
		
		return $enable_jobs;
	}
	
	/**
	 *
	 * 修改企业账户登录名
	 * @param array $inPath 参数信息
	 */
	public function pageModifyName($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$success = base_lib_BaseUtils::getStr($path_data['success'], 'string', '');
		if (base_lib_BaseUtils::nullOrEmpty($success)) {
			return;
		}
//    	$service_company = new base_service_company_company();
//   		$company = $service_company->getCompany($this->_userid,null,'user_id');
//   		if(base_lib_BaseUtils::nullOrEmpty($company)){
//   			return;
//   		}
		$service_company_account = new base_service_company_account();
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$account = $service_company_account->getAccount($account_id, array ('user_id'));
		if (base_lib_BaseUtils::nullOrEmpty($account)) {
			return;
		}
		$this->_aParams['user_id'] = $account['user_id'];
		$this->_aParams['success'] = $success;
		
		return $this->render('sysmanage/modusername.html', $this->_aParams);
	}
	
	/**
	 *
	 * 执行修改企业账户登录名
	 * @param unknown_type $inPath
	 */
	public function pageMofifyNameDo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$val = new base_lib_Validator();
		$password = $val->getPassword($path_data['txtPassword']);
		$user_id = $val->getStr($path_data['txtUserId'], '3', '20', '请填写长度为3-20位的用户名');
		if ($val->has_err) {
			echo header("Content-type:text/plain;charset=utf-8");
			echo $val->toJsonWithHtml();
			
			return;
		}
// 	   	$service_company = new base_service_company_company();
// 	   	// 验证登录密码是否相同
//	    if(!$service_company->checkPwd($this->_userid,$password)){
//	    	$val->addErr('登录密码错误');
//	    	echo $val->toJsonWithHtml();
//	        return;
//	    }
//	    if(!$this->checkUserName($user_id)) {
//	    	echo header("Content-type:text/plain;charset=utf-8");
//	        $val->addErr('该用户名已被注册，请更换');
//	        echo $val->toJsonWithHtml();
//	        return;
//	    }
//	    $result = $service_company->updateCompanyUserID($this->_userid,$user_id);
//	    if($result>=0) {
//	    	echo json_encode(array('user_id'=>$user_id,'msg'=>'修改用户名成功'));
//	    }else {
//	    	echo json_encode(array('msg'=>'修改用户名失败'));
//	    }
//	    return;
		$service_company_account = new base_service_company_account();
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$account = $service_company_account->getAccount($account_id, array ('password,user_id'));
		if (base_lib_BaseUtils::nullOrEmpty($account)) {
			$val->addErr('参数错误');
			echo $val->toJsonWithHtml();
			
			return;
		}
		if (base_lib_BaseUtils::md5_16($password) != $account['password']) {
			$val->addErr('登录密码错误');
			echo $val->toJsonWithHtml();
			
			return;
		}
		if ($user_id == $account['user_id']) {
			$val->addErr('填写新的用户名');
			echo $val->toJsonWithHtml();
			
			return;
		}
		$result = $service_company_account->updateAccount($account_id, array ('user_id' => $user_id));
		if ($result) {
			echo json_encode(array ('user_id' => $user_id, 'msg' => '修改用户名成功'));
		}
		else {
			echo json_encode(array ('msg' => '修改用户名失败'));
		}
		
		return;
	}
	
	/**
	 * 修改密码
	 * @param $inPath 参数信息
	 */
	public function pageModifyPassword($inPath) {
		if (!$this->canDo("update_password")) {
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";
			
			return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
		}
		
		return $this->render('sysmanage/modpassword.html');
	}
	
	/**
	 * 执行修改密码
	 * @param $inPath 参数信息
	 */
	public function pageModifyPasswordDo($inPath) {
		if (!$this->canDo("update_password")) {
			exit(json_encode(array ('error' => '无权限访问，没有开通相应权限')));
		}
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$val = new base_lib_Validator();
		$password = $val->getStr($path_data['txtOldPassword'], 6, 18, '登录密码错误');
		$new_password = $val->getPassword($path_data['txtNewPassword']);
		$affirm_new_password = $val->getPassword($path_data['txtRepeatPassword']);
		if ($val->has_err) {
			echo header("Content-type:text/plain;charset=utf-8");
			echo $val->toJsonWithHtml();
			
			return;
		}
		if ($new_password !== $affirm_new_password) {
			exit(json_encode(array ('error' => '两次密码不一致')));
		}

// 	   	$service_company = new base_service_company_company();
// 	   	// 验证登录密码是否相同
//	    if(!$service_company->checkPwd($this->_userid,$password)){
//	        echo json_encode(array('error'=>'登录密码错误'));
//	        return;
//	    }
//	    // 修改密码
//	    $result = $service_company->updateCompanyPassword($this->_userid,$new_password);
//	    if($result!==false) {
//	    	 echo json_encode(array('msg'=>'修改密码成功'));
//	    	 return;
//	    }else {
//	    	echo json_encode(array('error'=>'修改密码失败'));
//	    	return;
//	    }
		$service_company_account = new base_service_company_account();
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$account = $service_company_account->getAccount($account_id, array ('password'));
		if (base_lib_BaseUtils::nullOrEmpty($account)) {
			$val->addErr('参数错误');
			echo $val->toJsonWithHtml();
			
			return;
		}
		if (base_lib_BaseUtils::md5_16($password) != $account['password']) {
			$val->addErr('登录密码错误');
			echo $val->toJsonWithHtml();
			
			return;
		}
		$result = $service_company_account->updateAccount($account_id, array ('password' => base_lib_BaseUtils::md5_16($new_password)));
		if ($result !== false) {
			echo json_encode(array ('msg' => '修改密码成功'));
		}
		else {
			echo json_encode(array ('error' => '修改密码失败'));
		}
		
		return;
	}
	
	/**
	 * 验证用户名
	 * @param array $inPath 参数信息
	 */
	public function checkUserName($user_id) {
		$service_company = new base_service_company_company();
		$ckuser_id = $service_company->doCheckUniqueUserId($user_id);
		if (base_lib_BaseUtils::nullOrEmpty($ckuser_id)) {
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * 开通会员提示
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	public function pageShowApplyVipTips($inPath) {
		$service_company = new base_service_company_company();
		$current_company = $service_company->getCompany($this->_userid, 1, 'com_level,start_time,company_id,end_time');
		if (empty($current_company)) {
			return;
		}
		$current_company['is_opened'] = '1';
		if (empty($current_company['com_level']) || intval($current_company['com_level']) <= 0 || base_lib_BaseUtils::nullOrEmpty($current_company['end_time']) || base_lib_BaseUtils::nullOrEmpty($current_company['start_time'])) {
			$current_company['is_opened'] = '0';
		}
		$this->_aParams["company"] = $current_company;
		$domain = $this->GetDomainInfor();
		//获取招聘顾问
		$companyStateService = new base_service_company_comstate();
		$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
		$this->_aParams["hasHRManager"] = false;
		if (!empty($companyState)) {
			$hrManager = $this->GetHRManager($companyState["net_heap_id"]);
			if (!is_null($hrManager)) {
				$this->_aParams["hasHRManager"] = true;
				$headPhoto = base_lib_BaseUtils::nullOrEmpty($hrManager["head_photo_url"]) ? $domain["defaultPhoto"] : $hrManager["head_photo_url"];
				$hrManager["head_photo_url"] = $domain["image"] . "/" . $domain["photo"] . "/" . $headPhoto;
				$this->_aParams["hrManager"] = $hrManager;
			}
		}
		
		return $this->render('sysmanage/openviptips.html', $this->_aParams);
	}
	
	/**
	 * 开通会员提示 版本2
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	public function pageShowApplyVipTipsV2($inPath) {
		$service_company = new base_service_company_company();
		$current_company = $service_company->getCompany($this->_userid, 1, 'com_level,start_time,company_id,end_time');
		if (empty($current_company)) {
			return;
		}
		$current_company['is_opened'] = '1';
		if (empty($current_company['com_level']) || intval($current_company['com_level']) <= 0 || base_lib_BaseUtils::nullOrEmpty($current_company['end_time']) || base_lib_BaseUtils::nullOrEmpty($current_company['start_time'])) {
			$current_company['is_opened'] = '0';
		}
		$this->_aParams["company"] = $current_company;
		$domain = $this->GetDomainInfor();
		//获取招聘顾问
		$companyStateService = new base_service_company_comstate();
		$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
		$this->_aParams["hasHRManager"] = false;
		if (!empty($companyState)) {
			$hrManager = $this->GetHRManager($companyState["net_heap_id"]);
			if (!is_null($hrManager)) {
				$this->_aParams["hasHRManager"] = true;
				$headPhoto = base_lib_BaseUtils::nullOrEmpty($hrManager["head_photo_url"]) ? $domain["defaultPhoto"] : $hrManager["head_photo_url"];
				$hrManager["head_photo_url"] = $domain["image"] . "/" . $domain["photo"] . "/" . $headPhoto;
				$this->_aParams["hrManager"] = $hrManager;
			}
		}
		//获得客服
		$xml = SXML::load('../config/config.xml');
		if (!is_null($xml)) {
			$this->_aParams['company_ser_phone'] = $xml->CompanyServicePhone;
			$this->_aParams['company_ser_email'] = $xml->CompanyServiceEmail;
			$this->_aParams['person_ser_phone'] = $xml->PersonServicePhone;
			$this->_aParams['technical_phone'] = $xml->TechniquePhone;
			$this->_aParams['technical_email'] = $xml->TechniqueEmail;
			$this->_aParams['company_addr'] = $xml->CompanyAddress;
			$this->_aParams['post_code'] = $xml->PostCode;
			$this->_aParams['fax_num'] = $xml->ShowFaxNumber;
			$this->_aParams["company_ser_name"] = $xml->CustomerServiceName;
			$this->_aParams["company_ser_qq"] = $xml->CustomerServiceQq;
		}
		
		return $this->render('sysmanage/openviptipsv2.html', $this->_aParams);
	}
	
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
	
	// 微信用户列表
	public function pageWeiXin($inPath) {
		//$appid = 'wxe261f9f7edbac142';
		$companywerixinservice = new base_service_weixin_companyweixin();
		$companyweixins = $companywerixinservice->getListByCompanyId($this->_userid, 'nickname,open_id,create_time,account_id', 1);
		$service_account = new base_service_company_account();
		if (!empty($companyweixins)) {
			$account_ids = base_lib_BaseUtils::getProperty($companyweixins, "account_id");
			
			$account_infos = $service_account->getAccountByAccount_ids($account_ids, "account_id,user_name,user_id")->items;
			$account_list = base_lib_BaseUtils::array_key_assoc($account_infos, "account_id");
			foreach ($companyweixins as $k => $v) {
				$companyweixins[ $k ]["account_user_name"] = $account_list[ $v["account_id"] ]["user_id"];
			}
		}
		
		$this->_aParams['companyweixins'] = $companyweixins;
		$xml = SXML::load('../config/config.xml');
		//$openweixinservice = new SOpenWeiXin($appid);
		//$this->_aParams["twodimensioncodeurl"] = $openweixinservice->generateTwoDimensionCode($this->_userid);
		
		//判断当前账号是不是主账号
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		$fields = 'is_main,user_id';
		$account = $service_account->getAccount($account_id, $fields);
		$this->_aParams['is_main'] = $account['is_main'];
		$this->_aParams["account_id"] = $account_id;
		$this->_aParams['title'] = "微信账号 我的账号_{$xml->HuiBoSiteName}";
		
		return $this->render('sysmanage/weixin.html', $this->_aParams);
	}
	
	
	// 取消微信绑定
	public function pageUnBindweixin($inPath) {
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$openid = base_lib_BaseUtils::getStr($params['openid'], 'string', null);
		$companywerixinservice = new base_service_weixin_companyweixin();
		if (empty($openid)) {
			echo $this->jsonMsg(false, '参数无效');
			
			return;
		}
		$weixin = $companywerixinservice->getWeixinByComanyId($this->_userid, $openid, 'app_id', 1);
		if (empty($weixin)) {
			echo $this->jsonMsg(false, '参数无效');
			
			return;
		}
		$result = $companywerixinservice->updateCompanyWeixin($openid, $this->_userid, array ('is_effect' => 0, "account_id" => 0));
		if ($result === false) {
			echo $this->jsonMsg(false, '解绑失败');
		}
		else {
			$openweixinservice = new SOpenWeiXin($weixin['app_id']);
			$msg = "你的账号已经被解绑，解绑是通过登录汇博网站操作的。如果非本人操作，请注意你的企业账号安全性。";
			$openweixinservice->SendMsg($openid, $msg);
			echo $this->jsonMsg(true, '解绑成功');
		}
		
		return;
	}
	
	
	// 简历下载详情
	public function pageResumeDownDetail($inPath) {
		$companyservice = new base_service_company_company();
		
		$company = $companyservice->getCompany($this->_userid, 1, 'company_name');
		
		$this->_aParams['company_name'] = $company['company_name'];
		
		$resumedownservice = new base_service_company_resume_download();
		$service_comservice = new base_service_company_service_comservice();
		// 获取当前服务
		$curComservice = $service_comservice->getComService($this->_userid, 'service_id,resume_num,start_time,end_time');
		if (!empty($curComservice)) {
			// 下载的简历份数
			$curComservice['resumedownnum'] = $resumedownservice->getDownLoadResumeCount($this->_userid, strtotime($curComservice['start_time']), strtotime($curComservice['end_time']));
			//  下载的简历点
			$curComservice['resumedownpoint'] = $resumedownservice->getDownLoadResumePoint($this->_userid, strtotime($curComservice['start_time']), strtotime($curComservice['end_time']));
			$this->_aParams['curComservice'] = $curComservice;
		}
		$curserviceid = (empty($curComservice) ? null : $curComservice['service_id']);
		$preComService = $service_comservice->getPreComService($this->_userid, 'service_id,resume_num,start_time, end_time', $curserviceid);
		if (!empty($preComService)) {
			// 下载的简历份数
			$preComService['resumedownnum'] = $resumedownservice->getDownLoadResumeCount($this->_userid, strtotime($preComService['start_time']), strtotime($preComService['end_time']));
			//  下载的简历点
			$preComService['resumedownpoint'] = $resumedownservice->getDownLoadResumePoint($this->_userid, strtotime($preComService['start_time']), strtotime($preComService['end_time']));
			$preComService['surpluspoint'] = ($preComService['resume_num'] - $preComService['resumedownpoint']);
			$this->_aParams['preComService'] = $preComService;
			
		}
		$this->_aParams['title'] = '简历下载方式变动的说明';
		
		return $this->render('sysmanage/resumedowndetail.html', $this->_aParams);
	}
	
	// 二维码
	public function pageTwoDimensionCode($inPath) {
		$appid = 'wx1961770740d354ee';
		$openweixinservice = new SOpenWeiXin($appid);
		$account_id = base_lib_BaseUtils::getCookie('accountid');
		if (empty($account_id)) {
			echo $this->jsonMsg(true, '', array ('codeurl' => ""));
		}
		$sceen_id = $account_id + SOpenWeiXin::BASE_NUMBER;
		$twodimensioncodeurl = $openweixinservice->generateTwoDimensionCode($sceen_id);
		echo $this->jsonMsg(true, '', array ('codeurl' => $twodimensioncodeurl));
		
		return;
	}
	
	/**
	 * @desc 微信分享朋友圈
	 */
	public function pageWeixinShow($inPath) {
		//获取企业信息信息
		$company_id = $this->_userid;
		$company_service = new base_service_company_company();
		$company_info = $company_service->getCompany($company_id, null, "company_name,company_shortname,company_flag");
		$this->_aParams["company_info"] = $company_info;
		//获取最近10天企业的点击数和点赞数
		$likes_service = new base_service_company_weixinlikes();
		$likes_info = $likes_service->getTotalLikes($company_id, 10);
		$this->_aParams["total_likes"] = base_lib_BaseUtils::nullOrEmpty($likes_info["total_likes"]) ? 0 : $likes_info["total_likes"];
		$this->_aParams["real_likes"] = base_lib_BaseUtils::nullOrEmpty($likes_info["like_num"]) ? 0 : $likes_info["like_num"];
		$this->_aParams["share_url"] = base_lib_Constant::APP_MOBILE_URL . "/companylikes/index/flag-" . $company_info["company_flag"];
		$this->_aParams["image_url"] = base_lib_Constant::COMPANY_URL_NO_HTTP . "/account/qrcode/flag-" . $company_info["company_flag"];
		
		return $this->render("companylikes.html", $this->_aParams);
	}
	
	function pageQrcode($inPath) {
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$validator = new base_lib_Validator();
		$flag = $validator->getStr($params['flag'], "string", "缺少flag公司标识位");
		
		if ($validator->has_err) {
			echo json_encode(array ("status" => false, "msg" => $validator->err[0]));
			exit;
		}
		
		echo SQrcode::png(base_lib_Constant::APP_MOBILE_URL . "/companylikes/index/flag-" . $flag);
		exit;
	}
	
	/**
	 * @desc 单位账户日志查询
	 */
	public function pageAccountLogList($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$this->_aParams['pathdata'] = $pathdata;
		$page_index = base_lib_BaseUtils::getStr($pathdata['page'], 'int', 1);
		$page_size = base_lib_Constant::PAGE_SIZE;
		$companyaccountlog_service = new    base_service_company_companyaccountlog();
		$account_id = base_lib_BaseUtils::getStr($pathdata['account_id'], 'int', 0);
		$bgn_time = base_lib_BaseUtils::getStr($pathdata['stime'], 'string', null);
		$end_time = base_lib_BaseUtils::getStr($pathdata['etime'], 'string', null);
		$result = $companyaccountlog_service->getLogListFromDB($page_size, $page_index, "log_id,company_id,account_id,operate_type,content,create_time", $bgn_time, $end_time, $this->_userid, $account_id, null);
		//获取单位子账户
		$account_service = new  base_service_company_account();
		$sub_account = $account_service->GetSubAccount($this->_userid, "account_id,user_id,user_name");
		$curlogin_accountid = base_lib_BaseUtils::getCookie("accountid");
		if (!base_lib_BaseUtils::nullOrEmpty($curlogin_accountid)) {
			$cur_account = $account_service->getAccount($curlogin_accountid);
			array_push($sub_account, $cur_account);
		}
		
		$data = $result->items;
		if (!empty($data) && count($data) > 0) {
			$arr_accountids = array ();
			foreach ($data as $k => $v) {
				array_push($arr_accountids, $v['account_id']);
			}
			$accounts = $account_service->getAccountByAccount_ids($arr_accountids, "account_id,user_id,user_name")->items;
			if (!empty($accounts)) {
				$accounts = base_lib_BaseUtils::array_key_assoc($accounts, "account_id");
			}
			for ($a = 0; $a < count($data); $a++) {
				$data[ $a ]['company_name'] = $accounts[ $data[ $a ]['account_id'] ]['user_id'];
			}
		}
		$this->_aParams['data'] = $data;
		$this->_aParams['sub_account'] = $sub_account;
		$pager = $this->pageBarFullPath($result->totalSize, $page_size, $page_index, $inPath);
		$this->_aParams["pager"] = $pager;
		
		return $this->render('accountloglist.html', $this->_aParams);
	}
	
	/**
	 * @desc 解除绑定
	 */
	public function pageDelRelatedPerson($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$person_id = base_lib_BaseUtils::getStr($pathdata["person_id"], "int", 0);
		$account_id = base_lib_BaseUtils::getStr($pathdata["account_id"], "int", 0);
		if (empty($person_id) || empty($account_id)) {
			echo json_encode(array ("error" => "解绑失败"));
			exit;
		}
		$service_related = base_service_hractivity_related::getInstance();
		
		$result = $service_related->delRelated($person_id, $this->_userid, $account_id);
		if ($result === false) {
			echo json_encode(array ("error" => "解绑失败"));
			exit;
		}
		echo json_encode(array ("success" => "解绑成功"));
		exit;
	}
	
	
	/**
	 * @desc 绑定企业微信
	 */
	public function pageBindCompanyWx($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$part = base_lib_BaseUtils::getStr($pathdata["part"], "string", '');
		$backurl = base_lib_BaseUtils::getStr($pathdata["backurl"], "string", '');
		$source = base_lib_BaseUtils::getStr($pathdata["source"], "string", '');
		$sid = base_lib_BaseUtils::getStr($pathdata["sid"], "string", '');

		$this->_aParams['part'] = $part;
		$this->_aParams['redirect'] = $backurl;
		$this->_aParams['source'] = $source;
		$this->_aParams['sid'] = $sid;
		
		return $this->render('bindweixin.html', $this->_aParams);
	}
	
	/**
	 * 添加子账号时
	 * 1.将手机号作为必填项，输入框下面加上文字描述；座机号作为选填项
	 * 2.点击保存时，判断该手机号有无注册汇博个人账号，没有则静默注册
	 * 3.将创建的子账号与该手机号绑定，判断：
	 * ①该手机号已绑定其他账号，子账号创建失败，提示“手机号已和其他账号绑定，请更换”
	 * ②该手机号未绑定其他账号，子账号创建成功并绑定。
	 * User:hujian 2019/7/19 17:30:15
	 */
	public function ChechAccountByMobilePhone($mobile_phone) {
		if (!$mobile_phone) {
			return array ('status' => false, 'msg' => "手机号不正确");
		}
		//检查该手机号是否是汇博个人用户
		//获取求职者信息
		$service_person = new base_service_person_person();
		$service_related = base_service_hractivity_related::getInstance();
		$person = $service_person->getPersonByPhone($mobile_phone, null, "person_id,user_name,photo,password", null, 1);
		$person_id = $person["person_id"];
		if (empty($person)) {
			//注册
			$person_id = $this->_registerByMobile($mobile_phone);
			if (!$person_id) {
				return array ('status' => false, 'msg' => "通过手机号注册HR账号失败");
			}
		}
		//判断是否绑定过了
		$related_info = $service_related->getRelatedCompany($person_id, "person_id,company_id,account_id");
		if (!empty($related_info)) {
			$account_id = $related_info['account_id'];
			$service_company_account = new base_service_company_account();
			$fields = 'company_id,is_main,user_id,user_name,mobile_phone,link_tel,station,head_photo,last_login_time';
			$account = $service_company_account->getAccount($account_id, $fields);
			//获取单位名称
			$ser_company = new base_service_company_company();
			$company = $ser_company->getCompany($account['company_id'], "1", "company_name");
			
			return array (
				'status' => false, 'msg' => "该手机号已绑定:{$company['company_name']}，可输入验证码更换绑定",
				"data"   => array (
					'is_bind'   => true,
					'user_name' => $account['user_name'],
				),
			);
		}
		
		return array ('status' => true, 'msg' => "验证通过");
	}
	
	/**
	 * 账号绑定
	 * User:hujian 2019/7/22 9:35:17
	 */
	private function _bindAccount($account_id, $mobile_phone) {
		//获取求职者信息
		$service_person = new base_service_person_person();
		$service_related = base_service_hractivity_related::getInstance();
		
		//判断该账号是否已经被关联
		$company_id = $this->_userid;
		$related_info = $service_related->getRelatedByAccount($account_id, "person_id,company_id,account_id");
		if (!empty($related_info)) {
			return array ('status' => false, 'msg' => "绑定失败，您已经绑定过了");
		}
		
		$person = $service_person->getPersonByPhone($mobile_phone, null, "person_id,user_name,photo,password", null, 1);
		if (empty($person)) {
			$person_id = $this->_registerByMobile($mobile_phone);
		}
		else {
			$person_id = $person["person_id"];
			$related_info = $service_related->getRelatedCompany($person_id, "person_id,company_id,account_id");
			if (!empty($related_info)) {
				return array ('status' => false, 'msg' => "绑定失败，该手机号已经绑定过了，你可以登录汇博企业版APP解绑后再操作");
			}
		}
		if (empty($person_id)) {
			return array ('status' => false, 'msg' => "绑定失败");
		}
		
		$related_result = $service_related->addRelated($person_id, $account_id, $company_id);
		if ($related_result === false) {
			return array ('status' => false, 'msg' => "绑定失败，请重试");
		}
		
		return array ('status' => true, 'msg' => "绑定成功");
	}
	
	/**
	 * 通过手机号，解除绑定
	 * User:hujian 2019/7/22 14:13:35
	 */
	private function _delBindAccountByPhone($mobile_phone) {
		//获取求职者信息
		$service_person = new base_service_person_person();
		$service_related = base_service_hractivity_related::getInstance();
		
		$person = $service_person->getPersonByPhone($mobile_phone, null, "person_id,user_name,photo,password", null, 1);
		$person_id = $person["person_id"];
		$related_info = $service_related->getRelatedCompany($person_id, "person_id,company_id,account_id");
		if (empty($related_info)) {
			return array ('status' => false, 'msg' => "解绑失败，该手机号没有绑定过");
		}
		$account_id = $related_info['account_id'];
		//解除绑定
		$result = $service_related->delRelated($person_id, $this->_userid, $account_id);
		if ($result === false) {
			return array ('status' => false, 'msg' => "解绑失败");
		}
		
		return array ('status' => true, 'msg' => "解绑成功");
	}
	
	/**
	 * 注册求职者
	 * User:hujian 2019/7/22 10:40:38
	 */
	private function _registerByMobile($mobile_phone) {
		$person['user_id'] = '#' . $mobile_phone;
		$person['mobile_phone'] = $mobile_phone;
		$person['mobile_phone_is_validation'] = '1';
		
		$person['password'] = base_lib_BaseUtils::md5_16("hb" . $mobile_phone);
		$person['reg_source'] = 'app_hr_pc';
		$person['person_class'] = 1;
		$person["user_name"] = "HR" . time();
		$resume_id = 0;
		
		//渠道
		$service_actionsource = new base_service_common_actionsource();
		$service_person = new base_service_person_person();
		$action_source = $service_actionsource->account_bind;
		$person_id = $service_person->addPerson($person, $operate, $action_source, $resume_id);
		if (empty($person_id)) {
			return false;
		}
		
		return $person_id;
	}
	
	/**
	 * 验证求职者身份
	 * User:hujian 2019/7/22 10:40:49
	 */
	public function pageSendMobileCode($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$validator = new base_lib_Validator();
		$mobile_phone = $validator->getMobile($path_data['mobile_phone'], "手机不正确");
		/*$seed           = base_lib_BaseUtils::getStr($path_data["seed"],"string","");
        $vcode          = base_lib_BaseUtils::getStr($path_data["vild_code"],"string","");*/
		
		if ($validator->has_err) {
			echo json_encode(array ("status" => false, 'msg' => $validator->err[0]));
			die;
		}
		/*//判断图片验证
        $captcha = new SCaptchalu();
        if ($captcha->verify($seed, $vcode) === false) {
            return array( 'status'=>false, 'msg'=>"验证码错误");
        }*/
		
		$service_company = new base_service_company_company();
		/*--------------boss端企业违规封号处理- 2018-12-15 --------------*/
		$boos_fordden = $service_company->isBossForbid(null, null, null, $mobile_phone, 'bind');
		if ($boos_fordden['is_foribid'] === true) {
			echo json_encode(array ("status" => false, 'msg' => $boos_fordden['msg']));
			die;
		}
		
		list($send_result, $error) = $this->__sendVcode($mobile_phone);
		if ($send_result == false) {
			echo json_encode(array ("status" => false, 'msg' => $error));
			die;
		}
		echo json_encode(array ("status" => true, 'msg' => "验证码发送成功"));
		die;
		
	}
	
	/**
	 * 发送登录验证码
	 * User:hujian 2019/7/22 10:43:12
	 */
	private function __sendVcode($mobile_phone) {
		$service_validate_code = new base_service_hractivity_validationcode();
		$error = "";
		$result = $service_validate_code->addValidationCodeByPc($mobile_phone, $error);
		
		return [$result, $error];
	}
	
	/**
	 * 点击绑定手机号
	 * User:hujian 2019/7/22 11:07:17
	 */
	public function pageAccountBind($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$account_id = base_lib_BaseUtils::getStr($path_data["account_id"], "int", "0");
		
		$service_company_account = new base_service_company_account();
		$fields = 'company_id,is_main,user_id,user_name,mobile_phone,link_tel,station,head_photo,last_login_time';
		$account = $service_company_account->getAccount($account_id, $fields);
		$this->_aParams['account_id'] = $account_id;
		$this->_aParams['account'] = $account;
		
		return $this->render('./sysmanage/accountbind.html', $this->_aParams);
	}
	
	/**
	 * 点击绑定手机号
	 * User:hujian 2019/7/22 13:42:49
	 */
	public function pageAccountBindDo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$account_id = base_lib_BaseUtils::getStr($path_data["account_id"], "int", "0");
		$mobile_phone = base_lib_BaseUtils::getStr($path_data["mobile_phone"], "string", "");
		$phone_code = base_lib_BaseUtils::getStr($path_data["phone_code"], "string", "");
		$is_phoneCode = base_lib_BaseUtils::getStr($path_data["is_phoneCode"], "string", "");
		//先看是否需要输入验证码
		if ($is_phoneCode) {
			//验证短信验证码
			$service_validate_code = new base_service_hractivity_validationcode();
			$valid = $service_validate_code->getLastValidation($mobile_phone, "id,validation_code,deadline,send_reason");
			if (empty($valid)) {
				echo json_encode(array ("status" => false, 'msg' => "未发送验证码，或验证码已失效，请重新获取验证码"));
				die;
			}
			if (strtotime($valid['deadline']) < time()) {
				echo json_encode(array ("status" => false, 'msg' => "对不起，您的验证码已过期"));
				die;
			}
			if ($valid['validation_code'] != $phone_code) {
				echo json_encode(array ("status" => false, 'msg' => "对不起，您的验证码错误"));
				die;
			}
			$service_validate_code->updateValidStatus($valid["id"], 1); //验证成功
			//对该手机号进行解绑
			$result = $this->_delBindAccountByPhone($mobile_phone);
			if (!$result['status']) {
				echo json_encode(array ("status" => false, 'msg' => "解绑失败,请刷新重试"));
				die;
			}
		}
		else {
			
			$service_related = base_service_hractivity_related::getInstance();
			//判断该账号是否已经被关联
			$related_info = $service_related->getRelatedByAccount($account_id, "person_id,company_id,account_id");
			if (!empty($related_info)) {
				
				echo json_encode(array ("status" => false, 'msg' => "绑定失败，您已经绑定过了"));
				die;
			}
			
			//在没有验证码的时候优先检测手机号和账号是否被绑定
			$re = $this->ChechAccountByMobilePhone($mobile_phone);
			if (!$re['status']) {
				echo json_encode(array ("status" => false, "msg" => $re['msg'], 'data' => $re['data']));
				die;
			}
		}
		//进行手机号绑定
		$related_result = $this->_bindAccount($account_id, $mobile_phone);
		if (!$related_result['status']) {
			echo json_encode(array ("status" => false, "msg" => $related_result['msg']));
			die;
		}
		echo json_encode(array ("status" => true, 'msg' => "绑定成功"));
		die;
	}

    /**
     * 电子合同    tanqiang 2019/12/11 11:54:22
     */
    public function pageEditionContract($inPath){
        $company_service_contractEcontract = new company_service_contractEcontract();
		$service_account       = new base_service_company_account();
        $info = $company_service_contractEcontract->getInfoByCompanyId($this->_userid,'signature_file,sponsor_pdf_file,acceptor_phone,id,business_id,company_id,contract_code,signer,econtract_state,signature_time,sponsor_time,signature_operator');
	    $dirPath = base_lib_Constant::SERVER_TEMP_FULL_FOLDER;
	    $accountids = base_lib_BaseUtils::getProperty($info,'signature_operator');
		$account_list = $service_account->getAccounts($accountids,'account_id,user_id');
		$new_account = array();
		foreach ($account_list as $ke=>$va){
			$new_account[$va['account_id']] = $va['user_id'];
		}
        foreach ($info as $key=>&$val){
            $fileDir = "{$dirPath}SignContractEcontract/{$val['company_id']}/{$val['contract_code']}/";
            $val['fileDir'] = $fileDir;
			$val['user_name'] = $new_account[$val['signature_operator']];
        }
        $this->_aParams['info'] = $info;

        $fields = 'is_main';
        $accountid         = base_lib_BaseUtils::getCookie('accountid');
        $account = $service_account->getAccount($accountid, $fields);
        $this->_aParams['is_main'] = $account['is_main'];
        return $this->render('sysmanage/contractManagement.html', $this->_aParams);
    }

    public function pageSendRdcode($inPath){
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $econtract_id = base_lib_BaseUtils::getStr($path_data['econtract_id'], "int", "");
        $ser_ = new company_service_contractEcontract();
        $re = $ser_->send3Rdcode($econtract_id);
        if ($re['code']==200){
            $json['status'] = 200;

        }else{
            $json['status'] = 0;
        }
	    $json['msg'] = $re['msg'];
        
        echo json_encode($json);
    }

    public function pageSignContract($inPath){
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $econtract_id = base_lib_BaseUtils::getStr($path_data['econtract_id'], "int", "");
        $code = base_lib_BaseUtils::getStr($path_data['code'], "string", "");

        $company_service_contractEcontract = new company_service_contractEcontract();
		$account_id = base_lib_BaseUtils::getCookie('accountid');
        $re = $company_service_contractEcontract->userFileMobileSign($econtract_id, $code,$account_id);
        echo json_encode($re);
    }

    /**
     * 预览电子合同文件  tanqiang 2019/12/12 13:41
     * @param  array $inPath
     * @return
     */
    public function pageViewContractFile($inPath) {
	    $pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
	    $id = base_lib_BaseUtils::getStr($pathdata['id'], 'int', 0);
	    if(!$id) {
		    die('资源文件错误!');
	    }
	
	    $company_service_contractEcontract = new company_service_contractEcontract();
	    $Econtract_data = $company_service_contractEcontract->GetEcontractDataById($id, 'signature_file,sponsor_pdf_file,acceptor_phone,id,business_id,company_id,contract_code,signer,econtract_state,signature_time,sponsor_time');
	
	    if(!$Econtract_data || !$Econtract_data['id']) {
		    die('电子合同不存在!');
	    }
	    if($Econtract_data['econtract_state'] < 2) {
		    die('电子合同不存在!.');
	    }
	    if($Econtract_data['company_id'] != $this->_userid) {
		    die('暂无权限查看该电子合同!');
	    }
	    //电子合同状态 1-未发起(取消后保持当前信息) 2-待签章 3-已签章
	    if($Econtract_data['econtract_state'] == 3) {
		    $file = $Econtract_data['signature_file'];
	    }
	    else {
		    $file = $Econtract_data['sponsor_pdf_file'];
	    }
	
	    $dirPath = base_lib_Constant::SERVER_TEMP_FULL_FOLDER;
	    $fileDir = "{$dirPath}/SignContractEcontract/{$Econtract_data['company_id']}/{$Econtract_data['contract_code']}/";
	    $file = $fileDir . $file;
		    
        // Header content type
        header("Content-type: application/pdf");
        header("Content-Length: " . filesize($file));

        // 将文件发送到浏览器。
        readfile($file);
    }
    /**
     * 下载电子合同文件  tanqiang 2019/12/12 13:41
     * @param  array $inPath
     * @return
     */
    public function pageDownloadContractFile($inPath) {
        $pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
        $file = base_lib_BaseUtils::getStr($pathdata['file'], 'string','');
        $contract_code = base_lib_BaseUtils::getStr($pathdata['contract_code'], 'string','');
        if (!$file) {
            die('资源文件错误!');
        }
        //$filename = pathinfo($file)['basename'];
		$filename = "汇博信息科技服务协议_".$contract_code.'.pdf';
        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        readfile($file);
    }
}

?>
