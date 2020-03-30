<?php

class controller_nominate extends components_cbasepage {

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}

	private $_data = ["01" => "互联网技术类", "05" => "销售类"];

	public function pageIndex($inPath) {
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$jobsort  = base_lib_BaseUtils::getStr($params['jobsort'], 'string', null);
		$uniqid   = base_lib_BaseUtils::getStr($params['uniqid'], 'string', '');
		$cur_page = base_lib_BaseUtils::getStr($params['page'], 'int', 1);

		$this->_aParams['jobsort'] = $jobsort;
		$this->_aParams['uniqid']  = $uniqid;

		$company_resources = base_service_company_resources_resources::getInstance($this->_userid);

		$nominate_service = base_service_company_nominate_nominate::getInstances();
		$nominates = $nominate_service->getNominateList($company_resources->all_accounts, $field="person_id,job_id", $cur_page, base_lib_Constant::PAGE_SIZE, $uniqid, $jobsort);

		$nominate_jobsorts = $nominate_service->getNominateJobsort($company_resources->all_accounts, $uniqid);
		$common_jobsort = new base_service_common_jobsort();
		foreach ($nominate_jobsorts as &$jobsort) {
			$jobsort['station'] = $this->_data[$jobsort['jobsort']];
			$totalCount += $jobsort['count'];
		}

		$this->_aParams['nominate_jobsorts'] = $nominate_jobsorts;

		$totalSize = $nominates->totalSize;
		$nominates = $nominates->items;

		$this->_aParams['totalSize'] = $totalCount;
		$this->_aParams['resumes']   = $this->_bindResumeDatas($nominates, $jobs);
		$this->_aParams['pager']     = $this->pageBar($totalSize, base_lib_Constant::PAGE_SIZE, $cur_page, $inPath);

		/* 设置为已读 */
		$nominate_service->setWatched($company_resources->all_accounts, $uniqid);

		return $this->render("resume/nominate.html", $this->_aParams);
	}

	public function pageHide($inPath) {
		base_lib_BaseUtils::ssetcookie(['msgpopclose'=>1], 60 * 60);
		echo json_decode(true);
		exit;
	}

	/**
	 * 组装简历数据
	 * @param  array $resume_ids 简历IDs
	 * @param  array $result     solr结果集
	 * @return null
	 */
	private function _bindResumeDatas($nominates, $jobs) {
		if (empty($nominates))
			return [];

		$nominates  = base_lib_BaseUtils::array_key_assoc($nominates, "person_id");
		$person_ids = base_lib_BaseUtils::getProperty($nominates, "person_id");
		$job_ids    = base_lib_BaseUtils::getProperty($nominates, "job_id");

		$job_service = base_service_company_job_job::getInstances();
		$jobs = $job_service->getJobs($job_ids, $field="station,job_id");
		$jobs = base_lib_BaseUtils::array_key_assoc($jobs, "job_id");

		$service_resume = base_service_person_resume_resume::getInstances();
		$resumes = $service_resume->getDefaultResumes($person_ids, "resume_id,resume_name,"
			. "person_id,is_chinese_resume,degree_id,refresh_time,station,appraise")->items;

		/* whether to display resume detail */
		$service_company = base_service_company_company::getInstances();
		$isshowresumeinfo = $service_company->isShowResumeInfo($this->_userid);

		if (!empty($resumes)) {
			$service_person        = new base_service_person_person();
			$service_resume_work   = new base_service_person_resume_work();
			$service_resume_remark = new base_service_company_resume_resumeremark();
			$common_sex            = new base_service_common_sex();
			$common_degree         = new base_service_common_degree();
			$common_area           = new base_service_common_area();
			$common_jobstate 	   = new base_service_common_applystatus();
			$common_accessiontime  = new base_service_common_accessiontime();

			/* get person datas */
			$resume_ids  = base_lib_BaseUtils::getProperty($resumes, 'resume_id');
			$person_data = $service_person->getPersons($person_ids, 'person_id,user_name,'
					. 'sex,birthday2,cur_area_id,start_work,photo,name_open,photo_open,start_work,job_state_id,accession_time,create_time');
			
			$person_list = base_lib_BaseUtils::array_key_assoc($person_data->items, "person_id");

			/* graduate school */ 
			$service_edu = new base_service_person_resume_edu();
			$edu_list = $service_edu->getResumeEdus(implode(",", $resume_ids), 'resume_id,school,major_desc,degree')->items;
			$edu_list = base_lib_BaseUtils::array_key_assoc($edu_list, "resume_id", false);
			
			/* work experience */
			$work_datas = $service_resume_work->getResumeWorks(implode(",", $resume_ids), 'work_id,resume_id,start_time,'
																	. 'end_time,station,company_name')->items;
			foreach ((array)$work_datas as $key => $value) {
				$_resumeId = $value["resume_id"];
				$workslist[$_resumeId][] = array(
					'start_time'    => date('Y/m', strtotime($value['start_time'])),
					'end_time'      => empty($value['end_time']) ? "至今" : date('Y/m', strtotime($value['end_time'])),
					'station'		=> $value['station'],
					'company_name'  => $isshowresumeinfo ? $value['company_name'] : "",
					'last'			=> base_lib_TimeUtil::date_diff_year3(date("Y-m", strtotime($value['start_time'])), (empty($value['end_time']) ? "" : date("Y-m", strtotime($value['end_time']))))
				);
			}

			foreach ($resumes as $i => $resume_info) {
				$resume_id          = $resume_info['resume_id'];
				$person_id          = $resume_info['person_id'];
				$person_info        = $person_list[$person_id];
				$resume_work_info   = $workslist[$resume_id];
				$login_time_info    = $login_time_list[$resume_id]['login_time'];

				$resumes[$i]['nominate_station'] = $jobs[$nominates[$person_id]['job_id']]['station'];

				/* user name fix */
				if ($person_info['name_open'] == 1 && $isshowresumeinfo) {
					$resumes[$i]['user_name'] = $person_info['user_name'];
				} else {
					$sex_name = $person_info['sex'] == 1 ? '先生' : '女士';
					$resumes[$i]['user_name'] = mb_substr($person_info['user_name'], 0, 1, 'utf-8') . $sex_name;
				}

				$default_photo = base_lib_Constant::STYLE_URL . "/img/company/headportrait.png";
				/* photo,name,sex,degre.. */
				if ($person_info['photo_open'] != '0' && $isshowresumeinfo && !empty($person_info['photo'])) {
					$resumes[$i]['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP 
						. (empty($person_info['small_photo']) ? $person_info['photo'] : $person_info['photo']);
					$resumes[$i]['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['photo'];
				} else {
					$resumes[$i]['small_photo'] = $default_photo;
					$resumes[$i]['photo']       = $default_photo;
				}

				$resumes[$i]['create_time']   = base_lib_TimeUtil::to_friend_time($person_info['create_time']);

				$cur_areas = $common_area->getTopAreaByAreaID($person_info['cur_area_id']);

				$resumes[$i]['sex']           = $person_info['sex'];
				$resumes[$i]['sex_text']      = empty($person_info['sex']) ? "" : $common_sex->getName($person_info['sex']);
				$resumes[$i]['cur_area_text'] = empty($person_info['cur_area_id']) ? "" : $cur_areas[0]['area_name'];
				$resumes[$i]['cur_area_full'] = empty($person_info['cur_area_id']) ? "" : $cur_areas[1]['area_name'] . "-" . $cur_areas[0]['area_name'];
				$resumes[$i]['degree_text']   = empty($resume_info['degree_id']) ? "" : $common_degree->getDegree($resume_info['degree_id']);
				$resumes[$i]['age']           = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']) . '岁';
				$resumes[$i]['cur_area']      = $person_info['cur_area_id'];
				$resumes[$i]['job_state_id']  = empty($person_info['job_state_id']) ? "" : $person_info['job_state_id'];

				$job_state = $common_jobstate->getName($person_info['job_state_id']);
				$resumes[$i]['job_state']     = empty($job_state) ? "" : $job_state;

				$accession = $common_accessiontime->getAccession($person_info['accession_time']);
				$resumes[$i]['accession']     = (empty($accession) || $person_info['job_state_id'] == $common_jobstate->notconsider)
					? "" : $accession;

				/* station */
				$resumes[$i]['station']       = empty($resumes[$i]['station']) ? "" : $resumes[$i]['station'];

				/* appraise */
				$word_count = 40;
				$word_count += empty($job_state) ? 17 : 0;
				$word_count += empty($accession) ? 17 : 0;
				$resumes[$i]['appraise']	  = empty($resumes[$i]['appraise']) ? "" : base_lib_BaseUtils::cutstr($resumes[$i]['appraise'], $word_count, 'utf-8', '', '...');

				/* work_experience */
				$resumes[$i]['start_work'] = $this->_fixWorkYear($person_info['start_work']);

				/* recent work station */
				$recent_works = empty($workslist[$resume_id][0]) ? array() : $workslist[$resume_id][0];
				$resumes[$i]['work'] = empty($recent_works['station']) ? "无" : $recent_works['station'];
				$resumes[$i]['company_name'] = empty($recent_works['company_name']) ? "无" : $recent_works['company_name'];
				$resumes[$i]['last'] = empty($recent_works['last']) ? "无" : $recent_works['last'];
					
				/* login time/refresh_time */
				$resumes[$i]['login_time']   = base_lib_TimeUtil::to_friend_time2(date("Y-m-d H:i:s", $login_time_info));
				$resumes[$i]['refresh_time'] = base_lib_TimeUtil::to_friend_time2($resume_info['refresh_time']);

				/* resume summary */
				$edu_info = $edu_list[$resume_id];
				$resumes[$i]['degree']     = empty($edu_info[0]['degree']) ? "" : $edu_info[0]['degree'];
				$resumes[$i]['school']     = empty($edu_info[0]['school']) ? "" : $edu_info[0]['school'];
				$resumes[$i]['major_desc'] = empty($edu_info[0]['major_desc']) ? "" : $edu_info[0]['major_desc'];
				$resumes[$i]['worklist']   = $workslist[$resume_id] ? array_slice($workslist[$resume_id], 0, 2) : array();
			}
		}

		return $resumes;
	}

	/**
	 * 格式化工作经历
	 * @param  datetime $start_work 工作开始时间
	 * @return string             格式化的工作经验
	 */
	private function _fixWorkYear($start_work) {
		$basic_start_work_year = base_lib_TimeUtil::date_diff_month($start_work);
		$workY = floor($basic_start_work_year / 12);
		$workM = intval($basic_start_work_year % 12);

		if ($workY <= 0 && $workM <= 6 && $workM >= -6) {
			$basic_start_work_year = '应届毕业生';
		} else if ($workY == 0 && $workM > 6) {
			$basic_start_work_year = $workM . '个月';
		} else if ($basic_start_work_year < -6) {
			$basic_start_work_year = '目前在读';
		} else {
			$basic_start_work_year = $workY . '年';
		}
		
		if (empty($basic_start_work_year))
			return "应届毕业生";
		else
			return $basic_start_work_year . "工作经验";
	}
}