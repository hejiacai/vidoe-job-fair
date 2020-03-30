<?php
/**
 *
 * @ClassName controller_resumesearch
 * @author fangzhou@huibo.com
 * @date
 */
class controller_resumesearchnew extends components_cbasepage{

	function __construct() {
		parent::__construct();
	}

	/**
	 * 简历搜索主页面
	 * @param  [array] $inPath [url参数]
	 * @return [page]         [resume/searchnew.html]
	 */
	public function pageIndex($inPath) {
        if(!$this->canDo("search_resume")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

		$this->_aParams['job_id']    = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', null);
		$this->_aParams['station']   = base_lib_BaseUtils::getStr($path_data['station'], 'string', "");
		$this->_aParams['from']   = base_lib_BaseUtils::getStr($path_data['from'], 'string', "");//index ： 招聘首页

		/* get all enum params */
		$this->_getEnumTypes();

        /**获取公司默认地址 **/
        $service_company = new base_service_company_company();
        $company_info    = $service_company->getCompany($this->_userid,1,"area_id,net_heap_id,recruit_type");
        $default_area    = "";

        $this->_aParams['can_use_newresume_search'] = $service_company->canProviteNewResumeSearch($this->_userid);//是否可以提供新简历搜索

        if(!empty($company_info["area_id"])){
            $area_top = substr($company_info["area_id"], 0, 2);
            if($area_top == "03" || $area_top == "01" || $area_top == "04" || $area_top == "02"){
                $default_area = $area_top."00";
            }else{
                $default_area = substr($company_info["area_id"], 0, 4);
            }
        }
        //朱经理需求，将成都地区的职位类别搜索项隐藏
        if(strlen($company_info["area_id"])>4){
            $com_area_id = substr($company_info["area_id"], 0, 4);
        }else{
            $com_area_id = $company_info["area_id"];
        }
        if($com_area_id == "1501"){
            $this->_aParams["is_show_jobsort"] = false;
        }else{
            $this->_aParams["is_show_jobsort"] = true;
        }
        $this->_aParams["default_area"] = $default_area;
        $this->_aParams["this_own_man"] = $company_info['net_heap_id'];

        //所有在招职位
        $job_status = new base_service_common_jobstatus();
        $service_company_job_job = new base_service_company_job_job();
        $accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
        $this->_aParams["job_list"] = $service_company_job_job->getJobList($company_resources->all_accounts, '', $job_status->pub, 'job_id,station');
		return $this->render('./resume/searchnew.html', $this->_aParams);
	}

	/**
	 * 简历搜索ajax
	 * @param  array $inPath [url参数]
	 * @return [json]         [格式化简历数据]
	 */
	public function pageSearch($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

		if (!empty($path_data['seekerid'])) {
			/* search by the seek engine */
			list($params, $postvar) = $this->_getSeekerData($path_data['seekerid'], $path_data);

		} else if (!empty($path_data['job_id'])) {
			/* search for job_id */
            if($path_data['from'] == 'self')
                list($params, $postvar) = $this->_fixMatchParamsV1($path_data);
            else
                list($params, $postvar) = $this->_fixMatchParams($path_data);
		} else {
			/* search for datas */
			list($params, $postvar) = $this->_fixParams($path_data);
		}

		//搜索条件不能太长，不然会报错
		if(mb_strlen($postvar['keyword']) > 300) {
			$postvar['keyword'] = mb_substr(trim($postvar['keyword']), 0, 300);
		}
		if(mb_strlen($postvar['last_work_station']) > 300){
			$postvar['last_work_station'] = mb_substr(trim($postvar['last_work_station']),0,300);
		}

		/* build other params */
		$postvar['pageindex']   = base_lib_BaseUtils::getStr($path_data['p'], 'int', 1);
		$postvar['pagesize']    = base_lib_BaseUtils::getStr($path_data['ps'],'int', 20);
		$postvar['hddSortType'] = base_lib_BaseUtils::getStr($path_data['so'], 'int', 1);
		$postvar['photoType'] = base_lib_BaseUtils::getStr($path_data['pht'], 'int', 0); //是否有照片 0 不限： 1 ：有照片 todo solr写上

		$timelimit = base_lib_BaseUtils::getStr($path_data['tt'], 'int', 0);
		$postvar['hddSortType'] == 2 ? $postvar['logintime_lower'] = $timelimit : $postvar['freshtime_lower'] = $timelimit;

		$postvar['refresh_time_upper'] = base_lib_BaseUtils::getStr($path_data['rt'], 'int', 0);
		$postvar['is_new_resume'] = base_lib_BaseUtils::getStr($path_data['newr'], 'bool', false);//是否新简历


        // 判断是否 打标为假招聘转招生、KTV、海外招聘不提供筛选新简历功能
        $service_company = new base_service_company_company();
        if($postvar['is_new_resume']){
            if($service_company->canProviteNewResumeSearch($this->_userid)===0){
                $postvar['is_new_resume'] = false;
            }
        }

		/* build other */
		$search_type = (!empty($path_data['seekerid'])) ? 1 : 2;

		$searchFrom = base_lib_BaseUtils::getStr($path_data['searchfrom'], 'string', 0);
		if ($searchFrom != 0) {
			$search_type = $searchFrom;
		}

		if ($postvar['pageindex'] == 1) {
			$log = "";
			foreach ($postvar as $k => $v)
				$log .= $k . "::" . $v . ";;";

			$searchlog_service = new base_service_company_resume_searchlog();
			$searchlog_service->addSearchLog($this->_userid, $log, $search_type);

			unset($postvar['refresh_time_upper']);
		}

		/* search from solr  */
		$solr_service = new base_service_solr_resume();
		$result = $solr_service->resumeSearch($postvar);


		/* check company whether blenched */
		$resume_ids = base_lib_BaseUtils::getProperty($result['docs'], "id");
		$count = count($resume_ids);
		$resume_ids = $this->_getFilterResumeID($resume_ids);
		$count = $count - count($resume_ids);

        //区县企业增加简历置顶
        $service_company = new base_service_company_company();
        $site_type   = $service_company->getCompany($this->_userid,1,'site_type')['site_type'];
        if($site_type == 2){
            if(!empty($path_data['j'])){
                $jobsort = explode(",",$path_data['j']);
            }else{
                $jobsort = '';
            }
            $service_resumetop = new base_service_person_resume_resumetop();
            $resume_top        = $service_resumetop->getTopResume($path_data['sn'],$jobsort,'resume_id');
            $top_resume_ids    = base_lib_BaseUtils::getProperty($resume_top,'resume_id');
            $top_resume_ids    = array_unique($top_resume_ids);
        }
        if(!empty($top_resume_ids)){
            $resume_ids = array_merge($top_resume_ids,$resume_ids);
        }


        if(!empty($params['j']))
        {
            $common_jobsort = new base_service_common_jobsort();
            $params['jname'] = $common_jobsort->getJobsortName($params['j'],true);
        }
        $params['pht'] = $params['pht'] ? $params['pht'] : 0;


		/* get resume data detail */
        $resumes = $this->_bindResumeDatas($resume_ids, $result,$result['key_worlds_mark'],$postvar['hddSortType']);
        //判断这些person_ids对应的网易云账户是否在线
		if (!empty($resumes)) {
            $person_ids = base_lib_BaseUtils::getProperty($resumes, 'person_id');
            $resume_ids = base_lib_BaseUtils::getProperty($resumes, 'resume_id');

            //求职者一周内是否登录过app
            $sercie_loginlog= new base_service_person_loginlog();
            $login_status = $sercie_loginlog->getLoginAppData($person_ids,14);
            if(!empty($login_status))
                $login_status = base_lib_BaseUtils::array_key_assoc($login_status,'person_id');
            /*$service_wangyiaction = new base_service_app_wangyiaction();
            $wy_person_status_arr = $service_wangyiaction->checkPersonsIsOnline($person_ids);*/

            //是否新简历判断
            $service_resume = new base_service_person_resume_resume();
            $resume_info    = $service_resume->isNewResume($resume_ids);
            $resume_info    = base_lib_BaseUtils::array_key_assoc($resume_info, 'resume_id');

		    foreach($resumes as $k => $v) {
                $resumes[$k]['chat_status'] =  !empty($login_status[$v['person_id']]) ? true : false;
                $resumes[ $k ]['is_new_resume'] = $resume_info[ $v['resume_id'] ]['is_new_resume'] ? true : false;//是否 是新简历
            }
        }
		$pager = $this->_buildPager($result['numFound'], $postvar['pagesize'], $postvar['pageindex']);
		$obj = array(
			"resumes" => $resumes,
			"total"   => $result['numFound'] - $count,
			"pager"   => $pager,
			"params"  => $params,
			"time"	  => empty($postvar['refresh_time_upper']) ? time() : $postvar['refresh_time_upper']
		);

		//-----------------------Start：网站监控---------------------
		//说明： 网站trackv2监控记录：该页面由于采用了ajax的方式请求，所以无法以调用js的方式来监控
		//todo 考虑共用参数收集方法，暂不共用
		$service_core = new base_service_track_initcore();
		$track_array = [];
		$track_array['url'] = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];// 本站url
		$track_array['src_url'] = $_SERVER['HTTP_REFERER'] ?  $_SERVER['HTTP_REFERER'] : ''; // 来源url 可以为空
		$track_array['visit_sys'] = $service_core->is_mobile_request();// 访问类型 PC/触屏版/微信/IOS/Android
		$track_array['visit_dev'] = $service_core->getBrowser().' '.$service_core->getBrowserVer();// 访问设备 IE10/IE8/...
		$track_array['title'] = '';// 访问页面title
		$service_core->Index($track_array);
		//-----------------------End  ：网站监控---------------------

		echo header("Content-type:text/json;charset=utf-8");
		echo json_encode($obj);
		exit;
	}

