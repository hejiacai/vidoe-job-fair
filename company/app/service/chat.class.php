<?php

/**
 *@Desc 腾讯聊一聊 类
 * 
 */
class company_service_chat{
    public $account_id          = "";//企业账号
    public $company_id          = "";//企业编号
    private $bind_person_id     = "";//绑定的求职者编号
    public $qcloud              = [
                                    "qcloud_identifier" => "",//腾讯云identifier
                                    "qcloud_usersig"    => "",//腾讯云usersig
                                    "qcloud_nickname"   => "",//腾讯云昵称
                                    "qcloud_photo"      => "",//腾讯云头像
                                ];
    const QCLOUD_APPID          = 1400039639;//APPID
    const QCLOUD_APPIDAT3RD     = 1400039639;//授权APPid
    const QCLOUD_ACCOUNTTYPE    = 15150;//账号类型
    public $is_test             = false;  //是否测试账号
    private static $_instance = [];
    
    public function __construct($account_id,$company_id) {
        if(empty($account_id) || empty($company_id)){
            return false;
        }
        
        $this->account_id = $account_id;
        $this->company_id = $company_id;
        //获取绑定信息
        $bind_data = $this->_getBindData($account_id,$company_id);
        if(!empty($bind_data)){
            $this->bind_person_id = $bind_data["person_id"];
        }
        $this->is_test = base_lib_Constant::RUNTIME == "pub" ? false : true;
        //初始化腾讯云消息
        $this->__initQcloud();
    }
    
    /**
     *@desc 获取绑定账号 
     */
    public function getBindPersonId(){
        return $this->bind_person_id;
    }
    
    /**
     *@desc 企业账号与HR绑定信息
     *@param $account_id 企业账号
     *@param $company_id 企业编号 
     */
    private function _getBindData($account_id,$company_id){
        if(empty($account_id) || empty($company_id)){
            return false;
        }
        $service_related = base_service_hractivity_related::getInstance();
        $related_info    = $service_related->getRelatedByAccount($account_id,"person_id,company_id");
        if($related_info["company_id"] != $company_id){
            return null;
        }
        return $related_info;
    } 
    
    
    
    /**
     *@desc 腾讯云消息 
     */
    private function __initQcloud(){
        $person_id  = $this->bind_person_id;
        $company_id = $this->company_id;
        $account_id = $this->account_id;
        
        if(empty($person_id)){
            return false;
        }
//        $service_company_rong                 = new base_service_company_app_rong();
//        list($rong_user_name,$rong_photo)     = $service_company_rong->getComapnyRongData($company_id,$account_id);
        $service_sqloud         = new Sqcloud("APPCOM",$this->is_test); 
        $qcloud_usersig         = $service_sqloud->getUserSig($person_id);
        list($user_name,$photo) = $service_sqloud->getComapnyRongData($company_id,$account_id);
        $qcloud_identifier      = $service_sqloud->getIdentifier($person_id);
        $qcloud_data = [
                        "qcloud_identifier" => $qcloud_identifier,
                        "qcloud_usersig"    => $qcloud_usersig,
                        "qcloud_nickname"   => $user_name,
                        "qcloud_photo"      => $photo,
                    ];
        $this->qcloud = $qcloud_data;
    }
    
    
    /**
	 * 工厂模式
	 * @param  integer $company_id 企业id
	 * @return object              实例对象
	 */
	public static function getInstance($account_id,$company_id) {
		if (is_null(self::$_instance[$account_id])) {
			self::$_instance[$account_id] = new company_service_chat($account_id,$company_id);
		}

		return self::$_instance[$account_id];
	}
    
    /**
     *@desc 注册求职者 
     */
    public function registerByMobile($mobile_phone,$need_bind = true){
        if(empty($mobile_phone)){
            return false;
        }
        $person['user_id']                      = '#'.$mobile_phone;
        $person['mobile_phone']                 = $mobile_phone;
        $person['mobile_phone_is_validation']   = '1';

        $person['password']     = base_lib_BaseUtils::md5_16("hb".$mobile_phone);
        $person['reg_source']   = 'app_hr_pc';
        $person['person_class'] = 1;
        $person["user_name"]    = "HR".time();
        $resume_id              = 0;

        //渠道
        $service_actionsource   = new base_service_common_actionsource();
        $service_person         = new base_service_person_person();
        $action_source          = $service_actionsource->account_bind;
        $person_id              = $service_person->addPerson($person, $operate,$action_source, $resume_id);
        if(empty($person_id)){
            return false;
        }
        if($need_bind){
            $service_related = base_service_hractivity_related::getInstance();
            $related_info    = $service_related->getRelatedByAccount($this->account_id,"person_id,company_id,account_id");
            if(!empty($related_info)){
                return false;
            }
            $related_result = $service_related->addRelated($person_id, $this->account_id, $this->company_id);
            if($related_result === false){
                return false;
            }
        }
        return $person_id;
    }
    
