<?php
/**
 * 招聘会职位
 * @ClassName controller_fair 
 * @Desc todo(这里用一句话描述这个类的作用) 
 * @author liuchang@huibo.com
 * @date 2013-11-13 下午04:34:31 
 *
 */
class controller_fairjob extends components_cbasepage{
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * 招聘会职位管理列表入口
	 * Enter description here ...
	 * @param $inPath
	 */
	function pageIndex($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$this->_aParams['title'] = "汇博人才网_现场招聘_现场职位管理";
		$service_company = new base_service_company_company();
		$current_company = $service_company->getCompany($this->_userid, true, 'company_id');
		if (empty($current_company)) {
			return;
		}
		$service_comfairservicescene = new base_service_company_fair_comfairservicescene();
		$service_comfairservicescenestate = new base_service_company_fair_comfairservicescenestate();
		//单位所有参加的场次
		$companyalljoinscene = $service_comfairservicescene->getAllComFairServiceScene($current_company['company_id'], $service_comfairservicescenestate->started, 'scene_id');
		//单位所有参加的场次ID
		$companyalljoinsceneid = array();
		foreach ($companyalljoinscene->items as $value){
			array_push($companyalljoinsceneid, $value['scene_id']);
		}
		$service_fairscene = new base_service_company_fair_fairscene();
		$current_time = date('Y-m-d H:i:s',time());
		$service_fairscenestate = new base_service_company_fair_fairscenestate();
		//所有未召开和正在进行的招聘会
		$scenelist = $service_fairscene->getFairSceneList(null, $service_fairscenestate->enable, $current_time, 'scene_id, date, begin_time, end_time, subject');
		//正在召开或已结束的场次ID
		$is_begin_or_end_sceneid = 0;
		if (!empty($scenelist)) {
			$weekarray = array("日","一","二","三","四","五","六");
			foreach ($scenelist->items as $item=>$value){
				if (!in_array($value['scene_id'], $companyalljoinsceneid)){
					unset($scenelist->items[$item]);
					continue;
				}
				$scenelist->items[$item]['theweekday'] = '星期'.$weekarray[date('w', strtotime($value['date']))];
				$scenelist->items[$item]['thesimpledate'] = date("m-d", strtotime($value['date']));
				if ((strtotime($value['begin_time']) <= time() && strtotime($value['end_time']) >= time()) || strtotime($value['end_time']) <= time()){
					$is_begin_or_end_sceneid = $value['scene_id'];
				}
			}
		}
		if (empty($scenelist) || count($scenelist->items) <= 0) {
			$this->_aParams['no_anyscene'] = true;
		}else {
			$this->_aParams['scenelist'] = $scenelist->items;
			if (!empty($is_begin_or_end_sceneid)) {
				$this->_aParams['is_begin_or_end_sceneid'] = $is_begin_or_end_sceneid;
			}
			//获取职位列表
			$scene_id = base_lib_BaseUtils::getStr($path_data['sceneid'],'int',-1);
			if ($scene_id == -1) {
				$all_scene_keys = array_keys($scenelist->items);
				$first_scene_key = $all_scene_keys[0];
				$scene_id = $scenelist->items[$first_scene_key]['scene_id'];
			}
			$this->_aParams['scene_id'] = $scene_id;
			$this->getJobListPage($inPath,$path_data,$current_company['company_id'],$scene_id);
		}
		return $this->render('fair/joblist.html', $this->_aParams);
	}
	
	/**
	 * 分页查询职位列表
	 * Enter description here ...
	 * @param $inPath
	 * @param $path_data
	 * @param $company_id
	 * @param $scene_id
	 */
	function getJobListPage($inPath,$path_data,$company_id,$scene_id){
		$cur_page = base_lib_BaseUtils::getStr($path_data['page'],'int',1);
    	$page_size = base_lib_Constant::PAGE_SIZE;
    	$service_fair_job = new base_service_company_fair_fairjob();
		$job_list = $service_fair_job->getFairJobList($cur_page,$page_size,$company_id, $scene_id, 'job_id,station,content');
		if (!empty($job_list) && count($job_list->items) > 0) {
			$this->_aParams['job_list'] = $job_list->items;
			$total_count = $job_list->totalSize;
			$pager = $this->pageBar($total_count, $page_size, $cur_page, $inPath);
    		$this->_aParams['pager'] = $pager;
		}		
	}
	
