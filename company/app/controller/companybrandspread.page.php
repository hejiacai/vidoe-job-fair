<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/14 0014
 * Time: 15:00
 */
class controller_companybrandspread extends components_cbasepage {

    /**
     * 构造函数
     */
    function __construct() {
        parent::__construct();
        //品牌推广广告所用
        $this->_aParams['super_company_id'] = 299781;
        $this->_aParams['this_company_id'] = $this->_userid;
    }

    public function pageindex($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

        $condition = array();
        $condition['company_id'] = $this->_userid;
        //获取查询状态
        $status = base_lib_BaseUtils::getStr($pathdata['auditStatus'], 'string','');
        if($status == '1') {
            $condition['status'] = 1;
            $condition['audit_status'] = 1;
            $this->_aParams['status'] = 1;
        }else if($status == '0'){
            array_push($condition , '(status <> 1 or audit_status <> 1)');
            $this->_aParams['status'] = 0;
        }else{
            $this->_aParams['status'] = 'default';
        }
        $condition['is_effect'] = 1;
        //获取推广金额
        $spread_service = new base_service_company_spread_spread();
        $spreadInfo = $spread_service->getEffectConsume($this->_userid,'','',true);

        //$this->_aParams['spreadtotal'] = sprintf("%.2f",($spreadInfo['count']-$spreadInfo['used']));
        $this->_aParams['spreadtotal'] = $spreadInfo;

        $brandspread = new base_service_company_spread_companybrandspread();
        $items = 'spread_id,banner_position,title,company_id,spread_image,url,bid,budget,last_budget,is_effect,audit_status,status,area_ids,degree_ids,work_year_ids,sex,age_lower,age_upper,fail_text';
        
        $brandspreadList = $brandspread->getList($condition,$items)->items;
        foreach($brandspreadList as &$branditem){
            if(stripos($branditem['url'],'http://') === 0){
                $branditem['targetUrl'] = $branditem['url'];
            }else{
                $branditem['targetUrl'] =  'http://'.$branditem['url'];
            }
        }
        $this->_aParams['List'] = $brandspreadList;
        $this->_aParams['cur'] = '精准推广';

        return $this->render('spread/brandspread.html',$this->_aParams);
    }
    //设置状态
    public function pagesetStatus($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $spread_id = base_lib_BaseUtils::getStr($pathdata['spread_id'],'int','');
        if(empty($spread_id)){
            echo json_encode(array('status'=>false , 'msg'=> '参数错误！'));return;
        }

        //获取实际消耗的金额
        $click_service = new base_service_company_spread_click();
        $sumPrice = $click_service->getSpreadUsePrice($spread_id , 2);
        if($sumPrice == false){
            $json = array('status'=>false , 'msg'=> '参数错误！');echo json_encode($json);return;
        }
        $sumPrice = empty($sumPrice['price_sum']) ? 0 : $sumPrice['price_sum'];

        //获取品牌推广信息
        $companyBrandSpread_service = new base_service_company_spread_companybrandspread();
        $condition = array('spread_id'=>$spread_id,'company_id'=>$this->_userid , 'is_effect'=>1);
        $spreadinfo = $companyBrandSpread_service->getDataOne($condition, 'is_effect,status,audit_status,last_budget,budget');
        if(empty($spreadinfo)){
            echo json_encode(array('status'=>false , 'msg'=> '此品牌推广已删除！'));return;
        }
        $spreadbreadlog_service = new base_service_company_spread_spreadbreadlog();
        $updateData = array();
        $msg = '';
        if($spreadinfo['status']==1){
            $updateData['status'] = 2;

            $loginfo = array();
            $loginfo['company_id'] = $this->_userid;
            $loginfo['spread_id'] = $spread_id;
            $loginfo['content'] = "品牌推广手动关闭";
            $spreadbreadlog_service->addData($loginfo);

            $companybrandInfo = $companyBrandSpread_service->getDataOne(array('spread_id'=>$spread_id , 'company_id'=>$this->_userid) , 'budget,last_budget');
//            if($companybrandInfo['last_budget'] > 0){ //查看是否还有剩余预算金
//                $addSpreadMoney = $companybrandInfo['last_budget'];
//                $spread_service = new base_service_company_spread_spread();
//                $spread_origin = new base_service_common_spreadorigin();
//                $orgin = $spread_origin->__get('bidReturn');
//
//                $spread_regData = array('company_id' => $this->_userid,'total' => $addSpreadMoney,'start_time' => date('Y-m-d'),'end_time' => date('Y-m-d',time()+2592000),'origin' => $orgin);
//                $BidInfo = $spread_service->addCompanySpread($spread_regData);
//                if($BidInfo==false){
//                    $BidInfo=array('status'=>false, 'msg'=> '推广金返还失败！请联系客服');
//                    echo json_encode($BidInfo);return;
//                }else{
//                    $companyBrandSpread_service->setData($spread_id ,$this->_userid , array('last_budget'=>0));
//                }
//                $spread_history = new base_service_company_spread_spreadhistory();
//                $spread_history_origin = new base_service_common_spreadconsume();//推广金记录
//                $history_origin = $spread_history_origin->__get('BrandbidReturn');
//                $hisresult = $spread_history->addHistory($this->_userid, $spread_id, -$addSpreadMoney, $history_origin);
//
//                $spreadbreadlog_service = new base_service_company_spread_spreadbreadlog();
//                $loginfo = array();
//                $loginfo['company_id'] = $this->_userid;
//                $loginfo['spread_id'] = $spread_id;
//                $loginfo['content'] = "品牌推广删除，返还推广金：{$addSpreadMoney}";
//                $spreadbreadlog_service->addData($loginfo);
//            }
        }else if(in_array($spreadinfo['status'],array(0,2))){

            $updateData['status'] = 1;

            $loginfo = array();
            $loginfo['company_id'] = $this->_userid;
            $loginfo['spread_id'] = $spread_id;
            $loginfo['content'] = "品牌推广手动开启";
            $spreadbreadlog_service->addData($loginfo);

//            //实际消费 < 预算金额 则补款
//            if($sumPrice < $spreadinfo['budget']){
//                //补款
//                $resources_service = new base_service_company_resources_resources();
//                $BkPrice = 0;
//                //当天实际消费+剩余预算 ！= 预算 且当天实际消费=0 ， 则补款金额等于预算 - 剩余预算
//                if($sumPrice+$spreadinfo['last_budget'] !=$spreadinfo['budget'] && $sumPrice == 0){
//                    $BkPrice = $spreadinfo['budget']-$spreadinfo['last_budget'];
//                }else{
//                    $BkPrice = $spreadinfo['budget']-$sumPrice;
//                }
//                if($BkPrice>0 && $spreadinfo['last_budget'] == 0){
//                    $BidInfo = $resources_service->consume('Bid_ads_spread',array('company_id'=>$this->_userid , 'spread'=> $BkPrice ,'type'=>1));
//                    $updateData['last_budget'] = $BkPrice;
//                }
//
//            }else{
//                $msg = '今日已消耗'.$sumPrice.'元，超出您目前设置的预算 暂不补款';
////                echo json_encode(array('status'=>false , 'msg'=> '今日已消耗'.$sumPrice.'元，超出您目前设置的预算'));return;
//            }

        }
        $ret = $companyBrandSpread_service->setData($spread_id , $this->_userid , $updateData);

        if($ret !== false){
            echo json_encode(array('status'=>true , 'msg'=> '操作成功！'.$msg));return;
        }
        echo json_encode(array('status'=>false , 'msg'=> '操作失败！'));return;
    }



