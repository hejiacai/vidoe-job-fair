<?php
/**
 * @Desc:	兼职简历
 * @Author: zhangfangjun@huibo.com
 * @Date:   2015-08-03 14:46:33
 * @Last Modified by:   zhangfangjun - Administrator
 * @Last Modified time: 2015-09-15 13:53:42
 * @Copyright (c) http://www.huibo.com All rights reserved.:
 */
class controller_partresume extends components_cbasepage{


	function __construct(){
		parent::__construct(true,'part');
		$this->pageAjaxLoadPageSize = 4;
	}


    public function pageIndex($inPath)
    {
        $pathdata                        = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $page                            = base_lib_BaseUtils::getStr($pathdata['page'], 'int', 1);
        $status                          = base_lib_BaseUtils::getStr($pathdata['status'], 'int', 1);
        $job_id                          = base_lib_BaseUtils::getStr($pathdata['job_id'], 'int', null);
        $isendin24                       = base_lib_BaseUtils::getStr($pathdata['is_end_limit'], 'int', 0);
        $pub_user_name                   = base_lib_BaseUtils::getStr($pathdata['pub_user_name'], 'string', null);
        $this->_aParams['status']        = $status;
        $this->_aParams['job_id']        = $job_id;
        $this->_aParams['pub_user_name'] = $pub_user_name;
        $service_job                     = new base_service_part_company_job();
        $service_apply                   = new base_service_part_company_apply();
        $service_joboffered              = new base_service_part_company_joboffered();
        $service_invite                  = new base_service_part_company_invite();
        $service_jobapplystatus          = new base_service_common_part_applystatus();
        $service_footermodul             = new base_service_common_part_footermodule();
        $job_list                        = $service_job->getCompanyAllJobs($this->_userid, 'job_id,station,effect_time,status,link_man,check_state', true);

        $status_list                     = ["1" => "待处理", "2" => "已录用", "3" => "待面试", "4" => "不合适"];
        $pageSize                        = 20;

        $people_pub_job = array_unique(base_lib_BaseUtils::getPropertys($job_list, 'link_man'));
        foreach ($job_list as $k => $v) {
            $job_list[ $k ]['job_flag'] = base_lib_Rewrite::getFlag("partjob", $v['job_id']);
        }

        switch ($status) {
            case 1:
                $fields = 'a.apply_id,a.create_time,a.person_id,a.job_id,a.update_time';
                $data   = $service_apply->getApplys($pageSize, $page, $fields, $this->_userid, $job_id, $pub_user_name, $service_jobapplystatus->not_deal, $isendin24);
                $this->_modViewTime($this->_userid, 'apply');
                break;
            case 2:
                $fields = 'a.offer_id,a.job_id,a.person_id,a.apply_id,a.create_time,a.status,a.offer_time,a.is_company_assess,a.offer_time';
                $data   = $service_joboffered->getOfferList($pageSize, $page, $fields, $this->_userid, $job_id, $pub_user_name, null);
                $this->_modViewTime($this->_userid, 'offer');
                break;
            case 3:
                $fields = 'a.invite_id,a.job_id,a.person_id,a.create_time,a.apply_id,a.re_status,a.attention_time';
                $data   = $service_invite->getInviteList($pageSize, $page, $fields, $this->_userid, $job_id, $pub_user_name, null);
                $this->_modViewTime($this->_userid, 'invite');
                break;
            case 4:
                $fields = 'a.apply_id,a.create_time,a.person_id,a.job_id,a.update_time';
                $data   = $service_apply->getApplys($pageSize, $page, $fields, $this->_userid, $job_id, $pub_user_name, [
                    $service_jobapplystatus->improper,
                    $service_jobapplystatus->auto_improper,
                ],0,2);
                break;
            default:
                $fields = 'a.apply_id,a.create_time,a.person_id,a.job_id,a.update_time';
                $data   = $service_apply->getApplys($pageSize, $page, $fields, $this->_userid, $job_id, $pub_user_name, $service_jobapplystatus->not_deal, $isendin24);
                break;
        }
        $this->_packageData($data->items, true);

        $jobs_json  = []; // 职位筛选项
        $job_people = []; //发布人选项
        array_push($jobs_json, ["id" => "", "name" => "全部职位"]);
        array_push($job_people, ["id" => "", "name" => "全部发布人"]);
        $_temp_use_job = [];
        $_temp_pause_job = [];
        $_temp_stop_job = [];
        foreach ($job_list as $job) {
            if($job['check_state'] != 1){
                continue;
            }

            if(mb_strlen($job['station']) > 6){
                $station = mb_strcut($job['station'],0,18)."..";
            }else{
                $station = $job['station'];
            }
            if (in_array($job['status'], [0]) || $job['effect_time'] <= date("Y-m-d H:i:s")) {

                $station = $station. "<span class=\"orange\">(停招)</span>";
                array_push($_temp_stop_job, ["id" => $job['job_id'], "name" => $station]);
            }elseif(in_array($job['status'], [3,4])){
                $station = $job['station'] . "<span class=\"orange\">(暂停)</span>";

                array_push($_temp_pause_job, ["id" => $job['job_id'], "name" => $station]);
            }else{
                array_push($_temp_use_job, ["id" => $job['job_id'], "name" => $job['station']]);
            }

        }
        $jobs_json = array_merge($jobs_json,$_temp_use_job);
        $jobs_json = array_merge($jobs_json,$_temp_pause_job);
        $jobs_json = array_merge($jobs_json,$_temp_stop_job);

        foreach ($people_pub_job as $p) {
            array_push($job_people, ["id" => $p, "name" => $p]);
        }

        $pager                         = $this->pageBar($data->totalSize, $pageSize, $page, $inPath);
        $this->_aParams["pager"]       = $pager;
        $this->_aParams['status_list'] = $status_list;
        $this->_aParams['data']        = $data->items;
        $this->_aParams['cur_footer']  = $service_footermodul->resume;
        $this->_aParams['jobs']        = json_encode($jobs_json);
        $this->_aParams['pubpersons']  = json_encode(($job_people));

        return $this->render("part/resume/waitdeal.html", $this->_aParams);
    }


