<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/28
 * Time: 15:01
 */

class controller_partreport extends components_cbasepage
{


    function __construct()
    {
        parent::__construct(true, 'part');

    }

    public function pageIndex($inPath){
        $pathdata  = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_id = base_lib_BaseUtils::getStr($pathdata['resume_id'], 'int', 0);

        if(empty($resume_id)){
            echo '获取参数失败';
            return;
        }
        $common_partreporttype = new base_service_common_part_partreporttype();

        $resume_service = new base_service_part_person_resume();
        $person_id = $resume_service->getResumeById(['resume_id'=>$resume_id], "person_id")['person_id'];
        $person_service = new base_service_person_person();
        $_data['phone']     = $person_service->getPerson($person_id, "mobile_phone")['mobile_phone'];
        $_data['type']      = $common_partreporttype->getReportType();
        $_data['person_id'] = $person_id;
        $_data['resume_id'] = $resume_id;

        return $this->render('./part/partreport.html', $_data);
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
            echo json_encode(array ('status'=>false,'error' => '获取参数失败'));

            return;
        }

        $service_pointout = new base_service_part_company_resumepoint_pointout();
        $hasGetLink = $service_pointout->_hasWactchLinkWay($this->_userid,$person_id);

        if(!$hasGetLink){
            echo json_encode(array ('status'=>false,'error' => '请先与求职者联系后才能申诉'));
            return;
        }
        //判断是否举报
        $service_resumereport = new base_service_part_resumereport();
        $hasReported = $service_resumereport->hasReported($this->_userid,$person_id);
        if ($hasReported) {
            echo json_encode(array ('status'=>false,'error' => '您已经举报过该简历了，我们会仔细核实'));
            return;
        }

        echo json_encode(array ('status' => true));

