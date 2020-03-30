<?php
/**
 * @ClassName file_name
 * @Desc 
 * @author Administrator
 * @date 2015年8月3日 下午1:30:32
 */
class controller_partorder extends components_cbasepage{

	public function __construct(){
		parent::__construct(true, "part");
	}


	/**
	 * 购买简历点
	 * @param $inPath
	 * @return mixed
	 */
	public function pagebuyresumepoint($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

		$is_refresh = base_lib_BaseUtils::getStr($path_data['is_refresh'],'string','');

		//获取账号余额
		$company_resources              = base_service_company_resources_resources::getInstance($this->_userid);
		$company_service_source = $company_resources->getCompanyServiceSource($items=['has_pub_job_num','refresh_perday','refresh_today_num','spread_overage','account_overage'], true);

		//获取服务套餐码表
		$service_common_part_resumesetmeal = new base_service_common_part_resumesetmeal();
		$resumesetmeal = $service_common_part_resumesetmeal->getAll();

		//判断各套餐是否能用余额购买
		foreach($resumesetmeal as $key => &$val){
			$pay_num = $val['price'];
			if($val['discount']){
				$pay_num = $val['price']*$val['discount'];
			}
			$is_pay_balance = true;
			if($pay_num > $company_service_source['account_overage']){
				$is_pay_balance = false;
			}
			$val['is_pay_balance'] = $is_pay_balance;
		}

		//是否绑定销售
        $companyStateService               = new base_service_company_comstate();
        $companyState                      = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
        $hrManager                         = $this->GetHRManager($companyState["net_heap_id"]);
        $xml                               = SXML::load('../config/company/company.xml');
        $this->_aParams['default_qq']      = $xml->PartDeaultQQ;
        $this->_aParams['is_bind_hr']      = empty($hrManager) ? false : true;
        $this->_aParams['is_refresh']      = $is_refresh;
        $this->_aParams['resumesetmeal']   = $resumesetmeal;
        $this->_aParams['account_overage'] = $company_service_source['account_overage'];

		return $this->render("./part/pay/buyresumepoint.html",$this->_aParams);
	}

	/**
	 * 购买职位置顶
	 * @param $inPath
	 */
	public function pageBuyJobTop($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$job_id = base_lib_BaseUtils::getStr($path_data['job_id'],"int","");
		$is_refresh = base_lib_BaseUtils::getStr($path_data['is_refresh'],'string','');
		if(empty($job_id)){
			echo "请选择置顶职位";exit();
		}

		//获取职位信息
		$service_part_company_job = new base_service_part_company_job();
		$job_info = $service_part_company_job->getJob($job_id,"job_id,station,jobsort_id");

		if(empty($job_info)){
			echo "职位不存在或已停招";exit();
		}
		$service_common_part_jobsort = new base_service_common_part_jobsort();
		$jobsort_name = $service_common_part_jobsort->getName($job_info['jobsort_id']);


		//获取账号余额
		$company_resources              = base_service_company_resources_resources::getInstance($this->_userid);
		$company_service_source = $company_resources->getCompanyServiceSource($items=['has_pub_job_num','refresh_perday','refresh_today_num','spread_overage','account_overage'], true);

		//获取置顶类型
		$service_common_part_jobtoptype = new base_service_common_part_jobtoptype();
		$top_type = $service_common_part_jobtoptype->getAll();

		//验证是否能置顶
		$service_part_job_partjobtop = new base_service_part_job_partjobtop();
		$check_result = $service_part_job_partjobtop->checkJobTop($job_id);
		if(!$check_result['status']){
			echo "该职位已置顶";exit();
		}
        $service_order = new base_service_part_order_partorder();
        $PartMoneyToRmb = $service_order->getPriceConfig('PartMoneyToRmb');
        $PartJobPrice = $service_order->getPriceConfig('PartJobPrice');
        $FreePartJobNum = $service_order->getPriceConfig('FreePartJobNum');

        $this->_aParams['PartMoneyToRmb'] = $PartMoneyToRmb;
		$this->_aParams['is_refresh'] = $is_refresh;
		$this->_aParams['check_type'] = $check_result['type'];
		$this->_aParams['job_id'] = $job_id;
		$this->_aParams['jobsort_name'] = $jobsort_name;
		$this->_aParams['top_type'] = $top_type;
		$this->_aParams['account_overage'] = $company_service_source['account_overage'];
		return $this->render("./part/pay/buyjobtop.html",$this->_aParams);
	}