    //删除品牌推广
    public function pagesetEffect($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

        $spread_id = base_lib_BaseUtils::getstr($pathdata['spread_id'],'int',0);
        if(empty($spread_id)){
            echo json_encode(array('status'=>false , 'msg'=> '参数错误！'));return;
        }

        $updateData = array('is_effect'=>0,'status'=>2);
        $companyBrandSpread_service = new base_service_company_spread_companybrandspread();
        $ret = $companyBrandSpread_service->setData($spread_id , $this->_userid , $updateData);
        //需要返回推广金
        if($ret !== false){
//            $companybrandInfo = $companyBrandSpread_service->getDataOne(array('spread_id'=>$spread_id , 'company_id'=>$this->_userid) , 'budget,last_budget');
//            if($companybrandInfo['last_budget'] > 0){ //查看是否还有剩余预算金
//                $addSpreadMoney = $companybrandInfo['last_budget'];
//                $spread_service = new base_service_company_spread_spread();
//                $spread_origin = new base_service_common_spreadorigin();
//                $orgin = $spread_origin->__get('bidReturn');
//
//                $spread_regData = array('company_id' => $this->_userid,'total' => $addSpreadMoney,'start_time' => date('Y-m-d'),'end_time' => date('Y-m-d',time()+2592000),'origin' => $orgin);
//                $BidInfo = $spread_service->addCompanySpread($spread_regData);
//                if($BidInfo==false){
//                    $BidInfo=array('status'=>false, 'msg'=> '推广金返还失败！请联系客服');
//                    echo json_encode($BidInfo);return;
//                }else{
//                    $updateData = array('last_budget'=>0);
//                    $companyBrandSpread_service->setData($spread_id , $this->_userid , $updateData);
//                }
//                $spread_history = new base_service_company_spread_spreadhistory();
//                $spread_history_origin = new base_service_common_spreadconsume();//推广金记录
//                $history_origin = $spread_history_origin->__get('BrandbidReturn');
//                $hisresult = $spread_history->addHistory($this->_userid, $spread_id, -$addSpreadMoney, $history_origin);
//
//                $spreadbreadlog_service = new base_service_company_spread_spreadbreadlog();
//                $loginfo = array();
//                $loginfo['company_id'] = $this->_userid;
//                $loginfo['spread_id'] = $spread_id;
//                $loginfo['content'] = "品牌推广删除，返还推广金：{$addSpreadMoney}";
//                $spreadbreadlog_service->addData($loginfo);
//            }
            echo json_encode(array('status'=>true , 'msg'=> '操作成功！'));return;
        }
        echo json_encode(array('status'=>false , 'msg'=> '操作失败！'));return;
    }

