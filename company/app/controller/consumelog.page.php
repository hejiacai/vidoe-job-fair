<?php
/**
 * 其他消费记录
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/29
 * Time: 11:32
 */
class controller_consumelog extends components_cbasepage
{
    function __construct()
    {
        parent::__construct();
    }

    private $index_tile = array(
        'user_id'   => '用户名',
        'user_name' => '姓名',
        'station'   => '职位/身份',
        'resume'    => '简历点',
        'spread'    => '推广金',
        'refresh'    => '刷新点',
        'bouit'     => '精品点',
        'chat'      => '聊一聊',
        'sms'       => '短信数',
        'job_num'   => '职位数',
        'video_num_use'   => '视频',

    );


    public function pageHistory($inPath){
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $log_type = base_lib_BaseUtils::getStr($path_data['log_type'],"string","");
        $cur_page  = base_lib_BaseUtils::getStr($path_data['page'],'int',1);
        $page_size = base_lib_Constant::PAGE_SIZE;

        $this->_aParams['log_type'] = $log_type;

        $service_company_service_serviceConsumeLog = new base_service_company_service_serviceConsumeLog();

        $consumeLogs = $service_company_service_serviceConsumeLog->getConsumeLogs($this->_userid,null,$cur_page,$page_size,$log_type);
        $datalist = $consumeLogs->items;

        $accountids = base_lib_BaseUtils::getProperty($consumeLogs->items,'account_id'); //操作人ids

        $base_service_company_account = new base_service_company_account();
        $accounts = $base_service_company_account->getAccounts(array_unique($accountids),'account_id,user_id,user_name');
        $accountlist = base_lib_BaseUtils::array_key_assoc($accounts,'account_id');
        foreach ($datalist as $key=>$value){
            $datalist[$key]['user_name'] = $accountlist[$value['account_id']]['user_id'].'-'.$accountlist[$value['account_id']]['user_name'];
            if ($value['log_type'] == 1){
                $datalist[$key]['log_type'] = '简历点';
            }
            if ($value['log_type'] == 2){
                $datalist[$key]['log_type'] = '刷新点';
            }
            if ($value['log_type'] == 3){
                $datalist[$key]['log_type'] = '精品点';
            }
            if ($value['log_type'] == 4){
                $datalist[$key]['log_type'] = '聊一聊';
            }
            if ($value['log_type'] == 6){
                $datalist[$key]['log_type'] = '短信';
            }
            if ($value['log_type'] == 7){
                $datalist[$key]['log_type'] = '视频面试';
                $datalist[$key]['point']=self::format_time($datalist[$key]['point']);
            }
        }
        $this->_aParams['title'] = '其他消费记录 我的账户-汇博人才网';
        $total_count = $consumeLogs->totalSize;
        //分页
        $pager = $this->pageBar($total_count, $page_size, $cur_page, $inPath);
        $this->_aParams['pager'] = $pager;
        $this->_aParams['items'] = $datalist;

        return $this->render('service/historyconsumelog.html', $this->_aParams);
    }


