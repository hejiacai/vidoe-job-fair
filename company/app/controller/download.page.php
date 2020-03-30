<?php
/**
 * @copyright 2002-2013 www.huibo.com
 * @name 下载的简历
 * @author ZhangYu
 * @version 2014-7-2
 */
class controller_download extends components_cbasepage {
    
    function __construct() {
        parent::__construct();
    }

    /**
     * 简历管理 >> 待处理简历 >> 主页
     * @param  mixed $inPath 链接参数
     * @return html          resume/download/list_v2.html
     */
    public function pageIndex($inPath) {
        if(!$this->canDo("resume_manage")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
        $params     = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        //该企业是否需要使用新功能（是否参与灰度测试
        $is_gray = $this->is_gray_company;
        if($is_gray){
            //灰度测试单位跳转新方法
            $this->redirect_url2("/download/grayIndex", $params);
        }

        $tag_id     = base_lib_BaseUtils::getStr($params['tag_id'], 'int', null);
        $down_time  = base_lib_BaseUtils::getStr($params['down_time'], 'int', null);
        $keyword    = base_lib_BaseUtils::getStr($params['keyword'], 'string', '');
        $cur_page   = base_lib_BaseUtils::getStr($params['page'], 'int', 1);
        $page_size  = base_lib_BaseUtils::getStr($params['pageSize'], 'int', base_lib_Constant::PAGE_SIZE);
        $searchmode = base_lib_BaseUtils::getStr($params['search_model'], 'int', '0'); // 查询方式 0表示按筛选条件，1表示按姓名/简历编号

        //@update 增加获取时间，获取人 2019-06-06 by pengChengGong
        $get_time_start  = base_lib_BaseUtils::getStr($params['s_time'], 'string', null);
        $get_time_end    = base_lib_BaseUtils::getStr($params['e_time'], 'string', null);
        $down_account_id = base_lib_BaseUtils::getStr($params['a_id'], 'string', "all");

        //开始时间大于结束时间 ，交换位置
        if(!empty($get_time_start) && !empty($get_time_end) && $get_time_start>$get_time_end){
            list($get_time_start,$get_time_end) = [ $get_time_end ,$get_time_start];
        }

	    if ($down_time === 0) {
	    	$down_time = '+0'; // XXX：js的下拉组件默认认为选项为0的项为默认项
	    }

        $download_service = new base_service_company_resume_download();
        $tag_service      = new base_service_company_companytag();
        $service_account  = new base_service_company_account();

    	$totalCount = $download_service->getDownloadCount($this->_userid);// 下载简历数量
    	// 获取所有的tag
    	$companytags = $tag_service->getCompanyTagList($this->_userid, 'tag_id as id,tag_name as name')->items;
	    $new_companytags = array_merge(array(array('id'=>'','name'=>'按全部查看')) ,$companytags);	   	

    	$cur_accountid = base_lib_BaseUtils::getCookie('accountid');
        $accounttags =  $service_account->getAccountCompany($this->_userid,'account_id as id,user_name as name,resource_type,is_main','main');
    	$accounttags = base_lib_BaseUtils::array_key_assoc($accounttags,'id');

    	if($accounttags[$cur_accountid]['is_main']){
            $new_accounttags = array_merge(array(array('id'=>'all','name'=>'按全部查看')) ,$accounttags);
        }else{

            if($down_account_id === 'all' || $down_account_id>0){
                //用户选择了查看全部，这里要搜索全部的
            }else{
                //该条件无搜索的时候
                $down_account_id = $cur_accountid;
            }


    	    if((int)$accounttags[$cur_accountid]['resource_type'] ===1){
    	        //共享

                $new_accounttags = array_merge(array(array('id'=>'all','name'=>'按全部查看')) ,$accounttags);
            }else{
    	        //分配
                $new_accounttags[] = $accounttags[$cur_accountid];
            }
        }


        $_go_search_down_account_id = $down_account_id === 'all' ? null:$down_account_id;

        if ($searchmode != 0) {
            $tag_id    = null;
            $down_time = null;
            $get_time_start = null;
            $get_time_end = null;
            $_go_search_down_account_id = null;

        } else {
            $keyword   = null;
        }

        $result    = $download_service->getDownLoadResumes($page_size, $cur_page, $this->_userid, 'download_id,resume_id,info_resume_download.person_id,down_time,is_invite',$keyword,$tag_id,$down_time,$get_time_start,$get_time_end,$_go_search_down_account_id);
        $downloads = $result->items;

    	$this->_aParams['totalSize'] = $result->totalSize;
    	
        if (count($downloads) > 0) {
    		$persin_ids = $this->_buildIDs($downloads, 'person_id');
    		$resume_ids = $this->_buildIDs($downloads, 'resume_id');
            
            $service_resume        = new base_service_person_resume_resume();
            $service_person        = new base_service_person_person();
            $service_resume_remark = new base_service_company_resume_resumeremark();
            $service_area          = new base_service_common_area();
            $service_degree        = new base_service_common_degree();
            $service_tag           = new base_service_company_resume_resumecompanytag();
            $enum_openmode         = new base_service_common_openmode();
    
            $resume_data = $service_resume->getResumeListByIDs($resume_ids, 'resume_id,resume_name,degree_id,is_effect,current_station,current_station_start_time,current_station_end_time'); //简历
            $resumes     = $this->_buildArray($resume_data->items, 'resume_id');
            
            $person_data = $service_person->GetPersonListByIDs($persin_ids, 'person_id,open_mode,user_name,name_open,sex,birthday,cur_area_id,photo_open,photo,small_photo,birthday2,mobile_phone,start_work'); // 求职者
            $persons     = $this->_buildArray($person_data->items, 'person_id');
            
            $service_qloudmsg = new base_service_app_qcloudmsg();
            $chat_list        = $service_qloudmsg->getReplyTimes($persin_ids);
            $chat_list        = base_lib_BaseUtils::array_key_assoc($chat_list, "person_id");
            
            $remark_data = $service_resume_remark->getLastRemarkByResumeIDs($this->_userid, $resume_ids, 'remark_id,resume_id,company_id,remark'); //备注
            $remarks     = $this->_buildArray($remark_data->items, 'resume_id');

            //毕业院校
            $service_edu = new base_service_person_resume_edu();
            $edu_data = $service_edu->getResumeEdus($resume_ids, 'resume_id,school,major_desc,degree');
            //工作经验
            $service_resume_work = new base_service_person_resume_work();
            $work_datas = $service_resume_work->getResumeWorks($resume_ids, "work_id,resume_id,start_time,end_time,station,company_name,work_content");

            if (count($work_datas->items) > 0) {
                foreach ($work_datas->items as $workskey => $worksvalue) {
                    $workslist[$worksvalue["resume_id"]][$workskey]['start_time']   = date('Y/m',strtotime($worksvalue['start_time']));
                    $workslist[$worksvalue["resume_id"]][$workskey]['end_time']     = empty($worksvalue['end_time'])?"至今":date('Y/m',strtotime($worksvalue['end_time']));
                    $workslist[$worksvalue["resume_id"]][$workskey]['station']      = $worksvalue['station'];
                    $workslist[$worksvalue["resume_id"]][$workskey]['company_name'] = $worksvalue['company_name'];
                    $workslist[$worksvalue["resume_id"]][$workskey]['work_content'] = base_lib_BaseUtils::cutstr($worksvalue['work_content'], 180,'utf-8','','...');
                }
            }
    		// 适合岗位
			$tag_data = $service_tag->getTagbyIDs($this->_userid,$resume_ids, 'resume_id,tag_name');
    		$tags = $this->_buildArray($tag_data->items, 'resume_id');

    		//根据简历ids获取面试邀请情况
            $service_invite    = new base_service_company_resume_jobinvite();
            $company_resources = new base_service_company_resources_resources($this->_userid);
            $last_invite_list = $service_invite->getLastInviteByResumeids($resume_ids,$company_resources->all_accounts);
            if(!empty($last_invite_list)){
                $last_invite_list = base_lib_BaseUtils::array_key_assoc($last_invite_list,'resume_id');
            }

            //求职者一周内是否登录过app
            $sercie_loginlog= new base_service_person_loginlog();
            $login_status = $sercie_loginlog->getLoginAppData($persin_ids,14);
            if(!empty($login_status))
                $login_status = base_lib_BaseUtils::array_key_assoc($login_status,'person_id');
            //$service_chat = new company_service_chat(0,0);
            /*$service_wangyiaction = new base_service_app_wangyiaction();
            $wy_person_status_arr = $service_wangyiaction->checkPersonsIsOnline($persin_ids);*/

            for ($i = 0,$len = count($downloads); $i < $len; $i++) {
    			$resume_id = $downloads[$i]['resume_id'];
    			$person_id = $downloads[$i]['person_id'];
	
                $resume      = $resumes["{$resume_id}"];
                $person      = $persons["{$person_id}"];
                $remark      = $remarks["{$resume_id}"];
                $resume_work = $works["{$resume_id}"];
                $tag         = $tags["{$resume_id}"];
    			
                //姓名
				if ($person['name_open'] == 1) {
					$downloads[$i]['user_name'] = $person['user_name'];
				} else {
                    $sex_name = $person['sex'] == 1 ? '先生' : '女士';
                    $downloads[$i]['user_name'] = mb_substr($person['user_name'], 0, 1, 'utf-8') . $sex_name;
				}

 				//年龄 地区 学历
                if($this->canDo("see_resume_mobile")){
                    $downloads[$i]['phone']  = $person['mobile_phone'];  
                }else{
                    $downloads[$i]['phone']  = preg_replace('#(\d{3})[a-z0-9-]{2,10}(\d{3})#', '${1}****${2}', $person['mobile_phone']);
                }
                $downloads[$i]['age']    = base_lib_TimeUtil::ceil_diff_year($person['birthday2']) . '岁';
                $downloads[$i]['area']   = $service_area->getArea($person['cur_area_id']);
                $downloads[$i]['degree'] = $service_degree->getDegree($resume['degree_id']); 
                $downloads[$i]['sex']    = base_lib_BaseUtils::nullOrEmpty($person['sex']) ? '' : ($person['sex'] == 1 ? '男' : '女');

                //是否已经发送offer
                $downloads[$i]['is_send_offer'] = $last_invite_list[  $downloads[$i]['resume_id'] ]['offer_send_time'] ? true : false;
                $downloads[$i]['invite_id']     = $last_invite_list[  $downloads[$i]['resume_id'] ]['invite_id'];

    			// 个人信息未公开
                if (round((strtotime($this->now())-strtotime($downloads[$i]['down_time']))/3600) > 24){ //下载时间在24小时内，企业任可以查看
                    if ($person['open_mode'] == $enum_openmode->notopen) {
                        $downloads[$i]['notopen'] = true;
                    }
                }

            	// 简历已删除
    			if ($resume['is_effect'] != 1) {
					$downloads[$i]['isdelete'] = true ;
				}

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

				$downloads[$i]['start_work'] = $basic_start_work_year;
				if (base_lib_BaseUtils::nullOrEmpty($downloads[$i]['start_work'])) {
					$downloads[$i]['start_work'] = "应届毕业生";
				}
				
    			//最近工作经验
				if ($resume['current_station'] == '') {
					$downloads[$i]['work'] = '无';
				} else {
					if (empty($resume['current_station_start_time'])) {
						$downloads[$i]['work'] = $resume['current_station'];
					} else {
						$downloads[$i]['work'] = $resume['current_station']
                            . '(' . base_lib_TimeUtil::date_diff_year3($resume['current_station_start_time'], $resume['current_station_end_time']) . ')';
					}
				}
                
                //毕业院校
                $edu_info = $this->arrayFind($edu_data->items, 'resume_id', $resume_id);
                $downloads[$i]['school']        = $edu_info['school'];
                $downloads[$i]['major_desc']    = $edu_info['major_desc'];
                $downloads[$i]['school_degree'] = $this->getDegree($edu_info['degree']) ;
                
                //头像				
                if ($person['photo_open'] != '0') {					
                    if (base_lib_BaseUtils::nullOrEmpty($person['small_photo'])) {	
						if (!base_lib_BaseUtils::nullOrEmpty($person['photo'])) {
							$downloads[$i]['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person['photo'];
						}
                    } else {
						$downloads[$i]['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person['photo'];//改版后用原始头像
                    }
                }

                $downloads[$i]['remark']    = base_lib_BaseUtils::cutstr($remark['remark'], 8, 'utf-8', '', "...");
                $downloads[$i]['tagname']   = ($tag['tag_name'] == null ? "" : $tag['tag_name']);
                $downloads[$i]['down_time'] = date("Y-m-d H:i", strtotime($downloads[$i]['down_time']));
                //判断是否活跃
                $chat_info = $chat_list[$person_id];
                $downloads[$i]["is_active"] = $chat_info["count"] >= 3 ? true : false;
                //工作经验
                //最近工作经历
                $count = count($workslist["$resume_id"]);
				if ($count > 0) {
					$downloads[$i]['worklist'] = array_slice($workslist["$resume_id"], 0, ($count >= 3 ? 3 : $count));
				} else {
					$downloads[$i]['worklist'] = array();
				}

                //聊一聊状态 ，提示等级 1：(未登录)提示 2：（未登录）提示 + 提示消耗 3：提示消耗
                $chat_params['resume_id'] = $resume_id;
                $chat_params['person_id'] = $person_id;
                $chat_params['company_id'] = $this->_userid;
                //$downloads[$i]['chat_status'] = $service_chat->getChatNoticeStatus($this->_userid,base_lib_BaseUtils::getCookie('accountid'),$login_status[$person_id],$chat_params);
                //$downloads[$i]['chat_status'] = $service_wangyiaction->checkPersonIsOnline($person_id);
                //$downloads[$i]['chat_status'] = !empty($wy_person_status_arr[$person_id]) ? $wy_person_status_arr[$person_id] : false;
                $downloads[$i]['chat_status'] = !empty($login_status[$person_id]) ? true : false;
            }
    		
            //分页
    		$this->_aParams['pager'] = $this->pageBar($result->totalSize, $page_size, $cur_page, $inPath);
    		
            // 下载的简历列表
    		$this->_aParams['downresumes'] = $downloads;
    	}

        $this->_aParams['tag_id']            = $tag_id;
        $this->_aParams['down_time']         = $down_time;
        $this->_aParams['keyword']           = $keyword;
        $this->_aParams['hasdownload']       = ($totalCount > 0 ? true: false);
        $this->_aParams['hasFilterdownload'] = (count($downloads) > 0 ? true : false);
        $this->_aParams['downloadTotal']     = $result->totalSize;
        $this->_aParams['companytags']       = json_encode($new_companytags); 
        $this->_aParams['accounttags']       = json_encode($new_accounttags);
        $this->_aParams['showfilter']        = false;
        //@update 增加获取时间，获取人 2019-06-06 by pengChengGong
        $this->_aParams['s_time']    = $get_time_start;
        $this->_aParams['e_time']    = $get_time_end;
        $this->_aParams['accountid'] = $down_account_id;


        //获得各种状态下收到的总条数
		$use_job_ids = array();
		$showStopJobApply = base_lib_BaseUtils::getCookie("showStopJobApply"); //判断用户是否包含了 停招招聘的职位
		if (base_lib_BaseUtils::nullOrEmpty($showStopJobApply) || $showStopJobApply!="true") {
            $service_job = new base_service_company_job_job();
            $job_status  = new base_service_common_jobstatus();

            $use_job_list = $service_job->getJobList($this->_userid, null, $job_status->pub, 'job_id');
            $use_job_ids  = $this->_buildIDs($use_job_list, "job_id");
            $use_job_ids  = !empty($use_job_ids) ? explode(",", $use_job_ids) : array();
		}

		$service_apply = new base_service_company_resume_apply();
        $apply_status_count = $service_apply->getStatusGroupCount($this->_userid, $use_job_ids);
        $this->_aParams['apply_status_count'] = $apply_status_count->items;
        if (!base_lib_BaseUtils::nullOrEmpty($tag_id) || !base_lib_BaseUtils::nullOrEmpty($down_time) || !base_lib_BaseUtils::nullOrEmpty($keyword)) {
            $this->_aParams['showfilter'] = true;	
        }
        $service_question = new base_service_company_question();

        if($service_question->canAnswer($this->_userid)){
            $this->_aParams['is_question'] = 1;
        }
        $xml = SXML::load('../config/config.xml');
        $this->_aParams['title'] = "下载的简历 简历管理_{$xml->HuiBoSiteName}";	
    	return $this->render('resume/download/list_v2.html', $this->_aParams);
    }


    /**
     * 简历管理 >> 待处理简历 >> 主页
     * @param  mixed $inPath 链接参数
     * @return html          resume/download/list_v2.html
     */
    public function pagegrayIndex($inPath) {
        if(!$this->canDo("resume_manage")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
        $params     = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));


        $tag_id     = base_lib_BaseUtils::getStr($params['tag_id'], 'int', null);
        $down_time  = base_lib_BaseUtils::getStr($params['down_time'], 'int', null);
        $keyword    = base_lib_BaseUtils::getStr($params['keyword'], 'string', '');
        $cur_page   = base_lib_BaseUtils::getStr($params['page'], 'int', 1);
        $page_size  = base_lib_BaseUtils::getStr($params['pageSize'], 'int', base_lib_Constant::PAGE_SIZE);
        $searchmode = base_lib_BaseUtils::getStr($params['search_model'], 'int', '0'); // 查询方式 0表示按筛选条件，1表示按姓名/简历编号

        //@update 增加获取时间，获取人 2019-06-06 by pengChengGong
        $get_time_start  = base_lib_BaseUtils::getStr($params['s_time'], 'string', null);
        $get_time_end    = base_lib_BaseUtils::getStr($params['e_time'], 'string', null);
        $down_account_id = base_lib_BaseUtils::getStr($params['a_id'], 'string', "all");

        //开始时间大于结束时间 ，交换位置
        if(!empty($get_time_start) && !empty($get_time_end) && $get_time_start>$get_time_end){
            list($get_time_start,$get_time_end) = [ $get_time_end ,$get_time_start];
        }

        if ($down_time === 0) {
            $down_time = '+0'; // XXX：js的下拉组件默认认为选项为0的项为默认项
        }

        $download_service = new base_service_company_resume_download();
        $tag_service      = new base_service_company_companytag();
        $service_account  = new base_service_company_account();

        $totalCount = $download_service->getDownloadCount($this->_userid);// 下载简历数量
        // 获取所有的tag
        $companytags = $tag_service->getCompanyTagList($this->_userid, 'tag_id as id,tag_name as name')->items;
        $new_companytags = array_merge(array(array('id'=>'','name'=>'按全部查看')) ,$companytags);

        $cur_accountid = base_lib_BaseUtils::getCookie('accountid');
        $accounttags =  $service_account->getAccountCompany($this->_userid,'account_id as id,user_name as name,resource_type,is_main','main');
        $accounttags = base_lib_BaseUtils::array_key_assoc($accounttags,'id');

        if($accounttags[$cur_accountid]['is_main']){
            $new_accounttags = array_merge(array(array('id'=>'all','name'=>'按全部查看')) ,$accounttags);
        }else{

            if($down_account_id === 'all' || $down_account_id>0){
                //用户选择了查看全部，这里要搜索全部的
            }else{
                //该条件无搜索的时候
                $down_account_id = $cur_accountid;
            }


            if((int)$accounttags[$cur_accountid]['resource_type'] ===1){
                //共享

                $new_accounttags = array_merge(array(array('id'=>'all','name'=>'按全部查看')) ,$accounttags);
            }else{
                //分配
                $new_accounttags[] = $accounttags[$cur_accountid];
            }
        }


        $_go_search_down_account_id = $down_account_id === 'all' ? null:$down_account_id;

        if ($searchmode != 0) {
            $tag_id    = null;
            $down_time = null;
            $get_time_start = null;
            $get_time_end = null;
            $_go_search_down_account_id = null;

        } else {
            $keyword   = null;
        }

        $result    = $download_service->getDownLoadResumes($page_size, $cur_page, $this->_userid, 'download_id,resume_id,info_resume_download.person_id,down_time,is_invite',$keyword,$tag_id,$down_time,$get_time_start,$get_time_end,$_go_search_down_account_id);
        $downloads = $result->items;

        $this->_aParams['totalSize'] = $result->totalSize;

        if (count($downloads) > 0) {
            $persin_ids = $this->_buildIDs($downloads, 'person_id');
            $resume_ids = $this->_buildIDs($downloads, 'resume_id');


            $service_resume        = new base_service_person_resume_resume();
            $service_person        = new base_service_person_person();
            $service_resume_remark = new base_service_company_resume_resumeremark();
            $service_area          = new base_service_common_area();
            $service_degree        = new base_service_common_degree();
            $service_tag           = new base_service_company_resume_resumecompanytag();
            $enum_openmode         = new base_service_common_openmode();

            $resume_data = $service_resume->getResumeListByIDs($resume_ids, 'resume_id,resume_name,degree_id,is_effect,current_station,current_station_start_time,current_station_end_time'); //简历
            $resumes     = $this->_buildArray($resume_data->items, 'resume_id');

            $person_data = $service_person->GetPersonListByIDs($persin_ids, 'person_id,open_mode,user_name,name_open,sex,birthday,cur_area_id,photo_open,photo,small_photo,birthday2,mobile_phone,start_work'); // 求职者
            $persons     = $this->_buildArray($person_data->items, 'person_id');

            $service_qloudmsg = new base_service_app_qcloudmsg();
            $chat_list        = $service_qloudmsg->getReplyTimes($persin_ids);
            $chat_list        = base_lib_BaseUtils::array_key_assoc($chat_list, "person_id");

            $remark_data = $service_resume_remark->getLastRemarkByResumeIDs($this->_userid, $resume_ids, 'remark_id,resume_id,company_id,remark'); //备注
            $remarks     = $this->_buildArray($remark_data->items, 'resume_id');

            //毕业院校
            $service_edu = new base_service_person_resume_edu();
            $edu_data = $service_edu->getResumeEdus($resume_ids, 'resume_id,school,major_desc,degree');
            //工作经验
            $service_resume_work = new base_service_person_resume_work();
            $work_datas = $service_resume_work->getResumeWorks($resume_ids, "work_id,resume_id,start_time,end_time,station,company_name,work_content");

            if (count($work_datas->items) > 0) {
                foreach ($work_datas->items as $workskey => $worksvalue) {
                    $workslist[$worksvalue["resume_id"]][$workskey]['start_time']   = date('Y/m',strtotime($worksvalue['start_time']));
                    $workslist[$worksvalue["resume_id"]][$workskey]['end_time']     = empty($worksvalue['end_time'])?"至今":date('Y/m',strtotime($worksvalue['end_time']));
                    $workslist[$worksvalue["resume_id"]][$workskey]['station']      = $worksvalue['station'];
                    $workslist[$worksvalue["resume_id"]][$workskey]['company_name'] = $worksvalue['company_name'];
                    $workslist[$worksvalue["resume_id"]][$workskey]['work_content'] = base_lib_BaseUtils::cutstr($worksvalue['work_content'], 180,'utf-8','','...');
                }
            }
            // 适合岗位
            $tag_data = $service_tag->getTagbyIDs($this->_userid,$resume_ids, 'resume_id,tag_name');
            $tags = $this->_buildArray($tag_data->items, 'resume_id');

            //根据简历ids获取面试邀请情况
            $service_invite    = new base_service_company_resume_jobinvite();
            $company_resources = new base_service_company_resources_resources($this->_userid);
            $last_invite_list = $service_invite->getLastInviteByResumeids($resume_ids,$company_resources->all_accounts);

            if(!empty($last_invite_list)){
                $last_invite_list = base_lib_BaseUtils::array_key_assoc($last_invite_list,'resume_id');
            }
            
            $enum_result = new base_service_company_resume_auditionresult();
            //求职者一周内是否登录过app
            $sercie_loginlog= new base_service_person_loginlog();
            $login_status = $sercie_loginlog->getLoginAppData($persin_ids,14);
            if(!empty($login_status))
                $login_status = base_lib_BaseUtils::array_key_assoc($login_status,'person_id');
            //$service_chat = new company_service_chat(0,0);
            $service_wangyiaction = new base_service_app_wangyiaction();
            $wy_person_status_arr = $service_wangyiaction->checkPersonsIsOnline($persin_ids);

            for ($i = 0,$len = count($downloads); $i < $len; $i++) {
                $resume_id = $downloads[$i]['resume_id'];
                $person_id = $downloads[$i]['person_id'];

                $resume      = $resumes["{$resume_id}"];
                $person      = $persons["{$person_id}"];
                $remark      = $remarks["{$resume_id}"];
                $resume_work = $works["{$resume_id}"];
                $tag         = $tags["{$resume_id}"];

                //姓名
                if ($person['name_open'] == 1) {
                    $downloads[$i]['user_name'] = $person['user_name'];
                } else {
                    $sex_name = $person['sex'] == 1 ? '先生' : '女士';
                    $downloads[$i]['user_name'] = mb_substr($person['user_name'], 0, 1, 'utf-8') . $sex_name;
                }

                //年龄 地区 学历
                if($this->canDo("see_resume_mobile")){
                    $downloads[$i]['phone']  = $person['mobile_phone'];
                }else{
                    $downloads[$i]['phone']  = preg_replace('#(\d{3})[a-z0-9-]{2,10}(\d{3})#', '${1}****${2}', $person['mobile_phone']);
                }
                $downloads[$i]['age']    = base_lib_TimeUtil::ceil_diff_year($person['birthday2']) . '岁';
                $downloads[$i]['area']   = $service_area->getArea($person['cur_area_id']);
                $downloads[$i]['degree'] = $service_degree->getDegree($resume['degree_id']);
                $downloads[$i]['sex']    = base_lib_BaseUtils::nullOrEmpty($person['sex']) ? '' : ($person['sex'] == 1 ? '男' : '女');

                //是否已经发送offer
                $downloads[$i]['is_send_offer'] = $last_invite_list[  $downloads[$i]['resume_id'] ]['offer_send_time'] ? true : false;
                $downloads[$i]['invite_id']     = $last_invite_list[  $downloads[$i]['resume_id'] ]['invite_id'];

                //面试邀请状态
                $invite_status = 0;
                if($downloads[$i]['is_invite'] == 1){

                    $invite_re_status = $last_invite_list[$downloads[$i]['resume_id']]['re_status'];
                    $invite_audition_result = $last_invite_list[$downloads[$i]['resume_id']]['audition_result'];

                    if($invite_audition_result == $enum_result->pass && !in_array($invite_re_status,[3,9])){
                        //面试通过
                        $invite_status = 2;
                    }elseif($invite_audition_result == $enum_result->notpass && !in_array($invite_re_status,[3,9])){
                        //面试未通过
                        $invite_status = 3;
                    }elseif(in_array($invite_re_status,[3,4,9])){
                        //面试爽约
                        $invite_status = 4;
                    }elseif($invite_audition_result == $enum_result->entry){
                        //已入职
                        $invite_status = 5;
                    }else{
                        //已邀请面试
                        $invite_status = 1;
                    }
                }

                $downloads[$i]['invite_status'] = $invite_status;

                // 个人信息未公开
                if (round((strtotime($this->now())-strtotime($downloads[$i]['down_time']))/3600) > 24){ //下载时间在24小时内，企业任可以查看
                    if ($person['open_mode'] == $enum_openmode->notopen) {
                        $downloads[$i]['notopen'] = true;
                    }
                }

                // 简历已删除
                if ($resume['is_effect'] != 1) {
                    $downloads[$i]['isdelete'] = true ;
                }

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

                $downloads[$i]['start_work'] = $basic_start_work_year;
                if (base_lib_BaseUtils::nullOrEmpty($downloads[$i]['start_work'])) {
                    $downloads[$i]['start_work'] = "应届毕业生";
                }

                //最近工作经验
                if ($resume['current_station'] == '') {
                    $downloads[$i]['work'] = '无';
                } else {
                    if (empty($resume['current_station_start_time'])) {
                        $downloads[$i]['work'] = $resume['current_station'];
                    } else {
                        $downloads[$i]['work'] = $resume['current_station']
                            . '(' . base_lib_TimeUtil::date_diff_year3($resume['current_station_start_time'], $resume['current_station_end_time']) . ')';
                    }
                }

                //毕业院校
                $edu_info = $this->arrayFind($edu_data->items, 'resume_id', $resume_id);
                $downloads[$i]['school']        = $edu_info['school'];
                $downloads[$i]['major_desc']    = $edu_info['major_desc'];
                $downloads[$i]['school_degree'] = $this->getDegree($edu_info['degree']) ;

                //头像
                if ($person['photo_open'] != '0') {
                    if (base_lib_BaseUtils::nullOrEmpty($person['small_photo'])) {
                        if (!base_lib_BaseUtils::nullOrEmpty($person['photo'])) {
                            $downloads[$i]['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person['photo'];
                        }
                    } else {
                        $downloads[$i]['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person['photo'];//改版后用原始头像
                    }
                }

                $downloads[$i]['remark']    = base_lib_BaseUtils::cutstr($remark['remark'], 8, 'utf-8', '', "...");
                $downloads[$i]['tagname']   = ($tag['tag_name'] == null ? "" : $tag['tag_name']);
                $downloads[$i]['down_time'] = date("Y-m-d H:i", strtotime($downloads[$i]['down_time']));
                //判断是否活跃
                $chat_info = $chat_list[$person_id];
                $downloads[$i]["is_active"] = $chat_info["count"] >= 3 ? true : false;
                //工作经验
                //最近工作经历
                $count = count($workslist["$resume_id"]);
                if ($count > 0) {
                    $downloads[$i]['worklist'] = array_slice($workslist["$resume_id"], 0, ($count >= 3 ? 3 : $count));
                } else {
                    $downloads[$i]['worklist'] = array();
                }

                //聊一聊状态 ，提示等级 1：(未登录)提示 2：（未登录）提示 + 提示消耗 3：提示消耗
                $chat_params['resume_id'] = $resume_id;
                $chat_params['person_id'] = $person_id;
                $chat_params['company_id'] = $this->_userid;
                //$downloads[$i]['chat_status'] = $service_chat->getChatNoticeStatus($this->_userid,base_lib_BaseUtils::getCookie('accountid'),$login_status[$person_id],$chat_params);
                //$downloads[$i]['chat_status'] = $service_wangyiaction->checkPersonIsOnline($person_id);
                $downloads[$i]['chat_status'] = !empty($login_status[$person_id]) ? true : false;
            }

            //分页
            $this->_aParams['pager'] = $this->pageBar($result->totalSize, $page_size, $cur_page, $inPath);

            // 下载的简历列表
            $this->_aParams['downresumes'] = $downloads;
        }

        $this->_aParams['tag_id']            = $tag_id;
        $this->_aParams['down_time']         = $down_time;
        $this->_aParams['keyword']           = $keyword;
        $this->_aParams['hasdownload']       = ($totalCount > 0 ? true: false);
        $this->_aParams['hasFilterdownload'] = (count($downloads) > 0 ? true : false);
        $this->_aParams['downloadTotal']     = $result->totalSize;
        $this->_aParams['companytags']       = json_encode($new_companytags);
        $this->_aParams['accounttags']       = json_encode($new_accounttags);
        $this->_aParams['showfilter']        = false;
        //@update 增加获取时间，获取人 2019-06-06 by pengChengGong
        $this->_aParams['s_time']    = $get_time_start;
        $this->_aParams['e_time']    = $get_time_end;
        $this->_aParams['accountid'] = $down_account_id;


        //获得各种状态下收到的总条数
        $use_job_ids = array();
        $showStopJobApply = base_lib_BaseUtils::getCookie("showStopJobApply"); //判断用户是否包含了 停招招聘的职位
        if (base_lib_BaseUtils::nullOrEmpty($showStopJobApply) || $showStopJobApply!="true") {
            $service_job = new base_service_company_job_job();
            $job_status  = new base_service_common_jobstatus();

            $use_job_list = $service_job->getJobList($this->_userid, null, $job_status->pub, 'job_id');
            $use_job_ids  = $this->_buildIDs($use_job_list, "job_id");
            $use_job_ids  = !empty($use_job_ids) ? explode(",", $use_job_ids) : array();
        }

        $service_apply = new base_service_company_resume_apply();
        $apply_status_count = $service_apply->getStatusGroupCount($this->_userid, $use_job_ids);
        $this->_aParams['apply_status_count'] = $apply_status_count->items;
        if (!base_lib_BaseUtils::nullOrEmpty($tag_id) || !base_lib_BaseUtils::nullOrEmpty($down_time) || !base_lib_BaseUtils::nullOrEmpty($keyword)) {
            $this->_aParams['showfilter'] = true;
        }
        $service_question = new base_service_company_question();

        if($service_question->canAnswer($this->_userid)){
            $this->_aParams['is_question'] = 1;
        }
        $xml = SXML::load('../config/config.xml');
        $this->_aParams['title'] = "下载的简历 简历管理_{$xml->HuiBoSiteName}";
        return $this->render('resume/download/list_v2_gray.html', $this->_aParams);
    }

	/**
     * 简历管理 >> 下载的简历 >> 删除简历
     * @param  mixed $inPath 链接参数
     * @return json/html     删除结果
     */
	public function pageDeleteDownload($inPath) {
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $operate   = base_lib_BaseUtils::getStr($path_data['op']); 
        $ids       = base_lib_BaseUtils::getStr($path_data['ids'], 'array', null);
        $ids       = base_lib_BaseUtils::getIntArrayOrString($ids);
	    
        switch ($operate) {
			case 'del':
                if(!$this->canDo("delete_resume")){
                    echo json_encode(array('error' => '无权限访问，没有开通相应权限'));
					return ;
                }
				if (base_lib_BaseUtils::nullOrEmpty($ids) || count($ids) == 0) {
					echo json_encode(array('error' => '请选择你要删除的简历'));
					return ;
				}

			   	$service_download = new base_service_company_resume_download();	
				$result = $service_download->delDownload(implode(",",$ids), $this->_userid);
				echo json_encode(array('success' => '1'));				
				return;
			default:
                if(!$this->canDo("delete_resume")){
                    $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
                    $this->_aParams["url"] = "/";
                    return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
                }
	    		// 提示
	    		$names =  explode(',',urldecode(base_lib_BaseUtils::getStr($path_data['names'])));
                $this->_aParams['names'] = $names;
                $this->_aParams['ids']   = implode(",",$ids);
	    		return  $this->render('resume/download/delete.html', $this->_aParams);
        }
	}    

    /**
     * 简历管理 >> 获取联系方式 - 扣点提示
     * @param  mixed $inPath 链接参数
     * @return html          resume/down/deductcountprompt.html
     */
    public function pageDeductCountPrompt($inPath) {
        if(!$this->canDo("download_resume")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
        }

        $params       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resumeid     = base_lib_BaseUtils::getStr($params['resumeid'], 'int', null); 
        $recommend_id = base_lib_BaseUtils::getStr($params['recommendid'], 'int', null); 
        
        if (!base_lib_BaseUtils::nullOrEmpty($recommend_id)) {
            $this->_aParams['recommend_id'] = $recommend_id;
        }

        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,base_lib_BaseUtils::getCookie('accountid'));
        list($status, $code, $params) = $company_resources->check($func="download", $params=['resume_id'=>$resumeid]);
        $pricing_resource_data = $company_resources->getCompanyServiceSource(["_account_resource"]); //获取套餐资源

        $this->_aParams['isCqNewService'] = $pricing_resource_data['isCqNewService'];

        /* 余额 */
        $account_overage = $pricing_resource_data['account_overage'];
        $params['account_overage'] = $account_overage;
        
        //推广金
        $spread_overage = $pricing_resource_data['spread_overage'];
        $params['spread_overage'] = $spread_overage;

        //体验会员套餐--可使用推广金下载 仅限体验会员 2019/11/7 14:22
        $resource_data     = $company_resources->getCompanyServiceSource(["_account_resource", "company_level"]);
        if(in_array($resource_data['company_level'], array (8))) {
            $this->_aParams['is_spread_overage'] = true;
        }

        $params['status'] = $status;
        $params['code']   = $code;

        $this->_aParams['cq_resume_num_release']       = $pricing_resource_data['cq_resume_num_release']; //剩余简历点

        $this->_aParams['params']       = $params;
        $this->_aParams['account_type'] = $company_resources->isMember() ? "member" : "not_member";

        $servicePricing = new base_service_company_service_servicePricing();
        $account_overage_service_price = $servicePricing->GetFunParallelismSelling('point_dow_resume');

        //是重庆新套餐服务
        if($pricing_resource_data['isCqNewService']){
            //简历点 或 余额 不足
	        //添加 体验会员套餐--可使用推广金下载 仅限体验会员 2019/11/7 14:22
	        if($pricing_resource_data['cq_resume_num_release'] < 1 && $account_overage < $account_overage_service_price) {
		        if(in_array($pricing_resource_data['company_level'], array (8))) {
			        if($spread_overage + $account_overage < $account_overage_service_price) {
				        $this->redirect('/resume/resumeinfomsghtml/?type=1');
			        }
		        }
		        else {
			        $this->redirect('/resume/resumeinfomsghtml/?type=1');
		        }
	        }
        }
        $this->_aParams['account_overage_service_price'] = $account_overage_service_price;

        //如果简历点足够，就弹另一个弹框
        if ($pricing_resource_data['cq_resume_num_release'] > 1) {
            $companyService = new base_service_company_company();
            $company = $companyService->getCompany($this->_userid, true, 'company_id'    //hr会员提醒
            );
            //20191216新增，查询在招职位
            $service_job = new base_service_company_job_job();
            $jobs = $service_job->getJobList($company['company_id'], '', 1, 'station,job_is');
            $this->_aParams['jobs'] = $jobs;
            return $this->render('resume/down/deductbyresumerelease.html', $this->_aParams);
        }
    	return $this->render('resume/down/deductcountprompt.html', $this->_aParams);
    }



    /**
     * todo 新的 简历管理 >> 扣点提示
     * @desc  新增于2018-5-10，pc端弹窗提示改版
     * @param  mixed $inPath 链接参数
     * @return html          resume/down/deductcountpromptnew.html
     */
    public function pageDeductCountPromptNew($inPath) {
        if(!$this->canDo("download_resume")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
        }
        $params       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resumeid     = base_lib_BaseUtils::getStr($params['resumeid'], 'int', null);
        $recommend_id = base_lib_BaseUtils::getStr($params['recommendid'], 'int', null);

        if (!base_lib_BaseUtils::nullOrEmpty($recommend_id)) {
            $this->_aParams['recommend_id'] = $recommend_id;
        }


        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,base_lib_BaseUtils::getCookie('accountid'));
        list($status, $code, $params) = $company_resources->check($func="download", $params=['resume_id'=>$resumeid]);

        $params['status'] = $status;
        $params['code']   = $code;

        $this->_aParams['params']       = $params;
        $this->_aParams['account_type'] = $company_resources->isMember() ? "member" : "not_member";

        $companyresources = $company_resources->getCompanyServiceSource();
        $this->_aParams['companyresources'] = $companyresources;

        $this->_aParams['no_price'] = false;
        return $this->render('resume/down/deductcountpromptnew.html', $this->_aParams);
    }

	/**
     * 简历管理 >> 下载的简历 >> 下载简历
     * @param  mixed $inPath 链接参数
     * @return html          resume/download/down.html
     */
	public function pageDownLoad($inPath) {
        if(!$this->canDo("see_resume_info")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
        }
        $params     = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_ids = base_lib_BaseUtils::getStr($params['resumeid'],'array',null);
        $resume_ids = base_lib_BaseUtils::getIntArrayOrString($resume_ids);
		
        
        $this->_aParams['resumeids'] = implode(",", $resume_ids);
		return $this->render('resume/download/down.html', $this->_aParams);	
	}    

    /**
     * 简历管理 >> 获取简历联系方式
     * @param  mixed $inPath 链接参数
     * @return html/json
     */
    public function pageGetLinkWay($inPath) {
        if(!$this->canDo("download_resume")){
            echo json_encode($json_err=["error"=>"无权限访问，没有开通相应权限"]);
            exit;
        }
        $path_data  = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_id  = base_lib_BaseUtils::getStr($path_data['resumeID'],'int',0);
        $visit_id  = base_lib_BaseUtils::getStr($path_data['visit_id'],'int',0);
        $company_id = $this->_userid;
        $point     = base_lib_BaseUtils::getStr($path_data['point'],'int',0);
        $job_id     = base_lib_BaseUtils::getStr($path_data['job_id'],'int',0);

        //获取联系方式
        $service_resume = new base_service_person_resume_resume();
        $person_service = new base_service_person_person();
        $resume = $service_resume->getResume($resume_id, "person_id,resume_id,relate_resume_id,point");
        if ($resume == null) {
            $json_arr = array("error" => "对不起，该简历不存在");
            echo json_encode($json_arr);
            return ;
        }

        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,base_lib_BaseUtils::getCookie('accountid'));
        $is_open = $person_service->getPerson($resume['person_id'],'open_mode')['open_mode'];

        //判断简历是否是采集的简历
        $is_gather = false;
        if(!$is_open){
            $service_recommend  = new base_service_company_resume_recommend();
            $recommend_list     = $service_recommend->getRecommendInfoByResumeIds($company_id,$resume_id, "resume_id,recommend_id,recommend_type,is_read");
            $recommend_list     = base_lib_BaseUtils::array_key_assoc($recommend_list, "resume_id");
            $recommend_info     = $recommend_list[$resume_id];
            if(!empty($recommend_info) && $recommend_info["recommend_type"] == 3){
                $is_gather = true;
            }
        }
        
        /* 如果该简历未投递/简历未公开 */
        $service_apply       = new base_service_company_resume_apply();
        if (!$service_apply->isApply($company_resources->all_accounts, $resume_id) && $is_open !=1 && !$is_gather){
            $json_arr = array("error" => "对不起，该简历未投递或者简历未公开");
            echo json_encode($json_arr);
            return ;
        }

        //判断是否获取过改简历

        $is_cq_setmeal = $company_resources->getCompanyServiceSource(["account_resource","company_level"]); //获取套餐资源

        /* 余额 */
        $account_overage = $is_cq_setmeal['account_overage'];
        /* 推广金 */
	    $spread_overage = $is_cq_setmeal['spread_overage'];

        //余额扣除点数
        $servicePricing = new base_service_company_service_servicePricing();
        $account_overage_service_price = $servicePricing->GetFunParallelismSelling('point_dow_resume');

        $params = [
            'resume_id' => $resume_id,
            'ip'        => base_lib_BaseUtils::getIp(0),
            'is_invite' => 0
        ];
	
	    //添加 体验会员套餐--可使用推广金下载 仅限体验会员 2019/11/7 14:22
	    if($is_cq_setmeal['isCqNewService'] && $is_cq_setmeal['cq_resume_num_release'] < 1 && $account_overage < $account_overage_service_price) {
		    if(in_array($is_cq_setmeal['company_level'], array (8))) {
			    if($spread_overage + $account_overage < $account_overage_service_price) {
				    echo json_encode($json_err=["error"=>"简历点/推广金/余额不足，请联系招聘顾问进行购买！"]);
				    exit;
			    }
		    }
		    else {
			    echo json_encode($json_err=["error"=>"简历点/余额不足，请联系招聘顾问进行购买！"]);
			    exit;
		    }
	    }
	    
        //新套餐的联系方式扣款
        if ($is_cq_setmeal['isCqNewService']){
            //扣取 获取联系方式的金额  且 下载该简历
            $params = $company_resources->consume($func="cq_setmeal_consume",$params=['resume_id'=>$resume_id,'consume_type'=>'get_link']);
            if ($params['code'] != 200) {
                // echo json_encode($json_err=["error"=>"获取联系方式失败，请重新尝试！！"]);
                echo json_encode($json_err=["error"=>$params['msg']]);
                exit;
            }
        }else{
            list($status, $code, $params) = $company_resources->consume($func="download", $params);
            if (!$status) {
                echo json_encode($json_err=["error"=>"获取联系方式失败，请重新尝试！"]);
                exit;
            }
        }


    	//查询在一段时间内，单位下载了多少简历的联系方式，其中多少个应届毕业生的
        //30分钟下载了多少个 1个小时内下载了多少个
        //每10个提醒一次 ，如果是单位被锁定的，给单位的锁定人发一条oa信息     	
    	//go to..


    	$service_person = new base_service_person_person();
    	$person = $service_person->getPerson($resume['person_id'], "user_name,email,telephone,qq,email_is_validation,mobile_phone,mobile_phone_is_validation");
    	
        
        $phone_class = $person['mobile_phone_is_validation'] ? "class='p'" : "class='nop'";
        $email_class = $person['email_is_validation'] ? "class='e'" : "class='noe'";
        $telephone   = empty($person['telephone']) ? "" : "<p class='tipTxt'><em>座机：</em><b>{$person['telephone']}</b></p>";

        if (!base_lib_BaseUtils::nullOrEmpty($person['mobile_phone'])) {
            $phone_html  = "<span><i {$phone_class}></i><strong>{$person['mobile_phone']}</strong></span>";
        }

        if (!base_lib_BaseUtils::nullOrEmpty($person['email'])) {
            $email_html  = "<span><i {$email_class}></i><strong>{$person['email']}</strong></span>";
        }
            
        if (!base_lib_BaseUtils::nullOrEmpty($person['qq'])) {
            $qq_html     = "<span class='last'><i class='q'></i><strong>{$person['qq']}</strong></span>";
        }

    	//中文英文 goto ..    	
    	$contactway = "<p class='link'>{$phone_html}{$email_html}{$qq_html}</p>";

        $json_arr["contactway"]                 = $contactway;
        $json_arr["mobile_phone_is_validation"] = $person['mobile_phone_is_validation'];	
        $json_arr["mobile_phone"]               = $person['mobile_phone'];
        $json_arr["telephone"]                  = $person['telephone'];
        $json_arr["email"]                      = $person['email'];
        $json_arr["email_is_validation"]        = $person['email_is_validation'];
        $json_arr["qq"]                         = $person['qq'];

        //---------通过访问记录下载简历的记录-----------
        if(!empty($visit_id)){
            $visitdownData = array();
            $visitdownData['resume_id'] = $resume_id;
            $visitdownData['company_id'] = $company_id;
            $visitdownData['visit_id'] = $visit_id;
            $visitdown_service = new base_service_company_job_companyjobvisitdownload();
            $visitdown_service->addData($visitdownData);
        }

        //---------添加操作日志--------
        $service_resume = new base_service_person_resume_resume();
        $resume = $service_resume->getResume($resume_id, "resume_id,person_id");
        $service_persons = new base_service_person_person();
        $resume_infos = $service_persons->getPerson($resume['person_id'],'person_id,user_name');
        $common_oper_type = new base_service_common_account_accountoperatetype();
        $service_oper_log = new base_service_company_companyaccountlog();
        $common_oper_src_type = new base_service_common_account_accountlogfrom();
        $insertItems=array(
            "company_id"=>$this->_userid,
            "source"=>$common_oper_src_type->website,
            "account_id"=>base_lib_BaseUtils::getCookie('accountid'),
            "operate_type"=>$common_oper_type->resume_down,
            "content"=>"下载了'".$resume_infos['user_name']."'的简历",
            "create_time"=>date("Y-m-d H:i:s",time())
        );
        $service_oper_log->addLogToMongo($insertItems);
        //保存动作类型
        //保存登录动作
        //动作类型
        $service_actiontype = new base_service_common_actiontype();
        //用户类型
        $service_actionusertype = new base_service_common_actionusertype();
        //渠道
        $service_actionsource = new base_service_common_actionsource();
        base_lib_BaseUtils::saveAction($service_actiontype->downloadresume, $service_actionsource->website, $this->_userid, $service_actionusertype->company);

        //如果有职位，就发送聊一聊消息
        if (!empty($job_id)) {
            $service_qqcloud_msg = new base_service_interface_qqcloudsendmsg();
            $qq_cloud_content    = '您好，您的简历与我们的岗位需求非常匹配，请问方便交流下吗？';
            $from_id             = base_lib_BaseUtils::getCookie('accountid');
            $service_qqcloud_msg->addPreSendMsg("CI", $from_id,$resume['person_id'], $qq_cloud_content, $resume_id, $job_id);
        }

        //-------------END------------
    	echo json_encode($json_arr);
    	return;
    }

    /**
     * 简历管理 >> 检查余额
     * @param  array $inPath  url参数集
     * @return json           json返回数据
     */
    public function pageCheckBalance($inPath) {
        $path_data  = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_id  = base_lib_BaseUtils::getStr($path_data['resume_id'], 'int', 0);
        $check_type  = base_lib_BaseUtils::getStr($path_data['check_type'], 'string', '');

        //如果余额（简历点，推广金，现金余额）不足，是否返回弹窗
        $get_alert  = base_lib_BaseUtils::getStr($path_data['get_alert'], 'bool', false);

        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,base_lib_BaseUtils::getCookie('accountid'));
        list($status, $code, $params) = $company_resources->check($func="download", $params=['resume_id'=>$resume_id]);

        //简历期望薪资
        if(!$status && $code == base_service_company_resources_code::RESUME_EXP_SALARY){
            $data = [
                'status'        => $status,
                'code'          => $code,
                'msg'           => $params['msg']
            ];
            echo json_encode($data);
            exit;
        }

        $pricing_resource_data = $company_resources->getCompanyServiceSource(['account_resource', 'company_level']);
        $account_overage = $pricing_resource_data['account_overage'];  /* 余额 */
	    
	    //推广金
	    $spread_overage = $pricing_resource_data['spread_overage'];

        //余额扣除点数
        $servicePricing = new base_service_company_service_servicePricing();
        $account_overage_service_price = $servicePricing->GetFunParallelismSelling('point_dow_resume');

        $params['account_overage'] = $account_overage;

        $params['status'] = $status;
        $params['code']   = $code;

        $this->_aParams['no_price'] = true;
        $this->_aParams['params'] = $params;
        $params['cq_resume_num_release'] = $pricing_resource_data['resume_num_release']; //简历点数量
        $params['account_overage_service_price'] = $account_overage_service_price; //余额扣除点数

        //面试邀请的不验证余额
//        if($check_type == 'invite'){
//            $params['status'] = true;
//            echo json_encode($params);
//            exit;
//        }
	    
	    //添加 体验会员套餐--可使用推广金下载 仅限体验会员 2019/11/7 14:22
	    if(!empty($params['isCqNewService']) && $account_overage < $account_overage_service_price && !$status) {
            if(in_array($pricing_resource_data['company_level'], array (8))) {
                if($spread_overage + $account_overage < $account_overage_service_price) {
                    $params['status'] = false;
			    }
		    }
		    else {
			    $params['status'] = false;
		    }
	    }

        //返回余额不足的弹窗信息
        if($pricing_resource_data['resource_type'] == 2 && !$params['status'] && $code != 2){
            if($pricing_resource_data['isCqNewService']){
                $params['msg'] = "您的简历点不足，请联系主账号为您分配更多资源。";
            }
            if($pricing_resource_data['isNewService']){
                $params['msg'] = "您的简历点/推广金不足，请联系主账号为您分配更多资源。";
            }
            $params['code'] = 502;

            echo json_encode($params);
            exit;
        }
        
        //区县套餐下载主城简历判断,套餐为区县下载的简历为主城简历，并且套餐的主城简历数做了限制，则需要判断是否能够下载主城简历
        if(!$status && $code == base_service_company_resources_code::DOWNLOAD_NOT_RESTRICT){
            $companyStateService = new base_service_company_comstate();
            $companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
            $net_heap_id = $companyState["net_heap_id"];
            $hrManager = $this->GetHRManager($net_heap_id);
            $service_company = new base_service_company_company();
            $site_type = $service_company->getCompany($this->_userid, 1, 'site_type')['site_type'];
            $mobile_phone = $site_type == 5 ? '18523192707' : $hrManager['mobile'];

            $params['status'] = false;
            $params['code'] = 502;
            $params['msg'] = "当前套餐已达获取主城简历的最大额度，继续获取请联系招聘顾问{$mobile_phone}(微信同号)";
            echo json_encode($params);
            exit;
        }

        if($get_alert && !$status && $code != 2){
            $params['alert_info'] = $this->render('resume/down/deductcountpromptnew.html', $this->_aParams);
        }

        //判断是否有体验券
        $accountid = base_lib_BaseUtils::getCookie('accountid');
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
        $company_level_data = $company_resources->getCompanyServiceSource(['company_level']);
        //添加是否存在待使用优惠劵 已进行企业资料验证；已通过营业执照验证；非在用会员
        $letter_info = $company_resources->getCompanyAuditStatusV2();
        $licence_audit_type = $letter_info['licence_audit_type'];
        $letter_audit_type = $letter_info['letter_audit_type'];
        if (in_array($licence_audit_type, [1, 4, 5]) && in_array($letter_audit_type, [1, 4]) && $company_level_data['company_level'] == 0) {
            $ser_allotCouponPricing = new base_service_company_coupon_allotCouponPricing();
            $exist_where = [
                'allot_state'   => 2,
                'state'         => 1,
                'validity_date' => $this->_ymd,
            ];
            $allotCouponPricing_data = $ser_allotCouponPricing->getAllotCouponPricingByCompanyID($this->_userid, $exist_where);
            if ($allotCouponPricing_data && $allotCouponPricing_data['id']) {
                $params['have_pricing'] = 1;
            }
        }

        echo json_encode($params);
        exit;
    }

