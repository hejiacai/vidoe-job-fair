<?php
	$domainUrl = base_lib_Constant::MAIN_URL_NO_HTTP;
	$this->assign("domainUrl",$domainUrl);
	$cur_year = date('Y',time());
	$this->assign("year",$cur_year);
	$xml = SXML::load('../config/config.xml');
	if(!is_null($xml)){
		$this->assign("huibo_title",$xml->HuiBoSiteTitle);
	}
?>