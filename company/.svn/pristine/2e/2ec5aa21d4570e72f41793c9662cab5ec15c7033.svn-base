<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/6 0006
 * Time: 14:18
 */
class controller_readjob extends components_cbasepage {

    function __construct() {
        parent::__construct();
    }



    /*
     * 谁看过我的职位 tw 2018-09-06
     * */
    public function pageindex($inPath){

        $pathData   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

        $xml = SXML::load('../config/config.xml');
        $this->_aParams['ShowResumeNum'] = $xml->ShowResumeNum;
        $this->_aParams['title']         = "谁看过我的职位 简历管理_{$xml->HuiBoSiteName}";

        $cur_page       = base_lib_BaseUtils::getStr($pathData['page'], 'int', 1);
        $search_job_id  = base_lib_BaseUtils::getStr($pathData['search_job_id'], 'int', 0);
        $page_size      = base_lib_Constant::PAGE_SIZE;  //每页显示多少条

        $companyService = new base_service_company_company();
        
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid);
        $accounts_company_ids = $company_resources->all_accounts;

        $visitList = new base_service_company_job_jobvisitdetail();

        $list = $visitList->getJobGroupList($accounts_company_ids , $cur_page , $page_size,"id,company_id,person_id,job_id,create_time,visit_time,visit_date,max(visit_time) visit_time,read_time",$search_job_id);


