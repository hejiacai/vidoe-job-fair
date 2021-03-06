<?php
/**
 * @copyright 2002-2013 www.huibo.com
 * @name   公司
 * @author momo
 * @version 2013-7-16
 */
class controller_company extends components_cbasepage {

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}


	/**
	 * 企业完善资料
	 */
	public function pageImproveCompanyInfo($inPath)
	{
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

		$service_company =new base_service_company_company();
		$company = $service_company->getCompany($this->_userid, 1, 'company_id,company_flag,'
			. 'company_name,property_id,size_id,area_id,calling_ids,address,postcode,is_audit,'
			. 'homepage,info,hr_manager,hr_manager_sex,hr_tel,show_email,linkman,linkman_sex,'
			. 'open_linkman,linkman_station,link_tel,open_tel,fax,open_fax,link_mobile,'
			. 'open_mobile,email,'
			. 'company_bright_spot,company_shortname,company_reward_ids,company_other_reward');

		$this->_aParams['company'] = $company;
		//获取企业固定电话信息
		$this->_aParams['phone_infor'] = $this->get_telephone_infor($company["link_tel"]);


		// 获取公司性质码表信息
		$properties_service = new base_service_common_comproperty();
		$this->_aParams["properties"]       = $properties_service->getAll();
		if(empty($this->_aParams['company']['property_id'])){
			$this->_aParams['company']['property_id'] = '01';//默认民营
		}

		// 获取公司规模码表信息
		$service_comsize =  new base_service_common_comsize();
		$this->_aParams['comsizes']     = $service_comsize->getAll();
		if(empty($this->_aParams['company']['size_id'])){
			$this->_aParams['company']['size_id'] = '01';//默认50
		}

		//获得企业行业数据
		$calling_service = new base_service_common_calling();
		$calling_arr = array_slice(explode(",", $company['calling_ids']), 0, 2);

		$this->_aParams['calling_arr'] = $calling_arr;

		//获得主行业名称
		if (!empty($calling_arr[0]))
        {
            $calling_names[0] = $calling_service->getCallingName($calling_arr[0]);
            $service_callingadvantage = new base_service_common_callingadvantage();
            $this->_aParams['spots'] = $service_callingadvantage->getAdvantage($calling_arr[0]);
        }

		//获得次行业名称
		if (!empty($calling_arr[1]))
			$calling_names[1] = $calling_service->getCallingName($calling_arr[1]);

		$this->_aParams['calling_names'] = $calling_names;

        //融资阶段码表
        $service_franace = new base_service_common_frnance();
        $franaces = $service_franace->getFrance();

        $_framaces = ['0'=>'请选择'];
        foreach ($franaces as $k=>$v){
            $_framaces[(string)$k] = $v;
        }
        $this->_aParams['franaces'] = $_framaces;

        $service_state = new company_service_comstate();
        $company_state = $service_state->GetStateCompanyDataByID($this->_userid,'frnance_type');
        $this->_aParams['frnance_type'] = $company_state['frnance_type'];

        
		echo $this->render('./register/registerstep2.html',$this->_aParams);
	}


	/**
	 * 企业资料修改
	 * @param object $inPath 参数信息
	 */
	public function pageModify($inPath) {
        if(!$this->canDo("company_info_init")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
		$inData = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

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
        
        $cxml = SXML::load('../config/config.xml');
        if(!is_null($cxml))
            $this->_aParams['hot_serve_phone'] = $cxml->HuiboPhone400;
        else
            $this->_aParams['hot_serve_phone'] = '400-1010-970';
        
		$this->_aParams['logo'] = empty($company['company_logo_path']) 
			? base_lib_Constant::STYLE_URL . "/img/job/newjob/newJob_57.png"
			: base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $VirtualName . '/' . $LogoFolder . '/' . $company['company_logo_path'];

		$this->_aParams['logo_virt_path'] =  base_lib_Constant::UPLOAD_FILE_URL . '/' . $VirtualName . '/' . $logoTempfolder . '/';

		//获取企业固定电话信息
		$this->_aParams['phone_infor'] = $this->get_telephone_infor($company["link_tel"]);

		//获取企业视频信息
		$this->_aParams["video_infor"] = $this->get_video_infor($company["company_video_name"], $company["company_video_path"]);

        //获得企业行业数据
		$calling_service = new base_service_common_calling();
		$calling_arr = array_slice(explode(",", $company['calling_ids']), 0, 2);

		$this->_aParams['calling_arr'] = $calling_arr;

	    //获得主行业名称
		if (!empty($calling_arr[0]))
        {
            $calling_names[0] = $calling_service->getCallingName($calling_arr[0]);
            $service_callingadvantage = new base_service_common_callingadvantage();
            $this->_aParams['spots'] = $service_callingadvantage->getAdvantage($calling_arr[0]);
        }

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
	
		// 所有福利
		$service_reward = new base_service_common_reward();
		$this->_aParams["all_reward_data"] = $service_reward->getAll();
		
		//公司默认福利
		$this->_aParams["hidDefaultReward"]        = $company['company_reward_ids'];
		$this->_aParams['company_default_rewards'] = empty($company['company_reward_ids']) ? [] : explode(',', $company['company_reward_ids']);

		//公司其他福利
		$this->_aParams["hidOtherReward"]        = $company['company_other_reward'];
		$this->_aParams['company_other_rewards'] = empty($company['company_other_reward']) ? [] : explode(',', $company['company_other_reward']);
                 
		// 百度地图
		$service_baidumap = new base_service_company_companycooperaterbaidumap();
		$map = $service_baidumap->getCompanyCooperateBaiduMapByCompanyID($this->_userid);
		$this->_aParams['map'] = $map;

		$this->_aParams['title'] = '企业资料修改';
		$this->_aParams['upload_cookie_userid']   = $this->_userid;
		$this->_aParams['upload_cookie_nickname'] = $this->_username;
		$this->_aParams['upload_cookie_usertype'] = $this->_usertype;
		$this->_aParams['upload_cookie_userkey']  = base_lib_BaseUtils::getCookie('userkey');
		$this->_aParams['upload_cookie_tick']     = base_lib_BaseUtils::getCookie('tick');

        $service_resource = base_service_company_resources_resources::getInstance($this->_userid);
        list($audit_status,$audit_param) = $service_resource->getCompanyAuditStatus();
        $this->_aParams["audit_status"] = $audit_status;


        //融资阶段码表
        $service_franace = new base_service_common_frnance();
        $franaces = $service_franace->getFrance();

        $_framaces = ['0'=>'请选择'];
        foreach ($franaces as $k=>$v){
            $_framaces[(string)$k] = $v;
        }
        $this->_aParams['franaces'] = $_framaces;

        $service_state = new company_service_comstate();
        $company_state = $service_state->GetStateCompanyDataByID($this->_userid,'frnance_type');
        $this->_aParams['frnance_type'] = $company_state['frnance_type'];

		return $this->render('sysmanage/companymodify.html', $this->_aParams);
	}

	/**
	 *
	 * 修改企业资料
	 * @param object $inPath 参数信息
	 */
	public function pageModifyBasicInfo($inPath) {
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

	 	$result->has_err = $this->save_company_base_info($base_infor);
	 	if (!empty($result->has_err)) {
	 		$result->addErr($result->has_err);
	 		echo $result->toJsonWithHtml();
	 		return;
	 	}

		// 同步修改多账户表中的主账户信息
		$service_account = new base_service_company_account();
        $main_account    = $service_account->getMainAccount($this->_userid, "account_id");
		$account_info_insert = array();
		if(!empty($base_infor['link_mobile']))
			$account_info_insert['mobile_phone'] = $base_infor['link_mobile'];
		if(!empty($pathdata['phone_infor']))
		{
			$account_info_insert['link_tel'] = base_lib_BaseUtils::getStr($pathdata['phone_infor']);
			if(!empty($pathdata['zone_infor']))
				$account_info_insert['link_tel'] = $base_infor['zone_infor'].'-'.$account_info_insert['link_tel'];
			if(!empty($pathdata['ext_infor']) && $pathdata['ext_infor'] != '分机号'){
				$account_info_insert['link_tel'] .= '-'.$pathdata['ext_infor'];
			}
			$account_info_insert['link_tel'] = base_lib_BaseUtils::getStr($account_info_insert['link_tel']);
		}
        $account_info_insert['user_name'] = $base_infor['linkman'];
		if(!empty($account_info_insert) && !empty($main_account["account_id"]))
			$service_account->updateAccount($main_account["account_id"],$account_info_insert);

        $company_service_comstate = new company_service_comstate();


        //判断 给单位打电话无效标
        $service_company_linkmanAnomaly = new company_service_linkmanAnomaly();
        $is_link = $service_company_linkmanAnomaly->IsLinkmanAnomalyCompanyVain($this->_userid);
        if ($is_link){
            //电话异常举报
            $service_exception = new company_service_exception();
            $comstate_info = $company_service_comstate->GetStateCompanyDataByID($this->_userid,'company_id,invalid_tel,spe_calling,bad_com');
            if ($comstate_info['invalid_tel']!=1){
                $service_exception->setCompanyState('invalid_tel',1, $this->_userid);

                $exceptional_data['exceptional_type'] = 'invalid_tel';
                $exceptional_data['company_id'] = $this->_userid;
                $exceptional_type = 'invalid_tel';
                $service_exception->addExceptional($exceptional_data, 0);
                $exceptional = $service_exception->getExceptionalByCompany($this->_userid, array('invalid_tel'), 1, 'exceptional_id')->items;
                $service_exception->exceptionalDo($exceptional_type, $exceptional[0]['exceptional_id'], 0);
            }
        }else{
            $service_exception = new company_service_exception();
            $service_exception->setCompanyState('invalid_tel',0, $this->_userid);
        }
		//清除单位缓存
		$ser_company = new base_service_company_company();
        $ser_company->cacheDelete($this->_userid);

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

        //融资阶段修改
        $company_service_comstate->updateStateCompanyData($this->_userid,['frnance_type'=>$base_infor['frnance_type']]);


        echo json_encode(array("success"=>true));
	 	return;
	}


	public function pageGetCompanySpotByCallingid($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $calling_id = base_lib_BaseUtils::getStr($pathdata['calling_id'],'string','');
        if(empty($calling_id))
            exit(json_encode(['status'=>false,'msg'=>'行业不能为空']));

        $service_callingadvantage = new base_service_common_callingadvantage();
        $advantage_text = $service_callingadvantage->getAdvantage($calling_id);

        exit(json_encode(['status'=>true,'msg'=>'ok','spot'=>$advantage_text]));
    }

	/**
	 * LOGO的临时保存
	 * @param object  $inPath
	 */
	public function pageSaveTempLogo($inPath) {
		$service_company =new base_service_company_company();
		$file = $_FILES['Filedata'];
		$arr = $service_company->saveTempLogo($this->_userid, $file);
		if ($arr !== false) {
		   echo json_encode($arr);
		}
	}

	/**
	 * 保存地图信息
	 * @param array  $inPath 页面参数信息
	 */
	public function pageSaveMap($inPath) {
        if(!$this->canDo("edit_company_info")){
            echo json_encode(array('error'=>'无权限访问，没有开通相应权限'));
			return;
        }
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$validator = new base_lib_Validator();

		$address = $validator->getNotNull($pathdata['txtAddress'], '公司地址不能为空');
		$area_id = $validator->getNotNull($pathdata['hidArea'], '公司地区不能为空');

		if ($validator->has_err) {
			echo header("Content-type:text/plain;charset=utf-8");
			echo $validator->toJsonWithHtml();
			return;
		}

        $service_company = new base_service_company_company();
		$old_company = $service_company->getCompany($this->_userid,'1','map_x,map_y,area_id,address');
        
		$result = true;
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
		}
		
		if ($result === false) {
			echo json_encode(array('error'=>'保存公司地图标注失败'));
			return ;
		}
        
        $has_change_map = false;
        if($old_company["map_x"] != $map['map_x'] || $old_company["map_y"] != $map['map_y'] || $old_company["area_id"] != $area_id || $old_company["address"] != $address)
            $has_change_map = true;
		echo json_encode(array('success'=>true,"has_change_map"=>$has_change_map));
	}

	/**
	 * 保存临时照片
	 * @param array $inPath
	 */
	public function pageSaveTempPhoto($inPath) {
	 	$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
	 	$service_company = base_service_company_company::getInstances();

       	$file = $_FILES['Filedata'];
       	$arr = $service_company->saveTempPhoto($this->_userid, $file);
       	if ($arr !== false) {
			echo json_encode($arr);
       	}
	}

	/**
	 * 获取剩余上传数量
	 * @param array $inPath
	 */
	public function pageGetFree($inPath) {
		$xml = SXML::load('../config/company/company.xml');
		$max_photo_count = is_null($xml) ? 20 : $xml->PhotoMaxCount;

		$service_companyphoto= new base_service_company_companyphotoalbum();
		$alr_photo_count = $service_companyphoto->getPhotoCount($this->_userid);
		
		$photo_free = $max_photo_count - $alr_photo_count;
		echo json_encode(array('photo_free' => $photo_free));
		return;
	}

    /**
     *@desc 地图弹窗
     *@param string $save_path  裁剪后保存图片地址
     */
    public function pageMapDialog($inpath) {
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inpath));
		$this->_aParams['area_id'] = $pathdata['area_id'];
		$this->_aParams['mapX']    = $pathdata['mapX'];
		$this->_aParams['mapY']    = $pathdata['mapY'];
		$this->_aParams['mapZoom'] = $pathdata['mapZoom'];

        return $this->render('sysmanage/map.html', $this->_aParams);
    }
    
    /**
     * @desc 选择行业
     * @param string $calling_id
     */
    public function pageSelectCalling($inpath){
		$pathdata 	= base_lib_BaseUtils::saddslashes($this->getUrlParams($inpath));
		$calling_id = base_lib_BaseUtils::getStr($pathdata['calling_id'], 'string', NULL);
		$type       = base_lib_BaseUtils::getStr($pathdata['type'], 'int', -1);

		$calling_service = new base_service_common_calling();
		$top_callings = $calling_service->getTopCallings();

		foreach ((array)$top_callings as $k => $top) {
			$parent_id = $top['calling_id'];
			$subitem = $calling_service->getSubItem($parent_id);
			$top_callings[$k]['subItem'] = $subitem;
		}

		$this->_aParams['type']       = $type;
		$this->_aParams['calling_id'] = $calling_id;
		$this->_aParams['callings']   = $top_callings;

		if ($type == 2) {
			return $this->render("./part/calling.html", $this->_aParams);
		} else {
			return $this->render("calling.html", $this->_aParams);
		}
    }

	/**
	 *保存相册与视频信息
	 *@param array  $inPath 页面参数信息
	 */
	public function pageSavePhotoAndVideo($inPath) {
        if(!$this->canDo("save_company_photo")){
            echo json_encode(array('error'=>'无权限访问，没有开通相应权限'));
            return;
        }
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$photo_url_arr = base_lib_BaseUtils::getStr($pathdata['hddNewPhotoName'], 'array', '');
		$photo_del_arr = base_lib_BaseUtils::getStr($pathdata['hddDelPhotoID'], 'array', '');
		$photo_mod_arr = base_lib_BaseUtils::getStr($pathdata['hddModPhotoID'], 'array', '');
		
		$service_companyphoto = new base_service_company_companyphotoalbum();
		//删除文件
		if (is_array($photo_del_arr) && $photo_del_arr != '') {
			for ($j = 0; $j < count($photo_del_arr); $j++) {
				$photo_del_id = $photo_del_arr[$j];
				$this->__delPhotoData($photo_del_id, $this->_userid);
			}
		}

		//修改文件
		if (is_array($photo_mod_arr) && $photo_mod_arr != '') {
			for ($j = 0; $j < count($photo_mod_arr); $j++) {
				$photo_mod_temp_arr = explode('|', $photo_mod_arr[$j]);
				$photo_mod_id       = $photo_mod_temp_arr[0];
				$photo_mod_name     = $photo_mod_temp_arr[1];
				$result = $service_companyphoto->getPhotoAlbum($photo_mod_id);
				if (!base_lib_BaseUtils::nullOrEmpty($result)) {
					$service_companyphoto->modPhotoAlbum($photo_mod_id, $this->_userid, $photo_mod_name);
				}
			}
		}

		//保存文件
		if (is_array($photo_url_arr)) {
			$photo_max_count = 20;
			$alr_photo_count = $service_companyphoto->getPhotoCount($this->_userid);
			$xml = SXML::load('../config/company/company.xml');
			if (!is_null($xml)) {
				$photo_max_count     = $xml->PhotoMaxCount;
				$photo_folder        = $xml->PhotoFolder;
				$photo_thumb_suffix  = $xml->PhotoThumbSuffix;
				$photo_temp_folder   = $xml->PhotoTempFolder;
				$company_images_path = $xml->CompanyImagePath;
				$virtualName         = $xml->VirtualName;
			}

			//已经上传的，减去标记删除的，加上即将要上传的，如果大于最大数量，则提示错误
			if ($alr_photo_count - count($photo_del_arr) + count($photo_url_arr) > $photo_max_count){
				echo json_encode(array('error'=>'上传已经达到最大数量限制'));
				return;
			}

			$photo_names = array();
			for ($i = 0; $i < count($photo_url_arr); $i++) {
				$photo_arr  = explode('|', $photo_url_arr[$i]);
				$photo_path = $photo_arr[0];
				$photo_name = $photo_arr[1];

				$item['photo_name'] = $photo_name;
				$item['photo_path'] = $photo_path;
				$item['company_id'] = $this->_userid;

				$service_companyphoto->addPhotoAlbum($item);
				array_push($photo_names, $item['photo_path']);
			}

			//移动文件到正式文件夹下
			$postvar['newfile']      = "{$virtualName}/{$photo_folder}".'/'.$this->_userid;
			$postvar['oldfile']      = "{$virtualName}/{$photo_temp_folder}".'/'.$this->_userid;
			$postvar['names']        = $photo_names;
			$postvar['thumbSuffix']  = $photo_thumb_suffix;
			$postvar['authenticate'] = "photo";
			$result_move = base_lib_Uploadfilesv::moveFile($postvar);

			if (!$result_move) {
				echo json_encode(array('error'=>'上传文件失败'));
				return;
			} else {
				// 移动云存储文件到正式目录，上传七牛云存储
				$qiniu = new SQiniu();
				for ($k = 0; $k < count($photo_names); $k++) {
					$qiniu->Move($postvar['oldfile'] . "/" . $photo_names[$k], base_lib_Constant::QINIU_BUCKET, $postvar['newfile'] . "/" . $photo_names[$k], base_lib_Constant::QINIU_BUCKET);
					//移动缩略图
					$qiniu->Move($postvar['oldfile'] . "/" . str_replace('.', $photo_thumb_suffix . ".", $photo_names[$k]),
						base_lib_Constant::QINIU_BUCKET, $postvar['newfile'] . "/" . str_replace('.', $photo_thumb_suffix . ".", $photo_names[$k]), base_lib_Constant::QINIU_BUCKET);
				}
			}
		}

		$mien_infor = $this->get_mien_infor_from_post($pathdata);
		$result     = $this->validate_mien_info($mien_infor);
		if ($result->has_err) {
			echo $result->toJsonWithHtml();
			return;
		}

		$result->err = !$this->save_mien_infor($mien_infor);
		if ($result->has_err){
			$result->addErr("修改数据失败");
			echo $result->toJsonWithHtml();
			return;
		}

		$company_photos = $service_companyphoto->getPhotoAlbumList($this->_userid, 'photo_id,photo_name,photo_path');
		$company_photo_items = $company_photos->items;
		$alr_photo_count = count($company_photo_items);
		for ($j2 = 0; $j2 < count($company_photo_items); $j2++) {
			$fileParts = pathinfo($company_photo_items[$j2]['photo_path']);
			$company_photo_items[$j2]['photo_thumb_path'] = $fileParts['filename'] . $photo_thumb_suffix . '.' . $fileParts['extension'];
		}

		//更新企业冗余字段  photo_count
		$service_company = new base_service_company_company();
		$service_company->saveCompanyPhotoCount($this->_userid, $alr_photo_count);
		
		$json['company_photo']   = $company_photo_items;
		$json['alr_photo_count'] = $alr_photo_count;
		$json['success']         = true;

		echo json_encode($json);
		return;
	}

	// /**
	//  * 删除临时照片（废弃）
	//  * @param array $inPath
	//  */
	// public function pageDelTempPhoto($inPath){
	// 	$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
	// 	$file_path = base_lib_BaseUtils::getStr($pathdata['file_path'],'string','');
	// 	if($file_path==''){
	// 		echo json_encode(array('error'=>'获取参数失败'));
	// 		return;
	// 	}
	// 	$this->__delPhotoFile($this->_userid,$file_path);
	// 	echo json_encode(array('success'=>true));
	// 	return;
	// }

	public function pageShowVideoInfo($inPath) {
		return $this->render("getVd.html", $this->_aParams);
	}
	//发送验证码
	public function pageSendAuthCode($inPath){
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

		$mobile_phone = base_lib_BaseUtils::getStr($pathdata['mobile_phone'], "string", "");

		if(empty($mobile_phone) || !preg_match('/^(?:13|15|18|14|17|19|16)[0-9]\d{8}$/', $mobile_phone)){
			echo json_encode(["status"=>FALSE, "msg"=>"请输入正确的电话号码"]);
			return;
		}
		$client_ip = base_lib_BaseUtils::getIp(0);
		$error = '';

		$service_companyregvlid = new base_service_company_companyregvlid();
		$action_common          = new base_service_common_actionsource();

		$service_companyregvlid->createRegValidCode($client_ip, $mobile_phone, $error, $action_common->website);
		if(!empty($error)){
			echo json_encode(["status"=>false, "msg"=>$error]);
			return;
		}
		echo json_encode(["status"=>true]);
		return;
	}

	/**
	 * 删除照片（删除数据库及文件）
	 * @param int $photo_id
	 */
	private function __delPhotoData($photo_id, $company_id){
		$service_companyphoto= new base_service_company_companyphotoalbum();
		$company_photo = $service_companyphoto->getPhotoAlbum($photo_id);
		if (base_lib_BaseUtils::nullOrEmpty($company_photo)) {
			return;
		}

		$result = $service_companyphoto->delPhotoAlbum($photo_id, $company_id);
		if ($result) {
			$this->__delPhotoFile($company_id, $company_photo['photo_path']);
			return $result;
		}

		return false;
	}

	/**
	 * 删除照片（删除文件）
	 * @param int $company_id
	 * @param int $file_path
	 */
	private function __delPhotoFile($company_id, $file_path){
		$service_company = base_service_company_company::getInstances();
		$service_company->delPhoto($company_id, $file_path);
	}

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

