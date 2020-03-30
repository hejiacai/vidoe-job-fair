<?php

/**
 * 专题企业 相关统计查询
 * @desc   ：专题企业（区团委）统计数据查看 需登录
 * @Date   : 2019/5/14 0014 下午 2:26
 * @author ：PengCG
 */
class controller_topiccompanystatic extends components_cbasepage {
	
	private $index_tile = array (
		'goverment_name'  => '区团委',
		'company_name'    => '企业名称',
		'company_id'      => '企业编号',
		'pub_job_num'     => '发布职位',
		'fair_person_num' => '招聘人数',
		'apply_num'       => '收到投递',
		'accept_num'      => '面试通过',
		'refuse_num'      => '面试不通过',
		'giveup_num'      => '放弃面试',
	);
	private $cur_menu_id = '';
	private $menu_ids = array ();
	private $all_menu = array ();
	
	function __construct() {
		parent::__construct(true, 'c');
		$specillist_service = new base_service_company_speciallist();
		$can_pass = $specillist_service->getCompanySpecial($this->_userid, base_lib_BaseUtils::getCookie('username'), 1);
		if(!$can_pass && !$can_pass['menu_ids']) {
			exit('<h1>Sorry,this account is forbided to visit ! </h1>');
		}
		//判断是否有当前的菜单权限，没有的话跳转到有权限的第一个菜单
		$splitFlag = preg_quote(SlightPHP::$splitFlag, "/");
		if(!empty($_SERVER["PATH_INFO"])) {
			$path_array = preg_split("/[$splitFlag\/]/", $_SERVER["PATH_INFO"], -1, PREG_SPLIT_NO_EMPTY);
		}
		$entry = !empty($path_array[2]) ? $path_array[2] : SlightPHP::$defaultEntry;
		$base_serv_companymenu = new base_service_common_companymenu();
		$companymenu = $base_serv_companymenu->getMenuAll();
		$companymenu = base_lib_BaseUtils::array_key_assoc($companymenu, 'id');
		$this->all_menu = $companymenu;
		//判断当前人是不是有权限
		$menu_id = $base_serv_companymenu->getMenuByFunction($entry);
		$menu_ids = explode(',', $can_pass['menu_ids']);
		$this->cur_menu_id = $menu_id;
		$this->menu_ids = $menu_ids;
		if(!$menu_id) {
			exit('<h1>Sorry,this account is forbided to visit ! </h1>');
		}
		if(!in_array($menu_id, $menu_ids)) {
			//跳转到当确认有权限的页面
			foreach ($menu_ids as $id) {
				if($companymenu[ $id ]) {
					$this->redirect(base_lib_Constant::MAIN_URL_NO_HTTP . $companymenu[ $id ]['to_url']);
				}
			}
			exit('<h1>Sorry,this account is forbided to visit ! </h1>');
		}
	}
	