        if(!empty($list->items)){
            $job_ids = array_unique(base_lib_BaseUtils::getProperty($list->items,'job_id'));
            $job_service = new base_service_company_job_job();
            $job_List = $job_service->getJobs($job_ids,'job_id,station,job_flag');
            $job_List = base_lib_BaseUtils::array_key_assoc($job_List,'job_id');

            $person_ids = array_unique(base_lib_BaseUtils::getProperty($list->items,'person_id'));
            $person_service = new base_service_person_person();
            $resume_service = new base_service_person_resume_resume();

            $resume_list = $resume_service->getDefaultResumes($person_ids , 'person_id,resume_id,resume_name,degree_id,current_station,current_station_start_time,current_station_end_time,is_effect,mobile_phone')->items;
            $resume_list = base_lib_BaseUtils::array_key_assoc($resume_list,'person_id');

            $person_list = $person_service->GetPersonListByIDs($person_ids, 'person_id,user_name,name_open,photo_open,sex,birthday2,cur_area_id,start_work,photo,small_photo,mobile_phone,open_mode')->items;
            $person_list = base_lib_BaseUtils::array_key_assoc($person_list,'person_id');
            
            //最近投递职位
            $service_apply  = new base_service_company_resume_apply();
            $apply_list     = $service_apply->getNearApplyJobs($this->_userid, $person_ids, "job_id,person_id,company_id,create_time");
        
            $apply_job_ids  = base_lib_BaseUtils::getProperty($apply_list, "job_id");
            $apply_job_list = $job_service->getJobs($apply_job_ids,'job_id,station,job_flag');
            $apply_job_list = base_lib_BaseUtils::array_key_assoc($apply_job_list,'job_id');
            $resume_ids = array_unique(base_lib_BaseUtils::getProperty($resume_list,'resume_id'));
            //毕业院校
            $service_edu = new base_service_person_resume_edu();
            $edu_data = $service_edu->getResumeEdus(implode(',',$resume_ids),'resume_id,school,major_desc,degree')->items;
            $edu_data = base_lib_BaseUtils::array_key_assoc($edu_data,'resume_id');

            //工作经验
            $service_resume_work   = new base_service_person_resume_work();
            $work_datas = $service_resume_work->getResumeWorks(implode(',',$resume_ids), 'work_id,resume_id,start_time,end_time,station,company_name,work_content');
            $workslist = array();
            foreach ($work_datas->items as $workskey => $worksvalue) {
                $workslist[$worksvalue["resume_id"]][$workskey]['start_time']   = date('Y/m', strtotime($worksvalue['start_time']));
                $workslist[$worksvalue["resume_id"]][$workskey]['end_time']     = empty($worksvalue['end_time']) ? "至今" : date('Y/m', strtotime($worksvalue['end_time']));
                $workslist[$worksvalue["resume_id"]][$workskey]['station']      = $worksvalue['station'];
                $workslist[$worksvalue["resume_id"]][$workskey]['company_name'] = $worksvalue['company_name'];
                $workslist[$worksvalue["resume_id"]][$workskey]['work_content'] = base_lib_BaseUtils::cutstr($worksvalue['work_content'], 180, 'utf-8', '','...');
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



            foreach($list->items as &$item){
                if(empty($item['read_time'])){
                    //设置为已读
                    $visitList->setHasRed($item['job_id'],$item['company_id'],$item['person_id'],$item['visit_date']);
                }

                $person_info = $person_list[$item['person_id']];
                $resume_info = $resume_list[$item['person_id']];
                $_apply_list  = base_lib_BaseUtils::arrayFindAll($apply_list, "person_id", $item['person_id']);
                $item["apply_list"]     = [];
                $item["is_apply_job"]   = false;
                if(!empty($_apply_list)){
                    foreach($_apply_list as $_apk => $_apv){
                        $_apply_list[$_apk]["create_date"] = date("Y-m-d",strtotime($_apv["create_time"]));
                        $_apply_list[$_apk]["station"]     = !empty($apply_job_list[$_apv["job_id"]]["station"]) ? $apply_job_list[$_apv["job_id"]]["station"] : ""; 
                        if($_apv["job_id"] == $item["job_id"]){
                            $item["is_apply_job"] = true;
                        }
                    }
                    $_apply_list = base_lib_BaseUtils::array_key_assoc($_apply_list, "job_id"); //根据职位编号去重
                    $item["apply_list"] = $_apply_list;
                }
                $item['exist_resume'] = empty($resume_info)? 0 : 1;

                $item['station'] = $job_List[$item['job_id']]['station'];
                $item['job_flag'] = $job_List[$item['job_id']]['job_flag'];
                $item['open_mode'] = $person_info['open_mode'];
                //访问时间
                $item['visit_time'] = $this->get_day($item['visit_time']);
                // 姓名
                $item['user_name'] = empty($person_info['user_name']) ? "&nbsp;" : $person_info['user_name'];

                if ($person_info['name_open'] == 0) {
                    $sex_name = $person_info['sex'] == 1 ? '先生' : '女士';
                    $item['user_name'] = mb_substr($person_info['user_name'], 0, 1, 'utf-8') . $sex_name;
                }
                $item['full_user_name'] = base_lib_BaseUtils::htmlEncode($person_info['user_name']);


                //头像性别、年龄、学历、当前所在地

                if ($person_info['photo_open'] === '0') {//允许null,和1一样，默认可以公开
                    $person_info['photo']       = false;
                    $person_info['small_photo'] = false;
                } else {
                    if (base_lib_BaseUtils::nullOrEmpty($person_info['small_photo'])) {
                        $person_info['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['photo'];
                    } else {
                        $person_info['small_photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['small_photo'];
                    }
                    //兼容判断
                    if(base_lib_BaseUtils::nullOrEmpty($person_info['photo']))
                        $person_info['photo'] = false;
                    else
                        $person_info['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['photo'];
                }

                $item['photo']       = $person_info['photo'];
                $item['small_photo'] = $person_info['photo'];//改版后头像用原始头像
                $item['sex']         = $this->getSex($person_info['sex']);
                $item['age']         = base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']) . '岁';

                $item['cur_area']    = $this->getArea($person_info['cur_area_id']);

                //工作年限
                $basic_start_work_year = base_lib_TimeUtil::date_diff_month($person_info['start_work']);
                $workY = floor($basic_start_work_year / 12);
                $workM = intval($basic_start_work_year % 12);

                if ($workY <= 0 && $workM <=6 && $workM >= -6) {
                    $basic_start_work_year = '应届毕业生';
                } else if ($workY == 0 && $workM > 6) {
                    $basic_start_work_year = $workM . '个月工作经验';
                } else if ($basic_start_work_year < -6) {
                    $basic_start_work_year = '目前在读';
                } else {
                    $basic_start_work_year = $workY . '年工作经验';
                }

                $item['start_work'] = $basic_start_work_year;
                if (base_lib_BaseUtils::nullOrEmpty($item['start_work'])) {
                    $item['start_work'] = "应届毕业生";
                }
                ###
                if(!empty($resume_info)){

                    $item['resume_id'] = $resume_info['resume_id'];
                    //学历
                    $item['degree']      = $this->getDegree($resume_info['degree_id']);
                    //最近工作经验
                    if ($resume_info['current_station'] == '') {
                        $item['work'] = '无';
                    } else {
                        if (base_lib_BaseUtils::nullOrEmpty($resume_info['current_station_start_time'])) {
                            $item['work'] = $resume_info['current_station'];
                        } else {
                            $item['work'] = $resume_info['current_station'] . '('
                                . base_lib_TimeUtil::date_diff_year3($resume_info['current_station_start_time'], $resume_info['current_station_end_time'])
                                . ')';
                        }
                    }

                    //毕业院校
                    $edu_info = $edu_data[$resume_info['resume_id']];
                    $item['school']        = $edu_info['school'];
                    $item['major_desc']    = $edu_info['major_desc'];
                    $item['school_degree'] = $this->getDegree($edu_info['degree']) ;
                    //var_dump($workslist["$resume_id"]);
                    $count = count($workslist[$resume_info['resume_id']]);
                    if ($count > 0) {
                        $item['worklist'] = array_slice($workslist[$resume_info['resume_id']], 0, ($count >= 3 ? 3 : $count));
                    } else {
                        $item['worklist'] = array();
                    }
                }
                ###
                // 是否为代招
                $item['generation_binding'] = $item['company_id'] == $this->_userid ? false : true;


                //聊一聊状态 ，提示等级 1：(未登录)提示 2：（未登录）提示 + 提示消耗 3：提示消耗
                $chat_params['resume_id'] = $resume_info['resume_id'];
                $chat_params['person_id'] = $item['person_id'];
                $chat_params['company_id'] = $this->_userid;
                //$item['chat_status'] = $service_chat->getChatNoticeStatus($this->_userid,base_lib_BaseUtils::getCookie('accountid'),null,$chat_params, $item['person_id']);
                //$item['chat_status'] = !empty($wy_person_status_arr[$item['person_id']]) ? $wy_person_status_arr[$item['person_id']] : false;
                $item['chat_status'] = !empty($login_status[$item['person_id']]) ? true : false;
            }

        }

        /* 页码 */
        $pager = $this->pageBarFullPath($list->totalSize, $page_size, $cur_page, $inPath);

        $this->_aParams['page'] = $pager;
        $this->_aParams['list'] = $list->items;

        $job                   = new base_service_company_job_job();
        $status                = new base_service_common_jobstatus();
        
        $_job_list = [];
        $_job_list[] = ["id" => 0,"name" => "全部职位"];
        $job_list   = $job->getJobList($this->_userid, null, $status->pub, 'job_id,station');
        if(!empty($job_list)){
            foreach($job_list as $value){
                $_job_list[] = ["id"=>$value['job_id'],"name"=>$value['station']];
            }
        }
		$this->_aParams['pub_job_list']     = json_encode($_job_list);
        $this->_aParams["search_job_id"]    = $search_job_id;
        
        return $this->render('readjob/index.html',$this->_aParams);
    }

    /**
     * 主账号及使用“共享资源”模式的子账号默认不限，使用“分配资源”模式的子账号默认自己
     */
    private function checkAccountResource(){
        $service_company_account = new base_service_company_account();
        $account_id = base_lib_BaseUtils::getCookie('accountid');
        $fields = 'account_id,company_id,is_main';
        $account = $service_company_account->getAccount($account_id, $fields);
        if($account['is_main'] == 1){
            return true;
        }
        $company_resources =  base_service_company_resources_resources::getInstance($this->_userid,true,$account_id);
        $company_resources_info = $company_resources->getCompanyServiceSource(['account_resource']);
        if($company_resources_info['resource_type'] == 1){
            //共享模式
            return true;
        }else{
            return false;
        }
    }


    public function pageCompanyVisit($inPath){
        header("Content-type: text/html; charset=utf-8");
        $pathData   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        //500错误不显示的解决
//        ini_set("display_errors", "On");
//        error_reporting(E_ALL || E_STRICT);
        $xml = SXML::load('../config/config.xml');
        $this->_aParams['ShowResumeNum'] = $xml->ShowResumeNum;
        $this->_aParams['title'] = $title = "企业职位浏览统计_{$xml->HuiBoSiteName}";

        $execl = base_lib_BaseUtils::getStr($pathData['execl'], 'string', '');
        $cur_page = base_lib_BaseUtils::getStr($pathData['page'], 'int', 1);
        $page_size = base_lib_BaseUtils::getStr($pathData['page_size'], 'int',  base_lib_Constant::PAGE_SIZE);  //每页显示多少条

        $time_id = base_lib_BaseUtils::getStr($pathData['time_id'],'int',1);
        $company_id = base_lib_BaseUtils::getStr($pathData['company_id'],'int','');
        $is_stop = base_lib_BaseUtils::getStr($pathData['is_stop'],'int','0');
        $job_id = base_lib_BaseUtils::getStr($pathData['job_id'],'int','');
        $detail_job_id = base_lib_BaseUtils::getStr($pathData['detail_job_id'],'string','all');
//        $qusition_job = base_lib_BaseUtils::getStr($pathData['qusition_job'],'string','1');

        $accountid = base_lib_BaseUtils::getCookie('accountid');
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
        $son_account_id = '';
        if($this->checkAccountResource()){
            //主账号
//            $account_ids = $company_resources->all_accounts;
        }else{
            $son_account_id = $accountid;
//            $account_ids = $this->_userid;
        }
        $account_ids = $company_resources->all_accounts;

        $companyService = new base_service_company_company();
        $accounts = $companyService->getCompanys($account_ids, "company_id,company_name,company_shortname,company_flag,calling_ids");
        $accounts_company_ids = base_lib_BaseUtils::getProperty($accounts,'company_id');
        $company_list = base_lib_BaseUtils::array_key_assoc($accounts,"company_id");

        if(!in_array($company_id,$accounts_company_ids) && !empty($company_id)){
            $this->_aParams['message'] = '该企业编号不是本公司编号，请勿非法操作';
            return $this->render('showerror.html', $this->_aParams);
        }

        $this->_aParams['is_hr'] = $company_resources->account_type == 'hr_main' ? true : false;
        $this->_aParams['company_id'] = $company_id;
        $this->_aParams['is_stop'] = $is_stop;
        $this->_aParams['detail_job_id'] = $detail_job_id;
//        $this->_aParams['qusition_job'] = $qusition_job;
        #region 前端控件数据控制
        //时间控件数据
        $time_data = [
            ['id' => 1 , 'name' => '近7天'],
            ['id' => 2, 'name' => '近14天'],
            ['id' => 3, 'name' => '近30天']
        ];
        $time_value = '';
        foreach($time_data as $key => $value){
            if($value['id'] == $time_id){
                $time_value = $value['name'];
            }
        }
        $start_time = date("Y-m-d",strtotime("-7 day"));
        $all_days = 7;
        if($time_id == 2){
            $start_time = date("Y-m-d",strtotime("-14 day"));
            $all_days = 14;
        }elseif($time_id == 3){
            $start_time = date("Y-m-d",strtotime("-30 day"));
            $all_days = 30;
        }
        $this->_aParams['start_time'] = $start_time;
        $this->_aParams['time_id'] = empty($time_value) ? 1 : $time_id;
        $this->_aParams['time_value'] = empty($time_value) ? '近7天' : $time_value;
        $this->_aParams['time_data'] = json_encode($time_data);

        //代招公司数据
        $_datas[] = ["id" => "0", "name" => "所有公司"];
        foreach ($accounts as $key => $account) {
            $account['company_name_display']   = $account['company_shortname'] ? $account['company_shortname'] : $account['company_name'];
            $_accounts[$account['company_id']] = $account;

            $_datas[] = ['id' => $account['company_id'], "name" => $account['company_name_display']];
        }
        $this->_aParams['accounts_json'] = json_encode($_datas);

        //职位控件数据
        $showStopJobApply = $is_stop == 1 ? true : false;
        //获取近30天的访问职位ids
        $jobvisit_service = new base_service_company_job_companyjobvisit();
        $company_ids = empty($company_id) ? $accounts_company_ids : $company_id;
        $base_service_company_job_job = new base_service_company_job_job();
        $account_job_list =$base_service_company_job_job->getJobIdByAccount($company_ids,$son_account_id,'company_id,job_id,account_id');
        $account_job_ids = base_lib_BaseUtils::getProperty($account_job_list->items,'job_id');
        $job_ids = $jobvisit_service->getJobIdsByCompany_id($company_ids);
        $job_ids_temp = explode(',',$job_ids['job_ids']);
        $job_ids = array_intersect($account_job_ids,$job_ids_temp);
        $jobs = $this->_getJobsAndSort($job_ids, $showStopJobApply);
        $job_ids = base_lib_BaseUtils::getPropertys($jobs,"job_id");
        $this->_aParams['job_id'] = $job_id;

        // 职位筛选项
        $jobs_json = [];
        array_push($jobs_json, ["id"=>"", "name"=>"全部职位"]);
        foreach ($jobs as $key => $job){
            array_push($jobs_json, ["id"=>$job['job_id'], "name"=>$job['station']]);
        }
        $this->_aParams['jobs'] = json_encode($jobs_json);
        #endregion

        $job_list = base_lib_BaseUtils::array_key_assoc($jobs,"job_id");

        //获取访问数据
        $list = $jobvisit_service->getList($company_ids,$job_id , $time_id , $cur_page , $page_size ,true);
        $service_serviceConsumeLog = new base_service_company_service_serviceConsumeLog();
        $visit_job_ids = base_lib_BaseUtils::getPropertys($list->items,"job_id");
        $refresh_times = $service_serviceConsumeLog->getCountByRelevance(3, $visit_job_ids, 'relevance_id,count(id) refresh_times',7);
        $refresh_times = base_lib_BaseUtils::array_key_assoc($refresh_times,"relevance_id");

        $service_top = new base_service_company_job_jobtop();
//        $service_company_job_quality = new base_service_company_job_quality(); //精品职位
//        $jobquality = $service_company_job_quality->getJobIsQualityByIds($job_ids);
        $spreadJob_service = new base_service_company_spread_spreadjob();
        $company_ids_temp = is_array($company_ids) ? $company_ids : [$company_ids];
        $EffectSpreadJob = $spreadJob_service->getSpreadJobListOfjob_ids($company_ids_temp, $job_ids);

        //平均薪资
        $service_average_salary = new base_service_person_averagesalary();
        $service_replayrate_job = new base_service_replayrate_job();
        $applyJobService = new base_service_company_resume_apply();
        $visit_info_data = [];
        $visit_info_ids = [];
        if(!empty($list->items)){
            //获取平均曝光量
            $calling_ids = array_unique(base_lib_BaseUtils::getPropertys($list->items,"calling_id"));
            $jobsort_ids = array_unique(base_lib_BaseUtils::getPropertys($list->items,"jobsort"));
            $avg_visit_data = $jobvisit_service->getAvgVisitByCallingJobsort($calling_ids,$jobsort_ids,$time_id)->items;
            $avg_visit_data = base_lib_BaseUtils::array_key_assoc($avg_visit_data,"calling_jobsort");

            //职位诊断，踢出曝光量、点击率、投递率都正常的数据
            foreach($list->items as $key => &$visit_item){
                if(!in_array($visit_item['job_id'],$job_ids)){
                    unset($list->items[$key]);
                    continue;
                }
                $visit_item['station'] = $job_list[$visit_item['job_id']]['station'];
                $visit_item['click_rate'] = empty($visit_item['exposes_count']) ? 0 : round($visit_item['visit_count'] / $visit_item['exposes_count'] * 100,2);
                $visit_item['delivery_rate'] = empty($visit_item['visit_count']) ? 0 : round($visit_item['apply_count'] / $visit_item['visit_count'] * 100,2);
                $visit_item['invitation_rate'] = empty($visit_item['apply_count']) ? 0 : round($visit_item['invite_count'] / $visit_item['apply_count'] * 100,2);

                $visit_data = $avg_visit_data[$visit_item['calling_jobsort']];

                //平均曝光量
                $avg_exposes = sprintf("%.2f",($visit_data['exposes_avg']/$all_days));
                //平均点击率
                $avg_visit = empty($visit_data['exposes_count_num']) ? 0 : round($visit_data['visit_count_num'] / $visit_data['exposes_count_num'] * 100,2);
                //平均投递率
                $avg_apply = empty($visit_data['visit_count_num']) ? 0 : round($visit_data['apply_count_num'] / $visit_data['visit_count_num'] * 100,2);
//                echo "职位：{$job_list[$visit_item['job_id']]['station']}======================</br>";
//                echo "职位类别-行业类别:{$visit_item['calling_jobsort']},总曝光量:{$visit_data['exposes_avg']},总点击量:{$visit_data['visit_count_num']},总投递量:{$visit_data['apply_count_num']}</br>";
//                echo "搜索天数{$all_days}</br>";
//                echo "计算后评价值：曝光量:{$avg_exposes},点击率:$avg_visit,投递率:$avg_apply</br>";


                $exposes_status = 1;
                if($job_list[$visit_item['job_id']]['is_stop'] != 1){
                    $job_start_time = $job_list[$visit_item['job_id']]['start_time'];
                    $job_end_time = $job_list[$visit_item['job_id']]['end_time'];
                    $bg_days1 = base_lib_TimeUtil::time_diff_day($job_start_time,$job_end_time);
                    $bg_days = $bg_days1 > $all_days ? $all_days : $bg_days1;
                    $exposes_count_avg = sprintf("%.2f",($visit_item['exposes_count'] / $bg_days));
                    if($exposes_count_avg < $avg_exposes*0.7){
                        $visit_item['exposes_str'] = "待提升";
                        $exposes_status = 3;
                    }elseif($exposes_count_avg >= $avg_exposes*1.2 && !empty($exposes_count_avg)){
                        $visit_item['exposes_str'] = "数据良好继续保持";
                        $exposes_status = 2;
                    }else{
                        $visit_item['exposes_str'] = "较好";
                    }
//                    echo "曝光量计算数据:职位发布开始时间:{$job_start_time},结束时间:{$job_end_time},计算天数:{$bg_days},职位总曝光量:{$visit_item['exposes_count']}</br>";
                }
                $visit_item['job_is_stop'] = $job_list[$visit_item['job_id']]['is_stop'];
                $visit_item['exposes_status'] = $exposes_status;

                //点击率判断
                $click_status = 1;
                if($visit_item['click_rate'] < $avg_visit*0.7){
                    $visit_item['click_str'] = "待提升";
                    $click_status = 3;
                }elseif($visit_item['click_rate'] >= $avg_visit*1.2 && !empty($visit_item['click_rate'])){
                    $visit_item['click_str'] = "数据良好继续保持";
                    $click_status = 2;
                }else{
                    $visit_item['click_str'] = "较好";
                }
                $visit_item['click_status'] = $click_status;
//                echo "点击率计算数据:职位点击率:{$visit_item['click_rate']},职位类别点击率:{$avg_visit},比对结果:$click_status</br>";
                //投递率判断
                $apply_status = 1;
                if($visit_item['delivery_rate'] < $avg_apply*0.7){
                    $visit_item['apply_str'] = "待提升";
                    $apply_status = 3;
                }elseif($visit_item['delivery_rate'] >= $avg_apply*1.2 && !empty($visit_item['delivery_rate'])){
                    $visit_item['apply_str'] = "一般";
                    $apply_status = 2;
                }else{
                    $visit_item['apply_str'] = "较好";
                }
                $visit_item['apply_status'] = $apply_status;
//                echo "投递率计算数据:职位点击率:{$visit_item['delivery_rate']},职位类别点击率:{$avg_apply},比对结果:$apply_status</br>";
//                echo "职位：{$job_list[$visit_item['job_id']]['station']}============end==========</br>";
                //获取最近一周刷新次数
                $visit_item['refresh_times'] = empty($refresh_times[$visit_item['job_id']]['refresh_times']) ? 0 : $refresh_times[$visit_item['job_id']]['refresh_times'];
                //有一项不正常，加入数据
//                if($qusition_job == 1) {
//                    if ($exposes_flag && $click_flag && $apply_flag) {
//                        unset($list->items[$key]);
//                        continue;
//                    }
//                }
                //推广判断
                $is_spread = 1;
                //是否设置置顶
                $toplist = $service_top->getList('', '', $visit_item['job_id'], "id");
                if (count($toplist->items)) {
                    $is_spread = 0;
                }
                //是否这种急聘
                if($job_list[$visit_item['job_id']]['urgent_end_time'] > 0){
                    $interval_time = (strtotime(date('Y-m-d', $job_list[$visit_item['job_id']]['urgent_end_time']))) - (strtotime(date('Y-m-d', time())));
                    if ($interval_time >= 0) {
                        $is_spread = 0;
                    }
                }
                //是否设置精准推广
                if(in_array($visit_item['job_id'],$EffectSpreadJob)){
                    $is_spread = 0;
                }
                $visit_item['is_spread'] = $is_spread;

                //工作经验是否小于一年
                $work_year = 0;
                if($job_list[$visit_item['job_id']]['work_year_id'] == '01' && $job_list[$visit_item['job_id']]['allow_graduate'] != 1){
                    $work_year = 1;
                }
                $visit_item['work_year'] = $work_year;

                //薪资
                $jobsort_ids = $job_list[$visit_item['job_id']]['jobsort_ids'];
                $min_salary = $job_list[$visit_item['job_id']]['min_salary'];
                $average_salary_job = $service_average_salary->getSalaryByJobsortId($jobsort_ids, 'jobsort_name,job_min_salary,job_max_salary,person_salary,resume_num,job_num');
                $is_show_salary_tip = 0;
                if ($min_salary < $average_salary_job['job_min_salary']*0.9 && $average_salary_job) {
                    $is_show_salary_tip = 1;
                }
                $visit_item['is_show_salary_tip'] = $is_show_salary_tip;
                $visit_item['job_min_salary'] = $average_salary_job['job_min_salary'];
                $visit_item['job_max_salary'] = $average_salary_job['job_max_salary'];

                //简历回复率
                $reversion_rate = $service_replayrate_job->getByJobId($visit_item['job_id']);
                $reply_rate = base_lib_BaseUtils::formatnumber(floatval($reversion_rate['reply_rate']) * 100);
                $avg_time = base_lib_BaseUtils::formatnumber(floatval($reversion_rate['avg_time']));
                $visit_item['reply_rate'] = $reply_rate;
                $visit_item['avg_time'] = $avg_time;

                //聊一聊
                $visit_item['is_open_chat'] = $job_list[$visit_item['job_id']]['allow_online_talk'];

                //福利
                $job_reward = 0;
                if(empty($job_list[$visit_item['job_id']]['other_reward_ids']) && empty($job_list[$visit_item['job_id']]['other_reward_point'])){
                    $job_reward = 1;
                }
                $visit_item['job_reward'] = $job_reward;

                //承若回复
                $re_apply_type = $job_list[$visit_item['job_id']]['re_apply_type'];
                $re_apply_flag = 1;
                if($re_apply_type == 2 || $re_apply_type == 5){
                    $re_apply_flag = 0;
                }
                $visit_item['re_apply_flag'] = $re_apply_flag;

            }
            unset($visit_item);
            $this->_aParams['visit_data'] = $list->items;
            $visit_info_data = base_lib_BaseUtils::array_key_assoc($list->items,"job_id");
            $visit_info_ids = array_unique(base_lib_BaseUtils::getPropertys($list->items,"job_id"));
        }

        //获取详细信息
        $detail_job_data = [];
        if($detail_job_id){
            if($job_id){
                array_push($detail_job_data,$visit_info_data[$job_id]);
            }else{
                if($detail_job_id == 'all'){
                    foreach($visit_info_ids as $key => $val){
                        array_push($detail_job_data,$visit_info_data[$val]);
                    }
                }else{
                    array_push($detail_job_data,$visit_info_data[$detail_job_id]);
                }
            }
        }

        $this->_aParams['list'] = $detail_job_data;
        //企业风采 上传情况, 图片点亮
        //高管
        $base_service_introduce_management = new base_service_introduce_management();
        $managelist = $base_service_introduce_management->getListByCompanyId($this->_userid, 'id')->items;

        //企业图片
        $base_service_company_companyphotoalbum = new base_service_company_companyphotoalbum();
        $companyphotolist = $base_service_company_companyphotoalbum->getPhotoAlbumList($this->_userid, 'photo_path')->items;
        //项目
        $base_service_introduce_project = new base_service_introduce_project();
        $projectlist = $base_service_introduce_project->getListByCompanyId($this->_userid, 'id')->items;

        $this->_aParams['companyphoto'] = empty($companyphotolist) ? 0 : 1;
        $this->_aParams['managelist'] = empty($managelist) ? 0 : 1;
        $this->_aParams['projectlist'] = empty($projectlist) ? 0 : 1;

        //企业福利
        $service_company = new base_service_company_company();
        $company_select_rewards = $service_company->getCompany($this->_userid,'1','company_reward_ids,company_other_reward');
        $company_reward = 0;
        if(empty($company_select_rewards['company_reward_ids']) && empty($company_select_rewards['company_other_reward'])){
            $company_reward = 1;
        }
        $this->_aParams['company_reward'] = $company_reward;

        //企业差评
        $service_appraise   = new base_service_person_appraise_appraise();
        $appraise_object    = $service_appraise->getComplainAndStarByCompany($company_ids);
        $this->_aParams['appraise_flag'] = $appraise_object['complain_num'] > 0 ? 1 : 0;

        $this->_aParams['execl'] = $execl;

        if(!empty($execl)){
            $html = $this->render('readjob/view_table.html',$this->_aParams);
            $this->createExcel($html, null, $title, true);
        }
        return $this->render('readjob/view_index.html',$this->_aParams);
    }




    /**
     * 数组查询
     * @param array $arr
     * @param string $key
     * @param string $value
     */
    private function arrayFind($arr, $property, $value) {
        foreach ($arr as $item) {
            if ($item[ $property ] == $value) {
                return $item;
            }
        }

        return null;
    }


    /**
     * 获取职位信息并排序
     * @param  $job_ids
     */
    private function _getJobsAndSort($job_ids, $showStopJobApply=false){
        $service_job = new base_service_company_job_job();
        $jobs = $service_job->getJobs($job_ids, 'job_id,station,start_time,end_time,status,check_state,min_salary,urgent_end_time,work_year_id,jobsort_ids,allow_online_talk,other_reward_ids,other_reward,allow_graduate,re_apply_type');

        $validJob = array();
        $voidJob  = array();
        $status = new base_service_common_jobstatus();
        foreach ($jobs as $job) {
            $job['is_stop'] = 0;
            if ($job['status'] != $status->use || $job['check_state'] == 2 || base_lib_TimeUtil::time_diff_day($job['end_time'], date('Y-m-d H:i:s')) > 0) {
                if ($showStopJobApply == true) {
                    $job['station'] = $job['station'] . "<span class='orange'>(停招)</span>";
                    $job['is_stop'] = 1;
                    array_push($voidJob, $job);
                }

            } else {
                array_push($validJob, $job);
            }
        }

        return array_merge($validJob, $voidJob);
    }

    public function pageJobVisit($inPath){
        $pathData   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

        $xml = SXML::load('../config/config.xml');
        $this->_aParams['ShowResumeNum'] = $xml->ShowResumeNum;
        $this->_aParams['title'] = $title = "职位详情浏览统计_{$xml->HuiBoSiteName}";

        $execl = base_lib_BaseUtils::getStr($pathData['execl'], 'string', '');
        $cur_page = base_lib_BaseUtils::getStr($pathData['page'], 'int', 1);
        $page_size = base_lib_BaseUtils::getStr($pathData['page_size'], 'int',  base_lib_Constant::PAGE_SIZE);  //每页显示多少条

        $company_id = base_lib_BaseUtils::getStr($pathData['company_id'],'int','');
        $job_id = base_lib_BaseUtils::getStr($pathData['job_id'],'int','');

        $startDay = base_lib_BaseUtils::getStr($pathData['startDay'],'int','');
        $endDay = base_lib_BaseUtils::getStr($pathData['endDay'],'int','');

        if(empty($company_id)){
            $this->_aParams['message'] = '参数错误！';
            return $this->render('showerror.html', $this->_aParams);
        }
        $startDay = base_lib_BaseUtils::getStr($pathData['startDay'],'string',date('Y-m-d',strtotime('-7 day')));
        $startDay = date("Y-m-d",strtotime($startDay));
        $this->_aParams['startDay'] = $startDay;
        $this->_aParams['endDay'] = $endDay = base_lib_BaseUtils::getStr($pathData['endDay'],'string',date('Y-m-d'));

        $companyService       = new base_service_company_company();
        $accountid            = base_lib_BaseUtils::getCookie('accountid');
        $company_resources    = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
        $accounts             = $companyService->getCompanys($company_resources->all_accounts, "company_id,company_name,company_shortname,company_flag");
        $accounts_data        = array();
        $accounts_company_ids = base_lib_BaseUtils::getProperty($accounts, 'company_id');

        if(!in_array($company_id,$accounts_company_ids) && !empty($company_id)){
            $this->_aParams['message'] = '该企业编号不是本公司编号，请勿非法操作';
            return $this->render('showerror.html', $this->_aParams);
        }

        foreach($accounts as $item){
            $accounts_data[] = array('id'=>  $item['company_id'] , 'name'=> $item['company_name']);
        }
        
        $this->_aParams['first_account'] = empty ($company_id) ? $accounts_data[0] : ['id' => $company_id];
        $this->_aParams['accounts'] = json_encode($accounts_data);
        $this->_aParams['is_show_company_search'] = count($accounts) >= 2 ? true : false;
        $company_ids = empty($company_id) ? $accounts_company_ids : [$company_id];
        //获取近30天的访问职位ids
        $jobvisit_service = new base_service_company_job_companyjobvisit();
        $job_ids = $jobvisit_service->getJobIdsByCompany_id($company_ids);
        $job_ids = array_filter(explode(',',$job_ids['job_ids']));
//        $job_first = array('id'=>0 , 'name'=>'全部职位');

        $job_data = array(array('id'=>0 , 'name'=>'全部职位'));
        $job_list = array();
        $this->_aParams['jobFirst'] = array('id'=>0 , 'name'=>'全部职位');
        if(!empty($job_ids)){

            $job_service = new base_service_company_job_job();
            $job_list = $job_service->getJobs($job_ids,'job_id,station,is_effect,status,check_state,end_time');
            //if(status=1 and check_state<=1 and is_effect=1 and end_time >="2018-08-27",1,0)
            $job_list = base_lib_BaseUtils::array_key_assoc($job_list,'job_id');
            $this_time = strtotime(date('Y-m-d'));
            foreach($job_list as &$job_item){
                $temp_array = array();
                $temp_array['id'] = $job_item['job_id'];
                if($job_item['status'] == 1 && $job_item['check_state'] <=1 && $job_item['is_effect'] =1  && strtotime($job_item['end_time']) >= $this_time ){
                    $temp_array['name'] = $job_item['station'];
                }else{
                    $temp_array['name'] = $job_item['station'].'(停招)';
                }
                $job_data[] = $temp_array;
            }

        }

        if(!empty($job_id))
            $this->_aParams['jobFirst'] = ['id' => $job_id];
        
        $this->_aParams['jobListJson'] = json_encode($job_data);

        //查询数据职位数据访问数据
        $companyjobvisit_service = new base_service_company_job_companyjobvisit();

        $list->items = [];
        $list = $companyjobvisit_service->getJobVisitList($company_ids , $job_id , $startDay , $endDay , $cur_page , $page_size ,$execl);
        if(!empty($list->items)){
            $this->_aParams['pager'] = $this->pageBarFullPath($list->totalSize, $page_size, $cur_page, $inPath);
            foreach($list->items as &$visit_item){
                $visit_item['station'] = $job_list[$visit_item['job_id']]['station'];
                $visit_item['click_rate'] = round($visit_item['visit_count'] / $visit_item['exposes_count'] * 100,2);
                $visit_item['delivery_rate'] = round($visit_item['apply_count'] / $visit_item['visit_count'] * 100,2);
                $visit_item['invitation_rate'] = round($visit_item['invite_count'] / $visit_item['apply_count'] * 100,2);
            }
        }
        $this->_aParams['list'] = $list->items;
        $this->_aParams['execl'] = $execl;
        $this->_aParams['first_time'] = date('Y-m-d',strtotime('-30 day'));
        if(!empty($execl)){
            $this->_aParams['job_name'] = $job_list[$job_id]['station'];
            $html = $this->render('readjob/view_table1.html',$this->_aParams);
            $this->createExcel($html, null, $title, true);
        }
        return $this->render('readjob/view_job.html',$this->_aParams);
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
     * 获取几天前    hujian 2018/8/24 14:13:01
     */
    function get_day($date){
        if(empty($date)){
            return '';
        }
        $day=(strtotime(date('Y-m-d'))-strtotime(date('Y-m-d',strtotime($date))))/86400;
        if($day<0){
            $day=-1;
        }

        $return_str=date('Y-m-d',strtotime($date));
        switch($day){
            case 0:
                $return_str='今天';
                break;
            case 1:
                $return_str='昨天';
                break;
            case 2:
                $return_str='前天';
                break;
                break;
            case -1:
                $return_str='';
                break;
        }
        return $return_str;
    }


    /*
     * 获取同行业同职位类别的点击率、投递率、邀请率
     * */
    private function getListRate($company_id = '' , $job_id='' , $date_id = 1 , $this_rate){
        if(empty($company_id) && empty($job_id)){
            return array();
        }
        $type_name = "同行业同职位";
        $visit_data = array();
        //只有企业id 则查询同行业数据
        if(!empty($company_id) && empty($job_id)){
            $type_name = "同行业";
            $visit_service = new base_service_company_job_callingvisit();
            $visit_data = $visit_service->getVisitRateData($company_id  , $date_id);
        }
        //只有职位类别id 则查询同职位类别数据
        if(empty($company_id) && !empty($job_id)){
            $type_name = "同职位";
            $visit_service = new base_service_company_job_jobsortvisit();
            $visit_data = $visit_service->getVisitRateData($job_id  , $date_id);
        }
        //有企业id 职位类别id 则查询同行业同职位类别数据
        if(!empty($company_id) && !empty($job_id)){
            $type_name = "同行业同职位";
            $visit_service = new base_service_company_job_callingandjobsortvisit();
            $visit_data = $visit_service->getVisitRateData($company_id , $job_id , $date_id);
        }
        $visit_data['exposes_count'] = empty($visit_data['exposes_count']) ? 0 : (int)$visit_data['exposes_count'];
        $visit_data['visit_count'] = empty($visit_data['visit_count']) ? 0 : (int)$visit_data['visit_count'];
        $visit_data['apply_count'] = empty($visit_data['apply_count']) ? 0 : (int)$visit_data['apply_count'];
        $visit_data['invite_count'] = empty($visit_data['invite_count']) ? 0 : (int)$visit_data['invite_count'];

//        $visit_data['click_rate'] = round($visit_data['visit_count'] / $visit_data['exposes_count'] * 100,2);
//        $visit_data['delivery_rate'] = round($visit_data['apply_count'] / $visit_data['visit_count'] * 100,2);
//        $visit_data['invitation_rate'] = round($visit_data['invite_count'] / $visit_data['apply_count'] * 100,2);
        $visit_data['visit_rate_type'] = $type_name;

        $visit_data['click_rate_contrast'] = $this_rate['click_rate'] >= $visit_data['click_rate'] ? 1 : 0;
        $visit_data['delivery_rate_contrast'] = $this_rate['delivery_rate'] >= $visit_data['delivery_rate'] ? 1 : 0;
        $visit_data['invitation_rate_contrast'] = $this_rate['invitation_rate'] >= $visit_data['invitation_rate'] ? 1 : 0;

        return $visit_data;
    }

    /**
     * 导出excel    zhouwenjun 2016/12/29 14:58 copy
     * @param array|string $table_data      数据 array|html(table格式)
     * @param          $rows                array('user'=>"用户",'name'=>"用户名")
     * @param string $title                 标题
     * @param bool $excel_table             直接使用excel table输出
     */
    function createExcel($table_data, $rows, $title = '导出excel', $excel_table = false) {
        $title = iconv("utf-8", "gb2312", $title);
        if ($excel_table) {
            $this->SetExcelHeader($title, $title);
            die($table_data);
        }
        //数据太大了不建议使用 phpExcel导出数据,耗时太长
        if (count($table_data) > 2000) {
            $this->SetExcelHeader($title, $title);
            $this->SetExcelBody($table_data, $rows);
        } else {
//			$this->load->library("PHPExcel/Classes/PHPExcel.php");
//			new PHPExcel();
            //		$this->load->library("PHPExcel/Classes/PHPExcel/Writer/Excel5.php");
            $objexcel = SPHPExcel::CreatePHPExcel();
            $info_size = count($table_data);
            for ($i = 0; $i < count($rows); $i++) {
                $rows_value = array_values($rows);
                $rows_key = array_keys($rows);
                $pos = 0;

                //chr(65)==A Aasc码
                $objexcel->setActiveSheetIndex(0)->setCellValue(chr(65 + $i) . '1', strip_tags($rows_value[ $i ]));
                $objexcel->setActiveSheetIndex(0)->getColumnDimension(chr(65 + $i))->setWidth(15);
                $objexcel->getActiveSheet()->getStyle(chr(65 + $i) . '1')
                    ->applyFromArray(array (
                        'font'      => array ('bold' => true),
                        'alignment' => array (
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                        ),
                        'borders'   => array (
//							                 'allborders' => true,
                            'outline' => array (
                                'style' => PHPExcel_Style_Border::BORDER_THIN,   //设置border样式
//								                 'style' => PHPExcel_Style_Border::BORDER_THICK,  //另一种样式
                                'color' => array ('argb' => 'FF000000'),          //设置border颜色
                            ),
                        ),
                    ));
                if (!empty($table_data)) {
                    $table_data = array_values($table_data);
                    for ($j = 2; $j <= ($info_size + 1); $j++) {
//						$objexcel->getActiveSheet()->getStyle(chr(65 + $i))
//							->applyFromArray(array (
//								                 'alignment' => array (
//									                 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
//								                 ),
//							                 ));
                        $objexcel->getActiveSheet()->getStyle(chr(65 + $i) . $j)
                            ->applyFromArray(array (
                                'alignment' => array (
                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
                                ),
                                'borders'   => array (
//							                 'allborders' => true,
                                    'outline' => array (
                                        'style' => PHPExcel_Style_Border::BORDER_THIN,   //设置border样式
//								                 'style' => PHPExcel_Style_Border::BORDER_THICK,  //另一种样式
                                        'color' => array ('argb' => 'FF000000'),          //设置border颜色
                                    ),
                                ),
                            ));
                        $objexcel->setActiveSheetIndex(0)->setCellValue(chr(65 + $i) . $j, strip_tags($table_data[ $pos ][ $rows_key[ $i ] ]));
                        if ($pos < $info_size - 1) {
                            $pos++;
                        }
                    }

                }
            }

            header("content-type:application/vnd.ms-excel");
            header("Content-Transfer-Encoding: binary");
            header("content-disposition:attachment;filename={$title}.xls");
            header("Pragma: no-cache");
            $objwrite = new PHPExcel_Writer_Excel5($objexcel);
            $objwrite->save('php://output');
            exit;
        }
    }

    /**
     * 是否是主账号
     */
    private function isMain($account_id){
        $service_company_account = new base_service_company_account();
        $fields = 'account_id,company_id,is_main';
        $account = $service_company_account->getAccount($account_id, $fields);
        if($account['is_main'] == 1){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取某月的开始和结束
     * @param int $nian
     * @param int $yue
     * @return mixed
     */
    private function getMonthStartEnd($nian = 0, $yue = 0)
    {
        if (empty($nian) || empty($yue)) {
            $now  = time();
            $nian = date("Y", $now);
            $yue  = date("m", $now);
        }
        $time['begin'] = date('Y-m-d H:i:s', mktime(0, 0, 0, $yue, 1, $nian));
        $time['end']   = date('Y-m-d H:i:s', mktime(23, 59, 59, ($yue + 1), 0, $nian));

        return $time;
    }


    /**
     * 获取职位信息并排序
     * @param  $job_ids
     */
    private function _getJobsAndSortV2($job_ids, $show_use_job=1, $job_id=null, &$use_job_ids){
        $service_job = new base_service_company_job_job();
        $jobs = $service_job->getJobs($job_ids, 'job_id,station,end_time,status,check_state');


        $validJob = array();
        $voidJob  = array();

        $status = new base_service_common_jobstatus();
        foreach ($jobs as $job) {
            if ($job['status'] != $status->use
                || base_lib_TimeUtil::time_diff_day($job['end_time'], date('Y-m-d H:i:s')) > 0 || $job['check_state']>1) {
                if ($show_use_job) {
                    $job['station_all'] = $job['station'] . "<span class='orange'>(停招)</span>";
                    $job['station'] = base_lib_BaseUtils::cutstr($job['station'], 17, 'utf-8', '', '...') . "<span class='orange'>(停招)</span>";
                    array_push($voidJob, $job);
                }
            } else {
                $job['station_all'] = $job['station'];
                $job['station'] =  base_lib_BaseUtils::cutstr($job['station'], 19, 'utf-8', '', '...');
                $use_job_ids[] = $job['job_id'];
                array_push($validJob, $job);
            }
        }

        return array_merge($validJob, $voidJob);
    }


    public function pageRecruitmentEffect($inPath)
    {
        $pathData = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

        $xml                             = SXML::load('../config/config.xml');
        $this->_aParams['ShowResumeNum'] = $xml->ShowResumeNum;
        $this->_aParams['title']         = $title = "投递简历报表-汇博网";

        $execl            = base_lib_BaseUtils::getStr($pathData['execl'], 'string', '');
        $cur_page         = $this->_aParams['cur_page'] = base_lib_BaseUtils::getStr($pathData['page'], 'int', 1);
        $page_size        = base_lib_BaseUtils::getStr($pathData['page_size'], 'int', base_lib_Constant::PAGE_SIZE);  //每页显示多少条
        $company_id       = base_lib_BaseUtils::getStr($pathData['company_id'], 'int', '0');
        $job_id           = base_lib_BaseUtils::getStr($pathData['job_id'], 'int', '');
        $time_s            = base_lib_BaseUtils::getStr($pathData['time_s'], 'string', '');
        $time_e            = base_lib_BaseUtils::getStr($pathData['time_e'], 'string', '');
        $show_not_use_job = base_lib_BaseUtils::getStr($pathData['show_not_use'], 'int', 0);//是否不显示已停止招聘的职位

        $accountid         = base_lib_BaseUtils::getCookie('accountid');
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
        $companyresources  = $company_resources->getCompanyServiceSource();



        if (!empty($time_s) && !empty($time_e)) {
            $time['begin']  = $time_s;
            $time['end'] = $time_e;
        } else {
            $time['begin']  = date("Y-m-d",strtotime("-30 days"));
            $time['end'] = date('Y-m-d');
        }
        $time_start = $time['begin'];//------------------search：时间搜索条件------------------
        $time_end   = $time['end'];//------------------search：时间搜索条件------------------
        $this->_aParams['time_s'] = date('Y-m-d',strtotime($time_start));
        $this->_aParams['time_e'] = date('Y-m-d',strtotime($time_end));

        $this->_aParams['search_time_s'] = date('Y-m-d',strtotime($time_start));
        $this->_aParams['search_time_e'] = $this->getMonthStartEnd(date('Y', strtotime($time_end)), date('m', strtotime($time_end)))['end'];

        //当前登录是否是主账号
        $is_main        = $this->isMain($accountid);//当前登录是否为主账号
        $son_account_id = 0;//------------------search：职位发布人------------------
        if (empty($pathData['account_id'])) {
            //没有选择职位发布人的时候
            if ($is_main || $companyresources['resource_type']==1) {
                $son_account_id = 0;
            } else {
                $son_account_id = $accountid;
            }
        } else {
            $son_account_id = base_lib_BaseUtils::getStr($pathData['account_id'], 'int', 0);
        }



        $base_service_company_job_job = new base_service_company_job_job();
        $service_apply                = new base_service_company_resume_apply();
        $account_ids                  = $company_resources->all_accounts;
        $companyService               = new base_service_company_company();
        $accounts                     = $companyService->getCompanys($account_ids, "company_id,company_name,company_shortname,company_flag");
        $accounts_company_ids         = base_lib_BaseUtils::getProperty($accounts, 'company_id');
        if (!in_array($company_id, $accounts_company_ids) && !empty($company_id)) {
            $this->_aParams['message'] = '该企业编号不是本公司编号，请勿非法操作';

            return $this->render('showerror.html', $this->_aParams);
        }


        //获取所有账号
        $service_company_account = new base_service_company_account();
        $son_account_list        = $service_company_account->getAccountList($company_resources->all_accounts, 'account_id,company_id,is_main,user_id,user_name');


        //职位帅选条件查询
        $account_job_list = $base_service_company_job_job->getJobIdByAccount($company_resources->all_accounts, $son_account_id, 'company_id,job_id,account_id');
        $account_job_ids  = base_lib_BaseUtils::getProperty($account_job_list->items, 'job_id');

//        $hasApplyJobs = $service_apply->getHasApplyJobIdsNew($company_resources->all_accounts,$account_job_ids,$time_start,$time_end);
//        $job_ids = base_lib_BaseUtils::getProperty($hasApplyJobs->items, 'job_id');
//        $job_ids = array_unique($job_ids);

         $job_status = $show_not_use_job ?  null:2;
        $search_company_id = $company_id ? (array)$company_id:$company_resources->all_accounts;
         list($company_jobs, $total_size) = $base_service_company_job_job->getJobList($search_company_id,'',$job_status,"job_id,station",null,null,null,$son_account_id,null,true,null,['refresh_time' => -1]);
        $job_ids = base_lib_BaseUtils::getProperty($company_jobs, 'job_id');
        $job_ids = array_unique($job_ids);

        $jobs = $this->_getJobsAndSortV2($job_ids, $show_not_use_job, $job_id, $use_job_ids);
        if(!empty($jobs)){
            $jobs = base_lib_BaseUtils::array_key_assoc($jobs, 'job_id');
        }
        if ($son_account_id && empty($account_job_ids)) {
            $jobs = array();
        }


        //--------------------职位统计--------------------
//        $search_company_id = $company_id ? $company_id:$company_resources->all_accounts;
//        $res = $service_apply->RecruitmentEffectStatic($page_size,$cur_page,$search_company_id,$son_account_id,$job_id,$show_not_use_job,$time_start,$time_end);
//        $job_list = $res->items; getApplyPersonByTime



        if(!$execl){
            if($job_id){
                $job_list = $base_service_company_job_job->getJobs((array)$job_id,'job_id,station');
            }else{
                $job_list = $base_service_company_job_job->getJobList($search_company_id,'',$job_status,"job_id,station",$page_size,$cur_page,null,$son_account_id,null,false,null,['refresh_time' => -1]);
            }

        }else{

            //导出功能
            if(empty($job_id)) {
                if ($show_not_use_job) {
                    list($job_list) = $base_service_company_job_job->getJobList($search_company_id, '', 2, "job_id,station", null, null, null, $son_account_id, null, true, null, ['refresh_time' => -1]);
                    list($job_list_stop) = $base_service_company_job_job->getJobList($search_company_id, '', '0', "job_id,station", null, null, null, $son_account_id, null, true, null, ['refresh_time' => -1]);
                    $job_list = (array_merge((array)$job_list, (array)$job_list_stop));
                    $job_list = array_slice($job_list, 0, 1000);
                } else {
                    list($job_list) = $base_service_company_job_job->getJobList($search_company_id, '', 2, "job_id,station", 1000, 1, null, $son_account_id, null, true, null, ['refresh_time' => -1]);

                }
            }else{
                $job_list = $base_service_company_job_job->getJobs((array)$job_id,'job_id,station');
            }


        }


        //$_time_end 要处理成当月最后一天
        $time         = $this->getMonthStartEnd(date('Y', strtotime($time_end)), date('m', strtotime($time_end)));
        $_time_end_gosheach = $time['end'];//用于搜索的结束时间

        //邀请面试 面试到场 面试通过 入职人数 统计
        if(!empty($job_list)){
            $job_ids = base_lib_BaseUtils::getPropertys($job_list,'job_id');

            $res = $service_apply->getApplyNum($job_ids,$time_start,$_time_end_gosheach);
            if(!empty($res))
            {
                $res = base_lib_BaseUtils::array_key_assoc($res,'job_id');
            }

            $service_invite = new base_service_company_resume_jobinvite();
            $invit_statis = $service_invite->RecruitmentEffectStatic($job_ids,$time_start,$_time_end_gosheach);
            if(!empty($invit_statis))
            {
                $invit_statis = base_lib_BaseUtils::array_key_assoc($invit_statis,'job_id');
            }

            $remain_tip = "";
            $need_remain_job_stations = [];
            foreach ($job_list as &$list){
                $list['total_count'] = (int)$res[$list['job_id']]['total_count'];//邀请总数
                $list['invit_num'] = (int)$invit_statis[$list['job_id']]['invit_num'];//邀请总数
                $list['fail_num'] = (int)$invit_statis[$list['job_id']]['fail_num'];//爽约
                $list['present_num'] = $list['invit_num'] -   $list['fail_num'];
                $list['present_num'] = $list['present_num']>0? $list['present_num']:0;//到场
                $list['pass_num'] = (int)$invit_statis[$list['job_id']]['pass_num'];//通过
                $list['entry_num'] = (int)$invit_statis[$list['job_id']]['entry_num'];//入职
                $list['notpass_num'] = (int)$invit_statis[$list['job_id']]['notpass_num'];//未通过

                $list['station_new'] = $jobs[$list['job_id']]['station_all'];

                //面试爽约+面试未通过+面试通过+入职人数数据＜邀请面试人数 需提醒去设置
                $list['need_remin'] = false;
                if( ($list['fail_num'] + $list['notpass_num'] +  $list['pass_num'] + $list['entry_num']) < $list['invit_num']){
                    $list['need_remin'] = true;
                    $need_remain_job_stations[] = $list['station'];
                }

            }

            if(count($need_remain_job_stations)>0){
                if(count($need_remain_job_stations)>3){
                    $remain_tip = implode("、",$need_remain_job_stations)."等".count($need_remain_job_stations)."个职位未设置面试或入职结果，数据可能不准确";
                }else{
                    $remain_tip = implode("、",$need_remain_job_stations)."未设置面试或入职结果，数据可能不准确";
                }
            }

        }

        $this->_aParams['remain_tip'] = $remain_tip;


        //-----------------------------帅选条件组装---------------------------------


        //------职位发布人------
        $job_people = [];
        if ($is_main || $companyresources['resource_type'] == 1) {
            array_push($job_people, ["id" => '0', "name" => "全部"]);
            foreach ($son_account_list->items as $val) {
                array_push($job_people, ["id" => $val['account_id'], "name" => $val['user_name']]);
            }
        } else {

            foreach ($son_account_list->items as $val) {
                if ($val['account_id'] == $accountid) {
                    array_push($job_people, ["id" => $val['account_id'], "name" => $val['user_name']]);
                    break;
                } else {
                    continue;
                }
            }
        }

        $this->_aParams['job_people']     = json_encode($job_people);
        $this->_aParams['account_id']     = $accountid;//当前登录账号
        $this->_aParams['son_account_id'] = $son_account_id;//当前选择账号


        //----------导出excel----------
        if (!empty($execl)) {
            $this->_aParams['list'] = $job_list;
            $html = $this->render('readjob/view_table2.html',$this->_aParams);
            $this->createExcel($html, null,  $time_start."到". $time_end.$title, true);
        }


        //----- 年份 最近5年------
        $years = [];
        $now_Y=date('Y');
        for($i=$now_Y;$i>=$now_Y-4;$i--){
            array_push($years, ["id" => $i, "name" => "{$i}年"]);
        }
        $this->_aParams['year_json'] = json_encode($years);

        //------月份--------
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            array_push($months, ["id" => $i, "name" => "{$i}月"]);
        }
        $this->_aParams['month_json'] = json_encode($months);


        //------职位筛选项--------
        $jobs_json = [];
        array_push($jobs_json, ["id" => "", "name" => "全部职位"]);
        foreach ($jobs as $job)
            array_push($jobs_json, ["id" => $job['job_id'], "name" => $job['station']]);
        $this->_aParams['jobs_json'] = json_encode($jobs_json);
        $this->_aParams['job_id']    = $job_id;


        $this->_aParams['show_not_use_job'] = $show_not_use_job;


        //--------企业----------
        $accounts_data = [];
        array_push($accounts_data, ["id" => "0", "name" => "全部企业"]);
        foreach ($accounts as $item) {
            $accounts_data[] = array('id' => $item['company_id'], 'name' => $item['company_shortname']);
        }
        $this->_aParams['company_id']             = $company_id;
        $this->_aParams['accounts']               = json_encode($accounts_data);
        $this->_aParams['is_show_company_search'] = count($accounts) >= 2 ? true : false;


        /* 页码 */
        //$pager = $this->pageBarFullPath($res->totalSize, $page_size, $cur_page, $inPath);
        $total_size = $job_id ? 1:$total_size;
        $pager = $this->pageBarFullPath($total_size, $page_size, $cur_page, $inPath);

        $this->_aParams['page'] = $pager;
        $this->_aParams['list'] = $job_list;
        return $this->render('readjob/recruitmenteffect.html', $this->_aParams);
    }

    public function pageGetResemeRecruitmentEffect($inPath)
    {
        $pathData = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

        $xml                             = SXML::load('../config/config.xml');
        $this->_aParams['ShowResumeNum'] = $xml->ShowResumeNum;
        $this->_aParams['title']         = $title = "获取简历报表-汇博网";

        $execl            = base_lib_BaseUtils::getStr($pathData['execl'], 'string', '');
        $cur_page         = $this->_aParams['cur_page'] = base_lib_BaseUtils::getStr($pathData['page'], 'int', 1);
        $page_size        = base_lib_BaseUtils::getStr($pathData['page_size'], 'int', 10);  //每页显示多少条
        $company_id       = base_lib_BaseUtils::getStr($pathData['company_id'], 'int', '0');
        $job_id           = base_lib_BaseUtils::getStr($pathData['job_id'], 'int', '');
        $time_s            = base_lib_BaseUtils::getStr($pathData['time_s'], 'string', '');
        $time_e            = base_lib_BaseUtils::getStr($pathData['time_e'], 'string', '');
        $show_not_use_job = base_lib_BaseUtils::getStr($pathData['show_not_use'], 'int', 0);//是否不显示已停止招聘的职位
        $search_account_id = base_lib_BaseUtils::getStr($pathData['account_id'], 'int', 0);

        $accountid         = base_lib_BaseUtils::getCookie('accountid');
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
        $companyresources  = $company_resources->getCompanyServiceSource();



        if (!empty($time_s) && !empty($time_e)) {
            $time['begin']  = $time_s;
            $time['end'] = $time_e;
        } else {
            //如果有一个为空，默认最近12个月
            $time['end'] = date('Y-m-01',strtotime("-1 month"));
            $time['begin'] = date('Y-m-01',strtotime("-12 month"));
        }
        if($time['begin'] > $time['end'])
            list($time['begin'],$time['end']) = [ $time['end'], $time['begin'] ];

        $time_start =  date('Y-m-01',strtotime($time['begin']));//------------------search：时间搜索条件------------------
        $time_end   = date('Y-m-01',strtotime( $time['end']));//------------------search：时间搜索条件------------------
        $this->_aParams['time_s'] = date('Y-m',strtotime($time_start));
        $this->_aParams['time_e'] = date('Y-m',strtotime($time_end));

        $this->_aParams['search_time_s'] = date('Y-m-d',strtotime($time_start));
        $this->_aParams['search_time_e'] = date('Y-m-d',strtotime($this->getMonthStartEnd(date('Y', strtotime($time_end)), date('m', strtotime($time_end)))['end']));


        //当前登录是否是主账号
        $is_main        = $this->isMain($accountid);//当前登录是否为主账号
        $son_account_id = 0;//------------------search：职位发布人------------------
        if (empty($pathData['account_id'])) {
            //没有选择职位发布人的时候
            if ($is_main || $companyresources['resource_type']==1) {
                $son_account_id = 0;
            } else {
                $son_account_id = $accountid;
            }
        } else {
            $son_account_id = $search_account_id;
        }



        $base_service_company_job_job = new base_service_company_job_job();
        $service_apply                = new base_service_company_resume_apply();
        $account_ids                  = $company_resources->all_accounts;
        $companyService               = new base_service_company_company();
        $accounts                     = $companyService->getCompanys($account_ids, "company_id,company_name,company_shortname,company_flag");
        $accounts_company_ids         = base_lib_BaseUtils::getProperty($accounts, 'company_id');
        if (!in_array($company_id, $accounts_company_ids) && !empty($company_id)) {
            $this->_aParams['message'] = '该企业编号不是本公司编号，请勿非法操作';

            return $this->render('showerror.html', $this->_aParams);
        }


        //获取所有账号
        $service_company_account = new base_service_company_account();
        $son_account_list        = $service_company_account->getAccountList($company_resources->all_accounts, 'account_id,company_id,is_main,user_id,user_name');


        //职位帅选条件查询
        $account_job_list = $base_service_company_job_job->getJobIdByAccount($company_resources->all_accounts, $son_account_id, 'company_id,job_id,account_id');
        $account_job_ids  = base_lib_BaseUtils::getProperty($account_job_list->items, 'job_id');


        $job_status = $show_not_use_job ?  null:2;
        $search_company_id = $company_id ? (array)$company_id:$company_resources->all_accounts;
        list($company_jobs, $total_size) = $base_service_company_job_job->getJobList($search_company_id,'',$job_status,"job_id,station",null,null,null,$son_account_id,null,true,null,['refresh_time' => -1]);
        $job_ids = base_lib_BaseUtils::getProperty($company_jobs, 'job_id');
        $job_ids = array_unique($job_ids);

        $jobs = $this->_getJobsAndSortV2($job_ids, $show_not_use_job, $job_id, $use_job_ids);
        if(!empty($jobs)){
            $jobs = base_lib_BaseUtils::array_key_assoc($jobs, 'job_id');
        }
        if ($son_account_id && empty($account_job_ids)) {
            $jobs = array();
        }

        

        //----------------- 翻页处理-------------------

        if($execl){
            //最多导出10个月的数据
            $cur_page=1;
            $page_size=10;
        }

        $i=0;
        $how_much = 0;
        do{

            $_key = date('Y-m',strtotime("{$time_end} -{$i} month"));
            $i++;


            if(($cur_page-1)*$page_size == ($i-1)){
                $how_much = 1;
                $_time_end = date("Y-m-01 23:59:59",strtotime($_key));

            }

            if($how_much==$page_size){
                $_time_start = date("Y-m-01",strtotime( date('Y-m-01',strtotime($_key))." +0 month"));
            }

            if($how_much>=1){
                $how_much++;
            }

            $go_on = $_key == date('Y-m',strtotime($time_start))? false:true;
            if($i>=100){
                $go_on = false;//防止意外死循环
            }
        }while($go_on);
        $total_month = $i;
        if(empty($_time_start))
            $_time_start = $time_start;


        if(empty($_time_end) || empty($_time_start))
        {
            goto End;
        }

        //$_time_end 要处理成当月最后一天
        $time         = $this->getMonthStartEnd(date('Y', strtotime($_time_end)), date('m', strtotime($_time_end)));
        $_time_end_gosheach = $time['end'];//用于搜索的结束时间


        //---------------- 获取的简历数量-----------
        $service_down = new base_service_company_resume_download();
        $res = $service_down->getTotalNumByMonth($company_resources->all_accounts,$search_account_id,$_time_start,$_time_end_gosheach);

        if(!empty($res))
        {
            $res = base_lib_BaseUtils::array_key_assoc($res,'month');
        }
        //如果有哪个月的数据为空需插入空数据
        $i=0;
        do{

            $_key = date('Y-m',strtotime(date('Y-m-d',strtotime($_time_end))." -{$i} month"));
            if(empty($res[$_key])){
                $res[$_key] = ['month'=>$_key,"total_num"=>0];
            }
            $i++;
            $go_on = $_key == date('Y-m',strtotime($_time_start))? false:true;

            //获取这个月的开始和结束
            $_time = $this->getMonthStartEnd(date('Y',strtotime($_key)),date('m',strtotime($_key)));

            $res[$_key]['time_s'] = $_time['begin'];
            $res[$_key]['time_end'] = $_time['end'];

            if($i>=100){
                $go_on = false;//防止意外死循环
            }
        }while($go_on);
        //倒序排
        $res = $this->insertSort(array_values($res));
        $res = base_lib_BaseUtils::array_key_assoc($res,'month');

        $service_invite = new base_service_company_resume_jobinvite();
        $remain_tip = "";
        $need_remain_job_stations = [];
        foreach ($res as &$list) {
            //--------------邀请统计--------
            $invit_statis = $service_invite->getResumeRecruitmentEffectStaticV2($company_resources->all_accounts, [], $list['resume_ids']);

            $list['total_count'] = (int)$res[ $list['month'] ]['total_num'];//邀请总数
            $list['invit_num']   = (int)$invit_statis['invit_num'];//邀请总数
            $list['fail_num']    = (int)$invit_statis['fail_num'];//爽约
            $list['present_num'] = $list['invit_num'] - $list['fail_num'];
            $list['present_num'] = $list['present_num'] > 0 ? $list['present_num'] : 0;//到场
            $list['pass_num']    = (int)$invit_statis['pass_num'];//通过
            $list['entry_num']   = (int)$invit_statis['entry_num'];//入职
            $list['notpass_num'] = (int)$invit_statis['notpass_num'];//未通过

            $list['station_new'] = $jobs[ $list['month'] ]['station_all'];
            $list['time_s']      = date('Y-m-d', strtotime($res[ $list['month'] ]['time_s']));
            $list['time_end']    = date('Y-m-d', strtotime($res[ $list['month'] ]['time_end']));

            //面试爽约+面试未通过+面试通过+入职人数数据＜邀请面试人数 需提醒去设置
            $list['need_remin'] = false;
            if( ($list['fail_num'] + $list['notpass_num'] +  $list['pass_num'] + $list['entry_num']) < $list['invit_num']){
                $list['need_remin'] = true;
                $need_remain_job_stations[] = $list['station'];
            }

            if(count($need_remain_job_stations)>0){
                if(count($need_remain_job_stations)>3){
                    $remain_tip = implode("、",$need_remain_job_stations)."等".count($need_remain_job_stations)."个职位未设置面试或入职结果，数据可能不准确";
                }else{
                    $remain_tip = implode("、",$need_remain_job_stations)."未设置面试或入职结果，数据可能不准确";
                }
            }
        }

        $this->_aParams['remain_tip'] = $remain_tip;
        //-----------------------------帅选条件组装---------------------------------
        End:

        //------职位发布人------
        $job_people = [];
        if ($is_main || $companyresources['resource_type'] == 1) {
            array_push($job_people, ["id" => '0', "name" => "全部"]);
            foreach ($son_account_list->items as $val) {
                array_push($job_people, ["id" => $val['account_id'], "name" => $val['user_name']]);
            }
        } else {

            foreach ($son_account_list->items as $val) {
                if ($val['account_id'] == $accountid) {
                    array_push($job_people, ["id" => $val['account_id'], "name" => $val['user_name']]);
                    break;
                } else {
                    continue;
                }
            }
        }

        $this->_aParams['job_people']     = json_encode($job_people);
        $this->_aParams['account_id']     = $accountid;//当前登录账号
        $this->_aParams['son_account_id'] = $son_account_id;//当前选择账号


        //----------导出excel----------
        if (!empty($execl)) {
            $this->_aParams['list'] = $res;
            $html = $this->render('readjob/view_table3.html',$this->_aParams);
            $this->createExcel($html, null,  $_time_start."到". date('Y-m-d',strtotime($_time_end)).$title, true);
        }


        //----- 年份 最近5年------
        $years = [];
        $now_Y=date('Y');
        for($i=$now_Y;$i>=$now_Y-4;$i--){
            array_push($years, ["id" => $i, "name" => "{$i}年"]);
        }
        $this->_aParams['year_json'] = json_encode($years);

        //------月份--------
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            array_push($months, ["id" => $i, "name" => "{$i}月"]);
        }
        $this->_aParams['month_json'] = json_encode($months);


        //------职位筛选项--------
        $jobs_json = [];
        array_push($jobs_json, ["id" => "", "name" => "全部职位"]);
        foreach ($jobs as $job)
            array_push($jobs_json, ["id" => $job['job_id'], "name" => $job['station']]);
        $this->_aParams['jobs_json'] = json_encode($jobs_json);
        $this->_aParams['job_id']    = $job_id;


        $this->_aParams['show_not_use_job'] = $show_not_use_job;


        //--------企业----------
        $accounts_data = [];
        array_push($accounts_data, ["id" => "0", "name" => "全部企业"]);
        foreach ($accounts as $item) {
            $accounts_data[] = array('id' => $item['company_id'], 'name' => $item['company_shortname']);
        }
        $this->_aParams['company_id']             = $company_id;
        $this->_aParams['accounts']               = json_encode($accounts_data);
        $this->_aParams['is_show_company_search'] = count($accounts) >= 2 ? true : false;


        /* 页码 */
        $pager = $this->pageBarFullPath($total_month, $page_size, $cur_page, $inPath);

        $this->_aParams['page'] = $pager;
        $this->_aParams['list'] = $res;
        return $this->render('readjob/getresumerecruitmenteffect.html', $this->_aParams);
    }



    function insertSort($arr)
    {
        $len = count($arr);
        for ($i = 1; $i < $len; $i++) {
            $tmp = $arr[ $i ];
            //内层循环控制，比较并插入
            for ($j = $i - 1; $j >= 0; $j--) {
                if ($tmp['month'] > $arr[ $j ]['month']) {
                    //发现插入的元素要小，交换位置，将后边的元素与前面的元素互换
                    $arr[ $j + 1 ] = $arr[ $j ];
                    $arr[ $j ]     = $tmp;
                } else {
                    //如果碰到不需要移动的元素，由于是已经排序好是数组，则前面的就不需要再次比较了。
                    break;
                }
            }
        }

        return $arr;
    }
}