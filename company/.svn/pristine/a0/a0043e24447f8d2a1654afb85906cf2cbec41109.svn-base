<?php
/**
 * 已入场求职者
 * @ClassName controller_fair 
 * @Desc todo(这里用一句话描述这个类的作用) 
 * @author liuchang@huibo.com
 * @date 2013-11-11 下午03:29:17 
 *
 */
class controller_fairentermanage extends components_cbasepage{
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * 已入场求职者入口
	 * Enter description here ...
	 * @param $inPath
	 */
	function pageIndex($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$this->_aParams['title'] = "汇博人才网_现场招聘_邀请入场求职者";
		$service_company = new base_service_company_company();
		$current_company = $service_company->getCompany($this->_userid, true, 'company_id');
		if (empty($current_company)) {
			return;
		}
		$service_fairscene = new base_service_company_fair_fairscene();
		$current_time = date('Y-m-d H:i:s',time());
		$service_fairscenestate = new base_service_company_fair_fairscenestate();
		$scene = $service_fairscene->getStartFairScene($service_fairscenestate->enable, $current_time, 'scene_id, subject');
		if (empty($scene)) {
			return;
		}
		$this->_aParams['current_scene'] = $scene;
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
		$this->__setChooseItem($path_data, $service_degree, $service_workyear, $service_sex);
		//分页查询
		//define('DEBUG', true);
		$this->__getFairSceneTicketValidationPage($current_company['company_id'],$service_degree,$inPath,$path_data,$scene['scene_id']);
		return $this->render('fair/entermanagelist.html', $this->_aParams);
	}
	
	/**
	 * 设置选中值
	 * Enter description here ...
	 * @param unknown_type $path_data
	 * @param unknown_type $service_degree
	 * @param unknown_type $service_workyear
	 * @param unknown_type $service_sex
	 */
	private function __setChooseItem($path_data, $service_degree, $service_workyear, $service_sex){
		//列表类型
		$list_type = base_lib_BaseUtils::getStr($path_data['hddType'],'int',1);
		$this->_aParams['list_type'] = $list_type < 1 || $list_type > 3 ? 1 : $list_type;
		//关键字
		$keyword = base_lib_BaseUtils::getStr($path_data['txtKeyword'],'string','');
		$this->_aParams['keyword'] = $keyword;
		//学历
		$select_degrees = base_lib_BaseUtils::getStr($path_data['chkDegree'],'array','');
		$this->_aParams['select_degree'] = $select_degrees;
		//工作年限
		$select_workyear1 = base_lib_BaseUtils::getStr($path_data['hddWorkyear1'],'string','');
		$wkdes1 = $service_workyear->getWorkyear($select_workyear1);
		$select_workyear2 = base_lib_BaseUtils::getStr($path_data['hddWorkyear2'],'string','');
		$wkdes2 = $service_workyear->getWorkyear($select_workyear2);
		$this->_aParams['select_workyear1'] = base_lib_BaseUtils::nullOrEmpty($select_workyear1) ? '0' : (empty($wkdes1) ? '0' : $select_workyear1);
		$this->_aParams['select_workyear2'] = base_lib_BaseUtils::nullOrEmpty($select_workyear2) ? '10' : ($select_workyear2 == '0' ? '0' : (empty($wkdes2) ? '10' : $select_workyear2));
		//年龄
		$select_age1 = base_lib_BaseUtils::getStr($path_data['txtAge1'],'int',0);
		$select_age2 = base_lib_BaseUtils::getStr($path_data['txtAge2'],'int',0);
		$this->_aParams['select_age1'] = $select_age1<16 || $select_age1>60 ? '' : $select_age1;
		$this->_aParams['select_age2'] = $select_age2<16 || $select_age2>60 ? '' : $select_age2;
		//性别
		$select_sex = base_lib_BaseUtils::getStr($path_data['chkSex'],'array','');
		$this->_aParams['select_sex'] = $select_sex;
	}
	
	/**
	 * 分页查询列表
	 * Enter description here ...
	 * @param $inPath
	 * @param $path_data
	 * @param $company_id
	 */
	private function __getFairSceneTicketValidationPage($company_id,$service_degree,$inPath,$path_data,$scene_id){
    	//获取邀请总数
    	$service_fairsceneticketvalidation = new base_service_company_fair_fairsceneticketvalidation();
    	$jobinvite_live = new base_service_company_fair_jobinvitesms();
		$this->_aParams['enter_total_count'] = $service_fairsceneticketvalidation->getEnterTotalCount($scene_id);
		switch ($this->_aParams['list_type']) {
			case 1:
				$this->__getEnterResume($jobinvite_live,$company_id,$service_fairsceneticketvalidation,$service_degree,$inPath,$path_data,$scene_id);
				break;
			case 2:
				$this->__getInviteLiveResume($jobinvite_live, $company_id, $service_degree, $inPath, $path_data, $scene_id);
				break;
			case 3:
				$this->__getInviteBeforeSceneResume($jobinvite_live,$company_id,$service_fairsceneticketvalidation,$service_degree,$inPath,$path_data,$scene_id);
				break;
			default:
				$this->__getEnterResume($jobinvite_live,$company_id,$service_fairsceneticketvalidation,$service_degree,$inPath,$path_data,$scene_id);
				break;
		}
	}
	
