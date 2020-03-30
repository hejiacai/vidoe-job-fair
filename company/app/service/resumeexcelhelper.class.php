<?php
/**
 * 简历excel帮助类
 * @author Admin
 */
class company_service_resumeexcelhelper {
	private $objPHPExcel = null;
	private $company_id = 0;
    private $company_ids = array();
	private $cur_list_row = 0;
	private $listsheetdefaultstyle = null;
	private $resumepropertytitlestyle = null;
	private $resumedefaultrowheight = 22.5;
	private $headimgfilename = null;
	private $excelfilename = null;
	private $headImgs = array();
	private $_resumeCount = 0;
	
	
	function __construct() {
		$this->objPHPExcel = SPHPExcel::CreatePHPExcel();
	}
	
	/**
	 * 获取头像文件名
	 */
	public function getHeadFileName() {
		$headimgfilename = base_lib_Constant::SERVER_TEMP_FULL_FOLDER.'/'.$this->generateOrderno().'.jpg';
		array_push($this->headImgs, $headimgfilename);
		return 	$headimgfilename;
    }

	/**
	 * 获取excel文件名
	 */
	public function getExcelFileName() {
		if(is_null($this->excelfilename)) {
			$this->excelfilename = base_lib_Constant::SERVER_TEMP_FULL_FOLDER.'/'.$this->generateOrderno().'.xls';	
		}
		return $this->excelfilename;	 
    }
	
	/**
	 * 生成简历excel
	 */
	public function buildResumeExcel($resumeids,$companyid) {
        $service_resource   = base_service_company_resources_resources::getInstance($companyid);
        $this->company_ids  = $service_resource->all_accounts;
		$this->company_id   = $companyid;
		// 默认样式
		$styletitle = array(
			'font' => array(
		       'size'=>9,
			   'name'=>'微软雅黑'					
		    ),
		    'alignment' => array(
      			 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            	 'vertical' =>PHPExcel_Style_Alignment::VERTICAL_CENTER
		    )
	    );
	    $this->objPHPExcel->getDefaultStyle()->applyFromArray($styletitle);
	    $this->_createListSheet();
	    if(is_array($resumeids)) {
	    	$this->_resumeCount = count($resumeids);
	    	for($i = 0;$i<count($resumeids);$i++) {
	    		$this->cur_list_row +=1;
	    		$result = $this->_buildResume($resumeids[$i]);
	    		if(!$result) {
	    			$this->cur_list_row -=1;
	    		}
	    	}  
	    }else {
	    	$this->_resumeCount = 1;
	    	$this->cur_list_row +=1;
	    	$result = $this->_buildResume($resumeids);
	    }
	    $this->_complete();
	}

	/**
	 *
	 * 构建简历
	 * @param  $resumeid
	 */
	private function _buildResume($resumeid) {
		$is_show_name = false;
		$is_show_linkway = false;
		$not_member = false;
		$member_expires = false;
		$is_show_resumeinfo = false;
		$result = $this->checkResume($resumeid, $is_show_name, $is_show_linkway, $not_member, $member_expires,$is_show_resumeinfo);
		if(!$result) {
			return false;
		}
		// 简历
		$service_resume = new base_service_person_resume_resume();
		$service_resume->dbquery = true;
		$resume = $service_resume->getResume($resumeid,'resume_id,person_id,degree_id,station,is_salary_show,salary,appraise,expect_job_level,is_not_accept_lower_job_level,is_not_accept_lower_salary,job_type,is_accept_parttime,update_time,school');
		if(base_lib_BaseUtils::nullOrEmpty($resume)) {
			return false;
		}
		// 基本信息
		$service_person = new base_service_person_person();
		$service_person->dbquery = true;
		$person = $service_person->getPerson($resume['person_id'],'person_id,user_name,sex,email,cur_area_id,name_open,birthday2,mobile_phone,mobile_phone_is_validation,email_is_validation,telephone,qq,qq_open,tel_open,email_open,stature,marriage,avoirdupois,political_status,native_place_id,fertility_circumstance,start_work,login_time');
		if(base_lib_BaseUtils::nullOrEmpty($person)) {
			return false;
		}
		// 创建新工作薄
		$service_company=new base_service_company_company();
		if(!$is_show_resumeinfo) {
			$is_show_resumeinfo = $service_company->isShowResumeInfo($this->company_id);
		}
		$base_info = null;
		// 求职者姓名
		if($is_show_name&&$is_show_resumeinfo) {
			$base_info['user_name'] = $person['user_name'];
		}else {
			if($person['name_open']==1 && $isshowresumeinfo) {
				$base_info['user_name'] = $person['user_name'];
			}else {
				$base_info['user_name'] = mb_substr($person['user_name'],0,1,'utf-8').($person['sex']==1?'先生':'女士');
			}
		}
		// 头像
		/*
		if($person['photo_open']!='0' && $is_show_resumeinfo) {
			if(!base_lib_BaseUtils::nullOrEmpty($person['photo'])){
				$avatar = base_lib_Constant::UPLOAD_FILE_URL."{$person['photo']}";
				$base_info['photo'] = $avatar;
			}
		}
		if(!isset($base_info['photo'])) {
			$base_info['photo'] = base_lib_Constant::STYLE_URL.'/img/common/user80_100.jpg';
		}*/
        $service_edu = new base_service_person_resume_edu();
        $edu = $service_edu->getHighestEdu($resumeid,'major_desc');
		$base_info['sex'] = base_lib_BaseUtils::nullOrEmpty($person['sex'])?'':($person['sex']==1?'男':'女');// 性别
		$base_info['age'] = base_lib_TimeUtil::ceil_diff_year($person['birthday2']);
		$base_info['degree']=  $this->_getDegree($resume['degree_id']);
		$base_info['work_year'] = $this->_getWorkYear($person['start_work']);
		$base_info['livearea'] =  $this->_getArea($person['cur_area_id'])[0];
		$base_info['cur_live_area'] = $this->_getLiveArea($person['cur_area_id']);// 居住地
		$base_info['native_place'] = $this->_getLiveArea($person['native_place_id']);
		$base_info['marriage'] = $this->_getMarriage($person['marriage']);
		$base_info['avoirdupois'] = $person['avoirdupois'];
		$base_info['stature'] = $person['stature'];
		$base_info['political_status'] = $this->_getPoliticalstatus($person['political_status']);
        $base_info['school'] = $resume['school'];
        $base_info['major_desc'] = $edu['major_desc'];


		// 最近工作
		$work_service = new base_service_person_resume_work();
		$last_work =  $work_service->getLastResumeWork($resume['resume_id'],'station,company_name');
		// 备注
		$remark_service = new base_service_company_resume_resumeremark();
		$last_remark = $remark_service->getLastResumeRemark($this->company_id, $resume['resume_id'], 'remark');

		// 最近应聘职位
		$apply_service = new base_service_company_resume_apply();
		$last_apply = $apply_service->getLastApplyByeffect($this->company_ids,$resume['resume_id'],'station',0);
		$this->objPHPExcel->getSheet()->getStyle("B{$this->cur_list_row}:O{$this->cur_list_row}")->applyFromArray($this->listsheetdefaultstyle);
		$this->objPHPExcel->getSheet()->getRowDimension($this->cur_list_row)->setRowHeight($this->resumedefaultrowheight);	
		$this->objPHPExcel->getSheet()->setCellValue("B{$this->cur_list_row}", $resume['resume_id'])
		->setCellValue("C{$this->cur_list_row}", $base_info['user_name'])
		->setCellValue("D{$this->cur_list_row}", $base_info['age'])
		->setCellValue("E{$this->cur_list_row}", $base_info['sex'])
		->setCellValue("F{$this->cur_list_row}", $base_info['degree'])
		->setCellValue("G{$this->cur_list_row}", $base_info['major_desc'])
		->setCellValue("H{$this->cur_list_row}", $base_info['school'])
		->setCellValue("I{$this->cur_list_row}", $base_info['work_year'])
		->setCellValue("J{$this->cur_list_row}", $base_info['livearea'])
		->setCellValue("K{$this->cur_list_row}", $last_apply['station'])
		->setCellValue("L{$this->cur_list_row}", $last_work['station'])
		->setCellValue("M{$this->cur_list_row}", $last_work['company_name'])
		->setCellValue("N{$this->cur_list_row}", ($is_show_linkway?$person['mobile_phone']:''))
		->setCellValue("O{$this->cur_list_row}", ($is_show_linkway?$person['email']:''))
		->setCellValue("P{$this->cur_list_row}", $last_remark['remark']);
		
		//  链接样式
		$linkStyle = array(
			'font' => array(
		       'underline' => true,
		       'color'=>array(
			   	  'rgb'=>'3366cc' 			   	
			   )			
		    )		
		);
		$this->objPHPExcel->getSheet()->getStyle($this->_formatCellIndex($this->cur_list_row,'B','C'))->applyFromArray($linkStyle);																				 				
		$cur_resume_sheetname = $base_info['user_name'];
		// 设置链接跳转
		$this->objPHPExcel->getSheet()->getCell("B{$this->cur_list_row}")
		->getHyperlink()
		->setUrl("sheet://'{$cur_resume_sheetname}'!A1");
		$this->objPHPExcel->getSheet()->getCell("C{$this->cur_list_row}")
		->getHyperlink()
		->setUrl("sheet://'{$cur_resume_sheetname}'!A1");
		$resumeSheet = new PHPExcel_Worksheet($this->objPHPExcel, "$cur_resume_sheetname");
		$this->objPHPExcel->addSheet($resumeSheet);
	  
		$base_info['political_status'] = $this->_getPoliticalstatus($person['political_status']);
		$base_info['stature'] = $person['stature']; // 身高
		$base_info['avoirdupois'] = $person['avoirdupois'];
		$base_info['marriage'] = $this->_getMarriage($person['marriage']);
		// 联系方式
		if($is_show_resumeinfo) {
			$base_info['isshowresumeinfo'] = $is_show_resumeinfo;
			//手机号
			$base_info['mobile_phone'] = $person['mobile_phone'];
			$base_info['mobile_phone_valid'] = $person['mobile_phone_is_validation'];
			//邮箱
			$base_info['email'] = $person['email'];
			$base_info['email_is_validation'] = $person['email_is_validation'];
			//固话
			$base_info['telephone'] = $person['telephone'];
			//QQ
			$base_info['qq'] = $person['qq'];
			$base_info['show_linkway'] = $is_show_linkway;
			$base_info['not_member'] = $not_member;
			$base_info['member_expires'] = $member_expires;
		}
		$cur_rowindex = 1;
		$this->resumepropertytitlestyle = array(
			'font' => array(
		       'bold' => true,
		       'size'=>11,
			   'name'=>'微软雅黑'					
		    ),
            'fill' => array (
                'type' => PHPExcel_Style_Fill::FILL_SOLID ,
                'startcolor'=> array (
                    'rgb'=>'6DB364'
                    )
            )
        );
        $resumeSheet->getColumnDimension('A')->setWidth(1); 
        $resumeSheet->getColumnDimension('B')->setWidth(17);            
		$resumeSheet->getColumnDimension('C')->setWidth(72);
		//$resumeSheet->getDefaultRowDimension()->setRowHeight(22.5);
		$this->_buildBack($resumeSheet, $resumeid, $cur_rowindex);
		
		// 历史记录 
		$this->_buildResumeHistory($resumeSheet,$resumeid,$cur_rowindex);
        // 基本信息
        $this->_buildBasic($base_info, $resumeSheet,$cur_rowindex);           
		// 意向信息
		$exp = null;
		$exp['exp_job'] = $resume['station'];
		$exp['job_type'] = $resume['job_type'];
		$exp['is_accept_parttime'] = $resume['is_accept_parttime'];
		$exp['is_not_accept_lower_salary'] = $resume['is_not_accept_lower_salary'];
		$exp['is_not_accept_lower_job_level'] = $resume['is_not_accept_lower_job_level'];
		$exp['exp_job_level'] = $resume['expect_job_level'];
		$exp['is_salary_show'] = $resume['is_salary_show'];
		$exp['exp_salary'] = $resume['salary'];
		$exp['exp_jobsort'] = $this->_getJobsortExp($resume['resume_id']);
		$exp['exp_calling'] = $this->_getCallingExp($resume['resume_id']);
		$exp['exp_area'] = $this->_getAreaExp($resume['resume_id']);
        $this->_buildJobIntension($exp, $resumeSheet, $cur_rowindex);
                     
        // 工作经历
        $this->_buildWork($resumeSheet, $resumeid, $is_show_resumeinfo, $cur_rowindex);
        // 教育培训经历
        $this->_buildEdu($resumeSheet, $resumeid, $cur_rowindex);
        if($is_show_resumeinfo) {
			// 項目經驗                    
	        $this->_buildProject($resumeSheet, $resumeid, $cur_rowindex);           		
        }            
        // 语言能力
        $this->_buildLanguage($resumeSheet, $resumeid, $cur_rowindex);
        
        //技能
        $this->_buildSkill($resumeSheet, $resumeid, $cur_rowindex);

        //证书
        $this->_buildCertificate($resumeSheet, $resumeid, $cur_rowindex);
        
        // 实践经验
        $this->_buildPratices($resumeSheet, $resumeid, $cur_rowindex);
        
        // 其他信息
		$this->_buildOtherInfo($resumeSheet, $resumeid, $resume['appraise'], $cur_rowindex);
		return true;
	}
	