    public function pageResumedetail($inPath) {
		$pathdata  = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_id = base_lib_BaseUtils::getStr($pathdata['resumeid'], 'int', 0);
		$apply_id  = base_lib_BaseUtils::getStr($pathdata['applyid'], 'int', 0);
		$pageNo    = base_lib_BaseUtils::getStr($pathdata['page'], 'int', 1);
		$pageSize = $this->pageAjaxLoadPageSize;

		if ($resume_id && $apply_id) {
            $person_service            = new base_service_person_person();

            $resume_service            = new base_service_part_person_resume();
            $resume_jobsortexp_service = new base_service_part_person_resumejobsortexp();
            $jobsort_service           = new base_service_common_part_jobsort();
            $resume_areaexp_service    = new base_service_part_person_resumeareaexp();
            $resume_freetime_service   = new base_service_part_person_resumefreetime();
            $resume_photo_service      = new base_service_part_person_resumephoto();
            $area_service              = new base_service_common_area();
            $score_service             = new base_service_common_part_personscoretolevel();
            $degree_service            = new base_service_common_degree();
            $apply_service             = new base_service_part_company_apply();
            $applystatus_service       = new base_service_common_part_applystatus();
            $invite_service            = new base_service_part_company_invite();
            $service_offer             = new base_service_part_company_joboffered;
            $service_invit             = new base_service_part_company_invite;

			$map = array( 'company_id' => $this->_userid, 'apply_id' => $apply_id);
			$apply = $apply_service->selectOne($map, "status,mobile_watch_time");
			$status = $apply['status'];
			$this->_aParams['mobile_watched'] = $apply['mobile_watch_time'];

			if (empty($apply)) {
			    return $this->render("../config/404.html");
			}

            //$status状态：["1" => "待处理", "2" => "已录用", "3" => "待面试", "4" => "不合适"];
            if (in_array($status, [0])) {
                $status = 1;
            } else {
                $is_offer_send = $service_offer->selectOne(['apply_id'   => $apply_id, 'company_id' => $this->_userid], 'offer_id,is_company_assess,company_status');
                $is_invte_send = $service_invit->selectOne(['apply_id'   => $apply_id, 'company_id' => $this->_userid], 'invite_id,status');
                if ($is_offer_send && $is_offer_send['company_status'] != 2) {
                    $status = 2;//录用列表
                } else if ($is_invte_send && $is_invte_send['status'] == 0)
                    $status = 3;//待面试列表
                //               else if (in_array($status, [11,12, 13])) {
                else {
                    $status = 4;
                }
            }

			$base_field     = 'resume_id,person_id,school,degree,edu_start_year,edu_end_year,update_time,create_time,appraise,in_school';
			$person_field   = 'person_id,user_name,email,sex,birthday2 as birthday,stature,cur_area_id,avoirdupois,marriage,mobile_phone,qq,photo,small_photo,big_photo,has_big_photo';
			$areaexp_field  = 'area_id,update_time as area_update_time,id as areaexp_id';
			$freetime_field = 'code as freetime_code,update_time as freetime_update_time,id as freetime_id';
			$photo_field    = 'photo as pic_photo,update_time as photo_update_time,id as photo_id';

			$job_condition = array("resume_id" => $resume_id);
            //$resume_info = $service_person_resume_resume->getResume($resume_id,"resume_id,person_id",true);
			$base_resume = $resume_service->getResumeById(array('resume_id'=>$resume_id,'is_effect'=>1), $base_field);
			if (base_lib_BaseUtils::nullOrEmpty($base_resume)) {
				return $this->render("../config/404.html");
			}

            $person_info = $person_service->getPerson($base_resume['person_id'],"person_id,is_effect",true);
            if (base_lib_BaseUtils::nullOrEmpty($person_info)) {
                return $this->render("../config/404.html");
            }

			$person_resume     = $person_service->getPerson($base_resume['person_id'], $person_field);
			$jobsortexp_resume = $resume_jobsortexp_service->select($job_condition, 'jobsort_id')->items;
			$areaexp_resume    = $resume_areaexp_service->select(array('resume_id'=>$resume_id), $areaexp_field)->items;
			$freetime_resume   = $resume_freetime_service->select(array('resume_id'=>$resume_id), $freetime_field)->items;
			$photo_resume      = $resume_photo_service->select(array('resume_id'=>$resume_id,'is_effect'=>1), $photo_field)->items;

            $this->_aParams['need_get_way'] = false;
            $sv_point_out = new base_service_part_company_resumepoint_pointout();
            
            //判断企业是否需要下载简历
            $service_pointmanager = new base_service_part_company_resumepoint_pointmanager();
            $service_pointmanager->isneedPointBuy(false,$this->_userid,[$apply_id],$need_buy,$amount,$error,false,$buy_url,$can_pay);
            
            $has_get_link       = $sv_point_out->_hasWactchLinkWay($this->_userid, $base_resume['person_id']);
            if($need_buy){
                if(!$has_get_link){
                    //需付费查看
                    $this->_aParams['need_get_way'] = true;
                    $this->_aParams['phone'] = substr_replace($person_resume["mobile_phone"],'****',3,4);
                }
            }else{
                $service_get_linkway = new base_service_part_company_linkwayget();
                $get_linkway_data    = $service_get_linkway->getLinkWayGetData($this->_userid,$base_resume['person_id'] , "id,get_type");
                if(empty($get_linkway_data)){
                    //需付费查看
                    $this->_aParams['need_get_way'] = true;
                    $this->_aParams['phone'] = substr_replace($person_resume["mobile_phone"],'****',3,4);
                }
            }
            if(!$has_get_link){
                $this->_aParams['not_need_buy'] = true;
            }
            
            if(!$this->canDo("see_part_resume_mobile")){
                $person_resume["mobile_phone"] = "";
                $person_resume["qq"] = "";
            }

			$resume = array_merge($base_resume, $person_resume);
			$resume['age']        = $this->diffDate($resume['birthday'],date("Y-m-d"));
			$resume['grades']     = $this->getGrades($resume['edu_start_year'],$resume['edu_end_year']);
			$resume['degree']     = $degree_service->getDegree($resume['degree']);//($resume['edu_start_year'],$resume['edu_end_year']);
			$resume['jobsortexp'] = $jobsort_service->getJobsorts(base_lib_BaseUtils::getProperty($jobsortexp_resume,'jobsort_id'));
			$resume['areaexp']    = base_lib_BaseUtils::getProperty($area_service->getAreaByAreaID(base_lib_BaseUtils::getProperty($areaexp_resume,'area_id')),'area_name');
			$resume['freetime']   = base_lib_BaseUtils::getProperty($freetime_resume,'freetime_code');
			$resume['pics']       = base_lib_BaseUtils::getProperty($photo_resume,'pic_photo');
			$resume['address']    = $area_service->getAreaName($resume['cur_area_id']);

			$resume['in_school']  = $resume['in_school'] == "1" ? "在校学生" : $resume['in_school'];
			$resume['in_school']  = $resume['in_school'] == "2" ? "已经毕业" : $resume['in_school'];

			$this->_aParams['title'] = "{$resume['user_name']}-简历详情";
			$this->_aParams['pic_baseUrl'] = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP;
			$this->_aParams['resume'] = $resume;

			if($status == 2){
                $this->_aParams['user_name'] = $resume['user_name'];
                $this->_aParams['person_id'] = $base_resume['person_id'];
                $this->_aParams['offer_id'] = $is_offer_send['offer_id'];
                $this->_aParams['is_access'] = $is_offer_send['is_company_assess'];
            }


			//获取已经评价过的兼职
			$person_comment_service = new base_service_part_person_personcomment();
			$count = $person_comment_service->getCommentCount($base_resume['person_id'],false);

			$filed    = array_keys($person_comment_service::getDbField());
			$comments = $person_comment_service->getResumeComment($base_resume['person_id'],$filed,$pageNo,$pageSize,false);
			$dataLst  = $comments->items;

			$company_ids = base_lib_BaseUtils::getProperty($dataLst,'company_id');
			if (!empty($company_ids)) {
				$company_service = new base_service_company_company();
				$company_names = $company_service->select(array("company_id"=>array('in'=>$company_ids)),'company_id,company_name')->items;
				$dataLst = base_lib_BaseUtils::compareAdd($dataLst,$company_names,'company_id','company_name');
			}

			$all_levels = base_lib_BaseUtils::array_key_assoc($count,'level');
			$total_score = 100;
			foreach ($all_levels as $key => $value) {
				$total_score +=$value['score'];
			}

			if ($status == 2 || $status == 3) {

				$offer_service = new base_service_part_company_joboffered();
				$offer = $offer_service->selectOne(array('apply_id'=>$apply_id),'offer_id,status');

				if (empty($offer)) {
					$invite = $invite_service->selectOne(array('apply_id'=>$apply_id),'invite_id,re_status as status');
				}

				$this->_aParams['invite_status']     = new base_service_common_part_invite();
				$this->_aParams['cur_invite_status'] = empty($invite)?-1:$invite['status'];
				$this->_aParams['invite_id']         = empty($invite)?0:$invite['invite_id'];
				$this->_aParams['offer_id']          = empty($offer)?0:$offer['offer_id'];
				$this->_aParams['cur_offer_status']  = $offer['status'];
				$this->_aParams['offer_status']      = new base_service_common_part_offer();
			}

			$this->_aParams['scorelevel']    = $score_service->getLevel($total_score,true);
			$this->_aParams['count']         = $all_levels;
			$this->_aParams['comment_page']  = $comment_page;
			$this->_aParams['comment_count'] = $comments->totalSize;
			$this->_aParams['comments']      = $dataLst;
			$this->_aParams['cur_page']      = intval($comments->page);
			$this->_aParams['total_page']    = intval($comments->totalPage);
			$this->_aParams['apply_id']      = $apply_id;
			$this->_aParams['default_photo'] = base_lib_Constant::STYLE_URL."/img/c/new_resume/headportrait.png";
			$this->_aParams['cur_status']    = $status;
			$this->_aParams['applystatus']   = $applystatus_service;

			return $this->render("part/resumedetail.html",$this->_aParams);
		} else {
			return $this->render("../config/404.html");
		}
	}

