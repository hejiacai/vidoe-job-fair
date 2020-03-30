<?php
/**
 * 
 * @ClassName controller_part
 * @Desc 兼职首页
 * @author huangwentao@huibo.com
 * @date 2015-08-03 上午09:51:47
 */
class controller_part extends components_cbasepage{
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct(true, "part");
	}


	
	//兼职首页
	public function pageIndex($inPath) {
        
	    if(!$this->canDo("part_manage")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }

		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $type = base_lib_BaseUtils::getStr($path_data['c'],'int',1);
        $service_job         = new base_service_part_company_job();
        $service_pay_job         = new base_service_part_company_payjobnum();
        $service_checkstatus = new base_service_common_part_jobcheckstatus();
        $service_jobstatus   = new base_service_common_part_partjobstatus();
        $service_jobapplystatus = new base_service_common_part_applystatus();
        $service_apply        = new base_service_part_company_apply();
        $service_invite       = new base_service_part_company_invite();
        $service_offer        = new base_service_part_company_joboffered();
        $service_joboffer     = new base_service_common_part_offer();
        $service_partcompany  = new base_service_part_company_partcompany();
        $service_area         = new base_service_common_area();
        $service_pointin      = new base_service_part_company_resumepoint_pointin();
        $service_partjobtop   = new base_service_part_job_partjobtop();
        $service_part_order_partorder = new base_service_part_order_partorder(); //兼职消费订单信息
        $service_part_company_job     = new base_service_part_company_job();//企业发布兼职信息
        $service_pointaccount = new base_service_part_company_resumepoint_pointaccount();
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid);
        $service_company_loginlog = new base_service_company_loginlog();
		//获得企业信息
        $companyService = new base_service_company_company(); 
        $company = $companyService->getCompany($this->_userid, true, 'company_id,company_name,is_audit,audit_state,is_effect,company_flag,'
																. 'resume_download_upperlimit,com_level,start_time,end_time,effect_job_end_time,hr_manager,'
																. 'hr_manager_sex,hr_tel,company_logo_path,company_bright_spot,'
                												. 'company_shortname,create_time,property_id,email,linkman,link_tel,calling_id,calling_ids,area_id,address,info,link_mobile');
		
		$xml = SXML::load('../config/company/company.xml');
        //设置兼职企业信息
        $items['company_name']  = $company['company_name']; //企业名称
        $items['login_time']    = $service_company_loginlog->getLastLoginLogTime($this->_userid)['login_time']; //登录时间
        $items['create_time']   = $company['create_time'];  //注册时间

        $service_company_resources_resources = base_service_company_resources_resources::getInstance($this->_userid);
        $letter_info = $service_company_resources_resources->getCompanyAuditStatusV2();

        $items['is_audit']      = $letter_info['is_audit'] == 1 ? 1:0; //是否认证
        $items['property_id']   = $company['property_id']; //企业性质
        $items['is_recharge']   = $service_part_order_partorder->getSumConsume($this->_userid)?1:0; // 是否充值消费 1-是，0-否
        $items['is_recruiting'] = $service_part_company_job->getJobCount($this->_userid)->items[0]['jobnums']>0?1:0; //是否在招
        $service_partcompany->editPartCompany($this->_userid,$items);


        //-------企业已发布免费兼职职位数量、付费兼职职位数量 start---------
        $service_order = new base_service_part_order_partorder();
        $FreePartJobNum = $service_order->getPriceConfig('FreePartJobNum');
        $has_pub_job_num                        = $service_job->getHasPubJobNum($this->_userid);
        $can_pub_pay_job_num                    = $service_pay_job->getEffectNumTotal($this->_userid);
        $can_pub_free_job_num                   = $FreePartJobNum;
        $has_pub_pay_job_num                    = $has_pub_job_num - $can_pub_free_job_num > 0 ? $has_pub_job_num - $can_pub_free_job_num : 0;
        $has_pub_free_job_num                   = $has_pub_job_num - $has_pub_pay_job_num;
        $this->_aParams['has_pub_free_job_num'] = $has_pub_free_job_num;
        $this->_aParams['can_pub_pay_job_num']  = $can_pub_pay_job_num;
        $this->_aParams['can_pub_free_job_num'] = $can_pub_free_job_num;
        $this->_aParams['has_pub_pay_job_num']  = $has_pub_pay_job_num;

        //这里把已发布的免费职位数和付费职位数 改成 剩余可发布的职位数 （但是自动名字不改，含义改了，页面有4个）
        $this->_aParams['has_pub_free_job_num'] = $can_pub_free_job_num - $has_pub_free_job_num;
        $this->_aParams['has_pub_pay_job_num']  = $can_pub_pay_job_num - $has_pub_pay_job_num > 0 ? $can_pub_pay_job_num - $has_pub_pay_job_num : 0;

        //-------企业已发布免费兼职职位数量、付费兼职职位数量 end-----------


        //企业首次进入兼职板块，赠送10点简历点
        $last_point = $service_pointaccount->getAccountByCompany($this->_userid,'last_amount');
        $BeginDate = date('Y-m-01 H:i:s', strtotime(date("Y-m-d H:i:s")));
        $cur_month_last_day = date('Y-m-d 23:59:58', strtotime("{$BeginDate} +1 month -1 day"));
        if(empty($last_point)) {
            $item['company_id'] = $this->_userid;
            $item['create_time'] = date("Y-m-d H:i:s");
            $item['last_amount'] = 10;
            $item['amount']      = 10;
            $service_pointaccount->insert($item);
            $service_pointin->addPoint($this->_userid, 10, $cur_month_last_day, 99);
        }
            /*====获得兼职企业信息===*/
		$is_insure_fee = false; //是否缴纳保证金
		$insure_fee    = 0;//保证金
		$is_agency     = 0; //是否为中介  //若为 0 或者null 表示未选择  若 是1表示中介 2表示直招

		$part_company_info = $service_partcompany->getCompanyPartInfo($this->_userid,"company_id,is_insure_fee,insure_fee,score,is_agency,good_count,total_count,"
				. "see_assessed_time,see_invite_time,see_offer_time,see_apply_time,see_job_recruiting_time,see_job_auditfail_time,reply_rate");

		if (!base_lib_BaseUtils::nullOrEmpty($part_company_info)) {
			$is_insure_fee = $part_company_info['is_insure_fee'] == 1 ? true : false;
			$insure_fee    = $part_company_info['insure_fee'];
			$is_agency     = $part_company_info['is_agency'];
		}
		
		$this->_aParams["insure_fee"]    = $insure_fee;
		$this->_aParams["is_agency"]     = $is_agency;
		$this->_aParams['is_insure_fee'] = $is_insure_fee;
		$this->_aParams['is_audit']      = $letter_info['is_audit'];

		$this->_aParams['last_amount']   = empty($last_point)?10:$last_point['last_amount'];

		/*====企业待完善基本资料===*/
		$companyNotUpdate = $this->__getCompanyNotUpdate($company);
		if (!base_lib_BaseUtils::nullOrEmpty($companyNotUpdate)) {
			$this->_aParams['companyNotUpdate'] = $companyNotUpdate;
		}
		
		/*====获得企业的客服顾问===*/
		
		//获取招聘顾问
        $companyStateService = new base_service_company_comstate();
		$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
		$hrManager    = $this->GetHRManager($companyState["net_heap_id"]);
        $phone_xml = SXML::load('../config/config.xml');
		if(!is_null($phone_xml)){
			$this->_aParams['HuiboPhone400']       = $phone_xml->HuiboPhone400;
		}
        
		$this->_aParams['default_qq']     = "2851501279";
		$this->_aParams['default_mobile'] = $this->_aParams['HuiboPhone400'];

        /*====最近10天报名回复率及其统计===*/
        $replay_rate = $part_company_info['reply_rate'];
        $this->_aParams['replay_rate'] = ($replay_rate == -1)?'100':number_format(floatval($replay_rate * 100), 2);

        //24h将要过期的待处理
        $this->_aParams['end_apply_num'] = $service_apply->getApplys(1,1,'a.job_id',$this->_userid,'','',$service_jobapplystatus->not_deal,1)->totalSize;
        //审核未通过的职位
        $fail_jobs = $service_job->getPartjobPageList(1,3,3,$this->_userid,true);
        $this->_aParams['fail_jobs'] = $fail_jobs->items;
        //职位数据处理
        $job_fields = "job_id,station,create_time,check_time,is_effect,check_state,status,effect_time,refresh_time,refresh_num,visit_num,area_id,fail_reason";
        $job = $service_job->getCompanyJobList($this->_userid,$job_fields)->items;
        //获取待处理和不合适的数量
        $apply_count = $service_apply->getApplyCountByCompany($this->_userid);
        $apply_count = base_lib_BaseUtils::array_key_assoc($apply_count,'job_id');
        //待面试数量
        $invite = $service_invite->getInviteByCompanyId($this->_userid,'count(invite_id) as invite_num,job_id',true)->items;
        $invite = base_lib_BaseUtils::array_key_assoc($invite,'job_id');
        //已录用
        $offer = $service_offer->getOffersByCompany($this->_userid,$service_joboffer->refuse,"count(offer_id) as offer_num,job_id",1,2000,null,true)->items;
        $offer = base_lib_BaseUtils::array_key_assoc($offer,'job_id');
        //获取置顶数据
        $top_job_ids = array();
        if($type == 1){
            $top_jobs    = $service_partjobtop->getByCompany($this->_userid,'job_id,end_time,top_type,top_day')->items;
            //var_dump($top_jobs);
            $_top_job_ids = base_lib_BaseUtils::getProperty($top_jobs,'job_id');
            $_top_jobs   = array();
            foreach($top_jobs as $k=>$v){
                $top_day = round((strtotime($v['end_time'])-strtotime(date("Y-m-d H:i:s")))/86400);
                if(in_array($v['job_id'],$top_job_ids)){
                    //$job_temp = base_lib_BaseUtils::array_key_assoc($_top_jobs,'job_id');
                    $top_type = '';
                    if($v['top_type'] == 1){
                        $top_type = "全站置顶";
                    }elseif($v['top_type'] == 2){
                        $top_type = "搜索置顶";
                    }
                    foreach($_top_jobs as $key=>$val){
                        if($val['job_id'] == $v['job_id'] &&$top_type){
                            $temp_day = explode(',',$_top_jobs[$key]['_top_type'][0]['top_day'].','.$top_day);
                            $temp_type = explode(',',$_top_jobs[$key]['_top_type'][0]['top_type'].','.$top_type);
                            $_top_jobs[$key]['_top_type'][0]['top_type'] = $temp_type[0];
                            $_top_jobs[$key]['_top_type'][0]['top_day']  = $temp_day[0];

                            $_top_jobs[$key]['_top_type'][1]['top_type'] = $temp_type[1];
                            $_top_jobs[$key]['_top_type'][1]['top_day']  = $temp_day[1];
                        }
                    }
                }else{
                    $_top_jobs[$k]['job_id'] = $v['job_id'];
                    array_push($top_job_ids,$v['job_id']);
                    if($v['top_type'] == 1){
                        $_top_jobs[$k]['_top_type'][0]['top_type'] = '全站置顶';
                        $_top_jobs[$k]['_top_type'][0]['top_day']  =  $top_day;
                    }elseif($v['top_type'] == 2){
                        $_top_jobs[$k]['_top_type'][0]['top_type'] = '搜索置顶';
                        $_top_jobs[$k]['_top_type'][0]['top_day']  =  $top_day;
                    }
                }

            }
            $_top_jobs    = base_lib_BaseUtils::array_key_assoc($_top_jobs,'job_id');
        }

        //待审核的职位
        $check_job_arr = array();
        //招聘中和暂停招聘
        $pub_job_arr = array();
        //审核失败
        $notpass_job_arr = array();
        //已过期
        $end_job_arr = array();
        //所有待处理的简历数量
        $all_not_deal = 0;
        foreach($job as $key=>$val){
            $notfit_num = $apply_count[$val['job_id']]['notfit_num']+$apply_count[$val['job_id']]['autorefused_num'];
            //不合适数量
            $job[$key]['not_fit_count']  = empty($notfit_num)?0:$notfit_num;
            //待处理
            $job[$key]['not_deal_count'] = empty($apply_count[$val['job_id']]['notreply_num'])?0:$apply_count[$val['job_id']]['notreply_num'];
            $all_not_deal = $all_not_deal + $job[$key]['not_deal_count'];
            if(date("Y-m-d",strtotime($val['refresh_time'])) == date("Y-m-d")){
                $job[$key]['refresh_times']  = $val['refresh_num'];
            }else{
                $job[$key]['refresh_times']  = 3;
            }
            //待面试
            $job[$key]['invite_num']     = empty($invite[$val['job_id']]['invite_num'])?0:$invite[$val['job_id']]['invite_num'];
            //已录用
            $job[$key]['offer_num']      = empty($offer[$val['job_id']]['offer_num'])?0:$offer[$val['job_id']]['offer_num'];
            //是否有新的待处理
            $job[$key]['has_new_deal']   = empty($has_not_deal[$val['job_id']]['notreply_num'])?false:true;
            //是否有新的待面试
            $job[$key]['has_new_invite'] = empty($has_not_invite[$val['job_id']]['invite_num'])?false:true;
            //是否已经刷新
            if(date("Y-m-d",strtotime($val['refresh_time'])) == date("Y-m-d")){
                $job[$key]['is_refresh'] = true;
            }else{
                $job[$key]['is_refresh'] = false;
            }
            //置顶处理
            $job[$key]['is_top'] = false;
            if($type == 1){
                $job[$key]['is_top'] = $service_partjobtop->checkJobTop($val['job_id'])['status'] ?false:true;
            }
            if($top_job_ids){
                if(in_array($val['job_id'],$_top_job_ids)){
                    $job[$key]['is_toped'] = true;
                    $job[$key]['top_type'] = $_top_jobs[$val['job_id']]['_top_type'];
                }
            }
            $job[$key]["end_time"]    = $val['effect_time'];
            $job[$key]["effect_time"] = intval((strtotime($val['effect_time'])-strtotime(date("Y-m-d 23:59:59")))/86400);

            $job[$key]["job_flag"]    = base_lib_Rewrite::getFlag('partjob',$val['job_id']);
            $job[$key]['area_name']   = $service_area->getAreaAndParentName($val['area_id']);
            $job[$key]['show_btn']   = false;
            if($val["is_effect"]==1){
                if($val['check_state'] == $service_checkstatus->checking){
                    $job[$key]['status_str'] = "审核中";
                    $job[$key]['class_str']  = "blue";
                    array_push($check_job_arr,$job[$key]);
                }
                if(($val['status'] == $service_jobstatus->use || $val['status'] == $service_jobstatus->pause )&&$val['effect_time'] >= date("Y-m-d H:i:s")){
                    if($val['status'] == $service_jobstatus->use){
                        $job[$key]['status_str'] = "招聘中";
                        $job[$key]['show_btn']   = true;
                        $job[$key]['class_str']  = "green";
                    }else{
                        $job[$key]['status_str'] = "暂停中";
                        $job[$key]['show_btn']   = true;
                        $job[$key]['class_str']  = "green";
                    }

                    array_push($pub_job_arr,$job[$key]);
                }
                if($val['check_state'] == $service_checkstatus->notpass){
                    $job[$key]['status_str'] = "已拒绝";
                    $job[$key]['class_str']  = "red";
                    array_push($notpass_job_arr,$job[$key]);
                }
                if(($val['status'] == $service_jobstatus->stop_use  || ($val['status'] == $service_jobstatus->use ||  $val['status'] == $service_jobstatus->pause)&& $val['effect_time']< date("Y-m-d H:i:s") && !empty($val['effect_time']))&&!in_array($job[$key],$notpass_job_arr)){
                    $job[$key]['status_str'] = "已过期";
                    $job[$key]['class_str']  = "gray";
                    array_push($end_job_arr,$job[$key]);
                }
            }

        }

        $this->_aParams['all_not_deal'] = $all_not_deal;
		/*===评价====*/
        $assessment_service = new base_service_part_company_assessment();
		//积分
		$partCompanyScore = 100;
		if (!is_null($xml)) {
			$partCompanyScore    = $xml->partCompanyScore;
		}

        //获取首页的推广金，置顶点等资料
        $companyresources = $company_resources->getCompanyServiceSource();

        $this->_aParams['companyresources'] = $companyresources;

		/*====好评率和积分===*/
		$assess_count = $assessment_service->getCount($this->_userid);//获取公司的平均数
		$good_count   = 0;
		$total_count  = 0;
		if (!base_lib_BaseUtils::nullOrEmpty($assess_count)) {
			$good_count       = $assess_count['good_count'];
			$total_count      = $assess_count['total_count'];
			$partCompanyScore = $partCompanyScore+$assess_count["score"];
		}
		$this->_aParams["partCompanyScore"] = $partCompanyScore;

		$good_assessed_rate = $total_count > 0 ? number_format(floatval($good_count/$total_count*100),2) : 0;
		$this->_aParams['good_assessed_rate'] = $good_assessed_rate;

		$service_score = new base_service_common_part_companyscoretolevel();
		$score_level = $service_score->getLevel($partCompanyScore);
		$this->_aParams['score_level'] = $score_level;

        $this->_aParams["company_id"]        = $this->_userid;
        $this->_aParams['count_on_job']      = count($pub_job_arr) ? count($pub_job_arr):0;
        $this->_aParams['count_check_job']   = count($check_job_arr) ? count($check_job_arr):0;
        $this->_aParams['count_notpass_job'] = count($notpass_job_arr) ? count($notpass_job_arr):0;
        $this->_aParams['count_end_job']     = count($end_job_arr) ? count($end_job_arr):0;
        //是否关注了兼职公众号
        $jianzhiWeixinService = new SJianzhiWeixin();
        $this->_aParams["show_app_bing_weixin"] = $jianzhiWeixinService->isAttentionJZwxByperson_id($this->_userid,'c');
        $ticket = $jianzhiWeixinService->getTDcodeWithCompanyid($this->_userid,600);
        $this->_aParams["ticket"]       = $ticket->ticket;
        $this->_aParams["mjianzhi_url"] = base_lib_Constant::MOBILE_JIANZHI_NO_HTTP;

        //todo 调试
