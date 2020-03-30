<?php
/**
 * 置顶服务控制器111
 * @ClassName controller_index 
 * @Desc todo(这里用一句话描述这个类的作用) 
 * @author liuchang@huibo.com
 * @date 2014-2-17 下午02:51:51 
 *
 */
class controller_top extends components_cbasepage {

	/**
	 * 置顶详情
	 * @param  array $inPath url参数集
	 * @return json          json格式数据
	 */
	function pageIndex($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_id    = base_lib_BaseUtils::getStr($path_data['jobid'], 'int', 0);


		$company_resources = base_service_company_resources_resources::getInstance($this->_userid);
		list($status, $code, $params) = $company_resources->check($func="top", $params=['job_id' => $job_id]);
		if (!$status) {
			$this->_aParams['message'] = $params['msg'];
			return $this->render('showerror.html', $this->_aParams);
		}

		$this->_aParams['params'] = $params;

		$service_job = new base_service_company_job_job();
		$current_job = $service_job->getJob($job_id, 'job_id,jobsort,station,company_id,check_state,status,end_time,urgent_end_time');

		$common_jobsort = new base_service_common_jobsort();
		$this->_aParams['job_id']	= $job_id;
		$this->_aParams['jobsorts'] = $common_jobsort->getSelfAndParentJobsort($current_job['jobsort']);

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
    	foreach ((array)$datas as $data) {
    		if (empty($data['keyword']) && !empty($data['dllday']))
    			$validator->addErr("请选择置顶的关键词");

    		$tops[] = ['keyword' => $data['keyword'], 'delay_day' => $data['dllday']];
    	}

    	$company_resources = base_service_company_resources_resources::getInstance($this->_userid);
		list($status, $code, $params) = $company_resources->check($func="top", $params=['job_id'=>$job_id, "tops"=>$tops]);

		if (!$status)
			$validator->addErr($params['msg']);

    	if ($validator->has_err) {
    		echo json_encode($json=['status'=>false, 'msg'=>$validator->err[0]]);
    		exit;
    	}

    	echo json_encode($json=['status'=>true, 'params'=>$params]);
    	exit;
    }
}