     /**
      *@desc 获取浏览器版本 
      */
    public function getbrowser(){
        $agent  = $_SERVER['HTTP_USER_AGENT'];
        $browser  = '';
        $browser_ver  = '';
 
        if (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $agent, $regs)) {
            $browser  = 'OmniWeb';
            $browser_ver   = $regs[2];
        }
 
        if (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $agent, $regs)) {
            $browser  = 'Netscape';
            $browser_ver   = $regs[2];
        }
 
        if (preg_match('/safari\/([^\s]+)/i', $agent, $regs)) {
            $browser  = 'Safari';
            $browser_ver   = $regs[1];
        }
 
        if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) {
            $browser  = 'IE';
            $browser_ver   = $regs[1];
        }
 
        if (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs)) {
            $browser  = 'Opera';
            $browser_ver   = $regs[1];
        }
 
        if (preg_match('/NetCaptor\s([^\s|;]+)/i', $agent, $regs)) {
            $browser  = '(Internet Explorer ' .$browser_ver. ') NetCaptor';
            $browser_ver   = $regs[1];
        }
 
        if (preg_match('/Maxthon/i', $agent, $regs)) {
            $browser  = '(Internet Explorer ' .$browser_ver. ') Maxthon';
            $browser_ver   = '';
        }
        if (preg_match('/360SE/i', $agent, $regs)) {
            $browser       = '(Internet Explorer ' .$browser_ver. ') 360SE';
            $browser_ver   = '';
        }
        if (preg_match('/SE 2.x/i', $agent, $regs)) {
            $browser       = '(Internet Explorer ' .$browser_ver. ') 搜狗';
            $browser_ver   = '';
        }
 
        if (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs)) {
            $browser  = 'FireFox';
            $browser_ver   = $regs[1];
        }
 
        if (preg_match('/Lynx\/([^\s]+)/i', $agent, $regs)) {
            $browser  = 'Lynx';
            $browser_ver   = $regs[1];
        }
 
        if(preg_match('/Chrome\/([^\s]+)/i', $agent, $regs)){
            $browser  = 'Chrome';
            $browser_ver   = $regs[1];
 
        }
 
        if ($browser != '') {
            $browser_ver = intval($browser_ver);
            return ['browser'=>$browser,'version'=>$browser_ver];
        } else {
            return ['browser'=>'unknow browser','version'=>'unknow browser version'];
        }

    }


    /**
     * 获取聊一聊点击提醒状态
     * $is_login_app:一周内是否登录app
     * $chat_params :resume_id,person_id,company_id
     * $person_id : 该字段说明：传了该字段表示要去查询这个个人是否7天内登录了app 同时 $is_login_app 传入的会变成无效
     * @desc 提示等级
     *          0:不提示，
     *          1：(未登录)提示
     *          2：（未登录）提示 + 提示消耗
     *          3：提示消耗
     */
    public function getChatNoticeStatus($company_id,$accountid,$is_login_app,$chat_params,$person_id = null){
        $company_resources = base_service_company_resources_resources::getInstance($company_id,true,$accountid);
        $is_need_fast_check = $company_resources->check_cq_setmel_chat($chat_params);


        if(!empty($person_id))
        {
            //求职者一周内是否登录过app
            $sercie_loginlog= new base_service_person_loginlog();
            $login_status = $sercie_loginlog->getLoginAppData((array)$person_id,7);
            if(!empty($login_status))
                $login_status = base_lib_BaseUtils::array_key_assoc($login_status,'person_id');
            $is_login_app = $login_status[$person_id];
        }

        if($is_login_app){
            if($is_need_fast_check)
                $chat_status = 3;
            else
                $chat_status = 0;//不需要弹窗
        }else{
            //未登录

            if($is_need_fast_check)
                $chat_status = 2;
            else{
                $chat_status = 1;
            }
        }

        return $chat_status;
    }
}