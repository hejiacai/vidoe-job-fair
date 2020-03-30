<?php

/**
 * 微招聘入口
 * @desc     ：
 * @ClassName: controller_mrecruit
 * @Date     : 2019/5/24 0024 上午 10:17
 * @author   ：PengCG
 */
class controller_mrecruit extends components_cbasepage
{

    private $PAGE_ORDER = [1, 2, 3, 4, 5, 6];//所有需要设置的h5页面

    //页面风格
    private $PAGE_TEMPLATE;

    function __construct()
    {
        parent::__construct();
        $this->PAGE_TEMPLATE = [
            1 => ['id' => 1, 'pic_url' => base_lib_Constant::STYLE_URL . '/img/company/mrecruit/template1.jpg'],
            2 => ['id' => 2, 'pic_url' => base_lib_Constant::STYLE_URL . '/img/company/mrecruit/template2.jpg'],
            3 => ['id' => 3, 'pic_url' => base_lib_Constant::STYLE_URL . '/img/company/mrecruit/template3.jpg']
        ];

    }

    /**
     * h5分享链接页面
     * @param $inPath
     */
    public function pageIndex($inPath)
    {
        $path_data         = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $service_introduce = new base_service_company_mrecruit_introduce();
        $company_introduce = $service_introduce->getIntroduce($this->_userid);


        $this->_aParams['introduce']  = $company_introduce;
        $this->_aParams['page_style'] = $this->PAGE_TEMPLATE;
        $this->_aParams['cflag']      = base_lib_Rewrite::getFlag('company', $this->_userid);
        $this->_aParams['h5_url']     = str_replace('http:','',base_lib_Constant::APP_MOBILE_URL) . "/mrecruit/index/cflag-" . base_lib_Rewrite::getFlag('company', $this->_userid) . "-source-2";
        $this->_aParams['page_size']  = count(explode(',', $company_introduce['page_order']));

        return $this->render('./mrecruit/index.html', $this->_aParams);
    }

    /**
     * 编辑h5内容
     * @param $inPath
     */
    public function pageEdit($inPath)
    {
        $path_data         = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $service_introduce = new base_service_company_mrecruit_introduce();
        $company_introduce = $service_introduce->getIntroduce($this->_userid);

        if (!empty($company_introduce['page_order'])) {
            $_temp_page_order = explode(',', $company_introduce['page_order']);

            foreach ($this->PAGE_ORDER as $page) {
                if (!in_array((int)$page, $_temp_page_order)) {

                    $company_introduce['mouldMore'][] = $page;
                }
            }

        } else {
            $company_introduce['mouldMore'] = $this->PAGE_ORDER;
        }
        $company_introduce['mouldMore'] = implode(',', $company_introduce['mouldMore']);


        $this->_aParams['introduce']  = $company_introduce;
        $this->_aParams['page_style'] = $this->PAGE_TEMPLATE;


        //---------------企业简介、环境 编辑弹窗内容获取--------------
        $service_company = new base_service_company_company();
        $info            = $service_introduce->getIntroduce($this->_userid, 'info')['info'];
        $company         = $service_company->getCompany($this->_userid, '1', 'company_id,company_photo_count,info,company_bright_spot');
        $info            = $info ? $info : $company['info'];
        $info            = $info ? $info : "该公司暂无企业简介哦~";

        // 读取配置xml文件
        $xml = SXML::load('../config/company/company.xml');
        if (!is_null($xml)) {
            $photo_virt_path    = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $xml->VirtualName . '/' . $xml->PhotoFolder . '/' . $company['company_id'] . '/';
            $photo_thumb_suffix = $xml->PhotoThumbSuffix;
        }

        // 获取公司相册信息
         if ($company['company_photo_count'] > 0) {
            $service_companyphotoalbum = new base_service_company_companyphotoalbum();
            $companyphotoalbums        = $service_companyphotoalbum->getPhotoAlbumList($company['company_id'], 'photo_id,photo_path');
            $companyphotoalbum_items   = $companyphotoalbums->items;
            for ($i = 0; $i < count($companyphotoalbum_items); $i++) {
                $fileParts    = pathinfo($companyphotoalbum_items[ $i ]['photo_path']);
                $photo_list[] = $this->_userid . '/' . $fileParts['filename'] . $photo_thumb_suffix . '.' . $fileParts['extension'];
            }
        }

        $ser_upload                   = new base_service_upload_upload();
        $up_options                   = array(
            'file_name'      => 'hddNewPhotoName[]',
            'fileVal'        => 'Filedata',
            'auto'           => true,
            'is_load_jquery' => false
        );
        $up_options['defaults_files'] = $photo_list;

        $this->_aParams['up_img_html']  = $ser_upload->GetUploadHtmlDom($up_options, $this->_userid, 'up_style2', 'img', 'newPhoto', '/company/company.xml');
        $this->_aParams['info']         = $info;
        $this->_aParams['hidRightSpot'] = $company['company_bright_spot'];

        //埋点
        $service_company_mrecruit_companylog = new base_service_company_mrecruit_companylog();
        $service_company_mrecruit_companylog->addLog(base_lib_BaseUtils::getIp(), $this->_userid, 7);

        $this->_aParams['h5_url']     = str_replace('http:','',base_lib_Constant::APP_MOBILE_URL) . "/mrecruit/index/cflag-" . base_lib_Rewrite::getFlag('company', $this->_userid) . "-source-2";

        return $this->render('./mrecruit/edit.html', $this->_aParams);
    }

