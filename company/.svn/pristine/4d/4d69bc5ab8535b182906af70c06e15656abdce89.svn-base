<?php
/**
 * @ClassName file_name
 * @Desc 
 * @author Administrator
 * @date 2015年8月3日 下午1:30:32
 */
class controller_partjob extends components_cbasepage {

	//默认地区id
	private static $_areaidDefault = '0300';
	public function __construct() {
		parent::__construct(true, "part");
	}


	/*
	 * @Desc 兼职职位
	 */
	public function pageIndex($inPath){
        if(!$this->canDo("part_manage")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
		$service_partjobtype = new base_service_part_company_partjobtype();

		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$cur_page  = base_lib_BaseUtils::getStr($path_data['page'], 'int', 1);
		$page_size = base_lib_BaseUtils::getStr($path_data['pagesize'], 'int', base_lib_Constant::PAGE_SIZE);
			$status    = base_lib_BaseUtils::getStr($path_data['status'], 'string', $service_partjobtype->recruiting);

		$service_company     = new base_service_company_company();
		$service_partcompany = new base_service_part_company_partcompany();
		$service_area        = new base_service_common_area();
        $service_comstate = new base_service_company_comstate();
        $is_open_partjob = $service_comstate->getCompanyState($this->_userid,'is_open_partjob')['is_open_partjob'];
        $this->_aParams['is_open_partjob'] = $is_open_partjob;
        if($is_open_partjob!=1){
            return $this->render('part/job/jobadd.html', $this->_aParams);
        }
		$this->_aParams['has_set_agency'] = true;

		//薪资单位
		$service_unit = new base_service_part_job_salaryunit();
		$this->_aParams['units'] = $service_unit->getAll();

		//薪资类型
		$service_type = new base_service_part_job_salarytype();
		$this->_aParams['salarytypes'] = $service_type->getAll();

		// 职位列表
		$service_partjob = new base_service_part_company_job();
		$joblist = $service_partjob->getPartjobPageList($cur_page, $page_size, $status, $this->_userid);
		if (!empty($joblist)) {
			if ($joblist->totalSize > $page_size) {//只有一页的情况下不显示分页
				//分页
				$pager = $this->pageBar($joblist->totalSize, $page_size, $cur_page, $inPath);
			}

			$this->_aParams['pager'] = $pager;
			$joblist = $joblist->items;
			for ($i = 0; $i < sizeof($joblist); $i++) {
				// 职位链接,有效期,工作地
				$joblist[$i]['effect_time'] = date('Y-m-d', strtotime($joblist[$i]['effect_time']));
				$joblist[$i]['job_link']    = base_lib_Rewrite::partjobv2($joblist[$i]['job_id']);
				
				//是否职位即将过期
				$joblist[$i]['expire_tips'] = base_lib_TimeUtil::time_diff_day(date('Y-m-d'), $joblist[$i]['effect_time']) <= 3;
			}
		}
;
		$this->_aParams['status']  = $service_partjobtype->getPartjobStatus();
		$this->_aParams['curr']    = $status;
		$this->_aParams['joblist'] = $joblist;


        switch ($status) {
            case $service_partjobtype->recruiting:
                $this->_aParams['title'] = '正在招聘 兼职职位_汇博人才网';
                return $this->render('part/job/jobrecruiting.html', $this->_aParams);

            case $service_partjobtype->in_audit:
                $this->_aParams['title'] = '正在审核 兼职职位_汇博人才网';
                return $this->render('part/job/jobinaudit.html', $this->_aParams);

            case $service_partjobtype->expired:
                $this->_aParams['title'] = '已过期 兼职职位_汇博人才网';
                return $this->render('part/job/jobexpired.html', $this->_aParams);

            case $service_partjobtype->auditfail:
                $this->_aParams['title'] = '审核未通过 兼职职位_汇博人才网';
                return $this->render('part/job/jobauditfail.html', $this->_aParams);
        }


	}

	public function pageresources() {

		$company_resources =  base_service_company_resources_resources::getInstance($this->_userid,true,base_lib_BaseUtils::getCookie('accountid'));

		$company_resources_info = $company_resources->getCompanyServiceSource(['account_resource']);
		var_dump($company_resources_info);

	}
	
	/**
	 * @Desc 兼职职位发布入口
	 */
	public function pageJob() {

		$this->redirect('/partjob/jobV2');
	}

	/**
	 * 新版兼职职位发布          2018-7-27
	 * @Desc 兼职职位发布入口
	 */
	public function pageJobV2($inPath) {
		if(!$this->canDo("part_manage")){
			$this->_aParams["msg"] = "无权限访问，没有开通相应权限";
			$this->_aParams["url"] = "/";
			return $this->render("./common/showmsgpopedom.html", $this->_aParams);
		}
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$service_company     = new base_service_company_company();
		$service_partcompany = new base_service_part_company_partcompany();
        $service_partjob 							= new base_service_part_company_job();
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', 0);
		$huibo_job_id = base_lib_BaseUtils::getStr($path_data['huibo_job_id'], 'int', 0);
		$part_comapny = $service_partcompany->getCompanyPartInfo($this->_userid,"company_id,is_insure_fee,insure_fee,score,is_agency");
		//是否选择中介和直招
		$this->_aParams["is_agency"] =$part_comapny['is_agency'];
		if(empty($part_comapny['is_agency'])&&empty($huibo_job_id)){
			return $this->_returnFailPage('您还未选择中介或直招');
		}

		$company_items = "company_id,company_name,is_audit,audit_state,is_effect,company_flag,resume_download_upperlimit,com_level,start_time,end_time,effect_job_end_time,hr_manager,hr_manager_sex,hr_tel,company_logo_path,company_bright_spot,company_shortname,email,linkman,link_tel,calling_id,calling_ids,area_id,address,info,link_mobile";
		$company = $service_company->getCompany($this->_userid, 1, $company_items);

		$this->_aParams['company'] = $company;
		$this->_aParams['part_comapny'] = $part_comapny;
		$this->_aParams['title']   = '汇博人才网_发布职位';

		//企业是否通过执照审核
		$service_company_resources_resources = base_service_company_resources_resources::getInstance($this->_userid);
		$letter_info = $service_company_resources_resources->getCompanyAuditStatusV2();

		$is_audit = $letter_info['is_audit'];
		if ($is_audit != 1) {
			//未通过审核或审核失败，跳转到执照审核页面
			$this->_aParams['need_audit'] = true;
		}

		//企业兼职资料是否填写
		$companyNotUpdate = $this->_getCompanyNotUpdate($company);
		if (!base_lib_BaseUtils::nullOrEmpty($companyNotUpdate)) {
			//资料不完整跳转到上传资料页面
			$this->redirect('/partcompany/modify/flag-addjob');
		}


        $service_pay_job      = new base_service_part_company_payjobnum();
        $hasPubJob            = $service_partjob->getHasPubJobNum($this->_userid);//当前实际已经发布的
        $xml                  = SXML::load('../config/company/company.xml');
        $can_pub_pay_job_num  = $service_pay_job->getEffectNumTotal($this->_userid);//可发布的付费职位数
        $can_pub_free_job_num = (int)$xml->FreePartJobNum;//可发布的免费职位数
        if(!$job_id && ($can_pub_pay_job_num+$can_pub_free_job_num-$hasPubJob)<=0){
            $can_pub_num = $can_pub_pay_job_num+$can_pub_free_job_num;
            return $this->_returnFailPage("当前企业最多同时允许招聘{$can_pub_num}个职位，当前已经达到上限，无法再发布");
        }

        //是否达到允许的最大职位数
		/*$iscan = $this->_isCanSueJobV2($job_id);
		if (!$iscan) {
			return $this->_returnFailPage("每个企业最多同时允许招聘{$can_pub_pay_job_num}个职位，当前已经达到上限，无法再发布");
		}*/


		//企业信用积分情况
		$score = $this->_getCompanyScore($this->_userid);
		if ($score < 0) {
			return $this->_returnFailPage("由于你的信用积分低于0分，不能再发布职位，如有疑问请联系客服400-1010-970");
		}
		$service_partjobsort 						= new base_service_common_part_jobsort();
		$service_area        						= new base_service_common_area();
		$service_jobcheckstatus 					= new base_service_common_part_jobcheckstatus();
		$service_jobstatus      					= new base_service_common_part_partjobstatus();
		$service_part_job_partjobworktime 			= new base_service_part_job_partjobworktime();
		$service_part_job_partjobaddress 			= new base_service_part_job_partjobaddress();
		$service_part_job_partjobqualification 		= new base_service_part_job_partjobqualification();
		$service_part_job_partjobqualificationImg 	= new base_service_part_job_partjobqualificationImg();
		$service_common_part_partqualificationtype = new base_service_common_part_partqualificationtype();

		//图片上传插件
		$ser_upload = new base_service_upload_upload();
		$xml = SXML::load('../config/company/company.xml');
		if(!is_null($xml)){
			$credentials_folder 		= $xml->PartCredentials;
			$credentials_temp_folder 	= $xml->PartCredentialsTempFolder;
			$credentials_image_path 	= $xml->CompanyImagePath;
			$virtualName 				= $xml->VirtualName;
			$file_max_size 				= $xml->PartCredentialsFileMaxSize;
			$file_max_num 				= $xml->PartCredentialsFileMaxNum;
			$ext 						= $xml->PartCredentialsFileExtensions;
		}
		$path ="{$virtualName}/{$credentials_folder}";
		if($job_id){

			$thisdate = date('Y-m-d');
			$fields = 'job_id,company_id,jobsort_id,station,apply_count,sex,degree,salary,salary_unit,salary_type,status,'
				. 'area_id,address,map_x,map_y,position_require,need_invite,link_tel,link_part,link_way,account_id,create_time,valid_days,start_time,'
				. 'end_time,time_detail,is_effect,check_state,fee,fee_type,effect_time,long_recruit,is_need_workaddress,work_time,height,is_health,age,identity,link_weixin,link_qq,is_show_phone,is_show_weixin,is_show_qq,welfare,link_man,link_phone';
			$jobdata = $service_partjob->getJob($job_id, $fields);

			if (empty($jobdata) || $jobdata['company_id'] != $this->_userid) {
				return $this->_returnFailPage('您操作的职位不存在或已过期1');
			}
//			if ($jobdata['check_state'] == $service_jobcheckstatus->notpass
//				|| $jobdata['check_state'] == $service_jobcheckstatus->checking //审核中的职位也能重新发布修改（兼职二期补充）
//				|| $jobdata['status'] == $service_jobstatus->stop_use
//				|| ($jobdata['check_state'] == $service_jobcheckstatus->pass && $jobdata['effect_time'] < $thisdate)) {
//
//			} else {
//				return $this->_returnFailPage('您操作的职位不存在或已过期2');
//			}


			//获取职位地址信息
			$job_address_info = $service_part_job_partjobaddress->getPartJobAddressByJobId($job_id,"address_id,job_id,map_x,map_y,area_id,address_info");
			$job_worktime_info = $service_part_job_partjobworktime->getPartJobWorkTimeByJobId($job_id,"time_id,job_id,strat_time,end_time");

			//获取资质证明
			$job_qualification = $service_part_job_partjobqualification->getPartJobQualificationByJobId($job_id,"qualification_id,job_id,name,qualification_type");
			$job_qualification_img = $service_part_job_partjobqualificationImg->getPartJobQualificationImgByJobId($job_id,"img_id,job_id,company_id,qualification_id,path");
			foreach($job_qualification as $key=>&$value){
				$img_path = array();
				$img_str = '';
				foreach($job_qualification_img as $k=>$val){
					if($value['qualification_id'] == $val['qualification_id']){

						$val['img_path'] =  base_lib_Constant::UPLOAD_FILE_URL . '/' . $path . '/' .$val['path'];
						$img_path[] = $val;
						if($img_str){
							$img_str .= '|'.$val['path'];
						}else{
							$img_str = $val['path'];
						}
					}
				}

				$value['img_str'] = $value['name'].'|'.$value['qualification_type'].'|'.$img_str;
				$value['img_list'] = $img_path;
			}
			$jobdata['jobsort_name'] = $service_partjobsort->getJobsort($jobdata['jobsort_id']);
			$jobdata['area_name'] = $service_area->getAreaAndParentName($jobdata['area_id']);
			if(strtotime($jobdata['effect_time']) < time()){
				$jobdata['effect_time'] = date('Y-m-d');
			}else{
				$jobdata['effect_time'] = date('Y-m-d',strtotime($jobdata['effect_time']));
			}

			$defultWorkTime = "'".implode("', '",explode(', ',$jobdata['work_time']))."'";

			$this->_aParams['defultWorkTime'] = $defultWorkTime;
			$this->_aParams['job_info'] = $jobdata;
			$this->_aParams['address_info'] = $job_address_info;
			$this->_aParams['worktime_info'] = $job_worktime_info;

			$this->_aParams['job_qualification'] = $job_qualification;
		}else{
			//设置默认值
			$jobdata['salary_unit'] = 0;
			$jobdata['salary_type'] = 0;
			$jobdata['sex'] = 0;
			$jobdata['age'] = '0';
			$jobdata['identity'] = 0;
			$jobdata['height'] = 0;
			$jobdata['degree'] = 0;
			$jobdata['is_health'] = 0;
			$jobdata['effect_time'] = date('Y-m-d');
			$jobdata['work_time'] = '';
			$jobdata['is_show_phone'] = 1;
			$jobdata['long_recruit'] = 0;
			//查询汇博职位信息
			if($huibo_job_id){
				$base_service_job=new base_service_company_job_job();
				$reward_common   = new base_service_common_reward();
				$huibo_job = $base_service_job->getJob($huibo_job_id, "jobsort,station,quantity,area_id,map_x,map_y,address_id,add_info,create_time,issue_time,refresh_time,
			mod_time,min_salary,max_salary,content,other_reward,other_reward_ids,end_time,degree_id,age_lower,age_upper,other_need,open_linkway,self_linkway,linkman,link_tel,status");
				$reward = $reward_common->getSpecialRewards($huibo_job['other_reward_ids']);
				$huibo_job["rewards"]      = implode("、", (array)$reward);


				$jobdata['station'] =$huibo_job['station'];
				$jobdata['apply_count'] =$huibo_job['quantity'];
				$jobdata['area_id'] =$huibo_job['area_id'];
				$jobdata['area_name'] = $service_area->getAreaAndParentName($huibo_job['area_id']);
				$job_address_info[0]['address_info'] =$huibo_job['add_info'];
				$job_address_info[0]['area_id'] =$huibo_job['area_id'];
				$job_address_info[0]['map_x'] =$huibo_job['map_x'];
				$job_address_info[0]['map_y'] =$huibo_job['map_y'];
				$jobdata['degree'] =$huibo_job['degree_id'];
				$jobdata['welfare'] =$huibo_job['rewards'];
				$jobdata['position_require'] =$huibo_job['content'];
				$jobdata['age'] =$huibo_job['age_lower'];
				$this->_aParams['address_info'] = $job_address_info;
				$this->_aParams['huibo_job_id'] = $huibo_job_id;
			}
			$this->_aParams['defultWorkTime'] = '';
			$this->_aParams['job_info'] = $jobdata;
			$this->_aParams['job_qualification'] = array();
		}


		$service_degree      = new base_service_common_degree();

		$service_sex         = new base_service_common_sex();
		$service_height		 = new base_service_common_part_partheight();
		$service_age 		 = new base_service_common_part_partage();
		$service_time 		= new base_service_common_part_partTime();
		//薪资单位
		$service_unit = new base_service_part_job_salaryunit();
		$units = $service_unit->getAll();
		$this->_aParams['units'] = $this->_toJson($units);


		//薪资类型
		$service_type = new base_service_part_job_salarytype();
		$salarytypes = $service_type->getAll();
		$this->_aParams['salarytypes'] = $this->_toJson($salarytypes);

		//性别
		$sex = $service_sex->getSex();
		$this->_aParams['sex'] = $this->_toJson($sex);


		//学历
		$degree = $service_degree->getAll();
		$this->_aParams['degree'] = $this->_toJson($degree);

		//可选时间
		$this->_aParams['minTime'] = date('Y-m-d');
		$this->_aParams['maxTime'] = date('Y-m-d',strtotime('+30 days'));

		//身高
		$height = $service_height->getAllPartHeight();
		$this->_aParams['height'] = $this->_toJson($height);

		//年龄
		$age = $service_age->getAll();
		$this->_aParams['age'] = $this->_toJson($age);

		//工作时间
		$this->_aParams['worktime'] = $service_time->getAll();

		//职位类型
		$partjobsorts = $service_partjobsort->getJobsortAll();
		$this->_aParams['partjobsorts'] = $partjobsorts;

		$qualification = $service_common_part_partqualificationtype->getAll();
		$this->_aParams['qualification'] = $qualification;

		//工作地区
		$this->_aParams['area'] = $service_area->getOpenAreas();


		$up_options = array ('file_name' => 'hddNewPhotoName[]', 'fileVal' => 'Filedata','is_load_jquery'=>false, 'auto' => true,'path'=>$path,'file_max_size'=>$file_max_size,'ext'=>$ext,'photo_max_count'=>$file_max_num);
		$this->_aParams['up_img_html'] = $ser_upload->GetUploadHtmlDom($up_options, $this->_userid, 'up_style2', 'img', 'report', '../config/company/company.xml');
		$this->_aParams['expect_complete_time'] = date('Y-m-d');

		//公司子账户信息
		$service_company_account = new base_service_company_account();
		$company_account_info = $service_company_account->getAccountList($this->_userid,'account_id,is_main,user_id,user_name,mobile_phone,link_tel')->items;

		$this->_aParams['company_account_info'] = base_lib_BaseUtils::array_key_assoc($company_account_info,'account_id');

		//新职位发布默认为当前登录账户
		if(empty($this->_aParams['jobdata']['account_id']))
			$this->_aParams['jobdata']['account_id'] = base_lib_BaseUtils::getCookie('accountid');
		$service_comstate = new base_service_company_comstate();
		if($this->_userid){
			$this->_aParams['is_open_partjob'] = $service_comstate->getCompanyState($this->_userid,'is_open_partjob')['is_open_partjob'];
		}

		return $this->render('part/job/jobaddV2.html', $this->_aParams);
	}


	public function pageViolations($inPath){
		return $this->render('part/job/violations.html', $this->_aParams);
	}

	/*uploadify
 * 添加文件
 */
	public function pagepicture($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$up_type = base_lib_BaseUtils::getStr($pathdata['up_type'], 'string');
		$file = $_FILES['Filedata'];

		$verify_data = base_lib_BaseUtils::getStr($pathdata['verify_data'], 'array');
		$serv_askforleave = new base_service_upload_upload();
		$xml = SXML::load('../config/company/company.xml');
		if(!is_null($xml)){
			$credentials_folder 		= $xml->PartCredentials;
			$credentials_temp_folder 	= $xml->PartCredentialsTempFolder;
			$credentials_image_path 	= $xml->CompanyImagePath;
			$virtualName 				= $xml->VirtualName;
			$file_max_size 				= $xml->PartCredentialsFileMaxSize;
			$file_max_num 				= $xml->PartCredentialsFileMaxNum;
			$ext 						= $xml->PartCredentialsFileExtensions;
		}
		$in_data['path']			 	="{$virtualName}/{$credentials_temp_folder}";
		$in_data['file_max_size'] 		= $file_max_size;
		$in_data['ext'] 				= $ext;
		$in_data['photo_max_count'] 	= $file_max_num;
		$in_data['is_folder_date'] 		= false;
		$arr = $serv_askforleave->UploadFile($file, $verify_data, $up_type, 'company', '../config/company/company.xml',$in_data);
		if ($arr['status'] == false) {
			$this->ajax_data_json(ERROR, $arr['msg'], $arr);
		}
		if ($up_type == 'file') {
			$arr['newname_path'] = $arr['name'] . "|" . $arr['newname_path'];
		}
		$this->ajax_data_json(SUCCESS, "上传成功", $arr);
	}

	/**
	 * 删除临时照片（废弃）
	 * @param array $inPath
	 */
	public function pageDelTempFile($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$up_type = base_lib_BaseUtils::getStr($pathdata['up_type'], 'string');
		$verify_data = base_lib_BaseUtils::getStr($pathdata['verify_data'], 'array');
		$file = $_REQUEST['file_path'];
		$serv_askforleave = new base_service_upload_upload();
		$xml = SXML::load('../config/company/company.xml');
		if(!is_null($xml)){
			$credentials_folder 		= $xml->PartCredentials;
			$credentials_temp_folder 	= $xml->PartCredentialsTempFolder;
			$credentials_image_path 	= $xml->CompanyImagePath;
			$virtualName 				= $xml->VirtualName;
			$file_max_size 				= $xml->PartCredentialsFileMaxSize;
			$file_max_num 				= $xml->PartCredentialsFileMaxNum;
			$ext 						= $xml->PartCredentialsFileExtensions;
		}
		$in_data['path'] ="{$virtualName}/{$credentials_temp_folder}";
		$in_data['file_max_size'] = $xml->$file_max_size;
		$in_data['ext'] = $xml->$ext;
		$in_data['photo_max_count'] = $file_max_num;
		$arr = $serv_askforleave->DelFile($file, $verify_data, $up_type, 'company', '../config/company/company.xml',$in_data);
		if (@$arr['status'] == false) {
			$this->ajax_data_json(ERROR, $arr['msg'], $arr);
		}

		$this->ajax_data_json(SUCCESS, "删除成功", $arr);
	}

	/**
	 * 返回json数据
	 * @author chenbin
	 * @param int|string $code fail   success
	 * @param string $msg
	 * @param array| $data
	 */
	function ajax_data_json($code = ERROR, $msg = "操作失败", $data = array ()) {
		define('IS_AJAX', ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) ? true : false);
		$msg = $msg ? $msg : '操作失败';
		$code = $code ? $code : ERROR;
		if (!IS_AJAX) {
			$this->layer_msg_to_close($msg);
		}
		// 返回JSON数据格式到客户端 包含状态信息
		header('Content-Type:application/json; charset=utf-8');
		exit(json_encode(array ('code' => $code, 'msg' => $msg, 'data' => $data)));
	}
	function layer_msg_to_close($msg = "参数错误!") {
		$data['msg'] = $msg;

		die($this->render('public/msg_error.html', $data));
	}



	/**
	 * ajax获取发布职位对应的职位类别的提示语
	 * @param $inPath
	 * @return mixed|string
	 */
	public function pageGetJobsortMsg($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$jobsort_id = base_lib_BaseUtils::getStr($path_data["jobsort_id"],"string","");
		if(empty($jobsort_id)){
			return $this->jsonMsg(false,"请选择职位类别");
		}
		$service_company     = new base_service_company_company();
		$service_partjobsort = new base_service_common_part_jobsort();
		$service_partcompany = new base_service_part_company_partcompany();
		$hrlicencestatus = new base_service_common_hrlicencestatus();
		$service_part_company_availablejobsort = new base_service_part_company_availablejobsort();

		$service_company_resources_resources = base_service_company_resources_resources::getInstance($this->_userid);
		$letter_info = $service_company_resources_resources->getCompanyAuditStatusV2();

		$part_comapny = $service_partcompany->getCompanyPartInfo($this->_userid,"company_id,is_insure_fee,insure_fee,score,is_agency");
		//是否选择中介和直招
		if(empty($part_comapny['is_agency'])){
			return $this->jsonMsg(false,'您还未选择中介或直招');
		}

		$company_items = "company_id,company_name,is_audit,audit_state,is_effect,hrlicence";
		$company = $service_company->getCompany($this->_userid, 1, $company_items);

		$is_availabe = $service_part_company_availablejobsort->isJobsortAvailable($this->_userid,$jobsort_id);
		//该职位类别是绿色通道，不用提示信息
		if($is_availabe){
			return $this->jsonMsg(false,"");
		}

		$wordsJobsorts = $service_partjobsort->getWordsJobsorts();

		if($part_comapny['is_agency'] == 1){
			//中介	1.未上传人力资源许可证
			if(($company['hrlicence'] == $hrlicencestatus->noupload || $company['hrlicence'] == $hrlicencestatus->nopass) && in_array($jobsort_id,$wordsJobsorts)){
				return $this->jsonMsg(true,'上传《职业中介/人力资源服务许可证》和《代理招聘协议》证明，审核成功率更高哦.');
			}elseif(($company['hrlicence'] == $hrlicencestatus->noupload || $company['hrlicence'] == $hrlicencestatus->nopass) && !in_array($jobsort_id,$wordsJobsorts)){
                return $this->jsonMsg(true,'上传《职业中介/人力资源服务许可证》证明，审核成功率更高哦.');
            }else{

				if(in_array($jobsort_id,$wordsJobsorts)){
					return $this->jsonMsg(true,'上传《代理招聘协议》证明，审核成功率更高哦');
				}else{
					return $this->jsonMsg(false,'不知道提示啥');
				}
			}

		}else{
			//直聘
			$jobsort_all = $service_partjobsort->getAll();
			$jobsort_info = $jobsort_all[$jobsort_id];
			//企业是否通过执照审核
			$is_audit = $letter_info['is_audit'];
			if ($is_audit == 1) {
				if(isset($jobsort_info['words']) && !empty($jobsort_info['words'])){
					return $this->jsonMsg(true,$jobsort_info['words']);
				}else{
					return $this->jsonMsg(false,'');
				}
			}else{
				return $this->jsonMsg(true,"您还未上传营业执照");
			}
		}


	}

	public function pageSetJobAddress($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$info = base_lib_BaseUtils::getStr($path_data["info"],"string","");
		$is_edit = base_lib_BaseUtils::getStr($path_data["is_edit"],"int",0);
		$area_code = base_lib_BaseUtils::getStr($path_data["area_code"],"string",'');
		if($info){
			$area_info = explode('|',$info);
			$this->_aParams["map_x"] = $area_info[0];
			$this->_aParams["map_y"] = $area_info[1];
			$this->_aParams["area_id"] = $area_info[2];
			$this->_aParams["add_info"] = $area_info[3];

		}else{
			//$this->_aParams['area_id'] = $area_code;
		}
		$this->_aParams["is_edit"] = $is_edit;
		return $this->render('part/job/setjobaddress.html',$this->_aParams);
	}

	public function pageModJobAddress($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$add_info = base_lib_BaseUtils::getStr($path_data["add_info"],"string","");
		$map_x = base_lib_BaseUtils::getStr($path_data["map_x"],"string","");
		$map_y = base_lib_BaseUtils::getStr($path_data["map_y"],"string","");
		$area_id = base_lib_BaseUtils::getStr($path_data["area_id"],"string","");
		$id = base_lib_BaseUtils::getStr($path_data["id"],"int",-1);
		$company_id = base_lib_BaseUtils::getStr($path_data["company_id"],"int",0);

		if(empty($company_id)){
			return $this->jsonMsg(false,"企业数据异常");
		}
		$service_blue_bluecompanyaddress = new oa_service_blue_bluecompanyaddress();

		if($id > 0){
			//修改地址
			$data = array(
				'map_x'             => $map_x,
				'map_y'             => $map_y,
				'area_id'           => $area_id,
				'address_info'      => $add_info,
			);
			$result = $service_blue_bluecompanyaddress->updateAddressByid($id,$data);
			if($result){
				return $this->jsonMsg(true,"修改企业地址成功");
			}else{
				return $this->jsonMsg(false,"修改企业地址失败");
			}
		}else{
			//添加地址
			$data = array(
				'company_id'        => $company_id,
				'map_x'             => $map_x,
				'map_y'             => $map_y,
				'area_id'           => $area_id,
				'address_info'      => $add_info,
				'is_effect'         => 1
			);
			$result = $service_blue_bluecompanyaddress->insertCompanyAddress($data);
			if($result){
				return $this->jsonMsg(true,"添加企业地址成功");
			}else{
				return $this->jsonMsg(false,"添加企业地址失败");
			}
		}

	}


	/**
	 * @Desc 新增兼职职位
	 * @param type var
	 * @Return return_type
	 */
	public function pageAddJob($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', 0);

		$validator = new base_lib_Validator();
		$service_partjob        = new base_service_part_company_job();
		$service_degree         = new base_service_common_degree();
		$service_partjobsort    = new base_service_common_part_jobsort();
		$service_partjobstatus  = new base_service_common_part_partjobstatus();
		$service_jobcheckstatus = new base_service_common_part_jobcheckstatus();
        $service_comstate = new base_service_company_comstate();
		$service_company = new base_service_company_company();

		$current_company = $service_company->getCompany($this->_userid, 1, 'company_id,company_name,end_time,map_x,map_y,address');
		if (empty($current_company)) {
			echo json_encode(array('status'=>false,'msg'=>'对不起，您操作的公司不存在或已删除'));
			return;
		}
		//是否达到允许的最大职位数
		/*$iscan = $this->_isCanSueJobV2($job_id);
		if (!$iscan) {
			echo json_encode(array('status'=>false,'msg'=>'每个企业最多同时允许招聘10个职位，当前已经达到上限，无法再发布'));
			return;
		}*/

        $service_job = new base_service_part_company_job();
        $can_pub  = $service_job->PubJobNumLimit($job_id?2:1, $this->_userid, $job_id);
        if (!$can_pub) {
            echo json_encode(array('status'=>false,'msg'=>'对不起，你发布的职位数量已达上限，无法再发布'));
            return;
        }


		if($job_id){
			$thisdate = date('Y-m-d');
			$fields = 'job_id,company_id,jobsort_id,station,apply_count,sex,degree,salary,salary_unit,salary_type,effect_time,status'
				. ',area_id,address,map_x,map_y,position_require,need_invite,link_tel,create_time,valid_days,start_time,end_time,time_detail,is_effect,check_state';
			$jobdata = $service_partjob->getJob($job_id, $fields);
			if(empty($jobdata)){
				echo json_encode(array('status'=>false,'msg'=>'该职位不存在'));
				return;
			}
			if ($jobdata['company_id'] != $this->_userid) {
				echo json_encode(array('status'=>false,'msg'=>'您无权操作该职位'));
				return;
			}

//			if ($jobdata['check_state'] == $service_jobcheckstatus->checking || $jobdata['check_state'] == $service_jobcheckstatus->notpass || ($jobdata['status'] == $service_partjobstatus->use && $jobdata['effect_time'] > $thisdate)) {
//
//			} else {
//				echo json_encode(array('status'=>false,'msg'=>'您操作的职位还不能编辑'));
//				return;
//			}
			//修改职位
			//产品要求，已过期可以
			$if_apply = $this->_hasApply($job_id);//该职位是否已有报名
			if ($if_apply && $jobdata['effect_time'] > date('Y-m-d H:i:s') && !in_array($jobdata['check_state'], array($service_jobcheckstatus->checking,$service_jobcheckstatus->notpass))) {
				echo json_encode(array('status'=>false,'msg'=>'该职位已有求职者报名，不允许修改哦'));
				return;
			}
		}else{
			$is_agency=base_lib_BaseUtils::getStr($path_data['long_recruit'], 'int', 0);
			$service_partcompany = new base_service_part_company_partcompany();

			$part_comapny = $service_partcompany->getCompanyPartInfo($this->_userid,"company_id,is_insure_fee,insure_fee,score,is_agency");
			if(empty($part_comapny['is_agency'])){
				if(empty($is_agency)||!in_array($is_agency,array(1,2))){
					echo json_encode(array('status'=>false,'msg'=>'对不起，您还未选择招聘类型(中介或直招)'));
					return;
				}else{
					$update_items = array();
					$update_items['is_agency'] = $is_agency;
					$update_result = $service_partcompany->editPartCompany($this->_userid,$update_items);
					if(!$update_result){
						echo json_encode(array('status'=>false,'msg'=>'对不起，设置招聘类型失败'));
						return;
					}
				}
			}
		}


        if($this->_userid){
            $is_open_partjob = $service_comstate->getCompanyState($this->_userid,'is_open_partjob')['is_open_partjob'];
            if(empty($is_open_partjob)){
                echo json_encode(array('status'=>false,'msg'=>'对不起，贵单位无发布兼职的权限！'));
                return;
            }
        }
		$job['job_id'] = $job_id;
		//职位名称
		$job['station'] = $validator->getNotNull($path_data['partName'], '请输入职位名称');
		$validator->getStr($path_data['partName'], 6, 20, '职位名称6-20个字');

		//招聘人数
		$job['apply_count']  = $validator->getNum($path_data['partGivex'], 1, 999, '招聘人数为1-999人');

		//职位类型
		$partjobsorts = $service_partjobsort->getAll2();
		$job['jobsort_id'] = $validator->getEnum($path_data['jobsort_id'], $partjobsorts, '所属职业不正确');

		//展示城市
		$job['area_id'] = $validator->getNotNull($path_data['area_id'], '请选择展示城市');
		$address_info = base_lib_BaseUtils::getStr($path_data['add_info'], 'array', '');
		$is_need_workaddress = base_lib_BaseUtils::getStr($path_data['is_need_workaddress'], 'int', 1);

		//限制工作地点
		$address_list = array();
		if($is_need_workaddress == 1) {
			if (empty($address_info)) {
				//$validator->addErr("请选择工作地址");
				echo json_encode(array('status'=>false,'msg'=>"请添加工作地址"));die();

			} else {
				foreach ($address_info as $key => $value) {
					if ($value) {
						//拆分地址信息   121.481238|31.213348|0202|上海卢湾区
						$address_temp = explode('|', $value);
						if (count($address_temp) !== 4) {
							//$validator->addErr("工作地址信息错误");
							echo json_encode(array('status'=>false,'msg'=>"工作地址信息错误"));
							break;
						}
						$address_list[$key]['map_x'] = $address_temp[0];
						$address_list[$key]['map_y'] = $address_temp[1];
						$address_list[$key]['area_id'] = $address_temp[2];
						$address_list[$key]['address_info'] = $address_temp[3];
					}
				}
				$job['map_x'] 				= $address_list[0]['map_x'];
				$job['map_y'] 				= $address_list[0]['map_y'];
				$job['address'] 			= $address_list[0]['address_info'];

			}
		}else{
			//默认读取企业地址
			$job['map_x'] = $current_company['map_x'];
			$job['map_y'] = $current_company['map_y'];
			$job['address'] = $current_company['address'];
		}
		$job['is_need_workaddress'] = $is_need_workaddress;


		//长期招聘
		$long_recruits = array(0,1);
		$long_recruit = base_lib_BaseUtils::getStr($path_data['long_recruit'], 'int', 0);
		$job['long_recruit'] = $validator->getEnum($long_recruit, $long_recruits, '长期招聘不正确');

		//工作周期  新版改掉了老版的时间区间形式，改用选择的每一天，保存在worktime字段，该字段仅作为展示
		$job['work_time_type'] = 0;
		$min_time = '';
		$max_time = '';
		if(empty($job['long_recruit'])){
			$worktime = base_lib_BaseUtils::getStr($path_data['partTotaldate'], 'string', '');
			if(empty($worktime)){
				$validator->addErr("请选择工作周期");
			}else{
				$worktime_temp = explode(', ',$worktime);
				$min_time = $worktime_temp[0];
				$max_time = $worktime_temp[count($worktime_temp)-1];
				foreach($worktime_temp as $key=>$value){
					if(date('w',strtotime($value)) == 6 || date('w',strtotime($value)) == 0){
						//周末兼职
						$job['work_time_type'] = 2;
					}
					if(strtotime($value) < strtotime($min_time)){
						$min_time = $value;
					}
					if(strtotime($value) > strtotime($max_time)){
						$max_time = $value;
					}
				}
			}
			$job['work_time'] = $worktime;
		}else{
			//随时
			$job['work_time_type'] = 1;
		}

		//工作时间   新版自由选择时间
		$work_time_h = array();
		$workTimeHor = base_lib_BaseUtils::getStr($path_data['worktime_h'], 'array', '');
		if(empty($workTimeHor)){
			//$validator->addErr("请选择工作时间");
			echo json_encode(array('status'=>false,'msg'=>"请添加上班时间"));die();

		}else {
			foreach ($workTimeHor as $key => $val) {
				$time_temp = explode('-', $val);
				$work_time_h[$key]['stime'] = $time_temp[0];
				$work_time_h[$key]['etime'] = $time_temp[1];
			}
		}
		//职位有效期
		$validator->getNotNull($path_data['weekDatetime'], '请输入职位有效期');
		$job['effect_time'] = date('Y-m-d 23:59:59',strtotime($path_data['weekDatetime']));
		if(!$job['long_recruit']){
			if(strtotime($job['effect_time']) > strtotime($max_time)){
				//$validator->addErr("报名截止时间不能大于工作周期的最大时间");
				echo json_encode(array('status'=>false,'msg'=>"报名截止时间不能大于工作周期的最大时间"));die();
			}
//			if(strtotime($job['effect_time']) < strtotime($min_time)){
//				//$validator->addErr("报名截止时间不能小于工作周期的最小时间");
//				echo json_encode(array('status'=>false,'msg'=>"报名截止时间不能小于工作周期的最小时间"));
//				die();
//			}
		}
		//薪资
		$job['salary'] = $validator->getNotNull($path_data['partPayx'], '请输入薪资待遇');
		$validator->getNum($path_data['partPayx'], 0, 'max', '薪资待遇必须大于0');
		$validator->getNum($path_data['partPayx'], 'min', 10000, '薪资待遇不超过5位数');

		//薪资单位
		$service_unit = new base_service_part_job_salaryunit();
		$units = $service_unit->getAll();
		$job['salary_unit'] = $validator->getEnum($path_data['salaryunit'], $units, '薪资单位不正确');

		//薪资类型
		$service_type = new base_service_part_job_salarytype();
		$types = $service_type->getAll();
		$job['salary_type'] = $validator->getEnum($path_data['salarytypes'], $types, '薪资类型不正确');
		//福利待遇
		$job['welfare'] = base_lib_BaseUtils::getStr($path_data['wealTextarea'], 'string', '');

		//工作要求
		$job['position_require'] = $validator->getStr($path_data['partRequirements'], 20, 500, '岗位要求20-500个字');

		//性别
		$sex = base_lib_BaseUtils::getStr($path_data['sex'], 'int', 0);
		$service_sex = new base_service_common_sex();
		$sdes = $service_sex->getSex();
		$sdes['0'] = '不限';
		$job['sex'] = $validator->getEnum($sex, $sdes, '性别要求不正确');

		//社会身份
		$job['identity'] = base_lib_BaseUtils::getStr($path_data['idcard_type'], 'int', 0);
		//身高
		$job['height'] = base_lib_BaseUtils::getStr($path_data['height'], 'string', '');
		//年龄
		$job['age'] = base_lib_BaseUtils::getStr($path_data['age'], 'string', '');
		//学历
		$degreecode = $service_degree->getAll();
		$degreecode['0'] = '不限';
		$job['degree'] = $validator->getEnum($path_data['degree'], $degreecode, '学历要求不正确');
		//健康证
		$job['is_health'] = base_lib_BaseUtils::getStr($path_data['is_health'], 'int', 0);

		//资质证明照片
		$qualification_List = array();
		$qualification_info = base_lib_BaseUtils::getStr($path_data['qualification'], 'array', '');
		if(empty($qualification_info)){
			//$validator->addErr("请上传资质证明");
		}else {
			foreach ($qualification_info as $key => $val) {
				$temp_arr = explode('|', $val);
				if (count($temp_arr) < 3) {
					$validator->addErr("资质证明信息错误，请重新上传");
					break;
				}
				$qualification_List[$key]['name'] = $temp_arr[0];
				$qualification_List[$key]['qualification_type'] = $temp_arr[1];
				unset($temp_arr[0]);
				unset($temp_arr[1]);
				$qualification_List[$key]['img_path'] = $temp_arr;
				$qualification_List[$key]['company_id'] = $this->_userid;
				$qualification_List[$key]['create_time'] = date('Y-m-d H:i:s');
			}
		}

		//联系人相关
		//$job['account_id'] = $validator->getNotNull(base_lib_BaseUtils::getStr($path_data['param_account_id'],'int',''), '请选择职位发布人');
		$job['account_id'] = base_lib_BaseUtils::getCookie("accountid");
		$job['link_man'] = $validator->getStr(base_lib_BaseUtils::getStr($path_data['partcontName'],'string',''),2,10, '请填写2-10位的联系人姓名');
		$job['link_phone'] = $path_data['partcontTel'];
//		if(!preg_match('/^[1-9][0-9]{7}$/', $path_data['partcontTel']) && !preg_match('/0\d{2,3}\d{7,8}/', $path_data['partcontTel'])){
		if(!preg_match('/^((0\d{2,3}\d{7,8})|(1[3456789]\d{9}))$/', $path_data['partcontTel'])){
			$validator->addErr("请输入正确的联系电话或座机号");
		}

		$job['is_show_phone'] = base_lib_BaseUtils::getStr($path_data['is_show_phone'], 'int', 1);
		$job['is_show_weixin'] = base_lib_BaseUtils::getStr($path_data['is_show_weixin'], 'int', 0);
		$job['is_show_qq'] = base_lib_BaseUtils::getStr($path_data['is_show_qq'], 'int', 0);
		if($job['is_show_weixin'] == 1){
			$job['link_weixin'] = $validator->getNotNull($path_data['partWeixinName'], '请输入微信号');
		}
		if($job['is_show_qq'] == 1){
			$job['link_qq'] = $validator->getNotNull($path_data['partqqName'], '请输入qq号');
		}
		$thisdate = date('Y-m-d H:i:s');
		//职位刷新时间
        if(empty($job_id)){
            $job['refresh_time'] = $thisdate;
        }
		//职位创建时间
		$job['create_time'] = $thisdate;
		//职位更新时间
		$job['update_time'] = $thisdate;
		//企业id
		$job['company_id'] = $this->_userid;
		//职位状态
		$job['status'] = $service_partjobstatus->pub;
		//审核状态
		$job['check_state'] = $service_jobcheckstatus->checking;
		if ($validator->has_err) {
			echo json_encode(array('status'=>false,'msg'=>$validator->err[0]));
			//echo json_encode(array('status'=>false,'msg'=>"您填写的信息有误，请仔细查看"));
			return;
		}

		$result = $service_partjob->addJobV2($job,$address_list,$work_time_h,$qualification_List);
		if ($result['code']) {
		    $this->update_crm_partcompany_syncinfo($job["company_id"]);
			if(empty($job_id)){
				echo json_encode(array('status'=>true,'msg'=>'发布职位成功'));
			}else{
				echo json_encode(array('status'=>true,'msg'=>'编辑职位成功'));
			}

			return;
		} else {
			echo json_encode(array('status'=>false,'msg'=>$result['msg']));
			return;
		}			
	}


    /**
     * 招聘列表 手动停招
     * @param $inPath
     * @create_time 2019-4-15
     */
    public function pageEndJob($inPath)
    {
        $path_data         = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $job_flag          = base_lib_BaseUtils::getStr($path_data['job_flag'], 'string', '');
        $job_id            = base_lib_Rewrite::getId("partjob", $job_flag);
        $service_partjob   = new base_service_part_company_job();
        $service_jobapply  = new base_service_part_company_apply();
        $service_jobstatus = new base_service_common_part_partjobstatus();
        $jobdata           = $service_partjob->getJob($job_id, "job_id,company_id,create_time,end_time,check_state,status,valid_days");

        if (empty($jobdata) || $jobdata['company_id'] != $this->_userid) {
            exit(json_encode(array('error' => '职位不存在')));
        }
        if (!in_array($jobdata['status'], [$service_jobstatus->use, $service_jobstatus->pause])) {
            exit(json_encode(array('error' => '您操作的职位未招聘，无需停招')));
        }

        $result = $service_jobapply->refuseApplyByEndJob($job_id);
        if ($result) {
            $this->update_crm_partcompany_syncinfo($jobdata['company_id']);
            exit(json_encode(array('success' => '职位停招成功')));
        } else {
            exit(json_encode(array('error' => '职位停招失败')));
        }
    }



	/**
	 *
	 * 设置职位为已过期
	 */
	public function pageSetJobExpire($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$jobid = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', 0);

		$service_partjob        = new base_service_part_company_job();
		$service_jobcheckstatus = new base_service_common_part_jobcheckstatus();
		$service_jobstatus      = new base_service_common_part_partjobstatus();
		
		$thisdate = date('Y-m-d');
		$jobdata = $service_partjob->getJob($jobid, "job_id,company_id,create_time,end_time,check_state,status,valid_days");
		
		if (empty($jobdata) || $jobdata['company_id'] != $this->_userid) {
			echo json_encode(array('error'=>'您操作的职位不存在'));
			return;
		}else if($jobdata['status'] == $service_jobstatus->tmp_stop){
			echo json_encode(array('status'=>0,'error'=>'职位还没有处理完报名，请处理完后再设置过期'));
			return;
		}



		$valid_days = base_lib_TimeUtil::time_diff_day($jobdata['create_time'], $thisdate);
		$job['valid_days']    = $valid_days;
		$job['effect_time']   = date('Y-m-d H:i:s', strtotime("-1 day"));
		$job['status']        = $service_jobstatus->stop_use;
		$job['_stamp_remark'] = date('Y-m-d H:i:s');
		$result = $service_partjob->modifyJob($jobdata['job_id'], $job);

		if ($result) {
			$this->update_crm_partcompany_syncinfo($job["company_id"]);
			echo json_encode(array('success'=>'设置职位已过期成功'));
			return;
		} else {
			echo json_encode(array('error'=>'设置职位已过期失败'));
			return;
		}
	}
    /**
     *
     * 设置职位暂停招聘
     */
    public function pageSetJobPause($inPath) {
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $jobid = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', 0);

        $service_partjob        = new base_service_part_company_job();
        $service_jobcheckstatus = new base_service_common_part_jobcheckstatus();
        $service_jobstatus      = new base_service_common_part_partjobstatus();

        $thisdate = date('Y-m-d');
        $jobdata = $service_partjob->getJob($jobid, "job_id,company_id,create_time,end_time,check_state,status,valid_days");

        if (empty($jobdata) || $jobdata['company_id'] != $this->_userid) {
            echo json_encode(array('error'=>'您操作的职位不存在'));
            return;
        }
        $job['status']        = $jobdata['status']==$service_jobstatus->pause?$service_jobstatus->use:$service_jobstatus->pause;
        $job['_stamp_remark'] = date('Y-m-d H:i:s');
        $result = $service_partjob->modifyJob($jobdata['job_id'], $job);

        if ($result) {
            $this->update_crm_partcompany_syncinfo($job["company_id"]);
            echo json_encode(array("status"=>true,'msg'=>'设置成功','str'=>$jobdata['status']==$service_jobstatus->pause?"暂停招聘":"开启招聘",'statusstr'=>$jobdata['status']==$service_jobstatus->pause?"招聘中":"已暂停"));
            return;
        } else {
            echo json_encode(array("status"=>false,'msg'=>'设置失败'));
            return;
        }
    }
	
	/**
	 * 逻辑删除职位
	 */
	public function pageDeleteJob($inPath){
		$service_partjob        = new base_service_part_company_job();
		$service_jobstatus      = new base_service_common_part_partjobstatus();
		$service_jobcheckstatus = new base_service_common_part_jobcheckstatus();
		
		$thisdate = date('Y-m-d');
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$jobid = base_lib_BaseUtils::getStr($path_data['job_id'],'int',0);
		if ($jobid === 0) {
			echo json_encode(array('error'=>'您操作的职位不存在'));
			return;
		}

		$jobdata = $service_partjob->getJob($jobid, "company_id,end_time,check_state");
		if (empty($jobdata) || $jobdata['company_id'] != $this->_userid) {
			echo json_encode(array('error'=>'您操作的职位不存在'));
			return;
		}

		$result = $service_partjob->deleteJobLogic($jobid);
		
		if($result){
			$this->update_crm_partcompany_syncinfo($job["company_id"]);			
			echo json_encode(array('success'=>'删除职位成功'));return;
		}else{
			echo json_encode(array('error'=>'删除职位失败'));return;
		}
	}
	
	/**
	 * 待处理(无求职者报名)职位延期入口
	 */
	public function pageModJobExpire($inPath){
		$service_partjob        = new base_service_part_company_job();
		$service_jobstatus      = new base_service_common_part_partjobstatus();
		$service_jobcheckstatus = new base_service_common_part_jobcheckstatus();
		
		$thisdate = date('Y-m-d');
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$jobid = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', 0);

		$jobdata = $service_partjob->getJob($jobid, "company_id,job_id,station");
		if (empty($jobdata) || $jobdata['company_id'] != $this->_userid) {
			return $this->_returnFailPage("您操作的职位不存在");
		}

		$this->_aParams['station'] = $jobdata['station'];
		$this->_aParams['jobid']   = $jobid;
		return $this->render('part/job/modjobexpire.html',$this->_aParams);
	}
	
	/**
	 * 待处理(有求职者报名)职位延期
	 */
	public function pageModJobExpireDo($inPath){
		$service_partjob        = new base_service_part_company_job();
		$service_jobstatus      = new base_service_common_part_partjobstatus();
		$service_jobcheckstatus = new base_service_common_part_jobcheckstatus();
		
		$thisdate = date('Y-m-d');
		$now = date('Y-m-d H:i:s');
		
		$path_data  = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$jobid      = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', 0);
		$valid_days = base_lib_BaseUtils::getStr($path_data['valid_days'], 'int', 0);
		if ($valid_days <= 0 || $valid_days > 10) {
			echo json_encode(array('error'=>'每次最多只能延长10天'));
			return;
		}

		$if_apply = $this->_hasApply($jobid);//该职位是否已有报名
		if (!$if_apply) {
			echo json_encode(array('error'=>'有求职者报名的职位才允许延期哦'));
			return;
		}

		$jobdata = $service_partjob->getJob($jobid, "company_id,end_time,check_state");
		if (empty($jobdata) || $jobdata['company_id'] != $this->_userid) {
			echo json_encode(array('error'=>'您操作的职位不存在'));
			return;
		}

		$effect_time = date('Y-m-d H:i:s',strtotime("+{$valid_days} day"));
		$job = array(
			'valid_days'    => $valid_days,
			'effect_time'   => $effect_time,
			'_stamp_remark' => $now,
		);
		
		$result = $service_partjob->modifyJob($jobid, $job);
			
		if ($result) {
			echo json_encode(array('success'=>'职位延期成功'));return;
		} else {
			echo json_encode(array('error'=>'职位延期失败'));return;
		}
	}

	/**
	 * 重新发布职位入口
	 */
	public function pageRepubJob($inPath) {
		$service_partjob        = new base_service_part_company_job();
		$service_jobstatus      = new base_service_common_part_partjobstatus();
		$service_jobcheckstatus = new base_service_common_part_jobcheckstatus();
		$service_company        = new base_service_company_company();

		$current_company = $service_company->getCompany($this->_userid, 1, 'company_id,company_name,end_time');
		$this->_aParams['title'] = '汇博人才网_重新发布职位';

		if (empty($current_company)) {
			return $this->_returnFailPage("您操作的职位不存在或已过期");
		}

		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$jobid = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', 0);

		if ($jobid === 0) {
			return $this->_returnFailPage("您操作的职位不存在或已过期");
		}

		//是否达到允许的最大职位数
		$iscan = $this->_isCanSueJob($this->_userid);
		if (!$iscan) {
			return $this->_returnFailPage("每个企业最多同时允许招聘10个职位，当前已经达到上限，无法再发布");
		}

		//企业信用积分情况
		$score = $this->_getCompanyScore($this->_userid);
		if ($score < 0) {
			return $this->_returnFailPage("由于你的信用积分低于0分，不能再发布职位，如有疑问请联系客服400-1010-970");
		}

		$thisdate = date('Y-m-d');
		$fields = 'job_id,company_id,jobsort_id,station,apply_count,sex,degree,salary,salary_unit,salary_type,'
				. 'area_id,address,map_x,map_y,position_require,need_invite,link_tel,link_part,link_way,account_id,create_time,valid_days,start_time,end_time,time_detail,is_effect,check_state,fee,fee_type,effect_time';
		
		$jobdata = $service_partjob->getJob($jobid, $fields);
		if (empty($jobdata) 
			|| $jobdata['company_id'] != $this->_userid
			|| ($jobdata['status'] == $service_jobstatus->use && $jobdata['effect_time'] >= $thisdate)) {

			return $this->_returnFailPage("您操作的职位不存在或正在发布中");
		}

		//获取兼职时间
		$service_jobfreetime = new base_service_part_job_partjobfreetime();
		$jobtime = $service_jobfreetime->getJobFreeTime($jobid, 'job_id,code');
		if (!empty($jobtime)) {
			$this->_aParams['jobfreetime'] = base_lib_BaseUtils::getPropertys($jobtime, 'code');
		}

		$service_partjobsort = new base_service_common_part_jobsort();
		$service_freetime    = new base_service_common_part_freetime();
		$service_degree      = new base_service_common_degree();
		$service_area        = new base_service_common_area();
		$service_sex         = new base_service_common_sex();

		//薪资单位
		$service_unit = new base_service_part_job_salaryunit();
		$units = $service_unit->getAll();
		$this->_aParams['units'] = $this->_toJson($units);

		//薪资类型
		$service_type = new base_service_part_job_salarytype();
		$salarytypes = $service_type->getAll();
		$this->_aParams['salarytypes'] = $this->_toJson($salarytypes);

		//性别
		$sex = $service_sex->getSex();
		$this->_aParams['sex'] = $this->_toJson($sex);

		//兼职职位时间
		$freetime = $service_freetime->getAll();
		$this->_aParams['freetime'] = $this->_formatData($freetime);

		//学历
		$degree = $service_degree->getAll();
		$this->_aParams['degree'] = $this->_toJson($degree);

		//职位类型
		$partjobsorts = $service_partjobsort->getAll2();
		$this->_aParams['partjobsorts'] = $this->_toJson($partjobsorts);
		$this->_aParams['jobdata'] = $jobdata;

		//公司子账户信息
		$service_company_account = new base_service_company_account();
		$company_account_info = $service_company_account->getAccountList($this->_userid,'account_id,is_main,user_id,user_name,mobile_phone,link_tel')->items;
		$this->_aParams['company_account_info'] = base_lib_BaseUtils::array_key_assoc($company_account_info,'account_id');
		//新职位发布默认为当前登录账户
		if(empty($this->_aParams['jobdata']['account_id']))
			$this->_aParams['jobdata']['account_id'] = base_lib_BaseUtils::getCookie('accountid');


		return $this->render('part/job/repubjob.html', $this->_aParams);
	}

	/**
	 * @Desc 重新发布职位
	 */
	public function pageRepubJobDo($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$validator = new base_lib_Validator();

		$service_partjob        = new base_service_part_company_job();
		$service_degree         = new base_service_common_degree();
		$service_partjobsort    = new base_service_common_part_jobsort();
		$service_partjobstatus  = new base_service_common_part_partjobstatus();
		$service_jobcheckstatus = new base_service_common_part_jobcheckstatus();
		$jobid = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', 0);
		
		if ($jobid === 0) {
			return $this->_returnFailPage("您操作的职位不存在");
		}

		//是否达到允许的最大职位数
		$iscan = $this->_isCanSueJob($this->_userid);
		if (!$iscan) {
			return $this->_returnFailPage("每个企业最多同时允许招聘10个职位，当前已经达到上限，无法再发布");
		}

		//企业信用积分情况
		$score = $this->_getCompanyScore($this->_userid);
		if ($score < 0) {
			return $this->_returnFailPage("由于你的信用积分低于0分，不能再发布职位，如有疑问请联系客服400-1010-970");
		}

		//职位名称
		$job['station'] = $validator->getNotNull($path_data['txtStation'], '请输入职位名称');
		$validator->getStr($path_data['txtStation'], 3, 12, '名称3-12个字');

		//职位类型
		$partjobsorts = $service_partjobsort->getAll2();
		$job['jobsort_id'] = $validator->getEnum($path_data['jobType'], $partjobsorts, '兼职类型不正确');




		//长期招聘
		$long_recruits = array(0,1);
		$long_recruit = base_lib_BaseUtils::getStr($path_data['long_recruit'], 'int', 0);
		$job['long_recruit'] = $validator->getEnum($long_recruit, $long_recruits, '长期招聘不正确');
		
		//工作周期
		if(!$job['long_recruit']){
			$job['start_time'] = $validator->getDatetime($path_data['stime'], '请选择工作周期起始时间');
			$job['end_time']   = $validator->getDatetime($path_data['etime'], '请选择工作周期结束时间');
		}

		//工作时间
		$job['worktime'] = base_lib_BaseUtils::getStr($path_data['worktime'], 'array', '');

		//工作时间描述
		$job['time_detail'] = $validator->getStr($path_data['timeInfo'], 0, 50, '工作时间详情最多50个字', true);

		//招聘人数
		$applycount = $validator->getNotNull($path_data['applynums'], '请输入招聘人数');
		$job['apply_count']  = $validator->getNum($path_data['applynums'], 1, 999, '请输入大于0的4位以下数字');

		//性别
		$service_sex = new base_service_common_sex();
		$sdes = $service_sex->getSex();
		$sdes['0'] = '不限';
		$job['sex'] = $validator->getEnum($path_data['sex'], $sdes, '性别要求不正确');

		//学历
		$degreecode = $service_degree->getAll();
		$degreecode['0'] = '不限';
		$job['degree'] = $validator->getEnum($path_data['degree'], $degreecode, '学历要求不正确');

		//薪资
		$job['salary'] = $validator->getNotNull($path_data['salary'], '请输入薪资待遇');
		$validator->getNum($path_data['salary'], 0, 'max', '薪资待遇必须大于0');
		$validator->getNum($path_data['salary'], 'min', 10000, '薪资待遇不超过5位数');

		//薪资单位
		$service_unit = new base_service_part_job_salaryunit();
		$units = $service_unit->getAll();
		$job['salary_unit'] = $validator->getEnum($path_data['txtUnit'], $units, '薪资单位不正确');

		//薪资类型
		$service_type = new base_service_part_job_salarytype();
		$types = $service_type->getAll();
		$job['salary_type'] = $validator->getEnum($path_data['txtType'], $types, '薪资类型不正确');

		//工作地点
		$job['area_id'] = $validator->getStr($path_data['hidArea'], 0, 0, '请选择区域');

		//详细地点
		$job['address'] = $validator->getStr($path_data['txtAddress'], 0, 30, '详细地点最多30个字', true);

		//地图
		$job['map_x'] = base_lib_BaseUtils::getStr($path_data['hidMapX'], 'float', '');
		$job['map_y'] = base_lib_BaseUtils::getStr($path_data['hidMapY'], 'float', '');

		//是否收费
		$job['fee_type'] = $validator->getEnum($path_data['txtFee'], array(1 => '', 2 => ''), "请选择是否收费");
		$job['fee'] = base_lib_BaseUtils::getStr($path_data['txtFeeDetail'], 'float', '');

		//工作要求
		$job['position_require'] = $validator->getStr($path_data['workContent'], 20, 500, '工作要求20-500个字');

		//是否需要面试
		if (!base_lib_BaseUtils::nullOrEmpty($path_data['needinvite'])) {
			$job['need_invite'] = $validator->getEnum($path_data['needinvite'], array(0, 1), '请选择是否需要面试');
		}

		//咨询电话
//		$job['link_tel'] = $path_data['linkway'];
//		if (base_lib_BaseUtils::nullOrEmpty($job['link_tel'])
//			|| !(preg_match('/^.{0,30}\d{5}.{0,30}$/',$job['link_tel'])
//			|| preg_match('/^(?:13|15|18|14|17)[0-9]\d{8}$/', $job['link_tel']))) {
//			array_push($validator->err, '咨询电话格式不正确');
//			$validator->has_err = true;
//		}
		//联系人相关
		$job['account_id'] = $validator->getNotNull(base_lib_BaseUtils::getStr($path_data['param_account_id'],'int',''), '请选择职位发布人');
		$job['link_way'] = $validator->getNotNull(base_lib_BaseUtils::getStr($path_data['newLinkway'],'int',''), '请选择联系方式');
		if($job['link_way'] == 1){
			$job['link_tel'] = $validator->getTel(base_lib_BaseUtils::getStr($path_data['txtLinkTel'],'string',''), '请正确填写联系电话');
			$job['link_part'] = $validator->getStr(base_lib_BaseUtils::getStr($path_data['txtLinkPart'],'string',''),0,5, '请填写1-5为的分机号');//可为空
		}


		//职位有效期
		$job['valid_days'] = $validator->getNum($path_data['txtValidDays'], 1, 20, '职位有效期为1～20的整数，且首位不能为0');

		//职位有效时间
		$job['effect_time'] = date('Y-m-d H:i:s', strtotime("+{$job['valid_days']} day"));
		$thisdate = date('Y-m-d H:i:s');

		//职位刷新时间
	//	$job['refresh_time'] = $thisdate;

		//职位创建时间
		$job['create_time'] = $thisdate;

		//职位更新时间
		$job['update_time'] = $thisdate;

		//企业id
		$job['company_id'] = $this->_userid;

		//职位状态
		$job['status'] = $service_partjobstatus->pub;

		//审核状态
		$job['check_state'] = $service_jobcheckstatus->checking;
		if ($validator->has_err) {
			echo header("Content-type:text/plain;charset=utf-8");
			echo $validator->toJsonWithHtml();
			return;
		}

		$result = $service_partjob->addJob($job);
		if ($result) {
			//删除原来的职位
			$service_partjob->deleteJobLogic($jobid);
		    $this->update_crm_partcompany_syncinfo($job["company_id"]);
			echo json_encode(array('success'=>'重新发布职位成功'));
			return;
		} else {
			echo json_encode(array('error'=>'重新发布职位失败'));
			return;
		}
	}
	
	/**
	 * 修改职位入口
	 */
	public function pageModJob($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$jobid = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', 0);

		$service_partjob        = new base_service_part_company_job();
		$service_jobstatus      = new base_service_common_part_partjobstatus();
		$service_jobcheckstatus = new base_service_common_part_jobcheckstatus();
		$service_company        = new base_service_company_company();

		$current_company = $service_company->getCompany($this->_userid, 1, 'company_id,company_name,end_time');
		$this->_aParams['title'] = '汇博人才网_修改职位';

		if (empty($current_company) || empty($jobid)) {
			return $this->_returnFailPage('您操作的职位不存在或已过期');
		}

		$thisdate = date('Y-m-d');
		$fields = 'job_id,company_id,jobsort_id,station,apply_count,sex,degree,salary,salary_unit,salary_type,status,'
				. 'area_id,address,map_x,map_y,position_require,need_invite,link_tel,link_part,link_way,account_id,create_time,valid_days,start_time,'
				. 'end_time,time_detail,is_effect,check_state,fee,fee_type,long_recruit';
		$jobdata = $service_partjob->getJob($jobid, $fields);

		if (empty($jobdata) || $jobdata['company_id'] != $this->_userid) {
			return $this->_returnFailPage('您操作的职位不存在或已过期');
		}
		// var_dump($jobdata,$service_jobcheckstatus->getJobStatus());
		if ($jobdata['check_state'] == $service_jobcheckstatus->notpass 
			|| $jobdata['check_state'] == $service_jobcheckstatus->checking //审核中的职位也能重新发布修改（兼职二期补充）
			|| $jobdata['status'] == $service_jobstatus->stop_use
			|| ($jobdata['check_state'] == $service_jobcheckstatus->pass && $jobdata['effect_time'] < $thisdate)) {

		} else {
			return $this->_returnFailPage('您操作的职位不存在或已过期');
		}



		//是否达到允许的最大职位数
		$iscan = $this->_isCanSueJob($this->_userid);
		if (!$iscan) {
			return $this->_returnFailPage('每个企业最多同时允许招聘10个职位，当前已经达到上限，无法再发布');
		}

		//获取兼职时间
		$service_jobfreetime = new base_service_part_job_partjobfreetime();
		$jobtime = $service_jobfreetime->getJobFreeTime($jobid,'job_id,code');

		if (!empty($jobtime)) {
			$this->_aParams['jobfreetime'] = base_lib_BaseUtils::getPropertys($jobtime, 'code');
		}
		
		$service_partjobsort = new base_service_common_part_jobsort();
		$service_freetime    = new base_service_common_part_freetime();
		$service_degree      = new base_service_common_degree();
		$service_area        = new base_service_common_area();
		$service_sex         = new base_service_common_sex();
			
		//薪资单位
		$service_unit = new base_service_part_job_salaryunit();
		$units = $service_unit->getAll();
		$this->_aParams['units'] = $this->_toJson($units);
		
		//薪资类型
		$service_type = new base_service_part_job_salarytype();
		$salarytypes = $service_type->getAll();
		$this->_aParams['salarytypes'] = $this->_toJson($salarytypes);
		
		//性别
		$sex = $service_sex->getSex();
		$this->_aParams['sex'] = $this->_toJson($sex);
		
		//兼职职位时间
		$freetime = $service_freetime->getAll();
		$this->_aParams['freetime'] = $this->_formatData($freetime);
		
		//学历
		$degree = $service_degree->getAll();
		$this->_aParams['degree'] = $this->_toJson($degree);
		
		//职位类型
		$partjobsorts = $service_partjobsort->getAll2();
		$this->_aParams['partjobsorts'] = $this->_toJson($partjobsorts);
		
		$this->_aParams['jobdata'] = $jobdata;


		//公司子账户信息
		$service_company_account = new base_service_company_account();
		$company_account_info = $service_company_account->getAccountList($this->_userid,'account_id,is_main,user_id,user_name,mobile_phone,link_tel')->items;
		$this->_aParams['company_account_info'] = base_lib_BaseUtils::array_key_assoc($company_account_info,'account_id');
		//新职位发布默认为当前登录账户
		if(empty($this->_aParams['jobdata']['account_id']))
			$this->_aParams['jobdata']['account_id'] = base_lib_BaseUtils::getCookie('accountid');
        $service_comstate = new base_service_company_comstate();
        $this->_aParams['is_open_partjob'] = $service_comstate->getCompanyState($this->_userid,'is_open_partjob')['is_open_partjob'];

		return $this->render('part/job/jobupdate.html', $this->_aParams);
	}
	
	/**
	 * 修改职位
	 */
	public function pageModJobDo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

		$service_partjob        = new base_service_part_company_job();
		$service_jobcheckstatus = new base_service_common_part_jobcheckstatus();
		$service_partjobstatus  = new base_service_common_part_partjobstatus();
		$service_company        = new base_service_company_company();
        $service_comstate = new base_service_company_comstate();
        $is_open_partjob = $service_comstate->getCompanyState($this->_userid,'is_open_partjob')['is_open_partjob'];
        if($is_open_partjob != 1){
            echo json_encode(array('error'=>'对不起，贵单位无发布兼职的权限！'));
            return;
        }
		$current_company = $service_company->getCompany($this->_userid, 1, 'company_id,company_name,end_time');
		if (empty($current_company)) {
			echo json_encode(array('error'=>'您操作的职位不存在'));
			return;
		}

		$jobid = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', 0);
		if ($jobid === 0) {
			echo json_encode(array('error'=>'您操作的职位不存在'));
			return;
		}

		//是否达到允许的最大职位数
		$iscan = $this->_isCanSueJob($this->_userid);
		if (!$iscan) {
			echo json_encode(array('error'=>'每个企业最多同时允许招聘10个职位，当前已经达到上限，无法再发布'));
			return;
		}

		$thisdate = date('Y-m-d');
		$fields = 'job_id,company_id,jobsort_id,station,apply_count,sex,degree,salary,salary_unit,salary_type,effect_time,status'
				. ',area_id,address,map_x,map_y,position_require,need_invite,link_tel,create_time,valid_days,start_time,end_time,time_detail,is_effect,check_state';
		$jobdata = $service_partjob->getJob($jobid, $fields);

		if (empty($jobdata) || $jobdata['company_id'] != $this->_userid) {
			echo json_encode(array('error'=>'您操作的职位不存在'));
			return;
		}

		if ($jobdata['check_state'] == $service_jobcheckstatus->checking 
			|| $jobdata['check_state'] == $service_jobcheckstatus->notpass
			|| ($jobdata['status'] == $service_partjobstatus->use && $jobdata['effect_time'] > $thisdate)) {

		} else {
			echo json_encode(array('error'=>'您操作的职位不存在'));
			return;
		}

		$validator           = new base_lib_Validator();
		$service_degree      = new base_service_common_degree();
		$service_partjobsort = new base_service_common_part_jobsort();
		
		//职位名称
		$job['station'] = $validator->getNotNull($path_data['txtStation'], '请输入职位名称');
		$validator->getStr($path_data['txtStation'], 3, 12, '名称3-12个字');


		//联系人相关
		$job['account_id'] = $validator->getNotNull(base_lib_BaseUtils::getStr($path_data['param_account_id'],'int',''), '请选择职位发布人');
		$job['link_way'] = $validator->getNotNull(base_lib_BaseUtils::getStr($path_data['newLinkway'],'int',''), '请选择联系方式');
		if($job['link_way'] == 1){
			$job['link_tel'] = $validator->getTel(base_lib_BaseUtils::getStr($path_data['txtLinkTel'],'string',''), '请正确填写联系电话');
			$job['link_part'] = $validator->getStr(base_lib_BaseUtils::getStr($path_data['txtLinkPart'],'string',''),0,5, '请填写1-5为的分机号');//可为空
		}



		//职位类型
		$partjobsorts = $service_partjobsort->getAll2();
		$job['jobsort_id'] = $validator->getEnum($path_data['jobType'], $partjobsorts, '兼职类型不正确');
		
		//长期招聘
		$long_recruits = array(0,1);
		$long_recruit = base_lib_BaseUtils::getStr($path_data['long_recruit'], 'int', 0);
		$job['long_recruit'] = $validator->getEnum($long_recruit, $long_recruits, '长期招聘不正确');
		
		//工作周期
		if(!$job['long_recruit']){
			$job['start_time'] = $validator->getDatetime($path_data['stime'], '请选择工作周期起始时间');
			$job['end_time']   = $validator->getDatetime($path_data['etime'], '请选择工作周期结束时间');
		}
		
		//工作时间
		$job['worktime'] = base_lib_BaseUtils::getStr($path_data['worktime'], 'array', '');
		
		//工作时间描述
		$job['time_detail'] = $validator->getStr($path_data['timeInfo'], 0, 50, '工作时间详情最多50个字', true);
		
		//招聘人数
		$applycount = $validator->getNotNull($path_data['applynums'], '请输入招聘人数');
		$job['apply_count']  = $validator->getNum($path_data['applynums'], 1, 999, '请输入大于0的4位以下数字');
		
		//性别
		$service_sex = new base_service_common_sex();
		$sdes = $service_sex->getSex();
		$sdes['0'] = '不限';
		$job['sex'] = $validator->getEnum($path_data['sex'], $sdes, '性别要求不正确');
		
		//学历
		$degreecode = $service_degree->getAll();
		$degreecode['0'] = '不限';
		$job['degree'] = $validator->getEnum($path_data['degree'], $degreecode, '学历要求不正确');
		
		//薪资
		$job['salary'] = $validator->getNotNull($path_data['salary'], '请输入薪资待遇');
		$validator->getNum($path_data['salary'], 0, 'max', '薪资待遇必须大于0');
		$validator->getNum($path_data['salary'], 'min', 10000, '薪资待遇不超过5位数');
		
		//薪资单位
		$service_unit = new base_service_part_job_salaryunit();
		$units = $service_unit->getAll();
		$job['salary_unit'] = $validator->getEnum($path_data['txtUnit'], $units, '薪资单位不正确');
		
		//薪资类型
		$service_type = new base_service_part_job_salarytype();
		$types = $service_type->getAll();
		$job['salary_type'] = $validator->getEnum($path_data['txtType'], $types, '薪资类型不正确');
		
		//工作地点
		$job['area_id'] = $validator->getStr($path_data['hidArea'], 0, 0, '请选择区域');
		
		//详细地点
		$job['address'] = $validator->getStr($path_data['txtAddress'], 0, 30, '详细地点最多30个字', true);
		
		//地图
		$job['map_x'] = base_lib_BaseUtils::getStr($path_data['hidMapX'], 'float', '');
		$job['map_y'] = base_lib_BaseUtils::getStr($path_data['hidMapY'], 'float', '');

		//工作要求
		$job['position_require'] = $validator->getStr($path_data['workContent'], 20, 500, '工作要求20-500个字');

		//是否需要面试
		$job['need_invite'] = $validator->getEnum($path_data['needinvite'], array(0, 1), '请选择是否需要面试');

		//咨询电话
//		$job['link_tel'] = $path_data['linkway'];
//		if (base_lib_BaseUtils::nullOrEmpty($job['link_tel'])
//			|| !(preg_match('/^.{0,30}\d{5}.{0,30}$/',$job['link_tel'])
//			|| preg_match('/^(?:13|15|18|14|17)[0-9]\d{8}$/', $job['link_tel']))) {
//			array_push($validator->err, '咨询电话格式不正确');
//			$validator->has_err = true;
//		}

		//是否收费
		$job['fee_type'] = $validator->getEnum($path_data['txtFee'], array(1 => '', 2 => ''), "请选择是否收费");
		$job['fee'] = base_lib_BaseUtils::getStr($path_data['txtFeeDetail'], 'float', '');

		//职位有效期
		$job['valid_days'] = $validator->getNum($path_data['txtValidDays'], 1, 20, '职位有效期为1～20的整数，且首位不能为0');

		//职位有效时间
		$job['effect_time'] = date('Y-m-d H:i:s', strtotime ("+{$job['valid_days']} day"));

		//职位刷新时间
		//$job['refresh_time'] = date('Y-m-d H:i:s');

		//企业id
		$job['company_id'] = $this->_userid;

		//职位状态
		$job['status'] = $service_partjobstatus->pub;
		if ($validator->has_err) {
			echo header("Content-type:text/plain;charset=utf-8");
			echo $validator->toJsonWithHtml();
			return;
		}

		$flag = base_lib_BaseUtils::getStr($path_data['flag'], 'string', '');

		//审核状态
		$job['check_state'] = $service_jobcheckstatus->checking;

		//修改职位				
		$if_apply = $this->_hasApply($jobid);//该职位是否已有报名
		if ($if_apply) {
			echo json_encode(array('error'=>'该职位已有求职者报名，不允许修改哦'));
			return;
		}

		$result = $service_partjob->modifyJob($jobdata['job_id'], $job);
		if ($result) {
		    $this->update_crm_partcompany_syncinfo($job["company_id"]);
			echo json_encode(array('success'=>'修改职位成功'));
			return;
		} else {
			echo json_encode(array('error'=>'修改职位失败'));
			return;
		}
	}

