<?php
/** 
 * @ClassName mark.page.php
 * @Desc 标记
 * @author liukai@huibo.com
 * @date 2013-10-14 10:32:34
 */
class controller_mark extends components_cbasepage{
	function __construct(){
		 parent::__construct();
	}
	
	public function pagemarkdown($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));		
		$resume_id = base_lib_BaseUtils::getStr($path_data['resumeid'],'int',0);
		$operate = base_lib_BaseUtils::getStr($path_data['operate'],'string','down');
		//$user_name = base_lib_BaseUtils::getStr($path_data['resume_name'],'string','0');
		$server_resume = new base_service_person_resume_resume();		
		$resume = $server_resume->getResume($resume_id, "person_id");
		if($resume == null){
			return;
		}
		$person_id = $resume["person_id"];
		$server_person = new base_service_person_person();		
		$person = $server_person->getPerson($person_id, "user_name");
		if($person== null){
			return;
		}
		$user_name = $person["user_name"];		
		
		$service_companytag=new base_service_company_companytag();		
		$companytag = $service_companytag->getCompanyTagList($this->_userid, "tag_id,tag_name");
		
		$service_tag = new base_service_company_resume_resumecompanytag();
		$tag = $service_tag->getTag($this->_userid, $resume_id, "tag_name");		
		$this->_aParams['tag_items'] =$companytag->items;
		$this->_aParams['resume_id'] = $resume_id;
		$this->_aParams['user_name'] = $user_name;
		$this->_aParams['tag_name'] = $tag['tag_name'];
		$this->_aParams['operate'] = $operate;
		$this->_aParams["is_scroll"] = $companytag->pageSize>9;
		
		return $this->render('./resume/markdown.html', $this->_aParams);
	}
	
	public function pagemarkfav($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));		
		$resume_id = base_lib_BaseUtils::getStr($path_data['resumeid'],'int',0);
		$operate = base_lib_BaseUtils::getStr($path_data['operate'],'string','down');
		//$user_name = base_lib_BaseUtils::getStr($path_data['resume_name'],'string','0');
		$server_resume = new base_service_person_resume_resume();		
		$resume = $server_resume->getResume($resume_id, "person_id");
		if($resume == null){
			return;
		}
		$person_id = $resume["person_id"];
		$server_person = new base_service_person_person();		
		$person = $server_person->getPerson($person_id, "user_name");
		if($person== null){
			return;
		}
		$user_name = $person["user_name"];		
		
		$service_companytag=new base_service_company_companytag();		
		$companytag = $service_companytag->getCompanyTagList($this->_userid, "tag_id,tag_name");
		
		$service_tag = new base_service_company_resume_resumecompanytag();
		$tag = $service_tag->getTag($this->_userid, $resume_id, "tag_name");		
		$this->_aParams['tag_items'] =$companytag->items;
		$this->_aParams['resume_id'] = $resume_id;
		$this->_aParams['user_name'] = $user_name;
		$this->_aParams['tag_name'] = $tag['tag_name'];
		$this->_aParams['operate'] = $operate;
		$this->_aParams["is_scroll"] = $companytag->pageSize>9;
		
		return $this->render('./resume/markfav.html', $this->_aParams);
	}
	
	public function pageMarkDo($inPath){
		$path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));	
		$company_id = $this->_userid;
		$resume_id = base_lib_BaseUtils::getStr($path_data['resumeID'],'int',0);
		$tag_id = base_lib_BaseUtils::getStr($path_data['tagID'],'int',0);
		$tag_name = base_lib_BaseUtils::getStr($path_data['tagName'],'string','');	
		if(empty($tag_name)){
			$json_arr = array("isempty"=>true);
    		echo json_encode($json_arr);
    		return;
		}
		
		if(empty($resume_id)){
			$json_arr = array("error"=>"你要标记的简历不存在");
    		echo json_encode($json_arr);
    		return;
		}
		
		$tag['company_id'] = $company_id;
		$tag['resume_id'] = $resume_id;
		$tag['tag_id'] = $tag_id;
		$tag['tag_name'] = $tag_name;
		
		$servcie_tag =new base_service_company_resume_resumecompanytag();
		$servcie_tag->AddTag($tag);
		$json_arr["success"] = "添加标记成功";		
		echo json_encode($json_arr);
		return ;
		
	}
    
    /**
     *@desc 删除收藏 
     */
    public function pageDeleteTag($inPath){
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));	
        $tag_id = base_lib_BaseUtils::getStr($path_data["tag_id"],"int",0);
        if($tag_id == 0){
            $json_arr = array("error"=>"参数错误");
    		echo json_encode($json_arr);
    		return;
        }
        $service_tag = new base_service_company_companytag();
        
        $result = $service_tag->delete(array("tag_id"=>$tag_id));
        //删除添加标记表
        $servcie_tag =new base_service_company_resume_resumecompanytag();
        $servcie_tag->delete(array("tag_id"=>$tag_id));
        if($result === false){
            $json_arr = array("error"=>"删除失败");
    		echo json_encode($json_arr);
    		return;
        }
        $json_arr = array("success"=>"删除成功");
        echo json_encode($json_arr);
        return;
    }
}
?>