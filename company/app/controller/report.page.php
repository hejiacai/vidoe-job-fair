<?php

/**
 * @ClassName controller_report
 * @Desc      举报联系方式
 * @author    jiangchenglin@huibo.com
 * @date      2013-10-9 上午09:33:19
 */
class controller_report extends components_cbasepage {
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * 举报入口
	 * @param $inPath
	 */
	function pageIndex($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_id = base_lib_BaseUtils::getStr($path_data['resume_id'], 'int', 0);
		
		if ($resume_id == 0) {
			echo '获取参数失败';
			
			return;
		}
		
		$company_service = new base_service_company_company();
		$company = $company_service->getCompany($this->_userid, 1, 'end_time,recruit_type');
        if($company["recruit_type"] == 1){ //直招的企业才有没有联系到求职者
            $this->_aParams["show_notcontact"] = true;
        }
		
		$resume_service = new base_service_person_resume_resume();
		$person_id = $resume_service->getResume($resume_id, "person_id")['person_id'];
		
		$service_apply = new base_service_company_resume_apply();
		$apply = $service_apply->isApply($this->_userid, $resume_id);
		$this->_aParams['isApplyed'] = false;
		if (!empty($apply)) {
			$this->_aParams['isApplyed'] = true;
		}
		
		//检查是否需要返点
		//检查是否有7天之内是否有下载记录
		$service_download = new base_service_company_resume_download();
		$download = $service_download->getCompanyDownload($this->_userid, $resume_id, 'download_id,type,consume');
		if (!empty($download) && !base_lib_BaseUtils::nullOrEmpty($download)) {
			$this->_aParams['refund_type'] = $download['type'];
			$this->_aParams['is_should_refund'] = "1";
		}
		$person_service = new base_service_person_person();
		$mobile_phone = $person_service->getPerson($person_id, "mobile_phone")['mobile_phone'];
		
		$this->_aParams['resume_id'] = $resume_id;
		$this->_aParams['person_id'] = $person_id;
		$this->_aParams['phone'] = $mobile_phone;
		
		$report_type = new base_service_common_reporttype();
		$this->_aParams['empty_number']     = $report_type->emptynumber;
		$this->_aParams['stop']             = $report_type->stop;
		$this->_aParams['no_self']          = $report_type->noself;
		$this->_aParams['advertisement']    = $report_type->advertisement;
		$this->_aParams['meaningless']      = $report_type->meaningless;
		$this->_aParams['found_job']        = $report_type->found_job;
        $this->_aParams['notcontact']       = $report_type->notcontact; //联系不上求职者
		$this->_aParams['downloaded'] = $download;
        

		//获取恶意举报
		$report = new base_service_report_report();
		$_result = $report->isOverTwenty($this->_userid);
		$this->_aParams['isOverTwenty'] = $_result;
		
        //判断是否来源是聊一聊
        $report_from = base_lib_BaseUtils::getStr($path_data["report_from"],"string",""); //chat:表示是从聊一聊了中来的
        $this->_aParams["report_from"]      = $report_from;
        $this->_aParams["advertharass"]     = $report_type->advertharass; //广告骚扰
        $this->_aParams["abuse"]            = $report_type->abuse;         //言语辱骂
        //上传图片
        $ser_upload = new base_service_upload_upload();
        $up_options = array ('file_name' => 'reportFile[]', 'fileVal' => 'Filedata', 'auto' => true, 'defaults_files' => $defaults_files);
        $this->_aParams['up_img_html'] = $ser_upload->GetUploadHtmlDom($up_options, $this->_userid, 'up_style2', '', 'companyReport', '/company/company.xml');
        
        //公司职位
        //获取在招职位
        $company_resources  = base_service_company_resources_resources::getInstance($this->_userid);
        $chat_account_ids   = $company_resources->all_accounts;
        $service_job        = new base_service_company_job_job;
        $job_status         = new base_service_common_jobstatus();
        $job_list           = $service_job->getJobList($chat_account_ids, null, $job_status->use, 'job_id,station,account_id');
        $my_job_list        = [];
        $other_job_list     = [];
        if(!empty($job_list)){
            foreach($job_list as $value){
                if($value["account_id"] == $account_id){
                    $my_job_list[] = ["job_id"=>$value["job_id"],"station" => $value["station"]];
                }else{
                    $other_job_list[] = ["job_id"=>$value["job_id"],"station" => $value["station"]];
                }
            }
            $job_list = array_merge($my_job_list,$other_job_list);
        }
        $this->_aParams["job_list"] = $job_list;
		return $this->render('./resume/report.html', $this->_aParams);
	}
	