	/**
	 * 职位详情页
	 * @param  array $inPath  url参数
	 * @return html           兼职详情页面
	 */
	public function pageDetail($inPath) {
		$post = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$validator = new base_lib_Validator();
		$job_flag  = $validator->getNotNull($post['job_flag'], '请填写职位id');
		$job_id    = base_lib_Rewrite::getId("partjob", $job_flag);

		if ($validator->has_err)
			return $this->render("../config/404.html");

		/* 获取职位信息 */
		$job_service = new base_service_part_company_job();
		$job = $job_service->getJob($job_id, "job_id,company_id,station,apply_count,address,map_x,map_y,salary,salary_unit,salary_type,"
			. "start_time,end_time,time_detail,position_require,jobsort_id,sex,degree,need_invite,long_recruit,link_tel,link_part,account_id,link_way");

		if (empty($job) || empty($job['company_id']) || $job['company_id'] != $this->_userid)
			return $this->render("../config/404.html");
		
		$this->_aParams = $job;

		$freetime_service = new base_service_part_job_partjobfreetime();
		$freetimes = $freetime_service->getJobFreeTime($job_id, "job_id,code");
		$this->_aParams['freetimes'] = base_lib_BaseUtils::getProperty($freetimes, 'code');

		$salary_unit = new base_service_common_part_salaryunit();
		$this->_aParams['salary'] = $job['salary'] . "元". $salary_unit->getName($job['salary_unit']);

		$salary_type = new base_service_common_part_salarytype();
		$this->_aParams['salary_type'] = $salary_type->getName($job['salary_type']);

		$jobsort_common = new base_service_common_part_jobsort();
		$this->_aParams['jobsort'] = $jobsort_common->getJobsort($job['jobsort_id']);

		/* 获取公司信息 */
		$company_service = new base_service_company_company();
		$company = $company_service->getCompany($job['company_id'], 1, "company_name,company_shortname,company_bright_spot,info");

		if (empty($company))
			return $this->render("../config/404.html");

		$this->_aParams['company_name']        = empty($company['company_shortname']) ? $company['company_name'] : $company['company_shortname'];
		$this->_aParams['company_bright_spot'] = $company['company_bright_spot'];
		$this->_aParams['info']                = $company['info'];

		/* 获取其他兼职 */
		$other_jobs = $job_service->getCompanyJobs($job['company_id'], "job_id,company_id,station,salary,salary_type,salary_unit", $page_index=1, $page_size=10, $offset=0, $job_id);
		foreach ((array)$other_jobs->items as $key => $value) {
			$jobs[] = array(
				"job_flag"         => base_lib_Rewrite::getFlag("partjob", $value['job_id']),
				"job_id"           => $value['job_id'],
				"salary"           => $value['salary'] . "元",
				"station"          => $value['station'],
				"salary_type_text" => $salary_unit->getName($value['salary_unit'])
			);
		}
		$this->_aParams['other_jobs'] = $jobs;
		$this->_aParams['job_count']  = empty($other_jobs->totalSize) ? 0 : $other_jobs->totalSize;

		/* 好评率 */
		$assessment_service = new base_service_part_company_assessment();
		$count = $assessment_service->getCount($job['company_id']);
		$good_count = empty($count['good_count']) ? 0 : $count['good_count'];
		$this->_aParams['good_rate']  = empty($count['total_count']) ? "-" : intval($count['good_count'] / $count['total_count'] * 100) . "%";

		/* 获取三个月纠纷数量 */
		$blame_service = new base_service_part_company_blame();
		$this->_aParams['blame_count'] = $blame_service->getBlameCountWithCompany($job['company_id'], $day=90);

		/* 获取投递回复率 */
		$apply_service = new base_service_part_company_apply();
		$rate = $apply_service->getReplyRate($job['company_id'], $day = 10);
		$this->_aParams['reply_rate'] = (empty($rate['count'])) ? "100%" : intval($rate['reply_count'] / $rate['count'] * 100) . "%";

		$postvar['page_index'] = 1;
		$postvar['page_size']  = 3;
		
		/* 获取所有评价 */
		$assessments = $assessment_service->getAssessmentsByCompany($job['company_id'], "company_id,person_id,create_time,content", $postvar);
		$person_ids  = base_lib_BaseUtils::getProperty($assessments->items, "person_id");

		if (!empty($person_ids)) {
			$person_service = new base_service_person_person();
			$persons = $person_service->getPersons($person_ids, "person_id,user_name,small_photo")->items;
			$persons = base_lib_BaseUtils::array_key_assoc($persons, 'person_id');
		}
		$default_photo = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP."/photo/2015-08-17/0817992423_middle.jpg";
		foreach ((array)$assessments->items as $value) {
			$this->_aParams['assessments'][] = array(
				'person_id'   => $value['person_id'],
				'create_time' => date("Y-m-d", strtotime($value['create_time'])),
				'user_name'   => mb_substr($persons[$value['person_id']]['user_name'], 0, 1) . "**",
				'user_photo'  => !empty($persons[$value['person_id']]['small_photo']) ? base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $persons[$value['person_id']]['small_photo'] : $default_photo,
				'content'     => $value['content']
			);
		}

		//-----------------最新联系方式处理：--------------
		$service_account_info = new base_service_company_account();
		//如果只为acount_id为空，获取主账户count信息
		if(empty($job['account_id'])){
			$job['account_id'] = $this->getMainAccount($job['company_id']);
		}else{
			//如果acount_id对应的发布人被删除，默认主账户
			$account_info = $service_account_info->getAccount($job['account_id'],'user_name,user_id,head_photo,link_tel,mobile_phone,station');
			if(empty($account_info)){
				$job['account_id'] = $this->getMainAccount($job['company_id']);
			}
		}
		if(!empty($job['account_id'])){
			$account_info = $service_account_info->getAccount($job['account_id'],'user_name,user_id,head_photo,link_tel,mobile_phone,station');
			$this->_aParams['account_user_name_pic'] = mb_substr($account_info['user_name'],0,1);
			if(!empty($account_info['head_photo'])){
				$this->_aParams['account_user_pic'] = base_lib_Constant::UPLOAD_FILE_URL.$account_info['head_photo'];
			}

			//判断显示哪个联系电话
			if($job['link_way'] == 4){
				$_show_link_phone = $account_info['mobile_phone'];
			}else if($job['link_way'] == 5){
				$_show_link_phone = $account_info['link_tel'] ;
			}else{
				$_show_link_phone = empty($job['link_part']) ? $job['link_tel'] : $job['link_tel']."-".$job['link_part'];
			}

			$this->_aParams['com_linkman']['linkman'] = $account_info['user_name'];
			$this->_aParams['com_linkman']['linkman_station'] = $account_info['station'];
			$this->_aParams['com_linkman']['link_tel'] = $_show_link_phone;

		}


		$this->_aParams['assessment_count'] = empty($assessments->totalSize) ? 0 : $assessments->totalSize;
		return $this->render("part/detail.html", $this->_aParams);
	}