	public function pageAjaxLoadComment($inPath){
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$person_id = base_lib_BaseUtils::getStr($pathdata['person_id'],'int',0);

		$pageNo = base_lib_BaseUtils::getStr($pathdata['page'],'int',2);
		$pageSize = $this->pageAjaxLoadPageSize;

		$json = array('status'=>0);
		if($person_id){
			$person_comment_service = new base_service_part_person_personcomment();
			$filed = array_keys($person_comment_service::getDbField());
			$comments = $person_comment_service->getResumeComment($person_id,$filed,$pageNo,$pageSize,false);
			$dataLst = $comments->items;
			$company_ids = base_lib_BaseUtils::getProperty($dataLst,'company_id');
			if(!empty($company_ids)){
				$company_service = new base_service_company_company();
				$company_names = $company_service->select(array("company_id"=>array('in'=>$company_ids)),'company_id,company_name')->items;
				$dataLst = base_lib_BaseUtils::compareAdd($dataLst,$company_names,'company_id','company_name');
			}
			$json['status'] = 1;
			$json['dataLst'] = $dataLst;
			$json['cur_page'] = intval($comments->page);
			$json['total_page'] = intval($comments->totalPage);
		}
		echo json_encode($json);
		return;
	}

    /*
     *面试邀请 录用通知 页面
     */
    public function pageInvite($inPath)
    {

        $pathdata               = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $apply_id               = base_lib_BaseUtils::getStr($pathdata['apply_id'], 'string', null);
        $apply_ids = explode(',',$apply_id);
        $type                   = base_lib_BaseUtils::getStr($pathdata['type'], 'int', 1);//1 邀请面试  2录用通知
        $apply_service          = new base_service_part_company_apply();
        $service_partjob        = new base_service_part_company_job();
        $service_partjobaddress = new base_service_part_job_partjobaddress();
        $service_pointmanager = new base_service_part_company_resumepoint_pointmanager();
        $service_invite         = new base_service_part_company_invite();

        $service_pointmanager->isneedPointBuy(false,$this->_userid,$apply_ids,$need_buy,$amount,$error,false,$buy_url,$can_pay);
        
        if($error){
            echo "抱歉，该用户没有找到";die;
        }

        if (count($apply_ids) > 1) {
            $data['apply_id'] = $apply_id;
            $data['title']    = $type == 1 ? '面试邀请' : '录用通知';
            $data['_title']   = $type == 1 ? '面试' : '报道';
            $data['do_url']   = $type == 1 ? 'invitedo' : 'OfferDo';
            $data['type']     = $type;
            $this->_aParams   = $data;
        } else {
            $apply           = $apply_service->getApply($apply_id, 'apply_id,job_id,address_id');
            $job             = $service_partjob->getJob($apply['job_id'], 'link_man,link_phone,address,is_need_workaddress');
            $data['address'] = $apply['address_id'] ? $service_partjobaddress->getAddressById($apply['address_id'], 'address_info')['address_info'] : $job['address'];
            if($job['is_need_workaddress'] == 2)
                $data['address'] = '';
            if ($type == 1) {
                //使用上次的面试邀请地点
                $data['address'] = $service_invite->getLastInviteInfo('attention_address',$this->_userid)['attention_address'];
                if (empty($data['address'])) {
                    $data['address'] = '';
                }

            }
            $data['link_phone'] = $job['link_phone'];
            $data['link_man']   = $job['link_man'];
            $data['apply_id']   = $apply_id;
            $data['title']      = $type == 1 ? '面试邀请' : '录用通知';
            $data['_title']     = $type == 1 ? '面试' : '报道';
            $data['do_url']     = $type == 1 ? 'invitedo' : 'OfferDo';
            $data['type']       = $type;
            $this->_aParams     = $data;
        }
        
        $this->_aParams['need_buy']   = $need_buy;
        $this->_aParams['need_spent'] = $amount;
        $this->_aParams['buy_url']    = $buy_url;
        $this->_aParams['can_pay']    = $can_pay;
        //获取日期
        $weekarray = array("日", "一", "二", "三", "四", "五", "六");
        $date_arr  = [];
        for ($i = 0; $i < 15; $i++) {
            $date       = date('Y-m-d', strtotime("+{$i} day"));
            $weekday    = date("w", strtotime("+{$i} day"));
            $date_arr[] = ['id' => $date, 'name' => "{$date}[星期{$weekarray[$weekday]}]"];
        }
        $this->_aParams['date_json'] = $date_arr;

        return $this->render("part/resume/invite.html", $this->_aParams);
    }

