<?php
/**
 * @Desc:	
 * @Author: zhangfangjun@huibo.com
 * @Date:   2015-07-30 13:26:04
 * @Last Modified by:   zhangfangjun - Administrator
 * @Last Modified time: 2015-10-09 16:54:38
 * @Copyright (c) http://www.huibo.com All rights reserved.:
 */
class controller_partcomment extends components_cbasepage{

	function __construct(){
		parent::__construct(true,'part');
		$this->AjaxLoadPageSize = 3;
	}

	//企业收到的评价
	function pageIndex($inPath){
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$pageNo = base_lib_BaseUtils::getStr($pathdata['page'],'int',1);
		$pageSize = base_lib_Constant::PAGE_SIZE;

		$person_service = new base_service_person_person();
		$comment_service = new base_service_part_company_companycomment();
		$score_service = new base_service_common_part_companyscoretolevel();
		$offer_service = new base_service_part_company_joboffered();


		$condition['company_id'] = $this->_userid;
		$condition['status'] = array('in'=>array(1,2,4));
		$field = "assess_id,company_id,person_id,level,content,score,blame_id,offer_id,status,link_assess_id,create_time";
		//$field .= ",link_assess_id = (case when link_assess_id=0 then 0 when link_assess_id<>0 then 1 end) as tt";
		$order = "order by create_time desc";
		$comment_list = $comment_service->getPageList($condition,$field,$pageNo,$pageSize,null,$order); //die();
		$pager = $this->pageBar($comment_list->totalSize, $pageSize, $pageNo, $inPath);
		$dataLst = $comment_list->items;
		$hdata = array();
		foreach ($dataLst as $key => $value) {
			if($value['link_assess_id'] == 0){
				$hdata[] = $value;
				unset($dataLst[$key]);
			}
		}
		$hdata = base_lib_BaseUtils::multi_array_sort($hdata,'create_time',SORT_DESC);
		
		$dataLst = array_merge($hdata,$dataLst);
		$person_ids = base_lib_BaseUtils::getProperty($dataLst,'person_id');
		if(!base_lib_BaseUtils::nullOrEmpty($person_ids)){
			$persons = $person_service->select(array("person_id"=>array('in'=>$person_ids)),'person_id,user_name,small_photo,photo_open')->items;
			$dataLst = base_lib_BaseUtils::compareAdd($dataLst,$persons,'person_id','user_name,small_photo,photo_open');			
		}

		$offer_ids = array();
		foreach ($dataLst as $key => $value) {
			if(empty($value['blame_id']) && $value['level'] == 3)
				$offer_ids[] = $value['offer_id'];
		}
		if(!base_lib_BaseUtils::nullOrEmpty($offer_ids)){

			$join = array(" LEFT JOIN info_part_job"=>"info_part_joboffered.job_id = info_part_job.job_id");
			$offers = $offer_service->select(array("info_part_joboffered.offer_id"=>array('in'=>$offer_ids)),'info_part_joboffered.offer_id,info_part_joboffered.offer_time,info_part_joboffered.create_time as offer_create_time,info_part_job.station',null,null,$join)->items;
			$dataLst = base_lib_BaseUtils::compareAdd($dataLst,$offers,'offer_id','offer_time,offer_create_time,station');			
		}
		$count = base_lib_BaseUtils::array_key_assoc($comment_service->getCommentCount($this->_userid),'level');
		$total_score = 100;//初始分数100分
		foreach ($count as $key => $value) {
			$total_score+=$value['score'];
		}
		$company['level'] = $score_service->getLevel($total_score,true);
		$company['info']['score'] = $total_score;

		$ranking = $comment_service->getScoreRanking($this->_userid,$total_score-100);//die;
		if($ranking>10 && $ranking<=50){
			$ranking+=50;
		}elseif($ranking>50 && $ranking<=100){
			$ranking+=100;
		}elseif($ranking>100 && $ranking<=500){
			$ranking+=500;
		}elseif($ranking>500 && $ranking<=1000){
			$ranking+=1000;
		}elseif($ranking>1000){
			$ranking+=2000;
		}		
		
		$company['count'] = $count;// var_dump($ranking,$this->_userid,$total_score);
		$company['ranking'] = $ranking;

		$this->_aParams['title'] = "汇博兼职-公司评价";
		$this->_aParams['count'] = $count;
		$this->_aParams['company'] = $company;
	    $this->_aParams['dataLst'] = $dataLst; //var_dump($dataLst);
	    $this->_aParams['pager'] = $pager;
	    $this->_aParams['default_photo'] = base_lib_Constant::STYLE_URL."/img/c/new_resume/headportrait.png";
	    $this->_aParams['pic_baseUrl'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP;
	    $this->updateSeeCommentTime();
		return $this->render('./part/comment/comment.html', $this->_aParams);	
	}

	// 企业给求职者的评价
	function pageMycomment($inPath){
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$pageNo = base_lib_BaseUtils::getStr($pathdata['page'],'int',1);
		$pageSize = base_lib_Constant::PAGE_SIZE;

		$person_service = new base_service_person_person();
		$person_comment_service = new base_service_part_person_personcomment();
		$offer_service = new base_service_part_company_joboffered();
		$level =  new base_service_common_part_level();

		$condition = array();
		$condition['company_id'] = $this->_userid;
		$condition['status'] = array('in'=>array(1,2,4));

		$field = "assess_id,company_id,person_id,level,content,score,blame_id,offer_id,status,link_assess_id,create_time";
		$order = "order by create_time desc";
		$comment_list = $person_comment_service->getPageList($condition,$field,$pageNo,$pageSize,null,$order);
		$pager = $this->pageBar($comment_list->totalSize, $pageSize, $pageNo, $inPath);
		$dataLst = $comment_list->items;
		//var_dump($dataLst);
		$needFields = "job_id,person_id,company_id,create_time,offer_time,status,offer_id";
		$needComments = $offer_service->getCompanyNeedToComment($this->_userid,$needFields,$this->AjaxLoadPageSize,1);
		$needCommentList = $needComments->items;
		$person_ids = base_lib_BaseUtils::getProperty(array_merge($needCommentList,$dataLst),'person_id');

		if(!empty($person_ids)){
			//$person_ids = implode(',', $person_ids);
			$names = $person_service->select(array("person_id"=>array('in'=>$person_ids)),'person_id,user_name')->items;
			$needCommentList = base_lib_BaseUtils::compareAdd($needCommentList,$names,'person_id','user_name');
			$dataLst = base_lib_BaseUtils::compareAdd($dataLst,$names,'person_id','user_name');
		}
		
		$this->_aParams['title'] = "汇博兼职-公司评价";
	    $this->_aParams['needComments'] = $needCommentList;
	    $this->_aParams['cur_page'] = intval($needComments->page);
		$this->_aParams['total_page'] = intval($needComments->totalPage);
	    $this->_aParams['dataLst'] = $dataLst;
	    $this->_aParams['pager'] = $pager;
	    $this->_aParams['default_photo'] = base_lib_Constant::STYLE_URL."/img/c/new_resume/headportrait.png";
	    $this->_aParams['pic_baseUrl'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP;
	    $this->updateSeeCommentTime();
		return $this->render('./part/comment/mycomment.html', $this->_aParams);
	}

	function pageAjaxloadmoreneed($inPath){
		$pageNo = base_lib_BaseUtils::getStr($pathdata['page'],'int',2);
		$pageSize = $this->AjaxLoadPageSize;

		$offer_service = new base_service_part_company_joboffered();
		$person_service = new base_service_person_person();

		$needFields = "job_id,person_id,company_id,create_time,status,offer_id";
		$needCommentsobj = $offer_service->getCompanyNeedToComment($this->_userid,$needFields,$pageSize,$pageNo);
		$needComments = $needCommentsobj->items;
		$person_ids = base_lib_BaseUtils::getProperty($needComments,'person_id');
		if(!empty($person_ids)){
			//$person_ids = implode(',', $person_ids);
			$names = $person_service->select(array("person_id"=>array('in'=>$person_ids)),'person_id,user_name')->items;
			$needComments = base_lib_BaseUtils::compareAdd($needComments,$names,'person_id','user_name');
		}
		foreach ($needComments as &$value) {
			$value['before_time'] = date('m月d日',strtotime('+1 month',strtotime($value['create_time'])));
		}
		$json['status'] = 1;
		$json['dataLst'] = $needComments;
		$json['cur_page'] = intval($needCommentsobj->page);
		$json['total_page'] = intval($needCommentsobj->totalPage);

		echo json_encode($json);
		return;

	}

	function pageScoreLevelMap($inPath){
		$score_service = new base_service_common_part_companyscoretolevel();
		$level_service = new base_service_common_part_companylevel();
		$data = $score_service->getAll();
		$this->_aParams["level"] = $level_service->getAll();
		$this->_aParams["list"] = $data;
		return $this->render('./part/comment/socrelevelmap.html', $this->_aParams);
	}



    /**
     * 企业评价用户
     * @update 2018-11-9 支持批量评论 传值：offer_id ='1,2,3'  person_id ='11,22,33' 按照下标对应即可
     */
	function pageAddcommentDo($inPath){

	    $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$offer_id = base_lib_BaseUtils::getStr($path_data['offer_id'],'string',null);
		$person_id = base_lib_BaseUtils::getStr($path_data['person_id'],'string',null);
		$level = base_lib_BaseUtils::getStr($path_data['level'],'int',0);	
		$content = base_lib_BaseUtils::getStr($path_data['content'],'string','');

		$level_service = new base_service_common_part_personlevel();

		$json = array();	
		if(!$person_id || !$offer_id){
			$json['status'] = 0;
			$json['error'] 	= '参数错误';
			echo json_encode($json);
			return;
		}
		if(!in_array($level,array(1,2,3))){
			$json['status'] = 0;
			$json['error'] 	= '请选择好中差评';
			echo json_encode($json);
			return;
		}
		if(in_array($level,array(1,2))){
			if(base_lib_BaseUtils::nullOrEmpty($content)){
				$content = $level_service->getLevelDesc($level);
			}elseif(mb_strlen($content)>500){
				$json['status'] = 0;
				$json['error'] 	= '详细评价请保持500字以内';
				echo json_encode($json);
				return;
			}
		}else{
			if((mb_strlen($content)<10) || (mb_strlen($content)>500)){
				$json['status'] = 0;
				$json['error'] 	= '差评请填写详细评价（10-500个字以内）';
				echo json_encode($json);
				return;
			}
		}
		$person_comment_service = new base_service_part_person_personcomment();
		$company_comment_service = new base_service_part_company_companycomment();
		$person_app_service = new base_service_part_person_app();
		$offer_service = new base_service_part_company_joboffered();

        $offer_id = explode(',',$offer_id);
        $person_id = explode(',',$person_id);
        foreach ($offer_id as $k=>$v){

            //判断是否已经评论
            $is_company_assess = $offer_service->selectOne(array("offer_id"=>$offer_id[$k]),'is_company_assess')['is_company_assess'];
            if($is_company_assess){
                $json['status'] = 1;
                $json['success'] = '提交成功';
                continue;
            }

            $company_assess = $company_comment_service->selectOne(array("offer_id"=>$offer_id[$k]),'assess_id,link_assess_id');
            $comment_arr = array(
                "offer_id"		=>	$offer_id[$k],
                "level"			=>	$level,
                "score"			=>	$level_service->getScore($level),
                "company_id"	=>	$this->_userid,
                "link_assess_id"=>	empty($company_assess['assess_id'])?0:$company_assess['assess_id'],
                'status'		=>	1,
                'content'		=>	$content,
                'person_id'		=>	$person_id[$k],
                'create_time'	=>	date("Y-m-d H:i:s")
            );

            if(false !== $assess_id = $person_comment_service->insert($comment_arr)){

                $offer_service->update(array("offer_id"=>$offer_id[$k]),array('is_company_assess'=>1));
                $person_app_service->setnew($person_id[$k], $type = 2);

                if(empty($company_assess['link_assess_id'])){
                    $company_comment_service->update(array("offer_id"=>$offer_id[$k]),array('link_assess_id'=>$assess_id));
                }

                $action_log = new base_service_stat_partaction();
                $action_log->addAction($company_id,base_service_stat_partaction::ACTION_USER_COMPANY,
                    base_service_stat_partaction::ACTION_TYPE_RE_ASSESS,base_service_stat_partaction::ACTION_SOURCE_WEB
                );

                $json['status'] = 1;
                $json['success'] = '提交成功';
            }else{
                $json['status'] = 0;
                $json['error'] = '提交失败';
            }

        }


		/*$company_assess = $company_comment_service->selectOne(array("offer_id"=>$offer_id),'assess_id,link_assess_id');
		$comment_arr = array(
			"offer_id"		=>	$offer_id,
			"level"			=>	$level,
			"score"			=>	$level_service->getScore($level),
			"company_id"	=>	$this->_userid,
			"link_assess_id"=>	empty($company_assess['assess_id'])?0:$company_assess['assess_id'],
			'status'		=>	1,
			'content'		=>	$content,
			'person_id'		=>	$person_id,
			'create_time'	=>	date("Y-m-d H:i:s")
			);
		
		if(false !== $assess_id = $person_comment_service->insert($comment_arr)){
			
			$offer_service->update(array("offer_id"=>$offer_id),array('is_company_assess'=>1));
			$person_app_service->setnew($person_id, $type = 2);
			
			if(empty($company_assess['link_assess_id'])){				
				$company_comment_service->update(array("offer_id"=>$offer_id),array('link_assess_id'=>$assess_id));
			}

			$action_log = new base_service_stat_partaction();
			$action_log->addAction($company_id,base_service_stat_partaction::ACTION_USER_COMPANY,
				base_service_stat_partaction::ACTION_TYPE_RE_ASSESS,base_service_stat_partaction::ACTION_SOURCE_WEB	
			);

			$json['status'] = 1;
			$json['success'] = '提交成功';
		}else{
			$json['status'] = 0;
			$json['error'] = '提交失败';
		}*/



		echo json_encode($json);
		return;
	}


	// 申诉处理
	function pageComplainDo($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$comment_id = base_lib_BaseUtils::getStr($path_data['comment_id'],'int',0);	
		$person_id = base_lib_BaseUtils::getStr($path_data['person_id'],'int',0);	
		$offer_id = base_lib_BaseUtils::getStr($path_data['offer_id'],'int',0);	
		$blame_content = base_lib_BaseUtils::getStr($path_data['content'],'string','');
		$json = array();	
		if(!$comment_id || !$person_id || !$offer_id){
			$json['error'] = '参数错误';
			echo json_encode($json);
			return;
		}
		if((base_lib_BaseUtils::nullOrEmpty(trim($blame_content)))||(mb_strlen($blame_content)>500)){
			$json['error'] = '请填写申诉内容（500个字以内）';
			echo json_encode($json);
			return;
		}
		$blame_service = new base_service_part_company_blame();
		$company_comment_service = new base_service_part_company_companycomment();
		$blame_arr = array(
			"type"			=>	1,
			"assess_id"		=>	$comment_id,
			"company_id"	=>	$this->_userid,
			"person_id"		=>	$person_id,
			"offer_id"		=>	$offer_id,
			"reason"		=>	$blame_content,
			"is_effect"		=>	1,
			'create_time'	=>	date("Y-m-d H:i:s"),
			);		
		if(false !== $blame_id = $blame_service->insert($blame_arr)){
			$company_comment_service->update(array('assess_id'=>$comment_id),array('blame_id'=>$blame_id));
			$json['success'] = '提交成功,请等待工作人员处理';
		}else{
			$json['error'] = '提交失败';
		}
		echo json_encode($json);
		return;
	}

	//更改企业查看评论的时间
	private function updateSeeCommentTime(){
		$service = new base_service_part_company_partcompany();
		$condition = array('company_id'=>$this->_userid);
		$service->update($condition,array("see_assessed_time"=>date("Y-m-d H:i:s")));
	}
}