	//获取主账户ID
	private function getMainAccount($company_id)
	{
		//公司子账户信息
		$service_company_account = new base_service_company_account();
		$company_account_info = $service_company_account->getAccountList($company_id,'account_id,is_main')->items;
		$company_account_info_tmp = base_lib_BaseUtils::array_key_assoc($company_account_info,'account_id');
		//如果该职位无account_id 默认为主账户account_id
		foreach($company_account_info_tmp as $v){
			if($v['is_main'] == 1){
				return $v['account_id'];
			}
		}
	}

	/**
	 * 企业执照审核完善
	 */
	public function pageUpdateCompany($inPath) {
		$service_company = new base_service_company_company();
		$company = $service_company->getCompany($this->_userid, 1, 'company_id,company_name,is_audit,audit_state,is_effect,company_flag');
		$this->_aParams['company'] = $company;
		$this->_aParams['title'] = '汇博人才网_发布职位';
		//企业是否通过执照审核
		$service_company_resources_resources = base_service_company_resources_resources::getInstance($this->_userid);
		$letter_info = $service_company_resources_resources->getCompanyAuditStatusV2();

		$is_audit = $letter_info['is_audit'];
		//为1代表已经认证,或在临时和补办状态中,2认证中
		//未通过审核或审核失败，跳转到执照审核页面
		if($is_audit != 1){
			$this->_aParams['need_audit'] = true;
		}

		return $this->render('part/updatecompany.html',$this->_aParams);
	}

