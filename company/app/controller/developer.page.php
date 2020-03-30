<?php

/**
 * 开发测试使用
 * @desc     ： 用于开发测试 -- 内部使用
 * @ClassName: developer
 * @Date     : 2018/11/27 0027 下午 2:08
 * @author   ：PengCG
 */
class controller_developer extends components_cbasepage
{
    function __construct()
    {
        parent::__construct(false);
        header("Content-type: text/html; charset=utf-8");

    }
    private $_test_company = ['114540739', '115049603', '115036877',  '114913640' ,'114913696','114913683' ];
    private $_key = "asdfoiOLNASDFGOA234_23NSDLFL12;ASDFHHASD";



    /**
     * 所有方法必须加入该验证
     * 只通过我的计算机的请求111
     * @param $path_data
     */
    private function pre_do_virfiy($path_data){
        $_veryfy_key = $path_data['verify_code'];
        unset($path_data['verify_code']);
        ksort($path_data);
        if($_veryfy_key == md5(json_encode($path_data) . $this->_key)){
            return true;
        }else{
            exit( '服务请求验证失败1');
        }

    }




    public function pageAutomsgSend($path_data){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($path_data));
//        $this->pre_do_virfiy($params);

        echo '--------------start:-------------</br>';
        $x = new base_service_netfair_personapplynet();
        $x->AutomsgSend();


        echo '--------------end-------------</br>';

    }


    public function pagesolrresume($path_data){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($path_data));
        $this->pre_do_virfiy($params);

        $query = "*:*";
        if (!empty($postvar['keyword'])) {
            mb_regex_encoding('UTF-8');
            mb_internal_encoding("UTF-8");
            $keywords = mb_split("[,，、\|\t| |　]+", $params['keyword']);

            foreach ($keywords as $keyword) {
                if (!empty($keyword))
                    $_keywords[] = '"' . $keyword . '"';
            }
            if(!empty($_keywords)){
                if (!empty($postvar['contains_any_keyword'])) {
                    $query = "full_resume_keywords:(" . implode(" OR ", $_keywords) . ")^0";
                } else {
                    $query = "full_resume_keywords:(" . implode(" AND ", $_keywords).")^0";
                }
            }

        }


        $fq = [];
        if($params['resume_id'])
            $fq[] = "id:{$params['resume_id']}";
        $condition['fq'] = $fq;
        $condition['fl'] = $params['fl']?$params['fl']:"id,login_time,refresh_time,score";
        $solr = new SSolr(base_lib_BaseUtils::getSolr(), 8080, '/solr/resume/');
        $solr_resp = $solr->search($query, 0, 10, $condition);
        $raw_resp = $solr_resp->getRawResponse();
        $json = json_decode($raw_resp, true);


        $docs = $json['response']['docs'];
        foreach ($docs as &$v){
            $v['refrest_text'] = date('Y-m-d H:i:s',$v['refresh_time']);
        }

        echo json_encode($docs);die;
    }


    public function pagealterrefreshtime($path_data){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($path_data));
        $this->pre_do_virfiy($params);

        $resume_id = $params['resume_id'];
        $time = $params['refresh_time'];
        if(empty($resume_id))
            exit('请传入简历id');
        $service_resume = new base_service_person_resume_resume();
        $service_resume->updateResumeExpect(['resume_id'=>$resume_id],['refresh_time'=>$time]);
    }


    public $company_service         = null;
    public $openweixin              = null;
    public $weixin_service          = null;
    public $job_service             = null;
    public $job_status              = null;
    public $total                   = 0;


    /**
     * 企业微信推送 提醒刷新
     * @param $path_data
     */
    public function pageRefreshWxPush($path_data){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($path_data));