    public function pageadddata($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        //获取1广告位的评价出价
        $companyBrandspread_Service = new base_service_company_spread_companybrandspread();
        $this->_aParams['AveragePrice'] = $companyBrandspread_Service->getAveragePrice(1);
        $this->_aParams['AveragePrice'] = round($this->_aParams['AveragePrice'],2);
        //初始化新增、修改参数
        $this->reader();
        return $this->render('spread/brandspreadAdd.html',$this->_aParams);
    }

    public function pageaddDo($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $CheckData = $this->chcekData($pathdata);

        if(!$CheckData['status']){
            echo json_encode($CheckData);return;
        }
        $companyBrandspread_Service = new base_service_company_spread_companybrandspread();
        $CheckData['data']['banner_position'] = 1;
        $CheckData['data']['company_id'] = $this->_userid;
        $CheckData['data']['status'] = 1;
        $CheckData['data']['audit_status'] = 0;
        $CheckData['data']['create_time'] = date('Y-m-d H:i:s');
        $CheckData['data']['is_effect'] = 1;
        //查询质量分
        $Average = $this->getQualityScore($this->_userid);
        $CheckData['data']['quality_score'] = $Average;
        $CheckData['data']['sort_score'] = $Average*$CheckData['data']['bid']*1000;

        //扣除推广金 事务处理，如果为false 金额不会变动
//        $resources_service = new base_service_company_resources_resources();
//        $BidInfo = $resources_service->consume('Bid_ads_spread',array('company_id'=>$this->_userid , 'spread'=> $CheckData['data']['budget'] ,'type'=>1));
//        $ret = false;
//        $spreadbreadlog_service = new base_service_company_spread_spreadbreadlog();
//        if($BidInfo['status'] !== false){
//            //扣款成功！插入数据
//            $ret = $companyBrandspread_Service->addData($CheckData['data']);
//            //log data
//            $loginfo = array();
//            $loginfo['company_id'] = $this->_userid;
//            $loginfo['spread_id'] = $ret;
//            $loginfo['content'] = "新增品牌推广，预算设置为：{$CheckData['data']['budget']};状态为：{$BidInfo['msg']}";
//            $spreadbreadlog_service->addData($loginfo);
//        }else{
//            //log data
//            return json_encode(array('status'=>false, 'msg'=> $BidInfo['msg'] , 'data' => array()));
//        }
        $ret = $companyBrandspread_Service->addData($CheckData['data']);
        if($ret !== false){
            //将图片放到正式文件夹下面
            $this->brandImageToFormalPath($CheckData['data']['spread_image']);
            $json = array('status'=>true, 'msg'=> '添加成功！' , 'data' => array());return json_encode($json);
        }
        return json_encode(array('status'=>false, 'msg'=> '添加失败！' , 'data' => array()));
    }