	public function pageAssessments($inPath) {
		$post = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$postvar['offset']     = 3;
		$postvar['page_index'] = $post['page'];
		$postvar['page_size']  = base_lib_Constant::PAGE_SIZE;

		/* 获取所有评价 */
		$assessment_service = new base_service_part_company_assessment();
		$assessments = $assessment_service->getAssessmentsByCompany($this->_userid, "company_id,person_id,create_time,content", $postvar);
		$person_ids  = base_lib_BaseUtils::getProperty($assessments->items, "person_id");

		if (!empty($person_ids)) {
			$person_service = new base_service_person_person();
			$persons = $person_service->getPersons($person_ids, "person_id,user_name,small_photo")->items;
			$persons = base_lib_BaseUtils::array_key_assoc($persons, 'person_id');
		}

		$default_photo = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP."/photo/2015-08-17/0817992423_middle.jpg";
		foreach ((array)$assessments->items as $value) {
			$arrs[] = array(
				'person_id'   => $value['person_id'],
				'create_time' => date("Y-m-d", strtotime($value['create_time'])),
				'user_name'   => mb_substr($persons[$value['person_id']]['user_name'], 0, 1) . "**",
				'user_photo'  => !empty($persons[$value['person_id']]['small_photo']) ? base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$persons[$value['person_id']]['small_photo'] : $default_photo,
				'content'     => $value['content']
			);
		}
		echo json_encode($arrs);
		return ;
	}