//        $this->pre_do_virfiy($params);

        $sweixin                    = new SOpenWeiXin();
        $weixin_account_service     = new base_service_weixin_companyweixin();
        $company_service            = new base_service_company_company();
        $job_service                = new base_service_company_job_job();
        $job_status                 = new base_service_common_jobstatus();

        $this->weixin_service       = $weixin_account_service;
        $this->openweixin           = $sweixin;
        $this->company_service      = $company_service;
        $this->job_service          = $job_service;
        $this->job_status           = $job_status;

        //获取所有正在使用企业微信
        //1.获取在用二维码
        $cur_page   = 1;
        $page_size  = 2;

        $scan_list  = $weixin_account_service->getCompanyScanInfo($cur_page, $page_size,"open_id,company_id,nickname",[989786]);
        var_dump($scan_list);
        $total_page = $scan_list->totalPage;
        $total_size = $scan_list->totalSize;

        if(empty($scan_list->items)){
            echo "======没有找到信息=====\r\n";return;
        }
        echo "======共找到{$total_size}条，{$total_page}页待推送微信=====\r\n";
        $this->sendWeixin($scan_list->items);
        //添加统计
        while(true){
            $cur_page++;
            echo "======第{$cur_page}页开始=====\r\n";
            if($cur_page > $total_page){
                echo "====所有微信信息都已发送完成,共发送{$this->total}个企业微信=====\r\n";return;
                break;
            }
            $scan_list  = $weixin_account_service->getCompanyScanInfo($cur_page, $page_size,"open_id,company_id,nickname");
            $this->sendWeixin($scan_list->items);
            sleep(1);
        }
    }



    /**
     *@desc 根据open_id 找到person_id 并推送消息
     */
    public function sendWeixin($list){
        if(empty($list)){
            return;
        }
        foreach($list as $value){
            $company_id = $value["company_id"];
            echo "正在推送企业编号{$company_id}....\r\n";

            $company_resources = base_service_company_resources_resources::getInstance($company_id);
            $account_ids = $company_resources->all_accounts;
            $job_list   = $this->job_service->getJobList($account_ids, null, $this->job_status->pub,"job_id");
            $job_count = count($job_list);
            if($job_count >0){
                $content = "汇博人才网提醒您，有{$job_count}条职位没有刷新，请通过微信【汇博招聘】及时刷新。 <a href='http://m.huibo.com/autologin/companyWeixinlogin?suer_name={$value["nickname"]}&type=refreshjob&openid=".$value["open_id"]."'>去刷新</a>";
                $result = $this->openweixin->SendMsg($value["open_id"], $content);
                echo "==结果：{$result}==\r\n";
                echo "====编号为：{$value["company_id"]}的企业 微信提醒刷新成功 职位数：{$job_count}=====\r\n";
            }else{
                echo "====编号为：{$value["company_id"]}的企业 没有可刷新职位，不提醒刷新=====\r\n";
            }
            $this->total = $this->total +1;
        }

    }


    public function pagedoJoblink($path_data){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($path_data));
        $this->pre_do_virfiy($params);


        $service_company_link = new base_service_blue_companylink();
        $service_company      = new base_service_company_company();
        $service_blue_company = new base_service_blue_company_company();
        $service_blue_job     = new base_service_blue_company_job();
        $blue_companyid = 2298;
        $pubed_job_num = $service_blue_job->getNoStopJobNum($blue_companyid);
        var_dump($pubed_job_num);

        $blue_company = $service_blue_company->getCompany($blue_companyid,1,"com_level,mb_account");
        var_dump($blue_company);
        if((int)$blue_company['com_level'] <= 1){
            //开通高级会员和赠送米币
            var_dump('open');
            $service_companyservice = new base_service_blue_company_newservice();
            $service_data = array(
                'company_id'        => $blue_companyid,
                'start_time'        =>  date('Y-m-d 00:00:00'),
                'end_time'          =>  date('Y-m-d 23:59:59',strtotime("+2 year")),
                'user_id'           => 0,
                'job_num'           => 100,
                'is_effect'         => 1,
                'com_level'         =>  4,
                'mb_num'            => 1000,
                'create_time'       => date("Y-m-d H:i:s"),
                'service_time_id'   => 2
            );
            $res = $service_companyservice->addCompanyService($service_data);
            var_dump($res);
        }else{
            //米币低于10，赠送20米币
            if($blue_company['mb_account']<10){
                $service_mbmanager = new base_service_blue_company_mbmanager();
                $service_mbmanager->actionAddMb(true,8,$blue_companyid,20);
            }
            var_dump($blue_company['mb_account']);die;
        }

        $blue_company = $service_blue_company->getCompany($blue_companyid,1,"com_level,mb_account");
        var_dump($blue_company);


