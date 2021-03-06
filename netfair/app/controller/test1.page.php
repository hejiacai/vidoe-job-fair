<?php

/**
 * 测试
 */
class controller_test1 extends components_cbasepage {

    private $boss_degree = [
        '01'    => '初中及以下',
        '02'    => '高中',
        '03'    => '中技/中专',
        '05'    => '大专',
        '06'    => '本科',
        '07'    => '硕士',
        '08'    => '博士'
    ];

    //工作状态
    private $boss_job_status = [
        '1'         => '离职-随时到岗',
        '2'         => '在职-月内到岗',
        '3'         => '在职-考虑机会',
        '4'         => '在职-暂不考虑',
    ];

    /**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct(false);
	}

    public function pagesendnotice(){
        header("Content-type: text/html; charset=utf-8");
        $service_netfair_personapplynet = new base_service_netfair_personapplynet();
        $service_netfair_personapplynet->sendSecondInterviewNotice();
    }

    public function pageShortMsg(){
        $service_company = new base_service_blue_company_company();
        $company_info = $service_company->getCompany(593,'1','link_mobile,link_tel,password');
        $service_autologinurl = new    base_service_common_autologinurl();
        $sms_shortlink = $service_autologinurl->getAutoLoginShortUrl($company_info['link_mobile'], $company_info['password'], 1, base_lib_Constant::MBLUE_URL . '/company/index',3);
        var_dump($sms_shortlink);
    }


    public function pageindex(){
        ini_set("display_errors", "On");
        error_reporting(E_ALL || E_STRICT);
        $file_path = "C:/Users/Administrator/Documents/WXWork/1688851855611008/Cache/File/2020-03/fb56ccc933a3353e03x609y6FlI~.html";
        $htmlResumeData = $this->importBossResumeDo($file_path);
        var_dump($htmlResumeData);
    }


    /**
     * 解析boss直聘简历
     * @param $file_path
     * @return array
     */
    private function importBossResumeDo($file_path){
        if (empty($file_path)) {
            return ['code' => ERROR , 'msg' => '文件路径为空'];
        }
        $max_login_time = date("Y-m-d",strtotime("-6 month"));

        $service_person_person = new base_service_person_person();
        $service_person_resume_resume = new base_service_person_resume_resume();
        $resumeData = $this->getResumeDataByJson($file_path);
        var_dump($resumeData);
        exit();
        SlightPHP::log("boss直聘简历解析-arr:".print_r($resumeData,true));
        $resume_info_json = $resumeData['data'];
        if(!$resumeData['status']) {
            if ($resumeData['is_has_mobile']) {
                //电话已存在，则判断是否半年内登录，如果已登录，则返回resumeid到周文君，没有登录，则覆盖以前简历
                $person_info1 = $service_person_person->getPersonByPhone($resume_info_json['mobile_phone'], '', "person_id,login_time", null, 1);
                if ($person_info1) {
                    $resume_info1 = $service_person_resume_resume->getDefaultResume($person_info1['person_id'], "resume_id");
                    if ($person_info1['login_time'] >= $max_login_time) {
                        //返回成功，并将resume_id返回
                    } else {
                        //覆盖之前简历信息
                        $service_person_resume_resume->coverResumeBy51Job($resume_info_json, $resume_info_json, $person_info1['person_id'], $resume_info1['resume_id']);
                    }
                    return array('code' => SUCCESS, 'msg' => "导入成功", 'mobile_phone' => $resumeData['mobile_phone'], 'resume_id' => $resume_info1['resume_id']);
                }
            }
            return array ('code' => ERROR, 'msg' => $resumeData['msg'], 'mobile_phone' => $resumeData['mobile_phone']);
        }

        //添加简历
        $result = $service_person_resume_resume->addResumeBy51Job($resume_info_json, $resume_info_json);
        var_dump($result);
        if ($result['code'] == ERROR) {
            return array ('code' => ERROR, 'msg' => $result['msg'], 'mobile_phone' => $resume_info_json['mobile_phone']);
        }
        else {
            return array ('code' => SUCCESS, 'msg' => "导入成功", 'mobile_phone' => $resume_info_json['mobile_phone'], 'resume_id' => $result['resume_id']);
        }

    }