	public function pageAddJobSelect($inPath) {
		$service_partcompany = new base_service_part_company_partcompany();
		//获取当前企业的兼职信息
		$part_comapny = $service_partcompany->getCompanyPartInfo($this->_userid,"company_id,is_insure_fee,insure_fee,score,is_agency");
		$this->_aParams['has_set_agency'] = false;
		if(!base_lib_BaseUtils::nullOrEmpty($part_comapny)){
			if($part_comapny['is_agency'] ==2 || $part_comapny['is_agency']==1){
				$this->_aParams['has_set_agency'] = true;
			}
		}
		
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
		$company_level_data = $company_resources->getCompanyServiceSource(['company_level']);
		//添加是否存在待使用优惠劵 已进行企业资料验证；已通过营业执照验证；非在用会员
		$letter_info = $company_resources->getCompanyAuditStatusV2();
		$licence_audit_type = $letter_info['licence_audit_type'];
		$letter_audit_type = $letter_info['letter_audit_type'];
		if (in_array($licence_audit_type, [1, 4, 5]) && in_array($letter_audit_type, [1, 4]) && $company_level_data['company_level'] == 0) {
			$ser_allotCouponPricing = new base_service_company_coupon_allotCouponPricing();
			$exist_where = [
				'allot_state'   => 2,
				'state'         => 1,
				'validity_date' =>
					$this->_ymd,
			];
			$allotCouponPricing_data = $ser_allotCouponPricing->getAllotCouponPricingByCompanyID($this->_userid, $exist_where);
			if ($allotCouponPricing_data && $allotCouponPricing_data['id']) {
				$this->_aParams['allotCouponPricing_data'] = $allotCouponPricing_data;
			}
		}
		
		$martchCalling = $this->_matchCompanyCalling();
		$this->_aParams['martchCalling'] = $martchCalling;
		$this->_aParams['mjianzhi_url'] = base_lib_Constant::MOBILE_JIANZHI_NO_HTTP;
		$this->_aParams['company_id'] = $this->_userid;
        //是否关注了兼职公众号
        $jianzhiWeixinService = new SJianzhiWeixin();
        $this->_aParams["show_app_bing_weixin"] = $jianzhiWeixinService->isAttentionJZwxByperson_id($this->_userid,'c');
        $ticket = $jianzhiWeixinService->getTDcodeWithCompanyid($this->_userid,600);
        $this->_aParams["ticket"] = $ticket->ticket;
        $service_government = new base_service_company_government();
        $is_gov = $service_government->getGovernmentCompany($this->_userid,'company_id'); //区团委需求，去掉“发布兼职”按钮
        $this->_aParams["is_gov"] = empty($is_gov)?0:1;
		return $this->render('part/jobaddselect.html', $this->_aParams);
	}