    public function pageMobileWatched($inPath) {
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $apply_id  = base_lib_BaseUtils::getStr($path_data['apply_id'], 'int', 0);
        if (empty($apply_id))
        {
            echo "抱歉，该用户没有找到";
            die;
        }

        $service_pointmanager = new base_service_part_company_resumepoint_pointmanager();
        $service_pointmanager->isneedPointBuy(false,$this->_userid,$apply_id,$need_buy,$amount,$error,false,$buy_url,$can_pay);
        if($error){
            echo "抱歉，该用户没有找到";die;
        }
        $this->_aParams['need_buy'] = $need_buy;
        $this->_aParams['need_spent'] = $amount;
        $this->_aParams['buy_url'] = $buy_url;
        $this->_aParams['can_pay'] = $can_pay;

        $this->_aParams['apply_id'] = $apply_id;
        return $this->render('./part/resume/getlinkway.html',$this->_aParams);
    }

    public function pageInviteDo($inPath){
        $pathdata   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $apply_id   = base_lib_BaseUtils::getStr($pathdata['apply_id'], 'string', null);
        $link_phone = base_lib_BaseUtils::getStr($pathdata['txtLinktel'], 'string', '');
        $apply_ids = explode(',',$apply_id);
        $validator = new base_lib_Validator();
        $apply_service          = new base_service_part_company_apply();
        $service_jobapplystatus = new base_service_common_part_applystatus();
        $service_invite         = new base_service_part_company_invite();

        $data['link_man']          = $validator->getStr($pathdata['txtLinkman'],2,10,'联系人输入2-10字');
        $data['attention_address'] = $validator->getStr($pathdata['txtAddress'],3,50,'面试地点请输入3-50字');
        $data['attention_time']    = base_lib_BaseUtils::getStr($pathdata['timeDate'], 'string', '') ." ".base_lib_BaseUtils::getStr($pathdata['time'], 'string', '');
        $data['description']       = base_lib_BaseUtils::getStr($pathdata['txtRemark'], 'string', '');

        if($data['attention_time'] < date("Y-m-d H:i")){
            $validator->addErr("面试时间不能早于当前时间，请重新选择");
        }
        if (empty($apply_id) || empty($apply_ids)) {
            $validator->addErr("参数错误");
        }
        $invite_id = $service_invite->getInviteByApply($apply_id,'invite_id');
        if(!empty($invite_id)){
            $validator->addErr("请勿重复发送");
        }
        if(base_lib_BaseUtils::nullOrEmpty($link_phone) || !(preg_match('/^[1]\d{10}$/',$link_phone) || preg_match('/^(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/',$link_phone))){
            array_push($validator->err,'联系电话格式不正确');
            $validator->has_err = true;
        }
        if($validator->has_err){
            echo header("Content-type:text/plain;charset=utf-8");
            echo json_encode(["status"=>false,"msg"=>$validator->err[0]]);
            return;
        }

        $service_pointmanager = new base_service_part_company_resumepoint_pointmanager();
        $service_pointmanager->isneedPointBuy(false,$this->_userid,$apply_ids,$need_buy,$amount,$error,false,$buy_url,$can_pay);
        if($need_buy && !$can_pay){
            exit( json_encode(['status'=>false,"msg"=>"兼职币不足,正在跳往充值页面...",'code'=>113]));
        }

        foreach ($apply_ids as $apply_id){
            $apply = $apply_service->getApply($apply_id,'apply_id,job_id,person_id');
            $data['link_tel']   = $link_phone;
            $data['apply_id']   = $apply_id;
            $data['company_id'] = $this->_userid;
            $data['person_id']  = $apply['person_id'];
            $data['job_id']     = $apply['job_id'];
            $result = $service_invite->addInvite($data,false);

             if($result){
                //更新申请状态
                $apply_service->modApply($apply_id, array('status'=>$service_jobapplystatus->apply_done),false);
                //标记有新增录用和面试通知
                $person_app_service = new base_service_part_person_app();
                $person_app_service->setnew($apply['person_id'], $type = 2);
            }
        }

        if(!$result){
            if(count($apply_ids) >1)
                $msg = '某些面试邀请发送失败，需从新发送，刷新后再试';
            else
                $msg = '发送失败';
            echo json_encode(['status'=>false,"msg"=>$msg,'code'=>112]);
            return;
        }

        echo json_encode(['status'=>true,"msg"=>"面试邀请发送成功"]);
        return;
    }