	/**
	 * 校园招聘会数据统计
	 * @param $inPath
	 * @return mixed
	 */
	public function pageVideo($inPath) {
		$this->_aParams['menu_ids'] = $this->menu_ids;
		$this->_aParams['all_menu'] = $this->all_menu;
		$this->_aParams['cur_menu_id'] = $this->cur_menu_id;
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$sid = base_lib_BaseUtils::getStr($params['sid'], 'int', null);
		$start_time = base_lib_BaseUtils::getStr($params['start_time'], 'string', null);
		$end_time = base_lib_BaseUtils::getStr($params['end_time'], 'string', null);
		$is_download = base_lib_BaseUtils::getStr($params['is_download'], 'int', '');
		$page = base_lib_BaseUtils::getStr($params['page'], 'int', 1);
		$page_size = 20;
		
		$service_shuangxuannet = new base_service_netfair_net();
		$activity_list = $service_shuangxuannet->getSelectList();
		if(!$sid) {
			$sid = $activity_list[0]['id'];
		}
		//当前活动
		$now_activity = $service_shuangxuannet->getShuangXuanNetByID($sid, 'id,speciality,degree_id,is_grade,is_class,is_student_id,is_academic_advisor,is_jobsort,is_department,is_poor_family');
		$this->_aParams['now_activity'] = $now_activity;
		$service_shuangxuanpersonenter = new base_service_netfair_personenternet();
		$fields = 'sid,person_id,student_name,student_school,student_grade,student_class,student_id,student_name,academic_advisor,student_department,create_time,major_desc';
		if(1 == $is_download) {
			//获取锁
			$ser_redis_lock = new base_service_redis_redisLock();
			$lock_name = "topiccompanystatic:$sid";
			$lock_auth = $ser_redis_lock->acquire_lock($lock_name, 1, 120);
			if(!$lock_auth) {
				return $this->render('404.html', array ('error_msg' => '请求失败,操作过于频繁,请2分钟后重试'));
				//$ser_redis_lock->release_lock($lock_name, $lock_auth);
			}
			
			$list = $service_shuangxuanpersonenter->getNetPerson($sid, $fields, 'order by create_time desc');
		}
		else {
			$list = $service_shuangxuanpersonenter->getList($sid, $params, $fields, $page, $page_size, 'order by create_time desc');
		}
		$enter_list = $list->items;
		//最早报名的条记录
		$min_personenter = $service_shuangxuanpersonenter->getRecord(array ('sid' => $sid), 'id,sid,create_time', 'order by create_time asc');
		//var_dump($enter_list);
		$service_login_log_data_huibo = array ();
		$service_login_log_data_km = array ();
		if(!empty($enter_list)) {
			$person_ids = base_lib_BaseUtils::getProperty($enter_list, 'person_id');
			
			$ser_netfair_person = new base_service_netfair_person();
			$person_list = $ser_netfair_person->GetPersonDataByIds($person_ids, 'person_id,user_name,sex,birthday,netfair_resume_id,ext_data');
			$netfair_resume_ids = base_lib_BaseUtils::getProperty($person_list, 'netfair_resume_id');
			
			$huibo_personids = array ();
			$blue_personids = array ();
			if($person_list) {
				foreach ($person_list as $key => $val) {
					if($val['source'] == 1) {//汇博
						$huibo_personids[] = $val['person_id'];
					}
					else if($val['source'] == 2) {//快米
						$blue_personids[] = $val['person_id'];
					}
				}
			}
			
			if(@$huibo_personids) {
				$service_login_log = new base_service_person_loginlog();
				$service_login_log_data_huibo = $service_login_log->getLoginPerson($huibo_personids, array (
					'start_time' => $min_personenter['create_time'],
					'origin'     => 'App',
				), 'distinct person_id');
				$service_login_log_data_huibo = base_lib_BaseUtils::array_key_assoc($service_login_log_data_huibo, 'person_id');
			}
			
			//快米
			if(@$blue_personids) {
				$service_blue_login_record = new base_service_blue_person_loginlog();
				$service_login_log_data_km = $service_blue_login_record->getLoginPerson1($blue_personids, [
					'start_time' => $start_time,
					'origins'    => array (
						'15',
						'16',
					),
				], 'person_id');
				$service_login_log_data_km = base_lib_BaseUtils::array_key_assoc($service_login_log_data_km, 'person_id');
			}
			
			//简历
			$resume_list = array ();
			if($netfair_resume_ids) {
				$ser_netfair_resume = new base_service_netfair_resume();
				$resume_list = $ser_netfair_resume->GetResumeDataByIds($netfair_resume_ids, 'resume_id,person_id,school,major_desc,complete_percent,appraise,resume_project_data,jobsort_exp_data');
			}
			
			
			//视频面试
			if(1 == $is_download) {
				$query_data['start_time'] = $start_time;
				$query_data['end_time'] = $end_time;
				// $query_data['source'] = 1;
			}
			else {
				$query_data = array ();
				//  $query_data['source'] = 1;
			}
			
			$service_shuangxuanpersonapply = new base_service_netfair_personapplynet();
			$person_apply_list = $service_shuangxuanpersonapply->getNetPersonInfo($sid, $person_ids, $query_data);
			//关联投递
			$service_shuangxuanpersonapplyrelate = new base_service_netfair_personapplyrelatenet();
			$relate_list = $service_shuangxuanpersonapplyrelate->getNetPersonApplyStatus($sid, $person_ids, $query_data);
			
			$common_sex = new base_service_common_sex();
			$common_sex_list = $common_sex->getSex();
			
			//专业
			$service_person_enter = new base_service_netfair_personenternet();
			$person_enter = $service_person_enter->getPersonMajorDescs($sid, $person_ids);
			if(!$person_enter) {
				$person_enter = array ();
			}
			else {
				$person_enter = base_lib_BaseUtils::array_key_assoc($person_enter, 'person_id');
			}
			$service_jobsort = new base_service_common_jobsort();
			foreach ($enter_list as &$val) {
				$val['create_time'] = substr($val['create_time'], 0, 10);
				$val['resume_id'] = $person_list[ $val['person_id'] ]['netfair_resume_id'];
				$val['netfair_source'] = $person_list[ $val['person_id'] ]['source'];
				$val['base_person_id'] = $person_list[ $val['person_id'] ]['person_id'];
				$val['age'] = base_lib_TimeUtil::ceil_diff_year($person_list[ $val['person_id'] ]['birthday']);
				$val['user_name'] = $person_list[ $val['person_id'] ]['user_name'];
				$val['sex_name'] = $common_sex_list[ $person_list[ $val['person_id'] ]['sex'] ] ?: '未知';
				if($val['netfair_source'] == 1) {
					$val['is_app'] = isset($service_login_log_data_huibo[ $val['base_person_id'] ]) ? '是' : '否';
				}
				else {
					$val['is_app'] = isset($service_login_log_data_km[ $val['base_person_id'] ]) ? '是' : '否';
				}
				$val['school_name'] = $resume_list[ $val['resume_id'] ]['school'];
				// $val['major_desc'] = $resume_list[ $val['resume_id'] ]['major_desc'];
				
				if(!isset($resume_list[ $val['resume_id'] ]) ||
					$resume_list[ $val['resume_id'] ]['complete_percent'] < 38 ||
					empty($resume_list[ $val['resume_id'] ]["appraise"]) ||
					!isset($resume_project[ $resume_list[ $val['resume_id'] ]['resume_id'] ])
				) {
					$val['complete_percent'] = $resume_list[ $val['resume_id'] ]['complete_percent'] ? "{$resume_list[ $val['resume_id'] ]['complete_percent']}%" : '待完善';
				}
				else {
					$val['complete_percent'] = $resume_list[ $val['resume_id'] ]['complete_percent'] ? "{$resume_list[ $val['resume_id'] ]['complete_percent']}%" : '已完善';
				}
				
				if($person_apply_list[ $val['person_id'] ]['video_apply_num']) {
					$val['video_num'] = $person_apply_list[ $val['person_id'] ]['video_apply_num'];
				}
				else {
					$val['video_num'] = 0;
				}
				if($person_apply_list[ $val['person_id'] ]['status_0']) {
					$val['video_status_0'] = $person_apply_list[ $val['person_id'] ]['status_0'];
				}
				else {
					$val['video_status_0'] = 0;
				}
				if($person_apply_list[ $val['person_id'] ]['status_2']) {
					$val['video_status_2'] = $person_apply_list[ $val['person_id'] ]['status_2'];
				}
				else {
					$val['video_status_2'] = 0;
				}
				if($relate_list[ $val['person_id'] ]['apply_num']) {
					$val['relate_num'] = $relate_list[ $val['person_id'] ]['apply_num'];
				}
				else {
					$val['relate_num'] = 0;
				}
				if($relate_list[ $val['person_id'] ]['apply_status_1']) {
					$val['relate_status_1'] = $relate_list[ $val['person_id'] ]['apply_status_1'];
				}
				else {
					$val['relate_status_1'] = 0;
				}
				if($now_activity['is_poor_family']) {
					if($person_list[ $val['person_id'] ]['ext_data']) {
						$val['is_poor_family'] = '是';
					}
					else {
						$val['is_poor_family'] = '否';
					}
				}
				
				//获取意向职位类别
				if($resume_list[ $val['resume_id'] ]['jobsort_exp_data']) {
					$val['jobsort'] = implode(', ', $resume_list[ $val['resume_id'] ]['jobsort_exp_data_name']);
				}
			}
		}
		
		
		if(1 == $is_download) {
			$exl_title['create_time'] = '报名时间';
			$exl_title['student_name'] = '姓名';
			$exl_title['sex_name'] = '性别';
			$exl_title['age'] = '年龄';
			$exl_title['school_name'] = '学校';
			$exl_title['major_desc'] = '专业';
			if($now_activity['is_department']) {
				$exl_title['student_department'] = '院系';
			}
			if($now_activity['is_grade']) {
				$exl_title['student_grade'] = '年级';
			}
			if($now_activity['is_class']) {
				$exl_title['student_class'] = '班级';
			}
			if($now_activity['is_student_id']) {
				$exl_title['student_id'] = '学号';
			}
			if($now_activity['is_academic_advisor']) {
				$exl_title['academic_advisor'] = '指导老师';
			}
			if($now_activity['is_jobsort']) {
				$exl_title['jobsort'] = '意向职位类别';
			}
			if($now_activity['is_poor_family']) {
				$exl_title['is_poor_family'] = '建卡贫困家庭';
			}
			
			$exl_title['complete_percent'] = '简历完善度';
			$exl_title['is_app'] = '是否下载APP';
			$exl_title['video_num'] = '申请视频面试';
			$exl_title['video_status_0'] = '已面试';
			$exl_title['video_status_2'] = '初面通过';
			$exl_title['relate_num'] = '投递简历';
			$exl_title['relate_status_1'] = '邀请面试';
			
			/*  $exl_title = array(
				  'create_time' => '报名时间',
				  'student_name' => '姓名',
				  'sex_name' => '性别',
				  'age' => '年龄',
				  'school_name' => '学校',
				  'major_desc' => '专业',
				  'complete_percent' => '简历完善度',
				  'is_app' => '是否下载APP',
				  'video_num' => '申请视频面试',
				  'video_status_0' => '已面试',
				  'video_status_2' => '初面通过',
				  'relate_num' => '投递简历',
				  'relate_status_1' => '邀请面试',
			  );*/
			$title = "校园招聘会数据统计-" . time();
			$this->createExcel($enter_list, $exl_title, $title);
			//$this->_aParams['list'] = $enter_list;
			//$html= $this->render('./company/videoexcel.html', $this->_aParams['list']);
			// $this->createExcel($html ,null ,$title,true);
			die;
		}
		$this->_aParams['list'] = $enter_list;
		
		//echo json_encode($enter_list);die();
		//累计已报名人数    进入会场人数    已申请视频面试人数    已视频面试人数  不能视频面试(未完善简历、未下载APP)
		$net_person_ids_list = $service_shuangxuanpersonenter->getNetPerson($sid, 'person_id')->items;
		$person_ids_1 = base_lib_BaseUtils::getProperty($net_person_ids_list, 'person_id');
		$ser_netfair_person = new base_service_netfair_person();
		$person_list_1 = $ser_netfair_person->GetPersonDataByIds($person_ids_1, 'person_id,user_name,sex,birthday,netfair_resume_id,ext_data');
		$huibo_personids_toal = array ();
		$blue_personids_toal = array ();
		$service_login_log_data_huibo_1 = array ();
		$service_login_log_data_km_1 = array ();
		if($person_list_1) {
			foreach ($person_list_1 as $key => $val) {
				if($val['source'] == 1) {//汇博
					$huibo_personids_toal[] = $val['person_id'];
				}
				else if($val['source'] == 2) {//快米
					$blue_personids_toal[] = $val['person_id'];
				}
			}
		}
		
		if(@$huibo_personids_toal) {
			$service_login_log = new base_service_person_loginlog();
			$service_login_log_data_huibo_1 = $service_login_log->getLoginPerson($huibo_personids_toal, array (
				'start_time' => $min_personenter['create_time'],
				'origin'     => 'App',
			), 'distinct person_id');
			$service_login_log_data_huibo_1 = base_lib_BaseUtils::array_key_assoc($service_login_log_data_huibo_1, 'person_id');
		}
		
		//快米
		if(@$blue_personids_toal) {
			$service_blue_login_record = new base_service_blue_person_loginlog();
			$service_login_log_data_km_1 = $service_blue_login_record->getLoginPerson1($blue_personids_toal, [
				'start_time' => $start_time,
				'origins'    => array (
					'15',
					'16',
				),
			], 'person_id');
			$service_login_log_data_km_1 = base_lib_BaseUtils::array_key_assoc($service_login_log_data_km_1, 'person_id');
		}
		$not_download_total = count($person_list_1) - count($service_login_log_data_huibo_1) -
			count($service_login_log_data_km_1);
		
		//var_dump($not_download_total);
		$service_personenter = new base_service_netfair_personenternet();
		$service_shuangxuanpersonapply = new base_service_netfair_personapplynet();
		$enter_sum_count = $service_personenter->getNetCount($sid, '', '', 'count(*) as num');
		if(!$start_time && !$end_time) {
			$enter_count = $enter_sum_count;
			$video_sum_count = $service_shuangxuanpersonapply->getNetPersonVideo($sid, 'count(person_id) as num');
			$video_count = $service_shuangxuanpersonapply->getNetPersonVideo($sid, 'count(person_id) as num', true);
		}
		else {
			$enter_count = $service_personenter->getNetCount($sid, $start_time, $end_time, 'count(*) as num');
			$video_sum_count = $service_shuangxuanpersonapply->getNetPersonVideo($sid, 'count(person_id) as num', false, $params);
			$video_count = $service_shuangxuanpersonapply->getNetPersonVideo($sid, 'count(person_id) as num', true, $params);
		}
		$this->_aParams['not_download_total'] = $not_download_total;
		$this->_aParams['enter_sum_count'] = $enter_sum_count['num'];
		$this->_aParams['enter_count'] = $enter_count['num'];
		$this->_aParams['video_sum_count'] = count($video_sum_count->items);
		$this->_aParams['video_count'] = count($video_count->items);
		$this->_aParams['activity_list'] = $activity_list;
		$this->_aParams['params'] = $params;
		$this->_aParams['page_html'] = $this->pageBar($list->totalSize, $page_size, $page, $inPath, 'style2', 'www');
		
		return $this->render('./company/video.html', $this->_aParams);
	}
	
