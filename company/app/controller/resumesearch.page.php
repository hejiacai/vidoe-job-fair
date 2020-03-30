<?php
/**
 * @ClassName resumeearch.page.php
 * @Desc
 * @author liukai@huibo.com
 * @date 2013-10-28 ����10:10:00
 */

class controller_resumesearch extends components_cbasepage {

	private $__group;
	private $__old_postvar;

	function __construct(){
		parent::__construct();
	}

	public function pageIndex($inPath){
        if(!$this->canDo("search_resume")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		base_lib_BaseUtils::ssetcookie($cookie = array('searchselect' => 1), 3600 * 24 * 7, '/', base_lib_Constant::COOKIE_HUIBO_DOMAIN);

		$service_degree = new base_service_common_degree();
		$degree['0']='不限';
		$degree = array_merge($degree,$service_degree->getAll());
		$this->_aParams['degree'] = $degree;
		$service_sex = new base_service_common_sex();
		$sex['0'] = '不限';
		$sex = array_merge($sex,$service_sex->getSex());
		$this->_aParams['sex'] = $sex;

		$service_marriage = new base_service_common_marriage();
		$marriage['0'] = '不限';
		$marriage = array_merge($marriage,$service_marriage->getMarriage());
		$this->_aParams['marriage'] = $marriage;

		$seeker_id = base_lib_BaseUtils::getStr($path_data['seekerid'],'int',null);
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'],'int',null);
		if (!empty($job_id))  {
			$service_job = new base_service_company_job_job();
			$jobinfo = $service_job->getJob($job_id,'station,work_year_id,allow_graduate,degree_id,sex,age_lower,age_upper');
			if(count($jobinfo)>0){
				if($jobinfo['allow_graduate']!=1){
					$postvar['workyear_min'] = intval($jobinfo['work_year_id'])?intval($jobinfo['work_year_id']):0;
				}else{
					$postvar['workyear_min'] = 0;
				}				
				$postvar['workyear_max'] = 0;
				//学历
				$degree_ids = array();
				$postvar['degree_ids'] = array(0=>0);
				$degree_id = intval($jobinfo['degree_id'])?intval($jobinfo['degree_id']):1;
				if($degree_id>1){
					for($i=$degree_id;$i<9;$i++){
						array_push($degree_ids,'0'.$i);
					}
					if(count($degree_ids)>0){
						$postvar['degree_ids'] = $degree_ids;
					}
				}
				//性别
				if($jobinfo['sex']>0){
					$postvar['sex']= $jobinfo['sex'];
				}
				if(!empty($jobinfo['age_lower'])){
					$postvar['age_lower'] = $jobinfo['age_lower'];
				}

				if(!empty($jobinfo['age_upper'])){
					$postvar['age_upper'] = $jobinfo['age_upper'];
				}
				
				if(!empty($jobinfo['station'])){
					$postvar['keyword'] = $jobinfo['station'];
				}
			}			
			$postvar['keyword_type'] = 0;
			$postvar['pageindex'] = base_lib_BaseUtils::getStr($path_data['page'], 'int', 1);
			$postvar['pagesize'] =  base_lib_BaseUtils::getStr($path_data['hddPageSize'], 'int', 20);
			$postvar['hddSortType'] = base_lib_BaseUtils::getStr($path_data['hddSortType'], 'int', 1);
			$postvar['islistorsummary']= 1;
			$postvar['hddIsfirstPost'] = 1;
				
		} else {
			if (empty($seeker_id)) {
				//获取请求数据
				$postvar = $this->_getData($path_data);
				if ($postvar['pageindex'] == 1 && $postvar['hddIsfirstPost'] == 1) {
					$log = "";
					foreach ($postvar as $k => $v)
						$log .= $k . "::" . $v . ";;";

					$searchlog_service = new base_service_company_resume_searchlog();
					$searchlog_service->addSearchLog($this->_userid, $log, 3);
				}

			} else {
				$postvar =$this->_getSeekerData($seeker_id,$path_data);
				if ($postvar['pageindex'] == 1 && $postvar['hddIsfirstPost'] == 1) {
					$log = "";
					foreach ($postvar as $k => $v)
						$log .= $k . "::" . $v . ";;";

					$searchlog_service = new base_service_company_resume_searchlog();
					$searchlog_service->addSearchLog($this->_userid, $log, 4);
				}

			}
		}

		$tip_service = new base_service_company_tip();
		//发生切换事件，则关闭引导信息
		if ($postvar['hddSortType'] == 2 && (empty($tip)))
			$tip_service->updateReadTip($this->_userid, $type=base_service_common_companytip::REFRESHTIP);

		// 引导信息
		$tip = $tip_service->getTips($field="*", $this->_userid, $type=base_service_common_companytip::REFRESHTIP);
		$this->_aParams['showTip'] = empty($tip) ? true : false;

		//加入自身的单位编号以用于筛选屏蔽了本单位的简历
		$postvar['cur_company_id'] = $this->_userid;

		//查询条件是否改变
		if($postvar['oldpostvar'] == $postvar['newpostvar'] && !$postvar['hddIsfirstPost']){
			$postvar['is_change'] = false;
		}
		else {
			$postvar['is_change'] = true;
		}
		if ($postvar['hddIsfirstPost']) {
			$postvar['group']=$postvar['group_json'];
//			$service_sphinx=new base_service_sphinx_sphinx();
//			$solrresult = $service_sphinx->resumeSearch($postvar);

			$postvar['person_class'] = 1;
			$service_solr = new base_service_solr_solr();
			$solrresult = $service_solr->resumeSearch($postvar);
			
			$postvar['group_json'] = $solrresult['group'];
			unset($postvar['oldpostvar']);
			$this->_aParams['postvar'] = $postvar;
			$this->_aParams['group'] = $this->_getGroupData($solrresult['group']);
			if (count($solrresult['resumeIDs'])) {
				$resume_ids = implode(",", $solrresult['resumeIDs']);
				$filter_ids = $this->_getFilterResumeID($resume_ids);
				$filter_id = array();
				$filter_id = array_diff($solrresult['resumeIDs'],$filter_ids);
				$fids = implode(",", $filter_id);
				$resume_data = $this->_bindData($fids);
			}

			//是否显示简历详细信息
			$service_company = new base_service_company_company();
			$isshowresumeinfo = $service_company->isShowResumeInfo($this->_userid);
			
			if (count($resume_data->items) > 0) {
				$items = $resume_data->items;

				$service_person = new base_service_person_person();
				$service_resume_work = new base_service_person_resume_work();
				$service_resume_remark = new base_service_company_resume_resumeremark();

				$person_ids = $this->_buildIDs($items,'person_id');
				$person_data = $service_person->GetPersonListByIDs($person_ids, 'person_id,user_name,sex,birthday2,cur_area_id,start_work,photo,small_photo,name_open,photo_open');
				$person_list = $this->_buildArray($person_data->items,"person_id");

				/*$work_data = $service_resume_work->getLastResumeWorkByResumeIDs($resume_ids,'work_id,resume_id,start_time,end_time,station');
				 $work_list = $this->_buildArray($work_data->items,'resume_id');
				 */
				$login_time_list = $solrresult['login_time'];
				//print_r($refresh_time_list);
				//查询备注列表
				$remark_data = $service_resume_remark->getLastRemarkByResumeIDs($this->_userid,$resume_ids,'remark_id,resume_id,company_id,remark');
				$remark_list = $this->_buildArray($remark_data->items,'resume_id');

				//摘要
				if ($postvar['islistorsummary'] == 2) {
					//毕业院校
					$service_edu = new base_service_person_resume_edu();
					$edu_data = $service_edu->getResumeEdus($resume_ids,'resume_id,school,major_desc,degree');
					$edu_list= $edu_data->items;// $this->_buildArray($edu_data->items,"resume_id");
					//工作经验

					$work_datas = $service_resume_work->getResumeWorks($resume_ids,'work_id,resume_id,start_time,end_time,station,company_name');
					foreach ($work_datas->items as $workskey => $worksvalue){
						$workslist[$worksvalue["resume_id"]][$workskey]['start_time'] = date('Y/m',strtotime($worksvalue['start_time']));
						$workslist[$worksvalue["resume_id"]][$workskey]['end_time'] = empty($worksvalue['end_time'])?"至今":date('Y/m',strtotime($worksvalue['end_time']));
						$workslist[$worksvalue["resume_id"]][$workskey]['station'] = $worksvalue['station'];
						if($isshowresumeinfo){
							$workslist[$worksvalue["resume_id"]][$workskey]['company_name'] = $worksvalue['company_name'];
						} else {
							$workslist[$worksvalue["resume_id"]][$workskey]['company_name'] = '';
						}
					}
				}

				for($i=0;$i<count($items);$i++){
					$resume_info = $items[$i];
					$resume_id =$resume_info['resume_id'];
					$person_id = $resume_info['person_id'];
					$person_info = $person_list["$person_id"];
					$resume_work_info = $work_list["$resume_id"];
					$resume_remark_info=$remark_list["$resume_id"];
					$login_time_info = $login_time_list["$resume_id"];
					//姓名
					if($person_info['name_open']==1 && $isshowresumeinfo){
						$items[$i]['user_name'] = $person_info['user_name'];
					}else{
						$sex_name = $person_info['sex']==1?'先生':'女士';
						$items[$i]['user_name']=mb_substr($person_info['user_name'],0,1,'utf-8').$sex_name;
					}

					$items[$i]['remark'] = $resume_remark_info['remark'];

					//头像性别、年龄、学历、当前所在地
					if($person_info['photo_open']!='0' && $isshowresumeinfo){
						if(empty($person_info['photo'])){
                                                    $person_info['photo'] = "";
                                                    $person_info['small_photo'] ="";
						}
						else{
							if(empty($person_info['small_photo'])){
                                                            $person_info['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$person_info['photo'];
							}else{
                                                            $person_info['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$person_info['small_photo'];
									
							}
							$person_info['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$person_info['photo'];
						}
                                        }else{
                                            $person_info['photo'] = "";
                                            $person_info['small_photo'] ="";
                                        }

					$items[$i]['photo'] = $person_info['photo'];
					$items[$i]['small_photo'] = $person_info['small_photo'];
					$items[$i]['sex'] = $person_info['sex'];
					$items[$i]['age'] = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']).'岁';
					$items[$i]['degree'] = $resume_info['degree_id'];
					$items[$i]['cur_area'] = $person_info['cur_area_id'];

					//工作年限

					$basic_start_work_year = base_lib_TimeUtil::date_diff_month($person_info['start_work']);
					$workY = floor($basic_start_work_year/12);
					$workM = intval($basic_start_work_year%12);

					if($workY<=0 && $workM<=6&&$workM>=-6){
						$basic_start_work_year = '应届毕业生';
					}else if($workY == 0 && $workM>6){
						$basic_start_work_year = $workM.'个月';
					}else if($basic_start_work_year<-6){
						$basic_start_work_year = '目前在读';
					}else{
						$basic_start_work_year = $workY.'年';
					}
					$items[$i]['start_work'] = $basic_start_work_year;
					if(empty($items[$i]['start_work'])){
						$items[$i]['start_work'] = "应届毕业生";
					}

					//最近工作经验
					if($resume_info['current_station']==''){
						$items[$i]['work'] = '无';
					}else {
						if(empty($resume_info['current_station_start_time'])){
							$items[$i]['work'] = $resume_info['current_station'];
						}else{
							$items[$i]['work']= $resume_info['current_station'].'('.base_lib_TimeUtil::date_diff_year3($resume_info['current_station_start_time'],$resume_info['current_station_end_time']).')';
						}
					}

					//刷新时间
					$items[$i]['login_time'] = base_lib_TimeUtil::to_friend_time2(date("Y-m-d H:i:s",$login_time_info));
					$items[$i]['refresh_time'] = base_lib_TimeUtil::to_friend_time2($resume_info['refresh_time']);

					//摘要
					if($postvar['islistorsummary']==2){
						$edu_info =$this->arrayFind($edu_list,'resume_id', $resume_id);
						
						$items[$i]['degree'] = $edu_info['degree'];
						$items[$i]['school'] = $edu_info['school'];
						$items[$i]['major_desc'] = $edu_info['major_desc'];
						$items[$i]['station'] = $resume_info['station'];
						if(count($workslist["$resume_id"])){
							$items[$i]['worklist'] = array_slice($workslist["$resume_id"],0,3);
						}
					}

				}
				$this->_aParams['issummary'] = $postvar['islistorsummary']==2;
				$data['list'] = $items;
				$this->_aParams['resumedata'] = $data['list'];
				$this->_aParams['words'] = $solrresult['words'];

				if(!empty($postvar['keyword'])){
					if($postvar['word_slipt']){
						$redKeyword = implode('|', $solrresult['words']);
					}
					else{
						$redKeyword = $postvar['keyword'];
					}
						
					$redKeyword = str_replace('/','',$redKeyword);
					$redKeyword = str_replace('\\','',$redKeyword);
					$this->_aParams['redKeyword'] = urlencode($redKeyword);
				}
				//分页
				$pager = $this->pageBarFullPath($solrresult['total'],$postvar['pagesize'],$postvar['pageindex'],$inPath);
				$this->_aParams['pager'] = $pager;
			}
		} else {
			$this->_aParams['postvar'] = $postvar;
		}
		return $this->render('./resume/search.html', $this->_aParams);
	}

	public function pageRefreshTip($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$tip_service = new base_service_company_tip();
		$tip_service->updateReadTip($this->_userid, $type=base_service_common_companytip::REFRESHTIP);

		echo header("Content-type:text/json;charset=utf-8");
		echo json_encode($arr=array('status'=>true));
		exit();
	}

	public function pageSolr($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		
		$service_degree = new base_service_common_degree();
		$degree['0']='不限';
		$degree = array_merge($degree,$service_degree->getAll());
		$this->_aParams['degree'] = $degree;
		$service_sex = new base_service_common_sex();
		$sex['0'] = '不限';
		$sex = array_merge($sex,$service_sex->getSex());
		$this->_aParams['sex'] = $sex;

		$service_marriage = new base_service_common_marriage();
		$marriage['0'] = '不限';
		$marriage = array_merge($marriage,$service_marriage->getMarriage());
		$this->_aParams['marriage'] = $marriage;

		$seeker_id = $path_data['seekerid'];
		$job_id = $path_data['job_id'];
		if(!empty($job_id)){
			$service_job = new base_service_company_job_job();
			$jobinfo = $service_job->getJob($job_id,'station,work_year_id,allow_graduate,degree_id,sex,age_lower,age_upper');
			if(count($jobinfo)>0){
				if($jobinfo['allow_graduate']!=1){
					$postvar['workyear_min'] = intval($jobinfo['work_year_id'])?intval($jobinfo['work_year_id']):0;
				}else{
					$postvar['workyear_min'] = 0;
				}				
				$postvar['workyear_max'] = 0;
				//学历
				$degree_ids = array();
				$postvar['degree_ids'] = array(0=>0);
				$degree_id = intval($jobinfo['degree_id'])?intval($jobinfo['degree_id']):1;
				if($degree_id>1){
					for($i=$degree_id;$i<9;$i++){
						array_push($degree_ids,'0'.$i);
					}
					if(count($degree_ids)>0){
						$postvar['degree_ids'] = $degree_ids;
					}
				}
				//性别
				if($jobinfo['sex']>0){
					$postvar['sex']= $jobinfo['sex'];
				}
				if(!empty($jobinfo['age_lower'])){
					$postvar['age_lower'] = $jobinfo['age_lower'];
				}

				if(!empty($jobinfo['age_upper'])){
					$postvar['age_upper'] = $jobinfo['age_upper'];
				}
				
				if(!empty($jobinfo['station'])){
					$postvar['keyword'] = $jobinfo['station'];
				}
			}			
			$postvar['keyword_type'] = 0;
			$postvar['pageindex'] = base_lib_BaseUtils::getStr($path_data['page'],'int',1);
			$postvar['pagesize'] =  base_lib_BaseUtils::getStr($path_data['hddPageSize'],'int',20);
			$postvar['islistorsummary']= 1;
			$postvar['hddIsfirstPost'] = 1;
				
		}else{
			if(empty($seeker_id)){
				//获取请求数据
				$postvar = $this->_getData($path_data);
			}
			else{
				$postvar =$this->_getSeekerData($seeker_id,$path_data);
			}
		}
		
		
		//查询条件是否改变
		if($postvar['oldpostvar'] == $postvar['newpostvar'] && !$postvar['hddIsfirstPost']){
			$postvar['is_change'] = false;
		}
		else {
			$postvar['is_change'] = true;
		}
		if($postvar['hddIsfirstPost']){
			$postvar['group']=$postvar['group_json'];
			$service_sphinx=new base_service_sphinx_sphinx();
			$sphinxresult = $service_sphinx->resumeSearch($postvar);

			$postvar['group_json'] = $sphinxresult['group'];
			unset($postvar['oldpostvar']);
			$this->_aParams['postvar'] = $postvar;
			$this->_aParams['group'] = $this->_getGroupData($sphinxresult['group']);
			if(count($sphinxresult['resumeIDs'])){
				$resume_ids = implode(",", $sphinxresult['resumeIDs']);
				$filter_ids = $this->_getFilterResumeID($resume_ids);
				$filter_id = array();
				$filter_id = array_diff($sphinxresult['resumeIDs'],$filter_ids);
				$fids = implode(",", $filter_id);
				$resume_data = $this->_bindData($fids);
			}

			//是否显示简历详细信息
			$service_company=new base_service_company_company();
			$isshowresumeinfo = $service_company->isShowResumeInfo($this->_userid);
			
			if(count($resume_data->items)>0){
				$items = $resume_data->items;

				$service_person = new base_service_person_person();
				$service_resume_work = new base_service_person_resume_work();
				$service_resume_remark = new base_service_company_resume_resumeremark();

				$person_ids = $this->_buildIDs($items,'person_id');
				$person_data = $service_person->GetPersonListByIDs($person_ids, 'person_id,user_name,sex,birthday2,cur_area_id,start_work,photo,small_photo,name_open,photo_open');
				$person_list = $this->_buildArray($person_data->items,"person_id");

				/*$work_data = $service_resume_work->getLastResumeWorkByResumeIDs($resume_ids,'work_id,resume_id,start_time,end_time,station');
				 $work_list = $this->_buildArray($work_data->items,'resume_id');
				 */
				$refresh_time_list = $sphinxresult['refresh_time'];				
				//print_r($refresh_time_list);
				//查询备注列表
				$remark_data = $service_resume_remark->getLastRemarkByResumeIDs($this->_userid,$resume_ids,'remark_id,resume_id,company_id,remark');
				$remark_list = $this->_buildArray($remark_data->items,'resume_id');

				//摘要
				if($postvar['islistorsummary']==2){
					//毕业院校
					$service_edu = new base_service_person_resume_edu();
					$edu_data = $service_edu->getResumeEdus($resume_ids,'resume_id,school,major_desc,degree');
					$edu_list= $edu_data->items;// $this->_buildArray($edu_data->items,"resume_id");
					//工作经验

					$work_datas = $service_resume_work->getResumeWorks($resume_ids,'work_id,resume_id,start_time,end_time,station,company_name');
					foreach ($work_datas->items as $workskey=>$worksvalue){
						$workslist[$worksvalue["resume_id"]][$workskey]['start_time'] = date('Y/m',strtotime($worksvalue['start_time']));
						$workslist[$worksvalue["resume_id"]][$workskey]['end_time'] = empty($worksvalue['end_time'])?"至今":date('Y/m',strtotime($worksvalue['end_time']));
						$workslist[$worksvalue["resume_id"]][$workskey]['station'] = $worksvalue['station'];
						if($isshowresumeinfo){
							$workslist[$worksvalue["resume_id"]][$workskey]['company_name'] = $worksvalue['company_name'];
						}else {
							$workslist[$worksvalue["resume_id"]][$workskey]['company_name'] = '';
						}
					}
				}

				for($i=0;$i<count($items);$i++){
					$resume_info = $items[$i];
					$resume_id =$resume_info['resume_id'];
					$person_id = $resume_info['person_id'];
					$person_info = $person_list["$person_id"];
					$resume_work_info = $work_list["$resume_id"];
					$resume_remark_info=$remark_list["$resume_id"];
					$refresh_time_info = $refresh_time_list["$resume_id"];
					//姓名
					if($person_info['name_open']==1 && $isshowresumeinfo){
						$items[$i]['user_name'] = $person_info['user_name'];
					}else{
						$sex_name = $person_info['sex']==1?'先生':'女士';
						$items[$i]['user_name']=mb_substr($person_info['user_name'],0,1,'utf-8').$sex_name;
					}

					$items[$i]['remark'] = $resume_remark_info['remark'];

					//头像性别、年龄、学历、当前所在地
					if($person_info['photo_open']!='0' && $isshowresumeinfo){
						if(empty($person_info['photo'])){
							$person_info['photo'] = "";
							$person_info['small_photo'] ="";
						}
						else{
							if(empty($person_info['small_photo'])){
								$person_info['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$person_info['photo'];
							}else{
								$person_info['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$person_info['small_photo'];
									
							}
							$person_info['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$person_info['photo'];
						}
					}


					$items[$i]['photo'] = $person_info['photo'];
					$items[$i]['small_photo'] = $person_info['small_photo'];
					$items[$i]['sex'] = $person_info['sex'];
					$items[$i]['age'] = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']).'岁';
					$items[$i]['degree'] = $resume_info['degree_id'];
					$items[$i]['cur_area'] = $person_info['cur_area_id'];

					//工作年限

					$basic_start_work_year = base_lib_TimeUtil::date_diff_month($person_info['start_work']);
					$workY = floor($basic_start_work_year/12);
					$workM = intval($basic_start_work_year%12);

					if($workY<=0 && $workM<=6&&$workM>=-6){
						$basic_start_work_year = '应届毕业生';
					}else if($workY == 0 && $workM>6){
						$basic_start_work_year = $workM.'个月';
					}else if($basic_start_work_year<-6){
						$basic_start_work_year = '目前在读';
					}else{
						$basic_start_work_year = $workY.'年';
					}
					$items[$i]['start_work'] = $basic_start_work_year;
					if(empty($items[$i]['start_work'])){
						$items[$i]['start_work'] = "应届毕业生";
					}

					//最近工作经验
					if($resume_info['current_station']==''){
						$items[$i]['work'] = '无';
					}else {
						if(empty($resume_info['current_station_start_time'])){
							$items[$i]['work'] = $resume_info['current_station'];
						}else{
							$items[$i]['work']= $resume_info['current_station'].'('.base_lib_TimeUtil::date_diff_year3($resume_info['current_station_start_time'],$resume_info['current_station_end_time']).')';
						}
					}


					//刷新时间
					$items[$i]['refresh_time'] = base_lib_TimeUtil::to_friend_time2(date("Y-m-d H:i:s",$refresh_time_info));

					//摘要
					if($postvar['islistorsummary']==2){
						$edu_info =$this->arrayFind($edu_list,'resume_id', $resume_id);
						
						$items[$i]['degree'] = $edu_info['degree'];
						$items[$i]['school'] = $edu_info['school'];
						$items[$i]['major_desc'] = $edu_info['major_desc'];
						$items[$i]['station'] = $resume_info['station'];
						if(count($workslist["$resume_id"])){
							$items[$i]['worklist'] = array_slice($workslist["$resume_id"],0,3);
						}
					}

				}
				$this->_aParams['issummary'] = $postvar['islistorsummary']==2;
				$data['list'] = $items;
				$this->_aParams['resumedata'] = $data['list'];
				$this->_aParams['words'] = $sphinxresult['words'];

				if(!empty($postvar['keyword'])){
					if($postvar['word_slipt']){
						$redKeyword = implode('|', $sphinxresult['words']);
					}
					else{
						$redKeyword = $postvar['keyword'];
					}
						
					$redKeyword = str_replace('/','',$redKeyword);
					$redKeyword = str_replace('\\','',$redKeyword);
					$this->_aParams['redKeyword'] = urlencode($redKeyword);
				}
				//分页
				$pager = $this->pageBarFullPath($sphinxresult['total'],$postvar['pagesize'],$postvar['pageindex'],$inPath);
				$this->_aParams['pager'] = $pager;
			}
		}
		else{
			$this->_aParams['postvar'] = $postvar;
		}
		return $this->render('./resume/search.html', $this->_aParams);
	}
	
   /**
    * 
    * 数组查询
    * @param array $arr
    * @param string $key
    * @param string $value
    */
   private function arrayFind($arr,$property,$value) {
   	   $obj = null;
   	   foreach ($arr as $item){
	   	  if($item[$property]==$value) {
	   	  	  $obj =  $item;
	   	  	  break;
	   	  }
	   }
	   return  $obj;
   }

	private function _getData($path_data){
		//关键词
		$postvar['keyword'] = base_lib_BaseUtils::getStr($path_data['txtKeyword'],'string','');
		$postvar['word_slipt'] = base_lib_BaseUtils::getStr($path_data['chkIsSplitKeyword'],'int',0);

		//年龄
		$postvar['age_lower'] = base_lib_BaseUtils::getStr($path_data['txtAgeLower'],'int','');
		$postvar['age_upper'] = base_lib_BaseUtils::getStr($path_data['txtAgeUpper'],'int','');
			
		//工作年限
		$postvar['workyear_min'] = base_lib_BaseUtils::getStr($path_data['ddlMinWrokYear'],'int',0);
		$postvar['workyear_max'] = base_lib_BaseUtils::getStr($path_data['ddlMaxWrokYear'],'int',0);

		//学历
		$postvar['degree_ids'] = base_lib_BaseUtils::getStr($path_data['chkDegree'],'array',array(0=>0));
		//性别
		$postvar['sex']= base_lib_BaseUtils::getStr($path_data['radSex'],'int',0);
		//婚姻状况
		$postvar['marriage']= base_lib_BaseUtils::getStr($path_data['radMarriage'],'int',0);
			
		//身高
		$postvar['stature_min'] = base_lib_BaseUtils::getStr($path_data['txtMinStature'],'int','');
		$postvar['stature_max'] = base_lib_BaseUtils::getStr($path_data['txtMaxStature'],'int','');
			
			
		//当前地区
		if(!empty($path_data['currArea'])){
			$postvar['cur_areas'] = explode(',',$path_data['currArea']);
			//$postvar['curarea'] = $path_data['currArea'];
			$postvar['curarea'] = "'".implode("','", $postvar['cur_areas']). "'";
		}
			
		//期望地区
		if(!empty($path_data['expArea'])){
			$postvar['exp_areas'] = explode(',',$path_data['expArea']);
			$postvar['expArea'] = "'".implode("','", $postvar['exp_areas']). "'";

		}
			
		//职位类别
		if(!empty($path_data['jobsort'])){
			$postvar['jobsorts'] =  explode(',',$path_data['jobsort']);
			$postvar['jobsort'] =  "'".implode("','", $postvar['jobsorts']). "'";
		}
			
		//行业类别
		if(!empty($path_data['calling'])){
			$postvar['callings'] =  explode(',',$path_data['calling']);
			$postvar['calling'] =  "'".implode("','", $postvar['callings']). "'";
		}
			
		//户籍
		if(!empty($path_data['nativeArea'])){
			$postvar['native_areas'] = explode(',',$path_data['nativeArea']);
			$postvar['nativeArea'] =  "'".implode("','", $postvar['native_areas']). "'";
		}
		$postvar['newpostvar']      = md5(urlencode(json_encode($postvar)));
		$postvar['oldpostvar']      = base_lib_BaseUtils::getStr($path_data['hddpostvar'],'string',"");
		$postvar['group_json']      = base_lib_BaseUtils::getStr($path_data['hddGroupJson'],'string',"");
		$postvar['keyword_type']    = base_lib_BaseUtils::getStr($path_data['hddKeytype'],'int',0);
		$postvar['pageindex']       = base_lib_BaseUtils::getStr($path_data['page'],'int',1);
		$postvar['pagesize']        = base_lib_BaseUtils::getStr($path_data['hddPageSize'],'int',20);
		$postvar['islistorsummary'] = base_lib_BaseUtils::getStr($path_data['hddIsList'],'int',1);
		$postvar['hddIsfirstPost']  = base_lib_BaseUtils::getStr($path_data['hddIsfirstPost'],'int',0);
		$postvar['hddSortType']     = base_lib_BaseUtils::getStr($path_data['hddSortType'], 'int', 1);

		$degree_ids  = implode(",", $postvar['degree_ids']);
		$buildseeker = "jobsort::{$path_data['jobsort']};;calling::{$path_data['calling']};;"
		."degree_ids::{$degree_ids};;"
		."work_year1::{$postvar['workyear_min']};;work_year2::{$postvar['workyear_max']};;"
		."age_lower::{$postvar['age_lower']};;age_upper::{$postvar['age_upper']};;"
		."sex::{$postvar['sex']};;stature_min::{$postvar['stature_min']};;"
		."stature_max::{$postvar['stature_max']};;exp_area_id::{$path_data['expArea']};;"
		."cur_area_id::{$path_data['currArea']};;nativeArea::{$path_data['nativeArea']};;"
		."keyword::{$postvar['keyword']};;marriage::{$postvar['marriage']};;"
		."searchKeywordType::{$postvar['keyword_type']};;";
		$postvar['buildseeker'] = urlencode($buildseeker);
		return $postvar;
	}

	//通过搜索器搜索
	private function _getSeekerData($seeker_id,$path_data){
		$service_seeker = new base_service_company_resume_seeker();

		$seeker = $service_seeker->getSeeker($this->_userid,$seeker_id,"content");

		if(!empty($seeker['content'])){
			$seeker_content = urldecode($seeker['content']);
			$seeker_items = explode(";;",$seeker_content);
			$postvar['workyear_min'] = 0;
			$postvar['workyear_max'] = 0;
			$postvar['hddSortType']  = 1;
			array_pop($seeker_items);
			foreach ($seeker_items as $items) {
				$item = explode("::",$items);
				switch($item[0]){
					case 'jobsort':
						$jobsort_arr =  explode(',',$item[1]);
						$postvar['jobsort'] =  "'".implode("','", $jobsort_arr). "'";
						break;
					case 'calling':
						$calling_arr =  explode(',',$item[1]);
						$postvar['calling'] =  "'".implode("','", $calling_arr). "'";
						break;
					case 'degree_ids':
						$degree_ids_arr = explode(',',$item[1]);
						$postvar['degree_ids'] = $degree_ids_arr;
						break;
					case 'work_year1':
						$postvar['workyear_min'] = empty($item[1])?0:$item[1];
						break;
					case'work_year2':
						$postvar['workyear_max'] = empty($item[1])?0:$item[1];
						break;
					case 'age_lower':
						$postvar['age_lower'] = $item[1];
						break;
					case 'age_upper':
						$postvar['age_upper'] = $item[1];
						break;
					case 'sex':
						$postvar['sex'] = $item[1];
						break;
					case 'stature_min':
						$postvar['stature_min'] = $item[1];
						break;
					case 'stature_max':
						$postvar['stature_max'] = $item[1];
						break;
					case 'exp_area_id':
						$exp_areas_arr = explode(',',$item[1]);
						$postvar['expArea'] = "'".implode("','", $exp_areas_arr). "'";
						break;
					case 'cur_area_id':
						$cur_areas_arr = explode(',',$item[1]);
						$postvar['curarea'] = "'".implode("','", $cur_areas_arr). "'";
						break;
					case 'nativeArea':
						$nativeArea_arr = explode(',',$item[1]);
						$postvar['nativeArea'] =  "'".implode("','", $nativeArea_arr). "'";
						break;
					case 'keyword':
						$postvar['keyword'] = $item[1];
						break;
					case 'marriage':
						$postvar['marriage'] = intval($item[1],0);
						break;
					case 'searchKeywordType':
						$postvar['keyword_type'] = $item[1];
						break;

				}
			}
		}
		else{
			$postvar['marriage'] =0;
			$postvar['curarea'] ='';
			$postvar['nativeArea']='';
			$postvar['expArea'] ='';
			$postvar['calling'] ='';
			$postvar['jobsort'] = '';
			$postvar['sex']=0;
			$postvar['degree_ids'] = array(0=>0);
			$postvar['workyear_min']=0;
			$postvar['workyear_max']=0;

		}
		$postvar['keyword_type'] = 0;
		$postvar['pageindex'] = base_lib_BaseUtils::getStr($path_data['page'],'int',1);
		$postvar['pagesize'] =  base_lib_BaseUtils::getStr($path_data['hddPageSize'],'int',20);
		$postvar['islistorsummary']= 1;
		$postvar['hddIsfirstPost'] = 1;
		return $postvar;
	}

	private function _getGroupData($group){
		if(!empty($group)){
			$group_items = explode(";;",$group);
			array_pop($group_items);
			foreach ($group_items as $items){
				$item = explode("::",$items);
				switch($item[0]){
					case 'resumeWorkExp':
						$postvar['group']['resumeWorkExp'] =  $item[1];
						break;
					case 'resumeWorkwork':
						$postvar['group']['resumeWorkwork'] =  $item[1];
						break;
					case 'resumeproject':
						$postvar['group']['resumeproject'] = $item[1];
						break;
					case 'resumeExp':
						$postvar['group']['resumeExp'] = $item[1];
						break;
					case 'resumeSkill':
						$postvar['group']['resumeSkill'] = $item[1];
						break;
					case 'resumeCertificate':
						$postvar['group']['resumeCertificate'] = $item[1];
						break;
					case 'resumeEduTrain':
						$postvar['group']['resumeEduTrain'] = $item[1];
						break;
					case 'resumeSuit':
						$postvar['group']['resumeSuit'] = $item[1];
						break;
					case 'all':
						$postvar['group']['all'] = $item[1];
						break;
				}
			}
		}
		return $postvar['group'];
	}

	private function _bindData($resume_ids){
		if(empty($resume_ids)){
			return null;
		}
		$service_resume = new base_service_person_resume_resume();
		$resumes = $service_resume->getResumeListByIDs($resume_ids, "resume_id,resume_name,person_id,is_chinese_resume,degree_id,current_station,current_station_start_time,current_station_end_time,refresh_time,station");
		return $resumes;
	}

	private function _buildArray($arr,$filer="resume_id"){
		//if($arr.length<=0) return array();
		if(!is_array($arr) || count($arr)<=0) return $arr;
		foreach ($arr as $key=>$value){
			$new_arr[$value["$filer"]] = $value;
		}
		return $new_arr;
	}

	private function _buildIDs($arr,$filer="resume_id"){
		if(!is_array($arr) || count($arr)<=0) return $arr;
		foreach ($arr as $key=>$value){
			$newArr[] = $value["$filer"];
		}
		return implode(',',$newArr);
	}

	/**
	 * 简历搜索显示的字段弹窗
	 */
	public function pageField($inPath){
		//需要要增加表
		return $this->render('./resume/searchfield.html', $this->_aParams);
	}

	/**
	 * 简历搜索显示的字段	修改
	 */
	public function pageFieldModDo($inPath){
		//最多7项,返回json
			
	}

	/**
	 * 添加到搜索器弹窗
	 */
	public function pageSeekerAdd($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$this->_aParams['keyword'] = base_lib_BaseUtils::getStr($path_data['keyword']);
		return $this->render('./resume/searchseekeradd.html',$this->_aParams);
	}

	//检查单位是否能添加简历搜索器
	public function pageCanAddSeeker($inPath){
		$service_seeker = new base_service_company_resume_seeker();
		$seeker_count = $service_seeker->getCompanySeekerCount($this->_userid);

		$json_arr['status'] = "succeed";
		$xml = SXML::load("../config/company/company.xml");
		if(!is_null($xml)){
			$maxResumeSeekerNum = $xml->MaxResumeSeekerNum;
		}
		if($seeker_count>=$maxResumeSeekerNum){
			$json_arr['status'] = "fail";
			$json_arr['msg'] = "您最多可以添加{$maxResumeSeekerNum}个简历搜索器，请先删除不用的简历搜索器。";
		}
		echo json_encode($json_arr);
		return;
	}


	/**
	 * 搜索器保存
	 */
	public function pageSeekerAddDo($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

		//保存搜索器到数据库
		$seeker_name = base_lib_BaseUtils::getStr($path_data['seekerName']);

		if(empty($seeker_name)){
			$json_arr['error'] = "搜索器名称不能为空";
			echo json_encode($json_arr);
			return ;
		}

		$service_seeker = new base_service_company_resume_seeker();
		$isUnique = $service_seeker -> isUniqueSeekerName($this->_userid,$seeker_name);

		if(!$isUnique){
			$json_arr['error'] = "搜索器名称已存在，请指定其他名称。";
			echo json_encode($json_arr);
			return ;
		}

		$seeker_conter = base_lib_BaseUtils::getStr($path_data['seekerconter'],'string',"");
		$seeker['company_id'] = $this->_userid;
		$seeker['seeker_name'] = $seeker_name;
		$seeker['content'] = urldecode($seeker_conter);
		$seeker['create_time'] = date('Y-m-d H:i:s');
		$service_seeker->AddResumeSeeker($seeker);

		//返回json
		$json_arr['status'] = "succeed";
		echo json_encode($json_arr);
		return;
	}

	/**
	 * 搜索器列表弹窗
	 */
	public function pageSeeker($inPath){
		$service_seeker = new base_service_company_resume_seeker();
		$seeker_list = $service_seeker ->getResumeSeekerList($this->_userid,"seeker_id,seeker_name");
		$items = $seeker_list->items;
		$this->_aParams['data'] = $items;
		return $this->render('./resume/searchseeker.html',$this->_aParams);
	}

	/**
	 * 删除搜索器
	 */
	public function pageSeekerDel($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$seekerid= base_lib_BaseUtils::getStr($path_data['seekerid'],'int',0);
		$service_seeker = new base_service_company_resume_seeker();

		$result = $service_seeker->delSeeker($this->_userid, $seekerid);
		if(empty($result)){
			$json_arr['error'] = "删除简历搜索器失败。";
			echo json_encode($json_arr);
			return ;
		}

		$json_arr['seeker_id'] = $seekerid;
		echo json_encode($json_arr);
		return;
	}

	public function pageSearchID($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_id = base_lib_BaseUtils::getStr($path_data['resume_id'],"int",0);
		$json_arr['status'] = 'succeed';
		$service_resume = new base_service_person_resume_resume();
		//define('DEBUG', true);
		$resume = $service_resume->getResume($resume_id, "resume_id");
		if(empty($resume)){
			$json_arr['status'] = 'fail';
			echo json_encode($json_arr);
			return;
		}

		$json_arr['resumeid'] = $resume['resume_id'];
		echo json_encode($json_arr);
		return ;
	}


	public function _getFilterResumeID($resume_ids){
		$service_resume = new base_service_person_resume_resume();
		$resumes = $service_resume->getResumeListByIDs($resume_ids, "person_id,resume_id");
		$service_blench = new base_service_person_blench();
		$filter_id = array();
		if(count($resumes)){
			foreach ($resumes->items as $resume){
				$blenchs = $service_blench->getAllBlenchKeyList($resume['person_id'],'person_id,type,com_keyword,company_id');
				$blenchs_items = $blenchs->items;
				if(!base_lib_BaseUtils::nullOrEmpty($blenchs_items)){
					for($i =0 ; $i< count($blenchs_items);$i++){
						if($blenchs_items[$i]['type']==0){
							if($this->_getKeySetResult($blenchs_items[$i]['com_keyword'])){
								array_push($filter_id,$resume['resume_id']);
							}
						}
				 		else{
				 			if($blenchs_items[$i]['company_id']==$this->_userid){
				 				array_push($filter_id,$resume['resume_id']);
				 			}
				 		}
					}
				}
			}
		}
		return $filter_id;
	}

	public function _getKeySetResult($com_keyword){
		//$servcie_sphinx = new base_service_sphinx_sphinx();
		$service_solr = new base_service_solr_solr();
		$postvar['keyword'] = $com_keyword;
		$result= $service_solr->companySearch($postvar);
		if(isset($result['companys']) && count($result['companys'])){
			if($this->arrayFind($result['companys'],'company_id',$this->_userid) != null){
				return true;
			}
			else{
				return false;
			}
		}else{
			return false;
		}
	}


}
?>