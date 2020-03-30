<?php
/**
 * 现场招聘会邀请网站求职者
 * @ClassName controller_faironline 
 * @Desc todo(这里用一句话描述这个类的作用) 
 * @author liuchang@huibo.com
 * @date 2013-11-15 下午05:46:38 
 *
 */
class controller_faironline extends components_cbasepage{
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * 网站求职者列表入口
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	function pageIndex($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$this->_aParams['title'] = "汇博人才网_现场招聘_邀请网站求职者";
		$service_company = new base_service_company_company();
		$current_company = $service_company->getCompany($this->_userid, true, 'company_id');
		if (empty($current_company)) {
			return;
		}
		//绑定学历
		$service_degree = new base_service_common_degree();
		$degree_arr = $service_degree->getAll();
		$this->_aParams['degree_arr'] = $degree_arr;
		//绑定工作年限
		$service_workyear = new base_service_common_workyear();
		$workyear_arr = $service_workyear->getAll();
		$workyear_json = "{id:\"0\",name:\"应届毕业生\"},";
		foreach ($workyear_arr as $item=>$value){
			if ($item != 10) {
				$value = (int)$item.'年';
				$workyear_json = "$workyear_json{id:\"$item\",name:\"$value\"},";
			}else{
				$workyear_json = "$workyear_json{id:\"$item\",name:\"$value\"},";
			}
		}
		$workyear_json = substr($workyear_json, 0, strlen($workyear_json)-1);
		$this->_aParams['workyear_json1'] = "[$workyear_json]";
		$this->_aParams['workyear_json2'] = "[$workyear_json]";
		//绑定性别
		$service_sex = new base_service_common_sex();
		$sex_arr = $service_sex->getSex();
		$this->_aParams['sex_arr'] = $sex_arr;
		//设置选中值
		$this->__setChooseItem($path_data, $service_workyear, $service_sex);
		//分页查询
		// $service_sphinx=new base_service_sphinx_sphinx();
		// $sphinxresult = $service_sphinx->resumeSearch($this->_aParams);
		$this->_aParams['person_class'] = 1;
		$solr_service = new base_service_solr_solr();
		$result = $solr_service->resumeSearch($this->_aParams);

		$str_resume_ids = implode(",", $result['resumeIDs']);
		$resume_data = $this->__bindResumeData($str_resume_ids);
		if(count($resume_data->items)>0){
			$resumes = $resume_data->items;
			$resume_ids = $this->__getPropertys($resumes, 'resume_id');
			$person_ids = $this->__getPropertys($resumes, 'person_id');
			$person_data = $this->__bindPersonData($person_ids);
			$persons = $person_data->items;
			//根据简历编号数组获取所有邀请过的记录
			$service_fairinvite = new base_service_company_fair_jobinvitescene();
			$all_invite = $service_fairinvite->getAllInvite($current_company['company_id'], $resume_ids, '1', 'resume_id,scene_id');
			$all_invite_data = $all_invite->items;
			$invite_scene_ids_arr = $this->__getPropertys($all_invite_data, 'scene_id');
			//根据场次编号数组获取所有未召开的现场招聘会场次
			$service_fair = new base_service_company_fair_fairscene();
			$service_fairscenestate = new base_service_company_fair_fairscenestate();
			$all_fair_scene = $service_fair->getFairSceneListByIDs(implode(',', $invite_scene_ids_arr),$service_fairscenestate->enable, date('Y-m-d H:i:s',time()), 'scene_id');
			$fair_scene_ids_arr = $this->__getPropertys($all_fair_scene->items, 'scene_id');
			//获取未召开的场次邀请过的简历
			foreach ($all_invite_data as $item=>$value) {
				$is_invite = false;
				foreach ($fair_scene_ids_arr as $it=>$val) {
					if ($value['scene_id'] == $val) {
						$is_invite = true;
						break;
					}
				}
				if ($is_invite == false) {
					unset($all_invite_data[$item]);
				}
			}
			$area = new base_service_common_area();
			foreach ($resumes as $item=>$value){
				$person_info = $this->__arrayFind($persons, 'person_id', $value['person_id']);
				//姓名
				if(is_null($value['is_chinese_resume'])||$value['is_chinese_resume']==1){
					$resumes[$item]['username'] = $person_info['user_name'];
				}else {
					$resumes[$item]['username'] = $person_info['user_name_en'];
				}
				//年龄 地区 学历
				$resumes[$item]['birthday'] = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']).'岁';
				$resumes[$item]['curarea'] = $area->getArea($person_info['cur_area_id']);
				$resumes[$item]['degree'] = $service_degree->getDegree($value['degree_id']);
				//最近工作经验
				if($value['current_station']==''){
					$resumes[$item]['work'] = '无';
				}else {
					if(empty($value['current_station_start_time'])){
						$resumes[$item]['work'] = $value['current_station'];
					}else{
						$resumes[$item]['work']= $value['current_station'].'('.base_lib_TimeUtil::date_diff_year3($value['current_station_start_time'],$value['current_station_end_time']).')';
					}
				}
				//头像
	        	if($person_info['photo_open']!='0'){					
					if(empty($person_info['small_photo'])){						
						if(!empty($person_info['photo'])){
							$photo = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$person_info['photo'];
							$resumes[$item]['photo'] = '<img width="50px" height="50px" src="'.$photo.'" />';
						}else{
							$resumes[$item]['photo'] = '';
						}
					}else{
						$photo = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$person_info['small_photo'];
						$resumes[$item]['photo'] = '<img width="50px" height="50px" src="'.$photo.'" />';
					}
				}else{
					$resumes[$item]['photo'] = '';
				}
				$invite_info = $this->__arrayFind($all_invite_data, 'resume_id', $resumes[$item]['resume_id']);
				if($invite_info){
					$resumes[$item]['can_invite'] = false;
				}else {
					$resumes[$item]['can_invite'] = true;
				}
			}
			$this->_aParams['the_list'] = $resumes;
			$pager = $this->pageBar($sphinxresult['total'], $this->_aParams['pagesize'], $this->_aParams['pageindex'], $inPath);
    		$this->_aParams['pager'] = $pager;
		}
		
		
		return $this->render('fair/onlineresumelist.html', $this->_aParams);
	}
	