    /**
     * 购买职位发布数量
     * @param $inPath
     */
    public function pageBuyJobNum($inPath){
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $is_refresh = base_lib_BaseUtils::getStr($path_data['is_refresh'],'string','');


        //获取账号余额
        $company_resources              = base_service_company_resources_resources::getInstance($this->_userid);
        $company_service_source = $company_resources->getCompanyServiceSource($items=['has_pub_job_num','refresh_perday','refresh_today_num','spread_overage','account_overage'], true);

        $service_order = new base_service_part_order_partorder();
        $PartMoneyToRmb = $service_order->getPriceConfig('PartMoneyToRmb');
        $PartJobPrice = $service_order->getPriceConfig('PartJobPrice');

        $this->_aParams['PartMoneyToRmb'] = $PartMoneyToRmb;
        $this->_aParams['PartJobPrice'] = $PartJobPrice;
        $this->_aParams['is_refresh'] = $is_refresh;
        $this->_aParams['account_overage'] = $company_service_source['account_overage'];
        return $this->render("./part/pay/buyjobnum.html",$this->_aParams);
    }

	/**
	 * pc职位置顶和购买简历点生成订单
	 * @param $inPath
	 * @return string
	 */
	public function pagecreateOrder($inPath){
		echo header("Content-type:text/json;charset=utf-8");
		$path_data 		= base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$pay_type 		= base_lib_BaseUtils::getStr($path_data['pay_type'], 'int');
		$meal_id 		= base_lib_BaseUtils::getStr($path_data['meal_id'], 'int');
		$order_src 		= base_lib_BaseUtils::getStr($path_data['order_src'], 'int');

		$com_ord_src = $service_common_part_partordersrc = new base_service_common_part_partordersrc();
		$service_part_order_partorder = new base_service_part_order_partorder();
		$service_common_part_partorderpaytype = new base_service_common_part_partorderpaytype();

		//生成订单数据
		$order_data = array(
			'pay_type'			=> $pay_type,
			'order_src'			=> $order_src,
			'company_id'		=> $this->_userid,
		);

		if($pay_type == $service_common_part_partorderpaytype->partpoint){
            return $this->jsonMsg(false,"该接口暂不接受兼职币购买");
        }

		//todo 调试
//		if($pay_type == 1){
//            return $this->jsonMsg(false,'XXXX',$order_data);
//        }

		//订单来源判断
		if(in_array($order_src,[$com_ord_src->pcresumepoint,$com_ord_src->mobileresumepoint])){
			//pc购买简历点，构建简历点数据
			if(empty($meal_id)){
				return $this->jsonMsg(false,"请选择要购买的套餐类型");
			}

			$service_common_part_resumesetmeal = new base_service_common_part_resumesetmeal();
			$resumesetmeal = $service_common_part_resumesetmeal->getSetmealByCode($meal_id);
			if(empty($resumesetmeal)){
				return $this->jsonMsg(false,"套餐类型不存在");
			}
			$resume_point_data = array(
				'company_id'		=> $this->_userid,
				'setmeal_id'		=> $meal_id,
				'resume_point_num'	=> $resumesetmeal['point_num'],
				'is_effect'			=> 0,
				'create_time'		=> date("Y-m-d H:i:s"),
				'end_time'			=> date("Y-m-d H:i:s",strtotime("+1 year")),
				'last_point_num'	=> $resumesetmeal['point_num'],
			);

			$order_data['resume_point'] = $resume_point_data;
			$order_data['order_money'] = empty($resumesetmeal['discount']) ? $resumesetmeal['price'] : $resumesetmeal['price']*$resumesetmeal['discount'];
			$order_data['real_total_money'] = empty($resumesetmeal['discount']) ? $resumesetmeal['price'] : $resumesetmeal['price']*$resumesetmeal['discount'];
			$order_data['body'] = "汇博-兼职币购买";
		}
		elseif(in_array($order_src,[$com_ord_src->pcjobtop,$com_ord_src->mobilejobtop,$com_ord_src->wxjobtop]))
        {
			//职位置顶
			$job_id 		= base_lib_BaseUtils::getStr($path_data['job_id'], 'int','');
			$top_day 		= base_lib_BaseUtils::getStr($path_data['top_day'], 'int');
			$top_type 		= base_lib_BaseUtils::getStr($path_data['top_type'], 'int');

			if(empty($job_id)){
				return $this->jsonMsg(false,"请选择要置顶的职位");
			}
			$service_part_company_job = new base_service_part_company_job();
			$job_info = $service_part_company_job->getJob($job_id,"job_id,station,jobsort_id");
			if(empty($job_info)){
				return $this->jsonMsg(false,"职位不存在或已停招");
			}
			if(empty($top_day) || !is_numeric($top_day) || $top_day < 0){
				return $this->jsonMsg(false,"置顶天数应该为正整数");
			}

			$service_common_part_jobtoptype = new base_service_common_part_jobtoptype();
			$job_top_type = $service_common_part_jobtoptype->gettoptypeByCode($top_type);
			if(empty($job_top_type)){
				return $this->jsonMsg(false,"职位置顶类型错误");
			}

			$service_common_jobsort = new base_service_common_jobsort();
			$jobsort_name = $service_common_jobsort->getJobsortName($job_info['jobsort_id']);

			//构造职位置顶数据
			$job_top_data = array(
				'company_id'	=> $this->_userid,
				'job_id'		=> $job_id,
				'create_time'	=> date("Y-m-d H:i:s"),
				'is_effect'		=> 0,
				'jobsort'		=> $job_info['jobsort_id'],
				'top_type'		=> $top_type,
				'top_day'		=> $top_day,
				'keyworld'		=> $job_info['station'].','.$jobsort_name
			);

			$order_data['top_data'] 		= $job_top_data;
			$order_data['order_money'] 		= $job_top_type['price'] * $top_day;
			$order_data['real_total_money'] = $job_top_type['price'] * $top_day;
			$order_data['body'] = "汇博-兼职职位置顶";
		}
        elseif(in_array($order_src,[$com_ord_src->pcjobnum,$com_ord_src->mjobnum,$com_ord_src->wxjobnum])){
		    //购买发布职位条数
            $job_num 		= base_lib_BaseUtils::getStr($path_data['job_num'], 'int',0);
            $job_num = intval($job_num);
            if ($job_num === false || ($job_num)<=0)
                return $this->jsonMsg(false,"购买条数必须是大于0的正整数");

            $service_order = new base_service_part_order_partorder();
            $PartMoneyToRmb = $service_order->getPriceConfig('PartMoneyToRmb');
            $PartJobPrice = $service_order->getPriceConfig('PartJobPrice');

            $jobnum_data = [
                'job_num'=>$job_num,
                'company_id'		=> $this->_userid,
            ];
            $order_data['jobnum_data'] 		= $jobnum_data;
            $order_data['order_money'] 		= $PartMoneyToRmb * $PartJobPrice * $job_num;
            $order_data['real_total_money'] = $PartMoneyToRmb * $PartJobPrice * $job_num;
            $order_data['body'] = "汇博-兼职职位数购买";

        }



		$result = $service_part_order_partorder->addOrder($order_data);

		if($result['code'] == ERROR){
			return $this->jsonMsg(false,$result['msg'],$order_data);
		}else{
			if($pay_type == $service_common_part_partorderpaytype->balance){
				return $this->jsonMsg(true,$result['data']['return_msg'],$result['data']);
			}else{
				return $this->jsonMsg(true,"订单生成成功",$result['data']);
			}
		}
	}


