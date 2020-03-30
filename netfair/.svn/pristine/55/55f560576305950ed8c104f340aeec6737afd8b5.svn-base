<?php


/**
 * 简历中心
 * @desc     ：独立网络招聘会
 * @ClassName: controller_resume
 * @Date     : 2020/3/3 0003 下午 1:33
 * @author   ：PengCG
 */
class controller_resume extends components_cbasepage
{

    private $_company_id;// 网络招聘会企业id
    private $_reusme_price = 1;//一个简历1视频点

    /**
     * 构造函数
     */
    function __construct()
    {
        parent::__construct(true);
        $this->_company_id = $this->_netfair_userid;
    }

    /**
     *
     * 简历详情
     * @param  $inPath
     */
    public function pageIndex($inPath)
    {
        $pathdata   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_id  = base_lib_BaseUtils::getStr($pathdata['resume_id'], 'int', 0);
        $sid        = base_lib_BaseUtils::getStr($pathdata['sid'], 'int', 0);
        $apply_id   = base_lib_BaseUtils::getStr($pathdata['applyid'], 'int', 0);//招聘会投递id
        $company_id = $this->_company_id;

        //获取简历详情
        $service_netfair_resume                = new base_service_netfair_resume();
        base_service_netfair_resume::$_cur_sid = $sid;
        $resume_info                           = $service_netfair_resume->getResumeDetail($resume_id, $company_id, $sid);
        if($resume_info=='e_2'){
            return $this->render('./404.html',['error_msg'=>'该简历不存在或已删除','title'=>'您好，您访问的简历详情暂时无法打开']);
        }
        if($resume_info=='e_1'){
            return $this->render('./404.html',['error_msg'=>'该简历不存在或已删除','title'=>'您好，您访问的简历详情暂时无法打开']);
        }

        $resume_info['isshowresumeinfo']       = true;
        $resume_info['title']                  = '简历详情-网络招聘会';
        $resume_info['resume_id']              = $resume_id;
        $resume_info['sid']                    = $sid;


        //聊一聊处理：搬用汇博的所有逻辑（完全一致）
        if ($resume_info['resume_site'] == 1 && $resume_info['company_site'] == 1) {
            $service_chat               = new company_service_chat(0, 0);
            $chat_params['resume_id']   = $resume_info['base_resume_id'];
            $chat_params['person_id']   = $resume_info['base_person_id'];
            $chat_params['company_id']  = $resume_info['base_company_id'];
            $resume_info['chat_status'] = $service_chat->getChatNoticeStatus($resume_info['base_company_id'], base_lib_BaseUtils::getCookie('accountid'), false, $chat_params, $resume_info['base_person_id']);

            $serviceArea = new base_service_company_service_serviceArea();
            //已超出简历下载地区限制，请联系工作人员开通相应招聘服务。
            $not_area_limit                = $serviceArea->IsServiceAreaTypeDownloadResumeScope($resume_info['base_company_id'], $resume_info['base_resume_id']);//限制为false
            $resume_info['not_area_limit'] = $not_area_limit;


            $is_show_linkway = false;
            $this->huibo_checkResume($resume_info['base_resume_id'], $is_show_name, $is_show_linkway, $member_info, $member_expires = false, $is_show_resumeinfo);
            $resume_info['huibo_show_linkway'] = $is_show_linkway;
        }

        //视频点资源
        $service_companynet              = new base_service_netfair_companynet();
        $companynet                      = $service_companynet->getCompanyEnterInfo($company_id, $sid, "id,video_point,video_point_use");
        $resume_info['video_point']      = $companynet['video_point'];
        $resume_info['video_point_use']  = $companynet['video_point_use'];
        $resume_info['video_point_last'] = $companynet['video_point'] > $companynet['video_point_use'] ? $companynet['video_point'] - $companynet['video_point_use'] : 0;
        $resume_info['resume_price']     = $this->_reusme_price;

        //applyid如果为空，看看企业是否有该简历的有效投递
        $apply_service = new base_service_netfair_personapplynet();
        if (empty($apply_id)) {
            $apply1_info = $apply_service->getPassOrWait($sid, $company_id, $resume_id, [0, 1, 2, 3, 4,5,6], 1, null);
            $apply_id    = $apply1_info[0]['apply_id'];
        }


        //根据投递id获取源职位id
        if (!empty($apply_id)) {
            $serv                       = new base_service_netfair_personapplynet();
            $applyinfo                  = $serv->getApplyInfo($apply_id, "job_id,status,create_time", 1);
            $ser_job                    = new base_service_netfair_job();
            $jobinfo                    = $ser_job->selectOne(['id' => $applyinfo['job_id']], 'base_job_id');
            $resume_info['base_job_id'] = $jobinfo['base_job_id'];
        }


        $apply1_info = $apply_service->getPassOrWait($sid, $company_id, $resume_id, [0, 1, 2, 4], 1, null);

        //是否在待面试或者复面列表
        $resume_info['is_in_wait_rewait'] = !empty($apply1_info) ? true : false;

        //是否属于初面待面试
        $apply2_info                      = $apply_service->getPassOrWait($sid, $company_id, $resume_id, [0,1], 1);
        $resume_info['is_wait_interview'] = empty($apply2_info) ? false : true;

        //是否是未接通列表： 如果是，同时获取了联系方式 展示二维码，否则不展示
        $resume_info['is_not_call'] = false;
        if(  $apply_id &&
            ($applyinfo['status'] == 5 || ( $applyinfo['status'] == 0 && $applyinfo['create_time']<date('Y-m-d 00:00:00') ))
        )
        {
            $resume_info['is_not_call'] = true;
            $resume_info['is_wait_interview'] = false;
            $resume_info['is_in_wait_rewait'] = false;
        }

        //是否发送offer
        $resume_info['is_send_offer'] = $applyinfo['status'] == 6 ? true : false;//和产品叶延生沟通，暂不做处理


        $service_fair                 = new base_service_netfair_net();
        $fair_info                    = $service_fair->getNetByID($sid, 'id,school_source,link_by,enter_phone_desc');
        $resume_info['school_source'] = $fair_info['school_source'];//招聘会平台类型
        $resume_info['link_by']       = $fair_info['enter_phone_desc'];//招聘会平台-联系人电话

        $resume_info['apply_id'] = $apply_id;

        return $this->render('./resume/resume_show.html', $resume_info);
    }


