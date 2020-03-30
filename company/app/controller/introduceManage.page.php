<?php

/**
 * 高管介绍
 * Class introduceManage.page.php
 * User: zhouwenjun  2018/8/28 17:56
 */
class controller_introduceManage extends components_cbasepage {
	
	function __construct() {
		parent::__construct(true);
	}

    /**
     * 高管团队介绍首页
     * @author tanqiang 2018/8/29
     */
    public function pageIndex($inPath) {
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $this->_aParams["step"] = base_lib_BaseUtils::getStr($pathdata["step"]);
        $company_id = $this->_userid;

        $this->_aParams['introduce_list'] = $this->_getIntroduceListHtml($company_id);

        $ser_upload = new base_service_upload_upload();
        $up_options = array ('file_name' => 'hddNewPhotoName[]','fileVal' => 'Filedata', 'auto' => true, 'is_load_jquery' => false);
        $this->_aParams['up_img_html'] = $ser_upload->GetUploadHtmlDom($up_options, $this->_userid, 'up_style2', 'img', 'manage', '/company/company.xml');

        $server_do = new base_service_introduce_management();
        $introduceAmount = count($server_do->getListByCompanyId($company_id,'id')->items);

        $this->_aParams['introduceAmount'] = $introduceAmount;
        return $this->render('introduce/Manage/index.html', $this->_aParams);

    }

    //获取列表
    private function _getIntroduceListHtml($company_id){
        $item = 'id,company_id,name,position,details';
        $server_do = new base_service_introduce_management();
        $list = $server_do->getListByCompanyId($company_id, $item)->items;
        $introduce_ids = base_lib_BaseUtils::getProperty($list, 'id');

        $base_service_introduce_img = new base_service_introduce_img();
        $item1 = 'introduce_id,img_path';
        $imgs = $base_service_introduce_img->getListByIntroduceIds($introduce_ids, 2,$item1)->items;

        foreach ($imgs as $key => $v) {
            $newimg[ $v['introduce_id'] ][] = $v['img_path'];

        }

        $ser_upload = new base_service_upload_upload();
        $up_options = array ('file_name' => 'hddNewPhotoName[]', 'auto' => true);
        $ser_upload->GetUpConfig($path, $file_max_size, $ext, $photo_max_count, 'img', 'manage', '/company/company.xml');
        $in_data['img_path'] = base_lib_Constant::UPLOAD_FILE_URL.'/'.$path;

        foreach ($list as $key => $val) {
            $list[ $key ]['imgs'] = $newimg[ $val['id'] ];
        }
        $in_data['list'] = $list;
        return $html = $this->render('introduce/Manage/common.html', $in_data);
    }

    /**
     * 添加  / 编辑     tanqiang 2018/8/29 17:58
     */
    function pageIntroduceManageEdit($inPath) {
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $in_data['id'] = base_lib_BaseUtils::getStr($pathdata['id'], 'int');
        $base_service_introduce_management = new base_service_introduce_management();
        $base_service_introduce_img = new base_service_introduce_img();
        if ($in_data['id']){
            $introduceInfo = $base_service_introduce_management->getIntroduceInfoById($in_data['id'],'id,company_id,name,position,details');
            $imgs = $base_service_introduce_img->getListByIntroduceIds($introduceInfo['id'], 2,'introduce_id,img_path')->items;
            $in_data['introduceInfo'] = $introduceInfo;
        }

        $ser_upload = new base_service_upload_upload();
        $up_options = array ('file_name' => 'hddNewPhotoName[]', 'fileVal' => 'Filedata', 'auto' => true, 'is_load_jquery' => false);
        @$imgs and  $up_options['defaults_files']= base_lib_BaseUtils::getProperty($imgs,'img_path');
        $in_data['up_img_html'] = $ser_upload->GetUploadHtmlDom($up_options, $this->_userid, 'up_style2', 'img', 'manage', '/company/company.xml');
        $introduceAmount = count($base_service_introduce_management->getListByCompanyId($this->_userid,'id')->items);
        $in_data['introduceAmount'] = $introduceAmount;
        $re_html = $this->render('introduce/Manage/addintroduce.html', $in_data);

        return json_encode(array ('status' => true, 'msg' => '修改成功','re_html'=>$re_html));
    }

    public function pagePicture($inPath) {
        $pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
        $up_type = base_lib_BaseUtils::getStr($pathdata['up_type'], 'string');
        $file = $_FILES['Filedata'];

        $verify_data = base_lib_BaseUtils::getStr($pathdata['verify_data'], 'array');
        $serv_askforleave = new base_service_upload_upload();
        $arr = $serv_askforleave->UploadFile($file, $verify_data, $up_type, 'manage', '/company/company.xml');

        if ($arr['status'] == false) {
            $this->ajax_data_json(ERROR, $arr['msg'], $arr);
        }
        if ($up_type == 'file') {
            $arr['newname_path'] = $arr['name'] . "|" . $arr['newname_path'];
        }

        $this->ajax_data_json(SUCCESS, "上传成功", $arr);
    }

