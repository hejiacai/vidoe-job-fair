<?php
/**
 *
 * @ClassName controller_spreadjob
 * @Desc 统计
 * @author linian
 * @date 2015-08-03 上午09:51:47
 */
class controller_spreadstatistics extends components_cbasepage{
    /**
     * 构造函数
     */
    function __construct() {
        parent::__construct();
        //剩余推广金
        $accountid = base_lib_BaseUtils::getCookie('accountid');
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
        $companyresources = $company_resources->getCompanyServiceSource(['account_resource']);
        $this->_aParams['count'] = $companyresources['spread_overage'];
        //品牌推广广告所用
        $this->_aParams['super_company_id'] = 299781;
        $this->_aParams['this_company_id'] = $this->_userid;
    }



    /**
     * 账户整体分析
     * @param $inPath
     * @return mixed
     */
    public function pageIndex($inPath) {
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $stime = base_lib_BaseUtils::getStr($path_data['startDay'],'string','');
        $etime = base_lib_BaseUtils::getStr($path_data['endDay'],'string','');

        if(empty($stime) && empty($etime)){
            $stime = date('Y-m-d',strtotime('-7 day'));
            $etime = date('Y-m-d',strtotime('-1 day'));
        }

        $yesterday_stime = date('Y-m-d',strtotime('-1 day'));

        //获取统计信息
        $company_resources  = base_service_company_resources_resources::getInstance($this->_userid);
        $company_ids        = $company_resources->all_accounts;
       $company_spread_dayhistory = new base_service_company_spread_dayhistory();
        $yesterday_info = $company_spread_dayhistory->getDayHistoryLists($company_ids,$yesterday_stime,$yesterday_stime)->items;

        $this->_aParams['yesterday_see_times'] = $yesterday_info[0]['seeTimes'] ? $yesterday_info[0]['seeTimes'] : 0;
        $this->_aParams['yesterday_click_times'] = $yesterday_info[0]['clickTimes']? $yesterday_info[0]['clickTimes'] : 0;
        $this->_aParams['yesterday_total_consume'] = $yesterday_info[0]['totalConsume']? number_format($yesterday_info[0]['totalConsume'],2) : 0;
        $this->_aParams['yesterday_num'] = $yesterday_info[0]['num']? $yesterday_info[0]['num'] : 0;

        $dayhistory = $company_spread_dayhistory->getDayHistoryLists($company_ids,$stime,$etime);
        $lists = $dayhistory->items;
        foreach($lists as $key=>&$val){
            $val['click_rate'] = empty($val['seeTimes']) ? 0 : number_format(($val['clickTimes']/$val['seeTimes'])*100,2).'%';
            $val['totalConsume'] = number_format($val['totalConsume'],2);
        }

        $this->_aParams['list'] = $lists;
        $this->_aParams['stime'] = $stime;
        $this->_aParams['etime'] = $etime;
        return $this->render("./spread/statistics_all.html",$this->_aParams);
    }

