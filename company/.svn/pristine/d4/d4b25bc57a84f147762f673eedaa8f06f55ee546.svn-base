<?php
/**
 * @ClassName file_name
 * @Desc 
 * @author Administrator
 * @date 2015年8月12日 上午10:31:42
 */
class controller_partjobapply extends components_cbasepage {
	
	public function __construct(){
		parent::__construct(true, "part");
	}

	/**
	 * @Desc 待处理的报名
	 */
	public function pageIndex($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$cur_page  = base_lib_BaseUtils::getStr($path_data['page'], 'int', 1);
		$pagesize  = base_lib_BaseUtils::getStr($path_data['pagesize'], 'int', base_lib_Constant::PAGE_SIZE);
		$status    = base_lib_BaseUtils::getStr($path_data['status'], 'int', '');
 		
		$company_service = new base_service_part_company_partcompany();
		$service_apply = new base_service_part_company_apply();
		$apply_status = new base_service_common_part_applystatus();
 		$fields = 'apply_id,create_time,person_id,job_id,mobile_watch_time,update_time';
		$applylist = $service_apply->getApplysByCompany($this->_userid, $status, $fields, $cur_page, $pagesize);

		$this->_aParams['title'] = '待处理的报名 报名管理_汇博人才网';

		/*====最近10天报名回复率及其统计===*/
		$replay_rate = $company_service->getCompanyPartInfo($this->_userid,'reply_rate')['reply_rate'];
		$this->_aParams['replay_rate'] = ($replay_rate == -1)?'100':number_format(floatval($replay_rate * 100), 2);
		
		$apply_count = $service_apply->getReplyCountByCompany($this->_userid,10);
		$all_apply = 0; $man_count = 0;$auto_count = 0; $notdeal_count = 0;
		foreach ($apply_count as $key => $value) {
			$all_apply += $value['count'];
			if($value['status']>$apply_status->auto_improper){
				$man_count += $value['count'];
			}elseif ($value['status']==$apply_status->auto_improper) {
				$auto_count += $value['count'];
			}else{
				$notdeal_count += $value['count'];
			}
		}

		$this->_aParams['apply_count'] = json_encode($apply_count);
		$this->_aParams['allapplycount'] = number_format($all_apply);
		$this->_aParams['manapplycount'] = number_format($man_count);
		$this->_aParams['autoapplycount'] = number_format($auto_count);
		$this->_aParams['notapplycount'] = number_format($notdeal_count);

		
		if ($applylist->totalSize > $pagesize) {//只有一页的情况下不显示分页
			//分页
			$pager = $this->pageBar($applylist->totalSize, $pagesize, $cur_page, $inPath);
			$this->_aParams['pager'] = $pager;
		}

		$applylist = $applylist->items; 
		$this->_packageData($applylist, true);
		// var_dump($applylist);die;

		//更新企业查看待处理报名列表的时间
		$this->_modViewTime($this->_userid, 'apply');

		// var_dump($applylist);die;
		//处理用户头像，年龄等
		if (!empty($applylist)) {
			$applylist = $this->_formatPersonInfo($applylist);
		}

		$this->_aParams['applylist'] = $applylist;
		return $this->render('part/apply/notdeal_apply.html', $this->_aParams);
	}
	