    public function pageOfferDo($inPath){
        $pathdata               = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $apply_id               = base_lib_BaseUtils::getStr($pathdata['apply_id'], 'string', null);
        $link_phone             = base_lib_BaseUtils::getStr($pathdata['txtLinktel'], 'string', '');
        $validator              = new base_lib_Validator();
        $apply_service          = new base_service_part_company_apply();
        $service_jobapplystatus = new base_service_common_part_applystatus();
        $service_person         = new base_service_person_person();
        $service_partjob        = new base_service_part_company_job();
        $service_invite         = new base_service_part_company_invite();
        $service_offer          = new base_service_part_company_joboffered();
        $data['link_man']       = $validator->getStr($pathdata['txtLinkman'], 2, 10, '联系人输入2-10字');
        $data['offer_address']  = $validator->getStr($pathdata['txtAddress'], 3, 50, '报道地点请输入3-50字');
        $data['offer_time']     = base_lib_BaseUtils::getStr($pathdata['timeDate'], 'string', '') . " " . base_lib_BaseUtils::getStr($pathdata['time'], 'string', '');
        $data['description']    = base_lib_BaseUtils::getStr($pathdata['txtRemark'], 'string', '');

        $apply_ids = explode(',',$apply_id);

        if ($data['offer_time'] < date("Y-m-d H:i")) {
            $validator->addErr("报道时间不能早于当前时间，请重新选择");
        }
        if (empty($apply_id) || empty($apply_ids)) {
            $validator->addErr("参数错误");
        }
        $offer = $service_offer->getOfferByApply($apply_id, 'offer_id');
        if (!empty($offer)) {
            $validator->addErr("请勿重复发送");
        }
        if (base_lib_BaseUtils::nullOrEmpty($link_phone) || !(preg_match('/^[1]\d{10}$/', $link_phone) || preg_match('/^(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/', $link_phone))) {
            array_push($validator->err, '联系电话格式不正确');
            $validator->has_err = true;
        }
        if ($validator->has_err) {
            echo header("Content-type:text/plain;charset=utf-8");
            echo json_encode(["status" => false, "msg" => $validator->err[0]]);

            return;
        }

        $service_pointmanager = new base_service_part_company_resumepoint_pointmanager();
        $service_pointmanager->isneedPointBuy(false,$this->_userid,$apply_ids,$need_buy,$amount,$error,false,$buy_url,$can_pay);
        if($need_buy && !$can_pay){
            exit( json_encode(['status'=>false,"msg"=>"兼职币不足,正在跳往充值页面...",'code'=>113]));
        }

        foreach ($apply_ids as $apply_id){
            $apply              = $apply_service->getApply($apply_id, 'apply_id,job_id,person_id');
            $data['link_tel']   = $link_phone;
            $data['apply_id']   = $apply_id;
            $data['company_id'] = $this->_userid;
            $data['person_id']  = $apply['person_id'];
            $data['job_id']     = $apply['job_id'];
            $result             = $service_offer->addOffer($data,false);

            if($result){
                //更新申请状态
                $apply_service->modApply($apply_id, array('status'=>$service_jobapplystatus->apply_done),false);
                $invite_id = $service_invite->getInviteByApply($apply_id,'invite_id')['invite_id'];
                $common_invite = new base_service_common_part_invite();
                if($invite_id){
                    $items['status'] = $common_invite->agree;
                    $service_invite->modInvite($invite_id,$items);
                }

                //标记有新增录用和面试通知
                $person_app_service = new base_service_part_person_app();
                $person_app_service->setnew($apply['person_id'], $type = 2);
            }
        }

        if(!$result){
            if(count($apply_ids) >1)
                $msg = '某些录用发送失败，需从新发送，刷新后再试';
            else
                $msg = '录用失败';
            echo json_encode(['status'=>false,"msg"=>$msg,'code'=>112]);
            return;
        }
        echo json_encode(['status'=>true,"msg"=>"您的录用信息已发送，请及时与对方沟通确认。"]);
        return;
    }

