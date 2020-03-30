<?php
/**
 * 兼职用工管理
 * @ClassName controller_parttallage 
 */
class controller_parttallage extends components_cbasepage {
    
	function __construct() {
		parent::__construct();
//$this->_userid=114913710;
	}

	function pageIndex($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $cur_page      = base_lib_BaseUtils::getStr($path_data['page'], 'int', 1);
        $page_size  = base_lib_Constant::PAGE_SIZE;

        $position_name      = base_lib_BaseUtils::getStr($path_data['position_name'], 'string', '');
        $user_name          = base_lib_BaseUtils::getStr($path_data['user_name'], 'string', '');
        $work_start_time    = base_lib_BaseUtils::getStr($path_data['work_start_time'], 'string', '');
        $work_end_time      = base_lib_BaseUtils::getStr($path_data['work_end_time'], 'string', '');
        $work_end_time      = base_lib_BaseUtils::getStr($path_data['work_end_time'], 'string', '');
        $work_end_time      = base_lib_BaseUtils::getStr($path_data['work_end_time'], 'string', '');
        $task_status        = base_lib_BaseUtils::getStr($path_data['task_status'], 'int', 0);

        $service_importperson           = new base_service_tallage_tallageimportperson();
        $service_importtask             = new base_service_tallage_importtask();
        $service_importsalary           = new base_service_tallage_importsalary();
        $service_worktype               = new base_service_common_tallage_worktype();
        $service_tallage_taskallot      = new base_service_tallage_taskallot();
        $service_person                 = new base_service_person_person();
        $service_common_area            = new base_service_common_area();
        $service_common_part_salaryunit = new base_service_common_part_salaryunit();
        $service_common_part_partTime   = new base_service_common_part_partTime();
        $service_part_job_salarytype    = new base_service_part_job_salarytype();
        $service_partjobworktime        = new base_service_part_job_partjobworktime();

//        $select = $service_importperson->getSelectData('group_concat(position_name) position',$this->_userid);
        $positions = [];
        
        $feild = 'b.import_task_id,a.id,a.company_id,a.company_name,a.person_id,a.user_name,a.open_bank,a.card_no,b.salary,b.work_time,b.task_salary_total,b.is_upload_salary,b.account_statement_imgs,b.task_work_day,b.settle_type,b.work_start_time'
                . ',a.position_name';
        $data = $service_importperson->getList($cur_page,$page_size,null,null,$position_name,null,1,$user_name,null,-1,$work_start_time,$work_end_time,$feild,null,$task_status,$this->_userid);
//        $data = $service_importperson->getList($cur_page,$page_size,null,null,$position_name,null,1,$user_name,null,-1,$work_start_time,$work_end_time,$feild,null,$task_status,114913851);
        $list = $data->items;

        $import_task_ids = array_unique(array_filter(base_lib_BaseUtils::getProperty($list,'import_task_id')));
        $bind_user_ids = array_unique(array_filter(base_lib_BaseUtils::getProperty($list,'person_id')));
        
        $salary_list = $service_importsalary->getDataByTaskId(array_unique($import_task_ids),'import_task_id,base_salary,allowance,reward,reduce_salary,actually_salary,remark');
        $salary_list = base_lib_BaseUtils::array_key_assoc($salary_list,'import_task_id');
        $person_list = $service_person->getPersons($bind_user_ids, 'user_name,cur_area_id,birthday2,stature,person_id,sex,photo')->items;
        $person_list = base_lib_BaseUtils::array_key_assoc($person_list,'person_id');

        $task_list  = $service_tallage_taskallot->getTaskDetails($import_task_ids);
        $job_ids    =  array_unique(array_filter(base_lib_BaseUtils::getProperty($task_list,'job_id')));
        $work_time_list = $service_partjobworktime->getPartJobWorkTimeByJobIds($job_ids);
        $work_time_list = base_lib_BaseUtils::array_key_assoc($work_time_list,'job_id');
        
        $salary_type_list = $service_part_job_salarytype->getAll();
        $part_time_list = $service_common_part_partTime->getAll();
        $salary_unit_all = $service_common_part_salaryunit->getAll();
//var_dump($list);
        $xml = SXML::load("../config/oa/config.xml");
        foreach ($list as $k => $v) { 
//            $v['work_time'] = $v['settle_type'] == 2 ? $v['work_start_time'] : $v['work_time'];
            $list[$k]['photo_url'] =  !empty($person_list[$v['person_id']]['photo']) ? base_lib_Constant::YUN_ASSETS_URL . $person_list[$v['person_id']]['photo'] : 
                ($person_list[$v['person_id']]['sex'] == 1 ? base_lib_Constant::STYLE_URL."/img/company/video/defaultMan.png" : base_lib_Constant::STYLE_URL."/img/company/video/defaultWoman.png");
            $list[$k]['work_time_str'] = date('Y-m', strtotime($v['work_time']));
            $list[$k]['age'] = base_lib_TimeUtil::ceil_diff_year($person_list[$v['person_id']]['birthday2']);
            $list[$k]['stature'] = $person_list[$v['person_id']]['stature'];
            $list[$k]['sex'] = $person_list[$v['person_id']]['sex'];
            $list[$k]['cur_area'] = $this->getArea($person_list[$v['person_id']]['cur_area_id'], '', true);
            $list[$k]['is_end_task'] = true;
            $task_items = [];
            foreach ($task_list as $v1) {
                if($v1['import_task_id'] == $v['import_task_id']){
                    if($v1['task_status'] != 3)
                        $list[$k]['is_end_task'] = false;
                    $strat_time = $work_time_list[$v1['job_id']]['strat_time'];
                    $end_time = $work_time_list[$v1['job_id']]['end_time'];
                    $work_time_arr = explode(',', $v1['work_time']);
                    array_push($task_items, [
                        'work_time' => implode('~',  array_filter([array_shift($work_time_arr), array_pop($work_time_arr)])),
                        'work_time_h' => $part_time_list[$strat_time]."-".$part_time_list[$end_time],
                        'address' => $v1['address'] ? $v1['address'] : '不限',
                        'station' => $v1['station'],
                        'salary_unit_name' => $salary_unit_all[$v1['salary_unit']][1],
                        'salary_type_name' => $salary_type_list[$v1['salary_type']],
                        'salary' => $v1['salary']
                    ]);
                }
            }
            $list[$k]['task_items'] = $task_items;
            $list[$k]['has_salary'] = $list[$k]['is_end_task'] && isset($salary_list[$v['import_task_id']]) ? true : false;
            $list[$k]['base_salary']     = 0;//基本薪资
            $list[$k]['allowance']       = 0;//津贴
            $list[$k]['reduce_salary']   = 0;//扣款
            $list[$k]['actually_salary'] = 0;//实发薪资
            $list[$k]['task_work_day']   = 0;
            if($list[$k]['has_salary']){
                $list[$k]['base_salary']     = $salary_list[$v['import_task_id']]['base_salary'];//基本薪资
                $list[$k]['allowance']       = $salary_list[$v['import_task_id']]['allowance'];//津贴
                $list[$k]['reduce_salary']   = $salary_list[$v['import_task_id']]['reduce_salary'];//扣款
                $list[$k]['actually_salary'] = $salary_list[$v['import_task_id']]['actually_salary'];//实发薪资
                $list[$k]['task_work_day']   = $v['task_work_day'];
            }
            
            $defaults_files = explode(',',$v['account_statement_imgs']);
            $list[$k]['account_statement_imgs_arr'] = [];
            if(empty($defaults_files)){
                foreach ($defaults_files as $k1=>$v1) 
                    $list[$k]['account_statement_imgs_arr'][] = base_lib_Constant::UPLOAD_FILE_URL . '/' . $xml->VirtualName . '/' . $xml->tallageAccountStatementImageFilePath . '/' . $v1;
            }
        }

        $positions = array_unique(array_filter(base_lib_BaseUtils::getProperty($list,'position_name')));
        $positions = $positions ? $positions : ['id' => '', 'name' => ''];
        foreach ($positions as $v) 
            $this->_aParams['position_arr'][] = ['id' => $v, 'name' => $v];
        $this->_aParams['position_arr'] = json_encode($this->_aParams['position_arr']);
        
		$this->_aParams['title']                = '用工管理';
		$this->_aParams['position_name']        = $position_name;
		$this->_aParams['user_name']            = $user_name;
		$this->_aParams['work_start_time']      = $work_start_time;
		$this->_aParams['work_end_time']        = $work_end_time;
		$this->_aParams['task_status']          = $task_status;
		$this->_aParams['list']                 = $list;
        $this->_aParams["pager"]                = $this->pageBar($data->totalSize, $page_size, $cur_page, $inPath);
		return $this->render("part/tallage/index.html", $this->_aParams);
	}
    
