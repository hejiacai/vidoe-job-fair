<?php
/**
 * 
 * @ClassName controller_appraise
 * @Desc 我的面试评价
 * @author huangwentao@huibo.com
 * @date 2016-9-26 下午01:51:47
 */
class controller_appraise extends components_cbasepage{
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}
    
    public function pageIndex($inPath){
        $path_data          = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $cur_page           = base_lib_BaseUtils::getStr($path_data['page'], 'int', 1);
		$page_size          = base_lib_BaseUtils::getStr($path_data['pageSize'], 'int', base_lib_Constant::PAGE_SIZE);
        $is_complain         = base_lib_BaseUtils::getStr($path_data["is_complain"],"int",0);
        $service_appraise   = new base_service_person_appraise_appraise();
        $service_resource   = new base_service_company_resources_resources($this->_userid);
        $company_ids        =$service_resource->all_accounts;
        
        if(!$is_complain)
            $this->_aParams['loadActionType'] = 404;
        else if($is_complain == 1)
            $this->_aParams['loadActionType'] = 405;

        if($path_data['page'])
            $this->_aParams['loadActionType'] = false;
        
        $appraise_object    = $service_appraise->getCompanyAppraiseList($cur_page, $page_size, $company_ids,"appraise_id,person_id,job_id,company_id,station,jobsort,create_time,appraise_time"
            . ",useful_count,com_reply_content,content,com_reply_time,complain_status,match_level,experience_level,complain_time,complain_reason_id,complain_reason_content,complain_check_time,"
            . "is_com_read,tips,link_order_id,check_state,welfare_salary_level",$is_complain);
        $appraise_list      = $appraise_object->items;
        $total              = $appraise_object->totalSize;

        $this->_aParams["is_complain"] = $is_complain;
        $set_read_ids                   = array(); //设置成已读
        if(!empty($appraise_list)){
            $person_ids         = base_lib_BaseUtils::getPropertys($appraise_list, "person_id");
            $service_person     = new base_service_person_person();
            $person_infos       = $service_person->getPersons($person_ids, "person_id,user_name,photo,small_photo")->items;
            $person_list        = base_lib_BaseUtils::array_key_assoc($person_infos, "person_id");
            //获取所有理由编号
            $complain_ids       = base_lib_BaseUtils::getPropertys($appraise_list,"link_order_id");
            $complain_list      = array();
            if(!empty($complain_ids)){
                $service_workorder  = new base_service_report_report();
                $workorder_info     = $service_workorder->getReportInfoByIds($complain_ids, "id,station_letter");
                $workorder_list     = base_lib_BaseUtils::array_key_assoc($workorder_info, "id");
            }
            //获取所有tips
            $service_tips   = new base_service_person_appraise_tip();
            $all_tips       = $service_tips->getAll("tip_id,tip_name");
            $tip_list       = base_lib_BaseUtils::array_key_assoc($all_tips, "tip_id");
            $all_complan    = base_service_person_appraise_appraise::getComplainType();

            //最后申述时间，评价时间超过30天不能申述
            $last_complaint_time = date("Y-m-d",strtotime("-30 days"));

            foreach($appraise_list as $k => $v){
                $person                                         = $person_list[$v["person_id"]];
                $appraise_list[$k]["person_user_name"]          = $person["user_name"];
                //个人头像
                if (!base_lib_BaseUtils::nullOrEmpty($person['small_photo'])) {
                    $appraise_list[$k]['person_small_photo']    = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person['small_photo'];
                    
                } elseif (!base_lib_BaseUtils::nullOrEmpty($person['photo'])) {
                    $appraise_list[$k]['person_small_photo']    = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person['photo'];
                }
                if (!base_lib_BaseUtils::nullOrEmpty($person['photo'])){
                    $appraise_list[$k]['person_photo']              = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . $person['photo']; 
                }
                //是否能申述  1能 0 否
                $appraise_time = date("Y-m-d",strtotime($v['appraise_time']));
                $appraise_list[$k]['is_can_complaint'] = 1;
                if($last_complaint_time > $appraise_time){
                    $appraise_list[$k]['is_can_complaint'] = 0;
                }

                //企业申诉回复
                $appraise_list[$k]['complain_reply']            = !empty($workorder_list[$v["link_order_id"]]) ?  $workorder_list[$v["link_order_id"]]["station_letter"] : "";
                //tip
                $appraise_list[$k]["tip_array"]                 = $this->getTips($tip_list, $v["tips"]);
                //complain_reason_id
                //complain_reason_type
                $appraise_list[$k]["complain_reason_type"]      = !empty($all_complan[$v["complain_reason_id"]]) ? $all_complan[$v["complain_reason_id"]] : "";
                if($v["is_com_read"] ==0){
                    $set_read_ids[] = $v["appraise_id"]; 
                }
            }
        }
        //获取所有申诉分类
        $all_complan                        = base_service_person_appraise_appraise::getComplainType();
        $this->_aParams["complans"]         = $all_complan;
        //判断企业是否开启面试评价
        $service_company                    = new base_service_company_company();
        $company_info                       = $service_company->getCompany($this->_userid, 1, "company_id,is_allow_appraise");
        $this->_aParams["is_allow_appraise"]= $company_info["is_allow_appraise"] ==1 ? true : false;
        //分页
        $pager              = $this->pageBar($total, $page_size, $cur_page, $inPath,'style2');
        $this->_aParams['pager']            = $pager;
        $this->_aParams["appraise_list"]    = $appraise_list;
        $this->_aParams["total"]            = $total;
        $this->_aParams["title"]            = "面试评价管理";
        
        //设置已读
        if(!empty($set_read_ids)){
            $service_appraise->setCompanyRead($this->_userid,$set_read_ids);
        }
        return $this->render("./appraise/appraise_list.html",$this->_aParams);
    }
	
    /**
     *@desc 获取标签 
     */
    private function getTips($all_tips,$tip_string){
        if(empty($all_tips) || empty($tip_string)){
            return array();
        }
        $tip_array = explode(",", $tip_string);
        $return = array();
       // var_dump($tip_array);
        foreach($tip_array as $tip_id){
            if(!empty($all_tips[$tip_id])){
                $return[] = $all_tips[$tip_id]["tip_name"];
            }
        }
        return $return;
    }

    public function pageComplanAppraise($inPath){
        $path_data      = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $appraise_id    = base_lib_BaseUtils::getStr($path_data["appraise_id"],"int",0);
        $content        = base_lib_BaseUtils::getStr($path_data["textarea"],"string","");
        $radio          = base_lib_BaseUtils::getStr($path_data["radio"], "int" , 0);
        $validator      = new base_lib_Validator();
        $content        = $validator->getStr($content, "1","500","申诉的具体理由为1-500字");
        if($appraise_id == 0){
            $validator->addErr("缺少必要参数");
            $validator->has_err = true;
        }
        if($radio == 0){
            $validator->addErr("请选择申诉类型");
            $validator->has_err = true;
        }
        $all_complan    = base_service_person_appraise_appraise::getComplainType();
        if(empty($all_complan[$radio])){
            $validator->addErr("申诉类型错误");
            $validator->has_err = true;
        }
        
        if ($validator->has_err) {
    		echo header("Content-type:text/plain;charset=utf-8");
			echo $validator->toJsonWithHtml();
			return;
    	}
        $service_appraise                   = new base_service_person_appraise_appraise();
        $appraise_info                      = $service_appraise->getAppraiseById($appraise_id, "company_id,person_id,complain_status,content,job_id,station,appraise_time");
        $service_resource   = new base_service_company_resources_resources($this->_userid);
        $company_ids        =$service_resource->all_accounts;
        if(empty($appraise_info) || !in_array($appraise_info["company_id"],$company_ids)){
            echo json_encode(array("error"=>"该评论已删除"));exit;
        }
        if($appraise_info["complain_status"] != 0){
            echo json_encode(array("error"=>"已经申诉过了，不能重复申诉"));exit;
        }

        //最后申述时间，评价时间超过30天不能申述
        $last_complaint_time = date("Y-m-d",strtotime("-30 days"));
        $appraise_time = date("Y-m-d",strtotime($appraise_info['appraise_time']));
        if($last_complaint_time > $appraise_time){
            echo json_encode(array("error"=>"已超过申诉期限"));exit;
        }

        //获取个人信息
        $service_person = new base_service_person_person();
        $person_infos = $service_person->getPersonByPersonID($appraise_info["person_id"],'person_id,user_name');
        $service_company                    = new base_service_company_company();
        $company_info                       = $service_company->getCompany($appraise_info["company_id"], 1, "company_name,company_shortname,link_tel,email");
        $sev                                = new base_service_report_report();
		$items = array();
        $items['product_type']              = (new base_lib_BaseUtils)->GetBrowser();
		$items['order_type']                = '9';
		$items['obj_id']                    = $appraise_id;//评价ID
		$items['obj_name']                  = base_lib_BaseUtils::cutstr($appraise_info["content"], "10" ,"utf-8","","...");//内容截取100字
		$items['obj_belongs_id']            = $person_infos["person_id"];//person_id
        $items['obj_belongs_name']       = $person_infos['user_name'];
        $items['obj_belongs_type']       = 1;
		$items['title']                     = $all_complan[$radio]; //标题
		$items['content']                   = $content;//举报内容
		$items['source_obj_type']           = '2';
		$items['source_obj_id']             = $appraise_info["company_id"];
		$items['source_obj_name']           = $company_info['company_name'];//企业全称
		$items['email']                     = $company_info['email'];//企业邮箱
		$items['tel']                       = $company_info['link_tel'];//企业电话
        
		$res = $sev->addreport($items);
        if($res ===false){
            echo json_encode(array("error"=>"申诉失败，请重试"));exit;
        }
        //修改申诉
        $complain_items = array();
        $complain_items["complain_status"]           = 3;//申诉中...
        $complain_items["complain_reason_content"]   = $content;
        $complain_items["complain_time"]             = date("Y-m-d H:i:s");
        $complain_items["link_order_id"]             = $res;
        $complain_items["complain_reason_id"]        = $radio;
        
        $result = $service_appraise->companyComplain($appraise_id, $appraise_info["company_id"], $complain_items);
        if($result !==false){
            //申诉成功发送QQ消息给销售人员
            $companyid = $appraise_info["company_id"];
            $userservice = new base_service_boss_user();
            $infoComState = new base_service_company_comstate();
            $OwnMain = $infoComState->getCompanyStaeOwnMain($companyid);
            if(!empty($OwnMain['own_man'])){
                //根据面试评价获取职位信息
                $station = $appraise_info['station'];
                $tomans = array($OwnMain['own_man']);
                $title="企业申诉通知";
                $content = $company_info["company_name"].'公司针对“'.$station.'”职位的评价进行了申诉，申诉内容如下：'.$content;
                $result = $userservice->sendMsg("workorder_msg", $tomans, $title, $content, null);
            }

            echo json_encode(array("success"=>"申诉成功"));exit;
        }else{
            echo json_encode(array("success"=>"申诉失败"));exit;
        }
    }
    
    
    /**
     *@desc 回复面试评价 
     */
    public function pageReplyAppraise($inPath){
        $path_data          = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $appraise_id        = base_lib_BaseUtils::getStr($path_data["appraise_id"],"int",0);
        $content            = base_lib_BaseUtils::getStr($path_data["reply"],"string","");
        $validator          = new base_lib_Validator();
        $content            = $validator->getStr($content, "1","500","回复内容为1-500字");
        if($appraise_id == 0){
            $validator->addErr("缺少必要参数");
            $validator->has_err = true;
        }
       if ($validator->has_err) {
    		echo header("Content-type:text/plain;charset=utf-8");
			echo $validator->toJsonWithHtml();
			return;
    	}
        $hrinfo = base_service_company_resources_resources::getInstance($this->_userid,false);;
        $company_ids = $hrinfo->all_accounts;

        $service_appraise   = new base_service_person_appraise_appraise();
        $result             = $service_appraise->companyReply($appraise_id, $company_ids, $content);
        if($result === false ){
            echo json_encode(array("error"=>"回复失败"));exit;
        }else{
            echo json_encode(array("success"=>"回复成功"));exit;
        }
    }
    
    /**
     * @desc 修改是否开启面试评价
     */
    public function pageChangeAppeal($inPath){
        $path_data          = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $value              = base_lib_BaseUtils::getStr($path_data["value"],"int",0);
        
        if($value !=1 && $value !=0){
            echo json_encode(array("error"=>"设置失败"));exit;
        }
        $text               = $value ==1 ? "开启" : "关闭";
        $service_company    = new base_service_company_company();
        $result = $service_company->setIsAllowAppraise($this->_userid,$value);
        if($result ===false){
            echo json_encode(array("error"=>"{$text}面试评价失败"));exit;
        }
        echo json_encode(array("success"=>"{$text}面试评价成功"));exit;
    }
    public function pagehowtoreply(){
        echo $this->render('howtoreply.html');
    }
}
?>