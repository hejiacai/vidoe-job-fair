<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/22 0022
 * Time: 11:01
 */
class controller_specification extends components_cbasepage {

    function __construct() {
        parent::__construct();
    }

    //汇博招聘规范
    public function pageRecruitment(){

        echo $this->render('./specification/Recruitment.html');
    }

    //3招斩获5星面试评价
    public function pageStarAppraise($inPath){
        $pathdata = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
        $state = base_lib_BaseUtils::getStr($pathdata['state'], 'int', 0);
        $this->_aParams['state'] = $state;
        echo $this->render('./specification/starAppraise.html');
    }

}