    /**
     * 微招聘 职位展示 翻页获取
     */
    public function pagegetSortJobspage($inPath)
    {
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $page      = base_lib_BaseUtils::getStr($path_data['page'], 'int', 0);
        $page_size = base_lib_BaseUtils::getStr($path_data['pagesize'], 'int', 3);

        $service_joborder = new base_service_company_mrecruit_pageorder();
        $job_service      = new base_service_company_job_job();
        $joborders        = $service_joborder->getCompanyOrder($this->_userid, 2, $page, $page_size)->items;
        if (!empty($joborders)) {
            foreach ($joborders as &$job) {
                $job['jobname'] = $job_service->getJob($job['job_id'], 'station')['station'];
            }
        }
        $this->_aParams['jobs'] = json_encode($joborders);

        return $this->render('./mrecruit/editjobsort.html', $this->_aParams);

    }

    /**
     * 微招聘 职位展示 翻页获取
     */
    public function pagegetSortJobs($inPath)
    {

        $path_data        = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $page             = base_lib_BaseUtils::getStr($path_data['page'], 'int', null);
        $page_size        = base_lib_BaseUtils::getStr($path_data['pagesize'], 'int', 3);
        $service_joborder = new base_service_company_mrecruit_pageorder();
        $job_service      = new base_service_company_job_job();
        $joborders        = $service_joborder->getCompanyOrder($this->_userid, 2, $page, $page_size)->items;
        if (!empty($joborders)) {
            foreach ($joborders as &$job) {
                $_job           = $job_service->getJob($job['job_id'], 'station,job_flag');
                $job['jobname'] = $_job['station'];
                $job['joburl']  = base_lib_Constant::MAIN_URL_NO_HTTP . "/zhaopin/zhiwei/{$_job['job_flag']}-resouce-3.html";
            }
        }
        exit($this->returnJson(true, 'ok', 0, ['jobs' => $joborders]));
    }