    public function pageOfferAgainDo($inPath){
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $apply_ids = base_lib_BaseUtils::getStr($path_data['apply_id'],'string',null);
        $apply_ids = explode(',',$apply_ids);
        $service_apply = new base_service_part_company_apply();

        if(empty($apply_ids))
        {
            echo json_encode(array('msg'=>'参数错误', 'status'=>false,'data'=>$apply_ids));
            return;
        }

        foreach ($apply_ids as $k=>$apply_id){
            $result = $service_apply->offerAgain( $apply_id,$this->_userid);
        }

        if ($result) {
            echo json_encode(array('msg'=>'重新录用成功', 'status'=>true));
            return;
        } else {
            echo json_encode(array('msg'=>'设置失败', 'status'=>false));
            return;
        }
    }

    public function pageNOfitDo($inPath){
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $apply_ids = base_lib_BaseUtils::getStr($path_data['apply_id'],'string',null);
        $from = base_lib_BaseUtils::getStr($path_data['from'],'int',1);
        $apply_ids = explode(',',$apply_ids);
        $service_apply = new base_service_part_company_apply();

        if(empty($apply_ids))
        {
            echo json_encode(array('msg'=>'参数错误', 'status'=>false,'data'=>$apply_ids));
            return;
        }

        foreach ($apply_ids as $k=>$apply_id){
            $result = $service_apply->refuseApply($this->_userid, $apply_id,"normal",$from);
        }

        if ($result) {
            echo json_encode(array('msg'=>'设置成功', 'status'=>true));
            return;
        } else {
            echo json_encode(array('msg'=>'设置失败', 'status'=>false));
            return;
        }
    }

