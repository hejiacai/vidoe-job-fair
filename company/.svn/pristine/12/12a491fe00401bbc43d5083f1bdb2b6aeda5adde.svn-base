<?php
/**
 * @Desc:   推广金
 * @Author: zhangfangjun@huibo.com
 * @Date:   2015-12-14 14:33:41
 * @Last Modified by:   zhangfangjun - Administrator
 * @Last Modified time: 2015-12-23 21:47:48
 * @Copyright (c) http://www.huibo.com All rights reserved.:
 */
class controller_spread extends components_cbasepage {	
    
    function __construct() {
		parent::__construct();
    }

    function pageIndex($inPath) {
        if(!$this->canDo("spread_job")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
        }
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $cur_page = base_lib_BaseUtils::getStr($pathdata['page'],'int',1);
        $page_size = base_lib_Constant::PAGE_SIZE;

        $spread_service = new base_service_company_spread_spread();
        $origin_status = new base_service_common_spreadorigin();
        $spreads = $spread_service->getSpreads($this->_userid,'spread_id,total,used,end_time,start_time,origin,create_time',$cur_page,$page_size);
        $count = $spread_service->getEffectConsume($this->_userid);
        $dataLst = $spreads->items;

        foreach ($dataLst as $key => $value) {
            $dataLst[$key]['create_time'] = date('Y-m-d',strtotime($value['create_time']));
            $dataLst[$key]['se_time'] = date('Y/m/d',strtotime($value['start_time'])).'~'.date('Y/m/d',strtotime($value['end_time']));
            $dataLst[$key]['status'] = $this->_buildStatus($value);
            $dataLst[$key]['from'] = $origin_status->getName($value['origin']);
        }
        $pager = $this->pageBar($spreads->totalSize, $page_size, $cur_page, $inPath);
        $this->_aParams['title'] = '推广金详情 我的账户-汇博人才网';
        $this->_aParams['pager'] = $pager;
        $this->_aParams['spreads'] = $dataLst;
        $this->_aParams['count'] = sprintf("%.2f",($count['count']-$count['used']));

        $company_resources    = base_service_company_resources_resources::getInstance($this->_userid, true, base_lib_BaseUtils::getCookie('accountid'));
        $resource_data        = $company_resources->getCompanyServiceSource(["_account_resource"]);
        $this->_aParams['isCQService'] = $resource_data['isCqNewService'];

        return $this->render('service/spread.html', $this->_aParams);
    }

    function pageHistory($inPath) {
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $cur_page  = base_lib_BaseUtils::getStr($pathdata['page'],'int',1);
        $page_size = base_lib_Constant::PAGE_SIZE;

        $history_service = new base_service_company_spread_spreadhistory();
        $spread_service  = new base_service_company_spread_spread();
        $consume_status  = new base_service_common_spreadconsume();
        $company_resources  = base_service_company_resources_resources::getInstance($this->_userid,true,base_lib_BaseUtils::getCookie('accountid'));
        $company_ids        = $company_resources->all_accounts;
        $historys = $history_service->getHistorys($company_ids, 'history_id,spread_id,consume,consume_type,create_time,account_id', $cur_page, $page_size);
        $accountids = base_lib_BaseUtils::getProperty($historys->items,'account_id');

        $base_service_company_account = new base_service_company_account();
        $accounts = $base_service_company_account->getAccounts(array_unique($accountids),'account_id,user_id,user_name');
        $list = base_lib_BaseUtils::array_key_assoc($accounts,'account_id');

        $count         = $spread_service->getEffectConsume($this->_userid);
        $consume_count = $history_service->getConsumes($this->_userid);
        $dataLst       = $historys->items;
        $cur_total     = 0;

        foreach ($dataLst as $key => $value) {
            $cur_total += intval($value['consume']);
            if ($value['consume_type'] == '11'){
                $dataLst[$key]['create_time'] = date('Y-m-d', strtotime ("-1 day", strtotime($value['create_time']))).'&nbsp;23:59:59';;
            }
            $dataLst[$key]['consume_type'] = $consume_status->getName($value['consume_type']);
            $dataLst[$key]['user_name'] = $list[$value['account_id']]['user_id'].'-'.$list[$value['account_id']]['user_name'];
        }

        $pager = $this->pageBar($historys->totalSize, $page_size, $cur_page, $inPath);
        $this->_aParams['title']         = '推广金详情 我的账户-汇博人才网';
        $this->_aParams['pager']         = $pager;
        $this->_aParams['historys']      = $dataLst;
        $this->_aParams['count']         = sprintf("%.2f", ($count['count'] - $count['used']));
        $this->_aParams['cur_count']     = sprintf("%.2f", $cur_total);
        $this->_aParams['consume_count'] = sprintf("%.2f", $consume_count['total_consume']);

    	return $this->render('service/spreadhistory.html', $this->_aParams);
    }