	/**
	 * 打开搜索器
	 * @param  [array] $inPath url参数
	 * @return [page]         [resume/searchseeker.html]
	 */
	public function pageSeeker($inPath){
		$service_seeker = new base_service_company_resume_seeker();
		$seeker_list = $service_seeker ->getResumeSeekerList($this->_userid, "seeker_id,seeker_name")->items;

		$this->_aParams['data'] = $seeker_list;
		return $this->render('./resume/searchseekernew.html', $this->_aParams);
	}

	/**
	 * 删除搜索器
	 * @param  array $inPath url参数
	 * @return json         true/false
	 */
	public function pageSeekerDel($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$seekerid = base_lib_BaseUtils::getStr($path_data['seekerid'], 'int', 0);

		$service_seeker = new base_service_company_resume_seeker();
		$result = $service_seeker->delSeeker($this->_userid, $seekerid);

		if (empty($result)) {
			$json_arr['error'] = "删除简历搜索器失败。";
			echo json_encode($json_arr);
			return ;
		}

		$json_arr['seeker_id'] = $seekerid;
		echo json_encode($json_arr);
		return;
	}

	/**
	 * 检查单位是否能添加简历搜索器
	 * @param  array $inPath url参数
	 * @return json         msg
	 */
	public function pageCanAddSeeker($inPath) {
		$service_seeker = new base_service_company_resume_seeker();
		$seeker_count = $service_seeker->getCompanySeekerCount($this->_userid);

		$json_arr['status'] = "succeed";
		$xml = SXML::load("../config/company/company.xml");
		if (!is_null($xml)) {
			$maxResumeSeekerNum = $xml->MaxResumeSeekerNum;
		}
		if ($seeker_count >= $maxResumeSeekerNum) {
			$json_arr['status'] = "fail";
			$json_arr['msg'] = "您最多可以添加{$maxResumeSeekerNum}个常用搜索，请先删除不用的常用搜索。";
		}

		echo json_encode($json_arr);
		return;
	}

	/**
	 * 搜索器保存
	 * @param  array $inPath url参数
	 * @return json         msg
	 */
	public function pageSeekerAddDo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

		//保存搜索器到数据库
		$seeker_name = base_lib_BaseUtils::getStr($path_data['seekerName']);
		$seeker_name = trim($seeker_name);

		$validator = new base_lib_Validator();
		//搜索条件太长报错处理
		$validator->getStr($seeker_name,1,300,"常用搜索条件不能超过300字");
		if (empty($seeker_name)) {
			$json_arr['error'] = "搜索器名称不能为空";
			echo json_encode($json_arr);
			return ;
		}
		if($validator->has_err){
			$json_arr['error'] = $validator->err[0];
			echo json_encode($json_arr);
			return ;
		}


		$service_seeker = new base_service_company_resume_seeker();
		$isUnique = $service_seeker->isUniqueSeekerName($this->_userid, $seeker_name);

		$seeker_service = new company_service_seeker();
		if (!$isUnique) {
			$json_arr['error'] = "搜索器名称已存在，无法保存。";
			echo json_encode($json_arr);
			return ;
		}
		$seeker_conter = base_lib_BaseUtils::getStr($path_data['seekerconter'], '', "");
		$seeker['company_id']  = $this->_userid;
		$seeker['seeker_name'] = $seeker_name;
		$seeker['content']     = $seeker_service->buildSeeker($seeker_conter);
		$seeker['create_time'] = date('Y-m-d H:i:s');
		$service_seeker->AddResumeSeeker($seeker);