	/**
	 * @Desc 录用的报名
	 */
	public function pageApplyOffered($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		
		$service_joboffered = new base_service_part_company_joboffered();
		$common_offer       = new base_service_common_part_offer();

		$offerstatus = $common_offer->getAll();
		$status   = base_lib_BaseUtils::getStr($path_data['status'], 'int', '');
		$cur_page = base_lib_BaseUtils::getStr($path_data['page'],'int', 1);
		$pagesize = base_lib_BaseUtils::getStr($path_data['pagesize'], 'int', base_lib_Constant::PAGE_SIZE);
	
		$this->_aParams['status'] = $offerstatus;

		$fields = 'offer_id,job_id,person_id,apply_id,create_time,status,offer_time';
		$offerlist = $service_joboffered->getOffersByCompany($this->_userid, $status, $fields, $cur_page, $pagesize);
		$this->_aParams['title'] = '录用的报名 报名管理_汇博人才网';
		
		if ($offerlist->totalSize > $pagesize) {//只有一页的情况下不显示分页
			//分页
			$pager = $this->pageBar($offerlist->totalSize, $pagesize, $cur_page, $inPath);
			$this->_aParams['pager'] = $pager;
		}
		$offerlist = $offerlist->items;
		
		$this->_packageData($offerlist);
		
		//更新企业查看待处理报名列表的时间
		$this->_modViewTime($this->_userid, 'offer');
		
		//处理用户头像，年龄等
		if (!empty($offerlist)) {
			$offerlist = $this->_formatPersonInfo($offerlist);
		}

		$this->_aParams['offerlist'] = $offerlist;
		return $this->render('part/apply/offer_apply.html', $this->_aParams);
	}

	/**
	 * @Desc 需面试的报名
	 */
	public function pageApplyInvited($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

		$common_invite  = new base_service_common_part_invite();
		$service_invite = new base_service_part_company_invite();

		$cur_page   = base_lib_BaseUtils::getStr($path_data['page'], 'int', 1);
		$pagesize   = base_lib_BaseUtils::getStr($path_data['pagesize'], 'int', base_lib_Constant::PAGE_SIZE);
		$status     = base_lib_BaseUtils::getStr($path_data['status'],'int','');
		$updatetime = base_lib_BaseUtils::getStr($path_data['time'],'string','');

		$fields = 'invite_id,job_id,person_id,create_time,apply_id,re_status,attention_time';
		$invitelist = $service_invite->getInvitesByCompany($this->_userid, $status, $fields, $cur_page, $pagesize);

		$this->_aParams['title'] = '需面试的报名 报名管理_汇博人才网';
		if ($invitelist->totalSize > $pagesize) {//只有一页的情况下不显示分页
			//分页
			$pager = $this->pageBar($invitelist->totalSize, $pagesize, $cur_page, $inPath);
			$this->_aParams['pager'] = $pager;
		}

		$invitelist = $invitelist->items;

		$this->_packageData($invitelist);

		//更新企业查看待处理报名列表的时间
		$this->_modViewTime($this->_userid, 'invite');

		//处理用户头像，年龄等
		if (!empty($invitelist)) {
			$invitelist = $this->_formatPersonInfo($invitelist);
		}
		$this->_aParams['invitelist'] = $invitelist;
		return $this->render('part/apply/invite_apply.html', $this->_aParams);
	}
	
	/**
	 * @Desc 不合适的报名
	 */
	public function pageApplyRefused($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$cur_page = base_lib_BaseUtils::getStr($path_data['page'], 'int', 1);
		$pagesize = base_lib_BaseUtils::getStr($path_data['pagesize'], 'int', base_lib_Constant::PAGE_SIZE);

		$service_jobapplystatus = new base_service_common_part_applystatus();
		$service_apply          = new base_service_part_company_apply();

		$this->_aParams['auto_improper'] = $service_jobapplystatus->auto_improper;
		
		$status = $service_jobapplystatus->refused;
 		$fields = 'apply_id,create_time,person_id,job_id,status,refuse_time';
		$applylist = $service_apply->getApplysByCompany($this->_userid, $status, $fields, $cur_page, $pagesize);
		$this->_aParams['title'] = '不合适的报名 报名管理_汇博人才网';

		if ($applylist->totalSize > $pagesize) {//只有一页的情况下不显示分页
			//分页
			$pager = $this->pageBar($applylist->totalSize, $pagesize, $cur_page, $inPath);
			$this->_aParams['pager'] = $pager;
		}

		$applylist = $applylist->items;
		$this->_packageData($applylist, true);
		//处理用户头像，年龄等
		if (!empty($applylist)) {
			$applylist = $this->_formatPersonInfo($applylist);
		}

		$this->_aParams['applylist'] = $applylist;
		return $this->render('part/apply/refused_apply.html', $this->_aParams);
	}