    /*
     * 编辑品牌推广
     * */
    public function pageupdate($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $spread_id = base_lib_BaseUtils::getStr($pathdata['spread_id'],'string',0);

        $companyBrandSpread = new base_service_company_spread_companybrandspread();
        $condition = array();
        $condition['spread_id'] = $spread_id;
        $condition['company_id'] = $this->_userid;
        $items = "spread_id,banner_position,title,company_id,status,audit_status,audit_time,create_time,is_effect,bid,budget,last_budget,quality_score,sort_score,spread_image,url,area_ids,degree_ids,work_year_ids,sex,age_lower,age_upper";
        $spreadinfo = $companyBrandSpread->getDataOne($condition , $items);

        $spreadinfo['area_ids_arr'] = explode(',',$spreadinfo['area_ids']);
        $spreadinfo['degree_ids_arr'] = explode(',',$spreadinfo['degree_ids']);
        $spreadinfo['work_year_ids_arr'] = explode(',',$spreadinfo['work_year_ids']);
        //获取1广告位的评价出价
        $this->_aParams['AveragePrice'] = $companyBrandSpread->getAveragePrice(1);
        $this->_aParams['AveragePrice'] = round($this->_aParams['AveragePrice'],2);
        //初始化修改参数
        $this->reader();
        $this->_aParams['spreadinfo'] = $spreadinfo;
        header("Content-type: text/html; charset=utf-8");
        return $this->render('spread/brandspreadUpdate.html',$this->_aParams);
    }