    /**
     * 预览保存 只保存 企业展示页面的顺序和模板风格
     */
    public function pageSaveEdit($inPath)
    {
        $path_data         = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $page_order        = base_lib_BaseUtils::getStr($path_data['page_order'], 'string', '');
        $tempid            = base_lib_BaseUtils::getStr($path_data['tempid'], 'int', null);
        $service_introduce = new base_service_company_mrecruit_introduce();
        $res               = $service_introduce->updateIntroduce($this->_userid, $tempid, null, $page_order);

        if ($res) {
            //生成模板埋点
            $service_company_mrecruit_templatelog = new base_service_company_mrecruit_templatelog();
            $service_company_mrecruit_templatelog->addLog(base_lib_BaseUtils::getIp(), $this->_userid, $tempid, 1);
            $msg = '保存成功';
        } else {
            $msg = '保存失败';
        }
        exit($this->returnJson($res, $msg));
    }

    /**
     * 音乐列表编辑
     * @param $inPath
     * @return mixed
     */
    public function pageMusicEdit($inPath)
    {
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

        //获取企业已保存的音乐列表
        $service_music = new base_service_company_mrecruit_mrecruitmusic();
        $list          = $service_music->getMusices($this->_userid)->items;

        //获取默认的几首音乐
        $defaults = $service_music->getDefaultMusics();

        //去重
        $selected_href = '';
        $defaults_key_href = base_lib_BaseUtils::array_key_assoc($defaults, 'href');
        foreach ($list as $k=>$v){
            if($v['is_select'] == 1){
                $selected_href = $v['href'];
            }
            if($defaults_key_href[$v['href']]){
                unset($list[$k]);
            }
        }



        //企业微招聘音乐文件上传配置
        $ser_upload = new base_service_upload_upload();
        $xml = SXML::load('../config/company/company.xml');
        if(!is_null($xml)){
            $licence_folder = $xml->MusicFolder;
            $licence_temp_folder = $xml->MusicTempFolder;
            $company_image_path = $xml->CompanyImagePath;
            $virtualName = $xml-> VirtualName;
            $file_max_size = $xml->MusicFileMaxSize;
            $ext = $xml->MusicFileExtensions;
        }
        $path ="{$virtualName}/{$licence_folder}";
        $photo_max_count = 20;
        $up_options = array ('file_name' => 'business_license[]', 'fileVal' => 'Filedata','is_load_jquery'=>false, 'auto' => true,'path'=>$path,'file_max_size'=>$file_max_size,'ext'=>$ext,'photo_max_count'=>$photo_max_count);
        $this->_aParams['licence_up_img_html'] = $ser_upload->GetUploadHtmlDom($up_options, $this->_userid, 'up_style2', 'img', 'report', '../config/company/company.xml');

        //返回数据封装
        $this->_aParams['list'] = $list;//默认列表
        $this->_aParams['default_list'] = $defaults;//上传的列表
        $this->_aParams['selected_href'] = $selected_href;//选择的音乐
        $this->_aParams['href_path'] = base_lib_Constant::UPLOAD_FILE_URL."/".$path;
        $this->_aParams['file_info'] = ['ext'=>$ext,'size'=>((int)$file_max_size)/1024];
        return $this->render('./mrecruit/music.html', $this->_aParams);

    }


    /**
     * 音乐上传
     */
    public function pagemusicupV2($inPath)
    {
        $pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
        $up_type  = 'img';
        $file     = $_FILES['videosup'];

        $service_music = new base_service_company_mrecruit_mrecruitmusic();

        $verify_data      = base_lib_Constant::SYSUSERKEY;
        $serv_askforleave = new base_service_upload_upload();
        $xml              = SXML::load('../config/company/company.xml');
        if (!is_null($xml)) {
            $licence_folder      = $xml->MusicFolder;
            $licence_temp_folder = $xml->MusicTempFolder;
            $company_image_path  = $xml->CompanyImagePath;
            $virtualName         = $xml->VirtualName;
            $file_max_size       = $xml->MusicFileMaxSize;
            $ext                 = $xml->MusicFileExtensions;
            $count               = $xml->MusicCount;
        }

        if ($service_music->getMusicCount($this->_userid) >= $count) {
            $this->ajax_data_json(ERROR, '不能超过' . $count . "首背景音乐.", []);
        }

        $in_data['path']            = "{$virtualName}/{$licence_folder}";
        $in_data['file_max_size']   = $file_max_size;
        $in_data['ext']             = $ext;
        $in_data['photo_max_count'] = $count;
        $in_data['is_folder_date']  = false;
        $arr                        = $serv_askforleave->UploadFile($file, $verify_data, $up_type, 'company', '../config/company/company.xml', $in_data);

        if ($arr['status'] == false) {
            $this->ajax_data_json(ERROR, $arr['msg'], $arr);
        }
        if ($up_type == 'file') {
            $arr['newname_path'] = $arr['name'] . "|" . $arr['newname_path'];
        }


        $service_music->insertMusic($this->_userid, mb_substr($arr['old_file_name'], 0, 15) .".". $arr['extension_name'], $arr['newname']);


        $this->ajax_data_json(SUCCESS, "上传成功", $arr);
    }

