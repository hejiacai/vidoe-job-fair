<?php
/**
 * 企业聊一聊
 * @ClassName controller_chat
 * @Desc 自动登录
 * @author huangwentao@huibo.com
 * @date 2016-12-14 上午10:30:46
 */
class controller_chat extends components_cbasepage {

    function __construct() {
        parent::__construct(true);
    }

    /**
     * 入口
     */
    public function pageIndex($inPath) {
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $is_https = $this->isHttps();
        if(!$is_https){
             $this->redirect_url2("https:".base_lib_Constant::COMPANY_URL_NO_HTTP."/chat/",$path_data);
         }
        $company_id     = $this->_userid;
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        $service_chat   = new  company_service_chat();
        // $bind_person_id = $service_chat->getBindPersonId();
        /* if(empty($bind_person_id)){
             return $this->render("./chat/notbind.html",$this->_aParams);
         }*/
        //获取浏览器版本
        $browser = $service_chat->getbrowser();
        //if($browser["browser"] == "IE" && $browser["version"] <=8){
        if($browser["browser"] == "IE" || $this->_isIE()){
            return $this->render("./chat/notchat.html",$this->_aParams);
        }

        $service_swychat = new SWYChat("APP_COM");
        $swy_info_data = $service_swychat->getAccountInfo($account_id);
        list($nick_name,$photo) = $service_swychat->getAccountNameAndPhoto($account_id,'APP_COM');
        $swy_info['appkey'] = $service_swychat->getAppkey();
        $swy_info['accid'] = $swy_info_data[0];
        $swy_info['token'] = $swy_info_data[1];
        // var_dump($swy_info_data);die();
        $swy_info['account_type'] = "APP_COM";
        $swy_info['nick_name'] = $nick_name;
        $swy_info['photo'] = $photo;
        $this->_aParams['swy_info'] = $swy_info;
        $is_ie = $this->_isIE();
        $this->_aParams['is_ie'] = $is_ie;
        //var_dump($swy_info);die();
        $this->_aParams["netfair_url"] = base_lib_Constant::NETFAIR_URL_NOT_HTTP;
        //判断是否有简历 带入会话中
        $resume_id      = base_lib_BaseUtils::getStr($path_data["resume_id"],"int",0);
        $job_id         = base_lib_BaseUtils::getStr($path_data["job_id"],"int",0);
        //打招呼设置
        $service_set_hello = new base_service_rong_rongsethello();
        $hello_list = $service_set_hello->getListByAccountId($account_id,'a.id as set_hello_id,b.id as template_id,a.is_effect,b.content,b.is_default','order by b.template_id asc',$company_id);
        $this->_aParams['hello_list'] = $hello_list;
        if(1==$hello_list[0]['is_effect']){
            $this->_aParams['hello_default'] = 1;
        }else{
            $this->_aParams['hello_default'] = 0;
        }
        $service_set_fix = new base_service_rong_rongsetfixtemplate();
        $this->_aParams['set_fix_list'] = $service_set_fix->getAccountTemplate($company_id,$account_id,'order by id desc','id,content');
        if($resume_id > 0){
            $init_info = $this->_getPreInfo($resume_id,$job_id);
        }
        $this->_aParams['resume_id'] = $resume_id;
        $this->_aParams['job_id'] = $job_id;
        $this->_aParams["has_init_info"] = false;
        $net_apply_id   = base_lib_BaseUtils::getStr($path_data["net_apply_id"],"int",0);
        $sid            = base_lib_BaseUtils::getStr($path_data["sid"],"int",0);
        $chat_status            = base_lib_BaseUtils::getStr($path_data["chat_status"],"string",null);
        $person_chat_status = '-1';
        if($chat_status){
            $person_chat_status = $chat_status;
        }
        if(!empty($init_info)){
            //当前用户的状态
            if(!$chat_status){
                $person_status =  $this->personStatus(array($init_info["person_id"]));
                if(isset($person_status[$init_info["person_id"]])){
                    $temp_person_chat_status = $person_status[$init_info["person_id"]];
                    if(6==$temp_person_chat_status){
                        $person_chat_status = '6';
                    }
                }
            }
            $init_info["is_show_video"]  = !empty($net_apply_id) ? 1 : 0;
            $init_info["net_apply_id"]   = $net_apply_id;
            $this->_aParams["has_init_info"] = true;
            $this->_aParams["init_info"]     = json_encode($init_info);
        }
        $this->_aParams["person_chat_status"] = $person_chat_status;
        //常用语
        $service_template = new base_service_hractivity_msgtemplate();
        $template_count   = $service_template->getTemplateAccountCount($account_id);
        if($template_count <= 0){
            $service_template->addDefaultTemplatesAccount($company_id,$account_id); //添加默认短语
        }
        $template_list    = $service_template->getTemplateByAccount($account_id,"id,create_time,content");
        //如果没有 则添加5个默认常用语
        $return_template = array();
        if(!empty($template_list)){
            foreach($template_list as $value){
                array_push($return_template,array("id" => (string)$value["id"],"content" => $value["content"]));
            }
        }
        $this->_aParams["template_list"] = json_encode($return_template);

        //公司地址
        $service_company_companyaddress = new base_service_company_companyaddress();
        $address_list = $service_company_companyaddress->getAddressListByCompanyId($company_id,1)->items;
        $this->_aParams["address_list"] = json_encode($address_list);

        $this->_aParams["auto_create_resume"] = false;

        //校园网络招聘会招聘会
        if(!empty($net_apply_id)){
            $service_net_apply  = new base_service_netfair_personapplynet();
            $service_channel    = new base_service_schoolnet_channel();
            $service_netfair_person = new base_service_netfair_person();
            $netfair_source = 1;
            $apply_info         = $service_net_apply->getApplyById($net_apply_id,"company_id,sid,create_time,status,source,person_id");
            //判断该求职者是否在线 如果不在线 则不直接打开视频面试 如果在线 则直接打开视频面试
            $service_wy_action  = new base_service_app_wangyiaction();
            $netfair_person =$service_netfair_person->getPersonIdById($apply_info["person_id"],$netfair_source);
            $is_online          = $service_wy_action->checkPersonIsOnline($netfair_person["base_person_id"]);
            $is_online          = true;
            if($is_online && $apply_info["source"] == 1){
                $this->_aParams["auto_create_resume"] = true;
            }
            //判断该求职者是否正在与其他人面试
            $is_on_interview = $service_channel->checkPersonIsOnInterview($netfair_person["base_person_id"], '');
            if($browser["browser"] == "IE" || $is_ie){
                $this->_aParams["error_msg"]    = "当前浏览器暂时不支持视频面试";
                $this->_aParams["sid"]          = $apply_info["sid"];
                $this->_aParams["back_url"]     = base_lib_Constant::NETFAIR_URL_NOT_HTTP."/videohall/VideoInterviewHall/?sid=".$apply_info["sid"];
                $this->_aParams["buttom_name"]  = "返回求职者大厅";
                return $this->render("./chat/videoerror.html",$this->_aParams);
            }
            if($is_on_interview){
                $this->_aParams["error_msg"]    = "该求职者正在面试，请稍后...";
                $this->_aParams["sid"]          = $apply_info["sid"];
                $this->_aParams["back_url"]     = base_lib_Constant::NETFAIR_URL_NOT_HTTP."/videohall/VideoInterviewHall/?sid=".$apply_info["sid"];
                $this->_aParams["buttom_name"]  = "返回求职者大厅";
                return $this->render("./chat/videoerror.html",$this->_aParams);
            }
            
            
            //该企业是否在与其他人面试
            $cache_channel_data = $service_channel->getChannelByAccount($account_id);
            if(!empty($cache_channel_data)){
                $_person_id     = $cache_channel_data["person_id"];
                $service_person = new base_service_person_person();
                $person_info    = $service_person->getPerson($_person_id, "person_id,user_name");
                $_end_time      = $cache_channel_data["start_time"] + 600;;
                $diff_time      = $_end_time - time();
                $diff_minutes   = ceil($diff_time/60);
                //$service_channel->setChannelEnd($cache_channel_data["channel_id"], $account_id); //如果超过10分钟结束上一个面试
                if($cache_channel_data["net_apply_id"] != $net_apply_id){
                    if($diff_time > 0){
                        $this->_aParams["error_msg"]    = "同时间仅能面试1人，检测到您与学生（{$person_info["user_name"]}）已在面试中，请先结束面试或等待{$diff_minutes}分钟后，发起新的面试";
                        $this->_aParams["sid"]          = $apply_info["sid"];
                        $this->_aParams["back_url"]     = base_lib_Constant::NETFAIR_URL_NOT_HTTP."/videohall/VideoInterviewHall/?sid=".$apply_info["sid"];
                        $this->_aParams["buttom_name"]  = "结束视频面试";
                        $this->_aParams["channel_id"]   = $cache_channel_data["channel_id"];
                        $this->_aParams["error_type"]   = 1;
                        return $this->render("./chat/videoerror.html",$this->_aParams);
                    }else{
                        $service_channel->setChannelEnd($cache_channel_data["channel_id"], $account_id); //如果超过10分钟结束上一个面试
                    }
                }
            }

            $this->_aParams["apply_source"] = $apply_info["source"];
           /* if($apply_info["source"] == 1){ //
                if(!in_array($apply_info["status"],[0,1,2,4])){
                    $this->_aParams["error_msg"]    = "您已经面试过该求职者了，不能重复面试";
                    $this->_aParams["sid"]          = $apply_info["sid"];
                    $this->_aParams["back_url"]     = base_lib_Constant::COMPANY_URL_NO_HTTP."/videohall/VideoInterviewHall/?sid=".$apply_info["sid"];
                    $this->_aParams["buttom_name"]  = "返回求职者大厅";
                    return $this->render("./chat/videoerror.html",$this->_aParams);
                }

            }else{
                //获取企业今日面试时间 如果超过3小时 则
                $service_channel        = new base_service_schoolnet_channel(); //获取企业今日面试时长
                $total_time             = $service_channel->getCompanyInterviewTime($company_id, 2);
                $allow_total_time       = 3*3600; //一天允许3小时
                if($total_time >= $allow_total_time){
                    $this->_aParams["error_msg"] = "今日面试时间已用完，可明日继续";
                    $this->_aParams["sid"]       = $apply_info["sid"];
                    $this->_aParams["back_url"]  = base_lib_Constant::COMPANY_URL_NO_HTTP."/videohall/JobWanters/?sid=".$apply_info["sid"];
                    $this->_aParams["buttom_name"]  = "返回面试大厅";
                    return $this->render("./chat/videoerror.html",$this->_aParams);
                }
            }*/
            //if(empty($apply_info) || $apply_info["company_id"] != $company_id){
            if(empty($apply_info) ){
                $net_apply_id = 0;
            }
        }
        $this->_aParams["sid"]          = !empty($apply_info) ? $apply_info["sid"] : 0;
        $this->_aParams["net_apply_id"] = $net_apply_id;

        $service_company = new base_service_company_company();
        $company_info    = $service_company->getCompany($this->_userid, 1, "company_name,area_id");
        $this->_aParams["company_name"] = $company_info["company_name"];
        //是否来自成都
        $this->_aParams['from_cd'] = preg_match('/^1501/i', $company_info['area_id']) ? true : false;
        //账号的招聘职位
        $job = new base_service_company_job_job();
        $job_list = $job->getJobList($company_id, null, 1, 'status,job_id,end_time,is_effect,check_state,station', 0, 0, null, null);
        if(!empty($job_list)){
            foreach($job_list as $key => $value){
                if($value["end_time"] < date("Y-m-d 00:00:00") || in_array($value["status"],[0,9]) || in_array($value["status"], [2,3,9]) || $value["is_effect"] == 0){
                    $job_list[$key]["job_status"] = 0;
                }else{
                    $job_list[$key]["job_status"] = 1;
                }
            }
        }else{
            $job_list = array();
        }
        $job_list = base_lib_BaseUtils::array_sort($job_list,'desc','job_status');
        $this->_aParams['job_list'] = $job_list;

        //var_dump($job_list);die();
        //上传图片
        $ser_upload = new base_service_upload_upload();
        $up_options = array ('file_name' => 'reportFile[]', 'fileVal' => 'Filedata', 'auto' => true, 'defaults_files' => '');
        $this->_aParams['up_img_html'] = $ser_upload->GetUploadHtmlDom($up_options, $this->_userid, 'up_style2', '', 'companyReport', '/company/company.xml');

        //招聘顾问
        $xml = SXML::load('../config/config.xml');
        $tel_head = "023-61627888";
        if (!is_null($xml)) {
            $tel_head = $xml->TechniquePhone;
        }
        $easy_tel_head = str_replace("-", "", $tel_head);
        $this->_aParams['tel_head'] = $tel_head;
        $this->_aParams['easy_tel_head'] = $easy_tel_head;
        //获取招聘顾问
        $companyStateService = new base_service_company_comstate();
        $companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
        //获取客服员
        $companyHeapService = new base_service_company_netheap();
        $companyHeap = $companyHeapService->GetNetHeapByID($companyState["net_heap_id"], "own_man");
        $hrManager = null;
        if (is_null($companyHeap) || !isset($companyHeap["own_man"])) {
           // return $userInfor;
        }else{
            $userService = new base_service_crm_user();
            $hrManager = $userService->GetUsers($companyHeap['own_man'], "user_name,head_photo_url,tel,mobile,qq");
        }
        $this->_aParams["hrManager"] = $hrManager;

        return $this->render("./chat/index.html",$this->_aParams);
    }