    /*
     * 修改品牌推广信息
     * */
    public function pageUpdateDo($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

        $spread_id  = base_lib_BaseUtils::getStr($pathdata['spread_id'],'string','');
        if(empty($spread_id)){
            $json = array('status'=>false , 'msg'=> '参数错误！');echo json_encode($json);return;
        }

        $CheckData = $this->chcekData($pathdata);
        unset($CheckData['data']['last_budget']);
        if(!$CheckData['status']){
            echo json_encode($CheckData);return;
        }
        $companyBrandspread_Service = new base_service_company_spread_companybrandspread();

        $condition = array();
        $condition['spread_id'] = $spread_id;
        $condition['company_id'] = $this->_userid;

        //获取品牌信息
        $spreadinfo = $companyBrandspread_Service->getDataOne($condition , 'bid,budget,last_budget,url,spread_image');
        //判断是否动了url 和 图片 ， 如果图片或者链接有调整，需要重新审核改条信息
        if($spreadinfo['url'] != $CheckData['data']['url'] || $spreadinfo['spread_image'] != $CheckData['data']['spread_image']){
            $CheckData['data']['audit_status'] = 0;
            $CheckData['data']['is_effect'] = 1;
        }
//        //获取实际消耗的金额
//        $click_service = new base_service_company_spread_click();
//        $sumPrice = $click_service->getSpreadUsePrice($spread_id , 2);
//
//        if($sumPrice == false){
//            $json = array('status'=>false , 'msg'=> '参数错误！');echo json_encode($json);return;
//        }
//
//        $sumPrice = empty($sumPrice['price_sum']) ? 0 : $sumPrice['price_sum'];

        $budget = $spreadinfo['budget'];
        $thisbudget = $CheckData['data']['budget'];//调整到的预算

//        $spreadbreadlog_service = new base_service_company_spread_spreadbreadlog();
//        $spread_history = new base_service_company_spread_spreadhistory();

//        if($thisbudget > $budget){//预算调高 需要补差
//
//            //扣除推广金 事务处理，如果为false 金额不会变动
//            $addSpreadMoney = 0;
//            $resources_service = new base_service_company_resources_resources();
//            //上调预算大于实际消耗金额 则调用价款
//            if($thisbudget > $sumPrice){
//                //实际消费=0 且 剩余金额+实际消费！=当前预算  则补款金额 = 上调预算-剩余预算
//                if($sumPrice == 0 && $spreadinfo['last_budget']+$sumPrice != $budget){
//                    $addSpreadMoney = $thisbudget-$spreadinfo['last_budget'];
//                }else{
//                    $tempPrice = $budget > $sumPrice ? $budget : $sumPrice;
//                    $addSpreadMoney = $thisbudget-$tempPrice;
//                }
//                $BidInfo = $resources_service->consume('Bid_ads_spread',array('company_id'=>$this->_userid , 'spread'=> $addSpreadMoney ,'type'=>1));
//            }else{
//                $BidInfo['status'] = true;
//            }
//            //预算调高，剩余预算也调高
//            if($BidInfo['status']==false){
//                echo json_encode($BidInfo);return;
//            }else if($thisbudget > $sumPrice){
//                $CheckData['data']['last_budget']=$spreadinfo['last_budget']+$addSpreadMoney;
//            }
//            //log data
//            $loginfo = array();
//            $loginfo['company_id'] = $this->_userid;
//            $loginfo['spread_id'] = $spread_id;
//            $loginfo['content'] = "当前预算为：{$spreadinfo['budget']};当前剩余预算为：{$spreadinfo['last_budget']};新调整预算为：{$thisbudget};填补预算为：{$addSpreadMoney};填补状态：{$BidInfo['msg']},当天实际消费金额：{$sumPrice}";
//            $spreadbreadlog_service->addData($loginfo);
//        }else if($thisbudget < $budget){ //预算调低 且当天剩余推广金大于返还推广金 退换多余推广金
//            $retPrice = 0;
//            //实际用款<调整预算 则返款 返款金额=预算-调整预算   剩余预算=调整预算-实际用款
//            if($sumPrice < $thisbudget){
//                $retPrice = $spreadinfo['budget'] - $thisbudget;
//                $CheckData['data']['last_budget'] = $spreadinfo['last_budget'] - $retPrice >0 ? $spreadinfo['last_budget'] - $retPrice : 0;
//            }else if($sumPrice >= $thisbudget){//实际用款>=调整预算 则返款=剩余预算且设置剩余预算为0
//                $retPrice = $spreadinfo['last_budget'];
//                $CheckData['data']['last_budget'] = 0;
//            }
//            $spread_service = new base_service_company_spread_spread();
//            $spread_origin = new base_service_common_spreadorigin();
//            $orgin = $spread_origin->__get('bidReturn');
//            if($retPrice>0 && $spreadinfo['last_budget'] >= $retPrice){//返还推广金
//                $spread_regData = array('company_id' => $this->_userid,'total' => $retPrice,'start_time' => date('Y-m-d'),'end_time' => date('Y-m-d',time()+2592000),'origin' => $orgin);
//                $BidInfo = $spread_service->addCompanySpread($spread_regData);
//            }else{
//                $BidInfo = true;
//            }
//            //下调预算 剩余预算为0，则关闭该推广
//            if($CheckData['data']['last_budget'] == 0){
//                $CheckData['data']['status'] = 0;
//            }
//            if($BidInfo==false){
//                $BidInfo=array('status'=>false, 'msg'=> '推广金返还失败！请联系客服');
//                echo json_encode($BidInfo);return;
//            }
//            $spread_history_origin = new base_service_common_spreadconsume();//推广金记录
//            $history_origin = $spread_history_origin->__get('BrandbidReturn');
//            $hisresult = $spread_history->addHistory($this->_userid, $spread_id, -$retPrice, $history_origin);
//
//            $loginfo = array();
//            $loginfo['company_id'] = $this->_userid;
//            $loginfo['spread_id'] = $spread_id;
//            $loginfo['content'] = "当前预算为：{$spreadinfo['budget']};当前剩余预算为：{$spreadinfo['last_budget']};新调整预算为：{$thisbudget};返款金额为：{$retPrice};返款状态：成功";
//            $spreadbreadlog_service->addData($loginfo);
//        }
        //修改推广信息
        $ret = $companyBrandspread_Service->setData($spread_id , $this->_userid , $CheckData['data']);
        $json = array('status'=>true, 'msg'=> '修改成功！');
        echo json_encode($json);return;
    }