	/**
	 * 已入场求职者
	 * Enter description here ...
	 * @param unknown_type $service_degree
	 * @param unknown_type $inPath
	 * @param unknown_type $path_data
	 * @param unknown_type $scene_id
	 */
	private function __getEnterResume($jobinvite_live,$company_id,$service_fairsceneticketvalidation,$service_degree,$inPath,$path_data,$scene_id){
		$cur_page = base_lib_BaseUtils::getStr($path_data['page'],'int',1);
    	$page_size = base_lib_Constant::PAGE_SIZE;
		if (($this->_aParams['select_workyear1'] == '0' && $this->_aParams['select_workyear2'] == '10') || ($this->_aParams['select_workyear1'] == '10' && $this->_aParams['select_workyear2'] == '0')) {
			unset($this->_aParams['select_workyear1']);
			unset($this->_aParams['select_workyear2']);
		}
		$fairticket_list = $service_fairsceneticketvalidation->getFairSceneTicketValidationList($cur_page,$page_size,$scene_id,$this->_aParams['keyword'],$this->_aParams['select_degree'],$this->_aParams['select_workyear1'],$this->_aParams['select_workyear2'],$this->_aParams['select_age1'],$this->_aParams['select_age2'],$this->_aParams['select_sex'],'a.validate_time,b.is_effect,b.resume_id,b.degree_id,b.is_chinese_resume,b.current_station,b.current_station_start_time,b.current_station_end_time,c.person_id,c.user_name,c.user_name_en,c.birthday2,c.cur_area_id,c.photo,c.photo_open,c.small_photo');
		if (!empty($fairticket_list) && count($fairticket_list->items) > 0) {
			$fairticket_arr = $fairticket_list->items;
			$area = new base_service_common_area();
			$resume_ids = $this->__getPropertys($fairticket_arr, 'resume_id');
			$jobinvitelives = $jobinvite_live->getAllInviteScene($resume_ids,$company_id, $scene_id, 'resume_id');
			foreach ($fairticket_arr as $item=>$value){
				//姓名
				if(is_null($value['is_chinese_resume'])||$value['is_chinese_resume']==1){
					$fairticket_arr[$item]['user_name'] = $value['user_name'];
				}else {
					$fairticket_arr[$item]['user_name'] = $value['user_name_en'];
				}
				//年龄 地区 学历
				$fairticket_arr[$item]['birthday2'] = base_lib_TimeUtil::ceil_diff_year($value['birthday2']).'岁';
				$fairticket_arr[$item]['cur_area_id'] = $area->getArea($value['cur_area_id']);
				$fairticket_arr[$item]['degree_id'] = $service_degree->getDegree($value['degree_id']);
				//最近工作经验
				if($value['current_station']==''){
					$fairticket_arr[$item]['work'] = '无';
				}else {
					if(empty($value['current_station_start_time'])){
						$fairticket_arr[$item]['work'] = $value['current_station'];
					}else{
						$fairticket_arr[$item]['work']= $value['current_station'].'('.base_lib_TimeUtil::date_diff_year3($value['current_station_start_time'],$value['current_station_end_time']).')';
					}
				}
				//头像
	        	if($value['photo_open']!='0'){					
					if(empty($value['small_photo'])){						
						if(!empty($value['photo'])){
							$photo = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$value['photo'];
							$fairticket_arr[$item]['photo'] = '<img width="50px" height="50px" src="'.$photo.'" />';
						}else{
							$fairticket_arr[$item]['photo'] = '';
						}
					}else{
						$photo = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$value['small_photo'];
						$fairticket_arr[$item]['photo'] = '<img width="50px" height="50px" src="'.$photo.'" />';
					}
				}else{
					$fairticket_arr[$item]['photo'] = '';
				}
				$invite_live_info = $this->__arrayFind($jobinvitelives->items, 'resume_id', $fairticket_arr[$item]['resume_id']);
				if ($invite_live_info) {
					$fairticket_arr[$item]['state'] = true;
				}else {
					$fairticket_arr[$item]['state'] = false;
				}
				$fairticket_arr[$item]['the_time'] = $this->__timeConvert($fairticket_arr[$item]['validate_time']);
			}
			$this->_aParams['the_list'] = $fairticket_arr;
			$total_count = $fairticket_list->totalSize;
			$pager = $this->pageBar($total_count, $page_size, $cur_page, $inPath);
    		$this->_aParams['pager'] = $pager;
		}
		$this->_aParams['the_list_type'] = 1;
	}
	
