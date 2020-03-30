<?php
/**
 * @copyright 2002-2013 www.huibo.com
 * @name   公司
 * @author momo
 * @version 2013-7-16
 */
class controller_hrcompany extends components_cbasepage {

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}

	public function pageadd($inPath){
		$company_service = new base_service_company_company();
		$hrinfo = base_service_company_resources_resources::getInstance($this->_userid,false);
		$this->_aParams['title'] = '待招客户添加';
		$this->_aParams['sub_count'] = count($hrinfo->accounts);
		$this->_aParams['max_count'] = intval($this->GetCompanyMaxCount($this->_userid));
		if(count($hrinfo->accounts)>=$this->_aParams['max_count']){
			return $this->render('hrmember/add.html',$this->_aParams);
		}

		$company_service = new base_service_company_company();
		$company_field = 'hr_manager,hr_manager_sex,hr_tel,show_email,linkman,linkman_sex,open_linkman,linkman_station,link_tel,'
						.'open_tel,fax,open_fax,link_mobile,open_mobile';
		$company = $company_service->getCompany($this->_userid,'1',$company_field);
		$this->_aParams['company'] = $company;

		//获取企业固定电话信息
		$this->_aParams['phone_infor'] = $this->get_telephone_infor($company["link_tel"]);

		$service_reward = new base_service_common_reward();
		$all_reward_data = $service_reward->getAll();
		$this->_aParams["all_reward_data"] = $all_reward_data;
		
		//获得公司已有的福利
		$company_rewards = array(
							'company_reward_ids' => $company['company_reward_ids'],
							'company_other_reward' => $company['company_other_reward']
							);
		
		//公司默认福利
		$company_default_rewards = $company_rewards['company_reward_ids'];
		$this->_aParams["hidDefaultReward"] = $company_default_rewards;
		if (!base_lib_BaseUtils::nullOrEmpty($company_default_rewards)) {
			$company_default_rewards_arr = explode(',', $company_default_rewards);
		} else {
			$company_default_rewards_arr = array();
		}

		//公司其他福利
		$company_other_rewards = $company_rewards['company_other_reward'];
		$this->_aParams["hidOtherReward"] = $company_other_rewards;
		if (!base_lib_BaseUtils::nullOrEmpty($company_other_rewards)) {
			$company_other_rewards_arr = explode(',', $company_other_rewards);
		} else {
			$company_other_rewards_arr = array();
		}
		$this->_aParams['company_other_rewards']   = $company_other_rewards_arr;
		$this->_aParams['company_default_rewards'] = $company_default_rewards_arr;
        

		// 获取公司规模码表信息
		$service_comsize =  new base_service_load(new base_service_common_comsize());
		$comsizes = $service_comsize->getAll();
		$this->_aParams['comsizes']     = $comsizes;

        $properties_service = new base_service_load(new base_service_common_comproperty());
        $properties = $properties_service->getAll();
		$this->_aParams["properties"]       = $properties;
		// 读取配置xml文件
		$xml = SXML::load('../config/company/company.xml');
		if (!is_null($xml)) {
			$max_photo_count = $xml->PhotoMaxCount;
			$photo_virt_path = base_lib_Constant::UPLOAD_FILE_URL.'/'.$xml->VirtualName.'/'.$xml->PhotoFolder.'/'.$company['company_id'].'/';
			$photo_thumb_suffix = $xml->PhotoThumbSuffix;
			$singel_photo_count = $xml->PhotoSingelCount;
			//logo
			$logofolder     = $xml->LogoFolder;
			$logoTempfolder = $xml->LogoTempFolder;
			$virtualName    = $xml-> VirtualName;
		}
		$this->_aParams['logo'] = base_lib_Constant::STYLE_URL."/img/job/newjob/newJob_57.png";
		// $logo_virt_path =  base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.'/'.$virtualName.'/'.$logofolder.'/';
		$logo_virt_path =  base_lib_Constant::UPLOAD_FILE_URL . '/' . $virtualName . '/' . $logoTempfolder . '/';
        
        $this->_aParams['alr_photo_count'] = 0;
        $this->_aParams['singel_photo_count'] = $singel_photo_count;
        $this->_aParams['photo_free'] = $max_photo_count;
        $this->_aParams['max_photo_count'] = $max_photo_count;
        $this->_aParams['photo_virt_path'] = $photo_virt_path;
        $this->_aParams['logo_virt_path'] = $logo_virt_path;

        
		$this->_aParams['upload_cookie_userid']   = $this->_userid;
		$this->_aParams['upload_cookie_nickname'] = $this->_username;
		$this->_aParams['upload_cookie_usertype'] = $this->_usertype;
		$this->_aParams['upload_cookie_userkey']  = base_lib_BaseUtils::getCookie('userkey');
		$this->_aParams['upload_cookie_tick']     = base_lib_BaseUtils::getCookie('tick');
        $this->_aParams["upload_cookie_accountid"] = base_lib_BaseUtils::getCookie('accountid'); 
		return $this->render('hrmember/add.html',$this->_aParams);
	}

	function pagemod($inPath){
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$company_flag = base_lib_BaseUtils::getStr($pathdata['flag'],'string',null);

		$company_id = base_lib_Rewrite::getId('company',$company_flag);

		$company_service = new base_service_company_company();
		$hrinfo = base_service_company_resources_resources::getInstance($this->_userid,false);
		$sub_account = $hrinfo->accounts;
		
		if(!$company_id || !in_array($company_id,$sub_account)){
			return $this->render('../config/404.html');
		}

		$this->_aParams['sub_count'] = count($sub_account);
		$this->_aParams['max_count'] = intval($this->GetCompanyMaxCount($this->_userid));
        $left_count = intval($this->_aParams['max_count']-count($sub_account));
        $left_count = ($left_count <=0)?0:$left_count;
        $this->_aParams['left_count'] = $left_count;
		$company_field = 'company_id,company_flag,company_name,property_id,size_id,area_id,calling_ids,address,postcode,homepage,info,'
						.'hr_manager,hr_manager_sex,hr_tel,show_email,linkman,linkman_sex,open_linkman,linkman_station,link_tel,'
						.'open_tel,fax,open_fax,link_mobile,open_mobile,company_logo_path,company_video_path,company_video_name,'
						.'email,company_bright_spot,company_shortname,company_photo_count,company_reward_ids,company_other_reward';
		$company = $company_service->getCompany($company_id,'1',$company_field);
		$this->_aParams['company'] = $company;
		$xml = SXML::load('../config/company/company.xml');
		if (!is_null($xml)) {
			$VirtualName = $xml->VirtualName;//虚拟目录名 CompanyImages
			$LogoFolder  = $xml->LogoFolder;// <!--logo文件夹名-->
		}
		if (base_lib_BaseUtils::nullOrEmpty($company['company_logo_path'])) {
			$this->_aParams['logo'] = base_lib_Constant::STYLE_URL."/img/job/newjob/newJob_57.png";
		} else {
			$this->_aParams['logo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.'/'.$VirtualName.'/'.$LogoFolder.'/'.$company['company_logo_path'];
		}

		//获取企业固定电话信息
		$this->_aParams['phone_infor'] = $this->get_telephone_infor($company["link_tel"]);
		//获取企业视频信息
		// $this->_aParams["video_infor"] = $this->get_video_infor($company["company_video_name"],$company["company_video_path"]);
        //获得企业行业数据
		$calling_service = new base_service_common_calling();
		$calling_ids = $company['calling_ids'];
		$calling_arr = array();
		if (!base_lib_BaseUtils::nullOrEmpty($calling_ids)) {
			$calling_arr = explode(",", $calling_ids);
			if (count($calling_arr) > 2) {
				$calling_arr = array_slice($calling_arr, 0, 2);
			}
		}

		$calling_names = array();
		$this->_aParams['calling_arr'] = $calling_arr;
		if (!base_lib_BaseUtils::nullOrEmpty($calling_arr[0])) {
		   //获得主行业名称
		   $calling_names[0] = $calling_service->getCallingName($calling_arr[0]);
		}
		if (!base_lib_BaseUtils::nullOrEmpty($calling_arr[1])) {
		   //获得主行业名称
		   $calling_names[1] = $calling_service->getCallingName($calling_arr[1]);
		}
        $this->_aParams['calling_names'] = $calling_names;
		
		// 获取信息完善度
		$companyInfoPercent = 0;
		$no_complete = $company_service->getComPercentAndNoComplete($this->_userid, false, $companyInfoPercent);
		$this->_aParams['precent']     = $companyInfoPercent.'%';
		$this->_aParams['no_complete'] = $no_complete;

		// 获取公司性质码表信息
		$properties_service = new base_service_load(new base_service_common_comproperty());
		$properties = $properties_service->getAll();
		$company_property = $properties_service->getComproperty($company["property_id"]);
		$this->_aParams["properties"]       = $properties;
		$this->_aParams["company_property"] = $company_property;

		// 获取公司规模码表信息
		$service_comsize =  new base_service_load(new base_service_common_comsize());
		$comsizes = $service_comsize->getAll();
		$company_size = $service_comsize->getComsize($company["size_id"]);
		$this->_aParams['comsizes']     = $comsizes;
		$this->_aParams["company_size"] = $company_size;
		//$company_flag = base_lib_Rewrite::getFlag('company', $company['company_id']);
		
		$service_reward = new base_service_common_reward();
		$all_reward_data = $service_reward->getAll();
		$this->_aParams["all_reward_data"] = $all_reward_data;
		
		//获得公司已有的福利
		// $company_service = new base_service_company_company();
		// $company_rewards = $company_service->getCompany($this->_userid,'1','company_reward_ids,company_other_reward');
		$company_rewards = array(
							'company_reward_ids' => $company['company_reward_ids'],
							'company_other_reward' => $company['company_other_reward']
							);//$company_service->getCompany($this->_userid,'1','company_reward_ids,company_other_reward');
		
		//公司默认福利
		$company_default_rewards = $company_rewards['company_reward_ids'];
		$this->_aParams["hidDefaultReward"] = $company_default_rewards;
		if (!base_lib_BaseUtils::nullOrEmpty($company_default_rewards)) {
			$company_default_rewards_arr = explode(',', $company_default_rewards);
		} else {
			$company_default_rewards_arr = array();
		}

		//公司其他福利
		$company_other_rewards = $company_rewards['company_other_reward'];
		$this->_aParams["hidOtherReward"] = $company_other_rewards;
		if (!base_lib_BaseUtils::nullOrEmpty($company_other_rewards)) {
			$company_other_rewards_arr = explode(',', $company_other_rewards);
		} else {
			$company_other_rewards_arr = array();
		}
		$this->_aParams['company_other_rewards']   = $company_other_rewards_arr;
		$this->_aParams['company_default_rewards'] = $company_default_rewards_arr;
                 
		// 读取配置xml文件
		$xml = SXML::load('../config/company/company.xml');
		if (!is_null($xml)) {
			$max_photo_count = $xml->PhotoMaxCount;
			$photo_virt_path = base_lib_Constant::UPLOAD_FILE_URL.'/'.$xml->VirtualName.'/'.$xml->PhotoFolder.'/'.$company['company_id'].'/';
			$photo_thumb_suffix = $xml->PhotoThumbSuffix;
			$singel_photo_count = $xml->PhotoSingelCount;
			//logo
			$logofolder     = $xml->LogoFolder;
			$logoTempfolder = $xml->LogoTempFolder;
			$virtualName    = $xml-> VirtualName;
		}
	
		$companyphotoalbum_items = array();
        if($company['company_photo_count']>0){
            $service_companyphotoalbum = new base_service_company_companyphotoalbum();
            $companyphotoalbums = $service_companyphotoalbum->getPhotoAlbumList($company_id,'photo_id,photo_name,photo_path');
            $companyphotoalbum_items = $companyphotoalbums->items;
            for ($i = 0; $i < count($companyphotoalbum_items); $i++) {
                    $fileParts = pathinfo($companyphotoalbum_items[$i]['photo_path']);
                    $companyphotoalbum_items[$i]['photo_path'] = $fileParts['filename'].$photo_thumb_suffix.'.'.$fileParts['extension'];
            }
        }

        // $logo_virt_path =  base_lib_Constant::YUN_ASSETS_URL.'/'.$virtualName.'/'.$logofolder.'/';
        $logo_virt_path =  base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $virtualName . '/' . $logoTempfolder . '/';
        //已经上传
        $alr_photo_count = count($companyphotoalbum_items);
        //剩余上传
        $rem_photo_count = $max_photo_count - $alr_photo_count;
        $this->_aParams['photo_albums'] = $companyphotoalbum_items;
        $this->_aParams['alr_photo_count'] = count($companyphotoalbum_items);
        $this->_aParams['singel_photo_count'] = $singel_photo_count;
        $this->_aParams['photo_free'] = $rem_photo_count;
        $this->_aParams['max_photo_count'] = $max_photo_count;
        $this->_aParams['photo_virt_path'] = $photo_virt_path;
        $this->_aParams['logo_virt_path'] = $logo_virt_path;

		$this->_aParams['title'] = '代招客户资料修改';
        
		$this->_aParams['upload_cookie_userid']   = $this->_userid;
		$this->_aParams['upload_cookie_nickname'] = $this->_username;
		$this->_aParams['upload_cookie_usertype'] = $this->_usertype;
		$this->_aParams['upload_cookie_userkey']  = base_lib_BaseUtils::getCookie('userkey');
		$this->_aParams['upload_cookie_tick']     = base_lib_BaseUtils::getCookie('tick');
        $this->_aParams["upload_cookie_accountid"] = base_lib_BaseUtils::getCookie('accountid'); 
		// var_dump($this->_aParams);
		return $this->render('hrmember/mod.html',$this->_aParams);
	}

	private function getCompanyPhotos($company_id) {
        if(empty($company_id)) return false;
        $xml = SXML::load('../config/company/company.xml');
        if(!is_null($xml)){
            $max_photo_count = $xml->PhotoMaxCount;
            $photo_virt_path = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.'/'.$xml->VirtualName.'/'.$xml->PhotoFolder.'/'.$company_id.'/';
            $photo_thumb_suffix = $xml->PhotoThumbSuffix;
            $singel_photo_count = $xml->PhotoSingelCount;
            //logo
            $logofolder = $xml->LogoFolder;
            $virtualName = $xml-> VirtualName;
        }

        // 获取公司相册信息
        $companyphotoalbum_items = array();
        if($company['company_photo_count']>0){
            $service_companyphotoalbum = new base_service_company_companyphotoalbum();
            $companyphotoalbums = $service_companyphotoalbum->getPhotoAlbumList($company_id,'photo_id,photo_name,photo_path');
            $companyphotoalbum_items = $companyphotoalbums->items;
            for ($i = 0; $i < count($companyphotoalbum_items); $i++) {
                    $fileParts = pathinfo($companyphotoalbum_items[$i]['photo_path']);
                    $companyphotoalbum_items[$i]['photo_path'] = $fileParts['filename'].$photo_thumb_suffix.'.'.$fileParts['extension'];
            }
        }

        $logo_virt_path =  base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.'/'.$virtualName.'/'.$logofolder.'/';
        //已经上传
        $alr_photo_count = count($companyphotoalbum_items);
        //剩余上传
        $rem_photo_count = $max_photo_count - $alr_photo_count;
// var_dump($company_id,$companyphotoalbum_items);die;
        $this->_aParams['title'] = '企业资料修改';
        $this->_aParams['photo_albums'] = $companyphotoalbum_items;
        $this->_aParams['alr_photo_count'] = count($companyphotoalbum_items);
        $this->_aParams['singel_photo_count'] = $singel_photo_count;
        $this->_aParams['photo_free'] = $rem_photo_count;
        $this->_aParams['max_photo_count'] = $max_photo_count;
        $this->_aParams['photo_virt_path'] = $photo_virt_path;
        $this->_aParams['logo_virt_path'] = $logo_virt_path;
        $this->_aParams['title'] = "企业环境修改";
    }

	/**
	 *
	 * 保存添加企业资料
	 * @param object $inPath 参数信息
	 */
	 public function pageAddBasicInfo($inPath) {
 	 	$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));	 	
	 	$base_infor = $this->get_company_base_infor_from_post($pathdata);
	 	$validata = $this->validate_company_base_info($base_infor,'add');
	 	if($validata->has_err){
	 		echo $validata->toJsonWithHtml();
	 		return;
	 	}
	 	$base_infor = $this->addExtrInfo($base_infor);
	 	$company_id = $this->add_company_base_info($base_infor);
	 	/*============== 将新添加的虚拟账户添加到审核 info_hr_company_audit=====*/
	 		
	 	if(false !== $company_id){
	 		$adult_service = new base_service_company_hrcompanyaudit();
			// $adult_status = new base_service_common_hrcompanystatus();
			$items['company_id'] = $company_id;
			$items['proxy_com_id'] = $this->_userid;
			$items['create_time'] = date('Y-m-d H:i:s');
			$items['status'] = 0;//$adult_status->checking;
			$adult_service->addHrAudit($items);
	 	}


	 	if(($company_id!==false) && ($photo = $this->SavePhoto($company_id,$pathdata))){
	 		if(!empty($base_infor['logo'])){
	 			//审核logo
	 			$service_logoaudit = new base_service_company_logoaudit();
				$service_logoaudit->addCompanyLogoAudit($company_id,0);//将logo 设置为未审核

	 		}
	 		//简称审核
			$service_shortname_audit = new base_service_company_shortnameaudit();
			$service_shortname_audit->addCompanyAudit($company_id,$base_infor['hidCompanyShortName'],0);
			
			//主营业务审核审核
			$service_brightspot = new base_service_company_brightspot();
			$service_brightspot->addCompanyAudit($company_id,0);
	 		
	 		$this->send_boss_qq_tip();

	 		echo json_encode(array("success"=>true,'photo'=>$photo));	 		
	 	}else{
	 		$validata->addErr("添加失败");
	 		echo $validata->toJsonWithHtml();
	 	}
	 	return;
	}

	/**
	 *
	 * 保存修改企业资料
	 * @param object $inPath 参数信息
	 */
	 public function pageModifyBasicInfo($inPath) {
 	 	$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

 	 	$company_flag = base_lib_BaseUtils::getStr($pathdata['company_flag'],'string',null);
 	 	$company_id = base_lib_BaseUtils::getStr($pathdata['company_id'],'int',0);
 	 	if(!$company_id){
 	 		$company_id = base_lib_Rewrite::getId('company',$company_falg);
 	 	}
		$hrinfo = base_service_company_resources_resources::getInstance($this->_userid,false);
		$sub_account = $hrinfo->accounts;
 	 	if(!$company_id || !in_array($company_id,$sub_account)){
 	 		$validata = new base_lib_Validator();
 	 		array_push($validata->err, '操作失败'.implode(',',$sub_account));
 	 		echo $validata->toJsonWithHtml();
	 		return;
 	 	}

	 	$base_infor = $this->get_company_base_infor_from_post($pathdata);
	 	$result = $this->validate_company_base_info($base_infor);
	 	if($result->has_err){
	 		echo $result->toJsonWithHtml();
	 		return;
	 	}
	 	$result->has_err = $this->save_company_base_info($company_id,$base_infor);
	 	if($result->has_err===false){
	 		$result->addErr("修改数据失败");
	 		echo $result->toJsonWithHtml();
	 		return;
	 	}
        $del_logo = base_lib_BaseUtils::getStr($pathdata['del_logo']);
        if($del_logo != $base_infor['logo']){
            $logo_name = array();
            // 读取配置xml文件
            $xml = SXML::load('../config/company/company.xml');
            if(!is_null($xml)){
                $logo_folder = $xml->LogoFolder;
                $logo_temp_folder = $xml->LogoTempFolder;
                $company_image_path = $xml->CompanyImagePath;
                $virtualName = $xml->VirtualName;
            }
            array_push($logo_name,$base_infor['logo']);
            //将LOGO移动到正式目录中
            $postvar['newfile'] = "{$virtualName}/{$logo_folder}";
            $postvar['oldfile'] = "{$virtualName}/{$logo_temp_folder}";
            $postvar['names'] = $logo_name;
            $postvar['thumbSuffix'] = '';
            $postvar['authenticate'] = "logo";
            $result_move = base_lib_Uploadfilesv::moveFile($postvar);
            if(!$result_move){
                echo json_encode(array('error'=>"修改logo图片失败"));
                return;
            }
            $service_company = new base_service_company_company();
            $result = $service_company->delLogo($del_logo);
			$service_logoaudit = new base_service_company_logoaudit();
			$service_logoaudit->addCompanyLogoAudit($company_id,0);//将logo 设置为未审核
			
        }
        $photo = $this->SavePhoto($company_id,$pathdata);
	 	echo json_encode(array("success"=>true,'photo'=>$photo));
	 	return;
	}

	/**
	 *
	 * LOGO的临时保存
	 * @param object  $inPath
	 */
	 public function pageSaveTempLogo($inPath) {
	    $service_company =new base_service_company_company();
        $file = $_FILES['Filedata'];
        $arr = $service_company->saveTempLogo($this->_userid,$file);
        if($arr!==false) {
           echo json_encode($arr);
        }
	 }

	 /**
	  * 保存临时照片
	  * @param array $inPath
	  */
	 public function pageSaveTempPhoto($inPath){
	 	$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

	 	$service_company =new base_service_company_company();
       	$file = $_FILES['Filedata'];
       	$arr = $service_company->saveTempPhoto($this->_userid,$file);
       	if($arr!==false) {
          echo json_encode($arr);
       	}
	 }

	 /**
	  * 获取剩余上传数量
	  * @param array $inPath
	  */
	 public function pageGetFree($inPath){
	 	$max_photo_count = 20;
	 	$xml = SXML::load('../config/company/company.xml');
		if(!is_null($xml)){
			$max_photo_count = $xml->PhotoMaxCount;
		}
		$service_companyphoto= new base_service_company_companyphotoalbum();
	 	$alr_photo_count = $service_companyphoto->getPhotoCount($this->_userid);
		$photo_free = $max_photo_count - $alr_photo_count;
 		echo json_encode(array('photo_free'=>$photo_free));
 		return;
	 }

	 /**
	  * 删除临时照片（废弃）
	  * @param array $inPath
	  */
	 public function pageDelTempPhoto($inPath){
	 	$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
	 	$file_path = base_lib_BaseUtils::getStr($pathdata['file_path'],'string','');
	 	if($file_path==''){
	 		echo json_encode(array('error'=>'获取参数失败'));
	 		return;
	 	}
	 	$this->__delPhotoFile($this->_userid,$file_path);
	 	echo json_encode(array('success'=>true));
	 	return;
	 }

	 public function pageShowVideoInfo($inPath){
	 	return $this->render("getVd.html", $this->_aParams);
	 }

	 /**
	  * 删除照片（删除数据库及文件）
	  * @param int $photo_id
	  */
	 private function __delPhotoData($photo_id,$company_id){
	 	$service_companyphoto= new base_service_company_companyphotoalbum();
	 	$company_photo = $service_companyphoto->getPhotoAlbum($photo_id);
	 	if(base_lib_BaseUtils::nullOrEmpty($company_photo)){
	 		return;
	 	}
	 	$result = $service_companyphoto->delPhotoAlbum($photo_id, $company_id);
	 	if($result){
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
	 private function __delPhotoFile($company_id,$file_path){
	 	$service_company =new base_service_company_company();
	 	$service_company->delPhoto($company_id,$file_path);
	 }

	 /**
	  *
	  *  保存相册与视频信息
	  *  @param array  $inPath 页面参数信息
	  */
	 private function SavePhoto($company_id,$pathdata) {
	 	
	 	$service_companyphoto = new base_service_company_companyphotoalbum();
	 	$photo_url_arr = base_lib_BaseUtils::getStr($pathdata['hddNewPhotoName'],'array','');
	 	$photo_del_arr = base_lib_BaseUtils::getStr($pathdata['hddDelPhotoID'],'array','');
	 	$photo_mod_arr = base_lib_BaseUtils::getStr($pathdata['hddModPhotoID'],'array','');

	 	//删除文件
	 	if(is_array($photo_del_arr)&&$photo_del_arr!=''){
	 		for ($j = 0; $j < count($photo_del_arr); $j++) {
	 			$photo_del_id = $photo_del_arr[$j];
	 			$this->__delPhotoData($photo_del_id, $company_id);
	 		}
	 	}

	 	//修改文件
	 	if(is_array($photo_mod_arr)&&$photo_mod_arr!=''){
	 		for ($j = 0; $j < count($photo_mod_arr); $j++) {
	 			$photo_mod_temp_arr= explode('|',$photo_mod_arr[$j]);
	 			$photo_mod_id = $photo_mod_temp_arr[0];
	 			$photo_mod_name = $photo_mod_temp_arr[1];
	 			$result = $service_companyphoto->getPhotoAlbum($photo_mod_id);
	 			if(!base_lib_BaseUtils::nullOrEmpty($result)){
	 				$service_companyphoto->modPhotoAlbum($photo_mod_id, $company_id, $photo_mod_name);
	 			}
	 		}
	 	}
		//$company_flag = base_lib_Rewrite::getFlag('company', $this->_userid);
	 	//保存文件
		if(is_array($photo_url_arr)){
			$photo_max_count = 20;
			$alr_photo_count = $service_companyphoto->getPhotoCount($company_id);
			$xml = SXML::load('../config/company/company.xml');
			if(!is_null($xml)){
				$photo_max_count = $xml->PhotoMaxCount;
				$photo_folder = $xml->PhotoFolder;
				$photo_thumb_suffix = $xml->PhotoThumbSuffix;
				$photo_temp_folder = $xml->PhotoTempFolder;
				$company_images_path = $xml->CompanyImagePath;
				$virtualName = $xml-> VirtualName;
			}
			//已经上传的，减去标记删除的，加上即将要上传的，如果大于最大数量，则提示错误
			if($alr_photo_count-count($photo_del_arr) + count($photo_url_arr) > $photo_max_count){
				return false;
			}

			$photo_names = array();
			for ($i = 0; $i < count($photo_url_arr); $i++) {
				$photo_arr = explode('|',$photo_url_arr[$i]);
				$photo_path = $photo_arr[0];
				$photo_name = $photo_arr[1];
				$item['photo_name'] = $photo_name;
				$item['photo_path'] = $photo_path;
				$item['company_id'] = $company_id;
				$service_companyphoto->addPhotoAlbum($item);
				array_push($photo_names, $item['photo_path']);
			}
			//移动文件到正式文件夹下
			$postvar['newfile'] = "{$virtualName}/{$photo_folder}".'/'.$company_id;
			$postvar['oldfile'] = "{$virtualName}/{$photo_temp_folder}".'/'.$this->_userid;
			$postvar['names'] = $photo_names;
			$postvar['thumbSuffix'] = $photo_thumb_suffix;
			$postvar['authenticate'] = "photo";
			$result_move = base_lib_Uploadfilesv::moveFile($postvar);

			if(!$result_move){
				return false;
			}else{
				//移动云存储文件到正式目录
				//上传七牛云存储
				$qiniu = new SQiniu();
				for($k=0;$k<count($photo_names);$k++){
					$qiniu->Move($postvar['oldfile']."/".$photo_names[$k], base_lib_Constant::QINIU_BUCKET, $postvar['newfile']."/".$photo_names[$k], base_lib_Constant::QINIU_BUCKET);
					//移动缩略图
					$qiniu->Move($postvar['oldfile']."/".str_replace('.',$photo_thumb_suffix.".",$photo_names[$k]), base_lib_Constant::QINIU_BUCKET, $postvar['newfile']."/".str_replace('.',$photo_thumb_suffix.".",$photo_names[$k]), base_lib_Constant::QINIU_BUCKET);
				}
			}
		}
 		$mien_infor = $this->get_mien_infor_from_post($pathdata);
		$result = $this->validate_mien_info($mien_infor);
		if($result->has_err){
			return false;
		}
 		$result->err = !$this->save_mien_infor($mien_infor);
 		if($result->has_err){
	 		return false;
 		}
 		$company_photos = $service_companyphoto->getPhotoAlbumList($company_id, 'photo_id,photo_name,photo_path');
 		$company_photo_items = $company_photos->items;
 		$alr_photo_count = count($company_photo_items);
 		for ($j2 = 0; $j2 < count($company_photo_items); $j2++) {
 			$fileParts = pathinfo($company_photo_items[$j2]['photo_path']);
			$company_photo_items[$j2]['photo_thumb_path'] = $fileParts['filename'].$photo_thumb_suffix.'.'.$fileParts['extension'];
 		}
 		
 		//更新企业冗余字段  photo_count
 		$service_company = new base_service_company_company();
 		$service_company->saveCompanyPhotoCount($company_id,$alr_photo_count);

 		return true;
	 }

	private function validate_company_base_info($base_infor,$operate='edit'){
		$validator = new base_lib_Validator();
		$properties_service = new base_service_load(new base_service_common_comproperty());
		$properties = $properties_service->getAll();
		if(!isset($properties[$base_infor["property_id"]])){
			$validator->addErr("请选择正确的公司性质");
		}
		$size_service = new base_service_load(new base_service_common_comsize());
		$sizes = $size_service->getAll();
		if(!isset($sizes[$base_infor["size_id"]])){
			$validator->addErr("请选择正确的公司规模");
		}
        $validator->getNotNull($base_infor["main_calling"],"请选择公司所属行业");
		$validator->getNotNull($base_infor["info"],"请输入公司简介");
		$validator->getStr($base_infor["info"],2,4000,"公司简介请输入2-4000个字");
		
		if($operate != 'edit'){
        	$validator->getStr($base_infor["hidCompanyShortName"],2,15,"公司简称请输入2-15个字");
        	$validator->getStr($base_infor["company_name"],2,30,"公司全称请输入2-30个字");
		}

        $validator->getStr($base_infor["hidRightSpot"],5,50,"公司亮点请输入5-50个字");
        $validator->getStr($base_infor["info"],2,4000,"公司简介请输入2-4000个字");
		$validator->getNotNull($base_infor["linkman"],"请输入联系人姓名");
		$validator->getStr($base_infor["linkman"],1,15,"联系人姓名不能超过15字");
		$validator->getNotNull($base_infor["link_tel"],"请输入座机号码");
		$validator->getTel($base_infor["link_tel"],"请输入正确的座机号码");
		if($base_infor["email"]!="")
			$validator->getEmail($base_infor["email"],"请输入正确的邮箱地址");
		if($base_infor["fax"]!="")
			$validator->getTel($base_infor["fax"],"请输入正确的传真号码");
		if($base_infor["link_mobile"]!="")
			$validator->getTel($base_infor["link_mobile"],"请输入正确的手机号码");

		return $validator;
	}

	private function add_company_base_info($base_infor){
		$service_company = new base_service_company_company();
		
		$company["property_id"] = $base_infor["property_id"];
        $calling_ids = $base_infor['main_calling'];
        if(!base_lib_BaseUtils::nullOrEmpty($base_infor['next_calling'])){
            $calling_ids = $calling_ids.",".$base_infor['next_calling'];
        }
		$company["calling_ids"] = $calling_ids;
		
        $company["is_proxyed"] 	= $base_infor["is_proxyed"];
        $company["proxy_com_id"] = $base_infor["proxy_com_id"];
        $company["size_id"] 	= $base_infor["size_id"];
		$company["info"] 		= $base_infor["info"];
		$company["linkman"] 	= $base_infor["linkman"];
		$company["fax"] 		= $base_infor["fax"];
		$company["homepage"] 	= $base_infor["homepage"];
		$company["link_mobile"] = $base_infor["link_mobile"];
		$company["email"] 		= $base_infor["email"];
		$company["open_linkman"]= $base_infor["open_linkman"];
		$company["open_mobile"] = $base_infor["open_mobile"];
		$company["show_email"] 	= $base_infor["show_email"];
		$company["open_tel"] 	= $base_infor["open_tel"];
		$company["link_tel"]	= $base_infor["link_tel"];
		$company['com_level']	= 1;
		$company["company_logo_path"] = $base_infor["logo"];
        $company["company_reward_ids"] = $base_infor["company_reward_ids"];
        $company["company_other_reward"] = $base_infor["company_other_reward"];

        $company["address"] 	= $base_infor["address"];
		$company["area_id"] 	= $base_infor["area_id"];
		
		//企业简称		
        $company["company_shortname"] = $base_infor["hidCompanyShortName"];
        $company["company_name"] = $base_infor["company_name"];
        $company['is_audit'] = $base_infor['is_audit'];
		
		//企业主营业务		
        $company["company_bright_spot"] = $base_infor["hidRightSpot"];
        $company_id = false;
        // var_dump($company);die;
        $service_company->addCompany($company,$company_id);
		return $company_id;
	}

	private function save_company_base_info($company_id,$base_infor){
		if(empty($company_id)) return false;

		$service_company = new base_service_company_company();
		$company = $service_company->getCompany($company_id,'1','company_id,company_name,property_id,size_id,area_id,calling_ids,address,postcode,homepage,info,hr_manager,hr_manager_sex,hr_tel,email,show_email,linkman,linkman_sex,open_linkman,linkman_station,link_tel,open_tel,fax,open_fax,link_mobile,open_mobile,company_logo_path,company_video_path,company_video_name,company_reward_ids,company_other_reward,company_bright_spot,company_shortname');
		$company["property_id"] = $base_infor["property_id"];
        $calling_ids = $base_infor['main_calling'];
        if(!base_lib_BaseUtils::nullOrEmpty($base_infor['next_calling'])){
            $calling_ids = $calling_ids.",".$base_infor['next_calling'];
        }
		$company["calling_ids"] = $calling_ids;
		
        $company["size_id"] 	= $base_infor["size_id"];
		$company["info"] 		= $base_infor["info"];
		$company["linkman"] 	= $base_infor["linkman"];
		$company["fax"] 		= $base_infor["fax"];
		$company["homepage"] 	= $base_infor["homepage"];
		$company["link_mobile"] = $base_infor["link_mobile"];
		$company["email"] 		= $base_infor["email"];
		$company["open_linkman"]= $base_infor["open_linkman"];

		$company["open_mobile"] = $base_infor["open_mobile"];
		$company["show_email"] 	= $base_infor["show_email"];
		$company["open_tel"] 	= $base_infor["open_tel"];

		$company["address"] 	= $base_infor["address"];
		$company["area_id"] 	= $base_infor["area_id"];

		$company["link_tel"]	= $base_infor["link_tel"];
        	
        $company["company_reward_ids"] = $base_infor["company_reward_ids"];
        $company["company_other_reward"] = $base_infor["company_other_reward"];

        //不在修改企业名称以及简称 2015-12-03 14:56
		// $company["company_name"] = $base_infor["company_name"];			
		// if($company["company_shortname"] != $base_infor['hidCompanyShortName']){
		// 	//修改审核状态
		// 	$service_shortname_audit = new base_service_company_shortnameaudit();
		// 	$service_shortname_audit->addCompanyAudit($company_id,0);
		// 	// $this->send_boss_qq_tip('ALL');
		// }
		
        //$company["company_shortname"] = $base_infor["hidCompanyShortName"];
		
		//判断是否修改企业主营业务
		if($company['company_bright_spot'] !=$base_infor['hidRightSpot']){
			//修改审核状态
			$service_brightspot = new base_service_company_brightspot();
			$service_brightspot->addCompanyAudit($company_id,0);
		}
		$company["company_bright_spot"] = $base_infor["hidRightSpot"];
		
		//判断是否修改企业logo
		if($company['company_logo_path'] !=$base_infor['logo'] && !empty($base_infor['logo'])){
			//修改审核状态
			$service_logo = new base_service_company_logoaudit();
			$service_logo->addCompanyLogoAudit($company_id,0);
		}
		$company["company_logo_path"] = $base_infor["logo"];

		//保存公司所处行业的公司行业表
		//$companyCallingService = new base_service_company_companycalling();
		//$companyCallingService->delelteCompanyCalling($this->_userid);
		//$companyCallingService->addCompanyCalling($this->_userid, $company["calling_ids"]);
		// var_dump($company);die;
		return $service_company->updateCompany($company,explode(',', $company["calling_ids"]));
	}

	private function get_company_base_infor_from_post($post){
		$base_infor = array();
		$base_infor["property_id"] 				= $this->get_post_value($post["company_property"]);
        $base_infor["main_calling"] 			= $this->get_post_value($post["main_calling"]);
		$base_infor["size_id"]					= $this->get_post_value($post["company_size"]);
		$base_infor["info"]						= urldecode(urldecode($this->get_post_value($post["company_info"])));
		$base_infor["linkman"]					= urldecode(urldecode($this->get_post_value($post["linkman"])));
		$base_infor["fax"]						= $this->get_post_value($post["fax"]);
		$base_infor["homepage"]					= base_lib_BaseUtils::getStr($post["homepage"],'');
		$base_infor["link_mobile"]				= $this->get_post_value($post["link_mobile"]);
		$base_infor["email"]					= $this->get_post_value($post["email"]);
		$base_infor["open_linkman"]				= $this->get_post_value($post["open_linkman"]);
		$base_infor["open_mobile"]				= $this->get_post_value($post["open_mobile"]);
		$base_infor["show_email"]				= $this->get_post_value($post["show_email"]);
		$base_infor["open_tel"]					= $this->get_post_value($post["open_tel"]);
		$base_infor["phone_infor"]				= $this->get_post_value($post["phone_infor"]);
		$base_infor["zone_infor"]				= urldecode(urldecode($this->get_post_value($post["zone_infor"])));
		$base_infor["ext_infor"]				= urldecode(urldecode($this->get_post_value($post["ext_infor"])));
		$base_infor["logo"]						= $this->get_post_value($post["logo"]);
		$base_infor["link_tel"]					= $this->collect_telephone_number($base_infor["zone_infor"],$base_infor["phone_infor"],$base_infor["ext_infor"]);
        $base_infor["company_reward_ids"]		= $this->get_post_value($post["hidDefaultReward"]);
        $base_infor["company_other_reward"]		= $this->get_post_value($post["hidOtherReward"]);
        $base_infor["hidCompanyShortName"]		= $this->get_post_value($post["hidCompanyShortName"]);
        $base_infor["hidRightSpot"]				= $this->get_post_value($post["hidRightSpot"]);

        $base_infor["area_id"]					= $this->get_post_value($post["hidArea"]);
        $base_infor["address"]					= $this->get_post_value($post["txtAddress"]);

		$base_infor["company_name"]				= $this->get_post_value($post["company_name"]);
        
		// var_dump($base_infor);die;
		return $base_infor;
	}

	private function get_post_value($value){
		return isset($value) ? base_lib_BaseUtils::getStr($value) : "";
	}

	private function get_telephone_infor($telephone){
		$telephone_infor = array('zone'=>'区号','phone'=>'固定电话','ext'=>'分机号');
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

    private function addExtrInfo($base_infor){
    	if(empty($base_infor) || !is_array($base_infor)) return false;
    	// var_dump(count($base_infor));
    	$base_infor['proxy_com_id'] = $this->_userid;
    	$base_infor['is_proxyed'] = base_service_company_resources_resources::HR_SUB_ACCOUNT;

    	$base_infor['is_audit'] = 1;  //直接把子账户的状态改为审核通过
    	// 3-未上传
		// 1-通过
		// 0不通过
		// 2审核中
    	// var_dump($base_infor);die;
    	return $base_infor;
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
    /**
      * @desc 地图弹窗
      * @param string $save_path  裁剪后保存图片地址
      */
    public function pageMapDialog($inpath){
         $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inpath));
        
        $this->_aParams['area_id'] = $pathdata['area_id'];
         $this->_aParams['mapX'] = $pathdata['mapX'];
          $this->_aParams['mapY'] = $pathdata['mapY'];
           $this->_aParams['mapZoom'] = $pathdata['mapZoom'];
        return $this->render('sysmanage/map.html',$this->_aParams);
    }
    
    /**
      * @desc 选择行业
      * @param string $calling_id
      */
    public function pageSelectCalling($inpath){
         $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inpath));
         $calling_id = base_lib_BaseUtils::getStr($pathdata['calling_id'],'string',NULL);
         $type = base_lib_BaseUtils::getStr($pathdata['type'],'int',0);
		 
         $calling_service = new base_service_common_calling();
         $top_callings = $calling_service->getTopCallings();
         if(!base_lib_BaseUtils::nullOrEmpty($top_callings)){
            foreach($top_callings as $k=>$top){
                 $parent_id = $top['calling_id'];
                 $subitem = $calling_service->getSubItem($parent_id);
                 $top_callings[$k]['subItem'] = $subitem;
            }
         }
         $this->_aParams['type'] = $type;
         $this->_aParams['calling_id'] = $calling_id;
         $this->_aParams['callings'] = $top_callings;
		 if($type==2){
			 return $this->render("./part/calling.html", $this->_aParams);
		 }else{
			return $this->render("calling.html", $this->_aParams);
		 }
    }

	// 获取代招客户的最大值
	private function GetCompanyMaxCount($companyid) {
		$comservice = new base_service_company_service_comservice();
		$myservice = $comservice->getComService($companyid,'proxy_company_num',null,2);
		return $myservice['proxy_company_num'];
	}


	//关联到企业QQ通知我们后台人员审核
	private function send_boss_qq_tip()
	{
		$content = "有hr会员的代招客户简称需要审核，请尽快处理";
		$userids = array();
		$xml = SXML::load('../config/company/company.xml');
		if (!is_null($xml)) {
			$users = $xml->HrAuditBossUsers;
			$userids = @explode(',',$users);
		}
		if(empty($userids)) return false;
		$userservice = new base_service_boss_user();      
		$sendResult = $userservice->sendMsg('newcompany_reg_audit', $userids, $content, $content, null);
	}
       
}
?>