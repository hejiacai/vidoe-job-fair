<?php
    require_once("./config.php");
    define('APP_ROOT',str_replace("\\",DIRECTORY_SEPARATOR,dirname(__FILE__)). DIRECTORY_SEPARATOR);
    SlightPHP::setDebug(false);
    SlightPHP::setAppDir("app");
    SlightPHP::setDefaultPage("index");
    SlightPHP::setDefaultEntry("index");
    SlightPHP::setUrlFormat("-");
    SlightPHP::setUrlSuffix("html");
    SlightPHP::setSplitFlag("-.");
    SlightPHP::setDefaultZone("controller");
    //SDb::setConfigFile(SlightPHP::$appDir . "/components/db.ini.php");
    SDb::setConfigFile("../db.forbidden/db.ini.php"); 
    if (false === ($res = SlightPHP::run())) {
            header('HTTP/1.1 404 Not Found');
            header('Status: 404 Not Found');
            $gui = new base_components_basepage();
    	    $config = SXML::load('../config/config.xml');
    	    echo $gui->render("../config/404.html", array('phone400'=>$config->HuiboPhone400));
    } else {
            echo $res;
    }
?>
