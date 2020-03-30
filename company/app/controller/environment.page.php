<?php
	/** 
	 * @copyright 2004 www.huibo.com
	 * @name 企业环境
	 * @author huangwt
	 * @date 2014-11-10
	 *
	*/
	class controller_environment extends components_cbasepage {
		function __construct(){
			parent::__construct();
		}
		
        /**
        * 企业环境入口
        */
		public function pageIndex($inPath) {
                    //获取flag
                    $inData = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
                    $this->_aParams["flag"] = base_lib_BaseUtils::getStr($inData["flag"]);
                    $this->_aParams["step"] = base_lib_BaseUtils::getStr($inData["step"]);
                    $service_company =new base_service_company_company();
                    $company = $service_company->getCompany($this->_userid,'1','company_id,company_flag,company_name,property_id,size_id,area_id,calling_ids,address,postcode,homepage,info,hr_manager,hr_manager_sex,hr_tel,show_email,linkman,linkman_sex,open_linkman,linkman_station,link_tel,open_tel,fax,open_fax,link_mobile,open_mobile,company_logo_path,company_video_path,company_video_name,email,company_photo_count');
                    $this->_aParams['company'] = $company;
                    // 读取配置xml文件
                    $xml = SXML::load('../config/company/company.xml');
                    if(!is_null($xml)){
                            $max_photo_count = $xml->PhotoMaxCount;
                            $photo_virt_path = base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.'/'.$xml->VirtualName.'/'.$xml->PhotoFolder.'/'.$company['company_id'].'/';
                            $photo_thumb_suffix = $xml->PhotoThumbSuffix;
                            $singel_photo_count = $xml->PhotoSingelCount;
                            //logo
                            $logofolder = $xml->LogoFolder;
                            $virtualName = $xml-> VirtualName;
                    }

                    // 获取公司相册信息
                    $companyphotoalbum_items = array();
                    if($company['company_photo_count']>0){
                        $service_companyphotoalbum = new base_service_company_companyphotoalbum();
	                    $companyphotoalbums = $service_companyphotoalbum->getPhotoAlbumList($company['company_id'],'photo_id,photo_name,photo_path');
	                    $companyphotoalbum_items = $companyphotoalbums->items;
	                    for ($i = 0; $i < count($companyphotoalbum_items); $i++) {
	                            $fileParts = pathinfo($companyphotoalbum_items[$i]['photo_path']);
	                            $companyphotoalbum_items[$i]['photo_path'] = $fileParts['filename'].$photo_thumb_suffix.'.'.$fileParts['extension'];
	                    }
                    }

                    $logo_virt_path =  base_lib_Constant::YUN_ASSETS_URL_NO_HTTP.'/'.$virtualName.'/'.$logofolder.'/';
                    //已经上传
                    $alr_photo_count = count($companyphotoalbum_items);
                    //剩余上传
                    $rem_photo_count = $max_photo_count - $alr_photo_count;
                    $this->_aParams['title'] = '企业资料修改';
                    $this->_aParams['photo_albums'] = $companyphotoalbum_items;
                    $this->_aParams['alr_photo_count'] = count($companyphotoalbum_items);
                    $this->_aParams['singel_photo_count'] = $singel_photo_count;
                    $this->_aParams['photo_free'] = $rem_photo_count;
                    $this->_aParams['max_photo_count'] = $max_photo_count;
                    $this->_aParams['photo_virt_path'] = $photo_virt_path;
                    $this->_aParams['logo_virt_path'] = $logo_virt_path;
                     $this->_aParams['title'] = "企业环境修改";
                     
                    $this->_aParams['upload_cookie_userid']= $this->_userid;
                    $this->_aParams['upload_cookie_nickname']= $this->_username;
                    $this->_aParams['upload_cookie_usertype']= $this->_usertype;
                    $this->_aParams['upload_cookie_userkey']= base_lib_BaseUtils::getCookie('userkey');
                    $this->_aParams['upload_cookie_tick']= base_lib_BaseUtils::getCookie('tick');
					$this->_aParams["upload_cookie_accountid"] = base_lib_BaseUtils::getCookie('accountid'); 
                   return $this->render('sysmanage/environment.html', $this->_aParams);
                }


        /**
         * 上传 企业环境 图片 不保存到数据
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
            $arr['newname_path'] = $arr['newname'];
            $this->ajax_data_json(SUCCESS, "上传成功", $arr);
        }

        /**
         *  删除 企业环境 图片 不做数据库层 的操作
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


            $this->ajax_data_json(SUCCESS, "删除成功", $arr);
        }


        private function returnJson($status, $msg, $code = 0, $data = null)
        {
            return json_encode(['status' => $status, 'msg' => $msg, 'code' => $code, 'data' => $data]);
        }
    }
        
?>