        return;
    }

    /**
     * 举报操作
     * @param string $inPath
     */
    function pageReportDo($inPath) {
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_id = base_lib_BaseUtils::getStr($path_data['resume_id'], 'string', 0);
        $person_id = base_lib_BaseUtils::getStr($path_data['person_id'], 'string', 0);
        $complaint_tel = base_lib_BaseUtils::getStr($path_data['complaint_tel'], 'string', '');
        $report_type = base_lib_BaseUtils::getStr($path_data['report_type'], 'string', '0');
        $complaint_desc = base_lib_BaseUtils::getStr($path_data['rpDesc'], 'string', '');
        if ($resume_id == 0 || $person_id == 0 || $report_type == '0') {
            echo json_encode(array ('status'=>false,'error' => '获取参数失败'));

            return;
        }

        $com_report_type = new base_service_common_part_partreporttype();
        $report_cause = $com_report_type->getReportType();
        if (!in_array($report_type, array_keys($report_cause))) {
            echo json_encode(array ('status'=>false,'error' => '举报类型错误!'));

            return;
        }

        //判断举报次数
        $service_resumereport = new base_service_part_resumereport();
        if(!$this->_userid){
            echo json_encode(array ('status'=>false,'error' => '登录信息错误'));
            return;
        }
        $hasReported = $service_resumereport->hasReported($this->_userid,$person_id);
        if ($hasReported) {
            echo json_encode(array ('status'=>false,'error' => '您已经举报过该简历了，我们会仔细核实'));
            return;
        }

        $report = new base_service_report_report();
        $_result = $report->isOverTwenty($this->_userid);
        if ($_result['status']) {
            echo json_encode(array ('status'=>false,'error' => $_result['msg']));

            return;
        }

        //添加举报
        $item['is_effect'] = 1;
        $item['resume_id'] = $resume_id;
        $item['person_id'] = $person_id;
        $item['create_time'] = date("Y-m-d H:i:s");
        $item['company_id'] = $this->_userid;
        $item['report_type'] = $report_type;
        //添加举报记录
        $ser_person = new base_service_person_person();
        $service_pointout = new base_service_part_company_resumepoint_pointout();
        $download_id = $service_pointout->getDataByPerson($person_id,$this->_userid,'id')['id'];  //从消费记录获取下载id
        $item['down_id'] = $download_id;
        $result = $service_resumereport->insert($item);
        if ($result > 0) {
            //已找到兼职 单独处理
            if ($report_type == 6) {
                $create_time_ild = date('Y-m-d H:i:s', strtotime(' -3 day'));
                $ser_resumeFeedbackReport = new base_service_resume_resumeFeedbackReport();
//				$old_resumeFeedbackReport_data = $ser_resumeFeedbackReport->GetResumeFeedbackReportByPersonId($person_id, $create_time_ild)->items;
                //是否 有已处理的
                $old_FeedbackReportFeedback_data = $ser_resumeFeedbackReport->GetResumeFeedbackReportFeedbackByPersonId($person_id, $create_time_ild,'id',2);
                $add_report_data = array (
                    'resume_id'    => $resume_id,
                    'person_id'    => $person_id,
                    'company_id'   => $this->_userid,
                    'download_id'  => $download_id,
                    'is_download'  => $download_id ? 1 : 0,
                    'create_time'  => $this->now(),
                    'report_cause' => $report_type,
                    'data_type'    => 2,  //1-全职 2-兼职
                );
                $re = $ser_resumeFeedbackReport->AddResumeFeedbackReport($add_report_data);
                if ($re) {
                    //反馈时间 - 上次反馈时间≤3天 不再次发送短信
                    if (!$old_FeedbackReportFeedback_data) {
                        $person_data = $ser_person->getPerson($person_id, 'person_id,user_name,mobile_phone,mobile_phone_is_validation');
                        if ($person_data && $person_data['mobile_phone'] && base_lib_BaseUtils::IsMobile($person_data['mobile_phone'])) {
                            $id_key = $ser_resumeFeedbackReport->GetCrypt($re);
                            $url = base_lib_Constant::MOBILE_URL . "/partreport/index/id-$id_key";
                            $xml = SXML::load('../config/company/company.xml');
                            $ser_shorurl = new base_service_common_shorturl();
                            $key = $ser_shorurl->shorturlAdd(['type' => 1, 'url' => $url, 'create_time' => $this->now()]);
                            $ShortUrlDomain = @parse_url($xml->ShortUrlDomain)['host'] . '/' ?: 'huibo.cn/';
                            $ShortUrlDomain .= $key;
                            //您好，有企业反馈您已找到工作。为避免被打扰，请点击huibo.cn/xxxxxx设置工作状态；如正在求职，请忽略谢谢
                            $sms_content = "{$person_data['user_name']}，有企业反馈您已找到兼职。为避免被打扰，请点击 {$ShortUrlDomain} 设置求职状态；如正在求职，请忽略谢谢";
                            //发送短信
                            base_lib_SMS::send($person_data['mobile_phone'], $sms_content, 2, 0);
                        }
                    }
                    else {
                        //举报3天内有反馈 系统自动处理
                        $ser_resumeFeedbackReport->UpdateResumeFeedbackReportById($re, array ('feedback_type' => 3));
                    }

                    echo json_encode(array ('status'=>true,'msg' => '举报成功，我们会仔细核实并处理，谢谢！'));
                }
            }
            else {
//                $service_message = new base_service_message_messageperson();
                $service_person = new base_service_person_person();
                $service_company = new base_service_company_company();
                $service_partreportype = new base_service_common_part_partreporttype();
                //$service_messagetype = new base_service_message_messagepersontype();

                $person = $service_person->getPerson($person_id, 'user_name');
                $company = $service_company->getCompany($item['company_id'], '', 'company_name,email,link_tel');
                $complaint_content = $service_partreportype->getName($report_type);

//                $item_message['person_id'] = $person_id;
//                $item_message['subject'] = "企业反映您的手机号码【{$complaint_content}】";
//                $item_message['content'] = "亲爱的{$person['user_name']}:\r\n     您好!\r\n    {$company['company_name']}曾试图邀请您面试,但您所填写的手机号码无法联系到您（原因:{$complaint_content}）。请尽快核实您所填写的手机号码是否正确，以便您能即时收到企业的面试邀请。\r\n    同时，为避免错过面试机会，您最好主动联系{$company['company_info']}。\r\n    预祝您求职成功。";
//                $item_message['sender'] = 'huibo.com';
//                $item_message['type'] = $service_messagetype->accusereport;
                //$service_message->addPersonMessage($item_message);
                //添加到工单系统
                $reportData = array ();
                $reportData['order_type'] = 18;                //来源类型
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

                $reportData['resume_id'] = $resume_id;
                $report->addReport($reportData);

                echo json_encode(array ('status'=>true,'msg' => '举报成功，我们会仔细核实并处理，谢谢！'));
            }
        }
        else {
            echo json_encode(array ('status'=>false,'error' => '举报失败，请稍后再试！'));
        }

        return;
    }
}
