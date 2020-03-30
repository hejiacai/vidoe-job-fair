<?php
    $data['ip'] = base_lib_BaseUtils::getIp(0);   
	$im  = base_lib_BaseUtils::getCookie('is_mapping');
    $data['im']  = empty($im)?'false':'true';
    
	
    $this->assign('dataitem', $data);