    public function pageConsumeStatic($inPath)
    {
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $s_time    = base_lib_BaseUtils::getStr($path_data['s_time'], "string", date('Y-m-d', strtotime('-7 days')));
        $e_time    = base_lib_BaseUtils::getStr($path_data['e_time'], "string", date('Y-m-d', strtotime('-1 days')));
        $action    = base_lib_BaseUtils::getStr($path_data['action'], "string", '');
        $day_type  = base_lib_BaseUtils::getStr($path_data['day_type'], "int", 0);
        if($s_time == date('Y-m-d', strtotime('-7 days')) && $e_time == date('Y-m-d', strtotime('-1 days'))){
            $day_type = 7;
        }

        if ($s_time < date('Y-m-d', strtotime('-365 days'))) {
            $s_time = date('Y-m-d', strtotime('-365 days'));
        }
        if ($s_time >= date('Y-m-d')) {
            $s_time = date('Y-m-d', strtotime('-7 days'));
        }

        if ($e_time >= date('Y-m-d')) {
            $e_time = date('Y-m-d', strtotime('-1 days'));
        }
        if ($e_time <= date('Y-m-d', strtotime('-365 days'))) {
            $e_time = date('Y-m-d', strtotime('-364 days'));
        }

        if($s_time> $e_time){
            $e_time = date('Y-m-d', strtotime('-1 days'));
        }

        $account_id            = base_lib_BaseUtils::getCookie('accountid');
        $company_resources     = base_service_company_resources_resources::getInstance($this->_userid, true, $account_id);
        $pricing_resource_data = $company_resources->getCompanyServiceSource(["_account_resource"]);
        $service_account       = new base_service_company_account();
        $account_info          = $service_account->getAccountCompany($this->_userid, 'account_id,is_main,user_name,user_id,station,head_photo','main');
        $account_info          = base_lib_BaseUtils::array_key_assoc($account_info, 'account_id');
        $is_main_account       = $account_info[ $account_id ]['is_main'] == 1 ? true : false;
        $account_ids           = base_lib_BaseUtils::getPropertys($account_info, 'account_id');

        //简历点、刷新点、精品点...
        $service_consumeLog = new base_service_company_service_serviceConsumeLog();
        $res_llist          = $service_consumeLog->consumeStaticDo($account_ids, trim($s_time) . " 0:0:0", trim($e_time) . " 23:59:59");
        if (!empty($res_llist))
            $res_llist = base_lib_BaseUtils::array_key_assoc($res_llist, 'account_id');

        //推广金
        $service_spreadLog = new base_service_company_spread_spreadhistory();
        $spread_list       = $service_spreadLog->consumeStaticDo($account_ids, trim($s_time) . " 0:0:0", trim($e_time) . " 23:59:59");
        if (!empty($spread_list))
            $spread_list = base_lib_BaseUtils::array_key_assoc($spread_list, 'account_id');

        //视频使用分钟数
        $base_schoolnet_channel = new base_service_schoolnet_channel();
        $channel=$base_schoolnet_channel->getCompanyUsedRtcTimeByAccountIDs($this->_userid,$account_ids,0,trim($s_time) . " 0:0:0",trim($e_time) . " 23:59:59");
        if($channel){
            $channel = base_lib_BaseUtils::array_key_assoc($channel,"account_id");
        }
        foreach ($account_info as $_account_id => &$info) {
            $info['resume']  = $this->parseToAbs($res_llist[ $_account_id ]['resume']);
            $info['refresh'] = $this->parseToAbs($res_llist[ $_account_id ]['refresh']);
            $info['bouit']   = $this->parseToAbs($res_llist[ $_account_id ]['bouit']);
            $info['chat']    = $this->parseToAbs($res_llist[ $_account_id ]['chat']);
            $info['sms']     = $this->parseToAbs($res_llist[ $_account_id ]['sms']);

            $info['job_num'] = $company_resources->getJobCount([$_account_id]);  //职位数(免费+精品)
            $info['spread']  = number_format($spread_list[ $_account_id ]['spread'] >= 0 ? $spread_list[ $_account_id ]['spread'] : 0, 2);

            $info['has_head_photo'] = true;
            if (base_lib_BaseUtils::nullOrEmpty($info['head_photo'])) {
                $info['has_head_photo'] = false;
                preg_match_all('/./u', $info['user_name'], $names);
                $info['head_photo'] = isset($names[0][0]) ? $names[0][0] : '';
            }
            //头像缩略
            if (!empty($info['head_photo']) && $info["has_head_photo"]) {
                $info['head_photo'] = base_lib_BaseUtils::getThumbImg($info['head_photo'], 100, 100);
            }
            $info['video_num_use']="0分0秒";
            if($channel[$_account_id]['total_time']){
                $info['video_num_use']=self::format_time($channel[$_account_id]['total_time']);
            }
        }
        $res_last = $is_main_account ? $account_info : [$account_id => $account_info[ $account_id ]];
        $this->_aParams['list']                  = $res_last;
        $this->_aParams['is_main_account']                  = $is_main_account;
        $this->_aParams['isCqNewService']        = $pricing_resource_data['isCqNewService'];
        $this->_aParams['title']                 = "资源消耗统计";
        $this->_aParams['condition']['s_time']   = $s_time;
        $this->_aParams['condition']['e_time']   = $e_time;
        $this->_aParams['condition']['day_type'] = $day_type;

        if ($action == 'down_exce') {

            if (!$pricing_resource_data['isCqNewService']) {
                unset($this->index_tile['chat']);
                unset($this->index_tile['sms']);
            } else {
                unset($this->index_tile['bouit']);
            }

            foreach ($res_last as $_user) {
                $exl_list[] = [
                    'user_id'   => $_user['user_id'],
                    'user_name' => $_user['user_name'],
                    'station'   => $_user['station'],
                    'resume'    => $_user['resume'],
                    'spread'    => $_user['spread'],
                    'refresh'   => $_user['refresh'],
                    'bouit'     => $_user['bouit'],
                    'chat'      => $_user['chat'],
                    'sms'       => $_user['sms'],
                    'job_num'   => $_user['job_num'],
                    'video_num_use'   => $_user['video_num_use'],

                ];

                if (!$pricing_resource_data['isCqNewService']) {
                    unset($exl_list['chat']);
                    unset($exl_list['sms']);
                } else {
                    unset($exl_list['bouit']);
                }
            }

            $title = "{$s_time}-{$e_time}企业资源消耗统计";
            $this->createExcel($exl_list, $this->index_tile, $title);
            die;

        }

        return $this->render('./consumestatic.html', $this->_aParams);

    }