	private function _salaryalias($value) {
		if ($value == 1) {
			return "/天";
		} else if ($value == 2) {
			return "/周";
		} else if ($value == 3) {
			return "/月";
		}
	}

	/**
	 * 判断是否能发布职位,目前只根据可发布职位数判断
	 * 0   表示可以发布职位
	 * 1   表示会员级别不够
	 * 2   表示会员期已过期
	 * 3   发布职位数已满
	 */
	private function _isCanSueJob($companyID) {
		//获得未开通服务的企业默认职位数量
		$xml = SXML::load('../config/company/company.xml');
		$default_partjob_num = 0;
		if (!is_null($xml)) {
			$default_partjob_num = $xml->DefaultPartJobNum;
		}
	
		$job_num = intval($default_partjob_num); // 企业可发布职位数量
	
		$service_job_status = new base_service_common_part_partjobstatus();
		$service_partjob    = new base_service_part_company_job();
		$current_job_num = $service_partjob->getJobCount($this->_userid, array('in' => array($service_job_status->pub, $service_job_status->use, $service_job_status->tmp_stop)))->items[0];
		
		if ($job_num <= $current_job_num['jobnums']) {
			return false;
		}
		return true;
	}

	/**
	 * 判断是否能发布职位,目前只根据可发布职位数判断
	 * 0   表示可以发布职位
	 * 1   表示会员级别不够
	 * 2   表示会员期已过期
	 * 3   发布职位数已满
	 */
	private function _isCanSueJobV2($job_id = '') {
		//获得未开通服务的企业默认职位数量
		$xml = SXML::load('../config/company/company.xml');
		$default_partjob_num = 0;
		if (!is_null($xml)) {
			$default_partjob_num = $xml->DefaultPartJobNum;
		}

		$job_num = intval($default_partjob_num); // 企业可发布职位数量
        $service_pay_job      = new base_service_part_company_payjobnum();
        $can_pub_pay_job_num  = $service_pay_job->getEffectNumTotal($this->_userid);//可发布的付费职位数
        $job_num = $job_num+$can_pub_pay_job_num;
		$service_job_status = new base_service_common_part_partjobstatus();
		$service_partjob    = new base_service_part_company_job();

		$job_info = $service_partjob->getJobCountV2($this->_userid, array('in' => array($service_job_status->pub,$service_job_status->pause, $service_job_status->use, $service_job_status->tmp_stop)));
		if ($job_num <= count($job_info)) {
			if($job_id){
				$job_ids = array();
				foreach($job_info as $val){
					$job_ids[] = $val['job_id'];
				}
				if(!in_array($job_id,$job_ids)){
					return false;
				}
			}else{
				return false;
			}

		}



		return true;
	}
	