    /**
     * 兼职币购买职位置顶
     */
    public function pageResumePointJobTopBuy($inPath)
    { 
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $job_id    = base_lib_BaseUtils::getStr($path_data['job_id'], 'int', '');
        $top_day   = base_lib_BaseUtils::getStr($path_data['top_day'], 'int',0);
        $top_type  = base_lib_BaseUtils::getStr($path_data['top_type'], 'int');

        if (empty($job_id) || empty($top_type))
            exit(json_encode(['status' => false, 'msg' => '传入参数不能为空', 'code' => 0]));

        $top_day = intval($top_day);
        if ($top_day === false || ($top_day)<=0)
            exit(json_encode(['status' => false, 'msg' => '置顶天数必须是大于0的正整数', 'code' => 0]));


      
        $service_order = new base_service_part_order_partorder();
        $PartMoneyToRmb = $service_order->getPriceConfig('PartMoneyToRmb');
        $PartJobPrice = $service_order->getPriceConfig('PartJobPrice');
        $FreePartJobNum = $service_order->getPriceConfig('FreePartJobNum');

        $service_pointaccount   = new base_service_part_company_resumepoint_pointaccount();
        $last_point             = $service_pointaccount->getAccountByCompany($this->_userid, 'last_amount');
        $service_pointmanager   = new base_service_part_company_resumepoint_pointmanager();
        $service_common_job_top = new base_service_common_part_jobtoptype();
        $spent_amount           =  $top_day * $service_common_job_top->gettoptypeByCode($top_type)['price']/$PartMoneyToRmb;

        if ($spent_amount > $last_point['last_amount']) {
            exit(json_encode(['status' => false, 'msg' => '余额不足', 'code' => 1]));
        }

        //todo 调试
//        exit(json_encode(['status' => false, 'msg' => '调试错误', 'code' => 1]));


        $res = $service_pointmanager->pointSpnetDo(true, $this->_userid, $spent_amount, 4, null, null, null,
            'base_service_part_job_partjobtop', 'jobTopDo', [$this->_userid, $job_id, $top_type, $top_day]);
        if ($res['status']) {
            exit(json_encode(['status' => true, 'msg' => '支付成功', 'code' => 0]));
        } else
            exit(json_encode(['status' => false, 'msg' => $res['msg'], 'code' => 0]));
    }