    private function GetHRManager($heap_id) {
        $companyHeapService = new base_service_company_netheap();
        $companyHeap = $companyHeapService->GetNetHeapByID($heap_id, "own_man");
        $userInfor = null;
        if (is_null($companyHeap) || !isset($companyHeap["own_man"])) {
            return $userInfor;
        }
        $userService = new base_service_crm_user();
        $userInfor = $userService->GetUsers($companyHeap['own_man'], "user_name,head_photo_url,tel,mobile,qq");

        return $userInfor;
    }

    /**
     * 简历管理 >> 检查获取联系方式的余额是否足够
     * @param  array $inPath  url参数集
     * @return json           json返回数据
     */
    public function pageCheckLinkWay($inPath) {

        $path_data  = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_id  = base_lib_BaseUtils::getStr($path_data['resume_id'], 'int', 0);

        //如果余额（简历点，推广金，现金余额）不足，是否返回弹窗
        $get_alert  = base_lib_BaseUtils::getStr($path_data['get_alert'], 'bool', false);

        
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,base_lib_BaseUtils::getCookie('accountid'));
        $resource_data = $company_resources->getCompanyServiceSource(["account_resource"]);
        $spread_overage = $resource_data['spread_overage'];
        $account_overage = $resource_data['account_overage'];
        list($status, $code, $params) = $company_resources->check($func="download", $params=['resume_id'=>$resume_id]);

