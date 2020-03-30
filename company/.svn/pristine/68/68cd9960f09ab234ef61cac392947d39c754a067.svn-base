<?php

/**
 * 员工共享管理中心
 * @desc   ： 员工共享管理中心 worksharmanage
 * @Date   : 2020/3/11 0011 上午 11:26
 * @author ：PengCG
 */
class controller_worksharmanage extends components_cbasepage
{
    /**
     * 构造函数
     */
    function __construct()
    {
        parent::__construct(true);
    }

    /**
     * 需求列表
     * @param $inPath
     * @return string
     */
    public function pageIndex($inPath)
    {
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $type      = base_lib_BaseUtils::getStr($path_data['type'], 'int', 1);//1:员工共享, 2:求共享
        $page      = base_lib_BaseUtils::getStr($path_data['page'], 'int', 1);
        $page_size = base_lib_BaseUtils::getStr($path_data['page_size'], 'int', 5);

        $service_workershare = new base_service_company_workershar_workershar();
        $list                = $service_workershare->getList($page, $page_size, $type, null, $this->_userid, "id,title,worker_require,worker_day,worker_introdece,check_state,check_reason");
        $res                 = $list->items;
        $workershar_ids      = base_lib_BaseUtils::getPropertys($res, 'id');
        $service_apply       = new base_service_company_workershar_wsharapply();
        $apply_static        = $service_apply->applyStatic($workershar_ids);
        $apply_static        = base_lib_BaseUtils::array_key_assoc($apply_static, 'wshar_id');
        foreach ($res as &$v) {
            $v['apply_num'] = (int)$apply_static[ $v['id'] ]['apply_num'];
        }

        $this->_aParams['pager'] = $this->pageBar($list->totalSize, $page_size, $page, $inPath);
        $this->_aParams['list']  = $res;
        $this->_aParams['type']  = $type;

        $return_html = '';
        switch ($type) {
            case 1:
                //员工共享
                $return_html = $this->render('./managementCenter.html', $this->_aParams);
                break;

            case 2:
                //求共享
                $return_html = $this->render('./seek_share.html', $this->_aParams);
                break;

            default:
                break;
        }

        return $return_html;

    }


    /**
     * 需求删除
     * @param $inPath
     */
    public function pageworksharDelete($inPath)
    {
        $path_data    = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $workshare_id = base_lib_BaseUtils::getStr($path_data['id'], 'int', 0);
        $ser_wshare   = new base_service_company_workershar_workershar();
        $ser_wshare->w_delete($workshare_id);

        exit(json_encode(['status' => true, 'msg' => '删除成功']));

    }

    /**
     * 咨询详情/投标详情
     * @param $inPath
     * @return string
     */
    public function pageDetails($inPath)
    {
        $path_data    = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $workshare_id = base_lib_BaseUtils::getStr($path_data['id'], 'int', 0);
        $page         = base_lib_BaseUtils::getStr($path_data['page'], 'int', 1);
        $page_size    = base_lib_BaseUtils::getStr($path_data['page_size'], 'int', 5);

        $service_workershare = new base_service_company_workershar_workershar();
        $workshare           = $service_workershare->selectOne(['id' => $workshare_id], "id,type,title,worker_require,worker_day");

        $service_apply     = new base_service_company_workershar_wsharapply();
        $list              = $service_apply->getList($page, $page_size, $workshare_id, $this->_userid, $workshare['type'], "apply_company_id,worker_require,phone,worker_introdece,create_time");
        $res               = $list->items;
        $service_company   = new base_service_company_company();
        $apply_company_ids = base_lib_BaseUtils::getPropertys($res, 'apply_company_id');
        $apply_companys    = $service_company->getCompanys($apply_company_ids, 'company_id,company_name');
        $apply_companys    = base_lib_BaseUtils::array_key_assoc($apply_companys, 'company_id');
        foreach ($res as &$v) {
            $v['company_name'] = $apply_companys[ $v['apply_company_id'] ]['company_name'];
            $v['create_time']  = date('Y.m.d H:i', strtotime($v['create_time']));
        }

        $this->_aParams['pager']       = $this->pageBar($list->totalSize, $page_size, $page, $inPath);
        $this->_aParams['list']        = $res;
        $this->_aParams['wshare_info'] = $workshare;


        $return_html = '';
        switch ($workshare['type']) {
            case 1:
                //员工共享

                $return_html = $this->render('consultation.html', $this->_aParams);
                break;

            case 2:
                //求共享

                $return_html = $this->render('tender_detail.html', $this->_aParams);
                break;

            default:
                break;
        }

        return $return_html;
    }