    /**
     * 导出excel    zhouwenjun 2016/12/29 14:58
     * @param array|string $table_data      数据 array|html(table格式)
     * @param          $rows                array('user'=>"用户",'name'=>"用户名")
     * @param string $title                 标题
     * @param bool $excel_table             直接使用excel table输出
     */
    function createExcel($table_data, $rows, $title = '导出excel', $excel_table = false) {
        if ($excel_table) {
            $this->SetExcelHeader($title, $title);
            die($table_data);
        }
        //数据太大了不建议使用 phpExcel导出数据,耗时太长
        if (count($table_data) > 2000) {
            $this->SetExcelHeader($title, $title);
            $this->SetExcelBody($table_data, $rows);
        } else {
            //			$this->load->library("PHPExcel/Classes/PHPExcel.php");
            //			new PHPExcel();
            //		$this->load->library("PHPExcel/Classes/PHPExcel/Writer/Excel5.php");
            $objexcel = SPHPExcel::CreatePHPExcel();
            $info_size = count($table_data);
            for ($i = 0; $i < count($rows); $i++) {
                $rows_value = array_values($rows);
                $rows_key = array_keys($rows);
                $pos = 0;

                //chr(65)==A Aasc码
                $objexcel->setActiveSheetIndex(0)->setCellValue(chr(65 + $i) . '1', strip_tags($rows_value[ $i ]));
                $objexcel->setActiveSheetIndex(0)->getColumnDimension(chr(65 + $i))->setWidth(15);
                $objexcel->getActiveSheet()->getStyle(chr(65 + $i) . '1')
                    ->applyFromArray(array (
                        'font'      => array ('bold' => true),
                        'alignment' => array (
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                        ),
                        'borders'   => array (
                            //							                 'allborders' => true,
                            'outline' => array (
                                'style' => PHPExcel_Style_Border::BORDER_THIN,   //设置border样式
                                //								                 'style' => PHPExcel_Style_Border::BORDER_THICK,  //另一种样式
                                'color' => array ('argb' => 'FF000000'),          //设置border颜色
                            ),
                        ),
                    ));
                if (!empty($table_data)) {
                    $table_data = array_values($table_data);
                    for ($j = 2; $j <= ($info_size + 1); $j++) {
                        //						$objexcel->getActiveSheet()->getStyle(chr(65 + $i))
                        //							->applyFromArray(array (
                        //								                 'alignment' => array (
                        //									                 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
                        //								                 ),
                        //							                 ));
                        $objexcel->getActiveSheet()->getStyle(chr(65 + $i) . $j)
                            ->applyFromArray(array (
                                'alignment' => array (
                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
                                ),
                                'borders'   => array (
                                    //							                 'allborders' => true,
                                    'outline' => array (
                                        'style' => PHPExcel_Style_Border::BORDER_THIN,   //设置border样式
                                        //								                 'style' => PHPExcel_Style_Border::BORDER_THICK,  //另一种样式
                                        'color' => array ('argb' => 'FF000000'),          //设置border颜色
                                    ),
                                ),
                            ));
                        $objexcel->setActiveSheetIndex(0)->setCellValue(chr(65 + $i) . $j, strip_tags($table_data[ $pos ][ $rows_key[ $i ] ]));
                        if ($pos < $info_size - 1) {
                            $pos++;
                        }
                    }

                }
            }
            ob_end_clean();
            header("content-type:application/vnd.ms-excel");
            header("Content-Transfer-Encoding: binary");
            header("content-disposition:attachment;filename={$title}.xls");
            header("Pragma: no-cache");
            $objwrite = new PHPExcel_Writer_Excel5($objexcel);
            $objwrite->save('php://output');
            exit;
        }
    }


    private function parseToAbs($num)
    {
        return $num <= 0 ? abs($num) : 0;

    }
    /**
     * 格式化分和秒
     * @param $all_time   总的秒数
     * @return 分和秒
     */
    public  static function format_time ($all_second)
    {
        $mark="";
        if($all_second<0){
            $mark="-";
            $all_second=abs($all_second);
        }
        $quotient = intval($all_second / 60);
        $remainder = $all_second % 60;

        return "{$mark}{$quotient}分{$remainder}秒";
    }

}