    public function pageSelectMusic($inPath)
    {
        $pathdata      = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
        $href          = base_lib_BaseUtils::getStr($pathdata['href'], 'string', 0);
        $service_music = new base_service_company_mrecruit_mrecruitmusic();

        $service_music->selectMusic($this->_userid, $href);
        exit($this->returnJson(true, 'ok'));

    }


    /**
     * 删除音乐
     */
    public function pagemusicdelV2($inPath)
    {
        $pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
        $href     = base_lib_BaseUtils::getStr($pathdata['href'], 'string');
        $xml      = SXML::load('../config/company/company.xml');
        if (!is_null($xml)) {
            $licence_temp_folder = $xml->MusicTempFolder;
            $virtualName         = $xml->VirtualName;
            $licence_folder      = $xml->MusicFolder;
        }

        $file                    = array();
        $file[]                  = "/" . $href;
        $postvar['file']         = "{$virtualName}/{$licence_folder}";
        $postvar['names']        = $file;
        $postvar['authenticate'] = 'report'; //必填项

        //默认的无法删除
        $service_music = new base_service_company_mrecruit_mrecruitmusic();
        $default_hrefs = base_lib_BaseUtils::getPropertys($service_music->getDefaultMusics(), 'href');
        if (in_array($href, $default_hrefs)) {
            exit($this->returnJson(false, '默认音乐无法删除'));
        }

        base_lib_Uploadfilesv::delFile($postvar);


        $service_music->deleteMusic($this->_userid, $href);

        exit($this->returnJson(true, '删除成功'));
    }

    /**
     * 禁用/启用 排序职位
     */
    public function pageSetStatus($inPath)
    {
        $path_data        = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $job_id           = base_lib_BaseUtils::getStr($path_data['jobid'], 'int', 0);
        $status           = base_lib_BaseUtils::getStr($path_data['status'], 'int', 0);
        $service_joborder = new base_service_company_mrecruit_pageorder();
        $deal_status      = $service_joborder->setStatus($this->_userid, $job_id, $status);
        $status           = $deal_status;
        if ($status) {
            $msg = '设置成功';
        } else {
            $msg = '设置失败';
        }

        exit($this->returnJson($status, $msg));
    }

    /**
     * 上移 下移 设置排序职位
     */
    public function pageSetOrder($inPath)
    {
        $path_data        = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $up_jobid         = base_lib_BaseUtils::getStr($path_data['new_up_jobid'], 'int', 0);
        $down_jobid       = base_lib_BaseUtils::getStr($path_data['new_down_jobid'], 'int', 0);
        $service_joborder = new base_service_company_mrecruit_pageorder();
        $deal_status      = $service_joborder->saveNewCompanyOrder($up_jobid, $down_jobid, $this->_userid);
        $status           = $deal_status;
        if ($status) {
            $msg = '设置成功';
        } else {
            $msg = '设置失败';
        }

        exit($this->returnJson($status, $msg));
    }