//      $x = new base_service_blue_autofuntest_linkjobdeal();
//      $x->dolink();
    }



    public function pagegetCompanyInfo($path_data){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($path_data));
        $this->pre_do_virfiy($params);

        $company_id = $params['company_id'];

        $service_company = new base_service_company_company();
        $common_calling = new base_service_common_blue_bluecalling();
        $xx = new base_service_blue_company_company();
//        $xx->addCompanyFromHb(28724394);die;

        $company         = $service_company->getCompany($company_id, 1, "company_name,property_id,calling_id,size_id,area_id,address,info,link_man,link_tel,link_mobile,email,company_shortname,user_id,password,is_famous");
var_dump($company);
    }

    public function pageSmsAction($path_data){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($path_data));
        $this->pre_do_virfiy($params);

        $service_company = new base_service_blue_autofuntest_smsaction();
        $service_company->sendMsg();
    }




    public function pageStopSmsDO($path_data){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($path_data));
        $this->pre_do_virfiy($params);

        $job_id=$params['job_id'];
        $company_id=$params['company_id'];
        //-------------关闭职位发送短信：需要发送的求职者插入到待发列表中去 info_company_action_sms_log---------------
        $job = new base_service_company_job_job();
        $job->JobStopSmsSend($job_id,$company_id);
        //-------------END---------------

    }


    public function pageChatFast($path_data){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($path_data));
        $this->pre_do_virfiy($params);

        $service_chat = new company_service_chat(0,0);
        $service_resume = new base_service_person_resume_resume();
        $resume = $service_resume->getResume($params['resume_id'],'resume_id,person_id');

        $chat_params['resume_id'] = $params['resume_id'];
        $chat_params['person_id'] = $resume['person_id'];
        $chat_params['company_id'] = $params['company_id'];
        $accountid = $params['accountid'];
        $x = $service_chat->getChatNoticeStatus($this->_userid,$accountid,null,$chat_params, $chat_params['person_id']);
        var_dump($x);

    }

    public function pageTestJs(){

        $html = "
      <!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <title>Title</title>
</head>
<body>

</body>
 <script>
      
            var ua = window.navigator.userAgent.toLowerCase();
            if(ua.match(/MicroMessenger/i) == 'micromessenger'){
               alert('weixin');
            }else{
                alert('other');
            }
        </script>
</html>
       
        ";
        echo $html;
    }

//    /**
//     * 简历自动推荐
//     * @param $path_data
//     */
//    public function pageGetCOmmontResume($path_data){
//        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($path_data));
//        $solr_resume = new base_service_solr_resume();
//        $company_id =114913639 ;
//        $job_id = 8623133;
//        $job['station'] = "(阿斯?*蒂芬),./:-_";
//        $job['station'] = str_replace(['(',")","-","_",",",".","。","，","【","】","+","=",'/','\\',':','：','&'.'|','!','^','”', '~', '*', '?'],'',$job['station']);
//
//        $company_id = $params['company_id'];
//        $job_id = $params['job_id'];
//        $cur_page  = base_lib_BaseUtils::getStr($params['page'],'int',1);
//        $resume_ids = $solr_resume->resumeRecommendByJob($company_id,$job_id,$cur_page,[],10,true);
//        var_dump('最后的结果');
//        var_dump($resume_ids);
//
////        $x = $solr_resume->getCompanyHaveResume($company_id);
////        var_dump($x);
//    }