//		 $validator->getNotNull($base_infor["main_calling"], "请选择公司所属行业");
		$validator->getNotNull($base_infor["info"], "请输入公司简介");
		$validator->getStr($base_infor["info"], 2, 4000, "公司简介请输入2-4000个字");
		$validator->getStr($base_infor["hidCompanyShortName"], 1, 15, "公司简称请输入1-15个字");
        $validator->getStr($base_infor["hidRightSpot"], 5, 50, "公司优势请输入5-50个字");
		$validator->getStr($base_infor["info"], 2, 4000, "公司简介请输入2-4000个字");
		$validator->getNotNull($base_infor["linkman"], "请输入联系人姓名");
		$validator->getStr($base_infor["linkman"], 1, 10, "联系人姓名不能超过10字");

//		//固定电话和手机号必须填写一项的验证
//		if(empty($base_infor["link_tel"]) && empty($base_infor["link_mobile"])){
//			$validator->addErr("手机号和固定电话必填写一项");
//		}
		if(!empty($base_infor["link_tel"]))
		{
			$validator->getNotNull($base_infor["link_tel"], "请输入座机号码");
			$validator->getTel($base_infor["link_tel"], "请输入正确的座机号码");
		}

		$validator->getMobile($base_infor["link_mobile"], "请输入正确的手机号码");



		if (!empty($base_infor["company_name"]))
			$validator->getStr($base_infor["company_name"], 2, 30, "公司名称请输入2-30字");

		if ($base_infor["email"] != "")
			$validator->getEmail($base_infor["email"], "请输入正确的邮箱地址");

		if ($base_infor["fax"] != "")
			$validator->getTel($base_infor["fax"], "请输入正确的传真号码");


		//互联网/游戏/软件行业必填 融资阶段
        $_calling_id = $base_infor['main_calling'];
        if(empty($_calling_id)){
            $service_company = new base_service_company_company();
            $company = $service_company->getCompany($this->_userid,1,'calling_ids');
            $_calling_id = $company['calling_ids'];
        }
        if(in_array($_calling_id,['01','03','04','05']) && in_array($base_infor['frnance_type'],['',0,'0'])){
            $validator->addErr("互联网/游戏/软件行业必选【融资阶段】");
        }

		return $validator;
	}

	/**
	 * [save_company_base_info 保存企业的基本信息]
	 * @param  array $base_infor  基本信息数组
	 * @return boolean            是否成功
	 */
	private function save_company_base_info($base_infor) {
		$service_company = new base_service_company_company();
		$company = $service_company->getCompany($this->_userid, 1, 'company_id,company_name,property_id,'
			. 'size_id,area_id,calling_ids,address,postcode,homepage,info,hr_manager,hr_manager_sex,hr_tel,'
			. 'email,show_email,linkman,linkman_sex,open_linkman,linkman_station,link_tel,open_tel,fax,'
			. 'open_fax,link_mobile,open_mobile,company_logo_path,company_video_path,company_video_name,'
			. 'company_reward_ids,company_other_reward,company_bright_spot,company_shortname');

		$company["property_id"] = $base_infor["property_id"];
        $calling_ids = $base_infor['main_calling'];
        if (!base_lib_BaseUtils::nullOrEmpty($base_infor['next_calling'])) {
            $calling_ids = $calling_ids . "," . $base_infor['next_calling'];
        }

        if (!empty($calling_ids))
			$company["calling_ids"] = $calling_ids;
		//短信验证码
		if($base_infor["link_mobile"]!=$company["link_mobile"]){
			if(empty($base_infor["authCode"])){
				return "请输入短信验证码";
			}
			$ip = base_lib_BaseUtils::getIp(0);
			$service_companyregvlid = new base_service_company_companyregvlid();
			$service_companyregvlid->validMobilePhone($ip,$base_infor["link_mobile"],$base_infor["authCode"],$error);
			if (!empty($error)) {
				return $error;
			}
		}
		$company["size_id"]              = $base_infor["size_id"];
		$company["info"]                 = $base_infor["info"];
		$company["linkman"]              = $base_infor["linkman"];
		$company["fax"]                  = $base_infor["fax"];
		$company["homepage"]             = $base_infor["homepage"];
		$company["link_mobile"]          = $base_infor["link_mobile"];
		$company["email"]                = $base_infor["email"];
		$company["open_linkman"]         = $base_infor["open_linkman"];
		$company["link_tel"]             = $base_infor["link_tel"];
		$company["company_logo_path"]    = $base_infor["logo"];
		$company["company_reward_ids"]   = $base_infor["company_reward_ids"];
		$company["company_other_reward"] = $base_infor["company_other_reward"];

		if(!empty($base_infor["company_name"]))
			$company["company_name"] = $base_infor["company_name"];

		//判断是否修改企业简称
        $service_shortname_audit = new base_service_company_shortnameaudit();
		if ($company["company_shortname"] != $base_infor['hidCompanyShortName']) {
			//修改审核状态
			$shortname_result = $service_shortname_audit->addCompanyAudit($this->_userid,$base_infor['hidCompanyShortName'], 0);
			if($shortname_result == false){
				return false;
			}

        }else{//2019-8-8 当简称修改一致时，需要清除审核表未审核数据
            $service_shortname_audit->clearAudit($this->_userid);
        }

		//8月4日        新需求，注册企业或者修改企业简称时，简称不写入company表，而是进入审核表
        //$company["company_shortname"] = $base_infor["hidCompanyShortName"];
//		if(empty($company["company_shortname"])){
//			$company["company_shortname"] = '';
//		}else{
//			$company["company_shortname"] = $company["company_shortname"];
//		}


		//判断是否修改企业主营业务
		if ($company['company_bright_spot'] != $base_infor['hidRightSpot']) {
			//修改审核状态
			$service_brightspot = new base_service_company_brightspot();
			$service_brightspot->addCompanyAudit($this->_userid,0);
		}
		
        $company["company_bright_spot"] = $base_infor["hidRightSpot"];

		$result = $service_company->updateCompany($company, explode(',', $company["calling_ids"]));
		if(!$result)
			return '修改数据失败';
		else
			return null;
	}


    /**
     * 单独保存福利待遇
     * @param $inPath
     */
    public function pageUpdateRewards($inPath)
    {
        $path_data                       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $service_company                 = new base_service_company_company();
        $company['company_id']           = $this->_userid;
        $_arr_hidDefaultReward           = explode(',', $path_data["hidDefaultReward"]);
        $_arr_hidOtherReward             = explode(',', $path_data["hidOtherReward"]);
        $company["company_reward_ids"]   = $this->get_post_value(implode(',', array_unique($_arr_hidDefaultReward)));
        $company["company_other_reward"] = $this->get_post_value(implode(',', array_unique($_arr_hidOtherReward)));

        $result = $service_company->updateCompany($company, []);

        if ($result) {
            exit(json_encode(['status' => true, 'msg' => '福利待遇保存成功']));
        } else {
            exit(json_encode(['status' => false, 'msg' => '福利待遇保存失败']));
        }
    }

    /**
     * 单独保存企业主营业务
     * @param $inPath
     */
    public function pageUpdateRightSpot($inPath)
    {
        $path_data       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $service_company = new base_service_company_company();
        $company         = $service_company->getCompany($this->_userid, 1, 'company_id,company_bright_spot');

        $base_infor["hidRightSpot"] = $this->get_post_value(($path_data['hidRightSpot']));
        if ($company['company_bright_spot'] != $base_infor['hidRightSpot']) {
            //修改审核状态
            $service_brightspot = new base_service_company_brightspot();
            $service_brightspot->addCompanyAudit($this->_userid, 0);
        }
        $company_update["company_bright_spot"] = $base_infor["hidRightSpot"];


        $result = $service_company->updateCompany($company_update, []);
        if ($result) {
            exit(json_encode(['status' => true, 'msg' => '企业主营业务保存存成功']));
        } else {
            exit(json_encode(['status' => false, 'msg' => '企业主营业务保存失败']));
        }
    }



	/**
	 * [get_company_base_infor_from_post 获取提交数据]
	 * @param  array $post  提交数据
	 * @return array        数据 
	 */
	private function get_company_base_infor_from_post($post) {
		$base_infor = [];

		$base_infor["property_id"]          = $this->get_post_value($post["company_property"]);
		$base_infor["main_calling"]         = $this->get_post_value($post["main_calling"]);
		$base_infor["next_calling"]         = $this->get_post_value($post["next_calling"]);
		$base_infor["size_id"]              = $this->get_post_value($post["company_size"]);
		$base_infor["info"]                 = urldecode(urldecode($this->get_post_value($post["company_info"])));
		$base_infor["linkman"]              = urldecode(urldecode($this->get_post_value($post["linkman"])));
		$base_infor["fax"]                  = $this->get_post_value($post["fax"]);
		$base_infor["homepage"]             = base_lib_BaseUtils::getStr($post["homepage"],'');
		$base_infor["link_mobile"]          = $this->get_post_value($post["link_mobile"]);
		$base_infor["email"]                = $this->get_post_value($post["email"]);
		$base_infor["open_linkman"]         = $this->get_post_value($post["open_linkman"]);
		$base_infor["open_mobile"]          = $this->get_post_value($post["open_mobile"]);
		$base_infor["show_email"]           = $this->get_post_value($post["show_email"]);
		$base_infor["open_tel"]             = $this->get_post_value($post["open_tel"]);
		$base_infor["phone_infor"]          = $this->get_post_value($post["phone_infor"]);
		$base_infor["zone_infor"]           = urldecode(urldecode($this->get_post_value($post["zone_infor"])));
		$base_infor["ext_infor"]            = urldecode(urldecode($this->get_post_value($post["ext_infor"])));
		$base_infor["logo"]                 = $this->get_post_value($post["logo"]);
		$base_infor["authCode"]             = $this->get_post_value($post["authCode"]);
		$base_infor["link_tel"] = '';
		if(!empty($base_infor["zone_infor"]) && !empty($base_infor["phone_infor"]))
			$base_infor["link_tel"]             = $this->collect_telephone_number($base_infor["zone_infor"],$base_infor["phone_infor"],$base_infor["ext_infor"]);
		$base_infor["company_reward_ids"]   = $this->get_post_value($post["hidDefaultReward"]);
		$base_infor["company_other_reward"] = $this->get_post_value($post["hidOtherReward"]);
		$base_infor["hidCompanyShortName"]  = $this->get_post_value($post["hidCompanyShortName"]);
		$base_infor["hidRightSpot"]         = $this->get_post_value($post["hidRightSpot"]);
		$base_infor["frnance_type"]         = $this->get_post_value($post["company_finance"]);

		if(!empty($post["company_name"]))
			$base_infor["company_name"]         = $this->get_post_value($post["company_name"]);
		return $base_infor;
	}

	/**
	 * [get_post_value 获取提交数据]
	 * @param  string $value 提交数据
	 * @return string        数据
	 */
	private function get_post_value($value) {
		return isset($value) ? base_lib_BaseUtils::getStr($value) : "";
	}

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

	private function collect_telephone_number($zone,$phone,$ext){
                //过滤掉phone 里面的- 及();
        $phone_arr = explode("-",$phone);
        $phone = $phone_arr[count($phone_arr)-1];
        $phone_arr = explode("(",$phone);
        $phone = $phone_arr[0];
		$telephone = $phone;
		if(preg_match("/^[0-9]{3}[0-9]?$/",$zone))
			$telephone = $zone."-".$phone;
		else
			$telephone = "023-".$phone;
		if(preg_match("/^[0-9]+$/",$ext))
			$telephone = $telephone."(".$ext.")";
		return $telephone;
	}

	private function get_video_infor($video_name,$video_path){
		$video_infor = array("video_name"=>"视频名称","video_path"=>"优酷、土豆等网站的视频url地址");
		if($video_name!=""){
			$video_infor["video_name"] = $video_name;
		}
		if($video_path!=""){
			$video_infor["video_path"] = $video_path;
		}
		return $video_infor;
	}

	private function get_mien_infor_from_post($post){
		$mien_infor = array();
		if($this->get_post_value($post["has_data"])){
			$mien_infor["video_name"] = $this->get_post_value($post["mien_name"]);
			$mien_infor["video_path"] = $this->get_post_value($post["mien_path"]);
		}
		return $mien_infor;
	}

	private function validate_mien_info($mien_infor){
		$validator = new base_lib_Validator();
		// $url_reg = "^((https?|ftp):\/\/)?(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$";
		// if($mien_infor["video_path"]!="" && !eregi($url_reg, $mien_infor["video_path"]))
		// 	$validator->addErr("请输入正确的视频地址");//print_r($validator);
		return $validator;
	}

	private function save_mien_infor($mien_infor){
		$service_company = new base_service_load(new base_service_company_company());
		$company_info = $service_company->getCompany($this->_userid,'1','company_id,company_video_name,company_video_path');
		$company_info["company_video_name"] = $mien_infor["video_name"];
		$company_info["company_video_path"] = $mien_infor["video_path"];
		return $service_company->updateCompany($company_info,"");
	}
        
        /**
        *@desc 公司上传LOGO
        *@param $inpath
        */
        public function pageUploadCompanyLogo($inPath){
            if(!$this->canDo("save_company_logo")){
                $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
                $this->_aParams["url"] = "/";
                return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
            }
            $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
             
            return $this->render('./sysmanage/uploadlogo.html',$this->_aParams);
        }
        
        /**
        *@desc 上传logo
        *@param  mixed $inpath 
        */
    public function pageUploadImage($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$xml = SXML::load('../config/company/company.xml');
		if(!is_null($xml)){
			$VirtualName = $xml->VirtualName;
			/////////////
			$file_folder = $xml->LogoTempFolder;
			$file_max_size = $xml->LogoMaxSize;
			$file_types = $xml->LogoExtensions;
		}
		$file = $_FILES['imageFile'];
		//检查是否有文件
		if($file==null){
			$json['error'] = 100;
			$json['errorMsg'] = '请选择您要上传的图片';
			echo json_encode($json);
			return;
		}
		//检查大小
		if($file['size']>$file_max_size*1024){
			$json['error'] = 101;
			$json['errorMsg'] = '请上传小于'.$file_max_size.'KB的图片';
			echo json_encode($json);
			return;
		}

		//检查类型
		$extension_name = base_lib_BaseUtils::fileext($file['name']);
		$arr_file_type = explode(',', $file_types);
		if(!in_array('.'.$extension_name,$arr_file_type)){
			$json['error'] = 102;
			$json['errorMsg'] = '请上传格式为'.$file_types.'的图片';
			echo json_encode($json);
			return;
		}

		//用于上传时解决火狐FLASH丢失COOKIE导致无法验证而上传不起的BUG
		$iUserId = isset($_POST['upload_cookie_userid'])?$_POST['upload_cookie_userid']: base_lib_BaseUtils::getCookie('userid');
                if(base_lib_BaseUtils::nullOrEmpty($iUserId)){
                        $json['error'] = 103;
			$json['errorMsg'] = '请先登录您的企业账号';
			echo json_encode($json);
			return;
                }
		//$company_flag = base_lib_Rewrite::getFlag('company', $iUserId);
		$path = "{$VirtualName}/{$file_folder}";
                $company_flag = base_lib_Rewrite::getFlag('company', $this->_userid);
		$postvar['path'] = $path;//存放路径 配置文件件读取
                $time =date('Ymdhis').$company_flag;
		$postvar['name'] = $time.'.'.$extension_name;
                $thumb_name =$time."thumb.".$extension_name;
                $img_info = getimagesize($file['tmp_name']);
                 //获得图片的高宽
                if(base_lib_BaseUtils::nullOrEmpty($img_info)){
                    return null;
                }
                
                $src_width = $img_info[0]; //来源图片宽度
                $src_height = $img_info[1];//来源图片高度
                if($src_width >400){
                    $max_width = 400;
                }else{
                       $max_width = $src_width;
                }
                if($src_height>327){
                    $max_height =327;
                }else{
                    $max_height = $src_height;
                }
                $thumb_type ='';//缩放方式
                $thumb_ratio = 0;//缩放比例
                $height_ratio = $src_height/$max_height;
                $width_ratio = $src_width/$max_width;
                if($width_ratio >= $height_ratio){
                    $thumb_width = $max_width;
                    $thumb_type = "width";
                    $thumb_height = $src_height*($max_width/$src_width);
                    $thumb_ratio = $width_ratio;
                }else{
                    $thumb_height = $max_height;
                    $thumb_type = "height";
                    $thumb_width = $src_width*($max_height/$src_height);
                    $thumb_ratio = $height_ratio;
                }
                
		$postvar['thumbMaxWidth'] = $max_width; //图片最大宽度,上传图片时必填
		$postvar['thumbMaxHeight'] = $max_height; //图片最大高度,上传图片时必填
                //调用方法 成功返回{'success',true}
		$result = base_lib_Uploadfilesv::postfile($postvar, $file['name'],$file['tmp_name'],$file['type']);
		if($result){
            //上传七牛云存储
            //$qiniu = new SQiniu();
            //$path = rtrim($postvar['path'],'/');
            //unset($postvar['thumbMaxWidth']);
            //unset($postvar['thumbMaxHeight']);
            //$qiniu->upload2qiniu($path."/{$postvar['name']}", $file['tmp_name'], base_lib_Constant::QINIU_BUCKET, $postvar);

            $json['status'] = 1;
            $json['name'] = $postvar['name'];
            $json['path'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.'/'.$path;
            $json['fileName'] = $thumb_name;
            $json['item_index'] = $item_index;
            $json['thumb_ratio'] = $thumb_ratio;
            $json['thumb_type'] = $thumb_type;
            echo json_encode($json);
            return;
		}else{
            $json['status'] = 0;
			$json['error'] = 104;
			$json['errorMsg'] = '图片上传失败';
			echo json_encode($json);
			return;
		}	
    }
       /**
        *@desc 保存裁剪LOGO图片
        *@param $inpath
        */ 
      public function pageSaveLogo($inpath){
          $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inpath));
           $company_id = $this->_userid;
            $xml = SXML::load('../config/company/company.xml');
            if(!is_null($xml)){
                $VirtualName = $xml->VirtualName;
                $CompanyImagePath = $xml->CompanyImagePath;
                $file_folder = $xml->LogoTempFolder;//LOGO临时文件路径
                $TemplateImageFolder = $xml->LogoFolder;//LOGO图片完整路径
            }
            //缩放比例
            $thumb_ratio = floatval($pathdata['thumb_ratio']);
            if(empty($thumb_ratio)){
                $json['state'] = 0;
                $json['msg'] ="参数错误";
                echo json_encode($json);
                return;
            }
            //$thumb_type = $pathdata['thumb_type'];
            $source_name = base_lib_BaseUtils::getStr($pathdata['name'],'string','');
            if($source_name ==''){
                $json['state'] = 0;
                $json['msg'] ="图片名称不能为空";
                echo json_encode($json);
                return;
            }
            //上传图片的临时物理地址
            $path= "{$VirtualName}/{$file_folder}";
            $source_x = base_lib_BaseUtils::getStr($pathdata['cropX'],'int',0);//开始裁剪X坐标
            $source_x = $source_x*$thumb_ratio;
            $source_y = base_lib_BaseUtils::getStr($pathdata['cropY'],'int',0);//开始裁剪Y坐标
            $source_y = $source_y*$thumb_ratio;
            $cropped_width = base_lib_BaseUtils::getStr($pathdata['cropW'],'float',0);//裁剪宽度
            $cropped_width = $cropped_width*$thumb_ratio;
            $cropped_height = base_lib_BaseUtils::getStr($pathdata['cropH'],'float',0);//裁剪高度
            $cropped_height = $cropped_height * $thumb_ratio;
            if($cropped_width==0 || $cropped_height==0){
                $json['state'] = 0;
                $json['msg'] ="裁剪的长度不能为0";
                echo json_encode($json);
                return;
            }
                $max_width = 160;
                $max_height =160;
            //裁剪图片
            $postvar['path'] = $path;//图片路径
            $postvar['new_path'] = $path;//图片路径
            $postvar['name'] = $source_name;//裁剪原图名称
            $postvar['type'] ="modify";
            $postvar['authenticate'] = "logo";
            $postvar['new_file_name'] = "copy".$source_name;
            $postvar['modifytype'] =2;
            $postvar['copyX'] = $source_x;
            $postvar['copyY'] = $source_y;
            $postvar['copyW'] = $cropped_width;
            $postvar['copyH'] = $cropped_height;
            $postvar['createWidth'] = 160;
            $postvar['createHeight'] = 160;     
            $result = base_lib_Uploadfilesv::modifyImage($postvar);
            //返回图片地址及名称
            if($result){
	             //移动云存储文件到正式目录
				//上传七牛云存储
				//$qiniu = new SQiniu();
				//$qiniu->CopyModify($postvar['path']."/".$source_name, base_lib_Constant::QINIU_BUCKET, $postvar['path']."/".$postvar['new_file_name'], base_lib_Constant::QINIU_BUCKET,$postvar);
				//$qiniu->Del($postvar['path']."/".$source_name, base_lib_Constant::QINIU_BUCKET);
            	
               $json['image_url'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP."/".$path."/".$postvar['new_file_name'];
               $json['image_path'] = $postvar['new_file_name'];
               $json['state'] = 1;
               $json['msg'] ="裁剪图片成功";
               echo json_encode($json);
                return;
            }else{
                $json['state'] = 0;
                $json['msg'] ="裁剪图片失败";
                echo json_encode($json);
                return;
            }
      }
      /**
      * @desc获得个性化图片的缩略图缓存数据
      * @param string $hard_thumbimg_path  缩略图存放地址
      * @param string $hard_img_path 原图 地址
      * @param int $max_width 缩略图最大宽度
      * @param int @max_height 缩略图最大高度
      */

    private function __imageCopyreSampled($hard_thumbimg_path,$hard_img_path,$max_width,$max_height){
        $img_info = getimagesize($hard_img_path);
        if(base_lib_BaseUtils::nullOrEmpty($img_info)){
            return null;
        }
        $src_width = $img_info[0]; //来源图片宽度
        $src_height = $img_info[1];//来源图片高度
        $src_type = $img_info[2];//来源图片类型
        switch($src_type){
            case 2: //jpeg
                $src_img = imagecreatefromjpeg($hard_img_path);
                break;
            case 3://png
                $src_img = imagecreatefrompng($hard_img_path);
                break;
            default:
                return null;
        }
        $thumb_type ='';
        $thumb_ratio = 0;
        $height_ratio = $src_height/$max_height;
        $width_ratio = $src_width/$max_width;
        if($width_ratio >= $height_ratio){
            $thumb_width = $max_width;
            $thumb_type = "width";
            $thumb_height = $src_height*($max_width/$src_width);
            $thumb_ratio = $width_ratio;
        }else{
            $thumb_height = $max_height;
            $thumb_type = "height";
            $thumb_width = $src_width*($max_height/$src_height);
            $thumb_ratio = $height_ratio;
        }
        //声明一个真彩图片资源
        $dist_img = imagecreatetruecolor($thumb_width, $thumb_height);
        //开始缩放图片
         imagecopyresampled($dist_img, $src_img, 0, 0, 0, 0, $thumb_width, $thumb_height,$src_width, $src_height);
         switch($src_type){
            case 2: //jpeg
               // header('content-type:image/jpeg');
                imagejpeg($dist_img,$hard_thumbimg_path);
                break;
            case 3://png
                // header('content-type:image/png');
                 imagepng($dist_img,$hard_thumbimg_path);
                break;
            default:
                return null;
        }
         imagedestroy($dist_img);
       return array('thumb_type'=>$thumb_type,'thumb_ratio'=>$thumb_ratio);
    }
    
     /**
      * @desc获得个性化图片的裁剪后保存的数据
      * @param string $save_path  裁剪后保存图片地址
      * @param string $hard_img_path 原图 地址
      * @param int $max_width 缩略图最大宽度
      * @param int max_height 缩略图最大高度
      * @param int $source_x 裁剪原图坐标X
      * @param int $source_y 裁剪原图坐标Y
      * @param int $cropped_width 裁剪宽度
      * @param int $cropped_height 裁剪高度
      */

    private function __imageCopyModify($save_path,$hard_img_path,$max_width,$max_height,$source_x,$source_y,$cropped_width,$cropped_height){
        $img_info = getimagesize($hard_img_path);
        if(base_lib_BaseUtils::nullOrEmpty($img_info)){
            return false;
        }
        $src_width = $img_info[0]; //来源图片宽度
        $src_height = $img_info[1];//来源图片高度
        $src_type = $img_info[2];//来源图片类型
        switch($src_type){
            case 2: //jpeg
                $src_img = imagecreatefromjpeg($hard_img_path);//来源图片资源
                break;
            case 3://png
                $src_img = imagecreatefrompng($hard_img_path);//来源图片资源
                break;
            default:
                return false;
        }
         //声明一个真彩图片资源
        $copy_img = imagecreatetruecolor($cropped_width, $cropped_height);
        $dist_img = imagecreatetruecolor($max_width, $max_height);
        //开始裁剪
        imagecopy($copy_img, $src_img, 0, 0, $source_x, $source_y, $cropped_width, $cropped_height);
         //根据裁剪的图片缩放图片
         imagecopyresampled($dist_img, $copy_img, 0, 0, 0, 0, $max_width, $max_height,$cropped_width, $cropped_height);
         switch($src_type){
            case 2: //jpeg
               // header('content-type:image/jpeg');
                imagejpeg($dist_img,$save_path);
                break;
            case 3://png
                // header('content-type:image/png');
                 imagepng($dist_img,$save_path);
                break;
            default:
                return false;
        }
         imagedestroy($dist_img);
          imagedestroy($copy_img);
        return true;
    }

}
?>