    /**
     * 兼职币购买职位数量
     */
    public function pageResumePointJobNumBuy($inPath)
    {
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $job_num   = base_lib_BaseUtils::getStr($path_data['job_num'], 'int', 0);

        $job_num = intval($job_num);
        if ($job_num === false || ($job_num)<=0)
            exit(json_encode(['status' => false, 'msg' => '购买条数必须是大于0的正整数', 'code' => 0]));


        $service_order        = new base_service_part_order_partorder();
        $PartJobPrice         = $service_order->getPriceConfig('PartJobPrice');
        $service_pointaccount = new base_service_part_company_resumepoint_pointaccount();
        $last_point           = $service_pointaccount->getAccountByCompany($this->_userid, 'last_amount');
        $service_pointmanager = new base_service_part_company_resumepoint_pointmanager();
        $spent_amount         = $job_num * $PartJobPrice;//花费多少兼职币


        if ($spent_amount > $last_point['last_amount']) {
            exit(json_encode(['status' => false, 'msg' => '余额不足', 'code' => 1]));
        }

        //todo 调试
        //exit(json_encode(['status' => false, 'msg' => '调试错误', 'code' => 1]));


        $res = $service_pointmanager->pointSpnetDo(true, $this->_userid, $spent_amount, 5, null, null, null,
            'base_service_part_company_payjobnum', 'doBug', [$this->_userid, $job_num,4]);
        if ($res['status']) {
            exit(json_encode(['status' => true, 'msg' => '支付成功', 'code' => 0]));
        } else
            exit(json_encode(['status' => false, 'msg' => $res['msg'], 'code' => 0,'debug'=>"{$job_num},{$artJobPrice}花费：{$spent_amount},余额{$last_point['last_amount']}"]));
    }


