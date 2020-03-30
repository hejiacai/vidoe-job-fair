<?php
/**
 * 
 * 分享简历查看
 * @author huangwentao
 *
 */
class controller_share extends components_cbasepage {	
    static $key = "wzeh3iyaun";
    protected $cache_prefix = 'app_com_cache_share_code';
    public $isshowresumeinfo = false;
    function __construct() {
        parent::__construct(false);
    }

    //查看分享代码
    public function pageIndex(){
        $path_data      = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_id      = base_lib_BaseUtils::getStr($path_data["key"],"int",0);
        $company_flag   = base_lib_BaseUtils::getStr($path_data["flag"],"string","");
        $account_id     = base_lib_BaseUtils::getStr($path_data["ac"],"int",0);

        if(empty($account_id) || empty($company_flag) || empty($resume_id)){
            $this->_aParams["msg"] = "该页面失效";
            $this->_aParams["url"] = "/";

            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
        $this->cache_prefix = $this->cache_prefix.$resume_id."_".$account_id;
        $cache_obj              = new base_lib_Cache('redis');

        $company_id = base_lib_Rewrite::getId("company", $company_flag);
        $has_cache_data = $cache_obj->get($this->cache_prefix);
        if(empty($has_cache_data)){
            $this->_aParams["msg"] = "该页面失效";
            $this->_aParams["url"] = "/";

            return $this->render("./common/showmsgpopedom.html", $this->_aParams);

        }
        $service_account = new base_service_company_account();
        $account_info    = $service_account->getAccount($account_id, "account_id,company_id");
        if(empty($account_info) || $account_info["company_id"] != $company_id){
            $this->_aParams["msg"] = "该页面失效";
            $this->_aParams["url"] = "/";

            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }

        $service_resource = base_service_company_resources_resources::getInstance($company_id);
        list($is_audit,$audit_params) = $service_resource->getCompanyAuditStatus();
        $isshowresumeinfo = false;
        if(in_array($is_audit, array(1,4,5))){
            $isshowresumeinfo = true;
        }
        $this->_fill($resume_id,$isshowresumeinfo);
        
        return $this->render("./resume/shareresume.html",$this->_aParams);
    }

        
    private function _fill($resume_id,$isshowresumeinfo) {
// 		未通过认证时，需要隐藏的简历信息		
// 		1.个人照片		
// 		2.工作经历中 公司名称、工作职责		
// 		3.项目经历		
// 		5.教育经历 -只显示最近一次教育经历，其他隐藏		
// 		6.联系方式		
// 		7.实践经验		
// 		8.个人头像
		
		if ($resume_id == 0) {
			return;
		}

		$service_resume = new base_service_person_resume_resume();
		$service_resume->dbquery = true; //load data from subordinate database
		//获取简历
		$resume = $service_resume->getResume($resume_id, 'resume_id,person_id,degree_id,station,'
		                                               . 'is_salary_show,salary,appraise,expect_job_level,'
		                                               . 'is_not_accept_lower_job_level,is_not_accept_lower_salary,'
		                                               . 'job_type,is_accept_parttime,update_time,point');


		if (base_lib_BaseUtils::nullOrEmpty($resume)) {
			return;
		}

		$service_person = new base_service_person_person();
		$service_person->dbquery = true; //load data from subordinate database
		//获取用户
		$person = $service_person->getPerson($resume['person_id'], 'person_id,user_name,sex,email,cur_area_id,'
		                                                         . 'has_big_photo,big_photo,photo,photo_open,'
		                                                         . 'name_open,birthday2,mobile_phone,mobile_phone_is_validation,'
		                                                         . 'email_is_validation,telephone,qq,qq_open,tel_open,email_open,'
		                                                         . 'stature,marriage,avoirdupois,political_status,native_place_id,'
		                                                         . 'fertility_circumstance,start_work,login_time,job_state_id,'
		                                                         . 'accession_time,person_class,password');
		if (base_lib_BaseUtils::nullOrEmpty($person)) {
			return;
		}

		$this->_aParams['resume_id'] = $resume_id;
		$this->_aParams['person_id'] = $person['person_id'];
		
		$this->_aParams['last_login_time'] = date('Y-m-d H:i', strtotime($person['login_time']));


		//---------------- @update 新增贫困地区 2019-5-16-----------------
		$this->getPersonExt($person['person_id']);
		//姓名
		if ($isshowresumeinfo) {
			$this->_aParams['user_name'] = $person['user_name'];
		} else {
			$sex_name = $person['sex'] == 1 ? '先生' : '女士';
			$this->_aParams['user_name'] = mb_substr($person['user_name'], 0, 1, 'utf-8') . $sex_name;
		}

		$this->_aParams['appraise'] = $resume['appraise'];
		//头像，性别。年龄，学历，工作经验，职位类别，接受兼职，低薪资，低岗位，自我评价
		$this->_aParams['avatar'] = ($is_down?base_lib_Constant::YUN_ASSETS_URL:base_lib_Constant::YUN_ASSETS_URL_NO_HTTP).'/img/common/user120_150.jpg';
		if ($person['photo_open'] != '0' && $isshowresumeinfo) {//如果 photo_open 为null也会进
			if (!base_lib_BaseUtils::nullOrEmpty($person['photo'])) {
				$avatar =  ($is_down?base_lib_Constant::YUN_ASSETS_URL:base_lib_Constant::YUN_ASSETS_URL_NO_HTTP)."{$person['photo']}";
				if ($person['has_big_photo'] == '1') {
					$avatar_big =  "{$person['big_photo']}";
					$this->_aParams['avatar_big'] = $avatar_big;
					$this->_aParams['avatar'] = $avatar;
				} else {
					$this->_aParams['avatar'] = $avatar;
				}
			}
		}

		$this->_aParams['sex'] = $person['sex'];
		$this->_aParams['age'] = base_lib_TimeUtil::ceil_diff_year($person['birthday2']) . '岁';
		$this->_aParams['degree'] = $resume['degree_id'];
		$this->_aParams['job_state_id'] = $person['job_state_id'];
		$this->_aParams['job_state_text'] = (new base_service_common_applystatus())->getName($person['job_state_id']);
		$this->_aParams['accession_time'] = $person['accession_time'];
		
		$basic_start_work_year = base_lib_TimeUtil::date_diff_month($person['start_work']);
		$workY = floor($basic_start_work_year / 12);
		$workM = intval($basic_start_work_year % 12);
		
		if ($workY <= 0 && $workM <= 6 && $workM >= -6) {
			$basic_start_work_year = '应届毕业生';
		} else if ($workY == 0 && $workM > 6) {
			$basic_start_work_year = $workM . '个月工作经验';
		} else if ($basic_start_work_year < -6) {
			$basic_start_work_year = '目前在读';
		} else {
			$basic_start_work_year = $workY . '年工作经验';
		}

		$this->_aParams['work_year'] = $basic_start_work_year;
		$this->_aParams['job_type'] = $resume['job_type'];
		$this->_aParams['is_accept_parttime'] = $resume['is_accept_parttime'];
		$this->_aParams['is_not_accept_lower_salary'] = $resume['is_not_accept_lower_salary'];
		$this->_aParams['is_not_accept_lower_job_level'] = $resume['is_not_accept_lower_job_level'];
		$this->_aParams['appraise'] = $resume['appraise'];
		
		//当前所在地
		$service_area = new base_service_common_area();
		$cur_area_name = '';
		if ($person['cur_area_id'] != '') {
			$cur_area_arr = $service_area->getTopAreaByAreaID($person['cur_area_id']);
			$cur_area_count = count($cur_area_arr);
			for ($j = $cur_area_count - 1; $j >= 0; $j--) {
				if ($j == 0) {
					$cur_area_name .= $cur_area_arr[ $j ]['area_name'];
				} else {
					$cur_area_name .= $cur_area_arr[ $j ]['area_name'] . '-';
				}
			}
		}

		$this->_aParams['cur_area_name'] = $cur_area_name;
		$this->_aParams['exp_job'] = $resume['station'];
		$this->_aParams['exp_job_level'] = $resume['expect_job_level'];
		
		//用于邮件简历
		if ($chkIsHideSalary == false) {
			if ($resume['is_salary_show'] == 0) {
				$this->_aParams['exp_salary'] = $resume['salary'];
			} else {
				$this->_aParams['exp_salary'] = 'negotiable';
			}
		}
		
		//获取意向行业类别
		$service_callingexp = new base_service_person_resume_callingexp();
		$service_calling = new base_service_common_calling();
		$callings = $service_callingexp->getResumeCallingexpList($resume['resume_id'], 'resume_id,calling_id');
		$calling_items = $callings->items;
		if (!base_lib_BaseUtils::nullOrEmpty($calling_items)) {
			$str_expect_callings = '';
			for ($g = 0; $g < sizeof($calling_items); $g++) {
				if (sizeof($calling_items) == 1 || $g == sizeof($calling_items) - 1) {
					$str_expect_callings .= $service_calling->getCallingName($calling_items[ $g ]['calling_id']);
				} else {
					$str_expect_callings .= $service_calling->getCallingName($calling_items[ $g ]['calling_id']) . ';';
				}
			}
			$this->_aParams['str_expect_callings'] = $str_expect_callings;
		}
		
		//获取意向地区
		$service_areaexp = new base_service_person_areaexp();
		$service_areaexp->dbquery = true; //load data from subordinate database
		$areas = $service_areaexp->getResumeAreaexpList($resume['resume_id'], 'person_id,resume_id,area_id');
		$area_items = $areas->items;
		if (!base_lib_BaseUtils::nullOrEmpty($area_items)) {
			$area_name = '';
			$area_ids = '';
			$area_ids_arr = array ();
			for ($i = 0; $i < sizeof($area_items); $i++) {
				$area = $service_area->getArea($area_items[ $i ]['area_id'], false);
				if ($i == sizeof($area_items) - 1) {
					$area_ids .= $area['area_id'];
				} else {
					$area_ids .= $area['area_id'] . ',';
				}
				array_push($area_ids_arr, $area['area_id']);
			}
			$area_name = $this->getSpecialAreaName($service_area, $area_ids_arr, $area_ids);
		}
		if (!empty($area_name)) {
			$area_name_array = explode(";", $area_name);
			$area_name_array = array_filter($area_name_array, function ($v) {
				if (empty($v))
					return false;
				
				return true;
			});
			$area_name = !empty($area_name_array) ? implode(";", $area_name_array) : "不限";
		}
		$this->_aParams['exp_area_names'] = $area_name;
		
		//获取意向职位类别
		$service_jobsort = new base_service_common_jobsort();
		$service_jobsortexp = new base_service_person_resume_jobsortexp();
		$jobsorts = $service_jobsortexp->getResumeJobsortexpList($resume_id, 'resume_id,jobsort');
		$jobsort_items = $jobsorts->items;
		if (!base_lib_BaseUtils::nullOrEmpty($jobsort_items)) {
			$str_expect_jobsorts = '';
			$expect_jobsort_ids = '';
			for ($i = 0; $i < sizeof($jobsort_items); $i++) {
				if (sizeof($jobsort_items) == 1 || $i == sizeof($jobsort_items) - 1) {
					$str_expect_jobsorts .= $service_jobsort->getJobsortName($jobsort_items[ $i ]['jobsort']);
					$expect_jobsort_ids .= $jobsort_items[ $i ]['jobsort'];
				} else {
					$str_expect_jobsorts .= $service_jobsort->getJobsortName($jobsort_items[ $i ]['jobsort']) . ',';
					$expect_jobsort_ids .= $jobsort_items[ $i ]['jobsort'] . ',';
				}
			}
			$this->_aParams['expect_jobsort_ids'] = $expect_jobsort_ids;
			$this->_aParams['str_expect_jobsorts'] = $str_expect_jobsorts;
		}
        /************联系方式****************************/
		
		//户籍
		$native_place_name = '';
		if ($person['native_place_id'] != '') {
			$native_place = new base_service_common_area();
			$native_place_arr = $native_place->getTopAreaByAreaID($person['native_place_id']);
			$native_place_arr_count = count($native_place_arr);
			for ($j = $native_place_arr_count - 1; $j >= 0; $j--) {
				if ($j == 0) {
					$native_place_name .= $native_place_arr[ $j ]['area_name'];
				} else {
					$native_place_name .= $native_place_arr[ $j ]['area_name'] . '-';
				}
			}
		}
		
		$this->_aParams['native_place_name'] = $native_place_name;
		//政治面貌
		$this->_aParams['political_status'] = $person['political_status'];
		//身高
		$this->_aParams['stature'] = $person['stature'];
		//体重
		$this->_aParams['avoirdupois'] = $person['avoirdupois'];
		
		//工作经验板块
		$service_work = new base_service_person_resume_work();
		$service_work->dbquery = true; //load data from subordinate database
		$works = $service_work->getResumeWorkList($resume['resume_id'], 'start_time,end_time,company_name,calling_id,com_property,com_size,station,is_salary_show,work_content,salary_month,job_type,job_level,department,subordinate,is_creator,leave_reason,manage_department,report_man');
		$works_items = $works->items;
		$this->_aParams['resume_works'] = array ();
		if (!base_lib_BaseUtils::nullOrEmpty($works_items)) {
			for ($j = 0; $j < count($works_items); $j++) {
				//每份工作经验时间
				$work_time_diff = (base_lib_TimeUtil::date_diff_year2($works_items[ $j ]['start_time'], $works_items[ $j ]['end_time']));
				$works_items[ $j ]['work_time'] = $work_time_diff;
				//开始结束时间
				$works_items[ $j ]['start_time'] = base_lib_BaseUtils::nullOrEmpty($works_items[ $j ]['start_time']) ? '开始时间未填写' : date('Y.m', strtotime($works_items[ $j ]['start_time']));
				$works_items[ $j ]['end_time'] = base_lib_BaseUtils::nullOrEmpty($works_items[ $j ]['end_time']) ? '至今' : date('Y.m', strtotime($works_items[ $j ]['end_time']));
				
				if (!$isshowresumeinfo) {
					$works_items[ $j ]['company_name'] = "*****公司";
					$works_items[ $j ]['work_content'] = "营业执照认证后可查看";
				}
				
			}
			$this->_aParams['resume_works'] = $works_items;
		}
		
		//教育经历&培训经历
		$service_edu = new base_service_person_resume_edu();
		$service_edu->dbquery = true; //load data from subordinate database
		$edus = $service_edu->getResumeEduList($resume['resume_id'], 'start_time,end_time,school,major_desc,degree,edu_detail,duty,school_score');
		$edu_items = $edus->items;
		foreach ($edu_items as &$edu_temp){
			if($edu_temp['school_score']){
				$school_score = explode(',',$edu_temp['school_score']);
				$edu_temp['school_score'] = "{$school_score[0]}/{$school_score[1]}";
			}
		}
		$this->_aParams['hasEdusOrTrains'] = false;
		$this->_aParams['resume_edus'] = $isshowresumeinfo ? $edu_items : array_slice($edu_items, 0, 1);//未通过认证时教育经历 -只显示最近一次教育经历，其他隐藏 sx 2015.7.16
		if ($isshowresumeinfo) {//未通过认证时教育经历 -只显示最近一次教育经历，其他隐藏 sx 2015.7.16
			$service_train = new base_service_person_resume_train();
			$service_train->dbquery = true; //load data from subordinate database
			$trains = $service_train->getResumeTrainList($resume['resume_id'], 'start_time,end_time,institution,course,certificate,train_detail');
			$train_items = $trains->items;
			$this->_aParams['resume_trains'] = $train_items;
			if (count($edu_items) > 0 || count($train_items) > 0) {
				$this->_aParams['hasEdusOrTrains'] = true;
			}
		}
		$this->_aParams['resume_projects'] = array ();
		
		//获奖经历
		$winningrecord_service = new base_service_person_resume_winningrecord();
		$resume_winningrecord = $winningrecord_service->getWinningsByResumeId($resume['resume_id']);
		if ($resume_winningrecord)
			foreach ($resume_winningrecord as &$winnin) {
				$winnin['winning_time'] and $winnin['winning_time'] = date('Y.m', strtotime($winnin['winning_time']));
			}
		$this->_aParams['resume_winningrecord'] = $resume_winningrecord;
		
		//项目经验
		if ($isshowresumeinfo) {//未通过认证时，需要隐藏的简历信息 sx 2015.7.16
			$service_project = new base_service_person_resume_project();
			$service_project->dbquery = true; //load data from subordinate database
			$projects = $service_project->getResumeProjectList($resume['resume_id'], 'start_time,end_time,project_name,project_detail,main_duty,duty');
			$project_items = $projects->items;
			if (!base_lib_BaseUtils::nullOrEmpty($project_items)) {
				for ($a = 0; $a < count($project_items); $a++) {
					//每份工作经验时间
					$project_time_diff = (base_lib_TimeUtil::date_diff_year2($project_items[ $a ]['start_time'], $project_items[ $a ]['end_time']));
					$project_items[ $a ]['project_time'] = $project_time_diff;
					//开始结束时间
					$project_items[ $a ]['start_time'] = base_lib_BaseUtils::nullOrEmpty($project_items[ $a ]['start_time']) ? '开始时间未填写' : date('Y.m', strtotime($project_items[ $a ]['start_time']));
					$project_items[ $a ]['end_time'] = base_lib_BaseUtils::nullOrEmpty($project_items[ $a ]['end_time']) ? '至今' : date('Y.m', strtotime($project_items[ $a ]['end_time']));
				}
				$this->_aParams['resume_projects'] = $project_items;
			}
		}
		
		//证书
		$service_certificate = new base_service_person_resume_certificate();
		$service_certificate->dbquery = true; //load data from subordinate database
		$certificates = $service_certificate->getResumeCertificateList($resume['resume_id'], 'certificate_name,certificate_no,gain_time,score');
		$certificates_items = $certificates->items;
		$this->_aParams['resume_certificates'] = array ();
		if (!base_lib_BaseUtils::nullOrEmpty($certificates_items)) {
			for ($k = 0; $k < count($certificates_items); $k++) {
				$grin_time = strtotime($certificates_items[ $k ]['gain_time']);
				$certificates_items[ $k ]['gain_time'] = date('Y年m月', $grin_time);
			}
			$this->_aParams['resume_certificates'] = $certificates_items;
		}
		
		//我的作品
		$service_achievement = new base_service_person_resume_achievement();
		$service_achievement->dbquery = true; //load data from subordinate database
		
		$service_attachment = new base_service_person_resume_achievementattachment();
		$service_attachment->dbquery = true; //load data from subordinate database
		$achievements = $service_achievement->getResumeAchievementList($resume['resume_id'], 'achievement_id,resume_id,achievement_name,start_time,end_time,achievement_description');
		$achievement_item = $achievements->items;
		
		$this->_aParams['resume_achievements'] = array ();
		if (!base_lib_BaseUtils::nullOrEmpty($achievement_item)) {
			// 读取配置xml文件
			$xml = SXML::load('../config/person/ResumeAttachment.xml');
			if (!is_null($xml)) {
				$attachment_virt_path = ($is_down?base_lib_Constant::YUN_ASSETS_URL:base_lib_Constant::YUN_ASSETS_URL_NO_HTTP) . '/' . $xml->AchievementAttachmentVirtualName . '/';
				$this->_aParams['attachment_virt_path'] = $attachment_virt_path;
				$achievement_thumb_suffix = $xml->ThumbSuffix;
				$achievement_thumb_ext = $xml->AchievementImageExtensions;
			}
			for ($j2 = 0; $j2 < count($achievement_item); $j2++) {
				$attachments = $service_attachment->getResumeAttachmentList($achievement_item[ $j2 ]['achievement_id'], 'achievement_id,achievement_path,attachment_name,status,achievement_extension');
				$attachment_item = $attachments->items;
				if (!base_lib_BaseUtils::nullOrEmpty($attachment_item)) {
					$achievement_id = $achievement_item[ $j2 ]['achievement_id'];
					for ($k = 0; $k < count($attachment_item); $k++) {
						$fileParts = pathinfo($attachment_item[ $k ]['achievement_path']);
						if (count(explode($fileParts['extension'], $achievement_thumb_ext)) > 1) {
							//$attachment_item[$k]['achievement_thumb_path'] =$fileParts['dirname'].'/'.$fileParts['filename'].$achievement_thumb_suffix.'.'.$fileParts['extension'];
							$imgsrc = base_lib_BaseUtils::getThumbImg($xml->AchievementAttachmentVirtualName . '/' . $attachment_item[ $k ]['achievement_path'], 200, 160,$is_down);
							$attachment_item[ $k ]['achievement_thumb_path'] = $imgsrc;
						}
					}
					$attachment_temp_item = $this->array_sort($attachment_item, 'achievement_extension');
					$achievement_item[ $j2 ]['attachments'] = $attachment_temp_item;
					//$this->_aParams['achievement_attachments'.$achievement_id] = $attachment_temp_item;
				}
			}
			$this->_aParams['resume_achievements'] = $achievement_item;
		}
		
		//附加信息
		$service_append = new base_service_person_resume_append();
		$service_append->dbquery = true; //load data from subordinate database
		$appends = $service_append->getResumeAppendList($resume['resume_id'], 'topic_desc,content');
		$append_item = $appends->items;
		$this->_aParams['resume_appends'] = array ();
		if (!base_lib_BaseUtils::nullOrEmpty($append_item)) {
			$this->_aParams['resume_appends'] = $append_item;
		}
		/**===============照片===start=================**/
		$album_service = new base_service_person_resume_album();
		$albums = $album_service->getAlbums($resume_id, "id,photo");
		$this->_aParams['resume_albums'] = $albums;
		/**===============照片=== end =================**/
		//技能
		$service_skill = new base_service_person_resume_skill();
		$service_skill->dbquery = true; //load data from subordinate database
		$skill = $service_skill->getResumeSkillList($resume['resume_id'], 'skill_name,skill_level');
		$skill_items = $skill->items;
		$this->_aParams['resume_skills'] = array ();
		if (!base_lib_BaseUtils::nullOrEmpty($skill_items)) {
			$this->_aParams['resume_skills'] = $skill_items;
		}
		
		//语言能力
		$service_language = new base_service_person_resume_language();
		$service_language->dbquery = true; //load data from subordinate database
		$service_languagecert = new base_service_person_resume_languagecert();
		$service_languagecert->dbquery = true; //load data from subordinate database
		$resume_languages = $service_language->getLanguageList($resume_id, 'resume_id,language_id,language_type,skill_level')->items;
		foreach ($resume_languages as &$resume_language_item) {
			$resume_language_item['certificates'] = $service_languagecert->getCertList($resume_language_item['language_id'], 'cert_id,language_id,cert_name')->items;
		}
		$this->_aParams['resume_languages'] = $resume_languages;
		
		//实践经验
		if ($isshowresumeinfo) {
			$service_practice = new base_service_person_resume_practice();
			$service_practice->dbquery = true; //load data from subordinate database
			$practices = $service_practice->getResumePracticeList($resume['resume_id'], 'start_time,end_time,practice_name,practice_detail');
			$practice_items = $practices->items;
			$this->_aParams['resume_practices'] = array ();
			if (!base_lib_BaseUtils::nullOrEmpty($practice_items)) {
				for ($a = 0; $a < count($practice_items); $a++) {
					//每份工作经验时间
					$practice_time_diff = (base_lib_TimeUtil::date_diff_year2($practice_items[ $a ]['start_time'], $practice_items[ $a ]['end_time']));
					$practice_items[ $a ]['practice_time'] = base_lib_BaseUtils::nullOrEmpty($practice_time_diff) ? '1个月' : $practice_time_diff;
					//开始结束时间
					$practice_items[ $a ]['start_time'] = base_lib_BaseUtils::nullOrEmpty($practice_items[ $a ]['start_time']) ? '开始时间未填写' : date('Y.m', strtotime($practice_items[ $a ]['start_time']));
					$practice_items[ $a ]['end_time'] = base_lib_BaseUtils::nullOrEmpty($practice_items[ $a ]['end_time']) ? '至今' : date('Y.m', strtotime($practice_items[ $a ]['end_time']));
				}
				$this->_aParams['resume_practices'] = $practice_items;
			}
		}
		/**===============照片===start=================**/
		$album_service = new base_service_person_resume_album();
		$albums = $album_service->getAlbums($resume_id, "id,photo");
		$this->_aParams['resume_albums'] = $albums;
		/**===============照片=== end =================**/
		

		
		
		$service_job_level = new base_service_common_joblevel();
		//岗位级别   实习/见习  普通员工  高级/资深（非管理岗）的编号
		$this->_aParams['job_level_practice'] = $service_job_level->practice;
		$this->_aParams['job_level_ordinary'] = $service_job_level->ordinary;
		$this->_aParams['job_level_senior'] = $service_job_level->senior;
		$service_job_type = new base_service_common_jobtype();
		//工作类型 全职的编号ID
		$this->_aParams['job_type_fulltime'] = $service_job_type->fulltime;
		
		$this->_aParams['isshowresumeinfo'] = $isshowresumeinfo;
		

	}
    
    private function getPersonExt($person_id)
    {
        if (empty($person_id))
            return false;

        $service_person_exp            = new base_service_person_personext();
        $person_exp                    = $service_person_exp->getData($person_id, 'person_id,is_poor_family,family_address');
        $this->_aParams['_person_exp'] = $person_exp;
        return true;
    }  
    
    /**
	 * 获取地区名称(特殊  例如：重庆-主城区,北京-五环以内)
	 * @param $service_area       实例化对象
	 * @param array $area_ids_arr 选择的地区数组
	 * @param string $area_ids    选择的地区字符串
	 */
	private function getSpecialAreaName($service_area, $area_ids_arr, $area_ids) {
		$area_name = '';
		if (count($area_ids_arr) <= 0) {
			return $area_name;
		}
		$xml = SXML::load('../config/person/Person.xml');
		if (!is_null($xml)) {
			$cq_mian_city = $xml->CQMainCity;
			$cq_other_counties = $xml->CQOtherCounties;
			$bj_rings_within = $xml->BJRingsWithin;
			$bj_rings_without = $xml->BJRingsWithout;
			$sh_outer_within = $xml->SHOuterWithin;
			$sh_suburbs = $xml->SHSuburbs;
			$tj_mian_city = $xml->TJMainCity;
			$tj_other_counties = $xml->TJOtherCounties;
		}
		$temp_area_ids_arr = array ();
		//重庆
		$cq_mian_city_arr = explode(',', $cq_mian_city);
		$cq_other_counties_arr = explode(',', $cq_other_counties);
		
		$intersect_cqmain = implode(',', array_intersect($cq_mian_city_arr, $area_ids_arr)) == $cq_mian_city;
		$intersect_cqother = implode(',', array_intersect($cq_other_counties_arr, $area_ids_arr)) == $cq_other_counties;
		
		if ($intersect_cqmain && $intersect_cqother) {
			$area_name .= '重庆-主城区;重庆-周边区县;';
			$temp_area_ids_arr = array_diff($area_ids_arr, $cq_mian_city_arr);
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $cq_other_counties_arr);
		} elseif ($intersect_cqmain) {
			$area_name .= '重庆-主城区;';
			$temp_area_ids_arr = array_diff($area_ids_arr, $cq_mian_city_arr);
		} elseif ($intersect_cqother) {
			$area_name .= '重庆-周边区县;';
			$temp_area_ids_arr = array_diff($area_ids_arr, $cq_other_counties_arr);
		}
		$temp_area_ids_arr = count($temp_area_ids_arr) <= 0 && empty($area_name) ? $area_ids_arr : $temp_area_ids_arr;
		
		//北京
		$bj_mian_city_arr = explode(',', $bj_rings_within);
		$bj_other_counties_arr = explode(',', $bj_rings_without);
		
		$intersect_bjmain = implode(',', array_intersect($bj_mian_city_arr, $temp_area_ids_arr)) == $bj_rings_within;
		$intersect_bjother = implode(',', array_intersect($bj_other_counties_arr, $temp_area_ids_arr)) == $bj_rings_without;
		
		if ($intersect_bjmain && intersect_bjother) {
			$area_name .= '北京-五环以内;北京-五环以外;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $bj_mian_city_arr);
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $bj_other_counties_arr);
		} elseif ($intersect_bjmain) {
			$area_name .= '北京-五环以内;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $bj_mian_city_arr);
		} elseif ($intersect_bjother) {
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $bj_other_counties_arr);
			$area_name .= '北京-五环以外;';
		}
		//上海
		$sh_mian_city_arr = explode(',', $sh_outer_within);
		$sh_other_counties_arr = explode(',', $sh_suburbs);
		
		$intersect_shmain = implode(',', array_intersect($sh_mian_city_arr, $temp_area_ids_arr)) == $sh_outer_within;
		$intersect_shother = implode(',', array_intersect($sh_other_counties_arr, $temp_area_ids_arr)) == $sh_suburbs;
		
		if ($intersect_shmain && $intersect_shother) {
			$area_name .= '上海-外环以内;上海-郊区/县;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $sh_mian_city_arr);
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $sh_other_counties_arr);
		} elseif ($intersect_shmain) {
			$area_name .= '上海-外环以内;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $sh_mian_city_arr);
		} elseif ($intersect_shother) {
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $sh_other_counties_arr);
			$area_name .= '上海-郊区/县;';
		}
		//天津
		$tj_mian_city_arr = explode(',', $tj_mian_city);
		$tj_other_counties_arr = explode(',', $tj_other_counties);
		
		$intersect_tjmain = implode(',', array_intersect($tj_mian_city_arr, $temp_area_ids_arr)) == $tj_mian_city;
		$intersect_tjother = implode(',', array_intersect($tj_other_counties_arr, $temp_area_ids_arr)) == $tj_other_counties;
		
		if ($intersect_tjmain && $intersect_tjother) {
			$area_name .= '天津-主城区;天津-周边区县;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $tj_mian_city_arr);
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $tj_other_counties_arr);
		} elseif ($intersect_tjmain) {
			$area_name .= '天津-主城区;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $tj_mian_city_arr);
		} elseif ($intersect_tjother) {
			$temp_area_ids_arr = array_diff($temp_area_ids_arr, $tj_other_counties_arr);
			$area_name .= '天津-周边区县;';
		}
		$temp_area_ids_arr = array_merge($temp_area_ids_arr);
		if (count($temp_area_ids_arr) == 0) {
			$area_name = mb_substr($area_name, 0, mb_strlen($area_name, 'utf-8') - 1);
		}
		for ($m2 = 0; $m2 < count($temp_area_ids_arr); $m2++) {
			$area_temp_name = $service_area->getArea($temp_area_ids_arr[ $m2 ]);
			if ($m2 == sizeof($temp_area_ids_arr) - 1) {
				$area_name .= $area_temp_name;
			} else {
				$area_name .= $area_temp_name . ';';
			}
		}
		
		return $area_name;
	}
	
	/**
	 * 数组排序 (自定义附件排序)
	 * @param array $arr   需排序的数组
	 * @param string $keys 排序的字段
	 */
	private function array_sort($arr, $keys) {
		$new_array = array ();
		if ($keys == 'achievement_extension') {
			$z = 1;
			foreach ($arr as $k => $v) {
				if (count(explode($arr[ $k ]['achievement_extension'], '.jpg,.jpeg,.gif,.bmp,.png')) > 1) {
					if ($z > 1) {
						$new_array[ $k - ($z - 1) ] = $arr[ $k ];
					} else {
						$new_array[ $k ] = $arr[ $k ];
					}
				} else {
					$new_array[ count($arr) - $z ] = $arr[ $k ];
					$z++;
				}
			}
			ksort($new_array);
			
			return $new_array;
		} else {
			ksort($arr);
			
			return $arr;
		}
	}
	
	
	/**
	 * 数组查询
	 * @param array $arr
	 * @param string $key
	 * @param string $value
	 */
	private function arrayFind($arr, $property, $value) {
		$obj = null;
		foreach ($arr as $item) {
			if ($item[ $property ] == $value) {
				$obj = $item;
				break;
			}
		}
		
		return $obj;
	}
    
    public function pageSharelinkImg($inPath){
        $path_data   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_id      = base_lib_BaseUtils::getStr($path_data["key"],"int",0);
        $company_flag   = base_lib_BaseUtils::getStr($path_data["flag"],"string","");
        $account_id     = base_lib_BaseUtils::getStr($path_data["ac"],"int",0);
		$share_url = base_lib_Constant::APP_MOBILE_URL."/share/NewIndex/?key=".$resume_id."&ac=".$account_id."&flag=".$company_flag;
		SQrcode::png($share_url);
    }
	
}
?>