    /*
     * 获取企业品牌推广受众条件
     * */
    public function pagegetbrandinfo($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

        $spread_id = base_lib_BaseUtils::getStr($pathdata['spread_id'] , 'int', '');

        if(empty($spread_id)){
            return json_encode(array('status'=> false , 'msg' => '参数错误！' , 'data'=> null));
        }
        $condition =  array();
        $condition['spread_id'] = $spread_id;
        $brandSpreadService = new base_service_company_spread_companybrandspread();
        $brandinfo = $brandSpreadService->getDataOne($condition , 'area_ids,degree_ids,work_year_ids,sex,age_lower,age_upper');

        if(!empty($brandinfo)){
            $brandData = array();
            $brandData['area_ids_arr'] = $this->getAreasName(explode(',',$brandinfo['area_ids']));
            $brandData['degree_ids_arr'] = $this->getDegreesName(explode(',',$brandinfo['degree_ids']));
            $brandData['work_year_ids_arr'] = $this->getWorkYearNames(explode(',',$brandinfo['work_year_ids']));
            if($brandinfo['age_lower']==0 && $brandinfo['age_upper'] == 0){
                $brandData['age'] = '不限';
            }else{
                $brandData['age'] = $brandinfo['age_lower'].'-'.$brandinfo['age_upper'];
            }

            switch($brandinfo['sex']){
                case 0:
                    $brandData['sexstring'] =  '不限';break;
                case 1:
                    $brandData['sexstring'] =  '男';break;
                case 2:
                    $brandData['sexstring'] = '女';break;
            }

            return json_encode(array('status'=> true , 'msg' => '操作成功！' , 'data'=> $brandData));
        }
    }

    /*
     * 初始化新增/修改的参数
     * */
    private function reader(){
        $this->_aParams['arealist'] = $this->getAreaList();
        $this->_aParams['degreelist'] = $this->getDegreeList();
        $this->_aParams['workyearlist'] = $this->getWorkYearList();
        $this->_aParams['cur'] = '精准推广';
        //设置上传插件参数
        $this->_aParams['upload_cookie_userid']= $this->_userid;
        $this->_aParams['upload_cookie_nickname']= $this->_username;
        $this->_aParams['upload_cookie_usertype']= $this->_usertype;
        $this->_aParams['upload_cookie_userkey']= base_lib_BaseUtils::getCookie('userkey');
        $this->_aParams['upload_cookie_tick']= base_lib_BaseUtils::getCookie('tick');
        $this->_aParams["upload_cookie_accountid"] = base_lib_BaseUtils::getCookie('accountid');
        //设置上传图片参数
        $this->_aParams['ImageConfig'] = $this->BrandImageConfig();
    }
    /*
     * 验证数据
     * */
    private function chcekData($pathdata){
        $validata = new base_lib_Validator();
        $data = array();
        $data['title'] = $title = $validata->getStr($pathdata['title'],0, 20, '广告名称必须为0-20个字');
        $data['spread_image'] = $spread_image = $validata->getStr($pathdata['spread_image'] , 0, 0 ,'请选择广告图片');
        $data['url'] = $url = $validata->getStr($pathdata['url'] ,0 , 0, '请输入目标链接');
        $data['bid'] = $bid = $validata->getNum($pathdata['bid'], 5 , 0, '最低出价不能小于5元');
        $data['budget'] = $budget = $validata->getNum($pathdata['budget'], 5 , 0 , '最低预算不能小于5元');
        $data['last_budget'] = $budget;
        $data['area_ids'] = $area_ids = $validata->getStr($pathdata['area_ids'] , 0 , 0, '请选择地区');
        $data['degree_ids'] = $degree_ids = $validata->getStr($pathdata['degree_ids'], 0 , 0 , '请选择学历');
        $data['work_year_ids'] = $workyear_ids = $validata->getStr($pathdata['workyear_ids'], 0, 0 ,'请选择工作经验');
        $data['age_lower'] = $age_lower = base_lib_BaseUtils::getStr($pathdata['age_lower'], 'string' , 0);
        $data['age_upper'] = $age_upper = base_lib_BaseUtils::getStr($pathdata['age_upper'], 'string' , 0);
        $data['sex'] = $sex = $validata->getNum($pathdata['sex'] ,0 , 2,  '请选择性别');

        $ret = array();
        $ret['status'] = true;
        $ret['msg'] = '';
        $ret['data'] = $data;
        if($validata->has_err){
            $ret['status'] = false;
            $ret['msg'] = implode(';',$validata->err);
        }
        return $ret;
    }

