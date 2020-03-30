<?php
/**
 * 
 * 11测试用11例211211ff222221222111
 * @author ZhangYu
 *
 */
	class controller_test extends components_cbasepage {
		private $audition_day       = 1;//邀请面试是第2天
		private $get_linkway_day    = 7; //获取联系方式是7天后
		private $limit              = 100;//一次啦100条数据
		private $total              = 0;
		public  $service_getlinkway = null;
		public  $service_job        = null;
		public  $service_appraise   = null;
		public  $service_company    = null;
		public  $service_jobsort    = null;

		function __construct() {
			parent::__construct(false);
	    }
		

		public function index(){
			/**
			 * 重庆套餐各套餐点数消费
			 * $params 数据如下
			 * consume_type  参数必填，且只有以下值
			 *
			 * refresh 职位刷新 			传job_id
			 * chat    聊一聊   			传resume_id，company_id，person_id
			 * get_link 获取联系方式     传 resume_id，company_id，
			 * invite  邀请面试          传 resume_id，company_id
			 * accounts  子账号         	传 company_id
			 * apply    投递           暂缺
			 *
			 * 统一返回数组 如array('code' => 200 , 'msg' => '企业子账号个数扣除成功', 'result' => true);  result=true 扣除相应点数成功
			 * 注:职位刷新逻辑已在方法里已经处理，其他逻辑自行根据返回结果处理
			 */
			$company_resources            = base_service_company_resources_resources::getInstance($this->_userid);
			$params = array(
				'consume_type' => 'refresh',
				'job_id'		=> 1111
			);
			$consume_result = $company_resources->consume('cq_setmeal_consume',$params);
		}

		//临时同步mogo企业操作日志
	    function pageupdateMongologsToDB()
		{
			$xx = new base_service_company_companyaccountlog();
			$xx->updateMongologsToDB();
		}

		public function pagegetPerson(){
			$service_person_person = new base_service_person_person();
			$person_info = $service_person_person->getPersonByNewRegster();
			$service_person_apply = new base_service_person_apply();
			$apply_info = $service_person_apply->getApplyByNewApply();

			$service_fair_comment = new base_service_fair_comment();

			$info = $service_fair_comment->getCompanyListByComment(10,1);
			var_dump($info);

		}

        function pageTestBindAccount($inPath)
        {
            ini_set("display_errors", "On");
            error_reporting(E_ALL || E_STRICT);
            $service_company = new base_service_company_company();
            $company = $service_company->newLoginTest('17388202086', 'a.company_id,a.company_name,a.company_logo_path,b.account_id,b.is_main,b.user_name,a.site_type', true);
            var_dump($company);

            $service_related = base_service_hractivity_related::getInstance();
            $related_info    = $service_related->getPersonByAccount($company["account_id"],"person_id,company_id,account_id");
           if(!empty($related_info)){
               var_dump("begion ");
           }
            var_dump($related_info);

        }


	    function pageTestCharge($inPath) {
            $link = "http://m.huibo.com/share/NewIndex/?key=15525596&ac=2&flag=entmz1j59";
            
            SQrcode::png($link);
	    }

	    /**
	     * 
	     * 公司模板测试
	     * @param $inPath
	     */
		function pageTestCompanyTemplate($inPath) {
			$service_templatehelper = new base_service_load(new base_service_common_templatehelper());
			$template = $service_templatehelper->getCompanyTemplate('04');
			var_dump($template);
			$arr = array('[@单位名称]'=>'重庆人才网','[职位名称]'=>'软件工程师');	
			$content = $service_templatehelper->replaceFromContent($arr,$template->Content);
			var_dump($content);
		}
		
		function pageApplyList() {
			// 时间test
			$time = 7;
			$date = date('Y-m-d',strtotime("-{$time} days")).' 00:00:00';
			var_dump($date);
			
		}
		
	public function pageHistory($inPath) {
		$params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_id = base_lib_BaseUtils::getStr($params['resumeid'],'int',0);		
		$this->_getResumeHistoryRecord($this->_userid, $resume_id);
	}	
		
		
	/**
	 * 获取简历历史记录	
	 */
	private function _getResumeHistoryRecord($company_id,$resume_id) {
		$service_apply  = new base_service_company_resume_apply();
		$service_invite = new base_service_company_resume_jobinvite();
		$service_remark = new base_service_company_resume_resumeremark();
		$applys = $service_apply->getApplyByPerson($company_id,$resume_id,null,'station,create_time'); //TODO:字段
		$invites= $service_invite->getInviteList($resume_id,$company_id,'station,create_time,audition_time');	
		$remarks = $service_remark->getResumeRemarkList($company_id, $resume_id, null, 'remark,update_time');
		$search_refuse = function ($date) {
		  return function ($apply) use ($date) { return $apply['re_status']==3&& date('Y-m-d',strtotime($apply['create_time']))==$date; };
		};
		$search_norefuse = function ($date) {
		  return function ($apply) use ($date) { return $apply['re_status']!=3&& date('Y-m-d',strtotime($apply['create_time']))==$date; };
		};	
		$search_invite = function ($date) {
		    return function ($invite) use ($date) { return date('Y-m-d',strtotime($invite['create_time']))==$date; };
		};
		$search_remark = function ($date) {
		   return function ($remark) use ($date) { return  date('Y-m-d',strtotime($remark['update_time']))==$date; };
		};
		
		$items = array();
		for ($i = 0; $i <= 90; $i+=1){			
			 $d  = date('Y-m-d',strtotime("-{$i} days"));
			 $arr_refuseapplys = array_filter($applys->items, $search_refuse($d));
			 $arr_invite =  array_filter($invites->items, $search_invite($d));
			 $arr_refuseapplys = base_lib_BaseUtils::array_sort($arr_refuseapplys, 'create_time');
			 $arr_invite = base_lib_BaseUtils::array_sort($arr_invite, 'create_time');
			 $apply  =reset($arr_refuseapplys);
			 $invite  =reset($arr_invite);
			 // 邀请
			 if($apply&&$invite) {
			 	if(strtotime($apply['create_time'])>strtotime($invite['create_time'])) {
			 		array_push($items,array('time'=>$d,'content'=>"邀请面试:面试职位【{$invite['station']}】，面试时间【{$invite['audition_time']}】"));
			 	}else {
			 		array_push($items,array('time'=>$d,'content'=>'婉言谢绝'));
			 	}
			 }elseif($apply) {
			 		array_push($items,array('time'=>$d,'content'=>'婉言谢绝'));			 		
			 }elseif($invite) {
			 		array_push($items,array('time'=>$d,'content'=>"邀请面试:面试职位【{$invite['station']}】，面试时间【{$invite['audition_time']}】"));
			 }
			 // 求职申请	 
			 $arr_apply = array_filter($applys->items, $search_norefuse($d));
			 if(count($arr_apply)>0) {
			 	array_push($items,array('time'=>$d,'content'=>'应聘职位：'. $this->_arrFieldJoin($arr_apply,'station')));	
			 }			 
			 
			 // 简历备注
			 $arr_remark = array_filter($remarks->items, $search_remark($d));
			 if(count($arr_remark)>0) {
			 	array_push($items,array('time'=>$d,'content'=>'添加备注：'. $this->_arrFieldJoin($arr_remark,'remark')));	
			 }
		     
			 // 30天以内有记录
			 if($i==30) {
			 	if(count($items)>0) {
			 		echo '近30天有记录';
			 	}	
			 }
		}	
		var_dump($items);	
	}	

		// 将数组中的字段连接起来
	private function _arrFieldJoin($arr,$field,$separator=',') {
   	   $new_arr = array();
   	   foreach ($arr as $item){
			array_push($new_arr, $item[$field]);
	   }
	   return implode($separator,$new_arr);		
    }	
	
		function pageIndex($inPath){
			  $company_resources = base_service_company_resources_resources::getInstance(114509992);
                $ismember = $company_resources->isMember();
			var_dump($ismember);die();
			//$agelower = date('Y')-15;
			print_r(strtotime(date('Y-m')));
			//echo $agelower
			/*$audit['audit_time'] = "2014-01-15 00:12:35";
			
			$audittime = date('Y-m-d',strtotime($audit['audit_time']));					
			$timediff = strtotime(date('Y-m-d','1964-4-20')) - strtotime($audittime);
			$days = intval($timediff/86400);*/
			
			
//			$postvar['person_id'] = 11;//当前登录用户
//			$postvar['page_index'] = 1;//当前页面
//			$postvar['page_size'] = 20;//每页显示数
//			$servcie_sphinx = new base_service_sphinx_sphinx();
//			$result = $servcie_sphinx->getPageRecommend($postvar);
//			
//			print_r($result);

/*			$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
 			$keyword = base_lib_BaseUtils::getStr($pathdata['txtKeyword'],'string',"");
			$servcie_sphinx = new base_service_sphinx_sphinx();
			
			$postvar['keyword'] = $keyword;
			
			$result= $servcie_sphinx->getFilterCompany($postvar);
			
			$this->_aParams["companys"] = $result["company_name"];
			$this->_aParams["total"]  = $result["total"];
			
			
			print_r($keyword);
			return $this->render('./test.html', $this->_aParams);*/
			/*
			$jobsortService = new base_service_common_indexjobsort();
			$jobSorts = $jobsortService->getAllJobsort();
			$topSorts = $jobsortService->getTopJobsort();
			
			$sorts = array();
			
			foreach ($topSorts as $key=>$value){
				$sorts[$value["jobsort"]]["id"]=$value["jobsort"];
				$sorts[$value["jobsort"]]["name"]=$value["jobsort_name"];
				$subSorts = array(); 
				foreach ($jobSorts as $sKey=>$sValue){
					if($sValue["parent_id"] == $value["jobsort"]){
						$subSorts[$sKey]["id"] = $sValue["jobsort"];
						$subSorts[$sKey]["name"] = $sValue["jobsort_name"];
						$subSorts[$sKey]["order"] = $sValue["order_no"];				
						$tSorts = array();
						foreach ($jobSorts as $tKey=>$tValue){
							if($tValue["parent_id"] == $sValue["jobsort"]){
								$tSorts[$tKey]["id"] = $tValue["jobsort"];
								$tSorts[$tKey]["name"] = $tValue["jobsort_name"];								
							}
						}
						$subSorts[$sKey]["sub"] = array_values($tSorts);
					}
				}
				$sorts[$value["jobsort"]]["sub"] = array_values($subSorts);
			}
			
			
			
			
			
			$jsonstr =  json_encode(array_values($sorts));
			
			//$jsonstr = str_replace("{", "[{", $jsonstr);
			//$jsonstr = str_replace("}", "}]", $jsonstr);
			
			echo $jsonstr;*/
			//print_r($sorts["01"]);

		}
		
		function pageTime($inPath){
			print_r(date("Y-m-d H:i:s","1388412591"));
		}
		
		//上传组件后端测试
		function pageUploadfile($inPath){
			return $this->render('./uploadfile.html', $this->_aParams);
		}
		
		//上组件后端上传到资源服务器测试
		function pageUploadfileDo($inPath){
			$file = $_FILES['Filedata'];
			
			$extension_name = base_lib_BaseUtils::fileext($file['name']);
			
			$xml = SXML::load('../config/company/CompanyPersonalizedTemplate.xml');
			if(!is_null($xml)){
				$logofolder = $xml->LogoFolder;
				$companyTemplatePath = $xml->CompanyTemplatePath;
				$virtualName = $xml-> VirtualName;
			}
			
			$path = "{$companyTemplatePath}{$logofolder}";
			
			
			$fileParts = pathinfo($file['name']);
			$postvar['path'] = $path;//存放路径 配置文件件读取
			$postvar['name'] = date("YmdHis").rand(1, 100).'.'.$extension_name;
			//$postvar['type'] = 'file';//表示上传文件，默认上传图片
			
			$postvar['maxWidth'] = 600; //图片最大宽度,上传图片时必填
			$postvar['maxHeight'] = 600; //图片最大高度,上传图片时必填
			
			$postvar['thumbMaxWidth'] = 210;//缩略图最大宽度,非必填
			$postvar['thumbMaxHeight'] = 137; //缩略图最大高度,非必填
			$postvar['thumbSuffix'] = 'thumb'; //缩略图后缀,非必填
			
			//调用方法 成功返回{'success',true}
			$result = base_lib_Uploadfilesv::postfile($postvar, $file['name'],$file['tmp_name'], $file['type']);
			
			print_r($result);
			echo base_lib_Constant::UPLOAD_FILE_URL.'/'.$virtualName.'/'.$logofolder.'/'.$postvar['name'];
		}
		
		function pageMoveFile($inPath){
			
			$fileName = array('2013102317585530.gif','2013102413594510.jpg','2013102413594634.jpg');
			
			$postvar['newfile'] = "D:\IMGVirtual\CompanyImages\Logo";
			$postvar['oldfile'] = "D:\IMGVirtual\CompanyTemplates\Logo";
			$postvar['names'] = $fileName;
			$postvar['thumbSuffix'] = 'thumb';
			$postvar['authenticate'] = "photo";
			base_lib_Uploadfilesv::moveFile($postvar);
		}
		
		function pageDelFile($inPath){
			$postvar['file'] = "D:\IMGVirtual\CompanyImages\Logo";
			$fileName = array('2013102317585530.gif','2013102413594510.jpg','2013102413594634.jpg');
			$postvar['names'] = $fileName;
			$postvar['thumbSuffix'] = 'thumb';
			$postvar['authenticate'] = "photo";
			base_lib_Uploadfilesv::delFile($postvar);
		}
		
		function pageShowThumbnail($inpath){
			print_r(getimagesize('D:\IMGVirtual\CompanyTemplates\Logo\1.gif'));
		}
		
		function pageHtmlDwon($inpath){
			return $this->render('./test.html', $this->_aParams);
		}
		
		function pageDate($inPath){
			$imgsrc = base_lib_BaseUtils::getThumbImg("//assets.huibo.com/photo/2012-10-26/1026157432.jpg",100,100);
			
			
			print_r($imgsrc);
//			$begin_date=date('Y-m',strtotime("2012-6-25"));
//			$end_date=date('Y-m',strtotime(date('Y-m-d')));
//			
//			list($y1,$m1) = explode('-',$begin_date); 
//			list($y2,$m2) = explode('-',$end_date); 
//			$y = $m = 0; 
//			$math = ($y2-$y1)*12+$m2-$m1; 
//			$y = floor($math/12); 
//			$m = intval($math%12); 
//			
//			echo $y."年".$m;
//			
//			echo date("Y-m-d",strtotime("-1 years -6 months"));
//			
//			
//			echo '<br/>';
//			
//			echo strtotime("2012/12");
//			
//			
//			$strtime = "";
//			if(empty($strtime)||$strtime=="至今"){
//				$time= null;
//			}else {
//				$stime = explode("/", $strtime);
//				$time = $stime[0].'-'.$stime[1].'-01';
//			}
//			return $time
//			
//			
//			if($stime[0]=="至今"){
//				$start_time = null;
//			}else{
//				$start_time = $stime[0].'-'.$stime[1].'-01';
//			}
//			
//			echo $start_time;
//			
			//echo date('Y-m-d',strtotime("2012/12"));
			
		}
		
		function pageBase64(){
			$base64str = "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";
			$data= base64_decode($base64str);
			
			
			$adirPath = '../temp';
			$afileDir = "{$dirPath}/appphoto/";
			$aname = date("md").rand(100000, 999999).'.jpg';
			$afilename = "{$fileDir}/$aname";
			base_lib_BaseUtils::writeFile("{$afilename}",$data);
			
			
			$xml = SXML::load('../config/config.xml');
			if(!is_null($xml)){
				$file_mapping_path = $xml->FileMappingPath;
			}
			
			$personxml = SXML::load('../config/person/upload.xml');
			if(!is_null($personxml)){
				$photo_dir = $personxml->PersonPhotoDir;
				$photo_file_type = $personxml->PersonPhotoFileType;
				$max_size = $personxml->MaxPersonPhotoSize;
			}
			
			$dirpath =$file_mapping_path;
			$photopath = $photo_dir."/".date("Y-m-d");
			$file_name = date("md").rand(100000, 999999);
			
			
			$postvar['type'] = 'personphoto';
			$postvar['path'] = $dirpath.$photopath;
			$postvar['big_size'] = "180x225";
			$postvar['middle_size'] = "120x150";
			$postvar['small_size']="48x60";
			$postvar['name'] = "{$file_name}.jpg";
			$postvar['middle_name'] = "{$file_name}_middle.jpg";
			$postvar['small_name']= "{$file_name}_small.jpg";
			$result = base_lib_Uploadfilesv::postfile($postvar, $aname,$afilename, 'application/octet-stream');
			
			if($result['success']){
				//保存到数据库
				$person['big_photo'] = $photopath."/{$file_name}.jpg";
				$person['has_big_photo'] = 1;
				$person['photo'] = $photopath."/{$file_name}_middle.jpg";
				$person['small_photo']= $photopath."/{$file_name}_small.jpg";			
				$service_person = new base_service_person_person();
				$service_person->updatePerson("person_id = '{$this->_userid}'", $person);
			}
			
//			$dirPath = '../temp';
//			$fileDir = "{$dirPath}/test/1/";
//			$filename = "{$fileDir}/huibo_1.jpg";
//			base_lib_BaseUtils::writeFile("{$filename}",$data);
			//header("Content-type: image/png");
			//echo $filename;
		}
		
		public function pageTestTemp($inPath){
			$service_company = new base_service_company_company();
			$company_info = $service_company->getCompany(28724394, $is_effect,"company_name,info,address");
			
			$this->_aParams["company_info"] = $company_info;
			return $this->render("test.html",$this->_aParams);
		}
        
        //position: loginForm:登录弹窗，register：注册，loginSuccess：登陆成功，joblist:职位管理，apply：简历管理
        public function pageClickCount($inPath){
            
            //mongo分组统计
            $position_arr = [
                "serach_calling"=>"专业搜索",
                "regist"=>"注册",
                "login"=>"登录",
                "login_success"=>"登录成功",
                "regist_success"=>"注册成功"
            ];
            header("Content-type: text/html; charset=utf-8");
            $service_cache_clicks = new base_service_cache_clicks();
            $click_count = $service_cache_clicks->getClicksGroup();
            echo "<table style='border:1px solid #F00'><tr><td>日期</td><td>位置</td><td>点击数</td><td>ip数</td></tr>";
            foreach ($click_count['retval'] as $value) {
//                echo "<tr><td>{$value['date']}</td><td>{$position_arr[$value['position']]}</td><td>{$value['click_total']}</td><td>{$value['ip_total']}</td></tr>";
                echo "<tr><td>{$value['date']}</td><td>{$value['position']}</td><td>{$value['click_total']}</td><td>{$value['ip_total']}</td></tr>";
            }
            echo "</table>";
        }

		public function pageaddress(){
//			$json_string = file_get_contents('./zhilian.json');
//			$json_string = (array)json_decode($json_string);
			$service_job_outerclock = new base_service_job_outerclock();
			$address_list = $service_job_outerclock->getAllAddress();
			$service_common_area = new base_service_common_area();
			$area_list = $service_common_area->getAllArea();

			//var_dump($area_list);
			foreach($address_list as $key=>$value){
				foreach($area_list as $k=>$v){
					if($value['outer_name'].'市' == $v['area_name'] && empty($value['inner_id'])){
						$data = array(
							'inner_id'		=> $v['area_id']
						);
						$service_job_outerclock->updateInnerId($value['id'],$data);
					}
				}
			}

		}
        
        public function pageTest(){
			$service_common_autologinurl = new base_service_common_autologinurl();

        }


		public function pageTestjob(){

			ob_start();
			$service_getlinkway         = new base_service_company_appraise_linkwayget();
			$service_appraise           = new base_service_person_appraise_appraise();
			$service_job                = new base_service_company_job_job();
			$service_company            = new base_service_company_company();
			$service_jobsort            = new base_service_common_jobsort();

			$this->service_getlinkway   = $service_getlinkway;
			$this->service_job          = $service_job;
			$this->service_appraise     = $service_appraise;
			$this->service_company      = $service_company;
			$this->service_jobsort      = $service_jobsort;

			//第一步 获取获取联系方式的数据
			ob_flush();
			flush();
			echo "#=======get_link_way type start========#\n";
			while(true){
				ob_flush();
				flush();
				$get_linkway_day          = $this->get_linkway_day;
				$get_linkway_time         = date("Y-m-d 00:00:00",strtotime("-{$get_linkway_day} days"));
				echo $get_linkway_time;
				$data_list1 = $service_getlinkway->getData($get_linkway_time, "person_id,company_id,get_id,create_time,job_id,station", "get_linkway",$this->limit);
				$data_list2 = $service_getlinkway->getData($get_linkway_time, "person_id,company_id,get_id,create_time,job_id,station", "down_loaded",$this->limit);
				$data_list = array_merge($data_list1,$data_list2);
				var_dump($data_list);
				if(empty($data_list)){
					echo "#=======get_link_way type end  total:{$this->total}========#\n";
					break;
				}
				$this->addAppraise($data_list);
			}
			//第二步 邀请面试的数据
			echo "#=======get_invite type start========#\n";
			while(true){
				ob_flush();
				flush();
				$audition_day        = $this->audition_day;
				$audition_time       = date("Y-m-d 00:00:00",strtotime("-{$audition_day} days"));
				echo $audition_time;
				$data_list = $service_getlinkway->getData($audition_time, "person_id,company_id,get_id,audition_time,job_id,station,get_type,create_time", "invite",$this->limit);

				if(empty($data_list)){
					echo "#=======get_invite type end========#\n";
					break;
				}
				$this->addAppraise($data_list);
				$get_ids = base_lib_BaseUtils::getPropertys($data_list,"get_id");
				$condition_one = array();
				array_push($condition_one, array("get_id"=>array("in"=>$get_ids)));
				$this->service_getlinkway->update($condition_one,array("is_set_appraise"=>1));
			}
			echo "##========add end total:{$this->total}===========##\n";
			return;
		}

		private  function addAppraise($data_list){
			$job_ids    = base_lib_BaseUtils::getPropertys($data_list,"job_id");
			$job_list   = array();
			if(!empty($job_ids)){
				$job_info   = $this->service_job->getJobs($job_ids, "job_id,jobsort");
				$job_list   = base_lib_BaseUtils::array_key_assoc($job_info, "job_id");
			}
			$company_ids = base_lib_BaseUtils::getPropertys($data_list, "company_id");
			if(!empty($company_ids)){
				$company_info   = $this->service_company->getCompanys($company_ids, "company_id,company_name,is_allow_appraise");
				$company_list   = base_lib_BaseUtils::array_key_assoc($company_info, "company_id");
			}
			foreach($data_list as $v){
				ob_flush();
				flush();
				$items = array();
//            if($company_list[$v["company_id"]]['is_allow_appraise'] == "0"){
//                $this->total+=1;
//                echo "#=======wait company_id: {$v["company_id"]}  第{$this->total} 条数据  该企业未开启面试评价========#\n";
//            }else{
				$items["station"] = !empty($v["station"]) ? $v["station"] : "其他";
				$items["job_id"]  = $v["job_id"];
				$jobsort = !empty($job_list[$v["job_id"]]) ? $job_list[$v["job_id"]]["jobsort"] : "";
				if(empty($jobsort)){
					$items["jobsort"] = "011600";
					$items["jobsort1"] = "011699";
				}else{
					$items["jobsort"] = $this->service_jobsort->getParentJobsort($jobsort);
					$items["jobsort1"] = $jobsort;
				}

				$items["company_name"] = $company_list[$v["company_id"]]["company_name"];
				$items["get_type"]      = $v["get_type"];
				$items["get_time"]      = $v["create_time"];

				$result           = $this->service_appraise->addAppraise($v["person_id"],$v["company_id"],$v["create_time"],$items);
				$this->total+=1;
				if($result){
					echo "#=======success person_id:{$v["person_id"]} and company_id: {$v["company_id"]}  第{$this->total} 条数据========#\n";
				}else{
					echo "#=======fail result:{$result}  get_id:{$v["get_id"]} person_id:{$v["person_id"]} and company_id: {$v["company_id"]}========#\n";
				}
//            }
			}

			$get_ids = base_lib_BaseUtils::getPropertys($data_list,"get_id");
			$condition_one = array();
			array_push($condition_one, array("get_id"=>array("in"=>$get_ids)));
			$this->service_getlinkway->update($condition_one,array("is_set_appraise"=>1));
		}

		public function pageNianTest($inPath){
			$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
			$type = base_lib_BaseUtils::getStr($path_data['type'],1);
			$service_company_yearusestatic = new base_service_company_yearusestatic();
			$img_path = $service_company_yearusestatic->getCompanyAnnualReportCode($this->_userid,$type);
			echo $img_path;
		}

		public function pageTestJobAppraise(){
			//获取所有的求职者已评价，企业未读，未发送短信，评价为差评的面试反馈
			$service_person_appraise_appraise = new base_service_person_appraise_appraise();
			$appraise_list = $service_person_appraise_appraise->getCompanyNotReadBadReviewAll();
			if($appraise_list){
				$appraise_ids = array_unique(base_lib_BaseUtils::getPropertys($appraise_list,"appraise_id"));
				$appraise_list = base_lib_BaseUtils::array_key_assoc($appraise_list,"appraise_id");
				//获取已发送短信的评论
				$service_person_appraise_msglog = new base_service_person_appraise_msglog();
				$send_msg_list = $service_person_appraise_msglog->getSendMsgByAppraiseIds($appraise_ids);
				$send_msg_appraise_ids = array_unique(base_lib_BaseUtils::getPropertys($send_msg_list,"appraise_id"));

				//获取未发送短信的评论id
				$not_send_appraise_ids = array_diff($appraise_ids,$send_msg_appraise_ids);
				if($not_send_appraise_ids){
					$appraise_list_temp = [];
					foreach($not_send_appraise_ids as $key => $val){
						if(isset($appraise_list[$val])){
							array_push($appraise_list_temp,$appraise_list[$val]);
						}
					}
					//给主账号发送短信
					$company_ids = base_lib_BaseUtils::getPropertys($appraise_list_temp,"company_id");
					$service_account = new base_service_company_account();
					$base_lib_SMS = new base_lib_SMS();
					$account_list = $service_account->getMainAccountByCompanys($company_ids, "company_id,account_id,mobile_phone,is_main");
					var_dump($account_list);
					//职位发布账号发短信
					$job_ids = base_lib_BaseUtils::getPropertys($appraise_list_temp,"job_id");
					$service_company_job_job = new base_service_company_job_job();
					$job_list = $service_company_job_job->getJobs($job_ids,"job_id,account_id");
					$job_account_ids = array_unique(base_lib_BaseUtils::getPropertys($job_list,"account_id"));
					$job_account_list = $service_account->getAccountByAccount_ids($job_account_ids,"company_id,account_id,mobile_phone,is_main")->items;
					var_dump($job_account_list);
					//去掉职位发布人为主账号的数据
					foreach($job_account_list as $key => $val){
						if($val['is_main'] == 1){
							unset($job_account_list[$key]);
						}
					}


					//合并数据
					$account_list = empty($job_account_list) ? $account_list : array_merge($account_list,$job_account_list);
					var_dump($account_list);
					$msg_count = "您收到一条低职位相符度的面试反馈，请立即修正职位信息，如果求职者评价不符可进行申诉（申诉有效期15天内）。";

					foreach($appraise_list_temp as $key => $value){
						//主账号发送短信
						foreach($account_list as $k => $v){
							if($value['company_id'] == $v['company_id'] && !base_lib_BaseUtils::nullOrEmpty($v['mobile_phone']) && preg_match('/^[1]\d{10}$/', $v['mobile_phone'])){
								var_dump($v);
								$base_lib_SMS->send($v['mobile_phone'],$msg_count);
								//添加发送日志
								$data = [
									'appraise_id'   => $value['appraise_id'],
									'company_id'    => $v['company_id'],
									'account_id'    => $v['account_id'],
									'mobile_phone'  => $v['mobile_phone'],
									'create_time'   => date("Y-m-d H:i:s"),
								];
								$service_person_appraise_msglog->insert($data);
							}
						}
					}


				}
			}
		}
        
        private $mongo;
    
    function pagedemodai($arguments) {echo date('Y-m-d H:i:s');
        $mongo = $this->getMongo();
        $mongo->go_init('huibo', 'jobsortstatistics');
        
        $service_common_jobsort = new base_service_common_jobsort();
        $service_company_job = new base_service_company_job_job();
        $third_jobsorts = $service_common_jobsort->getThirdJobsort();
        foreach ($third_jobsorts as $v) {
            $num = $service_company_job->companyJobStatisticsByJobsort($v['jobsort']);
            $statistics = $mongo->go_findOne(['jobsort' => $v['jobsort']]);
            if(empty($statistics)){
                $mongo->go_insert(['jobsort' => $v['jobsort'], 'company_num' => $num['company_num'], 'job_num' => $num['job_num']]);
                continue;
            }
            $mongo->go_update(['$set' => ['company_num' => $num['company_num'], 'job_num' => $num['job_num']]], ['jobsort' => $v['jobsort']]);
        }echo date('Y-m-d H:i:s');
    }
    
    private function getMongo() {
		$cache = null;
		
		if ($this->mongo == null) {
			$cache = new base_lib_Cache('mongo');
			$this->mongo = $cache;
		} else {
			$cache = $this->mongo;
		}
		
		return $cache;
	}
	}
?>