    public function pageMobileWatchedDo($inPath) {
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $apply_id  = base_lib_BaseUtils::getStr($path_data['applyid'], 'int', 0);
        if (empty($apply_id))
            exit( json_encode(['status'=>false,"msg"=>"传入参数不合法"]));
        $apply_service = new base_service_part_company_apply();
        $res = $apply_service->mobileWatched($apply_id, $this->_userid,false);
        if($res['status']){
            //简历点
            $service_point                 = new base_service_part_company_resumepoint_pointaccount();
            $point_info                    = $service_point->getAccountByCompany($this->_userid);

            $str = "获取联系方式成功，剩余兼职币：{$point_info['last_amount']}个";
            exit( json_encode(['status'=>true,"msg"=>$str]));
        }else{
            exit( json_encode($res));
        }


    }







	private function diffDate($date1,$date2){
	    if(strtotime($date1)>strtotime($date2)){
	        $tmp=$date2;
	        $date2=$date1;
	        $date1=$tmp;
	    }
	    list($Y1,$m1,$d1)=explode('-',$date1);
	    list($Y2,$m2,$d2)=explode('-',$date2);
	    $Y=$Y2-$Y1;
	    $m=$m2-$m1;
	    $d=$d2-$d1;
	    if($d<0){
	        $d+=(int)date('t',strtotime("-1 month $date2"));
	        $m--;
	    }
	    if($m<0){
	        $m+=12;
	        $Y--;
	    }
	    return $Y;
	}

	private function getGrades($start,$end){
		if(strtotime($start)>time())
			return null;
		if(strtotime($end)<time())
			return '已毕业';
		$year  = $this->diffDate($start,date("Y-m-d"));
		switch ($year) {
			case '0':
				$returnstr =  '大一';
				break;
			case '1':
				$returnstr =  '大二';
				break;
			case '2':
				$returnstr =  '大三';
				break;
			case '3':
				$returnstr =  '大四';
				break;
			default:
				$returnstr =  '';
				break;
		}
		return $returnstr;
	}