    public function pageGetChatCookie($inPath)
    {

        $path_data      = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $account_id           = base_lib_BaseUtils::getStr($path_data["account_id"],"int","0");
        if(!$account_id){
            header('Content-Type:application/json; charset=utf-8');
            echo $this->jsonMsg(false,'','');
            return;
        }
        $service_wy_action  = new base_service_app_wangyiaction();
        $is_online          = $service_wy_action->checkAccountIsOnline($account_id);
       // $chat_login_status     = base_lib_BaseUtils::getCookie('chatLoginStatus');
        if($is_online){
            header('Content-Type:application/json; charset=utf-8');
             echo $this->jsonMsg(true,'','');
            return;
        }else{
            header('Content-Type:application/json; charset=utf-8');
            echo $this->jsonMsg(false,'','');
            return;
        }
    }

    private function _getPreInfo($resume_id,$job_id){
        //初始化聊天人信息
        $init = [
            /* "sessType"          => "",//会话类型
             "SessionTypeZh"     => "",//会话语言*/
            "SessionId"         => "",//会话ID Sqcloud::PRE_APP_V2.$person_id
            "SessionNick"       => "",//会话昵称 为空
            "SessionImage"      => "",//会话头像  为空
            "Account_id"        => "",//会话ID
            "unread"            => 0,//未读消息数 默认是0
            "ResumeId"          => "",//简历编号
            "SessionJobId"      => "",//聊天职位编号
            "SessionJobStation" => "",//聊天职位//可以为空
            "MsgTimeStamp"      => time()*1000,
            "id"                => "",
        ];
        $service_resume = new base_service_person_resume_resume();
        $service_person = new base_service_person_person();
        $service_job    = new base_service_company_job_job();
        $resume_info    = $service_resume->getResume($resume_id,"person_id",false);
        if(empty($resume_info)){
            return null;
        }
        $person_id      = $resume_info["person_id"];
        $person_info    = $service_person->getPerson($person_id, "user_name,small_photo,open_mode",false);
        if(empty($person_info)){
            return null;
        }
        $accountid = base_lib_BaseUtils::getCookie('accountid');
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
        //导入求职者账号
        $service_swychat = new SWYChat("APP");
        $swy_info_data = $service_swychat->getAccountInfo($person_id);
        if(empty($swy_info_data[0])){
            return null;
        }
        //求职者信息
        $person_account = $swy_info_data[0];
        $default_photo                  = base_lib_Constant::STYLE_URL."/img/common/user120_150.jpg";
        $init["id"]             = 'p2p-'.$person_account;
        $init["person_id"]      = $person_id;
        $init["SessionId"]      = $person_account;
        $init["Account_id"]     = $person_account;
        $init["ResumeId"]       = $resume_id;
        $init["SessionNick"]    = $person_info["user_name"];
        $init["SessionImage"]   = empty($person_info['small_photo']) ? $default_photo : base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . "{$person_info['small_photo']}";
        $job_info = $service_job ->getJob($job_id, "station,company_id");
        if(!empty($job_info)){
            if(!in_array($job_info["company_id"], $company_resources->all_accounts)){
                $this->showErrorPage("参数错误，您没有该职位");
            }
            $init["SessionJobId"]           = $job_id;
            $init["SessionJobStation"]      = $job_info["station"];
        }

        return $init;
    }

    /**
     *@desc 验证求职者身份
     */
    public function pageSendMobileCode($inPath){
        $path_data      = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $validator      = new base_lib_Validator();
        $mobile_phone   = $validator->getMobile($path_data['phone'], "手机不正确");
        $seed           = base_lib_BaseUtils::getStr($path_data["seed"],"string","");
        $vcode          = base_lib_BaseUtils::getStr($path_data["vild_code"],"string","");

        if($validator->has_err){
            echo json_encode(array("error" => $validator->err[0]));exit;
        }
        //判断图片验证
        $captcha = new SCaptchalu();
        if ($captcha->verify($seed, $vcode) === false) {
            echo json_encode(array("error" =>"验证码错误"));exit;
        }

        $service_company = new base_service_company_company();
        /*--------------boss端企业违规封号处理- 2018-12-15 --------------*/
        $boos_fordden = $service_company->isBossForbid(null, null, null, $mobile_phone, 'bind');
        if ($boos_fordden['is_foribid'] === true) {
            echo json_encode(array("error" => $boos_fordden['msg']));exit;
        }

        list($send_result,$error) = $this->__sendVcode($mobile_phone);
        if($send_result == false){

            echo json_encode(array("error" => $error));exit;
        }
        echo json_encode(array("successs" => "验证码发送成功"));exit;

    }

    /**
     *@desc 绑定求职者
     */
    public function pageBindPerson($inPath){
        $path_data      = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $validator      = new base_lib_Validator();
        $mobile_phone   = $validator->getMobile($path_data['phone'], "手机不正确");
        $valid_code     = base_lib_BaseUtils::getStr($path_data["msg_code"],"string","");
        $account_id     = base_lib_BaseUtils::getStr($path_data["account_id"],"int",0);
        $account_id     = !empty($account_id) ? $account_id : base_lib_BaseUtils::getCookie('accountid');
        if($validator->has_err){
            echo json_encode(array("error" => $validator->err[0],"code" => 0));exit;
        }

        //验证短信验证码
        $service_validate_code = new base_service_hractivity_validationcode();
        $valid = $service_validate_code->getLastValidation($mobile_phone,"id,validation_code,deadline,send_reason");
        if(empty($valid)){
            echo json_encode(array("error" =>"未发送验证码，或验证码已失效，请重新获取验证码","code" => 0));exit;
        }
        if(strtotime($valid['deadline']) < time()){
            echo json_encode(array("error" =>"对不起，您的验证码已过期","code" => 0));exit;

        }
        if($valid['validation_code'] != $valid_code){
            echo json_encode(array("error" =>"对不起，您的验证码错误","code" => 0));exit;
        }
        $service_validate_code->updateValidStatus($valid["id"],1); //验证成功

        $service_company = new base_service_company_company();
        /*--------------boss端企业违规封号处理- 2018-12-15 --------------*/
        $boos_fordden = $service_company->isBossForbid(null, null, null, $mobile_phone, 'bind');
        if ($boos_fordden['is_foribid'] === true) {
            echo json_encode(array("error" => $boos_fordden['msg'],"code" => 0));exit;
        }


        //获取求职者信息
        $service_person = new base_service_person_person();
        $service_related = base_service_hractivity_related::getInstance();

        //判断该账号是否已经被关联
        $company_id     = $this->_userid;
        $related_info    = $service_related->getRelatedByAccount($account_id,"person_id,company_id,account_id");
        if(!empty($related_info)){
            echo json_encode(array("error" =>"绑定失败，您已经绑定过了","code" => 0));exit;
        }

        $person = $service_person->getPersonByPhone($mobile_phone,null,"person_id,user_name,photo,password",null,1);
        if(empty($person)){
            $person_id = $this->_registerByMobile($mobile_phone);
        }else{
            $person_id = $person["person_id"];
            $related_info    = $service_related->getRelatedCompany($person_id,"person_id,company_id,account_id");
            if(!empty($related_info)){
                $service_company = new base_service_company_company();
                $company_info    = $service_company->getCompany($related_info["company_id"], 1,"company_name,company_shortname");
                $_company_name   = $company_info["company_name"];
                echo json_encode(array("error" =>"该手机号已和【{$_company_name}】绑定，确认后将与其解除绑定状态，并和目前登录账号进行绑定！","code" => 2,"unbind_person_id" => $related_info["person_id"],"unbind_account_id"=>$related_info["account_id"]));exit;
            }
        }
        if(empty($person_id)){
            echo json_encode(array("error" =>"绑定失败","code" => 0));exit;
        }

        $related_result = $service_related->addRelated($person_id, $account_id, $company_id);
        if($related_result === false){
            echo json_encode(array("error" =>"绑定失败，请重试","code" => 0));exit;
        }
        echo json_encode(array("success" =>"绑定成功","code" => 1));exit;
    }

    //解绑并且绑定新手机号
    public function pageRebind($inPath){
        $path_data              = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $validator              = new base_lib_Validator();
        $mobile_phone           = $validator->getMobile($path_data['phone'], "手机不正确");
        $account_id             = base_lib_BaseUtils::getStr($path_data["account_id"],"int",0);
        $account_id             = !empty($account_id) ? $account_id : base_lib_BaseUtils::getCookie('accountid');
        $unbind_person_id       = base_lib_BaseUtils::getStr($path_data["unbind_person_id"],"int",0);
        $unbind_account_id      = base_lib_BaseUtils::getStr($path_data["unbind_account_id"],"int",0);

        if(empty($unbind_account_id) || empty($unbind_person_id)){
            echo json_encode(array("error" =>"解绑失败，参数错误"));exit;
        }
        if($validator->has_err){
            echo json_encode(array("error" => $validator->err[0],"code" => 0));exit;
        }
        //先解绑
        $service_person     = new base_service_person_person();
        $service_related    = base_service_hractivity_related::getInstance();

        $person_info        = $service_person->getPerson($unbind_person_id,"mobile_phone");
        if(empty($person_info) || $person_info["mobile_phone"] != $mobile_phone){
            echo json_encode(array("error" =>"解绑失败，待解绑人错误"));exit;
        }
        $related_info = $service_related->getRelatedByAccount($unbind_account_id, "person_id,account_id,company_id");
        if(empty($related_info) || $related_info["person_id"] != $unbind_person_id){
            echo json_encode(array("error" =>"解绑失败，待解绑账号错误"));exit;
        }
        $del_result = $service_related->delRelated($unbind_person_id, $related_info["company_id"], $unbind_account_id);
        if($del_result === false){
            echo json_encode(array("error" =>"解绑失败，待解绑账号错误"));exit;
        }
        //重新绑定
        $related_result = $service_related->addRelated($unbind_person_id, $account_id, $this->_userid);
        if($related_result === false){
            echo json_encode(array("error" =>"重新绑定失败"));exit;
        }
        echo json_encode(array("success" =>"重新绑定成功"));exit;
    }


