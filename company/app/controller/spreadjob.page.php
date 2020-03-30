<?php
/**
 * 
 * @ClassName controller_spreadjob
 * @Desc 兼职首页
 * @author linian
 * @date 2015-08-03 上午09:51:47
 */
class controller_spreadjob extends components_cbasepage{
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
		//品牌推广广告所用
		$this->_aParams['super_company_id'] = 299781;
		$this->_aParams['this_company_id'] = $this->_userid;
	}

	/**
	 * 职位推广列表
	 * @param $inPath
	 * @return mixed
	 */
	public function pageIndex($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$status = base_lib_BaseUtils::getStr($path_data['status'],'int',0);

		$this->_aParams['status'] = $status;

		$server_spread_job = new base_service_company_spread_spreadjob();
		$server_company_job = new base_service_company_job_job();


		$now_time = date('Y-m-d h:i:s',time());
		$item = "spread_id,job_id,company_id,status,create_time,is_effect,bid,budget,last_budget,quality_score,sort_score,jobsort,end_time";
		//获取公司职位

		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);

		$account_ids = $company_resources->all_accounts;
		$companyresources = $company_resources->getCompanyServiceSource(['account_resource']);
		$this->_aParams['count'] = $companyresources['spread_overage'];
		$this->_aParams['companyresources'] = $companyresources;

		//获取所有的推广职位
		$spread_job_lists = $server_spread_job->getJobSpreads($account_ids,'',$item)->items;
		//获取推广职位
		$spread_job_lists = $server_spread_job->getJobSpreads($account_ids,'',$item)->items;
		$job_ids = base_lib_BaseUtils::getProperty($spread_job_lists,'job_id');
		$spread_key_job = base_lib_BaseUtils::array_key_assoc($spread_job_lists,'job_id');

		//获取该公司在招职位
		if($companyresources['resource_type'] == 2){
			$company_jobs       = $server_company_job->getJobList($account_ids,'',1,'company_id,job_id,jobsort_id,jobsort,station,end_time,account_id',0,0,null,$accountid);
		}else{
			$company_jobs       = $server_company_job->getJobList($account_ids,'',1,'company_id,job_id,jobsort_id,jobsort,station,end_time,account_id');
		}
        $jobsorts           = base_lib_BaseUtils::getProperty($company_jobs, "jobsort");
        $jobsort_avg_list   = $server_spread_job->getCompanyBidAvgV2($jobsorts);
        $spread_jobsort_avg = base_lib_BaseUtils::array_key_assoc($jobsort_avg_list, "jobsort");

		$company_job_list = base_lib_BaseUtils::array_key_assoc($company_jobs,"job_id");
        
		$is_spread_jobs = array();
		$is_effect_jobs = array();
		$not_spread_jobs = array();
		//成都市场判断，如果是成都的，跳到成都职位列表

		$click_service = new base_service_company_spread_click();
		if($spread_job_lists && $company_jobs) {
			$job_ids = base_lib_BaseUtils::getProperty($spread_job_lists,'job_id');
			$job_ids = array_unique($job_ids);
			//获取实际消耗的金额
			$sumPrice = $click_service->getSpreadJobUsePriceById($job_ids);
			$sumPrice = base_lib_BaseUtils::array_key_assoc($sumPrice,'job_id');
			foreach ($company_jobs as $key => $val) {
				if(!in_array($val['job_id'],$job_ids)){
					$temp = array(
						'job_id' => $val['job_id'],
						'company_id' => $val['company_id'],
						'status' => 0,
						'station' => $val['station'],
						'bid' => '',
						'is_effect' => 1,
						'budget' => 0,
						'last_budget' => 0,
						'account_id'	=> $val['account_id'],
						'jobsort'	=> $val['jobsort']
					);
					array_push($not_spread_jobs, $temp);
					continue;
				}
				$banking = '';
				if($spread_key_job[$val['job_id']]['status'] == 1){
					$banking = $server_spread_job->getCompanyBidRankingByJobsort($spread_key_job[$val['job_id']]['spread_id'],$spread_key_job[$val['job_id']]['jobsort'],$spread_key_job[$val['job_id']]['sort_score']);
				}

				//$avgBid = $server_spread_job->getCompanyBidAvg($val['jobsort']);
                $_job_jobsort = $val["jobsort"];
                $_jobsort_avg = !empty($spread_jobsort_avg[$_job_jobsort]) ? (string)sprintf("%.2f",$spread_jobsort_avg[$_job_jobsort]["bidavg"]) : $spread_key_job[$val['job_id']]['bid'];
				$last_budget = (float)(empty($sumPrice[$val['job_id']]['price_sum']) ? 0 : $sumPrice[$val['job_id']]['price_sum']);
				$last_budget = $last_budget > 0 ? sprintf("%.2f",$last_budget) : 0;
				$temp = array(
					'spread_id'	=>	$spread_key_job[$val['job_id']]['spread_id'],
					'job_id' => $val['job_id'],
					'company_id' => $val['company_id'],
					'status' => $spread_key_job[$val['job_id']]['status'],
					'ranking' => $banking,
					'jobsort'	=> $spread_key_job[$val['job_id']]['jobsort'],
					'station' => $val['station'],
					'bid' => $spread_key_job[$val['job_id']]['bid'],
					'avgbid' => $_jobsort_avg,
					'is_effect' => 1,
					'budget' => $spread_key_job[$val['job_id']]['budget'],
					'last_budget' => $last_budget,
					'account_id'	=> $val['account_id'],
					'end_time' => empty($spread_key_job[$val['job_id']]['end_time'])? '' : date('Y-m-d H:i',strtotime($spread_key_job[$val['job_id']]['end_time'])),
				);
				if ($spread_key_job[$val['job_id']]['status'] == 1) {
					array_push($is_spread_jobs, $temp);
				}else{
					array_push($is_effect_jobs,$temp);
				}
			}


		}elseif(empty($spread_job_lists) && $company_jobs){
			foreach ($company_jobs as $key => $val) {
				$temp = array(
					'job_id' => $val['job_id'],
					'company_id' => $val['company_id'],
					'status' => 0,
					'station' => $val['station'],
					'bid' => '',
					'is_effect' => 1,
					'account_id'	=> $val['account_id'],
					'budget' => 0,
					'last_budget' => 0,
					'jobsort' => $val['jobsort'],
				);
				array_push($not_spread_jobs, $temp);
			}

		}

		if($status == 1){
			$job_spread_list = $is_spread_jobs;
		}elseif($status == 2){
			$job_spread_list = array_merge($is_effect_jobs,$not_spread_jobs);
		}else{
			$job_spread_list = empty($spread_job_lists) ? $not_spread_jobs : array_merge($is_spread_jobs,$is_effect_jobs,$not_spread_jobs);
		}

		$spread_job_lists = base_lib_BaseUtils::array_key_assoc($spread_job_lists,'job_id');
		//1.获取所有的job_id
		$pub_job_id = base_lib_BaseUtils::getProperty($job_spread_list,"job_id");

		//2.再获取职位的精品信息
		$service_company_job_quality = new base_service_company_job_quality();
		$job_quality_list = $service_company_job_quality->getJobQulityByJobId($pub_job_id)->items;
		$job_quality_list = base_lib_BaseUtils::array_key_assoc($job_quality_list,"job_id");



		$service_company_account = new base_service_company_account();
		$company_account_info = $service_company_account->getAccountList($this->_userid, 'account_id,user_id,user_name,head_photo,station,resource_type')->items;
		$assoc_accounts       = base_lib_BaseUtils::array_key_assoc($company_account_info, 'account_id');


		$a_list = $service_company_account->getAccountByAccount_ids(base_lib_BaseUtils::getProperty($job_spread_list, 'account_id'), 'account_id,user_id,user_name,head_photo,station');
		$a_list = base_lib_BaseUtils::array_key_assoc($a_list->items, 'account_id');

		$fields = 'account_id,company_id,is_main,user_id,user_name,mobile_phone,link_tel,station,head_photo,last_login_time,state,resource_type';
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$account = $service_company_account->getAccount($accountid, $fields);

		$companyService = new base_service_company_company();
		$company = $companyService->getCompany($this->_userid, 1, 'company_id,company_name,is_audit,audit_state,company_flag,resume_download_upperlimit,com_level,start_time,end_time,hr_manager,hr_manager_sex,hr_tel,linkman');

		//获取职位信息
		foreach($job_spread_list as $k=>$item){

			if($companyresources['resource_type'] == 2 && $accountid != $company_job_list[$item['job_id']]['account_id']){
				unset($job_spread_list[$k]);
				continue;
			}
			$tempIsEffect = empty($spread_job_lists[$item['job_id']]['is_effect']) ? 0 : $spread_job_lists[$item['job_id']]['is_effect'];
			$job_spread_list[$k]['is_effect'] = $tempIsEffect;
			$job_spread_list[$k]['bid'] = $item['bid'];
			$job_spread_list[$k]['budget'] = $item['budget'];
			$job_spread_list[$k]['last_budget'] = $item['last_budget'];
			$job_spread_list[$k]['end_time'] = $item['end_time'];

			$job_spread_list[ $k ]['can_do']   =  ($assoc_accounts[$item['account_id']]['resource_type']==1 && $account['resource_type'] == 1) || ($accountid == $item['account_id']) ? 1 : 2;  //只有共享模式的帐号可以相互操作功能
			//招聘人
			$job_spread_list[ $k ]['job_account_resource_type']   =  $assoc_accounts[$item['account_id']]['resource_type'];
			$job_spread_list[ $k ]['account_user_name'] = $a_list[ $item['account_id'] ]['user_name'] ? $a_list[ $item['account_id'] ]['user_name'] : $company['linkman'];//这里就用账户名

			//是否精品职位
			$is_quality = false;
			if($job_quality_list[$item['job_id']]['is_quality'] == 1 && $companyresources['isNewService']){
				$is_quality = true;
			}
			$job_spread_list[$k]['is_quality'] = $is_quality;

			//根据职位类别去拿首屏展示最低价
			$first_screen_bid=$server_spread_job->getFirstScreenBidByJobSort($job_spread_list[$k]["jobsort"],$this->_userid);
			$job_spread_list[ $k ]['first_screen_bid']=$first_screen_bid;
		}

		$this->_aParams['list'] = $job_spread_list;
		return $this->render("./spread/spreadjob.html",$this->_aParams);
	}

	public function pageModBid($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$company_id = base_lib_BaseUtils::getStr($path_data['company_id'],'int','');
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'],'int','');
		$type = base_lib_BaseUtils::getStr($path_data['type'],'string','');
		$source = base_lib_BaseUtils::getStr($path_data['source'],'string','');
		$company_id = empty($company_id) ? $this->_userid : $company_id;
		if(empty($job_id)){
			echo json_encode(array('msg'=>'职位为空'));
			return;
		}
		//获取该职位的首屏展示最低价
		$server_spread_job = new base_service_company_spread_spreadjob();
		$server_company_job = new base_service_company_job_job();
		$field= "spread_id,company_id,job_id,quality_score,bid,budget,last_budget,end_time,is_effect,jobsort";
		$spread_job_info = $server_spread_job->getJobSpreadByJobid($company_id,$job_id,$field);
		$comapny_job_info = $server_company_job->getJob($job_id,'company_id,job_id,jobsort',$company_id);
		$_job_jobsort=$spread_job_info['jobsort'];
		if(!$spread_job_info){
			$_job_jobsort=$comapny_job_info['jobsort'];
		}
		$jobsort_avg_list   = $server_spread_job->getCompanyBidAvgV2(array('jobsort'=>$comapny_job_info['jobsort']));
		$spread_jobsort_avg = base_lib_BaseUtils::array_key_assoc($jobsort_avg_list, "jobsort");
		$this->_aParams['first_screen_bid']=$server_spread_job->getFirstScreenBidByJobSort($_job_jobsort,$this->_userid);
		$this->_aParams['job_id'] = $job_id;
		$this->_aParams['company_id'] = $company_id;
		$this->_aParams['type'] = $type;
		$this->_aParams['source'] = $source;
		$this->_aParams['avgbid'] = !empty($spread_jobsort_avg[$_job_jobsort]) ? (string)sprintf("%.2f",$spread_jobsort_avg[$_job_jobsort]["bidavg"]) : $spread_job_info['bid'];
		return $this->render("./spread/modbid.html",$this->_aParams);
	}

	public function pageModBidDo($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$company_id = base_lib_BaseUtils::getStr($path_data['company_id'],'int','');
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'],'int','');
		$type = base_lib_BaseUtils::getStr($path_data['type'],'string','');
		$txtBid = base_lib_BaseUtils::getStr($path_data['txtBid'],'int','');
		$company_id = empty($company_id) ? $this->_userid : $company_id ;
		//$company_id = $this->_userid;
		if(empty($company_id)){
			echo json_encode(array('error'=>'请登录'));return;
		}
		if(empty($job_id)){
			echo json_encode(array('error'=>'职位不存在'));return;
		}
		if(empty($txtBid) || !is_numeric($txtBid)){
			echo json_encode(array('error'=>'请输入金额'));return;
		}
		//获取推广表中是否有数据
		$server_spread_job = new base_service_company_spread_spreadjob();
		$server_company_job = new base_service_company_job_job();


		$field= "spread_id,company_id,job_id,quality_score,bid,budget,last_budget,end_time,is_effect";
		$spread_job_info = $server_spread_job->getJobSpreadByJobid($company_id,$job_id,$field);
		$comapny_job_info = $server_company_job->getJob($job_id,'company_id,job_id,jobsort',$company_id);

		if(empty($comapny_job_info['jobsort'])){
			echo json_encode(array('error'=>'更新失败，职位类别为空'));return;
		}

		//获取该职位的质量分数

		$quality_score = $server_spread_job->getQualityScoreAvgByJobsort($company_id,$comapny_job_info['jobsort'])->items;

		$quality_score = empty($quality_score[0]['score_avg']) ? 1 : $quality_score[0]['score_avg'];
		$quality_score = round($quality_score,2);

		//获取推广金
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
		$companyresources = $company_resources->getCompanyServiceSource(['account_resource']);
		$spread_last = $companyresources['spread_overage'];

		if($type == 'bid'){
			$bid = $txtBid;
			$budget = 0;
		}else{
			$bid = 0;
			$budget = $txtBid;
		}
		$spreadjoblog_service = new base_service_company_spread_spreadjoblog();
		$job_service = new base_service_company_job_job();

		$jobinfo = $job_service->getJob($job_id,'station');
		//推广表没有数据，新增，有，update
		if(empty($spread_job_info)){

			//114656167          7963915     2  0
			$item = array(
				'company_id'	=>	$company_id,
				'job_id'		=>	$job_id,
				'status'		=>  '0',
				'create_time'	=>	date('Y-m-d h:i:s',time()),
				'is_effect'		=> 	0,
				'bid'			=> 	$bid,
				'budget'		=> 	$budget,
				'last_budget'	=>	0,
				'quality_score' =>	$quality_score,
				'sort_score'	=>	$bid*$quality_score*100,
				'jobsort'		=>	$comapny_job_info['jobsort'],
			);
            if($spread_last < $bid){
                if($companyresources['resource_type'] == 2){
                    echo json_encode(array('error'=>'剩余推广金不足，请联系主账号分配更多资源'));return;
                }else{
                    echo json_encode(array('error'=>'剩余推广金不足'));return;
                }
            }

			$result = $server_spread_job->addCompanySpread($item);

			if($result){

				$loginfo = array();
				$loginfo['company_id'] = $this->_userid;
				$loginfo['spread_id'] = $result;
				$loginfo['job_id'] = $job_id;
				$loginfo['station'] = $jobinfo['station'];
				$loginfo['content'] = '新增“'.$jobinfo['station'].'”职位推广开启，设置推广金出价为：'.$bid.'，设置推广金预算为：'.$budget;
				$spreadjoblog_service->addData($loginfo);

				self::addLogo('add','新增“'.$jobinfo['station'].'”职位推广开启，设置推广金出价为：'.$bid.'，设置推广金预算为：'.$budget);

				echo json_encode(array('msg'=>'更新成功','data'=>$item,'spread_id' => $result));return;
			}else{
				echo json_encode(array('error'=>'更新失败','data'=>$item));return;
			}

		}else{

			//更新出价和预算金额
			if($type == 'bid'){
				//1.出价 <= 预算
				if($spread_job_info['budget'] > 0 && $txtBid > $spread_job_info['budget']){
					echo json_encode(array('error'=>'修改失败，出价不能比预算大'));return;
				}
                if($spread_last < $txtBid){
                    if($companyresources['resource_type'] == 2){
                        echo json_encode(array('error'=>'剩余推广金不足，请联系主账号分配更多资源'));return;
                    }else{
                        echo json_encode(array('error'=>'剩余推广金不足'));return;
                    }
                }
				$data = array(
					'bid' => $txtBid,
					'sort_score' => $txtBid*$spread_job_info['quality_score']*100,
				);
				$timeMsg = "";
				if(!empty($spread_job_info['end_time']) && strtotime($spread_job_info['end_time']) <= time()){
					$data['status'] = 0;
					$timeMsg = "，但该职位推广已经截止";
				}

				$result = $server_spread_job->updateJobSpread($company_id,$job_id,$data);
				if($result){
					$loginfo = array();
					$loginfo['company_id'] = $this->_userid;
					$loginfo['spread_id'] = $spread_job_info['spread_id'];
					$loginfo['job_id'] = $job_id;
					$loginfo['station'] = $jobinfo['station'];
					$loginfo['content'] = '修改出价成功'.$timeMsg;
					$spreadjoblog_service->addData($loginfo);

					self::addLogo('update','“'.$jobinfo['station'].'”职位出价修改为'.$txtBid.$timeMsg);
					self::SpreadJobUnique($this->_userid,$job_id,$spread_job_info['spread_id']);
					echo json_encode(array('msg'=>'修改出价成功'.$timeMsg,'spread_id' => $spread_job_info['spread_id']));return;
				}else{
					echo json_encode(array('error'=>'修改出价失败'));return;
				}

			}else{

				//修改预算
				if($spread_job_info['bid'] > 0 && $txtBid < $spread_job_info['bid']){
					echo json_encode(array('error'=>'修改失败，预算不能比出价小'));return;
				}
                
                if($spread_last < $txtBid){
                    if($companyresources['resource_type'] == 2){
                        echo json_encode(array('error'=>'剩余推广金不足，请联系主账号分配更多资源'));return;
                    }else{
                        echo json_encode(array('error'=>'剩余推广金不足'));return;
                    }
                }
                
				//获取实际消耗的金额
				$click_service = new base_service_company_spread_click();
				$sumPrice = $click_service->getSpreadUsePrice($spread_job_info['spread_id'] , 1);

				if($sumPrice == false){
					$json = array('status'=>false , 'msg'=> '参数错误！');
					echo json_encode($json);return;
				}

				$sumPrice = empty($sumPrice['price_sum']) ? 0 : $sumPrice['price_sum'];
				$spread_job_info['budget'] = $spread_job_info['budget'] ? $spread_job_info['budget'] : 0;

				//上调预算
				if($txtBid > $spread_job_info['budget']){

					if($txtBid > $sumPrice){
						//1.新预算 > 实际消费金额  扣除推广金,剩余预算修改
						$deff_budget = $txtBid - $spread_job_info['budget'];

						$data = array(
							'budget'		=>	$txtBid,
							'last_budget'	=>	0,
						);

						if($spread_job_info['is_effect']==1){
							$data['status'] = 1;
						}

					}else{
						//2.新预算 < 实际扣款金额  只修改预算
						$data = array(
							'budget'		=>	$txtBid,
						);
					}
					$timeMsg = "";
					$spreadCount = $spread_last;
					if($spreadCount <=0){
						$data['status'] = 0;
						if($companyresources['resource_type'] == 2){
							$timeMsg = "，但总推广金不足，请联系主账号为您分配更多资源。";
						}else{
							$timeMsg = "，但总推广金不足";
						}
					}else if($sumPrice >= $txtBid){
						$data['status'] = 0;
						$timeMsg = "，但该职位推广预算已用完";
					}else if(!empty($spread_job_info['end_time']) && strtotime($spread_job_info['end_time']) <= time()){
						$data['status'] = 0;
						$timeMsg = "，但该职位推广时间已经截止";
					}
					$result = $server_spread_job->updateJobSpread($company_id,$job_id,$data);

					if($result){
						$loginfo = array();
						$loginfo['company_id'] = $this->_userid;
						$loginfo['spread_id'] = $spread_job_info['spread_id'];
						$loginfo['job_id'] = $job_id;
						$loginfo['station'] = $jobinfo['station'];
						$loginfo['content'] = '修改预算成功'.$timeMsg."上调预算：当前预算为：{$spread_job_info['budget']};实际消费金额：{$sumPrice};新调整预算为：{$txtBid};";
						$spreadjoblog_service->addData($loginfo);

						self::addLogo('update','“'.$jobinfo['station'].'”职位预算修改为'.$txtBid.$timeMsg);
						self::SpreadJobUnique($this->_userid,$job_id,$spread_job_info['spread_id']);
						echo json_encode(array('msg'=>'修改预算成功'.$timeMsg, 'spread_id' => $spread_job_info['spread_id']));return;
					}else{
						echo json_encode(array('error'=>'修改出价失败'));return;
					}

				}elseif($txtBid < $spread_job_info['budget']){
					// 下调
					$return_spread = 0;
					if($txtBid > $sumPrice){
						//1.新预算 > 实际消费   设置预算为新预算，剩余预算为新预算减已消费预算，返还推广金为旧剩余预算-新剩余预算
						$new_last_budget = $txtBid - $sumPrice;
						$return_spread = $spread_job_info['last_budget'] - $new_last_budget;

						$data = array(
							'budget'		=>	$txtBid,
							'last_budget'	=>	0,
							//'status'		=> 	0,
						);
					}else{
						$data = array(
							'budget'		=> $txtBid,
							'last_budget'	=> 0,
							'status'		=> 0,
						);
					}

					$timeMsg = "";
					if(!empty($spread_job_info['end_time']) && strtotime($spread_job_info['end_time']) <= time()){
						$data['status'] = 0;
						$timeMsg = "，该职位推广已经截止";
					}
					$result = $server_spread_job->updateJobSpread($company_id,$job_id,$data);
					if($result){
						$loginfo = array();
						$loginfo['company_id'] = $this->_userid;
						$loginfo['spread_id'] = $spread_job_info['spread_id'];
						$loginfo['job_id'] = $job_id;
						$loginfo['station'] = $jobinfo['station'];
						$loginfo['content'] = '修改预算成功'.$timeMsg."下调预算：当前预算为：{$spread_job_info['budget']};实际消费金额：{$sumPrice};新调整预算为：{$txtBid};";
						$spreadjoblog_service->addData($loginfo);

						self::addLogo('update','“'.$jobinfo['station'].'”职位预算修改为'.$txtBid.$timeMsg);
						self::SpreadJobUnique($this->_userid,$job_id,$spread_job_info['spread_id']);
						echo json_encode(array('msg'=>'修改预算成功'.$timeMsg , 'spread_id' => $spread_job_info['spread_id']));return;
					}else{
						echo json_encode(array('error'=>'修改预算失败'));return;
					}
				}
			}
		}
	}


	public function pageModShowTopImage(){
		return $this->render('./spread/modimage.html');
	}

	public function pageModEndTime($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$company_id = base_lib_BaseUtils::getStr($path_data['company_id'],'int','');
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'],'int','');
		$spread_id = base_lib_BaseUtils::getStr($path_data['job_id'],'int','');
		$source = base_lib_BaseUtils::getStr($path_data['source'],'string','');

		$server_spread_job = new base_service_company_spread_spreadjob();
		$jobspreadinfo = $server_spread_job->getSpreadInfoById($spread_id,'end_time');
		$endTime = "";
		if(!empty($jobspreadinfo['end_time'])){
			$endTime = date('Y-m-d H:i',strtotime($jobspreadinfo['end_time']));
		}

		$this->_aParams['company_id'] = $company_id;
		$this->_aParams['job_id'] = $job_id;
		$this->_aParams['spread_id'] = $spread_id;
		$this->_aParams['endTime'] = $endTime;
		$this->_aParams['source'] = $source;
		$this->_aParams['thisDate'] = date('Y-m-d H:i');

		return $this->render('./spread/modendtime.html',$this->_aParams);

	}
	public function pageModEndTimeDo($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$company_id = base_lib_BaseUtils::getStr($path_data['company_id'],'int','');
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'],'int','');
		$spread_id = base_lib_BaseUtils::getStr($path_data['spread_id'],'string','');
		$endtime = base_lib_BaseUtils::getStr($path_data['endtime'],'string','');
		$company_id = empty($company_id) ? $this->_userid : $company_id ;
		//$company_id = $this->_userid;
		if(empty($company_id)){
			echo json_encode(array('error'=>'请登录'));return;
		}
		if(empty($job_id)){
			echo json_encode(array('error'=>'职位不存在'));return;
		}
		if(empty($endtime)){
			echo json_encode(array('error'=>'请选择截止时间'));return;
		}

		if(!empty($endtime) && strtotime($endtime) < time()){
			echo json_encode(array('error'=>'截止时间请选择大于当前的时间'));return;
		}
		//获取推广表中是否有数据
		$server_spread_job = new base_service_company_spread_spreadjob();
		$server_company_job = new base_service_company_job_job();

		$resources_service = new base_service_company_resources_resources($this->_userid);

		$field= "spread_id,company_id,job_id,quality_score,bid,budget,last_budget,is_effect,end_time";
		$spread_job_info = $server_spread_job->getJobSpreadByJobid($company_id,$job_id,$field);
		$comapny_job_info = $server_company_job->getJob($job_id,'company_id,job_id,jobsort',$company_id);

		if(empty($comapny_job_info['jobsort'])){
			echo json_encode(array('error'=>'更新失败，职位类别为空'));return;
		}

		//获取该职位的质量分数

		$quality_score = $server_spread_job->getQualityScoreAvgByJobsort($company_id,$comapny_job_info['jobsort'])->items;

		$quality_score = empty($quality_score[0]['score_avg']) ? 1 : $quality_score[0]['score_avg'];
		$quality_score = round($quality_score,2);

		//获取推广金
		$spread_service = new base_service_company_spread_spread();
		$count = $spread_service->getEffectConsume($this->_userid);
		$spread_last = sprintf("%.2f",($count['count']-$count['used']));


		$bid = 0;
		$budget = 0;

		$spreadjoblog_service = new base_service_company_spread_spreadjoblog();
		$job_service = new base_service_company_job_job();
		$spread_history = new base_service_company_spread_spreadhistory();

		//推广表没有数据，新增，有，update
		if(empty($spread_job_info)){

			//114656167          7963915     2  0
			$item = array(
				'company_id'	=>	$company_id,
				'job_id'		=>	$job_id,
				'status'		=>  '0',
				'create_time'	=>	date('Y-m-d h:i:s',time()),
				'is_effect'		=> 	0,
				'bid'			=> 	0,
				'budget'		=> 	0,
				'last_budget'	=>	0,
				'quality_score' =>	$quality_score,
				'sort_score'	=>	$bid*$quality_score*100,
				'jobsort'		=>	$comapny_job_info['jobsort'],
				'end_time'		=>	$endtime
			);


			$result = $server_spread_job->addCompanySpread($item);

			if($result){

				$jobinfo = $job_service->getJob($job_id,'station');

				$loginfo = array();
				$loginfo['company_id'] = $this->_userid;
				$loginfo['spread_id'] = $result;
				$loginfo['job_id'] = $job_id;
				$loginfo['station'] = $jobinfo['station'];
				$loginfo['content'] = "新增职位推广，截止时间设置为：{$endtime}";
				$spreadjoblog_service->addData($loginfo);

				self::addLogo('add','新增“'.$jobinfo['station'].'”职位推广，截止时间设置为：'.$endtime);

				echo json_encode(array('msg'=>'职位推广更新成功','data'=>$item ,'spread_id' => $result));return;
			}else{
				echo json_encode(array('error'=>'职位推广更新失败','data'=>$item));return;
			}

		}else{
			$data = array();
			$data['end_time'] = $endtime;


			if($spread_job_info['is_effect']==1){
				$data['status'] = 1;
			}
			//获取实际消耗的金额
			$click_service = new base_service_company_spread_click();
			$sumPrice = $click_service->getSpreadUsePrice($spread_job_info['spread_id'] , 1);
			if($sumPrice == false){
				$json = array('status'=>false , 'msg'=> '参数错误！');
				echo json_encode($json);return;
			}
			$sumPrice = empty($sumPrice['price_sum']) ? 0 : $sumPrice['price_sum'];

			$accountid = base_lib_BaseUtils::getCookie('accountid');
			$company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
			$companyresources = $company_resources->getCompanyServiceSource(['account_resource']);
			$spreadCount = $companyresources['spread_overage'];

			$timeMsg = "";
			if($spreadCount <= 0){
				$data['status'] = 0;
				if($companyresources['resource_type'] == 2){
					$timeMsg = "，但总推广金不足，请联系主账号为您分配更多资源。";
				}else{
					$timeMsg = "，但总推广金不足";
				}
			}else if($sumPrice >= $spread_job_info['budget']){
				$data['status'] = 0;
				$timeMsg = "，但该职位推广预算已用完";
			}else if(!empty($spread_job_info['end_time']) && strtotime($endtime) <= time()){
				$data['status'] = 0;
				$timeMsg = "，但该职位推广时间已经截止";
			}

			$result = $server_spread_job->updateJobSpread($company_id,$job_id,$data);

			if($result){


				$jobinfo = $job_service->getJob($spread_job_info['job_id'],'station');

				$loginfo = array();
				$loginfo['company_id'] = $this->_userid;
				$loginfo['spread_id'] = $spread_job_info['spread_id'];
				$loginfo['job_id'] = $spread_job_info['job_id'];
				$loginfo['station'] = $jobinfo['station'];
				$loginfo['content'] = "修改职位推广，截止时间设置为：{$endtime}";
				$spreadjoblog_service->addData($loginfo);

				self::addLogo('update','将“'.$jobinfo['station'].'”职位的精准推广时间设置为：'.$endtime);
				self::SpreadJobUnique($this->_userid,$job_id,$spread_job_info['spread_id']);
				echo json_encode(array('msg'=>'职位推广更新成功'.$timeMsg , 'spread_id' => $spread_job_info['spread_id']));return;
			}else{
				echo json_encode(array('error'=>'职位推广更新失败'));return;
			}
		}
	}
	/**
	 * 修改状态
	 * @param $inPaht
	 */
	public function pageSetSpreadStatus($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$spread_id = base_lib_BaseUtils::getStr($path_data['spread_id'],'int','');
		$status = base_lib_BaseUtils::getStr($path_data['status'],'int','');
		if(empty($spread_id)){
			echo json_encode(array('error'=>'请选择推广职位'));return;
		}


		//获取推广职位信息
		$server_spread_job = new base_service_company_spread_spreadjob();
		$item = "spread_id,company_id,job_id,status,bid,budget,last_budget,is_effect,end_time";
		$spread_job_info = $server_spread_job->getSpreadJobById($spread_id,$item);

		if(empty($spread_job_info) || empty($spread_job_info['bid'])|| empty($spread_job_info['budget'])){
			echo json_encode(array('error'=>'请先设置出价和预算金额'));return;
		}

		/****************************精品职位逻辑判断***************************/
		//开启精品推广时，判断职位是否为精品职位
		$service_company_job_quality = new base_service_company_job_quality();

		//成都市场判断，如果是成都的，判断是否为精品职位
		$accountid = base_lib_BaseUtils::getCookie('accountid');
		$company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
		$companyresources = $company_resources->getCompanyServiceSource(['account_resource']);


		if(empty($spread_job_info['is_effect']) && $companyresources['isNewService']){
			$job_quality = $service_company_job_quality->getJobIsQuality($spread_job_info['job_id']);
			if(!$job_quality || empty($job_quality)){
				echo json_encode(array('error'=>'精品推广职位必须为精品职位','err_code' => 'set_quality','job_id' => $spread_job_info['job_id']));return;
			}
		}

		/****************************精品职位逻辑判断***************************/

		$job_service = new base_service_company_job_job();
		$jobinfo = $job_service->getJob($spread_job_info['job_id'],'station');

		$spreadjoblog_service = new base_service_company_spread_spreadjoblog();
		//获取实际消耗的金额
		$click_service = new base_service_company_spread_click();
		$sumPrice = $click_service->getSpreadUsePrice($spread_job_info['spread_id'] , 1);
		if($sumPrice == false){
			$json = array('status'=>false , 'msg'=> '参数错误！');
			echo json_encode($json);return;
		}
		$sumPrice = empty($sumPrice['price_sum']) ? 0 : $sumPrice['price_sum'];
		$timeMsg = "";
		if($spread_job_info['is_effect'] ==1){
			//关闭 推广位下线
			$data = array(
				'status' 	=> 0,
				'is_effect' 	=> 0,
			);

			$loginfo = array();
			$loginfo['company_id'] = $this->_userid;
			$loginfo['spread_id'] = $spread_id;
			$loginfo['job_id'] = $spread_job_info['job_id'];
			$loginfo['station'] = $jobinfo['station'];
			$loginfo['content'] = "职位推广手动关闭";
			$spreadjoblog_service->addData($loginfo);

			$logocontent = "关闭了“{$jobinfo['station']}”职位精准推广";

			$msg = "设置成功";
		}else{

			$data = array(
				'status' 	=> 1,
				'is_effect' 	=> 1,
			);

			$msg = "设置成功";

			$loginfo = array();
			$loginfo['company_id'] = $this->_userid;
			$loginfo['spread_id'] = $spread_id;
			$loginfo['job_id'] = $spread_job_info['job_id'];
			$loginfo['station'] = $jobinfo['station'];
			$loginfo['content'] = "职位推广手动开启";
			$spreadjoblog_service->addData($loginfo);

			$logocontent = "开启了“{$jobinfo['station']}”职位精准推广";
			$accountid = base_lib_BaseUtils::getCookie('accountid');
			$company_resources = base_service_company_resources_resources::getInstance($this->_userid,true,$accountid);
			$companyresources = $company_resources->getCompanyServiceSource(['account_resource']);
			$spreadCount = $companyresources['spread_overage'];
            if($spreadCount < $spread_job_info['bid']){
                if($companyresources['resource_type'] == 2){
                    echo json_encode(array('error'=>'设置失败，推广金不足，请联系主账号为您分配更多资源'));return;
				}else{
                    echo json_encode(array('error'=>'设置失败，推广金不足'));return;
				}
            }
            
            
			if($spreadCount<=0){
				if($companyresources['resource_type'] == 2){
                    echo json_encode(array('error'=>'设置失败，推广金不足，请联系主账号为您分配更多资源'));return;
				}else{
                    echo json_encode(array('error'=>'设置失败，推广金不足'));return;
				}

				$data['status'] = 0;
			}else if($sumPrice >= $spread_job_info['budget']){
				$timeMsg = "，但该职位推广预算已用完";
				$data['status'] = 0;
			}else if(!empty($spread_job_info['end_time']) && strtotime($spread_job_info['end_time']) <= time()){
				$data['status'] = 0;
				$timeMsg = "，但该职位推广时间已经截止";
			}
		}

		$result = $server_spread_job->updateJobSpread($spread_job_info['company_id'],$spread_job_info['job_id'],$data);
		if($result){
			self::addLogo('update',$logocontent);
			self::SpreadJobUnique($this->_userid,$spread_job_info['job_id'],$spread_job_info['spread_id']);
			echo json_encode(array('msg'=>$msg.$timeMsg,'status'=>true));return;
		}else{
			echo json_encode(array('error'=>'设置失败'));return;
		}
	}

	private function addLogo($type = 'add' , $content){

		$common_oper_src_type = new base_service_common_account_accountlogfrom();
		$common_oper_type = new base_service_common_account_accountoperatetype();
		$service_oper_log = new base_service_company_companyaccountlog();

		$operate_type = $type == "add" ? $common_oper_type->spread_JobAdd : $common_oper_type->spread_JobUpdate;

		$insertItems=array(
			"company_id"=>$this->_userid,
			"source"=>$common_oper_src_type->website,
			"account_id"=>base_lib_BaseUtils::getCookie('accountid'),
			"operate_type"=>$operate_type,
			"content"=>$content,
			"create_time"=>date("Y-m-d H:i:s",time())
		);
		$service_oper_log->addLogToMongo($insertItems);

	}


	/*
	 * 职位推广数据去除重复
	 * */
	private function SpreadJobUnique($company_id,$job_id,$spread_id){
		if(empty($company_id) || empty($job_id) || empty($spread_id)){
			return false;
		}

		$spreadJob_service = new base_service_company_spread_spreadjob();
		$list = $spreadJob_service->JobSpreadsUnique($company_id , $job_id , $spread_id);
	}

}

?>