    /**
     * 获取个人，职位等附加信息
     * @param  int $apply_list 兼职报名列表
     * @param bool $special    待处理的报名时间有特殊处理
     * @return mixed
     */
    private function _packageData(&$apply_list, $special = false)
    {
        if (base_lib_BaseUtils::nullOrEmpty($apply_list))
            return $apply_list;

        $person_ids = base_lib_BaseUtils::getProperty($apply_list, 'person_id');
        $job_ids    = base_lib_BaseUtils::getProperty($apply_list, 'job_id');

        $service_area              = new base_service_common_area();
        $service_person            = new base_service_person_person();
        $service_partjob           = new base_service_part_company_job();
        $service_partresume        = new base_service_part_person_resume();
        $degree_service            = new base_service_common_degree();
        $common_sex                = new base_service_common_sex();
        $common_degree             = new base_service_common_degree();
        $jobsort_service           = new base_service_common_part_jobsort();
        $resume_jobsortexp_service = new base_service_part_person_resumejobsortexp();
        $persons                   = $service_person->GetPersonListByIDs(implode(',', array_unique($person_ids)), 'person_id,user_name,is_effect,sex,photo_open,has_big_photo,photo,big_photo,birthday2,cur_area_id,address,stature,mobile_phone')->items;
        $persons                   = base_lib_BaseUtils::array_key_assoc($persons, 'person_id');

        $jobs = $service_partjob->getJobs(implode(',', array_unique($job_ids)), 'job_id,station,need_invite');
        $jobs = base_lib_BaseUtils::array_key_assoc($jobs, 'job_id');

        $resumes = $service_partresume->getResumeByPersonIds($person_ids, 'person_id,resume_id,edu_start_year,edu_end_year,degree,school', true)->items;
        $resumes = base_lib_BaseUtils::array_key_assoc($resumes, 'person_id');

        //获取职位链接
        if (!empty($jobs)) {
            foreach ($jobs as $k => $job) {
                $jobs[ $k ]['joblink'] = base_lib_Constant::MOBILE_JIANZHI_NO_HTTP . '/partjob/job/job_flag-' . base_lib_Rewrite::getFlag('partjob', $job['job_id']);
            }
        }
        $can_see_mobile = $this->canDo("see_part_resume_mobile");
        //个人现居住地址
        if (!empty($persons)) {
            foreach ($persons as $k => $person) {
                $area                     = $service_area->getTopAreaByAreaID($person['cur_area_id']);
                $persons[ $k ]['address'] = implode('', base_lib_BaseUtils::getProperty(array_reverse($area), 'area_name'));
                if (!$can_see_mobile) {
                    $persons[ $k ]['mobile_phone'] = preg_replace('#(\d{3})[a-z0-9-]{2,10}(\d{3})#', '${1}****${2}', $person['mobile_phone']);;
                }
            }
        }
        $sv_point_out = new base_service_part_company_resumepoint_pointout();
        foreach ($apply_list as $k => $apply) {
            //报名时间
            $apply_list[ $k ]['refuse_time']    = empty($apply['refuse_time']) ? "" : date("Y-m-d", strtotime($apply['refuse_time']));
            $apply_list[ $k ]['create_time']    = base_lib_TimeUtil::to_friend_time($apply['create_time']);
            $apply_list[ $k ]['update_time']    = base_lib_TimeUtil::to_friend_time($apply['update_time']);
            $apply_list[ $k ]['attention_time'] = base_lib_TimeUtil::to_friend_time($apply['attention_time']);
            $apply_list[ $k ]['offer_time']     = base_lib_TimeUtil::to_friend_time($apply['offer_time']);
            $apply_list[ $k ]['need_get_way']   = $sv_point_out->_hasWactchLinkWay($this->_userid,$apply['person_id'])? false:true;

            /* $apply_list[ $k ]['create_time']    = date('Y-m-d', strtotime($apply['create_time']));
             $apply_list[ $k ]['update_time']    = date('Y-m-d', strtotime($apply['update_time']));
             $apply_list[ $k ]['attention_time'] = date('Y-m-d', strtotime($apply['attention_time']));
             $apply_list[ $k ]['offer_time']     = date('Y-m-d', strtotime($apply['offer_time']));*/

            if (!empty($resumes[ $apply['person_id'] ]['edu_end_year'])) {
                $apply_list[ $k ]['in_school'] = $resumes[ $apply['person_id'] ]['edu_end_year'] > date("Y-m-d 00:00:00") ? "学生" : "已毕业";
            } else {
                $apply_list[ $k ]['in_school'] = "";
            }
            $apply_list[ $k ]['resume_id'] = $resumes[ $apply['person_id'] ]['resume_id'];

            $apply_list[ $k ]['age']         = base_lib_TimeUtil::ceil_diff_year($persons[ $apply['person_id'] ]['birthday2']) . "岁";
            $apply_list[ $k ]['photo_url']   = empty($persons[ $apply['person_id']]['big_photo']) ? base_lib_Constant::STYLE_URL."/img/part/companyindex/person_head.png" : base_lib_BaseUtils::getThumbImg($persons[ $apply['person_id']]['big_photo'],65,65);
            $apply_list[ $k ]                = empty($persons[ $apply['person_id'] ]) ? $apply_list[ $k ] : array_merge($apply_list[ $k ], $persons[ $apply['person_id'] ]);
            $apply_list[ $k ]                = empty($jobs[ $apply['job_id'] ]) ? $apply_list[ $k ] : array_merge($apply_list[ $k ], $jobs[ $apply['job_id'] ]);
            $apply_list[ $k ]                = empty($resumes[ $apply['person_id'] ]) ? $apply_list[ $k ] : array_merge($apply_list[ $k ], $resumes[ $apply['person_id'] ]);
            $apply_list[ $k ]['sex_name']    = $common_sex->getName($apply_list[ $k ]['sex']);
            $apply_list[ $k ]['degree_name'] = $common_degree->getDegree($apply_list[ $k ]['degree']);
            $apply_list[ $k ]['stature']     = $persons[ $apply['person_id'] ]['stature'];
            $some_info                       = [];
            $some_info[]                     = $apply_list[ $k ]['sex_name'];
            if (!empty($apply_list[ $k ]['age']))
                $some_info[] = $apply_list[ $k ]['age'];

            if (!empty($apply_list[ $k ]['stature']))
                $some_info[] = $apply_list[ $k ]['stature'] . 'cm';


            if (!empty($apply_list[ $k ]['address']))
                $some_info[] = $apply_list[ $k ]['address'];
            if (!empty($apply_list[ $k ]['school']))
                $some_info[] = $apply_list[ $k ]['school'];
            if (!empty($apply_list[ $k ]['degree_name']))
                $some_info[] = $apply_list[ $k ]['degree_name'];
            $apply_list[ $k ]['some_info'] = implode("<span>|</span>", $some_info);
            $apply_list[ $k ]['person_effect'] = 0;
            if($persons[ $apply['person_id'] ]['is_effect'] == 1 && !empty($persons[ $apply['person_id'] ]['mobile_phone'])){
                $apply_list[ $k ]['person_effect'] = 1;
            }



            $job_condition                  = array("resume_id" => $resumes[ $apply['person_id'] ]['resume_id']);
            $jobsortexp_resume              = $resume_jobsortexp_service->select($job_condition, 'jobsort_id')->items;
            $apply_list[ $k ]['jobsortexp'] = $jobsort_service->getJobsorts(base_lib_BaseUtils::getProperty($jobsortexp_resume, 'jobsort_id'));


        }
    }




    /**
     * @Desc 更新企业查看各种报名的时间
     * @param int  $company_id 企业id
     * @param enum $apply_type 报名类型('apply','invite','offer')
     */
    private function _modViewTime($company_id, $apply_type)
    {
        if (empty($company_id) || empty($apply_type))
            return false;
        $service_partcompany     = new base_service_part_company_partcompany();
        $thisdate                = date('Y-m-d H:i:s');
        $condition['company_id'] = $company_id;
        $update                  = array();
        switch ($apply_type) {
            case 'apply' :
                $update['see_apply_time'] = $thisdate;
                break;
            case 'invite' :
                $update['see_invite_time'] = $thisdate;
                break;
            case 'offer' :
                $update['see_offer_time'] = $thisdate;
                break;
        }

        return $service_partcompany->update($condition, $update);
    }


}