    public function getResumeDataByJson($file_path){
        if(!is_file($file_path) || !file_exists($file_path)){
            return ['status' => false , 'msg' => '文件错误'];
        }
        $fd = fopen($file_path,"rb");
        $file_content = @fread($fd, filesize($file_path));
        fclose($fd);
//        $file_content = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $file_content);
//        $file_content = '{"zpData":{"data":{"uid":99357863,"encryptUid":"6a91176291160ac83n153tq1FlE~","lastTime":"15:36","encryptExpectId":"2edd9c42608dcfcc1nV-0966F1FU","addTime":"2020-03-19","year":"1年","lowSalary":8,"highSalary":12,"salaryDesc":"8-12K","showCandidate":0,"firstTalk":false,"major":"信息管理","school":"齐齐哈尔大学","price":"11-16K","bothTalked":true,"block":false,"applyStatusDes":"曾任","applyStatusDes2":null,"ageDesc":"25岁","toPosition":"产品经理","weixinVisible":0,"sourceTitle":null,"labels":[],"newGeek":0,"jobId":39292560,"weixin":null,"toPositionId":"054e339e084ccfde1H140t-4FlI~","phone":"17783580946","sourceType":2,"sourceExtend":null,"isTop":0,"edu":"本科","name":"好玩","largeAvatar":"https://img.bosszhipin.com/boss/avatar/avatar_13.png","position":"项目经理","positionName":"产品经理","applyStatus":2,"lastCompany2":null,"note":"","gender":1,"city":"重庆","resumeVisible":0,"mobileVisible":1,"cooperate":1,"expectId":114837734,"expectType":0,"outCount":1,"inCount":1,"belongGroup":null,"lastPosition":"PHP","avatar":"https://img.bosszhipin.com/boss/avatar/avatar_13.png","lastCompany":"重庆时报","positionStatus":"在职-考虑机会","initTime":"15:35","interview":0,"lastPosition2":null,"relationType":6,"securityId":"uCkR4PzwATpn_XPrA65czaRFCAZ_qD6hM8YyttmFJME0tEEXpT0eShNCLqRdtqDls0IsYUnGE3KYmPDhF5KBB3ky0B8S0URXUWhefes2AR5RY31IaiQadEJRYY2iUPBqtjsR36DPlCQdcZdBmUYlHjqWKTIizKG-Il7y_g8QZxEQFa4TAFUraMXxAkSgomHbLudrhJFjYfOFUoeY9TjIojvA5fkkFqnJz6A8Gt4fCh-DcDhuK8M3kRceWCX3CTL7lMeg2JI~","filterReasonList":null,"everWorkPositionNameList":null,"workExpList":[{"timeDesc":"2014.08-2015.12","company":"重庆时报","positionName":"PHP"}],"eduExpList":[{"timeDesc":"2010.01-2014.01","school":"齐齐哈尔大学","major":"信息管理","degree":"本科"}],"activeTimeDesc":"刚刚活跃","completeType":0,"isSystemJob":false,"isCloseInterview":false,"isFiltered":false},"status":1}}';

//        $file_content = htmlspecialchars_decode($file_content);
//        var_dump($file_content);
        $char = mb_substr($file_content,0,1);
        if(ord($char) == 239){
            $file_content = mb_substr($file_content,1,mb_strlen($file_content) -1);
        }
        $file_data = json_decode($file_content,true);
        if(empty($file_data) || empty($file_data['zpData']['data'])){
            return ['status' => false , 'msg' => '解析文件失败'];
        }


        $resume_data = $file_data['zpData']['data'];

        $validator = new base_lib_Validator();
        $resume_info = [];

        $user_name = $validator->getStr($resume_data['name'], 2, 6, 'html简历姓名为2-6个汉字');
        $mobile_phone = $validator->getMobile($resume_data['phone'], '手机号码不正确');
        if($validator->has_err){
            return ['status' => false , 'msg' => implode(',',$validator->err)];
        }

        $service_person = new base_service_person_person();
        $mobile_result = $service_person->checkPhoneUnique($mobile_phone, 0, null);
        $is_has_mobile = false;
        if (!$mobile_result) {
            $is_has_mobile = true;
        }

        $resume_info['mobile_phone']                    = $mobile_phone;
        $resume_info['user_name']                            = trim($user_name);
        $resume_info['age']                             = intval(trim($resume_data['ageDesc']));
        $resume_info['sex']                             = intval(trim($resume_data['gender']));
        //学历
        $resume_info['degree_id']                       = trim($resume_data['edu']) ? array_search(trim($resume_data['edu']),$this->boss_degree) : '';
        $resume_info['school']                          = trim($resume_data['school']);
        $resume_info['major']                           = trim($resume_data['major']);
        //期望薪资
        $salary = (intval($resume_data['lowSalary'])/2 + intval($resume_data['highSalary'])/2) * 1000;
        if (!empty($salary)) {
            if ($salary < 1500) {
                $salary = "01";// 1000及以上
            }
            else if ($salary < 2000) {
                $salary = "02";// 1500及以上
            }
            else if ($salary < 2500) {
                $salary = "03";//  2000及以上
            }
            else if ($salary < 3000) {
                $salary = "04";//  2500及以上
            }
            else if ($salary < 4000) {
                $salary = "05";//  3000及以上
            }
            else if ($salary < 5000) {
                $salary = "06";//  4000及以上
            }
            else if ($salary < 6000) {
                $salary = "07";//  5000及以上
            }
            else if ($salary < 7000) {
                $salary = "08";//  6000及以上
            }
            else if ($salary < 8000) {
                $salary = "09";//  7000及以上
            }
            else if ($salary < 9000) {
                $salary = "10";//  8000及以上
            }
            else if ($salary < 10000) {
                $salary = "11";//  9000及以上
            }
            else if ($salary < 12000) {
                $salary = "12";//  10000及以上
            }
            else if ($salary < 15000) {
                $salary = "13";//  12000及以上
            }
            else if ($salary < 20000) {
                $salary = "14";//  15000及以上
            }
            else if ($salary < 30000) {
                $salary = "15";//  20000及以上
            }
            else if ($salary >= 30000) {
                $salary = "16";//  30000及以上
            }
        }
        else {
            $salary = "";
        }
        $resume_info['exp_salary']                      = $salary;

        //工作状态
        $resume_info['job_status']                      = trim($resume_data['positionStatus']) ? array_search(trim($resume_data['positionStatus']),$this->boss_job_status) : '';

//        $resume_info['home_address']                    = $resume_data['city'];
//        $resume_info['marriage']                        = $resume_data['exp_salary'];
//        $resume_info['height']                          = $resume_data['exp_salary'];
//        $resume_info['native_place']                    = $resume_data['exp_salary'];
//        $resume_info['appraise']                        = $resume_data['exp_salary'];


        $base_service_company_codearea = new base_service_company_codearea();
        //获取意向地区
        $exp_area_ids = array ();
        if ($resume_data['city']) {
            $exp_area_arr = explode(',', $resume_data['city']);
            foreach ($exp_area_arr as $key => $val) {
                $area_info = $base_service_company_codearea->getAreaIdByAreaName($val);
                if ($area_info['area_id']) {
                    $exp_area_ids[ $key ] = $area_info['area_id'];
                }
            }
            $resume_info['exp_area_arr'] = $exp_area_ids;
            $resume_info['address'] = $resume_data['city'];
        }

        $service_job_outerclock = new base_service_job_outerclock();

        //意向职位类别
        $exp_jobsort_ids = array ();
        if ($resume_data['position']) {
            $exp_jobsort_arr = explode(',', $resume_data['position']);
            foreach ($exp_jobsort_arr as $key => $val) {
                $jobsort_info = $service_job_outerclock->getOuterClock($val, 'jobsort', 3);
                if ($jobsort_info && !empty($jobsort_info['inner_id'])) {
                    $exp_jobsort_ids[ $key ] = $jobsort_info['inner_id'];
                }
            }
            $resume_info['exp_jobsort_ids'] = $exp_jobsort_ids;
            $resume_info['exp_jobsort'] = $resume_data['position'];
        }


        //教育经历
        $edu_list = [];
        if($resume_data['eduExpList']){
            foreach($resume_data['eduExpList'] as $key => $value){
                $edu_list[$key]['school']           = $value['school'];
                $edu_list[$key]['major']            = $value['major'];
                $edu_list[$key]['degree']           = trim($value['degree'] ) ? array_search(trim($value['degree'] ),$this->boss_degree) : '';
                //时间
                $edu_time = explode('-',$value['timeDesc']);
                $start_time = $edu_time[0].".01";
                $end_time = $edu_time[1].".01";
                $start_time = str_replace('.','-',$start_time);
                $end_time = str_replace('.','-',$end_time);
                $edu_list[$key]['start_time'] = strtotime($start_time) ? date("Y-m-d",strtotime($start_time)) : '';
                $edu_list[$key]['end_time'] = strtotime($end_time) ? date("Y-m-d",strtotime($end_time)) : '';
            }
        }
        $resume_info['edu_list'] = $edu_list;

        //工作经历
        $work_list = [];
        if($resume_data['workExpList']){
            foreach($resume_data['workExpList'] as $key => $value){
                $work_list[$key]['company_name']           = $value['company'];
                $work_list[$key]['station']                = $value['positionName'];

                //时间
                $edu_time = explode('-',$value['timeDesc']);
                $start_time = $edu_time[0].".01";
                $end_time = $edu_time[1].".01";
                $start_time = str_replace('.','-',$start_time);
                $end_time = str_replace('.','-',$end_time);
                $work_list[$key]['start_time'] = strtotime($start_time) ? date("Y-m-d",strtotime($start_time)) : '';
                $work_list[$key]['end_time'] = strtotime($end_time) ? date("Y-m-d",strtotime($end_time)) : '';
            }
        }
        $resume_info['work_list'] = $work_list;
        var_dump($resume_info);
        return ['status' => true , 'data' => $resume_info , 'is_has_mobile' => $is_has_mobile];
    }

	
}

?>