    /**
     * 需求编辑
     * @desc 逻辑执行
     * @param $inPath
     */
    public function pageworksharEditDo($inPath)
    {
        $pathdata       = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $id             = base_lib_BaseUtils::getStr($pathdata['id'], 'int', 0);
        $title          = base_lib_BaseUtils::getStr($pathdata['title'], 'string', '');
        $worker_require = base_lib_BaseUtils::getStr($pathdata['worker_require'], 'string', '');
        $worker_day     = base_lib_BaseUtils::getStr($pathdata['worker_day'], 'int', 0);
        $type           = base_lib_BaseUtils::getStr($pathdata['type'], 'int', 0);


        if (empty($title)) {
            echo json_encode(['status' => false, 'msg' => '请填写标题']);

            return;
        }

        if ($type == 1) {
            $worker_require_tip = '可共享员工';
            $worker_day_tip     = '可雇用时长';
        } else {
            $worker_require_tip = '急需人才';
            $worker_day_tip     = '需要时长';
        }

        if (empty($worker_require)) {
            echo json_encode(['status' => false, 'msg' => "请填写{$worker_require_tip}"]);

            return;
        }


        if (mb_strlen($title) > 30) {
            echo json_encode(['status' => false, 'msg' => '标题不多于30字']);

            return;
        }

        if (mb_strlen($worker_require) > 50) {
            echo json_encode(['status' => false, 'msg' => "{$worker_require_tip}不能超过50字"]);

            return;
        }

        if (!is_numeric($worker_day) || $worker_day<=0) {
            echo json_encode(['status' => false, 'msg' => "{$worker_day_tip}请填写大于0的整数"]);
            return;
        }

        if($worker_day>720){
            exit(json_encode(['status' => false, 'msg' => "{$worker_day_tip}最多720天"]));
        }

        if(empty($_REQUEST['worker_introdece_nohtml'])){
            exit(json_encode(['status' => false, 'msg' => "请填写详情介绍"]));
        }

        $data = [
            'title'            => $title,
            'worker_require'   => $worker_require,
            'worker_day'       => (int)$worker_day,
            'check_state'      => 0,
            'worker_introdece' => $_REQUEST['worker_introdece'],
        ];
        if ($id) {
            $data['id'] = $id;
        } else {
            //添加
            $data['company_id'] = $this->_userid;
            $data['is_effect'] = 1;
            $data['type'] = $type;
        }


        $service_company_workershar_workershar = new base_service_company_workershar_workershar();
        list($status, $id) = $service_company_workershar_workershar->saveData($data);

        echo json_encode(['status' => $status, 'msg' => $status === false ? '操作失败' : '操作成功']);

        return;
    }

    /**
     * 需求编辑
     * @desc 进入编辑页面
     * @param $inPath
     */
    public function pageworksharEdit($inPath)
    {
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $id       = base_lib_BaseUtils::getStr($pathdata['id'], 'int', 0);
        $type       = base_lib_BaseUtils::getStr($pathdata['type'], 'int', 1);


        if (!empty($id)) {
            $service_company_workershar_workershar = new base_service_company_workershar_workershar();
            $this->_aParams['work_share']          = $service_company_workershar_workershar->getWorkSharInfo($id);
            $type = $this->_aParams['work_share']['type'];
        }

        if ($type == 1) {
            $this->_aParams['worker_require_tip'] = '可共享员工';
            $this->_aParams['worker_day_tip']     = '可雇用时长';
            $this->_aParams['title']              = '共享员工';
        } else {
            $this->_aParams['worker_require_tip'] = '急需人才';
            $this->_aParams['worker_day_tip']     = '需要时长';
            $this->_aParams['title']              = '需求发布';
        }

        $this->_aParams['type'] = $type;
        return $this->render("workershar/edit.html", $this->_aParams);
    }

}