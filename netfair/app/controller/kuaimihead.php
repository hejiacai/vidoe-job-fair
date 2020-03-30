<?php
/**
 * 企业头部
 * @desc：企业通用头部
 * @Date: 2018/5/17 0017 上午 11:18
 * @author ：PengCG
 */
$basepage = new components_cbasepage(true);
$xml = SXML::load('../config/blue/company.xml');

//企业模块加载
$commmon_company_module = new base_service_common_blue_companymodule();
$company_modules = $commmon_company_module->getType();


//--------模板参数设置-----------
$_data['company_modules'] = $company_modules;
$this->assign('_head_data', $_data);