//    public function pageTestSys($inPath)
//    {
//
//        $html1 = "<!DOCTYPE html>
//                <html lang=\"en\">
//                <head>
//                    <meta charset=\"UTF-8\">
//                    <title>1111111</title>
//                </head>
//                <body>
//                我的设备是：<em id='sb'> </em>
//                </body>
//
//                <script type=\"text/javascript\" language=\"javascript\" src=\"//assets.huibo.com/js/jquery-1.8.3.min.js?v=20190425\"></script>
//                <script>
//                    if(\"ontouchstart\" in window){
//                        if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
//                            visit_sys = 'IOS';
//                        } else if (/(Android)/i.test(navigator.userAgent)) {
//                            visit_sys = 'Android';
//                        } else {
//                            visit_sys = '触屏版';
//                        }
//                    }else{
//                        visit_sys = 'PC';
//                    }
//                    $('#sb').html(visit_sys);
//                    alert(visit_sys);
//                    console.log(visit_sys);
//                </script>
//                </html>
//                ";
//        return $html1;
//    }


    //全职职位刷新时间修改
    public function pagehbJobrefreshalter($path_data){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($path_data));
        $this->pre_do_virfiy($params);

        $company_id = $params['company_id'];
        $job_id = $params['job_id'];
        $refresh_time = $params['refresh_time'];

        if(!in_array($company_id,$this->_test_company)){
            exit('该企业不在白名单');
        }

        $service_job = new base_service_company_job_job();
        $service_job->updateMongo(["refresh_time ='".$refresh_time."'"],$company_id,$job_id);
    }

