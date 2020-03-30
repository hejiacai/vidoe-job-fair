<?php
/**
 * @copyright 2002-2013 www.huibo.com
 * @name 收藏的简历
 * @author che
 * @version 2013-8-13
 */
class controller_fav extends components_cbasepage {
    /**
     * service fav
     * @var base_service_company_resume_fav
     */

    function __construct() {
        parent::__construct();
    }

    /**
     * 感兴趣列表的入口
     * @param array  $InPath
     */
    public function pageIndex($inPath) {
        if(!$this->canDo("resume_manage")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
		$params     = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$tag_id     = base_lib_BaseUtils::getStr($params['tag_id'], 'int', null);
		$keyword    = base_lib_BaseUtils::getStr($params['keyword'], 'string', '');
		$cur_page   = base_lib_BaseUtils::getStr($params['page'], 'int', 1);
		$page_size  = base_lib_BaseUtils::getStr($params['pageSize'], 'int', base_lib_Constant::PAGE_SIZE);	
		$searchmode = base_lib_BaseUtils::getStr($params['search_model'], 'int', '0'); // 查询方式 0表示按筛选条件，1表示按姓名/简历编号 	

	    $fav_service = new base_service_company_resume_fav();
		$totalCount = $fav_service->getFavCount($this->_userid);

   		$tag_service = new base_service_company_companytag();
    	
    	// 获取所有的tag
    	$companytags = $tag_service->getCompanyTagList($this->_userid, 'tag_id as id,tag_name as name')->items;
	    $new_companytags = array_merge(array(array('id'=>'', 'name'=>'按全部查看')), $companytags);	   	
    	
    	if ($searchmode != 0) {
    		$tag_id = null;
    	} else {
    		$keyword = null;
    	}

    	// 获取公司会员信息
    	$memberinfo = $this->getCompanyMemberInfo();
		$result = $fav_service->queryFavs($page_size, $cur_page, $this->_userid, 'fav_id,info_resume_fav.resume_id,info_resume_fav.create_time',$keyword,$tag_id);
    	$favs = $result->items;
        $this->_aParams['totalSize'] = $result->totalSize;
        $service_apply         = new base_service_company_resume_apply();
    	
    	if (count($favs) > 0) {
    		$resume_ids = $this->_buildIDs($favs, 'resume_id');
			$service_resume        = new base_service_person_resume_resume();
			$service_person        = new base_service_person_person();
			$service_resume_remark = new base_service_company_resume_resumeremark();
			$service_area          = new base_service_common_area();
			$service_degree        = new base_service_common_degree();
			$service_tag           = new base_service_company_resume_resumecompanytag();
			$enum_openmode         = new base_service_common_openmode();
			$invite_service        = new base_service_company_resume_jobinvite();
			$download_service      = new base_service_company_resume_download();
			
    		$resume_data = $service_resume->getResumeListByIDs($resume_ids, 'resume_id,person_id,resume_name,degree_id,is_effect,current_station,current_station_start_time,current_station_end_time'); //简历
    		$resumes = $this->_buildArray($resume_data->items, 'resume_id');
    		// 获取求职者编号
    		$persin_ids = $this->_buildIDs($resume_data->items, 'person_id');
    		$person_data = $service_person->GetPersonListByIDs($persin_ids, 'person_id,open_mode,user_name,name_open,sex,birthday,cur_area_id,photo_open,photo,small_photo,birthday2,start_work,mobile_phone'); // 求职者

            $service_qloudmsg = new base_service_app_qcloudmsg();
            $chat_list        = $service_qloudmsg->getReplyTimes($persin_ids);
            $chat_list        = base_lib_BaseUtils::array_key_assoc($chat_list, "person_id");
            
    		$persons = $this->_buildArray($person_data->items, 'person_id');
    		$remark_data = $service_resume_remark->getLastRemarkByResumeIDs($this->_userid, $resume_ids, 'remark_id,resume_id,company_id,remark'); //备注
    		$remarks = $this->_buildArray($remark_data->items, 'resume_id');
            
            //工作经验
            $service_resume_work = new base_service_person_resume_work();
            $work_datas = $service_resume_work->getResumeWorks($resume_ids,"work_id,resume_id,start_time,end_time,station,company_name,work_content");
            //$work_datas = $this->arrayFormat($work_datas->items, "resume_id");
            if (count($work_datas->items) > 0) {
                foreach ($work_datas->items as $workskey=>$worksvalue){
					$workslist[$worksvalue["resume_id"]][$workskey]['start_time']   = date('Y/m', strtotime($worksvalue['start_time']));
					$workslist[$worksvalue["resume_id"]][$workskey]['end_time']     = empty($worksvalue['end_time']) ? "至今" : date('Y/m', strtotime($worksvalue['end_time']));
					$workslist[$worksvalue["resume_id"]][$workskey]['station']      = $worksvalue['station'];
					$workslist[$worksvalue["resume_id"]][$workskey]['company_name'] = $worksvalue['company_name'];
					$workslist[$worksvalue["resume_id"]][$workskey]['work_content'] = base_lib_BaseUtils::cutstr($worksvalue['work_content'], 180, 'utf-8', '', '...');
                }
            }

    		//适合岗位
			$tag_data = $service_tag->getTagbyIDs($this->_userid,$resume_ids,'resume_id,tag_name');
    		$tags = $this->_buildArray($tag_data->items, 'resume_id');
    		
    		//邀请的简历
    		$invite_data = $invite_service->queryInviteList($resume_ids, $this->_userid, 'resume_id');
    		$invites = $this->_buildArray($invite_data->items, 'resume_id');
            
            //毕业院校
            $service_edu = new base_service_person_resume_edu();
            $edu_data = $service_edu->getResumeEdus($resume_ids, 'resume_id,school,major_desc,degree');
    		
    		//下载的简历
    		$down_data = $download_service->queryDownloadList($resume_ids, $this->_userid, 'resume_id');
    		$downs = $this->_buildArray($down_data->items, 'resume_id');
            $resume_downloadids = base_lib_BaseUtils::getPropertys($down_data->items,"resume_id"); 
            
            /**获取投递记录**/
            $company_resources = base_service_company_resources_resources::getInstance($this->_userid);
            
            $apply_list         = $service_apply->getCompanyResumeApplyData($company_resources->all_accounts, $resume_ids, "apply_id,resume_id");
            $resume_applyids    = base_lib_BaseUtils::getPropertys($apply_list,"resume_id");

            //求职者一周内是否登录过app
            $sercie_loginlog= new base_service_person_loginlog();
            $login_status = $sercie_loginlog->getLoginAppData($persin_ids,14);
            if(!empty($login_status))
                $login_status = base_lib_BaseUtils::array_key_assoc($login_status,'person_id');
            //$service_chat = new company_service_chat(0,0);
            $service_wangyiaction = new base_service_app_wangyiaction();
            //判断这些person_ids对应的网易云账户是否在线
            $wy_person_status_arr = $service_wangyiaction->checkPersonsIsOnline($persin_ids);
    		//用户联系方式获取数组
    		$resume_ids = explode(",", $resume_ids);
    		$privileges = $service_person->checkMobilesPrivilege($resume_ids, $this->_userid);
            $serviceArea = new base_service_company_service_serviceArea();
    		for ($i = 0,$len = count($favs); $i < $len; $i++) {
				$resume_id   = $favs[$i]['resume_id'];
				$resume      = $resumes[$resume_id];
				$person_id   = $resume['person_id'];
				$person      = $persons[$person_id];
				$remark      = $remarks[$resume_id];
				$resume_work = $works[$resume_id];
				$tag         = $tags[$resume_id];
				$apply       = $effect_apply_list[$person_id];
				$down        = $downs[$resume_id];

                $show_full_name     = (in_array($resume_id, $resume_downloadids) || in_array($resume_id, $resume_applyids)) ? true : false;
    			//姓名
				if ($person['name_open'] == 1 && $show_full_name) {
					$favs[$i]['user_name'] = $person['user_name'];
				} else {
					$sex_name = $person['sex'] == 1 ? '先生' : '女士';
					$favs[$i]['user_name']=mb_substr($person['user_name'], 0, 1, 'utf-8') . $sex_name;
				}

 				//年龄 地区 学历
				$favs[$i]['age']    = base_lib_TimeUtil::ceil_diff_year($person['birthday2']).'岁';
				$favs[$i]['area']   = $service_area->getArea($person['cur_area_id']);
				$favs[$i]['degree'] = $service_degree->getDegree($resume['degree_id']); 
				$favs[$i]['sex']    = base_lib_BaseUtils::nullOrEmpty($person['sex']) ? '' : ($person['sex'] == 1 ? '男' : '女');

                //已超出简历下载地区限制，请联系工作人员开通相应招聘服务。
                $favs[$i]['not_area_limit'] = $serviceArea->IsServiceAreaTypeDownloadResumeScope($this->_userid,$favs[$i]['resume_id']);//限制为false
    			// 个人信息未公开		
				if ($person['open_mode'] == $enum_openmode->notopen) {
					$favs[$i]['notopen']  = true ;
				}

				// 简历已删除
    			if ($resume['is_effect'] != 1) {
					$favs[$i]['isdelete'] = true;
				}

				// 已邀请
    			if (isset($invites[$resume_id])) {
					$favs[$i]['isinvite'] = true ;	
				}

				// 已下载
				if (isset($downs[$resume_id])) {
					$favs[$i]['isdown'] = true ;
				}

				// 非会员需要联系方式
				$favs[$i]['needdown'] = !$privileges[$resume_id];
				// $favs[$i]['needdown'] = $this->_checkNeedContact($memberinfo, $apply, $down);
				// $favs[$i]['phone'] = $person['mobile_phone'];

    			//工作年限					
				$basic_start_work_year = base_lib_TimeUtil::date_diff_month($person['start_work']);		
				$workY = floor($basic_start_work_year / 12); 
				$workM = intval($basic_start_work_year % 12); 
				
				if ($workY <= 0 && $workM <= 6 && $workM >= -6) {
					$basic_start_work_year = '应届毕业生';
				} else if ($workY == 0 && $workM > 6) {
					$basic_start_work_year = $workM . '个月工作经验';
				} else if ($basic_start_work_year <- 6) {
			    	$basic_start_work_year = '目前在读';
			    } else {
					$basic_start_work_year = $workY . '年工作经验';
				}

				$favs[$i]['start_work'] = $basic_start_work_year;
				if (base_lib_BaseUtils::nullOrEmpty($favs[$i]['start_work'])) {
					$favs[$i]['start_work'] = "应届毕业生";
				}

    			//最近工作经验
				if ($resume['current_station'] == '') {
					$favs[$i]['work'] = '无';
				} else {
					if (empty($resume['current_station_start_time'])) {
						$favs[$i]['work'] = $resume['current_station'];
					} else {
						$favs[$i]['work'] = $resume['current_station']
							. '(' . base_lib_TimeUtil::date_diff_year3($resume['current_station_start_time'], $resume['current_station_end_time']) . ')';
					}
				}

    			//头像				
				if ($person['photo_open'] != '0') {					
					if (base_lib_BaseUtils::nullOrEmpty($person['small_photo'])) {						
						if (!base_lib_BaseUtils::nullOrEmpty($person['photo'])) {
							$favs[$i]['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person['photo'];
						}
					} else {
						$favs[$i]['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person['photo'];//改版后用原始头像
					}
				}

				$favs[$i]['remark']    = base_lib_BaseUtils::cutstr($remark['remark'], 8, 'utf-8', '', '...');
				$favs[$i]['tagname']   = ($tag['tag_name'] == null ? "" : $tag['tag_name']);		
				$favs[$i]['down_time'] = date("Y-m-d H:i",strtotime($favs[$i]['create_time']));
                //工作经验
                //最近工作经历
                $count = count($workslist[$resume_id]);
				if ($count > 0) {
					$favs[$i]['worklist'] = array_slice($workslist["$resume_id"], 0, ($count >= 3 ? 3 : $count));
				} else {
					$favs[$i]['worklist'] = array();
				}

                //毕业院校
                $edu_info = $this->arrayFind($edu_data->items, 'resume_id', $resume_id);
				$favs[$i]['school']        = $edu_info['school'];
				$favs[$i]['major_desc']    = $edu_info['major_desc'];
				$favs[$i]['school_degree'] = $this->getDegree($edu_info['degree']) ;
                //判断是否活跃
                $chat_info = $chat_list[$person_id];
                $favs[$i]["is_active"] = $chat_info["count"] >= 3 ? true : false;

                //聊一聊状态 ，提示等级 1：(未登录)提示 2：（未登录）提示 + 提示消耗 3：提示消耗
                $chat_params['resume_id'] = $resume_id;
                $chat_params['person_id'] = $person_id;
                $chat_params['company_id'] = $this->_userid;
                //$favs[$i]['chat_status'] = $service_chat->getChatNoticeStatus($this->_userid,base_lib_BaseUtils::getCookie('accountid'),$login_status[$person_id],$chat_params);
                //$favs[$i]['chat_status'] = !empty($wy_person_status_arr[$person_id]) ? $wy_person_status_arr[$person_id] : false;
                $favs[$i]['chat_status'] = !empty($login_status[$person_id]) ? true : false;

            }
    		//分页
    		$this->_aParams['pager'] = $this->pageBar($result->totalSize,$page_size,$cur_page,$inPath);
    		// 下载的简历列表
    		$this->_aParams['favs'] = $favs;
    	}

		$this->_aParams['tag_id']       = $tag_id;
		$this->_aParams['keyword']      = $keyword;
		$this->_aParams['hasFav']       = ($totalCount > 0 ? true : false);
		$this->_aParams['hasFilterfav'] = (count($favs) > 0 ? true : false);
		$this->_aParams['favTotal']     = $result->totalSize;
		$this->_aParams['companytags']  = json_encode($new_companytags); 
		$this->_aParams['showfilter']   = false;
        
        //获得各种状态下收到的总条数
		$use_job_ids = array();
		$showStopJobApply = base_lib_BaseUtils::getCookie("showStopJobApply"); //判断用户是否包含了 停招招聘的职位
		if (base_lib_BaseUtils::nullOrEmpty($showStopJobApply) || $showStopJobApply != "true") {
			$service_job = new base_service_company_job_job();
			$job_status  = new base_service_common_jobstatus();

			$use_job_list = $service_job->getJobList($this->_userid, null, $job_status->pub, 'job_id');
			$use_job_ids  = $this->_buildIDs($use_job_list, "job_id");
			$use_job_ids  = !empty($use_job_ids) ? explode(",", $use_job_ids) : array();
		}

        $apply_status_count = $service_apply->getStatusGroupCount($this->_userid, $use_job_ids);
        $this->_aParams['apply_status_count'] = $apply_status_count->items;
        if (!base_lib_BaseUtils::nullOrEmpty($tag_id) || !base_lib_BaseUtils::nullOrEmpty($keyword)) {
			$this->_aParams['showfilter'] = true;
        }

        $xml = SXML::load('../config/config.xml');
        $this->_aParams['title'] = "我感兴趣的 简历管理_{$xml->HuiBoSiteName}";
        $service_question = new base_service_company_question();

        if($service_question->canAnswer($this->_userid)){
            $this->_aParams['is_question'] = 1;
        }
        if ($this->is_gray_company)
            return $this->render('./resume/fav/list_v2_gray.html', $this->_aParams);
        else
            return $this->render('./resume/fav/list_v2.html', $this->_aParams);
    }
    
    // 获取简历的邀请状态
 	public function pageGetStatus($inPath) {
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resumeids  = base_lib_BaseUtils::getStr($params['resumeid'],'array',null);
		$resumeids = base_lib_BaseUtils::getIntArrayOrString($resumeids);
		if(base_lib_BaseUtils::nullOrEmpty($resumeids)) {
	    	  $json['error'] = '请指定查看的简历';
	    	  echo json_encode($json);
	    	  return;			
		}
		$invite_service = new base_service_company_resume_jobinvite();
		$result = $invite_service->queryInviteList(implode(',',$resumeids), $this->_userid, 'resume_id');
		echo json_encode($result->items);
	    return;		
	}    
    
    
	/**
	 * 获取简历
	 * @param array $inPath
	 */
	public function pageresumeFav($inPath) { 
		$apply_status = new base_service_company_resume_applystatus();
	    $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
	    // 获取参数信息
	    $tag_id  =  base_lib_BaseUtils::getStr($path_data['tagid'],'int',0);
	    if($tag_id==0) {
	    	$tag_id = null;
	    } 
	    $page_size = base_lib_BaseUtils::getStr($path_data['pageSize'],'int',base_lib_Constant::PAGE_SIZE);
	    $cur_page = base_lib_BaseUtils::getStr($path_data['page'],'int',1);
	    $data = $this->__getFavList($cur_page,$page_size,$this->_userid,$tag_id);
	    echo json_encode($data);		   
	}
    
    
    private function __getFavList($cur_page,$page_size,$company_id,$tag_id=null){
    	// 简历收藏
		$service_fav = new base_service_company_resume_fav();
		$service_fav->setLimit($page_size);
		$service_fav->setPage($cur_page);
		$service_fav->setCount(true);
    	// 根据标记
    	$fav_list = $service_fav->getFavList($company_id,$tag_id,'fav_id,company_id,resume_id,create_time,is_effect');
    	$data = null;
		$fav_list_item = $fav_list->items;
		$fav_list_item_count = count($fav_list_item);
		if($fav_list_item_count>0){
			$service_resume = new base_service_person_resume_resume();
			$service_person = new base_service_person_person();
			$service_tag =  new base_service_company_resume_resumecompanytag();
			$resume_work = new base_service_person_resume_work();
			$resume_remark = new base_service_company_resume_resumeremark();
			$area = new base_service_common_area();
			$degree = new base_service_common_degree();
		    //标记信息,将简历信息，个人信息，工作信息和备注信息一次性取出来
			//$tag_ids = $this->getPropertys($fav_list_item, 'tag_id');
			//$resumetags = $service_tag->getResumeTagList($this->_userid, 'tag_id,company_id,tag_name,create_time');
			$resume_ids = $this->getPropertys($fav_list_item, 'resume_id');
	        $resumes = $service_resume->getResumes($resume_ids,'person_id,resume_id,resume_name,degree_id,is_effect,current_station,current_station_start_time,current_station_end_time',false);
	        $person_ids = $this->getPropertys($resumes->items, 'person_id');
	        $persons = $service_person->getPersons($person_ids, 'person_id,user_name,user_name_en,birthday2,cur_area_id,photo,small_photo,photo_open');
	        //$lastworks = $resume_work->getLastResumeWorks($resume_ids, 'work_id,resume_id,start_time,end_time,station');
	        $remarks = $resume_remark->getLastResumeRemarks($this->_userid, $resume_ids,'remark_id,resume_id,company_id,remark');
	        //适合的岗位
			$resumetags = $service_tag->getTagbyIDs($this->_userid,$resume_ids,'resume_id,tag_name');
			
	        for($i = 0; $i <  $fav_list_item_count; $i ++) {
				//收藏时间
				$fav_list_item[$i]['create_time_name'] = date('m-d',strtotime($fav_list_item[$i]['create_time']));
				
				//简历信息
				$resume_info =$this->arrayFind($resumes->items, 'resume_id', $fav_list_item[$i]['resume_id']);
				//用户信息
				$person_info = $this->arrayFind($persons->items, 'person_id', $resume_info['person_id']);

				//标记信息
				$tag_info = $this->arrayFind($resumetags->items, 'resume_id', $fav_list_item[$i]['resume_id']);
								
				//工作信息
				//$resume_work_info =  $this->arrayFind($lastworks->items, 'resume_id', $fav_list_item[$i]['resume_id']);
				//备注信息
				$resume_remark_info =  $this->arrayFind($remarks->items, 'resume_id', $fav_list_item[$i]['resume_id']);
				//姓名
				$fav_list_item[$i]['user_name'] = $person_info['user_name'];
			
				$fav_list_item[$i]['is_effect'] = $resume_info['is_effect'];
				//年龄 地区 学历
				$fav_list_item[$i]['age'] = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']).'岁';
				$fav_list_item[$i]['area'] = $area->getArea($person_info['cur_area_id']);
				$fav_list_item[$i]['degree'] = $degree->getDegree($resume_info['degree_id']);
				
				//最近工作经验
				if($resume_info['current_station']==''){
					$fav_list_item[$i]['work'] = '无';
				}else {
					if(empty($resume_info['current_station_start_time'])){
						$fav_list_item[$i]['work'] = $resume_info['current_station'];
					}else{
						$fav_list_item[$i]['work']= $resume_info['current_station'].'('.base_lib_TimeUtil::date_diff_year3($resume_info['current_station_start_time'],$resume_info['current_station_end_time']).')';
					}
				}
				
				//头像				
	       		if($person_info['photo_open']!='0'){					
					if(empty($person_info['small_photo'])){						
						if(!empty($person_info['photo'])){
							$photo = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$person_info['photo'];
							$fav_list_item[$i]['photo'] = '<img width="50px" height="50px" src="'.$photo.'" />';
						}else{
							$fav_list_item[$i]['photo'] = '';
						}
					}else{
						$photo = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$person_info['small_photo'];
						$fav_list_item[$i]['photo'] = '<img width="50px" height="50px" src="'.$photo.'" />';
					}
				}else{
					$fav_list_item[$i]['photo'] = '';
				}
				
				
				/*if($person_info['photo_open']!='0'){
					if(!base_lib_BaseUtils::nullOrEmpty($person_info['photo'])){
						$photo =  base_lib_Constant::YUN_ASSETS_URL."{$person_info['photo']}";
						$fav_list_item[$i]['photo'] = "<img width=\"50px\" height=\"50px\" src='{$photo}' />";
					}else{
						$fav_list_item[$i]['photo'] = '';
					}
				}else{
					$fav_list_item[$i]['photo'] = '';
				}*/
				 	
				//备注
				$fav_list_item[$i]['remark'] = $resume_remark_info['remark'];
				$fav_list_item[$i]['suit_work'] = $tag_info['tag_name'];
			}
			$data['list'] = $fav_list_item;
		}
	    $data['curPage'] = $cur_page;
		$data['pageSize'] = $page_size;	
		$data['recordCount'] = $fav_list->totalSize;
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
   	   if($arr==null) return null;
   	   $obj = null;
   	   if(count($arr>0)){
	   	   foreach ($arr as $item){
		   	  if($item[$property]==$value) {
		   	  	  $obj = $item;
		   	  	  break;
		   	  }
		   }
   	   }
	   return  $obj;
   }

	/**
	 * 
	 * 下载简历
	 */
	public function pageDown($inPath) {
        if(!$this->canDo("see_resume_info")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
        }
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_ids  = base_lib_BaseUtils::getStr($params['resumeid'],'array',null);
		$resume_ids  = base_lib_BaseUtils::getIntArrayOrString($resume_ids);
		$this->_aParams['resumeids'] = implode(",",$resume_ids);
		return $this->render('resume/fav/down.html', $this->_aParams);	
	}   

    /**
     * 标记感兴趣
     */
    public function pageFav($inPath) {
        //获取参数
    	$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
    	$resume_id = base_lib_BaseUtils::getStr($path_data['resume_id'],'int',0);
    	if($resume_id==0){
    		$json_arr = array("error"=>"对不起，该简历不存在");
    		echo json_encode($json_arr);
    		return;
    	}    
		//验证参数
    	$service_resume = new base_service_person_resume_resume();
    	$resume = $service_resume->getResume($resume_id, "resume_id,person_id");
    	
    	if(base_lib_BaseUtils::nullOrEmpty($resume)){
    		$json_arr = array("error"=>"对不起，该简历不存在");
    		echo json_encode($json_arr);
    		return;
    	}
		$company_id = $this->_userid; 
	    $service_company  = new base_service_company_company();
    	$company = $service_company->getCompany($company_id, 1,"company_id");
    	
    	if(base_lib_BaseUtils::nullOrEmpty($company)){
    		$json_arr = array("error"=>"请重新登录");
    		echo json_encode($json_arr);
    		return;
    	}
		
    	$service_fav = new base_service_company_resume_fav();
    	//收藏
    	//检查是否已收藏
    	$favinfo = $service_fav->isFav($company_id, $resume_id);
    	if($favinfo['fav_id'] > 0 ){
    		//有旧数据
    		if($favinfo["is_effect"] == "1"){
    			//收藏过并有效－取消收藏		
	    		$service_fav->cancelFav($company_id,$resume_id,$favinfo['fav_id']);
	    		$fav_type = "cancel";
    		}
    		elseif ($favinfo["is_effect"] == "0"){
    			//收藏过但无效－重置为有效
    			$service_fav->revertFav($company_id, $resume_id,$favinfo['fav_id']);
    			$fav_type = "add";
    		}
    	}
    	else{
    		//未收藏－新增
    		//组装数据 
    		$fav["company_id"] = $company_id;
    		$fav["resume_id"] = $resume_id;
    		$fav["create_time"] = date("Y-m-d H:i:s");
    		$fav["is_effect"] = "1";
    		//添加
    		$service_fav->addFav($fav);
    		$fav_type = "add";
    	}
    	
    	$json_arr["fav_type"] = $fav_type;

		//---------添加操作日志--------
		$service_persons = new base_service_person_person();
		$resume_infos = $service_persons->getPerson($resume['person_id'],'person_id,user_name');
		$common_oper_type = new base_service_common_account_accountoperatetype();
		$service_oper_log = new base_service_company_companyaccountlog();
		$common_oper_src_type = new base_service_common_account_accountlogfrom();


		if($fav_type == 'cancel'){
			$insertItems=array(
				"company_id"=>$this->_userid,
				"source"=>$common_oper_src_type->website,
				"account_id"=>base_lib_BaseUtils::getCookie('accountid'),
				"operate_type"=>$common_oper_type->resume_collect_cancel,
				"content"=>"取消了'".$resume_infos['user_name']."'的简历收藏",
				"create_time"=>date("Y-m-d H:i:s",time())
			);
		}else{
			$insertItems=array(
				"company_id"=>$this->_userid,
				"account_id"=>base_lib_BaseUtils::getCookie('accountid'),
				"source"=>$common_oper_src_type->website,
				"operate_type"=>$common_oper_type->resume_collect,
				"content"=>"收藏了'".$resume_infos['user_name']."'的简历",
				"create_time"=>date("Y-m-d H:i:s",time())
			);
		}

		$service_oper_log->addLogToMongo($insertItems);
		//-------------END------------

    	echo json_encode($json_arr);
    	return; 
    }
    
    
	/**
	 *@ 删除收藏的简历(传收藏编号删除)
	 *@return
	*/
	public function pageDeleteFavs($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$operate =   base_lib_BaseUtils::getStr($path_data['op'],'string',''); 
		$ids = base_lib_BaseUtils::getStr($path_data['ids'],'array',null);
		$ids = base_lib_BaseUtils::getIntArrayOrString($ids);
		
	    switch($operate){
			case 'del':
                if(!$this->canDo("delete_resume")){
                    echo json_encode(array('error' => '无权限访问，没有开通相应权限'));
					return ;
                }
				$fav_ids =$ids;
				if(base_lib_BaseUtils::nullOrEmpty($fav_ids)){
					echo json_encode(array('error'=>'请选择你要取消收藏的简历'));
					return ;
				}
				$service_resume_fav = new base_service_company_resume_fav();
				foreach ($fav_ids as $fav_id){
					$service_resume_fav->setFavs($this->_userid,$fav_id,'0');
				}

				//获取简历用户名
				$fav_infos = $service_resume_fav->getFavs($ids,'resume_id');
				$resume_ids = base_lib_BaseUtils::getProperty($fav_infos->items,'resume_id');
				$service_persons = new base_service_person_person();
				$service_resumes = new base_service_person_resume_resume();
				$resume_infos = $service_resumes->getResumes($resume_ids,'resume_id,person_id');
				$person_ids = base_lib_BaseUtils::getProperty($resume_infos->items,'person_id');
				$resume_infos = $service_persons->getPersons($person_ids,'person_id,user_name')->items;
				$person_user_name = base_lib_BaseUtils::getProperty($resume_infos,'user_name');
				$log_message = implode('，',$person_user_name);
				//---------添加操作日志--------
				$common_oper_type = new base_service_common_account_accountoperatetype();
				$service_oper_log = new base_service_company_companyaccountlog();
				$common_oper_src_type = new base_service_common_account_accountlogfrom();
				$insertItems=array(
					"company_id"=>$this->_userid,
					"source"=>$common_oper_src_type->website,
					"account_id"=>base_lib_BaseUtils::getCookie('accountid'),
					"operate_type"=>$common_oper_type->resume_delete,
					"content"=>"已下收藏的简历被放入回收站：".$log_message,
					"create_time"=>date("Y-m-d H:i:s",time())
				);
				$service_oper_log->addLogToMongo($insertItems);
				//-------------END------------


				echo json_encode(array('success'=>'1'));				
				break;
			default:
                if(!$this->canDo("delete_resume")){
                    $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
                    $this->_aParams["url"] = "/";
                    return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
                }
	    		// 跳转到提示窗口
	    		$names =  explode(',',urldecode(base_lib_BaseUtils::getStr($path_data['names'])));
	    		$this->_aParams['names'] = $names;
	    		$this->_aParams['ids'] = implode(",",$ids);
	    		return  $this->render('resume/fav/delete.html', $this->_aParams);
	        	break;				
	   }

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
    	return implode(',', $newArr);
    	
    }	
    /**
      * 获取学历
      */
	private function getDegree($degree_id,$default='') {
		if(empty($degree_id)) {
			return $default;
		}
		$service_degree = new base_service_common_degree();
		return $service_degree->getDegree($degree_id);
	}

	
	private function _checkNeedContact($memberinfo, $apply, $download) {
    	if ($memberinfo == "notmember" && empty($download))
    		return true;

    	if ($memberinfo != 'notmember' && empty($download) && empty($apply))
    		return true;

    	return false;
    }
}