    /**
     *
     * 验证简历 - 汇博简历端的
     * @param  $resume_id
     * @param  $is_show_name
     * @param  $is_show_linkway
     * @param  $not_member
     * @param  $member_expires
     */
    private function huibo_checkResume($resume_id, &$is_show_name, &$is_show_linkway, &$member_info, &$member_expires, &$is_show_resumeinfo)
    {
        $service_company = new base_service_company_company();
        $service_person  = new base_service_person_person();
        $service_resume  = new base_service_person_resume_resume();
        $result          = true;
        $resume          = $service_resume->getResume($resume_id, 'resume_id,person_id');
        if (empty($resume)) {
            return false;
        }

        $person = $service_person->getPerson($resume['person_id'], 'name_open,user_name,user_name_en,open_mode');
        if (empty($person)) {
            return false;
        }


        $service_apply    = new base_service_company_resume_apply();
        $service_download = new base_service_company_resume_download();

        // 验证求职者是否投递过企业的职位
        $accountid         = base_lib_BaseUtils::getCookie('accountid');
        $this->_userid     = base_lib_BaseUtils::getCookie('userid');
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid, true, $accountid);
        $relate_resume_id  = empty($resume['relate_resume_id']) ? 0 : $resume['relate_resume_id'];
        if ($service_apply->isApply($company_resources->all_accounts, $resume['resume_id'])) {
            $is_show_linkway    = true;
            $is_show_name       = true;
            $is_show_resumeinfo = true;
        } else if ($service_apply->isApply($company_resources->all_accounts, $relate_resume_id)) {
            $is_show_linkway    = true;
            $is_show_name       = true;
            $is_show_resumeinfo = true;
        } else {
            $enum_openmode = new base_service_common_openmode();
            $downloads     = $service_download->getResumeDownload($this->_userid, $resume['resume_id'], 'down_time');
            if (round((strtotime($this->now()) - strtotime($downloads['down_time'])) / 3600) > 24) { //下载时间在24小时内，企业任可以查看
                if ($person['open_mode'] == $enum_openmode->notopen) {
                    return false;
                }
            }
        }