//    public function pageXXX(){
//        $service_company = new base_service_company_company();
//        $x = new base_service_blue_company_company();
//        $x->addCompanyFromHb(114913696);die;
//        $company         = $service_company->getCompany(114913696, 1, "last_manage_time,company_name,property_id,calling_id,size_id,area_id,address,info,link_man,link_tel,link_mobile,email,company_shortname,user_id,password,is_famous");
//var_dump($company);
//    }

    //米币清理
    public function pageMbDeal($path_data){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($path_data));
        $this->pre_do_virfiy($params);


        $companyService = new base_service_blue_company_company();
        $company        = $companyService->getCompany($this->_userid, 1, 'company_id,mb_account');
        if (empty($company))
            return false;
        $service_mbmanager = new base_service_blue_company_mbmanager();
        $error             = $service_mbmanager->amountSpnet(true, $params['company_id'], $company['mb_account'], 3, null, '技术部--手动设置到期');
        if ($error)
            return $error;
        else
            return true;
    }

    //社税销售跟踪短信发送
    public function pageTallageSms($path_data){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($path_data));
        $this->pre_do_virfiy($params);

        $service_mbmanager = new base_service_blue_autofuntest_trackremind();
        $service_mbmanager->run();
    }

    //修改企业锁定时间
    public function pageTallageupdatelocktime($path_data){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($path_data));
        $this->pre_do_virfiy($params);

        $company_id = $params['company_id'];
        $update['lock_time'] = $params['lock_time'];
        if(empty($company_id) || empty($update))
            exit('参数不能为空');
        $service_companytallage =  new base_service_tallage_tallagecompany();

        $service_companytallage->update(['company_id'=>$company_id],$update);

        $res = $service_companytallage->selectOne(['company_id'=>$company_id],'company_id,lock_time');
        var_dump('修改成功');
        var_dump($res);
    }



    /**
     *
     * 接口路颈：1
     * 精品点定时器路径：1
     * http://company.hb.com/developer/AutoFUN/debug-open
     * 将精品职位手动修改为可再次扣费：1
     * http://company.hb.com/developer/ReSetSpentTime?job_id=职位id-职位id-职位id-职位id-职位id-职位id....
     * 精品点充值：1
     * http://company.beta.huibo.com/developer/QualiteBuy?company_id=&amount=10
     * 查询是否是精品职位：1
     * http://company.beta.huibo.com/developer/IsQualite?job_id=11998159-11998222-12763338-12763339
     *
     */





    public function pageisCompanyForbid($inPath){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->pre_do_virfiy($params);
        $service_company = new base_service_company_company();
        /*--------------boss端企业违规封号处理- 2018-12-15 --------------
        'test_wangman_001'
        114913683
        '无敌1234'
        8310469
        12901618
        */
        $boos_fordden = $service_company->isBossForbid(null, null, NULL, '15023254624');
        if ($boos_fordden['is_foribid'] === true) {
            echo '封了';
        } else {
            echo '没封';
        }

        session_start(); echo session_id();
    }


    public function pageDelResumePoint($inPath){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->pre_do_virfiy($params);

        $company_id        = $params['company_id'] ;
        $x = new base_service_part_company_resumepoint_pointaccount();
        $res = $x->delAccount($company_id);
        var_dump($res);
    }

    /**
     * 获取 一下渠道的企业信息
     * 1: 发布职位H5点击量,
     * 2: 职位管理H5点击量
     * 3: 首页H5点击量（投递数据入口）
     * 4: 首页H5点击量（固定入口）
     * @return mixed
     */
    public function pageMrecruitstatic($inPath){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->pre_do_virfiy($params);
        $x = new base_service_blue_autofuntest_mrecruitstatic();
       $x->getVisitcompanyIfno($params['type']);
    }




    public function pageinsertData($inPath){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
                $this->pre_do_virfiy($params);


        $mongo_personlog  = new base_service_company_mrecruit_personlog();
        $mongo_companylog = new base_service_company_mrecruit_companylog();

        $mongo_personlog->addVisitor('123','1233',1,1);
        $mongo_personlog->addVisitor('123','1233',2,2);
        $mongo_personlog->addVisitor('123','1233',3,2);
        $mongo_personlog->addVisitor('123','1233',3,1);
        $mongo_personlog->addVisitor('123','1233',1,2);
        $mongo_personlog->addVisitor('111','222',2,1);
        $mongo_personlog->addVisitor('111','222',1,1);
        $mongo_personlog->addVisitor('111','222',2,2);
        $mongo_personlog->addVisitor('111','222',3,2);


//        $mongo_companylog->addLog('123','123',1);
//        $mongo_companylog->addLog('123','123',2);
//        $mongo_companylog->addLog('123','123',3);
//        $mongo_companylog->addLog('123','123',4);
//        $mongo_companylog->addLog('123','123',5);
//        $mongo_companylog->addLog('123','123',6);
//        $mongo_companylog->addLog('123','123',7);
//        $mongo_companylog->addLog('123','123',2);
//        $mongo_companylog->addLog('123','123',5);
//
//        $mongo_companylog->addLog('123','111',5);
//        $mongo_companylog->addLog('123','111',4);
//        $mongo_companylog->addLog('123','111',3);
//        $mongo_companylog->addLog('123','111',2);

    }



    public function pagesyncjob($inPath){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
//        $this->pre_do_virfiy($params);

        $refreshed_company_ids = [123123,1231,12,31,23123123,1,23,12,31,23];
        $page_size= 2;
        $cur_page = 1;
        $go_set = true;
        do{
            $this_company_ids = array_slice($refreshed_company_ids,($cur_page-1)*$page_size,$page_size);

//            if(empty($this_company_ids)){
//                $go_set = false;
//            }else{
//                //执行修改_stamp_remark
//                $service_company = new base_service_company_company();
//                $service_company->update_stamp_remark($this_company_ids);
//                var_dump($this_company_ids);
//            }

            $cur_page++;
            var_dump($cur_page);
            if($cur_page==20)
                break;
        }while($go_set);

        var_dump($cur_page);
        die;


       $x = new base_service_blue_autofuntest_syncjobstop();
       $x->run();
    }


    //日期埋点统计
    public function pageH5Static($inPath){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->pre_do_virfiy($params);
        $days = $params['days'];
        $x = new base_service_company_mrecruit_mrecruitstatic();
        $res = $x->indexStatic(date('Y-m-d',strtotime("-{$days} days")),date('Y-m-d'));
        $x->outputMessage(1,$res);
    }

    //投递/登录求职者明细数据统计
    public function pagePersonStatic($inPath)
    {
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->pre_do_virfiy($params);
        $days = $params['days'];
        $x    = new base_service_company_mrecruit_mrecruitstatic();
        $x->loginStatic($days);
    }

    //模板统计
    public function pageTemplateStatic($inPath)
    {
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->pre_do_virfiy($params);
        $days = $params['days'];
        $x    = new base_service_company_mrecruit_mrecruitstatic();
        $x->templateStatic($days);
    }

    //企业端汇总统计（去重)
    public function pageh5CompanyStatic($inPath){
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->pre_do_virfiy($params);
        $days = $params['moths'];
        $x = new base_service_company_mrecruit_mrecruitstatic();
        $res = $x->companyStatic(date('Y-m-01',strtotime("-{$days} months")),date('Y-m-01'));
        $x->outputMessage(2,$res);
    }

    /**
     * 资源接口修改单元测试
     */
    public function pageIndex($inPath)
    {
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->pre_do_virfiy($params);

        $company_id        = $params['company_id'] ? $params['company_id'] : 114913639;
        $company_resources = base_service_company_resources_resources::getInstance($company_id, true, $params['accountid']);
        $companyresources  = $company_resources->getCompanyServiceSource();
//        $companyresources  = $company_resources->getCompanyServiceSource();
//        $companyresources  = $company_resources->getCompanyServiceSource();
        //        $companyresources = $company_resources->getCompanyServiceSource(['refresh_point']);
        //        $companyresources = $company_resources->getCompanyServiceSource(['boutique_point']);
        //        $companyresources = $company_resources->getCompanyServiceSource(['pricing_resource']);
        //        $companyresources = $company_resources->getCompanyServiceSource(['pricing_resource','boutique_point','refresh_point']);
        var_dump('【' . $company_id . '】该企业资源如下：');
        var_dump($companyresources);

    }


    public function pageCheck($inPath)
    {
        $params            = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->pre_do_virfiy($params);
        $company_id        = 28724394;//natural
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid);

        //pub_boutique_job refreshV2
        var_dump($params);
        var_dump($company_resources->check('pub_boutique_job', $params));

    }

    public function pageResumeCommon($inPath)
    {
        $params            = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->pre_do_virfiy($params);
        $sx = new base_service_solr_resume();
        $company_id = $params['company_id'];
        $job_id = $params['job_id'];
        $res = $sx->_resumeRecommendByJob($x,$company_id,$job_id,1,10,true);

    }




    public function pageConsume($inPath)
    {
        $params            = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->pre_do_virfiy($params);
        $company_id        = 28724394;//natural
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid);

        $params['company_id'] = 28724394;
        $params['job_id']     = 8620679;

        var_dump($company_resources->consume('refreshV2', $params));
    }

    //简历点扣点测试
    public function pageDown()
    {
        $params   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->pre_do_virfiy($params);
        $company_id         = 28724394;//natural
        $point              = 1;
        $resume_id          = 1212;
        $service_comservice = new base_service_company_service_comservice();
        $res                = $service_comservice->AddResumeDownNum($company_id, $point, $resume_id);
        var_dump($res);
    }

    public function pageIssqresume($inPath){
        $params   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->pre_do_virfiy($params);
        $ser_resources = new base_service_company_resources_resources($params['company_id']);
        $resume_down_restrict = $ser_resources->resume_down_restrict($params['resume_id'], $params['company_id']);
        var_dump($resume_down_restrict);
    }


    /**
     * 精品点定时器扣费测试
     * @param $inPath
     */
    public function pageAutoFUN($inPath)
    {

        $params   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->pre_do_virfiy($params);
        $is_debug = false;
        if ($params['debug'] === 'open') {
            $is_debug = true;
        }
        $x = new base_service_company_job_spentboutique();
        $x->autoExeSpent($is_debug);
    }

    //子账号回复率定时器
    public function pageAutoFUNreplyreate($inPath)
    {

        $params   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->pre_do_virfiy($params);

        $x = new base_service_blue_autofuntest_jobreplyreate();
        $x->runAccount();
    }