    private function GetHRManager($heap_id){
        $companyHeapService = new base_service_company_netheap();
        $companyHeap = $companyHeapService->GetNetHeapByID($heap_id, "own_man");
        $userInfor = null;
        if(is_null($companyHeap) || !isset($companyHeap["own_man"])){
            return $userInfor;
        }
        $userService=new base_service_crm_user();
        $userInfor = $userService->GetUsers($companyHeap['own_man'], "user_name,head_photo_url,tel,mobile,qq");
        return $userInfor;
    }


    /**
	 * 扫码支付
	 * @param $inPath
	 * @return mixed
	 */
	public function pagepay($inPath){
		$path_data 		= base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$code_url 		= base_lib_BaseUtils::getStr($path_data['code_url'], 'string');
		$order_no 		= base_lib_BaseUtils::getStr($path_data['order_no'], 'string');
		$is_refresh 	= base_lib_BaseUtils::getStr($path_data['is_refresh'], 'string');

		if(empty($code_url)){
			echo header("Content-type:text/plain;charset=utf-8");
			echo "支付二维码生成失败";exit();
		}
		if(empty($order_no)){
			echo header("Content-type:text/plain;charset=utf-8");
			echo "订单号不存在";exit();
		}

		$service_part_order_partorder = new base_service_part_order_partorder();
		$service_part_person_partresumepoint = new base_service_part_company_resumepoint_pointin();
		$service_part_job_partjobtop = new base_service_part_job_partjobtop();
		$service_common_part_jobtoptype = new base_service_common_part_jobtoptype();
		$service_common_part_resumesetmeal = new base_service_common_part_resumesetmeal();
		$service_common_part_partorderpaytype = new base_service_common_part_partorderpaytype();


		//获取订单信息
		$order_info = $service_part_order_partorder->getOrderInfoByOrderNo($order_no);

		if(empty($order_no)){
			echo header("Content-type:text/plain;charset=utf-8");
			echo "订单不存在";exit();
		}
		if($order_info['order_status'] == 1){
			echo header("Content-type:text/plain;charset=utf-8");
			echo "订单已支付";exit();
		}
		if($order_info['order_status'] == 2){
			echo header("Content-type:text/plain;charset=utf-8");
			echo "订单已取消";exit();
		}
		if($order_info['is_refund'] == 1){
			echo header("Content-type:text/plain;charset=utf-8");
			echo "订单已退款";exit();
		}
		if($order_info['data_flag'] == 0){
			echo header("Content-type:text/plain;charset=utf-8");
			echo "订单已删除";exit();
		}
		if($order_info['resume_point_id']){
			$resume_point_info = $service_part_person_partresumepoint->getResumePointById($order_info['resume_point_id']);
			if(empty($resume_point_info)){
				echo "兼职比信息有误";exit();
			}
			//获取简历点套餐信息
			$setmeal_info = $service_common_part_resumesetmeal->getSetmealByCode($resume_point_info['setmeal_id']);
			if(empty($setmeal_info)){
				echo header("Content-type:text/plain;charset=utf-8");
				echo "兼职币套餐信息有误";exit();
			}
			$this->_aParams['setmeal_info'] = $setmeal_info;
			$this->_aParams['resume_point_info'] = $resume_point_info;
		}
		if($order_info['job_top_id']){
			$job_top_info = $service_part_job_partjobtop->getPartTopById($order_info['job_top_id']);
			if(empty($job_top_info)){
				echo header("Content-type:text/plain;charset=utf-8");
				echo "职位置顶信息有误";exit();
			}

			$job_top_type = $service_common_part_jobtoptype->getAll();
			$this->_aParams['job_top_type'] = $job_top_type;
			$this->_aParams['job_top_info'] = $job_top_info;
		}
		if($order_info['buy_job_num']>0){
            $service_order = new base_service_part_order_partorder();
            $PartMoneyToRmb = $service_order->getPriceConfig('PartMoneyToRmb');
            $PartJobPrice = $service_order->getPriceConfig('PartJobPrice');
            $FreePartJobNum = $service_order->getPriceConfig('FreePartJobNum');

            $this->_aParams['PartMoneyToRmb'] = $PartMoneyToRmb;
            $this->_aParams['partJobPrice'] = $PartJobPrice;
        }

		$pay_type = $service_common_part_partorderpaytype->getAllPayType();
		$this->_aParams['is_refresh'] = $is_refresh;
		$this->_aParams['pay_type'] = $pay_type;
		$this->_aParams['order_info'] = $order_info;
		$this->_aParams['code_url'] = $code_url;

		return $this->render("./part/pay/orderpay.html",$this->_aParams);
	}