        $privilege       = $service_person->checkMobilesPrivilege(array($resume_id), $this->_userid);
        $is_show_linkway = $privilege[ $resume_id ];
        //如果是投递或者下载的简历 显示姓名
        $is_show_name = $privilege[ $resume_id ];

        return $result;
    }


    /**
     * 简历是否需要付费
     * @param $inPath
     */
    public function pageNeedPay($inPath)
    {
        $pathdata   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_id  = base_lib_BaseUtils::getStr($pathdata['resume_id'], 'int', 0);
        $sid        = base_lib_BaseUtils::getStr($pathdata['sid'], 'int', 0);
        $need_down  = base_lib_BaseUtils::getStr($pathdata['need_down'], 'bool', false);
        $company_id = $this->_company_id;
        $type       = base_lib_BaseUtils::getStr($pathdata['type'], 'string', 0);//video 需要特殊处理
        $apply_id   = base_lib_BaseUtils::getStr($pathdata['applyid'], 'int', 0);

        $service_netfair_resume = new base_service_netfair_resume();
        $is_need_pay            = $service_netfair_resume->isNeedPayResume($company_id, $resume_id, $sid);

        if (!$is_need_pay) {
            $resume = $service_netfair_resume->GetResumeDataByIds((array)$resume_id, "mobile_phone,person_id");
            $phone  = $resume[ $resume_id ]['mobile_phone'];

            //需要下载简历
            if ($need_down) {
                // 当前是否获下载了简历（或者获取了联系方式）
                $down_service = new base_service_netfair_resumedownload();
                $downs        = $down_service->getDowns($company_id, $resume_id, 'download_id', 90, 1);
                if (empty($downs)) {
                    $net_fair                = $service_netfair_resume->selectOne(['id' => $resume_id], 'netfair_person_id');
                    $data_down['company_id'] = $company_id;
                    $data_down['person_id']  = $net_fair['netfair_person_id'];
                    $data_down['resume_id']  = $resume_id;
                    $data_down['down_time']  = date('Y-m-d H:i:s');
                    $data_down['get_type']   = 2;
                    $data_down['ip']         = base_lib_BaseUtils::getIp(0);
                    $down_service->addDownload($data_down);
                }
            }

            if ($type == 'video' && empty($apply_id)) {
                $net_fair = $service_netfair_resume->selectOne(['id' => $resume_id], 'netfair_person_id');
                //如果是点击视频面试需要主动添加视频邀请
                $companynet_service = new base_service_netfair_companynet();
                $companynet         = $companynet_service->getNetCompany([$sid], $company_id, "sid,company_id");
                $job_id             = 0;//随便取一个jobid
                if (!empty($companynet)) {
                    $sids = base_lib_BaseUtils::getPropertys($companynet, 'sid');//当前企业参加的有效的招聘会

                    //获取 当前企业参加的有效的招聘会 中报名的职位
                    $jobnet_service = new base_service_netfair_jobnet();
                    $jobs           = $jobnet_service->getNetCompanyJob($company_id, $sids, "job_id");
                    if (!empty($jobs)) {
                        $job_ids = base_lib_BaseUtils::getPropertys($jobs, 'job_id');//有效的报名的职位

                        $job_service = new base_service_netfair_job();
                        $job_list    = $job_service->GetJobDataByIds($job_ids, "station,job_id");

                        foreach ($job_list as $k => $v) {
                            $job_list_res[] = $v;
                            if ($job_id == 0)
                                $job_id = $k;
                        }
                    }
                }

                $res      = $this->CreateApply($sid, $job_id, $net_fair['netfair_person_id'], $resume_id);
                $apply_id = $res['applyid'];
            }

        } else {
            //当前视频点
            $service_companynet = new base_service_netfair_companynet();

            $companynet                    = $service_companynet->getCompanyEnterInfo($company_id, $sid, "id,video_point,video_point_use");
            $companynet['video_point']     = (int)$companynet['video_point'];
            $companynet['video_point_use'] = (int)$companynet['video_point_use'];
        }

        exit(json_encode([
            'status'      => true,
            'msg'         => 'ok',
            'is_need_pay' => $is_need_pay,
            'resource'    => $companynet,
            'price'       => $this->_reusme_price,
            'phone'       => $phone,
            'apply_id'    => $apply_id,
        ]));
    }

    /**
     * 简历付费
     * @param $inPath
     */
    public function pagePayResumDo($inPath)
    {
        $pathdata  = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_id = base_lib_BaseUtils::getStr($pathdata['resume_id'], 'int', 0);
        $sid       = base_lib_BaseUtils::getStr($pathdata['sid'], 'int', 0);
        $type      = base_lib_BaseUtils::getStr($pathdata['type'], 'string', 0);//video 需要特殊处理
        $apply_id  = base_lib_BaseUtils::getStr($pathdata['applyid'], 'int', 0);


        $company_id = $this->_company_id;

        $service_netfair_resume = new base_service_netfair_resume();
        $resume                 = $service_netfair_resume->GetResumeDataByIds((array)$resume_id, "mobile_phone");
        $phone                  = $resume[ $resume_id ]['mobile_phone'];

        $service_companynet = new base_service_netfair_companynet();
        $companynet         = $service_companynet->getCompanyEnterInfo($company_id, $sid, "id,video_point,video_point_use");

        if ($companynet['video_point'] <= $companynet['video_point_use']) {
            exit(json_encode(['status' => false, 'msg' => '视频点不足', 'code' => 1]));
        }

        $res = $service_companynet->payVideopoint($company_id, $sid, 1);
        if (!$res['status']) {
            exit(json_encode(['status' => false, 'msg' => $res['msg'], 'code' => 2]));
        }

        $net_fair                = $service_netfair_resume->selectOne(['id' => $resume_id], 'netfair_person_id');
        $down_service            = new base_service_netfair_resumedownload();
        $data_down['company_id'] = $company_id;
        $data_down['person_id']  = $net_fair['netfair_person_id'];
        $data_down['resume_id']  = $resume_id;
        $data_down['down_time']  = date('Y-m-d H:i:s');
        $data_down['get_type']   = 1;
        $data_down['ip']         = base_lib_BaseUtils::getIp(0);
        $down_service->addDownload($data_down);

        if ($type == 'video' && empty($apply_id)) {
            //如果是点击视频面试需要主动添加视频邀请
            $companynet_service = new base_service_netfair_companynet();
            $companynet         = $companynet_service->getNetCompany([$sid], $company_id, "sid,company_id");
            $job_id             = 0;//随便取一个jobid
            if (!empty($companynet)) {
                $sids = base_lib_BaseUtils::getPropertys($companynet, 'sid');//当前企业参加的有效的招聘会
                //获取 当前企业参加的有效的招聘会 中报名的职位
                $jobnet_service = new base_service_netfair_jobnet();
                $jobs           = $jobnet_service->getNetCompanyJob($company_id, $sids, "job_id");
                if (!empty($jobs)) {
                    $job_ids = base_lib_BaseUtils::getPropertys($jobs, 'job_id');//有效的报名的职位

                    $job_service = new base_service_netfair_job();
                    $job_list    = $job_service->GetJobDataByIds($job_ids, "station,job_id");

                    foreach ($job_list as $k => $v) {
                        $job_list_res[] = $v;
                        if ($job_id == 0)
                            $job_id = $k;
                    }
                }
            }

            $res      = $this->CreateApply($sid, $job_id, $net_fair['netfair_person_id'], $resume_id);
            $apply_id = $res['applyid'];
        }

        exit(json_encode([
            'status'  => true,
            'msg'     => '联系方式获取成功',
            'code'    => 1,
            'applyid' => $apply_id,
            'phone'   => $phone
        ]));

    }


    /**
     * 企业邀请面试 主动创建申请
     * @desc pageStartInterview扩展的方法
     * @param type $inPath
     */
    private function CreateApply($sid, $job_id, $person_id, $resume_id)
    {
        $service_netfair_personapplynet = new base_service_netfair_personapplynet();
        $service_netfair_person         = new base_service_netfair_person();
        $service_person_resume          = new base_service_person_resume_resume();
        $apply                          = [];

        $data['sid']       = $sid;
        $data['job_id']    = $job_id;
        $data['person_id'] = $person_id;
        if (!$data['sid'] || !$data['job_id'] || !$data['person_id']) {
            return (['status' => false, 'msg' => '参数错误']);
        }

        $person = $service_netfair_person->GetPersonDataByIds([$data['person_id']], 'person_id,user_name,mobile_phone,source');
        $person = $person[ $apply['person_id'] ];

        if ($person['source'] == 1) {
            $default_resume = $service_person_resume->getDefaultResume($data['person_id'], 'resume_id');
            if (empty($default_resume)) {
                return (['status' => false, 'msg' => '求职者未创建简历，无法发起面试']);

            }
        }

        $apply_service = new base_service_netfair_personapplynet();

        if (empty($apply_id)) {
            $apply1_info = $apply_service->getPassOrWait($sid, $this->_netfair_userid, $resume_id, [
                0,
                1,
                2,
                3,
                4
            ], 1, null);//主动创建是因为 没得投递，业务逻辑又需要提供一个投递id，所以这里查询全部
            $apply_id    = $apply1_info[0]['apply_id'];
        }
        if (empty($apply_id)) {
            $data['source']      = 2;
            $data['is_effect']   = 1;
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['wait_time']   = $data['create_time'];
            $data['company_id']  = $this->_netfair_userid;
            $data['resume_id']   = $resume_id;
            $apply_id            = $service_netfair_personapplynet->insert($data);
        }

        return (['status' => true, 'msg' => '操作成功', 'applyid' => $apply_id]);
    }


    /**
     * 网络招聘会简历备注 弹框页面获取
     * @param $inpath
     * @return mixed
     */
    public function pageremarkhtml($inpath)
    {
        $pathdata             = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inpath));
        $in_data['resume_id'] = $resume_id = base_lib_BaseUtils::getStr($pathdata['resume_id'], 'int', 0);

        $company_id = $this->_company_id;

        $service_resumeremark = new base_service_netfair_resumeremark();
        $remarks              = $service_resumeremark->getResumeRemarkList($company_id, $resume_id, 5, 'remark_id,remark,update_time');
        $in_data['remarks']   = $remarks->items;

        return $this->render('./resume/remark.html', $in_data);
    }


    /**
     * 网络招聘会简历备注操作
     * @param $inpath
     * @return mixed
     */
    public function pageremarkdo($inpath)
    {
        $pathdata  = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inpath));
        $operate   = base_lib_BaseUtils::getStr($pathdata['operate'], 'string', '');
        $resume_id = base_lib_BaseUtils::getStr($pathdata['hidResumeId'], 'int', 0);
        if (!$operate || !$resume_id) {
            return json_encode(array('error' => '参数错误！'));
        }

        $company_id = $this->_company_id;

        $service_resumeremark = new base_service_netfair_resumeremark();
        switch ($operate) {
            case 'delete':
                //删除
                $remark_id = base_lib_BaseUtils::getStr($pathdata['remark_id'], 'int', 0);
                $remark    = $service_resumeremark->delResumRemark($company_id, $resume_id, $remark_id);
                if (!$remark) {
                    return json_encode(array('error' => '删除备注失败'));
                }
                break;
            case 'save':
                //保存
                $validator = new base_lib_Validator();
                $remark    = $validator->getStr($pathdata['taRemark'], 0, 30, '备注内容不能超过30字', true);
                if ($validator->has_err) {
                    return $validator->toJsonWithHtml();
                }
                $items     = [
                    'remark'      => $remark,
                    'company_id'  => $company_id,
                    'resume_id'   => $resume_id,
                    'update_time' => $this->now(),
                ];
                $remark_id = $service_resumeremark->addResumeRemark($items);
                if (!$remark_id) {
                    return json_encode(array('error' => '新增备注失败'));
                }
                break;
            default:
                return json_encode(array('error' => '备注操作失败'));
        }

        //获取备注数量
        $remark_list  = $service_resumeremark->getResumeRemarkList($company_id, $resume_id, null, 'remark_id')->items;
        $remark_count = count($remark_list);

        return json_encode(['success' => 'true', 'remark_id' => $remark_id, 'remark_count' => $remark_count]);
    }


    /**
     * 简历word下载
     */
    public function pageWordDown($inPath)
    {


        $company_id = $this->_company_id;

        $pathdata   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_ids = base_lib_BaseUtils::getStr($pathdata['resumeid'], 'array', null);
        $resume_ids = base_lib_BaseUtils::getIntArrayOrString($resume_ids);

        if (!empty($resume_ids)) {
            $ids     = $resume_ids;
            $dirPath = base_lib_Constant::SERVER_TEMP_FULL_FOLDER;
            $fileDir = "{$dirPath}/netfair_resumedown_company/{$company_id}/";
            array_map('unlink', glob("{$fileDir}*"));

            foreach ($ids as $resume_id) {
                //获取简历详情
                $service_netfair_resume = new base_service_netfair_resume();
                $resume_info            = $service_netfair_resume->getResumeDetail($resume_id, $company_id);

                if ($resume_info['has_get_linkway'] || $resume_info['show_linkway']) {
                    $person_name = $resume_info['user_name'];
                } else {
                    $sex_name    = $resume_info['sex'] == 1 ? '先生' : '女士';
                    $person_name = mb_substr($resume_info['user_name'], 0, 1, 'utf-8') . $sex_name;
                }


                $station = $resume_info['apply_list'][0]['station'];
                if ($station) {
                    $applystation = str_replace("/", "_", $station["$resume_id"]["station"]);
                    $fname        = "huibo_(应聘：{$applystation})_{$person_name}_{$resume_id}.doc";
                    $filename     = $fileDir . $fname;
                } else {
                    $fname    = "huibo_{$person_name}_{$resume_id}.doc";
                    $filename = $fileDir . $fname;
                }

                $html = $this->_writeResumeWord($resume_info);
                base_lib_BaseUtils::writeFile($filename, $html);
            }

            if (count($ids) == 1) {
                base_lib_BaseUtils::download($filename, $fname);
            } else if (count($ids) > 1) {
                $zip       = new base_lib_PHPZip();
                $date_name = date("Y-m-d");
                $filename  = "{$fileDir}简历{$date_name}.zip";
                $zip->ZipAndDownload($fileDir, "Huibo_简历_{$date_name}");
            }

            array_map('unlink', glob("{$fileDir}*"));
            rmdir($fileDir);
        }
    }


    /**
     * 简历PDF下载
     */
    public function pagePdfDown($inPath)
    {


        $company_id = $this->_company_id;

        $pathdata   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_ids = base_lib_BaseUtils::getStr($pathdata['resumeid'], 'array', null);
        $resume_ids = base_lib_BaseUtils::getIntArrayOrString($resume_ids);

        if (!empty($resume_ids)) {
            $ids     = $resume_ids;
            $dirPath = base_lib_Constant::SERVER_TEMP_FULL_FOLDER;
            $fileDir = "{$dirPath}/netfair_resumedown_company/{$company_id}/";
            array_map('unlink', glob("{$fileDir}*"));

            foreach ($ids as $resume_id) {
                //获取简历详情
                $service_netfair_resume = new base_service_netfair_resume();
                $resume_info            = $service_netfair_resume->getResumeDetail($resume_id, $company_id);

                if ($resume_info['has_get_linkway'] || $resume_info['show_linkway']) {
                    $person_name = $resume_info['user_name'];
                } else {
                    $sex_name    = $resume_info['sex'] == 1 ? '先生' : '女士';
                    $person_name = mb_substr($resume_info['user_name'], 0, 1, 'utf-8') . $sex_name;
                }


                $station = $resume_info['apply_list'][0]['station'];
                if ($station) {
                    $applystation = str_replace("/", "_", $station["$resume_id"]["station"]);
                    $fname        = "huibo_(应聘：{$applystation})_{$person_name}_{$resume_id}.pdf";
                    $filename     = $fileDir . $fname;
                } else {
                    $fname    = "huibo_{$person_name}_{$resume_id}.pdf";
                    $filename = $fileDir . $fname;
                }

                $html        = $this->_writeResumeHtml($resume_info);
                $filename    = "{$fileDir}/huibo_{$resume_id}.html";
                $outfilename = "{$fileDir}/huibo_{$resume_id}.pdf";
                base_lib_BaseUtils::writePDF($filename, $outfilename, $html, "huibo_{$person_name}_{$resume_id}.pdf");
            }

            if (count($ids) == 1) {
                base_lib_BaseUtils::download($outfilename, $fname);
            } else if (count($ids) > 1) {
                $zip       = new base_lib_PHPZip();
                $date_name = date("Y-m-d");
                $filename  = "{$fileDir}简历{$date_name}.zip";
                //$zip->Zip($fileDir,$filename);
                $zip->ZipAndDownload($fileDir, "Huibo_简历_{$date_name}");
                //base_lib_BaseUtils::download($filename,"Huibo_简历_{$date_name}.zip");
            }
            array_map('unlink', glob("{$fileDir}*"));
            rmdir($fileDir);

        }
    }

    /**
     *
     * html下载
     * @param  $inPath
     */

    public function pageHtmlDown($inPath)
    {

        $company_id = $this->_company_id;

        $pathdata   = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $resume_ids = base_lib_BaseUtils::getStr($pathdata['resumeid'], 'array', null);
        $resume_ids = base_lib_BaseUtils::getIntArrayOrString($resume_ids);

        if (!empty($resume_ids)) {
            $ids     = $resume_ids;
            $dirPath = base_lib_Constant::SERVER_TEMP_FULL_FOLDER;
            $fileDir = "{$dirPath}/netfair_resumedown_company/{$company_id}/";
            array_map('unlink', glob("{$fileDir}*"));

            foreach ($ids as $resume_id) {
                //获取简历详情
                $service_netfair_resume = new base_service_netfair_resume();
                $resume_info            = $service_netfair_resume->getResumeDetail($resume_id, $company_id);

                if ($resume_info['has_get_linkway'] || $resume_info['show_linkway']) {
                    $person_name = $resume_info['user_name'];
                } else {
                    $sex_name    = $resume_info['sex'] == 1 ? '先生' : '女士';
                    $person_name = mb_substr($resume_info['user_name'], 0, 1, 'utf-8') . $sex_name;
                }

                $station = $resume_info['apply_list'][0]['station'];
                if ($station) {
                    $applystation = str_replace("/", "_", $station["$resume_id"]["station"]);
                    $fname        = "huibo_(应聘：{$applystation})_{$person_name}_{$resume_id}.html";
                    $filename     = $fileDir . $fname;
                } else {
                    $fname    = "huibo_{$person_name}_{$resume_id}.html";
                    $filename = $fileDir . $fname;
                }
                $html = $this->_writeResumeHtml($resume_info);
                base_lib_BaseUtils::writeFile($filename, $html);
            }


            if (count($ids) == 1) {
                base_lib_BaseUtils::download($filename, $fname);
            } else if (count($ids) > 1) {
                $zip       = new  base_lib_PHPZip();
                $date_name = date("Y-m-d");
                $filename  = "{$fileDir}简历{$date_name}";
                $zip->ZipAndDownload($fileDir, "Huibo_简历_{$date_name}");
            }
            array_map('unlink', glob("{$fileDir}*"));
        }
    }


    private function _buildArray($arr, $filer = "resume_id")
    {
        if (!is_array($arr) || count($arr) <= 0) {
            return $arr;
        }
        foreach ($arr as $key => $value) {
            $new_arr[ $value["$filer"] ] = $value;
        }

        return $new_arr;
    }

    private function _writeResumeWord($resume_info)
    {
        ob_clean();
        $html = $this->_resumeword($resume_info);

        return $html;
    }

    private function _writeResumeHtml($resume_info)
    {

        ob_clean();
        $html = $this->_resumeHtml($resume_info);

        return $html;
    }

    /**
     *  简历world
     */
    private function _resumeword($resume_info)
    {
        return $this->render('./resume/word_template.xml', $resume_info);
    }

    /**
     *  简历html
     */
    private function _resumeHtml($resume_info)
    {

        return $this->render('./resume/html_template_new.html', $resume_info);
    }


}

?>