    /*
     * 返回图片上传配置
     * return array()
     * */
    private function BrandImageConfig(){
        $xml = SXML::load('../config/company/company.xml');
        $imageArr =  array();
        if(!is_null($xml)){
            $VirtualName = $xml->VirtualName;
            $imageArr = array();

            $imageArr['ImagePath'] = $VirtualName.'/'.$xml->brandspreadImagePath;
            $imageArr['ImageTempPath'] = $VirtualName.'/'.$xml->brandspreadTempPath;
            $imageArr['ImageType'] = $xml->brandspreadType;
            $imageArr['ImageTypeWeb'] = $xml->brandspreadTypeWeb;
            $imageArr['ImageCount'] = $xml->brandspreadCount;
            $imageArr['ImageSize'] = $xml->brandspreadSize;
        }
        return $imageArr;
    }

    /*
     * 上传临时图片
     * */
    public function pageaddTempImage($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $file = array_pop($_FILES);
        $files = array();
        if(is_array($file['name'])){
            foreach($file['name'] as $key=>$item){
                $temp_file = array();
                $temp_file['name'] = $item;
                $temp_file['type'] = $file['type'][$key];
                $temp_file['tmp_name'] = $file['tmp_name'][$key];
                $temp_file['error'] = $file['error'][$key];
                $temp_file['size'] = $file['size'][$key];
                $files[] = $temp_file;
            }
        }else{
            $files = $file;
        }

        if (empty($files)) {
            $result['error'] = 100;return json_encode($result);
        }

        $success = 0;
        $BrandImageConfig = $this->BrandImageConfig();

        // 读取配置xml文件
        $result  = false;

        //验证文件大小
        if($files['size']/1024 > $BrandImageConfig['ImageSize'] || $files['size'] == 0){
            $result['error'] = 101;return json_encode($result);
        }
        //验证后缀名
        $extension_name = base_lib_BaseUtils::fileext($files['name']);

        if(stripos($BrandImageConfig['ImageType'],strtolower($extension_name)) === false){
            $result['error'] = 102;return json_encode($result);
        }

        //原名
        $old_file_name = base_lib_BaseUtils::cutstr(str_replace('.'.$extension_name,'',$files['name']),30);
        $new_file_name = date('mdHis').floor(microtime()*1000).'_'.base_lib_BaseUtils::random(3,1).'.'.$extension_name;

        $postvar['path'] = $BrandImageConfig['ImagePath'];//存放路径 配置文件件读取
        $postvar['name'] = $new_file_name;
        $postvar['type'] = 'file';

        //调用方法上传 返回文件处理条数
        $success = base_lib_Uploadfilesv::postfile($postvar, $files['name'],$files['tmp_name']);

        $imagepath = base_lib_Constant::STYLE_URL.'/'.$BrandImageConfig['ImagePath'].'/'.$new_file_name;
        $imageinfo = getimagesize($imagepath);

        $tempimageWidth = $imageinfo[0];
        $tempimageHeight = $imageinfo[1];

        if($tempimageWidth != 750 || $tempimageHeight != 90){
            $result['error'] = 105;
            return json_encode($result);
        }

        //判断文件上传成功的数量 > 0 则成功
        if($success>0){

            //上传七牛云存储
            $qiniu = new SQiniu();
            $path = rtrim($postvar['path'],'/');
            $temp_thumb_suffix = empty($postvar['thumbSuffix'])?'thumb':$postvar['thumbSuffix'];
            $postvar["key_thumb"] = $path."/".str_replace('.',$temp_thumb_suffix.".",$new_file_name);
            $qiniu->upload2qiniu($path."/{$new_file_name}", $file['tmp_name'], base_lib_Constant::QINIU_BUCKET, $postvar);

            $arr['name'] = $old_file_name.'.'.$extension_name;
            $arr['newname'] = $new_file_name;
            $arr['path'] = base_lib_Constant::UPLOAD_FILE_URL.'/'.$BrandImageConfig['ImagePath'].'/'.$new_file_name; //base_lib_Constant::UPLOAD_FILE_URL.'/'.$path.'/'.$postvar['name'];
            $arr['size'] = $file['size'];
            return json_encode($arr);
        }else{
            $result['error'] = 104;
            return json_encode($result);
        }
    }