	/**
	 * 
	 * 实践经验
	 * @param $resumeSheet
	 * @param $resumeid
	 * @param $index
	 */
	private function _buildPratices($resumeSheet,$resumeid,&$index) {
		$service_practice = new base_service_person_resume_practice();
		$service_practice->dbquery = true; 
		$practices = $service_practice->getResumePracticeList($resumeid, 'start_time,end_time,practice_name,practice_detail');
		$practice_items = $practices->items;
		if(count($practice_items)>0) {
			$this->insertSegmentationRow($resumeSheet, $index);
			$index+=1;
			$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
			$resumeSheet->getStyle($this->_formatCellIndex($index,'A','C'))->applyFromArray($this->resumepropertytitlestyle);
			$resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}",'实践经验');
			for ($j = 0; $j < count($practice_items); $j++) {
				$this->insertSegmentationRow($resumeSheet, $index);
				$index +=1;
				$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
				$starttime = base_lib_BaseUtils::nullOrEmpty($practice_items[$j]['start_time'])?'开始时间未填写':date('Y.m',strtotime($practice_items[$j]['start_time']));
				$endtime = base_lib_BaseUtils::nullOrEmpty($practice_items[$j]['end_time'])?'至今':date('Y.m',strtotime($practice_items[$j]['end_time']));
				$resumeSheet->setCellValue("B{$index}","{$starttime}-{$endtime}")
							->setCellValue("C{$index}",$practice_items[$j]['practice_name']);
				if(!base_lib_BaseUtils::nullOrEmpty($practice_items[$j]['practice_detail'])) {
					$index +=1;
					$resumeSheet->setCellValue("C{$index}",$practice_items[$j]['practice_detail']);
					$resumeSheet->getStyle("C{$index}")->getAlignment()->setWrapText(true);					
				}						
			}
		}
	}
	
	
	private function _buildBack($resumeSheet,$resumeid,&$index) {
		$linkStyle = array(
			'font' => array(
		       'underline' => true			
		    ),
		    'alignment' => array(
      			 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
		    )		
		);
		$contentStyle = array(
		    'fill' => array (
                'type' => PHPExcel_Style_Fill::FILL_SOLID ,
                'startcolor'=> array (
                    'rgb'=>'2C5885'
                    )
            ),
            'font' => array(
		       'color'=>array(
			   	  'rgb'=>'FFFFFF' 			   	
            	)
            )		
		);
		$position = array(
		    'alignment' => array(
      			 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
		    )		
	    );
		
		$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
		$resumeSheet->getStyle("B{$index}")->applyFromArray($linkStyle);
		$resumeSheet->getStyle($this->_formatCellIndex($index,'A','C'))->applyFromArray($contentStyle);
		$resumeSheet->getStyle("C{$index}")->applyFromArray($position);																				 				
		// 设置链接跳转
		$resumeSheet->getCell("B{$index}")
		->getHyperlink()
		->setUrl("sheet://'简历列表'!A1");
		$resumeSheet->setCellValue("B{$index}",'返回首页')
					->setCellValue("C{$index}","简历编号：{$resumeid}");
	}
	
	
	/**
	 * 
	 * 历史记录
	 * @param $resumeSheet
	 * @param $resumeid
	 * @param $index
	 */
	private function _buildResumeHistory($resumeSheet, $resumeid, &$index) {
		$service_apply  = new base_service_company_resume_apply();
		$service_invite = new base_service_company_resume_jobinvite();
		$service_remark = new base_service_company_resume_resumeremark();
		$applys = $service_apply->getApplyByPerson($this->company_id,$resumeid,null,'station,create_time,re_status',0); 
		$invites= $service_invite->getInviteList($resumeid,$this->company_id,'station,create_time,audition_time');	
		$remarks = $service_remark->getResumeRemarkList($this->company_id, $resumeid, null, 'remark,update_time');
		// lambda 查询婉拒的求职申请
		$search_refuse = function ($date) {
		  return function ($apply) use ($date) { return $apply['re_status']==3&& date('Y-m-d',strtotime($apply['create_time']))==$date; };
		};
		// lambda 查询应聘职位信息
		$search_norefuse = function ($date) {
		  return function ($apply) use ($date) { return date('Y-m-d',strtotime($apply['create_time']))==$date; };
		};	
		// lambda 查询邀请信息
		$search_invite = function ($date) {
		    return function ($invite) use ($date) { return date('Y-m-d',strtotime($invite['create_time']))==$date; };
		};
		// lambda 查询备注信息
		$search_remark = function ($date) {
		   return function ($remark) use ($date) { return  date('Y-m-d',strtotime($remark['update_time']))==$date; };
		};
		$count = 20; // 最多显示消息数量
		$items = array();  
		$items_ago  = array();
		for ($i = 0; $i <= 90; $i+=1){			
			 $d  = date('Y-m-d',strtotime("-{$i} days"));			 
			 // 合并同一天的面试邀请,求职婉拒
			 $arr_refuseapplys = array_filter($applys->items, $search_refuse($d));
			 $arr_invite =  array_filter($invites->items, $search_invite($d));
			 $arr_refuseapplys = base_lib_BaseUtils::array_sort($arr_refuseapplys, 'create_time',true);
			 $arr_invite = base_lib_BaseUtils::array_sort($arr_invite, 'create_time',true);
			 $apply = reset($arr_refuseapplys);
			 $invite = reset($arr_invite);	
			 if($apply&&$invite) {
			 	if(strtotime($apply['create_time'])>strtotime($invite['create_time'])) {
			 		$time = $this->_getAuditionTime($invite['audition_time']);
			 		array_push($items,array('time'=>$d,'type'=>1,'datetime'=>strtotime($invite['create_time']),'content'=>"邀请面试：面试职位【{$invite['station']}】，面试时间【{$time}】"));
			 	}else {
			 		array_push($items,array('time'=>$d,'type'=>3,'datetime'=>strtotime($apply['create_time']),'content'=>'婉言谢绝'));
			 	}
			 }elseif($apply) {
			 		array_push($items,array('time'=>$d,'type'=>3,'datetime'=>strtotime($apply['create_time']),'content'=>'婉言谢绝'));			 		
			 }elseif($invite) {
			 		 $time = $this->_getAuditionTime($invite['audition_time']);
			 		 array_push($items,array('time'=>$d,'type'=>1,'datetime'=>strtotime($invite['create_time']),'content'=>"邀请面试：面试职位【{$invite['station']}】，面试时间【{$time}】"));
			 }
			 
			 // 合并同一天求职申请	 
			 $arr_apply = array_filter($applys->items, $search_norefuse($d));
			 if(count($arr_apply)>0) {
			 	$first_apply = reset($arr_apply);
			 	array_push($items,array('time'=>$d,'type'=>2,'datetime'=>strtotime($first_apply['create_time']),'content'=>'应聘职位：'. $this->_arrFieldJoin($arr_apply,'station','、')));	
			 }			 
			 
			 // 备注信息
			 $arr_remark = array_filter($remarks->items, $search_remark($d));
			 if(count($arr_remark)>0) {
			 	foreach ($arr_remark as $remark) {
			 		array_push($items,array('time'=>$d,'type'=>4,'datetime'=>strtotime($remark['update_time']),'content'=>'添加备注：'. $remark['remark']));	
			 	}
			 }
			 // 记录30天内的数据
			 if($i==29) {
			 	$items_ago = $items; 
			 	$items = array();
			 }	 
			 if((count($items)+count($items_ago))>=$count) {
			 	 break;
			 }
		}
		$historyrecord = base_lib_BaseUtils::array_sort($items_ago,'datetime',true);
		$historyrecordagos = base_lib_BaseUtils::array_sort($items,'datetime',true) ;
		if(count($historyrecord)>0||count($historyrecordagos)>0) {
			$style = array(
				'font'=>array(
					'bold'=>true,
					'size' =>11
				),
	            'fill' => array (
	                'type' => PHPExcel_Style_Fill::FILL_SOLID ,
	                'startcolor'=> array (
	                    'rgb'=>'eeeeee'
	                )
	            )
        	);
        	
 			$contentstyle = array(
				'font'=>array(
					'size' =>9
				),
	            'fill' => array (
	                'type' => PHPExcel_Style_Fill::FILL_SOLID ,
	                'startcolor'=> array (
	                    'rgb'=>'eeeeee'
	                )
	            )
        	);       	
			$this->insertSegmentationRow($resumeSheet, $index);
			$index += 1;
			$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
			$resumeSheet->getStyle($this->_formatCellIndex($index,'A','C'))->applyFromArray($style);
			$resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}",'历史记录');					

			for($i = 0;$i<count($historyrecord);$i++) {
				$index +=1;
				$resumeSheet->setCellValue("B{$index}",$historyrecord[$i]['time'])
        					->setCellValue("C{$index}",$historyrecord[$i]['content']);
        		$resumeSheet->getStyle($this->_formatCellIndex($index,'A','C'))->applyFromArray($contentstyle);	
        		$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);						
			}
			// 30天之前的记录
			for($i = 0;$i<count($historyrecordagos);$i++) {
				$index +=1;
				$resumeSheet->setCellValue("B{$index}",$historyrecordagos[$i]['time'])
        					->setCellValue("C{$index}",$historyrecordagos[$i]['content']);
        		$resumeSheet->getStyle($this->_formatCellIndex($index,'A','C'))->applyFromArray($contentstyle);	
        		$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);						
			}	
		}
	}
	
	// 获取面试时间
	private function _getAuditionTime($invite_audition_time) {
		 $audition_time = base_lib_BaseUtils::getStr($invite_audition_time,'datetime',null);
		 if(base_lib_BaseUtils::nullOrEmpty($audition_time)) { return $invite_audition_time; };
		 $auditiondate =date('Y年m月d日',strtotime($audition_time));
		 $noon = date('H',strtotime($audition_time))<=12?'上午':'下午';
		 $auditiontime =$noon.date('H:i',strtotime($audition_time));
		 $week = $this->week(base_lib_TimeUtil::date_of_week($audition_time));
	 	 $time = $auditiondate.'（'.$week.'）'.$auditiontime;
	 	 return $time;
	}

	// 将数组中的字段连接起来
	private function _arrFieldJoin($arr,$field,$separator=',') {
   	   $new_arr = array();
   	   foreach ($arr as $item){
			array_push($new_arr, $item[$field]);
	   }
	   return implode($separator,$new_arr);		
    }	
