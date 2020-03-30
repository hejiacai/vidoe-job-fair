<?php
 class controller_head extends components_sbasepage {
 	
     
 	private $key = 'jobsearchkey';
 	
 	function __construct() {
		parent::__construct();
	}
	
	
	
	//************-----------加关注的后台代码------------************************//
 	/**
	 * 保存职位搜索器
	 * @param $inPath
	 */
	function pageSaveJobseeker($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		//判断求职者是否登录
		$is_login = $this->isLogin();
		if(!$is_login||$this->_usertype != "p"){
			return;
		}
		$val = new base_lib_Validator();
		$seeker_name = $val->getStr($path_data['seeker_name'],1,10,'名称为1-10个字');
		if($val->has_err){
			echo header("Content-type:text/plain;charset=utf-8");
			echo $validator->toJsonWithHtml();
			return;
		}
		
		foreach ($path_data as $key=>$val){
			$conditon_items[$key] = $val;
		}
		$service_jobseeker = new base_service_person_jobseeker();
		$result = $service_jobseeker->getJobSeeker($seeker_name, $this->_userid, 'seeker_id');
		if(!empty($result)){
			echo header("Content-type:text/plain;charset=utf-8");
			echo json_encode(array('error'=>'该名称已经存在!'));
			return;
		}
		//限制个数
		$result = $service_jobseeker->getJobSeekerCount($this->_userid);
		if($result['total']>6){
			echo header("Content-type:text/plain;charset=utf-8");
			echo json_encode(array('error'=>'关注数量最多为6个!'));
			return;
		}
		
		$items['person_id'] = $this->_userid;
		$items['seeker_name'] = $seeker_name;
		$items['create_time'] = date('Y-m-d H:i:s',time());
		$items['last_search_time'] = date('Y-m-d H:i:s',time());
		
		$search_items = array();
		
		//TODO 组合搜索条件
		
		$result = $service_jobseeker->addSearchAttention($items, $conditon_items);
		if($result!==false){
			echo header("Content-type:text/plain;charset=utf-8");
			echo json_encode(array('error'=>'添加关注成功!'));
		}else{
			echo header("Content-type:text/plain;charset=utf-8");
			echo json_encode(array('error'=>'添加关注失败!'));
		}
		return;
	}
	
	
	
 	/**
	 * 获取职位搜索器列表  (求职中心用)
	 * @param $inPath
	 */
	function pageJobSeekerList($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$seeker_name = base_lib_BaseUtils::getStr($path_data['seeker_name'],'string','');
		
		$service_jobseeker = new base_service_person_jobseeker();
		$jobseekers = $service_jobseeker->getJobSeekers($this->_userid,$seeker_name,'seeker_id,seeker_name,create_time');
		$jobseeker_items = $jobseekers->items;
		$jobseeker_count = count($jobseeker_items);
		if($jobseeker_count>0){
			$service_jobseekercondition = new base_service_person_jobseekercondition();
			$seeker_ids = $this->getPropertys($jobseeker_items,'seeker_id');
			$seekerconditions = $service_jobseekercondition->getJobseekerCondition($seeker_ids,'seeker_id,search_key,search_value');
			
			for ($i = 0; $i < $jobseeker_count; $i++) {
				$temp_arr = array();
				$seeker_infos = $this->arrayFindAll($seekerconditions->items,'seeker_id',$jobseeker_items[$i]['seeker_id']);
				if(count($seeker_infos)>0){
					for ($j = 0; $j < count($seeker_infos); $j++) {
						$seeker_infos[$j]['content'] = $seeker_infos[$j]['search_key'].':'.$seeker_infos[$j]['search_value'];
						array_push($temp_arr, $seeker_infos[$j]['search_key'].':'.$seeker_infos[$j]['search_value']);
					} 
				}
				$jobseeker_items[$i]['content'] = $temp_arr;
			}
			$this->_aParams['jobseeker_arr'] = $jobseeker_items;
		}
		return $this->render('test.html', $this->_aParams);
	}
	

	
	//------------------------------顶部的搜索记录等....---------------------------//	
	
 	/**
	 * 获取搜索记录
	 * @param $inPath
	 */
	function pageGetSearchKeyword($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		//判断求职者是否登录
		$is_login = $this->isLogin();
		if($is_login&&$this->_usertype == "p"){
			//搜索记录
			$service_jobsearchkeyword = new base_service_person_jobsearchkeyword();
			$searchkeywords = $service_jobsearchkeyword->getKeywords($this->_userid,'record_id,person_id,keyword,update_time');
			$searchkeyword_items = $searchkeywords->items;
			
			if(count($searchkeyword_items)>0){
				$service_jobsort = new base_service_common_jobsort();
				$record_ids = array();
				//将数组中重复的搜索词去除
				$searchkeyword_items = $this->remove_duplicate($searchkeyword_items,'keyword','update_time',$record_ids);
				if(count($record_ids)>0){
					//批量删除编号
					$service_jobsearchkeyword->delKeywords($record_ids, $this->_userid);
				}
				for ($i = 0; $i < count($searchkeyword_items); $i++) {
					if(is_numeric($searchkeyword_items[$i]['keyword'])){
						$jobsort = $service_jobsort->getJobsortName($searchkeyword_items[$i]['keyword']);
						if(!empty($jobsort)){
							$searchkeyword_items[$i]['jobsort_id'] = $searchkeyword_items[$i]['keyword'];
							$searchkeyword_items[$i]['keyword'] = $jobsort;
						}
					}
				}
			}
			
			//职位搜索器
			$service_jobseeker = new base_service_person_jobseeker();
			$jobseekers = $service_jobseeker->getJobSeekers($this->_userid,'','seeker_id,seeker_name,create_time');
			$jobseeker_items = $jobseekers->items;
			$jobseeker_count = count($jobseeker_items);
			if($jobseeker_count>0){
				$service_jobseekercondition = new base_service_person_jobseekercondition();
				$seeker_ids = $this->getPropertys($jobseeker_items,'seeker_id');
				$seekerconditions = $service_jobseekercondition->getJobseekerCondition($seeker_ids,'seeker_id,search_key,search_value');
				
				for ($i = 0; $i < $jobseeker_count; $i++) {
					$temp_arr = array();
					$seeker_infos = $this->arrayFindAll($seekerconditions->items,'seeker_id',$jobseeker_items[$i]['seeker_id']);
					if(count($seeker_infos)>0){
						for ($j = 0; $j < count($seeker_infos); $j++) {
							$seeker_infos[$j]['content'] = $seeker_infos[$j]['search_key'].':'.$seeker_infos[$j]['search_value'];
							array_push($temp_arr, $seeker_infos[$j]['search_key'].':'.$seeker_infos[$j]['search_value']);
						} 
					}
					$jobseeker_items[$i]['content'] = $temp_arr;
				}
			}
		}else{
			$searchkeyword_items = $this->getSearchKeywordCookie('searchkey');
			if($searchkeyword_items){
				$searchkeyword_items = $this->sort_by_field($searchkeyword_items,'update_time',true);
			}
		}
		if(count($searchkeyword_items)>0&&count($jobseeker_items)>0){
			$json = array('data_record'=>$searchkeyword_items,'data_seeker'=>$jobseeker_items);
			echo json_encode($json);
		}elseif(count($searchkeyword_items)>0){
			$json = array('data_record'=>$searchkeyword_items);
			echo json_encode($json);
		}elseif (count($jobseeker_items)>0){
			$json = array('data_seeker'=>$jobseeker_items);
			echo json_encode($json);
		}else{
			echo json_encode(array('error'=>false));
		}
		return;
	}
	
	
	

	//TODO: ZhangYu
	/**
	 * 
	 * 测试入口 
	 * @param $inPath
	 */
//	function pageIndex($inPath){
//		return $this->render('headtest.html', $this->_aParams);
//	}
	
	/** 
	 * 保存搜索记录
	 * @param $inPath
	 */
	function pageSaveJobkey($inPath) {
		header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
		header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With');

		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$key_word = base_lib_BaseUtils::getStr($path_data['keyword'],'string',null);
		$callback = base_lib_BaseUtils::getStr($path_data['callback'],'string',null);
		$this->_saveJobkey($key_word);
		if(!base_lib_BaseUtils::nullOrEmpty($callback)){
			echo $callback.'('.json_encode(array('success'=>true)).')';
		}
		else{
			echo json_encode(array('success'=>true));
		}
		return;		
	}
	
	/**
	 * 
	 * 搜索关键字
	 * @param $inPath
	 */
 	function pageSearchKeyword($inPath){
 		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
 		$callback = base_lib_BaseUtils::getStr($path_data['callback'],'string',null);

		$Area_id = base_lib_BaseUtils::getCookie('ip_area_info');

		// 是否登录，登录用户通过后台读取数据，未登录用户通过cookie获取数据
		if($this->_isPersonLogin()){
			//已登录，读取用户保存的搜索关键词记录
			$service_jobsearchkeyword = new base_service_person_jobsearchkeyword();
			$searchkeywords = $service_jobsearchkeyword->getKeywords($this->_userid,'record_id,keyword');
			$searchkeyword_items = $searchkeywords->items;
            
			/*
			//已登录状态时，获取关注记录
			$service_attention = new base_service_person_jobattention();
			$service_attention_condition = new base_service_person_jobattentioncondition();
			$service_urlrule = new www_service_jobsearchurlrule();
			$attentions = $service_attention->getJobAttentions($this->_userid, 'attention_id as value,attention_name as label',null,5,1);
			
			$attentions = $attentions->items;
			//获取关注的条件
			$attention_ids = array();
			for($i=0;$i<count($attentions);$i++){
				array_push($attention_ids,$attentions[$i]['value']);
			}
			$conditions = $service_attention_condition->getConditions($this->_userid, $attention_ids, 'attention_id,search_type,search_value');
			if($conditions!==false && count($conditions)>0){
				$conditions = $conditions->items;
				for($i=0;$i<count($attentions);$i++){
					$condition_arr_temp = array();
					for($j=0;$j<count($conditions);$j++){
						if($conditions[$j]['attention_id'] == $attentions[$i]['value']){
							$condition_arr_temp[$conditions[$j]['search_type']] = $conditions[$j]['search_value'];
						}
					}
					$attentions[$i]['link'] = base_lib_Constant::MAIN_URL.$service_urlrule->buildParamUrl($condition_arr_temp).'&attid='.$attentions[$i]['value'];
				}
			}
            */
		}else{
			// 未登录读取cookie
			$searchkeyword_items = $this->getSearchKeywordCookie($this->key);
			if($searchkeyword_items){
				$searchkeyword_items = $this->sort_by_field($searchkeyword_items,'create_time',true);
			}else {
				$searchkeyword_items = array();
			}
		}
		$items = array();
		$searchRecord = array();
		$len = count($searchkeyword_items);

		$ippositioning_service = new www_service_ippositioning();
		$searchlink = $ippositioning_service->getsearchlink($Area_id);
		if($len>0) {
			// 按接口格式，封装数据
			for($i = 0;$i<$len;$i++) {
				if(!base_lib_BaseUtils::nullOrEmpty($searchkeyword_items[$i]['keyword'])){
					$item = array('label'=>$searchkeyword_items[$i]['keyword'],'value'=>$searchkeyword_items[$i]['keyword'],'link'=>$searchlink.'/jobsearch/?key='.urlencode($searchkeyword_items[$i]['keyword']));//'renderType'=>false,'iconType'=>2
					array_push($searchRecord,$item);
				}
			}
		}
		$items['searchRecord'] = $searchRecord;
		$items['hotData'] = array(
			array('label'=>'销售','link'=>$searchlink.'/jobsearch/?key=销售','isHot'=>true),
			array('label'=>'PHP','link'=>$searchlink.'/jobsearch/?key=PHP'),
			array('label'=>'服务员','link'=>$searchlink.'/jobsearch/?key=服务员','isHot'=>true),
			array('label'=>'厨师','link'=>$searchlink.'/jobsearch/?key=厨师'),
			array('label'=>'营销策划','link'=>$searchlink.'/jobsearch/?key=营销策划'),
		);
		if(count($conditions)>0){
//			$items['attentionRecord'] = $attentions;
            $items['attentionRecord'] = array();//2015-10-15，删除使用关注搜索
		}
 		if(!base_lib_BaseUtils::nullOrEmpty($callback)){
			echo $callback.'('.json_encode($items).')';
		}
		else{
			echo json_encode($items);
		}

		return;
	}


  	/**
	 * 清空记录
	 */
	function pageClearSearchKeywords($inPath){
		$callback = base_lib_BaseUtils::getStr($path_data['callback'],'string',null);
		if(!$this->_isPersonLogin()) {
			// 未登录时清空cookie
			$this->clearSearchCookie();
		}else {
			// 已登录清空历史记录
			$service_jobsearchkeyword = new base_service_person_jobsearchkeyword();
			$service_jobsearchkeyword->delAllKeywords($this->_userid);
		}
		if(!base_lib_BaseUtils::nullOrEmpty($callback)){
			echo $callback.'('.json_encode(array('success'=>true)).')';
		}
		else{
			echo json_encode(array('success'=>true));
		}
		return;
	}
	

	/**
	 * 删除单个关键词记录
	 */
	function pageDelSearchKeyword($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$key_word = base_lib_BaseUtils::getStr($path_data['keyword'],'string',null);
		$callback = base_lib_BaseUtils::getStr($path_data['callback'],'string',null);
		
		// 判断查询词
		if(!base_lib_BaseUtils::nullOrEmpty($key_word)){
			if(!$this->_isPersonLogin()) {
				// 未登录用户删除cookie里的历史记录
				$this->delJobSearchCookie($key_word);
			}else {
				// 登录用户删除数据库记录
				$service_jobsearchkeyword = new base_service_person_jobsearchkeyword();
				$service_jobsearchkeyword->delKeyword($key_word, $this->_userid);
			}
		}
		if(!base_lib_BaseUtils::nullOrEmpty($callback)){
			echo $callback.'('.json_encode(array('success'=>true)).')';
		}
		else{
			echo json_encode(array('success'=>true));
		}
		return;
	}	

	
	/**
	 * 保存记录
	 */
	private function _saveJobkey($key_word) {
		if(!$this->_isPersonLogin()) {
			$this->addJobSearchCookie($key_word, $this->key);	
		}else {
			// 同步cookie
			$this->_searchJobKeyWordRecord();
			$this->clearSearchCookie();
			// 记录用户
			if(!base_lib_BaseUtils::nullOrEmpty($key_word)) {
				$keyword = array('keyword'=>$key_word,'create_time'=>date('Y-m-d H:i:s'),'person_id'=>$this->_userid);
				$this->_saveUserCookie($keyword);
			}
		}		
	}
	
	/**
	 * 获取搜索关键词记录
	 * @param  $keyword
	 */
	private function _searchJobKeyWordRecord() {
		$searchkey_cookie = $this->getSearchKeywordCookie($this->key);
		if(!$searchkey_cookie) {
			return;
		}
		for ($i = 0,$len = count($searchkey_cookie); $i < $len; $i++) {
			$this->_saveUserCookie($searchkey_cookie[$i]);
		}				
	}

	/**
	 * 是否是求职者登录
	 */
	private function _isPersonLogin() {
		if(!$this->isLogin()||$this->_usertype == "c") {
			return false;
		}else {
			return true;
		}
	}
	
	/**
	 * 保存cookie
	 * @param  $keywordCookie
	 */
	private  function _saveUserCookie($keywordCookie) {
		if(base_lib_BaseUtils::nullOrEmpty($keywordCookie)) {return;}
		$service_jobsearchkeyword = new base_service_person_jobsearchkeyword();
		$keyword = $service_jobsearchkeyword->getKeyword($keywordCookie['keyword'],$this->_userid,'record_id,person_id');
		if(empty($keyword)) {
			// 未存在，新增记录
			$item = array('keyword'=>$keywordCookie['keyword'],'create_time'=>$keywordCookie['create_time'],'person_id'=>$this->_userid);
			$result = $service_jobsearchkeyword->addKeyword($item);
		}else {
			// 存在时，重设修改时间
			$keyword['create_time'] = $keywordCookie['create_time'];
			$result = $service_jobsearchkeyword->updateKeyword($keyword['record_id'], $keyword['person_id'], $keyword);
		}
		return $result;
	}
	
	/***
	 * 添加职位搜索cookie
	 */
	private function addJobSearchCookie($key_word,$key) {
		$is_exists = false;
		// 验证是否有职位搜索cookie记录
		$searchkey_cookie = $this->getSearchKeywordCookie($key);
		if(!$searchkey_cookie){
			$searchkey_cookie = array();
		}else{
			$is_exists =  $this->deep_in_array($key_word,$searchkey_cookie);
		}
		if($is_exists){
			// 存在记录
			$searchkey_temp_cookie = $this->remove_one($key_word,$searchkey_cookie,$type);
			$searchkey_cookie = serialize($searchkey_temp_cookie);
		}else{
			$data = array('keyword'=>$key_word,'create_time'=>date('Y-m-d H:i:s',time()));
			array_push($searchkey_cookie, $data);
			$searchkey_cookie = serialize($searchkey_cookie);
		};
    	$cookie = array($key => $searchkey_cookie);
    	//设置cookie
		base_lib_BaseUtils::ssetcookie($cookie, 3600 * 24 * 365, '/', base_lib_Constant::COOKIE_DOMAIN);		
	}
	
	/**
	 * 
	 * 删除搜索关键字
	 * @param  $key_word
	 */
	private function delJobSearchCookie($key_word) {
		$is_exists = false;
		// 验证是否有职位搜索cookie记录
		$searchkey_cookie = $this->getSearchKeywordCookie($this->key);
		if(!$searchkey_cookie){
			$searchkey_cookie = array();
		}else{
			$is_exists =  $this->deep_in_array($key_word,$searchkey_cookie);
		}
		if($is_exists) {
			$searchkey_temp_cookie = $this->remove_one($key_word,$searchkey_cookie,'del');
			$searchkey_cookie = serialize($searchkey_temp_cookie);	
			$cookie = array($this->key => $searchkey_cookie);	
			base_lib_BaseUtils::ssetcookie($cookie, 3600 * 24 * 365, '/', base_lib_Constant::COOKIE_DOMAIN);	
		}			
	}
	
	
	/**
	 * 设置搜索关键字记录cookie
	 * @param string $keyword
	 */
	private function setSearchKeywordCookie($key_word,$type){
		$is_exists = false;
		//获取cookie
		$searchkey_cookie = $this->getSearchKeywordCookie('searchkey');
		if(!$searchkey_cookie){
			$searchkey_cookie = array();
		}else{
			$is_exists =  $this->deep_in_array($key_word,$searchkey_cookie);
		}
		if($is_exists){
			$searchkey_temp_cookie = $this->remove_one($key_word,$searchkey_cookie,$type);
			$searchkey_cookie = serialize($searchkey_temp_cookie);
		}else{
			if(count($searchkey_cookie)>=10){
				array_splice($searchkey_cookie,0,1);
			}
			if($type=='mod'){
				$data = array('keyword'=>$key_word,'create_time'=>date('Y-m-d H:i:s',time()),'update_time'=>date('Y-m-d H:i:s',time()));
				array_push($searchkey_cookie, $data);
			}
			$searchkey_cookie = serialize($searchkey_cookie);
		};
    	$cookie = array('searchkey' => $searchkey_cookie);
    	//设置cookie
		base_lib_BaseUtils::ssetcookie($cookie, 3600 * 24 * 365, '/', base_lib_Constant::COOKIE_DOMAIN);
	}
	
 	/**
	 * 获取cookie值
	 * @param string $searchkey
	 */
	private function getSearchKeywordCookie($searchkey){
		$searchkey_cookie = base_lib_BaseUtils::getCookie($searchkey);
		$searchkey_cookie = unserialize($searchkey_cookie);
		return $searchkey_cookie;
	}
	
	/**
	 * 清空搜索历史记录cookie
	 */
	private function clearSearchCookie(){
		setcookie($this->key,'',time()-3600,'/',base_lib_Constant::COOKIE_DOMAIN);
	}
	
 	/**
	 * 判断多维数组是否存在某个值
	 * @param string $value
	 * @param array $array
	 */
	private function deep_in_array($value, $array) { 
		foreach($array as $item) { 
			if(!is_array($item)) { 
				if ($item == $value) {
					return true;
				} else {
					continue; 
				}
			}
			if(in_array($value, $item)) {
				return true; 
			} else if($this->deep_in_array($value, $item)) {
				return true; 
			}
		} 
		return false; 
	}
	
	/**
	 * 根据某个值删除多维数组中的一组元素
	 * @param string $value
	 * @param array $array
	 * @param string $type
	 */
	private function remove_one($value,$array,$type){
		for ($i = 0; $i < count($array); $i++) {
			if(!is_array($array[$i])) { 
				if($array[$i]==$value){
					if($type!=''){	
						if($type=='mod'){
							$item['keyword'] = $value;
							$item['create_time'] = $array[$i]['create_time'];
							$item['update_time'] = date('Y-m-d H:i:s',time());
							array_push($array, $item);
						}
						array_splice($array,$i,1);
					}
				}else{
					continue;	
				}
			}
			if(in_array($value, $array[$i])) {
				if($type!=''){
					if($type=='mod'){
						$item['keyword'] = $value;
						$item['create_time'] = $array[$i]['create_time'];
						$item['update_time'] = date('Y-m-d H:i:s',time());
						array_push($array, $item);
					}
					array_splice($array,$i,1);
				}
			}
		}
		return $array;
	}
	
	/** 
	 *  
	 * 二维数组按指定列排序 
	 * @param $arr_data 原数组 
	 * @param $field 指定列 
	 * @param $descending 是否降顺（默认升顺） 
	 * @return 排列好的数组 
	**/ 
	private function sort_by_field($arr_data, $field, $descending = false) { 
		$arr_sort = array(); 
	 	foreach ( $arr_data as $key => $value ) { 
	 		$arr_sort[$key] = $value[$field]; 
	 	} 
		if( $descending ) { 
	 		arsort($arr_sort); 
		} else { 
	 		asort($arr_sort); 
	 	} 
	 	$result_arr = array(); 
		 foreach ($arr_sort as $key => $value ) { 
		 	$result_arr[$key] = $arr_data[$key]; 
		 } 
		 return $result_arr; 
	} 

	/**
	 * 二维数组根据某个字段的值删除重复，并按某个字段比较保留
	 * @param $arr_data  原数组
	 * @param $unset_filed	指定列
	 * @param $sort_field 排序字段
	 * @param $record_id 返回记录编号
	 * @return 返回去重复之后的数组
	 */
	private function remove_duplicate($arr,$unset_filed,$sort_field,&$record_ids) {
		$record_ids = array();
		$temp_arr = $arr;
     	for ($i = 0; $i < count($temp_arr); $i++) {
     		for ($j = $i+1; $j < count($temp_arr)-1; $j++) {
     			if($temp_arr[$i][$unset_filed]==$temp_arr[$j][$unset_filed]){
     				if($temp_arr[$i][$sort_field]>$temp_arr[$j][$sort_field]){
     					array_push($record_ids,$arr[$j]['record_id']);
     					unset($arr[$j]);
     				}else{
     					array_push($record_ids,$arr[$i]['record_id']);
     					unset($arr[$i]);
     				}
     			}
     		}
     	}
     	return $arr;
    }
    
  	/**
	 * 
	 * 获取数组中对象的属性的值
	 * @param array $arr  对象数组
	 * @param string $property  属性
   	*/
	private function  getPropertys($arr,$property) {
	   $peropertys = array();	
	   foreach ($arr as $item){
	   	 array_push($peropertys, $item[$property]);
	   }
	   return $peropertys;	
   	}
   
   	/**
     * 
     * 通过属性和值对数组查询
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
   
	/**
     * 
     * 通过属性和值对数组查询
     * @param array $arr
     * @param string $key
     * @param string $value
    */
    private function arrayFindAll($arr,$property,$value) {
   	   $obj = array();
   	   foreach ($arr as $item){
	   	  if($item[$property]==$value) {
	   	  	  array_push($obj, $item);
	   	  }
	   }
	   return  $obj;
    }
 }
?>