	/**
	 * 支付扫码页面 轮询检查订单结果
	 */
	function pageScanCodePaySearchResult($inPath) {
		echo header("Content-type:text/json;charset=utf-8");
		$path = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$order_no = base_lib_BaseUtils::getStr($path['order_no'], 'string','');
		$from = base_lib_BaseUtils::getStr($path['from'], 'string','');

		//记录轮询查询次数
		session_start();
		!isset($_SESSION["partScanCodePaySearchResult:{$order_no}"])
		and $_SESSION["partScanCodePaySearchResult:{$order_no}"] = 0;
		$_SESSION["partScanCodePaySearchResult:{$order_no}"]++;


		$service_part_order_partorder = new base_service_part_order_partorder();
		$service_part_order_partorderlog = new base_service_part_order_partorderlog();
		$order_info = $service_part_order_partorder->getOrderInfoByOrderNo($order_no);

		if(empty($order_info)){
			return $this->jsonMsg(false,"订单信息不存在");
		}

		if (!$order_info['data_flag']) {
			return $this->jsonMsg(false,"订单已无效");
		}
		if ($order_info['is_refund']) {
			return $this->jsonMsg(false,"订单已退款");
		}
		$return_msg = "";
		if($order_info['job_top_id']){
			$service_part_job_partjobtop = new base_service_part_job_partjobtop();
			$top_info = $service_part_job_partjobtop->getPartTopById($order_info['job_top_id']);
			$return_msg = "置顶成功，该职位置顶天数为{$top_info['top_day']}天";
		}
		if($order_info['resume_point_id']){
			$service_part_company_resumepoint_pointin = new base_service_part_company_resumepoint_pointin();
			$resume_point = $service_part_company_resumepoint_pointin->getResumePointId($order_info['resume_point_id']);
			$return_msg = "订单支付完成,成功购买{$resume_point['resume_point_num']}兼职币";
		}

		if ($order_info['order_status'] == 1 && $order_info['is_pay'] == 1 && $order_info['order_log_id']) {

			$order_log_info = $service_part_order_partorderlog->GetModelOrderPayLogById($order_info['order_log_id']);
			if (!$order_log_info) {
				return $this->jsonMsg(true,"订单未支付",array ('is_pay' => 0));
			}
			if ($order_log_info['order_status'] != 1 || $order_log_info['is_pay'] != 1) {
				return $this->jsonMsg(true,"订单未支付",array ('is_pay' => 0));
			}

			return $this->jsonMsg(true,$return_msg,array ('is_pay' => 1));
		} else {
			if ($_SESSION["partScanCodePaySearchResult:{$order_no}"] > 8 || $from == 'overpay') {
				//订单5次回调后无回调 尝试调用订单查询接口
				switch ($order_info['pay_type']) {
					case 2:
						//微信支付
						require_once(PLUGINS_DIR . '/pay/WxPay/WxPay.Notify.php');
						require_once(PLUGINS_DIR . '/pay/WxPay/JzWxPay.Config.php');
						$input = new WxPayOrderQuery();
						$input->SetOut_trade_no($order_no);
						$config = new JzWxPayConfig();
						$result = WxPayApi::orderQuery($config, $input);
						if (array_key_exists("return_code", $result)
							&& array_key_exists("result_code", $result)
							&& array_key_exists("trade_state", $result)
							&& $result["return_code"] == "SUCCESS"
							&& $result["result_code"] == "SUCCESS"
							&& $result["trade_state"] == "SUCCESS"
						) {
							$transaction_id = $result['transaction_id'];
							$order_no = $result['out_trade_no'];
							$total_fee = abs(floatval($result['cash_fee'] / 100));
							$service_part_order_partorder = new base_service_part_order_partorder();
							$rse_order = $service_part_order_partorder->orderSuccessPay($result, $transaction_id, $order_no, $total_fee, $msg);
							if ($rse_order) {
								return $this->jsonMsg(true,$return_msg,array ('is_pay' => 1));
							}
							SlightPHP::log('微信支付回调记录日志_更新失败1:' . print_r($msg, true) . print_r($result, true));
							return $this->jsonMsg(true,"订单支付成功,更新订单信息失败!",array ('is_pay' => 0));
						}
						return $this->jsonMsg(true,"订单未支付",array ('is_pay' => 0,'result'=>$result));
						break;
					case 3:
						//支付宝
						$notify_url = base_lib_Constant::MOBILE_URL . "/partinterface/AlipayChargerRespond";
						require_once(PLUGINS_DIR . '/pay/AliPay/f2fpay/service/AlipayTradeService.php');
						require PLUGINS_DIR . '/pay/AliPay/f2fpay/config/config.php';
						$config['notify_url'] = $notify_url;
						$qrPay = new AlipayTradeService($config);
						$queryContentBuilder = new AlipayTradeQueryContentBuilder();
						$queryContentBuilder->setOutTradeNo($order_no);
						$queryContentBuilder->setAppAuthToken($queryContentBuilder->getAppAuthToken());
						$queryResponse = $qrPay->queryTradeResult($queryContentBuilder);

						if ($queryResponse->getTradeStatus() == 'SUCCESS') {
							$re_data = (array)$queryResponse->getResponse();
							$transaction_id = $re_data['trade_no'];
							$order_no = $re_data['out_trade_no'];
							$total_fee = abs(floatval($re_data['total_amount']));
							$service_part_order_partorder = new base_service_part_order_partorder();
							$rse_order = $service_part_order_partorder->orderSuccessPay($re_data, $transaction_id, $order_no, $total_fee, $msg);
							if ($rse_order) {
								return $this->jsonMsg(true,$return_msg,array ('is_pay' => 1));
							}
							SlightPHP::log('支付宝支付回调记录日志_更新失败:' . print_r($msg, true) . print_r($re_data, true));
							return $this->jsonMsg(true,"订单支付成功,更新订单信息失败!",array ('is_pay' => 0));
						} else {
							return $this->jsonMsg(true,"订单未支付", array ('is_pay' => 0, 'queryResponse' => $queryResponse->getResponse()));
						}
						break;
					default:
						return $this->jsonMsg(true,"支付类型错误",array ('is_pay' => 0));
						break;
				}
			}

			return $this->jsonMsg(true,"订单未支付",array ('is_pay' => 0));
		}

	}


	/**
	 * 生成支付二维码
	 */
	function pagePaySQrcode($inPath) {
		$path = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$in_data['code_url'] = base_lib_BaseUtils::getStr($path['code_url'], 'string');

		ob_end_clean();
		header('Content-Type:image/png');
		SQrcode::png1($in_data['code_url'],6,1);
	}

}