	/**
	 * 可以视频面试的数量
	 * @param $not_download_total
	 * @param $net_person_ids_list
	 * @param $min_personenter
	 * @return mixed
	 */
	protected function _downloadNum($not_download_total, $net_person_ids_list, $min_personenter) {
		if(0 != $not_download_total) {
			$net_person_ids = array ();
			$net_person_ids_list_items = $net_person_ids_list;
			foreach ($net_person_ids_list_items as $val) {
				$net_person_ids[] = $val['person_id'];
			}
			
			//            $service_resume = new base_service_person_resume_resume();
			//            $resume_def_list = $service_resume->getDefaultResumes($net_person_ids,'resume_id,person_id,school,major_desc,complete_percent,appraise');
			//            $resume_list = base_lib_BaseUtils::array_key_assoc($resume_def_list->items,'person_id');
			//            $resume_ids = array();
			//            $resume_person_ids = array();
			//            //获取简历满足38和自我介绍的
			//            foreach ($resume_list as $key=>$val){
			//                if( $val["complete_percent"] >=38 && $val["appraise"]){
			//                    array_push($resume_ids,$val['resume_id']);
			//                    $resume_person_ids[$val['resume_id']] = $key;
			//                }else{
			//                    unset($resume_list[$key]);
			//                }
			//            }
			//            //判断是否有自我评价和校内外工作经验
			//            $service_project = new base_service_person_resume_project();
			//            $resume_project = $service_project->getResumesProjects($resume_ids, 'distinct  resume_id')->items;
			//            $resume_project = base_lib_BaseUtils::array_key_assoc($resume_project,'resume_id');
			//            $net_person_ids = array();
			//            foreach ($resume_project as $key=>$val){
			//                $net_person_ids[] = $resume_person_ids[$val['resume_id']];
			//            }
			if(!empty($net_person_ids)) {
				/*$service_push = new base_service_person_pushdevice();
				$push_user = $service_push->getPersonAppid($net_person_ids, 'count(*) as num');
				$not_download_total = $not_download_total - $push_user[0]['num'];*/
				$service_login_log = new base_service_person_loginlog();
				$login_log_num = $service_login_log->getLoginPerson($net_person_ids, array (
					'start_time' => $min_personenter['create_time'],
					'origin'     => 'App',
				), 'count(distinct person_id) as num');
				$not_download_total = $not_download_total - $login_log_num[0]['num'];
			}
		}
		
		return $not_download_total;
	}
}


?>