	/**
	 * 已邀请到展位的
	 * Enter description here ...
	 * @param unknown_type $service_degree
	 * @param unknown_type $inPath
	 * @param unknown_type $path_data
	 * @param unknown_type $scene_id
	 */
	private function __getInviteLiveResume($jobinvite_live,$company_id,$service_degree,$inPath,$path_data,$scene_id){
		$cur_page = base_lib_BaseUtils::getStr($path_data['page'],'int',1);
    	$page_size = base_lib_Constant::PAGE_SIZE;
		if (($this->_aParams['select_workyear1'] == '0' && $this->_aParams['select_workyear2'] == '10') || ($this->_aParams['select_workyear1'] == '10' && $this->_aParams['select_workyear2'] == '0')) {
			unset($this->_aParams['select_workyear1']);
			unset($this->_aParams['select_workyear2']);
		}
		$invitelive_list = $jobinvite_live->getInviteLiveList($scene_id,$cur_page,$page_size,$company_id,$this->_aParams['keyword'],$this->_aParams['select_degree'],$this->_aParams['select_workyear1'],$this->_aParams['select_workyear2'],$this->_aParams['select_age1'],$this->_aParams['select_age2'],$this->_aParams['select_sex'],'a.resume_id,b.is_effect,b.degree_id,b.is_chinese_resume,b.current_station,b.current_station_start_time,b.current_station_end_time,c.person_id,c.user_name,c.user_name_en,c.birthday2,c.cur_area_id,c.photo,c.photo_open,c.small_photo');
		if (!empty($invitelive_list) && count($invitelive_list->items) > 0) {
			$invitelive_arr = $invitelive_list->items;
			$area = new base_service_common_area();
			$resume_ids = $this->__getPropertys($invitelive_arr, 'resume_id');
			foreach ($invitelive_arr as $item=>$value){
				//姓名
				if(is_null($value['is_chinese_resume'])||$value['is_chinese_resume']==1){
					$invitelive_arr[$item]['user_name'] = $value['user_name'];
				}else {
					$invitelive_arr[$item]['user_name'] = $value['user_name_en'];
				}
				//年龄 地区 学历
				$invitelive_arr[$item]['birthday2'] = base_lib_TimeUtil::ceil_diff_year($value['birthday2']).'岁';
				$invitelive_arr[$item]['cur_area_id'] = $area->getArea($value['cur_area_id']);
				$invitelive_arr[$item]['degree_id'] = $service_degree->getDegree($value['degree_id']);
				//最近工作经验
				if($value['current_station']==''){
					$invitelive_arr[$item]['work'] = '无';
				}else {
					if(empty($value['current_station_start_time'])){
						$invitelive_arr[$item]['work'] = $value['current_station'];
					}else{
						$invitelive_arr[$item]['work']= $value['current_station'].'('.base_lib_TimeUtil::date_diff_year3($value['current_station_start_time'],$value['current_station_end_time']).')';
					}
				}
				//头像
	        	if($value['photo_open']!='0'){					
					if(empty($value['small_photo'])){						
						if(!empty($value['photo'])){
							$photo = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$value['photo'];
							$invitelive_arr[$item]['photo'] = '<img width="50px" height="50px" src="'.$photo.'" />';
						}else{
							$invitelive_arr[$item]['photo'] = '';
						}
					}else{
						$photo = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$value['small_photo'];
						$invitelive_arr[$item]['photo'] = '<img width="50px" height="50px" src="'.$photo.'" />';
					}
				}else{
					$invitelive_arr[$item]['photo'] = '';
				}
			}
			$this->_aParams['the_list'] = $invitelive_arr;
			$total_count = $invitelive_list->totalSize;
			$pager = $this->pageBar($total_count, $page_size, $cur_page, $inPath);
    		$this->_aParams['pager'] = $pager;
		}
		$this->_aParams['the_list_type'] = 2;
	}
	