	/**
	 * 新增现场职位
	 * Enter description here ...
	 * @param $inPath
	 */
	function pageAddjob($inPath){
		$service_company = new base_service_company_company();
		$company_info = $service_company->getCompany($this->_userid,1,'company_id');
		if (empty($company_info)) {
			$json['error'] = '发布职位失败';
			echo json_encode($json);
			return;
		}
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$scene_id = base_lib_BaseUtils::getStr($path_data['hddthesceneid'],'int',-1);
		$service_fairscene = new base_service_company_fair_fairscene();
		$service_fairscenestate = new base_service_company_fair_fairscenestate();
		$scene = $service_fairscene->getFairscene($scene_id, $service_fairscenestate->enable, 'scene_id,begin_time,end_time');
		$validator = new base_lib_Validator();
		$fair_job['station'] = $validator->getStr($path_data['txtStation'],1,30,'职位名称填写错误','');
		$fair_job['content'] = $validator->getStr($path_data['txtContent'],1,4000,'职位详情填写错误','');
		if($validator->has_err){
			echo $validator->toJsonWithHtml();
			exit;
    	}
		$fair_job['company_id'] = $company_info['company_id'];
		$fair_job['scene_id'] = $scene_id;
		$fair_job['create_time'] = date('Y-m-d H:i:s',time());
		$fair_job['order_no'] = null;
		$service_fair_job = new base_service_company_fair_fairjob();
		$result = $service_fair_job->addFairJob($fair_job, $scene);
		if ($result === 6) {
			$json['success'] = '发布职位成功';
			$json['sceneid'] = $scene_id;
			echo json_encode($json);
			return;
		}
		$des = '';
		switch ($result) {
			case 1:
				$des = '对不起，该场次不存在';
				break;
			case 2:
				$des = '对不起，本次招聘会已经结束';
				break;
			case 3:
				$des = '贵公司服务已过期';
				break;
			case 4:
				$des = '您已申请参加，请等待我们的客服联系您';
				break;
			case 5:
				$des = '贵公司尚未开通现场招聘会服务，请联系我们的客服';
				break;
			case 7:
				$des = '该场招聘会正在进行不能发布职位';
				break;
			case 8:
				$des = '发布职位失败';
				break;
			case 9:
				$des = '您已申请参加，稍后客服将会与您联系';
				break;
			case 10:
				$des = '请申请参加本次招聘会或直接联系我们的客服';
				break;
			default:
				$des = '发布职位失败';
				break;
		}
		$json['error'] = $des;
		echo json_encode($json);
		return;
	}
	
	/**
	 * 删除职位
	 * Enter description here ...
	 * @param $inPath
	 */
	function pageDelFairJob($inPath){
		$service_company = new base_service_company_company();
		$company_info = $service_company->getCompany($this->_userid,1,'company_id');
		if (empty($company_info)) {
			$json['error'] = '删除职位失败';
			echo json_encode($json);
			return;
		}
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($path_data['jobid'],'int',0);
		$scene_id = base_lib_BaseUtils::getStr($path_data['sceneid'],'int',0);
		if (empty($job_id) || empty($scene_id)) {
			$json['error'] = '删除职位失败';
			echo json_encode($json);
			return;
		}
		$service_fairscene = new base_service_company_fair_fairscene();
		$service_fairscenestate = new base_service_company_fair_fairscenestate();
		$scene = $service_fairscene->getFairscene($scene_id, $service_fairscenestate->enable, 'scene_id,begin_time,end_time');
		$service_fair_job = new base_service_company_fair_fairjob();
		$result = $service_fair_job->delFairJob($company_info['company_id'], $scene, $job_id);
		if ($result === 6) {
			$json['success'] = '删除职位成功';
			$json['sceneid'] = $scene_id;
			echo json_encode($json);
			return;
		}
		$des = '';
		switch ($result) {
			case 1:
				$des = '对不起，该场次不存在';
				break;
			case 2:
				$des = '对不起，本次招聘会已经结束';
				break;
			case 3:
				$des = '贵公司服务已过期';
				break;
			case 4:
				$des = '您已申请参加，请等待我们的客服联系您';
				break;
			case 5:
				$des = '贵公司尚未开通现场招聘会服务，请联系我们的客服';
				break;
			case 7:
				$des = '该场招聘会正在进行不能删除职位';
				break;
			case 8:
				$des = '删除职位失败';
				break;
			case 9:
				$des = '您已申请参加，稍后客服将会与您联系';
				break;
			case 10:
				$des = '请申请参加本次招聘会或直接联系我们的客服';
				break;
			default:
				$des = '删除职位失败';
				break;
		}
		$json['error'] = $des;
		echo json_encode($json);
		return;
	}
	
