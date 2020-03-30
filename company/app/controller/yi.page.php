<?php

/*首页
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class controller_yi extends components_cbasepage {

	function __construct() {
		parent::__construct(false);
	}

	function pageIndex($inPath) {
                
                $a = 1500;
                $b = 4000;
                echo $b/$a;exit; 

		var_dump($output);
		
		$lambda_fuc = function($string){
  			  print $string;
		};
		$lambda_fuc("laruence");
		
		var_dump(true=='1');
		var_dump(date('Y-m-d',mktime(0,0,0,10,30,2013)));
		return;
		$iUserId = base_lib_BaseUtils::getCookie('userid');
		$sUserName = base_lib_BaseUtils::getCookie('nickname');
		$sUserType = base_lib_BaseUtils::getCookie('usertype');
		$sKey =  base_lib_BaseUtils::getCookie('userkey');
		
		echo $iUserId;
		
		
		return;
		
		
		$svr_job = new base_service_load(new base_service_job());
		//$job = $svr_job->query('select top 1 * from info_job');
		
		$job =  $svr_job->selectOne(array('job_id'=>23),'job_id,station');

//		var_dump($job);

		
		//define("DEBUG",true);

		$model_job = new base_service_job();

		$job = $model_job->selectOne(array('job_id'=>1),'job_id,station');
//		var_dump($job);

		$job = $model_job->selectOne(array('job_id'=>888),'job_id,station');
		//返回一个empty的数组
		if (!empty($job))
		{
			//
		}
//		var_dump($job);

		//获取url参数/post参数统一使用
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		echo $pathdata['station'];

		//分页演示
		$iPage = 2;  //当前是第几页
		$limit = base_lib_Constant::PAGE_SIZE;  //每页显示多少条
		$model_job->setLimit($limit);
		$model_job->setPage($iPage);
		$model_job->setCount(true);
		$jobs = $model_job->select(array('job_id>1','job_id>2'),'job_id,station'); //数据查询
//		var_dump($jobs);
		$arnum = $jobs->totalSize;
		$this->_aParams['list'] = $jobs->items;
		if($arnum > $limit) { //如果总个数大于每页显示的个数，则进行分页，否则不分页
			$page_bar = $this->pageBar($arnum, $limit, $iPage, $inPath);
			$this->_aParams['pagebar'] = $page_bar;
		}
		$this->_aParams['page'] = $iPage;		

		//后端生成url
		$url = SlightPHP::createUrl('/yi/add',array('x'=>'1'));

//		echo $url;

//		var_dump($_GET);

		return $this->render('job/list.html', $this->_aParams);
	}

	function pageAdd($inPath) {
		
		//var_dump($this->_aParams);
		return $this->render('job/add.html',array());
	}

	function pageAddDo($inPath) {
		
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		//echo $pathdata['station'];

		//校验入库
		
		$val = new base_lib_Validator();
		
		$job['station'] = $val->getStr($pathdata['station'],1,64,'err');

		if($val->has_err)
		{
			//错误处理，反馈给用户
		}
		else
		{
			$model_job = new base_service_job();
			$model_job->set($job);
			$job_id = $model_job->save();
			if($job_id>0)
			{
				$url = SlightPHP::createUrl('/yi/');
				$this->redirect($url);
			}
//			echo $job_id;
		}
	
	}

	function pageMod($inPath){
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		//校验参数
		$job_id = base_lib_BaseUtils::getStr($pathdata['job_id'],'int',0);
		if($job_id == 0)
		{
			//参数出错，需要处理
		}
		else
		{
			//校验权限,只能是当前单位修改自己的职位
			$model_job = new base_service_job();
			$job = $model_job->selectOne(array('job_id'=>$job_id,'company_id'=>$this->_userid),'job_id,station'); //查询的时候要带单位编号
			if($job)
			{
				$this->_aParams['job'] = $job;
				return $this->render('job/mod.html',$this->_aParams);
			}
		}
	}


	function pageModDo($inPath){
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		//校验参数
		$val = new base_lib_Validator();
		$job = array();
		$job['station'] = $val->getStr($pathdata['station'],1,64,'岗位名称长度不正确');
		$job['job_id'] = $val->getNum($pathdata['job_id'],0,'max','职位编号不正确');

		if($val->has_err){
			//
			echo 'err';
			var_dump($val->err);
		}
		else{
			$model_job = new base_service_job($job['job_id']);
			$job['company_id'] = $this->_userid; //这一句不要忘记，否则其他单位也可以修改了
			$model_job->set($job);
			$update = $model_job->save();
			if($update)
			{
				$this->redirect_url('/yi');
			}
		}
	}
	
	
	function pageTestSendTemplate($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$id = base_lib_BaseUtils::getStr($pathdata['id'],'int',0);
		if($id==0) {
			return;
		}
		var_dump($id);
		$service_weixin = new base_service_interface_weixin();
		$service_weixin->sendInviteTemplateMsg($id);
	}


}