//        $this->_aParams["show_app_bing_weixin"] = 0;

        //企业资质认证
        $letter_result = $this->checkCompanyLetter($this->_userid);
        $this->_aParams['letter_info'] = $letter_result;

        switch($type){
            case 1:
                $this->_aParams['job']     = $pub_job_arr;
                return $this->render("./part/indexon.html",$this->_aParams);
                break;
            case 2:
                $this->_aParams['job'] = $check_job_arr;
                return $this->render("./part/indexchecking.html",$this->_aParams);
                break;
            case 3:

                $this->_aParams['job'] =  $this->arraySort($notpass_job_arr,"check_time",SORT_DESC);
                return $this->render("./part/indexrefuse.html",$this->_aParams);
                break;
            case 4:
                $this->_aParams['job'] = $this->arraySort($end_job_arr,"effect_time",SORT_DESC);
                return $this->render("./part/indexend.html",$this->_aParams);
                break;
            default:
                $this->_aParams['job'] = $pub_job_arr;
                break;
        }

		//return $this->render("./part/index.html",$this->_aParams);
	}

	//购买咨询-》对应销售
    public function pagebuypointask($inPath)
    {
        $pathdata            = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $companyStateService = new base_service_company_comstate();
        $companyState        = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
        $hrManager           = $this->GetHRManager($companyState["net_heap_id"]);
        $phone_xml           = SXML::load('../config/config.xml');
        if (!is_null($phone_xml)) {
            $this->_aParams['HuiboPhone400'] = $phone_xml->HuiboPhone400;
        }

        $xml                          = SXML::load('../config/company/company.xml');
        $this->_aParams['default_qq'] = $xml->PartDeaultQQ;
        $this->_aParams['hrManager']  = $hrManager;

        echo $this->render('./part/pay/askdetail.html', $this->_aParams);

    }

    public function pageGetResumePoint($inPath)
    {
        $pathdata             = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $service_pointaccount = new base_service_part_company_resumepoint_pointaccount();
        $last_point           = $service_pointaccount->getAccountByCompany($this->_userid, 'last_amount');
        exit(json_encode(['status' => true, 'last_point' => $last_point['last_amount']]));
    }

    public function pagepubNumLimit($inPath)
    {
        $pathdata    = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $type        = base_lib_BaseUtils::getStr($pathdata['type'], 'int', 1);
        $job_id      = base_lib_BaseUtils::getStr($pathdata['job_id'], 'int', 0);
        $service_job = new base_service_part_company_job();
        $can_pub  = $service_job->PubJobNumLimit($type, $this->_userid, $job_id);
        exit(json_encode(['status' => true, 'can_pub' => $can_pub]));
    }


    public function pagepayjoblist($inPath)
    {
        $pathdata        = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $service_pay_job = new base_service_part_company_payjobnum();
        $list            = $service_pay_job->getAllBycompany($this->_userid, '*');
        foreach ($list as $k => &$v) {
            $v['status'] = $v['end_time'] > date('Y-m-h H:i:s') ? '有效' : '过期';
            $v['create_time'] = date('Y-m-d',strtotime($v['create_time']));
        }
        $this->_aParams['list'] = $list;
        echo $this->render('./part/payjoblist.html', $this->_aParams);
    }


    function arraySort($array, $keys, $sort = 'SORT_DESC') {
        $keysValue = [];
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }
    /**
     * 刷新职位
     */
    public function pageRefreshJob($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $job_flag = base_lib_BaseUtils::getStr($pathdata['job_flag'],'string',0);
        $job_id   = base_lib_Rewrite::getId("partjob",$job_flag);
        if(empty($job_id)){
            echo json_encode(["status"=>false,"msg"=>"参数错误"]);
            return;
        }
        $service_partjob        = new base_service_part_company_job();
        $jobdata = $service_partjob->getJob($job_id, "company_id,end_time,check_state");
        if (empty($jobdata) || $jobdata['company_id'] != $this->_userid) {
            echo json_encode(['status'=>false,'msg'=>'职位不存在']);
            return;
        }
        $job = $service_partjob->getJob($job_id,'refresh_time,refresh_num');
        if(date("Y-m-d",strtotime($job['refresh_time'])) == date("Y-m-d") && $job['refresh_num'] == 0){
            echo json_encode(["status"=>false,"msg"=>"今日刷新次数已用完"]);
            return;
        }

        //记录操作日志
        $jobaction_service = new base_service_part_statistics_jobactionlog();
        $jobaction_service->addVisitLog([$job_id],$this->_userid,2);

        if(date("Y-m-d",strtotime($job['refresh_time'])) != date("Y-m-d")){
            $service_partjob->refreshJobSingle($job_id);
            $service_partjob->modifyJob($job_id,['refresh_num'=>2]);
            echo json_encode(["status"=>true,"msg"=>"职位刷新成功，剩余刷新次数：2"]);
            return;
        }
        if(date("Y-m-d",strtotime($job['refresh_time'])) == date("Y-m-d") && $job['refresh_num'] != 0){
            $service_partjob->refreshJobSingle($job_id);
            $service_partjob->modifyJob($job_id,['refresh_num'=>$job['refresh_num']-1]);
            echo json_encode(["status"=>true,"msg"=>"职位刷新成功，剩余刷新次数：".($job['refresh_num']-1)]);
            return;
        }

        echo json_encode(["status"=>false,"msg"=>"刷新失败"]);
        return;
    }

    /**
     * 暂停职位
     */
    public function pageSuspendJob($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $job_flag = base_lib_BaseUtils::getStr($pathdata['job_flag'],'string',0);
        $job_id   = base_lib_Rewrite::getId("partjob",$job_flag);
        if(empty($job_id)){
            echo json_encode(["status"=>false,"msg"=>"参数错误"]);
            return;
        }
        $service_partjob        = new base_service_part_company_job();
        $jobdata = $service_partjob->getJob($job_id, "company_id,end_time,check_state");
        if (empty($jobdata) || $jobdata['company_id'] != $this->_userid) {
            echo json_encode(['status'=>false,'msg'=>'职位不存在']);
            return;
        }

        $job = $service_partjob->getJob($job_id,'status,effect_time');
        if($job['effect_time'] < date("Y-m-d H:i:s")){
            echo json_encode(["status"=>false,"msg"=>"职位已过期"]);
            return;
        }

        $service_jobstatus   = new base_service_common_part_partjobstatus();
        if($job['status'] == $service_jobstatus->pause){
            $data['status'] = $service_jobstatus->use;
        }elseif($job['status'] == $service_jobstatus->use){
            $data['status'] = $service_jobstatus->pause;
        }
        //$data['update_time'] = date("Y-m-d H:i:s");
        $service_partjob = new base_service_part_company_job();
        $result = $service_partjob->modifyJob($job_id,$data);

        $str        = $job['status'] == $service_jobstatus->pause?"暂停":"开启";
        $status_str = $job['status'] == $service_jobstatus->pause?"招聘中":"暂停招聘";
        if($result){
            echo json_encode(["status"=>true,"msg"=>"设置成功","str"=>$str,"statusStr"=>$status_str]);
            return;
        }
        echo json_encode(["status"=>false,"msg"=>"设置失败"]);
        return;
    }

    /**
     * 逻辑删除职位
     */
    public function pageDeleteJob($inPath){
        $service_partjob        = new base_service_part_company_job();

        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $jobid = base_lib_BaseUtils::getStr($path_data['job_id'],'int',0);
        if ($jobid === 0) {
            echo json_encode(['status'=>false,'msg'=>'职位不存在']);
            return;
        }

        $jobdata = $service_partjob->getJob($jobid, "company_id,end_time,check_state");
        if (empty($jobdata) || $jobdata['company_id'] != $this->_userid) {
            echo json_encode(['status'=>false,'msg'=>'职位不存在']);
            return;
        }

        $result = $service_partjob->deleteJobLogic($jobid);

        if($result){
            $this->update_crm_partcompany_syncinfo($jobdata["company_id"]);
            echo json_encode(['status'=>true,'msg'=>'删除成功']);return;
        }else{
            echo json_encode(['status'=>false,'msg'=>'删除失败']);return;
        }
    }
    /**
     *群发短信入口
     */
    public function pageSendSms($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $job_flag = base_lib_BaseUtils::getStr($pathdata['job_flag'],'string','');
        $is_ajax  = base_lib_BaseUtils::getStr($pathdata['is_ajax'],'string','');
        $this->_aParams['job_flag']      = $job_flag;

        $service_joboffered     = new base_service_part_company_joboffered();
        $service_invite         = new base_service_part_company_invite();

        $job_id = base_lib_Rewrite::getId("partjob",$job_flag);
        //$job_id = 87;
        if(empty($job_id)){
             return $this->render("part/partsms.html", $this->_aParams);
        }
        $service_smsrecord = new base_service_part_job_smsrecord();
        $record = $service_smsrecord->getCount($job_id,$this->_userid,'send_time,sms_used_count',2)[0];
        if(date("Y-m-d",strtotime($record['send_time'])) != date("Y-m-d") || empty($record)){
            $job_sms_count = 100; //当日剩余条数
        }else{
            $job_sms_count = 100-$record['sms_used_count'];
        }
        //已录用
        $fields         = 'offer_id,job_id,person_id,apply_id';
        $offered        = $service_joboffered->getOffersByCompany($this->_userid, '', $fields, 1, 100,$job_id)->items;
        $offered_person = base_lib_BaseUtils::assoc_unique($offered,'person_id');
        //待面试
        $fields         = 'invite_id,job_id,person_id,apply_id';
        $invited        = $service_invite->getInvitesByCompany($this->_userid, '', $fields, 1, 100,$job_id)->items;
        $invited_person = base_lib_BaseUtils::assoc_unique($invited,'person_id');
        foreach($invited_person as $k=>$v){
            if(in_array($v['person_id'],base_lib_BaseUtils::getProperty($offered_person,'person_id'))){
                unset($invited_person[$k]);
            }
        }
        $data = array_merge($offered_person,$invited_person);
        $can_total = count($data);
        $person_ids = base_lib_BaseUtils::getProperty($data,'person_id');
        $person_ids = array_unique($person_ids);
        $persons_sms = $service_smsrecord->getCount($person_ids,$this->_userid,'id,send_time,sms_used_count',1);
        $persons_sms = base_lib_BaseUtils::array_key_assoc($persons_sms,'id');
        if(!empty($persons_sms)){
            foreach($data as $key=>$val){
                if(!empty($persons_sms[$val['person_id']])&&$persons_sms[$val['person_id']]['sms_used_count']>1&&date("Y-m-d",strtotime($persons_sms[$val['person_id']]['send_time']))==date("Y-m-d")){
                    unset($data[$key]);
                }
            }
        }
        if($is_ajax){
            echo json_encode(['total'=>count($data),'can_total'=>$can_total]);
            return;
        }

        if(!empty($data)){
            $this->_packageData($data);
        }
        $this->_aParams['job']           = $data;
        $this->_aParams['job_sms_count'] = $job_sms_count;
        return $this->render("part/partsms.html", $this->_aParams);
    }

    /**
     *执行群发
     */
    public function pageSendSmsDo($inPath){
        $pathdata   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $job_flag   = base_lib_BaseUtils::getStr($pathdata['job_flag'],'string','');
        $person_ids = base_lib_BaseUtils::getStr($pathdata['person_ids'],'string','');
        $content    = base_lib_BaseUtils::getStr($pathdata['content'],'string','');

        if(empty($job_flag)){
            echo json_encode(['status'=>false,"msg"=>"参数错误"]);
            return;
        }

        $job_id = base_lib_Rewrite::getId('partjob',$job_flag);
        if(empty($person_ids)){
            echo json_encode(['status'=>false,"msg"=>"请选择需发送人员"]);
            return;
        }
        $service_person    = new base_service_person_person();
        $service_smsrecord = new base_service_part_job_smsrecord();

        $person_ids     = explode(",",$person_ids);
        if(count($person_ids)>20){
            echo json_encode(['status'=>false,"msg"=>"每次最多发送20条"]);
            return;
        }
        $job_sms_count  = $service_smsrecord->getCount($job_id,$this->_userid,'record_id,id,send_time,sms_used_count',2)[0];

        if(!empty($job_sms_count) && date("Y-m-d",strtotime($job_sms_count['send_time'])) == date("Y-m-d") && (100-$job_sms_count['sms_used_count']) <= count($person_ids)){
            echo json_encode(['status'=>false,"msg"=>"当前条数不能超过今日剩余条数"]);
            return;
        }
        $persons_sms    = $service_smsrecord->getCount($person_ids,$this->_userid,'record_id,id,send_time,sms_used_count',1);
        $sms_person_ids = base_lib_BaseUtils::getProperty($persons_sms,'id');
        $persons_sms    = base_lib_BaseUtils::array_key_assoc($persons_sms,'id');
        $persons        = $service_person->GetPersonListByIDs($person_ids, 'person_id,mobile_phone')->items;
        $send_total     = 0;
        foreach($persons as $k=>$v){
            if(in_array($v['person_id'],$sms_person_ids)){
                if(date("Y-m-d",strtotime($persons_sms[$v['person_id']]['send_time'])) == date("Y-m-d") && $persons_sms[$v['person_id']]['sms_used_count']<2 || date("Y-m-d",strtotime($persons_sms[$v['person_id']]['send_time'])) != date("Y-m-d")){
                    if($v['mobile_phone']){
                        $data['send_time']      = date("Y-m-d H:i:s");
                        $data['sms_used_count'] = date("Y-m-d",strtotime($persons_sms[$v['person_id']]['send_time'])) == date("Y-m-d")?$persons_sms[$v['person_id']]['sms_used_count']+1:1;
                        $service_smsrecord->modRecord($persons_sms[$v['person_id']]['record_id'],$data);
                        $result = base_lib_SMS::send($v['mobile_phone'],$content);
                    }
                }
            }else{
                $items['company_id']     = $this->_userid;
                $items['id']             = $v['person_id'];
                $items['send_time']      = date("Y-m-d H:i:s");
                $items['create_time']    = date("Y-m-d H:i:s");
                $items['sms_used_count'] = 1;
                $items['id_type']        = 1;
                $service_smsrecord->addRecord($items);
                base_lib_SMS::send($v['mobile_phone'],$content);
            }
            $send_total +=1;
        }
        if(empty($job_sms_count['record_id'])){
            $job_items['sms_used_count'] = $send_total;
            $job_items['send_time']      = date("Y-m-d H:i:s");
            $job_items['create_time']    = date("Y-m-d H:i:s");
            $job_items['id_type']        = 2;
            $job_items['id']             = $job_id;
            $job_items['company_id']     = $this->_userid;
            $res = $service_smsrecord->addRecord($job_items);
        }else{
            $mod['sms_used_count'] = $job_sms_count['sms_used_count']+$send_total;
            $mod['send_time']      = date("Y-m-d H:i:s");
            $res = $service_smsrecord->modRecord($job_sms_count['record_id'],$mod);
        }
        if($res){
            echo json_encode(['status'=>true,"msg"=>"成功发送{$send_total}条"]);
            return;
        }
        echo json_encode(['status'=>false,"msg"=>"发送失败"]);
        return;
    }



    /**
     * @Desc 获取个人，职位等附加信息
     * @param int $apply_list 兼职报名列表
     * @param bool $special 待处理的报名时间有特殊处理
     * @param enum $apply_list 附加个人和职位等信息的报名列表
     */
    private function _packageData(&$apply_list, $special=false) {
        if (base_lib_BaseUtils::nullOrEmpty($apply_list))
            return $apply_list;

        $person_ids = base_lib_BaseUtils::getProperty($apply_list, 'person_id');
        $job_ids    = base_lib_BaseUtils::getProperty($apply_list, 'job_id');

        // $service_area       = new base_service_common_area();
        $service_person     = new base_service_person_person();
        $service_partjob    = new base_service_part_company_job();
        $service_partresume = new base_service_part_person_resume();
        $jobsort_service           = new base_service_common_part_jobsort();
        $resume_jobsortexp_service = new base_service_part_person_resumejobsortexp();
        //  $degree_service     = new base_service_common_degree();
        $persons = $service_person->GetPersonListByIDs(implode(',',array_unique($person_ids)), 'person_id,user_name,sex,photo_open,has_big_photo,photo,big_photo,birthday2,mobile_phone')->items;
        $persons = base_lib_BaseUtils::array_key_assoc($persons, 'person_id');

        $jobs = $service_partjob->getJobs(implode(',',array_unique($job_ids)), 'job_id,need_invite');
        $jobs = base_lib_BaseUtils::array_key_assoc($jobs, 'job_id');

        $resumes = $service_partresume->getResumeByPersonIds($person_ids, 'person_id,resume_id,edu_start_year,edu_end_year,degree', true)->items;
        $resumes = base_lib_BaseUtils::array_key_assoc($resumes, 'person_id');

        //获取职位链接
//        if (!empty($jobs)) {
//            foreach ($jobs as $k => $job) {
//                $jobs[$k]['joblink'] = base_lib_Constant::MOBILE_URL . '/partjob/PartJobDetailv2/job_flag-' . base_lib_Rewrite::getFlag('partjob',$job['job_id']);
//            }
//        }
        $can_see_mobile = $this->canDo("see_part_resume_mobile");
        //个人现居住地址
        if (!empty($persons)) {
            foreach($persons as $k => $person){
                // $area = $service_area->getTopAreaByAreaID($person['cur_area_id']);
                // $persons[$k]['address'] = implode('', base_lib_BaseUtils::getProperty(array_reverse($area), 'area_name'));
                if(!$can_see_mobile){
                    $persons[$k]['mobile_phone'] = preg_replace('#(\d{3})[a-z0-9-]{2,10}(\d{3})#', '${1}****${2}', $person['mobile_phone']);;
                }
            }
        }
        foreach($apply_list as $k => $apply) {
            //报名时间
            $apply_list[$k]['refuse_time']    = empty($apply['refuse_time']) ? "" : date("Y-m-d", strtotime($apply['refuse_time']));
            $apply_list[$k]['create_time']    = base_lib_TimeUtil::to_friend_time($apply['create_time']);
            $apply_list[$k]['attention_time'] = base_lib_TimeUtil::to_friend_time($apply['attention_time']);
            if(!empty($resumes[$apply['person_id']]['edu_end_year'])){
                $apply_list[$k]['in_school'] = $resumes[$apply['person_id']]['edu_end_year']>date("Y-m-d 00:00:00")? "学生":"已毕业";
            }else{
                $apply_list[$k]['in_school'] = "";
            }
            $apply_list[$k]['age'] =  base_lib_TimeUtil::ceil_diff_year($persons[$apply['person_id']]['birthday2'])."岁";
            $apply_list[$k]['photo_url'] =  !empty($persons[$apply['person_id']]['big_photo'])?base_lib_Constant::YUN_ASSETS_URL_NO_HTTP."{$persons[$apply['person_id']]['big_photo']}":"";
            $apply_list[$k] = empty($persons[$apply['person_id']]) ? $apply_list[$k] : array_merge($apply_list[$k], $persons[$apply['person_id']]);
            $apply_list[$k] = empty($jobs[$apply['job_id']]) ? $apply_list[$k] : array_merge($apply_list[$k], $jobs[$apply['job_id']]);
            $apply_list[$k] = empty($resumes[$apply['person_id']]) ? $apply_list[$k] : array_merge($apply_list[$k], $resumes[$apply['person_id']]);
            $job_condition                  = array("resume_id" => $resumes[ $apply['person_id'] ]['resume_id']);
            $jobsortexp_resume              = $resume_jobsortexp_service->select($job_condition, 'jobsort_id')->items;
            $apply_list[ $k ]['jobsortexp'] = $jobsort_service->getJobsorts(base_lib_BaseUtils::getProperty($jobsortexp_resume, 'jobsort_id'));
        }
    }
    /*
 *@desc 更新需要同步到后台的兼职单位信息
 **/
    private  function update_crm_partcompany_syncinfo($company_id){
        //
        $service_partjob=new base_service_part_company_job();
        $result=$service_partjob->get_JobEndTime_And_ValidJobNums($company_id);
        if($result!==false){
            $service_partcompany= new base_service_part_company_partcompany();
            $service_partcompany->editPartCompany($company_id,$result);
        }
    }
    /**
     * 是否关注微信
     * @param $inPath
     */
	public function pageIsBindWx($inPath){
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $jianzhiWeixinService = new SJianzhiWeixin();
        $show_app_bing_weixin = $jianzhiWeixinService->isAttentionJZwxByperson_id($this->_userid,'c');
        $ticket = $jianzhiWeixinService->getTDcodeWithCompanyid($this->_userid,600);
        $actach_twc = new base_service_part_catch_Twcode();
        $actach_twc->setVerifyCode(false,$this->_userid);
        exit(json_encode(['status'=>true,'is_show'=>$show_app_bing_weixin,'ticket'=>$ticket->ticket]));
    }

	/*
     * 获得企业资料中未填的重要项，在首页中引导出来
     */
    private function __getCompanyNotUpdate($company){
        $word_array = array();
        if(!base_lib_BaseUtils::nullOrEmpty($company)){
			 if(base_lib_BaseUtils::nullOrEmpty($company['company_name'])){
                 $word_array[] = "公司名称";
            }
            if(base_lib_BaseUtils::nullOrEmpty($company['company_shortname'])){
                 $word_array[] = "公司简称";
            }
//            if(base_lib_BaseUtils::nullOrEmpty($company['property_id'])){
//                $word_array[] = "公司性质";
//            }
			if(base_lib_BaseUtils::nullOrEmpty($company['calling_ids'])){
                $word_array[] = "公司行业";
            }
            if(base_lib_BaseUtils::nullOrEmpty($company['address'])){
                $word_array[] = "详细地址";
            }
            if(base_lib_BaseUtils::nullOrEmpty($company['area_id'])){
                $word_array[] = "公司地址";
            }
            if(base_lib_BaseUtils::nullOrEmpty($company['linkman'])){
                $word_array[] = "联系人";
            }
            if(base_lib_BaseUtils::nullOrEmpty($company['link_tel']) && base_lib_BaseUtils::nullOrEmpty($company['link_mobile'])){ //企业座机和手机必填一个
                $word_array[] = "联系方式";
            }
//                if(base_lib_BaseUtils::nullOrEmpty($company['company_logo_path'])){
//                    $word_array[] = "公司LOGO";
//                }
            if(base_lib_BaseUtils::nullOrEmpty($company['info'])){
                $word_array[] = "公司介绍";
            }
        }
        return $word_array;
    }
	
	
	/**
	 * @desc 刷新职位
	 */
	
	public function pageRefureshAllJobs($inPath){
		//获得需要刷新的 在招职位，不管职位审核或者未审核 
		if(base_lib_BaseUtils::nullOrEmpty($this->_userid)){
			echo json_encode(array("error"=>"您的登录已过期，请重新登录"));exit;
		}
		$service_partjob = new base_service_part_company_job();
		$service_jobcheckstatus = new base_service_common_part_jobcheckstatus();
		$service_comrefrech = new base_service_part_company_refresh();
		$status = null;//待定 职位的状态
		$items = "job_id,refresh_time";
		$jobstatus = new base_service_common_part_partjobstatus();
		$status = $jobstatus->pub;
		$joblist = $service_partjob->getJobList($this->_userid, $items, $status, $service_jobcheckstatus->pub,true)->items;
		if(base_lib_BaseUtils::nullOrEmpty($joblist)){
			echo json_encode(array("error"=>"您还没有发布过职位哦，不能刷新"));exit;
		}
		$job_ids = base_lib_BaseUtils::getProperty($joblist,"job_id");

		//获得企业上次刷新时间
		$last_refresh = $service_comrefrech->getLastCompanyRefreshTime($this->_userid,"company_id,refresh_time");
		if(!base_lib_BaseUtils::nullOrEmpty($last_refresh)){
			$last_refresh_time = $last_refresh['refresh_time'];
			if(date("Y-m-d") == date("Y-m-d",strtotime($last_refresh_time))){
				echo json_encode(array("error"=>"今天已经刷新过了，明天再来吧"));exit;
			}
		}
		
		//刷新职位
		$result = $service_partjob->refreshJob($this->_userid,$job_ids);
		if($result !==false){
		    //记录操作日志
            $jobaction_service = new base_service_part_statistics_jobactionlog();
            $res = $jobaction_service->addVisitLog($job_ids,$this->_userid,2);

			//刷新成功
			echo json_encode(array("success"=>"刷新成功"));exit;
		}else{
			echo json_encode(array("error"=>"刷新失败"));exit;
		}
	}
	
	/**
	 * @desc 设置兼职企业类型 直招或者兼职
	 */
	public function pageSetCompanyAgency($inPath){
		//判断该企业是否已经设置 ，如果已设置则不能再设置
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$callBackPage = base_lib_BaseUtils::getStr($pathdata['callBackPage'],"page","");
		$service_partcompany     = new base_service_part_company_partcompany();
		//获取当前企业的兼职信息
		$part_comapny = $service_partcompany->getCompanyPartInfo($this->_userid,"company_id,is_insure_fee,insure_fee,score,is_agency");
		if(!base_lib_BaseUtils::nullOrEmpty($part_comapny)){
			if($part_comapny['is_agency'] ==2 || $part_comapny['is_agency']==1){
				$this->_aParams['has_set_agency'] = true;//已经设置了企业兼职类型 不能再设置
			}
		}
		return $this->render("./part/setcompanyagency.html",$this->_aParams);
	}
	
	/**
	 * @desc 设置兼职企业类型 直招或者兼职
	 */
	public function pageSetCompanyAgencyDo($inPath){
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$agency = base_lib_BaseUtils::getStr($pathdata['agency'],"int",0);
		if($agency!=1 && $agency!=2){
			echo json_encode(array("error"=>"请选择您的招聘类型"));exit;
		}
		if(base_lib_BaseUtils::nullOrEmpty($this->_userid)){
			echo json_encode(array("error"=>"您的登录已过期，请重新登录"));exit;
		}
		
		$service_partcompany = new base_service_part_company_partcompany();
		//获取当前企业的兼职信息
		$part_comapny = $service_partcompany->getCompanyPartInfo($this->_userid,"company_id,is_insure_fee,insure_fee,score,is_agency");
		if(base_lib_BaseUtils::nullOrEmpty($part_comapny)){
			//添加企业信息
			$items = array();
			$items['company_id'] = $this->_userid;
			$items['is_agency'] = $agency;
			$error = "";
			$add_result = $service_partcompany->addPartCompany($items, $error);
			if(!empty($error)){
				echo json_encode(array("error"=>$error));exit;
			}
			if($add_result !==false){
				echo json_encode(array("success"=>"设置成功"));exit;
			}else{
				echo json_encode(array("error"=>"设置失败"));exit;
			}
		}
		//如果已经有企业兼职信息 则修改
		if($part_comapny['is_agency'] ==2 || $part_comapny['is_agency']==1){
			echo json_encode(array("error"=>"您已经设置招聘类型，不能再设置"));exit;
		}
		$update_items = array();
		$update_items['is_agency'] = $agency;
		$update_result = $service_partcompany->editPartCompany($this->_userid,$update_items);
		if($update_result !==false){
			//修改成功
			echo json_encode(array("success"=>"设置成功"));exit;
		}else{
			//修改失败
			echo json_encode(array("error"=>"设置失败"));exit;
		}
	}
	
	private function GetHRManager($heap_id){
		$companyHeapService = new base_service_company_netheap();
		$companyHeap = $companyHeapService->GetNetHeapByID($heap_id, "own_man");
		$userInfor = null;
		if(is_null($companyHeap) || !isset($companyHeap["own_man"])){
			return $userInfor;
		}
		$userService=new base_service_crm_user();
		$userInfor = $userService->GetUsers($companyHeap['own_man'], "user_name,head_photo_url,tel,mobile,qq");
		return $userInfor;
	}

	// 获取客服人员
	 private function GetCustomerService($companyid) {
	 	$customerbelongService = new base_service_crm_netcompanycustomerbelong();
	 	$customerbelong =  $customerbelongService->getCustomerbelongById($companyid, 'net_heap_id');
	 	if(empty($customerbelong)) {
	 		return null;		
	 	}
	 	$customerheapservice = new base_service_crm_netcustomerheap();
	 	$customerheap = $customerheapservice->getCustomerheapById($customerbelong['net_heap_id'], 'own_man');
	 	if(empty($customerheap)) {
	 		return null;
	 	}
	 	$userService=new base_service_crm_user();
	 	$userInfor = $userService->GetUsers($customerheap['own_man'], "user_name,head_photo_url,tel,mobile,qq");
	 	return $userInfor;	

	 }
	
	/**
	 *@desc 保证金详情 
	 */
	public function pageBackInsure($inPath){
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		//获得企业信息
        $companyService = new base_service_company_company(); 
        $company = $companyService->getCompany($this->_userid, true, 'company_id,company_name');
		$this->_aParams["company"] = $company;
		
		/*====获得兼职企业信息===*/
		$is_insure_fee = false; //是否缴纳保证金
		$insure_fee = 0;//保证金
		$service_partcompany = new base_service_part_company_partcompany();
		$part_company_info = $service_partcompany->getCompanyPartInfo($this->_userid,"company_id,is_insure_fee,insure_fee,insure_time,score,is_agency,good_count,total_count,"
				. "see_assessed_time,see_invite_time,see_offer_time,see_apply_time,see_job_recruiting_time,see_job_auditfail_time");
		if(!base_lib_BaseUtils::nullOrEmpty($part_company_info)){
			$is_insure_fee = $part_company_info['is_insure_fee']==1 ? true : false;
			//$partCompanyScore = $part_company_info['score'];
			$insure_fee = $part_company_info['insure_fee'];
		}
		$this->_aParams["insure_fee"] = $insure_fee;
		$this->_aParams['is_insure_fee'] = $is_insure_fee;
		$this->_aParams["insure_time"] = $part_company_info["insure_time"];
		
		$this->_aParams['default_qq'] = "2851501279";
		$this->_aParams['default_mobile'] = "400-1010-970";
		$this->_aParams["title"] = "退回保证金";
		return $this->render("part/backinsure.html",$this->_aParams);
	}

    /**
     * 兼职消费记录
     * @param $inPath
     */
	public function pagePartlist($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        //购买兼职点套餐信息
        $service_part_company_resumepointin = new base_service_part_company_resumepoint_pointin();
        //兼职简历套餐码表信息
        $service_common_part_resumesetmeal = new base_service_common_part_resumesetmeal;
        //购买兼职置顶信息
        $base_service_part_company_jobtop = new base_service_part_job_partjobtop();
        //兼职置顶码表信息
        $service_common_part_jobtoptype = new base_service_common_part_jobtoptype;

        $cur_page = base_lib_BaseUtils::getStr($pathdata['page'], 'int', 1);
        $page_size = base_lib_Constant::PAGE_SIZE;

        //简历点支出记录
        $service_part_company_resumepoint_pointout = new base_service_part_company_resumepoint_pointout();
        $pointout_list                   = $service_part_company_resumepoint_pointout->getListByCompany($this->_userid, 'company_id,amount,create_time,spent_type', $cur_page, $page_size);
        $pointout_count                  = $pointout_list->totalSize;
        $pointout_list                   = $pointout_list->items;
        $spent_type                      = $service_part_company_resumepoint_pointout->getCommonSpentType();
        $this->_aParams['pointout_list'] = $pointout_list;
        $this->_aParams['spent_type']    = $spent_type;

        /**
         * 当前页面简历点消费金额
         */
        foreach ($pointout_list as $key=>$value){
            $this->_aParams['total_out'] += $value['amount'];
        }
        //总简历点消费金额
        $this->_aParams['sum_out'] = $service_part_company_resumepoint_pointout->getSumPointOut($this->_userid);

        $service_part_company_partjoborder = new base_service_part_order_partorder();
        $items = 'user_id,user_name,create_time,order_money,real_total_money,resume_point_id,job_top_id';
        $companys = $service_part_company_partjoborder->getOrderCompanyId($cur_page,$page_size,$this->_userid,$items);
        $datalist = $companys->items;
        $this->_aParams['sum'] = $service_part_company_partjoborder->getSumConsume($this->_userid);//总金额

        foreach($datalist as $key=>$value){
            $order_money = empty($value['order_money'])?$value['real_total_money']:$value['order_money']; //金额
            $datalist[$key]['order_money'] = $order_money;
            $this->_aParams['total'] += $order_money; //当前页面金额
            $resumepoint = $service_part_company_resumepointin->getResumePointId($value['resume_point_id']);//兼职购买套餐信息
            $resumesetmeal = $service_common_part_resumesetmeal->getSetmealByCode($resumepoint['setmeal_id']);
            $datalist[$key]['order_name'] = $resumesetmeal['name'].'*'.$resumesetmeal['point_num'];
            $datalist[$key]['create_time'] = $resumepoint['create_time'];//购买时间


            //置顶订单
            if ($value['job_top_id'] > 0){
                $jobtop = $base_service_part_company_jobtop->getJobTopId($value['job_top_id']);
                if (!empty($jobtop)){
                    $datalist[$key]['create_time'] = $jobtop['create_time'];//购买时间
                    $datalist[$key]['order_name'] = $service_common_part_jobtoptype->gettoptypeByCode($jobtop['top_type'])['name']."(".$jobtop['top_day']."天)"; //产品名称
                }
            }
        }
        $this->_aParams['title'] = '兼职消费记录 我的账户-汇博人才网';
        $total_count             = $companys->totalSize > $pointout_count ? $companys->totalSize : $pointout_count;
        $pager = $this->pageBar($total_count, $page_size, $cur_page, $inPath);
        $this->_aParams['pager'] = $pager;
        $this->_aParams['datalist']=$datalist;

        return $this->render('service/historypart.html', $this->_aParams);
    }

    /**
     * 一个数组返回所有元素被第一个元素除的结果
     */
    public function pagetest(){
        //象棋问题
        $i = 81;
        while(true){
            if($i/9%3 == $i%9%3){
                continue;
            }
            echo "A:".($i/9+1)."B:".($i%9+1);
        }


    }
	
}

?>