    /**
     * 上传流水图片
     * @param $inPath
     */
    function pageStatementImgs($inPath) {
        $pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
        $import_task_id = base_lib_BaseUtils::getStr($pathdata['import_task_id'], 'int', 0);

        //获取已上传图片
        $service_importtask = new base_service_tallage_importtask();
        $info = $service_importtask->getTaskById($import_task_id,"import_task_id,is_upload_salary,account_statement_imgs");
        $defaults_files = array ();
        $xml = SXML::load("../config/oa/config.xml");
        if($info && $info['account_statement_imgs']){
            $defaults_files = explode(',',$info['account_statement_imgs']);
        }
        foreach ($defaults_files as $k=>$v) 
            $defaults_files[$k] = base_lib_Constant::UPLOAD_FILE_URL . '/' . $xml->VirtualName . '/' . $xml->tallageAccountStatementImageFilePath . '/' . $v;

        $this->_aParams['defaults_files'] = $defaults_files;
        return $this->render('part/tallage/statementimgs.html', $this->_aParams);
    }
    
    /**
     * 获取居住地
     * 默认 如果是填写的重庆下的地区，不显示重庆，只显示详细地址，非重庆的 只显示一级城市和二级地区
     */
    private function getArea($area_id,$default='',$isShowAll=false) {
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
     	return implode('-', $names);
     } 

}