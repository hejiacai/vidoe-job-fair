<?php

/**
 * 线上视频双选会
 * Class: shuangxuannet.page.php
 * User: hujian  2019/9/25 16:30:22
 */
class controller_shuangxuannet extends components_cbasepage {
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}
    #region
	/**
	 * 提交资料
	 * User:hujian 2019/9/25 16:22:10
	 */
	public function pageAddCompanyNet($inPath)
	{
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $sid = base_lib_BaseUtils::getStr($pathdata['sid'], "string", "");
		$service_company = new base_service_company_company();
		$company = $service_company->getCompany($this->_userid, 1, 'company_id,company_flag,'
			. 'company_name,property_id,size_id,area_id,calling_ids,address,postcode,is_audit,'
			. 'homepage,info,hr_manager,hr_manager_sex,hr_tel,show_email,linkman,linkman_sex,'
			. 'open_linkman,linkman_station,link_tel,open_tel,fax,open_fax,link_mobile,'
			. 'open_mobile,company_logo_path,company_video_path,company_video_name,email,'
			. 'company_bright_spot,company_shortname,company_reward_ids,company_other_reward');

		$this->_aParams['base_company_shortname'] = $company['company_shortname'];
		$service_shortname_audit = new base_service_company_shortnameaudit();
		$shortname_result = $service_shortname_audit->getLastedAudit($this->_userid);
		$this->_aParams['need_audit_shortname'] = !empty($shortname_result);
		if($this->_aParams['need_audit_shortname'] && !empty($shortname_result['company_shortname']))
			$company['company_shortname'] = $shortname_result['company_shortname'];

		$this->_aParams['company'] = $company;

		$xml = SXML::load('../config/company/company.xml');
		if (!is_null($xml)) {
			$VirtualName = $xml->VirtualName;//虚拟目录名 CompanyImages
			$LogoFolder  = $xml->LogoFolder;// <!--logo文件夹名-->
			$logoTempfolder = $xml->LogoTempFolder;
		}

		$this->_aParams['logo'] = empty($company['company_logo_path'])
			? base_lib_Constant::STYLE_URL . "/img/job/newjob/newJob_57.png"
			: base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $VirtualName . '/' . $LogoFolder . '/' . $company['company_logo_path'];

		$this->_aParams['logo_virt_path'] =  base_lib_Constant::UPLOAD_FILE_URL . '/' . $VirtualName . '/' . $logoTempfolder . '/';

		//获取企业固定电话信息
		$this->_aParams['phone_infor'] = $this->get_telephone_infor($company["link_tel"]);

		//获得企业行业数据
		$calling_service = new base_service_common_calling();
		$calling_arr = array_slice(explode(",", $company['calling_ids']), 0, 2);

		$this->_aParams['calling_arr'] = $calling_arr;

		//获得主行业名称
		if (!empty($calling_arr[0]))
			$calling_names[0] = $calling_service->getCallingName($calling_arr[0]);

		//获得次行业名称
		if (!empty($calling_arr[1]))
			$calling_names[1] = $calling_service->getCallingName($calling_arr[1]);

		$this->_aParams['calling_names'] = $calling_names;

		// 获取信息完善度
		$companyInfoPercent = 0;
		$no_complete = $service_company->getComPercentAndNoComplete($this->_userid, false, $companyInfoPercent);
		$this->_aParams['precent']     = $companyInfoPercent . '%';
		$this->_aParams['no_complete'] = $no_complete;

		// 获取公司性质码表信息
		$properties_service = new base_service_common_comproperty();
		$this->_aParams["properties"]       = $properties_service->getAll();
		$this->_aParams["company_property"] = $properties_service->getComproperty($company["property_id"]);

		// 获取公司规模码表信息
		$service_comsize =  new base_service_common_comsize();
		$this->_aParams['comsizes']     = $service_comsize->getAll();
		$this->_aParams["company_size"] = $service_comsize->getComsize($company["size_id"]);

		// 百度地图
		$service_baidumap = new base_service_company_companycooperaterbaidumap();
		$map = $service_baidumap->getCompanyCooperateBaiduMapByCompanyID($this->_userid);
		$this->_aParams['map'] = $map;

		$this->_aParams['title'] = '报名资料完善';
		$this->_aParams['upload_cookie_userid']   = $this->_userid;
		$this->_aParams['upload_cookie_nickname'] = $this->_username;
		$this->_aParams['upload_cookie_usertype'] = $this->_usertype;
		$this->_aParams['upload_cookie_userkey']  = base_lib_BaseUtils::getCookie('userkey');
		$this->_aParams['upload_cookie_tick']     = base_lib_BaseUtils::getCookie('tick');

		$service_resource = base_service_company_resources_resources::getInstance($this->_userid);
		list($audit_status,$audit_param) = $service_resource->getCompanyAuditStatus();
		$this->_aParams["audit_status"] = $audit_status;
        //获取场次
        $base_serv_shuangxuannet=new base_service_schoolnet_shuangxuannet();
        $shuangxuannet=$base_serv_shuangxuannet->getShuangXuanNetApplyListForCompany()->items;
        $this->_aParams["shuangxuannet"] = $shuangxuannet;
        $this->_aParams["sid"] = $sid;
        //获取企业的在招职位
        $job = new base_service_company_job_job();
        $job_status = new base_service_common_jobstatus();
        $job_list = $job->getJobList($this->_userid, "", $job_status->pub, 'job_id,job_flag,station,issue_time,check_state,end_time,status,order_no,urgent_end_time,
        auto_filter,
        re_apply_type,company_id,account_id,map_x,map_y,agency_state,refresh_time', 0, 0, null, null);
        $this->_aParams["job_list"] = $job_list;
		return $this->render('schoolnet/submitcompanyinfo.html', $this->_aParams);
	}
	/**
	 * 统计资料保存
	 * User:hujian 2019/9/25 16:23:21
	 */
	public function pageAddCompanyNetDo($inPath)
	{
        if(!$this->canDo("edit_company_info")){
            echo json_encode(array('error'=>"无权限访问，没有开通相应权限"));
            return;
        }
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $base_infor = $this->get_company_base_infor_from_post($pathdata);

        $result     = $this->validate_company_base_info($base_infor);
        if ($result->has_err) {
            echo $result->toJsonWithHtml();
            return;
        }
        $validator = new base_lib_Validator();
        $address = $validator->getNotNull($pathdata['txtAddress'], '公司地址不能为空');
        $area_id = $validator->getNotNull($pathdata['hidArea'], '公司地区不能为空');
        $link_man=$validator->getNotNull($pathdata["link_man"], "请输入参会联系人");
        $link_man=$validator->getStr($link_man, 1, 10, "参会联系人不能超过10字");
        if ($validator->has_err) {
            echo header("Content-type:text/plain;charset=utf-8");
            echo $validator->toJsonWithHtml();
            return;
        }
        $link_mobile = base_lib_BaseUtils::getStr($pathdata['link_mobile']);
        if(!$this->matchPhoneNo($link_mobile)){
            echo json_encode(array('error'=>"请输入正确的联系电话"));
            return;
        }
        //选择的职位
        $job_ids = base_lib_BaseUtils::getStr($pathdata['job_ids']);
        if(!$job_ids){
            echo json_encode(array('error'=>"请选择要参会的职位"));
            return;
        }
        $job_ids=explode(',',$job_ids);
        $sid = base_lib_BaseUtils::getStr($pathdata['sid']);
        if(!$sid){
            echo json_encode(array('error'=>"请选择要参加的场次"));
            return;
        }
        $base_serv_companynet=new base_service_schoolnet_shuangxuancompanynet();

        //保存单位基本信息
        $result=$base_serv_companynet->save_company_base_info($this->_userid,$base_infor);
        if($result['code']==ERROR){
            echo json_encode(array('error'=> $result['msg']));
            return;
        }
        //保存logo
        $del_logo = base_lib_BaseUtils::getStr($pathdata['del_logo']);
        if ($del_logo != $base_infor['logo']) {
            $logo_name = array();
            // 读取配置xml文件
            $xml = SXML::load('../config/company/company.xml');
            if (!is_null($xml)) {
                $logo_folder        = $xml->LogoFolder;
                $logo_temp_folder   = $xml->LogoTempFolder;
                $company_image_path = $xml->CompanyImagePath;
                $virtualName        = $xml->VirtualName;
            }
            array_push($logo_name, $base_infor['logo']);

            //将LOGO移动到正式目录中
            $postvar['newfile']      = "{$virtualName}/{$logo_folder}";
            $postvar['oldfile']      = "{$virtualName}/{$logo_temp_folder}";
            $postvar['names']        = $logo_name;
            $postvar['thumbSuffix']  = '';
            $postvar['authenticate'] = "logo";
            $result_move = base_lib_Uploadfilesv::moveFile($postvar);

            if (!$result_move) {
                echo json_encode(array('error'=>"修改logo图片失败"));
                return;
            }

            $service_company = base_service_company_company::getInstances();
            $result = $service_company->delLogo($del_logo);

            $service_logoaudit = new base_service_company_logoaudit();
            $service_logoaudit->addCompanyLogoAudit($this->_userid, 0);//将logo 设置为未审核
        }
        //保存地址
        $service_company = new base_service_company_company();
        $result = $service_company->saveCompanyAddress($this->_userid, $area_id, $address);
        if ($result === false) {
            echo json_encode(array('error'=>'保存公司地址失败'));
            return;
        }
        $map['company_id'] = $this->_userid;
        $map['map_x']      = base_lib_BaseUtils::getStr($pathdata['hidMapX'], 'float', '');
        $map['map_y']      = base_lib_BaseUtils::getStr($pathdata['hidMapY'], 'float', '');
        $map['map_zoom']   = base_lib_BaseUtils::getStr($pathdata["hidMapZoom"],'int','');

        if ($map['map_x'] != '' && $map['map_y'] != '' && $map['map_zoom'] != '') {
            $service_baidumap = new base_service_company_companycooperaterbaidumap();
            $result  = $service_baidumap->saveBaiduMap($map);
            if ($result === false) {
                echo json_encode(array('error'=>'保存公司地图标注失败'));
                return ;
            }
        }
        //保存之后检测单位是否满足
        $to_url=base_lib_Constant::COMPANY_URL."/licencevalidate";
        $to_url="<a target='_blank' href='{$to_url}'><u>(去验证)</u></a>";
        $result=$base_serv_companynet->checkCompanyInfo($this->_userid,$to_url);
        if($result['code']==ERROR){
            echo json_encode(array('error'=>$result['msg']));
            return ;
        }
        //添加职位信息
        $aRs=$base_serv_companynet->addCompanyNet($this->_userid, $sid,$job_ids,$link_man,$link_mobile,2,0);
        if($aRs['code'] == ERROR){
            echo json_encode(array('error'=>$aRs['msg']));
            return ;
        }
        echo json_encode(array('success'=>"报名成功"));
        return ;
	}
    /**
     * 电话
     * User:hujian 2019/9/25 16:36:07
     */
    private function get_telephone_infor($telephone){
        $telephone_infor = array('zone'=>'','phone'=>'','ext'=>'');
        preg_match("/^([0-9]+)-([0-9]+)(\(([0-9]+)\))?$/", $telephone, $regs);
        if(count($regs)<=0){
            $telephone_infor["phone"] = $telephone;
            return $telephone_infor;
        }
        if($regs[1]!="")
            $telephone_infor["zone"] 	= $regs[1];
        if($regs[2]!="")
            $telephone_infor["phone"]	= $regs[2];
        if($regs[4]!=""){
            $telephone_infor["ext"]		= $regs[4];
        }
        return $telephone_infor;
    }

    /**
     * 获取提交数据
     * User:hujian 2019/9/26 10:33:28
     */
    private function get_company_base_infor_from_post($post) {
        $base_infor = [];

        $base_infor["property_id"]          = $this->get_post_value($post["company_property"]);
        $base_infor["size_id"]              = $this->get_post_value($post["company_size"]);
        $base_infor["info"]                 = urldecode(urldecode($this->get_post_value($post["company_info"])));
        $base_infor["logo"]                 = $this->get_post_value($post["logo"]);
        $base_infor["hidCompanyShortName"]  = $this->get_post_value($post["hidCompanyShortName"]);
        $base_infor["hidRightSpot"]         = $this->get_post_value($post["hidRightSpot"]);

        /*$base_infor["linkman"]              = urldecode(urldecode($this->get_post_value($post["linkman"])));
        $base_infor["link_mobile"]          = $this->get_post_value($post["link_mobile"]);*/
        return $base_infor;
    }

    /**
     * 获取提交数据
     * User:hujian 2019/9/26 10:34:24
     */
    private function get_post_value($value) {
        return isset($value) ? base_lib_BaseUtils::getStr($value) : "";
    }

    /**
     * 数据验证
     * User:hujian 2019/9/26 10:40:17
     */
    private function validate_company_base_info($base_infor){
        $validator = new base_lib_Validator();

        $properties_service = new base_service_common_comproperty();
        $service_resource = base_service_company_resources_resources::getInstance($this->_userid);
        list($audit_status,$audit_param) = $service_resource->getCompanyAuditStatus();

        $properties = $properties_service->getAll();

        if (!isset($properties[$base_infor["property_id"]])) {
            $validator->addErr("请选择正确的公司性质");
        }

        $size_service = new base_service_common_comsize();
        $sizes = $size_service->getAll();
        if (!isset($sizes[$base_infor["size_id"]])) {
            $validator->addErr("请选择正确的公司规模");
        }

//		$validator->getNotNull($base_infor["main_calling"], "请选择公司所属行业");
        $validator->getNotNull($base_infor["info"], "请输入公司简介");
        $validator->getStr($base_infor["info"], 2, 4000, "公司简介请输入2-4000个字");
        $validator->getStr($base_infor["hidCompanyShortName"], 1, 15, "公司简称请输入1-15个字");
        $validator->getStr($base_infor["hidRightSpot"], 5, 50, "主营业务及行业地位请输入5-50个字");
        return $validator;
    }
    /**
     * 验证座机号和手机号
     * User:hujian 2019/9/20 13:49:38
     */
    public function matchPhoneNo($tel)
    {
        //验证手机号
        $isMob="/^1[345789]{1}\d{9}$/";
       //验证座机
        $isTel = '/^(0[0-9]{2,3}-)?([2-9][0-9]{6,7})+(-[0-9]{1,4})?$/';
        if(!preg_match($isMob,$tel) && !preg_match($isTel,$tel)) {
            return false;
        }
        return true;
    }
    #endregion
    /**
     *活动列表
     */
    public function pageIndex($inPath)
    {
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $page = base_lib_BaseUtils::getStr($params['page'], 'int', 1);



        //跳转独立网络招聘会
        $url = "/netfairlist";
        $ser_ = new base_service_netfair_company();

        $iUserId = isset($_POST['upload_cookie_userid']) ? $_POST['upload_cookie_userid'] : base_lib_BaseUtils::getCookie('userid');
        $sUserName = isset($_POST['upload_cookie_nickname']) ? $_POST['upload_cookie_nickname'] : base_lib_BaseUtils::getCookie('nickname');
        $sUserType = isset($_POST['upload_cookie_usertype']) ? $_POST['upload_cookie_usertype'] : base_lib_BaseUtils::getCookie('usertype');
        $sKey = isset($_POST['upload_cookie_userkey']) ? $_POST['upload_cookie_userkey'] : base_lib_BaseUtils::getCookie('userkey');
        $sTick = isset($_POST['upload_cookie_tick']) ? $_POST['upload_cookie_tick'] : base_lib_BaseUtils::getCookie('tick');
        $sAccountid = isset($_POST['upload_cookie_accountid']) ? $_POST['upload_cookie_accountid'] : base_lib_BaseUtils::getCookie('accountid');

        $re        = $ser_->GetNetfairDomainCookie($iUserId, $sUserName, 'c', 1, $url);

        $re = $re ? $re : "/company";
        $this->redirect($re);

        die;





        //$page_size = base_lib_Constant::PAGE_SIZE;
        $page_size = 200;
        $service_shuangxuannet = new base_service_schoolnet_shuangxuannet();
        //$params['is_effect'] = 1;
        $params['state'] = 1;
        $company_id = $this->_userid;
        $params['order_by'] = 'order by start_time desc';
        $activity_fields = 'id,title,sponsor,start_time,end_time,logo,is_effect,state,superiority_info';
        $list = $service_shuangxuannet->getVideoList($params, $page, $page_size, $activity_fields);
        $temp_items = $list->items;
        $temp_items_ing = array();
        $temp_items_end = array();
        foreach ($temp_items as $key=>$val){
            if(strtotime($val['end_time'])>=time()){
                array_unshift($temp_items_ing,$val);
            }else{
                array_push($temp_items_end,$val);
            }
        }
        $items = array_merge($temp_items_ing,$temp_items_end);
        $filter_ids = [29,40,41,45,48];
//        $filter_all_ids = [63,64,65,67];//改写到service层控制了
        foreach ($items as $key=>$val){
            if(in_array($val['id'],$filter_ids)){
               if(!in_array($company_id,['114954006','989786','308933','115036877','115029985','114528443','299781','114570537','114451432'])){
                   unset($items[$key]);
               }
            }
//            if(in_array($val['id'],$filter_all_ids) && SlightPHP::getDebug() ==false){
//                unset($items[$key]);
//            }
        }
        $sids = array();
        foreach ($items as $val) {
            array_push($sids, $val['id']);
        }
        $service_shuangxuancompanynet = new base_service_schoolnet_shuangxuancompanynet();
        $sid_company_number_list = $service_shuangxuancompanynet->getNetCompanyNumber($sids);
        $company_status_list = $service_shuangxuancompanynet->getNetCompany($sids, $company_id, 'sid,company_id,status,remark');
        $to_url = base_lib_Constant::COMPANY_URL . "/licencevalidate";
        $to_url = "<a target='_blank' href='{$to_url}'><u>(去验证)</u></a>";
        $check_company_info = $service_shuangxuancompanynet->checkCompanyInfo($company_id, $to_url);
        $this->_aParams['check_company_info'] = $check_company_info;
        //var_dump($company_status_list);
        $xml = SXML::load('../config/cp/config.xml');
        //echo json_encode($xml);die();
        foreach ($items as $key => $val) {
            if($val['logo']){
                $items[$key]['logo'] = base_lib_Constant::UPLOAD_FILE_URL . '/' . $xml->VirtualName . '/' . $xml->shuangXuanNetLogoImageFilePath . $val['logo'];
            }
            //活动参加公司数量
            if (isset($sid_company_number_list[$val['id']])) {
                $items[$key]['count_num'] = $sid_company_number_list[$val['id']]['count_num'];
            } else {
                $items[$key]['count_num'] = 0;
            }
            //公司活动状态
            if (isset($company_status_list[$val['id']])) {
                $items[$key]['company_status'] = $company_status_list[$val['id']]['status'];
                $items[$key]['company_remark'] = $company_status_list[$val['id']]['remark'];
            } else {
                $items[$key]['company_status'] = -1;
            }
            //活动状态判断
            $activity_ing_status = $this->_getActivityStatus($val['start_time'], $val['end_time']);
            $items[$key]['activity_ing_status'] = $activity_ing_status;
            //时间格式修改  0未开始；1进行中；2结束
            if (1 == $activity_ing_status) {
                //$day = base_lib_TimeUtil::time_diff_day('', $val['end_time']);
               // $day = base_lib_TimeUtil::to_friend_time5($val['end_time']);
                $items[$key]['date_text'] = '进行中（' . date('Y年m月d日 H:i', strtotime($val['start_time'])) . '~' . date('Y年m月d日 H:i', strtotime($val['end_time'])) . '）';
                /* if (0 == $day) {
                     $items[$key]['date_text'] = '进行中（今天结束）';
                 } else {
                     $items[$key]['date_text'] = '进行中（' . $day . '天后结束）';
                 }*/
            } else if (2 == $activity_ing_status) {
                $items[$key]['date_text'] = '已结束';

            } else {
                $items[$key]['date_text'] = date('Y年m月d日 H:i', strtotime($val['start_time'])) . '~' . date('Y年m月d日 H:i', strtotime($val['end_time']));
            }

        }
        $this->_aParams['list'] = $items;
        return $this->render('schoolnet/index.html', $this->_aParams);
    }

    /**
     *全局视频弹出视频面试
     */
    public function pageApplyOne($inPath)
    {
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $company_id = $this->_userid;
        $account_id = base_lib_BaseUtils::getCookie('accountid');
        $service_company_account = new base_service_company_account();
        $fields = 'account_id,company_id,is_main,user_id';
        $account = $service_company_account->getAccount($account_id, $fields);
        //主账号
        if(1!=$account['is_main']){
            return $this->ajax_data_json(1);
        }
        $params['company_id'] = $company_id;
        $service_person = new base_service_schoolnet_shuangxuanpersonapply();
        $fields = 'id,sid,company_id,job_id,person_id,resume_id';
        $apply_status_1 = $service_person->getCompanyOne($company_id, $fields , array('status'=>1));
        //有面试中
        if($apply_status_1){
            return $this->ajax_data_json(1);
        }
        $apply_list = $service_person->getCompanyNewOne($company_id, $fields);
        $cookie_is_video_tip =  base_lib_BaseUtils::getCookie('is_video_tip');
        if (1 == count($apply_list)) {
            $apply = $apply_list[0];
            if($cookie_is_video_tip && $apply['id']==$cookie_is_video_tip){
                return $this->ajax_data_json(1);
            }
            $service_person = new base_service_person_person();
            $person_item = $service_person->getPerson($apply['person_id'], 'person_id,user_name,sex,birthday2,photo');
            $service_resume = new base_service_person_resume_resume();
            $resume_item = $service_resume->getResume($apply['resume_id'], 'resume_id,user_name,sex,birthday,school,major_desc,degree_id', false);
            //var_dump($apply['resume_id']);
            $service_job = new base_service_company_job_job();
            $job_item = $service_job->getJob($apply['job_id'], 'job_id,station');
            $common_sex = new base_service_common_sex();
            $common_sex_list = $common_sex->getSex();
            //学历
            $service_degree = new base_service_common_degree();
            $degree_list = $service_degree->getAll();
            $service_person_enter = new base_service_schoolnet_shuangxuanpersonenter();
            $person_enter = $service_person_enter->getRecord(array('sid'=>$apply['sid'],'person_id'=>$apply['person_id']),'person_id,major_desc');
            $data = array(
                'shuangxuan_apply_id' => $apply['id'],
                'sid' => $apply['sid'],
                'job_id' => $apply['job_id'],
                'person_id' => $apply['person_id'],
                'resume_id' => $apply['resume_id'],
                'user_name' => $person_item['user_name'],
                'sex' => isset($common_sex_list[$person_item['sex']]) ? $common_sex_list[$person_item['sex']] : '未知',
                'age' => base_lib_TimeUtil::ceil_diff_year($person_item['birthday2']),
                'photo' => $person_item['photo']?base_lib_Constant::UPLOAD_FILE_URL.$person_item['photo']:base_lib_Constant::APP_STYLE_URL.'/img/m/new_person/headportrait.png',
                'degree' => $degree_list[$resume_item['degree_id']]?:'',
                'school' => $resume_item['school'],
                'major_desc' => $person_enter['major_desc']?$person_enter['major_desc']:'',
                'station' => $job_item['station'],
            );
            $cookie = array('is_video_tip'=>$apply['id']);
            base_lib_BaseUtils::ssetcookie($cookie, 24*3600, '/', base_lib_Constant::COOKIE_HUIBO_DOMAIN);
            return $this->ajax_data_json(SUCCESS, '有新的面试申请', $data);
        } else {
            return $this->ajax_data_json(1);
        }
    }

    public function pageShowEnter($inPath)
    {
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        var_dump($params);
    }
    /**
     * @param $start_time
     * @param $end_time
     * @return int   0未开始；1进行中；2结束
     */
    protected function _getActivityStatus($start_time, $end_time)
    {
        $start_time = strtotime($start_time);
        $end_time = strtotime($end_time);
        $now_time = time();
        if ($end_time < $start_time) {
            return 2;
        }
        if ($now_time < $start_time) {
            return 0;
        }
        if ($now_time <= $end_time) {
            return 1;
        } else {
            return 2;
        }
    }
	
}

?>