	/**
	 * @Desc 发送通知入口
	 */
	public function pageSendOffer($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$apply_id  = base_lib_BaseUtils::getStr($path_data['applyid'],'int',0);

		$service_apply   = new base_service_part_company_apply();
		$service_partjob = new base_service_part_company_job();
		$service_area    = new base_service_common_area();
		$service_part_job_partjobaddress = new base_service_part_job_partjobaddress();
		$apply = $service_apply->getApplyNotDeal($this->_userid,$apply_id,'apply_id,company_id,job_id,person_id,status,address_id');
		
		if (empty($apply)) {
			echo '<div style="height:450px"><p style="text-align:center;line-height:450px">您操作的记录不存在</p></div>';
			return;
		}

		$jobinfo = $service_partjob->getJob($apply['job_id'], 'station,need_invite,address,link_tel,area_id,is_need_workaddress', false);
		if (!empty($jobinfo)) {
			if(empty($apply['address_id'])){
				$apply['address']     = $service_area->getAreaName($jobinfo['area_id']).$jobinfo['address'];
			}else{
				$address = $service_part_job_partjobaddress->getAddressById($apply['address_id'],"address_id,address_info");
				$apply['address']     = $address['address_info'];
			}

			$apply['need_invite'] = $jobinfo['need_invite'];
			$apply['link_tel']    = $jobinfo['link_tel'];
			$apply['station']     = $jobinfo['station'];
			$apply['is_need_workaddress']     = $jobinfo['is_need_workaddress'];
		}

		$this->_aParams['apply'] = $apply;
		return $this->render('part/apply/sendoffer.html', $this->_aParams);
	}

	/**
	 * 查看联系方式
	 * @param  array $inPath url参数集
	 * @return boolean       是否成功
	 */
	public function pageMobileWatched($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$apply_id  = base_lib_BaseUtils::getStr($path_data['apply_id'], 'int', 0);
		if (empty($apply_id))
			return true;

		$apply_service = new base_service_part_company_apply();
		$apply_service->mobileWatched($apply_id, $this->_userid);
			
		return true;
	}