    /**
     * 是否可以分享改微企业链接
     * @desc 根据是否有职位展示
     */
    public function pageCanPub($inPath)
    {
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

        $service_joborder = new base_service_company_mrecruit_pageorder();
        $joborders        = $service_joborder->getCompanyOrder($this->_userid, 1, 1, 1)->items;

        if (empty($joborders)) {
            $status = false;
            $msg    = '暂无可展示的职位，无法分享';
        } else {
            $status = true;
            $msg    = '可以分享';
        }

        exit($this->returnJson($status, $msg));
    }


    /**
     * 是否可以分享改微企业链接
     * @desc 根据是否有职位展示
     *       3：公司福利
     *       4：高管团队
     *       5：核心产品/项目
     *       6：招聘岗位
     */
    public function pageisHaveData($inPath)
    {
        $path_data  = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $which_page = base_lib_BaseUtils::getStr($path_data['which_page'], 'int', 0);

        $service_introduce = new base_service_company_mrecruit_introduce();
        $service_introduce->CompositeData($data, $which_page, $this->_userid, 1, 1);

        $is_have = false;
        switch ($which_page) {
            case 3:
                if (!empty($data['rewards'])) {
                    $is_have = true;
                }
                break;
            case 4:
                if (!empty($data['list'])) {
                    $is_have = true;
                }
                break;
            case 5:
                if (!empty($data['list'])) {
                    $is_have = true;
                }
                break;
            case 6:
                if (!empty($data['joblist'])) {
                    $is_have = true;
                }
                break;
            default:
                $is_have = false;
                break;
        }


        if (!$is_have) {
            $status = false;
            $msg    = '无数据';
        } else {
            $status = true;
            $msg    = '有数据';
        }

        exit($this->returnJson($status, $msg));
    }


    /**
     * 获取 合成图片
     * http://m.hbs.com/mrecruit/index/cflag-ent5y2wf5c-source-2
     */
    public function pagegetPic($inPath)
    {
        $path_data         = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $page              = base_lib_BaseUtils::getStr($path_data['page'], 'int', 0);
        $tempid            = base_lib_BaseUtils::getStr($path_data['tempid'], 'int', 0);
        $save_temp         = base_lib_BaseUtils::getStr($path_data['save_temp'], 'bool', false);
        $service_introduce = new base_service_company_mrecruit_introduce();
        if (in_array($tempid, [1, 2, 3]) && $save_temp) {
            $service_introduce->updateIntroduce($this->_userid, $tempid);
        }

        $company_flag = base_lib_Rewrite::getFlag('company',$this->_userid);
        $url = base_lib_Constant::APP_MOBILE_URL."/mrecruit/index/cflag-{$company_flag}-source-2-tempid-{$tempid}";

        exit(json_encode(['status'=>true,'url'=>$url]));

//        $service_introduce->getPic($page, $this->_userid, $tempid, $save_temp);//http://m.hbs.com/mrecruit/index/cflag-ent5y2wf5c-source-2

    }

