<?php

/**
 * @ClassName controller_301
 * @Desc      301跳转
 * @author    yileilei@huibo.com
 * @date      2014-1-10 下午03:55:47
 *
 */
class controller_301 extends components_cbasepage {
	
	function __construct() {
		parent::__construct(false);
	}
	
	public function pageIndex($inPath) {
		$pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
		$route_name = $pathdata['route_name'];
		$url = '/';
		if (!base_lib_BaseUtils::nullOrEmpty($route_name)) {
			$route_config = require APP_ROOT . "../config/app/route.php";
			$APP_ROOT = "company";
			unset($_REQUEST['route_name']);
			if (isset($route_config[ $route_name ][ $APP_ROOT ])) {
				$partition = $_REQUEST ? '?' : '';
				$url = $route_config[ $route_name ][ $APP_ROOT ] . $partition . http_build_query($_REQUEST);
			}
		}
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' . $url);
		exit(0);
	}
}

?>