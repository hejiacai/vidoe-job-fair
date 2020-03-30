<?php

/**
 * 企业帮助中心
 * @desc   ：无需登录
 * @Date   : 2019/10/22 0022 上午 9:43
 * @author ：PengCG
 */
class controller_help extends components_cbasepage
{

    /**
     * 构造函数
     */
    function __construct()
    {
        parent::__construct(false);
    }


    public function pageIndex($in_path)
    {

        $pathdata  = base_lib_BaseUtils::saddslashes($this->getUrlParams($in_path));
        $key_wolrd = base_lib_BaseUtils::getStr($pathdata['key'], 'string', '');
        $catoryid  = base_lib_BaseUtils::getStr($pathdata['catoryid'], 'string', '');

        //问题类别
        $categorey_service = new base_service_company_helpcategory();
        $categorys         = $categorey_service->getList();

        $xml = SXML::load('../config/company/company.xml');
        if (!is_null($xml)) {
            $VirtualName = $xml->VirtualName;//虚拟目录名 CompanyImages
            $LogoFolder  = $xml->AdLogoFolder;// <!--logo广告图文件夹名-->
            $path        = $VirtualName . "/" . $LogoFolder;
        }

        if (!empty($categorys)) {
            foreach ($categorys as &$_catory) {
                $host = str_replace("http:",'',base_lib_Constant::YUN_ASSETS_URL);
				$_catory['logo_path'] = $_catory['logo_path'] ?  $host. "/" . $path . "/" . $_catory['logo_path'] : '';
            }
        }

        //问题
        $question_service = new base_service_company_helpquestion();
        $questions        = $question_service->getList($key_wolrd, "question_id,category_id,title,tips,content");


        //-----------招聘顾问----------
        $hasHRManager = false;
        $hrManager    = [];
        $xml          = SXML::load('../config/config.xml');
        if ($this->isLogin() && $this->_usertype == 'c') {
            $companyStateService = new base_service_company_comstate();
            $companyState        = $companyStateService->getCompanyState($this->_userid, "net_heap_id");
            $hrManager           = $this->GetHRManager($companyState["net_heap_id"]);
            //获取客服员
            $hasHRManager = false;
            if (!is_null($hrManager)) {
                $hasHRManager = true;
            }

            $service_company = new base_service_company_company();
            $site_type       = $service_company->getCompany($this->_userid, 1, 'site_type')['site_type'];
            if ($site_type == 5) {
                $hasHRManager           = true;
                $hrManager['mobile']    = '18523192707';
                $hrManager['qq']        = '2851501221';
                $hrManager['user_name'] = '任慧荣';
            }

        }

        $defaultHR['qq'] = "2851501279";
        //-----------招聘顾问----------


        //参数返回
        $this->_aParams['catoryid']     = $catoryid;
        $this->_aParams['key_wolrd']    = $key_wolrd;
        $this->_aParams['categorys']    = $categorys;
        $this->_aParams['questions']    = $questions;
        $this->_aParams['hasHRManager'] = $hasHRManager;
        $this->_aParams["hrManager"]    = $hrManager;
        $this->_aParams["defaultHR"]    = $defaultHR;
        $this->_aParams['huibo400']     = str_replace("", "", $xml->HuiboPhone400);

        return $this->render('./help/index.html', $this->_aParams);
    }


    private function GetHRManager($heap_id)
    {
        $companyHeapService = new base_service_company_netheap();
        $companyHeap        = $companyHeapService->GetNetHeapByID($heap_id, "own_man");
        $userInfor          = null;
        if (is_null($companyHeap) || !isset($companyHeap["own_man"])) {
            return $userInfor;
        }
        $userService = new base_service_crm_user();
        $userInfor   = $userService->GetUsers($companyHeap['own_man'], "user_name,head_photo_url,tel,mobile,qq");

        return $userInfor;
    }

}