	/**
	 * @Desc 发送通知（面试通知或录用通知）
	 */
	public function pageSendOfferDo($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$service_apply = new base_service_part_company_apply();
		$service_jobapplystatus = new base_service_common_part_applystatus();
		$apply_id = base_lib_BaseUtils::getStr($path_data['applyid'],'int',0);
		$apply = $service_apply->getApplyNotDeal($this->_userid,$apply_id,'apply_id,company_id,job_id,person_id');
		$notice_type = base_lib_BaseUtils::getStr($path_data['txtType'],'string','');
		if(empty($apply) || empty($notice_type) || !in_array($notice_type,array('offer','invite'))){
			echo json_encode(array('error'=>'通知发送失败'));return;
		}
		$validator = new base_lib_Validator();
		//联系人
		$notice['link_man'] = $validator->getStr($path_data['linkman'],2,10,'联系人输入2-10字',true);

		$link_tel = trim($path_data['linkway']);
		if(base_lib_BaseUtils::nullOrEmpty($link_tel) || !(preg_match('/^[1]\d{10}$/',$link_tel) || preg_match('/^(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/',$link_tel))){
			array_push($validator->err,'联系电话格式不正确');
			$validator->has_err = true;
		}
		$notice['link_tel'] = $link_tel;
		//其他描述
		$notice['description'] = $validator->getStr($path_data['description'],0,100,'描述最多可以输入100字',true);
		//详细时间
		$date = $validator->getNotNull($path_data['date'],'请选择报到日期');
		$hours = $validator->getNotNull($path_data['timeDelta'],'请选择报到具体时间');
		$time_detail = $date.' '.$hours;
		if($notice_type=='offer'){
			$notice['offer_time'] = $time_detail;
			$notice['offer_address'] = $validator->getStr($path_data['txtAddress'],3,20,'报到地点输入3-20字');
		}else{
			$notice['attention_time'] = $time_detail;
			$notice['attention_address'] = $validator->getStr($path_data['txtAddress'],3,50,'面试地点请输入3-50字');
		}
		
		if($validator->has_err){
			echo header("Content-type:text/plain;charset=utf-8");
			echo $validator->toJsonWithHtml();
			return;
		}
		$notice['apply_id'] = $apply_id;
		$notice['company_id'] = $this->_userid;
		$notice['job_id'] = $apply['job_id'];
		$notice['person_id'] = $apply['person_id'];
		if($notice_type=='offer'){
			$service_offer = new base_service_part_company_joboffered();
			$result = $service_offer->addOffer($notice);
		}else{
			$service_invite = new base_service_part_company_invite();
			$result = $service_invite->addInvite($notice);
		}
		if($result){
			//更新申请状态
			$service_apply->modApply($apply_id, array('status'=>$service_jobapplystatus->apply_done));
			//标记有新增录用和面试通知
			$person_app_service = new base_service_part_person_app();
			$person_app_service->setnew($apply['person_id'], $type = 2);
			//向求职者发送短信
			//$this->_sendMsg($apply['company_id'],$apply['person_id'],$notice_type);

            //向求职者发送微信通知
            $service_job = new base_service_part_company_job();
            $job_info = $service_job->getJob($apply['job_id'],'job_id,station');
            $jianzhi_weixin_service = new base_service_part_weixin();
            $user_info = $jianzhi_weixin_service->getWeixinInfoBypersonId($apply['person_id'],'p','id,open_id');
            if(!empty($user_info)){
                $openweixinservice = new SJianzhiWeixin();
                 if($notice_type == 'offer'){
                     $msg = "你的报名[{$job_info['station']}]已被录用,报道时间：{$time_detail}，地点：{$notice['offer_address']} ，联系：{$notice['link_man']}-{$link_tel }，请及时与企业联系。";
                 }else{
                    $msg = "[{$this->_username}]邀请您投递的[{$job_info['station']}]进行面试，时间：{$time_detail} 地点：{$notice['offer_address']} 联_系：{$notice['link_man']}-{$link_tel }";
                }
                $openweixinservice->SendMsg($user_info['open_id'], $msg);
            }
			echo header("Content-type:text/json;charset=utf-8");
			echo json_encode(array('success'=>'通知发送成功','inviteid'=>$result));return;
		}else{
			echo header("Content-type:text/json;charset=utf-8");
			echo json_encode(array('error'=>'通知发送失败','res'=>$result,'job_id'=>$apply['job_id'],'applyid'=>$apply_id));return;
		}			
	}
	
	/**
	 * @Desc 设置报名不合适
	 */
	public function pageRefuseApply($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$apply_id = base_lib_BaseUtils::getStr($path_data['applyid'],'int',0);

		$service_apply = new base_service_part_company_apply();
		$result = $service_apply->refuseApply($this->_userid, $apply_id);

		if ($result) {
			echo header("Content-type:text/json;charset=utf-8");
			echo json_encode(array('success'=>'设置不合适成功', 'status'=>1));
			return;
		} else {
			echo header("Content-type:text/json;charset=utf-8");
			echo json_encode(array('error'=>'设置不合适失败', 'status'=>0));
			return;
		}
	}
	
	/**
	 * @Desc 设置面试结果入口
	 */
	public function pageModInvite($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$invite_id = base_lib_BaseUtils::getStr($path_data['inviteid'], 'int', 0);

		$common_invite  = new base_service_common_part_invite();
		$service_invite = new base_service_part_company_invite();

		$invite = $service_invite->getInviteByCompany($this->_userid, $invite_id, 'invite_id,company_id,job_id,person_id,link_tel,link_man,attention_time,attention_address,description,re_status');
		if (empty($invite)) {
			echo json_encode(array('error'=>'该记录不存在或已删除'));
			return;
		}

		//邀请状态
		$invite_types = $common_invite->getAll();
		$invite['invite_types'] = $invite_types;
		
		//职位
		$service_job = new base_service_part_company_job();
		$station = $service_job->getJob($invite['job_id'], 'station');
		$invite['station'] = $station['station'];
		
		//求职者
		$service_person = new base_service_person_person();
		$username = $service_person->getPerson($invite['person_id'], 'user_name');
		$invite['user_name'] = $username['user_name'];
		$this->_aParams['invite'] = $invite;

		return $this->render('part/apply/setinvite.html', $this->_aParams);
	}
	
