<?php

    $base           = new base_components_basepage();
    $company_id     = $base->_userid;
    $account_id     = base_lib_BaseUtils::getCookie('accountid');
    $service_chat   = company_service_chat::getInstance($account_id,$company_id);
/*$bind_person_id = $service_chat->getBindPersonId();

//如果没得绑定 则判断是否能自动绑定
$can_auto_bind = false;
if(empty($bind_person_id)){
    $service_account = new base_service_company_account();
    $account_info    = $service_account->getAccount($account_id,"account_id,mobile_phone");
    if(!empty($account_info["mobile_phone"])){
        //判断是否该手机号已绑定
        $service_person = new base_service_person_person();
        $person_info    = $service_person->getPersonByPhone($account_info["mobile_phone"], null, "person_id,mobile_phone",null,1);
        if(empty($person_info)){
            //自动注册
//                $reg_person_id = $service_chat->registerByMobile($account_info["mobile_phone"], $need_bind = true);
//                if($reg_person_id !== false){
//                    $bind_person_id = $reg_person_id;
//                }
            $can_auto_bind = true;
        }else{
            //判断有没有绑定 如果没有绑定 自动绑定
            $service_related    = base_service_hractivity_related::getInstance();
            $relate_info        = $service_related->getRelatedCompany($person_info["person_id"], "company_id,account_id");
            if(empty($relate_info)){
                //绑定
//                    $related_result = $service_related->addRelated($person_info["person_id"], $account_id, $company_id);
//                    if($related_result !== false){
//                        $bind_person_id = $person_info["person_id"];
//                    }
                $can_auto_bind = true;
            }
        }
    }
}*/
    
    //验证码
    $this->assign('chat_seed', uniqid());
    //获取在招职位
    $company_resources  = base_service_company_resources_resources::getInstance($company_id);
    $chat_account_ids   = $company_resources->all_accounts;
    $service_job        = new base_service_company_job_job;
    $job_status         = new base_service_common_jobstatus();
    $job_list           = $service_job->getJobList($chat_account_ids, null, $job_status->use, 'job_id,station,account_id');
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
    /*$can_auto_bind = false;
    $this->assign('can_auto_bind', $can_auto_bind);*/
    $this->assign('chat_job_list',  json_encode($job_list));
    //获取浏览器版本
    $this->assign('can_chat', true);
    $browser = $service_chat->getbrowser();
    if($browser["browser"] == "IE" && $browser["version"] <=8){
        $this->assign('can_chat', false);
    }


    $service_swychat = new SWYChat("APP_COM");
    $swy_info_data = $service_swychat->getAccountInfo($account_id);
    list($nick_name,$photo) = $service_swychat->getAccountNameAndPhoto($account_id,'APP_COM');
    $swy_info['appkey'] = $service_swychat->getAppkey();
    $swy_info['accid'] = $swy_info_data[0];
    $swy_info['token'] = $swy_info_data[1];
    $this->assign('swy_info', $swy_info);


/*$qcloud_data    = $service_chat->qcloud;
$this->assign('chat_qcloud_data', $qcloud_data);
$this->assign('chat_appid', company_service_chat::QCLOUD_APPID);
$this->assign('chat_appidAi3rd', company_service_chat::QCLOUD_APPIDAT3RD);
$this->assign('chat_account_type', company_service_chat::QCLOUD_ACCOUNTTYPE);*/




/* $need_bind_person = false; //是否需要绑定
if(empty($bind_person_id)){
    $need_bind_person = true;
    $this->assign('need_bind_person', $need_bind_person);
}else{
    $service_swychat = new SWYChat("APP_COM");
    $swy_info_data = $service_swychat->getAccountInfo($account_id);
    list($nick_name,$photo) = $service_swychat->getAccountNameAndPhoto($account_id,'APP_COM');
    $swy_info['appkey'] = $service_swychat->getAppkey();
    $swy_info['accid'] = $swy_info_data[0];
    $swy_info['token'] = $swy_info_data[1];
    $this->assign('swy_info', $swy_info);
   $qcloud_data    = $service_chat->qcloud;
//        $qcloud_data = [
//                        "qcloud_identifier" => "hr11941388",
//                        "qcloud_usersig"    => "eJxlkEtPg0AURvf8iglbjXIZKIyJC6U0obVVQFu7mvCY4rTp8Bqo1vjfrdTESVyfk-vl3E8NIaQ-P8RXSZaVnZBUflRMRzdIN-TLP1hVPKeJpLjJ-0H2XvGG0WQjWTNA0yamYagKz5mQfMN-hbcGgFiAXVdx2nxHh51BAet0AZMRJqrCiwHO-RcvCMfebGLJOFsIq1sTWLau57*uiO-Xolys*uXhsPe9bTjz3LoIinQehbZ53108wS5yncA8rtPOSevy8RjJ0XUfT6vtFCakHd-dKpOS78-vANvC2MFA1K6eNS0vxbnaABtOXcZPuvalfQOW5lzB",
//                        "qcloud_nickname"   => "黄小芬",
//                        "qcloud_photo"      => "http://imgs.huibo.com/photo/2018-09-25/0925219716_middle.jpg",
//        ];
    $need_bind_person = false; //是否需要绑定
    $this->assign('need_bind_person', $need_bind_person);
    $this->assign('chat_qcloud_data', $qcloud_data);
    $this->assign('chat_appid', company_service_chat::QCLOUD_APPID);
    $this->assign('chat_appidAi3rd', company_service_chat::QCLOUD_APPIDAT3RD);
    $this->assign('chat_account_type', company_service_chat::QCLOUD_ACCOUNTTYPE);
        
    }*/
    
