<?php
/**
 * @copyright 2002-2013 www.huibo.com
 * @name 简历回收站
 * @author fuzy
 * @version 2013-8-5 上午11:20:56
*/
class controller_recycle extends components_cbasepage {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * 回收站入口
	 */
	function pageIndex($inPath) {
		$this->_aParams['title'] = "简历回收站";
		//绑定来源
		$service_origintype = new base_service_company_resume_origintype();
		$origintype_arr = $service_origintype->getOriginType();
		$this->_aParams['origintypes'] = $origintype_arr;
		$service_recycle = new base_service_company_resume_recycle();
		$count = $service_recycle->getRecycleCount($this->_userid);
    	if($count>0){
    		$this->_aParams['has_resume'] = true;
    	}else{
    		$this->_aParams['has_resume'] = false;
    	}
		return $this->render('resume/recycle/list.html', $this->_aParams);
	}
	
	/**
	 * 获取简历回收站数据
	 * Enter description here ...
	 * @param unknown_type $inPath
	 */
	public function pageRecycle($inPath){
		$service_origintype = new base_service_company_resume_origintype();
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$origin_type = base_lib_BaseUtils::getStr($path_data['hddOriginType'],'string','0');
		if (!array_key_exists($origin_type, $service_origintype->getOriginType())){
			$origin_type = 0;
		}
		$cur_page = base_lib_BaseUtils::getStr($path_data['page'],'int',1);
		$page_size = base_lib_BaseUtils::getStr($path_data['pageSize'],'int',base_lib_Constant::PAGE_SIZE);
		$data = $this->getRecycleList($cur_page,$page_size,$origin_type,$this->_userid);
	    echo json_encode($data);
	}
	
	/**
	 * 查询回收站数据
	 * Enter description here ...
	 * @param unknown_type $cur_page
	 * @param unknown_type $page_size
	 * @param unknown_type $origin_type
	 */
	private function getRecycleList($cur_page,$page_size,$origin_type,$company_id){
		$service_recycle = new base_service_company_resume_recycle();
		$service_recycle->setLimit($page_size);
		$service_recycle->setPage($cur_page);
		$service_recycle->setCount(true);
		//define("DEBUG", true);
		$recycle_list = $service_recycle->getRecycleList($company_id,$origin_type,'*');
		$data = null;
		if ($recycle_list->items) {
			$service_resume = new base_service_person_resume_resume();
			$service_person = new base_service_person_person();
			$resume_work = new base_service_person_resume_work();
			$area = new base_service_common_area();
			$degree = new base_service_common_degree();
			$service_Origintype = new base_service_company_resume_origintype();
			//将简历信息，个人信息，工作信息一次性取出来
			$resume_ids = $this->getPropertys($recycle_list->items, 'resume_id');
			$resume_ids_new_array = array();
			foreach ($resume_ids as $key=>$value){
				if (!base_lib_BaseUtils::nullOrEmpty($value)) {
					array_push($resume_ids_new_array, $value);
				}
			}
	        $resumes = $service_resume->getResumes($resume_ids_new_array,'person_id,resume_id,resume_name,is_chinese_resume,degree_id,is_effect,current_station,current_station_start_time,current_station_end_time',null);
	        $person_ids = $this->getPropertys($resumes->items, 'person_id');
	        $persons = $service_person->getPersons($person_ids, 'person_id,user_name,user_name_en,birthday,cur_area_id,photo,small_photo,photo_open,birthday2',null);
	        //$lastworks = $resume_work->getLastResumeWorks($resume_ids, 'work_id,resume_id,start_time,end_time,station');
	        for ($i = 0; $i < count($recycle_list->items); $i++) {
	        	//简历信息
				$resume_info =$this->arrayFind($resumes->items, 'resume_id', $recycle_list->items[$i]['resume_id']);
				if (empty($resume_info)) {
					unset($recycle_list->items[$i]);
					continue;
				}
				//用户信息
				$person_info = $this->arrayFind($persons->items, 'person_id', $resume_info['person_id']);
	        	//工作信息
				$resume_work_info = $this->arrayFind($lastworks->items, 'resume_id', $recycle_list->items[$i]['resume_id']);
				$recycle_list->items[$i]['is_effect'] = $resume_info['is_effect'];
				//姓名
				if(is_null($resume_info['is_chinese_resume'])||$resume_info['is_chinese_resume']==1){
					$recycle_list->items[$i]['user_name'] = $person_info['user_name'];
				}else {
					$recycle_list->items[$i]['user_name'] = $person_info['user_name_en'];
				}
				//年龄 地区 学历
				$recycle_list->items[$i]['age'] = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']).'岁';
				$recycle_list->items[$i]['area'] = $area->getArea($person_info['cur_area_id']);
				$recycle_list->items[$i]['degree'] = $degree->getDegree($resume_info['degree_id']);
	        	//最近工作经验
	        	if($resume_info['current_station']==''){
					$recycle_list->items[$i]['work'] = '无';
				}else {
					if(empty($resume_info['current_station_start_time'])){
						$recycle_list->items[$i]['work'] = $resume_info['current_station'];
					}else{
						$recycle_list->items[$i]['work']= $resume_info['current_station'].'('.base_lib_TimeUtil::date_diff_year3($resume_info['current_station_start_time'],$resume_info['current_station_end_time']).')';
					}
				}
				
	        	//头像
	        	if($person_info['photo_open']!='0'){					
					if(empty($person_info['small_photo'])){						
						if(!empty($person_info['photo'])){
							$photo = base_lib_Constant::UPLOAD_FILE_URL.$person_info['photo'];
							$recycle_list->items[$i]['photo'] = '<img width="50px" height="50px" src="'.$photo.'" />';
						}else{
							$recycle_list->items[$i]['photo'] = '';
						}
					}else{
						$photo = base_lib_Constant::UPLOAD_FILE_URL.$person_info['small_photo'];
						$recycle_list->items[$i]['photo'] = '<img width="50px" height="50px" src="'.$photo.'" />';
					}
				}else{
					$recycle_list->items[$i]['photo'] = '';
				}
				
				/*//头像
				if(empty($person_info['photo'])){
					$recycle_list->items[$i]['photo'] ='';
				}else {
					$photo = base_lib_Constant::UPLOAD_FILE_URL."/{$person_info['photo']}?r=".rand(1000, 9999);
					$recycle_list->items[$i]['photo'] = '<img width="50px" height="50px" src="'.$photo.'" />';
				}	*/
				
				$recycle_list->items[$i]['type'] = $service_Origintype->getName($recycle_list->items[$i]['origin_type']);
	        }
	        $data['list'] = $recycle_list->items;
		}
		$data['curPage'] = $cur_page;
		$data['pageSize'] = $page_size;	
		$data['recordCount'] = $recycle_list->totalSize;
		return $data;
	}
	
