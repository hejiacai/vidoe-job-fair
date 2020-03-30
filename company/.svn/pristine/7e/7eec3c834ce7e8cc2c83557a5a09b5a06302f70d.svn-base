<?php
// error_reporting(null);
//error_reporting(null);
error_reporting(E_ERROR | E_PARSE);
require_once("../slightPHP/SlightPHP.php");
define('PLUGINS_DIR',"../slightPHP/plugins");
//定义核心模块目录  a
define("BASECORE_DIR","../slightPHP/basecore");
define("COMPANY_DIR","app");
define("COMSITE_DIR","../comsite_admin/app");
function __autoload($class){
	if($class{0}=="S"){
		$file = PLUGINS_DIR."/$class.class.php";
	}elseif('company' == substr($class,0,7)){
		$file = COMPANY_DIR.'/'.str_replace('_','/',substr($class, 7)).'.class.php';	
	}else if('base' == substr($class,0,4)) {	//加载核心模块
		$file = BASECORE_DIR.'/'.str_replace('_','/',substr($class, 4)).'.class.php';
	}else if('cs' == substr($class,0,2)) {	//加载核心模块
		$file = COMSITE_DIR.'/'.str_replace('_','/',substr($class, 2)).'.class.php';
	}else{
		$file = SlightPHP::$appDir."/".str_replace("_","/",$class).".class.php";
	}
	//echo $file;
	//if(is_file($file)) return require_once($file);
	if(is_file($file))
		return require_once($file);
	else{
        $_class = strtolower($class);
        if (!(strstr($_class,'phpexcel') || substr($_class, 0, 16) === 'smarty_internal_' || $_class == 'smarty_security')) 
	    	SlightPHP::log($file . ' is not found');
	}
	//is_file会有缓存，但影响不大，会快很多倍
	//if(file_exists($file)) return require_once($file);
}
spl_autoload_register('__autoload');

ini_set("session.save_handler","redis"); 
ini_set("session.save_path","tcp://" . base_lib_Constant::REDIS_SERVER . "?database=9&timeout=2.5");