	/**
	 * 举报操作
	 * @param string $inPath
	 */
	function pageReportDo($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_id = base_lib_BaseUtils::getStr($path_data['resume_id'], 'int', 0);
		$person_id = base_lib_BaseUtils::getStr($path_data['person_id'], 'int', 0);
		$report_type = base_lib_BaseUtils::getStr($path_data['report_type'], 'string', '0');
		$complaint_tel = base_lib_BaseUtils::getStr($path_data['complaint_tel'], 'string', '');
		$complaint_desc = base_lib_BaseUtils::getStr($path_data['rpDesc'], 'string', '');
        $account_id = base_lib_BaseUtils::getCookie('accountid');
		if ($resume_id == 0 || $person_id == 0 || $report_type == '0') {
			echo json_encode(array ('error' => '获取参数失败'));
			
			return;
		}
		
		$com_report_type = new base_service_common_reporttype();
		$report_cause = $com_report_type->getReportType();
		if (!in_array($report_type, array_keys($report_cause))) {
			echo json_encode(array ('error' => '举报类型错误!'));
			
			return;
		}
		
		$service_oddreport = new base_service_company_oddreport();
		$report_count = $service_oddreport->getReportCountInSevenDay($this->_userid, $person_id, $resume_id);
		if (!base_lib_BaseUtils::nullOrEmpty($report_count) && $report_count >= 1) {
			echo json_encode(array ('error' => '您已经举报过该简历了，我们会仔细核实'));
			
			return;
		}
		
		$report = new base_service_report_report();
		$_result = $report->isOverTwenty($this->_userid);
		if ($_result['status']) {
			echo json_encode(array ('error' => $_result['msg']));
			
			return;
		}
		if($report_type == 12){ //联系不上求职者 单独处理 此举报不计入总的举报次数
            $job_id = base_lib_BaseUtils::getStr($path_data["hiddselectjobid"],"int",0);
            $this->_reportNotContact($person_id,$resume_id,$job_id);
        }
        
		//添加举报
		$item['resume_id'] = $resume_id;
		$item['person_id'] = $person_id;
		$item['exceptional_tel'] = $complaint_tel;
		$item['exceptional_type'] = $report_type;
		$item['exceptional_content'] = $complaint_desc;
		$item['company_id'] = $this->_userid;
		
		//检查是否需要返点
		//检查是否有7天之内是否有下载记录
		$service_download = new base_service_company_resume_download();
		$download = $service_download->getCompanyDownload($this->_userid, $resume_id, 'download_id,point,type');
		if ($download !== false && !base_lib_BaseUtils::nullOrEmpty($download)) {
			if ($download['type'] == 2 || $download['type'] == 3) {
				$item['refund_download_id'] = $download["download_id"];
			}
			else {
				$item['is_should_refund'] = "1";
				$item['refund_download_id'] = $download["download_id"];
				$item['refund_point'] = $download["point"];
			}
		}
		$result = $service_oddreport->addReport($item);
        if($report_type == 12){ //如果是联系不上求职者 则举报在此
            echo json_encode(array ('msg' => '举报成功，我们会仔细核实并处理，谢谢！'));exit;
        };
        
		$ser_person = new base_service_person_person();
		
		if ($result > 0) {
			//已找到工作 单独处理
			if ($report_type == 9) {
				$ser_resumeFeedbackReport = new base_service_resume_resumeFeedbackReport();
				$create_time_ild = date('Y-m-d H:i:s', strtotime(' -3 day'));
//				$old_resumeFeedbackReport_data = $ser_resumeFeedbackReport->GetResumeFeedbackReportByPersonId($person_id, $create_time_ild)->items;
				//是否 有已处理的
				$old_FeedbackReportFeedback_data = $ser_resumeFeedbackReport->GetResumeFeedbackReportFeedbackByPersonId($person_id, $create_time_ild);
				$add_report_data = array (
					'resume_id'    => $resume_id,
					'person_id'    => $person_id,
					'company_id'   => $this->_userid,
					'download_id'  => @$item['refund_download_id'] ?: 0,
					'is_download'  => $item['refund_download_id'] ? 1 : 0,
					'create_time'  => $this->now(),
					'report_cause' => $report_type,
				);
				$re = $ser_resumeFeedbackReport->AddResumeFeedbackReport($add_report_data);
				if ($re) {
					//反馈时间 - 上次反馈时间≤3天 不再次发送短信
					if (!$old_FeedbackReportFeedback_data) {
						$person_data = $ser_person->getPerson($person_id, 'person_id,user_name,mobile_phone,mobile_phone_is_validation');
						if ($person_data && $person_data['mobile_phone'] && base_lib_BaseUtils::IsMobile($person_data['mobile_phone'])) {
							$id_key = $ser_resumeFeedbackReport->GetCrypt($re);
							$url = base_lib_Constant::MOBILE_URL . "/fb-$id_key";
							$xml = SXML::load('../config/company/company.xml');
							$ser_shorurl = new base_service_common_shorturl();
							$key = $ser_shorurl->shorturlAdd(['type' => 1, 'url' => $url, 'create_time' => $this->now()]);
							$ShortUrlDomain = @parse_url($xml->ShortUrlDomain)['host'] . '/' ?: 'huibo.cn/';
							$ShortUrlDomain .= $key;
							//您好，有企业反馈您已找到工作。为避免被打扰，请点击huibo.cn/xxxxxx设置工作状态；如正在求职，请忽略谢谢
							$sms_content = "{$person_data['user_name']}，有企业反馈您已找到工作。为避免被打扰，请点击 {$ShortUrlDomain} 设置工作状态；如正在求职，请忽略谢谢";
							//发送短信
							base_lib_SMS::send($person_data['mobile_phone'], $sms_content, 2, 0);
						}
					}
					else {
						//举报3天内有反馈 系统自动处理
						$ser_resumeFeedbackReport->UpdateResumeFeedbackReportById($re, array ('feedback_type' => 3));
					}
					
					echo json_encode(array ('msg' => '举报成功，我们会仔细核实并处理，谢谢！'));
				}
			}else {
				$service_message = new base_service_message_messageperson();
				$service_person = new base_service_person_person();
				$service_company = new base_service_company_company();
				$service_reportype = new base_service_common_reporttype();
				$service_messagetype = new base_service_message_messagepersontype();
				
				$person = $service_person->getPerson($person_id, 'user_name');
				$company = $service_company->getCompany($item['company_id'], '', 'company_name,email,link_tel');
				$complaint_content = $service_reportype->getName($report_type);
				
				$item_message['person_id'] = $person_id;
				$item_message['subject'] = "企业反映您的手机号码【{$complaint_content}】";
				$item_message['content'] = "亲爱的{$person['user_name']}:\r\n     您好!\r\n    {$company['company_name']}曾试图邀请您面试,但您所填写的手机号码无法联系到您（原因:{$complaint_content}）。请尽快核实您所填写的手机号码是否正确，以便您能即时收到企业的面试邀请。\r\n    同时，为避免错过面试机会，您最好主动联系{$company['company_info']}。\r\n    预祝您求职成功。";
				$item_message['sender'] = 'huibo.com';
				$item_message['type'] = $service_messagetype->accusereport;
				//$service_message->addPersonMessage($item_message);
				//添加到工单系统
				$reportData = array ();
				$reportData['order_type'] = 6;                //来源类型
				$reportData['obj_id'] = $person_id;            //举报对象id 求职者
				$reportData['obj_name'] = $person['user_name'];//举报对象名称  求职者名称
				$reportData['obj_tel'] = $complaint_tel;        //被举报电话
				$reportData['title'] = $complaint_content;
				$reportData['content'] = $complaint_desc;    //发起方描述
				
				$reportData['source_obj_id'] = $this->_userid;        //发起方id
				$reportData['source_obj_name'] = $this->_username;        //发起方名称
				$reportData['source_obj_type'] = 2;        //发起方类型 企业
				
				$reportData['tel'] = $company['link_tel'];
				$reportData['email'] = $company['email'];
				$reportData['product_type'] = base_lib_BaseUtils::GetBrowser();
                if($report_type == 7){
                    //广告
                    $file_img = array ();
                    //移动图片
                    $ser_upload = new base_service_upload_upload();
                    foreach ($path_data['reportFile'] as $picurl) {
                        $re_move = $ser_upload->MoveFileUploadFileSuccess($picurl, '', 'companyReport', '/company/company.xml');
                        if (!$re_move['status']) {
                            echo json_encode(array ('error' => '图片上传失败！'));exit;
                        }
                        $file_img[] = $re_move;
                    }
                    $file_img = base_lib_BaseUtils::getProperty($file_img, 'newname_path');
                    if(!empty($file_img)){
                        $reportData["annex"] = implode(',', $file_img);
                    }
                }
				$reportData['resume_id']        = $resume_id;
                $reportData["other_params"]     = $account_id;
				$report->addReport($reportData);
                if($report_type == $service_reportype->advertharass || $report_type == $service_reportype->abuse){ //如果是聊一聊过来的
                    //是否加入黑名单
                    $addchatblack = base_lib_BaseUtils::getStr($path_data['addchatblack'], 'string', '');
                    if($addchatblack){
                        //加入黑名单
                        $service_swychat        = new SWYChat('APP_COM');
                        $service_swychat_person = new SWYChat('APP');
                        $accId          = $service_swychat->getAccId($account_id);
                        $personAccId    = $service_swychat_person->getAccId($person_id);
                        $service_swychat->specializeFriend($accId,$personAccId, 1, 1);
                    }
                }
                
				echo json_encode(array ('msg' => '举报成功，我们会仔细核实并处理，谢谢！'));
			}
		}
		else {
			echo json_encode(array ('error' => '举报失败，请稍后再试！'));
		}
		
		return;
	}
	
