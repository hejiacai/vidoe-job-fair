<?php

    $base           = new base_components_basepage();
    $company_service = new base_service_netfair_company();
    $company_id= base_lib_BaseUtils::getCookie('netfair_userid');

    $company_net = $company_service->getBaseCompanyIdByID($company_id);
    $base_company_id     = $company_net['base_company_id'];
    $account_id     = base_lib_BaseUtils::getCookie('accountid');
    $service_chat   = company_service_chat::getInstance($account_id,$company_id);

    
    //验证码
    $this->assign('chat_seed', uniqid());

    //获取参加网络招聘会的职位
    $job_list_res = [];
    $net_service = new base_service_netfair_net();

    $sid = base_service_netfair_resume::$_cur_sid;
    if($sid){
        $sids = [$sid];
    }else{
        $nets = $net_service->getNetApplyList("id")->items;
        $sids = base_lib_BaseUtils::getPropertys($nets,'id');
    }
    if(!empty($sids)){

        $companynet_service = new base_service_netfair_companynet();
        $companynet = $companynet_service->getNetCompany($sids,$company_id,"sid,company_id");
        if(!empty($companynet)){
            $sids =  base_lib_BaseUtils::getPropertys($companynet,'sid');//当前企业参加的有效的招聘会

            //获取 当前企业参加的有效的招聘会 中报名的职位
            $jobnet_service = new base_service_netfair_jobnet();
            $jobs = $jobnet_service->getNetCompanyJob($company_id,$sids,"job_id");
            if(!empty($jobs)){
                $job_ids = base_lib_BaseUtils::getPropertys($jobs,'job_id');//有效的报名的职位

                $job_service = new base_service_netfair_job();
                $job_list = $job_service->GetJobDataByIds($job_ids,"station,job_id");

                foreach ($job_list as $k=>$v){
                    $job_list_res[] = $v;
                }
            }

        }

    }


    $this->assign('chat_job_list',  json_encode($job_list_res));
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


    