    function pageTop($inPath) {
        if(!$this->canDo("spread_job")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
        }
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $job_id    = base_lib_BaseUtils::getStr($path_data['jobid'], 'int', 0);

        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,base_lib_BaseUtils::getCookie('accountid'));
        list($status, $code, $params) = $company_resources->check($func="top", $params=['job_id' => $job_id]);
        if (!$status) {
            $this->_aParams['message'] = $params['msg'];
            return $this->render('showmsg.html', $this->_aParams);
        }

        $this->_aParams['params'] = $params;
        $service_jobtop = new base_service_company_job_jobtop();
        if ($params['is_already']) {
            //是否已经是置顶职位
            $top_list = $service_jobtop->getList($service_id=0, $this->_userid, $job_id, 'id,keyword,end_time,jobsort')->items;

            $this->_aParams['toplist'] = $top_list;
        }

        $this->_aParams['params'] = $params;

        $service_job = new base_service_company_job_job();
        $current_job = $service_job->getJob($job_id, 'job_id,jobsort,jobsort_ids,station,company_id,check_state,status,end_time,urgent_end_time');

        $common_jobsort = new base_service_common_jobsort();
        $this->_aParams['job']      = $current_job;
        $this->_aParams['job_id']   = $job_id;
        $this->_aParams['jobsorts'] = $params['is_already']
            ? $common_jobsort->getSelfAndParentJobsort($top_list[0]['jobsort'])
            : $common_jobsort->getSelfAndParentJobsort($current_job['jobsort']);

//        $sameSortJobNumber = 0;
//        $topJob_All = $service_jobtop->getListByCompanyid($service_id=0, $this->_userid, 'id,keyword,end_time,jobsort');
//        if(!empty($topJob_All)){
//            foreach($topJob_All as $topval){
//                $tempParentJobSort = $common_jobsort->getParentJobsort($topval['jobsort']);
//                if($tempParentJobSort == $this->_aParams['jobsorts'][1]['jobsort']){
//                    $sameSortJobNumber++;
//                }
//            }
//        }
        $companyService = new base_service_company_company();
        $company = $companyService->getCompany($this->_userid, null, 'calling_id');
        $sameSortJobNumber = $service_jobtop->getTopNumberByJobsort($this->_aParams['jobsorts'][1]['jobsort'],$company['calling_id']);
        $this->_aParams['sameSortJobNumber'] = $sameSortJobNumber;
        //是否已经是置顶职位
        return $this->render('settopjob.html', $this->_aParams);
    }

    /**
     * 返回订单信息
     * @param  array $inPath url参数集
     * @return json          json格式数据
     */
    function pageOrder($inPath) {
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $validator = new base_lib_Validator();

        $datas  = $validator->getArray($path_data['data'], "请选择置顶的关键词");
        $job_id = $validator->getNotNull($path_data['job_id'], "请选择置顶的职位");
        $log_type = '';
        $log_message = '';
        foreach ((array)$datas as $data) {
            if ($data['type'] == 'top') {
                $log_type = 'top';
                if (empty($data['keyword']) && !empty($data['dllday']))
                    $validator->addErr("请选择置顶的关键词");

                if (!empty($data['dllday']))
                    $tops[] = ['keyword' => $data['keyword'], 'delay_day' => $data['dllday']];


            } else if ($data['type'] == 'urgent') {
                
                if (!empty($data['dllday']))
                    $urgents[] = ['delay_day' => $data['dllday']];
            }
        }

        $key = 1;
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,base_lib_BaseUtils::getCookie('accountid'));
        $companyresources = $company_resources->getCompanyServiceSource(['account_resource']);
        /*
         * $tops
         *  'keyword'
         *  'delay_day'
         * */
        if (!empty($tops)) {
            list($status, $code, $params) = $company_resources->check($func="top", $_condition=['job_id'=>$job_id, "tops"=>$tops]);

            if (!$status){
                $validator->addErr($params['msg']);
            }

            if($companyresources['resource_type'] == 2){
                if(($params['spread_overage'] + $params['account_overage']) < $params['consume']){
                    $validator->addErr("您的推广金不足，请联系主账号为您分配更多资源");
                }
            }


            $items['orders'][] = [
                "title"   => "订单{$key}：职位置顶",
                "day"     => $params['count'] . "天",
                "consume" => $params['consume']
            ];
            $items['params'] = $params;
            $key ++;
        }
        
        if (!empty($urgents)) {
            list($status, $code, $_params) = $company_resources->check($func="urgent", $_condition=['job_id'=>$job_id, "urgents"=>$urgents]);

            if (!$status){

                if($code == base_service_company_resources_code::LACK_OF_ACCOUNT && $companyresources['resource_type'] == 2){
                    $validator->addErr("您的推广金不足，请联系主账号为您分配更多资源");
                }else{
                    $validator->addErr($_params['msg']);
                }
            }


            $items['orders'][] = [
                "title"   => "订单{$key}：职位急聘",
                "day"     => ($_params['count'] * 7) . "天",
                "consume" => $_params['consume']
            ];

            $_params['consume'] = $params['consume'] + $_params['consume'];
            $items['params'] = $_params;
        }

        if ($validator->has_err) {
            echo json_encode(array('status'=>false, 'msg'=>$validator->err[0]));
            exit;
        }

        echo json_encode(array('status'=>true,'msg'=>'操作成功', 'items'=>$items));
        exit;
    }

    /**
     * 急聘推广详情弹窗
     * @param  array $inPath url参数
     * @return html          seturgentjob.html
     */
    function pageUrgent($inPath) {
        if(!$this->canDo("spread_job")){
            $this->_aParams["msg"] = "无权限访问，没有开通相应权限";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom1.html", $this->_aParams);
        }
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $job_id   = base_lib_BaseUtils::getStr($path_data['jobid'], 'int', 0);

        $company_resources = base_service_company_resources_resources::getInstance($this->_userid);
        list($status, $code, $params) = $company_resources->check($func="urgent", $params=['job_id' => $job_id]);

        if (!$status) {
            $this->_aParams['message'] = $params['msg'];
            return $this->render('showmsg.html', $this->_aParams);
        }

        $this->_aParams['is_urgentjob_already'] = $params['is_already'];

        $service_job = new base_service_company_job_job();
        $current_job = $service_job->getJob($job_id, 'job_id,station,company_id,check_state,status,end_time,urgent_end_time');

        $this->_aParams['job']    = $current_job;
        $this->_aParams['params'] = $params;
        return $this->render('seturgentjob.html', $this->_aParams);
    }

    /**
     * 置顶，急聘消费订单控制
     * @param  array $inPath url参数
     * @return json          json格式数据库
     */
    function pageConsume($inPath) {
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $validator = new base_lib_Validator();
        $datas  = $validator->getArray($path_data['orders'], "请选择置顶的关键词");
        $job_id = $validator->getNotNull($path_data['job_id'], "请选择置顶的职位");

        $service_job = new base_service_company_job_job();
        $job_info = $service_job->getJob($job_id, 'job_id,station');

        $log_message = '职位：'.$job_info['station'].",";
        foreach ((array)$datas as $data) {
            if ($data['type'] == 'top') {
                if (empty($data['keyword']) && !empty($data['dllday']))
                    $validator->addErr("请选择置顶的关键词");

                if (!empty($data['dllday']))
                    $tops[] = ['keyword' => $data['keyword'], 'delay_day' => $data['dllday']];

            } else if ($data['type'] == 'urgent') {
                
                if (!empty($data['dllday']))
                    $urgents[] = ['delay_day' => $data['dllday']];
            }
        }

        $company_resources = base_service_company_resources_resources::getInstance($this->_userid);
        if (!empty($tops)) {
            list($status, $code, $params) = $company_resources->consume($func="top", $params=['job_id'=>$job_id, "tops"=>$tops]);
            $log_message .= '关键词推广置顶，';

            if (!$status)
                $validator->addErr($params['msg']);
            else{
                $log_message .= "关键词：".implode('，',base_lib_BaseUtils::getProperty($tops,'keyword'));
                //剩余推广金
                $spread_overage  = $params['spread_overage'];
                //账户余额
                $account_overage = $params["account_overage"];
                //消费
                $consume         = $params['consume'];
                if($spread_overage >= $consume){
                    $log_message .= "推广金扣除：".$consume;
                }else{
                    if($spread_overage >0){
                        $consume    -= $spread_overage;
                        $log_message .= "推广金扣除：".$spread_overage."，余额扣除：".$consume;
                    }else{
                        $log_message .= "余额扣除：".$consume;
                    }
                } 
            }
        }


        if (!empty($urgents)) {
            list($status, $code, $params) = $company_resources->consume($func="urgent", $params=['job_id'=>$job_id, "urgents"=>$urgents]);
            $log_message .= '急聘，';
            if (!$status)
                $validator->addErr($params['msg']);
            else{
                 //剩余推广金
                $spread_overage  = $params['spread_overage'];
                //账户余额
                $account_overage = $params["account_overage"];
                //消费
                $consume         = $params['consume'];
               if($spread_overage >= $consume){
                    $log_message .= "推广金扣除：".$consume;
                }else{
                    if($spread_overage >0){
                        $consume    -= $spread_overage;
                        $log_message .= "推广金扣除：".$spread_overage."，余额扣除：".$consume;
                    }else{
                        $log_message .= "余额扣除：".$consume;
                    }
                } 
            }
        }

        if ($validator->has_err) {
            echo json_encode($json=['status'=>false, 'msg'=>$validator->err[0]]);
            exit;
        }


        //---------添加操作日志--------
        $common_oper_type = new base_service_common_account_accountoperatetype();
        $service_oper_log = new base_service_company_companyaccountlog();
        $common_oper_src_type = new base_service_common_account_accountlogfrom();
        $insertItems=array(
            "company_id"=>$this->_userid,
            "source"=>$common_oper_src_type->website,
            "account_id"=>base_lib_BaseUtils::getCookie('accountid'),
            "operate_type"=>$common_oper_type->job_sperd_top,
            "content"=>$log_message,
            "create_time"=>date("Y-m-d H:i:s",time())
        );
        $service_oper_log->addLogToMongo($insertItems);
        //-------------END------------


        echo json_encode($json=['status'=>true]);
        exit;
    }

    private function _buildStatus($arr) {
        $cu_time = time();
        if($cu_time<strtotime($arr['start_time'])){
            return '未开始';
        }elseif($cu_time>strtotime($arr['end_time'])){
            return '<span style="color:#d73937">已过期</span>';
        }else{
            return '有效';
        }
    }
    
    
	/**
	 *@Desc 推广金推广页面
	 *@return type_name
	*/
	function pageDetail($inPath) {
		//获取招聘顾问
        $companyStateService = new base_service_company_comstate();
		$companyState = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
		$hrManager    = $this->GetHRManager($companyState["net_heap_id"]);
	
        //获取客服员
        $this->_aParams["hasHRManager"] = false;
        if (!is_null($hrManager)) {
            $this->_aParams["hasHRManager"] = true;
            $headPhoto = base_lib_BaseUtils::nullOrEmpty($hrManager["head_photo_url"]) ? $domain["defaultPhoto"] : $hrManager["head_photo_url"];
            $hrManager["head_photo_url"] = $domain["image"] . "/" . $domain["photo"] . "/" . $headPhoto;
            $this->_aParams["hrManager"] = $hrManager;
        }
        
        $xml = SXML::load('../config/config.xml');
        if (!is_null($xml)) {
            $tel_head = $xml->TechniquePhone;
            $HuiboPhone400 = $xml->HuiboPhone400;
        }
        $easy_tel_head = str_replace("-", "", $tel_head);
		$this->_aParams['tel_head']      = $tel_head;
		$this->_aParams['easy_tel_head'] = $easy_tel_head;
		$this->_aParams['HuiboPhone400'] = $HuiboPhone400;
		return $this->render('spread_detail.html', $this->_aParams);
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
}
