<?php

/**
 * 企业offer管理
 * @desc   ：企业offer管理
 * @Date   : 2019/9/2 0002 上午 10:54
 * @author ：PengCG
 */
class controller_offermanager extends components_cbasepage
{

    function __construct()
    {
        parent::__construct(true);
    }

    /**
     * 管理列表
     * @param $inPath
     */
    public function pageIndex($inPath)
    {
        $pathdata  = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $page_cur  = base_lib_BaseUtils::getStr($pathdata['page'], 'int', 1);
        $page_size = base_lib_Constant::PAGE_SIZE;
        $is_send   = base_lib_BaseUtils::getStr($pathdata['is_send'], 'int', 0);
        $left_type = base_lib_BaseUtils::getStr($pathdata['left_type'], 'int', 1);
        $station   = base_lib_BaseUtils::getStr($pathdata['station'], 'string', '');
        $user_name = base_lib_BaseUtils::getStr($pathdata['name'], 'string', '');

        //时间选择
        $s_time = base_lib_BaseUtils::getStr($pathdata['s_time'], 'string', '');
        $e_time = base_lib_BaseUtils::getStr($pathdata['e_time'], 'string', '');
        if (!empty($s_time) && !empty($e_time) && $s_time > $e_time) {
            list($s_time, $e_time) = [$e_time, $s_time];
        }


        //---------搜索条件返回----------------
        $re_con['is_send'] = $is_send;
        $re_con['s_time']  = $s_time;
        $re_con['e_time']  = $e_time;
        $re_con['station'] = $station;
        $re_con['name']    = $user_name;


        //---------数据查询----------------
        $service_invite    = new base_service_company_resume_jobinvite();
        $company_resources = new base_service_company_resources_resources($this->_userid);
        $list_obj          = $service_invite->getOfferlist($page_size, $page_cur, $company_resources->all_accounts, $is_send, $station, $user_name, $s_time, $e_time);
        $list              = $list_obj->items;

        $offer_list = [];
        if (!empty($list)) {
            $invite_ids  = base_lib_BaseUtils::getPropertys($list, 'invite_id');
            $offer_svice = new base_service_company_job_offer();
            $offer_list  = $offer_svice->getListByCondition(false, 0, 0, $invite_ids, "offer_id,invite_id", "group by invite_id", 'order by create_time desc')->items;
            $offer_list  = base_lib_BaseUtils::array_key_assoc($offer_list, 'invite_id');
        }

        foreach ($list as &$v_list) {
            $v_list['is_offer_send']  = $v_list['offer_send_time'] ? 1 : 0;
            $v_list['send_time_show'] = $v_list['offer_send_time'] ? date('Y-m-d', strtotime($v_list['offer_send_time'])) : '';
            $v_list['offer_id']       = $offer_list[ $v_list['invite_id'] ]['offer_id'];
        }


        //---------发送邀请的职位列表----------------
        $jobs        = $service_invite->getInviteStationNew($company_resources->all_accounts)->items;
        $jobs_json[] = ["id" => "", "name" => "全部职位"];
        if (count($jobs) > 0) {
            foreach ($jobs as $json)
                $jobs_json[] = ["id" => $json['station'], "name" => $json['station']];
        }


        $this->_aParams['job_list']  = json_encode($jobs_json);
        $this->_aParams['pager']     = $this->pageBar($list_obj->totalSize, $page_size, $page_cur, $inPath);
        $this->_aParams['list']      = $list;
        $this->_aParams['left_type'] = $left_type;
        $this->_aParams['re_con']    = $re_con;

        if($this->is_gray_company)
            return $this->render('./offer/offermanager_gray.html', $this->_aParams);
        else
            return $this->render('./offer/offermanager.html', $this->_aParams);
    }


    /**
     * 企业查看offer
     */
    public function pageVisitOffer($inPath)
    {
        $pathdata  = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $invite_id = base_lib_BaseUtils::getStr($pathdata['invite_id'], 'int', 1);

        $offer_svice = new base_service_company_job_offer();
        $offer_svice->personVisitOffer('', $this->_aParams, $invite_id);


        if (empty($this->_aParams['offer']) || $this->_userid != $this->_aParams['offer']['company_id']) {

            return $this->render('./common/tipsmsg.html', ['msg' => "无权查看该录取通知书"]);
        }

        $this->_aParams['title'] = "录用通知书-汇博人才网";

        return $this->render('../../../slightPHP/basecore/templates/offer/index.html', $this->_aParams);
    }
}