/**
	 * 
	 * 返回星期
	 * @param $number
	 */
	private  function week($number) {
		switch ($number){
			case 1:
				$week ='周一';
				break;
			case 2:
				$week ='周二';
				break;					
			case 3:
				$week ='周三';
				break;					
			case 4:
				$week ='周四';
				break;
			case 5:
				$week ='周五';
				break;
			case 6:
				$week ='周六';
				break;
			default:
				$week = '周日';
				break;	
		}
		return  $week;
	}
    
	/**
	 * 
	 * 其他信息
	 * @param  $resumeSheet
	 * @param  $resumeid
	 * @param  $index
	 */
	private function _buildOtherInfo($resumeSheet,$resumeid,$appraise,&$index) {		
		// 亮点标签
		$service_highlight = new base_service_person_resume_highlight();
		$service_highlight->dbquery = true; 
		$highlight = $service_highlight->getResumeHighlightList($resumeid, 'light_desc');
		$highlight_items = $highlight->items;

		//附加信息
		$service_append = new base_service_person_resume_append();
		$service_append->dbquery = true; 
		$appends = $service_append->getResumeAppendList($resumeid,'topic_desc,content');
		$append_item = $appends->items;
		if(count($highlight_items)>0||!base_lib_BaseUtils::nullOrEmpty($appraise)||count($append_item)>0) {
			$this->insertSegmentationRow($resumeSheet, $index);
			$index += 1;
			$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
			$resumeSheet->getStyle($this->_formatCellIndex($index,'A','C'))->applyFromArray($this->resumepropertytitlestyle);
			$resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}",'其他信息');				
			if(count($highlight_items)>0) {
				$this->insertSegmentationRow($resumeSheet, $index);
				$index+=1;
				$arr = array();
				foreach ($highlight_items as $key=>$value){
					array_push($arr, $value['light_desc']);
				}
				$resumeSheet->getStyle($this->_formatCellIndex($index,'B','B'))->applyFromArray(array('font'=>array('bold'=>true,'size'=>11)));
				$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
				$resumeSheet->setCellValue("B{$index}",'亮点标签')
        					->setCellValue("C{$index}",implode(' 、', $arr));	
			}
			if(!base_lib_BaseUtils::nullOrEmpty($appraise)) {
				$this->insertSegmentationRow($resumeSheet, $index);
				$index+=1;	
				$resumeSheet->getStyle($this->_formatCellIndex($index,'B','B'))->applyFromArray(array('font'=>array('bold'=>true,'size'=>11)));
				$resumeSheet->setCellValue("B{$index}",'自我评价')
        					->setCellValue("C{$index}",$appraise);	
        		$resumeSheet->getStyle("C{$index}")->getAlignment()->setWrapText(true);       					
			}
			if(count($append_item)>0) {
				foreach ($append_item as $key=>$value){
					$this->insertSegmentationRow($resumeSheet, $index);
					$index+=1;		
					$resumeSheet->getStyle($this->_formatCellIndex($index,'B','B'))->applyFromArray(array('font'=>array('bold'=>true,'size'=>11)));
					$resumeSheet->setCellValue("B{$index}","{$value['topic_desc']}")
        						->setCellValue("C{$index}","{$value['content']}");	
        			$resumeSheet->getStyle("C{$index}")->getAlignment()->setWrapText(true);		
				}
			}			
		}
   }
	
	/**
	 * 
	 * 证书
	 * @param $resumeSheet
	 * @param $resumeid
	 * @param $index
	 */
	public function _buildCertificate($resumeSheet,$resumeid,&$index) {
		$service_certificate = new base_service_person_resume_certificate();
		$service_certificate->dbquery = true; 
		$certificates = $service_certificate->getResumeCertificateList($resumeid,'certificate_name,certificate_no,gain_time,score');
		$certificates_items = $certificates->items;;
		if(count($certificates_items)>0){
			$this->insertSegmentationRow($resumeSheet, $index);
			$index += 1;
			$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
			$resumeSheet->getStyle($this->_formatCellIndex($index,'A','C'))->applyFromArray($this->resumepropertytitlestyle);
			$resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}",'证书');		
			$this->insertSegmentationRow($resumeSheet, $index);				
			for ($k = 0;$k < count($certificates_items); $k++){
				$index+=1;
				$arr = array();
				if(!base_lib_BaseUtils::nullOrEmpty($certificates_items[$k]['certificate_name'])) {
					array_push($arr, $certificates_items[$k]['certificate_name']);
				}
				if(!base_lib_BaseUtils::nullOrEmpty($certificates_items[$k]['gain_time'])) {
					$grin_time =date('Y年m月',strtotime($certificates_items[$k]['gain_time']));
					array_push($arr, "{$grin_time}获得");
				}
				$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);				
				$resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}",implode(' | ', $arr));	
			}
		}		
	}
	
	
	/**
	 * 
	 * 技能
	 * @param  $resumeSheet
	 * @param  $resumeid
	 * @param  $index
	 */
	private function _buildSkill($resumeSheet,$resumeid,&$index) {
		$service_skill = new base_service_person_resume_skill();
		$service_skill->dbquery = true; //load data from subordinate database
		$skill = $service_skill->getResumeSkillList($resumeid,'skill_name,skill_level');
		$skill_items =  $skill->items;
		if(count($skill_items)>0){
			$stat_arr = array();
			$skill_temp_item = $skill_items;
			for ($k2 = 0; $k2 < count($skill_temp_item); $k2++) {
				$skill_name = $skill_temp_item[$k2]['skill_name'];
				$skill_level = $skill_temp_item[$k2]['skill_level'];

				if(array_key_exists($skill_level,$stat_arr)){
					$stat_arr[$skill_level] = $stat_arr[$skill_level].' | '.$skill_name;
				}else{
					$stat_arr[$skill_level] = $skill_name;
				}
			}
			$this->insertSegmentationRow($resumeSheet, $index);
			$index += 1;
			$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
			$resumeSheet->getStyle($this->_formatCellIndex($index,'A','C'))->applyFromArray($this->resumepropertytitlestyle);
			$resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}",'技能专长');	
			$this->insertSegmentationRow($resumeSheet, $index);			
			foreach ($stat_arr as $key=>$value){
				$index+=1;	
				$level_name = $this->_getSkillLevel($key);
				$arr = array();
				if(!empty($level_name)) {
					array_push($arr, $level_name);
				}
				if(!base_lib_BaseUtils::nullOrEmpty($value)) {
					array_push($arr, $value);
				}
				$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
				$resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}",implode(' | ', $arr));				
			}
		}		
	}
	
	
	
	
	
	/**
	** 语言能力
	*/
	private function _buildLanguage($resumeSheet,$resumeid,&$index) {
		$service_language = new base_service_person_resume_language();
		$service_language->dbquery = true; 
		$service_languagecert = new base_service_person_resume_languagecert();
		$service_languagecert->dbquery = true; 
		$resume_languages = $service_language->getLanguageList($resumeid,'resume_id,language_id,language_type,skill_level')->items;
		if(count($resume_languages)>0) {
			$this->insertSegmentationRow($resumeSheet, $index);
			$index += 1;
			$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
			$resumeSheet->getStyle($this->_formatCellIndex($index,'A','C'))->applyFromArray($this->resumepropertytitlestyle);
			$resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}",'语言能力');				
			for ($i = 0; $i < count($resume_languages); $i++) {
				$this->insertSegmentationRow($resumeSheet, $index);
				$index+=1;
				$arr = array();
				$language = $this->_getLanguage($resume_languages[$i]['language_type']);
				if(!empty($language)) {
					array_push($arr, $language);
				}
				$skilllevel = $this->_getSkillLevel($resume_languages[$i]['skill_level']);
				if(!empty($skilllevel)) {
					array_push($arr, $skilllevel);
				}
				$certificates = $service_languagecert->getCertList($resume_languages[$i]['language_id'], 'cert_id,language_id,cert_name')->items;
				foreach($certificates as $c) {
					array_push($arr, $c['cert_name']);
				}
				$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
				$resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}",implode(' | ', $arr));	
			}
			
		}		
		
	}
	
	/*
	 * 获取语言类型
	 */
	private function _getLanguage($languageid) {
		if(base_lib_BaseUtils::nullOrEmpty($languageid)) return null;
		$language_service = new base_service_common_languagetype();
		return $language_service->getLanguageType($languageid);		
		
	}
	
	/**
	 * 
	 * 获取技能等级
	 * @param  $levelid
	 */
	private function _getSkillLevel($levelid) {
		if(base_lib_BaseUtils::nullOrEmpty($levelid)) return null;
		$skilllevel_service = new base_service_common_skilllevel();
		return $skilllevel_service->getName($levelid);			
	}
	
	/**
	 * 
	 * 項目經驗
	 * @param  $resumeSheet
	 * @param  $resumeid
	 * @param  $index
	 */
	private function _buildProject($resumeSheet,$resumeid,&$index) {
		$service_project = new base_service_person_resume_project();
		$service_project->dbquery = true; 
		$projects = $service_project->getResumeProjectList($resumeid,'start_time,end_time,project_name,project_detail,main_duty,duty');
		$project_items = $projects->items;
		if(count($project_items)>0){
			$this->insertSegmentationRow($resumeSheet, $index);
			$index += 1;
			$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
			$resumeSheet->getStyle($this->_formatCellIndex($index,'A','C'))->applyFromArray($this->resumepropertytitlestyle);
			$resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}",'项目经验');					
			for ($i = 0; $i < count($project_items); $i++) {
				$this->insertSegmentationRow($resumeSheet, $index);
				$index+=1;
				//开始结束时间
				$starttime = base_lib_BaseUtils::nullOrEmpty($project_items[$i]['start_time'])?'开始时间未填写':date('Y.m',strtotime($project_items[$i]['start_time']));
				$endtime = base_lib_BaseUtils::nullOrEmpty($project_items[$i]['end_time'])?'至今':date('Y.m',strtotime($project_items[$i]['end_time']));
				$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
				$resumeSheet->setCellValue("B{$index}","{$starttime}-{$endtime}");
				$arr = array();
				if(!base_lib_BaseUtils::nullOrEmpty($project_items[$i]['project_name'])) {
					array_push($arr, $project_items[$i]['project_name']);
				} 
				if(!base_lib_BaseUtils::nullOrEmpty($project_items[$i]['duty'])) {
					array_push($arr, '担任:'.$project_items[$i]['duty']);
				}
				$resumeSheet->getStyle($this->_formatCellIndex($index,'C','C'))->applyFromArray(array('font'=>array('bold'=>true,'size'=>11)));			
				$resumeSheet->setCellValue("C{$index}",implode(' | ', $arr));
				if(!base_lib_BaseUtils::nullOrEmpty($project_items[$i]['project_detail'])) {
					$index+=1;
					$resumeSheet->setCellValue("C{$index}",$project_items[$i]['project_detail']);
					$resumeSheet->getStyle("C{$index}")->getAlignment()->setWrapText(true);
				}
				if(!base_lib_BaseUtils::nullOrEmpty($project_items[$i]['main_duty'])) {
					$index+=1;
					$resumeSheet->setCellValue("C{$index}",$project_items[$i]['main_duty']);	
					$resumeSheet->getStyle("C{$index}")->getAlignment()->setWrapText(true);				
				}				
			}
		}		
   }
	
	
	/**
	 *
	 * 教育培训经历
	 * @param  $resumeSheet
	 * @param  $resumeid
	 * @param  $cur_rowindex
	 */
	private function _buildEdu($resumeSheet,$resumeid,&$index) {
		$service_edu = new base_service_person_resume_edu();
		$service_edu->dbquery = true; 
		$edus = $service_edu->getResumeEduList($resumeid,'start_time,end_time,school,major_desc,degree,edu_detail,duty');
		$edu_items = $edus->items;

		$service_train = new base_service_person_resume_train();
		$service_train->dbquery = true; 
		$trains = $service_train->getResumeTrainList($resumeid,'start_time,end_time,institution,course,certificate,train_detail');
		$train_items = $trains->items;
		if(count($edu_items)>0||count($train_items)>0) {
			$this->insertSegmentationRow($resumeSheet, $index);
			$index += 1;
			$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
			$resumeSheet->getStyle($this->_formatCellIndex($index,'A','C'))->applyFromArray($this->resumepropertytitlestyle);
			$resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}",'教育培訓经历');		
			for ($i = 0;$i<count($edu_items);$i++){
				$this->insertSegmentationRow($resumeSheet, $index);
				$index +=1;
				$starttime = base_lib_BaseUtils::nullOrEmpty($edu_items[$i]['start_time'])?'开始时间未填写':date('Y.m',strtotime($edu_items[$i]['start_time']));
				$endtime = base_lib_BaseUtils::nullOrEmpty($edu_items[$i]['end_time'])?'至今':date('Y.m',strtotime($edu_items[$i]['end_time']));	
				$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
				$resumeSheet->setCellValue("B{$index}","{$starttime}-{$endtime}");
			    $arr = array();
			    $degree = $this->_getDegree($edu_items[$i]['degree']);
			    if(!empty($degree)) {
			   		array_push($arr, $degree);
			    } 
				if(!base_lib_BaseUtils::nullOrEmpty($edu_items[$i]['school'])) {
					array_push($arr, $edu_items[$i]['school']);
				}
				if(!base_lib_BaseUtils::nullOrEmpty($edu_items[$i]['major_desc'])) {
					array_push($arr, $edu_items[$i]['major_desc']);
				}
				$resumeSheet->getStyle($this->_formatCellIndex($index,'C','C'))->applyFromArray(array('font'=>array('bold'=>true,'size'=>11)));	
				$resumeSheet->setCellValue("C{$index}",implode(' | ', $arr));
				if(!base_lib_BaseUtils::nullOrEmpty($edu_items[$i]['edu_detail'])) {
					$index +=1;
					$resumeSheet->setCellValue("C{$index}","学习课程：{$edu_items[$i]['edu_detail']}");
					$resumeSheet->getStyle("C{$index}")->getAlignment()->setWrapText(true);
				}
				if(!base_lib_BaseUtils::nullOrEmpty($edu_items[$i]['duty'])) {
					$index +=1;
					$resumeSheet->setCellValue("C{$index}","担任职务：{$edu_items[$i]['duty']}");
					$resumeSheet->getStyle("C{$index}")->getAlignment()->setWrapText(true);
				}
			}
			for ($j = 0;$j<count($train_items);$j++){
				$this->insertSegmentationRow($resumeSheet, $index);
				$index +=1;
				$starttime = base_lib_BaseUtils::nullOrEmpty($train_items[$j]['start_time'])?'开始时间未填写':date('Y.m',strtotime($train_items[$j]['start_time']));
				$endtime = base_lib_BaseUtils::nullOrEmpty($train_items[$j]['end_time'])?'至今':date('Y.m',strtotime($train_items[$j]['end_time']));	
				$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
				$resumeSheet->setCellValue("B{$index}","{$starttime}-{$endtime}");
				$train_arr = array();
				if(!base_lib_BaseUtils::nullOrEmpty($train_items[$j]['course'])) {
					array_push($train_arr, $train_items[$j]['course']);
				}
				
				if(!base_lib_BaseUtils::nullOrEmpty($train_items[$j]['institution'])) {
					array_push($train_arr, $train_items[$j]['institution']);
				}
				
				if(!base_lib_BaseUtils::nullOrEmpty($train_items[$j]['certificate'])) {
					array_push($train_arr, $train_items[$j]['certificate']);
				}
				$resumeSheet->getStyle($this->_formatCellIndex($index,'C','C'))->applyFromArray(array('font'=>array('bold'=>true,'size'=>11)));								
				$resumeSheet->setCellValue("C{$index}",implode(' | ', $train_arr));
				if(!base_lib_BaseUtils::nullOrEmpty($train_items[$j]['train_detail'])) {
					$index+=1;
					$resumeSheet->setCellValue("C{$index}",$train_items[$j]['train_detail']);
					$resumeSheet->getStyle("C{$index}")->getAlignment()->setWrapText(true);
				}	
			}
		}
		
	}
	 
	 
	/**
	 *
	 * 工作经验
	 * @param  $resumeid
	 * @param  $isshowresumeinfo
	 * @param  $index
	 */
	private function _buildWork($resumeSheet,$resumeid,$isshowresumeinfo,&$index) {
		$service_work = new base_service_person_resume_work();
		$service_work->dbquery = true;
		$works = $service_work->getResumeWorkList($resumeid,'start_time,end_time,company_name,calling_id,com_property,com_size,station,is_salary_show,work_content,salary_month,job_type,job_level,report_man,department,subordinate,leave_reason');
		$works_items = $works->items;
		if(!empty($works_items)&&count($works_items)>0){
			$this->insertSegmentationRow($resumeSheet, $index);
			$index+=1;
			$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
			$resumeSheet->getStyle($this->_formatCellIndex($index,'A','C'))->applyFromArray($this->resumepropertytitlestyle);
			$resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}",'工作经历');
			for ($j = 0; $j < count($works_items); $j++) {
				$this->insertSegmentationRow($resumeSheet, $index);
				$index +=1;
				$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
				$starttime = base_lib_BaseUtils::nullOrEmpty($works_items[$j]['start_time'])?'开始时间未填写':date('Y.m',strtotime($works_items[$j]['start_time']));
				$endtime = base_lib_BaseUtils::nullOrEmpty($works_items[$j]['end_time'])?'至今':date('Y.m',strtotime($works_items[$j]['end_time']));
				if(!$isshowresumeinfo){
					$works_items[$j]['company_name'] = "*****公司";
					$works_items[$j]['work_content'] = "您暂未通过企业认证，认证后即可查看完整简历";
				}
				$resumeSheet->setCellValue("B{$index}","{$starttime}-{$endtime}");
				$resumeSheet->getStyle($this->_formatCellIndex($index,'C','C'))->applyFromArray(array('font'=>array('bold'=>true,'size'=>11)));
				$resumeSheet->setCellValue("C{$index}","{$works_items[$j]['station']} | {$works_items[$j]['company_name']}");
				$index +=1;
				$arr = array();
				if($works_items[$j]['is_salary_show']!=1&&!base_lib_BaseUtils::nullOrEmpty($works_items[$j]['salary_month'])) {
					array_push($arr, $works_items[$j]['salary_month'].'元/月');
				}
				$job_level = $this->_getJobLevel($works_items[$j]['job_level']);
				if(!empty($job_level)) {
					array_push($arr, "岗位级别：{$job_level}");
				}
				$comproperty = $this->_getComProperty($works_items[$j]['com_property']);
				if(!empty($comproperty)) {
					array_push($arr, "公司性质：{$comproperty}");
				}
				$info = array();  //implode('-', $arr);
				$calling = $this->_getCalling($works_items[$j]['calling_id']);
				if(!empty($calling)) {
					array_push($info,$calling);
				}
				$comsize = $this->_getComsize($works_items[$j]['com_size']);
				if(!empty($comsize)) {
					array_push($info,$comsize);
				}
				$str = implode('-', $arr);
				if(count($arr)>0&&count($info)>0) {
					$str.='，';
				}
				$str.=implode('，', $info);
				$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
				$resumeSheet->setCellValue("C{$index}",$str);
				$index +=1;	
				$resumeSheet->setCellValue("C{$index}",$works_items[$j]['work_content']);
				$resumeSheet->getStyle("C{$index}")->getAlignment()->setWrapText(true);
				if(!base_lib_BaseUtils::nullOrEmpty($works_items[$j]['report_man'])||!base_lib_BaseUtils::nullOrEmpty($works_items[$j]['subordinate'])) {
					$index +=1;
					$otherinfo = array();
					if(!base_lib_BaseUtils::nullOrEmpty($works_items[$j]['report_man'])) {
						array_push($otherinfo, "汇报对象：{$works_items[$j]['report_man']}");
					}
					if(!base_lib_BaseUtils::nullOrEmpty($works_items[$j]['subordinate'])) {
						array_push($otherinfo, "下属人数：{$works_items[$j]['subordinate']}");
					}
					$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
					$resumeSheet->setCellValue("C{$index}",implode(' ', $otherinfo));
				}

				if(!base_lib_BaseUtils::nullOrEmpty($works_items[$j]['leave_reason'])) {
					$index +=1;			
					$resumeSheet->setCellValue("C{$index}","离职原因：{$works_items[$j]['leave_reason']}");
					$resumeSheet->getStyle("C{$index}")->getAlignment()->setWrapText(true);
				}
			}
		}
	}
	 
	/**
	 *
	 * 获取公司性质
	 * @param  $property_id
	 */
	private function _getComProperty($property_id) {
		if(base_lib_BaseUtils::nullOrEmpty($property_id)) return null;
		$comproperty_service = new base_service_common_comproperty();
		return $comproperty_service->getComproperty($property_id);
	}
	 
	/**
	 *
	 * 获取行业
	 * @param  $property_id
	 */
	private function _getCalling($calling_id) {
		if(base_lib_BaseUtils::nullOrEmpty($property_id)) return null;
		$calling_service = new base_service_common_calling();
		return $calling_service->getCallingName($calling_id);
	}

	/**
	 *
	 * 获取公司规模
	 * @param  $comsize_id
	 */
	private function _getComsize($comsize_id) {
		if(base_lib_BaseUtils::nullOrEmpty($comsize_id)) return null;
		$comsize_service = new base_service_common_comsize();
		return $comsize_service->getComsize($comsize_id);
	}
	 
	/**
	 * 求职意向
	 */
	private function _buildJobIntension($exp,$resumeSheet,&$index){
		$this->insertSegmentationRow($resumeSheet, $index);
		$index+=1;
		$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
		$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
		$resumeSheet->getStyle($this->_formatCellIndex($index),'A','C')->applyFromArray($this->resumepropertytitlestyle);
		$resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}",'求职意向');
		$this->insertSegmentationRow($resumeSheet, $index);
		$index+=1;
		$station = $exp['exp_job'];
		$jobtype = $exp['job_type'];
		if($jobtype==1&& $exp['is_accept_parttime']==1) {
			$station .='（可接受兼职）';
		}else if($jobtype ==2) {
			$station .='（兼职）';
		}else if($jobtype==3) {
			$station .='（实习）';
		}
		$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
		$resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}","希望从事：{$station}");
		$index+=1;
		$joblevel = $this->_getJobLevel($exp['exp_job_level']);
		if(!base_lib_BaseUtils::nullOrEmpty($joblevel)) {
			if($exp['is_not_accept_lower_job_level']==1&&$exp['exp_job_level']!='01'&&$exp['exp_job_level']!='02') {
				$joblevel.='（不低于此级别）';
			}
		}
		$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
		$resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}","岗位级别：{$joblevel}");
		// 期望薪资
		$index+=1;
		if($exp['is_salary_show']==1) {
			$salary = '面议';
		}else {
			if(base_lib_BaseUtils::nullOrEmpty($exp['exp_salary'])||$exp['exp_salary']==0) {
				$salary = '——';
			}else {
				$salary = $this->_getSalary($exp['exp_salary']);
				if($exp['is_not_accept_lower_salary']==1&&$exp['exp_salary']!='01') {
					$salary.='（不低于此薪资）';
				}
			}
		}
		$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
		$resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}","期望薪资：{$salary}");

		// 职位类别
		$index +=1;
		$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
		$resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}","期望类别：{$exp['exp_jobsort']}");
		// 行业
		$index+=1;
		$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
		$resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}","期望行业：{$exp['exp_calling']}");

		// 地区
		$index+=1;
		$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
		$resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}","工作地点：{$exp['exp_area']}");
	}
	 
	/**
	 *
	 * 获取薪资
	 * @param  $salary_id
	 */
	private function  _getSalary($salary_id) {
		if(base_lib_BaseUtils::nullOrEmpty($salary_id)) {
			return null;
		}
		$salary_service = new base_service_common_salary();
		return $salary_service->getSalary($salary_id);
	}
	 
	/**
	 *
	 * 获取岗位等级
	 * @param  $job_level
	 */
	private function _getJobLevel($job_level) {
		if(base_lib_BaseUtils::nullOrEmpty($job_level)) {
			return null;
		}
		$joblevel_service = new base_service_common_joblevel();
		return $joblevel_service->getName($job_level);
	}
	 
	/**
	 *
	 * 获取意向职位类别
	 * @param  $resumeid
	 */
	private function _getJobsortExp($resumeid) {
		$service_jobsortexp = new base_service_person_resume_jobsortexp();
		$service_jobsort = new base_service_common_jobsort();
		$jobsorts = $service_jobsortexp->getResumeJobsortexpList($resumeid, 'resume_id,jobsort');
		$jobsorts_items = $jobsorts->items;
		$str_expect_jobsorts = null;
		if(count($jobsorts_items)<=0){
			return $str_expect_jobsorts;
		}
		for ($g = 0; $g < sizeof($jobsorts_items); $g++) {
			if(sizeof($jobsorts_items)==1||$g == sizeof($jobsorts_items)-1){
				$str_expect_jobsorts .= $service_jobsort->getJobsortName($jobsorts_items[$g]['jobsort']);
			}else{
				$str_expect_jobsorts .= $service_jobsort->getJobsortName($jobsorts_items[$g]['jobsort']).';';
			}
		}
		return $str_expect_jobsorts;
	}
	 
	/**
	 *
	 * 获取意向行业
	 * @param  $resumeid
	 */
	private function  _getCallingExp($resumeid) {
		$service_callingexp = new base_service_person_resume_callingexp();
		$service_calling = new base_service_common_calling();
		$callings = $service_callingexp->getResumeCallingexpList($resumeid,'calling_id');
		$calling_items = $callings->items;
		$str_expect_callings = null;
		if(count($calling_items)<=0) {
			return $str_expect_callings;
		}
		for ($g = 0; $g < sizeof($calling_items); $g++) {
			if(sizeof($calling_items)==1||$g == sizeof($calling_items)-1){
				$str_expect_callings .= $service_calling->getCallingName($calling_items[$g]['calling_id']);
			}else{
				$str_expect_callings .= $service_calling->getCallingName($calling_items[$g]['calling_id']).';';
			}
		}
		return $str_expect_callings;
	}
	 
	/**
	 *
	 * 获取意向地区
	 * @param  $resumeid
	 */
	private function _getAreaExp($resumeid) {
		$service_areaexp = new base_service_person_areaexp();
		$service_areaexp->dbquery = true;
		$areas = $service_areaexp->getResumeAreaexpList($resumeid,'area_id');
		$area_items = $areas->items;
		$area_name = null;
		if(count($area_items)<=0) {
			return $area_name;
		}
		$area_ids = '';
		$area_ids_arr = array();
		$service_area = new base_service_common_area();
		for ($i = 0; $i < sizeof($area_items); $i++) {
			$area = $service_area->getArea($area_items[$i]['area_id'],false);
			if($i == sizeof($area_items)-1){
				$area_ids .= $area['area_id'];
			}else{
				$area_ids .= $area['area_id'].',';
			}
			array_push($area_ids_arr, $area['area_id']);
		}
		$area_name = $this->getSpecialAreaName($area_ids_arr,$area_ids);
		return $area_name;
	}
	/**
	 * 获取地区名称(特殊  例如：重庆-主城区,北京-五环以内)
	 * @param array $area_ids_arr  选择的地区数组
	 * @param string $area_ids  选择的地区字符串
	 */
	private function getSpecialAreaName($area_ids_arr,$area_ids){
		$service_area = new base_service_common_area();
		$area_name = '';
		if(count($area_ids_arr)<=0){
			return $area_name;
		}
		$xml = SXML::load('../config/person/Person.xml');
		if(!is_null($xml)){
			$cq_mian_city = $xml->CQMainCity;
			$cq_other_counties = $xml->CQOtherCounties;
			$bj_rings_within = $xml->BJRingsWithin;
			$bj_rings_without = $xml->BJRingsWithout;
			$sh_outer_within = $xml->SHOuterWithin;
			$sh_suburbs = $xml->SHSuburbs;
			$tj_mian_city = $xml->TJMainCity;
			$tj_other_counties = $xml->TJOtherCounties;
		}
		$temp_area_ids_arr = array();
		//重庆
		$cq_mian_city_arr = explode(',', $cq_mian_city);
		$cq_other_counties_arr = explode(',', $cq_other_counties);

		$intersect_cqmain = implode(',',array_intersect($cq_mian_city_arr,$area_ids_arr))== $cq_mian_city;
		$intersect_cqother = implode(',',array_intersect($cq_other_counties_arr,$area_ids_arr))== $cq_other_counties;

		if($intersect_cqmain&&$intersect_cqother){
			$area_name .= '重庆-主城区;重庆-周边区县;';
			$temp_area_ids_arr = array_diff($area_ids_arr,$cq_mian_city_arr);
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$cq_other_counties_arr);
		}elseif ($intersect_cqmain){
			$area_name .= '重庆-主城区;';
			$temp_area_ids_arr = array_diff($area_ids_arr,$cq_mian_city_arr);
		}elseif ($intersect_cqother){
			$area_name .= '重庆-周边区县;';
			$temp_area_ids_arr = array_diff($area_ids_arr,$cq_other_counties_arr);
		}
		$temp_area_ids_arr = count($temp_area_ids_arr)<=0&&empty($area_name)?$area_ids_arr:$temp_area_ids_arr;

		//北京
		$bj_mian_city_arr = explode(',', $bj_rings_within);
		$bj_other_counties_arr = explode(',', $bj_rings_without);

		$intersect_bjmain = implode(',',array_intersect($bj_mian_city_arr,$temp_area_ids_arr))== $bj_rings_within;
		$intersect_bjother = implode(',',array_intersect($bj_other_counties_arr,$temp_area_ids_arr))== $bj_rings_without;

		if($intersect_bjmain&&intersect_bjother){
			$area_name .= '北京-五环以内;北京-五环以外;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$bj_mian_city_arr);
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$bj_other_counties_arr);
		}elseif ($intersect_bjmain){
			$area_name .= '北京-五环以内;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$bj_mian_city_arr);
		}elseif ($intersect_bjother){
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$bj_other_counties_arr);
			$area_name .= '北京-五环以外;';
		}
		//上海
		$sh_mian_city_arr = explode(',', $sh_outer_within);
		$sh_other_counties_arr = explode(',', $sh_suburbs);

		$intersect_shmain = implode(',',array_intersect($sh_mian_city_arr,$temp_area_ids_arr))== $sh_outer_within;
		$intersect_shother = implode(',',array_intersect($sh_other_counties_arr,$temp_area_ids_arr))== $sh_suburbs;

		if($intersect_shmain&&$intersect_shother){
			$area_name .= '上海-外环以内;上海-郊区/县;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$sh_mian_city_arr);
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$sh_other_counties_arr);
		}elseif ($intersect_shmain){
			$area_name .= '上海-外环以内;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$sh_mian_city_arr);
		}elseif ($intersect_shother){
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$sh_other_counties_arr);
			$area_name .= '上海-郊区/县;';
		}
		//天津
		$tj_mian_city_arr = explode(',', $tj_mian_city);
		$tj_other_counties_arr = explode(',', $tj_other_counties);

		$intersect_tjmain = implode(',',array_intersect($tj_mian_city_arr,$temp_area_ids_arr))== $tj_mian_city;
		$intersect_tjother = implode(',',array_intersect($tj_other_counties_arr,$temp_area_ids_arr))== $tj_other_counties;

		if($intersect_tjmain&&$intersect_tjother){
			$area_name .= '天津-主城区;天津-周边区县;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$tj_mian_city_arr);
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$tj_other_counties_arr);
		}elseif ($intersect_tjmain){
			$area_name .= '天津-主城区;';
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$tj_mian_city_arr);
		}elseif ($intersect_tjother){
			$temp_area_ids_arr = array_diff($temp_area_ids_arr,$tj_other_counties_arr);
			$area_name .= '天津-周边区县;';
		}
		$temp_area_ids_arr = array_merge($temp_area_ids_arr);
		if(count($temp_area_ids_arr)==0){
			$area_name = mb_substr($area_name,0,mb_strlen($area_name,'utf-8')-1);
		}
		for ($m2 = 0; $m2 < count($temp_area_ids_arr); $m2++) {
			$area_temp_name = $service_area->getArea($temp_area_ids_arr[$m2]);
			if($m2 == sizeof($temp_area_ids_arr)-1){
				$area_name .= $area_temp_name;
			}else{
				$area_name .= $area_temp_name.';';
			}
		}
		return $area_name;
	}
	 
	// 格式化单元格索引
	private function _formatCellIndex($index,$startChar = null,$endChar =null) {
		if(empty($startChar)&&empty($endChar)) {
			return sprintf('A%s:C%s',$index,$index);
		}
		return sprintf("{$startChar}%s:{$endChar}%s",$index,$index);
	}
	 
	/**
	 *
	 * 基本信息
	 * @param  $basicinfo
	 * @param  $worksheet
	 * @param  $c
	 */
	private function _buildBasic($basicinfo,$resumeSheet,&$index) {
        $this->insertSegmentationRow($resumeSheet,$index);
        $index +=1;
		$resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
        $resumeSheet->getStyle($this->_formatCellIndex($index,'A','C'))->applyFromArray($this->resumepropertytitlestyle);
        $resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}",'基本信息');
        $this->insertSegmentationRow($resumeSheet, $index);
        $index += 1;
        $resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
        /* 	
        $filename = $this->getHeadFileName();
        $filename = $this->GrabImage($basicinfo['photo'],$filename);
        if($filename&&file_exists($filename)) {
       		$endindex = $index+4;
       		$resumeSheet->mergeCells("B{$index}:B{$endindex}");                    	
            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setName('headportrait');
            $objDrawing->setDescription('headportrait');
            $objDrawing->setPath($filename);
            $objDrawing->setWidth(80);
            $objDrawing->setHeight(140);
            $objDrawing->setCoordinates("B{$index}");
            $objDrawing->setWorksheet($resumeSheet);                    	
        }*/
        $resumeSheet->getStyle($this->_formatCellIndex($index,'B','C'))->applyFromArray(array('font'=>array('bold'=>true,'size'=>12)));
        $resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}","{$basicinfo['user_name']}（{$basicinfo['sex']}）");
       

        $index+=1;
        $arr = array();
        if(!base_lib_BaseUtils::nullOrEmpty($basicinfo['age'])) {
        	array_push($arr, $basicinfo['age'].'岁');
        }
        if(!base_lib_BaseUtils::nullOrEmpty($basicinfo['degree'])) {
        	array_push($arr, $basicinfo['degree']);
        }
        if(!base_lib_BaseUtils::nullOrEmpty($basicinfo['school'])) {
            array_push($arr, $basicinfo['school']);
        }
        if(!base_lib_BaseUtils::nullOrEmpty($basicinfo['major_desc'])) {
            array_push($arr, $basicinfo['major_desc']);
        }
        if(!base_lib_BaseUtils::nullOrEmpty($basicinfo['work_year'])) {
        	array_push($arr, $basicinfo['work_year']);
        }
        if(!base_lib_BaseUtils::nullOrEmpty($basicinfo['cur_live_area'])) {
        	array_push($arr,'现居:'.$basicinfo['cur_live_area']);
        }
        $resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
        $resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}",implode(' | ', $arr));
        $arr = null;
        $arr = array();
        $index += 1;
        if(!base_lib_BaseUtils::nullOrEmpty($basicinfo['marriage'])) {
        	array_push($arr, $basicinfo['marriage']);
        }else {
        	array_push($arr, '婚姻状态：未填写');
        }
        if(!base_lib_BaseUtils::nullOrEmpty($basicinfo['native_place'])) {
        	array_push($arr,"户籍:{$basicinfo['native_place']}");
        }else {
        	array_push($arr,'户籍:未填写');
        }
        if(!base_lib_BaseUtils::nullOrEmpty($basicinfo['stature'])||!base_lib_BaseUtils::nullOrEmpty($basicinfo['avoirdupois'])) {
        	$str = null;
        	if(!base_lib_BaseUtils::nullOrEmpty($basicinfo['stature'])) {
        		$str .="{$basicinfo['stature']}cm";
        	}else {
        		$str .="身高:未填写";
        	}
            if(!base_lib_BaseUtils::nullOrEmpty($basicinfo['avoirdupois'])) {
            	if(!empty($str)) {
            		$str.='/';
            	}
        		$str .="{$basicinfo['avoirdupois']}kg";
        	}else {
        		$str .=" 体重:未填写";
        	}                   	
        	array_push($arr,$str);
        }else {
        	array_push($arr, '身高/体重:未填写');
        }
        if(!base_lib_BaseUtils::nullOrEmpty($basicinfo['political_status'])) {
        	array_push($arr,$basicinfo['political_status']);
        }else{
        	array_push($arr,'政治面貌：未填写');
        }
        $resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
        $resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}",implode(' | ', $arr));

        $arr = null;
        $arr = array();
        $index+=1;
        if(isset($basicinfo['isshowresumeinfo'])) {
        	if($basicinfo['show_linkway']==true) {
        		array_push($arr,'手机：'.base_lib_BaseUtils::getStr($basicinfo['mobile_phone'],'string','未填写'));
        		array_push($arr,'Email：'.base_lib_BaseUtils::getStr($basicinfo['email'],'string','未填写'));
        		array_push($arr,'QQ：'.base_lib_BaseUtils::getStr($basicinfo['qq'],'string','未填写'));
        	}else if($basicinfo['not_member']==true) {
        		array_push($arr,'您未开通服务');
        	}else if($basicinfo['member_expires']==true) {
        		array_push($arr,'您的会员已过期');
        	}
        }
        $resumeSheet->getRowDimension($index)->setRowHeight($this->resumedefaultrowheight);
        $resumeSheet->mergeCells($this->_formatCellIndex($index,'B','C'))->setCellValue("B{$index}",implode(' | ', $arr));
	}

	//  插入分割行
	private function insertSegmentationRow($resumeSheet,&$index) {
		$index += 1;
		$resumeSheet->getRowDimension("{$index}")->setRowHeight(7.5);
	}	 
	// 生成唯一编号
	private function generateOrderno() {
		$timestr = date('YmdHis');
		return base_lib_BaseUtils::random(5,1,$timestr);
	}
	// 获取图片文件
	function GrabImage($url,$filename="") {
		if($url=="") return false;
		ob_start();
		if(readfile($url)===false) {
			return false;
		}
		$img = ob_get_contents();
		ob_end_clean();
		$size = strlen($img);
		$fp2=@fopen($filename, "a");
		fwrite($fp2,$img);
		fclose($fp2);
		return $filename;
	}
	 
	/**
	 *
	 * 获取婚姻状况
	 * @param  $marriage
	 */
	private function _getMarriage($marriage) {
		if(base_lib_BaseUtils::nullOrEmpty($marriage)) {
			return null;
		}
		$marriage_service = new base_service_common_marriage();
		return $marriage_service->getName($marriage);
	}
	 
	/**
	 *
	 * 获取学历
	 * @param  $degree_id
	 */
	private function _getDegree($degree_id) {
		if(base_lib_BaseUtils::nullOrEmpty($degree_id)) {
			return null;
		}
		$service_degree = new base_service_common_degree();
		return $service_degree->getDegree($degree_id);
	}
	 
	/**
	 *
	 * 工作经验
	 * @param  $start_work
	 */
	private function _getWorkYear($start_work) {
		if(base_lib_BaseUtils::nullOrEmpty($start_work)) {
			return null;
		}
		$basic_start_work_year = base_lib_TimeUtil::date_diff_month($start_work);
		$workY = floor($basic_start_work_year/12);
		$workM = intval($basic_start_work_year%12);
		if($workY<=0 && $workM<=6&&$workM>=-6){
			$basic_start_work_year = '应届毕业生';
		}else if($workY == 0 && $workM>6){
			$basic_start_work_year = $workM.'个月';
		}else if($basic_start_work_year<-6){
			$basic_start_work_year = '目前在读';
		}else{
			$basic_start_work_year = $workY.'年工作经验';
		}
		return  $basic_start_work_year;
	}
	 
	/**
	 *
	 * 获取居住地
	 * @param  $area_id
	 */
	private function _getLiveArea($area_id) {
		if(base_lib_BaseUtils::nullOrEmpty($area_id)) {
			return null;
		}
		if($area_id!=''){
			$service_area = new base_service_common_area();
			$cur_area_arr = $service_area->getTopAreaByAreaID($area_id);
			$cur_area_count = count($cur_area_arr);
			for ($j = $cur_area_count-1; $j >= 0; $j--) {
				if($j==0){
					$cur_area_name .= $cur_area_arr[$j]['area_name'];
				}else{
					$cur_area_name .= $cur_area_arr[$j]['area_name'].'-';
				}
			}
		}
		return $cur_area_name;
	}
	
	 private function _getArea($area_id,$default='',$isShowAll=false) {
     	if(base_lib_BaseUtils::nullOrEmpty($area_id)) {
     		return $default;
     	}
     	$service_area = new base_service_common_area();
     	$areas = $service_area->getTopAreaByAreaID($area_id);
     	$areas = array_reverse($areas);
     	$count  = count($areas);
     	if($count<=0) {
     		return $default;
     	}
     	$names  = array();
     	if($isShowAll) {
     	  for($i= 0;$i<$count;$i++) {
     		$area = $areas[$i];
     		array_push($names, $area['area_name']);	
     	  }     		
     	}else {
     	  $isChongqing = false;
     	  for($i= 0;$i<$count;$i++) {
     		$area = $areas[$i];
     	    if($i==0) {
     	    	if($count==1) {
     	    		array_push($names, $area['area_name']);
     	    		continue;
     	    	}
     	    	if($area['area_id']=='0300') {
     	    		$isChongqing = true;
     	    		continue;
     	    	}
     	    }
			if(!$isChongqing&&$i>=2) {
				break;
			}
		 	array_push($names, $area['area_name']);	
     		}    			
       }
       return $names;
     } 	
	
	
	 
	/**
	 * 政治面试
	 * @param  $political_status
	 */
	private function _getPoliticalstatus($political_status) {
		if(base_lib_BaseUtils::nullOrEmpty($political_status)) {
			return null;
		}
		$political_servicee = new base_service_common_politicalstatus();
		return $political_servicee->getName($political_status);
	}

	/**
	 *
	 * 设置文档属性
	 */
	private function _setExcelProperty() {
		$this->objPHPExcel->getProperties()->setCreator("HuiBo")
		->setLastModifiedBy("HuiBo")
		->setTitle("")
		->setSubject("Test")
		->setDescription("Test")
		->setKeywords("Test")
		->setCategory("Test result file");
	}
	private function _createListSheet() {
		// 工作表样式
		// 设置列宽
		$cells = array('A'=>2,'B'=>11,'C'=>8.5,'D'=>6,'E'=>8,'F'=>8,'G'=>10.5,'H'=>18,'I'=>16.5,'J'=>19,'K'=>32.5,'L'=>30.5,'M'=>30,'N'=>12,'O'=>12);
		// 隐藏网格线
		$this->objPHPExcel->getActiveSheet()->setShowGridlines(false);

		$this->listsheetdefaultstyle = array(
		    'font' => array(
		       'size'=>9,
			   'name'=>'微软雅黑'					
		    ),
            'alignment' => array(
      			 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            	 'vertical' =>PHPExcel_Style_Alignment::VERTICAL_CENTER
		    ),
   			'borders' => array(  
		          'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,
		        			     	 'color' => array ('rgb' => '47813F'))  
		    )
		);
		     
	    // 字体、填充颜色、定位
	    $styletitle = array(
			'font' => array(
		       'bold' => true,
		       'size'=>10,
			   'name'=>'微软雅黑'					
		    ),
            'fill' => array (
                'type' => PHPExcel_Style_Fill::FILL_SOLID ,
                'startcolor'=> array (
                    'rgb'=>'6DB364'
                )
            )
        );
		$this->cur_list_row+=1;
			$this->objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(22.5);
        // 设置列名
        $this->objPHPExcel->getActiveSheet()->getStyle($this->_formatCellIndex($this->cur_list_row,'B','O'))->applyFromArray($styletitle);
        $this->objPHPExcel->getActiveSheet()->getStyle($this->_formatCellIndex($this->cur_list_row,'B','O'))->applyFromArray($this->listsheetdefaultstyle);
        $this->objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(26.5);
        $this->objPHPExcel->setActiveSheetIndex(0)->setTitle('简历列表')
        ->setCellValue("B{$this->cur_list_row}", "简历编号")
        ->setCellValue("C{$this->cur_list_row}", "姓名")
        ->setCellValue("D{$this->cur_list_row}", "年龄")
        ->setCellValue("E{$this->cur_list_row}", "性别")
        ->setCellValue("F{$this->cur_list_row}", "学历")
        ->setCellValue("G{$this->cur_list_row}", "专业")
        ->setCellValue("H{$this->cur_list_row}", "毕业学校")
        ->setCellValue("I{$this->cur_list_row}", "工作年限")
        ->setCellValue("J{$this->cur_list_row}", "现居地")
        ->setCellValue("K{$this->cur_list_row}", "应聘职位")
        ->setCellValue("L{$this->cur_list_row}", "最近工作")
        ->setCellValue("M{$this->cur_list_row}", "所在公司")
        ->setCellValue("N{$this->cur_list_row}", "联系方式")
        ->setCellValue("O{$this->cur_list_row}", "邮箱")
        ->setCellValue("P{$this->cur_list_row}", "备注");
         
        foreach ($cells as $key=>$value){
        	$this->objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth($value);
        }
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->_formatCellIndex($this->cur_list_row,'B','M'));

        $this->cur_list_row +=1;
        $this->objPHPExcel->getActiveSheet()->getStyle($this->_formatCellIndex($this->cur_list_row,'B','M'))->applyFromArray(array(
			'font' => array(
        		'size'=>9,
       			'name'=>'微软雅黑',	
        		'color'=>array(
        		 	'rgb' =>'CC3300'
        		 )				
		    ),
		    'alignment' => array(
      			 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            	 'vertical' =>PHPExcel_Style_Alignment::VERTICAL_CENTER
		    ),
            'fill' => array (
                'type' => PHPExcel_Style_Fill::FILL_SOLID ,
                'startcolor'=> array (
                    'rgb'=>'FFE699'
                )
            )
        ));
      $this->objPHPExcel->getActiveSheet()->mergeCells($this->_formatCellIndex(2,'B','O'))->setCellValue("B{$this->cur_list_row}",'提示：点击姓名或编号，可直达该简历详细内容页');
	}


	/**
	 *
	 * 完成保存excel
	 */
	private function _complete(){
		$this->objPHPExcel->setActiveSheetIndex(0);
		$objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
		$objWriter->save($this->getExcelFileName()); //str_replace('class.php', 'xls', __FILE__)
   }
	
   /**
    * 
    * 清理文件
    */
   public  function cleanFile(){
   		// 清理excel
   		$excelFile = $this->getExcelFileName();
   		if(file_exists($excelFile)) {
   			unlink($excelFile);
   		}
   		// 清理头像
   		/*
   		for ($i=0;$i<count($this->headImgs);$i++){
   			$imgfile = $this->headImgs[$i];
   		   	if(file_exists($imgfile)) {
   				unlink($imgfile);
   			}
   		}*/	
   } 
	
	/**
	 *
	 * 验证简历查看权限
	 * @param  $resume_id  简历编号
	 * @param  $company_id 公司编号
	 * @param  $is_show_name  是否显示求职者姓名
	 * @param  $is_show_linkway 是否显示联系方式
	 * @param  $not_member     是否是会员
	 * @param  $member_expires  是否过期
	 * @param  $is_show_resumeinfo  是否显示简历详情
	 * @return boolean  ture 验证成功，false:验证失败
	 */
	private function  checkResume($resume_id,&$is_show_name,&$is_show_linkway,&$not_member,&$member_expires,&$is_show_resumeinfo) {
		$service_company = new base_service_company_company();
		$service_person = new base_service_person_person();
		$service_resume = new base_service_person_resume_resume();
		$result = true;
		$resume = $service_resume->getResume($resume_id, 'resume_id,person_id');
		if(empty($resume)) {
			return false;
		}
		$person = $service_person->getPerson($resume['person_id'],'name_open,user_name,user_name_en,open_mode');
		if(empty($person)) {
			return false;
		}
		if($this->_getFilter($resume['person_id'])) {
			return false;
		}
		if($person['name_open']==1) {
			$is_show_name = true;
		}
		$service_apply  = new base_service_company_resume_apply();
		$service_download = new base_service_company_resume_download();
		// 验证求职者是否投递过企业的职位
		$company_resources = base_service_company_resources_resources::getInstance($this->company_id);
		$relate_resume_id =empty($resume['relate_resume_id'])?0:$resume['relate_resume_id'];
		if($service_apply->isApply($company_resources->all_accounts, $resume['resume_id'])) {
			$is_show_linkway  = true;
			$is_show_name = true;
			$is_show_resumeinfo =true;
		}else if($service_apply->isApply($company_resources->all_accounts,$relate_resume_id)) {
			$is_show_linkway  = true;
			$is_show_name = true;
			$is_show_resumeinfo = true;
		}else {
			$enum_openmode = new base_service_common_openmode();
			if($person['open_mode']==$enum_openmode->notopen) {
				return false;
			}
			// 验证企业是否下载过该简历
			if($service_download->isResumeDownloaded($company_resources->all_accounts, $resume['resume_id'])) {
				$is_show_linkway = true;
				$is_show_resumeinfo = true;
			}
		}
		$company = $service_company->getCompany($this->company_id,'1','company_id,end_time,com_level');
		$memberday = 8;
		if(!empty($company['end_time'])) {
			$memberday = base_lib_TimeUtil::time_diff_day($company['end_time'],date('Y-m-d H:i:s'));
		}
		// 如果显示联系方式，需验证 企业的会员状态
		if($is_show_linkway) {
			if($memberday>7||$company['com_level']<=0) {
				$is_show_linkway = false;
				if($company['com_level']<=0){
					$not_member = true;
				}else {
					$member_expires =true;
				}
			}
		}else {
			if($company['com_level']<=0){
				$not_member = true;
			}else if($memberday > 7) {
				$member_expires =true;
			}
		}
		return $result;
	}

	/**
	 * 单位是否在求职者屏蔽的关键字当中
	 */
	public function _getFilter($person_id){
		$service_blench = new base_service_person_blench();
		$is_filter = false;
		$blenchs = $service_blench->getBlenchKeyList($person_id,'person_id,com_keyword');
		$blenchs_items = $blenchs->items;
		if(!base_lib_BaseUtils::nullOrEmpty($blenchs_items)){
			for($i =0 ; $i< count($blenchs_items);$i++){
				if($this->_getKeySetResult($blenchs_items[$i]['com_keyword'])){
					$is_filter = true;
					break;
				}
			}
		}
		return $is_filter;
	}

//	//  获取关键字结果
//	public function _getKeySetResult($com_keyword){
//		$servcie_sphinx = new base_service_sphinx_sphinx();
//		$postvar['keyword'] = $com_keyword;
//        var_dump($postvar);exit;
//		$result= $servcie_sphinx->getFilterCompany($postvar);
//		if(isset($result['company_ids']) && count($result['company_ids'])){
//			return in_array($this->company_id,$result['company_ids'])==true;
//		}else{
//			return false;
//		}
//	}
    
    
	//  获取关键字结果
	public function _getKeySetResult($com_keyword){
			//$servcie_sphinx = new base_service_sphinx_sphinx();
		$service_solr = new base_service_solr_solr();
		$postvar['keyword'] = $com_keyword;
		$result= $service_solr->companySearch($postvar);

		if(isset($result['companys']) && count($result['companys'])){
			if(base_lib_BaseUtils::arrayFind($result['companys'],'company_id',$this->company_id) != null){
				return true;
			}
			else{
				return false;
			}
			//return in_array($this->_userid,$result['companys'])==true;
		}else{
			return false;
		}
	}

}
?>