//    public function pageTest($inPath){
//        $params   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
//        $X= NEW base_service_blue_autofuntest_partresumepoint();
//        $X->run();
//    }

    public function pageGETxx($inPath){

        $params   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->pre_do_virfiy($params);
        $X= NEW base_service_person_appraise_appraise();
        $RES = $X->getAccountScore([1,2,3,4,5,6,7,8,9,10,20,11]);
        var_dump($RES);
    }

    public function pageTextjobgetNum($inPath){

        $params   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->pre_do_virfiy($params);

        //500错误不显示的解决
        ini_set("display_errors", "On");
        error_reporting(E_ALL || E_STRICT);
        $service_job_status = new base_service_common_jobstatus();
        $service_job        = new base_service_company_job_job();
        $company_id = $params['company_id'];
        $account_id = $params['account_id'];
        $company_resources = base_service_company_resources_resources::getInstance($company_id);
//        $account_source      = new base_service_company_accountservice();
//        $account_ids         = $account_source->getCompanyAccountIds($company_id);
//        $account_ids         = base_lib_BaseUtils::getPropertys($account_ids, 'account_id');
//        $has_pub_free_job_num = ($service_job->getJobCount($company_resources->all_accounts, $service_job_status->pub, $account_ids));  //已发布职位数量

        if(!$account_id)
            $_s_account_id = [];
        else
            $_s_account_id = [$account_id];
        $has_pub_free_job_num = ($service_job->getJobCount($company_resources->all_accounts, $service_job_status->pub, $_s_account_id));  //已发布职位数量

        var_dump($company_resources->all_accounts);
        var_dump($_s_account_id);
        var_dump('---发布的职位数量----（包含了精品）');
        var_dump($has_pub_free_job_num);
    }


    /**
     * 精品职位扣费时间重置为可再次修改
     * @param $inPath
     */
    public function pageReSetSpentTime($inPath)
    {
        $params  = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->pre_do_virfiy($params);
        $job_ids = explode('-', $params['job_id']);

        foreach ($job_ids as $job) {
            if (empty($job) || intval($job) === false) {
                echo '请按照 职位id-职位id 的格式传入职位';

                return;
            }
        }

        $x = new base_service_company_job_spentboutique();
        $x->resetSpentTime($job_ids);
        var_dump('现在可以再次扣费了');


    }


    /**
     * 是否是精品职位查询
     * @param $inPath
     */
    public function pageIsQualite($inPath)
    {
        $params  = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $job_ids = explode('-', $params['job_id']);
        $this->pre_do_virfiy($params);
        foreach ($job_ids as $job) {
            if (empty($job) || intval($job) === false) {
                echo '请按照 职位id-职位id 的格式传入职位';

                return;
            }
        }

        $x  = new base_service_company_job_spentboutique();
        $xx = $x->isQualite($job_ids);
        if (empty($xx)) {
            echo '都不是精品';
        } else {
            $xx = base_lib_BaseUtils::array_key_assoc($xx, 'job_id');
            foreach ($job_ids as $job_id) {
                echo '<br/>';
                echo $job_id . '的情况：';
                echo '<br/>';
                var_dump($xx[ $job_id ]);
                echo '<br/>';
            }
        }


    }

    //新投递微信败模板发送
    public function pageaggreInvite($inpath){
        $params  = base_lib_BaseUtils::saddslashes($this->getUrlParams($inpath));
        $this->pre_do_virfiy($params);
        $x = new base_service_weixin_newtemplatesmsg();
        $res = $x->addTemplateMsg('C03',2,14598878,15670178);
        var_dump($res);
        exit('已排入队列，等待发送，请稍等');
    }

    //新投递微信败模板发送
    public function pagerefreshInvite($inpath){
        $params  = base_lib_BaseUtils::saddslashes($this->getUrlParams($inpath));
        $this->pre_do_virfiy($params);
        $x = new base_service_weixin_newtemplatesmsg();
        $res = $x->addTemplateMsg('C04',2,14598878,15670178);
        var_dump($res);
        exit('已排入队列，等待发送，请稍等');
    }




    public function pageQualiteBuy($inPath){
        $params  = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->pre_do_virfiy($params);
        $company_id = $params['company_id'];
        $amount = $params['amount'];
        if(empty($company_id))
            exit('传入企业id');

        if(empty($amount))
            exit('传入精品点充值金额');

        $company_resources = base_service_company_resources_resources::getInstance($company_id);
        $companyresources  = $company_resources->getCompanyServiceSource();
        $x  = new base_service_company_job_spentboutique();

        $res = $x->qualitebuy($companyresources['service_id'],$amount);

        if(!$res){
            exit('充值失败');
        }else{
            $companyresources  = $company_resources->getCompanyServiceSource([],true);
            echo "充值成功，当前精品点余额为：".$companyresources['point_job_boutique_last'];
        }


    }

}