	/**
	 * @Desc 设置面试结果
	 */
	public function pageModInviteDo($inPath){			
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$common_invite = new base_service_common_part_invite();
		$service_invite = new base_service_part_company_invite();
		$common_offer = new base_service_common_part_offer();
		$invite_id = base_lib_BaseUtils::getStr($path_data['inviteid'],'int',0);
		$invite = $service_invite->getInviteByCompany($this->_userid,$invite_id,'invite_id,company_id,job_id,apply_id,person_id');
		$invite_types = $common_invite->getAll();
		if(empty($invite)){
			echo json_encode(array('error'=>'设置面试结果失败'));return;
		}
		$validator = new base_lib_Validator();
		//面试结果
		$notice['status'] = $validator->getEnum($path_data['resultType'],$invite_types,'请设置面试结果');
		if($notice['status']=='1'){
			$notice['company_id'] = $invite['company_id'];
			$notice['person_id'] = $invite['person_id'];
			$notice['job_id'] = $invite['job_id'];
			$notice['apply_id'] = $invite['apply_id'];
			//联系人
			$notice['link_man'] = $validator->getStr($path_data['linkman'],2,10,'联系人输入2-10字',true);
			//联系电话
			$link_tel = trim($path_data['linkway']);
			if(base_lib_BaseUtils::nullOrEmpty($link_tel) || !(preg_match('/^[1]\d{10}$/',$link_tel) || preg_match('/^(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/',$link_tel))){
				array_push($validator->err,'联系电话格式不正确');
				$validator->has_err = true;
			}
			$notice['link_tel'] = $link_tel;
			//其他描述
			$notice['description'] = $validator->getStr($path_data['description'],0,100,'描述最多可以输入100字',true);
			//报到时间
			$date = $validator->getNotNull($path_data['date'],'请选择报到日期');
			$hours = $validator->getNotNull($path_data['timeDelta'],'请选择报到具体时间');
			$notice['offer_time'] = $date.' '.$hours;
			//报到地址
			$notice['offer_address'] = $validator->getStr($path_data['txtAddress'],3,20,'报到地点输入3-20字');
		}	
		if($validator->has_err){
			echo header("Content-type:text/plain;charset=utf-8");
			echo $validator->toJsonWithHtml();
			return;
		}
		if($notice['status']=='1'){
			//录用通知状态
			$notice['status'] = $common_offer->not_deal;
			//发送录用通知
			$service_offer = new base_service_part_company_joboffered();
			$result = $service_offer->addOffer($notice);
			if($result){
				//设置面试结果
				$modinvite['status'] = $common_invite->agree;
				$result = $service_invite->modInvite($invite_id,$modinvite);
			}
		}else{
			//设置面试结果
			$modinvite['status'] = $common_invite->refuse;
			$result = $service_invite->modInvite($invite_id,$modinvite);				
			if($result){
				//设置报名不合适
				$service_apply = new base_service_part_company_apply();
				$service_apply->refuseApply($invite['company_id'],$invite['apply_id']);
			}
		}
		if($result){
			echo json_encode(array('success'=>'设置面试结果成功'));return;
		}else{
			echo json_encode(array('error'=>'设置面试结果失败'));return;
		}
	}
	
	/**
	 * @Desc 删除不合适报名
	 */
	public function pageDelRefusedApply($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$service_apply = new base_service_part_company_apply();
		$apply_id = base_lib_BaseUtils::getStr($path_data['applyid'],'int',0);
		$result = $service_apply->delRefusedApply($this->_userid,$apply_id);
		if($result){
			echo json_encode(array('success'=>'删除不合适的报名成功'));return;
		}else{
			echo json_encode(array('error'=>'删除不合适的报名失败'));return;
		}
	}
	
