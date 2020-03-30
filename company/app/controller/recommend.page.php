<?php
/**
 * @copyright 2002-2013 www.huibo.com
 * @name 推荐的简历
 * @author ZhangYu
 * @version 2014-02-13
*/
class controller_recommend extends components_cbasepage {
	
    private $_reommend_type = 0;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * 收到的简历列表
	 * @param  $inPath
	 */
	public function pageIndex($inPath) {
        if(!$this->canDo("resume_manage")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        //该企业是否需要使用新功能（是否参与灰度测试
        $is_gray = $this->is_gray_company;
        if($is_gray){
            //灰度测试单位跳转新方法
            $this->redirect_url("/recommend/grayIndex", $params);
        }

        // 参数信息:  职位编号，投递时间，状态，姓名/简历编号，显示方式（列表/摘要）  
        $job_id = base_lib_BaseUtils::getStr($params['job_id'],'int',null);
        $status = base_lib_BaseUtils::getStr($params['status'],'int',null);
        $cur_page = base_lib_BaseUtils::getStr($params['page'],'int',1);
	    $page_size =base_lib_BaseUtils::getStr($params['pageSize'],'int',base_lib_Constant::PAGE_SIZE);
        $type = base_lib_BaseUtils::getStr($params['type'],'int',1);
        $is_ajax = base_lib_BaseUtils::getStr($params['is_ajax'],'int',0);

        $this->_aParams['type'] = $type;


        // 获取是否为HR会员
        $account_id = base_lib_BaseUtils::getCookie('accountid');
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $account_id);
        // 是否为会员
        $memberinfo = $company_resources->isMember() ? "member" : "not_member";
        $this->_aParams['memberinfo'] = $memberinfo;
        //非会员只能看自动推荐简历
        if($memberinfo == 'not_member'){
            $type = 1;
        }

        $service_recommend = new base_service_company_resume_recommend();
        $service_job = new base_service_company_job_job();

        if(empty($job_id)) {
            $station = base_lib_BaseUtils::getStr($params['job_id'],'string',null);
        }
        $is_read = null;
        $this->_aParams['status'] = $status;
        if($status ==99){
            $is_read = false; //未读
            $status =null;
        }
        //推荐简历的状态 已读 未读 合适 不合适 未处理
        //获得已读未读状态
        $status_array = array();
        //添加未读 与 为处理
        // $status_array[] =  array('id'=>"",'name' =>"不限");
        $status_array[] =  array('id'=>"99",'name' =>"未读");
        $status_array[] =  array('id'=>"0",'name' =>"未处理");
        $service_recommend_status = new base_service_common_recommendstatus();
        $recommend_status_array = $service_recommend_status->getRecommendStatus();
        foreach($recommend_status_array as $k=>$recommend_status){
            $status_array[] = array('id'=>$k,'name' => $recommend_status);
        }
        $this->_aParams['status_array'] = $status_array;

        $service_apply = new base_service_company_resume_apply();

        // 获取未处理推荐的列表
        $recommend_temp_list = $service_recommend->getRecommendList(20,1,$this->_userid,'recommend_id,resume_id,job_id,is_read,status,recommend_time,station,recommend_man,recommend_type',null, null, null, null,null,0);
        //自动推荐
//        $recommend_resume_num = count($recommend_temp_list->items);
        $recommend_resume_num = $service_recommend->hasNotDealRecommend($this->_userid); 
        if($type == 1){
            $resume_list = array();
            $default_data = $this->getDefaultJob();
            $this->_aParams['job_list'] = $default_data['job_list'];
            $job_id =  empty($job_id) ? $default_data['default_job_id'] : $job_id;
            $new_resume_list = [];
            if($job_id) {
                $service_company_job_job = new base_service_company_job_job();
                $job_info = $service_company_job_job->getJob($job_id, "job_id,station,jobsort");
                $this->_aParams['job_info'] = $job_info;
                $this->_aParams['default_job_id'] = $job_id;
                $solr_resume = new base_service_solr_resume();
                //换一换
                $auto_recommend_resumeids = base_lib_BaseUtils::getStr($params['auto_recommend_resumeids'],'string',null);

                $fresh = base_lib_BaseUtils::getStr($params['fresh'],'int','');
                $page = base_lib_BaseUtils::getStr($params['page'],'int',1);
                if($fresh && $page < 2) {
                    $page = 2;
                }
                $auto_recommend_resumeids = empty($auto_recommend_resumeids) ? [] : explode(',',$auto_recommend_resumeids);
                $resume_ids = $solr_resume->resumeRecommendByJob($this->_userid, $job_id, $page, $auto_recommend_resumeids, 20);
                if($fresh){
                    if($page < 2){
                        $page = 2;
                    }
                    $page += 1;
                }
                $next_page = $resume_ids['next_page'];
                if(!$next_page && $page > 1){
                    $page = 1;
                    $next_page = true;
                }
                $this->_aParams['auto_recommend_resumeids'] = empty($auto_recommend_resumeids) ? implode(',',$resume_ids['resume_ids']) : implode(',',array_merge($auto_recommend_resumeids,$resume_ids['resume_ids']));
                $this->_aParams['next_page'] = $next_page;
                $this->_aParams['page'] = $page;
                //获取简历
                $service_person_resume_resume = new base_service_person_resume_resume();
                $service_person_person = new base_service_person_person();
                $service_edu = new base_service_person_resume_edu();
                $service_person_resume_work = new base_service_person_resume_work();
                $serviceArea = new base_service_company_service_serviceArea();

                if ($resume_ids['resume_ids']) {
                    $resume_ids_str = implode(',', $resume_ids['resume_ids']);
                    $field = "resume_id,person_id,user_name,sex,birthday,cur_area_id,degree_id,major_desc,work_year,station,appraise";

                    $resume_list = $service_person_resume_resume->getResumes($resume_ids_str, $field)->items;

                    $person_ids = base_lib_BaseUtils::getPropertys($resume_list, "person_id");
                    
                    $service_qloudmsg = new base_service_app_qcloudmsg();
                    $chat_list        = $service_qloudmsg->getReplyTimes($person_ids);
                    $chat_list        = base_lib_BaseUtils::array_key_assoc($chat_list, "person_id");
                    
                    $person_ids = implode(',', $person_ids);
                    //求职信息
                    $person_list = $service_person_person->GetPersonListByIDs($person_ids, 'person_id,user_name,name_open,photo_open,sex,birthday2,cur_area_id,start_work,photo,small_photo,mobile_phone,job_state_id,accession_time')->items;
                    $person_list = base_lib_BaseUtils::array_key_assoc($person_list, "person_id");

                    //教育
                    $edu_data = $service_edu->getResumeEdus($resume_ids_str, 'resume_id,school,major_desc,degree')->items;
                    //工作经验
                    $work_datas = $service_person_resume_work->getResumeWorks($resume_ids_str, 'work_id,resume_id,start_time,end_time,station,company_name,work_content')->items;


                    /*获得下载的简历记录*/
                    $download_service = new base_service_company_resume_download();
                    $download_list = $download_service->queryDownloadList($resume_ids_str, $this->_userid, "resume_id,download_id")->items;
                    $resume_downloadids = base_lib_BaseUtils::getPropertys($download_list, "resume_id");

                    $service_apply = new base_service_company_resume_apply();
                    $has_apply_resume = $service_apply->getApplyByResumeId($resume_ids_str, $this->_userid, true);
                    $has_apply_resume_ids = base_lib_BaseUtils::getProperty($has_apply_resume, 'resume_id');
                    $has_apply_resume_ids = array_unique($has_apply_resume_ids);
                    $service_company_resume_resumevisit = new base_service_company_resume_resumevisit();
                    $company_resources_info = $company_resources->getCompanyAuditStatusV2();
                    $isshowresumeinfo = true;
                    $letter_info = $this->CheckCompanyLetter($this->_userid);

                    if($company_resources_info['audit_type'] == 1){
                        //老规则
                        $isshowresumeinfo = true;
                    }else{
                        //新规则
                        if($letter_info['code'] != 200){
                            $isshowresumeinfo = false;
                        }
                    }

                    //求职者一周内是否登录过app
                    $sercie_loginlog= new base_service_person_loginlog();
                    $login_status = $sercie_loginlog->getLoginAppData($person_ids,14);
                    if(!empty($login_status))
                        $login_status = base_lib_BaseUtils::array_key_assoc($login_status,'person_id');
                    //$service_chat = new company_service_chat(0,0);
                    //$service_wangyiaction = new base_service_app_wangyiaction();
                    //判断这些person_ids对应的网易云账户是否在线
                    //$wy_person_status_arr = $service_wangyiaction->checkPersonsIsOnline($person_ids);
                    //意向职位类别
                    $jobsort_service = new base_service_person_resume_jobsortexp();
                    $jobsort_common = new base_service_common_jobsort();
                    $jobsorts = $jobsort_service->getJobsortByResumeIds($resume_ids['resume_ids']);
                    if(!empty($jobsorts))
                        $jobsorts = base_lib_BaseUtils::array_key_assoc($jobsorts,'resume_id');


                    foreach ($resume_list as $key => &$value) {
                        $person_info = $person_list[$value['person_id']];

                        $is_show_name = false; //是否显示简历的名字
                        $is_show_linkway = false;//是否显示联系方式  其中有是否下载
                        $member_info = false;//是否是会员
                        $member_expires = false;//会员是否过期
                        $is_show_resumeinfo = false;//是否显示详细信息

                        $result = $this->checkResume($value['resume_id'], $is_show_name, $is_show_linkway, $member_info, $member_expires,$is_show_resumeinfo);

                        //姓名
                        if ($is_show_name && $isshowresumeinfo) {
                            $value['user_name'] = $person_info['user_name'];
                        } else {
                            $sex_name = $person_info['sex'] == 1 ? '先生' : '女士';
                            $value['user_name'] = mb_substr($person_info['user_name'], 0, 1, 'utf-8') . $sex_name;

                        }

                        if ($person_info['photo_open'] === '0') {//允许null,和1一样，默认可以公开
                            $person_info['photo'] = false;
                            $person_info['small_photo'] = false;
                        } else {
                            if (base_lib_BaseUtils::nullOrEmpty($person_info['small_photo'])) {
                                $person_info['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['photo'];
                            } else {
                                $person_info['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['small_photo'];
                            }
                            //兼容判断
                            if (base_lib_BaseUtils::nullOrEmpty($person_info['photo']))
                                $person_info['photo'] = false;
                            else
                                $person_info['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['photo'];
                        }

                        $value['small_photo'] = $person_info['photo'];
                        $value['sex'] = $this->getSex($person_info['sex']);
                        $value['age'] = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']) . '岁';
                        $value['degree'] = $this->getDegree($value['degree_id']);
                        $value['cur_area'] = $this->getArea($person_info['cur_area_id']);


                        //工作年限
                        $basic_start_work_year = base_lib_TimeUtil::date_diff_month($person_info['start_work']);
                        $workY = floor($basic_start_work_year / 12);
                        $workM = intval($basic_start_work_year % 12);

                        if ($workY <= 0 && $workM <= 6 && $workM >= -6) {
                            $basic_start_work_year = '应届毕业生';
                        } else if ($workY == 0 && $workM > 6) {
                            $basic_start_work_year = $workM . '个月工作经验';
                        } else if ($basic_start_work_year < -6) {
                            $basic_start_work_year = '目前在读';
                        } else {
                            $basic_start_work_year = $workY . '年工作经验';
                        }

                        $value['start_work'] = $basic_start_work_year;
                        if (base_lib_BaseUtils::nullOrEmpty($value['start_work'])) {
                            $value['start_work'] = "应届毕业生";
                        }
                        //工作经历
                        $work_list = array();
                        foreach ($work_datas as $k => $v) {
                            $data = array();
                            if(count($work_list) >= 3){
                                continue;
                            }
                            if ($v['resume_id'] == $value['resume_id']) {
                                $data['start_time'] = date('Y/m', strtotime($v['start_time']));
                                $data['end_time'] = empty($v['end_time']) ? "至今" : date('Y/m', strtotime($v['end_time']));
                                $data['station'] = $v['station'];
                                $data['company_name'] = $v['company_name'];
                                //$data['work_content'] = base_lib_BaseUtils::cutstr($v['work_content'], 180, 'utf-8', '','...');
                            }
                            if ($data) {
                                array_push($work_list, $data);
                            }
                        }
                        $value['work_list'] = $work_list;

                        //教育
                        $edu_info = $this->arrayFind($edu_data, 'resume_id', $value['resume_id']);
                        $value['school'] = $edu_info['school'];
                        $value['major_desc'] = $edu_info['major_desc'];
                        $value['school_degree'] = $this->getDegree($edu_info['degree']);

                        //求职意向
                        $value['job_state_id'] = $person_info['job_state_id'];
                        $value['accession_time'] = $person_info['accession_time'];

                        if (!empty($resume_downloadids) || !empty($has_apply_resume_ids)) {
                            if (in_array($value['resume_id'], $resume_downloadids) || in_array($value['resume_id'], $has_apply_resume_ids)) {
                                $value['has_download'] = true;
                            }
                        }
                        //2周内是否查看简历
                        $is_see_resume = $service_company_resume_resumevisit->getCompanyRecommendResume($this->_userid,$account_id,$value['resume_id']);
                        $value['is_see'] = false;
                        if($is_see_resume){
                            $value['is_see'] = true;
                        }
                        $value['not_area_limit'] = $serviceArea->IsServiceAreaTypeDownloadResumeScope($this->_userid, $value['resume_id']);//限制为false
                        //判断是否活跃
                        $chat_info = $chat_list[$value['person_id']];
                        $value["is_active"] = $chat_info["count"] >= 3 ? true : false;


                        //聊一聊状态 ，提示等级 1：(未登录)提示 2：（未登录）提示 + 提示消耗 3：提示消耗
                        $chat_params['resume_id'] = $value['resume_id'];
                        $chat_params['person_id'] = $value['person_id'];
                        $chat_params['company_id'] = $this->_userid;
                        //$value['chat_status'] = $service_chat->getChatNoticeStatus($this->_userid,base_lib_BaseUtils::getCookie('accountid'),$login_status[$chat_params['person_id']],$chat_params);
                        //$value['chat_status'] =  !empty($wy_person_status_arr[$value['person_id']]) ? $wy_person_status_arr[$value['person_id']] : false;;
                        $value['chat_status'] =  !empty($login_status[$value['person_id']]) ? true : false;;

                        //意向职位类别
                        $_jobsort_res = [];
                        $_jobsorts    = $jobsorts[ $value['resume_id'] ]['jobsort'];
                        if (!empty($_jobsorts)) {
                            $_jobsort_arr = explode(',', $_jobsorts);
                            foreach ($_jobsort_arr as $__jobsort) {
                                $_jobsort_res[] = ['jobsort' => $__jobsort, 'name'    => $jobsort_common->getJobsortName($__jobsort, true)];
                            }
                        }
                        $value['jobsort_arr'] = $_jobsort_res;

                    }
                    $resume_list = base_lib_BaseUtils::array_key_assoc($resume_list,"resume_id");
                    foreach($resume_ids['resume_ids'] as $key => $val){
                        if($resume_list[$val]){
                            array_push($new_resume_list,$resume_list[$val]);
                        }
                    }
                }
            }
            $this->_aParams['auto_job_id'] = $job_id;
            $this->_aParams['resume_list'] = $new_resume_list;

            if($is_ajax){
                echo header("Content-type:text/json;charset=utf-8");
                $ajax_html = $this->render('./ajaxautorecommend.html',$this->_aParams);
                echo json_encode(["page"=>$page, 'auto_recommend_resumeids' => $this->_aParams['auto_recommend_resumeids'], 'count' => count($resume_list), "html"=>$ajax_html]);
                return;
            }

        }else{

            $recommend_list = $service_recommend->getRecommendList($page_size,$cur_page,$this->_userid,'recommend_id,resume_id,job_id,is_read,status,recommend_time,station,recommend_man,recommend_type',$job_id, null, null, $station,$is_read,$status);
            // 职位
            $hasRecommendJobs = $service_recommend->getHasRecommendJobIds($this->_userid);
            $job_ids = $this->getPropertys($hasRecommendJobs->items, 'job_id');
            $jobs = $this->_getJobsAndSort($job_ids, $this->_userid);
            $list = $recommend_list->items;

            if(!base_lib_BaseUtils::nullOrEmpty($list)&& count($list) > 0 ) {
                $person_ids = base_lib_BaseUtils::getPropertys($list, 'person_id');
                //求职者一周内是否登录过app
                $sercie_loginlog= new base_service_person_loginlog();
                $login_status = $sercie_loginlog->getLoginAppData($person_ids,14);
                if(!empty($login_status)){
                    $login_status = base_lib_BaseUtils::array_key_assoc($login_status,'person_id');
                }
                $service_person = new base_service_person_person();
                $service_resume = new base_service_person_resume_resume();
                $service_resume_work = new base_service_person_resume_work();
                $service_resume_remark = new base_service_company_resume_resumeremark();
                // 简历
                $resume_ids = $this->getPropertys($list, 'resume_id');
                //毕业院校
                $service_edu = new base_service_person_resume_edu();
                $edu_data = $service_edu->getResumeEdus(implode(",", $resume_ids),'resume_id,school,major_desc,degree');
                /*获得下载的简历记录*/
                $download_service = new base_service_company_resume_download();
                $download_list = $download_service->queryDownloadList(implode(",", $resume_ids),$this->_userid,"resume_id,download_id")->items;
                $resume_downloadids = base_lib_BaseUtils::getPropertys($download_list,"resume_id");
                //工作经验
                $work_datas = $service_resume_work->getResumeWorks(implode(",", $resume_ids),"work_id,resume_id,start_time,end_time,station,company_name,work_content");
                //$work_datas = $this->arrayFormat($work_datas->items, "resume_id");
                if(count($work_datas->items )>0){
                    foreach ($work_datas->items as $workskey=>$worksvalue){
                        $workslist[$worksvalue["resume_id"]][$workskey]['start_time'] = date('Y/m',strtotime($worksvalue['start_time']));
                        $workslist[$worksvalue["resume_id"]][$workskey]['end_time'] = empty($worksvalue['end_time'])?"至今":date('Y/m',strtotime($worksvalue['end_time']));
                        $workslist[$worksvalue["resume_id"]][$workskey]['station'] = $worksvalue['station'];
                        $workslist[$worksvalue["resume_id"]][$workskey]['company_name'] = $worksvalue['company_name'];
                        $workslist[$worksvalue["resume_id"]][$workskey]['work_content'] = base_lib_BaseUtils::cutstr($worksvalue['work_content'], 180,'utf-8','','...');
                    }
                }

                $resume_list = $service_resume->getResumes($resume_ids,'resume_id,person_id,resume_name,degree_id,current_station,current_station_start_time,current_station_end_time,is_effect',false);
                // 求职者
                $person_ids = $this->getPropertys($resume_list->items, 'person_id');
                $service_qloudmsg = new base_service_app_qcloudmsg();
                $chat_list        = $service_qloudmsg->getReplyTimes($persin_ids);
                $chat_list        = base_lib_BaseUtils::array_key_assoc($chat_list, "person_id");
                
                $person_ids     = implode(',',$person_ids);
                $person_list    = $service_person->GetPersonListByIDs($person_ids, 'person_id,user_name,sex,birthday2,cur_area_id,start_work,photo,small_photo,login_time,name_open,photo_open');
                //  备注列表
                $remark_data = $service_resume_remark->getLastResumeRemarks($this->_userid,$resume_ids,'remark_id,resume_id,company_id,remark,update_time');
                // 用户信息
                $userids = base_lib_BaseUtils::getPropertys($list, 'recommend_man');
                $userservice = new base_service_crm_user();
                $users = $userservice->getUserByIDs($userids, 'user_id, user_name')->items;
                $serviceArea = new base_service_company_service_serviceArea();
                $service_wangyiaction = new base_service_app_wangyiaction();
                //判断这些person_ids对应的网易云账户是否在线
                $wy_person_status_arr = $service_wangyiaction->checkPersonsIsOnline($person_ids);
                //获取已投递的简历
                $has_apply_resume     = $service_apply->getApplyByResumeId(implode(",",$resume_ids),$this->_userid,true);
                $has_apply_resume_ids = base_lib_BaseUtils::getProperty($has_apply_resume,'resume_id');
                $has_apply_resume_ids = array_unique($has_apply_resume_ids);
                for($i=0,$len=count($list);$i<$len;$i++){
                    $recommend = $list[$i];
                    $resume_info =  $this->arrayFind($resume_list->items, 'resume_id', $recommend['resume_id']);
                    $resume_id = $resume_info['resume_id'];
                    $person_id = $resume_info['person_id'];
                    $person_info = $this->arrayFind($person_list->items, 'person_id', $person_id);
                    $resume_work_info = $this->arrayFind($workslist, 'resume_id', $resume_id);
                    $resume_remark_info= $this->arrayFind($remark_data->items, 'resume_id', $resume_id);
                    //获得状态
                    //判断简历是否已邀请
                    $list[$i]['resume_status'] = $this->_getStatus($recommend['is_read'],$recommend['status'],$resume_id);
                    //姓名
                    $list[$i]['user_name'] =  base_lib_BaseUtils::cutstr($person_info['user_name'], 4,'utf-8','','...');
                    if($person_info['name_open'] ==0){
                        $sex_name = $person_info['sex']==1?'先生':'女士';
                        $list[$i]['user_name'] = mb_substr($person_info['user_name'],0,1,'utf-8').$sex_name;
                    }

                    if (!empty($resume_downloadids) || !empty($has_apply_resume_ids)) {
                        if(in_array($resume_id, $resume_downloadids) || in_array($resume_id,$has_apply_resume_ids)){
                            $list[$i]['has_download'] = true;
                        }
                    }

                    $list[$i]['full_user_name'] = base_lib_BaseUtils::htmlEncode($person_info['user_name']);
                    $list[$i]['remark'] = base_lib_BaseUtils::nullOrEmpty($resume_remark_info['remark'])?false:base_lib_BaseUtils::cutstr($resume_remark_info['remark'],8,'utf-8','','...').'&nbsp'.date('Y-m-d',strtotime($resume_remark_info['update_time']));

                    //头像性别、年龄、学历、当前所在地
                    if(base_lib_BaseUtils::nullOrEmpty($person_info['photo']) || $person_info['photo_open'] ==0){
                        $person_info['photo'] = false;
                        $person_info['small_photo'] =false;
                    }
                    else{
                        if(base_lib_BaseUtils::nullOrEmpty($person_info['small_photo'])){
                            $person_info['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$person_info['photo'];
                        }else{
                            $person_info['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$person_info['photo'];//改版后用原始头像
                        }
                        $person_info['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$person_info['photo'];
                    }
                    //已超出简历下载地区限制，请联系工作人员开通相应招聘服务。
                    $list[$i]['not_area_limit'] = $serviceArea->IsServiceAreaTypeDownloadResumeScope($this->_userid,$recommend['resume_id']);//限制为false
                    $list[$i]['photo'] = $person_info['photo'];
                    $list[$i]['small_photo'] = $person_info['small_photo'];
                    $list[$i]['sex'] = $this->getSex($person_info['sex']) ;
                    $list[$i]['age'] = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']).'岁';
                    $list[$i]['degree'] =$this->getDegree($resume_info['degree_id']);
                    $list[$i]['cur_area'] = $this->getArea($person_info['cur_area_id']) ;
                    //毕业院校
                    $edu_info =$this->arrayFind($edu_data->items,'resume_id', $resume_id);
                    $list[$i]['school'] = $edu_info['school'];
                    $list[$i]['major_desc'] = $edu_info['major_desc'];
                    $list[$i]['school_degree'] = $this->getDegree($edu_info['degree']) ;
                    //最近工作经历
                    $count =count($workslist["$resume_id"]);
                    if($count >0){
                        $list[$i]['worklist'] = array_slice($workslist["$resume_id"],0,($count>=3?3:$count));
                    }else{
                        $list[$i]['worklist'] = array();
                    }
                    //工作年限
                    $basic_start_work_year = base_lib_TimeUtil::date_diff_month($person_info['start_work']);
                    $workY = floor($basic_start_work_year/12);
                    $workM = intval($basic_start_work_year%12);

                    if($workY<=0 && $workM<=6&&$workM>=-6){
                        $basic_start_work_year = '应届毕业生';
                    }else if($workY == 0 && $workM>6){
                        $basic_start_work_year = $workM.'个月工作经验';
                    }else if($basic_start_work_year<-6){
                        $basic_start_work_year = '目前在读';
                    }else{
                        $basic_start_work_year = $workY.'年工作经验';
                    }
                    $list[$i]['start_work'] = $basic_start_work_year;
                    if(base_lib_BaseUtils::nullOrEmpty($list[$i]['start_work'])){
                        $list[$i]['start_work'] = "应届毕业生";
                    }
                    // 推荐职位
                    if(empty($list[$i]['job_id'])) {
                        $list[$i]['recommendstation'] = $list[$i]['station'];
                    }else {
                        $job = $this->arrayFind($jobs, 'job_id', $list[$i]['job_id']);
                        $list[$i]['recommendstation'] = $job['station'];
                    }

                    // 推荐时间
                    //$list[$i]['recommendtime'] = base_lib_TimeUtil::to_friend_time($list[$i]['recommend_time']);
                    $list[$i]['recommendtime'] = $list[$i]['recommend_time'];
                    //最近工作经验
                    if($resume_info['current_station']==''){
                        $list[$i]['work'] = '无';
                    }else {
                        if(base_lib_BaseUtils::nullOrEmpty($resume_info['current_station_start_time'])){
                            $list[$i]['work'] = $resume_info['current_station'];
                        }else{
                            $list[$i]['work']= $resume_info['current_station'].'('.base_lib_TimeUtil::date_diff_year3($resume_info['current_station_start_time'],$resume_info['current_station_end_time']).')';
                        }
                    }
                    // 求职者登录时间
                    $list[$i]['personlogintime'] = base_lib_TimeUtil::to_friend_time($person_info['login_time']);
                    $user = base_lib_BaseUtils::arrayFind($users, 'user_id', $recommend['recommend_man']);
                    // 推荐人姓名
                    if(!empty($user)) {
                        $list[$i]['recommendmanname'] = '汇博-'.$user['user_name'];
                    }
                    //教育经历
                    //判断是否活跃
                    $chat_info = $chat_list[$person_id];
                    $list[$i]["is_active"] = $chat_info["count"] >= 3 ? true : false;
                    //$list[$i]['chat_status'] = !empty($wy_person_status_arr[$person_id]) ? $wy_person_status_arr[$person_id] : false;
                    $list[$i]['chat_status'] = !empty($login_status[$person_id]) ? true : false;
                }
                //分页
                $pager = $this->pageBar($recommend_list->totalSize,$page_size,$cur_page,$inPath);
                $this->_aParams['pager'] = $pager;
            }

            $this->_aParams['station'] = $station;
            $this->_aParams['recommendlist'] = $list;
            $this->_aParams['totalSize'] = $recommend_list->totalSize;
            // 是否发布过职位
            $has_issuejob = $service_job->hasIssueJob($this->_userid);
            $this->_aParams['hasJob'] = $has_issuejob;
            // 是否有推荐
            $this->_aParams['hasRecommend'] = count($hasRecommendJobs->items)>0?true:false;


            //获得各种状态下收到的总条数
            $use_job_ids = array();
            $showStopJobApply = base_lib_BaseUtils::getCookie("showStopJobApply"); //判断用户是否包含了 停招招聘的职位
            if(base_lib_BaseUtils::nullOrEmpty($showStopJobApply) || $showStopJobApply!="true"){
                $service_job = new base_service_company_job_job();
                $job_status = new base_service_common_jobstatus();
                $use_job_list = $service_job->getJobList($this->_userid, null,$job_status->pub,'job_id');
                $use_job_ids = $this->getPropertys($use_job_list,"job_id");
            }

            $apply_status_count = $service_apply->getStatusGroupCount($this->_userid,$use_job_ids);
            $this->_aParams['apply_status_count'] = $apply_status_count->items;
            $this->_aParams['job_id'] = empty($job_id) ? $station : $job_id;

            // 查询是否有收到的简历信息

            $company_resources = base_service_company_resources_resources::getInstance($this->_userid);
            $this->_aParams['pricing_resource_data'] = $company_resources->getCompanyServiceSource(["cq_pricing_resource"]); //获取套餐资源

            $service_question = new base_service_company_question();

            if($service_question->canAnswer($this->_userid)){
                $this->_aParams['is_question'] = 1;
            }
        }
        $this->_aParams['jobs'] = "[]";
        if(count($jobs)>0) {
            $jobs_json = array();
            array_push($jobs_json, "{id:\"\",name:\"全部职位\"}");
            foreach ($jobs as $job){
                array_push($jobs_json, "{id:\"".$job['job_id']."\",name:\"".$job['station']."\"}");
            }
            $this->_aParams['jobs'] = "[".implode(',', $jobs_json)."]";
        }
        $xml = SXML::load('../config/config.xml');
        $this->_aParams['ShowResumeNum'] = $xml->ShowResumeNum;
        $this->_aParams['title'] = "推荐的简历 简历管理_{$xml->HuiBoSiteName}";

        $this->_aParams['company_id'] = $this->_userid;
        $this->_aParams['recommend_resume_num'] = $recommend_resume_num;
        //todo:添加开始，这里把类型先写死为自动推荐，上线前一定去掉，切记
        /*$this->_aParams['type'] = 1;
        $this->_aParams['resume_list'] = $this->_aParams['recommendlist'];*/
        //todo：添加结束
        return $this->render('resume/recommend/list_v2.html', $this->_aParams);
	}


    /**
     * 收到的简历列表
     * @param  $inPath
     */
    public function pagegrayIndex($inPath) {
        if(!$this->canDo("resume_manage")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

        // 参数信息:  职位编号，投递时间，状态，姓名/简历编号，显示方式（列表/摘要）
        $job_id = base_lib_BaseUtils::getStr($params['job_id'],'int',null);
        $status = base_lib_BaseUtils::getStr($params['status'],'int',null);
        $cur_page = base_lib_BaseUtils::getStr($params['page'],'int',1);
        $page_size =base_lib_BaseUtils::getStr($params['pageSize'],'int',base_lib_Constant::PAGE_SIZE);
        $type = base_lib_BaseUtils::getStr($params['type'],'int',1);
        $is_ajax = base_lib_BaseUtils::getStr($params['is_ajax'],'int',0);

        $this->_aParams['type'] = $type;


        // 获取是否为HR会员
        $account_id = base_lib_BaseUtils::getCookie('accountid');
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $account_id);
        // 是否为会员
        $memberinfo = $company_resources->isMember() ? "member" : "not_member";
        $this->_aParams['memberinfo'] = $memberinfo;
        //非会员只能看自动推荐简历
        if($memberinfo == 'not_member'){
            $type = 1;
        }

        $service_recommend = new base_service_company_resume_recommend();
        $service_job = new base_service_company_job_job();

        if(empty($job_id)) {
            $station = base_lib_BaseUtils::getStr($params['job_id'],'string',null);
        }
        $is_read = null;
        $this->_aParams['status'] = $status;
        if($status ==99){
            $is_read = false; //未读
            $status =null;
        }
        //推荐简历的状态 已读 未读 合适 不合适 未处理
        //获得已读未读状态
        $status_array = array();
        //添加未读 与 为处理
        // $status_array[] =  array('id'=>"",'name' =>"不限");
        $status_array[] =  array('id'=>"99",'name' =>"未读");
        $status_array[] =  array('id'=>"0",'name' =>"未处理");
        $service_recommend_status = new base_service_common_recommendstatus();
        $recommend_status_array = $service_recommend_status->getRecommendStatus();
        foreach($recommend_status_array as $k=>$recommend_status){
            $status_array[] = array('id'=>$k,'name' => $recommend_status);
        }
        $this->_aParams['status_array'] = $status_array;

        $service_apply = new base_service_company_resume_apply();
        //1周内企业未读的人工推荐的简历
        $recomend_type = [4];
        $week_time = date("Y-m-d",strtotime("-1 week"));
        $recommend_list = $service_recommend->getRecommendResumeV2ByTime($this->_userid,"recommend_id,company_id,recommend_type,is_read",$recomend_type,0,$week_time);
        $recommend_not_read_num = count($recommend_list);
        //是否关闭人工推荐的简历气泡

        $showArtificialRecommendTip_temp = base_lib_BaseUtils::getCookie("showArtificialRecommendTip_".$account_id);
        $isShowArtificialRecommendTip = false;
        if(!$showArtificialRecommendTip_temp && $recommend_not_read_num > 0){
            $isShowArtificialRecommendTip = true;
        }

        $this->_aParams['isShowArtificialRecommendTip'] = $isShowArtificialRecommendTip;
        // 获取未处理推荐的列表
        $recommend_temp_list = $service_recommend->getRecommendList(20,1,$this->_userid,'recommend_id,resume_id,job_id,is_read,status,recommend_time,station,recommend_man,recommend_type',null, null, null, null,null,0);
        //自动推荐
//        $recommend_resume_num = count($recommend_temp_list->items);
        $recommend_resume_num = $service_recommend->hasNotDealRecommend($this->_userid);
        if($type == 1){
            $resume_list = array();
            $default_data = $this->getDefaultJob();
            $this->_aParams['job_list'] = $default_data['job_list'];
            $job_id =  empty($job_id) ? $default_data['default_job_id'] : $job_id;
            $new_resume_list = [];
            if($job_id) {
                $service_company_job_job = new base_service_company_job_job();
                $job_info = $service_company_job_job->getJob($job_id, "job_id,station,jobsort");
                $this->_aParams['job_info'] = $job_info;
                $this->_aParams['default_job_id'] = $job_id;
                $solr_resume = new base_service_solr_resume();
                //换一换
                $auto_recommend_resumeids = base_lib_BaseUtils::getStr($params['auto_recommend_resumeids'],'string',null);

                $fresh = base_lib_BaseUtils::getStr($params['fresh'],'int','');
                $page = base_lib_BaseUtils::getStr($params['page'],'int',1);
                if($fresh && $page < 2) {
                    $page = 2;
                }
                $auto_recommend_resumeids = empty($auto_recommend_resumeids) ? [] : explode(',',$auto_recommend_resumeids);
                
                $resume_ids = $solr_resume->resumeRecommendByJob($this->_userid, $job_id, $page, $auto_recommend_resumeids, 20);
                if($fresh){
                    if($page < 2){
                        $page = 2;
                    }
                    $page += 1;
                }
                $next_page = $resume_ids['next_page'];
                if(!$next_page && $page > 1){
                    $page = 1;
                    $next_page = true;
                }
                $this->_aParams['auto_recommend_resumeids'] = empty($auto_recommend_resumeids) ? implode(',',$resume_ids['resume_ids']) : implode(',',array_merge($auto_recommend_resumeids,$resume_ids['resume_ids']));
                $this->_aParams['next_page'] = $next_page;
                $this->_aParams['page'] = $page;
                //获取简历
                $service_person_resume_resume = new base_service_person_resume_resume();
                $service_person_person = new base_service_person_person();
                $service_edu = new base_service_person_resume_edu();
                $service_person_resume_work = new base_service_person_resume_work();
                $serviceArea = new base_service_company_service_serviceArea();

                if ($resume_ids['resume_ids']) {
                    $resume_ids_str = implode(',', $resume_ids['resume_ids']);
                    $field = "resume_id,person_id,user_name,sex,birthday,cur_area_id,degree_id,major_desc,work_year,station,appraise";

                    $resume_list = $service_person_resume_resume->getResumes($resume_ids_str, $field)->items;

                    $person_ids = base_lib_BaseUtils::getPropertys($resume_list, "person_id");

                    $service_qloudmsg = new base_service_app_qcloudmsg();
                    $chat_list        = $service_qloudmsg->getReplyTimes($person_ids);
                    $chat_list        = base_lib_BaseUtils::array_key_assoc($chat_list, "person_id");

                    $person_ids = implode(',', $person_ids);
                    //求职信息
                    $person_list = $service_person_person->GetPersonListByIDs($person_ids, 'person_id,user_name,name_open,photo_open,sex,birthday2,cur_area_id,start_work,photo,small_photo,mobile_phone,job_state_id,accession_time')->items;
                    $person_list = base_lib_BaseUtils::array_key_assoc($person_list, "person_id");

                    //教育
                    $edu_data = $service_edu->getResumeEdus($resume_ids_str, 'resume_id,school,major_desc,degree')->items;
                    //工作经验
                    $work_datas = $service_person_resume_work->getResumeWorks($resume_ids_str, 'work_id,resume_id,start_time,end_time,station,company_name,work_content')->items;


                    /*获得下载的简历记录*/
                    $download_service = new base_service_company_resume_download();
                    $download_list = $download_service->queryDownloadList($resume_ids_str, $this->_userid, "resume_id,download_id")->items;
                    $resume_downloadids = base_lib_BaseUtils::getPropertys($download_list, "resume_id");

                    $service_apply = new base_service_company_resume_apply();
                    $has_apply_resume = $service_apply->getApplyByResumeId($resume_ids_str, $this->_userid, true);
                    $has_apply_resume_ids = base_lib_BaseUtils::getProperty($has_apply_resume, 'resume_id');
                    $has_apply_resume_ids = array_unique($has_apply_resume_ids);
                    $service_company_resume_resumevisit = new base_service_company_resume_resumevisit();
                    $company_resources_info = $company_resources->getCompanyAuditStatusV2();
                    $isshowresumeinfo = true;
                    $letter_info = $this->CheckCompanyLetter($this->_userid);

                    if($company_resources_info['audit_type'] == 1){
                        //老规则
                        $isshowresumeinfo = true;
                    }else{
                        //新规则
                        if($letter_info['code'] != 200){
                            $isshowresumeinfo = false;
                        }
                    }

                    //求职者一周内是否登录过app
                    $sercie_loginlog= new base_service_person_loginlog();
                    $login_status = $sercie_loginlog->getLoginAppData($person_ids,14);
                    if(!empty($login_status))
                        $login_status = base_lib_BaseUtils::array_key_assoc($login_status,'person_id');
                    //$service_chat = new company_service_chat(0,0);
                    $service_wangyiaction = new base_service_app_wangyiaction();
                    //判断这些person_ids对应的网易云账户是否在线
                    $wy_person_status_arr = $service_wangyiaction->checkPersonsIsOnline($person_ids);
                    //意向职位类别
                    $jobsort_service = new base_service_person_resume_jobsortexp();
                    $jobsort_common = new base_service_common_jobsort();
                    $jobsorts = $jobsort_service->getJobsortByResumeIds($resume_ids['resume_ids']);
                    if(!empty($jobsorts))
                        $jobsorts = base_lib_BaseUtils::array_key_assoc($jobsorts,'resume_id');

                    foreach ($resume_list as $key => &$value) {
                        $person_info = $person_list[$value['person_id']];

                        $is_show_name = false; //是否显示简历的名字
                        $is_show_linkway = false;//是否显示联系方式  其中有是否下载
                        $member_info = false;//是否是会员
                        $member_expires = false;//会员是否过期
                        $is_show_resumeinfo = false;//是否显示详细信息

                        $result = $this->checkResume($value['resume_id'], $is_show_name, $is_show_linkway, $member_info, $member_expires,$is_show_resumeinfo);

                        //姓名
                        if ($is_show_name && $isshowresumeinfo) {
                            $value['user_name'] = $person_info['user_name'];
                        } else {
                            $sex_name = $person_info['sex'] == 1 ? '先生' : '女士';
                            $value['user_name'] = mb_substr($person_info['user_name'], 0, 1, 'utf-8') . $sex_name;

                        }

                        if ($person_info['photo_open'] === '0') {//允许null,和1一样，默认可以公开
                            $person_info['photo'] = false;
                            $person_info['small_photo'] = false;
                        } else {
                            if (base_lib_BaseUtils::nullOrEmpty($person_info['small_photo'])) {
                                $person_info['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['photo'];
                            } else {
                                $person_info['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['small_photo'];
                            }
                            //兼容判断
                            if (base_lib_BaseUtils::nullOrEmpty($person_info['photo']))
                                $person_info['photo'] = false;
                            else
                                $person_info['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['photo'];
                        }

                        $value['small_photo'] = $person_info['photo'];
                        $value['sex'] = $this->getSex($person_info['sex']);
                        $value['age'] = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']) . '岁';
                        $value['degree'] = $this->getDegree($value['degree_id']);
                        $value['cur_area'] = $this->getArea($person_info['cur_area_id']);


                        //工作年限
                        $basic_start_work_year = base_lib_TimeUtil::date_diff_month($person_info['start_work']);
                        $workY = floor($basic_start_work_year / 12);
                        $workM = intval($basic_start_work_year % 12);

                        if ($workY <= 0 && $workM <= 6 && $workM >= -6) {
                            $basic_start_work_year = '应届毕业生';
                        } else if ($workY == 0 && $workM > 6) {
                            $basic_start_work_year = $workM . '个月工作经验';
                        } else if ($basic_start_work_year < -6) {
                            $basic_start_work_year = '目前在读';
                        } else {
                            $basic_start_work_year = $workY . '年工作经验';
                        }

                        $value['start_work'] = $basic_start_work_year;
                        if (base_lib_BaseUtils::nullOrEmpty($value['start_work'])) {
                            $value['start_work'] = "应届毕业生";
                        }
                        //工作经历
                        $work_list = array();
                        foreach ($work_datas as $k => $v) {
                            $data = array();
                            if(count($work_list) >= 3){
                                continue;
                            }
                            if ($v['resume_id'] == $value['resume_id']) {
                                $data['start_time'] = date('Y/m', strtotime($v['start_time']));
                                $data['end_time'] = empty($v['end_time']) ? "至今" : date('Y/m', strtotime($v['end_time']));
                                $data['station'] = $v['station'];
                                $data['company_name'] = $v['company_name'];
                                //$data['work_content'] = base_lib_BaseUtils::cutstr($v['work_content'], 180, 'utf-8', '','...');
                            }
                            if ($data) {
                                array_push($work_list, $data);
                            }
                        }
                        $value['work_list'] = $work_list;

                        //教育
                        $edu_info = $this->arrayFind($edu_data, 'resume_id', $value['resume_id']);
                        $value['school'] = $edu_info['school'];
                        $value['major_desc'] = $edu_info['major_desc'];
                        $value['school_degree'] = $this->getDegree($edu_info['degree']);

                        //求职意向
                        $value['job_state_id'] = $person_info['job_state_id'];
                        $value['accession_time'] = $person_info['accession_time'];

                        if (!empty($resume_downloadids) || !empty($has_apply_resume_ids)) {
                            if (in_array($value['resume_id'], $resume_downloadids) || in_array($value['resume_id'], $has_apply_resume_ids)) {
                                $value['has_download'] = true;
                            }
                        }
                        //2周内是否查看简历
                        $is_see_resume = $service_company_resume_resumevisit->getCompanyRecommendResume($this->_userid,$account_id,$value['resume_id']);
                        $value['is_see'] = false;
                        if($is_see_resume){
                            $value['is_see'] = true;
                        }
                        $value['not_area_limit'] = $serviceArea->IsServiceAreaTypeDownloadResumeScope($this->_userid, $value['resume_id']);//限制为false
                        //判断是否活跃
                        $chat_info = $chat_list[$value['person_id']];
                        $value["is_active"] = $chat_info["count"] >= 3 ? true : false;


                        //聊一聊状态 ，提示等级 1：(未登录)提示 2：（未登录）提示 + 提示消耗 3：提示消耗
                        $chat_params['resume_id'] = $value['resume_id'];
                        $chat_params['person_id'] = $value['person_id'];
                        $chat_params['company_id'] = $this->_userid;
                        //$value['chat_status'] = $service_chat->getChatNoticeStatus($this->_userid,base_lib_BaseUtils::getCookie('accountid'),$login_status[$chat_params['person_id']],$chat_params);
                        $value['chat_status'] =  !empty($wy_person_status_arr[$value['person_id']]) ? $wy_person_status_arr[$value['person_id']] : false;;

                        //意向职位类别
                        $_jobsort_res = [];
                        $_jobsorts    = $jobsorts[ $value['resume_id'] ]['jobsort'];
                        if (!empty($_jobsorts)) {
                            $_jobsort_arr = explode(',', $_jobsorts);
                            foreach ($_jobsort_arr as $__jobsort) {
                                $_jobsort_res[] = ['jobsort' => $__jobsort, 'name'    => $jobsort_common->getJobsortName($__jobsort, true)];
                            }
                        }
                        $value['jobsort_arr'] = $_jobsort_res;

                    }

                    $resume_list = base_lib_BaseUtils::array_key_assoc($resume_list,"resume_id");
                    foreach($resume_ids['resume_ids'] as $key => $val){
                        if($resume_list[$val]){
                            array_push($new_resume_list,$resume_list[$val]);
                        }
                    }
                }
            }
            $this->_aParams['auto_job_id'] = $job_id;
            $this->_aParams['resume_list'] = $new_resume_list;

            if($is_ajax){
                echo header("Content-type:text/json;charset=utf-8");
                $ajax_html = $this->render('./ajaxautorecommend.html',$this->_aParams);
                echo json_encode(["page"=>$page, 'auto_recommend_resumeids' => $this->_aParams['auto_recommend_resumeids'], 'count' => count($resume_list), "html"=>$ajax_html]);
                return;
            }

        }else{

            $recommend_list = $service_recommend->getRecommendList($page_size,$cur_page,$this->_userid,'recommend_id,resume_id,job_id,is_read,status,recommend_time,station,recommend_man,recommend_type',$job_id, null, null, $station,$is_read,$status);
            // 职位
            $hasRecommendJobs = $service_recommend->getHasRecommendJobIds($this->_userid);
            $job_ids = $this->getPropertys($hasRecommendJobs->items, 'job_id');
            $jobs = $this->_getJobsAndSort($job_ids, $this->_userid);
            $list = $recommend_list->items;

            if(!base_lib_BaseUtils::nullOrEmpty($list)&& count($list) > 0 ) {
                $service_person = new base_service_person_person();
                $service_resume = new base_service_person_resume_resume();
                $service_resume_work = new base_service_person_resume_work();
                $service_resume_remark = new base_service_company_resume_resumeremark();
                // 简历
                $resume_ids = $this->getPropertys($list, 'resume_id');
                //毕业院校
                $service_edu = new base_service_person_resume_edu();
                $edu_data = $service_edu->getResumeEdus(implode(",", $resume_ids),'resume_id,school,major_desc,degree');
                /*获得下载的简历记录*/
                $download_service = new base_service_company_resume_download();
                $download_list = $download_service->queryDownloadList(implode(",", $resume_ids),$this->_userid,"resume_id,download_id")->items;
                $resume_downloadids = base_lib_BaseUtils::getPropertys($download_list,"resume_id");
                //工作经验
                $work_datas = $service_resume_work->getResumeWorks(implode(",", $resume_ids),"work_id,resume_id,start_time,end_time,station,company_name,work_content");
                //$work_datas = $this->arrayFormat($work_datas->items, "resume_id");
                if(count($work_datas->items )>0){
                    foreach ($work_datas->items as $workskey=>$worksvalue){
                        $workslist[$worksvalue["resume_id"]][$workskey]['start_time'] = date('Y/m',strtotime($worksvalue['start_time']));
                        $workslist[$worksvalue["resume_id"]][$workskey]['end_time'] = empty($worksvalue['end_time'])?"至今":date('Y/m',strtotime($worksvalue['end_time']));
                        $workslist[$worksvalue["resume_id"]][$workskey]['station'] = $worksvalue['station'];
                        $workslist[$worksvalue["resume_id"]][$workskey]['company_name'] = $worksvalue['company_name'];
                        $workslist[$worksvalue["resume_id"]][$workskey]['work_content'] = base_lib_BaseUtils::cutstr($worksvalue['work_content'], 180,'utf-8','','...');
                    }
                }

                $resume_list = $service_resume->getResumes($resume_ids,'resume_id,person_id,resume_name,degree_id,current_station,current_station_start_time,current_station_end_time,is_effect',false);
                // 求职者
                $person_ids = $this->getPropertys($resume_list->items, 'person_id');
                $service_qloudmsg = new base_service_app_qcloudmsg();
                $chat_list        = $service_qloudmsg->getReplyTimes($persin_ids);
                $chat_list        = base_lib_BaseUtils::array_key_assoc($chat_list, "person_id");

                $person_ids     = implode(',',$person_ids);
                $person_list    = $service_person->GetPersonListByIDs($person_ids, 'person_id,user_name,sex,birthday2,cur_area_id,start_work,photo,small_photo,login_time,name_open,photo_open');
                //  备注列表
                $remark_data = $service_resume_remark->getLastResumeRemarks($this->_userid,$resume_ids,'remark_id,resume_id,company_id,remark,update_time');
                // 用户信息
                $userids = base_lib_BaseUtils::getPropertys($list, 'recommend_man');
                $userservice = new base_service_crm_user();
                $users = $userservice->getUserByIDs($userids, 'user_id, user_name')->items;
                $serviceArea = new base_service_company_service_serviceArea();
                $service_wangyiaction = new base_service_app_wangyiaction();
                //判断这些person_ids对应的网易云账户是否在线
                $wy_person_status_arr = $service_wangyiaction->checkPersonsIsOnline($person_ids);
                //获取已投递的简历
                $has_apply_resume     = $service_apply->getApplyByResumeId(implode(",",$resume_ids),$this->_userid,true);
                $has_apply_resume_ids = base_lib_BaseUtils::getProperty($has_apply_resume,'resume_id');
                $has_apply_resume_ids = array_unique($has_apply_resume_ids);
                for($i=0,$len=count($list);$i<$len;$i++){
                    $recommend = $list[$i];
                    $resume_info =  $this->arrayFind($resume_list->items, 'resume_id', $recommend['resume_id']);
                    $resume_id = $resume_info['resume_id'];
                    $person_id = $resume_info['person_id'];
                    $person_info = $this->arrayFind($person_list->items, 'person_id', $person_id);
                    $resume_work_info = $this->arrayFind($workslist, 'resume_id', $resume_id);
                    $resume_remark_info= $this->arrayFind($remark_data->items, 'resume_id', $resume_id);
                    //获得状态
                    //判断简历是否已邀请
                    $list[$i]['resume_status'] = $this->_getStatus($recommend['is_read'],$recommend['status'],$resume_id);
                    //姓名
                    $list[$i]['user_name'] =  base_lib_BaseUtils::cutstr($person_info['user_name'], 4,'utf-8','','...');
                    if($person_info['name_open'] ==0){
                        $sex_name = $person_info['sex']==1?'先生':'女士';
                        $list[$i]['user_name'] = mb_substr($person_info['user_name'],0,1,'utf-8').$sex_name;
                    }

                    if (!empty($resume_downloadids) || !empty($has_apply_resume_ids)) {
                        if(in_array($resume_id, $resume_downloadids) || in_array($resume_id,$has_apply_resume_ids)){
                            $list[$i]['has_download'] = true;
                        }
                    }

                    $list[$i]['full_user_name'] = base_lib_BaseUtils::htmlEncode($person_info['user_name']);
                    $list[$i]['remark'] = base_lib_BaseUtils::nullOrEmpty($resume_remark_info['remark'])?false:base_lib_BaseUtils::cutstr($resume_remark_info['remark'],8,'utf-8','','...').'&nbsp'.date('Y-m-d',strtotime($resume_remark_info['update_time']));

                    //头像性别、年龄、学历、当前所在地
                    if(base_lib_BaseUtils::nullOrEmpty($person_info['photo']) || $person_info['photo_open'] ==0){
                        $person_info['photo'] = false;
                        $person_info['small_photo'] =false;
                    }
                    else{
                        if(base_lib_BaseUtils::nullOrEmpty($person_info['small_photo'])){
                            $person_info['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$person_info['photo'];
                        }else{
                            $person_info['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$person_info['photo'];//改版后用原始头像
                        }
                        $person_info['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.$person_info['photo'];
                    }
                    //已超出简历下载地区限制，请联系工作人员开通相应招聘服务。
                    $list[$i]['not_area_limit'] = $serviceArea->IsServiceAreaTypeDownloadResumeScope($this->_userid,$recommend['resume_id']);//限制为false
                    $list[$i]['photo'] = $person_info['photo'];
                    $list[$i]['small_photo'] = $person_info['small_photo'];
                    $list[$i]['sex'] = $this->getSex($person_info['sex']) ;
                    $list[$i]['age'] = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']).'岁';
                    $list[$i]['degree'] =$this->getDegree($resume_info['degree_id']);
                    $list[$i]['cur_area'] = $this->getArea($person_info['cur_area_id']) ;
                    //毕业院校
                    $edu_info =$this->arrayFind($edu_data->items,'resume_id', $resume_id);
                    $list[$i]['school'] = $edu_info['school'];
                    $list[$i]['major_desc'] = $edu_info['major_desc'];
                    $list[$i]['school_degree'] = $this->getDegree($edu_info['degree']) ;
                    //最近工作经历
                    $count =count($workslist["$resume_id"]);
                    if($count >0){
                        $list[$i]['worklist'] = array_slice($workslist["$resume_id"],0,($count>=3?3:$count));
                    }else{
                        $list[$i]['worklist'] = array();
                    }
                    //工作年限
                    $basic_start_work_year = base_lib_TimeUtil::date_diff_month($person_info['start_work']);
                    $workY = floor($basic_start_work_year/12);
                    $workM = intval($basic_start_work_year%12);

                    if($workY<=0 && $workM<=6&&$workM>=-6){
                        $basic_start_work_year = '应届毕业生';
                    }else if($workY == 0 && $workM>6){
                        $basic_start_work_year = $workM.'个月工作经验';
                    }else if($basic_start_work_year<-6){
                        $basic_start_work_year = '目前在读';
                    }else{
                        $basic_start_work_year = $workY.'年工作经验';
                    }
                    $list[$i]['start_work'] = $basic_start_work_year;
                    if(base_lib_BaseUtils::nullOrEmpty($list[$i]['start_work'])){
                        $list[$i]['start_work'] = "应届毕业生";
                    }
                    // 推荐职位
                    if(empty($list[$i]['job_id'])) {
                        $list[$i]['recommendstation'] = $list[$i]['station'];
                    }else {
                        $job = $this->arrayFind($jobs, 'job_id', $list[$i]['job_id']);
                        $list[$i]['recommendstation'] = $job['station'];
                    }

                    // 推荐时间
                    //$list[$i]['recommendtime'] = base_lib_TimeUtil::to_friend_time($list[$i]['recommend_time']);
                    $list[$i]['recommendtime'] = $list[$i]['recommend_time'];
                    //最近工作经验
                    if($resume_info['current_station']==''){
                        $list[$i]['work'] = '无';
                    }else {
                        if(base_lib_BaseUtils::nullOrEmpty($resume_info['current_station_start_time'])){
                            $list[$i]['work'] = $resume_info['current_station'];
                        }else{
                            $list[$i]['work']= $resume_info['current_station'].'('.base_lib_TimeUtil::date_diff_year3($resume_info['current_station_start_time'],$resume_info['current_station_end_time']).')';
                        }
                    }
                    // 求职者登录时间
                    $list[$i]['personlogintime'] = base_lib_TimeUtil::to_friend_time($person_info['login_time']);
                    $user = base_lib_BaseUtils::arrayFind($users, 'user_id', $recommend['recommend_man']);
                    // 推荐人姓名
                    if(!empty($user)) {
                        $list[$i]['recommendmanname'] = '汇博-'.$user['user_name'];
                    }
                    //教育经历
                    //判断是否活跃
                    $chat_info = $chat_list[$person_id];
                    $list[$i]["is_active"] = $chat_info["count"] >= 3 ? true : false;
                    $list[$i]['chat_status'] = !empty($login_status[$person_id]) ? true : false;
                }
                //分页
                $pager = $this->pageBar($recommend_list->totalSize,$page_size,$cur_page,$inPath);
                $this->_aParams['pager'] = $pager;
            }

            $this->_aParams['station'] = $station;
            $this->_aParams['recommendlist'] = $list;
            $this->_aParams['totalSize'] = $recommend_list->totalSize;
            // 是否发布过职位
            $has_issuejob = $service_job->hasIssueJob($this->_userid);
            $this->_aParams['hasJob'] = $has_issuejob;
            // 是否有推荐
            $this->_aParams['hasRecommend'] = count($hasRecommendJobs->items)>0?true:false;


            //获得各种状态下收到的总条数
            $use_job_ids = array();
            $showStopJobApply = base_lib_BaseUtils::getCookie("showStopJobApply"); //判断用户是否包含了 停招招聘的职位
            if(base_lib_BaseUtils::nullOrEmpty($showStopJobApply) || $showStopJobApply!="true"){
                $service_job = new base_service_company_job_job();
                $job_status = new base_service_common_jobstatus();
                $use_job_list = $service_job->getJobList($this->_userid, null,$job_status->pub,'job_id');
                $use_job_ids = $this->getPropertys($use_job_list,"job_id");
            }

            $apply_status_count = $service_apply->getStatusGroupCount($this->_userid,$use_job_ids);
            $this->_aParams['apply_status_count'] = $apply_status_count->items;
            $this->_aParams['job_id'] = empty($job_id) ? $station : $job_id;

            // 查询是否有收到的简历信息

            $company_resources = base_service_company_resources_resources::getInstance($this->_userid);
            $this->_aParams['pricing_resource_data'] = $company_resources->getCompanyServiceSource(["cq_pricing_resource"]); //获取套餐资源

            $service_question = new base_service_company_question();

            if($service_question->canAnswer($this->_userid)){
                $this->_aParams['is_question'] = 1;
            }
        }
        $this->_aParams['jobs'] = "[]";
        if(count($jobs)>0) {
            $jobs_json = array();
            array_push($jobs_json, "{id:\"\",name:\"全部职位\"}");
            foreach ($jobs as $job){
                array_push($jobs_json, "{id:\"".$job['job_id']."\",name:\"".$job['station']."\"}");
            }
            $this->_aParams['jobs'] = "[".implode(',', $jobs_json)."]";
        }
        $xml = SXML::load('../config/config.xml');
        $this->_aParams['ShowResumeNum'] = $xml->ShowResumeNum;
        $this->_aParams['title'] = "推荐的简历 简历管理_{$xml->HuiBoSiteName}";

        $this->_aParams['company_id'] = $this->_userid;
        $this->_aParams['recommend_resume_num'] = $recommend_resume_num;
        //todo:添加开始，这里把类型先写死为自动推荐，上线前一定去掉，切记
        /*$this->_aParams['type'] = 1;
        $this->_aParams['resume_list'] = $this->_aParams['recommendlist'];*/
        //todo：添加结束
        return $this->render('resume/recommend/list_v2_gray.html', $this->_aParams);
    }

    public function pageAddIngore($inPath){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_id = base_lib_BaseUtils::getStr($params['resume_id'],'int',null);
        if(empty($resume_id)){
            return [];
        }
        $feedback_type = base_lib_BaseUtils::getStr($params['feedback_type'], 'string', '');
        $job_id = base_lib_BaseUtils::getStr($params['job_id'], 'int', 0);
        $remark = base_lib_BaseUtils::getStr($params['remark'], 'string', '');
        $service_person_resume_resume = new base_service_person_resume_resume();
        $company_id = $this->_userid;
        $account_id = base_lib_BaseUtils::getCookie("accountid");
        $resume_info = $service_person_resume_resume->getResume($resume_id,"resume_id,person_id");
        $person_id = $resume_info['person_id'];
        //添加反馈原因，屏蔽简历的原因
        $service_resumerecommendfeedback = new base_service_company_resume_resumerecommendfeedback();
        //$FeedText = $service_resumerecommendfeedback->getFeedbackNames($feedback_type);
        $resumeRecommendFeedBack = array(
            'job_id' => $job_id,
            'company_id' => $company_id,
            'person_id' => $person_id,
            'resume_id' => $resume_id,
            'feedback_type' => $feedback_type,
            'feedback_reason' => $remark,
            'source_type' => 1
        );
        $service_resumerecommendfeedback->addRecommendFeedback($resumeRecommendFeedBack);
        $service_company_resume_ingore = new base_service_company_resume_ingore();
        $service_company_resume_ingore->AddIngore($company_id,$account_id,$resume_id,$person_id);
    }
	
	
	/**
	 * 设置阅读状态	
	 */
	public function  pageSetRead($inPath) {
            $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
            $recommendid = base_lib_BaseUtils::getStr($params['recommendid'],'int',null);
            if(empty($recommendid)) {
                    return;
            }			
            $commendservice = new base_service_company_resume_recommend();
            $commendservice->setRead($recommendid, $this->_userid);
	}
        //获得推荐简历的状态
        private function _getStatus($is_read,$status){
            //判断是否已邀请面试
            $service_recommend_status = new base_service_common_recommendstatus();
            $recommend_status = $service_recommend_status->getRecommendStatus();
            if($status >=1){
                return array("status_id"=>$status,"status_name"=>$recommend_status[$status]);
            }
            if($is_read ==1 && !$status >=1) return array("status_id"=>0,"status_name"=>"未处理");//未处理 
            if($is_read !=1) return array("status_id"=>99,"status_name"=>"未读");//未读
            return null;
            
        } 
	// 设置推荐简历状态
	public function pageSetStatus($inPath) {
            $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
            $recommendid = base_lib_BaseUtils::getStr($params['recommendid'],'int',null);
            $status = base_lib_BaseUtils::getStr($params['status'],'int',1);
            $is_render = base_lib_BaseUtils::getStr($params['is_render'],'int',0);
            $invite_id = base_lib_BaseUtils::getStr($params['invite_id'],'int',0);
            $recommendservice = new base_service_company_resume_recommend();
            if($is_render ==1){
                if(empty($recommendid)){
                    echo "错误：推荐简历不存在";exit;
                }
                if(!$this->canDo("refuse_resume")){
                    $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
                    $this->_aParams["url"] = "/";
                    return $this->render("./common/showmsgpopedom1.html", $this->_aParams);return;
                }
                $this->_aParams['recommend_id'] = $recommendid;
                $this->_aParams['inviteid'] = $invite_id;
                
                $recommendservice->setRecommendStatus($recommendid, $status, $this->_userid);//修改简历状态
                return $this->render("./resume/recommend/notuse.html",$this->_aParams);
            }
            if(!$this->canDo("refuse_resume")){
               echo json_encode(array('status'=>false));
                return;
            }
            if(empty($recommendid)) {
                echo json_encode(array('status'=>false));
                return;
            }
            
            $recommend_info   = $recommendservice->getRecommend($recommendid, 1, "recommend_type,resume_id,job_id,status");
            $result = $recommendservice->setRecommendStatus($recommendid, $status, $this->_userid,true); //同时设置已读
            if( $result === false) {
                echo json_encode(array('status'=>false));
                return;
            }
            //如果有推荐简历 那么设置同步修改推荐简历状态
            if(intval($invite_id) >0){
                $this->__updateInviteStatus($invite_id,$status);
            }
           
            if($status == 1){
                if($recommend_info["recommend_type"] == 3 && $recommend_info["status"] == 0){
                    //采集简历
                    $service_gather_resume = new base_service_gather_taskresume();
                    $service_gather_resume->setDownLoadNum($recommend_info["resume_id"]);
                    
                }
            }
            //修改合适不合适数量
            if($recommend_info["recommend_type"] == 3 && $params["setnumber"] == 1 && $recommend_info["status"] == 0){
                $service_gather_jobwait = new base_service_gather_jobwait();
                $service_gather_jobwait->updateNumber($recommend_info["job_id"], $status);
            }
            
            echo json_encode(array('status'=>true));
            return;
	}
       //同步修改被邀请的简历状态
       private function __updateInviteStatus($invite_id,$status){
            if(base_lib_BaseUtils::nullOrEmpty($invite_id)){
                return;
            }
            $data = array();
            $data['invite_id'] = $invite_id;
            $service_recommend_status = new base_service_common_recommendstatus();
            $service_auditionresult = new base_service_company_resume_auditionresult();
            switch($status){
//                 case $service_recommend_status->invite: //面试邀请
//                     
//                     $data["state_value"] = $service_auditionresult->notset;
//                    break;;
//                case $service_recommend_status->refuseaudition: //拒绝面试
//                    $service_jobinvite = new base_service_company_resume_jobinvite();
//                    $service_jobinvite->refuseByCompany($invite_id, $this->_userid);
//                    return;
//                case $service_recommend_status->notcometoaudition: //未参加面试
//                    $data["state_value"] = $service_auditionresult->notcometoaudition;
//                    break;
//                case $service_recommend_status->notpassaudition://面试未过
//                    $data["state_value"] = $service_auditionresult->eliminate;
//                    break;
//                case $service_recommend_status->employ://已发offer
//                    $data["state_value"] = $service_auditionresult->employ;
//                    break;
//                case $service_recommend_status->refuseoffer://拒绝offer
//                    $data["state_value"] = $service_auditionresult->refuseoffer;
//                    break;
//                case $service_recommend_status->entry://已入职
//                    $data["state_value"] = $service_auditionresult->entry;
//                    break;
                case $service_recommend_status->notset:
                    $data["state_value"] = $service_auditionresult->notset;
                    break;
                case $service_recommend_status->pass:
                    $data["state_value"] = $service_auditionresult->pass;
                    break;
                case $service_recommend_status->notpass:
                    $data["state_value"] = $service_auditionresult->notpass;
                    break;
                default :
                    return;
            }
           $this->save_state_infor($data);
           return;
       }
     //修改面试结果
    private function save_state_infor($data){
        $service_invites = new base_service_company_resume_jobinvite();
        $applyService = new base_service_company_resume_apply();
        $service_invitetype = new  base_service_company_resume_invitetype();
        $result = $service_invites->update_invite_state($this->_userid,$data["invite_id"],$data["state_value"]);
        $deleteApplies = $service_invites->getInvitesByIDs($data["invite_id"],"apply_id,invite_type")->items;
        $applyids = array();
        foreach ($deleteApplies as $apply) {
            if(!base_lib_BaseUtils::nullOrEmpty($apply["apply_id"]) && 
                !in_array($apply["apply_id"], $applyids)&&$apply['invite_type']==$service_invitetype->jobapply){
                array_push($applyids, $apply["apply_id"]);
            }
        }
        if(count($applyids)>0) {
        	$result = $applyService->SetApplyStateOfIDs(implode(",", $applyids),$data["state_value"]);
        }    	
        return $result;
    }
     /**
      * 获取性别
      */
     private function getSex($sex,$default='') {
     	 if(base_lib_BaseUtils::nullOrEmpty($sex)||$sex=='0') {
            return  $default;
     	 }
     	 $enum_sex = new base_service_common_sex();
     	 return $enum_sex->getName($sex);
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
     
    /**
     * 获取居住地
     * 默认 如果是填写的重庆下的地区，不显示重庆，只显示详细地址，非重庆的 只显示一级城市和二级地区
     */
     private function getArea($area_id,$default='',$isShowAll=false) {
     	if(base_lib_BaseUtils::nullOrEmpty($area_id)) {
     		return $default;
     	}
     	$service_area = new base_service_common_area();
     	$areas = $service_area->getTopAreaByAreaID($area_id);
     	$areas = array_reverse($areas);
     	$count  = count($areas);
     	if($count<=0) {
     		return $default;
     	}
     	$names  = array();
     	if($isShowAll) {
     	  for($i= 0;$i<$count;$i++) {
            $area = $areas[$i];
            array_push($names, $area['area_name']);	
        }     		
     	}else {
            $isChongqing = false;
            for($i= 0;$i<$count;$i++) {
     		$area = $areas[$i];
                if($i==0) {
                    if($count==1) {
                        array_push($names, $area['area_name']);
                        continue;
                    }
                    if($area['area_id']=='0300') {
                        $isChongqing = true;
                        continue;
                    }
                }
                if(!$isChongqing&&$i>=2) {
                    break;
                }
                array_push($names, $area['area_name']);	
            }       			
     	}
     	return implode('-', $names);
     } 

     /**
      *  获取显示的申请状态
      */
     private function getStatus($apply,$resume) {
     	if($resume['is_effect']==0) return 6;
     	if($apply['is_cancelled']==1) return 5;
     	if($apply['re_status']==1) return 1;
     	if($apply['re_status']==3) return 3;
     	if($apply['has_read']==1) return 4;
     	return 2;
     }
     
     /**
      * 状态信息
      */
     private function getStatusName($status) {
     	if($status==6) return '对方删除简历';
     	if($status==5) return '对方放弃';
     	if($status==4) return '已读';
     	if($status==3) return '已婉拒';
     	if($status==2) return '未读';
     	return '已邀请';     	
     	
     }
	
	/**
	 * 
	 * 获取数组里对象的属性集合
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
    * 数组查询
    * @param array $arr
    * @param string $key
    * @param string $value
    */
   private function arrayFind($arr,$property,$value) {
        $obj = null;
        if(!base_lib_BaseUtils::nullOrEmpty($arr)){
            foreach ($arr as $item){
                if($item[$property]==$value) {
                    $obj =  $item;
                    break;
                }
            }
        }
        return  $obj;
   }
   /**
    * 数组查询
    * @return 返回数据
    */
	private function arrayFindAll($arr,$property,$value){
   	   $new_arr = array();
            if(!base_lib_BaseUtils::nullOrEmpty($arr)){
                foreach ($arr as $item){
                     if($item[$property]==$value) {
                         array_push($new_arr, $item);
                     }
                }
            }
	   return  $new_arr;
	}
	/**
	 * 获取职位信息并排序
	 * @param  $job_ids
	 */
	private function _getJobsAndSort($job_ids, $companyid){
		$service_job = new base_service_company_job_job();
		$jobs = $service_job->getJobs($job_ids, 'job_id,station,end_time,status');
		$validJob = array();
		$voidJob = array();
		$status = new base_service_common_jobstatus();
		foreach ($jobs as $job) {
			if(empty($job['station'])) {
				continue;
			}			
			if($job['status']!=$status->use||base_lib_TimeUtil::time_diff_day($job['end_time'], date('Y-m-d H:i:s'))>0) {
				$job['station'] =  base_lib_BaseUtils::cutstr($job['station'], 8,'utf-8','','...')."<span class='orange'>(停招)</span>";
				array_push($voidJob, $job);
			}else {
				$job['station'] =  base_lib_BaseUtils::cutstr($job['station'], 10,'utf-8','','...');
				array_push($validJob, $job);
			}
		}
		$recommendservice = new  base_service_company_resume_recommend();
	 	$stations = $recommendservice->getNoPublishStation($companyid);
		$noPublishStations = array();
	 	if(count($stations)>0) {
	 		foreach ($stations as $station) {
	 			array_push($noPublishStations, array('job_id'=>$station['station'], 'station'=>base_lib_BaseUtils::cutstr($station['station'], 10,'utf-8','','...')));
	 		}
	 	}
		return array_merge($validJob, $voidJob, $noPublishStations);
	}	
        
       /**
        *@desc 查看推荐的简历
        */
        public function pageRecommendResumeInfo($inPath){
            if(!$this->canDo("see_resume_info")){
                $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
                $this->_aParams["url"] = "/";
                return $this->render("./common/showmsgpopedom.html", $this->_aParams);
            }
            $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
            $resume_id = base_lib_BaseUtils::getStr($pathdata['resumeid'],'int',0);
            $origin = base_lib_BaseUtils::getStr($pathdata['type'],'string','network');
            $resumesrc = base_lib_BaseUtils::getStr($pathdata['src'],'string','network');
			$recommendid = base_lib_BaseUtils::getStr($pathdata['recommendid'],'int',0);
            if ($resume_id == 0) {
                $this->_aParams['msg'] = '您查看的简历不存在或未向您公开';
                return $this->render('./resume/msg.html', $this->_aParams); ;
            }
           
            $this->_aParams['origin'] = $origin;
            $this->_aParams['recommendid'] = $recommendid;
            $this->_aParams['src'] = $resumesrc;
            $this->_aParams['resume_id'] = $resume_id;
            //检查简历是否存在当前用户推荐简历编号中
            $service_recommend = new base_service_company_resume_recommend();
            $company_id = $this->_userid;
            //获得简历的 推荐职位
            $recommend_station = $service_recommend->getRecommend($recommendid,true,'recommend_id,station,is_read,status,recommend_type');
            if(empty($recommend_station)){
                $this->_aParams['msg'] = '您查看的简历不不在您的推荐列表中';
                return $this->render('./resume/msg.html', $this->_aParams);
            }
            $this->_reommend_type = $recommend_station["recommend_type"];
            $this->_aParams['recommendid'] =$recommend_station['recommend_id'];
            $this->_aParams['recommend_station'] = $recommend_station['station'];
            //根据简历ID 获得简历的邀请记录
            $invite_id = 0;
            $items = "invite_id,audition_result";
            $service_invites = new base_service_company_resume_jobinvite();
            $invite_result =  $service_invites->getLatestInvite($resume_id,$this->_userid, $items);
            if(!base_lib_BaseUtils::nullOrEmpty($invite_result)){
                $invite_id = $invite_result['invite_id'];//如果已被邀请 返回邀请的记录ID
            }
            $resume_status = $this->_getStatus($recommend_station['is_read'], $recommend_station['status']);
            $this->_aParams['invite_id'] = $invite_id;
            $this->_aParams['resume_status'] = $resume_status;
            //判断简历逻辑 todo  简历是否公开 等等 等等
            //获取简历的详细信息
            $is_show_name = false; //是否显示简历的名字
            $is_show_linkway = false;//是否显示联系方式  其中有是否下载
            $member_info = false;//是否是会员
            $member_expires = false;//会员是否过期
            $is_show_resumeinfo = false;//是否显示详细信息
            $mobile_phone = "";
            $email = "";
            $result = $this->checkResume($resume_id, $is_show_name, $is_show_linkway, $member_info, $member_expires,$is_show_resumeinfo);
            if(!$result){
		    $this->_aParams['msg'] = '您查看的简历不存在或未向您公开';
                return $this->render('./resume/msg.html', $this->_aParams);
            }
            $service_company=new base_service_company_company();
            $service_company_resources_resources = base_service_company_resources_resources::getInstance($this->_userid);
            $company_resources_info = $service_company_resources_resources->getCompanyAuditStatusV2();

            $isshowresumeinfo = true;
            $letter_info = $this->CheckCompanyLetter($this->_userid,'resume');
            $this->_aParams['letter_info'] = $letter_info;
            if($company_resources_info['audit_type'] == 1){
                //老规则
                $isshowresumeinfo = $service_company->isShowResumeInfo($this->_userid);
            }else{
                //新规则
                if($letter_info['code'] != 200){
                    $isshowresumeinfo = false;
                }
            }
            $resumeservice = new base_service_person_resume_resume();
            //根据简历编号 获得简历信息
            $resume = $resumeservice->getResume($resume_id, 'point,person_id');
            if(!$is_show_resumeinfo) {
                $resumelevelservice = new base_service_common_resumelevel();
                $level = $resumelevelservice->getLevelByPoint($resume['point']);
                
                $this->_aParams['resumelevel'] = $level;
            }
            /**===============照片===start=================**/
            $album_service = new base_service_person_resume_album();
            $albums = $album_service->getAlbums($resume_id, "id,photo");
            $this->_aParams['resume_albums'] = $albums;
            /**===============照片=== end =================**/
            $this->_aParams['is_show_linkway'] = $is_show_linkway;
            $this->_aParams['member_info'] = $member_info;
            $this->_aParams['member_expires'] = $member_expires;
            $this->_aParams['is_show_resumeinfo'] = $is_show_resumeinfo;
            $this->_aParams['isshowresumeinfo'] = $isshowresumeinfo;
            //获得简历的电话 及邮箱
            $this->_aParams['mobile_phone'] = $mobile_phone;
            $this->_aParams['email'] = $email;
            $this->_aParams['recommend_status'] = $recommend_station['status'];
            //修改简历状态  显示为已读
			$service_recommend->setRead($recommend_station['recommend_id']);
            $this->_fill($resume_id, $is_show_name, $is_show_linkway, $not_member, $member_expires,false,$is_show_resumeinfo);
            $this->_getResumeHistoryRecord($this->_userid, $resume_id);
            $this->_aParams['recommend_id'] = $recommend_station['recommend_id'];
            $xml = SXML::load('../config/config.xml');
            $this->_aParams['title']= $this->_aParams['user_name']."-{$xml->HuiBoSiteName}";
            $serviceArea = new base_service_company_service_serviceArea();
            //已超出简历下载地区限制，请联系工作人员开通相应招聘服务。
            $not_area_limit = $serviceArea->IsServiceAreaTypeDownloadResumeScope($this->_userid,$resume_id);//限制为false
            $this->_aParams['not_area_limit'] = $not_area_limit;

            $company_resources = base_service_company_resources_resources::getInstance($this->_userid);
            $this->_aParams['isCqNewService'] = $company_resources->getCompanyServiceSource(["cq_pricing_resource"])['isCqNewService']; //是否为新套餐资源
            
            //如果该简历是第一次查看，并且属于采集的 则发送短信
            if($recommend_station["recommend_type"] == 3 && $recommend_station["is_read"] == 0){
                $this->_sendMsgToPerson($resume["person_id"]);
            }
            //设置简历查看次数
            if($recommend_station["recommend_type"] == 3){
                $service_gather_resume = new base_service_gather_taskresume();
                $service_gather_resume->setClickNum($resume_id);
            }
            $this->_aParams["recommend_type"] = $recommend_station["recommend_type"];
            return $this->render("resume/recommend/recommendresumedetail_new.html", $this->_aParams);
        }
        
    public function pageRecommendResumeInfoV2($inPath){
            if(!$this->canDo("see_resume_info")){
                $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
                $this->_aParams["url"] = "/";
                return $this->render("./common/showmsgpopedom.html", $this->_aParams);
            }
            $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
            $resume_id = base_lib_BaseUtils::getStr($pathdata['resumeid'],'int',0);
            if ($resume_id == 0) {
                $this->_aParams['msg'] = '您查看的简历不存在或未向您公开';
                return $this->render('./resume/msg.html', $this->_aParams); ;
            }
            $this->_aParams['resume_id'] = $resume_id;
            //检查简历是否存在当前用户推荐简历编号中
            $company_id = $this->_userid;
            //根据简历ID 获得简历的邀请记录
            //判断简历逻辑 todo  简历是否公开 等等 等等
            //获取简历的详细信息
            $is_show_name = false; //是否显示简历的名字
            $is_show_linkway = false;//是否显示联系方式  其中有是否下载
            $member_info = false;//是否是会员
            $member_expires = false;//会员是否过期
            $is_show_resumeinfo = false;//是否显示详细信息
            $mobile_phone = "";
            $email = "";
            $result = $this->checkResume($resume_id, $is_show_name, $is_show_linkway, $member_info, $member_expires,$is_show_resumeinfo);
//            if(!$result){
//		    $this->_aParams['msg'] = '您查看的简历不存在或未向您公开';
//                return $this->render('./resume/msg.html', $this->_aParams);
//            }
            $service_company=new base_service_company_company();
            $service_company_resources_resources = base_service_company_resources_resources::getInstance($this->_userid);
            $company_resources_info = $service_company_resources_resources->getCompanyAuditStatusV2();

            $isshowresumeinfo = true;
            $letter_info = $this->CheckCompanyLetter($this->_userid,'resume');
            $this->_aParams['letter_info'] = $letter_info;
            if($company_resources_info['audit_type'] == 1){
                //老规则
                $isshowresumeinfo = $service_company->isShowResumeInfo($this->_userid);
            }else{
                //新规则
                if ($letter_info['code'] != 200
                    && ($company_resources_info['is_audit'] != 1)) {
                    $isshowresumeinfo = false;
                }
            }

            $resumeservice = new base_service_person_resume_resume();
            //根据简历编号 获得简历信息
            $resume = $resumeservice->getResume($resume_id, 'point,person_id');
            if(!$is_show_resumeinfo) {
                $resumelevelservice = new base_service_common_resumelevel();
                $level = $resumelevelservice->getLevelByPoint($resume['point']);
                
                $this->_aParams['resumelevel'] = $level;
            }
            /**===============照片===start=================**/
            $album_service = new base_service_person_resume_album();
            $albums = $album_service->getAlbums($resume_id, "id,photo");
            $this->_aParams['resume_albums'] = $albums;
            /**===============照片=== end =================**/
            $this->_aParams['is_show_linkway'] = $is_show_linkway;
            $this->_aParams['member_info'] = $member_info;
            $this->_aParams['member_expires'] = $member_expires;
            $this->_aParams['is_show_resumeinfo'] = $is_show_resumeinfo;
            $this->_aParams['isshowresumeinfo'] = $isshowresumeinfo;
            //获得简历的电话 及邮箱
            $this->_aParams['mobile_phone'] = $mobile_phone;
            $this->_aParams['email'] = $email;
            $this->_aParams['recommend_status'] = $recommend_station['status'];
            $this->_fill($resume_id, $is_show_name, $is_show_linkway, $not_member, $member_expires,false,$isshowresumeinfo);
            $this->_getResumeHistoryRecord($this->_userid, $resume_id);
            $xml = SXML::load('../config/config.xml');
            $this->_aParams['title']= $this->_aParams['user_name']."-{$xml->HuiBoSiteName}";
            $serviceArea = new base_service_company_service_serviceArea();
            //已超出简历下载地区限制，请联系工作人员开通相应招聘服务。
            $not_area_limit = $serviceArea->IsServiceAreaTypeDownloadResumeScope($this->_userid,$resume_id);//限制为false
            $this->_aParams['not_area_limit'] = $not_area_limit;

            $company_resources = base_service_company_resources_resources::getInstance($this->_userid);
            $this->_aParams['isCqNewService'] = $company_resources->getCompanyServiceSource(["cq_pricing_resource"])['isCqNewService']; //是否为新套餐资源

            $this->_aParams["recommend_type"] = $recommend_station["recommend_type"];
            return $this->render("resume/resume_cleare.html", $this->_aParams);
    }
    public function pageRecommendResumeInfoChat($inPath){
        if(!$this->canDo("see_resume_info")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_id = base_lib_BaseUtils::getStr($pathdata['resumeid'],'int',0);
        if ($resume_id == 0) {
            $this->_aParams['msg'] = '您查看的简历不存在或未向您公开';
            return $this->render('./resume/msg.html', $this->_aParams);
        }
        $this->_aParams['resume_id'] = $resume_id;
        //检查简历是否存在当前用户推荐简历编号中
        $company_id = $this->_userid;
        //根据简历ID 获得简历的邀请记录
        //判断简历逻辑 todo  简历是否公开 等等 等等
        //获取简历的详细信息
        $is_show_name = false; //是否显示简历的名字
        $is_show_linkway = false;//是否显示联系方式  其中有是否下载
        $member_info = false;//是否是会员
        $member_expires = false;//会员是否过期
        $is_show_resumeinfo = false;//是否显示详细信息
        $mobile_phone = "";
        $email = "";

        $result = $this->checkResume($resume_id, $is_show_name, $is_show_linkway, $member_info, $member_expires,$is_show_resumeinfo);
//            if(!$result){
//		    $this->_aParams['msg'] = '您查看的简历不存在或未向您公开';
//                return $this->render('./resume/msg.html', $this->_aParams);
//            }
        $service_company=new base_service_company_company();
        $service_company_resources_resources = base_service_company_resources_resources::getInstance($this->_userid);
        $company_resources_info = $service_company_resources_resources->getCompanyAuditStatusV2();

        $isshowresumeinfo = true;
        $letter_info = $this->CheckCompanyLetter($this->_userid,'resume');
        $this->_aParams['letter_info'] = $letter_info;
        if($company_resources_info['audit_type'] == 1){
            //老规则
            $isshowresumeinfo = $service_company->isShowResumeInfo($this->_userid);
        }else{
            //新规则
            //新规则
            if ($letter_info['code'] != 200
                && ($company_resources_info['is_audit'] != 1)) {
                $isshowresumeinfo = false;
            }
        }
        $resumeservice = new base_service_person_resume_resume();
        //根据简历编号 获得简历信息
        $resume = $resumeservice->getResume($resume_id, 'point,person_id,is_salary_show,salary,is_effect');
        if(0==$resume['is_effect']){
            $this->_aParams['msg'] = '您查看的简历不存在或未向您公开';
            return $this->render('./resume/msg.html', $this->_aParams); ;
        }
        if(!$is_show_resumeinfo) {
            $resumelevelservice = new base_service_common_resumelevel();
            $level = $resumelevelservice->getLevelByPoint($resume['point']);

            $this->_aParams['resumelevel'] = $level;
        }
        /**===============照片===start=================**/
        $album_service = new base_service_person_resume_album();
        $albums = $album_service->getAlbums($resume_id, "id,photo");
        $this->_aParams['resume_albums'] = $albums;
        /**===============照片=== end =================**/
        $this->_aParams['is_show_linkway'] = $is_show_linkway;
        $this->_aParams['member_info'] = $member_info;
        $this->_aParams['member_expires'] = $member_expires;
        //$this->_aParams['is_show_resumeinfo'] = $is_show_resumeinfo;
        $this->_aParams['isshowresumeinfo'] = $isshowresumeinfo;
        //获得简历的电话 及邮箱
        $this->_aParams['mobile_phone'] = $mobile_phone;
        $this->_aParams['email'] = $email;
        $this->_aParams['recommend_status'] = $recommend_station['status'];
        $this->_fill($resume_id, $is_show_name, $is_show_linkway, $not_member, $member_expires,true,$isshowresumeinfo);
        $this->_getResumeHistoryRecord($this->_userid, $resume_id);
        $xml = SXML::load('../config/config.xml');
        $this->_aParams['title']= $this->_aParams['user_name']."-{$xml->HuiBoSiteName}";
        $serviceArea = new base_service_company_service_serviceArea();
        //已超出简历下载地区限制，请联系工作人员开通相应招聘服务。
        $not_area_limit = $serviceArea->IsServiceAreaTypeDownloadResumeScope($this->_userid,$resume_id);//限制为false
        $this->_aParams['not_area_limit'] = $not_area_limit;
        if($resume['is_salary_show']==0){
            if($resume['salary']){
                $this->_aParams['exp_salary']=$resume['salary'];
            }else{
                $this->_aParams['exp_salary']='negotiable';
            }
        }else{
            $this->_aParams['exp_salary']='negotiable';
        }
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid);
        $this->_aParams['isCqNewService'] = $company_resources->getCompanyServiceSource(["cq_pricing_resource"])['isCqNewService']; //是否为新套餐资源

        $this->_aParams["recommend_type"] = $recommend_station["recommend_type"];

        return $this->render("resume/resume_cleare_chat.html", $this->_aParams);
    }

    
    //企业第一次查看采集推荐的简历 则发送短信
    private function _sendMsgToPerson($person_id){
        if(empty($person_id)){
            return false;
        }
        $service_person     = new base_service_person_person();
        $service_company    = new base_service_company_company();
        $person_info = $service_person->getPerson($person_id,"user_name,mobile_phone");
        
        if(empty($person_info) || empty($person_info["mobile_phone"])){
            return false;
        }
        
        $company_info = $service_company->getCompany($this->_userid, 1, "company_name,company_shortname");
        if(empty($company_info)){
            return false;
        }
        $company_name = !empty($company_info["company_shortname"]) ? $company_info["company_shortname"] : $company_info["company_name"];
        //获取APP下载链接的短地址
        $app_down_url ="http://app2.huibo.com/index/download";
        //生成短地址
        $xml = SXML::load('../config/company/company.xml');
        $shorturl_service = new base_service_common_shorturl();
        $shorturl['create_time'] = date('Y-m-d H:i:s',time());
        $shorturl['type'] = 4;
        $shorturl["url"]  = $app_down_url;
        $url_key    = $shorturl_service->shorturlAdd($shorturl);
        $short_url  = $xml->ShortUrlDomain.$url_key;
        $content = $person_info["user_name"].",{$company_name}刚刚查看了您的简历，点击查看详情 {$short_url}";
        
        base_lib_SMS::send($person_info["mobile_phone"], $content);
        //base_lib_SMS::send(15023634995, $content);
    }
        
    /**
	 *
	 * 验证简历
	 * @param  $resume_id
	 * @param  $is_show_name
	 * @param  $is_show_linkway
	 * @param  $not_member
	 * @param  $member_expires
	 */
	private function checkResume($resume_id, &$is_show_name, &$is_show_linkway, &$member_info, &$member_expires, &$is_show_resumeinfo) {
		$service_company = new base_service_company_company();
		$service_person  = new base_service_person_person();
		$service_resume  = new base_service_person_resume_resume();
		$result = true;
		$resume = $service_resume->getResume($resume_id, 'resume_id,person_id');
		if (empty($resume)) {
			return false;
		}
		$person = $service_person->getPerson($resume['person_id'], 'name_open,user_name,user_name_en,open_mode');
		if (empty($person)) {
			return false;
		}
		if ($this->_getFilter($resume['person_id'])) {
			return false;
		}
//		if ($person['name_open'] == 1) {
//			$is_show_name = true;
//		}

		$service_apply    = new base_service_company_resume_apply();
		$service_download = new base_service_company_resume_download();

		// 验证求职者是否投递过企业的职位
        $accountid = base_lib_BaseUtils::getCookie('accountid');
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
		$relate_resume_id  = empty($resume['relate_resume_id']) ? 0 : $resume['relate_resume_id'];
		if ($service_apply->isApply($company_resources->all_accounts, $resume['resume_id'])) {
			$is_show_linkway    = true;
			$is_show_name       = true;
			$is_show_resumeinfo = true;
		} else if ($service_apply->isApply($company_resources->all_accounts, $relate_resume_id)) {
			$is_show_linkway    = true;
			$is_show_name       = true;
			$is_show_resumeinfo = true;
		} else {
			$enum_openmode = new base_service_common_openmode();
            //采集推荐的企业不是公开也可以查看
//            if($this->_reommend_type != 3){
//                if ($person['open_mode'] == $enum_openmode->notopen) {
//                    return false;
//                }
//            }
		}
        if(!$is_show_linkway) {
            $privilege = $service_person->checkMobilesPrivilege(array($resume_id), $this->_userid);
            $is_show_linkway = $privilege[$resume_id];

        }
		if(!$is_show_linkway){
            $service_linkwayget = new base_service_company_appraise_linkwayget();
            $is_show_linkway = $service_linkwayget->checkIsGet($this->_userid, $resume['person_id']);
        }
        //如果是投递或者下载的简历 显示姓名
        $is_show_name = $privilege[ $resume_id ];

		return $result;
	}
     
    /**
	 * 单位是否在求职者屏蔽的关键字当中
	 */
	public function _getFilter($person_id){
		$service_blench = new base_service_person_blench();
		$is_filter = false; 
		$blenchs = $service_blench->getAllBlenchKeyList($person_id,'person_id,type,com_keyword,company_id');
		$blenchs_items = $blenchs->items;
 
		 if(!base_lib_BaseUtils::nullOrEmpty($blenchs_items)){
		 	for($i =0 ; $i< count($blenchs_items);$i++){
		 		if($blenchs_items[$i]['type']==0){
			 		if ($this->_getKeySetResult($blenchs_items[$i]['com_keyword'])){
			 			$is_filter = true;
			 			break;
			 		}
		 		}
		 		else{
		 			if($blenchs_items[$i]['company_id']==$this->_userid){
		 				$is_filter = true;
			 			break;
		 			}
		 		}
		 	}
		 }
		return $is_filter;
	}
        	//  获取关键字结果
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
			//return in_array($this->_userid,$result['companys'])==true;
		}else{
			return false;
		}
	}
        /**
	 *
	 * 填充数据
	 * @param  $resume_id
	 * @param  $show_name
	 * @param  $show_linkway
	 * @param  $not_member
	 * @param  $member_expires
	 */
	private function _fill($resume_id,$show_name,$show_linkway,$member_info,$member_expires,$chkIsHideSalary=false,$is_show_resumeinfo = false) {
		
		if($resume_id==0){
			return;
		}
		$service_resume = new base_service_person_resume_resume();
		$service_resume->dbquery = true; //load data from subordinate database
		//获取简历
		$resume = $service_resume->getResume($resume_id,'resume_id,person_id,degree_id,station,is_salary_show,salary,appraise,expect_job_level,is_not_accept_lower_job_level,is_not_accept_lower_salary,job_type,is_accept_parttime,update_time,point');
		if(base_lib_BaseUtils::nullOrEmpty($resume)){
			return;
		}
		$service_person = new base_service_person_person();
		$service_person->dbquery = true; //load data from subordinate database
		//获取用户
		$person = $service_person->getPerson($resume['person_id'],'person_id,user_name,sex,email,cur_area_id,has_big_photo,big_photo,photo,photo_open,name_open,birthday2,mobile_phone,mobile_phone_is_validation,email_is_validation,telephone,qq,qq_open,tel_open,email_open,stature,marriage,avoirdupois,political_status,native_place_id,fertility_circumstance,start_work,login_time,job_state_id,accession_time,person_class,password');
		if(base_lib_BaseUtils::nullOrEmpty($person)){
			return;
		}
		
		//判断是否二类简历或者一类简历但是未设密码
		if($person['person_class'] =='2'){
			$this->_aParams['show_person_class_tip'] = 1;
		}
		else{
			$this->_aParams['show_person_class_tip'] = 0;
		}

		// 获取简历等级
		$resumelevelservice = new base_service_common_resumelevel();
		$this->_aParams['resumelevel'] = $resumelevelservice->getLevelByPoint($resume['point']);
		
		//企业未验证提示信息 sx 7.16
		$companyauditService = new base_service_common_companyaudit();
		$is_audit = $companyauditService->getNotice($this->_userid);
		$this->_aParams['is_audit'] = str_replace("X",$this->_aParams['resumelevel']['consume'],$is_audit);
		
		// 新增简历访问记录
		$this->_addVisit($resume_id, $person['person_id'], $this->_userid);
		$this->_aParams['resume_id'] = $resume_id;
		$this->_aParams['person_id'] = $person['person_id'];
		$this->_aParams['company_id'] = $this->_userid;
		//$this->_aParams['resume_update_time'] = date('Y-m-d ',strtotime($resume['update_time']));
		$this->_aParams['last_login_time'] = date('Y-m-d H:i',strtotime($person['login_time']));
		//$this->_aParams['is_chinese_resume'] = $resume['is_chinese_resume'];
		//$this->_aParams['relate_resume_id'] = $resume['relate_resume_id'];
		//是否显示简历详细信息
		$service_company=new base_service_company_company();
//		if($is_show_resumeinfo) {
//                    $isshowresumeinfo = true;
//		}else {
//                    $isshowresumeinfo = $service_company->isShowResumeInfo($this->_userid);
//		}
        $service_company_resources_resources = base_service_company_resources_resources::getInstance($this->_userid);
        $company_resources_info = $service_company_resources_resources->getCompanyAuditStatusV2();

        //是否显示简历详细信息
        //$isshowresumeinfo = $service_company->isShowResumeInfo($this->_userid);
        $isshowresumeinfo = true;
        $letter_info = $this->CheckCompanyLetter($this->_userid);
        $this->_aParams['letter_info'] = $letter_info;
        if($company_resources_info['audit_type'] == 1){
            //老规则
            //$isshowresumeinfo = $service_company->isShowResumeInfo($this->_userid);
            $isshowresumeinfo = true;
        }else{
            if ($letter_info['code'] != 200
                && ($company_resources_info['is_audit'] != 1)) {
                $isshowresumeinfo = false;
            }
        }


		//姓名
		if($show_name=='1' && $isshowresumeinfo){
                    $this->_aParams['user_name']=$person['user_name'];
		}else{
                    if($person['name_open']==1 && $isshowresumeinfo){
                        $this->_aParams['user_name']=$person['user_name'];
                    }else{
                        $sex_name = $person['sex']==1?'先生':'女士';
                        $this->_aParams['user_name']=mb_substr($person['user_name'],0,1,'utf-8').$sex_name;
                    }
		}

		$this->_aParams['appraise'] = $resume['appraise'];
		//头像，性别。年龄，学历，工作经验，职位类别，接受兼职，低薪资，低岗位，自我评价
		$this->_aParams['avatar'] = '';
		if($person['photo_open']!='0' && $isshowresumeinfo){
                    if(!base_lib_BaseUtils::nullOrEmpty($person['photo'])){
                        $avatar = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP."{$person['photo']}";
                        if($person['has_big_photo']=='1'){
                            $avatar_big = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP."{$person['big_photo']}";
                            $this->_aParams['avatar_big'] = $avatar_big;
                            $this->_aParams['avatar'] =$avatar;
                        }else{
                            $this->_aParams['avatar'] = $avatar;
                        }
                    }
		}
		$this->_aParams['sex']=$person['sex'];
		$this->_aParams['age']= base_lib_TimeUtil::ceil_diff_year($person['birthday2']).'岁';
        $this->_aParams['degree']=$resume['degree_id'];
        $this->_aParams['job_state_id'] = $person['job_state_id'];
        $this->_aParams['job_state_text'] = (new base_service_common_applystatus())->getName($person['job_state_id']);
		$this->_aParams['accession_time']=$person['accession_time'];
		$basic_start_work_year = base_lib_TimeUtil::date_diff_month($person['start_work']);
		$workY = floor($basic_start_work_year/12);
		$workM = intval($basic_start_work_year%12);

		if($workY<=0 && $workM<=6&&$workM>=-6){
                    $basic_start_work_year = '应届毕业生';
		}else if($workY == 0 && $workM>6){
                    $basic_start_work_year = $workM.'个月工作经验';
		}else if($basic_start_work_year<-6){
                    $basic_start_work_year = '目前在读';
		}else{
                    $basic_start_work_year = $workY.'年工作经验';
		}
		$this->_aParams['work_year'] = $basic_start_work_year;
		$this->_aParams['job_type'] = $resume['job_type'];
		$this->_aParams['is_accept_parttime'] = $resume['is_accept_parttime'];
		$this->_aParams['is_not_accept_lower_salary'] = $resume['is_not_accept_lower_salary'];
		$this->_aParams['is_not_accept_lower_job_level'] = $resume['is_not_accept_lower_job_level'];
		$this->_aParams['appraise'] = $resume['appraise'];
			
		$service_area = new base_service_common_area();
			
		//当前所在地
		$cur_area_name = '';
		if($person['cur_area_id']!=''){
                    $cur_area_arr = $service_area->getTopAreaByAreaID($person['cur_area_id']);
                    $cur_area_count = count($cur_area_arr);
                    for ($j = $cur_area_count-1; $j >= 0; $j--) {
                        if($j==0){
                            $cur_area_name .= $cur_area_arr[$j]['area_name'];
                        }else{
                            $cur_area_name .= $cur_area_arr[$j]['area_name'].'-';
                        }
                    }
		}
		$this->_aParams['cur_area_name'] = $cur_area_name;
		//$this->_aParams['job_state'] = $person['job_state_id'];
		//$this->_aParams['accession_time'] = $resume['accession_time'];
		$this->_aParams['exp_job'] = $resume['station'];
		$this->_aParams['exp_job_level'] = $resume['expect_job_level'];
		/*if($resume['is_salary_show']==0){
			$this->_aParams['exp_salary']=$resume['salary'];
			}else{
			$this->_aParams['exp_salary']='negotiable';
			}*/
		//用于邮件简历
		if($chkIsHideSalary==false){
                    if($resume['is_salary_show']==0){
                        $this->_aParams['exp_salary']=$resume['salary'];
                    }else{
                        $this->_aParams['exp_salary']='negotiable';
                    }
		}
		
		//获取意向行业类别
		$service_callingexp = new base_service_person_resume_callingexp();
		$service_calling = new base_service_common_calling();
		$callings = $service_callingexp->getResumeCallingexpList($resume['resume_id'],'resume_id,calling_id');
		$calling_items = $callings->items;
		if(!base_lib_BaseUtils::nullOrEmpty($calling_items)){
                    $str_expect_callings = '';
                    for ($g = 0; $g < sizeof($calling_items); $g++) {
                        if(sizeof($calling_items)==1||$g == sizeof($calling_items)-1){
                                $str_expect_callings .= $service_calling->getCallingName($calling_items[$g]['calling_id']);
                        }else{
                                $str_expect_callings .= $service_calling->getCallingName($calling_items[$g]['calling_id']).';';
                        }
                    }
                    $this->_aParams['str_expect_callings'] = $str_expect_callings;
		}
			
		//获取意向地区
		$service_areaexp = new base_service_person_areaexp();
		$service_areaexp->dbquery = true; //load data from subordinate database
		$areas = $service_areaexp->getResumeAreaexpList($resume['resume_id'],'person_id,resume_id,area_id');
		$area_items = $areas->items;
		if(!base_lib_BaseUtils::nullOrEmpty($area_items)){
                    $area_name = '';
                    $area_ids = '';
                    $area_ids_arr = array();
                    for ($i = 0; $i < sizeof($area_items); $i++) {
                        $area = $service_area->getArea($area_items[$i]['area_id'],false);
                        if($i == sizeof($area_items)-1){
                            $area_ids .= $area['area_id'];
                        }else{
                            $area_ids .= $area['area_id'].',';
                        }
                        array_push($area_ids_arr, $area['area_id']);
                    }
                    $area_name = $this->getSpecialAreaName($service_area,$area_ids_arr,$area_ids);
		}

		$this->_aParams['exp_area_names'] = $area_name;

		//获取意向职位类别
		$service_jobsort = new base_service_common_jobsort();
		$service_jobsortexp = new base_service_person_resume_jobsortexp();
		$jobsorts = $service_jobsortexp->getResumeJobsortexpList($resume_id,'resume_id,jobsort');
		$jobsort_items = $jobsorts->items;
		if(!base_lib_BaseUtils::nullOrEmpty($jobsort_items)){
                    $str_expect_jobsorts = '';
                    $expect_jobsort_ids = '';
                    for ($i = 0; $i < sizeof($jobsort_items); $i++) {
                        if(sizeof($jobsort_items)==1||$i == sizeof($jobsort_items)-1){
                            $str_expect_jobsorts .= $service_jobsort->getJobsortName($jobsort_items[$i]['jobsort']);
                            $expect_jobsort_ids .= $jobsort_items[$i]['jobsort'];
                        }else{
                            $str_expect_jobsorts .= $service_jobsort->getJobsortName($jobsort_items[$i]['jobsort']).',';
                            $expect_jobsort_ids .= $jobsort_items[$i]['jobsort'].',';
                        } 
                    }
                    $this->_aParams['expect_jobsort_ids'] = $expect_jobsort_ids;
                    $this->_aParams['str_expect_jobsorts'] = $str_expect_jobsorts;
		}


		/********************联系方式****************************/
		//if ($isshowresumeinfo) {//非会员和未认证都显示不完整联系方式 sx 7.16 
            //手机号
            if ($show_linkway) {
				$this->_aParams['mobile_phone'] = $person['mobile_phone'];
				$this->_aParams['qq']           = $person['qq'];
				$this->_aParams['email']        = $person['email'];
            } else {
				$this->_aParams['mobile_phone'] = preg_replace('#(\d{3})[a-z0-9-]{2,10}(\d{3})#', '${1}****${2}', $person['mobile_phone']);
				$this->_aParams['qq']           = preg_replace('#(\d{3})[a-z0-9-]{2,10}(\d{3})#', '${1}****${2}', $person['qq']);
				$this->_aParams['email']        = preg_replace('#(\w{3})[a-z0-9-]+@#', '${1}****@', $person['email']);
            }
			$this->_aParams['mobile_phone_valid']  = $person['mobile_phone_is_validation'];
			$this->_aParams['email_is_validation'] = $person['email_is_validation'];
			//固话
			$this->_aParams['telephone']      = $person['telephone'];
			//QQ
			$this->_aParams['show_linkway']   = $show_linkway;
			$this->_aParams['member_info']	  = $member_info;
		//}
		/********************联系方式****************************/    
		//户籍
		$native_place_name = '';
		if($person['native_place_id']!=''){
                    $native_place = new base_service_common_area();
                    $native_place_arr = $native_place->getTopAreaByAreaID($person['native_place_id']);
                    $native_place_arr_count = count($native_place_arr);
                    for ($j = $native_place_arr_count-1; $j >= 0; $j--) {
                        if($j==0){
                            $native_place_name .= $native_place_arr[$j]['area_name'];
                        }else{
                            $native_place_name .= $native_place_arr[$j]['area_name'].'-';
                        }
                    }
		}
		$this->_aParams['native_place_name'] = $native_place_name;
		//政治面貌
		$this->_aParams['political_status'] = $person['political_status'];
		//身高
		$this->_aParams['stature'] = $person['stature'];
		//体重
		$this->_aParams['avoirdupois'] = $person['avoirdupois'];
		//婚否
		//$this->_aParams['marriage_open'] = $person['marriage_open'];
		//if($person['marriage_open']=='1'){
		$this->_aParams['marriage'] = $person['marriage'];
		$this->_aParams['fertility'] = $person['fertility_circumstance'];
		//}

		//亮点标签
//		$service_highlight = new base_service_person_resume_highlight();
//		$service_highlight->dbquery = true; //load data from subordinate database
//		$highlight = $service_highlight->getResumeHighlightList($resume['resume_id'], 'light_desc');
//		$highlight_items = $highlight->items;
//		if(!base_lib_BaseUtils::nullOrEmpty($highlight_items)){
//			$this->_aParams['resume_highlights'] = $highlight_items;
//		}
	
		//工作经验板块
		$service_work = new base_service_person_resume_work();
		$service_work->dbquery = true; //load data from subordinate database
		$works = $service_work->getResumeWorkList($resume['resume_id'],'start_time,end_time,company_name,calling_id,com_property,com_size,station,is_salary_show,work_content,salary_month,job_type,job_level,department,subordinate,is_creator,leave_reason,manage_department,report_man');
		$works_items = $works->items;
		if(!base_lib_BaseUtils::nullOrEmpty($works_items)){
                    for ($j = 0; $j < count($works_items); $j++) {
                        //每份工作经验时间
                        $work_time_diff = (base_lib_TimeUtil::date_diff_year2($works_items[$j]['start_time'],$works_items[$j]['end_time']));
                        $works_items[$j]['work_time'] = $work_time_diff;
                        //开始结束时间
                        $works_items[$j]['start_time'] = base_lib_BaseUtils::nullOrEmpty($works_items[$j]['start_time'])?'开始时间未填写':date('Y.m',strtotime($works_items[$j]['start_time']));
                        $works_items[$j]['end_time'] = base_lib_BaseUtils::nullOrEmpty($works_items[$j]['end_time'])?'至今':date('Y.m',strtotime($works_items[$j]['end_time']));

                        if(!$isshowresumeinfo){
                            $works_items[$j]['company_name'] = "*****公司";
                            $works_items[$j]['work_content'] = "您暂未通过企业认证，认证后即可查看完整简历";
                        }

                    }
                    $this->_aParams['resume_works'] = $works_items;
		}
			
		//教育经历&培训经历
		$service_edu = new base_service_person_resume_edu();
		$service_edu->dbquery = true; //load data from subordinate database
		$edus = $service_edu->getResumeEduList($resume['resume_id'],'start_time,end_time,school,major_desc,degree,edu_detail,duty');
		$edu_items = $edus->items;
		$this->_aParams['hasEdusOrTrains'] = false;
		$this->_aParams['resume_edus'] = $isshowresumeinfo ? $edu_items : array_slice($edu_items, 0, 1);//未通过认证时教育经历 -只显示最近一次教育经历，其他隐藏 sx 2015.7.16
		if($isshowresumeinfo){//未通过认证时教育经历 -只显示最近一次教育经历，其他隐藏 sx 2015.7.16
			$service_train = new base_service_person_resume_train();
			$service_train->dbquery = true; //load data from subordinate database
			$trains = $service_train->getResumeTrainList($resume['resume_id'],'start_time,end_time,institution,course,certificate,train_detail');
			$train_items = $trains->items;
			$this->_aParams['resume_trains'] = $train_items;
			if(count($edu_items)>0||count($train_items)>0) {
				$this->_aParams['hasEdusOrTrains'] = true;
			}
		}
		$this->_aParams['resume_projects'] = array();
		//项目经验
		if($isshowresumeinfo){//未通过认证时，需要隐藏的简历信息 sx 2015.7.16
			$service_project = new base_service_person_resume_project();
			$service_project->dbquery = true; //load data from subordinate database
			$projects = $service_project->getResumeProjectList($resume['resume_id'],'start_time,end_time,project_name,project_detail,main_duty,duty');
			$project_items = $projects->items;
			if(!base_lib_BaseUtils::nullOrEmpty($project_items)){
				for ($a = 0; $a < count($project_items); $a++) {
					//每份工作经验时间
					$project_time_diff = (base_lib_TimeUtil::date_diff_year2($project_items[$a]['start_time'],$project_items[$a]['end_time']));
					$project_items[$a]['project_time'] = $project_time_diff;
					//开始结束时间
					$project_items[$a]['start_time'] = base_lib_BaseUtils::nullOrEmpty($project_items[$a]['start_time'])?'开始时间未填写':date('Y.m',strtotime($project_items[$a]['start_time']));
					$project_items[$a]['end_time'] = base_lib_BaseUtils::nullOrEmpty($project_items[$a]['end_time'])?'至今':date('Y.m',strtotime($project_items[$a]['end_time']));
				}
				$this->_aParams['resume_projects'] = $project_items;
			}
		}
		
		//证书
		$service_certificate = new base_service_person_resume_certificate();
		$service_certificate->dbquery = true; //load data from subordinate database
		$certificates = $service_certificate->getResumeCertificateList($resume['resume_id'],'certificate_name,certificate_no,gain_time,score');
		$certificates_items = $certificates->items;;
		if(!base_lib_BaseUtils::nullOrEmpty($certificates_items)){
                    for ($k = 0;$k < count($certificates_items); $k++){
                        $grin_time = strtotime($certificates_items[$k]['gain_time']);
                        $certificates_items[$k]['gain_time'] = date('Y年m月',$grin_time);
                    }
                    $this->_aParams['resume_certificates'] =$certificates_items;
		}	
		//我的作品
		$service_achievement = new base_service_person_resume_achievement();
		$service_achievement->dbquery = true; //load data from subordinate database

		$service_attachment = new base_service_person_resume_achievementattachment();
		$service_attachment->dbquery = true; //load data from subordinate database
		$achievements = $service_achievement->getResumeAchievementList($resume['resume_id'],'achievement_id,resume_id,achievement_name,start_time,end_time,achievement_description');               
		$achievement_item = $achievements->items;
		if(!base_lib_BaseUtils::nullOrEmpty($achievement_item)){
                    // 读取配置xml文件
                    $xml = SXML::load('../config/person/ResumeAttachment.xml');
                    if(!is_null($xml)){
                        $attachment_virt_path = base_lib_Constant::UPLOAD_FILE_URL.'/'.$xml->AchievementAttachmentVirtualName.'/';
                        $this->_aParams['attachment_virt_path'] = $attachment_virt_path;
                        $achievement_thumb_suffix = $xml->ThumbSuffix;
                        $achievement_thumb_ext = $xml->AchievementImageExtensions;
                    }
                    for ($j2 = 0; $j2 < count($achievement_item); $j2++) {
                        $attachments = $service_attachment->getResumeAttachmentList($achievement_item[$j2]['achievement_id'],'achievement_id,achievement_path,attachment_name,status,achievement_extension');
                        $attachment_item = $attachments->items;
                        if(!base_lib_BaseUtils::nullOrEmpty($attachment_item)){
                            $achievement_id = $achievement_item[$j2]['achievement_id'];
                            for ($k = 0; $k < count($attachment_item); $k++) {
                                $fileParts = pathinfo($attachment_item[$k]['achievement_path']);
                                if(count(explode($fileParts['extension'],$achievement_thumb_ext))>1){
                                    //$attachment_item[$k]['achievement_thumb_path'] =$fileParts['dirname'].'/'.$fileParts['filename'].$achievement_thumb_suffix.'.'.$fileParts['extension'];
                                    $imgsrc = base_lib_BaseUtils::getThumbImg($xml->AchievementAttachmentVirtualName . '/' .$attachment_item[$k]['achievement_path'],200,160);
                                    $attachment_item[$k]['achievement_thumb_path'] = $imgsrc;
                                }
                            }
                            $attachment_temp_item = $this->array_sort($attachment_item,'achievement_extension');
                            $achievement_item[$j2]['attachments'] = $attachment_temp_item;
                            //$this->_aParams['achievement_attachments'.$achievement_id] = $attachment_temp_item;
                        }
                    }
                    $this->_aParams['resume_achievements'] = $achievement_item;
		}        
		//附加信息
		$service_append = new base_service_person_resume_append();
		$service_append->dbquery = true; //load data from subordinate database
		$appends = $service_append->getResumeAppendList($resume['resume_id'],'topic_desc,content');
		$append_item = $appends->items;
		if(!base_lib_BaseUtils::nullOrEmpty($append_item)){
                    $this->_aParams['resume_appends'] = $append_item;
		}
		//技能
		$service_skill = new base_service_person_resume_skill();
		$service_skill->dbquery = true; //load data from subordinate database
		$skill = $service_skill->getResumeSkillList($resume['resume_id'],'skill_name,skill_level');
		$skill_items =  $skill->items;
		if(!base_lib_BaseUtils::nullOrEmpty($skill_items)){
//			$stat_arr = array();
//			$skill_temp_item = $skill_items;
//			for ($k2 = 0; $k2 < count($skill_temp_item); $k2++) {
//				$skill_name = $skill_temp_item[$k2]['skill_name'];
//				$skill_level = $skill_temp_item[$k2]['skill_level'];
//
//				if(array_key_exists($skill_level,$stat_arr)){
//					$stat_arr[$skill_level] = $stat_arr[$skill_level].'、'.$skill_name;
//				}else{
//					$stat_arr[$skill_level] = '、'.$skill_name;
//				}
//			}
			$this->_aParams['resume_skills'] = $skill_items;
		}
			
		//语言能力
		/*$service_language = new base_service_person_resume_language();
			$languages = $service_language->getLanguagePriview($resume['resume_id']);
			$language_items = $languages->items;
			if(!base_lib_BaseUtils::nullOrEmpty($language_items)){
			$this->_aParams['resume_languages'] = $languages->items;
			}*/
		//语言能力
		$service_language = new base_service_person_resume_language();
		$service_language->dbquery = true; //load data from subordinate database
		$service_languagecert = new base_service_person_resume_languagecert();
		$service_languagecert->dbquery = true; //load data from subordinate database
		$resume_languages = $service_language->getLanguageList($resume_id,'resume_id,language_id,language_type,skill_level')->items;
		foreach($resume_languages as &$resume_language_item){
                    $resume_language_item['certificates'] = $service_languagecert->getCertList($resume_language_item['language_id'], 'cert_id,language_id,cert_name')->items;
		}
		$this->_aParams['resume_languages'] = $resume_languages;         
		//实践经验
		$service_practice = new base_service_person_resume_practice();
		$service_practice->dbquery = true; //load data from subordinate database
		$practices = $service_practice->getResumePracticeList($resume['resume_id'], 'start_time,end_time,practice_name,practice_detail');
		$practice_items = $practices->items;
		if(!base_lib_BaseUtils::nullOrEmpty($practice_items)){
                    for ($a = 0; $a < count($practice_items); $a++) {
                        //每份工作经验时间
                        $practice_time_diff = (base_lib_TimeUtil::date_diff_year2($practice_items[$a]['start_time'],$practice_items[$a]['end_time']));
                        $practice_items[$a]['practice_time'] = base_lib_BaseUtils::nullOrEmpty($practice_time_diff)?'1个月':$practice_time_diff;
                        //开始结束时间
                        $practice_items[$a]['start_time'] = base_lib_BaseUtils::nullOrEmpty($practice_items[$a]['start_time'])?'开始时间未填写':date('Y.m',strtotime($practice_items[$a]['start_time']));
                        $practice_items[$a]['end_time'] = base_lib_BaseUtils::nullOrEmpty($practice_items[$a]['end_time'])?'至今':date('Y.m',strtotime($practice_items[$a]['end_time']));
                    }
                    $this->_aParams['resume_practices'] = $practice_items;
		}
		//感兴趣
		$service_fav = new base_service_company_resume_fav();
		$fav_info = $service_fav->isFav($this->_userid, $resume['resume_id']);
		if(base_lib_BaseUtils::nullOrEmpty($fav_info)){
                    $this->_aParams['is_fav'] = false;
		}else{
                    $this->_aParams['is_fav'] = $fav_info["is_effect"] == "1";
		}
		//获取备注数量
		$service_resumeremark = new base_service_company_resume_resumeremark();
		$remark_list = $service_resumeremark->getResumeRemarkList($this->_userid, $resume['resume_id'], null,'remark_id');
		if($remark_list!==false && count($remark_list->items)>0){
                    $this->_aParams['remark_count'] = count($remark_list->items);
		}
		$service_job_level = new base_service_common_joblevel();	
                //岗位级别   实习/见习  普通员工  高级/资深（非管理岗）的编号
                $this->_aParams['job_level_practice'] = $service_job_level->practice;
                $this->_aParams['job_level_ordinary'] = $service_job_level->ordinary;
                $this->_aParams['job_level_senior'] = $service_job_level->senior;
                $service_job_type = new base_service_common_jobtype();
                //工作类型 全职的编号ID
                $this->_aParams['job_type_fulltime'] = $service_job_type->fulltime;
		$this->_aParams['isshowresumeinfo'] = $isshowresumeinfo;
	}
        /**
	 * 获取简历历史记录	
	 */
	private function _getResumeHistoryRecord($company_id,$resume_id) {
		$service_apply  = new base_service_company_resume_apply();
		$service_invite = new base_service_company_resume_jobinvite();
		$service_remark = new base_service_company_resume_resumeremark();
		$applys = $service_apply->getApplyByPerson($company_id,$resume_id,null,'station,create_time,re_status',0); 
		$invites= $service_invite->getInviteList($resume_id,$company_id,'station,invite_type,create_time,audition_time');	
		$remarks = $service_remark->getResumeRemarkList($company_id, $resume_id, null, 'remark,update_time');
		// lambda 查询婉拒的求职申请
		$search_refuse = function ($date) {
		  return function ($apply) use ($date) { return $apply['re_status']==3&& date('Y-m-d',strtotime($apply['create_time']))==$date; };
		};
		// lambda 查询应聘职位信息
		$search_norefuse = function ($date) {
		  return function ($apply) use ($date) { return date('Y-m-d',strtotime($apply['create_time']))==$date; };
		};	
		// lambda 查询邀请信息
		$search_invite = function ($date) {
		    return function ($invite) use ($date) { return date('Y-m-d',strtotime($invite['create_time']))==$date; };
		};
		// lambda 查询备注信息
		$search_remark = function ($date) {
		   return function ($remark) use ($date) { return  date('Y-m-d',strtotime($remark['update_time']))==$date; };
		};
		$count = 20; // 最多显示消息数量
		$items = array();  
		$items_ago  = array();
		//XXX: 使用循环递减的时间（90天以内）
		for ($i = 0; $i <= 90; $i+=1){			
			 $d  = date('Y-m-d',strtotime("-{$i} days"));			 
			 // 合并同一天的面试邀请,求职婉拒
			 //$arr_refuseapplys = array_filter($applys->items, $search_refuse($d));
			 $arr_invite =  array_filter($invites->items, $search_invite($d));
			 //$arr_refuseapplys = base_lib_BaseUtils::array_sort($arr_refuseapplys, 'create_time',true);
			 $arr_invite = base_lib_BaseUtils::array_sort($arr_invite, 'create_time',true);
			 //$apply = reset($arr_refuseapplys);
			 $invite = reset($arr_invite);
			 /*	 
			 if($apply&&$invite) {
			 	if(strtotime($apply['create_time'])>strtotime($invite['create_time'])) {
			 		$time = $this->_getAuditionTime($invite['audition_time']);
			 		array_push($items,array('time'=>$d,'type'=>1,'datetime'=>strtotime($invite['create_time']),'content'=>"邀请面试：面试职位【{$invite['station']}】，面试时间【{$time}】"));
			 	}else {
			 		array_push($items,array('time'=>$d,'type'=>3,'datetime'=>strtotime($apply['create_time']),'content'=>'婉言谢绝'));
			 	}
			 }elseif($apply) {
			 		array_push($items,array('time'=>$d,'type'=>3,'datetime'=>strtotime($apply['create_time']),'content'=>'婉言谢绝'));			 		
			 }elseif($invite) {
			 		$time = $this->_getAuditionTime($invite['audition_time']);
			 		array_push($items,array('time'=>$d,'type'=>1,'datetime'=>strtotime($invite['create_time']),'content'=>"邀请面试：面试职位【{$invite['station']}】，面试时间【{$time}】"));
			 }*/
			 if($invite) {
			 	$time = $this->_getAuditionTime($invite['audition_time']);
			 	array_push($items,array('time'=>$d,'type'=>1,'datetime'=>strtotime($invite['create_time']),'content'=>"邀请面试：面试职位【{$invite['station']}】，面试时间【{$time}】"));
			 }
			 // 合并同一天求职申请	 
			 $arr_apply = array_filter($applys->items, $search_norefuse($d));
			 if(count($arr_apply)>0) {
			 	$first_apply = reset($arr_apply);
			    $str = array();
			    foreach ($arr_apply as $a){
			    	if($a['re_status']==3) {
			    	   array_push($str, $a['station'].'(已婉拒)');
			    	}else {
			    		array_push($str, $a['station']);
			    	}
			    }
			 	array_push($items,array('time'=>$d,'type'=>2,'datetime'=>strtotime($first_apply['create_time']),'content'=>'应聘职位：'. implode('；', $str)));	
			 }			 
			 
			 // 备注信息
			 $arr_remark = array_filter($remarks->items, $search_remark($d));
			 if(count($arr_remark)>0) {
			 	foreach ($arr_remark as $remark) {
			 		array_push($items,array('time'=>$d,'type'=>4,'datetime'=>strtotime($remark['update_time']),'content'=>'添加备注：'. $remark['remark']));	
			 	}
			 }
			 // 记录90天内的数据
			 if($i==89) {
			 	$items_ago = $items; 
			 	$items = array();
			 }	 
			 if((count($items)+count($items_ago))>=$count) {
			 	 break;
			 }
		}
		$this->_aParams['historyrecords'] = base_lib_BaseUtils::array_sort($items_ago,'datetime',true);
		$this->_aParams['historyrecordagos'] = base_lib_BaseUtils::array_sort($items,'datetime',true) ;
	}
	
	
	// 获取面试时间
	private function _getAuditionTime($invite_audition_time) {
		 $audition_time = base_lib_BaseUtils::getStr($invite_audition_time,'datetime',null);
		 if(base_lib_BaseUtils::nullOrEmpty($audition_time)) { return $invite_audition_time; };
		 $auditiondate =date('Y年m月d日',strtotime($audition_time));
		 $noon = date('H',strtotime($audition_time))<=12?'上午':'下午';
		 $auditiontime =$noon.date('H:i',strtotime($audition_time));
		 $week = $this->week(base_lib_TimeUtil::date_of_week($audition_time));
	 	 $time = $auditiondate.'（'.$week.'）'.$auditiontime;
	 	 return $time;
	}
	/**
	 * 
	 * 返回星期
	 * @param $number
	 */
	private  function week($number) {
		switch ($number){
			case 1:
				$week ='周一';
				break;
			case 2:
				$week ='周二';
				break;					
			case 3:
				$week ='周三';
				break;					
			case 4:
				$week ='周四';
				break;
			case 5:
				$week ='周五';
				break;
			case 6:
				$week ='周六';
				break;
			default:
				$week = '周日';
				break;	
		}
		return  $week;
	}
        /**
	 * 新增访问记录
	 * @param  $inPath
	 */
	private  function _addVisit($resume_id,$person_id,$company_id) {
		$visit['resume_id']	 = $resume_id;
		$visit['person_id']	 = $person_id;
		$visit['company_id']	 = $company_id;
		$visit['hit_time']	 = date('Y-m-d H:i:s');
		$service_visit = new base_service_person_visit();
                $last_visit = $service_visit->getVisitByResumeId($resume_id, $company_id,"hit_time");
                $visite_time_minit = 200;//如果在3分钟以内 就不添加访问记录
                if(!base_lib_BaseUtils::nullOrEmpty($last_visit)){
                    $visite_time_minit = time()-strtotime($last_visit['hit_time']);
                }
                if($visite_time_minit>=180){
                    $service_visit->addVisit($visit);
                    // 更新简历冗余记录
                    $service_resume = new base_service_person_resume_resume();
                    //获得上次

                    $service_resume->increaseVisitNum($resume_id);
                }
	}
        
	/**
	 * 获取地区名称(特殊  例如：重庆-主城区,北京-五环以内)
	 * @param $service_area   实例化对象
	 * @param array $area_ids_arr  选择的地区数组
	 * @param string $area_ids  选择的地区字符串
	 */
	private function getSpecialAreaName($service_area,$area_ids_arr,$area_ids){
		$area_name = '';
		if(count($area_ids_arr)<=0){
			return $area_name;
		}
		$xml = SXML::load('../config/person/Person.xml');
		if(!is_null($xml)){
			$cq_mian_city = $xml->CQMainCity;
			$cq_other_counties = $xml->CQOtherCounties;
			$bj_rings_within = $xml->BJRingsWithin;
			$bj_rings_without = $xml->BJRingsWithout;
			$sh_outer_within = $xml->SHOuterWithin;
			$sh_suburbs = $xml->SHSuburbs;
			$tj_mian_city = $xml->TJMainCity;
			$tj_other_counties = $xml->TJOtherCounties;
		}
		$temp_area_ids_arr = array();
		//重庆
		$cq_mian_city_arr = explode(',', $cq_mian_city);
		$cq_other_counties_arr = explode(',', $cq_other_counties);

		$intersect_cqmain = implode(',',array_intersect($cq_mian_city_arr,$area_ids_arr))== $cq_mian_city;
		$intersect_cqother = implode(',',array_intersect($cq_other_counties_arr,$area_ids_arr))== $cq_other_counties;

		if($intersect_cqmain&&$intersect_cqother){
			$area_name .= '重庆-主城区;重庆-周边区县;';
			$temp_area_ids_arr = array_diff($area_ids_arr,$cq_mian_city_arr);
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$cq_other_counties_arr);
		}elseif ($intersect_cqmain){
			$area_name .= '重庆-主城区;';
			$temp_area_ids_arr = array_diff($area_ids_arr,$cq_mian_city_arr);
		}elseif ($intersect_cqother){
			$area_name .= '重庆-周边区县;';
			$temp_area_ids_arr = array_diff($area_ids_arr,$cq_other_counties_arr);
		}
		$temp_area_ids_arr = count($temp_area_ids_arr)<=0&&empty($area_name)?$area_ids_arr:$temp_area_ids_arr;

		//北京
		$bj_mian_city_arr = explode(',', $bj_rings_within);
		$bj_other_counties_arr = explode(',', $bj_rings_without);

		$intersect_bjmain = implode(',',array_intersect($bj_mian_city_arr,$temp_area_ids_arr))== $bj_rings_within;
		$intersect_bjother = implode(',',array_intersect($bj_other_counties_arr,$temp_area_ids_arr))== $bj_rings_without;

		if($intersect_bjmain&&intersect_bjother){
			$area_name .= '北京-五环以内;北京-五环以外;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$bj_mian_city_arr);
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$bj_other_counties_arr);
		}elseif ($intersect_bjmain){
			$area_name .= '北京-五环以内;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$bj_mian_city_arr);
		}elseif ($intersect_bjother){
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$bj_other_counties_arr);
			$area_name .= '北京-五环以外;';
		}
		//上海
		$sh_mian_city_arr = explode(',', $sh_outer_within);
		$sh_other_counties_arr = explode(',', $sh_suburbs);

		$intersect_shmain = implode(',',array_intersect($sh_mian_city_arr,$temp_area_ids_arr))== $sh_outer_within;
		$intersect_shother = implode(',',array_intersect($sh_other_counties_arr,$temp_area_ids_arr))== $sh_suburbs;

		if($intersect_shmain&&$intersect_shother){
			$area_name .= '上海-外环以内;上海-郊区/县;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$sh_mian_city_arr);
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$sh_other_counties_arr);
		}elseif ($intersect_shmain){
			$area_name .= '上海-外环以内;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$sh_mian_city_arr);
		}elseif ($intersect_shother){
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$sh_other_counties_arr);
			$area_name .= '上海-郊区/县;';
		}
		//天津
		$tj_mian_city_arr = explode(',', $tj_mian_city);
		$tj_other_counties_arr = explode(',', $tj_other_counties);

		$intersect_tjmain = implode(',',array_intersect($tj_mian_city_arr,$temp_area_ids_arr))== $tj_mian_city;
		$intersect_tjother = implode(',',array_intersect($tj_other_counties_arr,$temp_area_ids_arr))== $tj_other_counties;

		if($intersect_tjmain&&$intersect_tjother){
			$area_name .= '天津-主城区;天津-周边区县;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$tj_mian_city_arr);
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$tj_other_counties_arr);
		}elseif ($intersect_tjmain){
			$area_name .= '天津-主城区;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$tj_mian_city_arr);
		}elseif ($intersect_tjother){
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$tj_other_counties_arr);
			$area_name .= '天津-周边区县;';
		}
		$temp_area_ids_arr = array_merge($temp_area_ids_arr);
		if(count($temp_area_ids_arr)==0){
			$area_name = mb_substr($area_name,0,mb_strlen($area_name,'utf-8')-1);
		}
		for ($m2 = 0; $m2 < count($temp_area_ids_arr); $m2++) {
			$area_temp_name = $service_area->getArea($temp_area_ids_arr[$m2]);
			if($m2 == sizeof($temp_area_ids_arr)-1){
				$area_name .= $area_temp_name;
			}else{
				$area_name .= $area_temp_name.';';
			}
		}
		return $area_name;
	}
        //设置面试结果
        // 进入面试结果设置页面
        public function pageSetResult($inPath) {
            $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
            $operate =  $path_data['op']; 
            $invite_id =base_lib_BaseUtils::getStr($path_data['invite_id'],'int',0);  
            $recommend_id =base_lib_BaseUtils::getStr($path_data['recommend_id'],'int',0);
            //推荐简历状态
            $recommend_status_service = new base_service_common_recommendstatus();
            $recommend_status_arr = $recommend_status_service->getRecommendStatus();
            $this->_aParams['recommend_status_arr'] = $recommend_status_arr;
            $this->_aParams['recommendid'] = $recommend_id;
            $this->_aParams['inviteid'] = $invite_id;
            return  $this->render('resume/recommend/setresult.html', $this->_aParams);    		
        }
        	/**
	 * 数组排序 (自定义附件排序)
	 * @param array $arr 需排序的数组
	 * @param string $keys 排序的字段
	 */
	private function array_sort($arr,$keys){
		$new_array = array();
		if($keys=='achievement_extension'){
			$z = 1;
			foreach ($arr as $k=>$v){
				if(count(explode($arr[$k]['achievement_extension'],'.jpg,.jpeg,.gif,.bmp,.png'))>1){
					if($z>1){
						$new_array[$k-($z-1)] =  $arr[$k];
					}else{
						$new_array[$k] = $arr[$k];
					}
				}else{
					$new_array[count($arr)-$z] = $arr[$k];
					$z++;
				}
			}
			ksort($new_array);
			return $new_array;
		}else{
			ksort($arr);
			return $arr;
		}
	}
    
    	
	//删除推荐的简历
	public function pageDeleteRecommendResume($inPath){
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$recommendid = base_lib_BaseUtils::getStr($params['recommend_id'],'int',null);
		if(empty($recommendid)) {
			echo json_encode(array('error'=>"未找到需要删除的推荐简历！"));
			return;
		}
		$recommendservice = new base_service_company_resume_recommend();
		$result = $recommendservice->setIsEffect($recommendid,false);
		if( $result === false) {
			echo json_encode(array('error'=>"删除失败！"));
			return;
		}else{
			echo json_encode(array('success'=>"删除成功！"));
			return;
		}
	}


    /**
     * 获取自动推荐默认职位
     */
    private function getDefaultJob(){
        $apply_resume_num = 5;
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, base_lib_BaseUtils::getCookie('accountid'));
        $account_id = $company_resources->all_accounts;

        $job_status = new base_service_common_jobstatus();

        //获取所有在招职位
        $status = $job_status->pub;
        $service_company_job_job = new base_service_company_job_job();
        $field = 'job_id,job_flag,station,issue_time,check_state,end_time,status,create_time,mod_time,re_apply_type,company_id,account_id,agency_state';
        $job_list = $service_company_job_job->getJobList($account_id, '', $status, $field, 0, 0, null, null);
        $job_ids = base_lib_BaseUtils::getPropertys($job_list,"job_id");

        //获取一周内新发布职位
        $week_time = date("Y-m-d",strtotime("-1 week"));
        $create_week_job = array();
        $default_job_id = '';
        $last_pub_job = 0;
        $last_mod_time = "";
        foreach($job_list as $key => $value){
            if($value['create_time'] >= $week_time){
                array_push($create_week_job,$value['job_id']);
            }
            if($value['mod_time'] > $last_mod_time){
                $last_pub_job = $value['job_id'];
                $last_mod_time = $value['mod_time'];
            }

        }

        $service_company_resume_apply = new base_service_company_resume_apply();
        $job_apply_result = $service_company_resume_apply->getJobApplyCount($create_week_job);

        if(empty($job_apply_result)){
            //若无符合条件职位，默认最近发布/修改的职位
            $default_job_id = $last_pub_job;
        }else{
            //将投递职位大于X份的简历抛出
            foreach($job_apply_result as $key =>$value){
                if($value['applycount'] >= $apply_resume_num){
                    foreach($create_week_job as $k => $val){
                        if($val == $value['jobid']){
                            unset($create_week_job[$k]);
                        }
                    }
                }
            }
            if(empty($create_week_job)){
                $default_job_id = $last_pub_job;
            }else{
                $job_list = base_lib_BaseUtils::array_key_assoc($job_list,"job_id");
                $last_create_time = "";
                $last_pub_job = "";
                foreach($create_week_job as $key => $value){
                    if($job_list[$value]['create_time'] > $last_create_time){
                        $last_pub_job = $value;
                        $last_create_time = $job_list[$value]['create_time'];
                    }
                }
                $default_job_id = $last_pub_job;
            }
        }
        $data = array(
            'job_list'          => $job_list,
            'default_job_id'    => $default_job_id
        );
        return $data;
    }


}
?>