    /**
     * 编辑企业简介/环境
     * @desc D:\project\huibo\company\app\controller\company.page.php SavePhotoAndVideo 方法保存环境图片
     * @param $inPath
     */
    public function pageEditAboutUs($inPath)
    {
        $service_company   = new base_service_company_company();
        $service_introduce = new base_service_company_mrecruit_introduce();

        $info    = $service_introduce->getIntroduce($this->_userid, 'info')['info'];
        $company = $service_company->getCompany($this->_userid, '1', 'company_id,company_photo_count,info,company_bright_spot');
        $info    = $info ? $info : $company['info'];
        $info    = $info ? $info : "该公司暂无企业简介哦~";

        // 读取配置xml文件
        $xml = SXML::load('../config/company/company.xml');
        if (!is_null($xml)) {
            $photo_virt_path    = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP . '/' . $xml->VirtualName . '/' . $xml->PhotoFolder . '/' . $company['company_id'] . '/';
            $photo_thumb_suffix = $xml->PhotoThumbSuffix;
        }

        // 获取公司相册信息
        if ($company['company_photo_count'] > 0) {
            $service_companyphotoalbum = new base_service_company_companyphotoalbum();
            $companyphotoalbums        = $service_companyphotoalbum->getPhotoAlbumList($company['company_id'], 'photo_id,photo_path');
            $companyphotoalbum_items   = $companyphotoalbums->items;
            for ($i = 0; $i < count($companyphotoalbum_items); $i++) {
                $fileParts    = pathinfo($companyphotoalbum_items[ $i ]['photo_path']);
                $photo_list[] = $this->_userid . '/' . $fileParts['filename'] . $photo_thumb_suffix . '.' . $fileParts['extension'];
            }
        }


        $ser_upload                   = new base_service_upload_upload();
        $up_options                   = array(
            'file_name'      => 'hddNewPhotoName[]',
            'fileVal'        => 'Filedata',
            'auto'           => true,
            'is_load_jquery' => false
        );
        $up_options['defaults_files'] = $photo_list;

        $this->_aParams['up_img_html']  = $ser_upload->GetUploadHtmlDom($up_options, $this->_userid, 'up_style2', 'img', 'newPhoto', '/company/company.xml');
        $this->_aParams['info']         = $info;
        $this->_aParams['hidRightSpot'] = $company['company_bright_spot'];

        return $this->render('./mrecruit/editabout.html', $this->_aParams);
    }

    public function pageEditAboutUsdO($inPath)
    {
        $path_data         = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $validator         = new base_lib_Validator();
        $info              = $validator->getStr(base_lib_BaseUtils::getStr($path_data['info'], 'string', ''), 1, 1000, '企业简介1-1000字');
        $hidRightSpot      = $validator->getStr(base_lib_BaseUtils::getStr($path_data['hidRightSpot'], 'string', ''), 1, 50, '主营业务及行业地位 最多50字');
        $service_introduce = new base_service_company_mrecruit_introduce();

        if ($validator->has_err) {
            $errArr = $validator->err;

            return $this->jsonMsg(false, implode(',', $errArr));
        }

        $this->UpdateRightSpot($hidRightSpot);
        $res = $service_introduce->updateIntroduce($this->_userid, null, $info);

        if ($res) {
            $msg = '设置成功';
        } else {
            $msg = '设置失败';
        }
        exit($this->returnJson($res, $msg));

    }

    /**
     * 单独保存企业主营业务
     * @param $inPath
     */
    private function UpdateRightSpot($hidRightSpot)
    {
        $service_company = new base_service_company_company();
        $company         = $service_company->getCompany($this->_userid, 1, 'company_id,company_bright_spot');

        if ($company['company_bright_spot'] != $hidRightSpot) {
            //修改审核状态
            $service_brightspot = new base_service_company_brightspot();
            $service_brightspot->addCompanyAudit($this->_userid, 0);
        } else {
            return ((['status' => true, 'msg' => '企业主营业务保存存成功']));
        }
        $company_update["company_bright_spot"] = $hidRightSpot;
        $company_update["company_id"]          = $this->_userid;


        $result = $service_company->updateCompany($company_update, []);
        if ($result) {
            return ((['status' => true, 'msg' => '企业主营业务保存存成功']));
        } else {
            return ((['status' => false, 'msg' => '企业主营业务保存失败']));
        }
    }