	/**
	 * 判断职位是否有报名
	 * @param int $job_id 
	 * @return bool 是否已有报名
	 */
	private function _hasApply($job_id){
		if(empty($job_id))
			return true;
		$service_apply = new base_service_part_company_apply();
		$applys = $service_apply->getApplyByJob($job_id,'(count(1) > 0) has_apply');
		return $applys['has_apply'];
	}
	
	/**
	 * 转换为前端的json数据
	 * @param array $data
	 * @param string $special 特殊处理的数据
	 * @return jsondata
	 */
	private function _toJson($data,$special=''){
		if(empty($data))
			return json_encode((array)$data);
		$result = array();
		//前台格式：{label: '日结', value: 1},
		if($special == 'areas'){
			foreach ((array)$data as $k=>$v){
				$container['label'] = $v['area_name'];
				$container['value'] = $v['area_id'];
				array_push($result, $container);
			}				
		}
		if($special == ''){
			foreach ((array)$data as $k=>$v){
				$container['label'] = $v;
				$container['value'] = $k;
				array_push($result, $container);
			}
		}
		return json_encode($result);
	}
	
	/**
	 * 转换职位时间为适应前端的数据
	 * @param array $data of freetime
	 * @return array
	 */
	private function _formatData($data){
		if(empty($data))
			return array();
		$result = array();//{label: '日结', value: 1},
		foreach ((array)$data as $k=>$v){
			$substr = substr($k, -2);
			$result[$substr][$v['code']] = $v['name'];
		}			
		return $result;
	}
	