	/**
	 * 设置选中值
	 * Enter description here ...
	 * @param unknown_type $path_data
	 * @param unknown_type $service_degree
	 * @param unknown_type $service_workyear
	 * @param unknown_type $service_sex
	 */
	private function __setChooseItem($path_data, $service_workyear, $service_sex){
		//行业类别
		if(!base_lib_BaseUtils::nullOrEmpty($path_data['calling'])){
			$this->_aParams['callings'] =  explode(',',base_lib_BaseUtils::getStr($path_data['calling']));
			$this->_aParams['calling'] =  "'".base_lib_BaseUtils::getStr($path_data['calling']). "'";
		}
		//职位类别
		if(!base_lib_BaseUtils::nullOrEmpty($path_data['jobsort'])){
			$this->_aParams['jobsorts'] =  explode(',',base_lib_BaseUtils::getStr($path_data['jobsort']));
			$this->_aParams['jobsort'] =  "'".base_lib_BaseUtils::getStr($path_data['jobsort']). "'";
		}
		//关键字
		$keyword = base_lib_BaseUtils::getStr($path_data['txtKeyword'],'string','');
		$this->_aParams['keyword'] = $keyword;
		//学历
		$select_degrees = base_lib_BaseUtils::getStr($path_data['chkDegree'],'array',array(0=>0));
		$this->_aParams['degree_ids'] = $select_degrees;
		//工作年限
		$select_workyear1 = base_lib_BaseUtils::getStr($path_data['hddWorkyear1'],'string','0');
		$select_workyear2 = base_lib_BaseUtils::getStr($path_data['hddWorkyear2'],'string','10');
		$this->_aParams['workyear_min'] = $select_workyear1;
		$this->_aParams['workyear_max'] = $select_workyear2;
		//年龄
		$select_age1 = base_lib_BaseUtils::getStr($path_data['txtAge1'],'int','');
		$select_age2 = base_lib_BaseUtils::getStr($path_data['txtAge2'],'int','');
		$this->_aParams['age_lower'] = $select_age1;
		$this->_aParams['age_upper'] = $select_age2;
		//性别
		$select_sex = base_lib_BaseUtils::getStr($path_data['chkSex'],'array',array(0=>0));
		$this->_aParams['select_sex'] = $select_sex;
		if (count($select_sex) == 2 || count($select_sex) == 0) {
			$this->_aParams['sex'] = 0;
		}else if(count($select_sex) == 1){
			$this->_aParams['sex'] = $select_sex[0];
		}else{
			$this->_aParams['sex'] = 0;
		}
		$this->_aParams['pageindex'] = base_lib_BaseUtils::getStr($path_data['page'],'int',1);
		$this->_aParams['pagesize'] = base_lib_Constant::PAGE_SIZE;
	}
	
	private function __bindResumeData($resume_ids){
		if(empty($resume_ids)){
			return null;
		}
		$service_resume = new base_service_person_resume_resume();
		$resumes = $service_resume->getResumeListByIDs($resume_ids, "is_effect,resume_id,person_id,degree_id,is_chinese_resume,current_station,current_station_start_time,current_station_end_time");
		return $resumes;
	}
	
	private function __bindPersonData($person_ids){
		if(empty($person_ids) || count($person_ids) == 0){
			return null;
		}
		$str_person_ids = implode(',', $person_ids);
		$service_person = new base_service_person_person();
		$persons = $service_person->GetPersonListByIDs($str_person_ids, 'person_id,user_name,user_name_en,birthday2,cur_area_id,photo,photo_open,small_photo');
		return $persons;
	}
	
	/**
	 * 
	 * 获取数组里对象的属性集合
	 * @param array $arr  对象数组
	 * @param string $property  属性
	 */
	private function __getPropertys($arr,$property) {
	   $peropertys = array();
	   foreach ($arr as $item){
	   	 array_push($peropertys, $item[$property]);
	   }	
	   return $peropertys;	
   }
   
	/**
    * 
    * 数组查询
    * @param array $arr
    * @param string $key
    * @param string $value
    */
	private function __arrayFind($arr,$property,$value) {
   	   if($arr==null) return null;
   	   $obj = null;
   	   if(count($arr>0)){
	   	   foreach ($arr as $item){
		   	  if($item[$property]==$value) {
		   	  	  $obj = $item;
		   	  }
		   }
   	   }
	   return  $obj;
   	}
}
?>