    /**
     * 上传 企业环境 图片
     * @param $inPath
     */
    public function pageUploadEnvalop($inPath)
    {
        $pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
        $up_type  = base_lib_BaseUtils::getStr($pathdata['up_type'], 'string');
        $file     = $_FILES['Filedata'];

        $verify_data                 = base_lib_BaseUtils::getStr($pathdata['verify_data'], 'array');
        $serv_askforleave            = new base_service_upload_upload();
        $in_data['_last_foler']      = "/" . $this->_userid;
        $in_data['is_define_folder'] = true;//自定义 图片路径的 最后一级目录
        $arr                         = $serv_askforleave->UploadFile($file, $verify_data, $up_type, 'newPhoto', '/company/company.xml', $in_data);
        if ($arr['status'] == false) {
            $this->ajax_data_json(ERROR, $arr['msg'], $arr);
        }

        //缩略的保存
        $in_data['_last_foler']      = "/" . $this->_userid;
        $in_data['is_define_folder'] = true;//自定义 图片路径的 最后一级目录
        $in_data['file_name_define'] = str_replace([
            '.jpg',
            '.png',
            '.gif'
        ], 'thumb.' . trim($arr['extension_name'], '.'), $arr['newname']);
        $arr2                        = $serv_askforleave->UploadFile($file, $verify_data, $up_type, 'newPhoto', '/company/company.xml', $in_data);
        if ($arr2['status'] == false) {
            $this->ajax_data_json(ERROR, $arr['msg'], $arr2);
        }


        // 保存到数据库
        $service_companyphotoalbum = new base_service_company_companyphotoalbum();
        $item['company_id']        = $this->_userid;
        $item['photo_path']        = $arr['newname'];
        $item['photo_name']        = (string)rand(1000, 9999);
        $service_companyphotoalbum->addPhotoAlbum($item);


        $company_photos = $service_companyphotoalbum->getPhotoAlbumList($this->_userid, 'photo_id,photo_name,photo_path');
        $company_photo_items = $company_photos->items;
        $alr_photo_count = count($company_photo_items);
        $service_company = new base_service_company_company();
        $service_company->saveCompanyPhotoCount($this->_userid, $alr_photo_count);


        $this->ajax_data_json(SUCCESS, "上传成功", $arr);
    }

    /**
     *  删除 企业环境 图片
     */
    public function pageDelTempFile($inPath)
    {
        $pathdata                    = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
        $up_type                     = base_lib_BaseUtils::getStr($pathdata['up_type'], 'string');
        $verify_data                 = base_lib_BaseUtils::getStr($pathdata['verify_data'], 'array');
        $file                        = $_REQUEST['file_path'];
        $in_data['is_define_folder'] = true;//自定义 图片路径的 最后一级目录
        $in_data['_last_foler']      = "/" . $this->_userid;
        $serv_askforleave            = new base_service_upload_upload();
        $arr                         = $serv_askforleave->DelFile($file, $verify_data, $up_type, 'newPhoto', '/company/company.xml', $in_data);
        if (@$arr['status'] == false) {
            $this->ajax_data_json(ERROR, $arr['msg'], $arr);
        }

        $service_companyphotoalbum = new base_service_company_companyphotoalbum();
        $service_companyphotoalbum->delPhotoAlbumV2(str_replace('thumb', '', $_REQUEST['file_name']), $this->_userid);

        $company_photos = $service_companyphotoalbum->getPhotoAlbumList($this->_userid, 'photo_id,photo_name,photo_path');
        $company_photo_items = $company_photos->items;
        $alr_photo_count = count($company_photo_items);
        $service_company = new base_service_company_company();
        $service_company->saveCompanyPhotoCount($this->_userid, $alr_photo_count);


        $this->ajax_data_json(SUCCESS, "删除成功", $arr);
    }


    private function returnJson($status, $msg, $code = 0, $data = null)
    {
        return json_encode(['status' => $status, 'msg' => $msg, 'code' => $code, 'data' => $data]);
    }

    /**
     * 生成海报二维码地址
     */
    public function pagePostCode($inPath)
    {
        $path   = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
        $tempid = base_lib_BaseUtils::getStr($path['tempid'], 'string', 0);
        ob_clean();

        $cflag = base_lib_Rewrite::getFlag("company", $this->_userid);
        $url   = "http://".str_replace(['http:','//'],'',base_lib_Constant::MOBILE_URL) . '/mrecruit/index/tempid-' . $tempid . '-source-1-cflag-' . $cflag;

        SQrcode::png1($url, 5, 1);
    }
}

?>