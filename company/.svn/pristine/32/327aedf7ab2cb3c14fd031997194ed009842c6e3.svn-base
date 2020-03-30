<?php
	/** 
	 * @copyright 2004 www.huibo.com
	 * @name 部门管理
	 * @author huangwt
	 * @date 2014-11-10
	 *
	*/
	class controller_department extends components_cbasepage {
		
		function __construct(){
			parent::__construct();
		}
		
                /**
                * 部门管理入口
                */
		public function pageIndex($inPath) {
                     $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
                    $company_id = $this->_userid;
                    $service_dept = new base_service_company_dept();
                    $dept_data = $service_dept->getAdeptInfo($company_id,'dept_id,dept_name,order_no');
                    $this->_aParams['dept_data'] = $dept_data;
                    $obj = base_lib_BaseUtils::getStr($path_data['obj'], 'string', '');
                    $callback = base_lib_BaseUtils::getStr($path_data['callback'], 'string', '');
                    $this->_aParams['obj'] = $obj;
                    $this->_aParams['callback'] = $callback;
                   return $this->render('./department/index.html', $this->_aParams);
                }
                
                
              /**
               *@desc 保存部门信息
               *@param array $inPath
               */  
               public function pageSaveDept($inPath){
                   $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
                   $company_id = $this->_userid;
                   $addOrder = base_lib_BaseUtils::getStr($path_data['addOrder'],'array',array());
                   $addDept = base_lib_BaseUtils::getStr($path_data['addDept'],'array',array());
                   $editOrder = base_lib_BaseUtils::getStr($path_data['editOrder'],'array',array());
                   $editDept = base_lib_BaseUtils::getStr($path_data['editDept'],'array',array());
                   $deptids = base_lib_BaseUtils::getStr($path_data['deptids'],'array',array());
                   $service_dept = new base_service_company_dept();
                   if(count($addDept) >0){
                       foreach($addDept as $k => $dept){
                           //验证数据的有效性
                           if(mb_strlen($dept)>15){
                                echo json_encode(array('error'=>'部门 '.$dept.' 不能超过15个字'));exit;
                           }
                       }
                   }
                   if(count($editDept) >0){
                       foreach($editDept as $k => $dept){
                            //验证数据的有效性
                           if(mb_strlen($dept)>15){
                                echo json_encode(array('error'=>'部门 '.$dept.' 不能超过15个字'));exit;
                           }
                       }
                   }
                   //开始添加部门
                   if(count($addDept) >0){
                       foreach($addDept as $k => $dept){
                          $add_data = array();
                          $add_data['company_id'] = $company_id;
                          $add_data['dept_name'] = trim($dept);
                          if(base_lib_BaseUtils::nullOrEmpty($add_data['dept_name'])){
                              continue;
                          }
                          $add_data['order_no'] = isset($addOrder[$k]) ? intval(trim($addOrder[$k])) : 0;
                          $result = $service_dept->addCompanyDept($add_data);
                          if(!$result){
                              echo json_encode(array('error'=>'保存部门 '.$dept." 中断，请重试"));exit;
                          }
                       }
                   }
                   //开始编辑部门
                   if(count($deptids) >0){
                       foreach($deptids as $k => $deptid){
                           $edit_data = array();
                           $edit_data['dept_name'] = trim($editDept[$k]);
                           $edit_data['order_no'] = isset($editOrder[$k]) ? intval(trim($editOrder[$k])) : 0;
                           if(base_lib_BaseUtils::nullOrEmpty($edit_data['dept_name'])){
                              continue;
                          }
                           $result = $service_dept->modifyDept($deptid,$edit_data);
                           if(!$result){
                                 echo json_encode(array('error'=>'修改部门 '.$editDept[$k]." 中断，请重试"));exit;
                           }
                       }
                   }
                   echo json_encode(array('success'=>'保存部门成功'));exit;
                   
               }
               /**
               *@desc 删除部门信息
               *@param array $inPath
               */  
               function pageDeleteDept($inPath){
                    $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
                    $dept_id = base_lib_BaseUtils::getStr($path_data['dept_id'],'int',0);
                    $service_dept = new base_service_company_dept();
                    if($dept_id ==0){
                         echo json_encode(array('error'=>'删除失败，部门ID出错'));exit;
                    }
                    $result = $service_dept->deleteDept($this->_userid,$dept_id);
                    if($result){
                         echo json_encode(array('success'=>'删除成功'));exit;
                    }else{
                        echo json_encode(array('error'=>'删除失败'));exit;
                    }
                    
               }
               /**
               *@desc 获得更新后的部门数据
               *@param array $inPath
               */  
               function pageAjaxGetDept($inPath){
                  $service_dept = new base_service_company_dept();
                  $dept_info = $service_dept->getAdeptInfo($this->_userid,'dept_id,dept_name');
                  $dept_json = array();
                  if(count($dept_info) >0){
                     foreach($dept_info as $dept){
                         $dept_json[] = array('id'=>$dept['dept_name'],'name'=>$dept['dept_name']);
                     }
                  }
                  echo json_encode(array('success'=>true,'dept_data'=>$dept_json));exit;
               }
              
        }
        
?>