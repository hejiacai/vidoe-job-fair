<?php
	/** 
	 * @copyright 2004 www.huibo.com
	 * @name 岗位特点管理
	 * @author huangwt
	 * @date 2014-11-12
	 *
	*/
	class controller_feature extends components_cbasepage {
		
		function __construct(){
			parent::__construct();
		}
		
                /**
                * 岗位特点入口
                */
		public function pageIndex($inPath) {
                   $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
                     $defaultFea = base_lib_BaseUtils::getStr($path_data['defaultFea'],'string','');
                     $otherFea = base_lib_BaseUtils::getStr($path_data['otherFea'],'string','');
                     $obj = base_lib_BaseUtils::getStr($path_data['obj'], 'string', '');
                     $callback = base_lib_BaseUtils::getStr($path_data['callback'], 'string', '');
                     $this->_aParams['obj'] = $obj;
                     $this->_aParams['callback'] = $callback;
                     $service_feature = new base_service_common_feature();
                     $all_feature_data = $service_feature->getAll();
                     //获得公司已有的福利
                     $service_job = new base_service_company_job_job();
                     $jobflag = base_lib_BaseUtils::getStr($path_data['jobid'],'string','');
                     $jobid = base_lib_Rewrite::getId('job', $jobflag);
                     if($jobid ==0 || $jobflag == ''){
                            //添加岗位特点入口
                          $job_features = array();//添加岗位特点时 岗位特点默认文库
                     }else{
                         //修改岗位特点入口  修改时 就有
                          $job_features = $service_job->getJob($jobid,'job_id,job_feature_ids,job_other_feature');
                     }
                    
                     //已经选择了的福利
                     if(base_lib_BaseUtils::nullOrEmpty($defaultFea) || $defaultFea ==0){
                        $job_default_features = $job_features['job_feature_ids'];
                        if(!base_lib_BaseUtils::nullOrEmpty($job_default_features)){
                            $job_default_features_arr = explode(',', $job_default_features);
                        }else{
                            $job_default_features_arr = array();
                        }
                     }else{
                         $job_default_features_arr = explode(',',$defaultFea);
                     }
                     //公司的其他福利
                      if(base_lib_BaseUtils::nullOrEmpty($otherFea) || $otherFea =="0"){
                           $job_other_features = $job_features['job_other_feature'];
                           if(!base_lib_BaseUtils::nullOrEmpty($job_other_features)){
                               $job_other_features_arr = explode(',', $job_other_features);
                           }else{
                               $job_other_features_arr = array();
                           }
                      }else{
                          $job_other_features_arr = explode(',',$otherFea);
                      }
                     $this->_aParams['job_other_features'] = $job_other_features_arr;
                     $this->_aParams['job_default_features'] = $job_default_features_arr;
                     $count = count($job_other_features_arr) + count($job_default_features_arr);
                     $this->_aParams['count'] = $count;
                     $this->_aParams['all_feature_data'] = $all_feature_data;
                     return $this->render('./feature/index.html', $this->_aParams);
                }
        }
?>