	/**
	 * 批量删除职位
	 * Enter description here ...
	 * @param $inPath
	 */
	function pageDelFairJobMulti($inPath){
		$service_company = new base_service_company_company();
		$company_info = $service_company->getCompany($this->_userid,1,'company_id');
		if (empty($company_info)) {
			$json['error'] = '删除职位失败';
			echo json_encode($json);
			return;
		}
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_ids = base_lib_BaseUtils::getStr($path_data['jobids'],'array',null);
		$job_ids = base_lib_BaseUtils::getIntArrayOrString($job_ids);
		$scene_id = base_lib_BaseUtils::getStr($path_data['sceneid'],'int',0);
		if (empty($job_ids) || count($job_ids) == 0 || empty($scene_id)) {
			$json['error'] = '删除职位失败';
			echo json_encode($json);
			return;
		}
		$service_fairscene = new base_service_company_fair_fairscene();
		$service_fairscenestate = new base_service_company_fair_fairscenestate();
		$scene = $service_fairscene->getFairscene($scene_id, $service_fairscenestate->enable, 'scene_id,begin_time,end_time');
		$service_fair_job = new base_service_company_fair_fairjob();
		$result = $service_fair_job->delFairJobMulti($company_info['company_id'], $scene, $job_ids);
		if ($result === 6) {
			$json['success'] = '删除职位成功';
			$json['sceneid'] = $scene_id;
			echo json_encode($json);
			return;
		}
		$des = '';
		switch ($result) {
			case 1:
				$des = '对不起，该场次不存在';
				break;
			case 2:
				$des = '对不起，本次招聘会已经结束';
				break;
			case 3:
				$des = '贵公司服务已过期';
				break;
			case 4:
				$des = '您已申请参加，请等待我们的客服联系您';
				break;
			case 5:
				$des = '贵公司尚未开通现场招聘会服务，请联系我们的客服';
				break;
			case 7:
				$des = '该场招聘会正在进行不能删除职位';
				break;
			case 8:
				$des = '删除职位失败';
				break;
			case 9:
				$des = '您已申请参加，稍后客服将会与您联系';
				break;
			case 10:
				$des = '请申请参加本次招聘会或直接联系我们的客服';
				break;
			default:
				$des = '删除职位失败';
				break;
		}
		$json['error'] = $des;
		echo json_encode($json);
		return;
	}
	
	/**
	 * 修改职位弹窗
	 * Enter description here ...
	 * @param $inPath
	 */
	function pageJobEditShow($inPath){
		$service_company = new base_service_company_company();
		$company_info = $service_company->getCompany($this->_userid,1,'company_id');
		if (empty($company_info)) {
			return;
		}
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($path_data['jobid'],'int',0);
		if (empty($job_id)) {
			return;
		}
		$this->_aParams['job_id'] = $job_id;
		$service_fair_job = new base_service_company_fair_fairjob();
		$the_job = $service_fair_job->getJob($company_info['company_id'], $job_id, 'job_id,scene_id,station,content');
		if (empty($the_job)) {
			return;
		}
		$this->_aParams['job'] = $the_job;
		$obj = base_lib_BaseUtils::getStr($path_data['obj'],'string','');
		$callback = base_lib_BaseUtils::getStr($path_data['callback'],'string','');
		$this->_aParams['obj'] = $obj;
		$this->_aParams['callback'] = $callback;
		return $this->render('fair/edit.html', $this->_aParams);
	}
	
	/**
	 * 修改职位
	 * Enter description here ...
	 * @param $inPath
	 */
	function pageJobEditDo($inPath){
		$service_company = new base_service_company_company();
		$company_info = $service_company->getCompany($this->_userid,1,'company_id');
		if (empty($company_info)) {
			$json['error'] = '修改职位失败';
			echo json_encode($json);
			return;
		}
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$scene_id = base_lib_BaseUtils::getStr($path_data['hddmodsceneid'],'int',-1);
		$job_id = base_lib_BaseUtils::getStr($path_data['hddmodjobid'],'int',-1);
		$service_fairscene = new base_service_company_fair_fairscene();
		$service_fairscenestate = new base_service_company_fair_fairscenestate();
		$scene = $service_fairscene->getFairscene($scene_id, $service_fairscenestate->enable, 'scene_id,begin_time,end_time');
		$validator = new base_lib_Validator();
		$fair_job['station'] = $validator->getStr($path_data['txtStation'],1,30,'职位名称填写错误','');
		$fair_job['content'] = $validator->getStr($path_data['txtContent'],1,4000,'职位详情填写错误','');
		if($validator->has_err){
			echo $validator->toJsonWithHtml();
			exit;
    	}
		$fair_job['company_id'] = $company_info['company_id'];
		$fair_job['scene_id'] = $scene_id;
		$fair_job['job_id'] = $job_id;
		$service_fair_job = new base_service_company_fair_fairjob();
		$result = $service_fair_job->modFairJob($fair_job, $scene);
		if ($result === 6) {
			$json['success'] = '修改职位成功';
			$json['sceneid'] = $scene_id;
			echo json_encode($json);
			return;
		}
		$des = '';
		switch ($result) {
			case 1:
				$des = '对不起，该场次不存在';
				break;
			case 2:
				$des = '对不起，本次招聘会已经结束';
				break;
			case 3:
				$des = '贵公司服务已过期';
				break;
			case 4:
				$des = '您已申请参加，请等待我们的客服联系您';
				break;
			case 5:
				$des = '贵公司尚未开通现场招聘会服务，请联系我们的客服';
				break;
			case 7:
				$des = '该场招聘会正在进行不能修改职位';
				break;
			case 8:
				$des = '修改职位失败';
				break;
			case 9:
				$des = '您已申请参加，稍后客服将会与您联系';
				break;
			case 10:
				$des = '请申请参加本次招聘会或直接联系我们的客服';
				break;
			default:
				$des = '修改职位失败';
				break;
		}
		$json['error'] = $des;
		echo json_encode($json);
		return;
	}
}
?>