    /**
     * 职位推广分析
     * @param $inPath
     * @return mixed
     */
    public function pageSpreadJobList($inPath){
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $stime = base_lib_BaseUtils::getStr($path_data['startDay'],'string','');
        $etime = base_lib_BaseUtils::getStr($path_data['endDay'],'string','');
        $job_id = base_lib_BaseUtils::getStr($path_data['job_id'],'int','');

        $company_spread_dayhistory = new base_service_company_spread_dayhistory();
        $company_resources  = base_service_company_resources_resources::getInstance($this->_userid);
        $company_ids        = $company_resources->all_accounts;
        $spread_job_list = $company_spread_dayhistory->getSpreadJobStaistics($company_ids,$stime,$etime,$job_id);
        $lists = $spread_job_list->items;

        if(empty($stime) && empty($etime)){
            $stime = date('Y-m-d',strtotime('-7 day'));
            $etime = date('Y-m-d',strtotime('-1 day'));
        }
        $this->_aParams['stime'] = $stime;
        $this->_aParams['etime'] = $etime;
        $this->_aParams['job_id'] = empty($job_id)?'0':$job_id;
        //获取职位名称
        $company_job = new base_service_company_job_job();

        $spread_job_obj = new base_service_company_spread_spreadjob();
        $job_list = $spread_job_obj->getSpreadJobInfo($company_ids)->items;
        $json_data = "{'value':'0','label':'全部'},";
        foreach($job_list as $key=>$val){

            if(mb_strlen($val['station']) >9 ){
                $val['station'] = mb_substr($val['station'],0,8)."...";
            }
            $json_data.="{'value':'".$val['job_id']."','label':'".$val['station']."'},";
        }
        $this->_aParams['data'] = substr($json_data,0,-1);
        $job_list = base_lib_BaseUtils::array_key_assoc($job_list,'job_id');

        foreach($lists as $key=>&$val){
            $val['station'] = $job_list[$val['job_id']]['station'];
            $val['click_rate'] = empty($val['see_times']) ? 0 : round(($val['click_times']/$val['see_times'])*100,2).'%';
            $val['click_price'] = empty($val['click_times']) ? 0 : round(($val['total_consume']/$val['click_times']),2);
            $val['total_consume'] = empty($val['total_consume']) ? 0 : round($val['total_consume'],2);
        }
        $this->_aParams['list'] = $lists;
        return $this->render("./spread/statistics_job.html",$this->_aParams);
    }

    /**
     * 品牌推广分析
     * @param $inPath
     * @return mixed
     */
    public function pageSpreadBrand($inPath){
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $project_name = base_lib_BaseUtils::getStr($path_data['project_name'],'string','');
        $stime = base_lib_BaseUtils::getStr($path_data['startDay'],'string','');
        $etime = base_lib_BaseUtils::getStr($path_data['endDay'],'string','');

        if(empty($stime) && empty($etime)){
            $stime = date('Y-m-d',strtotime('-7 day'));
            $etime = date('Y-m-d',strtotime('-1 day'));
        }

        if($etime && $stime && $stime > $etime){
            $temp = $stime;
            $stime = $etime;
            $etime = $temp;
        }

        $this->_aParams['stime'] = $stime;
        $this->_aParams['etime'] = $etime;
        $this->_aParams['project_id'] = empty($project_name)?'0':$project_name;

        //获取品牌数据
        $condition = array(
            'company_id' => $this->_userid,
        );
        $item= "spread_id,title";
        $company_brand_spread = new base_service_company_spread_companybrandspread();
        $company_brand_list = $company_brand_spread->getList($condition,$item);
        $json_data = "{'value':'0','label':'全部'},";
        foreach($company_brand_list->items as $key=>$val){
            if(mb_strlen($val['title']) >9 ){
                $val['title'] = mb_substr($val['title'],0,8)."...";
            }
            $json_data.="{'value':'".$val['spread_id']."','label':'".$val['title']."'},";
        }
        $this->_aParams['brand_spread'] = $json_data;

        $company_brand_list = base_lib_BaseUtils::array_key_assoc($company_brand_list->items,'spread_id');
        //var_dump($company_brand_list);

        $spread_id = '';
        if($project_name){
            $where = array(
                'company_id'    => $this->_userid,
                'spread_id'         => $project_name,
            );
            $brand_one = $company_brand_spread->getDataOne($where,$item);
            $spread_id = $brand_one['spread_id'];
        }

        //获取品牌推广统计数据

        $company_spread_dayhistory = new base_service_company_spread_dayhistory();
        $spread_brand_list = $company_spread_dayhistory->getSpreadBrand($this->_userid,$stime,$etime,$spread_id)->items;

        foreach($spread_brand_list as $key=>&$val){
            $val['title'] = $company_brand_list[$val['spread_id']]['title'];
            $val['click_rate'] = empty($val['see_times']) ? 0 : round(($val['click_times']/$val['see_times'])*100,2).'%';
            $val['click_price'] = empty($val['click_times']) ? 0 : round(($val['total_consume']/$val['click_times']),2);
            $val['total_consume'] = empty($val['total_consume']) ? 0 : round($val['total_consume'],2);
        }
        $this->_aParams['list'] = $spread_brand_list;
        return $this->render("./spread/statistics_brand.html",$this->_aParams);

    }






}

?>