	/**
	 * @Desc 检查该企业7天内是否已经举报过该简历
	 * @return type_name
	 */
	function pageCheckReported($inPath) {
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$resume_id = base_lib_BaseUtils::getStr($path_data['resume_id'], 'int', 0);
		$person_id = base_lib_BaseUtils::getStr($path_data['person_id'], 'int', 0);
		
		//define("DEBUG",1);
		if ($resume_id == 0 || $person_id == 0) {
			echo json_encode(array ('error' => '获取参数失败'));
			
			return;
		}
		
		//判断举报次数
		$service_oddreport = new base_service_company_oddreport();
		$report_count = $service_oddreport->getReportCountInSevenDay($this->_userid, $person_id, $resume_id);
		if (!base_lib_BaseUtils::nullOrEmpty($report_count) && $report_count >= 1) {
			echo json_encode(array ('error' => '您已经举报过该简历了，我们会仔细核实'));
			
			return;
		}
		
		//已找到工作举报
		
		
		echo json_encode(array ('success' => 'true'));
		
		return;
	}
    
    /**
	 * 添加文件    zhouwenjun 2018/9/7 15:32
	 */
	public function pagePicture($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$up_type = base_lib_BaseUtils::getStr($pathdata['up_type'], 'string');
		$file = $_FILES['Filedata'];
		
		$verify_data = base_lib_BaseUtils::getStr($pathdata['verify_data'], 'array');
		$serv_askforleave = new base_service_upload_upload();
		$in_data['is_temp'] = true;
		$arr = $serv_askforleave->UploadFile($file, $verify_data, $up_type, 'companyReport', '/company/company.xml', $in_data);
		
		if ($arr['status'] == false) {
			$this->ajax_data_json(ERROR, $arr['msg'], $arr);
		}
		
		$this->ajax_data_json(SUCCESS, "上传成功", $arr);
	}
    
