<?php
/** 
 * @ClassName mobile_service_user
 * @Desc 用户
 * @author huangwt@huibo.com
 * @date 2015-01-12 11:06:13
 */
class company_service_user extends base_components_baseservice{
	
	protected $id;

	protected $marter_table = 'sys_users';

	protected $primary_key = 'user_id';

	public function __construct($id = 0){
		parent::__construct();
		$this->id = $id;
	}
	
	///获取后台员工列表
	public function getUserList($items){
		$condition = "is_effect='1'";
		return  $this->select($condition,$items);
	}

	
	//获取后台某个员工，将来会加缓存
	public function getUser($user_id,$items){
		return  $this->selectOne(array('user_id'=>$user_id),$items);
	}

	
	public function getUserByIDs($user_ids,$items){	
		if(is_array($user_ids)){
			$user_ids = implode(',', $user_ids);
		}
		$condition = "user_id in ({$user_ids})";
		return $this->select($condition,$items);
	}
	
	/**
	 * 获取取后台员信息根据员工编号
	 * @param  $user_id
	 */
	public function updateUser($user_id,$items){
		$condition = "user_id = {$user_id}";
		return $this->update($condition,$items);
	}
	
	//根据用户名来获取后台某个员工
	public function getUserByLoginId($login_id,$items){
		return  $this->selectOne(array('login_id'=>$login_id,'is_effect'=>'1'),$items);
	}
	
	/**
	 * 获取具有公司审核权限的人
	 */
	public function GetCompanyAuditPopedom(){
		$sql = "select distinct a.user_id from ( 
				select a.popedom_id,a.user_id,a.user_type from sys_popedom_user as a 
				left outer join sys_users as b on a.user_id=b.user_id
				where a.user_type='u' and b.is_effect='1'
				union 
				select a.popedom_id,c.user_id,a.user_type from sys_popedom_user as a 
				left outer join sys_user_group_detail as c on a.user_id=c.user_id
				left outer join sys_users as d on c.user_id=d.user_id 
				where user_type='g' and d.is_effect='1' and c.user_id is not null 
			) a 
			where a.popedom_id='companyFamousAudit' ";
			$this->setLimit(1);
			$user_id = $this->query($sql);
			return $user_id;
	}
	
	public function ModRewsumeDownNum($user_id,$resume_num){
		$month_first = date("Y-m-01");
		$sql = "update sys_users set resume_download_upperlimit ={$resume_num} 
		- (select count(*) from sys_resume_download  
		where down_time>'{$month_first}' and user_id = sys_users.user_id )
		where user_id in ({$user_id}) ";
		$result = $this->query($sql);
		return $result;
	}
	
	public function GetUserByDept($dept_id,$m_class,$items){
		$condition=array("is_effect='1'");
		array_push($condition,"dept_id = {$dept_id}");
		if(!base_lib_BaseUtils::nullOrEmpty($m_class)){
			array_push($condition,"not exists(select 1 from sys_dept_manager where dept_id=sys_users.dept_id and user_id=sys_users.user_id and m_class<={$m_class})");	
		}
		return $this->select($condition,$items);
	}
}

?>