	/**
	 * @Desc 处理用户头像等信息
	 */
	private function _formatPersonInfo($persons){
		$service_sex = new base_service_common_sex();
		$sexcode = $service_sex->getSex();
		$defaultPhoto = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . "/img/c/new_resume/headportrait.png";
		foreach ((array)$persons as $k => $person){
			if ($person['photo_open'] != '0') {
				if (!base_lib_BaseUtils::nullOrEmpty($person['photo'])) {
					$avatar = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . (!empty($person['big_photo'])?$person['big_photo']:$person['photo']);
					$persons[$k]['photo'] = "<img width='140' height='140' src='{$avatar}' />";
				} else {
					$persons[$k]['photo'] = "<img width='140' height='140' src='{$defaultPhoto}' />";
				}
			} else {
				$persons[$k]['photo'] = "<img width='140' height='140' src='{$defaultPhoto}' />";
			}
			$persons[$k]['sex'] = $sexcode[$person['sex']];
			if ($person['birthday2'] != '') {
				$persons[$k]['age'] = base_lib_TimeUtil::ceil_diff_year($person['birthday2']) . '岁';
			}
			if ($person['stature']) {
				$persons[$k]['stature'] = $person['stature'].'cm';
			}
		}
		return $persons;
	}
	
	/**
	 * @Desc  发送面试邀请或录用通知后向求职者发送短信
	 * @param array $apply 投递的信息
	 * @return boolean
	 */
	private function _sendMsg($company_id,$person_id,$type,$mobile=null){
		// if(empty($company_id) || empty($person_id))
		if(empty($company_id))
			return false;
		$service_company = new base_service_company_company();
		$service_person = new base_service_person_person();

		$company_name = $service_company->getCompany($company_id,true,'company_shortname')['company_shortname'];
		if(empty($mobile)) {
			$mobile = $service_person->getPerson($person_id,'mobile_phone',true)['mobile_phone'];			
		}
		$type = ($type == 'offer')?'录用通知':'面试邀请';
		$short_url = $this->makeShortLink("//app2.huibo.com/index/download");
		$content = "{$company_name}给你发送了兼职{$type}，请打开APP或关注汇博人才网微信公众号查看详情。如需下载APP请点击：{$short_url}。";
		return base_lib_SMS::send($mobile, $content,1);
	}


	public function pageSendMsg($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$mobile = base_lib_BaseUtils::getStr($path_data['mobile'],'string',null);
		$company_id = base_lib_BaseUtils::getStr($path_data['company_id'],'int',null);
		// $person_id = base_lib_BaseUtils::getStr($path_data['person_id'],'int',null);
		header("Content-type: text/html; charset=utf-8");
		echo 'mobile:'.$mobile;
		echo '<br/>company_id:'.$company_id;
		echo '<br/>person_id:'.$person_id;
		if(!empty($mobile) && !empty($company_id)){
			$res = $this->_sendMsg($company_id,null,'offer',$mobile);
			echo '<br/>结果：';
			var_dump($res); 
		}
	}


    /**
     * 生成短信短地址
     * Enter description here ...
     * @param $invite_id
     * @param $sms_content
     */
    private function makeShortLink($url) {
        $xml = SXML::load('../config/company/company.xml');
        if (!is_null($xml)) {
            $shorturl['create_time'] = date('Y-m-d H:i:s', time());
            $shorturl['type'] = 1;//
            $key = substr(md5($xml->ShortUrlKey),0,(int)($xml->ShortUrlKeySubStrLen));
            $shorturl['url'] = $url.'?key='.$key;
 			$shorturl_service = new base_service_common_shorturl();
			$url_key = $shorturl_service->shorturlAdd($shorturl);
            return $xml->ShortUrlDomain.$url_key;
        }
        return $url;
    }

