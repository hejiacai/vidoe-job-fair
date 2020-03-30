<?php
/**
 *
 * @ClassName 
 * @Desc 工作年限
 * @author ZhangYu
 * @date 2014-04-24
 *
 */
class company_service_workyear {
	
	private static $_data = array(
		'099' =>'应届生',
		'01'  => '1年',
		'02'  => '2年',
		'03'  => '3年',
		'04'  => '4年',
		'05'  => '5年',
		'06'  => '6年',
		'07'  => '7年',
		'08'  => '8年',
		'09'  => '9年',
		'10'  => '10年'
	);
	
	public function getWorkyear($workyear_id) {
		return self::$_data[$workyear_id];
	}

	public function getAll() {
		return self::$_data;
	}
}
?>