        //简历期望薪资
        if(!$status && $code == base_service_company_resources_code::RESUME_EXP_SALARY){
            $data = [
                'status'        => $status,
                'code'          => $code,
                'msg'           => $params['msg']
            ];
            echo json_encode($data);
            exit;
        }

        $params['status'] = $status;
        $params['code']   = $code;

        $this->_aParams['no_price'] = true;
        $this->_aParams['params'] = $params;

        $service_cashaccount = new base_service_company_charge_companycashaccount();

        //余额扣除点数
        $servicePricing = new base_service_company_service_servicePricing();
        $account_overage_service_price = $servicePricing->GetFunParallelismSelling('point_dow_resume');

        //简历点足够提示“获取联系方式将扣除1个简历点”，若简历点不足余额足够提示“获取联系方式将扣除10元账户余额”，余额不足则提示去充值
        $params['cq_resume_num_release'] = $resource_data['cq_resume_num_release']; //简历点数量
        $params['account_overage_service_price'] = $account_overage_service_price; //余额扣除点数

        if (!empty($resource_data['isCqNewService']) && ($spread_overage <= 0 && $account_overage < $account_overage_service_price) && $code != base_service_company_resources_code::DOWNLOAD_USE_POINT ){
            $params['status'] = false;
            $params['msg'] = "您的简历点/推广金不足";
        }
        if($resource_data['resource_type'] == 2 && !$params['status'] && $code != 2){
            if($resource_data['isCqNewService']){
                $params['msg'] = "您的简历点不足，请联系主账号为您分配更多资源。";
            }
            if($resource_data['isNewService']){
                $params['msg'] = "您的简历点/推广金不足，请联系主账号为您分配更多资源。";
            }
            $params['code'] = 502;

            echo json_encode($params);
            exit;
        }