	/**
	 * @Desc 更新企业查看各种报名的时间
	 * @param int $company_id 企业id
	 * @param enum $apply_type 报名类型('apply','invite','offer')
	 */
	private function _modViewTime($company_id,$apply_type){
		if(empty($company_id) || empty($apply_type))
			return false;
		$service_partcompany = new base_service_part_company_partcompany();
		$thisdate = date('Y-m-d H:i:s');
		$condition['company_id'] = $company_id;
		$update = array();
		switch ($apply_type){
			case 'apply' : 
					$update['see_apply_time'] = $thisdate;break;
			case 'invite' :
					$update['see_invite_time'] = $thisdate;break;
			case 'offer' :
					$update['see_offer_time'] = $thisdate;break;
		}
		return $service_partcompany->update($condition,$update);
	}

	/**
	 * @Desc 获取个人，职位等附加信息
	 * @param int $apply_list 兼职报名列表
	 * @param bool $special 待处理的报名时间有特殊处理
	 * @param enum $apply_list 附加个人和职位等信息的报名列表
	 */
	private function _packageData(&$apply_list, $special=false) {
		if (base_lib_BaseUtils::nullOrEmpty($apply_list))
			return $apply_list;

		$person_ids = base_lib_BaseUtils::getProperty($apply_list, 'person_id');
		$job_ids    = base_lib_BaseUtils::getProperty($apply_list, 'job_id');
		
		$service_area       = new base_service_common_area();
		$service_person     = new base_service_person_person();
		$service_partjob    = new base_service_part_company_job();
		$service_partresume = new base_service_part_person_resume();

		$persons = $service_person->GetPersonListByIDs(implode(',',array_unique($person_ids)), 'person_id,user_name,sex,photo_open,has_big_photo,photo,big_photo,birthday2,cur_area_id,address,stature,mobile_phone')->items;
		$persons = base_lib_BaseUtils::array_key_assoc($persons, 'person_id');

		$jobs = $service_partjob->getJobs(implode(',',array_unique($job_ids)), 'job_id,station,need_invite');
		$jobs = base_lib_BaseUtils::array_key_assoc($jobs, 'job_id');

		$resumes = $service_partresume->getResumeByPersonIds($person_ids, 'person_id,resume_id', true)->items;
		$resumes = base_lib_BaseUtils::array_key_assoc($resumes, 'person_id');

		//获取职位链接
		if (!empty($jobs)) {
			foreach ($jobs as $k => $job) {
				$jobs[$k]['joblink'] = base_lib_Rewrite::partjob($job['job_id']);
			}
		}
        $can_see_mobile = $this->canDo("see_part_resume_mobile");
		//个人现居住地址
		if (!empty($persons)) {
			foreach($persons as $k => $person){
				$area = $service_area->getTopAreaByAreaID($person['cur_area_id']);
				$persons[$k]['address'] = implode('', base_lib_BaseUtils::getProperty(array_reverse($area), 'area_name'));
                if(!$can_see_mobile){
                    $persons[$k]['mobile_phone'] = preg_replace('#(\d{3})[a-z0-9-]{2,10}(\d{3})#', '${1}****${2}', $person['mobile_phone']);;
                }
			}
		}
		foreach($apply_list as $k => $apply) {
			//报名时间
			$apply_list[$k]['refuse_time'] = empty($apply['refuse_time']) ? "" : date("Y-m-d", strtotime($apply['refuse_time']));
			$apply_list[$k]['create_time'] = base_lib_TimeUtil::to_friend_time($apply['create_time']);
			$apply_list[$k] = empty($persons[$apply['person_id']]) ? $apply_list[$k] : array_merge($apply_list[$k], $persons[$apply['person_id']]);
			$apply_list[$k] = empty($jobs[$apply['job_id']]) ? $apply_list[$k] : array_merge($apply_list[$k], $jobs[$apply['job_id']]);
			$apply_list[$k] = empty($resumes[$apply['person_id']]) ? $apply_list[$k] : array_merge($apply_list[$k], $resumes[$apply['person_id']]);
			
		}
	}
}
?>