    /*
     * 将文件移动到正式路径下
     * @params array $filename
     * */
    private function brandImageToFormalPath($filename){
        $config = $this->BrandImageConfig();
        if(empty($filename)){
            return false;
        }
        if(is_string($filename)){
            $names= array($filename);
        }

        //将LOGO移动到正式目录中
        $postvar['names'] = $names;
        $postvar['newfile'] = "{$config['ImagePath']}";
        $postvar['oldfile'] = "{$config['ImageTempPath']}";
        $postvar['thumbSuffix'] = '';
        $postvar['authenticate'] = "brandspreadImage";

        $result_move = base_lib_Uploadfilesv::moveFile($postvar);
        return $result_move;
    }

    private function getAreaList(){
        $area_service = new base_service_common_area();
        $openCity = array('0300');//重庆主城区
        $openCity = array_merge($openCity , $area_service->getCQSmallCityAll());//重庆区县
        $openCity = array_merge($openCity , $area_service->getBigCity());//省外大城市
        $openCity = array_merge($openCity , $area_service->getSmallCity());//省外小城市

        array_unique($openCity);
        $openCityList = $area_service->getAreaByAreaID($openCity);
        return $openCityList;
    }
    /*
     * 获取多个地区的地址名称
     * @params array $area_ids
     * returan string
     * */
    private function getAreasName($area_ids){
        if(empty($area_ids) || !is_array($area_ids)){
            return '';
        }
        $area_names = array();
        $area_service = new base_service_common_area();
        foreach($area_ids as $areaitem){
            $area_names[] = $area_service->getArea($areaitem);
        }
        return implode(',', $area_names);
    }

    /*
     * 获取学历集合
     * */
    private function getDegreeList(){
        $degree_service = new base_service_company_spread_codespreaddegree();
        return $degree_service->getDegreeCode();
    }

    /*
     * 获取学历名称
     * @params array $degree_ids
     * */
    private function getDegreesName($degree_ids){
        if(empty($degree_ids) || !is_array($degree_ids)){
            return '';
        }
        $degree_names = array();
        $degree_service = new base_service_company_spread_codespreaddegree();
        foreach($degree_ids as $degree_id){
            $degree_names[] = $degree_service->getDegreeName($degree_id);
        }
        return implode(',',$degree_names);
    }

    /*
     * 获取工作经验集合
     * */
    private function getWorkYearList(){
        $WorkYear_service = new base_service_company_spread_codespreadworkyear();
        return $WorkYear_service->getWrokYearCode();
    }
    /*
     * 获取多个工作经验的名称
     * @params array $work_year_ids
     * */
    private function getWorkYearNames($work_year_ids){
        if(empty($work_year_ids) || !is_array($work_year_ids)){
            return '';
        }
        $work_year_names = array();
        $workyear_service = new base_service_company_spread_codespreadworkyear();
        foreach($work_year_ids as $work_year_id){
            $work_year_names[] = $workyear_service->getWorkyearName($work_year_id);
        }
        return implode(',',$work_year_names);
    }

    /*
     * 获取广告位集合
     * */
    private function getBannerpositionList(){
        $Bannerposition_service = new base_service_company_spread_codebannerposition();
        return $Bannerposition_service->getBannerpositionCode();
    }

    /*
     * 查询质量分
     * */
    private function getQualityScore($company_id){

        $breadSpread_Service = new base_service_company_spread_companybrandspread();
        $condition = array();
        $condition['company_id'] = $company_id;
        $items = "FORMAT(SUM(quality_score)/count(1),3) Average,company_id";
        $Average = $breadSpread_Service->getDataOne($condition,$items);
        if(empty($Average['Average'])){
            return 1;
        }
        return $Average['Average'];
    }
}