    /**
     *@desc 注册求职者
     */
    private function _registerByMobile($mobile_phone){
        $person['user_id']                      = '#'.$mobile_phone;
        $person['mobile_phone']                 = $mobile_phone;
        $person['mobile_phone_is_validation']   = '1';

        $person['password']     = base_lib_BaseUtils::md5_16("hb".$mobile_phone);
        $person['reg_source']   = 'app_hr_pc';
        $person['person_class'] = 1;
        $person["user_name"]    = "HR".time();
        $resume_id              = 0;

        //渠道
        $service_actionsource   = new base_service_common_actionsource();
        $service_person         = new base_service_person_person();
        $action_source          = $service_actionsource->account_bind;
        $person_id              = $service_person->addPerson($person, $operate,$action_source, $resume_id);
        if(empty($person_id)){
            return false;
        }
        return $person_id;
    }

    /**
     *@desc 发送登录验证码
     * @params
     */
    private function __sendVcode($mobile_phone){
        $service_validate_code = new base_service_hractivity_validationcode();
        $error = "";
        $result = $service_validate_code->addValidationCodeByPc($mobile_phone, $error);
        return [$result,$error];
    }

    /**
     *@desc 图片验证码
     */
    public function pageVerify($inPath){
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $seed = $path_data["seed"];
        $captcha = new SCaptchalu();
        $captcha->conf->type = 0;
        $captcha->conf->mode = 0;//图片模式 文字变色为1
        $captcha->conf->length =4;
        $imageResource = $captcha->getImageResource($seed);
        header("Content-type: image/png");
        if (false !== $imageResource)
            imagepng($imageResource);
    }

    /**
     *@desc 搜索职位
     */
    public function pageSearchJob($inPath){
        $pathdata       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $company_id     = $this->_userid;
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        $station        = base_lib_BaseUtils::getStr($pathdata["station"],"string",null);
        //获取在招职位
        $accountid = base_lib_BaseUtils::getCookie('accountid');
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
        $chat_account_ids   = $company_resources->all_accounts;
        $service_job        = new base_service_company_job_job;
        $job_status         = new base_service_common_jobstatus();

        $job_list           = $service_job->getJobList($chat_account_ids, $station, $job_status->use, 'job_id,station,account_id');


        if(!$job_list){
            $job_list = [];
        }else{
            $my_job_list        = [];
            $other_job_list     = [];
            if(!empty($job_list)){
                foreach($job_list as $value){
                    if($value["account_id"] == $account_id){
                        $my_job_list[] = ["job_id"=>$value["job_id"],"station" => $value["station"]];
                    }else{
                        $other_job_list[] = ["job_id"=>$value["job_id"],"station" => $value["station"]];
                    }
                }
                $job_list = array_merge($my_job_list,$other_job_list);
            }
        }
        echo json_encode(array("job_list"=>$job_list));
    }


    public function pageSaveTemplate($inPath){
        $pathdata       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $content        = base_lib_BaseUtils::getStr($pathdata["content"],"string","");
        $id             = base_lib_BaseUtils::getStr($pathdata["id"],"int",0);

        $company_id     = $this->_userid;
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        /* $service_chat   = company_service_chat::getInstance($account_id,$company_id);
         $bind_person_id = $service_chat->getBindPersonId();
         if(empty($bind_person_id)){
             echo json_encode(array("error" => "没有绑定企业APP账号，不能添加"));exit;
         }*/

        if(empty($content)){
            echo json_encode(array("error" => "内容不能为空"));exit;
        }

        if(mb_strlen($content) > 60){
            echo json_encode(array("error" => "内容不能超过60字"));exit;
        }
        //判断个数
        $service_template = new base_service_hractivity_msgtemplate();
        $count            = $service_template->getTemplateAccountCount($account_id);
        if(empty($id)){
            if($count >= 10){ //最多10条
                echo json_encode(array("error" => "打招呼语最多只能有10条"));exit;
            }
        }
        if(!empty($id)){
            $result  = $service_template->updateTemplateAccount($id,$account_id, $content);
        }else{
            $result = $service_template->addTemplateAccount($content,$company_id,$account_id);
            $id     = $result;
        }
        if($result === false){
            echo json_encode(array("error" => "保存打招呼语失败"));exit;
        }
        echo json_encode(array("success" => "保存成功","id" => $id));exit;
    }

    /**
     *@desc 删除常用语
     */
    public function pageDeleteTempList($inPath){
        $pathdata       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $id             = base_lib_BaseUtils::getStr($pathdata["id"],"int",0);

        $company_id     = $this->_userid;
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        /* $service_chat   = company_service_chat::getInstance($account_id,$company_id);
         $bind_person_id = $service_chat->getBindPersonId();
         if(empty($bind_person_id)){
             echo json_encode(array("error" => "没有绑定企业APP账号，不能操作"));exit;
         }*/
        if(empty($id)){
            echo json_encode(array("error" => "删除失败，缺少打招呼语唯一编号"));exit;
        }
        $service_template = new base_service_hractivity_msgtemplate();
        $count            = $service_template->getTemplateAccountCount($account_id);
        if($count <=1){
            echo json_encode(array("error" => "删除失败，常用语至少保留一条"));exit;
        }
        $result = $service_template->deleteTemplateAccount($account_id, $id);
        if($result == false){
            echo json_encode(array("error" => "删除失败"));exit;
        }
        echo json_encode(array("success" => "删除成功"));exit;
    }

