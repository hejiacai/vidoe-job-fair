<?php

    $base           = new base_components_basepage();
    $company_id     = base_lib_BaseUtils::getCookie('userid');
    $account_id     = base_lib_BaseUtils::getCookie('accountid');
    $service_chat   = company_service_chat::getInstance($account_id,$company_id);

    
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

