<?php
/**
 * 招聘会
 * @ClassName controller_fair 
 * @Desc todo(这里用一句话描述这个类的作用) 
 * @author liuchang@huibo.com
 * @date 2013-11-11 下午03:21:34 
 *
 */
class controller_fair extends components_cbasepage{
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * 我的招聘会入口
	 * Enter description here ...
	 * @param $inPath
	 */
	function pageIndex($inPath){
		$this->_aParams['title'] = "汇博人才网_现场招聘_我的招聘会";
		$service_fairscene = new base_service_company_fair_fairscene();
		$current_time = date('Y-m-d H:i:s',time());
		$service_fairscenestate = new base_service_company_fair_fairscenestate();
		$scenelist = $service_fairscene->getFairSceneList(10, $service_fairscenestate->enable, $current_time, 'scene_id, date, subject, begin_time, end_time, predict_extent, predict_jobcount');
		//当前正在进行的场次
		$one_scene = array();
		//还未召开的场次
		$scenelst = array();
		if (!empty($scenelist->items)) {
			$service_company = new base_service_company_company();
			$current_company = $service_company->getCompany($this->_userid, true, 'company_id');
			if (empty($current_company)) {
				return;
			}
			$service_comfairservicescene = new base_service_company_fair_comfairservicescene();
			$service_comfairservicescenestate = new base_service_company_fair_comfairservicescenestate();
			//单位所有参加的场次
			$companyallscene = $service_comfairservicescene->getAllComFairServiceScene($current_company['company_id'], $service_comfairservicescenestate->started, 'scene_id');
			//单位所有参加的场次ID
			$companyallsceneid = array();
			foreach ($companyallscene->items as $value){
				array_push($companyallsceneid, $value['scene_id']);
			}
			$service_fairapply = new base_service_company_fair_fairapply();
			//单位所有申请了的场次
			$companyallapply = $service_fairapply->getCompanyApplyList($current_company['company_id'], '', 'scene_id');
			//单位所有申请了的场次ID
			$companyallapplyid = array();
			foreach ($companyallapply->items as $value){
				array_push($companyallapplyid, $value['scene_id']);
			}
			$weekarray = array("日","一","二","三","四","五","六");
			foreach ($scenelist->items as $value){
				if (in_array($value['scene_id'], $companyallsceneid)) {
					//已参加
					$value['isjoin'] = 1;
				}else if(in_array($value['scene_id'], $companyallapplyid)){
					//已申请
					$value['isjoin'] = 2;
				}else {
					//未参加
					$value['isjoin'] = 3;
				}
				if (strtotime($value['begin_time']) < time() && strtotime($value['end_time']) > time()) {
					$one_scene = $value;
				}
				else {
					$value['theweekday'] = '星期'.$weekarray[date('w', strtotime($value['date']))];
					$value['thesimpledate'] = date("m-d", strtotime($value['date']));
					$value['thedate'] = date("Y年m月d日", strtotime($value['date']));
					$value['thebegintime'] = date("H:i", strtotime($value['begin_time']));
					$value['theendtime'] = date("H:i", strtotime($value['end_time']));
					array_push($scenelst, $value);
				}
			}
		}
		$this->_aParams['one_scene'] = $one_scene;
		$this->_aParams['scenelist'] = $scenelst;
		$config = SXML::load('../config/config.xml');
		if (!empty($config)) {
			$this->_aParams['config'] = $config;
		}
		return $this->render('fair/index.html', $this->_aParams);
	}
	
	/**
	 * 申请参会弹窗
	 * Enter description here ...
	 * @param $inPath
	 */
	function pageFairapplyshow($inPath){
		$service_company = new base_service_company_company();
		$company_info = $service_company->getCompany($this->_userid,1,'company_name,linkman,link_tel');
		if (empty($company_info)) {
			return;
		}
		$this->_aParams['current_company'] = $company_info;
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$scene_id = base_lib_BaseUtils::getStr($path_data['sceneid'],'int',0);
		if (empty($scene_id)) {
			return;
		}
		$this->_aParams['scene_id'] = $scene_id;
		$obj = base_lib_BaseUtils::getStr($path_data['obj'],'string','');
		$callback = base_lib_BaseUtils::getStr($path_data['callback'],'string','');
		$this->_aParams['obj'] = $obj;
		$this->_aParams['callback'] = $callback;
		return $this->render('fair/fairapply.html', $this->_aParams);
	}
	