	/*
	 * 获得企业资料中未填的重要项，在首页中引导出来
	 */
	private function _getCompanyNotUpdate($company){
		$word_array = array();
		$service_shortname = new base_service_company_shortnameaudit();
		$companys_j = $service_shortname->getCompanyByCompanyIdAndStatus($this->_userid,"audit_id,company_id,company_shortname");
		$company_audit_list = $companys_j->items;

		if(!base_lib_BaseUtils::nullOrEmpty($company)){
			if(base_lib_BaseUtils::nullOrEmpty($company['company_name'])){
				$word_array[] = "公司名称";
			}
			if(base_lib_BaseUtils::nullOrEmpty($company['company_shortname']) && !empty($company_audit_list)){
				$word_array[] = "公司简称";
			}
			//            if(base_lib_BaseUtils::nullOrEmpty($company['property_id'])){
			//                $word_array[] = "公司性质";
			//            }
			if(base_lib_BaseUtils::nullOrEmpty($company['calling_ids'])){
				$word_array[] = "公司行业";
			}
			if(base_lib_BaseUtils::nullOrEmpty($company['address'])){
				$word_array[] = "详细地址";
			}
			if(base_lib_BaseUtils::nullOrEmpty($company['area_id'])){
				$word_array[] = "公司地址";
			}
			if(base_lib_BaseUtils::nullOrEmpty($company['linkman'])){
				$word_array[] = "联系人";
			}
			if(base_lib_BaseUtils::nullOrEmpty($company['link_tel']) && base_lib_BaseUtils::nullOrEmpty($company['link_mobile'])){ //企业座机和手机必填一个
				$word_array[] = "联系方式";
			}
			//                if(base_lib_BaseUtils::nullOrEmpty($company['company_logo_path'])){
			//                    $word_array[] = "公司LOGO";
			//                }
			if(base_lib_BaseUtils::nullOrEmpty($company['info'])){
				$word_array[] = "公司介绍";
			}
		}
		return $word_array;
	}
	/*
	 * 获得企业信用积分
	 */
	private function _getCompanyScore($company_id){
		$comment_service = new base_service_part_company_companycomment();
		$count = base_lib_BaseUtils::array_key_assoc($comment_service->getCommentCount($company_id),'level');
		$total_score = 100;//初始分数100分
		foreach ($count as $key => $value) {
			$total_score+=$value['score'];
		}
		return $total_score;
	}
	
	/*
	 *@desc判断该企业是否有行业类别 
	 **/
	private function _matchCompanyCalling(){
		/*==============判断该企业是否有行业类别===================*/
		//修改行业信息
		$companyService = new base_service_company_company(); 
		$company = $companyService->getCompany($this->_userid, true, 'company_id,is_effect,com_level,start_time,end_time,effect_job_end_time,hr_manager_sex,hr_tel,company_logo_path,company_bright_spot,calling_id,calling_ids');
		$service_companycalling = new base_service_company_companycalling();
		$latest_setting = $service_companycalling->select("company_id={$this->_userid} and is_effect=1","_stamp_remark","","order by company_calling_id desc limit 1")->items;
		//未在招聘中且行业匹配到特殊行业的企业
		$matchTime = strtotime('2015-07-09 18:00:00');//行业调整上线时间
		$is_alter = true;//是否已选择行业,默认未选择
		if(!empty($latest_setting) && strtotime($latest_setting[0]['_stamp_remark']) > $matchTime){
			$is_alter = false;
		}
		$thistime = time();//当前时间
		$calling_mark = false;//默认为招聘中企业
		$notMarkCallingids = array('11'=>11,'50'=>50);
		if($is_alter && $company['is_effect'] && $company['calling_ids'] && $company["effect_job_end_time"]>$thistime && strtotime($company["end_time"])>$thistime){
			$calling_ids = explode(',',$company['calling_ids']);
			foreach($calling_ids as $v){
				if($notMarkCallingids[$v]){
					$calling_mark = true;
				}
			}
		}
		return $calling_mark;
	}
	
	/*
	 *@desc 更新需要同步到后台的兼职单位信息 
	 **/
	private  function update_crm_partcompany_syncinfo($company_id){
	         //
	        $service_partjob=new base_service_part_company_job();
			$result=$service_partjob->get_JobEndTime_And_ValidJobNums($company_id);
			if($result!==false){
			   $service_partcompany= new base_service_part_company_partcompany();
			   $service_partcompany->editPartCompany($company_id,$result);
			}
	}

	/**
	 * 返回错误提示页面
	 * @param  string $msg 错误信息
	 * @return html      错误提示页面
	 */
	private function _returnFailPage($msg) {
		$this->_aParams['msg'] = $msg;
		$this->_aParams['par'] = '兼职招聘';
		$this->_aParams['cur'] = '职位管理';
		return $this->render('common/tipsmsg.html', $this->_aParams);
	}
}