        if ($params['isCqNewService']){
            $pricing_resource_data = $company_resources->getCompanyServiceSource(["cq_pricing_resource"]); //获取套餐资源
            $array = array('status'=>false , 'code'=> '400' , 'msg'=> '余额不足','isCqNewService'=>true);

            //检测简历点是否充足
            if($pricing_resource_data['cq_resume_num_release'] >= 1){
                $array = array('status'=>true , 'code'=> '200' , 'msg'=> '获取联系方式将扣除1个简历点','isCqNewService'=>true);
            }elseif($account_overage >= $account_overage_service_price){ //检测余额是否足够
                $array = array('status'=>true , 'code'=> '201' , 'msg'=> '获取联系方式将扣除'.$account_overage_service_price.'元账户余额','isCqNewService'=>true);
            }
            echo json_encode($array);die;
        }

        echo json_encode($params);
        exit;
    }

    //聊一聊判断
    public function pageCheckBalanceV2($inPath) {
        $path_data  = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_id  = base_lib_BaseUtils::getStr($path_data['resume_id'], 'int', 0);
        //获取该用户的套餐情况

        $accountid = base_lib_BaseUtils::getCookie('accountid');
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
        $pricing_resource_data = $company_resources->getCompanyServiceSource(['account_resource']);
        list($status, $code, $params) = $company_resources->check($func="download", $params=['resume_id'=>$resume_id]);

        $params['status'] = $status;
        $params['code']   = $code;
        //点击聊一聊按钮，判定需要消耗聊一聊次数时：
        //a. 次数足够提示消耗聊一聊次数，
        //b. 次数不足，推广金+余额足够提示消耗推广金，
        //c. 刷新点/推广金/余额都不够时，提示余额不足
        $service_consolelog = new base_service_company_service_serviceConsumeLog();
        $service_person_resume_resume = new base_service_person_resume_resume();
        $resumes					  = $service_person_resume_resume->getResume($resume_id,'person_id,resume_id');//简历对应的用户



        // 0:不提示，1：(未登录)提示, 2：（未登录）提示 + 提示消耗  3：提示消耗
        $service_chat = new company_service_chat(0,0);
        $chat_params['resume_id'] = $resume_id;
        $chat_params['person_id'] = $resumes['person_id'];
        $chat_params['company_id'] = $params['company_id'];
        $remain_status = $service_chat->getChatNoticeStatus($this->_userid,$accountid,null,$chat_params, $chat_params['person_id']);



        //判断是否需要弹框提示
        $retData = array();
        //该企业跟该求职者3个月内聊过天,不扣除点数
        $is_chat = $service_consolelog->checkConsumeChatByCompanyIdAndPersonId($this->_userid,$resumes['person_id']);
        //聊过天
        if($is_chat){
            $retData = array('status'=> '200', 'remain_status'=>$remain_status, 'msg' => '该企业跟该求职者3个月内聊过天,不扣除点数','data'=>$resumes);
        }else{ //未聊过天，判断扣费标准是否满足

            $retData = array('status'=> '400', 'remain_status'=>$remain_status, 'msg' => '需要消耗聊一聊次数/推广金，您的余额不足','data'=>$resumes);



            //聊一聊推广金换算
            $service_company_service_servicePricing = new base_service_company_service_servicePricing();
            $params['point_chat_fun']                   = $service_company_service_servicePricing->GetFunParallelismSelling('point_chat');
            /* 推广金 */

            $spread = $pricing_resource_data['spread_overage'];            /* 余额 */


            
     		$account_overage = $pricing_resource_data['account_overage'];
            $total_temp = $spread + $account_overage;
            $total = sprintf("%.2f", $total_temp);
            //次数聊天次数是否足够，
            if($pricing_resource_data['cq_release_point_chat'] >= 1){
                $retData = array('status'=> '401', 'remain_status'=>$remain_status, 'msg' => '需要消耗1次聊一聊次数，确定继续吗？','data'=>$resumes);
            }else if($total >= $params['point_chat_fun']){ //判断余额是否充足
                $retData = array('status'=> '402', 'remain_status'=>$remain_status,  'msg' => '需要消耗'.$params['point_chat_fun'].'元推广金，确定继续吗？','data'=>$resumes);
            }
        }
        return json_encode($retData);
        //a. 次数足够提示消耗聊一聊次数 ，

        //该企业跟该求职者3个月内聊过天,不扣除点数
//        $is_chat = $service_consolelog->checkConsumeChatByCompanyIdAndPersonId($this->_userid,$resumes['person_id']);
//        if($is_chat){
//            $params['is_chat'] = 0;
//        }else{
//            $params['is_chat'] = 1;
//        }

//        $params['person_id'] = $resumes['person_id'];
//
//        $params['point_chat'] = $pricing_resource_data['cq_release_point_chat']; //剩余聊天数
//
//        //聊一聊推广金换算
//        $service_company_service_servicePricing = new base_service_company_service_servicePricing();
//        $params['point_chat_fun']                   = $service_company_service_servicePricing->GetFunParallelismSelling('point_chat');
//
//        /* 推广金 */
//        $service_spread = new base_service_company_spread_spread();
//        $spread = $service_spread->getEffectConsume($this->_userid);
//        /* 余额 */
//        $service_cashaccount = new base_service_company_charge_companycashaccount();
//        $account_overage = $service_cashaccount->getCompanyAccount($this->_userid);
//
//        $params['is_pay'] = 0;
//        if ($params['point_chat_fun'] >(($spread['count'] - $spread['used']) + $account_overage)){
//            $params['is_pay'] = 1;
//        }

//        echo json_encode($params);
//        exit;
    }
    /**
     * 确认新套餐扣费-聊一聊扣费
     * */
    public function pageSetmealChat($inPath){
        $path_data  = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_id  = base_lib_BaseUtils::getStr($path_data['resume_id'], 'int', 0);
        $person_id  = base_lib_BaseUtils::getStr($path_data['person_id'], 'int', 0);
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid);
//        $this->_aParams['pricing_resource_data'] = $company_resources->getCompanyServiceSource(["cq_pricing_resource"]); //获取套餐资源
        $vd = $company_resources->consume($func="cq_setmeal_consume", $params=['resume_id'=>$resume_id,'consume_type'=>'chat', 'is_invite'=>1,'person_id'=>$person_id]);
        return json_encode($vd);
    }

    private function _buildArray($arr, $filer="resume_id") {
   		//if($arr.length<=0) return array();
   		if(!is_array($arr) || count($arr)<=0) return $arr;   		
    	foreach ($arr as $key=>$value){
			$new_arr[$value["$filer"]] = $value; 
		}
		return $new_arr;
    }

    private function _buildIDs($arr, $filer="resume_id") {
    	if (!is_array($arr) || count($arr) <= 0)
            return $arr; 
    	
        foreach ($arr as $key => $value) {
    		$newArr[] = $value["$filer"];
    	}
    	return implode(',', $newArr);
    }

    /**
     * 数组查询
     * @param array $arr
     * @param string $key
     * @param string $value
     */
    private function arrayFind($arr, $property, $value) {
        $obj = null;
        foreach ($arr as $item) {
       	    if ($item[$property] == $value) {
                $obj =  $item;
                break;
            }
        }
        return $obj;
    }

    /**
      * 获取学历
      */
    private function getDegree($degree_id, $default='') {
        if (empty($degree_id)) {
        	return $default;
        }
        
        $service_degree = new base_service_common_degree();
        return $service_degree->getDegree($degree_id);
    }

    /**
     * 获取指定的hr与person 在指定天数内的聊天记录，默认90天
     * @param $inPath
     * @return mixed|string
     */
    public function pageGetHrPersonChatHistory($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_id = base_lib_BaseUtils::getStr($pathdata['resume_id'], 'int', 0);
        if (empty($resume_id)) {
            return $this->jsonMsg(false, '求职者简历获取失败');
        }
        $service_resume = new base_service_person_resume_resume();
        $resume = $service_resume->getResume($resume_id, 'person_id');
        if (empty($resume['person_id'])) {
            return $this->jsonMsg(true, '简历不存在');
        }
        $person_id = $resume['person_id'];
        $accountid = base_lib_BaseUtils::getCookie('accountid');
        $days = base_lib_BaseUtils::getStr($pathdata['days'], 'int', 90);
        $params = array(
            'p_hr' => $person_id . '_' . $accountid,
            'start_time' => time() - $days*24*3600
        );
        $service_rong_chat_record = new base_service_rong_rongchatrecord();
        $record = $service_rong_chat_record->getOneRecord($params, 'msg_content,station,job_id');
        if ($record) {
            return $this->jsonMsg(true, '3个月内与我聊过', array('job_id' => $record['job_id']));
        }
        return $this->jsonMsg(false, '3个月内没有聊过');
    }
}