	/**
	 * 申请参会
	 * Enter description here ...
	 * @param $inPath
	 */
	function pageFairapplyDo($inPath){
		$service_company = new base_service_company_company();
		$company_info = $service_company->getCompany($this->_userid,1,'company_id');
		if (empty($company_info)) {
			$json['error'] = '申请参会失败';
			echo json_encode($json);
			return;
		}
		//验证
		$validator = new base_lib_Validator();
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$fairapply['scene_id'] = base_lib_BaseUtils::getStr($path_data['hddthesceneid'],'int',0);
		if (empty($fairapply['scene_id'])) {
			$json['error'] = '申请参会失败';
			echo json_encode($json);
			return;
		}
		$fairapply['company_name'] = $validator->getNotNull($path_data['txtCompanyName'],'请输入企业名称');
		$fairapply['link_man'] = $validator->getNotNull($path_data['txtLinkMan'],'请输入联系人');
		$fairapply['tel'] = $validator->getTel($path_data['txtLinkTel'],'请输入联系电话');
		$fairapply['describe'] = $validator->getStr($path_data['textMark'],0,256,'备注信息不能超过256个字符',true);
		if($validator->has_err){
			echo $validator->toJsonWithHtml();
			exit;
    	}
		$service_fairscene = new base_service_company_fair_fairscene();
		$service_fairscenestate = new base_service_company_fair_fairscenestate();
		//当前场次
		$current_scene = $service_fairscene->getFairscene($fairapply['scene_id'], $service_fairscenestate->enable, 'scene_id,begin_time,end_time');
		if (empty($current_scene)) {
			$json['error'] = '申请参会失败';
			echo json_encode($json);
			return;
		}
		//判断当前场次是否已开始或结束
		if (strtotime($current_scene['begin_time']) <= time() && strtotime($current_scene['end_time']) >= time()) {
			$json['error'] = '该招聘会已经开始，申请参会失败';
			echo json_encode($json);
			return;
		}else if(strtotime($current_scene['end_time']) < time()){
			$json['error'] = '该招聘会已经结束，申请参会失败';
			echo json_encode($json);
			return;
		}
		//判断是否已经开通了该场招聘会
		$service_comfairservicescene = new base_service_company_fair_comfairservicescene();
		$service_comfairservicescenestate = new base_service_company_fair_comfairservicescenestate();
		$company_fair_scene = $service_comfairservicescene->getComFairServiceScene($company_info['company_id'], $current_scene['scene_id'], $service_comfairservicescenestate->started, '', 'fair_service_scene_id');
		if (!empty($company_fair_scene)) {
			$json['info'] = '您已参加了该场招聘会';
			echo json_encode($json);
			return;
		}
		//判断是否已经申请了该场招聘会 如果申请过直接提示成功
		$service_fairapply = new base_service_company_fair_fairapply();
		$is_apply = $service_fairapply->isCompanyAppliedFiar($company_info['company_id'], $current_scene['scene_id']);
		if ($is_apply) {
			echo json_encode(array('success'=>'申请参会成功', 'sceneid'=>$current_scene['scene_id']));
			return;
		}
		//添加申请记录
		$fairapply['company_id'] = $company_info['company_id'];
		$fairapply['apply_date'] = date('Y-m-d H:i:s',time());
		$service_fairapplystate = new base_service_company_fair_fairapplystate();
		$fairapply['state'] = $service_fairapplystate->applied;
		$result = $service_fairapply->addFairApply($fairapply);
		if ($result) {
			echo json_encode(array('success'=>'申请参会成功', 'sceneid'=>$current_scene['scene_id']));
			return;
		}
		$json['error'] = '申请参会失败';
		echo json_encode($json);
		return;
	}
}
?>