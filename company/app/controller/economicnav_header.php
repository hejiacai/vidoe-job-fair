<?php

    $base       = new components_cbasepage();
    $base->isLogin();
    $company_id = $base->_userid;
    $account_id = base_lib_BaseUtils::getCookie('accountid');

    $company_resources      = base_service_company_resources_resources::getInstance($company_id, true, base_lib_BaseUtils::getCookie('accountid'));
    $pricing_resource_data  = $company_resources->getCompanyServiceSource(["_account_resource"]);
    $is_show_consume_static = ($pricing_resource_data['isCqNewService'] || $pricing_resource_data['isNewService']) ? true : false;
    $this->assign('is_show_consume_static', $is_show_consume_static);
    $this->assign('_is_gray_test_company', $base->is_gray_company);

?>