	/**
	 * 会前邀请的
	 * Enter description here ...
	 * @param unknown_type $service_degree
	 * @param unknown_type $inPath
	 * @param unknown_type $path_data
	 * @param unknown_type $scene_id
	 */
	private function __getInviteBeforeSceneResume($jobinvite_live,$company_id,$service_fairsceneticketvalidation,$service_degree,$inPath,$path_data,$scene_id){
		$cur_page = base_lib_BaseUtils::getStr($path_data['page'],'int',1);
    	$page_size = base_lib_Constant::PAGE_SIZE;
    	$service_invite = new base_service_company_fair_jobinvitescene();
		if (($this->_aParams['select_workyear1'] == '0' && $this->_aParams['select_workyear2'] == '10') || ($this->_aParams['select_workyear1'] == '10' && $this->_aParams['select_workyear2'] == '0')) {
			unset($this->_aParams['select_workyear1']);
			unset($this->_aParams['select_workyear2']);
		}
		$invite_list = $service_invite->getInviteList($scene_id,$cur_page,$page_size,$company_id,'1',$this->_aParams['keyword'],$this->_aParams['select_degree'],$this->_aParams['select_workyear1'],$this->_aParams['select_workyear2'],$this->_aParams['select_age1'],$this->_aParams['select_age2'],$this->_aParams['select_sex'],'a.person_id,a.invite_id,a.resume_id,b.is_effect,b.degree_id,b.is_chinese_resume,b.current_station,b.current_station_start_time,b.current_station_end_time,c.user_name,c.user_name_en,c.birthday2,c.cur_area_id,c.photo,c.photo_open,c.small_photo');
		if (!empty($invite_list) && count($invite_list->items) > 0) {
			$invite_arr = $invite_list->items;
			$area = new base_service_common_area();
			$resume_ids = $this->__getPropertys($invite_arr, 'resume_id');
			$person_ids = $this->__getPropertys($invite_arr, 'person_id');
			$jobinvitelives = $jobinvite_live->getAllInviteScene($resume_ids,$company_id, $scene_id, 'resume_id');
			//已入场求职者
			$fairsceneticketvalidations = $service_fairsceneticketvalidation->getFairSceneTicketValidations($person_ids,$scene_id,'person_id');
			foreach ($invite_arr as $item=>$value){
				//姓名
				if(is_null($value['is_chinese_resume'])||$value['is_chinese_resume']==1){
					$invite_arr[$item]['user_name'] = $value['user_name'];
				}else {
					$invite_arr[$item]['user_name'] = $value['user_name_en'];
				}
				//年龄 地区 学历
				$invite_arr[$item]['birthday2'] = base_lib_TimeUtil::ceil_diff_year($value['birthday2']).'岁';
				$invite_arr[$item]['cur_area_id'] = $area->getArea($value['cur_area_id']);
				$invite_arr[$item]['degree_id'] = $service_degree->getDegree($value['degree_id']);
				//最近工作经验
				if($value['current_station']==''){
					$invite_arr[$item]['work'] = '无';
				}else {
					if(empty($value['current_station_start_time'])){
						$invite_arr[$item]['work'] = $value['current_station'];
					}else{
						$invite_arr[$item]['work']= $value['current_station'].'('.base_lib_TimeUtil::date_diff_year3($value['current_station_start_time'],$value['current_station_end_time']).')';
					}
				}
				//头像
	        	if($value['photo_open']!='0'){					
					if(empty($value['small_photo'])){						
						if(!empty($value['photo'])){
							$photo = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$value['photo'];
							$invite_arr[$item]['photo'] = '<img width="50px" height="50px" src="'.$photo.'" />';
						}else{
							$invite_arr[$item]['photo'] = '';
						}
					}else{
						$photo = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$value['small_photo'];
						$invite_arr[$item]['photo'] = '<img width="50px" height="50px" src="'.$photo.'" />';
					}
				}else{
					$invite_arr[$item]['photo'] = '';
				}
				$fairsceneticketvalidations_info = $this->__arrayFind($fairsceneticketvalidations->items, 'person_id', $invite_arr[$item]['person_id']);
				if ($fairsceneticketvalidations_info) {
					$invite_arr[$item]['state'] = true;
				}else {
					$invite_arr[$item]['state'] = false;
				}
				$invite_live_info = $this->__arrayFind($jobinvitelives->items, 'resume_id', $invite_arr[$item]['resume_id']);
				if ($invite_live_info) {
					$invite_arr[$item]['is_live_invite'] = true;
				}else {
					$invite_arr[$item]['is_live_invite'] = false;
				}
			}
			$this->_aParams['the_list'] = $invite_arr;
			$total_count = $invite_list->totalSize;
			$pager = $this->pageBar($total_count, $page_size, $cur_page, $inPath);
    		$this->_aParams['pager'] = $pager;
		}
		$this->_aParams['the_list_type'] = 3;
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
	
   	/**
   	 * 距现在时间
   	 * Enter description here ...
   	 * @param unknown_type $the_time
   	 */
	private function __timeConvert($the_time){
   		$show_time = strtotime($the_time);
   		$dur = time() - $show_time;
   		if($dur < 60){
    		return $dur.'秒前';
   		}else{
    		if($dur < 3600){
     			return floor($dur/60).'分钟前';
    		}else{
     			if($dur < 86400){
      				return floor($dur/3600).'小时前';
     			}else{
      				if($dur < 259200){//3天内
       					return floor($dur/86400).'天前';
      				}else{
       					return date('Y-m-d H:i', strtotime($the_time));
      				}
     			}
    		}
   		}
	}
}
?>