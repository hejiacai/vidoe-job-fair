<?php
/**
 * @ClassName 
 * @Desc 刷新时间/登录时间枚举字段
 * @author arkfang
 * @date 2015-10-22
 */
class company_service_timelimit {
	
	private static $_data = array(
		'1'  => '今天',
		'3'  => '近3天',
		'7'  => '近7天',
		'30' => '近30天'
	);

	public function getAll() {
		return self::$_data;
	}
}
?>