    public function pageGetResumeAndJobData($inPath){
        $pathdata       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $account_ids    = base_lib_BaseUtils::getStr($pathdata["account_ids"],"array","");
        $job_ids        = base_lib_BaseUtils::getStr($pathdata["job_ids"],"array","");
        $resume_id        = base_lib_BaseUtils::getStr($pathdata["resume_id"],"int",0);
        $select_person_id        = base_lib_BaseUtils::getStr($pathdata["select_person_id"],"string","");
        if($select_person_id){
            $select_person_id = preg_replace('/[a-z|\_]+/',"",$select_person_id);
        }
        $company_id    = $this->_userid;
        //获取简历信息
        $service_resume = new base_service_person_resume_resume();
        $service_person = new base_service_person_person();
        $service_job    = new base_service_company_job_job();
        $degree_common = new base_service_common_degree();

        if(empty($account_ids) && empty($job_ids)){
            echo json_encode(array("status" => false));exit;
        }

        $job_list       = array();
        $job_status     = array();
        if(!empty($job_ids)){
            $job_ids    = array_map(function($v){
                return intval($v);
            }, $job_ids);
            $job_list   = $service_job->getJobs($job_ids, "status,job_id,end_time,is_effect,check_state,station");
            if(!empty($job_list)){
                foreach($job_list as $key => $value){
//                    array ('end_time' => array ('$lt' => $stamp)),
//					array ('status' => intval($job_status)),
//					array ('check_state' => array ('$in' => array (2, 3, 9)))
                    if($value["end_time"] < date("Y-m-d 00:00:00") || in_array($value["status"],[0,9]) || in_array($value["status"], [2,3,9]) || $value["is_effect"] == 0){
                        $job_list[$key]["job_status"] = 0;
                        $job_status[$value["job_id"]] = 0;
                    }else{
                        $job_list[$key]["job_status"] = 1;
                        $job_status[$value["job_id"]] = 1;
                    }

                }
            }
        }
        $resume_data = array();
        if(!empty($account_ids)){
            $person_ids = array_map(function($v){
                $v = preg_replace('/[a-z|\_]+/',"",$v);
                return intval($v);
            }, $account_ids);
            //意向薪资
            $service_salary = new base_service_common_salary();
            $salary_arr = $service_salary->getAll();
            $resume_list   = $service_resume->getDefaultResumes($person_ids,"person_id,degree_id,resume_id,salary")->items;
            $resume_list   = base_lib_BaseUtils::array_key_assoc($resume_list, "person_id");
            //有简历进入判断
            if($select_person_id){
                if(isset($resume_list[$select_person_id]) && $resume_id!=$resume_list[$select_person_id]['resume_id']){
                    $person_resume = $service_resume->getResume($resume_id,'person_id,degree_id,resume_id,salary');
                    if($person_resume){
                        $resume_list[$select_person_id] = $person_resume;
                    }
                }
            }
            $resume_list_resume_ids   = base_lib_BaseUtils::getProperty($resume_list, "resume_id");
            if(!empty($resume_list_resume_ids)){
                //工作经历
                $service_resume_work = new base_service_person_resume_work();
                $service_resume_work_info = $service_resume_work->getResumeWorks($resume_list_resume_ids,'resume_id,start_time,end_time,company_name,station')->items;
                foreach ($service_resume_work_info as $key=>$val){
                    if(!isset( $service_resume_work_info[$val['resume_id']])){
                        $service_resume_work_info[$val['resume_id']] = $val;
                    }
                }
                //$service_resume_work_info = base_lib_BaseUtils::array_key_assoc($service_resume_work_info->items,'resume_id');
                //获取意向职位
                $resume_jobsort_exp     = new base_service_person_resume_jobsortexp();
                $resume_jobsort_list = $resume_jobsort_exp->getJobsortByResumeIds($resume_list_resume_ids);
                $resume_jobsort_list = base_lib_BaseUtils::array_key_assoc($resume_jobsort_list, 'resume_id');
                $service_common_jobsort = new base_service_common_jobsort();
                foreach ($resume_jobsort_list as $key=>$val){
                    if($val['jobsort']){
                        $jobsort_names = [];
                        $jobsorts = explode(',',$val['jobsort']);
                        foreach ($jobsorts as $v) {
                            array_push($jobsort_names, $service_common_jobsort->getJobsort($v, true));
                        }
                        $resume_jobsort_list[$key]['jobsort_name'] = implode('，', $jobsort_names);
                    }else{
                        $resume_jobsort_list[$key]['jobsort_name'] = '';
                    }
                }
                //简历收藏
                $service_fav = new base_service_company_resume_fav();
                $fav_list = $service_fav->getCompanyResume($company_id,$resume_list_resume_ids,1,'fav_id,resume_id');
                $fav_list = base_lib_BaseUtils::array_key_assoc($fav_list,'resume_id');
                //备注
                $service_remark = new base_service_company_resume_resumeremark();
                $resume_remarks = $service_remark->getResumeRemarks($company_id, $resume_list_resume_ids, 2,'remark_id,resume_id,remark,update_time');
                $resume_remarks_list = [];
                foreach ($resume_remarks as $key=>$val){
                    $resume_remarks_list[$val['resume_id']][] = $val;
                }
            }
            $netfair_base_personids = array();
            if(!empty($person_ids)){
                $person_list = $service_person->getPersons($person_ids,"person_id,user_name,birthday2,start_work,sex,small_photo,photo")->items;
                $person_list = base_lib_BaseUtils::array_key_assoc($person_list, "person_id");
                $person_chat_status = $this->personStatus($person_ids);

                $net_apply_list     = [];

                //判断是否有正在举行的校招活动
                $service_shuangxuan_net = new base_service_netfair_net();
                $shuangxuan_net_list    = $service_shuangxuan_net->getNowShuangxuan("id");
                $sids                   = base_lib_BaseUtils::getProperty($shuangxuan_net_list, "id");

                //判断求职者是否申请过该企业视频面试
                if(!empty($sids)){
                    $service_net_apply   = new base_service_netfair_personapplynet();
                    $service_netfair_person = new base_service_netfair_person();
                    $service_netfair_company = new base_service_netfair_company();
                    $netfair_company_id = $service_netfair_company->getCompanyID($this->_userid,'1','id');
                    $netfair_personids = $service_netfair_person->getPersonIds($person_ids,'1','id,base_person_id');
                    $netfair_base_personids = base_lib_BaseUtils::array_key_assoc($netfair_personids,'base_person_id');
                    $netfair_personids = base_lib_BaseUtils::getProperty($netfair_personids,'id');
                    if(!empty($netfair_personids)&&!empty($netfair_company_id)){
                        $net_apply_list      = $service_net_apply->getApplyDataByPersonIds($sids, $netfair_personids, "id,person_id", $netfair_company_id);
                        $net_apply_list      = base_lib_BaseUtils::array_key_assoc($net_apply_list, "person_id");
                    }
                }
            }
            //是否投递
            $apply_service      = new base_service_person_apply();
            //获取在招职位
            $accountid = base_lib_BaseUtils::getCookie('accountid');
            $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
            $chat_company_ids   = $company_resources->all_accounts;

            $apply_list         = $apply_service->personIsApplyCompanys($person_ids, $chat_company_ids,"person_id,job_id")->items;
            $apply_person_ids   = base_lib_BaseUtils::getProperty($apply_list, "person_id");
            $service_swychat = new SWYChat("APP");
            $pre_string = $service_swychat->getPreString();
            $default_photo  = base_lib_Constant::STYLE_URL."/img/user_img.png";
            foreach($person_ids as $person_id){
                $_data = array();
                $person_info            = $person_list[$person_id];
                $resume_info            = $resume_list[$person_id];
                $_data["resume_id"]     = $resume_info["resume_id"];
                $_data["person_id"]     = $person_info["person_id"];
                $_data["user_name"]     = $person_info["user_name"];
                $resume_list_salary     = $resume_info['salary'];
                //状态
                if(isset($person_chat_status[$person_id])){
                    $_data["chat_status"]     = $person_chat_status[$person_id];
                }else{
                    $_data["chat_status"]     = 1;
                }
                //薪金
                if($resume_list_salary){
                    $_data["salary"]     = $salary_arr[$resume_list_salary]/1000;
                    $_data["salary"]     .='K及以上';
                }else{
                    $_data["salary"]      ='';
                }
                //意向职位
                if(isset($resume_jobsort_list[$resume_info["resume_id"]])){
                    $_data["jobsort"] = $resume_jobsort_list[$resume_info["resume_id"]]['jobsort_name'];
                }else{
                    $_data["jobsort"] = '';
                }
                //最近工作信息
                if (isset($service_resume_work_info[$resume_info["resume_id"]])) {
                    $resume_work_item = $service_resume_work_info[$resume_info["resume_id"]];
                    $year_desc = base_lib_TimeUtil::date_diff_year2($resume_work_item['start_time'], $resume_work_item['end_time']);
                    if ($resume_work_item['end_time']) {
                        $_data["work_desc_date"] = '曾任：';
                    } else {
                        $_data["work_desc_date"] = '现任：';
                    }
                    $_data["work_company_name"] = $resume_work_item['company_name'];
                    $_data["work_desc"] = '(' . $year_desc . ')';
                    $_data["work_station"] = $resume_work_item['station'];
                } else {
                    $_data["work_desc"] = '';
                    $_data["work_station"] = '';
                }
                //判断简历是否收藏
                if(isset($fav_list[$resume_info["resume_id"]])){
                    $_data["is_fav"]  = 1;
                }else{
                    $_data["is_fav"]  = 0;
                }
                //备注列表
                if(isset($resume_remarks_list[$resume_info["resume_id"]])){
                    $_data["resume_remarks"]  = $resume_remarks_list[$resume_info["resume_id"]];
                }else{
                    $_data["resume_remarks"]  = [];
                }
                $_data["account_id"]    = $pre_string.$person_id;
                $_data["is_apply"]      = in_array($person_id, $apply_person_ids) ? true : false;
                $_data["work_year"]     = $this->_calWorkYear($person_info["start_work"]);//工作经验
                $_data["age"]           = !empty($person_info["birthday2"]) ? base_lib_TimeUtil::ceil_diff_year($person_info['birthday2']) . '岁' : "年龄未知";//工作经验
                $_data["degree_name"]   = "学历未知";
                if ($person_info["sex"] == 1) {
                    $_data["sex"] = "男";
                    $_data["sex_img"] = base_lib_Constant::STYLE_URL . '/img/company/video/schoolnetManIcon.png';
                    $default_photo = base_lib_Constant::STYLE_URL . '/img/company/video/defaultMan.png';
                } else {
                    $_data["sex"] = "女";
                    $_data["sex_img"] = base_lib_Constant::STYLE_URL . '/img/company/video/schoolnetWomanIcon.png';
                    $default_photo = base_lib_Constant::STYLE_URL . '/img/company/video/defaultWoman.png';
                }

                $_data["is_show_video"] = 0;
                $_data["net_apply_id"]  = 0;
                $net_apply_data         = !empty($net_apply_list[$netfair_base_personids[$person_id]['id']]) ? $net_apply_list[$netfair_base_personids[$person_id]['id']] : [];
                if(!empty($net_apply_data)){
                    $_data["is_show_video"] = 1;
                    $_data["net_apply_id"]  = $net_apply_data["id"];
                }
                if(!empty($resume_info["degree_id"])){
                    $degree_info            = $degree_common->getDegree($resume_info["degree_id"]);
                    $_data["degree_name"]   = $degree_info;
                }

                //兼容判断
                if(base_lib_BaseUtils::nullOrEmpty($person_info['photo'])){
                    $_data['photo'] = $default_photo;
                }else{
                    $_data['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person_info['photo'];
                }
                $resume_data[] = $_data;
            }
        }
        $resume_data = base_lib_BaseUtils::array_key_assoc($resume_data, "account_id");
        $job_list_sort    = base_lib_BaseUtils::array_sort2($job_list, "job_status");
        $job_list    = base_lib_BaseUtils::array_key_assoc($job_list, "job_id");

        echo json_encode(
            array("status" => true, "resume_list" => $resume_data, "job_status" => $job_status,
                "job_list" => $job_list, "job_list_sort" => $job_list_sort,
            ));
        exit;
    }

    /**
     * @Desc 格式化工作经验
     * @param $start_work YYYY-mm-dd
     * @return text 格式化文字
     */
    private function _calWorkYear($start_work) {
        $basic_start_work_year = base_lib_TimeUtil::date_diff_month($start_work);
        $workY = floor($basic_start_work_year / 12);
        $workM = intval($basic_start_work_year % 12);
        if ($workY <= 0 && $workM <= 6 && $workM > -6) {
            $basic_start_work_year = '应届毕业生';
        } else if ($workY == 0 && $workM > 6) {
            $basic_start_work_year = $workM . '个月工作经验';
        } else if ($basic_start_work_year <= -6) {
            $basic_start_work_year = '目前在读';
        } else {
            $basic_start_work_year = $workY . '年工作经验';
        }
        return $basic_start_work_year;
    }

    /**
     *@desc 发送职位聊一聊的时候 记录聊一聊发送记录    停止使用，网易云信抄送时处理
     */
    public function pageGetSendMsgJob($inPath){
        exit;
        $pathdata       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_id = base_lib_BaseUtils::getStr($pathdata["resume_id"],"int",0);
        if(empty($resume_id)){
            echo json_encode(["status" => "fail"]);exit;
        }
        $service_actionsource   = new base_service_common_actionsource();
        $source                 = $service_actionsource->website;

        $service_resume = new base_service_person_resume_resume();
        $resume_info    = $service_resume->getResume($resume_id, "person_id");
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        $qcloud_identifier  = base_lib_BaseUtils::getStr($pathdata["sess_id"],"string","");

        if(!empty($resume_info) && !empty($account_id)){
            $service_send_msg = new base_service_app_companyqcloudmsg();
            $items = array();
            $items["person_id"]         = $resume_info["person_id"];
            $items["resume_id"]         = $resume_id;
            $items["company_id"]        = $this->_userid;
            $items["account_id"]        = $account_id;
            $items["source"]            = (int)$source;
            if(!empty($qcloud_identifier)){
                $items["qcloud_identifier"]            = $qcloud_identifier;
            }
            $service_send_msg->addData($items);
        }
        echo json_encode(["status" => "success"]);exit;
    }


    /**
     *@desc 自动绑定
     */
    public function pageAutoBind($inPath){
        $pathdata       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $company_id     = $this->_userid;
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        $service_chat   = company_service_chat::getInstance($account_id,$company_id);
        $bind_person_id = $service_chat->getBindPersonId();
        if(!empty($bind_person_id)){
            echo json_encode(["status" => true]);exit;
        }

        $service_account = new base_service_company_account();
        $account_info    = $service_account->getAccount($account_id,"account_id,mobile_phone");
        if(!empty($account_info["mobile_phone"])){
            //判断是否该手机号已绑定
            $service_person = new base_service_person_person();
            $person_info    = $service_person->getPersonByPhone($account_info["mobile_phone"], null, "person_id,mobile_phone",null,1);
            if(empty($person_info)){
                //自动注册
                $reg_person_id = $service_chat->registerByMobile($account_info["mobile_phone"], $need_bind = true);
                if($reg_person_id !== false){
                    $bind_person_id = $reg_person_id;
                }

            }else{
                //判断有没有绑定 如果没有绑定 自动绑定
                $service_related    = base_service_hractivity_related::getInstance();
                $relate_info        = $service_related->getRelatedCompany($person_info["person_id"], "company_id,account_id");
                if(empty($relate_info)){
                    //绑定
                    $related_result = $service_related->addRelated($person_info["person_id"], $account_id, $company_id);
                    if($related_result !== false){
                        $bind_person_id = $person_info["person_id"];
                    }
                }
            }
        }
        if(!empty($bind_person_id)){
            echo json_encode(["status" => true]);exit;
        }else{
            echo json_encode(["status" => false]);exit;
        }

    }

    /**
     * 验证14天内企业与求职者聊天历史
     * @param $inPath
     */
    public function pageCheckChatHistory($inPath){
        $pathdata       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $company_id     = $this->_userid;
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        $service_chat   = company_service_chat::getInstance($account_id,$company_id);
        $bind_person_id = $service_chat->getBindPersonId();
        $resume_id      = base_lib_BaseUtils::getStr($pathdata['resume_id'], 'int', '');
        //查询当前hr对应公司下的其他子账号
        $service_hractivity_related = new base_service_hractivity_related();
        $account_ids = $service_hractivity_related->getRelatedByCompany($company_id, 'person_id');
        /*$service_company_account = new base_service_company_account();
        $account_ids = $service_company_account->getAccountCompany($company_id,'account_id');*/
        if (empty($account_ids)) {
            return $this->jsonMsg(true, '检验成功');
        }
        $account_ids = base_lib_BaseUtils::getProperty($account_ids, 'person_id');
        $account_ids = array_diff($account_ids, [$bind_person_id]);
        //查询简历详情
        $service_resume = new base_service_person_resume_resume();
        $resume = $service_resume->getResume($resume_id, 'person_id');
        $person_id = $resume['person_id'];
        //获取企业最近一条与该求职者的聊天记录
        $service_rong_chat_record = new base_service_rong_rongchatrecord();
        $chat_record = $service_rong_chat_record->getRecord($person_id, $account_ids, 'job_id,from_account, to_account, send_origin, p_name, hr_name', time() - 14*24*3600);
        if (empty($chat_record)) {
            return $this->jsonMsg(true, '检验成功');
        }
        $station = array(
            'station' => ''
        );
        $hr_info = array(
            'user_name' => ''
        );
        if (!empty($chat_record)) {
            $service_job = new base_service_company_job_job();
            $station = $service_job->selectOne(['job_id' => $chat_record['job_id']], 'station');
            //通过当前的account_id获取绑定的person
            $hr_person_id = $chat_record['send_origin'] == 1 ? $chat_record['from_account'] : $chat_record['to_account'];
            $hr_person_related_info = $service_hractivity_related->getRelatedCompany($hr_person_id, 'account_id');
            //通过account_id查找hr信息
            $service_hr = new base_service_company_account();
            $hr_info = $service_hr->getAccount($hr_person_related_info['account_id'], 'user_name');
        }
        return $this->jsonMsg(false, $chat_record['p_name'] . '在14天内被您的企业账号【' . $hr_info['user_name'] . '】沟通过职位【' . $station['station'] . '】');
    }

    /**
     * 验证求职者聊天历史
     * @param $inPath
     * @return mixed|string
     */
    public function pageCheckChatHistoryV2($inPath){
        $pathdata       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $company_id     = $this->_userid;
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        //$resume_id      = base_lib_BaseUtils::getStr($pathdata['resume_id'], 'int', '');
        $session_id      = base_lib_BaseUtils::getStr($pathdata['session_id'], 'string', '');
        //$service_chat   = company_service_chat::getInstance($account_id,$company_id);
        //$bind_person_id = $service_chat->getBindPersonId();
        //获取该企业下的其他子账号
//        $service_hractivity_related = new base_service_hractivity_related();
//        $account_ids = $service_hractivity_related->getRelatedByCompany($company_id, 'person_id');
//        if (empty($account_ids)) {
//            return $this->jsonMsg(true, '检验成功');
//        }
        //查询简历详情
        /*  $service_resume = new base_service_person_resume_resume();
          $resume = $service_resume->getResume($resume_id, 'person_id');
          $person_id = $resume['person_id'];*/
        $person_id = preg_replace('/[a-z|\_]+/',"",$session_id);

        //获取企业最近一条与该求职者的聊天记录
        $service_companyqcloudmsg = new base_service_app_companyqcloudmsg();
        $chat_record = $service_companyqcloudmsg->getRecord($person_id, $company_id, $account_id, 'id,account_id', time() -14*24*3600);
        if (empty($chat_record)) {
            return $this->jsonMsg(true, '检验成功');
        }
        //获取求职者信息
        $service_person = new base_service_person_person();
        $person_info = $service_person->getPerson($person_id, 'user_name');
        $hr_info = array(
            'user_name' => ''
        );
        if (!empty($chat_record)) {
            //通过account_id查找hr信息
            $service_hr = new base_service_company_account();
            $hr_info = $service_hr->getAccount($chat_record['account_id'], 'user_name');
            if(empty($hr_info['user_name'])) {
                return $this->jsonMsg(true, '检验成功');
            }
            //$hr_info = $service_person->getPerson($chat_record['account_id'], 'user_name');
        }
        if (empty($person_info['user_name'])) {
            return $this->jsonMsg(true, '检验成功');
        }
        return $this->jsonMsg(false, $person_info['user_name'] . '在14天内被您的企业账号【' . $hr_info['user_name'] . '】沟通过');
    }

    /**
     * 获取聊天记录
     * @param $inPath
     * @return mixed|string
     */
    public function pageGetChatHistory($inPath){
        $pathdata       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        $company_id = $this->_userid;
        // $service_chat   = company_service_chat::getInstance($account_id,$company_id);
        // $bind_person_id = $service_chat->getBindPersonId();
        //$resume_id      = base_lib_BaseUtils::getStr($pathdata['resume_id'], 'int', '');
        $date           = base_lib_BaseUtils::getStr($pathdata['msg_date'], 'string', '');
        $session_id      = base_lib_BaseUtils::getStr($pathdata['session_id'], 'string', '');
        $keyword        = base_lib_BaseUtils::getStr($pathdata['keyword'], 'string', '');
        $page           = base_lib_BaseUtils::getStr($pathdata['page'], 'int', 1);

        //查询简历详情
        /* $service_resume = new base_service_person_resume_resume();
         $resume = $service_resume->getResume($resume_id, 'person_id');
         $person_id = $resume['person_id'];*/
        $person_id = preg_replace('/[a-z|\_]+/',"",$session_id);
        //获取企业最近一条与该求职者的聊天记录
        $service_rong_chat_record = new base_service_rong_rongchatrecord();
        /* $params = array(
             'p_hr' => $person_id . '_' . $bind_person_id,
             'keyword' => $keyword,
             'page' => $page
         );*/
        $params = array(
            'p_hr' => $person_id . '_' . $account_id,
            'keyword' => $keyword,
            'page' => $page
        );
        $start_timestamp = time() - 30*24*3600;
        $end_timestamp = time();
        $start_date = date('Y年m月d日', $start_timestamp);
        $end_date = date('Y年m月d日', $end_timestamp);
        //获取最早有消息的日期
        $start_record = $service_rong_chat_record->getStartRecord($params, 'msg_timestamp', 'order by msg_timestamp ASC');
        if (!empty($start_record)) {
            $start_timestamp = $start_record['msg_timestamp'];
            $start_date = date('Y年m月d日', $start_timestamp);
        }
        //获取最晚有消息的日期
        $end_record = $service_rong_chat_record->getStartRecord($params, 'msg_timestamp', 'order by msg_timestamp DESC');
        if (!empty($end_record)) {
            $end_timestamp = $end_record['msg_timestamp'];
            $end_date = date('Y年m月d日', $end_timestamp);
        }
        $params['first_date_timestamp'] = $start_timestamp;
        //如果传了筛选的日期的，就有筛选的日期作为条件，否则，就用最后的一条消息的时间作为条件来筛选数据
        if (!empty($date)) {
            $params['msg_date'] = str_replace('-', '', $date);
            $msg_date = date('Y年m月d日', strtotime($date));
        }/* else {
            $params['msg_date'] = str_replace('-', '', $end_date);
            $msg_date = date('Y年m月d日', strtotime($end_date));
        }*/
        $chat_record = $service_rong_chat_record->getAllRecords($params, 'id,msg_type,job_id,station,from_name, msg_content, send_time,msg_timestamp,send_origin');
        $total_page = $chat_record->totalPage;
        $chat_record = $chat_record->items;
        $is_sort = false;
        foreach ($chat_record as $k => $v) {
            $chat_record[$k]['msg_date'] = date('Y年m月d日', $v['msg_timestamp']);
            $chat_record[$k]['msg_format_date'] = date('Y-m-d', $v['msg_timestamp']);
            //图片
            if(!$v['msg_content']){
                unset($chat_record[$k]);
                $is_sort = true;
            }
            if ($v['msg_type'] == 2) {
                $imgs = explode(',', $v['msg_content']);
                $chat_record[$k]['msg_content'] = '';
                if (!empty($imgs[2])) {
                    $chat_record[$k]['msg_content'] .= '<img style="max-width:400px;" src="'. $imgs[2] .'">';
                }else{
                    if(strpos($v['msg_content'],'http',0)===false){
                        $chat_record[$k]['msg_content'] =  $v['msg_content'];
                    }else{
                        $chat_record[$k]['msg_content'] = '<img style="max-width:400px;" src="'. $v['msg_content'] .'">';
                    }
                }
            }
            //语音
            if ($v['msg_type'] == 3) {
                $chat_record[$k]['msg_content'] = '【语音】';
            }
        }
        if($is_sort){
            $chat_record = array_values($chat_record);
        }
        $data = array(
            'list' => array_reverse($chat_record),
            'pre_page' => ($page + 1) < $total_page ? $page + 1 : $total_page,
            'after_page' => ($page - 1) > 0 ? $page - 1 : 1,
            'total_page' => $total_page,
            'curr_page' => $page,
            'msg_date' => $msg_date,
            'start_timestamp' => $start_timestamp,
            'end_timestamp' => $end_timestamp,
            'curr_timestamp' => empty($date) ? $end_timestamp : strtotime($date),
            'start_date' => $start_date,
            'end_date' => $end_date
        );
        return $this->jsonMsg(true, '查询成功', $data);
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
        $days = base_lib_BaseUtils::getStr($pathdata['days'], 'int', 90);
        $params = array(
            'p_hr' => $person_id . '_' . $this->_userid,
            'start_time' => time() - $days*24*3600
        );
        $service_rong_chat_record = new base_service_rong_rongchatrecord();
        $record = $service_rong_chat_record->getOneRecord($params, 'msg_content,station,job_id');
        if ($record) {
            return $this->jsonMsg(true, '3个月内与我聊过', array('job_id' => $record['job_id']));
        }
        return $this->jsonMsg(false, '3个月内没有聊过');
    }

    /**
     * 获取当前账号的历史联系人记录
     * @param $inPath
     * @return mixed|string
     */
    public function pageGetAccountChatHistory($inPath){
        $pathdata       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $keyword = base_lib_BaseUtils::getStr($pathdata['keyword'], 'string', '');
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        $company_id = $this->_userid;
        /* $service_chat   = company_service_chat::getInstance($account_id,$company_id);
         $bind_person_id = $service_chat->getBindPersonId();*/
        $service_rong_chat_record = new base_service_rong_rongchatrecord();
        /* $params = array(
             'account_id' => $bind_person_id,
             'search_keyword' => $keyword
         );*/
        $params = array(
            'account_id' => $account_id,
            'search_keyword' => $keyword
        );
        $init_info = [];
        $has_init_info = false;
        $params['group_by'] = 'group by p_hr';
        $params['start_timestamp'] = time() - 30*24*3600;
        $chat_records = $service_rong_chat_record->getGroupRecords($params, 'id,msg_type,from_account,to_account,send_origin,p_name, hr_name, job_id, resume_id, msg_timestamp,msg_content,station,p_hr');

        $jobs = [];
        if (!empty($chat_records)) {
            $job_ids = base_lib_BaseUtils::getProperty($chat_records, 'job_id');
            $service_job = new base_service_company_job_job();
            $jobs = $service_job->getJobsByIds($job_ids, 'status,job_id,end_time,is_effect,check_state,station');
            $jobs = base_lib_BaseUtils::array_key_assoc($jobs, 'job_id');
            if(!empty($jobs)){
                foreach($jobs as $key => $value){
                    if($value["end_time"] < date("Y-m-d 00:00:00") || in_array($value["status"],[0,9]) || in_array($value["status"], [2,3,9]) || $value["is_effect"] == 0){
                        $jobs[$key]["job_status"] = 0;
                    }else{
                        $jobs[$key]["job_status"] = 1;
                    }

                }
            }
        }
        //历史记录对应关系数组
        $items = [];
        $items_data = array();
        //2019-11-30  一个月后清楚
        $service_swychat = new SWYChat("APP");
        if (!empty($chat_records)) {
            $items = $chat_records;
            /* $resume_ids = base_lib_BaseUtils::getProperty($items, 'resume_id');
             $service_resume = new base_service_person_resume_resume();
             $resume = $service_resume->getResumes($resume_ids, 'person_id, resume_id',false);
             $resume = base_lib_BaseUtils::array_key_assoc($resume->items, 'person_id');
            $person_ids = base_lib_BaseUtils::getProperty($resume, 'person_id');*/
            //获取用户的名称和头像
            $person_ids = array();
            foreach ($chat_records as $key=>$val){

                if(1==$val['send_origin']){
                    $person_ids[] = $val['to_account'];
                    $swy_info_data = $service_swychat->getAccountInfo($val['to_account']);
                }else{
                    $person_ids[] = $val['from_account'];
                    $swy_info_data = $service_swychat->getAccountInfo($val['from_account']);
                }
            }
            $service_person = new base_service_person_person();
            $person = $service_person->getPersons($person_ids, 'person_id,user_name,photo');
            if(!empty($person)){
                $person_photo = base_lib_BaseUtils::array_key_assoc($person->items,'person_id');
            }else{
                $person_photo = array();
            }

            //会话信息的求职者前缀
            $service_swychat = new SWYChat("APP");
            $session_pre = $service_swychat->getPreString();
            $default_photo  = base_lib_Constant::STYLE_URL."/img/user_img.png";
            foreach($items as $k => $v) {
                //求职者id
                if(1==$v['send_origin']){
                    $item_person_id = $v['to_account'];
                }else{
                    $item_person_id = $v['from_account'];
                }
                if (!empty($v['job_id'])) {
                    $items[$k]['station'] = !empty($jobs[$v['job_id']]['station']) ? $jobs[$v['job_id']]['station'] : '';
                }else{
                    $items[$k]['station'] = '';
                }
                if (!empty($v['job_id'])) {
                    $items[$k]['job_status'] = $jobs[$v['job_id']]['job_status'];
                }else{
                    $items[$k]['job_status'] = 1;
                }


                //兼容判断 头像显示
                if(empty($person_photo[$item_person_id]['photo'])){
                    $items[$k]['photo'] = $default_photo;
                }else{
                    $items[$k]['photo'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP .$person_photo[$item_person_id]['photo'];
                }
                if ($v['msg_type'] == 2) {
                    $items[$k]['msg_content'] = '【图片】';
                }
                if ($v['msg_type'] == 3) {
                    $items[$k]['msg_content'] = '【语音】';
                }
                $items_data[] = $items[$k];

                $pre_item_person_id = $session_pre . $item_person_id;
                if ($person_photo[$item_person_id]['user_name']) {
                    $name = $person_photo[$item_person_id]['user_name'];
                } else {
                    $name = $v['p_name'];
                }

                $init_info[] = array(
                    "SessionId"         => $pre_item_person_id,//会话ID Sqcloud::PRE_APP_V2.$person_id
                    "SessionNick"       => $name,//会话昵称 为空
                    "SessionImage"      => $items[$k]['photo'],//会话头像  为空
                    "Account_id"        => $pre_item_person_id,//会话ID
                    "unread"            => 0,//未读消息数 默认是0
                    "ResumeId"          => $v['resume_id'],//简历编号
                    "SessionJobId"      => $v['job_id'],//聊天职位编号
                    "SessionJobStation" => $items[$k]['station'],//聊天职位//可以为空
                    "MsgTimeStamp"      => $v['msg_timestamp'],
                    "id"                => 'p2p-'.$pre_item_person_id,
                );
            }
        }
        if(!empty($init_info)){
            $has_init_info = true;
        }

        $data = array(
            'list' => $items_data,
            'has_init_info' => $has_init_info,
            'init_info' => $init_info
        );
        return $this->jsonMsg(true, '查询成功', $data);
    }

    /**
     *@desc 绑定账号
     */
    public function pageBindAccount($inPath){
        $path_data      = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $path_data      = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $account_id     = base_lib_BaseUtils::getStr($path_data["account_id"],"int",0);
        if(empty($account_id)){
            echo json_encode(array("error" =>"绑定失败，缺少账号编号","code" => 0));exit;
        }
        //获取求职者信息
        $service_person = new base_service_person_person();
        $service_related = base_service_hractivity_related::getInstance();

        //判断该账号是否已经被关联
        $company_id     = $this->_userid;
        $related_info    = $service_related->getRelatedByAccount($account_id,"person_id,company_id,account_id");
        if(!empty($related_info)){
            echo json_encode(array("error" =>"绑定失败，您已经绑定过了","code" => 0));exit;
        }
        $service_account = new base_service_company_account();
        $account_info    = $service_account->getAccount($account_id,"account_id,mobile_phone");
        if(empty($account_info) || empty($account_info["mobile_phone"])){
            echo json_encode(array("error" =>"绑定失败，该账号没有手机号","code" => 0));exit;
        }
        $mobile_phone = $account_info["mobile_phone"];
        $person = $service_person->getPersonByPhone($mobile_phone,null,"person_id,user_name,photo,password",null,1);
        if(empty($person)){
            $person_id = $this->_registerByMobile($mobile_phone);
        }else{
            $person_id = $person["person_id"];
            $related_info    = $service_related->getRelatedCompany($person_id,"person_id,company_id,account_id");
            if(!empty($related_info)){
                $service_company = new base_service_company_company();
                $company_info    = $service_company->getCompany($related_info["company_id"], 1,"company_name,company_shortname");
                $_company_name   = $company_info["company_name"];
                echo json_encode(array("error" =>"该手机号已和【{$_company_name}】绑定，需解绑后才能继续绑定",
                    "code" => 2,"unbind_person_id" => $related_info["person_id"],"unbind_account_id"=>$related_info["account_id"]));exit;
            }
        }
        if(empty($person_id)){
            echo json_encode(array("error" =>"绑定失败","code" => 0));exit;
        }

        $related_result = $service_related->addRelated($person_id, $account_id, $company_id);
        if($related_result === false){
            echo json_encode(array("error" =>"绑定失败，请重试","code" => 0));exit;
        }
        echo json_encode(array("success" =>"绑定成功","code" => 1));exit;
    }

    /**
     * 给会话用户标记状态
     * @param $person_ids
     * @return array
     */
    public function personStatus($person_ids)
    {
        //判断聊天状态  1:打招呼；2沟通中；3面试邀请；4：面试通过；5：已发offer;6:不合适
        //邀请
        $person_status = array();
        $company_id = $this->_userid;
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        $temp_person_ids = [];
        $service_company_resume_jobinvite = new base_service_company_resume_jobinvite();
        $job_invite =  $service_company_resume_jobinvite->getInviteByPersonIds($company_id,$person_ids,'invite_id,person_id,re_status,audition_result,offer_send_time',3,"order by create_time asc");

        foreach ($job_invite as $key => $val) {
            $person_id = $val['person_id'];
            if (9 == $val['audition_result']) {
                if(isset($person_status[$person_id])){
                    if(5 != $person_status[$person_id]){
                        $person_status[$person_id] = 5;
                    }
                }else{
                    $person_status[$person_id] = 5;
                    $temp_person_ids[] = $person_id;
                }
            } elseif (1 == $val['audition_result']) {
                if($val['offer_send_time']){
                    if(isset($person_status[$person_id])){
                        if(5 != $person_status[$person_id]){
                            $person_status[$person_id] = 5;
                        }
                    }else{
                        $person_status[$person_id] = 5;
                        $temp_person_ids[] = $person_id;
                    }
                }else{
                    if(isset($person_status[$person_id])){
                        if(5 != $person_status[$person_id]){
                            $person_status[$person_id] = 4;
                        }
                    }else{
                        $person_status[$person_id] = 4;
                        $temp_person_ids[] = $val['person_id'];
                    }
                }

            } elseif (2 == $val['audition_result']) {
                if(isset($person_status[$person_id])){
                    if(!in_array($person_status[$person_id], [5, 4])){
                        $person_status[$person_id] = 6;
                    }
                }else{
                    $person_status[$person_id] = 6;
                    $temp_person_ids[] = $val['person_id'];
                }
            } elseif (0 == $val['audition_result']) {
                if (in_array($val['re_status'], [0, 1, 2])) {
                    if(isset($person_status[$person_id])){
                        if(!in_array($person_status[$person_id], [ 5, 4])){
                            $person_status[$person_id] = 3;
                        }
                    }else{
                        $person_status[$person_id] = 3;
                        // $temp_person_ids[] = $person_id;
                    }
                }
            }
        }
        //  var_dump($job_invite);
       //   var_dump($person_status);

        //聊一聊设置的不合适
        $service_rongsetfix = new base_service_rong_rongsetfix();
        $person_rongsetfix = $service_rongsetfix->getFixByPersonIds($account_id,$person_ids,'person_id,set_status',3);
        $person_rongsetfix = base_lib_BaseUtils::array_key_assoc($person_rongsetfix,'person_id');
//        foreach ($person_rongsetfix as $key=>$val){
//            if (0 == $val['set_status']) {
//                if(isset($person_status[$val['person_id']])){
//                    if(!in_array($person_status[$val['person_id']], [ 5, 4,])){
//                        $person_status[$val['person_id']] = 6;
//                    }
//                }else{
//                    $person_status[$val['person_id']] = 6;
//                }
//            }else{
//                if(isset($person_status[$val['person_id']])){
//                    if(!in_array($person_status[$val['person_id']], [5, 4,3])){
//                        $person_status[$val['person_id']] = 1;
//                    }
//                }
//            }
//        }

        $diff_personIds = array_diff($person_ids,$temp_person_ids);
        $temp_person_ids = [];

        if(!empty($diff_personIds)){
            $service_company_resume_jobinvite = new base_service_person_apply();
            $job_apply_invite = $service_company_resume_jobinvite->personIsApplyCompanysV1($diff_personIds, $company_id, 'apply_id,person_id,re_status,audition_result','order by apply_id asc',3)->items;
            // var_dump($job_apply_invite);
            //$job_apply_invite = base_lib_BaseUtils::array_key_assoc();
            foreach ($job_apply_invite as $key => $val) {
               if (3 == $val['audition_result']) {
                    if(isset($person_status[$val['person_id']])){
                        if( !in_array($person_status[$val['person_id']], [6, 5, 4])){
                            $person_status[$val['person_id']] = 6;
                        }
                    }else{
                        $person_status[$val['person_id']] = 6;
                        $temp_person_ids[] = $val['person_id'];
                    }
                } elseif (0 == $val['audition_result']) {
                    if(1==$val['re_status']){
                        if(!isset($person_status[$val['person_id']])){
                            if( !in_array($person_status[$val['person_id']], [6,5, 4])){
                                $person_status[$val['person_id']] = 3;
                            }
                        }else{
                            $person_status[$val['person_id']] = 3;
                            $temp_person_ids[] = $val['person_id'];
                        }
                    }
                    if(3==$val['re_status']){
                        if(!isset($person_status[$val['person_id']])){
                            if(isset($person_rongsetfix[$val['person_id']])){
                                if( !in_array($person_status[$val['person_id']], [6,5, 4,3]) &&$person_rongsetfix[$val['person_id']]['set_status']!=1){
                                    $person_status[$val['person_id']] = 6;
                                }
                            }else{
                                if( !in_array($person_status[$val['person_id']], [6,5, 4])){
                                    $person_status[$val['person_id']] = 6;
                                }
                            }

                        }else{
                            if($person_rongsetfix[$val['person_id']]['set_status']!=1){
                                $person_status[$val['person_id']] = 6;
                                $temp_person_ids[] = $val['person_id'];
                            }

                        }
                    }
                }
            }
        }

        $diff_personIds = array_diff($diff_personIds,$temp_person_ids);

        foreach ($person_rongsetfix as $key=>$val){
            if (0 == $val['set_status']) {
                if(isset($person_status[$val['person_id']])){
                    if(!in_array($person_status[$val['person_id']], [ 5, 4,])){
                        $person_status[$val['person_id']] = 6;
                    }
                }else{
                    $person_status[$val['person_id']] = 6;
                }
            }
            if(1 == $val['set_status']){
                if(isset($person_status[$val['person_id']])){
                    if(!in_array($person_status[$val['person_id']], [5, 4,3])){
                        $person_status[$val['person_id']] = 1;
                    }
                }
            }

        }


            //聊一聊
            $service_rongchatrecord = new base_service_rong_rongchatrecord();
            //企业对求职者
            $person_recode_1 = $service_rongchatrecord->getChatByPersonIds($account_id, $person_ids, 1, 'DISTINCT to_account as person_id',3);
            $person_recode_1 = base_lib_BaseUtils::getProperty($person_recode_1,'person_id');
            foreach ($person_recode_1 as $val){
                if(!isset($person_status[$val])){
                    $person_status[$val] = 1;
                }
            }

           // var_dump($person_recode_1);
            $person_recode_2 = $service_rongchatrecord->getChatByPersonIds($account_id, $person_ids, 2, 'DISTINCT from_account as person_id',3);
            $person_recode_2 = base_lib_BaseUtils::getProperty($person_recode_2,'person_id');
          //  var_dump($person_recode_2);

            foreach ($person_recode_2 as $val){
                if(isset($person_status[$val])&&$person_status[$val]==1){
                    $person_status[$val] = 2;
                }else{
                    if(!isset($person_status[$val])){
                        $person_status[$val] = 1;
                    }
                }
            }

        //var_dump($person_status);die();
        return $person_status;
    }

    public function personOneStatus($person_id)
    {
        //判断聊天状态  1:打招呼；2沟通中；3面试邀请；4：面试通过；5：已发offer;6:不合适
        //邀请
        //$person_id = 230006505;
        $status = 1;
        $company_id = $this->_userid;
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        $service_company_resume_jobinvite = new base_service_company_resume_jobinvite();
        $job_invite =  $service_company_resume_jobinvite->getInviteByPersonIds($company_id,[$person_id],'person_id,re_status,audition_result,offer_send_time',3,$order='order by create_time asc');

        foreach ($job_invite as $key => $val) {
            if (9 == $val['audition_result']) {
                $status = 5;
            } elseif (1 == $val['audition_result']) {
                if($val['offer_send_time']){
                    $status = 5;
                }else{
                    $status = 4;
                }
            } elseif (2 == $val['audition_result']) {
                if(isset($person_status[$val['person_id']])){
                    if(!in_array($status, [5, 4])){
                        $status = 6;
                    }
                }else{
                    $status = 6;
                }
            } elseif (0 == $val['audition_result']) {
                if (in_array($val['re_status'], [0, 1, 2])) {
                    if(!in_array($status, [ 5, 4])){
                        $status = 3;
                    }
                }
            }
        }

        //聊一聊设置的不合适
        $service_rongsetfix = new base_service_rong_rongsetfix();
        $person_rongsetfix = $service_rongsetfix->getFixByPersonId($account_id,$person_id,'person_id,set_status',3);


        $service_company_resume_jobinvite = new base_service_person_apply();
        $job_apply_invite = $service_company_resume_jobinvite->personIsApplyCompanys([$person_id], $company_id, 'person_id,re_status,audition_result')->items;
        foreach ($job_apply_invite as $key => $val) {
            if (3 == $val['audition_result']) {
                if(isset($person_rongsetfix['set_status'])&&isset($person_rongsetfix['set_status'])!=1){
                    if (!in_array($status, [6, 5, 4])) {
                        $status = 6;
                    }
                }else{
                    if (!in_array($status, [6, 5, 4])) {
                        $status = 6;
                    }
                }
            } elseif (0 == $val['audition_result']) {
                if (1 == $val['re_status']) {
                    if (!in_array($status, [6, 5, 4])) {
                        $status = 3;
                    }
                }
                if (3 == $val['re_status']) {
                    if(isset($person_rongsetfix['set_status'])){
                        if(1!=$person_rongsetfix['set_status']){
                            if (!in_array($status, [6, 5, 4,3])) {
                                $status = 6;
                            }
                        }
                    }else{
                        if (!in_array($status, [6, 5, 4])) {
                            $status = 6;
                        }
                    }
                }
            }
        }

        if($person_rongsetfix){
            if(0 == $person_rongsetfix['set_status']){
                if(!in_array($status, [ 5, 4 ])){
                    $status = 6;
                }
            }
            if(1 == $person_rongsetfix['set_status']){
                if($status==6){
                    $status = 1;
                }
            }
        }

        //聊一聊
        $service_rongchatrecord = new base_service_rong_rongchatrecord();
        //企业对求职者
        $person_recode_1 = $service_rongchatrecord->getChatByPersonId($account_id, $person_id, 1, 'to_account as person_id',3);
        if($person_recode_1){
            if(!in_array($status, [6, 5, 4,3])){
                $status = 1;
            }
        }
        if($status==1 && $person_recode_1){
            $person_recode_2 = $service_rongchatrecord->getChatByPersonId($account_id, $person_id, 2, 'from_account as person_id',3);
            if($person_recode_2){
                $status = 2;
            }
        }
        return $status;
    }

    public function pagePersonOneStatus($inPath)
    {
        //判断聊天状态  1:打招呼；2沟通中；3面试邀请；4：面试通过；5：已发offer;6:不合适
        //邀请
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $person_id = $params['person_id'];
        $status = 1;
        $company_id = $this->_userid;
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        $service_company_resume_jobinvite = new base_service_company_resume_jobinvite();
        $job_invite =  $service_company_resume_jobinvite->getInviteByPersonIds($company_id,[$person_id],'invite_id,person_id,re_status,audition_result,offer_send_time',3,$order='order by create_time asc');

        foreach ($job_invite as $key => $val) {
            if (9 == $val['audition_result']) {
                $status = 5;
            } elseif (1 == $val['audition_result']) {
                if($val['offer_send_time']){
                    $status = 5;
                }else{
                    $status = 4;
                }
            } elseif (2 == $val['audition_result']) {
                if(isset($person_status[$val['person_id']])){
                    if(!in_array($status, [5, 4])){
                        $status = 6;
                    }
                }else{
                    $status = 6;
                }
            } elseif (0 == $val['audition_result']) {
                if (in_array($val['re_status'], [0, 1, 2])) {
                    if(!in_array($status, [ 5, 4])){
                        $status = 3;
                    }
                }
            }
        }
        echo "邀请数据**************<br>";
        var_dump($job_invite);
        var_dump($status);
        echo "**************<br>";
        //聊一聊设置的不合适
        $service_rongsetfix = new base_service_rong_rongsetfix();
        $person_rongsetfix = $service_rongsetfix->getFixByPersonId($account_id,$person_id,'person_id,set_status',3);

        echo "聊一聊设置的不合适**************<br>";
        var_dump($person_rongsetfix);
        echo "**************<br>";
        $service_company_resume_jobinvite = new base_service_person_apply();
        $job_apply_invite = $service_company_resume_jobinvite->personIsApplyCompanysV1($person_id, $company_id, 'person_id,re_status,audition_result','order by apply_id asc',3)->items;
        foreach ($job_apply_invite as $key => $val) {
            if (3 == $val['audition_result']) {
                if(isset($person_rongsetfix['set_status'])&&isset($person_rongsetfix['set_status'])!=1){
                    if (!in_array($status, [6, 5, 4])) {
                        $status = 6;
                    }
                }else{
                    if (!in_array($status, [6, 5, 4])) {
                        $status = 6;
                    }
                }
            } elseif (0 == $val['audition_result']) {
                if (1 == $val['re_status']) {
                    if (!in_array($status, [6, 5, 4])) {
                        $status = 3;
                    }
                }
                if (3 == $val['re_status']) {
                    if(isset($person_rongsetfix['set_status'])){
                        if(1!=$person_rongsetfix['set_status']){
                            if (!in_array($status, [6, 5, 4,3])) {
                                $status = 6;
                            }
                        }
                    }else{
                        if (!in_array($status, [6, 5, 4])) {
                            $status = 6;
                        }
                    }
                }
            }
        }
        echo "申请**************<br>";
        var_dump($job_apply_invite);
        echo "**************<br>";
       // var_dump($job_apply_invite);
       // var_dump($status);
        if($person_rongsetfix){
            if(0 == $person_rongsetfix['set_status']){
                if(!in_array($status, [ 5, 4 ])){
                    $status = 6;
                }
            }
            if(1 == $person_rongsetfix['set_status']){
                if($status==6){
                    $status = 1;
                }
            }
        }

        //聊一聊
        $service_rongchatrecord = new base_service_rong_rongchatrecord();
        //企业对求职者
        $person_recode_1 = $service_rongchatrecord->getChatByPersonId($account_id, $person_id, 1, 'to_account as person_id',3);
        if($person_recode_1){
            if(!in_array($status, [6, 5, 4,3])){
                $status = 1;
            }
        }
        if($status==1 && $person_recode_1){
            $person_recode_2 = $service_rongchatrecord->getChatByPersonId($account_id, $person_id, 2, 'from_account as person_id',3);
            if($person_recode_2){
                $status = 2;
            }
        }
        return $status;
    }
    /**
     * 设置打招呼
     */
    public function pageSetHello($inPath)
    {
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $is_effect = base_lib_BaseUtils::getStr($params['is_effect'], 'int', 0);
        $service_set_hello = new base_service_rong_rongsethello();
        $account_id = base_lib_BaseUtils::getCookie('accountid');
        $res = $service_set_hello->setHelloStatus($account_id, $is_effect);
        if ($res) {
            if ($is_effect == 1) {
                return $this->ajax_data_json(SUCCESS, '开启成功');
            } else {
                return $this->ajax_data_json(SUCCESS, '开启失败成功');
            }
        } else {
            return $this->ajax_data_json(ERROR, '设置失败');
        }
    }

    /**
     * 修改打招呼默认模板
     * @param $inPath
     */
    public function pageSeTHelloDefaultTemplate($inPath)
    {
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $id = base_lib_BaseUtils::getStr($params['id'], 'int', 0);
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        //$company_id = $this->_userid;
        $service_hello_template = new base_service_rong_rongsethellotemplate();
        $res = $service_hello_template->setDefault($id, $account_id);
        //if ($res!=false) {
            return $this->ajax_data_json(SUCCESS, '设置成功');
        //} else {
        //    return $this->ajax_data_json(ERROR, '设置失败');
       // }
    }

    /**
     * 修改打招呼语
     * @param $inPath
     */
    public function pageSeTHelloUpdateTemplate($inPath)
    {
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $id = base_lib_BaseUtils::getStr($params['id'], 'int', 0);

        $validate = new base_lib_Validator();
        $validate->getNotNull($params['content'],'请输入内容');
        $content = $validate->getStr($params['content'],1,60,'最多60字');
        if($validate->has_err){
            return $this->ajax_data_json(ERROR, $validate->err[0]);
        }
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        $service_hello_template = new base_service_rong_rongsethellotemplate();
        $res = $service_hello_template->updateContent($id,$content ,$account_id);
        if ($res!==false) {
            return $this->ajax_data_json(SUCCESS, '编辑成功');
        } else {
            return $this->ajax_data_json(ERROR, '编辑失败');
        }
    }

    /**聊天设置不合适
     * @param $inPath
     */
    public function pageSetPersonFix($inPath)
    {
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $content = base_lib_BaseUtils::getStr($params['content'], 'string', '');
        $session_id = base_lib_BaseUtils::getStr($params['session_id'], 'string', '');
        $resume_id = base_lib_BaseUtils::getStr($params['resume_id'], 'int', 0);
        $job_id = base_lib_BaseUtils::getStr($params['job_id'], 'string', '');
        $template_id = base_lib_BaseUtils::getStr($params['template_id'], 'int', 0);
        $status = base_lib_BaseUtils::getStr($params['status'], 'int', 0);
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        $person_id = preg_replace('/[a-z|\_]+/',"",$session_id);
        $service_hello_template = new base_service_rong_rongsetfix();
        $insert_data = array(
            'account_id'=>$account_id,
            'company_id'=>$this->_userid,
            'person_id'=>$person_id,
            'resume_id'=>$resume_id,
            'template_id'=>$template_id,
            'content'=>$content,
            'job_id'=>$job_id,
            'set_status'=>$status,
        );
        $res = $service_hello_template->setPersonFix($insert_data);
        if ($res) {
            if(0==$status){
                $service_apply = new base_service_company_resume_apply();
                $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$account_id);
                $applys = $service_apply->getApplyPerson($this->_userid, $person_id,0,"company_id,apply_id,station,has_read,re_status",3);
                if(!empty($applys)){
                    //站内信
                    $service_message     = new base_service_message_messageperson();
                    $service_messagetype = new base_service_message_messagepersontype();
                    $item_message = [];
                    $item_message['sender']    = 'huibo.com';
                    $item_message['type']      = $service_messagetype->jobmessage;
                    $item_message['person_id'] = $person_id;
                    $item_message['subject']   = "面试结果通知";
                    $item_message['content']   = $content;
                    $service_message->addPersonMessage($item_message);
                }

                $this->setNotFix($person_id,2);

                $applys = base_lib_BaseUtils::array_key_assoc($applys, "apply_id");
                $apply_ids  = base_lib_BaseUtils::getProperty($applys, "apply_id");
                $apply_ids_ok = array();
                $apply_ids_no = array();
                foreach ($apply_ids as $apply_id) {
                    if (!in_array($applys[$apply_id]['company_id'], $company_resources->all_accounts))
                        continue;
                    $result = null;
                    //阅读状态必须是 已读未回复、未读 (re_status = 0)
                    if($applys[$apply_id]['re_status'] == 0){
                        $result = $service_apply->refusedReplayV2($apply_id, $applys[$apply_id]['company_id'], $content);
                    }else{
                        continue;
                    }
                    if ($result !== false) {
                        $apply_ids_ok[] = $apply_id;
                    }else{
                        $apply_ids_no[] = $apply_id;
                    }
                }

                if (empty($apply_ids_no)) {
                    //---------添加操作日志--------
                    $service_apply = new base_service_company_resume_apply();
                    $service_persons = new base_service_person_person();
                    $applys = $service_apply->getApplys($apply_ids_ok,$field="apply_id,station,person_id")->items;
                    $applys = base_lib_BaseUtils::array_key_assoc($applys, "apply_id");
                    $person_ids = base_lib_BaseUtils::getProperty($applys,'person_id');
                    $resume_infos = $service_persons->getPersons($person_ids,'person_id,user_name')->items;
                    $resume_infos = base_lib_BaseUtils::array_key_assoc($resume_infos,'person_id');
                    $log_message = '';
                    foreach($applys as $k=>$v)
                    {
                        $log_message .= "'".$resume_infos[$v['person_id']]['user_name']."'投递的简历'".
                            $v['station']."' ";
                    }
                    $common_oper_type = new base_service_common_account_accountoperatetype();
                    $service_oper_log = new base_service_company_companyaccountlog();
                    $common_oper_src_type = new base_service_common_account_accountlogfrom();
                    $insertItems=array(
                        "company_id"=>$this->_userid,
                        "source"=>$common_oper_src_type->website,
                        "account_id"=>base_lib_BaseUtils::getCookie('accountid'),
                        "operate_type"=>$common_oper_type->resume_refuse,
                        "content"=>"以下简历被设置成了不合适：".$log_message,
                        "create_time"=>date("Y-m-d H:i:s",time())
                    );
                    $service_oper_log->addLogToMongo($insertItems);
                    //-------------END------------
                }
            }
            // if(1==$status){
            $chat_status =  $this->personOneStatus($person_id);
            //}
            return $this->ajax_data_json(SUCCESS, '发送成功',['chat_status'=>$chat_status]);
        } else {
            return $this->ajax_data_json(ERROR, '发送失败');
        }
    }


    public function setNotFix($person_id,$state_value)
    {
        $service_invites    = new base_service_company_resume_jobinvite();
        $applyService       = new base_service_company_resume_apply();
        $service_invitetype = new  base_service_company_resume_invitetype();
        $accountid = base_lib_BaseUtils::getCookie('accountid');
        $invite_id_items = $service_invites->getInvitesByPersonId($this->_userid,$person_id,null,0,'invite_id');
        if(!empty($invite_id_items)){
            $invite_id = base_lib_BaseUtils::getProperty($invite_id_items,'invite_id');
        }else{
            return false;
        }
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
        $result = $service_invites->update_invite_state($company_resources->all_accounts, $invite_id, $state_value);
        $deleteApplies = $service_invites->getInvitesByIDs($invite_id, "apply_id,invite_type,resume_id")->items;
        $applyids  = array();
        $resumeids = array();
        foreach ($deleteApplies as $apply) {
            array_push($resumeids, $apply["resume_id"]);
            if (!base_lib_BaseUtils::nullOrEmpty($apply["resume_id"])
                && !in_array($apply["apply_id"], $applyids)
                && $apply['invite_type'] == $service_invitetype->jobapply) {

                array_push($applyids, $apply["apply_id"]);
            }
        }

        $recommendis = array();
        if (count($resumeids) > 0) {
            //同步修改推荐的简历状态
            $service_recommend = new base_service_company_resume_recommend();
            $recommends = $service_recommend->getRecommendInfoByResumeIds($company_resources->all_accounts, $resumeids, "recommend_id");
            if (!base_lib_BaseUtils::nullOrEmpty($recommends)) {
                foreach($recommends as $recommend) {
                    array_push($recommendis, $recommend['recommend_id']);
                }
            }
            if (count($recommendis) > 0) {
                $recommend_status = $this->__getRecommendStatus($state_value);
                if (!empty($recommend_status)) {
                    $service_recommend->updateRecommendStatus($recommend_status, $recommendis);
                }
            }
        }
        if (count($applyids) > 0) {
            $result = $applyService->SetApplyStateOfIDs(implode(",", $applyids),$state_value);
        }
    }
    /**
     * 修改不合适模板
     * @param $inPath
     */
    public function pageSeTFixUpdateTemplate($inPath)
    {
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $id = base_lib_BaseUtils::getStr($params['id'], 'int', 0);
        $validate = new base_lib_Validator();
        $validate->getNotNull($params['content'],'请输入内容');
        $content = $validate->getStr($params['content'],1,60,'最多60字');
        if($validate->has_err){
            //return $this->ajax_data_json(ERROR, $validate->err[0]);
            echo json_encode(array("error" =>$validate->err[0],));exit;
        }
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        $service_hello_template = new base_service_rong_rongsetfixtemplate();
        if($id>0){
            $res = $service_hello_template->updateContent($id,$content ,$account_id);
            if ($res!==false) {
                echo json_encode(array("success" => "保存成功","id" => $id));exit;
                // return $this->ajax_data_json(SUCCESS, '编辑成功');
            } else {
                echo json_encode(array("error" => "编辑失败","id" => $id));exit;
            }
        }else{
            $service_hello_template = new base_service_rong_rongsetfixtemplate();
            $count = $service_hello_template->getCountByAccount($account_id);
            if($count['num']>=4){
                echo json_encode(array("error" => "最多4条"));exit;
            }
            $res = $service_hello_template->addTemplate($this->_userid,$account_id,$content);
            if ($res!==false) {
                echo json_encode(array("success" => "保存成功","id" => $res));exit;
            } else {
                echo json_encode(array("error" => "失败"));exit;
            }
        }


    }

    /**
     * 删除不合适模板
     * @param $inPath
     */
    public function pageSeTFixDelTemplate($inPath)
    {
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $id = base_lib_BaseUtils::getStr($params['id'], 'int', 0);
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        $service_hello_template = new base_service_rong_rongsetfixtemplate();
        $res = $service_hello_template->delById($id ,$account_id);
        if ($res) {
            return $this->ajax_data_json(SUCCESS, '编辑成功');
        } else {
            return $this->ajax_data_json(ERROR, '编辑失败');
        }
    }


    /**
     * 增加不合适模板
     * @param $inPath
     */
    public function pageSetFixAddTemplate($inPath)
    {
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $id = base_lib_BaseUtils::getStr($params['id'], 'int', 0);
        $validate = new base_lib_Validator();
        $validate->getNotNull($params['content'],'请输入内容');
        $content = $validate->getStr($params['content'],1,60,'最多60字');
        if($validate->has_err){
            return $this->ajax_data_json(ERROR, $validate->err[0]);
        }
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        $service_hello_template = new base_service_rong_rongsetfixtemplate();
        $count = $service_hello_template->getCountByAccount($account_id);
        if($count['num']>=4){
            return $this->ajax_data_json(ERROR, '最多4条');
        }
        $res = $service_hello_template->addTemplate($this->_userid,$account_id,$content);
        if ($res) {
            return $this->ajax_data_json(SUCCESS, '编辑成功');
        } else {
            return $this->ajax_data_json(ERROR, '编辑失败');
        }
    }
    /**
     * 发送offer
     * @param $inPath
     */
    public function pageSendOffer($inPath)
    {
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $session_id = base_lib_BaseUtils::getStr($params['session_id'], 'string', '');
        $person_id = preg_replace('/[a-z|\_]+/',"",$session_id);
        $service_invite = new base_service_company_resume_jobinvite();
        $invite_item =  $service_invite->getInviteByPersonId($this->_userid,$person_id,null,null,'invite_id',3);
        if ($invite_item) {
            return $this->ajax_data_json(SUCCESS, '成功',$invite_item);
        } else {
            return $this->ajax_data_json(ERROR, '失败');
        }
    }


    public function pageIsHello($inPath)
    {
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $company_id = $this->_userid;
        $account_id     = base_lib_BaseUtils::getCookie('accountid');
        $resume_id = base_lib_BaseUtils::getStr($params['resume_id'], 'int', 0);
        //打招呼设置
        $status = false;
        $msg = '';
        $service_set_hello = new base_service_rong_rongsethello();
        $hello_list = $service_set_hello->getListByAccountId($account_id,'a.id as set_hello_id,b.id as template_id,a.is_effect,b.content,b.is_default','order by b.template_id asc',$company_id);
        if($resume_id > 0 && 1==$hello_list[0]['is_effect']){
           $service_resume = new base_service_person_resume_resume();
           $resume = $service_resume->getResume($resume_id,'person_id',false);
            //判断打招呼
            if(!empty($resume)){
                $serice_rongchatrecord = new base_service_rong_rongchatrecord();
                $chatrecord = $serice_rongchatrecord->getDateHaveRecord($resume['person_id'].'_'.$account_id,3,'id');
                if(empty($chatrecord)){
                    $service_download = new base_service_company_resume_download();
                    $service_apple    = new base_service_company_resume_apply();
                    $is_download      = $service_download->isResumeDownloaded($company_id, $resume_id);
                    $is_apply         = $service_apple->isApply($company_id, $resume_id);
                    if(!$is_download && !$is_apply){
                        foreach ($hello_list as $key=>$val){
                            if(1==$val['is_default']){
                                $status =  true;
                                $msg =  $val['content'];
                                break;
                            }
                        }
                    }
                }
            }
        }
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array ('status' => $status, 'msg' => $msg));
        die();
    }
    private function _isHttps(){
        if ( !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
            return true;
        } elseif ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
            return true;
        } elseif ( !empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
            return true;
        }
        return false;
    }

    private function _isIE() {
        $is_iE = strpos($_SERVER['HTTP_USER_AGENT'],"Triden");
        return $is_iE;
    }
}