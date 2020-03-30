<?php

if (!empty($_COOKIE['msgpopclose'])) {
	$this->assign('newmsg', null);
} else {
	$base = new base_components_basepage();

	$company_resources = base_service_company_resources_resources::getInstance($base->_userid);
	$nominate_service = base_service_company_nominate_nominate::getInstances();

	$newmsg = $nominate_service->checkNew($company_resources->all_accounts, $field="jobsort,COUNT(*) AS count");
	$items = ["01" => "互联网技术类", "05" => "销售类"];

	$total = 0;
	foreach ((array)$newmsg as $key => $msg) {
		$newmsg[$key]['name'] = $items[$msg['jobsort']];
		$total += $msg['count'];
	}

	$this->assign('total', $total);
	$this->assign('newmsg', $newmsg);
	$this->assign('uniqid', uniqid());
}