	/**
	 * 
	 * 获取数组里对象的属性集合
	 * @param array $arr  对象数组
	 * @param string $property  属性
	 */
	private function getPropertys($arr,$property) {
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
   private function arrayFind($arr,$property,$value) {
   	   foreach ($arr as $item){
	   	  if($item[$property]==$value) {
	   	  	  return $item;
	   	  }
	   }
	    return  null;
   }
	
	/**
	 *@desc清空回收站
	 *@return
	*/
	public function pageClear($inPath) {
		$recycle = new base_service_company_resume_recycle();
		$recycle = $recycle->clearRecycle($this->_userid);
		if($recycle===false){
			echo json_encode(array('error'=>'清空回收站失败'));
			return ;
		}
		echo json_encode(array('success'=>'1'));
	}
	
	
	/**
	 *@desc 删除简历
	 *@return
	*/
	public function pageDelete($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$recycle_ids = explode(',',$path_data['recycleID']);
		if(empty($recycle_ids)){
			echo json_encode(array('error'=>'请选择你要删除的简历'));
			return ;
		}
		
		$recycle = new base_service_company_resume_recycle();
		foreach ($recycle_ids as $recycle_id){
			$recycle->deleteRecycle($this->_userid, $recycle_id);
		}
		
		echo json_encode(array('success'=>'1'));
	}
	
	/**
	 *@desc 恢复简历提示窗
	 *@return
	 */
	public function pageRecovery($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$ids = $path_data['ids'];
		$this->_aParams['ids'] = $ids;
		$names =  explode(',',$path_data['names']);
		$this->_aParams['names'] = $names;
		return  $this->render('resume/recycle/recovery.html', $this->_aParams);
//		if(empty($recycle_ids)){
//			echo json_encode(array('error'=>'请选择你要恢复的简历'));
//			return ;
//		}
//	
//		$recycle = new base_service_company_resume_recycle();
//		foreach ($recycle_ids as $recycle_id){
//			$recycle->deleteRecycle($this->_userid, $recycle_id);
//		}
//	
//		echo json_encode(array('success'=>'1'));
	}
	
	public function pageRecoveryDo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$ids = explode(',', $path_data['ids']);
		if (empty($ids)) {
			echo json_encode(array('error'=>'请选择你要恢复的简历'));
			return ;
		}
		$recycle = new base_service_company_resume_recycle();
		foreach ($ids as $recycle_id){
			$recycle->recoveryRecycle($this->_userid, $recycle_id);
		}
		echo json_encode(array('success'=>'1'));				
		exit;
	}
	
}
?>