    /**
	 * 删除临时照片    zhouwenjun 2018/9/7 15:32
	 */
	public function pageDelTempFile($inPath) {
		$pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
		$up_type = base_lib_BaseUtils::getStr($pathdata['up_type'], 'string');
		$verify_data = base_lib_BaseUtils::getStr($pathdata['verify_data'], 'array');
		$file = $_REQUEST['file_path'];
		$serv_askforleave = new base_service_upload_upload();
		$arr = $serv_askforleave->DelFile($file, $verify_data, $up_type, 'companyReport', '/company/company.xml');
		if (@$arr['status'] == false) {
			$this->ajax_data_json(ERROR, $arr['msg'], $arr);
		}
		
		$this->ajax_data_json(SUCCESS, "删除成功", $arr);
	}
    
    private function _reportNotContact($person_id,$resume_id,$job_id){
        if(empty($person_id) || empty($resume_id)){
            echo json_encode(array ('error' => '举报失败，参数错误'));exit;
        }
        if(empty($job_id)){
            echo json_encode(array ('error' => '举报失败，请选择想要沟通的职位'));exit;
        }
        //判断简历下载是否是24小时内
        $service_download = new base_service_company_resume_download();
		$download = $service_download->getCompanyDownload($this->_userid, $resume_id, 'download_id,type,consume,down_time');
		if (empty($download)) {
            echo json_encode(array ('error' => '举报失败，您还没有下载该简历'));exit;
		}
        $last_time = date("Y-m-d H:i:s",strtotime("-24 hours"));
        if($download["down_time"] >  $last_time){
            echo json_encode(array ('error' => '下载简历超过24小时后可举报，您可发送短信、加微信、再次拨打电话尝试联系求职者',"type"=>1));exit;
        }
        //获取本月举报简历数
        $service_report_notcontact = new base_service_report_notcontact();
        $report_count = $service_report_notcontact->getReportCount($this->_userid);
        if($report_count >= 50){
            echo json_encode(array ('error' => '您当月举报联系不上求职者不属实次数已超过50次，下个月才可举报',"type"=>1));exit;
        }
        $account_id = base_lib_BaseUtils::getCookie('accountid');
        $result     = $service_report_notcontact->addReport($this->_userid,$account_id,$person_id,$resume_id,$job_id,$download["download_id"]);
        if($result  === false){
            echo json_encode(array ('error' => '举报失败'));exit;
        }
    }
	
}

?>