<?php
/**
 * 现场招聘会邀请
 * @ClassName controller_fairsceneinvite 
 * @Desc todo(这里用一句话描述这个类的作用) 
 * @author liuchang@huibo.com
 * @date 2013-11-16 下午03:19:42 
 *
 */
class controller_fairsceneinvite extends components_cbasepage{
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * 发出的邀请列表入口
	 * Enter description here ...
	 * @param $inPath
	 */
	function pageIndex($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$this->_aParams['title'] = "汇博人才网_现场招聘_发出的邀请";
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
		$this->__setChooseItem($path_data, $service_degree, $service_workyear, $service_sex);
		//分页查询
		//define('DEBUG', true);
		$this->__getInviteResumePage($service_degree,$inPath,$path_data,$current_company['company_id']);
		return $this->render('fair/invitelist.html', $this->_aParams);
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
	 * 分页获取邀请列表
	 * Enter description here ...
	 * @param $inPath
	 * @param $path_data
	 * @param $company_id
	 */
	private function __getInviteResumePage($service_degree,$inPath,$path_data,$company_id){
		$cur_page = base_lib_BaseUtils::getStr($path_data['page'],'int',1);
    	$page_size = base_lib_Constant::PAGE_SIZE;
    	$service_invite = new base_service_company_fair_jobinvitescene();
    	//获取邀请总数
		$this->_aParams['invite_total_count'] = $service_invite->getInviteTotalCount($company_id,'1');
		if (($this->_aParams['select_workyear1'] == '0' && $this->_aParams['select_workyear2'] == '10') || ($this->_aParams['select_workyear1'] == '10' && $this->_aParams['select_workyear2'] == '0')) {
			unset($this->_aParams['select_workyear1']);
			unset($this->_aParams['select_workyear2']);
		}
		$invite_list = $service_invite->getInviteList(null,$cur_page,$page_size,$company_id,'1',$this->_aParams['keyword'],$this->_aParams['select_degree'],$this->_aParams['select_workyear1'],$this->_aParams['select_workyear2'],$this->_aParams['select_age1'],$this->_aParams['select_age2'],$this->_aParams['select_sex'],'a.invite_id,a.scene_date,a.station,a.resume_id,c.user_name,c.birthday2,c.cur_area_id,c.photo,b.degree_id');
		if (!empty($invite_list) && count($invite_list->items) > 0) {
			$invite_arr = $invite_list->items;
			$resume_ids = $this->__getPropertys($invite_arr, 'resume_id');
			if (!empty($resume_ids) && count($resume_ids) > 0) {
				$resume_service = new base_service_person_resume_resume();
				$resume_data = $resume_service->getResumeListByIDs(implode(',', $resume_ids), 'resume_id,person_id,degree_id,is_chinese_resume');
				if (count($resume_data->items)>0) {
					$resumes = $resume_data->items;
					$person_ids = $this->__getPropertys($resumes, 'person_id');
					if (!empty($person_ids) && count($person_ids) > 0) {
						$person_service = new base_service_person_person();
						$person_data = $person_service->GetPersonListByIDs(implode(',', $person_ids), 'person_id,user_name,user_name_en,birthday2,cur_area_id,photo,photo_open,small_photo');
						if (count($person_data->items)>0) {
							$persons = $person_data->items;
							$area = new base_service_common_area();
							$weekarray = array("日","一","二","三","四","五","六");
							foreach ($invite_arr as $item=>$value){
								$resume_info = $this->__arrayFind($resumes, 'resume_id', $value['resume_id']);
								$person_info = $this->__arrayFind($persons, 'person_id', $resume_info['person_id']);
								if(is_null($resume_info['is_chinese_resume'])||$resume_info['is_chinese_resume']==1){
									$invite_arr[$item]['user_name'] = $person_info['user_name'];
								}else {
									$invite_arr[$item]['user_name'] = $person_info['user_name_en'];
								}
								$invite_arr[$item]['birthday2'] = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']).'岁';
								$invite_arr[$item]['degree_id'] = $service_degree->getDegree($resume_info['degree_id']);
								$invite_arr[$item]['cur_area_id'] = $area->getArea($person_info['cur_area_id']);
								$invite_arr[$item]['thedate'] = date("Y年m月d日", strtotime($invite_arr[$item]['scene_date']));
								$invite_arr[$item]['theweekday'] = '（星期'.$weekarray[date('w', strtotime($invite_arr[$item]['scene_date']))].'）';
								if($person_info['photo_open']!='0'){
									if(empty($person_info['small_photo'])){
										if(!empty($person_info['photo'])){
											$photo = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$person_info['photo'];
											$invite_arr[$item]['photo'] = '<img width="50px" height="50px" src="'.$photo.'" />';
										}else{
											$invite_arr[$item]['photo'] = '';
										}
									}else{
										$photo = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$person_info['small_photo'];
										$invite_arr[$item]['photo'] = '<img width="50px" height="50px" src="'.$photo.'" />';
									}
								}else{
									$invite_arr[$item]['photo'] = '';
								}
							}
							$this->_aParams['invite_list'] = $invite_arr;
							$total_count = $invite_list->totalSize;
							$pager = $this->pageBar($total_count, $page_size, $cur_page, $inPath);
				    		$this->_aParams['pager'] = $pager;
						}
					}
				}
			}
		}
	}
	
	/**
	 * 单个删除邀请
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	function pageDeleteInvite($inPath){
		$service_company = new base_service_company_company();
		$company_info = $service_company->getCompany($this->_userid,1,'company_id');
		if (empty($company_info)) {
			$json['error'] = '删除邀请失败';
			echo json_encode($json);
			return;
		}
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$invite_id = base_lib_BaseUtils::getStr($path_data['inviteid'],'int',0);
		if (empty($invite_id)) {
			$json['error'] = '删除邀请失败';
			echo json_encode($json);
			return;
		}
		$service_fairinvite = new base_service_company_fair_jobinvitescene();
		$result = $service_fairinvite->delInvite($company_info['company_id'], $invite_id);
		if ($result === true) {
			$json['success'] = '删除邀请成功';
			echo json_encode($json);
			return;
		}
		$json['error'] = '删除邀请失败';
		echo json_encode($json);
		return;
	}
	
	/**
	 * 批量删除邀请
	 * Enter description here ...
	 * @param $inPath
	 */
	function pageDeleteInviteMulti($inPath){
		$service_company = new base_service_company_company();
		$company_info = $service_company->getCompany($this->_userid,1,'company_id');
		if (empty($company_info)) {
			$json['error'] = '删除邀请失败';
			echo json_encode($json);
			return;
		}
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$invite_ids = base_lib_BaseUtils::getStr($path_data['inviteids'],'array',null);
		$invite_ids = base_lib_BaseUtils::getIntArrayOrString($invite_ids);
		if (empty($invite_ids) || count($invite_ids) == 0) {
			$json['error'] = '删除邀请失败';
			echo json_encode($json);
			return;
		}
		$service_fairinvite = new base_service_company_fair_jobinvitescene();
		$result = $service_fairinvite->delInviteMulti($company_info['company_id'], $invite_ids);
		if ($result === true) {
			$json['success'] = '删除邀请成功';
			echo json_encode($json);
			return;
		}
		$json['error'] = '删除邀请失败';
		echo json_encode($json);
		return;
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