		//返回json
		$json_arr['status'] = "succeed";
		echo json_encode($json_arr);
		return;
	}

	/**
	 * 直接搜索简历ID
	 * @param  array $inPath URL链接参数
	 * @return json         msg(resume_id)
	 */
	public function pageSearchID($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_id = base_lib_BaseUtils::getStr($path_data['resume_id'],"int",0);
		$json_arr['status'] = 'succeed';

		$service_resume = new base_service_person_resume_resume();
		$resume = $service_resume->getResume($resume_id, "resume_id");

		if (empty($resume)) {
			$json_arr['status'] = 'fail';
			echo json_encode($json_arr);
			return;
		}

		$json_arr['resumeid'] = $resume['resume_id'];
		echo json_encode($json_arr);
		return ;
	}

	/* private function for the page */

	/**
	 * 获取所有参数
	 * @param  array $path_data 所有链接参数
	 * @return array       solr请求参数
	 */
	private function _fixParams($path_data) {
		/* company selected & value */
        $_search_compose_data = []; //组合搜索

		$company_name = base_lib_BaseUtils::getStr($path_data['cn'], 'string', '');
		$company_type = base_lib_BaseUtils::getStr($path_data['ct'], 'string', '0');

		/* station selected & value */
		$station      = base_lib_BaseUtils::getStr($path_data['sn'], 'string', '');
		$station_type = base_lib_BaseUtils::getStr($path_data['st'], 'string', '0');
        $idst         = base_lib_BaseUtils::getStr($path_data["idst"],"int",0);
		if (!empty($station)){
			$station_type == 0 ? $postvar['last_work_station'] = $station : $postvar['station_name'] = $station;
            $_search_compose_data["last_station"] = $station_type == 0 ? $station."（最近职位）" : $station;
            if($idst == 1){
                $_search_compose_data["last_station"] = $_search_compose_data["last_station"]."（模糊匹配）";
            }
        }
        $postvar["station_indis"] = $idst;

		if (!empty($company_name)){
            $company_type == 0 ? $postvar['last_company_name'] = $company_name : $postvar['company_name'] = $company_name;
            $_search_compose_data["company_name"] = $company_type == 0 ? $company_name."（最近公司）" : $company_name;
        }

		/* keyword param */
		$postvar['keyword']              = base_lib_BaseUtils::getStr($path_data['k'], 'string', '');

		//查询数据太长报错问题
		$postvar['keyword'] = mb_substr($postvar['keyword'],0,300);
		$postvar['contains_any_keyword'] = base_lib_BaseUtils::getStr($path_data['sp'], 'string', '');
		if(!empty($postvar['keyword'])){
            $_search_compose_data["full_keyrod"] = !empty($postvar['contains_any_keyword']) ? $postvar['keyword']."（包含任一关键字）" : $postvar['keyword'];
        }

		/* age param */
		$postvar['age_lower']    = base_lib_BaseUtils::getStr($path_data['amin'], 'int', '');
		$postvar['age_upper']    = base_lib_BaseUtils::getStr($path_data['amax'], 'int', '');
		if ($postvar['age_upper'] < $postvar['age_lower'] && $postvar['age_lower'] && $postvar['age_upper']) {
			list($postvar['age_lower'], $postvar['age_upper']) = array($postvar['age_upper'], $postvar['age_lower']);
			list($path_data['amin'], $path_data['amax']) = array($path_data['amax'], $path_data['amin']);
		}

        if(!empty($postvar['age_lower']) || !empty($postvar['age_upper'])){
            $_search_age_value = "";
            if(!empty($postvar['age_lower']) && !empty($postvar['age_upper'])){
                $_search_age_value = $postvar['age_lower']."至".$postvar['age_upper'];
            }elseif(!empty($postvar['age_lower'])){
                $_search_age_value = $postvar['age_lower']."至不限";
            }else{
                $_search_age_value = "不限至".$postvar['age_upper'];
            }
            $_search_compose_data["age"] = $_search_age_value;
        }


		/* workyear param */
		$postvar['workyear_min'] = base_lib_BaseUtils::getStr($path_data['ymin'], 'int', 0);
		$postvar['workyear_max'] = base_lib_BaseUtils::getStr($path_data['ymax'], 'int', 0);
		if ($postvar['workyear_min'] != 99 && $postvar['workyear_max'] != 99) {
			if ($postvar['workyear_min'] > $postvar['workyear_max'] && $postvar['workyear_min'] && $postvar['workyear_max']) {
				list($postvar['workyear_min'], $postvar['workyear_max']) = array($postvar['workyear_max'], $postvar['workyear_min']);
				list($path_data['ymin'], $path_data['ymax']) = array($path_data['ymax'], $path_data['ymin']);
			}
		} else if ($postvar['workyear_max'] == 99 && $postvar['workyear_min']) {
			list($postvar['workyear_min'], $postvar['workyear_max']) = array($postvar['workyear_max'], $postvar['workyear_min']);
			list($path_data['ymin'], $path_data['ymax']) = array($path_data['ymax'], $path_data['ymin']);
		}

        if(!empty($postvar['workyear_min']) || !empty($postvar['workyear_max'])){
            $_work_year_min     = $postvar['workyear_min'] == 99 ? "应届生" : $postvar["workyear_min"];
            $_work_year_max    = $postvar['workyear_max'] == 99 ? "应届生" : $postvar["workyear_max"];
            $_search_workyear_value = "";
            if(!empty($_work_year_min) && !empty($_work_year_max)){
                $_search_workyear_value = $_work_year_min."至".$_work_year_max;
            }elseif(!empty($_work_year_min)){
                $_search_workyear_value = $_work_year_min."至不限";
            }else{
                $_search_workyear_value = "不限至".$_work_year_max;
            }
            $_search_compose_data["work_year"] = $_search_workyear_value;
        }


		/* degree param */
		$degree_lower            = base_lib_BaseUtils::getStr($path_data['dmin'], 'string', '');
		$degree_upper            = base_lib_BaseUtils::getStr($path_data['dmax'], 'string', '');
		$degree_common           = new base_service_common_degree();
		$degrees_all             = array_keys($degree_common->getAll());

		if ($degree_lower > $degree_upper && $degree_upper && $degree_lower) {
			list($degree_upper, $degree_lower) = array($degree_lower, $degree_upper);
			list($path_data['dmin'], $path_data['dmax']) = array($path_data['dmax'], $path_data['dmin']);
		}

		$lower = array_search($degree_lower, $degrees_all);
		$upper = array_search($degree_upper, $degrees_all);
        $degree_lower_name = !empty($degree_lower) ? $degree_common->getDegree($degree_lower) : "";
        $degree_upper_name = !empty($degree_upper) ? $degree_common->getDegree($degree_upper) : "";
		if ($lower !== false && $upper !== false) {
			$degrees = array_slice($degrees_all, $lower, $upper-$lower+1);
            $_search_compose_data["degree"]     = $degree_lower_name."至".$degree_upper_name;
		} else if ($lower !== false) {
			$degrees = array_slice($degrees_all, $lower);
            $_search_compose_data["degree"]     = $degree_lower_name."至不限";
		} else if ($upper !== false) {
            $degrees = array_slice($degrees_all, 0, $upper);
            $_search_compose_data["degree"]     = "不限至".$degree_upper_name;
		}

		if (!empty($degrees))
			$postvar['degree_ids'] = implode(",", $degrees);

		/* salary param */
		$postvar['salary_min']   = base_lib_BaseUtils::getStr($path_data['smin'], 'string', 0);
		$postvar['salary_max']   = base_lib_BaseUtils::getStr($path_data['smax'], 'string', 0);

		$salary_common           = new base_service_common_salary();
		$postvar['salary_min']   = $salary_common->fixSalary($postvar['salary_min']);
		$postvar['salary_max']   = $salary_common->fixSalary($postvar['salary_max']);


		if ($postvar['salary_min'] > $postvar['salary_max'] && $postvar['salary_max'] && $postvar['salary_min']) {
			list($postvar['salary_min'], $postvar['salary_max']) = array($postvar['salary_max'], $postvar['salary_min']);
			list($path_data['smin'], $path_data['smax']) = array($path_data['smax'], $path_data['smin']);
		}
        $salary_min_name = !empty($postvar['salary_min']) ? $salary_common->getSalary($postvar['salary_min']) : "";
        $salary_max_name = !empty($postvar['salary_max']) ? $salary_common->getSalary($postvar['salary_max']) : "";
        if(!empty($salary_min_name) && !empty($salary_max_name)){
            $_search_compose_data["exp_salary"]     = $path_data['smin']."至".$path_data['smax'];
        }elseif(!empty($salary_min_name)){
            $_search_compose_data["exp_salary"]     = $path_data['smin']."至不限";
        }elseif(!empty($salary_min_name)){
            $_search_compose_data["exp_salary"]     = "不限至".$path_data['smax'];
        }
		/* build accession_time param */
		$postvar['accession_time'] = base_lib_BaseUtils::getStr($path_data['ac'], 'string', 0);
        if(!empty($postvar["accession_time"])){
            $common_accessiontime  = new base_service_common_accessiontime();
            $accession_time_name    = $common_accessiontime->getAccession($postvar["accession_time"]);
            $_search_compose_data["accession_time"]     = $accession_time_name;
        }

		/* sex param */
		$postvar['sex'] = base_lib_BaseUtils::getStr($path_data['s'], 'int', 0);
        if(!empty($postvar["sex"])){
            $_search_compose_data["sex"]    = $postvar['sex'] == 1 ? "限男性" : "限女性";
        }

		/* expect station param */
		$postvar['exp_station'] = base_lib_BaseUtils::getStr($path_data['es'], 'string', '');
        if(!empty($postvar["exp_station"])){
            $_search_compose_data["exp_station"]    = $postvar["exp_station"];
        }

		/* current area param */
		$postvar['major_desc'] = base_lib_BaseUtils::getStr($path_data['md'], 'string', '');
        if(!empty($postvar['major_desc'])){
            $_search_compose_data["major"]    = $postvar["major_desc"];
        }

		/* current area param */
		$postvar['cur_areas'] = base_lib_BaseUtils::getStr($path_data['a'], 'string', '');
        if(!empty($postvar["cur_areas"])){
            $cur_area_ids = explode(",", $postvar['cur_areas']);
//            $service_area = new base_service_common_area();
//            $area_names   = [];
//            foreach($cur_area_ids as $_cur_are_id){
//                $_area_name = $service_area->getAreaName($_cur_are_id);
//                if(!empty($_area_name)){
//                    $area_names[] = $_area_name;
//                }
//            }
            $area_names = $this->_getAreaName($cur_area_ids);

            if(!empty($area_names)){
                $_search_compose_data["cur_area_id"]    = $area_names;
            }
        }

		/* expect area param */
		$postvar['exp_areas'] = base_lib_BaseUtils::getStr($path_data['ea'], 'string', '');
        if(!empty($postvar["exp_areas"])){
            $exp_area_ids = explode(",", $postvar['exp_areas']);
//            $service_area = new base_service_common_area();
//            $area_names   = [];
//            foreach($exp_area_ids as $_exp_are_id){
//                $_area_name = $service_area->getAreaName($_exp_are_id);
//                if(!empty($_area_name)){
//                    $area_names[] = $_area_name;
//                }
//            }
            $area_names = $this->_getAreaName($exp_area_ids);
            if(!empty($area_names)){
                $_search_compose_data["exp_area_id"]    = $area_names;
            }
        }

		/* jobsort param */
		$postvar['jobsorts'] = base_lib_BaseUtils::getStr($path_data['j'], 'string', '');
        if(!empty($postvar['jobsorts'])){
            $service_jobsort = new base_service_common_jobsort();
            $jobsorts_array  = explode(",", $postvar['jobsorts']);
            $jobsort_names   = [];
            foreach((array)$jobsorts_array as $_jobsort){
                $jobsort_name = $service_jobsort->getJobsortName($_jobsort);
                if(!empty($jobsort_name)){
                    $jobsort_names[] = $jobsort_name;
                }
            }
            if(!empty($jobsort_names)){
                $_search_compose_data["exp_jobsort"]    = implode("、", $jobsort_names);
            }
        }

		/* build calling param */
		$postvar['callings'] = base_lib_BaseUtils::getStr($path_data['c'], 'string', '');
        if(!empty($postvar["callings"])){
            $service_calling = new base_service_common_calling();
            $calling_array   = explode(",", $postvar["callings"]);
            $calling_names   = [];
            if(!empty($calling_array)){
                foreach($calling_array as $_calling){
                    $calling_name = $service_calling->getCallingName($_calling);
                    if(!empty($calling_name)){
                        $calling_names[] = $calling_name;
                    }
                }
                if(!empty($calling_names)){
                    $_search_compose_data["exp_calling"]    = implode("、", $calling_names);
                }
            }
        }

		/* build marriage param */
		$postvar['marriage'] = base_lib_BaseUtils::getStr($path_data['ma'],"int",0);

		/* build stature param */
		$postvar['stature_min'] = base_lib_BaseUtils::getStr($path_data['sml'],"int",0);
		$postvar['stature_max'] = base_lib_BaseUtils::getStr($path_data['smb'],"int",0);
		if ($postvar['stature_max'] < $postvar['stature_min'] && $postvar['stature_min'] && $postvar['stature_max']) {
			list($postvar['stature_min'], $postvar['stature_max']) = array($postvar['stature_max'], $postvar['stature_min']);
			list($path_data['sml'], $path_data['smb']) = array($path_data['smb'], $path_data['sml']);
		}
        if(!empty($postvar['stature_min']) && !empty($postvar['stature_max'])){
            $_search_compose_data["stature"]    = $postvar['stature_min']."至".$postvar['stature_max']."厘米";
        }elseif(!empty($postvar['stature_min'])){
            $_search_compose_data["stature"]    = $postvar['stature_min']."厘米至不限";
        }elseif(!empty($postvar['stature_max'])){
            $_search_compose_data["stature"]    = "不限至".$postvar['stature_max']."厘米";
        }

		/* build other params */
		$postvar['cur_company_id'] = $this->_userid;

		/* build person_class for huibo resume set */
		$postvar['person_class'] = 1;

		foreach (array("last_work_station", "station_name", "last_company_name", "company_name", "exp_station", "keyword", "major_desc") as $value) {
			if (!empty($postvar[$value])) {
				if ($value == "keyword") {
					mb_regex_encoding('UTF-8');
		     		mb_internal_encoding("UTF-8");
					$path_data['highlight'][$value] = mb_split("[,，、\|\t| |　]+", $postvar[$value]);
                }elseif($value == "station_name" || $value == "last_work_station"){
                    if($postvar["station_indis"] == 0){
                        $path_data['highlight'][$value] = $postvar[$value];
                    }else{
                        $path_data['highlight'][$value] = SSplitWord::split2($postvar[$value]);
                    }
                } else {
					$path_data['highlight'][$value] = SSplitWord::split2($postvar[$value]);
				}
			}
		}

        $photoType = base_lib_BaseUtils::getStr($path_data['pht'], 'int', 0); //是否有照片 0 不限： 1 ：有照片 todo solr写上
        if($photoType == 1){
            $_search_compose_data["photo"]    = "有照片";
        }
        //添加搜索记录
        if(!empty($_search_compose_data) && $path_data["p"] == 1){
            $service_actionsource           = new base_service_common_actionsource();
            $account_id                     = base_lib_BaseUtils::getCookie('accountid');
            $action_source                  = $service_actionsource->website;
            $service_searchresumehistory    = new base_service_company_searchresumehistory();
            $service_searchresumehistory->addSearchHistory($_search_compose_data,$this->_userid,$account_id,$action_source);
        }


		return array($path_data, $postvar);
	}

	/**
	 * 根据职位信息获取参数
	 * @param  array $path_data 参数集
	 * @return array            solr请求参数
	 */
	private function _fixMatchParams($path_data) {
		$service_job = new base_service_company_job_job();
		$job_id = $path_data['job_id'];
		$job    = $service_job->getJob($job_id, 'station,work_year_id,allow_graduate,degree_id,sex,age_lower,age_upper,min_salary,max_salary');

		if (!empty($job)) {
			/* work year param */
			if ($job['allow_graduate'] != 1) {
				$postvar['workyear_min'] = intval($job['work_year_id']) ? intval($job['work_year_id']) : 0;
				$path_data['ymin'] = $job['work_year_id'];
			} else {
				$postvar['workyear_min'] = 0;
			}

			/* degree param */
			if (!empty($postvar['degree_id'])) {
				$degree_common	= new base_service_common_degree();
				$degrees_all	= array_keys($degree_common->getAll());
				$lower = array_search($postvar['degree_id'], $degrees_all);
				if ($lower !== false)
					$degrees = array_slice($degrees_all, $lower);
				$path_data['dmin'] = $postvar['degree_id'];
			}

			/* sex param */
			if (!empty($job['sex'])) {
				$postvar['sex'] = $job['sex'];
				$path_data['s'] = $job['sex'];
			}

			/* age lower param */
			if (!empty($job['age_lower'])) {
				$postvar['age_lower'] = $job['age_lower'];
				$path_data['amax']    = $job['age_lower'];
			}

			/* age upper param */
			if (!empty($job['age_upper'])) {
				$postvar['age_upper'] = $job['age_upper'];
				$path_data['amax']    = $job['age_upper'];
			}

			/* station param */
			if (!empty($job['station'])) {
				$postvar['keyword'] = $job['station'];
				$path_data['k']     = $job['station'];
			}

			$salary_common = new base_service_common_salary();
			$salaries_all  = $salary_common->getAll();
			/* salary lower param */
			if (!empty($job['min_salary'])) {
				$value = "";
				foreach ($salaries_all as $_key => $_salary) {
					if ($job['min_salary'] < intval($_salary))
						break;

					$value = $_key;
				}
				$postvar['salary_min'] = $value;
				$path_data['smin']     = $value;
			}

			/* salary lower param */
			if (!empty($job['max_salary'])) {
				$value = "";
				foreach ($salaries_all as $_key => $_salary) {
					if ($job['max_salary'] < intval($_salary))
						break;

					$value = $_key;
				}
				$postvar['salary_max'] = $value;
				$path_data['smax']     = $value;
			}

			/* build other params */
			$postvar['cur_company_id'] = $this->_userid;

			/* build person_class for huibo resume set */
			$postvar['person_class'] = 1;

			return array($path_data, $postvar);
		}
	}

    private function _fixMatchParamsV1($path_data) {
		$service_job = new base_service_company_job_job();
		$job_id = $path_data['job_id'];
		$job    = $service_job->getJob($job_id, 'station,work_year_id,allow_graduate,degree_id,sex,age_lower,age_upper,min_salary,max_salary,area_id');

		if (!empty($job)) {
			/* work year param */
			if ($job['allow_graduate'] != 1) {
				$postvar['workyear_min'] = intval($job['work_year_id']) ? intval($job['work_year_id']) : 0;
				$path_data['ymin'] = $job['work_year_id'];
			} else {
				$postvar['workyear_min'] = 0;
			}

			/* degree param */
			if (!empty($job['degree_id'])) {

                /* degree param */
                $degree_lower            = $job['degree_id'];
                $degree_common           = new base_service_common_degree();
                $degrees_all             = array_keys($degree_common->getAll());

                $lower = array_search($degree_lower, $degrees_all);
                $degree_lower_name = !empty($degree_lower) ? $degree_common->getDegree($degree_lower) : "";
                $degrees = array_slice($degrees_all, $lower);
//                $_search_compose_data["degree"]     = $degree_lower_name."至不限";

                if (!empty($degrees)){
                    $postvar['degree_ids'] = implode(",", $degrees);
                    $path_data['dmin'] = $job['degree_id'];
                }
			}

			/* age lower param */
			if (!empty($job['age_lower'])) {
				$postvar['age_lower'] = $job['age_lower'];
				$path_data['amin']    = $job['age_lower'];
			}

			/* age upper param */
			if (!empty($job['age_upper'])) {
				$postvar['age_upper'] = $job['age_upper'];
				$path_data['amax']    = $job['age_upper'];
			}

			/* station param */
			if (!empty($job['station'])) {
				$postvar['station_name'] = $job['station'];
				$path_data['sn']     = $job['station'];
                $path_data['highlight']['station_name'] = $postvar['station_name'];
			}

            /* expect area param */
            if(!empty($job["area_id"])){
//                $exp_area_ids = explode(",", $postvar['exp_areas']);
//                $area_names = $this->_getAreaName($exp_area_ids);
//                if(!empty($area_names)){
//                    $_search_compose_data["exp_area_id"]    = $area_names;
//                }
                $path_data['ea'] = $this->_getExpAreas($job["area_id"]);
                $postvar['exp_areas'] = $path_data['ea'];
            }

            $postvar["station_indis"] = 0;
			/* build other params */
			$postvar['cur_company_id'] = $this->_userid;

			/* build person_class for huibo resume set */
			$postvar['person_class'] = 1;

			return array($path_data, $postvar);
		}
	}

	/**
	 * 获取枚举变量
	 * @return  null
	 */
	private function _getEnumTypes() {
		$degree_common      = new base_service_common_degree();
		$salary_common      = new base_service_common_salary();
		$workyear_common    = new company_service_workyear();
		$timelimit_common   = new company_service_timelimit();
		$applystatus_common = new base_service_common_applystatus();
		$accession_common   = new base_service_common_accessiontime();

		$this->_aParams['degrees']   = $degree_common->getAll();
		$this->_aParams['salaries']  = array_map(function($v) {
			return mb_substr($v, 0, -3);
		}, $salary_common->getAll());

		$this->_aParams['accessions'] = $accession_common->getAll();
		$this->_aParams['workyear']   = $workyear_common->getAll();
		$this->_aParams['timelimit']  = $timelimit_common->getAll();
	}

	/**
	 * 根据搜索器获得结果
	 * @param  int $seeker_id 搜索器ID
	 * @param  array $path_data 链接参数列表
	 * @return array           solr请求参数
	 */
	private function _getSeekerData($seeker_id, $path_data) {
		$service_seeker = new base_service_company_resume_seeker();
		$seeker = $service_seeker->getSeeker($this->_userid, $seeker_id, "content");

		$seeker_service = new company_service_seeker();
		list($params, $postvar) = $seeker_service->buildSolr($seeker['content']);

		$postvar['cur_company_id'] = $this->_userid;
		foreach (array("last_work_station", "work_stations", "last_company_name", "company_names", "exp_station", "keyword", "majorDesc") as $value) {
			if (!empty($postvar[$value])) {
				mb_regex_encoding('UTF-8');
	     		mb_internal_encoding("UTF-8");
				$params['highlight'][$value] = mb_split("[,，、\|\t| ]", $postvar[$value]);
			}
		}
		return array($params, $postvar);
	}

	/**
	 * 过滤被屏蔽的简历IDs
	 * @param  array $resume_ids 简历IDs
	 * @return array             过滤后的简历IDs
	 */
	private function _getFilterResumeID($resume_ids) {
		if (empty($resume_ids))
			return array();

		$service_resume = new base_service_person_resume_resume();
		$service_blench = new base_service_person_blench();

        $service_blench_keyword = new base_service_person_blenchkeyword();
		/* get all resumes */
		$resumes                = $service_resume->getResumeListByIDs(implode(",", $resume_ids), "person_id,resume_id");
        $person_ids             = base_lib_BaseUtils::getProperty($resumes->items, "person_id");
        $all_blench_keyword     = $service_blench_keyword->getAllKeywordsByPersonIds($person_ids, "person_id,keyword");
        $company_service = new base_service_company_company();
		$company_info    = $company_service->getCompany($this->_userid, null, "company_name,company_shortname");

		$filter_id = array();
		foreach ((array)$resumes->items as $resume) {
			$blenches = $service_blench->getAllBlenchKeyList($resume['person_id'], 'person_id,type,com_keyword,company_id')->items;
			foreach ((array)$blenches as $key => $blench) {
				if ($blench['type'] == 0) {
					/* type = 0 for keyword for blenching companys */
					if ($this->_getKeySetResult($blench['com_keyword']))
						array_push($filter_id, $resume['resume_id']);
				} else {
					/* type = 1 for company_id for blenching companys */
		 			if ($blench['company_id'] == $this->_userid){
		 				array_push($filter_id, $resume['resume_id']);
                    }
		 		}
			}
            //APP屏蔽公司关键字
            if(!in_array($resume['resume_id'], $filter_id)){
                $is_filter_keyword = $this->_checkKeyFilterStatus($company_info["company_name"],$company_info["company_shortname"],$all_blench_keyword,$resume["person_id"]);
                if($is_filter_keyword){
                    array_push($filter_id, $resume['resume_id']);
                    $is_filter = true;
                }
            }
		}
		return array_diff($resume_ids, $filter_id);
	}

    private function _checkKeyFilterStatus($company_name,$company_shortname,$all_blench_keyword,$person_id){
        $is_filter = false;
        if(empty($all_blench_keyword)){
            return false;
        }
        foreach ($all_blench_keyword as $value){
            if(!empty($company_name)){
                if ($value["person_id"]== $person_id && mb_strpos($company_name, $value["keyword"]) !== false) {
                    $is_filter = true;
                    break;
                }
            }
            if(!empty($company_shortname)){
                if ($value["person_id"]== $person_id && mb_strpos($company_name, $value["keyword"]) !== false) {
                    $is_filter = true;
                    break;
                }
            }

        }
        return $is_filter;
    }
	/**
	 * 根据关键词过滤公司
	 * @param  string $keyword 关键词
	 * @return boolean          是否需要过滤
	 */
	private function _getKeySetResult($keyword) {
		$company_service = new base_service_company_company();
		$company = $company_service->getCompany($this->_userid, null, "company_name,company_shortname");

		$filter = true;
		if (mb_strpos($company['company_name'], $keyword) === false) {
			$filter = false;
		}

		if (!empty($company['company_shortname']) && mb_strpos($company['company_shortname'], $keyword) === false && !$filter) {
			$filter = false;
		}

		return $filter;
	}

	/**
	 * 组装简历数据
	 * @param  array $resume_ids 简历IDs
	 * @param  array $result     solr结果集
	 * @param  array $key_worlds_mark     需要标红的字段，来自resumesolr-》resumeSearch 的返回参数
	 * @return null
	 */
	private function _bindResumeDatas($resume_ids, $result,$key_worlds_mark=null,$sort_type = 1) {
		if (empty($resume_ids)) {
			$this->_aParams['resumes'] = array();
			return ;
		}


		$service_resume = new base_service_person_resume_resume();
		$resumes = $service_resume->getResumeListByIDs(implode(",", $resume_ids), "resume_id,resume_name,"
			. "person_id,is_chinese_resume,degree_id,refresh_time,station,appraise")->items;

		/* whether to display resume detail */
		$service_company = new base_service_company_company();
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
			$person_ids  = base_lib_BaseUtils::getProperty($resumes, 'person_id');
			$person_data = $service_person->GetPersonListByIDs(implode(",", (array)$person_ids), 'person_id,user_name,'
					. 'sex,birthday2,cur_area_id,start_work,photo,small_photo,name_open,photo_open,start_work,job_state_id,accession_time');

			$person_list = base_lib_BaseUtils::array_key_assoc($person_data->items, "person_id");

            $service_qloudmsg = new base_service_app_qcloudmsg();
            $chat_list        = $service_qloudmsg->getReplyTimes($person_ids);
            $chat_list        = base_lib_BaseUtils::array_key_assoc($chat_list, "person_id");

            //求职者一周内是否登录过app
            $sercie_loginlog= new base_service_person_loginlog();
            $login_status = $sercie_loginlog->getLoginAppData($person_ids,7);
            if(!empty($login_status))
                $login_status = base_lib_BaseUtils::array_key_assoc($login_status,'person_id');
            $service_chat = new company_service_chat(0,0);


			/* login_time list */
			$login_time_list = base_lib_BaseUtils::array_key_assoc($result['docs'], "id");

			/* graduate school */
			$service_edu = new base_service_person_resume_edu();
			$edu_list = $service_edu->getResumeEdus(implode(",", $resume_ids), 'resume_id,school,major_desc,degree')->items;
			$edu_list = base_lib_BaseUtils::array_key_assoc($edu_list, "resume_id", false);

			/* work experience */
			$work_datas = $service_resume_work->getResumeWorks(implode(",", $resume_ids), 'work_id,resume_id,start_time,end_time,station,company_name')->items;
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

			/*获得下载的简历记录*/
			$download_service = new base_service_company_resume_download();
			$download_list = $download_service->queryDownloadList(implode(",", $resume_ids),$this->_userid,"resume_id,download_id")->items;
			$resume_downloadids = base_lib_BaseUtils::getPropertys($download_list,"resume_id");

            /**获取投递记录**/
            $company_resources = base_service_company_resources_resources::getInstance($this->_userid);
            $service_apply      = new base_service_company_resume_apply();

            $apply_list         = $service_apply->getCompanyResumeApplyData($company_resources->all_accounts, $resume_ids, "apply_id,resume_id");
            $resume_applyids    = base_lib_BaseUtils::getPropertys($apply_list,"resume_id");

			/*获得简历查看记录*/
			$service_visit = new base_service_person_visit();
			//$visit_list = $service_visit->getVisits(implode(",",$resume_ids),$this->_userid,"resume_id",60);
            $visit_list     = array();
			$resume_visitids = base_lib_BaseUtils::getPropertys($visit_list,"resume_id");
			if(!empty($visitids)){
				$resume_visitids = array_unique($visitids);
			}

			/*获得简历备注记录*/
			$service_resume_remark = new base_service_company_resume_resumeremark();
			$remark_list = $service_resume_remark->getLastRemarkByResumeIDs($this->_userid,implode(",",$resume_ids),"resume_id,remark,update_time")->items;

            $privilege = $service_person->checkMobilesPrivilege($resume_ids,$this->_userid);

			$resume_remarkids = base_lib_BaseUtils::getPropertys($remark_list,"resume_id");
			$resume_remark_arr = base_lib_BaseUtils::array_key_assoc($remark_list, "resume_id", true);
            $serviceArea = new base_service_company_service_serviceArea();
			foreach ($resumes as $i => $resume_info) {
				$resume_id          = $resume_info['resume_id'];
				$person_id          = $resume_info['person_id'];
				$person_info        = $person_list[$person_id];
				$resume_remark_info = $resume_remark_arr[$resume_id];
				$login_time_info    = $login_time_list[$resume_id]['login_time'];

                $show_full_name     = (in_array($resume_id, $resume_downloadids) || in_array($resume_id, $resume_applyids)) ? true : false;
				/* user name fix */ /**2018-02-28 按朱经理邀请 修改姓名展示逻辑 按照投递和下载显示 若没有投递和下载 则显示先生**/
				if ($person_info['name_open'] == 1 && $isshowresumeinfo && $show_full_name) {
					$resumes[$i]['user_name'] = $person_info['user_name'];
				} else {
					$sex_name = $person_info['sex'] == 1 ? '先生' : '女士';
					$resumes[$i]['user_name'] = mb_substr($person_info['user_name'], 0, 1, 'utf-8') . $sex_name;
				}

				/* 是否显示联系方式 */
                $is_show_linkway = $privilege[ $resume_id ];
                $resumes[$i]['has_download'] = $is_show_linkway;

				/* resume remark */
				$resumes[$i]['remark'] = $resume_remark_info['remark'];

                //已超出简历下载地区限制，请联系工作人员开通相应招聘服务。
                $resumes[$i]['not_area_limit'] = $serviceArea->IsServiceAreaTypeDownloadResumeScope($this->_userid,$resumes[$i]['resume_id']);//限制为false

				/* photo,name,sex,degre.. */
				if ($person_info['photo_open'] != '0' && $isshowresumeinfo && !empty($person_info['photo'])) {
					$resumes[$i]['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP
						. (empty($person_info['small_photo']) ? $person_info['photo'] : $person_info['small_photo']);
					$resumes[$i]['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['photo'];
				} else {
					$resumes[$i]['small_photo'] = "";
					$resumes[$i]['photo']       = "";
				}

				/* 判断该简历是否是已下载 */
				if (!empty($resume_downloadids)) {
					if(in_array($resume_id, $resume_downloadids)){
						$resumes[$i]['has_download'] = true;
					}
				}
				/*判断该简历是否已看过*/
				if (!empty($resume_visitids)) {
					if(in_array($resume_id, $resume_visitids)){
						$resumes[$i]['has_read'] = true;
					}
				}

				/* 判断是否已经备注 */
				if (!empty($resume_remark_info)) {
					$resumes[$i]['remark_msg'] = base_lib_BaseUtils::cutstr($resume_remark_info['remark'],5,"utf-8","","...")." ".date("Y-m-d",strtotime($resume_remark_info['update_time']));
				}

				$cur_areas = $common_area->getTopAreaByAreaID($person_info['cur_area_id']);

				$resumes[$i]['sex']           = $person_info['sex'];
				$resumes[$i]['sex_text']      = empty($person_info['sex']) ? "" : $common_sex->getName($person_info['sex']);
				$resumes[$i]['cur_area_text'] = empty($person_info['cur_area_id']) ? "" : $cur_areas[0]['area_name'];
				$resumes[$i]['cur_area_full'] = empty($person_info['cur_area_id']) ? "" : $cur_areas[1]['area_name'] . "-" . $cur_areas[0]['area_name'];
				$resumes[$i]['degree_text']   = empty($person_info['degree_id']) ? "" : $common_degree->getDegree($person_info['degree_id']);
				$resumes[$i]['age']           = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']) . '岁';
				$resumes[$i]['degree_text']   = empty($resume_info['degree_id']) ? "" : $common_degree->getDegree($resume_info['degree_id']);
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
				$word_count = 32;
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
				$refresh_time = $resume_info['refresh_time'];
				$resumes[$i]['refresh_time'] = base_lib_TimeUtil::to_friend_time2($refresh_time);
				//今天，昨天，前天，一周内，一周前
                if($refresh_time>=date('Y-m-d')){
                    $refresh_txt = "今天";
                }else if($refresh_time>=date('Y-m-d',strtotime("-1 days"))){
                    $refresh_txt = "昨天";
                }else if($refresh_time>=date('Y-m-d',strtotime("-2 days"))){
                    $refresh_txt = "前天";
                }else if($refresh_time>=date('Y-m-d',strtotime("-7 days"))){
                    $refresh_txt = "一周内";
                }else {
                    $refresh_txt = base_lib_TimeUtil::to_friend_time2($refresh_time);//一周前
                }
                $resumes[$i]['refresh_txt'] = $refresh_txt;
                $resumes[$i]['so_type'] = $sort_type;


				/* resume summary */
				$edu_info = $edu_list[$resume_id];
				$resumes[$i]['degree']     = empty($edu_info[0]['degree']) ? "" : $edu_info[0]['degree'];
				$resumes[$i]['school']     = empty($edu_info[0]['school']) ? "未填写" : $edu_info[0]['school'];
				$resumes[$i]['major_desc'] = empty($edu_info[0]['major_desc']) ? "未填写" : $edu_info[0]['major_desc'];

                //判断是否活跃
                $chat_info = $chat_list[$person_id];
                $resumes[$i]["is_active"] = $chat_info["count"] >= 3 ? true : false;

                //聊一聊状态 ，提示等级 1：(未登录)提示 2：（未登录）提示 + 提示消耗 3：提示消耗
                $chat_params['resume_id'] = $resume_id;
                $chat_params['person_id'] = $person_id;
                $chat_params['company_id'] = $this->_userid;
                $resumes[$i]['chat_status'] = $service_chat->getChatNoticeStatus($this->_userid,base_lib_BaseUtils::getCookie('accountid'),$login_status[$person_id],$chat_params);


                /**
                 * 标红逻辑处理
                 * 1、全文关键词搜索命中且 关键词不在“往期职位名称”、“求职意向”中,默认显示第一条命中：
                 *      需要匹配的项：意向职位类别，期望行业，自我评价，教育/培训经历，获奖经历，校内外项目经验,技能专长，证书，我的作品
                 */

                $resumes[$i]['one_marks_key'] = '';//列表右侧需要展示的命中数据对应的key值,比如：意向职位类别，自我评价
                $resumes[$i]['one_marks'] = '';//列表右侧需要展示的命中数据（前端只需根据有无该字段来展示即可）

                $is_in_workstation = false;//全文匹配搜索是否命中工作经历
                $is_in_workstation_2 = false;//曾任职位搜索是否命中工作经历
                $which_in_workstation = false;//工作经历中第一次被命中的位置


                if ($key_worlds_mark['_keywords'] || $key_worlds_mark['_station']) {
                    //求职意向
                    list($one_marks, $is_in_station) = $this->world_replace_mark($resume_info['station'], $key_worlds_mark['_keywords']);

                    //往期职位名称命中处理
                    if(!empty($workslist[$resume_id])){
                        $_i = 0;
                        foreach ($workslist[$resume_id] as $v){

                            list($one_marks, $_has_mark) = $this->world_replace_mark($v['station'], $key_worlds_mark['_station']);
                            if($_has_mark)
                            {
                                if($which_in_workstation===false){
                                    $which_in_workstation = $_i;
                                }
                            }


                            $_i++;
                        }

                        
                        if($which_in_workstation === false){
                            $_i = 0;
                            foreach ($workslist[$resume_id] as $v){

                                list($one_marks, $_has_mark) = $this->world_replace_mark($v['station'], $key_worlds_mark['_keywords']);
                                if($_has_mark)
                                {
                                    $is_in_workstation = true;
                                    if($which_in_workstation===false){
                                        $which_in_workstation = $_i;
                                    }
                                }

                                $_i++;
                            }
                        }
                    }

                    if($which_in_workstation!==false && $which_in_workstation>2){
                        $workslist[$resume_id] = array_slice($workslist[$resume_id], $which_in_workstation-2, 3);
                    }


                    //逻辑1处理
                    $is_school_get = false;
                    list($one_marks1, $is_school_get) = $this->world_replace_mark( $resumes[$i]['school'], $key_worlds_mark['_keywords']);

                    if ($key_worlds_mark['_keywords'] && !$is_in_station && !$is_in_workstation && !$is_school_get) {
                        $one_marks_key = '';

                        //意向职位类别 将相似职位类别一并加入
                        $common_jobsort    = new base_service_common_jobsort();
                        $service_jobosrt = new base_service_job_jobsort();
                        $service_jobsortexp = new base_service_person_resume_jobsortexp();
                        $jobsorts           = $service_jobsortexp->getResumeJobsortexpList($resume_id, 'resume_id,jobsort');
                        $jobsort_items      = $jobsorts->items;
                        if (!base_lib_BaseUtils::nullOrEmpty($jobsort_items)) {

                            $jobsort_ids = base_lib_BaseUtils::getPropertys($jobsort_items,'jobsort');
                            $same_jobsorts = $service_jobosrt->getJobsortByJobsortids($jobsort_ids,"jobsort,similar_word");
                            $same_jobsorts = base_lib_BaseUtils::array_key_assoc($same_jobsorts,'jobsort');

                            $str_expect_jobsorts = '';
                            foreach ($jobsort_items as $v){
                                $str_expect_jobsorts .= $common_jobsort->getJobsortName($v['jobsort']).",".$same_jobsorts[$v['jobsort']]['similar_word'].',';
                            }
                            $str_expect_jobsorts = trim($str_expect_jobsorts,',');
                        }
                        list($one_marks, $has_mark_wolrd) = $this->world_replace_mark($str_expect_jobsorts, $key_worlds_mark['_keywords']);
                        $one_marks_key = '意向职位类别';


                        //期望行业
                        if (!$has_mark_wolrd) {
                            $service_callingexp = new base_service_person_resume_callingexp();
                            $service_calling    = new base_service_common_calling();
                            $callings           = $service_callingexp->getResumeCallingexpList($resume_id, 'resume_id,calling_id');
                            $calling_items      = $callings->items;
                            if (!base_lib_BaseUtils::nullOrEmpty($calling_items)) {
                                $str_expect_callings = '';
                                for ($g = 0; $g < sizeof($calling_items); $g++) {
                                    if (sizeof($calling_items) == 1 || $g == sizeof($calling_items) - 1) {
                                        $str_expect_callings .= $service_calling->getCallingName($calling_items[ $g ]['calling_id']);
                                    } else {
                                        $str_expect_callings .= $service_calling->getCallingName($calling_items[ $g ]['calling_id']) .
                                            ';';
                                    }
                                }

                            }
                            list($one_marks, $has_mark_wolrd) = $this->world_replace_mark($str_expect_callings, $key_worlds_mark['_keywords']);
                            $one_marks_key = '期望行业';
                        }


                        //自我评价appraise
                        if (!$has_mark_wolrd) {
                            list($one_marks, $has_mark_wolrd) = $this->world_replace_mark($resume_info['appraise'], $key_worlds_mark['_keywords'],'peng');
                            $one_marks_key = '自我评价';
                        }


                        //教育/培训经历
                        if (!$has_mark_wolrd) {
                            $service_edu          = new base_service_person_resume_edu();
                            $service_edu->dbquery = true; //load data from subordinate database
                            $edus                 = $service_edu->getResumeEduList($resume_id, 'start_time,end_time,school,major_desc,degree,edu_detail,duty,school_score');
                            $edu_items            = $edus->items;
                            $school_str           = '';
                            if (!empty($edu_items)) {
                                foreach ($edu_items as $edu_temp) {

                                    $school_str = "{$edu_temp['school']},{$edu_temp['major_desc']}";
                                    list($one_marks, $has_mark_wolrd) = $this->world_replace_mark($school_str, $key_worlds_mark['_keywords']);
                                    if ($has_mark_wolrd) {
                                        break;
                                    }


                                    $school_str = "{$edu_temp['edu_detail']}";
                                    list($one_marks, $has_mark_wolrd) = $this->world_replace_mark($school_str, $key_worlds_mark['_keywords']);
                                    if ($has_mark_wolrd) {
                                        break;
                                    }
                                }
                                $one_marks_key = '教育/培训经历';
                            }

                            if(!$has_mark_wolrd){
                                $service_train = new base_service_person_resume_train();
                                $service_train->dbquery = true; //load data from subordinate database
                                $trains = $service_train->getResumeTrainList($resume_id, 'institution,course,train_detail');
                                $train_items = $trains->items;
                                if(!empty($train_items)){
                                    foreach ($train_items as $v){

                                        $school_str = "{$v['course']},{$v['institution']},{$v['train_detail']}";

                                        list($one_marks, $has_mark_wolrd) = $this->world_replace_mark($school_str, $key_worlds_mark['_keywords']);
                                        if ($has_mark_wolrd) {
                                            break;
                                        }
                                    }
                                    $one_marks_key = '教育/培训经历';
                                }
                            }

                        }

                        //获奖经历
                        if (!$has_mark_wolrd) {
                            $winningrecord_service = new base_service_person_resume_winningrecord();
                            $resume_winningrecord  = $winningrecord_service->getWinningsByResumeId($resume_id);
                            $_str                  = '';
                            if ($resume_winningrecord) {
                                foreach ($resume_winningrecord as &$winnin) {
                                    $_str .= "{$winnin['winning_name']},{$winnin['winning_desc']}";
                                }
                            }
                            list($one_marks, $has_mark_wolrd) = $this->world_replace_mark($_str, $key_worlds_mark['_keywords']);
                            $one_marks_key = '获奖经历';
                        }

                        //校内外项目经验
                        if (!$has_mark_wolrd) {
                            $_str                     = '';
                            $service_project          = new base_service_person_resume_project();
                            $service_project->dbquery = true; //load data from subordinate database
                            $projects                 = $service_project->getResumeProjectList($resume_id, 'start_time,end_time,project_name,project_detail,main_duty,duty');
                            $project_items            = $projects->items;
                            if (!base_lib_BaseUtils::nullOrEmpty($project_items)) {
                                for ($a = 0; $a < count($project_items); $a++) {
                                    $_str = "{$project_items[$a]['project_name']},{$project_items[$a]['duty']}";
                                    list($one_marks, $has_mark_wolrd) = $this->world_replace_mark($_str, $key_worlds_mark['_keywords']);
                                    if ($has_mark_wolrd) {
                                        break;
                                    }
                                }
                            }

                            $one_marks_key = '项目经验';
                        }

                        //技能专长
                        if (!$has_mark_wolrd) {
                            $_str                   = '';
                            $service_skill          = new base_service_person_resume_skill();
                            $service_skill->dbquery = true; //load data from subordinate database
                            $skill                  = $service_skill->getResumeSkillList($resume_id, 'skill_name,skill_level');
                            $obj                    = new base_service_common_skilllevel();
                            $skill_items            = $skill->items;
                            foreach ($skill_items as $v) {
                                $_str .= "{$v['skill_name']}," . $obj->getName($v['skill_level']);
                            }

                            list($one_marks, $has_mark_wolrd) = $this->world_replace_mark($_str, $key_worlds_mark['_keywords']);
                            $one_marks_key = '技能专长';
                        }

                        //证书
                        if (!$has_mark_wolrd) {
                            $service_certificate          = new base_service_person_resume_certificate();
                            $service_certificate->dbquery = true; //load data from subordinate database
                            $certificates                 = $service_certificate->getResumeCertificateList($resume_id, 'certificate_name,certificate_no,gain_time,score');
                            $certificates_items           = $certificates->items;
                            $_str                         = '';
                            if (!base_lib_BaseUtils::nullOrEmpty($certificates_items)) {
                                for ($k = 0; $k < count($certificates_items); $k++) {
                                    $_str .= "{$certificates_items[ $k ]['certificate_name']}";
                                }

                            }

                            list($one_marks, $has_mark_wolrd) = $this->world_replace_mark($_str, $key_worlds_mark['_keywords']);
                            $one_marks_key = '证书';
                        }

                        //我的作品-不参与
                       /* if (!$has_mark_wolrd) {
                            $service_achievement          = new base_service_person_resume_achievement();
                            $service_achievement->dbquery = true; //load data from subordinate database
                            $service_attachment           = new base_service_person_resume_achievementattachment();
                            $service_attachment->dbquery  = true; //load data from subordinate database
                            $achievements                 = $service_achievement->getResumeAchievementList($resume_id, 'achievement_id,achievement_name,achievement_description');
                            $achievement_item             = $achievements->items;
                            $_str                         = '';
                            if (!base_lib_BaseUtils::nullOrEmpty($achievement_item)) {

                                foreach ($achievement_item as $v) {
                                    $_str .= "作品名称：{$v['achievement_name']},作品描述：{$v['achievement_description']}";
                                }
                            }

                            list($one_marks, $has_mark_wolrd) = $this->world_replace_mark($_str, $key_worlds_mark['_keywords']);
                            $one_marks_key = '作品';

                        }*/

                        if ($has_mark_wolrd) {
                            $resumes[ $i ]['one_marks'] = $one_marks;
                            $resumes[ $i ]['one_marks_key'] = $one_marks_key;
                        }
                    }
                }


                $resumes[$i]['worklist']   = $workslist[$resume_id] ? array_slice($workslist[$resume_id], 0, 3) : array();
                $resumes[$i]['which_in_workstation']   = $which_in_workstation;//工作经历中第一次被命中的位置


            }

		}

		return $resumes;
	}

    /**
     * 关键词替换标红
     * @param $world
     * @param $_marks
     * @return mixed|string
     */
    private function world_replace_mark($world, $_marks, $is_limit_num = true)
    {

        $has_mark_wolrd = false;//是否存在需要替换的关键词
        if (empty($world))
            return ['', $has_mark_wolrd];

        if (empty($_marks))
            return [$world, $has_mark_wolrd];
        $_preg_world = '<strong class="prTabx4">%s</strong>';

        $world = strtolower($world);

        $_marks = array_unique((array)$_marks);
        foreach ((array)$_marks as $v) {
            $v     = strtolower(trim($v, "\""));
            $start = mb_strpos($world, $v);

            if ($start !== false) {

                $has_mark_wolrd = true;

                if ($is_limit_num) {
                    //截取逻辑
                    $_start = ($start - 20) > 0 ? $start - 20 : 0;
                    $world  = (($start - 20) > 0 ? "..." : '') . mb_substr($world, $_start, $start - $_start + mb_strlen($v)) . "...";
                }
            }
            $world = str_replace($v, sprintf($_preg_world, $v), $world);

            if ($start) {
                //匹配到第一次就不在匹配了
                break;
            }
        }



        return [$world, $has_mark_wolrd];
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
			return $basic_start_work_year;
	}

	private function _fixSeeker($seeker_conter) {
		$arrs = explode("&", $seeker_conter);
	}

	private function _buildPager($total_size, $limit, $curr_page) {
		$total_page = min(ceil($total_size / $limit), ceil(500 / ($limit / 20)));
		$curr_page  = min($total_page, $curr_page);
		$curr_page  = $curr_page ? $curr_page : 1;
		$prePage    = $curr_page - 1;
		$nextPage   = $curr_page >= $total_page ? '' : $curr_page + 1;
		$offset     = ($curr_page - 1) * $limit;
		$startData  = $total_size ? ($offset + 1) : 0;
		$endData    = min($offset + $limit, $total_size);

		$params = array();
		//用于网站页面，只显示当前页左右10条的选项
		$params['pages'][]= 1;

		$page_show_start = 2;
		$page_show_end = max($total_page - 1, 1);
		if ($curr_page <= 6) {
			$page_show_start = 2;
			$page_show_end   = min(10, $total_page - 1);
		} else if ($curr_page >= ($total_page - 4)) {
			$page_show_start = max($curr_page - 4, 1);
			$page_show_end   = $total_page - 1;
		} else if ($curr_page > 6 && $curr_page < ($total_page - 4)) {
			$page_show_start = $curr_page - 4;
			$page_show_end   = $curr_page + 3;
		}

		$params['page_show_start'] = $page_show_start;
		$params['page_show_end']   = $page_show_end;

		for ($i = $page_show_start; $i <= $page_show_end; $i++) {
			$params['pages'][] = $i;
		}

		if ($total_page > 1) {
			$params['pages'][] = $total_page;
		}

		$params['total_page'] = $total_page; //总页数
		$params['startdata']  = $startData;
		$params['enddata']    = $endData;
		$params['currpage']   = $curr_page;
		$params['total_size'] = $total_size;
		$params['limit']      = $limit;
		$params['preg']		  = $prePage;
		$params['next']		  = $nextPage;

		return $params;
	}

    private function _getAreaName($area_ids){
        if(empty($area_ids)){
            return "";
        }
        $area_ids = is_array($area_ids) ? $area_ids : explode(",", $area_ids);
        $area_names = $this->getSpecialAreaName($area_ids);
        return !empty($area_names) ? implode("、", $area_names) : "";
    }

    private function getSpecialAreaName($area_ids_arr) {
		if (count($area_ids_arr) <= 0)
			return array();

        $xml = SXML::load('../config/person/Person.xml');
        $_special   = [];
		if (!is_null($xml)) {
			$_special = array(
				array('name' => '重庆-主城区',      'array' => explode(",", $xml->CQMainCity), 'alias_code' => 'd030'),
				array('name' => '重庆-周边区县',    'array' => explode(",", $xml->CQOtherCounties), 'alias_code' => 'd031'),
				array('name' => '北京-五环以内',    'array' => explode(",", $xml->BJRingsWithin), 'alias_code' => 'd010'),
				array('name' => '北京-五环以外',    'array' => explode(",", $xml->BJRingsWithout), 'alias_code' => 'd011'),
				array('name' => '上海-外环以内',    'array' => explode(",", $xml->SHOuterWithin), 'alias_code' => 'd020'),
				array('name' => '上海-郊区/县',     'array' => explode(",", $xml->SHSuburbs), 'alias_code' => 'd021'),
				array('name' => '天津-主城区',      'array' => explode(",", $xml->TJMainCity), 'alias_code' => 'd040'),
				array('name' => '天津-周边区县',    'array' => explode(",", $xml->TJOtherCounties), 'alias_code' => 'd041')
            );
        }
		$areas = array();
		foreach ($_special as $special) {
			$arr = array_diff($special['array'], $area_ids_arr);
			if (empty($arr)) {
				array_push($areas, $special['name']);
				$area_ids_arr = array_diff($area_ids_arr, $special['array']);
			}
		}
		$area_service = new base_service_common_area();
		foreach ($area_ids_arr as $area_id) {
			array_push($areas, $area_service->getAreaName($area_id));
		}
		return $areas;
	}

    private function _getExpAreas($area_id){
        if(!$area_id)
            return false;

        $area_id = substr($area_id, 0, 4);
        $xml = SXML::load('../config/person/Person.xml');
		if (!is_null($xml)) {
            if(in_array($area_id, explode(",", $xml->CQMainCity)))
                return (string)$xml->CQMainCity;

            if(in_array($area_id, explode(",", $xml->CQOtherCounties)))
                return (string)$xml->CQOtherCounties;

            if(in_array($area_id, explode(",", $xml->BJRingsWithin)))
                return (string)$xml->BJRingsWithin;

            if(in_array($area_id, explode(",", $xml->BJRingsWithout)))
                return (string)$xml->BJRingsWithout;

            if(in_array($area_id, explode(",", $xml->SHOuterWithin)))
                return (string)$xml->SHOuterWithin;

            if(in_array($area_id, explode(",", $xml->SHSuburbs)))
                return (string)$xml->SHSuburbs;

            if(in_array($area_id, explode(",", $xml->TJMainCity)))
                return (string)$xml->TJMainCity;

            if(in_array($area_id, explode(",", $xml->TJOtherCounties)))
                return (string)$xml->TJOtherCounties;
        }

        $area_service = new base_service_common_area();
		$area = $area_service->getArea($area_id,false);
		while (!empty($area)){
		 	$area = $area_service->getArea($area['parent_id'],false);
            if($area["parent_id"] == "NULL"){
                return $area['area_id'];
            }
		}
		return $area_id;
    }
}