    /**
     * 添加高管团队介绍
     * @author tanqiang 2018/8/29
     */
    public function pageAddIntroduceManage($inPath) {
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $company_id = $this->_userid;
        $name = base_lib_BaseUtils::getStr($pathdata['introduce_name']);
        $position = base_lib_BaseUtils::getStr($pathdata['position']);
        $id = base_lib_BaseUtils::getStr($pathdata['id']);
        $details = base_lib_BaseUtils::getStr($pathdata['txtContent']);
        $pics = $pathdata['hddNewPhotoName'];   //图片文件
        if (empty($name)) {
            return json_encode(array ('status' => false, 'msg' => '姓名不能为空'));
        }
        if (empty($position)) {
            return json_encode(array ('status' => false, 'msg' => '职位不能为空'));
        }
        if (empty($details)) {
            return json_encode(array ('status' => false, 'msg' => '简介不能为空'));
        }
        if (empty($pics)) {
            return json_encode(array ('status' => false, 'msg' => '个人形象照不能为空'));
        }

        $server_do = new base_service_introduce_management();
        $base_service_introduce_img = new base_service_introduce_img();

        $items['company_id'] = $company_id;
        $items['name'] = $name;
        $items['position'] = $position;
        $items['details'] = $details;
        $items['is_effect'] = 1;
        if (empty($id)){
            //获取数量 不超过三个
            $introduceAmount = count($server_do->getListByCompanyId($company_id,'id')->items);
            if ($introduceAmount>=5){
                return json_encode(array ('status' => false, 'msg' => '添加人数不能超过5个'));
            }
            $ret = $server_do->addIntroduceManage($items);
            $data['is_effect'] = 1;
            $data['introduce_id'] = $ret;
            $data['introduce_type'] = 2;

            foreach ($pics as $val){
                $data['img_path'] = $val;
                $base_service_introduce_img->addIntroducImg($data);
            }
            if (!$ret) {
                return json_encode(array ('status' => false, 'msg' => '添加失败'));
                die;
            } else {
                //$introduce_list = $this->_getIntroduceListHtml($company_id);
                //$introduceAmount = count($server_do->getListByCompanyId($company_id,'id')->items);
                return json_encode(array ('status' => true,'data'=>$introduce_list, 'msg' => '添加成功','count'=>$introduceAmount));
            }
        }else{
            $ret = $server_do->updateManageById($items, $id);

            $re = $base_service_introduce_img->delImgByIntroduceId($id,2);

            $data1['is_effect'] = 1;
            $data1['introduce_id'] = $id;
            $data1['introduce_type'] = 2;

            foreach ($pics as $val){
                $data1['img_path'] = $val;
                $base_service_introduce_img->addIntroducImg($data1);
            }

            if (!$ret&&!$re) {
                return json_encode(array ('status' => false, 'msg' => '修改失败'));
                die;
            } else {
                $ser_upload = new base_service_upload_upload();
                $up_options = array ('file_name' => 'hddNewPhotoName[]', 'auto' => true);
                $ser_upload->GetUpConfig($path, $file_max_size, $ext, $photo_max_count, 'img', 'manage', '/company/company.xml');
                $in_data['img_path'] = base_lib_Constant::UPLOAD_FILE_URL.'/'.$path;
                $introduceInfo = $server_do->getIntroduceInfoById($id,'id,company_id,name,position,details');
                $imgs = $base_service_introduce_img->getListByIntroduceIds($introduceInfo['id'], 2,'introduce_id,img_path')->items;
                $introduceInfo['imgs'] = $imgs;
                $in_data['introduceInfo'] = $introduceInfo;
                $re_html = $this->render('introduce/Manage/editinfo.html', $in_data);
                return json_encode(array ('status' => true, 'msg' => '修改成功','re_html'=>$re_html));
            }
        }



    }

    /**
     * 删除临时照片（废弃）
     * @param array $inPath
     */
    public function pageDelTempFile($inPath) {
        $pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
        $up_type = base_lib_BaseUtils::getStr($pathdata['up_type'], 'string');
        $verify_data = base_lib_BaseUtils::getStr($pathdata['verify_data'], 'array');
        $file = $_REQUEST['file_path'];
        $serv_askforleave = new base_service_upload_upload();
        $arr = $serv_askforleave->DelFile($file, $verify_data, $up_type, 'img', '/company/company.xml');
        if (@$arr['status'] == false) {
            $this->ajax_data_json(ERROR, $arr['msg'], $arr);
        }

        $this->ajax_data_json(SUCCESS, "删除成功", $arr);
    }

    /**
     * 删除介绍
     * @author tanqiang 2018/8/29
     */
    public function pageDelIntroduceManage($inPath) {
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $id = base_lib_BaseUtils::getStr($pathdata['id']);

        $server_do = new base_service_introduce_management();
        $base_service_introduce_img = new base_service_introduce_img();
        $ret = $server_do->delManageById($id);
        $base_service_introduce_img->delImgByIntroduceId($id,2);
        if (!$ret) {
            return json_encode(array ('status' => false, 'msg' => '删除失败'));
            die;
        } else {
            /*$company_id = $this->_userid;
            $introduce_list = $this->_getIntroduceListHtml($company_id);
            $introduceAmount = count($server_do->getListByCompanyId($company_id,'id')->items);
            if ($introduceAmount==0){
                $in_data['introduceAmount'] = $introduceAmount;
                $ser_upload = new base_service_upload_upload();
                $up_options = array ('file_name' => 'hddNewPhotoName[]', 'fileVal' => 'Filedata', 'auto' => true, 'is_load_jquery' => false);
                @$imgs and  $up_options['defaults_files']= base_lib_BaseUtils::getProperty($imgs,'img_path');
                $in_data['up_img_html'] = $ser_upload->GetUploadHtmlDom($up_options, $this->_userid, 'up_style2', 'img', 'introduce', '/company/company.xml');

                $re_html = $this->render('introduce/Manage/addintroduce.html', $in_data);
            }*/
            return json_encode(array ('status' => true, 'msg' => '删除成功','introduce_list'=>$introduce_list,'count'=>$introduceAmount,'re_html'=>$re_html));
        }

    }

    function ajax_data_json($code = ERROR, $msg = "操作失败", $data = array ()) {
        // 返回JSON数据格式到客户端 包含状态信息
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array ('code' => $code ? $code : ERROR, 'msg' => $msg ? $msg : '操作失败', 'data' => $data)));
    }
}